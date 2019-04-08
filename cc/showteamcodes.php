<?php

require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}

if(!$sport) $sport='ccg';
$table=GetSchoolsTable($sport);

sort($cc_sch);
$csv="\"Team Name\",\"Hytek Code\"\r\n";
$sql="SELECT school,sid FROM $table ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sid=$row[sid]; 
   if(strlen($sid)==1) $sid="000".$sid;
   if(strlen($sid)==2) $sid="00".$sid;
   if(strlen($sid)==3) $sid="0".$sid;
   $csv.="\"$row[school]\",\"$sid\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/attachments/teamcodes_$sport.txt"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/teamcodes_$sport.txt");
header("Location:../attachments.php?session=$session&filename=teamcodes_$sport.txt");
exit();
?>
