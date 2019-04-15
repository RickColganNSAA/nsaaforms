<?php
$db_host="localhost";
//$db_user="nsaamysql";
$db_user="nsaaweb";
//$db_pass="bio!!7308";
$db_pass="!!new!!2014!!";
$db_name="nsaascores_live";
$stateassn="NSAA";
//$db_user2="nsaamysql";
//$db_user2="nsaaweb";
$db_user2="root";
//$db_pass2="bio!!7308";
//$db_pass2="!!new!!2014!!";
$db_pass2="P@55w0rd";
$db_name2="nsaaofficials_live";
$db_test="testwildcard";

$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
print "<pre>";
$query = mysql_query("select offid,mailing,class from sboff where mailing='100'") or die(mysql_error());
echo (mysql_num_rows($query));
while($arr = mysql_fetch_array($query)){
	print_r($arr);
}
?>

