<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if(!$database || $database=='')
{
   $db1=$db_name; $db2=$db_name2;
}
else
{
   $db1=$database; $db2=ereg_replace("scores","officials",$database);
}

$sqls=array("SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.comment,t1.datesub,t1.verify,t1.notes,t2.id AS id2,t2.offid,t2.school1,t2.datesub,t2.verify,t2.notes,t2.level,t2.coach FROM $db1.ejections AS t1 LEFT JOIN $db2.ejections AS t2 on t1.sport=t2.sport AND t1.sid=t2.sid AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!='')) WHERE t2.id IS NOT NULL","SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.comment,t1.datesub,t1.verify,t1.notes FROM $db1.ejections AS t1 LEFT JOIN $db2.ejections AS t2 ON (t1.sport=t2.sport AND t1.sid=t2.sid AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!=''))) WHERE t2.id IS NULL","SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.reason,t1.datesub,t1.verify,t1.notes,t1.offid FROM $db2.ejections AS t1 LEFT JOIN $db1.ejections AS t2 ON (t1.sport=t2.sport AND t1.sid=t2.sid AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!=''))) WHERE t2.id IS NULL");
//$sqls=array("SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.comment,t1.datesub,t1.verify,t1.notes,t2.id AS id2,t2.offid,t2.school1,t2.datesub,t2.verify,t2.notes,t2.level,t2.coach FROM $db1.ejections AS t1 LEFT JOIN $db2.ejections AS t2 on t1.sport=t2.sport AND t1.school=t2.school AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!='')) WHERE t2.id IS NOT NULL","SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.comment,t1.datesub,t1.verify,t1.notes FROM $db1.ejections AS t1 LEFT JOIN $db2.ejections AS t2 ON (t1.sport=t2.sport AND t1.school=t2.school AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!=''))) WHERE t2.id IS NULL","SELECT t1.id,t1.sport,t1.school,t1.gamedate,t1.player,t1.coach,t1.level,t1.reason,t1.datesub,t1.verify,t1.notes,t1.offid FROM $db2.ejections AS t1 LEFT JOIN $db1.ejections AS t2 ON (t1.sport=t2.sport AND t1.school=t2.school AND t1.gamedate=t2.gamedate AND (t1.player=t2.player OR (t1.coach!='' AND t2.coach!=''))) WHERE t2.id IS NULL");
$eject=array(); $ix=0;
for($i=0;$i<count($sqls);$i++)
{
   $sqls[$i].=" ORDER BY t1.datesub DESC";
   $result=mysql_query($sqls[$i]);
   while($row=mysql_fetch_array($result))
   {
      if($i==0)
         $eject[$ix]="$row[school],$row[id],$row[id2]";
      else if($i==1)
	 $eject[$ix]="$row[school],$row[id],";
      else
	 $eject[$ix]="$row[school],,$row[id]";
      $ix++;
   }
}

sort($eject);	//sort into SCHOOL order
$csv="School,Date of Ejection,Sport,Athlete/Coach,Name,Level,Officials,Official's Name,Verified Report,School,AD Name,Verified Report\r\n";
for($i=0;$i<count($eject);$i++)
{
   $cur=split(",",$eject[$i]);
   $school=$cur[0]; $school2=addslashes($school);
   $id1=$cur[1];
   $id2=$cur[2];
   //get details from database:
   if($id1!="")
   {
      $sql="SELECT * FROM $db1.ejections WHERE id='$id1'";
      //$csv.="$school: $sql\r\n";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $date=split("-",$row[gamedate]);
      $gamedate="$date[1]/$date[2]/$date[0]";
      $sport=GetSportName($row[sport]);
      if($row[player]!=0)	//player ejected
      {
    	 $athletecoach="athlete";
 	 $sql2="SELECT first,middle,last,semesters FROM $db1.eligibility WHERE id='$row[player]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $athcoachname="$row2[first] $row2[last]";
      }
      else
      {
	 $athletecoach="coach";
	 $athcoachname=$row[coach];
      }
      $level=$row[level];
      $schoolreport="yes";
      $sql2="SELECT name FROM $db1.logins WHERE school='$school2' AND level='2'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $schoolad=$row2[name];
      $schoolverified=$row[verify];
   }
   if($id2!="")
   {
      $sql="SELECT * FROM $db2.ejections WHERE id='$id2'";
      $result=mysql_query($sql);
      //$csv.="$school: $sql\r\n";
      $row=mysql_fetch_array($result);
      if($id1=="")	
      {
         $date=split("-",$row[gamedate]);
	 $gamedate="$date[1]/$date[2]/$date[0]";
	 $sport=GetSportName($row[sport]);
	 if($row[player]!=0)	//player ejected
	 {
	    $athletecoach="athlete";
	    $sql2="SELECT first,middle,last,semesters FROM $db1.eligibility WHERE id='$row[player]'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);   
	    $athcoachname="$row2[first] $row2[last]";
	 }
	 else
	 {
	    $athletecoach="coach";
	    $athcoachname=$row[coach];
	 }
	 $level=$row[level];
	 $schoolreport="no";
	 $schoolad="";
	 $schoolverified="";
      }
      $offreport="yes";
      $sql2="SELECT first,last FROM $db2.officials WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offname="$row2[first] $row2[last]";
      $offverified=$row[verify];
   }
   else
   {
      $offreport="no";
      $offname="";
      $offverified="";
   }
   $csv.="$school,$gamedate,$sport,$athletecoach,$athcoachname,$level,$offreport,$offname,$offverified,$schoolreport,$schoolad,$schoolverified\r\n";
}

$open=fopen(citgf_fopen("/home/nsaahome/reports/ejectionexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/ejectionexport.csv");

header("Location:reports.php?session=$session&filename=ejectionexport.csv");
exit();
    
?>
