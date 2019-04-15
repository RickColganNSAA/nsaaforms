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
   $sql="SELECT * FROM judges WHERE socsec='$socsec'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0 || trim($socsec)=="")
   {
      $error=1;
   }
   else
   {
         $error=0;
	 $row=mysql_fetch_array($result);
	 $From="nsaa@nsaahome.org"; $FromName="NSAA";
         $To=$row[email]; $ToName="$row[first] $row[last]";
	 $Subject="NSAA Judge's Password Retrieval";
         $sql2="SELECT passcode FROM logins_j WHERE offid='$row[id]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $Html="Your password for the NSAA Judge's Login is: <b>$row2[passcode]</b>.<br><br>Thank You!";
	 $Text="Your password for the NSAA Judge's Login is: $row2[passcode].\r\n\r\nThank You!";
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
<form method="post" action="https://secure.nsaahome.org/nsaaforms/officials/jforgotpass.php">
<table>
<tr align=left>
<th align=left colspan=2><font style="font-family:arial">NSAA Judges Password Retrieval:</font></th>
</tr>
<tr align=left>
<td colspan=2 width=300>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>We're sorry, but the social security number you entered does not match our records.  Please try again.  If you continue to have problems, please contact the NSAA.</b></font><br><br>";
}
?>
</td>
</tr>
<?php 
if(!$submit || $error==1)
{
   echo "<tr align=left><td colspan=2 width=300><font style=\"font-family:arial;font-size:10pt\">Please enter your social security number and click \"Submit\".  If the information you enter matches the information in our database, we will send you an e-mail with your password.  If the information does not match our records, you will see an error on this screen.</font></td></tr>";
?>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">My Social Security Number is:</font></th>
<td><input type=text name="socsec" size=15></td>
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
