<?php
/*** ASSIGN JUDGES TO STATE SPEECH ***/
$sport='sp';

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}
$curix=$jx;

$sportname="Speech";
$contracts=$sport."contracts";
$districts=$sport."districts";
$declines=$sport."declines";
$offtable=$sport."off";
$zonestbl=$sport."_zones";
$apptable=$sport."apply";

//GET SPEECH DATES
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
$empty=array();
$sportstate2=array(); $sportdist2=array(); 
$sportstate_sm=array(); $sportdist_sm=array();
$sportstate2=array_merge($empty,$spstate2);
$sportdist2=array_merge($empty,$spdist2);
$sportstate_sm=array_merge($empty,$spstate_sm);
$sportdist_sm=array_merge($empty,$spdist_sm);

echo $init_html_ajax;
echo "<script type=\"text/javascript\" src=\"/javascript/JudgeAssign.js\"></script>";
?>
</head>
<body onload="JudgeAssign.initialize('sp');">
<div id='loading' style='display:none;'></div>
<?php
echo "<form><table width=100%><tr align=center><td>";

echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=1>";
echo "<caption><b>$sportname Judges:</b><br>";
echo "<table width=100%>";

echo "<tr align=center><td>";
$sql="SELECT t1.*,t2.*,TIME_FORMAT(t1.time,'%l:%i%p') AS curtime FROM spstaterounds AS t1, spstaterooms AS t2 WHERE t1.id=t2.roundid AND t2.id='$roomid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[rounddate]);
$rounddate=$row[rounddate]; $time=$row[curtime];
echo "You are assigning a judge to <b>Class $row[class] $row[event], Round $row[round]</b> on <b>$date[1]/$date[2]</b> @ <b>$row[curtime]</b> in Room <b>$row[room]</b>.<br>";
echo "</td></tr>";
$class=$row['class'];
$event=$row[event];
for($i=0;$i<count($prefs_lg);$i++)
{
   if($event==$prefs_lg[$i])
      $event_sm=$prefs_sm[$i];
}
   
$varname1="offid".$curix;
$varname2="offname".$curix;
echo "<tr align=center><td><a href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='0';window.opener.document.forms.assignform.$varname2.value='[Click to Pick Judge]';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">Click Here to RESET This Field</a></td></tr>";
echo "<tr align=left><td>\"Ideal\" judges highlighted in YELLOW have been contracted to work on $date[1]/$date[2], have no conflict with Class $class, and have not already been scheduled in this<br>time slot in another room.  These judges also prefer Class $row[class] and rank $row[event] high in preference.</td></tr>";
if($sort && $sort!="ideal")
   echo "<tr align=left><td><a class=small href=\"judgespick3.php?roomid=$roomid&session=$session&ix=$curix\">Sort by Ideal Matches at the TOP</a></td></tr>";
echo "</table></caption>";

