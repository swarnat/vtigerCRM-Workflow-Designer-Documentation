create custom Tasks
=========================================

If you want to integrate a very special function into your workflow, this isn’t a big problem.
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

**<slug>** – STRING key of the task
**<classname>** – STRING classname of the task
**<file>** – STRING filename, which contains the task
**<mainmodule>** – STRING module, which contain the task
**<output>** – ARRAYoutput points of the task
	array with sub-arrays
	first element key, second label
**<persons>** - ARRAY person input points  of the task
	array with sub-arrays
	first element key, second label
**<text>** – STRING task, which is display below the task
**<category>** – STRING category, which contain the task in administration
**<input>** – INT have this point an input point? (have to be set to “1?)
**<styleClass>** – STRING css class, which will be assigned to the task block
**<backgroundimage>** – backgroundimage of the task block
**<singleModule>** – ARRAY or “” List of modules,  for which this task is valid
**<supporturl>** – STRING - URL to a Support page for this task (Button in configuration)