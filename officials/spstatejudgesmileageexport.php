<?php
/***************************************
spstatejudgesmileageexport.php
Exports judges' info and # of rounds
for mileage reimbursement
Created 3/21/12
Author Ann Gaffigan
****************************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

$sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.address,t1.city,t1.state,t1.zip,t1.socsec FROM judges AS t1, spstateassign AS t2 WHERE t1.id=t2.offid ";
$sql.="ORDER BY t1.last,t1.first,t1.city";
$result=mysql_query($sql);
$string="\"First\",\"Last\",\"Address\",\"City\",\"State\",\"Zip\",\"SSN\",\"# Rounds\"\r\n";
$ix=0;
while($row=mysql_fetch_array($result))
{
   $offid=$row[id];
   $first=trim($row[first]);
   $last=trim($row[last]);
   $string.="\"$first\",\"$last\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[socsec]\",";
   $sql2="SELECT DISTINCT roomid FROM spstateassign WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   $ct=mysql_num_rows($result2);
   $string.="\"$ct\"\r\n";
   $ix++;
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstatejudgesmileage.csv"),"w");
fwrite($open,$string);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatejudgesmileage.csv");
header("Location:reports.php?session=$session&filename=spstatejudgesmileage.csv");
exit();

?>
