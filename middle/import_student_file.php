<?php
/*********************************************
import_student_file.php: 
Takes filename from
import_students.php and uploads that file,
parses it, and enters its info in to the
database.
Copied 12/28/09 from ../import_student_file.php
Author: Ann Gaffigan
**********************************************/

require '../functions.php';
require '../variables.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || ($level!=1 && $level!=8))
{
   header("Location:index.php?error=1");
   exit();
}

if($level==8) $school=GetSchool($session);
$school2=addslashes($school);

//connect to database:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!citgf_copy($import_file,"eligtemp/temp$session.csv") || !is_uploaded_file($_FILES['import_file']['tmp_name']))
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><div class='error'><b>ERROR:</b><br><br>";
   echo "No file was uploaded OR the file could not be copied to the server.</div>";
   echo "<br><a href=\"javascript:history.go(-1);\">Go Back and Try Again</a><br><br>";
   echo $end_html;
   exit();
}

//IF FILE WAS SUCCESSFULLY UPLOADED AND COPIED TO SERVER, OPEN IT:
$open=fopen(citgf_fopen("eligtemp/temp$session.csv"), "r");
$line=file(getbucketurl("eligtemp/temp$session.csv"));
//INITIALIZE ERROR-CHECK VARIABLES
$dob_error="";
$sem_error="";
$name_error="";
$gender_error="";
$file_error="";
fclose($open);
$cap_error=0;
flush();	//LET THE BROSWER KNOW YOU'RE STILL WORKING
for($i=0;$i<count($line);$i++)	//FOR EACH LINE IN FILE (EACH STUDENT RECORD)
{
   $line[$i]=ereg_replace("\"","",$line[$i]);	//GET RID OF QUOTES
   $line[$i]=split(",",$line[$i]);		//SPLIT FIELDS UP INTO ARRAY
   $cols=count($line[$i]);
   if($cols<6)	//NOT ENOUGH INFORMATION SUBMITTED FOR EACH STUDENT
   {
      $r=$i+1;
      $file_error.="$r,";
   }
   $line[$i][2]=strtoupper(trim($line[$i][2]));	//middle initial
   $line[$i][3]=strtoupper(trim($line[$i][3]));	//gender
   $line[$i][4]=ereg_replace("/","-",trim($line[$i][4]));	//DOB
   $date=split("-",$line[$i][4]);
   for($j=0;$j<2;$j++)
   {
      if(strlen($date[$j])==1) $date[$j]="0$date[$j]";
   }
   $line[$i][4]=$date[0];
   $line[$i][4].="-";
   $line[$i][4].=$date[1];
   $line[$i][4].="-";
   $line[$i][4].=$date[2];
   if($line[$i][4]=="" || !ereg("([0-9]{2})-([0-9]{2})-([0-9]{4})",$line[$i][4]))
   {
	//check for DOB errors. Must be of format mm-dd-yyyy
      $r=$i+1;
      $dob_error.="$r,";
   }
   $line[$i][5]=trim($line[$i][5]);	//SEMESTERS
   if($line[$i][5]=="" || !ereg("([0-4]{1})",$line[$i][5]))
   {
	//check for semester errors
      $r=$i+1;
      $sem_error.="$r,";
   }
   $line[$i][0]=trim($line[$i][0]);	//last name
   $line[$i][1]=trim($line[$i][1]);	//first name
   $temp_last=strtoupper($line[$i][0]);
   $temp_first=strtoupper($line[$i][1]);
   if(ereg($temp_last,$line[$i][0]) && ereg($temp_first,$line[$i][1]))
   {
      $cap_error=1;
   }
   $line[$i][0]=ereg_replace("\'","\'",$line[$i][0]);
   $line[$i][1]=ereg_replace("\'","\'",$line[$i][1]);
   $line[$i][2]=ereg_replace("\.","",$line[$i][2]);
   if($line[$i][0]=="" || $line[$i][1]=="" || strlen($line[$i][0])<=1 || strlen($line[$i][1])<=1)
   {
	//check for name errors
      $r=$i+1;
      $name_error.="$r,";
   }
   $first_name=ereg_replace("\(","",$line[$i][1]);
   $first_name=ereg_replace("\)","",$first_name);
   if(!ereg("^[[:alpha:] -\']+$",$line[$i][0]) || !ereg("^[[:alpha:] -\']+$",$first_name) || !(ereg("^[[:alpha:] ]+$",$line[$i][2]) || trim($line[$i][2])==""))
   {
	//check for name errors
      $r=$i+1;
      $name_error.="$r,";
   }
   if($line[$i][3]=="" || !ereg("[M|F|m|f]{1}",$line[$i][3]))
   {
	//check for gender errors
      $r=$i+1;
      $gender_error.="$r,";
   }
   if(!ereg(" (",$line[$i][1]))
   {
      $line[$i][1]=ereg_replace("\("," (",$line[$i][1]);
   }

   $last[$i]=$line[$i][0];
   $first[$i]=$line[$i][1];
   $middle[$i]=$line[$i][2];
   $gender[$i]=$line[$i][3];
   $temp=split("-",$line[$i][4]);
   $dob[$i]=$temp[2]."-".$temp[0]."-".$temp[1];
   $semesters[$i]=$line[$i][5];
   flush();
}

