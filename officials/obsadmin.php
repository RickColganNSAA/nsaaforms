<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($submit=="Delete Checked")
{
   for($i=0;$i<count($delid);$i++)
   {
      if($delete[$i]=='x')
      {
	 $obstable=$delsp[$i]."observe";
	 $sql="DELETE FROM $obstable WHERE id='$delid[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo GetHeader($session,"obshome");
echo "<br><br>";
echo "<form method=post action=\"obsadmin.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=submitted value=$submitted>";
if($submitted=="no")
   echo "<a href=\"obsadmin.php?session=$session&submitted=yes\" class=small>View Observations SUBMITTED by the NSAA</a><br><br>";
else
   echo "<a href=\"obsadmin.php?session=$session&submitted=no\" class=small>View Observations SAVED but NOT SUBMITTED by the NSAA</a><br><br>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#333333 1px solid;\">";
if($submitted=="no")
   echo "<caption><b>Observations SAVED but NOT SUBMITTED by the NSAA</b></caption>";
else
   echo "<caption><b>Observations SUBMITTED by the NSAA</b></caption>";
echo "<tr align=center><td><a class=small href=\"obsadmin.php?session=$session&submitted=no&sort=last\">Official</a></td>";
echo "<td><a class=small href=\"obsadmin.php?session=$session&submitted=no&sort=date\">Date of Game</a></td>";
echo "<td><a class=small href=\"obsadmin.php?session=$session&submitted=no&sort=location\">Location of Game</a></td>";
echo "<td><b>Observation</b></td>";
if($submitted=="no")
   echo "<th class=small>Delete<br>Observation</th>";
echo "</tr>";
$sql0="SHOW TABLES LIKE '%observe'";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $temp=split("observe",$row0[0]);
   $cursp=$temp[0];
   if(preg_match("/clinic/",$cursp))
   {
      $temp2=split("clinic",$cursp);
      $page=$cursp."observe.php";
      $cursp=$temp2[0];
      $sportname=GetSportName($cursp)." CLINIC";
      $obstable=$cursp."clinicobserve";
      $sql="SELECT t1.id,t1.offid,t1.official,t1.clinicdate AS evaldate,t1.location,t3.first,t3.last FROM $obstable AS t1, officials AS t3 WHERE t1.offid=t3.id AND ";
      if($submitted=="no")
         $sql.="t1.dateeval=''";
      else
         $sql.="t1.dateeval!=''";
      $sql.=" AND t1.obsid=1 ORDER BY ";
      if(!$sort || $sort=="" || $sort=="last")
         $sql.="t3.last, t3.first";
      else if($sort=="date")
         $sql.="t1.clinicdate,t3.last,t3.first";
      else if($sort=="location")
         $sql.="t1.location,t3.last,t3.first";
   }
   else
   {
      $page=$cursp."observe.php";
      $sportname=GetSportName($cursp);
      $obstable=$cursp."observe";
      $schtable=$cursp."sched";
      $sql="SELECT t1.id,t1.offid,t1.gameid,t2.offdate AS evaldate,t2.location,t3.first,t3.last FROM $obstable AS t1, $schtable AS t2, officials AS t3 WHERE t1.offid=t3.id AND t1.gameid=t2.id AND ";
      if($submitted=="no")
         $sql.="t1.dateeval=''";
      else
         $sql.="t1.dateeval!=''";
      $sql.=" AND t1.obsid=1 ORDER BY ";
      if(!$sort || $sort=="" || $sort=="last")
         $sql.="t3.last, t3.first";
      else if($sort=="date")
         $sql.="t2.offdate,t3.last,t3.first";
      else if($sort=="location")
         $sql.="t2.location,t3.last,t3.first";
   }
   $result=mysql_query($sql);
   $ix=0;
   if(mysql_num_rows($result)>0)
      echo "<tr align=left><td colspan=5><b>$sportname:</b></td></tr>";
   else echo "<tr align=left><td colspan=5><b>$sportname:</b> [No observations]</td></tr>";
//echo $sql."<br>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left>";
      if($row[offid]=='3427' && $row[official]!='') echo "<td>$row[official]</td>";
      else echo "<td>$row[first] $row[last]</td>";
      $date=split("-",$row[evaldate]);
      echo "<td>$date[1]/$date[2]/$date[0]</td>";
      echo "<td>$row[location]</td>";
      if($submitted=="no") $edit="yes";
      else $edit="";
      echo "<td><a target=\"_blank\" class=small href=\"".$page."?session=$session&obsid=1&gameid=$row[gameid]&offid=$row[offid]&edit=$edit\">Click to ";
      if($submitted=="no") echo "Edit";
      else echo "View";
      echo "</a></td>";
      if($submitted=="no")
      {
	 echo "<input type=hidden name=\"delid[$ix]\" value=\"$row[id]\">";
	 echo "<input type=hidden name=\"delsp[$ix]\" value=\"".$cursp."\">";
	 echo "<td align=center><input type=checkbox name=\"delete[$ix]\" value='x'></td>"; 
      }
      echo "</tr>";
      $ix++;
   }
}
echo "</table>";
if($submitted=="no")
   echo "<input type=submit name=submit value=\"Delete Checked\">";
echo "</form>";
echo "<a href=\"welcome.php?session=$session\" class=small>Home</a>&nbsp;&nbsp;&nbsp;";
echo $end_html;
?>
