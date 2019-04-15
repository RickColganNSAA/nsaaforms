<?php
//edit_wr.php: Wrestling entry form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);

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
$schoolid=$row[id]; $sport="wr";
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

//get level of user
$level=GetLevel($session);

//get name of coach from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Wrestling'";
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
$sql="SELECT duedate FROM form_duedates WHERE form='wr'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//CHECK IF IT IS >2 DAYS PAST THE DUE DATE FOR THIS FORM
//Changed 1/29/07 to NO GRACE PERIOD
if(PastDue($duedate,0) && $level!=1 && $school!="Test's School")
{
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo $header;
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT t1.* FROM wr AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t1.school='$school2' AND t1.checked='y'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_wr.php?session=$session&school_ch=$school_ch\">";
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

//If form has already been submitted, get info from db:
$sql="SELECT t1.* FROM wr AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t1.school='$school2'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[6];
   $cur_id[$ix]=$row[1];
   $cur_weight[$ix]=$row[4];
   $cur_record[$ix]=$row[5];
   $ix++;
}
?>

<html>
<head>
   <title>NSAA Home</title>
   <link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<body>

<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase() != 'TD' && element != null)
   {
      element=document.all ? element.parentElement : element.parentNode;
   }
   if(element)
   {
      element.bgColor="FFFF33";
   }
}
</script>

<?php
echo $header;

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Wrestling\">Return to Home-->Wrestling Entry Forms</a><br>";

//get class/dist number choices for wrestling from database
$sql="SELECT choices FROM classes_districts WHERE sport='wr'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_array=$row[0];
$class_array=split(",",$class_array);

//get class/dist# for this team if already entered
$sql="SELECT t1.class_dist FROM wr AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$class_dist="";
while($row=mysql_fetch_array($result))
{
   if($class_dist=="") $class_dist=$row[0];
}
?>
<form method=post action="submit_wr.php" name="form1">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<table width=80%>
<tr align=center>
<th>WRESTLING DISTRICT ENTRY</th>
</tr>
<tr align=center>
<td><b>Due <?php echo $duedate2; ?></b><br><br></td>
</tr>
<tr align=left>
<td>
   <table cellspacing=0 cellpadding=2>
   <tr align=left>
   <th align=left>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'wr');
$sql="SELECT * FROM wrschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];

   echo GetSchoolName($sid,'wr')." $mascot";
   ?>
   </td>
   </tr>
   <tr align=left>
   <th align=left>Colors:</th><td><?php echo $colors; ?></td>
   </tr>
   <tr align=left>
   <th align=left>NSAA-Certified Coach:</th><td><?php echo $coach; ?></td>
   </tr>
   <tr align=left>
   <th align=left>Assistant Coaches:</th><td><input type=text name=asst size=50 value="<?php echo $asst; ?>"></td></tr>
   <tr align=left>
   <th align=left>Class:</th>
   <td><select name=class_dist>
       <option>Choose
   <?php
   for($i=0;$i<count($class_array);$i++)
   {
      echo "<option";
      if($class_dist==$class_array[$i]) echo " selected";
      echo ">$class_array[$i]";
   }
   ?>
      </select>
   </td>
   </tr>
   </table>
</td>
</tr>
<tr align=left>
<td>
<ul>
<li>The district director will automatically receive these forms once the due date has passed. You do NOT need to email this form to the district director.</li>
<li>Check the box next to the name of each student who will be participating in the district competition.  Then select the weight class for that student and enter the student's record.
<li>Substitutions may be made until the deadline established by the district tournament director.  <i>Only one entry allowed in each weight class.</i>
</ul></td></tr>
<tr align=center>
<td>
   <table border=1 cellspacing=2 cellpadding=3 bordercolor=#000000>
   <tr align=center>
   <th colspan=2>Name</th><th>Grade</th>
   <th>Weight Class</th><th>Record</th>
   </tr>
