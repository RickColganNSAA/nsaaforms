<?php
//edit_jo.php: edit journalism form
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

$level=GetLevel($session);

if($school_ch && $level==1)
   $school=$school_ch;
else
   $school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);

$joevents=array();
$sql="SELECT * FROM jocategories WHERE showplace=1 ORDER BY category";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   $joevents[$i]=$row[category]; $i++;
}

//NON-STATE JO EVENTS:
$joevents2=array(); $joeventids2=array();
$sql="SELECT * FROM jocategories WHERE showplace=0 ORDER BY category";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   $joeventids2[$i]=$row[id];
   $joevents2[$i]=$row[category]; $i++;
}

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

echo $init_html;
echo GetHeader($session);

$duedate=GetDueDate("jo");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

$form_type="State";
$state=1;
$table="jo";

if(PastDue($duedate,0) && $level!=1)
{
//CHECK IF IT >2, (lock if so)
   $late_page=GetLatePage($duedate2);
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM jo WHERE school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_jo.php?session=$session&school_ch=$school_ch\">";
      echo "View your Submitted Form</a>";
   }
   else
   {
      echo "<font size=2>";
      echo "No information was submitted for your state entry.<br>";
      echo "If this was a mistake, please contact the NSAA immediately!";
      echo "<br><br>";
      echo "<a href=\"../welcome.php?session=$session\">Return Home</a></font>";
   }
   exit();
}

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Journalism\">Return to Home-->Journalism Entry Forms</a><br>";

//School/Team Information:
$sid=GetSID2($school,$sport);
$coach=GetCoaches($schoolid,$sport);
$asst=GetAsstCoaches($schoolid,$sport);
$directorcell=GetCoachCell($schoolid,$sport);
$mascot=GetMascot($schoolid,$sport);
$colors=GetColors($schoolid,$sport);
$class=GetClass($sid,$sport,"","joschool");

//Get information already saved
$sql="SELECT t1.* FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2' ORDER BY t2.last"; 
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_id[$ix]=$row[1];
   $cur_event1[$ix]=$row[4];
   $cur_event2[$ix]=$row[5];
   $cur_checked[$ix]=$row[3];
   if(ereg("News/Feature Photography",$cur_event1[$ix]) || ereg("News/Feature Photography",$cur_event2[$ix]))
   {
      if(ereg("News/Feature Photography",$cur_event1[$ix]))
      {
         $temp=split(",",$cur_event1[$ix]);
         $cur_event1[$ix]=$temp[0];
         $cur_phototype1[$ix]=$temp[1];
         $cur_storage1[$ix]=$temp[2];
      }
      else
      {
	 $temp=split(",",$cur_event2[$ix]);
	 $cur_event2[$ix]=$temp[0];
         $cur_phototype2[$ix]=$temp[1];
         $cur_storage2[$ix]=$temp[2];
      }
   }
   $ix++;
}
?>
<br><h1><?php echo $form_type; ?> Journalism Contest Entry Form</h1>
   <form method=post action="submit_jo.php">
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">

<table cellspacing=0 cellpadding=5 class='nine' style="border:#80808 1px solid;" frame="all" rules="all">
<caption style="text-align:left;">
<p><b>School/Mascot:</b><?php echo GetSchoolName($sid,'jo')." $mascot"; ?></p>
<p><b>Colors:</b> <?php echo $colors; ?></p>
<p><b>Class:</b> <?php echo $class; ?></p>
<p><b>Director:</b> <?php echo $coach; ?></p>
<p><b>Director's Cell Phone:</b> <input type=text name="directorcell" size=15 value="<?php echo $directorcell; ?>"></p>
<p><b>Assistant(s):</b> <input type=text name="asst" size=50 value="<?php echo $asst; ?>"></p>
      <?php
            $sql = "SELECT * FROM `instructions` WHERE type='preliminary'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
		
            ?>
				<?php echo $row['school_entry_form']; ?>
   </caption>
   <tr align=center>
   <th>Name/Grade</th>
   <th colspan=2>Event(s)</th>
   </tr>
   <?php
