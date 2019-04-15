<?php
//rulesmeetingattendance.php: officials & coaches attendance for online rules meetings

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"rulesmeetingadmin");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($save)
{
   for($i=0;$i<count($appid);$i++)
   {
      $note[$i]=ereg_replace("\'","\'",$note[$i]);
      $note[$i]=ereg_replace("\"","\'",$note[$i]);
      $sql="UPDATE $database.rulesmeetingattendance SET checked='$check[$i]', notes='$note[$i]', nosee='$nosee[$i]' WHERE invoiceid='$appid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;
echo "<br><form method=post action=\"rulesmeetingattendance.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3>";
if(!$database) $database=$db_name2;
echo "<caption><b><select name=\"database\" id=\"database\" onchange=\"submit();\">";
echo "<option value=\"$db_name2\"";
if($database==$db_name2) echo " selected";
echo ">Officials & Judges</option><option value=\"$db_name\"";
if($database==$db_name) echo " selected";
echo ">Coaches & AD's</option></select> ";
echo "<select onchange=\"submit();\" name=\"sport\"><option value=''>All Sports + AD's</option>";
$sql2="SHOW TABLES FROM $database LIKE '%rulesmeetings'";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $temp=split("rulesmeetings",$row2[0]);
   echo "<option value=\"$temp[0]\"";
   if($sport==$temp[0]) echo " selected";
   echo ">".GetSportName($temp[0])."</option>";
}
echo "</select> ";
echo "Online Rules Meeting Attendance:</b><br><br></caption>";
$colheaders="<tr align=left><th class=smaller>Delete<br>from View</th><th class=smaller>Sport</th><th class=smaller>Date/Time Completed</th><th class=smaller>Name</th>";
if($database==$db_name) 
{
   $colheaders.="<th class=smaller>School</th>";
   $colheaders.="<th class=smaller>Official/Judge?</th>";
}
else
{
   $colheaders.="<th class=smaller>Head Coach?</th>";
}
$colheaders.="<th class=smaller>Check if<br>Viewed</th><th class=smaller>Notes</th></tr>";
$sql="SELECT * FROM $database.rulesmeetingattendance WHERE ";
if($sport && $sport!='')
   $sql.="invoiceid LIKE '%-$sport' AND ";
$sql.="nosee!='y' AND datepaid>0 ORDER BY datepaid DESC";
$result=mysql_query($sql);
$ix=0;
//echo $sql;
while($row=mysql_fetch_array($result))
{
   $date=date("m/d/Y g:ia T",$row[datepaid]);
   $id=$row[invoiceid]; $temp=split("-",$id); $cursp=strtoupper($temp[1]);
   if($ix%15==0) echo $colheaders;
   echo "<tr align=left><td align=center><input type=checkbox name=\"nosee[$ix]\"";
   if($row[nosee]=='y') echo " checked";
   echo " value='y'></td><td>$cursp</td><td>";
   echo $date;
   if($database==$db_name2)
   {
      if($cursp=='SP' || $cursp=='PP') $table="judges";
      else $table="officials";
      $sql2="SELECT first,last FROM $table WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $name="$row2[first] $row2[last]";
      $sql2="SELECT * FROM $database.".strtolower($cursp)."rulesmeetings WHERE offid='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $school1=$row2[school1]; $school2=$row2[school2];
   }
   else
   {
      $sql2="SELECT name,school FROM $db_name.logins WHERE id='$row[coachid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $name=$row2[name]; $school=$row2[school];
      $sql2="SELECT * FROM $database.".strtolower($cursp)."rulesmeetings WHERE coachid='$row[coachid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offid=$row2[offid];
   }
   echo "</td><td>$name</td>";
   if($database==$db_name) 
   {
      echo "<td>$school</td>";
      if(!$offid) echo "<td>No</td>";
      else echo "<td>Yes (Official ID # $offid)</td>";
   }
   else
   {
      echo "<td>";
      if($cursp=='SP' || $cursp=='PP')
      {
         if($school1!='') echo "Speech Director<br>";
         if($school2!='') echo "Play Director";
      }
      else if($cursp=='BB' || $cursp=='SO' || $cursp=='SW' || $cursp=='TR')
      {
	 if($school1!='') echo "Boys ".GetSportName(strtolower($cursp))."<br>";
   	 if($school2!='') echo "Girls ".GetSportName(strtolower($cursp));
      }
      echo "&nbsp;</td>";
   }
   echo "<td align=center><input type=checkbox name=\"check[$ix]\"";
   if($row[checked]=='y') echo " checked";
   echo " value='y'></td><td><input type=text size=40 name=\"note[$ix]\" value=\"$row[notes]\"></td></tr>";
   echo "<input type=hidden name=\"appid[$ix]\" value=\"$id\">";
   $ix++;
}
if(mysql_num_rows($result)>0)
   echo "</table><br><input type=submit name=save value=\"Save\"></form>";
else
   echo "<tr align=center><td>[No online rules meetings have been completed at this time.]</td></tr></table></form>";

echo $end_html;
?>
