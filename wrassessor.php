<?php
require "../functions.php";
require "variables.php";

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$states=array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WV","WA","WI","WY","DC");
$titles=array("Certified Athletic Trainer","Exercise Physiologist","Health Educator","Licensed Practical Nurse","Nutritionist","Physical Therapist","Physician","Physician's Assistant","Registered Nurse");

if($login && $isassessor=="yes")
{
   $sql="SELECT * FROM wrassessors WHERE userid='$userid' AND password='$password'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//LOGIN ERROR
      $error=1;
   else
   {
      	     		$secret = "6Le7-DgUAAAAAO_YjztUnPL-o-8U9WZIN3-QEeot";
		if(isset($_POST['g-recaptcha-response'])){
			$captcha=$_POST['g-recaptcha-response'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$response=file_get_contents("http://google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
		}
		if(!$captcha){
		 $error=4;
 		  header("Location:wrassessor.php?error=4");
		  exit();  
		 
		}
		if($response.success!=false)
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
}
else if($isassessor=="no" && ($hiddencreate || $create))	//CREATE NEW ACCOUNT
{
   //ERROR CHECK: USER ID ALREADY IN USE?
   $sql="SELECT * FROM wrassessors WHERE userid='$userid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $useriderror=1;
   }
   else
   {
      $first=addslashes($first); $last=addslashes($last);
      $address=ereg_replace("\"","'",$address);
      $address=addslashes($address); $city=addslashes($city);
      $hphone=$harea."-".$hpre."-".$hpost;
      $wphone=$warea."-".$wpre."-".$wpost."-".$wext;
      $cphone=$carea."-".$cpre."-".$cpost;
      if($title=="Other" && $titleother!='') $title=$titleother;
      $title=addslashes($title);
      $sql="INSERT INTO wrassessors (datecreated,first,last,userid,password,email,hphone,wphone,cphone,bestphone,address,city,state,zip,title) VALUES ('".time()."','$first','$last','$userid','$password','$email','$hphone','$wphone','$cphone','$bestphone','$address','$city','$state','$zip','$title')";
      $result=mysql_query($sql);
      if(mysql_error()) $createerror=mysql_error();
      else $createerror="";
      $newid=mysql_insert_id();

      if($createerror=="")	//SUCCESSFULLY CREATED THE ACCOUNT - LOG THEM IN
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
	 //NOW WE HAVE A UNIQUE $session
         $sql="UPDATE wrassessors SET session='$session' WHERE id='$newid'";
         $result=mysql_query($sql);
         header("Location:wrassessor/updateinfo.php?session=$session&createdaccount=1");
         exit();
      }  
   }
}

echo GetMainHeader();
?>
<script language="javascript">
function ErrorCheck()
{
   var errors="";
   if(document.getElementById('first').value=="" || document.getElementById('last').value=="")
   {
      errors+="<tr align=left><td><b>NAME:</b> You must enter the first AND last name.</td></tr>";
      document.getElementById('nameerror').style.display='';
   }
   else
      document.getElementById('nameerror').style.display='none';
   if(document.getElementById('email').value=="")
   {     
      errors+="<tr align=left><td><b>E-MAIL:</b> You must enter the e-mail address.</td></tr>";
      document.getElementById('emailerror').style.display='';
   }  
   else  
      document.getElementById('emailerror').style.display='none';
   if(document.getElementById('userid').value=="")
   {    
      errors+="<tr align=left><td><b>USER ID:</b> You must enter a USER ID.</td></tr>";
      document.getElementById('useriderror').style.display='';
   } 
   else  
      document.getElementById('useriderror').style.display='none';
   if(document.getElementById('password').value=="")
   {
      errors+="<tr align=left><td><b>PASSWORD:</b> You must enter a PASSWORD.</td></tr>";
      document.getElementById('passworderror').style.display='';
   } 
   else if(document.getElementById('password').value.length<8)
   {
      errors+="<tr align=left><td><b>PASSWORD:</b> Your password must be at LEAST 6 CHARACTERS.</td></tr>";
      document.getElementById('passworderror').style.display='';
   }
   else  
      document.getElementById('passworderror').style.display='none';

   if(errors!="")
   {
      document.getElementById('errordiv').style.display="";
      document.getElementById('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in the form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"document.getElementById('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      document.getElementById('hiddencreate').value="Save";
      document.forms.infoform.submit();
   }
}
</script>
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="https://secure.nsaahome.org/nsaaforms/wrassessor.php" name="infoform">
<br>
<table cellspacing=4 cellpadding=4 style="width:700px;">
<tr align=left><td colspan=2><h1>Wrestling Assessor Annual Registration</h1></td></tr>
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
if($error==4)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>The reCAPTCHA wasn't entered correctly. Try it again.</b></font><br><br>";
}
?>
</td>
</tr>
<?php
$sql="SELECT * FROM misc_duedates WHERE sport='wrassessor' AND duedate>=CURDATE()";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0) //PAST DUE
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
<tr align=left><td colspan=2><b><i>Do you already have a Wrestling Assessor account with the NSAA & Track Wrestling?</b></i>&nbsp;
	<input type=radio name="isassessor" value="yes" <?php if($isassessor=="yes") echo " checked"; ?> onClick="submit();"> YES&nbsp;&nbsp;<input type=radio name="isassessor" value="no" <?php if($isassessor=="no") echo " checked"; ?> onClick="submit();"> NO
