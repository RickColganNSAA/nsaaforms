<?php
//off_query.php: Advanced Search Tool for officials list

require 'variables.php';
require 'functions.php';

$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($search && $report && $reportsport)
{
   $regyr=GetSchoolYear();
   $sql="SELECT DISTINCT * FROM officials AS t1,".$reportsport."off_hist AS t2 WHERE t1.id=t2.offid AND t2.regyr='$regyr' AND ";
   if($report=="notest")
   {
      $sql.="t2.obtest='' AND t2.class!='AFF'";
   } //END IF report==notest
   else if($report=="norm")
   {
      $sql.="t2.rm!='x' AND t2.class!='AFF'";
   }
   else //NO CLASSIFICATION (report==noclass)
   {
      $sql.="t2.class=''";
   }
   header("Location:officials.php?session=$session&query=$sql");
}
else if($search && !$report)
{
   $city=ereg_replace("\'","\'",$city);
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $socsec=ereg_replace("-","",$socsec);

   if($sport=="All Sports")
   {
      $sql="SELECT DISTINCT * FROM officials WHERE ";
      if($active=='yes') $sql.="inactive!='x' AND ";
      else if($active=='no') $sql.="inactive='x' AND ";
      if(trim($socsec)!="") $sql.="socsec LIKE '$socsec%' AND ";
      if(trim($city)!="") $sql.="city LIKE '$city%' AND ";
      if(trim($state)!="") $sql.="state='$state' AND ";
      if(trim($lastname)!="") $sql.="last LIKE '$lastname%' AND ";
      if(trim($first)!="") $sql.="first LIKE '$first%' AND ";
      if(trim($zip)!="") $sql.="zip LIKE '$zip%' AND ";
      if(trim($area)!="") $sql.="(homeph LIKE '$area%' OR cellph LIKE '$area%' OR workph LIKE '$area%') AND ";
      if(trim($email)!="") $sql.="email LIKE '$email%' AND ";
      //if(trim($payment)!="") $sql.="payment LIKE '$payment%' AND ";
      if($senttofed=='y') $sql.="senttofed > 0 AND ";
      else if($senttofed=='n') $sql.="senttofed = 0 AND ";
      if($nhsoa=='y') $sql.="nhsoa = 'x' AND ";
      else if($nhsoa=='n') $sql.="nhsoa != 'x' AND ";
      if($gender=='M' || $gender=='F') $sql.="gender = '$gender' AND ";
      if($minority=='x') $sql.="minority = 'x' AND ";
      if(ereg("AND",$sql))
      {
	 $sql=substr($sql,0,strlen($sql)-5);
      }
      else
      {
	 $sql=substr($sql,0,strlen($sql)-7);
      }
      if($mailnum3 && $mailnum3!='')
     	 $mailoption=3;
      if($districtoff=='x' || $stateoff=='x')
      {
         $contractdata="$districtoff;$stateoff;$andor;$insince;$offyear";
      }
   }
   else
   {
      $table=$sport."off"; $histtable=$table."_hist";
      $sql="SELECT DISTINCT t1.* FROM officials AS t1, $table AS t2";
      if($rm || $clinic=='x' || $nhsoa) 
      {
	 if(!$clinicrmregyr) $clinicrmregyr=GetSchoolYear(date("Y"),date("n")); 
         $sql.=", $histtable AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t3.regyr = '$clinicrmregyr' AND ";
      }
      else
         $sql.=" WHERE t1.id=t2.offid AND ";
      if($active=='yes') $sql.="t1.inactive!='x' AND ";
      else if($active=='no') $sql.="t1.inactive='x' AND ";
      if(trim($socsec)!="") $sql.="t1.socsec LIKE '$socsec%' AND ";
      if(trim($city)!="") $sql.="t1.city LIKE '$city%' AND ";
      if(trim($state)!="") $sql.="t1.state='$state' AND ";
      if(trim($lastname)!="") $sql.="t1.last LIKE '$lastname%' AND ";
      if(trim($first)!="") $sql.="t1.first LIKE '$first%' AND ";
      if(trim($zip)!="") $sql.="t1.zip LIKE '$zip%' AND ";
      if(trim($area)!="") $sql.="(t1.homeph LIKE '$area%' OR t1.cellph LIKE '$area%' OR t1.workph LIKE '$area%') AND ";
      if(trim($email)!="") $sql.="t1.email LIKE '$email%' AND ";
      if(trim($payment)!="") $sql.="t2.payment LIKE '$payment%' AND ";
      if($senttofed=='y') $sql.="t1.senttofed > 0 AND ";
      else if($senttofed=='n') $sql.="t1.senttofed = 0 AND ";
      if($nhsoa=='y') $sql.="t3.nhsoa = 'x' AND ";
      else if($nhsoa=='n') $sql.="t1.nhsoa != 'x' AND ";
      if($gender=='M' || $gender=='F') $sql.="t1.gender = '$gender' AND ";
      if($minority=='x') $sql.="t1.minority = 'x' AND ";
      if($class!="Choose") $sql.="t2.class LIKE '$class%' AND ";
      if(trim($suptestdate)!="") $sql.="t2.suptestdate $supineq $suptestdate AND ";
      if(trim($mailing)!="") $sql.="t2.mailing $mailineq $mailing AND ";
      if(trim($years)!="") $sql.="t2.years $yrsineq $years AND ";
      if($sport=='ba')
      {
	 if(trim($currentst)!="") $sql.="t2.currentst LIKE '$currentst%' AND ";
         if(trim($retaketest)!="") $sql.="t2.retaketest $rtineq $retaketest AND ";
         if(trim($chosen)!="") $sql.="t2.chosen LIKE '$chosen%' AND ";
      }   
      else if($sport=='bb' || $sport=='wr')
      {   
         if(trim($currentst)!="") $sql.="t2.currentst LIKE '$currentst%' AND ";
         if(trim($retaketest)!="") $sql.="t2.retaketest $rtineq $retaketest AND "; 
         if($clinic=='x') $sql.="t3.clinic='x' AND ";
      } 
      else if($sport=='fb' || $sport=='so' || $sport=='vb')
      {  
         if(trim($currentst)!="") $sql.="t2.currentst LIKE '$currentst%' AND ";
      }
      else if($sport=='sb')
      {
	 if(trim($patches)!="") $sql.="t2.patches LIKE '$patches%' AND ";
      }
      $sql2="SHOW TABLES LIKE '".$sport."rulesmeetings'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         if($rm=='yes') $sql.="t3.rm = 'x' AND ";
         else if($rm=='no') $sql.="t3.rm != 'x' AND ";
      }

      $sql=substr($sql,0,strlen($sql)-5);

      if($mailoption=='1')
      {
         if($whichmailnum=="primary")
	 {
	    $sql.=" AND $sport='x' AND t2.mailing='$mailnum'";
	    $sql2="UPDATE mailing SET mailnum=mailnum+1 WHERE sport='$sport'";
	 }
	 else if($whichmailnum=="secondary")
	 {
	    $sql.=" AND $sport='x' AND t2.mailing='$mailnum2'";
	    $sql2="UPDATE mailing SET mailnum2=mailnum2-1 WHERE sport='$sport'";
         }
	 $result2=mysql_query($sql2);
      }
      else if($mailoption=='2')
      {
	 $sql.=" AND $sport='x' AND t2.mailing $mailineq '$mailnum3'";
      }
      if(!$payment && $class=="Choose" && !$suptestdate && !$mailoption && !$years && !$currentst && !$retaketest && !$chosen && !$patches && !$rm && !$clinic)
      {
	 //just select from officials table
	 $sql=ereg_replace(", $table AS t2 WHERE t1.id=t2.offid"," WHERE t1.$sport='x'",$sql);
      }
   }
  
   if(strlen($socsec)==9)	//entire soc sec # entered
   {
      $findone=1;	//if only one result from this search, go straight to that official's edit_off page
   }
   else
   {
      $findone=0;
   }
   $sql2=ereg_replace(",",";",$sql);
   //$sql2=urlencode($sql2);
   header("Location:officials.php?session=$session&query=$sql2&sport=$sport&mailoption=$mailoption&whichmailnum=$whichmailnum&findone=$findone&mailnum3=$mailnum3&mailineq=$mailineq&insincedist=$insincedist&yeardist=$yeardist&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate");
}

