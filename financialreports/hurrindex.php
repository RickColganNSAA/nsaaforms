<?php

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

if($submit)
{
   for($i=0;$i<count($formid);$i++)
   {
      if($delete[$i]=='x')
      {
	 $sql="DELETE FROM finance_hurr WHERE id='$formid[$i]'";
	 $result=mysql_query($sql);
      }
      $notes[$i]=addslashes($notes[$i]);
      $sql="UPDATE finance_hurr SET notes='$notes[$i]' WHERE id='$formid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"hurrindex.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption><b>Hurricane Relief Fund Basketball Game Financial Forms:</b></caption>";
echo "<tr align=center>";
echo "<th class=small>Delete</th>";
echo "<td><a class=small href=\"hurrindex.php?sort=gender&session=$session\">Gender</a></td>";
echo "<td><a class=small href=\"hurrindex.php?sort=site&session=$session\">Site</a><br>(Click to Open Form)</td>";
echo "<td><a class=small href=\"hurrindex.php?sort=gamedate&session=$session\">Game<br>Date</a></td>";
echo "<td><a class=small href=\"hurrindex.php?sort=hostschool&session=$session\">Host</a></td>";
echo "<td><a class=small href=\"hurrindex.php?sort=oppschool&session=$session\">Opponent</a></td>";
echo "<td><a class=small href=\"hurrindex.php?sort=datesub&session=$session\">Date<br>Submitted</a></td>";
echo "<td><b>Notes</b></td>";
echo "</tr>";

if(!$sort || $sort=='') $sort="datesub";
$sql="SELECT * FROM finance_hurr ORDER BY $sort";
if($sort=="datesub") $sql.=" DESC";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left>";
   echo "<input type=hidden name=\"formid[$ix]\" value=\"$row[id]\">";
   echo "<td align=center><input type=checkbox name=\"delete[$ix]\" value='x'></td>";
   echo "<td>";
   if($row[gender]=='f') echo "Girls";
   else if($row[gender]=='m') echo "Boys";
   else echo "Boys & Girls";
   echo "</td>";
   echo "<td><a class=small target=new href=\"hurrfinance.php?session=$session&gameid=$row[id]\">$row[site]</a></td>";
   echo "<td>".date("m/d/Y",$row[gamedate])."</td>";
   echo "<td>$row[hostschool]</td><td>$row[oppschool]</td>";
   echo "<td>".date("m/d/Y",$row[datesub])."</td>";
   echo "<td><input type=text class=tiny size=25 name=\"notes[$ix]\" value=\"$row[notes]\"></td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";
echo "<br><input type=submit name=submit value=\"DELETE Checked & SAVE Notes\">";
echo "</form>";
echo $end_html;
?>
