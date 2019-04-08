<?php
//view_cc_b.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_cc_b.php
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$string="";	//string will be written to html file to e-mail to dist dir
$csv="";

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
   $sql="SELECT * FROM $db_name2.ccbdistricts WHERE hostid='$hostid'";
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
$schoolid=$row[id]; $sport="ccb";
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
//check if it is day of districts or day after and if user is dist director
$sql="SELECT * FROM $db_name2.ccbdistricts WHERE hostschool='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)	//user is district host
{
   $row=mysql_fetch_array($result);
   $today=time();
   $date=split("-",$row[dates]);
   $mo=$date[0];
   $day=$date[1];
   $day--;	//unlock form day before meet
   $year=$date[2];
   $dist_date=mktime(0,0,0,$mo,$day,$year);
   $days=4;
   if(GetLevel($session)==1)    //State Assn
      $days=10;
   $diff=$days*24*60*60;
   $lock_date=$dist_date+$diff;
   if($today>=$dist_date && $today<=$lock_date)
   {
      header("Location:state_cc_b_view.php?session=$session&school_ch=$school_ch");
      exit();
   }
}

//get class_dist for this team, if submitted
$sql="SELECT class_dist FROM cc_b WHERE school='$school2'";
$result=mysql_query($sql);
$class_dist="";
while($row=mysql_fetch_array($result))
{
   if($class_dist=="") $class_dist=$row[0];
}

//get due date for boys cross-country form:
$sql="SELECT duedate FROM form_duedates WHERE form='cc_b'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//check if this form has already been submitted:
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM cc_b AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND ((t1.school='$school2' AND t2.school='$school2') OR (t1.co_op='$school2' AND t2.school=t1.school)) AND t1.checked='y' ORDER BY t2.last";
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_cc_b.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:

$string.=$init_html;
echo $init_html;

if($print!=1)
{
   $header=GetHeader($session);
   echo $header;

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Cross-Country\">Return to Home-->Cross-Country Entry Forms</a><br>";
}

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$colors=$row2[5];
$mascot=$row2[6];
$sql2="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Boys Cross-Country'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0]; $asst=$row2[1];

if($print!=1)
{
   echo "<br><br>";
   echo "<a class=small href=\"view_cc_b.php?session=$session&school_ch=$school_ch&print=1\" target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"edit_cc_b.php?session=$session&school_ch=$school_ch\">Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   if($level==1 || $level==2)
      echo "<a class=small href=\"view_cc_g.php?session=$session&school_ch=$school_ch\">Go to GIRLS District Entry Form</a>";
   echo "<br><br>";
}

$string.="<table>";
$string.="<tr align=center>";
$string.="<th>BOYS CROSS-COUNTRY DISTRICT ENTRY</th>";
$string.="</tr>";
$string.="<tr align=center>";
$string.="<td><b>Due $duedate2</b><br><br></td>";
$string.="</tr>";
$string.="<tr align=left><td>";
$string.="<table cellspacing=2 cellpadding=2><!--Show school name, coach, etc.-->";
$string.="<tr align=left><th align=left>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'ccb');
$sql2="SELECT * FROM ccbschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($row2[mascot]!='') $mascot=$row2[mascot];
if($row2[colors]!='') $colors=$row2[colors];
if($row2[coach]!='') $coach=$row2[coach];
$string.="<td>".GetSchoolName($sid,'ccb')." $mascot</td></tr>";
$csv.="School/Mascot:, ".GetSchoolName($sid,'ccb')." $mascot\r\n";
$string.="<tr align=left><th align=left>School Colors:</th>";
$string.="<td>$colors</td></tr>";
$string.="<tr align=left><th align=left>Coach:</th>";
$string.="<td>$coach</td></tr>";
$string.="<tr align=left><th align=left>Assistant Coaches:</th>";
$string.="<td>$asst</td></tr>";
$string.="<tr align=left><th align=left>Class:</th>";
$string.="<td>$class_dist</td></tr>";
$csv.="School Colors:,$colors\r\n";
$csv.="Coach:,$coach\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$csv.="Class:,$class_dist\r\n";
$string.="</table>";
$string.="</td></tr>";
$string.="<tr align=center>";
$string.="<td><br>";
$string.="<table cellpadding=5 cellspacing=0 frame=all rules=all style=\"border:#808080 1px solid;\">";
$string.="<tr align=center>";
$string.="<th align=left>Name</th><th align=left>Grade</th></tr>";

$csv.="Name, Grade\r\n";

$count=0;
while($row=mysql_fetch_array($result))
{
  if($row[3]=="y")	//that student was checked to be on the roster
  {
     $string.="<tr align=left>";
     $string.="<td>$row[6], $row[7] $row[8]";
     $string.="</td>";
     $year=GetYear($row[9]);
     $string.="<td>$year</td>";
     $string.="</tr>";
     $csv.="$row[7] $row[6],$year\r\n";
     $count++;
  }
}

$string.="</table></td></tr>";
echo $string;

if($print!=1)
{
   if(($count>6 && $class_dist!="A")||($count>7))
   {
      echo "<tr align=left><th align=left><font color=red>You have entered too many students!<br>";
      echo "Please make sure you have only ";
      if($class_dist=="A") echo "7";
      else echo "6";
      echo " students checked by the day this form due.</b>";
      if($count==7 && ($class_dist=='' || !$class_dist))
         echo "<br>If you are a Class A school, make sure to select \"A\" as your class on the <a href=\"edit_cc_b.php?session=$session\">Edit Screen</a>";
      echo "</font>";
      echo "</th></tr>";
   }
?>
<tr align=center>
<td><br>
    <a class=small href="view_cc_b.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;
    <a class=small href="edit_cc_b.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Edit this Form</a>&nbsp;&nbsp;&nbsp;
    <a class=small href="../welcome.php?session=<?php echo $session; ?>">Home</a>
</td>
</tr>
<?php
}//end if print!=1
else    //print=1 (Printer-Friendly View)
{
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Boys Cross Country";
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
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<table>
<tr align=center><td colspan=2><b>E-MAIL THIS FORM:</b><br>PLEASE NOTE: Your district director will automatically receive these forms once the due date has passed. You do NOT need to email this form to the district director.</td></tr>
<tr align=left><th align=left>
Your e-mail address:</th>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><th align=left>
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
<font style="font-size:8pt;"><?php echo $email_note; ?></font>
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
