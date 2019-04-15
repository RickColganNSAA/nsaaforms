<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   if($sport=='sp' || $sport=='pp')
      header("Location:jindex.php?error=1");
   else
      header("Location:index.php?error=1");
   exit();
}
if($sport=='sp' || $sport=='pp')
{
   $offid=GetJudgeID($session);
   $newoff=IsNewJudge($offid);
}
else
{
   $offid=GetOffID($session);
   $newoff=IsNewOfficial($offid);
}

$sportname=GetSportName($sport);
$sql="SELECT * FROM rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
//EXCEPTIONS FOR OFFICIALS TO GET CREDIT FOR FREE:
if($newoff) 
{
   $row[paydate]=$row[enddate];
   $row[latedate]=$row[enddate];
}
$late=split("-",$row[latedate]);   
$pay=split("-",$row[paydate]);
$end=split("-",$row[enddate]);

if($verify && trim($signature)!='')
{
   $now=time(); $invoiceid=$session."-".$sport;
   $sql="UPDATE ".$sport."rulesmeetings SET datepaid='".$now."',signature='".addslashes($signature)."' WHERE offid='$offid'";
   $result=mysql_query($sql);
   $sql2="SELECT * FROM rulesmeetingattendance WHERE invoiceid='$invoiceid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO rulesmeetingattendance (datepaid,offid,invoiceid) VALUES ('$now','$offid','$invoiceid')";
      $result=mysql_query($sql);
   }
   //UPDATE/INSERT INTO _off_hist table a 'x' for rules meeting field (or judges table for SPPP)
   if($sport=='sp' || $sport=='pp')
   {
      $sql="SELECT * FROM judges WHERE id='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo "ERROR: We cannot find you in our database and therefore cannot mark you as having attended a rules meeting.  Please contact the NSAA and reference Invoice #$invoiceid and Judge ID# $offid.";
         exit();
      }
      else
      {
         $sql="UPDATE judges SET ".$sport."meeting='x' WHERE id='$offid'";
         $result=mysql_query($sql);
      }

      //IF ALSO COACH, UPDATE %rulesmeeting TABLE
      $sql2="SELECT * FROM ".$sport."rulesmeetings WHERE offid='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $school=trim($row2[school]); 
      if($school!='') //ALSO A COACH
      {
         $sql="UPDATE $db_name.logins SET rulesmeeting='x' WHERE school='".addslashes($school)."' AND sport='".GetSportName($sport)."'";
         $result=mysql_query($sql);
      }
   }
   else
   {
      $year1=date("Y"); $mo=date("m");
      $regyr=GetSchoolYear($year1,$mo);
      $table=$sport."off_hist";
      //update __off_hist table
      $sql2="SELECT * FROM $table WHERE offid='$offid' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0) //INSERT
      {
         echo "You have not registered for the $regyr School Year yet.  Please do so in order to be marked as having attended a ".GetSportName($sport)." Rules Meeting this year.  Once you have registered, please contact the NSAA and ask for credit for the online rules meeting.";
         exit();
      }
      else    //UPDATE
      {
         $sql3="UPDATE $table SET rm='x' WHERE offid='$offid' AND regyr='$regyr'";
         $result3=mysql_query($sql3);

         //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
         UpdateRank($offid,$sport);

         //CHECK IF ALSO A COACH
         $sql2="SELECT * FROM ".$sport."rulesmeetings WHERE offid='$offid'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
	 $school1=trim($row2[school1]); $school2=trim($row2[school2]);
         if($sport=='bb' || $sport=='so' || $sport=='sw' || $sport=='tr')        //BOYS/GIRLS COACH TOO?
         {
            if($school1!='')	//ALSO A BOYS COACH
    	    {
	       $sql="UPDATE $db_name.logins SET rulesmeeting='x' WHERE school='".addslashes($school1)."' AND sport='Boys ".GetSportName($sport);
		if($sport=='tr') $sql.=" & Field";
	       $sql.="'";
	       $result=mysql_query($sql);
       	    }
            if($school2!='')    //ALSO A GIRLS COACH
            {
               $sql="UPDATE $db_name.logins SET rulesmeeting='x' WHERE school='".addslashes($school1)."' AND sport='Girls ".GetSportName($sport);
                if($sport=='tr') $sql.=" & Field";
               $sql.="'";
               $result=mysql_query($sql);
            } 
         }
         else if($school1!='')    //ALSO A COACH?
         {
            $sql="UPDATE $db_name.logins SET rulesmeeting='x' WHERE school='".addslashes($school1)."' AND sport='".GetSportName($sport)."'";
            $result=mysql_query($sql);
         } 
      }
   }//end if not SPPP
   //SHOW CONFIRMATION
   echo $init_html;
   if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
   else echo GetHeader($session);
   echo "<br><table cellspacing=3 cellpadding=3><caption><b><i>Your attendance at the NSAA Online $sportname Rules Meeting has been verified.  You may print this page for your records.</b></i>";
   if($sport=='sp' || $sport=='pp') $name=GetJudgeName($offid);
   else $name=GetOffName($offid);
   echo "<tr align=left><td><b>Name:</b></td><td>$name</td></tr>";
   if($sport=='sp' || $sport=='pp')
   {
      echo "<tr align=left valign=top><td><b>Received credit as:</b></td><td>NSAA Judge<br>";
      if($school!='')
	 echo "$school ".GetSportName($sport)." Head Coach<br>";
      echo "</td></tr>";
   }
   else
      echo "<tr align=left><td><b>Sport:</b></td><td>".GetSportName($sport)."</td></tr>";
   echo "<tr align=left><td><b>Date Completed:</b></td><td>".date("F j, Y",$now)." at ".date("g:ia T",$now)."</td></tr>";
   echo "<tr align=left><td><b>Completion Code:</b></td><td>$now</td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}

