<?php

require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}
if(!$resetsch || $resetsch=="")
{
   header("Location:welcome.php?session=$session");
   exit();
}

$resetsch2=addslashes($resetsch);
$sql="DELETE FROM eligibility WHERE school='$resetsch2'";
$result=mysql_query($sql);

echo $init_html;
echo GetHeader($session);
echo "<br><br><br><font style=\"font-size:9pt;\">";
echo "<b>$resetsch's</b> Eligibility List has been reset.<br><br>";
echo "<a href=\"welcome.php?session=$session&toggle=menu2\">Home-->Eligibility</a>";
echo $end_html;
?>
