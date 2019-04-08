<?php
//decline.php: Page displayed if CC was declined for payment
require 'wrfunctions.php';
require '../variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$sql="UPDATE wrassessorsapp SET approved='no' WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);

$sql="SELECT * FROM wrassessorsapp WHERE appid='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$assessorid=$row[assessorid];
$sql="SELECT session FROM wrassessors WHERE userid='$assessorid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$session=$row[0];

echo $init_html;
echo GetAssessorHeader($session);

echo "<br><br><br>
<table width=450>
<caption><b>We're sorry, your transaction request was not approved.</b></caption>
<tr align=left><td>You may elect to <a href=\"javascript:history.go(-1);\">Go Back</a> and complete this transaction with a different credit card or you may contact the NSAA via e-mail: <a href=\"mailto:nsaa@nsaahome.org\">nsaa@nsaahome.org</a> or by phone: (402)489-0386. You may also <a href=\"logout.php?session=$session\">Logout</a> and try again later.</td></tr>
<tr align=center><td><br>Thank You!<br><br></td></tr>";
echo $end_html;
?>
