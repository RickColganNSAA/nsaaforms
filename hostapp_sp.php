<?php
//hostapp_sp.php: site survey for Speech

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

   $sphostdates=array(); $i=0;
   $sphostdates_sm=array();
   $sql="SELECT * FROM $db_name2.sptourndates WHERE hostdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $sphostdates[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sphostdates_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $num=$i+1;
      $index="date".$num;
      $sql2="SHOW FULL COLUMNS FROM hostapp_sp WHERE Field='$index'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="ALTER TABLE hostapp_sp ADD `$index` VARCHAR(10) NOT NULL";
         $result2=mysql_query($sql2);
      }
      $i++;
   }

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1)
   {
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $comments=addslashes($comments);
   $sql="SELECT * FROM hostapp_sp WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_sp (school,interested,";
      for($i=1;$i<=count($sphostdates);$i++)
      {
         $sql2.="date".$i.", ";
      }
      $sql2.="multidist,schools,classrooms,director,choice,neutral,comments) VALUES ('$school2','$interested',";
      for($i=1;$i<=count($sphostdates);$i++)
      {
	 $field="date".$i;
	 $sql2.="'".$$field."', ";
      }
      $sql2.="'$multidist','$schools','$classrooms','$director','$choice','$neutral','$comments')";
   }
   else					//UPDATE
   { 
      $sql2="UPDATE hostapp_sp SET interested='$interested', ";
      for($i=1;$i<=count($sphostdates);$i++)
      {
         $field="date".$i;
         $sql2.="$field='".$$field."', ";
      }
      $sql2.="multidist='$multidist', schools='$schools', classrooms='$classrooms', director='$director',choice='$choice',neutral='$neutral',comments='$comments' WHERE school='$school2'";
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
$sql="SELECT duedate FROM app_duedates WHERE sport='sp'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_sp";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_sp (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $curryear-$curryear1 SPEECH District/Sub-District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";
echo "<form method=post action=\"hostapp_sp.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<table width=\"750px\">";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
{
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Speech District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
}
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1)
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Speech District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting multiple NSAA district contests for Speech?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='y'";
if((!$interested && $row[2]=='y') || $interested=='y') echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
echo "<tr><td>NOTE:  District Hosts will be required to use SpeechWire software.</td></tr>"; 	
if($interested=='y' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><h3><br>If <u>YES</u>:</h3></th></tr>";
echo "<tr align=left><td><b>Please indicate dates:</b></td></tr>";
      for($i=0;$i<count($sphostdates);$i++)
      {
         $field="date".$i;
	 echo "<tr align=left><td><input type=checkbox name=\"$field\" value='y'";
	 if($row[$field]=='y') echo " checked";
	 if($print==1) echo " disabled";
	 echo "> $sphostdates[$i]</td></tr>";
      }
  
echo "<tr align=left><td><p>";
//echo "<b>District Speech Contests hosted on a week day must start no later than 12:00 noon.</b></p></td></tr>";
echo "</p></td></tr>";
}
if($interested!='' || $row[interested]!='')
{
/* echo "<tr align=left><td><p><b><u>Note:</u> New Legislation in regard to District Contests:</b></p>
	<ol>
	<li>All contestants will compete in two preliminary rounds. Preliminary round sections will each be evaluated by a single judge. The top six contestants in each event will advance to a final round, which will be evaluated by two judges.</li><br>
	<li>Each participant school will provide one NSAA-certified judge. The NSAA will hire additional NSAA-certified judges to ensure that each district has 0 NSAA-certified judges. If a school does not provide a judge, there will be a $200 charge, which will allow the hiring of additional NSAA-provided judge. There would also be a $200 fine for schools that are assigned to a district but do not participate in the contest.</li>
	</ul></td></tr>"; */
if($print!=1)
{
   echo "<tr align=center><td><br><input type=submit name=submitapp";
   if(PastDue($duedate) && $level!=1) echo " disabled";
   echo " value=\"Submit Application\"></td></tr>";
}
}
echo "</table></form>";
echo "</div>";
} //END FOR EACH APP TO HOST

echo $end_html;
?>
