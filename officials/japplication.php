<?php
require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

  if($_SERVER['HTTPS']!="on")
  {
     //$redirect= "https://nsaahome.org".$_SERVER['REQUEST_URI'];
     //header("Location:$redirect");
  }

?>
<script language="javascript">
<?php echo $autotab; ?>
function Calculate()
{
   var total=0;
   if(document.getElementById('play').checked && document.getElementById('speech').checked)
      total=parseFloat(document.getElementById('bothfee').value);
   else if(document.getElementById('play').checked)
      total=25;
   else if(document.getElementById('speech').checked)
      total=25;
   document.getElementById('total').value=total.toFixed(2);
}
function ErrorCheck()
{
   if(document.getElementById('ssl_exp_date').value.match(/\D/) || document.getElementById('ssl_exp_date').value.length!=4)
      return false;
   else
      return true;
}
</script>
<?php
if($submit=="Log In") //logging in from this application
{
   $sql="SELECT t1.* FROM logins_j AS t1, judges AS t2 WHERE t1.offid=t2.id AND t1.passcode='$passcode'";
   //(MUST include judges table in this query in case there are outdated entries in logins_j table
   //with no match in judges table)
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0 || trim($passcode)=="")
   {
      $loginerror=1; $login=1;
   } 
   else
   {
      $loginerror=0;
      $offid=$row[offid];
	  $loginerror=0;
      $offid=$row[offid];
	  $session_id = time();
        $sql2 = "SELECT * FROM sessions WHERE session_id='$session_id'";
        $result2 = mysql_query($sql2);
        while ($row2 = mysql_fetch_array($result2)) {
            $session_id++;
            $sql2 = "SELECT * FROM sessions WHERE session_id='$session_id'";
            $result2 = mysql_query($sql2);
        }
        $sql = "INSERT INTO sessions (session_id, login_id) VALUES ('$session_id', '$row[0]')";
        $result = mysql_query($sql);
        header("Location:japplication.php?session=$session_id&offid=$offid");
   }
}
/*else if($session!="") //logged in from jlogin.php
{
   $sql="SELECT t1.offid FROM logins_j AS t1, sessions AS t2 WHERE t1.id=t2.login_id AND session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) //invalid session variable
      $session="";
   else	//logged in, but log them out until them complete this app
   {
      citgf_exec("/usr/local/bin/php logout.php $session > logout.html 2>&1 &");
      $loginerror=0;
      $offid=$row[offid];
   }
}*/
?>

<html>
<head>
   <title>NSAA | Judges Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<table width=100%><tr align=center><td>
<?php
//if user has finished entering information: check for errors, save to db, show confirmation page

