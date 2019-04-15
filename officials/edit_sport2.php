<?php

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}   

if($nameid) $id=$nameid;
$offid=$id;

//connect to database:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//get level of user
$level=GetLevel($session);

for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
      $sportname=$act_long[$i];
}
$offtable=$sport."off";
$histtable=$offtable."_hist";

if($save)
{
   //update __off table
   $sql="SELECT id FROM $offtable WHERE offid='$offid'";
   $result=mysql_query($sql);

   //check mailing number
   if(trim($mailing)=="")
   {
      $mailing="-1";
   }

   if(mysql_num_rows($result)>0)
   {
      $sql2="UPDATE $offtable SET class='$class',suptestdate='$suptestdate',mailing='$mailing',years='$years'";
      if($sport=='bb') $sql2.=",clinic='$bbclinic',gstateyears='$gstateyears',gnumstateyears='$gnumstateyears',bstateyears='$bstateyears',bnumstateyears='$bnumstateyears'";
      else $sql2.=",stateyears='$stateyears',numstateyears='$numstateyears'";
      $sql2.=" WHERE offid='$offid'";
      $result2=mysql_query($sql2);
   }
   else
   {
      $sql2="INSERT INTO $offtable (offid,class,suptestdate,mailing,years";
      if($sport=='bb') $sql2.=",clinic,gstateyears,gnumstateyears,bstateyears,bnumstateyears";
      else $sql2.=",stateyears,numstateyears";
      $sql2.=") VALUES ('$offid','$class','$suptestdate','$mailing','$years'";
      if($sport=='bb') $sql2.=",'$bbclinic','$gstateyears','$gnumstateyears','$bstateyears','$bnumstateyears'";
      else $sql2.=",'$stateyears','$numstateyears'";
      $sql2.=")";
      $result2=mysql_query($sql2);
   }

   //update __off_hist table
   $sql="DELETE FROM $histtable WHERE offid='$offid'";
   $result=mysql_query($sql);
      //check if there is an entry for the current year AND if they paid; 
      //if so and no mailing number yet, give them
      //current mailing number
      $curyr=date("Y",time());
      $curmo=date("m",time());
      if($curmo<6)
      {
	 $yr1=$curyr-1;
	 $thisregyr=$yr1."-".$curyr;
      }
      else
      {
	 $yr2=$curyr+1;
	 $thisregyr=$curyr."-".$yr2;
      }
      $thisyear=0;	//assume no entry for this year
   for($i=0;$i<count($subid);$i++)
   {
      //CHECK IF 90's (199x) or 2000's (20xx)
      $curyr1=substr($regyr[$i],0,2);
      if(substr($curyr1,0,1)=='0') $curyr1=substr($curyr1,1,1);
      $curyr2=substr($regyr[$i],2,2);
      if(substr($curyr2,0,1)=='0') $curyr2=substr($curyr2,1,1);
      if($curyr1<100 && $curyr1>=90)
	$newregyr="19";
      else
	$newregyr="20";
      $newregyr.=substr($regyr[$i],0,2);
      if($curyr2<100 && $curyr2>=90)
	$newregyr.="-19";
      else $newregyr.="-20";
      $newregyr.=substr($regyr[$i],2,2);

      $tempyr=split("-",$newregyr);
      if(substr($appdate[$i],0,2)<6)
	 $newappdate=$tempyr[1]."-";
      else
	 $newappdate=$tempyr[0]."-";
      $newappdate.=substr($appdate[$i],0,2)."-".substr($appdate[$i],2,2);

      if($regyr[$i] && trim($regyr[$i])!="")
      {
	 $rm[$i]=ereg_replace("\'","\'",$rm[$i]);
         $sql="INSERT INTO $histtable (offid,regyr,appdate,contest,rm,";
         $sql.="obtest,suptest,class) VALUES ('$offid','$newregyr','$newappdate','$contest[$i]','$rm[$i]',";
	 $sql.="'$obtest[$i]','$suptest[$i]','$class2[$i]')";
         $result=mysql_query($sql);
      }

      if($thisregyr==$newregyr)
	 $thisyear=1;
   }
   if($thisyear==1)
   {
      //if no mailing num yet & off is checked for this sport, update this off with current mail num
      //check if payment field is full
      $sql="SELECT payment FROM $offtable WHERE offid='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $payment=$row[0];
      if((trim($mailing)=="" || $mailing=="-1")&& trim($payment)!="")
      {
	 $sql="SELECT mailnum FROM mailing WHERE sport='$sport'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $sql2="UPDATE $offtable SET mailing='$row[0]' WHERE offid='$offid'";
	 $result2=mysql_query($sql2);
      }
      //If payment field has something, make sure this sport is checked in officials table
      if(trim($payment)!='')
      {
	 $sql="UPDATE officials SET $sport='x' WHERE id='$offid'";
	 $result=mysql_query($sql);
      }
   }
/*
   $query2=ereg_replace("[\]","",$query);
   $query2=ereg_replace("\'","\'",$query2);
?>
<script language="javascript">
window.opener.location.replace("edit_off.php?session=<?php echo $session; ?>&sport=<?php echo $querysport; ?>&id=<?php echo $id; ?>&query=<?php echo $query2; ?>&last=<?php echo $last; ?>");
</script>
<?php
*/
}
if($save=="Save & Close" && $individual!=1)
{
?>
<script language="javascript">
<!--window.opener.top.location.reload();-->
window.close();
</script>
<?php
   exit();
}
else if($save=="Save & Close") //only reload if opened from officials list, not individual off form
{
?>
<script language="javascript">
window.close();
</script>
<?php
   exit();
}

