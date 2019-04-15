<?php
require 'functions.php';
require 'variables.php';
if($preview!=1)
{
   if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
   {
      echo "ERROR: No application id.";
      exit();
   }
   else    //check that invoice is not old (needs to equal today's date)
   {
      $today=date("m/d/y");
      $invoicedate=date("m/d/y",$ssl_invoice_number);
      if($today!=$invoicedate && $secret!='46D5431FF61CD7EC47478FB32A52A')
      {
         echo "Expired session.  To complete your payment, you must start over.<br><br>";
         echo "<a href=\"affapplication.php\">Click Here to complete the Affiliate Officials Application Form</a>";
         exit();
      }
   }
}

//affapproval.php: Page displayed if CC was approved (for OOS officials)
//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if($preview!=1)
{
//insert transaction data into database
$sql="SELECT * FROM officialsapp WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   $sql2="INSERT INTO officialsapp (appid,approved) VALUES ('$ssl_invoice_number','yes')";
else
   $sql2="UPDATE officialsapp SET approved='yes' WHERE appid='$ssl_invoice_number'";
$result2=mysql_query($sql2);

//insert transaction data into database
$sql="SELECT * FROM pendingoffs WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: No data for invoice #$ssl_invoice_number. (Date of invoice: ".date("m/d/y",$ssl_invoice_number).")";
   $attm=array("apps/app$ssl_invoice_number.html");
   exit();
}
else
{
   $sql2="UPDATE pendingoffs SET approved='yes' WHERE appid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
}

//UPDATE/INSERT INTO officials table the data from pendingoffs table for this official
$row=mysql_fetch_array($result);	//data from pendingoffs into $row
echo mysql_error();
if($row[offid]=="" || $row[offid]=='0')	//INSERT new entry
{
   $firstname=$row[first];
   $lastname=$row[last];   //might use for passcode generation below
   $sql1="SELECT * FROM officials WHERE appid='$ssl_invoice_number'";
   $result1=mysql_query($sql1);
   if(mysql_num_rows($result1)==0)
   {
      $sql2="INSERT INTO officials (appid,first,middle,last,address,city,state,zip,homeph,workph,cellph,email) VALUES ('$ssl_invoice_number','$row[first]','$row[middle]','$row[last]','$row[address]','$row[city]','$row[state]','$row[zip]','$row[homeph]','$row[workph]','$row[cellph]','$row[email]')";
   }
   else		//update entry (this will be used if they hit reload on approval page)
   {
      $sql2="UPDATE officials SET first='$row[first]',middle='$row[middle]',last='$row[last]',address='$row[address]',city='$row[city]',state='$row[state]',zip='$row[zip]',homeph='$row[homeph]',workph='$row[workph]',cellph='$row[cellph]',email='$row[email]' WHERE appid='$ssl_invoice_number'";
   }
   $result2=mysql_query($sql2);
   if(mysql_error()) 
   {
      echo "We're sorry.  An unexpected error occurred.  Please contact the NSAA office and reference your application ID: $ssl_invoice_number.";
      exit();
   }
   //get new offid
   $sql2="SELECT id FROM officials WHERE appid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $offid=$row2[0];
   $newoff=1;
}
else	//UPDATE entry
{
   $offid=$row[offid];
   $sql2="UPDATE officials SET appid='$ssl_invoice_number',first='$row[first]',middle='$row[middle]',last='$row[last]',address='$row[address]',city='$row[city]',state='$row[state]',zip='$row[zip]',homeph='$row[homeph]',workph='$row[workph]',cellph='$row[cellph]',email='$row[email]' WHERE id='$offid'";
   $result2=mysql_query($sql2);
   $newoff=0;
   $firstname=$row[first]; $lastname=$row[last];
}
$savedql=$sql2;

//LOGINS table: see if $offid has an entry
//If it does, get passcode OR if passcode is blank, generate passcode
//If it does NOT, generate passcode and add entry to logins table
$sql2="SELECT * FROM logins WHERE offid='$offid' AND offid!='0'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)	//official already has entry in logins table
{
   $row2=mysql_fetch_array($result2);
   $passcode=$row2[passcode];
   if($passcode=='' || $passcode=='0')
   {
      //generate new passcode for this official:
      $passcode=GeneratePasscode($lastname,1);
      //Update logins table
      $sql3="UPDATE logins SET passcode='$passcode' WHERE offid='$offid'";
      $result3=mysql_query($sql3);
   }
}
else	//Need NEW entry for this official in logins table
{
   //First, generate passcode, as done above
   $passcode=GeneratePasscode($lastname,1);
   //Then INSERT new entry into logins table
   $name=addslashes("$firstname $lastname");
   $sql2="INSERT INTO logins (name,level,passcode,offid) VALUES ('$name','2','$passcode','$offid')";
   $result2=mysql_query($sql2);
}
//Now $passcode has current passcode for this official
   
