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
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

if($delete && $table!='')
{
   $sql="DELETE FROM $table WHERE id='$delete'";
   $result=mysql_query($sql);
}
echo "<br><table cellspacing=3 cellpadding=3><caption><b>Applications for Sanctions of Interstate/International Athletic/Fine Arts Events MAIN MENU</b><br><br><a href=\"stateinfo.php?session=$session\">Manage State Associations' Contact Info</a><br><br>";
if($eventtype=='' || !$eventtype) $eventtype=$eventtypetables[0];
echo "<form method=post action=\"sanctionsadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "View Applications for Sanctions of <select onChange=\"submit();\" name=\"eventtype\"><option value=''>Select Type of Sanctioned Event</option>";
for($i=0;$i<count($eventtypetables);$i++)
{
   echo "<option value=\"$eventtypetables[$i]\"";
   if($eventtype==$eventtypetables[$i]) { echo " selected"; $eventname=$eventtypenames[$i]; }
   echo ">$eventtypenames[$i]</option>";
}
echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\"></form>";
echo "</caption>";

$formname=substr($eventtype,0,strlen($eventtype)-1);    //Take off the "s" (yes very janky)
$eventnamesingular=substr($eventname,0,strlen($eventname)-1); //Take off 's'

echo "<tr align=center><td>";
//SUBMITTED APPS AWAITING ACTION
echo "<br><table class='nine' cellspacing=0 cellpadding=3 frame=all rules=all class='outlined'><caption>Applications for Sanctions of <b>".strtoupper($eventname)."</b>";
if($delete && $table=='$eventtype')
   echo "<br><div class='alert' style='width:400px;'>The application has been deleted.</div><br>";
