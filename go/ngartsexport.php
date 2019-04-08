<?php
/***************************************
ngartsexport.php
Export for State Program
Created 10/2/12
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

$schs=array();
$studs=array();
$points=array();
$dists=array();
$teamix=1;
$sql="SELECT * FROM $schtable WHERE class='$class' ORDER BY school";
$result=mysql_query($sql);
$csv="<table><tr bgcolor='#e0e0e0'><td>Team</td><td>First Name</td><td>Last Name</td><td>Grade</td><td>School</td></tr>";
$bgcolor="#ffffff";
while($row=mysql_fetch_array($result))	//FOR EACH SCHOOL IN THIS CLASS - ARE THERE QUALIFIERS?
{
   $sql2="SELECT * FROM $teamtable WHERE sid='$row[sid]' AND place<=3 AND place>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)	//QUALIFIED AS A TEAM
   {
      $row2=mysql_fetch_array($result2);
      for($j=0;$j<5;$j++)
      {
         $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
	 if($j==1) $csv.="<tr bgcolor='$bgcolor'><td>$teamix</td>";
	 else $csv.="<tr bgcolor='$bgcolor'><td> </td>";
	 $sql3="SELECT first,last,semesters FROM eligibility WHERE id='".$row2[$studvar]."'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
   	 if(ereg("[(]",$row3[first]))      //nickname
   	 {
      	 	$first_nick=explode("(",$row3[first]);
      		$first_nick[1]=trim($first_nick[1]);
      		$first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      		$row3[first]=$first;
   	 }
	 $csv.="<td>$row3[first]</td><td>$row3[last]</td><td>".GetYear($row3[semesters])."</td><td>".GetSchoolName($row2[sid],$sport)."</td></tr>";
      }
      if($bgcolor=="#ffffff") $bgcolor="#e0e0e0";
      else $bgcolor="#ffffff";
      $teamix++;
   }
}
$sql="SELECT * FROM $schtable WHERE class='$class' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))  //FOR EACH SCHOOL IN THIS CLASS - ARE THERE QUALIFIERS?
{
   $sql2="SELECT * FROM $teamtable WHERE sid='$row[sid]' AND place<=3 AND place>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)	//MAYBE INDIVIDUALS QUALIFIED
   {
      $sql2="SELECT * FROM $indytable WHERE sid='$row[sid]' AND place>0 ORDER BY place";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)       //QUALIFIED AS A TEAM
      {
         while($row2=mysql_fetch_array($result2))
	 {
            $csv.="<tr bgcolor='$bgcolor'><td> </td>";
            $sql3="SELECT first,last,semesters FROM eligibility WHERE id='".$row2[studentid]."'";
            $result3=mysql_query($sql3);
            $row3=mysql_fetch_array($result3);
            if(ereg("[(]",$row3[first]))      //nickname
            {
                $first_nick=explode("(",$row3[first]);
                $first_nick[1]=trim($first_nick[1]);
                $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
                $row3[first]=$first;
            }
	    $csv.="<td>$row3[first]</td><td>$row3[last]</td><td>".GetYear($row3[semesters])."<td>".GetSchoolName($row[sid],$sport)."</td></tr>";
	 }
      }
   }
}
$csv.="</table>";

$filename="NGA_RTS_Class".$class.".xls";
$open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
header("Location:../exports.php?session=$session&filename=$filename");
exit();
?>
