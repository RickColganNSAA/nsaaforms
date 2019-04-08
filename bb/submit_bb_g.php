<?php
//submit_bb_g.php: submits form info for girls basketball.
//  Form is submitted from edit_bb_g.php

//check if user cancelled request
if($submit=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
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
   $table="bb_gstate";
}
else
{
   $table="bb_g";
}

//update asst coaches
$asst_coaches=ereg_replace("\"","\'",$asst_coaches);
$asst_coaches=ereg_replace("\'","\'",$asst_coaches);
$sql="UPDATE logins SET asst_coaches='$asst_coaches' WHERE school='$school2' AND sport='Girls Basketball'";
$result=mysql_query($sql);

$sid=GetSID2($school,'bbg');
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
$sql="UPDATE bbgschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
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
	$sql="UPDATE bbgschool SET filename='$image' WHERE sid='$sid'";
    $result=mysql_query($sql);	 	
} 
if($class_dist=="Choose")
{
   $class_dist="";
}
$record="$wins-$losses";
for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT student_id FROM $table WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;

     //get height in right format:
     $height="$height_ft[$i]-$height_in[$i]";

     //get positions in right format:
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
	$sql="UPDATE $table SET school='$school2',checked='$check[$i]', jersey_lt='$jersey_lt[$i]', jersey_dk='$jersey_dk[$i]', height='$height', total_pts='$total_pts[$i]', pt_avg='$pt_avg[$i]', total_rb='$total_rb[$i]', reb_avg='$reb_avg[$i]', total_assists='$total_assists[$i]',total_steals='$total_steals[$i]',total_blocks='$total_blocks[$i]', position='$positions', record='$record', off_avg='$off_avg', def_avg='$def_avg', class_dist='$class_dist' WHERE student_id='$id[$i]'";
     }
     else  //INSERT
     {
        $sql="INSERT INTO $table (student_id, school, checked, jersey_lt, jersey_dk, height, total_pts, pt_avg, total_rb, reb_avg, total_assists,total_steals,total_blocks,position, record, off_avg, def_avg, class_dist) VALUES ('$id[$i]', '$school2', '$check[$i]', '$jersey_lt[$i]', '$jersey_dk[$i]', '$height', '$total_pts[$i]', '$pt_avg[$i]', '$total_rb[$i]', '$reb_avg[$i]', '$total_assists[$i]', '$total_steals[$i]','$total_blocks[$i]','$positions','$record', '$off_avg', '$def_avg', '$class_dist')";
     }

     $result=mysql_query($sql);
}

//store Co-op students' info:
for($i=0;$i<count($coop_student);$i++)
{
   //get height in right format
   $height="$coop_height_ft[$i]-$coop_height_in[$i]";

     //get positions in right format:
     $positions="";
     for($j=0;$j<count($coop_position_array[$i]);$j++)
     {
        $next=$coop_position_array[$i][$j];
        $positions.="$next/";
     }     
     $positions=substr($positions,0,strlen($positions)-1);

   $sql="UPDATE $table SET checked='$coop_check[$i]', class_dist='$class_dist', record='$record', off_avg='$off_avg', def_avg='$def_avg', jersey_lt='$coop_jersey_lt[$i]', jersey_dk='$coop_jersey_dk[$i]', height='$height', total_pts='$coop_total_pts[$i]', pt_avg='$coop_pt_avg[$i]', total_rb='$coop_total_rb[$i]', reb_avg='$coop_reb_avg[$i]', total_assists='$coop_total_assists[$i]',total_steals='$coop_total_steals[$i]',total_blocks='$coop_total_blocks[$i]',position='$positions',co_op='$school2' WHERE student_id='$coop_student[$i]'";
   $result=mysql_query($sql);
   if(mysql_error())
   {
	echo $sql."<br>".mysql_error();
       	exit();
   }
}

if($send=='y')  //auto send to view_bb_g.php and e-mail data to NSAA
{
   header("Location:view_bb_g.php?session=$session&school_ch=$school_ch&send=y");
   exit();
}
else if($submit=="Save and Keep Editing")
{
   header("Location:edit_bb_g.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save and View Form")
{
   header("Location:view_bb_g.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
