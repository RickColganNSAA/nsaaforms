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
echo $header;

echo "<br><a class=small target=new href=\"priorityexport.php?session=$session\">Export Priority Lists</a><br>";
echo "<br><table><caption><b>Football Priority Lists: </b>";
$sql="SELECT t1.*,t2.school FROM fbpriority AS t1,fbschool AS t2 WHERE t1.sid=t2.sid ORDER BY t2.school";
$result=mysql_query($sql);
$total=mysql_num_rows($result);
echo "($total Results)<hr></caption>";
$ix=0;
echo "<tr valign=top align=left>";
$percol=$total/4;
$thiscol=1;
$column=1;
while($row=mysql_fetch_array($result))
{
   if($thiscol==1)
   {
      echo "<td><table>";
   }
   echo "<tr align=left><td><a class=small target=new href=\"priority.php?session=$session&sid=$row[sid]\">$row[school]</a>";
   if($row[revise]!='0' && $row[revise]!="")
      echo " (revised ".date("m/d/y",$row[revise]).")";
   echo "</td></tr>";
   $thiscol++;
   $ix++;
   if($thiscol>=$percol && $column<4)
   {
      echo "</table></td>";
      $thiscol=1;
      $column++;
   }
}
echo "</td></tr></table>";

echo $end_html;
?>
