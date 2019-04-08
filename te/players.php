<?php
/*******************************************
players.php
Display Player Results Summary, Boys Tennis
Adapted from player_te_b.php on 8/14/13 for public use
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';
require 'tefunctions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
if(!$sport) $sport='te_b';
if($sport=='teb') $sport="te_b";
else if($sport=='teg') $sport="te_g";
$sportname=GetActivityName($sport);
if($sport=='te_b') $gender='M';
else $gender='F';
$meettable=$sport."meets";
$resultstable=$sport."meetresults";

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/TEMeetResults.js"></script>
</head>
<body onload="TEMeetResults.initialize('showresults','<?php echo $sport; ?>','0','<?php echo $sid; ?>','<?php echo $session; ?>','<?php echo $school2; ?>','0');">
<?php
echo "<table style=\"width:100%;\"><tr align=center><td>";
if(ValidUser($session))
   echo "<br><a class=small href=\"main_".$sport.".php?school_ch=$school_ch&session=$session\">".$sportname." Main Menu</a><br><br>";
else
   echo "<br>";
echo "<form method=post name=resultsform action=\"players.php\">";
echo "<input type=hidden name=\"sport\" value=\"$sport\">";
if($gender=="M") $hisher="his";
else $hisher="her";
echo "<table width='75%'><caption><b>".$sportname." Player Summaries:</b>";

$sql="SELECT * FROM $resultstable";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "<p><i>No results have been entered for this season yet.</i></p>";
   echo "</caption></table>";
   echo "<br><br><a href=\"javascript:window.close();\">Close Window</a>";
   echo $end_html;
   exit();
}
echo "<p><i>Please select a school and then a player to view a summary of ".$hisher." results so far this season.</i></p>";
if(!$sidch) $sidch=$sid;
if(!$database) $database=$db_name;
echo "<b>Select a School:</b>&nbsp;<select name=\"sidch\" id=\"sidch\" onchange=\"submit();\"><option value='0'>Select School</option>";
$sql="SELECT * FROM $database.".$sport."school ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[sid]\"";
   if($sidch==$row[sid]) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select>&nbsp;";
if($sidch)
{
   $sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.semesters FROM $database.eligibility AS t1, $database.headers AS t2, $database.".$sport."school AS t3 WHERE t1.school=t2.school AND (t2.id=t3.mainsch OR t2.id=t3.othersch1 OR t2.id=t3.othersch2 OR t2.id=t3.othersch3) AND t3.sid='$sidch' AND t1.gender='$gender' AND te='x' ORDER BY t1.last,t1.first";
   $result=mysql_query($sql);
   echo "<br><b>Select a Player:</b>&nbsp;";
   if(mysql_num_rows($result)==0)
   {
      echo "<i>No results have been entered for ".GetSchoolName($sidch,$sport)." yet</i>";
   }
   else
   {
   echo "<select name=\"player\" id=\"player\" onchange=\"submit();\"><option value=\"0\">~</option>";
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($player==$row[id]) echo " selected";
      echo ">$row[last], $row[first] (".GetYear($row[semesters]).")</option>";
   }
   echo "</select>&nbsp;";
   }
}
if($player)
{
   echo "<br>You may select a partner to see a DOUBLES report: <select name=\"player2\" id=\"player2\" onchange=\"submit();\"><option value=\"0\">~</option>";
   $sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.semesters FROM $database.eligibility AS t1, $database.headers AS t2, $database.".$sport."school AS t3 WHERE t1.school=t2.school AND (t2.id=t3.mainsch OR t2.id=t3.othersch1 OR t2.id=t3.othersch2 OR t2.id=t3.othersch3) AND t3.sid='$sidch' AND t1.gender='$gender' AND t1.id!='$player' AND te='x' ORDER BY t1.last,t1.first";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($player2==$row[id]) echo " selected";
      echo ">$row[last], $row[first] (".GetYear($row[semesters]).")</option>";
   }
   echo "</select>&nbsp;";
}
echo "</caption>";
echo "<tr align=center><td><br>";
if($player)
{
$sql="SELECT * FROM $database.eligibility WHERE id='$player'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school2=addslashes($row[school]);
$sql="SELECT t1.* FROM $database.".$sport."school AS t1,$database.headers AS t2 WHERE (t1.mainsch=t2.id OR t1.othersch1=t2.id OR t1.othersch2=t2.id OR t1.othersch3=t2.id) AND t2.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($player2) echo GetAllResults($sport,$row['class'],'doubles1',$row[sid],$player,$player2,'0',$database);
else echo GetAllResults($sport,$row['class'],"singles1",$row[sid],$player,0,0,$database);
}
echo "</td></tr></table>";
echo "</form>";
   echo "<br><br><a href=\"javascript:window.close();\">Close Window</a>";
echo "<div id=\"loading\" style=\"display:none;\"></div>";
echo $end_html;
?>
