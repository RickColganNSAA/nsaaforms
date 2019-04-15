<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";
$schooltbl=GetSchoolsTable($sport);
if($sport=='sog' || $sport=='sob')
{
   $sport2="so";
   $zonestbl=$sport2."_zones";
   $offtable=$sport2."off";
   $apptable=$db_name.".hostapp_".$sport2;
   if($sport=='sog') $gender="Girls";
   else $gender="Boys";
}
else
{
   $zonestbl=$sport."_zones";
   $offtable=$sport."off";
   $apptable=$db_name.".hostapp_".$sport;
   if($sport=="bbb") $apptable=$db_name.".hostapp_bb_b";
   else if($sport=="bbg") $apptable=$db_name.".hostapp_bb_g";
   else if($sport=="teb") $apptable=$db_name.".hostapp_te_b";
   else if($sport=="teg") $apptable=$db_name.".hostapp_te_g";
}

$sportname=GetSportName($sport);
$empty=array();
$sportstate2=array(); $sportdates=array(); $sportdates_sm=array();
if($sport=='vb')
{
   $sql2="SELECT * FROM vbtourndates WHERE hostdate='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $vbhostdates=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("l, M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $vbhostdates[$i]=$showdate;
      $i++;
   }
   $sportdates=array_merge($empty,$vbhostdates);
   $sportdates_sm=array_merge($empty,$vbhostdates_sm);
}
else if($sport=='go_g')
{
   $go_ghostdates=array(); $i=0;
   $go_ghostdates_sm=array();
   $sql="SELECT * FROM go_gtourndates WHERE hostdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $go_ghostdates[$i]=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $go_ghostdates_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $sportdates=array_merge($empty,$go_ghostdates);
   $sportdates_sm=array_merge($empty,$go_ghostdates_sm);
}
else if($sport=='go_b')
{
   $go_bhostdates=array(); $i=0;
   $go_bhostdates_sm=array();
   $sql="SELECT * FROM go_btourndates WHERE hostdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $go_bhostdates[$i]=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $go_bhostdates_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $sportdates=array_merge($empty,$go_bhostates);
   $sportdates_sm=array_merge($empty,$go_bhostdates_sm);
}
else if($sport=='cc' || $sport=='ba')	//only one date OR dates not used on app to host
{
   $sportdates=array();
   $sportdates_sm=array();
}
else if($sport=='sb')
{
   $sql2="SELECT * FROM sbtourndates WHERE hostdate='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $sbhostdates=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sbhostdates[$i]=$showdate;
      $i++;
   }
   $sportdates=array_merge($empty,$sbhostdates);
   $sportdates_sm=array_merge($empty,$sbhostdates_sm);
}
else if($sport=='sog' || $sport=='sob')
{
   $sohostdates=array(); $i=0;
   $sohostdates_sm=array();
   $sql="SELECT DISTINCT tourndate,label FROM sotourndates WHERE hostdate='x' ORDER BY label,tourndate";
   $result=mysql_query($sql);
   $colct=0; $curlabel="";
   $sohostcolspans=array(); $sohostlabels=array();
   $sohostix=array();	//INDICES FOR PURPOSES OF FINDING "date_" FIELD LATER
   while($row=mysql_fetch_array($result))
   {
      if($curlabel!=$row[label])	//re-set colspan count (colct)
      {
	 if($curlabel!='')
	 {
	    $start=$i-$colct; $end=$i;
	    for($c=$start;$c<$end;$c++) $sohostcolspans[$c]=$colct;
	 }
  	 $curlabel=$row[label]; $colct=0;
      }
      $date=explode("-",$row[tourndate]);
      $sohostdates[$i]=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sohostdates_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sohostlabels[$i]=$row[label];
      $sql2="SELECT * FROM sotourndates WHERE hostdate='x' ORDER BY tourndate,label";
      $result2=mysql_query($sql2);
      $curix=1;
      while($row2=mysql_fetch_array($result2))
      {
	 if($row[tourndate]==$row2[tourndate] && $row[label]==$row2[label])
	    $sohostix[$i]=$curix;
	 $curix++;
      } 
      $colct++;
      $i++;
   }
   $start=$i-$colct; $end=$i;
   for($c=$start;$c<$end;$c++) $sohostcolspans[$c]=$colct;
   $sportdates=array_merge($empty,$sohostdates);
   $sportdates_sm=array_merge($empty,$sohostdates_sm);
   $sportcolspans=array_merge($empty,$sohostcolspans);
   $sportlabels=array_merge($empty,$sohostlabels);
   $sportix=array_merge($empty,$sohostix);
}
else if($sport=='pp')
{
   $pphostdates=array(); $i=0;
   $pphostdates_sm=array();
   $sql="SELECT * FROM pptourndates WHERE hostdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $pphostdates[$i]=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $pphostdates_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $sportdates=array_merge($empty,$pphostdates);
   $sportdates_sm=array_merge($empty,$pphostdates_sm);
}
else if($sport=='sp')
{
   $sphostdates=array(); $i=0;
   $sphostdates_sm=array();
   $sql="SELECT * FROM sptourndates WHERE hostdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $sphostdates[$i]=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sphostdates_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $sportdates=array_merge($empty,$sphostdates);
   $sportdates_sm=array_merge($empty,$sphostdates_sm);
}
else if($sport=='wr')
{
   $sql2="SELECT * FROM wrtourndates WHERE hostdate='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $wrhostdates=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $wrhostdates[$i]=$showdate;
      $i++;
   }
   $sportdates=array_merge($empty,$wrhostdates);
   $sportdates_sm=array_merge($empty,$wrhostdates);
}
else if($sport=='bbb')
{
   $sql="SELECT * FROM bbtourndates WHERE boys='x' AND hostdate='x' ORDER BY tourndate,label";
   $result=mysql_query($sql);
   $sportdates=array(); $sportdates_sm=array();
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $datesec=mktime(0,0,0,$date[1],$date[2],$date[0]);
      $sportdates_sm[$i]=date("M j",$datesec);
      if(trim($row[label])!='')
         $sportdates_sm[$i]=preg_replace("/ /","<br />",$row[label])."<br />".$sportdates_sm[$i];
      $sportdates[$i]=$row[id];
      $i++;
   }
}
else if($sport=='bbg')
{
   $sql="SELECT * FROM bbtourndates WHERE girls='x' AND hostdate='x' ORDER BY tourndate,label";
   $result=mysql_query($sql);
   $sportdates=array(); $sportdates_sm=array();
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $datesec=mktime(0,0,0,$date[1],$date[2],$date[0]);
      $sportdates_sm[$i]=date("M j",$datesec);
      if(trim($row[label])!='')
         $sportdates_sm[$i]=preg_replace("/ /","<br />",$row[label])."<br />".$sportdates_sm[$i];
      $sportdates[$i]=$row[id];
      $i++;
   }
}
else if(preg_match("/tr/",$sport))
{
   $sql2="SELECT * FROM trtourndates WHERE hostdate='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $trhostdates=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $trhostdates[$i]=$showdate;
      $i++;
   }
   $sportdates=array_merge($empty,$trhostdates);
   $sportdates_sm=array_merge($empty,$trhostdates);
   $apptable=$db_name.".hostapp_tr";
}

