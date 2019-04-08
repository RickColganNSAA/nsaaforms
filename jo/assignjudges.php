<?php
/*******************************************
assignjudges.php
NSAA Assigns Journalism Judges to Event
Categories
Created 11/15/12
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
    header("Location:../index.php");
    exit();
}

if($_POST['save'])
{
    for($i=0;$i<count($judgeid);$i++)
    {
        $sql="SELECT * FROM joassignments WHERE catid='$catid[$i]' AND class='$class[$i]'";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)==0)
            $sql="INSERT INTO joassignments (judgeid,catid,class) VALUES ('$judgeid[$i]','$catid[$i]','$class[$i]')";
        else
            $sql="UPDATE joassignments SET judgeid='$judgeid[$i]' WHERE catid='$catid[$i]' AND class='$class[$i]'";
        $result=mysql_query($sql);
    }
    header("Location:assignjudges.php?session=$session&saved=1");
}

echo $init_html;
echo $header;

echo "<form method='post' action='assignjudges.php'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br><a href=\"judges.php?session=$session\">Return to Journalism Judges</a>";
echo "<br><h2>Assign PRELIM Journalism Judges:</h2>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";
if($saved==1)
    echo "<caption><div class='alert' style='width:400px;text-align:center;'>The assignments have been saved.</div></caption>";
$sql="SELECT * FROM jojudges ORDER BY last,first";
$result=mysql_query($sql);
$judges=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
    $judges[id][$ix]=$row[id];
    $judges[name][$ix]="$row[first] $row[last]";
    $ix++;
}
$sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY school";
$result=mysql_query($sql);
$classes=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
    $classes[$ix]=$row[0]; $ix++;
}
if (!in_array("C", array_values($classes))) {
    $classes[$ix] = "C";
}
$sql="SELECT * FROM jocategories ORDER BY category";
$result=mysql_query($sql);
echo "<tr align=center>";
for($c=0;$c<count($classes);$c++)
{
    echo "<td><b>Class</b></td><td><b>Category</b></td><td><b>Assigned Judge</b></td>";
}
echo "</tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
    echo "<tr align=left>";
    for($c=0;$c<count($classes);$c++)
    {
        $sql2="SELECT * FROM joassignments WHERE catid='$row[id]' AND class='$classes[$c]'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        echo "<td>$classes[$c]<input type=hidden name=\"class[$ix]\" value=\"$classes[$c]\"></td><td>$row[category]<input type=hidden name=\"catid[$ix]\" value=\"$row[id]\"></td><td><select name=\"judgeid[$ix]\"><option value=\"0\">Select Judge</option>";
        for($i=0;$i<count($judges[id]);$i++)
        {
            echo "<option value=\"".$judges[id][$i]."\"";
            if($row2[judgeid]==$judges[id][$i]) echo " selected";
            echo ">".$judges[name][$i]."</option>";
        }
        echo "</select></td>";
        $ix++;
    } //END FOR EACH CLASS
    echo "</tr>";
}
echo "</table>";
echo "<input type=submit class='fancybutton2' name='save' value=\"Save Assignments\">";
echo "</form>";

echo $end_html;
?>
