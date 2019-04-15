<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

if($offid)	//report on officials
{
   $year=GetFallYear($sport);
   $reporttbl="reportcard_".$sport;
   $schedtbl=$sport."sched";
   $sql="SELECT * FROM $db_name.$reporttbl WHERE (offid1='$offid' OR offid2='$offid' OR offid3='$offid' OR offid4='$offid' OR offid5='$offid' OR offid6='$offid') AND datesub!='' ORDER BY datesub ASC";
   $result=mysql_query($sql);
   echo "<table cellspacing=0 cellpadding=2 border=1 bordercolor=#000000 width=99%>";
   if($field!='feedback')
      echo "<caption><b>Comments on ".GetOffName($offid)."'s $criteria:</b></caption>";
   else
      echo "<caption><b>".GetOffName($offid)."'s Positive or Negative Feedback:</b></caption>";
   echo "<tr align=center><td><b>Game</b></td><td><b>Comment</b></td><td><b>School</b></td><td><b>Date of<br>Report</b></td></tr>";
   $ct=0;
   while($row=mysql_fetch_array($result))
   {
      $scoreid=$row[scoreid];
      $sql2="SELECT * FROM $db_name.$schedtbl WHERE scoreid='$scoreid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $opp1=GetSchoolName($row2[sid],$sport,$year); $opp2=GetSchoolName($row2[oppid],$sport,$year); 
      $date=split("-",$row2[received]);
      $gamedate="$date[1]/$date[2]/$date[0]";
      $comment=$row[$field];
      $school=$row[school];
      $datesub=date("m/d/Y",$row[datesub]);
      if($comment!='')
      {	
	 $ct++;
         echo "<tr align=left valign=top><td>$gamedate<br>$opp1 vs $opp2</td>";
         echo "<td width=300>$comment</td><td>$school</td><td>$datesub</td></tr>";
      }
   }
   echo "<tr align=center><td colspan=4><b>$ct</b> Total Comments Submitted.</td></tr>";
   echo "</table>";
}
else if($sid)
{
   $year=GetFallYear($sport);
   $reporttbl="reportcard_".$sport;
   $schedtbl=$sport."sched";
   $sql="SELECT * FROM $reporttbl WHERE (oppid1='$sid' OR oppid2='$sid') AND datesub!='' ORDER BY datesub ASC";
   $result=mysql_query($sql);
   echo "<table cellspacing=0 cellpadding=2 border=1 bordercolor=#000000 width=99%>";
   if($field!='feedback')
      echo "<caption><b>Comments on ".GetSchoolName($sid,$sport,$year)."'s $criteria:</b></caption>";
   else
      echo "<caption><b>".GetSchoolName($sid,$sport,$year)."'s Positive or Negative Feedback:</b></caption>";
   echo "<tr align=center><td><b>Game</b></td><td><b>Comment</b></td><td><b>Official</b></td><td><b>Date of<br>Report</b></td></tr>";
   $ct=0;
   while($row=mysql_fetch_array($result))
   {
      $scoreid=$row[scoreid];
      $sql2="SELECT * FROM $db_name.$schedtbl WHERE scoreid='$scoreid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $opp1=GetSchoolName($row2[sid],$sport,$year); $opp2=GetSchoolName($row2[oppid],$sport,$year);
      $date=split("-",$row2[received]);
      $gamedate="$date[1]/$date[2]/$date[0]";
      $homenum=$num; $awaynum=$num-2;
      if($row[oppid1]==$sid && $field=="feedback") $field="homefeedback";
      else if($row[oppid2]==$sid && $field=="feedback") $field="awayfeedback";
      else if($row[oppid1]==$sid) $field="homecomments".$homenum;
      else $field="awaycomments".$awaynum;
      $comment=$row[$field];
      $offid=$row[offid];
      $datesub=date("m/d/Y",$row[datesub]);
      if((!(ereg("away",$field) && $awaynum<=0) || ereg("feedback",$field)) && $comment!='')
      {
         echo "<tr align=left valign=top><td>$gamedate<br>$opp1 vs $opp2</td>";
         echo "<td width=300>$comment</td><td>".GetOffName($offid)."</td><td>$datesub</td></tr>";
	 $ct++;
      }
   }
   echo "<tr align=center><td colspan=4><b>$ct</b> Total Comments Submitted.</td></tr>";
   echo "</table>";
}
echo $end_html;
?>
