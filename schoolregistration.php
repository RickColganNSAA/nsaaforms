<?php
/********************************************************
schoolregistration.php
AD's submit activity registration for the year, print
off invoice for payment
Created 6/16/11
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

if($print==1) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level>2)
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid || $level!=1)
{
   $schoolid=GetSchoolID($session);
   if($level==1) $schoolid=1616; 	//Test's School
}
$school=GetSchool2($schoolid);
$school2=ereg_replace("\'","\'",$school);

//MEMBERSHIP:
	//DUE DATE:
$memdate=GetMiscDueDate("membership");
$memdate2=date("F j, Y",strtotime($memdate));
	//SHOW DATE:
$memshow=GetMiscDueDate("membership","showdate");
$memshow2=date("F j, Y",strtotime($memshow));

//REGISTRATION:
	//FALL
$falldate=GetMiscDueDate("registration_fall");
$falldate2=date("F j, Y",strtotime($falldate));
	//WINTER
$winterdate=GetMiscDueDate("registration_winter");
$winterdate2=date("F j, Y",strtotime($winterdate));
	//SPRING
$springdate=GetMiscDueDate("registration_spring");
$springdate2=date("F j, Y",strtotime($springdate));

//GET SCHOOL YEAR
$dueyear=date("Y",strtotime($memdate));
$year1=$dueyear; $year2=$year1+1;

if(!PastDue($memshow,-1))       //NOT AVAILABLE YET
{
   echo $init_html.GetHeader($session);
   echo "<br /><h2>$year1-$year2 NSAA Activities Registration</h2>";
   echo "<div class='error'>$year1-$year2 membership and registration will be available on ".date("F j, Y",strtotime($memshow)).".</div>";
   echo $end_html;
   exit();
}

if($hiddensave)	//SCHOOL USER SUBMISSION
{ 
   //SAVE TO DATABASE:
   $datesub=time();
  
   //MEMBERSHIP
   $sql="SELECT * FROM schoolmembership WHERE schoolid='$schoolid'";
   $result=mysql_query($sql);
   if($membership=='x' && mysql_num_rows($result)==0)
   {
      $sql="INSERT INTO schoolmembership (schoolid,datesubmitted) VALUES ('$schoolid','$datesub')";
      $result=mysql_query($sql);
   }

   for($i=0;$i<count($regacts);$i++)
   {
      $sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
         if($participate[$i]=='x' || $possible[$i]=='x' || ($regacts[$i]=='cc_g' && $ccfee=='x'))
         {
            $sql="INSERT INTO schoolregistration (totalfee,";
	    if($level==1) $sql.="latefee,overridelatefee,checkno,amtpaid,overrideamtpaid,";
	    $sql.="schoolid,sport,participate,postseason,possible,datesub,signature";
	    if($regacts[$i]=="cc_g") $sql.=",ccfee";
 	    else if($regacts[$i]=='wr') $sql.=",wrfee";
	    $sql.=") VALUES ('$totalfee',";
	    if($level==1) $sql.="'$latefee','$overridelatefee','$checkno','$amountpaid','$overrideamtpaid',";
	    $sql.="'$schoolid','$regacts[$i]','$participate[$i]','$postseason[$i]','$possible[$i]','$datesub','".trim(addslashes($signature))."'";
	    if($regacts[$i]=='cc_g') $sql.=",'$ccfee'";
	    else if($regacts[$i]=='wr') $sql.=",'$wrfee'";
	    $sql.=")";
	    $result=mysql_query($sql);
	 }
      }
      else if($dontsave[$i]!=1) //UPDATE
      {
	 $sql="UPDATE schoolregistration SET totalfee='$totalfee',";
	 if($level==1) $sql.="latefee='$latefee',overridelatefee='$overridelatefee',amtpaid='$amountpaid',overrideamtpaid='$overrideamtpaid',checkno='$checkno',";
         if($regacts[$i]=='cc_g') $sql.="ccfee='$ccfee',";
         else if($regacts[$i]=='wr') $sql.="wrfee='$wrfee',";
	 $sql.="participate='$participate[$i]',postseason='$postseason[$i]',possible='$possible[$i]' WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";	
	 $result=mysql_query($sql);
      }
      else //UPDATE JUST THE TOTAL FEE
      {
	 $sql="UPDATE schoolregistration SET totalfee='$totalfee'";
	 if($level==1) $sql.=",latefee='$latefee',overridelatefee='$overridelatefee',checkno='$checkno',overrideamtpaid='$overrideamtpaid',amtpaid='$amountpaid'"; 
	 $sql.=" WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
         $result=mysql_query($sql);
      }
   }
   header("Location:schoolregistration.php?session=$session");
   exit();
}
if($save)	//LEVEL 1 SAVE
{
   //SAVE UPDATES TO DATABASE:

   //MEMBERSHIP
   if(strlen($mmonthpaid)==1) $mmonthpaid="0".$mmonthpaid;
   if(strlen($mdaypaid)==1) $mdaypaid="0".$mdaypaid;
   $datepaid="$myearpaid-$mmonthpaid-$mdaypaid";
   if(ereg("-00",$datepaid) || $datepaid=='--') $datepaid="0000-00-00";
   $sql="SELECT * FROM schoolmembership WHERE schoolid='$schoolid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)    //INSERT
   {
      $sql="INSERT INTO schoolregistration (datesubmitted,schoolid,amtpaid,datepaid) VALUES ('".time()."','$schoolid','$mamtpaid','$datepaid')";
      $result=mysql_query($sql);
   }
   else
   {
      if($datepaid=="0000-00-00") $mamtpaid=0;
      $sql="UPDATE schoolmembership SET amtpaid='$mamtpaid',datepaid='$datepaid' WHERE schoolid='$schoolid'";
      $result=mysql_query($sql);
   }
   
   //REGISTRATION
   for($i=0;$i<count($regacts);$i++)
   {
      if(strlen($monthpaid[$i])==1) $monthpaid[$i]="0".$monthpaid[$i];
      if(strlen($daypaid[$i])==1) $daypaid[$i]="0".$daypaid[$i];
      $datepaid="$yearpaid[$i]-$monthpaid[$i]-$daypaid[$i]";
      if(ereg("-00",$datepaid) || $datepaid=='--') $datepaid="0000-00-00";
      $sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
         $insert=1;
	 $sql0="INSERT INTO schoolregistration (datesub,totalfee,latefee,overridelatefee,amtpaid,overrideamtpaid,checkno,participate,postseason,possible,datepaid,ccfeedatepaid,ccfee,wrfeedatepaid,wrfee,schoolid,sport) VALUES ";
      }
      else
      {
 	 $insert=0;
         $sql="UPDATE schoolregistration SET totalfee='$totalfee',latefee='$latefee',overridelatefee='$overridelatefee',amtpaid='$amountpaid',overrideamtpaid='$overrideamtpaid',checkno='$checkno',participate='$participate[$i]',postseason='$postseason[$i]',possible='$possible[$i]',datepaid='$datepaid'";
      }
      if($regacts[$i]=='cc_g')	//CC FEE
      {
         if($ccfee=='x')	//CHECK THAT THEY HAVE A CC RECORD IN HERE
	 {
	    $sql2="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)==0)
	    {
	       $sql2="INSERT INTO schoolregistration (schoolid,sport) VALUES ('$schoolid','$regacts[$i]')";
	       $result2=mysql_query($sql2);
	       $insert=0;
	    }
	 }
         if(strlen($ccfeemonthpaid)==1) $ccfeemonthpaid="0".$ccfeemonthpaid;      
	 if(strlen($ccfeedaypaid)==1) $ccfeedaypaid="0".$ccfeedaypaid;
         $ccfeedatepaid="$ccfeeyearpaid-$ccfeemonthpaid-$ccfeedaypaid";
         if(ereg("-00",$ccfeedatepaid)) $ccfeedatepaid="0000-00-00";
	 $sql.=",ccfeedatepaid='$ccfeedatepaid',ccfee='$ccfee'";
      }
      else if($regacts[$i]=='wr')    //WR FEE
      {
         if(strlen($wrfeemonthpaid)==1) $wrfeemonthpaid="0".$wrfeemonthpaid;      
         if(strlen($wrfeedaypaid)==1) $wrfeedaypaid="0".$wrfeedaypaid;
         $wrfeedatepaid="$wrfeeyearpaid-$wrfeemonthpaid-$wrfeedaypaid";
         if(ereg("-00",$wrfeedatepaid)) $wrfeedatepaid="0000-00-00";
         $sql.=",wrfeedatepaid='$wrfeedatepaid',wrfee='$wrfee'";
      }
      $sql.=" WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
      $sql0.="('".time()."','$totalfee','$latefee','$overridelatefee','$amountpaid','$overrideamtpaid','$checkno','$participate[$i]','$postseason[$i]','$possible[$i]','$datepaid','$ccfeedatepaid','$ccfee','$wrfeedatepaid','$wrfee','$schoolid','$regacts[$i]')";
      if($insert==1 && ($participate[$i]=='x' || $possible[$i]=='x' || ($regacts[$i]=='cc_g' && $ccfee=='x')))
	 $result=mysql_query($sql0);
      else if($insert==0)
         $result=mysql_query($sql);
      if($regacts[$i]!='mu' && $datepaid!='0000-00-00')       //REGISTERED & PAID FOR THIS ACTIVITY - MAKE SURE THIS SCHOOL IS IN __school TABLE
      {
         $table=GetSchoolsTable($regacts[$i],$year1,$year2);	
         $sql="SELECT * FROM $table WHERE (mainsch='$schoolid' OR othersch1='$schoolid' OR othersch2='$schoolid' OR othersch3='$schoolid')";
	 $result=mysql_query($sql);
	 if(mysql_num_rows($result)==0)	//INSERT
	 {
	    $sql2="INSERT INTO $table (school,mainsch) VALUES ('".addslashes(GetSchool2($schoolid))."','$schoolid')";
	    $result2=mysql_query($sql2);
		//echo "$sql2 - $datepaid <br>";
	 }
      }
   }
}

//GET SCHOOLS
$sql2="SELECT * FROM headers ORDER BY school";
$result2=mysql_query($sql2);
$i=0; $schools=array();
while($row2=mysql_fetch_array($result2))
{
   $schools[id][$i]=$row2[id];
   $schools[school][$i]=$row2[school];
   $i++; 
} 

if($print) echo ereg_replace("nsaaforms.css","nsaaformsprint.css",$init_html);
else echo $init_html;
echo $header;

// BEGIN REGISTRATION FORM AND INSTRUCTIONS:

?>
<script language='javascript'>
function CheckAll()
{
   var regactct=document.getElementById('regactct').value;
   var d=new Date();
   var day=d.getDate();
   if(day<10) day="0"+ day;
   var month=d.getMonth()+1;
   if(month<10) month="0"+ month;
   var year=d.getFullYear();
   for(var i=0;i<regactct;i++)
   {
      var monthpaid="monthpaid"+ i;
      var daypaid="daypaid"+ i;
      var yearpaid="yearpaid"+ i; 
      var participate="participate"+ i;
      if(document.getElementById(participate).checked)
      {
  	 document.getElementById(monthpaid).value=month;
	 document.getElementById(daypaid).value=day;
	 document.getElementById(yearpaid).value=year;
      }
   }
   if(document.getElementById("ccfee").checked)
   {
      document.getElementById("ccfeemonthpaid").value=month;
      document.getElementById("ccfeedaypaid").value=day;
      document.getElementById("ccfeeyearpaid").value=year;
   }
   if(document.getElementById("wrfee").checked)
   {
      document.getElementById("wrfeemonthpaid").value=month;
      document.getElementById("wrfeedaypaid").value=day;
      document.getElementById("wrfeeyearpaid").value=year;
   }
}
function ErrorCheck()
{
   errors="";
   if(parseFloat(document.getElementById('totalfee').value)==0 || document.getElementById('totalfee').value=='')
      errors+="<tr align=left><td>You must check at least one activity in which your school will participate.</td></tr>";
   if(document.getElementById('signature').value=='')
      errors+="<tr align=left><td>You must type the name of the person submitting this form in the ELECTRONIC SIGNATURE box at the bottom of this form.</td></tr>";

   if(errors!="")
   {
      document.getElementById('errordiv').style.display="";
      document.getElementById('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"document.getElementById('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      document.getElementById('hiddensave').value="Save";
      document.forms.registerform.submit();
   }

}
function CalculateFee()
{
   var totalregacts=document.getElementById('totalregacts').value;
   var totalfee=0;
   for(var i=0;i<totalregacts;i++)
   {
      var varname="participate"+ i;
      if(document.getElementById(varname).checked) totalfee+=60;
   }
   if(document.getElementById('ccfee').checked) totalfee+=20;
   if(document.getElementById('wrfee').checked) totalfee+=150;
   if(document.getElementById('membership').checked) totalfee+=40;
   document.getElementById('totalfee').value=totalfee.toFixed(2);
   var amountpaid=parseFloat(document.getElementById('amountpaid').value);
   var latefee=parseFloat(document.getElementById('latefee').value);
   var amountdue=totalfee+latefee-amountpaid;
   document.getElementById('amountdue').value=amountdue.toFixed(2);
}
</script>
<?php

$sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' ORDER BY datesub DESC LIMIT 1";
$result=mysql_query($sql);
if($row=mysql_fetch_array($result))
{
   $submitted=1; $datesub=$row[datesub];
   $signature=$row[signature]; 
   $totalfee=GetRegistrationAmount($schoolid,"totalfee",''); //$row[totalfee];	//TOTAL FEE DUE
   $amountpaid=GetRegistrationAmount($schoolid,"paid","");
   $fallamountpaid=GetRegistrationAmount($schoolid,"paid","Fall");
   $winteramountpaid=GetRegistrationAmount($schoolid,"paid","Winter");
   $springamountpaid=GetRegistrationAmount($schoolid,"paid","Spring");
   $fallfee=GetRegistrationAmount($schoolid,"totalfee","Fall");
   $winterfee=GetRegistrationAmount($schoolid,"totalfee","Winter");
   $springfee=GetRegistrationAmount($schoolid,"totalfee","Spring");
   $amountdue=GetRegistrationAmount($schoolid,"due","");
   $latefee=GetRegistrationAmount($schoolid,"late","");
}
else
{
   $totalfee=GetRegistrationAmount($schoolid,"totalfee",'');
   $amountdue=GetRegistrationAmount($schoolid,"due","");
}

if($print!=1) 
{
   echo "<br><form method='post' action='schoolregistration.php' name='registerform'>";
   echo "<input type=hidden name='session' value='$session'>";
   echo "<input type=hidden name='schoolid' value='$schoolid'>";
   echo "<input type=hidden name='filtersubmitted' value='$filtersubmitted'>";
   echo "<input type=hidden name='filterschoolid' value='$filterschoolid'>";
}
if($level==1 && $print!=1) echo "<br><a href=\"schoolregadmin.php?session=$session&filtersubmitted=$filtersubmitted&filterschoolid=$filterschoolid\">Return to School Registration Main Menu</a><br><br>";
echo "<table cellspacing=0";
if(!$print) echo " cellpadding=4 frame=all rules=all style=\"border:#a0a0a0 1px solid;\">";
else echo " cellpadding=1>";
echo "<caption><b>$year1-$year2 ACTIVITIES REGISTRATION FORM for <u>$school</u></b>";
if($print==1) 
{
   echo " - as of ".date("F j, Y")."<br><div class='normalwhite' style='margin:3px;padding:3px;width:800px;'>";
   echo "<p><b>FAILURE TO SUBMIT PAYMENT BY THE DUE DATE COULD RESULT IN THE LOSS OF CATASTROPHIC INSURANCE
 FOR EACH ACTIVITY YOU HAVE NOT PAID FOR.</b></p><p><b>Furthermore, <u>an administration fee will be charged</u> in the event of a late registration submission, as per the bylaws:</b></p><blockquote>A.R.2.11: The penalty for late submission of registration forms and payment shall be an administrative fee of $50 per occurrence, plus other penalties as the Board of Directors shall assess.</blockquote></div>";
}
else echo "<br>";
/***** CHECK FOR ERRORS *****/
if($print!=1)	
{
   //POSSIBLE ERRORS: 
   //1 - School is in a co-op but they did NOT check this sport
   //2 - Declared in a sport but did NOT check it (removed in June 2014)
   //3 - Past a due date and didn't check participating for a Possible sport
   //1:
   $error1="";
   for($i=0;$i<count($regacts);$i++) 
   {
      $sql2="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(IsInCoop($schoolid,$regacts[$i]) && $row2[participate]!='x' && (!ereg("_g",$regacts[$i]) || !IsBoysOnly($schoolid)) && (!ereg("_b",$regacts[$i]) || !IsGirlsOnly($schoolid)))
         $error1.=$regacts[$i].",";
   }
   if($error1!='') $error1=substr($error1,0,strlen($error1)-1);
   //3:
   $error3="";
   for($i=0;$i<count($regacts);$i++)
   {
      $sql2="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($regactseasons[$i]=="Winter" && PastDue($winterdate,0) && $row2[possible]=='x' && $row2[participate]!='x')
         $error3.=$regacts[$i].",";
      else if($regactseasons[$i]=="Spring" && PastDue($springdate,0) && $row2[possible]=='x' && $row2[participate]!='x')
	 $error3.=$regacts[$i].",";
   }
   //BE SURE THEY'VE SUBMITTED A FORM BEFORE SHOWING THEM ERRORS:
      $sql2="SELECT * FROM schoolregistration WHERE schoolid='$schoolid'";
      $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0 && ($error1!='' || $error3!=''))	//THERE ARE ERRORS
   {
      echo "<div class='error' style='padding:10px;margin:10px;width:500px;'><p><b><i>Please note the following <u>WARNINGS</u> regarding your NSAA Activities Registration form:</b></i></p>";
      if($error1!='')
      {
	 echo "You are in a <u>CO-OP</u> for the following activities but did NOT mark that you will PARTICIPATE in them:";
  	 $sp=split(",",$error1);
	 for($i=0;$i<count($sp);$i++)
	 {
	    echo "<p style='margin:5px 5px 5px 100px;'>".GetActivityName($sp[$i])."</p>";
	 }
         echo "<p><i>Please contact the NSAA if you are NOT supposed to be listed in a co-op for these activities. Otherwise, you MUST register for these activities, EVEN IF YOU ARE NOT THE HEAD SCHOOL.</i></p>";
	 echo "<br>";
      }
      if($error3!='')
      {
         echo "You marked that it is <u>POSSIBLE</u> you will participate in the following activities. It is now PAST THE DUE DATE for these activities, and you did NOT mark that you will PARTICIPATE in them:";
         $sp=split(",",$error3);
         for($i=0;$i<count($sp);$i++)
         {
            echo "<p style='margin:5px 5px 5px 100px;'>".GetActivityName($sp[$i])."</p>";
         }
	 echo "<p><i>Please UNCHECK the box in the POSSIBLE column if you have no intentions of participating in these activities. Otherwise, you MUST complete registration in order to participate.</i></p>";
         echo "<br>";
      }
      echo "</div>";
   }
}
/***** INSTRUCTIONS *****/
if($amountdue>0 && $submitted==1 && $print!=1)	//INSTRUCTIONS IF THE FORM HAS BEEN FILLED OUT
{
   echo "<div class='alert'>";
   
      echo "<p><b>You have filled out this form, but the NSAA has NOT indicated that you have paid in full.</b></p><br>";
      echo "<p><u>If you have already mailed your payment to the NSAA</u>, check back at a later date to confirm that we have received your payment. The \"REGISTRATION FEE PAID?\" column below will be updated by the NSAA once they receive your payment.</p><br>";
      echo "<p><u>If you need to mail a payment to the NSAA</u>, please follow these steps:</p>";
      echo "<ol><li><a href=\"schoolregistration.php?session=$session&schoolid=$schoolid&print=1\" target=\"_blank\">Print a copy of this form</a>.</li><br>";
      echo "<li>Have your <b>Superintendent or President sign and date the form</b>.</li><br>";
      echo "<li><b>Mail the signed copy of the form with your payment to:</b><br><br>";	
	echo "NSAA<br>500 Charleston Street, Suite 1<br>Lincoln NE 68508</li>";
      echo "</ol>";
      echo "<p><b>MEMBERSHIP FEES are due by <u>$memdate2</u>.</b></p>";
      echo "<p><b>PAYMENTS for each season's activities are DUE ON THE FOLLOWING DATES:</b></p><ul>";	
        echo "<li>Fall Sports: <b>$falldate2</b></li>";
        echo "<li>Winter Sports and Non-Athletic Activities: <b> $winterdate2</b></li>";
        echo "<li>Spring Sports: <b>$springdate2</b></li></ul>";
   echo "<p><b>FAILURE TO SUBMIT PAYMENT BY THE DUE DATE COULD RESULT IN THE LOSS OF CATASTROPHIC INSURANCE FOR EACH ACTIVITY YOU HAVE NOT PAID FOR.</b></p><p><br><b>Furthermore, <u>an administration fee will be charged</u> in the event of a late registration submission, as per the bylaws:</b><blockquote>A.R.2.11: The penalty for late submission of registration forms and payment shall be an administrative fee of $50 per occurrence, plus other penalties as the Board of Directors shall assess.</blockquote></p>";
   echo "</div><br>";
}
else if($print!=1)	//INSTRUCTIONS FOR A BRAND NEW FORM OR PAID-IN-FULL FORM
{
   echo "<br>";
   if($amountdue==0 && $submitted==1)
      echo "<div class='normalwhite' style='width:500px;font-size:9pt;padding:10px;'><p><b>THANK YOU!</b></p><p>The NSAA has received payment in full for the activities your school has indicated intention to participate in below.</p><p><a href=\"schoolregistration.php?session=$session&schoolid=$schoolid&print=1\" target=\"_blank\">Print a copy of this form</a> for your records.</p><p>You may still wish to submit registration for activities for which the due date has not yet passed. To do so, please follow the instructions below.</p></div><br>";
   echo "<div class='alert' style=\"width:800px;\"><b>INSTRUCTIONS:</b>";
   echo "<p>Please fill out the form below for each activity in which your school will participate (or will possibly participate) for the $year1-$year2 school year. When you are finished, <b><u>click \"SUBMIT TO THE NSAA\"</b></u> to submit the information to the NSAA office. You will then <b><u>PRINT A COPY</b></u> of this form, <b><u>SIGN THE FORM</b></u> and <b><u>MAIL IT WITH YOUR PAYMENT TO</u></b>:</p><br>";
   echo "<p style='text-align:center;'>NSAA<br>500 Charleston Street, Suite 1<br>Lincoln NE 68508</p><br>";
   echo "PLEASE NOTE:<ul>";
   echo "<li>The registration fee is <b><u>$60.00 per activity</b></u> in which your school will participate. The total amount due will calculate at the bottom of the form, as you fill it out.</li>";
   echo "<li>If a <b><u>cooperative sponsorship agreement</b></u> exists, select the name(s) of the other school(s) for each activity affected. <b>ALL SCHOOLS IN A CO-OP MUST PAY THE REGISTRATION FEE.</b></li>";
   echo "<li><b>MEMBERSHIP FEES are due by <u>$memdate2</u>.</b></li>";
   echo "<li>Only if a final decision has not been made regarding participation in an activity, indicate if there is a <b><u>possibility</b></u> of participation. The <b><u>FINAL DATES FOR REGISTRATION ARE:</b></u><br><br><ul>";
	echo "<li>Fall Sports: <b>$falldate2</b> ($50 late fee if paid after 7/1/$year1)";
   	if(PastDue($falldate,0)) echo " <label class=red>- <u>PAST DUE</u></label>";
	echo "</li>";
	echo "<li>Winter Sports & Non-Athletic Activities: <b> $winterdate2</b> ($50 late fee if paid after 9/1/$year1)";
        if(PastDue($winterdate,0)) echo " <label class=red>- <u>PAST DUE</u></label>";
        echo "</li>";
	echo "<li>Spring Sports: <b>$springdate2</b> ($50 late fee if paid after 1/1/$year2)";
        if(PastDue($springdate,0)) echo " <label class=red>- <u>PAST DUE</u></label>";
        echo "</li>";
	echo "</ul>";
   echo "<br><p><b>FAILURE TO SUBMIT PAYMENT BY THE DUE DATE COULD RESULT IN THE LOSS OF CATASTROPHIC INSURANCE
 FOR EACH ACTIVITY YOU HAVE NOT PAID FOR.</b></p><p><b>An <u>administration fee will be charged</u> in the event of a late registration submission, as per the bylaws:</b></p><blockquote>A.R.2.11: The penalty for late submission of registration forms and payment shall be an administrative fee of $50 per occurrence, plus other penalties as the Board of Directors shall assess.</blockquote>";
   echo "</li>";
   echo "</ul>";
   echo "</div><br>";
}
/***** MEMBERSHIP *****/
$sql2="SELECT * FROM schoolmembership WHERE schoolid='$schoolid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($print!=1)
   echo "<div class=\"alert\" style=\"background-color:#00CCff !important;text-align:center;width:400px;\"><h1>";
