<?php
//Export all accepted/confirmed STATE BB Officials

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
    header("Location:index.php?error=1");
    exit();
}

$sport='sb';
$sportname=GetSportName($sport);
$contracts=$sport."contracts";
$disttimes=$sport."disttimes";
$districts=$sport."districts";

$sql="SELECT DISTINCT t1.* FROM officials AS t1, $contracts AS t2, $disttimes AS t3, $districts AS t4 WHERE t1.id=t2.offid AND t2.disttimesid=t3.id AND t3.distid=t4.id AND t4.type='State' ORDER BY t1.last,t1.first,t1.middle";
if($class=="A"){
    echo $sql="SELECT DISTINCT t1.* FROM officials AS t1, $contracts AS t2, $disttimes AS t3, $districts AS t4 WHERE t1.id=t2.offid AND t2.disttimesid=t3.id AND t3.distid=t4.id AND t4.type='State' AND t4.class='A' ORDER BY t1.last,t1.first,t1.middle";

}
$result=mysql_query($sql);
echo mysql_error();
if (isset($export)){
    $filename=$sport."stateoffs.csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$filename.'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Name','SSN','Classification','Address','City','State','Zip','E-mail'));
    while($row=mysql_fetch_array($result))
    {
        $sql2="SELECT class FROM sboff WHERE offid='$row[id]'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        fputcsv($output, array($row[first].' '. $row[middle].' '. $row[last],$row[socsec],$row2[0],$row[address],$row[city],$row[state],$row[zip],$row[email]));
    }
    exit();
}


echo $init_html;
echo "<table width=100%><tr align=center><td><b>";
echo "$sportname Officials Contracted for the State Tournament: <a class=small href=\"sbassignexport.php?session=$session&export=sb\">Click Here</a></b>";

echo $end_html;

exit();
?>
