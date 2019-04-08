<?php
/********************************************************
coopformindex.php
Cooperative Sponsorship Agreement Index(user must be logged in and an AD)
*********************************************************/
session_start();
//Require files
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

error_reporting(E_ALL);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
$level=GetLevel($session);
if(!ValidUser($session) || $level>2)	//If user isn't logged in OR is at a level less than AD, kick them out
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1 Admin) or belongs to (Level 2 AD)
if(!$schoolid || $level!=1)	//SCHOOL USER - GET SCHOOL ID BASED ON SESSION
{
   $schoolid=GetSchoolID($session);
}
$school=GetSchool2($schoolid);

//Get Header, based on if this is a printer-friendly version or not
if($print==1) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);

//Echo Header
echo $init_html;
echo $header;

//Function to save activities - Coop Form
function getActivities($acts) {
	$actsql = "SELECT * FROM coopformactivities WHERE activitiesID = ".$acts;
		$actresult = mysql_query($actsql);
		$actrow = mysql_fetch_array($actresult);
		$actsList = "";
	if ($actrow['fb'] == 'x') $actsList .= "Football - ".$acts['fb_type']." Man<br /> ";
	if ($actrow['ba'] == 'x') $actsList .= "Baseball<br />";
	if ($actrow['bbb'] == 'x') $actsList .= "Boy's Basketball<br />";
	if ($actrow['bbg'] == 'x') $actsList .= "Girl's Basketball<br />";
	if ($actrow['ccb'] == 'x')$actsList .= "Boy's Cross Country<br />";
	if ($actrow['ccg'] == 'x') $actsList .= "Girl's Cross Country<br />";
	if ($actrow['de'] == 'x') $actsList .= "Debate<br />";
	if ($actrow['go_b'] == 'x') $actsList .= "Boy's Golf<br />";
	if ($actrow['go_g'] == 'x') $actsList .= "Girl's Golf<br />";
	if ($actrow['jo'] == 'x') $actsList .= "Journalism<br />";
	if ($actrow['pp'] == 'x') $actsList .= "Play Production<br />";
	if ($actrow['sb'] == 'x') $actsList .= "Girl's Softball<br />";
	if ($actrow['sob'] == 'x') $actsList .= "Boy's Soccer<br />";
	if ($actrow['sog'] == 'x') $actsList .= "Girl's Soccer<br />";
	if ($actrow['sp'] == 'x') $actsList .= "Speech<br />";
	if ($actrow['swg'] == 'x') $actsList .= "Girl's Swimming<br />";
	if ($actrow['swb'] == 'x') $actsList .= "Boy's Swimming<br />";
	if ($actrow['te_b'] == 'x') $actsList .= "Boy's Tennis<br />";
	if ($actrow['te_g'] == 'x') $actsList .= "Girl's Tennis<br />";
	if ($actrow['trb'] == 'x') $actsList .= "Boy's Track<br />";
	if ($actrow['trg'] == 'x') $actsList .= "Girl's Track<br />";
	if ($actrow['vb'] == 'x') $actsList .= "Volleyball<br />";
	if ($actrow['wr'] == 'x') $actsList .= "Wrestling<br />";
	if ($actrow['vm'] == 'x') $actsList .= "Vocal Music<br />";
	if ($actrow['im'] == 'x') $actsList .= "Instrumental Music<br />";
	return $actsList;
	}

//Check to see if Coop Form requires attention
$sql = "SELECT t1.formID, t1.activitiesID, t2.name FROM coopform as t1, coopformschools as t2 
	WHERE t1.formID = t2.formID AND t2.id = $schoolid AND t2.submitted IS NULL";
$result = mysql_query($sql);

if (mysql_num_rows($result) > 0) {
	echo "<div style='margin-left:auto; margin-right:auto;width:700px; border:1px solid black;'>
	  <h2>You have pending cooperative agreements that require your attention</h2>";
	echo "<h3>Please enter your school enrollment and participation information for the forms below</h3>";
     echo "<table><tr style='text-align:left;'><th>Schools</th><th>Activities</th><th>Action</th>
    </tr>";
	while ($row = mysql_fetch_assoc($result)) {
		$activities = getActivities($row['activitiesID']);
		
	$names = "";
    $namesql = "SELECT name, submitted FROM coopformschools WHERE formID = ".$row['formID'];
	$nameresult = mysql_query($namesql);
	while ($row2 = mysql_fetch_assoc($nameresult)) {
	  $names .= $row2['name']."<br />";	
	  }
	
		$add = "coopformsch.php?session=".$session."&cformID=".$row['formID'];
		echo "<tr><td style='width:150px;'>$names</td><td style='width:150px;'>$activities</td>
		<td style='width:150px;'><a href='$add'>Enter Information</a></td></tr>";
			}
	echo "</table>";
	echo "</div>";
	}	