echo "</caption>";
echo "<tr align=center bgcolor='#fafda2'><td colspan=6><b>Submitted Applications Awaiting Action</b></td></tr>";
if(!$sort1 || $sort1=='') $sort1="submitted DESC";
$sql="SELECT * FROM $eventtype WHERE submitted>0 AND (NSAAapproved=0 OR NSAAfinal=0) ORDER BY $sort1";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "<tr align=center><td colspan=6>[There are no submitted applications awaiting action.]</td></tr>";
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
   echo "<td><a href=\"sanctionsadmin.php?session=$session&sort1=$thissort\">Date Submitted";
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
   echo "<td><a href=\"sanctionsadmin.php?session=$session&sort1=$thissort\">Submitting School";
   if($arrow!='') echo "<img src=\"../$arrow\" width=\"15px\">";
   echo "</a></td>";
   echo "<td><b>Event Information</b></td><td><b>Invited States</b></td><td><b>Status</b></td><td><b>Delete</b></td></tr>";
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
      else 	//No states?? Give NSAA a link to edit this incomplete application
         $statestr="NONE INDICATED!<br><a href=\"$formname.php?session=$session&appid=$row[id]&edit=1\">Edit this Application</a>";
      echo $statestr."</td>";
      //GET STATUS OF APPLICATION
      $invitees=split(", ",$statestr);	//CHECK IF THESE STATES HAVE BEEN MARKED AS APPROVING THIS APP
      $approvedstr=""; $emailedstr="";  $notnotifiedstr="";
      for($i=0;$i<count($invitees);$i++)
      {
	 $field=$invitees[$i]."approved";
	 //if($row[$field]==0) --> NSAA HASN'T MARKED THAT THEY SENT THIS STATE THEIR EMAIL w/ PASSCODE SO THEY CAN LOGIN AND APPROVE APP
	 if($row[$field]==1)	//NSAA HAS SENT THIS STATE EMAIL w/ PASSCODE BUT STATE HAS NOT APPROVED APP YET
            $emailedstr.=$invitees[$i].", ";
         else if($row[$field]>0)	//THIS STATE HAS LOGGED IN AND APPROVED THIS APP
	    $approvedstr.=$invitees[$i].", ";
	 else
	    $notnotifiedstr.=$invitees[$i].", ";
      }
      echo "<td";
      if(($approvedstr=='' && $emailedstr=='') || $notnotifiedstr!='')	//RED --> NSAA needs to send emails to these states
         echo " style=\"background-color:#ff0000;color:#ffffff;\"";
      else if($approvedstr!='' && $emailedstr=='' && $notnotifiedstr=='')	//GREEN --> ALL states have approved, just need final NSAA approval
	 echo " style=\"background-color:#00ff00;\"";
      else	//YELLOW --> States emailed, some have responded
	 echo " style=\"background-color:#fafda2;\"";
      echo ">App submitted by school<br>";	//This is a given
      if(($approvedstr=='' && $emailedstr=='') || $notnotifiedstr!='')	//RED -- NSAA HASN'T MARKED THAT THEY SENT EMAILS TO ALL STATES YET
	 echo "Waiting for NSAA to e-mail invited states<br>";
      if($notnotifiedstr!='')	//SOME STATES HAVE NOT BEEN NOTIFIED
      {
	 $notnotifiedstr=substr($notnotifiedstr,0,strlen($notnotifiedstr)-2);
	 echo "NSAA has NOT notified $notnotifiedstr.<br>";
      }
      if($emailedstr!='')	//THERE ARE SOME STATES THAT HAVE BEEN EMAILED BUT NOT RESPONDED YET
      {
  	 $emailedstr=substr($emailedstr,0,strlen($emailedstr)-2);
	 echo "NSAA e-mailed $emailedstr, AWAITING APPROVAL RESPONSE.<br>";
      }
      if($approvedstr!='')       //STATES THAT HAVE BEEN EMAILED AND HAVE RESPONDED 
      {
	 if($emailedstr!='' || ($notnotifiedstr!='' && $approvedstr!=''))	//SOME STATES HAVE RESPONDED, STILL WAITING ON OTHERS
	 {
            $approvedstr=substr($approvedstr,0,strlen($approvedstr)-2);
            echo "NSAA e-mailed $approvedstr, APPROVAL RESPONSE RECEIVED.<br>";
	 }
	 else if($notnotifiedstr=='')			//ALL HAVE RESPONDED, JUST WAITING ON NSAA
	    echo "All states have approved, awaiting final NSAA approval.<br>";
      }
      echo "<br><a href=\"$formname.php?session=$session&appid=$row[id]\">Take Action on this App</a></td>";
      echo "<td align=center><a href=\"sanctionsadmin.php?delete=$row[id]&table=$eventtype&session=$session\" onClick=\"return confirm('Are you sure you want to delete this submitted application?');\">X</a></td>";
      echo "</tr>"; 
   }
}
echo "</table><br><br>";

//APPLICTIONS THAT HAVE HAD ACTION TAKEN BY NSAA
echo "<br><table class='nine' cellspacing=0 cellpadding=3 frame=all rules=all class='outlined'>";
echo "<tr align=center bgcolor='#fafda2'><td colspan=5><b>Applications that have been ACTED UPON:</b></td></tr>";
if(!$sort1 || $sort1=='') $sort1="submitted DESC";
$sql="SELECT * FROM $eventtype WHERE submitted>0 AND NSAAfinal>0 AND NSAAapproved>0 ORDER BY $sort1";
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
   echo "<td><a href=\"sanctionsadmin.php?session=$session&sort1=$thissort\">Date of Action by NSAA";
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
   echo "<td><a href=\"sanctionsadmin.php?session=$session&sort1=$thissort\">Submitting School";
   if($arrow!='') echo "<img src=\"../$arrow\" width=\"15px\">";
   echo "</a></td>";
   echo "<td><b>Event Information</b></td><td><b>Invited<br>States</b></td><td width='250px'><b>Action</b></td></tr>";
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
         $statestr="NONE INDICATED!<br><a href=\"$formname.php?session=$session&appid=$row[id]&edit=1\">Edit this Application</a>";
      echo $statestr."</td>";
      //ACTION TAKEN
      echo "<td width='250px'";
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
/****** END SANCTIONS OF INTERSTATE ATHLETIC EVENTS ******/



echo "</td></tr></table>";

echo $end_html;
?>
