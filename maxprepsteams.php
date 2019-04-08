<?php
/***************************
MAX PREPS HITS THIS SCRIPT
WITH A VALID KEY TO ACCESS 
TEAMS FOR A
PARTICULAR SPORT
Created 10/9/13
Author Ann Gaffigan, Gazelle INC
****************************/
require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';


$sportsend=strtolower($_REQUEST['sport']);
$apikey=$_REQUEST['apikey'];
$gendersend=strtolower($_REQUEST['gender']);
$sport=$sportsend; $gender="";

$sql="SHOW TABLES LIKE '".$sport."sched'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//ADD GENDER
{
   if($gendersend=="girls") { $sport.="g"; $gender="f"; }
   else { $sport.="b"; $gender="m"; }
}
if($apikey!=$mpkey)
{
   echo "<xml>
	<error>ERROR: Invalid API KEY.</error>
	</xml>";
   exit();
}

$csv="\"ID\",\"FullUniqueSchoolName\",\"Name\",\"Address\",\"Address2\",\"City\",\"State\",\"Zip\",\"Mascot\",\"IsMemberSchool\",\"Gender\",\"Sport\",\"SeasonName\",\"Class\",\"District\",\"Coop?\",\"Cooping Teams\"\r\n";
$table=$sport."school";
$sql2="SELECT * FROM $table ORDER BY outofstate,school";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))     //IF ENTRY EXISTS FOR THIS SCHOOL IN THIS SPORT'S TABLE
{
   $sql="SELECT * FROM headers WHERE id='$row2[mainsch]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $cityst=explode(", ",$row[city_state]);
   if(trim($row2[mascot])!='') $mascot=$row2[mascot];
   else $mascot=$row[mascot];
   if($row2[outofstate]==1) $membersch="No";
   else $membersch="Yes";
   if($row2[othersch1]>0) 
   {
      $coop="Yes";
      $cteams=GetSchool2($row2[mainsch]).", ".GetSchool2($row2[othersch1]);
      if($row2[othersch2]>0)
	 $cteams.=", ".GetSchool2($row2[othersch2]);
      if($row2[othersch3]>0)
         $cteams.=", ".GetSchool2($row2[othersch3]);
   }
   else 
   {
      $coop="No"; $cteams="";
   }
	$csv.="\"$row2[maxprepsid]\",\"$row2[school]";
	if($membersch=="Yes") $csv.=" ($cityst[1])";
        $csv.="\",\"$row2[school]\",\"$row[address1]\",\"$row[address2]\",\"$cityst[0]\",\"$cityst[1]\",\"$row[zip]\",\"$mascot\",\"$membersch\",\"$gendersend\",\"$sportsend\",\"".GetSeason($sport)."\",\"$row2[class]\",\"".GetDistrict($row2[sid],$sport)."\",\"$coop\",\"$cteams\"\r\n";
}

   
$filename=$gendersend.$sportsend."TEAMSforMP.csv";
$open=fopen(citgf_fopen("attachments/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("attachments/$filename");
header("Location:attachments/$filename");
?> 
