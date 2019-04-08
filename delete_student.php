<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//delete_student.php: deletes specified student from the eligibility table in 
// db and from any other table she is in.

//connect to db
$db=mysql_connect("$db_host","nsaa","scores");
mysql_select_db("$db_name",$db);

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
   header("Location:view_student.php?session=$session&id=$id&activity_ch=$activity_ch&school_ch=$school_ch&letter=$letter");
   exit();
}

//get student's activities:
$sql="SELECT * FROM eligibility WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$act_list=array();
$x=0; 
for($i=17;$i<=35;$i++)
{
   if($row[$i]=='x')
   {
      $ix=$i-17;
      $act_list[$x]=$activity[$ix];
      if($act_list[$x]=="cc" || $act_list[$x]=="te" || $act_list[$x]=="bb" || $act_list[$x]=="go" || $act_list[$x]=="tr" || $act_list[$x]=="so")
      {
	 if($row[5]=="M") $act_list[$x].="_b";
	 else $act_list[$x].="_g";
      }
      $x++;
   }
}
for($i=0;$i<count($act_list);$i++)
{
   $sql="DELETE FROM $act_list[$i] WHERE student_id='$id'";
   $result=mysql_query($sql);
}

$sql="DELETE FROM eligibility WHERE id='$id'";
$result=mysql_query($sql);

?>
<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<?php
$header=GetHeader($session);
echo $header;
?>
<center>
<br><br>
<font size=2>
<b><?php echo $name; ?></b> has been deleted from the database.</font>
<br><br>
<?php
echo "<a href=\"eligibility.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=$letter\">Return to Eligibility List</a>&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\">Return to Home</a>";
?>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
