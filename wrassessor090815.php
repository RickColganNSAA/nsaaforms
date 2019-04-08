<?php
require "../functions.php";
require "variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($login && $isassessor=="yes")
{
   $sql="SELECT * FROM wrassessors WHERE userid='$userid' AND password='$password'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//LOGIN ERROR
      $error=1;
   else
   {
      $session=time();
      $sql2="SELECT * FROM wrassessors WHERE session='$session'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $session++;
         $sql2="SELECT * FROM wrassessors WHERE session='$session'";
         $result2=mysql_query($sql2);
      }
      $row=mysql_fetch_array($result);
      $sql="UPDATE wrassessors SET session='$session' WHERE id='$row[id]'";
      $result=mysql_query($sql);
      header("Location:wrassessor/index.php?session=$session");
      exit();
   }
}
else if($login && $isassessor=="no")
{
   if($password1!=$password2 || trim($email)=="" || !ereg("@",$email))
   {
      $error=2;
   }
   else
   {
      $session=time();
      $sql2="SELECT * FROM wrassessors WHERE session='$session'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $session++;
         $sql2="SELECT * FROM wrassessors WHERE session='$session'";
         $result2=mysql_query($sql2);
      }  
      $sql="INSERT INTO wrassessors (email,password,session) VALUES ('$email','$password1','$session')";
      $result=mysql_query($sql);
      header("Location:wrassessor/index.php?session=$session");
      exit();
   }
}

echo GetMainHeader();
?>
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="http://nsaahome.criticalitgroup.com/nsaaforms/wrassessor.php">
<input type=hidden name="secret" value="<?php echo $secret; ?>">
<br>
<table cellspacing=4 cellpadding=4>
<tr align=left><td colspan=2><font style="font-size:10pt;"><b>Wrestling Assessor Annual Registration</b></font></td></tr>
<tr align=left>
<td colspan=2>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>You have entered your ID and/or password incorrectly.<br>Please make sure your capslock is not on.<br>If you have forgotten your password, contact the NSAA.</b></font><br><br>";
}
else if($error==2)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>You have either not entered a valid email address OR you did not enter the temporary password correctly.<br>Please make sure your capslock is not on.</b></font><br><br>";
}
else if($error==3)
{
   echo "<font style=\"color:red;font-family:arial;\" size=2><b>You have been logged out of the system.  Please login again below.</b></font><br><br>";
}
?>
</td>
</tr>
<?php
$sql="SELECT * FROM misc_duedates WHERE sport='wrassessor' AND duedate>=CURDATE()";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $secret!=1) //PAST DUE
{
?>
<tr align=left><td colspan=2><b>Please check back at a later date to login to your Wrestling Assessor Account.</b></td></tr>
</table></td></tr></table>
<?php
echo GetMainFooter();
exit();
}
else
{
?>
<tr align=left><td colspan=2><b><i>Do you have an NSAA & NWCA Assessor ID & Password?</b></i>&nbsp;
	<input type=radio name="isassessor" value="yes" <?php if($isassessor=="yes") echo " checked"; ?> onClick="submit();"> YES&nbsp;&nbsp;<input type=radio name="isassessor" value="no" <?php if($isassessor=="no") echo " checked"; ?> onClick="submit();"> NO
</td></tr>
<?php if($isassessor): ?>
<?php if($isassessor=="yes"):	//ALREADY HAS A USERID & PASSWORD ?>
	<tr align=left><td>NSAA & NWCA Assessor ID:</td><td><input type=text name="userid" id="userid" size=20></td></tr>
	<tr align=left><td>NSAA & NWCA Assessor Password:</td><td><input type=password name="password" id="password" size=20></td></tr>
	<tr align=center>
	<th colspan=2><input type=submit name=login value="Login"></th>
	</tr>
<?php else: 	//NEEDS AN ACCOUNT: ?>
	<tr align=left><td colspan=2><i>Please contact Ron Higdon at <a href="mailto:rhigdon@nsaahome.criticalitgroup.com">rhigdon@nsaahome.criticalitgroup.com</a> to receive your Assessor ID & Password.</i></td></tr>
	<!--<tr align=left><td>Please enter your email address:</td><td><input type=text name="email" id="email" size=40></td></tr>-->
<?php
	//$temppass="nwca".rand(1000,9999);
?>
	<!--<tr align=left><td colspan=2>Your temporary password is <b><?php echo $temppass; ?></b>.  Please enter it below.</td></tr>
	<tr align=left><td>Please enter your temporary password:</td><td><input type=hidden name="password1" id="password1" value="<?php echo $temppass; ?>"><input type=text name="password2" id="password2" size=20></td></tr>	-->
<?php endif; ?>
<?php endif; ?>
</table>
</form>
</td></tr>
</table>

<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();
}//END IF NOT PAST DUE (wrassessor due date)
?>
