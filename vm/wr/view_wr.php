<?php
//view_wr.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_wr.php
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}


//get school user chose (Level 1) or belongs to (Level 2, 3)
if((!$school_ch || $level==2 || $level==3) && $director!=1)
{
   $school=GetSchool($session);
}
else if($level==1)
{
   $school=$school_ch;
}
else if($director==1)
{
   $print=1;
   $school=$school_ch;
   $hostsch=GetSchool($session);
   $hostsch2=addslashes($hostsch);
   $sql="SELECT id FROM logins WHERE school='$hostsch2' AND level='$level'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[0];
   $sql="SELECT * FROM $db_name2.wrdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=ereg_replace("\'","\'",$school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="wr";
if(!IsHeadSchool($schoolid,$sport) && !GetCoopHeadSchool($schoolid,$sport) && $school!="Test's School") //NOT a $sport school at all
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><br><div class='alert' style='width:400px;'><b>$school</b> is not listed as a ".GetActivityName($sport)." school.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}
else if(!IsHeadSchool($schoolid,$sport) && $school!="Test's School")    //in a Co-op, not the head school
{
   echo $init_html;
   echo GetHeader($session);
   $mainsch=GetCoopHeadSchool($schoolid,$sport);
   echo "<br><br><br><div class='alert' style='width:400px'><b>$school</b> is in a co-op with <b>$mainsch</b> for ".GetActivityName($sport).".<br><br>Only the head school of the co-op can fill out this entry form.  <b>$mainsch</b> is listed as the head school for this co-op.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}

//check if this is state form or district form
$due_date=GetDueDate("wr");
/***No state form for Wrestling 10/15/2003
if(PastDue($due_date,9))       //state form
{
   $state=1;
   $table="wr_state";
   $form_type="STATE";
}
else
{
***/
   $state=0;
   $table="wr";
   $form_type="DISTRICT";
//}

//get class/dist submitted for this team
$sql="SELECT t1.class_dist FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($class_dist=="" || !$class_dist)
   {
      $class_dist=$row[0];
   }
}


//check if this form has already been submitted:
$sql="SELECT * FROM $table WHERE (school='$school2' OR co_op='$school2') AND checked='y'";
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_wr.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:
  $string=$init_html;
  $csv="";
  echo $init_html;

if($print!=1)
{
   $header=GetHeader($session);
   echo $header;
   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Wrestling\">Return to Home-->Wresting Entry Forms</a><br>";
}
else
   echo "<table width=100%><tr align=center><td>";

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$colors=$row2[5];
$mascot=$row2[6];
$sql2="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Wrestling'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0]; $asst=$row2[1];

if($print!=1)
{
echo "<br><a href=\"view_wr.php?session=$session&school_ch=$school_ch&print=1\" target=new class=small>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"edit_wr.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
}
$info="<table>";
$info.="<tr align=center>";
$info.="<th>WRESTLING $form_type ENTRY</th>";
$info.="</tr>";
if($state!=1)
{
   $info.="<tr align=center>";
   $info.="<td><b>Due $due_date</b><br><br></td></tr>";
}
$info.="<tr align=left><td>";
$info.="<table cellspacing=2 cellpadding=2><!--Show school, coach, etc.-->";
$info.="<tr align=left><th>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'wr');
$sql="SELECT * FROM wrschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$info.="<td>".GetSchoolName($sid,'wr')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'wr')." $mascot\r\n";
$info.="<tr align=left><th>School Colors:</th>";
$info.="<td>$colors</td></tr>";
$info.="<tr align=left><th>NSAA-Certified Coach:</th>";
$info.="<td>$coach</td></tr>";
$info.="<tr align=left><th>Assistant Coaches:</th>";
$info.="<td>$asst</td></tr>";
$info.="<tr align=left><th>Class:</th>";
$info.="<td>$class_dist</td></tr>";
$info.="</table>";
$csv.="School Colors:,$colors\r\n";
$csv.="NSAA-Certified Coach:,$coach\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$csv.="Class:,$class_dist\r\n";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table border=1 cellpadding=3 cellspacing=2 bordercolor=#000000>";
$info.="<tr align=center>";
$info.="<th class=smaller>Weight<br>Class</th>";
$info.="<th class=smaller>Name</th><th class=smaller>Grade</th>";
$info.="<th class=smaller>Record</th></tr>";

