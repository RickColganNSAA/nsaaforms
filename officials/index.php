<?php
require $_SERVER['DOCUMENT_ROOT'].'/functions_jw.php';
echo GetMainHeader();
?>
		<!-- BEGIN MAIN TEXT -->
                
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="/nsaaforms/officials/login.php">
<table>
<tr align=left>
<th align=left colspan=2><font style="font-family:arial">NSAA Officials & Observers Login:</font></th>
</tr>
<tr align=left>
<td colspan=2>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;font-size:9pt;\"><b>You have entered you password incorrectly.<br>Please make sure your capslock is not on.<br>If you have forgotten your password, <a href=\"forgotpass.php\"><font style=\"font-family:arial;font-size:9pt;\"><b>Click Here (Officials)</b></font></a> or contact the NSAA (Observers).</b></font><br><br>";
}
if($error==2)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>The reCAPTCHA wasn't entered correctly. Try it again.</b></font><br><br>";
}
?>
<font style="font-family:arial; font-size:9pt">
<!--Insert Note Here-->
</font>
</td>
</tr>
<?php 
echo "<tr align=left><th align=left><font style=\"font-family:arial; font-size:10pt;\">I am an:</font></th>";
echo "<th align=left><select name=level><option value='2'>Official</option>";
echo "<option value='3'>Observer</option><option value='1'>NSAA Admin User</option></select></th></tr>";
?>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">Passcode:</font></th>
<th align=left><input type=password name="passcode" size=15></th>
</tr>
<tr align=left><td colspan=2>
<a href="forgotpass.php"><font style="font-family:arial;font-size:9pt;"><b>Forgot your password? Click Here. (Officials Only)</font></a></b>
</td></tr>

<tr align=left>
<th colspan=2 align=left><input type=submit name=submit value="Login"></th>
</tr>
</table>
    <input id="token" type="hidden" name="g-recaptcha-response">
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
