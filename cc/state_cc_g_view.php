<?php
//state_cc_g_view.php: view submitted qualifiers (dist host only)

require '../variables.php';
require '../functions.php';

$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get school of user
if($school_ch && GetLevel($session)==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

//check that user is a district host
if(!$dist_select)
{
   $sql="SELECT * FROM $db_name2.ccgdistricts WHERE hostschool='$school2'";
}
else
{
   $sql="SELECT * FROM $db_name2.ccgdistricts WHERE id='$dist_select'";
}
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$dist=$row[id]; $count=mysql_num_rows($result);
$class_dist="$row[class]-$row[district]";
$submitted=$row[submitted_g];

if((!$dist_select && mysql_num_rows($result)==0) || !ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

//check if school is hosting more than one district
if($count>0 && !$dist_select && $print!=1)
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br>";
   echo "<table width=350><caption align=left><b>Please select the district you wish to enter results for:</b></caption>";
   echo "<tr align=left><th>";
   $sql="SELECT id,class,district FROM $db_name2.ccgdistricts WHERE hostschool='$school2' ORDER BY class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<a href=\"state_cc_g_view.php?session=$session&school_ch=$school_ch&dist_select=$row[0]\">District $row[class]-$row[district]</a><br>";
   }
   exit();
}

//check to see if anything has been submitted yet:
$sql="SELECT * FROM cc_g_state_indy WHERE district_id='$dist'";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
$sql="SELECT * FROM cc_g_state_team WHERE district_id='$dist'";
$result=mysql_query($sql);
$ct2=mysql_num_rows($result);
$total=$ct+$ct2;
if($total==0)
{
   header("Location:state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist");
   exit();
}

echo $init_html;
if($print!=1)
{
   echo GetHeader($session);
}
$string=$init_html;
$csv="";

echo "<br>";
if($print!=1)
{
   echo "<a href=\"state_cc_g_view.php?session=$session&school_ch=$school_ch&dist_select=$dist&print=1\" class=small target=new>Printer/E-mail Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist\" class=small>Edit this Form</a><br><br>";
}//end if not print
   
$string.="<center><br><br>";

if($final=='y')
{
   echo "<font style=\"color:red\"><b>The following results have been submitted to the NSAA for your district:</b><br><br></font>";
}

//get submitted info for this district
$info="<table width=90%><!--Table of Tables-->";
$info.="<caption><b>GIRLS CROSS-COUNTRY DISTRICT RESULTS</b>";
if($submitted=='x')
{
   echo "<div class=alert style=\"width:400px;\">You have already submitted the following results to the NSAA.  If you need to make changes to these results, please <a href=\"state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist\" class=small>Edit this Form</a>, check the box indicating your results are final, and submit this form again.</div>";
}
else
{
   echo "<div class=error style=\"width:400px;\">You have NOT submitted the following results to the NSAA yet.  To do so, please click <a href=\"state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist\" class=small>Edit this Form</a>, check the box indicating your results are final, and submit this form.</div>";
}
//check if there are too may students checked for a team
$sql2="SELECT t1.*,t2.school FROM cc_g_state_team AS t1,ccgschool AS t2 WHERE t1.sid=t2.sid AND t1.district_id='$dist' AND t1.student_ids IS NOT NULL ORDER BY t1.place";
$result2=mysql_query($sql2);
$toomany=0;
while($row2=mysql_fetch_array($result2))
{
   $students=split(",",$row2[3]); $times=split(",",$row2[finishtimes]);
   if(count($students)>7 && $row['class']=="A")
   {
      $final="";
      echo "<br><div class=error style=\"width:500px\">ERROR: You have checked too may students for $row2[school].  Please <a class=white href=\"state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist\">Correct this Error</a> and try again.<br><br>Your results will NOT be sent to the NSAA until you have corrected this error.</div>";
   }
   if(count($students)>count($times))
   {
      $final="";
      echo "<br><div class=error style=\"width:500px\">ERROR: You have not entered finishing times for all of the students checked for $row2[school].  Please <a class=white href=\"state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist\">Correct this Error</a> and try again.<br><br>Your results will NOT be sent to the NSAA until you have corrected this error.</div>";
   }
}
$info.="</caption>";
$info.="<tr align=center><td><table><tr align=left>";
$info.="<th>Class/District:</th><td>$row[class]-$row[district]</td>";
$info.="<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$info.="Location:</th><td>$row[site]</td>";
$info.="<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$info.="Date:</th><td>";
$date=split("-",$row[dates]);
$info.="$date[1]/$date[2]/$date[0]</td></tr>";
$info.="</table></td></tr>";

