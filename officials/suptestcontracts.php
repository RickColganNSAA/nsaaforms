<?php
//NOT IN USE AS OF FALL 2011
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session,"contractadmin");

echo "<br><form method=post action=\"suptestcontracts.php\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<br><table width=400><caption><b>Supervised Test Hosts: Main Menu<hr></b></caption>"; 
echo "<tr align=center><td>";
echo "<ul>";
echo "<li><a href=\"addsuptesthost.php?session=$session\">Assign New Supervised Test Host</a><br><br>";
echo "<li><a href=\"suptesthostbyhost.php?session=$session\">Search for Supervised Test Host</a><br><br></li>";
echo "<li><a href=\"suptesthostreport.php?session=$session\">Supervised Test Host Report</a><br><br></li>";
echo "<li><a href=\"suptesthostexport.php?session=$session\" target=new>Export Supervised Test Host Information (ALL Activities)</a><br><br></li>";
echo "<li><a href=\"suptestschedule.php?session=$session\" target=\"_blank\">Preview Supervised Test Schedule (posted to NSAA Officials & Judges)</a><br><br></li>";
echo "</ul>";
echo "</td></tr></table>";

echo $end_html;
?>
