<?php
//coop_sp.php: pop-up window to add coop-students to district entry

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

//submit prose information:
if($save=="Save & Close")
{ 
$school2 = mysql_real_escape_string($school);
$name = mysql_real_escape_string($name);
$title = mysql_real_escape_string($title);
$author = mysql_real_escape_string($author);
$publisher = mysql_real_escape_string($publisher);
$isbn = mysql_real_escape_string($isbn);
$website = mysql_real_escape_string($website);
 //$sql="SELECT * FROM spprose WHERE school='".$school2."' AND name='".$name."' ";
   $sql="SELECT * FROM spprose WHERE school='".$school2."' AND student_id='".$student_id."' AND name='".$name."' "; 
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
   if(empty($row))
   {
       $sql="INSERT INTO spprose (school,student_id,name,title,author,publisher,isbn,website) VALUES ('$school2','$student_id','$name','$title','$author','$publisher','$isbn','$website')";
      $result=mysql_query($sql);
   }
   else
   {
       $sql="UPDATE spprose SET title='$title',author='$author',publisher='$publisher',isbn='$isbn',website='$website' WHERE school='$school2' AND student_id='$student_id' AND name='$name'"; 
      $result=mysql_query($sql);
   }
echo mysql_error();
//exit;
//exit;
?>
<script language="javascript">
//window.opener.document.forms.spform.submit();
window.close();
</script>
<?php
exit();
}
?>

<html>
<head>
<title>Oral Interpretation of Prose</title>
<link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<center>
<?php 
$school2=ereg_replace("\'","\'",$school);
//$sql="SELECT * FROM spprose WHERE school='".$school2."' AND name='".$name."' ";
$sql="SELECT * FROM spprose WHERE school='".$school2."' AND student_id='".$id."' AND name='".$name."' ";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
?>
<form name=form1 method="post" action="prose.php">
<input type="hidden" name="school" value="<?php echo $school; ?>">
<input type="hidden" name="name" value="<?php echo $name; ?>">
<input type="hidden" name="student_id" value="<?php echo $id; ?>">

<table width=400 border=1 bordercolor=#000000 cellspacing=2 cellpadding=5 style="margin:20 80px;">
<tr><td>Title</td><td><input type="text" name="title" value="<?php echo $row[title];?>" style="width:100%" required></td><tr>
<tr><td>Author</td><td><input type="text" name="author" value="<?php echo $row[author];?>" style="width:100%" required></td><tr>
<tr><td>Publisher</td><td><input type="text" name="publisher" value="<?php echo $row[publisher];?>" style="width:100%" required></td><tr>
<tr><td>ISBN</td><td><input type="text" name="isbn" value="<?php echo $row[isbn];?>" style="width:100%" required></td><tr>
<tr><td>Website</td><td><input type="text" name="website" value="<?php echo $row[website];?>" style="width:100%" required></td><tr>
</table>
<br>
<?php if(empty($view)) {?>
<input type="submit" name=save value="Save & Close">
<?php } ?>
</center>
</form>
</body></html>
