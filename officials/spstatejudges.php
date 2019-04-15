<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$contracts="spcontracts";
$districts="spdistricts";

//GET SP LODGING DATES
   $sql2="SELECT * FROM sptourndates WHERE lodgingdate='x' AND label LIKE '%State%' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $splodging=array(); $splodging_sm=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $splodging[$i]=date("l, F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $splodging_sm[$i]=$date[1]."/".$date[2];
      $i++;
   }

echo $init_html;
echo GetHeaderJ($session);
echo "<br>";
echo "<div id='lodgingdiv' name='lodgingdiv' style='display:none;margin:10px;width:400px;' class=alert></div>";
echo "<div id='lodgingdiv1' name='lodgingdiv' style='display:none;margin:10px;width:400px;' class=alert></div>";
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption align=center><b>Speech Judges Assigned to State:</b><br><br>";
//LINKS FOR DAY 1, DAY 2 and ALL
//Get the days:
$sql="SELECT DISTINCT dates FROM $districts WHERE type='State' ORDER BY dates";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if(!$statedate || $statedate=='') $statedate=$row[dates];
   if($statedate==$row[dates])	//THIS DAY IS SHOWING NOW
      echo "<b>".strtoupper(date("l",strtotime($row[dates])))."</b>";
   else
      echo "<a href=\"spstatejudges.php?session=$session&sort=$sort&statedate=$row[dates]\">".date("l",strtotime($row[dates]))."</a>";
   echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"; 
}
if($statedate=="both")
   echo "<b>BOTH DAYS</b>";
else
   echo "<a href=\"spstatejudges.php?session=$session&sort=$sort&statedate=both\">Both Days</a>";
echo "<br><br>";
echo "</caption>";
echo "<tr align=center>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session\">Judge</a></td>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=dates\">Day</a></td>";
echo "<th rowspan=2 class=small>State Room<br>Assignments</th>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=post\">Posted</a></td>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=accept\">Accept</a></td>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=confirm\">NSAA-Confirm</a></td>";
for($i=0;$i<count($prefs_sm);$i++)
{
   echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=$prefs_sm[$i]\">$prefs_lg2[$i]</a></td>";
}
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=schrep\">School</a></td>";
echo "<th class=small colspan=2>Conflicts</th>";
echo "<th colspan=2 class=small>Lodging</th>";
echo "</tr>";
echo "<tr align=center>";
echo "<td><a class=tiny href=\"spstatejudges.php?session=$session&sort=classconflict\">Class</a></td>";
echo "<td><a class=tiny href=\"spstatejudges.php?session=$session&sort=schconflict\">School</a></td>";
$csv1="\"Official ID\",\"Name\",";
$csv3="\"Judge\",\"Day\",\"State Room Assignments\",\"Posted\",\"Accept\",\"NSAA-Confirm	\",\"Hum Prose	\",\"Ser Prose\",\"Poetry\",\"Pers Speak\",\"Entertain Speak\",\"Extemp Speak\",\"Public Speak\",\"Drama\",\"Duet\",\"School\",\"Class\",\"School\",";
$csv="\"Day\",\"Judge\",\"Hum Prose	\",\"Ser Prose\",\"Poetry\",\"Pers Speak\",\"Entertain Speak\",\"Extemp Speak\",\"Public Speak\",\"Drama\",\"Duet\",\"Conflict Class\",\"Conflict School\",";
for($i=0;$i<count($splodging);$i++)
{
   $num=$i+1;
   echo "<td><a class=tiny href=\"spstatejudges.php?session=$session&sort=date".$num."\">$splodging_sm[$i]</a></td>";
   $csv3.="\"".$splodging_sm[$i]."\",";
   $csv1.="\"".$splodging_sm[$i]."\",";
}
$csv.="\r\n";
$csv1.="\r\n";
echo "</tr>";
$sql="SELECT t1.first,t1.middle,t1.last,t2.*,t3.dates FROM judges AS t1, $contracts AS t2, $districts AS t3 WHERE t1.id=t2.offid AND t2.distid=t3.id AND t3.type='State'";
if($statedate && $statedate!='' && $statedate!='both')
   $sql.=" AND t3.dates='$statedate'";
