<?php
//transferdistresults.php: 
//Transfer District T&F Results to nsaastatetrack.trstatequalifiers
//This link is clicked from stateadmin.php once all district results are in & verified
//This moves all of the participants over to the nsaastatetrack DB/State T&F Program
//Created 2/16/12
//Author: Ann Gaffigan
require '../functions.php';
require '../variables.php';
require 'trfunctions.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

$db1="nsaascores";
$db2="nsaaofficials";
$statedb="nsaastatetrack";

echo $init_html;

echo GetHeader($session);

echo "<br /><br /><h2>Loading CLASS $class1 District Results....</h2><br>";
flush();

/*** CLEAR OUT ***/
$sql="DELETE FROM $statedb.trstatequalifiers WHERE (class='$class1')";
$result=mysql_query($sql);
$sql="DELETE FROM $statedb.trdistrictresults WHERE (class='$class1')";
$result=mysql_query($sql);

/***** COPY DISTRICT PLACERS TO STATE QUALIFIERS TABLE *****/
/*** BOYS ***/
$sql="SELECT * FROM $db2.trbdistricts WHERE (class='$class1') ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $statedb.trevents WHERE gender='Boys' ORDER BY gender,eventdistcode";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      GetResults($row[id],'b',$row2[eventdistcode],2);
   }
   //ALSO GET EXTRA QUALIFIERS 
   GetResults($row[id],'b','extraqual',2);
}

/**** GIRLS ****/
$sql="SELECT * FROM $db2.trgdistricts WHERE (class='$class1') ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $statedb.trevents WHERE gender='Girls' ORDER BY gender,eventdistcode";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      GetResults($row[id],'g',$row2[eventdistcode],2);
   }
   //ALSO GET EXTRA QUALIFIERS 
   GetResults($row[id],'g','extraqual',2);
}

/*** MOVE DISTRICT RESULTS TO STATE DB ***/
echo "<h2>Moving Class $class1 District Results to State Track & Field Database...</h2><br>";
$sql="INSERT INTO $statedb.trdistrictresults SELECT * FROM $statedb.trstatequalifiers WHERE (class='$class1')";
$result=mysql_query($sql);
flush();

/*** CHECK FOR ODD PERFORMANCES ***/
echo "<h2>Checking Class $class1 District Results for Odd Performances...</h2><br>";
$errors=0;
for($i=0;$i<count($trevents);$i++)
{
   $eventid=GetEventField($trevents[$i],'b');
   if(IsRelay($eventid)) $sql="SELECT DISTINCT sid,class,district,distperf1,distperf2";
   else $sql="SELECT DISTINCT sid,class,district,distperf1,distperf2,studentid";
   $sql.=" FROM $statedb.trstatequalifiers WHERE eventid='$eventid' AND (class='$class1')";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if(!ValidPerformance($eventid,$row[distperf1],$row[distperf2]))
      {
         if(IsRelay($eventid)) $studteam=GetSchoolName($row[sid],'trb');
         else $studteam=GetStudentInfo($row[studentid],FALSE);
         $sql2="SELECT * FROM $db2.trbdistricts WHERE class='$row[class]' AND district='$row[district]'";
         $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
         echo "<div class='error' style='width:400px;'><a class='white' href=\"previewresults.php?session=$session&distid=$row2[id]\" target=\"_blank\">District $row[class]-$row[district]</a> ".GetEventName($eventid)." (#$eventid): ".$studteam."'s result was ".FormatPerformance($row['class'],$eventid,$row[distperf1],$row[distperf2])."</div>";
	 $errors++;
      }
   }
}
for($i=0;$i<count($trevents_g);$i++)
{
   $eventid=GetEventField($trevents_g[$i],'g');
   if(IsRelay($eventid)) $sql="SELECT DISTINCT sid,class,district,distperf1,distperf2";
   else $sql="SELECT DISTINCT sid,class,district,distperf1,distperf2,studentid";
   $sql.=" FROM $statedb.trstatequalifiers WHERE eventid='$eventid' AND (class='$class1')";
   $result=mysql_query($sql);
echo mysql_error();
   while($row=mysql_fetch_array($result))
   {
      if(!ValidPerformance($eventid,$row[distperf1],$row[distperf2]))
      {
         if(IsRelay($eventid)) $studteam=GetSchoolName($row[sid],'trg');
         else $studteam=GetStudentInfo($row[studentid],FALSE);
         $sql2="SELECT * FROM $db2.trgdistricts WHERE class='$row[class]' AND district='$row[district]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         echo "<div class='error' style='width:400px;'><a class='white' href=\"previewresults.php?session=$session&distid=$row2[id]\" target=\"_blank\">District $row[class]-$row[district]</a> ".GetEventName($eventid)." (#$eventid): ".$studteam."'s result was ".FormatPerformance($row['class'],$eventid,$row[distperf1],$row[distperf2])."</div>";
	 $errors++;
      }
   }
}
echo "<br><p><b>$errors Errors Found.</b></p>";
flush();


