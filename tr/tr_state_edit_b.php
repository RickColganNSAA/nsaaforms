<?php
//tr_state_edit_b.php: Track & Field Dist Results Form (State Qualifiers)
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sql="SELECT * FROM $db_name2.trbdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(GetLevel($session)!=1 && PastDue($row[dates],1) && ($row['class']=="A" || $row['class']=="D" || $row['class']=="C" || $row['class']=="B"))
{
   header("Location:tr_state_view_b.php?session=$session&distid=$distid");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=addslashes($school);

$db1="nsaascores";
$db2="nsaaofficials";

if(!($store=="Save & View Form" || $final=='y'))
{
?>
<script language="javascript">
function UpdatePlaces(ix,tie)
{
   var max=Utilities.getElement('max').value;
   if(tie=="yes" && ix!="")
   {
      if(!Utilities.getElement('placediv'+ ix).innerHTML.match("="))
      {
	 Utilities.getElement('placediv'+ ix).innerHTML="="+ Utilities.getElement('placediv'+ ix).innerHTML;
	 Utilities.getElement('place'+ ix).value=Utilities.getElement('placediv'+ ix).innerHTML;
      }
      var nextix=parseFloat(ix)+1; 
      if(nextix>=max)
      {
	 //UNHIDE LINE
	 Utilities.getElement('line'+ nextix).style.display='';
      }
      Utilities.getElement('placediv'+ nextix).innerHTML=Utilities.getElement('placediv'+ ix).innerHTML; 
      Utilities.getElement('place'+ nextix).value=Utilities.getElement('placediv'+ nextix).innerHTML;
   }
   else if(tie=="no" && ix!="")
   {
      var previx=parseFloat(ix)-1;
      if(Utilities.getElement('placediv'+ previx) && Utilities.getElement('placediv'+ previx).innerHTML==Utilities.getElement('placediv'+ ix).innerHTML)
      {
	 //if this competitor tied with previous competitor, keep their place the same; only change lines after this competitor:
      }
      else
      {
         Utilities.getElement('placediv'+ ix).innerHTML=Utilities.getElement('placediv'+ ix).innerHTML.replace("=","");
         Utilities.getElement('place'+ ix).value=Utilities.getElement('placediv'+ ix).innerHTML;
      }
      var nextix=parseFloat(ix)+1; var curix=parseFloat(ix);
      while(Utilities.getElement('placediv'+ nextix))
      {
	 var nextplace=nextix+1;
         Utilities.getElement('placediv'+ nextix).innerHTML=nextplace;
	 Utilities.getElement('place'+ nextix).value=Utilities.getElement('placediv'+ nextix).innerHTML;
         Utilities.getElement('tie'+ nextix +'no').checked=true;
	 curix=nextix; nextix++;
      }
      var curix=parseFloat(ix);
      while(Utilities.getElement('line'+ curix) && Utilities.getElement('line'+ curix).style.display!='none')
      {
	 if(curix>=max)
            Utilities.getElement('line'+ curix).style.display='none';
         curix++;
      }
   }
}
</script>
<?php
}//end if not redirecting

//get districts this school is hosting
$sql="SELECT id FROM $db1.logins WHERE level='2' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostid=$row[0];
$districts="trbdistricts";
if($level==1 && $distid)
   $sql="SELECT * FROM $db2.$districts WHERE id='$distid'";
else
   $sql="SELECT * FROM $db2.$districts WHERE hostid='$hostid' ORDER BY class,district";
$result=mysql_query($sql);
$distchs=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   $distchs[$ix][id]=$row[id];
   $distchs[$ix][classdist]="$row[class]-$row[district]";
   $ix++;
}
$distcount=$ix;

//get coach
$sql="SELECT name,asst_coaches FROM $db1.logins WHERE level='3' AND sport='Boys Track & Field' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];

