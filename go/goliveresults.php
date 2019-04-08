<?php
/******************************************
gotournresults.php
Regular Season Golf Tournament Results for the PUBLIC
Created 3/17/16
Author Ann Gaffigan
********************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=$sport."tourn";
$teamtbl=$tourntbl."team";
$indytbl=$tourntbl."indy";

$sql="SELECT *,YEARWEEK(tourndate) AS week FROM $tourntbl WHERE id='$tournid'";
$result=mysql_query($sql);
if(!$tournid || mysql_num_rows($result)==0)
{
   echo $init_html."<table width=\"100%\"><tr align=center><td><br><br><div class='error'>ERROR: No Tournament Selected.</div></td></tr></table>";
   echo $end_html; exit();
}
$tourn=mysql_fetch_array($result);
$headschool=GetSchool2($tourn['schoolid']); 
$headsid=GetSID2($headschool,$sport);
$weschool=$headschool; $isare="is";

//SHOW RESULTS:
echo $init_html."<table width=\"100%\"><tr align=center><td><a name=\"top\"><br></a>";

//NAVIGATE TO SEASON REPORT & WEEKLY RESULTS
if($sid)
   echo "<a href=\"goteamreport.php?sport=$sport&sid=$sid\">&larr; Return to ".GetSchoolName($sid,$sport)." Season Report</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a href=\"goweeklyresults.php?sport=$sport&week=$tourn[week]\">Weekly $sportname Results</a>";

//SHOW TOURNAMENT REPORT
echo "<h2>$sportname Tournament Report for <u>$tourn[tournname]</u> on <u>".date("m/d/Y",strtotime($tourn['tourndate']))."</u>:</h2>";

echo "<table class=\"nine\" style=\"width:850px;\" cellspacing=0 cellpadding=5>";
if($tourn['datesub']>0)	//SUBMITTED
{
   $submitted=1;
   echo "<br><p>This report was submitted by ".GetCoaches($tourn['schoolid'],$sport)." (".GetSchool2($tourn['schoolid'])."), Tournament Director, on ".date("F j, Y",$tourn[datesub]).".</p>";
}
else if(PastDue($tourn['tourndate'],-1))	//Results not submitted yet; this school is not the host
{
   echo "<div class='alert'><p><b>".GetSchool2($tourn['schoolid'])."</b> has not yet submitted this tournament report.</p></div>";
}
echo "</caption>";

/*** TOURNAMENT INFO (go_tourn) ***/
echo "<tr align=center><td><br><table cellspacing=0 cellpadding=6 class=\"nine\">";

echo "<tr align=left><td><b>Tournament Date:</b></td><td>".date("F j, Y",strtotime($tourn['tourndate']))."</td></tr>";
if($tourn['postponed']=='x')
{
   echo "<tr align=left><td>&nbsp;</td><td>This tournament was POSTPONED.";
   if($tourn['origdate']>'0000-00-00') echo " (originally scheduled for ".date("F j, Y",strtotime($tourn['origdate'])).".)";
   echo "</td></tr>";
}
else if($tourn['canceled']=='x')
   echo "<tr align=left><td>&nbsp;</td><td>This tournament was <label style=\"background-color:red;color:white;\">CANCELED</label></td></tr>";

