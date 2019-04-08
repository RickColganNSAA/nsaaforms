<?php
//hostapp_go_g.php: site survey for girls golf

require 'functions.php';
require 'variables.php';
require 'officials/variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if($nsaa==1)
{
   $db_name="$db_name2";
   $level=1;
}
else
   $db_name="$db_name";
if(!ValidUser($session,$db_name))
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if($sample==1) $school_ch="Test's School";
if($level!=1)
   $school=GetSchool($session);
else
   $school=$school_ch;
$school2=ereg_replace("\'","\'",$school);

//GET TOURNAMENT DATES
$sql2="SELECT * FROM $db_name2.go_gtourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$goghostdates=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   $index=$i+1;
   $field="date".$index;
   $sql="SHOW FULL COLUMNS FROM hostapp_go_g WHERE Field='$field'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE hostapp_go_g ADD `$field` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(trim($row2[label])!='') $showdate.=" ($row2[label])";
   }
   $goghostdates[$i]=$showdate;
   $i++;
}

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1)
   {
   $course=ereg_replace("\'","\'",$course);
   $course=ereg_replace("\"","\'",$course);
   $location=ereg_replace("\'","\'",$location);
   $location=ereg_replace("\"","\'",$location);
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   /*
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   */
   $comments=addslashes($comments);
   $sql="SELECT * FROM hostapp_go_g WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_go_g (school,interested,";
      for($i=1;$i<=count($goghostdates);$i++)
      {
         $sql2.="date".$i.", ";
      }
      $sql2.="hole9,hole18,drivingrange,puttinggreen,course,location,director,comments) VALUES ('$school2','$interested',";
      for($i=1;$i<=count($goghostdates);$i++)      
      {
         $var="date".$i;
	 $sql2.="'".$$var."', ";
      }
      $sql2.="'$hole9','$hole18','$drivingrange','$puttinggreen','$course','$location','$director','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_go_g SET interested='$interested', ";
      for($i=1;$i<=count($goghostdates);$i++)      
      {         
	 $var="date".$i;
         $sql2.="$var='".$$var."', ";
      }
      $sql2.="hole9='$hole9', hole18='$hole18', drivingrange='$drivingrange', puttinggreen='$puttinggreen', course='$course', location='$location', director='$director',comments='$comments' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else
   echO "<table width=100%><tr align=center><td>";

//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='go_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
$fallyear=$date[0];

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_go_g";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_go_g (school) VALUES ('$school2')";
   $result2=mysql_query($sql2);
   $result=mysql_query($sql);
}
while($row=mysql_fetch_array($result))
{
   echo "<div style=\"page-break-after:always;\">";
if($nsaa!=1)
   echo "<p><a href=\"hostapps.php?session=$session\" class=\"small\">&larr; Apply to Host Another Activity's Event</a></p><br>";
else
   echo "<h1><i>$row[school]</i></h1>";
echo "<h3>Application to Host a $date[0] GIRLS GOLF District Event</h3>";

if($print!=1) echo "<p>Due $duedate2</p>";

echo "<form method=post action=\"hostapp_go_g.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<table width=\"750px\">";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
{
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Girls Golf District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
}
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1)
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Girls Golf District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
      echo "</table>";
      echo $end_html;
      exit();
   }  
   else
   {
      echo "<tr align=left><td><font style=\"color:red\"><b>The due date for this application is past.  The application you've submitted is shown below.  You can no longer make changes to your application.  If you wish to do so, please contact the NSAA.</b></font></td></tr>";
   }
} 
} //end if not print
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Girls Golf?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio onclick=\"submit();\" name=interested value='y'";
if($interested=='y' ||  (!$interested && $row[2]=='y')) echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='y' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><h3><br>If <u>YES</u>:</h3></th></tr>";
echo "<tr align=left><th align=left>Please indicate the date(s) your facility is available and its course type:</th></tr>";
echo "<tr align=left><td><p>";
for($i=0;$i<count($goghostdates);$i++)
{
   $i2=$i+1; $var="date".$i2;
   echo "<tr align=left><td><input type=checkbox name=\"$var\" value='y'";
   if($row[$var]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo ">&nbsp;$goghostdates[$i]&nbsp;&nbsp;&nbsp;";
}
echo "</p></td></tr><tr align=left><td><p>";
echo "<input type=checkbox name=hole9 value='y'";
if($row[5]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;9-Hole Course&nbsp;&nbsp;&nbsp;";
echo "<input type=checkbox name=hole18 value='y'";
if($row[6]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;18-Hole Course&nbsp;&nbsp;&nbsp;";
echo "<input type=checkbox name='drivingrange' value='y'";
if($row[drivingrange]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;On-Site Driving Range&nbsp;&nbsp;&nbsp;";
echo "<input type=checkbox name='puttinggreen' value='y'";
if($row[puttinggreen]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;Practice Putting Green&nbsp;&nbsp;&nbsp;";
echo "</p></td></tr>";
echo "<tr align=left><td><p>";
echo "Name of Golf Course for Site of District Tournament:&nbsp;&nbsp;";
if($print==1) echo "$row[7]</p></td></tr>";
else echo "<input type=text name=course size=40 value=\"$row[7]\"></p></td></tr>";
echo "<tr align=left><td><p>";
echo "Location of Golf Course (city):&nbsp;&nbsp;";
if($print==1) echo "$row[8]</p></td></tr>";
else echo "<input type=text name=location size=40 value=\"$row[8]\"></p></td></tr>";
echo "<tr align=left><td><p>";
echo "Who will serve as the District Tournament Director if your school hosts?&nbsp;&nbsp;";
if($print==1) echo "$row[9]</p></td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[9]\"></p></td></tr>";
}
if($interested!='' || $row[2]!='')
{
   echO "<tr align=left><td><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else
      echo "<textarea style=\"width:100%;height:75px;\" name=\"comments\">$row[comments]</textarea></td></tr>";
   if($print!=1)
   {
      echo "<tr align=center><td><br><input type=submit ";
      if(PastDue($duedate) && $level!=1) echo "disabled ";
      echo "name=submitapp value=\"Submit Application\"></td></tr>";
   }
}
echo "</table></form>";
echo "</div>";
} //END FOR EACH APP TO HOST

echo $end_html;
?>
