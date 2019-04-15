<?php
require 'functions.php';
require 'variables.php';
if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No application id.";
   exit();
}
else	//check that invoice is not old (needs to equal today's date)
{
   $today=date("m/d/y");
   $invoicedate=date("m/d/y",$ssl_invoice_number);
   if($today!=$invoicedate && $secret!='46D5431FF61CD7EC47478FB32A52A')
   {
      echo "Expired session.  To complete your payment, you must start over.<br><br>";
      echo "<a href=\"japplication.php\">Click Here to complete the Judges Application Form</a>";
      exit();
   }
}
//approval.php: Page displayed if CC was approved

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//insert transaction data into database
$sql="SELECT * FROM judgesapp WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   $sql2="INSERT INTO judgesapp (appid,approved) VALUES ('$ssl_invoice_number','yes')";
else
   $sql2="UPDATE judgesapp SET approved='yes' WHERE appid='$ssl_invoice_number'";
$result2=mysql_query($sql2);


//insert transaction data into database
$sql="SELECT * FROM pendingjudges WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   $sql2="INSERT INTO pendingjudges (appid,approved) VALUES ('$ssl_invoice_number','yes')";
   echo "ERROR: No data for invoice #$ssl_invoice_number. (Date of invoice: ".date("m/d/y",$ssl_invoice_number).")";
   $attm=array();
   exit();
}
else
   $sql2="UPDATE pendingjudges SET approved='yes' WHERE appid='$ssl_invoice_number'";
$result2=mysql_query($sql2);

//UPDATE/INSERT INTO judges table the data from pendingjudges table for this judge
$row=mysql_fetch_array($result);	//data from pendingoffs into $row
$datereg=date("Y-m-d",$row[datesub]);
$row[first]=addslashes($row[first]); $row[last]=addslashes($row[last]);
$row[address]=addslashes($row[address]); $row[city]=addslashes($row[city]);
$row[convictionexplain]=addslashes($row[convictionexplain]);
if($row[offid]=="" || $row[offid]=='0')	//INSERT new entry
{
   $firstname=$row[first];
   $lastname=$row[last];   //might use for passcode generation below
   $sql1="SELECT * FROM judges WHERE appid='$ssl_invoice_number'";
   $result1=mysql_query($sql1);
   if(mysql_num_rows($result1)==0)
   {
      $sql2="INSERT INTO judges (appid,socsec,first,last,address,city,state,zip,homeph,workph,cellph,email,firstyrplay,firstyrspeech,yearsplay,yearsspeech,conviction,convictionexplain,speech,play,payment,datereg) VALUES ('$ssl_invoice_number','$row[socsec]','$row[first]','$row[last]','$row[address]','$row[city]','$row[state]','$row[zip]','$row[homeph]','$row[workph]','$row[cellph]','$row[email]','$row[firstyrplay]','$row[firstyrspeech]','$row[yearsplay]','$row[yearsspeech]','$row[conviction]','$row[convictionexplain]','$row[speech]','$row[play]','credit','$datereg')";
   }
   else	//in case they hit reload and entry is actually already in database:
   {
      $sql2="UPDATE judges SET socsec='$row[socsec]',first='$row[first]',last='$row[last]',address='$row[address]',city='$row[city]',state='$row[state]',zip='$row[zip]',homeph='$row[homeph]',workph='$row[workph]',cellph='$row[cellph]',email='$row[email]',firstyrplay='$row[firstyrplay]',firstyrspeech='$row[firstyrspeech]',yearsplay='$row[yearsplay]',yearsspeech='$row[yearsspeech]',conviction='$row[conviction]',convictionexplain='$row[convictionexplain]',speech='$row[speech]',play='$row[play]',payment='credit',datereg='$datereg' WHERE appid='$ssl_invoice_number'";
   }
   $result2=mysql_query($sql2);
   //get new offid
   $sql2="SELECT id FROM judges WHERE appid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $offid=$row2[0];
   $newoff=1;
}
else	//UPDATE entry in judges table
{
   $offid=$row[offid];
   $sql2="SELECT * FROM judges WHERE id='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sql2="UPDATE judges SET appid='$ssl_invoice_number',socsec='$row[socsec]',first='$row[first]',last='$row[last]',address='$row[address]',city='$row[city]',state='$row[state]',zip='$row[zip]',homeph='$row[homeph]',workph='$row[workph]',cellph='$row[cellph]',email='$row[email]',";
   if($row[speech]=='x')
      $sql2.="speech='$row[speech]',firstyrspeech='$row[firstyrspeech]',yearsspeech='$row[yearsspeech]',";
   if($row[play]=='x')
      $sql2.="play='$row[play]',firstyrplay='$row[firstyrplay]',yearsplay='$row[yearsplay]',";
   $sql2.="conviction='$row[conviction]',convictionexplain='$row[convictionexplain]',payment='credit',datereg='$datereg' WHERE id='$offid'";
   $result2=mysql_query($sql2);
   $newoff=0;
   $firstname=$row[first]; $lastname=$row[last];
}
$savedql=$sql2;

