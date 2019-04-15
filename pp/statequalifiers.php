<?php
/************************************
statequalifiers.php

Link on NSAA Play Production page
(nsaahome.org/pp.php)

Given: $class 

Pulls schools marked as qualifying
for State in that class, shows play
info and students

Created 12/1/09
Author: Ann Gaffigan
*************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../variables.php';
require '../functions.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

echo $init_html;
echo "<table width='100%' class='nine'><tr align=left><td>";
echo "<b><u>CLASS $class STATE PLAY PRODUCTION QUALIFIERS</u></b><br><br>";
$sql="SELECT * FROM pp WHERE statequalifier='x' ORDER BY school";
if ($class=='A') $sql="SELECT * FROM pp WHERE statequalifier='x' AND class_dist!=4 ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sid=GetSID2($row[school],'pp');
   $sql2="SELECT * FROM ppschool WHERE sid='$sid' AND class='$class'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)  
   {
	//get coach
	$sql3="SELECT name,asst_coaches FROM logins WHERE school='".addslashes($row[school])."' AND level=3 AND sport='Play Production'";
	$result3=mysql_query($sql3);
	$row3=mysql_fetch_array($result3);
	$coach=$row3[0];
	$asst=$row3[1];
      $row[title]=ereg_replace("\"","",$row[title]);
      echo "<font style='font-size:11pt;'><b>$row[school]</b></font><br><b>\"$row[title]\"</b><br>Written by: $row[playwright]<br>Director: $row[director]<br>";
      if(trim($asst)!='') echo "Assistant Director(s): $asst<br>";
      echo "<br>";
      $sql2="SELECT t1.*,t2.part FROM eligibility AS t1,pp_students AS t2 WHERE t1.id=t2.student_id AND t2.school='".addslashes($row[school])."' AND t2.crew IS NULL ORDER BY t2.partorder";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "$row2[part]: ".GetStudentInfo($row2[id],FALSE)."<br>";
      }
      $sql2="SELECT t1.* FROM eligibility AS t1,pp_students AS t2 WHERE t1.id=t2.student_id AND t2.school='".addslashes($row[school])."' AND t2.crew='y' ORDER BY t1.last,t1.first";
      $result2=mysql_query($sql2);
      $crew="";
      while($row2=mysql_fetch_array($result2))
      {
         $crew.="$row2[first] $row2[last], ";
      }
      $crew=substr($crew,0,strlen($crew)-2);
      echo "Technical Crew: $crew<br><br>";
   }
}
echo $end_html;
?>
