<?php
/*******************************************
 * judges.php
 * NSAA Manages Journalism Judges
 * Created 11/15/12
 * Author: Ann Gaffigan
 *******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header = GetHeader($session);
$level = GetLevel($session);

//connect to db:
$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $db);
if (isset($_POST['export'])) {
    $sql = "SELECT first,last,email,city,state,zip,address,password FROM jojudges";
    $result = mysql_query($sql);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=judges.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array("First Name","Last Name","Email","City","State","Zip","Address","password"));
    while ($row = mysql_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}
//verify user
if (!ValidUser($session) || $level != 1) {
    header("Location:../index.php");
    exit();
}

if ($_REQUEST['delete']) {
    $sql = "DELETE FROM jojudges WHERE id='" . $_REQUEST['delete'] . "'";
    $result = mysql_query($sql);
}

if ($_POST['add'] && $_POST['first'] != '' && $_POST['password'] != '') {
    $first = addslashes($first);
    $last = addslashes($last);
    $sql = "INSERT INTO jojudges (first,last,email,password,datesub) VALUES ('$first','$last','$email','$password','" . time() . "')";
    $result = mysql_query($sql);
    header("Location:judges.php?session=$session&added=1");
}

echo $init_html;
echo $header;

echo "<form method='post' action='judges.php'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br><a href=\"stateadmin.php?session=$session\">Return to Journalism Admin</a>";
echo "<br><h2>PRELIM Journalism JUDGES:</h2>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";
echo "<caption>";

//ADD NEW JUDGE
echo "<table cellspacing=0 cellpadding=3 style=\"width:400px;\"><caption><b>Add a New Judge:</b></caption>";
echo "<tr align=left><td>Name:</td><td><input type=text size=15 name=\"first\" value=\"First\" onFocus=\"if(this.value=='First') { this.value=''; }\"> <input type=text size=25 name=\"last\" value=\"Last\" onFocus=\"if(this.value=='Last') { this.value=''; }\"></td></tr>";
echo "<tr align=left><td>E-mail:</td><td><input type=text size=40 name=\"email\"></td></tr>";
echo "<tr align=left><td>Password:</td><td><input type=password size=20 name=\"password\"></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name='add' value=\"Add Judge\">";
if ($added == 1)
    echo "<br><div class='alert' style='width:400px;text-align:center;'>The judge has been added below.</div>";
else if ($saved == 1)
    echo "<br><div class='alert' style='width:400px;text-align:center;'>The changes have been saved.</div>";
echo "</td></tr>";
echo "</table><br>";
echo "<a href=\"assignjudges.php?session=$session\">Assign Judges to Event Categories</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a href=\"/nsaaforms/jo\" target=\"_blank\">Preview Judges Login Link</a><br><br>";
echo "<a href='instructions.php?session=$session' target=\"_blank\">Instructions Link</a><br><br>";
if ($_REQUEST['delete'])
    echo "<div class=alert>The judge has been deleted.</div>";

//EXISTING JUDGES:
if ($resetdatesub == 1) {
    $sql = "UPDATE joassignments SET datesub=0";
    if ($resetdatesubid > 0) $sql .= " WHERE id='$resetdatesubid'";
    $result = mysql_query($sql);
}
$sql = "SELECT * FROM jojudges ORDER BY last,first";
$result = mysql_query($sql);
echo "(" . mysql_num_rows($result) . " Judges)</caption>";
//code added by robin
echo "<tr align=right><td colspan='6'><button name='export'>Export</button></td></tr>";
//end of code by robin
if (mysql_num_rows($result) > 0) {
    echo "<tr align=center><td><b>Name</b><br>(Click to Edit Judge's Information)</td></b></td><td><b>E-mail (Username)</b><br>(Click to send email)</td><td><b>Password</b></td><td><b>Assignment</b></td><td><b>Submitted Rankings?</b><br><a href=\"judges.php?session=$session&resetdatesub=1\" onClick=\"return confirm('Are you sure you want to reset all judges\' rankings to NOT submitted?');\">Reset ALL to<br>Not Submitted</a></td><td><b>Delete</b></td></tr>";
}
while ($row = mysql_fetch_array($result)) {
    echo "<tr align=left><td><a href=\"editjudge.php?session=$session&judgeid=$row[id]\">$row[first] $row[last]</a></td><td><a class='small'href=\"mailto:$row[email]\">$row[email]</td><td>$row[password]</td>";
    echo "<td>";
    $sql2 = "SELECT * FROM joassignments WHERE judgeid='$row[id]'";
    $result2 = mysql_query($sql2);
    $assigns = "";
    $submits = "";
    while ($row2 = mysql_fetch_array($result2)) {
        $assigns .= GetJOCategory($row2[catid]) . " (Class $row2[class])<br>";
        if ($row2[datesub] > 0)
            $submits .= date("m/d/y", $row2[datesub]) . " at " . date("g:ia", $row2[datesub]) . " - <a class=\"small\" href=\"judges.php?session=$session&resetdatesub=1&resetdatesubid=$row2[id]\" onClick=\"return confirm('Are you sure you want to reset this judge\'s rankings to NOT submitted?');\">Reset to Not Submitted</a><br>";
        else $submits .= "Not Submitted<br>";
    }
    if ($assigns != '') $assigns = substr($assigns, 0, strlen($assigns) - 4);
    if ($submits != '') $submits = substr($submits, 0, strlen($submits) - 4);
    echo $assigns . "&nbsp;</td><td>$submits&nbsp;</td>";
    echo "<td align=center><a href=\"judges.php?session=$session&delete=$row[id]\" onClick=\"return confirm('Are you sure you want to delete this judge?');\">X</a></td></tr>";
    echo "</tr>";
}

echo "</table>";
echo "</form>";


echo $end_html;
?>
