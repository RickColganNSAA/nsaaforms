<?php
/*******************************************
showdistresults.php
for Public Website: Tennis District Results
Created 7/6/09
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'tefunctions.php';
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$sport || $sport=='te_b')
{
   $sport='te_b';
   $gender="M";
   $max=3;
   $sportname="Boys Tennis";
   $districts="tebdistricts";
}
else
{
   $sport='te_g';
   $gender="F";
   $max=4;
   $sportname="Girls Tennis";
   $districts="tegdistricts";
}
$results=$sport."distresults";

if(!$distid)
{
   echo "ERROR: No District Selected.";
   exit();
}
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $district=$row[district];
if($row[resultssubmitted]==0)
{
   echo $init_html;
   echo "<table class='nine' width='100%'><tr align=center><td><br><br><br>";
   echo "The results for District $class-$district are not yet available.";
   echo "</td></tr></table>";
   echo $end_html;
   exit();
}

echo $init_html;
echo "<table width='100%'><tr align=center><td><br>";

echo "<table class=nine cellspacing=4 cellpadding=10><caption><b>".$sportname." District $class-$district Results:</b><br><br>";
echo "</b></caption>";

echo "<tr align=left valign=top>";

//#1 SINGLES:
echo "<td><b>#1 SINGLES:</b><br><br>";

for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT t1.*,t2.player1,t2.player2 FROM eligibility AS t1,".$sport."distresults AS t2,headers AS t3 WHERE t3.school=t1.school AND t1.id=t2.player1 AND t2.distid='$distid' AND t2.division='singles1' AND t2.place='$place'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$place)&nbsp;";
   if(mysql_num_rows($result)>0)
      echo "$row[first] $row[last] - ".GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"))." (".GetRecord($sport,'singles1','Varsity',$row[player1],$row[player2]).")<br>";
   else echo "<br>";
}
echo "</td>";

//#2 SINGLES:
echo "<td><b>#2 SINGLES:</b><br><br>";
for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT t1.*,t2.player1,t2.player2 FROM eligibility AS t1,".$sport."distresults AS t2,headers AS t3 WHERE t1.school=t3.school AND t1.id=t2.player1 AND t2.distid='$distid' AND t2.division='singles2' AND t2.place='$place'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$place)&nbsp;";
   if(mysql_num_rows($result)>0)
      echo "$row[first] $row[last] - ".GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"))." (".GetRecord($sport,'singles2','Varsity',$row[player1],$row[player2]).")<br>";
   else echo "<br>";
}
echo "</td>";
echo "</tr>";

echo "<tr align=left valign=top>";

//#1 DOUBLES:
echo "<td><b>#1 DOUBLES:</b><br><br>";
for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT t1.*,t2.player1,t2.player2 FROM eligibility AS t1,".$sport."distresults AS t2,headers AS t3 WHERE t1.school=t3.school AND t1.id=t2.player1 AND t2.distid='$distid' AND t2.division='doubles1' AND t2.place='$place'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sql2="SELECT * FROM eligibility WHERE id='$row[player2]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "$place)&nbsp;";
   if(mysql_num_rows($result)>0)
      echo "$row[first] $row[last], $row2[first] $row2[last] - ".GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"))." (".GetRecord($sport,'doubles1','Varsity',$row[player1],$row[player2]).")<br>";
   else echo "<br>";
}
echo "</td>";
//#2 DOUBLES:
echo "<td><b>#2 DOUBLES:</b><br><br>";
for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT t1.*,t2.player1,t2.player2 FROM eligibility AS t1,".$sport."distresults AS t2,headers AS t3 WHERE t1.school=t3.school AND t1.id=t2.player1 AND t2.distid='$distid' AND t2.division='doubles2' AND t2.place='$place'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sql2="SELECT * FROM eligibility WHERE id='$row[player2]'";   
   $result2=mysql_query($sql2);   
   $row2=mysql_fetch_array($result2);   
   echo "$place)&nbsp;";
   if(mysql_num_rows($result)>0)
      echo "$row[first] $row[last], $row2[first] $row2[last] - ".GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"))." (".GetRecord($sport,'doubles2','Varsity',$row[player1],$row[player2]).")<br>";
   else echo "<br>";
}
echo "</td>";

echo "</tr>";
//TEAM SCORES
$sql="SELECT teamscores FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<tr align=center><td colspan=2><table cellspacing=0 cellpadding=0 class=nine><tr align=left><td><b>Team Scores:</b><br>";
echo "$row[teamscores]</td></tr>";
echo "</table></td></tr>";

echo "</table>";
echo $end_html;
?>