if($submit=="Submit")
{
    if ($session=='')$session=$_GET['session'];
	if (empty($offid)){
		$offid= GetJudgeID($session);
	}
   //Error-Checking:
   $error=0;

   $hphone=$harea.$hpre.$hsuff;
   $wphone=$warea.$wpre.$wsuff;
   $cphone=$carea.$cpre.$csuff;
   $name=trim($first)." ".trim($last);
   $socsec=$socsec1.$socsec2.$socsec3;
   $email=trim($email);
 
   $errors="";
   
   //if(($speech!='x' && $play!='x') || trim($socsec)=="" || trim($name)=="" || trim($address)=="" || trim($city)=="" || trim($state)=="" || trim($zip)=="" || (trim($hphone)=="" && trim($wphone)=="" && trim($cphone)=="") || trim($email)=="" || !filter_var($email, FILTER_VALIDATE_EMAIL))
   if(trim($socsec)=="" || trim($name)=="" || trim($address)=="" || trim($city)=="" || trim($state)=="" || trim($zip)==""|| trim($reg_year)=="" || (trim($hphone)=="" && trim($wphone)=="" && trim($cphone)=="") || trim($email)=="" || !filter_var($email, FILTER_VALIDATE_EMAIL))
   {
      if(!filter_var($email, FILTER_VALIDATE_EMAIL))
         $errors.="<p>You did not enter a VALID email address.</p>";
      else
         $errors.="<p>You did not fill in one or more required fields.  Please do so and then hit \"Submit\" at the bottom of this page.</p>";
   }
   if($agree!='x') 
   {
      $errors.="<p>You MUST check the box at the bottom of the screen before you may continue.</p>";
   }
/*    if($app_play=='not_applied') 
   {
      $errors.="<p>You need to apply to Judge PLAY PRODUCTION.</p>";
   }
   if($app_speech=='not_applied') 
   {
      $errors.="<p>You need to apply to Judge SPEECH.</p>";
   } 
   if(($app_play=='not_applied' ) && ($app_speech=='not_applied' ))
   {
      $errors.="<p>You need to apply to Judge PLAY PRODUCTION or SPEECH.</p>"; 
   }*/
   //fix capital letters, etc:
      //name:
   $first=trim(Capitalize($first));
   $last=trim(Capitalize($last));
   $address=Capitalize($address);
   $city=Capitalize($city);
   $state=strtoupper($state);
   if(strlen($state)!=2)
   {
      $errors.="<p>You must enter your state abbreviation in the form of 2 capital letters, like \"NE\".</p>";
   }

   //check that soc sec is 9 digits, and phone nums are 10 digits:
   if(strlen($reg_year)<1)
   {
      $errors.="<p>You must enter years of registration.</p>";
   }
   if(strlen($socsec)!=9)
   {
      $errors.="<p>You must enter a <u>9-digit</u> social security number.</p>";
   }
   else if(($hphone!="" && strlen($hphone)!=10)||($wphone!="" && strlen($wphone)!=10)||($cphone!="" && strlen($cphone)!=10))
   {
      $errors.="<p>You must enter the full phone number, including area code, for any phone number you wish to provide.</p>";
   }

   //check that first AND last name were given
   if(trim($first)=="" || trim($last)=="")
   {
      $errors.="<p>You must enter your first name in the first text box and your last name in the second text box.</p>";
   }

   //Check that Yes or No was checked for conviction question - if Yes, require explanation
   if(!$conviction)
   {
      $errors.="<p>You must indicate whether or not you have ever been convicted of a misdemeanor or felony.</p>";
   }
   else if($conviction=="yes" && trim($convictionexplain)=="")
   {
      $errors.="<p>If you check \"Yes\" that you have been convicted of a misdemeanor or felony, you must include an explanation in the text box provided.</p>";
   }

   //Check that only numbers were entered for $yearsplay and $yearsspeech
   $yearsplay=trim($yearsplay); $yearsspeech=trim($yearsspeech);
   if(strlen($yearsplay)!=strlen(preg_replace("/[^0-9]/","",$yearsplay)))
   {
      $errors.="<p>You must enter a whole NUMBER for the number of years you've registered for Play Production (not something like \"35+\" or \"3 or 4\").</p>";
   }
   if(strlen($yearsspeech)!=strlen(preg_replace("/[^0-9]/","",$yearsspeech)))
   {
      $errors.="<p>You must enter a whole NUMBER for the number of years you've registered for Speech (not something like \"35+\" or \"3 or 4\").</p>";
   }
      
   //display entered info for confirmation:
   if($errors=="")
   {
?>
   <table style="width:500px" cellspacing=0 cellpadding=5 class='nine'>
   <caption><b>JUDGES APPLICATION FORM<br>Nebraska School Activities Association</b></caption>
   <tr align=center><td colspan=2><font size=2>Please make sure the information you entered is correct:</font>
   <br>
   <font style="color:red"><b>DO NOT use the "Back" button on your browser to go back and make changes.  Instead click the "Go Back and Make Changes" link below.</b></font>
   <br><br></td></tr>
<?php
   $string="";
   $string.="<tr align=left><th align=left>Social Security #:</th><td>$socsec</td></tr>";
   $string.="<tr align=left><th align=left>Full Name:</th><td>$name</td></tr>";
   $string.="<tr align=left><th align=left>Address:</th><td>$address</td></tr>";
   $string.="<tr align=left><th align=left>City:</th><td>$city</td></tr>";
   $string.="<tr align=left><th align=left>State:</th><td>$state</td></tr>";
   $string.="<tr align=left><th align=left>Zip:</th><td>$zip</td></tr>";
   $string.="<tr align=left><th align=left>Years Registered:</th><td>$reg_year</td></tr>";
   $string.="<tr align=left><th align=left>Home Phone:</th><td>($harea)$hpre-$hsuff</td></tr>";
   $string.="<tr align=left><th align=left>Work Phone:</th><td>($warea)$wpre-$wsuff</td></tr>";
   $string.="<tr align=left><th align=left>Cell Phone:</th><td>($carea)$cpre-$csuff</td></tr>";
   $string.="<tr align=left><th align=left>E-mail Address:</th><td>$email</td></tr>";
   $string.="<tr align=left><td colspan=2><b>Have you been convicted of a misdemeanor or felony as an adult over the age of 18?</b>&nbsp;&nbsp;";
   if($conviction=="yes") $string.="YES<blockquote>".preg_replace("/\r\n/","<br>",$convictionexplain)."</blockquote>";
   else $string.="NO";
   $string.="</td></tr>";
   //$string.="<tr align=left><th align=left>Qualified to judge Lincoln-Douglas Debate:</th><td>";
   //if($qualified=='x') $string.="Yes";
   //else $string.="No";

   $string.="<tr align=center><td colspan=2>";
   $string.="<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#000000 1px solid;\"><tr align=center><td>&nbsp;</td><td><b>Activity</b></td><td><b>First Year?</b></td><td><b>Years Registered</b></td><td><b>Fee</b></td><td><b>Due Date</b></td></tr>";
   $sduedate=GetDueDate('sp','reg');
   $pduedate=GetDueDate('pp','reg');
   $sdate=explode("-",$sduedate);
   $pdate=explode("-",$pduedate);
   $string.="<tr align=center><td><input type=checkbox id=\"play\" name=\"play\" value='x' disabled";
   if($play=='x') $string.=" checked";
   $string.="></td><td align=left>Play Production</td><td><input type=checkbox name=\"firstyrplay\" value=\"x\" disabled";
   if($firstyrplay=='x') $string.=" checked";
   $string.="></td><td>$yearsplay</td><td>$25.00</td><td>".date("F j",mktime(0,0,0,$pdate[1],$pdate[2],$pdate[0]))."</td></tr>";
   $string.="<tr align=center><td><input type=checkbox id=\"speech\" name=\"speech\" value='x' disabled";
   if($speech=='x') $string.=" checked";
   $string.="></td><td align=left>Speech</td><td><input type=checkbox name=\"firstyrspeech\" value=\"x\" disabled";
   if($firstyrspeech=='x') $string.=" checked";
   $string.="></td><td>$yearsspeech</td><td>$25.00</td><td>".date("F j",mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]))."</td></tr>";
   $string.="<tr align=center><th colspan=4 align=right>";
   $string.="Total Fee:</th><th align=left>$".$total."</th><td>&nbsp;</td></tr>";
   $string.="</table></td></tr>";
   $string.="</table></td></tr>";
   echo $string;

   //create invoice number and write to html file that will be e-mailed to NSAA
   if(!$appid || $appid=="" || $appid=='0')    
   {      
      $appid=time();      
      //MAKE SURE APPID IS UNIQUE!!!      
      $sql="SELECT * FROM judgesapp WHERE appid='$appid'";      
      $result=mysql_query($sql);      
      while($row=mysql_fetch_array($result))      
      {         
         $appid--;         
         $sql="SELECT * FROM judgesapp WHERE appid='$appid'";         
         $result=mysql_query($sql);      
      }   
   }
   $date=date("r T",$appid);
   /*
   $open=fopen(citgf_fopen("apps/japp$appid.html"),"w");
   fwrite($open,"<html><body><table><caption>Application # $appid:</caption><tr align=left><th align=left class=smaller>Date:</th><td>$date</td></tr>$string");
   fclose($open); 
 citgf_makepublic("apps/japp$appid.html");
   */ 
   $html="<html><body><table><caption>Application # $appid:</caption><tr align=left><th align=left class=smaller>Date:</th><td>$date</td></tr>$string";
   $html=addslashes($html);
   $sql="INSERT INTO judgesapp (appid,html) VALUES ('$appid','$html')";
   $result=mysql_query($sql);

   //enter judge into pending table
   $last=addslashes($last); $first=addslashes($first);
   $address=addslashes($address); $city=addslashes($city);
   $convictionexplain=addslashes(preg_replace("/\r\n/","<br>",$convictionexplain));
   $datereg=time();
   $sql="SELECT * FROM pendingjudges WHERE appid='$appid'";
   $result=mysql_query($sql);
   if(!$offid || $offid=='0')
      $offid="";
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO pendingjudges (offid,appid,datesub,socsec,last,first,address,city,state,zip,homeph,workph,cellph,email,firstyrplay,firstyrspeech,yearsplay,yearsspeech,conviction,convictionexplain,speech,play,reg_year) VALUES ('$offid','$appid','$datereg','$socsec','$last','$first','$address','$city','$state','$zip','$hphone','$wphone','$cphone','$email','$firstyrplay','$firstyrspeech','$yearsplay','$yearsspeech','$conviction','$convictionexplain','$speech','$play','$reg_year')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE pendingjudges SET offid='$offid',datesub='$datereg',socsec='$socsec',last='$last',first='$first',address='$address',city='$city',state='$state',zip='$zip',homeph='$hphone',workph='$wphone',cellph='$cphone',email='$email',reg_year='$reg_year',";
      if($play=='x') $sql2.="firstyrplay='$firstyrplay',yearsplay='$yearsplay',play='$play',";
      if($speech=='x') $sql2.="firstyrspeech='$firstyrspeech',yearsspeech='$yearsspeech',speech='$speech',";
      $sql2.="conviction='$conviction',convictionexplain='$convictionexplain'";
      $sql2.=" WHERE appid='$appid'";
   }
   $result2=mysql_query($sql2);

   if(!offid || $offid=="")
   {
      //check to see if this soc sec # is already in judges table
      $sql="SELECT * FROM judges WHERE socsec='$socsec'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)	//ssn found in database
      {
	 $row=mysql_fetch_array($result); 
     	 //check to see if passcode exists for this judge, if so re-direct to login
	 $sql2="SELECT passcode FROM logins_j WHERE offid='$row[id]'";
  	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[0]!="" && mysql_num_rows($result2)>0)	//passcode found, make them login
	 {
	    echo "<tr align=center><td colspan=2><br>";
	    echo "<b>Your social security number is already in our system.  Please <a class=small href=\"japplication.php?login=1\">Log In</a> and complete this application.<br></td></tr>";
	    echo "</table></body></html>";
	    exit();
	 }
	 else			//no passcode found
	 {
	    //check that first and last names match: if they DO, allow them to continue
	    if(trim(addslashes(strtolower($row[first])))==trim(strtolower($first)) && trim(addslashes(strtolower($row[last])))==trim(strtolower($last)))
	    {
	       echo "<tr align=center><td colspan=2><table>";
	       echo "<tr align=left><td><b>Your social security number is already in our system, but we have not assigned you a passcode yet.  Your entry in the database will be updated with the information you have just entered, shown above.  If you need to make changes to this information, <a class=small href=\"japplication.php?appid=$appid\">Click Here</a> to go back and do so BEFORE entering your credit card information below.  Once your credit card is approved, you will be issued a passcode.</b></td></tr>";
	       $offid=$row[id];
	       //update pending table:
	       $sql3="UPDATE pendingjudges SET offid='$offid' WHERE appid='$appid'";
	       $result3=mysql_query($sql3);
	    }
	    else	//names don't match: Make them go back and double-check information
	    {
	       echo "<tr align=center><td colspan=2>";
	       echo "<b>The social security number you have entered is already in our database, but it does not match the first and/or last names you have entered.  Please <a class=small href=\"japplication.php?appid=$appid\">Click Here</a> to go back and make sure you have entered all of your information correctly.  If you are sure your information is correct and you still receive this error, please contact the NSAA.</b></td></tr>";
	       echo "</table></body></html>";
	       exit();
	    }
	 }
      }
   }
   //If $offid is known (they are in database), check that they are not paying for a sport they've
   //already paid for:
	/*
   if($offid && $offid!="")
   {
      $sql="SELECT payment,datereg FROM judges WHERE payment!='' AND id='$offid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)       //already paid for this sport
      {
	 $row=mysql_fetch_array($result);
         $date=split("-",$row[datereg]);
         echo "<tr align=center><td colspan=2><br><hr><font style=\"color:red\"><b>You paid for your judges registration on $date[1]/$date[2]/$date[0].<br><br>If you think this is an error, please contact the NSAA Office at (402)489-0386.</td></tr>";
         echo "</table></body></html>";
         exit();
      }
   }
	*/
   $string.="<tr align=left><th align=left colspan=2><br><input type=checkbox name=agree value='x' checked>&nbsp;<i>I understand and accept
