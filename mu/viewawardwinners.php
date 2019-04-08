<?php
require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

if($level==3)
{
   $schoolid=GetSchoolID($session); $loginid=0;
}
else if($level==4)
{
   $schoolid=0; $loginid=GetUserID($session);
}

$database="nsaascores";
if($database=="nsaascores")
{
   if(date("m")>=6) $year=date("Y");
   else $year=date("Y")-1;
}
else
{
   $year=ereg_replace("nsaascores","",$database);
   $year=substr($year,0,4);
}
$year++;

if($delete)
{
   $sql="DELETE FROM $database.muawardwinners WHERE id='$delete'";
   $result=mysql_query($sql);
}

if($level!=1 && !$musiteid) 
{
   $musiteid=GetMusicSiteID($schoolid,$loginid);
   if($musiteid==0)
   {
      header("Location:../index.php");
      exit();
   }
}

echo $init_html;
echo $header."<br>";

if($delete)
   echo "<div class=alert style='width:400px;'>The award winner has been deleted.</div><br>";

if($level==1)
{
   if($clear=="yes")	//CLEAR OUT muawardwinners
   {
      $sql2="DELETE FROM muawardwinners";
      $result2=mysql_query($sql2);
   }
   echo "<a href=\"muadmin.php?session=$session\">District Music Admin Main Menu</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"districtawards.php?year=$year\" target=\"_blank\">Preview $year Award Winners on NSAA Website</a><br><br>";
   echo "<div class='alert' style='width:600px;'><p><b>PLEASE NOTE:</b> Each year, when the Award Winners are final, the link above (\"<a href=\"districtawards.php?year=$year\" target=\"_blank\" class=small>Preview Award Winners on NSAA Website</a>\") can be added to the <a href=\"/mu.php\" target=\"_blank\" class=small>Music Page</a> on the NSAA Website, under the heading <b>\"History: Outstanding Performance and Honorable Mention Awards.\" </b>Nothing else needs to be done to archive these awards on the website.</p></div>";
   $sql2="SELECT * FROM muawardwinners";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<div class='help' style='width:600px;'><p>To <b>clear out the award winners in the database and start fresh for the new school year</b>, please <a href=\"viewawardwinners.php?session=$session&clear=yes\" onClick=\"return confirm('Are you sure you want to clear out the award winners below?');\">Click Here</a></p><p>(Award winners are archived on June 1st each year, so as long as it is past that date, you are safe to clear out these award winners.)</p></div>";
   }
   else if($clear=="yes")
   {
      echo "<div class='help' style='width:400px;'><p>The award winners have been cleared from the database. Select a site below to enter new award winners.</p></div>";
   }
   echo "<form method=post action=\"viewawardwinners.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<select name=\"musiteid\" onChange=\"submit();\"><option value=\"0\">Select a District Site</option>";
   $sql="SELECT * FROM $database.mudistricts ORDER BY distnum,classes,site";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($musiteid==$row[id]) echo " selected";
      echo ">$row[distnum] -- $row[classes] $row[site]</option>";
   }
   echo "</select>&nbsp;<input type=submit name='go' value='Go'></form>";
}

