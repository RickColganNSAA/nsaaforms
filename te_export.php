<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//te_export.php: export csv file of names/grades/records of
//	tennis players for use in importing to Access DB
//	for seeding meeting

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

require 'variables.php';
require 'functions.php';

$level=GetLevel($session);
if(!ValidUser || $level!=1)
{
   header("Location:index.php");
   exit();
}

$string="";
$sql="SELECT * FROM te_b ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($tennis["$row[3]"]["class"]=="") $tennis["$row[3]"]["class"]=$row[4];
   if(ereg("singles",$row[5]))
   {
      $sql2="SELECT last, first, middle, semesters FROM eligibility WHERE id='$row[1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $tennis["$row[3]"]["$row[5]"]["name"]="$row2[1] $row2[0]";
      $tennis["$row[3]"]["$row[5]"]["grade"]=GetYear($row2[3]);
      $tennis["$row[3]"]["$row[5]"]["record"]=$row[6];
   }
   else if(ereg("doubles",$row[5]))
   {
      $sql2="SELECT last, first, middle, semesters FROM eligibility WHERE id='$row[1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $tennis["$row[3]"]["$row[5]"]["name1"]="$row2[1] $row2[0]";
      $tennis["$row[3]"]["$row[5]"]["grade1"]=GetYear($row2[3]);
      $tennis["$row[3]"]["$row[5]"]["record"]=$row[6];
      $sql2="SELECT last, first, middle, semesters FROM eligibility WHERE id='$row[2]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $tennis["$row[3]"]["$row[5]"]["name2"]="$row2[1] $row2[0]";
      $tennis["$row[3]"]["$row[5]"]["grade2"]=GetYear($row2[3]);
   }
}

$string.="Class,School,Coach,#1 Single Name,#1 Single Grade,#1 Single Record,#2 Single Name,#2 Single Grade,#2 Single Record,#1 Double Name 1,#1 Double Grade 1,#1 Double Name 2,#1 Double Grade 2,#1 Double Record,#2 Double Name 1,#2 Double Grade 1,#2 Double Name 2,#2 Double Grade 2,#2 Double Record\r\n";

$sql="SELECT DISTINCT school FROM te_b ORDER BY class_dist";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $s1name=$tennis["$row[0]"]["1singles"]["name"];
   $s1grade=$tennis["$row[0]"]["1singles"]["grade"];
   $s1record=$tennis["$row[0]"]["1singles"]["record"];
   $s2name=$tennis["$row[0]"]["2singles"]["name"];
   $s2grade=$tennis["$row[0]"]["2singles"]["grade"];
   $s2record=$tennis["$row[0]"]["2singles"]["record"];
   $d1name1=$tennis["$row[0]"]["1doubles"]["name1"];
   $d1grade1=$tennis["$row[0]"]["1doubles"]["grade1"];
   $d1record=$tennis["$row[0]"]["1doubles"]["record"];
   $d1name2=$tennis["$row[0]"]["1doubles"]["name2"];
   $d1grade2=$tennis["$row[0]"]["1doubles"]["grade2"];
   $d2name1=$tennis["$row[0]"]["2doubles"]["name1"];
   $d2grade1=$tennis["$row[0]"]["2doubles"]["grade1"];
   $d2record=$tennis["$row[0]"]["2doubles"]["record"];
   $d2name2=$tennis["$row[0]"]["2doubles"]["name2"];
   $d2grade2=$tennis["$row[0]"]["2doubles"]["grade2"];
   $class=$tennis["$row[0]"]["class"];
   $school2=ereg_replace("\'","\'",$row[0]);
   $sql2="SELECT name FROM logins WHERE level=3 AND school='$school2' AND sport='Boys Tennis'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $coach=$row2[0];
   if($row[0]!="Test's School")
      $string.="$class,$row[0],$coach,$s1name,$s1grade,$s1record,$s2name,$s2grade,$s2record,$d1name1,$d1grade1,$d1name2,$d1grade2,$d1record,$d2name1,$d2grade1,$d2name2,$d2grade2,$d2record\r\n";
}

$open=fopen(citgf_fopen("teplayers.txt"),"w");
fwrite($open,$string);
fclose($open); 
 citgf_makepublic("teplayers.txt");
header("Location:teplayers.txt");
//<a href="teplayers.csv" target=new>Click here to open file</a>
?>
