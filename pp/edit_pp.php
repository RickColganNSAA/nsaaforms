<?php
//edit_pp.php: edit play production form

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

if($school_ch && GetLevel($session)==1)
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
$schoolid=$row[id]; $sport="pp";
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

//get mascot and colors
$sql="SELECT mascot,color_names FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[0]; $colors=$row[1];

echo $init_html;
echo GetHeader($session);

$duedate=GetDueDate("pp");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//AS OF 5/11/10, there is no District and State PP form, just ONE Entry Form, NO DUE DATE
   $state=0;
   $table1="pp";
   $table2="pp_students";

//Get info already submitted for this school
  //play info
$sql="SELECT * FROM $table1 WHERE school='$school2'";
$result=mysql_query($sql);
$sql2="SELECT t1.* FROM $table2 AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2' ORDER BY t2.last";
$result2=mysql_query($sql);
//if first time editing state form, COPY DIST TABLE TO STATE TABLE
if($state==1 && mysql_num_rows($result)==0 && mysql_num_rows($result2)==0)
{
   if(mysql_num_rows($result)==0)
   {
      $sql3="INSERT INTO pp_state SELECT * FROM pp WHERE pp.school='$school2'";
      $result3=mysql_query($sql3);
   }
   if(mysql_num_rows($result2)==0)
   {
      $sql4="INSERT INTO pp_state_students SELECT t1.* FROM pp_students AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
      $result4=mysql_query($sql4);
   }
   $sql="SELECT * FROM $table1 WHERE school='$school2'";
   $result=mysql_query($sql);	//re-read state table
}
$row=mysql_fetch_array($result);
$submitted=mysql_num_rows($result);
$sch_id=$row[school];
$title=$row[title];
$short_title=$row[short_title];
$playwright=$row[playwright];
$director=$row[director];
$time=split(":",$row[5]);
$hrs=$time[0];
$min=$time[1];
$contest_site=$row[7];
$adult=$row[adult];
$royalty=$row[royalty];
$permission=$row[permission];
$weapons=$row[weapons];

//get coach
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND level=3 AND sport='Play Production'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];
$asst=$row[1];

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Play Production\">Return to Home-->Play Production Entry Forms</a><br>";
$date=explode('-',GetDueDate('pp'));
?>
<br>
<form method=post action="submit_pp.php" enctype="multipart/form-data">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=state value="<?php echo $state; ?>">

<font size=2><b>PLAY PRODUCTION CONTEST ENTRY FORM</b></font>
<table><!--Table of Tables-->
<tr align=center>
<td>
<?php if(!$state): ?>
	<div class='alert' style='width:500px;'>
   You do <b><u>NOT</b></u> need to email or otherwise send this form to the director of your Play Production contest. This form will be <b><i>automatically</b></i> sent to the director on the due date, so please make sure the information below is COMPLETE by <b><u>midnight on <?php echo $date[1]."/".$date[2]."/".$date[0]?></b></u>.</div>
<?php endif; ?>
</td>
</tr>
<tr align=center>
<td>

<table class=nine cellspacing=0 cellpadding=2><!--School & Play Info-->
<tr align=left>
<th align=left>School/Mascot:</th><td>
<?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'pp');
$sql="SELECT * FROM ppschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$class=$row['class'];
$filename=$row[filename];
echo GetSchoolName($sid,'pp')." $mascot";
?>
</td>
</tr>
<tr align=left>
<th align=left>Colors:</th>
<td><?php echo $colors; ?></td>
</tr>
<tr align=left>
<th align=left>Class:</th>
<td><?php echo $class; ?></td></tr>
<tr align=left>
<th align=left>NSAA-Certified Coach:</th>
<td><?php echo $coach; ?></td>
</tr>
<tr align=left>
<th align=left>Assistant Coach(es):</th>
<td><input type=text name=asst size=50 value="<?php echo $asst; ?>"></td>
</tr>
<tr align=left>
<th align=left>Title of Play:</th>
<td><textarea rows=1 cols=50 name=title><?php echo $title; ?></textarea></td>
</tr>
<tr align=left>
<th align=left>Short Title:</th>
<td><textarea rows=1 cols=50 name=short_title><?php echo $short_title; ?></textarea></td>
</tr>
<tr align=left>
<th align=left>Written By:</th>
<td><input type=text name=playwright size=50 value="<?php echo $playwright; ?>"></td>
</tr>
<tr align=left>
<th align=left>Director:</th>
<td><input type=text name=director size=50 value="<?php echo $director; ?>"></td>
</tr>
<tr align=left>
<th align=left>Playing Time:</th>
<td><input type=text name=hrs size=2 value="<?php echo $hrs; ?>">
    &nbsp;<font size=3><b>:</b></font>&nbsp;
    <input type=text name=min size=2 value="<?php echo $min; ?>">
