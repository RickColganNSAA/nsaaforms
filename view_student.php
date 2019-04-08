<?php
//view_student.php: displays specifics of student's
//	record.  

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$student_id=$id;

//connect to database:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//get student info from db
$sql="SELECT * FROM eligibility WHERE id='$student_id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school=$row[1];
$last=$row[2];
$first=$row[3];
$middle=$row[4];
if($row[5]=="M" || $row[5]=="m")
   $gender="male";
else $gender="female";
$dob=$row[7];
switch($row[8])  
{
   case 1:
      $semesters="1st Semester Freshman";
      break;
   case 2:
      $semesters="2nd Semester Freshman";
      break;
   case 3:
      $semesters="1st Semester Sophomore";
      break;
   case 4:
      $semesters="2nd Semester Sophomore";
      break;
   case 5:
      $semesters="1st Semester Junior";
      break;
   case 6:
      $semesters="2nd Semester Junior";
      break;
   case 7:
      $semesters="1st Semester Senior";
      break;
   case 8:
      $semesters="2nd Semester Senior";
      break;
   default:
      $semesters=$row[8];
}
if($row[9]=="y")
   $transfer="<font style=\"color:green\">Yes ($row[10])</font>";
else $transfer="No";
if($row[11]=="y")
{
   $eligible="Yes";
   if($row[12]!="") $eligible.=" ($row[12])";
}
else $eligible="<font style=\"color:red\">No&nbsp;($row[12])</font>";
if($row[13]=="y")
{
   $foreignx="Yes";
   if($row[14]!="")
      $foreignx.=" ($row[14])";
}
else $foreignx="No";
if($row[15]=="y")
   $enroll_option="Yes";
else $enroll_option="No";
$activities="";
$ix=0;
for($i=17;$i<35;$i++)
{
   if($row[$i]=="x")
      $activities.="$activity[$ix], ";
   $ix++;
}
if($activities=="") $activities="None";
else $activities=substr($activities,0,strlen($activities)-2);

?>

<html>
<head>
   <title>NSAA Home</title>
   <link rel="stylesheet" href="../css/nsaaforms.css" type="text/css">
</head>
<body>
<?php
$header=GetHeader($session);
echo $header;
?>
<br>
<form method="post" action="edit_student.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=letter value=<?php echo $letter; ?>>
<table cellspacing=0 cellpadding=3>
<tr align=center>
<th colspan=2>Student Eligibility Information:</th>
</tr>
<tr><td colspan=2><hr></td></tr>
<tr align=center>
<th colspan=2><?php echo $school; ?></th>
</tr>
<tr bgcolor=#D0D0D0 align=left>
<th align=left>Name:</th><td width=400><?php echo "$last, $first $middle"; ?></td>
</tr>
<tr align=left>
<th align=left>DOB:</th><td width=400><?php echo $dob; ?></td>
</tr>
<tr bgcolor=#D0D0D0 align=left>
<th align=left>Gender:</th><td width=400><?php echo $gender; ?></td>
</tr>
<tr align=left>
<th align=left>Semester:</th><td width=400><?php echo $semesters; ?></td>
<tr bgcolor=#D0D0D0 align=left>
<th align=left>Eligible:</th><td width=400><?php echo $eligible; ?></td>
</tr>
<!--
<tr align=left>
<th align=left>Transfer:</th><td><?php echo $transfer; ?></td>
</tr>
<tr bgcolor=#D0D0D0 align=left>
<th align=left>Enrollment Option:</th><td><?php echo $enroll_option; ?></td>
</tr>
-->
<tr align=left valign=top>
<th align=left>International Transfer:</th><td width=400>
   <?php 
   $sql2="SELECT * FROM forexsettings";
   $result2=mysql_query($sql2);
   $forex=mysql_fetch_array($result2);
   $sql2="SELECT * FROM forex WHERE studentid='$student_id'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(mysql_num_rows($result2)>0)
   {
      if($row2[datesub]=="")
         echo "<font style=\"red\">You have NOT submitted this student's $forex[formtitle]. <a class=small href=\"forex.php?session=$session&id=$row2[id]\">Click Here</a> to complete and submit this form.</font>";
      else if($row2[execsignature]=="")
         echo "You submitted this student's $forex[formtitle] on ".date("m/d/y",$row2[datesub]).".  <br>The Executive Director has not yet taken action on this form.  Please check back at a later date for the action of the Executive Director.";
      else if($row2[eligible]=="y")
         echo "You submitted this student's <a class=small target=\"_blank\" href=\"forex.php?session=$session&header=no&id=$row2[id]\">$forex[formtitle]</a> on ".date("m/d/y",$row2[datesub]).".  <br>On ".date("m/d/y",$row2[execdate]).", the Executive Director declared this student <b><i>eligible</b></i>.";
      else if($row2[eligible]=="n")
         echo "You submitted this student's <a class=small target=\"_blank\" href=\"forex.php?session=$session&header=no&id=$row2[id]\">$forex[formtitle]</a> on ".date("m/d/y",$row2[datesub]).".  <br>On ".date("m/d/y",$row2[execdate]).", the Executive Director declared this student <b><i><font style=\"color:red\">ineligible</font></b></i>.";
      if($row2[execcomments]!='')
         echo "<br>Executive Director Comments:&nbsp;<i>$row2[execcomments]</i>";
    }
    else if($foreignx=="Yes")
       echo "<font style=\"color:red\">You have NOT submitted a $forex[formtitle] for this student.  Please <a class=small href=\"forex.php?session=$session&studentid=$student_id\">Click Here</a> to complete this form online.";
    ?></td>
</tr>
<tr bgcolor=#D0D0D0 align=left>
<th align=left>Activities:</th><td><?php echo strtoupper($activities); ?></td>
</tr>
<input type=hidden name=id value="<?php echo $student_id; ?>">
<tr align=center><td colspan=2><br><input type=submit name=submit value="Edit Student Info">&nbsp;&nbsp;
  <input type=submit name=submit value="Delete Student"></td></tr>
<tr align=center><td colspan=2><br>
<?php
echo "<a href=\"eligibility.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=$letter\">Return to Eligibility List</a>";
echo "&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\">Return to Home</a>";
?>
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

