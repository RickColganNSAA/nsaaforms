<?php
/*******************************
obssearch.php
Search Observers Use to Find Officials he/she is Evaluating
Created 9/10/09 (upgrade from obs_search.php)
Author: Ann Gaffigan
********************************/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);


if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$sport || $sport=='')
{
   echo "ERROR: No Sport Entered.";
   exit();
}

$obsid=GetObsID($session);

echo $init_html;
echo GetHeader($session);

echo "<br><table class=nine cellspacing=0 cellpadding=4 frames=all rules=all style=\"border:#808080 1px solid;\"><caption><b>".GetSportName($sport)." Search Results:</b><br>";
echo "<div class=alert style=\"width:500px;\"><b>Modify Search:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a class=small href=\"welcome.php?session=$session&obssport=$sport\">Start a New Search</a>";
   echo "<form method=post action=\"obssearch.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=sort value=\"$sort\">";
   echo "<input type=hidden name=sport value=\"$sport\">";   
   echo "<table><tr align=left><td><b>Last Name:</b> (starts with)</td>";
   echo "<td><input type=text class=tiny size=20 name=last value=\"$last\"></td></tr>";
   echo "<tr align=left><td><b>First Name:</b> (starts with)</td>";
   echo "<td><input type=text class=tiny size=20 name=first value=\"$first\"></td></tr>";
   echo "<tr align=left><td><b>City:</b> (where official resides)</td>";
   echo "<td><select class=small name=city><option>~</option>";
   $sql2="SELECT DISTINCT city FROM officials WHERE city!='' ORDER BY city";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<option";
	 if($city==$row2[city]) echo " selected";
      echo ">$row2[city]</option>";
   }
   echo "</select></td></tr>";
   if($sport=='vb' || $sport=='so')
   {
   echo "<tr align=left><td colspan=2><input type=checkbox ";
   if($applied=='x') echo "checked ";
   echo "name=\"applied\" value=\"x\"> <b>Only show me officials who have APPLIED TO OFFICIATE POSTSEASON</b></td></tr>";
   }
   if($obssport=="wr" || $obssport=="vb") $game="Match";
   else if($obssport=="sw" || $obssport=="di" || $obssport=="tr") $game="Meet";
   else $game="Game";
   echo "<tr align=left><td><b>Date of $game:</b></td>";
   echo "<td><select name=\"month\"><option value='00'>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($month==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"day\"><option value='00'>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($day==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"year\">";
   $year1=date("Y")-1; $year2=date("Y"); $year3=date("Y")+1;
   for($i=$year1;$i<=$year3;$i++)
   {
      echo "<option";
      if($i==$year) echo " selected";
      echo ">$i</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td colspan=2><input type=radio name=\"showall\" value=\"yes\"";
   if(!$showall || $showall=="yes") echo " checked";
   echo "> Show me <b><u>GAMES</b></u> for all officials matching this criteria.<br><input type=radio name=\"showall\" value=\"no\"";
   if($sport!='bb') echo " disabled"; 
   if($showall=="no") echo " checked";
   echo "> <b><i>Basketball ONLY:</b></i> Show me officials matching this criteria, with links for the <u><b>CLINIC OBSERVATION FORM</b></u> and a <b><u>PRINTABLE SCHEDULE</u></b>.</td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=search value=\"Search\"></td></tr>";
echo "</table></form></div>";

//GET SEARCH QUERY (based on criteria entered on welcome.php)
$offtable=$sport."off";
if($showall=='no')
   $sql="SELECT DISTINCT t1.*";
else
{
   $sql="SELECT DISTINCT t1.*,t3.id AS gameid,t3.offdate,t3.location,t3.schools,t3.gametime,t3.otheroff";
   if($sport=='bb') $sql.=",t3.girls";
}
$sql.=" FROM officials AS t1, $offtable AS t2";
if($showall!='no') $sql.=", ".$sport."sched AS t3";
if($applied=='x') $sql.=",".$sport."apply AS t4";
$sql.=" WHERE t1.id=t2.offid ";
if($showall!='no') $sql.="AND t2.offid=t3.offid ";
if($applied=='x') $sql.="AND t2.offid=t4.offid ";
$sql.="AND t1.$sport='x' AND t2.payment!='' AND ";
if($last!="")
   $sql.="t1.last LIKE '$last%' AND ";
if($first!="")
   $sql.="t1.first LIKE '$first%' AND ";
if($city!="~")
   $sql.="t1.city='$city' AND ";
if($month!='00' && $day!='00' && $year!='0000' && $showall!='no')
   $sql.="t3.offdate='".$year."-".$month."-".$day."' AND ";
$sql=substr($sql,0,strlen($sql)-5);
if(!$sort) $sort="t1.last,t1.first";
$sql.=" ORDER BY $sort";
//echo $sql;
$result=mysql_query($sql);

if($showall!='no')
   echo "<p style=\"text-align:left;margin:5px;font-size:12px;\"><i>Click on <b>Official</b> to sort by Official's Name or <b>Date</b> to sort by contest date.</i></p></caption>";
else echo "<br>";
echo "<tr align=center>";
echo "<td><a href=\"obssearch.php?session=$session&last=$last&first=$first&applied=$applied&city=$city&month=$month&day=$day&year=$year&sport=$sport&sort=t1.last,t1.first\">Official</a><br>(Click official's name for Printable Schedule)</td>";
echo "<td><b>Applied<br>to Officiate<br>Post-<br>season</b></td>";
if($showall!='no')
{
if($sport=='bb')
   echo "<td><b>Girls or Boys</b></td>";
echo "<td><a href=\"obssearch.php?session=$session&last=$last&first=$first&applied=$applied&city=$city&month=$month&day=$day&year=$year&sport=$sport&sort=t3.offdate\">Date</a></td>";
echo "<td><b>Time</b></td><td><b>Location</b></td><td><b>Schools</b></td><td><b>Other Officials</b></td><td><b>Observation</b></td>";
}
else echo "<td><b>CLINIC OBSERVATION</b></td>";
echo "</tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor='#e0e0e0'";
   echo ">";
   echo "<td><a href=\"schedule.php?sport=$sport&session=$session&givenoffid=$row[id]\" target=\"_blank\">$row[first] $row[last]</a></td>";
   $sql2="SELECT * FROM ".$sport."apply WHERE offid='$row[id]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0) echo "<td align=center>YES</td>";
   else echo "<td align=center>NO</td>";
   if($showall!='no')
   {
   if($sport=='bb')
   {
      if($row[girls]=='x') echo "<td align=center>GIRLS</td>";
      else echo "<td align=center>BOYS</td>";
   }
   $date=split("-",$row[offdate]);
   echo "<td>$date[1]/$date[2]/$date[0]</td>";
   $time=split("-",$row[gametime]);
   echo "<td>$time[0]:$time[1] $time[2]</td><td>$row[location]</td><td width='200px'>$row[schools]</td><td width='150px'>$row[otheroff]</td>";
   echo "<td width='250px'>";
   //check if this observer has observed this official at this game yet
   $sql2="SELECT * FROM ".$sport."observe WHERE offid='$row[id]' AND obsid='$obsid' AND gameid='$row[gameid]'";
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
   echo "<a href=\"".$sport."observe.php?session=$session&sport=$sport&offid=$row[id]&game=$row[gameid]";
   if($sport=='bb' && ereg("Fill",$eval) && ($row[crewct]=='2' || $row[crewct]=='3'))   //check if 2-person or 3-person crew
      echo "&crew=$row[crewct]";
   echo "\" target=new>$eval</a>";
    if($submitted==1)
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$sport."observe.php?session=$session&sport=$sport&offid=$row[id]&game=$row[gameid]&export=yes\">Export</a>"; 
   
   if($saved==1)
      echo "<br>This evaluation has NOT been submitted to the NSAA yet.";
   if($submitted==1)
      echo "<br>This evaluation was submitted to the NSAA on ".date("m/d/Y",$row2[dateeval]).".";
   echo "</td>";
   }
   else	//CLINIC OBSERVATION
   {
      echo "<td width='250px'>";
      //check if this observer has observed this official at this game yet
      $sql2="SELECT * FROM ".$sport."clinicobserve WHERE offid='$row[id]' AND obsid='$obsid'";
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
      echo "<a href=\"".$sport."clinicobserve.php?session=$session&sport=$sport&offid=$row[id]";
      if($sport=='bb' && ereg("Fill",$eval) && ($row[crewct]=='2' || $row[crewct]=='3'))   //check if 2-person or 3-person crew
         echo "&crew=$row[crewct]";
      echo "\" target=new>$eval</a>";
	   if($submitted==1)
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$sport."observe.php?session=$session&sport=$sport&offid=$row[id]&game=$row[gameid]&export=yes\">Export</a>"; 
   
      if($saved==1)
         echo "<br>This evaluation has NOT been submitted to the NSAA yet.";
      if($submitted==1)
         echo "<br>This evaluation was submitted to the NSAA on ".date("m/d/Y",$row2[dateeval]).".";
      echo "</td>";
   }
   echo "</tr>";
   $ix++;
}
echo "</table>";
echo "<br><br><a class=small href=\"welcome.php?session=$session&sport=$sport\">Start Over</a>";
?>
