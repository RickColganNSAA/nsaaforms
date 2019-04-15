<?php
/*********************************************
showdistresults.php
Public version of PP District Results
for NSAA Website once approved by director
Created 10/6/14
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$distid)
{
   echo $init_html;
   echo "<br><br><div class=\"error\">ERROR: No District Selected.</div><br><br>";
   echo $end_html;
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

$sql="SELECT * FROM $db_name2.ppdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$dist=mysql_fetch_array($result);

if($dist[showresults]!='x' && !ValidUser($session))
{
   //ACCESS DENIED FOR NON-LOGGED-IN USER
   echo "<br><br><div class=\"alert\">Results for <b>District $dist[class]-$dist[district]</b> are not available at this time.</div><br><br>";
   echo $end_html;
   exit();
}
else if($dist[showresults]!='x' && ValidUser($session))
{
   echo "<div class=\"alert\" style=\"width:600px;\"><p><b>NOTE:</b> You are previewing the District $dist[class]-$dist[district] results. <b><u>These results are not yet available to the public. </b></u>At the bottom of the page on which you enter each judge's rank and points, you must check the box to approve the results and click Save in order for the public to view them on the NSAA website.</p></div><br>";
}
else if(ValidUser($session))
{
   echo "<div class=\"alert\" style=\"width:600px;\"><p>You have approved these results for the NSAA website.</p></div><br>";
}
$distid=$dist[id];
$sids=split(",",$dist[sids]);
$ppschs[sid]=array(); $ppschs[school]=array();
for($i=0;$i<count($sids);$i++)
{
   $ppschs[sid][$i]=trim($sids[$i]);
   $ppschs[team][$i]=GetSchoolName($ppschs[sid][$i],'pp');
   $ppschs[school][$i]=GetMainSchoolName($ppschs[sid][$i],'pp');
   $sql="SELECT * FROM ppdistresults WHERE school='".addslashes($ppschs[school][$i])."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[place]==0) $ppschs[place][$i]=100;
   else $ppschs[place][$i]=$row[place];
}
array_multisort($ppschs[place],SORT_ASC,SORT_NUMERIC,$ppschs[school],SORT_STRING,SORT_ASC,$ppschs[team],$ppschs[sid]);

echo "<br><h2>Play Production District $dist[class]-$dist[district] Results</h2>";

echo "<table class=\"nine\" cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<tr align=center><td rowspan=2><b>Play/School</b></td><td colspan=2><b>Judge 1</b></td><td colspan=2><b>Judge 2</b></td><td colspan=2><b>Judge 3</b></td><td colspan=2><b>Grand Total</b></td><td rowspan=2><b>PLACE</b></td><td rowspan=2><b>Reciprocal</b></td></tr>";
echo "<tr align=center><td><b>Rank</b></td><td><b>Points</b></td><td><b>Rank</b></td><td><b>Points</b></td><td><b>Rank</b></td><td><b>Points</b></td><td><b>Rank</b></td><td><b>Points</b></td></tr>";
for($i=0;$i<count($ppschs[school]);$i++)
{
   $sql="SELECT * FROM pp WHERE school='".addslashes($ppschs[school][$i])."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) $title="[No District Entry Submitted]";
   else $title="\"$row[title]\"";
   echo "<tr align=left><td>".$ppschs[school][$i]."<br>$title";
   $sql="SELECT * FROM ppdistresults WHERE distid='$distid' AND school='".addslashes($ppschs[school][$i])."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   for($j=1;$j<=3;$j++)
   {
      $rankvar="rank".$j; $pointsvar="points".$j;
      echo "<td>".$row[$rankvar]."</td>
	<td>".$row[$pointsvar]."</td>";
   }
   echo "<td bgcolor='#f0f0f0'>$row[totalrank]</td>
	<td bgcolor='#f0f0f0'>$row[totalpoints]</td>
	<td bgcolor='#d0d0d0'><b>$row[place]</b>";
	if($row[tiebreaker]>0) echo " (JP)";
	echo "</td>
	<td bgcolor='#f0f0f0'>$row[reciprocal]</td></tr>";
}
echo "</table>";

echo $end_html;

?>
