<?php
/*******************************************************
interstatesanction.php

Application for Sanction of Interstate Athletic Events

...to be completed by NSAA School AD's who will be
hosting such an event.
...form is submitted to the database, NSAA approves it
and sends to invited schools and the NFHS (if necessary)
...then host school is notified that it was approved
and this page is updated: 
https://nsaahome.org/textfile/about/sanction.pdf
(this link will be turned into php page that pulls
approved application info from database)

Created: 11/23/09
Author: Ann Gaffigan
*********************************************************/

require '../functions.php';
require '../variables.php';
require 'sanctionvariables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1 - NSAA) or belongs to (Level 2 - AD, 3 - Coach, 4 - College)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

if($level==1 && $open==1 && $appid)
{
   $sql="UPDATE interstatesanctions SET opened='".time()."' WHERE id='$appid'";
   $result=mysql_query($sql);
}
else if($level==1 && $close==1 && $appid)
{
   $sql="UPDATE interstatesanctions SET opened=0 WHERE id='$appid'";
   $result=mysql_query($sql);
}

if($copy && $copyfromid)	//COPY PREVIOUS YEAR APP TO NEW APP
{
   $sql="SELECT * FROM interstatesanctions WHERE id='$copyfromid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
   $syear=$start[0]+1; $eyear=$end[0]+1;
   $startdate="$syear-$start[1]-$start[2]"; $enddate="$eyear-$end[1]-$end[2]";
   $eventname=addslashes($row[eventname]);
   $sponsor=addslashes($row[sponsor]); $sponsoraddress=addslashes($row[sponsoraddress]); $sponsorcity=addslashes($row[sponsorcity]);
   $studentawards=addslashes($row[studentawards]);
   $teamawards=addslashes($row[teamawards]);
   $coachawards=addslashes($row[coachawards]);

   $sql2="INSERT INTO interstatesanctions (school,sport,startdate,enddate,eventtime,eventname,sponsor,sponsoraddress,sponsorcity,sponsorstate,sponsorzip,juniorvarsity,entryfee,admissionfee,studentawards,studentawardsvalue,teamawards,teamawardsvalue,coachawards,coachawardsvalue) VALUES ('$school2','$row[sport]','$startdate','$enddate','$row[eventtime]','$eventname','$sponsor','$sponsoraddress','$sponsorcity','$row[sponsorstate]','$row[sponsorzip]','$row[juniorvarsity]','$row[entryfee]','$row[admissionfee]','$studentawards','$row[studentawardsvalue]','$teamawards','$row[teamawardsvalue]','$coachawards','$row[coachawardsvalue]')";
   $result2=mysql_query($sql2);
   $appid=mysql_insert_id();

   $sql="SELECT * FROM interstatesanction_invitees WHERE appid='$copyfromid'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $schoolname=addslashes($row[schoolname]);
      $address=addslashes($row[address]);
      $city=addslashes($row[city]);
      $contact=addslashes($row[contact]);
      $sql2="INSERT INTO interstatesanction_invitees (appid,schoolname,address,city,state,zip,contact,phone,nfhsmember) VALUES ('$appid','$schoolname','$address','$city','$row[state]','$row[zip]','$contact','$row[phone]','$row[nfhsmember]')";
      $result2=mysql_query($sql2);
   }
   header("Location:interstatesanction.php?session=$session&appid=$appid&edit=1&copied=1");
   exit();
}