if(($store=="Save & Keep Editing" || $store=="Save & View Form") && $distid)
{
   $sql="SELECT * FROM $db2.$districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row['class'];

   //submit qualifiers to database:
   $event=$trevents[$eventnum];
   if($event!="extraqual" && $event!="teamscores")
   {
   $sch_list=""; $stud_list=""; $perf_list=""; $tie_list=""; $place_list="";
   for($i=0;$i<count($sch[$event]);$i++) //for each place, one row:
   {
      $thisplace=$i+1;
      $relay_list=""; 
      if($event!="400r" && $event!="1600r" && $event!="3200r") //if not a relay
      {
	 if($sch[$event][$i]=="Choose School") $sch_list.=",";
	 else
            $sch_list.=$sch[$event][$i].",";
	 if($stud[$event][$i]=="Choose Student") $stud_list.=",";
	 else
	    $stud_list.=$stud[$event][$i].",";
	 if(($class=="B" || $class=="A") && preg_match("/Meter/",$treventslong[$eventnum]))
	 {	//accutrack and handheld times
	    $perf_list.=$perf1[$event][$i]."/".$perf2[$event][$i].",";
	 }
	 else
	    $perf_list.=$perf[$event][$i].",";
	 if($event=="hj" || $event=="pv") 
	 {
	    $place_list.=$place[$event][$i].","; $tie_list.=$tie[$event][$i].",";
	 }
	 //echo $perf_list."<br>";
      }
      else //if event is a relay
      {
	 for($k=0;$k<4;$k++)
	 {
	    if($relay_stud[$event][$i][$k]!="Choose Student")
	    {
	       $relay_list.=$relay_stud[$event][$i][$k].",";
	    }
	    else $relay_list.=",";
	 }
	 $relay_sch=$sch[$event][$i];
	 if($relay_sch=="Choose School") $relay_sch="";
	 $relay_list=substr($relay_list,0,strlen($relay_list)-1);
	 $relay_sch=addslashes($relay_sch);
	 $sql="SELECT * FROM $db1.tr_state_relays_b WHERE district='$distid' AND place='$thisplace' AND relay='$event'";
	 $result=mysql_query($sql);
	 if($class=="A" || $class=="B")
	 {
	    $cur_perf=$perf1[$event][$i]."/".$perf2[$event][$i];
	 }
	 else
	    $cur_perf=$perf[$event][$i];
	 if(mysql_num_rows($result)==0)	//INSERT
	 {
	    $sql2="INSERT INTO $db1.tr_state_relays_b (district,place,relay,sch,stud,perf) VALUES ('$distid','$thisplace','$event','$relay_sch','$relay_list','$cur_perf')";
	 }
	 else		//UPDATE
	 {
	    $sql2="UPDATE $db1.tr_state_relays_b SET sch='$relay_sch', stud='$relay_list',perf='$cur_perf' WHERE district='$distid' AND place='$thisplace' AND relay='$event'";
	 }
	 $result2=mysql_query($sql2);
      }
   }
   if($event!="400r" && $event!="1600r" && $event!="3200r")
   {
      $eventsch="sch_".$event;
      $eventstud="stud_".$event;
      $eventperf="perf_".$event;
      $eventtie="tie_".$event;
      $eventplace="place_".$event;
      $sch_list=substr($sch_list,0,strlen($sch_list)-1);
      $sch_list=addslashes($sch_list);
      $stud_list=substr($stud_list,0,strlen($stud_list)-1);
      $perf_list=substr($perf_list,0,strlen($perf_list)-1);
      if($tie_list!='') $tie_list=substr($tie_list,0,strlen($tie_list)-1);
      if($place_list!='') $place_list=substr($place_list,0,strlen($place_list)-1);
      $sql="SELECT * FROM $db1.tr_state_qual_b WHERE district='$distid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
	 $sql2="INSERT INTO $db1.tr_state_qual_b (district,$eventsch,$eventstud,$eventperf,$eventtie,$eventplace) VALUES ('$distid','$sch_list','$stud_list','$perf_list','$tie_list','$place_list')";
      }
      else				//UPDATE
      {
	 $sql2="UPDATE $db1.tr_state_qual_b SET $eventsch='$sch_list', $eventstud='$stud_list',$eventperf='$perf_list',$eventtie='$tie_list',$eventplace='$place_list' WHERE district='$distid'";
      }
      $result2=mysql_query($sql2);
      if($event=="pv" || $event=="hj" || $event=="lj" || $event=="tj" || $event=="sp" || $event=="d")
      {
	 $pv=""; $hj=""; $lj=""; $tj=""; $sp=""; $d="";
	 for($p=0;$p<count($places[pv]);$p++)
	 {
	    $pv.=$places[pv][$p]."/";
	    $hj.=$places[hj][$p]."/";
	    $lj.=$places[lj][$p]."/";
	    $tj.=$places[tj][$p]."/";
	    $sp.=$places[sp][$p]."/";
	    $d.=$places[d][$p]."/";
	 }
	 $pv=substr($pv,0,strlen($pv)-1);
	 $hj=substr($hj,0,strlen($hj)-1);
	 $lj=substr($lj,0,strlen($lj)-1);
	 $tj=substr($tj,0,strlen($tj)-1);
	 $sp=substr($sp,0,strlen($sp)-1);
	 $d=substr($d,0,strlen($d)-1);
	 $sql="SELECT * FROM $db1.tr_state_place_b WHERE district='$distid'";
	 $result=mysql_query($sql);
	 if(mysql_num_rows($result)==0)	//INSERT
	 {
	    $sql2="INSERT INTO $db1.tr_state_place_b (district,pv,hj,lj,tj,sp,d) VALUES ('$distid','$pv','$hj','$lj','$tj','$sp','$d')";
	 }
	 else
	 {
	    $sql2="UPDATE $db1.tr_state_place_b SET pv='$pv', hj='$hj', lj='$lj', tj='$tj', sp='$sp', d='$d' WHERE district='$distid'";
	 }
	 $result2=mysql_query($sql2); 
      }
   }
   }
   if($event=="teamscores")
   {
      //update team scores, etc
      $teamstr="";
      for($i=0;$i<count($curplace);$i++)
      {
	 if($team[$i]!='')
	   $teamstr.=$team[$i].", ".$score[$i]."<br>";
	 else $teamstr.="<br>";
      }
      $teamstr=substr($teamstr,0,strlen($teamstr)-4);
      $teamstr=addslashes($teamstr); 
      $sql0="SELECT * FROM $db1.tr_state_dist_b WHERE dist='$distid'";
      $result0=mysql_query($sql0);
      if(mysql_num_rows($result0)>0)
         $sql="UPDATE $db1.tr_state_dist_b SET teamscores='$teamstr',teams='$teams',indys='$indys' WHERE dist='$distid'";
      else
	 $sql="INSERT INTO $db1.tr_state_dist_b (dist,teamscores,teams,indys) VALUES ('$distid','$teamstr','$teams','$indys')";
      $result=mysql_query($sql);
   }
   if($event=="pv" || $event=="hj" || $event=="lj" || $event=="sp" || $event=="d" || $event=="tj")
   {
      for($i=0;$i<count($extraid);$i++)
      {
	 if($extrasch[$i]!="Choose School" && $extrastud[$i]!="Choose Student")
	 {
	    $extrasch2[$i]=addslashes($extrasch[$i]);
	    $sql="SELECT * FROM $db1.tr_state_extra_b WHERE id='$extraid[$i]'";
	    $result=mysql_query($sql);
	    if(mysql_num_rows($result)==0 || $extraid=='0')//INSERT
	    {
	       $sql2="INSERT INTO $db1.tr_state_extra_b (district,eventnum,school,student_id,place,perf1,perf2) VALUES ('$distid','$extraevent[$i]','$extrasch2[$i]','$extrastud[$i]','$extraplace[$i]','$extraperf1[$i]','$extraperf2[$i]')";
	    }
	    else //UPDATE
	    {
	       $sql2="UPDATE $db1.tr_state_extra_b SET eventnum='$extraevent[$i]',school='$extrasch2[$i]',student_id='$extrastud[$i]',place='$extraplace[$i]',perf1='$extraperf1[$i]',perf2='$extraperf2[$i]' WHERE id='$extraid[$i]'";
	    }
	    $result2=mysql_query($sql2); 
	 }
	 else if($extraid[$i]!='0')
	 {
	    $sql="DELETE FROM $db1.tr_state_extra_b WHERE id='$extraid[$i]'";
	    $result=mysql_query($sql); 
	    $extraevent[$i]=""; $extrasch[$i]=""; $extrastud[$i]=""; $extraplace[$i]=""; $extraperf1[$i]="";
	    $extraperf2[$i]="";
	 }
      }
   }
   if($final=='y')
   {
      $now=time();
      $sql="UPDATE $db2.$districts SET resultssub_b='$now' WHERE id='$distid'";
      $result=mysql_query($sql);
   }
   if($store=="Save & View Form" || $final=='y')
   {
      header("Location:tr_state_view_b.php?session=$session&school_ch=$school_ch&distid=$distid&final=$final");
      exit();
   }
}//end if $store

