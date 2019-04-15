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

//Figure out what the last year archived was.  Will show those rosters below current ones:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb="$db_name2".$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb="$db_name2".$year00.$year0;
   $curyear="$year0-$year";
   $lastyear="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedb'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archive=0;
   else $archive=1;
}
else
{
   $archive=1;
   $curyear="$year-$year1";
   $lastyear="$year0-$year";
}

echo $init_html;
echo GetHeaderJ($session,"managejudge");

echo "<br><table><caption><b>Manage Judges:</b></caption><tr align=left><td><ul>";
echo "<li><a href=\"#\" onClick=\"window.open('add_judge.php?session=$session','addjudge','menubar=yes,resizable=yes,scrollbars=yes,titlebar=yes,width=600,height=600');\">Add New Judge</a></li>";
echo "<li><a href=\"judge_query.php?session=$session\">Judges Advanced Search & Queries</a></li>";
echo "<li><a href=\"jphotosadmin.php?session=$session\">Judges Profile Picture ADMIN</a>";
   $sql="SELECT * FROM judges WHERE photofile!='' AND photoapproved!='x'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct==1) echo " ($ct picture awaiting approval.)";
   else echo " ($ct pictures awaiting approval.)";
echo "</li>";
echo "<li><b>QUICK EXPORTS:</b><ul>";
	echo "<li>Judges Registered THIS YEAR: Name, Email, Address: <a href=\"export.php?sport=pp&session=$session&type=allthisyear\" target=\"_blank\">Play</a>&nbsp;&nbsp;<a href=\"export.php?sport=sp&session=$session&type=allthisyear\" target=\"_blank\">Speech</a></li>";
   	echo "<li>ALL Judges in the Database: Name, Email, Address: <a href=\"export.php?sport=pp&session=$session&type=allever\" target=\"_blank\">Play</a>&nbsp;&nbsp;<a href=\"export.php?sport=sp&session=$session&type=allever\" target=\"_blank\">Speech</a></li>";
echo "</ul></li>";
echo "<li><a href=\"jmailnumsummary.php?session=$session\">Summary of Judges' Mailings</a></li>";
echo "<li><a target=new href=\"jsummer.php?archivedb=$archivedb&session=$session\">Export $lastyear Judges for Summer Mailing</a></li>";
echo "<li><a href=\"jconvictions.php?session=$session\">Judges Convicted of a Misdemeanor or Felony</a></li>";
echo "<li><form method=post action=\"jroster.php\" target=new>";
echo "<input type=hidden name=session value=\"$session\">";
if($archive==1) echo "<b>$curyear Rosters:&nbsp;</b>";
echo "<select name=list onchange=\"submit();\">";
$sql="SELECT * FROM rosters WHERE (sport='pp' OR sport='sp') ORDER BY sport";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   //if active='x', will show on AD and officials pages
   echo "<option value=\"$row[sport]\">".GetSportName($row[sport]);
   if($row[active]=='x') echo " (active)";
   else echo " (inactive)";
   echo "</option>";
}
echo "</select><input type=submit name=go value=\"Go\"></form><br>";
if($archive==1)
{
   //if showold='x', archived rosters will show on AD and officials pages
   echo "<form method=post action=\"jroster.php\" target=new>";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<b>$lastyear Rosters:&nbsp;</b>";
   echo "<input type=hidden name=archive value=\"$archivedb\">";
   echo "<select name=list onchange=\"submit();\">";
   $sql="SELECT * FROM rosters WHERE (sport='pp' OR sport='sp') ORDER BY sport";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[sport]\">".GetSportName($row[sport]);
      if($row[showold]=='x') echo " (active)";
      else echo " (inactive)";
      echo "</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form>";
}
echO "</li>";
//NUMBERS FROM THIS YEAR AND PREVIOUS YEARS
echo "<li>";
echo "<form method=post action=\"managejudge.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<b>REGISTRATION NUMBERS:</b> ";
echo "<select name=\"database\" onchange=\"submit();\">";
$sql="SHOW DATABASES LIKE 'nsaaofficials%'";
$result=mysql_query($sql);
if(!$database) $database=$db_name2;
while($row=mysql_fetch_array($result))
{
   $temp=split("nsaaofficials",$row[0]);
   if($row[0]==$db_name2) $year="This Year";
   else $year=substr($temp[1],0,4)."-".substr($temp[1],4,4);
   echo "<option value=\"$row[0]\"";
   if($database==$row[0]) echo " selected";
   echo ">$year</option>";
}
echo "</select>";
if($database)
{
   $sql="SELECT * FROM $database.judges WHERE payment!='' AND ((spmeeting='x' AND sptest>=40 AND speech!='x') OR (ppmeeting='x' OR pptest>=8 AND play!='x'))";
   $result=mysql_query($sql);
if(mysql_error()) echo $sql;
   echo "<ul><li>TOTAL NUMBER OF JUDGES THAT COMPLETED REGISTRATION: <b>".mysql_num_rows($result)."</b></li><br>";
   $sql="SELECT * FROM $database.judges WHERE payment!='' AND spmeeting='x' AND sptest>=40 AND speech!=''";
   $result=mysql_query($sql);
if(mysql_error()) echo $sql;
   echo "<li>TOTAL NUMBER OF JUDGES THAT COMPLETED REGISTRATION FOR <b><u>SPEECH</b></u>: <b>".mysql_num_rows($result)."</b></li><br>";
   $sql="SELECT * FROM $database.judges WHERE payment!='' AND ppmeeting='x' AND pptest>=8 AND play!=''";
   $result=mysql_query($sql);   
if(mysql_error()) echo $sql;
   echo "<li>TOTAL NUMBER OF JUDGES THAT COMPLETED REGISTRATION FOR <b><u>PLAY</b></u>: <b>".mysql_num_rows($result)."</b></li><br>";
   echo "</li>";
}
echo "</form></li>";
echo "</ul></td></tr></table>";

echo $end_html;
?>
