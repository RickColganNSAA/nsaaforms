<?php
//Export all accepted/confirmed STATE Officials

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

$sport='wr';
$sportname=GetSportName($sport);
$contracts=$sport."contracts";
$disttimes=$sport."disttimes";
$districts=$sport."districts";
$offtbl=$sport."off";

if($type=="state") $typech="State";
else $typech="State Dual";

$sql="SELECT DISTINCT t1.* FROM officials AS t1, $contracts AS t2, $districts AS t3 WHERE t1.id=t2.offid AND t2.distid=t3.id AND t3.type='$typech' AND t2.post='y' AND t2.accept='y' AND t2.confirm='y' ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$csv="\"Name\",\"SSN\",\"Classification\",\"Address\",\"City\",\"State\",\"Zip\",\"E-mail\",";
$sql2="SELECT * FROM wrtourndates WHERE lodgingdate='x' AND label = '$typech' ORDER BY tourndate";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $date=explode("-",$row2[tourndate]);
   $csv.="\"Lodging $date[1]/$date[2]\",";
}
$csv.="\"Arrival\",\"Special Requests\"\r\n";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT class FROM $offtbl WHERE offid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $csv.="\"$row[first] $row[middle] $row[last]\",\"$row[socsec]\",\"$row2[0]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[email]\"";
   $sql2="SELECT t1.* FROM $contracts AS t1, $districts AS t2 WHERE t1.offid='$row[id]' AND t1.distid=t2.id AND t2.type='$typech' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $row2[special]=ereg_replace("\r\n","&nbsp;&nbsp;&nbsp;",$row2[special]);
   $csv.=",\"".strtoupper($row2[date1])."\",\"".strtoupper($row2[date2])."\",";
   if($type=="state")
      $csv.="\"".strtoupper($row2[date3])."\",\"".strtoupper($row2[date4])."\",";
   $csv.="\"$row2[arrive]\",\"$row2[special]\"";
   $csv.="\r\n";
} 

$filename=$sport.$type."offs.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");

echo $init_html;
echo "<table width=100%><tr align=center><td><b>";
echo "$sportname Officials Contracted for the $typech Tournament: <a class=small href=\"reports.php?session=$session&filename=$filename\">Click Here</a></b>";

echo $end_html;

exit();
?>
