<?php
/*******************************************
statcontesteentry.php
Director/AD uploads files for State JO
Contest Entries - $maxentries per category
Created 3/30/18
Author: criticalitgroup
*******************************************/

require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';
session_start();
$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
/* if(!$sid)
{
   $sid=GetSID2(GetSchool($session),'jo');
}
else
{
   if(!$sid || $sid=="") $sid=GetSID2("Test's School",'jo');
} */
/* $school=GetMainSchoolName($sid,'jo');
$school2=ereg_replace("\'","\'",$school); */
//$studs=explode("<result>",GetPlayers('jo',$school));
//echo'<pre>'; print_r($_POST); exit;
/* if (!empty($sid))
{
  for ($i=1;$i<13;$i++){
  $studs[$i]=explode("<result>",GetPlayers('jo',GetSchoolName($sid[$i],'jo')));
  }
} */
//echo '<pre>'; print_r($studs); 
/* $duedate=GetDueDate('jo_contest');
$date=explode("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0])); */
//SHOW MARCH 1, EVEN IF DUE DATE IS AFTER THAT
//$duedate2=date("F j, Y",mktime(0,0,0,3,1,$date[0]));

//GET DATA SPECIFIC TO THE SELECTED CATEGORY/EVENT
if($catid )
{
   $sql="SELECT * FROM jostatecategories WHERE catid='$catid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $max=$row[maxstudents];
   $maxfiles=$row[maxfiles];
   $maxentries=$row[maxentries];
   if($maxfiles>1) $filetype="JPG";     //PHOTOS
   else $filetype="PDF";                //PDF's
}

if(PastDue($duedate,0) && $level!=1) 
{
   //unset($catid);
   $editable=0;
}
else $editable=1;


