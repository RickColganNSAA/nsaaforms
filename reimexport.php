<?php
/********************************************************
reimexport.php
Reimbursements Export for NSAA
Created 9/12/12
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

if(!$sport) $sport='vb';
$filename=strtoupper($sport)."Reimbursements.xls";

$csv="\"".strtoupper($sport)." School\"\t\"City\"\t\"Coach\"\t";
if($sport=='wr')
   $csv.="\"Thursday Athletes\"\t\"Friday Athletes\"\t\"Saturday Athletes\"\t";
else if($sport=='cc' || $sport=='so' || $sport=='tr')
   $csv.="\"Girls\"\t\"Boys\"\t";
else 
   $csv.="\"Students\"\t";
$csv.="\"Miles\"\t\"Trips\"\t\"Rate\"\t\"Mileage\"\t";
$csv.="\"Nights\"\t\"Lodging\"\t";
if($sport=='vb')
   $csv.="\"Matches\"\t\"Matches Amount\"\t";
$csv.="\"Total\"\r\n";
$sql="SELECT t1.*,t2.school,t2.city_state FROM reimbursements AS t1,headers AS t2 WHERE t1.schoolid=t2.id AND t1.sport='$sport' ORDER BY t2.school";
$result=mysql_query($sql);
$currow=2;
while($row=mysql_fetch_array($result))
{
   $csv.="\"$row[school]\"\t\"$row[city_state]\"\t\"1\"\t\"$row[studct1]\"\t";
   if($sport=='wr')
   {
      $csv.="\"$row[studct2]\"\t\"$row[studct3]\"\t";
      $peopleCol1="D"; $peopleCol2="E"; $peopleCol3="F";
      $milesCol="G"; $tripsCol="H"; $rateCol="I";
      $mileageCol="J"; $nightsCol="K"; $lodgingCol="L";
   }
   else if($sport=='cc' || $sport=='so' || $sport=='tr')
   {
      $csv.="\"$row[studct2]\"\t";
      $peopleCol1="D"; $peopleCol2="E"; $peopleCol3="";
      $milesCol="F"; $tripsCol="G"; $rateCol="H";
      $mileageCol="I"; $nightsCol="J"; $lodgingCol="K";
   }
   else
   {
      $peopleCol1="D"; $peopleCol2=""; $peopleCol3="";
      $milesCol="E"; $tripsCol="F"; $rateCol="G";
      $mileageCol="H"; $nightsCol="I"; $lodgingCol="J";
      //VB:
   	$matchesCol="K"; $matchtotalCol="L";
   }
   $coachVar="C".$currow;
   $peopleVar1=$peopleCol1.$currow;
   $peopleVar2=$peopleCol2.$currow;
   $peopleVar3=$peopleCol3.$currow;
   $milesVar=$milesCol.$currow;
   $tripsVar=$tripsCol.$currow;
   $rateVar=$rateCol.$currow;
   $mileageVar=$mileageCol.$currow;
   $nightsVar=$nightsCol.$currow;
   $lodgingVar=$lodgingCol.$currow;
   //VB:
      $matchesVar=$matchesCol.$currow; $matchtotalVar=$matchtotalCol.$currow;

   $csv.="\"$row[mileage]\"\t\"$row[trips]\"\t";
   //MILEAGE RATE
   if($peopleCol2=="")	//just one set of people to count (not girls + boys)
   {
      $peopletotal="$coachVar+$peopleVar1";
      $rate="=IF($peopletotal>=31,5.10,IF($peopletotal>=25,4.25,IF($peopletotal>=19,3.40,IF($peopletotal>=13,2.55,IF($peopletotal>=7,1.7,0.85)))))";
   }
   else if($peopleCol3=="")	//just 2 sets
   {
      $peopletotal="$coachVar+$peopleVar1+$peopleVar2";
      //$rate="=IF(($coachVar+$peopleVar1+$peopleVar2)>=13,2.55,IF(($coachVar+$peopleVar1+$peopleVar2)>=7,1.7,0.85))";
      $rate="=IF($peopletotal>=31,5.10,IF($peopletotal>=25,4.25,IF($peopletotal>=19,3.40,IF($peopletotal>=13,2.55,IF($peopletotal>=7,1.7,0.85)))))";
   }
   else	//WRESTLING
   {
      $peopletotal="$coachVar+$peopleVar1+$peopleVar2+$peopleVar3";
      //$rate="=IF(($coachVar+$peopleVar1+$peopleVar2+$peopleVar3)>=13,2.55,IF(($coachVar+$peopleVar1+$peopleVar2+$peopleVar3)>=7,1.7,0.85))";
      $rate="=IF($peopletotal>=31,5.10,IF($peopletotal>=25,4.25,IF($peopletotal>=19,3.40,IF($peopletotal>=13,2.55,IF($peopletotal>=7,1.7,0.85)))))";
   }
   $csv.="\"$rate\"\t";
   //if($row[mileage]*$row[trips]<50) $mileage=0;
   //else
   $totalmiles=$row[mileage]*$row[trips];	//D1*E1
   $totalmiles-=50;
   $mileage="=IF(($milesVar*$tripsVar)-50<0,0,(($milesVar*$tripsVar)-50)*$rateVar)";
   //$mileage=number_format($mileage,2,'.','');
   $csv.="\"$mileage\"\t";
   $nights=0;
   for($i=1;$i<=8;$i++)
   {
      $var="lodging".$i;
      $nights+=$row[$var];
   }
   $csv.="\"$nights\"\t";
   if($peopleCol2=="")  //just one set of people to count (not girls + boys)
   {
      //$lodging="=15*($coachVar+$peopleVar1)*$nightsVar";
      $lodging="=20*($coachVar+$peopleVar1)*$nightsVar";
   }
   else if($peopleCol3=="")	//just 2 sets
   {
      //$lodging="=15*($coachVar+$peopleVar1+$peopleVar2)*$nightsVar";
      $lodging="=20*($coachVar+$peopleVar1+$peopleVar2)*$nightsVar";
   }
   else	//WR
   {
      //$lodging="=15*($coachVar+$peopleVar1+$peopleVar2+$peopleVar3)*$nightsVar";
      $lodging="=20*($coachVar+$peopleVar1+$peopleVar2+$peopleVar3)*$nightsVar";
   }
   //$lodging=number_format($lodging,2,'.',''); 
   $csv.="\"$lodging\"\t";
   if($sport=='vb')
   {
      $csv.="\"0\"\t";	//INITIALLY PUT IN 0 FOR MATCH COUNT - NSAA WILL ENTER THIS ON THEIR OWN
      $csv.="\"=$matchesVar*300\"\t";
      $total="=$mileageVar+$lodgingVar+$matchtotalVar";
   }
   else
      $total="=$mileageVar+$lodgingVar";
   $csv.="\"$total\"\r\n";
   $currow++;
}

//WRITE FILE & OPEN
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);

header("Location:exports.php?session=$session&filename=$filename");
exit();
?>
