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

if(!$sport) $sport=$schedsport;

$fallyear=GetFallYear($sport);
$sportname=GetSportName($sport);

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;

$table=$sport."sched";
$level=GetLevel($session);
if($level==4) $level=1;
//get off name
$sql="SELECT first,last FROM officials WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$offname="$row[0] $row[1]";

if($delete)
{
   $sql="DELETE FROM $table WHERE id='$delete'";
   $result=mysql_query($sql);
   $deleted=1;
}
else if($save || $add)
{
   //add new entries to database
      $curdate=$year."-".$month."-".$day;
      $curtime=$hour."-".$min."-".$ampm;
      if($sport=='bb')
      {
	 if($boysgirls=='boys') $girls='';
	 else if($boysgirls=='girls') $girls='x';
	 else $error="boysgirls";
      }
      $location=ereg_replace("\'","\'",$location);
      $schools=ereg_replace("\'","\'",$schools);
      $meetname=ereg_replace("\'","\'",$meetname);
      $otheroff=ereg_replace("\'","\'",$otheroff); 
      if($sport=='so')
      {
         $otheroff2=ereg_replace("\'","\'",$otheroff2);
         if(trim($otheroff)!='' && trim($otheroff2)!='')
            $otheroff.=", ".$otheroff2;
      }

      if($add)
      {
         $sql="INSERT INTO $table (offid,offdate,location,";
	 if($sport=='tr') $sql.="meetname,";
	 else $sql.="schools,";
	 if($sport=="so") $sql.="positions,";
	 else if(ereg("bb",$sport)) $sql.="crewct,girls,";
	 $sql.="gametime,otheroff) VALUES ('$offid','$curdate','$location',";
	 if($sport=='tr') $sql.="'$meetname',";
	 else $sql.="'$schools',";
	 $positions=$center." ".$ar1." ".$ar2;
	 if($sport=="so") $sql.="'$positions',";
	 else if(ereg("bb",$sport)) $sql.="'$crewct','$girls',";
	 if($tba=='x') $curtime="TBA";
	 $sql.="'$curtime','$otheroff')";
         $result=mysql_query($sql);	
	 $added=1;
      }
      else	//save
      {
         $positions=$center." ".$ar1." ".$ar2;
         $sql="UPDATE $table SET offdate='$curdate',location='$location'";
         if($sport=='tr') $sql.=",meetname='$meetname'";
         else $sql.=",schools='$schools'";
         if($sport=='so') $sql.=",positions='$positions'";
         else if(ereg("bb",$sport)) $sql.=",crewct='$crewct',girls='$girls'";
         if($tba=='x') $curtime="TBA";
         $sql.=",gametime='$curtime',otheroff='$otheroff' WHERE id='$editid'";
         $result=mysql_query($sql);
         $saved=1;
      }

   //update crew members if fb
   if($sport=='fb' && $level==1)
   {
      $sql="UPDATE fbapply SET chief='$chief',referee='$referee',umpire='$umpire',linejudge='$linejudge',linesman='$linesman',backjudge='$backjudge' WHERE offid='$offid'";
      $result=mysql_query($sql);
   }

   header("Location:schedule.php?session=$session&sport=$sport&added=$added&saved=$saved");
   exit();
}
//update starter value if TRACK
if($trsubmit)
{
   $sql0="SELECT * FROM trapply WHERE offid='$offid'";
   $result0=mysql_query($sql0);
   if(mysql_num_rows($result0)==0)
      $sql="INSERT INTO trapply (offid,starter) VALUES ('$offid','$starter')";
   else
      $sql="UPDATE trapply SET starter='$starter' WHERE offid='$offid'";
   $result=mysql_query($sql);
   header("Location:schedule.php?session=$session&sport=$sport&added=1");
   exit();
}

echo $init_html;
if($level=='2')
   echo GetHeader($session);
echo "<table width=100% cellspacing=0><tr align=center><td align=center>";
echo "<br><form method=post action=\"schedule.php\">";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=givenoffid value=$givenoffid>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
$curryear=date("Y",time());
if($level==1 && $edit!='yes')
   echo "<a class=small href=\"schedule.php?session=$session&givenoffid=$givenoffid&sport=$sport&edit=yes\">Edit</a>";
if($level==1)
   echo "&nbsp;&nbsp;<a class=small href=\"javascript:window.close();\">Close</a><br><br>";
echo "<font style=\"color:blue\"><b>";
if($submit)
{
   echo "Your information has been saved.<br>";
}
if($level==2 && !$submit)
   echo "The following schedule has been posted to the NSAA by you:</b><br>";
