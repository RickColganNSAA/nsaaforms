<?php
/*******************************************
previewresults.php
NSAA User can preview and approve results
for public jo.php page
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

if($approve)
{
    $sql="UPDATE jocategories SET webapproved='$webapproved' WHERE id='$catid'";
    $result=mysql_query($sql);
}

if($unlockid>0)
{
    $sql="UPDATE joassignments SET datesub=0 WHERE id='$unlockid'";
    $result=mysql_query($sql);
}

echo $init_html;
echo $header;

echo "<br><p><a href=\"stateadmin.php?session=$session\">Return to JO Contest Main Menu</a></p>";

echo "<h2>NSAA Journalism Contest  - Preliminary Results</h2>";
echo "<form method=post action=\"previewresults.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<table cellspacing=0 cellpadding=5 class='nine' style=\"width:700px;\">";
echo "<caption>";
echo "<p><b>Select an Event to View & Approve Results for the NSAA Website: </b>";
echo "<select name=\"catid\" onChange=\"submit();\"><option value=''>Select Event OR Team Results OR Full Results</option>";
/*
echo "<option value='team'";
if($catid=="team") echo " selected";
echo ">TEAM RESULTS/SWEEPSTAKES</option>";
*/
echo "<option value='full'";
if($catid=="full") echo " selected";
echo ">FULL PRELIMINARY RESULTS</option>";
$sql="SELECT * FROM jocategories ORDER BY category";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    echo "<option value=\"$row[id]\"";
    if($catid==$row[id]) echo " selected";
    echo ">$row[category]";
    echo "</option>";
}
echo "</select></p>";
echo "</caption>";
if($catid=="team")	//TEAM RESULTS ONLY
{
    /*
   echo "<tr align=left><td colspan=2>";
   echo "<div class='alert'>";
   echo "<p>The <b>SWEEPSTAKES (TEAM) RESULTS </b>will not show on the public website until ALL CATEGORIES have been approved for the public website.</p>";
   echo "<p><a href=\"prelimresults.php?session=$session&catid=$catid\" target=\"_blank\">Preview the Results on NSAA Website</a></p>";
   echo "</div>";
   echo "</td></tr>";
   echo "<tr align=center><td colspan=2>".GetJOTeamResults()."</td></tr>";
    */
}
else if($catid=="full")	//SHOW FULL RESULTS IN FORM USED AT https://nsaahome.org/textfile/journ/jprelims.pdf
{
    echo "<tr align=left><td colspan=2>";
    echo "<div class='alert'>";
    echo "<p>Only the categories that have been <b><u>APPROVED</b></u> for the public website will show up on the public screen containing the information below.</p>";
    echo "<p><a href=\"prelimresults.php?session=$session&catid=$catid\" target=\"_blank\">Preview the Full Results on NSAA Website</a></p>";
    echo "</div>";
    echo "</td></tr>";
    echo "<tr align=center><td colspan=2>".GetJOFullResults()."</td></tr>";
}
else if($catid && $catid!='')
{
    echo "<tr align=center><td colspan=2><br><h3>Preliminary Results: ".GetJOCategory($catid)."</h3>";

    //CHECK IF APPROVED FOR WEBSITE (LET THEM TOGGLE APPROVED/UNAPPROVED)
    echo "<div class='alert'>";
    //$sql="SELECT * FROM jocategories WHERE id='$catid'";
    $sql="SELECT t1.*,t2.id AS assignid,t2.datesub FROM jocategories AS t1,joassignments AS t2 WHERE t1.id=t2.catid AND t2.catid='$catid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $showplace=$row[showplace];
    if($row[webapproved]==0)	//NOT APPROVED
    {
        echo "<p>The following information is currently <b><u>NOT APPROVED</b></u> for the NSAA public website.</p>";
        echo "<p><a href=\"prelimresults.php?session=$session&catid=$catid\" target=\"_blank\">Preview ".GetJOCategory($catid)." Results on NSAA Website</a></p>";
        echo "<p><input type=checkbox name=\"webapproved\" value=\"".time()."\"> Check here to <b><u>APPROVE</b></u> this information for the NSAA website.";
        echo "&nbsp;&nbsp;<input type=submit name='approve' value='Save Checkmark'>";
    }
    else	//APPROVED
    {
        echo "<p>The following information is currently <b><u>APPROVED AND VISIBLE</b></u> on the NSAA public website.</p>";
        echo "<p><a href=\"prelimresults.php?session=$session&catid=$catid\" target=\"_blank\">Preview ".GetJOCategory($catid)." Results on NSAA Website</a></p>";
        echo "<p><input type=checkbox name=\"webapproved\" value=\"".time()."\" checked> Un-check this box to <b><u>REMOVE</b></u> this information from the NSAA website.";
        echo "&nbsp;&nbsp;<input type=submit name='approve' value='Save Checkmark'>";
    }
    echo "</div>";
    echo "</td></tr>";
    //FOR EACH CLASS -- SHOW TOP 12 AND SUBMISSIONS.
    $sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
    $result=mysql_query($sql);
    $percol=ceil(mysql_num_rows($result)/2);
    $i=0;
    echo "<tr align=left valign=top><td width='50%'>";
    while($row=mysql_fetch_array($result))
    {
        if($i==$percol)
            echo "</td><td>";
        echo "<p><b>CLASS $row[class]:</b></p>";
        echo "<div class='alert' style=\"width:400px;\"><p><i>The information in this box will not be shown on the public side.</i></p>";
        echo "<p><b>Judge: </b>".GetJOJudgeName(0,GetJOJudgeForCategory($catid,$row['class']))."</p>";
        //SUBMITTED?
        $sql2="SELECT id,datesub FROM joassignments WHERE catid='$catid' AND class='$row[class]'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        if($row2[datesub]>0)	//SUBMITTED
        {
            echo "<p>This judge submitted the rankings for Class $row[class] on ".date("n/d/y",$row2[datesub])." at ".date("g:ia",$row2[datesub]).".</p>";
            echo "<p><a href=\"previewresults.php?session=$session&catid=$catid&unlockid=$row2[id]\" onClick=\"return confirm('Are you sure you want to unlock the rankings so this judge has to re-submit them?');\">Unlock rankings</a> (if judge needs to make changes)</p>";
        }
        else echo "<p>This judge has NOT submitted rankings for this event and class yet.</p>";
        echo "</div>";
        $sql2="SELECT t1.* FROM joentries AS t1,joschool AS t2 WHERE t1.sid=t2.sid AND t1.catid='$catid' AND t2.class='$row[class]'";
        $sql2.=" AND t1.classrank>0 AND t1.classrank<=12 ";
        if($showplace==1) $sql2.="ORDER BY t1.classrank";
        else $sql2.="ORDER BY t2.school";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
            echo "<p>";
            if($showplace==1) echo "$row2[classrank]. ";
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
        }


        echo "<br>";
        $i++;
    }	//END FOR EACH CLASS
    //TOP 15 (12 + ALTERNATES) OVERALL:
    echo "</td></tr>";
    /*
   echo "<tr align=left valign=top><td>";
   echo "<p><b>TOP 12 OVERALL:</b></p>";
   $sql2="SELECT t1.* FROM joentries AS t1,joschool AS t2 WHERE t1.sid=t2.sid AND t1.catid='$catid'";
   $sql2.=" AND t1.overallrank>0 AND t1.overallrank<=15 ORDER BY t1.overallrank";
   $result2=mysql_query($sql2);
   $copy="";
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[overallrank]==13)	//START ALTERNATES
         echo "<br><p><u>Alternates:</u></p>";
      echo "<p>";
      if($row2[overallrank]<=12)
         echo "$row2[overallrank]. ";
      echo GetStudentInfo($row2[studentid],FALSE).", ";
      $copy.=GetStudentInfo($row2[studentid],FALSE).", ";
      for($j=2;$j<=6;$j++)
      {
         $var="studentid".$j;
         if($row2[$var]>0)
      {
        echo GetStudentInfo($row2[$var],FALSE).", ";
        $copy.=GetStudentInfo($row2[$var],FALSE).", ";
         }
      }
      echo GetSchoolName($row2[sid],'jo')."</p>";
      $copy.=GetSchoolName($row2[sid],'jo')."<br>";
      echo "<p style='padding-left:20px;'><a class='small' href=\"/nsaaforms/downloads/$row2[filename]\" target=\"_blank\">$row2[label]</a></p>";
      if($row2[filename2]!='')
         echo "<p style=\"padding-left:20px;\"><a href=\"/nsaaforms/downloads/$row2[filename2]\" target=\"_blank\">$row2[label2]</a></p>";
   }
   echo "</td><td><p><b>TOP 15 (to copy and paste):</b></p>".$copy;
    */
    echo "</td></tr>";
} //END IF CLASS SELECTED
echo "</table>";
echo "</form>";
echo $end_html;
?>
