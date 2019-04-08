<?php
//addto_elig.php: takes input from add_students.php
//(blank_elig_list.php) and inserts into database

require 'functions.php';
require 'variables.php';

//connect to database
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}


//check if user cancelled action:
if($submit=="Cancel")
{
?>
   <script language="javascript">
   top.location.replace('eligibility.php?session=<?php echo $session; ?>&activity_ch=<?php echo $activity_ch; ?>&school_ch=<?php echo $school_ch; ?>&last=a')
   </script>
<?php
   exit();
} //END if submit=Cancel loop
$i=0;
while($i<10)
{
   if(trim($last[$i]!="") && trim($first[$i]!=""))
   {
      //fix up name errors
      $last[$i]=ereg_replace("\'","\'",$last[$i]);
      $last[$i]=ereg_replace("\"","\'",$last[$i]);
      $first[$i]=ereg_replace("\'","\'",$first[$i]);
      $first[$i]=ereg_replace("\""," ",$first[$i]);
      if(!ereg(" \(",$first[$i]))
      {
	 $first[$i]=ereg_replace("\("," (",$first[$i]);
      }
      $middle[$i]=ereg_replace("\'"," ",$middle[$i]);
      $middle[$i]=ereg_replace("\""," ",$middle[$i]);
      $middle[$i]=ereg_replace("\.","",$middle[$i]);
      $dob[$i]=ereg_replace("/","-",$dob[$i]);
      $one=ereg_replace("-","",$last[$i]); 
      $two=ereg_replace("\(","",$first[$i]); $three=$middle[$i];
      $two=ereg_replace("\)","",$two);
      $two=ereg_replace("-","",$two);
      $name[$i]="$one $two $three";
      $gender[$i]=strtoupper($gender[$i]);
      $name_error=0;
      $gender_error=0;
      $sem_error=0;
      $dob_error=0;
      if(!ereg("(M|F)", $gender[$i]) || strlen(trim($gender[$i]))!=1)
	$gender_error=1;
      if(!ereg("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})", $dob[$i]))
	$dob_error=1;
      if(!ereg("[0-8]", $semesters[$i]) || strlen(trim($semesters[$i]))!=1)
	$sem_error=1;
      if(trim($semesters[$i])==0)
      {
         if($fb68[$i]=="x" || $fb11[$i]=="x" || $vb[$i]=="x" || $sb[$i]=="x" || $cc[$i]=="x" || $te[$i]=="x" || $bb[$i]=="x" || $wr[$i]=="x" || $sw[$i]=="x" || $go[$i]=="x" || $tr[$i]=="x" || $ba[$i]=="x" || $so[$i]=="x" || $ch[$i]=="x" || $sp[$i]=="x" || $de[$i]=="x" || $jo[$i]=="x" || $pp[$i]=='x')
	 {
	    $sem_error=1;
	 }
      }
      //if(!ereg("^[[:alpha:] -\']+$",$name[$i]) || strlen($last[$i])<=1 || strlen($two)<=1)
      if( strlen($last[$i])<=1 || strlen($two)<=1)
      {
   	$name_error=1;
      }
      if($gender_error==1 || $dob_error==1 || $sem_error==1 || $name_error==1)
      {
?>
<html><head><link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
      </head>
<?php
$header=GetHeader($session);
echo $header;
?>
   <center>
   <br><br>
   <table cellspacing=2 cellpadding=3>
   <tr align=center>
   <th><font color="#FF0000">Your input had the following errors:</font>
   <br></th>
   </tr>
   <tr align=left>
   <td>
<?php
         if($name_error==1)
	 {
	    echo "Some of the names you entered either:<br>";
	    echo "  (1)  Did not include a first and/or last name or<br>";
	    
	    echo $name[$i];
	 }
	 if($gender_error==1)
	 {
	    echo "Some of the data entered into the Gender column was invalid.<br>";
	    echo "You may only enter an 'M' or an 'F' in the Gender column.<br><br>";
	 }
	 if($dob_error==1)
	 {
	    echo "The date-of-birth data for some of the students were entered incorrectly.<br>";
	    echo "The DOB must be in this format: MM-DD-YYYY<br><br>";
	 }
	 if($sem_error==1)
	 {
	    echo "The semesters entered for some of the students were invalid.<br>";
	    echo "You may only enter a number between 0 and 8 in the semester column.<br>";
	    echo "Remember that a '0' may only be entered if that student will only be participating in Music.<br><br>";
	 }
?>
   </td></tr>
   <tr align=center><td>
   <br><b>Please <a href="javascript:history.go(-1)">Go Back</a> and make the necessary changes to your input.</b>
   </td></tr>
   </table>
   </center>
   </td><!--End Main Body-->
   </tr>
   </table>
   </body>
   </html>
<?php
	exit();
      } //END if-error loop
   } //END if-data entered loop
   $i++;
} //END while-loop