<?php
   //get all wrestling participants, boys and girls
   $sql="SELECT id, first, last, middle, semesters, eligible FROM eligibility WHERE school='$school2' AND wr='x' ORDER BY last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($ix%10==0 && $ix>0)
      {
	 echo "<tr align=center><th colspan=2>Name</th>";
	 echo "<th>Grade</th><th>Weight Class</th>";
	 echo "<th>Record</th></tr>";
      }
      //check if this student is already submitted:
      $submitted=0;
      for($i=0;$i<count($cur_id);$i++)
      {
	 if($cur_id[$i]==$row[0]) 
	 {
	    $submitted=1;
	    $index=$i;
	 }
      } 
      echo "<tr align=center><td onClick=\"Color(this)\">";
      echo "<input type=checkbox name=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($row[5]!="y") echo " bgcolor=red";
      echo " align=left>$row[2], $row[1] $row[3]</td>";
      if($row[4]==1 || $row[4]==2) $year="9";
      else if($row[4]==3 || $row[4]==4) $year="10";
      else if($row[4]==5 || $row[4]==6) $year="11";
      else $year="12";
      echo "<td>$year</td>";
      echo "<td><select name=weight[$ix]>";
      echo "<option>~";
      for($i=0;$i<count($weights);$i++)
      {
	 echo "<option";
	 if($submitted==1 && $cur_weight[$index]==$weights[$i])
	    echo " selected";
	 echo ">$weights[$i]";
      }
      echo "</select>&nbsp;lbs</td>";
      echo "<td><input type=text name=\"win[$ix]\" size=2";
      if($submitted==1)
      {
	 $rec=split("-",$cur_record[$index]);
	 $w=$rec[0];
	 $l=$rec[1];
	 echo " value=$w";
      }
      echo "><b>&nbsp;W</b>&nbsp;&nbsp;";
      echo "<input type=text name=\"loss[$ix]\" size=2";
      if($submitted==1) echo " value=$l";
      echo "><b>&nbsp;L</b>";
      echo "<input type=hidden name=\"id[$ix]\" value=$row[0]></td></tr>";
      $ix++;
   }
   echo "</table>";

   //Show Co-op Students Added (if any) and button to Add More
   $sql="SELECT t1.*, t2.school, t2.last, t2.first, t2.middle, t2.semesters, t2.eligible FROM wr AS t1, eligibility AS t2 WHERE t1.co_op='$school2' AND t1.student_id=t2.id";
   $result=mysql_query($sql);
   echo "<br><input type=button name=button onClick=\"window.open('coop_wr.php?session=$session&school=$school2','coop','menubar=no, location=no, resizable=no, scrollbars=yes, width=650 height=400')\" value=\"Add Co-Op Students\">";
   if(mysql_num_rows($result)>0)	//Show existing co-op students
   {
      echo "<br><a name=coop></a>";
      echo "<table border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
      echo "<caption><b>Co-op Students:</b></caption>";
      echo "<tr align=center><th colspan=2 class=smaller>School</th>";
      echo "<th class=smaller>Name</th><th class=smaller>Grade</th>";
      echo "<th class=smaller>Weight Class</th>";
      echo "<th class=smaller>Record</th>";
      echo "</tr>";
   }
   $coop=0;
   while($row=mysql_fetch_array($result))
   {
      //get year in school
      $year=GetYear($row[12]);
      echo "<tr align=center>";
      echo "<td><input onClick=\"Color(this)\" type=checkbox";
      echo " name=\"coop_check[$coop]\" value=y";
      if($row[6]=='y') echo " checked";
      echo "></td>";
      echo "<td>$row[school]</td>";
      echo "<td";
      if($row[13]!='y') echo " bgcolor=#FF0000";
      echo " align=left>$row[9], $row[10] $row[11]</td>";
      echo "<td>$year</td>";
      echo "<td><select name=coop_weight[$coop]>";
      echo "<option>~";
      for($i=0;$i<count($weights);$i++)
      {
	 echo "<option";
	 if($row[4]==$weights[$i]) echo " selected";
	 echo ">$weights[$i]";
      }
      echo "</select></td>";
      $rec=split("-",$row[5]);
      $w=$rec[0]; $l=$rec[1];
      echo "<td><input type=text name=\"coop_win[$coop]\" size=2 value=$w>&nbsp;<b>W</b>&nbsp;&nbsp;";
      echo "<input type=text name=\"coop_loss[$coop]\" size=2 value=$l>&nbsp;<b>L</b>";
      echo "</td>";
      echo "<input type=hidden name=\"coop_student[$coop]\" value=$row[1]>";
      echo "</tr>";
      $coop++;
   }
   if(mysql_num_rows($result)>0) echo "</table>";
?>

</td>
</tr>
<tr align=left>
<td><br>
<font style="color:red">Students listed in red are currently <b>ineligible</b>.  Please make sure they will be eligible for districts before submitting them on this form.</font></p></td>
</tr>
<tr align=left>
<td><br><font size=3><i><?php echo $certify; ?></i></font><br><br></td>
</tr>
<tr align=center>
<td><input type=submit name=save value="Save and Keep Editing">
    <input type=submit name=save value="Save and View Form">
    <input type=submit name=cancel value="Cancel">
</td>
</tr>
</table>
</form>
</center>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
