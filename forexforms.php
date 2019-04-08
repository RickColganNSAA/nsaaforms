<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
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
echo "<br>";

//GET TITLE OF THE FORM FROM forexsettings
$sql="SELECT * FROM forexsettings";
$result=mysql_query($sql);
$forminfo=mysql_fetch_array($result);

$sql="SELECT * FROM forex WHERE school='$school2' ORDER BY datesub,execsignature";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "[You currently have no ".$forminfo[formtitle]."s on file.]";
}
else
{
   echo "<table cellspacing=1 cellpadding=3 border=1 bordercolor=#000000>";
   echo "<caption class=small><b>".$forminfo[formtitle]."s on file for $school:</b><br><br></caption>";
   echo "<tr align=center><td><b>Submitted</b></td><td><b>Student (Country)</b><br>(Click for form)</td><td><b>Action taken by<br>Executive Director</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echO "<tr align=left><td>";
      if($row[datesub]=='') echo "NO";
      else echo date("m/d/Y",$row[datesub]);
      echo "</td><td><a class=small ";
      if($row[datesub]!='') echo "target=\"_blank\" ";
      echo "href=\"forex.php?session=$session";
      if($row[datesub]!='') echo "&header=no";
      echo "&id=$row[id]\">";
      $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "$row2[first] $row2[last] ($row[country])</a></td>";
      echo "<td>";
      if($row[execsignature]=='') echo "NO";
      else echo date("m/d/Y",$row[execdate]);
      echo "</td></tr>";
   }
   echo "</table>";
}
echo "<br><br>";
echo "<font style=\"font-size:9pt;\">Start a new: <a href=\"forex.php?session=$session\">$forminfo[formtitle]</a></font>";
echo $end_html;
?>
