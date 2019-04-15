<?php

require 'functions.php';
require '../../calculate/functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$database || $database=="")
{
   $db1=$db_name; $db2=$db_name2;
}
else
{
   $db2=$database; $db1=ereg_replace("officials","scores",$database);
}


$csv="\"School\",\"Player Name\",\"Sport\",\"Date of Game\",\"Level\",\"Opponent\",\"Official's Name\",\"Date Submitted\",\"Verified Report\",\"Notes\"\r\n";
$eject=array(); $ix=0;

$sql="SELECT t2.school,t1.* FROM $db2.yellowcards AS t1,$db1.".$sport."school AS t2 WHERE t1.sport='$sport' AND t1.datesub>0 AND t1.sid=t2.sid ORDER BY t2.school DESC";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $db1.eligibility WHERE id='$row[studentid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $csv.="\"$row[school]\",\"$row2[first] $row2[last]\",\"".GetSportName($row[sport])."\",\"$row[gamedate]\",\"$row[level]\",\"".GetSchoolName($row[oppid],$row[sport],date("Y"))."\",\"".GetOffName($row[offid])."\",\"".date("m/d/y",$row[datesub])."\",\"".strtoupper($row[verify])."\",\"$row[notes]\"\r\n";
}

$open=fopen(citgf_fopen("/home/nsaahome/reports/yellowcardexport.csv"),"w");
if(!fwrite($open,$csv)) { echo "COULD NOT WRITE"; exit(); }
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/yellowcardexport.csv");

header("Location:reports.php?session=$session&filename=yellowcardexport.csv");
exit();
    
?>
