<?php
/*******************************************
edit_te_b.php
District Entry, Boys Tennis
Created 6/27/09
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sport='te_b';
$sportname="Boys Tennis";
$gender='M';
$meettable=$sport."meets";
$resultstable=$sport."meetresults";
$disttable=$sport;
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
   $sid=GetSID($session,$sport);
}
else if($school_ch)
{
   $sid=$school_ch;
   $school=GetMainSchoolName($sid,$sport);
}
else
{
   echo "ERROR: No School Selected";
   exit();
}
$school2=ereg_replace("\'","\'",$school);

if($save1 || $save2)
{
   $sql="SELECT * FROM $disttable WHERE sid='$sid' AND division='singles1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 && $singles1>0)
      $sql="INSERT INTO $disttable (sid,division,player1) VALUES ('$sid','singles1','$singles1')";
   else
      $sql="UPDATE $disttable SET player1='$singles1' WHERE id='$row[id]'";
   $result=mysql_query($sql);
   //Was "no entry" checked?	-- if a player is entered AND no entry is checked, player overrides it.
   $sql="DELETE FROM ".$disttable."noentries WHERE sid='$sid' AND division='singles1'";
   $result=mysql_query($sql);
   if($singles1==0 && $noentrys1=='x')
   {
      $sql="INSERT INTO ".$disttable."noentries (sid,division) VALUES ('$sid','singles1')";
      $result=mysql_query($sql); 
   }

   $sql="SELECT * FROM $disttable WHERE sid='$sid' AND division='singles2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 && $singles2>0)
      $sql="INSERT INTO $disttable (sid,division,player1) VALUES ('$sid','singles2','$singles2')";
   else
      $sql="UPDATE $disttable SET player1='$singles2' WHERE id='$row[id]'";
   $result=mysql_query($sql);
   //Was "no entry" checked?    -- if a player is entered AND no entry is checked, player overrides it.
   $sql="DELETE FROM ".$disttable."noentries WHERE sid='$sid' AND division='singles2'";
   $result=mysql_query($sql);
   if($singles2==0 && $noentrys2=='x')
   {
      $sql="INSERT INTO ".$disttable."noentries (sid,division) VALUES ('$sid','singles2')";
      $result=mysql_query($sql);
   }

   $sql="SELECT * FROM $disttable WHERE sid='$sid' AND division='doubles1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 && $doubles11>0)
      $sql="INSERT INTO $disttable (sid,division,player1,player2) VALUES ('$sid','doubles1','$doubles11','$doubles12')";
   else
      $sql="UPDATE $disttable SET player1='$doubles11',player2='$doubles12' WHERE id='$row[id]'";
   $result=mysql_query($sql);
   //Was "no entry" checked?    -- if a player is entered AND no entry is checked, player overrides it.
   $sql="DELETE FROM ".$disttable."noentries WHERE sid='$sid' AND division='doubles1'";
   $result=mysql_query($sql);
   if($doubles11==0 && $noentryd1=='x')
   {
      $sql="INSERT INTO ".$disttable."noentries (sid,division) VALUES ('$sid','doubles1')";
      $result=mysql_query($sql);
   }

   $sql="SELECT * FROM $disttable WHERE sid='$sid' AND division='doubles2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 && $doubles21>0)
      $sql="INSERT INTO $disttable (sid,division,player1,player2) VALUES ('$sid','doubles2','$doubles21','$doubles22')";
   else
      $sql="UPDATE $disttable SET player1='$doubles21',player2='$doubles22' WHERE id='$row[id]'";
   $result=mysql_query($sql);
   //Was "no entry" checked?    -- if a player is entered AND no entry is checked, player overrides it.
   $sql="DELETE FROM ".$disttable."noentries WHERE sid='$sid' AND division='doubles2'";
   $result=mysql_query($sql);
   if($doubles21==0 && $noentryd2=='x')
   {
      $sql="INSERT INTO ".$disttable."noentries (sid,division) VALUES ('$sid','doubles2')";
      $result=mysql_query($sql); 
   } 

   $sql="DELETE FROM $disttable WHERE sid='$sid' AND division='substitute'";
   $result=mysql_query($sql);
   for($i=0;$i<count($substitute);$i++)
   {
      if($substitute[$i]>0)
      {
         $sql="INSERT INTO $disttable (sid,division,player1) VALUES ('$sid','substitute','$substitute[$i]')";
         $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo $header;
$duedate=GetDueDate($sport);

echo "<br><a class=small href=\"main_".$sport.".php?school_ch=$school_ch&session=$session\">".$sportname." Main Menu</a><br><br>";
echo "<form method=post name=resultsform action=\"edit_te_b.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"school_ch\" value=\"$school_ch\">";
if($gender=="M") $hisher="his";
else $hisher="her";
echo "<table cellspacing=4 cellpadding=4><caption><b>$school ".$sportname." District Entry:</b><br><br>";
if(!PastDue($duedate,0) || $level==1)
{
   echo "<b>NOTE:</b> <i>If you have no entry in a certain division, please check \"NO ENTRY\" under that division.</i><br><br>";
}
echo "</b></caption>";
echo "<tr align=center valign=top><td><table class=nine cellspacing=0 cellpadding=4 frames=box rules=rows style=\"border:#808080 1px solid;\">";
//#1 SINGLES:
echo "<tr align=left bgcolor=#e0e0e0><td align=right>#1 Singles:</td><td>";
if(!PastDue($duedate,0) || $level==1)
{
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='singles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<input type=\"hidden\" name=\"oldsingles1\" value=\"$row2[player1]\">";
echo "<select name=\"singles1\"><option value=\"0\">~</option>";
$sql="SELECT t1.id,t1.first,t1.last,t1.semesters,t1.school FROM eligibility AS t1, headers AS t2, ".$sport."school AS t3 WHERE t1.school=t2.school AND (t2.id=t3.mainsch OR t2.id=t3.othersch1 OR t2.id=t3.othersch2 OR t2.id=t3.othersch3) AND t3.sid='$sid' AND t1.gender='$gender' AND te='x' ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($row2[player1]==$row[id]) echo " selected";
   echo ">$row[last], $row[first] (".GetYear($row[semesters]).")</option>";
   $students[id][$ix]=$row[id];
   $students[name][$ix]="$row[last], $row[first] (".GetYear($row[semesters]).")";
   $ix++;
}
echo "</select><br>OR <input type=checkbox name=\"noentrys1\" value=\"x\"";
$sql2="SELECT * FROM ".$disttable."noentries WHERE division='singles1' AND sid='$sid'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0) echo " checked";
echo "> NO ENTRY for #1 Singles</td></tr>";
}
else
{
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='singles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2); 
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")</td></tr>";
}

//#2 SINGLES:
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='singles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left><td align=right>#2 Singles:</td><td>";
if(!PastDue($duedate,0) || $level==1)
{
echo "<input type=\"hidden\" name=\"oldsingles2\" value=\"$row2[player1]\">";
echo "<select name=\"singles2\"><option value=\"0\">~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   if($row2[player1]==$students[id][$i]) echo " selected";
   echo ">".$students[name][$i]."</option>";
}
echo "</select><br>OR <input type=checkbox name=\"noentrys2\" value=\"x\"";
$sql2="SELECT * FROM ".$disttable."noentries WHERE division='singles2' AND sid='$sid'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0) echo " checked";
echo "> NO ENTRY for #2 Singles</td></tr>";
}
else
{
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='singles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")</td></tr>";
}
//#1 DOUBLES
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='doubles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr bgcolor=#e0e0e0 align=left valign=top><td align=right>#1 Doubles:</td><td>";
if(!PastDue($duedate,0) || $level==1)
{
echo "<input type=\"hidden\" name=\"olddoubles11\" value=\"$row2[player1]\">";
echo "<select name=\"doubles11\"><option value=\"0\">~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   if($row2[player1]==$students[id][$i]) echo " selected";
   echo ">".$students[name][$i]."</option>";
}
echo "</select><br>";
echo "<input type=\"hidden\" name=\"olddoubles12\" value=\"$row2[player2]\">";
echo "<select name=\"doubles12\"><option value=\"0\">~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   if($row2[player2]==$students[id][$i]) echo " selected";
   echo ">".$students[name][$i]."</option>";
}
echo "</select><br>OR <input type=checkbox name=\"noentryd1\" value=\"x\"";
$sql2="SELECT * FROM ".$disttable."noentries WHERE division='doubles1' AND sid='$sid'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0) echo " checked";
echo "> NO ENTRY for #1 Doubles</td></tr>";
}
else
{
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='doubles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")<br>";
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player2 AND t2.sid='$sid' AND t2.division='doubles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)>0)
   echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")</td></tr>";
}
//#2 DOUBLES
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='doubles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left valign=top><td align=right>#2 Doubles:</td><td>";
if(!PastDue($duedate,0) || $level==1)
{
echo "<input type=\"hidden\" name=\"olddoubles21\" value=\"$row2[player1]\">";
echo "<select name=\"doubles21\"><option value=\"0\">~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   if($row2[player1]==$students[id][$i]) echo " selected";
   echo ">".$students[name][$i]."</option>";
}
echo "</select><br>";
echo "<input type=\"hidden\" name=\"olddoubles22\" value=\"$row2[player2]\">";
echo "<select name=\"doubles22\"><option value=\"0\">~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   if($row2[player2]==$students[id][$i]) echo " selected";
   echo ">".$students[name][$i]."</option>";
}
echo "</select><br>OR <input type=checkbox name=\"noentryd2\" value=\"x\"";
$sql2="SELECT * FROM ".$disttable."noentries WHERE division='doubles2' AND sid='$sid'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0) echo " checked";
echo "> NO ENTRY for #2 Doubles</td></tr>";
}
else
{
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='doubles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")<br>";
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player2 AND t2.sid='$sid' AND t2.division='doubles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)>0)
   echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")</td></tr>";
}
echo "</table><br>";
if(!PastDue($duedate,0) || $level==1)
   echo "<input type=submit name=save1 value=\"Save\">";
echo "</td><td><table class=nine cellspacing=0 cellpadding=4 frames=box rules=rows style=\"border:#808080 1px solid;\">";
//SUBSTITUTES
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='substitute'";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor=#e0e0e0";
   echo "><td align=right>Substitute:</td><td>";
   if(!PastDue($duedate,0) || $level==1)
   {
   echo "<select name=\"substitute[$ix]\"><option value=\"0\">~</option>";
   for($i=0;$i<count($students[id]);$i++)
   {
      echo "<option value=\"".$students[id][$i]."\"";
      if($row2[player1]==$students[id][$i]) echo " selected";
      echo ">".$students[name][$i]."</option>";
   }
   echo "</select></td></tr>";
   }
   else
   {
   $sql3="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.id='$row2[id]'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   echo "$row3[last], $row3[first] (".GetYear($row3[semesters]).")</td></tr>";
   }
   $ix++;
}
if(!PastDue($duedate,0) || $level==1)
{
//add place to add a sub:
echo "<tr align=left";
if($ix%2==0) echo " bgcolor=#e0e0e0";
echo "><td align=right valign=top>Substitute:</td><td>";
echo "<select name=\"substitute[$ix]\"><option value=\"0\">~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   echo ">".$students[name][$i]."</option>";
}
echo "</select></td></tr></table><font style=\"font-size:8pt;\"><i>(To delete a substitute, simply<br>select \"~\" and click \"Save/Add More\")</i></font><br>";
echo "<input type=submit name=save2 value=\"Save/Add More\">";
echo "</td></tr>";
}
echo "</table>";
echo "</form>";
echo $end_html;
?>
