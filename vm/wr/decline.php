<?php
//decline.php: Page displayed if CC was declined for payment
require "../functions.php";
require "../variables.php";

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No transaction id.";
   exit();
}

session_set_cookie_params(2*24*60*60);
session_start();

if(!$_SESSION['sessionid'])
{
   echo "Your session has expired.";
   exit();
}

$appid=$ssl_invoice_number;

//MARK AS DECLINED
$sql="UPDATE wrvideotransactions SET approved='no' WHERE appid='$ssl_invoice_number' AND sessionid='".$_SESSION['sessionid']."'";
$result=mysql_query($sql);

echo $init_html;

echo "<br><br><br>
<table width=450>
<caption><b>We're sorry, your transaction request was not approved.</b></caption>
<tr align=left><td>You may elect to <a href=\"javascript:history.go(-1);\">Go Back</a> and complete this transaction with a different credit card or you may contact the NSAA via e-mail: <a href=\"mailto:nsaa@nsaahome.org\">nsaa@nsaahome.org</a> or by phone: (402)489-0386. You may also come back to the <a href=\"/nsaaforms/wr/wrvideos.php\">NSAA Wrestling Videos Page</a> and try again later.</td></tr>
<tr align=center><td><br>Thank You!<br><br></td></tr>";
echo $end_html;
?>
