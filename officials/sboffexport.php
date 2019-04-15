<?php
//Export all accepted/confirmed STATE BB Officials

require 'functions.php';
require 'variables.php';

//connect to db
$db = mysql_connect("$db_host", $db_user2, $db_pass2);
mysql_select_db($db_name2, $db);

if (!ValidUser($session)) {
    header("Location:index.php?error=1");
    exit();
}

$sport = 'sb';
$sportname = GetSportName($sport);
$contracts = $sport . "contracts";
$disttimes = $sport . "disttimes";
$districts = $sport . "districts";
$filename=$sportname.(isset($class)?$class:"")."Officials";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$filename.'.csv');
$output = fopen('php://output', 'w');
fputcsv($output, array('Name','SSN','Classification','Address','City','State','Zip','E-mail'));
$sql = "SELECT DISTINCT id,class,district,hostschool,type FROM $districts WHERE type!='State' ";
if (isset($class) && $class == "A") {
    $sql .= " AND class='$class' ";
}
$sql .= "ORDER BY class,district";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
    $sql3 = "SELECT DISTINCT t1.offid FROM $contracts AS t1, $disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.distid='$row[id]' AND t2.day IN (SELECT DISTINCT day FROM sbdisttimes WHERE distid='$row[id]' ORDER BY day) AND t1.accept='y' ORDER BY t2.time";
    $result3 = mysql_query($sql3);
    while ($row3 = mysql_fetch_array($result3)) {
        $sql4 = "SELECT * FROM officials WHERE id='$row3[offid]'";
        $result4 = mysql_query($sql4);
        $row4 = mysql_fetch_array($result4);
        $sql5 = "SELECT class FROM sboff WHERE offid='$row3[offid]'";
        $result5 = mysql_query($sql5);
        $row5 = mysql_fetch_array($result5);
        fputcsv($output, array($row4[first].' '. $row4[middle].' '. $row4[last],$row4[socsec],$row5[0],$row4[address],$row4[city],$row4[state],$row4[zip],$row4[email]));
    }
}
exit();
?>
