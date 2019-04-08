<?php
//bb_bfinanceexport.php: export file of all bb_b financial report data

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

$bb_bclass=array("A","B","C1","C2","D1","D2");
$bb_bfiles=array("C1Sub","C2Sub","D1Sub","D2Sub","C1","C2","D1","D2","B","A");

$bb_bfields=array("classdist","location","grossreceipts","offtotal","insdeduct","hostgiven","hostallow","nsaaallow","balance","milespaid","milespaidtot","prorate","milespaidpro","milespaidprotot","bonus","hostperc","hostbonus","nsaabonus","visperc","visitorbonus","grossreceipts","offtotal","hosttotal","hostexp","nsaapartial","insdeduct","nsaatotal");

$bb_btitles=array("District","Site","Total Receipts","Officials","Insurance-10%","Host Allowance","Host School","NSAA","Balance","Expenses","Total Expenses","Prorate","Expenses","Expenses Paid","Balance Bonus","% Host","Host School","NSAA","% Teams","Teams","Total Receipts","Officials","Host","Teams","NSAA","Insurance","Check Amount");

//set overall summary total variables to 0
$totalrec_sub=0; $totalrec_dist=0; $totalrec_a=0; $totalrec_b=0;
$totaloff_sub=0; $totaloff_dist=0; $totaloff_a=0; $totaloff_b=0;
$totalhost_sub=0; $totalhost_dist=0; $totalhost_a=0; $totalhost_b=0;
$totalvis_sub=0; $totalvis_dist=0; $totalvis_a=0; $totalvis_b=0;
$totalnsaa_sub=0; $totalnsaa_dist=0; $totalnsaa_a=0; $totalnsaa_b=0;
$totalins_sub=0; $totalins_dist=0; $totalins_a=0; $totalins_b=0;

