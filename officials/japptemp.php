<?php
require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$open=fopen(citgf_fopen("apps/testapp.html"),"w");
$date=date("r");
fwrite($open,"Testing!! $date");
fclose($open); 
 citgf_makepublic("apps/testapp.html");

echo "<a href=\"apps/testapp.html\" target=new>testapp.html</a>";
exit();
?>
