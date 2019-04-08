<?php

require $_SERVER['DOCUMENT_ROOT'] . '/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../../functions.php';
require '../variables.php';

$header = GetHeader($session);
$level = GetLevel($session);

//connect to db:
$db = mysql_connect("$db_host", "$db_user", "$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if (!ValidUser($session)) {
    header("Location:../index.php");
    exit();
}
if (!$sport) $sport = 'gog';
$sport = preg_replace("/_/", "", $sport);
$sportname = GetActivityName($sport);
$tourntbl = ($sport == "gog" ? "go_g" : "go_b") . "tourn";
$teamtbl = $sport . "tournteam";
$indytbl = $sport . "tournindy";
$sport_indy=$sport."indy";
if ($level > 1) $school = GetSchool($session);
if ($level > 1) $sid = GetSID($session, $sport);
if ($school == "Test's School") $sid = 1000000;

$htmlheader = "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>$sportname Differential</b><br>as of " . date("F j, Y") . "</b></caption><tr align=center><td><b>Rank</b></td><td><b>Team</b></td><td><b>Average Differential</b></td></tr>";

//GET DATE THESE DIFFERENTIALS WERE LAST PUBLISHED
$sql = "SELECT * FROM godiffsettings WHERE sport='$sport'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$lastpub = $row['lastupdate'];

//GET TEAMS AND CALCULATE  THEIR DIFFERENTIAL AVERAGES
echo $sql = "SELECT DISTINCT t1.sid FROM $teamtbl AS t1,go_gtourn AS t2 WHERE t1.tournid=t2.tid";
$result = mysql_query($sql);
$sids = array();
$diffavgs = array();
$recentupdates = array();
$ix = 0;
$html = "";
$htmlpub = "";
while ($row = mysql_fetch_array($result)) {
    $sid = $row[sid];
    $difftotal = 0;
    $ct = 0;
    echo $sql2 = "SELECT DISTINCT t1.*,t2.sid,t2.score FROM $tourntbl AS t1,$teamtbl AS t2 WHERE t1.tid=t2.tournid AND t2.sid=$sid";
    echo "<br/>";
    $result2 = mysql_query($sql2);
    $recentupdates[$ix] = "";
    while ($row2 = mysql_fetch_array($result2)) {
        echo $tournrating = $row2[courserating] * 4;
        echo "<br/>";
        echo $teamscore = $row2[score];
        echo "<br/>";
        $diff = $teamscore - $tournrating;
        $difftotal += $diff;
        $ct++;
//      if($row2[holes]==18)
//      else $ct+=0.5;
//      if($row2['datesub']>$lastpub)	//THESE RESULTS WERE SUBMITTED SINCE THE LAST PUBLISHED UPDATE
//         $recentupdates[$ix].="<tr align=\"left\"><td>$row2[tournname]</td><td>".date("m/d",strtotime($row2['tourndate']))."</td><td>$row2[tournrating]</td><td>$row2[score]</td><td>$diff</td><td>".date("m/d",$row2['datesub'])." at ".date("g:ia",$row2['datesub'])."</td></tr>";
    }
    $sids[$ix] = $sid;
    if ($ct == 0)
        $diffavgs[$ix] = 0;
    else
        $diffavgs[$ix] = number_format(($difftotal / $ct), 3, '.', '');
    if ($recentupdates[$ix] != '')    //ENCLOSE WITH <table> TAGS
    {
        $recentupdates[$ix] = "<table cellspacing=0 cellpadding=3 frame=\"all\" rules=\"all\" style=\"background-color:#ffffff;border:#808080 1px solid;\"><caption style=\"text-align:left;\"><p><i>Results added since last published update (" . date("m/d/y", $lastpub) . " at " . date("g:ia", $lastpub) . "):</i></p></caption><tr align=\"center\"><td><b>Tournament</b></td><td><b>Date</b></td><td><b>Tourn<br>Rating</b></td><td><b>Score</b></td><td><b>Diff</b></td><td><b>Date Submitted</b></td></tr>" . $recentupdates[$ix] . "</table>";
    }
    $ix++;
}

//SORT BY DIFF AVG (LOWEST FIRST)
array_multisort($diffavgs, SORT_NUMERIC, SORT_ASC, $sids, $recentupdates);

