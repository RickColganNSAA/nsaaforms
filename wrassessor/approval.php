<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No transaction id.";
   exit();
}
 
$appid=$ssl_invoice_number;
$sql="UPDATE wrassessorsapp SET approved='yes' WHERE appid='$appid'";
$result=mysql_query($sql);

$sql="SELECT * FROM wrassessorsapp WHERE appid='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$assessorid=$row[assessorid];

$sql="UPDATE wrassessors SET datepaid='".time()."' WHERE userid='$assessorid'";
$result=mysql_query($sql);

$sql="SELECT session FROM wrassessors WHERE userid='$assessorid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$session=$row[0];

$string="<br><br>
<table class=nine width=\"500px\">
<caption><b>Transaction Complete!<hr></b></caption>";
$string.="<tr align=left><td colspan=2><b>Please print this page for your records:<br><br></b></td></tr>";
$string.="<tr align=left><td colspan=2><i>This page confirms that the following assessor has viewed the Wrestling Assessor PowerPoint and completed payment for the Annual Registration fee, thereby completing the NSAA Assessor Registration process. </i><br><br></td></tr>";
$date=date("M d, Y",$session);
$string.="<tr align=left><td><b>Transaction ID:</b></td><td>$appid</td></tr>";
$string.="<tr align=left><td><b>Transaction Date:</b></td><td>".date("F j, Y",$appid)."</td></tr>";
$sql="SELECT * FROM wrassessors WHERE userid='$assessorid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$string.="<tr align=left><td><b>Assessor's Name:</b></td><td>$row[first] $row[last]</td></tr>";
$string.="<tr align=left><td><b>ASSESSOR ID:</b></td><td><b><u>$row[userid]</b></u></td></tr>";
$string.="<tr align=left><td><b>ASSESSOR PASSWORD:</b></td><td><b><u>$row[password]</b></u></td></tr>";
$string.="<tr align=left><td><b>Transaction Description:</b></td><td>NSAA Wrestling Assessor Annual Registration</td></tr>";
$string.="<tr align=left><td><b>Transaction Amount:</b></td><td>$".$ssl_amount."</td></tr>";
$string.="<tr align=left><td>&nbsp;</td><td><table>";
$string.="<tr align=left><td><br>Billing Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><br>Billing Address:</td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><br>Credit Card Number:</td><td>$ssl_card_number</td></tr>";
$string.="</table></body></html>";
$string=addslashes($init_html.$string);
$sql="UPDATE wrassessorsapp SET html='$string' WHERE appid='$appid'";
$result=mysql_query($sql);

header("Location:index.php?session=$session&appid=$appid");
exit();

?>
