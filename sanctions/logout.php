<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//logout.php: erases session's entry from db table and sends
//	user to index.php

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//delete record from sessions table
$sql="DELETE FROM sessions WHERE session_id='$session'";
$result=mysql_query($sql);

//send to login page
header("Location:login.php");
exit();

?>
