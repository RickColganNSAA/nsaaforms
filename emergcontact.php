<?php
/******************************
emergcontact.php
Report of Emergency Contact #'s
for Schools; NSAA & Schools can
see this report
Created: 8/13/08
Author: Ann Gaffigan
*******************************/

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
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

echo "<a name=\"top\"><br></a><table width=\"400px\" cellspacing=0 cellpadding=4 frames=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>Athletic Director's Emergency Contact Numbers:</b><br><i>Alphabetical by School</i>";
$alphabet=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$alphabet2=array(); $ix=0;
for($i=0;$i<count($alphabet);$i++)
{
   $sql="SELECT id FROM headers WHERE school LIKE '".$alphabet[$i]."%' LIMIT 1";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $alphabet2[$ix]=$alphabet[$i]; $ix++;
   }
}
echo "</caption>";
echo "<tr align=center><td><b>School</b></td><td><b>Athletic Director</b></td><td><b>Emergency #</b></td></tr>";
$sql="SELECT * FROM logins WHERE level='2' ORDER BY school";
$result=mysql_query($sql);
$curalpha="";
while($row=mysql_fetch_array($result))
{
   if(substr($row[school],0,1)!=$curalpha) 
   {
      $curalpha=substr($row[school],0,1);
      echo "<tr align=center><td colspan=3>";
      for($i=0;$i<count($alphabet2);$i++)
      {
         echo "<a href=\"#".$alphabet2[$i]."\" class=small>".$alphabet2[$i]."</a>&nbsp;&nbsp;";
      }
      echo "<a name=\"$curalpha\">&nbsp;</a></td></tr>";
   }
   echo "<tr align=left><td>$row[school]</td><td>$row[name]&nbsp;</td>";
   if($row[hours]=='--') echo "<td>[Not Listed]</td>";
   else echo "<td>$row[hours]</td>";
   echo "</tr>";
}
echo "</table>";
echo "<br><br><a class=small href=\"#top\">Top</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"welcome.php?session=$session\">Home</a>";

echo $end_html;
?>
