<?php
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

//get info on this sup test:
$sql="SELECT * FROM $suptesthosts WHERE id='$siteid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
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
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td colspan=2>";
echo "NEBRASKA SCHOOL ACTIVITIES ASSOCIATION<br>";
echo "8230 Beechwood Drive, P.O. Box 5447<br>";
echo "Lincoln, Nebraska  68505-0447<br>(402) 489-0386<br><br></td></tr>";
echo "<tr align=left valign=top><td>TO:</td>";
echo "<td>";
if($name!='') echo "$name<br>";
echo "$hostname<br>$address<br>$city_state&nbsp;$zip</td></tr>";
echo "<tr align=left><td>FROM:</td><td>Larry Mollring, Assistant Director</td></tr>";
echo "<tr align=left><td>SUBJECT:</td><td>$year1-$year2 Supervised Test Reminder</td></tr>";
echo "<tr align=left><td>DATE:</td><td>".date("F j, Y")."</td></tr>";

echo "<tr align=left><td>&nbsp;</td><td>The \"closed book\" examination for officials is to be given at your school on the date listed below:</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Date:&nbsp;&nbsp;</b>$date[1]/$date[2]/$date[0]<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Location:</b>&nbsp;&nbsp;$location<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Time:</b>&nbsp;&nbsp;$mtgtime<br>";
echo "</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>We are mailing you a supply of the tests.  If you find it impossible to supervise yourself, please assign the duty to some other responsible faculty member.<br><br>";
echo "<b><u>Please note this is to be a supervised closed book examination.  If the tests are to be worthwhile, they must represent the individual work of each person taking them.</u></b><br><br>";
echo "Be sure the test and answer sheet both are handed in.  The corrected answer sheet and test will be returned to the individual official, so it is necessary that the official's name and address be clearly filled in on each completed examination and his/her name written on the test questions.<br><br>";
echo "On the day following the examination, the tests and answer sheets will be mailed to the NSAA office in the postage paid envelope or box provided.<br><br>";
echo "<b>IMPORTANT -- ALL TESTS AND ANSWER SHEETS (USED AND UNUSED) MUST BE RETURNED TO THE NSAA OFFICE.</b><br><br>";
echo "The number of tests and answer sheets is checked carefully when sent to you and when returned to our office.  We cannot have any of these tests out prior to the second week's testing.  Please be sure each individual hands in his test questions along with his answer sheet.  Do not retain any copies yourself.  Send all to the NSAA office.<br><br>";
echo "A fifteen dollar check is enclosed as reimbursement for your time.  Please keep this regardless of how many officials, if any, report for the test.<br><br>";
echo "Please show below the number of officials taking the test and return this letter with your materials:<br>";
echo "<table bordercolor=#000000 border=1 cellspacing=0 cellpadding=4>";
echo "<tr align=left><td># of Officials:</td><td width=25>&nbsp;</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td colspan=2>Enclosures:<br>Examination Schedule<br>Check<br>Tests and Return Envelope</td></tr>";
echo "</table>";

echo $end_html;
exit();
?>