else
   echo "<h2>";
echo "<input type=checkbox name=\"membership\" id=\"membership\" value=\"x\" onClick=\"CalculateFee();\"";
echo " checked";
if(((mysql_num_rows($result2)>0 || PastDue($memdate,0) || !PastDue($memshow,-1)) && $level!=1) || $print==1) echo " disabled";
echo ">&nbsp;&nbsp;&nbsp;NSAA Membership ($40.00)";
if($print!=1) echo "</h1>";
if(!PastDue($memshow,-1) && $level!=1 && $print!=1) echo "<p><i>NSAA Membership will be available on $memshow2.</i></p>";
//MEMBERSHIP PAID?
$datepaid=split('-',$row2[datepaid]);
if($print!=1 && $level==1)   //LEVEL 1 (NOT PRINTING)
{
   echo "<p>Date Paid: <input type=text size=2 name=\"mmonthpaid\" id=\"mmonthpaid\" maxlength=2 value=\"$datepaid[1]\">/<input type=text size=2 name=\"mdaypaid\" id=\"mdaypaid\" maxlength=2 value=\"$datepaid[2]\">/<input type=text size=4 name=\"myearpaid\" id=\"myearpaid\" maxlength=4 value=\"$datepaid[0]\"><input type=hidden name=\"mamtpaid\" value=\"40\"></p>";
   echo "<div id='mtodaysdate' class='help' style='margin-left:50px;float:left;";
   echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('mmonthpaid').value='".date("m")."';document.getElementById('mdaypaid').value='".date("d")."';document.getElementById('myearpaid').value='".date("Y")."';\">Today's Date</div>";
   echo "<div id='cleardate' class='help' style='margin-right:50px;float:right;";
   echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('mmonthpaid').value='00';document.getElementById('mdaypaid').value='00';document.getElementById('myearpaid').value='0000';\">Clear Date</div>";
}
else if(ereg("00-",$row2[datepaid]))   //NON-LEVEL 1 OR LEVEL 1 PRINT  AND  DIDN'T PAY YET
{
   if($print!=1) echo "<label class='red'>NOT PAID</label> (The NSAA will mark PAID when payment is received.)";          //SHOW NOT PAID ON FORM
}
else if(mysql_num_rows($result2)>0 && ($level!=1 || $print==1))  //NON LEVEL 1 OR PRINT  AND  PAID: SHOW DATE PAID
{
   echo "&nbsp;<label class='green'>PAID $datepaid[1]/$datepaid[2]/$datepaid[0]</label>";
}
if($print!=1) echo "<div style=\"clear:both;\"></div></div><br>";
else echo "</h2><br><br>";

