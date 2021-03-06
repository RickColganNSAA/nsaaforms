<?php
//hostapp_vb.php: site survey for Volleyball

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
   header("Location:../index.php");
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
$sql2="SELECT * FROM $db_name2.vbtourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$vbhostdates=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   $index=$i+1;
   $field="date".$index;
   $sql="SHOW FULL COLUMNS FROM hostapp_vb WHERE Field='$field'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE hostapp_vb ADD `$field` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(trim($row2[label])!='') $showdate.=" ($row2[label])";
   }
   $vbhostdates[$i]=$showdate;
   $i++;
}

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $ceiling=ereg_replace("\'","-",$ceiling);
   $ceiling=ereg_replace("\"","",$ceiling);
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $comments=addslashes($comments);
   $sql="SELECT * FROM hostapp_vb WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_vb (school,interested,";
      for($i=1;$i<=count($vbhostdates);$i++)
      {
	 $sql2.="date".$i.", ";
      }
      $sql2.="classa,classb,classc1,classc2,classd1,classd2,teams,spectators,parking,lockers,ceiling,director,neutral,choice,comments) VALUES ('$school2','$interested',";
      for($i=1;$i<=count($vbhostdates);$i++)      
      {
	 $field="date".$i;
	 $sql2.="'".$$field."', ";
      }
      $sql2.="'$classa','$classb','$classc1','$classc2','$classd1','$classd2','$teams','$spectators','$parking','$lockers','$ceiling','$director','$neutral','$choice','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_vb SET interested='$interested', ";
      for($i=1;$i<=count($vbhostdates);$i++)      
      {
         $field="date".$i;
         $sql2.="$field='".$$field."', ";
      }
      $sql2.="classa='$classa', classb='$classb', classc1='$classc1', classc2='$classc2', classd1='$classd1', classd2='$classd2', teams='$teams', spectators='$spectators', parking='$parking', lockers='$lockers', ceiling='$ceiling', director='$director', neutral='$neutral',choice='$choice',comments='$comments' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else   
   echo "<table width=100%><tr align=center><td>";

//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='vb'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//Get Fall Year
$fallyear=$date[0];

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_vb";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_vb (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $fallyear VOLLEYBALL District/Sub-District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";
echo "<form method=post action=\"hostapp_vb.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=\"nsaa\" value=\"$nsaa\">";
echo "<table width=\"700px\">";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
{
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Volleyball District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
}
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Volleyball District/Subdistrict Events.  The due date for this application is past.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district/sub-district contests for Volleyball?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio onclick=\"submit();\" name=interested value='y'";
if((!$interested && $row[interested]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested value='n' onclick=\"submit();\"";
if((!$interested && $row[interested]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='y' || (!$interested && $row[interested]=='y'))
{
echo "<tr align=left><th align=left><h3>If <u>YES</u>:</h3></th></tr>";
echo "<tr align=center><td><table width=95%>";
echo "<tr align=left><th align=left colspan=2>We are interested in hosting a district/subdistrict volleyball tournament on the following dates:</th></tr>";
for($i=0;$i<count($vbhostdates);$i++)
{
   $i2=$i+1; $field="date".$i2;
   echo "<tr align=left><td width=50><input type=checkbox name=\"$field\" value='y'";
   if($row[$field]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo "></td><td>$vbhostdates[$i]</td></tr>";
}
echo "<tr align=left><td colspan=2><br>NOTE:";
echo "<ul><li>A four-team subdistrict/district will be scheduled on one day.</li>";
echo "<li>A five-team subdistrict/district will be scheduled on two days.</li>";
echo "<li>A six-team subdistrict/district will be scheduled on three days.</li></ul>";
echo "<p>A request for a modification of the above format must be approved through the NSAA Office with all participating schools assigned to the district agreeing to a change in format <b>by September 1st.</b></p></td></tr>";
echo "<tr align=left><th align=left colspan=2><br>We would be interested in hosting:</th></tr>";
/* MAY 16, 2016 - removed Class A & B checkboxes (they are given to the higher seeds)
echo "<tr align=left><td><input type=checkbox name=classa value='y'";
if($row[classa]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class A District</td></tr>";
echo "<tr align=left><td><input type=checkbox name=classb value='y'";
if($row[classb]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class B District</td></tr>";
*/
echo "<tr align=left><td><input type=checkbox name=classc1 value='y'";
if($row[classc1]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class C1 Subdistrict</td></tr>";
echo "<tr align=left><td><input type=checkbox name=classc2 value='y'";
if($row[classc2]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class C2 Subdistrict</td></tr>";
echo "<tr align=left><td><input type=checkbox name=classd1 value='y'";
if($row[classd1]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class D1 Subdistrict</td></tr>";
echo "<tr align=left><td><input type=checkbox name=classd2 value='y'";
if($row[classd2]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class D2 Subdistrict</td></tr>";
echo "<tr align=left><td colspan=2><br>Our facility can accommodate the following numbers:</td></tr>";
echo "<tr align=center><td colspan=2>";
if($print==1)
{
   echo "Teams:&nbsp;$row[teams]&nbsp;&nbsp;&nbsp;";
   echo "Spectators:&nbsp;$row[spectators]&nbsp;&nbsp;&nbsp;";
   echo "Parking:&nbsp;$row[parking]&nbsp;&nbsp;&nbsp;";
   echo "Locker Rooms:&nbsp;$row[lockers]&nbsp;&nbsp;&nbsp;";
   echo "Ceiling Height:&nbsp;$row[ceiling]</td></tr>";
}
else
{
   echo "Teams:&nbsp;<input type=text size=3 name=teams value=\"$row[teams]\">&nbsp;&nbsp;&nbsp;";
   echo "Spectators:&nbsp;<input type=text size=5 name=spectators value=\"$row[spectators]\">&nbsp;&nbsp;&nbsp;";
   echo "Parking:&nbsp;<input type=text size=5 name=parking value=\"$row[parking]\">&nbsp;&nbsp;&nbsp;";
   echo "Locker Rooms:&nbsp;<input type=text size=3 name=lockers value=\"$row[lockers]\">&nbsp;&nbsp;&nbsp;";
   echo "Ceiling Height:&nbsp;<input type=text size=4 name=ceiling value=\"$row[ceiling]\"></td></tr>";
}
echo "<tr align=left><td colspan=2><br>";
echo "If your school is selected as a district host in volleyball, who will serve as district director?&nbsp;&nbsp;";
if($print==1) echo "$row[director]</td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[director]\"></td></tr>";
echo "</table></td></tr>";
}//end if interested
if($interested!='' || $row[2]!='')
{
   echo "<tr align=left><td><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else echo "<textarea style=\"width:100%;height:75px;\" name=\"comments\">$row[comments]</textarea></tf></tr>";
   if($print!=1)
   {
      echo "<tr align=center><td><br><input type=submit ";
      if(PastDue($duedate) && $level!=1 && $school!="Test's School") echo "disabled ";
      echo "name=submitapp value=\"Submit Application\"></td></tr>";
   }
}
echo "</table></form>";
echo "</div>";
} //END FOR EACH APP TO HOST

echo $end_html;
?>
