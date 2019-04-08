<?php

require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$header=GetHeader($session);
$level=GetLevel($session);
if($level!=1) $school=GetSchool($session);

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

$sql="SELECT * FROM misc_duedates WHERE sport LIKE 'allstatenom_%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $date=split("-",$row[duedate]);
   $time=mktime(0,0,0,$date[1],$date[2],$date[0]);
   $duedate=date("F jS",$time);
   if($row[sport]=="allstatenom_fall") $falldate=$duedate;
   else if($row[sport]=="allstatenom_winter") $winterdate=$duedate;
   else $springdate=$duedate;
}
$duedatestable="<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3>
<tr align=center><td>&nbsp;</td><td><b>Completed Nominations<br>must arrive at the NSAA office by:</b></td><td><b>Winners will be announced in:</b></td></tr>
<tr align=center><td><b>Fall Activities</b></td><td>$falldate</td><td>December</td></tr>
<tr align=center><td><b>Winter Activities</b></td><td>$winterdate</td><td>March</td></tr>
<tr align=center><td><b>Spring Activities</b></td><td>$springdate</td><td>May</td></tr>
</table>";

//get school user chose (Level 1) or belongs to (Level 2, 3)
if($level!=1)
{
   $schoolid=GetSchoolID2($school);
}
else if($nomid)
{
   $sql="SELECT schoolid FROM allstatenom WHERE id='$nomid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schoolid=$row[0];
}
else 
{
   echo "ERROR: No Nomination Specified.";
   exit();
}
$sql="SELECT * FROM headers WHERE id='$schoolid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school=$row[school];
$school2=addslashes($school);

if($nomid && $delete)
{
   $sql="SELECT * FROM allstatenom WHERE id='$nomid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   citgf_unlink("/home/nsaahome/attachments/$row[transcript]");
   $sql="UPDATE allstatenom SET transcript='',transcriptdate=0,confirmed=0,released=0 WHERE id='$nomid'";
   $result=mysql_query($sql);
}

if($nomid && !$step && !$hiddensave && !$marktranscript && !$upload && !$submittonsaa && !$makechanges)	//SHOW PRINTABLE VERSION OF SUBMITTED NOMINATION
{
   $sql="SELECT t1.first,t1.last,t1.semesters,t2.* FROM eligibility AS t1, allstatenom AS t2 WHERE t1.id=t2.studentid AND t2.id='$nomid'";
   if($level!=1) $sql.=" AND t2.schoolid='$schoolid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);

   if(mysql_num_rows($result)==0)
   {
      echo $init_html;
      echo "<br><br><table width='100%'><tr align=center><td>";
      echo "<div class=error style=\"width:400px;\">ERROR: Could not find the requested nomination.</div>";
      echo $end_html;
      exit();
   }
   else
   {
      if($row[datesub]==0 || $row[opened]>0 || ($level==1 && $edit==1))	//USER IS EDITING THE FORM 
      {
	 //GET FIELD VALUES
	 $activitych=$row[sport];
	 $activitychstuds1=$row[studentid];
	 $showname1=$row[studentname];
	 $gpa1=$row[gpa];
	 $text1=$row[comments];
      }
      else	//SHOW PRINTABLE VERSION OF SUBMITTED FORM
      {
         echo $init_html;
         echo "<br><br><table width='100%'><tr align=center><td>";
         $string="<div class=\"normalwhite\" style=\"width:600px;text-align:center;padding:10px;\">";
         $string.="<img src=\"/nsaaforms/officials/nsaacontract.jpg\"><br><br>";
         $string.="<table width='100%' class=nine><caption><b>NCPA Academic All-State Awards Nomination Form</b></caption>";
         $string.="<tr align=left><td><br><b>NSAA ACTIVITY:</b>&nbsp;&nbsp;".GetActivityName($row[sport])."</td></tr>";
         $string.="<tr align=left><td><br><b><u>STUDENT NOMINEE:</u></b></td></tr>";
         if($row[studentname]!='') 
	 {
	    $string.="<tr align=left><td><b>Name (according to NSAA database):</b>&nbsp;&nbsp;$row[first] $row[last]</td></tr>";
	    $string.="<tr align=left><td><b>Name (to show on certificate):</b>&nbsp;&nbsp;$row[studentname]";
	 }
	 else
	 {
	    $string.="<tr align=left><td><b>Name:</b>&nbsp;&nbsp;$row[first] $row[last]";
	 }
         $string.="</td></tr>";
         $string.="<tr align=left><td><b>School:</b>&nbsp;&nbsp;$school</td></tr>";
         $string.="<tr align=left><td><b>Grade:</b> ".GetYear($row[semesters])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         $string.="<b>Cumulative High School GPA:</b>&nbsp;&nbsp;$row[gpa]</td></tr>";
         $string.="<tr align=left><td>";
         $text1=ereg_replace("\r\n","<br>",$row[comments]);
         $string.="<b>Briefly explain why this student is being nominated.<br></b>$text1</td></tr>";
         $string.="<tr align=left><td><b>Nomination Form Submitted:</b>  ".date("m/d/y",$row[datesub])." at ".date("g:ia",$row[datesub])."</td></tr>";
	 $string.="<tr align=left><td>";
         if($row[transcript]=='')
            $string.="<b>Transcript Not Received.</b>";
         else if($row[transcript]=="RECEIVED")
            $string.="<b>Transcript Received by NSAA Office.</b>";
         else
            $string.="<b>Transcript Uploaded to NSAA Office:</b> ".date("m/d/y",$row[transcriptdate])." at ".date("g:ia",$row[transcriptdate]);
	 $string.="</td></tr>";
         $string.="</table></div></td></tr></table>";
         echo $string;
         echo $end_html;
         exit();
      }
   }
}

