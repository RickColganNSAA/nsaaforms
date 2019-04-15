<?php
require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

  if($_SERVER['HTTPS']!="on")
  {
    // $redirect= "https://nsaahome.org".$_SERVER['REQUEST_URI'];
    // header("Location:$redirect");
  }

?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php
if($submit=="Log In")
{
   $sql="SELECT t1.* FROM logins AS t1, officials AS t2 WHERE t1.offid=t2.id AND t1.passcode='$passcode'";
   //(MUST include officials table in this query in case there are outdated entries in logins table
   //with no match in officials table)
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
   }
}
else if($session!="")
{
   $sql="SELECT t1.offid FROM logins AS t1, sessions AS t2 WHERE t1.id=t2.login_id AND session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)
      $session="";
   else
   {
      citgf_exec("/usr/local/bin/php logout.php $session > logout.html 2>&1 &");
      $loginerror=0;
      $offid=$row[offid];
   }
}

//get current year and month for late fee purposes
$year=date("Y",time());
$month=date("n",time());
$day=date("d",time());
if($month>=6)	//If after June 1:
   $regfallyear=$year;
else
   $regfallyear=$year-1;
$lastyear=$regfallyear-1;
$curdate=mktime(0,0,0,$month,$day,$year);
$sql="SELECT * FROM reglate_duedates";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $fee=$row[sport]."latefee";
   $date=$row[sport]."latedate";
   if(PastDue($row[duedate],0) && $firstyear!='y') //LATE FEE
      $$fee=number_format(10,2,'.','');
   else
      $$fee=number_format(0,2);
   $temp=split("-",$row[duedate]);
   $$date=date("M j",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
}

//if user has submitted form
if($submit=="Calculate Total Fee" || ($haveread=='x' && $submit))
{
   //calculate fee total
   $total=0;
   $fbtotal=0; $vbtotal=0; $sbtotal=0;
   $bbtotal=0; $wrtotal=0; $swtotal=0; $ditotal=0;
   $sototal=0; $batotal=0; $trtotal=0;
   if($nhsoa=='x')
   {
      $total+=30;
   }
   if($fbcheck=='x')
   {
      $total+=45; $total+=$fblatefee;
      $fbtotal=number_format(45+$fblatefee,2);
   }
   if($vbcheck=='x')
   {
      $total+=45; $total+=$vblatefee;
      $vbtotal=number_format(45+$vblatefee,2);
   }
   if($sbcheck=='x')
   {
      $total+=45; $total+=$sblatefee;
      $sbtotal=number_format(45+$sblatefee,2);
   }
   if($bbcheck=='x')
   {
      $total+=45; $total+=$bblatefee;
      $bbtotal=number_format(45+$bblatefee,2);
   }
   if($wrcheck=='x')
   {
      $total+=45; $total+=$wrlatefee;
      $wrtotal=number_format(45+$wrlatefee,2);
   }
   if($swcheck=='x')
   {
      $total+=45; $total+=$swlatefee;
      $swtotal=number_format(45+$swlatefee,2);
   }
   if($dicheck=='x')
   {
      $total+=45; $total+=$swlatefee;
      $ditotal=number_format(45+$swlatefee,2);
   }
   if($socheck=='x')
   {
      $total+=45; $total+=$solatefee;
      $sototal=number_format(45+$solatefee,2);
   }
   if($bacheck=='x')
   {
      $total+=45; $total+=$balatefee;
      $batotal=number_format(45+$balatefee,2);
   }
   if($trcheck=='x')
   {
      $total+=45; $total+=$trlatefee;
      $trtotal=number_format(45+$trlatefee,2);
   }
   if($trcheck2=='x')
   {
      $total+=45; $total+=$trlatefee;
      $trtotal2=number_format(45+$trlatefee,2);
   }
   $total=number_format($total,2);
}
?>

<html>
<head>
   <title>NSAA | Official's Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<script language="javascript">
