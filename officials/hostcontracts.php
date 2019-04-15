<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($sport=='fb')
{
   header("Location:fbbrackets.php?session=$session");
   exit();
}

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

echo "<br><form method=post action=\"hostcontracts.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Sport/Activity</option>";
$sql="SHOW TABLES LIKE '%districts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("districts",$row[0]);
   $curact=$temp[0];
   if((($sport=='pp' || $sport=='sp') && ($curact=='sp' || $curact=='pp')) || (!($sport=='pp' || $sport=='sp') && !($curact=='sp' || $curact=='pp')))
   {
      echo "<option value=\"$curact\"";
      if($sport==$curact) echo " selected";
      echo ">".GetSportName($curact)."</option>";
   }
}
echo "</select><br>";

if($sport && $sport!='')
{
   $sportname=GetSportName($sport);
   $districts=$sport."districts";
   $disttimes=$sport."disttimes";

   if ($sport=='vb')
   echo "<br><table width=400><caption><b>$sportname Sub-district Host Contracts: Main Menu<hr></b></caption>"; 
   else
   echo "<br><table width=400><caption><b>$sportname District Host Contracts: Main Menu<hr></b></caption>"; 
   echo "<tr align=center><td>";
   echo "<ul>";
   if ($sport=='vb')
   echo "<li><a href=\"hostbyhost.php?session=$session&sport=$sport\">View/Assign Sub-district Hosts: One at a Time</a><br><br></li>";
   else
   echo "<li><a href=\"hostbyhost.php?session=$session&sport=$sport\">View/Assign District Hosts: One at a Time</a><br><br></li>";
   if ($sport=='vb')
   echo "<li><a href=\"hostreport.php?session=$session&sport=$sport\">View/Post Sub-district Hosts: Report Format</a><br><br></li>";
   else
   echo "<li><a href=\"hostreport.php?session=$session&sport=$sport\">View/Post District Hosts: Report Format</a><br><br></li>";
   if(strlen($sport)>2 && !preg_match("/_/",$sport)  && $sport!='ubo' )
      $sport2=substr($sport,0,2)."_".substr($sport,2,1);
   else
      $sport2=$sport;
   echo "<li><a target=\"_blank\" href=\"../hostapp_".$sport2.".php?session=$session&nsaa=1\">Preview $sportname APPLICATION TO HOST</a><br><br></li>";
   if ($sport=='vb')
   echo "<li><a target=\"_blank\" href=\"hostcontract_".$sport.".php?session=$session&sample=1\">Preview $sportname Sub-district HOST CONTRACT</a><br><br></li>";
   else
   echo "<li><a target=\"_blank\" href=\"hostcontract_".$sport.".php?session=$session&sample=1\">Preview $sportname District HOST CONTRACT</a><br><br></li>";
   if ($sport=='vb')
   echo "<li><a target=\"_blank\" href=\"".$sport."showtoad.php?session=$session\">Preview $sportname Sub-dISTRICT DIRECTOR REPORT</a><br><br></li>";
   else
   echo "<li><a target=\"_blank\" href=\"".$sport."showtoad.php?session=$session\">Preview $sportname DISTRICT DIRECTOR REPORT</a><br><br></li>";
   if ($sport=='vb')
   echo "<li><a href=\"hostexport.php?session=$session&sport=$sport\" target=new>Export Sub-district Host Information</a><br><br></li>";
   else
   echo "<li><a href=\"hostexport.php?session=$session&sport=$sport\" target=new>Export District Host Information</a><br><br></li>";
   if($sport=='vb' || preg_match("/so/",$sport) || preg_match("/bb/",$sport))
      echo "<li><a href=\"substatebrackets.php?session=$session&sport=$sport\">DISTRICT FINAL Game Assignments</a><br><br></li>";
   echo "<li><a href=\"hostappsearch.php?session=$session\">Advanced Search: Applications to Host</a></td></tr>";
   echo "</ul>";
   echo "</td></tr></table>";
}//end if sport given
else
{
   echo "<br><br><i>Please select a sport.</i>";
}

echo $end_html;
?>
