<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

echo $init_html;

if(!$open=fopen(citgf_fopen("fbinfo.csv"),"r")) echo "Could not Open File.";
$line=file(getbucketurl("fbinfo.csv"));
for($i=0;$i<count($line);$i++)
{
   $temp=split(",",$line[$i]);
   $sch=addslashes($temp[0]);
   $sql="UPDATE fbschool SET enrollment='$temp[1]',tiebreaker='$temp[2]' WHERE school='$sch'";
   $result=mysql_query($sql);
   echo "$sql<br>";
   echo mysql_error()."<br>";
}

echo $end_html;
?>
