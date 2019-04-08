<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$lastyear=GetFallYear('sw');
$thisyear=$lastyear+1;

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);


echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<table cellspacing=0 cellpadding=5><caption><b>$lastyear-$thisyear Swimming and Diving Schedules</b><hr></caption>";

$sql="SELECT * FROM swschool ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM swsched WHERE (sid='$row[sid]' OR oppid='$row[sid]') ORDER BY meetdate";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<tr align=left><th colspan=3 align=left>$row[school]";
      echo "</b></th></tr>";
      echo "<tr align=left><td><b>Date</b><td><b>Meet Name</b></td>";
      echo "<td><b>Site</b></td></tr>";
   }
   while($row2=mysql_fetch_array($result2))
   {
      $date=split("-",$row2[meetdate]);
      echo "<tr align=left><td>$date[1]/$date[2]</td>";
      echo "<td>$row2[meetname]</td>";
      echo "<td>$row2[site]</td>";
      echo "</tr>";
   }
}
echo "</table>";
echo $end_html;
?>
