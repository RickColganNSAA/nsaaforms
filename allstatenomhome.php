<?php
/*********************************
allstatenomhome.php
School can manage NCPA Academic
All-State Nomination Forms
Author: Ann Gaffigan
Created: 5/18/10
*********************************/

require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=addslashes($school);

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))	//IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;
$springyear=$fallyear+1;
$schoolid=GetSchoolID2($school,$fallyear);

echo $init_html;
echo $header;

echo "<br><div style='text-align:left;width:650px;font-size:9pt;color:#ff0000;margin:5px;padding:10px;'>Welcome!  PLEASE NOTE:  The NSAA office NO LONGER ACCEPTS Academic All-State <b><u>PAPER TRANSCRIPTS</b></u>.  The \"electronic nomination forms\" allow you to upload each student's <u>transcript</u> as the second step of the nomination process. Complete instructions are included below and during the process to make this new system easier for you to administer!<br><br>THANKS FOR YOUR COOPERATION and note the deadline dates for each season.  The NSAA office must receive both the \"electronic nomination form\" and the uploaded transcript <u>BY THE DUE DATE</u> to meet publication deadlines for posting student award recipients.</div>";

echo "<br><table cellspacing=0 cellpadding=5 class=nine><caption><b>NCPA Academic All-State Nominations You've Submitted:</b>";
echo "<div class='normalwhite' style='width:600px;font-size:9pt;margin:5px;padding:10px;'><b>You may nominate <u>two students per school</u> or in a <u>cooperative, two students per school-unit</u> in each activity program area.<br><br><u>Cooperatives:</u><br>The lead school in the coop will complete the nomination forms from the eligibility lists of the coop schools. If a student is nominated from a non-lead school, the student's school will be able to login and electronically upload a transcript.</b></div>";
echo "</caption>";