echo "<tr align=left><td><b>Tournament Host:</b></td><td>";
		if($tourn[hostschool]=="schoolid") echo GetSchool2($tourn[schoolid]);
		else if($tourn[hostschool]=="nonclassA") echo "a NON-CLASS A Nebraska School";
		if($tourn[hostschool]=="outofstate") echo "an OUT-OF-STATE School";
		echo "</td></tr>";
	echo "<tr align=left><td><b>Tournament Title:</b></td><td>$tourn[tournname]</td></tr>";
	echo "<tr align=left><td><b>Course:</b></td><td>$tourn[course]</td></tr>";
	echo "<tr align=left><td><b>Holes:</b></td><td>$tourn[holes]";
        if($tourn['holes']==9) echo " ($tourn[hole9name])";
        echo "</td></tr>";
	echo "</td></tr>";
   if($tourn['norating']=='x')	//COURSE NOT RATED
      echo "<tr align=\"left\"><td><b>Course Rating:</b><td>Course Not Rated.</td></tr>";
   else
   {
	echo "<tr align=left><td><b>Course Rating:</b></td><td>$tourn[courserating]";
	echo " (18-Hole Course Rating for tee boxes used)</td></tr>";
	echo "<tr align=left><td><b>Tournament Rating:</b></td><td>$tourn[tournrating]</td></tr>";
   }
   if($tourn['noscores']=='x') echo "<tr align=left><th colspan=2><i>Scores not reported due to format.</i></th></tr>";
   echo "</table>";

   $sql="SELECT DISTINCT t1.sid,t2.score FROM $indytbl AS t1,$teamtbl AS t2 WHERE t1.sid=t2.sid AND t2.tournid='$tournid'";
   //if($curusersid!=$headsid && $level!=1)	//SCHOOL THAT IS NOT THE HEAD SCHOOL THAT ENTERED THE RESULTS - ONLY SHOW THEM THEIR TEAM'S RESULTS
     // $sql.=" AND t1.sid='$curusersid'";
   $sql.=" AND t2.score >0 ORDER BY t2.score ASC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0) 
      echo "<br><br><b>Individual <label style='color:#ff0000;'>VARSITY</label> Results:</b><br>";
   while($row=mysql_fetch_array($result))
   {
        echo "<br><table cellspacing=0 cellpadding=3 class=\"nine\" frame=all rules=all style=\"border:#808080 1px solid;\">";
        echo "<caption>Individual <label style='color:#ff0000;'>VARSITY</label> Results for<br><a href=\"goteamreport.php?sport=$sport&sid=$row[sid]\">".GetSchoolName($row[sid],$sport)."</a>";
	if(!$submitted && $schoolid==$tourn['schoolid'])
		echo "&nbsp;<input type=button name=\"Edit\" value=\"Edit Results\" onClick=\"window.open('goindyresults.php?session=".$session."&tournid=".$tournid."&reportsid=".$sid."&sid=".$row[sid]."&sport=".$sport."','Individual_Results','width=550,height=500,location=no');\">";
	echo "</caption>";
        //NOTE: Need way to delete results for a team
        echo "<tr align=center><td>Player</td><td>9 or 18 Meet Score</td></tr>";
        $sql2="SELECT * FROM $indytbl WHERE tournid='$tournid' AND sid='$row[sid]' ORDER BY score";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
                echo "<tr align=center><td>".GetStudentInfo($row2[studentid])."</td><td>$row2[score]</td></tr>";
        }
        //TEAM SCORE
        echo "<tr align=center><td colspan=2>Team Score: <b>$row[score]</b> (Low four scores)</td></tr>";
        echo "</table>";
   }
   //TEAM RESULTS
   if(mysql_num_rows($result)>0)
   {
      echo "<br><table cellspacing=0 cellpadding=3 class=\"nine\" frame=all rules=all style=\"border:#808080 1px solid;\">";
      echo "<caption><b>Varsity Team Results:</b></caption>";
      echo "<tr align=center><td>Team</td><td>Team Score</td><td>Tournament<br>Rating</td><td>Differential</td></tr>";
      $sql="SELECT * FROM $teamtbl WHERE tournid='$tournid' ORDER BY score";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<tr align=center><td align=left><a href=\"goteamreport.php?sport=$sport&sid=$row[sid]\">".GetSchoolName($row[sid],$sport)."</a></td><td>$row[score]</td><td>$tourn[tournrating]</td>";
         $diff=$row[score]-$tourn[tournrating];
         echo "<td>$diff</td></tr>";
      }
      echo "</table>";
   }
else echo "</table>";
echo "</td></tr>";

echo "</table>";
echo $end_html;
?>
