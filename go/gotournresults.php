<?php
/******************************************
gotournresults.php
Regular Season Golf Tournament Results Entry
Used by NSAA to assign districts
Created 7/11/12
Changed 3/17/16 to move schedule entry to the
beginning of the season and allow schools to "join"
tournaments already in the system
Author Ann Gaffigan
 ********************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
    header("Location:../index.php");
    exit();
}
if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=($sport=="gob"?"go_b":"go_g")."tourn";
$teamtbl=$tourntbl."tournteam";
$indytbl=$tourntbl."tournindy";
if ($sport=='gob') $real_sport='go_b';
if ($sport=='gog') $real_sport='go_g';
if($tournid && $unlock==1)
{
    $sql="UPDATE $tourntbl SET unlockreport='x' WHERE tid='$tournid'";
    $result=mysql_query($sql);
}

if($tournid && $delete)
{
    $sql="DELETE FROM $tourntbl WHERE tid='$tournid'";
    $result=mysql_query($sql);
    $sql="DELETE FROM $teamtbl WHERE tournid='$tournid'";
    $result=mysql_query($sql);
    $sql="DELETE FROM $indytbl WHERE tournid='$tournid'";
    $result=mysql_query($sql);

    echo $init_html.$header;
    echo "<br><br><div class='alert' style='width:400px;'>The Tournament Report has been deleted.</div>";
    echo "<br><br>";
    echo "<a href=\"goteamreport.php?session=$session&sport=$sport&sid=$sid\">Return to ".GetSchoolName($sid,$sport)." Season Report</a>";
    echo $end_html;
    exit();
}
if ($tournid){
    $schedtbl=($sport=="gob"?"go_b":"go_g")."sched";
    $schooltbl=($sport=="gob"?"go_b":"go_g")."school";

    $sql="SELECT schoolid FROM $tourntbl WHERE tid='$tournid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if (is_null($row['schoolid'])||$row['schoolid']==0){
        $sql="SELECT * FROM $schedtbl WHERE tid='$tournid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $sid=$row['sid'];
        $sql="SELECT  mainsch FROM $schooltbl WHERE sid=$sid";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $schoolid= $row['mainsch'];
        $sql="UPDATE $tourntbl SET schoolid='$schoolid' WHERE tid=$tournid";
        mysql_query($sql);
    }
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
if($level!=1)
    $school=GetSchool($session);
else if($school_ch)
    $school=$school_ch;
else if($sid)
    $school=GetMainSchoolName($sid,$sport);
else if($tournid)
{
    $tourntbl=($sport=="gog")?"go_gtourn":"go_btourn";
    $sql="SELECT schoolid FROM $tourntbl WHERE tid='$tournid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $school=GetSchool2($row[0]);

}
else
    $school="Test's School";
$school2=ereg_replace("\'","\'",$school);
$schoolid=GetSchoolID2($school);
$sid=GetSID2($school,$sport);
if($school=="Test's School") $sid=1000000;

if($tournid)
{
    $sql="SELECT schoolid FROM $tourntbl WHERE tid='$tournid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $headschool=GetSchool2($row[0]);
    $curusersid=$sid; //GetSID($session,$sport);
    $headsid=GetSID2($headschool,$sport);
    if($curusersid!=$headsid || $level==1)
    {
        $weschool=$headschool; $isare="is";
    }
    else
    {
        $weschool="WE"; $isare="are";
    }
//echo "$curusersid $headsid $headschool $sport";
}
else
{
    $weschool="WE"; $isare="are";
}

if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=($sport=="gog"?"go_g":"go_b")."tourn";
$teamtbl=$sport."tournteam";
$indytbl=$sport."tournindy";
$fallyear=GetFallYear($sport);
$springyear=$fallyear+1;
if($school_ch)
    $school=$school_ch;
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
/*
if(!IsHeadSchool($schoolid,$sport) && !GetCoopHeadSchool($schoolid,$sport) && $school!="Test's School") //NOT a $sport school at all
{
echo $init_html.$header;
echo "<br><br><br><div class='alert' style='width:400px;'><b>$school</b> is not listed as a ".GetActivityName($sport)." school.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
echo $end_html;
exit();
}
else if(!IsHeadSchool($schoolid,$sport) && $school!="Test's School")    //in a Co-op, not the head school
{
echo $init_html.$header;
$mainsch=GetCoopHeadSchool($schoolid,$sport);
$mainsch=GetSchool2($mainsch);
echo "<br><br><br><div class='alert' style='width:400px'><b>$school</b> is in a co-op with <b>$mainsch</b> for ".GetActivityName($sport).".<br><br>Only the head school of the co-op can fill out this entry form.  <b>$mainsch</b> is listed as the head school for this co-op.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
echo $end_html;
exit();
}
*/

