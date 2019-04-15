<?php
require '../functions.php';

$sql="SELECT * FROM wrvideouserdownloads WHERE appid='$appid' AND sessionid='$sessionid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo $row[html];

?>
