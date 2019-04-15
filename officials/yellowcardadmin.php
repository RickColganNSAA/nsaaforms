<?php
/*****************************
Yellow Card Admin
Created 2/14/11
Author Ann Gaffigan
******************************/

require 'functions.php';
require '../../calculate/functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$database || $database=="")
{
   $db1=$db_name; $db2=$db_name2;
}
else
{
   $db2=$database; $db1=ereg_replace("officials","scores",$database);
}

if($save=="Save Changes & Delete Checked")
{
   //delete checked reports
   //update Verified checks and Notes
   for($i=0;$i<count($offid);$i++)
   {
      $notes=addslashes($offnotes[$i]);
      $sql="UPDATE $db2.yellowcards SET verify='$offverify[$i]',notes='$notes' WHERE id='$offid[$i]'";
      $result=mysql_query($sql);
      if($offdelete[$i]=='x')
      {
	 $sql="DELETE FROM $db2.yellowcards WHERE id='$offid[$i]'";
         $result=mysql_query($sql);
      }
   }

   unset($offid);
}

echo $init_html;
echo GetHeader($session,"yellowcardadmin");

echo "<form name=yellowcardform method=post action=\"yellowcardadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";

//first show yellow card reports that match another
echo "<br>";
echo "<table width=100%>";
echo "<caption><b>NSAA Yellow Card Reports Admin:</b><br><i>(Click on buttons in column headers to sort by that field)</i><br><br>";
echo "</caption>";
echo "<tr align=center><td><table>";
//FILTER
echo "<tr align=left><td colspan=2><b>Filter Yellow Card Reports:<hr></td></tr>";
echo "<tr align=left><td><b>Year:</b></td><td><select name=\"database\">";
$sql="SHOW DATABASES LIKE '".$db_name2."%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $row[0].yellowcards";
   $result2=mysql_query($sql2);
   if(!mysql_error())
   {
      $temp=split("$db_name2",$row[0]);
      $year1=substr($temp[1],0,4);
      $year2=substr($temp[1],4,4);
      echo "<option value=\"$row[0]\"";
      if($database==$row[0]) echo " selected";
      echo ">";
      if($row[0]=="$db_name2") echo "This Year";
      else echo "$year1-$year2";
      echo "</option>";
   }
}
echo "</select></td></tr>";
echo "<tr align=left><td><b>Sport:</b></td>";
echo "<td><select onchange=\"submit();\" name=\"sportch\"><option>All Sports</option><option value='sog'";
if($sportch=='sog') echo " selected";
echo ">GIRLS Soccer</option><option value='sob'";
if($sportch=='sob') echo " selected";
echo ">BOYS Soccer</option>";
echo "</select></td></tr>";
if(!$sportch || $sportch=="All Sports")
{
   echo "<tr align=center><th colspan=2>Please select a sport to continue.</th></tr>";
   echo "</table>"; 
   echo $end_html;
   exit();
}
echo "<tr align=left><td><b>School:</b></td><td>";
echo "<select name=\"schoolch\"><option>All Schools</option>";
$sql="SELECT * FROM $db1.".$sportch."school WHERE outofstate!=1 ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[sid]'";
   if($schoolch==$row[sid]) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td><b>Level:</b></td>";
