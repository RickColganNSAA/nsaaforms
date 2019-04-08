<?php
require_once '../../deprecated_functions.php';
?>
<?php
require_once '../../dbfunction.php';
?>
<?php
/********************************
 * This shows the students that the
 * JO directors have submitted as
 * their state participants.
 *********************************/
require '../../calculate/functions.php';
require '../functions.php';
require '../variables.php';
if (isset($_POST) && count($_POST) == 3) {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=entries.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array("First & Last Name", "Grade", "School", "Class"));

    $postKeys = array_keys($_POST);
    $curevent = $_POST[$postKeys[2]];
    $joclasses = GetClasses('jo');
    $sql = "SELECT DISTINCT school FROM jo WHERE (co_op IS NULL OR co_op='') AND (event1!='' OR event2!='') ORDER BY school";
    $result = db_query($sql);
    $ix = 0;
    $sch = array();
    while ($row = db_fetch_array($result)) {
        $sch[$ix] = $row[school];
        $sid[$ix] = GetSID2($row[school], 'jo');
        $schclass[$ix] = GetClass($row[sid],'jo',"",'joschool');
        $ix++;
    }
    for ($c = 0; $c < count($joclasses); $c++) {
        for ($j = 0; $j < count($sch); $j++) {
            if ($schclass[$j] == $joclasses[$c]) {
                $sch2[$j] = nsaa_ereg_replace("\'", "\'", $sch[$j]);
                $sql = "SELECT t1.last, t1.first, t1.semesters,t2.event1,t2.event2,t2.id FROM eligibility AS t1, jo AS t2 WHERE t1.id=t2.student_id AND (t2.school='$sch2[$j]' OR t2.co_op='$sch2[$j]') AND (t2.event1 LIKE '$curevent%' OR t2.event2 LIKE '$curevent%') ORDER BY t1.last";
                $result = db_query($sql);
                while ($row = db_fetch_array($result)) {
                    $array = explode(",", "$row[first] $row[last]," . GetYear($row[semesters]) . "," . ucwords($sch[$j]) . "," . $joclasses[$c]);
                    fputcsv($output, $array);
                }
            }
        }
    }
    exit;
}
$header = GetHeader($session);
$level = GetLevel($session);

//connect to db:
$db = db_connect($db_host, $db_user, $db_pass);
db_select_db($db_name, $db);