//Check to see if submitting school and there are open forms
$subsql = "SELECT formID FROM coopform WHERE submitting_ID = $schoolid AND submit_date IS NULL";
$subresult = mysql_query($subsql);

//If there are open forms, alert user
if (mysql_num_rows($subresult) > 0) {
echo "<div style='margin-top:8px;margin-left:auto;margin-right:auto;width:700px; border:1px solid black;'>
		<h2>You have unsubmitted cooperative agreements</h2>";
		echo "<table><tr style='text-align:left;'><th>Schools</th><th>Activities</th><th>Missing School Information</th><th>Actions</th>
		</tr>";
 $count = 0; 
while ($subrow = mysql_fetch_assoc($subresult)) {

  $sql2 = "SELECT activitiesID FROM coopform WHERE formID = ".$subrow['formID'];
  $result2 = mysql_query($sql2);
  $row2 = mysql_fetch_assoc($result2);
  $activities2 = getActivities($row2['activitiesID']);
	$names = "";
	$missing = "";
	
	$namesql = "SELECT name, submitted FROM coopformschools WHERE formID = ".$subrow['formID'];
	$nameresult = mysql_query($namesql);
	while ($row3 = mysql_fetch_assoc($nameresult)) {
	  $names .= $row3['name']."<br />";
	  
	  if ($row3['submitted'] == NULL) {
	    $missing .= $row3['name']."<br />";
	  }	  
	}
		$add = "coopform.php?session=".$session."&cformID=".$subrow['formID'];
		
		$mailqry = "SELECT t1.email, t2.school, t3.id FROM logins as t1, headers as t2, coopformschools as t3
        WHERE t3.id = t2.id AND t1.school = t2.school AND t3.id = ".$subrow['id']."
	    AND t3.formID = ".$subrow['formID']." AND t1.sport = 'Activities Director'";
	  
	  $mailres = mysql_query($mailqry);
	  if ($mailres) {
	  $mailrow = mysql_fetch_assoc($mailres);
	
	  $mailto .= ",".$mailrow['email'];
	
	}
   	$body = "An NSAA Cooperative Sponsorship Agreement is being processed between: " + $names;

	$body .= ".  This is a reminder that this Cooperative Sponsorship Agreement is awaiting your input.  This form
	cannot be submitted until your school enrollment and participation information has been entered.  Please log 
	into your NSAA account online at http://nsaahome.criticalitgroup.com/nsaaforms/index.php and Select the Cooperative Sponsorship
	Agreement link under Other Forms.  Then select the link to your form and enter your school's information.  Thank you
	 for your assistance.";
	 
	 if ($missing != "") {
		
	 
	 	echo "<tr><td style='width:150px;'>$names</td><td style='width:150px;'>$activities2</td>
		<td style='width:150px;'>$missing</td><td style='width:150px;'><a href='$add'>Edit</a><br />
		 <a href='mailto:".$mailto."?subject=".$subject."&body=".$body."' target='_blank' >Remind</a></td></tr>";
		} else {
		
	$readyID[$count] = $subrow['formID'];
	$count++;
	}
		
}
echo "</table>";
		echo "</div>";

$numReady = count($readyID);
if ($numReady > 0) {
  echo "<div style='margin-top:8px;margin-left:auto; margin-right:auto;width:700px; border:1px solid black;'>
	  <h2>You have cooperative agreements ready for submission</h2>";
  echo "<table style='padding:5px;'><tr style='text-align:left;'><th>Schools</th><th>Activities</th><th>Actions</th>
    </tr>";
	
	for ($l = 0; $l < count($readyID); $l++) {
    $sql6 = "SELECT activitiesID FROM coopform WHERE formID = ".$readyID[$l];
	$result6 = mysql_query($sql6);
	$row6 = mysql_fetch_assoc($result6);
  $actID = $row6['activitiesID'];
  $sql4 = "SELECT name FROM coopformschools WHERE formID = ".$readyID[$l];
  $result4 = mysql_query($sql4);
  $names2 = "";
  while ($row4 = mysql_fetch_assoc($result4)) {
	$names2 .= $row4['name']."<br />";
  }
  $activities3 = getActivities($actID);
  $eadd = "coopform.php?session=".$session."&cformID=".$readyID[$l];
  $padd = "coopformad.php?session=".$session."&cformID=".$readyID[$l]."&pr=y";
  $sadd = "coopformad.php?session=".$session."&cformID=".$readyID[$l]."&sub=y";

	echo "<tr><td style='width:150px;'>$names2</td><td style='width:150px;'>$activities3</td>
		<td style='width:150px;'><a href='$eadd'>Edit</a><br /><a href='$padd' target='_blank'>Print</a><br /><a href='$sadd' target='_blank'>Submit</a></td></tr>";  
  }
	
  
  	echo "</table>";
  	echo "</div>";
}
}
	  	
//Echo Footer
echo $end_html;	
?>
	
	
