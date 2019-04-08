<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$sport='so';
$coachid=GetUserID($session);
$school=GetSchool($session);
$sportname=GetActivityName($sport);

//if official's passcode was entered, check that the passcode is valid
if($passcode!='')
{
   $sql="SELECT * FROM $db_name2.logins WHERE passcode='$passcode'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)       //NOT VALID
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br><div style='width:300px;' class=error>ERROR:<br><br>You have entered an invalid official's passcode.<br><br>Please <a href=\"javascript:history.go(-1);\">Go Back and Try Again</a>.</div><br><br>";
      echo $end_html;
      exit();
   }
   $row=mysql_fetch_array($result);
   $offid=$row[offid];
}

$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);
$end=split("-",$row[enddate]);
$filepath=$row[lockedversion];

if($school!="Test's School" && (!PastDue($row[startdate],-1) || PastDue($row[enddate],0)))	//UNAVAILABLE
{
   header("Location:rulesmeetingintro.php?sport=$sport&session=$session");
   exit();
}
//ELSE MARK TIME MOVIE IS INITIATED:
$table=$sport."rulesmeetings";
$sql="SELECT * FROM $table WHERE coachid='$coachid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//INSERT
{
   $sql="INSERT INTO $table (coachid,offid,dateinitiated) VALUES ('$coachid','$offid','".time()."')";
   $result=mysql_query($sql);
}
else
{
   $sql="UPDATE $table SET offid='$offid',dateinitiated='".time()."',datecompleted='0' WHERE coachid='$coachid'";
   $result=mysql_query($sql);
}

echo $init_html;
echo "<table width='100%' class=nine cellspacing=0 cellpadding=0><tr align=center><td>";

echo "<div id='header' style='width:95%;padding:3px;text-align:right;'><form method=post action=\"rulesmeetingpay.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Rules Meeting Video\"></form></div>";

echo "<iframe src=\"$filepath\" style='width:100%;height:600px;valign:top;background-color:#ffffff;border:none;'></iframe>";

echo "<div id='footer' style='width:95%;padding:3px;text-align:center;'><form method=post action=\"rulesmeetingpay.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Rules Meeting Video\"></form></div>";

echo $end_html;

?>
