<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//login.php: takes passcode from index.php and checks if user
//  is authorized and for what level of access

if(($school=="All" && $passcode=="officials5") || $school=="Officials")
{
   //login to Officials database, send to $db_name2
   header("Location:officials/login.php?school=$school&passcode=$passcode");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

require 'functions.php';

$school2=ereg_replace("\'","\'",$school);
$sql="SELECT * FROM logins WHERE school='$school2' AND passcode='$passcode'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $passcode!="")
{
  $row=mysql_fetch_array($result);
  $num=$row[0];
  $session_id=time()*$num;
  $sql="INSERT INTO sessions (session_id, login_id) VALUES ('$session_id', '$row[0]')";
  $result=mysql_query($sql);
  header("Location:welcome.php?session=$session_id");
  exit();
}
else
{
  header("Location:index.php?error=1");
  exit();
}

?>
