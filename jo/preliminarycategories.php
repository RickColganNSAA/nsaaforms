
<?php
/*******************************************
 * statejudges.php
 * NSAA Manages Journalism Judges
 * Created 3/30/18
 * Author: criticalitgroup
 *******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header = GetHeader($session);
$level = GetLevel($session);

//connect to db:
$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $db);

//verify user
if (!ValidUser($session) || $level != 1) {
    header("Location:../index.php");
    exit();
}

if ($_REQUEST['delete']) {
    $sql = "DELETE FROM jocategories WHERE id='" . $_REQUEST['delete'] . "'";
    $result = mysql_query($sql);
}
//echo '<pre>'; print_r($_POST); exit;
if ($_POST['add'] && $_POST['category'] != '') {
    $category = addslashes($category);
    $headerr = addslashes($headerr);
    $sql = "INSERT INTO jocategories (category,maxstudents,maxfiles,maxentries,header,webapproved,description) VALUES ('$category','$maxstudents','$maxfiles','$maxentries','$headerr','" . time() . "','$description')";
    $result = mysql_query($sql);
    header("Location: preliminarycategories.php?session=$session&added=1");
}

echo $init_html;
echo $header;

echo "<form method='post' action='preliminarycategories.php'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br><a href=\"stateadmin.php?session=$session\">Return to Contest ENTRY SUBMISSIONS</a>";
echo "<br><h2>Categories:</h2>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption>";

//ADD NEW JUDGE
echo "<table cellspacing=0 cellpadding=5 style=\"width:800px;\"><caption><b>Add a New Category:</b></caption>";
echo "<tr align=left ><td  colspan='1'><b>Category Name:</b></td><td><input type=text name=\"category\" size=80 > </td></tr>";
echo "<tr align=left><td><b>Maximum Students:</b></td><td><input type=text size=80 name=\"maxstudents\"  > </td></tr>";
echo "<tr align=left><td><b>Maximum Files:</b></td><td><input type=text size=80 name=\"maxfiles\"  > </td></tr>";
echo "<tr align=left><td><b>Maximum Entries:</b></td><td><input type=text size=80 name=\"maxentries\"  > </td></tr>";
echo "<tr align=left><td><b>Header:</b></td><td><input type=text size=80 name=\"headerr\"></td></tr>";
echo "<tr align=left><td><b>Description:</b></td><td><textarea name='description' id='' cols='65' rows='4'></textarea></td></tr>";
echo "<tr align=right><td colspan=2><input type=submit name='add' value=\"Add Category\">";
if ($added == 1)
    echo "<br><div class='alert' style='width:400px;text-align:center;'>The Category has been added below.</div>";
else if ($saved == 1)
    echo "<br><div class='alert' style='width:400px;text-align:center;'>The changes have been saved.</div>";
echo "</td></tr>";
echo "</table><br>";

if ($_REQUEST['delete'])
    echo "<div class=alert>The Category has been deleted.</div>";

//EXISTING JUDGES:
if ($resetdatesub == 1) {
    $sql = "UPDATE jocategories SET datesub=0";
    if ($resetdatesubid > 0) $sql .= " WHERE id='$resetdatesubid'";
    $result = mysql_query($sql);
}
$sql = "SELECT * FROM jocategories ";
$result = mysql_query($sql);
echo "(" . mysql_num_rows($result) . " Categories)</caption>";
if (mysql_num_rows($result) > 0) {
    echo "<tr align=center><td><b>Category</b></td><td><b>Header</td><td><b>Delete</b></td></tr>";
}
while ($row = mysql_fetch_array($result)) {
    echo "<tr align=left><td><a href=\"editpreliminarycategories.php?session=$session&catid=$row[id]\">$row[category]</a></td><td>$row[header]</td>";
    echo "<td align=center><a href=\"preliminarycategories.php?session=$session&delete=$row[id]\" onClick=\"return confirm('Are you sure you want to delete this category?');\">X</a></td></tr>";
    echo "</tr>";
}

echo "</table>";
echo "</form>";


echo $end_html;
?>
