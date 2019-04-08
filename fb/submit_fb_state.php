<?php
//submit_fb_state.php: update db table fb_state
// If checkbox called 'send' was checked, e-mail
// submitted data to appropriate recipients for
// use in state tournament programs

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

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
   header("Location:view_fb.php?session=$session&school_ch=$school_ch");
   exit();
}

$level=GetLevel($session);
if($level==1 && $school_ch)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

//store selected class in db
   //check if a class has already been entered for this school:
   $sql="SELECT id FROM headers WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sch_id=$row[0];
   $sql="SELECT * FROM fb_classes WHERE school_id='$sch_id'";
   $result=mysql_query($sql);
   if($class=="Choose") $class="";
   if($send=='y') $datesub=time();
   else $datesub="";
   if(mysql_num_rows($result)>0)	//UPDATE
   {
      $sql2="UPDATE fb_classes SET class='$class'";
      if($send=='y') $sql2.=", datesub='$datesub'";
      $sql2.=" WHERE school_id='$sch_id'";
   }
   else		//INSERT
   {
      $sql2="INSERT INTO fb_classes (school_id, class, datesub) VALUES ('$sch_id','$class','$datesub')";
   }
   $result=mysql_query($sql2);

//store asst coaches, trainers, managers
$asst_coaches=ereg_replace("\'","\'",$asst_coaches);
$asst_coaches=ereg_replace("\"","\'",$asst_coaches);
$ath_trainers=ereg_replace("\'","\'",$ath_trainers);
$ath_trainers=ereg_replace("\"","\'",$ath_trainers);
$managers=ereg_replace("\'","\'",$managers);
$managers=ereg_replace("\"","\'",$managers);
  //check if already submitted for this school
  $sql="SELECT * FROM fb_staff WHERE school_id='$sch_id'";
  $result=mysql_query($sql);
  if(mysql_num_rows($result)>0)		//UPDATE
  {
     $sql2="UPDATE fb_staff SET asst_coaches='$asst_coaches', ath_trainers='$ath_trainers', managers='$managers' WHERE school_id='$sch_id'";
  }
  else					//INSERT
  {
     $sql2="INSERT INTO fb_staff (school_id, asst_coaches, ath_trainers, managers) VALUES ('$sch_id','$asst_coaches','$ath_trainers','$managers')";
  }
$result=mysql_query($sql2);

//store player info
$end=$start+25;
$starters=0;
for($i=$start;$i<$end;$i++)
{
      $pronunciation[$i]=ereg_replace("\'","\'",$pronunciation[$i]);
      $pronunciation[$i]=ereg_replace("\"","\'",$pronunciation[$i]);
      $nickname[$i]=addslashes($nickname[$i]);
      $height_ft[$i]=ereg_replace("\'","",$height_ft[$i]);
      $height_ft[$i]=ereg_replace("\"","",$height_ft[$i]);
      $height_in[$i]=ereg_replace("\'","",$height_in[$i]);
      $height_in[$i]=ereg_replace("\"","",$height_in[$i]);
      $height[$i]="$height_ft[$i]-$height_in[$i]";

      //check to see if this row has already been submitted
      $sql="SELECT * FROM fb_state WHERE id='$id[$i]'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))	//UPDATE or DELETE
      {
	 if($starter[$i]=="y") $starters++;
	 if($player[$i]=="Choose Player")
	 {
	    $sql2="DELETE FROM fb_state WHERE id='$id[$i]'";
	 }
	 else
	 {
	    $sql2="UPDATE fb_state SET student_id='$player[$i]', co_op='$school2', nickname='$nickname[$i]', pronunciation='$pronunciation[$i]', jersey_lt='$jersey_lt[$i]', jersey_dk='$jersey_dk[$i]', starter='$starter[$i]', medalist='$medalist[$i]', off_posn='$off_posn[$i]', def_posn='$def_posn[$i]', height='$height[$i]', weight='$weight[$i]' WHERE id='$id[$i]'";
	 }
	 $result2=mysql_query($sql2);
      }
      if(mysql_num_rows($result)==0 && $player[$i]!="Choose Player")
      {
         $sql3="INSERT INTO fb_state (student_id, co_op, nickname, pronunciation, jersey_lt, jersey_dk, starter, medalist, off_posn, def_posn, height, weight) VALUES ('$player[$i]','$school2','$nickname[$i]','$pronunciation[$i]','$jersey_lt[$i]','$jersey_dk[$i]','$starter[$i]','$medalist[$i]','$off_posn[$i]','$def_posn[$i]','$height[$i]','$weight[$i]')";
	 $result3=mysql_query($sql3);
      }
}

//store playoff game info
   //first delete old info
   $sql="DELETE FROM fb_playoff WHERE school_id='$sch_id'";
   $result=mysql_query($sql);
for($i=0;$i<count($opp);$i++)
{
   if($opp[$i]!="Choose Opponent")
   {
      $sql="INSERT INTO fb_playoff (school_id, opp_id, score, opp_score) VALUES ('$sch_id', '$opp[$i]','$score[$i]','$opp_score[$i]')";
      $result=mysql_query($sql);
   }
}

//WRITE EXPORTS
WriteFBExports($school);

//if checkbox at bottom was checked, this is a final submission
if($send=='y') //ERROR CHECK
{
   if($starters>22)
      header("Location:edit_fb_state.php?session=$session&school_ch=$school_ch&starters=$starters");
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM fb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($row[4]=="" || $row[4]==NULL || (($row[8]=="" || $row[8]==NULL)&&($row[9]=="" || $row[9]==NULL)) || $row[10]=="-" || $row[10]=="" || $row[10]==NULL || $row[11]=="" || $row[11]==NULL)
	 header("Location:edit_fb_state.php?session=$session&school_ch=$school_ch&err=1");
   }
}

if($submit=="Save & Keep Editing")
{
   header("Location:edit_fb_state.php?session=$session&school_ch=$school_ch&start=$start&send=$send");
   exit();
}
else if($submit=="Save & View Form")
{
   header("Location:view_fb_state.php?session=$session&school_ch=$school_ch&send=$send");
   exit();
}
?>
