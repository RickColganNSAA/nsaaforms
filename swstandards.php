<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
if($submit)
{
   for($i=0;$i<count($event);$i++)
   {
      if(!ereg("diving",$event[$i]))
      {
         $automark="$amin[$i]:$asec[$i].$atenth[$i]";
         $qualmark="$smin[$i]:$ssec[$i].$stenth[$i]";
         $automarksec=($amin[$i]*60)+$asec[$i]+($atenth[$i]/100);
         $automarksec=number_format($automarksec,2,'.','');
         $qualmarksec=($smin[$i]*60)+$ssec[$i]+($stenth[$i]/100);
         $qualmarksec=number_format($qualmarksec,2,'.','');
      }
      else
      {
         $automark=$adiving[$i];
         $qualmark=$sdiving[$i];
         $automarksec=$automark;
         $qualmarksec=$qualmark;
      }
      $sql="UPDATE sw_qualify SET qualmark='$qualmark',qualmarksec='$qualmarksec',automark='$automark',automarksec='$automarksec' WHERE event='$event[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"swstandards.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><a class=small href=\"swstate.php?session=$session\">Return to State Swimming Admin</a><br><br>";
echo "<table cellspacing=0 cellpadding=2 border=1 bordercolor=#000000><caption><b>Swimming Qualifying Standards:</b><br><br></caption>";
echo "<tr align=center><td><b>Event</b></td><td><b>Automatic</b></td><td><b>Secondary</b></td></tr>";
$sql="SELECT * FROM sw_qualify ORDER BY event";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"event[$ix]\" value=\"$row[event]\">";
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor=#E0E0E0";
   echo "><td><b>$row[eventfull]:</b></td>";
   if(!ereg("diving",$row[event]))
   {
      $temp=split("[:.]",$row[automark]);
      $min=$temp[0]; $sec=$temp[1]; $tenth=$temp[2];
      echo "<td><input type=text class=tiny maxlength=2 size=2 value=\"$min\" name=\"amin[$ix]\">:";
      echo "<input type=text class=tiny maxlength=2 size=2 value=\"$sec\" name=\"asec[$ix]\">.";
      echo "<input type=text class=tiny maxlength=2 size=2 value=\"$tenth\" name=\"atenth[$ix]\"></td>";
      $temp=split("[:.]",$row[qualmark]);
      $min=$temp[0]; $sec=$temp[1]; $tenth=$temp[2];
      echo "<td><input type=text class=tiny maxlength=2 size=2 value=\"$min\" name=\"smin[$ix]\">:";
      echo "<input type=text class=tiny maxlength=2 size=2 value=\"$sec\" name=\"ssec[$ix]\">.";
      echo "<input type=text class=tiny maxlength=2 size=2 value=\"$tenth\" name=\"stenth[$ix]\"></td>";
   }
   else
   {
      echo "<td><input type=text class=tiny maxlength=3 size=3 value=\"$row[automark]\" name=\"adiving[$ix]\"></td>";
      echo "<td><input type=text class=tiny maxlength=3 size=3 value=\"$row[qualmark]\" name=\"sdiving[$ix]\"></td>";
   }
   echo "</tr>";
   $ix++;
}
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save\"></form>";
echo $end_html;
?>
