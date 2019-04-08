<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
/* if(!ValidUser($session) )
{
   header("Location:index.php?error=3");
   exit();
} */
 if (!empty($_GET[school])){
 $school = preg_replace('/\.[^.]+$/','',$_GET[school]);
  }
 if($school)
 //$sql="SELECT * FROM eligibility WHERE school='".mysql_real_escape_string($_GET[school])."' AND (fb68='x' OR fb11='x' OR vb='x' OR sb='x' OR cc='x' OR te='x' OR bb='x' OR wr='x' OR sw='x' OR go='x' OR tr='x' OR ba='x' OR so='x' OR ch='x' OR sp='x' OR pp='x' OR de='x' OR im='x' OR vm='x' OR jo='x' OR ubo='x') order by first";
 $sql="SELECT * FROM eligibility WHERE school='".mysql_real_escape_string($_GET[school])."' order by first";
 else
 //$sql="SELECT * FROM eligibility WHERE fb68='x' OR fb11='x' OR vb='x' OR sb='x' OR cc='x' OR te='x' OR bb='x' OR wr='x' OR sw='x' OR go='x' OR tr='x' OR ba='x' OR so='x' OR ch='x' OR sp='x' OR pp='x' OR de='x' OR im='x' OR vm='x' OR jo='x' OR ubo='x' order by first";
 $sql="SELECT * FROM eligibility order by first";
 $result_name=mysql_query($sql);
 
 $sql1="SELECT school FROM headers ORDER BY school";
 $result1=mysql_query($sql1);
 
 $sql_question="SELECT * FROM believers_duedates where  id=1 ";
 $result_question=mysql_query($sql_question);
 $row_question=mysql_fetch_array($result_question);
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") { 
 
 foreach($_POST as $key=>$value) 
 
	if(!is_array($value))
			$_POST[$key]=mysql_real_escape_string($value);
			//$_POST[$key]=addslashes($value);
			//{$_POST[$key]=preg_replace("/'/", "\\'", $value);
            //$_POST[$key]=preg_replace("/\"/", "\\\"", $value);}
			
	//get_magic_quotes_gpc()
 $school_=$school;	
 $name=$_POST['name'];
 $gender=$_POST['gender'];
 $race=$_POST['race'];
 $school = $_POST['school'];
 $street=$_POST['street'];
 $city=$_POST['city'];
 $zip=$_POST['zip'];
 $cell=$_POST['cell'];
 $email=$_POST['email'];
 $submitted=$_POST['submitted'];
 $title=$_POST['title'];
 $class=$_POST['class'];
 $average=$_POST['average'];
 $list=$_POST['list'];
 $activity=implode(',',$_POST['activity']);
 
 $award=$_POST['award'];
 $activity1=$_POST['activity1'];
 $office1=$_POST['office1'];
 $length1=$_POST['length1'];
 $time1=$_POST['time1'];
 $activity2=$_POST['activity2'];
 $office2=$_POST['office2'];
 $length2=$_POST['length2'];
 $time2=$_POST['time2'];
 $activity3=$_POST['activity3'];
 $office3=$_POST['office3'];
 $length3=$_POST['length3'];
 $time3=$_POST['time3'];
 $activity4=$_POST['activity4'];
 $office4=$_POST['office4'];
 $length4=$_POST['length4'];
 $time4=$_POST['time4'];
 $c_activity1=$_POST['c_activity1'];
 $c_office1=$_POST['c_office1'];
 $c_length1=$_POST['c_length1'];
 $c_time1=$_POST['c_time1'];
 $c_activity2=$_POST['c_activity2'];
 $c_office2=$_POST['c_office2'];
 $c_length2=$_POST['c_length2'];
 $c_time2=$_POST['c_time2'];
 $c_activity3=$_POST['c_activity3'];
 $c_office3=$_POST['c_office3'];
 $c_length3=$_POST['c_length3'];
 $c_time3=$_POST['c_time3'];
 $c_activity4=$_POST['c_activity4'];
 $c_office4=$_POST['c_office4'];
 $c_length4=$_POST['c_length4'];
 $c_time4=$_POST['c_time4'];
 $essay=$_POST['essay'];
 $parent_name=$_POST['parent_name'];  
 $parent_email=$_POST['parent_email'];
 
 
	if (empty($_POST["name"])){
	$nameErr = "Field is required";
	} 
	if ($id) {   
	if((!empty($_POST["name"])) && (!empty($_POST["school"]))){
	$school = $school;
    $sql="UPDATE believers SET name='$name',gender='$gender',race='$race',school='$school',street='$street',city='$city',zip='$zip',cell='$cell',email='$email',submitted='$submitted',title='$title',class='$class',average='$average',list='$list',activity='$activity',award='$award',activity1='$activity1',office1='$office1',length1='$length1',time1='$time1',activity2='$activity2',office2='$office2',length2='$length2',time2='$time2',activity3='$activity3',office3='$office3',length3='$length3',time3='$time3',activity4='$activity4',office4='$office4',length4='$length4',time4='$time4', c_activity1='$c_activity1',c_office1='$c_office1',c_length1='$c_length1',c_time1='$c_time1',c_activity2='$c_activity2',c_office2='$c_office2',c_length2='$c_length2',c_time2='$c_time2',c_activity3='$c_activity3',c_office3='$c_office3',c_length3='$c_length3',c_time3='$c_time3',c_activity4='$c_activity4',c_office4='$c_office4',c_length4='$c_length4',c_time4='$c_time4',essay='$essay',parent_name='$parent_name',parent_email='$parent_email'  WHERE id='$id'";  
	$result=mysql_query($sql);
	
	
	
	
	
	
	//var_dump();
	 
	if(mysql_error()!='')
		citgf_file_put_contents('logs.txt', "\n".$sql. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX);
	
	if (!empty($_FILES["documentUpload"]["name"])){
	$document = rand().$_FILES["documentUpload"]["name"]; 
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/believers/"; 
	$target_file = $target_dir . basename($document);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["documentUpload"]["tmp_name"], $target_file);
	$sql1="UPDATE believers SET document='$document' WHERE id='$id'";
    $result=mysql_query($sql1);
	}
	
	
	if(!empty($_FILES["imageUpload"]["name"])){
	$image = rand().$_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/believers/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	 $sql1="UPDATE believers SET image='$image' WHERE id='$id'"; 
    $result=mysql_query($sql1);
	}
    header("Location:believers_success.php?session=$session&school=$school_");
  exit();
	}
	}
	else
	{ 
	if((!empty($_POST["name"])) && (!empty($_POST["school"]))){
	if(!empty($_FILES["documentUpload"]["name"])){
	$document = rand().$_FILES["documentUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/believers/";
	$target_file = $target_dir . basename($document);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["documentUpload"]["tmp_name"], $target_file);
	$document = mysql_real_escape_string($document);
	}
/* 	else{
	if(!$id){
	$refErr = 'Document file is required';
	}
	}
	if (($refFileType != 'pdf') || ($refFileType != 'doc') || ($refFileType != 'docx')){
	$refErr = 'You can upload only doc, pdf or docx file';
	} */
	
	if(!empty($_FILES["imageUpload"]["name"])){
	$image = rand().$_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/believers/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	}
/* 	else{
	if(!$id){
	$refErr = 'Image file is required';
	}
	}
	if (($refFileType != 'pdf') || ($refFileType != 'doc') || ($refFileType != 'docx')){
	$refErr = 'You can upload only doc, pdf or docx file';
	} */
  $school = $_POST['school'];
  $sql2="INSERT INTO believers (name,gender,race,school,street,city,zip,cell,email,submitted,title,class,average,list,activity,award,activity1,office1,length1,time1,activity2,office2,length2,time2,activity3,office3,length3,time3,activity4,office4,length4,time4,c_activity1,c_office1,c_length1,c_time1,c_activity2,c_office2,c_length2,c_time2,c_activity3,c_office3,c_length3,c_time3,c_activity4,c_office4,c_length4,c_time4,essay,parent_name,parent_email,document,image,question19) VALUES ('$name','$gender','$race','".$school."','$street','$city','$zip','$cell','$email','$submitted','$title','$class','$average','$list','$activity','$award','$activity1','$office1','$length1','$time1','$activity2','$office2','$length2','$time2','$activity3','$office3','$length3','$time3','$activity4','$office4','$length4','$time4','$c_activity1','$c_office1','$c_length1','$c_time1','$c_activity2','$c_office2','$c_length2','$c_time2','$c_activity3','$c_office3','$c_length3','$c_time3','$c_activity4','$c_office4','$c_length4','$c_time4','$essay','$parent_name','$parent_email','$document','$image','$row_question[question]')"; 
  //echo '<pre>'; print_r($_POST); exit;
  $result2=mysql_query($sql2);
  
  	if(mysql_error()!='')
		citgf_file_put_contents('logs.txt', "\n".$sql2. "\n"."\n". mysql_error().PHP_EOL , FILE_APPEND | LOCK_EX);
  
  header("Location:believers_success.php?session=$session&school=$school_");
  exit();
  }
  }
 }
  if (isset($_GET['id'])) {

   $sql="SELECT * FROM believers WHERE id =$_GET[id]";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result); 
   $name =$row[name];
   $id =$row[id];
   $gender =$row[gender];
   $race =$row[race];
   $school = $row[school];
   $street =$row[street];
   $get_school = mysql_real_escape_string($row[school]);
   $sql_address="SELECT * FROM headers WHERE school ='".$get_school."'"; 
   $result_address=mysql_query($sql_address);
   $row_address=mysql_fetch_array($result_address); 
   $address =$row_address[address1].' '.$row_address[address2].' '.$row_address[city_state].'  '.$row_address[zip];
   $city =$row[city];
   $zip =$row[zip];
   $cell =$row[cell];
   $email =$row[email];
   $submitted =$row[submitted];
   $title =$row [title];
   $class =$row ['class'];
   $average =$row [average];
   $list =$row ['list'];
   $activity =explode(',',$row [activity]);
   $award =$row [award];
   $activity1 =$row [activity1];
   $office1 =$row [office1];
   $length1 =$row [length1];
   $time1 =$row [time1];
   $activity2 =$row [activity2];
   $office2 =$row [office2];
   $length2 =$row [length2];
   $time2 =$row [time2];
   $activity3 =$row [activity3];
   $office3 =$row [office3];
   $length3 =$row [length3];
   $time3 =$row [time3];
   $activity4 =$row [activity4];
   $office4 =$row [office4];
   $length4 =$row [length4];
   $time4 =$row [time4];
   $c_activity1 =$row [c_activity1];
   $c_office1 =$row [c_office1];
   $c_length1 =$row [c_length1];
   $c_time1 =$row [c_time1];
   $c_activity2 =$row [c_activity2];
   $c_office2 =$row [c_office2];
   $c_length2 =$row [c_length2];
   $c_time2 =$row [c_time2];
   $c_activity3 =$row [c_activity3];
   $c_office3 =$row [c_office3];
   $c_length3 =$row [c_length3];
   $c_time3 =$row [c_time3];
   $c_activity4 =$row [c_activity4];
   $c_office4 =$row [c_office4];
   $c_length4 =$row [c_length4];
   $c_time4 =$row [c_time4];
   $essay =$row [essay];
   $document =$row [document];
   $image =$row [image];
   $parent_name =$row [parent_name];
   $parent_email =$row [parent_email];
   $question19 =$row [question19];

   //echo '<pre>';print_r($date); exit;
}
	$sql_="SELECT duedate FROM believers_duedates WHERE id=1 ";
	$result_=mysql_query($sql_);
	$row_=mysql_fetch_array($result_);
	
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
<table  width="90%" cellspacing="0" cellpadding="0" >

 <form method="post" action="believers.php" enctype="multipart/form-data">

   <tr align="center"><td colspan="3"><h2>U.S. BANK BELIEVERS & ACHIEVERS APPLICATION</h2></td></tr>
   <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>APPLICANT INFORMATION</h3></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td width="32%"><h4>1. Applicant Name:</h4>
   </td><td >
   <select name="name">
   <option ></option>
   <?php while($row=mysql_fetch_array($result_name)){  ?>
   <option <?php if($name==$row['first'].' '.$row['last']) echo 'selected';?> ><?php echo $row['first'].' '.$row['last'];?></option>
   <?php } ?>
   </select><td></tr>
   <!--<input type="text"; name="name" id="tags1" value="<?php echo $name; ?>">--><input type="hidden"; name="session" value="<?php echo $session; ?>"><input type="hidden"; name="id" value="<?php echo $id; ?>"></td></tr>
   <tr style="height:5px"><td></td><td ><span style="color:red"><?php if(!empty($nameErr)) echo $nameErr; ?></span></td></tr>
   <tr ><td><h4>2. Gender:</h4></td>
   <td colspan="2"><table><tr>
   <td><input type="radio" name="gender" value="female" <?php if($gender=='female')echo 'checked'; ?>> Female</td>
   <td><input type="radio" name="gender" value="male" <?php if($gender=='male')echo 'checked'; ?>> Male<br></td>
   </tr></table></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td  ><h4 >3. Please specify the applicant's ethnicity or race: </h4></td>
   <td colspan="2"><table><tr>
   <td><input type="radio" name="race" value="white" <?php if($race=='white')echo 'checked'; ?>> White</td>
   <td><input type="radio" name="race" value="hispanic" <?php if($race=='hispanic')echo 'checked'; ?>> Hispanic or Latino</td>
   <td><input type="radio" name="race" value="black" <?php if($race=='black')echo 'checked'; ?>> Black or African American</td>
   <td><input type="radio" name="race" value="inidan" <?php if($race=='inidan')echo 'checked'; ?>> Native American or American Indian</td>
   <td><input type="radio" name="race" value="asian" <?php if($race=='asian')echo 'checked'; ?>> Asian/Pacific Island</td>
   <td><input type="radio" name="race" value="other" <?php if($race=='other')echo 'checked'; ?>> Other<br></td></tr>
   </table></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>4. School</h4></td><td ><input type="text"; name="school" id="tags" value="<?php echo $school; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>5. Applicant Home Address</h4></td></tr>
   <tr><td ><h4 style="margin-left:10px">Street Address:</h4></td><td><input type="text" name="street" value="<?php echo $street; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4 style="margin-left:10px">City: </h4></td><td><input type="text" name="city" value="<?php echo $city; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4 style="margin-left:10px">Zip Code: </h4></td><td><input type="text" name="zip" value="<?php echo $zip; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>6. Applicant Cell Phone: </h4></td><td><input type="text" name="cell" value="<?php echo $cell; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>7. Applicant Email Address: </h4></td><td><input type="text" name="email" value="<?php echo $email; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>8. Parent/Guardian Name(s): </h4></td><td><input type="text" name="parent_name" value="<?php echo $parent_name; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>9. Parent/Guardian email address: </h4></td><td><input type="text" name="parent_email" value="<?php echo $parent_email; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>10. Person Submitting Application: </h4></td><td><input type="text" name="submitted" value="<?php echo $submitted; ?>"><br></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>11. Title of person submitting application:</h4></td>
   <td colspan="2"><table><tr>
   <td><input type="radio" name="title" value="superintendent" <?php if($title=='superintendent')echo 'checked'; ?>> Superintendent</td>
   <td><input type="radio" name="title" value="principal" <?php if($title=='principal')echo 'checked'; ?>> Principal</td>
   <td><input type="radio" name="title" value="ad" <?php if($title=='ad')echo 'checked'; ?>> Athletic/Activity Director</td>
   <td><input type="radio" name="title" value="counselor" <?php if($title=='counselor')echo 'checked'; ?>> Guidance Counselor</td>
   </tr></table></td></tr>
   <tr style="height:5px"><td ></td></tr>

   <tr><td><h4>12. NSAA Classification(for Track & Field):</h4></td>
   <td><table><tr>
   <td><input type="radio" name="class" value="a" <?php if($class=='a')echo 'checked'; ?>> A</td>
   <td><input type="radio" name="class" value="b" <?php if($class=='b')echo 'checked'; ?>> B</td>
   <td><input type="radio" name="class" value="c" <?php if($class=='c')echo 'checked'; ?>> C</td>
   <td><input type="radio" name="class" value="d" <?php if($class=='d')echo 'checked'; ?>> D</td>
   </tr></table></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>Scholastic Achievement</h3></td></tr> 
   <tr style="height:5px"><td ></td></tr>
   <tr><td colspan="3"><h4>Individuals must have a cumulative grade point average of 3.75(on an unweighted 4.0 scale) or higher. 
   All academic classes that the student has taken since entering grade nine, and which count toward fulfillment
   of the school's graduation requirements are to be used in determining the grade point average. The grade point 
   average will be calculated through the second semester of the nominee's junior year. Grade point averages are
   to be rounded off to the nearest hundredth (example:3.756 = 3.76)</h4></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>13. Cumulative Grade Point Average on an Unweighted Scale</h4></td>
   <td><input type="text" name="average" value="<?php echo $average; ?>"></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td colspan="3"><h4>14. List Academic Honors and Awards (e.g. 2017 NCPA Academic All-state, Nationa Honor-society, Honor Roll, Academic Letter, etc)</h4></td></tr>
   <tr><td></td><td><textarea name="list"  rows="10" cols="50"><?php echo $list; ?></textarea> </td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>NSAA Activity Participation</h3></td></tr>
   <tr><td colspan="3"><h4>Activities sanctioned by the NSAA are listed. A requirement of this award is the student must
   participant in an NSAA sanctioned activity. Achievement or recognition attained by participation in 
   non-NSAA sanctioned activities are prohibited in this category (but can be listed in community involvement).
   Outstanding achievement does not have to be primary factor. An individual serving as a student manager or
   member of a stage crew can be judged on his/her commitment and positive contributions to the activity.</h4></td></tr>
   <tr style="height:5px"><td ></td></tr>
   
   <tr><td colspan="3"><h4 >15. NSAA Activity Participation:(Check all activities participated in as a junior)</h4></td></tr>
   
   <tr><td><input type="checkbox" name="activity[]" value="cross_country" <?php if(in_array('cross_country',$activity)) echo 'checked';?>> Cross County</td><td width="32%"><input type="checkbox" name="activity[]" value="swimming" <?php if(in_array('swimming',$activity)) echo 'checked';?>> Swimming & Diving</td><td><input type="checkbox" name="activity[]" value="play" <?php if(in_array('play',$activity)) echo 'checked';?>> Play Production<br></td></tr>
   <tr><td><input type="checkbox" name="activity[]" value="boys_tenis" <?php if(in_array('boys_tenis',$activity)) echo 'checked';?>> Boys Tennis</td><td><input type="checkbox" name="activity[]" value="basketball" <?php if(in_array('basketball',$activity)) echo 'checked';?>> Basketball</td><td><input type="checkbox" name="activity[]" value="speech" <?php if(in_array('speech',$activity)) echo 'checked';?>> Speech<br></td></tr>
   <tr><td><input type="checkbox" name="activity[]" value="girls_golf" <?php if(in_array('girls_golf',$activity)) echo 'checked';?>> Girls Golf</td><td><input type="checkbox" name="activity[]" value="baseball" <?php if(in_array('baseball',$activity)) echo 'checked';?>> Baseball</td><td><input type="checkbox" name="activity[]" value="debate" <?php if(in_array('debate',$activity)) echo 'checked';?>> Debate<br></td></tr>
   <tr><td><input type="checkbox" name="activity[]" value="softball" <?php if(in_array('softball',$activity)) echo 'checked';?>> Softball</td><td><input type="checkbox" name="activity[]" value="boys_golf" <?php if(in_array('boys_golf',$activity)) echo 'checked';?>> Boys Golf</td><td><input type="checkbox" name="activity[]" value="journalism" <?php if(in_array('journalism',$activity)) echo 'checked';?>> Journalism<br></td></tr>
   <tr><td><input type="checkbox" name="activity[]" value="football" <?php if(in_array('football',$activity)) echo 'checked';?>> Football</td><td><input type="checkbox" name="activity[]" value="girls_tennis" <?php if(in_array('girls_tennis',$activity)) echo 'checked';?>> Girls Tennis</td><td><input type="checkbox" name="activity[]" value="music" <?php if(in_array('music',$activity)) echo 'checked';?>> Music<br></td></tr>
   <tr><td><input type="checkbox" name="activity[]" value="volleyball" <?php if(in_array('volleyball',$activity)) echo 'checked';?>> Volleyball</td><td><input type="checkbox" name="activity[]" value="track" <?php if(in_array('track',$activity)) echo 'checked';?>> Track & Field</td><td><input type="checkbox" name="activity[]" value="unified_bowling" <?php if(in_array('unified_bowling',$activity)) echo 'checked';?>> Unified Bowling<br></td></tr>
   <tr><td><input type="checkbox" name="activity[]" value="wreslting" <?php if(in_array('wreslting',$activity)) echo 'checked';?>> Wreslting</td><td><input type="checkbox" name="activity[]" value="soccer" <?php if(in_array('soccer',$activity)) echo 'checked';?>> Soccer<br></td></tr>
   <tr style="height:5px"><td ></td></tr>
    <tr><td colspan="3"><h4>16. List Awards from NSAA Activities (e.g. 2016 NSAA State Girls Golf Champion, Member of the 2016 NSAA Class C2 Girls Basketball
	Championship 4th Place Team, 2016 NSAA Class C2 State Speech 3rd Place Medalist in Serious Prose, etc)</h4></td></tr>
    <tr><td></td><td><textarea name="award"  rows="10" cols="50"><?php echo $award; ?></textarea> </td></tr>
    <tr style="height:5px"><td ></td></tr>
    <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>School Involvement</h3></td></tr>
	<tr style="height:5px"><td ></td></tr>
	<tr><td colspan="3"><h4>Involvement in clubs or organizations, volunteerism in school programs, support of activities other than those in which applicant participates,
	and non-NSAA activities during applicant's high school career.</h4></td></tr>
    
    <tr><td><h4>17. School Involvement-List Top 4</h4></td></tr>
    <tr><td><h4># 1 Group/Club Activity: </h4></td><td><input type="text" name="activity1" value="<?php echo $activity1; ?>">	</td></tr><br>
	<tr style="height:5px"><td ></td></tr>
    <tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="office1" value="<?php echo $office1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="length1" value="<?php echo $length1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="time1" value="<?php echo $time1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 2 Group/Club Activity: </h4></td><td><input type="text" name="activity2" value="<?php echo $activity2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="office2" value="<?php echo $office2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="length2" value="<?php echo $length2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="time2" value="<?php echo $time2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 3 Group/Club Activity: </h4></td><td><input type="text" name="activity3" value="<?php echo $activity3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="office3" value="<?php echo $office3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="length3" value="<?php echo $length3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="time3" value="<?php echo $time3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 4 Group/Club Activity: </h4></td><td><input type="text" name="activity4" value="<?php echo $activity4; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="office4" value="<?php echo $office4; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement:</h4> </td><td><input type="text" name="length4" value="<?php echo $length4; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="time4" value="<?php echo $time4; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	
	<tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>Community Involvement</h3></td></tr>
	<tr style="height:5px"><td ></td></tr>
	<tr><td colspan="3"><h4>Involvement and volunteerism in community organizations, youth groups and programs during the applicant's high school career.</h4></td></tr>
    <tr style="height:5px"><td ></td></tr>
    <tr><td><h4>18. Community Involvement-List Top 4 </h4></td></tr>
	<tr style="height:5px"><td ></td></tr>
    <tr><td><h4># 1 Group/Club Activity: </h4></td><td><input type="text" name="c_activity1" value="<?php echo $c_activity1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="c_office1" value="<?php echo $c_office1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="c_length1" value="<?php echo $c_length1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="c_time1" value="<?php echo $c_time1; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 2 Group/Club Activity: </h4></td><td><input type="text" name="c_activity2" value="<?php echo $c_activity2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="c_office2" value="<?php echo $c_office2; ?>"><br></td></tr>	
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="c_length2" value="<?php echo $c_length2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="c_time2" value="<?php echo $c_time2; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 3 Group/Club Activity: </h4></td><td><input type="text" name="c_activity3" value="<?php echo $c_activity3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="c_office3" value="<?php echo $c_office3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="c_length3" value="<?php echo $c_length3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="c_time3" value="<?php echo $c_time3; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 4 Group/Club Activity: </h4></td><td><input type="text" name="c_activity4" value="<?php echo $c_activity4; ?>"><br></td></tr>	
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><input type="text" name="c_office4" value="<?php echo $c_office4; ?>"><br></td></tr>	
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><input type="text" name="c_length4" value="<?php echo $c_length4; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><input type="text" name="c_time4" value="<?php echo $c_time4; ?>"><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	
	<tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>Citizenship Essay</h3></td></tr>
	<tr><td colspan="2"><h4>The applicant shall respond to the following in 250-300 words.</h4></td></tr>
	<?php if(!empty($_GET[school]) && empty($_GET[id])) { ?>
	<tr><td colspan="3"><h4>19.<?php echo ' '.$row_question[question];?></h4></td></tr>
	<?php } elseif(!empty($_GET[id])) {?>
	<tr><td colspan="3"><h4>19. <?php echo ' '.$question19;?></h4></td></tr>
	<?php } ?>
	<tr><td></td><td><textarea name="essay"  rows="15" cols="100"><?php echo $essay; ?></textarea></td></tr> 
	<tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Upload Essay:</td><td>
    <input type="file" name="documentUpload" id="fileToUpload1"><br><br></td>
	<?php if (!empty($document)){?>
    <td> <a href="believers/<?php echo $document; ?>" target="_blank"><?php echo $document; ?></a></td>
	<?php } ?>
	</tr>
	<tr><td>Please upload a photo of the applicant<td></tr>
	<tr><td><h4>Upload Image:</td><td>
    <input type="file" name="imageUpload" id="fileToUpload2"><br><br></td>
	<?php if (!empty($image)){?>
    <td> <a href="believers/<?php echo $image; ?>" target="_blank"><?php echo $image; ?></a></td>
	<?php } ?>
	</tr>
	<tr style="height:10px"><td ></td></tr>
	<?php if ($row_[duedate]>date("Y-m-d")){?>
	<tr align="center"><td colspan="3"><input type="submit" value="submit" name="submit"></td></tr>
	<?php } ?>
	<tr style="height:10px"><td ></td></tr>
</form>

</table>
  <script>

  $( function() {
    var availableTags = [
<?php  
 while($row=mysql_fetch_array($result1)){  
   echo '"'.mysql_real_escape_string($row[school]).'",';}
?>
    ];
	
    $( "#tags" ).autocomplete({
	     maxResults: 20,
        source: function(request, response) {
        var results = $.ui.autocomplete.filter(availableTags, request.term);
        response(results.slice(0, this.options.maxResults));
		    }
        });
      //source: availableTags
    });
  //});
/* function test(filter){
    document.getElementById("tag").value=filter;
    //xajax call here
}   */

  </script>
    <script>
  $( function() {
    //var availableTags1 = [
<?php  
 while($row1=mysql_fetch_array($result)){  
   echo '"'.mysql_real_escape_string($row1[first]).''.mysql_real_escape_string($row1[last]).'",';}
?>
    ];
	
    $( "#tags1" ).autocomplete({
	  maxResults: 20,
      source: function(request, response) {
      var results = $.ui.autocomplete.filter(availableTags1, request.term);
      response(results.slice(0, this.options.maxResults));
	}
	  //source: availableTags1
    });
  } );

  </script>
      <script>
$(document).ready(function() {
  $('input').keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
  </script>