<?php
//rulesmeetingadmin.php: admin main menu for online rules meetings

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"rulesmeetingadmin");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo $header;
echo "<table class=nine><tr align=left><td><br><br>";

echo "<h1>Online Rules Meetings MAIN MENU:</h1><br>";
echo "<ul><li><a href=\"rulesmeetingattendance.php?session=$session&database=nsaaofficials\">Officials & Judges Attendance</a>";
echo "</li><br>";
echo "<li><a href=\"rulesmeetingattendance.php?session=$session&database=nsaascores\">Coaches & AD's Attendance</a>";
echo "</li><br>";
echo "<li><a href=\"rulesmeetingpayments.php?session=$session&database=nsaaofficials\">Officials & Judges Payments</a>";
echo "</li><br>";
echo "<li><a href=\"rulesmeetingpayments.php?session=$session&database=nsaascores\">Coaches Payments</a>";
echo "</li><br><br>";
echo "<li><a href=\"rulesmeetingdata.php?session=$session&database=nsaaofficials\">Officials & Judges Data Lookup</a>";
echo "</li><br>";
echo "<li><a href=\"rulesmeetingdata.php?session=$session&database=nsaascores\">Coaches Data Lookup</a>";
echo "</li>";
echo "</ul>";

echo "</td></tr></table>";

echo $end_html;
?>
