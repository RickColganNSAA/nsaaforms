<?php
echo "No longer in use as of Spring 2012";
exit();
//exportresults.php: Export District T&F Results for Jerel's program
//Created 11/18/09 
//Author: Ann Gaffigan

require '../functions.php';
require '../variables.php';
require 'trfunctions.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

$db1="nsaascores";
$db2="nsaaofficials";

$sql="SELECT * FROM $db2.trdistricts ORDER BY class,district";
$result=mysql_query($sql);
$csv="";
while($row=mysql_fetch_array($result))
{
	/*
   for($i=0;$i<count($trevents_g);$i++)
   {
      $csv.=GetResults($row[id],'g',$trevents_g[$i],true);
   }
   for($i=0;$i<count($trevents);$i++)
   {
      $csv.=GetResults($row[id],'b',$trevents[$i],true);
   }
	*/
   $sql2="SELECT * FROM $db1.trevents ORDER BY gender,eventdistcode";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $csv.=GetResults($row[id],strtolower(substr($row2[gender],0,1)),$row2[eventdistcode],1);
   }
   $csv.=GetResults($row[id],'b','extraqual',1);
   $csv.=GetResults($row[id],'g','extraqual',1);
   $csv.=GetResults($row[id],'b','teamscores',1);
   $csv.=GetResults($row[id],'g','teamscores',1);
}
$csv.="END";
$filename="trdistrictresults_".date("m_d_y_gia").".csv";
$open=fopen(citgf_fopen("exports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("exports/$filename");

header("Content-type: text/css");
header("Content-Disposition: attachment; filename=".urlencode($filename)."");
readfile(getbucketurl("exports/".$filename.""));
?>
