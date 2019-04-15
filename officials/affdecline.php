<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

//affdecline.php: Page displayed if affiliate official's CC was declined

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//insert transaction data into database
$sql="INSERT INTO officialsapp (appid,approved) VALUES ('$ssl_invoice_number','no')";
$result=mysql_query($sql);

//erase any data entered for this official from affapplication.php
$sql="DELETE FROM pendingoffs WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
?>
<html>
<head>
   <title>NSAA | Affiliate Official's Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body><center><br><br><br>
<table width=450>
<caption><b>We're sorry, your transaction request was not approved.</b></caption>
<tr align=left><td>You may elect to <a href="javascript:history.go(-1)">Go Back</a> and complete this transaction with a different credit card or you may contact the NSAA via e-mail: <a href="mailto:nsaa@nsaahome.org">nsaa@nsaahome.org</a> or by phone: (402)489-0386. You may also <a href="affapplication.php">start the application process over</a>.</td></tr>
<tr align=center><td><br>Thank You!<br><br><a href="/">nsaahome.org</a></td></tr>
</table>
</body>
</html>
