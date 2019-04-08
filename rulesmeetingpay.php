<?php
require '../calculate/functions.php';
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   //header("Location:index.php?error=1");
echo "Session $session is not valid";
   exit();
}

$coachid=GetUserID($session);
$school=GetSchool($session);
$sportname=GetActivityName($sport);
$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);   
$pay=split("-",$row[paydate]);
$end=split("-",$row[enddate]);

if($verify && trim($signature)!='')
{
   $now=time(); $invoiceid=$session."-".$sport; 
   $sql2="SELECT * FROM rulesmeetingattendance WHERE invoiceid='$invoiceid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO rulesmeetingattendance (datepaid,coachid,invoiceid) VALUES ('$now','$coachid','$invoiceid')";
      $result=mysql_query($sql);
   }
   $sql="UPDATE ".$sport."rulesmeetings SET datepaid='".$now."',signature='".addslashes($signature)."' WHERE coachid='$coachid'";
   $result=mysql_query($sql);
   $sql2="UPDATE logins SET rulesmeeting='x' WHERE id='$coachid'";
   $result2=mysql_query($sql2);
   //IF SPEECH or PLAY or BOYS&GIRLS SPORT AND THIS PERSON IS THE COACH OF BOTH - GIVE CREDIT IN BOTH PLACES
   $cursp=GetActivity($session);
   if($sport=='sppp')
   {
      $name=GetUserName($session);
      if($cursp=="Speech") $othersp="Play Production";
      else $othersp="Speech";
      $sql2="SELECT * FROM logins WHERE sport='$othersp' AND school='".addslashes($school)."'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(trim($row2[name])==trim($name))
      {
         $sql2="UPDATE logins SET rulesmeeting='x' WHERE id='$row2[id]'";
         $result2=mysql_query($sql2);
         $duocoach="Speech and Play Production Director";
      }
      else $duocoach="Director";
   }
   else if(preg_match("/Boys/",$cursp) || preg_match("/Girls/",$cursp))
   {
      $name=GetUserName($session);
      if(preg_match("/Girls/",$cursp)) { $othersp=preg_replace("/Girls/","Boys",$cursp); $duocoach="Boys and ".$cursp." Coach"; }
      else { $othersp=preg_replace("/Boys/","Girls",$cursp); $duocoach="Girls and ".$cursp." Coach"; }
      $sql2="SELECT * FROM logins WHERE sport='$othersp' AND school='".addslashes($school)."'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(trim($row2[name])==trim($name))
      {
         $sql2="UPDATE logins SET rulesmeeting='x' WHERE id='$row2[id]'";
         $result2=mysql_query($sql2);
      }
      else $duocoach="$cursp Coach";
   }
   else if($sport=='sp' || $sport=='pp')
      $duocoach="Director";
   else
      $duocoach="Coach";
 
   //IF ALSO AN OFFICIAL, UPDATE OFFICIALS DATABASE
   $sql2="SELECT * FROM ".$sport."rulesmeetings WHERE coachid='$coachid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $offerror=""; $offid=0;
   if($row2[offid]>0)	//IS ALSO AN OFFICIAL
   {
      $offid=$row2[offid];
      if($sport=='sp' || $sport=='pp')
      {
         $sql2="UPDATE $db_name2.judges SET ".$sport."meeting='x' WHERE id='$offid'";
	 $result2=mysql_query($sql2);
      }
      else
      {
         //get regyr from end of $db_name
         $regyr=date("Y");
  	 if(date("m")<6) $regyr--;
         $regyr1=$regyr+1;
         $regyr="$regyr-$regyr1";
         $table=$sport."off_hist";
         //update __off_hist table
         $sql2="SELECT * FROM $db_name2.$table WHERE offid='$offid' AND regyr='$regyr'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)==0) //INSERT
         {
            $offerror="You have not registered as an OFFICIAL for the $regyr School Year yet.  Please do so in order to be marked as having attended a ".GetActivityName($sport)." Rules Meeting this year.  Once you have registered, please contact the NSAA and ask for credit for the online rules meeting.";
         }
         else    //UPDATE
         {
            $sql3="UPDATE $db_name2.$table SET rm='x' WHERE offid='$offid' AND regyr='$regyr'";
            $result3=mysql_query($sql3);
         }
      }
   }
   
   echo $init_html;
   echo GetHeader($session);
   echo "<br><table cellspacing=3 cellpadding=3><caption><b><i>Your attendance at the NSAA Online $sportname Rules Meeting has been verified";
   if($offerror!='')
      echo " as a $duocoach but NOT an OFFICIAL/JUDGE*";
   else if($offid>0)	//CREDIT AS AN OFFICIAL TOO
   {
      if($sport=='sp' || $sport=='pp') echo " as a $duocoach and a JUDGE";
      else echo " as a $duocoach and an OFFICIAL";
   }
   else	//JUST COACH
      echo " as a $duocoach";
   echo ". You may print this page for your records.</b></i>";
   if($offerror!='')
      echo "<br><div class=error>*$offerror</div>";
   echo "</caption>";
   $sql="SELECT * FROM logins WHERE id='$coachid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td><b>Name:</b></td><td>$row[name]</td></tr>";
   echo "<tr align=left><td><b>School:</b></td><td>$row[school]</td></tr>";
   echo "<tr align=left><td><b>Sport:</b></td><td>$sportname</td></tr>";
   echo "<tr align=left><td><b>Date Completed:</b></td><td>".date("F j, Y",$now)." at ".date("g:ia T",$now)."</td></tr>";
   echo "<tr align=left><td><b>Completion Code:</b></td><td>$now</td></tr>";
   echo "</table>";
   echo "<br><br><a href=\"welcome.php?session=$session\">Home<a>";
   echo $end_html;
   exit();
}

