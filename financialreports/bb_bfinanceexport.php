<?php
//bb_bfinanceexport.php: export file of all bb_b financial report data

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
$officialsdb=$db_name2;

$bbclass=array("A","B","C1","C2","D1","D2");
$bbfiles=array("C1Sub","C2Sub","D1Sub","D2Sub","C1","C2","D1","D2","B","A");

$bbfields=array("classdist","hostschool","attendance","grossreceipts","offtotal","insurance","hostgiven","hostallow","nsaaallow","balance","mileagedue","prorate","mileagepaid","bonus","hostperc","hostbonus","nsaabonus","visperc","visbonus","grossreceipts","offtotal","hosttotal","vistotal","nsaatotal","insurance","nsaacheck","localmedia_bfee","othermedia_wfee","othermedia_tfee");

$bbtitles=array("District","Host School","Attendance","Total Receipts","Officials","Insurance-10%","Host Allowance","Host School","NSAA","Balance","Expenses","Prorate","Expenses","Balance Bonus","% Host","Host School","NSAA","% Teams","Teams","Total Receipts","Officials","Host","Teams","NSAA","Insurance","Check Amount","Local/Unaffiliated Broadcast Fees","Other/Affiliated Webcast Fees","Other/Affiliated Telecast Fees");

//set overall summary total variables to 0
$totalatt_sub=0; $totalatt_dist=0; $totalatt_a=0; $totalatt_b=0;
$totalrec_sub=0; $totalrec_dist=0; $totalrec_a=0; $totalrec_b=0;
$totaloff_sub=0; $totaloff_dist=0; $totaloff_a=0; $totaloff_b=0;
$totalhost_sub=0; $totalhost_dist=0; $totalhost_a=0; $totalhost_b=0;
$totalvis_sub=0; $totalvis_dist=0; $totalvis_a=0; $totalvis_b=0;
$totalnsaa_sub=0; $totalnsaa_dist=0; $totalnsaa_a=0; $totalnsaa_b=0;
$totalins_sub=0; $totalins_dist=0; $totalins_a=0; $totalins_b=0;
$totallbfee_sub=0; $totallbfee_dist=0; $totallbfee_a=0; $totallbfee_b=0;
$totalowfee_sub=0; $totalowfee_dist=0; $totalowfee_a=0; $totalowfee_b=0;
$totalotfee_sub=0; $totalotfee_dist=0; $totalotfee_a=0; $totalotfee_b=0;

