<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

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

$fbclass=array("A","B","C1","C2","D1","D2");

/*** FOR EACH ROUND, CREATE CSV FILE: ***/
for($rnd=1;$rnd<5;$rnd++)
{
   $csv="";

    //ROUND TOTAL VARIABLES:
    $rndatt=0;
    $rndrec=0;
    $rndoff=0;
    $rndhost=0;
    $rndvis=0;
    $rndnsaa=0;
    $rndins=0;
    $rndlbfee=0; $rndowfee=0; $rndotfee=0;

    //get title of round and classes in round
    switch($rnd)
    {
       case 1:
	  $rndword="1st Round";
	  $cl0=0;
	  break;
       case 2:
	  $rndword="2nd Round";
	  $cl0=0;
	  break;
       case 3:
	  $rndword="Quarterfinals";
	  $cl0=0;
	  break;
       case 4:
	  $rndword="Semifinals";
	  $cl0=0;
	  break;
    }

    /*** FOR EACH CLASS, CREATE SECTION OF CSV FILE: ***/ 
    for($cls=$cl0;$cls<count($fbclass);$cls++)
    {
       /*** FOR EACH GAME's REPORT, STORE INFO: ***/
       $sql="SELECT * FROM finance_fb WHERE classdist='$fbclass[$cls]' AND round='$rnd' ORDER BY school";
       $result=mysql_query($sql);
       $ix=0;
       while($row=mysql_fetch_array($result))
       {
          $game[$cls][attendance][$ix]=$row[attendance];
	  $game[$cls][sch][$ix]=$row[1];
	  $game[$cls][opp][$ix]=$row[6];
	  $game[$cls][rec][$ix]=$row[7];
	  $rndrec+=$row[7]; $rndatt+=$row[attendance];
	  $game[$cls][off][$ix]=$row[8]+$row[10];
	  $rndoff+=$game[$cls][off][$ix];
	  $game[$cls][ins][$ix]=$row[12];
	  $rndins+=$row[12];
          $game[$cls][localmedia_bfee][$ix]=$row[localmedia_bfee];
	  $rndlbfee+=$row[localmedia_bfee];
          $game[$cls][othermedia_wfee][$ix]=$row[othermedia_wfee];
          $rndowfee+=$row[othermedia_wfee];
          $game[$cls][othermedia_tfee][$ix]=$row[othermedia_tfee];
          $rndotfee+=$row[othermedia_tfee];
	  $game[$cls][bal][$ix]=$row[13];
	  $game[$cls][host][$ix]=$row[14];
	  $game[$cls][nsaa][$ix]=$row[15];
	  $game[$cls][vismi][$ix]=($row[11]-50)*4.2;
	  if($game[$cls][vismi][$ix]<0) $game[$cls][vismi][$ix]=0;
	  $game[$cls][visexp][$ix]=$row[16];
	  $game[$cls][dist][$ix]=$row[17];
	  $game[$cls][pro][$ix]=number_format($row[13]/$row[17],5,'.',''); //$row[18];
	  if($game[$cls][pro][$ix]>1) 
	     $game[$cls][pro][$ix]="1.00";
	  if($game[$cls][pro][$ix]<1)
	  {
	     $game[$cls][hostpro][$ix]=$row[19];
	     $game[$cls][nsaapro][$ix]=$row[20];
	     $game[$cls][vispro][$ix]=$row[21];
	     $game[$cls][distpro][$ix]=$row[22];
	  }
	  else
	  {
	     $game[$cls][hostpro][$ix]=$game[$cls][host][$ix];
	     $game[$cls][nsaapro][$ix]=$game[$cls][nsaa][$ix];
	     $game[$cls][vispro][$ix]=$game[$cls][visexp][$ix];
	     $game[$cls][distpro][$ix]=$game[$cls][dist][$ix];
	  }
	  $game[$cls][bon][$ix]=$row[23];
	  $game[$cls][hostbon][$ix]=$row[24];
	  $game[$cls][visbon][$ix]=$row[25];
	  $game[$cls][nsaabon][$ix]=$row[26];
	  //summary data:
	  $game[$cls][hosttotal][$ix]=$game[$cls][hostpro][$ix]+$game[$cls][hostbon][$ix];
	  $rndhost+=$game[$cls][hosttotal][$ix];
	  $game[$cls][vistotal][$ix]=$game[$cls][vispro][$ix]+$game[$cls][visbon][$ix];
	  $rndvis+=$game[$cls][vistotal][$ix];
	  $game[$cls][nsaatotal][$ix]=$game[$cls][nsaapro][$ix]+$game[$cls][nsaabon][$ix];
	  $rndnsaa+=$game[$cls][nsaatotal][$ix];
	  $game[$cls][disttotal][$ix]=$game[$cls][off][$ix]+$game[$cls][hosttotal][$ix]+$game[$cls][vistotal][$ix]+$game[$cls][nsaatotal][$ix]+$game[$cls][ins][$ix];
	  $game[$cls][checkamt][$ix]=$game[$cls][nsaatotal][$ix]+$game[$cls][ins][$ix];

	  $ix++;
      }
   }
   
   /*** NOW TAKE STORED INFO AND OUTPUT TO CSV FILE ***/
   $curryear=date("Y");
   $filename="fbdist".$curryear;
   $filename.="rnd$rnd.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");

   $csvfields=array("sch","opp","attendance","rec","off","ins","bal","host","nsaa","vismi","visexp","dist","pro","hostpro","nsaapro","vispro","distpro","bon","hostbon","visbon","nsaabon","rec","off","hosttotal","vistotal","nsaatotal","ins","disttotal","checkamt","rec","off","hosttotal","vistotal","nsaatotal","ins","localmedia_bfee","othermedia_wfee","othermedia_tfee");
   $csvtitles=array("SITE","OPPONENT","Attendance","Total Receipts","Officials","Insurance-10% gross","Balance","Host School","NSAA","Mileage","Total Vis Exp or 10% Receipts","Total Expenses","Prorated","% Host","% NSAA","% Visitor Expenses","Total Expenses Paid","Balance","Host Bonus","Visitor Bonus","NSAA Bonus","Total Receipts","Officials","Host School","Visiting School","NSAA","Insurance","Total Distribution","Check Amount","Total Receipts","Officials","Hosts","Visiting Schools","NSAA","Insurance","Local/Unaffiliated Broadcast Fees","Other/Affiliated Webcast Fees","Other/Affiliated Telecast Fees");

   for($field=0;$field<count($csvfields);$field++)
   {
      /***FOR EACH DATA FIELD***/
      if($field==2)
      {
	 for($cls=$cl0;$cls<count($fbclass);$cls++)
	 {
	    $csv.="CLASS,";
	    for($i=0;$i<count($game[$cls][$csvfields[0]]);$i++)
	    {
	       $csv.="$fbclass[$cls],";
	    }
	    $csv.=",";
	 }
	 $csv.="\r\n";
	 for($cls=$cl0;$cls<count($fbclass);$cls++)
	 {
	    $csv.="Game Time,";
	    for($i=0;$i<count($game[$cls][$csvfields[0]]);$i++)
	    {
	       $csv.=",";
	    }
	    $csv.=",";
	 }
	 $csv.="\r\n";
      }
      else if($field==11)	//prorate field
      {
	 for($cls=$cl0;$cls<count($fbclass);$cls++)
	 {
	    $csv.="% Payable,";
	    for($i=0;$i<count($game[$cls][$csvfields[11]]);$i++)
	    {
	       $payable=$game[$cls][bal][$i]/$game[$cls][dist][$i];
	       $payable=number_format($payable,5,'.','');
	       $csv.="$payable,";
	    }
	    $csv.=",";
	 }
	 $csv.="\r\n";
      }
      else if($field==20)	//summary fields
      {
	 $csv.="\r\n";
	 for($cls=$cl0;$cls<count($fbclass);$cls++)
	 {
	    $csv.="SUMMARY,";
	    for($i=0;$i<count($game[$cls][sch]);$i++)
	    {
	       $csv.=",";
	    }
	    $csv.=",";
	 }
	 $csv.="\r\n";
      }
      else if($field==28)	//class summary fields
      {
	 $csv.="\r\n";
	 for($cls=$cl0;$cls<count($fbclass);$cls++)
	 {
	    $csv.="CLASS $fbclass[$cls] TOTALS,";
	    for($i=0;$i<count($game[$cls][sch]);$i++)
	    {
	       $csv.=",";
	    }
	    $csv.=",";
	 }
	 $csv.="\r\n";
      }
      if($field<28)
      {
         for($cls=$cl0;$cls<count($fbclass);$cls++)
         {
	    /***FOR EACH CLASS***/
            $csv.="$csvtitles[$field],";
            for($i=0;$i<count($game[$cls][sch]);$i++)
            {
	       $temp=$game[$cls][$csvfields[$field]][$i];
	       $csv.="$temp,";
            }
	    $csv.=",";
         }
      }
      else	//Class summary fields
      {	
	 for($cls=$cl0;$cls<count($fbclass);$cls++)
	 {
	    /***FOR EACH CLASS, GET TOTALS***/
	    $csv.="$csvtitles[$field],";
	    $temptotal=0;
	    for($i=0;$i<count($game[$cls][sch]);$i++)
	    {
	       $temptotal+=$game[$cls][$csvfields[$field]][$i];
	    }
	    if($csvtitles[$field]=="Total Receipts")
	       $totalrec[$rnd]+=$temptotal;
            else if($csvtitles[$field]=="Attendance")
               $totalatt[$rnd]=$temptotal;
	    else if($csvtitles[$field]=="Officials")
	       $totaloff[$rnd]=$temptotal;
	    else if($csvtitles[$field]=="Hosts")
	       $totalhost[$rnd]=$temptotal;
	    else if($csvtitles[$field]=="Visiting Schools")
	       $totalvis[$rnd]=$temptotal;
	    else if($csvtitles[$field]=="NSAA")
	       $totalnsaa[$rnd]=$temptotal;
	    else if($csvtitles[$field]=="Insurance")
	       $totalins[$rnd]=$temptotal;
            else if($csvtitles[$field]=="Local/Unaffiliated Broadcast Fees")
               $totallbfee[$rnd]=$temptotal;
            else if($csvtitles[$field]=="Other/Affiliated Webcast Fees")
               $totalowfee[$rnd]=$temptotal;
            else if($csvtitles[$field]=="Other/Affiliated Telecast Fees")
               $totalotfee[$rnd]=$temptotal;
            $csv.="$temptotal,";
	    if(count($game[$cls][sch])<1)
	    {
	    }
	    else
	    {
	    {
	       $csv.=",";
	    }
	    $csv.=",";
    	    }
         }
      }
      $csv.="\r\n";
   }	//END FOR EACH FIELD
 
   //NOW PUT ROUND SUMMARY:
   $temp=strtoupper($rndword);
   $csv.="\r\n$temp GRAND TOTALS\r\n";
   $csv.="ATTENDANCE,$rndatt,TOTAL RECEIPTS,$rndrec\r\nOFFICIALS,$rndoff\r\nHOSTS,$rndhost\r\nVISITORS,$rndvis\r\nNSAA,$rndnsaa\r\nINSURANCE,$rndins\r\nLOCAL/UNAFFILIATED BROADCAST FEES,$rndlbfee\r\nOTHER/AFFILIATED WEBCAST FEES,$rndowfee\r\nOTHER/AFFILIATED TELECAST FEES,$rndotfee\r\n";
   $totalatt[$rnd]=$rndatt;
   $totalrec[$rnd]=number_format($rndrec,2,'.','');
   $totaloff[$rnd]=number_format($rndoff,2,'.','');
   $totalhost[$rnd]=number_format($rndhost,2,'.','');
   $totalvis[$rnd]=number_format($rndvis,2,'.','');
   $totalnsaa[$rnd]=number_format($rndnsaa,2,'.','');
   $totalins[$rnd]=number_format($rndins,2,'.','');
   $totallbfee[$rnd]=number_format($rndlbfee,2,'.','');
   $totalowfee[$rnd]=number_format($rndowfee,2,'.','');
   $totalotfee[$rnd]=number_format($rndotfee,2,'.','');

   //NOW PUT IN REIMBURSEMENT INFO:
   $csv.="\r\nPRORATED REI\r\n";
   $totalrei=0;
   //host:
   for($cls=$cl0;$cls<count($fbclass);$cls++)
   {
      $csv.="SITE,";
      for($i=0;$i<count($game[$cls][sch]);$i++)
      {
	 $csv.=$game[$cls][sch][$i].",";
      }
      $csv.=",";
   }
   $csv.="\r\n";
   for($cls=$cl0;$cls<count($fbclass);$cls++)
   {
      $csv.="Host Reiumbursement,";
      for($i=0;$i<count($game[$cls][sch]);$i++)
      {
	 if($game[$cls][pro][$i]<1)
	 {
	    //$rei=$game[$cls][host][$i]-$game[$cls][hostpro][$i];
	    $rei=$game[$cls][nsaa][$i]-$game[$cls][nsaapro][$i];
	 }
	 else
	    $rei=0;
	 $rei=number_format($rei,2);
	 $csv.="$rei,";
	 $totalrei+=$rei;
      }
      $csv.=",";
   }
   $csv.="\r\n";
   //visitor:
   for($cls=$cl0;$cls<count($fbclass);$cls++)
   {
      $csv.="OPPONENT,";
      for($i=0;$i<count($game[$cls][sch]);$i++)
      {
	 $csv.=$game[$cls][opp][$i].",";
      }
      $csv.=",";
   }
   $csv.="\r\n";
   for($cls=$cl0;$cls<count($fbclass);$cls++)
   {
      $csv.="Visitor Reimbursement,";
      for($i=0;$i<count($game[$cls][sch]);$i++)
      {
	 if($game[$cls][pro][$i]<1)
	 {
	    //$rei=$game[$cls][visexp][$i]-$game[$cls][vispro][$i];
	    $rei=$game[$cls][dist][$i]-$game[$cls][distpro][$i];
	 }
	 else
	    $rei=0;
	 $rei=number_format($rei,2);
	 $csv.="$rei,";
	 $totalrei+=$rei;
      }
      $csv.=",";
   }
   $csv.="\r\n";
   $totalrei=number_format($totalrei,2);
   $csv.="$rndword TOTAL REI,$totalrei";
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<a href=\"../exports.php?session=$session&filename=$filename\">$filename</a><br>";

   unset($game);
}//end for $rnd loop

