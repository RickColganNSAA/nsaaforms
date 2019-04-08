<?php
/*******************************************
player_te_g.php
Display Player Results Summary, Girls Tennis
Created 7/22/08
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';
require 'tefunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sport='te_g';
$sportname="Girls Tennis";
$gender='F';
$meettable=$sport."meets";
$resultstable=$sport."meetresults";
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
   $sid=GetSID($session,$sport);
}
else if($school_ch)
{
   $sid=$school_ch;
   $school=GetMainSchoolName($sid,$sport);
}
else
{
   echo "ERROR: No School Selected";
   exit();
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/TEMeetResults.js"></script>
</head>
<body onload="TEMeetResults.initialize('showresults','<?php echo $sport; ?>','0','<?php echo $sid; ?>','<?php echo $session; ?>','<?php echo $school2; ?>','0');">
<?php
echo $header;

echo "<br><a class=small href=\"main_".$sport.".php?school_ch=$school_ch&session=$session\">".$sportname." Main Menu</a><br><br>";
echo "<form method=post name=resultsform action=\"player_te_g.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"school_ch\" value=\"$school_ch\">";
if($gender=="M") $hisher="his";
else $hisher="her";
echo "<table width='75%'><caption><b>".$sportname." Player Summaries:</b><br><i>Please select a school and then a player to view a summary of ".$hisher." results so far this season.</i><br>";
if(!$sidch) $sidch=$sid;
if(!$database) $database=$db_name;
echo "<b>Yesr:</b>&nbsp;<select name=\"database\" id=\"database\" onchange=\"submit();\">";
$sql="SHOW DATABASES LIKE 'nsaascores%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[0]=="nsaascores") $showyear="CURRENT SCHOOL YEAR";
   else
   {
      $years=substr($row[0],10,8);
      $showyear=substr($years,0,4)."-".substr($years,4,4)." School Year";
   }
   echo "<option value=\"$row[0]\"";
   if($database==$row[0]) echo " selected";
   echo ">$showyear</option>";
}
echo "</select>&nbsp;";
if($database==$db_name)
{
echo "<b>School:</b>&nbsp;<select name=\"sidch\" id=\"sidch\" onchange=\"submit();\"><option value='0'>Select School</option>";
$sql="SELECT * FROM $database.te_gschool ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[sid]\"";
   if($sidch==$row[sid]) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select>&nbsp;";
}//end if current school year (only then can they see other schools' players)
else
   $sidch=$sid;
if($sidch)
{
   echo "<b>Select a Player:</b>&nbsp;<select name=\"player\" id=\"player\" onchange=\"submit();\"><option value=\"0\">~</option>";
   $sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.semesters FROM $database.eligibility AS t1, $database.headers AS t2, $database.".$sport."school AS t3 WHERE t1.school=t2.school AND (t2.id=t3.mainsch OR t2.id=t3.othersch1 OR t2.id=t3.othersch2 OR t2.id=t3.othersch3) AND t3.sid='$sidch' AND t1.gender='$gender' AND te='x' ORDER BY t1.last,t1.first";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($player==$row[id]) echo " selected";
      echo ">$row[last], $row[first] (".GetYear($row[semesters]).")</option>";
   }
   echo "</select>&nbsp;";
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
echo "<div id=\"loading\" style=\"display:none;\"></div>";
echo $end_html;
?>