//get official's name
$sql="SELECT first,last FROM officials WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$offname="$row[0] $row[1]";

echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','sportform');">
<?php
echo "<table width=100%><tr align=center><td>";
?>
<script language="javascript">
<?php echo $autotab; ?>
</script
<?php
echo "<table width=100%><tr align=center><td>";

echo "<form method=post action=\"edit_sport2.php\" name=\"sportform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=querysport value=\"$querysport\">";
echo "<input type=hidden name=query value=\"$query\">";
echo "<input type=hidden name=last value=$last>";
echo "<input type=hidden name=id value=\"$offid\">";
echo "<a href=\"javascript:window.close();\" class=small>Close this Window (does not Save)</a><br><br>";
echo "<table>";
echo "<tr align=center><td>";
echo "<table><tr align=left valign=top><td><select name=\"sport\" onchange=\"submit();\"><option value=''>Select Sport</option>";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value='$activity[$i]'";
   if($activity[$i]==$sport) echo " selected";
   echo ">$act_long[$i]</option>";
}
echo "</select></td><td>";
echO "<input type=hidden name=\"nameid\" id=\"nameid\">";
echo "<input type=text class=tiny size=35 name=\"name\" id=\"name\" value=\"$offname\" onkeyup=\"UserLookup.lookup('name',this.value,'$sport','official');\"><div class=\"list\" id=\"nameList\" style=\"position:relative\"></div></td>";
echo "</tr></table>";
echo "</td></tr>";

echo "<tr align=center>";
echo "<th colspan=2>Official #$offid: $offname<br>$sportname Subform</th></tr>";
echo "<tr><td colspan=2><hr></td></tr>";