echo "<td><select name=levelch><option>All Levels</option>";
$levels=array("Varsity","Junior Varsity","Reserve","Freshman");
for($i=0;$i<count($levels);$i++)
{
   echo "<option";
   if($levels[$i]==$levelch) echo " selected";
   echo ">$levels[$i]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td><b>Game Date:</b></td>";
echo "<td>from&nbsp;<select name=gamemonth1><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $mo="0".$i;
   else $mo=$i;
   echo "<option";
   if($mo==$gamemonth1) echo " selected";
   echo ">$mo</option>";
}
echo "</select>/<select name=gameday1><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($d==$gameday1) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=gameyear1>";
$curryr=date("Y");
$lastyr=$curryr-1;
echo "<option";
if($gameyear1==$lastyr) echo " selected";
echo ">$lastyr</option><option";
if($gameyear1!=$lastyr) echo " selected";
echo ">$curryr</option></select>&nbsp;to&nbsp;<select name=gamemonth2><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $mo="0".$i;
   else $mo=$i;
   echo "<option";
   if($mo==$gamemonth2) echo " selected";
   echo ">$mo</option>";
}
echo "</select>/<select name=gameday2><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($d==$gameday2) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=gameyear2>";
if(gameyear2==$lastyr) echo " selected";
echo ">$lastyr</option><option";
if($gameyear2!=$lastyr) echo " seleted";
echo ">$curryr</option></select></td></tr>";
echo "<tr align=left><td><b>Date Submitted:</b></td>";
echo "<td>from&nbsp;<select name=submonth1><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $mo="0".$i;
   else $mo=$i;
   echo "<option";
   if($mo==$submonth1) echo " selected";
   echo ">$mo</option>";
}
echo "</select>/<select name=subday1><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($d==$subday1) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=subyear1>";
$curryr=date("Y");
$lastyr=$curryr-1;
echo "<option";
if($subyear1==$lastyr) echo " selected";
echo ">$lastyr</option><option";
if($subyear1!=$lastyr) echo " selected";
echo ">$curryr</option></select>&nbsp;to&nbsp;<select name=submonth2><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $mo="0".$i;
   else $mo=$i;
   echo "<option";
   if($mo==$submonth2) echo " selected";
   echo ">$mo</option>";
}
echo "</select>/<select name=subday2><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($d==$subday2) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=subyear2>";
$curryr=date("Y");
$lastyr=$curryr-1;
echo "<option";
if($subyear2==$lastyr) echo " selected";
echo ">$lastyr</option><option";
if($subyear2!=$lastyr) echo " selected";
echo ">$curryr</option></select></td></tr>";
echo "<tr align=left><td><b>Show Reports:</b></td>";
echo "<td><select name=reportch2>";
echo "<option";
if(!$reportch2 || $reportch2=="Non-Verified Only")
   echo " selected";
echo ">Non-Verified Only</option><option";
if($reportch2=='Verified & Non-Verified')
   echo " selected";
echo ">Verified & Non-Verified</option></select></td></tr>";

echo "<tr align=right><td colspan=2><input type=submit name=filter value=\"Filter\"></td></tr>";
mysql_close();
mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);
echo "</table><br>";
echo "<b>Exports:</b><br><br>";
if($sportch=="sog") $gb="GIRLS";
else $gb="BOYS";
echo "<a class=small target='_blank' href=\"yellowcardexport.php?sport=$sportch&database=$db2&session=$session\">All $gb Yellow Card Reports (in Excel Format)</a><br><br>";
echo "</td></tr>";

$toprow="";
$toprow.="<tr align=center>";
$toprow.="<th class=small>Delete</th>";
$toprow.="<td><b>Yellow Card Report</b></td>";
$toprow.="<td><input class=tiny type=submit name=sort value=\"Submitted\"></td>";
$toprow.="<td><input type=submit name=sort value=\"Sport\"></td>";
$toprow.="<td><input type=submit name=sort value=\"School\"></td>";
$toprow.="<td><input type=submit name=sort value=\"Level\"></td>";
$toprow.="<td><input type=submit name=sort value=\"Game Date\"></td>";
$toprow.="<td><b>Player</b></td>";
$toprow.="<td><input type=submit name=sort value=\"Verified\"></td>";
$toprow.="<td><b>Notes</b></td></tr>";

$adx=0; $offx=0;

      echo "<tr align=center><td><br>";
      echo "<table width=100% cellspacing=0 cellpadding=1 border=1 bordercolor=#000000>";
	     
$sql="SELECT t2.school,t1.* FROM $db2.yellowcards AS t1,$db1.".$sportch."school AS t2 WHERE t1.datesub>0 AND t1.sid=t2.sid";
if($schoolch && $schoolch!="All Schools")
{
   $sql.=" AND t1.sid='$schoolch'";
}
if($sportch && $sportch!="All Sports")
   $sql.=" AND t1.sport='$sportch'";
if($levelch && $levelch!="All Levels")
   $sql.=" AND t1.level='$levelch'";
