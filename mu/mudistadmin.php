<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
$schoolid=GetSchoolID($session); $loginid=GetUserID($session);
if($level==4) $schoolid=0;
$mudistid=GetMusicDistrictID($schoolid,$loginid);
if(!ValidUser($session) || $mudistid==0)
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

echo "<br>";

$sql="SELECT * FROM mubigdistricts WHERE id='$mudistid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$distnum=$row[distnum];
echo "<table cellspacing=0 cellpadding=3 width='600px'><caption><b><u>Submitted</u> Music District Entry Forms for District $row[distnum]:</b>";
echo "<br><br></caption>";
if(!$sort || $sort=="") $sort="t2.submitted DESC";
$sql="SELECT t1.distnum,t1.classes,t2.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.submitted!='' AND t1.distnum='$distnum' ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
echo "<tr align=left><td><a class=small href=\"entriesadmin.php?session=$session&sort=t1.distnum,t1.classes\">District # -- Class</a>";
if($sort=="t1.distnum,t1.classes")
   echo "&nbsp;<a href=\"entriesadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowdown.png\" width=15></a>";
echo "</td><td><a class=small href=\"entriesadmin.php?session=$session&sort=t2.school\">School</a>";
if($sort=="t2.school")
   echo "&nbsp;<a href=\"entriesadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowdown.png\" width=15></a>";
echo "<br>(click School Name to view form)</td><td><b># Students</b></td><td><a href=\"entriesadmin.php?session=$session&sort=t2.submitted DESC\" class=small>Submission<br>Date & Time</a>";
if($sort=="t2.submitted DESC")
   echo "&nbsp;<a href=\"entriesadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowup.png\" width=15></a>";
echo "</td></tr>";
}
else echo "<tr align=center><td><br><br><i>No entries have been submitted yet for District $distnum.  Please check back later.</i><br><br><a href=\"../welcome.php?session=$session\">Home</a></td></tr>";
$sql="SELECT t1.distnum,t1.classes,t2.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.submitted!='' AND t1.distnum='$distnum' ORDER BY $sort";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left bgcolor=#E0E0E0><td width=\"200px\">$row[distnum] -- $row[classes]</td>";
   echo "<td><a href=\"view_mu.php?session=$session&school_ch=$row[school]\">$row[school]</a></td>";
   echo "<td align=center>$row[studcount]</td>";
   echo "<td align=center>".date("m/d/y",$row[submitted])." @ ".date(" h:i a",$row[submitted])."</td>";
   //show links to attachments that were sent with form:
   echo "<tr align=center><td colspan=4><table width=95%>";
      //Get file names of attachments:
      $summary=strtolower($row[school]);
      $summary=ereg_replace(" ","",$summary);
      $summary=ereg_replace("[.]","",$summary);
      $summary=ereg_replace("\'","",$summary);
      $summary=ereg_replace("-","",$summary);
      $summary.="summary";
      $full=ereg_replace("summary","full",$summary);
      $eliglist=ereg_replace("summary","eliglist",$summary);
      $payment=ereg_replace("summary","payment",$summary);
   echo "<tr align=left>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$summary.".html\">Summary (.html)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$full.".html\">Full Version (.html)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$eliglist.".html\">Eligibility List (.html)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$payment.".html\">Payment Summary (.html)</a></td></tr>";
   echo "<tr align=left>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$summary.".csv\">Summary (.csv)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$full.".csv\">Full Version (.csv)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$eliglist.".csv\">Eligibility List (.csv)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$payment.".csv\">Payment Summary (.csv)</a></td>";
   echo "</td></table></td></tr>";
} 
echo "</table>";

echo $end_html;
?>
