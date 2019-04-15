<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1 || !$id || $id==0)
{
   header("Location:index.php?error=1");
   exit();
}
$sql="DELETE FROM ejections WHERE id='$id'";
//$result=mysql_query($sql);
?>
<script language='javascript'>
window.opener.document.forms.ejectionform.hiddensave.value='Submit';
window.opener.document.forms.ejectionform.submit();
window.close();
</script>
<?php
exit();
?>
