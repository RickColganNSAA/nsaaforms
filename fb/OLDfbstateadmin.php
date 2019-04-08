<?php
//fbstateadmin.php: NSAA Football Admin for State Entry Forms
//Created 2/28/09 because e-mailed forms were not being received, possibly due to spam filter
//Author: Ann Gaffigan

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

echo "<form method=post action=\"fbstateadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>State Football Entry Forms Admin</b><br>";
$sport="fb";
$table=$sport."_state";
if(!$sort) $sort="submitted DESC";
$sql="SELECT DISTINCT t2.school,t1.submitted FROM $table AS t1,eligibility AS t2 WHERE t1.student_id=t2.id AND t1.submitted!='' ORDER BY t1.$sort";
$result=mysql_query($sql);
//echo $sql;
if(mysql_num_rows($result)==0)
   echo "</caption><tr align=center><td><br>No forms have been submitted yet.<br><br></td></tr>";
else
{
   echo "<i>The following State ".GetActivityName($sport)." Entry Forms have been Submitted:</i></caption>";
   echo "<tr align=center><td><a class=small href=\"fbstateadmin.php?session=$session&sport=$sport&sort=school\">School</a>";
   if($sort=="school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td colspan=2><b>Exports (.csv for Excel)</b></td><td><a class=small href=\"fbstateadmin.php?session=$session&sport=$sport&sort=submitted%20DESC\">Submitted</a>";
   if($sort=="submitted DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td></tr>";
}
$sportname=GetActivityName($sport);
while($row=mysql_fetch_array($result))
{
   $sch=ereg_replace(" ","",$row[school]);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ="football";

   $filename="$sch$activ";
   $filename.="state";
   $file1=$filename."annc.csv";
   $file2=$filename."dan.csv";

   echo "<tr align=left><td>$row[school]</td><td><a class=small href=\"../attachments.php?session=$session&filename=$file1\">Announcer's Export ($file1</a></td><td><a class=small href=\"../attachments.php?session=$session&filename=$file2\">Export #2 ($file2)</a></td>";
   echo "<td>".date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted])."</td></tr>";
}
echo "</table>";

echo "</form>";

echo $end_html;
?>
