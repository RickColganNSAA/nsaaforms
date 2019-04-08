<?php
/*********************************
meetsadmin.php
Manage Tennis Meets
Created 3/23/09
Author: Ann Gaffigan
*********************************/
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
$sportname=GetActivityName($sport);

echo "<br><table class=nine width=\"550px\" cellspacing=2 cellpadding=3><caption><b>Manage $sportname Meets:</b>";
if($level==1) echo "<br><a href=\"te_gmain.php?session=$session\" class=small>$sportname MAIN MENU for Seeding & Brackets</a>";
echo "<hr></caption>";

echo "<tr align=left><td>Please click on a meet to view, edit or add results for that meet.</td></tr>";
echo "<tr align=center><td><table cellspacing=0 cellpadding=2 frame=all rules=all style=\"border:#333333 1px solid;\"><tr align=center><td><b>Meet Date(s)</b></td><td><b>Meet Name @ Meet Site</b><br>(Click to view results)</td><td><b>Number of Results</b></td></tr>";
$sql="SELECT * FROM ".$sport."meets ORDER BY startdate ASC";
$result=mysql_query($sql);
$meetids="";
while($row=mysql_fetch_array($result))
{
   echo "<tr><td align=center>";
   $start=split("-",$row[startdate]);
   $end=split("-",$row[enddate]);
   if($row[startdate]==$row[enddate])
      echo "$start[1]/$start[2]";
   else
      echo "$start[1]/$start[2]-$end[1]/$end[2]";
   echo "</td><td align=left>";
   echo "<a target=\"_blank\" class=small href=\"listmeetresults.php?sport=$sport&school_ch=$school_ch&session=$session&meetid=$row[id]\">$row[meetname] at $row[meetsite]</a></td>";
   $sql2="SELECT * FROM ".$sport."meetresults WHERE meetid='$row[id]'";
   $result2=mysql_query($sql2);
   echo "<td align=center>".mysql_num_rows($result2)."</td>";
   echo "</tr>";
}
echo "</table></td></tr>";
echo "</table>";

echo $end_html;
?>