</td>
</tr>
<tr align=left>
<th align=left>Contest Site:</th>
<td><input type=text name=contest_site size=50 value="<?php echo $contest_site; ?>">
</td>
</tr>

<!-- ADULT SUBJECT MATTER -->
<tr align=left><td colspan=2><input type=checkbox name='adult' value='x'<?php if($adult=="x"):?> checked<?php endif;?>> Production contains <b>adult subject matter.</b></td></tr>

<!-- SIMULATED WEAPONS -->
<tr align=left><td colspan=2><input type=checkbox name='weapons' value='x'<?php if($weapons=="x"):?> checked<?php endif;?>> Our school utilizes <b>simulated weapons</b> in our production.</td></tr>

<!-- ROYALTY PAYMENTS -->
<tr align=left><td colspan=2><b>If applicable, royalty payments have been paid.</b> <input type=radio name="royalty" value="Yes"<?php if($royalty=="Yes"):?> checked<?php endif; ?>> Yes&nbsp;&nbsp;
<input type=radio name="royalty" value="No"<?php if($royalty=="No"): ?> checked<?php endif; ?>> No&nbsp;&nbsp;
<input type=radio name="royalty" value="NA"<?php if($royalty=="NA"): ?> checked<?php endif; ?>> N/A
<?php if($submitted && !$royalty): ?>
<div class="error">ERROR: You must select Yes, No or N/A above.</div>
<?php endif; ?>
</td></tr>

<!-- PERMISSION TO BROADCAST -->
<tr align=left><td colspan=2><b>The Playwright has granted our school permission to air this production via webcasting/television for educational purposes.</b> <input type=radio name="permission" value="Yes"<?php if($permission=="Yes"):?> checked<?php endif; ?>> Yes&nbsp;&nbsp;<input type=radio name="permission" value="No"<?php if($permission=="No"): ?> checked<?php endif; ?>> No
<?php if($submitted && !$permission): ?>
<div class="error">ERROR: You must select Yes or No above.</div>
<?php endif; ?>
</td></tr>

<!-- PHOTO OF CAST AND CREW -->
   <tr align=left><th>Photo of Cast & Crew:</th><td>
   <?php if($filename!=''): ?>
   <p><a href="/nsaaforms/downloads/<?php echo $filename; ?>" target="_blank">Preview Photo</a></p>
   <?php endif; ?>
   <!--<iframe style="width:430px;height:175px;" src="simpleupload.php?session=<?php echo $session; ?>&sid=<?php echo $sid; ?>" frameborder='0'></iframe><p><i>Once
 your file has finished uploading, click "Save and Keep Editing" at the bottom of this page.</i></p></td></tr>-->
</table><!--End Play & School Info-->
   <input type="file" name="imageUpload" id="imageUpload"></p>
   </td></tr>
