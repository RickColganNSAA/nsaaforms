<?php
/*************************************
View Yellow Card Report
Created 2/14/11
Author Ann Gaffigan
*************************************/

require 'functions.php';
require 'variables.php';
require '../../calculate/functions.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level==4) $level=1;

$sql="SELECT * FROM yellowcards WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo $init_html;
if($header!='no') echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
if($new==1)
{
   echo "<font style=\"color:red\"><b>You have just submitted the following Yellow Card Report to the NSAA.<br><br></b></font>";
}

if($level==1)
{
   echo "<a class=small href=\"yellowcard.php?session=$session&header=$header&off=$off&id=$id\">Edit this Report</a><br><br>";
}

echo "<table cellspacing=2 cellpadding=4 width=500><caption><b>Nebraska High School Activities Association YELLOW CARD Report:</b><hr></caption>";
if($level==1)
{
   echo "<tr align=left><td colspan=2><table>";
   echo "<tr align=left><td><b><u>NSAA ONLY:</u></b></td></tr>";
   echo "<tr align=left><td><b>Verified:</b>&nbsp;&nbsp;";
   if($row[verify]=='x') echo "YES";
   else echo "NO";
   echo "</td></tr><tr align=left><td><b>Notes:</b>&nbsp;&nbsp;";
   if($row[notes]!='') echo $row[notes];
   else echo "[none]";
   echo "</td></tr>";
   echo "</table></td></tr>";
}
$datesub=date("F j, Y",$row[datesub]);
echo "<tr align=left><td><b>Date Submitted:</b></td><td>$datesub</td></tr>";
echo "<tr align=left><td><b>Name of Official Submitting Report:</b></td><td>".GetOffName($row[offid])."</td></
tr>";
echo "<tr align=left><td><b>E-mail:</b></td><td>";
$sql2="SELECT email FROM officials WHERE id='$row[offid]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<a href='mailto:$row2[email]' class=small>$row2[email]</a></td></tr>";
echo "<tr align=left><td><b>Sport:</b></td><td>".GetSportName($row[sport])."</td></tr>";
echo "<tr align=left><td><b>School:</b></td><td>".GetSchoolName($row[sid],$row[sport],date("Y"))."</td></tr>";

   $sql2="SELECT first,middle,last FROM $db_name.eligibility WHERE id='$row[studentid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $player="$row2[first] $row2[middle] $row2[last]";

   echo "<tr align=left><td><b>Name of Player:</b></td><td>$player";
   echo "</td></tr>";
echo "<tr align=left><td><b>Coach:</b></td><td>$row[coach]</td></tr>";

echo "<tr align=left><td><b>Date of Contest:</b></td>";
$date=split("-",$row[gamedate]);
echo "<td>$date[1]/$date[2]/$date[0]</td></tr>";
echo "<tr align=left><td><b>Opponent:</b></td><td>".GetSchoolName($row[oppid],$row[sport],date("Y"))."</td></tr>";
echo "<tr align=left><td><b>Level:</b></td><td>$row[level]</td></tr>";
echo "<tr align=left><td colspan=2><b>Reason for Yellow Card:<br></b>";
echo "$row[reason]<br></td></tr>";
echo "<tr align=left><th colspan=2><br>NOTE: If the player received two yellow cards, please fill out the ejection report form in lieu of a second yellow card report.</th></tr>";
echo "</table>";
if($header!='no') echo "<br><br><a href=\"welcome.php?session=$session&open11=11#11\" class=small>Home --> Yellow Card Reports</a>";
else echo "<br><br><a href=\"javascript:window.close()\" class=small>Close Window</a>";
echo $end_html;
?>