$csv.="Weight Class, Name,Grade,Record\r\n";

$sql="SELECT t1.*,t2.last,t2.first,t2.semesters,t2.eligible FROM $table AS t1, eligibility AS t2 WHERE (t1.school='$school2' OR t1.co_op='$school2') AND t2.id=t1.student_id ORDER BY weight";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
  if($row[checked]=="y")	//that student was checked to be on the roster
  {
     $info.="<tr align=left>";
     $last=$row[last];
     $first=$row[first];
     $info.="<td>$row[weight]</td>";
     $info.="<td>$first $last</td>";
     $year=GetYear($row[semesters]);
     $info.="<td>$year</td>";
     $info.="<td>$row[record]</td>";
     $info.="</tr>";
     $csv.="$row[weight],$first $last,$year,$row[record]\r\n";
  }
}

$info.="</table></td></tr>";
echo $info;
$string.=$info;

/**Wrestling does not have a state form as of 10/15/2003
//ADD SEASON GAMES AND SCORES TO CSV FILE (state form only)
if($state==1)
{
   $sql="SELECT sid FROM wrschool WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sch_id=$row[0];
   $year=date(Y);
   $cur_season="$year-08-01";
   $csv.="\r\nGames:\r\nOpponent,Score,Opp.Score\r\n";
   $sql="SELECT t1.* FROM wrscore AS t1, wrsched AS t3 WHERE (t1.sid='$sch_id' OR t1.oppid='$sch_id') AND t1.scoreid=t3.scoreid AND t3.received>'$cur_season' ORDER BY t3.received";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      //assume school is the home school
      $sql2="SELECT school FROM wrschool WHERE sid='$row[1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2); //opp name
      if($row2[0]==$school)  //school is the away school
      {
         $sql2="SELECT school FROM wrschool WHERE sid='$row[0]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2); //opp name
         $csv.="$row2[0],$row[4],$row[3]\r\n";
      }
      else $csv.="$row2[0],$row[3],$row[4]\r\n";
   }

   //Add table for history information
   $csv.="\r\nHistory:\r\n";
   $csv.="NSAA Enrollment:,\r\n";
   $csv.="Number of Times in Playoffs:,\r\n";
   $csv.="Most Recent State Tournament Appearance:,\r\n";
   $csv.="State Championship Years:,\r\n";
   $csv.="State Runner-Up Years:,\r\n";
}//end if state=1
******/

if($print!=1)
{
?>
<tr align=center>
<td><br>
    <a href="view_wr.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new class=small>Printer-Friendly Version</a>
    &nbsp;&nbsp;&nbsp;
    <a href="edit_wr.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
    &nbsp;&nbsp;&nbsp;
    <a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}//end if print!=1
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Wrestling";
   $activ_lower=strtolower($activ);

   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   /**Wrestling does not have a state form 10/15/03
   if($state==1)
      $filename.="state";
   ***/
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");
if($print==1)
{
?>
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php" name=emailform>
<!--<input type=hidden name=state value=<?php echo $state; ?>>-->
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<table>
<tr align=center><td colspan=2><b>E-MAIL THIS FORM:</b><br>PLEASE NOTE: Your district director will automatically receive these forms once the due date has passed. You do NOT need to email this form to the district director.</td></tr>
<tr align=left><th>
Your e-mail address:</th>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><th>
Recipient(s)' address(es):</th>
<td>
<textarea name=email class=email cols=50 rows=5><?php echo $recipients; ?></textarea>
<?php
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
?>
</td>
</tr>
<tr align=center><td colspan=2>
<input type=submit name=submit value="Send">
</td></tr>
</table>
<font style="font-size:8pt">
<?php echo $email_note; ?>
</font>
</form>
</th></tr>
<?php
}  //end if print=1
if($send=='y')  //if box checekd at bottom of edit screen, send to NSAA
{
   $From=GetEmail("main");
   $FromName=$stateassn;
   $To=GetEmail("wr");
   $ToName=GetName("wr");
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school'
s $activ State Tournament Roster information.<br><br>They have approved this as
their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
}
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
