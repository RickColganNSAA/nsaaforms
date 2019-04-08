<?php
/********************************************************
coopformsch.php
Form for participating coop schools to enter enrollment/participation info
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
echo $header;

//Get form ID
if (isset($_GET['cformID'])) {
  $_SESSION['formID'] = $_GET['cformID'];
}

//Get session to pass after form post
$_SESSION['schoolid'] = $schoolid;
$sess = $_GET['session'];

if (!isset($_POST['schsubmit'])) {
  {
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?session='.$sess; ?>">
<h3>School Information</h3>
<h4>Please enter the number of students enrolled.</h4>
<table>
<tr>
<th></th>
<th>Grade 9 Girls</th>
<th>Grade 9 Boys</th>
<th>Grade 10 Girls</th>
<th>Grade 10 Boys</th>
<th>Grade 11 Girls</th>
<th>Grade 11 Boys</th>
<th>Grade 12 Girls</th>
<th>Grade 12 Boys</th>
</tr>
<tr>
<td>Current Year: </td>
<td><input type="text" name="currenr9g" /></td>
<td><input type="text" name="currenr9b" /></td>
<td><input type="text" name="currenr10g" /></td>
<td><input type="text" name="currenr10b" /></td>
<td><input type="text" name="currenr11g" /></td>
<td><input type="text" name="currenr11b" /></td>
<td><input type="text" name="currenr12g" /></td>
<td><input type="text" name="currenr12b" /></td>
</tr>
<tr>
<td>Anticipated Next Year: </td>
<td><input type="text" name="ant1enr9g" /></td>
<td><input type="text" name="ant1enr9b" /></td>
<td><input type="text" name="ant1enr10g" /></td>
<td><input type="text" name="ant1enr10b" /></td>
<td><input type="text" name="ant1enr11g" /></td>
<td><input type="text" name="ant1enr11b" /></td>
<td><input type="text" name="ant1enr12g" /></td>
<td><input type="text" name="ant1enr12b" /></td>
</tr>
<tr>
<td>Anticipated Two Years Hence: </td>
<td><input type="text" name="ant2enr9g" /></td>
<td><input type="text" name="ant2enr9b" /></td>
<td><input type="text" name="ant2enr10g" /></td>
<td><input type="text" name="ant2enr10b" /></td>
<td><input type="text" name="ant2enr11g" /></td>
<td><input type="text" name="ant2enr11b" /></td>
<td><input type="text" name="ant2enr12g" /></td>
<td><input type="text" name="ant2enr12b" /></td>
</tr>
</table>

<h4>Please enter the number of students participating.</h4>
<table>
<tr>
<th></th>
<th>Grade 9 Girls</th>
<th>Grade 9 Boys</th>
<th>Grade 10 Girls</th>
<th>Grade 10 Boys</th>
<th>Grade 11 Girls</th>
<th>Grade 11 Boys</th>
<th>Grade 12 Girls</th>
<th>Grade 12 Boys</th>
</tr>
<tr>
<td>Current Year: </td>
<td><input type="text" name="currpart9g" /></td>
<td><input type="text" name="currpart9b" /></td>
<td><input type="text" name="currpart10g" /></td>
<td><input type="text" name="currpart10b" /></td>
<td><input type="text" name="currpart11g" /></td>
<td><input type="text" name="currpart11b" /></td>
<td><input type="text" name="currpart12g" /></td>
<td><input type="text" name="currpart12b" /></td>
</tr>
<tr>
<td>Anticipated Next Year: </td>
<td><input type="text" name="ant1part9g" /></td>
<td><input type="text" name="ant1part9b" /></td>
<td><input type="text" name="ant1part10g" /></td>
<td><input type="text" name="ant1part10b" /></td>
<td><input type="text" name="ant1part11g" /></td>
<td><input type="text" name="ant1part11b" /></td>
<td><input type="text" name="ant1part12g" /></td>
<td><input type="text" name="ant1part12b" /></td>
</tr>
<tr>
<td>Anticipated Two Years Hence: </td>
<td><input type="text" name="ant2part9g" /></td>
<td><input type="text" name="ant2part9b" /></td>
<td><input type="text" name="ant2part10g" /></td>
<td><input type="text" name="ant2part10b" /></td>
<td><input type="text" name="ant2part11g" /></td>
<td><input type="text" name="ant2part11b" /></td>
<td><input type="text" name="ant2part12g" /></td>
<td><input type="text" name="ant2part12b" /></td>
</tr>
</table>
<input type="submit" name="schsubmit" value="Submit" />
</form>
<?php
}
}

if (isset($_POST['schsubmit'])) {
$currenrolled9g = trim($_POST['currenr9g']);
$currenrolled9g = strip_tags($currenrolled9g);
$currenrolled9b = trim($_POST['currenr9b']);
$currenrolled9b = strip_tags($currenrolled9b);
$currenrolled10g = trim($_POST['currenr10g']);
$currenrolled10g = strip_tags($currenrolled10g);
$currenrolled10b = trim($_POST['currenr10b']);
$currenrolled10b = strip_tags($currenrolled10b);
$currenrolled11g = trim($_POST['currenr11g']);
$currenrolled11g = strip_tags($currenrolled11g);
$currenrolled11b = trim($_POST['currenr11b']);
$currenrolled11b = strip_tags($currenrolled11b);
$currenrolled12g = trim($_POST['currenr12g']);
$currenrolled12g = strip_tags($currenrolled12g);
$currenrolled12b = trim($_POST['currenr12b']);
$currenrolled12b = strip_tags($currenrolled12b);
$ant1enrolled9g = trim($_POST['ant1enr9g']);
$ant1enrolled9g = strip_tags($ant1enrolled9g);
$ant1enrolled9b = trim($_POST['ant1enr9b']);
$ant1enrolled9b = strip_tags($ant1enrolled9b);
$ant1enrolled10g = trim($_POST['ant1enr10g']);
$ant1enrolled10g = strip_tags($ant1enrolled10g);
$ant1enrolled10b = trim($_POST['ant1enr10b']);
$ant1enrolled10b = strip_tags($ant1enrolled10b);
$ant1enrolled11g = trim($_POST['ant1enr11g']);
$ant1enrolled11g = strip_tags($ant1enrolled11g);
$ant1enrolled11b = trim($_POST['ant1enr11b']);
$ant1enrolled11b = strip_tags($ant1enrolled11b);
$ant1enrolled12g = trim($_POST['ant1enr12g']);
$ant1enrolled12g = strip_tags($ant1enrolled12g);
$ant1enrolled12b = trim($_POST['ant1enr12b']);
$ant1enrolled12b = strip_tags($ant1enrolled12b);
$ant2enrolled9g = trim($_POST['ant2enr9g']);
$ant2enrolled9g = strip_tags($ant2enrolled9g);
$ant2enrolled9b = trim($_POST['ant2enr9b']);
$ant2enrolled9b = strip_tags($ant2enrolled9b);
$ant2enrolled10g = trim($_POST['ant2enr10g']);
$ant2enrolled10g = strip_tags($ant2enrolled10g);
$ant2enrolled10b = trim($_POST['ant2enr10b']);
$ant2enrolled10b = strip_tags($ant2enrolled10b);
$ant2enrolled11g = trim($_POST['ant2enr11g']);
$ant2enrolled11g = strip_tags($ant2enrolled11g);
$ant2enrolled11b = trim($_POST['ant2enr11b']);
$ant2enrolled11g = strip_tags($ant2enrolled11g);
$ant2enrolled12g = trim($_POST['ant2enr12g']);
$ant2enrolled12g = strip_tags($ant2enrolled12g);
$ant2enrolled12b = trim($_POST['ant2enr12b']);
$ant2enrolled12b = strip_tags($ant2enrolled12b);
$currparticipating9g = trim($_POST['currpart9g']);
$currparticipating9g = strip_tags($currparticipating9g);
$currparticipating9b = trim($_POST['currpart9b']);
$currparticipating9b = strip_tags($currparticipating9b);
$currparticipating10g = trim($_POST['currpart10g']);
$currparticipating10g = strip_tags($currparticipating10g);
$currparticipating10b = trim($_POST['currpart10b']);
$currparticipating10b = strip_tags($currparticipating10b);
$currparticipating11g = trim($_POST['currpart11g']);
$currparticipating11g = strip_tags($currparticipating11g);
$currparticipating11b = trim($_POST['currpart11b']);
$currparticipating11b = strip_tags($currparticipating11b);
$currparticipating12g = trim($_POST['currpart12g']);
$currparticipating12g = strip_tags($currparticipating12g);
$currparticipating12b = trim($_POST['currpart12b']);
$currparticipating12b = strip_tags($currparticipating12b);
$ant1participating9g = trim($_POST['ant1part9g']);
$ant1participating9g = strip_tags($ant1participating9g);
$ant1participating9b = trim($_POST['ant1part9b']);
$ant1participating9b = strip_tags($ant1participating9b);
$ant1participating10g = trim($_POST['ant1part10g']);
$ant1participating10g = strip_tags($ant1participating10g);
$ant1participating10b = trim($_POST['ant1part10b']);
$ant1participating10b = strip_tags($ant1participating10b);
$ant1participating11g = trim($_POST['ant1part11g']);
$ant1participating11g = strip_tags($ant1participating11g);
$ant1participating11b = trim($_POST['ant1part11b']);
$ant1participating11b = strip_tags($ant1participating11b);
$ant1participating12g = trim($_POST['ant1part12g']);
$ant1participating12g = strip_tags($ant1participating12g);
$ant1participating12b = trim($_POST['ant1part12b']);
$ant1participating12b = strip_tags($ant1participating12b);
$ant2participating9g = trim($_POST['ant2part9g']);
$ant2participating9g = strip_tags($ant2participating9g);
$ant2participating9b = trim($_POST['ant2part9b']);
$ant2participating9b = strip_tags($ant2participating9b);
$ant2participating10g = trim($_POST['ant2part10g']);
$ant2participating10g = strip_tags($ant2participating10g);
$ant2participating10b = trim($_POST['ant2part10b']);
$ant2participating10b = strip_tags($ant2participating10b);
$ant2participating11g = trim($_POST['ant2part11g']);
$ant2participating11g = strip_tags($ant2participating11g);
$ant2participating11b = trim($_POST['ant2part11b']);
$ant2participating11b = strip_tags($ant2participating11b);
$ant2participating12g = trim($_POST['ant2part12g']);
$ant2participating12g = strip_tags($ant2participating12g);
$ant2participating12b = trim($_POST['ant2part12b']);
$ant2participating12b = strip_tags($ant2participating12b);

$enrquery = "UPDATE coopformschools SET currenrolled9g = $currenrolled9g, currenrolled9b = $currenrolled9b,
  currenrolled10g = $currenrolled10g, currenrolled10b = $currenrolled10b, currenrolled11g = $currenrolled11g,
  currenrolled11b = $currenrolled11b, currenrolled12g = $currenrolled12g, currenrolled12b = $currenrolled12b,
  ant1enrolled9g = $ant1enrolled9g, ant1enrolled9b  = $ant1enrolled9b, ant1enrolled10g = $ant1enrolled10g,
  ant1enrolled10b = $ant1enrolled10b, ant1enrolled11g = $ant1enrolled11g, ant1enrolled11b = $ant1enrolled11b,
  ant1enrolled12g = $ant1enrolled12g, ant1enrolled12b = $ant1enrolled12b, ant2enrolled9g = $ant2enrolled9g,
  ant2enrolled9b = $ant2enrolled9b, ant2enrolled10g = $ant2enrolled10g, ant2enrolled10b = $ant2enrolled10b,
  ant2enrolled11g = $ant2enrolled11g, ant2enrolled11b = $ant2enrolled11b, ant2enrolled12g = $ant2enrolled12g,
  ant2enrolled12b = $ant2enrolled12b WHERE id = $schoolid AND formID = ".$_SESSION['formID'];

$partquery = "UPDATE coopformschools SET currparticipating9g = $currparticipating9g, currparticipating9b = $currparticipating9b,
  currparticipating10g = $currparticipating10g, currparticipating10b = $currparticipating10b, currparticipating11g = $currparticipating11g,
  currparticipating11b = $currparticipating11b, currparticipating12g = $currparticipating12g, currparticipating12b = $currparticipating12b,
  ant1participating9g = $ant1participating9g, ant1participating9b = $ant1participating9b, ant1participating10g = $ant1participating10g,
  ant1participating10b = $ant1participating10b, ant1participating11g = $ant1participating11g, ant1participating11b = $ant1participating11b,
  ant1participating12g = $ant1participating12g, ant1participating12b = $ant1participating12b, ant2participating9g = $ant2participating9g,
  ant2participating9b = $ant2participating9b, ant2participating10g = $ant2participating10g, ant2participating10b = $ant2participating10b,
  ant2participating11g = $ant2participating11g, ant2participating11b = $ant2participating11b, ant2participating12g = $ant2participating12g,
  ant2participating12b = $ant2participating12b WHERE id = $schoolid AND formID = ".$_SESSION['formID'];

$subquery = "UPDATE coopformschools SET submitted = CURRENT_TIMESTAMP WHERE id = $schoolid AND formID = ".$_SESSION['formID'];
$enrresult = mysql_query($enrquery);
$partresult = mysql_query($partquery);
$subresult = mysql_query($subquery);

if ($enrresult && $partresult) {
  echo '<h4>Your school information has been entered.</p>';
  echo '<a href="coopformindex.php?session='.$session.'" >Return to Cooperative Form Index</a>';
  exit();
}
 else {
  echo '<h4>There was a problem with your query.  Please try again.</h4>';
}
}


