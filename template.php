<?php
/********************************************************
template.php
Description of script
Created [date]
Author: [author]
*********************************************************/

//Require files
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
$level=GetLevel($session);
if(!ValidUser($session))	//May want to also exclude certain user levels here
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid || $level!=1)	//SCHOOL USER - GET SCHOOL ID BASED ON SESSION
{
   $schoolid=GetSchoolID($session);
}
$school=GetSchool2($schoolid);
//GET SCHOOL YEAR (fall and spring)
if(date("m")>=6) $year1=date("Y");	//Starting June 1, start new year
else $year1=date("Y")-1;
$year2=$year1+1;

//Get Header
if($print==1) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);


echo $init_html;
echo $header;

//STUFF GOES HERE

echo $end_html;
?>
