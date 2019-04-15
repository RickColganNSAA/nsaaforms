<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($submit)
{
   $sql="SELECT * FROM logins WHERE passcode='$passcode'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)	//not logged in
   {
      $error=1;
   } 
   else
   {
      $error=0;
      $offid=$row[offid];
?>
<script language=javascript>
window.opener.document.forms.appform.offid.value="<?php echo $offid; ?>";
window.opener.document.forms.appform.submit();
window.close();
</script>
<?php
      exit();
   }
}

echo $init_html;
echo "<form method=post action=\"minilogin.php\">";
echo "<br><br><table><tr align=left><th align=left>Please Enter Your Passcode:<br>";
echo "<input type=password name=passcode size=20><br>";
echo "<input type=submit name=submit value=\"Log In\"></th></tr></table>";
echo "</form>";
echo $end_html;
?>
