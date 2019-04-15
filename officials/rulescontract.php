<?php
/*****CONTRACT TO HOST RULES MEETING*****/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!$sport || $sport=='') 
{
   echo "No sport given";
   exit();
}
else if(!$siteid || $siteid=='')
{
   echo "No site given";
   exit();
}
$ruleshosts=$sport."ruleshosts";
$sportname=GetSportName($sport);

if($level!=1)   //Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostname=$row[school];
   $sql="SELECT hostname,post FROM $ruleshosts WHERE id='$siteid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($hostname=='' || !$hostname || $hostname!=$row[hostname] || $row[post]!='y')
   {
      echo "You are not the host of this rules meeting.";
      exit();
   }
}
else if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($submit && $siteid && $sport)
{
   if($accept=='y')
   {
      $location=addslashes($location);
      $contactname=addslashes($contactname);
      $contacttitle=addslashes($contacttitle);
      $contactphone="($pharea) $phpre-$phpost";
      if($ext!='') $contactphone.=" ext. $ext";
      $equipment=addslashes($equipment);
      $sql="UPDATE $ruleshosts SET accept='y',location='$location',contactname='$contactname',contacttitle='$contacttitle',contactphone='$contactphone',equipment='$equipment' WHERE id='$siteid'";
      $result=mysql_query($sql);
      header("Location:rulescontract.php?session=$session&sport=$sport&siteid=$siteid");
      exit();
   }
   else if($accept=='n')
   {
      $sql="UPDATE $ruleshosts SET accept='n' WHERE id='$siteid'";
      $result=mysql_query($sql);
      header("Location:rulescontract.php?session=$session&sport=$sport&siteid=$siteid");
      exit();
   }
   else if(($confirm=='y' || $confirm=='n') && $level==1)
   {
      $sql="UPDATE $ruleshosts SET confirm='$confirm' WHERE id='$siteid'";
      $result=mysql_query($sql);
      header("Location:rulescontract.php?session=$session&sport=$sport&siteid=$siteid");
      exit();
   }
   else
   {
      header("Location:rulescontract.php?session=$session&sport=$sport&siteid=$siteid&error=1");
      exit();
   }
}
//get info on this rules meeting:
$sql="SELECT * FROM $ruleshosts WHERE id='$siteid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type=$row[type];
$origsiteid=$row[origsiteid];
$hostname=$row[hostname];
$date=split("-",$row[mtgdate]);
$year1=$date[0];
if($date[1]<=6) $year1--;
$year2=$year1+1;
$mtgtime=$row[mtgtime];
$location=$row[location];
$contactname=$row[contactname]; $contacttitle=$row[contacttitle];
$contactphone=$row[contactphone];
$equipment=$row[equipment];
$post=$row[post]; $accept=$row[accept]; $confirm=$row[confirm];

//get info on the host:
$hostname2=addslashes($hostname);
$sql="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND (level='2' OR level='4' OR level='6')";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostlevel=$row[level];
$name=$row[name];
if($hostlevel==2 && $name=='')
{
   $sql="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND sport='Activities Director'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name=$row[name];
}
if($hostlevel=='4' || $hostlevel=='6')
   $sql="SELECT * FROM $db_name.logins WHERE school='$hostname2'";
else
   $sql="SELECT * FROM $db_name.headers WHERE school='$hostname2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$address=$row[address1];
if($row[address2]!='') $address.="<br>$row[address2]";
$city_state=$row[city_state]; $zip=$row[zip];

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br><a class=small href=\"javascript:window.close();\">Close Window</a><br>";
echo "<form method=post action=\"rulescontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=siteid value=$siteid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
echo "</td></tr>";
echo "<tr align=center><td>";

if($error==1)
{
   if($level==1)
      echo "<font style=\"color:red\"><b>You must check an NSAA response to this contract.</font><br>";
   else
      echo "<font style=\"color:red\"><b>You must check whether or not you will be able to host this rules meeting.</b></font><br>";
}
else if($accept=='y')
{
   if($level==1) $words="$hostname has";
   else $words="You have";
   echo "<table width=400><tr align=left><td>$words <b>ACCEPTED</b> the following contract.</td></tr>";
   if($confirm=='y')
      echo "<tr align=left><td>The NSAA has <b>CONFIRMED</b> this contract.  More information will be sent to you at a later date</td></tr>";
   else if($confirm=='n')
      echo "<tr align=left><td>The NSAA has <b>REJECTED</b> this contract.</td></tr>";
   else
      echo "<tr align=left><td>The NSAA has not responded to this contract yet.</td></tr>";
   echo "</table>";
}
else if($accept=='n')
{
   if($level==1) $words="$hostname has";
   else $words="You have";
   echo "<table width=400><tr align=left><td>$words <b>DECLINED</b> the following contract.</td></tr>";
   if($confirm!='')
      echo "<tr align=left><td>The NSAA has <b>ACKNOWLEDGED</b> this contract.</td></tr>";
   else 
      echo "<tr align=left><td>The NSAA has not responded to this contract yet.</td></tr>";
   echo "</table>";
}

