<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

$districts=$sport."districts";
if($sport=='fb') $districts="fbbrackets";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";
$zonestbl=$sport."_zones";
$offtable=$sport."off";
$apptable=$sport."apply";
if(ereg("bb",$sport)) 
{
   $offtable="bboff"; $zonestbl="bb_zones"; $apptable="bbapply";
}
else if(ereg("so",$sport)) 
{
   $offtable="sooff"; $zonestbl="so_zones"; $apptable="soapply";
}
$histtable=$offtable."_hist";

$sportname=GetSportName($sport);
$empty=array();
$sportstate2=array(); $sportdates=array(); $sportdates_sm=array();
if($sport=='wr')
{
   $sql="SELECT DISTINCT tourndate FROM wrtourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $wrdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $wrdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $wrdates2[$i]=$row[tourndate];
      $wrdates_sm[$i]="$date[1]/$date[2]";
      $i++;
   }
   $sportdates2=array_merge($empty,$wrdates2);
   $sportdates=array_merge($empty,$wrdates);
   $sportdates_sm=array_merge($empty,$wrdates_sm);
}
else if($sport=='bb' || ereg("bb",$sport))
{
   $bbdates=array();
   $bbdates2=array();
   $bbdates_sm=array();
   $sql2="SELECT DISTINCT tourndate FROM bbtourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $bbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $bbdates2[$i]=$row2[tourndate];
      $bbdates_sm[$i]=date("n/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $sql2="SELECT DISTINCT tourndate,girls,boys FROM bbtourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,girls DESC";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $bbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $bbdates2[$i]=$row2[tourndate];
      $bbdates_sm[$i]=date("n/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $sportdates2=array_merge($empty,$bbdates2);
   $sportdates=array_merge($empty,$bbdates);
   $sportdates_sm=array_merge($empty,$bbdates_sm);
}
else if(ereg("so",$sport))
{
   $sql="SELECT * FROM sotourndates WHERE offdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   $sodates=array(); $i=0; 
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $sodates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." ($row[label])";
      $sodates2[$i]=$row[tourndate];
      $sodates_sm[$i]=date("jS",mktime(0,0,0,$date[1],$date[2],$date[0]))."<br>";
      $curlabel=preg_replace("/State - /","",$row[label]);
      $curlabel=preg_replace("/Substate - /","",$curlabel);
      $curlabel=preg_replace("/Districts - /","",$curlabel);
      $curlabel=preg_replace("/ - /","-",$curlabel);
      $curlabel=preg_replace("/pm/","",$curlabel);
      $curlabel=preg_replace("/am/","",$curlabel);
      $curlabel=preg_replace("/ and later/","+",$curlabel);
      $curlabel=preg_replace("/Morning/","Morn<br>ing",$curlabel);
      $curlabel=preg_replace("/Afternoon/","After<br>noon",$curlabel);
      $curlabel=preg_replace("/Evening/","Eve<br>ning",$curlabel);
      $sodates_sm[$i].=$curlabel;
      $sql2="SELECT * FROM sotourndates WHERE tourndate='$row[tourndate]' AND offdate='x'";
      $result2=mysql_query($sql2);
      $sodatect[$i]=mysql_num_rows($result2);
      $i++;
   }
   $sportdates=array_merge($empty,$sodates);
   $sportdates2=array_merge($empty,$sodates2);
   $sportdates_sm=array_merge($empty,$sodates_sm);
   $sportdatect=array_merge($empty,$sodatect);
}
else if($sport=='ba')
{
   $sql="SELECT * FROM batourndates WHERE offdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   $badates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $badates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $badates2[$i]=$row[tourndate];
      $badates_sm[$i]="$date[1]/$date[2]";
      $i++;
   }
   $sportdates2=array_merge($empty,$badates2);
   $sportdates=array_merge($empty,$badates);
   $sportdates_sm=array_merge($empty,$badates_sm);
}
else if($sport=='sb')
{
   $sql="SELECT * FROM sbtourndates WHERE offdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   $sbdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $sbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sbdates2[$i]=$row[tourndate];
      $sbdates_sm[$i]="$date[1]/$date[2]";
      $i++;
   }
   $sportdates2=array_merge($empty,$sbdates2);
   $sportdates=array_merge($empty,$sbdates);
   $sportdates_sm=array_merge($empty,$sbdates_sm);
}
else if($sport=='vb')
{
   $sql="SELECT * FROM vbtourndates WHERE offdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   $vbdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $vbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $vbdates2[$i]=$row[tourndate];
      $vbdates_sm[$i]="$date[1]/$date[2]";
      $i++;
   }

   $sportdates2=array_merge($empty,$vbdates2);
   $sportdates=array_merge($empty,$vbdates);
   $sportdates_sm=array_merge($empty,$vbdates_sm);
}
else if($sport=='fb')
{
   $sql="SELECT * FROM fbtourndates WHERE offdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   $fbdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $fbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $fbdates2[$i]=$row[tourndate];
      $fbdates_sm[$i]="$date[1]/$date[2]";
      $i++;
   }
   $sportdates2=array_merge($empty,$fbdates2);
   $sportdates=array_merge($empty,$fbdates);
   $sportdates_sm=array_merge($empty,$fbdates_sm);
}

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
$dboffs="$db_name2";
$dbscores="$db_name";
$thisyr=GetFallYear('fb');

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$curix=$ix;