echo "</caption>";

$columns="<tr align=center><td><b>Activity</b></td><td><b>YES!</b> We will<br>participate in<br>this activity.</td><td><b>YES!</b> We wish to<br>be involved in<br>district and state<br>competition.</td>";
if($print==1) $columns.="<td><b>REGISTRATION<br>FEE</b></td>";
else $columns.="<td><b>REGISTRATION<br>FEE PAID?</b><br>";
if($level==1)
{
   //CHECK ALL option
   $columns.="<div id='checkall' class='help' style='float:left;text-align:center;width:80px;cursor:hand;' onClick=\"CheckAll();\">Check ALL as PAID</div>";
}
else
   $columns.="(The NSAA will<br>update this column.)";
$columns.="</td>";
$columns.="<td><b>COOPERATING SCHOOLS</b> (If applicable)<br>* NOTE: ALL cooperating schools must be marked as PAID<br>in order for the entire team to be considered \"registered.\"</td></tr>";

$fallcolumns="<tr align=center><td><b>Activity</b></td><td><b>YES!</b> We will<br>participate in<br>this activity.</td><td><b>YES!</b> We wish to<br>be involved in<br>district and state<br>competition.</td>";
if($print==1) $fallcolumns.="<td><b>REGISTRATION<br>FEE</b></td>";
else $fallcolumns.="<td><b>REGISTRATION<br>FEE PAID?</b><br>";
if($level==1)
{
   //CHECK ALL option
   $fallcolumns.="<div id='checkall' class='help' style='float:left;text-align:center;width:80px;cursor:hand;' onClick=\"CheckAll();\">Check ALL as PAID</div>";
}
else
   $fallcolumns.="(The NSAA will<br>update this column.)";
