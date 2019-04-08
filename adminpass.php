<?php
//adminpass.php: allows NSAA user to change their Level 1 passwords
//   The password must be at least 8 chars and must be a
//   combo of numbers and letters

require 'functions.php';
require 'variables.php';

CleanSessions();

//validate user
if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db("$db_name",$db);

echo $init_html.GetHeader($session);

//if new password entered, store it:
if($submit=="Save Passwords")
{
   //SCHOOLS (schoolspass)
   $schoolspass=trim($schoolspass);
   $schoolserr=""; $schoolsnote="";
   if(strlen($schoolspass)<8) 
      $schoolserr="The password must be AT LEAST 8 characters.";
   else if(!(preg_match("/[0-9]/",$schoolspass) && preg_match("/[a-zA-Z]/",$schoolspass)))
      $schoolserr="The password must have at least one NUMBER and at least one LETTER";
   //make sure password is not already in use
   $sql="SELECT * FROM logins WHERE passcode='$schoolspass' AND level!=1";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)	//passcode in use
      $schoolserr="That password is already in use. Please choose a different password.";
   if($schoolserr=="")
   {
      $sql="UPDATE logins SET passcode='$schoolspass',changepass='".time()."' WHERE level='1'";
      $result=mysql_query($sql);
      if(mysql_error())
         $schoolserr="There was an error in the query: ".mysql_error();
      else
         $schoolsnote="The NSAA Schools Password has been successfully updated.";
   }


   //OFFICIALS (officialspass)
   $officialspass=trim($officialspass);
   $officialserr=""; $officialsnote="";
   if(strlen($officialspass)<8)
      $officialserr="The password must be AT LEAST 8 characters.";
   else if(!(preg_match("/[0-9]/",$officialspass) && preg_match("/[a-zA-Z]/",$officialspass)))
      $officialserr="The password must have at least one NUMBER and at least one LETTER";
   //make sure password is not already in use
   $sql="SELECT * FROM logins WHERE passcode='$officialspass' AND level!=1";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)        //passcode in use
      $officialserr="That password is already in use. Please choose a different password.";
   if($officialserr=="")
   {
      $sql="UPDATE $db_name2.logins SET passcode='$officialspass' WHERE level='1'";
      $result=mysql_query($sql);
      if(mysql_error())
         $officialserr="There was an error in the query: ".mysql_error();
      else
         $officialsnote="The NSAA Officials Password has been successfully updated.";
   }


   //JUDGES (judgespass)
   $judgespass=trim($judgespass);
   $judgeserr=""; $judgesnote="";
   if(strlen($judgespass)<8)
      $judgeserr="The password must be AT LEAST 8 characters.";
   else if(!(preg_match("/[0-9]/",$judgespass) && preg_match("/[a-zA-Z]/",$judgespass)))
      $judgeserr="The password must have at least one NUMBER and at least one LETTER";
   //make sure password is not already in use
   $sql="SELECT * FROM logins WHERE passcode='$judgespass' AND level!=1";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)        //passcode in use
      $judgeserr="That password is already in use. Please choose a different password.";
   if($judgeserr=="")
   {
      $sql="UPDATE $db_name2.logins_j SET passcode='$judgespass' WHERE level='1'";
      $result=mysql_query($sql);
      if(mysql_error())
         $judgeserr="There was an error in the query: ".mysql_error();
      else
         $judgesnote="The NSAA Judges Password has been successfully updated.";
   }
}
?>

<form method=post action="adminpass.php">
<input type="hidden" name="session" value="<?php echo $session; ?>">
<br>
<h1>Manage NSAA Passwords:</h1>
<?php if($errormsg!=''): echo $errormsg; endif; ?>
<p>Passwords must be at least 8 characters in length and must
  contain both letters <i>and</i> numbers.</p>

<h3>NSAA Schools Password:</h3>
<?php
if($schoolserr!='') echo "<div class=\"error\">$schoolserr</div>";
else if($schoolsnote!='') echo "<div class=\"alert\">$schoolsnote</div>";
$sql="SELECT * FROM logins WHERE level='1'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
?>
<input type="text" name="schoolspass" size="20" value="<?php echo $row[passcode]; ?>">

<h3>NSAA Officials Password:</h3>
<?php
$sql="SELECT * FROM $db_name2.logins WHERE level='1'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($officialserr!='') echo "<div class=\"error\">$officialserr</div>";
else if($officialsnote!='') echo "<div class=\"alert\">$officialsnote</div>";
?>
<input type="text" name="officialspass" size="20" value="<?php echo $row[passcode]; ?>">

<h3>NSAA Judges Password:</h3>
<?php
if($judgeserr!='') echo "<div class=\"error\">$judgeserr</div>";
else if($judgesnote!='') echo "<div class=\"alert\">$judgesnote</div>";
$sql="SELECT * FROM $db_name2.logins_j WHERE level='1'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
?>
<input type="text" name="judgespass" size="20" value="<?php echo $row[passcode]; ?>">

<br><br>
<input type="submit" name="submit" value="Save Passwords">
</form>
<?php echo $end_html; ?>
