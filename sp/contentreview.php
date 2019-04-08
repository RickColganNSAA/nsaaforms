<?php
/*********************************************
contentreview.php
AD must sign statement that content of
performance is appropriate/permission is granted
9/9/14
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session) || GetLevel($session)!=2)
{
   header("Location:../index.php?error=1");
   exit();
}
$school=GetSchool($session);
$schoolid=GetSchoolID2($school);
$sport='sp';
$sportname=GetActivityName($sport);
$sql="SELECT * FROM contentreview WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$wording=$row[wording];

if($save)
{
   $sql="DELETE FROM contentreviews WHERE schoolid='$schoolid' AND sport='$sport'";
   $result=mysql_query($sql);
   $sql="INSERT INTO contentreviews (schoolid,sport,adminsig,datesub) VALUES ('$schoolid','$sport','".addslashes($adminsig)."','".time()."')";
   $result=mysql_query($sql);
}

echo $init_html;
echo GetHeader($session);

$sql="SELECT * FROM contentreviews WHERE schoolid='$schoolid' AND sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[datesub]>0) $submitted=1;
else $submitted=0;

echo "<br><form method=post action=\"contentreview.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
echo "<h2>$sportname Review Form</h2>";
if($save)
   echo "<div class='alert' style=\"width:450px;text-align:center;\"><i>Thank you. Your form has been submitted to the NSAA.</i> <a href=\"../welcome.php?session=$session\">Return Home</a></div>";
echo "<div style=\"width:700px;text-align:left;\">";
echo "<p><b>School: </b> $school</p>";
echo $wording;
echo "<div class='alert'>";
if($submitted==1)
{
   echo "<p><b>Administrator's Signature:</b> $row[adminsig]</p><p>This form was signed and submitted electronically to the NSAA on <b>".date("F j, Y",$row[datesub])." at ".date("g:ia T",$row[datesub]).".</b></p>";
}
else
{
   echo "<p><input type=text size=40 name=\"adminsig\" placeholder=\"Type your full name\"><br /><b>Administrator's Signature</b></p>";
   echo "<p><input type=submit name=\"save\" value=\"Submit\"></p>";
}
echo "</div>";

echo "</div>";
echo "</form>";
echo $end_html;


?>
