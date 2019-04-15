<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$seeds=$sport."seeds";
$sched=$sport."sched";
$school=GetSchoolsTable($sport);
$districts=$sport."districts";
$disttimes=$sport."disttimes";

//get number of teams in this district:
$sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid'";
$result=mysql_query($sql);
$teamct=mysql_num_rows($result);
if($teamct==8){
    $match1seed1=5; $match1seed2=6;
    if(ereg("so",$sport))
    {
        $match2seed1=2; $match2seed2=7;
        $match3seed1=3; $match3seed2=6;
    }
    if($sport=='ba')
    {
        $match2seed1=3; $match2seed2=6;
        $match3seed1=7; $match3seed2=2;
    }
    else if($sport=='sb')
    {
        $match2seed1=3; $match2seed2=8;
        $match3seed1=4; $match3seed2=7;
    }
}
else if($teamct==6)	//create matches 1 and 2 in wildcard program
{
   $match1seed1=4; $match1seed2=5;
   $match2seed1=3; $match2seed2=6;
}
else if($teamct==5)
{
   $match1seed1=4; $match1seed2=5;
   $match2seed1=3; $match2seed2=2;
   if($sport=='sb' || $sport=='ba')
   {
      $match2seed1=2;  $match2seed2=3;
   }
}
else if($teamct==4)
{
   $match1seed1=1; $match1seed2=4;
   $match2seed1=3; $match2seed2=2;
   if($sport=='sb' || $sport=='ba')
   {
      $match2seed1=2; $match2seed2=3;
   }
}
else if($teamct==7)	//Soccer, Baseball & Softball
{
   $match1seed1=4; $match1seed2=5;
   if(ereg("so",$sport))
   {
      $match2seed1=2; $match2seed2=7;
      $match3seed1=3; $match3seed2=6;
   }
   if($sport=='ba')
   {
      $match2seed1=3; $match2seed2=6;
      $match3seed1=7; $match3seed2=2;
   }
   else if($sport=='sb')
   {
      $match2seed1=3; $match2seed2=6;
      $match3seed1=2; $match3seed2=7;
   }
}

   //MATCH 1: 
   //get date and time from $disttimes table
   $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND gamenum='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $received=$row[day];
   $gametime=$row[time];
   //get seeds
   $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match1seed1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid1=$row[sid];
   $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match1seed2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid2=$row[sid];
   //get host school
   $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $type=$row[type];
   $homeid=GetSID2($row[hostschool],$sport);

   //insert into wildcard program:
   $sql="INSERT INTO $db_name.$sched (gametype,received,sid,oppid,homeid,distid,gamenum) VALUES ('1','$received','$sid1','$sid2','$homeid','$distid','1')";
   $result=mysql_query($sql);

   //MATCH 2: 
   //get date and time from $disttimes table
   if((ereg('so',$sport) || ereg('bb',$sport)) && $teamct==5) $gamenum='3';
   else $gamenum='2';
   $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND gamenum='$gamenum'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $received=$row[day];
   $gametime=$row[time];
   //get seeds
   $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match2seed1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid1=$row[sid];
   $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match2seed2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid2=$row[sid];
   //get host school
   $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $homeid=GetSID2($row[hostschool],$sport);

   //insert into wildcard program:
   $sql="INSERT INTO $db_name.$sched (gametype,received,sid,oppid,homeid,distid,gamenum) VALUES ('1','$received','$sid1','$sid2','$homeid','$distid','$gamenum')";
   $result=mysql_query($sql);
//echo "$sql<br>";

   if($teamct==7)	//soccer, baseball & softball: 3 seeded matches
   {
      $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND gamenum='3'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $received=$row[day];
      $gametime=$row[time];
      //get seeds
      $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match3seed1'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sid1=$row[sid];
      $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match3seed2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sid2=$row[sid];
      //get host school
      $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $homeid=GetSID2($row[hostschool],$sport);
      
      //insert into wildcard program:
      $sql="INSERT INTO $db_name.$sched (gametype,received,sid,oppid,homeid,distid,gamenum) VALUES ('1','$received','$sid1','$sid2','$homeid','$distid','3')";
      $result=mysql_query($sql);
   }

   if ($teamct==8){
    echo $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND gamenum='4'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $received=$row[day];
    $gametime=$row[time];
    //get seeds
    $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match3seed1'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $sid1=$row[sid];
    $sql="SELECT * FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$match3seed2'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $sid2=$row[sid];
    //get host school
    $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $homeid=GetSID2($row[hostschool],$sport);

    //insert into wildcard program:
    $sql="INSERT INTO $db_name.$sched (gametype,received,sid,oppid,homeid,distid,gamenum) VALUES ('1','$received','$sid1','$sid2','$homeid','$distid','3')";
    $result=mysql_query($sql);
}

   $sql="UPDATE $db_name2.$districts SET bracketed='y' WHERE id='$distid'";
   $result=mysql_query($sql);

   header("Location:hostbyhost.php?distid=$distid&type=$type&session=$session&sport=$sport");
   exit();
?>
