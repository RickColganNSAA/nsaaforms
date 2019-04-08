<?php
//rulesmeetingdecline.php: Page displayed if CC was declined for rules meeting payment
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

//insert transaction data into database
$temp=split("-",$ssl_invoice_number);
$session=$temp[0];
$sport=$temp[1];
$sportname=GetActivityName($sport);
$coachid=$ssl_customer_code;
$sql="SELECT * FROM rulesmeetingtransactions WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $ssl_invoice_number)
{
   $sql2="DELETE FROM rulesmeetingtransactions WHERE invoiceid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
}
$sql2="INSERT INTO rulesmeetingtransactions (invoiceid,approved,coachid) VALUES ('$ssl_invoice_number','no','$coachid')";
if(!$ssl_invoice_number)
{
   echo "ERROR: no Invoice ID.";
   exit();
}
$result2=mysql_query($sql2);
$sql="UPDATE ".$sport."rulesmeetings SET invoiceid='' WHERE coachid='$coachid'";
$result=mysql_query($sql);

echo $init_html;
echo GetHeader($session);

echo "<br><br><br>
<table width=450>
<caption><b>We're sorry, your transaction request was not approved.</b></caption>
<tr align=left><td>You may elect to <a href=\"rulesmeetingpay.php?session=$session&sport=$sport\">Go Back</a> and complete this transaction with a different credit card or you may contact the NSAA via e-mail: <a href=\"mailto:nsaa@nsaahome.org\">nsaa@nsaahome.org</a> or by phone: (402)489-0386. You may also <a href=\"logout.php?session=$session\">Logout</a> and try again later.</td></tr>
<tr align=center><td><br>Thank You!<br><br></td></tr>";
echo $end_html;
?>
