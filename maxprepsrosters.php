<?php
/***************************
MAX PREPS HITS THIS SCRIPT
WITH A VALID KEY TO ACCESS 
ROSTERS FOR A 
PARTICULAR SPORT
Created 7/23/13
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

   
$xml="<xml>
<DateTime>".time()."</DateTime>
<Gender>$gendersend</Gender>
<Sport>$sportsend</Sport>
<Rosters>\n";
$sql="SELECT * FROM eligibility WHERE";
if($sportsend=='fb') $sql.=" (fb68='x' OR fb11='x')";
else $sql.=" $sportsend='x'";
if($gender!='') $sql.=" AND gender='".strtoupper($gender)."'";
$sql.=" ORDER BY school,last,first";
//echo $sql;
$result=mysql_query($sql);
$cursch="";
while($row=mysql_fetch_array($result))
{
   $school=$row[school];
   if($cursch!=$school)	//NEW SCHOOL
   {
      if($cursch!='') $xml.="</Roster>\r\n";
      $cursch=$school;

      $sid=GetSID2($school,$sport);
      $xml.="<Roster>
	<SchoolID>".GetMaxPrepsID($sid,$sport)."</SchoolID>\r\n";
   //echo "$school<br />"; flush();
   }
		$xml.="<Student>
		<FirstName>$row[first]</FirstName>
		<LastName>$row[last]</LastName>
		<Grade>".GetYear($row[semesters])."</Grade>
		</Student>\r\n";
}
if(mysql_num_rows($result)>0) $xml.="</Roster>\r\n";
$xml.="</Rosters>
</xml>";

$filename=$gendersend.$sportsend."ROSTERforMP.xml";
$open=fopen(citgf_fopen("attachments/$filename"),"w");
fwrite($open,$xml);
fclose($open); 
 citgf_makepublic("attachments/$filename");
header("Location:attachments/$filename");
?> 
