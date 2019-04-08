<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//import_student_file.php: takes filename from
//   import_students.php and uploads that file,
//   parses it, and enters its info in to the
//   database.

require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$header=GetHeader($session);
$school2=ereg_replace("\'","\'",$school);

//connect to database:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$import_file=$_FILES['import_file']['tmp_name'];
if(!citgf_copy($import_file,"eligtemp/temp$session.csv"))
{
   echo "$import_file did not copy";
   exit();
}

$open=fopen(citgf_fopen("eligtemp/temp$session.csv"), "r");
$line=file(getbucketurl("eligtemp/temp$session.csv"));
$dob_error="";
$sem_error="";
$name_error="";
$gender_error="";
$file_error="";
fclose($open);
$cap_error=0;
flush();
for($i=0;$i<count($line);$i++)
{
   $line[$i]=ereg_replace("\"","",$line[$i]);
   $line[$i]=split(",",$line[$i]);
   $cols=count($line[$i]);
   if($cols<6)
   {
      $r=$i+1;
      $file_error.="$r,";
   }
   $line[$i][2]=strtoupper($line[$i][2]);	//middle initial
   $line[$i][3]=strtoupper(trim($line[$i][3]));	//gender
   $line[$i][4]=ereg_replace("/","-",trim($line[$i][4]));	//DOB: ACCEPT SLASHES OR DASHES
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
	//check for DOB errors
      $r=$i+1;
      $dob_error.="$r,";
   }
   $line[$i][5]=trim($line[$i][5]);
   if($line[$i][5]=="" || !ereg("([0-8]{1})",$line[$i][5]))
   {
	//check for semester errors
      $r=$i+1;
      $sem_error.="$r,";
   }
   $line[$i][0]=trim($line[$i][0]);	//last name
   $line[$i][1]=trim($line[$i][1]);	//first name
   $line[$i][0]=addslashes($line[$i][0]);
   $line[$i][1]=addslashes($line[$i][1]);
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
/*    if(!ereg("^[[:alpha:] -\']+$",$line[$i][0]) || !ereg("^[[:alpha:] -\']+$",$first_name) || !(ereg("^[[:alpha:] ]+$",$line[$i][2]) || trim($line[$i][2])==""))
   {
	//check for name errors
      $r=$i+1;
      $name_error.="$r,";
   } */
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
   $dob[$i]=$line[$i][4];
   $semesters[$i]=$line[$i][5];
   flush();
}

unlink("eligtemp/temp$session.csv");
echo "<html><head><title>NSAA Home</title><link href=\"../css/nsaaforms.css\" rel=\"stylesheet\" type=\"text/css\"></head>$header";
flush();

//Check if there were errors
if($cap_error==1 || $name_error!="" || $sem_error!="" || $dob_error!="" || $gender_error!="" || $file_error!="")
{
      echo "<table width=80%><tr align=left><td><br>";
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
	 echo "<b>NOTE:</b> Birth dates must be entered in this format: MM-DD-YYYY or MM/DD/YYYY (for example, 09-01-1994 or 09/01/1994).  If you open your file in Excel, it will sometimes automatically convert your Date of Birth column to another format, such as MM/DD/YY.  BUT YOUR YEAR MUST HAVE 4 DIGITS. To fix this, click on the header of the Date of Birth column to select the entire column, go to Format-->Cells and under the Number tab, click \"Date\" in the list and then \"Custom\" (at the bottom of the same list).  Then in the box to the right, type \"mm-dd-yyyy\" to indicate the correct format.  Click \"OK\" and Save the file to your Desktop as a .CSV (Comma-Delimited) file on a PC or a .CSV (for Windows) on a Mac.<br><br>";
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
      //if they are too old to participate (>=19), set them as ineligible
      if(IsTooOld($dob[$i]))
      {
         $eligible="n";
         $elig_comment="Older than 19 years";
      }

      $sql="INSERT INTO eligibility (school, last, first, middle, gender, dob, semesters, eligible, eligible_comment) VALUES ('$school2', '$last[$i]', '$first[$i]', '$middle[$i]', '$gender[$i]', '$dob[$i]', '$semesters[$i]', '$eligible', '$elig_comment')";
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
<p>If you have just imported last year's freshmen, sophomores, juniors and transfers, you must now add THIS YEAR'S (non-transfer) INCOMING FRESHMEN to your database.  You may either enter them <a href=\"add_students.php?session=$session\">Manually</a> (not recommended for large schools) OR you may create a file containing this year's FRESHMAN ONLY and import those freshman into your database in order to add them to your current list of students.  <a href=\"import_students.php?session=$session\">Click Here to Learn How to Create & Import a File of Students</a>.</p></td></tr></table></td></tr>";
?>
<tr align=center>
<td><p>Please <b><a href="eligibility.php?session=<?php echo $session; ?>&activity_ch=All%20Activities&school_ch=<?php echo $school; ?>&last=a">Click Here</a></b> to view your updated eligibility list.</p></td>
</tr>
</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
<?php
}
?>
