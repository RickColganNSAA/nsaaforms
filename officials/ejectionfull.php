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
if(!$database || $database=='')
{
   $db1="$db_name"; $db2="$db_name2";
}
else
{
   $db1=$database; $db2=ereg_replace("scores","officials",$database);
}

echo $init_html;
echo "<table><tr align=left><td>";
$sql="SELECT DISTINCT t1.school FROM $db1.headers AS t1 LEFT JOIN $db1.ejections AS t2 ON (t1.school=t2.school) WHERE t2.id IS NOT NULL ORDER BY t1.school";
$result=mysql_query($sql);
//echo "$sql<br>";
$list="";
while($row=mysql_fetch_array($result))
{ 
   $list.=$row[0].",";
}
$sql="SELECT DISTINCT t1.school FROM $db1.headers AS t1 LEFT JOIN $db2.ejections AS t2 ON (t1.school=t2.school) WHERE t2.id IS NOT NULL ORDER BY t1.school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $list.=$row[0].",";
}
$list=substr($list,0,strlen($list)-1);
$list=Unique($list);
$list=split(",",$list);
sort($list);
echo count($list)." Results:<hr>";
for($i=0;$i<count($list);$i++)
{
   echo $list[$i]."<br>";
}
echo $end_html;
?>
