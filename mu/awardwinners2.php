<?php
/****************************************
awardwinners2.php
Manually add District Music Award 
Winners to muawardwinners Table
Created 11/3/10
Author: Ann Gaffigan
*****************************************/
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

if(!$musiteid) $musiteid=GetMusicSiteID($schoolid,$loginid);
//verify user
if($musiteid==0 && $level!=1)
{
   header("Location:../index.php");
   exit();
}
if($level==1 && $awardid)
{
   $sql="SELECT * FROM $database.muawardwinners WHERE id='$awardid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $musiteid=$row[distid];
}

if($save)
{
   if($muensembletypesid==0 || $muschoolsid==0 || trim($studentnames)=="" || !$award)
   {
      $error=1;
   }
   else
   {
      if($awardid)
         $sql="UPDATE $database.muawardwinners SET distid='$musiteid',muensembletypesid='$muensembletypesid',muschoolsid='$muschoolsid',studentnames='".addslashes($studentnames)."',award='$award' WHERE id='$awardid'";
      else
         $sql="INSERT INTO $database.muawardwinners (distid,muensembletypesid,muschoolsid,studentnames,award) VALUES ('$musiteid','$muensembletypesid','$muschoolsid','".addslashes($studentnames)."','$award')";
      $result=mysql_query($sql);
      $error=0; 

      echo $init_html;
      echo $header."<br>";
      echo "<div class=alert style='width:500px'><p>The award winners have been successfully";
      if($awardid) echo " updated";
      else echo " added";
      echo ".</p><p style='text-align:center;'><a href=\"awardwinners2.php?musiteid=$musiteid&session=$session\">Enter Another Award Winner</a>&nbsp;|&nbsp;<a href=\"viewawardwinners.php?session=$session&musiteid=$musiteid\">View your Complete List of Award Winners</a></p></div>";
      echo $end_html;
      exit();
   }
}

echo $init_html;
echo $header."<br>";

echo "<form method=post action='awardwinners2.php'>";
echo "<input type=hidden name='session' value='$session'>";
echo "<input type=hidden name='musiteid' value='$musiteid'>";
echo "<input type=hidden name='awardid' value='$awardid'>";
echo "<table cellspacing=0 cellpadding=5 class=nine>";
echo "<caption><b>District Music Outstanding Performance/Honorable Mention Award Winners:</b><br>(Manual Entry)<br>";
echo "<div class='alert' style='padding:10px;font-size:9pt;width:500px;'><b>INSTRUCTIONS:</b>";
if($awardid)
{
   echo "<br><br>Make any necessary changes to the award-winning ensemble or solo below and click \"Save.\"<br><br>";
}
else
{
   echo "<ol>";
   echo "<li><b>Select a solo/ensemble category</b> from the dropdown list below.</li>";
   echo "<li><b>Select the school</b> from the list of schools that competed at your site.</li>";
   echo "<li><b>Enter the name(s) of the student(s)</b> receiving an award.</li>";
   echo "<li><b>Indicate the type of award</b> the students should receive.</li>";
   echo "<li><b>Click \"Save\"</b> at the bottom of this page to add the award winner(s) to your site's list.</li>";
   echo "</ol><br>";
}
	//GET DUE DATE
        $sql2="SELECT * FROM muawardsduedate";        
	$result2=mysql_query($sql2);        
	$row2=mysql_fetch_array($result2);
        $date=split("-",$row2[duedate]);
echo "At any time, you can <a href=\"viewawardwinners.php?session=$session&musiteid=$musiteid\">Preview the Complete List of Award Winners</a> for your site.<br><br>The due date for this form is <b><u>$date[1]/$date[2]/$date[0]</b></u>. As of midnight on that date, your list will be considered COMPLETE by the NSAA.";
echo "</div>";
if($error==1)
   echo "<div class=error>One or more of the fields of information below are incomplete. Please complete ALL fields and hit Save.</div>";
echo "</caption>";

if(!$error && $awardid)
{
   $sql0="SELECT * FROM $database.muawardwinners WHERE id='$awardid'";
   $result0=mysql_query($sql0);
   $row0=mysql_fetch_array($result0);
   $muensembletypesid=$row0[muensembletypesid];
   $muschoolsid=$row0[muschoolsid];
   $studentnames=$row0[studentnames];
   $award=$row0[award];
}

   echo "<tr align=left><td><b>Select Solo/Ensemble Type:</b></td><td><select name=\"muensembletypesid\" id=\"muensembletypesid\"><option value=\"0\">Select Solo/Ensemble Type</option>";
   $sql="SELECT * FROM $database.muensembletypes ORDER BY id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($row[id]==$muensembletypesid) echo " selected";
      echo ">$row[ensembletype]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td><b>School:</b></td><td><select name=\"muschoolsid\" id=\"muschoolsid\"><option value=\"0\">Select School</option>";
   $sql="SELECT t1.* FROM $database.muschools AS t1,$database.mudistricts AS t2 WHERE t1.distid=t2.id AND (t1.distid='$musiteid' OR t2.distid1='$musiteid' OR t2.distid2='$musiteid') ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($row[id]==$muschoolsid) echo " selected";
      echo ">$row[school]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr valign=top align=left><td><b>Student Name(s):</b></td>";
   echo "<td><textarea name=\"studentnames\" id=\"studentnames\" rows=5 cols=50>$studentnames</textarea></td></tr>";
   echo "<tr align=left valign=top><td><b>Type of Award:</b></td>";
   echo "<td><input type=radio name=\"award\" value=\"OP\"";
   if($award=="OP") echo " checked";
   echo "> Outstanding Performance Award<br>";
   echo "<input type=radio name=\"award\" value=\"HM\"";
   if($award=="HM") echo " checked";
   echo "> Honorable Mention</td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit class=fancybutton name=\"save\" value=\"Save\"></td></tr>";

echo "</table>";
echo "</form>";
echo "<a href=\"../welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
