<?php
exit();


require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

$sql0="SELECT school FROM tr_temp";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $school=$row0[0];
   $school2=ereg_replace("\'","\'",$school);
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
/*
Re-direct to edit page if no students entered yet:
$sql="SELECT t1.* FROM $table AS t1 WHERE (t1.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   header("Location:edit_tr_b.php?session=$session&school_ch=$school_ch");
   exit();
}
*/

//echo $init_html;
//if($print!=1) 
   //echo $header;
$string=$init_html;

//get class from tr table
$sql="SELECT t1.class_dist FROM $table AS t1 WHERE t1.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row[0];

$string.="<center><br>";
//echo "<center>";
if($print!=1)	//show links if non-printer friendly
{
   //echo "<a href=\"edit_tr_b.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a>&nbsp;&nbsp;&nbsp;";
}

$info="";
$csv="";
$info.="<table cellspacing=4 cellpadding=4><!--Table of Tables-->";
$info.="<caption><b>BOYS TRACK & FIELD $form_type ENTRY FORM <br>";
if($state!=1) $info.= "Due $duedate2";
$info.="</b></caption>";
$info.="<tr align=left><td colspan=2><table><!--Team Info-->";
$info.="<tr align=left><th>School/Mascot:</th><td>$school $mascot</td></tr>";
$info.="<tr align=left><th>School Colors:</th><td>$colors</td></tr>";
$info.="<tr align=left><th>NSAA-Certified Coach:</th><td>$coach</td></tr>";
$info.="<tr align=left><th>Assistant Coach(es):</th><td>$asst</td></tr>";
$info.="<tr align=left><th>Class:</th>";
$info.="<td>$class</td></tr>";
$csv.="School/Mascot:,$school $mascot\r\n";
$csv.="School Colors:,$colors\r\n";
$csv.="NSAA-Certified Coach:,$coach\r\n";
$csv.="Assistant Coach(es):,\"$asst\"\r\n";
$csv.="Class:,$class\r\n";
$string.=$info;
//echo $info;
$info="";
if($print!=1)
{
   //echo "<tr align=left><td colspan=2>";
   //echo "<a href=\"teamlist_b.php?session=$session&school_ch=$school&print=1\" target=new>Click Here for your Team's District Roster</a><br>";
   //echo "<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your list of eligible team members (i.e., your district roster) will be automatically attached when you e-mail this form to your district director.</i></td></tr>";
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
  if($trevents[$x]!="teamscores" && $trevents[$x]!="extraqual")
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
      if(ereg("-",$perf))
      {
	 $perf=ereg_replace("-","'",$perf);
	 $perf.="\"";
      }
      $csv.="$row[14] $row[13],$year,$perf\r\n";
   }
   $info.="</table></td>";
   if(($x+1)%2==0) $info.="</tr>";
  }
}

//echo $info;
$string.=$info;

if($print!=1)
{
   //echo "</table><!--End Table of Tables-->";
   //echo "<a href=\"edit_tr_b.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   //echo "<a href=\"../welcome.php?session=$session\" class=small>Home</a>";
}

//Allow user to e-mail form
$string.="</table></td></tr></table></body></html>";
$activ="Boys Track & Field";
$activ_lower=strtolower($activ);

$sch=ereg_replace(" ","",$school);
$sch=ereg_replace("-","",$sch);
$sch=ereg_replace("\.","",$sch);
$sch=ereg_replace("\'","",$sch);
$sch=strtolower($sch);
$activ_lower=ereg_replace(" ","",$activ_lower);
$activ_lower=ereg_replace("&","",$activ_lower);
$filename=$sch.$activ_lower."2";
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
   citgf_exec("/usr/local/bin/php teamlist_b.php session=$session school_ch=\"$school_ch\" > output.txt 2>&1");
   $teamlist="tr/teamlists/".$sch."boysdistroster";
?>
</table>
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php">
<input type=hidden name=state value=<?php echo $state; ?>>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<input type=hidden name=teamlist value="<?php echo $teamlist; ?>">
<table>
<tr align=left><th>
Your e-mail address:</th>
<td><input type=text name=reply size=15></td>
</tr>
<tr align=left><th>
Recipient(s)' address(es):</th>
<td><input type=text name=email size=15></td>
</tr>
<tr align=center><td colspan=2>
<input type=submit name=submit value="Send">
</td></tr>
</table>
<?php //echo $email_note; ?>
</form>
</th></tr>
</table>
<?php
}

/*
if($send=='y')  //if box checked at bottom of edit screen, send to NSAA
{
   $From="nsaa@nsaahome.org";
   $FromName="NSAA";
   $To=$main_email;
   $ToName="NSAA";
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
/*
echo "</td><!--End Main Body-->
</tr>
</table>
</body>
</html>";
*/
}//end for each school
echo "DONE";

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
