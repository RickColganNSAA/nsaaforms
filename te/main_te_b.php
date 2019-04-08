<?php
/*********************************
main_te_b.php
Main Menu for Boys Tennis
Created 7/22/08
Author: Ann Gaffigan
*********************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sport='te_b';
$sportname="Boys Tennis";

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
   $sid=GetSID($session,$sport);
}
else if($school_ch)
{
   $sid=$school_ch;
   $school=GetMainSchoolName($sid,$sport);
}
else
{
   echo "ERROR: No School Selected";
   exit();
}
$school2=ereg_replace("\'","\'",$school);
//Get CLASS 
$sql="SELECT class FROM ".$sport."school WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row[0];

echo $init_html;
echo $header;

if($level==1) 
   echo "<br><a href=\"te_bmain.php?session=$session\" class=small>$sportname STATE SEEDING & BRACKETS Main Menu</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"te_bdistricts.php?session=$session\" class=small>$sportname DISTRICT RESULTS Main Menu</a><br>";
echo "<br><table class=nine width=\"550px\" cellspacing=2 cellpadding=3><caption><b>$sportname Main Menu";
if($level==1) echo " <font style=\"color:red\">for $school</font>";
echo ":</b>";
echo "<hr></caption>";

echo "<tr align=left><td><b>Meet Results in Which You Have Players Listed:</b><br>Please click on a meet to view, edit or add results for that meet.  If you need to enter results for a meet NOT listed already, click \"Enter Meet Results\" below.</td></tr>";
echo "<tr align=center><td><table>";
$sql="SELECT t1.meetname,t1.startdate,t1.enddate,t1.meetsite,t2.* FROM ".$sport."meets AS t1, ".$sport."meetresults AS t2 WHERE (t2.oppid1='$sid' OR t2.oppid2='$sid') AND t1.id=t2.meetid ORDER BY t1.startdate DESC";
$result=mysql_query($sql);
$meetids="";
while($row=mysql_fetch_array($result))
{
   $meetids.=$row[meetid].",";
}
if($meetids!='')
{
$meetids=substr($meetids,0,strlen($meetids)-1);
$meetids=Unique($meetids);
$meetids=split(",",$meetids);
$total=count($meetids);
   echo "<tr align=center><td><table cellspacing=0 cellpadding=3 frames=box rules=all style=\"border:#808080 1px solid;\" class=eight>";
   echo "<tr align=center><td><b>Date</b></td><td><b>Meet & Site</b></td><td colspan=2><b>Last Update & Which School</b></td></tr>";
for($i=0;$i<count($meetids);$i++)
{
   $sql="SELECT * FROM ".$sport."meets WHERE id='$meetids[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr><td align=center>";
   $start=split("-",$row[startdate]);
   $end=split("-",$row[enddate]);
   if($row[startdate]==$row[enddate])
      echo "$start[1]/$start[2]";
   else
      echo "$start[1]/$start[2]-$end[1]/$end[2]";
   echo "</td><td align=left>";
   echo "<a class=small href=\"meetresults_".$sport.".php?school_ch=$school_ch&session=$session&meetid=$row[id]\">$row[meetname] at $row[meetsite]</a></td>";
   $sql="SELECT oppid1,DATE_FORMAT(lastupdate,'%c/%d/%y') as lastupdate FROM ".$sport."meetresults WHERE meetid='$row[id]' ORDER BY lastupdate DESC LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td align=center>$row[lastupdate]</td><td align=left>".GetMainSchoolName($row[oppid1],$sport)."</td>";
   echo "</tr>";
}
   echo "</table></td></tr>";
}//end if no meets
else
   echo "<tr align=center><td>[Your players are NOT listed in any meet results yet.  Please click \"Enter Meet Resuts\" below.]</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td><a href=\"addmeet_".$sport.".php?school_ch=$school_ch&session=$session\">Enter Meet Results</a> <b>(for Meets NOT Listed Above):</b><br>";
echo "If you need to enter results for your players and the meet they participated in is NOT listed above, please click \"Enter Meet Results\" above.</td></tr>";
echo "<tr align=left><td><a href=\"player_".$sport.".php?school_ch=$school_ch&session=$session\">Player Summaries</a><br>";
echo "Click \"Player Summaries\" above to view a listing of results, sorted by division for each player for which results have been entered.</td></tr>";
	/***** AS OF 7/19/11, NO MORE CLASS B DISTRICTS, just STATE like Class A
if($class=="B")	//No State Entry, only District Entry
{
   if($level==1)
      echo "<tr align=left><td><a href=\"edit_".$sport.".php?session=$session&school_ch=$school_ch\">$school's District Entry</a><br>";
   else
      echo "<tr align=left><td><a href=\"edit_".$sport.".php?session=$session\">Your District Entry</a><br>";
   echo "Choose the players you wish to enter at the District Meet.</td></tr>";
   if(PastDue(GetDueDate('te_b'),0) || $secret)
      echo "<tr align=left><td><a href=\"distentries.php?session=$session&sport=$sport\">See ALL Class B District Entries</a></td></tr>";
   else
   {
      $date=split("-",GetDueDate('te_b'));
      echo "<tr align=left><td><b>[Check back after $date[1]/$date[2]/$date[0] to see ALL Class B District Entries]</b></td></tr>";
   }
}
else
{
	******/
   echo "<tr align=left><td><a href=\"state_".$sport.".php?school_ch=$school_ch&session=$session\">Your State Entry</a><br>";
   echo "Choose the players you wish to enter at the State Meet.</td></tr>";
   if(PastDue(GetDueDate('te_bstate'),0) || $secret)
      echo "<tr align=left><td><a href=\"stentries.php?session=$session&sport=$sport\">See ALL Class A State Entries</a></td></tr>";
   else
   { 
      $date=split("-",GetDueDate('te_bstate'));
      echo "<tr align=left><td><b>[Check back after $date[1]/$date[2]/$date[0] to see ALL State Entries]</b></td></tr>";
   }
/****** } ******/
echo "</table>";

echo $end_html;
?>
