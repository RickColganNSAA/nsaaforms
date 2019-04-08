<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $sql2="UPDATE believers_duedates SET question='$question' WHERE id=1"; 
  $result2=mysql_query($sql2);
  
  header("Location:believers_question.php?session=$session");
  exit();
}


 echo $init_html;
 echo $header;
//$session=$_GET[session];

//echo $end_html;
?>
<br><br><br>
<a href="believers_list.php?session=<?php echo $session; ?>"><span>Go Back to Believers & Achievers Nomination Form List</span></a>
<h3>Question NO 19</h3>

<?php
   $sql="SELECT * FROM believers_duedates WHERE id =1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result); 
?>
<form method="post" action="believers_question.php" enctype="multipart/form-data">
<table>
<tr align="center"><td ><textarea name="question"  rows="10" cols="50"><?php echo $row[question]; ?></textarea> </td></tr>
<tr align="center"><td ><input type="submit" value="submit" name="submit"></td></tr>
<tr align="center"><td ><input type="hidden" value="<?php echo $session; ?>" name="session"></td></tr>
</table>
</form>