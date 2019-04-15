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

if($hiddensave || $save)
{
   for($i=0;$i<count($place);$i++)
   {
      //get current category
      $sql="SELECT category FROM sptest WHERE place='$place[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $curcategid=$row[0];

      $field="ques".$place[$i];
      $sql="SELECT * FROM sptest_results WHERE offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)    //INSERT
      {
         $sql2="INSERT INTO sptest_results (offid,$field) VALUES ('$offid','$answer[$i]')";
         $result2=mysql_query($sql2);
      }
      else      //UPDATE
      {
         $sql2="UPDATE sptest_results SET $field='$answer[$i]' WHERE offid='$offid'";
         $result2=mysql_query($sql2);
      }
   }
   if($save)    //send to next section
   {
      $sql="SELECT place FROM sptest_categ WHERE id='$curcategid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $next=$row[0]+1;
      $sql="SELECT * FROM sptest_categ WHERE place='$next'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0 || $test=='speech' || $test=='play')
         $categid="Finish Test";
      else
      {
         $row=mysql_fetch_array($result);
         $categid=$row[id];
      }
   }
}
if($save=="Save & Quit")
{
   header("Location:jwelcome.php?session=$session");
   exit();
}
if($categid=="Finish Test")
{
   header("Location:sptest_submit.php?session=$session&givenoffid=$givenoffid&test=$test");
   exit();
}

echo $init_html;
echo GetHeaderJ($session);
echo "<br>";
echo "<form name=\"test_form\" method=post action=\"sptest.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=test value=\"$test\">";
echo "<input type=hidden name=categid value=\"$categid\">";
echo "<input type=hidden name=forcecategid>";
echo "<input type=hidden name=givenoffid value=$givenoffid>";
echo "<input type=hidden name=hiddensave value=\"0\">";
echo "<input type=hidden name=home>";
echo "<table width='750px' class=nine>";
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
echo "<div class='alert'><b>INSTRUCTIONS:</b> Indicate your answer to each question or statement by checking the appropriate circle.</div>";
echo "<hr>";
if(test=="speech")
   echo "<font style=\"font-size:8pt;\"><b>NOTE: Make sure to SCROLL DOWN to see and answer all 50 questions on the Speech portion of this test.</font>";
echo "</caption>";
if(!$categid)
{
   $sql="SELECT * FROM sptest_categ WHERE category LIKE '%Speech%'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $categid=$row[id];
}
$sql="SELECT category FROM sptest_categ WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$category=$row[0];
echo "<tr align=left><th align=left>$category:</th></tr>";
//get answers already entered by this official
$sql="SELECT * FROM sptest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=60;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}
$sql="SELECT question,place,id FROM sptest WHERE category='$categid' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   if($test=="play") $showplace=$place-50;
   else $showplace=$place;
   echo "<tr align=left><td>$showplace.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\"><ul style='list-style-type:none;'>";
   //GET MULTIPLE CHOICES
   $sql2="SELECT * FROM sptest_mchoices WHERE questionid='$row[id]' ORDER BY orderby";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<li><input type=radio name=\"answer[$ix]\" value=\"$row2[choicevalue]\"";
      if($answer[$place]==$row2[choicevalue]) echo " checked";
      echo ">$row2[choicelabel]</li>";
   }
   echo "</ul></td></tr>";
   $ix++;
}
echo "</table>";
if($test=="speech" || $test=="play")       //only show Finish Test button
{
   echo "<input type=submit name=save value=\"Save & Finish Test\">";
}
else
{
//get number answered for each section and total answered
$sql="SELECT * FROM sptest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$totalanswered=0;
$answered=array(); $possible=array();
$sql2="SELECT * FROM sptest_categ";
$result2=mysql_query($sql2);
$i=1;
while($row2=mysql_fetch_array($result2))
{
   $answered[$i]=0; $possible=array();
   $i++;
}
$sql2="SELECT * FROM sptest ORDER BY place";
$result2=mysql_query($sql2);
$ix=0; $curcategid=0;
while($row2=mysql_fetch_array($result2))
{
   if($row2[category]!=$curcategid)
   {
      $curcategid=$row2[category];  $ix++;
   }
   $index="ques".$row2[place];
   if($row[$index]!='')
   {
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}
echo "<div class=\"alert\" style='width:750px'>You may click \"Go to Next Section\" or \"Jump To...\" a specific section of the test.  You may also jump to \"Finish Test\" which will allow you to officially submit your test.  OR you may click \"Save & Quit\" and come back later to work on your test.  Please note that your test is not complete until you go to \"Finish Test\" and officially submit it.</div>";
$sql="SELECT place FROM sptest_categ WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$next=$row[0]+1;
$sql="SELECT * FROM sptest_categ WHERE place='$next'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "<br><input type=submit name=save value=\"Finish Test\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
else
   echo "<br><input type=submit name=save value=\"Go to Next Section\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
echo "<select class=small name=categid onchange=\"hiddensave.value='1';submit();\"><option>Jump To...";
//get category list from db
$sql="SELECT id,category,place FROM sptest_categ ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\">$row[category] (".$answered[$row[place]]." of ".$possible[$row[place]]." answered)</option>";
}
echo "<option>Finish Test</option>";
echo "</select>&nbsp;&nbsp;OR&nbsp;&nbsp;<input type=submit name=\"save\" value=\"Save & Quit\">";
}//end if combo speech/play test
echo "</form>";
echo $end_html;

?>