//initialize array of qualifiers
$qualify=array();
$ix=0;

//get submitted individuals
$info.="<tr align=center><td>";
$info.="<table rules=all frames=none cellspacing=0 cellpadding=2 style=\"border:#333333 1px solid;\"";
$info.="<caption><b>Qualifying Individuals:</b></caption>";
$info.="<tr align=center>";
$info.="<th>Place</th><th>Name</th><th>Grade</th><th>School</th><th>Coach</th><th>Time</th>";
$info.="</tr>";
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters, t2.gender FROM cc_g_state_indy AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t1.district_id='$dist' ORDER BY t1.place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $co_op=0;
   $schools="";
   $sid=$row[sid];
   //get student's coach
   $sql2="SELECT t1.name,t3.school FROM logins AS t1, headers AS t2, ccgschool AS t3 WHERE t1.school=t2.school AND t2.id=t3.mainsch AND t1.sport='Girls Cross-Country' AND t3.sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $info.="<tr align=center>";
   $info.="<th>$row[place]</th>";
   $info.="<td align=left>$row[last], $row[first] $row[middle]</td>";
   $year=GetYear($row[semesters]);
   $info.="<td>$year</td>";
   $info.="<td align=left>$row2[school]</td><td align=left>$row2[name]</td>";
   $info.="<td align=center>$row[finishtime]</td>";
   $info.="</tr>";
   $qualify[0][$ix]=$row[3];
   $qualify[1][$ix]=$sid;
   $ix++;
}
$info.="</table></td></tr>";

//get submitted qualifying teams
$info.="<tr align=center><td><br>";
$info.="<table cellspacing=4 cellpadding=4>";	//table of team tables
$info.="<caption><b>Qualifying Teams:</b></caption><tr align=center valign=top>";
$sql="SELECT * FROM cc_g_state_team WHERE district_id='$dist' AND student_ids IS NOT NULL ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $info.="<td><table frames=all rules=all style=\"border:#333333 1px solid;\" cellspacing=0 cellpadding=2>";
   $co_op=0;
   if($row[place]==1) $place="1st";
   else if($row[place]==2) $place="2nd";
   else if($row[place]==3) $place="3rd";
   $sql2="SELECT * FROM ccgschool WHERE sid='$row[sid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sid=$row2[sid];
   if($row2[othersch1]>0) $co_op=1;
   else $co_op=0;
   $info.="<tr align=center><th colspan=3>$place Place Team: <i>$row2[school]</i></th></tr>";
   $info.="<tr align=center><th class=smaller>Team Member</th>";
   $info.="<th class=smaller>Grade</th><th class=smaller>Time</th></tr>";
   $students=split(",",$row[3]); $times=split(",",$row[finishtimes]);
   for($i=0;$i<count($students);$i++)
   {
      $sql2="SELECT last, first, middle, semesters FROM eligibility WHERE id='$students[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $year=GetYear($row2[3]);
      $info.="<tr align=center><td align=left>$row2[0], $row2[1] $row2[2]</td>";
      $info.="<td>$year</td><td>".$times[$i]."</tr>";
      $qualify[0][$ix]=$students[$i];
      $qualify[1][$ix]=$sid;	//indicates if student's school is co-oping
      $ix++;
   }
   $info.="</table></td>";
}
$info.="</tr></table></td></tr>";

