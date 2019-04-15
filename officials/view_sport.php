<?php

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}   

if($nameid) $id=$nameid;
$offid=$id;

//connect to database:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
      $sportname=$act_long[$i];
}
$offtable=$sport."off";
$histtable=$offtable."_hist";

//get official's name
$sql="SELECT first,last FROM officials WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$offname="$row[0] $row[1]";

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<a href=\"javascript:window.close();\" class=small>Close this Window</a><br><br>";

echo "<h2>$offname: $sportname Subform</h2>";

//Get sport registrationhistory from __off_hist
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;'><tr align=center><th>Year</th><th>Date<br>Reg</th><th>Contests</th><th>Rules<br>Mtg</th>";
echo "<th>Part 1<br>Test</th><th>Part 2<br>Test</th>";
if(HasClinic($sport)) echo "<th>Clinic</th>";
else if($sport=='tr') echo "<th>Starter</th>";
echo "<th>Class<br>ifica<br>tion</th><th>NHSOA</th></tr>";
$sql="SELECT * FROM $histtable WHERE offid='$offid' ORDER BY appdate";
$result=mysql_query($sql);
$ix=0;
$subid=array();
while($row=mysql_fetch_array($result))
{
   echo "<tr align=center>";
   echo "<td>$row[regyr]</td>";
   echo "<td>".date("m/d",strtotime($row[appdate]))."</td>";
   echo "<td>$row[contest]</td>";
   echo "<td>".strtoupper($row[rm])."&nbsp;</td>";
   echo "<td>$row[obtest]&nbsp;</td>";
   echo "<td>$row[suptest]&nbsp;</td>";
   if(HasClinic($sport))
      echo "<td>".strtoupper($row[clinic])."&nbsp;</td>";
   else if($sport=='tr')
   {
      if($row[position]=='starter') echo "<td>X</td>";
      else echo "<td>&nbsp;</td>";
   }
   echo "<td>$row[class]&nbsp;</td>";
   echo "<td>".strtoupper($row[nhsoa])."&nbsp;</td>";
   echo "</tr>";
}
echo "</table><br><br>";

//Get info from __off
$sql="SELECT * FROM $offtable WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<table>";
echo "<tr align=left>";
echo "<th align=left class=smaller>Years of Service:</th>";
echo "<td align=left>$row[years]</td>";
echo "<th align=left class=smaller>Part 2 Test Date:</th>";
echo "<td align=left>$row[suptestdate]</td></tr>";
if($sport=='bb')
{
   echo "<tr align=left>";
   echo "<td><b>Years worked State (Girls):</b></td>";
   echo "<td>$row[gstateyears]</td>";
   echo "<td><b>Total years worked State (Girls):</b></td>";
   echO "<td>$row[gnumstateyears]</td>";
   echo "</tr>";
   echo "<tr align=left>";
   echo "<td><b>Years worked State (Boys):</b></td>";
   echo "<td>$row[bstateyears]</td>";
   echo "<td><b>Total years worked State (Boys):</b></td>";
   echO "<td>$row[bnumstateyears]</td>";
   echo "</tr>";
   echo "<tr align=left><td colspan=4>(These values are updated at the end of the school year)</td></tr>";
}
else
{
   echo "<tr align=left>";
   echo "<td><b>Years worked State:</b></td>";
   echo "<td>$row[stateyears]</td>";
   echo "<td><b>Total years worked State:</b></td>";
   echO "<td>$row[numstateyears]</td></tr>";
   echo "<tr align=left><td colspan=4>(These values are updated at the end of the school year)</td></tr>";
}
echo "</table><br><br>";

echo "<a href=\"javascript:window.close();\" class=small>Close this Window</a>";

echo $end_html;
?>
