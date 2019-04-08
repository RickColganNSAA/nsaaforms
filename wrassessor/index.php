<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidAssessor($session) || !$session)
{
   header("Location:../wrassessor.php?error=1");
}

$userid=GetWRAUserID($session);

//WR ASSESSOR MAIN MENU
if(IsPaid($userid))
{
   $year=date("Y");
   if(date("m")<6) $year--;
   $year1=$year+1;
   echo $init_html;
   echo GetAssessorHeader($session);
   echo "<br><br><table><tr align=center><td><h2><i>Congratulations, ".GetWRAUserName($userid)."!</i></h2><p><i>You have successfully completed the NSAA Assessor Registration process for the $year-$year1 school year.</i></p>";
   if($appid)
   {
      $sql="SELECT * FROM wrassessorsapp WHERE appid='$appid' AND assessorid='$userid'";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result))
      {
         echo "<p><a href=\"printreceipt.php?session=$session&appid=$appid\" target=\"_blank\">Print Your Receipt & Confirmation</a></p>";
      }
   }
   echo "<div class=\"alert\">";
   echo "<h3>Important Information for Wrestling Assessors:</h3>";
   $sql2="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='wrassessor'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[ppfile]=="") $row2[ppfile]=$row2[lockedversion];
   echo "<ul><li><a href=\"$row2[ppfile]\" target=\"_blank\">$year-$year1 Wrestling Assessor PowerPoint</a></li>";
   echo "<br><li>More resources located on the <a href=\"/wrestling\" target=\"_blank\">Wrestling Page on the NSAA Website</a>, under \"Assessors.\"</li>";
   echo "</ul></div>";
   echo "<br><p><b><i>Phone number, e-mail or other account information changed?</b></i>&nbsp;&nbsp;&nbsp;<a href=\"updateinfo.php?session=$session\">Update your Account Profile HERE</a></p>";
   echo "</td></tr></table>";
   echo $end_html;
   exit();
}
else if(WatchedAllSlides($userid))
{
   header("Location:payment.php?session=$session");
   exit();
}
else
{
   header("Location:updateinfo.php?session=$session");
   exit();
}
?>
