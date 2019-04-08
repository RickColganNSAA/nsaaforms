<?php
require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($level==3)
{
   $schoolid=GetSchoolID($session); $loginid=0;
}
else if($level==4)
{
   $schoolid=0; $loginid=GetUserID($session);
}
$musiteid=GetMusicSiteID($schoolid,$loginid);		//SITE DIRECTORS
$mudistid=GetMusicDistrictID($schoolid,$loginid);	//COORDINATORS
//check if this site is part of a combo site
$sql="SELECT * FROM mudistricts WHERE distid1='$musiteid' OR distid2='$musiteid'";
$result=mysql_query($sql);
$comboid=0;
if(mysql_num_rows($result)>0)
{
   $row=mysql_fetch_array($result);
   $comboid=$row[id];
}
//verify user
if(!ValidUser($session) || ($mudistid==0 && $musiteid==0))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header."<br>";

$sql0="SELECT * FROM mudistricts WHERE ";
if($mudistid)	//GET BIG DISTRICT
{
   $sql2="SELECT distnum FROM mubigdistricts WHERE id='$mudistid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sql0.="distnum='$row2[0]'";
}
else
{
   $sql0.="id='$musiteid'";
   if($comboid) $sql0.=" OR id='$comboid'";
}
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
    $musiteid=$row0[id];
echo "<table cellspacing=0 cellpadding=3 width='600px'><caption><b><u>Submitted</u> Music District Entry Forms for District $row0[distnum] -- $row0[classes]<br>$row0[site]</b>";
echo "<br><br></caption>";
if(!$sort || $sort=="") $sort="t2.submitted DESC";
$sql="SELECT t1.distnum,t1.classes,t2.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t1.id='$musiteid' AND t2.submitted!='' ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
echo "<tr align=left><td><a class=small href=\"musiteadmin.php?session=$session&sort=t2.school\">School</a>";
if($sort=="t2.school")
   echo "&nbsp;<a href=\"musiteadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowdown.png\" width=15></a>";
echo "<br>(click School Name to view form)</td><td><b># Students</b></td><td><a href=\"musiteadmin.php?session=$session&sort=t2.submitted DESC\" class=small>Submission<br>Date & Time</a>";
if($sort=="t2.submitted DESC")
   echo "&nbsp;<a href=\"musiteadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowup.png\" width=15></a>";
echo "</td></tr>";
}	//END IF RESULTS
else echo "<tr align=center><td><br><br><i>No entries have been submitted for this site yet.  Please check back later.</i><br><br><br></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<td><a href=\"view_mu.php?session=$session&school_ch=$row[school]\">$row[school]</a></td>";
   echo "<td align=center>$row[studcount]</td>";
   echo "<td align=center>".date("m/d/y",$row[submitted])." @ ".date(" h:i a",$row[submitted])."</td>";
   //show links to attachments that were sent with form:
   echo "<tr align=center><td colspan=3><table width=95%>";
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
echo "</table><hr width='500px'>";
}//end for each DISTRICT
echo "<a href=\"../welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
