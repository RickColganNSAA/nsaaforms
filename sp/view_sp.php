<?php
//view_sp.php: view speech district form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$level=GetLevel($session);
if((!$school_ch || $level==2 || $level==3) && $director!='1')
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
   $sql="SELECT id FROM logins WHERE school='$hostsch2' AND level='2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[0];
   $sql="SELECT * FROM $db_name2.spdistricts WHERE hostid='$hostid'";
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
$schoolid=$row[id]; $sport="sp";
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

//MASCOT, COLORS, COACH
$mascot=GetMascot($schoolid,$sport);
$colors=GetColors($schoolid,$sport);
$coach=GetCoaches($schoolid,$sport);
$asst=GetAsstCoaches($schoolid,$sport);

//if no kids entered yet, redirect to Edit Page:
$sql="SELECT t1.* FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') AND t1.checked='y'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_sp.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}

$duedate=GetDueDate("sp");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

echo $init_html;
if($print!=1)
{
   echo GetHeader($session);
   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Speech\">Return to Speech Entry Forms</a><br>";
}
$string=$init_html;
$csv="";

echo "<br>";
if($print!=1)
{
   echo "<a href=\"view_sp.php?session=$session&school_ch=$school_ch&print=1\" target=new class=small>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"edit_sp.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
}
$string.="<br>";
$sql="SELECT id, last, first, middle, semesters, eligible FROM eligibility WHERE school='$school2' AND sp='x' ORDER BY last";
$result=mysql_query($sql);
$ssst=array();
while($row=mysql_fetch_array($result))
{
	
	$ssst[]=	$row['id'];
}
$in=implode(',',$ssst);
//get already-submitted info for this school:
if($in!='')
	$sql="SELECT t1.* FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.co_op='$school2' OR t2.school='$school2')  and t1.student_id in (".$in.") ORDER BY t2.last";
else $sql="SELECT t1.* FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.co_op='$school2' OR t2.school='$school2') ORDER BY t2.last";
$result=mysql_query($sql);

$info.="<font size=2><b>DISTRICT SPEECH CONTEST ENTRY FORM<br></font></b>";
$info.="<div class=alert style='width:600px;font-size:9pt;'><b>PLEASE NOTE: </b> You do <b><u>NOT</b></u> need to e-mail or otherwise send this form to your district director! The director will be able to access this form through his or her NSAA Login. The information you've entered below must be <b><u>COMPLETED BY MIDNIGHT ON <label style='color:#ff0000;'>$duedate2</label></b></u>. After that date, your director will consider your form <b>COMPLETE</b>.</div><br>";
$info.="<table><!--Table of Tables-->";

$row=mysql_fetch_array($result);
$class=$row[2];
$contest_site=$row[3];
$emergname=$row[emergname]; $emergph=$row[emergph];

