<?php
/********************************
Public Listing of each year's
District Award Winners
********************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';
require 'mufunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$year) $year=date("Y");
$year--;
$database=GetDatabase($year);
$showyear=$year+1;

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<table cellspacing=0 cellpadding=5 class=nine>";
echo "<caption><img src=\"../../images/nsaalogocolor.png\" style=\"border:none;width:300px;\"><h3>$showyear NSAA District Music Outstanding Performance and Honorable Mention Award Recipients</h3></caption>";

$sql="SELECT * FROM $database.mudistricts ORDER BY distnum,classes,site";
$result=mysql_query($sql);
while($row0=mysql_fetch_array($result))
{
   $musiteid=$row0[id];
   $sql2="SELECT t1.*,t2.school,t3.ensembletype FROM $database.muawardwinners AS t1, $database.muschools AS t2,$database.muensembletypes AS t3 WHERE t1.muschoolsid=t2.id AND t1.muensembletypesid=t3.id AND t1.distid='$musiteid' ORDER BY t2.school,t3.ensembletype,t1.award";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<tr align=left><td colspan=5><br><h3>District $row0[distnum] -- $row0[classes]: ".$row0[site]."</h3></td></tr>";
      echo "<tr align=left><td><b><u>Name</b></u></td><td><b><u>School</u></b></td><td><b><u>Award</b></u></td><td><b><u>Performance Type</b></u></td><td><b><u>Ensemble Students' Names</b></u></td></tr>";

      while($row2=mysql_fetch_array($result2))
      {
         echo "<tr valign=top align=left><td>";	
	 if(ereg("Solo",$row2[ensembletype])) echo $row2[studentnames];
	 else echo $row2[ensembletype];
	 echo "</td>";
	 echo "<td>$row2[school]</td><td>";
	 if($row2[award]=="HM") echo "Honorable Mention";
	 else if($row2[award]=="OP") echo "Outstanding Performance";
	 echo "</td>";
         if($row2[ensembletype]=="Instrumental Solo") $event="Instrumental Solo";
         else if($row2[ensembletype]=="Vocal Solo") $event="Vocal Solo";
         else if(ereg("Vocal",$row2[ensembletype])) $event="Vocal Ensemble";
         else $event="Instrumental Ensemble";
	 echo "<td>$event</td><td width='350px'>";
	 if(ereg("Solo",$row2[ensembletype])) echo "&nbsp;";
	 else echo "$row2[studentnames]";
	 echo "</td>";
	 echo "</tr>";
      }
   }
}

echo "</table>";

echo $end_html;
?>
