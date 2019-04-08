<?php
/*********************************
cupmusic.php
NSAA can calculate Music points
and see list of who got what
Author: Ann Gaffigan
Created: 9/29/15
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
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
/* $db_name='nsaascores20172018';
$db=mysql_connect($db_host,$db_user,$db_pass); */
if($pull==1)
{
   CupAssignPoints('mu','reg');	//MAKE SURE WE HAVEN'T MISSED ANY SCHOOLS THAT DID REGISTER FOR MU
   $sql="SELECT * FROM headers";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $schoolid=$row[id]; $points=0;
         //Need at least 2 IM entries to get 5 points; another 5 for >=2 VM entries
         $school2=mysql_real_escape_string(GetSchool2($schoolid));
                //INSTRUMENTAL:
         $sql2="SELECT t1.id FROM muentries AS t1, muschools AS t2, muensembles AS t3, mucategories AS t4 WHERE t1.schoolid=t2.id AND t2.school='$school2' AND t1.ensembleid=t3.id AND t3.categid=t4.id AND t4.vocinst='Instrumental'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>=2) $points+=5;
                //VOCAL:
         $sql2="SELECT t1.id FROM muentries AS t1, muschools AS t2, muensembles AS t3, mucategories AS t4 WHERE t1.schoolid=t2.id AND t2.school='$school2' AND t1.ensembleid=t3.id AND t3.categid=t4.id AND t4.vocinst='Vocal'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>=2) $points+=5;
                //Make sure these are the points in the database
				
		 $sql5="SELECT * FROM cupschools WHERE schoolid='$schoolid'"; 
         $result5=mysql_query($sql5);
         $sch=mysql_fetch_array($result5);
		 
         $sql2="SELECT * FROM cuppoints WHERE activity='mu' AND schoolid='$schoolid' AND class='$sch[cupclass]'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)==0 && $points>0)
            $sql2="INSERT INTO cuppoints (class,activity,schoolid,points) VALUES ('$sch[cupclass]','mu','$schoolid','$points')";
         else if(mysql_num_rows($result2)>0 && $points==0 && ($schoolid !='1569' && $schoolid !='1433' && $schoolid !='1453'))
            $sql2="DELETE FROM cuppoints WHERE activity='mu' AND schoolid='$schoolid' AND class='$sch[cupclass]'";
         else if(mysql_num_rows($result2)>0 && ($schoolid !='1569' && $schoolid !='1433' && $schoolid !='1453'))
            $sql2="UPDATE cuppoints SET points='$points' WHERE activity='mu' AND schoolid='$schoolid' AND class='$sch[cupclass]'";
         else $sql2="";
         if($sql2!='') $result2=mysql_query($sql2);

      UpdateCupPointTotals($schoolid);	//MAKE SURE THIS SCHOOL'S TOTAL IS UP TO DATE
   }
   $sql="SELECT * FROM cupmusicsettings";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="INSERT INTO cupmusicsettings (lastupdate) VALUES ('".time()."')";
      $result=mysql_query($sql);
   }
   else
   {
      $sql="UPDATE cupmusicsettings SET lastupdate='".time()."'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a href=\"cupplaces.php?session=$session\">&larr; Return to NSAA Cup State Championship Results</a></p>";

echo "<h1>NSAA Cup: Music Points</h1>";

echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\">
	<caption><p><b>Music points are awarded as follows:</b></p><ul><li>5 points if a school has at least 2 Instrumental Music entries on their District Music Contest form.</li><li>5 points if a school has at least 2 Vocal Music entries on their District Music Contest form, for a maximum possible total of 10 points.</li></ul>
	<h3>Schools with Music Points:</h3></caption><tr align=center><th rowspan=2>SCHOOL</th><th colspan=2>INSTRUMENTAL</th><th colspan=2>VOCAL</th><th rowspan=2>TOTAL</th></tr>
	<tr align=center><th>Entries</th><th>Points</th><th>Entries</th><th>Points</th></tr>";

 $sql="SELECT t1.*,t2.school FROM cuppoints AS t1, headers AS t2 WHERE t1.schoolid=t2.id AND t1.activity='mu' AND t1.class!='reg' ORDER BY t2.school";
 
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $school2=mysql_real_escape_string(GetSchool2($row[schoolid]));
   echo "<tr align='center'><td align='left'>$row[school]</td>";
	//IM:
   $sql2="SELECT t1.id FROM muentries AS t1, muschools AS t2, muensembles AS t3, mucategories AS t4 WHERE t1.schoolid=t2.id AND t2.school='$school2' AND t1.ensembleid=t3.id AND t3.categid=t4.id AND t4.vocinst='Instrumental'";
   $result2=mysql_query($sql2);
   $ct=mysql_num_rows($result2);
   if($ct>=2) $points=5;
   else $points=0;
   echo "<td>$ct</td><td>$points</td>";
	//VM:
   $sql2="SELECT t1.id FROM muentries AS t1, muschools AS t2, muensembles AS t3, mucategories AS t4 WHERE t1.schoolid=t2.id AND t2.school='$school2' AND t1.ensembleid=t3.id AND t3.categid=t4.id AND t4.vocinst='Vocal'";
   $result2=mysql_query($sql2);
   $ct=mysql_num_rows($result2);
   if($ct>=2) $points2=5;
   else $points2=0;
   echo "<td>$ct</td><td>$points2</td>";
   $points+=$points2;
   echo "<td>$row[points]";
   if($row[points]!=$points) echo "?????";
   echo "</td></tr>";
}

?>
