<?php
//edit_coop_info.php: pop-up window to edit team info for co-op
//  team for specific sport

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!$school_ch && GetLevel($session)!=1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

//submit co-op information:
if($save=="Save & Close")
{
   if($reset=='y')
   {
      //take special entry out of table
      $abbrev=GetActivityAbbrev2($sport);
      $sql="DELETE FROM coop_schools WHERE sport='$abbrev' AND (school1='$school2' OR school2='$school2')";
      $result=mysql_query($sql);
   }
   else
   {
   //update database table coop_schools with new co-op team info for this sport
      $school_1=$school2;
      $school_2=ereg_replace("\'","\'",$school_2);
      $abbrev=GetActivityAbbrev2($sport);
      $coopname=ereg_replace("\'","\'",$coopname);
      $coopname=ereg_replace("\"","",$coopname);
      $coopmascot=ereg_replace("\'","\'",$coopmascot);
      $coopmascot=ereg_replace("\"","",$coopmascot);
      $coopcolors=ereg_replace("\'","\'",$coopcolors);
      $coopcolors=ereg_replace("\"","",$coopcolors);
      $coopcoach=ereg_replace("\'","\'",$coopcoach);
      $coopcoach=ereg_replace("\"","",$coopcoach);

      $sql="SELECT id FROM coop_schools WHERE sport='$abbrev' AND ((school1='$school_1' AND school2='$school_2') OR (school1='$school_2' AND school2='$school_1'))";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
         $sql2="INSERT INTO coop_schools (school1,school2,coopname,coopmascot,coopcolors,coopcoach,sport) VALUES ('$school_1','$school_2','$coopname','$coopmascot','$coopcolors','$coopcoach','$abbrev')";
      }
      else					//UPDATE
      {
         $row=mysql_fetch_array($result);
         $sql2="UPDATE coop_schools SET school1='$school_1', school2='$school_2', coopname='$coopname', coopmascot='$coopmascot', coopcolors='$coopcolors', coopcoach='$coopcoach' WHERE id='$row[0]'";
      }
      $result2=mysql_query($sql2);
   }
?>
<script language="javascript">
window.opener.location.reload();
window.close();
</script>
<?php
exit();
}
?>

<html>
<head>
<title>Edit Co-Op Team Information</title>
<link href="../css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<center>
<form name=coopinfo method="post" action="edit_coop_info.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=sport value="<?php echo $sport; ?>">

<table>

<?php
//check if they already have this info in database
$abbrev=GetActivityAbbrev2($sport);
$sql="SELECT * FROM coop_schools WHERE (school1='$school2' OR school2='$school2') AND sport='$abbrev'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[1]==$school) $school_2=$row[2];
   else $school_2=$row[1];
   $coopname=$row[3];
   $coopmascot=$row[4];
   $coopcolors=$row[5];
   $coopcoach=$row[6];
}

echo "<br><caption><b>$school $sport Co-Op Team Information:<hr></b></caption>";
echo "<tr align=left><th>School 1:</th><td>$school</td></tr>";
echo "<tr align=left><th>School 2:</th>";
echo "<td><select name=\"school_2\">";
echo "<option>Choose School";
for($j=0;$j<count($schools);$j++)
{
   echo "<option";
   if($school_2==$schools[$j]) echo " selected";
   echo ">$schools[$j]";
}
echo "</select></td></tr>";
echo "<tr align=left><th>Co-Op Team Name:</th>";
echo "<td><input type=text name=coopname value=\"$coopname\" size=40></td></tr>";
echo "<tr align=left><th>Co-Op Team Mascot:</th>";
echo "<td><input type=text name=coopmascot value=\"$coopmascot\" size=40></td></tr>";
echo "<tr valign=top align=left><th>Co-Op Team Colors:</th>";
echo "<td><input type=text name=coopcolors value=\"$coopcolors\" size=40><br>";
echo "(Please separate colors with a '/')</td></tr>";
echo "<tr align=left><th>Co-Op Team Coach:</th>";
echo "<td><input type=text name=coopcoach value=\"$coopcoach\" size=40></td></tr>";
echo "<tr align=left><td colspan=2><input type=checkbox name=reset value='y'>&nbsp;Check here to <b>reset</b> your team name to your school name, and your mascot and colors to your school's mascot and colors.</td></tr>";
?>
</table>
<br>
<input type=submit name=save value="Save & Close">
</center>
</form>
</body></html>
