<?php
/************************************
wrclinicnames.php
Report of Officials who have
attended this year's WR clinic
Copied from bbclinicnames.php 10/4/12
*************************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<table><tr align=left><td>";
echo "<b>".GetSchoolYear(date("Y"),date("n"))." Wrestling Clinic Attendees:<br></b>";
echo "(as of ".date("m/d/y").")<br><br>";

$sql="SELECT t1.* FROM officials AS t1, wroff_hist AS t2 WHERE t1.id=t2.offid AND t2.regyr='".GetSchoolYear(date("Y"),date("n"))."' AND t2.clinic='x' ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "$row[first]";
   if(trim($row[middle])!='') echo " $row[middle]";
   echo " $row[last]<br>";
}
if(mysql_num_rows($result)==0)
   echo "<p><i>No officials have been marked as attending the clinic.</i></p>";
echo "</td></tr></table>";
echo $end_html;
?>
