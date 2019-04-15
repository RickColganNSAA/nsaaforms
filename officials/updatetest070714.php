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

//update test questions & answers in database
$test=$sport."test";
$categ=$sport."test_categ";
$results=$sport."test_results";

for($i=$start;$i<=$end;$i++)
{
   $ques2[$i]=addslashes($ques[$i]);
   $ref2[$i]=addslashes($ref[$i]);
   $sql="UPDATE $test SET question='$ques2[$i]',answer='$ans[$i]',reference='$ref2[$i]' WHERE place='$i'";
   $result=mysql_query($sql);
}

if($save=="Save & Go to Next Section")
{
   $curcat++;
   if($sport=='sp' || $sport=='pp')
      header("Location:jedittest.php?session=$session&sport=$sport&curcat=$curcat");
   else
      header("Location:edittest.php?session=$session&sport=$sport&curcat=$curcat");
   exit();
}
else	//jump to 
{
   if(ereg("Home",$jumptocat))
   {
      if($sport!='sp' && $sport!='pp')
         header("Location:welcome.php?session=$session");
      else
	 header("Location:jwelcome.php?session=$session");
   }
   else if(ereg("Admin",$jumptocat))
      header("Location:testreport.php?session=$session&sport=$sport");
   else
      header("Location:edittest.php?session=$session&sport=$sport&curcat=$jumptocat");
   exit();
}

?>