function ErrorCheck()
{
   if(document.getElementById('ssl_exp_date').value.match(/\D/) || document.getElementById('ssl_exp_date').value.length!=4)
      return false;
   else
      return true;
}
</script>
<body>
<table width=100%><tr align=center><td>
<?php
//if user has finished entering information: check for errors, save to db, show confirmation page
if($submit && $submit!="Calculate Total Fee" && $submit!="Log In")
{
   //Error-Checking:
   $error=0;

   $hphone=$harea.$hpre.$hsuff;
   $wphone=$warea.$wpre.$wsuff;
   $cphone=$carea.$cpre.$csuff;
   if(trim($middle)!="") 
      $name="$first $middle $last";
   else
      $name="$first $last";
   $socsec=$socsec1.$socsec2.$socsec3;
   if(trim($socsec)=="" || trim($name)=="" || trim($address)=="" || trim($city)=="" || trim($state)=="" || trim($zip)=="" || (trim($hphone)=="" && trim($wphone)=="" && trim($cphone)==""))
   {
      $error=1;
   }
   if($haveread!='x') $error=7;

   //fix capital letters, etc:
      //name:
   $first=Capitalize($first);
   $middle=strtoupper($middle);
   $last=Capitalize($last);
   $address=Capitalize($address);
   $city=Capitalize($city);
   $state=strtoupper($state);
   //if(strlen($state)!=2)
   if(strlen($state)!=2)
   {
      $error=3;
   }

   //check that soc sec is 9 digits, and phone nums are 10 digits:
   if(strlen($socsec)!=9)
      $error=4;
   else if(($hphone!="" && strlen($hphone)!=10)||($wphone!="" && strlen($wphone)!=10)||($cphone!="" && strlen($cphone)!=10))
      $error=5;

   //check that first AND last name were given
   if(trim($first)=="" || trim($last)=="")
      $error=6;

   //Check that Yes or No was checked for conviction question - if Yes, require explanation
   if(!$conviction)
      $error=8;
   else if($conviction=="yes" && trim($convictionexplain)=="")
      $error=9;

   //Check that at least one sport was checked and not just NHSOA
   if($total==30)
   {
      $error=10;
   }

   //display entered info for confirmation:
   if($error==0)
   {
?>
   <table style="width:500px;" cellspacing=0 cellpadding=5>
   <caption><b>FOR OFFICIALS LIVING IN NEBRASKA<br>Nebraska School Activities Association Officials' Application Form</b></caption>
   <tr align=center><td colspan=2><font size=2>Please make sure the information you entered is correct:</font>
   <br>
   <font style="color:red"><b>DO NOT use the "Back" button on your browser to go back and make changes.  Instead click the "Go Back and Make Changes" link below.</b></font>
   <br><br></td></tr>
<?php
   $string="";
   $string.="<tr align=left><th align=left class=smaller>Social Security #:</th><td>$socsec</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>Full Name:</th><td>$name</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>Address:</th><td>$address</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>City:</th><td>$city</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>State:</th><td>$state</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>Zip:</th><td>$zip</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>Home Phone:</th><td>($harea)$hpre-$hsuff</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>Work Phone:</th><td>($warea)$wpre-$wsuff</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>Cell Phone:</th><td>($carea)$cpre-$csuff</td></tr>";
   $string.="<tr align=left><th align=left class=smaller>E-mail Address:</th><td>$email</td></tr>";
   $string.="<tr align=left><td colspan=2><b>Total number of years as an active official in Nebraska with the NSAA:</b>&nbsp;&nbsp;";
   if($firstyear=='y') $string.="0";
   else $string.=$years;
   $string.="</td></tr>";
   $string.="<tr align=left><td colspan=2><b>Have you been convicted of a misdemeanor or felony as an adult over the age of 18?</b>&nbsp;&nbsp;";
   if($conviction=="yes") $string.="YES<blockquote>".preg_replace("/\r\n/","<br>",$convictionexplain)."</blockquote>";
   else $string.="NO";
   $string.="</td></tr>";
   if($gender)
      $string.="<tr align=left><td colspan=2><b>Gender:</b> $gender</td></tr>";
   if($minority=='x')
      $string.="<tr align=left><td colspan=2><b>Minority:</b> YES</td></tr>";
   $string.="<tr align=left><th align=left colspan=2><br><i>I understand that my credit card will be billed today.  However, the Registration packet will be mailed out on the Approximate Mail Date (see chart below).</i></th></tr>";
   $string.="<tr align=center><td colspan=2><br><table cellspacing=0 cellpadding=5 frame=\"all\" rules=\"all\" style=\"border:#a0a0a0 1px solid;\">";
   $string.="<tr align=center valign=top><th class=smaller>Sport</th><th class=smaller># of Varsity<br>Contests<br>Officiated<th class=smaller>Fee</th><th class=smaller>Late Fee</th><th class=smaller>Total Fee</th><th class=smaller>Approximate<br>Mail Date</th></tr>";
   if($fbcheck=='x')
   {
      $string.="<tr align=left><td>Football</td><td>$fbcontests</td><td>$45.00</td><td>$$fblatefee</td><td>$$fbtotal</td><td>July/Aug</td></tr>";
      $fb='x';
   }
   if($vbcheck=='x')
   {
      $string.="<tr align=left><td>Volleyball</td><td>$vbcontests</td><td>$45.00</td><td>$$vblatefee</td><td>$$vbtotal</td><td>July/Aug</td></tr>";
      $vb='x';
   }
   if($sbcheck=='x')
   {
      $string.="<tr align=left><td>Softball</td><td>$sbcontests</td><td>$45.00</td><td>$$sblatefee</td><td>$$sbtotal</td><td>July/Aug</td></tr>";
      $sb='x';
   }
   if($bbcheck=='x')
   {
      $string.="<tr align=left><td>Basketball</td><td>$bbcontests</td><td>$45.00</td><td>$$bblatefee</td><td>$$bbtotal</td><td>Oct/Nov</td></tr>";
      $bb='x';
   }
   if($wrcheck=='x')
   {
      $string.="<tr align=left><td>Wrestling</td><td>$wrcontests</td><td>$45.00</td><td>$$wrlatefee</td><td>$$wrtotal</td><td>Oct/Nov</td></tr>";
      $wr='x';
   }
   if($swcheck=='x')
   {
      $string.="<tr align=left><td>Swimming/Diving</td><td>N/A</td><td>$45.00</td><td>$$swlatefee</td><td>$$swtotal</td><td>Oct/Nov</td></tr>";
      $sw='x';
   }
   if($dicheck=='x')
   {
      $string.="<tr align=left><td>Diving Only</td><td>N/A</td><td>$45.00</td><td>$$swlatefee</td><td>$$ditotal</td><td>Oct/Nov</td></tr>";
      $di='x';
   }
   if($socheck=='x')
   {
      $string.="<tr align=left><td>Soccer</td><td>$socontests</td><td>$45.00</td><td>$$solatefee</td><td>$$sototal</td><td>Jan/Feb</td></tr>";
      $so='x';
   }
   if($bacheck=='x')
   {
      $string.="<tr align=left><td>Baseball</td><td>$bacontests</td><td>$45.00</td><td>$$$balatefee</td><td>$$batotal</td><td>Jan/Feb</td></tr>";
      $ba='x';
   }
   if($trcheck=='x')
   {
      $string.="<tr align=left><td>Track Starter/Referee</td><td>N/A</td><td>$45.00</td><td>$$trlatefee</td><td>$trtotal</td><td>Jan/Feb</td></tr>";
      $tr='x';
   }
   if($trcheck2=='x')
   {
      $string.="<tr align=left><td>Track Referee Only</td><td>N/A</td><td>$45.00</td><td>$$trlatefee</td><td>$trtotal2</td><td>Jan/Feb</td></tr>";
      $tr2='x';
   }
   if($nhsoa=='x')
   {
      $string.="<tr align=left><td align=right colspan=4><b>NHSOA Membership:</b><td align=left>$30.00</td><td>&nbsp;</td></tr>";
   }
   $string.="<tr align=right><th class=smaller colspan=4>TOTAL FEE:</th><td align=left><font size=2>$$total</font></td><td>&nbsp;</td></tr>";
   $string.="</table></td></tr>";
   echo $string;

   //create invoice number and write to html file that will be e-mailed to NSAA
   if(!$appid || $appid=="" || $appid=='0') 
   {
      $appid=time();
      //MAKE SURE APPID IS UNIQUE!!!
      $sql="SELECT * FROM officialsapp WHERE appid='$appid'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $appid--;
         $sql="SELECT * FROM officialsapp WHERE appid='$appid'";
         $result=mysql_query($sql);
      }
   }
   $date=date("r T",$appid);
   //$open=fopen(citgf_fopen("apps/app$appid.html"),"w");
   //fwrite($open,"<html><body><table><caption>Application # $appid:</caption><tr align=left><th align=left class=smaller>Date:</th><td>$date</td></tr>$string");
   //fclose($open); 
 citgf_makepublic("apps/app$appid.html");
   $html="<html><body><table><caption>Application # $appid:</caption><tr align=left><th align=left class=smaller>Date:</th><td>$date</td></tr>$string";
   $html=addslashes($html);
   $sql="INSERT INTO officialsapp (appid,html) VALUES ('$appid','$html')";
   $result=mysql_query($sql);

   //enter official into pending table
   $last=addslashes($last); $first=addslashes($first); $middle=addslashes($middle);
   $address=addslashes($address); $city=addslashes($city);
   $convictionexplain=addslashes(preg_replace("/\r\n/","<br>",$convictionexplain));
   $datereg=time();
   $sql="SELECT * FROM pendingoffs WHERE appid='$appid'";
   $result=mysql_query($sql);
   if(!$offid || $offid=='0')
      $offid="";
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO pendingoffs (offid,appid,datesub,socsec,last,first,middle,address,city,state,zip,homeph,workph,cellph,email,firstyr,years,nhsoa,gender,minority,conviction,convictionexplain,fb,fbcontests,vb,vbcontests,sb,sbcontests,bb,bbcontests,wr,wrcontests,sw,di,so,socontests,ba,bacontests,tr,tr2) VALUES ('$offid','$appid','$datereg','$socsec','".trim($last)."','".trim($first)."','".trim($middle)."','".trim($address)."','".trim($city)."','".trim($state)."','".trim($zip)."','$hphone','$wphone','$cphone','$email','$firstyr','$years','$nhsoa','$gender','$minority','$conviction','$convictionexplain','$fb','$fbcontests','$vb','$vbcontests','$sb','$sbcontests','$bb','$bbcontests','$wr','$wrcontests','$sw','$di','$so','$socontests','$ba','$bacontests','$tr','$tr2')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE pendingoffs SET offid='$offid',datesub='$datereg',socsec='$socsec',last='$last',first='$first',middle='$middle',address='$address',city='$city',state='$state',zip='$zip',homeph='$hphone',workph='$wphone',cellph='$cphone',email='$email',firstyr='$firstyr',years='$years',gender='$gender',minority='$minority',conviction='$conviction',convictionexplain='$convictionexplain',";
      if($nhsoa=='x') $sql2.="nhsoa='$nhsoa',";
      if($fb=='x') $sql2.="fb='$fb',fbcontests='$fbcontests',";
      if($vb=='x') $sql2.="vb='$vb',vbcontests='$vbcontests',";
      if($sb=='x') $sql2.="sb='$sb',sbcontests='$sbcontests',";
      if($bb=='x') $sql2.="bb='$bb',bbcontests='$bbcontests',";
      if($wr=='x') $sql2.="wr='$wr',wrcontests='$wrcontests',";
      if($sw=='x') $sql2.="sw='$sw',";
      if($di=='x') $sql2.="di='$di',";
      if($so=='x') $sql2.="so='$so',socontests='$socontests',";
      if($ba=='x') $sql2.="ba='$ba',bacontests='$bacontests',";
      if($tr=='x') $sql2.="tr='$tr',";
      if($tr2=='x') $sql2.="tr2='$tr2',";
      $sql2=substr($sql2,0,strlen($sql2)-1);
      $sql2.=" WHERE appid='$appid'";
   }
   $result2=mysql_query($sql2);
   if(mysql_error())
   {
      echo mysql_error();
      $Html=mysql_error()."<br>$sql2<br>Date: ".date("m/d/y")." (".time().")<br><br>Appid:$appid";
      $Text=ereg_replace("<br>","\r\n",$Html);
      $Attm=array();
      SendMail("nsaa@nsaahome.org","NSAA","agaffigan@gazelleincorporated.com","Ann Gaffigan","Officials App SQL Error",$Text,$Html,$Attm);
      exit();
   }


   if(!offid || $offid=="")
   {
      //check to see if this soc sec # is already in officials table
      $sql="SELECT * FROM officials WHERE socsec='$socsec'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)	//ssn found in database
      {
	 $row=mysql_fetch_array($result); 
     	 //check to see if passcode exists for this official, if so re-direct to login
	 $sql2="SELECT passcode FROM logins WHERE offid='$row[id]'";
  	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[0]!="" && mysql_num_rows($result2)>0)	//passcode found, make them login
	 {
	    echo "<tr align=center><td colspan=2><br>";
	    echo "<b>Your social security number is already in our system.  Please <a class=small href=\"application.php?login=1\">Log In</a> and complete this application.<br></td></tr>";
	    echo "</table></body></html>";
	    exit();
	 }
	 else			//no passcode found
	 {
	    //check that first and last names match: if they DO, allow them to continue,
	    //but CHECK THAT THEY AREN'T PAYING FOR SPORTS THEY HAVE ALREADY PAID FOR...
	    if(trim(addslashes(strtolower($row[first])))==trim(strtolower($first)) && trim(addslashes(strtolower($row[last])))==trim(strtolower($last)))
	    {
	       echo "<tr align=center><td colspan=2><table>";
	       echo "<tr align=left><td><b>Your social security number is already in our system, but we have not assigned you a passcode yet.  Your entry in the database will be updated with the information you have just entered, shown above.  If you need to make changes to this information, <a class=small href=\"application.php?appid=$appid\">Click Here</a> to go back and do so BEFORE entering your credit card information below.  Once your credit card is approved, you will be issued a passcode.</b></td></tr>";
	       $offid=$row[id];
	       //update pending table:
	       $sql3="UPDATE pendingoffs SET offid='$offid' WHERE appid='$appid'";
	       $result3=mysql_query($sql3);
	    }
	    else	//names don't match: Make them go back and double-check information
	    {
	       echo "<tr align=center><td colspan=2>";
	       echo "<b>The social security number you have entered is already in our database, but it does not match the first and/or last names you have entered.  Please <a class=small href=\"application.php?appid=$appid\">Click Here</a> to go back and make sure you have entered all of your information correctly.  If you are sure your information is correct and you still receive this error, please contact the NSAA.</b></td></tr>";
	       echo "</table></body></html>";
	       exit();
	    }
	 }
      }
   }

   //If $offid is known (they are in database), check that they are not paying for a sport they've
   //already paid for:
   if($offid && $offid!="")
   {
      $ccappsp=array("fb","vb","sb","bb","wr","sw","di","so","ba","tr","tr2");
      $ccappsp2=array("Football","Volleyball","Softball","Basketball","Wrestling","Swimming","Diving","Soccer","Baseball","Track Starter/Referee","Track Referee");
      $paid="";
      for($i=0;$i<count($ccappsp);$i++)
      {
	 $check=$ccappsp[$i]."check";
	 if($$check=='x')
         {
	    $table=$ccappsp[$i]."off";
	    $sql="SELECT payment FROM $table WHERE payment!='' AND offid='$offid'";
	    $result=mysql_query($sql);
	    if(mysql_num_rows($result)>0)	//already paid for this sport
	    {
	       $paid.=$ccappsp2[$i].", ";
	    }
	 }
      }
      if(trim($paid)!="")
      {
         $paid=substr($paid,0,strlen($paid)-2);
         echo "<tr align=center><td colspan=2><br><hr><font style=\"color:red\"><b>You have already paid for $paid.</b></font><b>  Please <a class=small href=\"application.php?appid=$appid&offid=$offid\">Go Back</a> and UNCHECK the sport(s) you have already paid for.</td></tr>"; 
         echo "</table></body></html>";
         exit();
      }
   }

   //button to go back and make changes
   echo "<tr align=center><td colspan=2><br><b>Are you sure your information is correct and complete?<br>If
 not, <a href=\"application.php?appid=$appid\"><< Go Back and Make Changes</a><br><br></td></tr>";
   
   /********CREDIT CARD FORM********/
   echo "<tr align=center><th colspan=2>Please enter your credit card information:</td></tr>";
   echo "<tr align=center><td colspan=2>";
   echo "<form method=post action=\"$VirtualMerchantAction\">";
   //echo "<input type=hidden name=\"ssl_test_code\" value=\"TRUE\">";
   echo "<input type=hidden name=\"ssl_merchant_id\" value=\"$VirtualMerchantID\">";
   echo "<input type=hidden name=\"ssl_user_id\" value=\"$VirtualMerchantUserID\">";
   echo "<input type=hidden name=\"ssl_pin\" value=\"$VirtualMerchantPIN\">";
   if($offid=="3427") $total="0.01";
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
   echo "<tr align=center><td colspan=2><div id=errordiv class=searchresults style=\"left:35%;width:300px;visibility:hidden;display:none\"><table width=100%><tr align=center><td><div class=error>Please correct the following fields in your form:</div></td></tr><tr align=left><td>The <b>Expiration Date</b> must be of the format \"MMYY\".  For example, <b>January 2009</b> would be entered as <b>\"0109\"</b>.</td></tr><tr align=center><td><img src='/okbutton.png' onclick=\"document.getElementById('errordiv').style.visibility='hidden';document.getElementById('errordiv').style.display='none';\"></td></tr></table></div></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Type of Card:</th><td><select name=\"cardtype\"><option>VISA</option><option>Mastercard</option><option>Discover</option></select></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Credit Card Number:</th><td><input type=password name=\"ssl_card_number\" size=20></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Expiration Date (MMYY):</th><td><input type=text name=\"ssl_exp_date\" id=\"ssl_exp_date\" size=4></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Card Security Code:</th><td><input type=text name=\"ssl_cvv2cvc2\" size=3>&nbsp;(3-digit number on back of card in signature strip)</td></tr>";
   echo "<input type=hidden name=\"ssl_cvv2cvc2_indicator\" value=\"1\">";
   echo "<input type=hidden name=\"ssl_result_format\" value=\"HTML\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/officials/decline.php\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/officials/approval.php\">";
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
?>
<form method=post action="https://secure.nsaahome.org/nsaaforms/officials/application.php" name=appform>
<input type=hidden name=offid value="<?php echo $offid; ?>">
<table width='600px' class='nine'>
<caption><b>FOR OFFICIALS LIVING IN NEBRASKA<br>Nebraska School Activities Association Officials' Application Form</b>
</caption>
<tr align=center><td colspan=2><font style="color:red"><b>NSAA registration includes NFHS membership dues.  DO NOT send separate payment to NFHS.</b></font></td></tr>
<tr align=center><td colspan=2><u><b>NOTE: Packets will not be available to purchase at rules meetings!</b></u></th></tr>
<?php
if($submit!='Calculate Total Fee')
{
if($error>0)
{
   echo "<tr align=left><th align=left colspan=2 class=smaller><div class='error'><b>ERROR:</b><br>";
   if($error==10) echo "You must register for at least one sport.<br>";
   if($error==1) echo "You did not fill in one or more required fields.  Please do so and then hit \"Submit\" at the bottom of this page.<br>";
   if($error==3) echo "You must enter your state abbreviation in the form of 2 capital letters, like \"NE\".";
   if($error==4) echo "You must enter a <u>9-digit</u> social security number.";
   if($error==5) echo "You must enter the full phone number, including area code, for any phone number you wish to provide.";
   if($error==6) echo "You must enter your first name and your last name in the text boxes provided.";
   if($error==7) echo "You must check that you have read the NSAA Regulations Governing Officials before you can continue.";
   if($error==8) echo "You must indicate whether or not you have ever been convicted of a misdemeanor or felony.<br>";
   if($error==9) echo "If you check \"Yes\" that you have been convicted of a misdemeanor or felony, you must include an explanation in the text box provied.<br>";
   echo "</div></th></tr>";
}
$sql="";
if($offid && $offid!="" && $offid!='0')	//get already-entered info
{
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $bacontests=CountVarsityContests($offid,'ba',$lastyear);
   $bbcontests=CountVarsityContests($offid,'bb',$lastyear);
   $fbcontests=CountVarsityContests($offid,'fb',$lastyear);
   $sbcontests=CountVarsityContests($offid,'sb',$lastyear);
   $socontests=CountVarsityContests($offid,'so',$lastyear);
   $vbcontests=CountVarsityContests($offid,'vb',$lastyear);
   $wrcontests=CountVarsityContests($offid,'wr',$lastyear);
   $blockreg=$row[blockreg];
}
if($appid && $appid!="" && $appid!='0')
{
   $sql="SELECT * FROM pendingoffs WHERE appid='$appid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
 
   if($row[offid]>0) $offid=$row[offid];
   $firstyr=$row[firstyr]; $years=$row[years];
   $fbcheck=$row[fb]; $fbcontests=$row[fbcontests];
   $vbcheck=$row[vb]; $vbcontests=$row[vbcontests]; $sbcontests=$row[sbcontests];
   $sbcheck=$row[sb]; $bbcheck=$row[bb]; $bbcontests=$row[bbcontests];
   $wrcheck=$row[wr]; $wrcontests=$row[wrcontests]; $swcheck=$row[sw];
   $dicheck=$row[di]; $socheck=$row[so]; $socontests=$row[socontests];
   $bacheck=$row[ba]; $bacontests=$row[bacontests]; $trcheck=$row[tr]; $trcheck2=$row[tr2];
}
if($sql!='')
{
   $socsec=$row[socsec];
   $socsec1=substr($socsec,0,3);
   $socsec2=substr($socsec,3,2);
   $socsec3=substr($socsec,5,4);
   $first=$row[first];
   $last=$row[last];
   $middle=$row[middle];
   $address=$row[address];
   $city=$row[city];
   $state=$row[state];
   $zip=$row[zip];
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
   $gender=$row[gender];
   $minority=$row[minority];
   $nhsoa=$row[nhsoa];
}
}

