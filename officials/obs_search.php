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

//show results of official search
//get query
$offtable=$sport."off";
$sql="SELECT DISTINCT t1.* FROM officials AS t1, $offtable AS t2, ".$sport."sched AS t3 WHERE t1.id=t2.offid AND t2.offid=t3.offid AND t1.$sport='x' AND t2.payment!='' AND ";
if($last!="")
   $sql.="t1.last LIKE '$last%' AND ";
if($first!="")
   $sql.="t1.first LIKE '$first%' AND ";
if($city!="~")
   $sql.="t1.city='$city' AND ";
if($month!='00' && $day!='00' && $year!='0000')
   $sql.="t3.offdate='".$year."-".$month."-".$day."' AND ";
$sql=substr($sql,0,strlen($sql)-5);
$sql.=" ORDER BY t1.last,t1.first";
$result=mysql_query($sql);

echo $init_html;
echo GetHeader($session);
echo "<br>";
echo "<table width=500><caption><b>Search Results:</b></caption>";
echo "<tr align=center><td><table cellspacing=2 cellpadding=2>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($ix%3==0)
      echo "<tr align=left>";
   echo "<td><a class=small href=\"obs_schedule.php?highlight=$year-$month-$day&session=$session&sport=$sport&offid=$row[id]\">$row[first] $row[last]</a></td>";
   if(($ix+1)%3==0)
      echo "</tr>";
   $ix++;
}
echo "</table></td></tr></table>";
echo "<br><br><a class=small href=\"welcome.php?session=$session&sport=$sport\">Start a New Search</a>";
?>
