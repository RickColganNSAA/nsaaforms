<?php
//echo $_GET[school]; exit;
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
   header("Location:index.php?error=3");
   exit();
}

$erro_happen=0;
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
	 
	 foreach($_POST as $key=>$value) 
 
	if(!is_array($value))
			$_POST[$key]=mysql_real_escape_string($value);
//print_r($_POST); exit;
	if (empty($_POST["school"])){
	$schErr = "School name is required";
	}
	
	if (empty($_POST["sponsor"])){
	$sponsorErr = "Field is required";
	}
	
	if (empty($_POST["sport"])){
	$sportErr = "Need to select a sport";
	}
	$j=0;
	for ($i=0;$i<8;$i++)
	{
	if(!empty($_POST["name"][$i]) && !empty($_POST["last"][$i]) && !empty($_POST["grade"][$i]))
	{$j++;}
	}
	if($_POST["type"]=='soloist' && $j!=1)
	$nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\">single </b>student";
	elseif($_POST["type"]=='group_duet' && $j!=2)
	$nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> two </b>students";
	elseif($_POST["type"]=='group_trio' && $j!=3)
    $nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> three </b>students";
	elseif($_POST["type"]=='group_quartet' && $j!=4)
	$nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> four </b>students";
	elseif($_POST["type"]=='group_quintet' && $j!=5)
    $nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> five </b>students";
	elseif($_POST["type"]=='group_sextet' && $j!=6)
	$nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> six </b>students";
	elseif($_POST["type"]=='group_septet' && $j!=7)
	$nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> seven </b>students";
	elseif($_POST["type"]=='group_octet' && $j!=8)
	$nameErr = "Please insert first name, last name and grade for <b style=\"font-size:14px\"> eight </b>students";
	

	$id = $_POST["id"];
	$name = $_POST["name"];
	$last_name = $_POST["last"];
	$type = $_POST["type"];
	$address = mysql_real_escape_string($_POST["address"]);
	$grade = $_POST["grade"];
	foreach($grade as $student_grade)
	{ if (!empty($student_grade) && ($student_grade<9 || $student_grade>12))
	  $gradeErr = "Grade value should be between 9 to 12";
	}
	$sport = $_POST["sport"];
	$audition_number = rand();
	if (!empty($_GET[school])){
	$school = $_GET[school];
	}else{
	$school = $_POST["school"];
	}
	$sponsor = $_POST["sponsor"];
	$session = $_POST["session"];
	//$insert_date = date('Y-m-d');
	$insert_date = date('Y-m-d');
	
    $singer = mysql_real_escape_string(implode(",",$name));
    $last = mysql_real_escape_string(implode(",",$last_name));
    $points = implode(",",$grade);
    $date = implode(",",$sport);
	
	if(!empty($_FILES["music_file"]["name"])){
	$music = rand().$_FILES["music_file"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/anthem/";
	//$target_dir = "D:xampp/htdocs/nsaaforms/anthem/";
	$target_file = $target_dir . basename(str_replace("'","_",$music));
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["music_file"]["tmp_name"], $target_file);
	$music = mysql_real_escape_string($music);
	}else{
	if(!$id){
	$musicErr = 'Music file is required';
	}
	}
    if (strtolower ($imageFileType) != 'mp3'){
	$musicErr = 'Upload mp3  file';
	}
	
	if(!empty($_FILES["reference_letter"]["name"])){
	$reference = rand().$_FILES["reference_letter"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/anthem/";
	//$target_dir = "D:xampp/htdocs/nsaaforms/anthem/";
	$target_file = $target_dir . basename(str_replace("'","_",$reference));
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["reference_letter"]["tmp_name"], $target_file);
	$reference = mysql_real_escape_string($reference);
	}else{
	if(!$id){
	$refErr = 'Reference file is required';
	}
	}
    if ((strtolower ($refFileType) != 'pdf') || (strtolower ($refFileType )!= 'doc') || (strtolower ($refFileType) != 'docx')){
	$refErr = 'You can upload only doc, pdf or docx file';
	}
	
	
	
	if ($id) {
	if((!empty($_POST["school"])) && (!empty($_POST["sponsor"])) && (!empty($_POST["sport"])) && (empty($nameErr)) && (empty($gradeErr))){
	//$school = mysql_real_escape_string($school);
	$sql="UPDATE anthem SET school='".$school."',name='$singer',type='$type',address='$address',grade='$points',sport='$date',sponsor='$sponsor',last='$last' WHERE id='$id'";
    $result=mysql_query($sql);
	if(mysql_error()!='')
		{	citgf_file_put_contents('logs.txt', "\n".$sql. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX); $erro_happen=1; }	
	
	if (!empty($_FILES["music_file"]["name"])){
		$music=mysql_real_escape_string($music);
	$sql1="UPDATE anthem SET music_file='$music' WHERE id='$id'";
    $result=mysql_query($sql1);
	
	if(mysql_error()!='')
		{	citgf_file_put_contents('logs.txt', "\n".$sql1. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX); $erro_happen=1; }	
	}
	
	if (!empty($_FILES["reference_letter"]["name"])){
		$reference_letter=mysql_real_escape_string($reference_letter);
	//$sql1="UPDATE anthem SET reference_letter='$reference_letter' WHERE id='$id'";
	$sql1="UPDATE anthem SET reference_letter='$reference' WHERE id='$id'";
    $result=mysql_query($sql1);
	
	if(mysql_error()!='')
		{	citgf_file_put_contents('logs.txt', "\n".$sql1. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX); $erro_happen=1; }	
	}
	
	//for file upload to insert name
	if (!empty($_FILES["name_file"]["name"])){
	$name = array();
	$grade = array();
	$handle = fopen(citgf_fopen($_FILES['name_file']['tmp_name']), "r");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
         $name[] = $data[0];
		 $grade[] = $data[1];
    }
	fclose($handle);
	$singer = mysql_real_escape_string(implode(",",$name));
    $points = implode(",",$grade);
	
	$sql="UPDATE anthem SET name='$singer',grade='$points' WHERE id='$id'";
    $result=mysql_query($sql);
	
	if(mysql_error()!='')
		{	citgf_file_put_contents('logs.txt', "\n".$sql. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX); $erro_happen=1; }	
	}
	
	if($level==1){
	//header("Location:anthem_list.php?session=$session");
	header("Location:anthem_success.php?session=$session&school=$school&error_hap=".$erro_happen);
	exit();
	}else{
	header("Location:welcome.php?session=$session");
	exit();
	}
	}
	}else{
	
	if((!empty($_POST["school"])) && (!empty($_POST["sponsor"])) && (!empty($_POST["sport"])) && (empty($musicErr)) && (empty($schErr2) ) && (empty($nameErr)) && (empty($gradeErr))){
	//$school = mysql_real_escape_string($school);
	$audition_number = (mysql_result(mysql_query("SELECT MAX(audition_number) FROM anthem"), 0))+1;
	
	//$reference= mysql_real_escape_string($reference);
	$insert_id = mysql_insert_id();
	$sql="INSERT INTO anthem (school,name,grade,type,address,sponsor,sport,music_file,reference_letter,date,audition_number,last) VALUES ('".$school."','$singer','$points','$type','$address','$sponsor','$date','$music','$reference','$insert_date','$audition_number','$last')"; 
    //echo '<pre>'; print_r($_POST); exit;
	$result=mysql_query($sql);
	
		if(mysql_error()!='')
			{	citgf_file_put_contents('logs.txt', "\n".$sql. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX); $erro_happen=1; }	
		
	$insert_id = mysql_insert_id();
	
	//for file upload to insert name and grade(not necessary now)
	if (!empty($_FILES["name_file"]["name"])){
	$name = array();
	$grade = array();
	$handle = fopen(citgf_fopen($_FILES['name_file']['tmp_name']), "r");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
         $name[] = $data[0];
		 $grade[] = $data[1];
    }
	fclose($handle);
	$singer = mysql_real_escape_string(implode(",",$name));
    $points = implode(",",$grade);
	
	$sql="UPDATE anthem SET name='$singer',grade='$points' WHERE id='$insert_id'";
    $result=mysql_query($sql);
	
		if(mysql_error()!='')
			{	citgf_file_put_contents('logs.txt', "\n".$sql. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX); $erro_happen=1; }	
	}
	// if($level==1){
	// header("Location:anthem_list.php?session=$session");
	// exit();
	// }else{
	header("Location:anthem_success.php?session=$session&school=$school");
	exit();
	//}
	}
	}
}	

 
 if (isset($_GET['id'])) {

   $sql="SELECT * FROM anthem WHERE id =$_GET[id]";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result); 
   $sponsor =$row[sponsor];
   $singer =$row[name];
   $point =$row[grade];
   $type =$row[type];
   $address =$row[address];
   $get_school = mysql_real_escape_string($row[school]);
   $sql_address="SELECT * FROM headers WHERE school ='".$get_school."'"; 
   $result_address=mysql_query($sql_address);
   	if(mysql_error()!='')
		citgf_file_put_contents('logs.txt', "\n".$sql_address. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX);
	
   $row_address=mysql_fetch_array($result_address); 
   $address =$row_address[address1].' '.$row_address[address2].' '.$row_address[city_state].'  '.$row_address[zip];
   $date =$row[sport];
   $school =$row[school];
   $last =$row[last];
   //$email =$row[email];
   $id =$row[id];
   $music_file =$row [music_file];
   $reference_letter =$row [reference_letter];
   $name = explode(",",$singer);
   $grade = explode(",",$point);
   $sport = explode(",",$date);
   $last_name = explode(",",$last);
   //echo '<pre>';print_r($date); exit;

}

 if (isset($_GET['school'])) {
   $get_schools = mysql_real_escape_string($_GET[school]);
   $sql="SELECT * FROM anthem WHERE school ='".$get_school."'"; 
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result); 
   $sponsor =$row[sponsor];
   $singer =$row[name];
   $point =$row[grade];
   $date =$row[sport];
   $type =$row[type];
   $sql_address="SELECT * FROM headers WHERE school ='".$get_schools."'"; 
   $result_address=mysql_query($sql_address);
   if(mysql_error()!='')
		citgf_file_put_contents('logs.txt', "\n".$sql_address. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX);
   $row_address=mysql_fetch_array($result_address); 
   $address =$row_address[address1].' '.$row_address[address2].' '.$row_address[city_state].'  '.$row_address[zip];
   $last =$row[last];
   //$school =$row[school];
   $school =$_GET['school']; //print_r($school); exit;
   //$email =$row[email];
   //$id =$row[id];
   $music_file =$row [music_file];
   $reference_letter =$row[reference_letter];
   $name = explode(",",$singer);
   $grade = explode(",",$point);
   $sport = explode(",",$date);
   $last_name = explode(",",$last);
   //echo '<pre>';print_r($date); exit;

}



 echo $init_html;
 echo $header;

 $sql_date="SELECT * FROM anthem_dates"; 
 $result_date=mysql_query($sql_date); 
 $row_date=mysql_fetch_array($result_date);
 $v_year= $row_date['year'];
 $dan = $row_date['dan_master'];
 $between = $row_date['between_date'];
 $cross_courntry = $row_date['cross_courntry'];
 $volleyball = $row_date['volleyball'];
 $football = $row_date['football'];
 $unified_bowling = $row_date['unified_bowling'];
 $duel_wrestling = $row_date['duel_wrestling'];
 $wrestling = $row_date['wrestling'];
 $swimming = $row_date['swimming'];
 $girls_basketball = $row_date['girls_basketball'];
 $boys_basketball = $row_date['boys_basketball'];
 $soccer = $row_date['soccer'];
 $boys_baseball = $row_date['boys_baseball'];
 $track_field = $row_date['track_field'];

