<?php
//viewfull.php: View FULL Version of Music Online Entry Form
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
require '../functions.php';
require '../../calculate/functions.php';
require 'mufunctions.php';
require '../variables.php';

if(!$session) $session=$argv[1];

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

//USE ARCHIVE DATABASE?
if(date("m")>=6 && date("m")<=8)
{
   $yr1=date("Y")-1; $yr2=$yr1+1;
   $eligdb="nsaascores".$yr1.$yr2;
}
else $eligdb=$db_name;

$level=GetLevel($session);
if($level==1 && $argv[2]) $school_ch=ereg_replace("`","'",$argv[2]);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
//TESTING
/*
   $sql="USE nsaascores20132014";
   $result=mysql_query($sql);
*/

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else if($level==2 || $level==3)
{
   $thisschool=GetSchool($session);
   if((IsCooping($thisschool,"Vocal") || IsCooping($thisschool,"Instrumental")) && (GetHeadCoopSchool($thisschool,"Vocal")==$school_ch || GetHeadCoopSchool($thisschool,"Instrumental")==$school_ch))
      $school=$school_ch;
   else
      $school=$thisschool;
}
else
{  
   $school=$school_ch;
}  
if(trim($school)=="")    
{
   echo "No school chosen.";
   exit();
}
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$class=$row[classch];
$homedist=$row[homedistrict];
$distid=$row[distid];
$year1=GetFallYear('mu');
$year2=$year1+1;

echo $init_html;
echo "<table width=100%><tr align=center><td>";

$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$distid=$row[distid];
$year1=GetFallYear('mu');
$year2=$year1+1;
$submitted=$row[submitted];
$supervisor=$row[supervisor];
$phone=$row[phone]; $email=$row[email];

