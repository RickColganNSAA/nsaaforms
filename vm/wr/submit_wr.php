<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//submit_wr.php: submits form info for wrestling 
//  Form is submitted from edit_wr.php

//check if user cancelled request
if($cancel=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}

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

//update asst coaches
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE level=3 AND school='$school2' AND sport='Wrestling'";
$result=mysql_query($sql);

if($class_dist=="Choose")
   $class_dist="";
for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT * FROM wr WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update='0';
     if(mysql_num_rows($result)>0) $update=1;

     $record[$i]="$win[$i]-$loss[$i]";
     
     //if student has been submitted before, use UPDATE:
     if($update=='1')
     {
	$sql="UPDATE wr SET checked='$check[$i]', class_dist='$class_dist', weight='$weight[$i]', record='$record[$i]',school='$school2' WHERE student_id='$id[$i]'";
     }

     //if student has not been submitted yet, use INSERT:
     else if($update=='0')
     {
        $sql="INSERT INTO wr (student_id, school,checked, class_dist, weight, record) VALUES ('$id[$i]', '$school2','$check[$i]','$class_dist','$weight[$i]','$record[$i]')";
     }
     $result=mysql_query($sql);
}

//store Co-op students' info
for($i=0;$i<count($coop_student);$i++)
{
   $coop_record[$i]="$coop_win[$i]-$coop_loss[$i]";
   $sql="UPDATE wr SET checked='$coop_check[$i]', class_dist='$class_dist', weight='$coop_weight[$i]', record='$coop_record[$i]' WHERE student_id='$coop_student[$i]'";
   $result=mysql_query($sql);
}

if($save=="Save and Keep Editing" || !$save)
{
   header("Location:edit_wr.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($save=="Save and View Form")
{
   header("Location:view_wr.php?session=$session&school_ch=$school_ch");
   exit();
}
?>

