<?php
//submit_sb.php: submits form info for softball.
//  Form is submitted from edit_sb.php

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

$level=GetLevel($session);

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
//echo '<pre>'; print_r($_POST); exit;

if($state==1)
{
   $table="sb_state";
}
else
{
   $table="sb";
}

//update asst coaches info
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE level=3 AND school='$school2' AND sport='Softball'";
$result=mysql_query($sql);

$sid=GetSID2($school,'sb');
$schoolid=GetSchoolID2($school);
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
$sql="UPDATE sbschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
$result=mysql_query($sql);
//echo $sql; exit();
}//END IF LEVEL=1
   if(!empty($_FILES["imageUpload"]["name"])){
	$image = $_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	$sql="UPDATE sbschool SET filename='$image'WHERE sid='$sid'";
    $result=mysql_query($sql);		
    }
if($class_dist=="Choose")
{
   $class_dist="";
}
$team_record="$wins-$losses";
for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT student_id FROM $table WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;

     //get position(s) in right format
     $positions="";
     for($j=0;$j<count($position[$i]);$j++)
     {
	$next=$position[$i][$j];
	$positions.="$next/";
     }
     $positions=substr($positions,0,strlen($positions)-1);

     //if student has been submitted before, use UPDATE or DELETE:
     if($update==1)
     {
	$sql="UPDATE $table SET checked='$check[$i]', nickname='".addslashes($nickname[$i])."', jersey_lt='$jersey_lt[$i]', jersey_dk='$jersey_dk[$i]', position='$positions', ";
	$sql.="average='$average[$i]', at_bats='$at_bats[$i]', hits='$hits[$i]', runs_scored='$runs_scored[$i]', runs_batted='$runs_batted[$i]', home_runs='$home_runs[$i]', pitching_record='$pitching_record[$i]', pitching_era='$pitching_era[$i]', ";
	$sql.="team_record='$team_record', class_dist='$class_dist' WHERE student_id='$id[$i]'";
     }
     else 
     {
        $sql="INSERT INTO $table (nickname, student_id, school, checked, jersey_lt, jersey_dk, position, ";
	$sql.="average, at_bats, hits, runs_scored, runs_batted, home_runs, pitching_record, pitching_era, ";
	$sql.="team_record, class_dist";
        if($school!=$studsch[$i]) $sql.=",co_op";
        $sql.=") VALUES ('".addslashes($nickname[$i])."','$id[$i]', '$school2', '$check[$i]', '$jersey_lt[$i]', '$jersey_dk[$i]', '$positions', ";
	$sql.="'$average[$i]', '$at_bats[$i]', '$hits[$i]', '$runs_scored[$i]', '$runs_batted[$i]', '$home_runs[$i]', '$pitching_record[$i]', '$pitching_era[$i]', ";
	$sql.="'$team_record', '$class_dist'";
        if($school!=$studsch[$i]) $sql.=",'$school2'";
	$sql.=")";
     }

     $result=mysql_query($sql);
}
 if (!empty($_POST[check]))
{
foreach ($_POST[check] as $key=>$value)
	{
 	if ((!empty($value)) && (empty($jersey_lt[$key]))) 
	{$light[]=$id[$key];}
	if ((!empty($value)) && (empty($jersey_dk[$key])))
	{$dark[]=$id[$key];} 
	}
    $lj=implode(",",$light);
    $dj=implode(",",$dark);
	if ((!empty($light)) || (!empty($dark)))
	{
	header("Location:edit_sb.php?session=$session&school_ch=$school_ch&light=$lj&dark=$dj");
	exit();
	}
} 
if($send=='y')  //auto send to view_sb.php and e-mail data to NSAA
{
   $sql="UPDATE $table SET submitted='".time()."' WHERE school='$school2'";
   $result=mysql_query($sql);
   header("Location:view_sb.php?session=$session&school_ch=$school_ch&send=y");
   exit();
}
else if($submit=="Save and Keep Editing")
{
   header("Location:edit_sb.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save and View Form")
{
   header("Location:view_sb.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
