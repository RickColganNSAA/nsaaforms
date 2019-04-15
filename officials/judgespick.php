<?php
require 'functions.php';
require 'variables.php';

if(!$sport)
   $sport='pp';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}
$curix=$ix;
$originaldistid=$distid; //if distid=State, it will be changed, so keep old one as well

if($sport=='sp') $sportname="Speech";
else $sportname="Play Production";
$contracts=$sport."contracts";
$districts=$sport."districts";
$declines=$sport."declines";
$offtable=$sport."off";
$zonestbl=$sport."_zones";
$apptable=$sport."apply";

$sql2="SELECT * FROM ".$sport."test ORDER BY place";
$result2=mysql_query($sql2);
$total=mysql_num_rows($result2);
if($total>0) $needed=.8*$total;
else $needed=40;

$empty=array();
$sportstate2=array(); $sportdist2=array(); 
$sportstate_sm=array(); $sportdist_sm=array();
if($sport=='sp')
{
   $spdist=array(); $i=0;
   $spdist2=array(); $spdist_sm=array();
   $sql="SELECT * FROM sptourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $spdist[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $spdist2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $spdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $spstate=array(); $i=0;
   $spstate2=array(); $spstate_sm=array();
   $sql="SELECT * FROM sptourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $class=trim(preg_replace("/State/","",$row[label]));
      $spstate[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $spstate2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $spstate_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $i++;
   }
   $sportstate2=array_merge($empty,$spstate2);
   $sportdist2=array_merge($empty,$spdist2);
   $sportstate_sm=array_merge($empty,$spstate_sm);
   $sportdist_sm=array_merge($empty,$spdist_sm);
}
else if($sport=='pp')
{
   $ppdist=array(); $i=0;
   $ppdist2=array(); $ppdist_sm=array();
   $sql="SELECT * FROM pptourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $ppdist[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $ppstate=array(); $i=0;
   $ppstate2=array(); $ppstate_sm=array();
   $sql="SELECT * FROM pptourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $class=trim(preg_replace("/State/","",$row[label]));
      $ppstate[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $ppstate2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $ppstate_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $i++;
   }
   $sportstate2=array_merge($empty,$ppstate2);
   $sportdist2=array_merge($empty,$ppdist2);
   $sportstate_sm=array_merge($empty,$ppstate_sm);
   $sportdist_sm=array_merge($empty,$ppdist_sm);
}

