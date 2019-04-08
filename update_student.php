<?php
//update_student.php: updates individual student
//	entry in database

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//check if user cancelled action:
if($submit=="Cancel")
{
   header("Location:view_student.php?id=$id&session=$session&school_ch=$school_ch&activity_ch=$activity_ch&letter=$letter");
   exit();
}

//Check that info was submitted correctly:
$sem_error=false;
if($semesters==0)	//Only Music OR PP can be chosen
{
   for($i=0;$i<count($activity);$i++)
   {
      if($$activity[$i]=="x" && $activity[$i]!="im" && $activity[$i]!="vm" && $activity[$i]!='pp')
      {
	 $sem_error=true;
      }
   }
}
if(!ereg("([0-8])",$semesters) || strlen($semesters)!=1)  //invalid semesters
   $sem_error=true;
$name_error=false;
$last=trim($last);
$last=ereg_replace("\'","\'",$last);
$first=trim($first);
$first=ereg_replace("\'","\'",$first);
$middle=trim($middle);
$middle=ereg_replace("\.","",$middle);
$nickname=trim($nickname);
$nickname=ereg_replace("\'","\'",$nickname);
$dob="$dobm-$dobd-$doby";
//if(strlen($last)<=1 || strlen($first)<=1 || !ereg("^[[:alpha:] -\']+$",$last) || !ereg("^[[:alpha:] -\']+$",$first) || (!ereg("^[[:alpha:] ]+$",$middle) && $middle!="") || (!ereg("^[[:alpha:] -\']+$",$nickname) && $nickname!=""))
if(strlen($last)<=1 || strlen($first)<=1 )
{
//if first or last names not given or contain characters other than letters
   $name_error=true;
}
if($sem_error || $name_error)
{
//send back to edit_student.php with error message
   header("Location:edit_student.php?id=$id&session=$session&activity_ch=$activity_ch&school_ch=$school_ch&sem_error=$sem_error&name_error=$name_error&letter=$letter");
   exit();
}

//connect to database:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//get level of user
$sql="SELECT t2.level FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$level=$row[0];

//get eligible status before this update:
$sql="SELECT eligible, eligible_comment FROM eligibility WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$old_eligible=$row[0];
$old_eligible_comment=$row[1];

//if non-NSAA user tried to change a foreign exchange student back to eligible
//on their own, don't allow them to.
if($level>1 && $old_eligible!="y" && $eligible=="y" && ereg("Missing Paperwork",$old_eligible_comment) && $foreignx=="y")
{
   $eligible=$old_eligible;
   $eligible_comment=$old_eligible_comment;
}
//if NSAA changes for ex student to elig, get rid of Missing Paperwork note
else if($level==1 && $old_eligible!="y" && $eligible=="y" && ereg("Missing Paperwork",$old_eligible_comment) && $foreignx=="y")
{
   $eligible_comment="";
}

//check if student is too old to participate:
if(IsTooOld($dob))
{
   $eligible="n";
   $eligible_comment="Older than 19 years";
}
else if(ereg("Older than 19 years",$eligible_comment))
{
   $eligible="y";
   $eligible_comment="";
}

//append nickname in parentheses to first name:
if(trim($nickname)!="")
{
   $first.=" ($nickname)";
}

//get foreign exchange status before this update:
$sql="SELECT foreignx FROM eligibility WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$old_foreignx=$row[0];

//if the student is now being updated as a foreign exchange, make ineligible:
if($old_foreignx!="y" && $foreignx=="y")
{
   $eligible="n";
   $eligible_comment="International Transfer; Missing Paperwork";
   $forx_flag=1;
}
/*
//if EO is checked, check T as well:
if($enroll_option=='y')
{
   $transfer='y';
   if($transfer_comment=="")
   {
      $transfer_comment="Enrollment Option";
   }
}
*/
//hide transfer & new eo
$transfer='';
$enroll_option='';
//update db:
$transfer_comment=addslashes($transfer_comment);
$eligible_comment=addslashes($eligible_comment);
$foreignx_comment=addslashes($foreignx_comment);
$sql="UPDATE eligibility SET last='$last', first='$first', middle='$middle', gender='$gender', dob='$dob', semesters='$semesters', transfer='$transfer', transfer_comment='$transfer_comment', eligible='$eligible', eligible_comment='$eligible_comment', foreignx='$foreignx', foreignx_comment='$foreignx_comment', enroll_option='$enroll_option', fb68='$fb68', fb11='$fb11', vb='$vb', sb='$sb', cc='$cc', te='$te', bb='$bb', wr='$wr', sw='$sw', go='$go', tr='$tr', ba='$ba', so='$so', sp='$sp', pp='$pp', de='$de', im='$im', vm='$vm', jo='$jo' WHERE id='$id'";

$result=mysql_query($sql);

if($school_attending)	//NSAA user may have changed the school
{
   $school_attending2=ereg_replace("\'","\'",$school_attending);
   $sql="UPDATE eligibility SET school='$school_attending2' WHERE id='$id'";
   $result=mysql_query($sql);
   $act_list=array();
   $x=0;
   for($i=0;$i<=18;$i++)
   {
      if($$activity[$i]=='x')
      {
         $act_list[$x]=$activity[$i];
         if($act_list[$x]=="cc" || $act_list[$x]=="te" || $act_list[$x]=="bb" || $act_list[$x]=="go" || $act_list[$x]=="tr" || $act_list[$x]=="so")
	 {
	    if($gender=="M") $act_list[$x].="_b";
	    else $act_list[$x].="_g";
	 }
	 $x++;
      }
   }
   for($i=0;$i<count($act_list);$i++)
   {
      if(!ereg("te_",$act_list[$i]))
      {
	 $school_attending2=ereg_replace("\'","\'",$school_attending);
         $sql="UPDATE $act_list[$i] SET school='$school_attending2' WHERE student_id='$id'";
      }
      else 	//tennis form
      {
	 $school_attending2=ereg_replace("\'","\'",$school_attending);
	 $sql="UPDATE $act_list[$i] SET school='$school_attending2' WHERE student_id_1='$id' AND student_id_2 IS NULL";
      }
      $result=mysql_query($sql);
   }
}

//send back to view_student.php:
header("Location:view_student.php?id=$id&session=$session&activity_ch=$activity_ch&school_ch=$school_ch&forx_flag=$forx_flag&letter=$letter");
exit();
?>