echo "<table width=90%><tr align=left><td><a class=small href=\"javascript:print();\">Print this Screen</a></td></tr></table>";
echo "<br><table><caption><b><u>$year1-$year2 OFFICIAL ENTRY FOR NSAA DISTRICT MUSIC CONTEST (Full Version)</b></u><br>";
$csv="\"$year1-$year2 OFFICIAL ENTRY FOR NSAA DISTRICT MUSIC CONTEST:\"\r\n\r\n";
$duedate=$year2."-03-20";	//March 20 of this year
echo "</caption>";
//District Info at Top:
$sql="SELECT * FROM mudistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$multiplesite=$row[multiplesite];
echo "<tr align=center><td colspan=2>";
echo "<table>";
if($multiplesite!='x')
{
echo "<tr align=left><td colspan=2><b>District $row[distnum] -- $row[classes]</b></td></tr>";
$date=split("/",$row[dates]);
$dates="";
for($i=0;$i<count($date);$i++)
{
   $cur=split("-",$date[$i]);
   $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
}
$dates.=$cur[0]; 
echo "<tr align=left><td><b>Date(s):</b></td><td>$dates</td></tr>";
echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
echo "<tr align=left><td><b>Director(s):</b></td><td>$row[director]</td></tr>";
   $distnum=$row[distnum];
}//end if not multiple site
else
{
   $sql="SELECT * FROM mudistricts WHERE (id='$row[distid1]' OR id='$row[distid2]')";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=split("/",$row[dates]);
      $dates="";
      for($i=0;$i<count($date);$i++)
      {
         $cur=split("-",$date[$i]);
         $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
      }
      $dates.=$cur[0];
      echo "<tr align=left><td colspan=2><b>District $row[distnum] -- $row[classes]</b></td></tr>";
      $csv.="\"District $row[distnum] -- $row[classes]\"\r\n";
      echo "<tr align=left><td colspan=2><b>$row[site] Site:</b></td></tr>";
      echo "<tr align=left><td><b>Date(s):</b></td><td>$dates</td></tr>";
      echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
      echo "<tr align=left><td><b>Director(s):</b></td><td>$row[director]</td></tr>";
      echO "<tr align=left valign=top><td><b>Address:</b></td>";
      echO "<td>$row[address1]<br>";
      if($row[address2]!='') echo "$row[address2]<br>";
      echo "$row[city], $row[state]  $row[zip]</td></tr>";
      $distnum=$row[distnum];
      $csv.="\"Date(s):\",\"$dates\"\r\n\"Site:\",\"$row[site]\"\r\n";
      $csv.="\"Director(s):\",\"$row[director]\"\r\n";
   }
   echo "<tr><td colspan=2><br></td></tr>";
}
$sql2="SELECT * FROM mubigdistricts WHERE distnum='$distnum'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2); 
echo "<tr align=left><td><b>District $distnum Coordinator(s):</b></td><td>$row2[coordinator] ($row2[school])</td></tr>";
$csv.="\"District $distnum -- $row[classes]\"\r\n";
$csv.="\"Date(s):\",\"$dates\"\r\n\"Site:\",\"$row[site]\"\r\n\"Director(s):\",\"$row[director]\"\r\n";
$csv.="\"District $distnum Coordinator(s):\",\"$row2[coordinator] ($row2[school])\"\r\n";
$sql2="SELECT * FROM muschools WHERE id='$schid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left><td><b>Your School:</b></td><td>$row2[school]</td></tr>";
$sql3="SELECT * FROM headers WHERE school='".addslashes($row2[school])."'";
$result3=mysql_query($sql3);
$row3=mysql_fetch_array($result3);
echo "<tr valign=top align=left><td><b>School Address:</b></td><td>";   
echo "$row3[address1]<br>";
if($row3[address2]!='') echo "$row3[address2]<br>";
echo "$row3[city_state] $row3[zip]</td></tr>";
echo "<tr align=left><td><b>Class:</b></td><td>$row2[classch]</td></tr>";
echo "<tr align=left><td><b>Number of Students Entered:</b></td><td>$row2[studcount]</td></tr>";
echo "<tr align=left><td colspan=2>".GetEntryStatus($schid)."</td></tr>";
$csv.="\"Your School:\",\"$row2[school]\"\r\n";
$csv.="\"School Address:\",\"$row3[address1],$row3[address2]\"\r\n\"\",\"$row3[city_state] $row3[zip]\"\r\n";
$csv.="\"Class:\",\"$row2[classch]\"\r\n";
$csv.="\"Number of Students Entered:\",\"$row2[studcount]\"\r\n";
      echo "<tr align=left><td colspan=2><b>MUSIC DIRECTORS:</b></td></tr>";
      echo "<tr align=center><td colspan=2><table>";
      echo "<tr align=left><td><b>Director of:</b></td><td><b>Director's Name:</b></td>";
      echo "<td><b>E-mail:</b></td><td><b>School Phone:</b></td><td><b>School Fax:</b></td><td><b>Home Phone:</b></td></tr>";
      $csv.="\"Director of:\",\"Director's Name:\",\"E-mail:\",\"School Phone:\",\"School Fax:\",\"Home Phone:\"\r\n";
      for($i=0;$i<count($mudirs);$i++)
      {
         echo "<tr align=left>";
         echo "<td><b>$mudirs[$i]:</b></td>";
         $name=$mudirs_sm[$i]; $email=$name."email"; $school3=$name."school"; $home=$name."home";
	 $schoolf=$name."schoolf";
         $row2[$school3]=ereg_replace("X"," Ext. ",$row2[$school3]);
         echo "<td>".$row2[$name]."</td>";
         echo "<td>".$row2[$email]."</td>";
         echo "<td>".$row2[$school3]."</td>";
	 echo "<td>".$row2[$schoolf]."</td>";
         echo "<td>".$row2[$home]."</td>";
         echo "</tr>";
	 $csv.="\"$mudirs[$i]\",\"".$row2[$name]."\",\"".$row2[$email]."\",\"".$row2[$school3]."\",\"".$row2[$schoolf]."\",\"".$row2[$home]."\"\r\n";
      }
      echo "</table></td></tr>";
      $csv.="\r\n";
echo "</table></td></tr>";
echo "<tr valign=top align=center><td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3><tr align=center valign=top><td>";

