<?php
/*******************************************
judgerankings.php
NSAA User can view/edit rankings submitted
by judges
Created 1/7/13
Author: Ann Gaffigan
*******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php?error=1");
   exit();
}

echo $init_html;
echo $header;

echo "<br><p><a href=\"stateadmin.php?session=$session\">Return to JO Contest Main Menu</a></p>";

echo "<h2>NSAA Journalism Contest - Judges' Rankings</h2>";
echo "<form method=post action=\"judgerankings.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<table cellspacing=0 cellpadding=5 class='nine' style=\"width:700px;\">";
echo "<caption>";
echo "<p><b>Select a Class to View/Edit Rankings: </b>";
echo "<select name=\"class\" onChange=\"submit();\"><option value=''>Select Class</option>";
$classes=array("A1","A2","B","C","D");
for($i=0;$i<count($classes);$i++)
{
   echo "<option value=\"$classes[$i]\"";
   if($class==$classes[$i]) echo " selected";
   echo ">Class $classes[$i]</option>";
}
echo "</select></p>";
echo "</caption>";
if($class && $class!='')
{
	/*
   echo "<tr align=center><td colspan=2><br><h3>Class $class Preliminary Sweepstakes Results:</h3></td></tr>";
   //FOR EACH CATEGORY -- SHOW SUBMISSIONS
   $sql="SELECT * FROM jocategories ORDER BY category";
   $result=mysql_query($sql);
   $percol=ceil(mysql_num_rows($result)/2);
   $i=0;
   echo "<tr align=left valign=top><td width='50%'>";
   while($row=mysql_fetch_array($result))
   {
      if($i==$percol)
         echo "</td><td>";
      echo "<p><b>".strtoupper($row[category]).":</b></p>"; 
      $sql2="SELECT t1.* FROM joentries AS t1,joschool AS t2 WHERE t1.sid=t2.sid AND t1.catid='$row[id]' AND t2.class='$class'";
      $sql2.=" AND t1.classrank>0 AND t1.classrank<=3 ORDER BY t1.classrank";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 echo "<p>$row2[classrank]. ".GetStudentInfo($row2[studentid],FALSE).", ";
	 for($j=2;$j<=6;$j++)
	 {
	    $var="studentid".$j;
	    if($row2[$var]>0) echo GetStudentInfo($row2[$var],FALSE).", ";
         }
	 echo GetSchoolName($row2[sid],'jo')."</p>";
	 echo "<p style='padding-left:20px;'><a class='small' href=\"/nsaaforms/downloads/$row2[filename]\" target=\"_blank\">$row2[label]</a></p>";
	 if($row2[filename2]!='')
	    echo "<p style=\"padding-left:20px;\"><a href=\"/nsaaforms/downloads/$row2[filename2]\" target=\"_blank\">$row2[label2]</a></p>";
      }
      echo "<br>";
      $i++;    
   }
   echo "</td></tr>";
	*/
} //END IF CLASS SELECTED
echo "</table>";
echo "(Under Construction)";
echo "</form>";
echo $end_html;
?>