if(!$offid && !$appid) 
{
   echo "<tr align=left><td colspan=2><a href=\"application.php?login=1\">Already have a passcode? Click Here to LOGIN and complete this application.</a>";
   echo "<br><a href=\"affapplication.php\">Are you an AFFILIATE OFFICIAL from another State? Click Here!</a>";
}
else echo "<tr align=left><td colspan=2>&nbsp;<br>";
if($login==1)
{
   if($loginerror==1) echo "<br><br><font style=\"color:red\"><b>You have entered an invalid passcode.</b></font>";
   echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=\"blue\">Please Enter Your Passcode:&nbsp;&nbsp;</font>";
   echo "<input type=password name=passcode size=20>&nbsp;";
   echo "<input type=submit name=submit value=\"Log In\">";
}
if($blockreg=='x')      //OFFICIAL IS BLOCKED FROM REGISTERING
{
   echo "<br><br><div class='error' style='text-align:center;'>Hello, ".trim("$first $last").". In order to register, you will first need to contact the NSAA at 402-489-0386.</div><br><br>";
   echo "</td></tr></table></form></body></html>";
   exit();
}
echo "</td></tr>";
?>
<tr align=left><th align=left colspan=2>Fields marked with a * are required.</th></tr>
<tr align=left><td>*Social Security #:</td>
<td><input type=text size=4 maxlength=3 onKeyUp='return autoTab(this,3,event);' name=socsec1 value="<?php echo $socsec1; ?>">-
    <input type=text size=3 maxlength=2 onKeyUp='return autoTab(this,2,event);' name=socsec2 value="<?php echo $socsec2; ?>">-
    <input type=text size=5 maxlength=4 onKeyUp='return autoTab(this,4,event);' name=socsec3 value="<?php echo $socsec3; ?>"></td></tr>
