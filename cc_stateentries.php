<?php
/*****************************************
Jun 22 2016:
Adapted this to just be run 1 time for both 
genders and all classes so that the bib
# runs from 1 all the way up. (Previously
it started over at 1 for each class-gender)
*******************************************/
require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}

//$sql="USE nsaascores20152016";
//$sql="USE nsaascores20162017";
//$result=mysql_query($sql);

$sql="DELETE FROM cc_b_state_quals";
$result=mysql_query($sql);
$sql="DELETE FROM cc_g_state_quals";
$result=mysql_query($sql);

$csv="\"Class\",\"Number\",\"First\",\"Last\",\"Gender\",\"Year\",\"School\"\r\n";

/*** FOR EACH CLASS ***/
$sql0="SELECT DISTINCT class FROM ccbschool WHERE class!='' ORDER BY class";
$result0=mysql_query($sql0);
$ccsports=array("cc_g","cc_b");	//will loop through this per class
$ix=1;	//This will be the bib number, incremented by 1 each time
while($row0=mysql_fetch_array($result0))
{
   $class=$row0['class'];

   //NOW GET ALL QUALIFIERS FOR EACH GENDER. 
   for($c=0;$c<count($ccsports);$c++)
   {
   $sport=$ccsports[$c];
   $schooltbl=GetSchoolsTable($sport);
   $sport2="ccg";
   $sql="SELECT t1.* FROM ".$sport."_state_team AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.student_ids IS NOT NULL AND t1.sid!='0' ORDER BY t2.school";
   $result=mysql_query($sql);
   $sids="";
   //temp table: cc_b_state_quals: sid,studentid1,studentid2,studentid3,studentid4,studentid5,studentid6,studentid7
   while($row=mysql_fetch_array($result))
   {
      $studs=split(",",$row[student_ids]);
      for($i=0;$i<count($studs);$i++)
      {
         $studs[$i]=trim($studs[$i]);
         if($studs[$i]!='' && $studs[$i]!='0')
         {
            $sql2="SELECT * FROM ".$sport."_state_quals WHERE sid='$row[sid]' AND studentid='$studs[$i]'";   
	    $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0)      //student not in table yet; INSERT
            {
               $sql3="INSERT INTO ".$sport."_state_quals (sid,studentid) VALUES ('$row[sid]','$studs[$i]')";
               $result3=mysql_query($sql3);
            }
         }
      }
   }
    $sql="SELECT t1.* FROM ".$sport."_state_indy AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER BY t2.school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
	   //if($row[sid]!=189){
			  $stud=$row[student_id]; $sid=$row[sid];
			  $sql2="SELECT * FROM ".$sport."_state_quals WHERE sid='$sid' AND studentid='$stud'";
			  $result2=mysql_query($sql2);
			  if(mysql_num_rows($result2)==0)	//student not in table yet; INSERT
			  {
				 $sql3="INSERT INTO ".$sport."_state_quals (sid,studentid) VALUES ('$sid','$stud')";
				 $result3=mysql_query($sql3);
			  }
	   //}
   }

   $sql="SELECT t1.*,t3.sid,t3.school as sch FROM eligibility AS t1,".$sport."_state_quals AS t2,$schooltbl AS t3 WHERE t1.id=t2.studentid AND t2.sid=t3.sid AND t3.class='$class' ORDER BY t1.gender,t3.school,t1.last,t1.first";
   //echo $sql; 
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($ix<10) $num="00".$ix;
      else if($ix<100) $num="0".$ix;
      else $num=$ix;
      //$csv.="\"$class\",\"$num\",\"$row[first]\",\"$row[last]\",\"$row[gender]\",\"".GetYear($row[semesters])."\",\"".GetSchoolName($row[sid],$sport2,date("Y"))."\"\r\n";
      $csv.="\"$class\",\"$num\",\"$row[first]\",\"$row[last]\",\"$row[gender]\",\"".GetYear($row[semesters])."\",\"$row[sch]\"\r\n";
      $ix++;
   }
   } //END FOR EACH SPORT (cc_g and cc_g)
}//exit;

$filename="CrossCountryStateEntries.csv";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
header("Location:attachments.php?session=$session&filename=$filename");

exit();
?>
