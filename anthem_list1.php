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

 $sql="SELECT * FROM anthem WHERE school='$_GET[school]'ORDER BY id";
 $result=mysql_query($sql);
     $list_data = array();
 	while($row=mysql_fetch_array($result)){ 
	$list_data[]= $row;	 } 

 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    if ($_POST['school'] && $_POST['schooll']!=55 && $_POST['schooll']!=88){
    $sql="SELECT * From anthem WHERE school='$_POST[school]'";
    $search_result=mysql_query($sql);
    }
  if ($_POST['choose'] && $_POST['schooll']==88){ 
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=SchoolList.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('Audition Number','Singer Type','School Name','Director Name','Address','Students Name'));
	foreach($_POST['choose'] as $ball){
    $rows = mysql_query('SELECT id,type,school,sponsor,address,name,last,grade FROM anthem WHERE id='.$ball);
	while ($row = mysql_fetch_assoc($rows)) 
     {                                $first_name=explode(",",$row[name]);
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
   if ($_POST['schooll']==55){
   if(!empty($_POST['choose'])){
   $rows = mysql_query('SELECT id FROM anthem');
   while ($row = mysql_fetch_assoc($rows)) {
   $all_id[]=$row['id'];
   }
   $result=array_diff($all_id,$_POST['choose']);
   	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=SchoolList.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('Audition Number','Singer Type','School Name','Director Name','Address','Students Name'));
   	foreach($result as $ball){
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
   }   
  }
  if($_GET['file']){
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('Audition Number','Singer Type','School Name','Director Name','Address','Students Name','Events'));
    $rows = mysql_query('SELECT id,type,school,sponsor,address,name,last,grade,sport FROM anthem');

    while ($row = mysql_fetch_assoc($rows)) 
	 {$events = explode(",",$row[sport]);  $a=array();foreach($events as $event){ 
	                                 if($event==1){$a[]='CC Championship(October 21, 2016, Kearney)';}
									 if($event==2){$a[]='VB Championship Finals (November 11 & 12, Lincoln)';} 
									 if($event==3){$a[]='FB Championship Finals (November 21 & 22, Lincoln)';} 
									 if($event==4){$a[]='WR Championship (February 16, 17, & 18, 2017, Omaha)';}
									 if($event==5){$a[]='SD Championship Finals (Feb. 23, 24 & 25, Lincoln)';}
									 if($event==6){$a[]='DWR Championships (February 25, Kearney)';} 
									 if($event==7){$a[]='G_BB Championship Finals (March 4, Lincoln)';}
									 if($event==8){$a[]='B_BB Championship Finals (March 11, Lincoln)';}
									 if($event==9){$a[]='SO Championship Finals (May 15 & 16, Omaha)';} 
									 if($event==10){$a[]='B_BA Championship Finals (May 18, Lincoln)';} 
									 if($event==11){$a[]='TF Championship (May 19 & 20, Omaha)';}} 
									 $row[sport]=implode(",",$a);
									 $first_name=explode(",",$row[name]);
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

  if($_GET['chosen']){
	$rows = mysql_query('SELECT id,sport FROM anthem');
	$football = array();
	while ($row = mysql_fetch_assoc($rows)) {
	$sport= explode(',',$row[sport]);
	if(in_array("3", $sport))
	  {
	  $football[]=$row[id];
	  }
	}
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=SchoolList.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('Audition Number','Singer Type','School Name','Director Name','Address','Students Name'));
	foreach($football as $ball){
    $rows = mysql_query('SELECT id,type,school,sponsor,address,name,last,grade FROM anthem WHERE id='.$ball);
	while ($row = mysql_fetch_assoc($rows)) 
    {                                $first_name=explode(",",$row[name]);
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
  //echo '<pre>'; print_r($football);

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


<h3>NATIONAL ANTHEM SINGERS, 2017-18 NSAA CHAMPIONSHIPS</h3>
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


<a href="anthem_list.php?session=<?php echo $session;?>&file=remaining"><div>Download Full Data Export</div></a><br>
<b>AVAILABILITY:</b>&nbsp;&nbsp;<a href="anthem_list.php?session=<?php echo $session;?>&sport=cross_country">CC</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=vollleyball">VB</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=football">FB</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=wrestling">WR</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=swimming">SD</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=duel_wrestling">DWR</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=girls_basketball">GBB</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=boys_basketball">BBB</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=soccer">SO</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=boys_baseball">BA</a>
<a href="anthem_list.php?session=<?php echo $session;?>&sport=track">TR</a><br><br>
<a href="#" onclick="test('88');$(this).closest('form').submit();"><div>Information of selected rows from below list</div></a><br>
<a href="#" onclick="test('55'); closest('form').submit();"><div>Information of not selected rows from below list</div></a><br>
-->
<table border='1' style="width:60%">
<th> </th>
<th>Audition Number</th>
<th>School</th>
<th>Sponsor</th>
<th>Music file</th>
<th>Action</th>
<?php
while($row=mysql_fetch_array($search_result)){
?>
<tr>
<td></td>
<td><?php echo $row[id];?></td>
<td><?php echo $row[school];?></td>
<td><?php echo $row[sponsor];?></td>
<td ><audio controls>
   <source src="anthem/<?php echo $row[music_file];?>" type="audio/mp4">
   <source src="anthem/<?php echo $row[music_file];?>" type="audio/mp3">
Your browser does not support the audio element.
</audio></td>
<td><a href="anthem.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>">Edit</a> 
    <a href="anthem_delete.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>&school=<?php echo ($row[school]);?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>

<tr>
<?php } ?>
<?php
if (empty($search_result)){
//while($row=mysql_fetch_array($result)){
foreach ($list_data as $row){
?>
<tr>
<td><input type="checkbox" name="choose[]"  id="#test" value="<?php echo $row[id]; ?>"></td>
<td><?php echo '#'.$row[id];?></td>
<td><?php echo $row[school];?></td>
<td><?php echo $row[sponsor];?></td>
<td ><audio controls>
   <source src="anthem/<?php echo $row[music_file];?>" type="audio/mp4">
   <source src="anthem/<?php echo $row[music_file];?>" type="audio/mp3">
Your browser does not support the audio element.
</audio></td>
<td><a href="anthem.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>">Edit</a> 
    <a href="anthem_delete.php?id=<?php echo $row[id]?>&session=<?php echo $session; ?>&school=<?php echo ($row[school]);?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>

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