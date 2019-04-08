<?php
//hostapp_so.php: site survey for SOCCER

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
$sql2="SELECT * FROM $db_name2.sotourndates WHERE hostdate='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$sohostdates=array(); $i=0;
$sohostshow=array();
while($row2=mysql_fetch_array($result2))
{
   $index=$i+1;
   $field="date".$index;
   $sql="SHOW FULL COLUMNS FROM hostapp_so WHERE Field='$field'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE hostapp_so ADD `$field` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   if($row2[labelonly]=='x') $showdate=$row2[label];
   else
   {
      $date=explode("-",$row2[tourndate]);
      $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." ($row2[label])";
   }
   $sohostshow[$i]=$showdate;
   $sohostdates[$i]=$row2[tourndate];
   $i++;
}


if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1 || $school=="Test's School")
   {
   $width=ereg_replace("\'","\'",$width);
   $width=ereg_replace("\"","\'",$width);
   $length=ereg_replace("\'","\'",$length);
   $length=ereg_replace("\"","\'",$length);
   $complex=ereg_replace("\'","\'",$complex);
   $complex=ereg_replace("\"","\'",$complex);
   $director=ereg_replace("\'","\'",$director);
   $director=ereg_replace("\"","\'",$director);
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   $comments=addslashes($comments);
   $sql="SELECT * FROM hostapp_so WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_so (school,interested,";
      for($i=0;$i<count($sohostdates);$i++)
      {
         $index=$i+1; $var="date".$index;
	 $sql2.="$var, ";
      }
      $sql2.="surface,width,length,lights,complex,director,choice,neutral,comments) VALUES ('$school2','$interested',";
      for($i=0;$i<count($sohostdates);$i++)
      {
         $index=$i+1; $var="date".$index;
         $sql2.="'".$$var."', ";
      }
      $sql2.="'$surface','$width','$length','$lights','$complex','$director','$choice','$neutral','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_so SET interested='$interested', ";
      for($i=0;$i<count($sohostdates);$i++)
      {
         $index=$i+1; $var="date".$index;
         $sql2.="$var='".$$var."', ";
      } 
      $sql2.="surface='$surface',width='$width', length='$length', lights='$lights', complex='$complex', director='$director', choice='$choice',neutral='$neutral',comments='$comments' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
   echo mysql_error();
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else
   echO "<table width=\"100%\"><tr align=center><td>";

$curryear=date("Y",time());
$curryear1=$curryear+1;
//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='so'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_so";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_so (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $curryear1 BOYS & GIRLS SOCCER District/Sub-District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";

echo "<form method=post action=\"hostapp_so.php\">";
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
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Soccer District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Soccer District/Subdistrict Events.  The due date for this application is past due.</b></td></tr></table></td></tr>";
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
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Soccer?</th></tr>";
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
echo "<tr align=center><td><table width=\"85%\" class=\"nine\">";
echo "<tr align=left><th align=left>Please check the dates your facility is available for the district(s) you wish to host:</th></tr>";

echo "<tr align=center><td><table cellspacing=0 cellpadding=6 class=\"nine\" style=\"width:100%;\">";
$curdate="0000-00-00";
for($i=0;$i<count($sohostdates);$i++)
{
   if($curdate!=$sohostdates[$i])	//NEW ROW
   {
      if($i>0) echo "</tr>";
      echo "<tr align=left>";
      $curdate=$sohostdates[$i];
   }
   $index=$i+1; $var="date".$index;
   echo "<td><input type=checkbox name=\"$var\" value=\"y\"";
   if($row[$var]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo ">".$sohostshow[$i]."</td>";
}
echo "</tr></table></td></tr>";

echo "<tr align=left><td><br>";
echo "Our field surface is:&nbsp;&nbsp;<input type=radio name=\"surface\" value=\"grass\"";
if($row[surface]=="grass") echo " checked";
if($print==1) echo " disabled";
echo ">Grass&nbsp;&nbsp;<input type=radio name=\"surface\" value=\"turf\"";
if($row[surface]=="turf") echo " checked";
if($print==1) echo " disabled";
echo ">Turf</td></tr>";
echo "<tr align=left><td><br>";
echo "Our field dimensions are:&nbsp;&nbsp;WIDTH: ";
if($print==1) echo "$row[7] X $row[8]</td></tr>";
else
   echo "<input type=text name=width size=5 value=\"$row[7]\">&nbsp;&nbsp;X&nbsp;&nbsp;LENGTH: <input type=text name=length size=5 value=\"$row[8]\"></td></tr>";
echo "<tr align=left><td><br>";
echo "Name of field or soccer complex:&nbsp;&nbsp;";
if($print==1) echo "$row[9]</td></tr>";
else echo "<input type=text name=complex size=40 value=\"$row[9]\"></td></tr>";
echo "<tr align=left><td><br>";
echo "Do you have lights for your field?&nbsp;&nbsp;<input type=radio name=\"lights\" value=\"yes\"";
if($row[lights]=="yes") echo " checked";
if($print==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;<input type=radio name=\"lights\" value=\"no\"";
if($row[lights]=="no") echo " checked";
if($print==1) echo " disabled";
echo ">No</td></tr>";
echo "<tr align=left><td><br>";
echo "Who will serve as the District Tournament Director if your school hosts?&nbsp;&nbsp;";
if($print==1) echo "$row[10]</td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[10]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
   echo "<tr align=left><td><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else echo "<textarea name=comments rows=5 cols=60>$row[comments]</textarea></td></tr>";
   if($print!=1)
   {
      echo "<tr align=center><td><br><input type=submit name=submitapp";
      if(PastDue($duedate) && $level!=1 && $school!="Test's School") echo " disabled";
      echo " value=\"Submit Application\"></td></tr>";
   }
}
echo "</table></form>";
echo "</div>";
} //END FOR EACH APP TO HOST

echo $end_html;
?>
