<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$table=$sport."districts";
if($sport=='fb') $table=$sport."brackets";

$sql="UPDATE $table SET showtimes='y'";
if($distid) $sql.=" WHERE id='$distid'";
$result=mysql_query($sql);

if($distid)
   header("Location:hostbyhost.php?session=$session&sport=$sport&distid=$distid&class=$class&type=$type");
else
   header("Location:hostreport.php?session=$session&sport=$sport");
exit();

?>