<tr align=left><td>*Name:</td>
<td>first <input type=text size=20 name=first value="<?php echo $first; ?>">&nbsp;
MI <input type=text size=2 name=middle value="<?php echo $middle; ?>">&nbsp;
last <input type=text size=20 name=last value="<?php echo $last; ?>"></td></tr>
<tr align=left><td>*Address:</td>
<td><input type=text size=40 name=address value="<?php echo $address; ?>"></td></tr>
<tr align=left><td>*City:</td>
<td><input type=text size=20 name=city value="<?php echo $city; ?>"></td></tr>
<tr align=left><td>*State:</td>
<td><input type=text size=2 name=state value="<?php echo $state; ?>"></td></tr>
<tr align=left><td>*Zip:</td>
<td><input type=text size=10 name=zip value="<?php echo $zip; ?>"></td></tr>
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
<tr align=left><td>E-mail Address:</td>
<td><input type=text size=30 name=email value="<?php echo $email; ?>"></td></tr>
<tr align=left><td colspan=2>
<?php
echo "<input type=checkbox name=firstyear value='y'";
if($firstyear=='y') echo " checked";
echo ">";
?>
Check here if you are a first-year official with the NSAA</th></tr>
<tr align=left><td colspan=2>Total number of years as an active official in Nebraska with the NSAA:&nbsp;&nbsp;<input type=text name=years size=2 value="<?php echo $years; ?>"></th></tr>
<tr align=left><td colspan=2><p><b>Have you been convicted of a misdemeanor or felony as an adult over the age of 18?</b>  If you answer yes, please include a letter of explanation.  (Convictions of either will not automatically disqualify an official for registration approval.)</p>
<p><input type=radio id='convictionyes' name='conviction' onClick="document.getElementById('convictiondiv').style.display='';" value="yes"<?php if($conviction=="yes"): ?> checked<?php endif; ?>> Yes&nbsp;&nbsp;&nbsp;
   <input type=radio id='convictionno' name='conviction' value="no" onClick="document.getElementById('convictiondiv').style.display='none';"<?php if($conviction=="no"): ?> checked<?php endif; ?>> No</p>
