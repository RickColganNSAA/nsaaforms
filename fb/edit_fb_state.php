<?php
//edit_fb_state.php: Football State Entry Form

require "../functions.php";
require "../variables.php";
require "../../calculate/functions.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

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
$schoolid=GetSchoolID2($school);
$sport='fb';

//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
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

if(!$start) $start=0;

echo $init_html;
echo GetHeader($session);

//MASCOT & COLORS & COACH
$mascot=GetMascot($schoolid,'fb');
$colors=GetColors($schoolid,'fb');
$coach=GetCoaches($schoolid,'fb');
?>
<br>
<a class=small href="view_fb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Football Main Page</a>
<?php
if($err==1)	//tried to send incomplete info
{
   echo "<br><br><font size=2 style=\"color:red\"><b>You have not completed all of your players' information.  Each player must have a jersey number, at least one position, a height, and a weight listed in order to be complete.  Please complete your information, check the box at the bottom of this page, and Save this form again.</b></font>";
}
else if($starters)	//too many starters checked
{
   echo "<br><br><font size=2 style=\"color:red\"><b>You have checked $starters starters.  You may only check 22 starters.  Please uncheck some of your players, check the box at the bottom of the page, and Save this form again.</b></font>";
}
else if($send=='y')
{
   echo "<br><br><font size=2 style=\"color:red\"><b>The following information has been submitted to the NSAA:</b></font>";
}
?>
<br><br>
   <form method=post action="submit_fb_state.php">
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<table width=100%><!--Table of Tables-->
<caption><b>NSAA Football State Playoff Roster Form</b><br>
<?php
//check if already submitted state form
$sql="SELECT t1.datesub FROM fb_classes AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[0]!='')
{
   echo "<font style=\"color:red\"><b>You submitted this form to the NSAA on ".date("m/d/y",$row[0]).".<br>";
   echo "You do NOT need to submit this form again, but you may do so IF you need to make a correction on the current form.</b></font>";
}
?>
</caption>
<tr align=center>
<td>
   <table><!--School Info-->
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
echo GetSchoolName($sid,'fb')." $mascot";
   ?>
   </td>
   </tr>
   <tr align=left>
   <th>Colors:</th><td><?php echo $colors; ?></td>
   </tr>
   <tr align=left>
   <th>Class:</th>
   <td><select name=class>
   <?php
   //get class/dist choices:
   $sql="SELECT choices FROM classes_districts WHERE sport='fb'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class_array=split(",",$row[0]);
   //get class for this school if already given
   $sql="SELECT t1.class FROM fb_classes AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $cur_class=$row[0];
   for($i=0;$i<count($class_array);$i++)
   {
      echo "<option";
      if($cur_class==$class_array[$i]) echo " selected";
      echo ">$class_array[$i]";
   }
   ?>
       </select>
   </td>
   </tr>
   <?php
   //get staff already submitted for this school
   $sql="SELECT t1.* FROM fb_staff AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $asst_coaches=$row[2];
   $ath_trainers=$row[3];
   $managers=$row[4];
   ?>
   <tr align=left>
   <th>NSAA-Certified Coach:</th><td><?php echo $coach; ?></td>
   </tr>
   <tr align=left>
   <th>Assistant Coaches:</th>
   <td><input type=text name=asst_coaches size=40 value="<?php echo $asst_coaches; ?>"></td>
   </tr>
   <tr align=left>
   <th>Athletic Trainer(s):</th>
   <td><input type=text name=ath_trainers size=40 value="<?php echo $ath_trainers; ?>"></td>
   </tr>
   <tr align=left>
   <th>Managers:</th>
   <td><input type=text name=managers size=40 value="<?php echo $managers; ?>"></td>
   </tr>
   </table>
</td>
</tr>
<tr align=center>
<td>
   <table frame=all rules=all cellspacing=0 cellpadding=5 style="border:#808080 1px solid;">
   <!--Playoff Results Table-->
   <caption><b>Playoff Games:</b><br>
