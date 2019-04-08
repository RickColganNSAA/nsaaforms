<?php
//edit_fb_stats.php: optional stats report form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo GetHeader($session);

$level=GetLevel($session);

if($level==1)
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

//get mascot
$sql="SELECT mascot,color_names FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[0]; $colors=$row[1];

//get coach, asst coaches
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport LIKE 'Football%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];
?>
<a class=small href="view_fb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Back to Football Main Page</a>
<br><br>
<table cellspacing=0 cellpadding=0><!--Big Table to Hold the Sub-Tables-->
<caption><b>NSAA Football Statistics Report</b></caption>
<tr align=center>
<td>
   <table>
   <tr align=left>
   <td>(1)&nbsp;Select the names of your top players in each category.</td>
   </tr>
   <tr align=left>
   <td>(2)&nbsp;Enter or update the <i>cumulative</i> statistics for each player selected.</td>
   </tr>
   <tr align=left>
   <td>(3)&nbsp;Don't forget to <b>SAVE</b>!</td>
   </tr>
   </table>
</td>
</tr>
<tr align=center>
<td><hr></td>
</tr>
   <form method=post action="submit_fb_stats.php">
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<tr align=center>
<td>
   <table><!--School Name and Class-->
   <tr align=left>
   <th>School/Mascot:</th>
   <td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'fb');
$sql="SELECT * FROM fbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
echo GetSchoolName($sid,'fb')." $mascot";
   ?>
   </td>
   <tr align=left>
   <th>Colors:</th>
   <td><?php echo $colors; ?></td>
   </tr>
   <tr align=left>
   <th>NSAA-Certified Coach:</th>
   <td><?php echo $coach; ?></td>
   </tr>
   <tr align=left>
   <th>Assistant Coaches:</th>
   <td><input type=text name=asst value="<?php echo $asst; ?>" size=50></td>
   </tr>
   <tr align=left>
   <th>Class:</th>
   <td><select name=class>
	  <option>Choose
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
   </table>
