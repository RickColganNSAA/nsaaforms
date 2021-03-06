<?php
//hostapp_tr.php: site survey for Track & Field

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
{
   $school=GetSchool($session);
}
else 
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

//GET TOURNAMENT DATES
$sql2="SELECT * FROM $db_name2.trtourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$trhostdates=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   $index=$i+1;
   $field="date".$index;
   $sql="SHOW FULL COLUMNS FROM hostapp_tr WHERE Field='$field'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE hostapp_tr ADD `$field` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." ($row2[label])";
   }
   $trhostdates[$i]=$showdate;
   $i++;
}

if($submitapp=="Submit Application")
{  //echo'<pre>'; print_r($_POST); exit;
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $facility=ereg_replace("\'","\'",$facility);
   $facility=ereg_replace("\"","\'",$facility);
   $surface=ereg_replace("\'","\'",$surface);
   $surface=ereg_replace("\"","\'",$surface);
   $lasthost=ereg_replace("\'","",$lasthost);
   $lasthost=ereg_replace("\"","",$lasthost);
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $sql="SELECT * FROM hostapp_tr WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_tr (school,interested,";
      for($i=1;$i<=count($trhostdates);$i++)
      {
         $sql2.="date".$i.",";
      }
      $sql2.="pvaultsite,pvaultdualdirection,pvaultdirection,dualljpit,ljpitdirection,pvault,hjump,discus,facility,teams,spectators,parking,lockers,measurement,surface,lanes,curvelanes,superalley,lasthost,director,choice,neutral,comments,orientation,fat) VALUES ('$school2','$interested',";
      for($i=1;$i<=count($trhostdates);$i++)      
      {         
	 $var="date".$i;
	 $sql2.="'".$$var."',";
      }
      $sql2.="'$pvaultsite','$pvaultdualdirection','$pvaultdirection','$dualljpit','$ljpitdirection','$pvault','$hjump','$discus','$facility','$teams','$spectators','$parking','$lockers','$measurement','$surface','$lanes','$curvelanes','$superalley','$lasthost','$director','$choice','$neutral','".addslashes($comments)."','$orientation','$fat')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_tr SET interested='$interested', ";
      for($i=1;$i<=count($trhostdates);$i++)      
      {         
         $var="date".$i;
         $sql2.="$var='".$$var."',";
      }
      $sql2.="pvaultsite='$pvaultsite',pvaultdualdirection='$pvaultdualdirection',pvaultdirection='$pvaultdirection', dualljpit='$dualljpit', ljpitdirection='$ljpitdirection', pvault='$pvault', hjump='$hjump', discus='$discus', facility='$facility', teams='$teams', spectators='$spectators', parking='$parking', lockers='$lockers', measurement='$measurement', surface='$surface',lanes='$lanes',curvelanes='$curvelanes',superalley='$superalley',lasthost='$lasthost',director='$director', choice='$choice',neutral='$neutral',orientation='$orientation',fat='$fat',comments='".addslashes($comments)."' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
//echo $sql2."<br>".mysql_error(); exit;
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else
   echo "<table width=100%><tr align=center><td>";

$curryear=date("Y",time());
$curryear1=$curryear+1;
//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='tr'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//Get Spring Year
$springyear=date("Y");
if(date("m")>=6) $springyear++;

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_tr";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else 
{
   $sql.=" WHERE interested='y' ORDER BY school";	//TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);

if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_tr (school) VALUES ('$school2')";
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
echo "<h2>Application to Host a $springyear TRACK & FIELD District Meet</h2>";
echo "<form method=post action=\"hostapp_tr.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
if($print!=1)
{
   echo "<h3>Due $duedate2</h3>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
   if($submitapp && (!PastDue($duedate) || $level==1))
      echo "<p style=\"color:red\" size=2><b><i>Your application to host Track District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></p>";
   else if(!PastDue($duedate) || $level==1)
      echo "<p>(After the due date, you may only view, not edit, this form)</p>";
   if(PastDue($duedate) && $level!=1 && $school!="Test's School")
   {
      if(mysql_num_rows($result)==0)
      {
         echo "<p>You did NOT submit an Application to Host Track & Field District Event.  The due date for this application is past.</p>";
         echo $end_html;
         exit();
      }  
      else
      {
         echo "<p style=\"color:red\"><b>The due date for this application is past.  The application you've submitted is shown below.  You can no longer make changes to your application.  If you wish to do so, please contact the NSAA.</b></p>";
      }
   } 
}
echo "<table><tr align=left><th align=left><h3><i>Hosting Information:</i></h3>1) Are you interested in hosting any NSAA district/sub-district contests for Track & Field?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='y'";
if((!$interested && $row[2]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='y' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><h3>If <u>YES:</u></h3></th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><th align=left colspan=2>Please indicate which NSAA district contest(s) you are interested in hosting:</th></tr>";
for($i=0;$i<count($trhostdates);$i++)
{
   $ix=$i+1; $var="date".$ix;
   echo "<tr align=left><td colspan=2><input type=checkbox name=\"$var\" value='y'";
   if($row[$var]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo ">&nbsp;&nbsp;$trhostdates[$i]</td></tr>";
}
echo "<tr align=left><td>Please indicate if you own or will rent a FAT timing system:</td>";
echo "<td><input type=radio name=fat value='own'";
if($print==1) echo " disabled";
if($row['fat']=='own') echo " checked";
echo ">&nbsp;Own&nbsp;&nbsp;&nbsp;<input type=radio name=fat value='rent'";
if($print==1) echo " disabled";
if($row['fat']=='rent') echo " checked";
echo ">&nbsp;Rent</td></tr>";

echo "<tr align=left><td colspan=2><br>(NOTE: Host schools MUST report meet results electronically via the NSAA website (nsaahome.org).)</td></tr>";
echo "<tr align=left><th align=left colspan=2><br>Your school must meet the NFHS Track & Field requirements in the following areas:</th></tr>";
echo "<tr align=left><td>Pole Vault Landing Pad</td>";
echo "<td><input type=radio name=pvault value='y'";
if($print==1) echo " disabled";
if($row[5]=='y') echo " checked";
echo ">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type=radio name=pvault value='n'";
if($print==1) echo " disabled";
if($row[5]=='n') echo " checked";
echo ">&nbsp;No</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>If <b><u>YES</u></b>, are the pole vault facilities located on the district host site?  <input type=radio name='pvaultsite' value='y'";
if($print==1) echo " disabled";
if($row[pvaultsite]=='y') echo " checked";
echo ">&nbsp;Yes&nbsp;&nbsp;<input type=radio name='pvaultsite' value='n'";
if($print==1) echo " disabled";
if($row[pvaultsite]=='n') echo " checked";
echo ">&nbsp;No</td></tr>";
echo "<tr align=left><td>Is Your Pole Vault Dual Direction?</td>";
echo "<td><input type=radio name=pvaultdualdirection value='y'";
if($print==1) echo " disabled";
if($row[pvaultdualdirection]=='y') echo " checked";
echo ">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type=radio name=pvaultdualdirection value='n'";
if($print==1) echo " disabled";
if($row[pvaultdualdirection]=='n') echo " checked";
echo ">&nbsp;No</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>If <b><u>YES</u></b>, Which Direction?  <input type=radio name='pvaultdirection' value='n/s'";
if($print==1) echo " disabled";
if($row[pvaultdirection]=='n/s') echo " checked";
echo ">&nbsp;North/South&nbsp;&nbsp;<input type=radio name='pvaultdirection' value='e/w'";
if($print==1) echo " disabled";
if($row[pvaultdirection]=='e/w') echo " checked";
echo ">&nbsp;East/West</td></tr>";
echo "<tr align=left><td>High Jump Landing Pad</td>";
echo "<td><input type=radio name=hjump value='y'";
if($print==1) echo " disabled";
if($row[6]=='y') echo " checked";
echo ">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type=radio name=hjump value='n'";
if($print==1) echo " disabled";
if($row[6]=='n') echo " checked";
echo ">&nbsp;No</td></tr>";
echo "<tr align=left width='200px'><td>Discus Cage</td>";
echo "<td><input type=radio name=discus value='y'";
if($print==1) echo " disabled";
if($row[7]=='y') echo " checked";
echo ">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type=radio name=discus value='n'";
if($print==1) echo " disabled";
if($row[7]=='n') echo " checked";
echo ">&nbsp;No</td></tr>";
echo "<tr align=left><td width='200px'>Is your Long Jump Pit dual direction?</td>";
echo "<td><input type=radio name='dualljpit' value='y'";
if($print==1) echo " disabled";
if($row[dualljpit]=='y') echo " checked";
echo ">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type=radio name='dualljpit' value='n'";
if($print==1) echo " disabled";
if($row[dualljpit]=='n') echo " checked";
echo ">&nbsp;No</td></tr>";
echo "<tr align=left><td>&nbsp;</td><td>If <b><u>YES</b></u>, which direction are the pits?";
echo "&nbsp;&nbsp;<input type=radio name='ljpitdirection' value='North/South'";
if($print==1) echo " disabled";
if($row[ljpitdirection]=="North/South") echo " checked";
echo ">&nbsp;North/South&nbsp;&nbsp;<input type=radio name='ljpitdirection' value='East/West'";
if($print==1) echo " disabled";
if($row[ljpitdirection]=="East/West") echo " checked";
echo ">&nbsp;East/West</td></tr>";
echo "<tr align=left><td colspan=2><br>Our school does not have adequate facilities; however, we would be willing to host the district meet at:<br>";
if($print==1) echo "<b><u>$row[8]</b></u></td></tr>";
else echo "<input type=text size=40 name=facility value=\"$row[8]\"></td></tr>";
echo "<tr align=left><td colspan=2><br>Our facility can accommodate the following numbers:</td></tr>";
echo "<tr align=left><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if($print==1) 
   echo "Teams: <b><u>$row[9]</u></b>, Spectators: <b><u>$row[10]</b></u>, Parking: <b><u>$row[11]</u></b>, Locker Rooms: <b><u>$row[12]</u></b></td></tr>";
else 
{
   echo "Teams:&nbsp;<input type=text size=3 name=teams value=\"$row[9]\">&nbsp;&nbsp;&nbsp;";
   echo "Spectators:&nbsp;<input type=text size=5 name=spectators value=\"$row[10]\">&nbsp;&nbsp;&nbsp;";
   echo "Parking:&nbsp;<input type=text size=5 name=parking value=\"$row[11]\">&nbsp;&nbsp;&nbsp;";
   echo "Locker Rooms:&nbsp;<input type=text size=3 name=lockers value=\"$row[12]\"></td></tr>";
}
echo "<tr align=left><td colspan=2><br>";
echo "Is your track measured in <b>meters</b> or <b>yards</b>?&nbsp;&nbsp;";
echo "<input type=radio name=measurement value=\"meters\"";
if($row[13]=="meters") echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;Meters&nbsp;&nbsp;&nbsp;<input type=radio name=measurement value=\"yards\"";
if($row[13]=="yards") echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;Yards</td></tr>";
echo "<tr align=left><td colspan=2><br>";
echo "Is your track orientation <b>North/South</b> OR <b>East/West</b>??&nbsp;&nbsp;";
echo "<input type=radio name=orientation value=\"north\"";
if($row[29]=="north") echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;North/South&nbsp;&nbsp;&nbsp;<input type=radio name=orientation value=\"east\"";
if($row[29]=="east") echo " checked";
if($print==1) echo " disabled";
echo ">&nbsp;East/West</td></tr>";
echo "<tr align=left><td colspan=2><br>Type of surface:&nbsp;&nbsp;";
if($print==1) echo "<b><u>$row[14]</b></u></td></tr>";
else echo "<input type=text name=surface size=30 value=\"$row[14]\"></td></tr>";
echo "<tr align=left><td colspan=2><br>";
if($print==1)
   echo "Number of lanes: <b><u>$row[15]</b></u>, Number of Lanes on Curve (if different from straightaway): <b><u>$row[16]</b></u></td></tr>";
else
{
   echo "Number of lanes:&nbsp;&nbsp;<input type=text name=lanes size=2 value=\"$row[15]\">&nbsp;&nbsp;&nbsp;";
echo "Number of Lanes on Curve (if different from straightaway):&nbsp;&nbsp;<input type=text name=curvelanes size=2 value=\"$row[16]\"></td></tr>";
}
echo "<tr align=left><td colspan=2><br>Is your track marked for the super alley start?&nbsp;&nbsp;<input type=radio name='superalley' value='Yes'";
if($row[superalley]=="Yes") echo " checked";
if($print==1) echo " disabled";
echo "> Yes&nbsp;&nbsp;<input type=radio name='superalley' value='No'";
if($row[superalley]=="No") echo " checked";
if($print==1) echo " disabled";
echo "> No</td></tr>";
echo "<tr align=left><td colspan=2><br>The last year you hosted a district:&nbsp;&nbsp;";
if($print==1) echo "<b><u>$row[17]</b></u></td></tr>";
else echo "<input type=text size=4 name=lasthost value=\"$row[17]\"></td></tr>";
echo "<tr align=left><td colspan=2>";
echo "If your school is selected as a district host in track & field, who will serve as district director?&nbsp;&nbsp;";
if($print==1) echo "<b><u>$row[19]</b></u></td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[19]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
echo "<tr align=center><td><b>Other Comments:</b>";
if($print==1) echo "<p style=\"text-align:left;\">$row[comments]</p></td></tr>";
else 
{
   echo "<br><textarea name=\"comments\" style=\"width:600px;height:75px;\">$row[comments]</textarea></td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submitapp";
   if(PastDue($duedate) && $level!=1 && $school!="Test's School") echo " disabled";
   echo " value=\"Submit Application\"></td></tr>";
}
}
echo "</table></form>";
   echo "</div>";
} //END FOR EACH FORM

echo $end_html;
?>
