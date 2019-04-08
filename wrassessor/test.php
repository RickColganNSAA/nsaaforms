<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

   for($i=49;$i<=71;$i++)
   {
      $field="slide".$i;
      $sql="ALTER TABLE wrassessors ADD $field VARCHAR( 10 ) NOT NULL";
      $result=mysql_query($sql);
   }

echo $sql."\r\n".mysql_error();

?>
