<?php
if($_REQUEST['get_argv']==1){
	$argv=array();
	if(isset($_GET['var1']))	$argv[1]=$_GET['var1'];
	if(isset($_GET['var2']))	$argv[2]=$_GET['var2'];
	if(isset($_GET['var3']))	$argv[3]=$_GET['var3'];
	if(isset($_GET['var4']))	$argv[4]=$_GET['var4'];
	if(isset($_GET['var5']))	$argv[5]=$_GET['var5'];
	if(isset($_GET['var6']))	$argv[6]=$_GET['var6'];
	if(isset($_GET['var7']))	$argv[7]=$_GET['var7'];
	if(isset($_GET['var8']))	$argv[8]=$_GET['var8'];
	if(isset($_GET['var9']))	$argv[9]=$_GET['var9'];
	if(isset($_GET['var10']))	$argv[10]=$_GET['var10'];
	if(isset($_GET['var11']))	$argv[11]=$_GET['var11'];
	if(isset($_GET['var12']))	$argv[12]=$_GET['var12'];
}
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!$session && count($argv)>1)	//RUN IN BACKGROUND (Roster export)
{
   $runinback=1;
   $filetime=$argv[1];
   $session=$argv[2];
   $sport=$argv[3];
   $query=$argv[4];
   $type=$argv[5];
   $search=$argv[6];
   $stateyearsineq=$argv[7];
   $numstateyears=$argv[8];
   $insincestate=$argv[9];
   $yearstate=$argv[10];
   $insincedist=$argv[11];
   $yeardist=$argv[12];
   $mailoption=$argv[13];
   $whichmailnum=$argv[14];
   $mailineq=$argv[15];
   $mailnum3=$argv[16];
}
else $runinback=0;

if(!$sport) $sport="judge";

if($sport=="judge")
   $query=ereg_replace("PLUS","+",$query);
$query=preg_replace("/`/","'",$query);
//echo "QUERY: ".$query."<br>";

