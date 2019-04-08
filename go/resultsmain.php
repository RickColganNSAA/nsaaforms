<?php
/******************************************
resultsmain.php
Regular Season Golf Tournament Results 
MAIN MENU FOR NSAA
Created 7/17/12
Author Ann Gaffigan
 ********************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
    header("Location:../index.php");
    exit();
}
if(!$sport) $sport='gog';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=($sport=="gog"?"go_g":"go_b")."tourn";
$teamtbl=$sport."tournteam";
$indytbl=$sport."tournindy";
$schooltbl=($sport=="gog"?"go_g":"go_b")."school";
if($level>1) $school=GetSchool($session);
if($level>1) $sid=GetSID($session,$sport);
if($school=="Test's School") $sid=1000000;

echo $init_html.$header."<br>";

$sport2=substr($sport,0,2)."_".substr($sport,2,1);

if($removetournid && ($sid || $level>1))
{
    $sql="SELECT * FROM $tourntbl WHERE id='$removetournid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $sql="DELETE FROM $teamtbl WHERE sid='$sid' AND tournid='$removetournid'";
    $result=mysql_query($sql);
    $sql="DELETE FROM $indytbl WHERE sid='$sid' AND tournid='$removetournid'";
    $result=mysql_query($sql);
    echo "<div class=\"alert\"><p>".GetSchoolName($sid,$sport)." has been removed from $row[name].</p></div>";
}

if($level>1)
{
    echo "<br>".GetGolfSeasonReportDash($sport,$session,$sid);

    echo "<p style=\"border-top:#808080 1px dotted;margin-top:20px;padding:10px;;\"><a href=\"teamrankings.php?sport=$sport\" target=\"_blank\">".GetActivityName($sport)." Differential</a></p>";
}
else if($save && $level>1)
{
    $sql="UPDATE form_duedates SET viewstandings='$viewstandings' WHERE form='$sport2'";
    $result=mysql_query($sql);
}

//SEE IF AD CAN SEE THE STANDINGS RIGHT NOW (NSAA HAS TO APPROVE THIS)
$sql="SELECT * FROM form_duedates WHERE form='$sport2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$viewstandings=$row[viewstandings];

$htmlheader="<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>$sportname Differential</b><br>as of ".date("F j, Y")."</b></caption><tr align=center><td><b>Rank</b></td><td><b>Team</b></td><td><b>Average Differential</b></td></tr>";

//GET DATE THESE DIFFERENTIALS WERE LAST PUBLISHED
$sql="SELECT * FROM godiffsettings WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$lastpub=$row['lastupdate'];

//GET TEAMS AND CALCULATE  THEIR DIFFERENTIAL AVERAGES
$sql="SELECT DISTINCT t1.sid FROM $teamtbl AS t1,$tourntbl AS t2,$schooltbl AS t3 WHERE t1.tournid=t2.tid AND t3.sid=t1.sid AND t3.class='A'";
$result=mysql_query($sql);
$sids=array(); $diffavgs=array(); $recentupdates=array(); $ix=0;
$html=""; $htmlpub="";
while($row=mysql_fetch_array($result))
{
    $sid=$row[sid]; $difftotal=array(); $ct=0;
    $sql2="SELECT DISTINCT t1.*,t2.sid,t2.score FROM $tourntbl AS t1,$teamtbl AS t2 WHERE t1.tid=t2.tournid AND t1.datesub>0 AND t2.sid='$sid' AND t1.noscores!='x' AND t1.norating!='x'";

    $result2=mysql_query($sql2);
    $recentupdates[$ix]="";
    while($row2=mysql_fetch_array($result2))
    {
        $tournrating=$row2[tournrating];
        $teamscore=$row2[score];
        if($teamscore==0){
            $diff=0;
        }else{
            $diff=$teamscore-$tournrating;
        }
        if($diff!=0){
            array_push($difftotal,$diff);
        }
        if($row2[holes]==18) $ct++;
        else $ct+=0.5;
        if($row2['datesub']>$lastpub)	//THESE RESULTS WERE SUBMITTED SINCE THE LAST PUBLISHED UPDATE
            $recentupdates[$ix].="<tr align=\"left\"><td>$row2[name]</td><td>".date("m/d",strtotime($row2['tourndate']))."</td><td>$row2[tournrating]</td><td>$row2[score]</td><td>$diff</td><td>".date("m/d",$row2['datesub'])." at ".date("g:ia",$row2['datesub'])."</td></tr>";
    }
    $sids[$ix]=$sid;
    if($ct==0)
        $diffavgs[$ix]=0;
    else
        $diffavgs[$ix]=number_format((array_sum($difftotal)/count($difftotal)),3,'.','');
    if($recentupdates[$ix]!='')	//ENCLOSE WITH <table> TAGS
    {
        $recentupdates[$ix]="<table cellspacing=0 cellpadding=3 frame=\"all\" rules=\"all\" style=\"background-color:#ffffff;border:#808080 1px solid;\"><caption style=\"text-align:left;\"><p><i>Results added since last published update (".date("m/d/y",$lastpub)." at ".date("g:ia",$lastpub)."):</i></p></caption><tr align=\"center\"><td><b>Tournament</b></td><td><b>Date</b></td><td><b>Tourn<br>Rating</b></td><td><b>Score</b></td><td><b>Diff</b></td><td><b>Date Submitted</b></td></tr>".$recentupdates[$ix]."</table>";
    }
    $ix++;
}

//SORT BY DIFF AVG (LOWEST FIRST)
array_multisort($diffavgs,SORT_NUMERIC,SORT_ASC,$sids,$recentupdates);

//SHOW RANKINGS
$currank=0; $curdiffavg="";
for($i=0;$i<count($diffavgs);$i++)
{
    if($curdiffavg!=$diffavgs[$i])	//NEW RANK
    {
        $curdiffavg=$diffavgs[$i];
    }
    if ($diffavgs[$i]>0){
        $currank++;
        $html.="<tr align=\"center\"";
        if($recentupdates[$i]!='') $html.=" bgcolor=\"yellow\"";
        $html.="><td>$currank</td><td align=left><a href=\"goteamreport.php?session=$session&sport=$sport&sid=$sids[$i]\">".GetSchoolName($sids[$i],$sport)."</a>".$recentupdates[$i]."</td><td>".$diffavgs[$i]."</td></tr>";
        $htmlpub.="<tr align=center><td>$currank</td><td align=left>".GetSchoolName($sids[$i],$sport)."</td><td>".$diffavgs[$i]."</td></tr>";
    }
}
$html.="</table>";
$htmlpub=$htmlheader.$htmlpub."</table>";

if($level==1) {
    if ($publish)    //WRITE HTMLPUB TO DATABASE
    {
        $sql = "SELECT * FROM godiffsettings WHERE sport='$sport'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) == 0)
            $sql = "INSERT INTO godiffsettings (sport,standings,lastupdate) VALUES ('$sport','" . addslashes($htmlpub) . "','" . time() . "')";
        else
            $sql = "UPDATE godiffsettings SET standings='" . addslashes($htmlpub) . "', lastupdate='" . time() . "' WHERE sport='$sport'";
        $result = mysql_query($sql);
    }
    echo "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>$sportname Differential</b><br>as of " . date("F j, Y") . "</b>";
    echo "<form method='post' action='resultsmain.php'><input type=hidden name='session' value='$session'><input type=hidden name='sport' value='$sport'>";
    /*
   echo "<div class='alert' style='width:400px;'><h3>School Access:</h3><p><input type=checkbox name='viewstandings' value='x'";
   if($viewstandings=='x') echo " checked";
   echo "> Check this box to show the table below to AD's who are logged in and looking at their Tournament Report page.</p><input type=submit name='save' value='Save Checkmark'>";
   if($save) echo " <i>The checkmark has been saved.</i>";
   echo "</div><br>";
    */
    echo "<div class='alert' style='width:400px;'><h2>Public Access:</h2>";
    echo "<ul><li><a href=\"goweeklyresults.php?sport=$sport\" target=\"_blank\">Link for Public Weekly Results</a></li></ul>";
    echo "<h3>TEAM RANKINGS:</h3>";
    //GET STANDINGS MOST RECENTLY PUBLISHED FOR THE PUBLIC, IF ANY:
    $sql = "SELECT * FROM godiffsettings WHERE sport='$sport'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if (mysql_num_rows($result) == 0 || $row[lastupdate] == 0)
        echo "<p>The team rankings have not yet been published to the public website.</p>";
    else
        echo "<p>The team rankings were last updated on the public website on <b><u>" . date("F j, Y", $row[lastupdate]) . " at " . date("g:ia T", $row[lastupdate]) . "</u></b>.</p><ul><li><a href=\"teamrankings.php?sport=$sport\" target=\"_blank\">View Team Rankings on Public Website</a></li><li><a href=\"indyranings.php?sport=$sport\" target=\"_blank\">View Team Individual on Public Website</a></li></ul>";
    echo "<p><i>Click the button below to publish the latest team rankings to the NSAA website.</i></p><input type=\"submit\" name=\"publish\" value=\"Publish Updated Team Rankings to the Website\">";
    echo "<br/><i>Click the button below to publish the latest team individual to the NSAA website.</i><br/><input type=\"submit\" name=\"publish_individual\" value=\"Publish Updated Individual Rankings to the Website\"></div>";
    echo "</form></caption>";
    echo "<tr align=center><td><b>Rank</b></td><td><b>Team</b></td><td><b>Average Differential</b></td></tr>";
    echo $html;
    $htmlheader = "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>$sportname Individual Differential</b><br>as of " . date("F j, Y") . "</b></caption><tr align=center><td><b>Rank</b></td><td><b>Player</b></td><td><b>School</b></td><td><b>Average Differential</b></td></tr>";
    $html="";
    $sql = "SELECT sid,studentid,AVG(score-$tourntbl.courserating) as average FROM $indytbl,$tourntbl WHERE $indytbl.tournid=$tourntbl.tid GROUP BY studentid ORDER BY average";
    $result = mysql_query($sql);
    $rank=1;
    while ($row = mysql_fetch_array($result)) {

        $school = GetSchoolName($row[sid], $sport);
        $mainschool=GetMainSchoolName($row[sid],$sport);
        $player = GetPlayer($row[studentid], $sport, $mainschool);

        $html.="<tr>";
        $student=$student_differentials[$score];
        $html.="<td>$rank</td>";
        $html.="<td>$player[first] $player[last]</td>";
        $html.="<td>$school</td>";
        $html.="<td>".number_format($row[average],3,'.','')."</td>";
        $html.="</tr>";
        $rank++;

    }

    $html.="</table>";
    echo $htmlpub= $htmlheader.$html;
    if ($level == 1) {
        $sport_indy=$sport."indy";
        $sql = "SELECT * FROM godiffsettings WHERE sport='$sport_indy'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) == 0)
            $sql = "INSERT INTO godiffsettings (sport,standings,lastupdate) VALUES ('$sport_indy','" . addslashes($htmlpub) . "','" . time() . "')";
        else
            $sql = "UPDATE godiffsettings SET standings='" . addslashes($htmlpub) . "', lastupdate='" . time() . "' WHERE sport='$sport_indy'";
        $result = mysql_query($sql);
        $sport=($sport=="gog")?"go_g":"go_b";
    }
}
echo $end_html;

?>