//see if this official is in the database yet for this rules meeting:
$now=time();
$sql2="SELECT * FROM ".$sport."rulesmeetings WHERE coachid='$coachid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$latesec=mktime(23,59,59,$late[1],$late[2],$late[0]);
$paysec=mktime(23,59,59,$pay[1],$pay[2],$pay[0]);
$endsec=mktime(23,59,59,$end[1],$end[2],$end[0]);
if($row2[datecompleted]==0)
   $totaltime=$now-$row2[dateinitiated];
else
   $totaltime=$row2[datecompleted]-$row2[dateinitiated];
if($row2[datepaid]>0)	//Already completed this rules meeting
{
   echo $init_html;
   echo GetHeader($session);
   if($sport=='te')
      echo "<br><br><div class=alert style=\"width:400px;\"><b>You have already completed the $year NSAA Online $sportname Rules Meeting.</b><br><br>";
   else
      echo "<br><br><div class=alert style=\"width:400px;\"><b>You have already completed and paid for the $year NSAA Online $sportname Rules Meeting.</b><br><br>";
   echo "If you think this is an error, please contact the NSAA.</div><br><br>";
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else if($totaltime<$row[totaltime] || $row2[dateinitiated]==0 || mysql_num_rows($result2)==0)	//DID NOT START VIDEO
{
   echo $init_html;
   echo GetHeader($session);

   if($row2[dateinitiated]>0 && mysql_num_rows($result2)>0)	//didn't watch the whole thing
   {
      echo "<br><br><div class='normalwhite' style='padding:10px;width:450px;'><div class=error style='width:425px;'>ERROR:</div><br>";
      echo "You <b>began watching</b> this rules meeting on ".date("m/d/y",$row2[dateinitiated])." at ".date("g:ia T",$row2[dateinitiated]).".<br><br>";
      echo "You <b>stopped watching</b> this rules meeting on ".date("m/d/y",$row2[dateinitiated]+$totaltime)." at ".date("g:ia T",$now).".<br><br>";
      echo "The rules meeting video is approximately ".ConvertSecToMin($row[totaltime])." long. Therefore, it appears that you have not watched the ENTIRE rules meeting video.<br><br>";
      echo "The following are possible reasons for this occurring:<ul>";
      echo "<li><b>There may be a limit on the size of file you are allowed to download and view.</b>  If you are at home, you need to contact your Internet Service Provider and ask if there is a size limit on downloads.  If you are at a school, office, or other location, you need to contact their network administrator.  This file is around 30-40 MegaBytes in size.</li>";
      echo "<li><b>Your internet connection may have temporarily cut out.</b> If your internet connection disconnected, even for a moment, during the viewing of this online video, that may be why you were not able to watch the entire video. It will help to <b>CLOSE ALL OTHER PROGRAMS AND BROWSER WINDOWS</b> during the viewing of the rules meeting, so that your computer and internet can run smoothly.</li>";   
      echo "</ul>We apologize for the inconvenience, but you must watch the ENTIRE RULES MEETING VIDEO in order to receive credit for attendance.";
      echo "<br><br><a href=\"rulesmeetingintro.php?session=$session&sport=$sport\">Start the $sportname Rules Meeting Video over</a>";
      echo "</div>";
      $sql3="UPDATE ".$sport."rulesmeetings SET timewatched='$totaltime' WHERE coachid='$coachid'";
      $result3=mysql_query($sql3); 
   }
   else if(mysql_num_rows($result2)==0)	//can't find this coach 
   {
      echo "<br><br><div class='error'>ERROR: Coach #$coachid could not be found in the $sportname Rules Meeting table in the database.</div>";
   }
   else	//video not started at all
   {
      echo "<br><br><div class='error'>ERROR: There is no record of Coach #$coachid watching the $sportname rules meeting video.</div>";
   }

   echo $end_html;
   exit();
}
else if($row2[datecompleted]==0)	//UPDATE DATE COMPLETED VIDEO
{
   $sql2="UPDATE ".$sport."rulesmeetings SET datecompleted='$now' WHERE coachid='$coachid'";
   $result2=mysql_query($sql2);
   if($school=="Tests School") $fee=0;
   else if($now>$endsec)	//TOO LATE TO ATTEND MEETING
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br><div class=alert style=\"width:400px;\"><b>This Meeting has Expired.</b><br><br>This meeting was available ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("F j, Y",$endsec)." at midnight.<br><br>";
      echo "You may no longer complete this online rules meeting.</div>";
      echo $end_html;
      exit();
   }
   else if($now>$latesec) $fee=$row[latefee];
   else if($now>$paysec) $fee=$row[fee];
   else $fee=0;
}
else	//check if late fee applies
{
   $completed=$row2[datecompleted];
   if($school=="Tests School") $fee=0;
   else if($now>$endsec)     //TOO LATE TO ATTEND MEETING   
   {      
      echo $init_html;      
      echo GetHeader($session);      
      echo "<br><br><div class=alert style=\"width:400px;\"><b>This Meeting has Expired.</b><br><br>This meeting was available ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("F j, Y",$endsec)." at midnight.<br><br>";      
      echo "You may no longer complete this online rules meeting.</div>";      
      echo $end_html;      
      exit();  
   }
   else if($completed>$latesec) $fee=$row[latefee];
   else if($completed>$paysec) $fee=$row[fee];
   else $fee=0;
} 
$invoiceid=$session."-".$sport;
$sql2="UPDATE ".$sport."rulesmeetings SET invoiceid='$invoiceid' WHERE coachid='$coachid'";
$result2=mysql_query($sql2);

