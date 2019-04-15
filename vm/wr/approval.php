<?php
/*************************************
approval.php
User is sent here upon successful
completion of credit card payment
for WR videos (from wrcart.php)
Created 2/12/13
By Ann Gaffigan
**************************************/
require "../functions.php";
require "../variables.php";

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No transaction id.";
   exit();
}

session_set_cookie_params(2*24*60*60);
session_start();

if(!$_SESSION['sessionid'])
{
   echo "Your session has expired.";
   exit();
}
 
$appid=$ssl_invoice_number;

//MARK TRANSACTION AS APPROVED
$sql="UPDATE wrvideotransactions SET approved='yes' WHERE appid='$appid' AND sessionid='".$_SESSION['sessionid']."'";
$result=mysql_query($sql);

//GET ORDER INFO; TRANSFER TO wrdownloads TABLE
$sql="SELECT * FROM wrvideocarts WHERE sessionid='".$_SESSION['sessionid']."'";
$result=mysql_query($sql);
$totalct=mysql_num_rows($result);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT DISTINCT filename FROM wrvideos WHERE (redfirst='".addslashes($row[wrestlerfirst])."' AND redlast='".addslashes($row[wrestlerlast])."' AND redteam='".addslashes($row[wrestlerteam])."') OR (bluefirst='".addslashes($row[wrestlerfirst])."' AND bluelast='".addslashes($row[wrestlerlast])."' AND blueteam='".addslashes($row[wrestlerteam])."') ORDER BY division,bouttype,boutnumber";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sql3="SELECT id FROM wrvideos WHERE filename='$row2[filename]' LIMIT 1";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $videoid=$row3[id];
      $sql3="INSERT INTO wrdownloads (sessionid,appid,wrestlerfirst,wrestlerlast,wrestlerteam,wrvideoid) VALUES ('".$_SESSION['sessionid']."','$appid','".addslashes($row[wrestlerfirst])."','".addslashes($row[wrestlerlast])."','".addslashes($row[wrestlerteam])."','$videoid')";
      $result3=mysql_query($sql3);
   }
}

//EMPTY CART
$sql="DELETE FROM wrvideocarts WHERE sessionid='".$_SESSION['sessionid']."'";
$result=mysql_query($sql);

$totaldue=$totalct*20;

$string=$init_html;
$userstring=$init_html;
$string.="<table style='width:100%;' cellspacing=3 cellpadding=3><tr align=center><td><br><br>
<table cellspacing=0 cellpadding=5 class='nine'>
<caption><b>Transaction Complete!<hr></b></caption>";
$userstring.="<table style='width:100%;' cellspacing=3 cellpadding=3><tr align=center><td><br><br>
<table cellspacing=0 cellpadding=5 class='nine'><caption><b>NSAA State Wrestling Championships Video Download</b></caption>";
$string.="<tr align=left><td colspan=2><h1>Please print this page for your records!<h1><h4>You will need this page as proof of purchase and to know how to return to this site at a later time and download your videos.</h4></td></tr>";
$string.="<tr align=left><td colspan=2>You may download each video for each wrestler below ONE TIME only.<br><br>
Return to the link below if you need to close this window and download your videos at a later date:<br><a href=\"https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."\">https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."</a><br><br>";
$userstring.="<tr align=left><td colspan=2>You may download each video for each wrestler below ONE TIME only.<br><br>
Return to the link below if you need to close this window and download your videos at a later date:<br><a href=\"https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."\">https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."</a><br><br>";
$Html="<p><b>Thank you for purchasing NSAA State Wrestling Championships videos!</b></p><p>Please save this email for your records. You will need it as proof of purchase and to know how to return to the site at a later time and download your videos, if you haven't done so already.</p><p>You may download each video for each wrestler ONE TIME only.</p><p>Return to the link below if you still need to download some of the videos you've purchased:</p><p><a href=\"https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."\">https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."</a></p>";
$string.="</td></tr>";
$string.="<tr align=left><td><b>User Session ID:</b></td><td>".$_SESSION['sessionid']."</td></tr>";
$string.="<tr align=left><td><b>Invoice ID:</b></td><td>$appid</td></tr>";
$string.="<tr align=left><td><b>Transaction Date:</b></td><td>".date("F j, Y",$appid)."</td></tr>";
$string.="<tr align=left><td><b>Transaction Description:</b></td><td>NSAA State Wrestling Videos</td></tr>";
$string.="<tr align=left><td><b>Transaction Amount:</b></td><td>$".$ssl_amount."</td></tr>";
$string.="<tr align=left><td><b>Billing Name:</b></td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><b>Billing Address:</b></td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><b>Credit Card Number:</b></td><td>$ssl_card_number</td></tr>";
echo $string;
//echo "<tr align=left><td colspan=2><div id='pleasewait'>Finding videos. Please be patient...<br><img src=\"/nsaaforms/pleasewait.gif\" style=\"margin:5px;border:0;\"></div></td></tr>";
flush();
$string1=$string;
$string="<tr align=center><td colspan=2><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";
$userstring.="<tr align=center><td colspan=2><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";

