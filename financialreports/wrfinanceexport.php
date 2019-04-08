<?php
//wrfinanceexport.php: export files of all wr financial report data

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
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

$wrclass=array("A","B","C","D");
$wrfiles=array("A","B","C","D");

$wrfields=array("hostschool","location","classdist","attendance","grossreceipts","offtotal","insurance","subbalance","nsaaallow","hostallow","balance","milesdue","milesduetot","prorate1","prorate2","milespaid","milespaidtot","bonus","hostbonus","visbonus","grossreceipts","offtotal","hosttotal","vistotal","nsaaallow","insurance","check","localmedia_bfee","othermedia_wfee","othermedia_tfee");

$wrtitles=array("Host","Site","Class/District","Attendance","Total Receipts","Officials","Insurance-10%","Sub-Balance","NSAA-25%","Host School-35%","Balance","Expenses","Total Expenses","% Paid","Prorate","% Paid Expenses","Total % Paid","Balance Bonus","Host's Bonus","Visitor's Bonus","Total Receipts","Officials","Host","Visitors","NSAA","Insurance","Check Amount","Local/Unaffiliated Broadcast Fees","Other/Affiliated Broadcast Fees","Other/Affiliated Telecast Fees");

   $totalatt['A']=0; $totalatt['B']=0; $totalatt['C']=0; $totalatt['D']=0;
   $totalrec['A']=0; $totalrec['B']=0; $totalrec['C']=0; $totalrec['D']=0;
   $totaloff['A']=0; $totaloff['B']=0; $totaloff['C']=0; $totaloff['D']=0;
   $totalhost['A']=0; $totalhost['B']=0; $totalhost['C']=0; $totalhost['D']=0;
   $totalvis['A']=0; $totalvis['B']=0; $totalvis['C']=0; $totalvis['D']=0;
   $totalnsaa['A']=0; $totalnsaa['B']=0; $totalnsaa['C']=0; $totalnsaa['D']=0;
   $totalins['A']=0; $totalins['B']=0; $totalins['C']=0; $totalins['D']=0;

for($i=0;$i<count($wrclass);$i++)
{
   $totallbfee[$wrclass[$i]]=0;
   $totalowfee[$wrclass[$i]]=0; 
   $totalotfee[$wrclass[$i]]=0;
}

