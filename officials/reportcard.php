<?php
if(!$sport)
{
   echo "No Sport Selected";
   exit();
}
if($sport=='bbb') { $sport='bb'; $gender='b'; }
else if($sport=='bbg') { $sport='bb'; $gender='g'; }
require 'functions.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;
$offname=GetOffName($offid);
$schedtbl=$sport."sched";
$year=GetFallYear($sport);

if($save)	//save to database
{
   $homecomments1=addslashes($homecomments1);
   $homecomments2=addslashes($homecomments2);
   $homecomments3=addslashes($homecomments3);
   $homecomments4=addslashes($homecomments4);
   $homecomments5=addslashes($homecomments5);
   $homecomments6=addslashes($homecomments6);
   $awaycomments1=addslashes($awaycomments1);
   $awaycomments2=addslashes($awaycomments2);
   $awaycomments3=addslashes($awaycomments3);
   $awaycomments4=addslashes($awaycomments4);
   $homefeedback=addslashes($homefeedback);
   $awayfeedback=addslashes($awayfeedback);
   if($save=="Save & Keep Editing") $datesub="";
   else $datesub=time();

   //get scoreid and gender
   $sql="SELECT * FROM $schedtbl WHERE id='$schedid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $scoreid=$row[scoreid]; 
   if($sport=='bb')
   {
      $gender=$row[gender];
      $wildsp=$sport.$gender;
   }
   else
      $wildsp=$sport;
   $reportcard="reportcard_".$wildsp;

   $sql="SELECT * FROM $reportcard WHERE scoreid='$scoreid' AND offid='$offid' AND sport='$wildsp'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO $reportcard (sport,scoreid,oppid1,oppid2,homeradio1,homeradio2,homeradio3,homeradio4,homeradio5,homeradio6,homecomments1,homecomments2,homecomments3,homecomments4,homecomments5,homecomments6,awayradio1,awayradio2,awayradio3,awayradio4,awaycomments1,awaycomments2,awaycomments3,awaycomments4,homefeedback,awayfeedback,offid,datesub) VALUES ('$wildsp','$scoreid','$oppid1','$oppid2','$homeradio1','$homeradio2','$homeradio3','$homeradio4','$homeradio5','$homeradio6','$homecomments1','$homecomments2','$homecomments3','$homecomments4','$homecomments5','$homecomments6','$awayradio1','$awayradio2','$awayradio3','$awayradio4','$awaycomments1','$awaycomments2','$awaycomments3','$awaycomments4','$homefeedback','$awayfeedback','$offid','$datesub')";
   }
   else					//UPDATE
   {
      $row=mysql_fetch_array($result);
      $id=$row[id];
      $sql2="UPDATE $reportcard SET homeradio1='$homeradio1',homeradio2='$homeradio2',homeradio3='$homeradio3',homeradio4='$homeradio4',homeradio5='$homeradio5',homeradio6='$homeradio6',homecomments1='$homecomments1',homecomments2='$homecomments2',homecomments3='$homecomments3',homecomments4='$homecomments4',homecomments5='$homecomments5',homecomments6='$homecomments6',awayradio1='$awayradio1',awayradio2='$awayradio2',awayradio3='$awayradio3',awayradio4='$awayradio4',awaycomments1='$awaycomments1',awaycomments2='$awaycomments2',awaycomments3='$awaycomments3',awaycomments4='$awaycomments4',homefeedback='$homefeedback',awayfeedback='$awayfeedback',datesub='$datesub' WHERE id='$id'";
   }
   $result2=mysql_query($sql2);
   mysql_error();
}

echo $init_html;
if(!$givenscoreid && $header!='no')
   echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";

echo "<form method=post action=\"reportcard.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=schedid value=\"$schedid\">";
echo "<input type=hidden name=finished value=\"$finished\">";
echo "<input type=hidden name=sport value=\"$sport\">";
if(!$givenscoreid && $header!='no')
   echo "<br><a class=small href=\"reportcards.php?session=$session&finished=$finished\">Return to Game Report Cards Main Menu</a><br>";
