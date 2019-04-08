<?php
require '../functions.php';
require '../../calculate/functions.php';
require 'mufunctions.php';
require '../variables.php';
//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

exit();
?>
