<?php
/*******************************************
stateadmin.php
Main Landing Page for NSAA User
Created 11/19/12
Author: Ann Gaffigan
 *******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
    header("Location:index.php?error=1");
    exit();
}

if($savedates)
{
    $prelim="$prelimy-$prelimm-$prelimd";
    $sql="UPDATE form_duedates SET duedate='$prelim' WHERE form='jo_contest'";
    $result=mysql_query($sql);
    $state="$statey-$statem-$stated";
    $sql="UPDATE form_duedates SET duedate='$state' WHERE form='jo'";
    $result=mysql_query($sql);
    $judge="$judgey-$judgem-$judged";
    $sql="UPDATE josettings SET judgeduedate='$judge'";
    $result=mysql_query($sql);
}
else if($deletetable=='joqualifiers' || $deletetable=='joentries' || $deletetable=='joassignments')
{
    $sql="DELETE FROM $deletetable";
    $result=mysql_query($sql);
}

echo $init_html;
echo $header;

echo "<br><h2>PRELIMINARY CONTEST ADMIN</h2>";
echo "<form method=post action=\"stateadmin.php#settings\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<table style=\"width:600px;\" class='nine'><tr align=left><td>";
echo "<ul>";
echo "<li><a href='preliminarycategories.php?session=$session'>Manage prelim Categories</a><p>Insert/Update and Delete the preliminary categories</p></li>";
echo "<li><a href=\"stateentries.php?session=$session\">View/Edit School SUBMISSIONS</a><p>Make changes, if necessary, to entries submitted by schools. Also, lookup specific submissions by school and event.</p></li>";
echo "<li><a href=\"judges.php?session=$session\">Manage Journalism JUDGES</a><p>Create judge accounts and assign categories for each judge to submit rankings for.</p></li>";
echo "<li><a href=\"previewresults.php?session=$session\">Review & Approve PRELIM Results for NSAA WEBSITE</a><p>Review information that will appear on the public NSAA website; approve for publishing.</p></li>";
echo "<li><a href=\"statequalifiers.php?session=$session\">Manage STATE QUALIFIERS</a><p>Finalize the list of State Qualifiers for each event.</p>";
echo "<hr></ul></td></tr></table>";

//SETTINGS & RESET FOR NEW YEAR
echo "<a name='settings'>&nbsp;</a>";
echo "<h3 style=\"text-align:center;\">Reset for the New Year & Settings for Journalism Prelims/State:</h3><div style=\"width:500px;text-align:left;\">";
//ENTRIES
$sql="SELECT * FROM joentries where filename!='' or filename2!=''";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo "<p><b>ENTRIES:</b></p><p>";
if($ct==1) echo "There is 1 Journalism entry submission in the database.";
else echo "There are $ct Journalism entry submissions in the database.";
echo "</p>";
if($ct>0)
    echo "<p><a class=\"small\" href=\"stateadmin.php?session=$session&deletetable=joentries\" onClick=\"return confirm('Are you sure you wish to remove the entries from the database?');\">Remove all submissions to reset for the new year</a></p>";
//JUDGE ASSIGNMENTS
$sql="SELECT * FROM joassignments";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo "<br><p><b>JUDGES' ASSIGNMENTS:</b></p><p>";
if($ct==1) echo "There is 1 judge assignment in the database.";
else echo "There are $ct judge assignments in the database.";
echo "</p>";
if($ct>0)
{
    echo "<p><a class=\"small\" href=\"stateadmin.php?session=$session&deletetable=joassignments\" onClick=\"return confirm('Are you sure you wish to remove the judge assignments from the database?');\">Remove all judge assignments</a> <i>(this does not remove the judges, just their assigned events.)</i></p>";
    echo "<p>OR ";
}
else echo "<p>";
echo "<a href=\"judges.php?session=$session\" target=\"_blank\">Go to Manage Journalism Judges &rarr;</a></p>";
//STATE QUALIFIERS
$sql="SELECT * FROM joqualifiers";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo "<br><p><b>QUALIFIERS FOR STATE:</b></p><p>";
if($ct==1) echo "There is 1 State Qualifier in the database.";
else echo "There are $ct State Qualifiers in the database.";
if($ct>0)
    echo "<p><a class=\"small\" href=\"stateadmin.php?session=$session&deletetable=joqualifiers\" onClick=\"return confirm('Are you sure you wish to remove the state qualifiers from the database?');\">Remove all state qualifiers</a> <i>(this does not remove the entries or their rankings, just their designation as a state qualifier.)</i></p>";
else echo "</p>";

//DATES:
echo "<br><p><b>DATES:</b></p>";
if($savedates)
    echo "<div class='alert'>The dates have been saved.</div>";
//Prelim Submissions Due Date:
$date=explode('-',GetDueDate('jo_contest'));
echo "<p>Due Date for Schools to Submit Preliminary Entries: <select name=\"prelimm\">".GetDateSelectOptions("MM",$date[1])."</select>/<select name=\"prelimd\">".GetDateSelectOptions("DD",$date[2])."</select>/<select name=\"prelimy\">".GetDateSelectOptions("YYYY",$date[0])."</select></p>";
//Judges Due Date:
$date=explode('-',GetJOSetting('judgeduedate'));
echo "<p>Due Date for Judges to Submit Final Rankings: <select name=\"judgem\">".GetDateSelectOptions("MM",$date[1])."</select>/<select name=\"judged\">".GetDateSelectOptions("DD",$date[2])."</select>/<select name=\"judgey\">".GetDateSelectOptions("YYYY",$date[0])."</select></p>";
//STATE Due Date
$date=explode('-',GetDueDate('jo'));
echo "<p>Due Date for Schools to Submit STATE Entrants: <select name=\"statem\">".GetDateSelectOptions("MM",$date[1])."</select>/<select name=\"stated\">".GetDateSelectOptions("DD",$date[2])."</select>/<select name=\"statey\">".GetDateSelectOptions("YYYY",$date[0])."</select></p>";
//SAVE
echo "<p><input type=\"submit\" name=\"savedates\" value=\"Save Dates\"></p>";

echo "</div>";
echo "</form>";
echo $end_html;
?>