echo $init_html;
$header=GetHeader($session);
echo $header; 
?>

<form method="post" action="off_query.php">
<br>
<h1>Officials Advanced Search & Reports:</h1>
<input type=hidden name=session value="<?php echo $session; ?>">
<table cellspacing=0 cellpadding=4 width='650px' class='nine'>
<?php
echo "<tr align=left><th colspan=2><hr>ADVANCED SEARCH:</th></tr>";
echo "<tr align=left><td colspan=2><p>Please indicate your search criteria below. You can put in just the first part of the criteria you are looking for, such as \"685\" in the Zip field for all zip codes beginning with 685.</p></td></tr>";
echo "<tr align=left><th align=left>Sport(s):</th><td align=left>";
echo "<select name=sport onchange=\"submit()\">";
echo "<option ";
if(!$sport || $sport=="All Sports")
   echo "selected";
echo ">All Sports";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value=\"$activity[$i]\"";
   if($sport==$activity[$i]) echo " selected";
   echo ">$act_long[$i]";
}
echo "</select></td></tr>";
//echo "<tr bgcolor=#E0E0E0 align=center><td align=center colspan=2><b><i>Please click ";
//echo "<input type=submit name=go value=\"Go\"> after selecting your sport(s).</i></b></td></tr>";
echo "<tr align=left><th align=left>Soc Sec #:</th>";
echo "<td align=left><input type=text name=socsec size=10></td></tr>";
echo "<tr align=left><th align=left>Last Name:</th>";
echo "<td align=left><input type=text name=lastname size=30></td></tr>";
echo "<tr align=left><th align=left>First Name:</th>";
echo "<td align=left><input type=text name=first size=30></td></tr>";
echo "<tr align=left><th align=left>City:</th>";
echo "<td align=left><input type=text name=city size=30></td></tr>";
echo "<tr align=left><th align=left>State:</th>";
echo "<td align=left><input type=text name=state size=3></td></tr>";
echo "<tr align=left><th align=left>Zip:</th>";
echo "<td align=left><input type=text name=zip size=10></td></tr>";
echo "<tr align=left><th align=left>Area Code:</th>";
echo "<td align=left><input type=text name=area size=5></td></tr>";
echo "<tr align=left><th align=left>E-mail:</th>";
echo "<td align=left><input type=text name=email size=30></td></tr>";
//echo "<tr align=left><th align=left>Payment:</th>";
//echo "<td align=left><input type=text name=payment size=20></td></tr>";
echo "<tr align=left><th align=left>Gender:</th>";
echo "<td align=left><input type=radio name=\"gender\" value=\"M\"> Male&nbsp;&nbsp;<input type=radio name=\"gender\" value=\"F\"> Female&nbsp;&nbsp;<input type=radio name=\"gender\" value=\"Either\" checked> Either</td></tr>";
echo "<tr align=left><th align=left>Minority:</th><td><input type=checkbox name=\"minority\" value=\"x\"> Minority Officials ONLY</td></tr>";
echo "<tr align=left><th align=left>Registered with NHSOA:</th>";
echo "<td align=left><input type=radio name=nhsoa value='y'>Yes&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=nhsoa value='n'>No</td></tr>";
echo "<tr align=left><th align=left>Sent to NFHS:</th>";
echo "<td align=left><input type=radio name=senttofed value='y'>Yes&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=senttofed value='n'>No</td></tr>";
//Choose officials who've been contracted online to officiate districts/subdistricts:
echo "<tr align=left><th align=left colspan=2>";
echo "Contracted Online to Officiate (Sub)Districts in ";
$sportname=GetSportName($sport);
if(!$sport || $sport=="All Sports") echo "<u>ANY SPORT</u>";
else  echo "<u>$sportname</u>";
echo " <select name=\"insincedist\"><option>IN</option><option>SINCE</option></select>";
echo " <select name=\"yeardist\"><option value=''>~</option>";
$sql="SHOW DATABASES LIKE '$db_name2%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("officials",$row[0]);
   if($temp[1]=="")
      echo "<option value=\"$row[0]\">This Year</option>";
   else
      echo "<option value=\"$row[0]\">".substr($temp[1],0,4)."-".substr($temp[1],4,4)."</option>";
}
echo "</select></th></tr>";
//Choose officials who've officiated state in/since certain year:
echo "<tr valign=top align=left><td colspan=2><b>";
echo "Officiated STATE in ";
if(!$sport || $sport=="All Sports") echo "<u>ANY SPORT</u>";
else  echo "<u>$sportname</u>&nbsp;";
echo " <select name=\"insincestate\"><option>IN</option><option>SINCE</option></select></b>";
echo " <input type=text size=5 name=\"yearstate\"><br><div class='alert' style='margin-left:50px;'>For one year, put \"08\" for 2008. For multiple years, put \"09,08,07\" for 2009, 2008, 2007, making sure they are in order of most recent year first, since that is how they are stored in the database.</div></td></tr>";
//Choose officials who officiated state a certain number of times
echo "<tr align=left><th align=left colspan=2>Number of Years Officiated STATE in ";
if(!$sport || $sport=="All Sports") echo "<u>ANY SPORT</u>";
else  echo "<u>$sportname</u>";
echo ": <select name=\"stateyearsineq\"><option><=</option><option>>=</option><option>=</option></select>&nbsp;";
echo "<input type=text size=2 name=\"numstateyears\"> YEARS</td></tr>";

