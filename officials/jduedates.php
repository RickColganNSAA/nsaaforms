<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

if($table=='reg_duedates')
   $title="Online Registration";
else if($table=='app_duedates')
   $title="Application to Judge";
else if($table=='test_duedates')
   $title="Online Test";
else if($table=="vote_duedates")
   $title="Online Ballots";
else if($table=="timeslot_duedates") 
   $title="Host Contract Details (Director, Time/Dates, etc)";

if($save=="Save")
{
   for($i=0;$i<count($sport);$i++)
   {
      $curdate=$year[$i]."-".$mo[$i]."-".$day[$i];
      if($table=="test_duedates")
      {
	 $sql="UPDATE $table SET duedate='$curdate' WHERE test='$sport[$i]'";
	 $result=mysql_query($sql);
      }
      else if($table!="vote_duedates")
      {
         $sql="UPDATE $table SET duedate='$curdate' WHERE sport='$sport[$i]'";
         $result=mysql_query($sql);
      }
      else
      {
	 $curdate2=$year2[$i]."-".$mo2[$i]."-".$day2[$i];
	 $sql="UPDATE $table SET startdate='$curdate',enddate='$curdate2' WHERE sport='$sport[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo GetHeaderJ($session,"jduedates");

echo "<br><a href=\"jtourndates.php?session=$session\">&larr; Edit Postseason Dates for Apps to Host, Apps to Judge, Lodging</a><br>";

echo "<form method=post action=\"jduedates.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=table value=\"$table\">";
echo "<br><table cellspacing=0 cellpadding=5 class='nine'><caption><b>Edit ";
if(!$table) $table="app_duedates";
echo "<select name=\"table\" onchange=\"submit();\">";
$sql="SHOW TABLES LIKE '%_duedates'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[0]=="app_duedates") $label="Application to Judge";
   else if($row[0]=="reg_duedates") $label="Online Registration";
   else if($row[0]=="test_duedates") $label="Online Test";
   else if($row[0]=="vote_duedates") $label="Online Ballots";
   else if($row[0]=="timeslot_duedates") $label="Host Contract Details (Director, Time/Dates, etc)";
   else $label="";
   if($label!='')
   {
      echo "<option value='$row[0]'";
      if($table==$row[0]) echo " selected";
      echo ">$label</option>";
   }
}
echo "</select>";
echo " Due Dates:<br>";
echo "</caption>";
$sql="SELECT * FROM $table ";
if($table=="reg_duedates") $sql.="WHERE (sport='sp' OR sport='pp') ORDER BY duedate ASC";
else if($table=="vote_duedates")
   $sql.="WHERE sport='pp' OR sport='sp'";
else if($table=="app_duedates")
   $sql.="WHERE sport='sp' OR sport='pp' ORDER BY duedate";
else if($table=="test_duedates")
   $sql.="WHERE (test='sp' OR test='pp')";
else if($table=="timeslot_duedates")
   $sql.="WHERE sport='sp' OR sport='pp'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>";
   if($table=="reg_duedates") echo "Lock Online Registration for <b><u>".GetSportName($row[sport])."</u></b> after midnight on";
   else if($table=="vote_duedates") echo "Show ".GetSportName($row[sport])." Ballots from";
   else if($table=="app_duedates")
   {
      if($row[sport]=='sp') echo "Speech";
      else echo "Play";
   }
   else if($table=="test_duedates") echo "Lock <b><u>".GetSportName($row[test])."</b></u> Test after midnight on"; 
   else if($table=="timeslot_duedates") echo "Lock <u>".GetSportName($row[sport])."</u> District Directors from Editing Information on their Host Contract after";
   echo ": </td>";
   if($table!="vote_duedates")
      $date=split("-",$row[duedate]);
   else
      $date=split("-",$row[startdate]);
   echo "<input type=hidden name=\"sport[$ix]\" value=\"$row[1]\">";
   echo "<td align=right><select name=\"mo[$ix]\">";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($date[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"day[$ix]\">"; 
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($date[2]==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"year[$ix]\">";
   $year=date("Y"); $year0=$year-1; $year1=$year+1;
   for($i=$year0;$i<=$year1;$i++)
   {
      echo "<option";
      if($date[0]==$i) echo " selected";
      echo ">$i</option>";
   }
   echo "</select>";
   if($table=="vote_duedates")
   {
      $date=split("-",$row[enddate]);
      echo " to <select name=\"mo2[$ix]\">";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($date[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day2[$ix]\">";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($date[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year2[$ix]\">";
      $year=date("Y"); $year0=$year-1; $year1=$year+1;
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($date[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select>";
   }
   echo "</td></tr>";
   if($table=="reg_duedates" && $ix>0)
   {
      echo "<tr align=center><td colspan=2><a class=small target=\"_blank\" href=\"japplication.php?nsaasession=$session\">Preview Online Application</a></td></tr>";
   }
   $ix++;
}
echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save\"></td></tr>";
echo "</form>";

echo $end_html;
?>
