<?php
echo "NOP";
exit();
/*
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sport="wr";
$table=$sport."off";
$tableorig=$sport."offorig";

echo "<table>";

$sql="SELECT * FROM officials WHERE $sport='x' ORDER BY last,first";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $tableorig WHERE offid='$row[socsec]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql3="SELECT years FROM $table WHERE offid='$row[id]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $oldyears=$row3[0];
      if($oldyears!=0 && mysql_num_rows($result3)>0)
         echo "<tr align=left><td>$row[id]</td><td>$row[socsec]</td><td>$row[first] $row[last]</td><td>$oldyears</td></tr>";
   }
   else
   {
      $row2=mysql_fetch_array($result2);
      $years=$row2[years];

      $sql3="UPDATE $table SET years='$years' WHERE offid='$row[id]'";
      $result3=mysql_query($sql3);
   }
}
echo "</table>";
*/
?>
