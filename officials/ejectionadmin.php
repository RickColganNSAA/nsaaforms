<?php

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

if(!$database || $database=="")
{
   $db1=$db_name; $db2=$db_name2;
}
else
{
   $db1=$database; $db2=ereg_replace("scores","officials",$database);
}

if($submit=="Save Changes & Delete Checked")
{
   //delete checked reports
   //update Verified checks and Notes
      //AD's:
   mysql_close();
   $db=mysql_connect("$db_host",$db_user,$db_pass);
   mysql_select_db($db_name,$db);
   for($i=0;$i<count($adid);$i++)
   {
      $notes=addslashes($adnotes[$i]);
      $sql="UPDATE $db1.ejections SET verify='$adverify[$i]',notes='$notes' WHERE id='$adid[$i]'";
      $result=mysql_query($sql);
      if($addelete[$i]=='x') 
      {
         $sql="DELETE FROM $db1.ejections WHERE id='$adid[$i]'";
         $result=mysql_query($sql);
      }
   }
   //Officials:
   mysql_close();
   $db=mysql_connect("$db_host",$db_user2,$db_pass2);
   mysql_select_db($db_name2,$db);
   for($i=0;$i<count($offid);$i++)
   {
      $notes=addslashes($offnotes[$i]);
      $sql="UPDATE $db2.ejections SET verify='$offverify[$i]',notes='$notes' WHERE id='$offid[$i]'";
      $result=mysql_query($sql);
      if($offdelete[$i]=='x')
      {
	 $sql="DELETE FROM $db2.ejections WHERE id='$offid[$i]'";
         $result=mysql_query($sql);
      }
   }

   unset($adid);
   unset($offid);
}

echo $init_html;
echo GetHeader($session,"ejectionadmin");

echo "<form name=ejectionform method=post action=\"ejectionadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";

//first show ejections reports that match another
echo "<br>";
echo "<table width=100%>";
echo "<caption><b>NSAA Ejection Reports Admin:</b><br><i>(Click on buttons in column headers to sort by that field)</i><br><br>";
echo "</caption>";
echo "<tr align=center><td><table>";
//FILTER
echo "<tr align=left><td colspan=2><b>Filter Ejection Reports:<hr></td></tr>";
echo "<tr align=left><td><b>Year:</b></td><td><select name=\"database\">";
$sql="SHOW DATABASES LIKE '$db_name%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $row[0].ejections";
   $result2=mysql_query($sql2);
   if(!mysql_error())
   {
      $temp=split("$db_name",$row[0]);
      $year1=substr($temp[1],0,4);
      $year2=substr($temp[1],4,4);
      echo "<option value=\"$row[0]\"";
      if($database==$row[0]) echo " selected";
      echo ">";
      if($row[0]=="$db_name") echo "This Year";
      else echo "$year1-$year2";
      echo "</option>";
   }
}
echo "</td></tr>";
echo "<tr align=left><td><b>School:</b></td><td>";
echo "<select name=\"schoolch\"><option>All Schools</option>";
//get schools from $db_name.headers
$sql="SELECT school FROM $db1.headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($schoolch==$row[0]) echo " selected";
   echo ">$row[0]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td><b>Sport:</b></td>";
