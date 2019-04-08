<?php
/********************************************************
coopformad.php
Cooperative Sponsorship Agreement Print and Submit(user must be logged in and an AD)
*********************************************************/
session_start();
//Require files
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

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
//echo $header;

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

if (isset($_GET['pr'])) {
  $formID = $_GET['cformID'];
  
  $sql = "SELECT * FROM coopform WHERE formID = $formID";
  $result = mysql_query($sql);
  $row = mysql_fetch_assoc($result);
  
  $sql3 = "SELECT * FROM coopformactivities WHERE activitiesID = ".$row['activitiesID'];
  $result3 = mysql_query($sql3);
  $row3 = mysql_fetch_assoc($result3);
  
  $sql2 = "SELECT * FROM coopformschools WHERE formID = $formID";
  $result2 = mysql_query($sql2);
    
  while ($row2 = mysql_fetch_assoc($result2)) {

  if ($row2['formschoolnum'] == '1') {
    $sch1qry = "SELECT * FROM coopformschools WHERE formschoolnum = '1' AND formID = ".$formID;
    $sch1res = mysql_query($sch1qry);
    $sch1row = mysql_fetch_assoc($sch1res);
}

  if ($row2['formschoolnum'] == '2') {
    $sch2qry = "SELECT * FROM coopformschools WHERE formschoolnum = '2' AND formID = ".$formID;
    $sch2res = mysql_query($sch2qry);
    $sch2row = mysql_fetch_assoc($sch2res);
}

  if ($row2['formschoolnum'] == '3') {
    $sch3qry = "SELECT * FROM coopformschools WHERE formschoolnum = '3' AND formID = ".$formID;
    $sch3res = mysql_query($sch3qry);
    $sch3row = mysql_fetch_assoc($sch3res);
}

  if ($row2['formschoolnum'] == '4') {
    $sch4qry = "SELECT * FROM coopformschools WHERE formschoolnum = '4' AND formID = ".$formID;
    $sch4res = mysql_query($sch4qry);
    $sch4row = mysql_fetch_assoc($sch4res);
}
}

  echo "<div style='width:800px;margin-left:auto;margin-right:auto;'";
  echo "<h2><center>COOPERATIVE SPONSORSHIP AGREEMENT</center></h2>";
  echo "<h5>This Agreement is made between/among the School Boards of:<br />
  School District No. ".$sch1row['district'].", ".$sch1row['name'].", Nebraska and<br />
  School District No. ".$sch2row['district'].", ".$sch2row['name'].", Nebraska ";
    if (isset($sch3row['district'])) echo "and<br />School District No. ".$sch3row['district'].", ".
	$sch3row['name'].", Nebraska";
	if (isset($sch4row['district'])) echo "and<br />School District No. ".$sch4row['district'].", ".
	$sch4row['name'].", Nebraska and<br /></h5>";
	
	echo "<h3>The parties agree as follows:</h3><ol><li><u>Joint Application.</u>  The above-named
  governing boards shall jointly make an applicaiton to the Nebraska School Activities Association
   (NSAA) Board of Directors before (April1 or June 1 for fall activities, September 1 for winter
    activities, or January 1 for spring activities) ".date('Y').", for approval for cooperative 
	sponsorship of a joint high school program.";
  $activities = getActivities($row['activitiesID']);
  echo "<h4>Activities</h4>".$activities;
  echo "<br />hereinafter 'combined program', for students attending ".$sch1row['name'];
  echo "for years ".$row['effective']." through ".$row['ending'];
  echo "</li><li><u>Purpose.</u>  The purposes for the above-named boards agreeing to apply for
 authority to cooperatively sponsor the combined program are as follows:";
  echo "<ol><li>".$row['purpose1']."</li><li>".$row['purpose2']."</li><li>".$row['purpose3']."</li>
<li>".$row['purpose4']."</li></ol>";
  echo "<li><u>Agreement to Cooperate.</u>  If the joint application is approved by the NSAA Board of Directors,
   the above-named governing boards agree that they will cooperatively sponsor the combined program in the school
   years specified, provided that nothing in this provision shall be deemed to require that the governing boards
    offer that combined program at all in any particular year.</li>";
  echo "<li><u>Terms and conditions of Cooperative Sponsorship.</u>  Any combined program shall be cooperatively 
   sponsored upon the following terms and conditions:<ul><li><u>Team Name, Mascot, and Team Colors.</u>  The team shall be 
   known as ".$row['teamname'].", the mascot shall be ".$row['teammascot'].", with School District No. ".$sch1row['dist']." 
serving as host school district.  The team colors are ".$row['teamcolors']."</li>";
 echo "<li><u>Contracts.</u>  Except as otherwise provided herein, contracts related to the cooperatively sponsored team with groups 
 such as referee associations, with individuals, or with other schools or school districts, shall be made by the governing
 board of School District No. ".$row['contract_dist'].", after consultation with the governing board of the cooperating school 
 district.  In the event this co-op qualifies for reimbursement for any state championship, the check should be written to ".$row['reimburse']." 
 High School.</li>";
  echo "<li><u>Allocation of Costs.</u>  All costs of the combined program shall be allocated between/among the parties in
 the manner indicated below for each expenditure category listed:<ol><li>Expenses for transportation, including daily 
transportation of participants to and from practice sessions and contests.<br />".$row['dtransexp_all']."</li>";
echo "<li>Expenses for transportation to 'away contests.'<br />".$row['atransexp_all']."</li>";   
  echo "<li>Expenses for spectator buses.<br />".$row['specbusexp_all']."</li>";   
  echo "<li>Expenses for facilities, lights, heating, showers, towels, laundry, etc., of the host school, including 
  maintenance of practice and competitive facilities.<br />".$row['facilexp_all']."</li>";   
  echo "<li>Expenses for banquets and awards.<br />".$row['banqexp_all']."</li>";   
  echo "<li>Expenses for scouting, coaches' meetings, and workshops.<br />".$row['scoutexp_all']."</li>";   
  echo "<li>Expenses for payment of referees and other personnel necessary to stage the event.<br />".$row['refexp_all']."</li>";   
  echo "<li>Expenses for purchasing of supplies and equipment.<br />".$row['suppexp_all']."</li>";   
  echo "<li>Expenses for salary and fringe benefit costs for coaches and other activity personnel.<br />".$row['salexp_all']."</li>";   
  echo "<li>Other expenses.<br />".$row['otherexp_all']."</li></ol>";
  echo "In the event that the allocation of an expenditure item is not specified above, the costs of that item shall be shared
 EQUALLY between/among the cooperating parties.</li>";
 echo "<li><u>Allocation of Gate Receipts.</u>  Funds from gat receipts shall be divided by the parties after payment of 
 referees and other personnel in the following manner:<br />".$row['gate_all']."<br />";
echo "In the event the gate receipts are insufficient to make the payments, the parties shall make up the difference in the
 following manner:<br />".$row['insufgate_all']."</li>";
echo "<li><u>Concessions.</u>  The provision of concessions at home contests shall be the responsibility of the home location 
school, and concession revenues shall not be covered by the provisions of this Agreement unless the parties specifically agree 
to the contrary herein.</li>";
echo "<li><u>Utilization of Resources.</u>  Personnel in charge of the program shall make every attempt to utilize the resources of 
each of the cooperating schools, such as equipment and uniforms.</li>";
echo "<li><u>Employment of Personnel.</u><br /><ol><li>The head coach of the combined program shall be employed by the school board of 
 School District No. ".$row['hcoach_dist']."</li>";
 echo "<li>Other joint program personnel, if any, shall be employed as follows:<br />";
echo "<table><tr><th>Position</th><th>Employer</th></tr>";
echo "<tr><td>".$row['jp_personnel1']."</td><td>".$row['jp_employer1']."</td></tr>";
echo "<tr><td>".$row['jp_personnel2']."</td><td>".$row['jp_employer2']."</td></tr>";
echo "<tr><td>".$row['jp_personnel3']."</td><td>".$row['jp_employer3']."</td></tr></table>";
echo "</li>";
echo "<li>Recommendations for employment of personnel by each board shall be in accordance with the board's policies.</li>";
echo "<li>Coaches and other personnel employed by a school district shall meet applicable state requirements.</li>";
echo "</ol>";
echo "<li><u>Control and Supervision of Programs and Participants.</u>  The control and supervision of a combined program, and of the 
behavior of student participants in the program, shall be the responsibility of the host school district.<br />The control and supervision 
of student participants while in transport to and from the host school district shall be the responsibility of the home school district.</li></ul>";
echo "<li><u>Interdistrict Advisory Board.</u>  An Interdistrict Advisory Board may be formed from members of the schools to work on the improvement 
of the various co-sponsored programs.</li>";
echo "<li><u>Resolution of Disputes.</u>  Any disputes relating to this Agreement, or items in this Agreement requiring clarification, will be 
investigated by the school superintendents from each school, and they will present their findings and recommendations to their respective boards.</li>";

$endyear = explode("-",$row['ending']);

echo "<li><u>Term, Dissolution.</u>  The term of this Agreement shall be for school years ".$row['effective']." through ".$row['ending'].".  The agreement 
shall terminate at the end of the last school year specified, unless extended by mutual agreement.  If the parties determine to extend the Agreement beyond
 the period specified, they agree to submit a 'Cooperative Program Renewal Agreement' form to the NSAA Board of Directors prior to (April 1 or June 1 for fall
  activities, September 1 for winter activities, or January 1 for spring activities, preceding the school year or season in which the coop program is to be 
   implemented), ".$endyear[1].".  If the parties determine to dissolve the Agreement at an earlier date, they agree to submit an application requesting dissolution 
   by April 1 of the school year prior to the school year in which dissolution is requested, i.e., April 1, 2011 for dissolution for the 2011-12 school year.  If the
    early dissolution of the Agreement is not approved, the combined program must be offered cooperatively, or not at all, during the remaining terms of the Agreement.</li>";
echo "<li><u>Liability, Insurance.</u>  Nothing contained in this Agreement shall revlieve any party to this Agreement from liability for its negligence or that of its
 officers, agents and employees.  Each party shall carry liability insurance in the amount of $".$row['claimant_ins']." for any claimant and $".$row['claim_ins']." for any
 number of claims arising out of a single occurence.  The policy shall name the officers, agents, and employees of the other party as named insured.  Each party shall
 provide the other party with a certificate evidencing such insurance coverage.</li></ol>";

 echo "<h4><center>SCHOOL INFORMATION</center></h4>";
echo "<h4>School 1:  ".$sch1row['name']."</h4>";
echo "<h5>Number of students enrolled in school</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch1row['currenrolled9g']."</td><td>".$sch1row['currenrolled9b']."</td><td>".$sch1row['currenrolled10g']."</td><td>".$sch1row['currenrolled10b']."</td>
<td>".$sch1row['currenrolled11g']."</td><td>".$sch1row['currenrolled11b']."</td><td>".$sch1row['currenrolled12g']."</td><td>".$sch1row['currenrolled12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch1row['ant1enrolled9g']."</td><td>".$sch1row['ant1enrolled9b']."</td><td>".$sch1row['ant1enrolled10g']."</td><td>".$sch1row['ant1enrolled10b']."</td>
<td>".$sch1row['ant1enrolled11g']."</td><td>".$sch1row['ant1enrolled11b']."</td><td>".$sch1row['ant1enrolled12g']."</td><td>".$sch1row['ant1enrolled12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch1row['ant2enrolled9g']."</td><td>".$sch1row['ant2enrolled9b']."</td><td>".$sch1row['ant2enrolled10g']."</td><td>".$sch1row['ant2enrolled10b']."</td>
<td>".$sch1row['ant2enrolled11g']."</td><td>".$sch1row['ant2enrolled11b']."</td><td>".$sch1row['ant2enrolled12g']."</td><td>".$sch1row['ant2enrolled12b']."</td></tr>";
echo "</table>";

echo "<h5>Number of students participating in activity</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch1row['currparticipating9g']."</td><td>".$sch1row['currparticipating9b']."</td><td>".$sch1row['currparticipating10g']."</td><td>".$sch1row['currparticipating10b']."</td>
<td>".$sch1row['currparticipating11g']."</td><td>".$sch1row['currparticipating11b']."</td><td>".$sch1row['currparticipating12g']."</td><td>".$sch1row['currparticipating12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch1row['ant1participating9g']."</td><td>".$sch1row['ant1participating9b']."</td><td>".$sch1row['ant1participating10g']."</td><td>".$sch1row['ant1participating10b']."</td>
<td>".$sch1row['ant1participating11g']."</td><td>".$sch1row['ant1participating11b']."</td><td>".$sch1row['ant1participating12g']."</td><td>".$sch1row['ant1participating12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch1row['ant2participating9g']."</td><td>".$sch1row['ant2participating9b']."</td><td>".$sch1row['ant2participating10g']."</td><td>".$sch1row['ant2participating10b']."</td>
<td>".$sch1row['ant2participating11g']."</td><td>".$sch1row['ant2participating11b']."</td><td>".$sch1row['ant2participating12g']."</td><td>".$sch1row['ant2participating12b']."</td></tr>";
echo "</table>";

echo "<h4>School 2:  ".$sch2row['name']."</h4>";
echo "<h5>Number of students enrolled in school</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch2row['currenrolled9g']."</td><td>".$sch2row['currenrolled9b']."</td><td>".$sch2row['currenrolled10g']."</td><td>".$sch2row['currenrolled10b']."</td>
<td>".$sch2row['currenrolled11g']."</td><td>".$sch2row['currenrolled11b']."</td><td>".$sch2row['currenrolled12g']."</td><td>".$sch2row['currenrolled12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch2row['ant1enrolled9g']."</td><td>".$sch2row['ant1enrolled9b']."</td><td>".$sch2row['ant1enrolled10g']."</td><td>".$sch2row['ant1enrolled10b']."</td>
<td>".$sch2row['ant1enrolled11g']."</td><td>".$sch2row['ant1enrolled11b']."</td><td>".$sch2row['ant1enrolled12g']."</td><td>".$sch2row['ant1enrolled12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch2row['ant2enrolled9g']."</td><td>".$sch2row['ant2enrolled9b']."</td><td>".$sch2row['ant2enrolled10g']."</td><td>".$sch2row['ant2enrolled10b']."</td>
<td>".$sch2row['ant2enrolled11g']."</td><td>".$sch2row['ant2enrolled11b']."</td><td>".$sch2row['ant2enrolled12g']."</td><td>".$sch2row['ant2enrolled12b']."</td></tr>";
echo "</table>";

echo "<h5>Number of students participating in activity</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch2row['currparticipating9g']."</td><td>".$sch2row['currparticipating9b']."</td><td>".$sch2row['currparticipating10g']."</td><td>".$sch2row['currparticipating10b']."</td>
<td>".$sch2row['currparticipating11g']."</td><td>".$sch2row['currparticipating11b']."</td><td>".$sch2row['currparticipating12g']."</td><td>".$sch2row['currparticipating12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch2row['ant1participating9g']."</td><td>".$sch2row['ant1participating9b']."</td><td>".$sch2row['ant1participating10g']."</td><td>".$sch2row['ant1participating10b']."</td>
<td>".$sch2row['ant1participating11g']."</td><td>".$sch2row['ant1participating11b']."</td><td>".$sch2row['ant1participating12g']."</td><td>".$sch2row['ant1participating12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch2row['ant2participating9g']."</td><td>".$sch2row['ant2participating9b']."</td><td>".$sch2row['ant2participating10g']."</td><td>".$sch2row['ant2participating10b']."</td>
<td>".$sch2row['ant2participating11g']."</td><td>".$sch2row['ant2participating11b']."</td><td>".$sch2row['ant2participating12g']."</td><td>".$sch2row['ant2participating12b']."</td></tr>";
echo "</table>";

if ($sch3row['name'] != "") {
echo "<h4>School 3:  ".$sch3row['name']."</h4>";
echo "<h5>Number of students enrolled in school</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch3row['currenrolled9g']."</td><td>".$sch3row['currenrolled9b']."</td><td>".$sch3row['currenrolled10g']."</td><td>".$sch3row['currenrolled10b']."</td>
<td>".$sch3row['currenrolled11g']."</td><td>".$sch3row['currenrolled11b']."</td><td>".$sch3row['currenrolled12g']."</td><td>".$sch3row['currenrolled12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch3row['ant1enrolled9g']."</td><td>".$sch3row['ant1enrolled9b']."</td><td>".$sch3row['ant1enrolled10g']."</td><td>".$sch3row['ant1enrolled10b']."</td>
<td>".$sch3row['ant1enrolled11g']."</td><td>".$sch3row['ant1enrolled11b']."</td><td>".$sch3row['ant1enrolled12g']."</td><td>".$sch3row['ant1enrolled12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch3row['ant2enrolled9g']."</td><td>".$sch3row['ant2enrolled9b']."</td><td>".$sch3row['ant2enrolled10g']."</td><td>".$sch3row['ant2enrolled10b']."</td>
<td>".$sch3row['ant2enrolled11g']."</td><td>".$sch3row['ant2enrolled11b']."</td><td>".$sch3row['ant2enrolled12g']."</td><td>".$sch3row['ant2enrolled12b']."</td></tr>";
echo "</table>";

echo "<h5>Number of students participating in activity</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch3row['currparticipating9g']."</td><td>".$sch3row['currparticipating9b']."</td><td>".$sch3row['currparticipating10g']."</td><td>".$sch3row['currparticipating10b']."</td>
<td>".$sch3row['currparticipating11g']."</td><td>".$sch3row['currparticipating11b']."</td><td>".$sch3row['currparticipating12g']."</td><td>".$sch3row['currparticipating12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch3row['ant1participating9g']."</td><td>".$sch3row['ant1participating9b']."</td><td>".$sch3row['ant1participating10g']."</td><td>".$sch3row['ant1participating10b']."</td>
<td>".$sch3row['ant1participating11g']."</td><td>".$sch3row['ant1participating11b']."</td><td>".$sch3row['ant1participating12g']."</td><td>".$sch3row['ant1participating12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch3row['ant2participating9g']."</td><td>".$sch3row['ant2participating9b']."</td><td>".$sch3row['ant2participating10g']."</td><td>".$sch3row['ant2participating10b']."</td>
<td>".$sch3row['ant2participating11g']."</td><td>".$sch3row['ant2participating11b']."</td><td>".$sch3row['ant2participating12g']."</td><td>".$sch3row['ant2participating12b']."</td></tr>";
echo "</table>";
}

if ($sch4row['name'] != "") {
echo "<h4>School 3:  ".$sch4row['name']."</h4>";
echo "<h5>Number of students enrolled in school</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch4row['currenrolled9g']."</td><td>".$sch4row['currenrolled9b']."</td><td>".$sch4row['currenrolled10g']."</td><td>".$sch4row['currenrolled10b']."</td>
<td>".$sch4row['currenrolled11g']."</td><td>".$sch4row['currenrolled11b']."</td><td>".$sch4row['currenrolled12g']."</td><td>".$sch4row['currenrolled12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch4row['ant1enrolled9g']."</td><td>".$sch4row['ant1enrolled9b']."</td><td>".$sch4row['ant1enrolled10g']."</td><td>".$sch4row['ant1enrolled10b']."</td>
<td>".$sch4row['ant1enrolled11g']."</td><td>".$sch4row['ant1enrolled11b']."</td><td>".$sch4row['ant1enrolled12g']."</td><td>".$sch4row['ant1enrolled12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch4row['ant2enrolled9g']."</td><td>".$sch4row['ant2enrolled9b']."</td><td>".$sch4row['ant2enrolled10g']."</td><td>".$sch4row['ant2enrolled10b']."</td>
<td>".$sch4row['ant2enrolled11g']."</td><td>".$sch4row['ant2enrolled11b']."</td><td>".$sch4row['ant2enrolled12g']."</td><td>".$sch4row['ant2enrolled12b']."</td></tr>";
echo "</table>";

echo "<h5>Number of students participating in activity</h5>";
echo "<table style='text-align:center;border:1px solid black;'><tr><th></th><th>Grade 9 Girls</th><th>Grade 9 Boys</th><th>Grade 10 Girls</th><th>Grade 10 Boys</th>
<th>Grade 11 Girls</th><th>Grade 11 Boys</th><th>Grade 12 Girls</th><th>Grade 12 Boys</th></tr>";
echo "<tr><td>Current School Year:</td><td>".$sch4row['currparticipating9g']."</td><td>".$sch4row['currparticipating9b']."</td><td>".$sch4row['currparticipating10g']."</td><td>".$sch4row['currparticipating10b']."</td>
<td>".$sch4row['currparticipating11g']."</td><td>".$sch4row['currparticipating11b']."</td><td>".$sch4row['currparticipating12g']."</td><td>".$sch4row['currparticipating12b']."</td></tr>";
echo "<tr><td>Anticipated Next Year:</td><td>".$sch4row['ant1participating9g']."</td><td>".$sch4row['ant1participating9b']."</td><td>".$sch4row['ant1participating10g']."</td><td>".$sch4row['ant1participating10b']."</td>
<td>".$sch4row['ant1participating11g']."</td><td>".$sch4row['ant1participating11b']."</td><td>".$sch4row['ant1participating12g']."</td><td>".$sch4row['ant1participating12b']."</td></tr>"; 
echo "<tr><td>Anticipated Two Years Hence:</td><td>".$sch4row['ant2participating9g']."</td><td>".$sch4row['ant2participating9b']."</td><td>".$sch4row['ant2participating10g']."</td><td>".$sch4row['ant2participating10b']."</td>
<td>".$sch4row['ant2participating11g']."</td><td>".$sch4row['ant2participating11b']."</td><td>".$sch4row['ant2participating12g']."</td><td>".$sch4row['ant2participating12b']."</td></tr>";
echo "</table>";

echo "</div>";
}
}
if (isset($_GET['sub'])) {
$formID = $_GET['cformID'];

$sql4 = "UPDATE coopform SET submit_date = CURRENT_TIMESTAMP WHERE formID = $formID";
$result4 = mysql_query($sql4);
if (mysql_num_rows($result4) > 0) {
echo "<h2>Your form has been submitted and is awaiting approval.  Please allow two weeks for processing.  An email notification 
will be sent to you upon approval of your agreement.</h2>";
}
}