//CHECK IF THIS SCHOOL HAS ALREADY SUBMITTED 2 NOMINATIONS FOR THIS ACTIVITY
$sql="SELECT * FROM allstatenom WHERE schoolid='$schoolid' AND sport='$activitych'";
$result=mysql_query($sql);
if((mysql_num_rows($result)>=2 || $activitych=='' || !$activitych) && !(($level==1 && $edit==1) || $nomid))
{
   echo $init_html;
   echo $header;
   if($activitych=='' || !$activitych)
      echo "<br><br><div class=error style='width:400px;'>ERROR: No Activity Selected.</div><br><br>";
   else
      echo "<br><br><div class=error style='width:400px;'>$school has already submitted 2 nominations for ".GetActivityName($activitych).".</div><br><br>";
   if($level==1)
      echo "<a href=\"allstatenomadmin.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session\">NCPA Academic All-State Nominations Main Menu</a>";
   else
      echo "<a href=\"allstatenomhome.php?session=$session\">NCPA Academic All-State Nominations Main Menu</a>";
   echo $end_html;
   exit();
}

if($hiddensave)	//DOUBLE CHECK FOR ERRORS 
{
   //check for errors
   $error="";
   if($activitych=="")
      $error.="You must select an activity.<br>";
   if($activitychstuds1==0)
      $error.="You must select a student.<br>";
   if($gpa1=="" || ($activitychstuds2>0 && $gpa2==""))
      $error.="You must enter the student's GPA.<br>";
   if($text1=="" || ($activitychstuds2>0 && $text2==""))
      $error.="You must explain the student's contributions to this activity.<br>";

   /***** IF NO ERRORS, ALLOW USER TO CHECK FOR TYPOS AND MAKE CHANGES OR SUBMIT *****/
   if($error=="")
   {
      //SAVE TO DB
      $studentname=addslashes($showname1);
      $comments=addslashes($text1);
      if(!$nomid)
      {
         $sql="INSERT INTO allstatenom (schoolid,sport,studentid,studentname,gpa,comments,datesub) VALUES ('$schoolid','$activitych','$activitychstuds1','$studentname','$gpa1','$comments','".time()."')";
         $result=mysql_query($sql);
         $nomid=mysql_insert_id();
      }
      else
      {
         $sql="UPDATE allstatenom SET studentname='$studentname',gpa='$gpa1',comments='$comments',datesub='".time()."',studentid='$activitychstuds1'";
         //if($level!=1) $sql.=",opened='0',confirmed='0',released='0'";
         $sql.=" WHERE id='$nomid'";
         $result=mysql_query($sql);
      }
  
      echo $init_html;
      echo $header;
      $string.="<table width=100%><tr align=center><td>";
      $string.="<div class=alert style=\"font-size:9pt;width:500px;\"><b>Please review the following information you've entered.</b><br><br>If you need to make changes, click \"Make Changes.\"<br><br>If you are satisfied that the form is complete and accurate, click \"SUBMIT TO NSAA\" to submit the nomination to the NSAA office.</div><br>";
      $string.="<div class=\"normalwhite\" style=\"width:600px;text-align:center;padding:10px;\">";
      $string.="<img src=\"/nsaaforms/officials/nsaacontract.jpg\"><br><br>";
      $string.="<table width='100%' class=nine><caption><b>NCPA Academic All-State Awards Nomination Form</b></caption>";
      $string.="<tr align=left><td><br><b>NSAA ACTIVITY:</b>&nbsp;&nbsp;".GetActivityName($activitych)."</td></tr>";
      $sql="SELECT * FROM eligibility WHERE id='$activitychstuds1'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $string.="<tr align=left><td><br><b><u>STUDENT #1:</u></b></td></tr>";
      $string.="<tr align=left><td><b>Name (first, last):</b>&nbsp;&nbsp;";
      if($showname1=='') $string.="$row[first] $row[last]";
      else $string.=$showname1;
      $string.="</td></tr>";
      $string.="<tr align=left><td><b>School:</b>&nbsp;&nbsp;$row[school]</td></tr>";
      $string.="<tr align=left><td><b>Grade:</b> ".GetYear($row[semesters])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $string.="<b>Cumulative High School GPA:</b>&nbsp;&nbsp;$gpa1</td></tr>";
      $string.="<tr alig=center><td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=4><tr align=left><td>";
      $text1=ereg_replace("\r\n","<br>",$text1);
      $string.="<b>Briefly explain why this student is being nominated.<br></b>$text1</td></tr></table></td></tr>";
      $string.="</table></div></td></tr></table>";
      echo $string;
      echo "<form method=post action=\"allstatenom.php\">";
      echo "<input type=hidden name=\"session\" value=\"$session\">";
      echo "<input type=hidden name=\"sort\" value=\"$sort\">";
      echo "<input type=hidden name=\"confirmed\" value=\"$confirmed\">";
      echo "<input type=hidden name=\"schoolch\" value=\"$schoolch\">";
      echo "<input type=hidden name=\"activitych\" value=\"$activitych\">";
      echo "<input type=hidden name=\"nomid\" value=\"$nomid\">";
      echo "<input type=hidden name=\"activitychstuds1\" value=\"$activitychstuds1\">";
      echo "<input type=hidden name=\"showname1\" value=\"$showname1\">";
      echo "<input type=hidden name=\"gpa1\" value=\"$gpa1\">";
      echo "<input type=hidden name=\"text1\" value=\"$text1\">";
      echo "<br><div class=\"alert\"><p>If you need to correct or add to any of the information above, click \"Make Changes\" below. Otherwise, if the information above is <b><u>complete and correct</u></b>, click \"SUBMIT TO NSAA\" below. You will then be able to <b>upload this student's transcript</b> or you can choose to come back and upload th transcript at a later time, as long as you meet the deadline.</p><p><b><u>PLEASE NOTE:</u> Once you click \"SUBMIT TO NSAA\" below, you will no longer be able to make changes to the information you entered on this nomination form.</b></p></div><br>";
      echo "<br><input type=submit name=makechanges class=\"fancybutton\" value=\"&larr; Make Changes\">&nbsp;&nbsp;&nbsp;";
      echo "<input type=submit name=\"submittonsaa\" class=\"fancybutton\" value=\"SUBMIT TO NSAA\" onClick=\"return confirm('Are you sure you wish to submit this nomination form as COMPLETE AND ACCURATE to the NSAA? You will not be able to make changes to this nomination form after you submit it to the NSAA.');\"></form>";
      echo $end_html;
      exit();
   }
}
else if($step==2 || $marktranscript || $upload || $submittonsaa) //SAVE TO DATABASE OR USER IS SKIPPING TO STEP 2
{
   if($upload)
   {
      $uploadedfile = $_FILES['transcript']['tmp_name'];
      $uploaderror="";
      if(is_uploaded_file($uploadedfile))
      {
         $ext = strtolower(pathinfo($_FILES['transcript']['name'], PATHINFO_EXTENSION));
         $filename="transcript".$nomid.".".$ext;
         if(!citgf_copy($uploadedfile,"/home/nsaahome/attachments/".$filename))
            $uploaderror="Could not upload the file $filename. Is the file too large? File must be smaller than 3MB.";
         else
         {
            $sql="UPDATE allstatenom SET transcript='".$filename."',transcriptdate='".time()."',confirmed='".time()."',released='".time()."' WHERE id='$nomid'";
            $result=mysql_query($sql);
            if(mysql_error()) $uploaderror=mysql_error();
            else if($level==1)
   	    {
		echo $init_html;
		echo $header;
		echo "<br><br><div class=alert><b>Thank you for completing Step 2 by uploading the student's transcript!</b></div><br><br><a href=\"allstatenomadmin.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session\">NCPA Academic All-State Awards Main Menu</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"welcome.php?session=$session\">Home</a>";
	 	echo $end_html;
		exit();
	    }
	    else
            {
               echo $init_html;
               echo $header;
               echo "<br><br><div class=alert><b>Thank you for completing Step 2 by uploading the student's transcript.</b><br><br>To print award certificates and letters, please <a class=\"small\" href=\"allstatenomhome.php?session=$session\">return to the NCPA Academic All-State Awards main menu</a>.</div><br><br>";
               echo "<a href=\"allstatenomhome.php?session=$session\">NCPA Academic All-State Awards Main Menu</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"welcome.php?session=$session\">Home</a>";
               echo $end_html;
               exit();
            }
         }
      }
      else
      {
         $uploaderror="We're sorry, but it appears you did not select a file to upload. Please try again.";
      }
   }

   //SAVE TO DB
   if($submittonsaa && $schoolid && $activitych && $activitychstuds1 && !$step)
   {
      $studentname=addslashes($showname1);
      $comments=addslashes($text1);
      if(!$nomid)
      {
         $sql="INSERT INTO allstatenom (schoolid,sport,studentid,studentname,gpa,comments,datesub) VALUES ('$schoolid','$activitych','$activitychstuds1','$studentname','$gpa1','$comments','".time()."')";
         $result=mysql_query($sql);
         $nomid=mysql_insert_id();
      }
      else
      {
         $sql="UPDATE allstatenom SET datesub='".time()."'";
	 //if($level!=1) $sql.=",opened='0',confirmed='0',released='0'";
         $sql.=" WHERE id='$nomid'";
         $result=mysql_query($sql);
      }
   } 

   if($marktranscript)
   {
      if($received=='x')
         $sql="UPDATE allstatenom SET transcript='RECEIVED',transcriptdate='".time()."',confirmed='".time()."',released='".time()."' WHERE id='$nomid' AND transcript!='RECEIVED'";
      else
         $sql="UPDATE allstatenom SET transcript='',transcriptdate=0,confirmed=0,released=0 WHERE id='$nomid'";
      $result=mysql_query($sql);

               echo $init_html;
               echo $header;
	       if($received=='x')
                  echo "<br><br><div class=alert><b>Thank you for completing Step 2 by marking the student's transcript as RECEIVED.</b></div><br><br>";
	       else
	     	  echo "<br><br><div class=alert style=\"font-size:9pt;width:400px;\"><b>The nominee's transcript has been marked as NOT RECEIVED.</b></div><br><br>";
               echo "<a href=\"allstatenom.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session&nomid=$nomid&edit=1\">Edit this Nomination</a>&nbsp;|&nbsp;<a href=\"allstatenomadmin.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session\">NCPA Academic All-State Awards Main Menu</a>";
               echo $end_html;
               exit();
   }

   //SHOW CONFIRMATION 
   echo $init_html;
   echo $header;
   echO "<br><br>";
   echo "<form method=post action=\"allstatenom.php\" enctype=\"multipart/form-data\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"sort\" value=\"$sort\">";
      echo "<input type=hidden name=\"schoolch\" value=\"$schoolch\">";
      echo "<input type=hidden name=\"activitych\" value=\"$activitych\">";
      echo "<input type=hidden name=\"confirmed\" value=\"$confirmed\">";
   echo "<input type=hidden name=\"nomid\" value=\"$nomid\">";
   echo "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"3000000\">";
   echo "<table width='500px' class=nine><tr align=left><td>";
   if(!$step)
      echo "<div class=alert><p><i>Thank you for completing Step 1 by submitting your NCPA Academic All-State Award nomination form to the NSAA!</i></p><p style=\"color:red;\"><b>Now you must complete Step 2 before the season's Award deadline.</b> <u>Paper copies for either the nomination form or transcript will not be accepted.</u></p></div><br><br>";
   //SEE IF TRANSCRIPT ALREADY UPLOADED:
   $sql="SELECT * FROM allstatenom WHERE id='$nomid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($delete)
      echo "<div class=alert style='width:400px;'>The transcript for this nominee has been deleted.</div><br>";
   if($row[transcript]!='')
   {
      echo "<b>STEP 2: TRANSCRIPT<br><br><i>";
      if($row[transcript]=="RECEIVED") echo "<div class='help' style='font-size:9pt;width:400px;'>This transcript has already been marked as RECEIVED.";
      else echo "<div class='help' style='font-size:9pt;width:400px;'>You have already submitted a transcript for this nominee";
      if(citgf_file_exists("/home/nsaahome/attachments/$row[transcript]")) 
      {
	 echo "</i><br><a class=small href=\"attachments.php?session=$session&filename=$row[transcript]\">Click to Download</a>&nbsp;|&nbsp;";
	 echo "<a href=\"allstatenom.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session&nomid=$nomid&step=2&delete=$nomid\" class=small onClick=\"return confirm('Are you sure you want to DELETE this transcript? This cannot be undone.');\">Delete</a>";
      }
      echo "</div>";
      if($level==1)
         echo "</b></i><br><br>If you wish to upload a new <b><u>digital copy</b></u> of the transcript, you may do so below. Otherwise, you can return to the Main Menu via the link at the bottom of this screen.</b>";
      else
	 echo "</b></i><br><br>If you need to submit a DIFFERENT transcript, you may do so via one of the methods below. Otherwise, you may return to the Main Menu via the link at the bottom of this screen.<br><br><b>There are TWO ways to submit a student's transcript:</b>";
      if($level==1) echo "<ul>";
      else echo "<ol>";
   }
   else if($level==1)
      echo "<b>STEP 2: TRANSCRIPT</b><br><br>You must either upload a <u>copy of the student's transcript</u> OR <u>check the box to mark the transcript as \"RECEIVED\"</u> if you do not have a digital copy to upload.<br><ul>";
   else
      echo "<b>STEP 2:</b><br><br>You must now attach <u>a copy of the student's transcript</u> to the NSAA.<br><br><ol>";
   echo "<li class=nine><b>Attach a <u>DIGITAL COPY</u> of the transcript here:</b><br><input type=file name=\"transcript\">&nbsp;<input type=submit name=\"upload\" value=\"UPLOAD TRANSCRIPT\"><br>";
   if($uploaderror!="")
      echo "<div class=error style=\"width:400px;\">$uploaderror</div><br>";
   echo "<div class=alert><p><b>PLEASE NOTE:</b> The transcript file must be <b><u>LESS THAN 3 MEGABYTES</b></u> in size.</p>";
   echo "<p>The transcript can be in the following formats: .pdf, .jpg, .png, .gif, .doc, .docx</p></div>";
   if($level!=1 && IsHeadSchool($schoolid,$activitych) && IsInCoop($schoolid,$activitych))
   {
      echo "<div class='alert'>If the nominee attends the school co-oping with your school for this activity, that school's AD will be able to login and upload the nominee's transcript now that you have submitted the nomination form. Alternatively, the AD can mail the transcript to the NSAA, as instructed below.</div>";
   }
   echo "$duedatestable</li><br>";
   echo "<li class=nine>The NSAA office will review your submitted nomination materials once the student's transcript is uploaded and Award Certificate and Congratulatory letter links will appear on the AD's NCPA Academic All-State page for successful nominees the day after each seasonal deadline.</li>";
   echo "</ol><br>";
   if($level==1)
      echo "<a href=\"allstatenomadmin.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session\">NCPA Academic All-State Nominations Main Menu</a>";
   else
      echo "<a href=\"allstatenomhome.php?session=$session\">NCPA Academic All-State Awards Main Menu</a>";
   echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"welcome.php?session=$session\">Home</a>";
   echo "</td></tr></table>";
   echo "</form>";
   echo $end_html;
   exit();
}

