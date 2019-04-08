<?php
/*********************************
allstatenomadmin.php
NSAA can manage NCPA Academic
All-State Nomination Forms
Author: Ann Gaffigan
Created: 5/21/10
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

if($resetdata==1)
{
   $sql="SELECT * FROM allstatenom";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   { 
   $student=GetStudentInfo($row[studentid]); 
   $filename1="AcademicAllStateAward".ereg_replace("[^a-zA-Z]","",$student).".pdf";
   chmod('/data/attachments/'.$filename1, 0755);
   citgf_unlink('/data/attachments/' . $filename1);
   $filename2="AcademicAllStateAwardLetter".ereg_replace("[^a-zA-Z]","",$student).".pdf";
   chmod('/data/attachments/'.$filename2, 0755);
   citgf_unlink('/data/attachments/' . $filename2);
   }
   $sql="DELETE FROM allstatenom";
   $result=mysql_query($sql);
   $sql="DELETE FROM allstatenomlocks";
   $result=mysql_query($sql);
   header("Location:allstatenomadmin.php?session=$session&resetted=1");
}

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-06-30",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

if($delete)
{
   $sql="SELECT * FROM allstatenom WHERE id='$delete'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $student=GetStudentInfo($row[studentid]); 
   $filename1="AcademicAllStateAward".ereg_replace("[^a-zA-Z]","",$student).".pdf";
   chmod('/data/attachments/'.$filename1, 0755);
   citgf_unlink('/data/attachments/' . $filename1);
   $filename2="AcademicAllStateAwardLetter".ereg_replace("[^a-zA-Z]","",$student).".pdf";
   chmod('/data/attachments/'.$filename2, 0755);
   citgf_unlink('/data/attachments/' . $filename2);
   
   $sql="DELETE FROM allstatenom WHERE id='$delete'";
   $result=mysql_query($sql);
   header("Location:allstatenomadmin.php?session=$session&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&deleted=1");
   exit();
}

if($unlock && $unlockschoolid)
{
   $sql="SELECT * FROM allstatenomlocks WHERE season='$unlockseason' AND schoolid='$unlockschoolid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="INSERT INTO allstatenomlocks (season,schoolid) VALUES ('$unlockseason','$unlockschoolid')";
      $result=mysql_query($sql);
   }
}
if($lock && $lockschoolid)
{
   $sql="DELETE FROM allstatenomlocks WHERE season='$lockseason' AND schoolid='$lockschoolid'";
   $result=mysql_query($sql);
}
if($release)	//RELEASE ALL APPROVED CERTS TO SCHOOLS
{
   $sql="SELECT * FROM allstatenom WHERE datesub>0 AND transcriptdate>0 AND confirmed>0 AND released=0";
   $result=mysql_query($sql);
   $now=time();
   while($row=mysql_fetch_array($result))
   {
      $sql2="UPDATE allstatenom SET released='$now' WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
   }
   header("Location:allstatenomadmin.php?session=$session&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&released=1");
   exit();
} 

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/AllStateNom.js"></script>
</head>
<body onLoad="AllStateNom.initialize();">
<?php
echo $header;

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"width:99%;border:#808080 1px solid;background-color:#ffffff;\">";
echo "<caption><br><h1>NCPA Academic All-State Nominations Main Menu:</h1>";
//FILTER
if($reset)
{
   unset($activitych); unset($schoolch); unset($confirmed); unset($season);
}
echo "<form method=post action=\"allstatenomadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sort\" value=\"$sort\">";
//echo "<input type=hidden name=\"offset\" value=\"$offset\" id=\"offset\">";
echo "<div class=alert style='width:400px;'><h2>FILTER SUBMITTED NOMINATIONS BY:</h2>";
echo "<ul><li><b>OPTION 1: Submitted Applications</b>";
echo "<p><b>ACTIVITY: </b><select name=\"activitych\"><option value='0'>All Activities</option>";
$sql="SELECT DISTINCT sport FROM allstatenom ORDER BY sport";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
      echo "<option value=\"$row[sport]\"";
      if($activitych==$row[sport]) echo " selected";
      echo ">".GetActivityName($row[sport])."</option>";
}
echo "</select></p>";
echo "<p><b>SCHOOL:</b> <select name=\"schoolch\"><option value='0'>All Schools</option>";
$sql="SELECT DISTINCT t1.id,t1.school FROM headers AS t1, allstatenom AS t2 WHERE t1.id=t2.schoolid ORDER BY t1.school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($schoolch==$row[id]) echo " selected";
   echo ">$row[school]</option>"; 
}
echo "</select></p>";
echo "<p><b>APPROVED FOR RELEASE:</b> <input type=radio name=\"confirmed\" value=\"yes\"";
if($confirmed=="yes") echo " checked";
echo "> Yes&nbsp;&nbsp;";
echo "<input type=radio name=\"confirmed\" value=\"no\"";
if($confirmed=="no") echo " checked";
echo "> No&nbsp;&nbsp;";
echo "<input type=radio name=\"confirmed\" value=\"either\"";
if(!$confirmed || $confirmed=="either") echo " checked";
echo "> Either</p></li><br>";
echo "<li><b>OPTION 2: Missing Applications</b>";
echo "<p>Show schools who have NOT submitted ANY nominees per activity for the...</p>";
echo "<p><input type=\"radio\" name=\"season\" value=\"Fall\"";
if($season=="Fall") echo " checked";
echo "> Fall&nbsp;&nbsp;<input type=\"radio\" name=\"season\" value=\"Winter\"";
if($season=="Winter") echo " checked";
echo "> Winter&nbsp;&nbsp;<input type=\"radio\" name=\"season\" value=\"Spring\"";
if($season=="Spring") echo " checked";
echo "> Spring</p>";
echo "<p>...Season.</p>";
echo "</li></ul>";
echo "<input type=submit name=\"filter\" value=\"Filter\">&nbsp;&nbsp;<input type=submit name=\"reset\" value=\"Reset Filter\"></form></div>";

if(!$offset) $offset=0;
if(!$sort || $sort=="") $sort="t2.datesub DESC";
$limit=25;
if($season)	//FIND SCHOOLS WHO HAVE NOT SUBMITTED NOMINEES FOR SELECTED SEASON
{
   //Get List of Schools
   $sql="SELECT * FROM headers ORDER BY school";
   $result=mysql_query($sql);
   $s=0; $schs=array(); $schids=array();
   while($row=mysql_fetch_array($result))
   {
      $schs[$s]=$row[school]; $schids[$s]=$row[id]; $s++;
   }
   //Get list of activities in this season:
   $curseason==""; $splist=array(); $s++;
   echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;'><tr valign='top'>";
   $curemails="";
   for($i=0;$i<count($allstatesp);$i++)
   {
      if($allstatesp2[$i]=="" && $allstatesp[$i]=="$season Season")	//Found our season
	 $curseason=$allstatesp[$i];
      else if($allstatesp2[$i]=="") $curseason="";
      if($curseason!='' && $allstatesp2[$i]!='')	//CHECK THIS SPORT FOR MISSING SCHOOLS
      {
	 echo "<td><p><b>$allstatesp[$i]:</b></p>";
	 for($j=0;$j<count($schs);$j++)
	 {
	    if(IsRegistered2011($schids[$j],$allstatesp2[$i]))
	    {
	       $sql="SELECT id FROM allstatenom WHERE schoolid='$schids[$j]' AND sport='$allstatesp2[$i]'";
	       $result=mysql_query($sql);
	       if(mysql_num_rows($result)==0) 
	       {
		  echo "$schs[$j]<br>";
		  //GET AD EMAIL
	          $curemails.=trim(preg_replace("/;/",", ",GetADInfo($schs[$j],TRUE))).",";
	       }
	    }	
	 }
	 echo "</td>";
      }
   }
   echo "</tr></table>";
   if($curemails!='')
   {
      $curemails=substr($curemails,0,strlen($curemails)-1);
      $curemails=explode(",",$curemails);
      $emails=array_unique($curemails);
      echo "<h3>Copy & Paste ".count($emails)." Unique AD E-mails:</h3>";
      $emails=implode(", ",$emails);
      echo "<textarea style=\"font-size:12px;width:800px;height:200px;\">$emails</textarea>";
   }
}
else
{
   $sql="SELECT DISTINCT t1.*,t2.datesub,t2.transcript,t2.transcriptdate,t2.opened,t2.confirmed,t2.released,t2.id AS nomid,t2.sport FROM eligibility AS t1,allstatenom AS t2 WHERE t1.id=t2.studentid";
   if($schoolch) 
   {
      if($activitych)
      {
	 $sql2="SELECT * FROM headers WHERE id='$schoolch'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $curschool=$row2[school];
	 if(ereg("cc",$activitych)) $cursport="cc";
	 else if(ereg("sw",$activitych)) $cursport='sw';
	 else if(ereg("_",$activitych) && !ereg("go",$activitych) && !ereg("te",$activitych))
	    $cursport=ereg_replace("_","",$activitych);
	 else $cursport=$activitych;
         $cursid=GetSID2($curschool,$cursport);
	 if($cursid>0)
	 {
	    $sql2="SELECT * FROM ".$cursport."school WHERE sid='$cursid'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $sql.=" AND (t2.schoolid='$row2[mainsch]' OR ";
	    if($row2[othersch1]>0) $sql.="t2.schoolid='$row2[othersch1]' OR ";
       	    if($row2[othersch2]>0) $sql.="t2.schoolid='$row2[othersch2]' OR ";
	    if($row2[othersch3]>0) $sql.="t2.schoolid='$row2[othersch3]' OR ";
	    $sql=substr($sql,0,strlen($sql)-4).")";
	 }
	 else $sql.=" AND t2.schoolid='$schoolch'";
      }
      else
         $sql.=" AND t2.schoolid='$schoolch'";
   }
   if($activitych) $sql.=" AND t2.sport='$activitych'";
   if($confirmed=='yes') $sql.=" AND t2.confirmed>0";
   else if($confirmed=='no') $sql.=" AND t2.confirmed=0";
   $sql.=" ORDER BY $sort";
   $result=mysql_query($sql);

   if(mysql_num_rows($result)>0)
   {
      echo "<table cellspacing=0 cellpadding=5><tr valign=top><td width='50%'>";
  	/****** EXPORT AWARD WINNERS ******/
      echo "<div class='normalwhite' style='font-size:12px;padding:0px;width:435px;'><p style=\"margin:5px;\"><b>Exports:</b></p><ol>";
      echo "<li><b>One-Column, Approved and Released Award Winners:</b><br><ul>";
	echo "<li><a href=\"allstatenomreport.php?session=$session&released=1&season=fall\" target=\"_blank\">Fall</a> (<a class=small href=\"allstatenomreport.php?session=$session&season=fall&released=0\" target=\"_blank\">Preview before Releasing Winners</a>)</li>";
	echo "<li><a href=\"allstatenomreport.php?session=$session&released=1&season=winter\" target=\"_blank\">Winter</a> (<a class=small href=\"allstatenomreport.php?session=$session&season=winter&released=0\" target=\"_blank\">Preview before Releasing Winners</a>)</li>";
	echo "<li><a href=\"allstatenomreport.php?session=$session&released=1&season=spring\" target=\"_blank\">Spring</a> (<a class=small href=\"allstatenomreport.php?session=$session&season=spring&released=0\" target=\"_blank\">Preview before Releasing Winners</a>)</li></ul>";
	echo "<p>(You will copy the single column of data and paste it into a document to create the PDF report of winners for the NSAA Website Awards Page, the Bulletin or for archiving purposes. Clicking the links above does NOT automatically publish anything to the NSAA website.)</p>";
      echo "</li><br>";
      echo "<li><a href=\"allstatenomexport.php?session=$session&released=1\">Excel: ALL Approved and Released Award Winners</a><ul>";
      echo "<li><a href=\"allstatenomexport.php?session=$session&released=0\">PREVIEW Excel Export</a><br>(Will export all Approved but not necessarily Released Award Winners)</li></ul></li>";
      echo "</ol></div><br />";

	/****** EDIT AWARD LETTER ******/
      echo "<div class='normalwhite' style='font-size:12px;padding:0px;width:435px;'><ul><li><a href=\"allstatenomletteradmin.php?session=$session\">Edit the Academic All-State LETTER</a></li></ul></div>";

        /****** RESET FOR NEW YEAR ******/
      echo "<br /><div class='normalwhite' style='font-size:12px;padding:10px;width:415px;'><h3>Reset Data for New School Year:</h3><p><b>PLEASE NOTE:</b> After June 30 each year, if you have not reset the data for the new school year yet, this screen may show incomplete nomination forms, as the eligibility list is archived on May 31. This page only refers to the archived eligibility lists through the end of June before looking ahead to the new school year's eligibility list.</p><ul><li><a href=\"allstatenomadmin.php?resetdata=1&session=$session\" onClick=\"return confirm('Are you sure you want to clear out all Academic All-State Nomination data?');\">Clear out ALL Academic All-State data (for new year)</a></li></ul>";
      if($resetted==1) echo "<div class='alert' style=\"width:auto;\">The data has been reset.</div>";
      echo "</div>";

	echo "</td><td>";

        /****** RELEASE NOMINATIONS ******/
      echo "<div class='normalwhite' style='font-size:9pt;padding:5px;width:450px;'>";
      echo "<form method='post' action='allstatenomadmin.php'>";
      echo "<input type=hidden name=\"session\" value=\"$session\">";
      echo "<input type=hidden name=\"sort\" value=\"$sort\">";
      echo "<p><b>Releasing Certificates to Schools:</b></p>";
      if($released)         
         echo "<div class=alert style='width:350px;text-align:center;'><i>All approved certificates have been released to the schools.</i></div>";
      if($deleted)
	 echo "<div class=alert style='width:350px;text-align:center;'><i>The nomination form has been deleted.</i></div>";
      echo "<p>When a nomination has been properly submitted with a transcript, you can <b>approve the certificate for release</b> by checking the box in the far right column for that nomination's record.</p><p>When you are ready to <b>release ALL approved certificates</b> to the schools, click the button below.</p>";
      echo "<input type=submit name='release' class=fancybutton2 value='Release All Approved Certificates' onClick=\"return confirm('Are you sure you want to release ALL approved certificates?');\">";
      echo "<div style='clear:both;'></div>";
      echo "</form></div>";

        /****** UNLOCK NOMINATIONS FOR LATE SCHOOLS ******/
      echo "<div class='normalwhite' style='font-size:9pt;margin-top:5px;padding:5px;width:450px;'>";
      echo "<form method='post' action='allstatenomadmin.php'>";
      echo "<input type=hidden name=\"session\" value=\"$session\">";
      echo "<input type=hidden name=\"sort\" value=\"$sort\">";
      echo "<p><b>Oh no! I forgot to nominate my students!</b></p>";
      if($unlock)
         echo "<div class=alert style='width:350px;text-align:center;'><i>The school can now login and submit nominations.</i></div>";
      if($lock)
         echo "<div class=alert style='width:350px;text-align:center;'><i>The school's nomination forms are now locked again.</i></div>";
      echo "<p>If a school needs to submit late nominations for students, please select which season and which school to unlock below and then click \"Unlock Nominations.\"</p>";
      echo "<p>Unlock <select name=\"unlockseason\">";
      $sql2="SELECT * FROM misc_duedates where sport LIKE 'allstatenom%' ORDER BY duedate";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $season=ereg_replace("allstatenom_","",$row2[sport]);
	 echo "<option value=\"$season\">".strtoupper($season)."</option>";
      }
      echo "</select> nominations for <select name=\"unlockschoolid\"><option value='0'>Select School</option>";
      $sql2="SELECT * FROM headers ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\">$row2[school]</option>";
      }
      echo "</select>.</p>";
      echo "<input type=submit name='unlock' class=fancybutton2 value='Unlock Nominations'>";
      $sql2="SELECT t1.school,t2.* FROM headers AS t1, allstatenomlocks AS t2 WHERE t1.id=t2.schoolid ORDER BY t2.season,t1.school";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         echo "<div style='clear:both;'></div><br><p><b>Currently UNLOCKED Nominations:</b></p><ul>";
         while($row2=mysql_fetch_array($result2))
         {
            echo "<li>".strtoupper($row2[season]).": $row2[school]  <a class=small href=\"allstatenomadmin.php?lock=1&lockschoolid=$row2[schoolid]&lockseason=$row2[season]&session=$session&sort=$sort&activitych=$activitych&schoolch=$schoolch&offset=$offset\">LOCK</a></li>";
         }
         echo "</ul>";
      }
      echo "</form></div>";

   	echo "</td></tr></table>";

        /***** NAVIGATION *****/
      echo "<div class=alert style='width:700px;'>";
      if(mysql_num_rows($result)>25)
      {
         echo "<table width='100%'><tr align=center>";
         if($offset>0)
         {
            $prevoffset=$offset-25;
            echo "<td align=left width='15%'><a class=small href=\"allstatenomadmin.php?session=$session&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&offset=$prevoffset\"><img border=0 src=\"arrowleft.png\" style='width:12px;margin:5px 5px 0px 0px;'>Previous</a></td>";
         }
         else echo "<td width='15%'>&nbsp;</td>";
         $start=$offset+1; $end=$offset+$limit;
         $total=mysql_num_rows($result);
         if($end>$total) $end=$total;
         echo "<td align=center>Showing $start-$end of $total Results</td>";
         if(($offset+25)<$total)
         {
            $nextoffset=$offset+25;
            echo "<td align=right width='15%'><a class=small href=\"allstatenomadmin.php?session=$session&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&offset=$nextoffset\">Next<img border=0 src=\"arrowright.png\" style='width:12px;margin:5px 0px 0px 5px;'></a></td>";
         }
         else echo "<td width='15%'>&nbsp;</td>";
         echo "</tr></table>";
      }
      else
      {
         echo mysql_num_rows($result)." nominations have been submitted.";
      }
      $sql.=" LIMIT $offset,$limit";
      $result=mysql_query($sql);
      echo "</div><br>";
      echo "</caption>";
      echo "<tr align=center>";
      if($sort=="t2.sport DESC")
      {
         $curimg="arrowup.png"; $cursort="t2.sport ASC";
      }
      else if($sort=="t2.sport ASC")
      {
         $curimg="arrowdown.png"; $cursort="t2.sport DESC";
      }
      else
      {
         $curimg=""; $cursort="t2.sport ASC";
      }
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">Activity</a>";
      if(ereg("t2.sport",$sort))
         echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
      echo "</td>";
      if($sort=="t1.school DESC")
      {
         $curimg="arrowup.png"; $cursort="t1.school ASC";
      }
      else if($sort=="t1.school ASC")
      {
         $curimg="arrowdown.png"; $cursort="t1.school DESC";
      }
      else
      {         $curimg=""; $cursort="t1.school ASC";
      }
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">School</a>";
      if(ereg("t1.school",$sort))
         echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
      echo "</td>";
      if($sort=="t1.last DESC,t1.first DESC")
      {
         $curimg="arrowup.png"; $cursort="t1.last ASC,t1.first ASC";
      }
      else if($sort=="t1.last ASC,t1.first ASC")
      {
         $curimg="arrowdown.png"; $cursort="t1.last DESC,t1.first DESC";
      }
      else
      {         $curimg=""; $cursort="t1.last ASC,t1.first ASC";
      }
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">Nominee</a>";
      if(ereg("t1.last",$sort))
         echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
      echo "</td>";
      if($sort=="t2.datesub DESC")
      {
         $curimg="arrowup.png"; $cursort="t2.datesub ASC";
      }
      else if($sort=="t2.datesub ASC")
      {
         $curimg="arrowdown.png"; $cursort="t2.datesub DESC";
      }
      else
      {         
         $curimg=""; $cursort="t2.datesub ASC";
      }
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">Nomination Form</a><br>(Order by date submitted)";
      if(ereg("t2.datesub",$sort))
         echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
      echo "</td>";
      if($sort=="t2.transcriptdate DESC")
      {
         $curimg="arrowup.png"; $cursort="t2.transcriptdate ASC";
      }
      else if($sort=="t2.transcriptdate ASC")
      {
         $curimg="arrowdown.png"; $cursort="t2.transcriptdate DESC";
      }
      else
      {
         $curimg=""; $cursort="t2.transcriptdate ASC";
      }     
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">Transcript</a><br>(Order by Date Submitted)";
      if(ereg("t2.transcriptdate",$sort))
         echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
      echo "</td>";
      if($sort=="t2.opened DESC")
      {
         $curimg="arrowup.png"; $cursort="t2.opened ASC";
      }
      else if($sort=="t2.opened ASC")
      {
         $curimg="arrowdown.png"; $cursort="t2.opened DESC";
      }
      else
      {         
	 $curimg=""; $cursort="t2.opened ASC";
      }
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">Open for Editing</a>";
      if(ereg("t2.opened",$sort))
         echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";
      echo "</td>";
      if($sort=="t2.confirmed DESC,t2.released DESC")
      {
         $curimg="arrowup.png"; $cursort="t2.confirmed ASC,t2.released ASC";
      }
      else if($sort=="t2.confirmed ASC,t2.released ASC")            
      {
         $curimg="arrowdown.png"; $cursort="t2.confirmed DESC,t2.released DESC";
      }  
      else      
      {         
         $curimg=""; $cursort="t2.confirmed ASC,t2.released ASC";      
      }            
      echo "<td><a class=small href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\">Released for<br>Award Cert Priting</a>";
      if(ereg("t2.released",$sort))         
	 echo "&nbsp;<a href=\"allstatenomadmin.php?session=$session&sort=$cursort&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch\"><img border=0 src=\"/nsaaforms/$curimg\" width=15></a>";      
      echo "</td>";
      //echo "<td><b>Action</b><br>(Check a box to complete the action)</td></tr>";
      echo "</tr>";
	$i=0;
      while($row=mysql_fetch_array($result))
      {
         $schname=GetSchoolName(GetSID2($row[school],$row[sport]),$row[sport],date("Y"));
	 if($schname=='') $schname=$row[school]; //"Test's School";
         echo "<tr align=left><td>".GetActivityName($row[sport])."</td><td>$schname</td><td>$row[first] $row[last]";
         if(IsInCoop(GetSchoolID2($row[school]),$row[sport]))
            echo "<br>($row[school])";
         echo "</td>";
         echo "<td";
         if($row[datesub]==0)
       	    echo " bgcolor='#ff0000'>NOT SUBMITTED<br>";
         else
 	    echo "><b>RECEIVED</b><br>".date("m/d/y",$row[datesub])." at ".date("g:ia",$row[datesub])."<br><a class=small href=\"allstatenom.php?session=$session&nomid=$row[nomid]\" target=\"_blank\">View</a>&nbsp;|&nbsp;";
         echo "<a class=small href=\"allstatenom.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session&nomid=$row[nomid]&edit=1\">Edit</a>&nbsp;|&nbsp;";
	 echo "<a class=small href=\"allstatenomadmin.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session&delete=$row[nomid]\" onClick=\"return confirm('Are you sure you want to DELETE this nomination form? This action cannot be undone.');\">Delete</a>";
	 echo "</td><td";
	 if($row[transcript]=='')
	    echo " bgcolor=\"#ff0000\">NOT RECEIVED<br><a class=white href=\"allstatenom.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session&nomid=$row[nomid]&step=2\">Submit Transcript</a>";
         else if(citgf_file_exists("/home/nsaahome/attachments/$row[transcript]"))
	    echo "><b>UPLOADED</b><br>".date("m/d/y",$row[transcriptdate])." at ".date("g:ia",$row[transcriptdate])."<br><a href=\"attachments.php?session=$session&filename=$row[transcript]\" class=small>Download Transcript</a>";
         else
	    echo "><b>RECEIVED</b><br>".date("m/d/y",$row[transcriptdate])." at ".date("g:ia",$row[transcriptdate]);
         if($row[transcript]!='')
	    echo "<br><a class=small href=\"allstatenom.php?confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&session=$session&nomid=$row[nomid]&step=2\">Edit Transcript</a>";
	 echo "</td>";
	 //OPENED
   	 echo "<td>";
         if($row[opened]==0) echo "<b>LOCKED</b>";
	 else echo "<b>OPENED</b><br>".date("m/d/y",$row[opened])." at ".date("g:ia",$row[opened]);
         echo "<br><input type=checkbox onClick=\"AllStateNom.updateNom('opened".$i."',$row[nomid]);\" name=\"opened[$i]\" id=\"opened".$i."\" value=\"x\"";
         if($row[opened]>0) echo " checked";
         echo "> Re-open for School to Edit<div class='normalwhite' style='display:none' id='opened".$i."response'></div><br>";
	 echo "</td><td>";
         //RELEASED
         if($row[datesub]>0)
            echo "<a href=\"allstatenomcert.php?session=$session&nomid=$row[nomid]\" class=small>View PDF Certificate</a>&nbsp;|&nbsp;<a href=\"allstatenomletter.php?session=$session&nomid=$row[nomid]\" class=small>View PDF Letter</a><br>";
	 if($row[released]==0) echo "<b>NOT YET RELEASED</b>";
	 else echo "<label style='background-color:#00ff00;'><b>RELEASED</b> ".date("m/d/y",$row[released])." at ".date("g:ia",$row[released])."</label>";
         echo "<br><input type=checkbox onClick=\"AllStateNom.updateNom('confirmed".$i."',$row[nomid]);\" name=\"confirmed[$i]\" id=\"confirmed".$i."\" value=\"x\"";
         if($row[confirmed]>0 || $row[released]>0) echo " checked";
	 if($row[datesub]==0 || $row[transcript]=='') echo " disabled";
         echo "> Approve for Release to School<div class='normalwhite' style='display:none' id='confirmed".$i."response'></div>";
	 echo "</td>";
	 echo "</tr>";
	 $i++;
      }
   }
   else 
   {
      echo "<br><br>[No nominations have been submitted.]<br><br>";

        /****** EDIT AWARD LETTER ******/
      echo "<div class='normalwhite' style='font-size:12px;padding:0px;width:435px;'><ul><li><a href=\"allstatenomletteradmin.php?session=$session\">Edit the Academic All-State LETTER</a></li></ul></div>";
      echo "</caption>";
   }

