<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($save)
{
   $sql="UPDATE muawardsduedate SET duedate='$year-$month-$day'";
   $result=mysql_query($sql);
}

echo $init_html;
echo $header;

echo "<br>";
echo "<table><caption><b>Music District Entry Form Admin:</b></caption>";
echo "<tr align=left><td>";
echo "<ul>";
   $sql2="SELECT * FROM muschools WHERE submitted>0";
   $result2=mysql_query($sql2);
echo "<br><li><a href=\"entriesadmin.php?session=$session\">Submitted Music Entries (and schools who have NOT submitted their entry)</a> (".mysql_num_rows($result2)." Submitted)</li>";
echo "<br><li><a href=\"unlockadmin.php?session=$session\">UNLOCK Entries for Schools who Missed the Deadline</a></li>";
echo "<br><li><a href=\"distadmin.php?session=$session\">Manage District Coordinators</a></li>";
echo "<br><li><a href=\"siteadmin.php?session=$session\">Manage District Site Information</a></li>";
        $sql="SELECT * FROM finrpt_entry WHERE datesub>0";
        $result=mysql_query($sql);
echo "<br><li><a href=\"dmcfinrptlist.php?session=$session\">Submitted District Music Financial Reports</a> (".mysql_num_rows($result)." Submitted)</li>";
echo "<br><li><a href=\"feeadmin.php?session=$session\">Manage Fee Schedules</a></li>";
echo "<br><li><a href=\"judgesadmin.php?session=$session\">Manage NSAA Music Judges</a></li>";
echo "<br><li><a href=\"mucoops.php?session=$session\">Manage Music Co-ops</a></li>";
echo "<br><li><a href=\"viewawardwinners.php?session=$session\">View Submitted Lists of District Music Award Winners</a><br>";
	echo "<form method=post action='muadmin.php'><input type=hidden name=session value=\"$session\">";
        if($save)
	   echo "<div class=alert>The due date has been saved.</div><br>";
   	$sql="SELECT * FROM muawardsduedate";
        $result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$date=split("-",$row[duedate]);
        echo "Edit Due Date for Award Winner Submissions: <select name=\"month\"><option value='00'>MM</option>";
      	for($i=1;$i<=12;$i++)
	{
	   if($i<10) $m="0".$i;		
	   else $m=$i; 
	   echo "<option value=\"$m\"";
	   if($date[1]==$m) echo " selected";
	   echo ">$m</option>";
	}
	echo "</select>/<select name=\"day\"><option value='00'>DD</option>";
	for($i=1;$i<=31;$i++)
	{
	   if($i<10) $d="0".$i;
	   else $d=$i;
	   echo "<option value=\"$d\"";
	   if($date[2]==$d) echo " selected";
	   echo ">$d</option>";
	}
	echo "</select>/<select name=\"year\">";
	$year1=date("Y")-1; $year2=date("Y")+1;
 	for($i=$year1;$i<=$year2;$i++)
	{
	   echo "<option value=\"$i\"";
	   if($date[0]==$i) echo " selected";
	   echo ">$i</option>";
	}
	echo "</select>&nbsp;<input type=submit name=\"save\" value=\"Save\">";	
        echo "</form>";
echo "</li>";
echo "<br><li><a href=\"createcertificate.php?session=$session\">Generate an Award Certificate (from Scratch)</a></li>";
echo "</ul>";
echo "</td></tr></table>";

echo $end_html;
?>
