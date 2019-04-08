<?php

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

echo $init_html;
echo "<table><tr align=center><td>";
echo "<center>";

echo "<table><caption><b>".strtoupper($sport)." Declarations:<br></b><hr></caption>";
echo "<tr align=left><td>";
$sql="SELECT school FROM declaration WHERE $sport='y' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "$row[0]<br>";
}
echo "</td></tr></table>";

echo $end_html;
?>
