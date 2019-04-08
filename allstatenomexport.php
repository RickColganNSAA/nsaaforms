<?php
/*******************************
allstatenomexport.php
Excel Export of all Academic
All State Nominations
Created 5/24/10
Author Ann Gaffigan
********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);


//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01"))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

$sql="SELECT t1.first,t1.last,t1.school,t2.* FROM eligibility AS t1,allstatenom AS t2 WHERE t1.id=t2.studentid AND t2.datesub>0 AND ";
if($released) $sql.="t2.released>0 ";
else $sql.="t2.confirmed>0 ";
$sql.="ORDER BY t2.sport,t1.school,t1.last,t1.first";
$result=mysql_query($sql);
$csv="Activity\tSchool\tSeason-Year\tNominee\tEdited Name\tGPA\tDate Nomination Submitted\tDate Transcript Submitted\tDate Released\r\n";
while($row=mysql_fetch_array($result))
{
   $sid=GetSID2($row[school],$row[sport],date("Y"));
   if($sid!="NO SID FOUND") $school=GetSchoolName($sid,$row[sport],date("Y"));
   else $school=$row[school];
   if($row[sport]=='sp') $season="Winter";
   else if($row[sport]=='pp') $season="Fall";
   else $season=GetSeason($row[sport]);
   $csv.=GetActivityName($row[sport])."\t$school\t".GetSeason($row[sport])." ".date("Y",$row[datesub])."\t".GetStudentInfo($row[studentid])."\t$row[studentname]\t$row[gpa]\t".date("m/d/y",$row[datesub])." at ".date("g:ia",$row[datesub])."\t".date("m/d/y",$row[transcriptdate])." at ".date("g:ia",$row[transcriptdate])."\t";
   if($row[released]==0) $csv.="NOT YET RELEASED\t";
   else $csv.=date("m/d/y",$row[released])." at ".date("g:ia",$row[released])."\t";
   $csv.="\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/AcademicAllStateNominationsExport.xls"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/AcademicAllStateNominationsExport.xls");
header("Location:exports.php?session=$session&filename=AcademicAllStateNominationsExport.xls");
exit();
?>