$Html.="<p><b>TIPS FOR DOWNLOADING LARGE FILES:</b></p><p>The videos below are very large files to download. You will need to download ONE VIDEO AT A TIME, being patient to allow each video to download in full.</p><p>Do NOT attempt to download these files on a dial-up connection. Please use a high-speed internet connection.</p><p>Do NOT download these files to your phone or tablet. Make sure you download to your computer AND that your computer has enough free space to hold the files (the file size is listed next to each video.)</p><p>Your computer or internet connection may interrupt a download and cause it to halt. If this happens, click the link to restart the download.</p><p><b>If you have further questions, please contact Ron Higdon</b> at the NSAA at (402) 489-0386 or <a href=\"mailto:rhigdon@nsaahome.org\">rhigdon@nsaahome.org</a></p><p>Thank You!</p>";

$string.="<caption><div class='normalwhite' style='margin-bottom:10px;text-align:left;'><p><b>TIPS FOR DOWNLOADING LARGE FILES:</b></p><p>The videos below are very large files to download. You will need to download ONE VIDEO AT A TIME, being patient to allow each video to download in full.</p><p>Do NOT attempt to download these files on a dial-up connection. Please use a high-speed internet connection.</p><p>Do NOT download these files to your phone or tablet. Make sure you download to your computer AND that your computer has enough free space to hold the files (the file size is listed next to each video.)</p><p>Your computer or internet connection may interrupt a download and cause it to halt. If this happens, click the link to restart the download.</p></div><br></caption>";
$userstring.="<caption><div class='normalwhite' style='margin-bottom:10px;text-align:left;'><p><b>TIPS FOR DOWNLOADING LARGE FILES:</b></p><p>The videos below are very large files to download. You will need to download ONE VIDEO AT A TIME, being patient to allow each video to download in full.</p><p>Do NOT attempt to download these files on a dial-up connection. Please use a high-speed internet connection.</p><p>Do NOT download these files to your phone or tablet. Make sure you download to your computer AND that your computer has enough free space to hold the files (the file size is listed next to each video.)</p><p>Your computer or internet connection may interrupt a download and cause it to halt. If this happens, click the link to restart the download.</p></div><br></caption>";
$sql="SELECT * FROM wrdownloads WHERE sessionid='".$_SESSION['sessionid']."' AND appid='$appid' ORDER BY wrestlerteam,wrestlerlast,wrestlerfirst";
$result=mysql_query($sql);
if(mysql_error())
{
   echo "UNEXPECTED ERROR FOR QUERY: $sql<br>".mysql_error()."<br>";
}
$string.="<tr align=center><td>NAME</td><td>TEAM</td><td>AVAILABLE VIDEOS</td></tr>";
$userstring.="<tr align=center><td>NAME</td><td>TEAM</td><td>AVAILABLE VIDEOS</td></tr>";
$i=0; $totaldue=0;
$dirnamebase=$_SESSION['sessionid'].$appid;
$curwrestler="";
while($row=mysql_fetch_array($result))
{
   if($curwrestler!="$row[wrestlerfirst] $row[wrestlerlast] $row[wrestlerteam]")
   {
      if($curwrestler!='') { $string.="</td></tr>"; $userstring.="</td></tr>"; }
      $string.="<tr align=left><td>".$row[wrestlerlast].", ".$row[wrestlerfirst]."</td><td>".$row[wrestlerteam]."</td><td>";
      $userstring.="<tr align=left><td>".$row[wrestlerlast].", ".$row[wrestlerfirst]."</td><td>".$row[wrestlerteam]."</td><td>";
      $totaldue+=20;
      $curwrestler="$row[wrestlerfirst] $row[wrestlerlast] $row[wrestlerteam]";
   }
      //GET VIDEOS FOR THIS PERSON
      $sql2="SELECT * FROM wrvideos WHERE id='$row[wrvideoid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      
      $bytes=citgf_filesize("/home/nsaahome/wrvideos/".$row2[filename]);
      $mbytes=number_format($bytes/1000000,2,'.','');
         $string.="<p><b>$row2[bouttype]:</b> $row2[redfirst] $row2[redlast] ($row2[redteam]) vs $row2[bluefirst] $row2[bluelast] ($row2[blueteam])<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class='small' href=\"wrdownload.php?downloadid=$row[id]&videoid=$row2[id]\" target=\"_blank\">Download $row2[filename] ($mbytes MB)</a></p>";
         $userstring.="<p><b>$row2[bouttype]:</b> $row2[redfirst] $row2[redlast] ($row2[redteam]) vs $row2[bluefirst] $row2[bluelast] ($row2[blueteam])<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class='small' href=\"wrdownload.php?downloadid=$row[id]&videoid=$row2[id]\" target=\"_blank\">Download $row2[filename] ($mbytes MB)</a></p>";
   $i++;
}
$string.="</td></tr></table><br><br>";
$userstring.="</td></tr></table><br><br>";
$string.="Return to the <a target=\"_blank\" href=\"/nsaaforms/wr/wrvideos.php\">NSAA Wrestling Videos Page</a><br><br>";
$userstring.="Return to the <a target=\"_blank\" href=\"/nsaaforms/wr/wrvideos.php\">NSAA Wrestling Videos Page</a><br><br></td></tr></table></body></html>";
$string.="Return to the link below if you need to close this window and download your videos at a later date:<br>";
$string.="<a href=\"https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."\">https://secure.nsaahome.org/nsaaforms/wr/wruserdownload.php?appid=$appid&sessionid=".$_SESSION['sessionid']."</a></td></tr>";
$string.="</table></body></html>";