echo "<table width=500 cellspacing=3 cellpadding=3><tr valign=top align=left><td>TO:</td>";
echo "<td>";
if($name!='') echo "$name<br>";
echO "$hostname<br>$address<br>$city_state&nbsp;$zip</td></tr>";
echo "<tr align=left><td>FROM:</td><td>Larry Mollring, Assistant Director</td></tr>";
echO "<tr align=left><td>SUBJECT:</td><td>$year1-$year2 Rules Meetings</td></tr>";
echO "<tr align=left><td>DATE:</td><td>".date("F j, Y")."</td></tr>";
echo "<tr align=left><td>&nbsp;</td>";
echo "<td>";
if($type=="Originating")
   echo "The Nebraska School Activities Association would like your facility to serve as the ORIGINATING site with distance learning for the following rules meeting:<br><br>";
else if($type=="Receiving")
   echo "The Nebraska School Activities Association would like your facility to serve as a RECEIVING site for distance learning for the following rules meeting:<br><br>";
else if($type=="Regular")
   echo "The Nebraska School Activities Association would like to hold the following rules meeting at your facility:<br><br>";
echo "Activity:&nbsp;&nbsp;$sportname<br>";
echo "Date:&nbsp;&nbsp;".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."<br>";
if($type=="Originating")
{
   echo "Receiving Sites:&nbsp;&nbsp;";
   $sql="SELECT * FROM $rulehosts WHERE origsiteid='$siteid' ORDER BY mtgdate,hostname";
   $result=mysql_query($sql);
   $string="";
   while($row=mysql_fetch_array($result))
   {
      $string.="$row[hostname], ";
   }
   $string=substr($string,0,strlen($string)-2);
   echo $string."<br>";
}
else if($type=="Receiving")
{
   echo "Originating Site:&nbsp;&nbsp;";
   $sql="SELECT hostname,mtgdate FROM $ruleshosts WHERE id='$origsiteid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $date=split("-",$row[mtgdate]);
   echo $row[hostname].", $date[1]/$date[2]/$date[0]<br>";
}
echo "<br>";
if($type=="Originating")
{
   echo "The meeting will begin at $mtgtime from the originating site.  We will not disturb any other meeting you may have at the same time.  It will be necessary for you to appoint a meeting supervisor/contact person.  The supervisor will receive a payment of $15.00.<br><br>";
   if($accept!='y' && $accept!='n')
   {
      echo "To ACCEPT this contract to host this rules meeting, please check \"We will be the originating site for the rules meeting\" and then complete the information below the checkbox and click \"Submit\".  If you have any questions, please call the NSAA office at (402)489-0386.<br><br>";
      echo "To DECLINE this contract, please check \"We will be unable to act as the originating site for the rules meeting\" and click \"Submit\".<br><br>";
   }
}
else if($type=="Receiving")
{
   echo "The meeting will begin at $mtgtime from the originating site.  We will not disturb any other meeting you may have at the same time.  It will be necessary for you to appoint a meeting supervisor.  The supervisor will be responsible for passing out handouts and taking attendance.  The supervisor will receive a payment of $15.<br><br>";
   if($accept!='y' && $accept!='n')
   { 
      echo "To ACCEPT this contract to host this rules meeting, please check \"We will be a receiving site for the rules meeting\" and then complete the information below the checkbox and click \"Submit\".  If you have any questions, please call the NSAA office at (402)489-0386.<br><br>";
      echo "To DECLINE this contract, please check \"We will be unable to act as a receiving site for the rules meeting\" and click \"Submit\".<br><br>";
   }
}
else if($type=="Regular")
{
   echo "The meeting will begin at $mtgtime.  We will not disturb any other meeting you may have at the same time.  It will be necessary for you to appoint a contact person.<br><br>";
   if($accept!='y' && $accept!='n')
   { 
      echo "To ACCEPT this contract to host this rules meeting, please check \"We will host the rules meeting\" and then complete the information below the checkbox and click \"Submit\".  If you have any questions, please call the NSAA office at (402)489-0386.<br><br>";
      echo "To DECLINE this contract, please check \"We will be unable to host the rules meeting\" and click \"Submit\".<br><br>";
   }
}
echo "</td></tr><tr align=left><td colspan=2>";
echo "You do NOT need to fax a copy of this contract to the NSAA.  Once you have completed this contract, the NSAA will respond to it accordingly.  You may periodically login and check the status of your contract under the \"Rules Meeting Host Information\" section on your main Welcome screen.  If you ACCEPT this contract, more information will be sent to you at a later date.<br><br>";
echo "</td></tr>";

