<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No application id.";
   exit();
}
//japproval.php: Page displayed if CC was approved

//require functions file for SendMail
require 'functions.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//insert transaction data into database
$sql="SELECT * FROM judgesapp WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   $sql2="INSERT INTO judgesapp (appid,approved) VALUES ('$ssl_invoice_number','yes')";
else
   $sql2="UPDATE judgesapp SET approved='yes' WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql2);

$id=$ssl_invoice_number;

//update entry in judges table

$sql="UPDATE judges SET amtpaid='20.00',payment='credit',pending='' WHERE appid='$id'";
$result=mysql_query($sql);

//update logins_j table with passcode
$sql="SELECT id,first,last FROM judges WHERE appid='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$offid=$row[0];
$name="$row[first] $row[last]";
$name2=addslashes($name);
   //generate new passcode
   $passcode=ereg_replace("\'","",$row[last]);
   $passcode=ereg_replace("\"","",$passcode);
   $passcode=ereg_replace(" ","",$passcode);
   $passcode=ereg_replace("[.]","",$passcode);
   $passcode=substr($passcode,0,6);
   $num=rand(1000,9999);
   $newpass=$passcode.$num;
   $sql="SELECT * FROM logins_j WHERE passcode='$newpass'";
   $result=mysql_query($sql);
   while(mysql_num_rows($result)>0) //make sure passcode is unique
   {
      $num++;
      $newpass=$passcode.$num;
      $sql="SELECT * FROM logins_j WHERE passcode='$newpass'";
      $result=mysql_query($sql);
   }
$sql="SELECT * FROM logins_j WHERE offid='$offid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//INSERT
{
   $sql2="INSERT INTO logins_j (name,level,passcode,offid) VALUES ('$name2','2','$newpass','$offid')";
   $result2=mysql_query($sql2);
}
else	//UPDATE
{
   $sql2="UPDATE logins_j SET name='$name2',level='2',passcode='$newpass' WHERE offid='$offid'";
   $result2=mysql_query($sql2);
}
?>
<html>
<head>
   <title>NSAA | Judges Registration Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body><center>
<table width=450>
<caption><b>Transaction Complete!<hr></b></caption>
<?php
//open application file to display as receipt:
$open=fopen(citgf_fopen("japp$id.html"),"r");
//$data=fread($open,citgf_filesize("japp$id.html"));
$data=stream_get_contents($open);
$filestr=split("</caption>",$data);
echo "<tr align=left><th colspan=2>Please print this page for your records:<br><br></th></tr>";
$date=date("M d, Y",$ssl_invoice_number);
echo "<tr align=left><th class=smaller>Transaction #:</th><td>$ssl_invoice_number</td></tr>";
$filestr2=split("</table>",$filestr[1]);
echo $filestr2[0];
echo "</table>";
echo $filestr2[1];

$string="<table>";
$string.="<tr align=left><td><br>Cardholder Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td>Credit Card Billing Address:</td><td>$ssl_avs_address<br>$ssl_city, $ssl_state $ssl_avs_zip</td></tr></table>";
echo $string;

//add cc info to file
$open=fopen(citgf_fopen("apps/japp$id.html"),"a");
fwrite($open,"$string</table></body></html>");
fclose($open); 
 citgf_makepublic("apps/japp$id.html");

//send e-mail to NSAA with html file for this transaction
/*
$From="nsaa@nsaahome.org";
$FromName="NSAA";
$To="judges@nsaahome.org";
$ToName="Judges";
$Subject="Judges Registration";
$Text="A new judges registration form is attached.\r\n\r\nThank You!";
$Html="A new judges registration form is attached.<br><br>Thank You!";
$Attm=array("japp$id.html");
SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
*/

//show new passcode
echo "<br><br><table>";
echo "<tr align=left><td><b>YOUR PASSCODE IS:</b> $newpass</td></tr>";
echo "<tr align=left><td><a class=small href=\"jindex.php\" target=new>Click Here to Login with your new passcode.</a></td></tr></table>";

//show letter
echo "<hr><table width=80%><tr align=left><td>";
echo $judgeletter;
echo "</td></tr></table>";
?>
</table>
</body>
</html>
