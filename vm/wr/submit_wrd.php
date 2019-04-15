<?php
/***************************
submit_wrd.php
Catches submission of form
on edit_wrd.php
****************************/
require '../variables.php';
require '../functions.php';
require '../../calculate/functions.php';

if($save=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

//Get school that user chose (Level 1) or belongs to (Level 2,3)
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
$sql="UPDATE logins SET asst_coaches='".addslashes($asst)."' WHERE level=3 AND school='$school2' AND sport='Wrestling'";
$result=mysql_query($sql);

$sid=GetSID2($school,'wr');
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

   //ENROLLMENT & CONFERENCE
   $sql="UPDATE headers SET conference='".addslashes($conference)."',enrollment='$enrollment' WHERE id='$schoolid'";
   $result=mysql_query($sql);
   if(mysql_error())
   {
      echo "$sql<br>".mysql_error()."<br>"; exit();
   }

   //HISTORICAL INFO
   $sql="UPDATE wrschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
   $result=mysql_query($sql);
   if(mysql_error())
   {
      echo "$sql<br>".mysql_error()."<br>"; exit();
   }
}//END IF LEVEL=1


//SAVE ROSTER
for($i=0;$i<count($id);$i++)
{
     //check if student has already been submitted:
     $sql="SELECT * FROM wrd WHERE student_id='$id[$i]'";
     $result=mysql_query($sql);
     $update=0;
     if(mysql_num_rows($result)>0) $update=1;

     $record[$i]="$win[$i]-$loss[$i]";
     
     //if student has been submitted before, use UPDATE:
     if($update==1)
     {
	$sql="UPDATE wrd SET checked='$check[$i]', weight='$weight[$i]', record='$record[$i]',school='$school2' WHERE student_id='$id[$i]'";
     }
     else //if student has not been submitted yet, use INSERT:
     {
        $sql="INSERT INTO wrd (student_id, school, checked, weight, record) VALUES ('$id[$i]', '$school2','$check[$i]','$weight[$i]','$record[$i]')";
     }
     $result=mysql_query($sql);
   if(mysql_error())
   {
      echo "$sql<br>".mysql_error()."<br>"; exit();
   }
}

//SAVE SCHEDULE
for($i=0;$i<count($scoreid);$i++)
{
   $received[$i]="$yr[$i]-$mo[$i]-$day[$i]";
   if($scoreid[$i]>0)
   {
      if($delete[$i]=='x')	//DELETE
      {
	 $sql="DELETE FROM wrdsched WHERE scoreid='$scoreid[$i]'";
	 $result=mysql_query($sql);
      }
      else	//UPDATE
      {
	 $sql="UPDATE wrdsched SET sid='$sid',oppid='$oppid[$i]',sidscore='$sidscore[$i]',oppscore='$oppscore[$i]',received='$received[$i]' WHERE scoreid='$scoreid[$i]'";
         $result=mysql_query($sql);
      }
   }
   else	if($oppid[$i]>0) //INSERT
   {
      $sql="INSERT INTO wrdsched (sid,oppid,sidscore,oppscore,received) VALUES ('$sid','$oppid[$i]','$sidscore[$i]','$oppscore[$i]','$received[$i]')";
      $result=mysql_query($sql);
   }
   if(mysql_error())
   {
      echo "$sql<br>".mysql_error()."<br>"; exit();
   }
}

if($send=='y')       //FINAL SUBMISSION
{
   $today=time();
   $sql="UPDATE wrd SET submitted='$today' WHERE school='$school2'";
   $result=mysql_query($sql);
}

if($save=="Save and Keep Editing" || !$save)
{
   header("Location:edit_wrd.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($save=="Save and View Form")
{
   header("Location:view_wrd.php?session=$session&school_ch=$school_ch");
   exit();
}
?>