if($sport=='vb' || $sport=='bbb' || $sport=='bbg')
{
   $sql="SELECT DISTINCT class FROM $districts WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   $sportclasses=array(); $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $sportclasses[$ix]=$row[0]; $ix++;
   }
}
else
{
   $sportclasses=array();
}

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session,"$db_name2"))
{
   header("Location:index.php?error=1");
   exit();
}

//get type (District, State, etc)
$sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type=$row[type]; $class=$row['class']; $district=$row[district];
$gender=$row[gender];
$hostschool=$row[hostschool];
$schools=$row[schools];
$curhostid=$row[hostid];

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption align=center><b>Potential $sportname $type $class-$district Hosts:</b><br>";
echo "<table>";

/****HOSTS FILTER****/
echo "<form method=post action=\"hostpick.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<tr align=center><td colspan=2><br><table>";
echo "<tr align=left><td><b>Host Filter:</td></tr>";
echo "<tr align=left><th class=smaller align=left>";
echo "<select name=hostch>";
echo "<option";
if($hostch=="All (Registered) Schools/Colleges") echo " selected";
echo ">All (Registered) Schools/Colleges</option>";
echo "<option";
if($hostch=="Colleges Only") echo " selected";
echo ">Colleges Only</option>";
echo "<option";
if($hostch=="(Registered) Schools Only") echo " selected";
echo ">(Registered) Schools Only</option></select></th></tr>";
echo "<tr align=left><td align=left>";
echo "<input type=checkbox name=appch value='x'";
if($appch=='x') echo " checked";
echo ">&nbsp;Applied to host $sportname Districts</td></tr>";
echo "<tr align=right><td><input type=submit name=filter value=\"Filter\"></td></tr>";
echo "</table>";
echo "</td></tr></form>";

