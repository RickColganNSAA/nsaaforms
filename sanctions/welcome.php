<?php
/********************************************
sanctionsadmin.php

NSAA main menu to administer submitted
applications for sanction of interstate/
international athletic/fine arts events

Created 11/24/09
Author: Ann Gaffigan
*********************************************/

require '../functions.php';
require '../variables.php';
require 'sanctionvariables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=7)
{
   header("Location:login.php?error=1");
   exit();
}

echo $init_html;
echo $header;
$state=GetActivity($session);
echo "<br><div class='content'>";

echo "<h3>Welcome, ".GetSchool($session)."!</h3>";

//SHOW THEM SANCTIONS THEY NEED TO RESPOND TO
echo "<table frame=all rules=all cellspacing=0 cellpadding=3 class='outlined' style='width:auto;margin:0 auto;'>";
echo "<caption><b>The following Applications are awaiting Action by YOUR ASSOCIATION:</b></caption>";
$appsct=0;
$sql="SELECT * FROM interstatesanctions WHERE ".$state."approved='1' AND NSAAfinal=0 ORDER BY sport,startdate,enddate";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr bgcolor='#f0f0f0' align=left><td colspan=5><b>INTERSTATE ATHLETIC EVENTS:</b></td></tr>";
   echo "<tr align=center><td><b>Sport</b></td>";
   echo "<td><b>Submitting School</b></td>";
   echo "<td><b>Event Information</b></td><td><b>$state Schools Invited</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $appsct++;
      echo "<tr align=left valign=top><td>".GetActivityName($row[sport])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      $sql2="SELECT * FROM interstatesanction_invitees WHERE appid=$row[id] AND state='$state' ORDER BY schoolname";
      $result2=mysql_query($sql2);
      $schools="";
      while($row2=mysql_fetch_array($result2))
      {
	 $schools.=$row2[schoolname].", ";
      }
      $schools=substr($schools,0,strlen($schools)-2);
      echo "$schools&nbsp;</td>";
      echo "<td style=\"background-color:#ff0000;color:#ffffff;\"";
      echo ">App submitted by school<br>";      //This is a given
      echo "AWAITING APPROVAL RESPONSE BY ".GetSchool($session);
      echo "<br><a href=\"interstatesanction.php?session=$session&appid=$row[id]\">Take Action on this App</a></td>";
      echo "</tr>";
   }
}
$sql="SELECT * FROM interstatefasanctions WHERE ".$state."approved='1' AND NSAAfinal=0 ORDER BY sport,startdate,enddate";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr bgcolor='#f0f0f0' align=left><td colspan=5><b>INTERSTATE FINE ARTS EVENTS:</b></td></tr>";
   echo "<tr align=center><td><b>Activity</b></td>";
   echo "<td><b>Submitting School</b></td>";
   echo "<td><b>Event Information</b></td><td><b>$state Schools Invited</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $appsct++;
      echo "<tr align=left valign=top><td>".GetActivityName($row[sport])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      $sql2="SELECT * FROM interstatefasanction_invitees WHERE appid=$row[id] AND state='$state' ORDER BY schoolname";
      $result2=mysql_query($sql2);
      $schools="";
      while($row2=mysql_fetch_array($result2))
      {
         $schools.=$row2[schoolname].", ";
      }
      $schools=substr($schools,0,strlen($schools)-2);
      echo "$schools&nbsp;</td>";
      echo "<td style=\"background-color:#ff0000;color:#ffffff;\"";
      echo ">App submitted by school<br>";      //This is a given
      echo "AWAITING APPROVAL RESPONSE BY ".GetSchool($session);
      echo "<br><a href=\"interstatefasanction.php?session=$session&appid=$row[id]\">Take Action on this App</a></td>";
      echo "</tr>";
   }
}
$sql="SELECT * FROM internationalsanctions WHERE ".$state."approved='1' ORDER BY sport,startdate,enddate";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr bgcolor='#f0f0f0' align=left><td colspan=5><b>INTERNATIONAL ATHLETIC EVENTS:</b></td></tr>";
   echo "<tr align=center><td><b>Sport</b></td>";
   echo "<td><b>Submitting School</b></td>";
   echo "<td><b>Event Information</b></td><td><b>$state Schools Invited</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $appsct++;
      echo "<tr align=left valign=top><td>".GetActivityName($row[sport])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      $sql2="SELECT * FROM internationalsanction_invitees WHERE appid=$row[id] AND state='$state' ORDER BY schoolname";
      $result2=mysql_query($sql2);
      $schools="";
      while($row2=mysql_fetch_array($result2))
      {
         $schools.=$row2[schoolname].", ";
      }
      $schools=substr($schools,0,strlen($schools)-2);
      echo "$schools&nbsp;</td>";
      echo "<td style=\"background-color:#ff0000;color:#ffffff;\"";
      echo ">App submitted by school<br>";      //This is a given
      echo "AWAITING APPROVAL RESPONSE BY ".GetSchool($session);
      echo "<br><a href=\"internationalsanction.php?session=$session&appid=$row[id]\">Take Action on this App</a></td>";
      echo "</tr>";
   }
}
if($appsct==0) echo "<tr align=center><td width='600px' colspan=5>[No applications are awaiting your action.]</td></tr>";
echo "</table><br>";