</td></tr>
<tr align=left><td colspan=2><p><i>NOTE: If you have an account but cannot remember your user ID and password, please email Ron Higdon at <a href="mailto:rhigdon@nsaahome.org">rhigdon@nsaahome.org</a></i></p>
<?php if($isassessor): ?>
<?php if($isassessor=="yes"):	//ALREADY HAS A USERID & PASSWORD ?>
	<tr align=left><td>NSAA & Track Wrestling Assessor ID:</td><td><input type=text name="userid" id="userid" size=20></td></tr>
	<tr align=left><td>NSAA & Track Wrestling Assessor Password:</td><td><input type=password name="password" id="password" size=20></td></tr>
	<tr align=center><th colspan=2><br><div class='g-recaptcha' data-sitekey="6Lfr-TgUAAAAABWSSVRENlGBaiUQGtaGNYnajAgo" data-callback="enableBtn"></div><br>
	<tr align=center>
	<th colspan=2><input type=submit name=login value="Login"></th>
	</tr>
<?php else: 	//NEEDS AN ACCOUNT: ?>
	<tr align=center><td colspan=2>
	<!--BEGIN CREATE ACCOUNT-->
	<div style="border:#808080 1px solid;margin:10px;padding:10px;">
	<table cellspacing=3 cellpadding=3><caption><h1>Create a Wrestling Assessor Account:</h1>
<?php
if($useriderror==1)
   echo "<div class=error>The userid \"$userid\" is already in use.</div>";
else if($createerror!='')
   echo "<div class=\"error\">UNEXPECTED ERROR: $createerror.<br><br>Please send a screenshot of this error to the programmer.</div>";
else
   echo "<i>Please complete the form below and click \"Create Account\" below.</i>";
?>
	</caption>
<!--NAME-->
	<tr align=left><td><b>Full Name (First, Last):</b><div id='nameerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value="<?php echo $first; ?>" type=text name="first" id="first" size=15>&nbsp;<input type=text name="last" id="last" value="<?php echo $last; ?>" size=25></td></tr>
<!--USER ID & PASSWORD-->
	<tr align=left><td><b>User ID:</b><div id='useriderror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value="<?php echo $userid; ?>" type=text name="userid" id="userid" size=15> <i>Choose a User ID you can easily remember.</i></td></tr>
	<tr align=left><td><b>Password:</b><div id='passworderror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value="<?php echo $password; ?>" type="password" name="password" id="password" size=15> <i>Your password must be at least 8 characters long.</i></td></tr>
   	<tr align=left><td colspan=2><b><i>(This User ID and Password will be used to login to the NSAA website AND Track Wrestling.)</i></b></td></tr>
<!--E-mail Address-->
	<tr align=left><td><b>Email Address:</b><div id='emailerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td><input value="<?php echo $email; ?>" type=text name="email" id="email" size=40></td></tr>