if($savemywork || $send || $save || $addmore)	//AD SUBMITTED FORM - CHECK FOR ERRORS, ADD TO DB FOR NSAA TO REVIEW *OR* FORM WAS UPDATED - SAVE CHANGES
{
   if($send)
   {
      //CHECK FOR ERRORS, UPDATE DB NO MATTER WHAT BUT SHOW ERRORS (IF ANY) AFTERWARDS
      $errors="";
      //First check for missing fields:
      if($sport=="") $errors.="<li>You must select a sport</li>";
      if($mo1=="00" || $day1=="00") $errors.="<li>You must enter the date of the event.</li>";
      else if($mo2=="00" || $day2=="00") { $mo2=$mo1; $day2=$day1; }
      if(!$entryfee) $errors.="<li>You must indicate whether or not an entry fee is charged for this event.</li>";
      if($entryfee=="Yes" && trim($entryfeeamount)=="") $errors.="<li>You must indicate the AMOUNT of the entry fee.</li>";
      if(!$admissionfee) $errors.="<li>You must indicate whether or not an admission fee will be charged at this event.</li>";
      if($admissionfee=="Yes" && trim($admissionfeeamount)=="") $errors.="<li>You must indicate the AMOUNT of the admission fee.</li>";
      if(trim($signature)=="") $errors.="<li>You must enter the name of the person submitting this form.</li>";
      //Check that date(s) are within that sport's season
      if(ereg("bb",$sport) || ereg("wr",$sport) || ereg("sw",$sport))	//Winter
      {
         if(($mo1!=12 && $mo1!=11 && $mo1>2) || ($mo2!=12 && $mo2!=11 && $mo2>2))
            $errors.="<li>The dates you entered are outside of this sport's regular season.</li>";
      }
      else if(ereg("cc",$sport) || $sport=='go_g' || $sport=='te_b' || $sport=='sb' || $sport=='vb' || $sport=='fb')
      {
	 if($mo1<8 || $mo1>10 || $mo2<8 || $mo2>10)
	    $errors.="<li>The dates you entered are outside of this sport's regular season. $mo1 $mo2</li>";
      }
      else
      {
	 if($mo1<3 || $mo1>5 || $mo2<3 || $mo2>5)
	    $errors.="<li>The dates you entered are outside of this sport's regular season.</li>";
      }
      //Check to make sure invited schools are listed (at least one)
      $schoolslisted=0;
      for($i=0;$i<count($schoolname);$i++)
      {
         if(trim($schoolname[$i])!='')
         {
	    $schoolslisted=1; $i=count($schoolname);
         }
      } 
      if($schoolslisted==0) $errors.="<li>You must list the schools invited to this event.</li>";
   }

   //PREPARE DATA FOR DATABASE
   $startdate="$yr1-$mo1-$day1";
   if($mo2=='00' || $day2=='00')	//no End Date filled out, consider it a 1-day event
	$enddate=$startdate;
   else
  	$enddate="$yr2-$mo2-$day2";
   $eventname=addslashes($eventname);
   $sponsor=addslashes($sponsor); $sponsoraddress=addslashes($sponsoraddress); $sponsorcity=addslashes($sponsorcity);
   if($entryfee=="Yes") $entryfee=$entryfeeamount;
   if($admissionfee=="Yes") $admissionfee=$admissionfeeamount;
   $studentawards=addslashes($studentawards);
   $teamawards=addslashes($teamawards);
   $coachawards=addslashes($coachawards);
   $signature=addslashes($signature);
   $pphone=$parea."-".$ppre."-".$ppost;

   if($appid)	//UPDATE DATABASE
   {
      $sql="UPDATE interstatesanctions SET sport='$sport',startdate='$startdate',enddate='$enddate',eventtime='$eventtime',eventname='$eventname',sponsor='$sponsor',sponsoraddress='$sponsoraddress',sponsorcity='$sponsorcity',sponsorstate='$sponsorstate',sponsorzip='$sponsorzip',juniorvarsity='$juniorvarsity',entryfee='$entryfee',admissionfee='$admissionfee',studentawards='$studentawards',teamawards='$teamawards',coachawards='$coachawards',studentawardsvalue='$studentawardsvalue',teamawardsvalue='$teamawardsvalue',coachawardsvalue='$coachawardsvalue',signature='$signature',pphone='$pphone',pemail='$pemail'";
      if($send && $errors=="") $sql.=",submitted='".time()."'";
      if($level!=1) $sql.=",opened='0'";
      $sql.=" WHERE id='$appid'";
      $result=mysql_query($sql);
   }
   else	//INSERT
   {
      $sql="INSERT INTO interstatesanctions (sport,school,startdate,enddate,eventtime,eventname,sponsor,sponsoraddress,sponsorcity,sponsorstate,sponsorzip,juniorvarsity,entryfee,admissionfee,studentawards,teamawards,coachawards,studentawardsvalue,teamawardsvalue,coachawardsvalue,signature,pphone,pemail";
      if($send && $errors=="") $sql.=",submitted";
      $sql.=") VALUES ('$sport','$school2','$startdate','$enddate','$eventtime','$eventname','$sponsor','$sponsoraddress','$sponsorcity','$sponsorstate','$sponsorzip','$juniorvarsity','$entryfee','$admissionfee','$studentawards','$teamawards','$coachawards','$studentawardsvalue','$teamawardsvalue','$coachawardsvalue','$signature','$pphone','$pemail'";
      if($send && $errors=="") $sql.=",'".time()."'";
      $sql.=")";
      $result=mysql_query($sql);
      $appid=mysql_insert_id();
   }

   //INVITEES
   $sql="DELETE FROM interstatesanction_invitees WHERE appid='$appid'";
   $result=mysql_query($sql);
   for($i=0;$i<count($schoolname);$i++)
   {
      if(trim($schoolname[$i])!='')
      {
	 $schoolname[$i]=addslashes($schoolname[$i]);
         $address[$i]=addslashes($address[$i]);
         $city[$i]=addslashes($city[$i]);
	 $phone="$area[$i]-$pre[$i]-$post[$i]";
	 $contact[$i]=addslashes($contact[$i]);
         $sql="INSERT INTO interstatesanction_invitees (appid,schoolname,address,city,state,zip,contact,phone,nfhsmember) VALUES ('$appid','$schoolname[$i]','$address[$i]','$city[$i]','$state[$i]','$zip[$i]','$contact[$i]','$phone','$nfhsmember[$i]')";
	 $result=mysql_query($sql);
      }
   }

   if($level!=1 && $send && $errors=="")	//SHOW CONFIRMATION
   {
      echo $init_html;
      echo $header;
      echo "<br /><br /><div class='alert' style='width:500px'><b>Thank you for submitting an Application for Sanction of Interstate Athletic Events.</b><br /><br />Your application has been sent to the NSAA. The NSAA will notify you when final approval of this event has been made.</div><br /><br /><a href=\"sanctionslist.php?eventtype=interstatesanctions&session=$session\">Return to Sanctions for Interstate/International Athletic & Fine Arts Events MAIN MENU</a>";
      echo $end_html;
      exit();
   }
}
else if($saveaction)	//STATE ASSOCIATION (OR OTHER LEVEL 7 USER) TOOK ACTION
{
   $state=GetActivity($session);
   for($i=0;$i<count($inviteeid);$i++)
   {
      $nojurisdiction[$i]=addslashes(trim($nojurisdiction[$i]));
      $comments[$i]=addslashes(trim($comments[$i]));
      $singnature[$i]=addslashes(trim($signature[$i]));
      if(trim($signature[$i])!='') $datesigned=time();
      else $datesigned=0;
      $sql="UPDATE interstatesanction_invitees SET membership='$membership[$i]',action='$action[$i]',nojurisdiction='$nojurisdiction[$i]',comments='$comments[$i]',signature='$signature[$i]' WHERE id='$inviteeid[$i]'";
      $result=mysql_query($sql);
      //ONLY UPDATE datesigned IF THIS IS THE FIRST TIME ACTION's BEEN TAKEN ON THIS INVITEE
      $sql="UPDATE interstatesanction_invitees SET datesigned='$datesigned' WHERE id='$inviteeid[$i]' AND datesigned=0";	
      $result=mysql_query($sql);
      $sql="UPDATE interstatesanction_invitees SET datesigned=0 WHERE signature=''";	//If NO SIGNATURE, make sure datesigned is 0
      $result=mysql_query($sql);
   }
   //SEE IF USER HAS TAKEN ACTION ON ALL SCHOOLS FOR THIS APPLICTION; IF SO, MARK $state.approved FIELD IN interstatesanctions TABLE
   $sql="SELECT id FROM interstatesanction_invitees WHERE appid='$appid' AND (membership='' OR action='' OR signature='' OR datesigned=0) AND state='$state'";
   $result=mysql_query($sql);
   $actiondone=0;
   if(mysql_num_rows($result)==0)	//ALL HAVE BEEN TAKEN ACTION ON
   {
      $sql="UPDATE interstatesanctions SET ".$state."approved='".time()."' WHERE id='$appid' AND ".$state."approved<=1";
      $result=mysql_query($sql);
      $actiondone=1;
   }
}
else if($nsaasubmit && $level==1)	//NSAA APPROVED REQUEST
{
   if($hostaction && $hostmembership) 
   {
      $NSAAapproved=time(); $nsaaerror=0;
   }
   else 
   {
      $NSAAapproved=0; $nsaaerror=1;
   }
   $sql="UPDATE interstatesanctions SET membership='$hostmembership',action='$hostaction',nojurisdiction='".addslashes(trim($hostnojurisdiction))."',comments='".addslashes(trim($hostcomments))."' WHERE id='$appid'";
   $result=mysql_query($sql);
   //ONLY UPDATE APPROVAL DATE IF IT WAS 0 SO FAR
   $sql="UPDATE interstatesanctions SET NSAAapproved='$NSAAapproved' WHERE id='$appid' AND NSAAapproved=0";
   $result=mysql_query($sql);
}
else if($level==1 && $nsaafinal)	//NSAA COMPLETED FINAL APPROVAL
{
   //ONLY UPDATE FINAL APPROVAL DATE IF IT WAS 0 SO FAR
   $sql="UPDATE interstatesanctions SET NSAAfinal='".time()."' WHERE id='$appid' AND NSAAfinal=0";
   $result=mysql_query($sql);
   $finaldone=1;
}
else if($level==1 && $unapprove==1)
{
   $sql="UPDATE interstatesanctions SET NSAAfinal=0 WHERE id='$appid'";
   $result=mysql_query($sql);
}

