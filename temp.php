<?php

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

$sql="SELECT email,name FROM logins WHERE";                
$sql.=" level=2 and email LIKE '%@%'";               
echo $sql;
$result=mysql_query($sql);               
while($row=mysql_fetch_array($result))               
{                  
   $recipients.=$row[0].",";               
}
echo $recipients;
?>
