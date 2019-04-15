<?php
$sport='bb';

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

//Get disttimesid, gender and type (stand-by or regular) for this contract
$sql="SELECT t1.id,t1.time,t1.gender FROM $disttimes AS t1,$contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$distid' AND t2.offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[time]=='standby') $type="standby";
else $type="state";
$disttimesid=$row[id];
$gender=$row[gender];
$empty=array();
if($gender=='Boys')
{
   $date1="Thursday, March 9, 2006"; $date2="Friday, March 10, 2006";
   $date3="Saturday, March 11, 2006";
   //GET LODGING DATES
   $sql="SELECT * FROM bbtourndates WHERE boys='x' AND lodgingdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $nights=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $nights[$i]=date("l, M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i2=$i+1; $field="date".$i2;
      $sql2="SHOW FULL COLUMNS FROM bbbcontracts WHERE Field='$field'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="ALTER TABLE bbbcontracts ADD `$field` VARCHAR(10) NOT NULL";
         $result2=mysql_query($sql2);
      }
      $i++;
   }
}
else if($gender=='Girls')
{
   $date1="Thursday, March 2, 2006"; $date2="Friday, March 3, 2006";
   $date3="Saturday, March 4, 2006";
   //GET LODGING DATES
   $sql="SELECT * FROM bbtourndates WHERE girls='x' AND lodgingdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $nights=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $nights[$i]=date("l, M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i2=$i+1; $field="date".$i2;
      $sql2="SHOW FULL COLUMNS FROM bbgcontracts WHERE Field='$field'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="ALTER TABLE bbgcontracts ADD `$field` VARCHAR(10) NOT NULL";
         $result2=mysql_query($sql2);
      }
      $i++;
   }
}

