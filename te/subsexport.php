<?php
/***************************
subsexport.php
Export Tennis Substitutes
Author: Ann Gaffigan
Date: 5/18/10
****************************/

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'tefunctions.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
if(!$sport) $sport="te_b";
$sportname=GetActivityName($sport);

$csv="School\tSubstitutes";
//if($class=="A" || $sport=='te_b') 
$table=$sport."state"; //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
//else $table=$sport;
$sql="SELECT DISTINCT t1.* FROM eligibility AS t1, $table AS t2 WHERE t1.id=t2.player1 AND t2.division='substitute' ORDER BY t1.school,t1.last,t1.first";
$result=mysql_query($sql);
$cursch="";
while($row=mysql_fetch_array($result))
{
   if($cursch!=$row[school])   
   {
      $csv.="\r\n$row[school]\t";
      $cursch=$row[school];
   }
   $csv.="$row[first] $row[last]\t";
}
$filename=strtoupper(ereg_replace("_","",$sport))."Class".$class."Substitutes.xls";
if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w")) echo "COULD NOT OPEN";
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
header("Location:../exports.php?session=$session&filename=$filename");

?>