//COLUMN HEADERS
echo "<tr align=center><th class=small rowspan=2><a class=tiny href=\"judgespick3.php?roomid=$roomid&session=$session&ix=$curix&sort=name\">Name</a><br>(click to Pick)</th>";
echo "<th class=small rowspan=2>State Room<br>Assignments</th>";
echo "<th class=small colspan=2>State Contract</th>";
echo "<th class=small rowspan=2><a class=tiny href=\"judgespick3.php?roomid=$roomid&session=$session&ix=$curix&sort=classrep\">Class</a></th>";
echo "<th class=small rowspan=2>Class<br>Conflicts</th>";
echo "<th class=small rowspan=2>Event<br>Preferences</th>";
echo "<th class=small rowspan=2>Class<br>Pref</th>";
echo "<th class=small rowspan=2>School<br>Conflicts</th>";
echo "<th class=small rowspan=2>Comments</th>";
echo "</tr>";
echo "<tr align=center>";
$sql="SELECT dates FROM spdistricts WHERE type='State' ORDER BY dates";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $date=split("-",$row[dates]);
   echo "<th class=small align=center>$date[1]/$date[2]</th>";
}
echo "</tr>";
if(!$sort) $sort="ideal";
$results=array(); $ix=0;	//create array of results to put in correct order according to $sort
$rejects=array(); $rx=0;	//create array of judges not shown in results because of conflicts
//GET ALL STATE SPEECH JUDGES FIRST:
$sql="SELECT DISTINCT t1.*,t2.schrep,t2.classrep,t2.classpref,t2.classconflict,t2.schconflict,t2.conflict,t2.humprose,t2.serprose,t2.oralpoetry,t2.persuasive,t2.entertain,t2.extemp,t2.inform,t2.oraldrama,t2.duet FROM judges AS t1,$contracts AS t2,$districts AS t3 WHERE t1.id=t2.offid AND t2.distid=t3.id AND t3.type='State' AND t2.post='y' AND t2.accept='y' AND t2.confirm='y' ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
//echo $sql;
while($row=mysql_fetch_array($result))
{
   $curid=$row[id];
   //first check if judge has a conflict with this class
   $classconflict=0;
   $schrep2=addslashes($row[schrep]);
   $temp=split(",",$row[schconflict]);
   if($row[schrep]!="")
      $sql2="SELECT * FROM $db_name.spschool WHERE (sid='$schrep2' OR "; 
   else
      $sql2="SELECT * FROM $db_name.spschool WHERE (";
   for($i=0;$i<count($temp);$i++)
   {
      if($temp[$i]!='')
         $sql2.="sid='$temp[$i]' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=")";
   if($sql2!="SELECT * FROM $db_name.spschool WHERE ()")
   {
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         if($class==$row2['class']) $classconflict=1;
      }
   }
   if($class==$row[classrep]) $classconflict=1;
   $temp=split(",",$row[classconflict]);
   for($i=0;$i<count($temp);$i++)
   {
      $temp[$i]=trim($temp[$i]);
      if($class==$temp[$i]) $classconflict=1;
   }

   //next check if judge is assigned to this DAY
   $dateconflict=0;
   $sql2="SELECT t1.id FROM spcontracts AS t1, spdistricts AS t2 WHERE t1.distid=t2.id AND t2.type='State' AND t2.dates='$rounddate' AND t1.offid='$curid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      $dateconflict=1; 

   //get name, city, zone, new judge, ld qual from JUDGES table
   $name="$row[first] $row[middle] $row[last]";
   $cityst="$row[city]";

   //Check if this judge is assigned to this room
   $sql2="SELECT * FROM spstateassign WHERE offid='$curid' AND roomid='$roomid'";
   $result2=mysql_query($sql2);
   //if($curid==211) echo $sql2;
   if(mysql_num_rows($result2)>0)	//if yes, make name RED
      $color='red';
   else					//else, regular blue color
      $color='blue';

   //Get any current ROOM ASSIGNMENTS for this judge
   $sql2="SELECT t1.*,t2.room,t2.id AS roomid,TIME_FORMAT(t1.time,'%l:%i%p') AS curtime FROM spstaterounds AS t1, spstaterooms AS t2, spstateassign AS t3 WHERE t1.id=t2.roundid AND t2.id=t3.roomid AND t3.offid='$curid' ORDER BY t1.rounddate,t1.time,t1.round,t1.class,t1.event";
   $result2=mysql_query($sql2);
   $roomass="";
   $scheduled=0;	//=1 if scheduled for this time slot already
   while($row2=mysql_fetch_array($result2))
   {
      $date=split("-",$row2[rounddate]);
      $day=date("D",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if($rounddate==$row2[rounddate])
	 $roomass.="<font style=\"color:blue\">";
      $roomass.="$day, $row2[curtime], $row2[class], $row2[room], ".GetEventAbbrev($row2[event])."<br></font>";
      if($rounddate==$row2[rounddate] && $row2[curtime]==$time && $row2[roomid]!=$roomid)
	 $scheduled=1;
   }
   if($roomass!='')
      $roomass=substr($roomass,0,strlen($roomass)-4);
   else
      $roomass="&nbsp;";

   //Get dates this judge was assigned to for state (Th/Fr/both)
   $sql2="SELECT id,dates FROM spdistricts WHERE type='State' ORDER BY dates";
   $result2=mysql_query($sql2);
   $i=1; $state1=''; $state2='';
   while($row2=mysql_fetch_array($result2))
   {
      $var="state".$i;
      $sql3="SELECT id FROM spcontracts WHERE offid='$curid' AND distid='$row2[id]' AND post='y' AND accept='y' AND confirm='y'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)>0)
         $$var='x';
      $i++;
   }

   //see if this judge is an "ideal match" (prefers this class and this event)
   $classprefs=split("/",$row[classpref]);
   $eventpref=$row[$event_sm];
   $inclasspref=0;
   for($i=0;$i<count($classprefs);$i++)
   {
      if($classprefs[$i]==$class) $inclasspref=1;
   } 
   $ideal=0;
   if($inclasspref==1 && ($eventpref=='1' || $eventpref=='2' || $eventpref=='3'))
      $ideal=1;
   if($ideal==1) $color2="yellow";
   else $color2="white";

   //get this off's app from APPLY table
   $sql2="SELECT * FROM $apptable WHERE offid='$curid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);	

   if($classconflict==0 && $dateconflict==0 && $scheduled==0)
   {
   if($sort=="name")	//sort by NAME is default
   {
      $varname1="offid".$curix;
      $varname2="offname".$curix;
      $name2=ereg_replace("\'","\'",$name);
      echo "<tr align=left><td bgcolor=\"$color2\"><a href=\"#\" style=\"color:$color\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\" class=small>$name</a><br>$cityst</td>";
      echo "<td width=250>$roomass</td>";
   }
   else
   {
      $results[offid][$ix]=$curid;
      $results[name][$ix]=$name;
      $results[color][$ix]=$color;
      $results[color2][$ix]=$color2;
      $results[city][$ix]=$cityst;
      $results[color][$ix]=$color;
      $results[roomass][$ix]=$roomass;
   }
   if($sort=="name")
   {
      if($state1=='x')
         echo "<td bgcolor=blue align=center><b>X</b></td>";
      else
         echo "<td>&nbsp;</td>";
      if($state2=='x')
         echo "<td bgcolor=blue align=center><b>X</b></td>";
      else
         echo "<td>&nbsp;</td>";
   }
   else
   {
      $results[state1][$ix]=$state1;
      $results[state2][$ix]=$state2;
   }
   if($sort=='name')
      echo "<td align=center>$row[classrep]</td>";
   else
      $results[classrep][$ix]=$row[classrep];
   //get class conflicts
   $classconf=split("/",$row[classconflict]);
   $classconfstr=""; 
   for($i=0;$i<count($classconf);$i++)
   {
      if($classconf[$i]!='') $classconfstr.=$classconf[$i].", ";
   }
   $classconfstr=substr($classconfstr,0,strlen($classconfstr)-2);
   $classconfstr=ereg_replace($class,"<b>$class</b>",$classconfstr);
   if($sort=='name')
      echo "<td align=center>$classconfstr</td>";
   else
      $results[classconflict][$ix]=$classconfstr;
   //get this judge's preferences from state contract
   $prefstr=""; $rank=1;
   while($rank<5)
   {
      for($i=0;$i<count($prefs_sm);$i++)
      {
         if($row[$prefs_sm[$i]]==$rank)
	 {
	    $prefstr.="$rank)&nbsp;$prefs_lg[$i]<br>";
	    $i=count($prefs_sm); $ranked=1;
	 }
      }
      $rank++;
   }
   $prefstr=ereg_replace($event,"<font style=\"color:blue\">$event</font>",$prefstr);
   if($sort=='name')
      echo "<td align=left>$prefstr</td>"; 
   else
      $results[eventpref][$ix]=$prefstr;
   //get class preferences
   $classpref=split("/",$row[classpref]);
   $classprefstr="";
   for($i=0;$i<count($classpref);$i++)
   {
      if($classpref[$i]!='') $classprefstr.=$classpref[$i].",";
   }
   $classprefstr=substr($classprefstr,0,strlen($classprefstr)-1);
   $classprefstr=ereg_replace($class,"<b>$class</b>",$classprefstr);
   if($sort=='name')
      echo "<td align=center>$classprefstr</td>";
   else
      $results[classpref][$ix]=$classprefstr;
   //get school conflicts
   $sids=split("/",$row[schconflict]);
   $schconf="";
   for($i=0;$i<count($sids);$i++)
   {
      $sql4="SELECT school FROM $db_name.spschool WHERE sid='$sids[$i]'";
      $result4=mysql_query($sql4); 
      $row4=mysql_fetch_array($result4);
      if(mysql_num_rows($result4)>0)
         $schconf.=$row4[school].", ";
   }
   $schconfstr=substr($schconf,0,strlen($schconf)-2);
   if($sort=='name')
      echo "<td align=left width=150>$schconfstr</td>";
   else
      $results[schconflict][$ix]=$schconfstr;
   if($sort=='name')
   {
      echo "<td><textarea rows=5 cols=30 style='font-size:8pt;' id='conflict".$ix."'>".$row[conflict]."</textarea>";
      echo "<div class=alert id='conflict".$ix."div' style='display:none;'></div><input type=button value='Save' onClick=\"JudgeAssign.updateContract($curid,'conflict','conflict".$ix."');\"></td>";
   }
   else
      $results[conflict][$ix]=$row[conflict];
   if($sort=='name')
      echo "</tr>";
   $ix++;
   }//end if classconflict=0
   else if($name!='')
   {
      $rejects[id][$rx]=$curid;
      $rejects[name][$rx]=$name; 
      $rejects[conflict][$rx]="$classconflict,$dateconflict,$scheduled";
      $rx++;
   }
}

