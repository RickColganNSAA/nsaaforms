<?php
/******************************************
teamrankings.php
Regular Season Golf Tournament RANKINGS
(Publicly Accessible page)
Created 8/31/15
Author Ann Gaffigan
********************************************/

require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);

echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";

   $sql="SELECT * FROM godiffsettings WHERE sport='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);

   echo $row[standings];

echo $end_html;

?>