$ix=0;
for($i=0;$i<count($allstatesp);$i++)
{
   if(preg_match("/Season/",$allstatesp[$i]))
   {
      $field=preg_replace("/ Season/","",$allstatesp[$i]);
      $field=strtolower("allstatenom_".$field);
      $sql="SELECT * FROM misc_duedates WHERE sport='$field'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $curduedate=$row[duedate];
      $date=split("-",$curduedate);
      $curseason=preg_replace("/allstatenom_/","",$row[sport]);

      if($allstatesp[$i]=="Fall Season") $curshowdate=$fallyear."-08-15";
      else if($allstatesp[$i]=="Winter Season") $curshowdate=$fallyear."-11-01";
      else $curshowdate=$springyear."-03-01";

      echo "<tr align=left><td><br><font style='font-size:10pt;'><b><u>".strtoupper($allstatesp[$i])." NOMINATION FORMS (Due $date[1]/$date[2]/$date[0]):</u></b></font></td></tr>";
      $ix=0;
   }
   else if(IsHeadSchool($schoolid,$allstatesp2[$i],$fallyear) || IsInCoop($schoolid,$allstatesp2[$i],$fallyear) || $school=="Test's School")
   {
      echo "<tr align=center";
      if($ix%2==0) echo " bgcolor=\"#f0f0f0\"";
      echo "><td><b><u>".strtoupper($allstatesp[$i])."</u></b></td></tr>";
      echo "<tr align=center";
      if($ix%2==0) echo " bgcolor=\"#f0f0f0\"";
      echo "><td>";
      if(!IsHeadSchool($schoolid,$allstatesp2[$i],$fallyear) && $school!="Test's School") //this IS a coop, but this is NOT the lead school
      {
         $coophead=GetCoopHeadSchool($schoolid,$allstatesp2[$i],$fallyear);
         $mainschid=GetSchoolID2($coophead,$fallyear);
	 echo "<div class='alert' style='width:600px;'><p><b>You are in a cooperative with $coophead for $allstatesp[$i].</b></p><p>$coophead will take care of submitting the nomination forms for the nominees on this team. If either of the nominees is from your school, you will see their submitted nomination form below and you can then <b>upload the student's transcript</b>.</p><p><b><u>The NSAA must receive each nominee's <label style=\"background-color:green;\">electronic</label> transcript in order for the nomination process to be complete.</b></u></p></div>";
      }
      else if(IsInCoop($schoolid,$allstatesp2[$i],$fallyear))	//this IS a coop, and this IS the lead school
      {
         $mainschid=$schoolid;
         echo "<div class='alert' style='width:600px;'><p><b>You are the lead school of this cooperative program.</b></p><p>If a nominee attends a school co-oping with your school for this activity, that school's AD will be able to login and upload the nominee's transcript once you have submitted the nomination form.</p><p><b><u>The NSAA must receive each nominee's <label style=\"background-color:green;\">electronic</label> transcript in order for the nomination process to be complete.</b></u></p></div>";
      }
      else $mainschid=$schoolid;
      $sql="SELECT DISTINCT t1.*,t2.datesub,t2.transcript,t2.opened,t2.confirmed,t2.released,t2.id AS nomid FROM eligibility AS t1,allstatenom AS t2 WHERE t1.id=t2.studentid AND ";
      if(!IsHeadSchool($schoolid,$allstatesp2[$i],$fallyear)) $sql.="t1.school='$school2' AND "; //Non-lead school can only see their nominees
      else $sql.="t2.schoolid='$mainschid' AND ";
      //$sql.="t2.schoolid='$mainschid' AND t2.sport='".$allstatesp2[$i]."' ORDER BY t1.last,t1.first";
      $sql.="t2.sport='".$allstatesp2[$i]."' ORDER BY t1.last,t1.first";
      $result=mysql_query($sql);
//echo $sql;
      if(mysql_num_rows($result)>0)
      {
         $num=1;
         echo "<table cellspacing=0 cellpadding=5 style='width:750px;'>";
         echo "<tr align=center valign=top>";
         while($row=mysql_fetch_array($result))
         {
         echo "<td><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"width:365px;border:#808080 1px solid;background-color:#ffffff;\">";
         echo "<tr align=center bgcolor='#fafda2'><td colspan=2><b>NOMINEE #$num</b></td></tr>";
         echo "<tr align=center><td align=right width='120px'><b>Student's Name:</b></td><td>$row[first] $row[last]</td></tr><tr align=center><td align=right><b>Transcript:</b></td><td";
         if($row[transcript]=='')
            echo " bgcolor=\"#ff0000\">Not Received.<br><a class=white href=\"allstatenom.php?session=$session&nomid=$row[nomid]&step=2\">UPLOAD TRANSCRIPT ONLINE</a>";
         else if($row[transcript]=="RECEIVED")
            echo ">Received by NSAA Office";
         else 
	    echo "><a href=\"attachments.php?session=$session&filename=$row[transcript]\" class=small>Uploaded to NSAA Office (Click to View)</a>";
         echo "</td></tr>";
         echo "<tr align=center><td align=right><b>Nomination Form:</b></td><td><a class=small href=\"allstatenom.php?session=$session&nomid=$row[nomid]\"";
         if($row[datesub]==0)
       	    echo ">Click to EDIT";
	 else if($row[opened]>0)
	    echo ">Returned by NSAA Office (Click to Edit)";
         else
 	    echo " target=\"_blank\">Received by NSAA Office (Click to View)"; // on ".date("m/d/y",$row[datesub])." at ".date("g:ia",$row[datesub]);
	 echo "</a></td></tr>";
         echo "<tr align=center><td align=right><b>Status:</b></td><td";
	 if($row[opened]>0)	//OPENED FOR EDITING
	    echo " align=left bgcolor=\"#fafda2\">This nomination has been <b>OPENED</b> by the NSAA for you to <b>EDIT</b>. Please make the necessary changes to your form and re-submit it to the NSAA.<br><a href=\"allstatenom.php?session=$session&nomid=$row[nomid]&edit=1\">Edit Nomination Form</a>";
	 else if($row[transcript]=='') echo ">Awaiting student's qualified transcript...uploaded transcript must be received by the NSAA office by the due date.";
	 else if($row[transcript]!='' && $row[datesub]>0 && $row[released]==0)
	    echo "><b>RECEIPT OF COMPLETE<br>NOMINATION & TRANSCRIPT<br>CONFIRMED</b><br>(Please check back for NSAA to release<br>the Award Certificate for this nominee)";
	 else	//RELEASED
            echo " bgcolor=\"#00ff00\"><b>AWARD APPROVED!<br>CERTIFICATE AND LETTER MAY NOW BE DOWNLOADED AND PRINTED</b><br>Download: <a href=\"allstatenomcert.php?session=$session&nomid=$row[nomid]\">CERTIFICATE</a> | <a href=\"allstatenomletter.php?session=$session&nomid=$row[nomid]\">LETTER</a><br><input type=button name='printingtips' value='Printing Tips' onClick=\"window.open('printingtips.php','Printing_Tips','width=500,height=350');\">";
	 echo "</td></tr>";
         echo "</table></td>";
         $num++;
      }
      echo "</td></tr></table>";
      $sql=preg_replace("/DISTINCT /","",$sql);
      $result=mysql_query($sql);
      if(mysql_num_rows($result)<2 && ($school=="Test's School" || IsHeadSchool($schoolid,$allstatesp2[$i],$fallyear)))
      {
	 if((!PastDue($curduedate,0) && ($school=="Test's School" || PastDue($curshowdate,-1))) || ASAIsUnlocked($schoolid,$curseason,$fallyear))
	{
            echo "<br><a href=\"allstatenom.php?session=$session&activitych=".$allstatesp2[$i]."\" class=small>Submit another $allstatesp[$i] Nomination</a>";
	}
         else if(PastDue($curduedate,0))   //past due
         {
            $date=split("-",$curduedate);
            echo "<br>$allstatesp[$i] nominations were due on $date[1]/$date[2]/$date[0].";
         }
         else      //not showing yet
         {
            $date=split("-",$curshowdate);
            echo "<br>$allstatesp[$i] nomination forms will be available on $date[1]/$date[2]/$date[0].";
         }
      }
   }	//END IF HEAD SCHOOL OR COOP
   else if(IsHeadSchool($schoolid,$allstatesp2[$i],$fallyear) || $school=="Test's School")
   {
      if(($school=="Test's School" || (!PastDue($curduedate,0) && PastDue($curshowdate,-1))) || ASAIsUnlocked($schoolid,$curseason,$fallyear))
      {
         echo "[No $allstatesp[$i] nominations have been submitted by your school.]<br><a href=\"allstatenom.php?session=$session&activitych=".$allstatesp2[$i]."\" class=small>Submit a $allstatesp[$i] Nomination</a>.";
      }
      else if(PastDue($curduedate,0))	//past due
      {
	 $date=split("-",$curduedate);
         echo "[No $allstatesp[$i] nominations have been submitted by your school.]<br><br>$allstatesp[$i] nominations were due on $date[1]/$date[2]/$date[0].";
      }	
      else	//not showing yet
      {
         $date=split("-",$curshowdate);
         echo "$allstatesp[$i] nomination forms will be available on $date[1]/$date[2]/$date[0].";
      }
   }	//END IF HEAD SCHOOL
   else
      echo "[No $allstatesp[$i] nominations have been submitted by ".GetCoopHeadSchool($schoolid,$allstatesp2[$i],$fallyear).".]";
   echo "<br><br></td></tr>";
   $ix++;
   }
   else if(!IsHeadSchool($schoolid,$allstatesp2[$i],$fallyear))
   {
   //   echo "$schoolid $allstatesp2[$i] $fallyear<br>";
   }
}

echo "</table>";

echo $end_html;
?>
