<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$sport='sb'; $sportname=GetSportName($sport);
$level=GetLevel($session);

if($level!=3)	//level 3 = OBSERVER -->just watches, no verification or payment
{
   $offid=GetOffID($session);
}

$sql="SELECT * FROM rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);
$end=split("-",$row[enddate]);
$filepath=$row[lockedversion];

if($offid!='3427' && $level!=3 && (!PastDue($row[startdate],-1) || PastDue($row[enddate],0)))	//UNAVAILABLE
{
   header("Location:rulesmeetingintro.php?sport=$sport&session=$session");
   exit();
}
//ELSE MARK TIME MOVIE IS INITIATED:
if($level!=3)
{
$table=$sport."rulesmeetings";
$sql="SELECT * FROM $table WHERE offid='$offid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//INSERT
{
   $sql="INSERT INTO $table (school1,offid,dateinitiated) VALUES ('".addslashes($school1)."','$offid','".time()."')";
   $result=mysql_query($sql);
}
else
{
   $sql="UPDATE $table SET school1='".addslashes($school1)."',dateinitiated='".time()."',datecompleted='0' WHERE offid='$offid'";
   $result=mysql_query($sql);
}
}

echo $init_html;
echo "<table width='100%' class=nine cellspacing=0 cellpadding=0><tr align=center><td>";

echo "<div id='header' style='width:95%;padding:3px;text-align:right;'><div style='float:left;'><b><i>Please be patient as the rules meeting loads.</i></b></div><form method=post action=\"rulesmeetingpay.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Rules Meeting Video\"></form></div>";

echo "<iframe src=\"$filepath\" style='width:100%;height:600px;valign:top;background-color:#ffffff;border:none;'></iframe>";

echo "<div id='footer' style='width:95%;padding:3px;text-align:center;'><form method=post action=\"rulesmeetingpay.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Rules Meeting Video\"></form></div>";

echo $end_html;

?>
