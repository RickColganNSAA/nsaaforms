<?php
//hostapp_ba.php: site survey for baseball

require 'functions.php';
require 'variables.php';
require 'officials/variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if($nsaa==1 || $sample==1)
{
   $nsaa=1;
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
$sql2="SELECT * FROM $db_name2.batourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$bahostdates=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(trim($row2[label])!='') $showdate.=" ($row2[label])";
   }
   $bahostdates[$i]=$showdate;
   $i++;
}

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $site=ereg_replace("\'","\'",$site);
   $site=ereg_replace("\"","\'",$site);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $comments=addslashes($comments);
   $sql="SELECT * FROM hostapp_ba WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_ba (school,interested,site,lights,neutral,director,choice,comments) VALUES ('$school2','$interested','$site','$lights','$neutral','$director','$choice','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_ba SET interested='$interested', site='$site', lights='$lights', neutral='$neutral', director='$director', choice='$choice', comments='$comments' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else echo "<table width=100%><tr align=center><td>";

//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='ba'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//Get Spring Year
$springyear=date("Y");
if(date("m")>=6) $springyear++;

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_ba";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
//echo $sql; exit;
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_ba (school) VALUES ('$school2')";
   $result2=mysql_query($sql2);
   $result=mysql_query($sql);
   //echo "<h2>No school is available</h2>";
}

while($row=mysql_fetch_array($result))
{
   echo "<div style=\"page-break-after:always;\">";
if($nsaa!=1)
   echo "<p><a href=\"hostapps.php?session=$session\" class=\"small\">&larr; Apply to Host Another Activity's Event</a></p><br>";
else
   echo "<h1><i>$row[school]</i></h1>";
echo "<h3>Application to Host a $springyear BASEBALL District Event</h3>";

if($print!=1) echo "<p>Due $duedate2</p>";

echo "<form method=post action=\"hostapp_ba.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<table>";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
   echo "<div class=\"alert\"><b><i>Your application to host Baseball District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></div>";
else if(!PastDue($duedate) || $level==1)
   echo "(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Baseball District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Baseball?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='y'";
if((!$interested && $row[2]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='y' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><br>If <u>YES</u>:</th></tr>";
echo "<tr align=center><td><table class='nine'>";
echo "<tr align=left><td>";
echo "<p>Dates will be <b>";
$dates="";
for($i=0;$i<count($bahostdates);$i++)
{
   $dates.=$bahostdates[$i].", ";
}
$dates=substr($dates,0,strlen($dates)-2);
echo "$dates.</b></p><p><b>";
if($print==1)
   echo "Site Name</b>: $row[3]</p></td></tr>";
else
   echo "Site Name</b>:&nbsp;&nbsp;<input type=text name=site size=40 value=\"$row[3]\"></p></td></tr>";
echo "<tr align=left><td><p><b>Does the facility have lights?&nbsp;&nbsp;</b>";
echo "<input type=radio name=lights value='y'";
if($row[lights]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;";
echo "<input type=radio name=lights value='n'";
if($row[lights]=='n') echo " checked";
if($print==1) echo " disabled";
echo ">No</p></td></tr>";
echo "<tr align=left><td>";
echo "<p>If your school is selected as a district host in baseball, who will serve as <b>district director</b>?";
if($print==1) echo "&nbsp;&nbsp;<i>$row[4]</i></p></td></tr>";
else echo "&nbsp;&nbsp;<input type=text name=director size=25 value=\"$row[4]\"></p></td></tr>";
echo "</table></td></tr>";
}//end if interested
if($interested!='' || $row[2]!='')
{
echo "<tr align=left><td align=left><b>Other Comments:</b>";
if($print==1) echo "<p>$row[comments]</p></td></tr>";
else echo "<br><textarea style=\"width:100%;height:70px;\" name=\"comments\">$row[comments]</textarea></td></tr>";
if($print!=1)
{
   echo "<tr align=center><td><br><input type=submit name=submitapp";
   if(PastDue($duedate) && $school!="Test's School" && $level!=1) echo " disabled";
   echo " value=\"Submit Application\"></td></tr>";
}
}
echo "</table></form>";
echo "</div>";
} //END FOR EACH APP TO HOST
if(mysql_num_rows($result)==0 && $school=='')
{
   echo "<div style=\"page-break-after:always;\">";
if($nsaa!=1)
   echo "<p><a href=\"hostapps.php?session=$session\" class=\"small\">&larr; Apply to Host Another Activity's Event</a></p><br>";
else
   echo "<h1><i>$row[school]</i></h1>";
echo "<h3>Application to Host a $springyear BASEBALL District Event</h3>";

if($print!=1) echo "<p>Due $duedate2</p>";

echo "<form method=post action=\"hostapp_ba.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<table>";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
   echo "<div class=\"alert\"><b><i>Your application to host Baseball District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></div>";
else if(!PastDue($duedate) || $level==1)
   echo "(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Baseball District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Baseball?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='y'";
if((!$interested && $row[2]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><br>If <u>YES</u>:</th></tr>";
echo "<tr align=center><td><table class='nine'>";
echo "<tr align=left><td>";
echo "<p>Dates will be <b>";
$dates="";
for($i=0;$i<count($bahostdates);$i++)
{
   $dates.=$bahostdates[$i].", ";
}
$dates=substr($dates,0,strlen($dates)-2);
echo "$dates.</b></p><p><b>";
if($print==1)
   echo "Site Name</b>: $row[3]</p></td></tr>";
else
   echo "Site Name</b>:&nbsp;&nbsp;<input type=text name=site size=40 value=\"$row[3]\"></p></td></tr>";
echo "<tr align=left><td><p><b>Does the facility have lights?&nbsp;&nbsp;</b>";
echo "<input type=radio name=lights value='y'";
if($row[lights]=='y') echo " checked";
if($print==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;";
echo "<input type=radio name=lights value='n'";
if($row[lights]=='n') echo " checked";
if($print==1) echo " disabled";
echo ">No</p></td></tr>";
echo "<tr align=left><td>";
echo "<p>If your school is selected as a district host in baseball, who will serve as <b>district director</b>?";
if($print==1) echo "&nbsp;&nbsp;<i>$row[4]</i></p></td></tr>";
else echo "&nbsp;&nbsp;<input type=text name=director size=25 value=\"$row[4]\"></p></td></tr>";
echo "</table></td></tr>";
}//end if interested
if($interested!='' || $row[2]!='')
{
echo "<tr align=left><td align=left><b>Other Comments:</b>";
if($print==1) echo "<p>$row[comments]</p></td></tr>";
else echo "<br><textarea style=\"width:100%;height:70px;\" name=\"comments\">$row[comments]</textarea></td></tr>";
if($print!=1)
{
   echo "<tr align=center><td><br><input type=submit name=submitapp";
   if(PastDue($duedate) && $school!="Test's School" && $level!=1) echo " disabled";
   echo " value=\"Submit Application\"></td></tr>";
}
}
echo "</table></form>";
echo "</div>";
}
echo $end_html;
?>
