<?php
require "../functions_jw.php";
require "variables.php";

redirectToHTTPS();
echo GetMainHeader();
?>
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="/nsaaforms/login.php">
<br>
<table>
<tr align=left>
<td colspan=2>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>You have entered your password incorrectly.<br><br>COACHES - If you have forgotten your password, please contact your Athletic Director.<br>ATHLETIC DIRECTORS and OTHERS - If you have forgotten your password, contact the NSAA.</b></font><br><br>";
}
if($error==2)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>The reCAPTCHA wasn't entered correctly. Try it again.</b></font><br><br>";
}
?>
<font style="font-family:arial; font-size:10pt">Please select your school (or college or ESU) from the<br>dropdown menu, and then enter your passcode.  Thank you!</font>
</td>
</tr>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">School:</font></th>
<td align=left><select name=school>
<option>Choose your School
<option value="All">NSAA
<?php
//connect to db
$db=db_connect("$db_host","$db_user","$db_pass");
db_select_db("$db_name",$db);
  $sql="SELECT DISTINCT school FROM logins WHERE level='5' ORDER BY school";
  $result=db_query($sql);
  $ix=0;
  while($row=db_fetch_array($result))
  {
      echo "<option";
      if($school==$row[0]) echo " selected";
      echo ">$row[0]";
  }

  $sql="SELECT school FROM headers ORDER BY school";
  $result=db_query($sql);
  $ix=0;
  while($row=db_fetch_array($result))
  {
      echo "<option";
      if($school==$row[0]) echo " selected";
      echo ">$row[0]";
  }
?>
</select>
</td>
</tr>
<?php
   echo "<tr align=left><th align=left><font style=\"font-family:arial; font-size:10pt\">OR College:</font></th>";
   echo "<td align=left><select name=college>";
   echo "<option value=''>Choose your College</option>";
   $sql="SELECT DISTINCT school FROM logins WHERE level='4' ORDER BY school";
   $result=db_query($sql);
   while($row=db_fetch_array($result))
   {
      echo "<option>$row[0]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><th align=left><font style=\"font-family:arial; font-size:10pt\">OR ESU:</font></th>";
   echo "<td align=left><select name=esu>";
   echO "<option value=''>Choose your ESU</option>";
   $sql="SELECT DISTINCT school FROM logins WHERE level='6' ORDER BY school";
   $result=db_query($sql);
   while($row=db_fetch_array($result))
   {
      echo "<option>$row[0]</option>";
   }
   echo "</select></td></tr>";
?>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">Passcode:</font></th>
<th align=left><input type=password name="passcode" size=15></th>
</tr>

<input id="token" type="hidden" name="g-recaptcha-response">
<tr align=center>
<th colspan=2><input type=submit name=login value="Login"></th>
</tr>
</table>
</form>
</td></tr>
</table>
<script src='https://www.google.com/recaptcha/api.js?render=6Ldt9YUUAAAAAHd3StCCS19_j5UQph31HDHHHtUW'></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('6Ldt9YUUAAAAAHd3StCCS19_j5UQph31HDHHHtUW', {action: 'action_name'})
            .then(function (token) {
// Verify the token on the server.
                var t = document.getElementById("token");
                t.value = token;
            });
    });
</script>
<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();  
?>
