<?php
//edit_tr_g.php: Track entry form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
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
$schoolid=$row[id]; $sport="trg";
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

if($alert!='')
{
?>
<script language="javascript">
window.alert('<?php echo $alert; ?>');
</script>
<?php
}

echo $init_html;
echo $header;

//get class/dist choices
$sql="SELECT choices FROM classes_districts WHERE sport='tr_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_array=split(",",$row[0]);

//get name of coach from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Track & Field'";
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
$sql="SELECT duedate FROM form_duedates WHERE form='tr_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

if(PastDue($duedate,0) && $level!=1)
{
//CHECK IF IT IS PAST THE DUE DATE FOR THIS FORM
   $late_page=GetLatePage($duedate2);
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT t1.* FROM tr_g AS t1 WHERE t1.school='$school2' OR t1.co_op='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_tr_g.php?session=$session&school_ch=$school_ch\">";
      echo "View your Submitted Form</a>";
   }
   else
   {
      echo "<font size=2>";
      echo "No information was submitted for your district entry.<br>";
      echo "If this was a mistake, please contact the NSAA immediately!";
      echo "<br><br>";
      echo "<a href=\"../welcome.php?session=$session\">Return Home</a></font>";
   }
   exit();
}
else    //district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="tr_g";
}

//get class from tr table
$sql="SELECT t1.class_dist FROM $table AS t1 WHERE t1.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

if($level==1 || $level==2)
{
   echo "<br>";
   if($level==1)
      echo "<a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Track\">Return to Home-->Track & Field Entry Forms</a>&nbsp;&nbsp;&nbsp;";  
   echo "<a href=\"edit_tr_b.php?session=$session&school_ch=$school_ch\" class=small>Go to BOYS $form_type Entry Form</a><br>";
}

echo "<form method=post action=\"submit_tr_g.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=state value=\"$state\">";
echo "<br><table width=90%><!--Table of Tables-->";
echo "<caption><b>GIRLS TRACK & FIELD $form_type ENTRY FORM <br>";
if($state!=1) echo "Due $duedate2";
echo "</b></caption>";
echo "<tr align=left><td colspan=2><table><!--Team Info-->";
echo "<tr align=left><th>School/Mascot:</th><td>";

//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,$sport);
$sql="SELECT * FROM trschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];

echo GetSchoolName($sid,$sport,date("Y"))." $mascot";
echo "</td></tr>";
echo "<tr align=left><th>School Colors:</th><td>$colors</td></tr>";
echo "<tr align=left><th>NSAA-Certified Coach:</th><td>$coach</td></tr>";
echo "<tr align=left><th>Assistant Coach(es):</th><td><input type=text size=40 name=asst value=\"$asst\"></td></tr>";
echo "<tr align=left><th>Class:</th><td>".GetClass($sid,$sport)."</td></tr>";
echo "</select></td></tr>";
echo "<tr align=left><td colspan=2><a href=\"teamlist_g.php?session=$session&print=1&school_ch=$school_ch\" target=new>Click Here for Your Team's District Roster</a></td></tr>";
echo "</table></td></tr>";

echo "<tr align=left><td colspan=2><!--Instructions-->";
echo "When you have completed this form, please click \"Save & View Form\" at the bottom of this page to double-check for accuracy. This form as well as the list of eligible track team members will be sent to the ditrict director automatically after the due date has passed. <b><u>Entree fees should be mailed to the district meet director (Class A--$25.00, Class B--$25.00, Class C--$20.00, Class D--$20.00).</b></u><br><br>";
echo "NOTE: It is permissible to convert times from yard races to the metered equivalent for best performances below.  An individual may be entered in only 4 EVENTS to include relays and all individual events.<br><br></td></tr>";

//get list of tr students for this school
$studs=explode("<result>",GetPlayers($sport,$school));
$i=0;
$students=array();
for($s=0;$s<count($studs);$s++)
{
   $stud=explode("<detail>",$studs[$s]);     //ID, name, school, eligible
   if($stud[3]=='y')
   {
      $students[0][$i]=$stud[0];
      $students[1][$i]=$stud[1];
      $students[2][$i]=$stud[2];
      $i++;
   }
}

//get players already submitted from db table
   //Pole Vault:

$colheaders="
   <tr align=center>
   <th class=smaller>Name (Grade)</th>
   <th class=smaller>Best<br>Performance</th>
   </tr>
";

for($x=0;$x<count($trevents_g);$x++)
{
  if($trevents_g[$x]!="teamscores" && $trevents_g[$x]!="extraqual")
  {
   if($x%2==0) echo "<tr align=center valign=top>";
   echo "<td>
   <table width=350 cellspacing=1 cellpadding=2 border=1 bordercolor=#000000>";
   echo "<caption align=left><b>$treventslong_g[$x]:</b></caption>";
   echo $colheaders;

   $sql=GetEventSql($treventslong_g[$x],$table,$school2);
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td";
      if($row[14]!="y") echo " bgcolor=red";
      echo "><select name=\"student[$x][$ix]\">";
      echo "<option>Choose Student";
      for($i=0;$i<count($students[0]);$i++)
      {
         $id=$students[0][$i];
         $name=$students[1][$i];
	 $school=$students[2][$i];
         echo "<option value=$id";
         if($row[1]==$id) echo " selected";
         echo ">$name</option>"; //($school)
      }
      echo "</select></td>";
      //get performance
      $perf=GetPerf($treventslong_g[$x],$row);
      echo "<td><input type=text name=\"perf[$x][$ix]\" size=5 value=\"$perf\"></td></tr>";
      $ix++;
   }
   if(ereg("Relay",$treventslong_g[$x])) $max=4;
   else $max=3;
   while($ix<$max)	//fill in remaining spots with blank entry
   {
      echo "<tr align=center>";
      echo "<td><select name=\"student[$x][$ix]\">";
      echo "<option>Choose Student";
      for($i=0;$i<count($students[0]);$i++)
      {
         $id=$students[0][$i];
         $name=$students[1][$i];
	 $school=$students[2][$i];
         echo "<option value=$id>$name</option>";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"perf[$x][$ix]\" size=5></td></tr>";
      $ix++;
   }
   echo "</table>";
   echo "</td>";
   if(($x+1)%2==0) echo "</tr>";
  }
}

?>
<tr align=left>
<td colspan=2><br><br>
<font style="color:red">Students listed in red are currently <b>ineligible</b>.  Please make sure they will be eligible for the <?php echo $form_type; ?> tournament before submitting them on this form.</font></font></td>
</tr>
<?php
/*
if($state==1)   //have checkbox for final submission
{
?>
<tr align=left>
<th colspan=2>
<input type=checkbox name="send" value=y>
Check this box if you want to submit the above information as your final state entry.
You have not officially submitted your entry until you have checked this
box and clicked one of the two "Save" buttons below!
</th>
</tr>
<?php
} //end if state
*/
?>
<tr align=left>
<td colspan=2><br><font size=3><i><?php echo $certify; ?></i></font><br><br></td>
</tr>
<tr align=center>
<td colspan=2><input type=submit name=submit value="Save and Keep Editing">
    <input type=submit name=submit value="Save and View Form">
    <input type=submit name=submit value="Cancel">
</td>
</tr>
</table><!--End Table of Tables-->
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
<?php
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
   $eventsql="SELECT t1.*, t2.semesters, t2.eligible FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND (t1.event_1='$event' OR t1.event_2='$event' OR t1.event_3='$event' OR t1.event_4='$event') ORDER BY t1.id";
   return $eventsql;
}
?>
