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

$sport='bbg';
$sportname=GetSportName($sport);
$contracts=$sport."contracts";
$disttimes=$sport."disttimes";
$districts=$sport."districts";

$sql="SELECT DISTINCT t1.* FROM officials AS t1, $contracts AS t2, $disttimes AS t3, $districts AS t4 WHERE t1.id=t2.offid AND t2.disttimesid=t3.id AND t3.distid=t4.id AND t4.type='State' AND t2.post='y' AND t2.accept='y' AND t2.confirm='y' ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$csv="\"SSN\",\"Name\",\"Classification\",\"Address\",\"City\",\"State\",\"Zip\",\"E-mail\",";
$sql2="SELECT * FROM bbtourndates WHERE girls='x' AND lodgingdate='x' ORDER BY tourndate";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $date=explode("-",$row2[tourndate]);
   $csv.="\"Lodging $date[1]/$date[2]\",";
}
$csv.="\"Arrival\",\"Special Requests\"\r\n";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT class FROM bboff WHERE offid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $csv.="\"$row[socsec]\",\"$row[first] $row[middle] $row[last]\",\"$row2[0]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[email]\",";
   $sql2="SELECT t1.* FROM $contracts AS t1, $disttimes AS t2, $districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t3.gender='$gender' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' AND t1.offid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $csv.="\"".strtoupper($row2[date1])."\",\"".strtoupper($row2[date2])."\",\"".strtoupper($row2[date3])."\",\"".strtoupper($row2[date4])."\",\"$row2[arrive]\",\"$row2[special]\"";
   $csv.="\r\n";
} 

$filename=$sport."stateoffs.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");

echo $init_html;
echo "<table width=100%><tr align=center><td><b>";
echo "$sportname $gender Officials Contracted for the State Tournament: <a class=small href=\"reports.php?session=$session&filename=$filename\">Click Here</a></b>";

echo $end_html;

exit();
?>
