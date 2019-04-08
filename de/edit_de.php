<?php
//edit_de.php: Edit Form for Debate State Tournament Entries

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

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
$schoolid=$row[id]; $sport="de";
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

//get due date for this form
$duedate=GetDueDate("de");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
$level=GetLevel($session);

//CHECK IF IT IS PAST THE DUE DATE FOR THIS FORM
if(PastDue($duedate,0) && $level!=1)
{
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo GetHeader($session);
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM de WHERE school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_de.php?session=$session&school_ch=$school_ch\">";
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

echo $init_html;
echo GetHeader($session);
if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Debate\">Return to Home-->Debate Entry Forms</a><br>";
echo "<br>";
?>
<table width=90%><!--Table of Tables-->
<tr align=center>
<td>
   <table>
   <tr align=center>
   <th>STATE DEBATE TOURNAMENT ENTRY FORM</th>
   </tr>
   <tr align=center>
   <th>Due&nbsp;<?php echo $duedate2; ?></th>
   </tr>
   <tr align=center>
   <th class=smaller><br>No Late Entries Will Be Accepted.</th>
   </tr>
   <tr align=left>
   <th class=smaller>EACH ENTRY MUST BE ACCOMPANIED WITH A JUDGE'S NAME, ADDRESS AND ANY JUDGING CONSTRAINTS<br>(TYPE 'NONE' IF THERE ARE NO CONSTRAINTS) IN ORDER TO BE ELIGIBLE FOR STATE COMPETITION.</th>
   </tr>
   <tr align=center>
   <th class=smaller>You do NOT need to send a copy of this form to the NSAA.</th>
   </tr>
   </table>
</td>
<tr align=center>
<td>
   <table cellspacing=4>
   <tr align=left>
   <th>School/Mascot:</th>
   <td>
   <?php
   //get debate coaches   
   $sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Debate' AND level=3";   
   $result=mysql_query($sql);   
   $row=mysql_fetch_array($result);   
   $coach=$row[0];
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'de');
$sql="SELECT * FROM deschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
   echo GetSchoolName($sid,'de')." $mascot";
   ?>
   </td></tr>
   <tr align=left>
   <th>NSAA-Certified Coach:</th>
   <td>
   <?php
   //get debate coaches
   $sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Debate' AND level=3";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo $coach;
   ?>
   </td>
   </tr>
<form method=post action="submit_de.php">
   <tr align=left>
   <th>Assistant Coach(es):</th>
   <td><input type=text name="asstcoaches" size=40 value="<?php echo $row[1]; ?>"></td>
   </tr>
   <tr align=center>
   <td colspan=2>
   <?php
   echo "<input type=button name=button onClick=\"window.open('coop_de.php?session=$session&school=$school2','coop','menubar=no, location=no, resizable=no, scrollbars=yes, width=650 height=400')\" value=\"Add Co-Op Students\">";
   ?>
   </td>
   </tr>
   </table>
</td>
</tr>
<?php
//get students participating in debate at this school
$sql="SELECT id, last, first, middle FROM eligibility WHERE school='$school2' AND de='x' ORDER BY last";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $students[0][$ix]=$row[0];
   $students[1][$ix]="$row[1], $row[2] $row[3]";
   $ix++;
}
//get co-op students
$sql="SELECT t1.* FROM eligibility AS t1, de_coop AS t2 WHERE t1.id=t2.student_id AND t2.co_op='$school2'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $students[0][$ix]=$row[0];
   $students[1][$ix]="$row[2], $row[3] $row[4]";
   $ix++;
}
?>
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<tr align=center>
<td>
   <table border=1 bordercolor=#000000 cellspacing=2 cellpadding=5 rules=none frame=box>
   <tr align=left>
   <th colspan=2><u>Entry #1:</u></th>
   </tr>
   <tr align=left>
   <th>Student:</th>
   <td><select name=student_1>
       <option>Choose Student
   <?php
   //get info already submitted for this school
   $sql="SELECT * FROM de WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sch_id=$row[school];
   $student_1=$row[student_id_1];
   $judge_1=$row[judge_1];
   $j_address_1=$row[j_address_1];
   $j_phone_1=$row[j_phone_1];
   $j_constraints_1=$row[j_constraints_1];
   $student_2=$row[student_id_2];
   $judge_2=$row[judge_2];
   $j_address_2=$row[j_address_2];
   $j_phone_2=$row[j_phone_2];
   $j_constraints_2=$row[j_constraints_2];
   for($i=0;$i<count($students[0]);$i++)
   {
      $id=$students[0][$i];
      $name=$students[1][$i];
      echo "<option value=$id";
      if($id==$student_1) echo " selected";
      echo ">$name";
   }
   ?>
       </select>
   </td>
   </tr>
   <tr align=left>
   <th colspan=2>Judge's Information:</th>
   </tr>
   <tr align=left>
   <th>Name:</th>
   <td><input type=text name=judge_1 size=30 value="<?php echo $judge_1; ?>">
   </td>
   </tr>
   <tr align=left>
   <th>Address:</th>
   <td><input type=text name=j_address_1 size=60 value="<?php echo $j_address_1; ?>">
   </td>
   </tr>
   <tr align=left>
   <th align=left>Phone:</th>
   <td><input type=text name=j_phone_1 size=20 value="<?php echo $j_phone_1; ?>">&nbsp;
   (ex: (402)123-4567)
   </td>
   </tr>
   <tr align=left>
   <th>Judging Constraints:</th>
   <td><input type=text name=j_constraints_1 size=60 value="<?php echo $j_constraints_1; ?>">
   </td>
   </tr>
   </table>
</td>
</tr>
<tr align=center>
<td>
   <table border=1 bordercolor=#000000 frame=box rules=none cellspacing=2 cellpadding=5>
   <tr align=left>
   <th colspan=2><u>Entry #2:</u></th>
   </tr>
   <tr align=left>
   <th>Student:</th>
   <td><select name=student_2>
       <option>Choose Student
   <?php
   for($i=0;$i<count($students[0]);$i++)
   {
      $id=$students[0][$i];
      $name=$students[1][$i];
      echo "<option value=$id";
      if($id==$student_2) echo " selected";
      echo ">$name";
   }
   ?>
       </select>
   </td>
   </tr>
   <tr align=left>
   <th colspan=2>Judge's Information:</th>
   </tr>
   <tr align=left>
   <th>Name:</th>
   <td><input type=text name=judge_2 size=30 value="<?php echo $judge_2; ?>">
   </td>
   </tr>
   <tr align=left>
   <th>Address:</th>
   <td><input type=text name=j_address_2 size=60 value="<?php echo $j_address_2; ?>">
   </td>
   </tr>
   <tr align=left>
   <th align=left>Phone:</th>
   <td><input type=text name=j_phone_2 size=20 value="<?php echo $j_phone_2; ?>">&nbsp;
   (ex: (402)123-4567)
   </td>
   </tr>
   <tr align=left>
   <th>Judging Constraints:</th>
   <td><input type=text name=j_constraints_2 size=60 value="<?php echo $j_constraints_2; ?>">
   </td>
   </tr>
   </table>
</td>
</tr>
<tr align=center>
<td>
   <input type=checkbox name=send value='y'><b><?php echo $finalsubmit; ?></b>
   <br><br>
   <i><font size=2><?php echo $certify; ?></i></font>
   <br>
   <br>
   <input type=submit name=submit value="Save & Keep Editing">
   <input type=submit name=submit value="Save & View Form">
   <input type=submit name=submit value="Cancel">
</td>
</tr>
    </form>
</table><!--End Table of Tables-->
</td>
</tr>
</table>
</body>
</html>
