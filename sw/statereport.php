<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
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

echo $init_html;
echo $header;

echo "<center><br>";
echo "<table width=90%><caption><b>";
if($gender=='b') echo "Boys ";
else echo "Girls ";
echo "Swimming State Entry Form Report:</b><hr></caption>";
for($i=0;$i<count($sw_events);$i++)
{
   echo "<tr align=left><td><table cellspacing=3 cellpadding=3>";
   echo "<tr align=left><th class=smaller align=left colspan=3>$sw_events[$i]:</th></tr>";
   $table="sw_state_".$gender;
   $sql="SELECT * FROM $table WHERE event='$sw_events[$i]' ORDER BY entry";
   if(ereg("Diving",$sw_events[$i])) $sql.=" DESC";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT school FROM swschool WHERE sid='$row[schoolid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $cursch=$row2[0];

      if(!ereg("Diving",$sw_events[$i])) $curperf=ConvertFromSec($row[entry]);
      else $curperf=$row[entry];

      if(ereg("Relay",$sw_events[$i]))
      {
	 $studs=split("/",$row[studs]);
	 for($j=0;$j<count($studs);$j++)
	 {
	    $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$studs[$j]'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    if($studs[$j]!="Choose Student")
	       $relaystuds[$j]="$row2[0] $row2[1] (".GetYear($row2[2]).")";
	    else $relaystuds[$j]="&nbsp;";
	 }
	 echo "<tr align=left valign=top><td width=500>";
	 for($j=0;$j<count($relaystuds);$j++)
	 {
	    echo $relaystuds[$j];
	    if($j<(count($relaystuds)-1) && trim($relaystuds[$j])!="&nbsp;")
	       echo ", ";
	 }
	 echo "</td><td>$cursch</td><td>$curperf</td></tr>";
      }
      else
      {
	 $stud=$row[studs];
	 $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$stud'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $curstud="$row2[0] $row2[1] (".GetYear($row2[2]).")";

	 echo "<tr align=left valign=top><td>$curstud</td><td>$cursch</td><td>$curperf</td></tr>";
      }
   }
   echo "</table></td></tr>";
}
echo "</table>";

echo $end_html;
?>
