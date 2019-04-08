<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidAssessor($session))
{
   header("Location:../wrassessor.php?error=3");
   exit();
}

$assessorid=GetWRAUserID($session);

echo $init_html;
echo GetAssessorHeader($session);

//CHECK THAT THEY WATCHED THE WHOLE VIDEO AND THAT THEY HAVEN'T ALREADY PAID
$now=time();
$sql2="SELECT * FROM wrassessors WHERE session='$session'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($row2[datecompleted]==0)
   $totaltime=$now-$row2[dateinitiated];
else
   $totaltime=$row2[datecompleted]-$row2[dateinitiated];
$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='wrassessor'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row2[datepaid]>0)   //Already completed and paid
{
   echo "<br><br><div class=alert style=\"width:400px;\"><b>You have already completed and paid for this year's NSAA Wrestling Assessor Registration.</b><br><br>";
   echo "If you think this is an error, please contact the NSAA.</div><br><br>";
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else if($totaltime<$row[totaltime] || $row2[dateinitiated]==0 || mysql_num_rows($result2)==0)   //DID NOT START VIDEO
{
   if($row2[dateinitiated]>0 && mysql_num_rows($result2)>0)     //didn't watch the whole thing
   {
      echo "<br><br><div class='normalwhite' style='padding:10px;width:450px;'><div class=error style='width:425px;'>ERROR:</div><br>";
      echo "You <b>began watching</b> this presentation on ".date("m/d/y",$row2[dateinitiated])." at ".date("g:ia T",$row2[dateinitiated]).".<br><br>";
      echo "You <b>stopped watching</b> this presentation on ".date("m/d/y",$row2[dateinitiated]+$totaltime)." at ".date("g:ia T",$now).".<br><br>";
      $extrasec=($row[totaltime]%60);
      $min=floor($row[totaltime]/60);
      echo "The presentation video is approximately $min minutes, $extrasec seconds long. Therefore, it appears that you have not watched the ENTIRE video.<br><br>";
      echo "The following are possible reasons for this occurring:<ul>";
      echo "<li><b>There may be a limit on the size of file you are allowed to download and view.</b>  If you are at home, you need to contact your Internet Service Provider and ask if there is a size limit on downloads.  If you are at a school, office, or other location, you need to contact their network administrator.  This file is around 30-40 MegaBytes in size.</li>";
      echo "<li><b>Your internet connection may have temporarily cut out.</b> If your internet connection disconnected, even for a moment, during the viewing of this online video, that may be why you were not able to watch the entire video. It will help to <b>CLOSE ALL OTHER PROGRAMS AND BROWSER WINDOWS</b> during the viewing of the presentation, so that your computer and internet can run smoothly.</li>";
      echo "</ul>We apologize for the inconvenience, but you must watch the ENTIRE PRESENTATION in order to receive credit for attendance.";
      echo "<br><br><a href=\"powerpoint.php?session=$session&sport=$sport\">Start the $sportname Presentation Video over</a>";
      echo "</div>";
   }
   else if(mysql_num_rows($result2)==0) //can't find this official
      echo "<br><br><div class='error'>ERROR: Assessor #$assessorid could not be found in the database.</div>";
   else //video not started at all
      echo "<br><br><div class='error'>ERROR: There is no record of Assessor #$assessorid watching the video.</div>";
   echo $end_html;
   exit();
}

//GET FEE AMOUNT
$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='wrassessor'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$fee=number_format($row[fee],2,'.','');
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
   <br><br>
   <table width="700px" class=nine>
   <caption><b>STEP 3 - COMPLETE PAYMENT ($<?php echo $fee; ?>)<br><br>Thank you for completing the presentation for your NSAA & Track Wrestling Assessor Annual Registration</b></caption>
<?php
   $appid=time();
   $sql="INSERT INTO wrassessorsapp (appid,assessorid) VALUES ('$appid','$assessorid')";
   $result=mysql_query($sql);
//echo $sql.mysql_error();

   /********CREDIT CARD FORM********/
   echo "<tr align=center><td colspan=2><br>The annual registration fee is <b><u>$fee</b></u>. Please enter your credit card information:</td></tr>";
   echo "<tr align=center><td colspan=2><br>";
   echo "<form method=post action=\"$VirtualMerchantAction\">";
   //echo "<input type=hidden name=\"ssl_test_code\" value=\"TRUE\">";
   echo "<input type=hidden name=\"ssl_merchant_id\" value=\"$VirtualMerchantID\">";
   echo "<input type=hidden name=\"ssl_user_id\" value=\"$VirtualMerchantUserID\">";
   echo "<input type=hidden name=\"ssl_pin\" value=\"$VirtualMerchantPIN\">";
   echo "<input type=hidden name=\"ssl_amount\" value=\"$fee\">";
   echo "<input type=hidden name=\"ssl_salestax\" value=\"0.00\">";
   echo "<input type=hidden name=\"ssl_show_form\" value=\"false\">";
   echo "<input type=hidden name=\"ssl_invoice_number\" value=\"$appid\">";
   echo "<input type=hidden name=\"ssl_customer_code\" value=\"$assessorid\">";
   echo "<input type=hidden name=\"ssl_transaction_type\" value=\"ccsale\">";
   echo "<div class=\"normal\" style=\"width:500px;\">";
   echo "<table class=nine cellspacing=2 cellpadding=2>";
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
   echo "<input type=hidden name=\"ssl_receipt_decl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/wrassessor/decline.php\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/wrassessor/approval.php\">";
   echo "<tr align=center><td colspan=2><b>PLEASE ONLY CLICK THIS BUTTON ONCE!!<br><input type=button name=go onClick=\"if(ErrorCheck()) { submit(); } else { errordiv.style.display='block'; errordiv.style.visibility='visible'; }\" value=\"Continue\"><br>PLEASE ONLY CLICK THIS BUTTON ONCE!!</b></td></tr></table></form>";
   /********END CREDIT CARD FORM********/
   echo "</td></tr>";
   echo "</table>";
?>
<script type='text/javascript' src='https://sealserver.trustwave.com/seal.js?style=normal'></script>
<?php
echo $end_html;
?>