if(!$sport || $sport=="judge")
{
   $logins="logins_j";
}
else
{
   $logins="logins";
}

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if($type=="zip")	//Zip Summary
{
   if($query && $query!="")
   {
      if(ereg("AS",$query))
         $sql=$query." ORDER BY t1.zip";
      else
         $sql=$query." ORDER BY zip";
   }
   else if($sport=='judge')
   {
      $sql="SELECT * FROM judges WHERE (speech='x' OR play='x') ORDER BY zip";
   }
   else if($sport)
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' AND $sport='x' ORDER BY zip";
   }
   else
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' ORDER BY zip";
   }
   $result=mysql_query($sql);
   echo $init_html;
   echo "<br>";
   echo "<table width=200 cellspacing=0 cellpadding=3 border=1 bordercolor=#000000>";
   echo "<caption class=small><b>Zip Code Summary for Search:</b><br>$search<hr></caption>";
   echo "<tr align=left><th align=left class=smaller>Zip Code</th><th align=left class=smaller>Count</th></tr>";
   if($sort=="count")
   {
      $row=mysql_fetch_array($result);
      $curzip=substr($row[zip],0,5);
      $curct=1;
      $zips=array(); $zips[ct]=array(); $zips[zip]=array();
      $z=0;
      $zips[zip][$z]=$curzip;
      $zips[ct][$z]=0;
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $inrange=1; 
         if($mailoption==3)
         {
            //IF mailoption #3, check that official has mailing number in given range in ANY SPORT
            $inrange=0; $showmailing="";
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
               }
            }
         }
	 $offdist=1;
	 if($insincedist!='' && $yeardist!='')
	 {
	    $offdist=0;
            for($i=0;$i<count($activity);$i++)
            {
               $cursp=$activity[$i];
	       if(!$sport || ereg("All",$sport) || $cursp==$sport)
	       {
		  if(OfficiatedDistricts($row[id],$insincedist,$yeardist,$cursp))
		     $offdist=1;
               }
            }
	 }
         $offstate=1;
         if($insincestate!='' && $yearstate!='')
         {
            $offstate=0; 
            for($i=0;$i<count($activity);$i++)
            {
               $cursp=$activity[$i];
               if(!$sport || ereg("All",$sport) || $cursp==$sport)
               {
                  if(OfficiatedState($row[id],$insincestate,$yearstate,$cursp))
                     $offstate=1;
               }
            }
         }
         $offyears=1;
         if($stateyearsineq!='' && $numstateyears!='')
         {
            $offyears=0;       //assume official does not meet criteria
            for($i=0;$i<count($activity);$i++)
            {
               $cursp=$activity[$i];
               if(!$sport || ereg("All",$sport) || $sport==$cursp)
               {
                  if(($stateyearsineq==">=" && YearsOfficiatedState($row[0],$cursp) >= $numstateyears) || ($stateyearsineq=="<=" && YearsOfficiatedState($row[0],$cursp) <= $numstateyears) || ($stateyearsineq=="=" && YearsOfficiatedState($row[0],$cursp) == $numstateyears))
                     $offyears=YearsOfficiatedState($row[0],$cursp);
               }
            }
         }
	 if($inrange==1 && $offdist==1 && $offstate==1 && $offyears>0)
         {
	    if(substr($row[zip],0,5)==$curzip)
	       $zips[ct][$z]++;
	    else
	    {
	       $z++;
	       $curzip=substr($row[zip],0,5);
	       $zips[zip][$z]=$curzip;
	       $zips[ct][$z]=1;
	    }
	 }
      }
   }
   else
   {
      $row=mysql_fetch_array($result);
      $curzip=substr($row[zip],0,3);
      $curct=0;
      echo "<tr align=left><td align=left>$curzip&nbsp;</td><td align=left>";
      $total=0;
      $result=mysql_query($sql);
	$csv="";
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
                  $inrange=1; $i=count($activity);
               }
            }
         }
         $offdist=1;
         if($insincedist!='' && $yeardist!='')
         {
            $offdist=0;
            for($i=0;$i<count($activity);$i++)
            {
               $cursp=$activity[$i];
               if(!$sport || ereg("All",$sport) || $cursp==$sport)
               {
                  if(OfficiatedDistricts($row[id],$insincedist,$yeardist,$cursp))
                     $offdist=1;
               }
            }
         }
         $offstate=1;
         if($insincestate!='' && $yearstatet!='')
         {
            $offstate=0; 
            for($i=0;$i<count($activity);$i++)
            {
               $cursp=$activity[$i];
               if(!$sport || ereg("All",$sport) || $cursp==$sport)
               {
                  if(OfficiatedDistricts($row[id],$insincestate,$yearstate,$cursp))
                     $offstate=1;
               }
            }
         }
         $offyears=1;
         if($stateyearsineq!='' && $numstateyears!='')
         {
            $offyears=0;       //assume official does not meet criteria
            for($i=0;$i<count($activity);$i++)
            {
               $cursp=$activity[$i];
               if(!$sport || ereg("All",$sport) || $sport==$cursp)
               {
                  if(($stateyearsineq==">=" && YearsOfficiatedState($row[0],$cursp) >= $numstateyears) || ($stateyearsineq=="<=" && YearsOfficiatedState($row[0],$cursp) <= $numstateyears) || ($stateyearsineq=="=" && YearsOfficiatedState($row[0],$cursp) == $numstateyears))
                     $offyears=YearsOfficiatedState($row[0],$cursp);
               }
            }
         }
         if($inrange==1 && $offdist==1 && $offstate==1 && $offyears>0)
         {
            if(substr($row[zip],0,3)==$curzip)
	       $curct++;
            else
            {
	       echo $curct."</td></tr>";
	       $total+=$curct;
	       $curzip=substr($row[zip],0,3);
	       $curct=1;
	       echo "<tr align=left><td align=left>$curzip</td><td align=left>";
            }
	 }
      }
   }
   echo $curct."</td></tr>";
   $total+=$curct;
   echo "<tr><th align=right class=smaller>TOTAL:</th><td align=left>$total</td></tr>";
   echo "</table>";
   echo $end_html;
}//end if type=zip
else if($type=="allever")
{
	//EXPORT ALL JUDGES IN DATABASE: NAME, EMAIL, ADDRESS
   $filename=strtoupper($sport)."Judges".date("mdY").".csv";
   $sql="SELECT * FROM judges WHERE ";
   if($sport=='sp') $sql.="speech='x' ";
   else $sql.="play='x' ";
   $sql.="ORDER BY last,first";
   $result=mysql_query($sql);
   //e-mails, first, last address, city state and zip
   $csv="\"PLAY\",\"SPEECH\",\"FIRST\",\"LAST\",\"ADDRESS\",\"CITY\",\"STATE\",\"ZIP\",\"EMAIL\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[play]\",\"$row[speech]\",\"$row[first]\",\"$row[last]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[email]\"\r\n"; 
   }
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   header("Location:reports.php?session=$session&filename=$filename");
   exit();
}
else if($type=="allthisyear")
{
	//EXPORT JUDGES WHO HAVE REGISTERED THIS YEAR: NAME, ADDRESS, EMAIL
   $filename=strtoupper($sport)."RegisteredJudges".date("mdY").".csv";
   if($list=="sp" || $sport=='sp')
   {
      $sql2="SELECT totalques FROM test_duedates WHERE test='sp'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $minimum=(0.8 * $row2[totalques]);
      $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.spmeeting='x' AND t1.speech='x' AND t2.correct>=$minimum";
   }
   else if($list=='pp' || $sport=='pp')
   {
      $sql2="SELECT totalques FROM test_duedates WHERE test='pp'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $minimum=(0.8 * $row2[totalques]);
      $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND t1.play='x' AND t2.correct>=$minimum";
   }
   $result=mysql_query($sql);
   //e-mails, first, last address, city state and zip
   $csv="\"PLAY\",\"SPEECH\",\"FIRST\",\"LAST\",\"ADDRESS\",\"CITY\",\"STATE\",\"ZIP\",\"EMAIL\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[play]\",\"$row[speech]\",\"$row[first]\",\"$row[last]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[email]\"\r\n";
   }
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   header("Location:reports.php?session=$session&filename=$filename");
   exit();
}
else if($type=="nhsoa")
{
   $now=time();
   $filename="nhsoa".$now.".csv";
   if($runinback==1) $filename="nhsoa".$filetime.".csv";
   if($sport=="judge") $filename="nhsoajudge".$filename;

   //GET SCHOOL YEAR
   $schoolyr=GetSchoolYear();

   if($query)
   {
      $sql=$query;
      $sql=ereg_replace("=x","='x'",$sql);
      if(ereg("AS",$sql))
         $sql.=" ORDER BY t1.last";
      else
         $sql.=" ORDER BY last";
   }
   else
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' AND nhsoa='x' ORDER BY last";
   }

   $result=mysql_query($sql);
   $string="\"Last\",\"First\",\"Middle\",";
   $string.="\"Address\",\"City\",\"State\",\"Zip\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"E-mail\",";
   $sql2="SHOW TABLES LIKE '%off_hist'";
   $result2=mysql_query($sql2);
   $tables=array(); $t=0;
   while($row2=mysql_fetch_array($result2))
   {
      $cursp=preg_replace("/off_hist/","",$row2[0]);
      $string.="\"".strtoupper($cursp)." Registered\",\"".strtoupper($cursp)." Years\",";
      $tables[$t]=$row2[0];
      $t++;
   }
   $string.="\r\n";
   while($row=mysql_fetch_array($result))
   {
      $inrange=1;
      if($mailoption==3)
      {
         //IF mailoption #3, check that official has mailing number in given range in ANY SPORT
         $inrange=0; $showmailing="";
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
               $showmailing.=strtoupper($cursp).": $row2[0]/";
            }
         }
      }
      $offdist=1;
      if($insincedist!='' && $yeardist!='')
      {
         $offdist=0; $showdistcontracts="";
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            if(!$sport || ereg("All",$sport) || $cursp==$sport)
            {
               if(OfficiatedDistricts($row[id],$insincedist,$yeardist,$cursp))
               {
                  $offdist=1;
                  $showdistcontracts.=strtoupper($cursp)."/";
               }
            }
         }
         $showdistcontracts=substr($showdistcontracts,0,strlen($showdistcontracts)-1);
      }
      $offstate=1;
      if($insincestate!='' && $yearstate!='')
      {
         $offstate=0; $showstatecontracts="";
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            if(!$sport || ereg("All",$sport) || $cursp==$sport)
            {
               if(OfficiatedState($row[id],$insincestate,$yearstate,$cursp))
               {
                  $offstate=1;
                  $showstatecontracts.=strtoupper($cursp)."/";
               }
            }
         }
         $showstatecontracts=substr($showstatecontracts,0,strlen($showstatecontracts)-1);
      }
      $offyears=1;
      if($stateyearsineq!='' && $numstateyears!='')
      {
         $offyears=0; $showoffyears="";       //assume official does not meet criteria
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            if(!$sport || ereg("All",$sport) || $sport==$cursp)
            {
               if(($stateyearsineq==">=" && YearsOfficiatedState($row[0],$cursp) >= $numstateyears) || ($stateyearsineq=="<=" && YearsOfficiatedState($row[0],$cursp) <= $numstateyears) || ($stateyearsineq=="=" && YearsOfficiatedState($row[0],$cursp) == $numstateyears))
               {
                  $offyears=YearsOfficiatedState($row[0],$cursp);
                  $showoffyears.=strtoupper($cursp).":$offyears/";
               }
            }
         }
         $showoffyears=substr($showoffyears,0,strlen($showoffyears)-1);
      }
      if($inrange==1 && $offdist==1 && $offstate==1 && $offyears>0)
      {
      $homeph="H-".substr($row[homeph],0,3)."-".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
      $workph="W-".substr($row[workph],0,3)."-".substr($row[workph],3,3)."-".substr($row[workph],6,4);
      $cellph="C-".substr($row[cellph],0,3)."-".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
      if($mailoption!=3) $showmailing=$mailing;
      if($insincedist!='' && $yeardist!='') $string.="\"$showdistcontracts\",";
      if($insincestate!='' && $yearstate!='') $string.="\"$showstatecontracts\",";
      if($stateyearsineq!='' && $numstateyears!='') $string.="\"$showoffyears\",";
      $string.="\"$row[last]\",\"$row[first]\",\"$row[middle]\",";
      $string.="\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$homeph\",\"$workph\",\"$cellph\",\"$row[email]\",";
      for($t=0;$t<count($tables);$t++)
      {
         $cursp=preg_replace("/off_hist/","",$tables[$t]);
         $sql3="SELECT * FROM $tables[$t] WHERE offid='$row[id]' AND regyr='$schoolyr'";
	 $result3=mysql_query($sql3);
	 if(mysql_num_rows($result3)==0)
	 {
	    $string.="\"\",\"\",";
	 }
         else
	 {
            $curyears=GetSportYears($cursp,$row[id]);
            $string.="\"X\",\"$curyears\",";
	 }
      }
      $string.="\r\n";
      $totalcount++;
      }//end if inrange==1
   }//end while($row....)

   echo $init_html;
   echo "<br><table width='100%'><tr align=center><td><br>";

   if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w")) echo "ERROR";
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   if($runinback==1)
   {
      echo "DONE!";      //alerts "please wait" screen that export is finished; this is echoed to output file
      exit();
   }
   echo "<br><h3>Download Export:</h3><a href=\"reports.php?session=$session&filename=$filename\">$search</a>";
   //header("Location:reports/$filename");
   echo $end_html;
   exit();
}
else if($type=="roster")
{
   $now=time();
   $filename="roster".$now.".csv";
   if($runinback==1) $filename="roster".$filetime.".csv";
   if($sport=="judge") $filename="judge".$filename;

   if($query)
   {
      $sql=$query;
      $sql=ereg_replace("=x","='x'",$sql);
      if(ereg("AS",$sql))
	 $sql.=" ORDER BY t1.last";
      else
	 $sql.=" ORDER BY last";
   }
   else if($sport)
   {
      $sql="SELECT * FROM judges WHERE (speech='x' OR play='x') ORDER BY last,first";
   }
   else
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' AND $sport='x' ORDER BY last,first";
   }

   $result=mysql_query($sql);
   $string="\"Mailing\",";
   if($insincedist!='' && $yeardist!='') $string.="\"District Contracts\",";
   if($insincestate!='' && $yearstate!='') $string.="\"State Contracts\",";
   if($stateyearsineq!='' && $numstateyears!='') $string.="\"State Years\",";
   $string.="\"Last\",\"First\",\"Middle\",";
   if($sport && !ereg("All",$sport) && $sport!='judge') $string.="\"Class\",";
   $string.="\"Address\",\"City\",\"State\",\"Zip\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"E-mail\",\"Gender\",\"Minority\",";
   if($sport=='judge')
      $string.="\"Play\",\"Speech\",";
   else
   {
   $sql2="SHOW TABLES LIKE '%off'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $cursp=preg_replace("/off/","",$row2[0]);
      $string.="\"".strtoupper($cursp)." Years\",";
   }
   }
   $string.="\r\n";
   $regct=0;
   $appct=0;
   $cerct=0;
   $other=0;
   $totalcount=0;
   if(mysql_error()) echo "ERROR<br>$sql<br>".mysql_error()."<br>";
   while($row=mysql_fetch_array($result))
   {
      $inrange=1; 
      if($mailoption==3)
      {
	 //IF mailoption #3, check that official has mailing number in given range in ANY SPORT
         $inrange=0; $showmailing="";
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
               $showmailing.=strtoupper($cursp).": $row2[0]/";
            }
         }
      }
      $offdist=1;
      if($insincedist!='' && $yeardist!='')
      {
         $offdist=0; $showdistcontracts="";
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            if(!$sport || ereg("All",$sport) || $cursp==$sport)
            {
               if(OfficiatedDistricts($row[id],$insincedist,$yeardist,$cursp))
	       {
                  $offdist=1;
	          $showdistcontracts.=strtoupper($cursp)."/";	
	       }
            }
         }
	 $showdistcontracts=substr($showdistcontracts,0,strlen($showdistcontracts)-1);
      }
      $offstate=1;
      if($insincestate!='' && $yearstate!='') 
      {
         $offstate=0; $showstatecontracts="";
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            if(!$sport || ereg("All",$sport) || $cursp==$sport)
            {
               if(OfficiatedState($row[id],$insincestate,$yearstate,$cursp))
               {
                  $offstate=1;
                  $showstatecontracts.=strtoupper($cursp)."/";
               }
            }
         }
         $showstatecontracts=substr($showstatecontracts,0,strlen($showstatecontracts)-1);
      }
      $offyears=1;
      if($stateyearsineq!='' && $numstateyears!='')
      {
         $offyears=0; $showoffyears="";       //assume official does not meet criteria
         for($i=0;$i<count($activity);$i++)
         {
            $cursp=$activity[$i];
            if(!$sport || ereg("All",$sport) || $sport==$cursp)
            {
               if(($stateyearsineq==">=" && YearsOfficiatedState($row[0],$cursp) >= $numstateyears) || ($stateyearsineq=="<=" && YearsOfficiatedState($row[0],$cursp) <= $numstateyears) || ($stateyearsineq=="=" && YearsOfficiatedState($row[0],$cursp) == $numstateyears))	
	       {
                  $offyears=YearsOfficiatedState($row[0],$cursp);
		  $showoffyears.=strtoupper($cursp).":$offyears/";
	       }
            }
         }
	 $showoffyears=substr($showoffyears,0,strlen($showoffyears)-1);
      }
      if($inrange==1 && $offdist==1 && $offstate==1 && $offyears>0)
      {
      if($sport!='judge' && $sport && !ereg("All",$sport))
      {
	 $table=$sport."off";
	 $sql2="SELECT class,mailing FROM $table WHERE offid='$row[id]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $mailing=$row2[mailing];
         switch($row2[0])
         {
	    case "A":
	       $class="Approved";
	       $appct++;
	       break;
	    case "C":
	       $class="Certified";
	       $cerct++;
	       break;
	    case "R":
	       $class="Registered";
	       $regct++;
	       break;
	    default:
	       $other++;
	       $class="";
         }
      }
      else
      {
	 if($sport=='judge')
	    $mailing=$row[datesent];
	 else
	    $mailing=$row[mailing];
      }
      $homeph="H-".substr($row[homeph],0,3)."-".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
      $workph="W-".substr($row[workph],0,3)."-".substr($row[workph],3,3)."-".substr($row[workph],6,4);
      $cellph="C-".substr($row[cellph],0,3)."-".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
      if($mailoption!=3) $showmailing=$mailing;
      $string.="\"$showmailing\",";
      if($insincedist!='' && $yeardist!='') $string.="\"$showdistcontracts\",";
      if($insincestate!='' && $yearstate!='') $string.="\"$showstatecontracts\",";
      if($stateyearsineq!='' && $numstateyears!='') $string.="\"$showoffyears\",";
      $string.="\"$row[last]\",\"$row[first]\",\"$row[middle]\",";
      if($sport!='judge' && $sport && !ereg("All",$sport)) $string.="\"$class\",";
      $string.="\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$homeph\",\"$workph\",\"$cellph\",\"$row[email]\",\"$row[gender]\",\"".strtoupper($row[minority])."\",";
      if($sport=='judge')
	 $string.="\"".strtoupper($row[play])."\",\"".strtoupper($row[speech])."\",";
      else
      {
      $sql2="SHOW TABLES LIKE '%off'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $cursp=preg_replace("/off/","",$row2[0]);
         $curyears=GetSportYears($cursp,$row[id]);
	 $string.="\"$curyears\",";
      }
      }
      $string.="\r\n";
      $totalcount++;
      }//end if inrange==1
   }//end while($row....)
   $total=$regct+$cerct+$appct;
   if($sport!='judge' && $sport && !ereg("All",$sport))
      $overalltotal=$total+$other;
   else
      $overalltotal=mysql_num_rows($result);

   if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w")) echo "ERROR";
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   if($runinback==1) 
   {
      echo "DONE!";      //alerts "please wait" screen that export is finished; this is echoed to output file
      exit();
   }
   echo $init_html;
   echo "<br>";
   if($sport && $sport!='judge' && !ereg("All",$sport))
   {
      for($i=0;$i<count($activity);$i++)
      {
	 if($activity[$i]==$sport)
	    $sportlong=$act_long[$i];
      }
      echo "<table>";
      echo "<tr align=center><th align=center>$sportlong Officials Completing Registration:<hr></th></tr>";
      echo "<tr align=center><td><table>";
      echo "<tr align=right><th align=right>Registered:</th><td align=right>$regct</td></tr>";
      echo "<tr align=right><th align=right>Approved:</th><td align=right>$appct</td></tr>";
      echo "<tr align=right><th align=right>Certified:</th><td align=right>$cerct</td></tr>";
      echo "<tr align=right><th align=right>No Classification:</th><td align=right>$other</td></tr>";
      echo "<tr align=right><td colspan=2><hr></td></tr>";
      echo "<tr align=right><th align=right>Total:</th><td align=right>$total</td></tr>";
      echo "<tr align=right><th align=right>Overall Total:</th><td align=right>$overalltotal</td></tr></table>";
      echo "</td></tr>";
      echo "</table><br>";
   }
   if($mailoption==3 || ($insincestate!='' && $yearstate!='') || ($insincedist!='' && $yeardist!='') || ($stateyearsineq!='' && $numstateyears!='')) 
      $overalltotal=$totalcount;
   echo "<a href=\"reports.php?bypass=1&session=$session&filename=$filename\">Download Export ($overalltotal Results)</a>";
   //header("Location:reports/$filename");
   exit();
}//end if type=roster
else if($type=="all")
{
   if($sport && !ereg("All",$sport))
   {
      $sportname=GetSportName($sport);
      if($query && $query!="")
      {
         if(ereg("AS",$query))
	    $sql=$query." ORDER BY t1.last";
	 else
	    $sql=$query." ORDER BY last";
      }
      else
      {
	 $sql="SELECT * FROM officials WHERE inactive!='x' AND $sport='x' ORDER BY last";
      }
      $result=mysql_query($sql);
      echo $init_html;
      echo "<table width=100% cellspacing=5 cellpadding=5>";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
	 //get info from sport table
	 $table=$sport."off";
	 $sql2="SELECT * FROM $table WHERE offid='$row[id]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $years=$row2[5];//$row2[years];
	 $class=$row2[2];//$row2[class];
	 $currentst=$row2[currentst];
	 $mailing=$row2[mailing];

	 if($ix%2==0) echo "<tr align=left valign=top>";
	 echo "<td align=left><table width=100% cellspacing=1 cellpadding=1><tr align=center valign=top><td>";
         echo "<table cellspacing=1 cellpadding=3>";
	 echo "<tr align=left><td align=left colspan=3>ID: $row[socsec]</td></tr>";
	 echo "<tr align=left><td align=left>$row[last]</td><td align=left>$row[first]</td><td align=left>$row[middle]</td></tr>";
	 echo "<tr align=left><td align=left colspan=3>$row[address]</td></tr>";
	 echo "<tr align=left><td align=left colspan=3>$row[city], $row[state] $row[zip]</td></tr>";
	 $homeph=substr($row[homeph],0,3)."-".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
	 echo "<tr align=left><td align=left colspan=2>Hph: $homeph</td>";
	 echo "<td align=left>Years: $years</td></tr>";
	 $workph=substr($row[workph],0,3)."-".substr($row[workph],3,3)."-".substr($row[workph],6,4);
	 echo "<tr align=left><td align=left colspan=3>Wph: $workph</td>";
	 //get last year this official has a $sport record for
         $table=$sport."off_hist";
	 $sql2="SELECT regyr FROM $table WHERE offid='$row[id]' ORDER BY appdate DESC LIMIT 1";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 echo "<tr align=left><td align=left colspan=3>$row2[0] $sportname Official</td></tr>";
	 echo "<tr align=left><td align=left>Class: $class</td>";
	 echo "<td align=left>ST: $currentst</td>";
	 echo "<td align=left>Mailing: $mailing</td></tr></table></td>";
	 echo "<td><table cellspacing=1 cellpadding=3>";
	 echo "<tr align=left><td align=left>Year</td><td align=left>App</td><td align=left>C</td><td align=left>M</td><td align=left>OBT</td><td align=left>ST</td><td align=left>C</td></tr>";
	 $sql2="SELECT * FROM $table WHERE offid='$row[id]' ORDER BY appdate";
         $result2=mysql_query($sql2);
	 while($row2=mysql_fetch_array($result2))
	 {
	    echo "<tr align=left><td align=left>".substr($row2[regyr],2,3).substr($row2[regyr],7,2)."</td>";
	    echo "<td align=left>".substr($row2[appdate],5,5)."</td>";
	    echo "<td align=left>$row2[contest]</td>";
	    echo "<td align=left>$row2[rm]</td>";
	    echo "<td align=left>$row2[obtest]</td>";
	    echo "<td align=left>$row2[suptest]</td>";
	    echo "<td align=left>$row2[class]</td></tr>";
         }
	 echo "</table></td></tr></table></td>";
	 if(($ix+1)%2==0)
	    echo "</tr>";
	 $ix++;
      }
      echo "</table>";
      echo $end_html;
   }
}
else if($type=="mail") //export mailing label data
{
   //1) generate passcodes for officials without one yet
   //2) create report with mailing info, suptestdate, and passcode as .CSV
   if($query && $query!="")
   {
      if(ereg("AS",$query))
         $sql=$query." ORDER BY t1.last";
      else
         $sql=$query." ORDER BY last";
   }
   else if($sport)
   {
      $offtable=$sport."off";
      if($sport=='judge') $sql="SELECT * FROM judges ORDER BY last";
      else $sql="SELECT t2.* FROM $offtable AS t1,officials AS t2 WHERE t1.offid=t2.id AND t2.inactive!='x' AND t1.payment!='' ORDER BY t2.last";
   }
   else
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' ORDER BY last";
   }

   echo $init_html;
   echo "<br>";
   echo "<table>";
   //get full sport name
   for($i=0;$i<count($activity);$i++)
   {
      if($activity[$i]==$sport)
	 $sportname=$act_long[$i];
   }
   $today=date("F d, Y");
   //get mailing number for this sport
   if(ereg(" judges ",$query)) $sport="judge";
   $sql2="SELECT mailnum,mailnum2 FROM mailing WHERE sport='$sport'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $mailnum=$row2[0]; $mailnum2=$row[1];
   if($sport=='judge')	//get today's date
   {
      $mailnum=date("Y-m-d");
   }
   if($mailoption=='1' && $sport!='judge') 
   {	
      if($whichmailnum=="primary") //need to decrease since mailnum has already been incremented
         $mailnum--; 
      else if($whichmailnum=="secondary") //need to increase
      {
         $mailnum2++; $mailnum=$mailnum2;
      }
   }
   else	if($sport!='judge') //need mail number specified by user
   {
      $getmailnum=$sql;
      $getmailnum=split("[=,]",$getmailnum);
      for($i=0;$i<count($getmailnum);$i++)
      {
	 if(ereg("mailing",$getmailnum[$i]))
	 {
	    $i++;
	    $curmailnum=$getmailnum[$i];
	    $curmailnum=split(" ",$curmailnum);
            $mailnum=ereg_replace("\'","",$curmailnum[1]);
	 }
      }
   }
   //(if mailtype is 1, then mail number was increased/decreased already for this export)

   echo "<tr align=left><td align=left>";
   if($sport=="judge")
   {
      echo "<b>Judges Export:</b></td>";
   }
   else
   {
      echo "<b>Sport:</b> $sportname</td>";
   }
   echo "<td align=left><b>Date:</b> $today</td><td align=left>";
   if($sport=='judge') echo "&nbsp;";
   else echo "<b>Mailing #:</b> $mailnum";
   echo "</td></tr>";

   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   //echo "<tr><td colspan=3>$sql</td></tr>";
   echo "<tr align=left><td colspan=3>Number of Records Exported: <b>$ct</b></td></tr>";
   $ix=0; 
   $csv="";
   if($sport!='judge') $csv.="\"Date\",\"Mailing\",\"NHSOA\"";
   else $csv.="\"PP\",\"PP Mailing\",\"SP\",\"SP Mailing\"";
   $csv.=",\"Last\",\"First\",\"Address\",\"City\",\"State\",\"Zip\",";
   if(!$sport || $sport=="judge")
      $csv.="\"E-mail\",\"New Judge PLAY\",\"New Judge SPEECH\",\"Passcode\"\r\n";
   else
      $csv.="\"Sup Test Date\",\"Passcode\"\r\n";
   $today=date("m/d/Y",time());
   while($row=mysql_fetch_array($result))
   {
      $curid=$row[0];
      //get passcode or generate one
      $sql2="SELECT passcode FROM $logins WHERE offid='$curid'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)	//need to generate passcode
      {
	 $curlast=ereg_replace("\'","",$row[last]);
	 $curlast=ereg_replace(" ","",$curlast);
	 $passcode=ereg_replace(" ","",substr($curlast,0,6));
	 $passcode=ereg_replace("\'","",$passcode);
	 $passcode=ereg_replace("[.]","",$passcode);
	 $num=rand(100,999);
	 $passcode.=$num;
	 //check that this passcode is not already in use
	 $sql3="SELECT id FROM $logins WHERE passcode='$passcode'";
	 $result3=mysql_query($sql3);
	 while(mysql_num_rows($result3)>0)
	 {
	    $oldnum=$num;
	    $num=rand(100,999);
	    $passcode=ereg_replace($oldnum,$num,$passcode);
	    $sql3="SELECT id FROM $logins WHERE passcode='$passcode'";
	    $result3=mysql_query($sql3);
	 }
	 //now passcode is unique; enter into logins table for current off
	 $sql3="INSERT INTO $logins (name,level,passcode,offid) VALUES ('$row[first] $row[last]','2','$passcode','$curid')";
	 $result3=mysql_query($sql3);
	 $curpasscode=$passcode;
      }
      else
      {
	 $row2=mysql_fetch_array($result2);
	 $curpasscode=$row2[0];
      }

      //\"PP\",\"PP Mailing\",\"SP\",\"SP Mailing\"
      if(!$sport || $sport=="judge")
      {
         //get suptestdate for sport if sport chosen
         $sql3="SELECT * FROM judges WHERE id='$curid'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $csv.="\"$row3[play]\",\"$row3[ppdatesent]\",\"$row3[speech]\",\"$row3[spdatesent]\",\"$row[last]\",\"$row[first]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",";
	 $csv.="\"$row[email]\",\"$row[firstyrplay]\",\"$row[firstyrspeech]\",\"$curpasscode\"\r\n";
      }
      else
      {
         //get suptestdate for sport if sport chosen
         $table=$sport."off";
         $sql3="SELECT suptestdate,mailing FROM $table WHERE offid='$curid'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $curstdate=$row3[0]; $curmailing=$row3[1];
         $csv.="\"$today\",\"$curmailing\",\"$row[nhsoa]\",\"$row[last]\",\"$row[first]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",";
	 $csv.="\"$curstdate\",\"$curpasscode\"\r\n";
      }
      echo "<tr align=left><td colspan=3>$row[last], $row[first]</td></tr>";

      $ix++;
   }
   echo "</table>";
   //write to csv file
   $filename=$sport."mailing".$mailnum.".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<br>Open File: <a href=\"reports.php?session=$session&filename=$filename\" target=new2>$filename</a>";
   echo $end_html;
}
else if($type=="member")	//Membership Cards
{
   if($query && $query!="")
   {
      if(ereg("AS",$query))
      $sql=$query." ORDER BY t1.last";
      else
         $sql=$query." ORDER BY last";
   }
   else if($sport)
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' AND $sport='x' ORDER BY last";
   }
   else
   {
      $sql="SELECT * FROM officials WHERE inactive!='x' ORDER BY last";
   }
   $result=mysql_query($sql);
   //echo $sql;
   $table=$sport."off_hist";
   $testtable=$sport."test_results";
   $tableoff=$sport."off";
   $curyr=date("Y",time());
   $curmo=date("m",time());
   if($curmo>=6) 
      $curyr1=$curyr+1;
   else
   {
      $curyr--;
      $curyr1=$curyr+1;
   }
   $regyr="$curyr-$curyr1";
   $csv="\"Last\",\"First\",\"Middle\",\"Address\",\"City\",\"State\",\"Zip\",\"Class\",\"Sup Test Date\",\"OB Test Score\",\"Missed\",\"Years\"\r\n";
   //echo mysql_num_rows($result);
   while($row=mysql_fetch_array($result))
   {
      $curid=$row[0];
      $sql2="SELECT * FROM $tableoff WHERE offid='$curid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $class=$row2['class']; $years=$row2[years];
      $suptestdate=$row2[suptestdate];
      $sql3="SELECT obtest FROM $table WHERE regyr='$regyr' AND offid='$curid'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $obtest=$row3[0];
      $sql2="SELECT * FROM $testtable WHERE offid='$curid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $obtest2=$row2[correct];
      if($obtest=="" && $obtest2!='0')
         $obtest=$obtest2;
	    $last=$row[last];
	    $first=$row[first];
	    $middle=$row[middle];
	    $address=$row[address];
	    $city=$row[city];
	    $state=$row[state];
	    $zip=$row[zip];
	    $missed=$row2[missed];
	    
	    $csv.="\"$last\",\"$first\",\"$middle\",\"$address\",\"$city\",\"$state\",\"$zip\",\"$class\",\"$suptestdate\",\"$obtest\",\"$missed\",\"$years\"\r\n";
   }
   $filename=$sport."membercards.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   //header("Location:reports/$filename");
   echo "<br><a href=\"reports.php?session=$session&filename=$filename\">$filename</a>";
   exit();
}
else if($type=="quick")        //Name, Email, Address
{
   if($query && $query!="")
   {
      if(ereg("AS",$query))
      $sql=$query." ORDER BY t1.last";
      else
         $sql=$query." ORDER BY last";
   }
   $result=mysql_query($sql);
   //echo $sql;
   $csv="\"First\",\"Middle\",\"Last\",\"Email\",\"Address\",\"City\",\"State\",\"Zip\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[first]\",\"$row[middle]\",\"$row[last]\",\"$row[email]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\"\r\n";
   }
   $filename="Judges".date("mdy").".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   //header("Location:reports/$filename");
   echo "<br><a href=\"reports.php?session=$session&filename=$filename\">$filename</a>";
   exit(); 
}
else echo "?";
?>
