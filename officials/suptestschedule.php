<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

echo $init_html;
echo "<table width=100%><tr align=center><td>";

if($sport && $sport!='')
{
   $sportname=GetSportName($sport);
   echo "<table width=800 class=nine>";
   echo "<caption>";
   echo "<b>Nebraska Schools Activities Association<br>2011-2012 $sportname Supervised Test Schedule<br></b>";
   echo "<br>Starting time is 7:00 PM local time, unless otherwise indicated.";
   echo "</caption>";
   $sql="SELECT * FROM suptesthosts WHERE showsched='x' AND sports LIKE '%$sport%' ORDER BY mtgdate,hostname";
   $result=mysql_query($sql);
   echo "<tr align=left><td colspan=2><b><u>$sportname Supervised Tests</u></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $date=split("-",$row[mtgdate]);
      echo "<tr align=left><td width=100>".date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]))."</td>";
      echo "<td>$row[hostname]";
      $hostname2=addslashes($row[hostname]);
      $sql3="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND level='2'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)>0) echo " High School";
      if($row[location]!='') echo ", $row[location]";
      if($row[mtgtime]!="7:00 PM local time" && $row[mtgtime]!='') echo ", <b>$row[mtgtime]</b>";
      echo "</td></tr>";
   }
}
else
{
   echo "<table width=800 class=nine>";
   echo "<caption>";
   echo "<b>Nebraska Schools Activities Association<br>2011-2012 Supervised Test Schedule<br></b>";
   echo "<br>Starting time is 7:00 PM local time, unless otherwise indicated.";
   echo "</caption>";
$sql="SHOW TABLES LIKE '%off'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($row[0]!='swoff' && $row[0]!='dioff')
   {
      $temp=split("off",$row[0]);
      $cursp=$temp[0];
      $sportname=GetSportName($cursp);
      echo "<tr align=left><td colspan=2><br><b><u>$sportname Supervised Tests</u></td></tr>";
      $sql2="SELECT * FROM suptesthosts WHERE showsched='x' AND sports LIKE '%$cursp%' ORDER BY mtgdate,hostname";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $date=split("-",$row2[mtgdate]);
         echo "<tr align=left><td width=100>".date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]))."</td>";
         echo "<td>$row2[hostname]";
	 $hostname2=addslashes($row2[hostname]);
         $sql3="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND level='2'";
         $result3=mysql_query($sql3);
	 if(mysql_num_rows($result3)>0) echo " High School";
	 if($row2[location]!='') echo ", $row2[location]";
         if($row2[mtgtime]!="7:00 PM local time" && $row2[mtgtime]!='') echo ", <b>$row2[mtgtime]</b>";
         echo "</td></tr>";
      }
      $ix++;
   }
}
}
echo "</table></td></tr></table>";

echo $end_html;
exit();
?>