echo "<br><table width=80%><caption><b>Game Report Card on Schools</b></caption>";
echo "<tr align=left><td>This report card on schools is to be filed by sports officials after each contest they officiate as an instrument to assist schools with their continuing effort to instill good sportsmanship among players, coaches, and spectators.  This report card shall be filed by the contest officials after each contest.  The NSAA is interested in the positives of the actions of the players, coaches and spectators of the participating schools as well as the areas that need improvement.</td></tr>";
echo "<tr align=left><td>This report card when submitted will be sent to the school's athletic administrator as well as the NSAA.  Data will be collected, schools will be notified and feedback will be provided with anonymity.</td></tr>";
echo "<tr align=left><td>Officials are requested to report on the school's performance using the following criteria: contest management, overall sportsmanship, player's conduct, coaches' conduct, facilities, security, and other pertinent information.</td></tr>";

echo "<tr align=center><td><hr>";
if($save=="Save & Keep Editing")
{
   echo "<font style=\"color:blue\"><b>Your Game Report Card has been saved.  Remember you must click \"Submit to NSAA\" in order to send your completed report card to the NSAA.</b></font>";
}
echo "<table>";

//CHECK TO SEE IF THERE IS A SCOREID STORED FOR THIS OFFICIAL's SCHEDULE ENTRY OR IF THEY NEED TO CHOOSE:
if($newscoreid && $newscoreid!='0')
{
   $sql="UPDATE $schedtbl SET scoreid='$newscoreid'";
   if($sport=='bb') $sql.=",gender='$genderch'"; 
   $sql.=" WHERE id='$schedid'";
   $result=mysql_query($sql);
}

if($givenscoreid) $scoreid=$givenscoreid;
if(!$schedid || $schedid=='0')
{
   $sql="SELECT * FROM $schedtbl WHERE scoreid='$scoreid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $offdate=$row[offdate];
   $gametime=$row[gametime];
}
else
{
   $sql="SELECT * FROM $schedtbl WHERE id='$schedid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);  
   if(!$givenscoreid) $scoreid=$row[scoreid];
   else $scoreid=$givenscoreid;
   if($sport=='bb' && $gender!='b' && $gender!='g') $gender=$row[gender];
   $offdate=$row[offdate];
   $gametime=$row[gametime];
}