//get type (District, State, etc)
$sql="SELECT t1.type,t2.time FROM $dboffs.$districts AS t1, $dboffs.$disttimes AS t2 WHERE t1.id=t2.distid AND t2.id='$disttimesid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type=$row[0];
if($row[time]=='standby') $standby=1;
else $standby=0;

if($type!="State")
{
   if($disttimesid)
   {
      $sql="SELECT * FROM $disttimes WHERE id='$disttimesid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $distid=$row[distid];
      if($sport=='bb' || $sport=='so')
         $gender=$row[gender]; 
      $date=split("-",$row[day]);  $day=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $time=$row[time];
   }
   $sql="SELECT * FROM $dboffs.$districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row['class']; $dist=$row[district]; 
   if($sport=='fb')
   {
      $round=$row[round];
      $school1=GetSchoolName($row[sid1],'fb',$thisyr);
      $school2=GetSchoolName($row[sid2],'fb',$thisyr);
      $temp=split("-",$row[day]);
      $distdate=date("F d, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
      $time=$row[time];
      $gamenum=$row[gamenum];
      $hostschool=GetSchoolName($row[hostschool],'fb',$thisyr);
   }
   if($sport=='wr') 
   {
      $distdate="";
      $date=split("/",$row[dates]);
      for($i=0;$i<count($date);$i++)
      {
         $temp=split("-",$date[$i]);
         $distdate.=date("F j",mktime(0,0,0,$temp[1],$temp[2],$temp[0])).", ";
      }
      $distdate.=$temp[0]; 
   }
   $type=$row[type];
   if($sport!='fb')
      $hostschool=$row[hostschool];
   $schools=$row[schools];
   if($sport=='so')
      $gender=$row[gender];
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<table cellspacing=0 cellpadding=2 frame=all rules=all style=\"border:#a0a0a0 1px solid;\" width=95%>";
echo "<caption align=center><b>$sportname Officials:</b><br>";
echo "<table class='nine'>"; // width='700px'>";

/****OFFICIALS FILTER****/
echo "<form method=post action=\"offspick.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=ix value=$curix>";
echo "<input type=hidden name=disttimesid value=\"$disttimesid\">";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<tr align=center><td colspan=2><br><table>";
echo "<tr align=left><td colspan=2><b>Officials Filter:";
echo "<font style=\"color:red\"><b>&nbsp;&nbsp;";
//if($filter || $filteragain) echo "ON";
//else echo "OFF";
echo "</b></font><hr></b></td></tr>";
/***BY ZONE(S)***/
if($zones && $zones!='') $zonechoices=split(";",$zones);
echo "<tr valign=top align=left><td><b>Zone(s):</b></td>";
echo "<td><select multiple size=4 name=zonechoices[]><option";
if($zonechoices[0]=="All Zones" || !$zonechoices[0]) echo " selected";
echo ">All Zones</option>";
$sql="SELECT * FROM $dboffs.$zonestbl ORDER BY zone";
$result=mysql_query($sql);
if($zonechoices[0]=="All Zones")
   $z=1;
else
   $z=0;
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($row[zone]==$zonechoices[$z])
   {
      echo " selected";
      $z++;
   }
   echo ">$row[zone]</option>";
}
echo "</select></td></tr>";
/***BY DATE(S) AVAILABLE***/
echo "<tr valign=top align=left><td><b>Dates Available:</b></td>";
echo "<td><p>";
if(!$thisandor) $thisandor=$andor;
echo "<input type=radio name=thisandor value='AND'";
if(!$thisandor || $thisandor=='' || $thisandor=='AND') 
{
   $thisandor="AND"; echo " checked";
}
echo ">ALL of the following dates...&nbsp;&nbsp;";
echo "<input type=radio name=thisandor value='OR'";
if($thisandor=='OR') echo " checked";
echo ">ANY of the following dates...</p>";
echo "<table>";
//get dates for selected District
$datechs=split(";",$dates);
for($i=0;$i<count($sportdates);$i++)
{
   if($i%4==0)   echo "<tr align=left>";
   $x=$i+1;
   $var="date".$x;
   echo "<td><input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   for($j=0;$j<count($datechs);$j++)
   {
      if($datechs[$j]==$var) 
      {
	 echo " checked"; $j=count($datechs);
      }
   } 
   echo ">$sportdates[$i]&nbsp;&nbsp;</td>";
   if(($i+1)%4==0)   echo "</tr>";
}
echo "</table></td></tr>";
/***** BY RANK ****/
echo "<tr align=right><td><b>Class:</b></td><td align=left>";
$sql="SELECT DISTINCT class FROM $dboffs.$offtable WHERE mailing>=100 ORDER BY class";
$result=mysql_query($sql);
$ix=0; $string="";
while($row=mysql_fetch_array($result))
{
   $string.="<input type=hidden name=\"ranks[$ix]\" value=\"$row[0]\"><input type=checkbox name=\"rankchecks[$ix]\" value='x'";
   if($rankchecks[$ix]=='x') $string.=" checked";
   $string.="><label style='font-size:12px;'><b>$row[0]</b></label>";
   $string.="  -OR-  ";
   $ix++;
}
echo substr($string,0,strlen($string)-8);
echo "</td></tr>";
/**** BASKETBALL: BY VARSITY CONTESTS ****/
if(ereg("bb",$sport))
{
   echo "<tr><td align=right><b>Varsity Contests:</b><td align=left>";
   echo "<select name=\"contestineq\"><option value=''>Less Than/Greater Than</option>";
   echo "<option value=\"<=\"";
   if($contestineq=="<=") echo " selected";
   echo ">Less Than or Equal To</option><option value=\">=\"";
   if($contestineq==">=") echo " selected";
   echo ">Greater Than or Equal To</option></select>&nbsp;";
   if(!$contestct) $contestct=0;
   echo "<input size=2 type=text name=\"contestct\" value=\"$contestct\"></td></tr>";
}
echo "<tr align=right><td colspan=2><input type=submit name=filter value=\"Filter\"></td></tr>";
echo "</table></td></tr></form>";

if($filter || $filteragain)
{
   /**** FILTER BY ZONES ****/
   if($zonechoices[0]!="All Zones" && count($zonechoices)>0)
   {
      $inzone=0; $zones="";
      for($k=0;$k<count($zonechoices);$k++)
      {
         $zones.=$zonechoices[$k]."; ";
      }
      $zones=substr($zones,0,strlen($zones)-2);
   }
   
   /***FILTER BY DATES AVAILABLE***/
   $dateschecked=0; $dates="";
   for($i=0;$i<count($sportdates);$i++)
   {
      $x=$i+1;
      $var="date".$x;
      
         if($$var=='x')    //if this date was checked in filter
         {
            $dateschecked=1;
            $dates.=$var.";";
         }
   }
   $dates=substr($dates,0,strlen($dates)-1);
}//end if filter

echo "<tr align=left><td class=nine><b>Current Filter:</b>&nbsp;&nbsp;";
echo "<b>Zones:</b>&nbsp;";
if($zones=='') echo "All Zones";
else echo $zones;
echo "&nbsp;&nbsp;<b>Dates:</b>&nbsp;";
if($dates=='') echo "None Specified";
else
{
   $temp=split(";",$dates); $datestr="";
   for($i=0;$i<count($sportdates);$i++)
   {
      $i2=$i+1;
      for($j=0;$j<count($temp);$j++)
      {
            if($temp[$j]=="date".$i2)
               $datestr.="$sodates[$i] ".strtolower($thisandor)." ";
      }
   }
   $datestr=substr($datestr,0,strlen($datestr)-4);
   echo $datestr;
}
echo "<br></td></tr>";

echo "<tr align=center><td>";
if($type=='State')
{
   echo "<table><tr align=center><td class=nine>";
   echo "You are choosing ";
   if((ereg("so",$sport) || ereg("bb",$sport)) && $standby==1) echo "a STAND-BY";
   else echo "an";
   echo " official for ";
   echo "State $sportname.<br></td></tr></table>";
}
else
{
   echo "<table><tr align=left><td class=nine>";
   if($sport=='wr')
      echo "You are choosing a Wrestling official for District <b>$class-$dist</b>,  <b>$distdate</b> (hosted by $hostschool).</td></tr>";
   else if($sport=='fb')
      echo "You are choosing a Football official for <b>Class $class $round (Game #$gamenum)</b>: $school1 vs. $school2 on <b>$distdate</b> at <b>$time</b>.</td></tr>";
   else 
      echo "You are choosing a $sportname official for <b>$gender District $class-$dist</b> (hosted by $hostschool) on <b>$day</b> at <b>$time</b>.</td></tr>";
   if($sport!='fb')
      echo "<tr align=left><td class=nine><b>Schools Competing:</b> $schools<br></td></tr></table>";
   else echo "</table>";
}
echo "</td></tr>";
echo "<tr align=center><td>(The officials you have already chosen for ";
if($type=="State") echo "State";
else if($standby==1) echo " Stand-By";
else if($sport=='fb') echo " this round";
else echo "this $type";
echo " are in <font style=\"color:red\"><b>RED</b></font>)";
echo "</td></tr>";

$varname1="offname".$curix."id";
$varname2="offname".$curix;
echo "<tr align=center><td><a href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='';window.opener.document.forms.assignform.$varname2.value='[Click to Choose Official]';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.dates.value='$dates';window.opener.document.forms.assignform.andor.value='$thisandor';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">Click Here to RESET This Field</a></td></tr>";

echo "</table>";
/*** EXPORT DIV ***/
echo "<div class='alert' id='exportdiv' style='width:400px;'>Please wait for results to load...</div>";
/*** END EXPORT DIV ***/
echo "</caption>";
if(count($sportlabels)>0) $rowspan=3;
else $rowspan=2;
$colheads="<tr align=center><th class=small rowspan=$rowspan width='150px'>Name<br>(click to Pick)";
$csv="\"First\",\"Last\",\"City\",\"State\",\"Zone\",";
if($sport=='fb' && $crew!=1)
{
   $colheads.="<br><a class=tiny href=\"offspick.php?disttimesid=$disttimesid&distid=$distid&sport=$sport&session=$session&dates=$dates&zones=$zones&andor=$thisandor&ix=$curix&crew=1\">Show Crew Members</a>";
}
else if($sport=='fb')
{
   $colheads.="<br><a class=tiny href=\"offspick.php?disttimesid=$disttimesid&distid=$distid&sport=$sport&session=$session&dates=$dates&zones=$zones&andor=$thisandor&ix=$curix&crew=0\">Hide Crew Members</a>";
}
$colheads.="</th>";
$colheads.="<th class=small rowspan=$rowspan>Class/<br>Years/<br>Varsity Worked</th>";
$csv.="\"Class\",\"Years\",";
if(ereg("bb",$sport))
   $csv.="\"Varsity Contests Worked\",";
if($type=="State")
{
   $colheads.="<th class=small rowspan=$rowspan>State</th>";
   $csv.="\"State\",";
}
if(HasClinic($sport))
{
   $colheads.="<th class=small rowspan=$rowspan>Clinic</th>";
   $csv.="\"Clinic\",";
}
$colheads.="<th class=small";
$colheads.=" colspan=".count($sportdates);
$colheads.=">Dates Available</th>";
if(ereg('so',$sport))
{
   $colheads.="<th class=small rowspan=$rowspan>Preferred<br>Partner(s)</th>";
}
$colheads.="<th class=small rowspan=$rowspan>Conflicts</th>";
$colheads.="</tr><tr align=center>";
for($i=0;$i<count($sportdates);$i++)
{
   $colheads.="<th class=small>$sportdates_sm[$i]</th>";
   $csv.="\"Available ".$sportdates_sm[$i]."\",";
}
if(ereg("so",$sport))
   $csv.="\"Preferred Partner(s)\",";
$csv.="\"Conflicts\"\r\n";
$colheads.="</tr>";
$results=array(); $ix=0;	//create array of results to put in correct order according to $sort
$sql="SELECT DISTINCT t1.* FROM $dboffs.officials AS t1,$dboffs.$offtable AS t2 WHERE t1.id=t2.offid AND t2.mailing>=100"; 
$ranksql="(";
for($i=0;$i<count($ranks);$i++)
{
   if($rankchecks[$i]=='x')
   {
      $ranksql.="t2.class='$ranks[$i]' OR ";
   }
}
if($ranksql!="(")
{
   $ranksql=substr($ranksql,0,strlen($ranksql)-4).")";
   $sql.=" AND $ranksql";
}
$sql.=" ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$zonech=split(";",$zones); $datech=split(";",$dates);
$rowct=0;
while($row=mysql_fetch_array($result))
{
   $inzone=1; $indates=1; $registered=1; $incontestrange=1;
   if(ereg("bb",$sport) && $contestineq!='')
   {
      $incontestrange=0;
      $curcontests=CountVarsityContests($row[id],'bb',GetFallYear($sport));
      if($contestineq=="<=" && $curcontests<=$contestct)
         $incontestrange=1;
      else if($contestineq==">=" && $curcontests>=$contestct)
         $incontestrange=1;
   }
   if($zonech[0] && $zonech[0]!="All Zones")
   {  
      $inzone=0;
      for($i=0;$i<count($zonech);$i++)
      {	
         $zonech[$i]=trim($zonech[$i]);
	 $zonech2[$i]=addslashes($zonech[$i]);
	 $row[city]=trim(addslashes($row[city]));
	 $sql2="SELECT * FROM $dboffs.$zonestbl WHERE zone='$zonech2[$i]' AND (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city],%' OR cities LIKE '%, $row[city]')";
 	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)>0)
	 {
	    $inzone=1;
	    $i=count($zonech);
	 }
      }
   }
   $indates=0;
   $dateschecked=0;
   $sql2="SELECT * FROM $dboffs.$apptable WHERE offid='$row[id]' AND (";
   for($i=0;$i<count($datech);$i++)
   {
      $sql2.=$datech[$i]."='x' $thisandor ";
      $dateschecked=1;
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=")";
   if($dates=='') $indates=1;
   else
   {
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
         $indates=1;
   }

   //check if submitted an app
   $sql2="SELECT * FROM $dboffs.$apptable WHERE offid='$row[id]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      $registered=0;
   
   //echo "<br>$inzone,$indates,$registered";
   if($incontestrange==1 && $inzone==1 && $indates==1 && $registered==1)
   {
      $row2=mysql_fetch_array($result2);		//get app info into $row2
  
      $conflict=$row2[conflict]; $fullconflict=$row2[conflict];
      if(strlen($conflict)>100)
      {
         $conflict=substr($conflict,0,100);
	 $conflict.="...<a class=small href=\"#\" onclick=\"window.open('showconflicts.php?table=$apptable&appid=$row2[id]&session=$session','Show_Conflicts','top=400,left=400,width=300,height=100,location=no,scrollbars=yes'); return false;\">View Full</a>";
      }
      if(ereg("so",$sport))
      {
         $prefpartner="";
         if($row2[partner1]!="") 
         {
            $prefpartner.="$row2[partner1]";
            if($row2[city1]!="")
               $prefpartner.=" ($row2[city1])";
         }
         if($row2[partner2]!="")
         {
            if($row2[partner1]!="") $prefpartner.=", ";
            $prefpartner.="$row2[partner2]";
            if($row2[city2]!="")
               $prefpartner.=" ($row2[city2])";
         }
      }
      else if($sport=='fb')	//get crew members
      {
         $sql3="SELECT t1.class,t2.first,t2.last FROM fboff AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid='$row2[chief]'";
   	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
         $curcrew="Chief: ".strtoupper("$row3[first] $row3[last]")." ($row3[class])<br>";
         $sql3="SELECT t1.class,t2.first,t2.last FROM fboff AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid='$row2[referee]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $curcrew.="R: $row3[first] $row3[last] ($row3[class])<br>";
         $sql3="SELECT t1.class,t2.first,t2.last FROM fboff AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid='$row2[umpire]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $curcrew.="U: $row3[first] $row3[last] ($row3[class])<br>";
         $sql3="SELECT t1.class,t2.first,t2.last FROM fboff AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid='$row2[linesman]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $curcrew.="L: $row3[first] $row3[last] ($row3[class])<br>";
         $sql3="SELECT t1.class,t2.first,t2.last FROM fboff AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid='$row2[linejudge]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $curcrew.="LJ: $row3[first] $row3[last] ($row3[class])<br>";
         $sql3="SELECT t1.class,t2.first,t2.last FROM fboff AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid='$row2[backjudge]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $curcrew.="BJ: $row3[first] $row3[last] ($row3[class])<br>";
      }

      $curid=$row[id];
      //get name, city, state, zone from officials table
      $name="$row[first] $row[middle] $row[last]";
	$firstname=$row[first]; $lastname=$row[last];
      $cityst="$row[city], $row[state]";
	$city=$row[city]; $state=$row[state];
         //get zone:
	 $sql3="SELECT zone FROM $dboffs.$zonestbl WHERE (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city],%' OR cities LIKE '%, $row[city]')";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 $zone=$row3[0];

      $sql3="SELECT * FROM $dboffs.$contracts WHERE offid='$curid' AND distid='$distid'";
      if(ereg("bb",$sport) || ereg("so",$sport) || $sport=='ba' || $sport=='sb' || $sport=='vb') //(time slots)
      {
         if($type=='State')
	    $sql3="SELECT t1.* FROM $dboffs.$contracts AS t1,$dboffs.$disttimes AS t2,$dboffs.$districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t1.offid='$curid'";
         else
            $sql3="SELECT * FROM $dboffs.$contracts WHERE offid='$curid' AND disttimesid='$disttimesid'";
      }
      if($sport=='fb')
         $sql3="SELECT * FROM $dboffs.$contracts WHERE offid='$curid' AND gameid='$distid'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)>0) $color="red";	//selected for this district--RED
      else $color="blue";	//not selected for this district--BLUE

      //get district assignments no matter what
      if(ereg("so",$sport) || ereg("bb",$sport) || $sport=='ba' || $sport=='sb' || $sport=='vb' || $sport=='wr')
      {
	 if($sport!='wr')
	 {
	    $sql3="SELECT t2.class,t2.district,t3.day,t3.time,t2.id";
	    if($sport=='so') $sql3.=",t2.gender";
	    $sql3.=" FROM $dboffs.$contracts AS t1, $dboffs.$districts AS t2, $dboffs.$disttimes AS t3 WHERE t1.disttimesid=t3.id AND t3.distid=t2.id AND t1.offid='$curid' AND t2.type!='State' "; 
            if($type=="State")
	       $sql3.="AND t1.accept='y' AND t1.confirm='y' ";
	    $sql3.="ORDER BY t2.class,t2.district,";
	    if($sport=='so') $sql3.="t2.gender,";
	    $sql3.="t3.day,t3.time";
	 }
         else
	 {
            $sql3="SELECT t2.* FROM $dboffs.$contracts AS t1,$dboffs.$districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$curid' AND t2.type!='State' ";
            if($type=="State")
               $sql3.="AND t1.accept='y' AND t1.confirm='y' ";
            $sql3.="ORDER BY t2.class,t2.district";
	 }
	 if(HasClinic($sport))
         {
	    $sql4="SELECT clinic FROM $dboffs.$histtable WHERE offid='$curid' AND clinic='x' AND regyr='".GetSchoolYear(date("Y"),date("n"))."'";
	    $result4=mysql_query($sql4);
	    $row4=mysql_fetch_array($result4);
	    if($row4[0]=='x') $clinic='X';
	    else $clinic="&nbsp;";
	 }
      }
      else if($sport=='fb')
      {
         $sql3="SELECT t2.* FROM $dboffs.$contracts AS t1, $dboffs.$districts AS t2 WHERE t1.gameid=t2.id AND t1.offid='$curid' ORDER BY t2.class,t2.day";
      }
      else
      {
         $sql3="SELECT t2.* FROM $dboffs.$contracts AS t1,$dboffs.$districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$curid' AND t2.type!='State' ";
         if($type=="State")
            $sql3.="AND t1.accept='y' AND t1.confirm='y' ";
         $sql3.="ORDER BY t2.class,t2.district";
      }
      $result3=mysql_query($sql3); 
      $distass=""; $distids=array(); $d=0;	//FOR TRACKING IF ASSIGNED TO MULTIPLE SITES
      while($row3=mysql_fetch_array($result3))
      {
         if($sport=='wr')
         {
	    $temp1=split("/",$row3[dates]);
	    $curdates="";
	    for($i=0;$i<count($temp1);$i++)
	    {
	       $temp2=split("-",$temp1[$i]);
	       $curdates.=date("n/j",mktime(0,0,0,$temp2[1],$temp2[2],$temp2[0])).", ";
       	    }
	    $curdates=substr($curdates,0,strlen($curdates)-2);
	    $distass.="$row3[class]-$row3[district] ($curdates)<br>";
	 }
	 else if($sport=='fb')
	 {
	    $fbtemp=split("-",$row3[day]);
	    $curdate=date("n/j",mktime(0,0,0,$fbtemp[1],$fbtemp[2],$fbtemp[0]));
	    $row3[round]=ereg_replace("First","1st",$row3[round]);
	    $row3[round]=ereg_replace("Second","2nd",$row3[round]);
	    $distass.="$row3[class] $row3[round], Game #$row3[gamenum] ($curdate)<br>";
	 }
	 else 
	 {
	    $temp=split("-",$row3[day]);
	    $thisday=date("n/j",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
	    if($sport=="bbb" || $sport=="sob") $distass.="Boys ";
	    else if($sport=="bbg" || $sport=="sog") $distass.="Girls ";
	    $distass.="$row3[class]-$row3[district]";
	    $distass.=": $thisday(".trim($row3[time]).")<br>";
	    $distids[$d]=$sport.$row3['id']; $d++;
	 }
      }
      if($distass=="")
	 $distass="&nbsp;";
      if(ereg("bb",$sport) || ereg("so",$sport))	//SO & BB: show scheduled assignments for other gender's districts as well
      {
	 if($sport=="bbb") $tempsp="bbg";
	 else if($sport=="bbg") $tempsp="bbb";
	 else if($sport=="sob") $tempsp="sog";
	 else $tempsp="sob";
         $tempcontracts=$tempsp."contracts";
         $tempdistricts=$tempsp."districts";
         $tempdisttimes=$tempsp."disttimes";
         $sql3="SELECT t2.class,t2.district,t3.day,t3.time,t2.id";
         $sql3.=" FROM $dboffs.$tempcontracts AS t1, $dboffs.$tempdistricts AS t2, $dboffs.$tempdisttimes AS t3 WHERE t1.disttimesid=t3.id AND t3.distid=t2.id AND t1.offid='$curid' AND t2.type!='State' ";
         if($type=="State")
         {
            $sql3.="AND t1.accept='y' AND t1.confirm='y' ";
         }
         $sql3.="ORDER BY t3.day,t3.time,t2.class,t2.district";
         $result3=mysql_query($sql3);
         while($row3=mysql_fetch_array($result3))
         {
            $temp=split("-",$row3[day]);
            $thisday=date("n/j",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
	    if($tempsp=="bbb" || $tempsp=="sob") $distass.="Boys ";
	    else $distass.="Girls ";
            $distass.="$row3[class]-$row3[district]";
            $distass.=": $thisday(".trim($row3[time]).")<br>";
	    $distids[$d]=$tempsp.$row3['id']; $d++;
         }
         if($distass=="")
            $distass="&nbsp;";
      }
      if($type=="State")	//get state assignments
      {
	 $sql3="SELECT t1.time FROM $dboffs.$disttimes AS t1, $dboffs.$contracts AS t2, $dboffs.$districts AS t3 WHERE t1.distid=t3.id AND t1.id=t2.disttimesid AND t3.type='State' AND t2.offid='$curid'";
	 $result3=mysql_query($sql3);
	 $stateass="<font color=\"blue\">";
	 while($row3=mysql_fetch_array($result3))
	 {
	    if($row3[time]=='standby') $stateass.="Stand-By<br>";
	    else $stateass.="STATE<br>";
	 }
	 $stateass.="</font>";
      }

      //get class, years from $offtable
      $sql3="SELECT class,years FROM $dboffs.$offtable WHERE offid='$curid'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $curclass=$row3[0]; $curyears=$row3[1];

      if(!$sort || $sort=="")	//sort by NAME is default
      {
         if($rowct%10==0) echo $colheads;
	 $rowct++;
	 $varname1="offname".$curix."id";
	 $varname2="offname".$curix;
         $name2=ereg_replace("\'","\'",$name);
         if($sport=='fb' && $crew==1) $name=$curcrew;
	 echo "<tr align=left valign=top><td width=150><a href=\"#\" style=\"color:$color\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.dates.value='$dates';window.opener.document.forms.assignform.andor.value='$thisandor';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\" class=small>$name</a><br>$cityst<br>($zone)</td>";
         echo "<td>$curclass/$curyears";
	 $csv.="\"$firstname\",\"$lastname\",\"$city\",\"$state\",\"$zone\",\"$curclass\",\"$curyears\",";
	 if(ereg("bb",$sport))
	 {
   	    $bbcontests=CountVarsityContests($curid,'bb',GetFallYear($sport));
	    echo "/$bbcontests";
	    $csv.="\"$bbcontests\",";
	 }
	 echo "</td>";
	 if($type=="State") { echo "<td>$stateass</td>"; $csv.="\"$stateass\","; }
	 if(HasClinic($sport))
	 { 
	    echo "<td align=center><b>$clinic</b></td>"; 
	    if($clinic=="&nbsp;") $csv.="\"\",";
	    else $csv.="\"$clinic\","; 
	 }
      }
      else
      {
	 $results[offid][$ix]=$curid;
	 $results[name][$ix]=$name;
	 $results[crew][$ix]=$curcrew;
	 $results[city][$ix]=$cityst;
	 $results[zone][$ix]=$zone;
	 $results[clinic][$ix]=$clinic;
         $results[conflict][$ix]=$conflict;
         $results[prefpartner][$ix]=$prefpartner;
	 $results[color][$ix]=$color;
	 $results[distass][$ix]=$distass;
	 if($type=="State") $results[stateass][$ix]=$stateass;
	 $results['class'][$ix]=$curclass;
	 $results[years][$ix]=$curyears;
         if(ereg("bb",$sport))
         {
            $bbcontests=CountVarsityContests($curid,'bb',GetFallYear($sport));
            $results[contests][$ix]=$bbcontests;
         }
      }
      for($i=0;$i<count($sportdates);$i++)
      {
         $index=$i+1;
	 $field="date".$index;
	 if(!$sort || $sort=="")
	 {
 	       if(ereg($sportdates_sm[$i],$distass))	//if this official assigned to this day
	       {
	          $games=split("<br>",$distass);
	          $game="";
	          for($j=0;$j<count($games);$j++)
	          {
	             if(ereg($sportdates_sm[$i],$games[$j]))
	             {
	                if($game!="") $game.="<br>";
	                $game.=ereg_replace(": ".$sportdates_sm[$i]," ",$games[$j]);
	                $game=ereg_replace(" CT","",$game);
		        $game=ereg_replace(" MT","",$game);
		        $game=ereg_replace(" PM","",$game); $game=ereg_replace(" AM","",$game);
	                $game=ereg_replace($sportdates_sm[$i],"",$game);
	                $game=ereg_replace("\(\)","",$game);
	                $game=ereg_replace("\(:\)","",$game); 
	                $game=trim($game);
	             }
	          }
		  if(OffIsDoubleAssigned($sport, $sportdates2[$i],$curid))
    	             $game.="<br><span style=\"color:#ffffff;\"><b>DOUBLE-ASSIGNED</b></span>";
	          if($game=="") $game=$games[$i];
			//IF NON-BB/SO OR BB/SO & this is a "DOUBLE ASSIGNMENT" - show in RED
		  if((!preg_match("/bb/",$sport) && !preg_match("/so/",$sport)) || preg_match("/DOUBLE-ASSIGNED/", $game))
	             echo "<td align=center bgcolor=\"red\">$game</td>";
		  else 	//BB/SO that is NOT a double assignment - show in green
                     echo "<td align=center bgcolor=\"#00ff00\">$game</td>";
		  $csv.="\"$game\",";
	       }
	       else if($row2[$field]=='x')
	       {
	          echo "<td align=center bgcolor=yellow><b>X</b></td>";
		  $csv.="\"X\",";
	       }
	       else
	       {
	          echo "<td>&nbsp;</td>";
		  $csv.="\"\",";
	       }
	 }
	 else
	    $results[$field][$ix]=$row2[$field];
      }
      if(!$sort || $sort=='')
      {
         if(ereg("so",$sport))
	 {
            echo "<td width=100>$prefpartner&nbsp;</td>";
	    $csv.="\"$prefpartner\",";
	 }
         echo "<td width=150>$conflict&nbsp;</td>";
	 $csv.="\"$fullconflict\"\r\n";
         echo "</tr>";
      }
      $ix++;
   }
}
/*** WRITE EXPORT ***/
   $filename=strtoupper($sport)."Officials".date("mdY").".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
?>
<script language='javascript'>
document.getElementById('exportdiv').innerHTML="<p><a href=\"reports.php?session=<?php echo $session; ?>&filename=<?php echo $filename; ?>\">Download Export of Officials Shown Below</a></p>";
</script>
<?php
if($sort && $sort!="")	//display results in $sort order
{
   if($sort=='zone') $table=$zonestbl;
   else if($sort=='conflict') $table=$apptable;
   else $table="officials";
   $sql="SELECT DISTINCT $sort";
   if($sort=='city') $sql.=",state";
   $sql.=" FROM $dboffs.$table ORDER BY $sort";
   $result=mysql_query($sql);
   $rowct=0;
   while($row=mysql_fetch_array($result))
   {
      if($sort=='city')
      $row[0]="$row[0], $row[1]";
      for($i=0;$i<count($results[offid]);$i++)
      {
         if($results[$sort][$i]==$row[0])
         {
	    if($rowct%10==0) echo $colheads;
	    $rowct++;
            $varname1="offname".$curix."id";
	    $varname2="offname".$curix;
	    $curid=$results[offid][$i];
	    $name2=ereg_replace("\'","\'",$results[name][$i]);
	    if($sport=='fb' && $crew==1) $name=$results[crew][$i];
	    else $name=$results[name][$i];
	    $sql3="SELECT * FROM $dboffs.$contracts WHERE distid='$distid' AND offid='$curid'";
	    $result3=mysql_query($sql3);
	    if(mysql_num_rows($result3)>0) $color="red";
	    else $color="blue";
	    echo "<tr align=left><td><a class=small style=\"color:$color\" href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.dates.value='$dates';window.opener.document.forms.assignform.andor.value='$thisandor';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">".$name."</a></td>";
            echo "<td>".$results['class'][$i]."/".$results[years][$i];
	    if(ereg("bb",$sport)) echo "/".$results[contests][$i];
	    echo "</td>";
	    if($type=="State") echo "<td>".$results[stateass][$i]."</td>";
	    echo "<td>".$results[city][$i]."</td>";
	    echo "<td>".$results[zone][$i]."</td>";
	    echo "<td align=center>".$results[clinic][$i]."</td>";
	    
	       for($d=0;$d<count($sportdates);$d++)
	       {
	          $index=$d+1; $field="date".$index;	
                  if(ereg($sportdates_sm[$d],$results[distass][$i]))  //if this official assigned to this day
                  {
                     echo "<td align=center bgcolor=red><b>".strtoupper($results[$field][$i])."</b></td>";
                  }
                  elseif($results[$field][$i]=='x')
                  {
                     echo "<td align=center bgcolor=yellow><b>X</b></td>";
                  }
                  else
                  {
                     echo "<td>&nbsp;</td>";
                  }
	       }
	  
	    if(ereg("so",$sport)) echo "<td width=150>".$results[prefpartner][$i]."</td>";
            echo "<td width=150>".$results[conflict][$i]."</td>";
	    echo "</tr>";
	 }
      }
   }
}
echo "</table>";
if($ix==1) echo "<p>$ix result.</p>";
else echo "<p>$ix results.</p>";
if($ix==0)
   echo "<p>No officials currently have a Mailing # of 100 or higher for $sportname and match the criteria selected in the Filter at the top of this screen.</p>";
else
   echo "<p>The officials shown above are registered for $sportname and have a Mailing # of 100 or higher and also meet any criteria selected in the Filter at the top of this screen.</p>";

echo $end_html;
?>
