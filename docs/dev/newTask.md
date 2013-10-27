create custom Tasks
=========================================

If you want to integrate a very special function into your workflow, this isn�t a big problem.
This could be done for a single system or inside a module you create.

Firstly you have to create the type inside the database.
This could be done with the Workflow2 Class.

```php
require_once("modules/Workflow2/Workflow2.php");
$objWorkflow = new Workflow2();
$objWorkflow->addType(
    "<slug>",
    "<classname>",
    "<file>",
    "<mainmodule>",
    <output>,
    <persons>,
    "<text>",
    "<category>",
    "<input>",
    "<styleClass>",
    "<backgroundimage>",
    "<singleModule>",
    "<supporturl>"
);
```

- **\<slug>** - *STRING* key of the task  
- **\<classname>** - *STRING* classname of the task  
- **\<file>** - *STRING* filename, which contains the task  
- **\<mainmodule>** - *STRING* module, which contain the task  
- **\<output>** - *ARRAY* output points of the task  
	array with sub-arrays  
	first element key, second label  
- **\<persons>** - *ARRAY* person input points  of the task  
	array with sub-arrays  
	first element key, second label  
- **\<text>** - *STRING* task, which is display below the task  
- **\<category>** - *STRING* category, which contain the task in administration  
- **\<input>** - *INT* have this point an input point? (have to be set to "1")  
- **\<styleClass>** - *STRING* css class, which will be assigned to the task block  
- **\<backgroundimage>** - backgroundimage of the task block  
- **\<singleModule>** - *ARRAY* or "" List of modules,  for which this task is valid  
- **\<supporturl>** - *STRING* - URL to a Support page for this task (Button in configuration)  

##### Examples 
```php
// request permission task
$objWorkflow->addType(
    "permission",
    "WfTaskPermission",
    "", // filename is empty, if file is from Workflow2 module
    "Workflow2",
    array( // 3 output points
        array("ok", "LBL_OK"),
        array("rework", "LBL_REWORK"),
        array("decline", "LBL_DECLINE")
    ),
    array( // one person point
        array("assigned_to", "Assigned to")
    ),
    "authorization Request",
    "flow",
    1,
    "",
    "task_permission",
    "",
    ""
);

// create new record
$objWorkflow->addType(
    "creator",
    "WfTaskCreator",
    "",
    "Workflow2",
    array(array("yes", "Weiter")),
    array(),
    "Create Record",
    "management",
    1,
    "",
    "task_creator",
    "",
    "http://shop.stefanwarnat.de/supporturl/"
);
```

A task could contain up to 4 files.

 - tasks/classfile.php
 - tasks/classfile.js (optional)
 - Smarty/templatefile.tpl (optional)
 - Smarty/statisticfile.tpl  (optional)
 
 
###### classfile.php

The PHP file of the task have to extend the Workflow_Task class from the Workflow2 Module. Here comes a blank file:

```php
<?php

require_once('modules/Workflow2/autoload_wf.php');

class WfTaskBlanktask extends WfTask {
	protected $_javascriptFile = "javascriptFILE.js";
	protected $_envSettings = array("environment_key", "env_key2");

	/**
	* function is called to execute the task
	* @param $context Workflow_VTEntity - Record, which has start the Workflow
	* @return string
	*/
	public function handleTask(&$context) {

		return "yes";
	}
	/**
	* function is called, before the configuration form is shown
	* @param $viewer Smarty Object
	* @return void
	*/
	public function beforeGetTaskform($viewer) {
		$viewer->assign("templatevar", "templatecontent");
	}

	/**
	* function is called, before configuration is saved
	* @param &$values configuration values
	* @return void
	*/
	public function beforeSave(&$values) {

	}
}
```

 - **$_javascriptFile** (optional)  

	If you set this value, you could define the name of the javascript file, which could be loaded automatically. It will be checked if this file exist and if yes, it will be included.

 - **$_envSettings** (optional)  

	Here you can set environment variables, which could be configured in the configuration form

 - **handleTask(VTEntity $context)**  

	This function is called to execute the task. It gets the $context Variable which is an Object of the Workflow_VTEntity class. This class contain all values of the record, which could be read with get(“fieldname”) and written with set(“fieldname”, “newvalue”)

	The return value of this function set the output point which will be executed after this task is done.
	If the task has two output points “yes”/”no” you could return “yes” or “no”. But you could be execute only one path.
	Also you could return an array to have additional features.
	At this moment you only could initiate a delay with the following return statement:
```php
	return array("delay" => time() + 1800, "checkmode" => "static");
```
	This waits 30 minutes (1800 secondes) and continue with the same task. The checkmode have to set to “static”. Later it will be possible to generate an dynamic delay until a specific fieldvalue.
	You could check if the task is continued with the $this->isContinued() call.  It returns true, if the delay was continued.

	You could quickly get a value from task configuration, if you use the default configuration handling with the $task variable. Only call $this->get(“configurationvariable”); and you get the config value. Also you could use $this->set(“configurationvariable, “newvalue”); to change a variable. But this change isn’t persistent. There could be any variable type, which could be serialized in json.

	You could save statistic/debug information, which is displayed into the statistic-PopUp of a single execution with $this->addStat(“String”);
	This could be used to debug your workflow, because every information will be stored together will a timestamp and the record.
	
###### Smarty/templatefile.tpl
If you have defined a “file” value in the task configuration, the filename of the templatefile will be converted from this value. (.php will be replaced with .tpl)

This is a Smarty template file, which is automatically inserted inside the configuration form. The Save Button/Form will be generated automatically. Also if you use input field with names like “task[configurationvariable]” this will be recognized and saved automatically. This values could be read with $this->get(…) [see above]
Also the task variable will be applied automatically to this task. So you could work with the $task variable in template.

###### Smarty/statisticfile.tpl
This file is inserted into the PopUp of the Statistic-View of a task. It will be loaded if you select a single user statistic.
	
	
Sample files:

[1. set Values](examples/WfTaskSetter.php?raw=true)  
[2. delay Task](examples/WfTaskDelay.php?raw=true)
