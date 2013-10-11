Task: duplicate Record
===============================================
<img src='../../images/task_duplicate.png' align='right'>

This Task could duplicate a record you specify.

The easiest way is to duplicate the current record, which execute this workflow.
To do this you should choose the current Module and insert “$id” into field ”duplicate following Record ID”.

But you also could duplicate every other record with this task. Not only the current one.
For example you have an Account, which want to renew a contract. You could manually choose the invoice and duplicate the record manually.

But you could also found the ID of a Invoice record from Accounts, duplicate the Invoice and refresh some dates.

Because you also could define fields you want to fill with special values, like the “Set values” does.