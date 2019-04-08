<?php
//hostapp_wr.php: site survey for Wrestling

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
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

//GET TOURNAMENT DATES
$sql2="SELECT * FROM $db_name2.wrtourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$wrhostdates=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   $index=$i+1;
   $field="date".$index;
   $sql="SHOW FULL COLUMNS FROM hostapp_wr WHERE Field='$field'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE hostapp_wr ADD `$field` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." ($row2[label])";
   }
   $wrhostdates[$i]=$showdate;
   $i++;
}

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $site=ereg_replace("\'","\'",$site);
   $site=ereg_replace("\"","\'",$site);
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $comments=addslashes($comments);
/*
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
*/
   $sql="SELECT * FROM hostapp_wr WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_wr (school,interested,";
      for($i=1;$i<=count($wrhostdates);$i++)
         $sql2.="date".$i.", ";
      $sql2.="internet,regseason,teams,spectators,parking,lockers,mats,site,director,comments";
      for($i=1;$i<=10;$i++)
      {
	 $hotel="hotel".$i; $rooms="rooms".$i; $distance="distance".$i;
	 $sql2.=",$hotel,$rooms,$distance";
      }
      $sql2.=") VALUES ('$school2','$interested',";
      for($i=1;$i<=count($wrhostdates);$i++)
      {
	 $var="date".$i;
         $sql2.="'".$$var."', ";
      }
      $sql2.="'$internet','$regseason','$teams','$spectators','$parking','$lockers','$mats','$site','$director','$comments'";
      for($i=1;$i<=10;$i++)      
      {         
	 $hotel="hotel".$i; $rooms="rooms".$i; $distance="distance".$i;         
	 $sql2.=",'".addslashes($$hotel)."','".$$rooms."','".$$distance."'";      
      }
      $sql2.=")";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_wr SET interested='$interested', ";
      for($i=1;$i<=count($wrhostdates);$i++)
      {
         $var="date".$i;         
         $sql2.="$var='".$$var."', ";
      }
      $sql2.=" internet='$internet', regseason='$regseason', teams='$teams', spectators='$spectators', parking='$parking', lockers='$lockers', mats='$mats', site='$site', director='$director', comments='$comments'";
      for($i=1;$i<=10;$i++)            
      {                  
	 $hotel="hotel".$i; $rooms="rooms".$i; $distance="distance".$i;                  
	 $sql2.=",$hotel='".addslashes($$hotel)."',$rooms='".$$rooms."',$distance='".$$distance."'";      
      } 
      $sql2.=" WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
echo mysql_error();
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else
   echo "<table width=100%><tr align=center><td>";

