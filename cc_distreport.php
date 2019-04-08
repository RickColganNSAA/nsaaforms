<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session);
echo "<br><a class=small href=\"cc_main.php?session=$session\">Cross-Country District Results & State Qualfiers MAIN MENU</a><br>";
echo "<br>";
echo "<table cellspacing=0 cellpadding=4 rules=all frames=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>Report of District Results Submitted</b></caption>";
echo "<tr align=center><td><b>District</b></td><td><b>Host School</b></td><td><b>Boys Results</b></td><td><b>Girls Results</b></td></tr>";
$sql="SELECT * FROM $db_name2.ccbdistricts WHERE type='District' ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td align=center>$row[class]-$row[district]</td><td>$row[hostschool]</td>";
   echo "<td align=center>".strtoupper($row[submitted_b])."</td>";
   $sql2="SELECT * FROM $db_name2.ccgdistricts WHERE type='District' AND class='$row[class]' AND district='$row[district]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td align=center>".strtoupper($row2[submitted_g])."</td>";
   echo "</tr>";
}
echo "</table>";
echo "<br><br><a class=small href=\"cc_main.php?session=$session\">Cross-Country District Results & State Qualfiers MAIN MENU</a><br>";
echo $end_html;

exit();
?>