</td>
</tr>
<tr align=center>
<td>
   <table width=100% cellspacing=2 cellpadding=2 border=1 bordercolor=#000000>
   <caption align=left><b><font size=2>Offensive Statistics:</font></caption>
   <!--Offensive Stats Table-->
   <tr align=center>
   <th class=smaller rowspan=2>Starter</th>
   <th class=smaller rowspan=2>Player<br>(Last, First M)</th>
   <th class=smaller rowspan=2>Light<br>Jersey<br>No.</th>
   <th class=smaller rowspan=2>Dark<br>Jersey<br>No.</th>
   <th class=smaller rowspan=2>Total<br>TDs</th>
   <th class=smaller rowspan=2>Total Pts<br>Scored</th>
   <th class=smaller colspan=3>Rushing</th>
   <th class=smaller colspan=3>Receiving</th>
   </tr>
   <tr align=center>
   <th class=smaller>Carries</th>
   <th class=smaller>Yards</th>
   <th class=smaller>TDs</th>
   <th class=smaller>Catches</th>
   <th class=smaller>Yards</th>
   <th class=smaller>TDs</th>
   </tr>
   <?php
   $sql="SELECT t1.*,t2.last, t2.first, t2.middle FROM fb_stat_off AS t1, eligibility AS t2 WHERE t2.id=t1.student_id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last";
   $result=mysql_query($sql);
   //get list of FB players from this school
   $studs=explode("<result>",GetPlayers($sport,$school));
   $ix=0;
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      $players[0][$ix]=$stud[0];
      $players[1][$ix]=$stud[1];
      $players[2][$ix]=$stud[2];
      $ix++;
   }
   //get co_op players
	/*
   $sql2="SELECT t1.* FROM eligibility AS t1, fb_coop AS t2 WHERE t1.id=t2.student_id AND co_op='$school2' ORDER BY t1.last";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $players[0][$ix]=$row2[0];
      $players[1][$ix]="$row2[2], $row2[3] $row2[4]";
      $ix++;
   }
	*/

   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"starter[$ix]\" value=y";
      if($row[2]=='y') echo " checked";
      echo "></td>";
      echo "<td><select name=\"student[$ix]\">";
      echo "<option>Choose Player";
      for($i=0;$i<count($players[0]);$i++)
      {
	 $id=$players[0][$i];
	 $name=$players[1][$i];
	 echo "<option value=$id";
	 if($id==$row[1]) echo " selected";
	 echo ">$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"jersey_lt[$ix]\" size=2";
      echo " value=$row[3]></td>";
      echo "<td><input type=text name=\"jersey_dk[$ix]\" size=2";
      echo " value=$row[4]></td>";
      echo "<td><input type=text name=\"total_tds[$ix]\" size=2 value=$row[5]></td>";
      echo "<td><input type=text name=\"total_pts[$ix]\" size=2 value=$row[6]></td>";
      echo "<td><input type=text name=\"rush_carry[$ix]\" size=2 value=$row[7]></td>";
      echo "<td><input type=text name=\"rush_yds[$ix]\" size=2 value=$row[8]></td>";
      echo "<td><input type=text name=\"rush_tds[$ix]\" size=2 value=$row[9]></td>";
      echo "<td><input type=text name=\"rec_catch[$ix]\" size=2 value=$row[10]></td>";
      echo "<td><input type=text name=\"rec_yds[$ix]\" size=2 value=$row[11]></td>";
      echo "<td><input type=text name=\"rec_tds[$ix]\" size=2 value=$row[12]></td>";
      echo "</tr>";
      $ix++;
   }
   $ct=15-$ix;
   for($i=0;$i<$ct;$i++)
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"starter[$ix]\" value=y></td>";
      echo "<td><select name=\"student[$ix]\">";
      echo "<option>Choose Player";
      for($j=0;$j<count($players[0]);$j++)
      {
	 $id=$players[0][$j];
	 $name=$players[1][$j];
	 echo "<option value=$id>$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"jersey_lt[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"jersey_dk[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"total_tds[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"total_pts[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"rush_carry[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"rush_yds[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"rush_tds[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"rec_catch[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"rec_yds[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"rec_tds[$ix]\" size=2></td>";
      echo "</tr>";
      $ix++;
   }
   ?>
   </table>
</td>
</tr>
<tr align=center>
<td><br>
   <table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=2>
   <caption align=left><font size=2><b>Passing Statistics:</b></font></caption>
   <tr align=center>
   <th class=smaller>Starter</th>
   <th class=smaller>Player<br>(Last, First M)</th>
   <th class=smaller>Light<br>Jersey<br>No.</th>
   <th class=smaller>Dark<br>Jersey<br>No.</th>
   <th class=smaller>Comp</th>
   <th class=smaller>Attempts</th>
   <th class=smaller>Yards</th>
   <th class=smaller>TDs</th>
   <th class=smaller>Interceptions</th>
   </tr>
   <?php
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_qb AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"qb_starter[$ix]\" value=y";
      if($row[2]=='y') echo " checked";
      echo "></td>";
      echo "<td><select name=\"qb_student[$ix]\">";
      echo "<option>Choose Player";
      for($i=0;$i<count($players[0]);$i++)
      {
	 $id=$players[0][$i];
	 $name=$players[1][$i];
	 echo "<option value=$id";
	 if($row[1]==$id) echo " selected";
	 echo ">$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"qb_jersey_lt[$ix]\" size=2 value=$row[3]></td>";
      echo "<td><input type=text name=\"qb_jersey_dk[$ix]\" size=2 value=$row[4]></td>";
      echo "<td><input type=text name=\"qb_comp[$ix]\" size=2 value=$row[5]></td>";
      echo "<td><input type=text name=\"qb_attempts[$ix]\" size=2 value=$row[6]></td>";
      echo "<td><input type=text name=\"qb_yds[$ix]\" size=2 value=$row[7]></td>";
      echo "<td><input type=text name=\"qb_tds[$ix]\" size=2 value=$row[8]></td>";
      echo "<td><input type=text name=\"qb_int[$ix]\" size=2 value=$row[9]></td>";
      echo "</tr>";
      $ix++;
   }
   $ct=5-$ix;
   for($i=0;$i<$ct;$i++)
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"qb_starter[$ix]\" value=y></td>";
      echo "<td><select name=\"qb_student[$ix]\">";
      echo "<option>Choose Player";
      for($j=0;$j<count($players[0]);$j++)
      {
	 $id=$players[0][$j];
  	 $name=$players[1][$j];
  	 echo "<option value=$id>$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"qb_jersey_lt[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"qb_jersey_dk[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"qb_comp[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"qb_attempts[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"qb_yds[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"qb_tds[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"qb_int[$ix]\" size=2></td>";
      echo "</tr>";
      $ix++;
   }
   ?>
   </table>
</td>
</tr>
<tr align=center>
<td><br>
   <table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=2>
   <caption align=left><font size=2><b>Punting Statistics:</b></font></caption>
   <tr align=center>
   <th class=smaller>Starter</th>
   <th class=smaller>Player<br>(Last, First M)</th>
   <th class=smaller>Light<br>Jersey<br>No.</th>
   <th class=smaller>Dark<br>Jersey<br>No.</th>
   <th class=smaller>Attempts</th>
   <th class=smaller>Yards</th>
   <th class=smaller>Average</th>
   <th class=smaller>Longest</th>
   </tr>
   <?php
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_kick AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"k_starter[$ix]\" value=y";
      if($row[2]=='y') echo " checked";
      echo "></td>";
      echo "<td><select name=\"k_student[$ix]\">";
      echo "<option>Choose Player";
      for($i=0;$i<count($players[0]);$i++)
      {
         $id=$players[0][$i];
    	 $name=$players[1][$i];
	 echo "<option value=$id";
	 if($row[1]==$id) echo " selected";
	 echo ">$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"k_jersey_lt[$ix]\" size=2 value=$row[3]></td>";
      echo "<td><input type=text name=\"k_jersey_dk[$ix]\" size=2 value=$row[4]></td>";
      echo "<td><input type=text name=\"k_attempts[$ix]\" size=2 value=$row[5]></td>";
      echo "<td><input type=text name=\"k_yds[$ix]\" size=2 value=$row[6]></td>";
      echo "<td><input type=text name=\"k_avg[$ix]\" size=2 value=$row[7]></td>";
      echo "<td><input type=text name=\"k_longest[$ix]\" size=2 value=$row[8]></td>";
      echo "</tr>";
      $ix++;
   }
   $ct=5-$ix;
   for($i=0;$i<$ct;$i++)
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"k_starter[$ix]\" value=y></td>";
      echo "<td><select name=\"k_student[$ix]\">";
      echo "<option>Choose Player";
      for($j=0;$j<count($players[0]);$j++)
      {
          $id=$players[0][$j];
  	  $name=$players[1][$j];
	  echo "<option value=$id>$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"k_jersey_lt[$ix]\" size=2>";
      echo "<td><input type=text name=\"k_jersey_dk[$ix]\" size=2>";
      echo "<td><input type=text name=\"k_attempts[$ix]\" size=2>";
      echo "<td><input type=text name=\"k_yds[$ix]\" size=2>";
      echo "<td><input type=text name=\"k_avg[$ix]\" size=2>";
      echo "<td><input type=text name=\"k_longest[$ix]\" size=2></td>";
      echo "</tr>";
      $ix++;
   }
?>
   </table>
</td>
</tr>
</td>
</tr>
<tr align=center>
<td><br>
   <table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=2>
   <caption align=left><font size=2><b>Place-Kicking Statistics:</b></font>
   </caption>
   <!--Place-Kicking Table-->
   <tr align=center>
   <th class=smaller rowspan=2>Starter</th>
   <th class=smaller rowspan=2>Player<br>(Last, First M)</th>
   <th class=smaller rowspan=2>Light<br>Jersey<br>No.</th>
   <th class=smaller rowspan=2>Dark<br>Jersey<br>No.</th>
   <th class=smaller colspan=2>Point After TD</th>
   <th class=smaller colspan=2>Field Goals</th>
   <th class=smaller rowspan=2>Longest</th>
   </tr>
   <tr align=center>
   <th class=smaller>Att</th><th class=smaller>Good</th>
   <th class=smaller>Att</th><th class=smaller>Good</th>
   </tr>
<?php
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_pk AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"pk_starter[$ix]\" value=y";
      if($row[2]=='y') echo " checked";
      echo "></td>";
      echo "<td><select name=\"pk_student[$ix]\">";
      echo "<option>Choose Player";
      for($i=0;$i<count($players[0]);$i++)
      {
 	 $id=$players[0][$i];
	 $name=$players[1][$i];
	 echo "<option value=$id";
	 if($row[1]==$id) echo " selected";
	 echo ">$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"pk_jersey_lt[$ix]\" size=2 value=$row[3]></td>";
      echo "<td><input type=text name=\"pk_jersey_dk[$ix]\" size=2 value=$row[4]></td>";
      echo "<td><input type=text name=\"pk_pat_att[$ix]\" size=2 value=$row[5]></td>";
      echo "<td><input type=text name=\"pk_pat_good[$ix]\" size=2 value=$row[6]></td>";
      echo "<td><input type=text name=\"pk_fg_att[$ix]\" size=2 value=$row[7]></td>";
      echo "<td><input type=text name=\"pk_fg_good[$ix]\" size=2 value=$row[8]></td>";
      echo "<td><input type=text name=\"pk_longest[$ix]\" size=2 value=$row[9]></td>";
      echo "</tr>";
      $ix++;
   }
   $ct=5-$ix;
   for($i=0;$i<$ct;$i++)
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"pk_starter[$ix]\" value=y></td>";
      echo "<td><select name=\"pk_student[$ix]\">";
      echo "<option>Choose Player";
      for($j=0;$j<count($players[0]);$j++)
      {
          $id=$players[0][$j];
	  $name=$players[1][$j];
	  echo "<option value=$id>$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"pk_jersey_lt[$ix]\" size=2>";
      echo "<td><input type=text name=\"pk_jersey_dk[$ix]\" size=2>";
      echo "<td><input type=text name=\"pk_pat_att[$ix]\" size=2>";
      echo "<td><input type=text name=\"pk_pat_good[$ix]\" size=2>";
      echo "<td><input type=text name=\"pk_fg_att[$ix]\" size=2>";
      echo "<td><input type=text name=\"pk_fg_good[$ix]\" size=2>";
      echo "<td><input type=text name=\"pk_longest[$ix]\" size=2>";
      echo "</tr>";
      $ix++;
   }
?>
   </table>
</td></tr>
<tr align=center>
<td><br>
   <table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=2>
   <caption align=left><font size=2><b>Defensive Statistics:</b></font>
   </caption>
   <!--Defensive Table-->
   <tr align=center>
   <th class=smaller rowspan=2>Starter</th>
   <th class=smaller rowspan=2>Player<br>(Last, First M)</th>
   <th class=smaller rowspan=2>Light<br>Jersey<br>No.</th>
   <th class=smaller rowspan=2>Dark<br>Jersey<br>No.</th>
   <th class=smaller colspan=3>Tackles</th>
   <th class=smaller rowspan=2>QB<br>Sacks</th>
   <th class=smaller rowspan=2>Passes<br>Intercepted</th>
   <th class=smaller rowspan=2>Blocked<br>Kicks</th>
   <th class=smaller rowspan=2>Fumble<br>Recoveries</th>
   </tr>
   <tr align=center>
   <th class=smaller>Solo</th>
   <th class=smaller>Assisted</th>
   <th class=smaller>Total</th>
   </tr>
   <?php
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_def AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"d_starter[$ix]\" value=y";
      if($row[2]=='y') echo " checked";
      echo "></td>";
      echo "<td><select name=\"d_student[$ix]\">";
      echo "<option>Choose Player";
      for($i=0;$i<count($players[0]);$i++)
      {
         $id=$players[0][$i];
         $name=$players[1][$i];
         echo "<option value=$id";
  	 if($id==$row[1]) echo " selected";
	 echo ">$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"d_jersey_lt[$ix]\" size=2";
      echo " value=$row[3]></td>";
      echo "<td><input type=text name=\"d_jersey_dk[$ix]\" size=2";
      echo " value=$row[4]></td>";
      echo "<td><input type=text name=\"d_tackles_solo[$ix]\" size=2 value=$row[5]></td>";
      echo "<td><input type=text name=\"d_tackles_asst[$ix]\" size=2 value=$row[6]></td>";
      echo "<td><input type=text name=\"d_tackles_totl[$ix]\" size=2 value=$row[7]></td>";
      echo "<td><input type=text name=\"d_sacks[$ix]\" size=2 value=$row[8]></td>";
      echo "<td><input type=text name=\"d_intercepts[$ix]\" size=2 value=$row[9]></td>";
      echo "<td><input type=text name=\"d_blocks[$ix]\" size=2 value=$row[10]></td>";
      echo "<td><input type=text name=\"d_fumbles[$ix]\" size=2 value=$row[11]></td>";
      echo "</tr>";
      $ix++;
   }
   $ct=15-$ix;
   for($i=0;$i<$ct;$i++)
   {
      echo "<tr align=center>";
      echo "<td><input type=checkbox name=\"d_starter[$ix]\" value=y></td>";
      echo "<td><select name=\"d_student[$ix]\">";
      echo "<option>Choose Player";
      for($j=0;$j<count($players[0]);$j++)
      {
         $id=$players[0][$j];
         $name=$players[1][$j];
         echo "<option value=$id>$name";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"d_jersey_lt[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_jersey_dk[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_tackles_solo[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_tackles_asst[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_tackles_totl[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_sacks[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_intercepts[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_blocks[$ix]\" size=2></td>";
      echo "<td><input type=text name=\"d_fumbles[$ix]\" size=2></td>";
      echo "</tr>";
      $ix++;
   }
   ?>
   </table>
</td>
</tr>
<tr align=center>
<td><br>
   <table border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>
   <!--Team Stats-->
   <caption><b>Team Statistics:</b></caption>
   <tr align=center>
   <th></th>
   <th class=smaller>Points<br>Scored</th>
   <th class=smaller>Rushing<br>Yards</th>
   <th class=smaller>Passing<br>Yards</th>
   <th class=smaller>Total<br>Offense</th>
   </tr>
   <tr align=left>
   <th>Your Team Totals:</th>
   <?php
   $sql="SELECT t1.* FROM fb_team AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td><input type=text name=pts size=5 value=\"$row[2]\"></td>";
   echo "<td><input type=text name=r_yds size=5 value=\"$row[3]\"></td>";
   echo "<td><input type=text name=p_yds size=5 value=\"$row[4]\"></td>";
   echo "<td><input type=text name=total size=5 value=\"$row[5]\"></td>";
   echo "</tr>";
   echo "<tr align=left><th>Opponents' Totals:</th>";
   echo "<td><input type=text name=opp_pts size=5 value=\"$row[6]\"></td>";
   echo "<td><input type=text name=opp_r_yds size=5 value=\"$row[7]\"></td>";
   echo "<td><input type=text name=opp_p_yds size=5 value=\"$row[8]\"></td>";
   echo "<td><input type=text name=opp_total size=5 value=\"$row[9]\"></td>";
   echo "</tr>";
   ?>
   </table>
</td>
</tr>
<tr align=center>
<td><br>
   <table>
   <caption align=left><b>Playoff Records Broken in Your Games:</b>
   <br><font size=2>(Please report one record at a time)</caption>
   <?php
   //create array of schools
   $schools=array();
   $ix=0;
   $sql="SELECT id,school FROM headers ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $schools[0][$ix]=$row[0];
      $schools[1][$ix]=$row[1];
      $ix++;
   }

   $sql="SELECT t1.* FROM fb_records AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2' ORDER BY date";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($ix%2==0)
      {
         echo "<tr align=left>";
      }
      echo "<td><table>";
      echo "<tr align=left>";
      echo "<td><input type=hidden name=\"recordid[$ix]\" value=\"$row[0]\">";
      echo "<select name=\"opp[$ix]\">";
      echo "<option>Choose Opponent";
      for($i=0;$i<count($schools[0]);$i++)
      {
	 $id=$schools[0][$i];
	 $name=$schools[1][$i];
	 echo "<option value=$id";
	 if($id==$row[2]) echo " selected";
	 echo ">$name";
      }
      echo "</select></td></tr>";
      echo "<tr align=left><td><select name=\"month[$ix]\">";
      $date=date("F d Y",$row[3]);
      $date=split(" ",$date);
      $month=trim($date[0]);
      $day=trim($date[1]);
      $year=trim($date[2]);
      if($year=="") $year=date(Y);
      for($i=0;$i<count($months2);$i++)
      {
	 $m=$i+1;
	 echo "<option value=$m";
	 if($month==$months2[$i]) echo " selected";
	 echo ">$months2[$i]";
      }
      echo "</select>";
      echo "<input type=text name=\"day[$ix]\" size=2 value=\"$day\">";
      echo "<input type=text name=\"year[$ix]\" size=2 value=\"$year\"></td>";
      echo "</tr>";
      echo "<tr align=center><td>";
      echo "<textarea cols=40 rows=3 name=\"record[$ix]\">$row[4]</textarea>";
      echo "</td></tr>";
      echo "</table>";
      echo "</td>";
      if($ix%2!=0)
      {
         echo "</tr>";
      }
      $ix++;
   }
   while($ix<16)
   {
      if($ix%2==0)
      {
         echo "<tr align=left>";
      }
      echo "<td><table><tr align=left>";
      echo "<td><select name=\"opp[$ix]\">";
      echo "<option>Choose Opponent";
      for($i=0;$i<count($schools[0]);$i++)
      {
	 $id=$schools[0][$i];
	 $name=$schools[1][$i];
	 echo "<option value=$id>$name";
      }
      echo "</select></td></tr>";
      echo "<tr align=left>";
      echo "<td><select name=\"month[$ix]\">";
      for($i=0;$i<count($months2);$i++)
      {
	 $m=$i+1;
	 echo "<option value=$m>$months2[$i]";
      }
      echo "</select>";
      echo "<input type=text name=\"day[$ix]\" size=2 value=1>";
      $year=date(Y);
      echo "<input type=text name=\"year[$ix]\" size=4 value=$year>";
      echo "</td></tr>";
      echo "<tr align=center><td>";
      echo "<textarea name=\"record[$ix]\" cols=40 rows=3></textarea>";
      echo "</td></tr></table></td>";
      if($ix%2!=0)
      {
         echo "</tr>";
      }
      $ix++;
   }
   ?>
   <tr align=left>
   <td>

   </td></tr>
   </table>
</td>
</tr>
</table><!--End Table of Sub-Tables-->
<br>
<input type=submit name=submit value="Save & Keep Editing">
<input type=submit name=submit value="Save & View Form">
<input type=submit name=submit value="Cancel">
</form>
</td>
</tr>
</table>
</body>
</html>
