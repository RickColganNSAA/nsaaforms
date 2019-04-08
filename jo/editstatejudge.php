<?php
/*******************************************
editstatejudge.php
NSAA Edit Journalism Judge Info
Created 11/15/12
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
   header("Location:../index.php");
   exit();
}

if($_POST['save'])
{
   $first=addslashes($first); $last=addslashes($last);
	//JUDGE INFO
   $sql="UPDATE jostatejudges SET first='$first',last='$last',email='$email',password='$password',address='$address',city='$city',state='$state',zip='$zip' WHERE id='$judgeid'";
   $result=mysql_query($sql);
} 

echo $init_html;
echo $header;

echo "<form method='post' action='editstatejudge.php'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"judgeid\" value=\"$judgeid\">";
$sql="SELECT * FROM jostatejudges WHERE id='$judgeid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<br><a href=\"statejudges.php?session=$session\">Return to Journalism Judges</a>";
echo "<br><h2>State Journalism Judge Profile: <u>$row[first] $row[last]</u></h2>";
if($_POST['save'])
{
   echo "<div class='alert'>Your changes have been saved. <a href=\"statejudges.php?session=$session\" class=\"small\">Return to Journalism Judges List</a></div>";
}
echo "<table cellspacing=0 cellpadding=3 style=\"width:400px;\">";
echo "<tr align=left><td>Name:</td><td><input type=text size=15 name=\"first\" value=\"$row[first]\"> <input type=text size=25 name=\"last\" value=\"$row[last]\"></td></tr>";
echo "<tr align=left><td>E-mail:</td><td><input type=text size=44 autocomplete=\"off\" name=\"email\" value=\"$row[email]\"></td></tr>";
echo "<tr align=left><td>Password:</td><td><input type=text size=44 autocomplete=\"off\" name=\"password\" value=\"$row[password]\"></td></tr>";
echo "<tr align=left><td>City:</td><td><input type=text size=44 autocomplete=\"off\" name=\"city\" value=\"$row[city]\"></td></tr>";
echo "<tr align=left><td>State:</td><td><input type=text size=44 autocomplete=\"off\" name=\"state\" value=\"$row[state]\"></td></tr>";
echo "<tr align=left><td>Zip:</td><td><input type=text size=44 autocomplete=\"off\" name=\"zip\" value=\"$row[zip]\"></td></tr>";
echo "<tr align=left><td>Address:</td><td><textarea name='address' id='' cols='34' rows='4'>$row[address]</textarea></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name='save' class='fancybutton2' value=\"Save Changes\">";
echo "</td></tr>";
echo "</table>";
echo "</form>";


echo $end_html;
?>
