<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!$sport || $sport=='')
{
   echo "No sport given";
   exit();
}
else if(!$siteid || $siteid=='')
{
   echo "No site given";
   exit();
}
$ruleshosts=$sport."ruleshosts";
$sportname=GetSportName($sport);

//get info on this rules meeting:
$sql="SELECT * FROM $ruleshosts WHERE id='$siteid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type=$row[type];
$origsiteid=$row[origsiteid];
$hostname=$row[hostname];
$date=split("-",$row[mtgdate]);
$year1=$date[0];
if($date[1]<=6) $year1--;
$year2=$year1+1;
$mtgtime=$row[mtgtime];
$location=$row[location];
$contactname=$row[contactname]; $contacttitle=$row[contacttitle];
$contactphone=$row[contactphone];
$equipment=$row[equipment];
$post=$row[post]; $accept=$row[accept]; $confirm=$row[confirm];

//get info on the host:
$hostname2=addslashes($hostname);
$sql="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND (level='2' OR level='4' OR level='6')";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostlevel=$row[level];
$name=$row[name];
if($hostlevel==2 && $name=='')
{
   $sql="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND sport='Activities Director'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name=$row[name];
}
if($hostlevel=='4' || $hostlevel=='6')
   $sql="SELECT * FROM $db_name.logins WHERE school='$hostname2'";
else
   $sql="SELECT * FROM $db_name.headers WHERE school='$hostname2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$address=$row[address1];
if($row[address2]!='') $address.="<br>$row[address2]";
$city_state=$row[city_state]; $zip=$row[zip];

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td colspan=2>";
echo "NEBRASKA SCHOOL ACTIVITIES ASSOCIATION<br>";
echo "8230 Beechwood Drive, P.O. Box 5447<br>";
echo "Lincoln, Nebraska  68505-0447<br>(402) 489-0386<br><br></td></tr>";
echo "<tr align=left valign=top><td>TO:</td>";
echo "<td>";
if($contactname!='')
   echo "$contactname<br>";
else if($name!='') 
   echo "$name<br>";
echo "$hostname";
if($address!='')
   echO "<br>$address<br>$city_state&nbsp;$zip</td></tr>";
else
   echo "</td></tr>";
echo "<tr align=left><td>FROM:</td><td>Larry Mollring, Assistant Director</td></tr>";
echo "<tr align=left><td>SUBJECT:</td><td>$year1-$year2 $sportname Rules Meeting Reminder</td></tr>";
echo "<tr align=left><td>DATE:</td><td>".date("F j, Y")."</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>This is a reminder of the rules meeting scheduled ar your school/service unit:<br>";
echo "<table><tr><td align=right><b>Meeting:</b></td><td align=left>$sportname</td></tr>";
echo "<tr><td align=right><b>Date:</b></td><td align=left>".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."</td></tr>";
echo "<tr><td align=right><b>Location:</b></td><td align=left>$location</td></tr>";
echo "<tr><td align=right><b>Starting Time:</b></td><td align=left>$mtgtime</td></tr>";
echo "</table></td></tr>";
if($type=="Receiving")
{
   echo "<tr align=left><td>&nbsp;</td><td>HOST'S CHECKLIST:<br><ul>";
   echo "<li>The meeting should start promptly and last around an hour.</li><br>";
   echo "<li>The distance learning lab should be open and staffed approximately 1/2 hour before the scheduled starting time.</li><br>";
   echo "<li>The connection with the originating site should be up and running approximately 15 minutes before the starting time.</li><br>";
   echo "<li>Besides having the facility ready for this meeting, we would appreciate any publicity you could give this meeting in your area prior to the meeting date.</li><br>";
   echo "<li>Please post signs directing coaches and officials to the meeting room.</li><br>";
   echo "<li>Keep accurate account of head coaches and officials on the sign-up sheets.  Return sign-up sheets and all extra handouts IMMEDIATELY (the next day) in the postage paid container.</li>";
   echo "</ul>";
   echo "</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>The NSAA appreciates your assistance in hosting this rules meeting.  A $15.00 check is included along with this reminder for your assistance in conducting this meeting.</td></tr>";
   echo "<tr align=left><td colspan=2><br><b>Enclosures:</b><br>";
   echo "NSAA Rules Meeting Schedule<br>$15.00 Check for meeting supervisor<br>Sign-up sheets</td></tr>";
}
else if($type=="Originating")
{
   echo "<tr align=left><td>&nbsp;</td><td>The basic needs for the meeting will be a room large enough to accommodate the expected attendance, equipped with a screen. All presentations will be PowerPoint presentations. The NSAA will provide the equipment necessary for this.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>If your site is the originating site for distance learning, arrangements will need to be made with your distance learning coordinator for the receiving sites to be up and running for the $mtgtime meeting.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>The meeting will start promptly and and should last about 1 1/2 hours.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>The interpreter will arrive about one-half hour before the scheduled starting time.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>Besides having the facility ready for this meeting, we would appreciate any publicity you could give this meeting in your area prior to the meeting date.</td></tr>";
   echo "<tr align=left><td colspan=2><br>Enclosure: $year1-$year2 NSAA Rules Meeting Schedule</td></tr>";
}
else
{
   echo "<tr align=left><td>&nbsp;</td><td>The basic needs for Basketball, Wrestling, and Swimming meetings will be a room large enough to accommodate the expected attendance, equipped with a screen. All presentations will be PowerPoint presentations. The NSAA will provide the equipment necessary for this.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>The meeting will start promptly and and should last about 1 1/2 hours.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>The interpreter will arrive about one-half hour before the scheduled starting time.</td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>Besides having the facility ready for this meeting, we would appreciate any publicity you could give this meeting in your area prior to the meeting date.</td></tr>";
   echo "<tr align=left><td colspan=2><br>Enclosure: $year1-$year2 NSAA Rules Meeting Schedule</td></tr>";
}
echo "</table>";

echo $end_html;
exit();
?>
