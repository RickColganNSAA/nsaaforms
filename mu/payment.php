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
require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

if(!$session) $session=$argv[1];

$header=GetHeader($session);
$level=GetLevel($session);

if($level==1 && $argv[2]) $school_ch=ereg_replace("`","'",$argv[2]);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
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

if($showdistid && $showdistid!='')
{
   //check that school is attending this district
   $sql="SELECT t1.*,t2.id AS schid,t2.classch FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[distid1]==$showdistid || $row[distid2]==$showdistid)	//YES
   {
      $schid=$row[schid]; $class=$row[classch];  $maindistid=$row[id];
      $sql="SELECT * FROM mudistricts WHERE id='$showdistid'";
      $result=mysql_query($sql); 
      $row=mysql_fetch_array($result);
      $bigdist=$row[distnum]; $classes=$row[classes]." ($row[site])";
      $distid=$showdistid; $multiplesite='x'; 
      $sql3="SELECT * FROM mudistricts WHERE (id='$row[distid1]' OR id='$row[distid2]')";
      $result3=mysql_query($sql3);
      $index=0; $sendfees="";
      while($row3=mysql_fetch_array($result3))
      {
         if($row3[feeaddress1]=='')
         {
            $feename=$row3[director];
            $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
            $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
         }
         else
         {
            $feename=$row3[feename];
            $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
            $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
         }
         $sendfees.="<b>Please send fees for the contest at $row3[site] to:</b><br>";
         $sendfees.="$feename<br>$feeaddress1<br>";
         if($feeaddress2!='') $sendfees.="$feeaddress2<br>";
         $sendfees.="$feecity, $feestate $feezip<br>";
         $sendfees.="<b>Make checks payable to:</b> $row3[checks]<br><br>";
         $index++;
      }
   }
   else
      unset($showdistid);
}
if(!$showdistid)
{
   //get big district, class this school is in
   $sql="SELECT t1.id AS schid,t1.classch,t2.id AS distid,t2.distnum,t2.classes,t2.multiplesite,t2.distid1,t2.distid2 FROM muschools AS t1, mudistricts AS t2 WHERE t1.distid=t2.id AND t1.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row[classch];
   $bigdist=$row[distnum]; $classes=$row[classes];
   $schid=$row[schid];
   $distid=$row[distid]; $distid1=$row[distid1]; $distid2=$row[distid2]; $multiplesite=$row[multiplesite];
      $sql="SELECT * FROM mudistricts WHERE id='$distid1'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
 	$classes1="$row[classes] (at $row[site])"; $site1=$row[site];
      $sql="SELECT * FROM mudistricts WHERE id='$distid2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        $classes2="$row[classes] (at $row[site])"; $site2=$row[site];
   if($multiplesite=='x')
   {
      $sql3="SELECT * FROM mudistricts WHERE (id='$distid1' OR id='$distid2')";
      $result3=mysql_query($sql3);
      $index=0; $sendfees="";
      while($row3=mysql_fetch_array($result3))
      {
         if($row3[feeaddress1]=='')
         {
            $feename=$row3[director];
            $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
            $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
         }
         else
         {
            $feename=$row3[feename];
            $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
            $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
         }
         $sendfees.="<b>Please send fees for the contest at $row3[site] to:</b><br>";
         $sendfees.="$feename<br>$feeaddress1<br>";
         if($feeaddress2!='') $sendfees.="$feeaddress2<br>";
         $sendfees.="$feecity, $feestate $feezip<br>";
         $sendfees.="<b>Make checks payable to:</b> $row3[checks]<br><br>";
         $index++;
      }
   }
   else
   {
      $sql3="SELECT * FROM mudistricts WHERE id='$distid'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $index=0; $sendfees="";
      if($row3[feeaddress1]=='')
      {
         $feename=$row3[director];
         $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
         $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
      }
      else
      {
         $feename=$row3[feename];
         $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
         $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
      }
      $sendfees.="<b>Please send your fees to:</b><br>";
      $sendfees.="$feename<br>$feeaddress1<br>";
      if($feeaddress2!='') $sendfees.="$feeaddress2<br>";
      $sendfees.="$feecity, $feestate $feezip<br>";
      $sendfees.="<b>Make checks payable to:</b> $row3[checks]";
   }
}

