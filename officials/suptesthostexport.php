<?php

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

$csv="\"Date\",\"Host\",\"Location\",\"Time\",\"Sports\"\r\n";

$sql2="SELECT * FROM suptesthosts WHERE showsched='x' ORDER BY mtgdate,hostname";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $date=split("-",$row2[mtgdate]);
   $sports=split("/",$row2[sports]);
   $sportstr="";
   for($i=0;$i<count($sports);$i++)
   {
      $sportstr.=GetSportName($sports[$i])."/";
   }
   $sportstr=substr($sportstr,0,strlen($sportstr)-1);
   $csv.="\"$date[1]/$date[2]/$date[0]\",\"$row2[hostname]\",\"$row2[location]\",\"$row2[mtgtime]\",\"$sportstr\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/suptesthostexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/suptesthostexport.csv");

header("Location:reports.php?session=$session&filename=suptesthostexport.csv");
exit();
?>
