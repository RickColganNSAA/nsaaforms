<?php
/*******************************************
logout.php
Log out for Judges
Created 11/14/12
Author: Ann Gaffigan
*******************************************/
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$sql="UPDATE jojudges SET session='0' WHERE session='$session'";
$result=mysql_query($sql);

   header("Location:joindex.php");
   exit();
?>
