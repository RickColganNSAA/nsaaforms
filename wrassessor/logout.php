<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//delete record from sessions table
$sql="UPDATE wrassessors SET session='0' WHERE session='$session'";
$result=mysql_query($sql);

//send to login page
header("Location:index.php");
exit();

?>
