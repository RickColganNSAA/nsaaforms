<?php
/********************************************************
reimindex.php
AD landing page for Reimbursements (all sports)
Created 9/20/12
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

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid)
{
      $schoolid=GetSchoolID($session);
}
$school=GetSchool2($schoolid);

echo $init_html;
echo GetHeader($session)."<br>";

echo "<h2>State Championship Reimbursement Forms for $school:</h2><div style='text-align:left;width:600px;'><ul>";

//FOR EACH REIM SPORT, SHOW LINK TO START A REIMBURSEMENT FORM OR LINK TO SUBMITTED ONE
$sql="USE $db_name2";
$result=mysql_query($sql);
$sql="SHOW TABLES LIKE '%districts'";
$result=mysql_query($sql);
$seasonnums=array();
$sports=array(); $seasons=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   $sport=preg_replace("/districts/","",$row[0]);
   if(!preg_match("/go/",$sport) && !preg_match("/te/",$sport) && $sport!='sp')
   {
      $season=GetSeason($sport);
      $sports[$ix]=$sport; $seasons[$ix]=$season; 
      if($season=="Fall") $seasonnums[$ix]=1;
      else if($season=="Winter") $seasonnums[$ix]=2;
      else $seasonnums[$ix]=3;
      $ix++;
   }
}

$sql="USE $db_name";
$result=mysql_query($sql);

array_multisort($seasonnums,SORT_NUMERIC,SORT_ASC,$sports,SORT_STRING,SORT_ASC,$seasons);
$curseason="";
for($i=0;$i<count($sports);$i++)
{
   $sport=$sports[$i];
   if($curseason!=$seasons[$i])
   {
      if($i>0) echo "</ul></li>";
      echo "<li><b>".strtoupper($seasons[$i]." ACTIVITIES:")."</b><ul>";
      $curseason=$seasons[$i];
   }
   echo "<li><b>".GetActivityName($sport).":</b>&nbsp;&nbsp;";
   if(CanSubmitReimbursement($schoolid,$sport) || $school=="Test's School")
   {
      $sql2="SELECT * FROM $db_name.reimbursements WHERE sport='$sport' AND schoolid='$schoolid'";
      $result2=mysql_query($sql2);
      if($row2=mysql_fetch_array($result2))
      {
	 if($row2[datesub]>0)	//SUBMITTED, LOCKED
	    echo "You submitted this Reimbursement Form on ".date("F j, Y",$row2[datesub]).".<p><a href=\"reimbursements.php?session=$session&sport=$sport&reimid=$row2[id]\" target=\"_blank\" class='small'>View your Submitted ".GetActivityName($sport)." Reimbursement Form</a></p>";
     	 else
	    echo "You have started, but not submitted, this Reimbursement Form.<p><a href=\"reimbursements.php?session=$session&sport=$sport&reimid=$row2[id]&edit=1\" target=\"_blank\">Complete your ".GetActivityName($sport)." Reimbursement Form</a></p>";
      }
      else
         echo "You have not submitted a ".GetActivityName($sport)." reimbursement form yet.<p><a href=\"reimbursements.php?session=$session&sport=$sport\">Fill Out your ".GetActivityName($sport)." Reimbursement Form</a></p>";
   }
   else	//DIDN'T QUALIFY FOR STATE (WR - or isn't registered)
   {
      echo "<i>You are not listed as a State Qualifer for ".GetActivityName($sport).".*</i>";
   }
   echo "</li>";
}
echo "</ul></li>";
echo "</ul>";
echo "<p style='text-align:left;'>* You must be listed as a State Qualifier in order to fill out the reimbursement form for an activity. The NSAA will mark you as a State Qualifier before it is time to fill out this form for each activity. If you believe you should be able to receive reimbursement but are unable to fill out the proper form above, please contact the NSAA.</p>";

echo "</div>";

echo $end_html;
?>