//if print!=1
echo $init_html;
echo $header;
?>
<script language="javascript">
function ErrorCheck()
{
   var errors="";
   if(document.getElementById('gpa1').value<3.70 || (document.getElementById('gpa1').value>10 && document.getElementById('gpa1').value<92.5)) 
   { 
      warning1.style.display='block'; warning1.style.visibility='visible'; 
   }
   else
   {
      document.getElementById('hiddensave').value="PREVIEW";
      document.forms.allstatenomform.submit();
   }
}
</script>
<?php
echo "<br><form method=post name=\"allstatenomform\" action=\"allstatenom.php\" name=\"allstateform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=sort value=\"$sort\">";
echo "<input type=hidden name=\"activitych\" value=\"$activitych\">";
      echo "<input type=hidden name=\"schoolch\" value=\"$schoolch\">";
      echo "<input type=hidden name=\"confirmed\" value=\"$confirmed\">";
echo "<input type=hidden name=\"nomid\" value=\"$nomid\">";
if($row[opened]>0)	//If this is a returned nomination form, skip the instructions
{
   echo "<table width=700 class=nine><caption><b><u>NCPA ACADEMIC ALL-STATE AWARDS NOMINATION FORM</b></u><br><br><div class='alert' style='font-size:9pt;'>This nomination form was returned by the NSAA Office on ".date("m/d/y",$row[opened]).". Please make the necessary changes to the information below and re-submit it to the NSAA.</div><br></caption>";
}
else
{
   echo "<table width=700 class=nine><caption><b><u>NCPA ACADEMIC ALL-STATE AWARDS NOMINATION PROCESS</b></u><br><br><font style=\"color:red\"><b><u>THIS INFORMATION HAS CHANGED SO PLEASE READ CAREFULLY.</b></u></font><br><br>";
   if($error!='')
      echO "<div class=error>You have some errors in your nomination form.  Please read the error message in red below and correct your errors.  Then click \"View Form\" again.</div><br><br>";
   echo "<table width=90%><tr align=center><td><div class='normalwhite' style='font-size:9pt;padding:10px;'><font style=\"color:red\"><b>INSTRUCTIONS:</b> <u>Paper student transcripts</u> are no longer accepted for this award. Transcripts must be uploaded with each student's nomination form as Step 2 of the process. All information must be submitted on the NSAA website electronically. NOTHING SHOULD BE MAILED OR FAXED TO THE NSAA OFFICE.
   </font><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<b>To nominate your school's students, complete the following steps for EACH NSAA ACTIVITY:</b>";
   echo "<ol class=bigger><li>Select the student(s) you wish to nominate from the eligibility list window for the appropriate season and activities program.  Complete all text fields.  Click to View the completed form and either make changes or <u>submit to the NSAA office</u> to complete the first step. </li>";
   echo "<li>Upload a copy of <u>each student's transcript by the season's deadline</u> to complete the second step of the nomination process.</li>";
   echo "<li>After the NSAA office receives both the electronic nomination form and qualifying transcript, they will approve and release the award certificate and Executive Director's letter of congratulations links so they can be printed by each student's school and presented.</li>";
   echo "</ol></div></td></tr>";
   echO "<tr align=left><td><b>REQUIREMENTS:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "A nominated student must be a <b>varsity player</b> or <b>organizational leader</b> who has played a significant role on the team or in the organizational activity during the seasons for which nominations are accepted.  A nominated student must have a minimum <b><u>cumulative</b></u> Grade Point Average, in all curricular subjects, of <b>93% on a 100 point scale</b> or the equivalent, (3.7-4.0) or A-, etc.).<br><br>";
   echo "NSAA high schools or their cooperative sponsorship may nominate a maximum of two students per NSAA activity program.  Students who meet these requirements and are nominated by their school administration shall be awarded the NCPA Academic All-State Award for that activity.</td></tr>";
   echo "<tr align=center><td><br>$duedatestable";
   echo "</td></tr></table><hr>";
   if($error!='')
   {
      echo "<div class=error>$error</div>";
   }
   echo "</caption>";
}
echo "<tr align=left><td><b>Activity:</b>&nbsp;&nbsp;".GetActivityName($activitych)."</td></tr>";
echo "<tr align=left><td><b>School:</b>&nbsp;&nbsp;$school</td></tr>";
echo "<tr align=left><td><br><b><u>Student Nominee:</u></b></td></tr>";
if($activitych=="go_b")	//ALLOW BOY OR GIRL TO BE CHOSEN
   $string=GetPlayers($activitych,$school,$fallyear,FALSE,TRUE);
