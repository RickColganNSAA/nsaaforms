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
$level=GetLevel($session);
if(GetLevel($session)==1)
{
   $offid=$givenoffid;
   if(!$givenoffid)
   {
      echO $init_html;
      echo "<br><br>ERROR: no official specified.";
      echo $end_html;
      exit();
   }
}     
else
   $offid=GetOffID($session);
/*
if($submit)
{
   for($i=0;$i<count($place);$i++)
   {
      $field="ques".$place[$i];
      $sql="SELECT * FROM sotest_results WHERE offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
	 $sql2="INSERT INTO sotest_results (offid,$field) VALUES ('$offid','$answer[$i]')";
	 $result2=mysql_query($sql2);
      }
      else	//UPDATE
      {
	 $sql2="UPDATE sotest_results SET $field='$answer[$i]' WHERE offid='$offid'";
	 $result2=mysql_query($sql2);
      }
   }
   $categid++;
}

if(!$categid || $categid=="Jump To...") $categid='1';
else if($categid=="Finish Test")
{
   //confirm with user that test is ready to be submitted
}
*/

//if no categid given, get first category:
if(!$categid || $categid==0)
{
   $sql="SELECT * FROM sotest_categ WHERE place='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $categid=$row[id];
}

echo $init_html;
echo "<table width=100%><tr><td><br>";

echo "<form name=\"test_form\" method=post action=\"sotest_update.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=categid value=\"$categid\">";
echo "<input type=hidden name=forcecategid>";
echo "<input type=hidden name=home>";
echo "<table width=90%>";
echo "<caption><b>Soccer Rules Examination - Part I</b><br>Copyrighted and Published by the National Federation of State High School Associations<br>";
if(GetLevel($session)==1)
{
   echo "for <font style=\"color:red\"><b>".GetOffName($offid)."</b></font><br>";
}
$date=split("-",GetTestDueDate("so"));
$duedate=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
echo "Due $duedate</caption>";
echo "<tr align=left><td align=left colspan=2><div class=alert><i><b>Instructions:</b></i><br><br>";
echo "Every part of each question is to be answered. Indicate whether you believe a part is true or false by checking the appropriate circle.</div></td></tr>";
echo "<tr align=center><td align=center colspan=2><hr><table cellspacing=0 cellpadding=5 class=nine>";
$sql="SELECT category,place FROM sotest_categ WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$category=$row[0];
echo "<tr align=left><th align=left colspan=2>$category</th></tr>";
//get answers already entered by this official
$sql="SELECT * FROM sotest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=100;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}
$sql="SELECT question,place,id FROM sotest WHERE category='$categid' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   echo "<tr align=left valign=top";
   if($ix%2==0) echo " bgcolor='#e0e0e0'";
   echo "><td width=650 align=left>$place.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "</td><td align=left>";
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\">";
   //GET MULTIPLE CHOICES
   $sql2="SELECT * FROM sotest_mchoices WHERE questionid='$row[id]' ORDER BY orderby";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<input type=radio name=\"answer[$ix]\" value=\"$row2[choicevalue]\"";
      if($answer[$place]==$row2[choicevalue]) echo " checked";
      echo "><b>$row2[choicelabel]</b><br>";
   }
   echo "</td></tr>";
   $ix++;
}
echo "</table>";

echo $end_html;

?>
