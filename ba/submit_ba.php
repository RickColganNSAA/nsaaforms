<?php
//submit_ba.php: submits form info for baseball.
//  Form is submitted from edit_ba.php

require '../functions.php';
require '../variables.php';

//check if user cancelled request
if($save=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$level=GetLevel($session);

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
$schoolid=GetSchoolID2($school);
$sid=GetSID2($school,'ba');

//check if this is state form submission
if($state==1)
{
   $table="ba_state";
}
else
{
   $table="ba";
}

if($class_dist=="Choose")
{
   $class_dist="";
}

//update asst coaches
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE sport='Baseball' AND school='$school2' AND level='3'";
$result=mysql_query($sql);

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
$sql="UPDATE baschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
$result=mysql_query($sql);
}//END IF LEVEL=1
   if(!empty($_FILES["imageUpload"]["name"])){
	$image = $_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	$sql="UPDATE baschool SET filename='$image'WHERE sid='$sid'";
    $result=mysql_query($sql);		
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
	/*
     for($j=0;$j<count($position[$i]);$j++)
     {
	$next=$position[$i][$j];
	$positions.="$next/";
     }
	*/
     for($j=0;$j<count($ba_positions);$j++)
     {
	 if($position[$i][$j]=='x')
	    $positions.=$ba_positions[$j]."/";
     }
     $positions=substr($positions,0,strlen($positions)-1);

     //put pitching record into one string
     $pitching_record[$i]=$pitching_record_wins[$i]."-".$pitching_record_losses[$i]."-".$pitching_record_saves[$i];

     //if student has been submitted before, use UPDATE or DELETE:
     if($update==1)
     {
	$sql="UPDATE $table SET checked='$check[$i]', nickname='".addslashes($nickname[$i])."',jersey_lt='$jersey_lt[$i]', jersey_dk='$jersey_dk[$i]', position='$positions', average='$average[$i]', at_bats='$at_bats[$i]', hits='$hits[$i]', runs_scored='$runs_scored[$i]', runs_batted='$runs_batted[$i]', home_runs='$home_runs[$i]', pitching_record='$pitching_record[$i]', pitching_era='$pitching_era[$i]', team_record='$team_record', class_dist='$class_dist' WHERE student_id='$id[$i]'";
     }

     //if student has not been submitted yet, use INSERT:
     else if($update==0)
     {
        $sql="INSERT INTO $table (student_id, school, checked, nickname, jersey_lt, jersey_dk, position, average, at_bats, hits, runs_scored, runs_batted, home_runs, pitching_record, pitching_era, team_record, class_dist";
        if($school!=$studsch[$i]) $sql.=",co_op";
        $sql.=") VALUES ('$id[$i]', '$school2', '$check[$i]', '".addslashes($nickname[$i])."','$jersey_lt[$i]', '$jersey_dk[$i]', '$positions', '$average[$i]', '$at_bats[$i]', '$hits[$i]', '$runs_scored[$i]', '$runs_batted[$i]', '$home_runs[$i]', '$pitching_record[$i]', '$pitching_era[$i]', '$team_record', '$class_dist'";
        if($school!=$studsch[$i]) $sql.=",'$school2'";
        $sql.=")";
     }

     $result=mysql_query($sql);
}

//store Co-op students' info:
for($i=0;$i<count($coop_student);$i++)
{
   //get position(s) in right format
   $positions="";
   for($j=0;$j<count($coop_position[$i]);$j++)
   {
      $next=$coop_position[$i][$j];
      $positions.="$next/";
   }
   $positions=substr($positions,0,strlen($positions)-1);

   $coop_pitching_record[$i]=$coop_pitching_record_wins[$i]."-".$coop_pitching_record_losses[$i]."-".$coop_pitching_record_saves[$i];

   $sql="UPDATE $table SET checked='$coop_check[$i]', class_dist='$class_dist', team_record='$team_record', jersey_lt='$coop_jersey_lt[$i]', jersey_dk='$coop_jersey_dk[$i]', position='$positions', average='$coop_average[$i]', at_bats='$coop_at_bats[$i]', hits='$coop_hits[$i]', runs_scored='$coop_runs_scored[$i]', runs_batted='$coop_runs_batted[$i]', home_runs='$coop_home_runs[$i]', pitching_record='$coop_pitching_record[$i]', pitching_era='$coop_pitching_era[$i]' WHERE student_id='$coop_student[$i]'";
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
		header("Location:edit_ba.php?session=$session&school_ch=$school_ch&light=$lj&dark=$dj");
		exit();
		}
	} 
if($send=='y')  //auto send to view_ba.php and e-mail data to NSAA
{
   header("Location:view_ba.php?session=$session&school_ch=$school_ch&send=y");
   exit();
}
else if($save=="Save and Keep Editing")
{
   header("Location:edit_ba.php?session=$session&school_ch=$school_ch");
   exit();
}
else 
{
   header("Location:view_ba.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
