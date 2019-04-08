<?php
//submit_de.php: store debate entry form info into db

require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

if($submit=="Cancel")
{
   header("Location:../welcome.php?session=$session");
   exit();
}

if($school_ch && GetLevel($session)==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

//make sure data entered is valid:
if(($student_1!="Choose Student" && ($judge_1=="" || $j_address_1=="" || $j_phone_1=="" || $j_constraints_1=="")) || ($student_2!="Choose Student" && ($judge_2=="" || $j_address_2=="" || $j_phone_2=="" || $j_constraints_2=="")) || ($judge_1!="" && $judge_1==$judge_2))
{
   echo $init_html;
   echo GetHeader($session);
   echo "<center><br><br>";
   echo "<table width=50%><tr align=left><th>";
   echo "<font style=\"color:red\"><b>You have entered invalid information.</font><br><br>";
   echo "Remember, each entry must be accompanied by a judge's name, address, and constraints (type 'none' if there are no constraints),";
   echo " and the same judge cannot be used for both entries.";
   echo "<br><br><a href=\"javascript:history.go(-1)\">Go Back</a></th></tr>";
   echo "</table>";
   exit();
}

//update asst coaches
$asstcoaches=ereg_replace("\'","\'",$asstcoaches);
$asstcoaches=ereg_replace("\"","\'",$asstcoaches);
$sql="UPDATE logins SET asst_coaches='$asstcoaches' WHERE school='$school2' AND sport='Debate'";
$result=mysql_query($sql);

//check if this is an update or an insert
$sql="SELECT * FROM de WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

//prepare entries for submission to DB
$judge_1=ereg_replace("\'","\'",$judge_1);
$judge_1=ereg_replace("\"","\'",$judge_1);
$j_address_1=ereg_replace("\'","\'",$j_address_1);
$j_address_1=ereg_replace("\"","\'",$j_address_1);
$j_constraints_1=ereg_replace("\'","\'",$j_constraints_1);
$j_constraints_1=ereg_replace("\"","\'",$j_constraints_1);
$judge_2=ereg_replace("\'","\'",$judge_2);
$judge_2=ereg_replace("\"","\'",$judge_2);
$j_address_2=ereg_replace("\'","\'",$j_address_2);
$j_address_2=ereg_replace("\"","\'",$j_address_2);
$j_constraints_2=ereg_replace("\'","\'",$j_constraints_2);
$j_constraints_2=ereg_replace("\"","\'",$j_constraints_2);

if($student_1=="Choose Student") $student_1="";
if($student_2=="Choose Student") $student_2="";

if($student_1=="" && $student_2=="")
{
   $sql2="DELETE FROM de WHERE school='$school2'";
}
else if(mysql_num_rows($result)>0)	//UPDATE
{
   $sql2="UPDATE de SET student_id_1='$student_1', judge_1='$judge_1', j_address_1='$j_address_1', j_phone_1='$j_phone_1',j_constraints_1='$j_constraints_1', student_id_2='$student_2', judge_2='$judge_2', j_address_2='$j_address_2', j_phone_2='$j_phone_2',j_constraints_2='$j_constraints_2' WHERE school='$school2'";
}
else				//INSERT
{
   $sql2="INSERT INTO de (school, student_id_1, judge_1, j_address_1, j_phone_1, j_constraints_1, student_id_2, judge_2, j_address_2, j_phone_2, j_constraints_2) VALUES ('$school2','$student_1','$judge_1','$j_address_1','$j_phone_1','$j_constraints_1','$student_2','$judge_2','$j_address_2','$j_phone_2','$j_constraints_2')";
}
$result=mysql_query($sql2);

if($send=='y')
{
   header("Location:view_de.php?session=$session&school_ch=$school_ch&send=$send");
   exit();
}
else if($submit=="Save & Keep Editing")
{
   header("Location:edit_de.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save & View Form")
{
   header("Location:view_de.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
