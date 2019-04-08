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
   //get sport this rules meeting was: ssl_invoice_number is of format $session-$sport
   $temp=split("-",$ssl_invoice_number);
   $session=$temp[0];
   if(!ValidUser($session) && $secret!='46D5431FF61CD7EC47478FB32A52A')
   {
      header("Location:index.php?error=1");
      exit();
   }
   $sport=$temp[1];
   $today=date("m/d/y");
   $coachid=GetUserID($session); //$ssl_customer_code;
}
//rulesmeetingapproval.php: Page displayed if CC was approved

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$now=time();

//insert transaction data into database
$sql="SELECT * FROM rulesmeetingtransactions WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
   $sql2="UPDATE rulesmeetingtransactions SET datepaid='$now',coachid='$coachid',approved='yes',amount='$ssl_amount' WHERE invoiceid='$ssl_invoice_number'";
else
   $sql2="INSERT INTO rulesmeetingtransactions (datepaid,coachid,invoiceid,approved,amount) VALUES ('$now','$coachid','$ssl_invoice_number','yes','$ssl_amount')";
$result2=mysql_query($sql2);

//insert transaction data into database
$sql="SELECT * FROM ".$sport."rulesmeetings WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: No data for invoice #$ssl_invoice_number. (Date of invoice: ".date("m/d/y",$session).")";
   $attm=array();
   exit();
}
else
{
   $sql2="UPDATE ".$sport."rulesmeetings SET datepaid='$now' WHERE invoiceid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
   $sql2="UPDATE logins SET rulesmeeting='x' WHERE id='$coachid'";
   $result2=mysql_query($sql2);
   //IF SPEECH or PLAY or T&F AND THIS PERSON IS THE COACH OF BOTH - GIVE CREDIT IN BOTH PLACES
   $cursp=GetActivity($session);
   if($sport=='sp' || $sport=='pp')
   {
      $name=GetUserName($session);
      if($cursp=="Speech") $othersp="Play Production";
      else $othersp="Speech";
      $sql2="SELECT * FROM logins WHERE sport='$othersp' AND school='".addslashes($school)."'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(trim($row2[name])==trim($name))
      {
         $sql2="UPDATE logins SET rulesmeeting='x' WHERE id='$row2[id]'";
         $result2=mysql_query($sql2);
         $duocoach="Speech and Play Production Director";
      }
      else $duocoach="Director";
   }
   else if(preg_match("/Boys/",$sport) || preg_match("/Girls/",$sport))
   {
      $name=GetUserName($session);
      if(preg_match("/Girls/",$cursp)) { $othersp=preg_replace("/Girls/","Boys",$cursp); $duocoach="Boys and ".$cursp." Coach"; }
      else { $othersp=preg_replace("/Boys/","Girls",$cursp); $duocoach="Girls and ".$cursp." Coach"; }
      $sql2="SELECT * FROM logins WHERE sport='$othersp' AND school='".addslashes($school)."'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(trim($row2[name])==trim($name))
      {
         $sql2="UPDATE logins SET rulesmeeting='x' WHERE id='$row2[id]'";
         $result2=mysql_query($sql2);
	 $duosql=$sql2;
      }
      else $duocoach="$cursp Coach";
   }
   else
      $duocoach="Coach";
   //IF ALSO AN OFFICIAL, UPDATE OFFICIALS DATABASE
   $sql2="SELECT * FROM ".$sport."rulesmeetings WHERE coachid='$coachid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $offerror=""; $offid=0;
   if($row2[offid]>0)   //IS ALSO AN OFFICIAL
   {
      $offid=$row2[offid];
      if($sport=='sp' || $sport=='pp')
      {
         $sql2="UPDATE $db_name2.judges SET meeting='x' WHERE id='$offid'";
         $result2=mysql_query($sql2);
      }
      else
      {
         //get regyr from end of $db_name
         $regyr=date("Y");
         if(date("m")<6) $regyr--;
         $regyr1=$regyr+1;
         $regyr="$regyr-$regyr1";
         $table=$sport."off_hist";
         //update __off_hist table
         $sql2="SELECT * FROM $db_name2.$table WHERE offid='$offid' AND regyr='$regyr'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)==0) //INSERT
         {
            $offerror="You have not registered as an OFFICIAL for the $regyr School Year yet.  Please do so in order to be marked as having attended a ".GetSportName($sport)." Rules Meeting this year.  Once you have registered, please contact the NSAA and ask for credit for the online rules meeting.";
         }
         else    //UPDATE
         {
            $sql3="UPDATE $db_name2.$table SET rm='x' WHERE offid='$offid' AND regyr='$regyr'";
            $result3=mysql_query($sql3);
         }
      }
   }
}