if($pdf!=1)
{
echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Sanctions.js"></script>
<script language="javascript">
<?php echo $autotab; ?>
</script>
</head>
<body onload="Sanctions.initialize('interstatesanctions');">
<?php
echo $header;
}

//IF THIS FORM HAS ALREADY BEEN INITIATED, WE WANT TO GET THE DATA:
$sql="SELECT * FROM interstatesanctions WHERE id='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

if($pdf!=1)
{
echo "<form method='post' action='interstatesanction.php'>";
echo "<input type='hidden' name='session' value='$session'>";
echo "<input type='hidden' name='appid' value='$appid'>";
if($level==1)
   echo "<br /><a href=\"sanctionsadmin.php?session=$session\">Return to Applications for Sanction MAIN MENU</a><br />";
else if($level==7)
   echo "<br /><a href=\"welcome.php?session=$session\">Return to MAIN MENU</a><br />";
else
   echo "<br /><a href=\"sanctionslist.php?eventtype=interstatesanctions&session=$session\">Return to Applications for Sanction MAIN MENU</a><br />";
echo "<br /><table cellspacing=2 cellpadding=2 width='900px'>";
echo "<caption><b>Application for Sanction of Interstate Athletic Event:</b><br><br>";

/**** IF BRAND NEW APPLICATION, GIVE OPTION TO COPY FROM ONE OF LAST YEAR'S APPLICATIONS (Added 11/16/10) ****/
if(!$appid)
{
   echo "<div class='helpbig' style='width:700px;padding:10px;margin:10px;'>";
   echo "<p><b><i>Want to COPY an application from a previous year and update it with this year's information?</b></i></p>";
   echo "<select name='copyfromid'><option value='0'>Select Application from Previous Year</option>";
   $sql2="SELECT * FROM interstatesanctions WHERE school='$school2' AND submitted>0 ORDER BY startdate DESC,enddate DESC";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $start=split("-",$row2[startdate]); $end=split("-",$row2[enddate]);
      if($row2[startdate]==$row2[enddate]) $eventdate="$start[1]/$start[2]/".substr($start[0],2,2);
      else $eventdate="$start[1]/$start[2]/".substr($start[0],2,2)." - $end[1]/$end[2]/".substr($end[0],2,2);
      echo "<option value='$row2[id]'>$row2[eventname] - $eventdate, submitted ".date("m/d/y",$row2[submitted])."</option>";
   }
   echo "</select>&nbsp;<input type=submit name=\"copy\" value=\"COPY TO NEW APPLICATION\"></div>";
}
/**** END IF BRAND NEW APPLICATION ****/

if($copied)
   echo "<div class='helpbig' style='width:700px;padding:10px;text-align:left;'>The selected application has been copied to a new application below, with the dates moved ONE YEAR forward. You may now proceed with the completion of this form, following the instructions in the yellow box below.</div>";

if($errors && $errors!='')
{
   echo "<div class='error' style='width:400px;'>You have the following errors in your form:<br><div class='normalwhite'><ul>$errors</ul></div><br>Please correct these errors in the form below.</div><br><br>";
   $edit=1;
}
else if($level!=1 && $row[submitted]>0 && $row[opened]==0)   //No errors, form already submitted - AD CANNOT EDIT
{
   $edit=0;
}
if($saveaction)
{
   echo "<div class='alert' style='width:400px;'>Your ACTIONS have been saved. Please double-check SECTION 3 below to make sure everything is complete and correct.";
   if($actiondone==1)
      echo "<br><br><b>You have COMPLETED ACTION for your school(s) listed below.</b> <a href=\"welcome.php?session=$session\" class=small>Return to MAIN MENU</a></div><br><br>";
   else
      echo "</div><br><div class='error'><b>YOU HAVE NOT COMPLETED ACTION FOR ALL SCHOOLS LISTED BELOW.</b><br>Please make sure you enter your Electronic Signature next to each school in Section 2 and complete all information for each school. The NSAA cannot proceed with this application until you have finished taking action on each school.</div><br><br>";
}
if($nsaasubmit)
{
   echo "<div class='alert' style='width:400px;'>Your ACTIONS have been saved. Please double-check SECTION 2 below to make sure everything is complete and correct.";
   if($nsaaerror==1)
      echo "</div><br><div class='error'><b>YOU HAVE NOT COMPLETED ACTION FOR THIS APPLICATION.</b><br>Please make sure you select the membership status and the action you are taking in Section 2 below.</div><br><br>";
}
else if($nsaafinal)
{
   echo "<div class='alert' style='width:400px;'>";
   if($finaldone==1)
      echo "<b>You have COMPLETED ACTION for this application.</b> <a href=\"sanctionsadmin.php?session=$session\" class=small>Return to Sanctions MAIN MENU</a>";
   echo "</div><br><br>";
}
else if($level==1 && $unapprove==1)
{
   echo "<div class='alert' style='width:400px;'>The final NSAA approval for this application has been retracted.</div>";
}
if($savemywork)
{
   if($level==1)
      echo "<div class='help'>The form below has been saved. You may continue working on this form or <a style='color:#8b0000;' href=\"sanctionsadmin.php?session=$session\">Return to the Applications for Sanction Main Menu</a>.</div>";
   echo "<div class='help'>Your work below has been saved. You may continue working on this form or <a style='color:#8b0000;' href=\"sanctionslist.php?eventtype=interstatesanctions&session=$session\">Return to your Applications for Sanction Main Menu</a>.</div>";
}
echo "</caption>";
}//end if NOT PDF

if(!$appid || $addmore || $savemywork) $edit=1;
//SECTION 1: COMPLETED BY HOST SCHOOL AD
if($pdf!=1)
{
   echo "<tr align=left><td><b><u>SECTION 1 (To be completed by host school):</b></u><br>";
   if($row[submitted]==0 && $level!=1)
      echo "<p><br><a onClick=\"return confirm('Are you sure you want to delete this application? This action cannot be undone.');\" href=\"sanctionslist.php?session=$session&table=interstatesanctions&delete=$row[id]\">Delete this Application</a></p>";
   echo "<div class='alert'><font style='color:#8b0000;font-size:9pt;'><b>INSTRUCTIONS - PLEASE READ CAREFULLY!</b></font><br><ul><li>To <b><u>SAVE THIS FORM</b></u> and come back to work on it later, click <b><u>\"Save My Work\"</b></u> at the bottom of this section.</li><li>Once you have <b><u>COMPLETED</b></u> Section 1 above <b><u>IN FULL</b></u>, click <b><u>\"Submit to NSAA\"</b></u> at the bottom of this section to send this form to the NSAA.</li><li><b><u>PLEASE NOTE:</b></u> You will <b><u>NOT</b></u> be able to make changes after submitting this form to the NSAA!!</li></ul></div>";
}
else 
   $pdfhtml.="<tr align=left><td><b><u>SECTION 1 (To be completed by host school):</b></u>";