//record payment as "credit" in $db_name2.__off table 
$ccappsp=array("fb","vb","sb","bb","wr","sw","di","so","ba","tr");
$appdate=date("Y-m-d");
$year1=date("Y"); $mo=date("m");
$regyr=GetSchoolYear($year1,$mo);
for($i=0;$i<count($ccappsp);$i++)
{
   $field=$ccappsp[$i];
   $table=$ccappsp[$i]."off";
   $table2=$table."_hist";
   $field2=$field."contests";
   if($row[$field]=='x' || ($field=='tr' && $row[tr2]=='x'))
   {
      //put check in officials table
      $sql3="UPDATE officials SET $field='x' WHERE id='$offid'";
      $result3=mysql_query($sql3);

      //get current mailing number
      $sql2="SELECT affmailnum FROM mailing WHERE sport='$field'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $curmailnum=$row2[0];

      //__off table:
      $sql3="SELECT * FROM $table WHERE offid='$offid'";
      $result3=mysql_query($sql3);
      $today=time();
      if(mysql_num_rows($result3)>0)
         $sql2="UPDATE $table SET payment='credit',datepaid='$today',appid='$ssl_invoice_number',mailing='$curmailnum' WHERE offid='$offid'";
      else	//new off: INSERT
         $sql2="INSERT INTO $table (offid,payment,datepaid,appid,mailing) VALUES ('$offid','credit','$today','$ssl_invoice_number','$curmailnum')";
      $result2=mysql_query($sql2);

      //update __off_hist table
      $sql2="SELECT * FROM $table2 WHERE offid='$offid' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)   //INSERT
      {
         $sql3="INSERT INTO $table2 (offid,regyr,appdate";
         if($field=='tr') $sql3.=",position";
         $sql3.=") VALUES ('$offid','$regyr','$appdate'";
         if($field=='tr' && $row[tr]=='x') $sql3.=",'starter'";
         else if($field=='tr') $sql3.=",'referee'";
         $sql3.=")";
      }
      else      //UPDATE
      {
         $sql3="UPDATE $table2 SET appdate='$appdate'";
         if($field=='tr' && $row[tr]=='x') $sql3.=",position='starter'";
         else if($field=='tr') $sql3.=",position='referee'";
         $sql3.=" WHERE offid='$offid' AND regyr='$regyr'";
      }
      $result3=mysql_query($sql3);
      if($field=='tr')
      {
         $html=$sql3;
      }
   }
}

$appid=$ssl_invoice_number;
}//end if preview!=1
?>
<html>
<head>
   <title>NSAA | Affiliate Official's Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<table><tr align=center><td>
<table width=500>
<caption><b>Transaction Complete!<hr></b></caption>
<?php
echo "<tr align=left><th colspan=2>Please print this page for your records:<br><br></th></tr>";
$date=date("M d, Y",$ssl_invoice_number);
echo "<tr align=left><th class=smaller>Transaction #:</th><td>$appid</td></tr>";

$string="<table>";
$string.="<tr align=left><td><br>Billing Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><br>Billing Address:</td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><br>Credit Card Number:</td><td>$ssl_card_number</td></tr>";

$sql="SELECT * FROM officialsapp WHERE appid='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$html=$row[html]."$string</table></body></html>";
$html=addslashes($html);
$sql="UPDATE officialsapp SET html='$html' WHERE appid='$appid'";
$result=mysql_query($sql);

$showhtml=ereg_replace("<html><body>","",$html);
$showhtml=ereg_replace("</body></html>","",$showhtml);
echo "<tr align=center><td colspan=2>".$showhtml."</td></tr></table><br>";

//add cc info to file
/*
$open=fopen(citgf_fopen("apps/app$appid.html"),"a");
fwrite($open,"$string</table></body></html>");
fclose($open); 
 citgf_makepublic("apps/app$appid.html");
*/
$sql="SELECT * FROM officialsapp WHERE appid='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$html=addslashes($row[html]."$string</table></body></html>");
$sql="UPDATE officialsapp SET html='$html' WHERE appid='$appid'";
$result=mysql_query($sql);

