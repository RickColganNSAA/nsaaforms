<?php
/***************************************
spstatejudgesexport2.php
Export just like spstatejudgesexport.php
Except each judges' info is on a separate
page so they can print them out one at
a time for the packets
Created 3/8/10
Author Ann Gaffigan
****************************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

$sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t3.room,t4.class,t4.event,t4.round,t4.rounddate,TIME_FORMAT(t4.time,'%l:%i %p') AS time FROM judges AS t1, spstateassign AS t2, spstaterooms AS t3, spstaterounds AS t4 WHERE t1.id=t2.offid AND t2.roomid=t3.id AND t3.roundid=t4.id ";
$sql.="ORDER BY t4.rounddate,t1.last,t1.first,t4.time";
$result=mysql_query($sql);
$curoffid='0';
$string="\"Judge Name\",\"Day\",\"Slot 1\",\"Slot 2\",\"Slot 3\",\"Slot 4\",\"Slot 5\",\"Slot 6\"\r\n";
$ix=0;
while($row=mysql_fetch_array($result))
{
   $offid=$row[id];
   $first=trim($row[first]);
   $last=trim($row[last]);
   $date=split("-",$row[rounddate]);
   $day=date("l",mktime(0,0,0,$date[1],$date[2],$date[0]));
   if($offid==$curoffid)
   {
      $string.="\"$row[time], $row[room], $row[class], $row[event]\",";
   } 
   else
   {
      $curoffid=$offid;
      if($ix>0) 
      {
         $string.="\r\n"; 
      }
      //$string.="<div style='page-break-after:always;font-family:arial;'>";
      $string.="\"$first $last\",\"$day\",";
      $string.="\"$row[time], $row[room], $row[class], $row[event]\",";
   }
   $ix++;
}
   $open=fopen(citgf_fopen("/home/nsaahome/reports/spstatejudgeslabels.csv"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatejudgeslabels.csv");
   header("Location:reports.php?session=$session&filename=spstatejudgeslabels.csv");
exit();

?>