if($homedist || $member)
{
   if($member=='y')
   {
      $sql="UPDATE muschools SET homedistrict='' WHERE id='$schid'";
   }
   else if($member=='n' || $homedist)
   {
      $sql="UPDATE muschools SET homedistrict='$homedist' WHERE id='$schid'";
   }
   $result=mysql_query($sql);
}
else
{
   $sql="SELECT homedistrict FROM muschools WHERE id='$schid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[0]!="")
   {
      $member='n'; $homedist=$row[0];
   }
   else
   {
     $member='y'; $homedist='';
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td><br>";
echo "<table width=90%><tr align=left><td><a class=small href=\"javascript:print();\">Print this Screen</a></td></tr></table>";

echo "<form method=post action=\"payment.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table cellspacing=0 cellpadding=5><caption><b><u>District $bigdist -- $classes<br>Music Entry Fee Schedule:</b></u><br>";
echo "<table width=400><tr align=left><td><b>School:</b>&nbsp;&nbsp;$school</td></tr>";
//IF co-oping for Vocal and not Instrumental, and is NOT head school, only show Instrumental Fees
// and surcharge if applicable
//ELSE IF co-oping for Instrumental and not Vocal, and is NOT head school, only show Vocal Fees
// and surcharge if applicable 
//ELSE IF co-oping for Vocal and not Instrumental and IS head school, show own Instrumental Fees
// and co-op's Vocal Fees
//ELSE IF co-oping for Instrumental and not Vocal and IS head school, show own Vocal Fees
// and co-op's Instrumental Fees
//ELSE IF co-opint for BOTH and IS head school, show co-op's V & I Fees
//ELSE, not co-oping (nothing changes)
//(non-head school of a full co-op will not have their own payment link)
$vocalonly=0; $instrumentalonly=0;
if(IsCooping($school,"Vocal") && !IsCooping($school,"Instrumental") && !IsHeadCoopSchool($school,"Vocal"))
{
   $instrumentalonly=1;
   echo "<tr align=left><td>The following fee schedule is for <b>$school's Instrumental Music Entries</b>.</td></tr>";
   echo "<tr align=left><td>$school is co-oping with ".GetHeadCoopSchool($school,"Vocal")." for Vocal Music.  The fees for this <b>Vocal Music Co-op Entry</b> can be found in ".GetHeadCoopSchool($school,"Vocal")."'s Music Contest Entry Form Payment Summary.</td></tr>";
}
else if(IsCooping($school,"Instrumental") && !IsCooping($school,"Vocal") && !IsHeadCoopSchool($school,"Instrumental"))
{
   $vocalonly=1;
   echo "<tr align=left><td>The following fee schedule is for <b>$school's Vocal Music Entries</b>.</td></tr>";
   echo "<tr align=left><td>$school is co-oping with ".GetHeadCoopSchool($school,"Instrumental")." for Instrumental Music.  The fees for this <b>Instrumental Music Co-op Entry</b> can be found in ".GetHeadCoopSchool($school,"Instrumental")."'s Music Contest Entry Form Payment Summary.</td></tr>";
}
else if(IsCooping($school,"Vocal") && !IsCooping($school,"Instrumental") && IsHeadCoopSchool($school,"Vocal"))
{
   echo "<tr align=left><td>The following fee schedule is for <b>$school's Instrumental Entries</b> and the <b>Vocal Entries for the co-op with ".GetOtherCoopSchool($school,"Vocal")."</b>.</td></tr>";
}
else if(IsCooping($school,"Instrumental") && !IsCooping($school,"Vocal") && IsHeadCoopSchool($school,"Instrumental"))
{
   echo "<tr align=left><td>The following fee schedule is for <b>$school's Vocal Entries</b> and the <b>Instrumental Entries for the co-op with ".GetOtherCoopSchool($school,"Instrumental")."</b>.</td></tr>";
}
else if(IsCooping($school,"Instrumental") && IsCooping($school,"Vocal"))
{
   echo "<tr align=left><td>The following fee schedule is for the <b>Co-Op between $school and ".GetOtherCoopSchool($school,"Vocal")."</b>.</td></tr>";
}
echo "</table>";
echo "<br></caption>";
$csv="\"District $bigdist Music Entry Fee Schedule:\"\r\n\r\n";

$color1="#CCFF66";
$color2="#99CCFF";
$color3="yellow";
if($multiplesite=='x')
{
echo "<tr align=left><td colspan=4 bgcolor=$color1>Fees to be sent to $site1 are highlighted in this color.</td></tr>";
echo "<tr align=left><td colspan=4 bgcolor=$color2>Fees to be sent to $site2 are highlighted in this color.</td></tr>";
}
echo "<tr><td colspan=4>&nbsp;<br></td></tr>";

//Is there a SURCHARGE? (get non-member fees here too)
$field="surcharge".$class;
$sql="SELECT $field,nondistfee FROM mudistricts WHERE id='$distid'";
if($multiplesite=='x')
   $sql="SELECT $field,nondistfee FROM mudistricts WHERE id='$distid1'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$nondistfee=$row[1];
echo "<tr align=left valign=top";
echo " bgcolor=$color1";
echo "><td><b>1)</b></td>";
$csv.="\"1.\",";
if($row[0]>0)	//yes, there is a surcharge
{
   $surcharge=number_format($row[0],2,'.','');
   echo "<td colspan=2>The <b><u>surcharge</u></b> for <b>District <u>$bigdist</u></b>, <b>Class <u>$class</u></b> is <b>$<u>$surcharge</u></b>.</td>";
   $csv.="\"The surcharge for District $bigdist, Class $class is $surcharge.\",";
}
else if($class=="")	//class unknown
{
   $surcharge=number_format($row[0],2,'.','');
   echO "<td colspan=2 width=400><b>You have not entered your school's Class</b>.  Please <a class=small href=\"javascript:window.close();\">Close this Window</a> and enter your school's Class in the \"Contact Info & Student Entry Count\" section of your form.  Thank you</td>";
}
else		//no, there is no surcharge
{
   $surcharge=number_format(0,2,'.','');
   echo "<td colspan=2>There is <b><u>no surcharge</b></u> for <b>District <u>$bigdist</u></b>, <b>Class <u>$class</u></b>.</td>";
   $csv.="\"There is no surcharge for District $surcharge, Class $class.\",";
}
echo "<td align=right bgcolor=$color";
echo ">$".$surcharge."</td></tr>";
$csv.="\"$".$surcharge."\"";
if($multiplesite=='x')
   $csv.=",\"$site1\"";
$csv.="\r\n\r\n";
$surcharge1=$surcharge;
if($multiplesite=='x')
{
   $nondistfee1=$nondistfee;
   $sql="SELECT $field,nondistfee FROM mudistricts WHERE id='$distid2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$nondistfee2=$row[1];
echo "<tr align=left valign=top";
echo " bgcolor=$color2";
echo "><td>&nbsp;</td>";
$csv.="\"1.\",";
if($row[0]>0)   //yes, there is a surcharge
{
   $surcharge=number_format($row[0],2,'.','');
   echo "<td colspan=2>The <b><u>surcharge</u></b> for <b>District <u>$bigdist</u></b>, <b>Class <u>$class</u></b> is <b>$<u>$surcharge</u></b>.</td>";
   $csv.="\"The surcharge for District $bigdist, Class $class is $surcharge.\",";
}
else if($class=="")     //class unknown
{
   $surcharge=number_format($row[0],2,'.','');
   echO "<td colspan=2 width=400><b>You have not entered your school's Class</b>.  Please <a class=small href=\"javascript:window.close();\">Close this Window</a> and enter your school's Class in the \"Contact Info & Student Entry Count\" section of your form.  Thank you</td>";
}
else            //no, there is no surcharge
{
   $surcharge=number_format(0,2,'.','');
   echo "<td colspan=2>There is <b><u>no surcharge</b></u> for <b>District <u>$bigdist</u></b>, <b>Class <u>$class</u></b>.</td>";
   $csv.="\"There is no surcharge for District $surcharge, Class $class.\",";
}
echo "<td align=right bgcolor=$color";
echo ">$".$surcharge."</td></tr>";
$csv.="\"$".$surcharge."\"";
if($multiplesite=='x')
   $csv.=",\"$site2\"";
$csv.="\r\n\r\n";
$surcharge2=$surcharge;
}//end if multiple site

//Fee for EACH EVENT:
echo "<tr align=left valign=top><td><b>2)</b></td>";
$csv.="\"2.\",\"EVENT FEES:\"\r\n";
echo "<td colspan=3><b><u>Event Fees:</u></b></td></tr>";
   //SMALL ENSEMBLE FEES:
if(!$instrumentalonly)
{
   //Vocal:
$sql="SELECT t1.id,t1.categid FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Small' AND t2.vocinst='Vocal'";
$result=mysql_query($sql);
$ix=0; $smallVcount=0;  $smallVcount1=0; $smallVcount2=0;
while($row=mysql_fetch_array($result))
{
   if($ix==0)
   {
      $ensid=$row[id];
      if(PayingForEnsemble($distid,$distid1,$ensid))
      {
	 $smallfee=GetEnsembleFee($distid1,$ensid); $color=$color1;
      }
      else if(PayingForEnsemble($distid,$distid2,$ensid))
      {
	 $smallfee=GetEnsembleFee($distid2,$ensid); $color=$color2;
      }
      else
      {
         $smallfee=GetEnsembleFee($distid,$ensid); $color=$color1;
      }
      echo "<tr align=left><td>&nbsp;</td><td colspan=3><b>Small Ensembles:</b>&nbsp;";
      echo "$".$smallfee."/each</td></tr>";
      $csv.="\"\",\"Small Ensembles:\",\"$".$smallfee."\"\r\n";
   }
   $curcount=CountEnsembles($schid,$row[id]);
   $smallVcount+=$curcount; 
   $ix++;
}
echo "<tr align=left";
echo "><td>&nbsp;</td><td>You have entered <b><u>$smallVcount</u> Small Vocal Ensemble";
$csv.="\"\",\"You have entered $smallVcount Small Vocal Ensemble";
if($smallVcount!=1) 
{
   echo "s"; $csv.="s";
}
echo "</b>.</td>";
$csv.=".\",";
echo "<td align=right>$smallVcount x $".$smallfee." = </td>";
$csv.="\"$smallVcount x $".$smallfee." = \",";
$totalsmallVfee=number_format($smallVcount*$smallfee,2,'.','');
echo "<td align=right";
echo " bgcolor=$color";
echo ">$".$totalsmallVfee."</td></tr>";
if($color==$color1)
{
   $totalsmallVfee1=$totalsmallVfee;
   $totalsmallVfee2=0;
}
else if($color==$color2)
{
   $totalsmallVfee2=$totalsmallVfee;
   $totalsmallVfee1=0;
}
$csv.="\"$".$totalsmallVfee."\"";
if($multiplesite=='x' && $color==$color1)
   $csv.=",\"$site1\"";
else if($multiplesite=='x' && $color==$color2)
   $csv.=",\"$site2\"";
$csv.="\r\n\r\n";
}
if(!$vocalonly)
{
   //Instrumental:
$sql="SELECT t1.id FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Small' AND t2.vocinst='Instrumental'";
$result=mysql_query($sql);
$smallIcount=0;
while($row=mysql_fetch_array($result))
{
   if($ix==0)
   {
      $ensid=$row[id];
      if(PayingForEnsemble($distid,$distid1,$ensid))
      {
         $smallfee=GetEnsembleFee($distid1,$ensid); $color=$color1;
      }
      else if(PayingForEnsemble($distid,$distid2,$ensid))
      {
         $smallfee=GetEnsembleFee($distid2,$ensid); $color=$color2;
      }
      else
      {
         $smallfee=GetEnsembleFee($distid,$ensid); $color=$color1;
      }
      echo "<tr align=left><td>&nbsp;</td><td colspan=3><b>Small Ensembles:</b>&nbsp;";
      echo "$".$smallfee."/each</td></tr>";
      $csv.="\"\",\"Small Ensembles:\",\"$".$smallfee."\"\r\n";
   }
   $curcount=CountEnsembles($schid,$row[id]);
   $smallIcount+=$curcount;
   $ix++;
}
echo "<tr align=left";
echo "><td>&nbsp;</td><td>You have entered <b><u>$smallIcount</u> Small Instrumental Ensemble";
$csv.="\"\",\"You have entered $smallIcount Small Instrumental Ensemble";
if($smallIcount!=1)
{
   echo "s"; $csv.="s";
}
echo "</b>.</td>";
$csv.=".\",";
echo "<td align=right>$smallIcount x $".$smallfee." = </td>";
$csv.="\"$smallIcount x $".$smallfee." = \",";
$totalsmallIfee=number_format($smallIcount*$smallfee,2,'.','');
if($color==$color1)
{
   $totalsmallIfee1=$totalsmallIfee;
   $totalsmallIfee2=0;
}
else if($color==$color2)
{
   $totalsmallIfee2=$totalsmallIfee;
   $totalsmallIfee1=0;
}
echo "<td align=right bgcolor=$color";
echo ">$".$totalsmallIfee."</td></tr>";
$csv.="\"$".$totalsmallIfee."\"";
if($multiplesite=='x' && $color==$color1)
   $csv.=",\"$site1\"";
else if($multiplesite=='x' && $color==$color2)
   $csv.=",\"$site2\"";
$csv.="\r\n\r\n";
}//end if !vocalonly

//MISC SMALL V & I ENSEMBLES:
$sql="SELECT id FROM muensembles WHERE id='60'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$smallMcount=CountEnsembles($schid,60);
echo "<tr align=left><td>&nbsp;</td><td>You have entered <b><u>$smallMcount</u> Miscellaneous Small Vocal & Instrumental Ensemble";
$csv.="\"\",\"You have entered $smallMcount Miscellaneous Small Vocal & Instrumental Ensemble";
if($smallMcount!=1)
{
   echo "s"; $csv.="s";
}
echo "</b>.</td>";
$csv.=".\",";
echo "<td align=right>$smallMcount x $".$smallfee." = </td>";
$csv.="\"$smallMcount x $".$smallfee." = \",";
$totalsmallMfee=number_format($smallMcount*$smallfee,2,'.','');
if($color==$color1)
{
   $totalsmallMfee1=$totalsmallMfee;
   $totalsmallMfee2=0;
}
else if($color==$color2)
{
   $totalsmallMfee2=$totalsmallMfee;
   $totalsmallMfee1=0;
}
echo "<td align=right bgcolor=$color";
echo ">$".$totalsmallMfee."</td></tr>";
$csv.="\"$".$totalsmallMfee."\"";
if($multiplesite=='x' && $color==$color1)
   $csv.=",\"$site1\"";
else if($multiplesite=='x' && $color==$color2)
   $csv.=",\"$site2\"";
$csv.="\r\n\r\n";

if(!$instrumentalonly)
{
   //VOCAL SOLO FEES:
$sql="SELECT id FROM muensembles WHERE ensemble LIKE 'Vocal Solo%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result); $ensid=$row[id];
echo "<tr align=left";
echo "><td>&nbsp;</td><td colspan=3><b>Vocal Solos:</b>&nbsp;";
$csv.="\"\",\"Vocal Solos:\",";
      if(PayingForEnsemble($distid,$distid1,$ensid))
      {
         $solofee=GetEnsembleFee($distid1,$ensid); $color=$color1;
      }
      else if(PayingForEnsemble($distid,$distid2,$ensid))
      {
         $solofee=GetEnsembleFee($distid2,$ensid); $color=$color2;
      }
      else
      {
         $solofee=GetEnsembleFee($distid,$ensid); $color=$color1;
      }
echo "$".$solofee."/each</td></tr>";
$csv.="\"$".$solofee."/each\"\r\n";
$solocount=CountEnsembles($schid,$row[id]);
echo "<tr align=left";
echo "><td>&nbsp;</td><td>You have entered <b><u>$solocount</u> Vocal Solo";
$csv.="\"\",\"You have entered $solocount Vocal Solo";
if($solocount!=1) 
{
   echo "s"; $csv.="s";
}
echo "</b>.</td>";
$csv.=".\",";
echo "<td align=right>$solocount x $".$solofee." = </td>";
$vocalsolofee=number_format($solocount*$solofee,2,'.','');
echo "<td align=right bgcolor=$color";
if($color==$color1)
{
   $vocalsolofee1=$vocalsolofee;
   $vocalsolofee2=0;
} 
else if($color==$color2)
{
   $vocalsolofee2=$vocalsolofee;
   $vocalsolofee1=0;
}
echo ">$".$vocalsolofee."</td></tr>";
$csv.="\"$solocount x $".$solofee." = \",\"$".$vocalsolofee."\"";
if($multiplesite=='x' && $color==$color1)
   $csv.=",\"$site1\"";
else if($multiplesite=='x' && $color==$color2)
   $csv.=",\"$site2\"";
$csv.="\r\n\r\n";
}
if(!$vocalonly)
{
   //INSTRUMENTAL SOLO FEES:
$sql="SELECT id FROM muensembles WHERE ensemble LIKE 'Instrumental Solo%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result); $ensid=$row[id];
echo "<tr align=left";
echo "><td>&nbsp;</td><td colspan=3><b>Instrumental Solos:</b>&nbsp;";
      if(PayingForEnsemble($distid,$distid1,$ensid))
      {
         $solofee=GetEnsembleFee($distid1,$ensid); $color=$color1;
      }
      else if(PayingForEnsemble($distid,$distid2,$ensid))
      {
         $solofee=GetEnsembleFee($distid2,$ensid); $color=$color2;
      }
      else
      {
         $solofee=GetEnsembleFee($distid,$ensid); $color=$color1;
      }
echo "$".$solofee."/each</td></tr>";
$csv.="\"\",\"Instrumental Solos:\",\"$".$solofee."/each\"\r\n";
$solocount=CountEnsembles($schid,$row[id]);
echo "<tr align=left";
echo "><td>&nbsp;</td><td>You have entered <b><u>$solocount</u> Instrumental Solo";
if($solocount!=1) echo "s";
echo "</b>.</td>";
echo "<td align=right>$solocount x $".$solofee." = </td>";
$instsolofee=number_format($solocount*$solofee,2,'.','');
echo "<td align=right bgcolor=$color";
if($color==$color1)
{
   $instsolofee1=$instsolofee;
   $instsolofee2=0;
} 
else if($color==$color2)
{
   $instsolofee2=$instsolofee;
   $instsolofee1=0;
}
echo ">$".$instsolofee."</td></tr>";
$csv.="\"\",\"You have entered $solocount Instrumental Solo";
if($solocount!=1) $csv.="s";
$csv.=".\",\"$solocount x $".$solofee." = \",\"$".$instsolofee."\"";
if($multiplesite=='x' && $color==$color1)
   $csv.=",\"$site1\"";
else if($multiplesite=='x' && $color==$color2)
   $csv.=",\"$site2\"";
$csv.="\r\n";
}
$totalsolofee=number_format($instsolofee+$vocalsolofee,'2','.','');
   //LARGE ENSEMBLES:
if(!$instrumentalonly)
{
   //Vocal:
echo "<tr align=left><td>&nbsp;</td><td colspan=2><b>Large Vocal Ensembles:</b></td></tr>";
$sql="SELECT t1.id,t1.ensemble FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Large' AND t2.vocinst='Vocal' ORDER BY t1.orderby";
$result=mysql_query($sql);
$largefee=array(); $largefee1=array(); $largefee2=array();
$largecount=array(); $largecount1=array(); $largecount2=array();
$totallargefee=array(); $totallargefee1=array(); $totallargefee2=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $ensid=$row[id];
   echo "<tr align=left";
   echo "><td>&nbsp;</td><td colspan=3><b>&nbsp;&nbsp;&nbsp;$row[ensemble]:</b>&nbsp;";
   if(PayingForEnsemble($distid,$distid1,$ensid))
   {
      $largefee[$ix]=GetEnsembleFee($distid1,$ensid); $color=$color1;
      $largefee1[$ix]=$largefee[$ix];
      $largecount[$ix]=CountEnsembles($schid,$row[id]);
      $largecount1[$ix]=$largecount[$ix];
      $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
      $totallargefee1[$ix]=$totallargefee[$ix]; $totallargefee2[$ix]=0;
   }
   else if(PayingForEnsemble($distid,$distid2,$ensid))
   {
      $largefee[$ix]=GetEnsembleFee($distid2,$ensid); $color=$color2;
      $largefee2[$ix]=$largefee[$ix];
      $largecount[$ix]=CountEnsembles($schid,$row[id]);
      $largecount2[$ix]=$largecount[$ix];
      $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
      $totallargefee2[$ix]=$totallargefee[$ix]; $totallargefee1[$ix]=0;
   }
   else
   {
      $largefee[$ix]=GetEnsembleFee($distid,$ensid); $color=$color1;
      $largecount[$ix]=CountEnsembles($schid,$row[id]);
      $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
      $totallargefee1[$ix]=0; $totallargefee2[$ix]=0;
   }
   echo "$".$largefee[$ix]."/each</td></tr>";
   $csv.="\"\",\"$row[ensemble]:\",\"$".$largefee[$ix]."/each\"\r\n";
   echo "<tr align=left";
   echo "><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;You have entered <b><u>".$largecount[$ix]."</u> $row[ensemble]";
   $csv.="\"\",\"You have entered $largecount[$ix] $row[ensemble]";
   if($largecount[$ix]!=1 && !ereg("Chorus",$row[ensemble])) 
   {
      echo "s"; $csv.="s";
   }
   else if($largecount[$ix]!=1) 
   {
      echo "es"; $csv.="es";
   }
   echo "</b>.</td>";
   $csv.=".\",";
   echo "<td align=right>".$largecount[$ix]." x $".$largefee[$ix]." = </td>";
   echo "<td align=right bgcolor=$color";
   echo ">$".$totallargefee[$ix]."</td></tr>";
   $csv.="\"$largecount[$ix] x $".$largefee[$ix]." = \",\"$".$totallargefee[$ix]."\"";
   if($multiplesite=='x' && $color==$color1)
      $csv.=",\"$site1\"";
   else if($multiplesite=='x' && $color==$color2)
      $csv.=",\"$site2\"";
   $csv.="\r\n";
   $ix++;
}
}
if(!$vocalonly)
{
   //Instrumental:
echo "<tr align=left><td>&nbsp;</td><td colspan=2><b>Large Instrumental Ensembles:</b></td></tr>";
$sql="SELECT t1.id,t1.ensemble FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Large' AND t2.vocinst='Instrumental' ORDER BY t1.orderby";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $ensid=$row[id];
   echo "<tr align=left";
   echo "><td>&nbsp;</td><td colspan=3><b>&nbsp;&nbsp;&nbsp;$row[ensemble]:</b>&nbsp;";
   if(PayingForEnsemble($distid,$distid1,$ensid))
   {
      $largefee[$ix]=GetEnsembleFee($distid1,$ensid); $color=$color1;
      $largefee1[$ix]=$largefee[$ix];
      $largecount[$ix]=CountEnsembles($schid,$row[id]);
      $largecount1[$ix]=$largecount[$ix];
      $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
      $totallargefee1[$ix]=$totallargefee[$ix]; $totallargefee2[$ix]=0;
   }
   else if(PayingForEnsemble($distid,$distid2,$ensid))
   {
      $largefee[$ix]=GetEnsembleFee($distid2,$ensid); $color=$color2;
      $largefee2[$ix]=$largefee[$ix];
      $largecount[$ix]=CountEnsembles($schid,$row[id]);
      $largecount2[$ix]=$largecount[$ix];
      $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
      $totallargefee2[$ix]=$totallargefee[$ix]; $totallargefee1[$ix]=0;
   }
   else
   {
      $largefee[$ix]=GetEnsembleFee($distid,$ensid); $color=$color1;
      $largecount[$ix]=CountEnsembles($schid,$row[id]);
      $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
      $totallargefee1[$ix]=0; $totallargefee2[$ix]=0;
   }
   echo "$".$largefee[$ix]."/each</td></tr>";
   $csv.="\"\",\"$row[ensemble]:\",\"$".$largefee[$ix]."/each\"\r\n";
   echo "<tr align=left";
   echo "><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;You have entered <b><u>".$largecount[$ix]."</u> $row[ensemble]";
   $csv.="\"\",\"You have entered $largecount[$ix] $row[ensemble]";
   if($largecount[$ix]!=1 && !ereg("Chorus",$row[ensemble]))
   {
      echo "s"; $csv.="s";
   }
   else if($largecount[$ix]!=1)
   {
      echo "es"; $csv.="es";
   }
   echo "</b>.</td>";
   $csv.=".\",";
   echo "<td align=right>".$largecount[$ix]." x $".$largefee[$ix]." = </td>";
   $totallargefee[$ix]=number_format($largecount[$ix]*$largefee[$ix],2,'.','');
   echo "<td align=right bgcolor=$color";
   echo ">$".$totallargefee[$ix]."</td></tr>";
   $csv.="\"$largecount[$ix] x $".$largefee[$ix]." = \",\"$".$totallargefee[$ix]."\"";
   if($multiplesite=='x' && $color==$color1)
      $csv.=",\"$site1\"";
   else if($multiplesite=='x' && $color==$color2)
      $csv.=",\"$site2\"";
   $csv.="\r\n";
   $ix++;
}
}
$csv.="\r\n";

