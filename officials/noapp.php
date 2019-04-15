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
echo GetHeader($session);
echo "<center><br><br>";
echo "This application is not yet available at this time.  Please check back later.  Thank You!<br><br>";
echo "<a href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;

?>