if($sort=='dates')
   $sql.=" ORDER BY t3.$sort ASC,";
else if($sort=='post' || $sort=='confirm' || $sort=='accept' || ereg("date",$sort))
   $sql.=" ORDER BY t2.$sort DESC,";
else if($sort=='schrep' || $sort=='classconflict' || $sort=='schconflict')
   $sql.=" ORDER BY t2.$sort ASC,";
else if($sort && $sort!='')
   $sql.=" ORDER BY t2.$sort ASC,";
else
   $sql.=" ORDER BY";
$sql.=" t1.last,t1.first,t1.middle,t3.dates";
$result=mysql_query($sql);
$judges=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   //Get any current ROOM ASSIGNMENTS for this judge on this day
   $sql2="SELECT t1.*,t2.room,TIME_FORMAT(t1.time,'%h:%i%p') AS curtime FROM spstaterounds AS t1, spstaterooms AS t2, spstateassign AS t3 WHERE t1.id=t2.roundid AND t2.id=t3.roomid AND t3.offid='$row[offid]' ORDER BY t1.rounddate,t1.round,t1.class,t1.event";
   $result2=mysql_query($sql2);
   $roomass="";
   while($row2=mysql_fetch_array($result2))
   {
      if($row2['rounddate']==$row[dates])
         $roomass.="$row2[curtime], $row2[room], $row2[class], ".GetEventAbbrev($row2[event])."<br>";
   }
   if($roomass!='')
      $roomass=substr($roomass,0,strlen($roomass)-4);
   else
      //$roomass="&nbsp;";
      $roomass="";
   $sql3="SELECT city FROM judges WHERE id='$row[offid]'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);   
   $judges[name][$ix]=GetJudgeName($row[offid])."<br>($row3[0])";
   for($i=0;$i<count($prefs_sm);$i++)
   {
      $judges[$prefs_sm[$i]][$ix]=$row[$prefs_sm[$i]];
   }
   $judges[schrep][$ix]=$row[schrep];
   $judges[classconflict][$ix]=$row[classconflict];
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
   $schconf=substr($schconf,0,strlen($schconf)-2);
   $judges[schconflict][$ix]=$schconf; 
   echo "<tr align=left>";
   echo "<td>".$judges[name][$ix]."</td>";
   
   echo "<td>".date("D",strtotime($row[dates]))."</td>";
   $csv.="\"".date("D",strtotime($row[dates]))."\","; 
   $csv.="\"".GetJudgeName($row[offid])."\","; 
   echo "<td width=200>".$roomass."</td>";
   $csv3.="\"".$roomass."\","; 
   $judges[roomass][$ix]=$roomass;
   if($row[post]=='y') {echo "<td align=center>X</td>";$csv3.="\"X\","; }
   else echo "<td>&nbsp;</td>";
   if($row[accept]=='y') {echo "<td align=center>YES</td>"; $csv3.="\"YES\","; }
   else if($row[accept]=='n') {echo "<td align=center>No</td>"; $csv3.="\"NO\","; }
   else {echo "<td>?</td>"; $csv.="\"?\","; $csv.="\r\n";}
   if($row[confirm]=='y') {echo "<td align=center>YES</td>"; $csv3.="\"YES\","; }
   else if($row[confirm]=='n') {echo "<td align=center>No</td>"; $csv3.="\"NO\","; }
   else {echo "<td align=center>?</td>"; $csv.="\"?\","; $csv.="\r\n";}
   for($i=0;$i<count($prefs_sm);$i++)
   {
      echo "<td align=center>".$judges[$prefs_sm[$i]][$ix]."</td>"; $csv.="\"".$judges[$prefs_sm[$i]][$ix]."\","; 
   }
   echo "<td>".$judges[schrep][$ix]."</td>"; $csv3.="\"".$judges[schrep][$ix]."\","; 
   echo "<td>".$judges[classconflict][$ix]."</td>"; $csv.="\"".$judges[classconflict][$ix]."\","; 
   echo "<td width=150>".$judges[schconflict][$ix]."</td>"; $csv.="\"".$judges[schconflict][$ix]."\","; 
   for($i=0;$i<count($splodging);$i++)
   {
      echo "<td align=center>";
      $num=$i+1;
      $field="date".$num;
      if($row[$field]=='x') $judges[lodging][$ix][$i]="X";
      //else $judges[lodging][$ix][$i]="&nbsp;";
      else $judges[lodging][$ix][$i]="";
      echo $judges[lodging][$ix][$i]; $csv3.="\"".$judges[lodging][$ix][$i]."\","; 
   }
   echo "</tr>";
   $csv.="\r\n";
   $ix++;
}