//get team scores
$info.="<tr align=center><td>";
$info.="<table rules=all frames=all cellspacing=0 cellpadding=2 style=\"border:#333333 1px solid;\">";
$info.="<caption><b>Team Scoring:</b></caption>";
$info.="<tr align=center><th>Place</th><th>Team</th><th>Score</th></tr>";
$sql="SELECT * FROM cc_g_state_team WHERE district_id='$dist' ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $info.="<tr align=center><th>$row[place]</th>";
   $sql2="SELECT * FROM ccgschool WHERE sid='$row[sid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $info.="<td align=left>$row2[school]</td><td>$row[score]</td></tr>";
}
$info.="</table></td></tr>";

$string.=$info;
$string.="</table><!--End Table of Tables--></td></tr></table></body></html>";
echo $info;
if($print!=1)
{
   echo "<tr align=center><td><br>";
   echo "<a href=\"state_cc_g_view.php?session=$session&school_ch=$school_ch&dist_select=$dist&print=1\" class=small target=new>Printer/E-mail Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"state_cc_g_edit.php?session=$session&school_ch=$school_ch&dist_select=$dist\" class=small>Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"../welcome.php?session=$session\" class=small>Home</a>";
}//end if not print
echo "</table><!--End Table of Tables--><br>";

//if checkbox was checked, send results to NSAA
if($final=='y')
{
   //update database to show that they've submitted results
   $sql="UPDATE $db_name2.ccgdistricts SET submitted_g='x' WHERE id='$dist'";
   $result=mysql_query($sql);
   /*
   for($i=0;$i<count($qualify[0]);$i++)
   {
      $sql="SELECT school FROM ccgschool WHERE sid='".$qualify[1][$i]."'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $school=$row[school];
      $sql="SELECT last,first,middle,gender,semesters FROM eligibility WHERE id='".$qualify[0][$i]."'";
      //echo $sql;
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $year=GetYear($row[semesters]);
      //$teamcode=GetTeamCode($qualify[1][$i],'cc_g'); //generate code for co-op
      $cursid=$qualify[1][$i];
      if(strlen($cursid)==1) $cursid="000".$cursid;
      if(strlen($cursid)==2) $cursid="00".$cursid;
      if(strlen($cursid)==3) $cursid="0".$cursid;
      $teamcode=$cursid;
      $csv.="D;$row[last];$row[first];$row[middle];$row[gender];;$teamcode;$school;;$year;;;M;;;;;;\r\n";
   }
   $filename="ccstate";
   $filename.=$dist;
   $filename.=".txt";
   $open=fopen(citgf_fopen($filename),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic($filename);

   //send file to NSAA
   $From="nsaa@nsaahome.org";
   $FromName="District $dist";
   $To=$cc_email;
   $ToName="NSAA";
   $Subject="$class_dist Girls Cross-Country State Qualifiers";
   $Text="Attached is the semicolon-delimited Hytek file for the $class_dist Girls Cross-Country District State Qualifier Info.  Thank you!";
   $Html="<font size=2 family=arial>Attached is the semicolon-delimited Hytek file for the $class_dist Girls Cross-Country District State Qualifier Info.<br><br>They have approved this as their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array($filename);
      */
   $Subject="$school has submitted Girls Cross-Country District Results";
   $Html=$Subject.".<br><br>Thank You!";
   $Text=$Subject.".\r\n\r\nThank You!";
   $AttmFiles=array();
   SendMail($From,$FromName,"nneuhaus@nsaahome.org","Nate Neuhaus",$Subject,$Text,$Html,$AttmFiles);
   //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);
}
if($print==1)	//printer-friendly version
{
   //Allow user to e-mail form
   $activ="Girls Cross Country";
   $activ_lower=strtolower($activ);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="ccstate";
   $filename.=$dist;
   $filename.=".html";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
?>
<table>
<tr align=center><th>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=district value="<?php echo $dist; ?>">
<input type=hidden name=class_dist value="<?php echo $class_dist; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<table>
<tr align=left><th class=smaller>
Your e-mail address:</th>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><th class=smaller>
Recipient(s)' address(es):</th>
<td>
<textarea name=email cols=50 rows=5 class=email><?php echo $recipients; ?></textarea>
<?php
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no, location=no, resizable=no, scrollbars=yes, width=500, height=600')\">";
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
</table>
<?php
}  //end if print=1

echo "</td></tr></table></body></html>";
?>
