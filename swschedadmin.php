<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo $header;

echo "<br><br><a class=small href=\"welcome.php?session=$session&toggle=menu3&menu3sport=Swimming\">Return to Home-->Swimming</a><br><br>";
echo "<br><table><caption><b>Swimming Schedules Admin:</b></caption>";
echo "<tr align=center><td><br><ul>";
echo "<form method=post action=\"swsched.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<li><select name=\"school_ch\">";
$sql="SELECT * FROM swschool ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option>$row[school]</option>";
}
echo "</select><input type=submit name=viewschedules value=\"View Schedule\"></li>";
echo "</form>";
echo "<li><a target=new href=\"swscheds.php?session=$session\">View All Swimming Schedules</a></li>";
echo "</ul>";
echo "</td></tr></table>";

echo $end_html;
?>
