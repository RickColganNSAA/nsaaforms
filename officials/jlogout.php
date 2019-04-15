<?php
//logout.php: erases session's entry from db table and sends
//	user to index.php

require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//delete record from sessions table
$sql="DELETE FROM sessions WHERE session_id='$session'";
$result=mysql_query($sql);

//send to login page
header("Location:jindex.php");
exit();

?>
