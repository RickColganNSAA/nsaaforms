<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session,"contractadmin");

$suptesthosts="suptesthosts";

if($submit)
{
   for($i=0;$i<count($id);$i++)
   {
      if($showschedall=='x') $showsched[$i]='x';
      if($remindersentall=='x') $remindersent[$i]='x';
      $sql="UPDATE $suptesthosts SET showsched='$showsched[$i]',remindersent='$remindersent[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
   }
}

echo "<br>";
echo "<a class=small href=\"suptestcontracts.php?session=$session\">Supervised Test Host MAIN MENU</a>&nbsp;&nbsp;";
echo "<a class=small href=\"suptesthostbyhost.php?session=$session\">Supervised Test Host SEARCH</a><br><br>";
echo "<form method=post action=\"suptesthostreport.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sort value=\"$sort\">";
echo "<table cellspacing=0 cellpadding=3 border=1 bordercolor=#000000>";
echo "<caption><b>Supervised Test Host Contract Report:</b>";
echo "<br><a href=\"postsuptest.php?session=$session&siteid=all\">Post ALL Supervised Test Contracts to Hosts</a><br>";
if($submit)
   echo "<br><font style=\"color:red\"><b>Your checkmarks have been saved.</b></font>";
echo "<br></caption>";
if(!$sort || $sort=='') $sort="mtgdate,hostname";
$sql="SELECT * FROM $suptesthosts ORDER BY $sort";
$result=mysql_query($sql);
echo "<tr align=center><td><a class=small href=\"suptesthostreport.php?session=$session&sort=mtgdate,hostname\">Date</a></td>";
echO "<td><a class=small href=\"suptesthostreport.php?session=$session&sort=hostname,mtgdate\">Host</a><br>(Click to View)</td>";
echo "<td><b>Contact</b></td>";
echo "<td><a class=small href=\"suptesthostreport.php?session=$session&sort=post,accept,confirm,mtgdate,hostname\">Posted</a></td>";
echo "<td><a class=small href=\"suptesthostreport.php?session=$session&sort=accept,confirm,mtgdate,hostname\">Accepted</a></td>";
echo "<td><a class=small href=\"suptesthostreport.php?session=$session&sort=confirm,mtgdate,hostname\">Confirmed</a></td>";
echo "<td><a class=small href=\"suptesthostreport.php?session=$session&sort=showsched,mtgdate,hostname\">Show on Schedule</a><br>";
echo "<input type=checkbox name=\"showschedall\" value='x'>Check ALL</td>";
echo "<td><a class=small href=\"suptesthostreport.php?session=$session&sort=remindersent,mtgdate,hostname\">Reminder Sent</a><br>";
echo "<input type=checkbox name=\"remindersentall\" value='x'>Check ALL</td>";
echo "</tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"id[$ix]\" value=\"$row[id]\">";
   echo "<tr align=center valign=top>";
   $date=split("-",$row[mtgdate]);
   echo "<td>$date[1]/$date[2]/$date[0]</td>";
   echo "<td align=left><a class=small href=\"suptesthostbyhost.php?session=$session&siteid=$row[id]\">$row[hostname]</a></td>";
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
   echo "></td><td><input type=checkbox name=\"remindersent[$ix]\" value='x'";
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