if($finalcheck=='x' && $submitfinal)	//SUBMIT TOURNAMENT REPORT AS FINAL (AFTER CHECKING FOR ERRORS)
{
    //ERROR CHECKING
    $errors="";
    if(!$hostschool && !$hostid)
        $errors.="<p>You must select indicate the Tournament Host.</p>";
    if(trim($tournname)=="")
        $errors.="<p>You must enter the Tournament Title.</p>";
    if($year=="0000" || $mo=="00" || $day=="00")
        $errors.="<p>You must enter the Tournament Date.</p>";
    else
    {
        $tourndatesec=mktime(0,0,0,$mo,$day,$year);
        if(ereg("b",$sport))	//Spring Season
        {
            $seasonstart=mktime(0,0,0,3,1,$springyear);
            $seasonend=mktime(0,0,0,6,1,$springyear);
            $startdate="March 1, $springyear"; $enddate="June 1, $springyear";
        }
        else			//Fall Season
        {
            $seasonstart=mktime(0,0,0,8,1,$fallyear);
            $seasonend=mktime(0,0,0,11,1,$fallyear);
            $startdate="August 1, $fallyear"; $enddate="November 1, $fallyear";
        }
        if($tourndatesec<$seasonstart || $tourndatesec>$seasonend)
            $errors.="<p>The date you entered is not a valid date for this season.</p>";
    }
    if(trim($course)=="")
        $errors.="<p>You must enter the name of the Course.</p>";
    if(!$holes)
        $errors.="<p>You must indicate whether the course is 9 holes or 18 holes.</p>";
    else if($holes==9 && !$hole9name)
        $errors.="<p>You must indicate which 9 holes were played.</p>";
    else if($holes==9 && $hole9name=="Other" && trim($hole9other)=="")
        $errors.="<p>You must indicate which 9 holes were played. (If you check \"Other\" please specify.)</p>";
    if((trim($courserating)=="" || $courserating==0) && $norating!='x')
        $errors.="<p>You must enter the Course Rating OR check the box next to \"This course is NOT RATED.\"</p>";
    //NOW CHECK THAT ALL TEAMS HAVE AT LEAST 4 GOLFERS ENTERED
    $sql="SELECT DISTINCT sid FROM $indytbl WHERE tournid='$tournid'";

    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result))
    {
        $cursid=$row[sid];
        $sql2="SELECT id FROM $indytbl WHERE tournid='$tournid' AND sid='$cursid'";
        $result2=mysql_query($sql2);
        if(mysql_num_rows($result2)<4)
        {
            $errors.="<p>You have only entered <u>".mysql_num_rows($result2)."</u> golfers' scores for ".GetSchoolName($cursid,$sport).". You must enter scores for at least 4 golfers.</p>";
        }
    }
    if(mysql_num_rows($result)==0 && $noscores!='x' && $norating!='x')	//NO GOLFERS ENTERED AT ALL
        $errors.="<p>You need to enter results for at least one team.</p>";


    if($errors=='' || $level==1)
    {
        //MARKED AS SUBMITTED
        $sql="UPDATE $tourntbl SET datesub='".time()."',unlockreport='' WHERE tid='$tournid'";
        $result=mysql_query($sql);
    }
}

