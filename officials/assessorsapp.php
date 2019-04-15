<?php
//assessorsapp.php: manage wr assessor's registration payments

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"officialsapp");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

if($curappid)
{
   $sql="SELECT * FROM $db_name.wrassessorsapp WHERE appid='$curappid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo $row[html];
   exit();
}
else if($print)
{
   echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'.$init_html;
   for($i=0;$i<count($appid);$i++)
   {
      $sql="SELECT * FROM $db_name.wrassessorsapp WHERE appid='$appid[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html=preg_replace("/\<html\>\<body\>/","",$row[html]);
      $html=preg_replace("/\<\/body\>\<\/html\>/","",$html);
      $html=preg_replace("/\?tr\>/","/tr>",$html);
      echo $html;
      echo "</td></tr></table><div style=\"position:static;page-break-after:always;\">&nbsp;</div>";
   }
   echo $end_html;
   exit();
}

if($_POST && count($appid)>0)
{
   for($i=0;$i<count($appid);$i++)
   {
      $note[$i]=ereg_replace("\'","\'",$note[$i]);
      $note[$i]=ereg_replace("\"","\'",$note[$i]);
      if($noseeall=='x') $nosee[$i]="y";
      if($checkall=='x') $check[$i]="y";
      $sql="UPDATE $db_name.wrassessorsapp SET checked='$check[$i]', notes='$note[$i]', nosee='$nosee[$i]' WHERE appid='$appid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<br><a href=\"officialsapp.php?session=$session\" class=small>Go to OFFICIALS' Registration</a><br>";
echo "<br><form method=post action=\"assessorsapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<h1>Submitted Wrestling Assessor Registrations:</h1>";
echo "<h3>Show Registrations for: <select name=\"day\"><option value=\"0\">ALL DAYS</option>";
$sql="SELECT DISTINCT FROM_UNIXTIME(appid,'%Y-%m-%d') as day FROM $db_name.wrassessorsapp WHERE approved='yes' ORDER BY day DESC";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[day]);
   $showday=date("D, M j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<option value=\"$row[day]\"";
   if($day==$row[day]) echo " selected";
   echo ">$showday</option>";
}
echo "</select> <input type=\"button\" onClick=\"form.target='_self';document.getElementById('print').value='0';submit();\" name=\"show\" value=\"Go\"></h3>";
echo "<p><a href=\"assessorsdata.php?session=$session\">View all INITIATED Wrestling Assessors' Registrations &rarr;</a></p>";
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
$colheaders="<tr align=left><th class=smaller>Delete<br>from View<th class=smaller>Registration Form</th><th class=smaller>Assessor's Name</th><th class=smaller>Check if<br>Viewed</th><th class=smaller>Notes</th></tr>";
$sql="SELECT * FROM $db_name.wrassessorsapp WHERE approved='yes' AND nosee!='y' ";
if($day && $day!='') $sql.="AND FROM_UNIXTIME(appid,'%Y-%m-%d')='$day' ";
$sql.="ORDER BY appid DESC";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if(trim($row[appid])!="")
   {
   $date=date("m/d/Y H:i T",$row[appid]);
   $id=$row[appid];
   if($ix%15==0) echo $colheaders;
   echo "<tr align=left><td align=center><input type=checkbox name=\"nosee[$ix]\"";
   if($row[nosee]=='y') echo " checked";
   echo " value='y'></td><td>";
   echo "<a href=\"assessorsapp.php?session=$session&curappid=$id\" target=new>#$id: $date</a>";
   $sql2="SELECT * FROM $db_name.wrassessors WHERE userid='$row[assessorid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "</td><td>$row2[first] $row2[last] (User ID: $row[assessorid])";
   echo "</td><td align=center><input type=checkbox name=\"check[$ix]\"";
   if($row[checked]=='y') echo " checked";
   echo " value='y'></td><td><input type=text size=40 name=\"note[$ix]\" value=\"$row[notes]\"></td></tr>";
   echo "<input type=hidden name=\"appid[$ix]\" value=\"$id\">";
   $ix++;
   }
}
echo "<tr align=center><td>CHECK ALL<br><input type=checkbox name=\"noseeall\" value=\"x\"></td><td colspan=2>&nbsp;</td><td>CHECK ALL<br><input type=checkbox name=\"checkall\" value=\"x\"></td><td>&nbsp;</td></tr>";
echo "</table>";
if($ix>0) 
{
   echo "<br><input type=button onClick=\"form.target='_self';document.getElementById('print').value='0';submit();\" name=\"save\" value=\"Save\">&nbsp;&nbsp;<input type=button onClick=\"form.target='_blank';document.getElementById('print').value='1';submit();\" name=\"printbutton\" value=\"Print ALL\">";
}
echo "<input type=hidden name='print' id='print'></form>";

echo $end_html;
?>
