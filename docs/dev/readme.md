Developers Documentation
=========================================

There are several different ways you could extend the Workflow Designer with functions only you want to have.

[1. new Tasks](https://github.com/swarnat/vtigerCRM-Workflow-Designer-Documentation/blob/master/docs/dev/newTask.md)

#### add function to expressions
Sometimes it is easier to implement a custom function you want to use directly into PHP, because the integrated Expression Interpreter has some security limitations, which will limit the functions.
This can be done very easy and upgrade-safe, which means the functions will persists after any upgrade of the Extension.

Go to the following directory in your vtigerCRM: **modules/Workflow2/functions/**
Here you can create a new file, named by the category or company you want to create. Important is the file extension “.inc.php”
The Workflow Designer will automatically load all of these files in every Workflow you execute. Therefore, it is important to double check the code, because an error could probably lead into critical problems with Workflows.
You can read examples in the **core.inc.php**, which includes some basic functions.