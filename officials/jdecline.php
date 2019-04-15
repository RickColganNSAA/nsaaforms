<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//jdecline.php: Page displayed if CC was declined

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//insert transaction data into database
$sql="SELECT * FROM judgesapp WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   $sql2="INSERT INTO judgesapp (appid,approved) VALUES ('$ssl_invoice_number','no')";
else
   $sql2="UPDATE judgesapp SET approved='no' WHERE appid='$ssl_invoice_number'";
$result2=mysql_query($sql2);

?>
<html>
<head>
   <title>NSAA | Judges Registration Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body><center><br><br><br>
<table width=450>
<caption><b>We're sorry, your transaction request was not approved.</b></caption>
<tr align=left><td>You may elect to <a href="japplication.php?appid=$ssl_invoice_number">Go Back</a> and complete this transaction with a different credit card or you may contact the NSAA via e-mail: <a href="mailto:nsaa@nsaahome.org">nsaa@nsaahome.org</a> or by phone: (402)489-0386. You may also <a href="/officials/japplication.php">start the application process over</a>.</td></tr>
<tr align=center><td><br>Thank You!<br><br><a href="/">nsaahome.org</a></td></tr>
</table>
</body>
</html>
