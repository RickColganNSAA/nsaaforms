<?php
/********************************************
sanctionslist.php

AD's main menu to administer submitted
applications for sanction of interstate/
international athletic/fine arts events

Created 12/14/09
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
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$school=GetSchool($session);
$school2=addslashes($school);

echo $init_html;
echo $header;

if($delete && $table)
{
   $sql="DELETE FROM $table WHERE id='$delete'";
   $result=mysql_query($sql);
   $eventtype=$table;
   echo "<br><div class=alert style='text-align:center;'>Your application for sanction has been deleted.</div><br>";
}

echo "<br><table cellspacing=3 cellpadding=3><caption><b><u>Applications for Sanctions of Interstate/International Athletic/Fine Arts Events MAIN MENU</b></u><br>";
if($eventtype=='' || !$eventtype) $eventtype=$eventtypetables[0];
echo "<br><form method=post action=\"sanctionslist.php\"><div class='helpbig'>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<b>SELECT TYPE OF SANCTIONED EVENT:</b> <select onChange=\"submit();\" name=\"eventtype\"><option value=''>Select Type of Sanctioned Event</option>";
for($i=0;$i<count($eventtypetables);$i++)
{
   echo "<option value=\"$eventtypetables[$i]\"";
   if($eventtype==$eventtypetables[$i]) { echo " selected"; $eventname=$eventtypenames[$i]; }
   echo ">$eventtypenames[$i]</option>";
}
echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\"></div></form>";
echo "</caption>";

$formname=substr($eventtype,0,strlen($eventtype)-1);	//Take off the "s" (yes very janky)
$eventnamesingular=substr($eventname,0,strlen($eventname)-1); //Take off 's'

//GET DATE RANGE FOR THIS SCHOOL YEAR
$month=date("m"); $year=date("Y");                
if($month<6)                
   $year1=$year-1;               
else
   $year1=$year;                
$year2=$year1+1;

/****** THIS SCHOOL'S SANCTIONS ******/
echo "<tr align=center><td><br><a href=\"$formname.php?session=$session\">Fill Out a NEW Application for Sanction of an $eventnamesingular</a><br>";
//APPS STARTED/EDITED BUT NOT SUBMITTED YET
if(!$sort0 || $sort0=='') $sort0="startdate ASC";
$sql="SELECT * FROM $eventtype WHERE submitted=0 AND school='$school2' AND startdate>='$year1-08-01' ORDER BY $sort0";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<br><table width='700px' class='nine' cellspacing=0 cellpadding=3><caption><p style='border:#000000 1px dotted;padding:5px;background-color:#fafda2;'>Applications for Sanctions of <b>".strtoupper($eventname)."</b> Your School has <b><u>BEGUN</b></u> but <b><u>NOT SUBMITTED</b></u>:</p>";
   echo "<p><b><i>Once you have completed the following applications, make sure to <u>SUBMIT THEM TO THE NSAA</u>.</b><br>To delete an application, click \"Edit\" and then click the \"Delete this application\" link at the top of the screen.</caption>";
   echo "<tr align=center><td><ul style='text-align:left;'>";
   while($row=mysql_fetch_array($result))
   {
      if(trim($row[eventname])=="") $row[eventname]="??";
      echo "<li style='font-size:9pt;'><b>Event Name:</b> $row[eventname], <b>Event Date(s):</b>&nbsp;";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "&nbsp;&nbsp;<a href=\"$formname.php?session=$session&appid=$row[id]&edit=1\">Edit</a></li>";
   }
   echo "</ul></td></tr></table>";
}

//SUBMITTED APPS AWAITING ACTION
echo "<br><br><table width='800px' class='nine' cellspacing=0 cellpadding=3 frame=all rules=all class='outlined'><caption><p style='border:#000000 1px dotted;padding:5px;background-color:#fafda2;'>Applications for Sanctions of <b>".strtoupper($eventname)."</b> Your School has <b><u>SUBMITTED</b></u>:</p><br></caption>";
echo "<tr align=center bgcolor='#f0f0f0'><td colspan=4><b>Submitted Applications <u>AWAITING ACTION</u> by the NSAA:</b></td></tr>";
if(!$sort1 || $sort1=='') $sort1="submitted DESC";
$sql="SELECT * FROM $eventtype WHERE submitted>0 AND (NSAAfinal=0 OR NSAAapproved=0) AND school='$school2' AND startdate>='$year1-08-01' ORDER BY $sort1";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "<tr align=center><td colspan=4>[There are no submitted applications awaiting action.]</td></tr>";
else
{
   echo "<tr align=center>";
   $arrow=""; 
   if($sort1=="submitted DESC")
   {
      $thissort="submitted ASC";
      $arrow="arrowup.png";
   }
   else if($sort1=="submitted ASC")
   {
      $thissort="submitted DESC";
      $arrow="arrowdown.png";
   }
   else
      $thissort="submitted ASC";
   echo "<td><a href=\"sanctionslist.php?session=$session&sort1=$thissort\">Date Submitted";
   if($arrow!='') echo "<img src=\"../$arrow\" width=\"15px\">";
   echo "</a></td>";
   echo "<td><b>Event Information</b></td><td><b>Invited States</b></td><td><b>Status</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left valign=top>";
      echo "<td>".date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted])."</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      //GET INVITED STATES
      $sql2="SELECT DISTINCT state FROM ".$formname."_invitees WHERE appid='$row[id]' ORDER BY state";
      $result2=mysql_query($sql2);
      $statestr=""; 
      while($row2=mysql_fetch_array($result2))
      {
	 $statestr.=$row2[state].", ";
      }
      if($statestr!='') $statestr=substr($statestr,0,strlen($statestr)-2);
      else 	//No states?? Give NSAA a link to edit this incomplete application
         $statestr="NONE INDICATED!";
      echo $statestr."</td>";
      //GET STATUS OF APPLICATION
      $invitees=split(", ",$statestr);	//CHECK IF THESE STATES HAVE BEEN MARKED AS APPROVING THIS APP
      $approvedstr=""; $emailedstr=""; 
      for($i=0;$i<count($invitees);$i++)
      {
	 $field=$invitees[$i]."approved";
	 //if($row[$field]==0) --> NSAA HASN'T MARKED THAT THEY SENT THIS STATE THEIR EMAIL w/ PASSCODE SO THEY CAN LOGIN AND APPROVE APP
	 if($row[$field]==1)	//NSAA HAS SENT THIS STATE EMAIL w/ PASSCODE BUT STATE HAS NOT APPROVED APP YET
            $emailedstr.=$invitees[$i].", ";
         else if($row[$field]>0)	//THIS STATE HAS LOGGED IN AND APPROVED THIS APP
	    $approvedstr.=$initees[$i].", ";
      }
      echo "<td>Awaiting Sanction by Associations of Invited Schools AND NSAA<br><a href=\"$formname.php?session=$session&appid=$row[id]\">View the Application</a></td>";
      echo "</tr>"; 
   }
}
echo "</table><br>";

