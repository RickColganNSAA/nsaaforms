<?php

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

echo $init_html;
echo GetHeaderJ($session,"jcontractadmin");

echo "<br /><a href=\"jtourndates.php?session=$session\">&larr; Edit Postseason Dates for Apps to Host, Apps to Officiate, Lodging</a><br><br />";

echo "<table><caption style=\"background-color:#E0E0E0\"><b>Judges Contracts:</b></caption>";
echo "<tr align=left><td>";
echo "<br><form method=post action=\"hostcontracts.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<b>Host Contracts:</b>&nbsp;";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Activity</option><option value='pp'>Play Production</option><option value='sp'>Speech</option></select></form></td></tr>";
echo "<tr align=left><td>";
echo "<form method=post action=\"jcontractadmin.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<b>Judges Contracts:</b>&nbsp;";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Activity</option>";
echo "<option value='pp'";
if($sport=='pp') echo " selected";
echo ">District Play</option><option value='pp-state'";
if($sport=="pp-state") echo " selected";
echo ">State Play</option><option value='sp'";
if($sport=="sp") echo " selected";
echo ">District Speech</option><option value='sp-state'";
if($sport=="sp-state") echo " selected";
echo ">State Speech</option></select>";
echo "</td></tr>";
if($sport && $sport!='')
{
   if(ereg("state",$sport)) { $type="State"; $isstate=1; }
   else { $type="District"; $isstate=0; }
   if(ereg("sp",$sport)) $sportname="Speech";
   else $sportname="Play";
   echo "<tr align=left><td><a class=small target=\"_blank\" href=\"playcontract.php?session=$session&sport=$sport&sample=1&state=$isstate\">Preview Sample of $type $sportname Contract</a></td></tr>";
   echo "<tr align=left><td><a class=small href=\"assignplay2.php?session=$session&sport=$sport\">Assign $type $sportname Judges</a></td></tr>";
   echo "<tr align=left><td><a class=small href=\"assignreportplay.php?session=$session&sport=$sport\">Assignment Report</a></td></tr>";
   echo "<tr align=left><td><a class=small href=\"playcontracts.php?session=$session&sport=$sport\">Contract Responses</a></td></tr>";
   echo "<tr align=left><td><a class=small href=\"judgesexport.php?session=$session&sport=$sport\">Export $type $sportname Judges for whom NSAA Accepted a Contract</a></td></tr>";
   if($sport=="sp-state")
   {
      echo "<tr align=left><td><a class=small href=\"sproomsexport.php?session=$session\">State Speech Room Assignments Export</a></td></tr>";
      echo "<tr align=left><td><a target=new class=small href=\"playcontract.php?session=$session&sport=sp&sample=1&state=1\">Sample State Speech Contract</a></td></tr>";
   }
}
echo "</form></table>";
echo $end_html;
?>
