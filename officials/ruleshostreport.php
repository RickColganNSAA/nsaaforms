<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($sport=='sp' || $sport=='pp')
  $level=GetLevelJ($session);
else
   $level=GetLevel($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session,"jcontractadmin");
else echo GetHeader($session,"contractadmin");

if($sport=='' || !$sport)
{
   echO "<br><br>ERROR: No sport selected";
   exit();
}
$ruleshosts=$sport."ruleshosts";
$sportname=GetSportName($sport);

if($submit)
{
   for($i=0;$i<count($id);$i++)
   {
      if($showschedall=='x') $showsched[$i]='x';
      if($remindersentall=='x') $remindersent[$i]='x';
      $sql="UPDATE $ruleshosts SET showsched='$showsched[$i]',remindersent='$remindersent[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
   }
}

echo "<br>";
if($delete)
{
   echo "<br><font style=\"color:red\"><b>Rules Meeting Site #$delete has been deleted.</b></font><br><br>";
}
echo "<a class=small href=\"rulescontracts.php?session=$session&sport=$sport\">$sportname Rules Meeting Host MAIN MENU</a>&nbsp;&nbsp;";
echo "<a class=small href=\"ruleshostbyhost.php?session=$session&sport=$sport\">$sportname Rules Meeting Host SEARCH</a><br><br>";
echo "<form method=post action=\"ruleshostreport.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=sort value=\"$sort\">";
echo "<table cellspacing=0 cellpadding=3 border=1 bordercolor=#000000>";
echo "<caption><b>$sportname Rules Meeting Host Contract Report:</b><br><br>";
echo "<a class=small href=\"postrules.php?session=$session&siteid=all&sport=$sport\">Post ALL $sportname Rules Meeting Host Contracts</a>&nbsp;&nbsp;";
echo "<a class=small href=\"rulesreminderemail.php?session=$session&sport=$sport\">E-mail Reminder to ALL $sportname Rules Meeting Hosts</a><br>";
if($submit)
   echo "<br><font style=\"color:red\"><b>Your checkmarks have been saved.</b></font>";
else if($posted=='yes')
   echo "<br><font style=\"color:red\"><b>All of the $sportname Rules Meeting Host Contracts have been posted.</b></font>";
echo "<br></caption>";
if(!$sort || $sort=='') $sort="mtgdate,type,hostname";
$sql="SELECT * FROM $ruleshosts ORDER BY $sort";
$result=mysql_query($sql);
echo "<tr align=center><td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=mtgdate,type,hostname\">Date</a></td>";
echo "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=type,mtgdate,hostname\">Type</a></td>";
echO "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=hostname,mtgdate,type\">Host</a><br>(Click to View)</td>";
echo "<td><b>Contact</b></td>";
echo "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=post,accept,confirm,mtgdate,hostname\">Posted</a></td>";
echo "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=accept,confirm,mtgdate,hostname\">Accepted</a></td>";
echo "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=confirm,mtgdate,hostname\">Confirmed</a></td>";
echo "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=showsched,mtgdate,hostname\">Show on Schedule</a><br>";
echo "<input type=checkbox name=\"showschedall\" value='x'>Check ALL</td>";
echo "<td><b>Reminder<br>Letter</b></td>";
echo "<td><a class=small href=\"ruleshostreport.php?session=$session&sport=$sport&sort=remindersent,mtgdate,hostname\">Reminder Sent</a><br>";
echo "<input type=checkbox name=\"remindersentall\" value='x'>Check ALL</td>";
echo "</tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"id[$ix]\" value=\"$row[id]\">";
   echo "<tr align=center valign=top>";
   $date=split("-",$row[mtgdate]);
   echo "<td>$date[1]/$date[2]/$date[0]</td>";
   echo "<td align=left>$row[type]</td>";
   echo "<td align=left><a class=small href=\"ruleshostbyhost.php?session=$session&sport=$sport&siteid=$row[id]\">$row[hostname]</a></td>";
   if($row[contactname]!='')
      echo "<td align=left>$row[contactname] ($row[contacttitle])<br>$row[contactphone]</td>";
   else echo "<td>&nbsp;</td>";
   if($row[post]=='y') echo "<td>YES</td>";
   else echo "<td>NO</td>";
   if($row[accept]=='y') echo "<td>YES</td>";
   else if($row[accept]=='n') echo "<td><font style=\"color:red\"><b>DECLINED</b></font></td>";
   else echo "<td>???</td>";
   if($row[confirm]=='y') echo "<td>YES</td>";
   else if($row[confirm]=='n') echo "<td><font style=\"color:red\"><b>REJECTED</b></font></td.";
   else echo "<td>???</td>";
   echo "<td><input type=checkbox name=\"showsched[$ix]\" value='x'";
   if($row[showsched]=='x') echo " checked";
   echo "></td>";
   if($row[accept]=='y' && $row[confirm]=='y')
      echo "<td><a class=small target=\"_blank\" href=\"rulesreminder.php?session=$session&siteid=$row[id]&sport=$sport\">View/Print Reminder</a></td>";
   else echo "<td>&nbsp;</td>";
   echo "<td><input type=checkbox name=\"remindersent[$ix]\" value='x'";
   if($row[remindersent]=='x') echo " checked";
   echo "></td>";
   echo "</tr>";
   $ix++;
}
echo "</table><br>";   
echo "<input type=submit name=submit value=\"Save Checkmarks\">";
echo "</form>";
echo $end_html;
exit();
?>