//write summary csv file
$csv="$curryear FOOTBALL PLAYOFF SUMMARY\r\n\r\n\r\n";
$csv.=",SEMIFINALS,QRTFINALS,2ND ROUND,1ST ROUND,TOTAL\r\n\r\n";
$totalatt[0]=$totalatt[1]+$totalatt[2]+$totalatt[3]+$totalatt[4];
$csv.="ATTENDANCE,$totalatt[4],$totalatt[3],$totalatt[2],$totalatt[1],$totalatt[0]\r\n";
$totalrec[0]=$totalrec[1]+$totalrec[2]+$totalrec[3]+$totalrec[4];
$totalrec[0]=number_format($totalrec[0],2,'.','');
$csv.="TOTAL RECEIPTS,$totalrec[4],$totalrec[3],$totalrec[2],$totalrec[1],$totalrec[0]\r\n";
$totaloff[0]=$totaloff[1]+$totaloff[2]+$totaloff[3]+$totaloff[4];
$totaloff[0]=number_format($totaloff[0],2,'.','');
$csv.="OFFICIALS,$totaloff[4],$totaloff[3],$totaloff[2],$totaloff[1],$totaloff[0]\r\n";
$totalhost[0]=$totalhost[1]+$totalhost[2]+$totalhost[3]+$totalhost[4];
$totalhost[0]=number_format($totalhost[0],2,'.','');
$csv.="HOSTS,$totalhost[4],$totalhost[3],$totalhost[2],$totalhost[1],$totalhost[0]\r\n";
$totalvis[0]=$totalvis[1]+$totalvis[2]+$totalvis[3]+$totalvis[4];
$totalvis[0]=number_format($totalvis[0],2,'.','');
$csv.="VISITORS,$totalvis[4],$totalvis[3],$totalvis[2],$totalvis[1],$totalvis[0]\r\n";
$totalnsaa[0]=$totalnsaa[1]+$totalnsaa[2]+$totalnsaa[3]+$totalnsaa[4];
$totalnsaa[0]=number_format($totalnsaa[0],2,'.','');
$csv.="NSAA,$totalnsaa[4],$totalnsaa[3],$totalnsaa[2],$totalnsaa[1],$totalnsaa[0]\r\n";
$totalins[0]=$totalins[1]+$totalins[2]+$totalins[3]+$totalins[4];
$totalins[0]=number_format($totalins[0],2,'.','');
$csv.="INSURANCE,$totalins[4],$totalins[3],$totalins[2],$totalins[1],$totalins[0]\r\n";
$totallbfee[0]=$totallbfee[1]+$totallbfee[2]+$totallbfee[3]+$totallbfee[4];
$totallbfee[0]=number_format($totallbfee[0],2,'.','');
$csv.="LOCAL/UNAFFILIATED BROADCAST FEES,$totallbfee[4],$totallbfee[3],$totallbfee[2],$totallbfee[1],$totallbfee[0]\r\n";
$totalowfee[0]=$totalowfee[1]+$totalowfee[2]+$totalowfee[3]+$totalowfee[4];
$totalowfee[0]=number_format($totalowfee[0],2,'.','');
$csv.="OTHER/AFFILIATED WEBCAST FEES,$totalowfee[4],$totalowfee[3],$totalowfee[2],$totalowfee[1],$totalowfee[0]\r\n";
$totalotfee[0]=$totalotfee[1]+$totalotfee[2]+$totalotfee[3]+$totalotfee[4];
$totalotfee[0]=number_format($totalotfee[0],2,'.','');
$csv.="OTHER/AFFILIATED TELECAST FEES,$totalotfee[4],$totalotfee[3],$totalotfee[2],$totalotfee[1],$totalotfee[0]\r\n";

$filename="fbdist".$curryear."summary.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
echo "<a href=\"../exports.php?session=$session&filename=$filename\">$filename</a>";

?>
