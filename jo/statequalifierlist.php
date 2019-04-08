<?php
/*******************************************
statequalifierlist.php
PUBLIC WEBSITE STATE QUALIFIERS PAGE
(Admin can preview with $session)
Created 4/11/13
Author: Ann Gaffigan
*******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(ValidUser($session) && $save)
{
   $sql="UPDATE joqualifiers SET showtopublic='$showtopublic'";
   $result=mysql_query($sql);
}

echo $init_html."<table class='nine' width='100%'><tr align=center><td>";

$sql="SELECT * FROM joqualifiers LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(ValidUser($session))
{
   echo "<div class=\"alert\" style=\"width:400px;\">
	<form method=post action=\"statequalifierlist.php\"><input type=hidden name=\"session\" value=\"$session\">
	<p><input type=checkbox name=\"showtopublic\" value=\"x\"";
   if($row[showtopublic]=='x') echo " checked";
   echo "> Check Here to Make these Qualifiers Available to the Public</p>
	<input type=submit name=\"save\" value =\"Save Checkmark\"></form>";
   echo "<p><a href=\"statequalifierlist.php\" target=\"_blank\">Preview the State Qualifier List on the Public Website</a></p>";
   echo "</div>";
}
else if($row[showtopublic]!='x')
{
   echo "<h2>Qualifiers for State Journalism Contest:</h2>
	<p><i>The list of qualifiers is not available at this time.</i></p>";
   echo $end_html;
   exit();
}

   echo "<div style=\"width:700px;text-align:left;\">";
   echo "<h2>Qualifiers for State Journalism Contest:</h2>";
   $string=GetJOStateQualifierList();
   $string=explode("<!--HALFWAY-->",$string);
   echo "<table><tr align=left valign=top><td>$string[0]</td><td>$string[1]</td></tr></table>";
   //echo GetJOStateQualifierList();
   echo "</div>";
   echo $end_html;
?>
