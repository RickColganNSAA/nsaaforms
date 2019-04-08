<?php
//add_students.php: redirects AD's to blank eligibility form.
//Prompt NSAA-user for school and then on submit goes to blank elig form

require 'functions.php';

//Validate User
if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php");
   exit();
}

$level=GetLevel($session);
if($level==2 || $submit=="Go")	//AD or NSAA chose a school
{
   header("Location:blank_elig_list.php?session=$session&school_ch=$school_ch&activity_ch=$activity_ch");
   exit();
}

if($submit=="Cancel")
{
   header("Location:eligibility.php?session=$session&school_ch=$school_ch&activity_ch=$activity_ch&last=a");
   exit();
}

?>

<html>
<head>
<title>NSAA Home</title>
<link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<?php 
$header=GetHeader($session);
echo $header;
?>
<center>
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

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
