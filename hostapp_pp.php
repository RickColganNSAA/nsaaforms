<?php
//hostapp_pp.php: site survey for Play Production

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
if($level!=1)
   $school=GetSchool($session);
else
{
   if($sample==1) $school="Test's School";
   else $school=$school_ch;
}
$school2=addslashes($school);

   $ppdist=array(); $i=0;
   $ppdist_sm=array();
   $sql="SELECT * FROM $db_name2.pptourndates WHERE hostdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $ppdist[$i]=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $num=$i+1;
      $index="date".$num;
      $sql2="SHOW FULL COLUMNS FROM hostapp_pp WHERE Field='$index'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="ALTER TABLE hostapp_pp ADD `$index` VARCHAR(10) NOT NULL";
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
   $sql="SELECT * FROM hostapp_pp WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_pp (school,interested,";
      for($i=0;$i<count($ppdist);$i++)
      {
         $num=$i+1;
         $var="date".$num;
	 $sql2.="$var,";
      }
      $sql2.="director,choice,neutral,comments) VALUES ('$school2','$interested',";
      for($i=0;$i<count($ppdist);$i++)      
      {         
	 $num=$i+1;         
	 $var="date".$num;
         $sql2.="'".$$var."',";
      }
      $sql2.="'$director','$choice','$neutral','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_pp SET interested='$interested', ";
      for($i=0;$i<count($ppdist);$i++)      
      {         
	 $num=$i+1;         
	 $var="date".$num;
         $sql2.="$var='".$$var."',";
      }
      $sql2.=" director='$director',choice='$choice',neutral='$neutral',comments='$comments' WHERE school='$school2'";
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
$sql="SELECT duedate FROM app_duedates WHERE sport='pp'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
$curryear=$date[0];
$curryear1=$curryear+1;

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_pp";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_pp (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $curryear-$curryear1 PLAY PRODUCTION District/Sub-District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";

echo "<form method=post action=\"hostapp_pp.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<table width=85%>";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
{
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Play Production District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
}
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1 && $school!="Test's School")
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Play Production District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
      echo "</table>";
      echo $end_html;
      exit();
   }  
   else
   {
      echo "<tr align=left><td><font style=\"color:red\"><b>The due date for this application is past.  The application you've submitted is shown below.  You can no longer make changes to your application.  If you wish to do so, please contact the NSAA.</b></font></td></tr>";
   }
} 
}	//END IF NOT PRINT
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Play Production?</th></tr>";
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
echo "<tr align=center><td><table class='nine' width='85%'>";
echo "<tr align=left><td colspan=2><b>We are interested in hosting NSAA district contest(s) on the dates selected:</b></td></tr>";
for($i=0;$i<count($ppdist);$i++)
{
   $num=$i+1;
   $var="date".$num;
   echo "<tr align=left><td colspan=2>";
   echo "<input type=checkbox name='$var' value='y'";
   if($row[$var]=='y') echo " checked";
   if($print==1) echo " disabled";
   echo "> $ppdist[$i]</td></tr>";
}
echo "<tr align=left><td colspan=2><br>";
echo "If your school is selected as a district host in play production, who will serve as <b>District Director</b>?&nbsp;&nbsp;";
if($print==1) echo "$row[director]</td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[director]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
   echo "<tr align=left><td><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else echo "<textarea rows=5 cols=60 name=\"comments\">$row[comments]</textarea></td></tr>";
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
