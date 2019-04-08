<?php
/*******************************************************
sanctions.php

Public link to show all approved sanctioned events

Created: 4/6/10
Author: Ann Gaffigan
*********************************************************/

require '../functions.php';
require '../variables.php';
require 'sanctionvariables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

echo $init_html."<table width='100%'><tr align=center><td>";

echo "<table class=nine cellspacing=0 cellpadding=3>";
echo "<caption><img src=\"../officials/nsaacontract.png\" border=0><h2>NSAA Sanctioned Activities:</h2><p style='text-align:left;'><i>The following activities have been sanctioned by the Nebraska 
School Activities Association for member school participation. </i></p></caption>";
$spacts=array_merge($sanctionsp,$sanctionact);
$spacts2=array_merge($sanctionsp2,$sanctionact2);
for($i=0;$i<count($spacts);$i++)
{
   if(!ereg("Season",$spacts[$i]))
   {
      echo "<tr align=left><td colspan=3><h3 style='margin:10px'>$spacts[$i]</h3></td></tr>";
      if($i<count($sanctionsp))	//INTERSTATE/NATIONAL ATHLETIC EVENTS
      {
         $sql="SELECT * FROM interstatesanctions WHERE enddate>=CURDATE() AND sport='$spacts2[$i]' AND NSAAfinal>1 ORDER BY startdate,enddate,eventname";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<tr align=left><td>$row[eventname], hosted by $row[school]</td><td>";
            $start=split("-",$row[startdate]);
            $end=split("-",$row[enddate]);
            echo "$start[1]/$start[2]";
            if($start[0]!=$end[0]) echo "/$start[0]";
            echo " - $end[1]/$end[2]/$end[0]";
            echo "</td><td>Sanctioned ".date("m/d/Y",$row[NSAAfinal])."</td></tr>";
         }
         $sql="SELECT * FROM internationalsanctions WHERE enddate>=CURDATE() AND sport='$spacts2[$i]' AND NSAAfinal>1 ORDER BY startdate,enddate,eventname";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<tr align=left><td>$row[eventname], hosted by $row[school]</td><td>";
            $start=split("-",$row[startdate]);
            $end=split("-",$row[enddate]);
            echo "$start[1]/$start[2]";
            if($start[0]!=$end[0]) echo "/$start[0]";
            echo " - $end[1]/$end[2]/$end[0]";
            echo "</td><td>Sanctioned ".date("m/d/Y",$row[NSAAfinal])."</td></tr>";
         }
      }
      else
      {
         $sql="SELECT * FROM interstatefasanctions WHERE enddate>=CURDATE() AND sport='$spacts2[$i]' AND NSAAfinal>1 ORDER BY startdate,enddate,eventname";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<tr align=left><td>$row[eventname], hosted by $row[school]</td><td>";
            $start=split("-",$row[startdate]);
            $end=split("-",$row[enddate]);
            echo "$start[1]/$start[2]";
            if($start[0]!=$end[0]) echo "/$start[0]";
            echo " - $end[1]/$end[2]/$end[0]";
            echo "</td><td>Sanctioned ".date("m/d/Y",$row[NSAAfinal])."</td></tr>";
         }
      }
      $sql="SELECT * FROM oossanctionedevents WHERE enddate>=CURDATE() AND sport='$spacts2[$i]' ORDER BY startdate,enddate,eventname";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<tr align=left><td>$row[eventname], $row[city] $row[state] $row[country]</td><td>";
         $start=split("-",$row[startdate]);
	 $end=split("-",$row[enddate]);
	 echo "$start[1]/$start[2]";
	 if($start[0]!=$end[0]) echo "/$start[0]";
	 echo " - $end[1]/$end[2]/$end[0]";
	 $date=split("-",$row[sanctiondate]);
	 echo "</td><td>Sanctioned $date[1]/$date[2]/$date[0]</td></tr>";
      }
   }
   else
      echo "<tr align=center><td colspan=3><br><h2 style='background-color:#f0f0f0;border:#808080 1px solid;margin:5px;padding:10px;text-align:center;width:100%;'>$spacts[$i]</h2></td></tr>";
}
echo "</table>";
echo "<br><a href=\"javascript:window.close();\">Close Window</a>";
echo $end_html;
?>
