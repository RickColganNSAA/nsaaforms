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

$sport='fb';
$sportname=GetSportName($sport);
$contracts=$sport."contracts";
$brackets=$sport."brackets";

$sql="SELECT DISTINCT t1.* FROM officials AS t1, $contracts AS t2, $brackets AS t3 WHERE t1.id=t2.offid AND t2.gameid=t3.id AND t2.post='y' AND t2.accept='y' AND t2.confirm='y' ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$csv=",,,,,\"Class A\",,,,,\"Class B\",,,,,\"Class C1\",,,,,\"Class C2\",,,,,\"Class D1\",,,,,\"Class D2\"\r\n";
$csv.="\"Name\",\"Classification\",\"SSN\",\"Address\",\"City\",\"State\",\"Zip\",\"E-mail\",";
for($i=0;$i<6;$i++)
{
   $csv.="\"First Round\",\"Second Round\",\"Quarterfinals\",\"Semifinals\",\"Finals\",";
}
$csv.="\r\n";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT class FROM fboff WHERE offid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $csv.="\"$row[first] $row[middle] $row[last]\",\"$row2[0]\",\"$row[socsec]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[email]\",";
   $sql2="SELECT t2.class,t2.round FROM $contracts AS t1, $brackets AS t2 WHERE t1.offid='$row[id]' AND t1.gameid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ORDER BY t2.class";
   $result2=mysql_query($sql2);
   $contract[A]=array(); $contract[B]=array(); $contract[C1]=array();
   $contract[C2]=array(); $contract[D1]=array(); $contract[D2]=array();
   while($row2=mysql_fetch_array($result2))
   {
      switch($row2[1])
      {
         case "First Round":
	    $round=1;
	    break;
	 case "Second Round":
	    $round=2;
	    break;
	 case "Quarterfinals":
	    $round=3;
	    break;
	 case "Semifinals":
	    $round=4;
	    break;
	 case "Finals":
	    $round=5;
	    break;
	 default:
	    $round=0;
      }
      $contract[$row2[0]][$round]='X';
   }
   for($i=0;$i<count($classes);$i++)
   {
      $csv.="\"".$contract[$classes[$i]][1]."\",";
      $csv.="\"".$contract[$classes[$i]][2]."\",";
      $csv.="\"".$contract[$classes[$i]][3]."\",";
      $csv.="\"".$contract[$classes[$i]][4]."\",";
      $csv.="\"".$contract[$classes[$i]][5]."\",";
   }
   $csv.="\r\n";
} 

$filename=$sport."stateoffs.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");

echo $init_html;
echo "<table width=100%><tr align=center><td><b>";
echo "$sportname Officials Contracted for the State Tournament: <a class=small href=\"reports.php?session=$session&filename=$filename\">Click Here</a></b>";

echo $end_html;

exit();
?>