echo "</table>";
   $sql="SELECT DISTINCT t1.*,t2.datesub,t2.transcript,t2.transcriptdate,t2.opened,t2.confirmed,t2.released,t2.id AS nomid,t2.sport FROM eligibility AS t1,allstatenom AS t2 WHERE t1.id=t2.studentid";
   $sql.=" ORDER BY $sort";
   $result=mysql_query($sql);

   if(mysql_num_rows($result)>0)
   {
      echo "<div class=alert style='width:700px;'>";
      if(mysql_num_rows($result)>25)
      {
         echo "<table width='100%'><tr align=center>";
         if($offset>0)
         {
            $prevoffset=$offset-25;
            echo "<td align=left width='15%'><a class=small href=\"allstatenomadmin.php?session=$session&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&offset=$prevoffset\"><img border=0 src=\"arrowleft.png\" style='width:12px;margin:5px 5px 0px 0px;'>Previous</a></td>";
         }
         else echo "<td width='15%'>&nbsp;</td>";
         $start=$offset+1; $end=$offset+$limit;
         $total=mysql_num_rows($result);
         if($end>$total) $end=$total;
         echo "<td align=center>Showing $start-$end of $total Results</td>";
         if(($offset+25)<$total)
         {
            $nextoffset=$offset+25;
            echo "<td align=right width='15%'><a class=small href=\"allstatenomadmin.php?session=$session&confirmed=$confirmed&activitych=$activitych&schoolch=$schoolch&sort=$sort&offset=$nextoffset\">Next<img border=0 src=\"arrowright.png\" style='width:12px;margin:5px 0px 0px 5px;'></a></td>";
         }
         else echo "<td width='15%'>&nbsp;</td>";
         echo "</tr></table>";
      }
      else
      {
         echo mysql_num_rows($result)." nominations have been submitted.";
      }
      $sql.=" LIMIT $offset,$limit";
      $result=mysql_query($sql);
      echo "</div>";
   }
} //END IF FILTER WAS FOR SUBMITTED FORMS
echo "<div id='loading' style='display:none;'></div>";

echo $end_html;
?>
