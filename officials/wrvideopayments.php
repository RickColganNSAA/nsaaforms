<?php
/* MANAGE PAYMENTS FOR WR STATE VIDEOS */

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
   $sql="SELECT * FROM $db_name.wrvideotransactions WHERE appid='$curappid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo $row[html];
   exit();
}

if($submit=="Save")
{
   for($i=0;$i<count($appid);$i++)
   {
      $note[$i]=ereg_replace("\'","\'",$note[$i]);
      $note[$i]=ereg_replace("\"","\'",$note[$i]);
      $sql="UPDATE $db_name.wrvideotransactions SET checked='$check[$i]', notes='$note[$i]', nosee='$nosee[$i]' WHERE appid='$appid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<br><a href=\"officialsapp.php?session=$session\" class=small>Go to OFFICIALS' Registration</a><br>";
echo "<br><form method=post action=\"wrvideopayments.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3>";
echo "<caption><b>Payments for NSAA State Wrestling Video Downloads:</b><br><br></caption>";
$colheaders="<tr align=left><th class=smaller>Delete<br>from View<th class=smaller>Registration Form</th><th class=smaller>Check if<br>Viewed</th><th class=smaller>Notes</th></tr>";
$sql="SELECT * FROM $db_name.wrvideotransactions WHERE approved='yes' AND nosee!='y' ORDER BY appid DESC";
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
   echo "<a href=\"wrvideopayments.php?session=$session&curappid=$id\" target=new>#$id: $date</a>";
   echo "</td><td align=center><input type=checkbox name=\"check[$ix]\"";
   if($row[checked]=='y') echo " checked";
   echo " value='y'></td><td><input type=text size=40 name=\"note[$ix]\" value=\"$row[notes]\"></td></tr>";
   echo "<input type=hidden name=\"appid[$ix]\" value=\"$id\">";
   $ix++;
   }
}
echo "</table><br><input type=submit name=submit value=\"Save\"></form>";

echo $end_html;
?>