<!--   <font size=2>(Classes A & B only need to enter 2 games)</font></caption>-->
   <tr align=center>
   <th class=smaller>Opponent</th>
   <th class=smaller>Your Score</th>
   <th class=smaller>Opp. Score</th>
   </tr>
   <?php
   //create schools array
   $sql="SELECT mainsch,school FROM fbschool ORDER BY school";
   $result=mysql_query($sql);
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      $schools[0][$i]=$row[0];
      $schools[1][$i]=$row[1];
      $i++;
   }
   //show playoff game info already submitted
   $sql="SELECT t1.* FROM fb_playoff AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
     echo "<tr align=center>";
     echo "<td><select name=\"opp[$ix]\">";
     echo "<option>Choose Opponent";
     for($i=0;$i<count($schools[0]);$i++)
     {
        $id=$schools[0][$i];
        $sch=$schools[1][$i];
        echo "<option value=$id";
        if($id==$row[2]) echo " selected";
        echo ">$sch";
     }
     echo "</select></td>";
     echo "<td><input type=text name=\"score[$ix]\" size=3 value=\"$row[3]\"></td>";
     echo "<td><input type=text name=\"opp_score[$ix]\" size=3 value=\"$row[4]\"></td>";
     echo "</tr>";
     $ix++;
   }
   while($ix<3)
   {
     echo "<tr align=center>";
     echo "<td><select name=\"opp[$ix]\">";
     echo "<option>Choose Opponent";
     for($i=0;$i<count($schools[0]);$i++)
     {
       $id=$schools[0][$i];
       $sch=$schools[1][$i];
       echo "<option value=$id>$sch";
     }
     echo "</select></td>";
     echo "<td><input type=text name=\"score[$ix]\" size=3></td>";
     echo "<td><input type=text name=\"opp_score[$ix]\" size=3></td>";
     echo "</tr>";
     $ix++;
   }
?>
   </table>
