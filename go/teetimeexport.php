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

//GET STUDENTS AND TEE TIMES INTO ARRAY
$schs=array();
$studs=array();
$points=array();
$dists=array();
$ix=0;
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
      for($j=0;$j<5;$j++)
      {
         $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
	 $schs[$ix]=GetSchoolName($row2[sid],$sport);
	 $studs[$ix]=$row2[$studvar];
	 $points[$ix]=$row2[$pointvar];
	 $dists[$ix]=$row2[distid];
	 $ix++;
      }
   }
   else	//MAYBE INDIVIDUALS QUALIFIED
   {
      $sql2="SELECT * FROM $indytable WHERE sid='$row[sid]' AND place>0 ORDER BY place";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)       //QUALIFIED AS A TEAM
      {
         while($row2=mysql_fetch_array($result2))
	 {
	    $schs[$ix]=GetSchoolName($row[sid],$sport);
	    $studs[$ix]=$row2[studentid];
	    $points[$ix]=$row2[points];
	    $dists[$ix]=$row2[distid];
	    $ix++;
	 }
      }
   }
}

array_multisort($points,SORT_NUMERIC,SORT_DESC,$schs,$studs,$dists);

$csv.="\"Name\",\"Grade\",\"School\",\"District\",\"Score\"\r\n";
for($i=0;$i<count($points);$i++)
{
   $sql="SELECT * FROM $db_name2.$districts WHERE id='$dists[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $dist=$row[district];
   $sql="SELECT semesters FROM eligibility WHERE id='$studs[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $grade=GetYear($row[0]);
   $csv.="\"".GetStudentInfo($studs[$i],FALSE)."\",\"$grade\",\"$schs[$i]\",\"$class-$dist\",\"$points[$i]\"\r\n";
}

$filename=strtoupper($sport2)."Class".$class.".csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
header("Location:../exports.php?session=$session&filename=$filename");
exit();
?>
