<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

$sql="SELECT * FROM $archivedb.judges WHERE payment!='' ORDER BY last,first,middle";
$result=mysql_query($sql);
$csv="\"First\",\"Last\",\"Address\",\"City\",\"State\",\"Zip\"\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="\"$row[first]\",\"$row[last]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/jexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/jexport.csv");

header("Location:reports.php?session=$session&filename=jexport.csv");
exit();

?>
