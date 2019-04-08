<?php
/*******************************************************
oossanctionedevents.

NSAA uses this form to add/edit/delete sanctioned events taking
place outside of Nebraska 

Created: 11/23/09
Author: Ann Gaffigan
*********************************************************/

require '../functions.php';
require '../variables.php';
require 'sanctionvariables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($save)
{
   $eventname=addslashes($eventname);
   $startdate="$yr1-$mo1-$day1";
   if($mo2!='00' && $day2!='00') $enddate="$yr2-$mo2-$day2";
   else $enddate=$startdate;
   $sanctiondate="$yr-$mo-$day";
   $city=addslashes($city);
   $country=addslashes($country);
   if($id)
   {
      $sql="UPDATE oossanctionedevents SET eventname='$eventname',startdate='$startdate',enddate='$enddate',sanctiondate='$sanctiondate',city='$city',state='$state',country='$country',sport='$sport' WHERE id='$id'";
      $result=mysql_query($sql);
      unset($id);
      $saved=1;
   }
   else
   {
      $sql="INSERT INTO oossanctionedevents (sport,eventname,startdate,enddate,sanctiondate,city,state,country) VALUES ('$sport','$eventname','$startdate','$enddate','$sanctiondate','$city','$state','$country')";
      $result=mysql_query($sql);
      $added=1;
   }
}
else if($delete)
{
   $sql="DELETE FROM oossanctionedevents WHERE id='$delete'";
   $result=mysql_query($sql);
   unset($delete);
   $deleted=1;
}

echo $init_html;
echo $header;

//ADD OR EDIT ($id given)
if($id)
{
   $sql="SELECT * FROM oossanctionedevents WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
}
echo "<br>";
if($saved)
   echo "<div class='alert' style='width:400px;'>Your changes have been saved.</div><br>";
else if($added)
   echo "<div class='alert' style='width:400px;'>The event has been added below.</div><br>";
else if($deleted)
   echo "<div class='alert' style='width:400px;'>The event has been deleted.</div><br>";
echo "<form method=post action='oossanctionedevents.php'>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=3 cellpadding=3 class=nine><caption><b>";
if($id) echo "You are editing \"$row[eventname]\":";
else echo "Add a NEW out-of-state Sanctioned Event:";
echo "</b></caption>";
echo "<tr align=left><td>Sport:</td><td><select name=\"sport\"><option value=''>~</option>";
$spacts=array_merge($sanctionsp,$sanctionact);
$spacts2=array_merge($sanctionsp2,$sanctionact2);
for($i=0;$i<count($spacts);$i++)
{
   if(!ereg("Season",$spacts[$i]))
   {
      echo "<option value=\"".$spacts2[$i]."\"";
      if($row[sport]==$spacts2[$i]) echo " selected";
      echo ">".$spacts[$i]."</option>";
   }
}
echo "</select></td></tr>"; 
echo "<tr align=left><td>Title of Event:</td><td><input type=text size=45 name=\"eventname\" value=\"$row[eventname]\"></td></tr>";
echo "<tr align=left><td>City, State & Country (if not US):</td><td><input type=text size=15 name=\"city\" value=\"$row[city]\">, <input type=text name=\"state\" value=\"$row[state]\" size=3 maxlength=2> Country: <input type=text name=\"country\" size=15 value=\"$row[country]\"></td></tr>";
echo "<tr align=left><td>Date(s) of Event:</td><td>from ";
echO "<select name=\"mo1\"><option value='00'>MM</option>";
$start=split("-",$row[startdate]);
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if($start[1]==$m) echo " selected";
   echo ">$m</option>";
}
echO "</select>/<select name=\"day1\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if($start[2]==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr1\">";
$year1=date("Y")-1; $year2=$year1+2;
if(!$id) $start[0]=date("Y");
for($i=$year1;$i<=$year2;$i++)
{
   echo "<option value=\"$i\"";
   if($start[0]==$i) echo " selected";
   echo ">$i</option>";
}
echo "</select>";
echo " to ";
echO "<select name=\"mo2\"><option value='00'>MM</option>";
$end=split("-",$row[enddate]);
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if($end[1]==$m) echo " selected";
   echo ">$m</option>";
}
echO "</select>/<select name=\"day2\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if($end[2]==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr2\">";
if(!$id) $end[0]=date("Y");
for($i=$year1;$i<=$year2;$i++)
{
   echo "<option value=\"$i\"";
   if($end[0]==$i) echo " selected";
   echo ">$i</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td>Date this event was sanctioned:</td>";
echO "<td><select name=\"mo\"><option value='00'>MM</option>";
$date=split("-",$row[sanctiondate]);
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if($date[1]==$m) echo " selected";
   echo ">$m</option>";
}
echO "</select>/<select name=\"day\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if($date[2]==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr\">";
if(!$id) $date[0]=date("Y");
for($i=$year1;$i<=$year2;$i++)
{
   echo "<option value=\"$i\"";
   if($date[0]==$i) echo " selected";
   echo ">$i</option>";
}
echo "</select></td></tr>";
echO "<tr align=center><td colspan=2><input type=submit name=\"save\" ";
if($id) echo "value=\"Save Changes\"";
else echo "value=\"Add Event\"";
echo "></td></tr>";
echo "</table>";
echo "</form>";

