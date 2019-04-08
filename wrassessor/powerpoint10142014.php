<?php
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

echo $init_html;
echo GetAssessorHeader($session);

//WR ASSESSOR POWERPOINT:
//IFRAME with slides saved as images; user clicks through each slide to get credit for "watching" the PP 

//GET INFO CURRENT IN DATABASE FOR THIS USER
$sql="SELECT * FROM wrassessors WHERE session='$session'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo "<br><table width='840px'><caption><b>STEP 2 - Weight Management Protocol PowerPoint</b><br><br><i>Please view <b>EACH SLIDE</b>, clicking the \"Next\" arrow to advance to the next slide.<br>You must view <b>ALL $slidect SLIDES</b> in order to complete this requirement.</i><br><Div class=alert style=\"font-size:9pt;width:600px;\"><b>NOTE:</b> You will be able to review this PowerPoint as often as you wish after watching it the first time and completing the $30 payment. It will be available when you login to this system.</div></caption>";
echo "<tr align=center><td colspan=3>";

//IFRAME
$slide="WRAssessor1.jpg";
$sql="UPDATE wrassessors SET slidesviewed='1' WHERE session='$session'";
$result=mysql_query($sql);
$curslide=1;
echo "<input type=hidden name=\"curslide\" id=\"curslide\" value=\"$curslide\">";
echo "<iframe id=\"slideframe\" name=\"slideframe\" frameborder=0 width='725px' height='550px' style='background-color:#ffffff;' src='showslide.php?slide=$curslide&session=$session'></iframe>";

//NAVIGATION
echo "</td></tr>";
echo "<tr><td width='33%' align=left><div id='prevslide' name='prevslide' style='display:none;'><input type=button class=fancybutton value='<< Previous' onClick=\"slideframe.src='showslide.php?slide=0&session=$session';\"></div>&nbsp;</td>";
echo "<td align=center width='33%'><div id='whichslide' name='whichslide' style='font-size:9pt;'><b>Slide 1 of $slidect</b></div></td>";
echo "<td width='34%' align=right><div id='nextslide' name='nextslide'><input type=button class=\"fancybutton\" value=\"Next\" onClick=\"document.all.slideframe.src='showslide.php?slide=2&session=$session';\"></div></td>";

echo "</td></tr></table>";


echo $end_html;
?>