if($gamemonth1)	//if any filter information submitted, check date ranges
{
   //Game Date
   if($gamemonth1!='MM' && $gameday1!='DD' && $gamemonth2!='MM' && $gameday2!='DD')
      $sql.=" AND t1.gamedate>='$gameyear1-$gamemonth1-$gameday1' AND t1.gamedate<='$gameyear2-$gamemonth2-$gameday2'";
   else if($gamemonth1!='MM' && $gameday1!='DD')
      $sql.=" AND t1.gamedate>='$gameyear1-$gamemonth1-$gameday1'";
   else if($gamemonth2!='MM' && $gameday2!='DD')
      $sql.=" AND t1.gamedate<='$gameyear2-$gamemonth2-$gameday2'";
   //Date Submitted
   $from=mktime(0,0,0,$submonth1,$subday1,$subyear1);
   $to=mktime(0,0,0,$submonth2,$subday2,$subyear2);
   $to+=24*60*60;	//midnight on this day
   if($submonth1!='MM' && $subday1!='DD' && $submonth2!='MM' && $subday2!='DD')
      $sql.=" AND (t1.datesub>='$from' AND t1.datesub<='$to')";
   else if($submonth1!='MM' && $subday1!='DD')
      $sql.=" AND t1.datesub>='$from'";
   else if($submonth2!='MM' && $subday2!='DD')
      $sql.=" AND t1.datesub<='$to'";
}
if(!$reportch2 || $reportch2=="Non-Verified Only")
   $sql.=" AND t1.verify!='x'";

if($sort=="Sport")
   $sql.=" ORDER BY t1.sport,t2.school,t1.gamedate DESC,t1.datesub";
else if($sort=="School")
   $sql.=" ORDER BY t2.school,t1.gamedate DESC,t1.sport,t1.datesub";
else if($sort=="Game Date")
   $sql.=" ORDER BY t1.gamedate,t1.sport,t2.school,t1.datesub";
else if($sort=="Level")
   $sql.=" ORDER BY t1.level,t1.sport,t1.gamedate DESC,t2.school,t1.datesub";
else if($sort=="Verified")
   $sql.=" ORDER BY t1.verify,t1.sport,t1.gamedate DESC,t2.school,t2.datesub";
else
   $sql.=" ORDER BY t1.datesub DESC";
$result=mysql_query($sql);
//echo $sql."<br>".mysql_error();
if(mysql_num_rows($result)>0) echo $toprow;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left>";
   
      echo "<input type=hidden name=\"offid[$offx]\" value=\"$row[id]\">";
      echo "<td align=center><input type=checkbox name=\"offdelete[$offx]\" value='x'>";

   echo "</td>";
   echo "<td>";
   echo "<a target=new class=small href=\"view_yellowcard.php?database=$db2&header=no&off=1&session=$session&id=$row[id]\">Official: ".GetOffName($row[offid])." (#$row[id])</a>";

   echo "</td><td>";
   echo date("m/d/Y",$row[datesub]);
   echo "</td>";

   echo "<td>".GetSportName($row[sport])."</td>";
   echo "<td>".GetSchoolName($row[sid],$row[sport],date("Y"))."</td>";
   echo "<td>$row[level]</td>";
   $date=split("-",$row[gamedate]);
   echo "<td>$date[1]/$date[2]/$date[0]</td>";
   //get player's name from $db_name.eligibility
   $sql2="SELECT first,middle,last FROM $db1.eligibility WHERE id='$row[studentid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $playername="$row2[first] $row2[middle] $row2[last]";
   echo "<td>$playername</td>";
  
      echo "<td align=center><input type=checkbox name=\"offverify[$offx]\" value='x'";
      if($row[verify]=='x') echo " checked";
      echo ">";
   
   echo "</td>";
   
      echo "<td><input type=text class=tiny size=20 value=\"$row[notes]\" name=\"offnotes[$offx]\">";
      $offx++;

   echo "</td></tr>";
}
echo "</table></td></tr>";

echo "<tr align=center><td><input type=submit name=save value=\"Save Changes & Delete Checked\"></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
