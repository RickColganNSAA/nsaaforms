<?php
/**********************************************
wrdownload.php
Given a $videoid and $sessionid, allow a user
to download the file he/she has paid for
Created 2/7/13
by Ann Gaffigan
***********************************************/
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//GIVEN: $downloadid (wrdownloads.id), $videoid (wrvideos.id)

//NOW CHECK IF THEY HAVE ALREADY DOWNLOADED THIS FILE
$sql="SELECT * FROM wrdownloads WHERE id='$downloadid' AND wrvideoid='$videoid' AND downloadend>0";
$result=mysql_query($sql);
/*
if(mysql_num_rows($result)>0)
{
   $row=mysql_fetch_array($result);
   echo $init_html."<table width='100%'><tr align=center><td><br><br><div style='width:600px;text-align:left;'>";
   echo "<div class='error' style='text-align:center;font-size:13px;'>Error: You have already downloaded this file.</div>";
   echo "<p>The system recorded that you downloaded this file on ".date("F j, Y",$row[downloadend])." at ".date("g:ia T",$row[downloadend]).".</p>";
   echo "<p>Your <b>Session ID</b> is <u>$row[sessionid]</u>.<br>Your <b>Invoice ID</b> is <u>$row[appid]</u>.<br>Your <b>Download ID</b> is <u>$downloadid</u>. The Video ID is $videoid.</p>";
   echo "<p>If you think there has been a mistake, please contact the NSAA and give them your Session ID, your Invoice ID and your Download ID.</p></div>";
   echo "<br><a href=\"javascript:window.close();\">Close Window</a>";
   echo $end_html;
   exit();
}
*/

//WE CAN PROCEED

//GET FILENAME
$sql="SELECT t2.filename FROM wrdownloads AS t1,wrvideos AS t2 WHERE t1.wrvideoid=t2.id AND t1.id='$downloadid' AND t1.wrvideoid='$videoid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$filename=$row[filename];

//RECORD DOWNLOAD START
$sql="UPDATE wrdownloads SET downloadstart='".time()."' WHERE id='$downloadid' AND wrvideoid='$videoid'";
$result=mysql_query($sql);

//INITIATE DOWNLOAD OF .mov FILE
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.urlencode($filename));
header('Content-Transfer-Encoding: binary');
readfile(getbucketurl("/home/nsaahome/wrvideos/".$filename.""));

//RECORD DOWNLOAD END (USER CAN DOWNLOAD EACH VIDEO ONLY ONCE)
$sql="UPDATE wrdownloads SET downloadend='".time()."' WHERE id='$downloadid' AND wrvideoid='$videoid'";
$result=mysql_query($sql);
exit();
?>
