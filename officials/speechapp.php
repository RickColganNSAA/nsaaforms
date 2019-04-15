<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

$level=GetLevelJ($session);

if(!$givenoffid) $offid=GetJudgeID($session);
else
{
   $offid=$givenoffid;
   $header="no";
}
if($print==1) $header="no";

//GET SPEECH DATES
   $spdist=array(); $i=0;
   $spdist2=array(); $spdist_sm=array();
   $sql="SELECT * FROM sptourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $spdist[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $spdist2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $spdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $num=$i+1;
      $index="dist".$num;
      $sql2="SHOW FULL COLUMNS FROM spapply WHERE Field='$index'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="ALTER TABLE spapply ADD `$index` VARCHAR(10) NOT NULL";
         $result2=mysql_query($sql2);
      }
      $i++;
   }
   $spstate=array(); $i=0;
   $spstate2=array(); $spstate_sm=array();
   $sql="SELECT * FROM sptourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $class=trim(preg_replace("/State/","",$row[label]));
      $spstate[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $spstate2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $spstate_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $num=$i+1;
      $index="state".$num;
      $sql2="SHOW FULL COLUMNS FROM spapply WHERE Field='$index'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="ALTER TABLE spapply ADD `$index` VARCHAR(10) NOT NULL";
         $result2=mysql_query($sql2);
      }
      $i++;
   }

if($submit && $offid)
{
   $conflict=addslashes($conflict);
   $schconflicts='';
   for($i=0;$i<count($schconflict);$i++)
   {
      if($schconflict[$i]!='')
         $schconflicts.="$schconflict[$i]/";
   }
   $schconflicts=substr($schconflicts,0,strlen($schconflicts)-1); 
   //enter info into database
   $sql="SELECT id FROM spapply WHERE offid='$offid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO spapply (offid,dist1,dist2,dist3,dist4,dist5,dist6,dist7,state1,state2,classrep,classpref,conflict,classconflict,schconflict,dist_not_int,state_not_int) VALUES ('$offid','$dist[0]','$dist[1]','$dist[2]','$dist[3]','$dist[4]','$dist[5]','$dist[6]','$state[0]','$state[1]','$classrep','$classpref[0]/$classpref[1]/$classpref[2]/$classpref[3]/$classpref[4]/$classpref[5]','$conflict','$classconflict[0]/$classconflict[1]/$classconflict[2]/$classconflict[3]/$classconflict[4]/$classconflict[5]','$schconflicts','$dist_not_int','$state_not_int')";
   }
   else	//UPDATE
   {
      $sql2="UPDATE spapply SET dist1='$dist[0]',dist2='$dist[1]',dist3='$dist[2]',dist4='$dist[3]',dist5='$dist[4]',dist6='$dist[5]',dist7='$dist[6]',state1='$state[0]',state2='$state[1]',classrep='$classrep',classpref='$classpref[0]/$classpref[1]/$classpref[2]/$classpref[3]/$classpref[4]/$classpref[5]',conflict='$conflict',classconflict='$classconflict[0]/$classconflict[1]/$classconflict[2]/$classconflict[3]/$classconflict[4]/$classconflict[5]',schconflict='$schconflicts',dist_not_int='$dist_not_int',state_not_int='$state_not_int' WHERE offid='$offid'";
   }
   $result2=mysql_query($sql2);
}

$duedate=GetDueDate("sp","app");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);

