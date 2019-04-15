<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$csv="\"Activity\",\"Date\",\"Host\",\"Location\",\"Type\",\"Time\"\r\n";

$sql="SHOW TABLES LIKE '%ruleshosts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("ruleshosts",$row[0]);
   $cursp=$temp[0];
   $table=$row[0];
   $sportname=GetSportName($cursp);
   $sql2="SELECT * FROM $table WHERE showsched='x' ORDER BY mtgdate,hostname";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $date=split("-",$row2[mtgdate]);
      $csv.="\"$sportname\",\"$date[1]/$date[2]/$date[0]\",\"$row2[hostname]\",\"$row2[location]\",\"$row2[type]\",\"$row2[mtgtime]\"\r\n";
   }
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/ruleshostexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/ruleshostexport.csv");

header("Location:reports.php?session=$session&filename=ruleshostexport.csv");
exit();
?>
