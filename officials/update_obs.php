<?php
//update_oobs.php: takes submitted information from observers.php
//	(obs_list.php) and updates the db

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);

for($i=0;$i<$count;$i++)
{
   $sql="UPDATE observers SET di='$di[$i]',fb='$fb[$i]',vb='$vb[$i]',wr='$wr[$i]',bb='$bb[$i]',so='$so[$i]',sb='$sb[$i]',sw='$sw[$i]',ba='$ba[$i]',tr='$tr[$i]' WHERE id='$obsid[$i]'";
   $result=mysql_query($sql);
}
?>
<script language="javascript">
top.location.replace("observers.php?session=<?php echo $session; ?>&lastname=<?php echo $lastname; ?>&sport=<?php echo $sport; ?>&query=<?php echo $query; ?>&last=<?php echo $last; ?>")
</script>