//get history from __off_hist
echo "<tr align=center><td align=center colspan=2>";
echo "<table cellspacing=1 cellpadding=1><tr align=center><th class=smaller align=center>Year</th><th class=smaller align=center>Apps</th><th class=smaller align=center>Contests</th><th class=smaller align=center>Meetings</th>";
echo "<th class=smaller align=center>Tests</th><th class=smaller align=center>ST</th><th class=smaller align=center>Class</th><th class=smaller align=center>Comment</th></tr>";
$sql="SELECT * FROM $histtable WHERE offid='$offid' ORDER BY appdate";
$result=mysql_query($sql);
$ix=0;
$subid=array();
while($row=mysql_fetch_array($result))
{
   //$classall=$row['class'];
   echo "<tr align=center>";
   echo "<input type=hidden name=\"subid[$ix]\" value=\"$row[0]\">";
   /*
   $regyr=split("-",$row[regyr]);
   echo "<td align=center><input type=text size=5 name=\"regyr1[$ix]\" value=\"$regyr[0]\" maxlength=4>-";
   echo "<input type=text size=5 name=\"regyr2[$ix]\" value=\"$regyr[1]\" maxlength=4></td>";
   $appdate=split("-",$row[appdate]);
   echo "<td align=center><input type=text size=3 name=\"appmo[$ix]\" value=\"$appdate[1]\" maxlength=2>/";
   echo "<input type=text size=3 name=\"appday[$ix]\" value=\"$appdate[2]\" maxlength=2></td>";
   */
   $regyr=substr($row[regyr],2,2).substr($row[regyr],7,2);
   echo "<td align=center><input type=text onKeyUp='return autoTab(this,4,event);' onfocus='select();' size=5 name=\"regyr[$ix]\" value=\"$regyr\" maxlength=4></td>";
   $appdatesave=$row[appdate];
   $appdate=ereg_replace("-","",$row[appdate]);
   $appdate=substr($appdate,4,4);
   echo "<td align=center><input type=text size=5 name=\"appdate[$ix]\" value=\"$appdate\" onKeyUp='return autoTab(this,4,event);' onfocus='select();' maxlength=4></td>";
   echo "<td align=center><input type=text size=5 name=\"contest[$ix]\" value=\"$row[contest]\"></td>";
   echo "<td align=center><input type=text size=4 class=tiny value=\"$row[rm]\" name=\"rm[$ix]\"></td>";
   echo "<td align=center><input type=text size=5 name=\"obtest[$ix]\" value=\"$row[obtest]\"></td>";
   echo "<td align=center><input type=text size=5 name=\"suptest[$ix]\" value=\"$row[suptest]\"></td>";
   echo "<td align=center><input type=text size=2 name=\"class2[$ix]\" value=\"$row[class]\"></td>";
   echo "<td align=left>&nbsp;";
   //get current year
   $curyr=date("Y",time()); $curmo=date("m",time());
   if($curmo<6)
   {
      $otheryr=$curyr-1;
      $curyr=$otheryr."-".$curyr;
   }
   else
   {
      $otheryr=$curyr+1;
      $curyr=$curyr."-".$otheryr;
   }
   echo "</td>";
   echo "</tr>";
   $ix++;
}
$curct=$ix;
$max=$curct+2;
while($ix<$max)
{
   echo "<tr align=center>";
   echo "<input type=hidden name=\"subid[$ix]\" value='0'>";
   echo "<td align=center><input type=text size=5 onKeyUp='return autoTab(this,4,event);' onfocus='select();' name=\"regyr[$ix]\" maxlength=4></td>";
   echo "<td align=center><input type=text size=5 name=\"appdate[$ix]\" onKeyUp='return autoTab(this,4,event);' onfocus='select();' maxlength=4></td>";
   echo "<td align=center><input type=text size=5 name=\"contest[$ix]\"></td>";
   echo "<td align=center><input type=text size=4 class=tiny name=\"rm[$ix]\"></td>";
   echo "<td align=center><input type=text size=5 name=\"obtest[$ix]\"></td>";
   echo "<td align=center><input type=text size=5 name=\"suptest[$ix]\"></td>";
   echo "<td align=center><input type=text size=2 name=\"class2[$ix]\"></td>";
   echo "<td>&nbsp;</td></tr>";
   $ix++;
}
echo "</table></td></tr>";
//get info from __off
$sql="SELECT * FROM $offtable WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<tr align=center><td colspan=2 align=center><table>";
echo "<tr align=left><th align=left class=smaller>Mailing Field:</th>";
echo "<td align=left><input type=text size=8 name=mailing value=\"$row[mailing]\"></td>";
echo "<th align=left class=smaller>Years of Service:</th>";
echo "<td align=left><input type=text size=3 name=years value=\"$row[years]\"></td></tr>";
echo "<tr align=left>";
echo "<th align=left class=smaller>Class for All:</th>";
echo "<td align=left><input type=text size=2 name=class value=\"$row[class]\"></td>";
echo "<th align=left class=smaller>Supervised Test Date:</th>";
echo "<td align=left><input type=text size=5 name=suptestdate value=\"$row[suptestdate]\"></td></tr>";
if($sport=='bb')
{
   //only for 06-07: show checkbox for bb clinic attendance
   $sql2="SELECT * FROM bboff WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left>";
   echo "<td><b>BB Clinic:</b></td>";
   echo "<td colspan=3><input type=checkbox name=bbclinic value='x'";
   if($row[clinic]=='x') echo " checked";
   echo "></td>";
   echo "</tr>";
   echo "<tr align=left>";
   echo "<td><b>Years worked State (Girls):</b></td>";
   echo "<td><input type=text class=tiny size=15 name=\"gstateyears\" value=\"$row[gstateyears]\"></td>";
   echo "<td><b>Total years worked State (Girls):</b></td>";
   echO "<td><input type=text class=tiny size=2 name=\"gnumstateyears\" value=\"$row[gnumstateyears]\"></td>";
   echo "</tr>";
   echo "<tr align=left>";
   echo "<td><b>Years worked State (Boys):</b></td>";
   echo "<td><input type=text class=tiny size=15 name=\"bstateyears\" value=\"$row[bstateyears]\"></td>";
   echo "<td><b>Total years worked State (Boys):</b></td>";
   echO "<td><input type=text class=tiny size=2 name=\"bnumstateyears\" value=\"$row[bnumstateyears]\"></td>";
   echo "</tr>";
   echo "<tr align=left><td colspan=4>(These values are updated at the end of the school year)</td></tr>";
}
else
{
   echo "<tr align=left>";
   echo "<td><b>Years worked State:</b></td>";
   echo "<td><input type=text class=tiny size=15 name=\"stateyears\" value=\"$row[stateyears]\"></td>";
   echo "<td><b>Total years worked State:</b></td>";
   echO "<td><input type=text class=tiny size=2 name=\"numstateyears\" value=\"$row[numstateyears]\"></td>";
   echo "</tr>";
   echo "<tr align=left><td colspan=4>(These values are updated at the end of the school year)</td></tr>";
}
echo "</table></td></tr>";
echo "<tr align=center><td colspan=2><b><u>Registration:</u></b><table>";
echo "<tr align=left><td><b>Date Paid:</b></td>";
if($row[payment]=="")
{
   echo "<td>N/A</td></tr>";
}
else
{
   echo "<td>";
   $appdate=split("-",$appdatesave);
   echo "$appdate[1]/$appdate[2]/$appdate[0]";
   echo "</td></tr>";
}
echo "<tr align=left><td><b>Payment Method:</b></td>";
echo "<td>$row[payment]</td></tr>";
echo "<tr align=left><td><b>Applied Online:</b></td>";
if($row[appid]!=0 && $row[appid]!='' && $row[payment]!="")
   echo "<td><a class=small target=new href=\"apps/app$row[appid].html\">App #$row[appid]</a></td></tr>";