unlink("eligtemp/temp$session.csv");	//DELETE TEMPORARY FILE

echo $init_html;
echo GetHeader($session);
flush();

//Check if there were errors
if($cap_error==1 || $name_error!="" || $sem_error!="" || $dob_error!="" || $gender_error!="" || $file_error!="")
{
      echo "<table width=70%><tr align=left><td><br>";
      echo "<div class='error'>YOU HAVE THE FOLLOWING ERRORS IN YOUR FILE:</div>";
      if($cap_error==1)
      {
	 echo "<br><br>The students' names in your file appear to be in all";
	 echo " capital letters.  Please change the names to the way they should appear.<br><br>";
      }
      if($name_error!="")
      {
	 $name_error=substr($name_error,0,strlen($name_error)-1);
	 $name_error=Unique($name_error);
	 $name_error=ereg_replace(",",", ",$name_error);
         echo "<br><br>Either a first or a last name was not entered or characters other than letters were used for the student(s) in row(s):<br>";
	 echo "<br><b>$name_error</b><br><br>";
      }
      if($sem_error!="")
      {
	 $sem_error=substr($sem_error,0,strlen($sem_error)-1);
	 $sem_error=Unique($sem_error);
	 $sem_error=ereg_replace(",",", ",$sem_error);
         echo "An invalid semester has been entered for the student(s) in row(s):<br><br>"; 
	 echo "<b>$sem_error</b><br><br>";
      }
      if($dob_error!="")
      {
	 $dob_error=substr($dob_error,0,strlen($dob_error)-1);
	 $dob_error=Unique($dob_error);
	 $dob_error=ereg_replace(",",", ",$dob_error);
         echo "The date(s) of birth for the student(s) in the following row(s) have been entered incorrectly:<br><br>";
	 echo "<b>$dob_error</b><br><br>";
	 echo "<b>NOTE:</b> Birth dates must be entered in this format: MM-DD-YYYY (for example, 09-01-1994).  If you open youur file in Excel, it will sometimes automatically convert your Date of Birth column to another format, such as MM/DD/YY.  You MUST have the YEAR in the 4-DIGIT FORMAT, however. To fix this, click on the header of the Date of Birth column to select the entire column, go to Format-->Cells and under the Number tab, click \"Date\" in the list and then \"Custom\" (at the bottom of the same list).  Then in the box to the right, type \"mm-dd-yyyy\" to indicate the correct format.  Click \"OK\" and Save the file to your Desktop as a .CSV (Comma-Delimited) file on a PC or a .CSV (for Windows) on a Mac.<br><br>";
      }
      if($gender_error!="")
      {
	 $gender_error=substr($gender_error,0,strlen($gender_error)-1);
	 $gender_error=Unique($gender_error);
	 $gender_error=ereg_replace(",",", ",$gender_error);
	echo "You may only enter \"M\" for male or \"F\" for female in the gender column.  The student(s) in the following row(s) have something other than M or F listed in their gender column:<br><br>";
	echo "<b>$gender_error</b><br><br>";
      }
      if($file_error!="")
      {
	 echo "It is possible that the file you have imported did not include the correct number of fields.<br><br>";
      }
      echo "Please <a href=\"javascript:history.go(-1)\">Go Back</a>";
      echo ", fix your file, and try again.</center>";
      echo "</td></tr></table></body></html>";
      exit();
}
else
{
   for($i=0;$i<count($last);$i++)
   {
      $eligible="y";		//default eligibility settings
      $elig_comment="";
      $physical="n"; $parent="n";
      //if they are too old to participate (>=15), set them as ineligible
      if(IsTooOldM($dob[$i]))
      {
         $eligible="n";
         $elig_comment="Older than 15 years";
      }

      $sql="INSERT INTO middleeligibility (school, last, first, middle, gender, dob, semesters, eligible, eligible_comment,physical,parent) VALUES ('$school2', '$last[$i]', '$first[$i]', '$middle[$i]', '$gender[$i]', '$dob[$i]', '$semesters[$i]', '$eligible', '$elig_comment','$physical','$parent')";
      if(!$result=mysql_query($sql)) echo "Could not do query";
   }
?>

<br><br>
<table>
<tr align=center>
<th>Your file has been imported successfully!</th>
</tr>
<tr align=center><td><table width=500>
<tr align=left><td>
<?php
echo "
<p>If you have just imported last year's 7th-Graders, you must now add THIS YEAR'S INCOMING 7th-GRADERS to your database.  You may either enter them <a href=\"add_students.php?session=$session\">Manually</a> (not recommended for large schools) OR you may create a file containing this year's 7th Graders ONLY and import those students into your database in order to ADD them to your current list of students.  <a href=\"import_students.php?session=$session\">Click Here to Learn How to Create & Import a File of Students</a>.</p></td></tr></table></td></tr>";
?>
<tr align=center>
<td><p>Please <b><a href="eligibility.php?session=<?php echo $session; ?>&school_ch=<?php echo $school; ?>&last=a">Click Here</a></b> to view your updated eligibility list.</p></td>
</tr>
</table>
<?php
echo $end_html;
}
?>