echo "<td><select name=\"sportch\"><option>All Sports</option>";
for($i=0;$i<count($eject2);$i++)
{
   echo "<option value=\"$eject2[$i]\"";
   if($sportch==$eject2[$i]) echo " selected";
   echo ">$eject_long[$i]</option>";
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
echo "<td><select name=reportch><option>All</option>";
echo "<option";
if($reportch=='Matches Only') echo " selected";
echo ">Matches Only</option><option";
if($reportch=="Non-Matches Only") echo " selected";
echo ">Non-Matches Only</option><option";
if($reportch=="AD's Reports w/o Matches") echo " selected";
echo ">AD's Reports w/o Matches</option><option";
if($reportch=="Officials' Reports w/o Matches") echo " selected";
echo ">Officials' Reports w/o Matches</option>";
echo "</select>&nbsp;";
echo "<select name=reportch2>";
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
echo "<a class=small target=new href=\"ejectionexport.php?database=$db1&session=$session\">ALL Ejection Reports (in Excel Format)</a><br><br>";
echo "<a class=small target=new href=\"ejectionless.php?database=$db1&session=$session\">Schools with NO ejections reported (by official or school or both)</a><br><br>";
echo "<a class=small target=new href=\"ejectionfull.php?database=$db1&session=$session\">Schools WITH ejections reported (by official or school or both)</a>";
echo "</td></tr>";

$toprow="";
$toprow.="<tr align=center>";
$toprow.="<th class=small>Delete</th>";
$toprow.="<td><b>Ejection Report</b></td>";
$toprow.="<td><input class=tiny type=submit name=sort value=\"Submitted\"></td>";
$toprow.="<td><input type=submit name=sort value=\"Sport\"></td>";
$toprow.="<td><input type=submit name=sort value=\"School\"></td>";
$toprow.="<td><input type=submit name=sort value=\"Level\"></td>";
$toprow.="<td><input type=submit name=sort value=\"Game Date\"></td>";
$toprow.="<td><b>Name of Ejected Coach/Player</b></td>";
$toprow.="<td><input type=submit name=sort value=\"Verified\"></td>";
$toprow.="<td><b>Notes</b></td></tr>";

$sqls=array("SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.comment,t1.datesub,t1.verify,t1.notes,t2.id,t2.offid,t2.school1,t2.datesub,t2.verify,t2.notes,t2.level,t2.coach FROM $db1.ejections AS t1 LEFT JOIN $db_name2.ejections AS t2 on t1.sport=t2.sport AND t1.sid=t2.sid AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!='')) WHERE t2.id IS NOT NULL","SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.comment,t1.datesub,t1.verify,t1.notes FROM $db1.ejections AS t1 LEFT JOIN $db_name2.ejections AS t2 ON (t1.sport=t2.sport AND t1.sid=t2.sid AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!=''))) WHERE t2.id IS NULL","SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.reason,t1.datesub,t1.verify,t1.notes,t1.offid FROM $db2.ejections AS t1 LEFT JOIN $db_name.ejections AS t2 ON (t1.sport=t2.sport AND t1.sid=t2.sid AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!=''))) WHERE t2.id IS NULL");
$adx=0; $offx=0;
for($x=0;$x<count($sqls);$x++)
{
   if((!$reportch || $reportch=='All' || ($x==0 && $reportch=='Matches Only') || ($x==1 && $reportch=="AD's Reports w/o Matches") || ($x==2 && $reportch=="Officials' Reports w/o Matches") || (($x==1 || $x==2) && $reportch=="Non-Matches Only")))
   {
      echo "<tr align=center><td><br>";
      echo "<table width=100% cellspacing=0 cellpadding=1 border=1 bordercolor=#000000>";
      echo "<caption align=left><font style=\"font-size:8pt;\"><b>";
      switch($x)
      {
	 case 0:
	    echo "Matches:";
	    break;
	 case 1:
	    echo "AD's Reports w/o a Match:";
	    break;
	 case 2:
	    echo "Officials' Reports w/o a Match:";
	    break;
      }
      echo "</b></font></caption>";
	     
      $sql=$sqls[$x];

if($schoolch && $schoolch!="All Schools")
{
   $schoolch2=addslashes($schoolch);
   $sql.=" AND t1.school='$schoolch2'";
}
if($sportch && $sportch!="All Sports")
   $sql.=" AND t1.sport='$sportch'";
if($levelch && $levelch!="All Levels")
   $sql.=" AND (t1.level='$levelch' OR t2.level='$levelch')";
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
      $sql.=" AND ((t1.datesub>='$from' AND t1.datesub<='$to') OR (t2.datesub>='$from' AND t2.datesub<='$to'))";
   else if($submonth1!='MM' && $subday1!='DD')
      $sql.=" AND (t1.datesub>='$from' OR t2.datesub>='$from')";
   else if($submonth2!='MM' && $subday2!='DD')
      $sql.=" AND (t1.datesub<='$to' OR t2.datesub<='$to')";
}

if($sort=="Sport")
   $sql.=" ORDER BY t1.sport,t1.gamedate DESC,t1.school,t1.datesub";
else if($sort=="School")
   $sql.=" ORDER BY t1.school,t1.gamedate DESC,t1.sport,t1.datesub";
else if($sort=="Game Date")
   $sql.=" ORDER BY t1.gamedate,t1.sport,t1.school,t1.datesub";
else if($sort=="Level")
   $sql.=" ORDER BY t1.level,t1.sport,t1.gamedate DESC,t1.school,t1.datesub";
else if($sort=="Verified")
   $sql.=" ORDER BY t1.verify,t2.verify,t1.sport,t1.gamedate DESC,t1.school,t2.datesub";
else
   $sql.=" ORDER BY t1.datesub DESC,t2.datesub DESC";
$result=mysql_query($sql);
//echo $sql."<br>";
if(mysql_num_rows($result)>0) echo $toprow;
while($row=mysql_fetch_array($result))
{
   if(((!$reportch2 || $reportch2=="Non-Verified Only") && ($row[9]!='x' || ($x==0 && $row[15]!='x'))) || $reportch2=="Verified & Non-Verified")
   {
   echo "<tr align=left>";
   if($x==0 || $x==1)
   {
      echo "<input type=hidden name=\"adid[$adx]\" value=\"$row[0]\">";
      echo "<td align=center><input type=checkbox name=\"addelete[$adx]\" value='x'>";
   }
   else
   {
      echo "<input type=hidden name=\"offid[$offx]\" value=\"$row[0]\">";
      echo "<td align=center><input type=checkbox name=\"offdelete[$offx]\" value='x'>";
   }
   if($x==0)
   { 
      echo "<input type=hidden name=\"offid[$offx]\" value=\"$row[11]\">";
      echo "<br><input type=checkbox name=\"offdelete[$offx]\" value='x'>";
   }
   echo "</td>";
   echo "<td>";
   if($x==0 || $x==1)	//Matches or AD Only
      echo "<a target=new class=small href=\"../view_ejection.php?database=$db1&header=no&off=1&session=$session&id=$row[0]\">$row[school]'s AD (#$row[0])</a>";
   else	//Official Only
      echo "<a target=new class=small href=\"view_ejection.php?database=$db1&header=no&off=1&session=$session&id=$row[0]\">Official: ".GetOffName($row[offid])." (#$row[0])</a>";
   if($x==0) //Matches
      echo "<br><a target=new class=small href=\"view_ejection.php?database=$db1&header=no&off=1&session=$session&id=$row[11]\">Official: ".GetOffName($row[offid])." (#$row[11])</a>";

   echo "</td><td>";
   echo date("m/d/Y",$row[8]);
   if($x==0) 
      echo "<br>".date("m/d/Y",$row[14]);
   echo "</td>";
   echo "<td>".GetSportName($row[sport])."</td>";
   echo "<td>$row[school]</td>";
   echo "<td>";
   echo $row[6];
   if($x==0) echo "<br>$row[17]";
   echo "</td>";
   $date=split("-",$row[gamedate]);
   echo "<td>$date[1]/$date[2]/$date[0]</td>";
   if($row[coach]!='')
   {
      echo "<td>Coach:<br>";
      echo $row[5];
      if($x==0) echo "<br>$row[18]";
      echo "</td>";
   }
   else
   {
      //get player's name from $db_name.eligibility
      mysql_close();
      $db=mysql_connect("$db_host",$db_user,$db_pass);
      mysql_select_db($db_name,$db);
      $sql2="SELECT first,middle,last FROM $db1.eligibility WHERE id='$row[player]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $playername="Player: $row2[first] $row2[middle] $row2[last]";

      mysql_close();
      $db=mysql_connect("$db_host",$db_user2,$db_pass2);
      mysql_select_db($db_name2,$db);
      
      echo "<td>$playername</td>";
   }
   if($x==0 || $x==1)
   {
      echo "<td align=center><input type=checkbox name=\"adverify[$adx]\" value='x'";
      if($row[9]=='x') echo " checked";
      echo ">";
   }
   else
   {
      echo "<td align=center><input type=checkbox name=\"offverify[$offx]\" value='x'";
      if($row[9]=='x') echo " checked";
      echo ">";
   }
   if($x==0)
   {  
      echo "<br><input type=checkbox name=\"offverify[$offx]\" value='x'";
      if($row[15]=='x') echo " checked";
      echo ">";
   }
   echo "</td>";
   if($x==0 || $x==1)
   {
      echo "<td><input type=text class=tiny size=20 value=\"$row[10]\" name=\"adnotes[$adx]\">";
      $adx++;
   }
   else
   {
      echo "<td><input type=text class=tiny size=20 value=\"$row[10]\" name=\"offnotes[$offx]\">";
      $offx++;
   }
   if($x==0)
   {
      echo "<br><input type=text class=tiny size=20 value=\"$row[16]\" name=\"offnotes[$offx]\">";
      $offx++;
   }
   echo "</td></tr>";
   }//end if $reportch2 conditions met
}
echo "</table></td></tr>";
}//end if show this report
}//end for each sql

echo "<tr align=center><td><input type=submit name=submit value=\"Save Changes & Delete Checked\"></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
