<?php
//view_fb_state.php: Football State Entry Form

require "../functions.php";
require "../variables.php";
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="fb";
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

//check if school has submitted any students yet
$sql="SELECT t1.* FROM fb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   header("Location:edit_fb_state.php?session=$session&school_ch=$school_ch");
   exit();
}

echo $init_html;
$string=$init_html;
if($print!=1) echo GetHeader($session);

//get school info
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[6];
$colors=$row[5];

//get coach's name
$sql="SELECT name FROM logins WHERE school='$school2' AND level='3' AND sport LIKE 'Football%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];
?>
<?php
if($print!=1)
{
?>
<a class=small href="view_fb_state.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;
<a class=small href="view_fb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Football Main Page</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="edit_fb_state.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Edit this Form</a>
<?php
}//end if not print
if($send=='y')
{
   echo "<br><br><font size=2 style=\"color:red\"><b>The following information has been submitted to the $stateassn:</b></font>";
}
$info="<br><br>";
$info.="<form method=post action=\"submit_fb_state.php\">";
$info.="<input type=hidden name=session value=$session>";
$info.="<input type=hidden name=school_ch value=\"$school_ch\">";
$info.="<table width=85%><!--Table of Tables-->";
$info.="<caption><b>$stateassn Football State Playoff Roster Form</b><br>";
//check if already submitted state form
$sql="SELECT t1.datesub FROM fb_classes AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[0]!='')
{
   $info.="<font style=\"color:red\"><b>Submitted to the NSAA on ".date("m/d/y",$row[0])."</b></font>";
}
$info.="<hr></caption>";
$info.="<tr align=center>";
$info.="<td>";
$info.="<table width=50%><!--School Info-->";
$info.="<tr align=left>";
$info.="<th>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'fb');
$sql="SELECT * FROM fbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$info.="<td>".GetSchoolName($sid,'fb')." $mascot</td></tr>";
$info.="<tr align=left>";
$info.="<th>Colors:</th><td>$colors</td></tr>";
$info.="<tr align=left><th>Class:</th>";
$info.="<td>$row[class]</td></tr>";
   //get staff already submitted for this school
   $sql="SELECT t1.* FROM fb_staff AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $asst_coaches=$row[2];
   $ath_trainers=$row[3];
   $managers=$row[4];
$info.="<tr align=left>";
$info.="<th>$stateassn-Certified Coach:</th><td>$coach</td></tr>";
$info.="<tr align=left>";
$info.="<th>Assistant Coaches:</th>";
$info.="<td>$asst_coaches</td></tr>";
$info.="<tr align=left>";
$info.="<th>Athletic Trainer(s):</th>";
$info.="<td>$ath_trainers</td></tr>";
$info.="<tr align=left>";
$info.="<th>Managers:</th>";
$info.="<td>$managers</td></tr>";
$info.="</table>";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table frame=all rules=all cellspacing=0 cellpadding=4 style=\"border:#808080 1px solid;\">";
$info.="<!--Players Info-->";
$info.="<caption><b>Playoff Roster</b></caption>";
$info.="<tr align=left><td colspan=12><b>* S</b>='Starter',  <b>M</b>='Medalist'</td></tr>";
$info.="<tr align=center>";
$info.="<th class=smaller>S*</th>";
$info.="<th class=smaller>M*</th>";
$info.="<th class=smaller>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Player's Name</th><th class=smaller>Nickname</th>";
$info.="<th class=smaller>Pronunciation</th>";
$info.="<th class=smaller>Grade</th>";
$info.="<th class=smaller>Offensive<br>Position</th>";
$info.="<th class=smaller>Defensive<br>Position</th>";
$info.="<th class=smaller>Height</th>";
$info.="<th class=smaller>Weight</th>";
$info.="</tr>";
   //get players already submitted from db table 
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM fb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt, t1.jersey_dk";
   $result=mysql_query($sql);
   $ix=0;
   $starters=0;
   $medalists=0;
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td>";
      if($row[6]=='y') 
      {
	 $info.="X"; $starters++;
      }
      else $info.="&nbsp;";
      $info.="</td>";
      $info.="<td>";
      if($row[7]=='y') 
      { 
	 $info.="X"; $medalists++;
      }
      else $info.="&nbsp;";
      $info.="</td>";
      $info.="<td>$row[4]</td>";
      $info.="<td>$row[5]</td>";
      $info.="<td align=left>$row[first] $row[middle] $row[last]</td><td align=left>$row[nickname]</td>";
      $info.="<td align=left>$row[3]</td>";
      $info.="<td>";
      $year=GetYear($row[semesters]);
      $info.=$year;
      $info.="</td>";
      $info.="<td>$row[8]</td>";
      $info.="<td>$row[9]</td>";
      $info.="<td>$row[10]</td>";
      $info.="<td>$row[11] lbs</td>";
      $info.="</tr>";
      $ix++;
   }
$info.="<tr align=center><td>$starters</td><td>$medalists</td><td colspan=10></td></tr>"; 
$info.="</table></td></tr>";

//display playoff games info
$sql="SELECT t1.* FROM fb_playoff AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$info.="<tr align=center><td><br>";
$info.="<table frame=all rules=all cellspacing=0 cellpadding=4 style=\"border:#808080 1px solid;\">";
$info.="<caption><b>Playoff Games</b></caption>";
$info.="<tr align=center>";
$info.="<th class=smaller>Opponent</th>";
$info.="<th class=smaller>Score</th>";
$info.="<th class=smaller>Opp. Score</th></tr>";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM headers WHERE id='$row[2]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $info.="<tr align=center>";
   $info.="<td align=left>$row2[0]</td><td>$row[3]</td><td>$row[4]</td></tr>";
}
$info.="</table></td></tr>";

$info.="</table></form><!--End Table of Tables-->";
echo $info;
if($print!=1)
{
echo "<a class=small href=\"view_fb_state.php?session=$session&school_ch=$school_ch&print=1\" target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"view_fb.php?session=$session&school_ch=$school_ch\">Football Main Page</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"edit_fb_state.php?session=$session&school_ch=$school_ch\">Edit this Form</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"../welcome.php?session=$session\">Home</a>";
}//end if not print
else
{
   //Allow user to e-mail form
   $string.=$info;
   $string.="</td></tr></table></body></html>";
   $activ="Football";
   $activ_lower=strtolower($activ);

   $sch=strtolower(preg_replace("/[^0-9a-zA-Z]/","",$school));
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename=$sch.$activ_lower."state";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");
?>
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=state value="1">
<input type=hidden name=fb value="2">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
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
</th></tr>
</table>
<?php
}  //end if print=1
echo "</td><!--End Main Body-->";
echo "</tr></table></body></html>";
?>
