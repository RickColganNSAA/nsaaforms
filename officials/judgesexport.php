<?php
require 'functions.php';
require 'variables.php';

$origsport=$sport;
if(ereg("state",$sport)) $type="State";
else $type="District";
if(ereg("sp",$sport)) $sport='sp';
else $sport='pp';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}


$sportname=GetSportName($sport);
$filename=$sport."judgesexport.csv";
$contracts=$sport."contracts";
$districts=$sport."districts";

$sql="SELECT DISTINCT t1.socsec,t1.first,t1.middle,t1.last,t1.address,t1.city,t1.state,t1.zip,t3.type FROM judges AS t1,$contracts AS t2,$districts AS t3 WHERE t1.id=t2.offid AND t2.distid=t3.id AND t2.accept='y' AND t2.confirm='y' AND t3.type='$type' ORDER BY t3.type,t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$csv="\"District/State\",\"SSN\",\"First\",\"Middle\",\"Last\",\"Address\",\"City\",\"State\",\"Zip\"\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="\"$row[8]\",\"$row[0]\",\"$row[1]\",\"$row[2]\",\"$row[3]\",\"$row[4]\",\"$row[5]\",\"$row[6]\",\"$row[7]\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");

echo $init_html;
echo GetHeaderJ($session,"jcontractadmin");
echo "<br><br><a target=new href=\"reports.php?session=$session&filename=$filename\">Click Here for the $type $sportname Judges Export</a>";
echo "<br><br><a class=small href=\"jcontractadmin.php?session=$session&sport=$origsport\">Contracts Home</a>&nbsp;&nbsp;&nbsp;<a class=small href=\"jwelcome.php?session=$session\">Home</a>";
echo $end_html;
exit();
?>
