<?php
//post_message.php:  Level 1 can post messages to Level 2, and Level2 
//	can post ones to Level 3

require 'functions.php';
require 'variables.php';
echo "i am here";
//connect to db

$session;
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);
$recips='saif.live@gmail.com';
$messageid='1251';
	  exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
echo "i am hessssssssssssssssre";
?>
