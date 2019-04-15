<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($sport=='sp' || $sport=='pp')
  $level=GetLevelJ($session);
else
   $level=GetLevel($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session,"jcontractadmin");
else echo GetHeader($session,"contractadmin");

echo "<br><form method=post action=\"rulescontracts.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Sport/Activity</option>";
$sql="SHOW TABLES LIKE '%ruleshosts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("ruleshosts",$row[0]);
   $curact=$temp[0];
   echo "<option value=\"$curact\"";
   if($sport==$curact) echo " selected";
   echo ">".GetSportName($curact)."</option>";
}
echo "</select><br>";

if($sport && $sport!='')
{
   $sportname=GetSportName($sport);
   $ruleshosts=$sport."ruleshosts";

   echo "<br><table width=400><caption><b>$sportname Rules Meeting Hosts: Main Menu<hr></b></caption>"; 
   echo "<tr align=center><td>";
   echo "<ul>";
   echo "<li><a href=\"addruleshost.php?session=$session&sport=$sport\">Assign New Rules Meeting Host</a><br><br>";
   echo "<li><a href=\"ruleshostbyhost.php?session=$session&sport=$sport\">Search for Rules Meeting Host</a><br><br></li>";
   echo "<li><a href=\"ruleshostreport.php?session=$session&sport=$sport\">Rules Meeting Host Report</a><br><br></li>";
   echo "<li><a href=\"ruleshostexport.php?session=$session&sport=$sport\" target=new>Export Rules Meeting Host Information (ALL Activities)</a><br><br></li>";
   echo "<li><a href=\"rulesschedule.php?session=$session\" target=\"_blank\">Preview Rules Meeting Schedule (posted to NSAA Officials & Judges)</a><br><br></li>";
   echo "</ul>";
   echo "</td></tr></table>";
}//end if sport given
else
{
   echo "<br><br><i>Please select a sport.</i>";
}

echo $end_html;
?>