if($sort!="name")	//display results in $sort order
{
   if($sort!="ideal")
   {
   if($sort=='classrep') $table=$apptable;
   else $table="judges";
   $sql="SELECT DISTINCT $sort";
   $sql.=" FROM $table ORDER BY $sort";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($sort=='city')
         $row[0]="$row[0], $row[1]";
      for($i=0;$i<count($results[offid]);$i++)
      {
         if($results[$sort][$i]==$row[0])
         {
            $varname1="offid".$curix;
            $varname2="offname".$curix;
            $curid=$results[offid][$i];
            $name2=ereg_replace("\'","\'",$results[name][$i]);
            $color=$results[color][$i];
	    $color2=$results[color2][$i];
            echo "<tr align=left><td bgcolor=\"$color2\"><a class=small style=\"color:$color\" href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">".$results[name][$i]."</a><br>".$results[city][$i]."</td>";
            echo "<td align=left width=250>".$results[roomass][$i]."</td>";
            echo "<td";
            if($results[state1][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state1][$i])."</td>";
            echo "<td";
            if($results[state2][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state2][$i])."</td>";
            echo "<td";
            echo " align=center>".strtoupper($results[classrep][$i])."</td>";
            echo "<td align=center>".$results[classconflict][$i]."</td>";
	    echO "<td align=left>".$results[eventpref][$i]."</td>";
	    echo "<td align=center>".$results[classpref][$i]."</td>";
	    echo "<td align=left width=150>".$results[schconflict][$i]."</td>";
            echo "<td><textarea rows=5 cols=30 style='font-size:8pt;' id='conflict".$i."'>".$results[conflict][$i]."</textarea>";
            echo "<div class=alert id='conflict".$i."div' style='display:none;'></div><input type=button value='Save' onClick=\"JudgeAssign.updateContract($curid,'conflict','conflict".$i."');\"></td>";
	    //echo "<td align=left width=150>".$results[conflict][$i]."</td>";
	    echo "</tr>";
	 }//end if this result is next in order
      }//end for each result
   }//end for each $sort value
   }//end if sort!=ideal
   else	//sort with ideal matches at top, then by name
   {
      for($i=0;$i<count($results[offid]);$i++)
      {
	 if($results[color2][$i]=="yellow")	//ideal
         {
            $varname1="offid".$curix;
            $varname2="offname".$curix;
            $curid=$results[offid][$i];
            $name2=ereg_replace("\'","\'",$results[name][$i]);
            $color=$results[color][$i];
            $color2=$results[color2][$i];
	    echo "<tr align=left><td bgcolor=\"$color2\"><a class=small style=\"color:$color\" href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">".$results[name][$i]."</a><br>".$results[city][$i]."</td>";
            echo "<td align=left width=250>".$results[roomass][$i]."</td>";
            echo "<td";
            if($results[state1][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state1][$i])."</td>";
            echo "<td";
            if($results[state2][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state2][$i])."</td>";
            echo "<td";
            echo " align=center>".strtoupper($results[classrep][$i])."</td>";
            echo "<td align=center>".$results[classconflict][$i]."</td>";
            echO "<td align=left>".$results[eventpref][$i]."</td>";
            echo "<td align=center>".$results[classpref][$i]."</td>";
            echo "<td align=left width=150>".$results[schconflict][$i]."</td>";
            echo "<td><textarea rows=5 cols=30 style='font-size:8pt;' id='conflict".$i."'>".$results[conflict][$i]."</textarea>";
            echo "<div class=alert id='conflict".$i."div' style='display:none;'></div><input type=button value='Save' onClick=\"JudgeAssign.updateContract($curid,'conflict','conflict".$i."');\"></td>";
            //echo "<td align=left width=150>".$results[conflict][$i]."</td>";
            echo "</tr>";
         }
      }
      for($i=0;$i<count($results[offid]);$i++)
      {
	 if($results[color2][$i]!="yellow")	//not ideal
	 {
            $varname1="offid".$curix;
            $varname2="offname".$curix;
            $curid=$results[offid][$i];
            $name2=ereg_replace("\'","\'",$results[name][$i]);
            $color=$results[color][$i];
            $color2=$results[color2][$i];
	    echo "<tr align=left><td bgcolor=\"$color2\"><a class=small style=\"color:$color\" href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\">".$results[name][$i]."</a><br>".$results[city][$i]."</td>";
            echo "<td align=left width=250>".$results[roomass][$i]."</td>";
            echo "<td";
            if($results[state1][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state1][$i])."</td>";
            echo "<td";
            if($results[state2][$i]=='x') echo " bgcolor=blue";
            echo " align=center>".strtoupper($results[state2][$i])."</td>";
            echo "<td";
            echo " align=center>".strtoupper($results[classrep][$i])."</td>";
            echo "<td align=center>".$results[classconflict][$i]."</td>";
            echO "<td align=left>".$results[eventpref][$i]."</td>";
            echo "<td align=center>".$results[classpref][$i]."</td>";
            echo "<td align=left width=150>".$results[schconflict][$i]."</td>";
            echo "<td><textarea rows=5 cols=30 style='font-size:8pt;' id='conflict".$i."'>".$results[conflict][$i]."</textarea>";
            echo "<div class=alert id='conflict".$i."div' style='display:none;'></div><input type=button value='Save' onClick=\"JudgeAssign.updateContract($curid,'conflict','conflict".$i."');\"></td>";
            //echo "<td align=left width=150>".$results[conflict][$i]."</td>";
            echo "</tr>";
         }
      }
   }
}//end if sort by other than name
echo "</table>$ix results<br>";
for($i=0;$i<count($rejects[name]);$i++)
{
   if($i==0)
      echo "<table cellspacing=2 cellpadding=2><tr align=left><td colspan=2><br><b>State Judges not listed above, with conflicts noted:</b></td></tr>";
   echo "<tr align=left";
   if($i%2==0) echo " bgcolor=#E0E0E0";
      $varname1="offid".$curix;
      $varname2="offname".$curix;
      $name2=ereg_replace("\'","\'",$rejects[name][$i]);
      $curid=$rejects[id][$i];
   echo "><td bgcolor=\"$color2\"><a href=\"#\" onClick=\"window.opener.document.forms.assignform.$varname1.value='$curid';window.opener.document.forms.assignform.$varname2.value='$name2';window.opener.document.forms.assignform.hiddensave.value='Save Changes';window.opener.document.forms.assignform.submit();window.close();\" class=small>".$rejects[name][$i]."</a></td>";
   echo "<td>";
   $conflict=split(",",$rejects[conflict][$i]);
   $string="";
   if($conflict[0]=='1')
      $string.="Conflict with Class $class, ";
   if($conflict[1]=='1')
      $string.="Not assigned to this date, ";
   if($conflict[2]=='1')
      $string.="Already scheduled for this time slot, ";
   $string=substr($string,0,strlen($string)-2);
   $string.=".";
   echo "$string</td></tr>";
} 
if(count($rejects[name])>0)
   echo "</table>";
echo "</form>";
echo $end_html;
?>
