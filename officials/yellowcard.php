<?php
/**********************************************
Yellow Card Report (for Officials to submit)
Created 2/14/11
Author Ann Gaffigan
**********************************************/

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions


//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
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
   $reason=addslashes($reason);

   $gamedate=$year."-".$month."-".$day;

   $error=0;
   if($sport=='' || $sid=='0' || $month=='MM' || $day=='DD' || $oppid==0 || ($studentid=='0' && trim($coach)=="") || !$level || $reason=='')
   {
      $errormsg="<ul>";
      if($sport=='') $errormsg.="<li>You are missing the SPORT.</li>";
      if($sid=='0') $errormsg.="<li>You are missing the SCHOOL.</li>";
      if($month=='MM' || $day=='DD') $errormsg.="<li>You are missing the GAME DATE.</li>";
      if($oppid=='0') $errormsg.="<li>You did not enter the OPPONENT.</li>";
      if($studentid==0 && trim($coach)=='') $errormsg.="<li>You are missing the PLAYER or COACH who received the yellow card.</li>";
      if(!$level) $errormsg.="<li>You did not mark the LEVEL of play.</li>";
      if($reason=='') $errormsg.="<li>You did not enter the REASON for the yellow card.</li>";
      $errormsg.="</ul>";
      $error=1;
   }

   if($error==0 && $userlevel!=1)
   {
      $today=time(); $coach=addslashes(trim($coach));
      $sql="INSERT INTO yellowcards (offid,sport,sid,gamedate,oppid,studentid,coach,level,reason,datesub) VALUES ('$offid','$sport','$sid','$gamedate','$oppid','$studentid','$coach','$level','$reason','$today')";
      $result=mysql_query($sql);
      $id=mysql_insert_id();

      //send to NSAA
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To="jschwartz@nsaahome.org"; $ToName="Jennifer Schwartz";
      $To="nneuhaus@nsaahome.org"; $ToName="Jennifer Schwartz";
      $Subject="An Official Has Submitted a Yellow Card Report";
      $Text=GetOffName($offid).", an NSAA official, has just submitted a yellow card report for $school ".GetSportName($sport).".";
      $Html=GetOffName($offid).", an NSAA official, has just submitted a yellow card report for $school ".GetSportName($sport).".";
      $Attm=array();
      if($offid!='3427')
         SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);

      header("Location:view_yellowcard.php?id=$id&session=$session&new=1");
      exit();
   }
   else if($error==0)	//NSAA Admin user
   {
      $notes=addslashes($notes);
      $sql="UPDATE yellowcards SET sport='$sport',sid='$sid',gamedate='$gamedate',oppid='$oppid',coach='".addslashes(trim($coach))."',studentid='$studentid',level='$level',reason='$reason',verify='$verify',notes='$notes' WHERE id='$id'";
      $result=mysql_query($sql);

      header("Location:view_yellowcard.php?header=$header&off=$off&id=$id&session=$session");
      exit();
   }
}

