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
if($reset==1)
{
$table= 'believers'.(date('Y')-1); 
$sql= "CREATE TABLE $table (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `gender` varchar(250) NOT NULL,
  `race` varchar(250) NOT NULL,
  `school` varchar(250) NOT NULL,
  `street` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `zip` varchar(250) NOT NULL,
  `cell` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `submitted` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `class` varchar(250) NOT NULL,
  `average` varchar(250) NOT NULL,
  `list` text NOT NULL,
  `activity` varchar(250) NOT NULL,
  `award` text NOT NULL,
  `activity1` varchar(250) NOT NULL,
  `office1` varchar(250) NOT NULL,
  `length1` varchar(250) NOT NULL,
  `time1` varchar(250) NOT NULL,
  `activity2` varchar(250) NOT NULL,
  `office2` varchar(250) NOT NULL,
  `length2` varchar(250) NOT NULL,
  `time2` varchar(250) NOT NULL,
  `activity3` varchar(250) NOT NULL,
  `office3` varchar(250) NOT NULL,
  `length3` varchar(250) NOT NULL,
  `time3` varchar(250) NOT NULL,
  `activity4` varchar(250) NOT NULL,
  `office4` varchar(250) NOT NULL,
  `length4` varchar(250) NOT NULL,
  `time4` varchar(250) NOT NULL,
  `c_activity1` varchar(250) NOT NULL,
  `c_office1` varchar(250) NOT NULL,
  `c_length1` varchar(250) NOT NULL,
  `c_time1` varchar(250) NOT NULL,
  `c_activity2` varchar(250) NOT NULL,
  `c_office2` varchar(250) NOT NULL,
  `c_length2` varchar(250) NOT NULL,
  `c_time2` varchar(250) NOT NULL,
  `c_activity3` varchar(250) NOT NULL,
  `c_office3` varchar(250) NOT NULL,
  `c_length3` varchar(250) NOT NULL,
  `c_time3` varchar(250) NOT NULL,
  `c_activity4` varchar(250) NOT NULL,
  `c_office4` varchar(250) NOT NULL,
  `c_length4` varchar(250) NOT NULL,
  `c_time4` varchar(250) NOT NULL,
  `essay` text NOT NULL,
  `document` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `parent_name` varchar(250) NOT NULL,
  `parent_email` varchar(250) NOT NULL,
  `question19` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
//echo $sql; exit;
mysql_query($sql);
$sql1="INSERT INTO $table SELECT * FROM believers";
mysql_query($sql1);
$sql2="TRUNCATE TABLE  believers";
mysql_query($sql2);
header("Location:believers_list.php?session=$session");
exit;
}

if($_GET['school']!='')
  $sql="SELECT * FROM believers where  school='".$_GET['school']."' ";
 else $sql="SELECT * FROM believers ORDER BY school";
 $result=mysql_query($sql);
     $list_data = array();
 	while($row=mysql_fetch_array($result)){ 
	$list_data[]= $row;	 } 

 

  if($_GET['file']){ 
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('Name','Gender','School','Class','Email','Parent\'s Name','Parent\'s Email','GPA'));
    $rows = mysql_query('SELECT name,gender,school,class,email,	parent_name,parent_email,average FROM believers order by school');
    while ($row = mysql_fetch_assoc($rows)) 
	 { 
	fputcsv($output, $row); }exit;
  }
 if($_GET['sport']){
	$rows = mysql_query('SELECT id,sport FROM anthem');
	$sports = array();
	if($_GET['sport']=='football')$number=3;
	else if($_GET['sport']=='vollleyball')$number=2;
	else if($_GET['sport']=='cross_country')$number=1;
	else if($_GET['sport']=='wrestling')$number=4;
	else if($_GET['sport']=='swimming')$number=5;
	else if($_GET['sport']=='duel_wrestling')$number=6;
	else if($_GET['sport']=='girls_basketball')$number=7;
	else if($_GET['sport']=='boys_basketball')$number=8;
	else if($_GET['sport']=='soccer')$number=9;
	else if($_GET['sport']=='boys_baseball')$number=10;
	else if($_GET['sport']=='track')$number=11;

	while ($row = mysql_fetch_assoc($rows)) {
	$sport= explode(',',$row[sport]);
	if($_GET['sport']){
	if(in_array($number, $sport))
	  {
	  $sports[]=$row[id];
	  }
	}
	}
	header('Content-Type: text/csv; charset=utf-8');
	if($_GET['sport']){
	header('Content-Disposition: attachment; filename='.$_GET['sport'].'.csv');
	}
	else{
	header('Content-Disposition: attachment; filename=sport.csv');	
	}
	$output = fopen('php://output', 'w');
	fputcsv($output, array('Audition Number','Singer Type','School Name','Director Name','Address','Students Name'));
	foreach($sports as $ball){
    $rows = mysql_query('SELECT id,type,school,sponsor,address,name,last,grade FROM anthem WHERE id='.$ball);
	while ($row = mysql_fetch_assoc($rows)) 
    {    $first_name=explode(",",$row[name]);
		 $last_name=explode(",",$row[last]);
		 $grade=explode(",",$row[grade]);
		 $name= array();
		 for($i=0;$i<8;$i++){
		 if(!empty($grade[$i]))
		 $name[] = $first_name[$i]." ".$last_name[$i]."(".$grade[$i].")";
		 else
		 $name[] = $first_name[$i]." ".$last_name[$i];
		 }
		 $row[name]=implode(",",$name);
		 unset($row[last]);
		 unset($row[grade]);
	fputcsv($output, $row); }
	}
    exit;
  }
	