//APPLICTIONS THAT HAVE HAD ACTION TAKEN BY NSAA
echo "<br><table width='800px' class='nine' cellspacing=0 cellpadding=3 frame=all rules=all class='outlined'>";
echo "<tr align=center bgcolor='#f0f0f0'><td colspan=5><b>Your Submitted Applications that have been <u>ACTED UPON</u> by the NSAA:</b></td></tr>";
if(!$sort1 || $sort1=='') $sort1="submitted DESC";
$sql="SELECT * FROM $eventtype WHERE submitted>0 AND NSAAfinal>0 AND NSAAapproved>0 AND school='$school2' AND startdate>='$year1-08-01' ORDER BY $sort1";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "<tr align=center><td colspan=5>[There are no applications that have been acted upon.]</td></tr>";
else
{
   echo "<tr align=center>";
   $arrow="";
   if($sort1=="NSAAfinal DESC")
   {
      $thissort="NSAAfinal ASC";
      $arrow="arrowup.png";
   }
   else if($sort1=="NSAAfinal ASC")
   {
      $thissort="NSAAfinal DESC";
      $arrow="arrowdown.png";
   }
   else
      $thissort="NSAAfinal ASC";
   echo "<td><a href=\"sanctionslist.php?session=$session&sort1=$thissort\">Date of Action by NSAA";
   if($arrow!='') echo "<img src=\"../$arrow\" width=\"15px\">";
   echo "</a></td>";
   $arrow="";
   if($sort1=="school DESC")
   {
      $thissort="school ASC";
      $arrow="arrowup.png";
   }
   else if($sort1=="school ASC")
   {
      $thissort="school DESC";
      $arrow="arrowdown.png";
   }
   else
      $thissort="school ASC";
   echo "<td><a href=\"sanctionslist.php?session=$session&sort1=$thissort\">Submitting School";
   if($arrow!='') echo "<img src=\"../$arrow\" width=\"15px\">";
   echo "</a></td>";
   echo "<td><b>Event Information</b></td><td><b>Invited<br>States</b></td><td><b>Action</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left valign=top>";
      echo "<td>".date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted])."</td>";
      echo "<td>$row[school]</td>";
      echo "<td>$row[eventname]<br>";
      $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
      if($row[startdate]==$row[enddate])
         echo "$start[1]/$start[2]/$start[0]";
      else
         echo "$start[1]/$start[2]/$start[0] - $end[1]/$end[2]/$end[0]";
      if(trim($row[eventtime])!='') echo " at $row[eventtime]";
      echo "</td><td>";
      //GET INVITED STATES
      $sql2="SELECT DISTINCT state FROM ".$formname."_invitees WHERE appid='$row[id]' ORDER BY state";
      $result2=mysql_query($sql2);
      $statestr="";
      while($row2=mysql_fetch_array($result2))
      {
         $statestr.=$row2[state].", ";
      }
      if($statestr!='') $statestr=substr($statestr,0,strlen($statestr)-2);
      else      //No states?? Give NSAA a link to edit this incomplete application
         $statestr="NONE INDICATED!";
      echo $statestr."</td>";
      //ACTION TAKEN
      echo "<td";
      if($row[action]=="Do Not Sanction Event" || $row[action]=="No Jurisdiction")   //RED 
         echo " style=\"background-color:#ff0000;color:#ffffff;\"";
      else
	 echo " style=\"background-color:#fafda2;\"";
      echo "><b>$row[action]</b>&nbsp;&nbsp;<i>$row[nojurisdiction]</i><br>$row[comments]<br>";
      echo "<a href=\"$formname.php?session=$session&appid=$row[id]\">View Application</a><br>";
      echo "<a href=\"$formname.php?session=$session&appid=$row[id]&pdf=1\" target=\"_blank\">Download PDF</a></td>";
      echo "</tr>";
   }
}
echo "</table>";
echo "</td></tr>";
/****** END SANCTIONS ******/



echo "</td></tr></table>";

echo $end_html;
?>
