<?php
/*******************************
sotest2_frame.php
Part 2 (Supervised) Online Test
Frame held by sotest2.php
The answering of the questions is
done within this frame/script
Created 8/18/11
By Ann Gaffigan
********************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$offid=GetOffID($session);

/******SPORT AND TABLE NAMES******/
$sport='so';
$sportname=GetSportName($sport);
$testtable=$sport."test2";
$categtable=$sport."test2_categ";
$resultstable=$sport."test2_results";
$answerstable=$sport."test2_answers";
$mchoicestable=$sport."test2_mchoices";

//GET VITALS ABOUT THIS TEST
$sql="SELECT * FROM test2_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$showdate=$row[showdate]; $duedate=$row[duedate]; $totalques=$row[totalques];
$instructions=$row[instructions];

if($hiddensave || $save)	//SAVE USER ANSWERS
{
   for($i=0;$i<count($place);$i++)      //Update answers for each question in this section
   {
      //get current category ID
      $sql="SELECT category FROM $answerstable WHERE place='$place[$i]' AND offid='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $curcategid=$row[0];

      $sql="SELECT * FROM $answerstable WHERE offid='$offid' AND questionid='$questionid[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)    //INSERT
      {
         $sql2="INSERT INTO $answerstable (offid,questionid,place,answer) VALUES ('$offid','$questionid[$i]','$place[$i]','$answer[$i]')";
         $result2=mysql_query($sql2);
      }
      else      //UPDATE
      {
         $row=mysql_fetch_array($result);
         $sql2="UPDATE $answerstable SET answer='$answer[$i]' WHERE id='$row[id]'";
         $result2=mysql_query($sql2);
      }
   }
   if($save)    //send to next section
   {
      $sql="SELECT place FROM $categtable WHERE id='$curcategid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $next=$row[0]+1;
      $sql="SELECT * FROM $categtable WHERE place='$next'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0 || $save=="Save & Finish Test")
         $categid="Save & Finish Test";
      else
      {
         $row=mysql_fetch_array($result);
         $categid=$row[id];
      }
   }
}
if($save=="Save & Quit")
{
   if($level==1)
      header("Location:test2report.php?session=$session&sport=$sport");
   else
      header("Location:welcome.php?session=$session");
   exit();
}
if($categid=="Save & Finish Test")
{
   header("Location:".$testtable."_submit.php?session=$session&givenoffid=$givenoffid");
   exit();
}

$sql="SELECT * FROM $categtable WHERE id='$categid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   $sql="SELECT * FROM $categtable ORDER BY place LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $categid=$row[id];
}

echo $init_html;
echo "<table width='100%'><tr align=center><td>";
echo "<form name=\"test_form\" method=post action=\"".$testtable."_frame.php\">";
echo "<input type=hidden name=hiddensave value=\"0\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=100%>";
echo "<caption><b>$sportname Supervised Test</b><br>";
$date=split("-",$duedate);
$duedate2=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($offid!='3427' && (PastDue($duedate,0) || !PastDue($showdate,-1))) //TEST IS NOT AVAILABLE
{
   echo "<br>This test is not available at this time.</caption></table></form>";
   echo $end_html;
   exit();
}
echo "You must complete and submit this test by <u>$duedate2</u>.<br>";
echo "</caption>";
echo "<tr align=left><td align=left>";
echo "<div class=alert><i><b>Instructions & Notes:</b></i><br><br>";
echo "Indicate your answer for each question by checking the correct circle.<br><br><b>PLEASE NOTE: </b>If you get <b>disconnected</b> from the internet while taking this test, you will have to start over. We apologize for the inconvenience, but we need to make sure you complete this test within 60 minutes. If you get disconnected from the internet, we lose track of your time. Therefore, you will have to start over with a new set of questions.<br><br><b>If you run out of time</b> before completing the test, the answers you've already entered will be submitted and your score will be recorded for the questions you were able to complete.<br><br><b>Keep an eye on the clock and SAVE your answers before time runs out!</b><br><br><B>DO NOT USE THE BACK BUTTON OR THE RELOAD BUTTON ON YOUR BROWSER.</b> This will cause you to have to start the test over.<br /><p>$instructions</p></div></td></tr>";
echo "<tr align=center><td align=center><table cellspacing=0 cellpadding=5>";
$sql="SELECT category FROM $categtable WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$category=$row[0];
echo "<tr align=left><th align=left>$category:</th></tr>";
//GET USER's QUESTIONS/ANSWERS
$sql="SELECT t1.question,t1.answer AS correctanswer,t2.place,t2.questionid,t2.answer FROM $testtable AS t1,$answerstable AS t2 WHERE t1.id=t2.questionid AND t2.category='$categid' AND t2.offid='$offid' ORDER BY t2.place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[place];
   echo "<tr align=left valign=top";
   if($ix%2==0) echo " bgcolor='#F0F0F0'";
   echo "><td>$place.&nbsp;&nbsp;&nbsp;";
   echo $row[question];
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\">";
   echo "<input type=hidden name=\"questionid[$ix]\" value=\"$row[questionid]\">";
   echo "<br><ul style='list-style-type:none;'>";
   //GET MULTIPLE CHOICES
   $sql2="SELECT * FROM ".$testtable."_mchoices WHERE questionid='$row[questionid]' ORDER BY orderby";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<li><input type=radio name=\"answer[$ix]\" value=\"$row2[choicevalue]\"";
      if($row[answer]==$row2[choicevalue]) echo " checked";
      echo ">$row2[choicelabel]</li>";
   }
   echo "</ul></td></tr>";

   $ix++;
}
echo "</table>";
echo "</td></tr></table>";
//get number answered for each section and total answered
$sql="SELECT * FROM $answerstable WHERE offid='$offid' ORDER BY category,place";
$result=mysql_query($sql);
$totalanswered=0; $ix=0; $curcategid=0;
$answered=array(); $possible=array();
while($row=mysql_fetch_array($result))
{
   if($row[category]!=$curcategid)
   {
      $curcategid=$row[category];  $ix++; $answered[$ix]=0;
   }
   if($row[answer]!='')
   {
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}
echo "<div class=\"alert\">You may click \"Save & Go to Next Section\" or \"Save & Jump To...\" a specific section of the test.  You may also jump to \"Save & Finish Test\" which will allow you to officially submit your test. Please note that your test is not complete until you go to \"Save & Finish Test\" and officially submit it.</div>";
//check if this is last section of test
$sql="SELECT place FROM $categtable WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$next=$row[0]+1;
$sql="SELECT DISTINCT t1.* FROM $categtable AS t1,$answerstable AS t2 WHERE t1.id=t2.category AND t1.place='$next'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "<br><input type=submit name=save value=\"Save & Finish Test\">&nbsp;&nbsp;OR&nbsp;&nbsp;<br>";
else
   echo "<br><input type=submit name=save value=\"Save & Go to Next Section\">&nbsp;&nbsp;OR&nbsp;&nbsp;<br>";
echo "<select class=small name=categid onchange=\"hiddensave.value='1';submit();\"><option>Save & Jump To...";
//get category list from db
$sql="SELECT DISTINCT t1.* FROM $categtable AS t1,$answerstable AS t2 WHERE t1.id=t2.category ORDER BY t1.place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\">$row[category] (".$answered[$row[place]]." of ".$possible[$row[place]]." answered)</option>";
}
echo "<option>Save & Finish Test</option>";
echo "</select></td></tr></table>"; //end main table
echo "</form>";
echo $end_html;
?>