else
   $string=GetPlayers($activitych,$school,$fallyear);
echo "<tr align=left><td><b>Name:</b>&nbsp;&nbsp;<select name=\"activitychstuds1\" id=\"activitychstuds1\"><option value=''>~</option>";
if($activitych!='')
{
   if(!ereg("Please",$string))
   {
      $results=split("<result>",$string);
      for($i=0;$i<count($results);$i++)
      {
         $details=split("<detail>",$results[$i]);
 	 echo "<option value=\"$details[0]\"";
	 if($activitychstuds1==$details[0]) echo " selected";
	 echo ">$details[1]</option>";
      }
   }
}
echo "</select>";
if($activitych=="go_b")
   echo "  <i>Girls golfers are showing in this list in case your school only offers a boys program, and you have girls that participate with the boys.</i><br>";
echo "<div id=\"activitychstuds1instructions\" class=\"plain\"";
if($activitych=="")
{
   echo " style=\"display:'';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style=\"color:red\">Please select an activity above in order to populate this list with the students on that activity's Eligibility List. A nominated student must be included on your school's student eligibility lists for each activity in which they participate. For assistance with adding students to the appropriate eligibility lists call the NSAA Office (402)489 0386.</font></div>";
}
else
   echo " style=\"display:none;\"></div>";
