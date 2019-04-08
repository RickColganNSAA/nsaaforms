<?php

require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

$level=GetLevel($session);

if(!$school_ch || $school_ch==GetSchool($session))
{
   $school=GetSchool($session);
}
else if($level==1)
{
   $school=$school_ch;
}
else
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br>You must select a school.";
   exit();
}
$school2=addslashes($school);

if((ereg("College",$school) || ereg("Public Schools",$school) || $school=="Test's School") && $reset=='y')
{
   $sql="UPDATE muschools SET submitted='' WHERE school='$school2'";
   $result=mysql_query($sql);
}
$sql="SELECT id,submitted FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];

echo $init_html;
echo GetHeader($session);

if($level==1)
{
   echo "<br><a class=small href=\"muadmin.php?school_ch=$school_ch&session=$session\">Return to Music Entry Forms Admin</a><br><br>";
   echo "You are currently viewing the Music Contest Entry Form for <b><u>$school</b></u>.<br>";
}

echo "<table width=600><tr align=left><td><br><font style=\"font-size:10pt\">";
echo "<font style=\"color:blue\"><b>Welcome to the NSAA District Music Contest Entry Form:<br><br></b></font>";
echo "The NSAA District Music Contest Entry Form must be completed and submitted by Midnight Central Time on March 20 via this Web site.  The school music director(s) will complete <u>one (1) on-line entry form for each school or cooperative school unit by this deadline</u>.  You may begin work on the entry form and after saving each page as you complete the form, return to continue working or make changes until the form is finished and you are ready to submit your final entry by the deadline.  The form will be locked at midnight Central Time at the end of the day on March 20.   Do not hit the submit button until you are ready to send your form.  It will automatically be routed to your host contest site director and the date and time of submission noted for later reference.  <b>Remember  to submit your contest entry fees immediately after the March 20 deadline to the address shown on the acknowledgement message displayed after the form is successfully submitted.</b>";
echo "<br><br>";
echo "<table width=100% border=1 bordercolor=red cellspacing=0 cellpadding=2 class=nine><tr align=left><td><font style=\"color:red\">";
echo "Before you may use the NSAA on-line District Music Contest Entry Form, your school must have completed submission of the NSAA music student on-line eligibility lists. See your activities director to make sure this has been completed. The deadline for entering eligible music students is February 20. You may view your school's current music student eligibility list <a target=\"_blank\" href=\"eliglist.php?session=$session&school_ch=$school\">here</a>.";
echo "</font></td></tr></table><br><br>";
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0)
   echo "Begin completing the ";
else if($row[submitted]!='')
   echo "View the ";
else 
   echo "Continue working on your ";
echo "<a href=\"view_mu.php?session=$session&school_ch=$school\">NSAA Online Form</a>";
if($row[submitted]!='')
   echo " you submitted on ".date("m/d/y",$row[submitted]).".";
else if(mysql_num_rows($result)>0)
   echo ".<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".GetEntryStatus($schid);
else echo ".";
echo "</font></td></tr></table>";
if($row[submitted]!='')
{
   $submitted=$row[submitted];
   echo "<table border=1 bordercolor=\"blue\" width=450 cellspacing=0 cellpadding=4><tr align=left><td>";
   echo "<font style=\"font-size:8pt;color:blue\"><b>You submitted this form to the NSAA on ".date("m/d/y",$submitted)." at ".date("h:i a T",$submitted).".</font></b>";
   echo "<br><br><font style=\"font-size:8pt;\"><b>To COMPLETE your District Music Contest Registration</b></font><font style=\"font-size:8pt\">, please submit your school's <b><u>CONTEST ENTRY FEES</b></u> to your Host Contest Site (not the NSAA) immediately, if required by your District.  Send your fees to the address below.<br><br>If available for your contest site, the total amount due will be shown on your contest form <a target=\"_blank\" class=small href=\"payment.php?school_ch=$school_ch&session=$session\">Payment Summary</a>.<br><br>";
   $sql3="SELECT t1.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.school='$school2'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   if($row3[multiplesite]!='x')
   {
      if($row3[feeaddress1]=='')
      {
         $feename=$row3[director];
         $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
         $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
      }
      else
      {
         $feename=$row3[feename];
         $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
         $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
      }
      echo "<b>Please send your fees to:</b><br>";
      echo "$feename<br>$feeaddress1<br>";
      if($feeaddress2!='') echo "$feeaddress2<br>";
      echo "$feecity, $feestate $feezip<br>";
      echo "<b>Make checks payable to:</b> $row3[checks]<br><br>";
   }
   else
   {
      $distid1=$row3[distid1]; $distid2=$row3[distid2];
      $sql3="SELECT * FROM mudistricts WHERE (id='$distid1' OR id='$distid2')";
      $result3=mysql_query($sql3);
      $index=0;
      while($row3=mysql_fetch_array($result3))
      {
         if($row3[feeaddress1]=='')
         {
            $feename=$row3[director];
            $feeaddress1=$row3[address1]; $feeaddress2=$row3[address2];
            $feecity=$row3[city]; $feestate=$row3[state]; $feezip=$row3[zip];
         }
         else
         {
            $feename=$row3[feename];
            $feeaddress1=$row3[feeaddress1]; $feeaddress2=$row3[feeaddress2];
            $feecity=$row3[feecity]; $feestate=$row3[feestate]; $feezip=$row3[feezip];
         }
         if($row3[id]==$distid1) $director1=$row3[director];
         else $director2=$row3[director];
         echo "<b>Please send fees for the contest at $row3[site] to:</b><br>";
         echo "$feename<br>$feeaddress1<br>";
         if($feeaddress2!='') echo "$feeaddress2<br>";
         echo "$feecity, $feestate $feezip<br>";
         echo "<b>Make checks payable to:</b> $row3[checks]<br><br>";
         $index++;
      }  
   }
   echo "</td></tr></table>";
}
echo $end_html;

exit();
?>
