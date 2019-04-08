<?php
/*******************************************
view_te_g.php
VIEW District Entry, Girls Tennis
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
$sport='te_g';
$sportname="Girls Tennis";
$gender='F';
$meettable=$sport."meets";
$resultstable=$sport."meetresults";
$disttable=$sport;
//get school user chose (Level 1) or belongs to (Level 2, 3)
if($level==1)
{
   $sid=$school_ch;
   $school=GetMainSchoolName($sid,$sport);
}
else if($sid)
{
   $school=GetMainSchoolName($sid,$sport);
   $hostsch=GetSchool($session);
   $hostsch2=addslashes($hostsch);
   $sql="SELECT id FROM logins WHERE school='$hostsch2' AND level='$level'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[0];
   $sql="SELECT * FROM $db_name2.tegdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
else
{
   echo "ERROR: No School Selected";
   exit();
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo $header;
$duedate=GetDueDate($sport);

echo "<br><a class=small href=\"host_teg.php?school_ch=$school_ch&session=$session\">".$sportname." District Host Main Menu</a><br><br>";
if($gender=="M") $hisher="his";
else $hisher="her";
echo "<table cellspacing=4 cellpadding=4><caption><b><u>$school</u> ".$sportname." District Entry:</b><br><br>";
//echo "<a href=\"edit_te_g.php?session=$session&school_ch=$sid\">Edit this Entry Form (use Substitutes)</a>";
echo "</b></caption>";
echo "<tr align=center valign=top><td><table class=nine cellspacing=0 cellpadding=4 frames=box rules=rows style=\"border:#808080 1px solid;\">";
//#1 SINGLES:
echo "<tr align=left><td align=right>#1 Singles:</td><td>";
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='singles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2); 
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else echo "<a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=singles1&playerid1=$row2[id]&playerid2=0\">$row2[last], $row2[first] (".GetYear($row2[semesters]).")</a></td></tr>";
//else echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")</td></tr>";

//#2 SINGLES:
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='singles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left><td align=right>#2 Singles:</td><td>";
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='singles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else echo "<a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=singles2&playerid1=$row2[id]&playerid2=0\">$row2[last], $row2[first] (".GetYear($row2[semesters]).")</a></td></tr>";

//#1 DOUBLES
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='doubles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left valign=top><td align=right>#1 Doubles:</td><td>";
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='doubles1'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$sql3="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player2 AND t2.sid='$sid' AND t2.division='doubles1'";
$result3=mysql_query($sql3);
$row3=mysql_fetch_array($result3);
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else 
{
   echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")<br>";
   if(mysql_num_rows($result3)>0)
      echo "$row3[last], $row3[first] (".GetYear($row3[semesters]).")";
   echo "<br><a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=doubles1&playerid1=$row2[id]&playerid2=$row3[0]\">Summary for this Pair</a></td></tr>";
}

//#2 DOUBLES
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='doubles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left valign=top><td align=right>#2 Doubles:</td><td>";
$sql2="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='doubles2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$sql3="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player2 AND t2.sid='$sid' AND t2.division='doubles2'";
$result3=mysql_query($sql3);
$row3=mysql_fetch_array($result3);
if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
else 
{   
   echo "$row2[last], $row2[first] (".GetYear($row2[semesters]).")<br>";
   if(mysql_num_rows($result3)>0)
      echo "$row3[last], $row3[first] (".GetYear($row3[semesters]).")";
   echo "<br><a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=doubles2&playerid1=$row2[id]&playerid2=$row3[0]\">Summary for this Pair</a></td></tr>";
}

echo "</table><br>";
echo "</td><td><table class=nine cellspacing=0 cellpadding=4 frames=box rules=rows style=\"border:#808080 1px solid;\">";
//SUBSTITUTES
$sql2="SELECT * FROM $disttable WHERE sid='$sid' AND division='substitute'";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   echo "<tr align=left";
   echo "><td align=right>Substitute:</td><td>";
   $sql3="SELECT t1.* FROM eligibility AS t1,$disttable AS t2 WHERE t1.id=t2.player1 AND t2.id='$row2[id]'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   //echo "$row3[last], $row3[first] (".GetYear($row3[semesters]).")</td></tr>";
   echo "<a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=doubles2&playerid1=$row3[id]&playerid2=0\">$row3[last], $row3[first] (".GetYear($row3[semesters]).")</a></td></tr>";
   $ix++;
}
echo "</table>";
echo "</form>";
echo $end_html;
?>