//LOGINS table: see if $offid has an entry
//If it does, get passcode OR if passcode is blank, generate passcode
//If it does NOT, generate passcode and add entry to logins_j table
$sql2="SELECT * FROM logins_j WHERE offid='$offid' AND offid!='0'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)	//judge already has entry in logins table
{
   $row2=mysql_fetch_array($result2);
   $passcode=$row2[passcode];
   if($passcode=='' || $passcode=='0')
   {
      //generate new passcode for this judge:
      $passcode=GeneratePasscode($lastname,0);
      //Update logins table
      $sql3="UPDATE logins_j SET passcode='$passcode' WHERE offid='$offid'";
      $result3=mysql_query($sql3);
   }
}
else	//Need NEW entry for this judge in logins table
{
   //First, generate passcode, as done above
   $passcode=GeneratePasscode($lastname,0);
   //Then INSERT new entry into logins table
   $name=addslashes("$firstname $lastname");
   $sql2="INSERT INTO logins_j (name,level,passcode,offid) VALUES ('$name','2','$passcode','$offid')";
   $result2=mysql_query($sql2);
}
//Now $passcode has current passcode for this judge
   
$appid=$ssl_invoice_number;
//send me e-mail so I can check up on how this is working

$Text="Judge Approved:\r\noffid=$offid\r\nappid=$appid\r\n\r\n$savedsql";
$Html="Judge Approved:<br>offid=$offid<br>appid=$appid<br><br>$savedsql";
$Attm=array();
//SendMail("nsaa@nsaahome.org","NSAA","run7soccer@aol.com","Ann Gaffigan","Judge's CC Approved",$Text,$Html,$Attm);

?>
<html>
<head>
   <title>NSAA | Judges Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<table><tr align=center><td>
<table width=500>
<caption><b>Transaction Complete!<hr></b></caption>
<?php
echo "<tr align=left><th colspan=2>Please print this page for your records:<br><br></th></tr>";
$date=date("M d, Y",$ssl_invoice_number);
echo "<tr align=left><th>Transaction #:</th><td>$appid</td></tr>";

$string="<tr><td colspan=2><table>";
$string.="<tr align=left><td><br>Billing Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><br>Billing Address:</td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><br>Credit Card Number:</td><td>$ssl_card_number</td></tr>";

$sql="SELECT * FROM judgesapp WHERE appid='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$html=$row[html]."$string</table></body></html>";
$html=addslashes($html);
$sql="UPDATE judgesapp SET html='$html' WHERE appid='$appid'";
$result=mysql_query($sql);

$showhtml=ereg_replace("<html><body>","",$html);
$showhtml=ereg_replace("</body></html>","",$showhtml);
echo "<tr align=center><td colspan=2><br>".$showhtml."</td></tr></table><br>";

//show new passcode
echo "<table class='nine' style='width:500px;border:#00000 3px solid;' frame=all rules=all cellspacing=0 cellpadding=5>";
echo "<tr align=left><td><p><b>YOUR PASSCODE IS:</b> $passcode</p>";
echo "<p><a href=\"jindex.php\" target=\"blank\">Click Here to Login with your new passcode.</a></p>";
echo "<p style=\"color:red\"><b>PLEASE WRITE THIS PASSCODE DOWN IN A SAFE, EASY-TO-REMEMBER PLACE.  You will NOT be able to return to this screen to retrieve your passcode.</b></p>";
echo "</td></tr></table>";

//show letter
echo "";
include 'previewjudgesletter.php';
?>
</td></tr>
</table>
</body>
</html>