if(!$scoreid || $scoreid=='0')	//HAVE OFFICIAL SELECT GAME FROM WILDCARD SCHEDULE
{
   $temp=split("-",$row[offdate]);
   $date="$temp[1]/$temp[2]/$temp[0]";
   $temp=split("-",$row[gametime]);
   $time="$temp[0]:$temp[1] $temp[2]";
   echo "<tr align=left><td><b>INSTRUCTIONS:</b></td></tr>";
   echo "<tr align=left><td>You are filling out a report card for a game you officiated on <b>$date</b> at <b>$time</b> ($row[schools] @ $row[location]).</td></tr>";
   echo "<tr align=left><td>You will need to narrow down to the exact game on which you wish to report.  ";
   if($sport=='bb')
      echo "First, you must select \"Boys\" or \"Girls\" from the \"Select Gender\" dropdown list.  The screen will REFRESH.  Then ";
   else
      echo "First ";
   echo "you must select ONE of the participating teams (it can be either team) from the \"Select an Opponent\" dropdown list.  The screen will REFRESH.  You will then be shown a list of at least one game from which you must choose in order to begin working on your report card.</td></tr>";
   echo "<tr align=center><td>";
   if($sport=='bb')	//gender
   {
      if(!$genderch || $genderch=='') $genderch=$gender;
      echo "<select onchange=\"submit();\" name=genderch><option value=''>Select Gender</option>";
      echo "<option value='b'";
      if($genderch=='b') echo " selected";
      echo ">Boys</option><option value='g'";
      if($genderch=='g') echo " selected";
      echo ">Girls</option>";
      echo "</select>&nbsp;";
   }
   echo "<select name=oppid1 onchange=\"submit();\"><option value='0'>Select an Opponent</option>";
   if(($sport=='bb' && $genderch && $genderch!='') || $sport!='bb')
   {
      if($sport=='bb') 
      {
	 $wildsched="bb".$genderch."sched"; $wildsch="bb".$genderch."school"; $wildsp="bb".$genderch;
	 $wildtourn="bb".$genderch."tourn";
      }
      else 
      {
	 $wildsched=$sport."sched"; $wildsch=$sport."school"; $wildsp=$sport;
	 $wildtourn=$sport."sched";
      }
      $sql2="SELECT DISTINCT t1.school,t1.sid FROM $db_name.$wildsch AS t1, $db_name.$wildsched AS t2 WHERE (t1.sid=t2.sid OR t1.sid=t2.oppid) AND t2.received='$row[offdate]' ORDER BY t1.school";
      $result2=mysql_query($sql2);
      $possiblescoreid=array(); $ix=0;
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value='$row2[sid]'";
	 if($oppid1==$row2[sid]) echo " selected";
	 echo ">$row2[school]</option>";
	 $ix++;
      }
   }
   echo "</select></td></tr>";
   if($oppid1 && $oppid1!='0')	//1st opponent selected	-->show choices as links
   {
      echo "<tr align=center><td>Please click on your game below:<br><table>";
      $sql2="SELECT * FROM $db_name.$wildsched WHERE (sid='$oppid1' OR oppid='$oppid1') AND received='$row[offdate]'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 if($row2[sid]==$oppid1)
	 {
	    $opp1name=GetSchoolName($oppid1,$wildsp,$year);
	    $opp2name=GetSchoolName($row2[oppid],$wildsp,$year);
	 }
         else
	 {
	    $opp1name=GetSchoolName($oppid1,$wildsp,$year);
	    $opp2name=GetSchoolName($row2[sid],$wildsp,$year);
	 }
	 $host=GetSchoolName($row2[homeid],$wildsp,$year);
	 if($row2[tid]>0)
	 {
	    $sql3="SELECT name FROM $db_name.$wildtourn WHERE tid='$row2[tid]'";
	    $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	    $host=$row3[name];
         }
         echo "<tr align=left><td><a class=small href=\"reportcard.php?session=$session&schedid=$schedid&genderch=$genderch&newscoreid=$row2[scoreid]\">$opp1name vs. $opp2name @ $host</a></td></tr>";
      }
      echO "<tr align=left><td>If you do not see your game, then it has not been entered on either opponent's schedule for the date of $date.</td></tr>";
      echo "</table></td></tr>";
   }
}
else	//SCOREID GIVEN
{
   if($sport=='bb')
      $wildsp="bb".$gender;
   else
      $wildsp=$sport;
   $wildsched=$wildsp."sched";
   $wildschool=$wildsp."school";
   $wildtourn=$wildsp."tourn";
   $reportcard="reportcard_".$wildsp;

   //if already submitted/saved, get info from database
   $sql0="SELECT * FROM $reportcard WHERE offid='$offid' AND sport='$wildsp' AND scoreid='$scoreid'";  
   $result0=mysql_query($sql0);
   $row0=mysql_fetch_array($result0);
   if($row0[datesub]!='') $edit=0;
   else $edit=1;
   $homeradio1=$row0[homeradio1]; $homeradio2=$row0[homeradio2]; $homeradio3=$row0[homeradio3]; 
   $homeradio4=$row0[homeradio4]; $homeradio5=$row0[homeradio5]; $homeradio6=$row0[homeradio6];
   $homecomments1=$row0[homecomments1]; $homecomments2=$row0[homecomments2]; 
   $homecomments3=$row0[homecomments3];
   $homecomments4=$row0[homecomments4]; $homecomments5=$row0[homecomments5];
   $homecomments6=$row0[homecomments6];
   $awayradio1=$row0[awayradio1]; $awayradio2=$row0[awayradio2]; $awayradio3=$row0[awayradio3];
   $awayradio4=$row0[awayradio4];
   $awaycomments1=$row0[awaycomments1]; $awaycomments2=$row0[awaycomments2];
   $awaycomments3=$row0[awaycomments3]; $awaycomments4=$row0[awaycomments4];
   $homefeedback=$row0[homefeedback]; $awayfeedback=$row0[awayfeedback];
   $oppid1=$row0[oppid1]; $oppid2=$row[oppid2];

   if($edit==1)
      echo "<tr align=left><td><i>You are filling out a report card for the following game:</i></td></tr>";
   else
      echo "<tr align=left><td><i>This report card was submitted by <b>".GetOffName($offid)."</b> on <b>".date("F j, Y",$row0[datesub])."</b> for the following game:</i></td></tr>";

   $sql="SELECT * FROM $db_name.$wildsched WHERE scoreid='$scoreid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[sid]==$row[homeid] || $row[oppid]==$row[homeid])	//one of the opponents is home team
   {
      if($row[sid]==$row[homeid])
      {
         $homeid=$row[sid]; $awayid=$row[oppid];
      }
      else
      {
	 $homeid=$row[oppid]; $awayid=$row[sid];
      }
      $home=GetSchoolName($homeid,$wildsp,$year);
      $away=GetSchoolName($awayid,$wildsp,$year);
      echo "<tr align=left><td><b>".GetSportName($wildsp)."</b></td></tr>";
      echo "<tr align=left><td><b>Home Team:</b>&nbsp;$home</td></tr>";
      echo "<tr align=left><td><b>Visitor:</b>&nbsp;$away</td></tr>";
      $host=$home;	//to display in Host School section
      if($oppid1=='0' || !$oppid1) $oppid1=$homeid;
      if($oppid2=='0' || !$oppid2) $oppid2=$awayid;
   }
   else
   {
      $opp1=$row[sid]; $opp2=$row[oppid];
      $homeid=$row[homeid];
      $home=GetSchoolName($opp1,$wildsp,$year);
      $away=GetSchoolName($opp2,$wildsp,$year);
      $host=GetSchoolName($homeid,$wildsp,$year);
      if($row[tid]>0)
      {
         $sql2="SELECT name,location FROM $wildtourn WHERE tid='$row2[tid]'";
         $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $host=$row2[name]." (".$row2[location].")";
      }
      echo "<tr align=left><td><b>Opponents:</b>&nbsp;";
      echo "$home vs. $away";
      echo " at $host</td></tr>";
      if($oppid1=='0' || !$oppid1) $oppid1=$opp1;
      if($oppid2=='0' || !$oppid2) $oppid2=$opp2;
   }
   
   $temp=split("-",$offdate);
   $temp2=split("-",$gametime);
   echo "<tr align=left><td><b>Date & Time:</b>&nbsp;$temp[1]/$temp[2]/$temp[0] @ $temp2[0]:$temp2[1] $temp2[2]</td></tr>";
   
   echo "<input type=hidden name=oppid1 value=\"$oppid1\">";
   echo "<input type=hidden name=oppid2 value=\"$oppid2\">";

   if($edit==1)
   {
      echo "<tr align=left><td><br>DIRECTIONS: Please indicate by choosing the appropriate button under each criteria.  You may also add comments to each criteria.</td></tr>";
   }

   $answers=array("Satisfactory","Unsatisfactory","Non-Applicable");
   $answers2=array("s","u","n");
   $hostcriteria=array("Game Management","Facilities");
   echo "<tr align=left><td><br><b>SECTION I:</b> Evaluation of the HOST: <font style=\"color:blue\"><b>$host:</b></font></td></tr>";
   echo "<tr align=center><td><table>";
   for($i=0;$i<count($hostcriteria);$i++)
   {
      $num=$i+1;
      $radio="homeradio".$num;
      $comments="homecomments".$num;
      echo "<tr align=left><td><b><u>$hostcriteria[$i]</u></b></td></tr>";
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
         echo "<tr align=left><td>Comments:<br><i>".$$comments."</i></td></tr>";
   }
   echo "</table></td></tr>";

   $criteria=array("Overall Sportsmanship","Players' Conduct","Coaches' Conduct","Spectators' Conduct");
   echo "<tr align=left><td><br><b>SECTION II:</b> Evaluation of each Opponent:</td></tr>";
   echo "<tr align=center><td><table>";
   for($i=0;$i<count($criteria);$i++)
   {
      $num=$i+3; $num2=$i+1;
      $homeradio="homeradio".$num; $awayradio="awayradio".$num2;
      $homecomments="homecomments".$num; $awaycomments="awaycomments".$num2;
      echo "<tr align=left><td colspan=2><br><b><u>$criteria[$i]</u></b></td></tr>";
      echo "<tr align=left><td><font style=\"color:blue\"><b>$home:</b></td>";
      echo "<td><font style=\"color:blue\"><b>$away:</b></td></tr>";
      echo "<tr align=left><td>";
      for($j=0;$j<count($answers);$j++)
      {
	 echo "<input type=radio name=\"$homeradio\" value=\"$answers2[$j]\"";
	 if($edit==0) echo " disabled";
         if($$homeradio==$answers2[$j]) echo " checked";
	 echo ">$answers[$j]&nbsp;&nbsp;";
      }
      echo "</td><td>";
      for($j=0;$j<count($answers);$j++)
      {
         echo "<input type=radio name=\"$awayradio\" value=\"$answers2[$j]\"";
         if($edit==0) echo " disabled";
         if($$awayradio==$answers2[$j]) echo " checked";
         echo ">$answers[$j]&nbsp;&nbsp;";
      }
      if($edit==1)
      {
         echo "<tr align=left><td>Comments:<br><textarea rows=5 cols=40 name=\"$homecomments\">".$$homecomments."</textarea></td>";
         echo "<td>Comments:<br><textarea rows=5 cols=40 name=\"$awaycomments\">".$$awaycomments."</textarea></td></tr>";
      }
      else
      {
  	 echo "<tr align=left valign=top><td>Comments:<br><i>".$$homecomments."</td>";
         echo "<td>Comments:<br><i>".$$awaycomments."</td></tr>";
      }
   }
   echo "<tr align=left><td><br><b><u>Positive or Negative Feedback:</u></b></td>";
   echo "<tr align=left><td><font style=\"color:blue\"><b>$home:</b></font></td>";
   echo "<td><font style=\"color:blue\"><b>$away:</b></font></td></tr>";
   if($edit==1)
   {
      echo "<td><textarea rows=5 cols=40 name=\"homefeedback\">$homefeedback</textarea></td>";
      echo "<td><textarea rows=5 cols=40 name=\"awayfeedback\">$awayfeedback</textarea></td></tr>";
   }
   else
   {
      echo "<td>$homefeedback</td><td>$awayfeedback</td></tr>";
   }
   echo "</table></td></tr>";

   if($edit==1)
   {
      echo "<tr align=center><td>";
      echo "<input type=submit name=save value=\"Save & Keep Editing\"><br>(You may come back and work on this report card later.)<br><br>";
      echo "<input type=submit name=save value=\"Submit to NSAA\"><br>(You will not be able to come back and make changes once you click \"Submit to NSAA\")";
      echo "</td></tr>";
   }
   echo "</table></td></tr>";
}//end if scoreid
echo "</table>";
echo "</form>";

echo $end_html;
?>
