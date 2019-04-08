<?php
//view_go_g.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_go_g.php
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

$level=GetLevel($session);

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
   $sql="SELECT * FROM $db_name2.go_gdistricts WHERE hostid='$hostid'";
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
$schoolid=$row[id]; $sport="go_g";
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

//get class_dist submitted by this team
$sql="SELECT class_dist FROM go_g WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//get due date for girls golf form:
$sql="SELECT duedate FROM form_duedates WHERE form='go_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//check if this form has already been submitted:
//$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM go_g AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND ((t1.school='$school2' AND t2.school='$school2') OR (t1.co_op='$school2' AND t2.school=t1.school)) AND t1.checked='y' ORDER BY t2.last";
$sql="SELECT t1.*,t2.last,t2.first,t2.middle,t2.semesters FROM go_g AS t1,eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY t2.last";
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_go_g.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:
  $csv="";
  $string=$init_html;
  echo $init_html;

if($print!=1)
{
   $header=GetHeader($session);
   echo $header;

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Golf\">Return to Home-->Golf Entry Forms</a><br>";
}

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$colors=$row2[5];
$mascot=$row2[6];
$sql2="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Golf'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0]; $asst=$row2[1];

if($print!=1)
{
   echo "<br><a class=small href=\"view_go_g.php?session=$session&print=1&school_ch=$school_ch\" target=_\"blank\">Printer-Friendly Version</a>";
   echo "&nbsp;&nbsp;&nbsp;<a class=small href=\"edit_go_g.php?school_ch=$school_ch&session=$session\">Edit this Form</a>";
}
$info.="<form method=post action=\"edit_go_g.php\">";
$info.="<input type=hidden name=session value=$session>";
$info.="<input type=hidden name=school_ch value=\"$school_ch\">";
$info.="<table>";
$info.="<tr align=center>";
$info.="<th>GIRLS GOLF DISTRICT ENTRY</th>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<td><b>Due $duedate2</b><br><br></td></tr>";
$info.="<tr align=left><td>";
$info.="<table cellspacing=2 cellpadding=2><!--Show school, coach, etc.-->";
$info.="<tr align=left><th>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'go_g');
$sql2="SELECT * FROM go_gschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($row2[mascot]!='') $mascot=$row2[mascot];
if($row2[colors]!='') $colors=$row2[colors];
if($row2[coach]!='') $coach=$row2[coach];
$info.="<td>".GetSchoolName($sid,'go_g')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'go_g')." $mascot\r\n";
$info.="<tr align=left><th>School Colors:</th>";
$info.="<td>$colors</td></tr>";
$info.="<tr align=left><th>$stateassn-Certified Coach:</th>";
$info.="<td>$coach</td></tr>";
$info.="<tr align=left><th>Assistant Coaches:</th>";
$info.="<td>$asst</td></tr>";
$info.="<tr align=left><th>Class:</th>";
$info.="<td>".GetClass($sid,'go_g')."</td></tr></table>";
$csv.="School Colors:,$colors\r\n";
$csv.="$stateassn-Certified Coach:,$coach\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$csv.="Class:,$class_dist\r\n";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table border=1 cellpadding=5 cellspacing=2 bordercolor=#000000>";
$info.="<tr align=center>";
$info.="<th>Name</th><th>Grade</th><th>Average<br>Round</th></tr>";

$csv.="Name, Grade, Average Round\r\n";

$count=0; $error=0;
while($row=mysql_fetch_array($result))
{
  if($row[3]=="y")	//that student was checked to be on the roster
  {
     $info.= "<tr align=left>";
     $info.="<td>$row[7], $row[8] $row[9]";
     $info.="</td>";
     $year=GetYear($row[10]);
     $info.="<td>$year</td>";
     $info.="<td";
     if(trim($row[4])=="") { $info.=" bgcolor=red"; $error=1; }
     $info.=">$row[4]</td>";
     $info.="</tr>";
     $csv.="$row[7] $row[8] $row[9],$year,$row[4]\r\n";
     $count++;
  }
}
$info.="</table>";
if($error==1)
{
   $info=ereg_replace("<table border=1 cellpadding=5 cellspacing=2 bordercolor=#000000>","<table border=1 cellpadding=5 cellspacing=2 bordercolor=#000000><caption><div class=error>ERROR:  You have not entered an AVERAGE for all of your players (see below).  You MUST enter an average for each player before this form is complete.</div></caption>",$info);
}
$info.="</td></tr>";
echo $info;
$string.=$info;

if($print!=1)
{
   if($count>5)
   {
      echo "<tr align=left><th><font color=red>You have entered too many students!<br>";
      echo "Please make sure you have only checked 5 students by the due date";
      echo " of this form</th></tr>";
   }
?>
<tr align=center>
<td><br>
<?php
echo "<a class=small target=new href=\"view_go_g.php?session=$session&school_ch=$school_ch&print=1\">Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"edit_go_g.php?session=$session&school_ch=$school_ch\">Edit this Form</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"../welcome.php?session=$session\">Home</a>";
?>
</td>
</tr>
<?php
}//end if print!=1
else    //print=1 (Printer-Friendly View)
{
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Girls Golf";
   $activ_lower=strtolower($activ);

   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");
?>
</form>
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php" name=emailform>
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
</th></tr>
<?php
}  //end if print=1
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