//echo $end_html;
?>
<form method="post" action="anthem.php" enctype="multipart/form-data">
   <br>
   <h3>NATIONAL ANTHEM SINGERS, <?php echo $v_year; ?> NSAA CHAMPIONSHIPS</h3>
   <!--<P style="width:60%;text-align: left;"><?php echo "The Nebraska School Activities Association is accepting audition materials for 2018-2019 NSAA Fall, Winter and Spring Championship Seasons.  This will be the only audition period for the year.
 Soloists and groups of singers with up to eight members from NSAA member schools only are needed to sing the National Anthem for the Championship Finals at selected NSAA events.  If you have students that are interested in representing your school and community, please electronically submit the following to Dan Masters before September 30, 2016:";?>
<br><br><span style="background-color: yellow;"><?php echo "1)    Completed National Anthem Application form (below),";?><br>
<?php echo "2)     One uploaded audition (MP3) of the student(s) singing the National Anthem without accompaniment at a tempo that is appropriate for a sporting event (70 seconds),
(Traditionally simple solo arrangements are preferred)";?><br>
 <?php echo "3)     One uploaded letter from the school supporting the student(s) application for consideration."; ?> </span><br><br>

<?php echo "NSAA Championship performers will be selected based on auditions submitted and notified between September 30, 2016 and January 15, 2017.  Selected singers will be invited to perform at NSAA Championship events & provided with a championship pass.  Auditioning students have a greater chance of being chosen if they select all available events offered for performances.";?> 
</P>-->
 <P style="width:60%;text-align: left;"><?php echo "The Nebraska School Activities Association is accepting audition materials for $v_year NSAA Fall, Winter and Spring Championship Seasons. <b><u> This will be the only audition period for the year.</u></b>