</td>
</tr>
<tr align=center>
<td><br>
   <table frame=all rules=all cellspacing=0 cellpadding=5 style="border:#808080 1px solid;">
   <!--Players Info-->
   <caption><b>Playoff Roster</b></caption>
   <tr align=left>
   <td colspan=12><b>* S</b>='Starter' (Only 22 allowed for Class A, B, C1, C2. 16 allowed for Class D1, D2. 12 allowed for Class D6),  <b>M</b>='Medalist'</td>
   </tr>
   <tr align=center>
   <th class=smaller>Select Player</th>
   <th class=smaller>Nickname<br>(How first name should show<br>in STATE PROGRAM, if different<br>from Column 1, e.g. "Tom")</th><th class=smaller>Pronunciation<br>(Mau-Er)</th>
   <th class=smaller>Grade</th>
   <th class=smaller>Light<br>Jersey<br>No.</th>
   <th class=smaller>Dark<br>Jersey<br>No.</th>
   <th class=smaller>S*</th>
   <th class=smaller>M*</th>
   <th class=smaller>Offensive<br>Position</th>
   <th class=smaller>Defensive<br>Position</th>
   <th class=smaller>Height</th>
   <th class=smaller>Weight</th>
   </tr>
   <?php
   $i=0;
   //get list of FB players from this school
   $studs=explode("<result>",GetPlayers($sport,$school));
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      $players[0][$i]=$stud[0];
      $players[1][$i]=$stud[1];
      $i++;
   }
   //get co_op players
	/*
   $sql="SELECT t1.* FROM eligibility AS t1, fb_coop AS t2 WHERE t1.id=t2.student_id AND t2.co_op='$school2' ORDER BY t1.last";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $players[0][$i]=$row[0];
      $players[1][$i]="$row[2], $row[3] $row[4]";
      $i++;
   }
	*/
   //get players already submitted from db table 
   $sql="SELECT t1.*, t2.semesters, t2.eligible FROM fb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last";
   $result=mysql_query($sql);
   $ix=0;
   $show=$start;
   $end=$start+25;
   while($row=mysql_fetch_array($result))
   {
      if($ix>=$start && $ix<$end)
      {
      if($show%10==0 && $show>0)
      {
	 echo "<tr align=center>";
	 echo "<th class=smaller>Select Player</th><th class=smaller>Nickname<br>(How first name should show<br>in STATE PROGRAM, if different<br>from Column 1, e.g. \"Tom\")</th>";
	 echo "<th class=smaller>Pronunciation<br>(Mau-Er)</th>";
	 echo "<th class=smaller>Grade</th>";
	 echo "<th class=smaller>Light<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>Dark<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>S</th>";
	 echo "<th class=smaller>M</th>";
	 echo "<th class=smaller>Offensive<br>Position</th>";
	 echo "<th class=smaller>Defensive<br>Position</th>";
	 echo "<th class=smaller>Height</th>";
	 echo "<th class=smaller>Weight</th>";
	 echo "</tr>";
      }
      echo "<tr align=center";
     if(($show%2)==0) echo " bgcolor='#f0f0f0'";
      echo ">";
      echo "<td";
      if($row[eligible]!='y') echo " bgcolor=red";
      echo "><select name=\"player[$show]\"><option>Choose Player";
      for($i=0;$i<count($players[0]);$i++)
      {
	 $id=$players[0][$i];
	 $name=$players[1][$i];
	 echo "<option value=$id";
	 if($row[1]==$id) echo " selected";
	 echo ">$name";
      }
      echo "</select></td>";
      echo "<input type=hidden name=\"id[$show]\" value=$row[0]>";
      echo "<td><input type=text name=\"nickname[$show]\" size=20 value=\"$row[nickname]\"></td>";
      echo "<td><input type=text name=\"pronunciation[$show]\" size=20 value=\"$row[3]\"></td>";
      $year=GetYear($row[semesters]);
      echo "<td>$year</td>";
      echo "<td><input type=text name=\"jersey_lt[$show]\" size=3 value=\"$row[4]\"></td>";
      echo "<td><input type=text name=\"jersey_dk[$show]\" size=3 value=\"$row[5]\"></td>";
      echo "<td><input type=checkbox name=\"starter[$show]\" value=y";
      if($row[6]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"medalist[$show]\" value=y";
      if($row[7]=='y') echo " checked";
      echo "></td>";
      echo "<td><select name=\"off_posn[$show]\"><option>~";
      for($i=0;$i<count($fb_off_posns);$i++)
      {
	 echo "<option";
	 if($fb_off_posns[$i]==$row[8]) echo " selected";
	 echo ">$fb_off_posns[$i]";
      }
      echo "</select></td>";
      echo "<td><select name=\"def_posn[$show]\"><option>~";
      for($i=0;$i<count($fb_def_posns);$i++)
      {
	 echo "<option";
	 if($fb_def_posns[$i]==$row[9]) echo " selected";
	 echo ">$fb_def_posns[$i]";
      }
      echo "</select></td>";
      //get height into separate feet and inches
      $height=split("-",$row[10]);
      $ft=$height[0];
      $in=$height[1];
      echo "<td><input type=text name=\"height_ft[$show]\" size=2 value=$ft>&nbsp;ft&nbsp;";
      echo "<input type=text name=\"height_in[$show]\" size=2 value=$in>&nbsp;in</td>";
      echo "<td><input type=text name=\"weight[$show]\" size=3 value=$row[11]>&nbsp;lbs</td>";
      echo "</tr>";
      $show++;
      }
      $ix++;
   }
   $ct=$end-$show;
   for($i=0;$i<$ct;$i++)
   {
      if($show%10==0 && $show>0)
      {
         echo "<tr align=center>";
         echo "<th class=smaller>Select Player</th><th class=smaller>Nickname<br>(How first name should show<br>in STATE PROGRAM, if different<br>from Column 1, e.g. \"Tom\")</th>";
         echo "<th class=smaller>Pronunciation<br>(Mau-Er)</th>";
	 echo "<th class=smaller>Grade</th>";
	 echo "<th class=smaller>Light<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>Dark<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>S*</th>";
	 echo "<th class=smaller>M*</th>";
	 echo "<th class=smaller>Offensive<br>Position</th>";
	 echo "<th class=smaller>Defensive<br>Position</th>";
	 echo "<th class=smaller>Height</th>";
	 echo "<th class=smaller>Weight</th>";
	 echo "</tr>";
      }
      echo "<tr align=center";
     if(($show%2)==0) echo " bgcolor='#f0f0f0'";
      echo ">";
      echo "<td><select name=\"player[$show]\"><option>Choose Player";
      for($j=0;$j<count($players[0]);$j++)
      {
	 $id=$players[0][$j];
	 $name=$players[1][$j];
	 echo "<option value=$id>$name";
      }
      echo "</select></td><td><input type=text name=\"nickname[$show]\" size=20></td>";
      echo "<td><input type=text name=\"pronunciation[$show]\" size=20></td>";
      echo "<td></td>";
      echo "<td><input type=text name=\"jersey_lt[$show]\" size=3></td>";
      echo "<td><input type=text name=\"jersey_dk[$show]\" size=3></td>";
      echo "<td><input type=checkbox name=\"starter[$show]\" value=y></td>";
      echo "<td><input type=checkbox name=\"medalist[$show]\" value=y></td>";
      echo "<td><select name=\"off_posn[$show]\"><option>~";
      for($j=0;$j<count($fb_off_posns);$j++)
      {
	 echo "<option>$fb_off_posns[$j]";
      }
      echo "</select></td>";
      echo "<td><select name=\"def_posn[$show]\"><option>~";
      for($j=0;$j<count($fb_def_posns);$j++)
      {
	 echo "<option>$fb_def_posns[$j]";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"height_ft[$show]\" size=2>&nbsp;ft&nbsp;";
      echo "<input type=text name=\"height_in[$show]\" size=2>&nbsp;in</td>";
      echo "<td><input type=text name=\"weight[$show]\" size=3>&nbsp;lbs</td>";
      echo "</tr>";
      $show++;
   }
   ?>
   </table>
</td>
</tr>
<tr align=center>
<td><div style="width:800px;text-align:left;">
<?php
if($start==0)
{
   echo "<a href=\"edit_fb_state.php?session=$session&school_ch=$school_ch&start=25\">View Next 25 Entries--></a><b>&nbsp;&nbsp;...Make sure to save any changes before moving on!</b>";
}
else
{
   $prev=$start-25; $next=$start+25;
   echo "<a href=\"edit_fb_state.php?session=$session&school_ch=$school_ch&start=$prev\"><--View Previous 25 Entries</a>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"edit_fb_state.php?session=$session&school_ch=$school_ch&start=$next\">View Next 25 Entries--></a>&nbsp;&nbsp;<b>...Make sure to save any changes before moving on!</b>";
}
?>
</div>
</td>
</tr>
<tr align=center>
<td><div style="width:800px;text-align:left;">
   * Check the box in the column labeled <b>"S"</b> if that student is a starter.  Check the box in the column labeled <b>"M"</b> if that student is a medalist.  NOTE: You may only check 22 starters.
   <br>
 <font style="color:red">Students listed in red are currently <b>ineligible</b>.
 Please make sure they will be eligible for districts before submitting them on
 this form.</font>
</div>
</td>
</tr>
<tr align=center>
<th><br><div style="width:800px;text-align:left;">
   <input type=checkbox name=send value=y>
   Check this box when you have finished updating information and wish to make your final submission of this form.
</div>
</th>
</tr>
<tr align=center>
<td>
<div style="width:800px;text-align:left;"><p><i><?php echo $certify; ?></u></p></div>
</td>
</tr>
<tr align=center>
<td>
   <input type=hidden name=start value=<?php echo $start; ?>>
   <input type=submit name=submit value="Save & Keep Editing">
   <input type=submit name=submit value="Save & View Form">
   <input type=submit name=submit value="Cancel">
</td>
</tr>

</table>
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
