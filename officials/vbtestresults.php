<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<table><tr><td>";
echo "<center><br>";
echo "<form name=\"test_form\" method=post action=\"vbtest_update.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=categid value=\"$categid\">";
echo "<input type=hidden name=forcecategid>";
echo "<table width=90%>";
echo "<caption><b>Volleyball Rules Examination - Part I</b></caption>";

//get answers already entered by this official
$answer=array();
$sql="SELECT * FROM vbtest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=100;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}

echo "<tr align=left><th>&nbsp;</th><th align=left class=smaller>Answer/Correct</th></tr>";
$sql0="SELECT category,place FROM vbtest_categ ORDER BY place";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $categid=$row0[place];
   $category=$row0[category];
   echo "<tr align=left><th align=left colspan=2>$category:</th></tr>";
$sql="SELECT question,place,answer FROM vbtest WHERE category='$categid' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   echo "<tr align=left><td width=80% align=left>$place.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "</td><td align=left>";
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\">";
   if($answer[$place]!=$row[2])
   {
      echo "<font style=\"color:red\"><b>".strtoupper($answer[$place])."</b></font>";
   }
   else
   {
      echo "<b>".strtoupper($answer[$place])."</b>";
   }
   echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".strtoupper($row[2])."</b>";
   echo "</td></tr>";
   $ix++;
}
}//end for each category
echo "</table>";

echo $end_html;

?>
