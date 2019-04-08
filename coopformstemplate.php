<?php
/********************************************************
coopformstemplate.php
Template for Coop Forms (user must be logged in and an AD)
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
if(!ValidUser($session) || $level>2)	//If user isn't logged in OR is at a level less than AD, kick them out
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1 Admin) or belongs to (Level 2 AD)
if(!$schoolid || $level!=1)	//SCHOOL USER - GET SCHOOL ID BASED ON SESSION
{
   $schoolid=GetSchoolID($session);
}
$school=GetSchool2($schoolid);

//Get Header, based on if this is a printer-friendly version or not
if($print==1) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);

//Echo Header
echo $init_html;
echo $header;

//STUFF GOES HERE

//Echo Footer
echo $end_html;
?>
