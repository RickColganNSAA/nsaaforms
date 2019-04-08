<?php
/*******************************************
prelimresults.php
PUBLIC WEBSITE RESULTS PAGE
(Admin can preview with $session)
Created 1/14/13
Author: Ann Gaffigan
*******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($year=="" || !$year) $database=$db_name;
else $database=GetDatabase($year);
$sql="USE $database";
$result=mysql_query($sql);

echo $init_html."<table class='nine' width='100%'><tr align=center><td>";

if($catid=="full")
{
   if($session && ValidUser($session) && GetLevel($session)==1) 
   {
      $public=FALSE;
      echo "<p style='color:#ff0000;'><b>You are logged in as the NSAA. The following information is hidden from the public.</b> <a class=small href=\"prelimresults.php?catid=$catid\">See what the public sees</a></p>";
   }
   else 
   {
      $public=TRUE;
   }
   echo "<div style=\"width:511pt;\">";
   echo GetJOFullResults($public,$year);
   echo "</div>";
   echo $end_html;
   exit();
}
else if($catid=="team")	//TEAM RESULTS
{
   if($session && ValidUser($session) && GetLevel($session)==1)
   {
      $public=FALSE;
      echo "<p style='color:#ff0000;'><b>You are logged in as the NSAA. The following information is hidden from the public.</b> <a class=small href=\"prelimresults.php?catid=$catid\">See what the public sees</a></p>";
   }
   else
   {
      $public=TRUE;
   }
   //echo "<h3>".date("Y")." State Journalism Sweepstakes Results:</h3>".GetJOTeamResults($public,$year);
   echo $end_html;
   exit();
}

echo "<form method='post' action='prelimresults.php'>";
echo "<input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"year\" value=\"$year\">";
echo "<p><b>Select a Category: </b>";
echo "<select name=\"catid\" onChange=\"submit();\"><option value=''>Select Category</option>";
$sql="SELECT DISTINCT t1.id,t1.category FROM jocategories AS t1,joassignments AS t2 WHERE t1.id=t2.catid ORDER BY t2.datesub DESC,t1.category";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($catid==$row[id]) echo " selected";
   echo ">$row[category]";
   echo "</option>";
}
echo "</select></p><form>";

echo "<h2>NSAA Journalism Contest  - Preliminary Results: <u>".GetJOCategory($catid)."</u></h2>";
if(!$catid)
{
   echo "<p><i>Please select a category above.</i></p>";
   echo $end_html;
   exit();
}
echo "<table cellspacing=0 cellpadding=5 class='nine' style=\"width:700px;\">";

   //CHECK IF APPROVED FOR WEBSITE (LET THEM TOGGLE APPROVED/UNAPPROVED)
$sql="SELECT * FROM jocategories WHERE id='$catid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$showplace=$row['showplace'];
if($row[webapproved]==0 && (!ValidUser($session) || GetLevel($session)!=1))
{
   $public=TRUE;
   echo "<caption><br><p>Information not available at this time.</p></caption>";
   echo "</table>";
   echo $end_html;
   exit();
}
else if($row[webapproved]==0)
{
   $public=FALSE;
   echo "<caption><p style='color:#ff0000;'><b>You are logged in as the NSAA. The following information is hidden from the public.</b> <a class=small href=\"prelimresults.php?catid=$catid\">See what the public sees</a></p>";
}

//IF WE GET HERE, WE CAN SHOW THE INFO

//Get State JO date
echo "<tr align=left><td colspan=2>";
if(!$showplace)
   echo "<p>This list does not indicate final placement. Final placements will be announced at the award ceremony.</p>";
//echo "<p>The class champion listed as 1st place under each class below advances to the State Journalism Championships at Northeast Community College, Norfolk.</p>";
   //FOR EACH CLASS -- SHOW TOP 12 AND SUBMISSIONS. 
   $sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   $percol=ceil(mysql_num_rows($result)/2);
   $i=0;
   echo "<tr align=left valign=top><td width='50%'>";
   $points=array(); //$points[class][sid]=points for that team
   while($row=mysql_fetch_array($result))
   {
      if($i==$percol)
         echo "</td><td>";
      echo "<p><b>CLASS $row[class]:</b></p>"; 
      $curclass=$row['class']; $points[$curclass]=array();
      $sql2="SELECT t1.* FROM joentries AS t1,joschool AS t2 WHERE t1.sid=t2.sid AND t1.catid='$catid' AND t2.class='$row[class]'";
      $sql2.=" AND t1.studentid>0 AND t1.classrank>0 AND t1.classrank<=12 ORDER BY t1.classrank";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 if($showplace==1) echo "<p>$row2[classrank].&nbsp;";
	 else echo "<p>";
	 echo GetStudentInfo($row2[studentid],FALSE).", ";
	 for($j=2;$j<=6;$j++)
	 {
	    $var="studentid".$j;
	    if($row2[$var]>0) echo GetStudentInfo($row2[$var],FALSE).", ";
         }
	 echo GetSchoolName($row2[sid],'jo')."</p>";
     $file_url=strpos($row2[filename],'http')===0?$row2[filename]:"/nsaaforms/downloads/".$row2[filename];
     echo "<p style='padding-left:20px;'><a class='small' href='$file_url' target=\"_blank\">$row2[label]</a></p>";
     if($row2[filename2]!=''){
        $file2_url=strpos($row2[filename2],'http')===0?$row2[filename2]:"/nsaaforms/downloads/".$row2[filename2];
        echo "<p style=\"padding-left:20px;\"><a href='$file2_url' target=\"_blank\">$row2[label2]</a></p>";
     }
     if($row2[filename3]!=''){
        $file3_url=strpos($row2[filename3],'http')===0?$row2[filename3]:"/nsaaforms/downloads/".$row2[filename3];
        echo "<p style=\"padding-left:20px;\"><a href='$file3_url' target=\"_blank\">$row2[label3]</a></p>";
     }
         if($row2[classrank]==1) $pts=3;
	 else if($row2[classrank]==2) $pts=2;
	 else $pts=1;
         $points[$curclass][$row2[sid]]+=$pts;
		//echo GetSchoolName($row2[sid],'jo')." - $pts Points ".$points[$curclass][$row2[sid]]."<br>";
      }
      echo "<br>";
      $i++;    
   }	//END FOR EACH CLASS
   //TOP 15 (12 + ALTERNATES) OVERALL:
   echo "</td></tr>";
	/*
   echo "<tr align=left><td colspan=2>";
   echo "<p><b>TOP 12 OVERALL:</b></p>";
   echo "<p>Top-12 students plus class champions not in the top-12 qualify for the State Journalism Championships at the University of Nebraska-Lincoln. In-depth News Coverage, Graphic Illustrations, Sports/Action Photography and Photo Illustration are all finals. The top-six place winners in those categories will be honored with medals at the awards ceremony at the UNL Student Union.</p>";
   $sql2="SELECT t1.*,t3.orderby FROM joentries AS t1,joschool AS t2,joqualifiers AS t3 WHERE t1.id=t3.entryid AND t1.sid=t2.sid AND t1.catid='$catid' AND t1.studentid>0";
   //$sql2.=" AND t1.overallrank>0 AND t1.overallrank<=15 ORDER BY t1.overallrank";
   $sql2.=" ORDER BY t3.orderby";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[orderby]==13)	//START ALTERNATES
         echo "<br><p><u><b>Alternates:</b></u></p>";
      echo "<p>";
      if($row2[orderby]<=12)
         echo "$row2[orderby]. ";
      echo GetStudentInfo($row2[studentid],FALSE).", ";
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
   echo "</td></tr>";
	*/
echo "</table>";

//TEAM RESULTS
//echo GetJOTeamResults($public);

echo $end_html;

$sql="USE $db_name";
$result=mysql_query($sql);
?>
