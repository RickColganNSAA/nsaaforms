<?php
//login.php: takes passcode from index.php and checks if user
//  is authorized and for what level of access

require 'variables.php';
require 'functions.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

$school2=ereg_replace("\'","\'",$school);
$sql="SELECT * FROM logins WHERE school='$school2' AND passcode='$passcode'";
$result=mysql_query($sql);

if($college!="")
{
   $college2=addslashes($college);
   $sql="SELECT * FROM logins WHERE school='$college2' AND level='4' AND passcode='$passcode'";
   $result=mysql_query($sql);
}
else if($esu!='')
{
   $esu2=addslashes($esu);
   $sql="SELECT * FROM logins WHERE school='$esu2' AND level='6' AND passcode='$passcode'";
   $result=mysql_query($sql);
}
if(mysql_num_rows($result)>0 && $passcode!="")
{
  $row=mysql_fetch_array($result);
  $num=$row[0];
  $session_id=time();
  $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
  $result2=mysql_query($sql2);
  while($row2=mysql_fetch_array($result2))
  {
     $session_id--;
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
//echo $sql;
  header("Location:index.php?error=1");
  exit();
}

?>