$fallcolumns.="</td>";
$fallcolumns.="<td><b>COOPERATING SCHOOLS</b> (If applicable)<br>* NOTE: ALL cooperating schools must be marked as PAID<br>in order for the entire team to be considered \"registered.\"</td></tr>";

$printcolumns="<tr align=center><td><b>Activity</b></td><td><b>YES!</b> We will<br>participate</td><td><b>YES!</b> in district<br>and state comp</td><td><b>It's POSSIBLE</b></td><td><b>FEE</b></td>";
$printcolumns.="<td><b>COOPERATING SCHOOLS</b> (If applicable)</td></tr>";

$k=0; $curseason="";
for($i=0;$i<count($regacts);$i++)
{
   $sql="SELECT * FROM schoolregistration WHERE schoolid='$schoolid' AND sport='$regacts[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0) 
   {
      $curoverridelatefee=$row[overridelatefee];
      $curcheckno=$row[checkno];
      $curoverrideamtpaid=$row[overrideamtpaid];
      $curamtpaid=$row[amtpaid];
   }
   if($row[datepaid]!='0000-00-00' && mysql_num_rows($result)>0) $paid=1;
   else $paid=0;
   if($paid==1) echo "<input type=hidden name=\"dontsave[$i]\" value=\"1\">";

   if($curseason!=$regactseasons[$i])
   {
      $curseason=$regactseasons[$i]; 
      if($print!=1) echo "<tr align=center bgcolor='yellow'><td colspan=5><h2 style='margin:8px;padding:0px;'>$curseason Sports";
      else echo "<tr align=center><td colspan=5><p style='margin:2px;'><b>$curseason Sports";
      if($curseason=="Winter") { echo " & Non-Athletic Activities - due $winterdate2"; $curduedate=$winterdate; }
      else if($curseason=="Fall") { echo " - due $falldate2"; $curduedate=$falldate; }
      else { echo " - due $springdate2"; $curduedate=$springdate; }
      if($print==1) echo "</b></p>";
      else echo "</h2>";
   echo "</td></tr>";
      if($curseason=="Fall" && $print==1) echo $printcolumns;
      else if($curseason=="Fall") echo $fallcolumns;
      else if($print!=1) echo $columns;
   }
   else if($i==0) { echo $columns; $k=0; }
   echo "<tr align=center";
   if($print!=1 && PastDue($curduedate,0)) echo " bgcolor='#f0f0f0'";
   echo ">";
   echo "<td align=left>".strtoupper(GetActivityName($regacts[$i]))."</td>";
   echo "<td><input type=checkbox name=\"participate[$i]\" id=\"participate".$i."\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('postseason".$i."').checked=true;";
   //if($regactseasons[$i]!='Fall') echo "document.getElementById('possible".$i."').checked=false;";
   if(($regacts[$i]=='cc_b' || $regacts[$i]=='cc_g') && (!IsInCoop($schoolid,$regacts[$i]) || IsHeadSchool($schoolid,$regacts[$i])))
      echo " document.getElementById('ccfee').checked=true;";
   if($regacts[$i]=='wr' && (!IsInCoop($schoolid,$regacts[$i]) || IsHeadSchool($schoolid,$regacts[$i])))
      echo " document.getElementById('wrfee').checked=true;";
   echo " } else { document.getElementById('postseason".$i."').checked=false;";
   if($regacts[$i]=='wr') 
      echo " document.getElementById('wrfee').checked=false;"; 
   echo " } CalculateFee(); \"";
   if($row[participate]=='x') echo " checked";
   if(($paid==1 && $level!=1) || $print==1) echo " disabled";
   echo "></td>";
   echo "<td><input type=checkbox name=\"postseason[$i]\" id=\"postseason".$i."\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('participate".$i."').checked=true; } CalculateFee();\"";
   if($row[postseason]=='x') echo " checked";
   if(($paid==1 && $level!=1) || $print==1) echo " disabled";
   echo "></td>";
   echo "<td align=center>";

   //PAID?
   $datepaid=split('-',$row[datepaid]);
	//SHOW IF PAID:
   if($print!=1 && $level==1)	//LEVEL 1 (NOT PRINTING)
   {
      echo "Date Paid: <input type=text size=2 name=\"monthpaid[$i]\" id=\"monthpaid".$i."\" maxlength=2 value=\"$datepaid[1]\">/<input type=text size=2 name=\"daypaid[$i]\" id=\"daypaid".$i."\" maxlength=2 value=\"$datepaid[2]\">/<input type=text size=4 name=\"yearpaid[$i]\" id=\"yearpaid".$i."\" maxlength=4 value=\"$datepaid[0]\"><br>";
      echo "<div id='todaysdate".$i."' class='help' style='float:left;";
      echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('monthpaid".$i."').value='".date("m")."';document.getElementById('daypaid".$i."').value='".date("d")."';document.getElementById('yearpaid".$i."').value='".date("Y")."';\">Today's Date</div>";
      echo "<div id='cleardate".$i."' class='help' style='float:right;";      
      echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('monthpaid".$i."').value='00';document.getElementById('daypaid".$i."').value='00';document.getElementById('yearpaid".$i."').value='0000';\">Clear Date</div>";
   }
   else if(mysql_num_rows($result)==0 || $row[participate]!='x')	//NON-LEVEL 1 OR LEVEL 1 PRINT  AND  DIDN'T CHECK THIS SPORT
   {
      echo "&nbsp;";
   }
   else if(ereg("00-",$row[datepaid]) && $row[participate]=='x') 			//NON-LEVEL 1 OR LEVEL 1 PRINT  AND  CHECKED SPORT  AND  DIDN'T PAY YET
   {
      if($print==1) echo "$60.00";	//PRINT INVOICE WITH AMOUNT DUE
      else echo "<label class='red'>NOT PAID</label>";		//SHOW NOT PAID ON FORM
   }
   else if($row[participate]=='x' && ($level!=1 || $print==1))	//NON LEVEL 1 OR PRINT  AND  PAID: SHOW DATE PAID
   {
      echo "<label class='green'>PAID $datepaid[1]/$datepaid[2]/$datepaid[0]</label>";
   }
   echo "</td><td align=left";
   $schooltable=GetSchoolsTable($regacts[$i],$year1,$year2);
   $manageteamssport=preg_replace("/(school)/","",$schooltable);
   $manageteamssport=preg_replace("/[0-9]/","",$manageteamssport);
   $sid=GetSID2(GetSchool2($schoolid),$manageteamssport);
   if($sid=="NO SID FOUND")
   {
      if($level==1 && $print!=1)
      {
         if($manageteamssport=="mu")
            echo ">[Music schools are maintained in a separate table only when<br>they include co-ops. You can edit the Music Co-ops <a href=\"mu/mucoops.php?session=$session\" target=\"_blank\" class=small>HERE</a>.]";
         else
         {
            if(IsRegistered2011($schoolid,$regacts[$i])) echo " bgcolor='#ff0000'";
            echo "><i>$school is not listed under <a class=small target=\"_blank\" href=\"../calculate/wildcard/schools.php?sport=$manageteamssport&session=$session&function=report\">Manage Schools > ".GetActivityName($manageteamssport)."</a><br>(the master list of ".GetActivityName($manageteamssport)." teams).</i>";
         }
      }
   }
   else
   {
      if($level==1 && $print!=1)
      {
         if($manageteamssport=="mu") 
	    echo ">[Music schools are only maintained in a separate table when they include co-ops.]";
         else
         {
            $teamname=GetSchoolName($sid,$manageteamssport);
            if($teamname=="") $teamname="<label class=red>?????</label>";
            echo ">$school is listed under <a class=small target=\"_blank\"href=\"../calculate/wildcard/schools.php?function=editschool&schid=$sid&sport=$manageteamssport&session=$session\">Manage Schools > ".GetActivityName($manageteamssport)."</a> as:<br><br><b>".GetActivityName($manageteamssport)." TEAM NAME:</b>&nbsp;".$teamname."<br>";
            $headschool=GetMainSchoolName($sid,$manageteamssport);
            if($headschool=="") $headschool="<label class=red>?????</label>";
            echo "<b>Head School:</b>&nbsp;".$headschool."<br>";
            $teamclass=GetClass($sid,$manageteamssport);
	    if($teamclass=="") $teamclass="<label class=red>?????</label>";
	    echo "<b>Class:</b> $teamclass<br>";
         }
      }
      $coopschs=GetCoopSchools($schoolid,$manageteamssport);
      $coopsch=split(";",$coopschs);
	$coopheader=0;
      for($c=0;$c<count($coopsch);$c++)
      {
         if(GetSchool2($coopsch[$c])!="" && $coopsch[$c]>0 && $schoolid!=$coopsch[$c])	//find this school's registration form and see if THEY paid for this activity
         {
	    if($coopheader==0)
	    {
	       echo "<b>Co-oping Schools:</b><br>"; $coopheader=1;
	    }
	    echo GetSchool2($coopsch[$c]);
	    $sql2="SELECT * FROM schoolregistration WHERE schoolid='$coopsch[$c]' AND sport='$regacts[$i]'";
	    $result2=mysql_query($sql2);
 	    if($row2=mysql_fetch_array($result2))
	    {
	       if($row2[datepaid]!='0000-00-00') echo "<label class='green'> - PAID</label>";
	       else echo " <label class='red'>- NOT PAID</label>";
	       if($level==1) echo " <a class=small href=\"schoolregistration.php?schoolid=".$coopsch[$c]."&filterschoolid=$filterschoolid&filtersubmitted=$filtersubmitted&session=$session\">Go to Form</a>";
	    }
            else 
            {
               if($coopheader==0)
               {
                  echo "<b>Co-oping Schools:</b><br>"; $coopheader=1;
               }
               echo " <label class='red'>- NOT PAID</label> ";
               if($level==1)
               {
                  $sql2="SELECT * FROM schoolregistration WHERE schoolid='$coopsch[$c]'";
                  $result2=mysql_query($sql2);               
	          if($row2=mysql_fetch_array($result2))
                     echo " <a class=small href=\"schoolregistration.php?schoolid=$coopsch[$c]&filterschoolid=$filterschoolid&filtersubmitted=$filtersubmitted&session=$session\">Go to Form</a>";
                  else
                     echo " No Form on File";
               }
            }
	    echo "<br>";
         }
      }
   }//end if SID FOUND
   echo "</td>";
   echo "</tr>";
   if($regacts[$i]=='cc_g')
   {
      echo "<tr align=center>";
      if($print==1) echo "<td align=right colspan=5>";
      else echo "<td align=right>";
      echo "Cross-Country Chip Timing Fee: ";
      if(!$print) echo "</td><td align=left colspan=2 width='300px'>";
      echo "<input type=checkbox name=\"ccfee\" id=\"ccfee\" value='x' onClick=\"CalculateFee();\"";
      if(($paid==1 && $level!=1) || $print==1) echo " disabled";
      if($row[ccfee]=='x') echo " checked";
      if($print!=1)
      {
 	 echo "> If you are registering for <b>Girls AND/OR Boys Cross-Country</b>, you will need to pay a <b>$20.00 Chip Timing fee</b>.";
         echo " Only uncheck this box if you are <u>in a co-op</u> and are <u>NOT</u> the head school.</td>";
      }
      else echo "> $20.00 (If you are in a co-op, ONLY the Head School needs to pay this fee.)</td>";
      if($print!=1)
      {
      echo "<td align=center>";
      $datepaid=split('-',$row[ccfeedatepaid]);
      if($level==1 && $print!=1)
      {
         echo "Date Paid: <input type=text size=2 name=\"ccfeemonthpaid\" id=\"ccfeemonthpaid\" maxlength=2 value=\"$datepaid[1]\">/<input type=text size=2 name=\"ccfeedaypaid\" id=\"ccfeedaypaid\" maxlength=2 value=\"$datepaid[2]\">/<input type=text size=4 name=\"ccfeeyearpaid\" id=\"ccfeeyearpaid\" maxlength=4 value=\"$datepaid[0]\"><br>";
         echo "<div id='todaysdate".$i."' class='help' style='float:left;";      
   	 echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('ccfeemonthpaid').value='".date("m")."';document.getElementById('ccfeedaypaid').value='".date("d")."';document.getElementById('ccfeeyearpaid').value='".date("Y")."';\">Today's Date</div>";
      	 echo "<div id='cleardate".$i."' class='help' style='float:right;";      
	 echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('ccfeemonthpaid').value='00';document.getElementById('ccfeedaypaid').value='00';document.getElementById('ccfeeyearpaid').value='0000';\">Clear Date</div>";
      }
      else if($paid!=1 || mysql_num_rows($result)==0)
         echo "&nbsp;";
      else if(ereg("00-",$row[ccfeedatepaid]))
      {
         if(IsHeadSchool($schoolid,'ccb')) 
         {
            if($print==1) echo "$20.00";
            else echo "<label class='red'>NOT PAID</label>";
         }
         else if($print==1) echo "&nbsp;";
         else
         {
            $headsch=GetCoopHeadSchool($schoolid,'ccg');
            $headid=GetSchoolID2($headsch);
            $sql3="SELECT ccfeedatepaid FROM schoolregistration WHERE schoolid='$headid' AND sport='cc_g'";
            $result3=mysql_query($sql3);
            $row3=mysql_fetch_array($result3);
            if(ereg("00-",$row3[0]) || mysql_num_rows($result3)==0) echo "<label class='red'>NOT PAID</label><br>($headsch must pay)";
            else 
            {
               $dpaid=explode("-",$row3[0]);
               echo "<label class='green'>PAID $dpaid[1]/$dpaid[2]/$dpaid[0]</label><br>(by $headsch)";
            }
         }
      }
      else if($level!=1 || $print==1)
         echo "<label class='green'>PAID $datepaid[1]/$datepaid[2]/$datepaid[0]</label>";
      echo "</td>";
      echo "<td>&nbsp;</td>";
      }//end if not print
      echo "</tr>";
   }
   else if($regacts[$i]=='wr')
   {
      echo "<tr align=center>";
      if($print==1) echo "<td align=right colspan=5>";
      else echo "<td align=right>";
      echo "<b>TrackWrestling Fee </b><br>(Weight Management Program, <br> Statistics Program, <br>High School Tournament Administration Fees):<br>(required)";//echo "<b><u>Weight Management Fee:</b></u><br>(required)";
      if(!$print) echo "</td><td align=left colspan=2 width='300px'>";
      echo "<input type=checkbox name=\"wrfee\" id=\"wrfee\" value='x' onClick=\"if(this.checked) { document.getElementById('participate".$i."').checked=true; document.getElementById('postseason".$i."').checked=true; } CalculateFee();\"";
      if(($paid==1 && $level!=1) || $print==1) echo " disabled";
      if($row[wrfee]=='x') echo " checked";
      if($print!=1)
      {
	 echo "> If you are registering for <b>Wrestling</b>, you will need to pay a <b>$150.00 TrackWrestling Fee</b>.";
         echo " Only uncheck this box if you are <u>in a co-op</u> and are <u>NOT</u> the head school.</td>";
      }
      else echo "> $150.00 (If you are in a co-op, ONLY the Head School needs to pay this fee.)</td>";
      $datepaid=split('-',$row[wrfeedatepaid]);
      if(!$print)  
      {
         echo "<td align=center>";
      if($level==1 && $print!=1)
      {
         echo "Date Paid: <input type=text size=2 id=\"wrfeemonthpaid\" name=\"wrfeemonthpaid\" maxlength=2 value=\"$datepaid[1]\">/<input type=text size=2 id=\"wrfeedaypaid\" name=\"wrfeedaypaid\" maxlength=2 value=\"$datepaid[2]\">/<input type=text size=4 id=\"wrfeeyearpaid\" name=\"wrfeeyearpaid\" maxlength=4 value=\"$datepaid[0]\"><br>";
         echo "<div id='todaysdate".$i."' class='help' style='float:left;";               
	 echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('wrfeemonthpaid').value='".date("m")."';document.getElementById('wrfeedaypaid').value='".date("d")."';document.getElementById('wrfeeyearpaid').value='".date("Y")."';\">Today's Date</div>";
         echo "<div id='cleardate".$i."' class='help' style='float:right;";
	echo "text-align:center;width:80px;cursor:hand;' onClick=\"document.getElementById('wrfeemonthpaid').value='00';document.getElementById('wrfeedaypaid').value='00';document.getElementById('wrfeeyearpaid').value='0000';\">Clear Date</div>";
      }
      if($paid!=1)
         echo "&nbsp;";
      else if(ereg("00-",$row[wrfeedatepaid]))
      {      
         if(IsHeadSchool($schoolid,'wr')) 
    	 {
	    //if($print==1) echo "$30.00";
	    if($print==1) echo "$150.00";
	    else echo "<label class='red'>NOT PAID</label>";
         }
	 else if($print==1) echo "&nbsp;";
	 else
	 {
	    $headsch=GetCoopHeadSchool($schoolid,'wr');
	    $headid=GetSchoolID2($headsch);
	    $sql3="SELECT wrfeedatepaid FROM schoolregistration WHERE schoolid='$headid' AND sport='wr'";
	    $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	    if(ereg("00-",$row3[0]) || mysql_num_rows($result3)==0) echo "<label class='red'>NOT PAID</label><br>($headsch must pay)";
	    else 
	    {
	       $dpaid=explode("-",$row3[0]);
	       echo "<label class='green'>PAID $dpaid[1]/$dpaid[2]/$dpaid[0]</label><br>(by $headsch)";
	    }
	 }
      }
      else if($level!=1 || $print==1)
         echo "<label class='green'>PAID $datepaid[1]/$datepaid[2]/$datepaid[0]</label>";
         echo "</td>";
         echo "<td>&nbsp;</td>";
      }//end if not print
      echo "</tr>";
   }	//END IF wr
   else if($regacts[$i]=='sp' || $regacts[$i]=='pp')
   {
      echo "<tr align=right><td colspan=5><i>Your indication to participate in the district <b>".GetActivityName($regacts[$i])."</b> contest obligates your school to pay your portion of district entry fees.</i></td></tr>";
   }
   $k++;
}

