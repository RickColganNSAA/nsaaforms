<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
include "../ssi/header.ssi";
?>
                <!-- BEGIN MAIN TEXT -->
                
<table cellspacing=0 cellpadding=0>
<tr align=left>
<td colspan=2>
<form method="post" action="login.php">
<br>
<a style="font-family: arial; font-size:10pt" href="/nsaaforms/help_ad.pdf" target=new><b>Help for Administrators</b></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a style="font-family: arial; font-size: 10pt" href="/nsaaforms/help_coach.pdf" target=new><b>Help for Coaches</b></a>
<br>
<br>
<table>
<tr align=left>
<td colspan=2>
<?php
if($error==1)
{
   echo "<font style=\"color:red; font-family:arial;\" size=2><b>You have entered you password incorrectly.<br>Please make sure your capslock is not on.<br>If you have forgotten your password, contact the NSAA.</b></font><br><br>";
}
?>
<font style="font-family:arial; font-size:10pt">For your security, you must now select your school from the<br>dropdown menu before entering your passcode.  Thank you!</font>
</td>
</tr>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">School:</font></th>
<td align=left><select name=school>
<option>Choose your School
<option value="All">NSAA
<option>College
<option>Lincoln Public Schools
<option>Omaha Public Schools
<option>Millard Public Schools
<?php
//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

  $sql="SELECT school FROM headers ORDER BY school";
  $result=mysql_query($sql);
  $ix=0;
  while($row=mysql_fetch_array($result))
  {
      echo "<option>$row[0]";
  }
?>
</select>
</td>
</tr>
<tr align=left>
<th align=left><font style="font-family:arial; font-size:10pt">Passcode:</font></th>
<th align=left><input type=password name="passcode" size=15></th>
</tr>
<tr align=left>
<td></td><td><font style="font-size:8pt;font-family:arial"><b>This application is best viewed with Netscape 7.x or Explorer 5.x or better.</b></td></font>
</tr>
<tr align=center>
<th colspan=2><input type=submit name=submit value="Login"></th>
</tr>
</table>
</form>
</td></tr>
</table>

<!-- END MAIN TEXT -->
<?php
include "../ssi/footer.ssi";
?>