//Get all Journalism participants, boys and girls
$studs=explode("<result>",GetPlayers($sport,$school));
$i=0;
for($s=0;$s<count($studs);$s++)
{
   $stud=explode("<detail>",$studs[$s]);        //id, name/grade, ?, eligible
   if($i%10==0 && $i>0)
      echo "<tr align=center><th>Name/Grade</th><th colspan=2>Event(s)</th></tr>";
   for($j=0;$j<count($cur_id);$j++)
   {
      if($cur_id[$j]==$stud[0])
      {
         $submitted=1;
         $index=$j;
      }
   }
   echo "<tr align='left'>";
   /*
   echo "<td><input type=checkbox name=\"check[$i]\" value=y";
   if($submitted==1 && $cur_checked[$index]=='y') echo " checked";
   echo "></td>";
   */
   echo "<td width='350px'";
   if($stud[3]!='y') echo " bgcolor='red'";
   echo "><h3>$stud[1]</h3>";
 	//CHECK TO SEE IF HE/SHE QUALIFIED IN A STATE THAT JUST SHOWS UP FOR MEDAL CEREMONY:
      $sql2="SELECT t1.* FROM joentries AS t1, joqualifiers AS t2 WHERE t2.entryid=t1.id AND (t1.studentid='$stud[0]' OR t1.studentid2='$stud[0]' OR t1.studentid3='$stud[0]' OR t1.studentid4='$stud[0]' OR t1.studentid5='$stud[0]' OR t1.studentid6='$stud[0]') AND (";
      for($j=0;$j<count($joevents2);$j++)
      {
	 $sql2.="t2.catid='$joeventids2[$j]' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4).")";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         echo "<p>(<b>PLEASE NOTE:</b> <i>The overall top 7 place winners for Sports Photography, Yearbook Theme Development and In-Depth Newspaper Coverage are invited to the awards ceremony to receive their medals. You do NOT need to select any of these events for this student on this form.)</i></p>";
      }
   echo "<input type=hidden name=\"student[$i]\" value=\"$stud[0]\"></td>";
   echo "<td><select name=\"event1[$i]\">";
   echo "<option>~";
   for($j=0;$j<count($joevents);$j++)
   {
      echo "<option";
      if($submitted==1 && $joevents[$j]==$cur_event1[$index])
 	 echo " selected";
      echo ">$joevents[$j]";
   }
   echo "</select>";
   
      //echo "<p><b>For <u>News/Feature Photography:</u></b> <input type=radio name=\"phototype1[$i]\" value='film'";
      //if($cur_phototype1[$index]=='film') echo " checked";
      //echo "> Film&nbsp;&nbsp;";
      //echo "<input type=radio name=\"phototype1[$i]\" value='digital'";
      //if($cur_phototype1[$index]=='digital') echo " checked";
      //echo "> Digital</p>";
      //echo "<p style=\"font-size:95%;\"><i>If <b><u>Digital</b></u>, indicate storage media:</i> <input type=text name=\"storage1[$i]\" size=20 value=\"$cur_storage1[$index]\"></p>";
     
   echo "</td><td><select name=\"event2[$i]\">";
   echo "<option>~";
   for($j=0;$j<count($joevents);$j++)
   {
      echo "<option";
      if($submitted==1 && $joevents[$j]==$cur_event2[$index])
 	 echo " selected";
      echo ">$joevents[$j]";
   }
   echo "</select>";
  
      //echo "<p><b>For <u>News/Feature Photography</u>:</b><input type=radio name=\"phototype2[$i]\" value='film'";
      //if($cur_phototype2[$index]=='film') echo " checked";
      //echo "> Film&nbsp;&nbsp;";
      //echo "<input type=radio name=\"phototype2[$i]\" value='digital'";
      //if($cur_phototype2[$index]=='digital') echo " checked";
      //echo "> Digital";
      //echo "<p style=\"font-size:95%;\"><i>If <b><u>Digital</b></u>, indicate storage media:</i> <input type=text name=\"storage2[$i]\" size=20 value=\"$cur_storage2[$index]\"></p>";
  
   echo "</td></tr>";
   $i++;
}
?>
</table>
<p style="color:red">Students listed in red are currently <b>ineligible</b>.
 Please make sure they will be eligible for the <?php echo strtolower($form_type); ?> contest before submitting them on this form.</p>
<p><input type=checkbox name="send" value=y>
Check this box if you want to submit the above information as your final state entry.  You have not officially submitted your entry until you have checked this box and clicked one of the two "Save" buttons below!</p>
<p><i><?php echo $certify; ?></i></p>
<p>
   <input type=submit name=save value="Save & Keep Editing">
   &nbsp;
   <input type=submit name=save value="Save & View Form">
   &nbsp;
   <input type=submit name=save value="Cancel">
</p>
</form>
<?php echo $end_html; ?>