<!--Phone-->
	<tr valign=top align=left><td><b>Phone Number(s):</b><div id='phoneerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>
	<p>Home: (<input value="<?php echo $harea; ?>" type=text name="harea" id="harea" size=4 maxlength=3>)<input value="<?php echo $hpre; ?>" type=text name="hpre" id="hpre" size=4 maxlength=3>-<input value="<?php echo $hpost; ?>" type=text name="hpost" id="hpost" size=5 maxlength=4>&nbsp;<input type=radio name="bestphone" value="hphone"<?php if($bestphone=="hphone"): ?> checked<?php endif; ?>> Best phone number</p>
	<p>Work: (<input value="<?php echo $warea; ?>" type=text name="warea" id="warea" size=4 maxlength=3>)<input value="<?php echo $wpre; ?>" type=text name="wpre" id="wpre" size=4 maxlength=3>-<input value="<?php echo $wpost; ?>" type=text name="wpost" id="wpost" size=5 maxlength=4>&nbsp;ext.<input value="<?php echo $wext; ?>" type=text name="wext" id="wext" size=4>&nbsp;<input type=radio name="bestphone" value="wphone"<?php if($bestphone=="wphone"): ?> checked<?php endif; ?>> Best phone number</p>
	<p>Cell: (<input value="<?php echo $carea; ?>" type=text name="carea" id="carea" size=4 maxlength=3>)<input value="<?php echo $cpre; ?>" type=text name="cpre" id="cpre" size=4 maxlength=3>-<input value="<?php echo $cpost; ?>" type=text name="cpost" id="cpost" size=5 maxlength=4>&nbsp;<input type=radio name="bestphone" value="cphone"<?php if($bestphone=="cphone"): ?> checked<?php endif; ?>> Best phone number</p>
	</td></tr>
<!--Mailing Address-->
	<tr align=left><td colspan=2><b>Mailing Address:</b><div id='addresserror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td></tr>
	<tr align=left><td align=right><b>Address:</b></td><td><input value="<?php echo $address; ?>" type=text name="address" id="address" size=30></td></tr>
	<tr align=left><td align=right><b>City, State Zip:</b></td><td><input value="<?php echo $city; ?>" type=text name="city" id="city" size=20>, <select name="state" id="state"><option value=''>~</option>
<?php
for($i=0;$i<count($states);$i++)
{
   echo "<option value=\"$states[$i]\"";
   if($state==$states[$i]) echo " selected";
   echo ">$states[$i]</option>";
}
?>
	</select>&nbsp;&nbsp;<input value="<?php echo $zip; ?>" type=text size=6 maxlength=5 name=zip id=zip></td></tr>
<!--ERROR DIV (HIDDEN UNLESS ERROR)-->
	<tr align=center><td colspan=2><div id="errordiv" class="searchresults" style="left:45%px;width:400px;display:none;"></div></td></tr>
<!--What area qualifies them-->
	<tr align=left><td colspan=2><b><br>Check the area below that qualifies you to be a registered assessor:</b></td></tr>
	<tr align=left valign=top><td><div id='titleerror' style='color:#ff0000;display:none;'><b>&nbsp;(!)</b></div></td><td>
<?php
for($i=0;$i<count($titles);$i++)
{
   echo "<input type=radio name=\"title\" id=\"title".$i."\" value=\"$titles[$i]\"";
   if($title==$titles[$i]) echo " checked";
   echo "> $titles[$i]<br>";
}
echo "<input type=radio name=\"title\" id=\"title".$i."\" value=\"Other\"";
if($title=="Other") echo " checked";
echo "> Other (please specify: <input type=text size=25 name=\"titleother\" value=\"$titleother\">)</p>";
?>
	</td></tr><tr align=center><td colspan=2><input class='fancybutton' type=button name="create" id="create" value="Create Account" onClick="ErrorCheck();"><input type=hidden name='hiddencreate' id='hiddencreate'></td></tr>
	</table>
	</div>
	<!--END CREATE ACCOUNT-->
	</td>
<?php endif; ?>
<?php endif; ?>
</table>
</td></tr>
</table>
</form>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();
}//END IF NOT PAST DUE (wrassessor due date)
?>
