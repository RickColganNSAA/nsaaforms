<?php
/********************************************************
reimadmin.php
Reimbursements Main Menu for NSAA
Created 9/12/12
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

if($sport && $reset==1)
{
   $sql="DELETE FROM reimbursements WHERE sport='$sport'";
   $result=mysql_query($sql);
}

if($reimid && $sport)
{
   header("Location:reimbursements.php?reimid=$reimid&sport=$sport&session=$session");
   exit();
}
echo $init_html;
echo GetHeader($session);

//SELECT SPORT
echo "<br>";
echo "<form method=post action=\"reimadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<h2>Manage Reimbursements for: <select name=\"sport\" onChange=\"submit();\"><option value=''>Select an Activity</option>";
$sql="SELECT DISTINCT sport FROM reimbursements ORDER BY sport";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[0]\"";
   if($sport==$row[0]) echo " selected";
   echo ">".GetActivityName($row[0])."</option>";
}
echo "</select></h2>";

if($sport && $sport!='')
{
echo "<div style=\"width:500px;text-align:left;\">";
if($reset==1)
{
   echo "<div class=\"alert\">The reimbursement forms for ".GetActivityName($sport)." have been cleared out.</div>";
}
echo "<ul>";

//SELECT A SCHOOL THAT HAS SUBMITTED A REIMBURSEMENT FORM AND EDIT IT:
echo "<li class='bigger'>View/Edit a Reimbursement Form: <select name=\"reimid\"><option value=\"0\">Select School</option>";
$sql="SELECT t1.id,t1.schoolid,t2.school FROM reimbursements AS t1,headers AS t2 WHERE t1.schoolid=t2.id AND t1.sport='$sport' ORDER BY t2.school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($reimid==$row[id]) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select> <input type=submit name='go' value=\"Go\">";
echo "</li><br>";

//EXPORT
echo "<li><a href=\"reimexport.php?session=$session&sport=$sport\">Download ".GetActivityName($sport)." Reimbursement EXPORT (Excel)</a></li><br>";

//RESET
echo "<li><a href=\"reimadmin.php?session=$session&sport=$sport&reset=1\" onClick=\"return confirm('Are you sure you want to clear out the reimbursements for this sport?');\">Clear Out ".GetActivityName($sport)." Reimbursements in the Database</a></li>";

echo "</ul>";
echo "</div>";
}//END IF SPORT

echo "</form>";


echo $end_html;
?>
