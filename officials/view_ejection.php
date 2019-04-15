<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level==4) $level=1;

if(!$database || $database=="") $database=$db_name;
$dbscores=$database;
$database=preg_replace("/scores/","officials",$database);

$sql="SELECT * FROM $database.ejections WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo $init_html;
if($header!='no') echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
if($new==1)
{
   echo "<font style=\"color:red\"><b>You have just submitted the following Ejection Report to the NSAA.<br><br></b></font>";
}

if($level==1 && $database==$db_name2)
{
   echo "<a class=small href=\"ejection.php?session=$session&header=$header&off=$off&id=$id\">Edit this Report</a><br><br>";
}

echo "<table cellspacing=2 cellpadding=4 width=500><caption><b>Nebraska High School Activities Association Ejection Report:</b><hr></caption>";
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
$sql2="SELECT email FROM $database.officials WHERE id='$row[offid]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "$row2[email]</td></tr>";
if($row[off1]!="")
   echo "<tr align=left><td><b>Name of Official:</b></td><td>$row[off1]</td></tr>";
if($row[off2]!="")
   echo "<tr align=left><td><b>Name of Official:</b></td><td>$row[off2]</td></tr>";
if($row[off3]!="")
   echo "<tr align=left><td><b>Name of Official:</b></td><td>$row[off3]</td></tr>";
if($row[off4]!="")
   echo "<tr align=left><td><b>Name of Official:</b></td><td>$row[off4]</td></tr>";
echo "<tr align=left><td><b>Sport:</b></td><td>".GetSportName($row[sport])."</td></tr>";
echo "<tr align=left><td><b>School:</b></td><td>$row[school]</td></tr>";
if($row[player]!='0')	//player was ejected
{
   //get player name

   $sql2="SELECT first,middle,last FROM $dbscores.eligibility WHERE id='$row[player]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $player="$row2[first] $row2[middle] $row2[last]";

   echo "<tr align=left><td><b>Name of Player Ejected:</b></td><td>$player";
   if($row[number]!="") echo " (Uniform No.: $row[number])";
   echo "</td></tr>";
}
else
{
   echo "<tr align=left><td><b>Name of Coach Ejected:</b></td><td>$row[coach]</td></tr>";
}
echo "<tr align=left><td><b>Date of Contest:</b></td>";
$date=split("-",$row[gamedate]);
echo "<td>$date[1]/$date[2]/$date[0]</td></tr>";
echo "<tr align=left><td><b>Contest:</b></td><td>$row[school1] VS. $row[school2]</td></tr>";
echo "<tr align=left><td><b>Site of Contest:</b></td><td>$row[site]</td></tr>";
echo "<tr align=left><td><b>Level:</b></td><td>$row[level]</td></tr>";
echo "<tr align=left><td colspan=2><b>Reason for Ejection (Rule Reference):<br></b>";
echo "$row[reason]<br></td></tr>";
echo "<tr align=left><td colspan=2><b>Any player or coach ejected from a contest for unsportsmanlike conduct shall be ineligible for the next athletic contest at that level of competition and any other athletic contest at any level during the interim, in addition to other penalties that NSAA or school may assess.</b></td></tr>";
echo "</table>";
if($header!='no') echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
else echo "<br><br><a href=\"javascript:window.close()\" class=small>Close Window</a>";
echo $end_html;
?>
