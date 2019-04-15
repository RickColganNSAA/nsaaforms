<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
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

$sport='sb';
$sportname=GetSportName($sport);
$testtable=$sport."test";
$resultstable=$sport."test_results";
$sql="SELECT * FROM $testtable";
$result=mysql_query($sql);
$totalques=mysql_num_rows($result);

//IS THIS A RETAKE?
if($retake>0)
{
   $sql="DELETE FROM $resultstable WHERE id='$retake' AND offid='$offid'";
   $result=mysql_query($sql);
}

//check if already submitted this test
$sql="SELECT * FROM $resultstable WHERE offid='$offid' AND datetaken!=''";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $level!=1)      //already taken
{
   header("Location:welcome.php?session=$session");
   exit();
}

if($hiddensave || $save)
{
   for($i=0;$i<count($place);$i++)
   {
      $field="ques".$place[$i];
      $sql="SELECT * FROM $resultstable WHERE offid='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)    //INSERT
      {
         $sql2="INSERT INTO $resultstable (offid,$field) VALUES ('$offid','$answer[$i]')";
         $result2=mysql_query($sql2);
      }
      else      //UPDATE
      {
         $sql2="UPDATE $resultstable SET $field='$answer[$i]' WHERE offid='$offid'";
         $result2=mysql_query($sql2);
      }

      //Get Current Category/$curplacestart
      if(($place[$i]%10)==0) $curplacestart=$place[$i]-9;
      else $curplacestart=$place[$i]-($place[$i]%10)+1;
   }
   if($save)	//send to next section
   {
      $placestart=$curplacestart+10;
      if($placestart>$totalques)
	 $placestart="Finish Test";
   }
}
if($save=="Save & Quit")
{
   header("Location:welcome.php?session=$session");
   exit();
}
if($placestart=="Finish Test")
{
   header("Location:".$testtable."_submit.php?session=$session&givenoffid=$givenoffid");
   exit();
}

if(!$placestart) $placestart=1;
$placeend=$placestart+9;

echo $init_html;
echo GetHeader($session);
echo "<table width='750px' class=nine>";
if(GetLevel($session)==1)
{
   echo "<tr align=right><td><a href=\"edit_off.php?session=$session&offid=$offid\">Return to ".GetOffName($offid)."'s Record</a></td></tr>";
}
echo "<tr align=center><td><br>";
echo "<form name=\"test_form\" method=post action=\"$testtable.php\">";
echo "<input type=hidden name=hiddensave value=\"0\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<table width=\"100%\">";
echo "<caption><b>$sportname Rules Examination - Part I</b><br>";
if(GetLevel($session)==1)
{
   echo "for <font style=\"color:red\"><b>".GetOffName($offid)."</b></font><br>";
}
$sql="SELECT * FROM test_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[duedate];
$instructions=$row[instructions];
$date=split("-",$row[fakeduedate]);
$duedate2=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if(GetLevel($session)!=1 && PastDue($duedate,0) && $offid!='3427')
{
   //test is past due
   echo "<br>This test is not available at this time.</caption></table>";
   echo "</td></tr></table></form>";
   echo $end_html;
   exit();
}
echo "Due $duedate2</caption>";
echo "<tr align=left><td align=left colspan=2>Copyrighted and Published by the National Federation of State High School Associations <br><div class=alert><b>INSTRUCTIONS:</b><br><br>";
echo "Indicate your answer to each question or statement by checking the appropriate circle.</div></td></tr>";
echo "<tr align=left><td align=left colspan=2>$instructions</td></tr>";
echo "<tr align=center><td align=center colspan=2><hr><table cellspacing=0 cellpadding=5>";
echo "<tr align=left><th align=left>Questions $placestart-$placeend:</th></tr>";
//get answers already entered by this official
$sql="SELECT * FROM $resultstable WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=$totalques;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}
$sql="SELECT question,place,id FROM $testtable WHERE place>='$placestart' AND place<='$placeend' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   echo "<tr align=left valign=top";
   if($ix%2==0) echo " bgcolor='#F0F0F0'";
   echo "><td>$place.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "<br><ul style='list-style-type:none;'>";
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\">";
   //GET MULTIPLE CHOICES
   $sql2="SELECT * FROM ".$testtable."_mchoices WHERE questionid='$row[id]' ORDER BY orderby";
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
echo "</td></tr></table>";
echo "<div class=\"alert\">You may click \"Go to Next Section\" or \"Jump To...\" a specific section of the test.  You may also jump to \"Finish Test\" which will allow you to officially submit your test.  OR you may click \"Save & Quit\" and come back later to work on your test.  Please note that your test is not complete until you go to \"Finish Test\" and officially submit it.</div>";
//get number answered for each section and total answered
$sql="SELECT * FROM $resultstable WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$totalanswered=0;
$answered=array(); $possible=array();
for($i=1;$i<=10;$i++)
{
   $answered[$i]=0;
}
$sql2="SELECT * FROM $testtable ORDER BY place";
$result2=mysql_query($sql2);
$ix=0; 
while($row2=mysql_fetch_array($result2))
{
   if(($row2[place]%10)==1)	//1, 11, 21, etc
   {
      $ix++;
   }
   $index="ques".$row2[place];
   if($row[$index]!='')
   {
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}
$nextstart=$placestart+10;
if($nextstart>$totalques)
   echo "<br><input type=submit name=save value=\"Finish Test\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
else
   echo "<br><input type=submit name=save value=\"Go to Next Section\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
echo "<select class=small name=\"placestart\" onchange=\"hiddensave.value='1';submit();\"><option>Jump To...";
for($i=1;$i<=10;$i++)
{
   $start=($i*10)-9;
   $end=$start+9;
   echo "<option value=\"$start\">Questions $start to $end (".$answered[$i]." of ".$possible[$i]." answered)</option>";
}
echo "<option>Finish Test</option>";
echo "</select>OR&nbsp;&nbsp;<input type=submit name=\"save\" value=\"Save & Quit\">";
echo "</td></tr></table>"; //end main table
echo "</form>";

echo $end_html;
?>
