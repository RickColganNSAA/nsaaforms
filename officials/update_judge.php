<?php
//update_judge.php: takes submitted information from judges.php
//	(judge_list.php) and updates the db

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);

for($i=0;$i<$count;$i++)
{
   $sql="UPDATE judges SET ppmeeting='$ppmeeting[$i]',spmeeting='$spmeeting[$i]',firstyrplay='$firstyrplay[$i]',firstyrspeech='$firstyrspeech[$i]',play='$play[$i]',speech='$speech[$i]' WHERE id='$offid[$i]'";
   $result=mysql_query($sql);
   $sql="UPDATE logins_j SET passcode='$passcode[$i]' WHERE offid='$offid[$i]'";
   $result=mysql_query($sql);
}
?>
<script language="javascript">
top.location.replace("judges.php?lastname=<?php echo $lastname; ?>&session=<?php echo $session; ?>&sport1=<?php echo $sport1; ?>&sport2=<?php echo $sport2; ?>&bool=<?php echo $bool; ?>&query=<?php echo $query; ?>&last=<?php echo $last; ?>")
</script>
