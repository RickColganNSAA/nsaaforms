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

for($i=0;$i<count($act_long);$i++)
{
   if($activity[$i]==$sport)
      $sportname=$act_long[$i];
}

$table=$sport."sched";
$level=GetLevel($session);
//get off name
$sql="SELECT first,last,address,city,state,zip,homeph,cellph,workph FROM officials WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$offname="$row[0] $row[1]";
$address=$row[address];
$cityst="$row[city], $row[state]";
$zip=$row[zip];
$homeph=$row[homeph];
$cellph=$row[cellph];
$workph=$row[workph];

$obsid=GetObsID($session);

echo $init_html;
echo GetHeader($session);
echo "<br><a class=small href=\"javascript:history.go(-1)\">Back to Search Results</a>&nbsp;&nbsp;";
echo "<a class=small href=\"welcome.php?session=$session&sport=$sport\">Start a New Search</a><br><br>";
$curryear=date("Y",time());
echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3><caption><b>$curryear $sportname Officiating Schedule for $offname:</b>";
if($sport=="fb")
{
   echo "<br><font style=\"font-size:9pt\">";
   $sql2="SELECT * FROM fbapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $referee=GetOffName($row2[referee]);
   $umpire=GetOffName($row2[umpire]);
   $linesman=GetOffName($row2[linesman]);
   $linejudge=GetOffName($row2[linejudge]);
   $backjudge=GetOffName($row2[backjudge]);
   $otheroff="$referee, $umpire, $linesman, $linejudge, $backjudge";
   echo "(Main Crew: $otheroff)";
   echo "</font><br>";
}
echo "<table><tr align=left><td><br><u>Contact Info:</u></td></tr>";
echo "<tr align=left><td>$offname<br>$address<br>$cityst $zip<br>";
if(trim($homeph)!="") echo "(H) (".substr($homeph,0,3).")".substr($homeph,3,3)."-".substr($homeph,6,4)."<br>";
if(trim($workph)!="") echo "(W) (".substr($workph,0,3).")".substr($workph,3,3)."-".substr($workph,6,4)."<br>";
if(trim($cellph)!="") echo "(C) (".substr($cellph,0,3).")".substr($cellph,3,3)."-".substr($cellph,6,4)."<br>";
echo "</td></tr></table>";
echo "<br>";
echO "</caption>";
echo "<tr align=center><th class=smaller>Evaluation</th><th class=smaller>Date</th><th class=smaller>Location</th><th class=smaller>";
if($sport=="tr") echo "Meet Name";
else echo "Schools (list both)<br>All Schools for Tournament";
echo "</th><th class=smaller>Local time of<br>VARSITY MATCH</th>";
if($sport=='so') echo "<td><b>Position(s)</b></td>";
echo "<th class=smaller>";

//if FB, get crew members:
if($sport=="fb") echo "Substitutes";
else echo "Other Officials";
echo "</th></tr>";

$sql="SELECT * FROM $table WHERE offid='$offid' ORDER BY offdate";
$result=mysql_query($sql);
$ix=0;
$obstable=$sport."observe";
while($row=mysql_fetch_array($result))
{
   $sport2=$sport;
   $curdate=ereg_replace("-","/",$row[offdate]);
   $curdate=substr($curdate,5,5)."/".substr($curdate,0,4);
   echo "<tr align=left";
   if($row[offdate]==$highlight) echo " bgcolor='yellow'";
   echo ">";
   echo "<input type=hidden name=\"schedid[$ix]\" value=\"$row[id]\">";
   echo "<td width=300>";
   //check if this observer has observed this official at this game yet
   $sql2="SELECT * FROM $obstable WHERE offid='$offid' AND obsid='$obsid' AND gameid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $saved=0; $submitted=0;
   if(mysql_num_rows($result2)==0) $eval="Fill out evaluation";
   else if($row2[dateeval]=='') 
   {
      $eval="Edit your evaluation";
      $saved=1;
   }
   else 
   {
      $eval="View your evaluation";
      $submitted=1;
   }
   echo "<a class=small href=\"".$sport2."observe.php?session=$session&sport=$sport&offid=$offid&game=$row[id]";
   if($sport=='bb' && ereg("Fill",$eval) && ($row[crewct]=='2' || $row[crewct]=='3'))	//check if 2-person or 3-person crew
      echo "&crew=$row[crewct]";
   echo "\" target=new>$eval</a>";
   if($saved==1)
      echo "<br>This evaluation has NOT been submitted to the NSAA yet.";
   if($submitted==1)
      echo "<br>This evaluation was submitted to the NSAA on ".date("m/d/Y",$row2[dateeval]).".";
   echo "</td><td>$curdate</td>";
   echo "<td>$row[location]</td>";
   echo "<td>";
   if($sport=='tr') echo $row[meetname];
   else echo $row[schools];
   echo "&nbsp;</td>";
   if($row[gametime]!="TBA")
   {
      $time=split("-",$row[gametime]);
      $curtime=$time[0].":".$time[1]." ".$time[2];
   }
   else
   {
      $curtime="TBA";
   }
   echo "<td>$curtime</td>";
   if($sport=='so') echo "<td>$row[positions]</td>";
   echo "<td>$row[otheroff]&nbsp;";
   if($sport=='bb') 
   {
      if($row[crewct]>0) echo "<br>($row[crewct]-Person Crew)";
   }
   echo "</td></tr>";
   $ix++;
}
echo "</table>";

echo $end_html;
?>