$sportname=GetActivityName($sport);
$string="<html>
<head>
   <title>NSAA Online $sportname Rules Meeting</title>
   <link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">
</head>
<body>
<table><tr align=center><td>
<table width=500>
<caption><b>Transaction Complete!<hr></b>";
   $string.="<br>Your attendance at the NSAA Online $sportname Rules Meeting has been verified";
   if($offerror!='')
      $string.=" as a $duocoach but NOT an OFFICIAL*";
   else if($offid>0)    //CREDIT AS AN OFFICIAL TOO
   {
      if($sport=='sp' || $sport=='pp') $string.=" as a $duocoach and a JUDGE";
      else $string.=" as a $duocoach and an OFFICIAL";
   }
   else //JUST COACH
      $string.=" as a $duocoach";
   if($offerror!='')
      $string.="<br><div class=error>*$offerror</div>";
   $string.="</caption>";

$string.="<tr align=left><td colspan=2><b>Please print this page for your records:<br><br></b></td></tr>";
$date=date("M d, Y",$session);
$string.="<tr align=left><td><b>Transaction ID:</b></td><td>$ssl_invoice_number</td></tr>";
$string.="<tr align=left><td><b>Transaction Date:</b></td><td>".date("F j, Y",$now)."</td></tr>";
$sql="SELECT name,email FROM logins WHERE id='$coachid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$string.="<tr align=left><td><b>Coach's Name:</b></td><td>$row[name]</td></tr></tr>";
$string.="<tr align=left><td><b>Transaction Description:</b></td><td>$sportname Online Rules Meeting</td></tr>";
$string.="<tr align=left><td><b>Transaction Amount:</b></td><td>$".$ssl_amount."</td></tr>";
$string.="<tr><td colspan=2><table>";
$string.="<tr align=left><td><br>Billing Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><br>Billing Address:</td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><br>Credit Card Number:</td><td>$ssl_card_number</td></tr>";
echo $string."<tr align=center><td><br><br><br><br><a href=\"welcome.php?session=$session\">Home</a></td></tr></table></body></html>";
$string.="</table></body></html>";
flush();
$text="Transaction Complete!\r\n\r\nTransaction ID: $ssl_invoice_number\r\nTransaction Date: ".date("F j, Y",$now)."\r\nCoach's Name: $row[name]\r\nTransaction Description: $sportname Online Rules Meeting\r\nTransaction Amount: $".number_format($ssl_amount,2,'.','')."\r\nCardholder Name: $ssl_first_name $ssl_last_name\r\nCredit Card Billing Address: $ssl_avs_address, $ssl_city, $ssl_state $ssl_avs_zip\r\nCredit Card Number: $ssl_card_number\r\n";
$html=str_replace(array('\r','\n'),"<br>",$text);
$Attm=array();
flush();
$string=addslashes($string);
$sql="UPDATE rulesmeetingtransactions SET html='$string' WHERE invoiceid='$ssl_invoice_number'";
$result=mysql_query($sql);

if($row[email]!='')
{
   SendMail("nsaa@nsaahome.org","NSAA",$row[email],$row[name],"Your Receipt from the NSAA",$text,$html,$Attm);
}

exit();
?>
