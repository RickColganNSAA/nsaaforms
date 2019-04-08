<?php
//view_jo.php: view play production form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidUser($session))
{
   header("Location:../index.php");
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
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="jo";
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

//check if this is state or district form
$duedate=GetDueDate("jo");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
$state=1;
$table="jo";
$form_type="State";

$edit="yes";
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY t2.last";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $edit=="yes")
{
   header("Location:edit_jo.php?session=$session&school_ch=$school_ch");
   exit();
}

echo $init_html;
$string=$init_html;
$csv="";
if($print!=1) 
{
   echo GetHeader($session);
   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Journalism\">Return to Home-->Journalism Entry Forms</a><br>";
   echo "<table><tr align=center><td align=center>";
   if($send=='y')
   {
      echo "<font style=\"color:red\"><b>Your form has been submitted to the NSAA.</b></font><br>";
   }
}
else
   echo "<table><tr align=center><td align=center>";

//Get info already submitted for this school
//class/dist
$sql="SELECT t1.class_dist FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//get mascot and colors
$sql="SELECT mascot,color_names FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[0]; $colors=$row[1];

//get coach
$sql="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND level='3' AND (sport='Newspaper' OR sport='Yearbook')";
$result=mysql_query($sql);
$coach="";
while($row=mysql_fetch_array($result))
{
   if(!ereg($row[0],$coach))
      $coach.="$row[0], ";
   $asst=$row[1];
}
$coach=substr($coach,0,strlen($coach)-2);

  //students entered already
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY t2.last"; 
$result=mysql_query($sql);

$string.="<center><br>";
echo "<center>";

if($print!=1)	//non printer friendly
{
?>
<br>
<a href="view_jo.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" class=small target=new>Printer-Friendly Version</a>
&nbsp;&nbsp;&nbsp;
<?php
if($edit=="yes")
{
?>
<a href="edit_jo.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
<?php } ?>
<br><br>

<?php
} //end if print!=1
$info="<h1>$form_type Journalism Contest Entry Form:</h1>";

$info.="<table><!--School Info-->";
//School/Team Information:
$sid=GetSID2($school,$sport);
$coach=GetCoaches($schoolid,$sport);
$asst=GetAsstCoaches($schoolid,$sport);
$directorcell=GetCoachCell($schoolid,$sport);
$mascot=GetMascot($schoolid,$sport);
$colors=GetColors($schoolid,$sport);
$class=GetClass($sid,$sport);
$info.="<table cellspacing=0 cellpadding=5 frame=\"all\" rules=\"all\" class=\"nine\" style=\"border;#808080 1px solid;\"><caption style=\"text-align:left;\">";
$info.="<p><b>School/Mascot:</b> ".GetSchoolName($sid,$sport)." $mascot</p>";;
$csv.="School/Mascot:,".GetSchoolName($sid,$sport)." $mascot\r\n";
$info.="<p><b>Colors:</b> $colors</p><p><b>Class:</b> $class</p>";
$info.="<p><b>Director:</b> $coach</p>";
$info.="<p><b>Director Cell Phone:</b> $directorcell</p>";
$info.="<p><b>Assistant(s):</b> $asst</p>";
$csv.="School Colors:,$colors\r\n";
$csv.="Class:,$class\r\n";
$csv.="Director:,$coach,$directorcell\r\n";
$csv.="Assistants:,\"$asst\"\r\n";
$info.="</caption><!--End Play & School Info-->";
$info.="<tr align=center>";
$info.="<th>Name (Grade)</th>";
$info.="<th colspan=2>Event(s)</th>";
$info.="</tr>";
$csv.="\r\nName Grade,Event(s)\r\n";

   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td align=left>".GetStudentInfo($row[student_id])."</td>";
      if(ereg("News/Feature Photography",$row[4]))
      {
	 $temp=split(",",$row[4]);
	 $event1=$temp[0];
	 $phototype1=$temp[1];
	 if($phototype1=="film")
	    $phototype1="(Film)";
	 else if($phototype1=="digital")
	    $phototype1="(Digital: $temp[2])";
	 else 
	    $phototype1="<font style=\"color:red\"><b>(Please go back and select Film or Digital!)</b></font>";
	 $info.="<td>$event1 $phototype1";
	 $row[4]="$event1 $phototype1";
      }
      else $info.="<td>$row[4]";
      $info.="&nbsp;</td><td>";
      if(ereg("News/Feature Photography",$row[5]))
      {
	 $temp=split(",",$row[5]);
	 $event2=$temp[0];
	 $phototype2=$temp[1];
	 if($phototype2=="film")
	    $phototype2="(Film)";
	 else
	    $phototype2="(Digital: $temp[2])";
	 $info.="$event2 $phototype2&nbsp;</td>";
	 $row[5]="$event2 $phototype2";
      }
      else
         $info.="$row[5]&nbsp;</td>";
      $info.="</tr>";
      $csv.=GetStudentInfo($row[student_id]).",$row[4],$row[5]\r\n";
   }
   
$info.="</table><!--End Students-->";

echo $info;
$string.=$info;

if($print!=1)	//non-printer friendly
{
?>
<br>
<p><a href="view_jo.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" class=small target=new>Printer-Friendly Version</a>
&nbsp;&nbsp;&nbsp;
<?php
if($edit=="yes")
{
?>
<a href="edit_jo.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
&nbsp;&nbsp;&nbsp;
<?php } ?>
<a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</p>
<?php
}//end if print!=1
   //Allow user to e-mail form
   $string.=$end_html;
   $activ="Journalism";
   $activ_lower=strtolower($activ);
   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   if($state==1) 
	$filename.="state";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");

if($print==1)	//show form for user to e-mail files
{
?>
<table>
<tr align=center><th><br>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<input type=hidden name=state value="<?php echo $state; ?>">
<table>
<tr align=left><th>
Your e-mail address:</th>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><th>
Recipient(s)' address(es):</th>
<td>
<textarea name=email cols=50 rows=5 class=email><?php echo $recipients; ?></textarea>
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
</th></tr></table>
<?php
}  //end if print=1
if($send=='y')	//send state file to state assn
{
   $From=GetEmail("main");
   $FromName=$stateassn;
   $To=GetEmail("jo");
   $ToName=GetName("jo");
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school's $activ State Tournament Roster Information.<br><br>They have approved this as their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
}
echo $end_html;
?>
