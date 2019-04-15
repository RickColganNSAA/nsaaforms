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

if(!$testsport)
{
   header("Location:welcome.php?session=$session&open=6#6");
   exit();
}

$page=$testsport."test.php";
header("Location:$page?session=$session&retake=$retake");
exit();

?>
