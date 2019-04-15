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
echo GetHeader($session,"obshome");

echo "<br><table class='nine'><caption><b>Observations & Observers:</b></caption>";
echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;Observations:</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td><a href=\"obsadmin2.php?session=$session\">Advanced Search: Officials Receiving Observations</a></td></tr>";
echo "<tr align=left><td><a href=\"obsadmin.php?submitted=no&session=$session\">Observations SAVED but NOT SUBMITTED by the NSAA</a></td></tr>";
echo "<tr align=left><td><a href=\"obsadmin.php?submitted=yes&session=$session\">Observations SUBMITTED by the NSAA</a></td></tr>";
echo "<tr align=left><td><br><b>Printable Versions of Observation Forms:</b><br><table>";
$sql="SHOW TABLES LIKE '%observe'";
$result=mysql_query($sql);
echo "<tr align=left valign=top><td><ul>";
while($row=mysql_fetch_array($result))
{      
   $temp=split("observe",$row[0]);
   if(preg_match("/(clinic)/",$temp[0]))
   {
      $temp2=split("clinic",$temp[0]);
      $sportname=GetSportName($temp2[0])." CLINIC";
      $page=$temp2[0]."clinicobserve.php";
   }
   else 
   {
      $sportname=GetSportName($temp[0]); $page=$temp[0]."observe.php";
   }
   echo "<li><a target=new href=\"".$page."?session=$session&print=1\">".$sportname."</a></li>";
}
echo "</ul></td></tr></table>";
//echo "<br><a target=new href=\"fbobs_contact.html\">Football Observers Names & Addresses</a><br>";
echo "</td></tr></table></td></tr>";

echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;Observers:</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td><a href=\"#\" onClick=\"window.open('add_obs.php?session=$session','addobs','menubar=yes,resizable=yes,scrollbars=yes,titlebar=yes,width=600,height=600');\">Add New Observer</a></td></tr>";
echo "<tr align=left><td><a href=\"obs_query.php?session=$session\">Advanced Search: Observers</a></td></tr>";
echo "</table></td></tr>";
echo "</table>";

echo $end_html;
?>