that judges are considered independent contractors and not employees of the Nebraska School Activities Association.<br><br>In compliance with Federal E-Verify regulations, and to the extent they apply to me, I shall use a federal immigration verification system to determine the work eligibility of new employees physically performing services within the State of Nebraska.</i></th></tr>";

   //button to go back and make changes
   echo "<tr align=center><td colspan=2><br><b>Are you sure your information is correct and complete?<br>If
 not, <a href=\"japplication.php?appid=$appid\"><< Go Back and Make Changes</a><br><br></td></tr>";
   
   /********CREDIT CARD FORM********/
   echo "<tr align=center><th colspan=2>Please enter your credit card information:</td></tr>";
   echo "<tr align=center><td colspan=2>";
   echo "<form method=post action=\"$VirtualMerchantAction\">";
   //echo "<input type=hidden name=\"ssl_test_code\" value=\"TRUE\">";
   echo "<input type=hidden name=\"ssl_merchant_id\" value=\"$VirtualMerchantID\">";
   echo "<input type=hidden name=\"ssl_user_id\" value=\"$VirtualMerchantUserID\">";
   echo "<input type=hidden name=\"ssl_pin\" value=\"$VirtualMerchantPIN\">";
   echo "<input type=hidden name=\"ssl_amount\" value=\"$total\">";
   echo "<input type=hidden name=\"ssl_salestax\" value=\"0.00\">";
   echo "<input type=hidden name=\"ssl_show_form\" value=\"false\">";
   echo "<input type=hidden name=\"ssl_invoice_number\" value=\"$appid\">";
   echo "<input type=hidden name=\"ssl_customer_code\" value=\"$appid\">";
   echo "<input type=hidden name=\"ssl_transaction_type\" value=\"ccsale\">";
   echo "<table>";
   echo "<tr align=left><th align=left class=smaller>Cardholder First Name:</th><td><input type=text name=\"ssl_first_name\"></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Cardholder Last Name:</th><td><input type=text name=\"ssl_last_name\"></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Credit Card Billing Address:</th><td><input type=text name=\"ssl_avs_address\" size=40></td></tr>";
   echo "<tr align=left><th align=left class=smaller colspan=2>City:&nbsp;<input type=text name=\"ssl_city\" size=25>&nbsp;&nbsp;State:&nbsp;<input type=text name=\"ssl_state\" size=3>&nbsp;&nbsp;Zip:&nbsp;<input type=text name=\"ssl_avs_zip\" size=5></th></tr>";
   echo "<tr align=center><td colspan=2><div id=errordiv class=searchresults style=\"left:35%;width:300px;visibility:hidden;display:none\"><table width=100%><tr align=center><td><div class=error>Please correct the following fields in your form:</div></td></tr><tr align=left><td>The <b>Expiration Date</b> must be of the format \"MMYY\".  For example, <b>January 2011</b> would be entered as <b>\"0109\"</b>.</td></tr><tr align=center><td><img src='/okbutton.png' onclick=\"document.getElementById('errordiv').style.visibility='hidden';document.getElementById('errordiv').style.display='none';\"></td></tr></table></div></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Type of Card:</th><td><select name=\"cardtype\"><option>VISA<option>Mastercard<option>Discover</select></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Credit Card Number:</th><td><input type=password name=\"ssl_card_number\" size=20></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Expiration Date (MMYY):</th><td><input type=text name=\"ssl_exp_date\" id=\"ssl_exp_date\" size=4></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Card Security Code:</th><td><input type=text name=\"ssl_cvv2cvc2\" size=3>&nbsp;(3-digit number on back of card in signature strip)</td></tr>";
   echo "<input type=hidden name=\"ssl_cvv2cvc2_indicator\" value=\"1\">";
   echo "<input type=hidden name=\"ssl_result_format\" value=\"HTML\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/officials/jdecline.php\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/officials/japproval.php\">";
   echo "<tr align=center><td colspan=2><b>PLEASE ONLY CLICK THIS BUTTON ONCE!!<br><input type=button name=go onClick=\"if(ErrorCheck()) { submit(); } else { errordiv.style.display='block'; errordiv.style.visibility='visible'; }\" value=\"Continue\"><br>PLEASE ONLY CLICK THIS BUTTON ONCE!!</b></td></tr></table></form>";
   /********END CREDIT CARD FORM********/
   echo "</td></tr>";
   echo "</table>";
