<?php
/*******************************************
editjudge.php
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
   $category=addslashes($category); $headerr=addslashes($headerr);
	//JUDGE INFO
   $sql="UPDATE jostatecategories SET category='$category',header='$headerr' WHERE id='$catid'";
   $result=mysql_query($sql);
} 

echo $init_html;
echo $header;

echo "<form method='post' action='editcategory.php'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"catid\" value=\"$catid\">";
$sql="SELECT * FROM jostatecategories WHERE id='$catid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<br><a href=\"statecategories.php?session=$session\">Return to Category List</a>";
//echo "<br><h2>State Journalism Judge Profile: <u>$row[first] $row[last]</u></h2>";
if($_POST['save'])
{
   echo "<div class='alert'>Your changes have been saved. <a href=\"statecategories.php?session=$session\" class=\"small\">Return to Category List</a></div>";
}
echo "</br></br><table cellspacing=0 cellpadding=3 style=\"width:400px;\">";
echo "<tr align=left><td><b>Category Name:</b></td><td><input type=text size=40 name=\"category\" value=\"$row[category]\"> </td></tr>";
echo "<tr align=left><td><b>Header:</b></td><td><input type=text size=80 autocomplete=\"off\" name=\"headerr\" value=\"$row[header]\"></td></tr>";
//echo "<tr align=left><td>Password:</td><td><input type=text size=20 autocomplete=\"off\" name=\"password\" value=\"$row[password]\"></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name='save' class='fancybutton2' value=\"Save Changes\">";
echo "</td></tr>";
echo "</table>";
echo "</form>";


echo $end_html;
?>
