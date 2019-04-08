<?php
/****************************
direxports.php
Landing page for the major
School Directory exports for
the program, website, etc
Created 9/12/11
Author: Ann Gaffigan
*****************************/

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("index.php?loginerror=1");
   exit();
}

echo $init_html;
echo $header;

echo "<br><br>";echo "<table><caption><b>School Directory EXPORTS:</b></caption><tr align=left><td><ul>";
echo "<li><a href=\"inhousedirexportexec.php?session=$session\" target=new>In-House Schools Database Export</a><br><div class=alert style='width:400px;'>This export will give you two tab-delimited exports compatible with the NSAA in-house Access database.</div></li>";
echo "<li><a href=\"printeddirexportexec.php?session=$session\" target=\"_blank\">Printed Directory Export</a><br><div class=alert style='width:400px;'>This export will generate the PDF pages to send to the printer.</div></li>";
echo "<li><a href=\"#\" onclick=\"window.open('printdirexportexec.php?online=1&session=$session','Online_Directory_Export','width=500,height=400');\">Online Directory Export (no E-mail
s)</a><br><div class=alert style='width:400px;'>This export will update the NSAA website with the current list of school information (without emails) as well as give you a one-column e
xport Ann can use to update the PDF online version of the Directory.</div></li>";
echo "</ul></td></tr></table>";

echo $end_html;
?>
