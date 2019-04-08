<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$table="jo";
$sql="SELECT * FROM $table";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM eligibility WHERE id='$row[1]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $school=ereg_replace("\'","\'",$row2[0]);
   $sql2="UPDATE $table SET school='$school' WHERE student_id='$row[1]'";
   $result2=mysql_query($sql2);
   echo mysql_error();
   echo $row[1]." ".$school."<br>";
}
echo "DONE";
//echo "NOP";
?>
