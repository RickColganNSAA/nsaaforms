<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($submit)
{
   $socsec=trim($socsec);
   $socsec=ereg_replace(" ","",$socsec);
   $socsec=ereg_replace("-","",$socsec);
   //$sql="SELECT * FROM officials WHERE socsec='$socsec' AND email='$email'";
   $sql="SELECT * FROM officials WHERE  email='$email'";
   $result=mysql_query($sql);
  // if(mysql_num_rows($result)==0 || ($socsec=="" || $email==""))
   if(mysql_num_rows($result)==0 || ( $email==""))
   {
      $error=1;
   }
   else
   {
         $error=0;
	 $row=mysql_fetch_array($result);
	 $From="nsaa@nsaahome.org"; $FromName="NSAA";
         $To=$row[email]; $ToName="$row[first] $row[last]";
	 $Subject="Your NSAA Password";
         $sql2="SELECT passcode FROM logins WHERE offid='$row[id]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $Html="Your password for the NSAA Official's Login is: <b>$row2[passcode]</b>.<br><br><a href='https://secure.nsaahome.org/nsaaforms/officials/'>Click Here to Log In</a>.<br><br>Thank You!";
	 $Text="Your password for the NSAA Official's Login is: $row2[passcode].\r\n\r\nGo Here to Login: https://secure.nsaahome.org/nsaaforms/officials/\r\n\r\nThank You!";
	 $Attm=array();
	 SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
	 $email=$row[email];
   }
}

require '../../functions.php';
echo GetMainHeader();
?>
		<!-- BEGIN MAIN TEXT -->
                
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="https://secure.nsaahome.org/nsaaforms/officials/forgotpass.php">
<table>
<tr align=left>
<th align=left colspan=2><font style="font-family:arial">NSAA Officials Password Retrieval:</font></th>
</tr>
<tr align=left>
<td colspan=2 width=300>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>We're sorry, but the social security number and the e-mail address you entered do not match our records.  Please try again.  If you continue to have problems, please contact the NSAA.</b></font><br><br>";
}
?>
</td>
</tr>
<?php 
if(!$submit || $error==1)
{
   echo "<tr align=left><td colspan=2 width=300><font style=\"font-family:arial;font-size:10pt\">Please enter the following information and click \"Submit\".  If the information you enter matches the information in our database, we will send you an e-mail with your password.  If the information does not match our records, you will see an error on this screen.</font></td></tr>";
   echo "<tr align=left valign=top><th align=left><font style=\"font-family:arial; font-size:10pt;\">I am an:</font></th>";
   echo "<td><font style=\"font-family:arial; font-size:9pt;\">OFFICIAL<br><i>(This feature is not available for observers.<br>Please contact the NSAA.)</font></td>";
   echo "</tr>";
?>

<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">My E-mail Address is:</font></th>
<td><input type=text name="email" size=30></td>
</tr>
<tr align=left>
<th colspan=2 align=left><input type=submit name=submit value="Submit"></th>
</tr>
<?php
}
else	//submitted and password found
{
   echo "<tr align=left><td colspan=2><font style=\"font-family:arial;font-size:10pt\">Your password has been e-mailed to $email.</td></tr>";
}
?>
</table>
</form>
</td></tr>
</table>

<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();
?>