//SHOW RANKINGS
$currank = 0;
$curdiffavg = "";
for ($i = 0; $i < count($diffavgs); $i++) {
    if ($curdiffavg != $diffavgs[$i])    //NEW RANK
    {
        $currank++;
        $curdiffavg = $diffavgs[$i];
    }
    $html .= "<tr align=\"center\"";
    if ($recentupdates[$i] != '') $html .= " bgcolor=\"yellow\"";
    $html .= "><td>$currank</td><td align=left><a href=\"goteamreport.php?session=$session&sport=$sport&sid=$sids[$i]\">" . GetSchoolName($sids[$i], $sport) . "</a>" . $recentupdates[$i] . "</td><td>" . $diffavgs[$i] . "</td></tr>";
    $htmlpub .= "<tr align=center><td>$currank</td><td align=left>" . GetSchoolName($sids[$i], $sport) . "</td><td>" . $diffavgs[$i] . "</td></tr>";
}
$html .= "</table>";
$htmlpub = $htmlheader . $htmlpub . "</table>";

if ($level == 1) {

    $sql = "SELECT * FROM godiffsettings WHERE sport='$sport'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 0)
        $sql = "INSERT INTO godiffsettings (sport,standings,lastupdate) VALUES ('$sport','" . addslashes($htmlpub) . "','" . time() . "')";
    else
        $sql = "UPDATE godiffsettings SET standings='" . addslashes($htmlpub) . "', lastupdate='" . time() . "' WHERE sport='$sport'";
    $result = mysql_query($sql);

//    header("Location: teamrankings.php?sport=$sport");
}

$sql = "SELECT DISTINCT studentid,sid FROM $indytbl";
$student_ids = array();
$result = mysql_query($sql);
$student_differentials = array();
while ($row = mysql_fetch_array($result)) {
    echo $school = GetSchoolName($row[sid], $sport);
    echo "<br/>";
    $player = GetPlayer($row[studentid], $sport, $school);

    $sql1 = "SELECT t1.*,t2.* FROM $indytbl as t1,$tourntbl as t2 WHERE t1.tournid=t2.tid AND t1.studentid=$row[studentid]";
    $result1 = mysql_query($sql1);
    $diff = array();
    while ($row1 = mysql_fetch_array($result1)) {
        $courerating = $row1[courserating];
        $score = $row1[score];
        array_push($diff, ($score - $courerating));
    }
    $average = number_format((array_sum($diff) / count($diff)), 3, '.', '');
    $student_differentials[$average] = array(
        'school' => $school,
        'player' => $player['first'] . ' ' . $player['last'],
        'average'=>$average
    );
}

echo $htmlpub;
echo $end_html;

$htmlheader = "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>$sportname Individual Differential</b><br>as of " . date("F j, Y") . "</b></caption><tr align=center><td><b>Rank</b></td><td><b>Player</b></td><td><b>School</b></td><td><b>Average Differential</b></td></tr>";
$html="";
$scores=array_keys($student_differentials);
sort($scores);
$rank=1;
foreach ($scores as $score){
    $html.="<tr>";
    $student=$student_differentials[$score];
    $html.="<td>$rank</td>";
    $html.="<td>$student[player]</td>";
    $html.="<td>$student[school]</td>";
    $html.="<td>$student[average]</td>";
    $html.="</tr>";
    $rank++;
}
$html.="</table>";
$htmlpub= $htmlheader.$html;
if ($level == 1) {

    $sql = "SELECT * FROM godiffsettings WHERE sport='$sport_indy'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 0)
        $sql = "INSERT INTO godiffsettings (sport,standings,lastupdate) VALUES ('$sport_indy','" . addslashes($htmlpub) . "','" . time() . "')";
    else
        $sql = "UPDATE godiffsettings SET standings='" . addslashes($htmlpub) . "', lastupdate='" . time() . "' WHERE sport='$sport_indy'";
    $result = mysql_query($sql);
    $sport=($sport=="gog")?"go_g":"go_b";
    header("Location: results_main.php?session=$session&school_ch=$school_ch&sport=$sport&done=done");
}

?>
