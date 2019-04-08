<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$userid=GetWRAUserID($session);

//WR ASSESSOR MAIN MENU
if(IsPaid($userid))
{
   echo $init_html;
   echo "<table cellspacing=3 cellpadding=3><tr align=center><td>";
   if($appid)   
   {      
      $sql="SELECT * FROM wrassessorsapp WHERE appid='$appid' AND assessorid='$userid'";      
      $result=mysql_query($sql);      
      if($row=mysql_fetch_array($result))
      {         
         echo $row[html];
      }
      else echo "ERROR: mismatched user and appid.";
   }
   else echo "ERROR: no appid.";
   echo "</td></tr></table>";
   echo $end_html;
}
?>