<div id='convictiondiv' style="<?php if($conviction!="yes"): ?>display:none;<?php endif; ?>padding-left:50px;">
        <p><b>If you checked "Yes" above, please explain:</b></p>
	<textarea name="convictionexplain" style="height:200px;width:600px;">
	<?php echo $convictionexplain; ?>
	</textarea>
</div>
</td></tr>
<tr align=left><td colspan=2><br><b>Demographic Information (Optional):</b></td></tr>
<tr align=left><td colspan=2><b>Gender:</b>&nbsp;&nbsp;<input type=radio name="gender" value="M"<?php if($gender=="M"): ?> checked<?php endif; ?>>Male&nbsp;&nbsp;<input type=radio name="gender" value="F"<?php if($gender=="F"): ?> checked<?php endif; ?>>Female</td></tr>
<tr align=left><td colspan=2><b>Minority:</b>&nbsp;&nbsp;<input type=checkbox name="minority" value="x"<?php if($minority=='x'): ?> checked<?php endif; ?>> Check here if you are of a non-White/non-Caucasian race</td></tr>

<tr align=left><td colspan=2><br><b>Please check the sports for which you are making application.  When you are finished, click "Submit" below to enter your credit card information.<br><br></b></td></tr>
<tr align=center><td colspan=2>
   <table cellspacing=0 cellpadding=3 border=1 bordercolor=#000000>
   <tr align=left valign=top>
   <td><b>Sport*</b></td>
   <td><b>Due Date</b></td>
   <td><b>Fee</b></td>
   <td><b>Late Fee<br>after date<br>shown**</b></td>
   <td><b>Varsity<br>Contests<br>Officiated<br>Last Year***</b></td>
   <td><b>Approximate<br>Mail Date</b></td></tr>