echo "<tr align=center><td><a href=\"#\" onClick=\"window.opener.document.forms.assignform.hostch.value='$hostch';window.opener.document.forms.assignform.appch.value='$appch';window.opener.document.forms.assignform.hostid.value='';window.opener.document.forms.assignform.hostschool.value='[Click to Choose Host]';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">Click Here to RESET This Field</a></td></tr>";

echo "</table></caption>";

if(count($sportlabels)>0)
   $rowspan=3;
else $rowspan=2;
$colheaders="
<tr align=center bgcolor='#f0f0f0'><th class=smaller rowspan=$rowspan>School/College<br>(click to Pick)</th>
<th class=small rowspan=$rowspan>Picked<br>to Host</a></th>
<th class=small rowspan=$rowspan>Applied<br>to Host</a></th>";
if(count($sportdates_sm)>0)
{
   $colspan=count($sportdates_sm);
   $colheaders.="<th class=small colspan=$colspan>Dates Applied to Host</th>";
}
if(count($sportclasses)>0)
   $colheaders.="<th class=small colspan=".count($sportclasses).">Classes Applied to Host</th>";
if($sport=='vb')
{
   $criteria=array("Teams","Spectators","Parking","Lockers","Ceiling<br>Height");
   $criteria_sm=array("teams","spectators","parking","lockers","ceiling");
}
else if($sport=='go_g' || $sport=='go_b')
{
   $criteria=array("Course","Holes","City");
   $criteria_sm=array("course","holes","location");
}
else if($sport=='sb')
{
   $criteria=array("Fields","Lighted<br>Fields","If > 1 Field,<br>Same Venue?");
   $criteria_sm=array("fieldct","lightfieldct","samesite");
}
else if($sport=='ba')
{
   $criteria=array("Lights");
   $criteria_sm=array("lights");
}
else if($sport=='sog' || $sport=='sob')
{
   $criteria=array("Field<br>Width","Field<br>Length","Complex","Lights");
   $criteria_sm=array("width","length","complex","lights");
}
else if($sport=='sp')
{
   $criteria=array("Would Host a<br>Multi-District","Schools","Classrooms");
   $criteria_sm=array("multidist","schools","classrooms");
}
else if($sport=='wr')
{
   $criteria=array("Internet<br>Capabilities","Hosted Regular<br>Season Tourn w/<br>Track Wrestling","Teams","Spectators","Parking","Lockers","Mats","Hotels");
   $criteria_sm=array("internet","regseason","teams","spectators","parking","lockers","mats","hotels");
}
else if($sport=='bbb' || $sport=='bbg')
{
   $criteria=array("Hosted<br>Last<br>Year","Teams","Spectators","Parking","Locker<br>Rooms","Floor<br>Surface","Restraining<br>Line");
   $criteria_sm=array("hostlastyear","teams","spectators","parking","lockers","floor","restline");
}
else if(preg_match("/tr/",$sport))
{
   $criteria=array("Pole Vault<br>Landing Pad","Pole Vault<br>Facilities<br>On-Site","High Jump<br>Landing Pad","Discus<br>Cage","Dual Direction<br>Long Jump Pit","Long Jump Pit<br>Direction","Alternate<br>Facility<br>(not at school)","Teams","Spectators","Parking","Locker<br>Rooms","Meters<br>or Yards","Surface","Lanes","Lanes on<br>Curve","Marked for<br>Super Alley","Last<br>Hosted","Willing to host at district<br>non-member facility?");
   $criteria_sm=array("pvault","pvaultsite","hjump","discus","dualljpit","ljpitdirection","facility","teams","spectators","parking","lockers","measurement","surface","lanes","curvelanes","superalley","lasthost","nonmember");
}
else //no specific criteria on app to host
{
   $criteria=array(); $criteria_sm=array();
}
for($i=0;$i<count($criteria);$i++)
{
   $colheaders.="<th class=small rowspan=$rowspan>$criteria[$i]</th>";
}
$colheaders.="</tr>";
if(count($sportlabels)>0)
{
   $ix=0; $ixend=count($sportcolspans);
   $colheaders.="<tr bgcolor='#f0f0f0' align=center>";
   while($ix<$ixend)
   {
      $curcolspan=$sportcolspans[$ix]; $curlabel=$sportlabels[$ix];
      $colheaders.="<th class=small colspan=\"$curcolspan\">$curlabel</th>";
      $ix+=$curcolspan;
   }
   $colheaders.="</tr>";
}   
$colheaders.="<tr bgcolor='#f0f0f0' align=center>";
if($sport=='pp')
{
   for($i=0;$i<count($sportdates_sm);$i++)
   {
      $colheaders.="<th class=small>$sportdates_sm[$i]</th>";
   }
}
else
{
   for($i=0;$i<count($sportdates_sm);$i++)
   {
      $colheaders.="<th class=small";
      $colheaders.=">$sportdates_sm[$i]</th>";
   }
   for($i=0;$i<count($sportclasses);$i++)
   {
      $colheaders.="<th class=small>$sportclasses[$i]</th>";
   }
}
$colheaders.="</tr>";
echo $colheaders;

