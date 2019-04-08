<?php
/********************************************************
coopform.php
Cooperative Sponsorship Agreement (user must be logged in and an AD)
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
?>
<script type="text/javascript" src="coopscript.js">
</script>
<?php

//Get Header, based on if this is a printer-friendly version or not
if($print==1) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);

//Echo Header
echo $init_html;
echo $header;

$_SESSION['schoolid'] = $schoolid;
$sess = $_GET['session'];

//If returning to complete a form, set session variable with cformID
if (isset($_GET['cformID'])) {
$_SESSION['cformID'] = $_GET['cformID'];
}

//Display form guidelines
if (!isset($_POST['continue'])) {
?>
<div style="width:700px;height:400px;overflow:auto;text-align:justify;margin-left:auto;margin-right:auto;padding:4px;">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?session='.$sess; ?>">
<p><center><b>NEBRASKA SCHOOL ACTIVITIES ASSOCIATION GUIDELINES FOR COOPERATIVE SPONSORSHIP</b>
</center><br />
<b><center><u>Purpose:</u></center></b><br /><br />
  The philosophy of the Nebraska School Activities Association 
  is to provide an opportunity for high school students to participate 
  in a variety of athletic and non-athletic activities.  Through cooperative 
  sponsorship, the opportunity for student participation will be maintained, 
  or increased, by permitting students who do not have a program available in 
  their school to go to another school for athletic and non-athletic activity 
  participation.  The problem of declining enrollment, the inherent financial 
  ramifications of supporting the cost of the program, the lack of facilities 
  and equipment, and the problem of providing quality coaching staff when the 
  number of teaching positions is reduced, make cooperative sponsorship desirable.
<br /><br />  
  <b>Schools will not be permitted to use cooperative sponsorship to gain an 
  advantage over other member schools.</b><br />
<br /><br />
<b><center><u>Guidelines:</u></center></b><br />
<ol>
<li>In activities that have one classification for competition, a 
  maximum of four schools may combine and form a cooperative program.  In all 
  other activities, no more than three schools shall be permitted to combine.</li>
<li>The combining schools must be in the same geographical area, and the 
  school districts must be contiguous or all schools located in the same school 
  district.  If a school has attempted to cooperatively sponsor a program with a
  contiguous district and was denied, the contiguous requirement may be waived.</li>
<li><b><u>The cooperative sponsorship agreement shall be for a minimum of two 
  years.</u></b>  The cooperative agreement may be voided at any time by mutual 
  agreement of both/all schools <b><u>and approval by the Board of Directors.</u></b>
    No other cooperative agreement in the same activity may be made with another 
  school until the original two-year period elapses.</li>
<li>The cooperative agreement will be for each activity.  A school may have
  a cooperative agreement with one school in a particular activity and with 
  another school in another activity.</li>
<li>Where there is an absence of an effective program in one school, a 
  cooperative program may be established, provided a need is shown to the 
  Board of Directors.  Examples which may constitute need are:  1) insufficient 
  numbers;  2) lack of staff;  and 3) lack of facilities.</li>
<li>In multi-school districts, the central administration must designate the 
  schools which may request permission to cooperatively sponsor activities.</li>
<li>If a school in one district wishes to join with a school in a 
  multi-school district in a cooperatively sponsored activity, the school must 
  join with the nearest high school in the multi-school district which offers 
  the activity.</li>
<li>If a school previously has offered a program in an activity and there 
  has been no significant decrease in high school enrollment, the school would 
  not be permitted to participate in a cooperative program.</li>
<li>If a school has previously played eleven-man football and has 
  sufficient interest and enrollment for eight-man football, the school 
  would not be permitted to cooperatively sponsor football with another 
  school.  If two/three schools which have previously played six-man football 
  agree to cooperatively sponsor football, the cooperative team may continue to 
  play six-man football if the enrollment requirement is met.  If two/three 
  schools which have previously played eight-man football agree to cooperatively 
  sponsor football, the cooperative team may play eight-man football if the 
  combined enrollment of the schools is less than 83.</li>
<li>If, through a cooperative sponsorship, the number participating in a 
  program in either school would be reduced, the request would not be approved.</li>
<li>The enrollment (grades 9, 10, and 11, as taken from the forms 
  sent to the NSAA office for classification purposes) of all of the schools 
  entering into a cooperative sponsorship will be combined to determine the 
  class in which the combined program will participate.  Each school will 
  continue to participate in its class in all activities except where the 
  cooperative sponsorship applies.</li>
<li>All schools of a cooperative program are required to pay the 
  yearly registration fee.</li>
</ol>
<br />
<b><center><u>Problems Schools Need to Resolve Before Entering Into a 
  Cooperative Program:</u></center></b><br />
<ol>
<li>If you already have a program, are students from another school 
  going to replace students from your community?</li>
<li>Who will pay the cost of equipment and travel?</li>
<li>How will gate receipts be dispersed?</li>
<li>Who is responsible for the cost of travel to and from practice?</li>
<li>Where will practice be held?</li>
<li>Where will contests be played?</li>
<li>Which school's identity will be used?  Mascot, colors, etc.</li>
<li>Are local eligibility rules, lettering guidelines, etc., the same at both/all schools?</li>
<li>Selection of cheerleaders.  Who's eligible?</li>
<li>Will activity tickets and/or season tickets be honored?</li>
<li>How will coaches be employed and paid?</li>
<li>Insurance.</li>
<li>If students are combined for girls basketball, for example, the boys' teams may be assigned 
  to different districts--possibly even different classes.</li>
<li>Expenses for facilities, lights, heating, showers, towels, laundry, etc., 
 including maintenance of practice and playing facilities.</li>
<li>Expenses for scouting, coaches' meetings, etc.  Who is responsible?</li>
<li>Contracts with other schools, officials, etc.</li>
<li>Responsibilities for hosting and supervising events.</li>
<li>Resolution of disputes.</li>
<li>Which school will handle eligibility?</li>
</ol>
<br />
<b><center><u>Application:</u></center></b><br />
<ol>
<li>The application form, available from the NSAA office, must be completed by 
  both/all schools and submitted to the NSAA.  A copy of the action item from 
  your Board of Education minutes, stating the application was approved, must 
  be attached.</li>
<li>Applications are to be initiated by both/all Boards of Education.  If possible, the 
  applications should be made by April 1 preceding the year in which the 
  cooperative agreement is to be implemented.  <b><u>If it is not possible to submit 
  the application by April 1, the applications must be submitted prior to 
  district assignments being made.  These dates are: June 1 for fall activities, 
  September 1 for winter activities and January 1 for spring activities.</u></b></li>
<li>Member schools may apply for cooperative sponsorship in any activity 
 recognized by the NSAA.</li>
<li>To renew an existing cooperative program, the Superintendents of the 
  schools involved must submit a "Cooperative Program Renewal Agreement" form.  
  It must be submitted to the Board of Directors (by June 1 for fall activities,
  September 1 for winter activities or January 1 for spring activities) preceding
  the school year or season in which the coop program is to be implemented.</li>
<li>When completing the application form, be specific when listing the 
  activities.  Example:  boys' and/or girls' cross country rather than cross 
  country.</li>
<li>Be specific when giving the school year(s) for the coop program.</li>
</ol><br />
<b><center>KEY FOR ACTIVITY ABBREVIATIONS (see next page)-</center></b><br />
<table style="margin-left:auto;margin-right:auto;">
<tr><td>FB6-Football 6-man</td><td>FB8-Football 8-man</td><td>FB11-Football 11-man</td></tr>
<tr><td>VB-Volleyball</td><td>BCC-Boys Cross Country</td><td>GCC-Girls Cross Country</td></tr>
<tr><td>GGO-Girls Golf</td><td>BTE-Boys Tennis</td><td>GSB-Girls Softball</td></tr>
<tr><td>BSW-Boys Swimming</td><td>GSW-Girls Swimming</td><td>BA-Baseball</td><tr>
<tr><td>BTR-Boys Track</td><td>GTR-Girls Track</td><td>GTE-Girls Tennis</td></tr>
<tr><td>BGO-Boys Golf</td><td>BSO-Boys Soccer</td><td>GSO-Girls Soccer</td></tr>
<tr><td>PP-Play Production</td><td>SP-Speech</td><td>DEB-Debate</td></tr>
<tr><td>VMU-Vocal Music</td><td>IMU-Instrumental Music</td><td>J-Journalism</td><tr>
<tr><td>WR-Wrestling</td><td>BBB-Boys Basketball</td><td>BSW-Boys Swimming</td></tr>
<tr><td>GSW-Girls Swimming</td><td>BA-Baseball</td><td>BTR-Boys Track</td></tr>
<tr><td>GTR-Girls Track</td><td>GTE-Girls Tennis</td><td>BGO-Boys Golf</td></tr>
<tr><td>BSO-Boys Soccer</td><td>GSO-Girls Soccer</td><td></td></tr>
</table>
<br />
<center>
<input type="submit" name="continue" value="Continue" />
</center>
<input type="hidden" name="next" />
</p>
</form>
</div>

<?php
} else {

//If user has hit continue, start a new form
if (isset($_POST['next'])) {

//If formID is set, query to get current information
  if (isset($_SESSION['cformID'])) {
  $_SESSION['formID'] = $_SESSION['cformID'];

$formqry = "SELECT * FROM coopform WHERE formID = ".$_SESSION['formID'];
$formresult = mysql_query($formqry);
$formrow = mysql_fetch_assoc($formresult);
$_SESSION['start_date'] = $formrow['start_date'];
$_SESSION['submit_date'] = $formrow['submit_date'];
$_SESSION['appr_date'] = $formrow['appr_date'];
$_SESSION['activitiesID'] = $formrow['activitiesID'];
$_SESSION['purpose1'] = $formrow['purpose1'];
$_SESSION['purpose2'] = $formrow['purpose2'];
$_SESSION['purpose3'] = $formrow['purpose3'];
$_SESSION['purpose4'] = $formrow['purpose4'];
$_SESSION['teamname'] = $formrow['teamname'];
$_SESSION['teammascot'] = $formrow['teammascot'];
$_SESSION['contract_dist'] = $formrow['contract_dist'];
$_SESSION['reimburse'] = $formrow['reimburse'];
$_SESSION['teamcolors'] = $formrow['teamcolors'];
$_SESSION['dtransexp_all'] = $formrow['dtransexp_all'];
$_SESSION['atransexp_all'] = $formrow['atransexp_all'];
$_SESSION['specbusexp_all'] = $formrow['specbusexp_all'];
$_SESSION['facilexp_all'] = $formrow['facilexp_all'];
$_SESSION['banqexp_all'] = $formrow['banqexp_all'];
$_SESSION['scoutexp_all'] = $formrow['scoutexp_all'];
$_SESSION['otherexp_all'] = $formrow['otherexp_all'];
$_SESSION['gate_all'] = $formrow['gate_all'];
$_SESSION['insufgate_all'] = $formrow['insufgate_all'];
$_SESSION['refexp_all'] = $formrow['refexp_all'];
$_SESSION['suppexp_all'] = $formrow['suppexp_all'];
$_SESSION['salexp_all'] = $formrow['salexp_all'];
$_SESSION['hcoach_dist'] = $formrow['hcoach_dist'];
$_SESSION['jp_personnel1'] = $formrow['jp_personnel1'];
$_SESSION['jp_personnel2'] = $formrow['jp_personnel2'];
$_SESSION['jp_personnel3'] = $formrow['jp_personnel3'];
$_SESSION['jp_employer1'] = $formrow['jp_employer1'];
$_SESSION['jp_employer2'] = $formrow['jp_employer2'];
$_SESSION['jp_employer3'] = $formrow['jp_employer3'];
$_SESSION['claimant_ins'] = $formrow['claimant_ins'];
$_SESSION['claim_ins'] = $formrow['claim_ins'];
$_SESSION['effective'] = $formrow['effective'];
$_SESSION['ending'] = $formrow['ending'];

$actqry = "SELECT * FROM coopformactivities WHERE activitiesID = ".$_SESSION['activitiesID'];
$actresult = mysql_query($actqry);
$actrow = mysql_fetch_assoc($actresult);
$_SESSION['fb'] = $actrow['fb'];
$_SESSION['fb_type'] = $actrow['fb_type'];
$_SESSION['ba'] = $actrow['ba'];
$_SESSION['bbb'] = $actrow['bbb'];
$_SESSION['bbg'] = $actrow['bbg'];
$_SESSION['ccb'] = $actrow['ccb'];
$_SESSION['ccg'] = $actrow['ccg'];
$_SESSION['de'] = $actrow['de'];
$_SESSION['go_b'] = $actrow['go_b'];
$_SESSION['go_g'] = $actrow['go_g'];
$_SESSION['jo'] = $actrow['jo'];
$_SESSION['pp'] = $actrow['pp'];
$_SESSION['sb'] = $actrow['sb'];
$_SESSION['sob'] = $actrow['sob'];
$_SESSION['sog'] = $actrow['sog'];
$_SESSION['sp'] = $actrow['sp'];
$_SESSION['swg'] = $actrow['swg'];
$_SESSION['swb'] = $actrow['swb'];
$_SESSION['te_b'] = $actrow['te_b'];
$_SESSION['te_g'] = $actrow['te_g'];
$_SESSION['trb'] = $actrow['trb'];
$_SESSION['trg'] = $actrow['trg'];
$_SESSION['vb'] = $actrow['vb'];
$_SESSION['wr'] = $actrow['wr'];
$_SESSION['vm'] = $actrow['vm'];
$_SESSION['im'] = $actrow['im'];

$schqry = "SELECT * FROM coopformschools WHERE formID = ".$_SESSION['formID'];
$schresult = mysql_query($schqry);

//If school information has been entered, get it to display in form
if ($schresult) {

while ($schrow = mysql_fetch_assoc($schresult)) {

  if ($schrow['formschoolnum'] == '1') {
    $sch1qry = "SELECT * FROM coopformschools WHERE formschoolnum = '1' AND formID = ".$_SESSION['formID'];
    $sch1res = mysql_query($sch1qry);
    $sch1row = mysql_fetch_assoc($sch1res);
	$_SESSION['sch1id'] = $sch1row['id'];
	$_SESSION['sch1name'] = $sch1row['name'];
	$_SESSION['sch1dist'] = $sch1row['dist'];
}

  if ($schrow['formschoolnum'] == '2') {
    $sch2qry = "SELECT * FROM coopformschools WHERE formschoolnum = '2' AND formID = ".$_SESSION['formID'];
    $sch2res = mysql_query($sch2qry);
    $sch2row = mysql_fetch_assoc($sch2res);
	$_SESSION['sch2id'] = $sch2row['id'];
	$_SESSION['sch2name'] = $sch2row['name'];
	$_SESSION['sch2dist'] = $sch2row['dist'];
}

  if ($schrow['formschoolnum'] == '3') {
    $sch3qry = "SELECT * FROM coopformschools WHERE formschoolnum = '3' AND formID = ".$_SESSION['formID'];
    $sch3res = mysql_query($sch3qry);
	$sch3row = mysql_fetch_assoc($sch3res);
	$_SESSION['sch3id'] = $sch3row['id'];
	$_SESSION['sch3name'] = $sch3row['name'];
	$_SESSION['sch3dist'] = $sch3row['dist'];
}

  if ($schrow['formschoolnum'] == '4') {
    $sch4qry = "SELECT * FROM coopformschools WHERE formschoolnum = '4' AND formID = ".$_SESSION['formID'];
    $sch4res = mysql_query($sch4qry);
    $sch4row = mysql_fetch_assoc($sch4res);
	$_SESSION['sch4id'] = $sch4row['id'];
	$_SESSION['sch4name'] = $sch4row['name'];
	$_SESSION['sch4dist'] = $sch4row['dist'];
}
}

}
} else {
  
  $qry7 = "INSERT INTO coopform (start_date) VALUES(CURRENT_TIMESTAMP)";
  $result7 = mysql_query($qry7);

  $formID = mysql_insert_id();
  $_SESSION['formID'] = $formID;

  }
 //Display the first part of form: schools, activities, and years.
?>
<div style="width:700px;text-align:center;margin-left:auto;margin-right:auto;">
<form method="post" id="first" onsubmit="return validateCoopForm1(this)"
action="<?php echo $_SERVER['PHP_SELF'].'?session='.$sess; ?>" >
<h2><center>AGREEMENT FOR COOPERATIVE SPONSORSHIP</center></h2>
<div style="width:700px;text-align:center;margin-left:auto;margin-right:auto;border:2px solid black;margin-bottom:5px;">
<h3>Parties Involved:</h3>
<h4>Please select names of the schools involved.
  (Agreement must be between at least two schools.)</h4>
<table style="margin-left:auto;margin-right:auto;">
<tr>
  <td>School 1: </td>
  <td>
  <?php 
  $sql="SELECT school FROM headers WHERE id = $schoolid";
  $result=mysql_query($sql);
  $row=mysql_fetch_assoc($result);
  echo $row['school'];
  echo '<input type="hidden" name="sch1_name" value="'.$row['school'].'" />';
  echo '<input type="hidden" name="sch1_id" value="'.$schoolid.'" />';

  ?>
  </td>
</tr>
<tr>
  <td>School 2: </td>
  <td><select name="sch2_name">
  <?php 
  $sql="SELECT school, id FROM headers ORDER BY school";
  $result=mysql_query($sql);
  while($row=mysql_fetch_assoc($result))
  {
      echo "<option";
      if (isset($_SESSION['sch2name']) && ($_SESSION['sch2name'] == $row['school'])) echo ' selected="selected"';
	  echo ">".$row['school']."</option>";
  }
  ?>
  </select>
  </td>
</tr>
<tr>
  <td>School 3: </td>
  <td><select name="sch3_name">
    <option>None</option>

<?php

  $sql="SELECT school, id FROM headers ORDER BY school";
  $result=mysql_query($sql);
  while($row=mysql_fetch_assoc($result))
  {
      echo "<option";
      if (isset($_SESSION['sch3name']) && ($_SESSION['sch3name'] == $row['school'])) echo ' selected="selected"';
	  echo ">".$row['school']."</option>";
 }
  
  ?>
  </select>
  </td>
</tr>
<tr>
  <td>School 4: </td>
  <td><select name="sch4_name">
      <option>None</option>
  <?php 
  $sql="SELECT school, id FROM headers ORDER BY school";
  $result=mysql_query($sql);
  while($row=mysql_fetch_assoc($result))
  {
      echo "<option";
      if (isset($_SESSION['sch4name']) && ($_SESSION['sch4name'] == $row['school'])) echo ' selected="selected"';
	  echo ">".$row['school']."</option>";

  }
  ?>
    </select>	
  </td>
</tr>
</table>
</div>

<div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px black solid">
<h3>Activities:</h3>
<a href="#" onClick="select_all('activity', '1');">Select All</a> | <a href="#" onClick="select_all('activity',
'0');">Unselect All</a>
<table>
<tr>
<td style="width:450px;">
  <h4>Fall</h4>
  <table>
  <tr>
  <td><input type="checkbox" name="activity[]" value="fb6" <?php if (isset($_SESSION['fb']) && $_SESSION['fb'] == 'x') { 
    if ($_SESSION['fb_type'] = '6') { echo 'checked="checked"'; }} ?> >FB6-Football 6-man</input></td>
  <td><input type="checkbox" name="activity[]" value="fb8" <?php if (isset($_SESSION['fb']) && $_SESSION['fb'] == 'x') { 
    if ($_SESSION['fb_type'] = '8') { echo 'checked="checked"'; }} ?>>FB8-Football 8-man</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="fb11" <?php if (isset($_SESSION['fb']) && $_SESSION['fb'] == 'x') { 
    if ($_SESSION['fb_type'] = '11') { echo 'checked="checked"'; }} ?>>FB11-Football 11-man</input></td>  
  <td><input type="checkbox" name="activity[]" value="vb" <?php if (isset($_SESSION['vb']) && $_SESSION['vb'] == 'x') { 
    echo 'checked="checked"'; } ?>>VB-Volleyball</input></td>	
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="ccb" <?php if (isset($_SESSION['ccb']) && $_SESSION['ccb'] == 'x') { 
    echo 'checked="checked"'; } ?>>BCC-Boys Cross Country</input></td>
  <td><input type="checkbox" name="activity[]" value="ccg" <?php if (isset($_SESSION['ccg']) && $_SESSION['ccg'] == 'x') { 
    echo 'checked="checked"'; } ?>>GCC-Girls Cross Country</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="go_g" <?php if (isset($_SESSION['go_g']) && $_SESSION['go_g'] == 'x') { 
    echo 'checked="checked"'; } ?>>GGO-Girls Golf</input></td>
  <td><input type="checkbox" name="activity[]" value="te_b" <?php if (isset($_SESSION['te_b']) && $_SESSION['te_b'] == 'x') { 
    echo 'checked="checked"'; } ?>>BTE-Boys Tennis</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="sb" <?php if (isset($_SESSION['sb']) && $_SESSION['sb'] == 'x') { 
    echo 'checked="checked"'; } ?>>GSB-Girls Softball</input></td>
  <td><input type="checkbox" name="activity[]" value="pp" <?php if (isset($_SESSION['pp']) && $_SESSION['pp'] == 'x') { 
    echo 'checked="checked"'; } ?>>PP-Play Production</input></td>  
  </tr>
  </table>
  </div>
</td>
<td style="width:450px;">  
  <h4>Winter</h4>
  <table>
  <tr>
  <td><input type="checkbox" name="activity[]" value="swb" <?php if (isset($_SESSION['swb']) && $_SESSION['swb'] == 'x') { 
    echo 'checked="checked"'; } ?>>BSW-Boys Swimming</input></td>
  <td><input type="checkbox" name="activity[]" value="swg" <?php if (isset($_SESSION['swg']) && $_SESSION['swg'] == 'x') { 
    echo 'checked="checked"'; } ?>>GSW-Girls Swimming</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="wr" <?php if (isset($_SESSION['wr']) && $_SESSION['wr'] == 'x') { 
    echo 'checked="checked"'; } ?>>WR-Wrestling</input></td>  
  <td><input type="checkbox" name="activity[]" value="bbb" <?php if (isset($_SESSION['bbb']) && $_SESSION['bbb'] == 'x') { ?> 
    checked="checked" <?php } ?>>BBB-Boys Basketball</input></td>	
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="bbg" <?php if (isset($_SESSION['bbg']) && $_SESSION['bbg'] == 'x') { 
    echo 'checked="checked"'; } ?>>GBB-Girls Basketball</input></td>
  <td><input type="checkbox" name="activity[]" value="sp" <?php if (isset($_SESSION['sp']) && $_SESSION['sp'] == 'x') { 
    echo 'checked="checked"'; } ?>>SP-Speech</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="de" <?php if (isset($_SESSION['de']) && $_SESSION['de'] == 'x') { 
    echo 'checked="checked"'; } ?>>DEB-Debate</input></td>
  </tr>
  </table>
  </div>
</td>
</tr>
<tr>
<td style="width:375px;"> 
  <h4>Spring</h4>
  <table>
  <tr>
  <td><input type="checkbox" name="activity[]" value="ba" <?php if (isset($_SESSION['ba']) && $_SESSION['ba'] == 'x') { 
    echo 'checked="checked"'; } ?>>BA-Baseball</input></td>
  <td><input type="checkbox" name="activity[]" value="trb" <?php if (isset($_SESSION['trb']) && $_SESSION['trb'] == 'x') { 
    echo 'checked="checked"'; } ?>>BTR-Boys Track</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="trg" <?php if (isset($_SESSION['trg']) && $_SESSION['trg'] == 'x') { 
    echo 'checked="checked"'; } ?>>GTR-Girls Track</input></td>  
  <td><input type="checkbox" name="activity[]" value="te_g" <?php if (isset($_SESSION['te_g']) && $_SESSION['te_g'] == 'x') { 
    echo 'checked="checked"'; } ?>>GTE-Girls Tennis</input></td>	
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="go_b" <?php if (isset($_SESSION['go_b']) && $_SESSION['go_b'] == 'x') { 
    echo 'checked="checked"'; } ?>>BGO-Boys Golf</input></td>
  <td><input type="checkbox" name="activity[]" value="sob" <?php if (isset($_SESSION['sob']) && $_SESSION['sob'] == 'x') { 
    echo 'checked="checked"'; } ?>>BSO-Boys Soccer</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity[]" value="sog" <?php if (isset($_SESSION['sog']) && $_SESSION['sog'] == 'x') { 
    echo 'checked="checked"'; } ?>>GSO-Girls Soccer</input></td>
  </tr>
  </table>
  </div>
</td>
<td style="width:375px;">  
  <h4>Other</h4>
  <table>
  <tr>
  <td><input type="checkbox" name="activity" value="vm" <?php if (isset($_SESSION['vm']) && $_SESSION['vm'] == 'x') { 
    echo 'checked="checked"'; } ?>>VMU-Vocal Music</input></td>
  <td><input type="checkbox" name="activity" value="im" <?php if (isset($_SESSION['im']) && $_SESSION['im'] == 'x') { 
    echo 'checked="checked"'; } ?>>IMU-Instrumental Music</input></td>
  </tr>
  <tr>
  <td><input type="checkbox" name="activity" value="jo" <?php if (isset($_SESSION['jo']) && $_SESSION['jo'] == 'x') { 
    echo 'checked="checked"'; } ?>>J-Journalism</input></td>  
  </tr>
  </table>
  </div>
</td>
</tr>
</table>  
 </div> 
<?php

$year1 = date("Y");
$year2 = $year1 + 1;
$year3 = $year2 + 1;
$year4 = $year3 + 1;
$year5 = $year4 + 1;

echo '<div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">';
echo '<h3>Select schools years to be covered:</h3>';
echo '<input type="checkbox" name="years[]" value="'.$year1.' - '.$year2.'"';
  if (isset($_SESSION['effective'])) {
    if ($_SESSION['effective'] == $year1.' - '.$year2) { 
      echo 'checked="checked"'; 
	  }
    } 
echo '>'.$year1.' - '.$year2.'</input>';
echo '<input type="checkbox" name="years[]" value="'.$year2.' - '.$year3.'"';
  if (isset($_SESSION['effective'])) { 
    if ($_SESSION['effective'] == $year2.' - '.$year3 || $_SESSION['ending'] == $year2.' - '.$year3) { 
	  echo 'checked="checked"'; 
	  }
	} 
echo '>'.$year2.' - '.$year3.'</input>';
echo '<input type="checkbox" name="years[]" value="'.$year3.' - '.$year4.'"';
  if (isset($_SESSION['effective'])) { 
    if ($_SESSION['effective'] == $year3.' - '.$year4 || $_SESSION['ending'] == $year3.' - '.$year4) { 
	  echo 'checked="checked"'; 
	  }
	} 
echo '>'.$year3.' - '.$year4.'</input>';
echo '<input type="checkbox" name="years[]" value="'.$year4.' - '.$year5.'"';
  if (isset($_SESSION['effective'])) { 
    if ($_SESSION['effective'] == $year4.' - '.$year5 || $_SESSION['ending'] == $year4.' - '.$year5) { 
	  echo 'checked="checked"'; 
	  }
	} 
echo '>'.$year4.' - '.$year5.'</input>';
echo '</div>';
?>
<input type="hidden" name="continue" />
<input type="submit" name="continue1" value="Continue" />
<input type="submit" name="save1" value="Save & Continue Later" />

</form>
</div>
  
<?php
} 
}

//Save part one information in database
if (isset($_POST['save1']) || isset($_POST['continue1'])) {

  $sch1_name = addslashes($_POST['sch1_name']);
  $sch2_name = addslashes($_POST['sch2_name']);
  $sch3_name = addslashes($_POST['sch3_name']);
  $sch4_name = addslashes($_POST['sch4_name']);
  $sch1_id = $_POST['sch1_id'];
      
  $schid2res = mysql_query("SELECT id FROM headers WHERE school = '$sch2_name'");
  $schid2 = mysql_fetch_array($schid2res);
  $sch2_id = $schid2[0];
  
  $schid3res = mysql_query("SELECT id FROM headers WHERE school = '$sch3_name'");
  if ($schid3res) {
  $schid3 = mysql_fetch_array($schid3res);
  $sch3_id = $schid3[0];
  }
  
  $schid4res = mysql_query("SELECT id FROM headers WHERE school = '$sch4_name'");
  if ($schid4res) {
  $schid4 = mysql_fetch_array($schid4res);
  $sch4_id = $schid4[0];
  }  
  
  $years = $_POST['years'];
  
  $activity = $_POST['activity'];
  
  $endyears = count((array)$years) - 1; 
  $effective = $years[0];
  $ending = $years[$endyears];
  
  $yrqry = "UPDATE coopform SET effective = '$effective', ending = '$ending' WHERE formID = ".$_SESSION['formID'];
  $yrresult = mysql_query($yrqry);
  
  if (isset($_SESSION['activitiesID'])) {
    
    if (count((array)$activity) > 1) {
  
      $qry1 = "INSERT INTO coopformactivities (multiple) VALUES('Y')";
	  $result1 = mysql_query($qry1);
	
	  } else {
	
	  $qry1 = "INSERT INTO coopformactivities (multiple) VALUES('N')";
	  $result1 = mysql_query($qry1);
	
	}
	
	for ($i=0; $i < count($activity); $i++) {
  
    $act = $activity[$i];
	if (preg_match('/^fb/', $act)) {
	  $act = 'fb';
	  $type = explode('b', $activity[$i]);
	  $fb_type = $type[1];
	  $fbqry = "UPDATE coopformactivities SET fb_type = '$fb_type', fb = 'x' WHERE activitiesID = ".$_SESSION['activityID'];
	  $fbresult = mysql_query($fbqry);
	} else {
	
    $qry2 = "UPDATE coopformactivities SET $act = 'x' WHERE activitiesID = ".$_SESSION['activityID'];
	$result2 = mysql_query($qry2);
	
	}	
	}
	} else {	
    
  
    if (count((array)$activity) > 1) {
  
    $qry1 = "INSERT INTO coopformactivities (multiple) VALUES('Y')";
	$result1 = mysql_query($qry1);
	
	} else {
	
	$qry1 = "INSERT INTO coopformactivities (multiple) VALUES('N')";
	$result1 = mysql_query($qry1);
	
	}
	
  $activityID = mysql_insert_id();
  $qrya = "UPDATE coopform SET activitiesID = $activityID WHERE formID = ".$_SESSION['formID'];
  $resulta = mysql_query($qrya);
  
  for ($i=0; $i < count($activity); $i++) {
  
    $act = $activity[$i];
	if (preg_match('/^fb/', $act)) {
	  $act = 'fb';
	  $type = explode('b', $activity[$i]);
	  $fb_type = $type[1];
	  $fbqry = "UPDATE coopformactivities SET fb_type = '$fb_type', fb = 'x' WHERE activitiesID = $activityID";
	  $fbresult = mysql_query($fbqry);
	} else {
	
    $qry2 = "UPDATE coopformactivities SET $act = 'x' WHERE activitiesID = $activityID";
	$result2 = mysql_query($qry2);
	
	}	
	}
	}
	
  $sch1qry = mysql_query("SELECT nsaadist FROM headers WHERE id = $sch1_id");
  $row1 = mysql_fetch_array($sch1qry);
  $sch2qry = mysql_query("SELECT nsaadist FROM headers WHERE id = $sch2_id");
  $row2 = mysql_fetch_array($sch2qry);
  $sch3qry = mysql_query("SELECT nsaadist FROM headers WHERE id = $sch3_id");
  $sch4qry = mysql_query("SELECT nsaadist FROM headers WHERE id = $sch4_id");
  
  $sch1_dist = $row1[0];
  $sch2_dist = $row2[0];
    
  $qry3 = "INSERT INTO coopformschools (id, formID, name, district, formschoolnum) 
    VALUES('$sch1_id', '".$_SESSION['formID']."', '$sch1_name', '$sch1_dist', '1')";
  $result3 = mysql_query($qry3);

  $qry4 = "INSERT INTO coopformschools (id, formID, name, district, formschoolnum) 
    VALUES('$sch2_id', '".$_SESSION['formID']."', '$sch2_name', '$sch2_dist', '2')";
  $result4 = mysql_query($qry4);

  if($sch3qry) {

	$row3 = mysql_fetch_array($sch3qry);
	$sch3_dist = $row3[0];
    $qry5 = "INSERT INTO coopformschools (id, formID, name, district, formschoolnum) 
    VALUES('$sch3_id', '".$_SESSION['formID']."', '$sch3_name', '$sch3_dist', '3')";
    $result5 = mysql_query($qry5);

  } 

  if($sch4qry) {

    $row4 = mysql_fetch_array($sch4qry);
	$sch4_dist = $row4[0];
    $qry6 = "INSERT INTO coopformschools (id, formID, name, district, formschoolnum) 
    VALUES('$sch4_id', '".$_SESSION['formID']."', '$sch4_name', '$sch4_dist', '4')";
    $result6 = mysql_query($qry6);

  } 
  
  $submitqry = "UPDATE coopform SET submitting_id = ".$_SESSION['schoolid']." WHERE formID = ".$_SESSION['formID'];
  $submitres = mysql_query($submitqry);
  
  //If user chose to save and continue later, do nothing more.
  if (isset($_POST['save1'])) {

    echo '<p>Your information has been saved.</p>';
	session_destroy();
    exit();
  
  //If user chose to continue now, display part two of form: purpose, team name, mascot, colors, who reimburses, and contract district
  } else if (isset($_POST['continue1'])) {

?>
	<div style="width:700px;text-align:center;margin-left:auto;margin-right:auto;">
    <form method="post" id="second" onsubmit="return validateCoopForm2(this)" action="<?php echo $_SERVER['PHP_SELF'].'?session='.$sess; ?>">
	<h2><center>AGREEMENT FOR COOPERATIVE SPONSORSHIP</center></h2>
	<div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
    <h3>Purpose:</h3>
    <h4>Specify conditions which have prompted the Boards to agree.</h4>
    <table style="margin-left:auto;margin-right:auto;">
    <tr>
    <td>a.</td>
    <td><textarea style="width:500px" name="purpose1"> 
      <?php if (isset($_SESSION['purpose1'])) { echo $_SESSION['purpose1']; } ?>
    </textarea></td>
    </tr>
    <tr>
    <td>b.</td>
    <td><textarea style="width:500px" name="purpose2">
      <?php if (isset($_SESSION['purpose2'])) { echo $_SESSION['purpose2']; } ?>
    </textarea></td>
    </tr>
    <tr>
    <td>c.</td>
    <td><textarea style="width:500px" name="purpose3">
      <?php if (isset($_SESSION['purpose3'])) { echo $_SESSION['purpose3']; } ?>
    </textarea></td>
    </tr>
    <tr>
    <td>d.</td>
    <td><textarea style="width:500px" name="purpose4">
      <?php if (isset($_SESSION['purpose4'])) { echo $_SESSION['purpose4']; } ?>
    </textarea></td>
    </tr>
    </table>
    </div>

    <div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
    <h3>Team Information:</h3>
    <table style="margin-left:auto;margin-right:auto;">
    <tr>
    <td>Team Name:</td>
    <td><input type="text" name="teamname" <?php if (isset($_SESSION['teamname'])) { 
      echo 'value="'.$_SESSION['teamname'].'"'; } ?> /></td>
    </tr>
    <tr>
    <td>Team Mascot:</td>
    <td><input type="text" name="teammascot" <?php if (isset($_SESSION['teammascot'])) { 
      echo 'value="'.$_SESSION['teammascot'].'"'; } ?> /></td>
    </tr>
    <tr>
    <td>Team Colors:</td>
    <td><input type="text" name="colors" <?php if (isset($_SESSION['teamcolors'])) { 
      echo 'value="'.$_SESSION['teamcolors'].'"'; } ?> /></td>
	  
	</td>
    </tr>
    </table>
    </div>

    <div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
    <h3>Contracts:</h3>
    <p>Contracts related to the cooperatively sponsored team should be made by the 
      governing board of School District No.:
	<select name="contract_dist">
	<?php
	echo '<option';
	if (isset($_SESSION['contract_dist']) && $_SESSION['contract_dist'] == $sch1_dist) echo ' select="selected"';
	echo '>'.$sch1_dist.'</option>';
	
	echo '<option';
	if (isset($_SESSION['contract_dist']) && $_SESSION['contract_dist'] == $sch2_dist) echo ' select="selected"';
	echo '>'.$sch2_dist.'</option>';
	
	if (isset($sch3_dist)) {
	echo '<option';
	if (isset($_SESSION['contract_dist']) && $_SESSION['contract_dist'] == $sch3_dist) echo ' select="selected"';
	echo '>'.$sch3_dist.'</option>';
	}
	
	if (isset($sch4_dist)) {
	echo '<option';
	if (isset($_SESSION['contract_dist']) && $_SESSION['contract_dist'] == $sch4_dist) echo ' select="selected"';
	echo '>'.$sch4_dist.'</option>';
	}
	
	?>
	</select>
	</p>
	<p>Name of school reimbursement checks should be written to: 
	<select name="reimburse">
	<?php
    echo '<option';
	if (isset($_SESSION['reimburse']) && $_SESSION['reimburse'] == $sch1_name) echo ' select="selected"';
	echo '>'.stripslashes($sch1_name).'</option>';
	
	echo '<option';
	if (isset($_SESSION['reimburse']) && $_SESSION['reimburse'] == $sch2_name) echo ' select="selected"';
	echo '>'.stripslashes($sch2_name).'</option>';
	
	if (isset($sch3_name)) {
	echo '<option';
	if (isset($_SESSION['reimburse']) && $_SESSION['reimburse'] == $sch3_name) echo ' select="selected"';
	echo '>'.stripslashes($sch3_name).'</option>';
	}
	
	if (isset($sch4_name)) {
	echo '<option';
	if (isset($_SESSION['reimburse']) && $_SESSION['reimburse'] == $sch4_name) echo ' select="selected"';
	echo '>'.stripslashes($sch4_name).'</option>';
	}
	?>
	</select>
	<br />
	</p>
	</div>
	<center>
	<input type="hidden" name="continue" />
	<input type="submit" name="continue2" value="Continue" />
    <input type="submit" name="save2" value="Save & Continue Later" />
	</center>
    </form>
	</div>
<?php
    
    }
	}
	
	//Save part two data into database
    if (isset($_POST['continue2']) || isset($_POST['save2'])) {

	 $purpose1 = trim($_POST['purpose1']);
     $purpose1 = strip_tags($purpose1); 
     $purpose1 = addslashes($purpose1); 
  

      if (isset($_POST['purpose2'])) {
      $purpose2 = trim($_POST['purpose2']);
      $purpose2 = strip_tags($purpose2);
      $purpose2 = addslashes($purpose2); 

      } else {
      $purpose2 = NULL;
      }
  
      if (isset($_POST['purpose3'])) {
      $purpose3 = trim($_POST['purpose3']);
      $purpose3 = strip_tags($purpose3);
      $purpose3 = addslashes($purpose3); 

      } else {
      $purpose3 = NULL;
      }
  
      if (isset($_POST['purpose4'])) {
      $purpose4 = trim($_POST['purpose4']);
      $purpose4 = strip_tags($purpose4);
      $purpose4 = addslashes($purpose4); 

      } else {
      $purpose4 = NULL;
      }

      $teamname = trim($_POST['teamname']);
      $teamname = strip_tags($teamname); 
      $teamname = addslashes($teamname); 
  
      $teammascot = trim($_POST['teammascot']);
      $teammascot = strip_tags($teammascot);
      $teammascot = addslashes($teammascot);
	  
      $teamcolors = trim($_POST['colors']);
	  $teamcolors = strip_tags($teamcolors);
	  $teamcolors = addslashes($teamcolors);
  
      $contract_dist = $_POST['contract_dist'];
      $reimburse = addslashes($_POST['reimburse']);

      $qry8 = "UPDATE coopform SET purpose1 = '$purpose1', purpose2 = '$purpose2',
        purpose3 = '$purpose3', purpose4 = '$purpose4', contract_dist = $contract_dist,
        teamname = '$teamname', teammascot = '$teammascot', reimburse = '$reimburse',
        teamcolors = '$teamcolors' WHERE formID = ".$_SESSION['formID'];

      $result8 = mysql_query($qry8);

	  //If user chose to continue later, do nothing more.
      if (isset($_POST['save2'])) {

        echo '<p>Your information has been saved.</p>';
		session_destroy();

        exit();

		//If user chose to continue now, display part three: allocation of expenses.
      } else if (isset($_POST['continue2'])) {
  
?>
		<div style="width:700px;text-align:center;margin-left:auto;margin-right:auto;">
	    <form method="post" id="third" onsubmit="return validateCoopForm3(this)"  action="<?php echo $_SERVER['PHP_SELF'].'?session='.$sess; ?>">
        <div>
		<h2><center>AGREEMENT FOR COOPERATIVE SPONSORSHIP</center></h2>
		<div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
        <h3>Allocation of Costs:</h3>
        <h4>Please list the manner that costs of the combined program should be allocated 
          between/among the parties in the manner indicated below for each expenditure 
          category listed.
        </h4>
		<p>(Specify method of allocation for each expense.)
        <ol style="margin-left:auto;margin-right:auto;">
         <li>Expenses for transportation, including daily transporation of 
             participants to and from practice sessions and contests.<br />  
         <textarea style="width:500px;" name="dtransexp_all" >
		       <?php if (isset($_SESSION['dtransexp_all'])) { echo $_SESSION['dtransexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for transportation to "away contests."<br />  
         <textarea style="width:500px;" name="atransexp_all" >
 		       <?php if (isset($_SESSION['atransexp_all'])) { echo $_SESSION['atransexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for spectator buses.<br />
         <textarea style="width:500px;" name="specbusexp_all" >
 		       <?php if (isset($_SESSION['specbusexp_all'])) { echo $_SESSION['specbusexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for facilities, lights, heating, showers, towels, laundry, etc., 
           of the host school, including maintenance of practice and competitive facilities.<br />  
         <textarea style="width:500px;" name="facilexp_all" >
 		       <?php if (isset($_SESSION['facilexp_all'])) { echo $_SESSION['facilexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for banquets and awards.<br />
         <textarea style="width:500px;" name="banqexp_all" >
 		       <?php if (isset($_SESSION['banqexp_all'])) { echo $_SESSION['banqexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for scouting, coaches' meetings, and workshops.<br />
         <textarea style="width:500px;" name="scoutexp_all" >
 		       <?php if (isset($_SESSION['scoutexp_all'])) { echo $_SESSION['scoutexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for payment of referees and other personnel necessary to stage 
           the event.<br />
         <textarea style="width:500px;" name="refexp_all" >
 		       <?php if (isset($_SESSION['refexp_all'])) { echo $_SESSION['refexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for purchasing supplies and equipments.<br />
         <textarea style="width:500px;" name="suppexp_all" >
 		       <?php if (isset($_SESSION['suppexp_all'])) { echo $_SESSION['suppexp_all']; } ?>
		 </textarea>
         </li>
         <li>Expenses for salary and fringe benefit costs for coaches and other activity personnel.<br />
         <textarea style="width:500px;" name="salexp_all" >
 		       <?php if (isset($_SESSION['salexp_all'])) { echo $_SESSION['salexp_all']; } ?>
		 </textarea>
         </li>
         <li>Other expenses.<br />
         <textarea style="width:500px;" name="otherexp_all" >
 		       <?php if (isset($_SESSION['otherexp_all'])) { echo $_SESSION['otherexp_all']; } ?>
		 </textarea>
         </li>
        </ol>
		</p>
        </div>

		<input type="hidden" name="continue" />
		<input type="submit" name="continue3" value="Continue" />
        <input type="submit" name="save3" value="Save & Continue Later" />
		</form>
		</div>
<?php        
   
  }
  }
  
		//Save part 3 data into database
        if (isset($_POST['continue3']) || isset($_POST['save3'])) {
		

          $dtransexp_all = trim($_POST['dtransexp_all']);
          $dtransexp_all = strip_tags($dtransexp_all);
          $dtransexp_all = addslashes($dtransexp_all);
          
          $atransexp_all = trim($_POST['atransexp_all']);
          $atransexp_all = strip_tags($atransexp_all);
          $atransexp_all = addslashes($atransexp_all);
  
          $specbusexp_all = trim($_POST['specbusexp_all']);
          $specbusexp_all = strip_tags($specbusexp_all); 
          $specbusexp_all = addslashes($specbusexp_all); 
  
          $facilexp_all = trim($_POST['facilexp_all']);
          $facilexp_all = strip_tags($facilexp_all);
          $facilexp_all = addslashes($facilexp_all);
  
          $banqexp_all = trim($_POST['banqexp_all']);
          $banqexp_all = strip_tags($banqexp_all);
          $banqexp_all = addslashes($banqexp_all);
  
          $scoutexp_all = trim($_POST['scoutexp_all']);
          $scoutexp_all = strip_tags($scoutexp_all);
          $scoutexp_all = addslashes($scoutexp_all);
  
          $refexp_all = trim($_POST['refexp_all']);
          $refexp_all = strip_tags($refexp_all); 
          $refexp_all = addslashes($refexp_all); 
  
          $suppexp_all = trim($_POST['suppexp_all']);
          $suppexp_all = strip_tags($suppexp_all);
          $suppexp_all = addslashes($suppexp_all);
		  
          $salexp_all = trim($_POST['salexp_all']);
          $salexp_all = strip_tags($salexp_all);
          $salexp_all = addslashes($salexp_all);		  
  
          $otherexp_all = trim($_POST['otherexp_all']);
          $otherexp_all = strip_tags($otherexp_all);
          $otherexp_all = addslashes($otherexp_all);

          $qry9 = "UPDATE coopform SET dtransexp_all = '$dtransexp_all', atransexp_all = '$atransexp_all',
            specbusexp_all = '$specbusexp_all', facilexp_all = '$facilexp_all', banqexp_all = '$banqexp_all',
            scoutexp_all = '$scoutexp_all', refexp_all = '$refexp_all', suppexp_all = '$suppexp_all',
            otherexp_all = '$otherexp_all', salexp_all = '$salexp_all' WHERE formID = ".$_SESSION['formID'];

          $result9 = mysql_query($qry9);

		  //If user chose to continue later, do nothing more.
          if (isset($_POST['save3'])) {

            echo '<p>Your information has been saved.</p>';
			session_destroy();
            exit();

			//If user chose to continue now, display part 4:gate receipts, personnel, insurance.
          } else if (isset($_POST['continue3'])) {

?>
			<div style="width:700px;text-align:center;margin-left:auto;margin-right:auto;">
			<form method="post" id="fourth" onsubmit="return validateCoopForm4(this)" action="<?php echo $_SERVER['PHP_SELF'].'?session='.$sess; ?>">
            <h2><center>AGREEMENT FOR COOPERATIVE SPONSORSHIP</center></h2>
			<div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
            <h3>Allocation of Gate Receipts:</h3>
            <ol style="margin-left:auto;margin-right:auto;">
              <li>Please list how funds from gate receipts should be divided by the parties after 
                payment of referees and other personnel.<br />
              <textarea style="width:500px;" name="gate_all" >
	 		       <?php if (isset($_SESSION['gate_all'])) { echo $_SESSION['gate_all']; } ?>
			  </textarea>
              </li>
              <li>Please list how payment of referees and other personnel will be allocated between
                the parties in the event of insufficient gate receipts.<br />
              <textarea style="width:500px;" name="insufgate_all" >
	 		       <?php if (isset($_SESSION['insufgate_all'])) { echo $_SESSION['insufgate_all']; } ?>
			  </textarea>
              </li>
            </ol>
            </div>

            <div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
            <h3>Employment of Personnel:</h3>
            <table style="margin-left:auto;margin-right:auto;">
			<tr>
			<td>The head coach of the combined program will be employed by the school board of 
                School District No.:
            </td>
            <td><select name="hcoach_dist">
            <?php
            $distqry = mysql_query("SELECT DISTINCT district FROM coopformschools WHERE formID = ".$_SESSION['formID']);
            while ($distrow = mysql_fetch_assoc($distqry)) {
               echo '<option';
               if (isset($_SESSION['hcoach_dist']) && $_SESSION['hcoach_dist'] == $distrow['district']) 
                echo ' select="selected"';
				echo '>'.$distrow['district'].'</option>';
			}
			?>
			</select>
			</td>
			</tr>
			</table>
			
			<h4>Other joint program personnel, if any, will be employed as follows:</h4>
                <table style="margin-left:auto;margin-right:auto;">
	            <tr>
	            <th>Position</th><th>Employer</th>
	            </tr>
	            <tr>
	            <td><input type="text" name="jp_personnel1" 
				<?php if (isset($_SESSION['jp_personnel1'])) { echo 'value="'.$_SESSION['jp_personnel1'].'"'; } ?> /></td>
				<td><select name="jp_employer1"><option>None</option>
            <?php
            $empqry = mysql_query("SELECT name FROM coopformschools WHERE formID = ".$_SESSION['formID']);
            while ($emprow = mysql_fetch_assoc($empqry)) {
               echo '<option';
               if (isset($_SESSION['jp_employer1']) && $_SESSION['jp_employer1'] == $emprow['name']) 
                echo ' select="selected"';
				echo '>'.$emprow['name'].'</option>';
			}
			?>
			</select>
			</td>
			</tr>
			<tr>
	            <td><input type="text" name="jp_personnel2"
				<?php if (isset($_SESSION['jp_personnel2'])) { echo 'value="'.$_SESSION['jp_personnel2'].'"'; } ?> /></td>
				<td><select name="jp_employer2"><option>None</option>
            <?php
            $empqry = mysql_query("SELECT name FROM coopformschools WHERE formID = ".$_SESSION['formID']);
            while ($emprow = mysql_fetch_assoc($empqry)) {
               echo '<option';
               if (isset($_SESSION['jp_employer2']) && $_SESSION['jp_employer2'] == $emprow['name']) 
                echo ' select="selected"';
				echo '>'.$emprow['name'].'</option>';
			}
			?>
			</select>
			</td>
			</tr>
			<tr>
	            <td><input type="text" name="jp_personnel3"
				<?php if (isset($_SESSION['jp_personnel3'])) { echo 'value="'.$_SESSION['jp_personnel3'].'"'; } ?> /></td>
				<td><select name="jp_employer3"><option>None</option>
            <?php
            $empqry = mysql_query("SELECT name FROM coopformschools WHERE formID = ".$_SESSION['formID']);
            while ($emprow = mysql_fetch_assoc($empqry)) {
               echo '<option';
               if (isset($_SESSION['jp_employer3']) && $_SESSION['jp_employer3'] == $emprow['name']) 
                echo ' select="selected"';
				echo '>'.$emprow['name'].'</option>';
			}
			?>
			</select>
			</td>
			</tr>
			</table>
            </div>

             <div style="width:700px;text-align:center;margin-bottom:5px;margin-left:auto;margin-right:auto;border:2px solid black;">
             <h3>Liability Insurance:</h3>
             <h4>Please enter the amount of liability insurance each party will cover:
             <table style="margin-left:auto;margin-right:auto;">
             <tr>
			 <?php
			 echo '<td>For any claimant:</td><td><input type="text" name="claimant_ins"';
             if (isset($_SESSION['claimant_ins'])) echo' value="'.$_SESSION['claimant_ins'].'"';
             echo '/></td></tr>';
			 echo '<td>For any claim:</td><td><input type="text" name="claim_ins"';
             if (isset($_SESSION['claim_ins'])) echo' value="'.$_SESSION['claim_ins'].'"';
             echo '/></td></tr></table>';
			 ?>
			 </td>
             </tr>
             </table>
			 <center>
			 <input type="hidden" name="continue" />
			 <input type="submit" name="process" value="Finish" />
             <input type="submit" name="save4" value="Save & Continue Later" />
			 </center>
			 </div>

			</form>
			</div>

<?php

            }
          }
//Save part 4 data into database.		  
if (isset($_POST['save4']) || isset($_POST['process'])) {
  
  $gate_all = trim($_POST['gate_all']);
  $gate_all = strip_tags($gate_all);
  $gate_all = addslashes($gate_all);

  $insufgate_all = trim($_POST['insufgate_all']);
  $insufgate_all = strip_tags($insufgate_all); 
  $insufgate_all = addslashes($insufgate_all); 
  
  $hcoach_dist = trim($_POST['hcoach_dist']);
  $hcoach_dist = strip_tags($hcoach_dist);
  $hcoach_dist = addslashes($hcoach_dist);
  

  if (isset($_POST['jp_personnel1'])) {
  $jp_personnel1 = trim($_POST['jp_personnel1']);
  $jp_personnel1 = strip_tags($jp_personnel1);
  $jp_personnel1 = addslashes($jp_personnel1);
  } else {
  $jp_personnel1 = NULL;
  }
  
  if (isset($_POST['jp_personnel2'])) {
  $jp_personnel2 = trim($_POST['jp_personnel2']);
  $jp_personnel2 = strip_tags($jp_personnel2);
  $jp_personnel2 = addslashes($jp_personnel2);
  } else {
  $jp_personnel2 = NULL;
  }
  
  if (isset($_POST['jp_personnel3'])) {
  $jp_personnel3 = trim($_POST['jp_personnel3']);
  $jp_personnel3 = strip_tags($jp_personnel3); 
  $jp_personnel3 = addslashes($jp_personnel3); 
  } else {
  $jp_personnel3 = NULL;
  }
  
  if (isset($_POST['jp_employer1'])) {
  $jp_employer1 = addslashes($_POST['jp_employer1']);
  } else {
  $jp_employer1 = NULL;
  }

  if (isset($_POST['jp_employer2'])) {
  $jp_employer2 = addslashes($_POST['jp_employer2']);
  } else {
  $jp_employer2 = NULL;
  }

  if (isset($_POST['jp_employer3'])) {  
  $jp_employer3 = addslashes($_POST['jp_employer3']);
  } else {
  $jp_employer3 = NULL;
  }
  
  $claimant_ins = trim($_POST['claimant_ins']);
  $claimant_ins = strip_tags($claimant_ins);
  $claimant_ins = addslashes($claimant_ins);
  
  $claim_ins = trim($_POST['claim_ins']);
  $claim_ins = strip_tags($claim_ins);
  $claim_ins = addslashes($claim_ins);
  
  $qry10 = "UPDATE coopform SET gate_all = '$gate_all', insufgate_all = '$insufgate_all',
    hcoach_dist = '$hcoach_dist', jp_personnel1 = '$jp_personnel1', jp_personnel2 = '$jp_personnel2',
    jp_personnel3 = '$jp_personnel3', jp_employer1 = '$jp_employer1', jp_employer2 = '$jp_employer2',
    jp_employer3 = '$jp_employer3', claimant_ins = '$claimant_ins', claim_ins = '$claim_ins' 
    WHERE formID = ".$_SESSION['formID'];

  $result10 = mysql_query($qry10);

  
  //If user chose to continue later, do nothing more.
  if (isset($_POST['save4'])) {  

    echo '<p>Your information has been saved.</p>';
	session_destroy();
    exit();

	//If user chose to finish, display message and link to email notifications to AD's of involved schools
  } else if (isset($_POST['process'])) {
 
	$formid = $_SESSION['formID'];
    $subject = "An NSAA Cooperative Sponsorship Agreement is Awaiting Your Input";
   	$body = "An NSAA Cooperative Sponsorship Agreement is being processed between: ";
	
	$mailqry1 = "SELECT t1.email, t2.school, t3.id FROM logins as t1, headers as t2, coopformschools as t3
      WHERE t3.id = t2.id AND t1.school = t2.school AND t3.formID = $formid
	  AND t3.formschoolnum = '1' AND t1.sport = 'Activities Director'";
	  
	$mailres1 = mysql_query($mailqry1);
	
	if ($mailres1) {
	  $mailrow1 = mysql_fetch_assoc($mailres1);
	  $mailto .= $mailrow1['email'];
	  $body .= $mailrow1['school'];
	}
	
	$mailqry2 = "SELECT t1.email, t2.school, t3.id FROM logins as t1, headers as t2, coopformschools as t3
      WHERE t3.id = t2.id AND t1.school = t2.school AND t3.formID = $formid
	  AND t3.formschoolnum = '2' AND t1.sport = 'Activities Director'";
	  
	$mailres2 = mysql_query($mailqry2);
	if ($mailres2) {
	  $mailrow2 = mysql_fetch_assoc($mailres2);
	  $mailto .= ",".$mailrow2['email'];
	  $body .= " ".$mailrow2['school'];
	}
	
	$mailqry3 = "SELECT t1.email, t2.school, t3.id FROM logins as t1, headers as t2, coopformschools as t3
      WHERE t3.id = t2.id AND t1.school = t2.school AND t3.formID = $formid
	  AND t3.formschoolnum = '3' AND t1.sport = 'Activities Director'";
	  
	$mailres3 = mysql_query($mailqry3);
	if ($mailres3) {
	$mailrow3 = mysql_fetch_assoc($mailres3);
	
	$mailto .= ",".$mailrow3['email'];
	$body .= " ".$mailrow3['school'];

	}
	  
	$mailqry4 = "SELECT t1.email, t2.school, t3.id FROM logins as t1, headers as t2, coopformschools as t3
      WHERE t3.id = t2.id AND t1.school = t2.school AND t3.formID = $formid
	  AND t3.formschoolnum = '4' AND t1.sport = 'Activities Director'";
	  
	$mailres4 = mysql_query($mailqry4);
	if ($mailres4) {
	$mailrow4 = mysql_fetch_assoc($mailres4);
	
	$mailto .= ",".$mailrow4['email'];
	$body .= " ".$mailrow4['school'];
	
	}
	
	$body .= ".  This agreement cannot be submitted until all schools involved have entered
	 their school enrollment and participation information.  You may access your portion of this form
	 by logging in to your NSAA account online and selecting the Cooperative Sponsorship Agreement link under
	 Other Forms.  Then, select the link to enter your school information for this cooperative sponsorship agreement form.";
	  

	?>	  
    <p>Your information has been saved.  The Activities Director for each school applying for 
      a cooperative sponsorship must enter their enrollment and sport participation
      information before this form can be submitted.</p>
	  
	<p>To send an email notification to each school's Activities Director that this form
    	is awaiting their input, please click 
		<a href="mailto:<?php echo $mailto; ?>?subject=<?php echo $subject; ?>&body=<?php echo $body; ?>"
			target="_blank" >here</a>.</p>
		
<?php
  	session_destroy();

		}
}
 

//Echo Footer
echo $end_html;	
?>