//if you get to this point, then all the info is complete
//Insert new students into db:
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

for($i=0;$i<count($last);$i++)
{
   if(trim($last[$i])!="" && trim($first[$i])!="")
   {
      //fix up submitted info:
	//gender:
      $gender[$i]=ereg_replace("m","M",$gender[$i]);
      $gender[$i]=ereg_replace("f","F",$gender[$i]);
	//dob:
      $dob[$i]=ereg_replace("/","-",$dob[$i]);
      $dob_array=split("-",$dob[$i]);
      if(strlen($dob_array[0])==1) $dob_array[0]="0$dob_array[0]";
      if(strlen($dob_array[1])==1) $dob_array[1]="0$dob_array[1]";
      $dob[$i]="$dob_array[0]-$dob_array[1]-$dob_array[2]";
         //check if student is too old to participate:
         if(IsTooOld($dob[$i]))
         {
	    $eligible[$i]="n";
	    $eligible_comment[$i]="Older than 19 years";
         }
	 else
	 {
	    $eligible_comment[$i]="";
	 }

	 //check if EO was checked; if so, check transfer too
	 if($enroll_option[$i]=='y')
	 {
	    $transfer[$i]='y';
	    $transfer_comment[$i]="Enrollment Option";
	 }

	 //if the student is checked as a foreign exchange, make ineligible:
	 if($foreignx[$i]=="y")
	 {
	    $eligible[$i]="n";
	    $eligible_comment[$i]="International Transfer; Missing Paperwork";
	    $forx_flag=1;
	 }

      $sql="INSERT INTO eligibility (school,last, first, middle, gender, dob, semesters, transfer, transfer_comment, eligible, eligible_comment, foreignx, enroll_option, fb68, fb11, vb, sb, cc, te, bb, wr, sw, go, tr, ba, so, ch, sp, pp, de, im, vm, jo, ubo) VALUES ('$school2','$last[$i]','$first[$i]','$middle[$i]','$gender[$i]','$dob[$i]','$semesters[$i]','$transfer[$i]','$transfer_comment[$i]','$eligible[$i]','$eligible_comment[$i]','$foreignx[$i]','$enroll_option[$i]','$fb68[$i]','$fb11[$i]','$vb[$i]','$sb[$i]','$cc[$i]','$te[$i]','$bb[$i]','$wr[$i]','$sw[$i]','$go[$i]','$tr[$i]','$ba[$i]','$so[$i]','$ch[$i]','$sp[$i]','$pp[$i]','$de[$i]','$im[$i]','$vm[$i]','$jo[$i]','$ubo[$i]')";
      $result=mysql_query($sql);
   }
}

//Check what user wants to do next:
if($submit=="Save and Add More")	//Get another blank entry page
{
   header("Location:blank_elig_list.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch");
   exit();
}
else if($submit=="Save and View List")	//Show updated eligibility list
{
   header("Location:eligibility.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=a");
   exit();
}
?>

