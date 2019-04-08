<?php
/******************************************
goweeklyresults.php
Regular Season Golf Tournament Results for the PUBLIC
by WEEK
Created 3/23/16
Author Ann Gaffigan
********************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=$sport."tourn";
$teamtbl=$tourntbl."team";
$indytbl=$tourntbl."indy";

//Link to tourney results: goliveresults.php?sport=$sport&tournid=$tournid

//HAS A WEEK BEEN SENT? IF NOT, FIND CURRENT WEEK OR WEEK 1 OF THE SEASON
if(!$week)
{
   $sql="SELECT YEARWEEK(tourndate) AS week FROM $tourntbl WHERE YEARWEEK(tourndate)=YEARWEEK(CURDATE())";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result))
      $week=$row['week'];
   else	//SEE IF WE ARE PAST THIS SEASON OR IF IT'S UP AHEAD
   {
      $sql="SELECT YEARWEEK(tourndate) FROM $tourntbl WHERE tourndate>CURDATE() ORDER BY tourndate LIMIT 1";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result))	//SEASON IS AHEAD (AT LEAST 1 TOURNEY IS)
         $week=$row[0];
      else	//JUST GET THE LAST WEEK OF THE TOURNEYS IN THE DB
      {
         $sql="SELECT YEARWEEK(tourndate) FROM $tourntbl ORDER BY tourndate DESC LIMIT 1";
         $result=mysql_query($sql);
         if($row=mysql_fetch_array($result))
            $week=$row[0];
      }
   }
} 
if(!$week)	//NOTHING IN THE DB AT THIS TIME
{
   echo $init_html."<table width=\"100%\"><tr align=center><td><br><h2><i>$sportname Schedules are not available at this time.</i></h2>".$end_html;
   exit();
}

//IF WE GET HERE, WE HAVE A $week AND THERE ARE TOURNAMENTS IN THE DB:

echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";
echo "<br><h1>$sportname Schedules & Results:</h1>";


//SHOW LIST OF WEEKS FOR THE TOURNAMENTS IN THE DATABASE:
$sql="SELECT DISTINCT YEARWEEK(t1.tourndate) AS week,t1.tourndate FROM $tourntbl AS t1, $teamtbl AS t2 WHERE t1.id=t2.tournid ORDER BY t1.tourndate";
$result=mysql_query($sql);
$curweek=0; $curmin=0; $curmax=0;
echo "<p><b>WEEKS: </b>";
while($row=mysql_fetch_array($result))
{
   if($row['week']!=$curweek)
   {
      if($curweek!=0)	//THIS WAS AN ACTUAL WEEK FROM THE DB, CLOSE IT UP
      {
         $range=date("M j",strtotime($curmin));
         if($curmax!=$curmin)	//THE WEEK HAD MORE THAN ONE TOURNAMENT DATE IN IT
            $range.=" - ".date("M j", strtotime($curmax));
         echo "<a class=\"small\" href=\"goweeklyresults.php?sport=$sport&week=$curweek\">$range</a>&nbsp;&nbsp;&nbsp;";
      }
      $curweek=$row['week']; $curmin=$row['tourndate']; 
   }
   $curmax=$row['tourndate'];
}
//Final week's link:
$range=date("M j",strtotime($curmin));
if($curmax!=$curmin) //THE WEEK HAD MORE THAN ONE TOURNAMENT DATE IN IT
   $range.=" - ".date("M j", strtotime($curmax));
echo "<a class=\"small\" href=\"goweeklyresults.php?sport=$sport&week=$curweek\">$range</a></p>";
echo "<a name='top'><br></a>";

//SHOW LIST OF TOURNAMENTS FOR THIS WEEK:

$sql="SELECT DISTINCT t1.*, t1.tourndate<=CURDATE() AS inthepast FROM $tourntbl AS t1, $teamtbl AS t2 WHERE t1.id=t2.tournid AND YEARWEEK(t1.tourndate)='$week' ORDER BY t1.tourndate ASC, t1.tournname ASC";
$result=mysql_query($sql);
$curdate=0;
echo "<div style=\"max-width:400px;text-align:left;\">";
while($row=mysql_fetch_array($result))
{
   if($curdate!=$row['tourndate'])
   {
      if($curdate!=0) echo "</ul><br>";
      $curdate=$row['tourndate'];
      echo "<h3>".date("l, F j, Y",strtotime($curdate)).":</h3><ul>";
   }
   echo "<li>";
   if($row['datesub']>0)
      echo "<a href=\"goliveresults.php?sport=$sport&tournid=$row[id]\">";
   echo "<b>$row[tournname]</b>";
   if($row['datesub']>0) echo "</a>";
   if($row['course']!='')  echo " ($row[course])";
   if($row['datesub']==0)
   {
      echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      if($row['inthepast']) echo "<span class=\"highlight\" style=\"color:red;\"><b>";
      echo "<i>tournament report not yet available</i>";
      if($row['inthepast']) echo "</b></span>";
      echo "</p>";
   }
   echo "</li>";
}
echo "</ul></div>";

echo "<br><br><a href=\"#top\">return to top &uarr;</a>";
echo $end_html;
?>
