<?php
//hostapp_te_g.php: site survey for girls tennis

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
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

if($submitapp=="Submit Application")
{
   if(!PastDue($duedate) || $level==1)
   {
   $facility=addslashes($facility);
   $location=addslashes($location);
   $director=addslashes($director);
   /*
   $choice=ereg_replace("\'","\'",$choice);
   $choice=ereg_replace("\"","\'",$choice);
   $neutral=ereg_replace("\'","\'",$neutral);
   $neutral=ereg_replace("\"","\'",$neutral);
   */
   $comments=addslashes($comments);
   $sql="SELECT * FROM hostapp_te_g WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO hostapp_te_g (school,interested,indoorcourts,outdoorcourts,indoor,outdoor,facility,location,director,comments) VALUES ('$school2','$interested','$indoorcourts','$outdoorcourts','$indoor','$outdoor','$facility','$location','$director','$comments')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE hostapp_te_g SET interested='$interested', indoorcourts='$indoorcourts', outdoorcourts='$outdoorcourts', indoor='$indoor', outdoor='$outdoor', facility='$facility', location='$location', director='$director',comments='$comments' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
   }
}

echo $init_html;
if($nsaa!=1)
   echo $header;
else
   echO "<table width=100%><tr align=center><td>";

$curryear=date("Y",time());
$curryear1=$curryear+1;
//get due date of this site survey
$sql="SELECT duedate FROM app_duedates WHERE sport='te_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

//get info already in DB for this school and survey:
$sql="SELECT * FROM hostapp_te_g";
if($school!='' || $level!=1) $sql.=" WHERE school='$school2'";
else
{
   $sql.=" WHERE interested='y' ORDER BY school";       //TO PRINT ALL (Level 1)
   $print=1;
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $school!='')
{
   $sql2="INSERT INTO hostapp_te_g (school) VALUES ('$school2')";
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
   echo "<h3>Application to Host a $fallyear GIRLS TENNIS District/Sub-District Event</h3>";

   if($print!=1) echo "<p>Due $duedate2</p>";
echo "<form method=post action=\"hostapp_te_g.php\">";
echo "<input type=hidden name=\"sample\" value=\"$sample\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=duedate value=\"$duedate\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<table width='85%'>";
if($print!=1)
{
echo "<tr align=center><td>";
if($submitapp && (!PastDue($duedate) || $level==1))
{
   echo "<font style=\"color:red\" size=2><b><i>Your application to host Girls Tennis District/Sub-District Events has been submitted.  Below is what you have submitted.  You may make changes to this application until the above due date.</i></b></font>";
}
else if(!PastDue($duedate) || $level==1)
   echo "<br>(After the due date, you may only view, not edit, this form)";
echo "<hr></td></tr>";
if(PastDue($duedate) && $level!=1)
{
   if(mysql_num_rows($result)==0)
   {
      echo "<tr align=center><td><table><tr align=left><td><b>You did NOT submit an Application to Host Girls Tennis District/Subdistrict Event.  The due date for this application is past.</b></td></tr></table></td></tr>";
      echo "</table>";
      echo $end_html;
      exit();
   }  
   else
   {
      echo "<tr align=left><td><font style=\"color:red\"><b>The due date for this application is past.  The application you've submitted is shown below.  You can no longer make changes to your application.  If you wish to do so, please contact the NSAA.</b></font></td></tr>";
   }
} 
}//end if not print
echo "<tr align=left><th align=left>1) Are you interested in hosting any NSAA district contests for Girls Tennis on $te_ghostdates[0]?</th></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<input type=radio onclick=\"submit();\" name=interested value='y'";
if($interested=='y' ||  (!$interested && $row[2]=='y')) echo " checked";
if($print==1) echo " disabled";
echo ">YES&nbsp;&nbsp;<input type=radio name=interested onclick=\"submit();\" value='n'";
if((!$interested && $row[2]=='n') || $interested=='n') echo " checked";
if($print==1) echo " disabled";
echo ">NO</td></tr>";
if($interested=='y' || (!$interested && $row[2]=='y'))
{
echo "<tr align=left><th align=left><br>&nbsp;&nbsp;&nbsp;If YES:</th></tr>";
echo "<tr align=center><td><table width=85%>";
echo "<tr align=left><td><input type=checkbox name=\"indoor\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('indoordiv').style.display='block';document.getElementById('indoordiv').style.visibility='visible'; }\"";
if($row[indoor]=="x") echo " checked";
if($print==1) echo " disabled";
echo ">Indoor Courts<br><div ";
if($row[indoor]=='' && $row[indoorcourts]=='') echo "style=\"display:none;visibility:hidden;\" ";
else echo "style=\"display:block;visibility:visible;\" ";
echo "id=\"indoordiv\">Please indicate the number of courts:&nbsp;";
if($print==1) echo "$row[indoorcourts]</div></td></tr>";
else echo "<input type=text name=\"indoorcourts\" value=\"$row[indoorcourts]\" size=3></div></td></tr>";
echo "<tr align=left><td><input type=checkbox name=\"outdoor\" value=\"x\" onClick=\"if(this.checked) { document.getElementById('outdoordiv').style.display='block';document.getElementById('outdoordiv').style.visibility='visible'; }\"";
if($row[outdoor]=="x") echo " checked";
if($print==1) echo " disabled";
echo ">Outdoor Courts<br><div ";
if($row[outdoor]=='' && $row[outdoorcourts]=='') echo "style=\"display:none;visibility:hidden;\" ";
else echo "style=\"display:block;visibility:visible;\" ";
echo "id=\"outdoordiv\">Please indicate the number of courts:&nbsp;";
if($print==1) echo "$row[outdoorcourts]</div></td></tr>";
else echo "<input type=text name=\"outdoorcourts\" value=\"$row[outdoorcourts]\" size=3></div></td></tr>";
echo "<tr align=left><td><br>";
echo "Name of Facility:&nbsp;&nbsp;";
if($print==1) echo "$row[facility]</td></tr>";
else echo "<input type=text name=\"facility\" size=40 value=\"$row[facility]\"></td></tr>";
echo "<tr align=left><td><br>";
echo "Location of Facility:&nbsp;&nbsp;";
if($print==1) echo "$row[location]</td></tr>";
else echo "<input type=text name=\"location\" size=40 value=\"$row[location]\"></td></tr>";
echo "<tr align=left><td><br>";
echo "Who will serve as the District Tournament Director?&nbsp;&nbsp;";
if($print==1) echo "$row[director]</td></tr>";
else echo "<input type=text name=director size=25 value=\"$row[director]\"></td></tr>";
echo "</table></td></tr>";
}
if($interested!='' || $row[2]!='')
{
   echO "<tr align=left><td><p><b>Other Comments:</b></p>";
   if($print==1) echo "<p>$row[comments]</p></td></tr>";
   else echo "<textarea rows=5 cols=60 name=\"comments\">$row[comments]</textarea></td></tr>";
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
