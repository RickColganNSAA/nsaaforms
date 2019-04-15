<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

$offid=GetJudgeID($session);
$level=GetLevelJ($session);
if($level==1)
   $offid=$givenoffid;

echo $init_html;
echo "<center><table width=100%><tr align=center><td>";
echo "<br>";
echo "<form name=\"test_form\" method=post action=\"sptest_update.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=test value=\"$test\">";
echo "<input type=hidden name=categid value=\"$categid\">";
echo "<input type=hidden name=forcecategid>";
echo "<input type=hidden name=givenoffid value=$givenoffid>";
echo "<input type=hidden name=home>";
echo "<table width=90%>";
echo "<caption><b>";
if($test=="speech") 
{
   echo "Speech "; 
   $sql="SELECT * FROM sptest_categ WHERE category LIKE '%Speech%'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $categid=$row[id];
}
else if($test=="play") 
{
   echo "Play Production ";
   $sql="SELECT * FROM sptest_categ WHERE category LIKE '%Play%'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $categid=$row[id];
}
else echo "Speech & Play Production ";
echo "Rules Examination - Part I</b><br>";
$date=split("-",GetTestDueDate("sp"));
$duedate=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
echo "Due $duedate";
echo "<hr>";
if(test=="speech")
   echo "<font style=\"font-size:8pt;\"><b>NOTE: Make sure to SCROLL DOWN to see and answer all 50 questions on the Speech portion of this test.</font>";
echo "</caption>";
$sql="SELECT category FROM sptest_categ WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$category=$row[0];
echo "<tr align=left><th align=left colspan=2>$category:</th></tr>";
//get answers already entered by this official
$sql="SELECT * FROM sptest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=60;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}
$sql="SELECT question,place FROM sptest WHERE category='$categid' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   echo "<tr align=left><td width=650 align=left>$place.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "</td><td align=left>";
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\">";
   echo "<input type=radio name=\"answer[$ix]\" value='t'";
   if($answer[$place]=='t') echo " checked";
   echo "><b>T</b>&nbsp;&nbsp;";
   echo "<input type=radio name=\"answer[$ix]\" value='f'";
   if($answer[$place]=='f') echo " checked";
   echo "><b>F</b></td></tr>";
   $ix++;
}
echo "</table>";

echo $end_html;

?>