//Fees for non-member schools:
echo "<tr align=left";
echo "><td><b>3)</b></td>";
$csv.="\"3.\",";
if($multiplesite=='x')
{
   if($nondistfee1!=0)
      $nondistfee=$nondistfee1;
   else if($nondistfee2!=0)
      $nondistfee=$nondistfee2;
}
if($nondistfee==0)
{
   if($member=='n')
   {
      echo "<td colspan=2>There is no fee for non-member schools for this District.</td>";
      $csv.="\"There is no fee for non-member schools for this District.\",";
   }
   else
   {
      echo "<td colspan=2>Fee for non-member schools: Non-Applicable</td>";
      $csv.="\"Fee for non-member schools: Non-Applicable\",";
   }
   $nondistfee=number_format(0,2,'.','');
   echo "<td align=right>$".$nondistfee."</td></tr>";
   $csv.="\"$".$nondistfee."\"\r\n";
}
else
{
   $nondistfee=number_format($nondistfee,2,'.','');
   //first ask if they are a member
   echo "<td colspan=3><a name=\"radio\">&nbsp;</a>Is your school a member of <b>District <u>$bigdist</u></b>?&nbsp;&nbsp;";
   $csv.="\"Is your school a member of District $bigdist?\",";
   echo "<input type=radio name=member value='y' onclick=\"this.form.action+='#radio';submit();\"";
   if($member=='y') echo " checked";
   echo ">Yes&nbsp;<input type=radio name=member value='n' onclick=\"this.form.action+='#radio';submit();\"";
   if($member=='n') echo " checked";
   echo ">No</td></tr>";
   if($member=='y') $csv.="\"YES\"\r\n";
   else if($member=='n') $csv.="\"NO\"\r\n";

   //if NO, ask for home district
   if($member=='n')
   {
      echo "<tr align=left><td>&nbsp;</td>";
      echo "<td colspan=2>Please select your home district:&nbsp;";
      $csv.="\"\",\"Your Home District:\",";
      echo "<select name=homedist onchange=\"this.form.action+='#radio';submit();\"><option value=''>~</option>";
      $sql="SELECT distnum FROM mubigdistricts WHERE distnum!='$bigdist' ORDER BY id";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option";
         if($homedist==$row[distnum]) echo " selected";
	 echo ">$row[distnum]</option>";
      }
      echo "</select></td></tr>";
      $csv.="\"$homedist\"\r\n";
   }
   
   //now show fee if any
   echo "<tr align=left><td>&nbsp;</td>";
   $csv.="\"\",";
   if($multiplesite=='x') $nondistfee=$nondistfee1;	//do site #1 first
   if($member=='y') 
   {
      $nondistfee=number_format(0,2,'.','');
      echo "<td colspan=2>Member schools are not charged any additional fees.</td>";
      $csv.="\"Member schools are not charged any additional fees.\",";
   }
   else
   {
      echo "<td colspan=2>The fee for non-member schools is <b><u>$".$nondistfee."</u></b></td>";   
      $csv.="\"The fee for non-member schools is $".$nondistfee."\",";
   }
   echo "<td align=right bgcolor=$color1>$".$nondistfee."</td></tr>";
   $csv.="\"$".$nondistfee."\"";
   if($multiplesite=='x' && $color==$color1)
      $csv.=",\"$site1\"";
   else if($multiplesite=='x' && $color==$color2)
     $csv.=",\"$site2\"";
   $csv.="\r\n";
   if($multiplesite=='x')
   {
      $nondistfee=$nondistfee2;
   if($member=='y')
   {
      $nondistfee=number_format(0,2,'.','');
      echo "<td colspan=2>Member schools are not charged any additional fees.</td>";
      $csv.="\"Member schools are not charged any additional fees.\",";
   }
   else
   {
      echo "<td colspan=2>The fee for non-member schools is <b><u>$".$nondistfee."</u></b></td>";
      $csv.="\"The fee for non-member schools is $".$nondistfee."\",";
   }
   echo "<td align=right bgcolor=$color2>$".$nondistfee."</td></tr>";
   $csv.="\"$".$nondistfee."\"";
   if($multiplesite=='x' && $color==$color1)
      $csv.=",\"$site1\"";
   else if($multiplesite=='x' && $color==$color2)
      $csv.=",\"$site2\"";
   $csv.="\r\n";
   }
}
$csv.="\r\n";

