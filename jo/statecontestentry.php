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
if(!$sid)
{
   $sid=GetSID2(GetSchool($session),'jo');
}
else
{
   if(!$sid || $sid=="") $sid=GetSID2("Test's School",'jo');
}
$school=GetMainSchoolName($sid,'jo');
$school2=ereg_replace("\'","\'",$school);
$studs=explode("<result>",GetPlayers('jo',$school));

$duedate=GetDueDate('jo_contest');
$date=explode("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
//SHOW MARCH 1, EVEN IF DUE DATE IS AFTER THAT
//$duedate2=date("F j, Y",mktime(0,0,0,3,1,$date[0]));

//GET DATA SPECIFIC TO THE SELECTED CATEGORY/EVENT
if($catid)
{
   $sql="SELECT * FROM jostatecategories WHERE id='$catid'";
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

//echo'<pre>'; print_r($_POST); exit;
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
         $sql.="label='".addslashes($label[$i])."' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'";
         $result=mysql_query($sql);

		 if(!empty($_FILES["docfile"]["name"][$i])){  
				$image = rand(0,10000).$_FILES["docfile"]["name"][$i];
				$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/"; 
				 $target_file = $target_dir . basename($image); 
				$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				citgf_moveuploadedfile($_FILES["docfile"]["tmp_name"][$i], $target_file);
				$image = mysql_real_escape_string($image);
			    $sql="UPDATE jostateentries SET filename='$image' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}
		 if(!empty($_FILES["docfile2"]["name"][$i])){  
				$image = rand(0,10000).$_FILES["docfile2"]["name"][$i];
				$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
				$target_file = $target_dir . basename($image);
				$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				citgf_moveuploadedfile($_FILES["docfile2"]["tmp_name"][$i], $target_file);
				$image = mysql_real_escape_string($image);
			    $sql="UPDATE jostateentries SET filename2='$image' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}	
		if(!empty($linkurl[$i])){  
				$image = mysql_real_escape_string($linkurl[$i]);
			    $sql="UPDATE jostateentries SET filename='$image' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}	
		if(!empty($linkurl2[$i])){  
				$image = mysql_real_escape_string($linkurl[$i]);
			    $sql="UPDATE jostateentries SET filename2='$image' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'"; 
				$result=mysql_query($sql);		
			}	
         if($maxfiles>1)
         {
            $sql="UPDATE jostateentries SET ";
            $sql.="label2='".addslashes($label2[$i])."' WHERE id='$entryid[$i]' AND sid='$sid' AND catid='$catid'";
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
}
else $saved=0;

echo $init_html;
echo $header;

echo "<br>";
if($level==1)
   echo "<p><a href=\"statecontestentries.php?session=$session\">Return to JO Contest Entry Submissions</a></p>";

echo "<form method='post' action='statecontestentry.php' enctype='multipart/form-data'>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sid\" value=\"$sid\">";
echo "<h2>State Journalism Contest Entry Submission for <u>".GetSchoolName($sid,'jo')."</u>:</h2>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";
echo "<caption>";
if(PastDue($duedate,0))
   echo "<div class='alert'><b>This form's due date has now passed.</b><br>Submissions were due on <b><u>$duedate2</u></b> at midnight. You can no longer make changes to your submissions.</div>";
else
   echo "<div class='alert'>Submissions are due on <b><u>$duedate2</u></b> at midnight. You will not be able to change or add submissions after this date.</div>";
echo "<b>Category:</b> <select name=\"catid\" onChange=\"submit();\"><option value='0'>";
//SEE IF THEY'VE SUBMITTED ANYTHING FIRST
$sql="SELECT * FROM jostateentries WHERE sid='$sid'";
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
if($catid)
{
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
         echo "<p>For each of the maximum $maxentries entries below, select the <b>Student(s)</b> and then upload <b>$maxfiles $filetype Files</b>, giving each a <b>descriptive Label.</b></p>";
      else
         echo "<p>Select the <b>Student(s)</b> and then upload <b>$maxfiles $filetype Files</b>, giving each a <b>descriptive Label.</b></p>";
   }
   else if($maxentries>1)
      echo "<p>For each of the maximum $maxentries entries below, select the <b>Student(s)</b>, enter a <b>descriptive Label</b> for the student's submission, and <b>Upload the $filetype file</b>.</p>";
   else
      echo "<p>Select the <b>Student(s)</b>, enter a <b>descriptive Label</b>, and <b>Upload the $filetype file</b>.</p>";
   echo "<p style=\"color:#ff0000;font-weight:bold;\">Be sure to click \"Save Entries\" at the bottom!</p>";
   echo "</caption>";
   if($editable)
   {
   echo "<tr align=center><td><b>#</b></td><td><b>Student</b></td><td><b>Label/Description</b></td><td><b>Upload File</b></td>";
   if($level==1) echo "<td><b>Judge's Comments</b></td>";
   echo "</tr>";
   $ix=1;
   $sql="SELECT * FROM jostateentries WHERE sid='$sid' AND catid='$catid'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center";
      if($ix==2) echo " bgcolor='#f0f0f0'";
      echo "><td rowspan=\"$maxfiles\"><b>$ix</b></td><td rowspan=\"$maxfiles\"><input type=hidden name=\"entryid[$ix]\" value=\"$row[id]\">";
      for($j=1;$j<=$max;$j++)
      {
         $studvar="studentid";
  	 if($j>1) $studvar.=$j;
         echo "<select id=\"studentid".$ix.$j."\" name=\"studentid[$ix][$j]\"><option value='0'>Select Student</option>";
         for($i=0;$i<count($studs);$i++)
         {
            $stud=explode("<detail>",$studs[$i]);
            echo "<option value=\"$stud[0]\"";
	    if($row[$studvar]==$stud[0]) echo " selected";
            echo ">$stud[1]</option>";
         }
         echo "</select><br>";
      }
      echo "</td><td><input type=text size=40 name=\"label[$ix]\" value=\"$row[label]\"></td>";
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
	  if($maxfiles>1)
      {
         echo "</td></tr><tr align=center";
         if($ix==2) echo " bgcolor='#f0f0f0'";
	     echo "><td><input type=text size=40 name=\"label2[$ix]\" value=\"$row[label2]\"></td>";
         echo "<td>";
		 if (preg_match('/http/', $row[filename2]))
		  { 
			 echo "<p style=\"text-align:left;\">You've uploaded <a href=\"$row[filename]\" target=\"_blank\">THIS FILE</a>.<br>";
			 echo "To <b>replace</b> this file, upload a new one below:</p>";
		 } else {
         if(citgf_file_exists($_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/".$row[filename2]) && $row[filename2]!='')   //FILE UPLOADED ALREADY
         {
            echo "<p style=\"text-align:left;\">You've uploaded <a href=\"/nsaaforms/downloads/$row[filename2]\" target=\"_blank\">THIS FILE</a>.<br>";
            echo "To <b>replace</b> this file, upload a new one below:</p>";
         }
         else
            echo "<p style=\"text-align:left;\">No file uploaded yet. Click \"Choose File\" to find your file and then click \"Save Entries\" below:</p>";
         }
		 
          echo "<table><tr align=left><th align=left>Find Your File:</th>";
		  echo "<td align=left><input type=file name=docfile2[$ix]></td></tr>";
		  echo "<tr valign=top align=left><th align=left>OR Type in the Link URL:</th>";
	      echo "<td><input type=text class=tiny size=70 name=linkurl2[$ix] ";
	      if (preg_match('/http/', $row[filename2]))
	      echo"value=\"$row[filename2]\"><br>";
	      else
	      echo"><br>";
		  echo "(Example:https://nsaa-static.s3.amazonaws.com/../bb_3.pdf)</td></tr>";
		  echo "<!--<tr align=left><td></td><td><input type=submit name=submit value=\"Upload\"></td></tr>--></table>";
		 echo "<i>To upload click \"Save Entries\" below.</i></td>";
         echo "</tr>";
      }
      else
      {
         echo "<br><i>Wait for the upload to complete before clicking \"Save Entries\" below.</i></td></tr>";
      }
      $ix++;
   }
   while($ix<=$maxentries)
   {
      $sql="INSERT INTO jostateentries (sid,catid) VALUES ('$sid','$catid')";
      $result=mysql_query($sql);
      $curentryid=mysql_insert_id();
      echo "<tr align=center><td><b>$ix</b></td><td><input type=hidden name=\"entryid[$ix]\" value=\"$curentryid\">";
      for($j=1;$j<=$max;$j++)
      {
         echo "<select id=\"studentid".$ix.$j."\" name=\"studentid[$ix][$j]\"><option value='0'>Select Student</option>";
         for($i=0;$i<count($studs);$i++)
         {
            $stud=explode("<detail>",$studs[$i]);
            echo "<option value=\"$stud[0]\">$stud[1]</option>";
         }
         echo "</select><br>";
      }
      echo "</td><td><input type=text size=40 name=\"label[$ix]\"></td>";
      //echo "<td><p style=\"text-align:left;\">Click \"Choose File\" to find your file and then click \"Upload\" below:</p><iframe style=\"width:410px;height:175px;\" src=\"simpleupload.php?session=$session&entryid=$curentryid\" frameborder='0'></iframe><br><i>Wait for the upload to complete before clicking \"Save Entries\" below.</i></td>";
      echo "</tr>";
      $ix++;
   }
   }	//END IF EDITABLE
   else	//SHOW WHAT WAS SUBMITTED, INCLUDING JUDGE'S COMMENTS
   {
      echo "<tr align=center><td><b>#</b></td><td><b>Student</b></td><td><b>Submitted Entry</b></td><td><b>Judge's Comments</b></td></tr>";
      $sql="SELECT t1.* FROM jostateentries AS t1, jostateassignments AS t2 WHERE t1.catid=t2.catid AND t1.sid='$sid' AND t1.catid='$catid' AND t2.datesub>0";
      $result=mysql_query($sql);
      $ix=1;
      while($row=mysql_fetch_array($result))
      {
         echo "<tr align=left><td align=center><b>$ix</b></td><td>";
         for($j=1;$j<=6;$j++)
         {
            $studvar="studentid";
            if($j>1) $studvar.=$j;
            $stud=explode("<detail>",$studs[$i]);
	    if($row[$studvar]>0) echo GetStudentInfo($row[$studvar],FALSE)."<br>";
         }
         echo "</td><td>";
         if(citgf_file_exists($_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/".$row[filename]) && $row[filename]!='')   //FILE UPLOADED ALREADY
            echo "<p><a href=\"/nsaaforms/downloads/$row[filename]\" target=\"_blank\">$row[label]</a></p>";
         else
            echo "<p>No file uploaded.</p>";
         if(citgf_file_exists($_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/".$row[filename2]) && $row[filename2]!='')   //FILE UPLOADED ALREADY
            echo "<p><a href=\"/nsaaforms/downloads/$row[filename2]\" target=\"_blank\">$row[label2]</a></p>";
	 echo "</td><td><p>";
         //SEE IF STATE QUALIFIERS HAVE BEEN POSTED - IF YES, JUDGE COMMENTS CAN BE SHOWN
	 $sql2="SELECT * FROM jostatequalifiers WHERE showtopublic='x'";
	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)>0)
	    echo $row[judgecomments];
	 else
	    echo "<i>Judge's comments will be shown once State Qualifiers have been posted.</i>";
	 echo "</p></td></tr>";
	 $ix++;
      }
   }	//END IF NOT EDITABLE
   echo "</table><br>";
   echo "<input type=submit name='save' class='fancybutton2' value='Save Entries'>";
}
else	//NO $catid -- check if there are submissions from this school
{
   $sql="SELECT DISTINCT t1.catid,t2.category FROM jostateentries AS t1,jostatecategories AS t2 WHERE t1.catid=t2.id AND t1.sid='$sid' AND t1.studentid>0";
   $sql="SELECT * FROM jostatecategories";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//NONE YET
   {
      echo "<p><i>Please select an event Category above.</i></p>";
      echo "</caption>";
   }
   else		//SOME ENTRIES - SHOW THEM
   {
      if(!PastDue($duedate,0) || $level==1)
         echo "<br><br><p style='text-align:left;'><i>Below are entries input by your school so far. Click on \"Edit Submissions\" to make changes to the submissions for that category. Or, select a category above.</i></p>";
      echo "</caption>";
      $total=mysql_num_rows($result);	//number of categories with entries
      $percol=ceil($total/3);
      $curcol=0;
      while($row=mysql_fetch_array($result))
      {
         if($curcol==0)
            echo "<tr align=left valign=top>";
         echo "<td width='33%'><h3 style='text-align:center;'>$row[category]<br>";
	 $errors=GetJOEntryErrors($sid,$row[id]);
         if($errors!='')
	    echo "<div class='error' style=\"width:250px;\">$errors</div>";
         if(!PastDue($duedate,0) || $level==1)
            echo "<a class=small href=\"statecontestentry.php?session=$session&sid=$sid&catid=$row[id]\">Edit Submissions</a></h3>";
         $curct=1;
         $sql2="SELECT * FROM jostateentries WHERE catid='$row[id]' AND sid='$sid' AND studentid>0";
         $result2=mysql_query($sql2);
         while($row2=mysql_fetch_array($result2))
	 {
	    if (preg_match('/http/', $row2[filename]))
		echo "<p>$curct.&nbsp;<a href=\"$row2[filename]\" target=\"_blank\">$row2[label]</a><br>";
		else
		echo "<p>$curct.&nbsp;<a href=\"/nsaaforms/downloads/$row2[filename]\" target=\"_blank\">$row2[label]</a><br>";
	    if($row2[filename2]!=''){
		   if (preg_match('/http/', $row2[filename2]))
	       echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$row2[filename2]\" target=\"_blank\">$row2[label2]</a><br>";
	       else
		   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/nsaaforms/downloads/$row2[filename2]\" target=\"_blank\">$row2[label2]</a><br>";
           }
		   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".GetStudentInfo($row2[studentid],FALSE);
	    $j=2;
	    while($j<=6)
	    {
	       $studvar="studentid".$j;
	       if($row2[$studvar]>0) echo ", ".GetStudentInfo($row2[$studvar],FALSE);
	       $j++;
	    }
	    echo "</p>";
	    $curct++;
	 }
         while($curct<=$maxentries)
	 {
   	    echo "<p>$curct.</p>";
	    $curct++;
         }
         echo "</td>";
         $curcol++;
         if(($curcol%3)==0) 
	 {
	    echo "</tr>"; $curcol=0;
         }
      }
   }
   echo "</table>";
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
