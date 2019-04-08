<?php
/********************************************************
reimbursements.php
School AD can submit reimbursement form to NSAA to 
get $$ for mileage and lodging at State
Created 9/12/12
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';
require 'officials/variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($reimid && $delete==1)
{
   $sql="DELETE FROM reimbursements WHERE id='$reimid'";
   $result=mysql_query($sql);

   echo $init_html;
   echo GetHeader($session)."<br><br>";
   echo "<div style=\"width:500px;\">";
   echo "<div class=\"alert\">The reimbursement form has been deleted.</div>";
   echo "<p><a href=\"reimadmin.php?session=$session&sport=$sport\">Return to ".GetActivityName($sport)." Reimbursements</a></p>";
   echo $end_html;
   exit();
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid)
{
   if($level==1)
   {
      if($reimid)
      {
         $sql="SELECT * FROM reimbursements WHERE id='$reimid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
	 $schoolid=$row[schoolid];
      }
      else $schoolid=1616;
   }
   else
      $schoolid=GetSchoolID($session);
}
$school=GetSchool2($schoolid);
if(!$sport) $sport='vb';
$sportname=GetActivityName($sport);
$sid=GetSID2($school,$sport);
$class=GetClass($sid,$sport);
//echo "$sid $class";

$dbyear=date("Y");
if(date("m")<7) $dbyear--;
$database=GetDatabase($dbyear);
$database='nsaascores';
$database2=preg_replace("/scores/","officials",$database);

if($save)
{
   //CHECK FOR ERRORS
   $errors="";
   if(!$studct1 && !$studct2 && !$studct3)
      $errors.="You must enter the number of athletes who competed for your school.<br>";
   if(!$lodging)
      $errors.="Please indicate whether or not your school used lodging.<br>";
   else 	//CHECK TO SEE IF ANY NIGHTS WERE CHECKED
   {
      $checked=0;
      for($i=1;$i<=$lodgingnights;$i++)
      {
	 $var="lodging".$i;
	 if($$var=='1') $checked=1;
      }
      if($checked==0 && $lodging=='yes')
         $errors.="You checked \"YES\" that your school used lodging, but you didn't check specific nights lodging was used.<br>";
      else if($checked==1 && $lodging=='no')
         $errors.="You checked \"NO\" that your school did NOT use lodging, but then you checked nights lodging was used. If your school used lodging, please check \"YES.\" Otherwise, please un-check the nights lodging was used.<br>";
      if($lodging=='yes' && trim($hotelname)=="")
      {
	 $errors.="You checked \"YES\" that your school used lodging, but you did not enter the name of the hotel.<br>";
      }
   }
   if($trips==0)
   {
      $errors.="You must enter the number of trips your school took to the host city.<br>";
   }
   if($mileage==0)
   {
      $errors.="The mileage for a one-way trip for your school is 0 miles. You cannot qualify for reimbursement if you do not live outside the host city.<br>";
   }
   if(trim($signature)=="")
   {
      $errors.="Please type the name of the person submitting this form in the \"Electronic Signature\" box.<br>";
   }
   //NO MATTER WHAT, GO AHEAD AND UPDATE DATABASE. THEN SHOW ERRORS
   if(!$reimid)	//INSERT AND GET $reimid, THEN UPDATE
   {
      $sql="INSERT INTO reimbursements (sport,schoolid,datesub) VALUES ('$sport','$schoolid','".time()."')";
      $result=mysql_query($sql);
      $reimid=mysql_insert_id();
   }
   //NOW CREATE UPDATE QUERY:
   $sql="UPDATE reimbursements SET sport='$sport',schoolid='$schoolid',studct1='$studct1',studct2='$studct2',studct3='$studct3',trips='$trips',";
   if($lodging=='yes') $sql.="lodging='x',";
   else $sql.="lodging='',";
   for($i=1;$i<=$lodgingnights;$i++)
   {
      $var="lodging".$i;
      $sql.="$var='".$$var."',";
   }
   $sql.="mileage='$mileage',hotelname='".addslashes($hotelname)."',signature='".addslashes($signature)."' WHERE id='$reimid'";
   $result=mysql_query($sql);
   if(mysql_error())
   {
      $errors.="There was a database query error: ".mysql_error()."<br>$sql<br><br>";
   }
   if($errors=='')	//SEND TO CONFIRMATION SCREEN
   {
      header("Location:reimbursements.php?session=$session&reimid=$reimid&confirm=1&sport=$sport");
      exit();
   }
   else
   {
      $edit=1;
   }
}

echo $init_html;
echo GetHeader($session)."<br>";

if($reimid)
{
   $sql="SELECT * FROM reimbursements WHERE id='$reimid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
}

//THE FORM
echo "<form method=post action='reimbursements.php'>";
echo "<input type=hidden name='session' value='$session'>";
echo "<input type=hidden name='reimid' value='$reimid'>";
echo "<input type=hidden name='sport' value='$sport'>";

echo "<h2>$sportname State Championship Reimbursement</h2>";
$duedate=GetReimDueDate($sport);
$nicedate=strtotime($duedate);
$nicedate=date("F j, Y",$nicedate);
if(!CanSubmitReimbursement($schoolid,$sport) && $level!=1 && $school!="Test's School")
{
   echo "<div class='error'>Your school is not eligible to submit a $sportname State Championship reimbursement form.</div>";
   echo $end_html;
   exit();
}
else if(PastDue($duedate,0) && $level!=1 && $school!="Test's School")
{
   echo "<div class='error'>This form was locked as of $nicedate, at midnight. You may no longer edit this form. Please contact the NSAA if you need to make any changes.</div>";
   echo $end_html;
   exit();
}
echo "<p><b>This form will be locked after <u>$nicedate, at midnight</u>.</b></p>";
echo "<table cellspacing=0 class='nine' cellpadding=5><caption>";
echo "<p style='text-align:left;'>Lodging and mileage will be paid according to the manual for each sport. NSAA reimbursement checks will be written following the completion of the season.</p>";
//CONFIRMATION/ERRORS/INSTRUCTIONS
if($errors!='')
{
   echo "<div class='error'>Please correct the following errors in your form:<br><br>$errors</div><br>";
}
else if($confirm)
{
   echo "<div class='alert'>This reimbursement form has been submitted to the NSAA.</div><br>";
}
echo "</caption>";
echo "<tr align=left><td><b><p>School:</b> $school</p></td></tr>";
if($sport=='wr')	//# of athletes per night (Th, Fr, Sa)
{
   echo "<tr align=left><td><p>Please indicate:</p>";
   if($reimid && !$edit)
   {
      echo "<p style=\"padding-left:25px;\"><b>Number of qualifiers competing Thursday: <u>$row[studct1]</u></b></p>";
      echo "<p style=\"padding-left:25px;\"><b>Number of qualifiers competing Friday: <u>$row[studct2]</u></b></p>";
      echo "<p style=\"padding-left:25px;\"><b>Number of qualifiers competing Saturday: <u>$row[studct3]</u></b></p>";
   }
   else
   {
      echo "<p style=\"padding-left:25px;\"><b>Number of qualifiers competing Thursday: <input type=text size=3 name=\"studct1\" id=\"studct1\" value=\"$row[studct1]\"><br>";
      echo "<b>Number of qualifiers competing Friday: <input type=text size=3 name=\"studct2\" id=\"studct2\" value=\"$row[studct2]\"><br>";
      echo "<b>Number of qualifiers competing Saturday: <input type=text size=3 name=\"studct3\" id=\"studct3\" value=\"$row[studct3]\"></p>";
   }
}
else if($sport=='cc' || $sport=='tr')
{
   echo "<tr align=left><td><p><b>Number of athletes competing:</b>&nbsp;&nbsp;&nbsp;";
   if($reimid && !$edit)
   {
      if($row[studct1]==1)
         echo "<b><u>$row[studct1]</b></u> Girl&nbsp;&nbsp;&nbsp;";      
      else
         echo "<b><u>$row[studct1]</b></u> Girls&nbsp;&nbsp;&nbsp;";
      if($row[studct2]==1)
         echo "<b><u>$row[studct2]</b></u> Boy&nbsp;&nbsp;&nbsp;";              
      else
         echo "<b><u>$row[studct2]</b></u> Boys&nbsp;&nbsp;&nbsp;";
      echo "</p></td></tr>";
   }
   else 
   {
      echo "<input type=text size=3 name=\"studct1\" id=\"studct1\" value=\"$row[studct1]\"> Girls&nbsp;&nbsp;&nbsp;";
      echo "<input type=text size=3 name=\"studct2\" id=\"studct2\" value=\"$row[studct2]\"> Boys";
      echo "</p></td></tr>";
   }
}
else
{
   if($sport=='pp')
      echo "<tr align=left><td><p><b>Number of Students in Cast:</b>&nbsp;&nbsp;&nbsp;";
   else
      echo "<tr align=left><td><p><b>Number of athletes competing:</b>&nbsp;&nbsp;&nbsp;";
   if($reimid && !$edit) 
   {
      if($row[studct1]==1)
         echo "<b><u>$row[studct1]</b></u> Student</p></td></tr>";
      else
         echo "<b><u>$row[studct1]</b></u> Students</p></td></tr>";
   }
   else 
   {
      echo "<input type=text size=3 name=\"studct1\" id=\"studct1\" value=\"$row[studct1]\"> Students";
      if($sport=='pp') echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Do not include stagehands, make-up helpers, etc.)";
      echo "</p></td></tr>";
   }
}
echo "<tr align=left><td><p><b>Did your school use lodging?</b>&nbsp;&nbsp;&nbsp;<input type=radio name='lodging' id='lodgingyes' value='yes' onClick=\"if(this.checked) { document.getElementById('trips').value='1'; }\"";
if($row[lodging]=='x') echo " checked";
if($reimid && !$edit) echo " disabled";
echo "> YES&nbsp;&nbsp;&nbsp;<input type=radio name='lodging' id='lodgingno' value='no'";
if($row[lodging]!='x' && $reimid) echo " checked";
if($reimid && !$edit) echo " disabled";
echo "> NO</p></td></tr>";
echo "<tr align=left><td><p style='padding-left:50px;'>If <u><b>YES</b></u>, please check the <b>nights lodging was used</b>:<br><br>";
	//GET STATE DATES FOR THIS SPORT
 $sql2="SELECT * FROM $database2.".$sport."districts WHERE type='State'";
$result2=mysql_query($sql2);
$datesarray=array(); $ix=0;
$datestr="";
while($row2=mysql_fetch_array($result2))
{
   $dates=explode("/",$row2[dates]);
   for($i=0;$i<count($dates);$i++)
   {
      if(!preg_match("/-00-/",$dates[$i]) && $dates[$i]!='' && !ereg($dates[$i]."!",$datestr))
      {
         $datesarray[$ix]=$dates[$i]; $ix++;
         $datestr.=$dates[$i]."!";
      }
   }
}
sort($datesarray);
	//SHOW CHECKBOXES
	//FIRST SHOW DAY BEFORE FIRST STATE DAY - THIS IS FIRST LODGING DATE
if(count($datesarray)>0)
{
   $sql2="SELECT DATE_SUB('$datesarray[0]',INTERVAL 1 DAY)";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $curlodgingdate=$row2[0];
   $cur=explode("-",$curlodgingdate);
   $cursec=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
   echo "<input type=checkbox name=\"lodging1\" id=\"lodging1\" value=\"1\" onClick=\"if(this.checked) { document.getElementById('lodgingyes').checked=true; document.getElementById('lodgingno').checked=false; }\"";
   if($row[lodging1]==1) echo " checked";
   if($reimid && !$edit) echo " disabled";
   echo "> ".date("l, M jS",$cursec)."&nbsp;&nbsp;&nbsp;";
}
else 
  echo "<div class='error' style='width:400px;'><p>ERROR: <i>There are no dates entered into the system yet for this State Championship. Please contact the NSAA.</i></p></div>";
for($i=0;$i<count($datesarray);$i++)
{
   $lix=$i+2;
   $curvar="lodging".$lix;
   $cur=explode("-",$datesarray[$i]);
   $cursec=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
   echo "<input type=checkbox name=\"$curvar\" id=\"$curvar\" value=\"1\" onClick=\"if(this.checked) { document.getElementById('lodgingyes').checked=true; document.getElementById('lodgingno').checked=false; }\"";
   if($row[$curvar]==1) echo " checked";
   if($reimid && !$edit) echo " disabled";
   echo "> ".date("l, M jS",$cursec)."&nbsp;&nbsp;&nbsp;";
}
echo "<br><br><b>Name of Hotel:</b>&nbsp;&nbsp;&nbsp;";   
if($reimid && !$edit) echo $row[hotelname];
else
   echo "<input type=text name='hotelname' id='hotelname' size=40 value=\"$row[hotelname]\">";
$lodgingnights=count($datesarray)+1;
echo "</p><input type=hidden name=\"lodgingnights\" value=\"$lodgingnights\"></td></tr>";
echo "<tr align=left><td><p style='padding-left:50px;'>If <u><b>NO</b></u>, please indicate the <b>number of trips</b> your school took to the host site:<br><br>";
if($reimid && !$edit && $row[trips]==1)
   echo "<b><u>$row[trips]</b></u> Trip</td></tr>";
else if($reimid && !$edit)
   echo "$row[trips] Trips</td></tr>";
else
   echo "<input type=text name=\"trips\" id=\"trips\" value=\"$row[trips]\" size=3> Trips</td></tr>";

	//MILEAGE
	//GetMileage($fromcity,$tocity,$thorough=FALSE,$fromstate="NE",$tostate="NE")  
$sql2="SELECT * FROM $database.headers WHERE id='$schoolid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$city=preg_replace("/, NE/","",$row2[city_state]);
$city=preg_replace("/,  NE/","",$city);
$city=trim($city);
$hostcity=GetHostCity($sport);
if($sport=='ba')
   $hostcity=GetHostCity($sport,"State",$class);
echo "<tr align=left><td><b>Mileage:</b> Mileage from your school in <u>$city</u> to the host city of <u>$hostcity</u>:&nbsp;&nbsp;&nbsp;";
if($row[miles]>0) $miles=$row[miles];
else $miles=GetMileage($city,$hostcity);
echo "<input type=hidden name='mileage' id='mileage' value=\"$miles\">";
echo "<label style=\"font-size:14px;\"><u><b>$miles</u></b></label>";
echo "</td></tr><tr align=center><td><i>(If you feel the mileage shown above is incorrect, please contact the NSAA.)</i></td></tr>";

echo "<tr align=center><td><br><b>Electronic Signature:</b> ";
if($reimid && !$edit) echo $row[signature]."</td></tr>";
else
   echo "<input type=text size=30 name='signature' id='signature' value='$row[signature]'><br>(Please type the name of the person submitting this form.)</td></tr>";

if(!$reimid || $edit==1)
   echo "<tr align=center><td><br><input type=submit name='save' value='Submit Reimbursement Form'></td></tr>";

echo "</table>";

echo "</form>";
echo "<br><br>";

if($level==1)
{
   if($reimid && $edit!=1)
      echo "<a href=\"reimbursements.php?session=$session&sport=$sport&reimid=$reimid&edit=1\">Edit this Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick=\"return confirm('Are you sure you want to delete this form?');\" href=\"reimbursements.php?session=$session&sport=$sport&reimid=$reimid&delete=1\">Delete this Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   echo "<a href=\"reimadmin.php?session=$session&sport=$sport\">Return to $sportname Reimbursements Main Menu</a>";
}
else
   echo "<a href=\"reimindex.php?session=$session\">Return to Reimbursements Main Menu</a>";

echo $end_html;
?>
