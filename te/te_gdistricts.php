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
$sport="te_g"; $sportname="Girls Tennis";

echo $init_html;
echo $header;

echo "<br><br><table width='600px' cellspacing=0 cellpadding=4><caption><b>Girls Tennis District Results</b><br><i>Please choose from the following.</i></caption>";
echo "<tr align=left><td><ul>";
echo "<li><a target=\"_blank\" href=\"https://nsaahome.org/teg.php\">Preview Girls Tennis Page</a></li><br>";
echo "<li class=bigger><form method=post action=\"../forms.php\">";                  
echo "<input type=hidden name=session value=\"$session\">";                  
echo "<input type=hidden name=activity_ch value=\"Girls Tennis\">";                  
echo "<b>$sportname Entry Forms:&nbsp;</b>";                  
echo "<select name=\"school_ch\"><option value=''>Choose a School</option>";                  
$sql="SELECT school FROM headers ORDER BY school";                  
$result=mysql_query($sql);                  
while($row=mysql_fetch_array($result))                     
   echo "<option>$row[school]</option>";                  
echo "</select>&nbsp;<input type=submit name=submit value=\"Go\"></form></li>";                  
echo "<li class=bigger><b>District Results:</b><br><br><ul>";
$sql="SELECT * FROM $db_name2.tegdistricts WHERE type='District' ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<li><b>District $row[class]-$row[district]:</b> [<a class=small href=\"te_gdistresults.php?session=$session&distid=$row[id]\">Edit</a>]&nbsp;[<a class=small href=\"../../tegdistresults.php?distid=$row[id]\" target=\"_blank\">Preview</a>]";
   if($row[resultssubmitted]==0)
      echo "<br>(These results have NOT been posted to the NSAA website yet.)";
   else
      echo "<br>(These results were last updated on the NSAA website on ".date("m/d/y",$row[resultssubmitted])." at ".date("g:ia T",$row[resultssubmitted]).")";
   echo "</li><br>";
}
echo "</ul></li>";
echo "<li class=bigger><a href=\"meetsadmin.php?session=$session&sport=$sport\">Manage $sportname Meets</a></li><br>";
echo "</ul></td></tr>";
echo "</table>";

echo $end_html;
?>
