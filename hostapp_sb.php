<?php
//hostapp_sb.php: site survey for softball

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
$sql2="SELECT * FROM $db_name2.sbtourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$sbhostdates=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   $index=$i+1;
   $field="date".$index;
   $sql="SHOW FULL COLUMNS FROM hostapp_sb WHERE Field='$field'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE hostapp_sb ADD `$field` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(trim($row2[label])!='') $showdate.=" ($row2[label])";
   }
   $sbhostdates[$i]=$showdate;
   $i++;
}

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $site=ereg_replace("\'","\'",$site);
   $site=ereg_replace("\"","\'",$site);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $comments=addslashes($comments);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $sql="SELECT * FROM hostapp_sb WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_sb (school,interested,";
      for($i=1;$i<=count($sbhostdates);$i++)
      {
         $sql2.="date".$i.", ";
      }
      $sql2.="director,site,samesite,fieldct,lightfieldct,choice,neutral,comments) VALUES ('$school2','$interested',";
      for($i=1;$i<=count($sbhostdates);$i++)      
      { 
         $field="date".$i;
         $sql2.="'".$$field."', ";
      }
      $sql2.="'$director','$site','$samesite','$fields','$lightfields','$choice','$neutral','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_sb SET interested='$interested', ";
      for($i=1;$i<=count($sbhostdates);$i++)            
      { 
         $field="date".$i;
         $sql2.="$field='".$$field."', ";
      }
      $sql2.="director='$director', site='$site', samesite='$samesite', fieldct='$fields',lightfieldct='$lightfields',choice='$choice',neutral='$neutral',comments='$comments' WHERE school='$school2'";
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
$curryear1=$curryear+1;
//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='sb'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//Get Fall Year
$fallyear=$date[0];

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_sb";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_sb (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $fallyear SOFTBALL District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";

echo "<form method=post action=\"hostapp_sb.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<table width=\"700px\">";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Softball District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1)
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Softball District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Softball?</th></tr>";
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
echo "<tr align=center><td><table width=85%>";
for($i=0;$i<count($sbhostdates);$i++)
{
   $ix=$i+1; $var="date".$ix;
   echo "<tr align=left><td>";
   echo "<input type=checkbox name=\"$var\" value='y'";
   if($row[$var]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo ">&nbsp;$sbhostdates[$i]&nbsp;&nbsp;&nbsp;&nbsp;";
}
echo "</td>";
echo "</tr>";
echo "<tr align=left><td>";
echo "Site Name:&nbsp;&nbsp;";
if($print==1) echo "$row[site]</td></tr>";
else echo "<input type=text name=site size=40 value=\"$row[site]\"></td></tr>";
echo "<tr align=left><td>";
echo "Total number of fields available:&nbsp;&nbsp;";
if($print==1) echo "$row[fieldct]</td></tr>";
else echo "<input type=text size=5 name=fields value=\"$row[fieldct]\"></td></tr>";
echo "<tr align=left><td>If you have multiple fields, are they located within the same site/venue?&nbsp;&nbsp;";
echo "<input type=\"radio\" name=\"samesite\" value=\"Yes\"";
if($print==1) echo " disabled";
if($row[samesite]=="Yes") echo " checked";
echo "> Yes&nbsp;&nbsp;";
echo "<input type=\"radio\" name=\"samesite\" value=\"No\"";
if($print==1) echo " disabled";
if($row[samesite]=="No") echo " checked";
echo "> No</td></tr>";
echo "<tr align=left><td>";
echo "Of the total number of fields, how many have lights?&nbsp;&nbsp:";
if($print==1) echo "$row[lightfieldct]</td></tr>";
else echo "<input type=text size=5 name=lightfields value=\"$row[lightfieldct]\"></td></tr>";
echo "<tr align=left><td>";
echo "If your school is selected as a district host for softball, who will serve as <b>district director</b>?&nbsp;&nbsp;";
if($print==1) echo "$row[director]</td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[director]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
   echo "<tr align=left><td align=left><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else echo "<textarea style=\"width:100%;height:75px;\" name=\"comments\">$row[comments]</textarea></td></tr>";
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
