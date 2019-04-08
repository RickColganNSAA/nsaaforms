<?php
//IMPORT POST/GET VARIABLES
if($_REQUEST)
{
   foreach($_REQUEST as $key => $value)
   {
        $$key=$value;
   }
}
if($_FILES)
{
   foreach($_FILES as $key => $value)
   {
      $$key = $_FILES[$key]['tmp_name'];
   }
}
//AS OF 2014 - REDIRECT TO NEW ADMIN SCREENS WITH PDF GENERATION LINKS
if($sport=='bb_g' || $sport=='bbg')
   header("Location:bbgstateadmin.php?session=$session");
else
   header("Location:bbbstateadmin.php?session=$session");

exit();

//bbstateadmin.php: NSAA Basketball Admin for State Entry Forms
//Created 2/28/09 because e-mailed forms were not being received, possibly due to spam filter
//Author: Ann Gaffigan

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

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

echo $init_html;
echo $header;

echo "<form method=post action=\"bbstateadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>State Basketball Entry Forms Admin</b><br>";
if(!$sport || $sport=='') $sport="bb_b";
echo "<select name=\"sport\" onchange=\"submit();\"><option value=\"bb_b\"";
if($sport=='bb_b') echo " selected";
echo ">Boys Basketball</option><option value=\"bb_g\"";
if($sport=='bb_g') echo " selected";
echo ">Girls Basketball</option></select><br><br>";

$table=$sport."state";
if(!$sort) $sort="submitted DESC";
$sql="SELECT DISTINCT school,submitted FROM $table WHERE submitted!='' ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "</caption><tr align=center><td><br>No forms have been submitted yet.<br><br></td></tr>";
else
{
   echo "<i>The following State ".GetActivityName($sport)." Entry Forms have been Submitted:</i></caption>";
   echo "<tr align=center><td><a class=small href=\"bbstateadmin.php?session=$session&sport=$sport&sort=school\">School</a>";
   if($sort=="school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td><b>Form (.csv for Excel)</b></td><td><a class=small href=\"bbstateadmin.php?session=$session&sport=$sport&sort=submitted%20DESC\">Submitted</a>";
   if($sort=="submitted DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td></tr>";
}
$sportname=GetActivityName($sport);
while($row=mysql_fetch_array($result))
{
   $activ=$sportname;
   $activ_lower=strtolower($activ);
   $sch=ereg_replace(" ","",$row[school]);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   $filename.="state.csv";
   echo "<tr align=left><td>$row[school]</td><td><a class=small href=\"../attachments.php?session=$session&filename=$filename\">$filename</a></td>";
   echo "<td>".date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted])."</td></tr>";
}
echo "</table>";

echo "</form>";

echo $end_html;
?>
