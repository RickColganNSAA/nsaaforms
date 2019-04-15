<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$offid=GetJudgeID($session);
$level=GetLevelJ($session);
if($level==1)
   $offid=$givenoffid;

//check that it is at least 3 days after the due date
$sql="SELECT * FROM test_duedates WHERE test='sp'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[duedate]);
$duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
$duedate3=$duedate+3*24*60*60;
$now=time();

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}
if($now<=$duedate3 && $level!=1)
{
   echo $init_html;
   echo "<table width=100%><tr align=center><td><br>";
   echo "<b>You may not view your test results at this time.  Please check back later.  Thank You!";
   echo "<br><br><a class=small href=\"javascript:window.close();\">Close</a>";
   echo $end_html;
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<table width=90%>";
echo "<caption><b>Speech & Play Production Rules Examination - Part I</b><br>";
if($level==1) echo "Judge #$offid: ".GetJudgeName($offid)."<br>";
echo "</b>(Questions that were incorrectly answered are highlighted in yellow.)";
echo "<hr></caption>";

//get answers already entered by this official
$answer=array();
$sql="SELECT * FROM sptest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=100;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}

echo "<tr align=left><th>&nbsp;</th><th align=left class=smaller>Answer/Correct</th>";
echo "<th align=left class=smaller>Reference</th></tr>";
$sql0="SELECT id,category,place FROM sptest_categ ORDER BY place";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $categid=$row0[id];
   $category=$row0[category];
   if(($category=="Speech" && $test=='speech') || ($category=='Play Production' && $test=='play') || $test=='combo')
   {
   echo "<tr align=left><th align=left colspan=2>$category:</th></tr>";
$sql="SELECT question,place,answer,reference FROM sptest WHERE category='$categid' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   echo "<tr align=left valign=top><td width=50% align=left";
   if($answer[$place]!=$row[2]) echo " bgcolor=yellow";
   echo ">$place.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "</td><td align=center>";
   if($answer[$place]!=$row[2])
   {
      echo "<font style=\"color:red\"><b>".strtoupper($answer[$place])."</b></font>";
   }
   else
   {
      echo "<b>".strtoupper($answer[$place])."</b>";
   }
   echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".strtoupper($row[2])."</b>";
   echo "</td>";
   echo "<td>$row[reference]</td></tr>";
   $ix++;
   }
}
}//end for each category
echo "</table>";

echo $end_html;

?>
