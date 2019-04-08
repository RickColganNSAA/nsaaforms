<?php
//edit_go_g.php: Girls golf entry form

//check if user needs to be re-directed:
if($submit=="Home")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}
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
$schoolid=$row[id]; $sport="go_g";
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

//get class/dist choices
$sql="SELECT DISTINCT class FROM $db_name2.go_gdistricts WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
$class_array=array();
$i=0;
while($row=mysql_fetch_array($result))
{
   $class_array[$i]=$row[0];
   $i++;
}

//get class/dist for this team
$sql="SELECT class_dist FROM go_g WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//get name of coaches from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Golf'";
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
$sql="SELECT duedate FROM form_duedates WHERE form='go_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//CHECK IF IT IS PAST THE DUE DATE FOR THIS FORM
if(PastDue($duedate,0) && $level!=1)
{
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo $header;
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM go_g WHERE (school='$school2' OR co_op='$school2')";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_go_g.php?session=$session&school_ch=$school_ch\">";
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
$sql="SELECT * FROM go_g WHERE school='$school2' OR co_op='$school2'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[3];
   $cur_id[$ix]=$row[1];
   $cur_avg_round[$ix]=$row[4];
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
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Golf\">Return to Home-->Golf Entry Forms</a><br>";
?>
<form name=form1 method=post action="submit_go_g.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<table width=80%>
<tr align=center>
<th>GIRLS GOLF DISTRICT ENTRY</th>
</tr>
<tr align=center>
<td><b>Due <?php echo $duedate2; ?></b><br><br></td>
</tr>
<tr align=left>
<td>
   <table cellspacing=0 cellpadding=2>
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'go_g');
$sql="SELECT * FROM go_gschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
echo GetSchoolName($sid,'go_g')." $mascot";
   ?>
   </td>
   </tr>
   <tr align=left>
   <th>Colors:</th><td><?php echo $colors; ?></td>
   </tr>
   <tr align=left>
   <th>NSAA-Certified Coach:</th><td><?php echo $coach; ?></td>
   </tr>
   <tr align=left>
   <th>Assistant Coaches:</th><td><input type=text name=asst value="<?php echo $asst; ?>" size=50></td>
   </tr>
   <tr align=left>
   <th>Class:</th><td><?php echo GetClass($sid,'go_g'); ?></td>
   </td>
   </tr>
   </table>
</td>
</tr>
<tr align=center>
<td><p>Check the box next to the names of <b>5 students</b> who will be participating in the district competition.  Then enter her <font style="font-size:10pt;color:red"><b>average score per round (18-Holes)</b></font> for the season.  (All fields are required.)</p></td>
</tr>
<tr align=center>
<td>
   <table border=1 cellspacing=2 cellpadding=3 bordercolor=#000000>
   <tr align=center>
   <th colspan=2 class=smaller>Name (Grade)</th>
   <th class=smaller>Average<br>Round<br><font style="color:red"><b>(18 Holes)</b></font></th>
   </tr>
<?php
   //get girls golf participants from eligibility table in db
	/*
   $sql="SELECT id, first, last, middle, semesters, eligible FROM eligibility WHERE school='$school2' and gender='F' and go='x' ORDER BY last";
   $result=mysql_query($sql);
	*/
   $ix=0; $error=0;
   $studs=explode("<result>",GetPlayers($sport,$school));
   //while($row=mysql_fetch_array($result))
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      if($ix%10==0 && $ix>0)
      {
	 echo "<tr align=center><th class=smaller colspan=2>Name (Grade)</th>";
	 echo "<th class=smaller>Average<br>Round</th></tr>";
      }
      //check if this student is already submitted:
      $submitted=0;
      for($i=0;$i<count($cur_id);$i++)
      {
	 if($cur_id[$i]==$stud[0]) 
	 {
	    $submitted=1;
	    $index=$i;
	 }
      } 
      echo "<tr align=left><td onClick=\"Color(this)\">";
      echo "<input type=checkbox name=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($stud[3]!="y") echo " bgcolor=red";
      echo ">$stud[1]</td>";
      echo "<td";
      if($submitted==1 && trim($cur_avg_round[$index])=="" && $cur_checked[$index]=='y')
      {
         $error=1; echo " bgcolor=red";
      }
      echo "><input onChange=\"Color(this)\" type=text size=3 name=avg_round[$ix]";
      if($submitted==1) echo " value=$cur_avg_round[$index]";
      echo "></td>";
      echo "<input type=hidden name=id[$ix] value=$stud[0]><input type=hidden name=\"studsch[$ix]\" value=\"$stud[2]\"></td></tr>";
      $ix++;
   }
   echo "</table>";

   //Show Co-op Students Added (if any) and button to Add More
	/*
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters, t2.eligible FROM go_g AS t1, eligibility AS t2 WHERE t1.co_op='$school2' AND t1.student_id=t2.id";
   $result=mysql_query($sql);
   echo "<br><input type=button name=button onClick=\"window.open('coop_go_g.php?session=$session&school=$school2&class_dist=$class_dist','coop','menubar=no, location=no, resizable=no, scrollbars=yes, width=650,height=400')\" value=\"Add Co-Op Students\">";
   if(mysql_num_rows($result)>0)
   {
      echo "<br><a name=coop></a>";
      echo "<table border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
      echo "<caption><b>Co-op Students:</b></caption>";
      echo "<tr align=center><th colspan=2 class=smaller>School</th>";
      echo "<th class=smaller>Name</th><th class=smaller>Grade</th>";
      echo "<th class=smaller>Average<br>Round</th></tr>";
   }
   $coop=0;
   while($row=mysql_fetch_array($result))
   {
      //get year in school
      $year=GetYear($row[10]);
      echo "<tr align=left>";
      echo "<td>";
      echo "<input onClick=\"Color(this)\" type=checkbox";
      echo " name=\"coop_check[$coop]\" value=y";
      if($row[3]=='y') echo " checked";
      echo "></td>";
      echo "<td>$row[2]</td>";
      echo "<td";
      if($row[11]!='y') echo " bgcolor=#FF0000";
      echo ">$row[7], $row[8] $row[9]</td>";
      echo "<td>$year</td>";
      echo "<td";
      if($row[3]=='y' && trim($row[4])=="") 
      {
	 $error=1; echo " bgcolor=red";
      }
      echo "><input type=text name=\"coop_avg_round[$coop]\" size=3";
      echo " value=$row[4]></td>";
      echo "<input type=hidden name=\"coop_student[$coop]\" value=$row[1]>";
      echo "</tr>";
      $coop++;
   }
   if(mysql_num_rows($result)>0) echo "</table>";
	*/
?>

</td>
</tr>
<tr align=left>
<td><br><font size=2><i>(Substitutions may be made the day of the district meet and, if team qualifies for state, for the start of the state meet.)</i><br>
<font style="color:red">Students listed in red are currently <b>ineligible</b>.  Please make sure they will be eligible for districts before submitting them on this form.</font></p></font>
<?php
if($error==1)
{
   echo "<br><div class=error>ERROR:  You have not entered an AVERAGE for all of your players (see above).  You MUST enter an average for each player before this form is complete.</div>";
}
?>
</td>
</tr>
<tr align=left>
<td><br><font size=3><i><?php echo $certify; ?></i></font><br><br></td>
</tr>
<tr align=center>
<td><input type=submit name=submit value="Save and Keep Editing">
    <input type=submit name=submit value="Save and View Form">
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
