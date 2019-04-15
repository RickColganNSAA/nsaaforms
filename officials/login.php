<?php
//login.php: takes passcode from index.php and checks if user
//  is authorized and for what level of access

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sql="SELECT * FROM logins WHERE passcode='$passcode' AND (level='$level'";
if($level=='1') $sql.=" OR level='4'";
$sql.=")";
$result=mysql_query($sql);

if(mysql_num_rows($result)>0 && $passcode!="")
{
  $row=mysql_fetch_array($result);
  $num=$row[0];
  $session_id=time();
  $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
  $result2=mysql_query($sql2);
  while($row2=mysql_fetch_array($result2))
  {
     $session_id++;
     $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
     $result2=mysql_query($sql2);
  }
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
