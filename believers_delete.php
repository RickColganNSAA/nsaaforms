<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);


 if (isset($_GET['id'])) {

   $sql="DELETE FROM believers WHERE id=$_GET[id]";
   $result=mysql_query($sql);
   header("Location:believers_list.php?session=$session");
   exit();

}



 echo $init_html;
 echo $header;


//echo $end_html;
?>
