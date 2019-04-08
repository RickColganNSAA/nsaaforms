<?php
//edit_cc_b.php: Boys Cross-Country entry form

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
$schoolid=$row[id]; $sport="ccb";
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

//get name of coaches from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Boys Cross-Country'";
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
$sql="SELECT duedate FROM form_duedates WHERE form='cc_b'";
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
   $sql="SELECT * FROM cc_b WHERE school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_cc_b.php?session=$session&school_ch=$school_ch\">";
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
$sql="SELECT * FROM cc_b WHERE school='$school2' OR co_op='$school2'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[3];
   $cur_id[$ix]=$row[1];
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
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Cross-Country\">Return to Home-->Cross-Country Entry Forms</a>&nbsp;&nbsp;&nbsp;";
if($level==1 || $level==2)
   echo "<a class=small href=\"edit_cc_g.php?session=$session&school_ch=$school_ch\">Go to GIRLS District Entry Form</a>";
echo "<br>";

//get class/dist number choices for CC from database
$sql="SELECT choices FROM classes_districts WHERE sport='cc_b'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_array=$row[0];
$class_array=split(",",$class_array);

//get class/dist# for this team if already entered
$sql="SELECT class_dist FROM cc_b WHERE school='$school2'";
$result=mysql_query($sql);
$class_dist="";
while($row=mysql_fetch_array($result))
{
   if($class_dist=="") $class_dist=$row[0];
}
?>
<form method=post action="submit_cc_b.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<table width=80%>
<tr align=center>
<th>BOYS CROSS-COUNTRY DISTRICT ENTRY</th>
</tr>
<tr align=center>
<td><b>Due <?php echo $duedate2; ?></b><br><br></td>
</tr>
<tr align=left>
<td>
   <table cellspacing=0 cellpadding=2>
   <tr align=left valign=top>
   <th align=left>School/Mascot:</th><td>
   <?php 
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'ccb');
$sql="SELECT * FROM ccbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
   echo GetSchoolName($sid,'ccb')." $mascot";
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
   <th align=left>Assistant Coaches:</th><td><input type=text name=asst value="<?php echo $asst; ?>" size=50></td></tr>
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
<td><p>Check the box next to the name of each student who will be participating in the district competition.</p></td>
</tr>
<tr align=left>
<td><p>Class A may list 7 entries.<br>Classes B, C, and D may list 6 entries.</p></td>
</tr>
<tr align=center>
<td>
   <table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">
   <tr align=center>
   <th colspan=2>Name (Grade)</th>
   </tr>
<?php
   //get boys cross-country participants from eligibility table in db
   $ix=0;
   $studs=explode("<result>",GetPlayers($sport,$school));
   //echo '<pre>'; print_r($studs);print_r($cur_id); exit;
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
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
      echo "<input type=checkbox name=\"check[$ix]\" value=\"y\"";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($stud[3]!="y") echo " bgcolor=red";
      echo ">$stud[1]";
      echo "<input type=hidden name=\"id[$ix]\" value=\"$stud[0]\"><input type=hidden name=\"studsch[$ix]\" value=\"$stud[2]\"></td></tr>";
      $ix++;
   }
   echo "</table>";

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
<td><input type=submit name=submit value="Save and Keep Editing">
    <input type=submit name=submit value="Save and View Form">
    <input type=submit name=submit value="Cancel">
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
