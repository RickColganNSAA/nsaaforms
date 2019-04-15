<?php

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
$offid=GetOffID($session);

echo $init_html;
echo GetHeader($session);

echo "<br><table width=500><caption><b>";
if($finished==1) 
{
   echo "<a class=small href=\"reportcards.php?session=$session\">See your UNFINISHED Game Report Cards</a><br><br>";
   echo "Game Report Cards you have SUBMITTED:<br></b><i>You have already sent these report cards to the NSAA.  You may no longer make changes to these report cards.</i>";
}
else 
{
   echo "<a class=small href=\"reportcards.php?session=$session&finished=1\">See your SUBMITTED Game Report Cards</a><br><br>";
   echo "UNFINISHED Game Report Cards:<br></b><i>You must complete these report cards and then click \"Submit to NSAA\" at the bottom of the report card in order to send it to the NSAA.</i>";
}
echo "<hr>";
if(!$finished)	//viewing unfinished)
{
   echo "<table><tr align=left><td><b>PLEASE NOTE:</b> If you are officiating a <u>Tournament</u>, please go to your <a class=small href=\"schedule.php?session=$session&sport=bb\">Schedule</a> and enter each game you are officiating in that tournament.  (You may then delete the original single schedule entry you entered for that tournament.)  Then, you will see each game you entered listed below, and thus you may complete report cards for each tournament game.</td></tr></table>";
}
echo "</caption>";
$schedtbl=$cursp."sched"; $tourntbl=$cursp."tourn";
$sportname=GetSportName($cursp);
if(ereg("Girls",$sportname)) $gender='g';
else $gender='b';
$offschedtbl="bbsched";
$today=date("Y-m-d");
$feb10=mktime(23,59,59,2,10,2007);
$now=time();
if($now>$feb10) $today="2007-02-10";
if($finished!=1)	//get games on official's schedule that have not been given a scoreid yet
{
   $sql="SELECT * FROM $offschedtbl WHERE offid='$offid' AND offdate>='2007-01-19' AND offdate<='$today' AND scoreid='0' ORDER BY offdate,gametime";
   $result=mysql_query($sql);
   echo "<tr align=left><td colspan=3><br><b>BOYS & GIRLS BASKETBALL (not started):</b></td></tr>";
   if(mysql_num_rows($result)>0)
   {
      while($row=mysql_fetch_array($result))
      {
         $temp=split("-",$row[offdate]); $date="$temp[1]/$temp[2]/$temp[0]";
         $temp=split("-",$row[gametime]); $date.=" @ $temp[0]:$temp[1] $temp[2]";
         echo "<tr align=left><td>";
         echo "<a class=small href=\"reportcard.php?sport=bb&session=$session&schedid=$row[id]&finished=0\">Begin</a>";
         echo "</td><td>$date</td><td>$row[schools] @ $row[location]";
         echo "</td></tr>";
      }
   }
   else
   {
      echo "<tr align=left><td colspan=3>[You have no Boys or Girls Basketball game report cards that have not been started.]</td></tr>";
   }
}//end if finished!=1
$reportcardsp=array("bbb","bbg");
for($i=0;$i<count($reportcardsp);$i++)
{
   $cursp=$reportcardsp[$i];
   $reportcard="reportcard_".$cursp;
   $schedtbl=$cursp."sched"; $tourntbl=$cursp."tourn"; 
   $sportname=GetSportName($cursp);
   if(ereg("Girls",$sportname)) $gender='g';
   else $gender='b';
   echo "<tr align=left><td colspan=3><br><b>".strtoupper($sportname);
   if($finished!=1) echo " (started, not finished)";
   else echo " (finished & submitted)";
   echo ":</b></td></tr>";
   if(ereg("bb",$cursp)) $offschedtbl="bbsched";
   else $offschedtbl=$cursp."sched";
   if($finished==1)
   {
      $sql="SELECT t1.* FROM $offschedtbl AS t1,$reportcard AS t2 WHERE t1.offid=t2.offid AND t1.scoreid=t2.scoreid AND t1.offid='$offid' AND t2.datesub!='' AND t1.gender='$gender' ORDER BY t1.offdate,t1.gametime";
   }
   else
   {
      $sql="SELECT t1.*,t2.scoreid AS gameid FROM $offschedtbl AS t1 LEFT JOIN $reportcard AS t2 ON (t1.offid=t2.offid AND t1.scoreid=t2.scoreid) WHERE t1.offid='$offid' AND t1.offdate<='$today' AND t1.offdate<='2007-02-10' AND t1.offdate>='2007-01-19' AND (t2.datesub='' OR t2.scoreid IS NULL) AND t1.gender='$gender'"; 
      $sql.=" ORDER BY t1.offdate,t1.gametime";
   }
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=left><td>&nbsp;</td><td width=125><b>Date & Time</b></td><td><b>Opponents & Site</b></td></tr>";
      while($row=mysql_fetch_array($result))
      {
         $temp=split("-",$row[offdate]); $date="$temp[1]/$temp[2]/$temp[0]";
	 $temp=split("-",$row[gametime]); $date.=" @ $temp[0]:$temp[1] $temp[2]";
         echo "<tr align=left><td>";
         if($row[gameid] || $finished==1)
         {
   	    echo "<a class=small href=\"reportcard.php?sport=$cursp&session=$session&schedid=$row[id]&finished=$finished\">";
  	    if($finished==1) echo "View";
	    else echo "Edit";
	    echo "</a>";
	    echo "</td><td>$date</td>";
	    $year=GetFallYear($cursp);
            if($row[gameid]) $gameid=$row[gameid];
	    else if($finished==1) $gameid=$row[scoreid];
	    $sql2="SELECT * FROM $db_name.$schedtbl WHERE scoreid='$gameid'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
            $host=GetSchoolName($row2[homeid],$cursp,$year);
	    if($row2[tid]>0)
	    {
   	       $sql3="SELECT name FROM $db_name.$tourntbl WHERE tid='$row2[tid]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       $host=$row3[0]; 
	    }
            echo "<td>".GetSchoolName($row2[sid],$cursp,$year)." vs. ".GetSchoolName($row2[oppid],$cursp,$year)." @ $host</td></tr>";
	 }
         else
 	 {
	    echo "<a class=small href=\"reportcard.php?sport=$cursp&session=$session&schedid=$row[id]&finished=0\">Begin</a>";
	    echo "</td><td>$date</td><td>$row[schools] @ $row[location]";
	    echo "</td></tr>";
	 }
      }
   }
   else 
   {
      if($finished==1) $finish="submitted";
      else $finish="unfinished";
      echo "<tr align=left><td colspan=3>[You have no $finish $sportname game report cards.]</td></tr>";
   }
}

echo $end_html;
?>
