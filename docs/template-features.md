Template features
===============================

Allmost all tasks have configuration fields, which could be filled with variables. You could recognize this feature if there is an icon next to the textfield, which shows this blue box: 

If there is a green box, next to a textarea, this isn’t a default text field, but an custom function field. This custom function are php scripts, which allow the most complex generation of values. But this feature needs some development skills, because there must be a “return” statement, which returns the result.

I will mention which template feature could be used inside which textfield (blue, green).
If you want to only select values from fields, without complex calculations, please ignore the information about a green textbox and don’t select “function” if there is this option before a textfield.

##### 1. Default fields
	$fieldname

**This is the most common way to use templates.** This variables will be replaced by the content of the field you reference by “fieldname”. For example there is $accountname, $lastname, $firstname, $bill_street, … . The complete list of possible variables, you get by click on the blue/green box icon.
This variables could be used inside the default blue Template fields and the “green” textareas. This is a readonly Access, which couldn’t be used to change the content of a field.

Inside the green custom functions there is a special feature, because you could generate temporary variables, which are available only in this script. The name of this variables have to follow the php structure of variables.  This variables will be removed at the end of the script.

##### 2. Relation fields
	$(contact_id: (Contacts) leadsource)
	$account_id->Accounts->tickersymbol
This could be used to access the value of a field inside a referenced record. (For example a value from Organization from Contacts module)
All possible variables (also this relations) are accessible over the blue/green icon. You don’t need to create the following structure by yourself!

In the blue Textbox this variable use this structure:
$(contact_id: (Contacts) leadsource)
The green textbox use another structure, because of the php base:
$account_id->Accounts->tickersymbol

##### 3. Quick Helpers
The blue Textfields have some special functions, which allows to make some simple calculations.

Currently the only helper are:
**$[Now]** – insert the current date in format (YYYY-MM-DD)
**$[Now,-x]** – insert the current date -X days in format (YYYY-MM-DD)
**$[Now,+x]** – insert the current date +X days in format (YYYY-MM-DD)
**$[Now,0,'d.m.Y']** – insert the current date in format (DD-MM-YYYYY) You get possible placeholders here.
**$[entityname, $id]** – Replace the ID with the Name of the Record (Accountname, Firstname & Lastname, …)

**At the moment this feature don’t allow calculations with a date within a field! Only with the current date.**

###### Please read the following sections only, if you have some basic development skills, because they make use of the custom functions. (Green textfield)

##### 4. Insert of custom functions inside default template fields

A big improvement was the feature to insert a custom function in every default textfield. (blue icon)
This makes it possible to generate very complex values by the the usage of lots of php functions, without the limitations to the functions (green) textfields.
If the available visible space isn’t enough, please double click on a textfield. A popUp with a bigger textfield will be displayed.

You only need to insert the following:

	${ ... custom function ... return "content"; }}>
The return statement is imperative, because the complete block (from “${” to “}}>” ) will be replaced the the return value.
This allows for example the insert of a rounded total sum of an invoice by:

	${ return round($hdnGrandTotal, 2); }}>
Also this feature allows the calculation of dates from fields by:

	${ $date = strtotime($fieldname); $new_date = strtotime($date, "+1 day"); return date("Y-m-D", $new_date); }}>

	Please note, you have to use the related variable structure of custom functions ($field->module->field) and the blue Icon still supplies the default structure. ($field (module) field)

Other examples I will mention on a [separate custom function page](docs/custom-functions.md).

##### 5. use environment variables (Only Green Textfields)

This environment variables could be used to transfer a value from one task to another task, because they won’t be clear after the task is ready.

You could access this variable normally with the $env – Array.
To set a $env Variable you have to use:
$env["variableame"] = “content of the env. variable”;
If you want to get this value in another/or the same task, you only should use

... $env["variablename"] ...