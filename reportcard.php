<?php
if(!$sport) $sport='bbb';

require 'variables.php';
require 'functions.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if($givenscoreid)	//user is from Officials' Admin
{
   if(!ValidUser($session,"$db_name2"))
   {
      header("Location:officials/index.php?error=1");
      exit();
   }
   $scoreid=$givenscoreid;
}
elseif(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID($session,$sport);
$schedtbl=$sport."sched";
$schooltbl=GetSchoolsTable($sport);
$tourntbl=$sport."tourn";
if($sport=='bbb' || $sport=='bbg') $offtbl="bboff";
else $offtbl=$sport."off";
$year=GetFallYear($sport);
$reportcard="reportcard_".$sport;

if($submit)	//save to database
{
   $comments1=addslashes($comments1);
   $comments2=addslashes($comments2);
   $comments3=addslashes($comments3);
   $comments4=addslashes($comments4);
   $comments5=addslashes($comments5);
   $feedback=addslashes($feedback);
   if($submit=="Save & Keep Editing") $datesub="";
   else $datesub=time();

   $sql="SELECT * FROM $reportcard WHERE scoreid='$scoreid' AND school='$school2' AND sport='$sport'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO $reportcard (offid1,offid2,offid3,sport,scoreid,radio1,radio2,radio3,radio4,radio5,comments1,comments2,comments3,comments4,comments5,feedback,school,datesub) VALUES ('$offid1','$offid2','$offid3','$sport','$scoreid','$radio1','$radio2','$radio3','$radio4','$radio5','$comments1','$comments2','$comments3','$comments4','$comments5','$feedback','$school2','$datesub')";
   }
   else					//UPDATE
   {
      $row=mysql_fetch_array($result);
      $id=$row[id];
      $sql2="UPDATE $reportcard SET offid1='$offid1',offid2='$offid2',offid3='$offid3',radio1='$radio1',radio2='$radio2',radio3='$radio3',radio4='$radio4',radio5='$radio5',comments1='$comments1',comments2='$comments2',comments3='$comments3',comments4='$comments4',comments5='$comments5',feedback='$feedback',datesub='$datesub' WHERE id='$id'";
   }
   $result2=mysql_query($sql2);
  echo "$sql2<br>".mysql_error();
}

echo $init_html;
if($givenscoreid)
   echo "<table width=100%><tr align=center><td>";
else
   echo $header;

echo "<form method=post action=\"reportcard.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=scoreid value=\"$scoreid\">";
echo "<input type=hidden name=sport value=\"$sport\">";
if(!$givenscoreid)
   echo "<a class=small href=\"reportcards.php?sport=$sport&session=$session&finished=$finished\">Return to Game Report Cards Main Menu</a><br><br>";
echo "<table width=80%><caption><b>Game Report Card on Officials</b></caption>";
echo "<tr align=left><td>To improve the quality of officiating in Nebraska, the NSAA is requesting that coaches fill out the following report card after each game/match.  It is recommended that coaches wait 24 hours before submitting the report card.  The NSAA is interested in the positives of the officiating, the areas that need improvement and any incident/incidents (positive or negative) that the NSAA should be appraised.</td></tr>";
echo "<tr align=left><td>This report card when submitted will be sent to the school's athletic administrator as well as the NSAA.  Data will be collected, officials will be contacted and feedback will be provided with anonymity.</td></tr>";
echo "<tr align=left><td>Coaches are requested to report on the official's performance using the following criteria: The officials professionalism, punctuality, game/match control, judgment, consistency, mechanics,use of correct signals, attitude, willingness to work with players/coaches, any other pertitent information.</td></tr>";
echo "<tr align=left><td><b>ONLY the Athletic Director may officially submit the final version of this report card to the NSAA.</b></td></tr>";

echo "<tr align=center><td><hr>";
if($submit=="Save & Keep Editing")
{
   echo "<font style=\"color:blue\"><b>Your Game Report Card has been saved.  ";
   if($level==3)	//coach
      echo "<br>If you are finished with this report card, please have your AD approve and submit the final version to the NSAA.";
   else	//AD
      echo "<br>Remember you must click \"Submit to NSAA\" in order to send your completed report card to the NSAA.";
   echo "</b></font>";
}
echo "<table>";

$sql="SELECT * FROM $schedtbl WHERE scoreid='$scoreid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($sid==$row[sid])	//school is sid school
{
   $oppid=$row[oppid];
   $oppname=GetSchoolName($oppid,$sport,$year);
   if($row[oppvargame]==0) $oppname.=" (JV)";
}
else	//school is oppid school
{
   $oppid=$row[sid];
   $oppname=GetSchoolName($oppid,$sport,$year);
   if($row[sidvargame]==0) $oppname.=" (JV)";
}
$host=GetSchoolName($row[homeid],$sport,$year);
$sidname=GetSchoolName($sid,$sport,$year);
//if already submitted/saved, get info from database
$sql0="SELECT * FROM $reportcard WHERE school='$school2' AND sport='$sport' AND scoreid='$scoreid'";
$result0=mysql_query($sql0);
$row0=mysql_fetch_array($result0);
if($row0[datesub]!='') $edit=0;
else $edit=1;
$offid1=$row0[offid1]; $offid2=$row0[offid2]; $offid3=$row0[offid3];
$radio1=$row0[radio1]; $radio2=$row0[radio2]; $radio3=$row0[radio3]; $radio4=$row0[radio4]; $radio5=$row0[radio5];
$comments1=$row0[comments1]; $comments2=$row0[comments2]; $comments3=$row0[comments3];
$comments4=$row0[comments4]; $comments5=$row0[comments5];
$feedback=$row0[feedback];

