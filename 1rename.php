<?php
require 'functions.php';
require 'variables.php';

$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);


if(is_dir('anthem'))
{
$newname=date("Y").'anthem';
if(!is_dir('anthem'))
rename('anthem', $newname);
}
mkdir("anthem", 0700);

$sql="TRUNCATE TABLE  anthem";
mysql_query($sql);
header("Location:anthem_list.php?session=$session");

?>