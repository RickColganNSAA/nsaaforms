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

if($save=="Save")
{
   for($i=0;$i<count($sport);$i++)
   {
      $curdate=$year[$i]."-".$mo[$i]."-".$day[$i];
      if($table=="test_duedates" || $table=="test2_duedates")
      {
	 $sql="UPDATE $table SET duedate='$curdate' WHERE test='$sport[$i]'";
	 $result=mysql_query($sql);
	 if($table=="test2_duedates")
	 {
	    $curdate2=$year2[$i]."-".$mo2[$i]."-".$day2[$i];
            $sql="UPDATE $table SET showdate='$curdate2' WHERE test='$sport[$i]'";
            $result=mysql_query($sql);
	 }
	 else
	 {
	    $fakedate=$fakey[$i]."-".$fakem[$i]."-".$faked[$i];
	    $sql="UPDATE $table SET fakeduedate='$fakedate',daystowait='$daystowait[$i]' WHERE test='$sport[$i]'";
	    $result=mysql_query($sql);
	 }
      }
      else if($table=="vote_duedates")
      {
	 $curdate2=$year2[$i]."-".$mo2[$i]."-".$day2[$i];
	 $sql="UPDATE $table SET startdate='$curdate',enddate='$curdate2' WHERE sport='$sport[$i]'";
	 $result=mysql_query($sql);
      }
      else if($table=="rulesmeetingdates")
      {
	 $curdate2="$year2[$i]-$mo2[$i]-$day2[$i]";
         $curdate3="$year3[$i]-$mo3[$i]-$day3[$i]";
         $curdate4="$year4[$i]-$mo4[$i]-$day4[$i]";
         $sql="UPDATE $table SET coachesonly='$coachesonly[$i]',officialsonly='$officialsonly[$i]',startdate='$curdate',paydate='$curdate2',latedate='$curdate3',enddate='$curdate4',fee='$fee[$i]',latefee='$latefee[$i]',totaltime='$totaltime[$i]',lockedversion='$lockedversion[$i]',ppfile='$ppfile[$i]' WHERE sport='$sport[$i]'";
         $result=mysql_query($sql);
      }
      else 
      {
         $sql="UPDATE $table SET duedate='$curdate' WHERE sport='$sport[$i]'";
         $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo GetHeader($session,"duedates");

echo "<br><a href=\"tourndates.php?session=$session\">&larr; Edit Postseason Dates for Apps to Host, Apps to Officiate, Lodging</a><br>";

echo "<form method=post action=\"duedates.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=table value=\"$table\">";
echo "<br><table border=1 cellspacing=0 cellpadding=5 class=nine><caption><b>Edit ";
echo "<select name=\"table\" onchange=\"submit();\">";
$sql="SHOW TABLE STATUS LIKE '%_duedates'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[0]'";
   if($table==$row[0]) echo " selected";
   echo ">$row[17]</option>";
}
$sql="SHOW TABLE STATUS LIKE 'rulesmeetingdates'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[0]'";
   if($table==$row[0]) echo " selected";
   echo ">$row[17]</option>";
}
echo "</select>";
echo " Due Dates by Sport: <input type=submit name=go value=\"Go\">";
if($table && $table!='')
{
if($table=='reg_duedates' || $table=="reglate_duedates")
   echo "<p><a class=small target=\"_blank\" href=\"application.php?secret=1\">Preview Online Official's Registration Form</a></p>";
else if($table=="rulesmeetingdates")
   echo "<p><a class=small target=\"_blank\" href=\"/nsaaforms/officials/rulesschedule.php\">Preview Rules Meeting Schedule</a></p>";
echo "</caption>";
$sql="SELECT * FROM $table ";
if($table=="reg_duedates") $sql.="WHERE sport!='judge' ";
if($table=="vote_duedates" || $table=="rulesmeetingdates")
   $sql.="ORDER BY startdate,enddate";
else
   $sql.="ORDER BY duedate";
echo mysql_error();
//echo $sql;
$result=mysql_query($sql);
if($table=="rulesmeetingdates")
{
   echo "<tr align=center><td>&nbsp;</td><td>Open (for FREE)<br />Starting</td><td>Regular<br />Fee</td><td>Starting at<br />midnight on</td><td>Late Fee</td><td>Starting at<br />midnight on</td><td>FINAL Due Date</td><td>Length of<br />Video (sec)*</td><td>FILE Locations**</td></tr>";
}
else if($table=="test_duedates")
{
   echo "<tr align=center><td>&nbsp;</td><td><b>FAKE Due Date</b><br>(What the officials will SEE)</td><td><b>ACTUAL Due Date</b><br>(What will be enforced)</td><td><b>How many days after the (real)<br>due date can the officials see their<br>test results in full?</b></td></tr>";
}
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor=#E0E0E0";
   if($row[1]=="wrassessor") echo "><td><b>WR ASSESSORS:</b>";
   else echo "><td><b>".GetSportName($row[1]).":</b>";
   if($table!="vote_duedates" && $table!="rulesmeetingdates")
      $date=split("-",$row[duedate]);
   else
      $date=split("-",$row[startdate]);
   if($table=="rulesmeetingdates")
   {
      //Checkboxes for Coaches Only or Officials Only
      echo "<p class=\"small\"><input type=\"checkbox\" name=\"coachesonly[$ix]\" value=\"x\"";
      if($row[coachesonly]=='x') echo " checked";
      echo "> Coaches ONLY<br /><input type=\"checkbox\" name=\"officialsonly[$ix]\" value=\"x\"";
      if($row[officialsonly]=='x') echo " checked";
      echo "> Officials ONLY</p>";
   }
   echo "<input type=hidden name=\"sport[$ix]\" value=\"$row[1]\"></td>";
   if($table=="test2_duedates")	//SHOW DATE as well
   {
      $date2=explode("-",$row[showdate]);
      echo "<td width='300px' align=right>show on: <select name=\"mo2[$ix]\">";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($date2[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day2[$ix]\">";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($date2[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year2[$ix]\">";
      $year=date("Y"); $year0=$year-1; $year1=$year+1;
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($date2[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td>";
   }
   else if($table=="test_duedates")	//FAKE due date too
   {
      $date2=explode("-",$row[fakeduedate]);
      echo "<td align=right><select name=\"fakem[$ix]\">";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($date2[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"faked[$ix]\">";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($date2[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"fakey[$ix]\">";
      $year=date("Y"); $year0=$year-1; $year1=$year+1;
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($date2[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td>";
   }
   //DUE DATE 
   echo "<td align=right";
   if($table=="vote_duedates") echo " width='500px'>start: ";
   else if($table=="test_duedates" || $table=="rulesmeetingdates") echo ">";
   else echo " width='300px'>due: ";
   echo "<select name=\"mo[$ix]\">";
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
      echo " end: <select name=\"mo2[$ix]\">";
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
      echo "</td>";
   }
   if($table=="test_duedates")	//HOW MANY DAYS DO THEY HAVE TO WAIT TO SEE RESULTS?
   {
      echo "<td align='center'><input type=text name=\"daystowait[$ix]\" value=\"$row[daystowait]\" size=4> days</td>";
   }
   else if($table=="rulesmeetingdates")
   {
      //IN ADDITION TO startdate, we have paydate, latedate, enddate, fee, latefee, totaltime, ppfile, lockedversion
	//PAY fee STARTING paydate
      $date=split("-",$row[paydate]);
      echo "<td>$<input type=text size=4 name=\"fee[$ix]\" value=\"$row[fee]\"></td><td><select name=\"mo2[$ix]\">";
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
      echo "</select></td>";
	//PAY latefee STARTING latedate
      $date=split("-",$row[latedate]);
      echo "<td>$<input type=text size=4 name=\"latefee[$ix]\" value=\"$row[latefee]\"></td><td><select name=\"mo3[$ix]\">";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($date[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day3[$ix]\">";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($date[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year3[$ix]\">";
      $year=date("Y"); $year0=$year-1; $year1=$year+1;
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($date[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td>";
	//FINAL DUE DATE enddate
      $date=split("-",$row[enddate]);      
      echo "<td><select name=\"mo4[$ix]\">";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($date[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day4[$ix]\">";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($date[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year4[$ix]\">";
      $year=date("Y"); $year0=$year-1; $year1=$year+1;
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($date[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td>";
	//TOTAL TIME IN SECONDS
      echo "<td><input type=text size=5 name=\"totaltime[$ix]\" value=\"$row[totaltime]\"></td>";
        //LOCKED VERSION
      echo "<td align='left'>LOCKED:<br><input type=text size=30 name=\"lockedversion[$ix]\" value=\"$row[lockedversion]\">";
	if($row[lockedversion]!='') echo " <a class=\"small\" href=\"$row[lockedversion]\" target=\"_blank\">Preview</a>";
        //UNLOCKED VERSION
      echo "<br />UNLOCKED:<br><input type=text size=30 name=\"ppfile[$ix]\" value=\"$row[ppfile]\">";
        if($row[ppfile]!='') echo " <a class=\"small\" href=\"$row[ppfile]\" target=\"_blank\">Preview</a>";
      echo "</td>";
   }
   echo "</tr>";
   $ix++;
}
if(mysql_num_rows($result)>0)
{
   if($table=="rulesmeetingdates") $colspan=9;
   else if($table=="test_duedates") $colspan=4;
   else if($table=="test2_duedates") $colspan=3;
   else $colspan=2;
   echo "<tr align=center><td colspan=$colspan><br>";
   if($table=="rulesmeetingdates")
   {
      echo "<div class=\"alert\" style=\"width:700px;text-align:left;\"><p><b>* Length of Video Presentation in Seconds:</b> Enter a number about 30-45 seconds less than the actual length of the video presentation. This number will be used to ensure that the user watch the video in its entirety.</p>
	<p><b>** FILE Locations:</b> Enter the path to the location of both the locked version and the unlocked verson of the video presentation. The LOCKED version is the one that does not allow the user to advance slides on his or her own. Click the PREVIEW link to ensure you entered a valid location.</p></div>";
   }
   echo "<input type=submit name=save value=\"Save\"></td></tr>";
}
echo "</table>";
if($table=="test_duedates")	//Explanation for "daystowait"
{
   echo "<p>* Examples for number of days after the due date officials must wait to see their test results in full:<ul style=\"max-width:600px;\">
	<li>0 = The officials will be able to see their test results in full on 12:01 am the day following the due date (in other words, immediately after the the clock strikes midnight on the due date and tests can no longer be submitted.)</li>
	<li>1 = The officials will have to wait a full day after the due date. For example, if the due date is February 16th (at midnight), and you enter a 1 here, the officials will be able to see the results in full on February 18th at 12:01am (essentially midnight on the 17th).</li>
	<li>4 = If the due date is October 1st, a 4 means they will be able to see their results in full AFTER October 5th at midnight.</li>
	</ul></p>";
}
} //END IF TABLE
echo "</form>";

echo $end_html;
?>
