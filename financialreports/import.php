<?php
/********************************/
$round=4;
$filename="FBRound42003.csv";
$classdist="D2";
$begin=13; $end=15;
/**********************************/

require '../functions.php';
require '../variables.php';

$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$open=fopen(citgf_fopen($filename),"r");
$line=file(getbucketurl($filename));
for($i=0;$i<count($line);$i++)
{
   $line[$i]=ereg_replace("\"","",$line[$i]);
   $line[$i]=ereg_replace("[$]","",$line[$i]);
   $line[$i]=trim($line[$i]);
}
fclose($open);

$schools=split(",",$line[1]);
$visitors=split(",",$line[2]);
$grossreceipts=split(",",$line[5]);
$offfees=split(",",$line[6]);
for($i=0;$i<count($offfees);$i++)
{
   $offfees[$i]-=7.6;
   $offmiles[$i]=10;
   $offmilespaid[$i]=7.60;
}
$insdeduct=split(",",$line[7]);
$balance=split(",",$line[8]);
$hostallow=split(",",$line[9]);
$nsaaallow=split(",",$line[10]);
$visitormilespaid=split(",",$line[11]);
for($i=0;$i<count($visitormilespaid);$i++)
{
   $visitormiles[$i]=($visitormilespaid[$i]/4.2)+50;
}
$visitorpaid=split(",",$line[12]);
$distribution=split(",",$line[13]);
$prorate=split(",",$line[15]);
$hostallowpro=split(",",$line[16]);
$nsaaallowpro=split(",",$line[17]);
$visitorpaidpro=split(",",$line[18]);
$distributionpro=split(",",$line[19]);
$bonus=split(",",$line[20]);
$hostbonus=split(",",$line[21]);
$visitorbonus=split(",",$line[22]);
$nsaabonus=split(",",$line[23]);

for($i=$begin;$i<$end;$i++)
{
   $schools[$i]=ereg_replace("\'","\'",$schools[$i]);
   $visitors[$i]=ereg_replace("\'","\'",$visitors[$i]);
  $sql="INSERT INTO finance_fb (school,classdist,round,location,attendance,visitor,grossreceipts,offfees,offmiles,offmilespaid,visitormiles,insdeduct,balance,hostallow,nsaaallow,visitorpaid,distribution,prorate,hostallowpro,nsaaallowpro,visitorpaidpro,distributionpro,bonus,hostbonus,visitorbonus,nsaabonus) VALUES ('$schools[$i]','$classdist','$round','$schools[$i]','','$visitors[$i]','$grossreceipts[$i]','$offfees[$i]','$offmiles[$i]','$offmilespaid[$i]','$visitormiles[$i]','$insdeduct[$i]','$balance[$i]','$hostallow[$i]','$nsaaallow[$i]','$visitorpaid[$i]','$distribution[$i]','$prorate[$i]','$hostallowpro[$i]','$nsaaallowpro[$i]','$visitorpaidpro[$i]','$distributionpro[$i]','$bonus[$i]','$hostbonus[$i]','$visitorbonus[$i]','$nsaabonus[$i]')";
  $result=mysql_query($sql);
  echo "$sql<br>";
  echo mysql_error();
}

?>