//verify user
if (!ValidUser($session)) {
    header("Location:../index");
    exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if (!$school_ch)
    $school = GetSchool($session);
else
    $school = $school_ch;
$school2 = nsaa_ereg_replace("\'", "\'", $school);

$joevents = array();
$sql = "SELECT * FROM jocategories ORDER BY category";
$result = db_query($sql);
$i = 0;
while ($row = db_fetch_array($result)) {
    $joevents[$i] = $row[category];
    $i++;
}

echo $init_html;
echo $header;

if ($missing == 1)    //SHOW SCHOOLS WHO HAVE STUDENTS IN joqualifiers BUT WHO HAVE NOT SUBMITTED THEIR STATE PARTICIPANTS YET
{
    $sql = "SELECT DISTINCT t1.sid FROM joentries AS t1, joqualifiers AS t2 WHERE t1.id=t2.entryid";
    $result = db_query($sql);
    $string = "";
    while ($row = db_fetch_array($result)) {
        $curschool = GetMainSchoolName($row['sid'], 'jo');
        $sql2 = "SELECT * FROM jo WHERE school='" . addslashes($curschool) . "' AND student_id>0 AND event1!=''";
        $result2 = db_query($sql2);
        if (db_num_rows($result2) == 0) {
            $string .= "<li><a href=\"edit_jo.php?session=$session&school_ch=$curschool\" target=\"_blank\">$curschool</a></li>";
        }
    }
    echo "<br><p><a href=\"entries.php?session=$session\">&larr; Return to State Journalism Entries</a></p>";
    if ($string != '') {
        echo "<div style=\"width:500px;\"><h2>Schools who have NOT submitted State Journalism Entries</h2><p style=\"text-align:left;\">These schools have state qualifiers (listed <a href=\"statequalifierlist\" target=\"_blank\" class=\"small\">HERE</a>), but have not yet submitted the students that will be competing at the State Contest.</p>";
        echo "<ul>" . $string . "</ul></div>";
    } else {
        echo "<p><i>There are no schools who have state qualifiers (listed <a href=\"statequalifierlist\" target=\"_blank\" class=\"small\">HERE</a>) that have not yet submitted their students that will be competing at the State Contest.</i></p><p>It appears that all schools with qualifiers have submitted their participants.</p><p><a href=\"entries.php?session=$session\">&larr; Return to State Journalism Entries</a></p>";
    }
    echo $end_html;
    exit();
}

//OTHERWISE, SHOW PARTICIPANTS SUBMITTED SO FAR:
//Get JO Classes
$joclasses = GetClasses('jo');
//get list of schools with jo entries
$sql = "SELECT DISTINCT school FROM jo WHERE (co_op IS NULL OR co_op='') AND (event1!='' OR event2!='') ORDER BY school";
$result = db_query($sql);
$ix = 0;
$sch = array();
while ($row = db_fetch_array($result)) {
    $sch[$ix] = $row[school];
    $sid[$ix] = GetSID2($row[school], 'jo');
    $schclass[$ix] = GetClass($row[sid],'jo',"",'joschool');
    $ix++;
}
//get schools that have ONLY coop entrants (unlikely)
$sql = "SELECT DISTINCT co_op FROM jo WHERE (co_op IS NOT NULL AND co_op!='') AND (event1!='' OR event2!='') ORDER BY co_op";
$result = db_query($sql);
while ($row = db_fetch_array($result)) {
    $found = 0;
    for ($j = 0; $j < count($sch); $j++) {
        if ($sch[$j] == $row[co_op])
            $found = 1;
    }
    if ($found != 1) {
        $sch[$ix] = $row[co_op];
        $ix++;
    }
}
sort($sch);

if ($resetdata == 1) {
    $sql = "DELETE FROM jo";
    $result = db_query($sql);
    header("Location:entries.php?session=$session&resetted=1");
}

$date = date("M d, Y", time());
echo "<form method=post action=\"entries.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<center><br><br><table style=\"width:100%;\"><tr align=center><td>";
echo "<h2>STATE JOURNALISM ENTRIES (as of $date):</h2>";
if ($resetted == 1)
    echo "<div class=\"alert\"><p>The State Journalism entry data has been cleared out.</p></div>";
/*** TIME TO ARCHIVE? ***/
$sql = "SELECT t1.last, t1.first, t1.semesters,t2.event1,t2.event2,t2.id FROM eligibility AS t1, jo AS t2 WHERE t1.id=t2.student_id";
$result = db_query($sql);
$connected = db_num_rows($result);
$sql = "SELECT * FROM jo";
$result = db_query($sql);
$totalentries = db_num_rows($result);
if ($totalentries > $connected)    //These entries are probably from last year, and the eligibility table has been archived
{
    $year2 = date("Y");
    if (date("m") < 6) $year2--;
    $year1 = $year2 - 1;
    echo "<div class=\"alert\"><p>You may not see any information below because the eligibility lists have been archived for $year1-$year2.</p>";
    echo "<ul><li><a href=\"entries.php?session=$session&resetdata=1\" onClick=\"return confirm('Are you sure you want to clear out the state entries?');\">Clear out State Journalism entry data</a></li></ul>";
    echo "</div>";
}
echo "<div class=\"alert\"><p>You may not see any information below because the eligibility lists have been archived for $year1-$year2.</p>";
echo "<ul><li><a href=\"entries.php?session=$session&resetdata=1\" onClick=\"return confirm('Are you sure you want to clear out the state entries?');\">Clear out State Journalism entry data</a></li></ul>";
echo "</div>";
/*** END TIME TO ARCHIVE ***/
echo "<p><a class=\"small\" href=\"entries.php?session=$session&missing=1\">View Schools MISSING State Journalism Entries &rarr;</a></p><br>";

echo "<table class=\"nine\" cellspacing=0 cellpadding=5 style=\"width:800px;\">";
echo "<caption><select name=\"joevent\" onChange=\"submit();\"><option value=''>All Categories (No Certificate Links)</option>";
for ($i = 0; $i < count($joevents); $i++) {
    echo "<option value=\"$joevents[$i]\"";
    if ($joevent == $joevents[$i]) echo " selected";
    echo ">$joevents[$i]</option>";
}
echo "</select> <input type=submit name=\"go\" value=\"Go\"><br><br></caption>";

//show jo entries organized by event, school
for ($i = 0; $i < count($joevents); $i++) {
    $curevent = $joevents[$i];
    if (!$joevent || $joevent == '' || $curevent == $joevent) {

        echo "<tr align=left><th colspan=2 align=left>$curevent: <button name='export_$i' value='$curevent'>Export</button></th></tr><tr align=\"left\" valign=\"top\">";
//        @$session_start();
        for ($c = 0; $c < count($joclasses); $c++) {
            echo "<td><b>CLASS $joclasses[$c]:</b><br><br>";
            //go through schools in alpha order and get students
            for ($j = 0; $j < count($sch); $j++) {
                if ($schclass[$j] == $joclasses[$c]) {
                    $sch2[$j] = nsaa_ereg_replace("\'", "\'", $sch[$j]);
                    $sql = "SELECT t1.last, t1.first, t1.semesters,t2.event1,t2.event2,t2.id FROM eligibility AS t1, jo AS t2 WHERE t1.id=t2.student_id AND (t2.school='$sch2[$j]' OR t2.co_op='$sch2[$j]') AND (t2.event1 LIKE '$curevent%' OR t2.event2 LIKE '$curevent%') ORDER BY t1.last";
                    $result = db_query($sql);
                    while ($row = db_fetch_array($result)) {
                        echo "<p>$row[first] $row[last],(" . GetYear($row[semesters]) . "),$sch[$j]";
//	       echo "<p>$row[first] $row[last], ,$sch[$j] (".GetYear($row[semesters]).")";
                        if ($curevent == "News/Feature Photography") {
                            if (nsaa_ereg("News/Feature", $row[event1])) {
                                $temp = nsaa_split(",", $row[event1]);
                                echo "&nbsp;&nbsp;($temp[1]; $temp[2])";
                            } else {
                                $temp = nsaa_split(",", $row[event2]);
                                echo "&nbsp;&nbsp;($temp[1]; $temp[2])";
                            }
                        }
                        if ($joevent && $joevent != '')
                            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"createcertificate?session=$session&state=1&event=$curevent&entryid=$row[id]\" target=\"_blank\" class=\"small\">Generate State Qualifier Certificate (PDF) &rarr;</a>";
                        echo "</p>";
                    }
                } //END IF CORRECT CLASS
            }//END FOR EACH SCHOOL
            echo "</td>";
        }//END FOR EACH CLASS
        echo "</tr>";
        if ($joevent == '' || !$joevent) echo "<tr align=center><td colspan=2><hr></td></tr>";
    }
}
echo "</table>";
echo "<br><br><br><a href=\"../welcome?session=$session\">Home</a>";

echo "</td></tr></table></form>" . $end_html;
?>
