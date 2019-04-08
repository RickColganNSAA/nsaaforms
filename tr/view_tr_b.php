<?php
//view_tr_b.php: Track entry form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

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
   $sql="SELECT * FROM $db_name2.trbdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=addslashes($school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="trb";
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

//get name of coach from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Boys Track & Field'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];

//get mascot and colors from headers table
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$colors=$row[5];
$mascot=$row[6];

//get due date from db
$sql="SELECT duedate FROM form_duedates WHERE form='tr_b'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

/*
//Check if State Form should show up now (Dist Form is 10 days past due date)
if(PastDue($duedate,9))
{
   $form_type="STATE";
   $state=1;
   $table="bb_bstate";
}
else    //district form
{
*/
   $state=0;
   $form_type="DISTRICT";
   $table="tr_b";
//}

//Re-direct to edit page if no students entered yet:
$sql="SELECT t1.* FROM $table AS t1 WHERE (t1.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_tr_b.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}

if($alert!='')
{
?>
<script language="javascript">
window.alert('<?php echo $alert; ?>');
</script>
<?php
}

echo $init_html;
if($print!=1) 
{
   echo $header;
   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Track\">Return to Home-->Track & Field Entry Forms</a><br>";
}
$string=$init_html;

//get class from tr table
$sql="SELECT t1.class_dist FROM $table AS t1 WHERE t1.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row[0];

$string.="<br>";
if($print!=1)	//show links if non-printer friendly
{
   echo "<br><a href=\"edit_tr_b.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   if($level==1 || $level==2)
      echo "<a href=\"view_tr_g.php?session=$session&school_ch=$school_ch\" class=small>Go to GIRLS $form_type Entry Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_tr_b.php?session=$session&school_ch=$school_ch&print=1\" class=small target=new>Printer-Friendly Version</a>";
   echo "<br><br>";
}

$info="";
$csv="";
$info.="<table cellspacing=4 cellpadding=4><!--Table of Tables-->";
$info.="<caption><b>BOYS TRACK & FIELD $form_type ENTRY FORM <br>";
if($state!=1) $info.= "Due $duedate2";
$info.="</b></caption>";
$info.="<tr align=left><td colspan=2><table><!--Team Info-->";
$info.="<tr align=left><th align=left>School/Mascot:</th>";

//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'tr');
$sql="SELECT * FROM trschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];

$info.="<td>".GetSchoolName($sid,'tr')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'tr')." $mascot\r\n";
$info.="<tr align=left><th align=left>School Colors:</th><td>$colors</td></tr>";
$info.="<tr align=left><th align=left>$stateassn-Certified Coach:</th><td>$coach</td></tr>";
$info.="<tr align=left><th align=left>Assistant Coach(es):</th><td>$asst</td></tr>";
$info.="<tr align=left><th align=left>Class:</th>";
$info.="<td>$class</td></tr>";
$csv.="School Colors:,$colors\r\n";
$csv.="$stateassn-Certified Coach:,$coach\r\n";
$csv.="Assistant Coach(es):,\"$asst\"\r\n";
$csv.="Class:,$class\r\n";
$string.=$info;
echo $info;
$info="";
if($print!=1)
{
   echo "<tr align=left><td colspan=2>";
   echo "<a href=\"teamlist_b.php?school_ch=$school_ch&session=$session&print=1\" target=new>Click Here for your Team's District Roster</a><br>";
   echo "<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your list of eligible team members (i.e., your district roster) will be automatically attached when you e-mail this form to your district director.</i></td></tr>";
}
$info.="</table></td></tr>";
$info.="<tr><td colspan=2><hr></td></tr>";

//get players already submitted from db table

$colheaders="
   <tr align=center>
   <th class=smaller>Name</th>
   <th class=smaller>Grade</th>
   <th class=smaller>Best<br>Performance</th>
   </tr>
";

for($x=0;$x<count($trevents);$x++)
{
   if($x%2==0) $info.="<tr align=center valign=top>";
   $info.="<td>
   <table width=300 cellspacing=1 cellpadding=2 border=1 bordercolor=#000000>";
   $info.="<caption align=left><b>$treventslong[$x]:</b></caption>";
   $info.=$colheaders;
   $csv.="\r\n$treventslong[$x]:\r\nName,Grade,Best Performance\r\n";
   $sql=GetEventSql($treventslong[$x],$table,$school2);
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td align=left>$row[13], $row[14] $row[15]</td>";
      $year=GetYear($row[16]);
      $info.="<td>$year</td>";
      //get performance
      $perf=GetPerf($treventslong[$x],$row);
      $info.="<td>$perf</td></tr>";
      if(preg_match("/-/",$perf))
      {
	 $perf=preg_replace("/-/","'",$perf);
	 $perf.="\"";
      }
      $csv.="$row[14] $row[13],$year,$perf\r\n";
   }
   $info.="</table></td>";
   if(($x+1)%2==0) $info.="</tr>";
}