if($save)	//Save Tournament Info
{
    if($hole9name=="Other") $hole9name=$hole9other;
    if($postcanc) $$postcanc="x";
    if ($hostid > 0) {
        $hostschool = "schoolid";
        $hostschoolid = $hostid;
    } else $hostschoolid = $schoolid;
    if($tournid)
    {
        if($norating=='x') { $courserating=0; $tournrating=0; }
        $sql="UPDATE $tourntbl SET schoolid='$hostschoolid', hostschool='$hostschool',name='".addslashes($tournname)."', tourndate='$year-$mo-$day',course='".addslashes($course)."',hole9name='$hole9name',holes='$holes',courserating='$courserating',tournrating='$tournrating',postponed='$postponed',canceled='$canceled',noscores='$noscores',norating='$norating'";
        if($postcanc=="postponed" && "$year-$mo-$day"!=$origdate) $sql.=",origdate='$origdate'";
        $sql.=" WHERE tid='$tournid'";

        $result=mysql_query($sql);

        $added=0;
    }
    else
    {
        $sql="INSERT INTO $tourntbl (hostschool,name,tourndate,course,hole9name,holes,courserating,tournrating,datesub,schoolid) VALUES ('$hostschool','".addslashes($tournname)."','$year-$mo-$day','".addslashes($course)."','".addslashes($hole9name)."','$holes','$courserating','$tournrating','0','$hostschoolid')";
        $result=mysql_query($sql);
        $tournid=mysql_insert_id();
        $added=1;
    }

    if($sid)	//MAKE SURE THIS SCHOOL IS ACTUALLY ADDED TO THIS TOURNAMENT
    {
        $sql="SELECT * FROM $teamtbl WHERE tournid='$tournid' AND sid='$sid'";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)==0)
        {
            $sql="INSERT INTO $teamtbl (tournid,sid) VALUES ('$tournid','$sid')";
            $result=mysql_query($sql);
        }
    }
    if($hostschoolid!=$schoolid)	//THIS MEANS THEY SELECTED ANOTHER CLASS A TEAM - MAKE SURE THEY ARE ADDED TO THE TOURNAMENT TOO
    {
        $hostsid=GetSID2(GetSchool2($hostschoolid),$sport);
        $sql="SELECT * FROM $teamtbl WHERE tournid='$tournid' AND sid='$hostsid'";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)==0)
        {
            $sql="INSERT INTO $teamtbl (tournid,sid) VALUES ('$tournid','$hostsid')";
            $result=mysql_query($sql);
        }
    }
    header("Location:gotournresults.php?session=$session&school_ch=$school_ch&sport=$sport&tournid=$tournid&added=$added");
}

//SHOW FORM:
echo $init_html.$header;

?>
<script language="javascript">
    function CalculateTournRating()
    {
        var courserating=parseFloat(document.getElementById('courserating').value);
        if(document.getElementById('holes9').checked)
            courserating=courserating/2;
        var tournrating=(courserating*4).toFixed(2);
        document.getElementById('tournrating').value=tournrating;
    }
</script>
<?php

echo "<form method=post action=\"gotournresults.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sid\" value=\"$sid\">";		//GIVEN IF NSAA GOT HERE FROM TEAM REPORT
echo "<input type=hidden name=\"sport\" value=\"$sport\">";
echo "<input type=hidden name=\"school\" value=\"$school\">";
echo "<input type=hidden name=\"tournid\" value=\"$tournid\">";
if($tournid)
{
    $sql="SELECT * FROM $tourntbl WHERE tid='$tournid'";
    $result=mysql_query($sql);
    $tourn=mysql_fetch_array($result);
}

echo "<p style=\"text-align:left;\">";
if($level==1)
    //echo "<a href=\"goteamreport.php?session=$session&sport=$sport&sid=$sid\">&larr; Return to ".GetSchoolName($sid,$sport)." Season Report</a>";
    echo "<a href=\"results_main.php?session=$session&sport=$real_sport&sid=$sid&school_ch=$school\">&larr; Return to ".GetSchoolName($sid,$sport)." Season Report</a>";
else if($level>1)
    echo "<a href=\"resultsmain.php?session=$session&sport=$sport\">&larr; Return to $sportname Schedule & Tournament Reports</a>";
echo "</p><a name='top'><br></a>";

echo "<table class=\"nine\" style=\"width:850px;\" cellspacing=0 cellpadding=5><caption><b>";
if(!$tournid)
    echo "Enter a $sportname Tournament on your Schedule:</b><br>";
