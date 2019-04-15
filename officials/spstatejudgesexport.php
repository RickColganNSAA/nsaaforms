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

$sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t3.room,t4.class,t4.event,t4.round,t4.rounddate,TIME_FORMAT(t4.time,'%l:%i %p') AS time FROM judges AS t1, spstateassign AS t2, spstaterooms AS t3, spstaterounds AS t4 WHERE t1.id=t2.offid AND t2.roomid=t3.id AND t3.roundid=t4.id ";
if($givenday) $sql.="AND t4.rounddate='$givenday' ";
$sql.="ORDER BY t4.rounddate,t1.last,t1.first,t4.time";
$result=mysql_query($sql);
$csv="Last,First,Day,Time1,Class1,Event1,Round1,Room1,Time2,Class2,Event2,Round2,Room2,Time3,Class3,Event3,Round3,Room3,Time4,Class4,Event4,Round4,Room4,Time5,Class5,Event5,Round5,Room5,Time6,Class6,Event6,Round6,Room6\r\n";
$curoffid='0';
$string=$init_html;
$string.="<table width=100%><tr align=center><td>";
$string.="<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=4>";
$string.="<caption><h2>State Speech Judge Assignments</h2>";
$sql2="SELECT DISTINCT rounddate FROM spstaterounds ORDER BY rounddate";
$result2=mysql_query($sql2);
echo $string;
while($row2=mysql_fetch_array($result2))
{
   $date=split("-",$row2[0]);
   if($row2[0]==$givenday)
      echo "<font style=\"color:#A0A0A0\"><b><u>".date("l",mktime(0,0,0,$date[1],$date[2],$date[0]))."</u></b></font>&nbsp;&nbsp;&nbsp;&nbsp;";
   else
      echo "<a href=\"spstatejudgesexport.php?session=$session&givenday=$row2[0]\">".date("l",mktime(0,0,0,$date[1],$date[2],$date[0]))."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
}
if(!$givenday || $givenday=="") 
   echo "<font style=\"color:#A0A0A0\"><b><u>All Days</b></u></font>";
else
   echo "<a href=\"spstatejudgesexport.php?session=$session\">All Days</a>";
$string2="<p>Last updated: ".date("F j, Y")." at ".date("g:ia T")."</p></caption>";
$string2.="<tr align=center><td><b>Judge</b></td><td><b>Day</b></td><td><b>State Room Assignments</b></td></tr>";
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
      $csv.="$row[time],$row[class],$row[event],$row[round],$row[room],";
      $string2.="$row[time], $row[room], $row[class], $row[event]<br>";
   } 
   else
   {
      $curoffid=$offid;
      if($ix>0) 
      {
         $string2.="</td></tr>"; $csv.="\r\n";
      }
      $csv.="$last,$first,$day,$row[time],$row[class],$row[event],$row[round],$row[room],";
      $string2.="<tr valign=top align=left><td>$first $last<br>($row[city])</td><td>$day</td><td>";
      $string2.= "$row[time], $row[room], $row[class], $row[event]<br>";
   }
   $ix++;
}
$string2.="</td></tr></table><br>";
echo $string2;
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstatejudges.csv"),"w");
if(!fwrite($open,$csv)) echo "COULD NOT WRITE csv file<br>";
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatejudges.csv");
$string.=$string2.$end_html;
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstatejudges.html"),"w");
if(!fwrite($open,$string)) echo "COULD NOT WRITE html file<br>";
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatejudges.html");
if($level==1)
{
   echo "<a href=\"reports.php?session=$session&filename=spstatejudges.csv\">Download Excel Export</a><br>";
}
echo $end_html;
exit();

?>