$curryear=date("Y",time());
if(date("m")<6) $curryear--;
$curryear1=$curryear+1;
//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='wr'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_wr";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_wr (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $curryear-$curryear1 WRESTLING District/Sub-District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";
echo "<form method=post action=\"hostapp_wr.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<table width='85%'>";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Wrestling District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Wrestling District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
      echo "</table>";
      echo $end_html;
      exit();
   }  
   else
   {
      echo "<tr align=left><td><font style=\"color:red\"><b>The due date for this application is past.  The application you've submitted is shown below.  You can no longer make changes to your application.  If you wish to do so, please contact the NSAA.</b></font></td></tr>";
   }
} 
} //END IF NOT PRINT
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Wrestling?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='y'";
if((!$interested && $row[2]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='y' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><br>&nbsp;&nbsp;&nbsp;If YES:</th></tr>";
echo "<tr align=center><td><table width='85%' class='nine'>";
echo "<tr align=left><th align=left colspan=2>Please indicate which NSAA district contest(s) you are interested in hosting:</th></tr>";
for($ix=1;$ix<=count($wrhostdates);$ix++)
{
   $ix2=$ix-1;
   $field="date".$ix;
   echo "<tr align=left><td><input type=checkbox name=\"$field\" value='y'";
   if($row[$field]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo "></td><td>$wrhostdates[$ix2]</td></tr>";
}
echo "<tr align=left><td><input type=checkbox name=\"internet\" value='y'";
if($row[internet]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Does the competition gym have <b>internet capabilities</b>, which are required to run <b>Trackwrestling</b> software? (If YES, check the box.)</td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"regseason\" value='y'";
if($row[regseason]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Will you host a <b>regular season</b> wrestling tournament using <b>Trackwrestling</b> software with Matside Scoring? (If YES, check the box.)</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>(NOTE: Host schools MUST use Trackwrestling software provided by the NSAA.)</td></tr>";
echo "<tr align=left><td colspan=2><br>Our facility can accommodate the following numbers:</td></tr>";
echo "<tr align=center><td colspan=2>";
if($print==1)
{
   echo "Teams:&nbsp;$row[teams]&nbsp;&nbsp;&nbsp;";
   echo "Spectators:&nbsp;$row[spectators]&nbsp;&nbsp;&nbsp;";
   echo "Parking:&nbsp;$row[parking]&nbsp;&nbsp;&nbsp;";
   echo "Locker Rooms:&nbsp;$row[lockers]&nbsp;&nbsp;&nbsp;";
   echo "Wrestling Mats:&nbsp;$row[mats]</td></tr>";
}
else
{
   echo "Teams:&nbsp;<input type=text size=3 name=teams value=\"$row[teams]\">&nbsp;&nbsp;&nbsp;";
   echo "Spectators:&nbsp;<input type=text size=5 name=spectators value=\"$row[spectators]\">&nbsp;&nbsp;&nbsp;";
   echo "Parking:&nbsp;<input type=text size=5 name=parking value=\"$row[parking]\">&nbsp;&nbsp;&nbsp;";
   echo "Locker Rooms:&nbsp;<input type=text size=3 name=lockers value=\"$row[lockers]\">&nbsp;&nbsp;&nbsp;";
   echo "Wrestling Mats:&nbsp;<input type=text size=3 name=mats value=\"$row[mats]\"></td></tr>";
}
echo "<tr align=left><td colspan=2><br><b>How many hotel rooms do you have in a 20-25 mile radius of the host site?</b><p><i>Please list the hotel(s), # of rooms and distance from the host site below:</i></p></td></tr>";
	//HOTELS
echo "<tr align=center><td colspan=2><table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\"><tr align=center><td>&nbsp;</td><td><b>Hotel</b></td><td><b># of<br>Rooms</b></td><td><b>Distance<br>from Site</b><br>(Miles)</td></tr>";
for($i=1;$i<=10;$i++)
{
   $hotel="hotel".$i; $rooms="rooms".$i; $distance="distance".$i;
   echo "<tr align=center><td align=\"right\">$i.</td>";
   if($print==1)
      echo "<td>".$row[$hotel]."</td><td>".$row[$rooms]."</td><td>".$row[$distance]."</td></tr>";
   else
   {
      echo "<td><input type=text name=\"$hotel\" value=\"".$row[$hotel]."\" size=30></td>
	<td><input type=text name=\"$rooms\" value=\"".$row[$rooms]."\" size=4></td>
	<td><input type=text name=\"$distance\" value=\"".$row[$distance]."\" size=4></td></tr>";
   }
}
echo "</table>";
echo "<tr align=left><td colspan=2><br>";
//echo "<i><b>A district host must have all competitions in <u>ONE</u> gym, with at least 5 feet between circles and 5 feet of out-of-bounds area for each mat.</i> <br>A physician is required for skin checks of all athletes at day one weigh-ins.</b><br>";
//echo "<i><b>A district host must have all competitions in <u>ONE</u> gym, with at least 5 feet of out-of-bounds area for each mat.</i> <br>A physician is required for skin checks of all athletes at day one weigh-ins.</b><br>";
echo "<i><b>A district host must have all competitions in <u>ONE</u> gym, with at least 5 feet of out-of-bounds area for each mat.</i> <br>A physician is required for skin checks of all athletes <b>on both days of weigh-ins</b>.</b><br>";
echo "Site of District Wrestling Tournament (if non-school site):&nbsp;";
if($print==1) echo "$row[site]</td></tr>";
else echo "<input type=text size=40 name=site value=\"$row[site]\"></td></tr>";
echo "<tr align=left><td colspan=2><br>";
echo "If your school is selected as a district host in wrestling, who will serve as district director?&nbsp;&nbsp;";
if($print==1) echo "$row[director]</td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[director]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
   echo "<tr align=center><td><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else echo "<textarea rows=5 cols=80 name=\"comments\">$row[comments]</textarea></td></tr>";
   if($print!=1)
   {
      echo "<tr align=center><td><br><input type=submit name=submitapp ";
      if(PastDue($duedate) && $level!=1 && $school!="Test's School") echo "disabled ";
      echo "value=\"Submit Application\"></td></tr>";
   }
}
echo "</table></form>";
echo "</div>";
} //END FOR EACH APP TO HOST

echo $end_html;
?>
