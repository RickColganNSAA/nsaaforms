<?php
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

//get level & school of user
$level=GetLevel($session);
$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
//else 
	
	$sql1="SELECT DISTINCT school From headers";
    $result=mysql_query($sql1);
	$all_school = array();
	while($row=mysql_fetch_array($result)){ 
	$all_school[]=$row[school]; } 
	
	$sql2="SELECT school From anthem";
    $result=mysql_query($sql2);
	$school_uploaded = array();
	while($row=mysql_fetch_array($result)){ 
	$school_uploaded[]=$row[school]; } 
	
	$remaining_schools = array_diff($all_school,$school_uploaded);
	
	$list_data = array();	
	
    foreach ($remaining_schools as $remaining_school){
	$sql3="SELECT name, email, school,sport From logins WHERE school='$remaining_school' AND sport='Vocal Music'";
    $result=mysql_query($sql3);
	while($row=mysql_fetch_array($result)){ 
	$list_data[]= $row;	 } 
    }
	
	$export_data = array();	
	
    foreach ($remaining_schools as $remaining_school){
	$sql3="SELECT school From logins WHERE school='$remaining_school' AND sport='Vocal Music'";
    $result=mysql_query($sql3);
	while($row=mysql_fetch_assoc($result)){ 
	$export_data[]= $row;	 } 
    }
	$email =array();
	foreach ($list_data as $row){ 
	$email[]=$row[email];
	}
	$subject="Anthem Upload Remaining";
	$emails = implode(",",$email);
	$Text="You need to upload anthem mp3 file and singers information.  Please log in using the Schools Login (https://secure.nsaahome.org/nsaaforms/), scroll down to the Activity Select section, select music, click on 'Upload National Anthem Singer'. \r\n\r\nPlease contact the NSAA office if you have any questions.\r\n\r\nThank You!";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['school']){
    $sql6="SELECT name, email, school,sport From logins WHERE school='$_POST[school]' AND sport='Vocal Music'";
    $search_result=mysql_query($sql6);
    }
	if ($_POST['replyto']){
	
	$headers = $_POST['replyto'];
	$subject = $_POST['subject'];
	$message = $_POST['content'];
	
    foreach ($list_data as $row){ 
	mail($row[email], $subject, $message, $headers);
	                            }
	                       }
				   
	}

    if($_GET['file']){
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=remaining_school.csv');
	$output = fopen('php://output', 'w');
    fputcsv($output, array('School Name'));
	foreach ($export_data as $data) {fputcsv($output, $data);  }exit;
	}
	$now=time();
   $filename="roster".$now.".csv";

//If you get here, need to show blank message form
echo $init_html;
echo GetHeader($session);
echo "<br>";


//echo $end_html;
?>
<style>
ul {background-color:white;width:100px; border:1px}
ul li {list-style:none; background-color:white;border:1px}
</style>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<a href="anthem_list.php?session=<?php echo $session;?>">Completed Schools</a><br><br>

<table border='1' style="width:60%">
<th>School</th>
<th>Name</th>
<th>Sport</th>
<th>Email</th>
<h3>Schools have not submitted anthem audio file</h3>
<form method="post" action="anthem_remaining.php?session=<?php echo $session;?>" enctype="multipart/form-data">
<div class="ui-widget">
  <label for="tags">Search School Name: </label>
  <input type="text" name="school" id="tags">
  <input type="submit" value="search">
</div>
</form>
<a href="anthem_remaining.php?session=<?php echo $session;?>&file=remaining"><h4>Export remaining school name </h4></a>

<?php 
while($row=mysql_fetch_array($search_result)){ 
?>
<tr>
<td><?php echo $row[school];?></td>
<td><?php echo $row[name];?></td>
<td><?php echo $row[sport];?></td>
<td><?php echo $row[email];?></td>

<tr>
<?php } ?>
<?php
if(empty($search_result)){
foreach ($list_data as $row){
?>
<tr>
<td><?php echo $row[school];?></td>
<td><?php echo $row[name];?></td>
<td><?php echo $row[sport];?></td>
<td><?php echo $row[email];?></td>
<tr>
<?php }} ?>
</table>
<br><br><br>
<form method="post" action="anthem_remaining.php?session=<?php echo $session;?>" enctype="multipart/form-data">
<table border=1 width=60% >
<tr align=center><td><b>Review the information below and click "Send Reminder" in order to e-mail each school to upload mp3 files for anthem and singers information:</b></td></tr>
<tr align=center><td>
<table  width=100% class=nine>
<tr><td></td></tr>
<tr><td><label style="vertical-align: top;"><b>Reply-to Email:</b></label></td> <td><input type="text" size=30 name="replyto" value="nsaa@nsaahome.org" style="width:60%"></td></tr>
<tr><td></td></tr>
<tr><td><b>Recipient List: </b></td><td><textarea rows="10" cols="50" name="content" style="width:100%"><?php echo $emails; ?></textarea></td></tr>
<tr><td></td></tr>
<tr><td><b>Subject: </b></td><td><input type="text" name="subject" value="<?php echo $subject;?>" style="width:60%"></td></tr>
<tr><td></td></tr>
<tr><td><label style="vertical-align: top;"><b>Message:</b></label></td> <td><textarea rows="10" cols="50" name="content" style="width:100%"><?php echo $Text;?></textarea></td></tr>
<tr align=center><td colspan="2" ><input type="submit" value="Send Reminder"></td></tr>
</table></td></tr></table>

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

  </script>
</head>
<body>
 

