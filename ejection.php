<?php

require 'functions.php';
require 'variables.php';
require 'officials/variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   //check if logged in as NSAA Officials Admin
   mysql_close();
   $db=mysql_connect("$db_host",$db_user2,$db_pass2);
   mysql_select_db($db_name2,$db);
   $sql="SELECT * FROM sessions WHERE session_id='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0 && $off!=1)
   {
      header("Location:index.php?error=1");
      exit();
   }
   else
   {
      $userlevel=1;
      //user is OK; change back to $db_name
      mysql_close();
      $db=mysql_connect("$db_host",$db_user,$db_pass);
      mysql_select_db($db_name,$db);
   }
}

if(!$userlevel)
   $userlevel=GetLevel($session);
//get AD's school and their name
if($userlevel==2)  //user is an AD
{
   $school=GetSchool($session);
   $school0=addslashes($school);
}
else	//user is NSAA
{
   $sql="SELECT school FROM ejections WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $school=$row[0];
   $school0=addslashes($school);
}
$sql="SELECT name,email FROM logins WHERE school='$school0' AND level=2";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$name=$row[0]; $email=$row[1];
if(trim($name)=="")
{
   $sql="SELECT name,email FROM logins WHERE sport='Activities Director' AND school='$school0'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name=$row[0]; $email=$row[1];
}

if($submitejection=="Submit")
{
   $sid=GetSID2($school,$sport);
   $site=addslashes($site);
   $coach=addslashes($coach);
   $comment=addslashes($comment);
   $school2=addslashes($school2);

   $gamedate=$year."-".$month."-".$day;

   $error=0;
   if($sport=='' || $month=='MM' || $day=='DD' || $school2=='' || $site=='' || ($player=='' && $coach=='') || !$level)
      $error=1;

   if($error==0 && $userlevel!=1)
   {
      $today=time();
      $sql="INSERT INTO ejections (sport,school,sid,gamedate,school2,site,player,number,coach,level,comment,datesub) VALUES ('$sport','$school0','$sid','$gamedate','$school2','$site','$player','$number','$coach','$level','$comment','$today')";
      $result=mysql_query($sql);

      //get id of this ejection
      $sql="SELECT id FROM ejections WHERE school='$school0' ORDER BY id DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $id=$row[0];

      //send to NSAA
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To="jschwartz@nsaahome.org"; $ToName="Jennifer Schwartz";
      $To="nneuhaus@nsaahome.org"; $ToName="Jennifer Schwartz";
      $Subject="An AD Has Submitted an Ejection Report";
      $Text="$name, the AD of $school, has just submitted an ejection report for ".GetEjectionActivity($sport).".";
      $Html="$name, the AD of $school, has just submitted an ejection report for ".GetEjectionActivity($sport).".";
      $Attm=array();
      SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);

      header("Location:view_ejection.php?id=$id&session=$session&new=1");
      exit();
   }
   else if($error==0)	//NSAA user: update
   {
      $notes=addslashes($notes);
      $sql="UPDATE ejections SET sport='$sport',sid='$sid',school='$school0',gamedate='$gamedate',school2='$school2',site='$site',player='$player',number='$number',coach='$coach',level='$level',comment='$comment',verify='$verify',notes='$notes' WHERE id='$id'";
      $result=mysql_query($sql);

      header("Location:view_ejection.php?header=$header&off=$off&id=$id&session=$session");
      exit();
   }
}

//if NSAA user AND id given, get submitted info from database
if($userlevel==1 && $id)
{
   $sql="SELECT * FROM ejections WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sport=$row[sport];
   $gamedate=split("-",$row[gamedate]);
   $year=$gamedate[0];
   $month=$gamedate[1];
   $day=$gamedate[2];
   $school2=$row[school2];
   $site=$row[site];
   $player=$row[player];
   $number=$row[number];
   $coach=$row[coach];
   $level=$row[level];
   $comment=$row[comment];
   $verify=$row[verify];
   $notes=$row[notes];
}

echo $init_html;
if($header!='no') echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";

echo "<form method=post action=\"ejection.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=header value=$header>";
echo "<input type=hidden name=off value=$off>";
echo "<input type=hidden name=id value=$id>";
echo "<table width=500><caption><b>Nebraska High School Activities Association Ejection Report:</b><br>";
echo "(Fields marked with a * are required.)<br>";
if($error==1)
{
   echo "<font style=\"color:red\"><b>You must complete ALL fields marked with a *.</b></font>";
}
echo "<hr></caption>";
if($userlevel==1)
{
   echo "<tr align=left><td><table>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<b><u>NSAA ONLY:</u></b></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<b>Verified:</b>&nbsp;&nbsp;";
   echo "<input type=checkbox name=\"verify\" value='x'";
   if($verify=='x') echo " checked";
   echo "></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<b>Notes:</b>&nbsp;&nbsp;";
   echo "<input type=text class=tiny size=30 name=\"notes\" value=\"$notes\"></td></tr>";
   echo "</table><br></td></tr>";
}
echo "<tr align=left><td><b>School:</b>&nbsp;&nbsp;&nbsp;$school</td></tr>";
echo "<tr align=left><td>*Sport: <select onchange=\"submit();\" name=sport><option value=''>~</option>";
for($i=0;$i<count($eject);$i++)
{
   echo "<option value=\"$eject2[$i]\"";
   if($sport==$eject2[$i]) echo " selected";
   echo ">$eject_long[$i]</option>";
}
echo "</select></td></tr>";

