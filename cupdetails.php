<?php
/*********************************
cupdetails.php
NSAA can view schools and according participation
Author: criticalitgroup
Created: 6/7/18
*********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user


/****** VIEW DETAILS FOR THIS SCHOOL ******/

echo $init_html;
echo GetHeader($session)."<br>";

//echo "<p style=\"text-align:left;\"><a href=\"cupadmin.php?session=$session\">&larr; Return to NSAA Cup Main Menu</a></p>";

      $schoolname=GetSchool2($schoolid);
      echo "<h1>NSAA Cup Schools</h1>";

      $sql2="SELECT Distinct schoolid FROM cupschoolsactivities ";
      $result2=mysql_query($sql2);
	  
	  $sql3="SELECT Distinct activity FROM cupschoolsactivities ";
      $result3=mysql_query($sql3);
	  
      echo '<table border="1"><tr><td></td>';
 	  while($row3=mysql_fetch_array($result3))
      {
	       echo '<td>'.GetActivityName($row3[activity]).'</td>';
		   $test[]=$row3[activity];
	  } 
	  echo '</tr>';
	  
	  while($row2=mysql_fetch_array($result2))
      {
           echo '<tr><td>'.GetSchool2($row2[schoolid]).'</td>';
	  
		  for ($i=0;$i<count($test); $i++)
		  {
			   $sql4="SELECT participating FROM cupschoolsactivities WHERE schoolid='$row2[schoolid]' and activity='$test[$i]'";
			   $result4=mysql_query($sql4);
			   $row4=mysql_fetch_array($result4);
			   if ($row4[participating]=='x')
			   echo '<td style="background-color:yellow">X</td>';
			   else echo '<td></td>';
		  } 
		  echo '</tr>';
	  }
echo'</table>'; 


?>