else echo "$sportname Tournament Report:</b><br>";
if($errors!='')
{
    echo "<div class='error' style=\"width:500px;\">$errors<p>Please correct these errors below, SAVE THE INFORMATION, and then re-submit this report.</p></div>";
}
else if($tourn[unlockreport]=='x' && ($level==1 || $headsid==$curusersid)) 	//UNLOCKED
{
    $submitted=0;
    echo "<div class='alert' style=\"width:500px;\">This form has been UNLOCKED by the NSAA. Please make the necessary corrections below and then RE-SUBMIT this Tournament Report, by checking the box next to \"I verify that this tournament report is complete, to the best of my knowledge\" and clicking \"Submit.\"</div>";
    if($level==1)
        echo "<p><a href=\"gotournresults.php?sid=$sid&session=$session&tournid=$tournid&sport=$sport&delete=1\" onClick=\"return confirm('Are you sure you want to delete this Tournament Report?');\">DELETE this Tournament Report</a></p>";
}
else if($tourn[datesub]>0)	//SUBMITTED
{
    $submitted=1;
    echo "<br><p>This report was submitted by ".GetCoaches($tourn['schoolid'],$sport)." (".GetSchool2($tourn['schoolid'])."), Tournament Director, on ".date("F j, Y",$tourn[datesub]).".</p>";
    if($level==1)
    {
        echo "<p><a href=\"gotournresults.php?sid=$sid&sport=$sport&session=$session&tournid=$tournid&edit=1\">EDIT this Tournament Report</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"gotournresults.php?sid=$sid&session=$session&tournid=$tournid&sport=$sport&unlock=1\">UNLOCK this Tournament Report for $school</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"gotournresults.php?sid=$sid&session=$session&tournid=$tournid&sport=$sport&delete=1\" onClick=\"return confirm('Are you sure you want to delete this Tournament Report?');\">DELETE this Tournament Report</a></p>";
    }
    else
        echo "<p>If you feel there are any <u>CORRECTIONS</u> that need to be made to this report, please contact the NSAA.</p>";
}
else if($schoolid==$tourn['schoolid'] && $tournid)	//NOT YET SUBMITTED (OR UNLOCKED)
{
    $submitted=0;
    if(PastDue($tourn['tourndate'],-1))	//It is on or after the day of the tournament
    {
        echo "<div class='error' style=\"width:600px;\"><p><b>This report has not yet been submitted to the NSAA office.</b></p>";
        if($tourn['hostschool']=="schoolid")
            echo "<p>Please enter <u>at the bottom of this screen</u> the VARSITY PLAYERS' individual score results for all Class A schools who attended <b><u>your hosted meet</u></b>.</p>";
        else
            echo "<p>Please enter <u>at the bottom of this screen</u> the VARSITY PLAYERS' individual score results <b><u>for your team only</u></b> (since this tournament was hosted by a <b><u>non-Class A school</b></u> or <b><u>out-of-state school</b></u>).</p>";
        echo "<p>Once this report has been completed in full, check the box next to \"I verify that this tournament report is complete, to the best of my knowledge\" and click \"Submit.\"</div>";
    }
    else
    {
        echo "<div class='alert'>";
        if($tourn['schoolid']==$schoolid)
            echo "<p>You will be able to enter the full individual and team results for this tournament starting on the day the tournament takes place.</p>";
        else
            echo "<p>".GetSchool2($tourn['schoolid'])." will enter the full individual and team results for this tournament once it is complete.</p>";
        echo "</div>";
    }
}
else if($tournid)	//Results not submitted yet; this school is not the host
{
    if($added==1) echo "<div class='alert'><p><i>This tournament has been added to your schedule.</i> <a href=\"resultsmain.php?session=$session&sport=$sport&school_ch=$school_ch\" class=\"small\">Return to your schedule</a></p>";
    if(PastDue($tourn['tourndate'],-1))  //It is on or after the day of the tournament
        echo "<div class='alert'><p><b>".GetSchool2($tourn['schoolid'])."</b> has not yet submitted this tournament report.</p></div>";
}
if(($errors=="" || !$errors) && $tournid && $schoolid==$tourn['schoolid'] && $submitted==0 && PastDue($tourn['tourndate'],-1)) 	//SHOW CHECKBOX AND SUBMIT BUTTON
{
    echo "<div class='normalwhite' style='text-align:center;font-size:14px;padding:10px;margin:10px;width:700px;'><input type=checkbox name=\"finalcheck\" value=\"x\"> I verify that this tournament report is complete, to the best of my knowledge. <input type=submit name=\"submitfinal\" class=\"fancybutton2\" value=\"Submit to the NSAA\"></div>";
}
echo "</caption>";

if($edit==1 && $level==1)
    $submitted=0;			//ALLOW NSAA TO EDIT

/*** TOURNAMENT INFO (go_tourn) ***/
echo "<tr align=center><td><br><table cellspacing=0 cellpadding=6 class=\"nine\">";

