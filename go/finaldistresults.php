<?php
/***************************************
finaldistresults.php
What the public will see for results/state qualifiers
Created 9/19/12
Author: Ann Gaffigan
****************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$sport) $sport='go_g';
$sport2=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$districts=$sport."districts";
$indytable=$sport2."distresults_indy";
$teamtable=$sport2."distresults_team";
$schtable=$sport."school";

if(ValidUser($session) && $publish==1)
{
   $sql="UPDATE $db_name2.$districts SET published='".time()."' WHERE id='$distid'";
   $result=mysql_query($sql);
}
else if(ValidUser($session) && $unpublish==1)
{
   $sql="UPDATE $db_name2.$districts SET published='0' WHERE id='$distid'";
   $result=mysql_query($sql);
}

echo $init_html."<table width='100%'><tr align=center><td><br>";

	//THE DISTRICT
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sids=explode(",",$row[sids]); $hostschool=$row[hostschool]; $site=$row[site];
$date=explode("-",$row[dates]);
$showdate=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
$email=$row[email]; $director=$row[director];
$teamct=count($sids);
if($row[resultssubmitted]>0)
{
   $datesub=$row[resultssubmitted];
}
if($row[published]==0 && !ValidUser($session))
{
   echo "<h2>Information not available at this time.</h2>";
   echo $end_html;
   exit();
}
else if(ValidUser($session))	//ALLOW THEM TO PUBLISH/UNPUBLISH
{
   echo "<div class='alert' style='width:500px;text-align:left;'>";
   if($row[published]>0)
      echo "<p>These district results have been <b><u>PUBLISHED</b></u> to the <a href=\"/$sport2.php\" target=\"_blank\">$sportname Page</a> on the NSAA website.</p><p><a href=\"finaldistresults.php?session=$session&sport=$sport&distid=$distid&unpublish=1\">Click here to UN-PUBLISH them.</p>";
   else
      echo "<p>These district results have <b><u>NOT BEEN PUBLISHED</b></u> to the <a href=\"/$sport2.php\" target=\"_blank\">$sportname Page</a> on the NSAA website.</p><p><a href=\"finaldistresults.php?session=$session&sport=$sport&distid=$distid&publish=1\">Click here to PUBLISH them.</p>";
   echo "</div>";
}

echo "<br><table cellspacing=0 cellpadding=5 style='width:750px;'><caption><b>".date("Y")." ".strtoupper("District $row[class]-$row[district] $sportname Results:")."</b><br>$site<br>$showdate</caption>";

echo "<tr align=left><td>";

//INDIVIDUAL RESULTS
echo "<p><b>Individual Qualifiers:</b></p>";
$sql2="SELECT t1.* FROM $indytable AS t1, eligibility AS t2 WHERE t1.studentid=t2.id AND t1.distid='$distid' AND t1.place>0 ORDER BY t1.place,t1.tie";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $cursid=$row2[sid];
   echo "<p>";
   if($row2[tie]=='x') echo "T";
   echo "$row2[place]. ".GetStudentInfo($row2[studentid]).", ".GetSchoolName($cursid,$sport).", $row2[points]</p>";
}

echo "<br>";

//TEAM QUALIFIERS
echo "<p><b>Qualifying Teams:</b></p>";
$sql2="SELECT t1.* FROM $teamtable AS t1, $schtable AS t2 WHERE t1.sid=t2.sid AND t1.distid='$distid' AND t1.place>0 AND t1.place<=3 ORDER BY t1.place,t2.school";
$result2=mysql_query($sql2);
$ix=0; $place=0; $top3teams="";
while($row2=mysql_fetch_array($result2))
{
   echo "<p>".GetSchoolName($row2[sid],$sport).", $row2[points]</p><p>Coach: ".GetCoaches(0,$sport,$row2[sid])."</p><p>";
   $studnames=array(); $studpoints=array();
   for($j=0;$j<5;$j++)
   {
      $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
      $studnames[$j]=GetStudentInfo($row2[$studvar]);
      $studpoints[$j]=$row2[$pointvar];
   }
   array_multisort($studpoints,SORT_NUMERIC,SORT_ASC,$studnames);
   for($j=0;$j<5;$j++)
   {
      if($studpoints[$j]==999) $studpoints[$j]="DQ";
      else if($studpoints[$j]==9999) $studpoints[$j]="WD";
      echo $studnames[$j].", ".$studpoints[$j]."<br>";
   }
   echo "</p><br>";
   $top3teams.=$row2[sid].",";
}
$top3teams=substr($top3teams,0,strlen($top3teams)-1);

//INDIVIDUAL QUALIFIERS
echo "<p><b>Individual Qualifiers:</b></p>";$sql2="SELECT t1.* FROM $indytable AS t1, eligibility AS t2 WHERE t1.studentid=t2.id AND t1.distid='$distid' AND t1.place>0 ORDER BY t1.place,t2.last,t2.first";
$result2=mysql_query($sql2);
$top3=explode(",",$top3teams);
while($row2=mysql_fetch_array($result2))
{
   $cursid=$row2[sid];
   //CHECK TO SEE IF THEY ARE ON ONE OF THE $top3teams - IF NOT, SHOW THEM AND THEIR COACH
   $top3team=0;
   for($i=0;$i<count($top3);$i++)
   {
      if($cursid==$top3[$i]) $top3team=1;
   }
   if($top3team==0)
   {
      echo "<p>".GetSchoolName($cursid,$sport)."</p><p>Coach: ".GetCoaches(0,$sport,$cursid)."</p><p>".GetStudentInfo($row2[studentid]).", $row2[points]</p><br>";
   }
}

//TEAM SCORES, ALL
echo "<p><b>Final Team Scores:</b></p>";
$sql2="SELECT t1.* FROM $teamtable AS t1, $schtable AS t2 WHERE t1.sid=t2.sid AND t1.distid='$distid' AND t1.place>0 ORDER BY t1.place,t2.school";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   if($row2[noscore]=='x') $row2[points]="NTS";
   echo "<p>".GetSchoolName($row2[sid],$sport).", $row2[points]</p>";
}


echo "</td></tr>";

echo "</table>";

echo $end_html;
?>
