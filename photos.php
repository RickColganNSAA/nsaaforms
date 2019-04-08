<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}

$temp=split("[.]",$filename);
header("Content-type: image");
header("Content-Disposition: inline; filename=".urlencode($filename)."");
readfile(getbucketurl("/home/nsaahome/photos/".$filename.""));
?>
