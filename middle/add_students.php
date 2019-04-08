<?php
/*************************************
add_students.php
Redirects AD's to blank eligibility form.
Prompt NSAA-user for school and then on submit goes to blank elig form
copied from ../add_students.php 12/26/09
Author: Ann Gaffigan
**************************************/

require '../functions.php';

//Validate User
if(!ValidUser($session))
{
   header("Location:index.php?error=");
   exit();
}

$level=GetLevel($session);
if($level==8 || $submit=="Go")	//AD or NSAA chose a school
{
   header("Location:blank_elig_list.php?session=$session&school_ch=$school_ch");
   exit();
}

if($submit=="Cancel")
{
   header("Location:eligibility.php?session=$session&school_ch=$school_ch&last=a");
   exit();
}

echo $init_html;
echo GetHeader($session);
?>
<br><br><br>
<form method=post action="add_students.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
<table>
<tr align=center>
<td>
<?php
//get schools
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}
?>
<select name=school_ch>
<?php
for($i=0;$i<count($schools);$i++)
{
   echo "<option";
   if($schools[$i]==$school_ch) echo " selected";
   echo ">$schools[$i]";
}
?>
</select>
<input type=submit name=submit value="Go">
<input type=submit name=submit value="Cancel">
</td>
</tr>
</table>
</form>
<?php echo $end_html; ?>