echo $string;

$string=addslashes($string1.$string);

//INSERT INTO WRVIDEOTRANSACTIONS TABLE
$sql="UPDATE wrvideotransactions SET html='$string' WHERE appid='$appid' AND sessionid='".$_SESSION['sessionid']."'";
$result=mysql_query($sql);

$sql="SELECT * FROM wrvideouserdownloads WHERE appid='$appid' AND sessionid='".$_SESSION['sessionid']."'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   $sql="INSERT INTO wrvideouserdownloads (appid,sessionid,html) VALUES ('$appid','".$_SESSION['sessionid']."','".addslashes($userstring)."')";
   $result=mysql_query($sql);
}
else
{
   $sql="UPDATE wrvideouserdownloads SET html='".addslashes($userstring)."' WHERE appid='$appid' AND sessionid='".$_SESSION['sessionid']."'";
   $result=mysql_query($sql);
}

//E-MAIL USER STRING
$From="nsaa@nsaahome.org";
$FromName="NSAA";
$To=$ssl_email;
$ToName="$ssl_first_name $ssl_last_name";
$Subject="Thank you for purchasing NSAA State Wrestling Videos";
$Text=preg_replace("/\<\/p\>/","\r\n",$Html);
$Text=strip_tags($Text);
$Attm=array();
SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);

echo mysql_error();
?>