//SHOW THEM SANCTIONS THEY'VE RESPONDED TO
echo "<table frame=all rules=all cellspacing=0 cellpadding=3 class='outlined' style='width:auto;margin:0 auto;'>";
echo "<caption><b>The following Applications have been Acted Upon by YOUR ASSOCIATION:</b></caption>";
$appsct=0;
$sql="SELECT * FROM interstatesanctions WHERE ".$state."approved>1 ORDER BY ".$state."approved DESC";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#f0f0f0'><td colspan=5><b>INTERSTATE ATHLETIC EVENTS:</b></td></tr>";
   echo "<tr align=center><td><b>Sport</b></td><td><b>Submitting School</b></td>";
   echo "<td><b>Event Information</b></td><td><b>$state Schools Invited</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $appsct++;
      $stateapproved=$state."approved";
      echo "<tr align=left valign=top><td>".GetActivityName($row[sport])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      $sql2="SELECT * FROM interstatesanction_invitees WHERE appid=$row[id] AND state='$state' ORDER BY schoolname";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<b>$row2[schoolname]:</b> ";
         if($row2[action]=="Sanction Event") echo $row2[action];
         else if($row2[action]=="Do Not Sanction Event") echo "<font style=\"color:#ff0000;\">$row2[action]</font>";
         else echo "<font style=\"color:#0000ff;\">$row2[action]</font>";
         echo "<br>";
      }
      echo "&nbsp;</td>";
      if($row[NSAAfinal]==0)	//RED
      {
         echo "<td style=\"background-color:#ff0000;color:#ffffff;\"";
         echo ">App submitted by school<br>Action taken by ".GetSchool($session)." on ".date("m/d/y",$row[$stateapproved])."<br>";      //This is a given
         echo "AWAITING APPROVAL RESPONSE BY NSAA";
      }
      else
      {
         echo "<td style=\"background-color:#fafad2;\"";
         echo ">App submitted by school<br>Action taken by ".GetSchool($session)." on ".date("m/d/y",$row[$stateapproved])."<br>";      //This is a given
         echo "APPROVED BY THE NSAA";
      }
      echo "<br><a href=\"interstatesanction.php?session=$session&appid=$row[id]\">View this Application</a></td>";
      echo "</tr>";
   }
}
$sql="SELECT * FROM interstatefasanctions WHERE ".$state."approved>1 ORDER BY ".$state."approved DESC";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#f0f0f0'><td colspan=5><b>INTERSTATE FINE ARTS EVENTS:</b></td></tr>";
   echo "<tr align=center><td><b>Activity</b></td><td><b>Submitting School</b></td>";
   echo "<td><b>Event Information</b></td><td><b>$state Schools Invited</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $appsct++;
      echo "<tr align=left valign=top><td>".GetActivityName($row[sport])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      $sql2="SELECT * FROM interstatefasanction_invitees WHERE appid=$row[id] AND state='$state' ORDER BY schoolname";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<b>$row2[schoolname]:</b> ";
         if($row2[action]=="Sanction Event") echo $row2[action];
         else if($row2[action]=="Do Not Sanction Event") echo "<font style=\"color:#ff0000;\">$row2[action]</font>";
         else echo "<font style=\"color:#0000ff;\">$row2[action]</font>";
         echo "<br>";
      }
      echo "&nbsp;</td>";
      if($row[NSAAfinal]==0) //RED
      {
         echo "<td style=\"background-color:#ff0000;color:#ffffff;\"";
         echo ">App submitted by school<br>Action taken by ".GetSchool($session)." on ".date("m/d/y",$row[$stateapproved])."<br>";      //This is a given
         echo "AWAITING APPROVAL RESPONSE BY NSAA";
      }
      else
      {
         echo "<td style=\"background-color:#fafad2;\"";
         echo ">App submitted by school<br>Action taken by ".GetSchool($session)." on ".date("m/d/y",$row[$stateapproved])."<br>";      //This is a given
         echo "APPROVED BY THE NSAA";
      }
      echo "<br><a href=\"interstatefasanction.php?session=$session&appid=$row[id]\">View this Application</a></td>";
      echo "</tr>";
   }
}
$sql="SELECT * FROM internationalsanctions WHERE ".$state."approved>1 ORDER BY ".$state."approved DESC";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#f0f0f0'><td colspan=5><b>INTERNATIONAL ATHLETIC EVENTS:</b></td></tr>";
   echo "<tr align=center><td><b>Sport</b></td><td><b>Submitting School</b></td>";
   echo "<td><b>Event Information</b></td><td><b>$state Schools Invited</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $appsct++;
      echo "<tr align=left valign=top><td>".GetActivityName($row[sport])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      $sql2="SELECT * FROM internationalsanction_invitees WHERE appid=$row[id] AND state='$state' ORDER BY schoolname";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<b>$row2[schoolname]:</b> ";
         if($row2[action]=="Sanction Event") echo $row2[action];
         else if($row2[action]=="Do Not Sanction Event") echo "<font style=\"color:#ff0000;\">$row2[action]</font>";
         else echo "<font style=\"color:#0000ff;\">$row2[action]</font>";
         echo "<br>";
      }
      echo "&nbsp;</td>";
      if($row[NSAAfinal]==0) //RED
      {
         echo "<td style=\"background-color:#ff0000;color:#ffffff;\"";
         echo ">App submitted by school<br>Action taken by ".GetSchool($session)." on ".date("m/d/y",$row[$stateapproved])."<br>";      //This is a given
         echo "AWAITING APPROVAL RESPONSE BY NSAA";
      }
      else
      {
         echo "<td style=\"background-color:#fafad2;\"";
         echo ">App submitted by school<br>Action taken by ".GetSchool($session)." on ".date("m/d/y",$row[$stateapproved])."<br>";      //This is a given
         echo "APPROVED BY THE NSAA";
      }
      echo "<br><a href=\"internationalsanction.php?session=$session&appid=$row[id]\">View this Application</a></td>";
      echo "</tr>";
   }
}
if($appsct==0) echo "<tr align=center><td width='600px' colspan=5>[No applications have been acted upon by your association.]</td></tr>";
echo "</table><br>";

echo "</div>";
echo $end_html;
?>