echo $init_html;
echo GetHeader($session);

if($fee>0 && $sport!='ad' && $sport!='te')	//PAY WITH CREDIT CARD
{
?>
<script language="javascript">
function ErrorCheck()
{
   if(document.getElementById('ssl_exp_date').value.match(/\D/) || document.getElementById('ssl_exp_date').value.length!=4)
      return false;
   else
      return true;
}
</script>
<?php
echo "<br>";
echo "<table cellspacing=2 cellpadding=2 class=nine width=100%>";
   /********CREDIT CARD FORM********/
   echo "<tr align=center><td colspan=2><table><tr align=left><td>";
   echo "<i>Thank you for viewing the <b>$year NSAA Online $sportname Rules Meeting</b>!<br><br>Please enter your credit card information below in order to confirm your completion of this meeting:</i><br>";
   echo "<form method=post action=\"$VirtualMerchantAction\">";
   //echo "<input type=hidden name=\"ssl_test_code\" value=\"TRUE\">";
   echo "<input type=hidden name=\"ssl_merchant_id\" value=\"$VirtualMerchantID\">";
   echo "<input type=hidden name=\"ssl_user_id\" value=\"$VirtualMerchantUserID\">";
   echo "<input type=hidden name=\"ssl_pin\" value=\"$VirtualMerchantPIN\">";
   echo "<input type=hidden name=\"ssl_amount\" value=\"$fee\">";
   echo "<input type=hidden name=\"ssl_salestax\" value=\"0.00\">";
   echo "<input type=hidden name=\"ssl_show_form\" value=\"false\">";
   echo "<input type=hidden name=\"ssl_invoice_number\" value=\"$invoiceid\">";
   echo "<input type=hidden name=\"ssl_customer_code\" value=\"$coachid\">";
   echo "<input type=hidden name=\"ssl_transaction_type\" value=\"ccsale\">";
   echo "<div class=\"normal\" style=\"width:500px;\">";
   echo "<table class=nine cellspacing=2 cellpadding=2>";
   echo "<tr align=left><td><b>Total Fee:</b></td><td>$".number_format($fee,2,'.','')."</td></tr>";
   echo "<tr align=left valign=top><td><b>Cardholder Name:</b></td>";
   echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input type=text name=\"ssl_first_name\"></td>";
   echo "<td><input type=text name=\"ssl_last_name\"></td></tr>";
   echo "<tr align=left><td>&nbsp;[First]</td><td>&nbsp;[Last]</td></tr></table></td>";
   echo "<tr align=left valign=top><td><b>Billing Address:</b></td><td>";
   echo "<table><tr align=left><td>Street:</td><td><input type=text name=\"ssl_avs_address\" size=30></td></tr>";
   echo "<tr align=left><td>City, State:</td><td><input type=text name=\"ssl_city\" size=20>,&nbsp;<input type=text name=\"ssl_state\" size=3 maxlength=2>&nbsp;&nbsp;Zip:&nbsp;<input type=text name=\"ssl_avs_zip\" size=5></td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=center><td colspan=2><div id=errordiv class=searchresults style=\"left:35%;width:300px;visibility:hidden;display:none\"><table width=100%><tr align=center><td><div class=error>Please correct the following fields in your form:</div></td></tr><tr align=left><td>The <b>Expiration Date</b> must be of the format \"MMYY\".  For example, <b>January 2009</b> would be entered as <b>\"0109\"</b>.</td></tr><tr align=center><td><img src='/okbutton.png' onclick=\"document.getElementById('errordiv').style.visibility='hidden';document.getElementById('errordiv').style.display='none';\"></td></tr></table></div></td></tr>";
   echo "<tr align=left><td><b>Type of Card:</b></td><td><select name=\"cardtype\"><option>VISA<option>Mastercard<option>Discover</select></td></tr>";
   echo "<tr align=left><td><b>Credit Card Number:</b></td><td><input type=password name=\"ssl_card_number\" size=20></td></tr>";
   echo "<tr align=left><td><b>Expiration Date (MMYY):</b></td><td><input type=text name=\"ssl_exp_date\" id=\"ssl_exp_date\" size=4> (Example: for January 2009, enter \"0109\")</td></tr>";
   echo "<tr align=left><th align=left class=smaller>Card Security Code:</th><td><input type=text name=\"ssl_cvv2cvc2\" size=3>&nbsp;(3-digit number on back of card in signature strip)</td></tr>";
   echo "<input type=hidden name=\"ssl_cvv2cvc2_indicator\" value=\"1\">";
   echo "<input type=hidden name=\"ssl_result_format\" value=\"HTML\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/rulesmeetingdecline.php\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/rulesmeetingapproval.php\">";
   echo "<tr align=center><td colspan=2><b>PLEASE ONLY CLICK THIS BUTTON ONCE!!<br><input type=button name=go onClick=\"if(ErrorCheck()) { submit(); } else { errordiv.style.display='block'; errordiv.style.visibility='visible'; }\" value=\"Continue\"><br>PLEASE ONLY CLICK THIS BUTTON ONCE!!</b></td></tr></table></form>";
   /********END CREDIT CARD FORM********/
   echo "</td></tr>";
   echo "</table></div>";
?>
<script type='text/javascript' src='https://sealserver.trustwave.com/seal.js?style=normal'></script>
<?php

echo "</td></tr>";
echo "</table>";
}	//END IF MUST PAY
else	//NO CHARGE; ELECTRONIC SIGNATURE
{
   echo "<form method=post action=\"rulesmeetingpay.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"sport\" value=\"$sport\">";
   echo "<br><table class=nine cellspacing=3 cellpadding=3><caption><b>Thank you for attending the NSAA Online $sportname Rules Meeting!</b></caption>";
   echo "<tr align=left><td>Please enter your name in the box below and click \"Verify Attendance\" in order to receive credit for attending this rules meeting.</td></tr>";
   echo "<tr align=center><td><b>Type your name:</b> <input type=text size=30 name=\"signature\"></td></tr>";
   echo "<tr align=center><td><input type=submit name=verify value=\"Verify Attendance\"></td></tr>";
   echo "</table>";
   echo "</form>";
}

echo $end_html;
?>
