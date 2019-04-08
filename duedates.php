<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($save=="Save")
{
   for($i=0;$i<count($sport);$i++)
   {
      $curdate=$year[$i]."-".$mo[$i]."-".$day[$i];
	  
	  if($table=="believers_duedates") {
		$sql="UPDATE $table SET duedate='$curdate' WHERE id=1";
		$result=mysql_query($sql);
	  }
      elseif($table!="vote_duedates")
      {
         if($table=="form_duedates") $field="form";
         else $field="sport";
         if($table=="wildcard_duedates") $date="lockdate";
         else $date="duedate";
         $sql="UPDATE $table SET $date='$curdate' WHERE $field='$sport[$i]'";
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
echo GetHeader($session);

echo "<form method=post action=\"duedates.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=table value=\"$table\">";
echo "<br><table cellspacing=0 cellpadding=5><caption><b>Edit ";
echo "<select name=\"table\" onchange=\"submit();\">";
$sql="SHOW TABLES LIKE '%_duedates'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[0]'";
   if($table==$row[0]) echo " selected";
   echo ">";
   if($row[0]=="app_duedates") echo "Applications to Host";
   else if($row[0]=="elig_duedates") echo "Eligibility Lists";
   else if($row[0]=="form_duedates") echo "District Entry Forms";
   else if($row[0]=="wildcard_duedates") echo "Wildcard Schedules";
   else if($row[0]=="misc_duedates") echo "Other Forms";
   else if($row[0]=="reim_duedates") echo "Reimbursement Forms";
   else if($row[0]=="believers_duedates") echo $string.="Believers & Achievers Form";
   echo "</option>";
}
echo "</select>";
if($table=='believers_duedates')
echo " Due Date:<br>";
else
echo " Due Dates by Sport:<br>";
if($table=='reg_duedates' || $table=="reglate_duedates")
   echo "<a class=small target=new href=\"application.php\">Preview Online Official's Registration Form</a><br>";
else if($table=='wildcard_duedates')
   echo "<table><tr align=left><td class=nine><i>The Wildcard Schedules for each sport will be locked for editing by the schools after the dates listed below.<br>Schools can only enter schedules for sports whose due dates listed below are <u>in the future</u>.  Thus to \"activate\" a sport's schedule, you must change the due date to one that is in the future.</i></td></tr></table>";
echo "<br></caption>";
if($table=="wildcard_duedates") $duedate="lockdate";
else $duedate="duedate";
$sql="SELECT * FROM $table ";
$sql.="ORDER BY $duedate";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($table!="form_duedates" || ($row[1]!="spshowentries" && $row[1]!='ppshowentries'))
   {
      //"spshowentries" will show right under Speech, indented; same with ppshowentries (after PP)
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor=#E0E0E0";
   echo "><td><b>";
   if($table=="misc_duedates")
   {
      if($row[sport]=="declaration") echo "Declarations";
      else if($row[sport]=="cc_classd") echo "Class D Cross-Country Survey";
      else if($row[sport]=="swsched") echo "Swimming Schedules";
      else if($row[sport]=="proposal") echo "Proposals";
      else if($row[sport]=="priority") echo "Priority Forms";
      else if($row[sport]=="fbsched") echo "Football Schedules";
      else if(ereg("allstatenom",$row[sport]))
      {
	 if(ereg("fall",$row[sport])) echo "All State Academic Nominations - FALL";
         else if(ereg("winter",$row[sport])) echo "All State Academic Nominations - WINTER";
         else echo "All State Academic Nominations - SPRING";
      }
      else if(ereg("registration",$row[sport]))
      {
         if(ereg("fall",$row[sport])) echo "Registration - FALL SPORTS";
         else if(ereg("winter",$row[sport])) echo "Registration - WINTER SPORTS & NON-ATHLETIC ACTIVITIES";
         else echo "Registration - SPRING SPORTS";
      }
      else if($row[sport]=="membership")
	 echo "Membership";
      else if($row[sport]=="wrassessor")
	 echo "Wrestling Assessor Training";
      else echo $row[sport];
   }
   else if($table=="form_duedates")
   {
      if($row[1]=="te_bstate") echo "Boys Tennis (Class A)";
      else if($row[1]=="te_gstate") echo "Girls Tennis (Class A)";
      else if($row[1]=="te_b") echo "Boys Tennis (Class B)";
      else if($row[1]=="te_g") echo "Girls Tennis (Class B)";
      else if($row[1]=="jo_contest") echo "Journalism Contest Submissions";
      else if($row[1]=="jo") echo "Journalism State Entry Form";
      else echo GetActivityName($row[1]);
   }
   else 
      echo GetActivityName($row[1]);
   if($row[1]=='fb11') echo " (Class A/B/Week 0 Teams)";
   else if($row[1]=='fb68') echo " (Class D1/D2)";
   else if($row[1]=='fb11_C') echo " (Class C1/C2)";
   if($table=="believers_duedates") echo "Due Date";
   echo ":</b></td>";
   if($table!="vote_duedates")
      $date=split("-",$row[$duedate]);
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
   echo "</td>";
   echo "</tr>";
   $ix++;
   if($table=="form_duedates" && ($row[1]=="pp" || $row[1]=="sp"))
   {
      //now show sp/ppshowentries, indented under Speech/PP
      if($row[1]=="pp") $sportname="Play Production";
      else $sportname="Speech";
      echo "<tr align=left";
      if($ix%2==0) echo " bgcolor=#E0E0E0";
      echo "><td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Show District $sportname Entries to Hosts AFTER:</b></td>";
      $sql2="SELECT * FROM $table WHERE form='".$row[1]."showentries'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $date=split("-",$row2[$duedate]);
      echo "<input type=hidden name=\"sport[$ix]\" value=\"".$row[1]."showentries\">";
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
      $ix++;
   }
   } //end if not Speech/Play Show Entries
}
echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save\"></td></tr>";
echo "</form>";

echo $end_html;
?>
