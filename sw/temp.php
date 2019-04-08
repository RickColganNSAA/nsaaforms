<?php
exit();
/*
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

$sql="SELECT * FROM sw_verify_perf_g WHERE formid!='0'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $curperf=$row[performance];
   if(ereg(":",$curperf))
   {
      $perf=split("[:.]",$curperf);
      $newperf=60*$perf[0];
      $newperf+=$perf[1];
      $newperf.=".".$perf[2];
      $sql2="UPDATE sw_verify_perf_g SET performance='$newperf' WHERE id='$row[0]'";
      $result2=mysql_query($sql2);
      echo "$sql2<br>".mysql_error();
   }
}

echo "DONE!";
*/
exit();
?>
