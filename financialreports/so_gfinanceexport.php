<?php
//so_bfinanceexport.php: export files of all so_b financial report data

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$sport="so_g";
$sport2="sog";
$sportname="Girls Soccer";

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

$sql="SELECT DISTINCT class FROM ".$db_name2.".".$sport2."districts WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
$ix=0;
$sofiles=array();
while($row=mysql_fetch_array($result))
{
   $sofiles[$ix]=$row['class'];
   $ix++;
}

$sofields=array("hostschool","location","classdist","attendance","grossreceipts","offtotal","insurance","balance","nsaaallow","hostallow","check","localmedia_bfee","othermedia_wfee","othermedia_tfee");
$sotitles=array("Host","Site","District/Substate","Attendance","Total Receipts","Officials","Insurance-10%","Balance","NSAA-25%","Host School-75%","Check Amount","Local/Unaffiliated Broadcasts","Other/Affiliated Broadcasts","Other/Affiliated Telecasts");

for($i=0;$i<count($sofiles);$i++)
{
   $curclass=$sofiles[$i];
   $totalrec[$curclass]=0; $totaloff[$curclass]=0; $totalhost[$curclass]=0; 
   $totalvis[$curclass]=0; $totalnsaa[$curclass]=0; $totalins[$curclass]=0;
   $totalatt[$curclass]=0;
   $totallbfee[$curclass]=0; $totalowfee[$curclass]=0; $totalotfee[$curclass]=0;
}

// FOR EACH District, CREATE CSV FILE: 
for($file=0;$file<count($sofiles);$file++)
{
   $curtotalrec=0;
   $curtotalatt=0;
   $curtotaloff=0;
   $curtotalhost=0;
   $curtotalvis=0;
   $curtotalnsaa=0;
   $curtotalins=0;
   $curtotallbfee=0; $curtotalowfee=0; $curtotalotfee=0;
   $csv="";

    $class=$sofiles[$file];

    // FOR EACH tournament in this district, CREATE column OF CSV FILE: 
    $sql="SELECT t1.*,t2.class,t2.type,t2.district,t2.hostschool,t2.site FROM finance_".$sport." AS t1,".$db_name2.".".$sport2."districts AS t2 WHERE t2.class='$class' AND t1.distid=t2.id ORDER BY t2.type,t2.district";
    $result=mysql_query($sql);
    $tid=0;	//tournament id
    $tourn=array();
    while($row=mysql_fetch_array($result))
    {
       // GET DISTRICT INFO 
       $tourn[type][$tid]=$row[type];
       $tourn[hostschool][$tid]=$row[hostschool];
       $tourn[winner][$tid]=$row[winner];
       $tourn[runnerup][$tid]=$row[runnerup];
       $tourn[classdist][$tid]=$row[type]." ".$class."-".$row[district];
       $tourn[district][$tid]=$row[district];
       $tourn[location][$tid]=$row[site];
       $tourn[attendance][$tid]=$row[attendance];
       $tourn[grossreceipts][$tid]=number_format($row[grossreceipts],2,'.','');
       $tourn[offtotal][$tid]=number_format($row[offtotal],2,'.','');
       $tourn[insurance][$tid]=number_format($row[insurance],2,'.','');
       $tourn[balance][$tid]=number_format($row[balance],2,'.','');
       $tourn[hostallow][$tid]=number_format($row[hostallow],2,'.','');
       $tourn[nsaaallow][$tid]=number_format($row[nsaaallow],2,'.','');
       $tourn[localmedia_bfee][$tid]=number_format($row[localmedia_bfee],2,'.','');
       $tourn[othermedia_wfee][$tid]=number_format($row[othermedia_wfee],2,'.','');
       $tourn[othermedia_tfee][$tid]=number_format($row[othermedia_tfee],2,'.','');

       $tid++;
    }  //end for each tournament
   
   // NOW TAKE STORED INFO AND OUTPUT TO CSV FILE 
   $curryear=date("Y");
   $filename=strtoupper($sport2).$curryear."_".$class.".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
 
   for($field=0;$field<count($sofields);$field++)
   {
      // FOR EACH DATA FIELD
      $csv.=$sotitles[$field].",";
      for($t=0;$t<$tid;$t++)
      {
         //for each tourn, put this field's data
         $csv.=$tourn[$sofields[$field]][$t].",";

         if($sofields[$field]=="grossreceipts")
            $curtotalrec+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="attendance")
            $curtotalatt+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="offtotal")
            $curtotaloff+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="hostallow")
            $curtotalhost+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="nsaaallow")
            $curtotalnsaa+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="insurance")
            $curtotalins+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="localmedia_bfee")
            $curtotallbfee+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="othermedia_wfee")
            $curtotalowfee+=$tourn[$sofields[$field]][$t];
         else if($sofields[$field]=="othermedia_tfee")
            $curtotalotfee+=$tourn[$sofields[$field]][$t];
      }
      $csv.="\r\n";
   }	//END FOR EACH FIELD
   
   //district totals section:
   $csv.="\r\nCLASS $class TOTALS\r\n";
   $csv.="Attendance,$curtotalatt\r\n";
   $csv.="Total Receipts,$curtotalrec\r\n";
   $csv.="Officials,$curtotaloff\r\n";
   $csv.="Host,$curtotalhost\r\n";
   $csv.="NSAA,$curtotalnsaa\r\n";
   $csv.="Insurance,$curtotalins\r\n";
   $csv.="Local/Unaffiliated Broadcasts,$curtotallbfee\r\n";
   $csv.="Other/Affiliated Broadcasts,$curtotalowfee\r\n";
   $csv.="Other/Affiliated Telecasts,$curtotalotfee\r\n";

   //update overall totals for districts
   $totalatt[$class]+=$curtotalatt;
   $totalrec[$class]+=$curtotalrec;
   $totaloff[$class]+=$curtotaloff;
   $totalhost[$class]+=$curtotalhost;
   $totalnsaa[$class]+=$curtotalnsaa;
   $totalins[$class]+=$curtotalins;
   $totallbfee[$class]+=$curtotallbfee;
   $totalowfee[$class]+=$curtotalowfee;
   $totalotfee[$class]+=$curtotalotfee;

   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<a href=\"../exports.php?session=$session&filename=$filename\" target=new>$filename</a><br>";
}
//SUMMARY CSV FILE:
$csv="";
$csv.="$curryear SUMMARY ".strtoupper($sportname)." RECEIPTS\r\n\r\n\r\n\r\n,";
for($i=0;$i<count($sofiles);$i++)
{
   $csv.="Class ".$sofiles[$i].",";
}
$csv.="\r\n";
$csv.="Total Attendance,".$totalatt['A'].",".$totalatt['B']."\r\n";
$csv.="Total Receipts,".number_format($totalrec['A'],2,'.','').",".number_format($totalrec['B'],2,'.','')."\r\n";
$csv.="Officials,".number_format($totaloff['A'],2,'.','').",".number_format($totaloff['B'],2,'.','')."\r\n";
$csv.="Hosts,".number_format($totalhost['A'],2,'.','').",".number_format($totalhost['B'],2,'.','')."\r\n";
$csv.="NSAA,".number_format($totalnsaa['A'],2,'.','').",".number_format($totalnsaa['B'],2,'.','')."\r\n";
$csv.="Insurance,".number_format($totalins['A'],2,'.','').",".number_format($totalins['B'],2,'.','')."\r\n";
$csv.="Local/Unaffiliated Broadcasts,".number_format($totallbfee['A'],2,'.','').",".number_format($totallbfee['B'],2,'.','')."\r\n";
$csv.="Other/Affiliated Broadcasts,".number_format($totalowfee['A'],2,'.','').",".number_format($totalowfee['B'],2,'.','')."\r\n";
$csv.="Other/Affiliated Telecasts,".number_format($totalotfee['A'],2,'.','').",".number_format($totalotfee['B'],2,'.','')."\r\n";

