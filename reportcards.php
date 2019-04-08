<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
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

echo $init_html;
echo $header;

echo "<table><caption><b>";
if($finished==1) 
{
   echo "<a class=small href=\"reportcards.php?sport=$sport&session=$session\">View your UNFINISHED Game Report Cards</a><br><br>";
   echo "Game Report Cards your School has SUBMITTED:<br></b>";
   echo "<table><tr align=left><td>";
   if($level==2)	//AD
      echo "<i>You have already sent these report cards to the NSAA.  You may no longer make changes to these report cards.</i>";
   else	//coach
      echo "<i>Your AD has already sent these report cards to the NSAA.  You may no longer make changes to these report cards.</i>";
   echo "</td></tr></table>";
}
else 
{
   echo "<a class=small href=\"reportcards.php?sport=$sport&session=$session&finished=1\">View your SUBMITTED Game Report Cards</a><br><br>";
   echo "UNFINISHED Game Report Cards:<br></b><table>";
   echo "<tr align=left><td><i>";
   if($level==2)	//AD
      echo "You must complete these report cards and then click \"Submit to NSAA\" at the bottom of the report card in order to send it to the NSAA.";
   else	//coach
      echo "Your AD must approve these report cards before submitting the final version to the NSAA.";
   echo "</i></td></tr></table>";
echo "<hr></caption>";
}

if($level==2)	//AD
{
   $reportcardsp=array("bbb","bbg");
   echo "<form method=post action=\"reportcards.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=finished value=\"$finished\">";
   echo "<select onchange=\"submit();\" name=sport>";
   if(!$sport || $sport=='') $sport='bbb';
   for($i=0;$i<count($reportcardsp);$i++)
   {
      echo "<option value=\"$reportcardsp[$i]\"";
      if($sport==$reportcardsp[$i]) echo " selected";
      echo ">".GetActivityName($reportcardsp[$i])."</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\">";
   echo "</form>";
}
else	//coach
{
   $sport=GetActivity($session);
   if($sport=="Boys Basketball") $sport='bbb';
   else if($sport=="Girls Basketball") $sport='bbg';
}

   $sportname=GetActivityName($sport);
   echo "<tr align=left><td colspan=4><br><b>".strtoupper($sportname).":</b></td></tr>";
   $schedtbl=$sport."sched";
   $tourntbl=$sport."tourn";
   $reportcard="reportcard_".$sport;
   $year=GetFallYear($sport);
   $sid=GetSID($session,$sport);
   if($sid!="NO SID FOUND")
   {
      $today=date("Y-m-d");
      $now=time(); $feb10=mktime(23,59,59,2,10,2007);
      if($feb10<$now)
         $today="2007-02-10";
   if($finished==1)
      $sql="SELECT t1.* FROM $schedtbl AS t1, $reportcard AS t2 WHERE t1.scoreid=t2.scoreid AND t2.school='$school2' AND t2.datesub!='' ORDER BY t1.received";
   else
      $sql="SELECT t1.*,t2.scoreid as gameid FROM $schedtbl AS t1 LEFT JOIN $reportcard AS t2 ON t1.scoreid=t2.scoreid WHERE ((t2.school='$school2' AND t2.datesub='') OR t2.id IS NULL) AND t1.received<='$today' AND t1.received>='2007-01-19' AND (t1.sid='$sid' OR t1.oppid='$sid') ORDER BY t1.received";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=left><td>&nbsp;</td><td><b>Date</b></td><td><b>Opponent & Site</b></td><td><b>Score</b></td></tr>";
      while($row=mysql_fetch_array($result))
      {
         if($sid==$row[sid])
   	 {
	    $oppid=$row[oppid];
	    $oppvargame=$row[oppvargame];
	    $score="$row[sidscore]-$row[oppscore]";
	 }
	 else
	 {
    	    $oppid=$row[sid];
	    $oppvargame=$row[sidvargame];
	    $score="$row[oppscore]-$row[sidscore]";
	 }
	 $oppname=GetSchoolName($oppid,$sport,$year);
	 $host=GetSchoolName($row[homeid],$sport,$year); 
         if($row[tid]!='0')
         {
	     $sql2="SELECT name FROM $tourntbl WHERE tid='$row[tid]'";
	     $result2=mysql_query($sql2);
	     $row2=mysql_fetch_array($result2);
	     $host=$row2[0];
         }
         $temp=split("-",$row[received]);
	 $date="$temp[1]/$temp[2]/$temp[0]";
         if($oppid!='0')
	 {
	    echo "<tr align=left><td>";
	    if($row[gameid])
	       echo "<a class=small href=\"reportcard.php?sport=$sport&session=$session&scoreid=$row[gameid]\">Edit</a>";
	    else
	    {
	       if($finished==1) 
	 	  $word="View";
	       else 
	          $word="Begin";
	       echo "<a class=small href=\"reportcard.php?sport=$sport&session=$session&scoreid=$row[scoreid]\">$word</a>";
	    }
	    echo "</td><td>$date</td><td>vs. $oppname @ $host</td><td>$score</td></tr>";
         }    
      }
   }
   else 
   {
      if($finished==1) $finish="submitted";
      else $finish="unfinished";
      echo "<tr align=center><td colspan=4>[You have no $finish $sportname report cards.]</td></tr>";
   }
   }//end if sid found

echo "</table>";

echo $end_html;
?>
