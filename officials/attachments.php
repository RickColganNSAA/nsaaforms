<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
      header("Location:index.php?error=1");
      exit();
}

$temp=split("[.]",$filename);
if($temp[1]!="html")	// force download
{
header("Content-type: text/css");
header("Content-Disposition: attachment; filename=".urlencode($filename)."");
readfile(getbucketurl("messagefiles/".$filename.""));
}
else 
{
$open=fopen(citgf_fopen("messagefiles/$filename"),"r");
//$data=fread($open,citgf_filesize("messagefiles/$filename"));
$data=stream_get_contents($open);
$data=ereg_replace("\r\n","<br>",$data);
echo $data;
}
?>
