<?php
$sport='so';

require 'functions.php';
require 'variables.php';
$thisyear=GetSchoolYear(date("Y"),date("m"));

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level==4) $level=1;

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";

//Get disttimesid and type (stand-by or regular) for this contract
$sql="SELECT t1.id,t1.time FROM $disttimes AS t1,$contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$distid' AND t2.offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[time]=='standby') $type="standby";
else $type="state";
$disttimesid=$row[id];

if($submit)
{
   if($level!=1)
   {
      if(!$accept) $error=1;
      else
      {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND disttimesid='$disttimesid'";
         $result=mysql_query($sql);
      }
   }
   else if($level==1)
   {
      $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND disttimesid='$disttimesid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<br>";
echo "<form method=post action=\"sostatecontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
echo "</td></tr>";
$sql="SELECT t2.accept,t2.confirm,t2.post FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t2.offid='$offid' AND t2.disttimesid='$disttimesid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
if($error==1)
{
   echo "<tr align=center><td><font style=\"color:red\"><b>You must either accept or decline this contract.</b></font></td></tr>";
}
if($row[accept]=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo GetOffName($offid)." has ";
   echo "<b>accepted</b> the following contract.<br>";
   if($row[confirm]=='y')
   {
      echo "The NSAA has <b>confirmed</b> the following contract.";
   }
   else if($row[confirm]=='n')
   {
      echo "The NSAA has <b>rejected</b> the following contract.";
   }
   else if($level!=1)
   {
      echo "Please check back later to see if the NSAA has <b>confirmed</b> your contract.";
   }
   else
   {
      echo "The NSAA has not yet confirmed this contract.";
   }
}
else if($row[accept]=='n' && !$submit)
{
   if($level!=1)
      echo "<tr align=left><td>You have <b>declined</b> the following contract.<br>";
   else 
      echo "<tr align=left><td>This officials has <b>declined</b> the following contract.<br>";
   if($confirm=='y')
      echo "The NSAA has <b>acknowledged</b> this contract.<br>";
   else if($confirm=='')
      echo "The NSAA has <b>not yet acknowledged</b> this contract.<br>";
}
echo "<br><br></td></tr></table></td></tr>";

if($submit)
{
   if($level!=1)
   {
      if($accept=='y')
      {
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  More information will be provided soon."; 
	 echo "<br><br></td></tr></table></td></tr>";
      }
      else if($accept=='n')
      {
         echo "<tr align=center><td>This confirms that you are not accepting this contract.<br><br></td></tr>";
      }
   }
   else
   {
      if($confirm=='y' && $accept=='y')
      {
	 echo "<tr align=center><td>You have <b>confirmed</b> the following contract.<br><br></td></tr>";
      }
      else if($confirm=='y' && $accept=='n')
      {
	 echo "<tr align=center><td>You have <b>acknowledged</b> the following contract.<br><br></td></tr>";
      }
   }
}

//Date and Official's Name
echo "<tr align=left><td><b>".date("F j, Y")."</b></td></tr>";
echo "<tr align=left><td><b>".GetOffName($offid)."</b></td></tr>";

//Text (Body of Contract)
echo "<tr align=left><td><font style=\"color:red\"><b>Congratulations!</b></font> You have been selected to ";
if($type=='standby') echo "serve as an alternate (stand-by) official for";
else echo "officiate";
echo " the <font style=\"color:red\">$thisyear ";
echo "Class A and B State Soccer Tournament</font> hosted by the Nebraska School Activities Association!";

echo "&nbsp;The State Soccer Tournament will be held on May 12, 13, 15, 16, and 17 in Omaha.  Game assignments along with further instructions will be emailed to you once this contract is accepted.  In addition, you may be assigned as a \"4th Official\".  (If the dates and times you are available have changed, please contact the NSAA promptly.) <br><br>";

echo "It will be necessary for you to attend a brief officials meeting on Thursday, May 11th at 7:00 p.m. in Omaha.  The exact location of the meeting will be emailed to you with the other instructions. <br><br>";

echo "The fee is $45.00 per match plus the NSAA per diem rate.  Should you receive a \"4th Official\" assignment the fee is $15 per match.  All tournament fees will be mailed to you at the conclusion of the tournament. ";
echo "</td></tr>";
echo "<tr align=left><td><u>Mileage:</u><br>";
echo "Officials are encouraged to carpool whenever possible.<br>";
echo "An official who is not hired as part of a crew or set of officials and who is required to drive his/her own vehicle will be paid mileage using the following formula: (Using the NSAA mileage chart) One-way miles (to the site of the host city) x number of trips made x $1.00. Officials whose mailing address is within the host city will not be paid mileage.</td></tr>";

echo "<tr align=left><td><u>Lodging:</u><br>";
echo "When lodging is required and used by an official, the NSAA will reserve and pay for those rooms.  Any charges beyond the single room rate are the responsibility of the official.  No lodging will be paid to an official living in the host city.";
if($type!='standby')
   echo "  Please contact the NSAA if lodging is needed.";
echo "</td></tr>";
if($type!='standby')
   echo "<tr align=left><td><i>Please do not announce your selection as an official.  The announcement of all officials will be made by the NSAA after all contracts are accepted.</i></td></tr>";

//Legal Wording
echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && $level!=1)
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;I, as an independent contractor, accept the above agreement for the $thisyear State Soccer Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear State Soccer Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the official's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
echo "</table></form>";
echo "<a class=small href=\"javascript:window.close()\">Close</a>";

echo $end_html;
?>