//send e-mail to me with html file for this transaction
$From="nsaa@nsaahome.org";
$FromName="NSAA";
$To="run7soccer@aol.com";
$ToName="Ann Gaffigan";
$Subject="Affiliate Officials Application";
$Text="A new affiliate official's application is attached.\r\n\r\nThank You!";
$Html="A new affiliate official's application is attached.<br><br>Thank You!";
$Attm=array("apps/app$appid.html");
//SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
?>
<table width=500>
<tr align=left><td colspan=2><br><b><i>I have read the NSAA regulations governing officials.  I further understand and accept that officials are considered independent contractors and not employees of the Nebraska School Activities Association.</i></b></th></tr>
<tr align=center><th align=left colspan=2><br><i>Your passcode is</i>: <?php echo $passcode; ?><br>
You may login with this passcode at: <a class=small target=new href="index.php">https://secure.nsaahome.org/nsaaforms/officials</a></th></tr>
<tr align=left><td colspan=2><font style=\"color:red\"><b>PLEASE WRITE THIS PASSCODE DOWN IN A SAFE, EASY-TO-REMEMBER PLACE OR PRINT THIS SCREEN.  You will NOT be able to return to this screen to retrieve your passcode.</b></font></td></tr>
<tr align=left><td colspan=2>
The NSAA is providing an online service for affiliate officials.  The NSAA will no longer issue officials' cards.  Here are the instructions on how to access this area of the NSAA website.  Below are the steps to logging in:<br><ol>
<li>Go to the NSAA's web site:  <a class=small href="https://nsaahome.org">https://nsaahome.org</a></li>
<li>In the upper left hand corner, you will see "OFFICIALS LOGIN".  Click on it.</li>
<li>Type in the login passcode listed above on this screen.  This passcode will remain the same each year you register with the NSAA as an affiliate official.  Do not give your passcode out to other officials for any reason.</li>
<li>Click Login.  You are now at your home page.  You will see "Welcome, [Your Name]"  up at the top of the page.</li>
</ol><br>

<b><u>Sections of your home page:</u></b><br><br>

<b>Links & Contacts</b><br><ul>
<li>The Nebraska rules meeting schedule and supervised test schedules are listed here.</li>
<li>The power point presentations from the Nebraska rules meetings are available to view.</li>
<li>The Nebraska Officials Roster, which also includes affiliate officials, is posted here.</li>
</ul><br>

<b>Reminders</b><br><br>
This section is to alert officials to upcoming deadlines regarding open book tests and applications to officiate districts and state competition.  This section does not apply to all affiliate officials, but some affiliate officials do apply for sub district and district competition.
<br><br>
<b>Messages</b><br><br>
This section will have sport specific messages posted for officials to view regarding rule changes or specific situations that arise during a season.
<br><br>
<b>Downloads</b><br><br>
This section lists rule changes and points of emphasis as well as Nebraska specific rules that out of state officials should be aware of.
<br><br>
<b>Contact Information</b><br><br>
Changes in your address and telephone number as well as other contact information can be updated here.
<br><br>
<b>Contracts</b><br><br>
This section is where contracts for sub district, district and state tournaments are listed and can be either accepted or rejected by an official.  (Note:  An official must apply in order to be offered a contract and preference will be given to officials living in Nebraska.)
<br><br>
<b>Schedule Entry</b><br><br>
Please enter your Nebraska varsity officiating schedule.  (Nebraska varsity contests only.)
<br><br>
<b>Applications to Officiate</b><br><ul>
<li>Preference will be given to Nebraska officials for all post season sub district, district and state tournament contracts, but affiliate officials are welcome to apply for any of those tournaments by completing the form listed here.</li>
<li>In order to be to be considered for NSAA sub district, district or state tournaments, you MUST apply by submitting the application form online.</li>
<li>After logging in go to the applications section.  Select the sport you are applying for and click "GO".  Follow the instructions on the screen.  WHEN FINISHED REMEMBER TO CLICK THE "SAVE" BUTTON.</li>
</ul><br>
<b>Ejection Report</b><br><br>
If an athlete or coach is ejected from a contest in which you are officiating in Nebraska a report must be submitted online.  If you have any questions about the Nebraska sportsmanship rules please refer to the 2006-07 Officials Manual posted under the Officials tab on the <a href="https://nsaahome.org/officials-2/" target="_blank">NSAA website</a> or contact Nate Neuhaus by email at <a class=small href="mailto:nneuhaus@nsaahome.org">nneuhaus@nsaahome.org</a>.
</td></tr>
</table>
</td></tr></table>
</body>
</html>