if($row[submitted]>0 && $pdf!=1)
{
   echo "<br><div class='alert'>This application was submitted by the host school on <b>".date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted])."</b>.";
   if($level==1)
   {
      echo "&nbsp;&nbsp;<a href=\"interstatesanction.php?session=$session&appid=$appid&edit=1\">EDIT SECTION 1</a>";
      if($row[opened]>0)
      {
         echo "<br><font style='color:#333333;'>[Host school currently can EDIT this form. It will lock again once the AD submits it. Or you can <a class=small href=\"interstatesanction.php?session=$session&appid=$appid&edit=$edit&close=1\">Lock this Form</a>]</font>";
      }
      else
      {
         echo "<br><font style='color:#333333;'>[Host school is LOCKED from editing this form. <a class=small href=\"interstatesanction.php?session=$session&appid=$appid&edit=$edit&open=1\">Unlock this Form</a>]</font>";
      }
   }
   else
   {
      if($row[opened]>0 && $edit!=1)
         echo "&nbsp;&nbsp;<a href=\"interstatesanction.php?session=$session&appid=$appid&edit=1\">EDIT THIS SECTION</a>";
      else if($row[opened]>0)
         echo "&nbsp;&nbsp;You are now EDITING this section. Please click \"Submit to NSAA\" when you are finished.";
      else echo "&nbsp;&nbsp;You can no longer edit this application. Please contact the NSAA if you must make a change.";
   }
   echo "</div>";
}
if($pdf!=1) echo "</td></tr>";
else $pdfhtml.="</td></tr>";
//Description of Event:
$string="<tr align=left><td><b>Description of Event:</b></td></tr>";
$string.="<tr align=left><td><b>Sport:</b>&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
{
   echo "<select name='sport' id='sport'><option value=''>Select a Sport</option>";
   for($i=0;$i<count($sanctionsp);$i++)
   {
      if(ereg("Season",$sanctionsp[$i]))
         echo "<optgroup label=\"$sanctionsp[$i]\">";
      else
      {
         echo "<option value=\"$sanctionsp2[$i]\"";
         if($row[sport]==$sanctionsp2[$i])  echo " selected"; 
         echo ">$sanctionsp[$i]</option>";
      }
   }
   echo "</select>";
}
else 
{
   $string=GetActivityName($row[sport]);
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr>";
$string.="<tr align=left><td><b>Date(s) of Event:</b>&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
{
   echo "<select name=\"mo1\"><option value='00'>MM</option>";
   $start=split("-",$row[startdate]);
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option value='$m'";
      if($start[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"day1\"><option value='00'>DD</option>"; 
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option value='$d'";
      if($start[2]==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"yr1\">";
   $year=date("Y"); $yearbefore=$year-1; $yearafter=$year+2;
   if($level!=1) $yearbefore++;
   for($i=$yearbefore;$i<=$yearafter;$i++)
   {
      echo "<option value='$i'";
      if($start[0]==$i || (!$start[0] && date("Y")==$i)) echo " selected";
      echo ">$i</option>";
   }
   echo "</select>&nbsp;-&nbsp;";
   echo "<select name=\"mo2\"><option value='00'>MM</option>";
   $end=split("-",$row[enddate]);
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option value='$m'";
      if($end[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"day2\"><option value='00'>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option value='$d'";
      if($end[2]==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"yr2\">";
   for($i=$yearbefore;$i<=$yearafter;$i++)
   {
      echo "<option value='$i'";
      if($end[0]==$i || (!$end[0] && date("Y")==$i)) echo " selected";
      echo ">$i</option>";
   }
   echo "</select>";
}
else
{
   $start=split("-",$row[startdate]);
   $end=split("-",$row[enddate]);
   $string="$start[1]/$start[2]/$start[0]";
   if($row[startdate]!=$row[enddate])
      $string.=" - $end[1]/$end[2]/$end[0]";
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr>";
$string.="<tr align=left><td><b>Time of Event:</b>&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
   echo "<input type=text size=15 name=\"eventtime\" value=\"$row[eventtime]\">";
else
{
   $string=$row[eventtime];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr>";
$string.="<tr align=left><td><b>Name of Event:</b>&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
   echo "<input type=text size=45 name=\"eventname\" value=\"$row[eventname]\">";
else
{
   $string=$row[eventname];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
   $filename=ereg_replace("[^a-zA-Z0-9]","",$row[eventname]);
}
$string="</td></tr>";
if($appid)
   $string.="<tr align=left><td><b>Host School:</b> $row[school]</td></tr>";
else
   $string.="<tr align=left><td><b>Host School:</b> $school</td></tr>";
$string.="<tr align=left><td><b>Sponsor:</b>&nbsp;"; 
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && !pdf!=1)
{
   echo "<input type=text size=40 name=\"sponsor\" value=\"$row[sponsor]\"><br>";
   echo "Sponsor Address: <input type=text size=30 name=\"sponsoraddress\" value=\"$row[sponsoraddress]\"><br>";
   echo "Sponsor City, State: <input type=text size=20 name=\"sponsorcity\" value=\"$row[sponsorcity]\">, <input type=text size=3 maxlength=2 name=\"sponsorstate\" value=\"$row[sponsorstate]\"> Zip: <input type=text size=6 name=\"sponsorzip\" value=\"$row[sponsorzip]\">";
}
else
{
   $string=$row[sponsor]." ($row[sponsoraddress], $row[sponsorcity] $row[sponsorstate] $row[sponsorzip])";
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr>";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
//INVITED SCHOOLS:
if($pdf==1) $pdfhtml.="<tr align=left><td><b>Invited Schools:</b></td></tr>";
else
   echo "<tr align=left><td><b>Please list all invited schools, including addresses, contact persons and telephone numbers.</b><br><i>NOTE: If a school is not a full member of its NFHS member association, please uncheck the box in the \"NFHS Member\" column.</i></td></tr>";
$string="<tr align=center><td>";	//BEGIN TABLE OF INVITED SCHOOLS
	//GET INVITED SCHOOLS (IF ANY) FROM DB
	$sql2="SELECT * FROM interstatesanction_invitees WHERE appid='$appid' ORDER BY schoolname";
	$result2=mysql_query($sql2);
 	$invitees=array(); $i=0;
	while($row2=mysql_fetch_array($result2))
	{
	   foreach($row2 as $key => $value)
	   {
	      $invitees[$i][$key]=$value;
	   }
	   $i++;
	}	
	$string.="<table border='1'>";
	$string.="<tr align=center><td><b>Name of School</b></td><td><b>Address, City, State, Zip</b></td><td><b>Contact Person<br>Name & Phone Number</b></td><td><b>NFHS<br>Member</b></td></tr>";
        if($pdf==1) $pdfhtml.=$string;
        else echo $string;
  	$max=10; $currentcount=$i;
	if($currentcount>=$max) $max=$currentcount+5;	
	if(!$edit) $max=$currentcount;
	for($i=0;$i<$max;$i++)
	{
	   $string="<tr valign=top align=left";
	   if($i%2==0) $string.=" bgcolor='#f0f0f0'";
	   $string.="><td>";
	   if($pdf==1) $pdfhtml.=$string;
	   else echo $string;
	   if($edit && $pdf!=1) echo "<input type=text name=\"schoolname[$i]\" value=\"".$invitees[$i][schoolname]."\" size=30>";
	   else 
	   {
	      $string=$invitees[$i][schoolname];
	      if($pdf==1) $pdfhtml.=$string;
	      else echo $string;
	   }
	   $string="</td>";
	   $string.="<td>";
	   if($pdf==1) $pdfhtml.=$string;
	   else echo $string;
	   if($edit && $pdf!=1) 
	   {
	      echo "Address: <input type=text name=\"address[$i]\" value=\"".$invitees[$i][address]."\" size=45><br>";
	      echo "City: <input type=text name=\"city[$i]\" value=\"".$invitees[$i][city]."\" size=20>, State:&nbsp;";
	      echo "<select name=\"state[$i]\"><option value=''>~</option>";
	      for($j=0;$j<count($states);$j++)
	      {
	         echo "<option value=\"$states[$j]\"";
	         if($invitees[$i][state]==$states[$j]) echo " selected";
	         echo ">$states[$j]</option>";
	      }
	      echo "</select> Zip: <input type=text name=\"zip[$i]\" value=\"".$invitees[$i][zip]."\" size=8>";
	   }
	   else
	   {
	      $string=$invitees[$i][address]."<br>".$invitees[$i][city].", ".$invitees[$i][state]." ".$invitees[$i][zip];
	      if($pdf==1) $pdfhtml.=$string;
	      else echo $string;
	   }
	   $string="</td><td>";
	   if($pdf==1) $pdfhtml.=$string;
	   else echo $string;
	   if($edit && $pdf!=1)
	   {
	      echo "Contact Name: <input type=text name=\"contact[$i]\" value=\"".$invitees[$i][contact]."\" size=20><br>";
	      $phone=split("-",$invitees[$i][phone]);
	      echo "Phone: (<input type=text name=\"area[$i]\" value=\"$phone[0]\" size=3 maxlength=3 onKeyUp='return autoTab(this,3,event);'>)<input type=text name=\"pre[$i]\" value=\"$phone[1]\" onKeyUp='return autoTab(this,3,event);' size=3 maxlength=3>-<input type=text onKeyUp='return autoTab(this,4,event);' name=\"post[$i]\" value=\"$phone[2]\" size=4 maxlength=4>";
	   }
	   else
	   {
	      $string=$invitees[$i][contact]."<br>".$invitees[$i][phone];
	      if($pdf==1) $pdfhtml.=$string;
	      else echo $string;
	   }
	   $string="</td><td align=center>";
	   if($pdf==1) $pdfhtml.=$string;
	   else echo $string;
	   if($edit && $pdf!=1)
	   {
	      echo "<input type=checkbox name=\"nfhsmember[$i]\" value=\"x\"";
	      if($invitees[$i][nfhsmember]=='x') echo " checked";
	      else if($i>=$currentcount) echo " checked";
	      echo ">";
	   }
	   else
	   {
	      $string=strtoupper($invitees[$i][nfhsmember]);
	      if($pdf==1) $pdfhtml.=$string;
	      else echo $string;
	   }
	   $string="</td></tr>";
	   if($pdf==1) $pdfhtml.=$string;
	   else echo $string;
	}
$string="</table></td></tr>";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
//END TABLE OF INVITED SCHOOLS
if($edit && $pdf!=1)
   echo "<tr align=center><td><div class='alert' style='width:500px;padding:10px;'><font style='color:#8b0000;font-size:9pt;'><b><i>Need more room to list schools?</b></i></font><br><br>Click \"Add More Schools\" below to save the information you've entered so far and be able to add more schools. You will then be given at least 5 more lines to add schools. If you fill those lines up, click \"Add More Schools\" and so on to add five more, and so on.<br><br><input type=submit name=\"addmore\" value=\"Add More Schools\"></div></td></tr>";
$string="<tr align=left><td>Will any <b>junior varsity teams</b> be participating?&nbsp;"; 
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
{
   echo "<input type=radio name=\"juniorvarsity\" value=\"Yes\"";
   if($row[juniorvarsity]=='Yes') echo " checked";
   echo ">Yes&nbsp;<input type=radio name=\"juniorvarsity\" value=\"No\"";
   if($row[juniorvarsity]=="No") echo " checked";
   echo ">No";
}
else
{
   $string=strtoupper($row[juniorvarsity]);
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr><tr align=left><td>Is there an <b>entry fee</b> for this event?&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
{
   echo "<input type=radio name=\"entryfee\" value=\"Yes\"";
   if($row[entryfee]!="No" && trim($row[entryfee])!='') 
   {
      echo " checked";
      $entryfeeamount=number_format($row[entryfee],2,'.',',');
   }
   else $entryfeeamount="";
   echo "> Yes (If yes, enter the amount: $<input type=text name=\"entryfeeamount\" value=\"$entryfeeamount\" size=5>)&nbsp;";
   echo "<input type=radio name=\"entryfee\" value=\"No\"";
   if($row[entryfee]=="No") echo " checked";
   echo "> No";
}
else
{
   if($row[entryfee]=="No") $string="NO";
   else $string="$".$row[entryfee];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr><tr align=left><td>Will you charge an <b>admission fee</b> for this event?&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
{
   echo "<input type=radio name=\"admissionfee\" value=\"Yes\"";
   if($row[admissionfee]!="No" && trim($row[admissionfee])!='') 
   {
      echo " checked";
      $admissionfeeamount=number_format($row[admissionfee],2,'.',',');
   }
   else $admissionfeeamount="";
   echo "> Yes (If yes, enter the amount: $<input type=text name=\"admissionfeeamount\" value=\"$admissionfeeamount\" size=5>)&nbsp;";
   echo "<input type=radio name=\"admissionfee\" value=\"No\"";
   if($row[admissionfee]=="No") echo " checked";
   echo "> No";
}
else 
{
   if($row[admissionfee]=="No") $string="NO";
   else $string="$".$row[admissionfee];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
//Description of Awards & Other Compensation:
$string="</td></tr><tr align=left><td><b>Description of Awards and Other Compensations and Maximum Retail Value:</b><br>(ribbons, trophies, t-shirts, practice uniform, waiver of entry fee, waiver of travel expenses, etc.)</td></tr>";
$string.="<tr align=center><td><table border='1'>";
$string.="<tr align=left><td>Individual Student Athlete Participant Awards:<br>";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
   echo "<textarea name=\"studentawards\" rows=4 cols=35>$row[studentawards]</textarea><br>Maximum retail value for each item: $<input type=text name=\"studentawardsvalue\" value=\"$row[studentawardsvalue]\" size=8>";
else
{
   $string="<br>$row[studentawards]<br><br>Maximum retail value for each item: $".$row[studentawardsvalue];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td><td>Team Awards:<br>";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
   echo "<textarea name=\"teamawards\" rows=4 cols=35>$row[teamawards]</textarea><br>Maximum retail value: $<input type=text name=\"teamawardsvalue\" value=\"$row[teamawardsvalue]\" size=8>";
else
{
   $string="<br>$row[teamawardsvalue]<br><br>Maximum retail value: $".$row[teamawardsvalue];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td><td>Coaches Awards:<br>";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
   echo "<textarea name=\"coachawards\" rows=4 cols=35>$row[coachawards]</textarea><br>Maximum retail value: $<input type=text name=\"coachawardsvalue\" value=\"$row[coachawardsvalue]\" size=8>";
else
{
   $string="<br>$row[coachawards]<br><br>Maximum retail value: $".$row[coachawardsvalue];
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr></table></td></tr>";
//Legal Wording & Signature:
$string.="<tr align=left><td><b>Principal's Responsibility:</b> Execution of this form constitutes an agreement by the principal of the host school, upon request, to submit a financial report about the event to the NFHS on the NFHS Financial Report Form found at www.nfhs.org. Execution also constitutes an agreement by the principal to assume oversight responsibility for the event, and to be present on site during the event, either in person or by a designee.</td></tr>";
$string.="<tr align=left><td><b>Executed by:</b>&nbsp;";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if($edit && $pdf!=1)
{
   echo "<input type=text name=\"signature\" size=30 value=\"$row[signature]\">&nbsp;&nbsp;";
   $phone=split("-",$row[pphone]);
   echo "Phone: (<input type=text name=\"parea\" value=\"$phone[0]\" onKeyUp='return autoTab(this,3,event);' size=3 maxlength=3>)<input type=text onKeyUp='return autoTab(this,3,event);' name=\"ppre\" value=\"$phone[1]\" size=3 maxlength=3>-<input type=text onKeyUp='return autoTab(this,4,event);' name=\"ppost\" value=\"$phone[2]\" size=4 maxlength=4>&nbsp;&nbsp;";
   echo "Email: <input type=text name=\"pemail\" value=\"$row[pemail]\" size=30>";
}
else
{
   $string="$row[signature]&nbsp;&nbsp;&nbsp;Phone: $row[pphone]&nbsp;&nbsp;Email: $row[pemail]";
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
$string="</td></tr>";
if($pdf==1) $pdfhtml.=$string;
else echo $string;
if(($row[submitted]==0 || ($edit==1 && ($level==1 || $row[opened]>0))) && $pdf!=1)
{
   if($level==1)
      echo "<tr align=center><td><input type=submit name=\"send\" value=\"Save SECTION 1 Information\"></td></tr>";
   else
   {
      echo "<tr><td><div class='alert'><font style='color:#8b0000;font-size:9pt;'><b>INSTRUCTIONS - PLEASE READ CAREFULLY!</b></font><br><ul><li>To <b><u>SAVE THIS FORM</b></u> and come back to work on it later, click <b><u>\"Save My Work\"</b></u>.</li><li>Once you have <b><u>COMPLETED</b></u> Section 1 above <b><u>IN FULL</b></u>, click <b><u>\"Submit to NSAA\"</b></u> to send this form to the NSAA.</li><li><b><u>PLEASE NOTE:</b></u> You will <b><u>NOT</b></u> be able to make changes after submitting this form to the NSAA!!</li></ul></div></td></tr>";
      echo "<tr align=center><td><input type=submit name=\"savemywork\" value=\"SAVE MY WORK\">&nbsp;&nbsp;<input type=submit name=\"send\" value=\"SUBMIT TO NSAA\"></td></tr>";
   }
}
//END SECTION 1
//SECTION 2: NSAA APPROVAL
if($row[submitted]>0)
{
   if($level==1 && $row[NSAAapproved]==0) $nsaaedit=1;   
   else $nsaaedit=0;   
   $string="<tr align=left><td><br><b><u>SECTION 2: Action by NSAA:</u></b><br>";   
   if($row[NSAAapproved]==0 && $level!=1)   
   {      
      $string.="<br>[AWAITING PRELIMINARY ACTION BY THE NSAA]<br></td></tr>";      
      echo $string;   
   }   
   else   
   {      
      if($pdf==1) $pdfhtml.=$string;      
      else echo $string;      
      if($nsaaedit==1)         
      echo "<div class=alert><b>Please fill out the information below and click \"Submit NSAA Approval of Sanction\" at the bottom. (You will complete FINAL approval of this sanction after the invited schools have completed action on this form.)</div>";
      $string="</td></tr><tr align=left><td>";
      $string.="<b>$row[school] Membership:</b><br>";
      if($pdf==1) $pdfhtml.=$string;
      else echo $string;
      if($nsaaedit==1)
      {
         echo "<input type=radio name=\"hostmembership\" value=\"State Association Member School\"";
         if($row[membership]=="State Association Member School") echo " checked";
         echo ">State Association Member School<br>";
         echo "<input type=radio name=\"hostmembership\" value=\"School Approved by State Association\"";
         if($row[membership]=="School Approved by State Association") echo " checked";
         echo ">School Approved by State Association<br>";
         echo "<input type=radio name=\"hostmembership\" value=\"Non-Member School\"";
         if($row[membership]=="Non-Member School") echo " checked";
         echo ">Non-Member School";
      }
      else
      {
         $string=$row[membership];
         if($pdf==1) $pdfhtml.=$string;
         else echo $string;
      }
      $string="<br><b>Preliminary Action by NSAA:</b><br>";
      if($pdf==1) $pdfhtml.=$string;
      else echo $string;
      if($nsaaedit!=1)
      {
         $string="$row[action]<br><i>$row[nojurisdiction]</i>";
         if($pdf==1) $pdfhtml.=$string;
         else echo $string;
      }
      else
      {
         echo "<input type=radio name=\"hostaction\" value=\"Sanction Event\"";
         if($row[action]=="Sanction Event") echo " checked";
         echo ">Sanction Event<br>";
         echo "<input type=radio name=\"hostaction\" value=\"Do Not Sanction Event\"";
         if($row[action]=="Do Not Sanction Event") echo " checked";
         echo ">Do Not Sanction Event<br>";
         echo "<input type=radio name=\"hostaction\" value=\"No Jurisdiction\"";
         if($row[action]=="No Jurisdiction") echo " checked";
         echo ">No Jurisdiction";
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If \"No Jurisdiction\" explain why:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name=\"hostnojurisdiction\" rows=3 cols=50>$row[nojurisdiction]</textarea>";
      }
      $string="<br><b>Limitations/Other Comments:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      if($pdf==1) $pdfhtml.=$string;
      else echo $string;
      if($nsaaedit!=1)
      {
         if($row[comments]=="") $row[comments]="[None]";
         $string="$row[comments]&nbsp;";
         if($pdf==1) $pdfhtml.=$string;
         else echo $string;
      }
      else
         echo "<textarea name=\"hostcomments\" rows=3 cols=50>$row[comments]</textarea>";
      $string="</td></tr>";
      if($pdf==1) $pdfhtml.=$string;
      else echo $string;
      if($nsaaedit==1)
         echo "<tr align=center><td><input type=hidden name='nsaaedit' value='$nsaaedit'><input type=hidden name='edit' value='$edit'><input type=submit name=\"nsaasubmit\" value=\"Submit NSAA Approval of Sanction\"></td></tr>";
   }
}
if($level==1 && $appid && $edit!=1) //NSAA: LIST OF STATES OF INVITED SCHOOLS, LINK TO EMAIL PASSWORD, CHECKBOX IF SENT EMAIL, RESPONSE RECEIVED
{   
   $string="<tr align=left><td><br><b><u>SECTION 3: Actions by State Associations of Invited Schools:</b></u><br>";   
   if($pdf==1) $pdfhtml.=$string."<table border='1'>";   
   else   
   {
      echo $string;
      if($level==1)
      {
         echo "<div class=alert><a href=\"interstatesanction.php?session=$session&appid=$appid&edit=1#section3\">EDIT SECTION 3</a></div>";
      }
      echo "<table border='1'>";
   }
   //GET INVITED STATES' LOGIN INFO   
   $sql2="SELECT DISTINCT t2.* FROM interstatesanction_invitees AS t1, logins AS t2 WHERE t1.state=t2.sport AND t2.level='7' AND t1.appid='$appid' ORDER BY t1.state";   
   $result2=mysql_query($sql2);   
   $i=0;   
   while($row2=mysql_fetch_array($result2))   
   {      
      if(strlen($row2[passcode])<2)      
      {         
         $passcode=$row2[sport].rand(100000,999999);         
         $sql3="UPDATE logins SET passcode='$passcode' WHERE id='$row2[id]'";         
         $result3=mysql_query($sql3);      
      }      
      else $passcode=$row2[passcode];      
      if($pdf!=1)      
      {         
         echo "<tr align=left><td colspan=6 bgcolor='#fafda2'>";         
	 echo "<input type=hidden name=\"state[$i]\" value=\"$row2[sport]\">";  //this is actually the 2-letter state, not sport         
	 echo "<b>$row2[sport] - $row2[school]</b><br>";        //this is actually the name of the state association, such as Illinois High School Association         
	 echo "Password: $passcode&nbsp;";
         echo "<div onClick=\"document.getElementById('marksent".$i."').checked=true;Sanctions.markSent('$appid','$i','$row2[sport]','1');\"><a class='small' href=\"mailto:$row2[email]?subject=Application for Sanction of Interstate Athletic Event&body=A member school (or otherwise affiliated school) has been invited to an Interstate Athletic Event by a Nebraska School Activities Association Member School.%0D%0A%0D%0ATo review and sanction this event, please use the following information to login to the NSAA online system at https://secure.nsaahome.org/nsaaforms/sanctions/login.php%0D%0A%0D%0AEnter Passcode: $passcode%0D%0A%0D%0APlease contact Deb Velder at the NSAA with any questions: dvelder@nsaahome.org%0D%0A%0D%0AThank You!\">Email Login Information to Contact</a>&nbsp;&nbsp;</div>";
         if($row[$row2[sport]."approved"]<=1)
         {
            echo "<input type=checkbox id=\"marksent".$i."\" name=\"marksent[$i]\" value=\"x\"";
            if($row[$row2[sport]."approved"]==1) echo " checked";
            echo " onClick=\"Sanctions.markSent('$appid','$i','$row2[sport]',(this.checked ? '1':'0'));\">Mark as SENT <div class='normalwhite' id='confirm".$i."' style='width:200px;display:none;'>Your checkmark has been saved.</div>";
         }
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>(You MUST mark that you SENT this person their login information in order for them to be able to access the application.)</b></i></td></tr>";
      }
      else $pdfhtml.="<tr align=left><td colspan=5><b>".strtoupper($row2[school])."</b></td></tr>";
      //GET LIST OF SCHOOLS THIS STATE NEEDS TO APPROVE, SHOW ACTION FOR EACH
      $sql3="SELECT * FROM interstatesanction_invitees WHERE appid='$appid' AND state='$row2[sport]' ORDER BY schoolname";
      $result3=mysql_query($sql3);
      $string="<tr align=center><td><b>School</b></td><td><b>School Membership</b></td><td><b>Action Taken by State Association</b></td><td><b>Comments</b></td><td><b>Signature</b></td></tr>";
      if($pdf==1) $pdfhtml.=$string;
      else echo $string;
      while($row3=mysql_fetch_array($result3))
      {
         $string="<tr valign=top align=left><td>$row3[schoolname]</td>";
         $string.="<td>$row3[membership]&nbsp;</td><td>$row3[action]<br><i>$row3[nojurisdiction]</i>&nbsp;</td><td>$row3[comments]&nbsp;</td>";
         if($row3[datesigned]==0)
            $string.="<td>No Action Taken Yet.</td>";
         else
            $string.="<td>$row3[signature]<br>".date("m/d/y",$row3[datesigned])."</td>";
         $string.="</tr>";
         if($pdf==1) $pdfhtml.=$string;
         else echo $string;
      }
      $i++;
   }
   $string="</table></td></tr>";
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
else if((($level==1 && $edit==1) || $level==7) && $appid)    //ALLOW ASSOCIATION OF INVITED SCHOOL TO APPROVE/DENY
{
   $state=GetActivity($session);
   if($row[$state."approved"]==1) $edit=1;
   echo "<tr align=left><td><a name='section3'><br></a><b><u>SECTION 3: Action by State Association of Invited School(s):</b></u><br>";
   if($edit==1)
       echo "<div class=alert><b>Please fill out the information for each school in the list below and click \"Submit\" at the bottom.</div>";
   echo "</td></tr><tr align=center><td>";
   echo "<table border='1'>";
   //GET LIST OF SCHOOLS THIS STATE NEEDS TO APPROVE, SHOW ACTION FOR EACH
   $sql3="SELECT * FROM interstatesanction_invitees WHERE appid='$appid' ";
   if($level==7) $sql3.="AND state='$state' ";
   $sql3.="ORDER BY schoolname";
   $result3=mysql_query($sql3);
   echo "<tr align=center><td><b>School</b></td><td><b>School Membership</b></td><td><b>Action Taken by State Association</b></td><td><b>Comments</b></td><td><b>Signature</b></td></tr>";
   $i=0;
   while($row3=mysql_fetch_array($result3))
   {
      echo "<tr valign=top align=left";
      if($i%2==0) echo " bgcolor='#f0f0f0'";
      echo "><td>$row3[schoolname]</td>";
      echo "<td><input type=hidden name=\"inviteeid[$i]\" value=\"$row3[id]\">";
      if($edit!=1) echo "$row3[membership]&nbsp;";
      else
      {
         echo "<input type=radio name=\"membership[$i]\" value=\"State Association Member School\"";
         if($row3[membership]=="State Association Member School") echo " checked";
         echo ">State Association Member School<br>";
         echo "<input type=radio name=\"membership[$i]\" value=\"School Approved by State Association\"";
         if($row3[membership]=="School Approved by State Association") echo " checked";
         echo ">School Approved by State Association<br>";
         echo "<input type=radio name=\"membership[$i]\" value=\"Non-Member School\"";
         if($row3[membership]=="Non-Member School") echo " checked";
         echo ">Non-Member School";
      }
      echo "</td><td>";
      if($edit!=1) echo "$row3[action]<br><i>$row3[nojurisdiction]</i>";
      else
      {
         echo "<input type=radio name=\"action[$i]\" value=\"Sanction Event\"";
         if($row3[action]=="Sanction Event") echo " checked";
         echo ">Sanction Event<br>";
         echo "<input type=radio name=\"action[$i]\" value=\"Do Not Sanction Event\"";
         if($row3[action]=="Do Not Sanction Event") echo " checked";
         echo ">Do Not Sanction Event<br>";
         echo "<input type=radio name=\"action[$i]\" value=\"No Jurisdiction\"";
         if($row3[action]=="No Jurisdiction") echo " checked";
         echo ">No Jurisdiction";
         echo "<br>If \"No Jurisdiction\" explain why:<br><textarea name=\"nojurisdiction[$i]\" rows=3 cols=25>$row3[nojurisdiction]</textarea>";
      }
      echo "</td><td>";
      if($edit!=1)echo "$row3[comments]&nbsp;";
      else
         echo "Limitations/Other Comments:<br><textarea name=\"comments[$i]\" rows=7 cols=25>$row3[comments]</textarea>";
      echo "</td><td>";
      if($edit!=1)
      {
         if($row3[datesigned]==0)
            echo "No Action Taken Yet.";
         else
            echo "$row3[signature]<br><div class=alert>Signed on ".date("m/d/y",$row3[datesigned])."</div>";
      }
      else
         echo "<input type=text size=20 name=\"signature[$i]\" value=\"$row3[signature]\"><br><i>(Please type your full name)</i>";
      echo "</td></tr>";
      $i++;
   }
   echo "</table><br>";
   if($level==7 && $edit==1) echo "<input type=submit name=\"saveaction\" value=\"Submit\">";
   else if($level==1 && $edit==1) echo "<input type=submit name=\"saveaction\" value=\"Save SECTION 3 Information\">";
   echo "</td></tr>";
}
//SECTION 4
if($row[NSAAfinal]>0)
{
   $string="<tr align=left><td><br><div class=alert style='font-size:10pt;'><b><u>FINAL APPROVAL BY NSAA:</b></u> This sanction is APPROVED as of ".date("F j, Y",$row[NSAAfinal]).".";
   if($pdf!=1 && $level==1) $string.="&nbsp;&nbsp;<a href=\"interstatesanction.php?session=$session&appid=$appid&unapprove=1\">Retract Approval for this Application</a>";
   $string.="</div></td></tr>";
   if($pdf==1) $pdfhtml.=$string;
   else echo $string;
}
else if($level==1 && $pdf!=1 && $row[NSAAapproved]>0)
{
   echo "<tr align=center><td><br><div class='help' style='width:500px;'>ONCE ALL OF THE INVITED SCHOOLS HAVE COMPLETED ACTION ABOVE, PLEASE SUBMIT FINAL NSAA APPROVAL OF THIS SANCTION BY CLICKING \"Submit Final NSAA Approval\" BELOW:<br><br>";
   echo "<input type=submit name=\"nsaafinal\" value=\"Submit Final NSAA Approval\"><br><div class='alert' style='width:400px;'>Clicking this button will publish this event to the list of Sanctioned Events on the NSAA Website IF \"Sanction Event\" is checked above.</div></div></td></tr>";
   echo "<tr align=center><td><br><a href=\"sanctionsadmin.php?session=$session\">Return to Sanctions Main Menu</a></td></tr>";
}

if($pdf!=1)
{
echo "</table>";
echo "</form>";
echo "<div id='loading' style='display:none;'></div>";
if($level==1)
   echo "<br /><a href=\"sanctionsadmin.php?eventtype=interstatesanctions&session=$session\">Return to Applications for Sanction MAIN MENU</a><br />";
else if($level==7)
   echo "<br /><a href=\"welcome.php?session=$session\">Return to MAIN MENU</a><br />";
else
   echo "<br /><a href=\"sanctionslist.php?eventtype=interstatesanctions&session=$session\">Return to Applications for Sanction MAIN MENU</a><br />";
echo $end_html;
}
else
{
//echo $pdfhtml; exit();
$pdfhtml.="</table>";
$pdfhtml=ereg_replace("<br>","<br />",$pdfhtml);
//echo "<textarea rows=20 cols=100>$pdfhtml</textarea>"; exit();
require_once('../../tcpdf_php4/config/lang/eng.php');
require_once('../../tcpdf_php4/tcpdf.php');
// create new PDF document^M
$orientation="P";
$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
// set document information^M
$pdf->SetCreator("NSAA");
$pdf->SetAuthor("NSAA");
$pdf->SetTitle("Application for Sanction of Interstate Athletic Event");
$pdf->SetSubject("Application for Sanction of Interstate Athletic Event");
$pdf->SetKeywords("sanction,NSAA,NFHS");
$pdf->SetMargins(1,1);
$pdf->SetAutoPageBreak(TRUE, 1);
//set some language-dependent strings
$pdf->setLanguageArray($l);
$pdf->SetFont('helvetica','',10);
//initialize document
$pdf->AliasNbPages();
// add a page
$pdf->AddPage();
$pdf->Image("https://secure.nsaahome.org/images/logofullsize.png",80,3,50);
$pdf->SetY(35);
// output the HTML content^M
$pdf->writeHTML("<b>NSAA APPLICATION FOR<br>SANCTION OF INTERSTATE ATHLETIC EVENTS</b>",true,0,true,0,"C");
$pdf->SetFont('helvetica','',9);
$pdf->writeHTML($pdfhtml, true, 0, true, 0);
$pdf->Output("$filename.pdf", "D");
}
?>
