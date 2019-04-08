<?php
//submit_tr_b.php: submits form info for boys track.
//  Form is submitted from edit_tr_b.php

//check if user cancelled request
if($submit=="Cancel")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}

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
/*
if($state==1)
{
   $table="sb_state";
}
else
{
*/
   $table="tr_b";
//}

if($class_dist=="Choose")
{
   $class_dist="";
}

//update asst coaches
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE school='$school2' AND sport='Boys Track & Field'";
$result=mysql_query($sql);

$idlist="";
$overlimit=array(); $o=0;
for($j=0;$j<count($trevents);$j++)
{
   $long=$treventslong[$j];
   $short=$trevents[$j];
   //echo "$long: ".$student[$j][3]."<br>";
   for($i=0;$i<count($student[$j]);$i++)
   {
      if($student[$j][$i]!="Choose Student")
      {
	 $id=$student[$j][$i];
	 $idlist.="$id,";
         //check if student has already been submitted:
         $sql="SELECT * FROM $table WHERE student_id='$id' AND (event_1='$long' OR event_2='$long' OR event_3='$long' OR event_4='$long')";
         $result=mysql_query($sql);
         $update=0;
         if(mysql_num_rows($result)>0) $update=1;
	 $row=mysql_fetch_array($result);

	 $index=5;
	 for($k=0;$k<4;$k++)
	 {
	    if(!$roster[$id][$k][0] || $roster[$id][$k][0]=="") 
	    {
	       $index=$k;
	       $k=4;
	    }
	 }
	 if($index==5) //all 4 events used up for this student
	 {
	    $overlimit[$o]=$student[$j][$i]; $o++;
	 }
	 else
	 {
	    $best=$perf[$j][$i];
	    $best=ereg_replace("\"","",$best);
	    $best=ereg_replace("\'","-",$best);
	    $roster[$id][$index][0]=$long;
	    $roster[$id][$index][1]=$best;
	 }
      }
   }
}

//delete old entries from database
$sql="SELECT t1.student_id FROM $table AS t1 WHERE t1.school='$school2'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="DELETE FROM $table WHERE student_id='$row[0]'";
   $result2=mysql_query($sql2);
}
$sql="SELECT t1.student_id FROM $table AS t1, tr_b_coop t2 WHERE t1.student_id=t2.student_id AND t2.co_op='$school2'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="DELETE FROM $table WHERE student_id='$row[0]'";
   $result2=mysql_query($sql2);
}

//put $roster values in database
$idlist=substr($idlist,0,strlen($idlist)-1);
$idlist=Unique($idlist);  //remove duplicate id's from string
$idlist=split(",",$idlist);  //convert string to array of id's
for($i=0;$i<count($idlist);$i++)	//for each student
{
   $id=$idlist[$i];
   $event[0]=$roster[$id][0][0];
   $mark[0]=$roster[$id][0][1];
   $event[1]=$roster[$id][1][0];
   $mark[1]=$roster[$id][1][1];
   $event[2]=$roster[$id][2][0];
   $mark[2]=$roster[$id][2][1];
   $event[3]=$roster[$id][3][0];
   $mark[3]=$roster[$id][3][1];

   //get student's school
   $sql="SELECT school FROM eligibility WHERE id='$idlist[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $studsch=ereg_replace("\'","\'",$row[0]);

   $sql="SELECT * FROM $table WHERE student_id='$idlist[$i]'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)	//update
   {
      $sql2="UPDATE $table SET school='$studsch',class_dist='$class_dist',event_1='$event[0]',performance_1='$mark[0]',event_2='$event[1]',performance_2='$mark[1]',event_3='$event[2]',performance_3='$mark[2]',event_4='$event[3]',performance_4='$mark[3]',co_op='$school2' WHERE student_id='$idlist[$i]'";
   }
   else		//insert
   {
      $sql2="INSERT INTO $table (school,student_id,class_dist,event_1,performance_1,event_2,performance_2,event_3,performance_3,event_4,performance_4,co_op) VALUES ('$studsch','$idlist[$i]','$class_dist','$event[0]','$mark[0]','$event[1]','$mark[1]','$event[2]','$mark[2]','$event[3]','$mark[3]','$school2')";
   }
   $result2=mysql_query($sql2);
}

$alert="";
for($i=0;$i<count($overlimit);$i++)
{
   $sql="SELECT first,last FROM eligibility WHERE id='$overlimit[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $alert.="$row[first] $row[last],";
}
$alert=substr($alert,0,strlen($alert)-1);
if(count($overlimit)==0) $alert="";
else if(count($overlimit)==1)
   $alert="$alert is entered in too many events!  You can only enter students in 4 events including relays.";
else
   $alert="The following students are entered in too many events: $alert!  You can only enter students in 4 events including relays.";
/*
if($send=='y')  //auto send to view_sb.php and e-mail data to NSAA
{
   header("Location:view_tr_b.php?session=$session&school_ch=$school_ch&send=y&alert=$alert");
   exit();
}
*/
if($submit=="Save and Keep Editing")
{
   header("Location:edit_tr_b.php?session=$session&school_ch=$school_ch&alert=$alert");
   exit();
}
else if($submit=="Save and View Form")
{
   header("Location:view_tr_b.php?session=$session&school_ch=$school_ch&alert=$alert");
   exit();
}

function GetEvent($event,$possibles)
{
   if($event==$possibles[3]) $num=1;
   else if($event==$possibles[5]) $num=2;
   else if($event==$possibles[7]) $num=3;
   else if($event==$possibles[9]) $num=4;
   return $num;
}
?>
