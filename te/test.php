<?php
require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'tefunctions.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

$sport="te_g"; $class="B"; $division="singles1";
//echo GetNonSeededEntries($sport,$class,$division);

exit();

//GET TEST DATA FOR STATE GIRLS TENNIS

$sport="te_g";
$sql="SELECT * FROM te_gschool WHERE class='B'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $div="doubles2";
   $ix=4;	//WHICH STUDENT TO PICK FROM THE LIST 
   $ix2=5;	//OTHER STUDENT (IF DOUBLES)
   $school=GetMainSchoolName($row[sid],$sport);
   $studs=GetPlayers($sport,$school);
         //$string.="$row[id]<detail>$row[first] $row[last] ($grade)<detail>$row[school]<detail>$row[eligible]<result>";
   $studs=explode("<result>",$studs);
   if(count($studs)>$ix)
      $stud=explode("<detail>",$studs[$ix]);
   else
      $stud=explode("<detail>",end($studs));
   if(count($studs)>$ix2)
      $stud2=explode("<detail>",$studs[$ix2]);
   else
      $stud2=explode("<detail>",end($studs));
   $sql2="INSERT INTO ".$sport."state (sid,division,player1,player2) VALUES ('$row[sid]','$div','$stud[0]','$stud2[0]')";
   $result2=mysql_query($sql2);
   echo $sql2."\r\n";
}
echo "DONE!\r\n";
?>