?>
<script type='text/javascript' src='https://sealserver.trustwave.com/seal.js?style=normal'></script>
<?php
   echo "</body></html>";
   
   exit();
   }//end if no errors
   
}//end if submit

$duedate=GetDueDate("sp","reg");	//SPEECH DUE DATE IS LATER THAN PLAY
$date=split("-",$duedate);
$sduedate2="$date[1]/$date[2]/$date[0]";
$pduedate=GetDueDate("pp","reg");        //SPEECH DUE DATE IS LATER THAN PLAY
$date=split("-",$pduedate);
$pduedate2="$date[1]/$date[2]/$date[0]";
echo "<form method=post action=\"https://secure.nsaahome.org/nsaaforms/officials/japplication.php\" name=appform>";
echo "<input type=hidden name=\"nsaasession\" value=\"$nsaasession\">";
echo "<input type=hidden name=offid value=\"$offid\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width='600px' class='nine'>";
echo "<caption><b>JUDGES APPLICATION FORM<br></b>Nebraska School Activities Association";
if(PastDue($duedate,0) && !ValidUser($nsaasession))
{
   echo "<br><br><font style=\"color:red\"><b>Online Applications were due <u>$pduedate2</u> (Play Production) and <u>$sduedate2</u> (Speech).";
   echo "</caption></table>";
   echo $end_html;
   exit();
}
echo "</caption>";
echo "<tr align=center><td colspan=2>Application Deadlines: <b>$pduedate2 (Play), $sduedate2 (Speech)</b></font></td></tr>";
echo "<tr align=center><td colspan=2><u><b>NOTE: Packets will be mailed out first class.</b></u></th></tr>";
echo "<tr align=left><td colspan=2>To become a registered judge a person must:<br><ul><li><b>Complete the form below and pay the registration fee</b>.</li><li>COMPLETE ONLINE a <b>Rules Meeting</b>.<br>(Please pay specific attention to the <a href=\"rulesschedule.php?sport=sppp\" target=\"_blank\">rules meeting schedule</a> to avoid additional fees.)</li><li>Score an 80% or higher on the <b>Open Book Test.</b></li></ul></td></tr>";  

