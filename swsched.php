<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
$year0=GetFallYear('sw');
$year1=$year0+1;

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

$sport='sw';

//GET SID FROM swschool TABLE
if($level!=1)
   $sid=GetSID($session,$sport);
else
   $sid=GetSID2($school,$sport);
if($sid=="" || $sid==0) { echo "Unexpected Error: No Swimming School Found."; exit(); }

if($save)
{
   $dateerrors="";
   for($i=0;$i<count($meetid);$i++)
   {
      if($month[$i]<6) $curyr=$year1;
      else $curyr=$year0;
      $feb15=mktime(23,59,59,2,15,$year1);
      $aug1=mktime(0,0,0,8,1,$year0);
      $curdate=mktime(0,0,0,$month[$i],$day[$i],$curyr);
      //check that 1) date was entered and 2) date is not after Feb 15 and 3) date is not before 08/01 of this year
      if($delete[$i]=='x') 	//DELETE MEET
      {
	 $sql="DELETE FROM swsched WHERE id='$meetid[$i]'";
	 $result=mysql_query($sql);
      }
      else if(($month[$i]=='00' || $day[$i]=='00' || $curdate<$aug1 || $curdate>$feb15) && $meetname[$i]!='')
      {
	 $dateerrors.="$meetname[$i] - $month[$i]/$day[$i]<br>";
      }
      else if($month[$i]!='00' && $day[$i]!='00' && $meetname[$i]!='') 	//ALL IS WELL, CARRY ON
      {
	 $curdate=$curyr."-".$month[$i]."-".$day[$i];
	 $meetname[$i]=addslashes($meetname[$i]);
	 $site[$i]=addslashes($site[$i]);
         if($meetid[$i]=='0')
	    $sql="INSERT INTO swsched (sid,meetdate,meetname,site) VALUES ('$sid','$curdate','$meetname[$i]','$site[$i]')";
	 else
	    $sql="UPDATE swsched SET sid='$sid',meetdate='$curdate',meetname='$meetname[$i]',site='$site[$i]' WHERE id='$meetid[$i]'";
	 $result=mysql_query($sql);
         //echo "$sql<br>";
      }
   }
}

echo $init_html;
echo GetHeader($session);

if($level==1)
   echo "<br><a class=small href=\"swschedadmin.php?session=$session\">Return to Swimming Schedules Admin</a><br>";
echo "<br><table width=100%><tr align=center><td>";
$sql="SELECT lockdate FROM wildcard_duedates WHERE sport='sw'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(PastDue($row[0],0) && $level!=1 && $school!="Test's School")
{
   $date=split("-",$row[0]);
   $duedate="$date[1]/$date[2]/$date[0]";
   echo "<br><br>This form was locked on $duedate.";
   echo $end_html;
   exit();
}

echo "<form method=post action=\"swsched.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
//echo $sid;
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style='border:#e0e0e0 1px solid;'>";
echo "<caption><b>$school $year0-$year1 Swimming Schedule:</b><br>";
echo "<div class='alert'><b>INSTRUCTIONS:</b><ul><li>Please use this form to enter your school's swimming schedule for the <b>$year0-$year1 season</b>.</li><li>Enter the <b>month and day</b> for each meet. The year will be automatically determined by the program.</li><li>Then enter the <b>meet name</b> and the <b>host school</b>.</li><li><b>Click \"Save\"</b> to save your changes.</li><li><b><u>You may enter up to 5 meets at a time.</u></b>  Then you must <b>click \"Save\"</b> in order to add more.</li><li>To <b><u>DELETE a meet</b></u>, check the box in the Delete column and click \"Save.\"</li></ul><br>";
echo "NOTE: Do not enter any meets <b>AFTER February 15th</b>.  The NSAA does not count any meets AFTER the 15th of February. You may swim after that date.  However the schedule only needs to reflect meets up through the 15th.</div>";
if($dateerrors!='')
{
   echo "<br><div class='error'>The following meets were entered with invalid DATES. You must enter meets that fall within the $year0-$year1 season. Also, you CANNOT enter meets AFTER February 15th, $year1.<br><br>$dateerrors</div>";
}
if($save)
{
   echo "<br><div class='help'>Your changes were saved.</div><br>";
}
echo "</caption>";
echo "<tr align=center><td><b>Meet Date</b></td>";
echo "<td><b>Meet Name</b></td><td><b>Host School</b></td><td><b>Delete</b></td>";
echo "</tr>";

//get schedule entries in database for this school
$sql="SELECT * FROM swsched WHERE sid='$sid' ORDER BY meetdate";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"meetid[$ix]\" value=\"$row[id]\">";
   echo "<tr valign=top align=center>";
   $curdate=split("-",$row[meetdate]);
   echo "<td><select name=\"month[$ix]\"><option value='00'>Month</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $mo="0".$i;
      else $mo=$i;
      echo "<option";
      if($mo==$curdate[1])
	 echo " selected";
      echo ">$mo</option>";
   }
   echo "</select>/<select name=\"day[$ix]\"><option value='00'>Day</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($d==$curdate[2])
	 echo " selected";
      echo ">$d</option>";
   }
   echo "</select></td>";
   echo "<td><input type=text size=30 class=tiny name=\"meetname[$ix]\" value=\"$row[meetname]\"></td>";
   echo "<td><input type=text size=30 class=tiny name=\"site[$ix]\" value=\"$row[site]\"></td>";
   echo "<td><input type=checkbox name=\"delete[$ix]\" value='x'></td>";
   echo "</tr>";
   $ix++;
}
$max=$ix+5;
while($ix<$max)
{
   echo "<input type=hidden name=\"meetid[$ix]\" value=\"0\">";
   echo "<tr valign=top align=center><td><select name=\"month[$ix]\"><option value='00'>Month</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $mo="0".$i;
      else $mo=$i;
      echo "<option>$mo</option>";
   }
   echo "</select>/<select name=\"day[$ix]\"><option value='00'>Day</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option>$d</option>";
   }
   echo "</select></td>";
   echo "<td><input type=text size=30 class=tiny name=\"meetname[$ix]\"></td>";
   echo "<td><input type=text size=30 class=tiny name=\"site[$ix]\"></td>";
   echo "<td><input type=checkbox name=\"delete[$ix]\" value='x'></td>";
   echo "</tr>";
   $ix++;
}

echo "<tr align=center><td colspan=4><div class='alert'>You may only enter up to <b>5 new meets at a time.</b> To enter <b>more</b>, please <b>click \"Save\"</b>.</div><br><input type=submit name=save value=\"Save\"></td></tr>";
echo "</table>";

echo $end_html;
?>