$info.="<tr align=left><td>";
$info.="<table><!--School and Contest Info-->";
$info.="<tr align=left>";
$info.="<th align=left>School/Mascot:</th>";
$info.="<td>".GetSchoolName($sid,'sp')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'sp')." $mascot\r\n";
$info.="<tr align=left><th align=left>Colors:</th><td>$colors</td></tr>";
$info.="<tr align=left>";
$info.="<th align=left>Class:</th><td>$class</td></tr>";
$info.="<tr align=left>";
$info.="<th align=left>Contest Site:</th><td>$contest_site</td></tr>";
$info.="<tr align=left>";
$info.="<th align=left>$stateassn-Certified Coach:</th>";
$info.="<td>$coach</td></tr>";
$info.="<tr align=left>";
$info.="<th align=left>Assistant Coaches:</th>";
$info.="<td>$asst</td></tr>";
$info.="<tr align=left><td colspan=2><b>Emergency Contact Person:&nbsp;&nbsp;</b>$emergname&nbsp;&nbsp;&nbsp;";
$emergph="(".substr($emergph,0,3).")".substr($emergph,3,3)."-".substr($emergph,6,4);
$info.="<b>Phone:</b>&nbsp;&nbsp;$emergph</td></tr>";
$info.="<tr align=left>";
$info.="<th align=left colspan=2>Code No*:__________";
$info.="<font style=\"font-size:8pt\">(Double Coding Shall be Used.)</font>";
$info.="</th></tr>";
$info.="</table>";
$csv.="Colors:,$colors\r\nClass:,$class\r\nContest Site:,$contest_site\r\n";
$csv.="$stateassn-Certified Coach:,$coach\r\nCode No:,\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$csv.="Emergency Contact Person:,$emergname\r\n";
$csv.="Phone:,$emergph\r\n";
$info.="</td>";
$info.="</tr>";
$info.="<tr align=left>";
$info.="<td>";
$info.="<table style=\"width:100%;border:#333333 1px solid;\" frame=all rules=all cellspacing=0 cellpadding=5>";
$info.="<!--Participants Table-->";
$info.="<tr align=center>";
$info.="<th class=smaller rowspan=2>Entrant's Name</th>";
$info.="<th class=smaller rowspan=2>Grade</th>";
$info.="<th class=smaller rowspan=2>Letter<br>Code*</th>";
$info.="<th class=smaller rowspan=2>Drama<br>Group 1</th><th class=smaller rowspan=2>Drama<br>Group 2</th>";
$info.="<th class=smaller rowspan=2>Poetry</th>";
$info.="<th class=smaller rowspan=2>Persuasive<br>Speaking</th>";
$info.="<th class=smaller rowspan=2>Informative<br>Public<br>Speaking</th>";
$info.="<th class=smaller rowspan=2>Extemporaneous<br>Speaking</th>";
$info.="<th class=smaller rowspan=2>Entertainment<br>Speaking</th>";
$info.="<th class=smaller rowspan=2>Duet Acting<br>Group 1</th><th class=smaller rowspan=2>Duet Acting<br>Group 2</th>";
$info.="<th class=smaller colspan=2>Oral Interpretation of Prose</th>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<th class=smaller>Humorous</th>";
$info.="<th class=smaller>Serious</th>";
$info.="</tr>";
$csv.="\r\nEntrant's Name,Grade,Letter Code,Drama 1,Drama 2,Poetry,Persuasive Speaking,Informative Public Speaking,Extemporaneous Speaking, Entertainment Speaking, Duet Acting 1,Duet Acting 2, Oral Interpretation of Prose: Humorous, Oral Interpretation of Prose: Serious\r\n";