if($musiteid)
{
if(!$sort) $sort="t3.ensembletype ASC,t2.school ASC,t1.award ASC";
$sql2="SELECT t1.*,t2.school,t3.ensembletype FROM $database.muawardwinners AS t1, $database.muschools AS t2,$database.muensembletypes AS t3 WHERE t1.muschoolsid=t2.id AND t1.muensembletypesid=t3.id AND t1.distid='$musiteid' ORDER BY $sort";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)
{
   echo "<a href=\"awardwinners.php?session=$session&musiteid=$musiteid\">Add Award Winners from the District Music Entry Form</a>&nbsp;|&nbsp;<a href=\"awardwinners2.php?session=$session&musiteid=$musiteid\">Add Award Winners Manually</a><br><br>";
   echo "<table cellspacing=0 cellpadding=3 class=nine frame=all rules=all style=\"#808080 1px solid;\">";
   echo "<caption><b>District Music Outstanding Performance/Honorable Mention Award Winners:</b><br><br>";
   $sql0="SELECT * FROM $database.mudistricts WHERE id='$musiteid'";
   $result0=mysql_query($sql0);
   $row0=mysql_fetch_array($result0);
   echo "<b>Site:</b> ".$row0[site]."<br><br>";

echo "<p style='text-align:left'><i>All of the award winners you have selected from District Music Entry Forms for your site OR entered manually are shown below. As you complete your site's list, the information is made available immediately to the NSAA office. Click \"Edit\" to edit the student names or other details. Click \"Delete\" to remove an award-winning soloist or ensemble from this list.</i></p></caption>";
echo "<tr align=center>";
if($sort=="t3.ensembletype DESC,t2.school ASC,t1.award ASC")
{
   $curimg="arrowup.png"; $cursort="t3.ensembletype ASC,t2.school ASC,t1.award ASC";
}
else if($sort=="t3.ensembletype ASC,t2.school ASC,t1.award ASC")
{
   $curimg="arrowdown.png"; $cursort="t3.ensembletype DESC,t2.school ASC,t1.award ASC";
}
else
{
   $curimg=""; $cursort="t3.ensembletype ASC,t2.school ASC,t1.award ASC";
}
echo "<td><a class=small href=\"viewawardwinners.php?session=$session&sort=$cursort&musiteid=$musiteid\">Ensemble</a>";
if($sort=="t3.ensembletype ASC,t2.school ASC,t1.award ASC" || $sort=="t3.ensembletype DESC,t2.school ASC,t1.award ASC")
   echo "&nbsp;<a href=\"viewawardwinners.php?session=$session&sort=$cursort&musiteid=$musiteid\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
echo "</td>";
if($sort=="t2.school DESC,t3.ensembletype ASC,t1.award ASC")
{
   $curimg="arrowup.png"; $cursort="t2.school ASC,t3.ensembletype ASC,t1.award ASC";
}
else if($sort=="t2.school ASC,t3.ensembletype ASC,t1.award ASC")
{
   $curimg="arrowdown.png"; $cursort="t2.school DESC,t3.ensembletype ASC,t1.award ASC";
}
else
{
   $curimg=""; $cursort="t2.school ASC,t3.ensembletype ASC,t1.award ASC";
}
echo "<td><a class=small href=\"viewawardwinners.php?session=$session&sort=$cursort&musiteid=$musiteid\">School</a>";
if($sort=="t2.school ASC,t3.ensembletype ASC,t1.award ASC" || $sort=="t2.school DESC,t3.ensembletype ASC,t1.award ASC")
   echo "&nbsp;<a href=\"viewawardwinners.php?session=$session&sort=$cursort&musiteid=$musiteid\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
echo "</td>";
echo "<td>Student Name(s)</td>";
if($sort=="t1.award DESC,t2.school ASC,t3.ensembletype ASC")
{
   $curimg="arrowup.png"; $cursort="t1.award ASC,t2.school ASC,t3.ensembletype ASC";
}
else if($sort=="t1.award ASC,t2.school ASC,t3.ensembletype ASC")
{
   $curimg="arrowdown.png"; $cursort="t1.award DESC,t2.school ASC,t3.ensembletype ASC";
}
else
{
   $curimg=""; $cursort="t1.award ASC,t2.school ASC,t3.ensembletype ASC";
}  
echo "<td><a class=small href=\"viewawardwinners.php?session=$session&sort=$cursort&musiteid=$musiteid\">Award</a>";
if($sort=="t1.award ASC,t2.school ASC,t3.ensembletype ASC" || $sort=="t1.award DESC,t2.school ASC,t3.ensembletype ASC")
   echo "&nbsp;<a href=\"viewawardwinners.php?session=$session&sort=$cursort&musiteid=$musiteid\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
echo "</td>";
echo "<td>Edit/Delete</td>";
echo "<td>Generate Certificate</td></tr>";

   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr valign=top align=left><td>$row2[ensembletype]</td><td>$row2[school]</td><td width='450px'>$row2[studentnames]</td><td>$row2[award]</td>";
      echo "<td align=center><a href=\"awardwinners2.php?session=$session&awardid=$row2[id]\">Edit</a>&nbsp;|&nbsp;<a onClick=\"return confirm('Are you sure you want to delete this award winner?');\" href=\"viewawardwinners.php?session=$session&musiteid=$musiteid&delete=$row2[id]\">Delete</a></td>";
      if($row2[award]=="HM") $hm="yes";
      else $hm="";
      if($row2[ensembletype]=="Instrumental Solo") $event="Instrumental Solo";
      else if($row2[ensembletype]=="Vocal Solo") $event="Vocal Solo";
      else if(ereg("Vocal",$row2[ensembletype])) $event="Vocal Ensemble";
      else $event="Instrumental Ensemble";
      echo "<td><form method=post action=\"createcertificate.php\"><input type=hidden name=\"event\" value=\"$event\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"siteid\" value=\"$musiteid\">";
	echo "<input type=hidden name=\"students\" value=\"$row2[studentnames]\"><input type=hidden name=\"school\" value=\"$row2[school]\"><input type=hidden name=\"hm\" value=\"$hm\">";
      echo "<input type=submit class=fancybutton name=\"createcert\" value=\"Generate Certificate\"></form></td></tr>";
   }
   echo "</table><br><br>";
   echo "<a href=\"awardwinners.php?session=$session&musiteid=$musiteid\">Add Award Winners from the District Music Entry Form</a>&nbsp;|&nbsp;<a href=\"awardwinners2.php?session=$session&musiteid=$musiteid\">Add Award Winners Manually</a><br><br>";
}
else 
{
   echo "<table cellspacing=0 cellpadding=3 class=nine frame=all rules=all style=\"#808080 1px solid;width:700px;\">";
   echo "<caption><b>District Music Outstanding Performance/Honorable Mention Award Winners:</b><br><br>";
   $sql0="SELECT * FROM $database.mudistricts WHERE id='$musiteid'";
   $result0=mysql_query($sql0);
   $row0=mysql_fetch_array($result0);   
   echo "<b>Site:</b> ".$row0[site]."<br><br>";
   echo "<p style='text-align:left'>Please enter your NSAA District Music Contest Site Outstanding Performance or Honorable Mention Award Recipients below. There are two ways to enter award winners from your District Music Site:<ul>";
   echo "<li><a href=\"awardwinners.php?session=$session&musiteid=$musiteid\">Enter Award Winners directly from the District Music Entry Forms submitted by schools for your site</a></li><br>";
   echo "<li><a href=\"awardwinners2.php?session=$session&musiteid=$musiteid\">Enter Award Winners Manually</a></li>";
   echo "</ol></p></caption></table>";
}
}//end if site selected
echo $end_html;
?>