<?php
//get due dates from database
$sql="SELECT * FROM reg_duedates";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[1]=="fb") $fbdate=$row[2];
   else if($row[1]=="vb") $vbdate=$row[2];
   else if($row[1]=="sb") $sbdate=$row[2];
   else if($row[1]=="bb") $bbdate=$row[2];
   else if($row[1]=="wr") $wrdate=$row[2];
   else if($row[1]=="sw") $swdate=$row[2];
   else if($row[1]=="di") $swdate=$row[2];
   else if($row[1]=="so") $sodate=$row[2];
   else if($row[1]=="ba") $badate=$row[2];
   else if($row[1]=="tr") { $trdate=$row[2]; $trdate2=$trdate; }
}
$temp=split("-",$fbdate);
$thisyear=$temp[0];	//"Fall" year of the current school year
$fbdate2="$temp[1]/$temp[2]/$temp[0]";
echo "
   <tr align=left>
   <td><input type=checkbox name=fbcheck value='x'";
if($fbcheck=='x') echo " checked";
if(PastDue($fbdate,0) || !PastDue("$thisyear-05-31",0)) 
{
   echo " disabled";
}
echo ">&nbsp;Football $due</td>
   <td>$fbdate2</td>
   <td>$45.00</td>
   <td>$$fblatefee<br>($fblatedate)</td>
   <td><input type=text size=2 name=fbcontests value=\"$fbcontests\"";
   //if(PastDue($fbdate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "></td>
   <td>July/Aug</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=vbcheck value='x'";
