<?php
/***************************************
programexport.php
Export for State Program
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
if(!$class) $class='A';
$sport2=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$districts=$sport."districts";
$indytable=$sport2."distresults_indy";
$teamtable=$sport2."distresults_team";
$schtable=$sport."school";

echo $init_html."<table width='100%' class='nine'><tr align=left><td><br><u><b>".date("Y")." $sportname Class $class Qualifiers</b></u><br><br>";

$sql="SELECT * FROM $schtable WHERE class='$class' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))	//FOR EACH SCHOOL IN THIS CLASS - ARE THERE QUALIFIERS?
{
   $qual=0;
   $sql2="SELECT * FROM $teamtable WHERE sid='$row[sid]' AND place<=3 AND place>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)	//QUALIFIED AS A TEAM
   {
      $row2=mysql_fetch_array($result2);
      echo "<b>".GetSchoolName($row2[sid],$sport)."</b><br>Coach: ".GetCoaches(GetSchoolID2(GetSchoolName($row2[sid],$sport)),$sport)."<br>";
      for($j=0;$j<5;$j++)
      {
         $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
	 $student=preg_replace("/ \(/",", ",GetStudentInfo($row2[$studvar]));
         echo substr($student,0,strlen($student)-1)."<br>";
      }
      echo "<br>";
   }
   else	//MAYBE INDIVIDUALS QUALIFIED
   {
      $sql2="SELECT * FROM $indytable WHERE sid='$row[sid]' AND place>0 ORDER BY place";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)       //QUALIFIED AS A TEAM
      {
         echo "<b>".GetSchoolName($row[sid],$sport)."</b><br>Coach: ".GetCoaches(GetSchoolID2(GetSchoolName($row[sid],$sport)),$sport)."<br>";
         while($row2=mysql_fetch_array($result2))
	 {
            $student=preg_replace("/ \(/",", ",GetStudentInfo($row2[studentid]));
            echo substr($student,0,strlen($student)-1)."<br>";
	 }
	 echo "<br>";
      }
   }
}


echo "</td></tr>";

echo "</table>";

echo $end_html;
?>