if($errors!='')
{
   echo "<tr align=left><th align=left colspan=2 class=smaller><div class=\"error\">".$errors."</div></th></tr>";
   //header("Location:japplication.php?session=$session&off_id=$off_id");
}
$getinfo=0;
if($offid && $offid!="" && $offid!='0')	//get already-entered info
{
   $sql="SELECT * FROM judges WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $getinfo=1;
}
if($appid && $appid!="" && $appid!='0')
{
   $sql="SELECT * FROM pendingjudges WHERE appid='$appid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $getinfo=1;
 
   if($row[offid]>0) $offid=$row[offid];
   $firstyrplay=$row[firstyrplay]; 
   $firstyrspeech=$row[firstyrspeech];
   $yearsplay=$row[yearsplay];
   $yearsspeech=$row[yearsspeech];
   $speech=$row[speech]; $play=$row[play];
}
if (empty($reg_year))$reg_year=1;
if($getinfo==1)
{
   $socsec=$row[socsec];
   $socsec1=substr($socsec,0,3);
   $socsec2=substr($socsec,3,2);
   $socsec3=substr($socsec,5,4);
   $first=trim($row[first]);
   $last=trim($row[last]);
   $address=$row[address];
   $city=$row[city];
   $state=$row[state];
   $zip=$row[zip];
   $reg_year=$row[reg_year];
   $hph=$row[homeph];
   $harea=substr($hph,0,3);
   $hpre=substr($hph,3,3);
   $hsuff=substr($hph,6,4);
   $wph=$row[workph];
   $warea=substr($wph,0,3);
   $wpre=substr($wph,3,3);
   $wsuff=substr($wph,6,4);
   $cph=$row[cellph];
   $carea=substr($cph,0,3);
   $cpre=substr($cph,3,3);
   $csuff=substr($cph,6,4);
   $email=$row[email];
   $conviction=$row[conviction];
   $convictionexplain=preg_replace("/<br>/","\r\n",$row[convictionexplain]);
}
if (!empty($_POST['reg_year']))$reg_year=$_POST['reg_year'];
if ($session=='')$session=$_GET['session'];
if (empty($offid)){
    $offid= GetJudgeID($session);
}
/* echo "<tr align=left><th colspan=2 align=left style=\"background-color: yellow\"><table>";
//if(JudgeIsRegisteredLastYear($offid,'play')){
echo "<tr align=left><th colspan=2 align=left>Please complete the forms below if you are interested in judging district or state PLAY PRODUCTION and/or SPEECH championships.</th></tr>";
 echo "<tr align=left><th colspan=2 align=left>";
 // echo "<input type=checkbox id=\"app_play\" name=\"appplay\" value='x' \"";
// if(JudgeIsApplied($offid,'play')) echo " checked";
// echo " disabled";
// echo ">&nbsp&nbsp&nbsp&nbsp"; 
if(!JudgeIsApplied($offid,'play')) echo "<input type=\"hidden\" name=\"app_play\" value='not_applied'>";
if($offid && JudgeIsApplied($offid,'play')) echo "You've already applied to Judge for PLAY PRODUCTION";
else echo "<a href=\"playapp.php?session=$session&off_id=$offid\">PLAY PRODUCTION District/State Application</a>";
if(!JudgeIsApplied($offid,'play'))
echo "&nbsp&nbsp&nbsp&nbsp<input type=checkbox id=\"app_play\" name=\"appplay_play\" value='x' \">Not Interested";

echo "</tr>";
//} else {echo "<tr align=left><th colspan=2 align=left>*You did not register for Play Production last year.</th></tr>";}
//echo "<td><p><a href=\"playapp.php?session=$session&off_id=$offid\">Application to Judge PLAY PRODUCTION</a></p></td></tr>";

//if(JudgeIsRegisteredLastYear($offid,'speech')){
echo "<tr align=left><th colspan=2 align=left>";
//echo "<tr align=left><th colspan=2 align=left><input type=checkbox id=\"appspeech\" name=\"app_speech\" value='x' \"";
// if(JudgeIsApplied($offid,'speech')) echo " checked";
// if(PastDue($sduedate,0) || ($offid && JudgeIsApplied($offid,'speech'))) 
// echo " disabled";
// echo ">&nbsp&nbsp&nbsp&nbsp";
if(!JudgeIsApplied($offid,'speech')) echo "<input type=\"hidden\" name=\"app_speech\" value='not_applied'>";
if($offid && JudgeIsApplied($offid,'speech')) echo "You've already applied to Judge for SPEECH";
else echo "<a href=\"speechapp.php?session=$session&off_id=$offid\">SPEECH District/State Application</a>";
if(!JudgeIsApplied($offid,'speech'))echo "&nbsp&nbsp&nbsp&nbsp<input type=checkbox id=\"app_play\" name=\"appplay_speech\" value='x' \">Not Interested";
echo "</tr>"; 
echo "</table></th></tr>"; */
//} else {echo"<tr align=left><th colspan=2 align=left>*You did not register for Speech last year.</th></tr>";}
//if(!$offid && !$appid) echo "<tr align=left><td colspan=2><br><a class=small href=\"japplication.php?login=1\">Already have a passcode? Click Here to LOGIN and complete this application</a>";
if( !$appid) echo "<tr align=left><td colspan=2 align=left><a class=small href=\"japplication.php?login=1&session=$session\"><span style=\"font-size: 140%\">Already have a passcode? Click Here to LOGIN and complete this application</span></a><br><br>";else echo "<tr align=left><td colspan=2>&nbsp;<br>";
if($login==1)
{
   if($loginerror==1) echo "<br><br><font style=\"color:red\"><b>You have entered an invalid passcode.</b></font>";
   echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=\"blue\">Please Enter Your Passcode:&nbsp;&nbsp;</font>";
   echo "<input type=password name=passcode size=20>&nbsp;";
   echo "<input type=submit name=submit value=\"Log In\">";
   echo "<input type=hidden name=session value=\"$session\"><br><br>";
}
echo "</td></tr>";
echo "<tr align=left><th colspan=2 align=left style=\"background-color: yellow\"><table>";
//if(JudgeIsRegisteredLastYear($offid,'play')){
echo "<tr align=left><th colspan=2 align=left>Please complete the forms below if you are interested in judging district or state PLAY PRODUCTION and/or SPEECH championships.</th></tr>";
 echo "<tr align=left><th colspan=2 align=left>";
 // echo "<input type=checkbox id=\"app_play\" name=\"appplay\" value='x' \"";
