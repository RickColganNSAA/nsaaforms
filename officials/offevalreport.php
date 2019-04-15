<?php
/***********************************
offevalreport.php
Report of each official and the year(s)
he or she has been evaluted by an
observer through the system
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

$obstable=$sport."observe";
$offtable=$sport."off";

$sql="SHOW DATABASES LIKE 'nsaaofficials2%'";
$result=mysql_query($sql);
$curyear=date("Y");
if(date("m")<6) $curyear--;
$csv="\"Official\",\"$curyear\",";
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
   $observed=0;
   $curcsv="\"$row[first] $row[last]\",";
   //CURRENT DB:
   $sql2="SELECT * FROM $obstable WHERE (offid='$row[id]'";
   if($sport=='bb') $sql2.=" OR offid2='$row[id]' OR offid3='$row[id]'";
   $sql2.=") AND dateeval!=''";
   $result2=mysql_query($sql2); echo mysql_error();
   if(mysql_num_rows($result2)>0)
   {
      $curcsv.="\"x\",";
      $observed=1;
   }
   else $curcsv.="\"\",";
   //ARCHIVES:
   for($i=(count($dbs)-1);$i>=0;$i--)
   {
      $sql2="SELECT * FROM ".$dbs[$i].".$obstable WHERE (offid='$row[id]'";
      if($sport=='bb') $sql2.=" OR offid2='$row[id]' OR offid3='$row[id]'";
      $sql2.=") AND dateeval!=''";
      $result2=mysql_query($sql2); 
      if(!mysql_error())
      {
         if(mysql_num_rows($result2)>0) 
         {
	    $curcsv.="\"x\",";
	    $observed=1;
         }
         else $curcsv.="\"\",";
      }
      else $curcsv.="\"\",";
   }
   $curcsv.="\r\n";
   if($observed==1)
   {
      $csv.=$curcsv;
      $ct++;
   }
}

$filename=$sport."offevalyears.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
echo "<h2>$ct officials total! <a href=\"reports.php?session=$session&filename=$filename\">Download Report</a></h2>";

echo $end_html;
?>
