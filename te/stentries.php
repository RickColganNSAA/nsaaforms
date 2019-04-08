<?php
/*******************************************
stentries.php
Vew ALL State Entries, after due date
Created 5/5/10
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
if(!ValidUser($session))	//CLEARANCE: logged in School user
{
   header("Location:../index.php");
   exit();
}
if(!$sport) $sport='te_g';
$sportname=GetActivityName($sport);
if($sport=='te_g') $gender='F';
else $gender='M';

echo $init_html;
echo $header;
$duedate=GetDueDate($sport."state");
if(!$secret && !PastDue($duedate,0))	//NO ACCESS
{
   $date=split("-",$duedate);
   echo "<br><br>The State Entries for Class A schools will be available after $date[1]/$date[2]/$date[0].<br><br><a href=\"main_".$sport.".php?session=$session\">Return to $sportname Main Menu</a><br><br>";
   echo $end_html;
   exit();
}

//USER CHOOSES SCHOOL
echo "<a href=\"main_".$sport.".php?session=$session\">Return to $sportname Main Menu</a><br><br>";
echo "<form method=post action=\"stentries.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"secret\" value=\"$session\">";
echo "<input type=hidden name=\"sport\" value=\"$sport\">";
echo "<p><b>SELECT A CLASS:</b> <select onChange=\"submit();\" name='class'><option value=''>Class</option>";
echo "<option value='A'";
if($class=="A") echo " selected";
echo ">Class A</option><option value='B'";
if($class=="B") echo " selected";
echo ">Class B</option></select><p>";
echo "<b>Select a School to see its State Entry:</b> <select name=\"sid\" onChange='submit();'><option value='0'>Select School</option>";
$sql="SELECT * FROM ".$sport."school ";
if($class!='') $sql.="WHERE class='$class' ";
$sql.="ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
      echo "<option value=\"$row[sid]\"";
      if($sid==$row[sid]) echo " selected";
      echo ">$row[school]</option>";
}
echo "</select></form>";

if($sid)
{
   $school=GetSchoolName($sid,$sport,date("Y"));
   if($gender=="M") $hisher="his";
   else $hisher="her";
   $statetable=$sport."state";
  
   echo "<table cellspacing=4 cellpadding=4><caption><b><u>$school</u> ".$sportname." State Entry:</b><br><br>";
   echo "</b></caption>";
   echo "<tr align=center valign=top><td><table class=nine cellspacing=0 cellpadding=4 frames=box rules=rows style=\"border:#808080 1px solid;\">";
   //#1 SINGLES:
   echo "<tr align=left><td align=right>#1 Singles:</td><td>";
   $sql2="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='singles1'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2); 
   if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
   else echo "<a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=singles1&playerid1=$row2[id]&playerid2=0\">$row2[last], $row2[first] (".GetYear($row2[semesters]).")</a></td></tr>";

   //#2 SINGLES:
   $sql2="SELECT * FROM $statetable WHERE sid='$sid' AND division='singles2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><td align=right>#2 Singles:</td><td>";
   $sql2="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='singles2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(mysql_num_rows($result2)==0) echo "<i>No Entry</i>";
   else echo "<a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=singles2&playerid1=$row2[id]&playerid2=0\">$row2[last], $row2[first] (".GetYear($row2[semesters]).")</a></td></tr>";
   
   //#1 DOUBLES
   $sql2="SELECT * FROM $statetable WHERE sid='$sid' AND division='doubles1'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left valign=top><td align=right>#1 Doubles:</td><td>";
   $sql2="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='doubles1'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sql3="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player2 AND t2.sid='$sid' AND t2.division='doubles1'";
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
   $sql2="SELECT * FROM $statetable WHERE sid='$sid' AND division='doubles2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left valign=top><td align=right>#2 Doubles:</td><td>";
   $sql2="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player1 AND t2.sid='$sid' AND t2.division='doubles2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sql3="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player2 AND t2.sid='$sid' AND t2.division='doubles2'";
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
   $sql2="SELECT * FROM $statetable WHERE sid='$sid' AND division='substitute'";
   $result2=mysql_query($sql2);
   $ix=0;
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr align=left";
      echo "><td align=right>Substitute:</td><td>";
      $sql3="SELECT t1.* FROM eligibility AS t1,$statetable AS t2 WHERE t1.id=t2.player1 AND t2.id='$row2[id]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      echo "<a target=\"_blank\" href=\"viewplayer.php?sid=$sid&session=$session&sport=$sport&class=B&division=doubles2&playerid1=$row3[id]&playerid2=0\">$row3[last], $row3[first] (".GetYear($row3[semesters]).")</a></td></tr>";
      $ix++;
   }
   echo "</table>";
}//END IF SID
echo $end_html;
?>
