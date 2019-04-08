<?php
/*******************************
Re-created Oct 2014 to handle
an Articulate presentation instead
of a slide-by-slide, no-audo PPT
10/14/14
Ann Gaffigan
********************************/
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidAssessor($session))
{
   header("Location:../wrassessor.php?error=3");
   exit();
}

$sport='wr'; $sportname="Wrestling";

$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='wrassessor'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$filepath=$row[lockedversion];

//ELSE MARK TIME MOVIE IS INITIATED:
$sql="UPDATE wrassessors SET dateinitiated='".time()."',datecompleted='0' WHERE session='$session'";
$result=mysql_query($sql);

echo $init_html;
echo "<table width='100%' class=nine cellspacing=0 cellpadding=0><tr align=center><td>";

echo "<div id='header' style='width:95%;padding:3px;text-align:right;'><form method=post action=\"payment.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Video\"></form></div>";

echo "<iframe src=\"$filepath\" style='width:100%;height:600px;valign:top;background-color:#ffffff;border:none;'></iframe>";

echo "<div id='footer' style='width:95%;padding:3px;text-align:center;'><form method=post action=\"payment.php\"><input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"sport\" value=\"$sport\"><input type=submit class=fancybutton2 style=\"float:right;\" name=\"finish\" value=\"I'm FINISHED Watching the Video\"></form></div>";

echo $end_html;
?>