// if(JudgeIsApplied($offid,'play')) echo " checked";
// echo " disabled";
// echo ">&nbsp&nbsp&nbsp&nbsp"; 
if(!JudgeIsApplied($offid,'play')) echo "<input type=\"hidden\" name=\"app_play\" value='not_applied'>";
if($offid && JudgeIsApplied($offid,'play')) echo "You've already applied to Judge for PLAY PRODUCTION";
else echo "<a href=\"playapp.php?session=$session&off_id=$offid\">PLAY PRODUCTION District/State Application</a>";
if(!JudgeIsApplied($offid,'play'))
echo "&nbsp&nbsp&nbsp&nbsp<input type=checkbox id=\"app_play\" name=\"appplay_play\" value='x' \">Not Interested";

echo "</tr>";
//} else {echo "<tr align=left><th colspan=2 align=left>*You did not register for Play Production last year.</th></tr>";}
//echo "<td><p><a href=\"playapp.php?session=$session&off_id=$offid\">Application to Judge PLAY PRODUCTION</a></p></td></tr>";

//if(JudgeIsRegisteredLastYear($offid,'speech')){
echo "<tr align=left><th colspan=2 align=left>";
//echo "<tr align=left><th colspan=2 align=left><input type=checkbox id=\"appspeech\" name=\"app_speech\" value='x' \"";
// if(JudgeIsApplied($offid,'speech')) echo " checked";
// if(PastDue($sduedate,0) || ($offid && JudgeIsApplied($offid,'speech'))) 
// echo " disabled";
// echo ">&nbsp&nbsp&nbsp&nbsp";
if(!JudgeIsApplied($offid,'speech')) echo "<input type=\"hidden\" name=\"app_speech\" value='not_applied'>";
if($offid && JudgeIsApplied($offid,'speech')) echo "You've already applied to Judge for SPEECH";
else echo "<a href=\"speechapp.php?session=$session&off_id=$offid\">SPEECH District/State Application</a>";
if(!JudgeIsApplied($offid,'speech'))echo "&nbsp&nbsp&nbsp&nbsp<input type=checkbox id=\"app_play\" name=\"appplay_speech\" value='x' \">Not Interested";
echo "</tr>"; 
echo "</table></th></tr>";
?>
<tr align=left><th align=left colspan=2>Fields marked with a * are required.</th></tr>
<tr align=left><td>*Social Security #:</td>
<td><input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=socsec1 value="<?php echo $socsec1; ?>">-
    <input type=text size=3 maxlength=2 onKeyUp='return autoTab(this,2,event);' name=socsec2 value="<?php echo $socsec2; ?>">-
    <input type=text size=5 maxlength=4 onKeyUp='return autoTab(this,4,event);' name=socsec3 value="<?php echo $socsec3; ?>"></td></tr>