$results=array(); $ix=0;	//create array of results to put in correct order according to $sort

//create array of potential hosts
$i=0; $hosts=array();
if($hostch!="(Registered) Schools Only")	//get colleges
{
   $sql="SELECT DISTINCT id,school,level FROM $db_name.logins WHERE level='4' AND usertitle!='Music'";
   if(!$sort || $sort=='') $sql.=" ORDER BY level DESC,school,name";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $hosts[hostid][$i]=$row[id];
      $hosts[level][$i]=$row[level];
      $hosts[hostname][$i]=$row[school];
      $hosts[school][$i]=$row[school];
      $i++;
   }
}
if($hostch!="Colleges Only")	//get high schools
{
   $sql="SELECT id,school,level FROM $db_name.logins WHERE level='5'";
   if(!$sort || $sort=='') $sql.=" ORDER BY level DESC,school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $hosts[hostid][$i]=$row[id];
      $hosts[level][$i]=$row[level];
      $hosts[hostname][$i]=$row[school];
      $hosts[school][$i]=$row[school];
      $i++;
   }
   if(!IsWildcardSport($sport))
   {
      $sql="SELECT DISTINCT id,level,school AS school,school AS hostname FROM $db_name.logins WHERE level='2'";
      if(!$sort || $sort=='') $sql.=" ORDER BY level DESC,school";
   }
   else
   {
      //$sql="SELECT DISTINCT t1.id,t1.level,t1.school AS school,t3.school AS hostname FROM $db_name.logins AS t1, $db_name.headers AS t2, $db_name.$schooltbl AS t3 WHERE t1.level='2' AND t1.school=t2.school AND t2.id=t3.mainsch";
      //if(!$sort || $sort=='') $sql.=" ORDER BY t1.level DESC,t3.school";
      $sql="SELECT DISTINCT id,level,school AS school,school AS hostname FROM $db_name.logins WHERE level='2'";
      if(!$sort || $sort=='') $sql.=" ORDER BY level DESC,school";	//EDITED 3/1/2016
   }
   $result=mysql_query($sql);
   echo mysql_error();
   while($row=mysql_fetch_array($result))
   {
      if($row[school]=="Sandhills")	//use Thedford's login.id
      {
         $sql2="SELECT * FROM $db_name.logins WHERE school='Thedford'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $hosts[hostid][$i]=$row2[id];
	 $hosts[level][$i]=$row2[level];
         $hosts[school][$i]=$row[school];
      }
      else
      {
         $hosts[hostid][$i]=$row[id];
         $hosts[level][$i]=$row[level];
         $hosts[school][$i]=$row[school];
      }
      $hosts[hostname][$i]=$row[hostname];
      $i++;
   }
}
/*
$sql="SELECT id,name,school,level FROM $db_name.logins WHERE ";
if($hostch=="Colleges Only") $sql.="(level='4')";
else if($hostch=="(Registered) Schools Only") $sql.="(level='2' OR level='5')";
else $sql.="(level='2' OR level='4' OR level='5')";
if(!$sort || $sort=='') $sql.=" ORDER BY level DESC,school,name";
$result=mysql_query($sql);

while($row=mysql_fetch_array($result))
*/
//echo count($hosts[hostid]); 
//exit();
for($h=0;$h<count($hosts[hostid]);$h++)
{
   $hostid=$hosts[hostid][$h];
   $level=$hosts[level][$h];
   $hostname=$hosts[hostname][$h];
   $hostname2=addslashes($hostname);
   $school=$hosts[school][$h]; $school2=addslashes($school);

   //get current hosting assignments, if any
   $sql2="SELECT * FROM $districts WHERE hostid='$hostid'";
   $result2=mysql_query($sql2);
   $hosting="";
   while($row2=mysql_fetch_array($result2))
   {
      $hosting.="$row2[type] $row2[class]-$row2[district]<br>";
   }

   $applied=1;
   //check if submitted an app
   $sql2="SELECT * FROM $apptable WHERE school='$school2' AND interested='y'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      $applied=0;

   $registered=0;
   //Fall Sports: Check if declared; Others: Check if registered
   if(($sport=='vb' || $sport=='bbb' || $sport=='bbg' || $sport=='ba' || $sport=='wr' || $sport=='sog' || $sport=='sob' || $sport=='sp' || $sport=='go_b' || $sport=='tr') && IsRegistered2011($hostid,$sport))  
   {
      //(VB is a fall sport, but she assigns those before June 1 archive)
      $registered=1;
   }
   else if(($sport=='sb' || $sport=='go_g' || $sport=='cc' || $sport=='pp') && IsDeclared($school,$sport))
   {
      $registered=1;
   }
   if($hostid==1616) { $registered=1; }
   if(($row[level]!='2' || $registered==1) && ($applied==1 || $appch!='x'))
   {
      //(NOT LEVEL 2 (school) or IS REGISTERED/DECLARED) and (APPLIED TO HOST or NOT FILTERING FOR APPS)
      $row2=mysql_fetch_array($result2);		//get app info into $row2
 
      //if this school is currently selected as host for this district, color RED
      if($hostid==$curhostid) $color="red";
      else $color="blue";

      echo "<tr align=left><td><a href='' style=\"color:$color\" onClick=\"window.opener.document.forms.assignform.hostch.value='$hostch';window.opener.document.forms.assignform.appch.value='$appch';window.opener.document.forms.assignform.hostid.value='$hostid';window.opener.document.forms.assignform.hostschool.value='$hostname2';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\" class=small>$hostname</a></td>";
      echo "<td>$hosting</td>";
      echo "<td>";
      if(mysql_num_rows($result2)==0 || $row2[interested]=='n') echo "NO";
      else echo "<b>YES</b>";
      echo "</td>"; 
      if($sport=='sog' || $sport=='sob')
      {
         for($i=0;$i<count($sportdates);$i++)
         {
            $index=$sportix[$i]; $var="date".$index;
            if($row2[$var]=='y')
               echo "<td align=center bgcolor=yellow><b>X</b></td>";
            else
               echo "<td>&nbsp;</td>";
         } 
      }
      else if(preg_match("/bb/",$sport))
      {
         for($i=0;$i<count($sportdates);$i++)
         {
            $index=$i+1;
            if(preg_match("/\|".$sportdates[$i]."\|/",$row2[dateschecked]))
               echo "<td align=center bgcolor=yellow><b>X</b></td>";
            else
               echo "<td>&nbsp;</td>";
         }
      }
      else
      {
         for($i=0;$i<count($sportdates);$i++)
         {
            $index=$i+1; $var="date".$index;
    	    if($row2[$var]=='y')
               echo "<td align=center bgcolor=yellow><b>X</b></td>";
            else
               echo "<td>&nbsp;</td>";
         }
      }
      for($i=0;$i<count($sportclasses);$i++)
      {
   	 $field="class".strtolower($sportclasses[$i]);
	 if($row2[$field]=='y') 
	    echo "<td width=15 align=center bgcolor=green><b>X</b></td>";
	 else
	    echo "<td width=15>&nbsp;</td>";
      }
      if(!$sort || $sort=='')
      {
	 for($i=0;$i<count($criteria_sm);$i++)
	 {
	    if($sport=='go_g' && $criteria_sm[$i]=='holes')
	    {
	       if($row2[hole9]=='y') echo "<td align=center>9</td>";
	       else if($row2[hole18]=='y') echo "<td align=center>18</td>";	
 	       else echo "<td>&nbsp;</td>";
	    } 
	    else if($sport=='wr' && $criteria_sm[$i]=="hotels")	//LIST HOTELS 1-10
	    {
	       $hotels="";
	       for($k=1;$k<=10;$k++)
	       {
	          $hvar="hotel".$k; $rvar="rooms".$k; $dvar="distance".$k;
		  if($row2[$hvar]!='')
	          {
	             $hotels.=$row2[$hvar].": ".$row2[$rvar]." rooms, ".$row2[$dvar]." mi<br>";
	          }
	       }
	       echo "<td align=left>$hotels&nbsp;</td>";
   	    }
	    else 
	    {
	       if($row2[$criteria_sm[$i]]=='y' || $row2[$criteria_sm[$i]]=='x')
	          $row2[$criteria_sm[$i]]='X';
	       else if($row2[$criteria_sm[$i]]=='n' || $row2[$criteria_sm[$i]]=="")
		  $row2[$criteria_sm[$i]]="&nbsp;";
	       echo "<td align=center>".$row2[$criteria_sm[$i]]."</td>";
	    }
	 }
	 echo "</tr>";
      }
      $ix++;
      if($ix%15==0) echo $colheaders;
   }
}
echo "</table>";
echo "$ix results";
echo $end_html;
?>