//echo "<br><table width=100% border=1 bordercolor=#000000 cellspacing=0 cellpadding=1>";
echo "<table width='900px' cellspacing=0 cellpadding=3><caption><b>$curryear <font style=\"color:red;font-size:10pt\"><u>$sportname</u></font> ";
if($sport=='ba' || $sport=='sb') echo "Umpiring";
else echo "Officiating";
echo " Schedule for $offname:</b><br>";
if($level=='2')
{
   echo "<div class=alert style=\"width:700px;\">";
   echo "<b>INSTRUCTIONS:</b>  NOTE:  <i>Select the date and time for each competition you will be an official for and also enter the competition location, meet name, and the names of any other officials that will be working the meet with you.  Then click <b>\"Add Entry\"</b>.  Repeat these steps for each entry in your schedule.  To <b>edit an entry</b>, click \"Edit\" next to the entry and you will be able to edit it at the top of the page.  When you are finished making your changes, click \"Save Changes\".  To <b>delete</b> an entry, click \"Delete\" next to that entry.</i>";
   echo "</div>";
   if($sport!='tr')
      echo "<br><font style=\"font-size:12px;color:blue\"><b>NOTE: Only enter <u>REGULAR SEASON</u> contests <u>for the current year</u>.  You do NOT need to enter district or state competitions.</b></font>";
}
echo "<br>";
//show crew members
if($sport=="fb") 
{
   //if FB, get crew members the official entered on their app to officiate
   $sql2="SELECT chief,referee,umpire,linesman,linejudge,backjudge FROM fbapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $chief=$row2[chief];
   $referee=$row2[referee];
   $umpire=$row2[umpire]; 
   $linesman=$row2[linesman];
   $linejudge=$row2[linejudge];
   $backjudge=$row2[backjudge];

   echo "<table><tr align=left><td colspan=2><b>Crew Members:</b><hr></td></tr>";
   if($edit=="yes")
   {
      echo "<tr align=left><td><b>Crew Chief:</b></td><td><select name=chief><option value=''>~</option>";
      if($chief=='0') $chief=$offid;
      $sql2="SELECT id,first,last FROM officials WHERE fb='x' ORDER BY last,first";
      $result2=mysql_query($sql2);
      $fb=array(); $fbname=array(); $f=0;
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=$row2[id]";
         if($chief==$row2[id]) echo " selected";
         echo ">$row2[last], $row2[first]</option>";
         $fb[$f]=$row2[id]; $fbname[$f]="$row2[last], $row2[first]"; $f++;
      }
      echo "</select></td></tr>";
      echo "<tr align=left><td><b>Referee:</b></td><td><select name=referee><option value=''>~</option>";
      for($i=0;$i<count($fb);$i++)
      {
         echo "<option value=$fb[$i]";
         if($referee==$fb[$i]) echo " selected";
         echo ">$fbname[$i]</option>";
      }
      echo "</select></td></tr>";
      echo "<tr align=left><td><b>Umpire:</b></td>";
      echo "<td><select name=umpire><option value=''>~</option>";
      for($i=0;$i<count($fb);$i++)
      {
         echo "<option value=$fb[$i]";
         if($umpire==$fb[$i]) echo " selected";
         echo ">$fbname[$i]</option>";
      }
      echo "</select></td></tr>";
      echo "<tr align=left><td><b>Linesman:</b></td>";
      echo "<td><select name=linesman><option value=''>~</option>";
      for($i=0;$i<count($fb);$i++)
      {
         echo "<option value=$fb[$i]";
         if($linesman==$fb[$i]) echo " selected";
         echo ">$fbname[$i]</option>";
      }
      echo "</select></td></tr>";
      echo "<tr align=left><td><b>Line Judge:</b></td>";
      echo "<td><select name=linejudge><option value=''>~</option>";
      for($i=0;$i<count($fb);$i++)
      {
         echo "<option value=$fb[$i]";
         if($linejudge==$fb[$i]) echo " selected";
         echo ">$fbname[$i]</option>";
      }
      echo "</select></td></tr>";
      echo "<tr align=left><td><b>Back Judge:</b></td>";
      echo "<td><select name=backjudge><option value=''>~</option>";
      for($i=0;$i<count($fb);$i++)
      {
         echo "<option value=$fb[$i]";
         if($backjudge==$fb[$i]) echo " selected";
         echo ">$fbname[$i]</option>";
      }
      echo "</select>";
      echo "</td></tr>";
   }
   else
   {
      echo "<tr align=left><td><b>Crew Chief:</b><td>".GetOffName($chief)."</td></tr>";
      echo "<tr align=left><td><b>Referee:</b></td><td>".GetOffName($referee)."</td></tr>";
      echo "<tr align=left><td><b>Umpire:</b></td><td>".GetOffName($umpire)."</td></tr>";
      echo "<tr align=left><td><b>Linesman:</b></td><td>".GetOffName($linesman)."</td></tr>";
      echo "<tr align=left><td><b>Line Judge:</b></td><td>".GetOffName($linejudge)."</td></tr>";
      echo "<tr align=left><td><b>Back Judge:</b></td><td>".GetOffName($backjudge)."</td></tr>";
   }
   if($level==2)
      echo "<tr align=left><td colspan=2><b>NOTE: <i>Your crew members' names are pulled from your application to officiate.<br>To view/edit your application, <a href=\"#\" onclick=\"window.open('fbapp.php?session=$session&header=no','fbapp','width=600,height=600,menubar=no,titlebar=no,resizable=yes,scrollbars=yes');\" class=small>Click Here</a> and then Reload this screen after you've edited your crew.</b></i></td></tr>";
   echo "</table><br>";
} 
if($sport=='tr' && $edit=='yes')
{
   $sql="SELECT starter FROM trapply WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $starter=$row[0];
   echo "<table width=500><tr align=left><td>";
   //check that not past track "application to officiate" due date
   $duedate=GetDueDate('tr','app');
   $date=split("-",$duedate);
   $duedate2=date("m/d/y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<br><input type=checkbox name=starter value='x'";
   if($starter=='x') echo " checked";
   if(PastDue($duedate,0)) echo " disabled";
   echo ">&nbsp;<font style=\"font-size:12pt;\">I would like to be considered to be a State Track & Field Starter<br></font>";
   if(PastDue($duedate,0))
      echo "(<i>You may not change your answer to the checkbox above after $duedate2</i>)";
   else
      echo "(<i>There is no Application to Officiate for Track. Please check the box above if you wish to be a State Track & Field Starter and then click the \"Save & Submit\" button.</i>)<br>";
   echo "<br><input type=submit name=trsubmit value=\"Save & Submit\">";
   echo "</td></tr></table>";
}
if($sport=='bb' && $error=="boysgirls")
{
   echo "<table><tr align=center><td><font style=\"color:red;font-size:9pt;\">You MUST select \"Boys\" or \"Girls\" for each game on your schedule.</font></td></tr></table>";
}
echo "</caption>";
if($level=='2' || ($level==1 && $edit=='yes'))
{
   if(!$editid)   
      echo "<tr align=left><td colspan=6><b><u>ADD NEW ENTRY:</b></u></td></tr>";
   else   
   {
      echo "<tr align=left><td colspan=6><b><u>EDIT ENTRY:</b></u></td></tr>";
      echo "<input type=hidden name=editid value=\"$editid\">";
   }
   echo "<tr align=center><th class=smaller colspan=2>Date</th>";
   if(ereg("bb",$sport))   
      echo "<th class=smaller>Boys or Girls<br>(check one)</th>";
   echo "<th class=smaller>Location</th><th class=smaller>";
   if($sport=="tr") 
      echo "Meet Name";
   else 
      echo "Schools (list both)<br>All Schools for Tournament";
   echo "</th><th class=smaller>Local time of ";
   if($sport=='tr') echo "MEET";
   else if(ereg("bb",$sport)) echo "VARSITY GAME";
   else echo "VARSITY MATCH";
   echo "</th>";
   if($sport!="fb" && $sport!="tr")
   {   
      echo "<th class=smaller>Other Officials";   
      if($sport=='so') echo "<br>(List both officials)";   
      echo "</th>";
   }
   else if($sport=="fb")
   {   
      echo "<td><b>Notes (List substitutes here)</b></th>";
   }
   if($sport=="so")
   {   
      echo "<th class=smaller>Positions<br>Worked</th>";
   }
   echo "</tr>";
   if($editid)
   {
      $sql="SELECT * FROM ".$sport."sched WHERE id='$editid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $offdate=split("-",$row[offdate]);
      $month=$offdate[1]; $day=$offdate[2]; $year=$offdate[0];
      $location=$row[location]; $schools=$row[schools];
      $gametime=split("-",$row[gametime]);
      $hour=$gametime[0]; $min=$gametime[1]; $ampm=$gametime[2];
      $tba=$row[gametime];
      $otheroff=$row[otheroff]; $boysgirls=$row[girls];
      if(ereg("bb",$sport))
      {
         if($boysgirls=='x') $boysgirls="girls";
         else $boysgirls="boys";
	 $crewct=$row[crewct];
      }
      else if(ereg("tr",$sport))
      {
         $schools=$row[meetname];
      }
      else if(ereg("so",$sport))
      {
	 $temp=split(", ",$otheroff);
         $otheroff=$temp[0]; $otheroff2=$temp[1];
         $pos=split(" ",$row[positions]);
         $center=$pos[0]; $ar1=$pos[1]; $ar2=$pos[2];
      }
   }
   echo "<tr align=center valign=top>";
   echo "<td colspan=2 width=200><select class=small name=\"month\">";
   for($j=1;$j<=12;$j++)
   {
      echo "<option";
      if($month==$j) echo " selected";
      echo ">$j</option>";
   }
   echo "</select>/<select class=small name=\"day\">";
   for($j=1;$j<=31;$j++)
   {
      echo "<option";
      if($day==$j) echo " selected";
      echo ">$j</option>";
   }
   echo "</select>/<select class=small name=\"year\">";
   for($j=($curryear-1);$j<($curryear+2);$j++)
   {
      echo "<option";
      if($year==$j) echo " selected";
      else if(!$editid && $curryear==$j) echo " selected";
      echo ">$j</option>";
   }
   echo "</select></td>";
   if(ereg("bb",$sport))
   {
      echo "<td><input type=radio name=\"boysgirls\" value='boys'";
      if($boysgirls=="boys") echo " checked";
      echo ">Boys&nbsp;";
      echo "<input type=radio name=\"boysgirls\" value='girls'";
      if($boysgirls=="girls") echo " checked";
      echo ">Girls<br>";
      echo "<font style=\"color:red\">You must check one!!</font></td>";
   }
   echo "<td><input type=text size=15 class=tiny name=\"location\" value=\"$location\"></td>";
   echo "<td><input type=text size=20 class=tiny name=\"";
   if($sport=='tr') echo "meetname";
   else echo "schools";
   echo "\" value=\"$schools\"></td>";
   echo "<td width=170 align=left><select class=small name=\"hour\">";
   for($j=1;$j<=12;$j++)
   {
      echo "<option";
      if($hour==$j) echo " selected";
      echo ">$j</option>";
   }
   echo "</select>:<select class=small name=\"min\">";
   for($j=0;$j<60;$j++)
   {
      if($j<10) $j="0".$j;
      echo "<option";
      if($min==$j) echo " selected";
      echo ">$j</option>";
   }
   echo "</select><select class=small name=\"ampm\">";
   echo "<option";
   if($ampm=="PM") echo " selected";
   echo ">PM</option><option";
   if($ampm=="AM") echo " selected";
   echo ">AM</option></select>";
   echo "<br><input type=checkbox name=\"tba\" value='x'";
   if($tba=="TBA") echo " checked";
   echo ">&nbsp;TBA</td>";
   if($sport=='so')
   {
      echo "<td><input type=text size=20 class=tiny name=\"otheroff\" value=\"$otheroff\"><br>";
      echo "<input type=text size=20 class=tiny name=\"otheroff2\" value=\"$otheroff2\"></td>";
   }
   elseif($sport!="fb" && $sport!="tr")
   {
      echo "<td><input type=text size=20 class=tiny name=\"otheroff\" value=\"$otheroff\">";
      if(ereg("bb",$sport))
      {
         echo "<br><input type=radio name=\"crewct\" value='2'";
         if($crewct=='2') echo " checked";
         echo ">2-Person Crew&nbsp;";
         echo "<input type=radio name=\"crewct\" value='3'";
	 if($crewct=='3') echo " checked";
	 echo ">3-Person Crew";
      }
      echo "</td>";
   }
   else if($sport=="fb")
      echo "<td><input type=text size=30 class=tiny name=\"otheroff\" value=\"$otheroff\"></td>";
   if($sport=="so")
   {
      echo "<td align=left width=85>";
      echo "<input type=checkbox name=\"center\" value='Center'";
      if($center=="Center") echo " checked";
      echo ">&nbsp;Center<br>";
      echo "<input type=checkbox name=\"ar1\" value='AR1'";
      if($ar1=="AR1") echo " checked";
      echo ">&nbsp;AR1<br>";
      echo "<input type=checkbox name=\"ar2\" value='AR2'";
      if($ar2=="AR2") echo " checked";
      echo ">&nbsp;AR2</td>";
   }
   echo "</tr>";
   echo "<tr align=center><td colspan=8>";
   if($editid)
      echo "<input type=submit name=\"save\" value=\"Save Changes\">";
   else
      echo "<input type=submit name=\"add\" value=\"Add Entry\">";
   echo "</td></tr>";
}
echo "</table>";
echo "<br>";
$sql="SELECT * FROM $table WHERE offid='$offid' ORDER BY offdate";
$result=mysql_query($sql);
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"width:800px;border:#808080 1px solid;\" class='nine'>";
if($added==1)
   echo "<caption><div class=alert style=\"width:250px\"><i>Your schedule entry has been added below!</div></caption>";
else if($saved==1)
   echo "<caption><div class=alert style=\"width:250px\"><i>Your schedule entry has been saved below!</div></caption>";
else if($deleted==1)
   echo "<caption><div class=alert style=\"width:250px\"><i>Your schedule entry has been deleted.</div></caption>";
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left>";
   if(($level=='2' || ($level==1 && $edit=='yes')) && !$scoreid) echo "<td><b>Edit/Delete</b></td>";
   echo "<td><b>Date</b></td>";
   if(ereg("bb",$sport)) echo "<td><b>Boys/Girls</b></td>";
   echo "<td><b>Location</b></td><td><b>Schools</b></td><td><b>Time</b></td>";
   if($sport!='tr') echo "<td><b>Other Officials</b></td>";
   if($sport=='so') echo "<td><b>Positions</b></td>";
   echo "</tr>";
}
$ix=0;
while($row=mysql_fetch_array($result))
{
   $curdate=ereg_replace("-","/",$row[offdate]);
   $curdate=substr($curdate,5,5)."/".substr($curdate,0,4);
   echo "<tr align=left>";
   echo "<input type=hidden name=\"schedid[$ix]\" value=\"$row[id]\">";
   $scoreid=$row[scoreid];
   if(($level=='2' || ($level==1 && $edit=='yes')) && !$scoreid)
      echo "<td align=left><a class=small href=\"schedule.php?session=$session&sport=$sport&editid=$row[id]\">Edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"schedule.php?session=$session&sport=$sport&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this schedule entry?');\">Delete</a></td>";
   else if(($level=='2' || ($level==1 && $edit=='yes')) && $scoreid) 
      echo "<td align=left><font style=\"color:#A0A0A0\">May NOT<br>Delete</font></td>";
   echo "<td";
   //if($level!='2' && !($level==1 && $edit=='yes')) echo " colspan=2";
   echo ">$curdate</td>";
   if($table=="bbsched")
   {
      echo "<td>";
      if($row[girls]=='x') echo "GIRLS";
      else echo "BOYS";
      echo "</td>";
   }
   if($scoreid)
   {
      $wildsp=$sport.$row[gender];
      $schedtbl=$wildsp."sched"; $tourntbl=$wildsp."tourn";
      $sql2="SELECT * FROM $db_name.$schedtbl WHERE scoreid='$scoreid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $sid=GetSchoolName($row2[sid],$wildsp,$fallyear);
      $oppid=GetSchoolName($row2[oppid],$wildsp,$fallyear);
      $host=GetSchoolName($row2[homeid],$wildsp,$fallyear);
      if($row2[tid]>0)
      {
         $sql3="SELECT * FROM $db_name.$tourntbl WHERE tid='$row2[tid]'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
         $host=$row3[name];
      }
      echo "<td colspan=2>$sid vs. $oppid @ $host<br><a class=small target=new href=\"reportcard.php?session=$session&sport=$wildsp&header=no&finished=1&schedid=$row[id]\">Click to view the Report Card you've submitted for this game</a></td>"; 
   } 
   else
   {
      echo "<td>$row[location]</td>";
      echo "<td>";
      if($sport=='tr') echo $row[meetname];
      else echo $row[schools];
      echo "&nbsp;</td>";
   }
   if($row[gametime]!="TBA")
   {
      $time=split("-",$row[gametime]);
      $curtime=$time[0].":".$time[1]." ".$time[2];
   }
   else
   {
      $curtime=$row[gametime];
   }
   echo "<td>$curtime</td>";
   if($sport!="fb" && $sport!="tr") 
   {
      echo "<td>$row[otheroff]&nbsp;";
      if((ereg("bb",$sport)) && $row[crewct]!=0) echo "<br>($row[crewct]-Person Crew)";
      echo "</td>";
   }
   else if($sport=="fb") echo "<td>$row[otheroff]&nbsp;</td>";
   if($sport=="so") echo "<td>$row[positions]&nbsp;</td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";
echo "</form>";
if($level==2)
   echo "<a href=\"welcome.php?session=$session\" class=small>Home</a>&nbsp;&nbsp;&nbsp;";
if($level==1)
   echo "<a class=small href=\"javascript:window.close();\">Close</a>";
echo "</td></tr></table>";
echo $end_html;
?>

