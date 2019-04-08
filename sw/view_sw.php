<?php

require '../functions.php';
require '../variables.php';

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
echo $header;

echo "<center><br>";
echo "<table><caption><b>NSAA Swimming Verification Forms:</b><hr></caption>";
echo "<tr align=left><td align=left>";
echo "<a href=\"edit_sw_verify.php?session=$session&school_ch=$school_ch\" class=small>Click Here to Start a New Verification Form</a><br><br>";
echo "<b>The following Verification Forms have been saved but NOT SUBMITTED to the NSAA:<br></b><ul>";
$sql="SELECT * FROM sw_verify WHERE school='$school2' AND submitted!='y' ORDER BY datesub";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<li><a href=\"edit_sw_verify.php?session=$session&formid=$row[0]&school_ch=$school_ch\" class=small>$row[3] at $row[5]</a>&nbsp; (saved on ".date("M d, Y",$row[7]).")<br>";
}
if(mysql_num_rows($result)==0) echo "(NONE)";
echo "</ul>";
echo "<b>The following Verification Forms have been SUBMITTED to the NSAA:<br></b><ul>";
$sql="SELECT * FROM sw_verify WHERE school='$school2' AND submitted='y' ORDER BY datesub";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<li><a href=\"view_sw_verify.php?session=$session&formid=$row[0]&school_ch=$school_ch\" class=small>$row[3] at $row[5]</a>&nbsp; (saved on ".date("M d, Y",$row[7]).")<br>";
}
if(mysql_num_rows($result)==0) echo "(NONE)";
echo "</ul>";
echo "</td></tr>";
echo "</table>";

echo $end_html;
?>
