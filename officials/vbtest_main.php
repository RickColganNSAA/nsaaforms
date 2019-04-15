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
      $sql="SELECT * FROM vbtest_results WHERE offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
	 $sql2="INSERT INTO vbtest_results (offid,$field) VALUES ('$offid','$answer[$i]')";
	 $result2=mysql_query($sql2);
      }
      else	//UPDATE
      {
	 $sql2="UPDATE vbtest_results SET $field='$answer[$i]' WHERE offid='$offid'";
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
   $sql="SELECT * FROM vbtest_categ WHERE place='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $categid=$row[id];
}

echo $init_html;
echo "<table width=100%><tr><td><br>";

echo "<form name=\"test_form\" method=post action=\"vbtest_update.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=categid value=\"$categid\">";
echo "<input type=hidden name=forcecategid>";
echo "<input type=hidden name=home>";
echo "<table width=90%>";
echo "<caption><b>Volleyball Rules Examination - Part I</b><br>";
if(GetLevel($session)==1)
{
   echo "for <font style=\"color:red\"><b>".GetOffName($offid)."</b></font><br>";
}
$date=split("-",GetTestDueDate("vb"));
$duedate=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
echo "Due $duedate</caption>";
echo "<tr align=left><td align=left colspan=2><i><b>Instructions:</b></i></td></tr>";
echo "<tr align=left><td align=left colspan=2><b>NOTE:</b> Team S = serving team; Team R = receiving team</td></tr>";
echo "<tr align=center><td align=center colspan=2><hr><table>";
$sql="SELECT category,place FROM vbtest_categ WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$category=$row[0];
/*
if($row[1]==20)	//special case
{
   echo "<tr align=center><th colspan=2>THE FOLLOWING QUESTIONS ARE VOLLEYBALL SITUATIONS.<br>USE THE FOLLOWING KEY IN ANSWERING QUESTIONS 89-100:<br><table>";
   echo "<tr align=center><td><b>T</b></td><td><b>F</b></td><td>&nbsp;</td></tr>";
   echo "<tr align=center><td><input type=radio name=ex1 value='x' readOnly='true' checked></td>";
   echo "<td><input type=radio name=ex1 value='y' readOnly='true'></td>";
   echo "<td align=left>Legal, play continues or proper procedure.</td></tr>";
   echo "<tr align=center><td><input type=radio name=ex2 value='x' readOnly=true></td>";
   echo "<td><input type=radio name=ex2 value='y' readOnly=true checked></td>";
   echo "<td align=left>Foul, Incorrect Procedure.</td></tr></table>";
   echo "</th></tr>";
}
*/
echo "<tr align=left><th align=left colspan=2>$category</th></tr>";
//get answers already entered by this official
$sql="SELECT * FROM vbtest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=100;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}
$sql="SELECT question,place FROM vbtest WHERE category='$categid' ORDER BY place";
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