// FOR EACH District and Sub-District, CREATE CSV FILE: 
for($file=0;$file<count($bb_bfiles);$file++)
{
    $csv="";

    //(sub)district TOTAL VARIABLES:
    $totalrec=0;
    $totaloff=0;
    $totalhost=0;
    $totalvis=0;
    $totalnsaa=0;
    $totalins=0;

    //get title of round and class
    if(ereg("Sub",$bb_bfiles[$file]))
    {
       $round=1;
       $class=substr($bb_bfiles[$file],0,2);
    }
    else
    {
       $round=2;
       $class=$bb_bfiles[$file];
    }

    // FOR EACH tournament in this (sub)district, CREATE column OF CSV FILE: 
    $sql="SELECT * FROM finance_bb_b WHERE class='$class' AND round='$round' ORDER BY district";
    $result=mysql_query($sql);
    $tid=0;	//tournament id
    $tourn=array();
    while($row=mysql_fetch_array($result))
    {
       // GET (SUB)DISTRICT INFO FROM finance_bb_b: 
       $tourn[hostschool][$tid]=$row[1];
       $tourn[classdist][$tid]=$class."-".$row[3];
       $tourn[district][$tid]=$row[3];
       $tourn[location][$tid]=$row[5];
       $tourn[grossreceipts][$tid]=$row[9];
       $tourn[offtotal][$tid]=number_format($row[10]+$row[12],2,'.','');
       $tourn[insdeduct][$tid]=$row[13];
       $tourn[hostgiven][$tid]=$row[15];
       $tourn[hostallow][$tid]=$row[16];
       $tourn[nsaaallow][$tid]=$row[17];
       $tourn[balance][$tid]=$row[14];
       $tourn[expenses][$tid]=$row[18];
       $tourn[prorate][$tid]=$row[19];
       $tourn[bonus][$tid]=$row[20];
       $tourn[hostbonus][$tid]=$row[21];
       $tourn[nsaabonus][$tid]=$row[23];
       $tourn[visitorbonus][$tid]=$row[22];
       $tourn[hosttotal][$tid]=$row[24];
       $tourn[hostexp][$tid]=$row[25];
       $tourn[nsaapartial][$tid]=$row[26];
       $tourn[nsaatotal][$tid]=number_format($tourn[nsaapartial][$tid]+$tourn[insdeduct][$tid],2,'.','');

       // FOR EACH GAME's REPORT in finance_bb_b_exp, STORE INFO: 
       $sql2="SELECT * FROM finance_bb_b_exp WHERE class='$class' AND district='".$tourn[district][$tid]."' AND round='$round' ORDER BY school";
       $result2=mysql_query($sql2);
       $ix=0;
       $tourn[milespaidtot][$tid]=0;
       $tourn[milespaidprotot][$tid]=0;
       $parthost=0;
       while($row2=mysql_fetch_array($result2))
       {
	  if($tourn[hostschool][$tid]==$row2[4])
	     $parthost=1;
          $tourn[milespaid][$tid][$ix]=number_format(trim($row2[8]),2,'.','');
	  if(trim($row2[9])=="" || $row2[9]==0)
	     $tourn[milespaidpro][$tid][$ix]=$tourn[milespaid][$tid][$ix];
	  else
	     $tourn[milespaidpro][$tid][$ix]=number_format(trim($row2[9]),2,'.','');
	  $tourn[milespaidtot][$tid]+=$tourn[milespaid][$tid][$ix];
	  $tourn[milespaidprotot][$tid]+=$tourn[milespaidpro][$tid][$ix];
	  $ix++;
       }

       //host and visitor bonbus percents
       if($parthost==1)	//participating host
       {
	  $tourn[hostperc][$tid]="0.00";
	  $tourn[visperc][$tid]="0.90";
       }
       else
       {
	  $tourn[hostperc][$tid]="0.15";
	  $tourn[visperc][$tid]="0.75";
       }

       $tid++;
    }  //end for each tournament
   
   // NOW TAKE STORED INFO AND OUTPUT TO CSV FILE 
   $curryear=date("Y");
   $filename="bb_b";
   if($round==1) $filename.="sub";
   $filename.="dist".$curryear;
   $filename.="_$class.csv";
   $open=fopen(citgf_fopen("financialreports/$filename"),"w");
 
   for($field=0;$field<count($bb_bfields);$field++)
   {
      // FOR EACH DATA FIELD
      if($field==9)	//miles paid to each team (non-prorated)
      {
	 for($i=0;$i<6;$i++)	//for each possible team in tournament
	 {
	    //put expenses in csv file
	    $csv.="Expenses,";
	    for($t=0;$t<$tid;$t++)	//for each tournament
	    {
	       //put non-prorated mileage paid to $i-th school
	       $csv.=$tourn[milespaid][$t][$i].",";
	    }
	    $csv.="\r\n";
	 }
      }
      else if($field==12)	//prorated miles paid
      {
	 for($i=0;$i<6;$i++)
	 {
	    $csv.="Expenses,";
	    for($t=0;$t<$tid;$t++)
	    {
	       $csv.=$tourn[milespaidpro][$t][$i].",";
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
	 $csv.=$bb_btitles[$field].",";
	 for($t=0;$t<$tid;$t++)
	 {
	    $csv.=$tourn[$bb_bfields[$field]][$t].",";
	    //add to gross receipts total for this (sub)district
	 }
	 $csv.="\r\n";
      }
      else
      {
	 $csv.=$bb_btitles[$field].",";
	 for($t=0;$t<$tid;$t++)
	 {
	    //for each tourn, put this field's data
	    $csv.=$tourn[$bb_bfields[$field]][$t].",";
	 }
	 $csv.="\r\n";
      }

      if($field>=20)	//summary fields
      {
	 for($t=0;$t<$tid;$t++)
	 {
	    if($bb_bfields[$field]=="grossreceipts")
	       $totalrec+=$tourn[$bb_bfields[$field]][$t];
	    else if($bb_bfields[$field]=="offtotal")
	       $totaloff+=$tourn[$bb_bfields[$field]][$t];
	    else if($bb_bfields[$field]=="hosttotal")
	       $totalhost+=$tourn[$bb_bfields[$field]][$t];
	    else if($bb_bfields[$field]=="hostexp")
	       $totalvis+=$tourn[$bb_bfields[$field]][$t];
	    else if($bb_bfields[$field]=="nsaapartial")
	       $totalnsaa+=$tourn[$bb_bfields[$field]][$t];
	    else if($bb_bfields[$field]=="insdeduct")
	       $totalins+=$tourn[$bb_bfields[$field]][$t];
	 }
      }

   }	//END FOR EACH FIELD
   
   //(sub) district totals section:
   $csv.="\r\n$class TOTALS\r\n";
   $csv.="Total Receipts,$totalrec\r\n";
   $csv.="Officials,$totaloff\r\n";
   $csv.="Host,$totalhost\r\n";
   $csv.="Teams,$totalvis\r\n";
   $csv.="NSAA,$totalnsaa\r\n";
   $csv.="Insurance,$totalins\r\n";

   //update overall totals for subdistricts, district finals, class a, class b
   if($round==1 && (ereg("C",$class) || ereg("D",$class)))  //Sub Districts
   {
      $totalrec_sub+=$totalrec;
      $totaloff_sub+=$totaloff;
      $totalhost_sub+=$totalhost;
      $totalvis_sub+=$totalvis;
      $totalnsaa_sub+=$totalnsaa;
      $totalins_sub+=$totalins;
   }
   else if($round==2 && (ereg("C",$class) || ereg("D",$class))) //C/D Districts
   {
      $totalrec_dist+=$totalrec;
      $totaloff_dist+=$totaloff;
      $totalhost_dist+=$totalhost;
      $totalvis_dist+=$totalvis;
      $totalnsaa_dist+=$totalnsaa;
      $totalins_dist+=$totalins;
   }
   else if($round==2 && ereg("A",$class)) //Class A District
   {
      $totalrec_a+=$totalrec;
      $totaloff_a+=$totaloff;
      $totalhost_a+=$totalhost;
      $totalvis_a+=$totalvis;
      $totalnsaa_a+=$totalnsaa;
      $totalins_a+=$totalins;
   }
   else	//Class B District
   {
      $totalrec_b+=$totalrec;
      $totaloff_b+=$totaloff;
      $totalhost_b+=$totalhost;
      $totalvis_b+=$totalvis;
      $totalnsaa_b+=$totalnsaa;
      $totalins_b+=$totalins;
   }

   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("financialreports/$filename");
   echo "<a href=\"financialreports/$filename\" target=new>$filename</a><br>";
}

//SUMMARY CSV FILE:
$csv="";
$csv.="$curryear SUMMARY DISTRICT BOYS BASKETBALL RECEIPTS\r\n\r\n\r\n\r\n";
$csv.=",Subdistricts,,District Finals,,Class A,,Class B\r\n";
$csv.="Total Receipts,$totalrec_sub,,$totalrec_dist,,$totalrec_a,,$totalrec_b\r\n";
$csv.="Officials,$totaloff_sub,,$totaloff_dist,,$totaloff_a,,$totaloff_b\r\n";
$csv.="Host,$totalhost_sub,,$totalhost_dist,,$totalhost_a,,$totalhost_b\r\n";
$csv.="Teams,$totalvis_sub,,$totalvis_dist,,$totalvis_a,,$totalvis_b\r\n";
$csv.="NSAA,$totalnsaa_sub,,$totalnsaa_dist,,$totalnsaa_a,,$totalnsaa_b\r\n";
$csv.="Insurance,$totalins_sub,,$totalins_dist,,$totalins_a,,$totalins_b\r\n";

$totalrec_all=$totalrec_sub+$totalrec_dist+$totalrec_a+$totalrec_b;
$totaloff_all=$totaloff_sub+$totaloff_dist+$totaloff_a+$totaloff_b;
$totalhost_all=$totalhost_sub+$totalhost_dist+$totalhost_a+$totalhost_b;
$totalvis_all=$totalvis_sub+$totalvis_dist+$totalvis_a+$totalvis_b;
$totalnsaa_all=$totalnsaa_sub+$totalnsaa_dist+$totalnsaa_a+$totalnsaa_b;
$totalins_all=$totalins_sub+$totalins_dist+$totalins_a+$totalins_b;

$csv.="\r\n\r\nGRAND TOTALS:\r\n";
$csv.="Total Receipts,$totalrec_all\r\n";
$csv.="Officials,$totaloff_all\r\n";
$csv.="Host,$totalhost_all\r\n";
$csv.="Teams,$totalvis_all\r\n";
$csv.="NSAA,$totalnsaa_all\r\n";
$csv.="Insurance,$totalins_all";

$filename="bb_bsummary$curryear.csv";
$open=fopen(citgf_fopen("financialreports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("financialreports/$filename");
echo "<a href=\"financialreports/$filename\" target=new>$filename</a><br>";
?>
