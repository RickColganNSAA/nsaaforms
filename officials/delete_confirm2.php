<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//delete_confirm.php: Ask user if they are sure they want to
//	delete specified judge from the database

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$sql="SELECT first, last FROM judges WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo $init_html;
if($header!="no") $header1=GetHeaderJ($session);
else $header1="<table><tr align=center><td>";
echo $header1;

$name="$row[0] $row[1] $row[2]";
?>
<center>
<br><br>
<font size=2>Are you sure you want to delete
<?php echo " <b>$name</b> ";  ?>
from the database?
<form method="post" action="delete_judge.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=id value=<?php echo $id; ?>>
<input type=hidden name=name value="<?php echo $name; ?>">
<input type=hidden name=sport value="<?php echo $sport; ?>">
<input type=hidden name=query value="<?php echo $query; ?>">
<input type=hidden name=last value=<?php echo $last; ?>>
<input type=hidden name=header value=<?php echo $header; ?>>

<input type=submit name=submit value="Yes">&nbsp;
<input type=submit name=submit value="No">
</form>
</center>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
