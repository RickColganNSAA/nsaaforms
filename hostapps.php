<?php
//hostapps.php: site surveys

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

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

if($submit=="Go")
{
   $redirect="hostapp_".$activity_ch.".php?session=$session";
   header("Location:$redirect");
   exit();
}

echo $init_html;
echo $header;

echo "<br><br><form method=post action=\"hostapps.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<h2>Applications to Host NSAA Tournaments/Events:</h2>";
echo "<p>Please choose the activity you wish to host an event for and click \"Go\":</p>";
echo "<p><select name=\"activity_ch\">";
$sql="SHOW TABLES LIKE 'hostapp_%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("hostapp_",$row[0]);
   echo "<option value=\"$temp[1]\">".GetActivityName($temp[1])."</option>";
}
echo "</select>&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Go\">";
echo "</form>";

echo "<br><br><p><a href=\"welcome.php/session=$session\">Return Home</a></p>";

echo $end_html;
?>
