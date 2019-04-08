<?php
/*******************************************
te_gdistresults.php
District Results, Girls Tennis
Created 3/22/10
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';
require 'tefunctions.php';

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
$gender="F";
$max=4;
$sportname="Girls Tennis";
$districts="tegdistricts";
$results=$sport."distresults";

if(GetLevel($session)!=1)
{
   //get district this school is hosting
   $sql="SELECT t1.id FROM logins AS t1,sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id];
   $sql="SELECT * FROM $db_name2.$districts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)
   {
      echo "ERROR: You are not a District Tennis Host";
      exit();
   }
}
else 
{
   $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid' AND type='District'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)
   {
      echo "ERROR: No District was specified.";
      exit();  
   }
}
$class=$row['class']; $district=$row[district];
$distid=$row[id]; $sids=split(",",$row[sids]);

if($save)
{
   for($i=0;$i<$max;$i++)
   {
      $place=$i+1;
      //#1 SINGLES
      $sql="SELECT * FROM $results WHERE distid='$distid' AND division='singles1' AND place='$place'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0 && $singles1[$i]>0)
         $sql="INSERT INTO $results (distid,division,place,player1) VALUES ('$distid','singles1','$place','$singles1[$i]')";
      else
         $sql="UPDATE $results SET player1='$singles1[$i]' WHERE id='$row[id]'";
      $result=mysql_query($sql);
      //#2 SINGLES
      $sql="SELECT * FROM $results WHERE distid='$distid' AND division='singles2' AND place='$place'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0 && $singles2[$i]>0)
         $sql="INSERT INTO $results (distid,division,place,player1) VALUES ('$distid','singles2','$place','$singles2[$i]')";
      else
         $sql="UPDATE $results SET player1='$singles2[$i]' WHERE id='$row[id]'";
      $result=mysql_query($sql);
      //#1 DOUBLES
      $sql="SELECT * FROM $results WHERE distid='$distid' AND division='doubles1' AND place='$place'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $temp=split(";",$doubles1[$i]); $player1=$temp[0]; $player2=$temp[1];
      if(mysql_num_rows($result)==0 && $doubles1[$i]!=0)
         $sql="INSERT INTO $results (distid,division,place,player1,player2) VALUES ('$distid','doubles1','$place','$player1','$player2')";
      else
         $sql="UPDATE $results SET player1='$player1', player2='$player2' WHERE id='$row[id]'";
      $result=mysql_query($sql);
      //#2 DOUBLES
      $sql="SELECT * FROM $results WHERE distid='$distid' AND division='doubles2' AND place='$place'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $temp=split(";",$doubles2[$i]); $player1=$temp[0]; $player2=$temp[1];
      if(mysql_num_rows($result)==0 && $doubles2[$i]!=0)
         $sql="INSERT INTO $results (distid,division,place,player1,player2) VALUES ('$distid','doubles2','$place','$player1','$player2')";
      else
         $sql="UPDATE $results SET player1='$player1', player2='$player2' WHERE id='$row[id]'";
      $result=mysql_query($sql);
   }
   $teamscores=ereg_replace("\r\n","<br>",$teamscores);
   $teamscores=addslashes($teamscores);
   $sql="UPDATE $db_name2.$districts SET teamscores='$teamscores' WHERE id='$distid'";
   $result=mysql_query($sql);
   //See if results have been submitted to website alread
   $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(($row[resultssubmitted]==0 && $complete=='x') || $row[resultssubmitted]>0)	//UPDATE time submitted
   {
      $sql="UPDATE $db_name2.$districts SET resultssubmitted='".time()."' WHERE id='$distid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

if(GetLevel($session)==1)
   echo "<br><a href=\"".$sport."districts.php?session=$session&distid=$distid\">".$sportname." District Results Main Menu</a><br><br>";
else
   echo "<br><a href=\"host_teg.php?school_ch=$school_ch&session=$session\">".$sportname." Host Main Menu</a><br><br>";
echo "<form method=post name=resultsform action=\"te_gdistresults.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"distid\" value=\"$distid\">";	//FOR LEVEL 1 USER
if($gender=="M") $hisher="his";
else $hisher="her";
echo "<table cellspacing=4 cellpadding=10><caption><b>".$sportname." District $class-$district Results:</b>";
if($save && $complete=='x')
   echo "<tr align=center><td colspan=2><div class='alert' style='width:600px;font-size:9pt;'><b>You submitted results for this district on ".date("m/d/y",$row[resultssubmitted])." at ".date("g:ia T",$row[resultssubmitted]).".</b><br><br><b>ANY CHANGES YOU MAKE</b> from now on will automatically show up on the NSAA Website under the results for this district.  [<a href=\"/teg.php\" target=\"_blank\">Preview NSAA Girls Tennis Page</a>]</div></td></tr>";
else if($save)
   echo "<tr align=center><td colspan=2><div class='alert' style='width:400px;font-size:9pt;text-align:center;'>Your changes have been saved below.</div></td></tr>";
echo "<br>";
echo "</b></caption>";

echo "<tr align=left valign=top>";

//#1 SINGLES:
echo "<td><b>#1 SINGLES:</b><br><br>";
$sql="SELECT t1.first,t1.last,t1.semesters,t2.* FROM eligibility AS t1,$sport AS t2 WHERE t1.id=t2.player1 AND t2.player1>0 AND (sid='3' OR ";
for($i=0;$i<count($sids);$i++)
{
   $sql.="t2.sid='".trim($sids[$i])."' OR ";
}
$sql=substr($sql,0,strlen($sql)-4).") AND t2.division='singles1'";
$result=mysql_query($sql);
$ix=0; $singles=array();
while($row=mysql_fetch_array($result))
{
   $singles[id][$ix]=$row[player1];
   $singles[name][$ix]="$row[first] $row[last] - ".GetSchoolName($row[sid],$sport,GetFallYear($sport))." (".GetRecord($sport,'singles1','Varsity',$row[player1],0).")";
   $ix++;
}

for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT * FROM ".$sport."distresults WHERE distid='$distid' AND division='singles1' AND place='$place' ORDER BY place";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$place) <select name=\"singles1[$ix]\"><option value=\"0\">~</option>";
   for($i=0;$i<count($singles[id]);$i++)
   {
      echo "<option value=\"".$singles[id][$i]."\"";
      if($row[player1]==$singles[id][$i]) echo " selected";
      echo ">".$singles[name][$i]."</option>";
   }
   echo "</select><br>";
}
echo "</td>";

//#2 SINGLES:
echo "<td><b>#2 SINGLES:</b><br><br>";
$sql="SELECT t1.first,t1.last,t1.semesters,t2.* FROM eligibility AS t1,$sport AS t2 WHERE t1.id=t2.player1 AND t2.player1>0 AND (sid='3' OR ";
for($i=0;$i<count($sids);$i++)
{
   $sql.="t2.sid='".trim($sids[$i])."' OR ";
}
$sql=substr($sql,0,strlen($sql)-4).") AND t2.division='singles2'";
$result=mysql_query($sql);
$ix=0; $singles=array();
while($row=mysql_fetch_array($result))
{
   $singles[id][$ix]=$row[player1];
   $singles[name][$ix]="$row[first] $row[last] - ".GetSchoolName($row[sid],$sport,GetFallYear($sport))." (".GetRecord($sport,'singles2','Varsity',$row[player1],0).")";
   $ix++;
}

for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT * FROM ".$sport."distresults WHERE distid='$distid' AND division='singles2' AND place='$place' ORDER BY place";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$place) <select name=\"singles2[$ix]\"><option value=\"0\">~</option>";
   for($i=0;$i<count($singles[id]);$i++)
   {
      echo "<option value=\"".$singles[id][$i]."\"";
      if($row[player1]==$singles[id][$i]) echo " selected";
      echo ">".$singles[name][$i]."</option>";
   }
   echo "</select><br>";
}
echo "</td>";
echo "</tr>";

echo "<tr align=left valign=top>";

//#1 DOUBLES:
echo "<td><b>#1 DOUBLES:</b><br><br>";
$sql="SELECT t1.first,t1.last,t1.semesters,t2.* FROM eligibility AS t1,$sport AS t2 WHERE t1.id=t2.player1 AND t2.player1>0 AND (sid='3' OR ";
for($i=0;$i<count($sids);$i++)
{
   $sql.="t2.sid='".trim($sids[$i])."' OR ";
}
$sql=substr($sql,0,strlen($sql)-4).") AND t2.division='doubles1'";
$result=mysql_query($sql);
$ix=0; $doubles=array();
while($row=mysql_fetch_array($result))
{
   $doubles[id][$ix]=$row[player1].";".$row[player2];
   $doubles[name][$ix]="$row[first] $row[last], ";
   $sql2="SELECT * FROM eligibility WHERE id='$row[player2]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $doubles[name][$ix].="$row2[first] $row2[last] - ".GetSchoolName($row[sid],$sport,GetFallYear($sport))." (".GetRecord($sport,'doubles1','Varsity',$row[player1],$row[player2]).")";
   $ix++;
}

for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT * FROM ".$sport."distresults WHERE distid='$distid' AND division='doubles1' AND place='$place' ORDER BY place";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$place) <select name=\"doubles1[$ix]\"><option value=\"0\">~</option>";
   for($i=0;$i<count($doubles[id]);$i++)
   {
      echo "<option value=\"".$doubles[id][$i]."\"";
      if("$row[player1];$row[player2]"==$doubles[id][$i]) echo " selected";
      echo ">".$doubles[name][$i]."</option>";
   }
   echo "</select><br>";
}
echo "</td>";
//#2 DOUBLES:
echo "<td><b>#2 DOUBLES:</b><br><br>";
$sql="SELECT t1.first,t1.last,t1.semesters,t2.* FROM eligibility AS t1,$sport AS t2 WHERE t1.id=t2.player1 AND t2.player1>0 AND (sid='3' OR ";
for($i=0;$i<count($sids);$i++)
{
   $sql.="t2.sid='".trim($sids[$i])."' OR ";
}
$sql=substr($sql,0,strlen($sql)-4).") AND t2.division='doubles2'";
$result=mysql_query($sql);
$ix=0; $doubles=array();
while($row=mysql_fetch_array($result))
{
   $doubles[id][$ix]=$row[player1].";".$row[player2];
   $doubles[name][$ix]="$row[first] $row[last], "; 
   $sql2="SELECT * FROM eligibility WHERE id='$row[player2]'"; 
   $result2=mysql_query($sql2); 
   $row2=mysql_fetch_array($result2); 
   $doubles[name][$ix].="$row2[first] $row2[last] - ".GetSchoolName($row[sid],$sport,GetFallYear($sport))." (".GetRecord($sport,'doubles2','Varsity',$row[player1],$row[player2]).")";
   $ix++;
}

for($ix=0;$ix<$max;$ix++)
{
   $place=$ix+1;
   $sql="SELECT * FROM ".$sport."distresults WHERE distid='$distid' AND division='doubles2' AND place='$place' ORDER BY place";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$place) <select name=\"doubles2[$ix]\"><option value=\"0\">~</option>";
   for($i=0;$i<count($doubles[id]);$i++)
   {
      echo "<option value=\"".$doubles[id][$i]."\"";
      if("$row[player1];$row[player2]"==$doubles[id][$i]) echo " selected";
      echo ">".$doubles[name][$i]."</option>";
   }
   echo "</select><br>";
}
echo "</td>";

echo "</tr>";
//TEAM SCORES
$sql="SELECT teamscores,resultssubmitted FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<tr align=center><td colspan=2><table cellspacing=0 cellpadding=0 class=nine><tr align=left><td><b>Team Scores:</b><br>Enter like:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Place School, 60<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Second Place School, 49<br>&nbsp;&nbsp;&nbsp;&nbsp;...</td></tr>";
echo "<tr align=center><td colspan=2><textarea name=\"teamscores\" id=\"teamscores\" cols=40 rows=10>".ereg_replace("<br>","\r\n",$row[teamscores])."</textarea></td></tr>";
echo "</table></td></tr>";
if($row[resultssubmitted]==0)	//HAVE NOT SUBMITTED RESULTS BEFORE
{
   echo "<tr align=center><td colspan=2><div class='alert' style='width:600px;font-size:9pt;'><b>ARE YOUR DISTRICT RESULTS COMPLETED IN FULL??</b><br><input type=checkbox name=\"complete\" value=\"x\"> <B>CHECK HERE</b> if your District Results (above) are COMPLETE and ACCURATE.  Then click \"Save Results\" below so that these results can be posted to the NSAA Website.</div></td></tr>";
}
else	//HAVE SUBMITTED RESULTS BEFORE
{
   echo "<tr align=center><td colspan=2><div class='alert' style='width:600px;font-size:9pt;'><b>You submitted results for this district on ".date("m/d/y",$row[resultssubmitted])." at ".date("g:ia T",$row[resultssubmitted]).".</b><br><br><b>ANY CHANGES YOU MAKE</b> from now on will automatically show up on the NSAA Website under the results for this district.  [<a href=\"/teg.php\" target=\"_blank\">Preview NSAA Girls Tennis Page</a>]</div></td></tr>";
}
echo "<tr align=center><td colspan=2><input type=submit name=\"save\" value=\"Save Results\"></td></tr>";

echo "</table>";
echo "</form>";
echo $end_html;
?>
