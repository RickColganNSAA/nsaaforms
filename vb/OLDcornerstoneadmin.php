<?php
//programadmin.php: For Cornerstone (printing company) to view approved program pages (roster pages)
//Created 7/29/13
//Author: Ann Gaffigan

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo GetHeader($session);

//TESTING
$sql="USE nsaascores20122013";
$result=mysql_query($sql);

echo "<form method=post action=\"programadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

echo "<br><table class='nine' cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>State Volleyball Roster Pages</b><br>";
$sport="vb";
$table=$sport."_state";
if(!$sort) $sort="t2.approvedforprogram DESC";
$sql="SELECT DISTINCT t1.school,t2.approvedforprogram FROM $table AS t1,".$sport."school AS t2,headers AS t3 WHERE t1.school=t3.school AND t3.id=t2.mainsch AND t2.approvedforprogram>0 ORDER BY t2.approvedforprogram DESC";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "</caption><tr align=center><td><br>No roster pages have been approved yet.<br><br></td></tr>";
else
{
   echo "<i>The following State ".GetActivityName($sport)." roster pages have been APPROVED:</i></caption>";
   echo "<tr align=center><td><b>Schools on the Page</b></td>";
   echo "<td><b>Roster Page</b></td></tr>"; //<td><b>Approved for<br>STATE PROGRAM</b></td></tr>";
}
$sportname=GetActivityName($sport);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $sid1=GetSID2($row[school],'vb');
   $school1=$row[school];

   if($row=mysql_fetch_array($result))
   {
      $sid2=GetSID2($row[school],'vb');
      $school2=$row[school];
   }
   else
   {
      $sid2=0; $school2="";
   }

   echo "<tr align=left><td>$school1";
   if($sid2>0) echo "<br>$school2";
   echo "</td>";
   echo "<td><a href=\"programpdf.php?sid1=$sid1&sid2=$sid2&session=$session\" target=\"_blank\">View/Save PDF</a></td>";
   //echo "<td align=center>".date("m/d/y",$row[approvedforprogram])." at ".date("g:ia",$row[approvedforprogram])."</td>";
   echo "</tr>";

}
echo "</table>";

echo "</form>";

echo $end_html;
?>
