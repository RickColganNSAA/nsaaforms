<?php
//echo '<pre>'; print_r($_POST); exit;
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//submit_cc_b.php: submits form info for boys cross-country.
//  Form is submitted from edit_cc_b.php

//check if user cancelled request
if($submit=="Cancel")
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

//update asst coaches info
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE school='$school2' AND sport='Boys Cross-Country' AND level=3";
$result=mysql_query($sql);

if($class_dist=="Choose")
   $class_dist="";
for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT * FROM cc_b WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;
     
     //if student has been submitted before, use UPDATE:
     if($update==1)
     {
	 $sql="UPDATE cc_b SET checked='$check[$i]', class_dist='$class_dist' WHERE student_id='$id[$i]'";
	 if(empty($row['co_op']) && $school!=$studsch[$i]){
	 $sql1="UPDATE cc_b SET co_op='$school2' WHERE student_id='$id[$i]'";
	 $result=mysql_query($sql1);
	 }
     }

     //if student has not been submitted yet, use INSERT:
     else if($update==0)
     {
        $sql="INSERT INTO cc_b (student_id, school, checked, class_dist";
        if($school!=$studsch[$i]) $sql.=",co_op";
        $sql.=") VALUES ('$id[$i]', '".addslashes($studsch[$i])."', '$check[$i]', '$class_dist'";
        if($school!=$studsch[$i]) $sql.=",'$school2'";
        $sql.=")";
     }
     $result=mysql_query($sql);
}

//store Co-op students' info
for($i=0;$i<count($coop_student);$i++)
{
   $sql="UPDATE cc_b SET checked='$coop_check[$i]', class_dist='$class_dist' WHERE student_id='$coop_student[$i]'";
   $result=mysql_query($sql);
}

if($submit=="Save and Keep Editing")
{
   header("Location:edit_cc_b.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save and View Form")
{
   header("Location:view_cc_b.php?session=$session&school_ch=$school_ch");
   exit();
}
?>