echo $init_html;
echo $header;


//echo $end_html;
?>
<style>
ul {background-color:white;width:100px; border:1px}
ul li {list-style:none; background-color:white;border:1px}
</style>
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<br>
<!--<a href="1rename.php?session=<?php echo $session;?>"><button type="button" name=""onclick="return confirm('Are you sure you want to delete all data from below list?');" value="delete">CLEAR LIST for new year</button><a>-->
<h3>Believers & Achievers Nomination Form List</h3>
<a href="believers_list.php?session=<?php echo $session;?>&reset=1"><button type="button" name=""onclick="return confirm('Are you sure you want to delete all data from below list?');" value="delete">CLEAR LIST for new year</button><a><br><br>
<?PHP if($_GET['school']=='') {?>
<!--<a href="anthem.php?session=<?php echo $session;?>">Add New School</a><br><br>
<a href="anthem_remaining.php?session=<?php echo $session;?>">Remaining Schools</a>
<br><br>
<form method="post" action="anthem_list.php?session=<?php echo $session;?>" enctype="multipart/form-data">
<div class="ui-widget">
  <label for="tags">Search School Name: </label>
  <input type="text" name="school" id="tags">
  <input type="hidden" name="schooll" id="tag">
  <input type="submit" value="search">
</div>
<?php } ?>
-->
<a href="believers_question.php?session=<?php echo $session;?>"><span>Edit Question No 19</span></a><br><br>
<a href="believers_list.php?session=<?php echo $session;?>&file=remaining"><div>Download Full Data Export</div></a><br>
<a href="believers_fullpdf.php?session=<?php echo $session;?>" target="_blank"><div>Full Data in PDF Format</div></a><br>

<table border='1' style="width:60%">

<th>Name</th>
<th>School</th>
<th>View</th>
<th>PDF</th>
<th>Action</th>
<?php
while($row=mysql_fetch_array($search_result)){
?>
<tr>

<td><?php echo $row[id];?></td>
<td><?php echo $row[school];?></td>
<td><a href="believers_view.php?session=<?php echo $session ?>&id=<?php echo $row[id]; ?>" target="_blank"><?php echo 'View Form';?></a></td>
<td><a href="believers_pdf.php?session=<?php echo $session; ?>&id=<?php echo $row[id]; ?>" target="_blank"><?php echo 'Preview PDF';?></a></td>
<td><a href="anthem.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>">Edit</a> 
    <a href="anthem_delete.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>

<tr>
<?php } ?>
<?php
if (empty($search_result)){
foreach ($list_data as $row){
?>
<tr>
<!--<td><input type="checkbox" name="choose[]"  id="#test" value="<?php echo $row[id]; ?>"></td>-->
<td><?php echo $row[name];?></td>
<td><?php echo $row[school];?></td>
<td><a href="believers_view.php?session=<?php echo $session ?>&id=<?php echo $row[id]; ?>" target="_blank"><?php echo 'View Form';?></a></td>
<td><a href="believers_pdf.php?session=<?php echo $session; ?>&id=<?php echo $row[id]; ?>" target="_blank"><?php echo 'Preview PDF';?></a></td>
<?PHP if($_GET['school']=='') {?>
<td><a href="believers.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>">Edit</a> 
    <a href="believers_delete.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>
<?PHP }?>
<tr>
<?php }} ?>
</table>
</form>
  <script>
  $( function() {
    var availableTags = [
<?php  
foreach ($list_data as $row){ echo '"'.$row[school].'",';}
?>
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  } );
function test(filter){
    document.getElementById("tag").value=filter;
    //xajax call here
}  

  </script>