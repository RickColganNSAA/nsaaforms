<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//update test questions & answers in database
$test=$sport."test2";

$sql="UPDATE test2_duedates SET totalques='$totalques',instructions='".addslashes($instructions)."' WHERE test='$sport'";
$result=mysql_query($sql);

for($i=0;$i<count($quesid);$i++)
{
   if($delete[$i]=='x')
   {
      $sql="DELETE FROM $test WHERE id='$quesid[$i]'";
      $result=mysql_query($sql);
      $sql="DELETE FROM ".$test."_mchoices WHERE questionid='$quesid[$i]'";
      $result=mysql_query($sql);
   }
   else
   {
      $ques2[$i]=addslashes($ques[$i]);
      $ref2[$i]=addslashes($ref[$i]);
      $sql="UPDATE $test SET question='$ques2[$i]',answer='$ans[$i]',reference='$ref2[$i]' WHERE id='$quesid[$i]'";
      $result=mysql_query($sql);
   }
}

   header("Location:edittest2.php?session=$session&sport=$sport&saved=1");
   exit();
?>
