<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) )
{
   header("Location:index.php?error=1");
   exit();
}

  if($_GET['school']!='')
  {
   if($_GET['school']=='Columbus' || $_GET['school']=='Papillion-La Vista' ||  $_GET['school']=='Omaha North' ||  $_GET['school']=='Falls City' ||  $_GET['school']=='Norfolk' ||  $_GET['school']=='Kearney' ||  $_GET['school']=='Ord' ||  $_GET['school']=='Hastings')
   $sql="SELECT * FROM believers where  school ='".mysql_real_escape_string($_GET['school'])."' ";
   else
   $sql="SELECT * FROM believers where  school LIKE '".'%'.mysql_real_escape_string($_GET['school']).'%'."' ";
   }
   else 
   {
   $sql="SELECT * FROM believers ORDER BY school";
   }
  $result=mysql_query($sql);
    $list_data = array();
 	while($row=mysql_fetch_array($result)){ 
	$list_data[]= $row;	 } 
	
	$sql_="SELECT duedate FROM believers_duedates WHERE id=1 ";
    $result_=mysql_query($sql_);
    $row_=mysql_fetch_array($result_);

 echo $init_html;
 echo $header;
//$session=$_GET[session];

//echo $end_html;

?>

<br><br><br>
<?php if ($row_[duedate]<date("Y-m-d")){?><h2>Due date is over</h2>
<?php } else {?>
<?php if (mysql_num_rows($result)>10){?><h2>Already Eleven Forms Are Submitted </h2>Due date is :<?php echo $row_[duedate]; ?>
<?php } else {?>
<h3><a href="believers.php?session=<?php echo $session; ?>&school=<?php echo $school;?>">Submit Application</a></h3>Due date is :<?php echo $row_[duedate]; ?>
<?php } }?>
<h3><a href="welcome.php?session=<?php echo $session; ?>" >Home</a></h3>

<table border='1' style="width:60%">
<th>Name</th>
<th>School</th>
<th>Action</th>
<th>Certificate</th>
<?php
foreach ($list_data as $row){
?>
<tr>
<td><?php echo $row[name];?></td>
<td><?php echo $row[school];?></td>
<td><a href="believers.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>&view=1&school=<?php echo $_GET['school'];?>"><?php if ($row_[duedate]>date("Y-m-d"))echo 'Edit';else echo 'View';?></a> 
    <!--<a href="believers_delete.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>--></td>
	<td><a href="certificate.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>" target="_blank"><?php  echo 'Certificate';?></a> 
<tr>
<?php } ?>
</table>