//see if this official is in the database yet for this rules meeting:
$now=time();
$sql2="SELECT * FROM ".$sport."rulesmeetings WHERE offid='$offid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$latesec=mktime(23,59,59,$late[1],$late[2],$late[0]);
$endsec=mktime(23,59,59,$end[1],$end[2],$end[0]);
$paysec=mktime(23,59,59,$pay[1],$pay[2],$pay[0]);
if($row2[datecompleted]==0)
   $totaltime=$now-$row2[dateinitiated];
else
   $totaltime=$row2[datecompleted]-$row2[dateinitiated];
if($row2[datepaid]>0)	//Already completed this rules meeting
{
   echo $init_html;
   if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
   else echo GetHeader($session);
   echo "<br><br><div class=alert style=\"width:400px;\"><b>You have already completed and paid for the $year NSAA Online $sportname Rules Meeting.</b><br><br>";
   echo "If you think this is an error, please contact the NSAA.</div><br><br>";
   if($sport=='sp' || $sport=='pp') echo "<a href=\"jwelcome.php?session=$session\">Home</a>";
   else echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else if($totaltime<$row[totaltime] || $row2[dateinitiated]==0 || mysql_num_rows($result2)==0)	//DID NOT START VIDEO
{
   echo $init_html;
   if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
   else echo GetHeader($session);
   if($row2[dateinitiated]>0 && mysql_num_rows($result2)>0)     //didn't watch the whole thing
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
      $sql3="UPDATE ".$sport."rulesmeetings SET timewatched='$totaltime' WHERE offid='$offid'";
      $result3=mysql_query($sql3);
   }
   else if(mysql_num_rows($result2)==0) //can't find this official
      echo "<br><br><div class='error'>ERROR: Official #$offid could not be found in the $sportname Rules Meeting table in the database.</div>";
   else //video not started at all
      echo "<br><br><div class='error'>ERROR: There is no record of Official #$offid watching the $sportname rules meeting video.</div>";
   echo $end_html;
   exit();
}
else if($row2[datecompleted]==0)	//UPDATE DATE COMPLETED VIDEO
{
   $sql2="UPDATE ".$sport."rulesmeetings SET datecompleted='$now' WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if($offid!='3427' && $now>$endsec)	//TOO LATE TO ATTEND MEETING
   {
      echo $init_html;
      if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
      else echo GetHeader($session);
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
   if($now>$endsec && $offid!='3427')     //TOO LATE TO ATTEND MEETING   
   {      
      echo $init_html;      
      if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
      else echo GetHeader($session);      
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
$sql2="UPDATE ".$sport."rulesmeetings SET invoiceid='$invoiceid' WHERE offid='$offid'";
$result2=mysql_query($sql2);

echo $init_html;
if($sport=='sp' || $sport=='pp') echo GetHeaderJ($session);
else echo GetHeader($session);

if($fee>0)	//MUST PAY
{
echo "<br>";
echo "<table cellspacing=2 cellpadding=2 class=nine width=100%>";
   /********CREDIT CARD FORM********/
   echo "<tr align=center><td colspan=2><table><tr align=left><td>";
   echo "<i>Thank you for viewing the <b>$year NSAA Online $sportname Rules Meeting</b>!<br><br>Please enter your credit card information below in order to confirm your completion of this meeting:</i><br>";
   echo "<form method=post action=\"$VirtualMerchantAction\">";
   if($offid=='598') echo "<input type=hidden name=\"ssl_test_code\" value=\"TRUE\">";
   echo "<input type=hidden name=\"ssl_merchant_id\" value=\"$VirtualMerchantID\">";
   echo "<input type=hidden name=\"ssl_user_id\" value=\"$VirtualMerchantUserID\">";
   echo "<input type=hidden name=\"ssl_pin\" value=\"$VirtualMerchantPIN\">";
   echo "<input type=hidden name=\"ssl_amount\" value=\"$fee\">";
   echo "<input type=hidden name=\"ssl_salestax\" value=\"0.00\">";
   echo "<input type=hidden name=\"ssl_show_form\" value=\"false\">";
   echo "<input type=hidden name=\"ssl_invoice_number\" value=\"$invoiceid\">";
   echo "<input type=hidden name=\"ssl_customer_code\" value=\"$offid\">";
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
   echo "<tr align=left><td><b>Type of Card:</b></td><td><select name=\"cardtype\"><option>VISA<option>Mastercard<option>Discover</select></td></tr>";
   echo "<tr align=left><td><b>Credit Card Number:</b></td><td><input type=password name=\"ssl_card_number\" size=20></td></tr>";
   echo "<tr align=left><td><b>Expiration Date (MMYY):</b></td><td><input type=text name=\"ssl_exp_date\" size=4></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Card Security Code:</th><td><input type=text name=\"ssl_cvv2cvc2\" size=3>&nbsp;(3-digit number on back of card in signature strip)</td></tr>";
   echo "<input type=hidden name=\"ssl_cvv2cvc2_indicator\" value=\"1\">";
   echo "<input type=hidden name=\"ssl_result_format\" value=\"HTML\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/officials/rulesmeetingdecline.php\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/officials/rulesmeetingapproval.php\">";
   echo "<tr align=center><td colspan=2><b>PLEASE ONLY CLICK THIS BUTTON ONCE!!<br><input type=submit value=\"Continue\"><br>PLEASE ONLY CLICK THIS BUTTON ONCE!!</b></td></tr></table></form>";
   /********END CREDIT CARD FORM********/
   echo "</td></tr>";
   echo "</table></div>";
?>
<script type='text/javascript' src='https://sealserver.trustwave.com/seal.js?style=normal'></script>
<?php

echo "</td></tr>";
echo "</table>";
}
else	//NO CHARGE
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
