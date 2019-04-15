<?php
/* PLAY RULES MEETING FOR JUDGES */
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}
$sport='pp';
$offid=GetJudgeID($session);
$sportname=GetSportName($sport);

$sql="SELECT * FROM rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);
$end=split("-",$row[enddate]);
$filepath=$row[lockedversion];

if(!$rewatch && $offid!='598' && (!PastDue($row[startdate],-1) || PastDue($row[enddate],0)))	//UNAVAILABLE
{
   //echo $row[startdate]."<br>".$row[enddate];
   header("Location:rulesmeetingintro.php?sport=$sport&session=$session");
   exit();
}
//ELSE MARK TIME MOVIE IS INITIATED:
$table=$sport."rulesmeetings";
$sql="SELECT * FROM $table WHERE offid='$offid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//INSERT
{
   $sql="INSERT INTO $table (offid,dateinitiated,school) VALUES ('$offid','".time()."','".addslashes($school)."')";
   $result=mysql_query($sql);
}
else
{
   $sql="UPDATE $table SET dateinitiated='".time()."',datecompleted='0',school='".addslashes($school)."' WHERE offid='$offid'";
   $result=mysql_query($sql);
}
//echo $sql;
echo mysql_error();

echo $init_html;
echo "<table width='100%' class=nine cellspacing=0 cellpadding=0><tr align=center><td>";

echo "<div id='header' style='width:95%;padding:3px;text-align:right;'><form method=post action=\"rulesmeetingpay.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Rules Meeting Video\"></form></div>";

echo "<iframe src=\"$filepath\" style='width:100%;height:600px;valign:top;background-color:#ffffff;border:none;'></iframe>";

echo "<div id='footer' style='width:95%;padding:3px;text-align:center;'><form method=post action=\"rulesmeetingpay.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Rules Meeting Video\"></form></div>";
echo $end_html;

?>
