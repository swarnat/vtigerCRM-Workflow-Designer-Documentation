Custom functions
===============================

Lots of  Workflow Tasks in the Designer could use custom functions.

You recognize the option to use this functions, if you have an green border around a textfield and a special icon  () next to the textbox.

Almost all such fields have special hints about this function. (for example, what value you have to return)

These scripts are default PHP Scripts with limited functions. All whitelisted PHP functions have the same arguments, like the original one.
Each custom function have to return a value with the “return” statement.  

In Version 1.7 you could use the following functions:

md5, rand, implode, substr, explode, microtime, date, time, sha1, hash, intval, floatval, floor, ceil, foreach
all functions, started with “str” (strpos, str_pad, str_replace, …)

##### Arrays
From version 1.7 custom functions could be handle arrays and make use of the foreach function.
This could be used espacelly for the environment variables.

##### Environment Variables
From version 1.7 every execution of a workflow have it’s own environment variables, which will be available from all custom expression fields.
They could be read and write over the variable $env[...] ( = “…”;) .
The key, have to be a valid array key.

##### Additional functions
I have added some special PHP functions you could use to do special tasks.

wf_get_entity($crmid)

Return values from all fields from the $cmrid record from.

$crmid – mixed (integer/string)
- Set the CRM ID to load

example:

$entity = wf_get_entity($assigned_user_id);
return $entity["email1"];
This will return the primary email address of the assigned to User. (I know this could be get easier, but also you could get the email from the reports_to User within the assigned_to User in this way.)

wf_date($db_date, $interval, $format)

This function makes date calculation easier. If you only wants to format a given date, please use the default php date function.

$db_date – string
Date to format/change in format YYYY-MM-DD
$interval – string
This sets the interval, which would be applied to the $db_date.
Possible are all values from http://www.php.net/manual/de/datetime.formats.relative.php
$format – string
The format of the return date. (Possible placeholder: http://php.net/manual/en/function.date.php)

example:

return wf_date("2001-12-31", "+1 day", "d.m.Y"); # returns 01.01.2002

##### custom function Examples
This example is only written to demonstrate the functions and could be done better.

1
$var1 = "864"."0";
2
$var1 = $var1.(0 + intval($vtiger_purchaseorder)).intval($vtiger_purchaseorder);
3
$var2 = 1 + (2 * 5) - 8;
4
$var1 = substr($var1, 0, 5);
5
if($vtiger_purchaseorder == "1") {
6
 $add = intval($var1) * $var2;
7
 return time() + $add;
8
} else {
9
 $add = intval($var1) * intval($vtiger_purchaseorder);
10
 return time() + $add;
11
}
These function could be used inside the delay task to wait one day, or the amount of days in the Purchase Order Field of an invoice.

Here you could see one limitation of my implementation:

If you want use mathematical operations, you have to use parentheses to become the correct result.

##### Other examples
Wait until same day next month

1
return date("Y-m-d", strtotime("+1 month"));
Wait 5 days

1
$days = 5;
2
$date = strtotime($datefield);
3
return date("Y-m-d", $date + (86400 * $days))