$sql="SELECT * FROM $db2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class'];
//Get number of places to show for each type of event ($limit is class-specific & stored in ../variables.php)
$max_track=$limit[$class][track];
$max_field=$limit[$class][field];
$max_relay_sh=$limit[$class][relay_sh];
$max_relay_lg=$limit[$class][relay_lg];
$event=$trevents[$eventnum];
if($event=="pv" || $event=="hj" || $event=="lj" || $event=="sp" || $event=="d" || $event=="tj")
{
   $max=$max_field;
   echo "<input type=hidden name=\"max\" id=\"max\" value=\"$max\">";
}
else if($event=="800" || $event=="1600m" || $event=="3200m" || $event=="110" || $event=="300" || $event=="100" || $event=="200" || $event=="400m")
   $max=$max_track;
else if($event=="3200r")
   $max=$max_relay_lg;
else
   $max=$max_relay_sh;
echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Team2.js"></script>
</head>
<body onload="Team2.initialize('<?php echo $session; ?>','tr_b','school','student','<?php echo $max; ?>');">
<?php
echo GetHeader($session);

echo "<form method=post action=\"tr_state_edit_b.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
?>
<br><h2>DISTRICT RESULTS: Qualifiers for Boys State Track & Field</h2>
<?php
if(GetLevel($session)==1)      
   echo "<a href=\"stateadmin.php?session=$session\" class=small>Return to Track & Field District Results MAIN MENU</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"tr_state_edit_g.php?session=$session&distid=$distid\">Go to GIRLS District Results</a><br>";

echo "<table width=\"700px\"><caption style=\"text-align:left;\">";
$sql="SELECT resultssub_b FROM $db2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[0]!='')    
{
   echo "<br><div class='alert' style=\"width:400px;\"><p>You submitted these results on <b>".date("m/d/y",$row[0])." at ".date("g:ia",$row[0])."</b>.</p><p>To view what you have submitted, <a href=\"tr_state_view_b.php?session=$session&distid=$distid\">Click Here</a>.</p><p>If you need to make a correction, please <b>CONTACT THE NSAA</b></p></div><br>";
   if($level!=1)
   {
      echo $end_html;
      exit();
   }
}
else
{
   echo "<br><div class=\"error\" style=\"width:100%;\"><p><b>You have NOT submitted these results yet.</b></p>";
   echo "<p>Once you are done entering the results FOR EACH EVENT as well as the TEAM SCORES below, <b>click \"Save & View Form\"</b> at the bottom of this screen and follow the instructions on the next screen to submit these results to the NSAA.</p></div>";
}
echo "<p><b>District:&nbsp;</b>";
if($distcount>1)
{
   echo "<select name=distid class=small onchange=\"submit();\"><option>Choose District</option>";
   for($i=0;$i<count($distchs);$i++)
   {
      echo "<option value=\"".$distchs[$i][id]."\"";
      if($distid==$distchs[$i][id]) echo " selected";
      echo ">".$distchs[$i][classdist]."</option>";
   }
   echo "</select>";
}
else
{
   echo $distchs[0]['classdist'];
   echo "<input type=hidden name=distid value=\"$distid\">";
}
echo "</p>";

//get info for this district
$sql="SELECT * FROM $db2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<p><b>Host School:</b>&nbsp;&nbsp;$row[hostschool]</p>";
$date=split("-",$row[dates]);
echo "<p><b>Date/Site:</b>&nbsp;&nbsp;$date[1]/$date[2]/$date[0] at $row[site]</p>";
echo "<p><b>Director(s):</b>&nbsp;&nbsp;$row[director] ($row[email])</p>";
echo "<p><b>Team(s):</b> $row[schools]</p>";
$class=$row['class']; $schoollist=$row[schools]; $sidlist=$row[sids];
$district="$class-$row[district]";

//get track&field schools
$tr_sid=split(",",$sidlist);
for($i=0;$i<count($tr_sid);$i++)
{
   $tr_sch[$i]=GetSchoolName($tr_sid[$i],'trb');
}
array_multisort($tr_sch,SORT_STRING,SORT_ASC,$tr_sid);

if(!$distid || $distid=="Choose District")	//NO DISTRICT SELECTED
{
   if($distcount>1)
      echo "<p><i><b>You have NOT selected a district.  Please select a district above.</b></i></p>";
   else
      echo "<div class='error'>ERROR: No district selected.</div>";
   echo $end_html;
   exit();
}
else if($distcount>1)	//WARN THEM TO MAKE SURE THEY SELECTED THE RIGHT DISTRICT
{
   echo "<div class='alert'><p>Make sure the <b><u>correct district</b></u> is selected above before entering any results!!</p><p><b>You have selected district <span style=\"font-size:16px;color:red\"><u>$district</u></span>.</p></div>";
}
echo "</caption>";
echo "<tr align=center><td>";
echo "<input type=hidden name=autosubmit value='1'>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
if(!$distid || $distid=="Choose District")
   unset($eventnum);