if($distid=='State')
{
   if($sport=='pp')
   {
      if($ix<3) $class='A';
      else if($ix<6) $class='B';
      else if($ix<9) $class='C1';
      else if($ix<12) $class='C2';
      else if($ix<15) $class='D1';
      else $class='D2';
      $sql="SELECT id,dates FROM $districts WHERE class='$class' AND district=''";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $distid=$row[0];
      $date=split("-",$row[1]);
      $distdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $curdistdate=$row[1];	//keeps date in database format (yyyy-mm-dd)
   }
   else	//speech
   {
      $sql="SELECT id,dates FROM $districts WHERE type='State' AND id='$stateday'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $distid=$row[0]; 
      $statedayofweek=date("l",strtotime($row[dates]));
   }
   $type="State";
}
else
{
   $sql="SELECT * FROM $districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $type=$row[type]; $class=$row['class']; $district=$row[district];
   $date=split("-",$row[dates]);
   $distdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $curdistdate=$row[dates]; 	//keeps date in yyyy-mm-dd format
   $disttime=$row[time];
   $hostschool=$row[hostschool];
   $schools=$row[schools];
   $site=$row[site];
}
if(!$sort || $sort=='')
{
   if($type=='State') $sort='votes';
   else $sort='name';
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#a0a0a0 1px solid;\">";
echo "<caption><b>$sportname Judges:</b><br>";
echo "<table width=600>";

/****JUDGES FILTER****/
echo "<form method=post action=\"judgespick.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=ix value=$curix>";
echo "<input type=hidden name=distid value=\"$originaldistid\">";
echo "<input type=hidden name=stateday value=\"$stateday\">";
echo "<tr align=center><td colspan=2><br><table>";
echo "<tr align=left><td colspan=2><b>Judges Filter:";
echo "<font style=\"color:red\"><b>&nbsp;&nbsp;";
echo "</b></font><hr></b></td></tr>";
/***BY ZONE(S)***/
if($zones && $zones!='') $zonechoices=split(";",$zones);
echo "<tr valign=top align=left><td><b>Zone(s):</b></td>";
echo "<td><select multiple size=4 name=zonechoices[]><option";
if($zonechoices[0]=="All Zones" || !$zonechoices[0]) echo " selected";
echo ">All Zones</option>";
$sql="SELECT * FROM $zonestbl ORDER BY zone";
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
echo "<td><table>";
//get dates for selected District
$distdatechs=split(";",$distdates);
$statedatechs=split(";",$statedates);
if($stateday!='' && trim($statedates)=="")	//BY DEFAULT - CHECK THE STATEDAY
{
   $sql2="SELECT id,dates FROM $districts WHERE type='State' ORDER BY dates";
   $result2=mysql_query($sql2);
   $dayix=1;
   while($row2=mysql_fetch_array($result2))
   {
      if($stateday==$row2[id]) 
	 $statedates="state".$dayix;
      $dayix++;
   }
   $filter=1;
   $statedatechs=split(";",$statedates);
   $$statedates="x";
}

echo "<tr align=left><td><b>Districts:</b></td><td>";
for($i=0;$i<count($sportdist2);$i++)
{
   $x=$i+1;
   $var="dist".$x;
   echo "<input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   for($j=0;$j<count($distdatechs);$j++)
   {
      if($distdatechs[$j]==$var)
      {
         echo " checked"; $j=count($distdatechs);
      }
   }
   echo ">$sportdist2[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "<tr align=left><td><b>State:</b></td><td>";
for($i=0;$i<count($sportstate2);$i++)
{
   $x=$i+1;
   $var="state".$x;
   echo "<input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   for($j=0;$j<count($statedatechs);$j++)
   {
      if($statedatechs[$j]==$var)
      {
         echo " checked"; $j=count($statedatechs);
      }
   }
   echo ">$sportstate2[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "</table></td></tr>";
echo "<tr align=right><td colspan=2><input type=submit name=filter value=\"Filter\"></td></tr>";
echo "</table><hr></td></tr></form>";
if($filter || $filteragain)
{
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
   $distdates=""; $statedates="";
   for($i=0;$i<count($sportdist2);$i++)
   {
      $x=$i+1;
      $var="dist".$x;
      if($$var=='x')    //if this date was checked in filter
      {
         $distdates.=$var.";";
      }
   }
   $distdates=substr($distdates,0,strlen($distdates)-1);
   for($i=0;$i<count($sportstate2);$i++)
   {
      $x=$i+1;
      $var="state".$x;
      if($$var=='x')
      {
	 $statedates.=$var.";";
      }
   }
   $statedates=substr($statedates,0,strlen($statedates)-1);
}//end if filter
echo "<tr align=left><td><b>Current Filter:</b>&nbsp;&nbsp;";
echo "<b>Zones:</b>&nbsp;";
if($zones=='' || !$zones) echo "All Zones";
else echo $zones;
echo "&nbsp;&nbsp;<b>Dates:</b>&nbsp;";
if($distdates=='' && $statedates=="") echo "None Specified";
else
{
   $temp=split(";",$distdates); $datestr="";
   for($i=0;$i<count($sportdist2);$i++)
   {
      $i2=$i+1;
      for($j=0;$j<count($temp);$j++)
      {
         if($temp[$j]=="dist".$i2)
            $datestr.="$sportdist2[$i] or ";
      }
   }
   $datestr=substr($datestr,0,strlen($datestr)-4);
   if($datestr!="") echo "DISTRICTS: $datestr"; 

   $temp2=split(";",$statedates); $datestr2="";
   for($i=0;$i<count($sportstate2);$i++)
   {
      $i2=$i+1;
      for($j=0;$j<count($temp2);$j++)
      {
         if($temp2[$j]=="state".$i2)
         $datestr2.="$sportstate2[$i] or ";
      }
   }
   $datestr2=substr($datestr2,0,strlen($datestr2)-4);
   if($datestr2!="") 
   { 
      if($datestr!="") echo ", ";
      echo "STATE: $datestr2";
   }
}
echo "<br></td></tr>";

echo "<tr align=center><td>";
if($type=='State' && $sport=='pp')
{
   echo "You are choosing a judge for Class ";
   echo "<b>$class</b> State $sportname on <b>$distdate</b>.<br>";
}
else if($type=="State")	//state speech
{
   echo "You are choosing a judge for <b>State Speech - $statedayofweek</b>.<br>";
}
else
{
   echo "<table><tr align=left><td>";
   echo "You are choosing a judge for $sportname <b>$type $class-$district</b> on <b>$distdate</b>";
   if($disttime!='') echo " at <b>$disttime</b>";
   echo ".<br><br>";
   echo "This district will be hosted by <b>$hostschool</b> at <b>$site</b>.<br>";
   echo "<b>Participating Schools:</b> $schools.";
   echo "</td></tr></table><br>";
}
echo "(The judges you have already chosen for ";
if($type!="State") echo "this DAY";
else if($sport=='pp') echo "this CLASS";
else echo "State Speech";
echo " are in <font style=\"color:red\"><b>RED</b></font>)";
echo "</td></tr>";
   
$varname1="assign".$curix;
$varname2="offname".$curix;
echo "<tr align=center><td><a href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='';window.opener.document.forms.assignform.$varname2.value='[Click to Pick Judge]';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.distdates.value='$distdates';window.opener.document.forms.assignform.statedates.value='$statedates';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">Click Here to RESET This Field</a></td></tr>";
echo "</table></caption>";

//COLUMN HEADERS
echo "<tr align=center><th class=small rowspan=2";
if($sort=='name') echo " bgcolor=#E0E0E0";
echo "><a class=tiny href=\"judgespick.php?distid=$originaldistid&sport=$sport&session=$session&distdates=$distdates&statedates=$statedates&zones=$zones&ix=$curix&sort=name\">Name</a><br>(click to Pick)";
if($sort=='name') echo "<br>&nabla;";
echo "</th>";
echo "<th class=small";
echo " colspan=".count($sportdist2);
echo ">Districts</th>";
echo "<th class=small colspan=2>State</th>";
if($type=="State")
{
   echo "<th class=small rowspan=2";
   if($sort=='votes') echo " bgcolor=#E0E0E0";
   echo "><a class=tiny href=\"judgespick.php?distid=$originaldistid&sport=$sport&session=$session&distdates=$distdates&statedates=$statedates&zones=$zones&ix=$curix&sort=votes\">Votes</a>";
   if($sort=='votes') echo "<br>&nabla;";
   echo "</th>";
   echo "<th class=small rowspan=2>State<br>Assignments</th>";
}
echo "<th class=small rowspan=2>District<br>Assignments</th>";
echo "<th class=small rowspan=2";
if($sort=='city') echo " bgcolor=#E0E0E0";
echo "><a class=tiny href=\"judgespick.php?distid=$originaldistid&sport=$sport&session=$session&distdates=$distdates&statedates=$statedates&zones=$zones&ix=$curix&sort=city\">City, State</a>";
if($sort=='city') echo "<br>&nabla;";
echo "</th>";
echo "<th class=small rowspan=2";
if($sort=='zone') echo " bgcolor=#E0E0E0";
echo "><a class=tiny href=\"judgespick.php?distid=$originaldistid&sport=$sport&session=$session&distdates=$distdates&statedates=$statedates&zones=$zones&ix=$curix&sort=zone\">Zone</a>";
if($sort=='zone') echo "<br>&nabla;";
echo "</th>";
echo "<th class=small rowspan=2";
if($sort=='firstyr') echo " bgcolor=#E0E0E0";
echo "><a class=tiny href=\"judgespick.php?distid=$originaldistid&sport=$sport&session=$session&distdates=$distdates&statedates=$statedates&zones=$zones&ix=$curix&sort=firstyr\">New<br>Judge</a>";
if($sort=='firstyr') echo "<br>&nabla;";
echo "</th>";
echo "<th class=small rowspan=2";
if($sort=='classrep') echo " bgcolor=#E0E0E0";
echo "><a class=tiny href=\"judgespick.php?distid=$originaldistid&sport=$sport&session=$session&distdates=$distdates&statedates=$statedates&zones=$zones&ix=$curix&sort=classrep\">Class</a>";
if($sort=='classrep') echo "<br>&nabla;";
echo "</th>";
echo "<th class=small colspan=6>Class Preference</th></tr>";
echo "<tr align=center>";

   //DISTRICT date choices
   for($i=0;$i<count($sportdist2);$i++)
   {
      echo "<th class=small>$sportdist_sm[$i]</th>";
   }
   //STATE date choices
   echo "<th class=small>$sportstate_sm[0]</th>";
   echo "<th class=small>$sportstate_sm[1]</th>";

for($i=0;$i<count($classes);$i++)
{
   echo "<th width=15 class=small>$classes[$i]</th>";
}
echo "</tr>";

$results=array(); $ix=0;	//create array of results to put in correct order according to $sort
if($sport=='pp')
   $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t2.correct>=$needed AND t1.ppmeeting='x' ORDER BY t1.last,t1.first,t1.middle";
else
   $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t2.correct>=$needed AND t1.spmeeting='x' ORDER BY t1.last,t1.first,t1.middle";
//echo $sql;
   $result=mysql_query($sql);
$zonech=array(); $distdatech=array(); $statedatech=array();
$zonech=split(";",$zones); $distdatech=split(";",$distdates); $statedatech=split(";",$statedates);

while($row=mysql_fetch_array($result))
{
   $inzone=1; $indates=1; $registered=1;
   if($zonech[0] && $zonech[0]!="All Zones")
   {  
      $inzone=0;
      for($i=0;$i<count($zonech);$i++)
      {	
         $zonech2[$i]=addslashes($zonech[$i]);
         $row[city]=addslashes($row[city]);
         $sql2="SELECT * FROM $zonestbl WHERE zone='$zonech2[$i]' AND (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city],%' OR cities LIKE '%, $row[city]')";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0)
         {
            $inzone=1;
            $i=count($zonech);
         }
      }
   }
   $indates=0;
   $sql2="SELECT * FROM $apptable WHERE offid='$row[id]' AND (";
   for($i=0;$i<count($distdatech);$i++)
   {
      if($distdatech[$i]!="")
         $sql2.=$distdatech[$i]."='x' OR ";
   }
   for($i=0;$i<count($statedatech);$i++)
   {
      if($statedatech[$i]!="")
         $sql2.=$statedatech[$i]."='x' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=")"; 
   if($distdates=='' && $statedates=='') $indates=1;
   else
   {
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
         $indates=1;
   }

   //check if submitted a play app
   $sql2="SELECT * FROM $apptable WHERE offid='$row[id]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      $registered=0;
   //echo "<br>$inzone,$indates,$registered";
   if($inzone==1 && $indates==1 && $registered==1)
   {
      $row2=mysql_fetch_array($result2);		//get app info into $row2
      $curid=$row[id];
      //get name, city, state, zone, new judge, ld qual from judges table
      $name="$row[first] $row[middle] $row[last]";
      $cityst="$row[city], $row[state]";
      //get zone:
      $row[city]=addslashes($row[city]);
      $sql3="SELECT zone FROM $zonestbl WHERE (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city],%' OR cities LIKE '%, $row[city]')";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $zone=$row3[0];
      $firstyr=strtoupper($row[firstyr]);

      if($originaldistid!="State")	//DISTRICTS: check if assigned on this district's date
         $sql3="SELECT t1.id FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t2.dates='$curdistdate' AND t1.offid='$curid'";
      else if($sport=='pp')	//STATE PLAY: check if assigned to this class for state
         $sql3="SELECT t1.id FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t2.class='$class' AND t2.type='State' AND t1.offid='$curid'";
      else			//STATE SPEECH: check if assigned to state speech
	 $sql3="SELECT id FROM $contracts WHERE distid='$distid' AND offid='$curid'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)>0) $color="red";
      else $color="blue";

      //get current assignments
      if($type=='State')
      {
	 $sql3="SELECT t2.* FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$curid' AND t2.type='State' ORDER BY t2.class";
	 $result3=mysql_query($sql3);
	 $stateass="";
	 while($row3=mysql_fetch_array($result3))
	 {
	    if($sport=='pp')
	    {
	       $date=split("-",$row3[dates]);
	       $stateass.="$row3[class] ($date[1]/$date[2]), ";
	    }
	    else
	       $stateass.="STATE-".substr(date("l",strtotime($row3[dates])),0,3)."<br>";
	 }
	 if(mysql_num_rows($result3)>0)
	 {
	    if($sport=='pp') $stateass=substr($stateass,0,strlen($stateass)-2);
   	    else $stateass=substr($stateass,0,strlen($stateass)-4);
	 }
	 else
	    $stateass="&nbsp;";
	 //if they've declined any state contracts, show them with $stateass
	 $sql3="SELECT t1.* FROM $declines AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$curid' AND t2.type='State'";
	 $result3=mysql_query($sql3);
	 if(mysql_num_rows($result3)>0)
	    $stateass="<b><i>DECLINED</i></b>";
      }
      //get district assignments no matter what
      $sql3="SELECT t2.* FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$curid' AND t2.district!='' ORDER BY t2.class,t2.district";
      $result3=mysql_query($sql3);
      $distass="";
      while($row3=mysql_fetch_array($result3))
      {
	 $date=split("-",$row3[dates]);
	 $distass.="$row3[class]-$row3[district] ($date[1]/$date[2]), ";
      }
      if(mysql_num_rows($result3)>0)
	 $distass=substr($distass,0,strlen($distass)-2);
      else
	 $distass="&nbsp;";
      //if they've declined any contracts, show them with $distass
      $sql3="SELECT t2.class,t2.district,t2.type FROM $declines AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t1.offid='$curid' AND t2.type!='State'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)>0)
      {
         if($distass!='&nbsp;') $distass.="<br>";
         $distass.="<b><i>DECLINED</b><i>: ";
      }
      while($row3=mysql_fetch_array($result3))
      {
         if($row3[type]=="State") 
	    $distass.="State, ";
	 else
	    $distass.="$row3[class]-$row3[district], "; 
      }
      if(mysql_num_rows($result3)>0)
	 $distass=substr($distass,0,strlen($distass)-2);

      if($sort=="name")	//sort by NAME is default
      {
	 $varname1="assign".$curix;
	 $varname2="offname".$curix;
         $name2=ereg_replace("\'","\'",$name);
	 echo "<tr align=left><td><a href=\"#\" style=\"color:$color\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.distdates.value='$distdates';window.opener.document.forms.assignform.statedates.value='$statedates';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\" class=small>$name</a></td>";
      }
      for($i=0;$i<count($sportdist2);$i++)
      {
         $index=$i+1;
         $field="dist".$index;
         if($sort=="name")
         {   
            if($row2[$field]=='x')
            {
               echo "<td align=center bgcolor=yellow><b>X</b></td>";
            }
            else
            {
               echo "<td>&nbsp;</td>";
            }
         }
         else
            $results[$field][$ix]=$row2[$field];
      }
      if($sort=='name')
      {
         if($row2[state1]=='x')
            echo "<td bgcolor=blue align=center><b>X</b></td>";
         else
            echo "<td>&nbsp;</td>";
         if($row2[state2]=='x')  
            echo "<td bgcolor=blue align=center><b>X</b></td>";
         else
            echo "<td>&nbsp;</td>";
      }
      else
      {
         $results[state1][$ix]=$row2[state1];
         $results[state2][$ix]=$row2[state2];
      }
      if($sort=='name')
      {
	 if($type=='State')
  	 {
	    $curvotes=CountVotes($sport,$curid,"both");	//get ad and coach votes for this judge
	    echo "<td align=center>$curvotes</td>";
	    echo "<td>$stateass</td><td>$distass</td>";
	 }
	 else
	    echo "<td>$distass</td>";
         echo "<td>$cityst</td><td>$zone</td><td align=center>$firstyr</td>";
      }
      else
      {
	 $results[votes][$ix]=CountVotes($sport,$curid,"both");
	 $results[offid][$ix]=$curid;
	 $results[name][$ix]=$name;
	 $results[city][$ix]=$cityst;
	 $results[zone][$ix]=$zone;
	 $results[firstyr][$ix]=strtolower($firstyr);
	 $results[color][$ix]=$color;
	 $results[stateass][$ix]=$stateass;
	 $results[distass][$ix]=$distass;
      }
      if($sort=='name')
	 echo "<td>$row2[classrep]</td>";
      else
	 $results[classrep][$ix]=$row2[classrep];
      for($i=0;$i<count($classes);$i++)
      {
	  if(ereg($classes[$i],$row2[classpref]))
	  {
	     if($sort=='name')
 	        echo "<td bgcolor=green align=center><b>X</b></td>";
             else
             {
		$num=$i+1;
		$index="classpref".$num;
		$results[$index][$ix]='x';
	     }
	  }
	  else
	  {
	     if($sort=='name')
	        echo "<td>&nbsp;</td>";
	     else
	     {
		$num=$i+1;
		$index="classpref".$num;
		$results[$index][$ix]=" ";
	     }
	  }
      }
      if($sort=='name')
         echo "</tr>";
      $ix++;
   }//end if indates and registered and inzone
}

if($sort!="name")	//display results in $sort order
{
   if($sort=='classrep') $table=$apptable;
   else if($sort=='zone') $table=$zonestbl;
   else $table="judges";
   $sql="SELECT DISTINCT $sort";
   if($sort=='city') $sql.=",state";
   $sql.=" FROM $table ORDER BY $sort";
   if($sort=='firstyr') $sql.=" DESC";
   if($sort=='votes')
   {
      $table=$sport."_votes";
      $sql="SELECT t1.officialid,count(t1.officialid),t2.last,t2.first FROM $table AS t1, judges AS t2 WHERE t1.officialid=t2.id GROUP BY t1.officialid ORDER BY 'count(t1.officialid)' DESC,t2.last,t2.first";
   }
   $result=mysql_query($sql); $zero=0;
   while($row=mysql_fetch_array($result))
   {
      if($sort=='city')
         $row[0]="$row[0], $row[1]";
      for($i=0;$i<count($results[offid]);$i++)
      {
         if(($results[$sort][$i]==$row[0] && $sort!='votes') || ($sort=='votes' && $results[offid][$i]==$row[0]))
         {
            $varname1="assign".$curix;
            $varname2="offname".$curix;
            $curid=$results[offid][$i];
            $name2=ereg_replace("\'","\'",$results[name][$i]);
            $sql3="SELECT * FROM $contracts WHERE distid='$distid' AND offid='$curid'";
            $result3=mysql_query($sql3);
            if(mysql_num_rows($result3)>0) $color="red";
            else $color="blue";
            echo "<tr align=left><td><a class=small style=\"color:$color\" href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.distdates.value='$distdates';window.opener.document.forms.assignform.statedates.value='$statedates';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">".$results[name][$i]."</a></td>";
            for($s=0;$s<count($sportdist2);$s++)
            {
               $x=$s+1; $field="dist".$x;
               echo "<td";
               if($results[$field][$i]=='x') echo " bgcolor=yellow";
               echo " align=center>".strtoupper($results[$field][$i])."</td>";
            }
            echo "<td";
            if($results[state1][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state1][$i])."</td>";
            echo "<td";
            if($results[state2][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state2][$i])."</td>";
            if($type=='State')
	    {
	       echo "<td align=center>".$results[votes][$i]."</td>";
               echo "<td>".$results[stateass][$i]."</td>";
	    }
            echo "<td>".$results[distass][$i]."</td>";
            echo "<td>".$results[city][$i]."</td>";
            echo "<td>".$results[zone][$i]."</td>";
            echo "<td align=center>".strtoupper($results[firstyr][$i])."</td>";
            echo "<td";
            echo " align=center>".strtoupper($results[classrep][$i])."</td>";
            echo "<td";
            if($results[classpref1][$i]=='x') echo " bgcolor=green";
            echo " align=center>".strtoupper($results[classpref1][$i])."</td>";
            echo "<td";
            if($results[classpref2][$i]=='x') echo " bgcolor=green";
            echo " align=center>".strtoupper($results[classpref2][$i])."</td>";
	    echo "<td";
	    if($results[classpref3][$i]=='x') echo " bgcolor=green";
	    echo " align=center>".strtoupper($results[classpref3][$i])."</td>";
	    echo "<td";
	    if($results[classpref4][$i]=='x') echo " bgcolor=green";
	    echo " align=center>".strtoupper($results[classpref4][$i])."</td>";
	    echo "<td";
	    if($results[classpref5][$i]=='x') echo " bgcolor=green";
	    echo " align=center>".strtoupper($results[classpref5][$i])."</td>";
	    echo "<td";
	    if($results[classpref6][$i]=='x') echo " bgcolor=green";
	    echo " align=center>".strtoupper($results[classpref6][$i])."</td>";
	    echo "</tr>";
	 }//end if this result is next in order
      }//end for each result
   }//end for each $sort value
   if($sort=='votes')
   {
      $table=$sport."_votes";
      $sql="SELECT t1.id FROM judges AS t1 LEFT JOIN $table AS t2 ON t1.id=t2.officialid WHERE t2.officialid IS NULL ORDER BY t1.last,t1.first";
      $result=mysql_query($sql); $zero=0;
      while($row=mysql_fetch_array($result))
      {
         for($i=0;$i<count($results[offid]);$i++)
         {
            if($results[offid][$i]==$row[0])
            {
               $varname1="assign".$curix;
               $varname2="offname".$curix;
               $curid=$results[offid][$i];
               $name2=ereg_replace("\'","\'",$results[name][$i]);
               $sql3="SELECT * FROM $contracts WHERE distid='$distid' AND offid='$curid'";
               $result3=mysql_query($sql3);
               if(mysql_num_rows($result3)>0) $color="red";
               else $color="blue";
               echo "<tr align=left><td><a class=small style=\"color:$color\" href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.zones.value='$zones';window.opener.document.forms.assignform.distdates.value='$distdates';window.opener.document.forms.assignform.statedates.value='$statedates';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">".$results[name][$i]."</a></td>";
               for($s=0;$s<count($sportdist2);$s++)
               {
                  $x=$s+1; $field="dist".$x;
                  echo "<td";
                  if($results[$field][$i]=='x') echo " bgcolor=yellow";
                  echo " align=center>".strtoupper($results[$field][$i])."</td>";
               }
               echo "<td";
               if($results[state1][$i]=='x') echo " bgcolor=blue";
               echo " align=center>".strtoupper($results[state1][$i])."</td>";
               echo "<td";
               if($results[state2][$i]=='x') echo " bgcolor=blue";
               echo " align=center>".strtoupper($results[state2][$i])."</td>";

               if($type=='State')
               {
                  echo "<td align=center>".$results[votes][$i]."</td>";
                  echo "<td>".$results[stateass][$i]."</td>";
               }
               echo "<td>".$results[distass][$i]."</td>";
               echo "<td>".$results[city][$i]."</td>";
               echo "<td>".$results[zone][$i]."</td>";
               echo "<td align=center>".strtoupper($results[firstyr][$i])."</td>";
               echo "<td";
               echo " align=center>".strtoupper($results[classrep][$i])."</td>";
               echo "<td";
               if($results[classpref1][$i]=='x') echo " bgcolor=green";
               echo " align=center>".strtoupper($results[classpref1][$i])."</td>";
               echo "<td";
               if($results[classpref2][$i]=='x') echo " bgcolor=green";
               echo " align=center>".strtoupper($results[classpref2][$i])."</td>";
               echo "<td";
               if($results[classpref3][$i]=='x') echo " bgcolor=green";
               echo " align=center>".strtoupper($results[classpref3][$i])."</td>";
               echo "<td";
               if($results[classpref4][$i]=='x') echo " bgcolor=green";
               echo " align=center>".strtoupper($results[classpref4][$i])."</td>";
               echo "<td";
               if($results[classpref5][$i]=='x') echo " bgcolor=green";
               echo " align=center>".strtoupper($results[classpref5][$i])."</td>";
               echo "<td";
               if($results[classpref6][$i]=='x') echo " bgcolor=green";
               echo " align=center>".strtoupper($results[classpref6][$i])."</td>";
               echo "</tr>";
            }//end if this result is next in order
         }//end for each result
      }//end for each $sort value
   }//end if sort==votes
}//end if sort by other than name

echo "</table>";
echo "$ix results";

echo $end_html;
?>
