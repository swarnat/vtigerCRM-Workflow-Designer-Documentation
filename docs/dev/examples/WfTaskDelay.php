<?php
/**
 This File was developed by Stefan Warnat <vtiger@stefanwarnat.de>

 It belongs to the Workflow Designer and must not be distributed without complete extension
**/

require_once(realpath(dirname(__FILE__).'/../autoload_wf.php'));

class WfTaskDelay extends Workflow_Task {
    private $weekDays = array(
        "1" => "LBL_MONDAY",
        "2" => "LBL_TUESDAY",
        "3" => "LBL_WEDNESDAY",
        "4" => "LBL_THURSDAY",
        "5" => "LBL_FRIDAY",
        "6" => "LBL_SATURDAY",
        "7" => "LBL_SUNDAY",
    );

    private $sysDays = array(
        "1" => "monday",
        "2" => "tuesday",
        "3" => "WEDNESDAY",
        "4" => "THURSDAY",
        "5" => "FRIDAY",
        "6" => "SATURDAY",
        "7" => "SUNDAY",
    );

    public function calculateContinueTS($base_datetime) {
        $timestamp = $base_datetime;

        if($this->get("waitMin") == "1") {
            $waitMinValue = $this->get("waitMinValue");
            if(strpos($waitMinValue, '$') !== false) {
                $objTemplate = new VTTemplate($context);
                $waitMinValue = $objTemplate->render($waitMinValue);
            }

            switch($this->get("waitMinCat")) {
                case "minutes":
                    $timestamp += (60 * $waitMinValue);
                    break;
                case "hours":
                    $timestamp += (60 * 60 * $waitMinValue);
                    break;
                case "days":
                    $timestamp += (24 * 60 * 60 * $waitMinValue);
                    break;
                case "weeks":
                    $timestamp += (7 * 24 * 60 * 60 * $waitMinValue);
                    break;
            }
        }

        if($this->get("waitUntilMonthDay") == "1") {
            $value = $this->get("waitUntilMonthDayValue");
            $group = $this->get("waitUntilMonthDayGroup");

            if(strpos($value, '$') !== false) {
                $objTemplate = new VTTemplate($context);
                $value = $objTemplate->render($value);
            }

            switch($group) {
                case "next_month":
                    if(date("d", $timestamp) < $value) {
                       $timestamp = strtotime(date("Y-m-".$value));
                    } else {
                        $timestamp = strtotime(date("Y-m-".$value)." +1 month");
                    }
                    break;
            }
        }

        if($this->get("waitUntilWeekDay") == "1") {
            $weekdays = $this->get("waitUntilWeekDayValue");
            $today = date("N");
            for($a = $today == 7?1:$today + 1; $a <= 7; $a++) {
                if(in_array("".$a, $weekdays)) {
                    $timestamp = strtotime("next ".getTranslatedString(strtolower($this->sysDays[$a])), $timestamp);
                    break;
                }
                if($a == $today) break;
                if($a == 7) $a = 0;
            }
        }



        if($this->get("waitUntilTime") == "1") {
            // To set clocktime, i used php internal functions, to get a timestamp from datetime string
            $time = $this->get("waitUntilTimeHour").":".$this->get("waitUntilTimeMinutes").":00";

            // Check now if is after the needed time
            if(date("H:i:s", $timestamp) > $time) {
                $timestamp += (3600 * 24);
            }

            $timestamp = strtotime(date("Y-m-d ", $timestamp).$time);
        }

        return $timestamp;
    }
    /**
     * @param $context Workflow_VTEntity
     * @return array
     */
    public function handleTask(&$context) {
        $oldTimestamp = time();
        $dynamicUpdateBasefield = false;

        if($this->get("baseTime") == "now()") {
            $timestamp = time();
        } else {
            $baseTimeField = $this->get("baseTime");
            $timestamp =  strtotime($context->get($baseTimeField));

            if($this->get("update_basefield") == "1") {
                $dynamicUpdateBasefield = true;
            }
            if(empty($timestamp)) {
                $timestamp = time();
            }
        }

        if($this->isContinued()) {
            return "yes";
        }

        // Evtl. vorhandene Funktion auswerten. Nach der normalen Berechnung der Startzeit, da diese natürlich beachtet werden muss!
        if($this->get("checkWaitUntilFunction") == "1") {
            $parser = new VTWfExpressionParser($this->get("waitUntilFunction"), $context, false); # Last Parameter = DEBUG

            try {
                $parser->run();
            } catch(ExpressionException $exp) {
                Workflow2::error_handler(E_EXPRESSION_ERROR, $exp->getMessage(), "", "");
            }
            $newValue = $parser->getReturn();

            $timestamp = $newValue;
        }

        $timestamp = $this->calculateContinueTS($timestamp);

        if($timestamp != $oldTimestamp && $timestamp > $oldTimestamp) {
            if(!$dynamicUpdateBasefield) {
                return array("delay" => $timestamp, "checkmode" => "static");
            } else {
                return array("delay" => $timestamp, "field" => $baseTimeField, "checkmode" => "dynamic");
            }

        }

        return "yes";

    }

    public function beforeGetTaskform($viewer) {
        $weekDays = $this->weekDays;
        foreach($weekDays as $key => $value) {
            $weekDays[$key] = getTranslatedString($value, "Workflow2");
        }
        $viewer->assign("weekdays", $weekDays);

        $hours = range(1, 24);
        $viewer->assign("hours", $hours);

        $viewer->assign("minutes", range(0, 59));

        $datefields = VtUtils::getFieldsForModule($this->getModuleName(), array(5,6,23));

        $viewer->assign("datefields", $datefields);
    }

    public function onTaskFormSave() {

    }

    public function showStatistikForm($viewer) {
        global $adb;

        $class = CRMEntity::getInstance($this->getModuleName());
		$fieldname = $class->list_link_field;

		$sql = "SELECT columnname, tablename FROM vtiger_field WHERE tabid = ".getTabId($this->getModuleName())." AND fieldname = ?";
		$result = $adb->pquery($sql, array($fieldname));

		$columnName = $adb->query_result($result, 0, "columnname");
		$tableName = $adb->query_result($result, 0, "tablename");

        $sql = "SELECT vtiger_wf_queue.*, recordTBL.".$columnName." as title
                FROM vtiger_wf_queue
                    LEFT JOIN ".$tableName." as recordTBL ON (recordTBL.".$class->table_index." = vtiger_wf_queue.crmid)
                WHERE block_id = ".$this->_taskID;
        $result = $adb->query($sql, true);

        $waiting = array();



        while($row = $adb->fetch_array($result)) {
            $row["timestamp"] = VtUtils::formatUserDate(VtUtils::convertToUserTZ($row["timestamp"]));
            $row["nextsteptime"] = VtUtils::formatUserDate(VtUtils::convertToUserTZ($row["nextsteptime"]));
            $waiting[] = $row;
        }

        $viewer->assign("waiting", $waiting);
    }

}