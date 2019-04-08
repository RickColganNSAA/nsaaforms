<?php
/***************************************
partnumbers.php
Participation Numbers
Created 9/25/12
Author: Ann Gaffigan
****************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$sport) $sport='go_g';
if(!$class) $class='A';
$sport2=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$districts=$sport."districts";
$indytable=$sport2."distresults_indy";
$teamtable=$sport2."distresults_team";
$schtable=$sport."school";

echo $init_html."<table width='100%' class='nine'><tr align=left><td><br><u><b>".date("Y")." $sportname Class $class State Participation Numbers</b></u><br><br>";

echo "<table frame=all rules=all class='nine' cellspacing=0 cellpadding=5 style=\"border:#808080 1px solid;\">";
echo "<tr align=center><td><b>School</b></td><td><b>Coach</b></td><td><b>Count</b></td></tr>";

$sql="SELECT * FROM $schtable WHERE class='$class' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))	//FOR EACH SCHOOL IN THIS CLASS - ARE THERE QUALIFIERS?
{
   $qualct=0;
   $sql2="SELECT * FROM $teamtable WHERE sid='$row[sid]' AND place<=3 AND place>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)	//QUALIFIED AS A TEAM
   {
      $row2=mysql_fetch_array($result2);
      for($j=0;$j<5;$j++)
      {
         $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
         if($row2[$studvar]>0)
	    $qualct++;
      }
   }
   else	//MAYBE INDIVIDUALS QUALIFIED
   {
      $sql2="SELECT * FROM $indytable WHERE sid='$row[sid]' AND place>0 AND studentid>0 ORDER BY place";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)       //QUALIFIED 
      {
         while($row2=mysql_fetch_array($result2))
	 {
	    $qualct++;
	 }
      }
   }
   if($qualct>0)
      echo "<tr align=left><td>".GetSchoolName($row[sid],$sport)."</td><td>".GetCoaches(0,$sport,$row[sid])."</td><td>$qualct</td></tr>";
}
echo "</table>";


echo $end_html;
?>
