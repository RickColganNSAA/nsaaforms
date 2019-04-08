<?php
/************************************
listmeetresults.php
List Meet Results for TE Meet 
Created 3/23/09
Author: Ann Gaffigan
************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

if(!$curresultid) $curresultid=0;

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
$sportname=GetActivityName($sport);
$meettable=$sport."meets";
$resultstable=$sport."meetresults";

if($delete=="yes" && $meetid>0)
{
   $sql="DELETE FROM $meettable WHERE id='$meetid'";
   $result=mysql_query($sql);
   $sql="DELETE FROM $resultstable WHERE meetid='$meetid'";
   $result=mysql_query($sql);

   echo $init_html."<table width='100%'><tr align=center><td><br><br>This meet has been deleted.<br><br><a class=small href='javascript:window.close();'>Close Window</a></td></tr></table>".$end_html;
   exit();
}

echo $init_html;
echo "<table width='100%'><tr align=center><td>";

echo "<br><form method=post name=resultsform action=\"listmeetresults.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"meetid\" id=\"meetid\" value=\"$meetid\">";
//RESULTS

      $sql="SELECT * FROM ".$sport."meetresults WHERE meetid='$meetid' ORDER BY division";
      $result=mysql_query($sql);
	$total=mysql_num_rows($result);
      $string="";
      while($row=mysql_fetch_array($result))
      {
         if(ereg("doubles",$row[division]))
         {
            $temp=split("doubles",$row[division]);
            $division="Doubles";
         }
         else
         {
            $temp=split("singles",$row[division]);
            $division="Singles";
         }
         $division="#".$temp[1]." ".$division;
         $string.="$row[id]<detail>$division<detail>";
         $player1=$row[player1]; $player2=$row[player2]; $player3=$row[player3]; $player4=$row[player4];
         $oppid2=$row[oppid2]; $oppid1=$row[oppid1];
         $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player1'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $string.=GetSchoolName($oppid1,$sport,date("Y")).": $row2[first] $row2[last] (".GetYear($row2[semesters]).")";
         if(ereg("double",$row[division]))
         {
            $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player2'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $string.=", $row2[first] $row2[last] (".GetYear($row2[semesters]).")";
         }
         $string.="<detail>$row[varsityjv1]<detail>";
         if($oppid2!="1000000000")
         {
            $sql2="SELECT school FROM ".$sport."school WHERE sid='$oppid2'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $string.="$row2[school]: ";
            $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player3'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $string.="$row2[first] $row2[last] (".GetYear($row2[semesters]).")";
            if(ereg("double",$row[division]))
            {
               $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$player4'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $string.=", $row2[first] $row2[last] (".GetYear($row2[semesters]).")";
            }
         }
         else
         {
            $string.="$row[oosschool]: ";
            $string.="$row[oosplayer1]";
            if(ereg("double",$row[division]))
               $string.=", $row[oosplayer2]";
         }
         $string.="<detail>$row[varsityjv2]<detail>";
         if($row[winnerid]==$oppid1) $winloss=GetSchoolName($oppid1,$sport,date("Y"));
         else $winloss=GetSchoolName($oppid2,$sport,date("Y"));
         $string.="$winloss<detail>$row[score]<detail>$row[oppid1]<result>";
      }
      $string=substr($string,0,strlen($string)-8);
      if(mysql_error()) $string=mysql_error();
      else if(mysql_num_rows($result)==0) $string="[No Results]";
      $results=$string;
$sql="SELECT * FROM $meettable WHERE id='$meetid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$start=split("-",$row[startdate]);
$end=split("-",$row[enddate]);
$meetname="$row[meetname] (";
if($row[startdate]==$row[enddate])
   $meetname.="$start[1]/$start[2] at&nbsp;";
else
   $meetname.="$start[1]/$start[2] - $end[1]/$end[2] at&nbsp;";
$meetname.="$row[meetsite])<br>";

$string="<table frame=box rules=cols style='border:#808080 1px solid;' cellspacing=0 cellpadding=3><caption><b>Meet Results for $meetname</b><br><a href=\"listmeetresults.php?session=$session&meetid=$meetid&sport=$sport&delete=yes\" onClick=\"return confirm('Are you sure you want to delete this meet and all of its results?  This action cannot be undone.');\">DELETE this meet and ALL of its results.</a><br><div class=help style='font-size:9pt;margin:5px;width:500px'><i>To <b>EDIT</b> results, close this window and select a school's entry form to edit its results for this meet.</i></div><br></caption><tr align=center><td><b>Division</b></td><td><b>Team 1, Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Team 2, Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Winner</b></td><td><b>Score</b></td></tr>";
$results=split("<result>",$results);
for($i=0;$i<count($results);$i++)
{
   if(($i%2)==0) $color="#E0E0E0";
   else $color="#FFFFFF";
   $details=split("<detail>",$results[$i]);
   if($details[0]==$curresultid) $color="#FAFAD2";
   $string.="<input type=hidden name=\"resultid[$i]\" value=\"$details[0]\"><tr align=left style=\"background-color:$color;\"><td>$details[1]</td><td>$details[2]</td><td>$details[3]</td><td>$details[4]</td><td>$details[5]</td><td>$details[6]</td><td>$details[7]</td></tr>";
}
$string.="</table>";
if($total==0)
   $string.="<br><br>[No results have been entered for this meet yet.  Please enter each match above.]";
echo $string;

echo "</div>";
echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
echo "</form>";
echo "<div id=\"loading\" style=\"display:none;\"></div>";
echo $end_html;
?>