Soloists and groups of singers with up to <b>eight</b> members from NSAA member schools only are needed to sing the National Anthem for the Championship Finals at selected NSAA events.  If you have students that are interested in representing your school and community, <b>please submit the following to Dan Masters before $dan:</b>";?>
<br><br><?php echo "1)    Completed National Anthem Application form (below),";?><br>
<?php echo "2)     One uploaded audition ";?><span style="background-color: yellow;"><?php echo"(MP3)";?></span><?php echo" of the student(s) singing the National Anthem without accompaniment at a tempo that is appropriate for a sporting event (70 seconds),";?>
<span style="background-color: yellow;"><?php echo "(Traditionally simple solo arrangements are preferred)";?><br>
 <?php echo "<b>3)     One uploaded letter from the school supporting the student(s) application for consideration.</b>"; ?> </span><br><br>

<?php echo "NSAA Championship performers will be selected based on auditions submitted and notified between $between.  Selected singers will be invited to perform at NSAA Championship events & provided with a championship pass.  Auditioning students have a greater chance of being chosen if they select all available events offered for performances.";?> 
</P>
   <?php echo 'School'; ?>
   <?php if ($_GET[school]) {?>
   <input type="text" name="school" value="<?php echo $_GET[school];?>" readonly></br></br>
   <?php } else{?>
   <select name="school" <?php if($_GET[school]) echo 'readonly';?>>
   <option value="">Select School</option>
   <?php
   $sql="SELECT school FROM headers ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
   ?>
    <option value="<?php echo $row[school]; ?>"<?php if ($row[school]==$school) echo 'selected';?>><?php echo $row[school]; ?></option>
	
   <?php } ?> 
   
   </select><span style="color:red"><?php echo $schErr; ?></span><span style="color:red"><?php echo $schErr2; ?></span></br></br>
   <?php } ?>
   <!--Email Address: <input type="email" name="email" value="<?php echo $email;?>" placeholder="email"><br><br>-->
   SCHOOL MUSIC DIRECTOR SPONSOR: <input type="text" name="sponsor" value="<?php echo $sponsor;?>"><span style="color:red"><?php echo $sponsorErr; ?></span><br><br>
   <label style="vertical-align: top;">Address:</label><textarea name="address"cols="28" rows="5"><?php echo $address; ?></textarea><br><br>
   Singer Type(soloist or group): <select name="type" style="width:150px">
                                 <option value="soloist"<?php if($type=='soloist')echo 'selected'; ?>>Soloist</option>
                                 <option value="group_duet"<?php if($type=='group_duet')echo 'selected'; ?>>Group-Duet</option>
                                 <option value="group_trio"<?php if($type=='group_trio')echo 'selected'; ?>>Group-Trio</option>
                                 <option value="group_quartet"<?php if($type=='group_quartet')echo 'selected'; ?>>Group-Quartet</option>
                                 <option value="group_quintet"<?php if($type=='group_quintet')echo 'selected'; ?>>Group-Quintet</option>
                                 <option value="group_sextet"<?php if($type=='group_sextet')echo 'selected'; ?>>Group-Sextet</option>
                                 <option value="group_septet"<?php if($type=='group_septet')echo 'selected'; ?>>Group-Septet</option>
                                 <option value="group_octet"<?php if($type=='group_octet')echo 'selected'; ?>>Group-Octet</option>
								 </select><br><br>  
   <b>STUDENT(S) PERFORMING / YEAR IN SCHOOL:</b>
   </br><span style="color:red"><?php echo $nameErr; ?></span></br>
   <span style="color:red"><?php echo $gradeErr; ?></span></br></br>
  &nbsp&nbsp<b>1)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[0];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[0];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[0];?>" placeholder="Grade" style="width:6%">
  &nbsp&nbsp<b>2)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[1];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[1];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[1];?>" placeholder="Grade" style="width:6%"><br><br>
  &nbsp&nbsp<b>3)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[2];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[2];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[2];?>" placeholder="Grade" style="width:6%">
  &nbsp&nbsp<b>4)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[3];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[3];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[3];?>" placeholder="Grade" style="width:6%"><br><br>
  &nbsp&nbsp<b>5)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[4];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[4];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[4];?>" placeholder="Grade" style="width:6%">
  &nbsp&nbsp<b>6)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[5];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[5];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[5];?>" placeholder="Grade" style="width:6%"><br><br>
  &nbsp&nbsp<b>7)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[6];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[6];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[6];?>" placeholder="Grade" style="width:6%">
  &nbsp&nbsp<b>8)</b>&nbsp <input type="text" name="name[]" value="<?php echo $name[7];?>" placeholder="First Name" style="width:14%"><input type="text" name="last[]" value="<?php echo $last_name[7];?>" placeholder="Last Name" style="width:14%">&nbsp&nbsp<input type="text" name="grade[]" value="<?php echo $grade[7];?>" placeholder="Grade" style="width:6%"><br><br>
  
  Music File <b style="background-color:yellow;">(MP3s only): </b><input type="file" name="music_file" accept="audio/*"><span style="color:red"><?php echo $musicErr; ?></span><br><br>
  <?php if ($music_file){?>
  <audio controls>
   <source src="anthem/<?php echo $music_file;?>" type="audio/mp4">
   <source src="anthem/<?php echo $music_file;?>" type="audio/mp3">
   </audio><br><br>
  <?php } ?>
  Reference Letter<b style="background-color:yellow;">(pdf/doc/docx):</b> <input type="file" name="reference_letter" accept=".pdf,.doc, .docx, application/vnd.ms-excel"><span style="color:red"><?php echo $refErr; ?></span><br><br><br>
  <?php if($reference_letter) { ?>
  <a target="_blank" href="anthem/<?php echo $reference_letter; ?>" download>Reference Letter</a><br><br><br>
  <?php } ?>
  <!--Name list: <input type="file" name="name_file" accept=".csv,.txt, application/vnd.ms-excel"><br><br><br>-->
  <b>PLEASE SELECT THE EVENTS YOU ARE AVAILABLE TO SING FOR:</b><br><span style="color:red"><?php echo $sportErr; ?></span><br><br>
  
  <input type="checkbox"   onClick="CheckAllAD()"> Select all<br><br>
  <input type="checkbox" name="sport[]" value="1" id="sport1" <?php if(in_array(1,$sport)) echo 'checked';?>> NSAA CROSS COUNTRY CHAMPIONSHIPS <?php echo $cross_courntry;?><br><br>
  <input type="checkbox" name="sport[]" value="2" id="sport2" <?php if(in_array(2,$sport)) echo 'checked';?>> NSAA VOLLEYBALL CHAMPIONSHIP FINALS <?php echo $volleyball;?><br><br>
  <input type="checkbox" name="sport[]" value="3" id="sport3" <?php if(in_array(3,$sport)) echo 'checked';?>> NSAA FOOTBALL CHAMPIONSHIP FINALS <?php echo $football;?><br><br>
  <input type="checkbox" name="sport[]" value="12" id="sport12" <?php if(in_array(12,$sport)) echo 'checked';?>>NSAA UNIFIED SPORTS, BOWLING CHAMPIONSHIPS <?php echo $unified_bowling;?><br><br>
  <input type="checkbox" name="sport[]" value="6" id="sport6" <?php if(in_array(6,$sport)) echo 'checked';?>> NSAA DUAL WRESTLING CHAMPIONSHIPS <?php echo $duel_wrestling;?><br><br>
  <input type="checkbox" name="sport[]" value="4" id="sport4" <?php if(in_array(4,$sport)) echo 'checked';?>> NSAA WRESTLING CHAMPIONSHIPS <?php echo $wrestling;?><br><br>
  <input type="checkbox" name="sport[]" value="5" id="sport5" <?php if(in_array(5,$sport)) echo 'checked';?>> NSAA SWIMMING & DIVING CHAMPIONSHIP FINALS <?php echo $swimming;?><br><br>
  <input type="checkbox" name="sport[]" value="7" id="sport7" <?php if(in_array(7,$sport)) echo 'checked';?>> NSAA GIRLS BASKETBALL CHAMPIONSHIP FINALS <?php echo $girls_basketball;?><br><br>
  <input type="checkbox" name="sport[]" value="8" id="sport8" <?php if(in_array(8,$sport)) echo 'checked';?>> NSAA BOYS BASKETBALL CHAMPIONSHIP FINALS <?php echo $boys_basketball;?><br><br>
  <input type="checkbox" name="sport[]" value="9" id="sport9" <?php if(in_array(9,$sport)) echo 'checked';?>> NSAA SOCCER CHAMPIONSHIP FINALS <?php echo $soccer;?><br><br>
  <input type="checkbox" name="sport[]" value="10" id="sport10" <?php if(in_array(10,$sport)) echo 'checked';?>> NSAA BOYS BASEBALL CHAMPIONSHIP FINALS <?php echo $boys_baseball;?><br><br>
  <input type="checkbox" name="sport[]" value="11" id="sport11" <?php if(in_array(11,$sport)) echo 'checked';?>> NSAA TRACK & FIELD CHAMPIONSHIPS <?php echo $track_field;?><br><br>
  <input type="hidden" name="id" value="<?php echo $id;?>">
  
  <input type="submit" value="Submit" id="submit">
  <input type="hidden" name="session" value="<?php echo $session; ?>" >