if($_POST['save'] || $upload && $catid)	//Saved the screen with entries for an event
{  
   $usedstudids=array(); $s=0;
   $saveerrors="";
   for($i=1;$i<=count($entryid);$i++)
   {
	//Check to make sure that for single-student entries ($max=1)
	//the student has not already been entered in this event
      $curstudlist="";
      for($j=1;$j<=count($studentid[$i]);$j++)
      {
	 if($studentid[$i][$j])	
	    $curstudlist.=$studentid[$i][$j].",";
      }
      if($curstudlist!='') $curstudlist=substr($curstudlist,0,strlen($curstudlist)-1);
      if(!in_array($curstudlist,$usedstudids) || $curstudlist=="")
      {
         $sql="UPDATE jostateentries SET ";
         for($j=1;$j<=count($studentid[$i]);$j++)
         {
            if($j==1) $sql.="studentid='".$studentid[$i][$j]."',";
            else $sql.="studentid".$j."='".$studentid[$i][$j]."',";
	        $usedstudids[$s]=$curstudlist; $s++;
         }
         //$sql.="label='".addslashes($label[$i])."' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'";
         $sql.="label='".addslashes($label[$i])."',sid='$sid[$i]' WHERE id='$entryid[$i]' AND  catid='$catid'";
		 //echo $sql ; exit;
         $result=mysql_query($sql);

		 if(!empty($_FILES["docfile"]["name"][$i])){  
				$image = rand(0,10000).$_FILES["docfile"]["name"][$i];
				$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/"; 
				 $target_file = $target_dir . basename($image); 
				$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				citgf_moveuploadedfile($_FILES["docfile"]["tmp_name"][$i], $target_file);
				$image = mysql_real_escape_string($image);
			    $sql="UPDATE jostateentries SET filename='$image' WHERE id='$entryid[$i]' AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}
		 if(!empty($_FILES["docfile2"]["name"][$i])){  
				$image = rand(0,10000).$_FILES["docfile2"]["name"][$i];
				$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
				$target_file = $target_dir . basename($image);
				$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				citgf_moveuploadedfile($_FILES["docfile2"]["tmp_name"][$i], $target_file);
				$image = mysql_real_escape_string($image);
			    $sql="UPDATE jostateentries SET filename2='$image' WHERE id='$entryid[$i]'  AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}	
		if(!empty($linkurl[$i])){  
				$image = mysql_real_escape_string($linkurl[$i]);
			    $sql="UPDATE jostateentries SET filename='$image' WHERE id='$entryid[$i]'  AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}	
		if(!empty($linkurl2[$i])){  
				$image = mysql_real_escape_string($linkurl[$i]);
			    $sql="UPDATE jostateentries SET filename2='$image' WHERE id='$entryid[$i]' AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}	
         if($maxfiles>1)
         {
            $sql="UPDATE jostateentries SET ";
            $sql.="label2='".addslashes($label2[$i])."' WHERE id='$entryid[$i]'  AND catid='$catid'";
            $result=mysql_query($sql);
         }
      } //END IF student has not already been entered in this event
      else	//SHOW ERROR
      {
         if($max==1)
            $saveerrors.="<p>".GetStudentInfo($studentid[$i][1],FALSE)." has already been entered in this event. You cannot enter more than one entry per student per event.</p>";
	 else
	    $saveerrors.="<p>You have entered the same set of students for more than one entry. You cannot submit more than one entry for the same set of students.</p>";
      }
   }
   $saved=1;
/*    header("Location:../statecontestentry1.php?session=$session&catid=$catid");
   exit(); */
   
}
else $saved=0;

echo $init_html;
echo $header;

echo "<br>";
if($level==1)
   echo "<p><a href=\"statecontestentries.php?session=$session\">Return to JO Contest Entry Submissions</a></p>";

echo "<form method='post' action='entrybyclass.php' enctype='multipart/form-data'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"catid\" value=\"$catid\">";
echo "<input type=hidden name=\"class\" value=\"$class\">";
echo "<h2>State Journalism Contest Entry Submission for <u>".GetSchoolName($sid,'jo')."</u>:</h2>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";
echo "<caption>";
if(PastDue($duedate,0))
    echo "<div class='alert'><b>This form's due date has now passed.</b><br>Submissions were due on <b><u>$duedate2</u></b> at midnight. You can no longer make changes to your submissions.</div>";
else
   echo "<div class='alert'>Submissions are due on <b><u>$duedate2</u></b> at midnight. You will not be able to change or add submissions after this date.</div>";
   echo "<b>Class: $class </b>&nbsp;&nbsp;&nbsp;&nbsp;";

   echo "<b>Category:</b> <select name=\"catid\" onChange=\"submit();\"><option value='0'>";
//SEE IF THEY'VE SUBMITTED ANYTHING FIRST
$sql="SELECT * FROM jostateentries WHERE class='$class' AND catid=$catid";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0) echo "Select Category";
else echo "Summary of ALL Entries";
echo "</option>";
$sql="SELECT * FROM jostatecategories ORDER BY category";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($catid==$row[id]) echo " selected";
   echo ">$row[category]</option>";
}
echo "</select>"; 
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"statecategories.php?session=$session\">Insert Category</a>";
//echo '<pre>';print_r($studs[2]); exit;
if($catid)
{   if (empty($max))$max=1;
   //CHECK FOR ERRORS
   $errors=GetJOEntryErrors($sid,$catid);
   if($errors!='')
      echo "<br><div class='error'><b>The following ERRORS were found for this event:</b>$errors $saveerrors</div>";
   else if($saveerrors!='')
      echo "<br><div class='error'><b>The following ERROR occurred and some information was not saved:</b>$saveerrors</div>";
   else if($saved==1)	//SUCCESSFUL SAVE WITH NO ERRORS
      echo "<br><div class='alert' style='width:300px;text-align:center;'>Your changes have been saved.</div>";
   if($maxfiles>1)
   {
      if($maxentries>1)
         echo "<p>For each of the maximum $maxentries entries below, select the <b>School(s)</b>,<b>Student(s)</b> and then upload <b>$maxfiles $filetype Files</b>, giving each a <b>descriptive Label.</b></p>";
      else
         echo "<p>Select the <b>School(s)</b>,<b>Student(s)</b> and then upload <b>$maxfiles $filetype Files</b>, giving each a <b>descriptive Label.</b></p>";
   }
   else if($maxentries>1)
      echo "<p>For each of the maximum $maxentries entries below, select the <b>School(s)</b>,<b>Student(s)</b>, enter a <b>descriptive Label</b> for the student's submission, and <b>Upload the $filetype file</b>.</p>";
   else
      echo "<p>Select the <b>School(s)</b>,<b>Student(s)</b>, enter a <b>descriptive Label</b>, and <b>Upload the $filetype file</b>.</p>";
   echo "<p style=\"color:#ff0000;font-weight:bold;\">Be sure to click \"Save Entries\" at the bottom!</p>";
   echo "</caption>";
   if($editable)
   {
   echo "<tr align=center><td><b>#</b></td><td><b>School</b></td><td><b>Student</b></td><td><b>Label/Description</b></td><td><b>Upload File</b></td>";
   if($level==1) echo "<td><b>Judge's Comments</b></td>";
   echo "</tr>";
   $ix=1;
  // $sql="SELECT * FROM jostateentries WHERE sid='$sid' AND catid='$catid'";
   $sql="SELECT * FROM jostateentries WHERE class='$class' AND catid='$catid'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   { //echo '<pre>'; print_r($row); 
      $students[]=$row['studentid'];
      echo "<tr align=center";
      if($ix/2==0) echo " bgcolor='#f0f0f0'";
      echo "><td rowspan=\"$maxfiles\"><b>$ix</b></td><td rowspan=\"$maxfiles\"><input type=hidden name=\"entryid[$ix]\" value=\"$row[id]\">";
      for($j=1;$j<=$max;$j++)
      {
	  $sql_school="SELECT * FROM joschool";
	  $sql_school.=" WHERE class='$class'";
      $sql_school.=" ORDER BY school";
      $result_school=mysql_query($sql_school);
      echo "<select name=sid[$ix] onchangee=\"submit();\"><option value=''>School</option>";
      $distidfound=0;
      while($row_s=mysql_fetch_array($result_school))
      { 
         echo "<option value=\"$row_s[sid]\"";
         if($row['sid']==$row_s['sid']) 
		 {
			$distidfound=1; 
			echo " selected";
			//$class=$row_s["class"];
		 }
         echo ">$row_s[school]";
         if($row_s[district]!='') echo "-$row_s[district]";
	 echo "</option>";
      }
      echo "</select></td>";
      }
	  for($j=1;$j<=$max;$j++)
      {
         $studvar="studentid";
  	     if($j>1) $studvar.=$j;
         echo "<td><select id=\"studentid".$ix.$j."\" name=\"studentid[$ix][$j]\"><option value='0'>Select Student</option>";

         echo "</select></td>"; 

      }
      echo "<td><input type=text size=35 name=\"label[$ix]\" value=\"$row[label]\"></td>";
      echo "<td>";
	  
	  if (preg_match('/http/', $row[filename]))
	  { 
         echo "<p style=\"text-align:left;\">You've uploaded <a href=\"$row[filename]\" target=\"_blank\">THIS FILE</a>.<br>";
         echo "To <b>replace</b> this file, upload a new one below:</p>";
     } else { 
	 
	  if( $row[filename]!='')	//FILE UPLOADED ALREADY
      {
         echo "<p style=\"text-align:left;\">You've uploaded <a href=\"/nsaaforms/downloads/$row[filename]\" target=\"_blank\">THIS FILE</a>.<br>";
         echo "To <b>replace</b> this file, upload a new one below:</p>";
      }
      else
         echo "<p style=\"text-align:left;\">No file uploaded yet. Click \"Choose File\" to find your file and then click \"Save Entries\" below:</p>";
      
	  }
	  
      echo "<table><tr align=left><th align=left>Find Your File:</th>";
	  echo "<td align=left><input type=file name=docfile[$ix]></td></tr>";
	  echo "<tr valign=top align=left><th align=left>OR Type in the Link URL:</th>";
	  echo "<td><input type=text class=tiny size=70 name=linkurl[$ix] ";
	  if (preg_match('/http/', $row[filename]))
	  echo"value=\"$row[filename]\"><br>";
	  else
	  echo"><br>";
	  echo "(Example:https://nsaa-static.s3.amazonaws.com/../bb_3.pdf)</td></tr>";
	  echo "<!--<tr align=left><td></td><td><input type=submit name=submit value=\"Upload\"></td></tr>--></table>";
      echo "</td><td></td></tr>";
     // }
      $ix++;
   }
   //echo '<pre>'; print_r($students); 
   if(mysql_num_rows($result)==0)
   {
   for ($k=1; $k<13; $k++)
   {
    $sql="INSERT INTO jostateentries (class,catid) VALUES ('$class','$catid')";
	$result=mysql_query($sql);
   }
    header("Location:entrybyclass.php?session=$session&class=$class&catid=$catid");
  
   }

   }	//END IF EDITABLE

   echo "</table><br>";
   echo "<input type=submit name='save' class='fancybutton2' value='Save Entries'>";
}

echo "</form>";


echo $end_html; 
$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

IF(isset($_SESSION['STE_URL']) && !in_array($url,$_SESSION['STE_URL']))
{

 $_SESSION['STE_URL'][]=$url;
	?>
	<script>
location.reload();
</script>
	<?php 
}
else $_SESSION['STE_URL'][]=$url; 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript"><!--
<?php for ($j=1; $j<13;$j++){?>
$('select[name=\'sid[<?php echo $j; ?>]\']').on('change', function() {
 	$.ajax({
		url: 'ajax.php?school=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'sid[<?php echo $j; ?>]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) { 
			html = '<option value=""><?php echo 'Select Student'; ?></option>';

			if (json['student']['id'] && json['student']['id'] != '') {
				for (i = 0; i < json['student']['id'].length; i++) {
					html += '<option value="' + json['student']['id'][i] + '"';

					if (json['student']['id'][i] == '<?php echo $students[$j-1]; ?>') {
						html += ' selected="selected"';
					}

					html += '>' + json['student']['name'][i] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo 'Select Student'; ?></option>';
			}

			$('select[name=\'studentid[<?php echo $j; ?>][1]\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	}); 
});
$('select[name=\'sid[<?php echo $j; ?>]\']').trigger('change');
<?php } ?>
//--></script>