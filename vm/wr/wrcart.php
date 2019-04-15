<?php
/************************************************
wrcart.php
View Cart of WR Videos
Created 2/12/13
by Ann Gaffigan
*************************************************/
require '../functions.php';
require '../../functions_jw.php';
require '../variables.php';

if($_SERVER['HTTPS']!="on")
{
   $redirect= "https://nsaahome.org".$_SERVER['REQUEST_URI'];
   header("Location:$redirect");
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

session_set_cookie_params(2*24*60*60);
session_start();

$now=time();
if(!$_SESSION['sessionid'] || $_SESSION['expires']<$num)
{
   //GET RANDOM 12 CHARACTER STRING AS sessionid
   $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $randstring='';
   for($i=0;$i<12;$i++) 
   {
      $randstring.=$characters[rand(0, strlen($characters))];
   }
   $_SESSION['sessionid']=$randstring;
   $_SESSION['expires']=$num+7200;	//2 hour session

   $sql="INSERT INTO wrvideosessions (sessionid,sessionstart) VALUES ('".$_SESSION['sessionid']."','$now')";
   $result=mysql_query($sql);
}

for($i=0;$i<count($wrestlerfirst);$i++)
{
   $submitvar="removefromcart".$i;
   if($$submitvar=="Remove")       //REMOVE THIS WRESTLER FROM CART
   {
      $sql="DELETE FROM wrvideocarts WHERE sessionid='".$_SESSION['sessionid']."' AND wrestlerfirst='".addslashes($wrestlerfirst[$i])."' AND wrestlerlast='".addslashes($wrestlerlast[$i])."' AND wrestlerteam='".addslashes($wrestlerteam[$i])."'";
      $result=mysql_query($sql);
   }
}

echo GetMainHeader();
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

echo "<form method=post action='wrcart.php'>";
echo "<h1>Wrestling Videos: Your Cart</h1>";

$cartct=GetWRCartCount($_SESSION['sessionid']);
if($cartct>0)
{
   echo "<div class='alert'>There are videos for <b><u>$cartct</u></b> ";
   if($cartct==1) echo "wrestler";
   else echo "wrestlers";
   echo " in your cart. &nbsp;&nbsp;<a href=\"wrvideos.php\">Continue Browsing Videos</a></div>";
}
else	//NOTHING IN CART
{
   echo "<div class='alert'>You do not have anything in your cart. &nbsp;&nbsp;<a href=\"wrvideos.php\">Browse Videos</a></div>";
   echo GetMainFooter();
   exit();
}

$sql="SELECT * FROM wrvideocarts WHERE sessionid='".$_SESSION['sessionid']."' ORDER BY wrestlerteam,wrestlerlast,wrestlerfirst";
$result=mysql_query($sql);
if(mysql_error())
{
   echo "UNEXPECTED ERROR FOR QUERY: $sql<br>".mysql_error()."<br>";
}
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"color:#808080 1px solid;\">";
echo "<tr align=center><td>NAME</td><td>TEAM</td><td>AVAILABLE VIDEOS</td><td>PRICE</td><td>REMOVE<br>FROM CART</td></tr>";
$i=0; $totaldue=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>".$row[wrestlerlast].", ".$row[wrestlerfirst]."</td><td>".$row[wrestlerteam]."</td>";
      echo "<td>";
      //GET VIDEOS FOR THIS PERSON
      $sql2="SELECT DISTINCT filename,bouttype FROM wrvideos WHERE (redfirst='".addslashes($row[wrestlerfirst])."' AND redlast='".addslashes($row[wrestlerlast])."' AND redteam='".addslashes($row[wrestlerteam])."') OR (bluefirst='".addslashes($row[wrestlerfirst])."' AND bluelast='".addslashes($row[wrestlerlast])."' AND blueteam='".addslashes($row[wrestlerteam])."') ORDER BY division,bouttype,boutnumber";
      $result2=mysql_query($sql2);
      $curvids="";
      while($row2=mysql_fetch_array($result2))
      {
         $curvids.="$row2[bouttype]<br>";
      }
      if($curvids!='')
  	 $curvids=substr($curvids,0,strlen($curvids)-4);
      echo $curvids;
      echo "</td><td align=center>$20.00</td>";
	//HIDDEN VARIABLES and REMOVE FROM CART:
      echo "<td align=center>
	<input type=hidden name=\"wrestlerfirst[$i]\" value=\"".$row[wrestlerfirst]."\">
	<input type=hidden name=\"wrestlerlast[$i]\" value=\"".$row[wrestlerlast]."\">
	<input type=hidden name=\"wrestlerteam[$i]\" value=\"".$row[wrestlerteam]."\">
	<input type=submit name=\"removefromcart".$i."\" value=\"Remove\" onClick=\"return confirm('Are you sure you want to remove this wrestler\'s videos from your cart?');\">";
      echo "</td>";
   echo "</tr>";
   $i++;
   $totaldue+=20;
}
//TOTALS
echo "<tr bgcolor='#e0e0e0'><td align=right colspan=3><b>TOTAL DUE:</b></td><td align=center><b><u>$".number_format($totaldue,2,'.',',')."</u></b></td><td>&nbsp;</td></tr>";
echo "</table>";
echo "</form>";
//END CART FORM

