<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);


 if (isset($_GET['id'])) {

   $sql="DELETE FROM anthem WHERE id=$_GET[id]";
   
   $result=mysql_query($sql);
   
   $result=mysql_query($sql);
   if(isset($_GET['school']) && $_GET['school']!='')
		header("Location:anthem_list1.php?session=$session&school=".$_GET['school']);
   else header("Location:anthem_list.php?session=$session");
   
  // header("Location:anthem_list.php?session=$session");
   exit();

}



 echo $init_html;
 echo $header;


//echo $end_html;
?>
