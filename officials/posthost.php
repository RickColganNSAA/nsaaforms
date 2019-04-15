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
   $sql="UPDATE $districts SET post='y' WHERE id='$row2[id]'";
   $result=mysql_query($sql);
}

if($all==1 && ($sport=='ba' || preg_match("/bb/",$sport)))	//CLASS A BASKETBALL - POST TO $disttimes AS WELL
{
   $sql2="SELECT * FROM ".$sport."disttimes WHERE hostid!='0' AND hostid!=''";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sql="UPDATE ".$sport."disttimes SET post='y' WHERE id='$row2[id]'";
      $result=mysql_query($sql);
   }
}

if($all==1)
   header("Location:hostreport.php?sport=$sport&posted=yes&session=$session");
else
   header("Location:hostbyhost.php?sport=$sport&posted=yes&session=$session&type=$type&distid=$distid");

exit();
?>
