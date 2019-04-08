<?php

require '../functions.php';
require '../variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
$sql="SELECT t1.school,t1.class,t1.district,t1.type,t2.* FROM fbschool AS t1,fbpriority AS t2 WHERE t1.sid=t2.sid ORDER BY t1.school";
$result=mysql_query($sql);
$csv="School,Class,District,Type,Opp1,HA1,Date1,Opp2,HA2,Date2,Opp3,HA3,Date3,Opp4,HA4,Date4,Opp5,HA5,Date5,Opp6,HA6,Date6,Opp7,HA7,Date7,Opp8,HA8,Date8,Share Stadium,Schools Sharing With,Name of Submitter,Date Submitted\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="$row[0],$row[1],$row[2],$row[3],";
   for($i=1;$i<=8;$i++)
   {
      $oppfield="opp".$i; $hafield="homeaway".$i; $datefield="date".$i;
      $sql2="SELECT school FROM fbschool WHERE sid='$row[$oppfield]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $curopp=$row2[0];
      $csv.=$curopp.",".strtoupper($row[$hafield]).",".$row[$datefield].",";
   }
   if($row[stadium]=='y') $csv.="YES,";
   else if($row[stadium]=='n') $csv.="NO,";
   else $csv.=",";
   $csv.="$row[schools],$row[submitter],".date("m/d/Y",$row[datesub])."\r\n";
}

$open=fopen(citgf_fopen("priorityexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("priorityexport.csv");

header("Location:priorityexport.csv");
exit();
?>
