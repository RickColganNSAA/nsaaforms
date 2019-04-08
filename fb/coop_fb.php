<?php
//coop_fb.php: add coop-students to fb participation list

require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$school2=ereg_replace("\'","\'",$school);

$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

//submit co-op information:
if($save=="Save & Close")
{
for($i=0;$i<10;$i++)
{
   $coop_school[$i]=ereg_replace("\'","\'",$coop_school[$i]);
   if($coop_school[$i]!="Choose School" && $coop_student[$i]!="Choose Student")
   {
      //check if student already entered as co_op student
      $sql="SELECT * FROM fb_coop WHERE student_id='$coop_student[$i]' AND co_op='$school2'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         $sql="INSERT INTO fb_coop (student_id, school, co_op) VALUES ('$coop_student[$i]','$coop_school[$i]','$school2')";
         $result=mysql_query($sql);
      }
   }
}
?>
<script language="javascript">
window.close();
</script>
<?php
exit();
}
?>

<html>
<head>
<title>Add Co-Op Students</title>
<link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<form name=form1 method="post" action="coop_fb.php">
<input type=hidden name=school value="<?php echo $school; ?>">

<table width=600 border=1 bordercolor=#000000 cellspacing=2 cellpadding=5>
<tr align=center><th class=smaller>School</th>
<th class=smaller>Name</th><th class=smaller>Grade</th></tr>

<?php
for($i=0;$i<10;$i++)
{
   echo "<tr align=center>";
   echo "<td><select name=\"coop_school[$i]\" onchange=submit()>";
   echo "<option>Choose School";
   for($j=0;$j<count($schools);$j++)
   {
      echo "<option";
      if($coop_school[$i]==$schools[$j]) echo " selected";
      echo ">$schools[$j]";
   }
   echo "</select></td>";
   echo "<td><select name=\"coop_student[$i]\" onchange=submit()>";
   echo "<option>Choose Student";
   //get students from selected school
   $sql="SELECT * FROM eligibility WHERE (fb68='x' OR fb11='x') AND school='$coop_school[$i]' ORDER BY last";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=$row[0]";
      if($coop_student[$i]==$row[0]) echo " selected";
      echo ">$row[2], $row[3] $row[4]";
   }
   echo "</select></td>";
   //get selected student's info
   $sql="SELECT semesters FROM eligibility WHERE id='$coop_student[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $year=GetYear($row[0]);
   echo "<td>$year</td>";
   echo "</tr>";
}
?>
</table>
<br>
<input type=submit name=save value="Save & Close">
</center>
</form>
</body></html>
