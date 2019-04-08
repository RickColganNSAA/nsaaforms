<?php
/*
exportdistrictspeech.php
Written for District Directors to be able to 
export entries from participating schools in
order to print ballots, etc.
Author Ann Gaffigan
Date March 5, 2012
*/

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);
$sport='sp';
$sportname=GetActivityName($sport);
$districts=$sport."districts";

//Make sure user is the HOST of a SPEECH DISTRICT
$sql="SELECT id FROM logins WHERE school='$school2' AND level='2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql="SELECT * FROM $db_name2.$districts WHERE (hostid='$row[0]') AND (type='District' OR type='Subdistrict' OR type='District Final') AND id='$distid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "<br>You are not the host of a $sportname District.";
   exit();
}
else 	//allow district to view this information on date indicated in database
{
   $showdate=GetDueDate($sport."showentries");
   if(!PastDue($showdate,0))
   {
      $date=split("-",$earlydate);
      echo "<br>You will be able to view the entry forms of the schools in your district after <b>$date[1]/$date[2]/$date[0]</b> at midnight.";
      exit();
   }
}

//else, school is a host and it is 2 days past due date of entry form...PROCEED:
$row=mysql_fetch_array($result);
$class=$row['class'];
$type=$row[type];
$district=$row[district];
$csv="\"$sportname District Entries\"\r\n\"Class\",\"$row[class]\"\r\n\"$row[type]\",\"$row[district]\"\r\n";
$schools=split(",",$row[schools]);
if($row[sids]!='')
  $sids=split(",",$row[sids]);
$csv.="\"School\",\"Mascot\",\"Colors\",\"Coach\",\"Assistant Coaches\",\"Last Name\",\"First Name\",\"Grade\",\"Drama Group 1\",\"Drama Group 2\",\"Duet Acting Group 1\",\"Duet Acting Group 2\",\"Entertainment Speaking\",\"Extemporaneous Speaking\",\"Humorous Interpretation of Prose\",\"Informative Public Speaking\",\"Poetry\",\"Persuasive Speaking\",\"Serious Interpretation of Prose\"\r\n";
for($i=0;$i<count($schools);$i++)
{
   $school=GetMainSchoolName($sids[$i],$sport);
   $school2=addslashes($school);
   $schoolid=GetSchoolID2($school);
   $team=GetSchoolName($sids[$i],$sport);
   $mascot=GetMascot($schoolid,$sport);
   $colors=GetColors($schoolid,$sport);
   $coach=GetCoaches($schoolid,$sport);
   $asst=GetAsstCoaches($schoolid,$sport);
   //Get this school's entry
   $sql3="SELECT t1.* FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.co_op='$school2' OR t2.school='$school2') AND t1.checked='y' ORDER BY t2.last";
   $result3=mysql_query($sql3);      
   while($row3=mysql_fetch_array($result3))
   {
      $csv.="\"$team\",\"$mascot\",\"$colors\",\"$coach\",\"$asst\",";
      $sql2="SELECT last, first, middle, semesters FROM eligibility WHERE id='$row3[student_id]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $csv.="\"$row2[0]\",\"$row2[1]\",\"".GetYear($row2[semesters])."\",";
      if($row3[drama1]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[drama2]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[duet_acting1]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[duet_acting2]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[ent_speak]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[extemp]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[prose_humor]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[inform]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[poetry]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[pers_speak]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      if($row3[prose_serious]=='y') $csv.="\"X\",";
      else $csv.="\"\",";
      $csv.="\r\n";
   }
}
   $filename=$type.$class.$district."SpeechEntries.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");

header("Location:../attachments.php?filename=$filename&session=$session");
exit();
?>
