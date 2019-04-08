<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

$states=array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WV","WA","WI","WY","DC");

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch || $level!=1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

//Figure out what the last year archived was.  Will show link to that list of transfer students
$temp=split("nsaascores",$database);
$years=$temp[1];
if($temp[1]!='')
{
   $year1=substr($temp[1],0,4);
   $year2=substr($temp[1],4,4);
}
else
{
   echo "ERROR";
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br><table cellspacing=0 cellpadding=3 style=\"border:#a0a0a0 1px solid;\" frame=all rules=all>";
echo "<caption><b>$year2 INCOMING TRANSFER STUDENTS</b><br>";
echo "<i>This form is <u>past due</u>.  The transfer students your school submitted to the NSAA for the $nextyear school year are shown below.</i><br><br>";
echo "</caption>";
$sql="SELECT * FROM $database.transfers WHERE school='$school2' ORDER BY last,first";
$result=mysql_query($sql);
echo "<tr align=center><td><b>Student Transferring</b></td>";
echo "<td><b>School Transferred From</b></td><td><b>Comments</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left valign=top><td><b>$row[first] $row[last]</b><br>";
   $date=split("-",$row[dob]);
   echo "DOB: $date[1]/$date[2]/$date[0]<br>";
   echo "Grade: $row[grade]</td>";
   echo "<td>$row[otherschool]";
   if($row[othercity]!='')
   {
      echo "<br>$row[othercity], $row[otherstate]<br>";
      echo "($row[publicprivate])";
   }
   echo "</td>";
   echo "<td>$row[comments]</td>";
   echo "</tr>";
}
echo "</table>";

echo "</td></tr></table>";
echo $end_html;
?>
