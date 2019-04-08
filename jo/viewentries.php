<?php
/*******************************************
viewentries.php
PUBLIC WEBSITE PAGE to show ALL ENTRIES
Created 4/16/15
Author: Ann Gaffigan
*******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($year=="" || !$year) $database=$db_name;
else $database=GetDatabase($year);
$sql="USE $database";
$result=mysql_query($sql);

$duedate=GetDueDate('jo');
if(!PastDue($duedate,24))
{
   echo $init_html;
   echo "<h3><i>Journalism entries are not available at this time.</i></h3>";
   echo $end_html;
   exit();
}

echo $init_html."<table class='nine' width='100%'><tr align=center><td>";

echo "<form method='post' action='viewentries.php'>";
echo "<input type=hidden name=\"year\" value=\"$year\">";
echo "<p><b>Select a Category: </b>";
echo "<select name=\"catid\" onChange=\"submit();\"><option value=''>Select Category</option>";
$sql="SELECT t1.id,t1.category,t2.judgeid,t2.datesub FROM jocategories AS t1,joassignments AS t2 WHERE t1.id=t2.catid ORDER BY t2.datesub DESC,t1.category";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($catid==$row[id]) echo " selected";
   echo ">$row[category]";
   echo "</option>";
}
echo "</select></p><form>";
if($catid>0)
   echo GetJOEntries($catid,$year,TRUE);
else
   echo "<div class=\"alert\"><p><i>Please select a Category above to view NSAA Journalism entries in that category/event.</i></p></div>";
   echo $end_html;

$sql="USE $db_name";
$result=mysql_query($sql);

exit();
?>