</td>
</tr>
<tr align=center>
<td>
<table width=500>
<tr align=left><td>
<font style="color:red"><b>*NOTE: After entering 5 characters or crew members, you will need to hit the "Save & Keep Editing" button to enter more characters or crew members.<br><br></b></font>
<input type=submit name=reset value="Reset" onclick="return confirm('Are you sure you want to RESET all of your cast and crew member entries?')"> (Click "Reset" to reset your cast and crew member entries)
</td></tr>
</table>
   <table cellspacing=0 cellpadding=4 frame=all rules=all style="border:#808080 1px solid;">
   <!--Cast & Crew-->
   <caption><b>Cast:</b>&nbsp;&nbsp;
   <font style="font-size:8pt;color:red"><b>(Please list in order of appearance)</font><br></b>
   </caption>
   <tr align=center>
   <th class=smaller>Character</th>
   <th class=smaller>Student (Grade)</th>
   </tr>
   <?php
   //get all pp participants for this school from db
   $sql="SELECT id, last, first, middle, semesters, eligible FROM eligibility WHERE school='$school2' AND pp='x' ORDER BY last";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   $studs=explode("<result>",GetPlayers('pp',$school));
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      $ppstuds[id][$s]=$stud[0];
      $ppstuds[name][$s]=$stud[1];
      $ppstuds[elig][$s]=$stud[3];
   }

   //now get co-op students
	/*
   $sql="SELECT t1.id, t1.last, t1.first, t1.middle, t1.semesters FROM eligibility AS t1, pp_coop AS t2 WHERE t2.student_id=t1.id AND t2.co_op='$school2' ORDER BY t1.last";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ppstuds[id][$i]=$row[0];
      $ppstuds[name][$i]=$row[1].", ".$row[2]." ".$row[3];
      $ppstuds[year][$i]=GetYear($row[4]);
      $i++;
   }
	*/

   //first show characters already entered
   $sql="SELECT t1.*,t2.eligible FROM $table2 AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t1.school='$school2' AND t1.part IS NOT NULL ORDER BY t1.partorder";
   $result=mysql_query($sql);
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      $row[2]=ereg_replace("\"","'",$row[2]);
      echo "<tr align=center><td><input type=text name=\"part[$i]\" value=\"$row[2]\" size=60></td>";
      echo "<td";
      if($row[8]!='y') echo " bgcolor=red";
      echo "><select name=\"stud[$i]\"><option>Choose Student";
      for($j=0;$j<count($ppstuds[id]);$j++)
      {
	 echo "<option value=\"".$ppstuds[id][$j]."\"";
	 if($ppstuds[id][$j]==$row[1]) echo " selected";
	 echo ">".$ppstuds[name][$j]."</option>";
      }
      echo "</select></td></tr>";
      $partorder=$i+1;
      echo "<input type=hidden name=\"order[$i]\" value=\"$partorder\">";
      $i++;
   }

   //now show spaces for 5 more characters
   $max=$i+5;
   while($i<$max)
   {
      echo "<tr align=center><td><input type=text name=\"part[$i]\" value=\"\" size=60></td>";
      echo "<td><select name=\"stud[$i]\"><option>Choose Student";
      for($j=0;$j<count($ppstuds[id]);$j++)
      {
	 echo "<option value=\"".$ppstuds[id][$j]."\">".$ppstuds[name][$j]."</option>";
      }
      echo "</select></td></tr>";
      $partorder=$i+1;
      echo "<input type=hidden name=\"order[$i]\" value=\"$partorder\">";
      $i++;
   }
   ?>
   </table><!--End Cast-->
   <br>
   <table cellspacing=0 cellpadding=4 frame=all rules=all style="border:#808080 1px solid;">
   <caption><b>Technical Crew:</b><br>Only 9-12 students are available below to list as members of the tech crew. Tech crew members that are NOT 9-12 students ARE permitted, but do not need to be listed on this form.</caption>
   <tr align=center><th class=smaller>Name (Grade)</th></tr>
<?php
$sql="SELECT t1.id,t1.last,t1.first,t1.middle,t1.semesters,t1.eligible FROM eligibility AS t1, $table2 AS t2 WHERE t1.id=t2.student_id AND t2.crew='y' AND t2.school='$school2' ORDER BY t1.last";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=center><td><select name=\"crew[$i]\"><option>Choose Student";
   for($j=0;$j<count($ppstuds[id]);$j++)
   {
      echo "<option value=\"".$ppstuds[id][$j]."\"";
      if($ppstuds[id][$j]==$row[0]) echo " selected";
      echo ">".$ppstuds[name][$j]."</option>";
   }
   echo "</select></td></tr>";
   $i++;
}
$max=$i+5;
while($i<$max)
{
   echo "<tr align=center><td><select name=\"crew[$i]\"><option>Choose Student";
   for($j=0;$j<count($ppstuds[id]);$j++)
   {
      echo "<option value=\"".$ppstuds[id][$j]."\">";
      echo $ppstuds[name][$j]."</option>";
   }
   echo "</select></td></tr>";
   $i++;
}
echo "</table>";
?>
</td>
</tr>
<tr align=center>
<td>
<?php
//echo "<input type=button name=button onClick=\"window.open('coop_pp.php?session=$session&school=$school2','coop','menubar=no, location=no, resizable=no, scrollbars=yes, width=650, height=450')\" value=\"Add Co-Op Students\">";
?>
</td>
</tr>
<tr align=left>
<td><br>
<font style="color:red">Students listed in red are currently <b>ineligible</b>.
 Please make sure they will be eligible for the tournament before submitting them on
 this form.</font><br>
</td>
</tr>
<?php
if($state==1)	//show checkbox for final submission
{
?>
<tr align=left>
<th width=650>
<input type=checkbox name="send" value=y>
Check this box if you want to submit the above information as your final state entry.  You have not officially submitted your entry until you have checked this box and clicked one of the two "Save" buttons below!
</th>
</tr>
<?php
} //end if state
?>
<tr align=left>
<td>
<font size=2><i><?php echo $certify; ?></i></font>
</td>
</tr>
<tr align=center>
<td>
   <input type=submit name=submit value="Save & Keep Editing">
   &nbsp;
   <input type=submit name=submit value="Save & View Form">
   &nbsp;
   <input type=submit name=submit value="Cancel">
</td>
</tr>
</table><!--End Table of Tables-->
   </form>

</td>
</tr>
</table>
</body>
</html>
