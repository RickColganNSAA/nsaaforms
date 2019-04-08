<?php
/***********************************
actregreport.php
Report of # of schools registered
for each activity via schoolregistration.php
Created 6/17/14
Author: Ann Gaffigan
************************************/
require '../calculate/functions.php';
require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//GET SCHOOL YEAR
if(date("m")>=6) $year1=date("Y");
else $year1=date("Y")-1;
$year2=$year1+1;

$csv="\"Activity\",\"Participating\",\"Postseason\",\"PAID\",\"NOT PAID\"\r\n";
for($i=0;$i<count($regacts);$i++)
{
   $csv.="\"".GetActivityName($regacts[$i])."\",";
   $sql="SELECT DISTINCT schoolid FROM schoolregistration WHERE sport='$regacts[$i]' AND participate='x' AND datesub>0";
   $result=mysql_query($sql);
   $csv.="\"".mysql_num_rows($result)."\",";
   $sql="SELECT DISTINCT schoolid FROM schoolregistration WHERE sport='$regacts[$i]' AND postseason='x' AND datesub>0";
   $result=mysql_query($sql);
   $csv.="\"".mysql_num_rows($result)."\",";
   $sql="SELECT DISTINCT schoolid FROM schoolregistration WHERE sport='$regacts[$i]' AND participate='x' AND datepaid!='0000-00-00' AND datesub>0";
   $result=mysql_query($sql);
   $csv.="\"".mysql_num_rows($result)."\",";
   $sql="SELECT DISTINCT schoolid FROM schoolregistration WHERE sport='$regacts[$i]' AND participate='x' AND datepaid='0000-00-00' AND datesub>0";
   $result=mysql_query($sql);
   $csv.="\"".mysql_num_rows($result)."\",";
   $csv.="\r\n";
}
$filename="ActivityRegistrationReport_".date("mdy").".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
header("Location:exports.php?session=$session&filename=$filename");
?>
