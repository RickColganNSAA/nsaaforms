<?php

require '../functions.php';
require '../variables.php';
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
$sport="te_b"; $sportname="Boys Tennis";
if ($type){
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=tennis_boys_coaches.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('School','Head Coach','Head Coach Cell Number','Assistant Coach'));

    $rows = mysql_query("SELECT te_bschool.school, logins.name, head_coach_phone,assistant_coach FROM te_bschool,logins WHERE logins.school=te_bschool.school AND logins.sport='Boys Tennis'");
    while ($row = mysql_fetch_assoc($rows))
    {	fputcsv($output, $row); }

    exit;
}
echo $init_html;
echo $header;

echo "<br><br><table width='600px' cellspacing=0 cellpadding=4><caption><b>Boys Tennis State Seeding</b><br><i>Please choose from the following reports.</i></caption>";
echo "<tr align=left><td><ul>";
echo "<li><a href=\"stateentries.php?sport=te_b&session=$session\">Printable Regular Season Meet Results for Players in the State Meet</a></li><br>";
echo "<li><a href=\"stateseeds.php?session=$session&sport=te_b\">State Meet Seeds & Brackets</a></li><br>";
echo "<li class=bigger>Preview/Publish Brackets:&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<a target=\"_blank\" href=\"https://nsaahome.org/teb.php\">Preview Boys Tennis Page</a><br>";
echo "<table cellpadding=3 cellspacing=1><tr align=left valign=top><td><b>CLASS A:</b><br><br>";
echo "#1 Singles:&nbsp;&nbsp;<a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=A;singles1&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassAsingles1.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "#2 Singles:&nbsp;&nbsp;<a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=A;singles2&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassAsingles2.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "#1 Doubles: <a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=A;doubles1&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassAdoubles1.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "#2 Doubles: <a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=A;doubles2&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassAdoubles2.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "</td><td width='20px'>&nbsp;</td><td><b>CLASS B:</b><br><br>";
echo "#1 Singles:&nbsp;&nbsp;<a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=B;singles1&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassBsingles1.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "#2 Singles:&nbsp;&nbsp;<a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=B;singles2&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassBsingles2.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "#1 Doubles: <a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=B;doubles1&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassBdoubles1.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "#2 Doubles: <a class=small target=\"_blank\" href=\"statebrackets.php?session=$session&classdiv=B;doubles2&pdf=1\">Preview</a>&nbsp;&nbsp;";
$filename="tebclassBdoubles2.pdf";
echo "<a class=small target=\"_blank\" href=\"publish.php?session=$session&filename=$filename\">Publish to Website</a><br>";
echo "</td></tr></table></li><br>";
echo "<li class=bigger><form method=post action=\"../forms.php\">";                  
echo "<input type=hidden name=session value=\"$session\">";                  
echo "<input type=hidden name=activity_ch value=\"Boys Tennis\">";                  
echo "<b>Boys Entry Forms:&nbsp;";                  
echo "<select name=\"school_ch\"><option value=''>Choose a School</option>";                  
$sql="SELECT school FROM headers ORDER BY school";                  
$result=mysql_query($sql);                  
while($row=mysql_fetch_array($result))                     
   echo "<option>$row[school]</option>";                  
echo "</select>&nbsp;<input type=submit name=submit value=\"Go\"></form></li>";                  
echo "<li class=bigger><b>Export Substitues:</b> <a href=\"subsexport.php?session=$session&class=A&sport=$sport\">Class A</a>&nbsp;|&nbsp;<a href=\"subsexport.php?class=B&sport=$sport&session=$session\">Class B</a></li><br>";
echo "<li class=bigger><a href=\"meetsadmin.php?session=$session&sport=$sport\">Manage $sportname Meets</a></li><br>";
echo "<li class=bigger>Calculate Team Scores:<br>";
echo "<a href=\"teamscores.php?session=$session&sport=te_b&class=A\" target=\"_blank\">Class A</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a href=\"teamscores.php?session=$session&sport=te_b&class=B\" target=\"_blank\">Class B</a></li><br>";
echo "<li class=bigger>Get ROSTERS:<br>";
echo "<a href=\"rosters.php?session=$session&sport=te_b&class=A\" target=\"_blank\">Class A</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a href=\"rosters.php?session=$session&sport=te_b&class=B\" target=\"_blank\">Class B</a></li><br>";
echo "<li class=bigger>Get SEEDED Players:<br>";
echo "<a href=\"seededplayers.php?session=$session&sport=te_b&class=A\" target=\"_blank\">Class A</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a href=\"seededplayers.php?session=$session&sport=te_b&class=B\" target=\"_blank\">Class B</a></li><br>";
echo "<li class=bigger>Tennis Coaches Information Export:<br>";
echo "<a href=\"te_bmain.php?session=$session&type=export\" target=\"_blank\">Export</a></li><br>";
echo "</ul></td></tr>";
echo "</table>";

echo $end_html;
?>
