<?php
//hostapp_bb_b.php: site survey for Boys Basketball

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

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $comments=addslashes($comments);
   $dateschecked="|";
   for($i=0;$i<count($dateid);$i++)
   {
      if($datecheck[$i]=='x') $dateschecked.=$dateid[$i]."|";
   }
   if($dateschecked=="|") $dateschecked="";
   $sql="SELECT * FROM hostapp_bb_b WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_bb_b (school,hostlastyear,interested,dateschecked,classa,classb,classc1,classc2,classd1,classd2,teams,spectators,parking,lockers,floor,restline,director,choice,neutral,comments) VALUES ('$school2','$hostlastyear','$interested','$dateschecked','$classa','$classb','$classc1','$classc2','$classd1','$classd2','$teams','$spectators','$parking','$lockers','$floor','$restline','$director','$choice','$neutral','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_bb_b SET hostlastyear='$hostlastyear',interested='$interested', dateschecked='$dateschecked', classa='$classa', classb='$classb', classc1='$classc1', classc2='$classc2', classd1='$classd1', classd2='$classd2', teams='$teams', spectators='$spectators', parking='$parking', lockers='$lockers', floor='$floor', restline='$restline', director='$director', choice='$choice', neutral='$neutral',comments='$comments' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
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
$sql="SELECT duedate FROM app_duedates WHERE sport='bb_b'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_bb_b";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_bb_b (school) VALUES ('$school2')";
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
echo "<h3>Application to Host a $curryear-$curryear1 BOYS BASKETBALL District/Sub-District Event</h3>";

if($print!=1) echo "<p>Due $duedate2</p>";
echo "<form method=post action=\"hostapp_bb_b.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<table width=\"85%\">";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Boys Basketball District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
else if(!PastDue($duedate) || $level==1)
   echO "<i>(After the due date, you may only view, not edit, this form.)</i>";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Boys Basketball District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Boys Basketball?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='y'";
if((!$interested && $row[interested]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[interested]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if((!$interested && $row[interested]=='y') || $interested=='y')
{
echo "<tr align=left><th align=left>&nbsp;&nbsp;&nbsp;If YES:</th></tr>";
echo "<tr align=center><td><table class='nine' cellspacing=0 cellpadding=5>";
echo "<tr align=left><th align=left colspan=2>Please check available date(s):</th></tr>";
echo "<tr align=left><td colspan=2><u>Before indicating availability of facilities, be certain your school can host on the date(s) indicated</u>.</td></tr>";

//GET DATES FROM tourndates table
$sql2="SELECT * FROM $db_name2.bbtourndates WHERE boys='x' AND hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   $date=explode("-",$row2[tourndate]);
   echo "<tr><td align=right>&nbsp;";
   if(trim($row2[label])!='') echo $row2[label].":";
   echo "</td><td align=left><input type=hidden name=\"dateid[$ix]\" value=\"$row2[id]\"><input type=checkbox name=\"datecheck[$ix]\" value=\"x\"";
   if(preg_match("/\|$row2[id]\|/",$row[dateschecked])) echo " checked";
   if($print==1) echo " disabled";
   echo "> ".date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]))."</td></tr>";
   $ix++;
}
echo "<tr align=left><td colspan=2><p><b>NOTE:</b> District Finals sites to be determined by sub-district winners.</p></td></tr>";
echo "<tr align=left><td align=right><input type=checkbox name=classa value='y'";
if($row[classa]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class A District (Saturday-Monday OR Saturday-Tuesday)</td></tr>";
echo "<tr align=left><td align=right><input type=checkbox name=classb value='y'";
if($row[classb]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class B District</td></tr>";
echo "<tr align=left><td align=right><input type=checkbox name=classc1 value='y'";
if($row[classc1]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class C1 Subdistrict</td></tr>";
echo "<tr align=left><td align=right><input type=checkbox name=classc2 value='y'";
if($row[classc2]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class C2 Subdistrict</td></tr>";
echo "<tr align=left><td align=right><input type=checkbox name=classd1 value='y'";
if($row[classd1]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class D1 Subdistrict</td></tr>";
echo "<tr align=left><td align=right><input type=checkbox name=classd2 value='y'";
if($row[classd2]=='y') echo " checked";
if($print==1) echo " disabled";
echo "></td><td>Class D2 Subdistrict</td></tr>";
echo "<tr align=left><td colspan=2><br>Our facility can accommodate the following numbers:</td></tr>";
echo "<tr align=center><td colspan=2>";
if($print==1)
   echo "Teams: $row[teams], Spectators: $row[spectators], Parking: $row[parking], Locker Rooms: $row[lockers]</td></tr>";
else
{
echo "Teams:&nbsp;<input type=text size=3 name=teams value=\"$row[teams]\">&nbsp;&nbsp;&nbsp;";
echo "Spectators:&nbsp;<input type=text size=5 name=spectators value=\"$row[spectators]\">&nbsp;&nbsp;&nbsp;";
echo "Parking:&nbsp;<input type=text size=5 name=parking value=\"$row[parking]\">&nbsp;&nbsp;&nbsp;";
echo "Locker Rooms:&nbsp;<input type=text size=3 name=lockers value=\"$row[lockers]\"></td></tr>";
}
echo "<tr align=left><td colspan=2><br>";
echo "Type of floor surface:&nbsp;&nbsp;<input type=radio name=floor value=\"wood\"";
if($row[floor]=="wood") echo " checked";
if($print==1) echo " disabled";
echo ">Wood&nbsp;&nbsp;&nbsp;<input type=radio name=floor value=\"tartan\"";
if($row[floor]=="tartan") echo " checked";
if($print==1) echo " disabled";
echo ">Tartan&nbsp;&nbsp;&nbsp;<input type=radio name=floor value=\"tile\"";
if($row[floor]=="tile") echo " checked";
if($print==1) echo " disabled";
echo ">Tile</td></tr>";
echo "<tr align=left><td colspan=2><br>";
echo "Is there a restraining line used in your court?&nbsp;&nbsp;";
echo "<input type=radio name=restline value=\"y\"";
if($row[restline]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;&nbsp;<input type=radio name=restline value=\"n\"";
if($row[restline]=='n') echo " checked";
if($print==1) echo " disabled";
echo ">No</td></tr>";
echo "<tr align=left><td colspan=2><br>";
echo "If your school is selected as a district host in boys basketball, who will serve as district director?<br>";
if($print==1) echo "<i>$row[director]</i></td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[director]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
echo "<tr align=left><th align=left>2) Did you host last year?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=hostlastyear value='y'";
if((!$hostlastyear && $row[hostlastyear]=='y') || $hostlastyear=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=hostlastyear value='n'";
if((!$hostlastyear && $row[hostlastyear]=='n') || $hostlastyear=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
echo "<tr align=left><td align=left colspan=2><b>Other Comments:</b>";
if($print==1) echo "<p><i>$row[comments]</i></p></td></tr>";
else echo "<br><textarea name=comments rows=5 cols=60>$row[comments]</textarea></td></tr>";
if($print!=1)
{
   echo "<tr align=center><td colspan=2><br><input type=submit name=submitapp";
   if(PastDue($duedate) && $level!=1 && $school!="Test's School") echo " disabled";
   echo " value=\"Submit Application\"></td></tr>";
}
}
echo "</table></form>";
echo "</div>";
}//END FOR EACH APPLICATION TO HOST

echo $end_html;
?>