// FOR EACH District and Sub-District, CREATE CSV FILE: 
for($file=0;$file<count($bbfiles);$file++)
{
    $csv="";

    //(sub)district TOTAL VARIABLES:
    $totalatt=0;
    $totalrec=0;
    $totaloff=0;
    $totalhost=0;
    $totalvis=0;
    $totalnsaa=0;
    $totalins=0;
    $totallbfee=0; $totalowfee=0; $totalotfee=0;

    //get title of round and class
    if(ereg("Sub",$bbfiles[$file]))
    {
       $round=1;
       $class=substr($bbfiles[$file],0,2);
       $type="Subdistrict";
    }
    else
    {
       $round=2;
       $class=$bbfiles[$file];
       if($class=='A' || $class=='B') $type="District";
       else $type="District Final";
    }

    // FOR EACH tournament in this (sub)district, CREATE column OF CSV FILE: 
    $sql="SELECT t1.*,t2.id AS distid,t2.class,t2.district,t2.hostschool,t2.site FROM finance_bb_b AS t1,$officialsdb.bbbdistricts AS t2 WHERE t2.class='$class' AND t1.distid=t2.id AND t2.type='$type' ORDER BY t2.district";
    $result=mysql_query($sql);
    $tid=0;	//tournament id
    $tourn=array();
    while($row=mysql_fetch_array($result))
    {
       // GET (SUB)DISTRICT INFO: 
       $tourn[hostschool][$tid]=$row[school];
       $tourn[classdist][$tid]=$class."-".$row[district];
       $tourn[district][$tid]=$row[district];
       $tourn[location][$tid]=$row[site];
       $tourn[attendance][$tid]=$row[attendance];
       $tourn[grossreceipts][$tid]=$row[grossreceipts];
       $tourn[offtotal][$tid]=number_format($row[offtotal],2,'.','');
       $tourn[insurance][$tid]=number_format($row[insurance],2,'.','');
       $tourn[hostgiven][$tid]=number_format($row[hostgiven],2,'.','');
       $tourn[hostallow][$tid]=number_format($row[hostallow],2,'.','');
       $tourn[nsaaallow][$tid]=number_format($row[nsaaallow],2,'.','');
       $tourn[balance][$tid]=number_format($row[balance],2,'.','');
       //$tourn[prorate][$tid]=number_format(($row[balance]/$row[vismileagepaid]),4,'.','');
       //if($tourn[prorate][$tid]>1)
	  //$tourn[prorate][$tid]="1.0000";
       $tourn[vismileagepaid][$tid]=number_format($row[vismileagepaid],2,'.','');
       if($row[hostpart]=='x')	//participating host
       {
	  $tourn[visperc][$tid]="90%";
     	  $tourn[hostperc][$tid]="0%";
       }
       else
       {
	  $tourn[hostperc][$tid]="15%";
	  $tourn[visperc][$tid]="75%";
       }
       $tourn[hostbonus][$tid]=number_format($row[hostbonus],2,'.','');
       $tourn[bonus][$tid]=number_format($row[bonus],2,'.','');
       $tourn[nsaabonus][$tid]=number_format($row[nsaabonus],2,'.','');
       $tourn[visbonus][$tid]=number_format($row[visbonus],2,'.','');
       $tourn[hosttotal][$tid]=number_format($row[hosttotal],2,'.','');
       $tourn[vistotal][$tid]=number_format($row[vistotal],2,'.','');
       $tourn[nsaatotal][$tid]=number_format($row[nsaatotal],2,'.','');
       $tourn[nsaacheck][$tid]=number_format($tourn[nsaatotal][$tid]+$tourn[insurance][$tid],2,'.','');
       $tourn[localmedia_bfee][$tid]=number_format($row[localmedia_bfee],2,'.','');
       $tourn[othermedia_wfee][$tid]=number_format($row[othermedia_wfee],2,'.','');
       $tourn[othermedia_tfee][$tid]=number_format($row[othermedia_tfee],2,'.','');
       $distid=$row[distid];

       // FOR EACH GAME's REPORT in finance_bb_b_exp, STORE INFO: 
       $sql2="SELECT * FROM finance_bb_b_exp WHERE distid='$distid' ORDER BY school";
       $result2=mysql_query($sql2);
       $ix=0;
       $tourn[milesduetot][$tid]=0;
       $tourn[milespaidtot][$tid]=0;
       $parthost=0;
       while($row2=mysql_fetch_array($result2))
       {
          $tourn[mileagedue][$tid][$ix]=number_format(trim($row2[mileagedue]),2,'.','');
	  $tourn[mileagepaid][$tid][$ix]=number_format(trim($row2[mileagepaid]),2,'.','');
	  $tourn[milesduetot][$tid]+=$tourn[mileagedue][$tid][$ix];
	  $tourn[milespaidtot][$tid]+=$tourn[mileagepaid][$tid][$ix];
	  $ix++;
       }
       $tourn[prorate][$tid]=number_format(($tourn[milespaidtot][$tid]/$tourn[milesduetot][$tid]),4,'.','');
       if($tourn[prorate][$tid]>1)
          $tourn[prorate][$tid]="1.0000";

       $tid++;
    }  //end for each tournament
   
   // NOW TAKE STORED INFO AND OUTPUT TO CSV FILE 
   $curryear=date("Y");
   $filename="bb_b";
   if($round==1) $filename.="sub";
   $filename.="dist".$curryear;
   $filename.="_$class.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
 
   for($field=0;$field<count($bbfields);$field++)
   {
      // FOR EACH DATA FIELD
      if($bbfields[$field]=="mileagedue")	//miles paid to each team (non-prorated)
      {
	 for($i=0;$i<6;$i++)	//for each possible team in tournament
	 {
	    //put expenses in csv file
	    $csv.="Expenses,";
	    for($t=0;$t<$tid;$t++)	//for each tournament
	    {
	       //put non-prorated mileage paid to $i-th school
	       $csv.=$tourn[mileagedue][$t][$i].",";
	    }
	    $csv.="\r\n";
	 }
	 $csv.="Total Expenses,";
	 for($t=0;$t<$tid;$t++)	//for each tournament, put total milage due
	 {
	    $csv.=$tourn[milesduetot][$t].",";
	 }
	 $csv.="\r\n";
      }
      else if($bbfields[$field]=="mileagepaid")	//prorated miles paid
      {
	 for($i=0;$i<6;$i++)
	 {
	    $csv.="Expenses,";
	    for($t=0;$t<$tid;$t++)
	    {
	       $csv.=$tourn[mileagepaid][$t][$i].",";
	    }
	    $csv.="\r\n";
	 }
	 $csv.="Expenses Paid,";
         for($t=0;$t<$tid;$t++) //for each tournament, put total milage paid
         {
            $csv.=$tourn[milespaidtot][$t].",";
         }
         $csv.="\r\n";
      }
      else if($field==18)	//summary fields
      {
	 $csv.="\r\n";
	 $csv.="SUMMARY,";
	 for($i=0;$i<$tid;$i++)
	 {
	    $csv.=",";
	  }
	 $csv.="\r\n";
	 $csv.=$bbtitles[$field].",";
	 for($t=0;$t<$tid;$t++)
	 {
	    $csv.=$tourn[$bbfields[$field]][$t].",";
	    //add to gross receipts total for this (sub)district
	 }
	 $csv.="\r\n";
      }
      else
      {
	 $csv.=$bbtitles[$field].",";
	 for($t=0;$t<$tid;$t++)
	 {
	    //for each tourn, put this field's data
	    $csv.=$tourn[$bbfields[$field]][$t].",";
	 }
	 $csv.="\r\n";
      }

      if($field>=18)	//summary fields
      {
	 for($t=0;$t<$tid;$t++)
	 {
	    if($bbfields[$field]=="grossreceipts")
	       $totalrec+=$tourn[$bbfields[$field]][$t];
	    else if($bbfields[$field]=="offtotal")
	       $totaloff+=$tourn[$bbfields[$field]][$t];
	    else if($bbfields[$field]=="hosttotal")
	       $totalhost+=$tourn[$bbfields[$field]][$t];
	    else if($bbfields[$field]=="vistotal")
	       $totalvis+=$tourn[$bbfields[$field]][$t];
	    else if($bbfields[$field]=="nsaatotal")
	       $totalnsaa+=$tourn[$bbfields[$field]][$t];
	    else if($bbfields[$field]=="insurance")
	       $totalins+=$tourn[$bbfields[$field]][$t];
            else if($bbfields[$field]=="localmedia_bfee")	//LOCAL BROADCASTS FEES
               $totallbfee+=$tourn[$bbfields[$field]][$t];
            else if($bbfields[$field]=="othermedia_wfee")	//OTHER WEBCAST FEES
               $totalowfee+=$tourn[$bbfields[$field]][$t];
            else if($bbfields[$field]=="othermedia_tfee")	//OTHER TELECAST FEES
               $totalotfee+=$tourn[$bbfields[$field]][$t];
	 }
      }

   }	//END FOR EACH FIELD
   //total attendance
   for($t=0;$t<$tid;$t++)
   {
      $totalatt+=$tourn[attendance][$t];
   }
   
   //(sub) district totals section:
   $csv.="\r\n$class TOTALS\r\n";
   $csv.="Attendance,$totalatt\r\n";
   $csv.="Total Receipts,$totalrec\r\n";
   $csv.="Officials,$totaloff\r\n";
   $csv.="Host,$totalhost\r\n";
   $csv.="Teams,$totalvis\r\n";
   $csv.="NSAA,$totalnsaa\r\n";
   $csv.="Insurance,$totalins\r\n";
   $csv.="Local/Unaffiliated Broadcasts,$totallbfee\r\n";
   $csv.="Other/Affiliated Webcasts,$totalowfee\r\n";
   $csv.="Other/Affiliated Telecasts,$totalotfee\r\n";

   //update overall totals for subdistricts, district finals, class a, class b
   if($round==1 && (ereg("C",$class) || ereg("D",$class)))  //Sub Districts
   {
      $totalatt_sub+=$totalall;
      $totalrec_sub+=$totalrec;
      $totaloff_sub+=$totaloff;
      $totalhost_sub+=$totalhost;
      $totalvis_sub+=$totalvis;
      $totalnsaa_sub+=$totalnsaa;
      $totalins_sub+=$totalins;
      $totallbfee_sub+=$totallbfee;
      $totalowfee_sub+=$totalowfee;
      $totalotfee_sub+=$totalotfee;
   }
   else if($round==2 && (ereg("C",$class) || ereg("D",$class))) //C/D Districts
   {
      $totalatt_dist+=$totalatt;
      $totalrec_dist+=$totalrec;
      $totaloff_dist+=$totaloff;
      $totalhost_dist+=$totalhost;
      $totalvis_dist+=$totalvis;
      $totalnsaa_dist+=$totalnsaa;
      $totalins_dist+=$totalins;
      $totallbfee_dist+=$totallbfee;
      $totalowfee_dist+=$totalowfee;
      $totalotfee_dist+=$totalotfee;
   }
   else if($round==2 && ereg("A",$class)) //Class A District
   {
      $totalatt_a+=$totalatt;
      $totalrec_a+=$totalrec;
      $totaloff_a+=$totaloff;
      $totalhost_a+=$totalhost;
      $totalvis_a+=$totalvis;
      $totalnsaa_a+=$totalnsaa;
      $totalins_a+=$totalins;
      $totallbfee_a+=$totallbfee;
      $totalowfee_a+=$totalowfee;
      $totalotfee_a+=$totalotfee;
   }
   else	//Class B District
   {
      $totalatt_b+=$totalatt;
      $totalrec_b+=$totalrec;
      $totaloff_b+=$totaloff;
      $totalhost_b+=$totalhost;
      $totalvis_b+=$totalvis;
      $totalnsaa_b+=$totalnsaa;
      $totalins_b+=$totalins;
      $totallbfee_b+=$totallbfee;
      $totalowfee_b+=$totalowfee;
      $totalotfee_b+=$totalotfee;
   }

   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<a href=\"../exports.php?session=$session&filename=$filename\" target=new>$filename</a><br>";
}