//if NSAA admin, get submitted information for this yellow card id
if($id && $off==1)
{
   $sql="SELECT * FROM yellowcards WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $offid=$row[offid];
   $sport=$row[sport];
   $sid=$row[sid];
   $gamedate=split("-",$row[gamedate]);
   $year=$gamedate[0]; $month=$gamedate[1]; $day=$gamedate[2];
   $oppid=$row[oppid]; 
   $studentid=$row[studentid];
   $coach=$row[coach];
   $level=$row[level];
   $reason=$row[reason];
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

echo "<form method=post action=\"yellowcard.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=id value=$id>";
echo "<input type=hidden name=header value=$header>";
echo "<input type=hidden name=off value=$off>";
echo "<input type=hidden name=offid value=$offid>";
echo "<table width=90%><caption><b>Nebraska High School Activities Association YELLOW CARD Report:</b><br>";
echo "(Fields marked with a * are required.)<br>";
if($error==1)
{
   echo "<div class='error' style='width:400px'>YOU HAVE THE FOLLOWING ERRORS IN YOUR FORM:<br><div class='normalwhite'>$errormsg</div></div>";
}
echo "<hr></caption>";
echo "<tr align=left><th><br>NOTE: If the player received two yellow cards, please fill out the ejection report form in lieu of a second yellow card report.</th></tr>";
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
echo "<tr align=left><td>*Sport: <select onchange=\"submit();\" name=sport><option value=''>~</option><option value='sog'";
if($sport=='sog') echo " selected";
echo ">GIRLS Soccer</option><option value='sob'";
if($sport=='sob') echo " selected";
echo ">BOYS Soccer</option>";
echo "</select></td></tr>";
echo "<tr align=left><td>*School: <select onchange=\"submit();\" name='sid'><option value='0'>~</option>";
$sql="SELECT * FROM $db_name.".$sport."school ORDER BY school";
$result=mysql_query($sql);
$sch=array(); $s=0;
while($row=mysql_fetch_array($result))
{
   if($row[outofstate]!='1')
   {
      echo "<option value=\"$row[sid]\"";
      if($sid==$row[sid]) echo " selected";
      echo ">$row[school]</option>";
   }
   $sch[$s]=$row[sid];
   $s++;
}
echo "</select></td></tr>";
if($sport && $sport!='' && $sid && $sid!='')
{
   //GET PIECE OF MYSQL QUERY THAT PULLS STUDENTS FROM ALL SCHOOLS INCLUDING COOPS FOR SELECTED TEAM
      $sql="SELECT * FROM $db_name.".$sport."school WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sql2="SELECT * FROM $db_name.headers WHERE (id='$row[mainsch]' OR ";
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
  
   
   if($sport=='sob') $gender='m';
   else $gender='f';
   $sql="SELECT id,first,last,middle FROM $db_name.eligibility WHERE ".$schoolsql." AND so='x' AND gender='$gender' ORDER BY last,first,middle";
   $result=mysql_query($sql);
   echo "<tr align=left><td>";
   echo "*Player Receiving Yellow Card: <select name='studentid'><option value=''>~</option>";
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($studentid==$row[id]) echo " selected";
      echo ">$row[first] $row[middle] $row[last]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>OR<br>*Coach Receiving Yellow Card: <input type=text name='coach' id='coach' value='$coach'></td></tr>";
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
   echo "<tr align=left><td>*Opponent: <select name=\"oppid\"><option value='0'>Select Opponent</option>";
   for($i=0;$i<count($sch);$i++)
   {
      echo "<option value=\"$sch[$i]\"";
      if($oppid==$sch[$i]) echo " selected";
      echo ">".GetSchoolName($sch[$i],$sport,date("Y"))."</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>*Level (Please choose one): ";
   echo "<input type=radio name=level value='Varsity'";
   if($level=="Varsity") echo " checked";
   echo ">Varsity&nbsp;&nbsp;";
   echo "<input type=radio name=level value='Junior Varsity'";
   if($level=='Junior Varsity') echo " checked";
   echo ">Junior Varsity</td></tr>";
   echo "<tr align=left><td>Reason for Yellow Card:<br>";
   echo "<textarea rows=10 cols=60 name=reason>$reason</textarea></td></tr>";

   echo "<tr align=left><td><b>Name of Official Submitting Report: </b>".GetOffName($offid)."</td></tr>";
   $sql="SELECT email FROM officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td><b>E-mail: </b><a href='mailto:$row[0]'>$row[0]</a></td></tr>";

   echo "<tr align=left><th><br>NOTE: If the player received two yellow cards, please fill out the ejection report form in lieu of a second yellow card report.</b></th></tr>";
   echo "<tr align=center><td><input type=submit name=submitreport value=\"Submit\"></td></tr>";
}
echo "</table>";
echo $end_html;

?>
