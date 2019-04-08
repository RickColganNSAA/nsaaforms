<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

$csv="";
$fields=array("gender","hostschool","oppschool","totalrec","offfees","balance","bankamt","gamedate","datesent","datesub");
$totalrecSUM=0; $balanceSUM=0; $bankamtSUM=0;
for($i=0;$i<count($fields);$i++)
{
   $sql="SELECT ";
   if($fields[$i]=="offfees")
   {
      $sql.="offfees+offmileage AS offtotal";
   }
   else if($fields[$i]=="balance")
   {
      $sql.="totalrec-(offfees+offmileage) AS balance";
   }
   else
   {
      $sql.=$fields[$i];
   }
   $sql.=" FROM finance_hurr ORDER BY hostschool";
   $result=mysql_query($sql);
   if($i==0) $csv.="B/G/B&G,";
   else if($i==1) $csv.="Host Team,";
   else if($i==2) $csv.="Visiting Team,";
   else if($i==3) $csv.="Total Receipts,";
   else if($i==4) $csv.="Officials Fees & Mileage,";
   else if($i==5) $csv.="Balance,";
   else if($i==6) $csv.="Amount Sent to US Bank,";
   else if($i==7) $csv.="Game Date,";
   else if($i==8) $csv.="Date Sent to US Bank,";
   else if($i==9) $csv.="Date Submitted,";
   while($row=mysql_fetch_array($result))
   {
      if($i==0)
      {
	 if($row[0]=='m') $csv.="B,";
	 else if($row[0]=='f') $csv.="G,";
	 else $csv.="B&G,";
      }
      else if($i>2 && $i<7)
      {
	 $num=number_format($row[0],2,'.','');
	 $csv.=$num.",";
	 if($i==3) $totalrecSUM+=$num;
	 else if($i==5) $balanceSUM+=$num;
	 else if($i==6) $bankamtSUM+=$num;
      }
      else if($i>=7)
      {
	 $date=date("m/d/Y",$row[0]);
	 $csv.="$date,";
      }
      else
         $csv.=$row[0].",";
   }
   $csv.="\r\n";
}

$csv.="\r\n\r\n";
$csv.="Grand Total of Receipts,$totalrecSUM\r\n";
$csv.="Grand Total of Balance,$balanceSUM\r\n";
$csv.="Grand Total of Amount to US Bank,$bankamtSUM\r\n";

$open=fopen(citgf_fopen("financialreports/hurrexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("financialreports/hurrexport.csv");

header("Location:financialreports/hurrexport.csv");

echo $end_html;
?>
