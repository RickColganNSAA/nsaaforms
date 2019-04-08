<?php
/*
scertsadmin.php
Admin page for district host/coordinator
to generate Superior Award Certificates
10/6/14 by Ann Gaffigan
*/
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
   echo $session.", $mudistid, $musiteid";
   //header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header."<br>";

echo "<h2>Generate Superior Award Certificates</h2>";

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
   echo "<h3><u>District $row0[distnum] -- $row0[classes] at $row0[site]:</u></b></h3>";
echo "<table cellspacing=0 cellpadding=3>";
if(!$sort || $sort=="") $sort="t2.school ASC";
$sql="SELECT t1.distnum,t1.classes,t2.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t1.id='$musiteid' AND t2.submitted!='' ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
}	//END IF RESULTS
else 
   echo "<tr align=center><td><br><br><i>No entries have been submitted for this site yet.  Please check back later.</i><br><br><br></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left>";
   echo "<td><a href=\"superiorcerts.php?session=$session&school=$row[school]&name=$name\">$row[school]</a></td></tr>";
} 
echo "</table>";
}//end for each DISTRICT
echo "<br><br><br><a href=\"../welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
