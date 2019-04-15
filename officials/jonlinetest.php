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

echo $init_html;
echo GetHeaderJ($session);
echo "<center><br><br>The online tests for speech and play production test are not yet available.  Please check back soon!";
echo $end_html;
exit();




?>
