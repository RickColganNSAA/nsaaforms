<?php
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

$dbscores="$db_name";
$dboffs="$db_name2";

$sql0="SELECT DISTINCT class FROM spdistricts WHERE class!='' ORDER BY class";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $class=$row0['class'];
   $csv="";
$sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t3.id AS roomid,t3.room,t3.section,t4.class,t4.event,t4.round,t4.rounddate,TIME_FORMAT(t4.time,'%l:%i %p') AS time FROM $dboffs.judges AS t1, $dboffs.spstateassign AS t2, $dboffs.spstaterooms AS t3, $dboffs.spstaterounds AS t4 WHERE t1.id=t2.offid AND t2.roomid=t3.id AND t3.roundid=t4.id AND t4.class='$class' ORDER BY t1.last,t1.first,t4.event,t4.round";
$result=mysql_query($sql);
$csv.="Judge First,Judge Last,Time,Class,Event,Round,Section,Room\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="$row[first],$row[last],$row[time],$row[class],$row[event],$row[round],$row[section],$row[room]\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstatespeedballotexport".$class.".csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatespeedballotexport".$class.".csv");
echo "<a href=\"reports.php?session=$session&filename=spstatespeedballotexport".$class.".csv\">Class $class: spstatespeedballotexport".$class.".csv</a><br>";
}

function GetYear($semester)
{
  //return year in school, given the semester
  if(!$semester) return "";
  if($semester==1 || $semester==2)
    return 9;
  else if($semester==3 || $semester==4)
    return 10;
  else if($semester==5 || $semester==6)
    return 11;
  else if($semester==7 || $semester==8)
    return 12;
  else if($semester<1)
    return "<9";
  else if($semester>8)
    return ">12";
  else return "";
}
?>
