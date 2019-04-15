<?php
/******************************
edittest.php: allow NSAA user to look at online PART 1 test questions and edit the question and/or answer
Created 7/7/14 by Ann Gaffigan
*******************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($sport=='sp' || $sport=='pp') $level=GetLevelJ($session);
else $level=GetLevel($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

if($sport=='' || !$sport) exit();
if($sport=='sp') $sportname="Speech";
else if($sport=='pp') $sportname="Play Production";
else $sportname=GetSportName($sport);

//get tables for this sport's test
$test=$sport."test";
$results=$sport."test_results";

echo $init_html;
if($sport=='sp' || $sport=='pp')
   echo GetHeaderJ($session,"jtestreport")."<br><a href=\"jtestreport.php?session=$session&sport=$sport\" class=small>Return to $sportname Online Test Admin</a><br><br>";
else if($sport=='sos')
   echo GetHeader($session,"testreport")."<br><a href=\"testreport.php?session=$session&sport=so\" class=small>Return to Soccer Online Test Admin</a><br><br>";
else
   echo GetHeader($session,"testreport")."<br><a href=\"testreport.php?session=$session&sport=$sport\" class=small>Return to $sportname Online Test Admin</a><br><br>";
echo "<form method=post action=\"updatetest.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";

//GET TEST INSTRUCTIONS AND TOTAL # QUESTIONS THE TEST TAKER GETS
$sql="SELECT * FROM test_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$instructions=$row[instructions];
$totalques=$row[totalques];

if($saved)
   echo "<div class=alert style=\"width:350px;\">Your changes have been saved.</div><br><br>";
echo "<table cellspacing=0 cellpadding=3 width='800px'><caption><b>Edit PART 1 $sportname Test Questions/Answers:<hr></b>";
if($sport=='sos')echo "<div class='alert'><p>Officials taking the Part 1 $sportname exam online will take a test of $totalques questions, ordered as they are entered here.</p><p>Edit the instructions and/or the existing questions in the database below or <a href=\"addtestquestion.php?session=$session&sport=$sport\">Add a NEW question (Spanish) HERE</a>.</p><p>You can also <a href=\"importtest.php?session=$session&sport=$sport\">Import Test (Spanish) Questions HERE</a>.</p>";
else
echo "<div class='alert'><p>Officials taking the Part 1 $sportname exam online will take a test of $totalques questions, ordered as they are entered here.</p><p>Edit the instructions and/or the existing questions in the database below or <a href=\"addtestquestion.php?session=$session&sport=$sport\">Add a NEW question HERE</a>.</p><p>You can also <a href=\"importtest.php?session=$session&sport=$sport\">Import Test Questions HERE</a>.</p>";
echo "</div>";
echo "<p style=\"text-align:left;\"><b>SPECIAL INSTRUCTIONS:</b><br /><textarea name=\"instructions\" style=\"width:700px;height:100px;\">$instructions</textarea>
	<p style=\"text-align:left;\"><b>Total # of Questions on this test:</b> <input type=text name=\"totalques\" value=\"$totalques\" size=4><br /><br /><i>(Click Save at the bottom of this screen.)</i></p><br />";
echo "</caption>";

$ques=array(); $ans=array();

echo "<tr align=left><td>&nbsp;</td><td><b>Question:</b></td>";
echo "<td><b>Answer:</b></td><td><b>Reference:</b></td></tr>";
$sql2="SELECT question,place,answer,reference,id FROM $test ORDER BY place";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   $place=$row2[place];
   if($ix==0) $start=$place;
   echo "<tr valign=top align=left";
   if($ix%2==0) echo " bgcolor='#e0e0e0'";
   echo "><th align=left>$place.<input type=hidden name=\"quesid[$ix]\" value=\"$row2[id]\"></th>";
   echo "<td><textarea class=small name=\"ques[$ix]\" rows=5 cols=50>$row2[question]</textarea><br><div class=alert style=\"width:250px;\"><a class=small href=\"addtestquestion.php?sport=$sport&session=$session&quesid=$row2[id]\">Edit this Question & its Multiple Choice Options</a></td>";
   echo "<td>";
   //GET MULTIPLE CHOICES
   $sql3="SELECT * FROM ".$test."_mchoices WHERE questionid='$row2[id]' ORDER BY orderby";
   $result3=mysql_query($sql3);
   while($row3=mysql_fetch_array($result3))
   {
      echo "<input type=radio name=\"ans[$ix]\" value=\"$row3[choicevalue]\"";
      if($row2[answer]==$row3[choicevalue]) echo " checked";
      echo ">$row3[choicelabel]<br>";
   }
   echo "<input type=radio name=\"ans[$ix]\" value=\"acceptall\"";
   if($row2[answer]=='acceptall') echo " checked";
   echo ">ACCEPT ANY ANSWER</td>";
   echo "<td><textarea class=small rows=2 cols=40 name=\"ref[$ix]\">$row2[reference]</textarea><br>";
   echo "<div class=error style=\"width:200px;\"><input type=checkbox name=\"delete[$ix]\" value=\"x\">DELETE this question";
   echo "<br>(NOTE: It's better to overwrite a question than to completely delete it.)";
   echo "</div>";
   echo "</td>";
   echo "</tr>";
   if($ix%10==0 && $ix>0)
   {
      echo "<tr align=center><td colspan=4>";
      echo "<input type=submit name=save value=\"Save\" class=fancybutton>&nbsp;";
      echo "</td></tr>";
   }
   $ix++;
}
   echo "<tr align=center><td colspan=4>";
   echo "<input type=submit name=save value=\"Save\" class=fancybutton>&nbsp;";
   echo "</td></tr>";
echo "</table></form>";
echo "<a class=small href=\"testreport.php?session=$session&sport=$sport\">$sportname Online Test Admin</a>&nbsp;&nbsp;&nbsp;";
if($sport=='sp' || $sport=='pp')
   echo "<a class=small href=\"jwelcome.php?session=$session\">Home</a>";
else
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;

?>
