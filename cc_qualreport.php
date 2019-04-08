<?php
/****************************************
ccqualreport.php
Excel report of schools' # qualifiers
for CC State Meet
Created 10/27/08
Author: Ann Gaffigan
*****************************************/
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

$ccsports=array('cc_g','cc_b');

$csv="\"School\",\"Girls Qualifiers\",\"Boys Qualifiers\",\"Total Qualifiers\"\r\n";
for($s=0;$s<count($ccsports);$s++)
{
   $sport=$ccsports[$s];
   $sport2=ereg_replace("_","",$sport);
   $schooltbl=GetSchoolsTable($sport);
   $sql="DELETE FROM ".$sport."_state_quals";
   $result=mysql_query($sql);

//get all schools qualifying runners for this class:
$sql="SELECT t1.* FROM ".$sport."_state_team AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.student_ids IS NOT NULL AND t1.sid!='0' ORDER BY t2.school";
$result=mysql_query($sql);
$sids="";
//temp table: cc_b_state_quals: sid,studentid
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
}	//END FOR EACH SPORT

//NOW, GET GIRLS SCHOOLS WITH QUALIFIERS:
$sql="SELECT DISTINCT t1.sid,t2.mainsch,t2.school FROM cc_g_state_quals AS t1,ccgschool AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER BY t2.school";
$result=mysql_query($sql);
$sids=""; $schids="";
while($row=mysql_fetch_array($result))
{  
   $sids.=$row[0].",";
   $schids.=$row[1].",";
}
//GET BOYS SCHOOLS WITH QUALIFIERS:
$sql="SELECT DISTINCT t1.sid,t2.mainsch FROM cc_b_state_quals AS t1,ccbschool AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER BY t2.school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{  
   $sids.=$row[0].",";
   $schids.=$row[1].",";
}
//echo "______<br>";
$schids=Unique($schids);
$schids=split(",",$schids);
$bsids=array(); $schname=array();
$gsids=array(); 
$sport2="ccb"; $sport3="ccg";
for($i=0;$i<count($schids);$i++)
{
   if($schids[$i]>0)
   {
      $school=GetSchool2($schids[$i]);
      $bsids[$i]=GetSID2($school,$sport2);
      $gsids[$i]=GetSID2($school,$sport3);
      $bschname[$i]=GetSchoolName($bsids[$i],$sport2);
      $gschname[$i]=GetSchoolName($gsids[$i],$sport3);
   }
   else
   {
      $bschname[$i]=""; $bsids[$i]=0;
      $gschname[$i]=""; $gsids[$i]=0;
   }
}
if(!array_multisort($bschname,SORT_STRING,SORT_ASC,$gschname,SORT_STRING,SORT_ASC,$bsids,$gsids,$schids)) echo "<br><br>ERROR";
for($i=0;$i<count($schids);$i++)
{
   if($schids[$i]>0)
   {
   if($bschname[$i]!='') $csv.="\"$bschname[$i]\",";
   else if($gschname[$i]!='') $csv.="\"$gschname[$i]\",";
   if($gsids[$i]>0)
   {
      $sql2="SELECT t1.id FROM cc_g_state_quals AS t1,eligibility AS t2 WHERE t1.studentid=t2.id AND t2.gender='F' AND t1.sid='$gsids[$i]'";
      $result2=mysql_query($sql2);
      $girls=mysql_num_rows($result2);
   }
   else $girls=0;
   $csv.="\"$girls\","; 
   if($bsids[$i]>0)
   {
      $sql2="SELECT t1.id FROM cc_b_state_quals AS t1,eligibility AS t2 WHERE t1.studentid=t2.id AND t2.gender='M' AND t1.sid='$bsids[$i]'";
      $result2=mysql_query($sql2);
      $boys=mysql_num_rows($result2);
   }
   else $boys=0;
   $csv.="\"$boys\",";
   $total=$girls+$boys;
   $csv.="\"$total\"\r\n";
   }
}

$filename="Class".$class."QualifiersReport.csv";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
header("Location:attachments.php?session=$session&filename=$filename");

exit();
?>
