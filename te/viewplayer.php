<?php
/*******************************************
viewplayer.php
View Player's summary
Created 6/27/09
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';
require 'tefunctions.php';

$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo "<table width='100%'><tr align=center><td>";

echo GetAllResults($sport,$class,$division,$sid,$playerid1,$playerid2);

echo $end_html;
?>
