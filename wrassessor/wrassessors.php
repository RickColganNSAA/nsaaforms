<?php
/************************************************
wrassessors.php
Report for Wrestling Coaches to view Name, City
and Email of all Registered WR Assessors
Created 11/3/11
by Ann Gaffigan
************************************************/
require "wrfunctions.php";
require "../functions.php";
require "../variables.php";

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<a class=small href=\"javascript:window.close()\">Close this Window</a><br><br>";

//GET ASSESSORS THAT ARE REGISTERED:
if(!$sort || $sort=="") $sort="last ASC,first ASC";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">
	<caption><b>Registered Wrestling Assessors, as of ".date("F j, Y")."</b>";
echo "</caption><tr align=center>";
if($sort=="last ASC,first ASC")
{
   $cursort="last DESC,first DESC";
   $curimg="../arrowdown.png";
}
else if($sort=="last DESC,first DESC")
{
   $cursort="last ASC,first ASC";
   $curimg="../arrowup.png";
}
else
{
   $cursort="last ASC,first ASC";
   $curimg="";
}
echo "<td><a class=small href=\"wrassessors.php?session=$session&sort=$cursort\">Name & Email";
if($curimg!='') echo " <img src=\"$curimg\" style=\"width:20px;\">";
echo "</a></td><td>E-mail Address</td>";
if($sort=="city ASC,state ASC")
{
   $cursort="city DESC,state DESC";
   $curimg="../arrowdown.png";
}
else if($sort=="city DESC,state DESC")
{
   $cursort="city ASC,state ASC";
   $curimg="../arrowup.png";
}
else
{
   $cursort="city ASC,state ASC";
   $curimg="";
}
echo "<td><a class=small href=\"wrassessors.php?session=$session&sort=$cursort\">City & State";
if($curimg!='') echo " <img src=\"$curimg\" style=\"width:20px;\">";
echo "</a></td>";
echo "</tr>";
$csv="\"Last Name\",\"First Name\",\"City\",\"State\",\"E-mail\"\r\n";
$sql="SELECT * FROM wrassessors ORDER BY $sort";	//GET ALL ASSESSORS FIRST
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if(IsPaid($row[userid]))	//IS REGISTERED - INCLUDE IN REPORT:
   {
      echo "<tr align=left>";
      echo "<td>$row[first] $row[last]</td><td><a class=small href=\"mailto:".trim($row[email])."\">$row[email]</a></td>";
      echo "<td>$row[city], $row[state]</td>";
      echo "</tr>";
      $csv.="\"$row[last]\",\"$row[first]\",\"$row[city]\",\"$row[state]\",\"$row[email]\"\r\n";
   }
}
echo "</table>";
$open=fopen(citgf_fopen("/home/nsaahome/reports/RegisteredWRAssessors_".date("m-d-y").".csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/RegisteredWRAssessors_".date("m-d-y");

echo "<br><a class=small href=\"javascript:window.close()\">Close this Window</a>";
echo $end_html;
?>