$totalatt_all=number_format($totalatt['A']+$totalatt['B'],2,'.','');
$totalrec_all=number_format($totalrec['A']+$totalrec['B'],2,'.','');
$totaloff_all=number_format($totaloff['A']+$totaloff['B'],2,'.','');
$totalhost_all=number_format($totalhost['A']+$totalhost['B'],2,'.','');
$totalnsaa_all=number_format($totalnsaa['A']+$totalnsaa['B'],2,'.','');
$totalins_all=number_format($totalins['A']+$totalins['B'],2,'.','');
$totallbfee_all=number_format($totallbfee['A']+$totallbfee['B'],2,'.','');
$totalowfee_all=number_format($totalowfee['A']+$totalowfee['B'],2,'.','');
$totalotfee_all=number_format($totalotfee['A']+$totalotfee['B'],2,'.','');
$csv.="\r\n\r\nGRAND TOTALS:\r\n";
$csv.="Total Attendance,$totalatt_all\r\n";
$csv.="Total Receipts,$totalrec_all\r\n";
$csv.="Officials,$totaloff_all\r\n";
$csv.="Host,$totalhost_all\r\n";
$csv.="NSAA,$totalnsaa_all\r\n";
$csv.="Insurance,$totalins_all\r\n";
$csv.="Local/Unaffiliated Broadcasts,$totallbfee_all\r\n";
$csv.="Other/Affiliated Broadcasts,$totalowfee_all\r\n";
$csv.="Other/Affiliated Telecasts,$totalotfee_all\r\n";

$filename=strtoupper($sport2)."summary$curryear.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
echo "<a href=\"../exports.php?session=$session&filename=$filename\" target=new>$filename</a><br>";
?>
