<?php
//changepassword.php: allows user to change their password 
//   The password must be at least 6 chars and must be a
//   combo of numbers and letters

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}
$level=GetLevel($session);

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db("$db_name2",$db);

$header=GetHeader($session);
echo $init_html;
echo $header; 

//$school=GetSchool($session);
//$school2=ereg_replace("\'","\'",$school);

//if new password entered, store it:
if($submit=="Save Password")
{
   $match_error=0;
   $error=0;
   //check that passwords match
   if($new_password!=$confirm_password)
   {
      $match_error=1;
   }
   else //check that password is valid
   {
      $new_password=trim($new_password);
      $confirm_password=trim($confirm_password);
/*       if(strlen($new_password)<8 || ereg("[^a-zA-Z0-9]",$new_password))
      {
	 $error=1;
      } */
	  	$containsLetter  = preg_match('/[a-zA-Z]/',    $confirm_password);
		$containsDigit   = preg_match('/\d/',          $confirm_password);
		
      if(strlen($new_password)<8 || $containsLetter==false or $containsDigit==false )
      {
			$error=1;
      }
   }
   if($error==1 || $match_error==1)	//invalid password
   {
      $errormsg="<div class='error'>";
      if($match_error==1)
      {
	 $errormsg.="Your password and the confirmation of your password did not match.";
      }
      else if($error==1)
      {
	 $errormsg.="Your password must be at least 8 characters long and must include a combination of letters and numbers only.";
      }
      $errormsg.="</div>";
   }
   else			//store new password
   {
      //make sure password is not already in use
      //$sql="SELECT * FROM logins WHERE passcode='$new_password' AND school='$school2'";
      $sql="SELECT * FROM logins_j WHERE passcode='$new_password' ";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)	//passcode in use
      {
         $errormsg="<div class='error'>";
	     $errormsg.="You have chosen an invalid password.<br>Please choose a different password.";
      }
      else
      {  
         $sql="SELECT t2.id FROM sessions AS t1, logins_j AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id"; 
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $id=$row[0];
          $sql="UPDATE logins_j SET passcode='$new_password',changepass='".time()."' WHERE id='$id'"; 
         $result=mysql_query($sql);
         echo "<table width='100%'><tr align=center><td><br><br><div class='normalwhite' style='padding:10px;width:300px;'>";
         echo "<p>Your password has been changed. We HIGHLY RECOMMEND that you do not give this password out unless absolutely necessary.</p>";
/* 	 if($level==2)
	    echo "<p>We strongly encourage you to ask your coaches to change their password at least once a year. If a new coach is hired, you can assign him or her a new password in your <a class=small href=\"directory.php?session=$session\">School Directory</a>. (Please make sure to click \"Save\" at the bottom of the School Directory after making any changes.)</p>";
         else
            echo "<p>We also recommend that you change your password at least once a year.</p>"; */
         echo "<p>Thank you!</p></div><br><br>";
         echo "<a href=\"jwelcome.php?session=$session\">Return Home</a>";
         echo "</td></tr></table></body></html>";
         exit();
      }
   }
}
?>

<table width='100%'><tr align=center><td>
<br>
<form method=post action="jchangepassword.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<table width='300px'>
<caption><b>Change your Password:</b>
<?php if($errormsg!=''): ?>
<?php echo $errormsg; ?>
<?php endif; ?>
</caption>
<tr align=left><td colspan=2>
  Your new password must be at least 8 characters in length<br>and must
  contain both letters <i>and</i> numbers.<br><br>
<?php if($level==2): ?>
  If you are both the AD and a coach, make sure you choose different passcodes for each account.
<?php else: ?>
  If you are the coach for more than one activity or if you
  are both the AD and a coach, make sure you choose different
  passcodes for each activity or job.
<?php endif; ?>
</td>
</tr>
<tr align=left>
<th>New Password:</th>
<td><input type=password name=new_password size=20></td>
</tr>
<tr align=left>
<th>Confirm Password:</th>
<td><input type=password name=confirm_password size=20></td>
<td><input type=hidden name=session value="<?php echo $session;?>"></td>
</tr>
<tr align=center>
<td colspan=2><br>
<input type=submit name=submit value="Save Password">
</td>
</tr>
</table>
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