//SUMMARY CSV FILE:
$csv="";
$csv.="$curryear SUMMARY DISTRICT BOYS BASKETBALL RECEIPTS\r\n\r\n\r\n\r\n";
$csv.=",Subdistricts,District Finals,Class A,Class B\r\n";
$csv.="Attendance,$totalatt_sub,$totalatt_dist,$totalatt_a,$totalatt_b\r\n";
$csv.="Total Receipts,$totalrec_sub,$totalrec_dist,$totalrec_a,$totalrec_b\r\n";
$csv.="Officials,$totaloff_sub,$totaloff_dist,$totaloff_a,$totaloff_b\r\n";
$csv.="Host,$totalhost_sub,$totalhost_dist,$totalhost_a,$totalhost_b\r\n";
$csv.="Teams,$totalvis_sub,$totalvis_dist,$totalvis_a,$totalvis_b\r\n";
$csv.="NSAA,$totalnsaa_sub,$totalnsaa_dist,$totalnsaa_a,$totalnsaa_b\r\n";
$csv.="Insurance,$totalins_sub,$totalins_dist,$totalins_a,$totalins_b\r\n";
$csv.="Local/Unaffiliated Broadcast Fees,$totallbfee_sub,$totallbfee_dist,$totallbfee_a,$totallbfee_b\r\n";
$csv.="Other/Affiliated Webcast Fees,$totalowfee_sub,$totalowfee_dist,$totalowfee_a,$totalowfee_b\r\n";
$csv.="Other/Affiliated Telecast Fees,$totalotfee_sub,$totalotfee_dist,$totalotfee_a,$totalotfee_b\r\n";