// FOR EACH District and Sub-District, CREATE CSV FILE: 
for($file=0;$file<count($wrfiles);$file++)
{
   $curtotalatt=0;
   $curtotalrec=0;
   $curtotaloff=0;
   $curtotalhost=0;
   $curtotalvis=0;
   $curtotalnsaa=0;
   $curtotalins=0;
   $curtotallbfee=0;
   $curtotalowfee=0;
   $curtotalotfee=0;
   $csv="";

    $class=$wrfiles[$file];
    switch($class)
    {
       case "A":
          $max=8;
          break;
       case "B":
          $max=12;
          break;
       case "C":
          $max=16;
          break;
       default:
          $max=20;
    }

    // FOR EACH tournament in this district, CREATE column OF CSV FILE: 
    $sql="SELECT t1.*,t2.class,t2.district,t2.hostschool,t2.site FROM finance_wr AS t1,$db_name2.wrdistricts AS t2 WHERE t2.class='$class' AND t1.distid=t2.id ORDER BY t2.district";
    $result=mysql_query($sql);
    $tid=0;	//tournament id
    $tourn=array();
    while($row=mysql_fetch_array($result))
    {
       // GET DISTRICT INFO 
       $tourn[hostschool][$tid]=$row[hostschool];
       $tourn[classdist][$tid]=$class."-".$row[district];
       $tourn[district][$tid]=$row[district];
       $tourn[location][$tid]=$row[site];
       $tourn[attendance][$tid]=$row[attendance];
           $curtotalatt+=$row[attendance];
       $tourn[grossreceipts][$tid]=number_format($row[grossreceipts],2,'.','');
       $tourn[offtotal][$tid]=number_format($row[offtotal],2,'.','');
       $tourn[insurance][$tid]=number_format($row[insurance],2,'.','');
       $tourn[localmedia_bfee][$tid]=number_format($row[localmedia_bfee],2,'.','');
       $tourn[othermedia_wfee][$tid]=number_format($row[othermedia_wfee],2,'.','');
       $tourn[othermedia_tfee][$tid]=number_format($row[othermedia_tfee],2,'.','');
       $tourn[subbalance][$tid]=number_format($row[subbalance],2,'.','');
       $tourn[hostallow][$tid]=number_format($row[hostallow],2,'.','');
       $tourn[nsaaallow][$tid]=number_format($row[nsaaallow],2,'.','');
       $tourn[balance][$tid]=number_format($row[balance],2,'.','');
       $tourn[vismileagepaid][$tid]=number_format($row[vismileagepaid],2,'.','');
       $tourn[bonus][$tid]=number_format($row[bonus],2,'.','');
       $tourn[hostbonus][$tid]=number_format($row[hostbonus],2,'.','');
       $tourn[visbonus][$tid]=number_format($row[visbonus],2,'.','');
       $tourn[hosttotal][$tid]=number_format($row[hosttotal],2,'.','');
       $tourn[vistotal][$tid]=number_format($row[vistotal],2,'.','');

       // FOR EACH GAME's REPORT in finance_wr_exp, STORE INFO: 
       $sql2="SELECT * FROM finance_wr_exp WHERE school!='' AND distid='$row[distid]' ORDER BY school";
       $result2=mysql_query($sql2);
       $ix=0;
       $tourn[milesduetot][$tid]=0;
       $tourn[milespaidtot][$tid]=0;
       $parthost=0;
       while($row2=mysql_fetch_array($result2))
       {
	  if($row[school]==$row2[school])
	     $parthost=1;
          $tourn[milesdue][$tid][$ix]=number_format(trim($row2[mileagedue]),2,'.','');
	  $tourn[milespaid][$tid][$ix]=number_format(trim($row2[mileagepaid]),2,'.','');
	  $tourn[milesduetot][$tid]+=$tourn[milesdue][$tid][$ix];
	  $tourn[milespaidtot][$tid]+=$tourn[milespaid][$tid][$ix];
	  $ix++;
       }
       $tourn[prorate1][$tid]=number_format($tourn[balance][$tid]/$tourn[milesduetot][$tid],5,'.','');
       if($tourn[prorate1][$tid]>1) $tourn[prorate2][$tid]=1;
       else $tourn[prorate2][$tid]=$tourn[prorate1][$tid];

       $tid++;
    }  //end for each tournament
   
   // NOW TAKE STORED INFO AND OUTPUT TO CSV FILE 
   $curryear=date("Y");
   $filename="wr";
   if($round==1) $filename.="sub";
   $filename.="dist".$curryear;
   $filename.="_$class.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
 
   for($field=0;$field<count($wrfields);$field++)
   {
      // FOR EACH DATA FIELD
      if($wrfields[$field]=="milesdue")	//miles due to each team (non-prorated)
      {
	 for($i=0;$i<$max;$i++)	//for each possible team in tournament
	 {
	    //put expenses in csv file
	    $csv.="Expenses,";
	    for($t=0;$t<$tid;$t++)	//for each tournament
	    {
	       //put non-prorated mileage paid to $i-th school
	       $csv.=$tourn[milesdue][$t][$i].",";
	    }
	    $csv.="\r\n";
	 }
      }
      else if($wrfields[$field]=="milespaid")	//prorated miles paid
      {
	 for($i=0;$i<$max;$i++)
	 {
	    $csv.="Expenses,";
	    for($t=0;$t<$tid;$t++)
	    {
	       $csv.=$tourn[milespaid][$t][$i].",";
	    }
	    $csv.="\r\n";
	 }
      }
      else if($field==20)	//summary fields
      {
	 $csv.="\r\n";
	    $csv.="SUMMARY,";
	    for($i=0;$i<$tid;$i++)
	    {
	       $csv.=",";
	    }
	 $csv.="\r\n";
	 $csv.=$wrtitles[$field].",";
	 for($t=0;$t<$tid;$t++)
	 {
	    $csv.=$tourn[$wrfields[$field]][$t].",";
	 }
	 $csv.="\r\n";
      }
      else
      {
	 $csv.=$wrtitles[$field].",";
	 for($t=0;$t<$tid;$t++)
	 {
	    //for each tourn, put this field's data
	    $csv.=$tourn[$wrfields[$field]][$t].",";
	 }
	 $csv.="\r\n";
      }

      if($field>=20)	//summary fields
      {
	 for($t=0;$t<$tid;$t++)
	 {
	    if($wrfields[$field]=="grossreceipts")
	       $curtotalrec+=$tourn[$wrfields[$field]][$t];
	    else if($wrfields[$field]=="offtotal")
	       $curtotaloff+=$tourn[$wrfields[$field]][$t];
	    else if($wrfields[$field]=="hosttotal")
	       $curtotalhost+=$tourn[$wrfields[$field]][$t];
	    else if($wrfields[$field]=="vistotal")
	       $curtotalvis+=$tourn[$wrfields[$field]][$t];
	    else if($wrfields[$field]=="nsaaallow")
	       $curtotalnsaa+=$tourn[$wrfields[$field]][$t];
	    else if($wrfields[$field]=="insurance")
	       $curtotalins+=$tourn[$wrfields[$field]][$t];
            else if($wrfields[$field]=="localmedia_bfee")
               $curtotallbfee+=$tourn[$wrfields[$field]][$t];
            else if($wrfields[$field]=="othermedia_wfee")
               $curtotalowfee+=$tourn[$wrfields[$field]][$t];
            else if($wrfields[$field]=="othermedia_tfee")
               $curtotalotfee+=$tourn[$wrfields[$field]][$t];
	 }
      }

   }	//END FOR EACH FIELD
   
   //(sub) district totals section:
   $csv.="\r\nCLASS $class TOTALS\r\n";
   $csv.="Attendance,$curtotalatt\r\n";
   $csv.="Total Receipts,$curtotalrec\r\n";
   $csv.="Officials,$curtotaloff\r\n";
   $csv.="Host,$curtotalhost\r\n";
   $csv.="Teams,$curtotalvis\r\n";
   $csv.="NSAA,$curtotalnsaa\r\n";
   $csv.="Insurance,$curtotalins\r\n";
   $csv.="Local/Unaffiliated Broadcast Fees,$curtotallbfee\r\n";
   $csv.="Other/Affiliated Broadcast Fees,$curtotalowfee\r\n";
   $csv.="Other/Affiliated Telecast Fees,$curtotalotfee\r\n";

   //update overall totals for subdistricts, district finals, class a, class b
   $totalatt[$class]+=$curtotalatt;
   $totalrec[$class]+=$curtotalrec;
   $totaloff[$class]+=$curtotaloff;
   $totalhost[$class]+=$curtotalhost;
   $totalvis[$class]+=$curtotalvis;
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
$csv.="$curryear SUMMARY DISTRICT WRESTLING RECEIPTS\r\n\r\n\r\n\r\n";
$csv.=",Class A,Class B,Class C,Class D\r\n";
$csv.="Attendance,".$totalatt['A'].",".$totalatt['B'].",".$totalatt['C'].",".$totalatt['D']."\r\n";
$csv.="Total Receipts,".number_format($totalrec['A'],2,'.','').",".number_format($totalrec['B'],2,'.','').",".number_format($totalrec['C'],2,'.','').",".number_format($totalrec['D'],2,'.','')."\r\n";
$csv.="Officials,".number_format($totaloff['A'],2,'.','').",".number_format($totaloff['B'],2,'.','').",".number_format($totaloff['C'],2,'.','').",".number_format($totaloff['D'],2,'.','')."\r\n";
$csv.="Hosts,".number_format($totalhost['A'],2,'.','').",".number_format($totalhost['B'],2,'.','').",".number_format($totalhost['C'],2,'.','').",".number_format($totalhost['D'],2,'.','')."\r\n";
$csv.="Visitors,".number_format($totalvis['A'],2,'.','').",".number_format($totalvis['B'],2,'.','').",".number_format($totalvis['C'],2,'.','').",".number_format($totalvis['D'],2,'.','')."\r\n";
$csv.="NSAA,".number_format($totalnsaa['A'],2,'.','').",".number_format($totalnsaa['B'],2,'.','').",".number_format($totalnsaa['C'],2,'.','').",".number_format($totalnsaa['D'],2,'.','')."\r\n";
$csv.="Insurance,".number_format($totalins['A'],2,'.','').",".number_format($totalins['B'],2,'.','').",".number_format($totalins['C'],2,'.','').",".number_format($totalins['D'],2,'.','')."\r\n";
$csv.="Local/Unaffiliated Broadcast Fees,".number_format($totallbfee['A'],2,'.','').",".number_format($totallbfee['B'],2,'.','').",".number_format($totallbfee['C'],2,'.','').",".number_format($totallbfee['D'],2,'.','')."\r\n";
$csv.="Other/Affiliated Broadcast Fees,".number_format($totalowfee['A'],2,'.','').",".number_format($totalowfee['B'],2,'.','').",".number_format($totalowfee['C'],2,'.','').",".number_format($totalowfee['D'],2,'.','')."\r\n";
$csv.="Other/Affiliated Telecast Fees,".number_format($totalotfee['A'],2,'.','').",".number_format($totalotfee['B'],2,'.','').",".number_format($totalotfee['C'],2,'.','').",".number_format($totalotfee['D'],2,'.','')."\r\n";

$totalatt_all=$totalatt['A']+$totalatt['B']+$totalatt['C']+$totalatt['D'];
$totalrec_all=number_format($totalrec['A']+$totalrec['B']+$totalrec['C']+$totalrec['D'],2,'.','');
$totaloff_all=number_format($totaloff['A']+$totaloff['B']+$totaloff['C']+$totaloff['D'],2,'.','');
$totalhost_all=number_format($totalhost['A']+$totalhost['B']+$totalhost['C']+$totalhost['D'],2,'.','');
$totalvis_all=number_format($totalvis['A']+$totalvis['B']+$totalvis['C']+$totalvis['D'],2,'.','');
$totalnsaa_all=number_format($totalnsaa['A']+$totalnsaa['B']+$totalnsaa['C']+$totalnsaa['D'],2,'.','');
$totalins_all=number_format($totalins['A']+$totalins['B']+$totalins['C']+$totalins['D'],2,'.','');
$totallbfee_all=number_format($totallbfee['A']+$totallbfee['B']+$totallbfee['C']+$totallbfee['D'],2,'.','');
$totalowfee_all=number_format($totalowfee['A']+$totalowfee['B']+$totalowfee['C']+$totalowfee['D'],2,'.','');
$totalotfee_all=number_format($totalotfee['A']+$totalotfee['B']+$totalotfee['C']+$totalotfee['D'],2,'.','');
$csv.="\r\n\r\nGRAND TOTALS:\r\n";
$csv.="Attendance,$totalatt_all\r\n";
$csv.="Total Receipts,$totalrec_all\r\n";
$csv.="Officials,$totaloff_all\r\n";
$csv.="Host,$totalhost_all\r\n";
$csv.="VIsitors,$totalvis_all\r\n";
$csv.="NSAA,$totalnsaa_all\r\n";
$csv.="Insurance,$totalins_all\r\n";
$csv.="Local/Unaffiliated Broadcast Fees,$totallbfee_all\r\n";
$csv.="Other/Affiliated Broadcast Fees,$totalowfee_all\r\n";
$csv.="Other/Affiliated Telecast Fees,$totalotfee_all";

$filename="wrsummary$curryear.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
echo "<a href=\"../exports.php?session=$session&filename=$filename\" target=new>$filename</a><br>";
?>