<tr align=left><td>*Name:</td>
<td>first <input type=text size=20 name=first value="<?php echo $first; ?>">&nbsp;
last <input type=text size=20 name=last value="<?php echo $last; ?>"></td></tr>
<tr align=left><td>*Address:</td>
<td><input type=text size=40 name=address value="<?php echo $address; ?>"></td></tr>
<tr align=left><td>*City:</td>
<td><input type=text size=20 name=city value="<?php echo $city; ?>"></td></tr>
<tr align=left><td>*State:</td>
<td><input type=text size=2 name=state value="<?php echo $state; ?>"></td></tr>
<tr align=left><td>*Zip:</td>
<td><input type=text size=10 name=zip value="<?php echo $zip; ?>"></td></tr>
<tr align=left><td>*Years Registered:</td>
<td><input type=text size=10 name=reg_year value="<?php echo $reg_year; ?>" style="border-color: red;"></td></tr>
<tr align=left><td colspan=2>*Phone: (only one phone number is required.)</td></tr>
<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home Phone:</td>
<td>(<input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=harea value="<?php echo $harea; ?>">)<input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=hpre value="<?php echo $hpre; ?>">
-&nbsp;<input type=text size=5 maxlength=4 onKeyUp='return autoTab(this,4,event);' name=hsuff value="<?php echo $hsuff; ?>"></td></tr>
<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Work Phone:</td>
<td>(<input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=warea value="<?php echo $warea; ?>">)<input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=wpre value="<?php echo $wpre; ?>">
-&nbsp;<input type=text size=5 maxlength=4 onKeyUp='return autoTab(this,4,event);' name=wsuff value="<?php echo $wsuff; ?>"></td></tr>
<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cell Phone:</td>
<td>(<input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=carea value="<?php echo $carea; ?>">)<input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=cpre value="<?php echo $cpre; ?>">
-&nbsp;<input type=text size=5 maxlength=4 onKeyUp='return autoTab(this,4,event);' name=csuff value="<?php echo $csuff; ?>"></td></tr>
<tr align=left><td>*E-mail Address:</td>
<td><input type=text size=30 name="email" value="<?php echo $email; ?>"></td></tr>
<tr align=left><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(This will be our means of communication, so it is important that you monitor your e-mail account regularly.)</i></td></tr>
<tr align=left><td colspan=2><p><b>Have you been convicted of a misdemeanor or felony as an adult over the age of 18?</b>  If you answer yes, please include a letter of explanation.  (Convictions of either will not automatically disqualify an official for registration approval.)</p>

<p><input type=radio name='conviction' onClick="document.getElementById('convictiondiv').style.display='';" value="yes"<?php if($conviction=="yes"): ?> checked<?php endif; ?>> Yes&nbsp;&nbsp;&nbsp;
   <input type=radio name='conviction' value="no" onClick="document.getElementById('convictiondiv').style.display='none';"> No</p>
