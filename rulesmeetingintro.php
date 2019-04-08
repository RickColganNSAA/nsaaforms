<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$school=GetSchool($session);
$coachid=GetUserID($session);
$sportname=GetActivityName($sport);
$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$pay=split("-",$row[paydate]);
$late=split("-",$row[latedate]);   
$end=split("-",$row[enddate]);
$totaltime=number_format($row[totaltime]/60,0,'.','');

echo $init_html;
echo GetHeader($session);
echo "<br><div class=alert style=\"width:550px;\">";
echo "<table cellspacing=2 cellpadding=2 class=nine width=100%><caption><b>Instructions for Completing the $year NSAA Online $sportname Rules Meeting:</b><br><i>Please read carefully!</i></caption>";
echo "<tr align=left><td><br>Today's date is ".date("F j, Y").".<br><br>";
if(!PastDue($row[startdate],-1) && $school!="Test's School")	//cannot attend rules meetings YET
{
   if($sport=='te')
      echo "The Online $sportname Rules Meeting will be available on ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0])).".";
   else 
      echo "The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b> from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which the fee will be <b>$".number_format($row[fee],2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($row[latefee],2,'.','')."</b>.<br><br>The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".";
   echo "</td></tr><tr align=center><td><br><a href=\"welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else if($school!="Test's School" && PastDue($row[enddate],0))	//NO LONGER AVAILABLE
{
   echo "The $year NSAA Online $sportname Rules Meeting was available ".date("l, F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("l, F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>";
   echo "This rules meeting is no longer available online.</td></tr>"; 
   echo "<tr align=center><td><br><a href=\"welcome.php?session=$session\">Home</a></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}
else	//AVAILABLE
{
   if($sport=='te')
      echo "The $year NSAA Online $sportname Rules Meeting is now available at NO CHARGE.  However, we would like to confirm your attendance.<br><br>";
   else if(PastDue($row[latedate],0))	//LATE FEE 
   {
      echo "The $year NSAA Online $sportname Rules Meeting is now in the <b>LATE FEE</b> period (".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))." at midnight).<br><br>";
      echo "The late fee for this rules meeting is <b><u>$".$row[latefee].".00</u></b>.<br><br>";
   }
   else if(PastDue($row[paydate],0))	//PAY REGULAR FEE
      echo "The $year NSAA Online $sportname Rules Meeting is now available for a fee of <b><u>$".number_format($row[fee],2,'.','')."</u></b>.  After midnight on <b>".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))."</b>, this meeting will be available for a <b>late fee of <u>$".number_format($row[latefee],2,'.','')."</u></b>.  This meeting will NOT be available after <b>".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0]))."</b> at midnight.<br><br>";
   else	//NO FEE
   {
      echo "The $year NSAA Online $sportname Rules Meeting is available for <b>NO CHARGE</b> until <b>".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))."</b> at midnight.<br><br>";
      if($row[fee]>0 && $row[latefee]>0)
         echo "After midnight on ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0])).", the fee will be <b>$".number_format($row[fee],2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($row[latefee],2,'.','')."</b>.<br><br>The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".<br><br>";
   }
   echo "This video is approximately $totaltime minutes long.<br><br>";
   if($sport=='te')
   {
      echo "<b>Please watch the video in its entirety.</b> At the END of the Rules Meeting Video, you will need to click the \"I'm FINISHED Watching the Online Rules Meeting Video\" in order to continue to the Verification Form.<br><br>";
      echo "<b>PLEASE NOTE:</b> We confirm your attendance at this meeting by having you enter your full name as your \"Electronic Signature\" at the end of this video.  Please allow your browser to redirect to the verification form at the end of the video so you can submit your Electronic Signature to the NSAA.<br><br>";
   }
   else if(PastDue($row[paydate],0))	//FEE
   {
      echo "<b>You must watch the rules meeting video IN ITS ENTIRETY.  At the END of the Rules Meeting Video, you will need to click the \"I'm FINISHED Watching the Online Rules Meeting Video\" in order to continue to the Credit Card Payment Form.<br><br>";
      echo "<b>PLEASE NOTE:<br><br><u>You will NOT be marked as having \"COMPLETED ATTENDANCE\" for the $year $sportname Rules Meeting until you have SUBMITTED YOUR PAYMENT VIA THE CREDIT CARD PAYMENT FORM, which you will be taken to at the end of the rules meeting video</u></b>.<br><br>";
   }
   else	//NO FEE
   {
      echo "<b>You must watch the rules meeting video IN ITS ENTIRETY.  At the END of the Rules Meeting Video, you will need to click the \"I'm FINISHED Watching the Online Rules Meeting Video\" in order to continue to the Verification Form.<br><br>";
      echo "<b>PLEASE NOTE:<br><br><u>You will NOT be marked as having \"COMPLETED ATTENDANCE\" for the $year $sportname Rules Meeting until you have completed the Verification Form by \"signing\" your name (entering your full name as your electronic signature) at the end of the rules meeting video.<br><br>";
   }
   if($sport!='te') echo "There will be no exceptions.</td></tr>";
   echo "<tr align=center><td><br><div class=normal style=\"width:400px;\"><table width=100%><tr align=center><td><div class=error style=\"width:97%;\">WARNING:</div></td></tr><tr align=left><td>This video is <b>NOT</b> intended to be watched on a <b>dial-up internet connection</b>.  It is a large file that will be very cumbersome to view on a slower computer.<br><br>PLEASE make sure you are using a high-speed internet connection on a newer (not older than 5 or 6 years) computer before starting the rules meeting video on the next page.</td></tr></table></div></td></tr>";
   echo "<tr align=left><td><br><b>Tips to optimize your viewing experience:</b><ul class=nine>";
   echo "<li><b>Close all programs</b> running on your computer besides your Internet browser.</li>";
   echo "<li><b>Do not open other browser windows</b> during the video and surf the Internet.</li>";
   echo "<li>The time you begin watching this video will be recorded.  <b>Do NOT skip ahead</b> in the video or else your attendance will NOT be counted.  In addition, skipping forward in the video will cause viewing problems.</li>";
   echo "<li><b>IMPORTANT:</b> Make sure your <b>VOLUME</b> is on so you can hear the assistant director speak.</li>";
   echo "</ul></td></tr>";
   //ARE YOU ALSO AN OFFICIAL?
   if($sport!='te' && $sport!='ad' && !preg_match("/go/",$sport))
   {
      echo "<tr align=center><td>";
      echo "<form method=post action=\"".$sport."rulesmeeting.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<table width='500px' cellspacing=0 cellpadding=4>";
      if($sport=='sp' || $sport=='pp') $offjudge="judge";
      else $offjudge="official";
      echo "<tr align=left><td><div class='normalwhite' style='padding:5px;font-size:10pt;'><b><i>Are you also a ".strtoupper(GetActivityName($sport))." ".strtoupper($offjudge)."?</i></b><br><br>If <b>YES</b>, please enter your <b><u>".strtoupper($offjudge)."'s PASSCODE</b></u> below so you can receive credit for both.<br><br>";
      echo "<b>My ".strtoupper($offjudge)."'s Passcode is:</b> <input type=password name=\"passcode\" size=15><br>";
      echo "(If you are NOT a ".GetActivityName($sport)." $offjudge, simply leave the passcode field above blank.)";
      echo "</div><br>";
   }
   else 
   {
      echo "<tr align=center><td>";
      echo "<form method=post action=\"".$sport."rulesmeeting.php\">";      
      echo "<input type=hidden name=session value=\"$session\">";      
      echo "<table width='500px' cellspacing=0 cellpadding=4><tr align=center><td>";
   }

   //ARE YOU A COACH/DIRECTOR OF THE SISTER/BROTHER SPORT TOO?
   $cursp=GetActivity($session);
   if($sport=='sppp' || preg_match("/Boys/",$cursp) || preg_match("/Girls/",$cursp))
   {
      if($sport=='sppp')
      {
         $name=GetUserName($session);
         if($cursp=="Speech") $othersp="Play Production";
         else $othersp="Speech";
         $sql2="SELECT name FROM logins WHERE sport='$othersp' AND school='".addslashes($school)."'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
	 if(trim($row2[name])==trim($name))
     	 {
	    echo "<div class='normalwhite' style='padding:5px;font-size:10pt;'><b><u>You are also listed as the $othersp Director for $school.</b></u> You will be given credit as both the $cursp AND $othersp Director.</div><br>";
         }
      }
      else 
      {
         $name=GetUserName($session);
         if(preg_match("/Girls/",$cursp)) $othersp=preg_replace("/Girls/","Boys",$cursp);
         else $othersp=preg_replace("/Boys/","Girls",$cursp); 
         $sql2="SELECT name FROM logins WHERE sport='$othersp' AND school='".addslashes($school)."'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(trim($row2[name])==trim($name))
         {
            echo "<div class='normalwhite' style='padding:5px;font-size:10pt;'><b><u>You are also listed as the $othersp Coach for $school.</b></u> You will be given credit as both the $cursp AND $othersp Coach.</div><br>";
         }
      }
   }
   echo "<br><input type=submit name=\"continue\" value=\"Continue to Online ".GetActivityName($sport)." Rules Meeting\">";
   echo "</td></tr>";     
   echo "</table></form></td></tr>";
   echo "</table></div>";
   echo $end_html;
   exit();
}

?>