$totalatt_all=$totalatt_sub+$totalatt_dist+$totalatt_a+$totalatt_b;
$totalrec_all=$totalrec_sub+$totalrec_dist+$totalrec_a+$totalrec_b;
$totaloff_all=$totaloff_sub+$totaloff_dist+$totaloff_a+$totaloff_b;
$totalhost_all=$totalhost_sub+$totalhost_dist+$totalhost_a+$totalhost_b;
$totalvis_all=$totalvis_sub+$totalvis_dist+$totalvis_a+$totalvis_b;
$totalnsaa_all=$totalnsaa_sub+$totalnsaa_dist+$totalnsaa_a+$totalnsaa_b;
$totalins_all=$totalins_sub+$totalins_dist+$totalins_a+$totalins_b;
$totallbfee_all=$totallbfee_sub+$totallbfee_dist+$totallbfee_a+$totallbfee_b;
$totalowfee_all=$totalowfee_sub+$totalowfee_dist+$totalowfee_a+$totalowfee_b;
$totalotfee_all=$totalotfee_sub+$totalotfee_dist+$totalotfee_a+$totalotfee_b;

$csv.="\r\n\r\nGRAND TOTALS:\r\n";
$csv.="Attendance,$totalatt_all\r\n";
$csv.="Total Receipts,$totalrec_all\r\n";
$csv.="Officials,$totaloff_all\r\n";
$csv.="Host,$totalhost_all\r\n";
$csv.="Teams,$totalvis_all\r\n";
$csv.="NSAA,$totalnsaa_all\r\n";
$csv.="Insurance,$totalins_all\r\n";
$csv.="Local/Unaffiliated Broadcast Fees,$totallbfee_all\r\n";
$csv.="Other/Affiliated Webcast Fees,$totalowfee_all\r\n";
$csv.="Other/Affiliated Telecast Fees,$totalotfee_all\r\n";

$filename="bb_bsummary$curryear.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
echo "<a href=\"../exports.php?session=$session&filename=$filename\" target=new>$filename</a><br>";
?>
