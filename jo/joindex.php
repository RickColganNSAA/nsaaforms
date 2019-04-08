<?php
/*******************************************
joindex.php
Login Landing Page for JO Judges
Created 3/29/18
Author: criticalitgroup
*******************************************/
require "../../functions_jw.php";
require "../functions.php";
require "../variables.php";
redirectToHTTPS();

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($_POST['login'])
{
   $session=LoginJOStateJudge($email,$password); 
   if(!$session)
      $error=1;
   else
   {
      header("Location:judgestatemain.php?session=$session");
      exit();
   }
} 

echo GetMainHeader();
?>
<form method="post" action="https://secure.nsaahome.org/nsaaforms/jo/joindex.php">
<br>
<h2>State Journalism Judges Login:</h2>
<p><i>Please enter your email address and password assigned to you by the NSAA:</i></p>
<table>
<?php
if($error==1)
{
   echo "<caption><p style=\"text-align:left;color:#ff0000;\"><b>You have entered your e-mail and/or password incorrectly.</p></caption>";
}
?>
<tr align=left>
<td><b>E-mail:</b></td><td><input type=text size=40 name="email"></td></tr>
<tr align=left>
<td><b>Password:</b></td><td><input type=password size=20 name="password"></td></tr>
</table>
<input type=submit name='login' value="Login">
<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();
?>