echo "<tr><td colspan=3 align=right><input type=hidden name='regactct' id='regactct' value='".count($regacts)."'>";
if($print==1) echo "<b>TOTAL FEES:</b>";
else echo "<h3 style='margin:8px;padding:0px;'>TOTAL FEES:</h3>";
echo "</td><td align=center>";
$totalfee=number_format($totalfee,2,'.','');
if($print==1) echo "$".$totalfee;
else echo "$<input type=text readOnly=TRUE size=8 name='totalfee' id='totalfee' value='$totalfee'>";
echo "</td>";
echo "<td>&nbsp;</td>";
echo "</tr>";
echo "<tr valign=center><td colspan=3 align=right>";
if($print==1) echo "<b>LATE FEES:</b>";
else echo "<h3 style='margin:8px;padding:0px;'>LATE FEES:</h3>";
echo "</td><td align=center>";
$latefee=number_format($latefee,2,'.','');
if($print==1) echo "$".$latefee;
else 
{
   echo "$<input type=text";
   if($level!=1) echo " readOnly=TRUE";
   echo " size=8 name='latefee' id='latefee' value='$latefee'>";
}
echo "</td>";
echo "<td><i>A $50.00 late fee is assessed for each SEASON<br>in which the school is late submitting payment.</i>";
if($level==1 && $print!=1)
{
   echo "<br><input type=checkbox name=\"overridelatefee\" value=\"x\"";
   if($curoverridelatefee=='x') echo " checked";
   echo "> <b>OVERRIDE LATE FEE</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(This will allow the NSAA to indicate the late fee amount.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Checking this box DISABLES automatic calculation<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;of the late fee for this school.)";
}
echo "</td>";
echo "</tr>";
echo "<tr><td colspan=3 align=right>";
if($print==1) echo "<b>TOTAL AMOUNT PAID:</b>";
else echo "<h3 style='margin:8px;padding:0px;'>TOTAL AMOUNT PAID:</h3>";
echo "</td><td align=center>";
$amountpaid=number_format($amountpaid,2,'.','');
if($print==1) echo "$".$amountpaid;
else 
{
   echo "$<input type=text size=8 name='amountpaid' id='amountpaid' value='$amountpaid'";
   if($level!=1) echo " readOnly=TRUE";
   echo ">";
}
echo "</td>";
if($level==1 && $print!=1)
{
   echo "<td>CHECK NO. <input type=text size=20 name=\"checkno\" id=\"checkno\" value=\"$curcheckno\">";
   echo "<br><input type=checkbox name=\"overrideamtpaid\" value=\"x\"";
   if($curoverrideamtpaid=='x') echo " checked";
   echo "> <b>OVERRIDE AMOUNT PAID</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(This will allow the NSAA to indicate the amount paid manually.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Checking this box DISABLES automatic calculation<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;of the amount paid by this school.)</td>";
}
else
   echo "<td>&nbsp;</td>";
