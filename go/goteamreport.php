<?php
/******************************************
goteamreport.php
Results/Details for a single team
Created 7/18/12
Author Ann Gaffigan
 ********************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$level=GetLevel($session);
if(!$session || !ValidUser($session) || $level!=1)
{
    $level=0; $session="";
    echo $init_html."<table width=\"100%\"><tr align=center><td>
		<br><a href=\"goweeklyresults.php?sport=$sport\">Go to ".GetActivityName($sport)." Weekly Results</a>
		<form method=\"post\" action=\"goteamreport.php\">
		<input type=\"hidden\" name=\"sport\" value=\"$sport\">
		<br /><h1>".GetActivityName($sport)." Schedules & Results:</h1>
		<h2>Select a School: <select name=\"sid\" onChange=\"submit();\"><option value=\"0\">Select School</option>";
    $sql2="SELECT * FROM ".GetSchoolsTable($sport)." ORDER BY school";
    $result2=mysql_query($sql2);
    while($row2=mysql_fetch_array($result2))
    {
        echo "<option value=\"$row2[sid]\"";
        if($sid==$row2[sid]) echo " selected";
        echo ">$row2[school]</option>";
    }
    echo "</select> <input type=\"submit\" name=\"go\" value=\"Go\"></form><br />";
}
else
{
    echo $init_html.GetHeader($session);
}

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=($sport=="gog"?"go_g":"go_b")."tourn";
$teamtbl=$sport."tournteam";
$indytbl=$sport."tournindy";

if(!$sid)
{
    echo "<br><br><div class='error' style=\"width:300px;text-align:center;\">ERROR: No School selected.</div>";
    if($level==1)
        echo "<br><br><a href=\"resultsmain.php?session=$session&sport=$sport\">Return to $sportname Season Reports & Differential</a>";
    echo $end_html;
    exit();
}

$school=GetSchoolName($sid,$sport);

if($level==1)
    echo "<br><a href=\"resultsmain.php?session=$session&sport=$sport\">Return to $sportname Season Reports & Differential</a><br><br>";

echo "<br /><table class='nine' cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>$sportname Season Report and Team Differential for <label style=\"background-color:yellow;\">$school</label>:</b><br><br>(Click tournament name for full report.)<br></caption>";
echo "<tr align=center><td><b>Date</b></td><td><b>Tournament</b></td><td><b>Course</b></td><td><b>Holes</b></td><td><b>Team Score</b></td><td><b>Tournament Rating</b></td><td><b>Differential</b></td></tr>";
//SHOW SUMMARY OF EACH RESULT ENTERED: Tournament, Course, Team Score, Tourn Rating, Differential, Diff Total, Diff Average
$difftotal=array(); $ct=0;
$sql2="SELECT DISTINCT t1.*,t2.sid,t2.score FROM $tourntbl AS t1,$teamtbl AS t2 WHERE t1.tid=t2.tournid AND t1.datesub>0 AND t2.sid='$sid'";
$result2=mysql_query($sql2);
$no_diff=1;
while($row2=mysql_fetch_array($result2))
{
    $date=explode("-",$row2[tourndate]);
    echo "<tr align=center><td>$date[1]/$date[2]/$date[0]</td>";
    if(trim($row2[name])=="") $row2[name]="[No Tournament Name Indicated]";
    if($level==1)
        echo "<td align=left><a href=\"gotournresults.php?sport=$sport&sid=$sid&session=$session&tournid=$row2[tid]\">$row2[name]</a></td>";
    else
        echo "<td align=left><a href=\"goliveresults.php?sport=$sport&sid=$sid&session=$session&tournid=$row2[tid]\">$row2[name]</a></td>";
    echo "<td align=left>$row2[course]";
    if($row2[holes]==9 && $row2[hole9name]!='')
    {
        if($row2[hole9name]=="Front" || $row2[hole9name]=="Back")
            echo "<br>$row2[hole9name] 9 Holes";
        else echo "<br>$row2[hole9name]";
    }
    echo "</td><td>$row2[holes]</td><td>$row2[score]</td>";
    if($row2['norating']=='x' || $row2['noscores']=='x')
    {
        if($row2['norating']=='x')
            echo "<td>(Course not rated)</td>";
        else echo "<td>(Scores not reported due to format)</td>";
        echo "<td>N/A</td>";
        $diff=0;
    }
    else
    {
        echo "<td>$row2[tournrating]</td>";
        $tournrating=$row2[tournrating];
        $teamscore=$row2[score];
        if($teamscore==0){
            $diff=0;
        }else{
            $diff=$teamscore-$tournrating;
        }
        echo "<td>$diff</td>";
        if($diff!=0){
            array_push($difftotal,$diff);
        }
        if($row2[holes]==18) $ct++;
        else $ct+=0.5;
    }
    echo "</tr>";
}
if($ct==0 || $no_diff==0){
    $diffavg=0;
    $difftotal=0;
}
else
    $diffavg=number_format((array_sum($difftotal)/count($difftotal)),2,'.','');
echo "<tr><td colspan=6 align=right><b>Differential Total:</b></td><td align=center>".array_sum($difftotal)."</td></tr>";
echo "<tr><td colspan=6 align=right><b>Differential Average (18-hole based):</b></td><td align=center>$diffavg</td></tr>";

echo "</table>";
if($level==1)
    echo "<br><a href=\"resultsmain.php?session=$session&sport=$sport\">Return to $sportname Season Reports & Differential</a>";
echo $end_html;
?>
