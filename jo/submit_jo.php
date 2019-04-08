<?php
//submit_jo.php: submits form info for journalism 
//  Form is submitted from edit_jo.php

//check if user cancelled request
if($save=="Cancel")
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
   //MAKE SURE THIS IS A VALID SCHOOL
   $sql="SELECT school FROM headers WHERE school='".addslashes($school)."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $school=$row['school'];
}
$school2=addslashes($school);

//update asst coaches
$asst=addslashes($asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE level='3' AND school='$school2' AND (sport='Newspaper' OR sport='Yearbook')";
$result=mysql_query($sql);

//Update Cell
$sql="UPDATE joschool SET directorcell='$directorcell' WHERE sid='".GetSID2($school,'jo')."'";
$result=mysql_query($sql);

if($class_dist=="Choose")
   $class_dist="";
for($i=0;$i<count($student);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT * FROM jo WHERE student_id='$student[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;

     if($event1[$i]=="~") $event1[$i]="";
     if($event2[$i]=="~") $event2[$i]="";
     if($event1[$i]!="" || $event2[$i]!="")
	$check[$i]='y';
     else
	$check[$i]='';

     if(preg_match("/News\/Feature Photography/",$event1[$i]))
     {
	$event1[$i].=",$phototype1[$i],$storage1[$i]";
     }
     else if(preg_match("/News\/Feature Photography/",$event2[$i]))
     {
	$event2[$i].=",$phototype2[$i],$storage2[$i]";
     }

     //if student has been submitted before, use UPDATE:
     if($update==1)
     {
	$sql="UPDATE jo SET checked='$check[$i]',school='$school2', class_dist='$class_dist', event1='$event1[$i]', event2='$event2[$i]' WHERE student_id='$student[$i]'";
     }

     //if student has not been submitted yet, use INSERT:
     else if($update==0)
     {
        $sql="INSERT INTO jo (student_id, checked, class_dist, event1, event2,school) VALUES ('$student[$i]', '$check[$i]','$class_dist','$event1[$i]','$event2[$i]','$school2')";
     }

     $result=mysql_query($sql);
}

if($send=="y")
{
   $sql="UPDATE jo SET datesub='".time()."' WHERE school='$school2'";
   $result=mysql_query($sql);
}
if($save=="Save & View Form" || $send=='y')
{
   header("Location:view_jo.php?session=$session&school_ch=$school_ch&send=$send");
   exit();
}
else 
{
   header("Location:edit_jo.php?session=$session&school_ch=$school_ch#entry");
   exit();
}
?>

