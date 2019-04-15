<?php
/*********************************
spfinaljudges.php

Export of FINAL ROUND Judges like:

Class A Humorous
3:30pm-CH 131
Sandy Hall
Susie Smith
Joe Schmoe

(Order: class/time, event, room)

Created 3/8/10
Author: Ann Gaffigan
**********************************/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$sql="SELECT DISTINCT class FROM spstaterounds WHERE round='3' ORDER BY class";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $class=$row['class'];
   for($i=0;$i<count($spevents3);$i++)
   {
      $event=$spevents2[$i];
      $txt.="Class $class $spevents3[$i]\r\n";
      $sql2="SELECT TIME_FORMAT(t1.time,'%l:%i %p') AS time,t2.* FROM spstaterounds AS t1, spstaterooms AS t2 WHERE t1.id=t2.roundid AND t1.round='3' AND t1.class='$class' AND t1.event='$event'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $row2[time]=eregi_replace("AM","a.m.",$row2[time]);
      $row2[time]=eregi_replace("PM","p.m.",$row2[time]);
      $txt.="$row2[time]-$row2[room]\r\n";
      $sql2="SELECT t1.* FROM judges AS t1,spstateassign AS t2 WHERE t1.id=t2.offid AND t2.roomid='$row2[id]' ORDER BY t1.last,t1.first";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $txt.=trim($row2[first])." ".trim($row2[last])."\r\n";
      }
      $txt.="\r\n";
   }
}	
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstatefinaljudges.txt"),"w");
fwrite($open,$txt);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatefinaljudges.txt");
header("Location:reports.php?session=$session&filename=spstatefinaljudges.txt");
?>
