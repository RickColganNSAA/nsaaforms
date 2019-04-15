<?php
/*****CONTRACT TO HOST SUPERVISED TEST*****/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!$siteid || $siteid=='')
{
   echo "No site given";
   exit();
}
$suptesthosts="suptesthosts";

if($level!=1)   //Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostname=$row[school];
   $sql="SELECT hostname,post FROM $suptesthosts WHERE id='$siteid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($hostname=='' || !$hostname || $hostname!=$row[hostname] || $row[post]!='y')
   {
      echo "You are not the host of this supervised test.";
      exit();
   }
}
else if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($submit && $siteid)
{
   if($accept=='y')
   {
      $location=addslashes($location);
      $contactname=addslashes($contactname);
      $contacttitle=addslashes($contacttitle);
      $contactphone="($pharea) $phpre-$phpost";
      if($ext!='') $contactphone.=" ext. $ext";
      $sql="UPDATE $suptesthosts SET accept='y',location='$location',contactname='$contactname',contacttitle='$contacttitle',contactphone='$contactphone' WHERE id='$siteid'";
      $result=mysql_query($sql);
      header("Location:suptestcontract.php?session=$session&siteid=$siteid");
      exit();
   }
   else if($accept=='n')
   {
      $sql="UPDATE $suptesthosts SET accept='n' WHERE id='$siteid'";
      $result=mysql_query($sql);
      header("Location:suptestcontract.php?session=$session&siteid=$siteid");
      exit();
   }
   else if(($confirm=='y' || $confirm=='n') && $level==1)
   {
      $sql="UPDATE $suptesthosts SET confirm='$confirm' WHERE id='$siteid'";
      $result=mysql_query($sql);
      header("Location:suptestcontract.php?session=$session&siteid=$siteid");
      exit();
   }
   else
   {
      header("Location:suptestcontract.php?session=$session&siteid=$siteid&error=1");
      exit();
   }
}
//get info on this sup test:
$sql="SELECT * FROM $suptesthosts WHERE id='$siteid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostname=$row[hostname];
$mtgdate=$row[mtgdate];
$sports="";
$sportch=split("/",$row[sports]);
for($i=0;$i<count($sportch);$i++)
{
   $sports.=GetSportName($sportch[$i]).", ";
}
$sports=substr($sports,0,strlen($sports)-2);
$date=split("-",$mtgdate);
$year1=$date[0];
if($date[1]<=6) $year1--;
$year2=$year1+1;
$mtgtime=$row[mtgtime];
$location=$row[location];
$contactname=$row[contactname]; $contacttitle=$row[contacttitle];
$contactphone=$row[contactphone];
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
echo "<form method=post action=\"suptestcontract.php\">";
echo "<input type=hidden name=session value=$session>";
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
      echo "<font style=\"color:red\"><b>You must check whether or not you will be able to host this supervised test.</b></font><br>";
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
echO "<tr align=left><td>SUBJECT:</td><td>$year1-$year2 Supervised Tests</td></tr>";
echO "<tr align=left><td>DATE:</td><td>".date("F j, Y")."</td></tr>";
echo "<tr align=left><td>&nbsp;</td>";
echo "<td>The Nebraska School Activities Association would like your high school to be a testing center for our closed book rules examinations for officials during the $year1-$year2 school year.  The tests will be scheduled for $mtgtime and will take about one and a half hours to monitor.  Here are the dates:<br><br>";
echo "Activities:&nbsp;&nbsp;$sports<br>";
echo "Date(s):&nbsp;&nbsp;";
$dates=split("/",$mtgdate);
$datestr="";
for($i=0;$i<count($dates);$i++)
{
   $cur=split("-",$dates[$i]);
   $datestr.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
}
$datestr.=$cur[0];
echo "$datestr<br>";
echo "</td></tr><tr align=left><td>&nbsp;</td><td>";
echo "It will be necessary for you or a responsible faculty member to supervise the examination in much the same way that high school examinations are supervised.  The supervisor will receive a stipend of $15.00 per night, regardless of the number of officials (if any) that report for the test.";
echo "</td></tr><tr align=left><td colspan=2>";
echo "You do NOT need to fax a copy of this contract to the NSAA.  Once you have completed this contract, the NSAA will respond to it accordingly.  You may periodically login and check the status of your contract under the \"Supervised Test Host Information\" section on your main Welcome screen.  If you ACCEPT this contract, more information will be sent to you at a later date.<br><br>";
echo "</td></tr>";

if($accept!='y' && $accept!='n' && ($post=='y' || $level==1))
{ 
   echo "<tr align=center><td colspan=2><b>Please complete and submit IMMEDIATELY:</b></td></tr>";
   echo "<tr align=left><td colspan=2><input type=radio name=\"accept\" value=\"y\">&nbsp;";
   echo "We will monitor the supervised test.</td></tr>";
   echo "<tr align=left valign=top><td>&nbsp;</td><td><table>";
   echo "<tr align=left valign=top><td>Room # or Address if different from:<br>$address<br>$city_state&nbsp;$zip</td>";
   echo "<td><input type=text class=tiny size=30 name=\"location\"></td></tr>";
   echo "<tr align=left><td>Contact Person:</td><td><input type=text class=tiny size=20 name=\"contactname\"></td></tr>";
   echo "<tr align=left><td>Title:</td><td><input type=text class=tiny size=20 name=\"contacttitle\"></td></tr>";
   echo "<tr align=left><td>Phone #:</td><td>( <input type=text class=tiny size=3 name=\"pharea\"> ) <input type=text class=tiny size=3 name=\"phpre\"> - <input type=text class=tiny size=4 name=\"phpost\"> ext <input type=text class=tiny size=3 name=\"ext\"></td></tr></table></td></tr>";
   echo "<tr align=left><td colspan=2><input type=radio name=\"accept\" value=\"n\">&nbsp;";
   echo "We will be unable to monitor this supervised test.</td></tr>";
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
   echo "</table></td></tr>";
}
if($level==1 && ($accept=='y' || $accept=='n') && $confirm!='y' && $confirm!='n')
{
   echo "<tr align=left><td colspan=2><input type=radio name=\"confirm\" value=\"y\">&nbsp;The NSAA ";
   if($accept=='y') echo "CONFIRMS";
   else echo "ACKNOWLEDGES";
   echo " this contract to host a supervised test.</td></tr>";
   if($accept=='y')
      echo "<tr align=left><td colspan=2><input type=radio name=\"confirm\" value=\"n\">&nbsp;The NSAA REJECTS this contract to host a supervised test.</td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Submit\"></td></tr>";
}
echo "</table></form>";

echo "</td></tr></table>";
echo $end_html;
exit();
?>
