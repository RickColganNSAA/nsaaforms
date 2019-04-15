<?php
/*
posthost.php
Script linked from hostbyhost.php to post
contracts to Host Schools
*/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   if($sport=='pp' || $sport=='sp')
      header("Location:jindex.php?error=1");
   else
      header("Location:index.php?error=1");
   exit();
}

$districts=$sport."districts";
$sportname=GetSportName($sport);

//1) MAKE SURE WE ARE POSTING A CONTRACT FOR A TOURNAMENT/GAME THAT ACTUALLY HAS A HOST ASSIGNED (hostid>0)
//2) MARK THE CONTRACT AS POSTED (post=y)
//3) EMAIL THE HOST IF THIS CONTRACT IS NEWLY POSTED
//4) RETURN TO hostbyhost.php

if($disttimesid>0)	//CLASS A BASKETBALL - ONE HOST PER GAME
{
   $districts=$sport."disttimes";
   $sql2="SELECT * FROM $districts WHERE hostid!='0' AND hostid!='' AND id='$disttimesid'";
}
else
{
   $sql2="SELECT * FROM $districts WHERE hostid!='0' AND hostid!=''";
   if($all!=1 && $distid!='' && $distid!='0')
      $sql2.=" AND id='$distid'";
}
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $oldpost=$row2[post];
   $sql="UPDATE $districts SET post='y' WHERE id='$row2[id]'";
   $result=mysql_query($sql);
   if($oldpost!='y')	//SEND EMAIL
   {
      $sql3="SELECT email,name,school,level FROM $db_name.logins WHERE id='$row2[hostid]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      if($row3[email]=="" && $row3[school]!='')	//get other email
      {
	 if($row3[level]==2)
	    $sql3="SELECT email,name,school,level,sport FROM $db_name.logins WHERE school='".addslashes($row3[school])."' AND sport='Activities Director'";
	 else 
	    $sql3="SELECT email,name,school,level,sport FROM $db_name.logins WHERE school='".addslashes($row3[school])."' AND level='2'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
      }
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To=$row3[email]; $ToName=$row3[name];
      $Subject="You have been selected to host a $sportname tournament.";
      $Html="Your school has been selected by the NSAA to host a $sportname tournament.<br><br>Please login at <a href=\"https://secure.nsaahome.org/nsaaforms\">https://secure.nsaahome.org/nsaaforms</a> to view and respond to your host contract.<br><br>Thank You!";
      $Text="Your school has been selected by the NSAA to host a $sportname tournament.\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms to view and respond to your host contract.\r\n\r\nThank You!";
      $Attm=array();
      if($To!='')
      {
	 //$To="run7soccer@aim.com"; $ToName="Ann Gaffigan";
	 //citgf_exec("/usr/local/bin/php sendemail.php '$session' '$To' '$ToName' '$Subject' '$Html' '$Attm' > sendemailsoutput.html 2>&1 &");
      }
   }
}

if($all==1)
   header("Location:hostreport.php?sport=$sport&posted=yes&session=$session");
else
   header("Location:hostbyhost.php?sport=$sport&posted=yes&session=$session&type=$type&distid=$distid");

exit();
?>
