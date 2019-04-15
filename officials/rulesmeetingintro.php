<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$offid=GetOffID($session);
if($sport=='sp' || $sport=='pp') $offid=GetJudgeID($session);
$sportname=GetSportName($sport);
$sql="SELECT * FROM rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);   
$pay=split("-",$row[paydate]);
$end=split("-",$row[enddate]);
$totaltime=number_format($row[totaltime]/60,0,'.','');

echo $init_html;
if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
else echo GetHeader($session);
echo "<br><div class=alert style=\"width:550px;\">";
echo "<table cellspacing=2 cellpadding=2 class=nine width=100%><caption><b>Instructions for Completing the $year NSAA Online $sportname Rules Meeting:</b><br><i>Please read carefully!</i></caption>";
echo "<tr align=left><td><br>Today's date is ".date("F j, Y").".<br><br>";
if(!($sport!='sp' && $sport!='pp' && ($offid=='3427')) && !(($sport=='sp' || $sport=='pp') && $offid=='598') && !PastDue($row[startdate],-1))	//cannot attend rules meetings YET
{
   echo "The $year NSAA Online $sportname Rules Meeting will be available starting ".date("l, F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0])).".  The fee for completing this rules meeting online will be <b>$".$row[fee].".00</b> through ".date("l, F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0])).", after which the fee will increase to <b>$".$row[latefee].".00</b>. New officials and judges will be able to complete rules meetings online for NO CHARGE through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))."<br><br>";
   echo "The $year NSAA Online $sportname Rules Meeting will NOT be available after ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".";
   echo "</td></tr><tr align=center><td><br><a href=\"welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else if(GetOffID($session)!='3427' && PastDue($row[enddate],0))	//NO LONGER AVAILABLE
{
   echo "The $year NSAA Online $sportname Rules Meeting was available ".date("l, F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>";
   echo "This rules meeting is no longer available online.</td></tr>"; 
   echo "<tr align=center><td><br><a href=\"welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else
{
   if(PastDue($row[latedate],0))   //LATE FEE
   {
      echo "The $year NSAA Online $sportname Rules Meeting is now in the <b>LATE FEE</b> period (".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))." at midnight).New officials and judges will be able to complete rules meetings online for NO CHARGE through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))."<br><br>";
      echo "The late fee for this rules meeting is <b><u>$".$row[latefee].".00</u></b>.<br><br>";
   }
   else if(PastDue($row[paydate],0))    //PAY REGULAR FEE
   {
      echo "The $year NSAA Online $sportname Rules Meeting is now available for a fee of <b><u>$".number_format($row[fee],2,'.','')."</u></b>.  After midnight on <b>".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))."</b>, this meeting will be available for a <b>late fee of <u>$".number_format($row[latefee],2,'.','')."</u></b>.  New officials and judges will be able to complete rules meetings online for NO CHARGE through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>This meeting will NOT be available after <b>".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))."</b> at midnight.<br><br>";
   }
   else //NO FEE
   {
      echo "The $year NSAA Online $sportname Rules Meeting is available for <b>NO CHARGE</b> until <b>".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))."</b> at midnight.<br><br>";
      echo "After midnight on ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0])).", the fee will be <b>$".number_format($row[fee],2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($row[latefee],2,'.','')."</b>.New officials and judges will be able to complete rules meetings online for NO CHARGE through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>";
   }
   echo "This video is approximately $totaltime minutes long.<br><br>";
   if($sport=='te')
   {
      echo "<b>Please watch the video in its entirety.</b> At the END of the Rules Meeting Video, you will need to click the \"I'm FINISHED Watching the Rules Meeting Video\" in order to continue to the Verification Form.  Please do NOT stop the video yourself.  (You can, however, PAUSE the video if you need to take a break.)<br><br>";
      echo "<b>PLEASE NOTE:</b> We confirm your attendance at this meeting by having you enter your full name as your \"Electronic Signature\" at the end of this video.  Please allow your browser to redirect to the verification form at the end of the video so you can submit your Electronic Signature to the NSAA.<br><br>";
   }
   else if(PastDue($row[paydate],0))    //FEE
   {
      echo "<b>You must watch the rules meeting video IN ITS ENTIRETY.  At the END of the Rules Meeting Video, you will need to click the \"I'm FINISHED Watching the Rules Meeting Video\" in order to continue to the Credit Card Payment Form</b>.<br><br>";
      echo "<b>PLEASE NOTE:<br><br><u>You will NOT be marked as having \"COMPLETED ATTENDANCE\" for the $year $sportname Rules Meeting until you have SUBMITTED YOUR PAYMENT VIA THE CREDIT CARD PAYMENT FORM, which you will be taken to at the end of the rules meeting video</u></b>.<br><br>";
   }
   else //NO FEE
   {
      echo "<b>You must watch the rules meeting video IN ITS ENTIRETY.  At the END of the Rules Meeting Video, you will need to click the \"I'm FINISHED Watching the Rules Meeting Video\" in order to continue to the Verification Form</b>. <br><br>";
      echo "<b>PLEASE NOTE:<br><br><u>You will NOT be marked as having \"COMPLETED ATTENDANCE\" for the $year $sportname Rules Meeting until you have completed the Verification Form by \"signing\" your name (entering your full name as your electronic signature) at the end of the rules meeting video.<br><br>";   
   }
   echo "There will be no exceptions.</td></tr>";
   echo "<tr align=center><td><br><div class=normal><table width=100%><tr align=center><td><div class=error>WARNING:</div></td></tr><tr align=left><td>This video is <b>NOT</b> intended to be watched on a <b>dial-up internet connection</b>.  It is a large file that will be very cumbersome to view on a slower computer.<br><br>Also, some of you may find that your <b>network</b> has a <b>limit on the size of file</b> you are allowed to download.<br><br>PLEASE make sure you are using a high-speed internet connection on a newer (not older than 5 or 6 years) computer before starting the rules meeting video on the next page.</td></tr></table></div></td></tr>";
   echo "<tr align=left><td><br><b>Tips to optimize your viewing experience:</b><ul class=nine>";   
   echo "<li><b>Close all programs</b> running on your computer besides your Internet browser.</li>";   
   echo "<li><b>Do not open other browser windows</b> during the video and surf the Internet.  This can cause the video to \"skip\".</li>";   
   echo "<li>The time you begin watching this video will be recorded.  <b>Do NOT skip ahead</b> in the video or else your attendance will NOT be counted.  In addition, skipping forward in the video will cause viewing problems.</li>";   
   echo "<li><b>IMPORTANT:</b> Make sure your <b>VOLUME</b> is on so you can hear the assistant director speak.</li>";   
   echo "</ul></td></tr>";
   if($sport=='sp' || $sport=='pp')	//ASK JUDGE IF HE/SHE IS ALSO A COACH
   {
      echo "<tr align=center><td>";
      echo "<form method=post action=\"".$sport."rulesmeeting.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<table width='500px' cellspacing=0 cellpadding=4>";
      echo "<tr align=left><td><div class='normalwhite' style='padding:5px;font-size:12px;'><b><i>Are you also a HEAD COACH??</i></b><br><br>If <b>YES</b>, please select your school so you can receive credit as a HEAD COACH as well.<br><br>";
      echo "<b>I am a ".GetSportName($sport)." HEAD COACH</b> for <select name=\"school\"><option value=\"\">(Leave blank if you are not a ".GetSportName($sport)." Head Coach)</option>";
      $sql="SELECT t1.* FROM ".$db_name.".headers AS t1, ".$db_name.".".$sport."school AS t2 WHERE t1.id=t2.mainsch ORDER BY t1.school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
            echo "<option value=\"$row[school]\"";
            if($school==$row[school]) echo " checked";
            echo ">$row[school]</option>";
      }
      echo "</select></div><br><br><input type=submit name=\"continue\" value=\"Continue to Online ".GetSportName($sport)." Rules Meeting >>\">";
      echo "</td></tr>";
      echo "</table></form></td></tr>";
   }
   else if($sport=='bb' || $sport=='so' || $sport=='sw' || $sport=='tr')	//BOYS/GIRLS COACH TOO?
   {
      echo "<tr align=center><td>";
      echo "<form method=post action=\"".$sport."rulesmeeting.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<table width='500px' cellspacing=0 cellpadding=4>";
      echo "<tr align=left><td><div class='normalwhite' style='padding:5px;font-size:10pt;'><b><i>Are you also a HEAD COACH??</i></b><br><br>If <b>YES</b>, please select your school so you can receive credit as a HEAD COACH as well.<br><br>";
      echo "<b>I am a BOYS ".GetSportName($sport)." HEAD COACH</b> for <select name=\"school1\"><option value=\"\">(Leave blank if you are not a Boys ".GetSportName($sport)." Head Coach)</option>";
      $regfield=$sport."_b";
      $sql="SELECT * FROM ".$db_name.".headers ORDER BY school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
            echo "<option value=\"$row[school]\"";
            if($school1==$row[school]) echo " checked";
            echo ">$row[school]</option>";
      }
      echo "</select><br><br>";
      echo "<b>I am a GIRLS ".GetSportName($sport)." HEAD COACH</b> for <select name=\"school2\"><option value=\"\">(Leave blank if you are not a Girls ".GetSportName($sport)." Head Coach)</option>";
      $regfield=$sport."_g";
      $sql="SELECT * FROM ".$db_name.".headers ORDER BY school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
            echo "<option value=\"$row[school]\"";
            if($school2==$row[school]) echo " checked";
            echo ">$row[school]</option>";
      }
      echo "</select></div><br><br><input type=submit name=\"continue\" value=\"Continue to Online ".GetSportName($sport)." Rules Meeting >>\">";
      echo "</td></tr>";
      echo "</table></form></td></tr>";
   }
   else	//COACH FOR THIS SPORT TOO?
   {
      //echo "<tr align=center><td><br><a href=\"".$sport."rulesmeeting.php?session=$session\">Click HERE to Begin the ONLINE $year NSAA $sportname Rules Meeting</a></td></tr>";
      echo "<tr align=center><td>";
      echo "<form method=post action=\"".$sport."rulesmeeting.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<table width='500px' cellspacing=0 cellpadding=4>";
      echo "<tr align=left><td><div class='normalwhite' style='padding:5px;font-size:10pt;'><b><i>Are you also a HEAD ".strtoupper(GetSportName($sport))." COACH??</i></b><br><br>If <b>YES</b>, please select your school so you can receive credit as a HEAD COACH as well.<br><br>";      
      echo "<b>I am a ".GetSportName($sport)." HEAD COACH</b> for <select name=\"school1\"><option value=\"\">(Leave blank if you are not a ".GetSportName($sport)." Head Coach)</option>";
      $sql="SELECT * FROM ".$db_name.".headers ORDER BY school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
            echo "<option value=\"$row[school]\"";
            if($school1==$row[school]) echo " checked";
            echo ">$row[school]</option>";
      }
      echo "</select></div><br><br><input type=submit name=\"continue\" value=\"Continue to Online ".GetSportName($sport)." Rules Meeting >>\">";
      echo "</td></tr>";      
      echo "</table></form></td></tr>";
   }
   echo "</table></div>";
   echo $end_html;
   exit();
}
?>
