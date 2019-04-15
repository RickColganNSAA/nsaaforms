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
$sportname=GetSportName($sport);
$sql="SELECT * FROM rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);   
$end=split("-",$row[enddate]);
$totaltime=number_format($row[totaltime]/60,0,'.','');

echo $init_html;
if($sport=='sppp') echo GetHeaderJ($session);
else echo GetHeader($session);
echo "<br><div class=alert style=\"width:550px;\">";
echo "<table cellspacing=2 cellpadding=2 class=nine width=100%><caption><b>Instructions for Completing the $year NSAA Online $sportname Rules Meeting:</b><br><i>Please read carefully!</i></caption>";
echo "<tr align=left><td><br>Today's date is ".date("F j, Y").".<br><br>";
if($offid!='3427' && $offid!='8114' && !PastDue($row[startdate],-1))	//cannot attend rules meetings YET
{
   echo "The $year NSAA Online $sportname Rules Meeting will be available starting ".date("l, F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0])).".  The fee for completing this rules meeting online will be <b>$".$row[fee].".00</b> through ".date("l, F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0])).", after which the fee will increase to <b>$".$row[latefee].".00</b>.<br><br>";
   echo "The $year NSAA Online $sportname Rules Meeting will NOT be available after ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".";
   echo "</td></tr><tr align=center><td><br><a href=\"welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else if($offid!='3427' && ($offid!='8114' || PastDue("2009-03-19",0)) && PastDue($row[enddate],0))	//NO LONGER AVAILABLE
{
   echo "The $year NSAA Online $sportname Rules Meeting was available ".date("l, F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>";
   echo "This rules meeting is no longer available online.</td></tr>"; 
   echo "<tr align=center><td><br><a href=\"welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else if($offid!='3427' && PastDue($row[latedate],0) && !PastDue($row[enddate],0))	//LATE FEE active
{
   echo "The $year NSAA Online $sportname Rules Meeting is now in the <b>LATE FEE</b> period (".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))." at midnight).<br><br>";
   echo "The late fee for this rules meeting is <b><u>$".$row[latefee].".00</u></b>.<br><br>";
   echo "This video is approximately $totaltime minutes long.  <b>You must watch the rules meeting video IN ITS ENTIRETY.  At the END of the Rules Meeting Video, you will be <u>AUTOMATICALLY RE-DIRECTED</u> to the Credit Card Payment Form</b>.  You must <b>NOT</b> stop the video yourself. (You can, however, PAUSE the video if you need to take a break.)<br><br>";
   echo "<b>PLEASE NOTE:<br><br><u>You will NOT be marked as having \"COMPLETED ATTENDANCE\" for the $year $sportname Rules Meeting until you have SUBMITTED YOUR PAYMENT VIA THE CREDIT CARD PAYMENT FORM, which you will be taken to at the end of the rules meeting video</u></b>.<br><br>";
   echo "There will be no exceptions.</td></tr>";
   echo "<tr align=center><td><br><div class=normal style=\"width:400px;\"><table width=100%><tr align=center><td><div class=error>WARNING:</div></td></tr><tr align=left><td>This video is <b>NOT</b> intended to be watched on a <b>dial-up internet connection</b>.  It is a large file that will be very cumbersome to view on a slower computer.<br><br>Also, some of you may find that your <b>network</b> has a <b>limit on the size of file</b> you are allowed to download.<br><br>PLEASE consider attending a rules meetings at one of the sites listed in the link below if you have a dial-up internet connection OR a limit on the size of file you can download through your network:<br><br><a class=small target=\"_blank\" href=\"/nsaaforms/officials/rulesschedule.php?sport=$sport\">$year $sportname Rules Meeting Sites</a></td></tr></table></div></td></tr>";
   echo "<tr align=left><td><br><b>Tips to optimize your viewing experience:</b><ul class=nine>";
   echo "<li><b>Close all programs</b> running on your computer besides your Internet browser.</li>";
   echo "<li><b>Do not open other browser windows</b> during the video and surf the Internet.  This can cause the video to \"skip\".</li>";
   echo "<li>The time you begin watching this video will be recorded.  <b>Do NOT skip ahead</b> in the video or else your attendance will NOT be counted.  In addition, skipping forward in the video will cause viewing problems.</li>";
   echo "<li><b>IMPORTANT:</b> Make sure your <b>VOLUME</b> is on so you can hear the assistant director speak.</li>";
   echo "</ul></td></tr>";
   echo "<tr align=center><td><br><a href=\"".$sport."rulesmeeting.php?session=$session\">Click HERE to Begin the ONLINE $year NSAA $sportname Rules Meeting</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else	//AVAILABLE, NO LATE FEE
{
   echo "The $year NSAA Online $sportname Rules Meeting is available for <b>$".$row[fee].".00</b> through ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be <b>$".$row[latefee].".00</b> through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))." at midnight (after which it will no longer be available).<br><br>";
   echo "PLEASE NOTE that you must complete the ENTIRE video for this rules meeting by midnight on ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." in order to not be charged with the late fee.<br><br>";
   echo "This video is approximately $totaltime minutes long.  <b>You must watch the rules meeting video IN ITS ENTIRETY.  At the END of the Rules Meeting Video, you will be <u>AUTOMATICALLY RE-DIRECTED</u> to the Credit Card Payment Form</b>.  You must <b>NOT</b> stop the video yourself. (You can, however, PAUSE the video if you need to take a break.)<br><br>";   
   echo "<b>PLEASE NOTE:<br><br><u>You will NOT be marked as having \"COMPLETED ATTENDANCE\" for the $year $sportname Rules Meeting until you have SUBMITTED YOUR PAYMENT VIA THE CREDIT CARD PAYMENT FORM, which you will be taken to at the end of the rules meeting video</u></b>.<br><br>";
   echo "There will be no exceptions.</td></tr>";
   echo "<tr align=center><td><br><div class=normal style=\"width:400px;\"><table width=100%><tr align=center><td><div class=error>WARNING:</div></td></tr><tr align=left><td>This video is <b>NOT</b> intended to be watched on a <b>dial-up internet connection</b>.  It is a large file that will be very cumbersome to view on a slower computer.<br><br>Also, some of you may find that your <b>network</b> has a <b>limit on the size of file</b> you are allowed to download.<br><br>PLEASE consider attending a rules meetings at one of the sites listed in the link below if you have a dial-up internet connection OR a limit on the size of file you can download through your network:<br><br><a class=small target=\"_blank\" href=\"/nsaaforms/officials/rulesschedule.php?sport=$sport\">$year $sportname Rules Meeting Sites</a></td></tr></table></div></td></tr>";
   echo "<tr align=left><td><br><b>Tips to optimize your viewing experience:</b><ul class=nine>";   
   echo "<li><b>Close all programs</b> running on your computer besides your Internet browser.</li>";   
   echo "<li><b>Do not open other browser windows</b> during the video and surf the Internet.  This can cause the video to \"skip\".</li>";   
   echo "<li>The time you begin watching this video will be recorded.  <b>Do NOT skip ahead</b> in the video or else your attendance will NOT be counted.  In addition, skipping forward in the video will cause viewing problems.</li>";   
   echo "<li><b>IMPORTANT:</b> Make sure your <b>VOLUME</b> is on so you can hear the assistant director speak.</li>";   
   echo "</ul></td></tr>";
   echo "<tr align=center><td><br><a href=\"".$sport."rulesmeeting.php?session=$session\">Click HERE to Begin the ONLINE $year NSAA $sportname Rules Meeting</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
?>
