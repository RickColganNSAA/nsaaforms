<?php

require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

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
echo "<table width=100%><tr align=center><td>";

//$sql="SELECT * FROM mujudges ORDER BY lastupdate DESC,last,first";
$sql="SELECT *,YEAR(lastupdate) AS yearupdated FROM mujudges ORDER BY last,first";
$result=mysql_query($sql);
if(!$export)
{
echo "<table cellspacing=0 cellpadding=1 frame=all rules=all style=\"$border:#a0a0a0 1px solid;\">";
$year1=GetFallYear('mu');
$year2=$year1+1;
echo "<caption><b>$year1-$year2 NSAA Music Judges<br></b>";
echO "The following is a list of judges indicating a willingness to judge District Music Contests.<br>";
echo "<b>Music Judges listed alphabetically.</b></caption>";
echo "<tr align=center><td rowspan=2><b>Year<br>Upda<br>-ted</b></td><td rowspan=2><b>Last, First</b></td>";
echO "<td rowspan=2><b>Address</b></td><td rowspan=2><b>City, State, Zip</b></td>";
echo "<td rowspan=2><b>E-mail</b></td><td rowspan=2><b>Phone</b></td>";
echo "<td colspan=7><b>Judging Preferences</b></td>";
echo "<td rowspan=2><b>Currently<br>Teaching<br>Music</b></td>";
echo "<td rowspan=2><b>Years<br>Teaching<br><u>&nbsp;&nbsp;Music&nbsp;&nbsp;</u><br>Years<br>Judging<br>Music</b></td>";
echo "<td rowspan=2><b>NSAA Sites<br>Judged - Year</b></td>";
echo "</tr>";
echo "<tr align=top align=center><td><b>V<br>o<br>c<br>a<br>l</b><td><b>P<br>i<br>a<br>n<br>o</b></td>";
echo "<td><b>O<br>r<br>c<br>h<br>e<br>s<br>t<br>r<br>a</b></td><td><b>I<br>n<br>s<br>t<br>r<br>u<br>m<br>e<br>t<br>a<br>l</b></td>";
echo "<td><b>B<br>r<br>a<br>s<br>s</b></td><td><b>W<br>o<br>o<br>d<br>w<br>i<br>n<br>d</b></td>";
echo "<td><b>P<br>e<br>r<br>c<br>u<br>s<br>s<br>i<br>o<br>n</b></td>";
echO "</tr>";
}
else
{
   $csv="\"Last Update\",\"First\",\"Last\",\"Address 1\",\"Address 2\",\"City/State\",\"Zip\",\"E-mail\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"NSAA Sites Judge and Year\"\r\n";
}
while($row=mysql_fetch_array($result))
{
   if(!$export)
   {
   echo "<tr align=left><td align=center>$row[yearupdated]</td><td>$row[last], $row[first]</td>";
   echo "<td>$row[address1]<br>$row[address2]</td><td>$row[cityst]<br>$row[zip]</td>";
   echo "<td><a href=\"mailto:$row[email]\" class=small>$row[email]</a></td><td>(H)".FormatPhone($row[homeph])."<br>(B)".FormatPhone($row[workph])."<br>(C)".FormatPhone($row[cellph])."</td>";
   echO "<td align=center title=\"Vocal\">$row[vocal]</td><td title=\"Piano\" align=center>$row[piano]</td>";
   echo "<td title=\"Orchestra\" align=center>$row[orchestra]</td><td title=\"Instrumental\" align=center>$row[instrumental]</td>";
   echo "<td title=\"Brass\" align=center>$row[brass]</td><td title=\"Woodwind\" align=center>$row[woodwind]</td>";
   echo "<td title=\"Percussion\" align=center>$row[percussion]</td><td title=\"Currently Teaching Music\" align=center>$row[teacher]</td>";
   echo "<td title=\"Years Teaching Music/Years Judging Music\" align=center><u>&nbsp;$row[yearsteach]&nbsp;</u><br>$row[yearsjudge]</td>";
   //SITES JUDGED
   $sql2="SELECT * FROM mujudgesites WHERE mujudgeid='$row[id]' ORDER BY year DESC,site LIMIT 3";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<td>";
      while($row2=mysql_fetch_array($result2))
      {
         echo "$row2[site] - $row2[year]<br>";
      }
   }
   else echo "<td>&nbsp;";
   echo "</td>";
   echo "</tr>";
   }
   else
   {
      $date=split("-",$row[lastupdate]);
      $csv.="\"$date[1]/$date[2]/$date[0]\",\"$row[first]\",\"$row[last]\",\"$row[address1]\",\"$row[address2]\",\"$row[cityst]\",\"$row[zip]\",\"$row[email]\",\"".FormatPhone($row[homeph])."\",\"".FormatPhone($row[workph])."\",\"".FormatPhone($row[cellph])."\",";
      $sql2="SELECT * FROM mujudgesites WHERE mujudgeid='$row[id]' ORDER BY year DESC,site LIMIT 3";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $csv.="\"$row2[site] - $row2[year]\",";
      }
      $csv.="\r\n";
   }
}
if(!$export)
   echO "</table>";
else
{
   $open=fopen(citgf_fopen("mujudges.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("mujudges.csv");
   echO "<br><br><a href=\"mujudges.csv\">Excel Export of Music Judges</a>";
}

echo $end_html;
?>
