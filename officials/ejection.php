<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions


//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$userlevel=GetLevel($session);
if($userlevel==4) $userlevel=1;

if($submitreport=="Submit")
{
   $sid=GetSID2($school,$sport);
   $site=addslashes($site);
   $coach=addslashes($coach);
   $reason=addslashes($reason);
   if(!IsWildcardSport(ereg_replace("_","",$sport)))
   {
      $schooltbl=GetSchoolsTable($sport);
      $school=addslashes(GetMainSchoolName($sid,$sport));
   }
   else
      $school=addslashes($school);
   $school1=addslashes($school1);
   $school2=addslashes($school2);
   $off1=addslashes($off1);
   $off2=addslashes($off2);
   $off3=addslashes($off3);
   $off4=addslashes($off4);

   $gamedate=$year."-".$month."-".$day;

   $error=0;
   if($sport=='' || $school=='' || $month=='MM' || $day=='DD' || $school1=='' || $school2=='' || $site=='' || ($player=='' && $coach=='') || !$level || $reason=='')
   {
      $errormsg="<ul>";
      if($sport=='') $errormsg.="<li>You are missing the SPORT.</li>";
      if($school=='') $errormsg.="<li>You are missing the SCHOOL.</li>";
      if($month=='MM' || $day=='DD') $errormsg.="<li>You are missing the CONTEST DATE.</li>";
      if($school1=='' || $school2=='') $errormsg.="<li>You did not enter both OPPONENTS.</li>";
      if($site=='') $errormsg.="<li>You are missing the SITE.</li>";
      if($player=='' && $coach=='') $errormsg.="<li>You are missing the PLAYER or COACH that got ejected.</li>";
      if(!$level) $errormsg.="<li>You did not mark the LEVEL of play.</li>";
      if($reason=='') $errormsg.="<li>You did not enter the REASON for ejection.</li>";
      $errormsg.="</ul>";
      $error=1;
   }

   if($error==0 && $userlevel!=1)
   {
      $today=time();
      $sql="INSERT INTO ejections (offid,sport,sid,school,gamedate,school1,school2,site,player,number,coach,level,reason,off1,off2,off3,off4,datesub) VALUES ('$offid','$sport','$sid','$school','$gamedate','$school1','$school2','$site','$player','$number','$coach','$level','$reason','$off1','$off2','$off3','$off4','$today')";
      $result=mysql_query($sql);

      //get id of this ejection
      $sql="SELECT id FROM ejections WHERE offid='$offid' ORDER BY id DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $id=$row[0];

      //send to NSAA
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To="jschwartz@nsaahome.org"; $ToName="Jennifer Schwartz";
      $To="nneuhaus@nsaahome.org"; $ToName="Jennifer Schwartz";
      $Subject="An Official Has Submitted an Ejection Report";
      $Text=GetOffName($offid).", an NSAA official, has just submitted an ejection report for $school ".GetSportName($sport).".";
      $Html=GetOffName($offid).", an NSAA official, has just submitted an ejection report for $school ".GetSportName($sport).".";
      $Attm=array();
      if($offid!='3427')
         SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);

      header("Location:view_ejection.php?id=$id&session=$session&new=1");
      exit();
   }
   else if($error==0)	//NSAA Admin user
   {
      $notes=addslashes($notes);
      $sql="UPDATE ejections SET sport='$sport',sid='$sid',school='$school',gamedate='$gamedate',school1='$school1',school2='$school2',site='$site',player='$player',number='$number',coach='$coach',level='$level',reason='$reason',off1='$off1',off2='$off2',off3='$off3',off4='$off4',verify='$verify',notes='$notes' WHERE id='$id'";
      $result=mysql_query($sql);

      header("Location:view_ejection.php?header=$header&off=$off&id=$id&session=$session");
      exit();
   }
}

//if NSAA admin, get submitted information for this ejection id
if($id && $off==1)
{
   $sql="SELECT * FROM ejections WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $offid=$row[offid];
   $sport=$row[sport];
   $school=$row[school];
   $gamedate=split("-",$row[gamedate]);
   $year=$gamedate[0]; $month=$gamedate[1]; $day=$gamedate[2];
   $school1=$row[school1]; $school2=$row[school2];
   $site=$row[site];
   $player=$row[player]; $number=$row[number];
   $coach=$row[coach];
   $level=$row[level];
   $reason=$row[reason];
   $off1=$row[off1]; $off2=$row[off2]; $off3=$row[off3]; $off4=$row[off4];
   $datesub=date("m/d/Y",$row[datesub]);
   $verify=$row[verify];
   $notes=$row[notes];
}
else
   $offid=GetOffID($session);


echo $init_html;
if($header!='no') echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";