echo $init_html;
if($header!='no') echo GetHeaderJ($session);
else echo "<table width=100%><tr align=center><td>";
if(!(PastDue($june1,0) && $june1time>$duetime))
{
echo "<br>";
echo "<form method=post action=\"speechapp.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$givenoffid>";
echo "<input type=hidden name=header value=\"$header\">";
echo "<table><caption><b>Application to Judge District & State Speech Contests:";
if($level==1) echo " (".GetJudgeName($offid).")";
echo "</b><br>Due $duedate2";
if($submit)
{
   echo "<br><font style=\"color:blue;font-size:9pt;\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed above.</b></font>";
}
else
{
   $sql="SELECT * FROM spapply WHERE offid='$offid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
      echo "<br><font style=\"color:blue;font-size:9\"><b>The following application to judge is currently posted to the NSAA by you.  You may make updates to this application until the due date listed above.</b></font><br><br>";
}
$offid=GetJudgeID($session);  

   $sql="SELECT payment FROM judges WHERE id='$offid' ";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if (empty($row['payment'])) echo "<h4><a href=\"japplication.php?session=$session&off_id=$offid\">Back to Judges Application Form</h4></a><br />";

echo "<hr></caption>";
}
else
{
   echo "<table>";
}
$sql="SELECT * FROM spapply WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($level!=1 && mysql_num_rows($result)>0 && PastDue($duedate,1) && !(PastDue($june1,0) && $june1time>$duetime))	//form is past due: show user what they entered
{
   echo "<tr align=center><td><font style=\"color:blue;font-size:9\"><b>The due date for this application is past.  The information you have submitted is as follows below.</b></font><br><br></td></tr>";
   echo "<tr align=center><td><b><u>CONSIDERATION FOR DISTRICT SPEECH CONTEST:</u></b></td></tr>";
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td colspan=2>I am available to judge a <b><u>District</b></u> Speech Contest on the dates indicated:</td></tr>";
   for($i=0;$i<count($spdist);$i++)
   {
      echo "<tr align=left><td>";
      $index=$i+1;
      $index="dist".$index;
      if($row[$index]=='x') echo "YES";
      else echo "NO";
      echo "</td><td>$spdist[$i]</td></tr>";
   }
   echo "</table></td></tr>";
   echo "<tr align=center><td><br><br><b><u>CONSIDERATION FOR STATE SPEECH CONTEST:</u></b></td></tr>";
   echo "<tr align=left><td><table>";
   echo "<tr align=left><td colspan=2>I am available to judge the <b><u>State</b></u> Speech Contest to be held in Kearney on the dates indicated.</td></tr>";
   for($i=0;$i<count($spstate);$i++)
   {
      echo "<tr align=left><td>";
      $index=$i+1;
      $index="state".$index;
      if($row[$index]=='x') echo "YES";
      else echo "NO";
      echo "</td><td>$spstate[$i]</td></tr>";
   }
   echo "</table></td></tr>";
   echo "<tr align=center><td><br><table>";
   echo "<tr align=left><td><b>Classification I represent:</b></td>";
   echo "<td>$row[classrep]</td></tr>";
   echo "<tr align=left><td><b>I prefer to judge:</b></td>";
   echo "<td>";
   $classprefs=split("/",$row[classpref]);
   for($i=0;$i<count($classes);$i++)
   {
      if($classprefs[$i]==$classes[$i]) echo "$classes[$i]&nbsp;&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   echo "<tr align=left><td><b>I have conflicts with schools in:</b></td>";
   echo "<td>";
   $classconflict=split("/",$row[classconflict]);
   for($i=0;$i<count($classes);$i++)
   {
      if($classconflict[$i]==$classes[$i]) echo "$classes[$i]&nbsp;&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   echo "<tr align=left valign=top><td><b>Specific Conflicting Schools:</b></td>";
   echo "<td>$row[schconflict]</td></tr>";
   echo "<tr align=left valign=top><td><b>Other Comments:</b></td>";
   echO "<td>$row[conflict]</td></tr>";
   echo "</table></td></tr></table>";
   echo $end_html;
   exit();
}
else if($level!=1 && mysql_num_rows($result)==0 && PastDue($duedate,1) && !(PastDue($june1,0) && $june1time>$duetime))     //nothing entered
{
   echo "<tr align=center><td><font style=\"color:blue;font-size:9pt\"><b>The due date for this application is
 past.  You may no longer apply to judge District/State Speech Contests.</b></font></td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}  
else if($level!=1 && PastDue($june1,0) && PastDue($duedate,0)) //due date is past AND June 1 is past
{
   echo "<tr align=center><td><i>This form is currently unavailable.</i><br><br>";
   echo "<a href=\"jwelcome.php?session=$session\">Home</a></td></tr></table>";
   echo $end_html;
   exit();
}

echo "<tr align=center><td><b><u>CONSIDERATION FOR DISTRICT SPEECH CONTEST:</u></b></td></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td colspan=2>I am available to judge a District Speech Contest on the dates indicated:</td></tr>";

for($i=0;$i<count($spdist);$i++)
{
   echo "<tr align=left><td><input type=checkbox name=\"dist[$i]\" value='x'";
   $index=$i+1;
   $index="dist".$index;
   if($row[$index]=='x') echo " checked";
   echo "></td><td>$spdist[$i]</td></tr>";
}
echo "<tr align=left><td><input type=checkbox name=\"dist_not_int\" value='x'";
if($row['dist_not_int']=='x') echo " checked";
echo "></td><td>NOT INTERESTED</td></tr>";
echo "</table></td></tr>";
echo "<tr align=center><td><br><br><b><u>CONSIDERATION FOR STATE SPEECH CONTEST:</u></b></td></tr>";
echo "<tr align=left><td><table>";
echo "<tr align=left><td colspan=2>I am available to judge the State Speech Contest to be held in Kearney on the dates indicated.</td></tr>";
for($i=0;$i<count($spstate);$i++)
{
   echo "<tr align=left><td><input type=checkbox name=\"state[$i]\" value='x'";
   $index=$i+1;
   $index="state".$index;
   if($row[$index]=='x') echo " checked";
   echo "></td><td>$spstate[$i]</td></tr>";
}
echo "<tr align=left><td><input type=checkbox name=\"state_not_int\" value='x'";
if($row['state_not_int']=='x') echo " checked";
echo "></td><td>NOT INTERESTED</td></tr>";
echo "</table></td></tr>";
echo "<tr align=center><td><br><table>";
echo "<tr align=left><td><b>Classification I represent:</b></td>";
echo "<td>";
for($i=0;$i<count($classes);$i++)
{
   echo "<input type=radio name=\"classrep\" value=\"$classes[$i]\"";
   if($row[classrep]==$classes[$i]) echo " checked";
   echo ">$classes[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "<tr align=left valign=top><td><b>I prefer to judge:<br>(you may select more than 1 class)</b></td>";
echo "<td>";
$classprefs=split("/",$row[classpref]);
for($i=0;$i<count($classes);$i++)
{
   echo "<input type=checkbox name=\"classpref[$i]\" value='$classes[$i]'";
   if($classprefs[$i]==$classes[$i]) echo " checked";
   echo ">$classes[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "<tr align=left valign=top><td><br><b>I have conflicts with schools in:<br>(you may select more than 1 class)</b></td>";
echo "<td><br>";
$classconflict=split("/",$row[classconflict]);
for($i=0;$i<count($classes);$i++)
{
   echo "<input type=checkbox name=\"classconflict[$i]\" value='$classes[$i]'";
   if($classconflict[$i]==$classes[$i]) echo " checked";
   echo ">$classes[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "<tr align=left valign=top><td colspan=2><b>Please specify any schools with which you have a conflict that ARE NOT in your class:</b></td></tr>";
echo "<tr align=center><td colspan=2><table>";
$sql2="SELECT school FROM $db_name.headers ORDER BY school";
$result2=mysql_query($sql2);
$i=0; $schs=array();
while($row2=mysql_fetch_array($result2))
{
   $schs[$i]=$row2[0]; $i++;
}
$schconflict=split("/",$row[schconflict]);
for($i=0;$i<8;$i++)
{
   if($i%2==0) echo "<tr align=left>";
   echo "<td><select name=\"schconflict[$i]\"><option value=''>~</option>";
   for($j=0;$j<count($schs);$j++)
   {
      echo "<option";
      if($schs[$j]==$schconflict[$i]) echo " selected";
      echo ">$schs[$j]</option>";
   }
   echo "</select></td>";
   if($i%2>0) echo "</tr>";
}
echo "</table></td></tr>";
echo "<tr align=left valign=top><td><b>Please note any other comments here:</td><td><textarea name=\"conflict\" rows=3 cols=40>$row[conflict]</textarea></td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<input type=submit name=submit value=\"Submit\">";
echo "</td></tr></table>";
echo "</form>";
if($header!='no')
   echo "<br><a href=\"jwelcome.php?session=$session\">Home</a>";
else
   echo "<br><a href=\"javascript:window.close()\">Close</a>";

echo $end_html;
?>