<div id='convictiondiv' style="<?php if($conviction!="yes"): ?>display:none;<?php endif; ?>padding-left:50px;">
        <p><b>If you checked "Yes" above, please explain:</b></p>
        <textarea name="convictionexplain" style="height:200px;width:600px;">
        <?php echo $convictionexplain; ?>
        </textarea>
</div>
</td></tr>
<?php
//echo "<tr align=left><td colspan=2>";
//echo "<input type=checkbox name=qualified value='x'";
//if($qualified=='x') echo " checked";
//echo ">Check here if qualified to judge Lincoln-Douglas Debate</th></tr>";

echo "<tr align=center><td colspan=2>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#000000 1px solid;\"><tr align=center><td>&nbsp;</td><td><b>Activity</b></td><td><b>First Year?</b></td><td><b>Years Registered</b></td><td><b>Fee</b></td><td><b>Due Date</b></td></tr>";

$sduedate=GetDueDate('sp','reg');
$pduedate=GetDueDate('pp','reg');
$sdate=explode("-",$sduedate);
$pdate=explode("-",$pduedate);
echo "<tr align=center><td><input type=checkbox id=\"play\" name=\"play\" value='x' onClick=\"Calculate();\"";
if($play=='x') echo " checked";
if(PastDue($pduedate,0) || ($offid && JudgeIsRegistered($offid,'play'))) echo " disabled";
echo "></td><td align=left>";
if($offid && JudgeIsRegistered($offid,'play')) echo "<s>Play Production</s><br>(You've already registered for Play)";
else echo "Play Production";
echo "</td><td><input type=checkbox name=\"firstyrplay\" value=\"x\"";
if($firstyrplay=='x') echo " checked";
if(PastDue($pduedate,0) || ($offid && JudgeIsRegistered($offid,'play'))) echo " disabled";
echo "></td><td><input type=text size=3 name=\"yearsplay\" value=\"$yearsplay\"";
if(PastDue($pduedate,0) || ($offid && JudgeIsRegistered($offid,'play'))) echo " readOnly=TRUE";
echo "></td><td>$25.00*</td><td>".date("F j",mktime(0,0,0,$pdate[1],$pdate[2],$pdate[0]))."</td></tr>";
echo "<tr align=center><td><input type=checkbox id=\"speech\" name=\"speech\" value='x' onClick=\"Calculate();\"";
if($speech=='x') echo " checked";
if(PastDue($sduedate,0) || ($offid && JudgeIsRegistered($offid,'speech'))) echo " disabled";
echo "></td><td align=left>";
if($offid && JudgeIsRegistered($offid,'speech')) echo "<s>Speech</s><br>(You've already registered for Speech)";
else echo "Speech";
echo "</td><td><input type=checkbox name=\"firstyrspeech\" value=\"x\"";
if(PastDue($sduedate,0) || ($offid && JudgeIsRegistered($offid,'speech'))) echo " disabled";
echo "></td><td><input type=text size=3 name=\"yearsspeech\" value=\"$yearsspeech\"";
if(PastDue($sduedate,0) || ($offid && JudgeIsRegistered($offid,'speech'))) echo " readOnly=TRUE";
echo "></td><td>$25.00*</td><td>".date("F j",mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]))."</td></tr>";
echo "<tr align=center><th colspan=4 align=right>";
echo "Total Fee:</th><td align=left>$<input type=text readOnly=TRUE size=6 name=\"total\" id=\"total\" value=\"$total\"></td><td>&nbsp;</td></tr>";
echo "</table>";
if(!PastDue($pduedate,0) && !PastDue($sduedate,0))
   $bothfee="40.00";
else
   $bothfee="50.00";
echo "<input type=hidden name=\"bothfee\" id=\"bothfee\" value=\"$bothfee\"><p style=\"text-align:left;\">* If you register for Speech AND Play Production ON OR BEFORE $pdate[1]/$pdate[2]/$pdate[0], the total registration fee will be <b><u>$40.00</b></u>.</p></td></tr>";

echo "<tr align=left><th colspan=2 align=left><b>PLEASE NOTE: No refunds of registration fees will be made if an individual fails to complete registration.</b></th></tr>";
echo "<tr align=left>";
echo "<th colspan=2 align=left>";
echo "<input type=checkbox name=agree value='x'";
if($agree=='x') echo " checked";
echo ">&nbsp;<i>I understand and accept that judges are considered independent contractors and not employees of the Nebraska School Activities Association.<br><br>In compliance with Federal E-Verify regulations, and to the extent they apply to me, I shall use a federal immigration verification system to determine the work eligibility of new employees physically performing services within the State of Nebraska.</i></th></tr>";
?>
<tr align=center>
<th colspan=2><br><i>NO refunds of Registration fees will be made if an individual<br>fails to complete ALL registration requirements.</i></th>
</tr>

<tr align=center>

<td colspan=2><input type=submit name=submit value="Submit"></td>
</tr>
<tr align=right><td colspan=2>
<table width="135" border="0" cellpadding="2" cellspacing="0">
<tr>
<td width="135" align="center" valign="top">
<script type='text/javascript' src='https://sealserver.trustwave.com/seal.js?style=normal'></script>
</td>
</tr>
</table>
</td></tr>
</table>
</form>
</td></tr></table>
</body>
</html>
