<?php
/********************************************
stateinfo.php

NSAA tool to manage invited states' contact
information

Created 04/22/10
Author: Ann Gaffigan
*********************************************/

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
   for($i=0;$i<count($id);$i++)
   {
      $school[$i]=addslashes($school[$i]);
      $sql="UPDATE logins SET school='$school[$i]',email='$email[$i]',passcode='$passcode[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action='stateinfo.php'>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><a href=\"sanctionsadmin.php?session=$session\">Return to Sanctions Main Menu</a><br><br>";
echo "<table class=nine cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>Manage State Contact Information for<br>Interstate/International Sanction Applications</b><br>";
if($save)
   echo "<div class=alert>Your changes have been saved.</div>";
echo "<br></caption>";
echo "<tr align=center>";
if(!$sort || $sort=='') $sort="sport ASC";
if($sort=="sport ASC")
{
   $curimg="arrowdown.png";
   $nextsort="sport DESC";
}
else if($sort=="sport DESC")
{
   $curimg="arrowup.png";
   $nextsort="sport ASC";
}
else
{
   $curimg="";
   $nextsort="sport ASC";
}
echo "<td><a href=\"stateinfo.php?session=$session&sort=$nextsort\">State";
if($curimg!='') echo "&nbsp;<img src=\"../$curimg\" border=0 width='15px'>";
echo "</a></td>";
if($sort=="school ASC")
{
   $curimg="arrowdown.png";
   $nextsort="school DESC";
}
else if($sort=="school DESC")
{
   $curimg="arrowup.png";
   $nextsort="school ASC";
}
else
{
   $curimg="";
   $nextsort="school ASC";
}
echo "<td><a href=\"stateinfo.php?session=$session&sort=$nextsort\">Main Contact Name";
if($curimg!='') echo "&nbsp;<img src=\"../$curimg\" border=0 width='15px'>";
echo "</a></td>";
echo "<td><b>Email</b></td><td><b>Passcode</b><br>(Will be generated once<br>state is listed on<br>an app for sanction)</td>";
echo "</tr>";
$sql="SELECT * FROM logins WHERE level='7' ORDER BY $sort";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left>";
   echo "<td>$row[sport]<input type=hidden name=\"id[$i]\" value=\"$row[id]\"></td>";
   echo "<td><input type=text name=\"school[$i]\" size=30 value=\"$row[school]\"></td>";
   echo "<td><input type=text name=\"email[$i]\" size=30 value=\"$row[email]\"></td>";
   echo "<td><input type=text name=\"passcode[$i]\" size=15 value=\"$row[passcode]\"></td>";
   echo "</tr>";
   $i++;
   if($i%10==0)
      echo "<tr align=center><td colspan=4><input type=submit name=\"save\" value=\"Save Changes\"></td></tr>";
}
echo "</table>";
echo "<input type=submit name=\"save\" value=\"Save Changes\">";

echo "</form>";
echo "<br><a href=\"sanctionsadmin.php?session=$session\">Return to Sanctions Main Menu</a><br><br>";

echo $end_html;
?>
