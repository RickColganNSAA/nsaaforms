<?php
/***********************************
offassignreport.php
Report of each official and the year(s)
he or she has been assigned postseason
11/13/13 by Ann Gaffigan
************************************/
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

if(!$sport || $sport=="") $sport='fb';
$sportname=GetSportName($sport);

$contracts=$sport."contracts";
$districts=$sport."districts";
$offtable=$sport."off";
if(preg_match("/so/",$sport))
   $offtable="sooff";
else if(preg_match("/bb/",$sport))
   $offtable="bboff";

$sql="SHOW DATABASES LIKE 'nsaaofficials2%'";
$result=mysql_query($sql);
$curyear=date("Y");
if(date("m")<6) $curyear--;
$csv="\"Official's ID\",\"Official\",\"$curyear\",";
$dbs=array(); $d=0;
while($row=mysql_fetch_array($result))
{
   $dbs[$d]=$row[0];
   $d++;
}
sort($dbs);
for($i=(count($dbs)-1);$i>=0;$i--)
{
   $year=substr($dbs[$i],13,4);
   $csv.="\"$year\",";
}
$csv.="\r\n";

echo $init_html;

$sql="SELECT DISTINCT t1.first,t1.last,t1.id FROM officials AS t1,$offtable AS t2 WHERE t1.id=t2.offid ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
echo mysql_error();
$ct=0;
while($row=mysql_fetch_array($result))
{
   $assigned=0;
   $curcsv="\"$row[id]\",\"$row[first] $row[last]\",";
   //CURRENT DB:
   $sql2="SELECT * FROM $contracts WHERE offid='$row[id]' AND post='y' AND accept='y' AND confirm='y'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      //GET ASSIGNMENTS
      $assignments=GetOffContracts($sport,$row[id],$session);
      $curassign="";
      for($a=0;$a<count($assignments);$a++)
      {
         if($assignments[abbrev][$a]!='') $curassign.=$assignments[abbrev][$a]."/";
      }
      if($curassign!='') 
      {
         $curassign=substr($curassign,0,strlen($curassign)-1);
         $curcsv.="\"$curassign\",";
         $assigned=1;
      }
      else
      {
	 $curcsv.="\"Could not find details\",";
	 $assigned=1;
      }
   }
   else $curcsv.="\"\",";
   //ARCHIVES:
   for($i=(count($dbs)-1);$i>=0;$i--)
   {
      $sql2="SELECT * FROM ".$dbs[$i].".$contracts WHERE offid='$row[id]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0) 
      {
  	 //GET ASSIGNMENTS
         $assignments=GetOffContracts($sport,$row[id],$session,$dbs[$i]);
	 $curassign="";
	 for($a=0;$a<count($assignments);$a++)
	 {
	    if($assignments[abbrev][$a]!='') $curassign.=$assignments[abbrev][$a]."/"; 
	 }
	 if($curassign!='') 
	 {
	    $curassign=substr($curassign,0,strlen($curassign)-1);
	    $curcsv.="\"$curassign\",";
	    $assigned=1;
         }
         else $curcsv.="\"$sport - $session - $sql2\",";
      }
      else $curcsv.="\"\",";
   }
   $curcsv.="\r\n";
   if($assigned==1)
   {
      $csv.=$curcsv;
      $ct++;
   }
}

$filename=$sport."offassignyears.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
echo "<h2>$ct officials total! <a href=\"reports.php?session=$session&filename=$filename\">Download Report</a></h2>";

echo $end_html;
?>