if($sport && $sport!='')
{
   if(ereg("_",$sport))
   {
      $temp=split("_",$sport);
      $spcheck=$temp[0];
      $gender=$temp[1];
      if($gender=='b') $gender='m';
      else if($gender=='g') $gender='f';
      $sql="SELECT id,first,last,middle FROM eligibility WHERE school='$school0' AND $spcheck='x' AND gender='$gender' ORDER BY last,first,middle";
   }
   else
   {
      $sql="SELECT id,first,last,middle FROM eligibility WHERE school='$school0' AND ";
      if($sport=='fb') $sql.="(fb68='x' OR fb11='x') ";
      else $sql.="$sport='x' ";
      $sql.="ORDER BY last,first,middle";
   }
   $result=mysql_query($sql);
   echo "<tr align=left><td>";
   echo "*Player Ejected: <select name=\"player\"><option value=''>~</option>";
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($player==$row[id]) echo " selected";
      echo ">$row[first] $row[middle] $row[last]</option>";
   }
   echo "</select>  ";
   echo "Uniform No. <input type=text class=tiny size=3 name=number value=\"$number\">";
   echo "</td></tr>";
   echo "<tr align=left><td>OR (You must enter either a player or a coach that was ejected)</td></tr>";
   echo "<tr align=left><td>*Name of Coach Ejected: <input type=text value=\"$coach\" name=coach size=40 class=tiny></td></tr>";
      $gamedate="0000-00-00";

   echo "<tr align=left><td>*Date of Contest: ";
   echo "<select name=month><option>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $mo="0".$i;
      else $mo=$i;
      echo "<option";
      if($mo==$month) echo " selected";
      echo ">$mo</option>";
   }
   echo "</select>/<select name=day><option>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($d==$day) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=year>";
   $curryr=date("Y");
   $lastyr=$curryr-1;
   $curryr1=$curryr+1;
   echo "<option";
   if($year==$lastyr) echo " selected";
   echo ">$lastyr</option>";
   echo "<option";
   if($year==$curryr || !$year) echo " selected";
   echo ">$curryr</option>";
   echo "<option";
   if($year==$curryr1) echo " selected";
   echo ">$curryr1</option>";
   echo "</select></td></tr>";
   echo "<tr align=left><td>*Contest: ";
   echo "$school VS. ";
   echo "<select name=school2><option value=''>~</option>";
   $schooltbl=GetSchoolsTable($sport);
   if(!IsWildcardSport(ereg_replace("_","",$sport)))
      $sql="SELECT school FROM headers ORDER BY school";
   else
      $sql="SELECT school,sid FROM $schooltbl ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option";
      if($school2==$row[0]) echo " selected";
      echo ">$row[0]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>*Site of Contest: <input type=text value=\"$site\" name=site size=50 class=tiny></td></tr>";
   echo "<tr align=left><td>*Level (Please choose one): ";
   echo "<input type=radio name=level value='Varsity'";
   if($level=="Varsity") echo " checked";
   echo ">Varsity&nbsp;&nbsp;";
   echo "<input type=radio name=level value='Junior Varsity'";
   if($level=='Junior Varsity') echo " checked";
   echo ">Junior Varsity&nbsp;&nbsp;";
   echo "<input type=radio name=level value='Reserve'";
   if($level=="Reserve") echo " checked";
   echo ">Reserve&nbsp;&nbsp;";
   echo "<input type=radio name=level value='Freshman'";
   if($level=="Freshman") echo " checked";
   echo ">Freshman</td></tr>";
   echo "<tr align=left><td>Additional Comments (Optional):<br>";
   echo "<textarea rows=10 cols=60 name=comment>$comment</textarea></td></tr>";

   echo "<tr align=left><td><b>Name of AD Submitting Report: </b>$name</td></tr>";
   echo "<tr align=left><td><b>E-mail: </b>$email</td></tr>";

   echo "<tr align=left><td><b>Any player or coach ejected from a contest for unsportsmanlike conduct shall be ineligible for the next athletic contest at that level of competition and any other athletic contest at any level during the interim, in addition to other penalties that NSAA or school may assess.</b></td></tr>";
   echo "<tr align=left><td><b>Coaches who are ejected for unsportsmanlike conduct may not participate in any coaching activities during any of the contests during the \"sit-out\" period, nor may they be present at the contest site or facility for contests during the time of their \"sit-out\" suspension.</b></td></tr>";
   echo "<tr align=center><td><input type=submit name=submitejection value=\"Submit\"></td></tr>";
}
echo "</table>";
echo $end_html;

?>
