<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($save)
{
   for($i=0;$i<count($id);$i++)
   {
      if($checkall=='x' || $check[$i]=='x')
      {
	 $sql="UPDATE eligibility_sw SET hidden='x' WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
   }
} 

echo $init_html;
echo "<form method=post action=\"swelig2.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<table width=100%><tr align=center><td><br>";
if($save)
   echo "<div class='alert' style='width:400px;'>The checked students have been hidden from view.</div><br>";

$sql="SELECT t1.*,t2.id AS eligid,t2.dateadded FROM eligibility AS t1, eligibility_sw AS t2 WHERE t1.id=t2.studentid ";
if($view!='all') $sql.="AND t2.hidden!='x' ";
if(!$sort || $sort=="school") $sort="t1.school,t1.gender,t2.dateadded DESC";
$sql.="ORDER BY $sort";
$result=mysql_query($sql);
echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style='border:#808080 1px solid;'><caption><b>Eligible Boys & Girls Swimmers, in order of Most Recently Added:</b><br>";
if($view!='all')
   echo "<a href=\"swelig2.php?session=$session&view=all\">View ALL eligible swimmers, including those that have been HIDDEN</a>";
else
   echo "<a href=\"swelig2.php?session=$session\">View just the eligible swimmers that have NOT been hidden yet</a>";
echo "</caption>";
echo "<tr align=center><td><a href=\"swelig2.php?session=$session&view=$view&sort=school\">School</a></td><td><b>Student</b></td><td><b>Gender</b></td><td><b>Semester</b></td><td><a href=\"swelig2.php?session=$session&view=$view&sort=t2.dateadded DESC\">Date Added</a></td><td>Hide from View<br><input type=checkbox name=\"checkall\" value=\"x\">Check ALL</td></tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor=#F0F0F0";
   echo "><td><input type=hidden name=\"id[$ix]\" value=\"$row[eligid]\">$row[school]</td><td>$row[first] $row[middle] $row[last]</td>";
   echo "<td align=center>".strtoupper($row[gender])."</td>";
   $year=GetYear($row[semesters]);
   switch($year)
   {
      case "9":
         $grade="FR";
         break;
      case "10":
	 $grade="SO";
	 break;
      case "11":
	 $grade="JR";
   	 break;
      case "12":
	 $grade="SR";
	 break;
      default:
	 $grade="??";
   }
   echo "<td align=center>$grade</td>";
   echo "<td align=center>".date("m/d/y",$row[dateadded])." at ".date("g:ia T",$row[dateadded])."</td>";
   echo "<td align=center><input type=checkbox name=\"check[$ix]\" value=\"x\"></td>";
   echo "</tr>";
   $ix++;
}
echo "<tr align=right><td colspan=6><input type=submit name=\"save\" value=\"Hide Checked Students\"></td></tr>";
echo "</table>";
echo "</form>";

echo $end_html;
?>