//WRITE TO LODGING EXPORTS, SHOW LINKS IN lodgingdiv
$result2=mysql_query($sql);
while($row2=mysql_fetch_array($result2))
{
   if(!ereg("\"$row2[offid]\",",$csv1) && !ereg("\"".GetJudgeName($row2[offid])."\",",$csv1))
   {
   $csv1.="\"$row2[offid]\",\"".GetJudgeName($row2[offid])."\",";
   for($i=0;$i<count($splodging);$i++)
   {
      $num=$i+1;
      $field="date".$num;
      $csv1.="\"".strtoupper($row2[$field])."\",";
   }
   $csv1.="\r\n";
   }
}
$filename="spstatelodging.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv1);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
$filename1="spstatedetails.csv";
$open1=fopen(citgf_fopen("/home/nsaahome/reports/$filename1"),"w");
fwrite($open1,$csv);
fclose($open1); 
 citgf_makepublic("/home/nsaahome/reports/$filename1");
?>
<script language="javascript">
document.getElementById('lodgingdiv').style.display='';
document.getElementById('lodgingdiv').innerHTML="<a class=small href='reports.php?session=<?php echo $session; ?>&filename=<?php echo $filename; ?>'>Export Judges Needing Lodging</a>";
document.getElementById('lodgingdiv1').style.display='';
document.getElementById('lodgingdiv1').innerHTML="<a class=small href='reports.php?session=<?php echo $session; ?>&filename=<?php echo $filename1; ?>'>Export State Speech Judges for whom NSAA Accepted a Contract</a>";
</script>
<?php

echo "<tr align=center>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session\">Judge</a></td>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=dates\">Day</a></td>";
echo "<th rowspan=2 class=small>State Room<br>Assignments</th>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=post\">Posted</a></td>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=accept\">Accept</a></td>";
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=confirm\">NSAA-<br>Confirm</a></td>";
for($i=0;$i<count($prefs_sm);$i++)
{
   echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=$prefs_sm[$i]\">$prefs_lg2[$i]</a></td>";
}
echo "<td rowspan=2><a class=tiny href=\"spstatejudges.php?session=$session&sort=schrep\">School</a></td>";
echo "<th colspan=2 class=small>Conflicts</th>";
echo "<th colspan=2 class=small>Lodging</th>";
echo "</tr>";
echo "<tr align=center>";
echo "<td><a class=tiny href=\"spstatejudges.php?session=$session&sort=classconflict\">Class</a></td>";
echo "<td><a class=tiny href=\"spstatejudges.php?session=$session&sort=schconflict\">School</a></td>";
for($i=0;$i<count($splodging);$i++)
{
   $num=$i+1;
   echo "<td><a class=tiny href=\"spstatejudges.php?session=$session&sort=date".$num."\">$splodging_sm[$i]</a></td>";
}
echo "</tr>";
echo "</table>";
echo "<br><br><a href=\"jwelcome.php?session=$session\" class=small>Home</a>";
echo $end_html;
?>
