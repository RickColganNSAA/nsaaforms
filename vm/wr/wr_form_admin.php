<?php
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);
if ($export){
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=coaches_form.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('School','Class','Head Coach Name','Assistant Coach 1','Assistant Coach 2','Assistant Coach 3','Assistant Coach 4','Assistant Coach 5','Assistant Coach 6','Assistant Coach 7','Assistant Coach 8'));
    $sql="SELECT * FROM wr_coaches_form";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result)){
        fputcsv($output,array($row['school'],$row['class'],$row['head_coach'],$row['assistant_1'],$row['assistant_2'],$row['assistant_3'],$row['assistant_4'],$row['assistant_5'],$row['assistant_6'],$row['assistant_7'],$row['assistant_8']));
    }
    exit();
}

$level=GetLevel($session);

//verify user
if(!ValidUser($session))
{
    header("Location:../index.php");
    exit();
}


//get school user chose (Level 1) or belongs to (Level 2, 3)
if((!$school_ch || $level==2 || $level==3) && $director!=1)
{
    $school=GetSchool($session);
}
echo GetHeader($session);
if ($delete==1){
    $sql="Delete FROM wr_coaches_form WHERE schoolid='$schoolid'";
    mysql_query($sql);
    echo "<div style='padding: 10px;background: #ccc9a4;width: 300px'>Form Deleted Successfully</div>";
}

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;width: 70%\">";
echo "<tr><td colspan='6' align='right'><form action='wr_form_admin.php' method='post'><input type='hidden' name='session' value='$session'><input type='submit' value='Export' name='export'/></form></td></tr>";
echo "<tr>";
echo "<td>School</td>";
echo "<td>Class</td>";
echo "<td>Head Coach</td>";
echo "<td>Assistant Coaches</td>";
echo "<td>View/Delete</td>";
echo "</tr>";
$sql="SELECT * FROM wr_coaches_form";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)){
    echo "<tr>";
    echo "<td>$row[school]</td>";
    echo "<td>$row[class]</td>";
    echo "<td>$row[head_coach]</td>";
    echo "<td>$row[assistant_1],$row[assistant_2],$row[assistant_3],$row[assistant_4],$row[assistant_5],$row[assistant_6],$row[assistant_7],$row[assistant_8]</td>";
    echo "<td>".($row[type]=="dual"?"Dual Wrestling Coaches Name Form":"Individual Wrestling Coaches Name Form")."</td>";
    echo "<td>";
    echo "<a href='wr_coaches_form.php?session=$session&schoolid=$row[schoolid]'>View</a>";
    echo "  <a href='wr_form_admin.php?session=$session&type=$row[type]&schoolid=$row[schoolid]&delete=1' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";