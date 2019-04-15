<?php
require '../../calculate/functions.php';
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!='1')
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo $header;

$sql="SELECT DISTINCT class FROM ppschool WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
echo "<br><h2>State Play Participation Numbers:</h2>";
echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;width:500px;\">";
while($row=mysql_fetch_array($result))
{
   $curclass=$row[0];
   //if($curclass=="") $curclass=";
   $sql2="SELECT DISTINCT school FROM pp WHERE statequalifier='x'";
   $result2=mysql_query($sql2);
   echo "<tr align=left><th align=left colspan=3>CLASS ".$curclass."</th></tr>";
   echo "<tr align=left><td><b>School</b></td><td><b>Coach</b></td><td><b>Count</b></td></tr>";
   while($row2=mysql_fetch_array($result2))
   {
      $sid=GetSID2($row2[school],'pp');
      $class=GetClass($sid,'pp');
      if($class==$curclass)
      {
         echo "<tr align=left><td>$row2[school]</td>";
		//COACH:
         $sql3="SELECT name FROM logins WHERE school='".addslashes($row2[school])."' AND sport='Play Production'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         echo "<td>$row3[name]</td><td>";
         $sql3="SELECT DISTINCT t2.student_id FROM pp AS t1,pp_students AS t2 WHERE t1.school=t2.school AND t1.school='".addslashes($row2[school])."'";
         $result3=mysql_query($sql3);
         echo mysql_num_rows($result3);
         echo "</td></tr>";
      }
   }
}
echo "</table>";
echo "<br><br><a href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Play Production\">Return to Home &rarr; Play</a>";


echo $end_html;
?>
