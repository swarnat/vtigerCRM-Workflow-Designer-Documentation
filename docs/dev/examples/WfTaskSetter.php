<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskSetter extends Workflow_Task
{
    protected $_javascriptFile = "WfTaskSetter.js";

    /**
     * @param $context Workflow_VTEntity
     */
    public function handleTask(&$context) {
        $objectCache = array();

        $setterMap = $this->get("setter");

        foreach($setterMap as $setter) {
            if(empty($setter["field"])) {
                continue;
            }

            if($setter["mode"] == "function") {
                $parser = new VTWfExpressionParser($setter["value"], $context, false); # Last Parameter = DEBUG

                try {
                    $parser->run();
                } catch(ExpressionException $exp) {
                    Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
                }

                $newValue = $parser->getReturn();
            } else {
                $setter["value"] = VTTemplate::parse($setter["value"], $context);

                $newValue = $setter["value"];
            }

            $this->addStat("Set '".$setter["field"]."' = '".$newValue."'");

            preg_match('/(\[([a-zA-Z0-9]*)((,(.*))?)\])|({(.*?)}}>)|\((\w+) ?: \(([_\w]+)\) (\w+)\)/', $setter["field"], $matches);
            if(count($matches) > 2) {
                if(!isset($objectCache[$matches[8]])) {
                    $objectCache[$matches[8]] = $context->getReference($matches[9], $matches[8]);
                }

                $targetContext = $objectCache[$matches[8]];
                $setter["field"] = $matches[10];
            } else {
                $targetContext = &$context;
            }

            $targetContext->set($setter["field"], $newValue);
        }

        foreach($objectCache as $object) {
            $object->save();
        }

        return "yes";
    }

    public function beforeGetTaskform($viewer) {

        WfTaskSetter::initSetterForm($viewer, $this, $this->getModuleName());

    }

    public function beforeSave(&$values) {

        unset($values["setter"]["##SETID##"]);

    }

    /**
     * @param $viewer
     * @param Workflow_Task $task
     */
    public static function initSetterForm(&$viewer, $task, $toModule, $fromModule = false, $additionalToFields = false, $options = false) {
        global $adb;
        if($fromModule === false) {
            $fromModule = $toModule;
        }
        if($options === false) {
            $options = array();
        }
        if(!isset($options["refFields"])) {
            $options["refFields"] = true;
        }

        /** Assigned Users */
        $sql = "SELECT id FROM vtiger_ws_entity WHERE name = 'Users'";
        $result = $adb->query($sql);
        $wsTabId = $adb->query_result($result, 0, "id");

        $sql = "SELECT id,user_name,first_name,last_name FROM vtiger_users WHERE status = 'Active'";
        $result = $adb->query($sql);
        while($user = $adb->fetchByAssoc($result)) {
            $user["id"] = $wsTabId."x".$user["id"];
            $availUser["user"][] = $user;
        }

        $sql = "SELECT id FROM vtiger_ws_entity WHERE name = 'Groups'";
        $result = $adb->query($sql);
        $wsTabId = $adb->query_result($result, 0, "id");

        $sql = "SELECT * FROM vtiger_groups ORDER BY groupname";
        $result = $adb->query($sql);
        while($group = $adb->fetchByAssoc($result)) {
            $group["groupid"] = $wsTabId."x".$group["groupid"];
            $availUser["group"][] = $group;
        }
        $viewer->assign("availUsers", $availUser);
        /** Assigned Users End */

        $fields = VtUtils::getFieldsWithBlocksForModule($toModule, $options["refFields"] == true ? true : false);
        if($additionalToFields !== false) {
            reset($fields);
            $firstKey = key($fields);
            foreach($additionalToFields as $addField) {
                $fields[$firstKey][] = $addField;
            }
        }
        $viewer->assign("fields", $fields);

        $viewer->assign("WfSetterToModule", $toModule);
        $viewer->assign("WfSetterFromModule", $fromModule);
        $viewer->assign("setterContent", $viewer->fetch(vtlib_getModuleTemplate("Workflow2","helpers/Setter.tpl")));

        $script = "var setter_values = ".json_encode($task->get("setter")).";\n";
        $script .= "var available_users = ".json_encode($availUser).";\n";
        $script .= "var WfSetterToModule = '".$toModule."';\n";
        $script .= "var WfSetterFromModule = '".$fromModule."';\n";
        $script .= "var WfSetterOptions = ".json_encode($options).";\n";

        $task->addInlineJs($script);
    }
}
