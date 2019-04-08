<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"r");
//$data=fread($open,citgf_filesize("/home/nsaahome/attachments/$filename"));
$data=stream_get_contents($open);
$data=ereg_replace("\r\n","<br>",$data);
echo $data;
?>
