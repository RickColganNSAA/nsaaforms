<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<table width=800 class=nine>";
echo "<caption>";
echo "<b>Nebraska Schools Activities Association<br>".GetSchoolYear()." ";
if($sport && $sport!='') echo GetSportName($sport)." ";
echo "Rules Meeting Schedule</b>";
echo "<p>All officials and head coaches are <u>REQUIRED</u> to <u>COMPLETE ONLINE</u> a rules meeting for their activity.</p>";
echo "<p><b>Rules meeting must be completed prior to midnight to meet the deadline.</b></p>";
echo "</caption>";

//GET DATES 
$sql2="SELECT * FROM rulesmeetingdates ";
if($sport=='sppp') $sql2.="WHERE (sport='sp' OR sport='pp') ";
else if($sport && $sport!='') $sql2.="WHERE sport='$sport' ";
$sql2.="ORDER BY startdate,sport";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $sportname=GetSportName($row2[sport]); $cursp=$row2[sport];
   if($sportname!="Sport")
   {
      echo "<tr align=left><td colspan=2><br><b><u>".GetSportName($row2[sport])." Rules Meetings</u></td></tr>";
      if($row2[startdate]=="0000-00-00" || $row2[paydate]=="0000-00-00" || $row2[latedate]=="0000-00-00" || $row2[enddate]=="0000-00-00")
      { 
	 echo "<tr align=left><td colspan=2>ONLINE $sportname Rules Meetings will be available during a time period that will be announced at a later date.</td></tr>";
      }
      else
      {
         echo "<tr align=left><td colspan=2>ONLINE $sportname Rules Meetings Available:</td></tr>";
         echo "<tr align=left><td colspan=2><table style=\"margin-left:20px;\" cellspacing=2 cellpadding=2>";
         $start=split("-",$row2[startdate]); $end=split("-",$row2[enddate]); $late=split("-",$row2[latedate]); $pay=split("-",$row2[paydate]);
         $latesec=mktime(0,0,0,$late[1],$late[2],$late[0]); $latesec1=$latesec+(25*60*60);
	 $paysec=mktime(0,0,0,$pay[1],$pay[2],$pay[0]); $paysec1=$paysec+(25*60*60);
         if($cursp=='sp' || $cursp=='pp' || $cursp=='sppp') $officials="Judges";
	 else $officials="Officials";
         echo "<tr align=left><td>".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." - ".date("F j, Y",$paysec)."</td><td>No Charge to $officials & Head Coaches</td></tr>";
         echo "<tr align=left><td>".date("F j, Y",$paysec1)." - ".date("F j, Y",$latesec)."</td><td>Basic $".number_format($row2[fee],2,'.','')." Fee - $officials & Head Coaches</td></tr>";
         echo "<tr align=left><td>".date("F j, Y",$latesec1)." - ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))."<?td><td>Late/Penalty $".number_format($row2[latefee],2,'.','')." Fee - $officials & Head Coaches</td></tr>";
         echo "</table></td></tr>";
      }
   }
} 
echo "</table>";
echo $end_html;
exit();
?>