//CREDIT CARD FORM/CHECK OUT:

   //PUT ENTRY IN wrvideotransactions for this session if not already there, create $appid
   $appid=time();
   $sql="INSERT INTO wrvideotransactions (appid,sessionid) VALUES ('$appid','".$_SESSION['sessionid']."')";
   $result=mysql_query($sql);

   /********CREDIT CARD FORM********/
   //$totaldue="0.05";
   echo "<form method=post action=\"$VirtualMerchantAction\">";
   echo "<input type=hidden name=\"ssl_test_code\" value=\"TRUE\">";
   echo "<input type=hidden name=\"ssl_merchant_id\" value=\"$VirtualMerchantID\">";
   echo "<input type=hidden name=\"ssl_user_id\" value=\"$VirtualMerchantUserID\">";
   echo "<input type=hidden name=\"ssl_pin\" value=\"$VirtualMerchantPIN\">";
   echo "<input type=hidden name=\"ssl_amount\" value=\"$totaldue\">";
   echo "<input type=hidden name=\"ssl_salestax\" value=\"0.00\">";
   echo "<input type=hidden name=\"ssl_show_form\" value=\"false\">";
   echo "<input type=hidden name=\"ssl_invoice_number\" value=\"$appid\">";
   echo "<input type=hidden name=\"ssl_customer_code\" value=\"".$_SESSION['sessionid']."\">";
   echo "<input type=hidden name=\"ssl_transaction_type\" value=\"ccsale\">";
   echo "<div class=\"normal\" style=\"width:500px;\">";
   echo "<h2>Please enter your Credit Card information below and click \"Submit\"</h2>";
   echo "<p>You will be given instructions for downloading your videos once payment has been completed.</p>";
   echo "<table cellspacing=0 cellpadding=5>";
   echo "<tr align=left valign=top><td><b>Cardholder Name:</b></td>";
   echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input type=text name=\"ssl_first_name\"></td>";
   echo "<td><input type=text name=\"ssl_last_name\"></td></tr>";
   echo "<tr align=left><td>&nbsp;[First]</td><td>&nbsp;[Last]</td></tr></table></td></tr>";
   echo "<tr align=left><td><b>E-mail Address:</b></td><td><input type=text name=\"ssl_email\" size=30></td></tr>";
   echo "<tr align=left valign=top><td><b>Billing Address:</b></td><td>";
   echo "<table><tr align=left><td>Street:</td><td><input type=text name=\"ssl_avs_address\" size=30></td></tr>";
   echo "<tr align=left><td>City, State:</td><td><input type=text name=\"ssl_city\" size=20>,&nbsp;<input type=text name=\"ssl_state\" size=3 maxlength=2>&nbsp;&nbsp;Zip:&nbsp;<input type=text name=\"ssl_avs_zip\" size=5></td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=left><td><b>Type of Card:</b></td><td><select name=\"cardtype\"><option>VISA<option>Mastercard<option>Discover</select></td></tr>";
   echo "<tr align=left><td><b>Credit Card Number:</b></td><td><input type=password name=\"ssl_card_number\" size=20></td></tr>";
   echo "<tr align=center><td colspan=2><div id=\"errordiv\" class=\"error\" style=\"width:300px;visibility:hidden;display:none\">Please correct the following fields in your form:<br><br>The <b>Expiration Date</b> must be of the format \"MMYY\".  For example, <b>December ".date("Y")."</b> would be entered as <b>\"12".date("y")."\"</b><br><br><img src='/okbutton.png' onclick=\"document.getElementById('errordiv').style.visibility='hidden';document.getElementById('errordiv').style.display='none';\"></div></td></tr>";
   echo "<tr align=left><td><b>Expiration Date (MMYY):</b></td><td><input type=text name=\"ssl_exp_date\" id=\"ssl_exp_date\" size=4> (Example: for December ".date("Y").", enter \"12".date('y')."\")</td></tr>";
   echo "<tr align=left><th align=left class=smaller>Card Security Code:</th><td><input type=text name=\"ssl_cvv2cvc2\" size=3>&nbsp;(3-digit number on back of card in signature strip)</td></tr>";
   echo "<tr align=left><td colspan=2>Your card will be charged for <b><u>$".number_format($totaldue,2,'.',',')."</u></b>.</td></tr>";
   echo "<input type=hidden name=\"ssl_cvv2cvc2_indicator\" value=\"1\">";
   echo "<input type=hidden name=\"ssl_result_format\" value=\"HTML\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_decl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/wr/decline.php\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_method\" value=\"REDG\">";
   echo "<input type=hidden name=\"ssl_receipt_apprvl_get_url\" value=\"https://secure.nsaahome.org/nsaaforms/wr/approval.php\">";
   echo "<tr align=center><td colspan=2><b>PLEASE ONLY CLICK THIS BUTTON ONCE!!<br><input type=button name=go onClick=\"if(ErrorCheck()) { submit(); } else { errordiv.style.display='block'; errordiv.style.visibility='visible'; }\" value=\"Submit\"><br>PLEASE ONLY CLICK THIS BUTTON ONCE!!</b></td></tr></table>";
   echo "</form>";
   echo "</div>";
   /********END CREDIT CARD FORM********/

echo GetMainFooter(0);
?>
