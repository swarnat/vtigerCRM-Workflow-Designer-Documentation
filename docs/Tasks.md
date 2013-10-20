Tasks
=========================================

[Start](docs/tasks/start.md)  

##### Order management  
[add Product](docs/tasks/add-product.md)  
[convert to Invoice](docs/tasks/convert-to-invoice.md)  
[Create Invoice/Quote/...](docs/tasks/create-inventory.md)  

##### record management  
create comment  
[Create Record](docs/tasks/create-record.md)  
[Duplicate Record](docs/tasks/duplicate-record.md)  
[Set Value](docs/tasks/set Value.md)  
create Task
create Event
remove Record

##### flow control  
[authorization Request](docs/tasks/manual-permission.md)  
[Delay](docs/tasks/delay.md)  
[exist related Record](docs/tasks/exist-related-record.md)  
[redirect User to a URL](docs/tasks/redirect-user.md)  
condition  
run external workflow
check MySQL query
global Search

##### communication  
[Send Mail](docs/tasks/send-mail.md)  

##### Special tools  
[execute Expression with external Record](docs/tasks/external-exec-expression.md)  
[execute Workflow with external Record](docs/tasks/external-exec-expression.md)  
Custom condition

##### EntityData Values  
write EntityData  
read EntityData  

##### Importer  
[CSV import](docs/tasks/csv-import.md)  

##### Debugging  
[stop PHP](docs/tasks/stop-php.md)  















A Task is one action you integrate into your workflow. 
You could combine multiple Tasks in one Workflow and combine them with a path. 
Every Record which release the Workflow trigger, follow these paths and execute tasks they reach.

Every task contains several configurations, you open with an double click or a click on the small icon top left.
Also you have on almost all tasks green and red dots. 
The green are input and the red output points. You can only connect red to green.

Some tasks, like conditions have multiple output dots, because these are true/false outputs.
At this time, every task could have up to 3 outputs. 

Every output contains a tooltip, with information about the meaning.

Every workflow starts with an [start] task. These task is the point all executions start to process.

[image]

Inside this [start] block you can configure the run conditions, which I describe later.

On the right border, you see the task chooser. 
All tasks are sorted in several categories and could insert into the workflow with a mouse click.

If you add a task, you have to connect the correct output of the previous task with the green input of this new task.

For example it could look like this:

[image2]

In this example you could see, that the task block has multiple follower. This is no problem and you could control the order, this paths are executed.
The successors will be executed from top to bottom in the graphical view.
In the image, the single condition will be executed, before the task “name contain vtiger”.
If you move the tasks, you can sort the execution sequence.
