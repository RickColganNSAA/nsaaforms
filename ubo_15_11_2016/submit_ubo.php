<?php
error_reporting(0);
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//submit_ubo.php: submits form info for unified bowling.
//  Form is submitted from edit_ubo.php

require '../functions.php';

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

//update asst coaches info
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
//$sql="UPDATE logins SET asst_coaches='$asst' WHERE level=3 AND school='$school2' AND sport='Girls Golf'";
 $sql="UPDATE logins SET asst_coaches='$asst' WHERE level=3 AND school='$school2' AND sport='Unified Bowling'";  
$result=mysql_query($sql);

if($class_dist=="Choose")
   $class_dist="";
for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT student_id FROM ubo WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result)) $update=1;
     else $update=0;
     
     //if student has been submitted before, use UPDATE or DELETE:
     if($update==1)
     {
	$sql="UPDATE ubo SET checked='$check[$i]', avg_round='$avg_round[$i]', class_dist='$class_dist' WHERE student_id='$id[$i]'";
     }

     //if student has not been submitted yet, use INSERT:
     if($update==0)
     {
        $sql="INSERT INTO ubo (student_id, school, checked, avg_round, class_dist";
   	    if($school!=$studsch[$i]) $sql.=",co_op";
        $sql.=") VALUES ('$id[$i]', '".addslashes($studsch[$i])."', '$check[$i]', '$avg_round[$i]', '$class_dist'";
        if($school!=$studsch[$i]) $sql.=",'$school2'";
        $sql.=")";
     }

     $result=mysql_query($sql);

	if(mysql_error())
	{
	echo $sql."<br>".mysql_error();
	exit();
	}
}

if($submit=="Save and View Form")
{
   header("Location:view_ubo.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save and Keep Editing")
{
   header("Location:edit_ubo.php?session=$session&school_ch=$school_ch");
   exit();
}
elseif($submit=="Cancel")
{
   header("Location:../welcome.php?session=$session");
   exit();
}
?>