//EVENTS ALREADY IN DATABASE
echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>Out-of-State Sanctioned Events in the Database:</b> <a href=\"sanctions.php\" target=\"_blank\">Preview Sanctioned Events on NSAA Website</a></caption>";
if(!$sort || $sort=="") $sort="sport ASC";
$sql="SELECT * FROM oossanctionedevents WHERE enddate>=CURDATE() ORDER BY $sort";
$result=mysql_query($sql);
echo "<tr align=center>";
if($sort=="sport DESC")
{
   $curimg="arrowup.png"; $cursort="sport ASC";
}
else if($sort=="sport ASC")
{
   $curimg="arrowdown.png"; $cursort="sport DESC";
}
else
{
   $curimg=""; $cursort="sport DESC";
}
echo "<td><a class=small href=\"oossanctionedevents.php?session=$session&sort=$cursort\">Sport</a>";
if(ereg("sport",$sort))
   echo "&nbsp;<a href=\"oossanctionedevents.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
echo "</td>";
if($sort=="eventname DESC")
{
   $curimg="arrowup.png"; $cursort="eventname ASC";
}
else if($sort=="eventname ASC")
{
   $curimg="arrowdown.png"; $cursort="eventname DESC";
}
else
{
   $curimg=""; $cursort="eventname DESC";
}
echo "<td><a class=small href=\"oossanctionedevents.php?session=$session&sort=$cursort\">Event Name</a><br>(Click to Edit)";
if(ereg("eventname",$sort))
   echo "&nbsp;<a href=\"oossanctionedevents.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
echo "</td>";
if($sort=="startdate DESC")
{
   $curimg="arrowup.png"; $cursort="startdate ASC";
}
else if($sort=="startdate ASC")
{
   $curimg="arrowdown.png"; $cursort="startdate DESC";
}
else
{
   $curimg=""; $cursort="startdate DESC";
}
echo "<td><a class=small href=\"oossanctionedevents.php?session=$session&sort=$cursort\">Event Date(s)</a>";
if(ereg("startdate",$sort))
   echo "&nbsp;<a href=\"oossanctionedevents.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
echo "</td>";
if($sort=="sanctiondate DESC")
{
   $curimg="arrowup.png"; $cursort="sanctiondate ASC";
}
else if($sort=="sanctiondate ASC")
{
   $curimg="arrowdown.png"; $cursort="sanctiondate DESC";
}
else
{
   $curimg=""; $cursort="sanctiondate DESC";
}
echo "<td><a class=small href=\"oossanctionedevents.php?session=$session&sort=$cursort\">Date Sanctioned</a>";
if(ereg("sanctiondate",$sort))
   echo "&nbsp;<a href=\"oossanctionedevents.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
echo "</td>";
echo "<td><b>Delete</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>".GetActivityName($row[sport])."</td>";
   echo "<td><a class=small href=\"oossanctionedevents.php?session=$session&id=$row[id]\">$row[eventname]</a><br>$row[city] $row[state] $row[country]</td>";
   $start=split("-",$row[startdate]);
   $end=split("-",$row[enddate]);
   if($row[startdate]==$row[enddate])
      echo "<td>$start[1]/$start[2]/$start[0]</td>";
   else
   {
      echo "<td>$start[1]/$start[2]";
      if($start[0]!=$end[0]) echo "/$start[0]";
      echo " - $end[1]/$end[2]/$end[0]</td>";
   }
   $date=split("-",$row[sanctiondate]);
   echO "<td>$date[1]/$date[2]/$date[0]</td>";
   echo "<td align=center><a href=\"oossanctionedevents.php?session=$session&delete=$row[id]\" onClick=\"return confirm('Are you sure you want to delete this sanctioned event?');\">X</a></td></tr>";
}

echo GetFooter($session);
?>