if($edit==1)
   echo "<tr align=left><td><i>You are filling out a report card for the following game:</i></td></tr>";
else
   echo "<tr align=left><td><i>This report card was submitted by <b>$school</b> on <b>".date("F j, Y",$row0[datesub])."</b> for the following game:</i></td></tr>";
echo "<tr align=left><td><b>";
if($row[tid]>0)	//tournament
{
   $sql2="SELECT location,location2,name FROM $tourntbl WHERE tid='$row[tid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "$sidname vs. $oppname<br>";
   echo "$row2[name] @ $row2[location]";
   if(trim($row2[location2])!='') echo "/$row2[location2]";
}
else
   echo "$sidname vs. $oppname @ $host";
echo "</b></td></tr>";
$temp=split("-",$row[received]);
$date="$temp[1]/$temp[2]/$temp[0]";
echo "<tr align=left><td><b>Date of Contest:</b>&nbsp;$date</td></tr>";
if($edit==1)
{
   echo "<tr align=left><td><b>Select the names of your game's officials:<br>";
   echo "<select name=offid1><option value='0'>~</option>";
   $sql="SELECT t1.id,t1.first,t1.last FROM $db_name2.officials AS t1, $db_name2.$offtbl AS t2 WHERE t1.id=t2.offid AND t2.payment!='' ORDER BY t1.last,t1.first";
   $result=mysql_query($sql);
   $offs=array(); $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($offid1==$row[id]) echo " selected";
      echo ">$row[last], $row[first]</option>"; 
      $offs[id][$ix]=$row[id];
      $offs[name][$ix]="$row[last], $row[first]";
      $ix++;
   }
   echo "</select>&nbsp;<select name=offid2><option value='0'>~</option>";
   for($i=0;$i<count($offs[id]);$i++)
   {
      echo "<option value=\"".$offs[id][$i]."\"";
      if($offid2==$offs[id][$i]) echo " selected";
      echo ">".$offs[name][$i]."</option>";
   }
   echo "</select>&nbsp;<select name=offid3><option value='0'>~</option>";
   for($i=0;$i<count($offs[id]);$i++)
   {
      echo "<option value=\"".$offs[id][$i]."\"";
      if($offid3==$offs[id][$i]) echo " selected";
      echo ">".$offs[name][$i]."</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td><br>DIRECTIONS: Please indicate by choosing the appropriate button under each criteria.  You may also add comments to each criteria.</td></tr>";
}
else	//edit=0
{
   echo "<tr align=left><td><b>Officials:&nbsp;</b>";
   $sql="SELECT first,last FROM $db_name2.officials WHERE id='$offid1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$row[0] $row[1]";
   if($offid2!='0')
   {
      $sql="SELECT first,last FROM $db_name2.officials WHERE id='$offid2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo ", $row[0] $row[1]";
   }
   if($offid3!='0')
   {
      $sql="SELECT first,last FROM $db_name2.officials WHERE id='$offid3'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo ", $row[0] $row[1]";
   }
   echo "</td></tr>";
}

$criteria=array("Professionalism","Game Control","Consistency","Mechanics","Communication with Players/Coaches");
$answers=array("Satisfactory","Unsatisfactory","Non-Applicable");
$answers2=array("s","u","n");

for($i=0;$i<count($criteria);$i++)
{
   $num=$i+1;
   $radio="radio".$num;
   $comments="comments".$num;
   echo "<tr align=left><td><br><b><u>$criteria[$i]</u></b></td></tr>";
   echo "<tr align=left><td>";
   for($j=0;$j<count($answers);$j++)
   {
      echo "<input type=radio name=\"$radio\" value=\"$answers2[$j]\"";
      if($edit==0) echo " disabled";
      if($$radio==$answers2[$j]) echo " checked";
      echo ">$answers[$j]&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   if($edit==1)
      echo "<tr align=left><td>Comments:<br><textarea rows=3 cols=70 name=\"$comments\">".$$comments."</textarea></td></tr>";
   else	//edit=0
      echo "<tr align=left><td>Comments:<br>".$$comments."</td></tr>";
}
echo "<tr align=left><td><br><b><u>Positive or Negative Feedback:</u></b></td></tr>";
if($edit==1)
   echo "<tr align=left><td><textarea rows=3 cols=70 name=\"feedback\">$feedback</textarea></td></tr>";
else
   echo "<tr align=left><td>$feedback</td></tr>";

if($edit==1)
{
   echo "<tr align=center><td>";
   echo "<input type=submit name=submit value=\"Save & Keep Editing\"><br>(You may come back and work on this report card later.";
   if($level==3)	//coach
      echo "  Your AD must approve and submit the final version to the NSAA.";
   echo ")<br><br>";
   if($level==2)	//AD
      echo "<input type=submit name=submit value=\"Submit to NSAA\"><br>(You will not be able to come back and make changes once you click \"Submit to NSAA\")";
   echo "</td></tr>";
}
echo "</table></td></tr>";

echo "</table>";
echo "</form>";

echo $end_html;
?>
