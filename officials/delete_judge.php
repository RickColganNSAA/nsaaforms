<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//delete_judge.php: deletes specified judge from the judges table

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

if($submit=="No")	//Did not want to delete
{
   header("Location:edit_judge.php?session=$session&id=$id&sport=$sport&query=$query&last=$last&header=$header");
   exit();
}

$sql="DELETE FROM judges WHERE id='$id'";
$result=mysql_query($sql);
$sql="DELETE FROM logins_j WHERE offid='$id'";
$result=mysql_query($sql);


echo $init_html;
if($header!="no") $header1=GetHeaderJ($session);
else $header1="<table><tr align=center><td>";
echo $header1;
?>
<center>
<br><br>
<font size=2>
<b><?php echo $name; ?></b> has been deleted from the database.</font>
<br><br>
<?php
if($header=="no")
{
   echo "<a href=\"#\" class=small onclick=\"window.close()\">Close this Window</a>";
}
else
{
   echo "<a href=\"judges.php?session=$session&sport=$sport&query=$query&last=$last\">Return to Judges List</a>&nbsp;&nbsp;";
   echo "<a href=\"jwelcome.php?session=$session\">Return to Home</a>";
}
?>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
