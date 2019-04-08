<?php
//student_count.php: shows list of schools and how many students
//	they have entered into the database, least to most

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo GetHeader($session);

if($submit=="Go")
{
   if($num=='')
   {
      echo "<br><br>You must enter a number.<br><br>";
      echo "<a class=small href=\"welcome.php?session=$session&open=2#2\">Try Again</a>";
      echo $end_html;
      exit();
   }
   echo "<br><br>";
   echo "<a href=\"welcome.php?session=$session&open=2#2\">Search Again</a><br>";
   $schools=array();
   $ix=0;
   $sql="SELECT school FROM headers ORDER BY school";
   $result=mysql_query($sql);
   echo "<table>";
   echo "<tr align=center><td colspan=2><b>Schools with <= $num students:</b></td></tr>";
   $ix=1;
   while($row=mysql_fetch_array($result))
   {
      $school2=ereg_replace("\'","\'",$row[0]);
      $sql2="SELECT * FROM eligibility WHERE school='$school2'";
      $result2=mysql_query($sql2);
      $ct=mysql_num_rows($result2);
      if($ct<=$num) 
      {
         echo "<tr align=left><td>$ix)&nbsp;$row[0]:</td><td>$ct</td></tr>";
         $ix++;
      }
   }
   echo "</table><br>";
   echo "<a href=\"welcome.php?session=$session&open=2#2\">Search Again</a>";
   echo "&nbsp;&nbsp;&nbsp;<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else
{
   echo "<br><br>Error";  
   echo $end_html;
   exit();
}
?>
