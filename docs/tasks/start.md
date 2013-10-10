Task: Start 
=========================================

This Task is the start of all workflows and this is allowed only once in a workflow.task_start

===== Options

- run time of this workflow
  *Should the workflow executed immediately or asynchronous (asynchronous creates a 1 second delay and execute the workflow with the next cronjob)*
- start trigger
  **You could use different options as trigger:**
	1. When a record is created
	2. Every time a record is saved
	3. Only manual execution
	4. Execute every time a mail is send to the record
	5. Execute every time a comment is created
- parallel execution   
	*If you use a [delay] or [set values] task, it could result in recursive execution, which could result in problems with the server*     
	You should only activate parallel execution if you know you need multiple instances of one Workflow per record
	
===== Environment variable Requests on before execution

- This values will be requested, if the workflow will be executed from the sidebar of a record (Otherwise these variables aren’t available!)
- The input are only available within custom functions

**Example:**

![configured start fields](/images/startfields.png)

This will request 3 variable on start, which will result in this form:

![configured start fields](/images/startfields2.png)

You could access these variables with the `$env["value"]["..."]` variable. In this example use

`$env["value"]["field_1"]   
$env["value"]["field_2"] # "on" if checked     
$env["value"]["field_3"]`

===== Trigger

- Start when a record is created
	- will be executed once every time a new Record is saved
- Start every time a record is saved
	- will be executed every time you save a record in the module
- Start only on manual execution
	- won’t be executed automatically
	- have to executed in sidebar or with the task “execute external Workflow”
- Start every time a mail is send to the record
	- This Trigger will be started if a new mail will be send to this record or an incoming mail will be related to an entry in the module
	- You could use the following environment variables inside a custom function to do something based on this values:
	-` $env["email"]["subject"] – contains subject    
	$env["email"]["content"] – contains mailtext    
	$env["email"]["from"] – contains sender    
	$env["email"]["to"] – contains destination`
-Start every time a comment is created
	- This Trigger will be executed if you write a new comment for an entry in the module