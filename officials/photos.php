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
$filename="AnnTereseGaffigan_10051981.jpg";
$temp=split("[.]",$filename);
/*
header("Content-type: image");
header("Content-Disposition: inline; filename=".$filename);
readfile("/home/nsaahome/photos/".$filename);

$open=fopen(citgf_fopen("/home/nsaahome/photos/".$filename);
),$data=fread($open,citgf_filesize("/home/nsaahome/photos/".$filename));
fclose($open);
echo "<img src=\"data:image;base64,$data\">$data";
if(citgf_file_exists("/home/nsaahome/photos/".$filename)) echo "EXISTS";
*/
header('Content-Type: image/jpeg');
print citgf_file_get_contents("/home/nsaahome/photos/".$filename);
?>
