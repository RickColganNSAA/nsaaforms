<?php
//submit_vb.php: submits form info for volleyball.
//  Form is submitted from edit_vb.php

//check if user cancelled request
if($submit=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}

require '../functions.php';
require '../../calculate/functions.php';
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
   $table="vb_state";
}
else
{
   $table="vb";
}

//store assistant coaches listed
$asst_coaches=ereg_replace("\'","\'",$asst_coaches);
$sql="UPDATE logins SET asst_coaches='$asst_coaches' WHERE school='$school2' AND level=3 AND sport='Volleyball'";
$result=mysql_query($sql);

$sid=GetSID2($school,'vb');
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
$sql="UPDATE vbschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
$result=mysql_query($sql);
}//END IF LEVEL=1

if($class_dist=="Choose")
   $class_dist="";
   
   if(!empty($_FILES["imageUpload"]["name"])){
	$image = $_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	$sql="UPDATE vbschool SET filename='$image'WHERE sid='$sid'";
    $result=mysql_query($sql);		
    }   
$count=0;
for($i=0;$i<count($id);$i++)
{
     //get school student is currently listed under
     $sql="SELECT school FROM eligibility WHERE id='$id[$i]'";
     $result=mysql_query($sql);
     $row=mysql_fetch_array($result);
     $your_school[$i]=ereg_replace("\'","\'",$row[0]);

     //check if student has already been submitted:
     $sql="SELECT student_id FROM $table WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;

     //put height in correct format (ft-in)
     $height[$i]="$height_ft[$i]-$height_in[$i]";
     if(trim($height[$i])=="-")		//no height given
     {
	$height[$i]="";
     }

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
     if($check[$i]=='y')
        $count++;
     if($count>14) $check[$i]='';	//don't let them check more than 14
     if($update==1)
     {
	$sql="UPDATE $table SET checked='$check[$i]', position='$positions', nickname='".addslashes($nickname[$i])."',jersey_lt='$jersey_lt[$i]', libero='$libero[$i]', height='$height[$i]', team_record='$team_record'";
	$sql.=", good_serves='$good_serves[$i]', att_serves='$att_serves[$i]', ace_serves='$ace_serves[$i]', blocks='$blocks[$i]', kills='$kills[$i]', assists='$assists[$i]'";
	$sql.=", class_dist='$class_dist' WHERE student_id='$id[$i]'";
     }

     //if student has not been submitted yet, use INSERT:
     else if($update==0)
     {
        $sql="INSERT INTO $table (student_id, school, checked, position, nickname, jersey_lt, libero, height, team_record,class_dist";
	$sql.=", good_serves, att_serves, ace_serves, blocks, kills, assists";
        if($school!=$studsch[$i]) $sql.=",co_op";
	$sql.=") VALUES ('$id[$i]', '$your_school[$i]', '$check[$i]', '$positions', '".addslashes($nickname[$i])."','$jersey_lt[$i]', '$libero[$i]', '$height[$i]', '$team_record', '$class_dist'";
	$sql.=",'$good_serves[$i]', '$att_serves[$i]', '$ace_serves[$i]', '$blocks[$i]', '$kills[$i]', '$assists[$i]'";
        if($school!=$studsch[$i]) $sql.=",'$school2'";
	$sql.=")";
     }
     $result=mysql_query($sql);
}

if($send=='y' && $count<=14)	//auto send to view_vb.php and e-mail data to NSAA
{
   header("Location:view_vb.php?session=$session&school_ch=$school_ch&send=y");
   exit();
}
else if($submit=="Save and Keep Editing" || $count>14)
{
   header("Location:edit_vb.php?session=$session&school_ch=$school_ch&count=$count");
   exit();
}
else if($submit=="Save and View Form")
{
   header("Location:view_vb.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
