<?php
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
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

$sportname=GetSportName($sport);
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$hostapp="hostapp_".$sport;

if($save)
{
   for($i=0;$i<count($distids);$i++)
   {
      if($checkall=='x') $showdistinfo[$i]="x";
      $sql="UPDATE $districts SET showdistinfo='$showdistinfo[$i]' WHERE id='$distids[$i]'";
      $result=mysql_query($sql);
      if(mysql_error()) echo mysql_error()."<br>";

      //GOLF
      if(preg_match("/go/",$sport)) //CLASS A ONLY THOUGH
      {
	 $sql="UPDATE $districts SET showdistrict='$showdistrict[$i]' WHERE id='$distids[$i]' AND class='A'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html;
if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
else echo GetHeader($session,"contractadmin");

echo "<br><form name=assignform method=post action=\"hostreport.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Sport/Activity</option>";
$sql="SHOW TABLES LIKE '%districts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("districts",$row[0]);
   $curact=$temp[0];
   if((($sport=='pp' || $sport=='sp') && ($curact=='sp' || $curact=='pp')) || (!($sport=='pp' || $sport=='sp')
 && !($curact=='sp' || $curact=='pp')))
   {
      echo "<option value=\"$curact\"";
      if($sport==$curact) echo " selected";
      echo ">".GetSportName($curact)."</option>";
   }
   $contractsports[$ix]=$curact;   
   $ix++;
}
echo "</select>&nbsp;";
echo "<a class=small href=\"hostcontracts.php?session=$session&sport=$sport\">Main Menu</a>&nbsp;&nbsp;";
echo "<a class=small href=\"hostbyhost.php?session=$session&sport=$sport\">View Hosts One at a Time</a><br>";

echo "<br><table cellspacing=0 cellpadding=3 frames=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>$sportname District Host Info & Contract Status:</b><br>";
echo "<a class=small href=\"posthost.php?session=$session&all=1&sport=$sport\">Post Contracts to ALL Assigned Hosts</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"hostexport.php?session=$session&sport=$sport\" target=\"_blank\">Export District Host Information</a>";
if($sport=='pp' || $sport=='sp')
   echo "&nbsp;&nbsp;<a class=small href=\"assignplay2.php?session=$session&sport=$sport\">Go to JUDGES Contracts</a><br>";
if($posted=='yes') echo "<br><font style=\"color:red;font-size:10pt;\"><b>All Contracts have been posted to the assigned hosts.</b></font>";
echo "<br></caption>";
echo "<tr align=center><th class=small>District/<br>Subdistrict</th><td><b>Assigned Host</b></td>";
echo "<th class=small>Posted</th><th class=small>Accept</th><th class=small>Confirm</th>";
if(preg_match("/go/",$sport))	//COLUMN FOR "SHOW DISTRICT" (CLASS A)
   echo "<th class=small>SHOW<br>DISTRICT?<br>(Class A)</th>";
echo "<td><b>Dates</b></td>";
if(IsTimeslotSport($sport))
{
   echo "<td><b>Date/Time Slots<br>for each game</b></td>";
}
echo "<td><b>Site</b></td><td><b>Director/<br>E-mail</b></td>";
if($sport=='go_g' || $sport=='go_b')
{
   $criteria=array("Course","Holes","City");
   $criteria_sm=array("course","holes","location");
}
else if($sport=='ba')
{
   $criteria=array("Lights");
   $criteria_sm=array("lights");
}
else if($sport=='wr')
{
   $criteria=array("Teams","Spectators","Parking","Lockers","Mats");
   $criteria_sm=array("teams","spectators","parking","lockers","mats");
}
else //no specific criteria on app to host
{
   $criteria=array(); $criteria_sm=array();
}
for($i=0;$i<count($criteria);$i++)
{
   echo "<th class=small>$criteria[$i]</th>";
}
if($sport=='sb' || $sport=='ba')
{
   echo "<th class=small>Fields</th>";
   echo "<th class=small>Lighted<br>Fields</th>";
}
echo "<td><b>Schools Assigned</b></td><th class=smaller>Show on<br><a class=small target=\"_blank\" href=\"/distassign.php?session=$session&sport=$sport\">Dist Assign Page</a></b><br><input type=checkbox name=\"checkall\" value='x'>Check ALL</th></tr>";

$sql="SELECT * FROM $districts WHERE type='District' OR type='Subdistrict' ORDER BY class, district";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($sport=='ba' || (preg_match("/bb/",$sport) && $row['class']=="A")) //BASEBALL/CLASS A BASKETBALL - LOOP THROUGH EACH GAME
   {
      $sql2="SELECT * FROM $disttimes WHERE distid='$row[id]' ORDER BY gamenum";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<tr align=left><td align=center><a class=\"small\" href=\"hostbyhost.php?session=$session&sport=$sport&type=$row[type]&distid=$row[id]\">$row[class]-$row[district]</a> #$row2[gamenum]</td>";
         if($row2[hostschool]=="[Click to Choose Host]") $row2[hostschool]="";
         echo "<td><a target=\"_blank\" class=\"small\" href=\"hostcontract.php?session=$session&sport=$sport&distid=$row[id]&disttimesid=$row2[id]\">$row2[hostschool]</a></td>";
         if($row2[post]=='y') echo "<td align=center><b>X</b></td>";
         else echo "<td>&nbsp;</td>";
         if($row2[accept]=='y') echo "<td align=center>Yes</td>";
         else if($row2[accept]=='n') echo "<td align=center>DECLINED</td>";
         else echo "<td align=center>???</td>";
         if($row2[confirm]=='y') echo "<td align=center>Yes</td>";
         else if($row2[confirm]=='n') echo "<td align=center>REJECTED</td>";
         else echo "<td align=center>???</td>";
	 $day=explode("-",$row2[day]);
	 echo "<td>$day[1]/$day[2]</td>";
	 echo "<td>$day[1]/$day[2] at $row2[time]</td>";
         echo "<td>$row2[site]</td><td>$row2[director]<br><a class=small href=\"mailto:$row2[email]\">$row2[email]</a></td>";
         $hostid=$row2[hostid];
         $sql3="SELECT t1.* FROM $db_name.$hostapp AS t1, $db_name.logins AS t2 WHERE t1.school=t2.school AND t2.id='$hostid'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         for($i=0;$i<count($criteria);$i++)
         {
            if($row3[$criteria_sm[$i]]=='y' || $row3[$criteria_sm[$i]]=='x')
               $row3[$criteria_sm[$i]]="X";
            else if($row3[$criteria_sm[$i]]=='n' || $row3[$criteria_sm[$i]]=='')
               $row3[$criteria_sm[$i]]="&nbsp;";
            echo "<td align=center>".$row3[$criteria_sm[$i]]."</td>";
         }
	 $schools=GetSchoolName($row2[sid],$sport)." vs ".GetSchoolName($row2[oppid],$sport);
         echo "<td width=200>$schools</td>";
         echo "<input type=hidden name=\"distids[$ix]\" value=\"$row[id]\">";
         echo "<td align=center><input type=checkbox value='x' name=\"showdistinfo[$ix]\"";
         if($row[showdistinfo]=='x') echo " checked";
         echo "></td>";
         echo "</tr>";
      }	//END FOR EACH GAME IN DISTRICT
   } //END IF CLASS A BASKETBALL
   else
   {
   echo "<tr align=left>";
   echo "<td align=center><a class=\"small\" href=\"hostbyhost.php?session=$session&sport=$sport&type=$row[type]&distid=$row[id]\">$row[class]-$row[district]</a></td>";
   if($row[hostschool]=="[Click to Choose Host]") $row[hostschool]="";
   echo "<td><a target=new class=small href=\"hostcontract.php?session=$session&sport=$sport&distid=$row[id]\">$row[hostschool]</a></td>";
   if($row[post]=='y') echo "<td align=center><b>X</b></td>";
   else echo "<td>&nbsp;</td>";
   if($row[accept]=='y') echo "<td align=center>Yes</td>";
   else if($row[accept]=='n') echo "<td align=center>DECLINED</td>";
   else echo "<td align=center>???</td>";
   if($row[confirm]=='y') echo "<td align=center>Yes</td>";
   else if($row[confirm]=='n') echo "<td align=center>REJECTED</td>";
   else echo "<td align=center>???</td>";
   //COLUMN FOR SHOW DISTRICT CHECKMARK FOR CLASS A GOLF
   if(preg_match("/go/",$sport))
   {
      if($row['class']=="A")
      {
         echo "<td align=center><input type=checkbox name=\"showdistrict[$ix]\" value=\"x\"";
	 if($row[showdistrict]=='x') echo " checked";
         echo "></td>";
      }
      else echo "<td>&nbsp;</td>";
   }
   //DATES
   $temp=split("/",$row[dates]);
   $dates="";
   for($i=0;$i<count($temp);$i++)
   {
      $curday=split("-",$temp[$i]);
      $curday2=mktime(0,0,0,$curday[1],$curday[2],$curday[0]);
      if($temp[$i]=='') $dates.="";
      else $dates.=date("n/j",$curday2).", ";
   }
   $dates=substr($dates,0,strlen($dates)-2);
   echo "<td>$dates</td>";
   if(IsTimeslotSport($sport))
   {
      $sql2="SELECT * FROM $disttimes WHERE distid='$row[id]' ORDER BY gamenum,day,time";
      $result2=mysql_query($sql2);
      $slots="";
      while($row2=mysql_fetch_array($result2))
      {
         $date=split("-",$row2[day]);
         $slots.="$date[1]/$date[2] at ";
         if($row2[time]!='') $slots.=$row2[time].", ";
	 else $slots.="??, ";
      }
      if($slots!='') $slots=substr($slots,0,strlen($slots)-2);
      echo "<td>$slots&nbsp;</td>";
   }
   echo "<td>$row[site]</td>";
   echo "<td>$row[director]<br><a class=small href=\"mailto:$row[email]\">$row[email]</a></td>";
   $hostid=$row[hostid];
   $sql2="SELECT t1.* FROM $db_name.$hostapp AS t1, $db_name.logins AS t2 WHERE t1.school=t2.school AND t2.id='$hostid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   for($i=0;$i<count($criteria);$i++)
   {
      if($row2[$criteria_sm[$i]]=='y' || $row2[$criteria_sm[$i]]=='x')
	 $row2[$criteria_sm[$i]]="X";
      else if($row2[$criteria_sm[$i]]=='n' || $row2[$criteria_sm[$i]]=='')
	 $row2[$criteria_sm[$i]]="&nbsp;";
      echo "<td align=center>".$row2[$criteria_sm[$i]]."</td>";
   } 
   if($sport=='sb' || $sport=='ba')	//asked for number of fields on contract
   {
      echo "<td align=center>$row[fieldct]</td>";
      echo "<td align=center>$row[lightedfieldct]</td>";
   }
   $schools=split(", ",$row[schools]);
   sort($schools);
   echo "<td width=200>";
   for($i=0;$i<count($schools);$i++)
   {
      echo $schools[$i];
      if($i<(count($schools)-1)) echo ", ";
   }
   echo "</td>";
   echo "<input type=hidden name=\"distids[$ix]\" value=\"$row[id]\">";
   echo "<td align=center><input type=checkbox value='x' name=\"showdistinfo[$ix]\"";
   if($row[showdistinfo]=='x') echo " checked";
   echo "></td>";
   echo "</tr>";
   }
   $ix++;
}
echo "</table><br>";
echo "<input type=submit name=save value=\"Save Checks\">";
echo "</form>";
echo $end_html;
?>
