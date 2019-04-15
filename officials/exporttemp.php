<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

   $logins="logins";

   $now=time();
   $filename="roster".$now.".csv";

   $sql="SELECT * FROM officials ORDER BY zip,last,first";
   $result=mysql_query($sql);
   $string.="\"Last\",\"First\",\"Middle\",";
   $string.="\"Address\",\"City\",\"State\",\"Zip\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"E-mail\"\r\n";
   $regct=0;
   $appct=0;
   $cerct=0;
   $other=0;
   $totalcount=0;
   $mailoption=3; $mailnum3="100"; $mailineq=">=";
   while($row=mysql_fetch_array($result))
   {
      $inrange=1; 
      if($mailoption==3)
      {
	 //IF mailoption #3, check that official has mailing number in given range in ANY SPORT
         $inrange=0; 
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            $table=$cursp."off";
            $sql2="SELECT mailing FROM $table WHERE offid='$row[id]' AND mailing $mailineq '$mailnum3'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            if(mysql_num_rows($result2)>0) //official has mailing # in given range
            {
               $inrange=1;
               //$showmailing.=strtoupper($cursp).": $row2[0]/";
            }
         }
      }
      $offdist=1;
      $offstate=1;
      $offyears=1;
      if($inrange==1 && $offdist==1 && $offstate==1 && $offyears>0)
      {
	    $mailing=$row[mailing];
      $homeph="H-".substr($row[homeph],0,3)."-".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
      $workph="W-".substr($row[workph],0,3)."-".substr($row[workph],3,3)."-".substr($row[workph],6,4);
      $cellph="C-".substr($row[cellph],0,3)."-".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
      if($mailoption!=3) $showmailing=$mailing;
      //$string.="\"$showmailing\",";
      //if($insincedist!='' && $yeardist!='') $string.="\"$showdistcontracts\",";
      //if($insincestate!='' && $yearstate!='') $string.="\"$showstatecontracts\",";
      //if($stateyearsineq!='' && $numstateyears!='') $string.="\"$showoffyears\",";
      $string.="\"$row[last]\",\"$row[first]\",\"$row[middle]\",";
      if($sport!='judge' && $sport && !ereg("All",$sport)) $string.="\"$class\",";
      $string.="\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$homeph\",\"$workph\",\"$cellph\",\"$row[email]\"\r\n";
      $totalcount++;
      }//end if inrange==1
   }//end while($row....)
   $total=$regct+$cerct+$appct;
      $overalltotal=$total+$other;

   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
      $overalltotal=$totalcount;
   echo "reports.php?filename=$filename\r\n$search ($overalltotal Results)\r\n";


   exit();


?>
