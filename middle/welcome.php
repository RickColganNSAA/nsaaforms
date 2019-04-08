<?php
/********************************************
welcome.php

Middle Schools Administrator's Main Menu

Created 12/26/09
Author: Ann Gaffigan
*********************************************/

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=8)
{
   header("Location:login.php");
   exit();
}

   //if on or after Jan 1, increment all students' semester
   $year=date("Y");
   $month=date("m");
   $day=date("d");
   $today="$year-$month-$day";
   $school2=addslashes(GetSchool($session));
   if($month>=1 && $month<6)
   {
      $sql="SELECT sem_inc FROM middleschools WHERE school='$school2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sem_inc=$row[0]; //=1 if semesters have been incremented, else =0
      if($sem_inc==0)
      {
         $sql2="UPDATE middleeligibility SET semesters=semesters+1 WHERE school='$school2' AND semesters!=0";
         $result2=mysql_query($sql2);

         $sql2="UPDATE middleschools SET sem_inc='1' WHERE school='$school2'";
         $result2=mysql_query($sql2);
      }
   }

echo $init_html;
echo $header;
$title=GetActivity($session);
echo "<br><div class='content'>";

echo "<h3>Welcome, ".GetSchool($session)."!</h3>";
echo "<div class='welcomeheader'>Eligibility List (Your Students):</div>";
echo "<div class='welcomesection'>";
if(date("Y")>2010 && !PastDue(date("Y")."-09-08",0) && PastDue(date("Y")."-05-31",0))        //Show link to import new students
{
   $year1=date("Y"); $year2=$year1+1;
   echo "<p><font style=\"color:red\"><b>It's time to import your $year1-$year2 students!</b><br></font>";
   echo "<a class=small href=\"export_students.php?session=$session\">Click Here</a> to get started.";
   echo "</p>";
}
echo "<a href=\"elig_query.php?session=$session\">Eligibility List Advanced Search</a><br><br><b>-OR-</b><br><br>";
echo "<a href=\"eligibility.php?session=$session\">View Full Eligibility List</a><br>";
echo "</div>";

echo "</div>";
echo $end_html;
?>
