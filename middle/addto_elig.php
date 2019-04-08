<?php
/*****************************************
addto_elig.php
Action of blank_elig_list.php
Created 12/26/09
Author: Ann Gaffigan
*****************************************/
require '../functions.php';
require '../variables.php';

//connect to database
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}
if($submit=="Cancel")
{
   header("Location:eligibility.php?session=$session&school_ch=$school_ch&last=a");
   exit();
} //END if submit=Cancel loop
$i=0;
while($i<10)
{
   if(trim($last[$i]!="") && trim($last[$i])!="[Last]" && trim($first[$i]!="") && trim($first[$i])!="[First]")
   {
      //check for errors
      $last[$i]=trim(addslashes($last[$i]));
      $first[$i]=trim(addslashes($first[$i]));
      $middle[$i]=trim(addslashes($middle[$i]));
      $dob[$i]="$year[$i]-$month[$i]-$day[$i]";
      $name[$i]=ereg_replace("-","","$first[$i] $middle[$i] $last[$i]");
      $name_error=0; $gender_error=0; $sem_error=0; $dob_error=0;
      if($gender[$i]=='')
	$gender_error=1;
      if($month[$i]=="00" || $day[$i]=="00" || $year[$i]=="0000")
	$dob_error=1;
      if(trim(ereg_replace("[^0-4]","",$semesters[$i]))=="" || strlen(trim($semesters[$i]))!=1)
	$sem_error=1;
      if(!ereg("^[[:alpha:] -\']+$",$name[$i]) || trim($first[$i])=="" || trim($last[$i])=="")
   	$name_error=1;
      $errormessage="";
      if($gender_error==1 || $dob_error==1 || $sem_error==1 || $name_error==1)
      {
         if($name_error==1)
	    $errormessage.="Some of the names you entered either:<br>(1) Did not include a first and/or last name or<br>(2) Included illegal characters such as # ? ! | etc.<br>";
	 if($gender_error==1)
	    $errormessage.="You did not enter a Gender for each student.<br>";
	 if($dob_error==1)
	    $errormessage.="The date-of-birth was not entered for all of the students.<br>";
	 if($sem_error==1)
	 {
	    $errormessage.="The semesters entered for some of the students were invalid.<br>";
	    $errormessage.="You may only enter a number between 0 and 4 in the semester column.<br>";
	 }
      } //END if-error loop
   } //END if-data entered loop
   $i++;
} //END while-loop
if($errormessage=="")
{
   //Insert new students into db:
   if($level==8)
      $school=GetSchool($session);
   else
      $school=$school_ch;
   $school2=ereg_replace("\'","\'",$school);
   for($i=0;$i<count($last);$i++)
   {
      if(trim($last[$i]!="") && trim($last[$i])!="[Last]" && trim($first[$i]!="") && trim($first[$i])!="[First]")
      {
         //check if student is too old to participate:
         if(IsTooOldM($dob[$i]))
         {
	    $eligible[$i]="n";
	    $eligible_comment[$i]="Older than 15 years";
         }
	 else
	 {
	    $eligible[$i]="y";
	    $eligible_comment[$i]="";
	 }
         $sql="INSERT INTO middleeligibility (school,last, first, middle, gender, dob, semesters, eligible, eligible_comment) VALUES ('$school2','$last[$i]','$first[$i]','$middle[$i]','$gender[$i]','$dob[$i]','$semesters[$i]','$eligible[$i]','$eligible_comment[$i]')";
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
}//end if no errors
else
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><div style='background-color:#f0f0f0;border:#a0a0a0 1px solid;padding:10px;text-align:left;width:400px'><div class='error'>Your input has the following errors:</div>$errormessage</div>";
   echo "<br><a href=\"javascript:history.go(-1);\">Go BACK and fix these errors</a>";
   echo $end_html;
   exit();
}
?>

