<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

echo "<br><br><table width='800px' cellspacing=0 cellpadding=4><caption><b>Cross-Country District Results & State Qualifiers</b><br><i>Please choose from the following reports.</i></caption>";
echo "<tr align=left><td><ul>";
echo "<li class=bigger><b>State Entries Exports:</b><br>";
echo "<table cellspacing=4 cellpadding=4>";
echo "<tr align=left valign=top><td><ul>";
$classes=GetClasses('ccb');
//for($i=0;$i<count($classes);$i++)
//{
   //echo "<li><a class=small href=\"cc_stateentries.php?session=$session&sport=cc_b&class=".$classes[$i]."\">Boys Class ".$classes[$i]."</a></li><br>";
   echo "<li><a class=small href=\"cc_stateentries.php?session=$session&sport=cc_b&class=".$classes[$i]."\">CrossCountryStateEntries</a></li><br>";
//}
//echo "</ul></td><td><ul>";
/* for($i=0;$i<count($classes);$i++)
{
   echo "<li><a class=small href=\"cc_stateentries.php?session=$session&sport=cc_g&class=".$classes[$i]."\">Girls Class ".$classes[$i]."</a></li><br>";
}*/
echo "</ul></td></tr></table></li>"; 
echo "<li><a href=\"cc_distreport.php?session=$session\">Report of Submitted District Results</a><br><i>This report lists each district and whether or not boys/girls results have been submitted for that district.</i></li><br>";
echo "<li><a href=\"cc_teamreport.php?session=$session\">Cross-Country Team Report</a><br><i>This report will sort qualifying teams by the number of students checked on that team's list of runners going to the State Meet.</i></li><br>";
echo "<li class=bigger><b>Printed Program Exports:</b>";
echo "<table cellspacing=3 cellpadding=3>";
echo "<tr align=left valign=top><td><ul>";
for($i=0;$i<count($classes);$i++)
{
   echo "<li><a class=small target='_blank' href=\"cc_programexport.php?session=$session&sport=cc_b&class=".$classes[$i]."\">Boys Class ".$classes[$i]."</a></li><br>";
}
echo "</ul></td><td><ul>";
for($i=0;$i<count($classes);$i++)
{
   echo "<li><a class=small target='_blank' href=\"cc_programexport.php?session=$session&sport=cc_g&class=".$classes[$i]."\">Girls Class ".$classes[$i]."</a></li><br>";
}
echo "</ul></td></tr></table></li>";
echo "<li class=bigger><b>Export District Results to NSAA Website:</b>&nbsp;&nbsp;|&nbsp;&nbsp;<a target=\"_blank\" href=\"https://nsaahome.org/cc.php\">Preview Cross-Country Page on NSAA Website</a><br>";
echo "<table class=nine width='750px' cellspacing=3 cellpadding=3><tr align=left valign=top><td>";
for($i=0;$i<count($classes);$i++)
{
   $filename="ccbClass".$classes[$i]."Results.html";
   echo "Boys Class $classes[$i]:  <a class=small target='_blank' href=\"cc_webexport.php?session=$session&sport=cc_b&class=".$classes[$i]."\">Preview</a><br><a class=small target=\"_blank\" href=\"cc/publish.php?session=$session&filename=$filename\">Publish Results to Website</a>&nbsp;|&nbsp;<a class=small target=\"_blank\" href=\"cc/publish.php?session=$session&blank=1&filename=$filename\">Publish \"Info Not Available\" Page</a><br><br>";
}
echo "</td><td>";
for($i=0;$i<count($classes);$i++)
{
   $filename="ccgClass".$classes[$i]."Results.html";   
   echo "Girls Class $classes[$i]:  <a class=small target='_blank' href=\"cc_webexport.php?session=$session&sport=cc_g&class=".$classes[$i]."\">Preview</a><br><a class=small target=\"_blank\" href=\"cc/publish.php?session=$session&filename=$filename\">Publish Results to Website</a>&nbsp;|&nbsp;<a class=small target=\"_blank\" href=\"cc/publish.php?session=$session&blank=1&filename=$filename\">Publish \"Info Not Available\" Page</a><br><br>";
}
echo "</ul></td></tr></table></li>";
echo "<li class=bigger><b>Reports of Number of Qualifiers per Team, by Class:</b><br><br>";
echo "<ul>";
for($i=0;$i<count($classes);$i++)
{
   echo "<li><a class=small href=\"cc_qualreport.php?session=$session&class=".$classes[$i]."\">Class $classes[$i]</a></li><br>";
}
echo "</ul></li>";
echo "</ul></td></tr>";
echo "</table>";

echo $end_html;
?>