$sql0="SELECT * FROM mucategories ORDER BY vieworder";
$result0=mysql_query($sql0);
$i=0; $categs=array();
while($row0=mysql_fetch_array($result0))
{
   $categs[id][$i]=$row0[id];
   $categs[category][$i]=$row0[category];
   $categs[vieworder][$i]=$row0[vieworder];
   $highvieworder=$row0[vieworder];
   $i++;
}
$categs[id][$i]=0;      //Small Misc Vocal & Inst Ensemble
$categs[category][$i]="Miscellaneous Small Vocal & Instrumental Ensemble";
$categs[vieworder][$i]=$highvieworder+1;
for($j=0;$j<count($categs[id]);$j++)
{
   $categ=$categs[category][$j];
   $categid=$categs[id][$j];
   $vieworder=$categs[vieworder][$j];
   if($vieworder==2 || $vieworder==3 || $vieworder==5 || $vieworder==7) 
      echo "</td><td>";
   else if($vieworder==4 || $vieworder==6) 
      echo "</td></tr></table></td></tr><tr align=center><td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3><tr align=center valign=top><td>";
   
   echo "<table cellspacing=2 cellpadding=3><caption><font style=\"font-size:9pt\"><b>".strtoupper($categ).":</b></font></caption>";
   $csv.="\"".strtoupper($categ).":\"\r\n";
   if(IsCooping($school,"Vocal") && !IsHeadCoopSchool($school,"Vocal") && ereg("Vocal",$categ))
   {
      echo "<tr align=left><td>"; 
      $headsch=GetHeadCoopSchool($school,"Vocal");
      echo "Co-oping with $headsch for Vocal Music.  Please see $headsch's entry.";
      echo "</td></tr></table></td>";
      $csv.="\"Co-oping with $headsch for Vocal Music.\"\r\n";
   }
   else if(IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Instrumental") && ereg("Instrumental",$categ))
   {
      echo "<tr align=left><td>";
      $headsch=GetHeadCoopSchool($school,"Instrumental");
      echo "Co-oping with $headsch for Instrumental Music.  Please see $headsch's entry.";
      echo "</td></tr></table></td>";
      $csv.="\"Co-oping with $headsch for Instrumental Music.\"\r\n";
   }
   else if(!ereg("Solo",$categ))	//Everything but Solos 
   {
      $sql="SELECT * FROM muensembles WHERE categid='$categid' ORDER BY orderby,id";
      $result=mysql_query($sql);
      $total=mysql_num_rows($result);
      $percol=$total/2;
      $curcol=0;
      $curfee=0;
      echo "<tr align=left valign=top><td>";
      $csv.="\"\",\"Ensemble\",\"No. in Group\",\"Accompanist\",\"Students\",\"Details\"\r\n";
      $string="";
      while($row=mysql_fetch_array($result))
      {
         if($open==$row[id]) $thisopen="";
	 else $thisopen=$row[id];
         $curct=CountEnsembles($schid,$row[id]);
         $ensembleid=$row[id];
	 if($curct>0)
         {
	    $string.="<b>$row[ensemble]</b>";
	    $string.=" [$curct]";
	    $string.="<br><table width=100% cellspacing=2 cellpadding=3 bgcolor=#E0E0E0>";
	 }
         $sql1="SELECT * FROM muentries WHERE ensembleid='$row[id]' AND schoolid='$schid' ORDER BY id";
	 $result1=mysql_query($sql1);
	 //$string.="<tr align=left><td>CURCT: $curct ".mysql_num_rows($result1)."</td></tr>";
	 $ct=1;
	 while($row1=mysql_fetch_array($result1))
	 {
	    $string.="<tr align=left><td>#$ct)";
	    if(ereg("Misc",$row[ensemble]))
	    {
	       $csv.="\"$ct\",\"$row1[event]";
	       $string.=" <b>$row1[event]</b>";
	       if($row1[strings]=='x') 
	       {
	          $string.=" (All Strings Ensemble)";
		  $csv.=" (All Strings Ensemble)";
	       }
	       $csv.="\",";
	    }
	    else
	       $csv.="\"$ct\",\"$row[ensemble]\",";
	    $string.="<br>";
	    $sql2="SELECT t2.first,t2.last FROM mustudentries AS t1, $eligdb.eligibility AS t2 WHERE t1.studentid=t2.id AND t1.entryid='$row1[id]' ORDER BY t2.last,t2.first";
	    $result2=mysql_query($sql2);
	    if(ereg("Large",$categ))
	    {
	       if(CountStudentsInEntry($row1[id])>0)
	          $groupsize=CountStudentsInEntry($row1[id]);
	       else
		  $groupsize=$row1[groupsize];
	       if($groupsize=="") $groupsize='0';
	    }
	    else
	       $groupsize=CountStudentsInEntry($row1[id]);
	    $string.="No. in Group: $groupsize<br>";
	    $csv.="\"$groupsize\",";
	    if(!ereg("Large Instrumental",$categ) && $row1[accompanist]!='')
	    {
	       $string.="Accompanist: $row1[accompanist]<br>";
	       $csv.="\"$row1[accompanist]\",";
	    }
	    else $csv.="\"\",";
	    $studs="";
	    while($row2=mysql_fetch_array($result2))
	    {
               if($studs=="")	//(first row on Excel sheet)
	          $csv.="\"$row2[first] $row2[last]\"\r\n";
	       else
	          $csv.="\"\",\"\",\"\",\"\",\"$row2[first] $row2[last]\"\r\n";
	       $studs.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row2[first] $row2[last]<br>"; 
	    }
	    $studs=substr($studs,0,strlen($studs)-4);
	    if(trim($studs)=="") 
	    {
	       $studs="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[No students entered]";
	       $csv.="\"[No students entered]\"\r\n";
    	    }
	    $string.=$studs."</td></tr>";
	    //Percussion Ensemble: Add instruments
	    if($row[ensemble]=="Percussion Ensemble")
	    {
	       $string.="<tr align=left><td><b>Instruments:</b><br><table cellspacing=0 cellpadding=3><tr align=center><td><b>Instrument</b></td><td><b>Using</b></td><td><b>Providing</b></td></tr>";
	       $sql2="SELECT * FROM mupercinsts WHERE entryid='$row1[id]' ORDER BY instid";
	       $result2=mysql_query($sql2);
	       $csv.="\"Instruments: "; 
	       while($row2=mysql_fetch_array($result2))
	       {
		  if($percinst[$row2[instid]]=="Other" && trim($row2[other])!='')
		  {
		     $string.="<tr align=left valign=top><td><b>Other:</b></td><td colspan=2>".$row2[other]."</td></tr>";
		     $csv.=$row2[other].", ";
	          }
	          else if($row2[isusing]=='x' || $row2[isproviding]=='x')
		  {
		     $string.="<tr align=center><td align=left>".$percinst[$row2[instid]]."</td><td>".strtoupper($row2[isusing])."</td><td>".strtoupper($row2[isproviding])."</td></tr>"; 
		     $csv.=$percinst[$row2[instid]];
		     if($row2[isproviding]) $csv.=" (providing)";
		     $csv.=", ";
		  }
	       }
	       $string.="</table></td></tr>";
	       $csv.="\"";
	    }
	    $csv.="\r\n";
	    $ct++;
	 }   //end for each entry in this ensemble
         $curfee+=($curct*GetEnsembleFee($distid,$ensembleid));
	 if($curct>0)
	    $string.="</table><entry>";
	 $curcol++;
      } //end for each ensemble
      $string=split("<entry>",$string);
      for($i=0;$i<count($string);$i++)
      {
	 if($i==(count($string)/2)) echo "</td><td>";
	 echo $string[$i];
      }
      unset($string);
      echo "</td></tr>";
      $curfee=number_format($curfee,2,'.','');
      echo "<tr align=right><td colspan=2><b>Total Fee for $categ Entries: $<u>".$curfee."</u></b></td></tr>";
      $csv.="\"\",\"\",\"\",\"\",\"Total Fee for $categ Entries:\",\"$".$curfee."\"\r\n";
      echo "</table><br>";
   }//end if NOT Solos 
   else		//SOLOS
   {
      echo "<table><tr align=left><td><b>Event:</b></td><td><b>Contestant:</b></td><td><b>Accompanist</b></td></tr>";
      $csv.="\"\",\"Event\",\"Contestant\",\"Accompanist\"\r\n";
      $sql="SELECT * FROM muensembles WHERE categid='$categid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      //show existing solos:
      $sql2="SELECT t1.*,t2.first,t2.last FROM muentries AS t1, $eligdb.eligibility AS t2 WHERE t1.studentid=t2.id AND t1.ensembleid='$row[id]' AND t1.schoolid='$schid' ORDER BY t2.last,t2.first";
      $result2=mysql_query($sql2);
      //echo "<tr align=left><td>$sql2</td></tr>";
      $ix=0;
      while($row2=mysql_fetch_array($result2))
      {
         $place=$ix+1;
         echo "<tr align=left><td>$place) $row2[event]</td>";
         echo "<td>$row2[first] $row2[last]</td>";
         echo "<td>$row2[accompanist]</td></tr>";
	 $csv.="\"$place\",\"$row2[event]\",\"$row2[first] $row2[last]\",\"$row2[accompanist]\"\r\n";
         $ix++;
      }
      $curfee=number_format(CountEnsembles($schid,$row[id])*GetEnsembleFee($distid,$row[id]),2,'.','');
      echo "<tr align=right><td colspan=3><b>Total Fee for $categ Entries: $<u>".$curfee."</u></b></td></tr>";
      $csv.="\"\",\"\",\"\",\"Total Fee for $categ Entries:\",\"$curfee\"\r\n";
      echo "</table>";
   }
}//end for each category
echo "</td></tr></table></td></tr>";

$totalfee=0;
echo "<tr align=right><td><br><table bordercolor=#000000 cellspacing=0 cellpadding=2 frame=\"border\" rules=\"
none\">";
echo "<tr align=left><td colspan=2><b><u>ENTRY FEES SUMMARY:</u></b></td></tr>";
$csv.="\r\n\"ENTRY FEES SUMMARY:\"\r\n";
$field="surcharge".$class;
$sql="SELECT $field,nondistfee,multiplesite,distid1,distid2 FROM mudistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[multiplesite]=='x')
{
   $multiplesite='x';
   $sql="SELECT $field,nondistfee FROM mudistricts WHERE (id='$row[distid1]' OR id='$row[distid2]')";
   $result=mysql_query($sql);
   $nondistfee=0; $surcharge=0;
   while($row=mysql_fetch_array($result))
   {
      $nondistfee+=$row[nondistfee]; $surcharge+=$row[$field];
   }
}
else
{
   $multiplesite="";
   $nondistfee=$row[nondistfee]; $surcharge=$row[$field];
}
echo "<tr align=left><td><b>Surcharge:</b></td><td align=right>$";
if($surcharge>0)   //yes, there is a surcharge
   $surcharge=number_format($surcharge,2,'.','');
else            //no, there is no surcharge
   $surcharge=number_format(0,2,'.','');
echo "$surcharge</td></tr>";
$csv.="\"Surcharge:\",\"$".$surcharge."\"\r\n";
$totalfee+=$surcharge;
   //SMALL ENSEMBLE FEES:
$sql="SELECT t1.id FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Small'";
$result=mysql_query($sql);
$ix=0; $smallcount=0;
echo "<tr align=left><td><b>Small Ensembles:</b></td><td align=right>";
$smallfee=0;
while($row=mysql_fetch_array($result))
{  
   if(GetEnsembleFee($distid,$row[id])>0) $smallfee=GetEnsembleFee($distid,$row[id]);
   $curcount=CountEnsembles($schid,$row[id]);
   $smallcount+=$curcount;
   $ix++;
}
$smallcount+=CountEnsembles($schid,60); //MISC SMALL VOCAL & INSTRUMENTAL ENSEMBLES
$totalsmallfee=number_format($smallcount*$smallfee,2,'.','');
$totalfee+=$totalsmallfee;
echo "$".$totalsmallfee."</td></tr>";
$csv.="\"Small Ensembles:\",\"$".$totalsmallfee."\"\r\n";
   //SOLO FEES:
$sql="SELECT id FROM muensembles WHERE ensemble LIKE 'Instrumental Solo%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$solofee=GetEnsembleFee($distid,$row[id]);
$solocount=CountEnsembles($schid,$row[id]);
$instfee=$solocount*$solofee;
$sql="SELECT id FROM muensembles WHERE ensemble LIKE 'Vocal Solo%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$solofee=GetEnsembleFee($distid,$row[id]);
$solocount=CountEnsembles($schid,$row[id]);
$vocfee=$solocount*$solofee;
 
echo "<tr align=left><td><b>Solos:</b></td><td align=right>";
$totalsolofee=number_format($vocfee+$instfee,2,'.','');
$totalfee+=$totalsolofee;
echo "$".$totalsolofee."</td></tr>";
$csv.="\"Solos:\",\"$".$totalsolofee."\"\r\n";
   //LARGE ENSEMBLES:
$sql="SELECT t1.id,t1.ensemble FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Large' ORDER BY t1.orderby";
$result=mysql_query($sql);
$largefee=array();
$largecount=array();
$totallargefee=array();
$ix=0;
echo "<tr align=left><td><b>Large Ensembles:</b></td><td align=right>";
$overalllargefee=0;
while($row=mysql_fetch_array($result))
{
   $largefee[$ix]=GetEnsembleFee($distid,$row[id]);
   $largecount[$ix]=CountEnsembles($schid,$row[id]);
   $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
   $overalllargefee+=$totallargefee[$ix];
   $ix++;
}
$overalllargefee=number_format($overalllargefee,2,'.','');
$totalfee+=$overalllargefee;
echo "$".$overalllargefee."</td></tr>";
$csv.="\"Large Ensembles:\",\"$".$overalllargefee."\"\r\n";
   //NON-MEMBER FEES:
echo "<tr align=left><td><b>Fee for Non-Member Schools:</b></td><td align=right>";
if($homedist!="")
{
   $nondistfee=number_format($nondistfee,2,'.','');
   $totalfee+=$nondistfee;
   echo "$".$nondistfee."</td></tr>";
}
else
{
   echo "N/A</td></tr>";
   $nondistfee="N/A";
}
$csv.="\"Fee for Non-Member Schools:\",\"";
if($nondistfee!="N/A") $csv.="$";
$csv.=$nondistfee."\"\r\n";
   //TOTAL FEES:
$totalfee=number_format($totalfee,2,'.','');
echo "<tr align=center><td colspan=2><hr></td></tr>";
echo "<tr align=right><td align=right><b>TOTAL ENTRY FEES:</td>";
echo "<th align=right>$".$totalfee."</th></tr>";
if($multiplesite=='x')
   echo "<tr align=left><td colspan=2><b><u>NOTE</u></b>: Please refer to breakdown of the fees<br>owed to EACH SITE in the Payment Summary.</td></tr>";
echo "</table></td></tr>";
$csv.="\"TOTAL ENTRY FEES:\",\"$".$totalfee."\"\r\n";
if($multiplesite=='x')
   $csv.="\"NOTE: Please refer to the breakdown of the fees owed to EACH SITE in the Payment Summary.\r\n";

echo "</table>";
$file=strtolower($school);
$file=ereg_replace(" ","",$file);
$file=ereg_replace("[.]","",$file);
$file=ereg_replace("\'","",$file);
$file=ereg_replace("-","",$file);
$file.="full";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$file.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$file.csv");
//echo "<a target=new2 href=\"/home/nsaahome/attachments/$file.csv\">$file.csv</a>";
echo $end_html;
?>
