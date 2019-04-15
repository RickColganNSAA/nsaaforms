<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//delete_official.php: deletes specified official from the officials table

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
   header("Location:edit_off.php?session=$session&id=$id&sport=$sport&query=$query&last=$last");
   exit();
}

$sql="SELECT * FROM officials WHERE id='$id'";
$result=mysql_query($sql);
$act_list=array(); $ix=0;
$row=mysql_fetch_array($result);
for($i=0;$i<count($activity);$i++)
{
   if($row[$activity[$i]]=='x')
   {
      $act_list[$ix]=$activity[$i];
      $ix++;
   }
}
for($i=0;$i<count($act_list);$i++)
{
   $table1=$act_list[$i]."off";
   $table2=$table1."_hist";
   $sql="DELETE FROM $table1 WHERE offid='$id'";
   $result=mysql_query($sql);
   $sql="DELETE FROM $table2 WHERE offid='$id'";
   $result=mysql_query($sql);
}

$sql="DELETE FROM officials WHERE id='$id'";
$result=mysql_query($sql);
$sql="DELETE FROM logins WHERE offid='$id'";
$result=mysql_query($sql);


echo $init_html;
$header=GetHeader($session);
echo $header;
?>
<center>
<br><br>
<font size=2>
<b><?php echo $name; ?></b> has been deleted from the database.</font>
<br><br>
<?php
echo "<a href=\"officials.php?session=$session&sport=$sport&query=$query&last=$last\">Return to Officials List</a>&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\">Return to Home</a>";
?>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
