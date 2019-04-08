<?php
//forms.php: takes as input selected school and activity from welcome page
//	and displays the corresponding form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get information from db about user using session id
$sql="SELECT t2.* FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school=$row[3];
$sport=$row[4];
$level=$row[5];

if($level==2)
{
   $school_ch=$school;
}
else if($level==3)
{
   $activity_ch=$sport;
   $school_ch=$school;
}
$school_ch2=ereg_replace("\'","\'",$school_ch);

//get requested form
$sport_abb=GetActivityAbbrev2($activity_ch);
$sport_dir=GetActivityAbbrev($activity_ch);

   //header("Location:$sport_dir/view_".$sport_abb.".php?session=$session&school_ch=$school_ch");
   header("Location:$sport_dir/results_main.php?session=$session&school_ch=$school_ch&sport=$sport_abb");
   //header("Location:$sport_dir/results_main.php?session=$session&sport=$sport_abb");
exit();
?>