$temp=split("-",$vbdate);
$vbdate2="$temp[1]/$temp[2]/$temp[0]";
if($vbcheck=='x') echo " checked";
if(PastDue($vbdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Volleyball</td>
   <td>$vbdate2</td>
   <td>$45.00</td>
   <td>$$vblatefee<br>($vblatedate)</td>
   <td><input type=text size=2 name=vbcontests value=\"$vbcontests\"";
   //if(PastDue($vbdate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "></td>
   <td>July/Aug</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=sbcheck value='x'";
$temp=split("-",$sbdate);
$sbdate2="$temp[1]/$temp[2]/$temp[0]";
if($sbcheck=='x') echo " checked";
if(PastDue($sbdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Softball</td>
   <td>$sbdate2</td>
   <td>$45.00</td>
   <td>$$sblatefee<br>($sblatedate)</td>
   <td><input type=text size=2 name=sbcontests value=\"$sbcontests\"";
   //if(PastDue($sbdate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "</td>
   <td>July/Aug</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=bbcheck value='x'";
$temp=split("-",$bbdate);
$bbdate2="$temp[1]/$temp[2]/$temp[0]";
if($bbcheck=='x') echo " checked";
if(PastDue($bbdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Basketball</td>
   <td>$bbdate2</td>
   <td>$45.00</td>
   <td>$$bblatefee<br>($bblatedate)</td>
   <td><input type=text size=2 name=bbcontests value=\"$bbcontests\"";
   //if(PastDue($bbdate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "></td>
   <td>Oct/Nov</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=wrcheck value='x'";
$temp=split("-",$wrdate);
$wrdate2="$temp[1]/$temp[2]/$temp[0]";
if($wrcheck=='x') echo " checked";
if(PastDue($wrdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Wrestling</td>
   <td>$wrdate2</td>
   <td>$45.00</td>
   <td>$$wrlatefee<br>($wrlatedate)</td>
   <td><input type=text size=2 name=wrcontests value=\"$wrcontests\"";
   //if(PastDue($wrdate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "></td>
   <td>Oct/Nov</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=swcheck value='x'";
$temp=split("-",$swdate);
$swdate2="$temp[1]/$temp[2]/$temp[0]";
if($swcheck=='x') echo " checked";
if(PastDue($swdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Swimming/Diving</td>
   <td>$swdate2</td>
   <td>$45.00</td>
   <td>$$swlatefee<br>($swlatedate)</td>
   <td>&nbsp;</td>
   <td>Oct/Nov</td>
   </tr>";
   echo "
   <tr align=left>
   <td><input type=checkbox name=dicheck value='x'";
if($dicheck=='x') echo " checked";
if(PastDue($swdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Diving Only</td>
   <td>$swdate2</td>
   <td>$45.00</td>
   <td>$$swlatefee<br>($swlatedate)</td>
   <td>&nbsp;</td>
   <td>Oct/Nov</td>
   </tr>";
   echo "
   <tr align=left>
   <td><input type=checkbox name=socheck value='x'";
$temp=split("-",$sodate);
$sodate2="$temp[1]/$temp[2]/$temp[0]";
if($socheck=='x') echo " checked";
if(PastDue($sodate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Soccer</td>
   <td>$sodate2</td>
   <td>$45.00</td>
   <td>$$solatefee<br>($solatedate)</td>
   <td><input type=text size=2 name=socontests value=\"$socontests\"";
   //if(PastDue($sodate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "></td>
   <td>Jan/Feb</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=bacheck value='x'";
$temp=split("-",$badate);
$badate2="$temp[1]/$temp[2]/$temp[0]";
if($bacheck=='x') echo " checked";
if(PastDue($badate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Baseball</td>
   <td>$badate2</td>
   <td>$45.00</td>
   <td>$$balatefee<br>($balatedate)</td>
   <td><input type=text size=2 name=bacontests value=\"$bacontests\"";
   //if(PastDue($badate,0) || !PastDue("$thisyear-05-31",0))
      echo " readOnly=true";
   echo "></td>
   <td>Jan/Feb</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=trcheck value='x'";
$temp=split("-",$trdate);
$trdateshow="$temp[1]/$temp[2]/$temp[0]";
if($trcheck=='x') echo " checked";
if(PastDue($trdate,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Track Starter/Referee</td>
   <td>$trdateshow</td>
   <td>$45.00</td>
   <td>$$trlatefee<br>($trlatedate)</td>
   <td>&nbsp;</td>
   <td>Jan/Feb</td>
   </tr>
   <tr align=left>
   <td><input type=checkbox name=trcheck2 value='x'";
if($trcheck2=='x') echo " checked";
if(PastDue($trdate2,0) || !PastDue("$thisyear-05-31",0)) echo " disabled";
echo ">&nbsp;Track Referee Only</td>
   <td>$trdateshow</td>
   <td>$45.00</td>
   <td>$$trlatefee<br>($trlatedate)</td>
   <td>&nbsp;</td>
   <td>Jan/Feb</td>
   </tr>";
?>
   </table>
	<ul style='list-style-type:none;'>
   <li>*  If the checkbox for a sport is disabled, then registration for that sport has been completed for the current school year.</li>
   <li>**  Late fees now apply for veteran officials for ALL sports.</li>
   <li>*** The system automatically calculates the number of Varsity Contests you officiated last year based on the number of regular season games you entered on your schedule and the number of postseason games you were contracted for. This field is not editable.</li>
	</ul>
</td></tr>

<!--NHSOA REGISTRATION-->
<tr align=left><td colspan=2><input type=checkbox name="nhsoa" value="x"<?php if($nhsoa=='x'):?> checked<?php endif; ?>>&nbsp;<b>NHSOA Membership $30.00</b> (This membership fee allows each paying member to attend clinics in each sport they register in free of charge. For questions about NHSOA membership please visit the <a href="http://www.nhsoahome.org" target="_blank">NHSOA website</a>.</td>
</tr>
<tr align=center>
<td colspan=2><br>
<input type=submit name=submit value="Calculate Total Fee">
</td></tr>
<tr align=center>
<th colspan=2>
Total Fee: $<?php echo $total; ?>
</th></tr>
<tr align=left>
<th colspan=2><br><input type=checkbox name="haveread" value="x">&nbsp;I have read the <a href="https://nsaahome.org/textfile/ref/refneeds.pdf" target="_blank">NSAA Regulations Governing Officials</a>.  I further understand and accept that officials are considered independent contractors and not employees of the Nebraska School Activities Association.</th>
</tr>
<tr align=center>
<th colspan=2><br><i>You will enter your credit card information after clicking "Submit" below.<br>NO refunds of Registration fees will be made if an individual<br>fails to complete ALL registration requirements.</i><br><br><input type=submit name=submit value="Submit"></td>
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
