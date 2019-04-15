<?php
//allow NSAA to retrieve school's votes

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$header=GetHeaderJ($session,"jvote");
$level=GetLevelJ($session);

//verify user
if(!ValidUser($session) || $level!='1')
{
   header("Location:jindex.php");
   exit();
}

//get school user chose 
$school=$school_ch;
$school2=ereg_replace("\'","\'",$school);

if($sport=='sp')
{
   $sportname="Speech";
   $other='pp';
   $othername="Play Production";
}
else
{
   $sport='pp';
   $sportname="Play Production";
   $other='sp';
   $othername="Speech";
}
if($save=="Save")
{
   if(strlen($month1)==1) $month1="0".$month1;
   if(strlen($day1)==1)  $day1="0".$day1;
   if(strlen($month2)==1) $month2="0".$month2;
   if(strlen($day2)==1) $day2="0".$day2;
   $date1="$year1-$month1-$day1";
   $date2="$year2-$month2-$day2";
   $sql="UPDATE vote_duedates SET startdate='$date1', enddate='$date2' WHERE sport='$sport'";
   $result=mysql_query($sql);
}

if($go=="Go")
{
   if($school_ch!="Choose School" && $ad_coach)
   {
      header("Location:vote_$sport.php?session=$session&nsaa=1&school_ch=$school_ch&ad_coach=$ad_coach");
      exit();
   }
}

echo $init_html;
echo $header;

echo "<br><table><tr align=left><td><ul>";
echo "<form method=post action=\"jvote.php\">";
echo "<input type=hidden name=session value=\"$session\">";
//echo "<input type=hidden name=sport value=\"$sport\">";
echo "<b><font style=\"font-size:10pt;\">Select Sport:&nbsp;&nbsp;</b></font><select name=sport onchange=\"submit();\"><option value='sp'";
if($sport=='sp') echo " selected";
echo ">Speech</option><option value='pp'";
if($sport=='pp') echo " selected";
echo ">Play Production</option></select><hr><br>";
echo "<li class=bigger>$sportname Judges' Ballot Forms:";
echo "<table>";
echo "<tr align=left><td><select name=school_ch><option>Choose School";
$votestbl=$sport."_votes";
$sql="SELECT DISTINCT school FROM $votestbl ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option>$row[0]";
}
echo "</select><br>";
echo "<input type=radio name=ad_coach value='ad'>&nbsp;AD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=ad_coach value='coach'>&nbsp;Coach</td></tr>";
echo "<tr align=center><td><input type=submit name=go value=\"Go\"></td></tr>";
echo "</table>";
echo "</form>";

//or let user choose from numerous reports
echo "<li class=bigger>$sportname Judges' Ballot Reports:";
echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=5>";
echo "<tr align=left><th>&nbsp;</th>";
echo "<td colspan=2><b>Report</a></td></tr>";
echo "<tr align=left><th align=left class=smaller>Schools Who Have Voted</th>";
echo "<td colspan=2><a class=small href=\"jvote_report.php?sport=$sport&type=schools&session=$session\">Schools Who Have Voted</a></td></tr>";
echo "<tr valign=top align=left><th align=left class=smaller>by Total Vote</th>";
echo "<td colspan=2><a class=small href=\"jvote_report.php?sport=$sport&ad_coach=ad&type=vote&session=$session\">AD Votes Only</a><br>";
echo "<a class=small href=\"jvote_report.php?sport=$sport&ad_coach=coach&type=vote&session=$session\">Coaches Votes Only</a><br>";
echo "<a class=small href=\"jvote_report.php?sport=$sport&ad_coach=both&type=vote&session=$session\">All Votes</a></td></tr>";
echo "<tr valign=top align=left><th align=left class=smaller>Alphabetical Order</th>";
echo "<td colspan=2><a class=small href=\"jvote_report.php?sport=$sport&ad_coach=ad&type=abc&session=$session\">AD Votes Only</a><br>";
echo "<a class=small href=\"jvote_report.php?sport=$sport&ad_coach=coach&type=abc&session=$session\">Coaches Votes Only</a><br>";
echo "<a class=small href=\"jvote_report.php?sport=$sport&ad_coach=both&type=abc&session=$session\">All Votes</a></td></tr>";
echo "</table>";

//allow user to put in due date for ballots
echo "<form method=post action=\"jvote.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<br><li class=bigger>Date Range for $sportname Judges' Ballots to be available to schools:";
echo "<table>";
echo "<tr align=left><td align=center>";
$sql="SELECT * FROM vote_duedates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[startdate]);
$curmo=$date[1];
$curday=$date[2];
$curyr=$date[0];
echo "from&nbsp;<select name=month1>";
for($i=0;$i<count($months);$i++)
{
   $mo=$i+1;
   echo "<option value=$mo";
   if($curmo==$mo) echo " selected";
   echo ">$months[$i]";
}
echo "</select>&nbsp;";
echo "<input class=tiny type=text name=day1 value=\"$curday\" size=2>&nbsp;";
if($curyr=="") $curyr=date("Y",time());
echo "<input class=tiny type=text name=year1 value=\"$curyr\" size=4>&nbsp;to&nbsp;";
$date=split("-",$row[enddate]);
$curmo=$date[1];
$curday=$date[2];
$curyr=$date[0];
echo "<select name=month2>";
for($i=0;$i<count($months);$i++)
{
   $mo=$i+1;
   echo "<option value=$mo";
   if($curmo==$mo) echo " selected";
   echo ">$months[$i]";
}
echo "</select>&nbsp;";
echo "<input type=text name=day2 value=\"$curday\" size=2 class=tiny>&nbsp;";
if($curyr=="") $curyr=date("Y");
echo "<input type=text name=year2 value=\"$curyr\" size=4 class=tiny>";
echo "</td></tr>";
echo "<tr align=center><td><input type=submit name=save value=\"Save\">";
echo "</table></form>";

echo "</ul></td></tr></table>";

echo $end_html;
?>
