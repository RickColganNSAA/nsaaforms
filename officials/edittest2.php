<?php
//edittest2.php: allow NSAA user to look at online PART 2 test questions and edit the question and/or answer

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

if($sport=='' || !$sport) exit();
$sportname=GetSportName($sport);

//get tables for this sport's test
$test=$sport."test2";
$results=$sport."test2_results";

echo $init_html;
echo GetHeader($session,"test2report");
echo "<br>";
echo "<a href=\"test2report.php?session=$session&sport=$sport\" class=small>Return to $sportname Online Test Admin</a><br><br>";
echo "<form method=post action=\"updatetest2.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";

//GET TEST INSTRUCTIONS AND TOTAL # QUESTIONS THE TEST TAKER GETS
$sql="SELECT * FROM test2_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$instructions=$row[instructions];
$totalques=$row[totalques];

if($saved)
   echo "<div class=alert style=\"width:350px;\">Your changes have been saved.</div><br><br>";
echo "<table cellspacing=0 cellpadding=3 width='800px'><caption><b>Edit PART 2 $sportname Test Questions/Answers:<hr></b>";
echo "<div class='alert'><p>Officials taking the Part 1 $sportname exam online will take a test of $totalques questions, randomly selected from the pool of questions in the database, put into a random order.</p><p>Edit the instructions and/or the existing questions in the database below or <a href=\"addtest2question.php?session=$session&sport=$sport\">Add a NEW question HERE</a>.</p><p>You can also <a href=\"importtest2.php?session=$session&sport=$sport\">Import Part 2 Test Questions HERE</a>.</p>";
echo "</div>";
echo "<p style=\"text-align:left;\"><b>SPECIAL INSTRUCTIONS:</b><br /><textarea name=\"instructions\" style=\"width:700px;height:100px;\">$instructions</textarea>
        <p style=\"text-align:left;\"><b>Total # of Questions on this test:</b> <input type=text name=\"totalques\" value=\"$totalques\" size=4><br /><br /><i>(Click Save at the bottom of this screen.)</i></p><br />";
echo "</caption>";

$ques=array(); $ans=array();

echo "<tr align=left><td>&nbsp;</td><td><b>Question:</b></td>";
echo "<td><b>Answer:</b></td><td><b>Reference:</b></td></tr>";
$sql2="SELECT question,place,answer,reference,id FROM $test ORDER BY id";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   $place=$ix+1;
   if($ix==0) $start=$place;
   echo "<tr valign=top align=left";
   if($ix%2==0) echo " bgcolor='#e0e0e0'";
   echo "><th align=left>$place.<input type=hidden name=\"quesid[$ix]\" value=\"$row2[id]\"></th>";
   echo "<td><textarea class=small name=\"ques[$ix]\" rows=5 cols=50>$row2[question]</textarea><br><div class=alert><a class=small href=\"addtest2question.php?sport=$sport&session=$session&quesid=$row2[id]\">Edit this Question & its Multiple Choice Options</a></td>";
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
   echo "<div class=error><input type=checkbox name=\"delete[$ix]\" value=\"x\">DELETE this question from the pool";
      $sql3="SELECT * FROM ".$test."_answers WHERE questionid='$row2[id]' AND answer!=''";
      $result3=mysql_query($sql3);
   if(mysql_num_rows($result3)==1)
      echo "<br>(<b><i>CAREFUL!</i></b> An official has already answered this question on his/her test!)";
   else if(mysql_num_rows($result3)>0)
      echo "<br>(<b><i>CAREFUL!</b></i> ".mysql_num_rows($result3)." officials have already answered this question on their test!)";
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
echo "<a class=small href=\"test2report.php?session=$session&sport=$sport\">$sportname Online Test Admin</a>&nbsp;&nbsp;&nbsp;";
if($sport=='sp')
   echo "<a class=small href=\"jwelcome.php?session=$session\">Home</a>";
else
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;

?>
