Task: execute Expression with external Record
===============================================
<img src='../../images/task_exec_expression.png' align='right'>

If you want to **get values from a record**, which you don’t know and have to find with a individual condition, you should use this block.
This block could be used to execute every Expression you could create **in the context of this record**.
This means all simple variables ($id, $name, …) are loaded from the matched record.
Because of the condition, there could be also found multiple records. The expression will be executed for every record, independently.

You also could limit the number of result records and sort the records to only get the latest ones.

A Special feature are the environmental variable $env, which are also available in this custom expression. 
If you change values inside this $env variable, the new values will be written back to your main Workflow.

> **Example:** In this way you could get values from a single field of this record or save the ID for later use.

The second output could be used to execute some tasks if there couldn’t be found any records.

#### CAUTION!

Because of the freely configurable conditions, the Performance could be heavily slower. 
If you use this block for lots of operations, like the new Import Feature, you should ask your system administrator to set database indexes on the columns you filter. This will speed up the Process.