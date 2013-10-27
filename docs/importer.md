Importer
==============================
In Verison 1.87 a new feature was integrated, which will help you to Import csv-Files and do individual tasks with entries from the file.

The basic idea behind the system is to create a system, which could be used to import data in every possible csv-format. That means the csv don't need to have a default structure like this:  
row1 => record1  
row2 => record2  
row3 => record3  

You could create a process which could handle the following structure (Only an example):  
row1 => record1 basic data  
row2 => additional information to record1 (products, ...)  
row3 => additional information to record1  
row4 => record2 basic data  
...

You only have to implement this process in the Workflow Designer. (At the moment I only have a far idea how this could be implemented, but it should be possible.)

**Please following the following steps to use this feature: (You could integrate other tasks, but this should be the basic structure)**

1. At first you have to create a Workflow with the Trigger "Import Process". This makes the Workflow invisible in situations, different than import.
2. After you set the Trigger to "Import Process" you have to reload the Workflow Designer Page, because there will be additional tasks available. (CSV Import)
3. you should create and configure a task "get next line from CSV"
4. create a task “Import finished” and connect the bottom output from the next line task. This path will execute if the file was completely imported
5. easiest way: create a task "execute expression with external record" to check if there is a record in your crm, which have to be changed by the CSV values (or directly create a record, ...)
6. Use the two functions wf_setField() and wf_saveRecord() to directly interact with the record you found.

I have created an example which will reflect this steps and search a Contact with the email from column1 and set the "title" field to the value from column 2.

Download: [Example Importer](../examples/import_workflow.bin?raw=true)