</form>

<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>

<script type="text/javascript">
$(document).ready(function() { 
	var options = { 
			target:   '#output',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,  // post-submit callback 
			uploadProgress: OnProgress, //upload progress callback 
			resetForm: true        // reset the form after successful submit 
		}; 
		
	 $('#MyUploadForm').submit(function() { 
			$(this).ajaxSubmit(options);  			
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		}); 
		

//function after succesful file upload (when server response)
function afterSuccess()
{
	$('#submit-btn').show(); //hide submit button
	$('#loading-img').hide(); //hide submit button
	$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar

}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#FileInput').val()) //check empty input filed
		{
			$("#output").html("Are you kidding me?");
			return false
		}
		
		var fsize = $('#FileInput')[0].files[0].size; //get file size
		var ftype = $('#FileInput')[0].files[0].type; // get file type
		

		//allow file types 
		switch(ftype)
        {
            case 'image/png': 
			case 'image/gif': 
			case 'image/jpeg': 
			case 'image/pjpeg':
			case 'text/plain':
			case 'text/html':
			case 'application/x-zip-compressed':
			case 'application/pdf':
			case 'application/msword':
			case 'application/vnd.ms-excel':
			case 'video/mp4':
			case 'video/x-ms-wmv':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
				return false
        }
		
		//Allowed file size is less than 5 MB (1048576)
		if(fsize>52428800) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
			return false
		}
				
		$('#submit-btn').hide(); //hide submit button
		$('#loading-img').show(); //hide submit button
		$("#output").html("");  
	}
	else
	{
		//Output error to older unsupported browsers that doesn't support HTML5 File API
		$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

//progress bar function
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
	$('#progressbox').show();
    $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
    $('#statustxt').html(percentComplete + '%'); //update status text
    if(percentComplete>50)
        {
            $('#statustxt').css('color','#000'); //change status text to white after 50%
        }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

}); 
function CheckAllAD()
{ 
   var varname="<?php echo 'sport1'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport2'; ?>";
   document.getElementById(varname).checked=true;   
   var varname="<?php echo 'sport3'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport4'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport5'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport6'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport7'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport8'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport9'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport10'; ?>";
   document.getElementById(varname).checked=true;
   var varname="<?php echo 'sport11'; ?>";
   document.getElementById(varname).checked=true;   
   var varname="<?php echo 'sport12'; ?>";
   document.getElementById(varname).checked=true; 
}
</script>
<!--<link href="style/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="upload-wrapper">
<div align="center">
<h3>Ajax File Uploader</h3>
<form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
<input name="FileInput" id="FileInput" type="file" />
<input type="submit"  id="submit-btn" value="Upload" />
<img src="images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
</form>
<div id="progressbox" ><div id="progressbar"></div ><div id="statustxt">0%</div></div>
<div id="output"></div>
</div>
</div>

</body>
</html>-->