//TOTAL FEES:
if($multiplesite!='x')
{
   $totalsmallfee=$totalsmallVfee+$totalsmallIfee+$totalsmallMfee;
   $totalfee=$surcharge+$totalsmallfee+$totalsolofee;
   foreach($totallargefee as $key => $value)
   {
      $totalfee+=$value;
   }
   $totalfee+=$nondistfee;
   $totalfee=number_format($totalfee,2,'.','');
   echo "<tr align=left><td colspan=4><hr></td></tr>";
   echo "<tr align=right><td colspan=3><b>Total District $bigdist Music Entry Fees for Your School:</b></td>";
   echo "<td bgcolor=$color1><font style=\"font-size:10pt;\">$".$totalfee."</td></tr>";
   $csv.="\"TOTAL DISTRICT $bigdist MUSIC ENTRY FEES for your School:\",\"\",\"\",\"$".$totalfee."\"\r\n";
}
else
{
   $totalsmallfee=$totalsmallVfee1+$totalsmallIfee1+$totalsmallMfee1;
   $totalfee=$surcharge1+$totalsmallfee1+$totalsolofee1;
   for($i=0;$i<count($totallargefee1);$i++)
   {
      $totalfee+=$totallargefee1[$i];
   }
   $totalfee+=$nondistfee1;
   $totalfee=number_format($totalfee,2,'.','');
   echo "<tr align=left><td colspan=4><hr></td></tr>";
   echo "<tr align=right><td colspan=3><b>Total District $bigdist -- $classes1 Music Entry Fees for Your School:</b></td>";
   echo "<td bgcolor=$color1><font style=\"font-size:10pt;\">$".$totalfee."</td></tr>";
   $csv.="\"TOTAL DISTRICT $bigdist-$classes1 MUSIC ENTRY FEES for your School:\",\"\",\"\",\"$".$totalfee."\"\r\n";

   $totalsmallfee=$totalsmallVfee+$totalsmallIfee+$totalsmallMfee;
   $totalfee=$surcharge2+$totalsmallfee+$totalsolofee;
   for($i=0;$i<count($totallargefee2);$i++)
   {
      $totalfee+=$totallargefee2[$i];
   }
   $totalfee+=$nondistfee;
   $totalfee=number_format($totalfee,2,'.','');
   echo "<tr align=left><td colspan=4><hr></td></tr>";
   echo "<tr align=right><td colspan=3><b>Total District $bigdist -- $classes2 Music Entry Fees for Your School:</b></td>";
   echo "<td bgcolor=$color2><font style=\"font-size:10pt;\">$".$totalfee."</td></tr>";
   $csv.="\"TOTAL DISTRICT $bigdist-$classes2 MUSIC ENTRY FEES for your School:\",\"\",\"\",\"$".$totalfee."\"\r\n";
}
echo "<tr align=center><td colspan=4>$sendfees</td></tr>";

echo "</table>";
echo "</form>";

//write to Excel (.csv) file
$file=strtolower($school);
$file=ereg_replace(" ","",$file);
$file=ereg_replace("[.]","",$file);
$file=ereg_replace("\'","",$file);
$file=ereg_replace("-","",$file);
$file.="payment";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$file.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$file.csv");
//echo "<a target=new2 href=\"/home/nsaahome/attachments/$file.csv\">$file.csv</a>";

echo $end_html;
?>