//Before they can enter anything else, they need to enter the DATE so we
//can search for other tournaments on that date and let them "join" a
//tournament if it's already in the system (3/16/16)
echo "<tr align=left><td><b>";
if($tournid) echo "Tournament Date:";
else echo "Select the TOURNAMENT DATE:";
echo "</b></td><td>";
if(!$tournid) $date=date("Y-m-d");
else
{
    if ($tourn[tourndate]=="0000-00-00"||is_null($tourn[tourndate])){
        $date=$tourn[received];
    }else{
        $date=$tourn[tourndate];
    }

}
$date=explode("-",$date);
if(!$mo) $mo=$date[1];
if(!$day) $day=$date[2];
if(!$year) $year=$date[0];
//If the results are already submitted OR this school is not the school that originally entered this tournament,
//they cannot edit the tournament
if(($tournid && ($submitted || $tourn['schoolid']!=$schoolid)) || $continue=="addnew") //Cannot Edit
{
    echo "$mo/$day/$year";
    echo "<input type=\"hidden\" name=\"mo\" value=\"$mo\">
	<input type=\"hidden\" name=\"day\" value=\"$day\">
	<input type=\"hidden\" name=\"year\" value=\"$year\">";
    if($continue=="addnew")
        echo "&nbsp;&nbsp;<a class=\"small\" href=\"gotournresults.php?session=$session&school_ch=$school_ch&sport=$sport&continue=1\">Change the date</a>";
}
else //Can Edit
{
    if(!$mo) $mo=$date[1];
    if(!$day) $day=$date[2];
    if(!$year) $year=$date[0];
    echo "<select name=\"mo\">".GetDateSelectOptions("MM",$mo,1,12)."</select>/";
    echo "<select name=\"day\">".GetDateSelectOptions("DD",$day,1,31)."</select>/";
    $year1=$date[0]-1; $year2=$date[0]+1;
    echo "<select name=\"year\">".GetDateSelectOptions("YYYY",$year,$year1,$year2)."</select>";
}
echo "</td></tr>";
if(!$tournid && $continue!="addnew")
{
    echo "<tr align=\"center\"><td colspan=\"2\"><input type=\"submit\" name=\"continue\" class=\"fancybutton\" value=\"Continue &rarr;\"></td></tr>";
}
if($continue && $continue!="addnew")	//FIND TOURNAMENTS ON THIS DATE
{
    echo "<tr align=\"left\"><td colspan=\"2\">";
    $sql="SELECT * FROM $tourntbl WHERE tourndate='$year-$mo-$day' ORDER BY name";
    $result=mysql_query($sql);
    $ct=mysql_num_rows($result);
    if($ct>0)
    {
        echo "<div class=\"alert\"><p>We found ";
        if($ct==1) echo "a tournament ";
        else echo "<b><u>$ct</b></u> tournaments ";
        echo "on this date.</p><p>Please <b>click the link below</b> for the tournament you wish to add to your schedule. If you do not see the tournament you wish to add to your schedule, click the link to add a NEW tournament to your schedule.</p><ul>";
        while($row=mysql_fetch_array($result))
        {
            echo "<li><a href=\"gotournresults.php?session=$session&school_ch=$school_ch&sport=$sport&addme=1&tournid=$row[id]\">$row[name] at $row[course] ($row[holes] Holes) &rarr;</a><p><i>entered by ".GetSchool2($row['schoolid'])."</i></p>";
            //SEE IF THIS SCHOOL IS ALREADY IN THIS TOURNAMENT
            $sql2="SELECT * FROM $teamtbl WHERE tournid='$row[id]' AND sid='$sid' LIMIT 1";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)>0)	//YES
            {
                if($row['schoolid']==$schoolid)
                    echo "<p style=\"background-color:#0000ff;color:#ffffff;\"><i>Click the link above to view/enter the RESULTS for this tournament.</p>";
                else
                    echo "<p style=\"background-color:#00ff00;\"><b><i>Your team is already included in this tournament!</b></i> ".GetSchool2($row['schoolid'])." must enter the results for this tournament. Click the link above to view any results that may have been entered already.</p>";
            }
            echo "</li>";
        }
        echo "</ul><p><a class=\"small\" href=\"gotournresults.php?session=$session&sport=$sport&school_ch=$school_ch&mo=$mo&day=$day&year=$year&continue=addnew\">Enter a New Tournament on this Date &rarr;</a></p></div>";
    } //END IF TOURNAMNETS FOUND ON THIS DATE
    else
        echo "<br /><p><i>No tournaments were found in the system on this date. <a href=\"gotournresults.php?session=$session&sport=$sport&school_ch=$school_ch&mo=$mo&day=$day&year=$year&continue=addnew\">Enter a New Tournament on this Date &rarr;</a></p>";
    echo "</td></tr></table>";
}
else if($tournid || $continue=="addnew" || $addme==1)
{
    if($addme==1 && $tournid) //Add this school to this tournament, if they aren't added already
    {
        $sql="SELECT * FROM $teamtbl WHERE tournid='$tournid' AND sid='$sid' LIMIT 1";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)==0)
        {
            $sql="INSERT INTO $teamtbl (tournid,sid) VALUES ('$tournid','$sid')";
            $result=mysql_query($sql);
        }
    }
    else if(!$tournid) $continue="addnew"; //Must enter a new tournament
    //POSTPONE, CANCEL
    if($tournid && PastDue($tourn['tourndate'],-1))
    {
        echo "<tr align=left><td>&nbsp;</td><td><input type=\"radio\" name=\"postcanc\" value=\"postponed\"";
        if($tourn['postponed']=='x') echo " checked";
        if($tournid && ($submitted==1 || $schoolid!=$tourn['schoolid'])) echo " disabled";
        echo "> POSTPONED";
        if($tourn['postponed']=='x' && $tourn['origdate']>'0000-00-00')
            echo " (originally scheduled for ".date("m/d/y",strtotime($tourn['origdate'])).")";
        echo "&nbsp;&nbsp;<input type=\"radio\" name=\"postcanc\" value=\"canceled\"";
        if($tourn['canceled']=='x') echo " checked";
        if($tournid && ($submitted==1 || $schoolid!=$tourn['schoolid'])) echo " disabled";
        echo "> CANCELED<input type=hidden name=\"origdate\" value=\"$tourn[tourndate]\"></td></tr>";
    }
    echo "<tr valign=\"top\" align=left><td width='140px'><b>Tournament Host:</b></td><td>";
    if($tournid && ($submitted || $schoolid!=$tourn['schoolid']))
    {
        if($tourn['hostschool']=="schoolid") echo GetSchoolName(GetSID2(GetSchool2($tourn['schoolid']),$sport),$sport);
        else if($tourn['hostschool']=="nonclassA") echo "a NON-CLASS A Nebraska School";
        else echo "an OUT-OF-STATE School</td></tr>";
    }
    else
    {
        echo "A <u>CLASS A Nebraska School</u> is hosting: <select name=\"hostid\"><option value=\"0\">Select Class A Host</option>";
        $sql2="SELECT * FROM ".GetSchoolsTable($sport)." WHERE class='A' ORDER BY school";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
            echo "<option value=\"$row2[mainsch]\"";
            if($tourn['hostschool']=="schoolid" && $tourn['schoolid']==$row2['mainsch']) echo " selected";
            echo ">$row2[school]</option>";
        }
        echo "</select><p><b>OR:</b></p><p>
		<input type=radio name=\"hostschool\" value=\"nonclassA\"";
        if($tourn[hostschool]=="nonclassA") echo " checked";
        if($tournid && ($submitted==1 || $schoolid!=$tourn['schoolid'])) echo " disabled";
        echo "> A <u>NON-CLASS A Nebraska school</u> is the host&nbsp;&nbsp;&nbsp;<input type=radio name=\"hostschool\" value=\"outofstate\"";
        if($tourn[hostschool]=="outofstate") echo " checked";
        if($tournid && ($submitted==1 || $schoolid!=$tourn['schoolid'])) echo " disabled";
        echo "> An <u>OUT-OF-STATE school</u> is the host</td></tr>";
        if(!$submitted)
            echo "<tr align=left><td>&nbsp;</td><td><div class='alert'><b>NOTE:</b> If you are the host school of a Class A meet, you need to enter ALL THE <u><b>CLASS A VARSITY</b></u> TEAMS' INDIVIDUAL PLAYERS'/TEAM RESULTS.  If you attended an out-of-state hosted meet or non-Class A hosted meet, you only need to enter your own VARSITY TEAM'S INDIVIDUAL PLAYERS'/TEAM RESULTS.</div></td></tr>";
    }
    echo "<tr align=left><td><b>Tournament Title:</b></td><td>";
    if($tournid && ($submitted || $schoolid!=$tourn['schoolid']))
        echo $tourn[name];
    else
        echo "<input type=text name=\"tournname\" value=\"$tourn[name]\" size=40>";
    echo "</td></tr>";
    echo "<tr align=left><td><b>Course:</b></td><td>";
    if($tournid && ($submitted || $schoolid!=$tourn['schoolid'])) echo $tourn[course];
    else echo "<input type=text name=\"course\" value=\"$tourn[course]\" size=40>";
    echo "</td></tr>";
    echo "<tr align=left><td>&nbsp;</td><td><input type=radio name=\"holes\" id='holes9' value=\"9\" onClick=\"if(document.getElementById('courserating')) { CalculateTournRating(); } if(this.checked) { document.getElementById('hole9div').style.display=''; }\"";
    if($tournid && ($submitted || $schoolid!=$tourn['schoolid'])) echo " disabled";
    if($tourn[holes]==9) echo " checked";
    echo "> 9-Hole Meet&nbsp;&nbsp;&nbsp;<input type=radio name=\"holes\" id='holes18' value=\"18\" onClick=\"if(document.getElementById('courserating')) { CalculateTournRating(); } if(this.checked) { document.getElementById('hole9div').style.display='none'; }\"";
    if($tournid && ($submitted || $schoolid!=$tourn['schoolid'])) echo " disabled";
    if(!$tournid || $tourn[holes]==18) echo " checked";
    echo "> 18-Hole Meet<br>";
    //DIV FOR NAME OF 9-HOLES
    echo "<div id='hole9div'";
    if($tourn[holes]==18 || !$tournid) echo " style='display:none;'";
    echo "><input type=radio name=\"hole9name\" id=\"hole9nameFront\" value=\"Front\"";
    if($tourn[hole9name]=="Front") echo " checked";
    echo "> Front<br><input type=radio name=\"hole9name\" id=\"hole9nameBack\" value=\"Back\"";
    if($tourn[hole9name]=="Back") echo " checked";
    echo "> Back<br><input type=radio name=\"hole9name\" id=\"hole9nameOther\" value=\"Other\"";
    if($tourn[hole9name]!="Front" && $tourn[hole9name]!="Back" && $tournid)
    {
        echo " checked"; $hole9other=$tourn[hole9name];
    }
    echo "> Other (Please enter name of 9-holes played: <input type=text size=20 name=\"hole9other\" value=\"$hole9other\">)";
    echo "</div>";
    echo "</td></tr>";
    if($tournid)
    {
        echo "<tr valign=\"top\" align=\"left\"><td><b>Course Rating:</b></td><td>";
        if($tournid && ($submitted || $schoolid!=$tourn['schoolid'])) echo $tourn[courserating];
        else
            echo "<input type=text name=\"courserating\" id=\"courserating\" onKeyUp=\"CalculateTournRating();\" size=5 value=\"$tourn[courserating]\">";
        echo " (18-Hole Course Rating for tee boxes used)
		<p><input type=\"checkbox\" name=\"norating\" value=\"x\"";
        if($tournid && ($submitted || $schoolid!=$tourn['schoolid'])) echo " disabled";
        if($tourn['norating']=='x') echo " checked";
        echo "><b> This course is NOT RATED.</b> (If checked, you do not need to enter a course rating. Please note that, if checked, this tournament will be excluded from differential calculations.)</p>";
        echo "</td></tr>";
        echo "<tr align=left><td><b>Tournament Rating:</b></td><td>";
        if($tournid && ($submitted || $schoolid!=$tourn['schoolid']))
            echo $tourn[tournrating];
        else
            echo "<input type=text readOnly=\"true\" name=\"tournrating\" id=\"tournrating\" value=\"$tourn[tournrating]\" size=5> (Automatically Calculated)";
        echo "</td></tr>";
        //Unconventional Format
        echo "<tr align=\"left\"><th colspan=2\"><input type=\"checkbox\" name=\"noscores\" value=\"x\"";
        if($tournid && ($submitted || $schoolid!=$tourn['schoolid'])) echo " disabled";
        if($tourn['noscores']=='x') echo " checked";
        echo "> <b>Scores not reported due to unconventional scoring format</b></th></tr>";
    }
    echo "</table>";
    if($save)
        echo "<div class='alert' style=\"width:400px;\">The tournament information has been saved.</div><br>";
    if(!$tournid || (!$submitted && $schoolid==$tourn['schoolid'])) echo "<input type=submit name=\"save\" value=\"Save Tournament Info\">";
    if(!$tournid)
        echo "<br><br>(You will be able to enter results once you save the above tournament information.)";
    else	//SHOW EXISTING RESULTS, LINK TO ADD MORE
    {
        $class=GetClass($sid,$sport);
        if(!$tournid || (!$submitted && $schoolid==$tourn['schoolid']) && PastDue($tourn['tourndate'],-1)||$level==1||$tourn['schoolid']==GetSchoolID($session)||($level==3||$class=='A'))
        {
            echo "<br><div class=\"normalwhite\" style=\"padding:15px 15px 0px 15px;margin:15px;\"><p>You will need to enter the <b>top 5 <label style='color:#ff0000;'>VARSITY</label> individual scores for each team</b>. From this information, the system will calculate the Team Scores.</p><p>Click \"Enter Individual Results\" below to enter individual scores for each team.</p><p><input type=button value=\"+ Enter Individual Results\" onClick=\"window.open('goindyresults.php?reportsid=".$sid."&session=".$session."&tournid=".$tournid."&sport=".$sport."','Individual_Results','width=550,height=500,location=no');\"></p></div>";
        }

        if($submitted==1 || ( PastDue($tourn['tourndate'],-1)))
        {
            $sql="SELECT DISTINCT t1.sid,t2.score FROM $indytbl AS t1,$teamtbl AS t2 WHERE t1.sid=t2.sid AND t2.tournid='$tournid'";
            //if($curusersid!=$headsid && $level!=1)	//SCHOOL THAT IS NOT THE HEAD SCHOOL THAT ENTERED THE RESULTS - ONLY SHOW THEM THEIR TEAM'S RESULTS
            // $sql.=" AND t1.sid='$curusersid'";
            $sql.=" ORDER BY t2.score ASC";
            $result=mysql_query($sql);
            if(mysql_num_rows($result)>0)
                echo "<br><br><b>Individual <label style='color:#ff0000;'>VARSITY</label> Results:</b><br>";
            //Check if the school is in the tournament
            $class=GetClass($sid,$sport);
            while($row=mysql_fetch_array($result))
            {
                echo "<br><table cellspacing=0 cellpadding=3 class=\"nine\" frame=all rules=all style=\"border:#808080 1px solid;\">";
                echo "<caption>Individual <label style='color:#ff0000;'>VARSITY</label> Results for<br><b>".GetSchoolName($row[sid],$sport)."</b>";
                if(!$submitted && ($schoolid==$tourn['schoolid'])||$level==1|| $tourn['schoolid']==GetSchoolID($session)||($level==3||$class=='A'))
                    echo "&nbsp;<input type=button name=\"Edit\" value=\"Edit Results\" onClick=\"window.open('goindyresults.php?session=".$session."&tournid=".$tournid."&reportsid=".$sid."&sid=".$row[sid]."&sport=".$sport."','Individual_Results','width=550,height=500,location=no');\">";
                echo "</caption>";
                //NOTE: Need way to delete results for a team
                echo "<tr align=center><td>Player</td><td>9 or 18 Meet Score</td></tr>";
                $sql2="SELECT * FROM $indytbl WHERE tournid='$tournid' AND sid='$row[sid]' ORDER BY score";
                $result2=mysql_query($sql2);
                while($row2=mysql_fetch_array($result2))
                {
                    echo "<tr align=center><td>".GetStudentInfo($row2[studentid])."</td><td>$row2[score]</td></tr>";
                }
                //TEAM SCORE
                echo "<tr align=center><td colspan=2>Team Score: <b>$row[score]</b> (Low four scores)</td></tr>";
                echo "</table>";
                if($sid==$row[sid] && $row['score']==0)	//Can Delete
                    echo "<p><a href=\"resultsmain.php?session=$session&sport=$sport&school_ch=$school_ch&removetournid=$tournid\" onClick=\"return confirm('Are you sure you want to remove this tournament from your schedule?');\">Remove your Team from this Tournament</a></p>";
            }
            //TEAM RESULTS
            if(mysql_num_rows($result)>0)
            {
                echo "<br><table cellspacing=0 cellpadding=3 class=\"nine\" frame=all rules=all style=\"border:#808080 1px solid;\">";
                echo "<caption><b>Varsity Team Results:</b></caption>";
                echo "<tr align=center><td>Team</td><td>Team Score</td><td>Tournament<br>Rating</td><td>Differential</td></tr>";
                $sql="SELECT * FROM $teamtbl WHERE tournid='$tournid' ORDER BY score";
                $result=mysql_query($sql);
                while($row=mysql_fetch_array($result))
                {
                    $sql2="SELECT sid FROM $indytbl WHERE tournid='$tournid' AND sid=$row[sid]";
                    $result2=mysql_query($sql2);
                    if(mysql_num_rows($result2)>3){
                        echo "<tr align=center><td align=left>".GetSchoolName($row[sid],$sport)."</td><td>$row[score]</td><td>$tourn[tournrating]</td>";
                        $diff=$row[score]-$tourn[tournrating];
                        echo "<td>$diff</td></tr>";
                    }
                }
                echo "</table>";
            }
        } //END IF RESULTS SUBMITTED OR THIS SCHOOL IS THE HOST
    }
}	//END IF $tournid || $continue
else echo "</table>";
echo "</td></tr>";

echo "</table>";
echo "</form>";

if($tournid || $continue=="addnew")
{
    echo "<a href=\"#top\">Return to Top</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
    if($level==1)
        echo "<a href=\"goteamreport.php?session=$session&sport=$sport&sid=$sid\">Return to ".GetSchoolName($sid,$sport)." Season Report</a>";
    else if($level>1)
        echo "<a href=\"resultsmain.php?session=$session&sport=$sport\">Return to $sportname Tournament Reports</a>";
    else
        echo "<a href=\"../welcome.php?session=$session\">Return Home</a>";
}
echo $end_html;
?>
