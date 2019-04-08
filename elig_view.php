<?php
//elig_view.php: Used mainly by coach (level 3):
//	shows uneditable list of students participating
//	in the specified activity

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);
$activity_name=GetActivity($session);
$abbrev=GetActivityAbbrev2($activity_name);
$abbrev=ereg_replace("_","",$abbrev);
if(!$schtable=GetTeamTable($abbrev))
{
   $sid=0; $sidschool=$school;
}
else
{
   $temp=split("school",$schtable);
   $sidsport=$temp[0];
   $sid=GetSID2($school,$sidsport);
   $sql="SELECT * FROM $schtable WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sidschool=$row[school];
   $i=0;
      $sql2="SELECT * FROM headers WHERE id='$row[mainsch]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
   $coopingschs[$i]=addslashes($row2[school]); $i++;
   if($row[othersch1]>0)
   {
      $sql2="SELECT * FROM headers WHERE id='$row[othersch1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
   $coopingschs[$i]=addslashes($row2[school]); $i++;
   }
   if($row[othersch2]>0)
   {
      $sql2="SELECT * FROM headers WHERE id='$row[othersch2]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
   $coopingschs[$i]=addslashes($row2[school]); $i++;
   }
   if($row[othersch3]>0)
   {
      $sql2="SELECT * FROM headers WHERE id='$row[othersch3]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
   $coopingschs[$i]=addslashes($row2[school]); $i++;
   }
}

if(ereg("Music",$activity_name) || $activity_name=="Orchestra")
{
   $activity_name="Music";
}
$sql="SELECT * FROM eligibility WHERE school='$school2' AND ";
if($sid>0)	//possible co-oping schools
{
   $sql="SELECT * FROM eligibility WHERE (";
   for($i=0;$i<count($coopingschs);$i++)
   {
      $sql.="school='$coopingschs[$i]' OR ";
   }
   $sql=substr($sql,0,strlen($sql)-4).") AND ";
}
if(ereg("Football",$activity_name))
   $activity_name="Football";
$sql.=GetActivityQuery($activity_name);
$sql=substr($sql,0,strlen($sql)-3);
$sql.=" ORDER BY last";
$result=mysql_query($sql);
?>
<html>
<head>
   <title>NSAA Home</title>
   <link rel="stylesheet" href="../css/nsaaforms.css" type="text/css">
</head>
<?php
if($print!=1)
{
   $header=GetHeader($session);
   echo $header;
}
?>
<br>
<?php 
if(mysql_num_rows($result)>0)
{
?>
<table cellspacing=0 cellpadding=5 border=1 bordercolor=#000000>
<caption><b><?php echo "$sidschool $activity_name"; ?>&nbsp;Eligibility List</b>
  <br>There are <b><?php echo mysql_num_rows($result); ?></b> students signed up for <?php echo $activity_name; ?>:</caption>
<tr align=center>
<th class=smaller>Name</th><th class=smaller>School</th><th class=smaller>Gender</th>
<th class=smaller>DOB</th><th class=smaller>Semester</th>
<th class=smaller>Eligible</th><th class=smaller>Transfer</th>
<th class=smaller>Foreign<br>Exchange</th>
<th class=smaller>Enrollment<br>Option</th><th class=smaller>Activities</th>
</tr>
<?php
}
else
{
?>
<table>
<tr align=left><th>No students are listed as participating in <?php echo $activity_name; ?> for <?php echo $school; ?>.</th></tr>
<tr align=left><th><br>Please contact your Athletic/Activities Director to resolve this issue.</th></tr></table>
<br><br>
<a href="welcome.php?session=<?php echo $session; ?>">Home</a>
<?php
exit();
}

$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left";
   if($ix%2==0 && $print!=1) echo " bgcolor=#D0D0D0>";
   else echo ">";
   echo "<td>$row[2], $row[3] $row[4]</td><td>$row[school]</td>";
   echo "<td>$row[5]</td>";
   echo "<td>$row[7]</td>";
   echo "<td>$row[8]</td>";
   echo "<td";
   if($row[11]=="y") echo ">Yes";
   else
   {
      if($print!=1) echo " bgcolor=#FF0000";
      echo ">No";
   }
   echo "</td><td";
   if($row[9]=="y") 
   {
      if($print!=1) echo " bgcolor=#66FF66";
      echo ">Yes";
   }
   else echo ">No";
   echo "</td><td>";
   if($row[13]=="y") echo "Yes";
   else echo "No";
   echo "</td>";
   echo "<td>";
   if($row[15]=="y") echo "Yes";
   else echo "No";
   echo "</td>";
   //Get Other Activities student is in:
   $act_str="";
   for($i=0;$i<count($activity);$i++)
   {
      if($row[$i+17]=="x")
      {
	 if($activity_name=="Music" && ($activity[$i]=='im' || $activity[$i]=='vm'))
	    $act_str.="<b>$activity[$i]</b>, ";
	 else
	    $act_str.="$activity[$i], ";
      }
   }
   $act_str=substr($act_str,0,strlen($act_str)-2);
   $act_str=strtoupper($act_str);
   echo "<td>$act_str</td>";
   echo "</tr>";
   $ix++;
}
?>
</table>
<br><br>
<?php
if($print!=1)
{
?>
<a href="elig_view.php?session=<?php echo $session; ?>&print=1" target=new>Printer-Friendly Version</a>
&nbsp;&nbsp;
<a href="welcome.php?session=<?php echo $session; ?>">Home</a>
<?php
}
?>
</center>
</td><!--End Main Body-->
</tr>
</table>
</body>
</html>


	 
