<?php
/*
classificationsettings.php
Used to update the parameters for officials
to upgrade from one class to another in each sport
*/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($save)
{
   $sql="UPDATE classificationsettings SET prevclass='$prevclass',prevclassyrs='".trim($prevclassyrs)."',rulesmeeting='$rulesmeeting',part1test='".trim($part1test)."',part2test='".trim($part2test)."',part2yrs='".trim($part2yrs)."',fbcontests='$fbcontests',vbcontests='$vbcontests',sbcontests='$sbcontests',bbcontests='$bbcontests',wrcontests='$wrcontests',socontests='$socontests',bacontests='$bacontests' WHERE classification='$classification'";
   $result=mysql_query($sql);
   if(mysql_error()) echo "ERROR WITH $sql: ".mysql_error()."<br>";
}

echo $init_html;
echo GetHeader($session);
echo "<form method=post action=\"classificationsettings.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
echo "<br /><h2>Manage Classification Settings for Officials:</h2>";
echo "<p>Manage the parameters used to determine if an official should move up or down in classification.</p>";
echo "<h3>Classification: <select name=\"classification\" onChange=\"submit();\">";
$sql="SELECT * FROM classificationsettings ORDER BY id";
$result=mysql_query($sql);
if(!$classification) $classification="R";
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[classification]\"";
   if($classification==$row[classification]) echo " selected";
   echo ">$row[classification]</option>";
}
echo "</select></h3>";

echo "<div style=\"width:500px;text-align:left;\">";

if($save)
   echo "<div class=\"alert\">The settings have been saved.</div>";

$sql="SELECT * FROM classificationsettings WHERE classification='$classification'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo "<p><input type=checkbox name=\"rulesmeeting\" value=\"x\"";
if($row[rulesmeeting]=='x') echo " checked";
echo "> Must attend a <b>RULES MEETING</b>.</p>";

echo "<p>Classification one level <b>BELOW</b> this classification: <select name=\"prevclass\"><option value=\"\">N/A</option>";
$sql2="SELECT * FROM classificationsettings WHERE classification!='$classification' ORDER BY id";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   echo "<option value=\"$row2[classification]\"";
   if($row2[classification]==$row[prevclass]) echo " selected";
   echo ">$row2[classification]</option>";
}
echo "</select></p>";
echo "<p><b>Years</b> the official must have a lower classification before moving up: <input type=text size=2 maxlength=2 name=\"prevclassyrs\" value=\"$row[prevclassyrs]\"></p>";

echo "<p>Minimum <b>Part 1 Test Score</b>: <input type=text name=\"part1test\" size=4 maxlength=3 value=\"$row[part1test]\"></p>";

echo "<p>Minimum <b>Part 2 Test Score</b>: <input type=text name=\"part2test\" size=4 maxlength=3 value=\"$row[part2test]\"></p>";
echo "<p><b>Years</b> an official has to take the Part 2 test: <input type=text name=\"part2yrs\" size=2 maxlength=2 value=\"$row[part2yrs]\"></p>";

echo "<p><b>MINIMUM CONTESTS</b> (previous year):</p>";
$sql2="DESCRIBE classificationsettings";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   if(preg_match("/contests/",$row2[0]))
   {
      $sport=preg_replace("/contests/","",$row2[0]);
      echo "<p>".GetSportName($sport).": <input type=text name=\"$row2[0]\" size=2 maxlength=2 value=\"".$row[$row2[0]]."\"></p>";
   }
}

echo "</div>";
echo "<input type=submit name=\"save\" value=\"Save Settings\">";
echo "</form>";

echo $end_html;



?>
