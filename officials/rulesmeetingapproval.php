<?php
require 'functions.php';
require 'variables.php';
if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No transaction id.";
   exit();
}
else    
{
   //get sport this rules meeting was: ssl_invoice_number is off format $session-$sport
   $temp=split("-",$ssl_invoice_number);
   $session=$temp[0];
   $sport=$temp[1];
   if(!ValidUser($session) && $secret!='46D5431FF61CD7EC47478FB32A52A')
   {
      if($sport=='sp' || $sport=='pp')
         header("Location:jindex.php?error=1");
      else
         header("Location:index.php?error=1");
      exit();
   }
   $today=date("m/d/y");
   if($sport=='sp' || $sport=='pp') $offid=GetJudgeID($session);
   else $offid=GetOffID($session);
   //$offid=$ssl_customer_code;
}
//rulesmeetingapproval.php: Page displayed if CC was approved

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name2,$db);

$now=time();

//insert transaction data into database
$sql="SELECT * FROM rulesmeetingtransactions WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);
/*
if(mysql_num_rows($result)>0)
{
   $sql2="DELETE FROM rulesmeetingtransactions WHERE invoiceid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
}
*/
$sql2="INSERT INTO rulesmeetingtransactions (datepaid,offid,invoiceid,approved,amount) VALUES ('$now','$offid','$ssl_invoice_number','yes','$ssl_amount')";
$result2=mysql_query($sql2);

//insert transaction data into database
$sql="SELECT * FROM ".$sport."rulesmeetings WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: No data for invoice #$ssl_invoice_number.<br><br>Please do not hit the BACK button.  Please contact the NSAA with this Invoice #.  Thank you.";
   $attm=array();
   SendMail("nsaa@nsaahome.org","NSAA","run7soccer@aim.com","Ann Gaffigan","Officials/Judges ".strtoupper($sport)." Rules Meeting Error","$ssl_invoice_number","$ssl_invoice_number $sql",$attm);
   exit();
}
else
{
   $sql2="UPDATE ".$sport."rulesmeetings SET datepaid='$now' WHERE invoiceid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);

   if($sport=='sp' || $sport=='pp')
   { 
      //IF ALSO SP/PP COACH, UPDATE sp/pp rulesmeeting TABLE
      $sql2="SELECT * FROM ".$sport."rulesmeetings WHERE offid='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $school=trim($row2[school]); 
      if($school!='') //ALSO A SPEECH HEAD COACH
      {
         $sql="UPDATE $db_name.logins SET rulesmeeting='x' WHERE school='".addslashes($school)."' AND sport='".GetSportName($sport)."'";
         $result=mysql_query($sql);
      }
   }
}

//UPDATE/INSERT INTO _off_hist table a 'x' for rules meeting field (or judges table for SPPP)
if($sport=='sp' || $sport=='pp')
{
   $sql="SELECT * FROM judges WHERE id='$offid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "ERROR: We cannot find you in our database and therefore cannot mark you as having attended a rules meeting.  Please contact the NSAA and reference Invoice #$ssl_invoice_number and Judge ID#$offid.";
      exit();
   }
   else
   {
      $sql="UPDATE judges SET ".$sport."meeting='x' WHERE id='$offid'";
      $result=mysql_query($sql);
   }
}
else
{
$year1=date("Y"); $mo=date("m");
$regyr=GetSchoolYear($year1,$mo);
$table=$sport."off_hist";
//update __off_hist table
$sql2="SELECT * FROM $table WHERE offid='$offid' AND regyr='$regyr'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)==0)	//INSERT
{
   echo "You have not registered for the $regyr School Year yet.  Please do so in order to be marked as having attended a ".GetSportName($sport)." Rules Meeting this year.  Once you have registered, please contact the NSAA and ask for credit for the online rules meeting.";
   exit();
}
else	//UPDATE
{
   $sql3="UPDATE $table SET rm='x' WHERE offid='$offid' AND regyr='$regyr'";
   $result3=mysql_query($sql3);

   //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
   UpdateRank($offid,$sport);
}
}//end if not SPPP

$sportname=GetSportName($sport);
$string="<html>
<head>
   <title>NSAA Online $sportname Rules Meeting</title>
   <link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">
</head>
<body>
<table><tr align=center><td>
<table width=500>
<caption><b>Transaction Complete!<hr></b></caption>";
$string.="<tr align=left><td colspan=2><b>Please print this page for your records:<br><br></b></td></tr>";
$date=date("M d, Y",$session);
$string.="<tr align=left><td><b>Transaction ID:</b></td><td>$ssl_invoice_number</td></tr>";
$string.="<tr align=left><td><b>Transaction Date:</b></td><td>".date("F j, Y",$now)."</td></tr>";
$sql="SELECT first,last,email FROM officials WHERE id='$offid'";
if($sport=="sp" || $sport=="pp")
   $sql="SELECT first,last,email FROM judges WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$name="$row[first] $row[last]";
$string.="<tr align=left><td><b>Officials's Name:</b></td><td>$row[first] $row[last]</td></tr></tr>";
$string.="<tr align=left><td><b>Transaction Description:</b></td><td>$sportname Online Rules Meeting</td></tr>";
$string.="<tr align=left><td><b>Transaction Amount:</b></td><td>$".$ssl_amount."</td></tr>";
$string.="<tr><td colspan=2><table>";
$string.="<tr align=left><td><br>Billing Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><br>Billing Address:</td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><br>Credit Card Number:</td><td>$ssl_card_number</td></tr>";
if($sport=='sp' || $sport=='pp')
   echo $string."<tr align=center><td><br><br><br><br><a href=\"jwelcome.php?session=$session\">Home</a></td></tr></table></body></html>";
else
   echo $string."<tr align=center><td><br><br><br><br><a href=\"welcome.php?session=$session\">Home</a></td></tr></table></body></html>";
$string.="</table></body></html>";
flush();
$string=addslashes($string);
$sql="UPDATE rulesmeetingtransactions SET html='$string' WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);
flush();
if($sport=='sp' || $sport=='pp') $person="Judge's";
else $person="Official's";
$text="Transaction Complete!\r\n\r\nTransaction ID: $ssl_invoice_number\r\nTransaction Date: ".date("F j, Y",$now)."\r\n$person Name: $name\r\nTransaction Description: $sportname Online Rules Meeting\r\nTransaction Amount: $".number_format($ssl_amount,2,'.','')."\r\nCardholder Name: $ssl_first_name $ssl_last_name\r\nCredit Card Billing Address: $ssl_avs_address, $ssl_city, $ssl_state $ssl_avs_zip\r\nCredit Card Number: $ssl_card_number\r\n";
$html=ereg_replace("\r\n","<br>",$text);
$Attm=array();
if($row[email]!='')
   SendMail("nsaa@nsaahome.org","NSAA",$row[email],$name,"Your Receipt from the NSAA",$text,$html,$Attm);

exit();
?>