$showname1=ereg_replace("\"","'",$showname1);
echo "<br><br>If you would like to have the student's name appear differently on the certificate than shown above,<br>please list the <b><u>preferred <label style='background-color:yellow;'>first and last name</label></u></b> here:&nbsp;</b><input type=text class=tiny size=30 name=\"showname1\" value=\"$showname1\"><br><br>";
echo "<b>Cumulative High School GPA:&nbsp;</b><input type=text class=tiny size=6 name=\"gpa1\" id=\"gpa1\" value=\"$gpa1\">&nbsp;";
echo "<i>This GPA must match the one shown on the student's transcript submitted with this form.</i>";
echo "</td></tr>";
echo "<tr align=center><td><div id=warning1 class='errbox' style='display:none;'><div class=error style=\"width:95%;\">ERROR:</div><p>The student's GPA must be roundable <u>once</u> to 93% (92.50 or higher) on a 100% scale or <u>at or above</u> 3.70 on a 4.0 scale.</p><img src='/okbutton.png' onclick=\"document.getElementById('warning1').style.visibility='hidden';document.getElementById('warning1').style.display='none';\"></div></td></tr>";
echo "<tr align=left><td><b>Student's contributions to this activity:</b> Briefly describe why this student is being nominated.<br>";
echo "<textarea rows=10 cols=80 name=\"text1\">$text1</textarea></td></tr>";
echo "<tr align=center><td><br><div class=alert style='text-align:center;'><p style='font-size:9pt;text-align:left;'>Click \"PREVIEW NOMINATION FORM\" below to check that the information you are submitting is complete and accurate. Then you will be able to make any necessary changes and submit the form to the NSAA.</p>";
echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\"><input type=button onClick=\"ErrorCheck();\" name=\"viewform\" value=\"PREVIEW NOMINATION FORM\"></div></td></tr>";
echo "</table>";
?>
<div id="loading" style="display:none;"></div></form>
<?php
echo $end_html;
?>