$result=mysql_query($sql);	//pull data again
while($row=mysql_fetch_array($result))
{
   if($row[checked]=='y')
   {
      $sql2="SELECT last, first, middle, semesters FROM eligibility WHERE id='$row[1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $info.="<tr align=center>";
      $info.="<td align=left>$row2[1] $row2[0]</td>";
      $year=GetYear($row2[3]);
      $info.="<td>$year</td>";
      $info.="<td>&nbsp;</td>";		//blank space for coding
      if($row[drama1]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[drama2]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[poetry]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[pers_speak]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[inform]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[extemp]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[ent_speak]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[duet_acting1]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[duet_acting2]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[prose_humor]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
      if($row[prose_serious]=='y') $info.="<td>X</td>";
      else $info.="<td>&nbsp;</td>";
	  if($row[prose_humor]=='y') $info.="<td style=\"border-right-style: hidden;border-top-style: hidden;border-bottom-style: hidden; \"><a href=\"\" style=\"font-size:10px\" onClick=\"window.open('prose.php?session=$session&school=$school2&name=prose_humor&id=$row[1]&view=1','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')\">Humorous</a></td>";
	  else $info.="<td style=\"border-right-style: hidden;border-top-style: hidden;border-bottom-style: hidden; \"><a href=\"\" style=\"font-size:10px\" onClick=\"window.open('prose.php?session=$session&school=$school2&name=prose_humor&id=$row[1]&view=1','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')\">&nbsp</a></td>";
	  if($row[prose_serious]=='y') $info.="<td style=\"border-right-style: hidden;border-top-style: hidden;border-bottom-style: hidden;\"><a href=\"\" style=\"font-size:10px\" onClick=\"window.open('prose.php?session=$session&school=$school2&name=prose_serious&id=$row[1]&view=1','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')\">Serious</a></td>";
      $info.="</tr>";
      $csv.="$row2[0] $row2[1] $row2[2],$year,,$row[drama1],$row[drama2],$row[poetry],$row[pers_speak],$row[inform],$row[extemp],$row[ent_speak],$row[duet_acting1],$row[duet_acting2],$row[prose_humor],$row[prose_serious]\r\n";
   }
}

$info.="</table>";
$info.="* For use by tournament director only.  Double coding shall be used.";
$info.="</td></tr>";

echo $info;
$string.=$info;
$sql="SELECT * FROM sp WHERE co_op='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
?>
<tr align=center><td><h4 align=center>Co-op Students</h4></td></tr>
<table style=\"width:100%;border:#333333 1px solid;\" cellspacing=0 cellpadding=5 frame=all rules=all>
   <tr align=center>
   <th class=smaller colspan=1 rowspan=2>School</th>
   <th class=smaller rowspan=2>Name</th>
   <th class=smaller rowspan=2>Grade</th>
   <th class=smaller rowspan=2>Drama<br>GROUP 1<br>(max: 5)</th>
   <th class=smaller rowspan=2>Drama<br>GROUP 2<br>(max: 5)</th>
   <th class=smaller rowspan=2>Poetry</th>
   <th class=smaller rowspan=2>Persuasive<br>Speaking</th>
   <th class=smaller rowspan=2>Informative<br>Public<br>Speaking</th>
   <th class=smaller rowspan=2>Extemporaneous<br>Speaking</th>
   <th class=smaller rowspan=2>Entertainment<br>Speaking</th>
   <th class=smaller rowspan=2>Duet Acting<br>GROUP 1<br>(2 students)</th>
   <th class=smaller rowspan=2>Duet Acting<br>GROUP 2<br>(2 students)</th>
   <th class=smaller colspan=2>Oral Interpretation of Prose</th>
   </tr>
   <tr align=center>
   <th class=smaller>Humorous</th>
   <th class=smaller>Serious</th>
   </tr>
<?php
$i=0;
   while($row=mysql_fetch_array($result))
   {
      //get info on co_op student
      $sql2="SELECT id, school, last, first, middle, semesters,eligible FROM eligibility WHERE id='$row[1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<tr align=center>";
      echo "<td>$row2[1]</td>";
      echo "<td";
      if($row2[6]!='y') echo " bgcolor=red";
      echo " align=left>$row2[2], $row2[3] $row2[4]</td>";
      echo "<input type=hidden name=\"coop_student[$i]\" value=$row2[0]>";
      $year=GetYear($row2[5]);
      echo "<td>$year</td>";
	  if($row[drama1]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[drama2]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[poetry]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[pers_speak]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[inform]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[extemp]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[ent_speak9]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[duet_acting1]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[duet_acting2]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[prose_humor]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[prose_serious]=='y') echo "<td>X</td>";
      else echo"<td>&nbsp;</td>";
	  if($row[prose_humor]=='y') $info.="<td style=\"border-right-style: hidden;border-top-style: hidden;border-bottom-style: hidden; \"><a href=\"\" style=\"font-size:10px\" onClick=\"window.open('prose.php?session=$session&school=$school2&name=prose_humor&id=$row[1]&view=1','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')\">Humorous</a></td>";
	  else $info.="<td style=\"border-right-style: hidden;border-top-style: hidden;border-bottom-style: hidden; \"><a href=\"\" style=\"font-size:10px\" onClick=\"window.open('prose.php?session=$session&school=$school2&name=prose_humor&id=$row[1]&view=1','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')\">&nbsp</a></td>";
	  if($row[prose_serious]=='y') $info.="<td style=\"border-right-style: hidden;border-top-style: hidden;border-bottom-style: hidden;\"><a href=\"\" style=\"font-size:10px\" onClick=\"window.open('prose.php?session=$session&school=$school2&name=prose_serious&id=$row[1]&view=1','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')\">Serious</a></td>";
      
      echo "</tr>";
      $i++;
    }
    echo "</table>";
} 

if($print!=1)	//non=printer friendly version
{
   echo "<tr align=center><td><br>";
   echo "<a href=\"view_sp.php?session=$session&school_ch=$school_ch&print=1\" target=new class=small>Printer-Friendly Version</a>";
   echo "&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"edit_sp.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a>";
   echo "&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"../welcome.php?session=$session\" class=small>Home</a>";
   echo "</td></tr>";
}
?>
</table><!--End Table of Tables-->
</td><!--End Main-->
</tr>
</table>
</body>
</html>