if($submit || $submit2)
{
   if($level!=1 && (($submit && (($lodging && $accept) || $accept=='n')) || $submit2))
   {
      if($submit)
      {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND disttimesid='$disttimesid'";
         $result=mysql_query($sql);
         if($accept=='y' && $lodging=='y')	//show LODGING FORM
	 {
	    echo $init_html;
            echo "<br>";
	    echo "<form method=post action=\"bbstatecontract.php\">";
	    echo "<input type=hidden name=session value=$session>";
  	    echo "<input type=hidden name=givenoffid value=$offid>";
	    echo "<input type=hidden name=distid value=$distid>";
	    echo "<table cellspacing=3 cellpadding=3>";
	    echo "<tr align=center><td>";
	    echo "<img src=\"nsaacontract.png\">";
	    echo "</td></tr>";
	    echo "<tr align=left><td><b>Please check the dates you need lodging:</b><br>";
	    for($i=0;$i<count($nights);$i++)
	    {
	       $num=$i+1; $var="night".$num;
	       echo "<input type=checkbox name=\"$var\" value='x'> $nights[$i]<br>";
	    }
	    echo "</td></tr>";
	    echo "<tr align=left><td><b>Approximate Time of Arrival:&nbsp;</b>";
	    echo "<input type=text class=tiny size=10 name=\"arrive\"></td></tr>";
	    echo "<tr align=left><td><b>Please list any special room requests below:<br></b>";
	    echo "(Example: need bigger room for family members (will pay additional cost), need non-smoking or smoking room, etc.)<br>";
	    echo "<textarea rows=5 cols=50 name=\"special\"></textarea></td></tr>";
	    echo "<tr align=center><td><input type=submit name=submit2 value=\"Submit\"></td></tr>";
	    echo "</table></form>";
	    echo $end_html;
	    exit();
         }
      }
      elseif($submit2)
      {
	 //store lodging form info
	 $arrive=addslashes($arrive);	
	 $special=addslashes($special);
	 $sql="UPDATE $contracts SET date1='$night1',date2='$night2',date3='$night3',date4='$night4',arrive='$arrive',special='$special' WHERE offid='$offid' AND disttimesid='$disttimesid'";
	 $result=mysql_query($sql);
         //echo "$sql<br>".mysql_error();
	 header("Location:bbstatecontract.php?session=$session&distid=$distid");
	 exit();
      }
   }
   else if($level!=1 && (!$accept || ($accept=='y' && !$lodging)))	//did not answer both questions
   {
      $error=1;
   }
   else if($level==1)
   {
      $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND disttimesid='$disttimesid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<br>";
echo "<form method=post action=\"bbstatecontract.php\">";
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
   echo "<tr align=center><td><font style=\"color:red\"><b>You must either accept or decline this contract.  If you are accepting this contract, you must check whether or not you need lodging.</b></font></td></tr>";
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
echo " the <font style=\"color:red\">$thisyear $gender State Basketball Tournament</font>!</td></tr>";

if($type=='standby')
{
   echo "<tr align=left><td>As an alternate (stand-by) official, you will be assigned to work six (6) games on $date1, and three (3) games on $date2.</td></tr>";

   echo "<tr align=left><td>The alternate official serves as an aid to the on-court officials, the scorekeeper, and the clock operator.  Alternate officials need to be mentally and physically prepared to officiate in case of injury, illness, or emergency.</td></tr>";

   echo "<tr align=left><td>The fee will be $15.00 per game plus the NSAA per diem rate.  Mileage to and from the state tournament will be paid to officials living outside of Lincoln at a rate of 85 cents per mile one way each trip using the NSAA mileage chart.  Officials living in Lincoln will not be paid mileage.  These fees will be paid following the tournament.</td></tr>";
}
else
{
   echo "<tr align=left><td>You will be assigned to officiate two (2) games on $date1, and one (1) game on $date2.  Twelve (12) officials will be assigned finals for $date3.  Six additional officials will be assigned as standby officials for the finals.</td></tr>";

   echo "<tr align=left><td>The fee will be $60.00 per game plus the NSAA per diem rate.  Mileage to and from the state tournament will be paid to officials living outside of Lincoln at a rate of 85 cents per mile one way each trip using the NSAA mileage chart.  Officials living in Lincoln will not be paid mileage.  Standby officials will receive $15.00 per game.  These fees will be paid following the tournament.</td></tr>";
}

echo "<tr align=left><td>All officials must attend a pre-tournament meeting at the Chase Suites in Lincoln, $date1, at 7:30 a.m.  Tournament instructions and assignments will be discussed at this meeting.</td></tr>";

echo "<tr align=left><td>The NSAA will provide lodging at the Chase Suites (200 S. 68th Place) for officials who require and use lodging.  No lodging will be paid to an official living in Lincoln.  Rooms will be held in the official's name and any charges beyond the single room rate are the responsibility of the official.  The NSAA will provide lodging for those officials who are selected to work on Saturday if lodging is used.</td></tr>";

echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

//LODGING QUESTION
if($accept!='y' && $accept!='n' && $level!=1)
{
   echo "<tr align=left><td>";
   echo "<input type=radio name=lodging value='y'>&nbsp;<b>YES, I need lodging<br></b>";
   echo "<input type=radio name=lodging value='n'>&nbsp;<b>No, I do not need lodging</b></td></tr>";
}
else if($accept=='y')
{
   $sql2="SELECT * FROM $contracts WHERE offid='$offid' AND disttimesid='$disttimesid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><td><b>Lodging Requirements:</b></td></tr>";
   if($row2[date1]=='' && $row2[date2]=='' && $row2[date3]=='' && $row2[date4]=='' && $row2[arrive]=='' && $row2[special]=='')
   {
      echo "<tr align=left><td>[None]</td></tr>";
   }
   else
   {
      echo "<tr align=left><td>";
      for($i=0;$i<count($nights);$i++)
      {
         $num=$i+1; $field="date".$num; 
         if($row2[$field]=='x') echo "<u>&nbsp;<b>X</b>&nbsp;</u>&nbsp;";
         else echo "<u>&nbsp;&nbsp;&nbsp;&nbsp;</u>&nbsp;";
         echo $nights[$i]."<br>";
      }
      echo "<br><b>Approximate Arrival Time: </b>$row2[arrive]<br><br>";
      echo "<b>Special Requests:</b><table width=300><tr align=left><td>$row2[special]</td></tr></table></td></tr>";
   }
}

echo "<tr align=left><td>The required uniform for the state tournament is as follows:  short sleeve black & white striped v-neck shirt with NSAA patch, black pants, <u>solid</u> black shoes with black laces, black socks, and a solid black nylon jacket.</td></tr>";

//Legal Wording
echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && $level!=1)
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;I, as an independent contractor, accept the above agreement for the $thisyear State Basketball Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear State Basketball Tournament";
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