echo "</tr>";
echo "<tr><td colspan=3 align=right>";
if($print==1) echo "<b>TOTAL AMOUNT DUE:</b>";
else echo "<h3 style='color:#ff0000;margin:8px;padding:0px;'>TOTAL AMOUNT DUE:</h3>";
echo "</td><td align=center>";
$amountdue=number_format($amountdue,2,'.','');
if($print==1) echo "<h2 style='padding:0px;margin:2px 0px;'><u>$".$amountdue."</u></h2>";
else echo "$<input type=text readOnly=TRUE size=8 name='amountdue' id='amountdue' value='$amountdue'>";
echo "</td>";
echo "<td>&nbsp;</td>";
echo "</tr>";

echo "</table>";
if($print!=1)
{
echo "<div id=\"errordiv\" class=\"searchresults\" style=\"left:40%;width:400px;display:none;\"></div><br>";
echo "<input type=hidden name=\"totalregacts\" id=\"totalregacts\" value=\"".count($regacts)."\">";
}
if($print==1)
{
   //echo "<br><br><i>Failure to pay the fee by the proper date may result in your school not being assigned to a district or state meet and not being sent the materials needed for the activity.</i><br><br>";
   echo "<br><b>Signature of Superintendent/Principal: ________________________________________________ Date ___________________<br><br></b>";
   echo "<i>Please mail the signed copy of this form and your payment to:</i><br>NSAA<br>500 Charleston Street, Suite 1<br>Lincoln NE 68508";
}
else
{
   echo "<br><div class='normalwhite' style='text-align:center;margin:10px;padding:10px;width:800px;'><B>ELECTRONIC SIGNATURE:</b> Please TYPE the name of the person submitting this form: <input type=text name=\"signature\" id=\"signature\" size=30 value=\"$signature\"";
   echo "></div><br>";
}

//AD: SUBMIT
if($level!=1 && $print!=1)
   echo "<input type=button class='fancybutton' onClick=\"CalculateFee();ErrorCheck();\" name=\"submitform\" value=\"Submit\"><input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
else if($level==1 && $print!=1)
   echo "<input type=submit class='fancybutton' name='save' value='Save'>";
echo "</form>";

// END REGISTRATION FORM

echo $end_html;
?>