if(!$sport || $sport=="All Sports")
{
   //If $sport not chosen--allow to chose officials with a certain mailing num in ANY sport
   //For example: Any officials with mailing num >=100 in any sport...
   echo "<tr align=left><th align=left>Mailing #:</th>";
   echo "<td align=left><select name=mailineq>"; 
   echo "<option>>=</option><option><=</option><option>=</option></select>";
   echo "<input type=text size=3 name=mailnum3>";
   echo "&nbsp;in ANY sport</td></tr>";
}
else
{
   echo "<tr align=left><th align=left>Payment:</th>";
   echo "<td align=left><input type=text name=payment size=20></td></tr>";
   echo "<tr align=left><th align=left>Class:</th>";
   echo "<td align=left>";
   $table=$sport."off";
   $sql="SELECT DISTINCT(class) FROM $table WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   echo "<select name=class><option>Choose";
   while($row=mysql_fetch_array($result))
   {
      echo "<option>$row[0]";
   }
   echo "</select></td></tr>";
   //echo "<input type=text name=class size=2></td></tr>";
   echo "<tr align=left><th align=left>Sup Test Date:</th>";
   echo "<td align=left>";
   echo "<select name=supineq><option>=<option><=<option>>=</select>&nbsp;";
   echo "<input type=text name=suptestdate size=5></td></tr>";
   echo "<tr align=left><th align=left>Years:</th>";
   echo "<td align=left>";
   echo "<select name=yrsineq><option>=<option><=<option>>=</select>&nbsp;";
   echo "<input type=text name=years size=3></td></tr>";

   $askforyear=0;
   if($sport=='ba')
   {
      echo "<tr align=left><th align=left>Current ST:</th>";
      echo "<td align=left><input type=text size=4 name=currentst></td></tr>";
   }
   else if($sport=='bb')
   {
      echo "<tr align=left><th align=left>Current ST:</th>";
      echo "<td align=left><input type=text size=4 name=currentst></td></tr>";
      echo "<tr align=left><td><b>Attended Clinic:</th>";
      echo "<td><input type=checkbox name=\"clinic\" value='x'></td></tr>";
	$askforyear=1;
   }
   else if($sport=='wr')
   {
      echo "<tr align=left><td><b>Attended Clinic:</th>";
      echo "<td><input type=checkbox name=\"clinic\" value='x'></td></tr>";
	$askforyear=1;
   }
   else if($sport=='fb' || $sport=='so' || $sport=='vb')
   {
      echo "<tr align=left><th align=left>Current ST:</th>";
      echo "<td align=left><input type=text size=4 name=currentst></td></tr>";
   }
   else if($sport=='sb')
   {
      echo "<tr align=left><th align=left>Patches:</th>";
      echo "<td align=left><input type=text size=4 name=patches></td></tr>";
   }
   $sql2="SHOW TABLES LIKE '".$sport."rulesmeetings'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<tr align=left><td><b>Attended a Rules Meeting:</b></td>";
      echo "<td><input type=radio name=\"rm\" value=\"yes\">YES&nbsp;&nbsp;";
      echo "<input type=radio name=\"rm\" value=\"no\">NO</td></tr>";
        $askforyear=1;
   }
   $askforyear=1;	//DEFAULTED TO 1 on 7/11/14 when adjusted search to specify year for NHSOA
   if($askforyear==1)
   {
      echo "<tr align=left><td colspan=2>If you select <b>NHSOA</b>, <b>CLINIC</b> or <b>RULES MEETING</b> options, you may indicate a year: <select name=\"clinicrmregyr\">";
	$sql2="SELECT DISTINCT regyr FROM ".$sport."off_hist ORDER BY regyr";
	$result2=mysql_query($sql2);
        if(!$clinicrmregyr) $clinicrmregyr=GetSchoolYear(date("Y"),date("n"));
	while($row2=mysql_fetch_array($result2))
	{
	   echo "<option value=\"$row2[regyr]\"";
	   if($clinicrmregyr==$row2[regyr]) echo " selected";
	   echo ">$row2[regyr]</option>";
	}
	echo "</select></td></tr>";
   }

   //mailing number options
   //get full sport name
   for($i=0;$i<count($activity);$i++)
   {
      if($activity[$i]==$sport)
	 $sportname=$act_long[$i];
   }
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   echo "<tr align=left><th align=left colspan=2>Mailing #:</th></tr>";
   echo "<tr align=left><th align=left colspan=2>";
   echo "<input type=radio name=mailoption value='1'>&nbsp;";
   echo "Option 1: Export Current Mailing # for $sportname</th></tr>";
   echo "<tr align=left><td colspan=2>(If you choose this option, the mailing # for this sport will be incremented or decremented<br>in the database, depending on if you check the primary or secondary mailing number.)</td></tr>";
   echo "<tr align=center><td colspan=2><table>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name=\"whichmailnum\" value='primary' checked>&nbsp;Current <b><u>PRIMARY</b></u> Mailing #:&nbsp;";
   //get current mailing #'s for this sport
   $sql="SELECT mailnum,mailnum2 FROM mailing WHERE sport='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $mailnum=$row[0]; $mailnum2=$row[1];
   //get number of officials for this sport with current mailing number
   $sql="SELECT DISTINCT t1.offid FROM $table AS t1, officials AS t2 WHERE t2.id=t1.offid AND t1.mailing='$mailnum'";
   $result=mysql_query($sql);
   $mailnumct=mysql_num_rows($result);
   echo "<input type=text size=3 name=mailnum value=\"$mailnum\" readOnly=TRUE>&nbsp;";
   echo "(There are currently <b>$mailnumct</b> $sportname officials with this mailing number.)";
   echo "</td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name=\"whichmailnum\" value='secondary'>&nbsp;Current <b><u>SECONDARY</b></u> Mailing #:&nbsp;";
   //get number of officials for this sport with secondary mail num
   $sql="SELECT DISTINCT t1.offid FROM $table AS t1, officials AS t2 WHERE t2.id=t1.offid AND t1.mailing='$mailnum2'";
   $result=mysql_query($sql);
   $mailnumct=mysql_num_rows($result);
   echo "<input type=text size=3 name=mailnum2 value=\"$mailnum2\" readOnly=TRUE>&nbsp;";
   echo "(There are currently <b>$mailnumct</b> $sportname officials with this mailing number.)";
   echo "</td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=left><th align=left colspan=2>";
   echo "<input type=radio name=mailoption value='2'>&nbsp;";
   echo "Option 2: Export Specific Mailing #(s)</th></tr>";
   echo "<tr align=left><td colspan=2>(This option will not increase the mailing # in the database)</td></tr>";
   echo "<tr align=center><td colspan=2><table>";
   echo "<tr align=left><td>Export Mailing #:&nbsp;&nbsp;";
   echo "<select name=mailineq>";
   echo "<option>=</option><option><=</option><option>>=</option></select>&nbsp;";
   echo "<input type=text name=mailnum3 size=3>";
   echo "</td></tr></table></td></tr>";
}
?>
<tr align=center><td colspan=2><input type=radio name="active" value="yes" checked> Active Officials ONLY&nbsp;&nbsp;&nbsp;<input type=radio name="active" value="no"> Inactive Officials ONLY&nbsp;&nbsp;&nbsp;<input type=radio name="active" value="both">Active AND Inactive Officials</td></tr>
<tr align=left><th colspan=2><hr>QUICK REPORTS:</th></tr>
<tr align=left><td colspan=2><p><i>Selecting a report from the list below overrides any options you chose above.</i></p>
<?php
echo "<p><b>Sport(s):</b> ";
echo "<select name=\"reportsport\">";
echo "<option value=\"\"";
if(!$reportsport || $reportsport=="")
   echo "selected";
echo ">Select a Sport";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value=\"$activity[$i]\"";
   if($sport==$activity[$i]) echo " selected";
   echo ">$act_long[$i]";
}
echo "</select>";
if($search && $report && (!$reportsport || $reportsport==""))
   echo " <label style=\"color:red;\"><b>Please select a SPORT</b></label>";
echo "</p>";
?>
<p><input type="radio" name="report" value="notest"> Officials who've REGISTERED but have NOT TAKEN THE TEST (Affiliate officials excluded)</p>
<p><input type="radio" name="report" value="norm"> Officials who've REGISTERED but have NOT VIEWED A RULES MEETING (Affiliate officials excluded)</p>
<p><input type="radio" name="report" value="noclass"> Officials who've REGISTERED but have NO CLASSIFICATION (Affiliate officials included)</p>
</td></tr>
<tr align=center>
<td colspan=2><br>
<input type="submit" name="search" value="Search">
</td>
</tr>
</table>
</form>
</center>

</td>
</tr>
</table>
</body>
</html>