if($accept!='y' && $accept!='n' && $post=='y')
{ 
   echo "<tr align=center><td colspan=2><b>Please complete and submit IMMEDIATELY:</b></td></tr>";
   echo "<tr align=left><td colspan=2><input type=radio name=\"accept\" value=\"y\">&nbsp;";
   if($type=="Originating")
      echo "We will be the originating site for the rules meeting";
   else if($type=="Receiving")
      echo "We will be a receiving site for the rules meeting";
   else if($type=="Regular")
      echo "We will host the rules meeting";
   echo "</td></tr>";
   echo "<tr align=left valign=top><td>&nbsp;</td><td><table>";
   echo "<tr align=left valign=top><td>Room # or Address if different from:<br>$address<br>$city_state&nbsp;$zip</td>";
   echo "<td><input type=text class=tiny size=30 name=\"location\"></td></tr>";
   echo "<tr align=left><td>Contact Person:</td><td><input type=text class=tiny size=20 name=\"contactname\"></td></tr>";
   echo "<tr align=left><td>Title:</td><td><input type=text class=tiny size=20 name=\"contacttitle\"></td></tr>";
   echo "<tr align=left><td>Phone #:</td><td>( <input type=text class=tiny size=3 name=\"pharea\"> ) <input type=text class=tiny size=3 name=\"phpre\"> - <input type=text class=tiny size=4 name=\"phpost\"> ext <input type=text class=tiny size=3 name=\"ext\"></td></tr>";
   if($type=="Originating")
   {
      echo "<tr align=left><td colspan=2>The NSAA will conduct the meeting using an IBM-formatted Power Point presentation.  Please indicate what equipment we will need to provide.  For example: laptop, disk only, hard copy of material, etc.</td></tr>";
      echo "<tr align=left><td>Equipment needed:</td><td><input type=text class=tiny size=30 name=\"equipment\"></td></tr>";
   }
   echo "</table></td></tr>";
   echo "<tr align=left><td colspan=2><input type=radio name=\"accept\" value=\"n\">&nbsp;";
   if($type=="Originating")
      echo "We will be unable to act as the originating site for the rules meeting";
   else if($type=="Receiving")
      echo "We will be unable to act as a receiving site for the rules meeting";
   else if($type=="Regular")
      echo "We will unable to host the rules meeting";
   echo "</td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='y')
{
   if($level==1) $words="$hostname";
   else $words="You";
   echo "<tr align=center><td colspan=2><b>$words submitted the following information to the NSAA:</b></td></tr>";
   echo "<tr align=center><td colspan=2><table>";
   echo "<tr align=left><td>Location:</td><td>$location</td></tr>";
   echo "<tr align=left><td>Contact Person:</td><td>$contactname</td></tr>";
   echo "<tr align=left><td>Contact Title:</td><td>$contacttitle</td></tr>";
   echo "<tr align=left><td>Contact Phone #:</td><td>$contactphone</td></tr>";
   if($type=="Originating")
   {
      echo "<tr align=left><td>Equipment Needed:</td><td>$equipment</td></tr>";
   }
   echo "</table></td></tr>";
}
if($level==1 && ($accept=='y' || $accept=='n') && $confirm!='y' && $confirm!='n')
{
   echo "<tr align=left><td colspan=2><input type=radio name=\"confirm\" value=\"y\">&nbsp;The NSAA ";
   if($accept=='y') echo "CONFIRMS";
   else echo "ACKNOWLEDGES";
   echo " this contract to host a rules meeting.</td></tr>";
   if($accept=='y')
      echo "<tr align=left><td colspan=2><input type=radio name=\"confirm\" value=\"n\">&nbsp;The NSAA REJECTS this contract to host a rules meeting.</td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Submit\"></td></tr>";
}
echo "</table></form>";

echo "</td></tr></table>";
echo $end_html;
exit();
?>