/*** COPY tr_state_* TABLES TO nsaastatetrack DB FROM nsaascores DB ***/
$sql="USE $statedb";
$result=mysql_query($sql);
$sql="SHOW TABLES LIKE tr_state_";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="DELETE FROM $statedb.".$row[0];
   $result2=mysql_query($sql2);
   $sql2="INSERT INTO $statedb.".$row[0]." SELECT * FROM $db1.".$row[0];
   $result2=mysql_query($sql2);
}
$others=array("trbschool","trgschool","eligibility","trbdistricts","trgdistricts","logins","headers");
for($i=0;$i<count($others);$i++)
{
   $sql="DELETE FROM $statedb.".$others[$i];
   $result=mysql_query($sql);
   if($others[$i]=="eligibility")
   {
      //GET ALL ELIGIBLE TRACK ATHLETES: This way, anyone can be subsituted on a relay as long as they are eligible
      $sql="INSERT INTO $statedb.eligibility SELECT * FROM $db1.eligibility WHERE tr='x' AND eligible='y'";
      $result=mysql_query($sql);
        if(mysql_error()) echo "$sql<br>".mysql_error()."<br><br>";
   }
   else
   {
      if($others[$i]=="trbschool" || $others[$i]=="trgschool" || $others[$i]=="headers")
         $sql="INSERT INTO $statedb.".$others[$i]." SELECT * FROM $db1.".$others[$i];
      else if($others[$i]=="trbdistricts" || $others[$i]=="trgdistricts")
         $sql="INSERT INTO $statedb.".$others[$i]." SELECT * FROM $db2.".$others[$i];
      else if($others[$i]=="logins")	//GET COACHES
         $sql="INSERT INTO $statedb.".$others[$i]." SELECT * FROM $db1.".$others[$i]." WHERE sport LIKE '%Track%'";
      $result=mysql_query($sql);
	if(mysql_error()) echo "$sql<br>".mysql_error()."<br><br>";
   }
}
echo "<h2>Checking for missing students or schools...</h2>";
flush();

/*** DOUBLE CHECK FOR INELIGIBLE STUDENTS: ***/
$sql2="SELECT DISTINCT studentid,class,district FROM $statedb.trstatequalifiers";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $sql="SELECT * FROM $db1.eligibility WHERE id='$row2[0]' AND eligible!='y'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $row=mysql_fetch_array($result);
      echo "<div class='alert'>WARNING: Student #$row2[0] ".GetStudentInfo($row2[0],FALSE)." from $row[school] ($row2[class]-$row2[district]) is INELIGIBLE.</div><br>";
   }
}

/*** CHECK FOR SCHOOLS THAT AREN'T FOUND IN DATABASE: ***/
$sql="SELECT DISTINCT sid,eventid FROM $statedb.trstatequalifiers";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if(IsBoys($row[eventid]))
   {
      $sql2="SELECT * FROM $statedb.trbschool WHERE sid='$row[sid]'";
      $gender="Boys";
   }
   else
   {
      $sql2="SELECT * FROM $statedb.trgschool WHERE sid='$row[sid]'";
      $gender="Girls";
   }
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      echo "<div class='error'>School #$row[sid] ($gender) NOT FOUND in Track & Field school database, even though it had district qualifiers. Event ID # $eventid</div><br>";
   }
}

/*** CHECK FOR STUDENTS THAT AREN'T FOUND IN DATABASE: ***/
$sql="SELECT DISTINCT studentid FROM $statedb.trstatequalifiers";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $statedb.eligibility WHERE id='$row[0]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      echo "<div class='error'>Student #$row[0] ".GetStudentInfo($row[0])." NOT FOUND in State Track & Field eligibility database, even though he/she was listed in district results. Is he/she eligible?</div><br>";
   }
}

echo "<h2>State Track & Field Database is ready to go for CLASS $class1! <a href=\"/statetrack\" target=\"_blank\">Go to State Meet Program</a></h2>";
if($errors>0)
{
   echo "<p><i>You can go back to the <a href=\"stateadmin.php?session=$session\" class=\"small\">District Track & Field Results Admin</a> to fix any errors. Then click the Transfer District Results link to run this program again.</i></p>";
}

echo "<br><br><br><br><br>";
echo "<a href=\"stateadmin.php?session=$session\">Return to District Track & Field Results Admin</a><br><br>";

echo $end_html;

$sql="USE $db_name";
$result=mysql_query($sql);
?>
