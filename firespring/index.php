<?php
require "../../functions.php";
require "../functions.php";
require "../variables.php";

//connect to db
$db=db_connect("$db_host","$db_user","$db_pass");
db_select_db("$db_name",$db);


if($login)
{
   $sql="SELECT * FROM logins WHERE name='$userid' AND passcode='$password' AND level='9'";
   $result=db_query($sql);
   if(db_num_rows($result)==0)	//LOGIN ERROR
   {
      $error=1;
   }
   else
   {
      $secret = "6Lfr-TgUAAAAACmup1PKdBSXT6hOPBWpaRb7udCS";

		if(isset($_POST['g-recaptcha-response'])){
			$captcha=$_POST['g-recaptcha-response'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$response=file_get_contents("http://google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
		}

		 if(!$captcha){
		  header("Location:index.php?error=2");
		  exit();
		} 
		if($response.success!=false)
		{
	  $session_id=time();
      $row=db_fetch_array($result);
      $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
      $result2=db_query($sql2);
      while($row2=db_fetch_array($result2))
      {
         $session_id++;
         $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
         $result2=db_query($sql2);
      }
      $sql="INSERT INTO sessions (session_id, login_id) VALUES ('$session_id', '$row[id]')";
      $result=db_query($sql);
      header("Location: index.php?session=$session_id");
      exit();
	  }
   }
}

if(ValidUser($session))
{
   echo $init_html;
   echo GetHeader($session);

   echo "<div style=\"width:600px;\">";
   echo "<br><h2>Firespring Main Menu for NSAA State Programs</h2>";
   //FALL
   echo "<h3 style=\"text-align:left;\">Fall Activities:</h3><ul>";
   //echo "<li><a href=\"../sb/programadmin.php?session=$session\">Softball Programs</a></li>";
   echo "<li><a href=\"../sb/sbstateadmin1.php?session=$session\">Softball Programs</a></li>";
   //echo "<li><a href=\"../vb/programadmin.php?session=$session\">Volleyball Programs</a></li>";
   echo "<li><a href=\"../vb/vbstateadmin1.php?session=$session\">Volleyball Programs</a></li>";
   echo "<li><a href=\"../fb/programadmin.php?session=$session\">Football Programs</a></li>";
   echo "<li><a href=\"../pp/programadmin.php?session=$session\">Play Production Programs</a></li>";
   echo "</ul>";
   //WINTER
   echo "<h3 style=\"text-align:left;\">Winter Activities:</h3><ul>";
   echo "<li><a href=\"../bb/programadmin.php?session=$session&sport=bbb\">Boys Basketball Programs</a></li>";
   echo "<li><a href=\"../bb/programadmin.php?session=$session&sport=bbg\">Girls Basketball Programs</a></li>";
   echo "<li><a href=\"../wr/programadmin.php?session=$session\">Wrestling Programs</a></li>";
   echo "</ul>";
   //SPRING
   echo "<h3 style=\"text-align:left;\">Spring Activities:</h3><ul>";
   echo "<li><a href=\"../ba/programadmin.php?session=$session\">Baseball Programs</a></li>";
   
   echo "<li><a href=\"../so/programadmin.php?session=$session&sport=sob\">Boys Soccer Programs </a></li>";
   
   echo "<li><a href=\"../so/programadmin.php?session=$session&sport=sog\">Girls Soccer Programs </a></li>";
   echo "</ul>";

   echo "</div>";

   echo $end_html; 
}
else
{
echo GetMainHeader();
?>
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="https://secure.nsaahome.org/nsaaforms/firespring/index.php">
<br>
<table cellspacing=4 cellpadding=4>
<tr align=left><td colspan=2><font style="font-size:10pt;"><b>Firespring Print & Marketing Login</b></font></td></tr>
<tr align=left>
<td colspan=2>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>You have entered your ID and/or password incorrectly.<br>Please make sure your capslock is not on.<br>If you have forgotten your password, contact the NSAA.</b></font><br><br>";
}
else if($error==3)
{
   echo "<font style=\"color:red;font-family:arial;\" size=2><b>You have been logged out of the system.  Please login again below.</b></font><br><br>";
}
if($error==2)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>The reCAPTCHA wasn't entered correctly. Try it again.</b></font><br><br>";
}
?>
</td>
</tr>
	<tr align=left><td>User ID:</td><td><input type=text name="userid" id="userid" size=20></td></tr>
	<tr align=left><td>Password:</td><td><input type=password name="password" id="password" size=20></td></tr>
	<tr align=left><th colspan=2><br><div class='g-recaptcha' data-sitekey="6Lfr-TgUAAAAABWSSVRENlGBaiUQGtaGNYnajAgo" data-callback="enableBtn"></div><br>
	<tr align=center>
	<th colspan=2><input type=submit name=login value="Login"></th>
	</tr>
</table>
</form>
</td></tr>
</table>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();
}
?>