else
   echo "<td>No</td></tr>";
echo "</table></td></tr>";
/*
//get sites data from baoff_sites
echo "<tr align=center><td align=center colspan=2>";
echo "<table cellspacing=1 cellpadding=1><tr align=center><th class=smaller align=center>Site</th><th class=smaller align=center>Off Time</th><th class=smaller align=center>Off Date</th><th class=smaller align=center>Crew</th></tr>";
$sql="SELECT * FROM baoff_sites WHERE offid='$offid' ORDER BY offdate";
$result=mysql_query($sql);
$ix=0;
$subid2=array();
while($row=mysql_fetch_array($result))
{
   echo "<tr align=center>";
   echo "<input type=hidden name=\"subid2[$ix]\" value=\"$row[0]\">";
   echo "<td align=center><input type=text size=20 name=\"site[$ix]\" value=\"$row[site]\"></td>";
   echo "<td align=center><input type=text size=8 name=\"offtime[$ix]\" value=\"$row[offtime]\"></td>";
   $offdate=split("/",$row[offdate]);
   echo "<td align=center><input type=text size=2 name=\"offdate1[$ix]\" value=\"$offdate[0]\">/";
   echo "<input type=text size=2 name=\"offdate2[$ix]\" value=\"$offdate[1]\"></td>";
   echo "<td align=center><input type=text size=40 name=\"crew[$ix]\" value=\"$row[crew]\"></td>";
   echo "</tr>";
   $ix++;
}
$curct=$ix;
$max=$curct+5;
while($ix<$max)
{
   echo "<tr align=center>";
   echo "<input type=hidden name=\"subid2[$ix]\" value='0'>";
   echo "<td align=center><input type=text size=20 name=\"site[$ix]\"></td>";
   echo "<td align=center><input type=text size=8 name=\"offtime[$ix]\"></td>";
   echo "<td align=center><input type=text size=2 name=\"offdate1[$ix]\">/";   
   echo "<input type=text size=2 name=\"offdate2[$ix]\"></td>";
   echo "<td align=center><input type=text size=40 name=\"crew[$ix]\"></td>";
   echo "</tr>";
   $ix++;
}
echo "</table></td></tr>";
*/

echo "<tr align=center><td colspan=2><table width=350><tr align=center><td>";
echo "<input type=submit name=save value=\"Save\">";
echo "<input type=submit name=save value=\"Save & Close\"><br>";
echo "<i><b>Save & Close</b> will close this window and reload the main window so that you will see the information as you have just updated it.</i></td></tr>";
echo "</table></td></tr>";
echo "</table></form>";
echo "<a href=\"javascript:window.close();\" class=small>Close this Window (does not Save)</a>";
?>
<div id="debug"></div>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
