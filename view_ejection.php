<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$database || $database=="") $database=$db_name;
$dbscores=$database;
$dboffs=preg_replace("/scores/","officials",$database);

if(!ValidUser($session))
{
   //check if logged in as NSAA Officials Admin
   mysql_close();
   $db=mysql_connect("$db_host",$db_user2,$db_pass2);
   mysql_select_db($db_name2,$db);
   $sql="SELECT * FROM sessions WHERE session_id='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0 && $off!=1)
   {
      header("Location:index.php?error=1");
      exit();
   }
   else
   {
      $level=1;
      //user is OK; change back to $db_name
      mysql_close();
      $db=mysql_connect("$db_host",$db_user,$db_pass);
      mysql_select_db($db_name,$db);
   }
}
if(!$level)
   $level=GetLevel($session);

$sql="SELECT * FROM $dbscores.ejections WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

$school=$row[school];
$school0=addslashes($school);

$sql2="SELECT name,email FROM $dbscores.logins WHERE school='$school0' AND level=2";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$name=$row2[0]; $email=$row2[1];

echo $init_html;
if($header!='no') echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
if($level==1)
   echo "<a class=small href=\"ejection.php?session=$session&id=$id&header=no\">Edit this Report</a><br><br>";
if($new==1)
{
   echo "<font style=\"color:red\"><b>You have just submitted the following Ejection Report to the NSAA.<br><br></b></font>";
}
echo "<table cellspacing=2 cellpadding=4 width=500><caption><b>Nebraska High School Activities Association Ejection Report:</b><hr></caption>";
if($level==1)	//show if verified and any notes added:
{
   echo "<tr align=left><td colspan=2><table><tr align=left><td><u><b>NSAA ONLY:</u></b></td></tr>";
   echo "<tr align=left><td><b>Verified by NSAA:</b>&nbsp;&nbsp;";
   if($row[verify]=='x') echo "YES</td></tr>";
   else echo "NO</td></tr>";
   echo "<tr align=left><td><b>NSAA Notes:</b>&nbsp;&nbsp;";
   if($row[notes]!='') echo "$row[notes]</td></tr>";
   else echo "[none]</td></tr>";
   echo "</table></td></tr>";
}
$datesub=date("F j, Y",$row[datesub]);
echo "<tr align=left><td><b>Date Submitted:</b></td><td>$datesub</td></tr>";
echo "<tr align=left><td><b>AD Submitting Report:</b></td><td>$name</td></tr>";
echo "<tr align=left><td><b>E-mail:</b></td><td>";
echo "$email</td></tr>";
echo "<tr align=left><td><b>Sport:</b></td><td>".GetEjectionActivity($row[sport])."</td></tr>";
echo "<tr align=left><td><b>School:</b></td><td>$school</td></tr>";
if($row[player]!='0')	//player was ejected
{
   $sql2="SELECT first,middle,last FROM $dbscores.eligibility WHERE id='$row[player]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $player="$row2[first] $row2[middle] $row2[last]";

   echo "<tr align=left><td><b>Name of Player Ejected:</b></td><td>$player";
   if($row[number]!="") echo " (Uniform No.: $row[number])";
   echo "</td></tr>";
}
else
{
   echo "<tr align=left><td><b>Name of Coach Ejected:</b></td><td>$row[coach]</td></tr>";
}
echo "<tr align=left><td><b>Date of Contest:</b></td>";
$date=split("-",$row[gamedate]);
echo "<td>$date[1]/$date[2]/$date[0]</td></tr>";
echo "<tr align=left><td><b>Contest:</b></td><td>$school VS. $row[school2]</td></tr>";
echo "<tr align=left><td><b>Site of Contest:</b></td><td>$row[site]</td></tr>";
echo "<tr align=left><td><b>Level:</b></td><td>$row[level]</td></tr>";
echo "<tr align=left><td colspan=2><b>Additional Comments (Optional):<br></b>";
echo "$row[comment]<br></td></tr>";
echo "<tr align=left><td colspan=2><b>Any player or coach ejected from a contest for unsportsmanlike conduct shall be ineligible for the next athletic contest at that level of competition and any other athletic contest at any level during the interim, in addition to other penalties that NSAA or school may assess.</b></td></tr>";
echo "</table>";
if($header!='no')
   echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
else
   echo "<br><br><a href=\"javascript:window.close()\" class=small>Close Window</a>";
echo $end_html;
?>