echo "<form method=post action=\"ejection.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=id value=$id>";
echo "<input type=hidden name=header value=$header>";
echo "<input type=hidden name=off value=$off>";
echo "<input type=hidden name=offid value=$offid>";
echo "<table width=90%><caption><b>Nebraska High School Activities Association Ejection Report:</b><br>";
echo "(Fields marked with a * are required.)<br>";
if($error==1)
{
   echo "<div class='error' style='width:400px'>YOU HAVE THE FOLLOWING ERRORS IN YOUR FORM:<br><div class='normalwhite'>$errormsg</div></div>";
}
echo "<hr></caption>";
if($userlevel==1)
{
   echo "<tr align=left><td><table>";
   echo "<tr align=left><td><b><u>NSAA ONLY:</u></b></td></tr>";
   echo "<tr align=left><td><b>Verified:</b>&nbsp;&nbsp;";
   echo "<input type=checkbox name=verify value='x'";
   if($verify=='x') echo " checked";
   echo "></td></tr>";
   echo "<tr align=left><td><b>Notes:</b>&nbsp;&nbsp;";
   echo "<input type=text class=tiny size=30 name=notes value=\"$notes\"></td></tr>";
   echo "</table><br></td></tr>";
}
echo "<tr align=left><td>*Sport: <select onchange=\"submit();\" name=sport><option value=''>~</option>";
for($i=0;$i<count($eject2);$i++)
{
   echo "<option value=\"$eject2[$i]\"";
   if($sport==$eject2[$i]) echo " selected";
   echo ">$eject_long[$i]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td>*School: <select onchange=\"submit();\" name='school'><option value=''>~</option>";

//connect to $db_name
mysql_close();
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);
$schooltbl=GetSchoolsTable($sport);
if(!IsWildcardSport(ereg_replace("_","",$sport)))
   $sql="SELECT school FROM headers ORDER BY school";
else
   $sql="SELECT school,sid,outofstate FROM $schooltbl ORDER BY school";
$result=mysql_query($sql);
$sch=array(); $s=0;
while($row=mysql_fetch_array($result))
{
   if($row[outofstate]!='1')
   {
      echo "<option";
      if($school==$row[0]) echo " selected";
      echo ">$row[0]</option>";
   }
   $sch[$s]=$row[0];
   $s++;
}
echo "</select></td></tr>";
if($sport && $sport!='' && $school && $school!='')
{
   $school0=addslashes($school);
   if(IsWildcardSport(ereg_replace("_","",$sport)))
   {
      $cursp=ereg_replace("_","",$sport);
      $schooltbl=GetSchoolsTable($cursp);
      $sql="SELECT * FROM $schooltbl WHERE school='$school0'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sql2="SELECT * FROM headers WHERE (id='$row[mainsch]' OR ";
      if($row[othersch1]!='0') $sql2.="id='$row[othersch1]' OR ";
      if($row[othersch2]!='0') $sql2.="id='$row[othersch2]' OR ";
      if($row[othersch3]!='0') $sql2.="id='$row[othersch3]' OR ";
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      $schoolsql="(";
      while($row2=mysql_fetch_array($result2))
      {
         $cursch=addslashes($row2[school]);
         $schoolsql.="school='$cursch' OR ";
      }
      $schoolsql=substr($schoolsql,0,strlen($schoolsql)-4);
      $schoolsql.=")";
   }
   else
      $schoolsql="school='$school0'";
   if(ereg("_",$sport))
   {
      $temp=split("_",$sport);
      $spcheck=$temp[0];
      $gender=$temp[1];
      if($gender=='b') $gender='m';
      else if($gender=='g') $gender='f';
      $sql="SELECT id,first,last,middle FROM eligibility WHERE ".$schoolsql." AND $spcheck='x' AND gender='$gender' ORDER BY last,first,middle";
   }
   else
   {
      $sql="SELECT id,first,last,middle FROM eligibility WHERE ".$schoolsql." AND ";
      if($sport=='fb') $sql.="(fb68='x' OR fb11='x') ";
      else $sql.="$sport='x' ";
      $sql.="ORDER BY last,first,middle";
   }
   $result=mysql_query($sql);
   echo "<tr align=left><td>";
   echo "*Player Ejected: <select name=player><option value=''>~</option>";
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
   echo "<select name=school1><option value=''>~</option>";
   for($i=0;$i<count($sch);$i++)
   {
      echo "<option";
      if($school1==$sch[$i]) echo " selected";
      echo ">$sch[$i]</option>";
   }
   echo "</select> VS. <select name=school2><option value=''>~</option>";
   for($i=0;$i<count($sch);$i++)
   {
      echo "<option";
      if($school2==$sch[$i]) echo " selected";
      echo ">$sch[$i]</option>";
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
   echo "<tr align=left><td>Reason for Ejection Rule (Rule Reference):<br>";
   echo "<textarea rows=10 cols=60 name=reason>$reason</textarea></td></tr>";

   //back to $db_name2
   mysql_close();
   $db=mysql_connect("$db_host",$db_user2,$db_pass2);
   mysql_select_db($db_name2,$db);

   echo "<tr align=left><td><b>Name of Official Submitting Report: </b>".GetOffName($offid)."</td></tr>";
   $sql="SELECT email FROM officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td><b>E-mail: </b>$row[0]</td></tr>";
   echo "<tr align=left><td>Name of Official: <input type=text name=off1 value=\"$off1\" class=tiny size=40></td></tr>";
   echo "<tr align=left><td>Name of Official: <input type=text value=\"$off2\" name=off2 class=tiny size=40></td></tr>";
   echo "<tr align=left><td>Name of Official: <input type=text value=\"$off3\" name=off3 class=tiny size=40></td></tr>";
   echo "<tr align=left><td>Name of Official: <input type=text value=\"$off4\" name=off4 class=tiny size=40></td></tr>";

   echo "<tr align=left><td><b>Any player or coach ejected from a contest for unsportsmanlike conduct shall be ineligible for the next athletic contest at that level of competition and any other athletic contest at any level during the interim, in addition to other penalties that NSAA or school may assess.</b></td></tr>";
   echo "<tr align=center><td><input type=submit name=submitreport value=\"Submit\"></td></tr>";
}
echo "</table>";
echo $end_html;

?>
