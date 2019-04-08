<?php
//edit_student.php: displays specifics of student's
//	record.  Changes can be made here as well.

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}   

$student_id=$id;

//check if submit action was "delete"
if($submit=="Delete Student")
{
   header("Location:delete_confirm.php?id=$id&session=$session&activity_ch=$activity_ch&school_ch=$school_ch&letter=$letter");
}

//connect to database:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//get level of user
$sql="SELECT t2.level FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$level=$row[0];

//get array of schools
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$ix=0;
$schools=array();
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

//get student info from db
$sql="SELECT * FROM eligibility WHERE id='$student_id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school_attending=$row[1];
$last=$row[2];
$first_nick=$row[3];
if(ereg("\(",$first_nick))
{
   $first_nick=split("\(",$first_nick);
   $first=$first_nick[0];
   $nickname=substr($first_nick[1],0,strlen($first_nick[1])-1);
}
else
{
   $first=$row[3];
   $nickname="";
}
$middle=$row[4];
$gender=$row[5];
$dob=$row[7];
$semesters=$row[8];
$transfer=$row[9];
$transfer_comment=$row[10];
$eligible=$row[11];
$eligible_comment=$row[12];
$foreignx=$row[13];
$foreignx_comment=$row[14];
$enroll_option=$row[15];
$eo_comment=$row[16];
$activities=array();
$ix=0;
for($i=17;$i<35;$i++)
{
   if($row[$i]=="x")
   {
      $activities[$ix]=$i;
      $ix++;
   }
}
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
<center><br>
<form method="post" action="update_student.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=letter value=<?php echo $letter; ?>>
<table>
<?php
//Check for error messages
if($dob_error)
{
   echo "<tr align=left><th align=left class=red colspan=2>Please make sure the student's date of birth is in this format: MM-DD-YYYY</th></tr>";
}
if($sem_error)
{
   echo "<tr align=left><th align=left class=red colspan=2>Please make sure the student's semester is between 1 and 8 unless <br>the student is only participating in music or play production (in which case you may enter a 0)</th></tr>";
}
if($name_error)
{
   echo "<tr align=left><th align=left class=red colspan=2>Please make sure you have entered a first and last name for the student<br>and that only letters were used</th></tr>";
}

?>
<tr align=center>
<th colspan=2>Student Eligibility Form</th>
</tr>
<tr><td colspan=2><hr></td></tr>
<tr align=left>
<th align=left>Name:</th>
<td><input type=text name="last" value="<?php echo $last; ?>" size=15>
    , <input type=text name="first" value="<?php echo $first; ?>" size=10>
    &nbsp;<input type=text name="middle" value="<?php echo $middle; ?>" size=2>
    </td>
</tr>
<tr align=left>
<th></th>
<th align=left>(&nbsp;Nickname:&nbsp;<input type=text name=nickname value="<?php echo $nickname; ?>" size=10>&nbsp;)</th>
</tr>
<?php
if($level==1)	//Only NSAA can change school the student attends
{
?>
   <tr align=left>
   <th align=left>School:</th>
   <td><select name=school_attending>
   <?php
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option";
      if($school_attending==$schools[$i]) echo " selected";
      echo ">$schools[$i]";
   }
   ?>
       </select>
   </td>
   </tr>
<?php
}
?>
<tr align=left>
<th align=left>DOB:</th>
<?php $dob=explode("-", $dob); ?>
<td><select name="dobm"><?php echo GetDateSelectOptions("MM",$dob[0],1,12); ?></select>/
	<select name="dobd"><?php echo GetDateSelectOptions("DD",$dob[1],1,31); ?></select>/
	<select name="doby"><?php echo GetDateSelectOptions("YYYY",$dob[2],date("Y")-30,date("Y")); ?></select>
</td>
</tr>
<tr align=left>
<th align=left>Gender:</th>
<td><select name="gender">
       <?php 
       if($gender=="m"||$gender=="M") echo "<option>M<option>F";
       else echo "<option>F<option>M";
       ?>
    </select></td>
</tr>
<tr align=left>
<th align=left>Semester:</th>
<td><input type=text name="semesters" value="<?php echo $semesters; ?>" size=2>
<?php
   if($semesters==0)
   {
      $sem_string="(Pre-High School)";
   }
   else if($semesters%2!=0)
   {
      $sem_string="(1st Semester ";
   }
   else
   {
      $sem_string="(2nd Semester ";
   }
   if($semesters<3 && $semesters!=0)	//freshman
   {
      $sem_string.="Freshman)";
   }
   else if($semesters<5 && $semesters!=0)	//sophomore
   {
      $sem_string.="Sophomore)";
   }
   else if($semesters<7 && $semesters!=0)	//junior
   {
      $sem_string.="Junior)";
   }
   else if($semesters!=0)	//senior
   {
      $sem_string.="Senior)";
   }
   echo " $sem_string";
?>
    </td>
<tr><td colspan=2><hr></td></tr>
<tr align=left>
<th align=left>Eligible:</th>
<td><input type=checkbox name="eligible"
       <?php if($eligible=="y") echo " checked"; ?>
     value="y">
     <input type=text name="eligible_comment" size=35
     value="<?php echo $eligible_comment; ?>">
     </td>
</tr>
<!--
<tr align=left>
<th>Transfer:</th>
<td><input type=checkbox name="transfer"
       <?php if($transfer=="y") echo " checked"; ?>
     value="y">
     <input type=text name="transfer_comment" size=35
     value="<?php echo $transfer_comment; ?>">
     </td>
</tr>
<tr align=left>
<th>Enrollment Option:</th>
<td><input type=checkbox name="enroll_option" value="y" 
     <?php if($enroll_option=="y") echo " checked"; ?>>
     </td>
</tr>
-->
<tr align=left>
<th align=left>International Transfer:</th>
<td><input type=checkbox name="foreignx" value="y"
       <?php if($foreignx=="y") echo " checked"; ?>>
    <input type=text name="foreignx_comment" size=35
    value="<?php echo $foreignx_comment; ?>">
    </td>
</tr>
<tr><td colspan=2><hr></td></tr>
<tr align=left>
<th align=left colspan=2>Activities:</th>
</tr>
<tr align=center><td colspan=2>
   <table>
<?php
$ix=0;
for($i=0;$i<count($activity);$i++)
{
   if($i%6==0) echo "<tr>";
   echo "<td width='50px'><input type=checkbox name=\"$activity[$i]\" value='x'";
   if($row[$activity[$i]]=='x') echo " checked";
   echo ">$activity[$i]</td>";
   if(($i+1)%6==0) echo "</tr>";
}
?>
   </table>
</td>
</tr>
<input type=hidden name=id value="<?php echo $student_id; ?>">
<tr align=center><td colspan=2><br><input type=submit name=submit value="Submit Changes">
   <input type=submit name=submit value="Cancel"></td></tr>
</table>
</form>
</center>

</body>
</html>