echo "<br /><h2 style=\"border-bottom:#e0e0e0 1px dotted;\">Select Event or Team Scores: <select name=\"eventnum\" onchange='submit();'><option";
if($trevents[$eventnum]=="") echo " selected";
echo ">Choose an Event</option>";
for($i=0;$i<count($treventslong);$i++)
{
   echo "<option value=$i";
   if($i==$eventnum && $trevents[$eventnum]!="") echo " selected";
   echo ">$treventslong[$i]</option>";
}
echo "</select></h2></td></tr><tr align=center>";
$event=$trevents[$eventnum];
echo "<a name=$event href=#$event></a>";
if($event!="teamscores" && $event!="extraqual")
{
   echo "<td><table cellspacing=0 cellpadding=3 frame=\"all\" rules=\"all\" style=\"min-width:400px;border:#808080 1px solid;\"><caption align=left><b>";
   echo "<h3 style=\"color:red\"><u>".$treventslong[$eventnum]."</u></h3>";
   if($treventslong[$eventnum]=="")
   {
      echo "<span style=\"color:red\">You have not selected an event yet.  Please choose the event you wish to enter results for above.</span>";
   }
   else
   {
      if($standard=GetTrackStandard($treventslong[$eventnum],"Boys",$class))
      {
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Automatic Qualifying: $standard)"; 
      }
      echo "</b><ul><li class=notbold>Please select the&nbsp;<b>school</b> and the&nbsp;<b>student</b> for each place and input that student's&nbsp;<b>performance</b>.</li>";
      if(preg_match("/Meter/",$treventslong[$eventnum]) && ($class=="A" || $class=="B"))   //NOTE ABOUT TOP 8/8 vs TOP 12/9
      {         
                if($class=="A") { $finalsmax=12; $relaysmax=7; } 
                else { $finalsmax=9; $relaysmax=6; }
                echo "<li class=\"notbold\"><span style=\"color:red;\"><b>CLASS $class RUNNING EVENTS:</b></span> 
                <ul>
                <li>In the individual running events with <b><u>preliminaries and finals</b></u>, please enter the <b><u>TOP 8</b></u> finishers.</li>
                <li>In the individual running events that are run as <b><u>finals only</b></u>, please enter the <b><u>TOP $finalsmax</b></u> finishers.</li>
                </li>For <b><u>RELAYS</b></u>, please enter the <b><u>TOP $relaysmax</b></u> teams.</li>
                </ul></li>";
      }
      echo "<li class=notbold>If the student you are looking for is NOT on the list, please contact the NSAA immediately.</li><li class=notbold><b>Don't forget to&nbsp;<font style=\"font-size:9pt;color:red\"><b>SAVE</b></font> (click on \"Save & Keep Editing\") before moving on to another event!!!</b></li><li class=notbold>Click \"Save & View Form\" to view all your events as they will appear on the final form.</li>";
      if($treventslong[$eventnum]=="Pole Vault" || $treventslong[$eventnum]=="High Jump")
      {
         echo "<li><b>TIES:</b>
        <ul><li> In <b>".$treventslong[$eventnum]."</b>, per the NSAA manual, those tied for the last qualifying spot shall qualify if places cannot be determined by using the tiebreaker rule for field events.</li>
        <li><b>If there is a tie,</b> check \"Yes\" next to \"Did this competitor and the next competitor TIE?\" under the 
competitor's result.</li>        <li>If <b>MORE THAN 2 SLOTS</b> are needed because of ties, they will show up as you click the \"Yes\" box.</li>
        <li><b><u>PLEASE NOTE:</b></u> There CANNOT be a tie for 1ST PLACE.</li>
        </ul></li>";
      }
      echo "</ul></caption>";
      if(($class=="B" || $class=="A") && preg_match("/Meter/",$treventslong[$eventnum]))	//A&B Running Events: FAT & Handheld options
      {
         echo "<tr><th class=smaller rowspan=2>Place</th>
	<th class=smaller rowspan=2>School</th>
	<th class=smaller rowspan=2>Name</th>
	<th class=smaller colspan=2>Performance</th></tr>";
         echo "<tr><th class=smaller>F.A.T.<br><font style=\"font-size:8pt;\">Ex: $trexampleacc[$eventnum]</font></th><th class=smaller>Handheld<br><font style=\"font-size:8pt;\">Ex: $trexample[$eventnum]</font></th></tr>";
      }
      else if($treventslong[$eventnum]=="High Jump" || $treventslong[$eventnum]=="Pole Vault")	//SPECIAL CASE: allow for ties
      {
	 echo "<tr align=center><td colspan=4>";
	 echo "<div style=\"background-color:#e0e0e0;\"><table cellspacing=0 cellpadding=3 width=\"100%\"><tr align=center><th class=smaller width=\"50px\">Place</th><th class=smaller width=\"225px\">School</th><th class=smaller width=\"225px\">Name</th><th class=smaller>Performance<br><font style=\"font-size:8pt;\">Ex: $trexample[$eventnum]</font></th></tr></table></div>";
      }
      else
      {
         echo "<tr><th class=smaller>Place</th>
	<th class=smaller>School</th>
	<th class=smaller>Name</th>
	<th class=smaller>Performance<br><font style=\"font-size:8pt;\">Ex: $trexample[$eventnum]</font></th></tr>";
      }
      //get info already in database
      if($event!="400r" && $event!="1600r" && $event!="3200r")
      {
         $sql="SELECT * FROM $db1.tr_state_qual_b WHERE district='$distid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if(mysql_num_rows($result)>0)
         { 
            $eventsch="sch_".$event;
            $eventstud="stud_".$event;
            $eventperf="perf_".$event;
	    $eventtie="tie_".$event;
	    $eventplace="place_".$event;
            $tempsch[$event]=split(",",$row[$eventsch]);
            $tempstud[$event]=split(",",$row[$eventstud]);
            $tempperf[$event]=split(",",$row[$eventperf]);
	    $temptie[$event]=split(",",$row[$eventtie]);
	    $tempplace[$event]=split(",",$row[$eventplace]);
            for($i=0;$i<count($tempsch[$event]);$i++)
            {
	       if(!$sch[$event][$i] || $sch[$event][$i]=="Choose School")
	          $sch[$event][$i]=$tempsch[$event][$i];
            }
            for($i=0;$i<count($tempstud[$event]);$i++)
            {
               if(!$stud[$event][$i] || $stud[$event][$i]=="Choose Student")
       	          $stud[$event][$i]=$tempstud[$event][$i];
            } 
            if(!preg_match("/Meter/",$treventslong[$eventnum]) || ($class!="A" && $class!="B"))
            {
               for($i=0;$i<count($tempperf[$event]);$i++)
               {
	          if(!$perf[$event][$i] || $perf[$event][$i]=="")
	             $perf[$event][$i]=$tempperf[$event][$i];
		  if(!$tie[$event][$i] || $tie[$event][$i]=="")
		     $tie[$event][$i]=$temptie[$event][$i];
                  if(!$place[$event][$i] || $place[$event][$i]=="")
                     $place[$event][$i]=$tempplace[$event][$i];
               }
            }
            else
            { 
	       for($i=0;$i<count($tempperf[$event]);$i++)
	       {
	          $tempvar=split("/",$tempperf[$event][$i]);
	          if(!$perf1[$event][$i] || $perf1[$event][$i]=="")
	             $perf1[$event][$i]=$tempvar[0];
	          if(!$perf2[$event][$i] || $perf2[$event][$i]=="")
	             $perf2[$event][$i]=$tempvar[1];
	       }
            }
         }
      }
      else //relays
      {
         $sql="SELECT sch,perf FROM $db1.tr_state_relays_b WHERE district='$distid' AND relay='$event' ORDER BY place";
         $result=mysql_query($sql);
         $ix=0;
         while($row=mysql_fetch_array($result))
         {
            if(!$sch[$event][$ix] || $sch[$event][$ix]=="Choose School")
	       $sch[$event][$ix]=$row[0];
            if($class=="A" || $class=="B")
            {
	       $tempvar=split("/",$row[1]);
	       if(!$perf1[$event][$ix]) $perf1[$event][$ix]=$tempvar[0];
	       if(!$perf2[$event][$ix]) $perf2[$event][$ix]=$tempvar[1];
            }
            else
            {
               if(!$perf[$event][$ix])
	          $perf[$event][$ix]=$row[1];
            } 
            $ix++;
         }
      }
      if(!preg_match("/Meter/",$treventslong[$eventnum]) && !preg_match("/Extra/",$treventslong[$eventnum]) && !preg_match("/Team/",$treventslong[$eventnum]))
      {
         $sql="SELECT * FROM $db1.tr_state_place_b WHERE district='$distid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if(mysql_num_rows($result)>0)
            $curplace=split("/",$row[$event]);
      }
      if($treventslong[$eventnum]=="High Jump" || $treventslong[$eventnum]=="Pole Vault")  //SPECIAL CASE: allow for ties
      {
         //put all $max lines, but hide all but the first one:
	 for($i=0;$i<$max;$i++)
	 {
	    echo "<div id=\"line".$i."\" style=\"";
	    if($i%2>0) echo "background-color:#e0e0e0;";
	    if(!$place[$event][$i] || $place[$event][$i]=="") $place[$event][$i]=$i+1;
	    echo "\"><table cellspacing=0 cellpadding=3 width=\"100%\"><tr align=center><th width=\"50px\"><div style=\"background-color:yellow;text-align:center;margin:3px;\" id=\"placediv".$i."\">".$place[$event][$i]."</div></th>";
	    echo "<input type=hidden name=\"place[$event][$i]\" value=\"".$place[$event][$i]."\" id=\"place".$i."\">";
            echo "<td width=\"225px\"><select class=small id=\"school".$i."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$i;\"><option>Choose School</option>";
	    for($j=0;$j<count($tr_sid);$j++)            
	    {               
		echo "<option value=\"".$tr_sid[$j]."\"";               
		if($sch[$event][$i]==$tr_sid[$j]) echo " selected";               
		echo ">$tr_sch[$j]</option>";            
	    }            
            echo "</select></td><td width=\"225px\"><select class=small id=\"student".$i."\" name=\"stud[$event][$i]\"><option>Choose Student</option>"; 
	    if($sch[$event][$i]!="Choose School")            //get students on TR elig list for selected school
	    {               
	       $sql2="SELECT * FROM $db1.trbschool WHERE sid='".$sch[$event][$i]."'";               
	       $result2=mysql_query($sql2);               
	       $row2=mysql_fetch_array($result2);               
	       $sql="SELECT DISTINCT t2.id,t2.last,t2.first,t2.middle,t2.semesters FROM $db1.eligibility AS t2, $db1.headers AS t3 WHERE t2.school=t3.school AND tr='x' AND gender='M' AND (t3.id='$row2[mainsch]'";               
	       if($row2[othersch1]) $sql.=" OR t3.id='$row2[othersch1]'";               
	       if($row2[othersch2]) $sql.=" OR t3.id='$row2[othersch2]'";               
	       if($row2[othersch3]) $sql.=" OR t3.id='$row2[othersch3]'";               
	       $sql.=") ORDER BY t2.school,t2.last,t2.first";               
	       $result=mysql_query($sql);               
	       while($row=mysql_fetch_array($result))               
	       {                  
                  $id=$row[id];
                  $name=$row[last].", ".$row[first]." ".$row[middle];
                  echo "<option value=\"$id\"";
                  if($stud[$event][$i]==$id) echo " selected";
                  echo ">$name (".GetYear($row[semesters]).")</option>";
               }
	    }//end if school!="Choose School"
            echo "</select></td>";
	    echo "<td><input type=text name=\"perf[$event][$i]\" size=8 value=\"".$perf[$event][$i]."\"></td></tr>";
	    //ASK IF THERE IS A TIE FOR THIS PLACE
	    echo "<tr align=center><td colspan=4><div class=alert style=\"margin:0px;text-align:center;\"><b><i>Did this competitor and the next competitor TIE?&nbsp;&nbsp;</i></b><input type=radio name=\"tie[$event][$i]\" id=\"tie".$i."yes\" value=\"yes\" onClick=\"if(this.checked) UpdatePlaces('$i','yes');\"";
	    if($tie[$event][$i]=="yes") echo " checked";
	    echo "> Yes&nbsp;&nbsp;<input type=radio name=\"tie[$event][$i]\" id=\"tie".$i."no\" value=\"no\" onClick=\"if(this.checked) UpdatePlaces('$i','no');\"";
	    if(!$tie[$event][$i] || $tie[$event][$i]=="no") echo " checked";
            echo "> No</div></td></tr></table></div>";
         }//end for each place
         while($i<($max+10))	//hidden divs in case ties result in more than $max competitors qualifying
	 {
            if(!$place[$event][$i] || $place[$event][$i]=="") $place[$event][$i]=$i+1; 
            echo "<div id=\"line".$i."\" style=\"";
	    $previ=$i-1;
	    if($tie[$event][$previ]!="yes") 	//cannot have additional qualifiers past $max unless there was a tie with the $max'th performer
	    {
		echo "display:none;";
	        $place[$event][$i]=""; $sch[$event][$i]="Choose School"; $stud[$event][$i]="Choose Student"; $tie[$event][$i]="";
	    }
            if($i%2>0) echo "background-color:#e0e0e0;";
	    echo "\"><table cellspacing=0 cellpadding=3 width=\"100%\"><tr align=center><th width=\"50px\"><div class=alert style=\"text-align:center;margin:3px;\" id=\"placediv".$i."\">".$place[$event][$i]."</div></th>";            
	    echo "<input type=hidden name=\"place[$event][$i]\" value=\"".$place[$event][$i]."\" id=\"place".$i."\">";
            echo "<td width=\"225px\"><select class=small id=\"school".$i."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$i;\"><option>Choose School</option>";
            for($j=0;$j<count($tr_sid);$j++)
            {
                echo "<option value=\"".$tr_sid[$j]."\"";
                if($sch[$event][$i]==$tr_sid[$j]) echo " selected";
                echo ">$tr_sch[$j]</option>";
            }
            echo "</select></td><td width=\"225px\"><select class=small id=\"student".$i."\" name=\"stud[$event][$i]\"><option>Choose Student</option>";
            if($sch[$event][$i]!="Choose School")            //get students on TR elig list for selected school
            {
               $sql2="SELECT * FROM $db1.trbschool WHERE sid='".$sch[$event][$i]."'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $sql="SELECT DISTINCT t2.id,t2.last,t2.first,t2.middle,t2.semesters FROM $db1.eligibility AS t2, $db1.headers AS t3 WHERE t2.school=t3.school AND tr='x' AND gender='M' AND (t3.id='$row2[mainsch]'";
               if($row2[othersch1]) $sql.=" OR t3.id='$row2[othersch1]'";
               if($row2[othersch2]) $sql.=" OR t3.id='$row2[othersch2]'";
               if($row2[othersch3]) $sql.=" OR t3.id='$row2[othersch3]'";
               $sql.=") ORDER BY t2.school,t2.last,t2.first";
               $result=mysql_query($sql);
               while($row=mysql_fetch_array($result))
               {
                  $id=$row[id];
                  $name=$row[last].", ".$row[first]." ".$row[middle];
                  echo "<option value=\"$id\"";
                  if($stud[$event][$i]==$id) echo " selected";
                  echo ">$name (".GetYear($row[semesters]).")</option>";
               }
            }//end if school!="Choose School"
            echo "</select></td>";
            echo "<td><input type=text name=\"perf[$event][$i]\" size=8 value=\"".$perf[$event][$i]."\"></td></tr>";
            //ASK IF THERE IS A TIE FOR THIS PLACE
            echo "<tr align=center><td colspan=4><div class=alert style=\"margin:0px;text-align:center;\"><b><i>Did this competitor and the next competitor TIE?&nbsp;&nbsp;</i></b><input type=radio name=\"tie[$event][$i]\" id=\"tie".$i."yes\" value=\"yes\" onClick=\"if(this.checked) UpdatePlaces('$i','yes');\"";            
	    if($tie[$event][$i]=="yes") echo " checked";            
	    echo "> Yes&nbsp;&nbsp;<input type=radio name=\"tie[$event][$i]\" id=\"tie".$i."no\" value=\"no\" onClick=\"if(this.checked) UpdatePlaces('$i','no');\"";            
	    if(!$tie[$event][$i] || $tie[$event][$i]=="no") echo " checked";            
	    echo "> No</div></td></tr></table></div>";
	    $i++;
	 }
      }//end if HJ or PV
      else 
      {
         for($i=0;$i<$max;$i++) //for each place, one row:
         {
            $schools=""; $co_op=0;
            $thisplace=$i+1;
            if(!preg_match("/Meter/",$treventslong[$eventnum]) && !preg_match("/Extra/",$treventslong[$eventnum]) && !preg_match("/Team/",$treventslong[$eventnum]))
            {
               if(trim($curplace[$i])!="")
	          $thisplace=$curplace[$i];
               echo "<tr align=center><th><input type=text size=1 name=\"places[$event][$i]\" value=\"$thisplace\"></th>";
            }
            else
               echo "<tr valign=top align=center><th><input type=hidden name=\"places[$event][$i]\" value=\"$thisplace\">$thisplace</th>";
            echo "<td valign=top>";
            if($event=="400r" || $event=="1600r" || $event=="3200r")  //relays
               echo "<select class=small id=\"school".$i."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$i;Team2.currentDuplicateStuds=4;\">";
            else
               echo "<select class=small id=\"school".$i."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$i;\">";
            echo "<option>Choose School";
            for($j=0;$j<count($tr_sid);$j++)
            {
               echo "<option value=\"".$tr_sid[$j]."\"";
               if($sch[$event][$i]==$tr_sid[$j]) echo " selected";
               echo ">$tr_sch[$j]</option>";
            } 
            echo "</select></td>";
            //get students on tr elig list for selected school
            if($sch[$event][$i]!="Choose School")
            {
               $sql2="SELECT * FROM $db1.trbschool WHERE sid='".$sch[$event][$i]."'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $sql="SELECT DISTINCT t2.id,t2.last,t2.first,t2.middle,t2.semesters FROM $db1.eligibility AS t2, $db1.headers AS t3 WHERE t2.school=t3.school AND tr='x' AND gender='M' AND (t3.id='$row2[mainsch]'"; 
               if($row2[othersch1]) $sql.=" OR t3.id='$row2[othersch1]'";
               if($row2[othersch2]) $sql.=" OR t3.id='$row2[othersch2]'";
               if($row2[othersch3]) $sql.=" OR t3.id='$row2[othersch3]'";
               $sql.=") ORDER BY t2.school,t2.last,t2.first";
               $result=mysql_query($sql);
               $ix=0;
               while($row=mysql_fetch_array($result))
               {
                  $tr_studs[0][$ix]=$row[0];
                  $tr_studs[1][$ix]=$row[1];
                  $tr_studs[2][$ix]=$row[2];
                  $tr_studs[3][$ix]=$row[3];
                  $tr_studs[4][$ix]=GetYear($row[4]);
                  $ix++;
               } 
               $numstuds=$ix;
            }//end if school!=Choose School
            else $numstuds=0;
            echo "<td align=left>";
            if($event=="400r" || $event=="1600r" || $event=="3200r")  //relays
            {
               echo "<table width=90%><tr align=left><td>";
               $sql="SELECT * FROM $db1.tr_state_relays_b WHERE district='$distid' AND relay='$event' ORDER BY place";
               $result=mysql_query($sql);
               $ix=0;
               while($row=mysql_fetch_array($result))
               {
                  $temp=$row[5];
                  $temp=split(",",$temp);
	          $temp2=$row[6];
	          $temp2=split(",",$temp2);
                  for($k=0;$k<count($temp);$k++)
	          {
	             if(!$relay_stud[$event][$ix][$k] || $relay_stud[$event][$ix][$k]=="Choose Student")
	                $relay_stud[$event][$ix][$k]=$temp[$k];
                  } 
	          $ix++;
               }
               $num=4;
               for($k=0;$k<$num;$k++)
               {
                  echo "<select class=small id=\"student".$i.$k."\" name=\"relay_stud[$event][$i][$k]\">";
                  echo "<option>Choose Student";
	          for($l=0;$l<$numstuds;$l++)
                  { 
	             $id=$tr_studs[0][$l]; 
	             $name=$tr_studs[1][$l].", ".$tr_studs[2][$l]." ".$tr_studs[3][$l];
                     echo "<option value=$id";
                     if($relay_stud[$event][$i][$k]==$id) echo " selected";
                     echo ">$name (".$tr_studs[4][$l].")";
                  }
                  echo "</select><br>";
               }
               echo "</td></tr></table>";
            }//end if relay
            else
            {  
               echo "<select class=small id=\"student".$i."\" name=\"stud[$event][$i]\">";
               echo "<option>Choose Student";
               for($l=0;$l<$numstuds;$l++)
               {
                  $id=$tr_studs[0][$l];
	          $name=$tr_studs[1][$l].", ".$tr_studs[2][$l]." ".$tr_studs[3][$l];
                  echo "<option value=$id";
	          if($stud[$event][$i]==$id) echo " selected";
                  echo ">$name (".$tr_studs[4][$l].")";
               }
               echo "</select>";
            }
            $cur_perf=$perf[$event][$i]; 
            if(($class=="B" || $class=="A") && preg_match("/Meter/",$treventslong[$eventnum]))
            {
               $cur_perf1=$perf1[$event][$i];
               $cur_perf2=$perf2[$event][$i];
               echo "</td><td><input type=text name=\"perf1[$event][$i]\" size=8 value=\"$cur_perf1\"></td><td><input type=text name=\"perf2[$event][$i]\" size=8 value=\"$cur_perf2\"></td></tr>";
            }
            else
               echo "</td><td><input type=text name=\"perf[$event][$i]\" size=8 value=\"$cur_perf\"></td></tr>";
         }//end for each place
      }//end if not HJ or PV
   }//end if event chosen
   echo "</table>";
}//end if not teamscores or extraqual
if($event=="pv" || $event=="hj" || $event=="lj" || $event=="sp" || $event=="d" || $event=="tj")
{
   //Extra field event qualifiers
   echo "<table cellspacing=0 cellpadding=3 style=\"border:#808080 1px solid;width:100%;\" frame=\"all\" rules=\"all\">
<caption><h3 style=\"color:red;\"><u>Extra ".strtoupper($treventslong[$eventnum])." Qualifiers:</u></h3>";
   echo "<ul><li class=notbold>Any student who is <b><u>NOT</u></b> listed above, in the top places for <b><u>".$treventslong[$eventnum]."</b></u>, but who met the qualifying mark of <u><b>$standard</b></u> at the District Meet, should be entered below as an \"Extra ".$treventslong[$eventnum]." Qualifier.\"</li>";
   echo "<li class=notbold>Select the&nbsp;<b>school</b> and the&nbsp;<b>student's name</b>, and enter the&nbsp;<b>place</b> and the&nbsp;<b>performance</b>, in feet and inches.</li>";
   echo "</ul></caption>";
   echo "<tr align=center><th>School</th><th>Name (Grade)</th><th>Place</th><th>Feet</th><th>Inches</th></tr>";
   $i2=$i; $i=0; 
   $sql="SELECT * FROM $db1.tr_state_extra_b WHERE district='$distid' AND eventnum='$event'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center><td><input type=hidden name=\"extraid[$i]\" value=\"$row[0]\">
		<input type=hidden name=\"extraevent[$i]\" value=\"$row[eventnum]\">";
      if(!$extrasch[$i] || $extrasch[$i]=="Choose School") $extrasch[$i]=$row[3];
      echo "<select class=small id=\"school".$i2."\" name=\"extrasch[$i]\" onMouseDown=\"Team2.currentPlace=$i2;\">";
      echo "<option>Choose School</option>";
      for($j=0;$j<count($tr_sid);$j++)
      {
         echo "<option value=\"$tr_sid[$j]\"";
         if($tr_sid[$j]==$extrasch[$i]) echo " selected";
         echo ">$tr_sch[$j]</option>";
      }
      echo "</select></td>";
      if(!$extrastud[$i] || $extrastud[$i]=="Choose Student") $extrastud[$i]=$row[4];
      echo "<td><select class=small id=\"student".$i2."\" name=\"extrastud[$i]\" onMouseDown=\"Team2.currentPlace=$i2;\">";
      echo "<option>Choose Student</option>";
      $sql2="SELECT * FROM $db1.trbschool WHERE sid='$extrasch[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $sql3="SELECT DISTINCT t2.id,t2.last,t2.first,t2.middle,t2.semesters,t2.school FROM $db1.eligibility AS t2, $db1.headers AS t3 WHERE t2.school=t3.school AND tr='x' AND gender='M' AND (t3.id='$row2[mainsch]'";
      if($row2[othersch1]) $sql3.=" OR t3.id='$row2[othersch1]'";
      if($row2[othersch2]) $sql3.=" OR t3.id='$row2[othersch2]'";
      if($row2[othersch3]) $sql3.=" OR t3.id='$row2[othersch3]'";
      $sql3.=") ORDER BY t2.school,t2.last,t2.first";
      $result3=mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
         echo "<option value=\"$row3[0]\"";
         if($extrastud[$i]==$row3[0]) echo " selected";
         echo ">$row3[1], $row3[2] $row3[3] (".GetYear($row3[4]).")</option>";
      }
      echo "</select></td>";
      if(!$extraplace[$i] || $extraplace[$i]=="") $extraplace[$i]=$row[5];
      echo "<td><input type=text name=\"extraplace[$i]\" value=\"$extraplace[$i]\" size=2></td>";
      if(!$extraperf1[$i] || $extraperf1[$i]=="") $extraperf1[$i]=$row[6];
      echo "<td><input type=text name=\"extraperf1[$i]\" value=\"$extraperf1[$i]\" size=2></td>";
      if(!$extraperf2[$i] || $extraperf2[$i]=="") $extraperf2[$i]=$row[7];
      echo "<td><input type=text name=\"extraperf2[$i]\" value=\"$extraperf2[$i]\" size=2></td>";
      echo "</tr>";
      $i++; $i2++;
   }
   while($i<10)
   {
      echo "<tr align=left><td><input type=hidden name=\"extraid[$i]\" value=\"0\">
	<input type=hidden name=\"extraevent[$i]\" value=\"$event\">";
      echo "<select class=small id=\"school".$i2."\" name=\"extrasch[$i]\" onMouseDown=\"Team2.currentPlace=$i2;\"><option>Choose School</option>";
      for($j=0;$j<count($tr_sch);$j++)
      { 
         echo "<option value=\"$tr_sid[$j]\"";
         echo ">$tr_sch[$j]</option>";
      } 
      echo "</select></td>";
      echo "<td><select class=small id=\"student".$i2."\" name=\"extrastud[$i]\" onMouseDown=\"Team2.currentPlace=$i2;\"><option>Choose Student</option>";
      $sql2="SELECT * FROM $db1.trbschool WHERE sid='$extrasch[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $sql="SELECT DISTINCT t2.id,t2.last,t2.first,t2.middle,t2.semesters,t2.school FROM $db1.eligibility AS t2, $db1.headers AS t3 WHERE t2.school=t3.school AND tr='x' AND gender='M' AND (t3.id='$row2[mainsch]'";
      if($row2[othersch1]) $sql.=" OR t3.id='$row2[othersch1]'";
      if($row2[othersch2]) $sql.=" OR t3.id='$row2[othersch2]'";
      if($row2[othersch3]) $sql.=" OR t3.id='$row2[othersch3]'";
      $sql.=") ORDER BY t2.school,t2.last,t2.first";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=$row[0]";
         echo ">$row[1], $row[2] $row[3] (".GetYear($row[4]).")";
      }
      echo "</select></td>";
      echo "<td><input type=text name=\"extraplace[$i]\" size=2></td>";
      echo "<td><input type=text name=\"extraperf1[$i]\" size=2></td>";
      echo "<td><input type=text name=\"extraperf2[$i]\" size=2></td>";
      echo "</tr>";
      $i++; $i2++;
   }//end while i<10
   echo "</table>";
}//end if extraqual
else if($event=="teamscores")
{
   //text box for team scores:
   $sql="SELECT teamscores,teams,indys FROM $db1.tr_state_dist_b WHERE dist='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $teamscores=split("<br>",$row[0]);
   $teams=$row[1]; $indys=$row[2];
   if($teams=='') $teams=count($tr_sch);
   echo "<td><table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=1>
	<caption><b>Team Scores:</b>";
   echo "<ul><li class=notbold>Please enter the team scores for this district below.</li>";
   echo "<li class=notbold>For each place, select the&nbsp;<b>school</b> and enter that team's&nbsp;<b>points</b>.</li>";
   echo "<li class=notbold>Then enter the&nbsp;<b>number of teams</b> that participated in this district and the total&nbsp;<b>number of individuals</b> that were entered in this district.</li>";
   echo "<li class=notbold><b>Don't forget to&nbsp;<font style=\"color:red\">SAVE</font> (click on \"Save & Keep Editing\") before moving on to another event!!!</b></li>";
   echo "<li class=notbold>Click \"Save & View Form\" to view all your events as they will appear on the final form.</li></ul>";
   echo "</caption>";
   echo "<tr align=center><td><b>PLACE</b></td><td><b>TEAM</b></td><td><b>SCORE</b></td></tr>";
   for($i=0;$i<count($teamscores);$i++)
   {
      $thisplace=$i+1;
      echo "<input type=hidden name=\"curplace[$i]\" value=\"$thisplace\">";
      echO "<tr align=left><th align=center>$thisplace</th>";
      $temp=split(", ",$teamscores[$i]);
      echo "<td><select name=\"team[$i]\"><option value=''>Choose Team</option>";
      for($j=0;$j<count($tr_sch);$j++)
      {
	 echo "<option value=\"$tr_sid[$j]\"";
	 if($temp[0]==$tr_sid[$j]) echo " selected";
	 echo ">$tr_sch[$j]</option>";
      }
      echo "</select></td><td><input type=text class=tiny name=\"score[$i]\" value=\"$temp[1]\" size=5>";
      echo "</td></tr>";
   }
   while($i<count($tr_sch))
   {
      $thisplace=$i+1;
      echo "<input type=hidden name=\"curplace[$i] value=\"$thisplace\">";
      echO "<tr align=left><th align=center>$thisplace</th>";
      echo "<td><select name=\"team[$i]\"><option value=''>Choose Team</option>";
      for($j=0;$j<count($tr_sch);$j++)
      {
	 echo "<option value=\"$tr_sid[$j]\">$tr_sch[$j]</option>";
      }
      echo "</select></td><td><input type=text class=tiny name=\"score[$i]\" size=5></td></tr>";
      $i++;
   }
   echo "<tr align=left>
	<th colspan=3>Number of teams participating:&nbsp;&nbsp;<input type=text size=3 name=teams value=\"$teams\"></th></tr>";
   echo "<tr align=left>
	<th colspan=3>Total number of individuals entered:&nbsp;&nbsp;<input type=text size=3 name=indys value=\"$indys\"></th></tr>
	</table>";
}
echo "</td></tr></table>";

if($treventslong[$eventnum]!="") 
{
   //show checkbox for final submission
   echo "<table width=400><tr align=center><td width=50%>";
   echo "<input type=submit name=\"store\" value=\"Save & Keep Editing\"><br>(Click this button to SAVE and continue entering results)</td>";
   echo "<td width=50%><input type=submit name=\"store\" value=\"Save & View Form\"><br>(Click this button to SAVE and view all the results you've entered)";
   echo "</td></tr></table>";
}
echo "</td></tr></table>";
echo "</form>";

?>
<div id="loading" style=\"display:none;\"></div>
<?php
echo $end_html;
?>