echo $info;
$string.=$info;

if($print!=1)
{
   echo "</table><!--End Table of Tables-->";
   echo "<a href=\"edit_tr_b.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_tr_b.php?session=$session&school_ch=$school_ch&print=1\" class=small target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"../welcome.php?session=$session\" class=small>Home</a>";
}

//Allow user to e-mail form
$string.="</table></td></tr></table></body></html>";
$activ="Boys Track & Field";
$activ_lower=strtolower($activ);

$sch=preg_replace("/[^0-9a-zA-Z]/","",$school);
$sch=strtolower($sch);
$activ_lower=preg_replace("/[^0-9a-zA-Z]/","",$activ_lower);
$filename="$sch$activ_lower";
if($state==1)
   $filename.="state";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
fwrite($open,$string);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.txt"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.txt");

if($print==1)
{
   //update track team elig list
   $date=date("M d, Y");
   $info=$init_html;
   $info.="<body><table width=100%><tr><td>";
   $info.="<br><table width=350 cellspacing=1 cellpadding=3 border=1 bordercolor=#000000>";
   $info.="<caption><b>$school Boys Track & Field District Roster as of $date:</b><br><br></caption>";
   $csv="$school Boys Track & Field District Roster:\r\n";
   $csv.=",Name,Grade\r\n";
   $info.="<tr align=center><th class=smaller colspan=2>Name</th><th class=smaller>Grade</th></tr>";

   $students=GetRoster('tr',$school,'M');
   for($i=0;$i<count($students);$i++)
   {
      $x=$i+1;
      $temp=split(",",$students[$i]);
      $name=$temp[0]; $grade=$temp[1];
      $info.="<tr align=left><td align=center>$x.</td>";
      $info.="<td><font size=2>$name</font></td>";
      $info.="<td align=center>$grade</td></tr>";
      $name=preg_replace("/,/","",$name);
      $csv.="$x,$name,$grade\r\n";
   }
   $info.="</table><br><b><font size=2>Total: $i</font></b>";
   $csv.="Total:,$i\r\n";

   $info.="</td></tr></table></body></html>";

   //write eligibility list to csv file to send along with dist entry form
   $sch=strtolower($school);
   $sch=preg_replace("/[^0-9a-zA-Z]/","",$sch);
   $filename="/home/nsaahome/attachments/";
   $filename.=$sch."boysdistroster";

   $open=fopen(citgf_fopen("$filename.txt"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("$filename.txt");

   $open=fopen(citgf_fopen("$filename.html"),"w");
   fwrite($open,$info);
   fclose($open); 
 citgf_makepublic("$filename.html");

   $teamlist=$filename;
?>
</table>
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=state value=<?php echo $state; ?>>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<input type=hidden name=teamlist value="<?php echo $teamlist; ?>">
<table>
<tr align=center><td colspan=2><b>E-MAIL THIS FORM:</b><br>PLEASE NOTE: Your district director will automatically receive these forms once the due date has passed. You do NOT need to email this form to the district director.</td></tr>
<tr align=left><th align=left>
Your e-mail address:</th>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><th align=left>
Recipient(s)' address(es):</th>
<td>
<textarea name=email class=email cols=50 rows=5><?php echo $recipients; ?></textarea>
<?php
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\"></td></tr>";
echo "<tr align=left><th align=left>Comments (optional):</th><td><textarea name=comments cols=50 rows=3></textarea>";
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
</table>
<?php
}

/*
if($send=='y')  //if box checked at bottom of edit screen, send to state assn
{
   $From=GetEmail("main");
   $FromName=$stateassn;
   $To=GetEmail("tr");
   $ToName=GetName("tr");
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament
Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school'
s $activ State Tournament Roster information.<br><br>They have approved this as
their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.txt");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
}
*/

echo "</td><!--End Main Body-->
</tr>
</table>
</body>
</html>";

//FUNCTIONS:
function GetPerf($event,$possibles)
{
   if($event==$possibles[3]) $perf=$possibles[4];
   else if($event==$possibles[5]) $perf=$possibles[6];
   else if($event==$possibles[7]) $perf=$possibles[8];
   else if($event==$possibles[9]) $perf=$possibles[10];
   return $perf;
}
function GetEventSql($event,$table,$school2)
{
   $eventsql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters, t2.eligible FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND (t1.event_1='$event' OR t1.event_2='$event' OR t1.event_3='$event' OR t1.event_4='$event')";
   return $eventsql;
}
?>
