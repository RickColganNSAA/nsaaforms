<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}

if($submit)
{
   for($i=0;$i<count($id);$i++)
   {
      $sql="UPDATE tr_standards SET classA='$classA[$i]', classB='$classB[$i]', classC='$classC[$i]', classD='$classD[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;
echo "<br><p><a class=\"small\" href=\"stateadmin.php?session=$session\">&larr; Return to Track & Field District Results</a></p>";
if($submit)
{
   echO "<br><font style=\"color:red\"><b>Your changes have been saved.</b></font><br>";
}
echo "<br><form method=post action=\"standards.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table frame=all rules=all cellspacing=0 cellpadding=5 style=\"border:#808080 1px solid;\">";
echo "<caption><b>".date("Y")." Track & Field State Meet Qualifying Standards:</b></caption>";
echo "<tr align=center><td><b>Gender</b></td><td><b>Event</b></td>";
echo "<td><b>Class A</b></td><td><b>Class B</b></td><td><b>Class C</b></td><td><b>Class D</b></td></tr>";
$sql="SELECT * FROM tr_standards ORDER BY gender,event";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>$row[gender]</td><td>$row[event]</td>";
   echo "<input type=hidden name=\"id[$i]\" value=\"$row[id]\">";
   echo "<td><input type=text class=tiny size=6 name=\"classA[$i]\" value=\"$row[classA]\"></td>";
   echo "<td><input type=text class=tiny size=6 name=\"classB[$i]\" value=\"$row[classB]\"></td>";
   echo "<td><input type=text class=tiny size=6 name=\"classC[$i]\" value=\"$row[classC]\"></td>";
   echo "<td><input type=text class=tiny size=6 name=\"classD[$i]\" value=\"$row[classD]\"></td>";
   echo "</tr>";
   $i++;
}
echo "</table><br><input type=submit name=submit value=\"Save\"></form>";
echo $end_html;
?>
