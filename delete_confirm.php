<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//delete_confirm.php: Ask user if they are sure they want to
//	delete specified student from the database

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$sql="SELECT first, middle, last FROM eligibility WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

?>
<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<?php
$header=GetHeader($session);
echo $header;

$name="$row[0] $row[1] $row[2]";
?>
<center>
<br><br>
<font size=2>Are you sure you want to delete
<?php echo " <b>$name</b> ";  ?>
from the database?
<form method="post" action="delete_student.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=id value=<?php echo $id; ?>>
<input type=hidden name=name value="<?php echo $name; ?>">
<input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=letter value=<?php echo $letter; ?>>

<input type=submit name=submit value="Yes">&nbsp;
<input type=submit name=submit value="No">
</form>
</center>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
