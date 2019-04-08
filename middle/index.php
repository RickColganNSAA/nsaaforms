<?php
/*********************************************
index.php
Login screen for Middle School Administrators
Created 12/26/09
Author: Ann Gaffigan
**********************************************/

if($login)	//USER HAS ENTERED LOGIN INFO
{
   require '../variables.php';
   require '../functions.php';

   //connect to db
   $db=mysql_connect($db_host,$db_user,$db_pass);
   mysql_select_db($db_name, $db);

   $sql="SELECT * FROM logins WHERE passcode='$passcode' AND level='8'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0 && $passcode!="")
   {
      $row=mysql_fetch_array($result);
      $num=$row[0];
      $session_id=time();
      $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $session_id--;
         $sql2="SELECT * FROM sessions WHERE session_id='$session_id'";
         $result2=mysql_query($sql2);
      }
      $sql="INSERT INTO sessions (session_id, login_id) VALUES ('$session_id', '$row[0]')";
      $result=mysql_query($sql);
      header("Location:welcome.php?session=$session_id");
      exit();
   }
   else
   {
      header("Location:index.php?error=1");
      exit();
   }
}

//ELSE: SHOW LOGIN SCREEN
require "../../functions.php";
require "../variables.php";
echo GetMainHeader();
?>
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="https://secure.nsaahome.org/nsaaforms/middle/index.php">
<br>
<br>
<table>
<tr align=left>
<td colspan=2>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>You have entered your password incorrectly.<br>Please make sure your capslock is not on.<br>If you have forgotten your password, contact the NSAA.</b></font><br><br>";
}
?>
</td>
</tr>
<tr align=left><th colspan=2><font style="font-family:arial; font-size:10pt">Welcome! Please select your school and enter your assigned passcode below.</th></tr>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">Middle School:</font></th>
<td align=left><select name=school>
<option>Choose your School</option>
<?php
  $sql="SELECT school FROM middleschools ORDER BY school";
  $result=mysql_query($sql);
  $ix=0;
  while($row=mysql_fetch_array($result))
  {
      echo "<option";
      if($school==$row[0]) echo " selected";
      echo ">$row[0]</option>";
  }
?>
</select>
</td>
</tr>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">Passcode:</font></th>
<th align=left><input type=password name="passcode" size=15></th>
</tr>
<tr align=center>
<th colspan=2><input type=submit name=login value="Login"></th>
</tr>
</table>
</form>
</td></tr>
</table>

<!-- END MAIN TEXT -->
<?php
echo GetMainFooter();
?>
