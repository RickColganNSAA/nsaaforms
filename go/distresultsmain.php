<?php
/***************************************
distresultsmain.php
NSAA Admin Main Menu for GO District Results
Created 9/19/12
Author: Ann Gaffigan
****************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

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

if(!$sport) $sport='go_g';
$sport2=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$districts=$sport."districts";
$indytable=$sport2."distresults_indy";
$teamtable=$sport2."distresults_team";
$schtable=$sport."school";

if($reset)
{
   $sql="DELETE FROM $indytable WHERE distid='$reset'";
   $result=mysql_query($sql);
   $sql="DELETE FROM $teamtable WHERE distid='$reset'";
   $result=mysql_query($sql);
   $sql="UPDATE $db_name2.$districts SET resultssubmitted=0,indyct=0,teamct=0 WHERE id='$reset'";
   $result=mysql_query($sql);
}
if($unlock)
{
   $sql="UPDATE $db_name2.$districts SET resultssubmitted=0 WHERE id='$unlock'";
   $result=mysql_query($sql);
}

echo $init_html.$header."<br>";

echo "<table cellspacing=0 cellpadding=5><caption><b>$sportname District Results Main Menu:</b></caption>";
echo "<tr align=left><td><ul>";

//EXPORTS: 
echo "<li><b>REPORTS & EXPORTS:</b><ul>";
	//PROGRAM EXPORT
	echo "<li>Program Exports:&nbsp;&nbsp;&nbsp;";
	$sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
 	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
	   $class=$row['class'];
	   echo "<a href=\"programexport.php?session=$session&sport=$sport&class=$class\" target=\"_blank\">Class $class</a>&nbsp;&nbsp;&nbsp";
        }
	echo "</li>";
	//TEE TIME EXPORT - SORTED BY SCORE
	echo "<li>Tee Time Exports (sorted by District Score):&nbsp;&nbsp;&nbsp;";
        $sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
           $class=$row['class'];
           echo "<a href=\"teetimeexport.php?session=$session&sport=$sport&class=$class\">Class $class</a>&nbsp;&nbsp;&nbsp";
        }
        echo "</li>";
	//SCORE SHEET EXPORT - SIMILAR TO PROGRAM BUT COACH UNDER STUDENTS
        echo "<li>Score Sheet Exports:&nbsp;&nbsp;&nbsp;";
        $sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
           $class=$row['class'];
           echo "<a target=\"_blank\" href=\"scoresheetexport.php?session=$session&sport=$sport&class=$class\">Class $class</a>&nbsp;&nbsp;&nbsp";
        }
        echo "</li>";
	//LEADERBOARD EXPORT - Excel: Name, Grade, School
        echo "<li>Leaderboard Exports (Name, Grade, School):&nbsp;&nbsp;&nbsp;";
        $sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
           $class=$row['class'];
           echo "<a href=\"leaderboardexport.php?session=$session&sport=$sport&class=$class\">Class $class</a>&nbsp;&nbsp;&nbsp";
        }
        echo "</li>";
        //NGA RTS
        echo "<li>NGA RTS Exports:&nbsp;&nbsp;&nbsp;";
        $sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
           $class=$row['class'];
           echo "<a href=\"ngartsexport.php?session=$session&sport=$sport&class=$class\">Class $class</a>&nbsp;&nbsp;&nbsp";
        }
        echo "</li>";
	//PARTICIPATION NUMBERS
        echo "<li>State Participation Numbers:&nbsp;&nbsp;&nbsp;";
        $sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
           $class=$row['class'];
           echo "<a target=\"_blank\" href=\"partnumbers.php?session=$session&sport=$sport&class=$class\">Class $class</a>&nbsp;&nbsp;&nbsp";
        }
        echo "</li>";
echo "</ul></li>";

//TABLE OF DISTRICTS & WHETHER OR NOT THEY'VE SUBMITTED RESULTS
echo "<li><b>District Results:</b><br><br>";
	echo "<table cellpadding=5 cellspacing=0 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\">";
	echo "<tr align=center><td>DISTRICT</td><td>DATE</td><td>HOST</td><td>RESULTS</td></tr>";
$sql="SELECT * FROM $db_name2.$districts WHERE type='District' ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td align=center>$row[class]-$row[district]</td>";
   $date=explode("-",$row[dates]);
   echo "<td align=center>$date[1]/$date[2]</td>";
   echo "<td><p><b>$row[hostschool]</b><br>$row[director]<br><a href=\"mailto:$row[email]\" class='small'>$row[email]</a></p><p>Site: $row[site]</p></td>";
   echo "<td>";
   if($row[resultssubmitted]>0)
   {
      echo "<p><label class='green'>RESULTS SUBMITTED</label> on ".date("m/d/y",$row[resultssubmitted])." at ".date("g:ia",$row[resultssubmitted])."</p>";
      echo "<p><a href=\"districtresults.php?session=$session&distid=$row[id]&sport=$sport\">Edit Results</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
      echo "<a href=\"finaldistresults.php?session=$session&sport=$sport&distid=$row[id]\" target=\"_blank\">Preview Results/State Qualifiers Page</a>";
      echo "</p><p>[<a class=small href=\"distresultsmain.php?session=$session&sport=$sport&unlock=$row[id]\" onClick=\"return confirm('Are you sure you want to unlock these results for the District Director to edit?');\">Unlock for Corrections by District Director</a>]&nbsp;[<a class=small href=\"distresultsmain.php?session=$session&sport=$sport&reset=$row[id]\" onClick=\"return confirm('Are you sure you want to reset these results? This will remove all data entered for this district.');\">Reset</a>]</p>";
   }
   else
   {
      echo "<p><label class='red'>RESULTS NOT YET SUBMITTED</label></p>";
      echo "<p><a href=\"districtresults.php?session=$session&distid=$row[id]&sport=$sport\">Enter Results</a></p>";
   }
   echo "</td>";
   echo "</tr>";
}
echo "</table></li>";

echo "</ul></td></tr>";
echo "</table>";


echo $end_html;
?>
