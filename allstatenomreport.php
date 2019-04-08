<?php
/*******************************
allstatenomreport.php
Report of all Academic
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

if(date("m")>=6 && date("m")<8)	//JUNE & JULY - STILL PULL FROM JUST-ENDED YEAR'S DB
{
   //get archived database
   $year2=date("Y");
   $year1=$year2-1;
   $database=$db_name.$year1.$year2;
}
else
   $database=$db_name;


//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

//GET DUE DATE FOR THIS SEASON SO WE CAN CHECK IF NOMINATIONS WERE ON TIME
$field="allstatenom_".$season;
$sql="SELECT duedate FROM misc_duedates WHERE sport='$field'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[0]);
$duedate=mktime(23,59,59,$date[1],$date[2],$date[0]);

$sql="SELECT t1.first,t1.last,t1.school,t2.* FROM eligibility AS t1,allstatenom AS t2 WHERE t1.id=t2.studentid AND ";
if($ontime==1)
   $sql.="t2.transcriptdate<=$duedate AND t2.datesub<=$duedate AND ";
//SEASONS: FALL - go_g,te_b,sb,cc_g,cc_b,vb,fb,pp WINTER - wr,sw_g,sw_b,bb_g,bb_b,sp,de SPRING - mu,jo,ba,so_g,so_b,te_g,tr_g,tr_b,go_b
if($season=="fall")
   $sql.="(sport='go_g' OR sport='te_b' OR sport='sb' OR sport='cc_g' OR sport='cc_b' OR sport='vb' OR sport='fb' OR sport='pp' OR sport='ubo') AND ";
else if($season=="winter")
   $sql.="(sport='wr' OR sport='sw_g' OR sport='sw_b' OR sport='bb_g' OR sport='bb_b' OR sport='sp' OR sport='de') AND ";
else if($season=="spring")
   $sql.="(sport='mu' OR sport='jo' OR sport='ba' OR sport='so_g' OR sport='so_b' OR sport='te_g' OR sport='tr_g' OR sport='tr_b' OR sport='go_b') AND ";
if($released) $sql.="t2.released>0 ";
else $sql.="t2.confirmed>0 ";
$sql.="ORDER BY t1.school,t2.sport,t1.last,t1.first";
$result=mysql_query($sql);
echo $init_html2;
$curschool=""; $cursport="";
while($row=mysql_fetch_array($result))
{
   $school=$row[school];
   if($school!=$curschool)
   {
      echo "<b>$school</b><br>";
      $curschool=$school; $cursport="";
   }
   $sport=GetActivityName($row[sport]);
   if($sport!=$cursport)
   {
      echo "<i>$sport</i><br>";
      $cursport=$sport;
   }
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   if(trim($row[studentname])!='') echo $row[studentname];
   else echo GetStudentInfo($row[studentid],FALSE,$database); //"$row[first] $row[last]";
   echo "<br>";
}
echo $end_html2;
?>
