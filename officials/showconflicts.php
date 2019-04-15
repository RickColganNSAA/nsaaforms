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

$dboffs="$db_name2";

$sql="SELECT t1.conflict,t2.first,t2.last FROM $dboffs.$table AS t1, $dboffs.officials AS t2 WHERE t1.offid=t2.id AND t1.id='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo mysql_error();
echo $init_html;
echo "<table width=100%><tr align=center><td><b>$row[first] $row[last] Conflicts:</b>";
echO "<table><tr align=left><td>$row[0]</td></tr></table>";
echo "</td></tr></table>";
echo "</td></tr></table></body></html>";
?>
