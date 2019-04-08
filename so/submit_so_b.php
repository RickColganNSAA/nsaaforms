<?php
//submit_so_b.php: submits form info for boys soccer.
//  Form is submitted from edit_so_b.php

//check if user cancelled request
if($submit=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}
require '../../calculate/functions.php';
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

//get school that user chose (Level 1) or belongs to (Level 2,3)
if(!$school_ch || trim($school_ch)=="")  //Level 2, 3
{
   $school=GetSchool($session);
}
else	//Level 1
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

//check if this is state form submission
if($state==1)
{
   $table="so_bstate";
}
else
{
   $table="so_b";
}

$sid=GetSID2($school,'sob');
$schoolid=GetSchoolID2($school);
$level=GetLevel($session);
if($level==1)
{
//SUPER/PRINCIPAL/AD
for($i=0;$i<count($loginid);$i++)
{
   $sql="UPDATE logins SET name='".addslashes($name[$i])."' WHERE id='$loginid[$i]'";
   $result=mysql_query($sql);
}

//ENROLLMENT
$sql="UPDATE headers SET enrollment='$enrollment' WHERE id='$schoolid'";
$result=mysql_query($sql);

//HISTORICAL INFO
$sql="UPDATE sobschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
$result=mysql_query($sql);
if(mysql_error()) { echo mysql_error(); exit(); }
}//END IF LEVEL=1
   if(!empty($_FILES["imageUpload"]["name"])){
	$image = $_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	$sql="UPDATE sobschool SET filename='$image'WHERE sid='$sid'";
    $result=mysql_query($sql);		
    }
//store assistant coaches listed
$asst_coaches=ereg_replace("\'","\'",$asst_coaches);
$sql="UPDATE logins SET asst_coaches='$asst_coaches' WHERE school='$school2' AND level=3 AND sport='Boys Soccer'";
$result=mysql_query($sql);

$sql="SELECT * FROM $table WHERE (school='$school2' OR co_op='$school2') AND checked!=''";
$result=mysql_query($sql);
$count=mysql_num_rows($result);

for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT student_id FROM $table WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;

     //put record in correct format (w-l)
     $team_record="$wins-$losses";
     if(trim($team_record)=="-")	//no record given
     {
	$team_record="";
     }

     //get positions in correct format
     $positions="";
     for($j=0;$j<count($position_array[$i]);$j++)
     {
	$next=$position_array[$i][$j];
	$positions.="$next/";
     }
     $positions=substr($positions,0,strlen($positions)-1);

     //if student has been submitted before, use UPDATE or DELETE:
     if($update==1)
     {
	$sql="UPDATE $table SET nickname='".addslashes($nickname[$i])."',checked='$check[$i]', position='$positions', jersey_lt='$jersey_lt[$i]', jersey_dk='$jersey_dk[$i]', goals='$goals[$i]', record='$team_record', assists='$assists[$i]', gk_games='$games[$i]', gk_goals_allowed='$allowed[$i]', gk_saves='$saves[$i]' WHERE student_id='$id[$i]'";
     }
     else 
     {
        $sql="INSERT INTO $table (nickname,student_id, checked, position, jersey_lt, jersey_dk, goals, record, assists, gk_games, gk_goals_allowed, gk_saves, school";
        if($school!=$studsch[$i]) $sql.=",co_op";
        $sql.=") VALUES ('".addslashes($nickname[$i])."','$id[$i]', '$check[$i]', '$positions', '$jersey_lt[$i]', '$jersey_dk[$i]', '$goals[$i]', '$team_record', '$assists[$i]', '$games[$i]', '$allowed[$i]', '$saves[$i]', '$school2'";
        if($school!=$studsch[$i]) $sql.=",'$school2'";
        $sql.=")";
     }

     $result=mysql_query($sql);
}

if($count>24)
{
   header("Location:edit_so_b.php?session=$session&school_ch=$school_ch&counterror=1");
   exit();
}
else if($send=='y')	//auto send to view_so_b.php and e-mail data to NSAA
{
   header("Location:view_so_b.php?session=$session&school_ch=$school_ch&send=y");
   exit();
}
else if($submit=="Save and Keep Editing")
{
   header("Location:edit_so_b.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save and View Form")
{
   header("Location:view_so_b.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
