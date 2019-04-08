<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td><br>";

$sql="SELECT school,first,middle,last,semesters FROM eligibility WHERE eligible='y' AND gender='$gender' AND sw='x' AND school!='Test\'s School' ORDER BY school";
$result=mysql_query($sql);
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2><caption><b>Eligible ";
if($gender=='m')
   echo "BOYS";
else echo "GIRLS";
echo " Swimmers, in School Order:</b></caption>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left";
   if($ix%2==0) echo " bgcolor=#E0E0E0";
   echo "><td>$row[school]</td><td>$row[first] $row[middle] $row[last]</td>";
   $year=GetYear($row[semesters]);
   switch($year)
   {
      case '9':
	 $grade="FR";
	 break;
      case '10':
	 $grade="SO";
	 break;
      case '11':
	 $grade="JR";
	 break;
      case '12':
	 $grade="SR";
	 break;
   }
   echo "<td align=center>$grade</td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";

echo $end_html;
?>
