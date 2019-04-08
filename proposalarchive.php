<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

$year=date("Y");
$year1=$year-1;
$archivedb="$db_name".$year1.$year;
//$sql="CREATE DATABASE IF NOT EXISTS $archivedb";
//$result=mysql_query($sql);
//AS OF JUNE 20, 2012, this just copies the proposals table; move it to archived proposals table on June 1 with archive.php or manually
$archivedb=$db_name;

//copy proposals table
$sql=" CREATE  TABLE  $archivedb.proposals".$year1.$year." (  `id` int( 11  )  NOT  NULL  auto_increment ,
 `school` varchar( 250  )  NOT  NULL DEFAULT  '',
 `datesub` varchar( 100  )  NOT  NULL DEFAULT  '',
 `name` varchar( 250  )  NOT  NULL DEFAULT  '',
 `district` varchar( 50  )  NOT  NULL DEFAULT  '',
 `yearbook` varchar( 10  )  NOT  NULL DEFAULT  '',
 `article` varchar( 250  )  NOT  NULL DEFAULT  '',
 `section` varchar( 250  )  NOT  NULL DEFAULT  '',
 `ybpage` varchar( 250  )  NOT  NULL DEFAULT  '',
 `actman` varchar( 10  )  NOT  NULL DEFAULT  '',
 `actman2` varchar( 250  )  NOT  NULL DEFAULT  '',
 `ampage` varchar( 250  )  NOT  NULL DEFAULT  '',
 `current` longtext NOT  NULL ,
 `changed` longtext NOT  NULL ,
 `costanal` longtext NOT  NULL ,
 `rationale` longtext NOT  NULL ,
 `pros` longtext NOT  NULL ,
 `cons` longtext NOT  NULL ,
 `filename` varchar( 250  )  NOT  NULL DEFAULT  '',
 `verify` varchar( 100  )  NOT  NULL DEFAULT  '',
 `locked` varchar( 100  )  NOT  NULL DEFAULT  '',
 `notes` text NOT  NULL ,
 `exAfile` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exAtitle` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exBfile` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exBtitle` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exCfile` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exCtitle` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exDfile` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exDtitle` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exEfile` varchar( 255  )  NOT  NULL DEFAULT  '',
 `exEtitle` varchar( 255  )  NOT  NULL DEFAULT  '',
  `class` varchar(5) NOT NULL,
  `type` varchar(50) NOT NULL,
 `travel` varchar( 10  )  NOT  NULL DEFAULT  '',
 `instruction` varchar( 10  )  NOT  NULL DEFAULT  '',
 `schoolcost` varchar( 10  )  NOT  NULL DEFAULT  '',
 `nsaacost` varchar( 10  )  NOT  NULL DEFAULT  '',
 `impdate` varchar( 255  )  NOT  NULL DEFAULT  '',
 PRIMARY  KEY (  `id`  )  ) ENGINE  =  MyISAM  DEFAULT CHARSET  = latin1";
$result=mysql_query($sql);
$sql="INSERT INTO $archivedb.proposals".$year1.$year." SELECT * FROM $db_name.proposals";
$result=mysql_query($sql);

//copy proposaltables table
$sql=" CREATE  TABLE  $archivedb.proposaltables".$year1.$year." (  `id` int( 11  )  NOT  NULL  auto_increment ,
 `proposalid` bigint( 20  )  NOT  NULL default  '0',
 `rows` int( 11  )  NOT  NULL default  '0',
 `cols` int( 11  )  NOT  NULL default  '0',
 `boldrows` varchar( 255  )  NOT  NULL default  '',
 `boldcols` varchar( 255  )  NOT  NULL default  '',
 `gridlines` varchar( 5  )  NOT  NULL default  '',
 `title` varchar( 255  )  NOT  NULL default  '',
 `entries` longtext NOT  NULL ,
 `pending` varchar( 5  )  NOT  NULL default  '',
 PRIMARY  KEY (  `id`  )  ) TYPE  =  MyISAM";
$result=mysql_query($sql);
$sql="INSERT INTO $archivedb.proposaltables".$year1.$year." SELECT * FROM `$db_name`.`proposaltables`;";
$result=mysql_query($sql);

//clear out proposal table:
$sql="DELETE FROM proposals";
$result=mysql_query($sql);

//clear out proposaltables table:
$sql="DELETE FROM proposaltables";
$result=mysql_query($sql);

//update proposaladmin table
$sql="UPDATE proposaladmin SET lastarchive='".$year1."-".$year."'";
$result=mysql_query($sql);

header("Location:proposaladmin.php?session=$session&archive=yes");
exit();

?>
