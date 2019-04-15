<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<center><br>";

if(!$distid) $distid=1;
$sql="SELECT * FROM sbdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo "<table>";
echo "<caption><b>District $row[class]-$row[district] at $row[site] ($row[hostschool]</b><hr></caption>";
echo "<tr align=left><th align=left>Director:</th><td>$row[email]</td></tr>";
echo "<tr align=left><th align=left valign=top>Schools Assigned:</th><td>$row[schools]<br><br></td></tr>";
$schools=$row[schools];
$class=$row['class'];
$district=$row[district];
$sch=split(",",$schools);
$days=split("/",$row[dates]);
for($i=0;$i<count($days);$i++)
{
   $curday=split("-",$days[$i]);
   echo "<tr align=left valign=top><td><b>".date("F j, Y",mktime(0,0,0,$curday[1],$curday[2],$curday[0]))."</b></td>";
   echo "<td><br><br>";
   $j=0;
   while($j<count($sch))
   {
      echo "Time:&nbsp;___________:&nbsp;______________________________________________________________<br><br>";
      $j++;
   }
   echo "</td></tr>";
}
echo "</table>";
echo $end_html;
?>
