<?php
//echo "THIS PAGE IS UNDER CONTRUCTION.  PLEASE CHECK BACK IN 1 HOUR.  THANK YOU!<br><br><br><br>";

require 'functions.php';
require 'variables.php';

$db=mysql_connect("$db_host",$db_user2,$db_pass2);

if($ad==1)	//connect to $db_name to verify user
{
   $sql="SELECT * FROM $db_name.sessions WHERE session_id='$session'";
   //get school
   $sql2="SELECT t1.school FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $school=$row2[0];
   $header="
   <table cellspacing=3 cellpadding=3 width=100%>
   <tr align=center><td><a href=\"javascript:window.close()\" class=small>Close</a>
   &nbsp;&nbsp;<a class=small href=\"hostcontract.php?session=$session&sport=$sport&distid=$distid\">View your Contract to Host</a><br>
   <img src=\"nsaacontract.png\"></td></tr><tr align=center><td>";
}
else
{
   $sql="SELECT * FROM $db_name2.sessions WHERE session_id='$session'";
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   header("Location:../index.php?error=1");
   //echo $sql;
   exit();
}

mysql_select_db($db_name2, $db);
if($ad!=1) 
{
   if($sport=='sp' || $sport=='pp') $header=GetHeaderJ($session);
   else $header=GetHeader($session);
}

//GIVEN: $sport, $distid
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$sportname=GetSportName($sport);

//GET UNIQUE HOST DATES:
if(preg_match("/so/",$sport))
{
   if(preg_match("/b/",$sport)) $boygirl="boys";
   else $boygirl="girls";
   $sql2="SELECT DISTINCT tourndate FROM sotourndates WHERE hostdate='x' AND $boygirl='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $sohostdates=array(); $i=0;
   $soshowdates=array();
   while($row2=mysql_fetch_array($result2))
   {
      $sohostdates[$i]=$row2[tourndate];
      $date=explode("-",$row2[tourndate]);
      $soshowdates[$i]=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
}
else if($sport=='ba')
{
   $sql2="SELECT * FROM $db_name2.batourndates WHERE hostdate='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $bahostdates=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[labelonly]=='x') $showdate=$row2[label];
      else
      {
         $date=explode("-",$row2[tourndate]);
         $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
         if(trim($row2[label])!='') $showdate.=" ($row2[label])";
      }
      $bahostdates[$i]=$showdate;
      $bahostdates2[$i]=$row2[tourndate];
      $i++;
   }
}
else if($sport=='sb')
{
   $sql2="SELECT * FROM $db_name2.sbtourndates WHERE hostdate='x' ORDER BY tourndate,label";
   $result2=mysql_query($sql2);
   $sbhostdates=array(); $i=0;
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[labelonly]=='x') $showdate=$row2[label];
      else
      {
         $date=explode("-",$row2[tourndate]);
         $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
         if(trim($row2[label])!='') $showdate.=" ($row2[label])";
      }
      $sbhostdates[$i]=$showdate;
      $sbhostdates2[$i]=$row2[tourndate];
      $i++;
   }
}

//if Saved, update database:
if($submit)
{
   //Update district details that are entered by host (site, director, e-mail)
   $site=addslashes($site);
   $director=addslashes($director);
   $email=addslashes($email);
   if($sport=='so')
   {
      $sql="UPDATE $districts SET site='$site', director='$director', email='$email' WHERE id='$distid'";
   }
   else if($sport=='ba')
   {
      $datestr="";
      for($i=0;$i<count($bahostdates);$i++)
      {
         if($dates[$i])
            $datestr.=$dates[$i]."/";
      }
      $datestr=substr($datestr,0,strlen($datestr)-1);
      $sql="UPDATE $districts SET dates='$datestr', site='$site', director='$director', email='$email' WHERE id='$distid'";
   }
   else if($sport=='sb')
   {
      $datestr="";
      for($i=0;$i<count($sbhostdates);$i++)
      {
         if($dates[$i])
            $datestr.=$dates[$i]."/";
      }
      $datestr=substr($datestr,0,strlen($datestr)-1);
      $sql="UPDATE $districts SET dates='$datestr', site='$site', director='$director', email='$email' WHERE id='$distid'";
	//echo "$sql<br>";
   }
   else if($sport=='pp')
   {
      $datestr="$year-$month-$day";
      $sql="UPDATE $districts SET dates='$datestr',site='$site',director='$director',email='$email',time='$time' WHERE id='$distid'";
   }
   else
   {
      $sql="UPDATE $districts SET site='$site', director='$director', email='$email' WHERE id='$distid'";
   }
   $result=mysql_query($sql);

   if($timeslots==1)
   {
   //Update time slots
   for($i=0;$i<count($disttimesid);$i++)
   {
      if(($mo[$i]!="" && $day[$i]!="")) // || ($sport=='vb' && $level!=1))	//VB: AD can't edit dates (hidden 12/19/14)
      {
         $thisday=$year[$i]."-".$mo[$i]."-".$day[$i];
         $thistime=$hour[$i].":".$min[$i]." ".$ampm[$i]." ".$timezone[$i];
	 if($sport=='sb') $field[$i]=addslashes($field[$i]);
         if($disttimesid[$i]!='0')	//UPDATE
   	 {	  
	    if($sport=='vb' || $sport=="ubo")
	    {
               $sql="UPDATE $disttimes SET ";
	       //if($level==1) $sql.="day='$thisday', ";
	       $sql.="day='$thisday', ";	//gave access to host on 12/19/14
	       $sql.="time='$thistime',gamenum='$gamenum[$i]' WHERE id='$disttimesid[$i]'";
	    }
	    else if($sport=='sb')
	       $sql="UPDATE $disttimes SET sbfield='$field[$i]', day='$thisday', time='$thistime',gamenum='$gamenum[$i]' WHERE id='$disttimesid[$i]'";
            else $sql="UPDATE $disttimes SET day='$thisday', time='$thistime' WHERE id='$disttimesid[$i]'";
	 }
	 else				//INSERT
	 {
	    $sql="INSERT INTO $disttimes (distid,day,time) VALUES ('$distid','$thisday','$thistime')";
	    if($sport=='vb' || $sport=="ubo")
    	       $sql="INSERT INTO $disttimes (distid,day,time,gamenum) VALUES ('$distid','$thisday','$thistime','$gamenum[$i]')";
	    else if($sport=='sb')
               $sql="INSERT INTO $disttimes (distid,day,time,sbfield,gamenum) VALUES ('$distid','$thisday','$thistime','$field[$i]','$gamenum[$i]')";
	 }
	 //echo "$sql<br>$disttimesid[$i]<br>".mysql_error()."<br>";
	 $result=mysql_query($sql);
      }
      else if($disttimesid[$i]!='0') // && ($sport!='vb' || $level==1)) - changed 12/19/14
      {
	 $sql="UPDATE $disttimes SET day='0000-00-00', time='' WHERE id='$disttimesid[$i]'";
	 $result=mysql_query($sql);
      }
   }
   }//end if timeslots
}
//Get District Info
 $sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$teamcount=$row[teamcount];
$sql2="SELECT school FROM $db_name.logins WHERE id='$row[hostid]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);

//If AD user, check that school matches hostschool for this distid
if(($ad==1 && $row2[school]!=$school) || mysql_num_rows($result)==0)
{
   echo $init_html;
   echo $header; 
   echo "<br><b>An error occurred.</b>";
   echo $end_html;
   exit();
}

echo $init_html;
echo $header;
echo "<form method=post action=\"hostslots.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<input type=hidden name=ad value=\"$ad\">";
echo "<table width=600>";
if($submit)
{
   echo "<tr align=center><td colspan=2><font style=\"color:red\"><b>The information for this district has been saved.</b></font></td></tr>";
}
$duedate=GetDueDate($sport,"timeslot");
if(PastDue($duedate,0))
{
   $date=split("-",$duedate);
   $duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<tr align=center><td colspan=2><font style=\"color:red\">The deadline to submit this information was <b>$duedate2</b>.<br>If any changes need to be made, please contact the NSAA.</font></td></tr>";
   $edit=0;
}
else
{
   $date=split("-",$duedate);
   $duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<tr align=center><td colspan=2><font style=\"color:red\">The deadline to submit this information is <b>$duedate2</b>.<br>Please make sure the information below is complete and correct before the deadline is past.</font></td></tr>";
   $edit=1;
}
echo "<tr align=left><th colspan=2 align=left><u>$row[type] $row[class]-$row[district] $sportname ";
if($sport=='sp' || $sport=='pp') echo "Contest";
else echo "Tournament";
echo ":</u></th></tr>";
echo "<tr align=left><td><b>Host:</b></td><td>$row[hostschool]</td></tr>";
if($edit==1)
{
   echo "<tr align=left><td><b>Site:</b></td><td><input type=text class=tiny size=30 name=\"site\" value=\"$row[site]\"></td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td><input type=text class=tiny size=30 name=\"director\" value=\"$row[director]\"></td></tr>";
   echo "<tr align=left><td><b>E-mail:</b></td><td><input type=text class=tiny size=30 name=\"email\" value=\"$row[email]\"></td></tr>";
}
else
{
   echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td>$row[director]</td></tr>";
   echo "<tr align=left><td><b>E-mail:</b></td><td>$row[email]</td></tr>";
}
$temp=split(",",$row[schools]);
//$teamcount=count($temp);
if($sport=='sb')
   $gamecount=($teamcount*2)-1;
else
   $gamecount=$teamcount-1;
$days=split("/",$row[dates]);
$moch=""; $daych=""; $yearch="";
$datestr="";
for($i=0;$i<count($days);$i++)
{
   $curday=split("-",$days[$i]);
   $curday2=mktime(0,0,0,$curday[1],$curday[2],$curday[0]);
   $daych.=$curday[2].",";
   $moch.=$curday[1].",";
   $yearch.=$curday[0].",";
   $datestr.=date("M j",$curday2).", ";
}
if(!$moch || ereg_replace("[^0-9]","",$moch)=="") $moch="1,2,3,4,5,6,7,8,9,10,11,12,";
if(!$daych || ereg_replace("[^0-9]","",$daych)=="") $daych="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,";
$year0=date("Y")-1; $year1=date("Y")+1; $yearnow=date("Y");
if(ereg_replace("[^0-9]","",$yearch)=="") $yearch="$year0,$yearnow,$year1,";
$datestr=substr($datestr,0,strlen($datestr)-2);
$daych=substr($daych,0,strlen($daych)-1);
$moch=substr($moch,0,strlen($moch)-1);
$yearch=substr($yearch,0,strlen($yearch)-1);
$daych=Unique($daych);
$moch=Unique($moch);
$yearch=Unique($yearch);
$daych=split(",",$daych);
$moch=split(",",$moch);
$yearch=split(",",$yearch);
sort($daych); sort($moch); sort($yearch);

echo "<tr valign=top align=left><td><b>Dates:</b></td><td>";
if($edit==1)
{
if($sport=='ba')
{
   for($i=0;$i<count($bahostdates2);$i++)
   {
      echo "<input type=checkbox name=\"dates[$i]\" value=\"$bahostdates2[$i]\"";
      for($j=0;$j<count($days);$j++)
      {
         if($bahostdates2[$i]==$days[$j]) echo " checked";
      }
      echo "> $bahostdates[$i]<br>";
   }
}
else if($sport=='sb')
{
   for($i=0;$i<count($sbhostdates2);$i++)
   {     
      echo "<input type=checkbox name=\"dates[$i]\" value=\"$sbhostdates2[$i]\"";
      for($j=0;$j<count($days);$j++)
      {
         if($sbhostdates2[$i]==$days[$j]) echo " checked";
      }   
      echo "> $sbhostdates[$i]<br>";
   }
}
else if($sport=='pp')
{
   $date=split("-",$days[0]);
   echo "<select name=month><option value=''>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($date[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=day><option value=''>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($date[2]==$d) echo " selected";
      echO ">$d</option>";
   }
   echo "</select>/<select name=year><option value=''>YYYY</option>";
   echo "<option";
   $year0=date("Y"); $year1=$year0+1;
   if($date[0]==$year0) echo " selected";
   echo ">$year0</option><option";
   if($date[0]==$year1) echO " selected";
   echO ">$year1</option></select>";
}
else
   echo $datestr;
}//end if edit
else
   echo $datestr;
echo "</td></tr>";
if($sport=='sp' || $sport=='pp')
{
   if($edit==1)
      echo "<tr align=left><td><b>Starting Time:</b></td><td><input type=text class=tiny name=\"time\" size=10 value=\"$row[time]\"></td></tr>";
   else
      echo "<tr align=left><td><b>Starting Time:</b></td><td>$row[time]</td></tr>";
}
echo "<tr align=left><td><b>Schools:</b></td><td>";
if($row[schools]!='') echo $row[schools];
else echo "TBA";
echo "</td></tr>";
$sql="SHOW TABLES LIKE '$disttimes'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
echo "<tr align=center><td colspan=2><br>";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption align=left>";
if($sport=='sb' || $sport=='vb' || $sport=='fb' || $sport=='go_g' || $sport=='gog' || ereg("cc",$sport) || $sport=='te_b' || $sport=='teb' || $sport=="ubo")
{
   $central="CT"; $mtn="MT";
}
else
{
   $central="CT"; $mtn="MT";
}
if($edit==1)
{
   echo "<ul><li class=notbold><b>Please enter the date (MM/DD/YYYY) and time (HH:MM AM/PM $central/$mtn) for each contest in this tournament.</b>";
   echo "<li class=notbold>To \"reset\" a time slot, simply choose \"MM\" for the month or \"DD\" for the day for that entry and click \"Save\".";
   echo "<li class=notbold><font style=\"color:blue\">This district has <b>$teamcount</b> teams.  Thus, you must fill in <b>$gamecount</b> time slots below.</font>";
   echo "</ul>";
}
else
{
   echo "<b>Time Slots:</b>";
}
echo "</caption>";
if(ereg("bb",$sport)) $contest="Game";
else $contest="Match";
echo "<tr align=center><td><b>$contest</b></td><td><b>Day<br>(Ex: 11/01/2006)</b></td><td><b>Time<br>(Ex: 3:30 PM $central)</b></td>";
if($sport=='sb') echo "<td><b>Field</b></td>";
echo "</tr>";
/*
$sql="SELECT * FROM $disttimes WHERE distid='$distid'";
$result=mysql_query($sql);
$gamect=mysql_num_rows($result);
*/
//code by robin
if($sport=="ubo"){
    $sql = "SELECT * FROM $disttimes WHERE distid='$distid'";
    $result=mysql_query($sql);
    if (mysql_num_rows($result)==0){
        for($i=1;$i<=$gamecount;$i++){
            $sql1="INSERT INTO $disttimes(distid ,gamenum) VALUES ('$distid','$i')";
            mysql_query($sql1);
        }
    }
}
//end of code by robin
$year=date("Y"); $year0=$year-1; $year1=$year+1;
$sql="SELECT * FROM $disttimes WHERE distid='$distid' AND gamenum>0 ORDER BY ";
if($sport=='sb' || $sport=='vb' || $sport=="ubo") $sql.="gamenum";
else $sql.="day,time";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $curday=split("-",$row[day]);
   $curtime=split("[: ]",$row[time]); 
   echo "<input type=hidden name=\"disttimesid[$ix]\" value=\"$row[id]\">";
   $matchnum=$ix+1;
   echo "<tr align=center><td><b>$matchnum</b></td>";
   if($edit==1)
   {
      if($sport=='sb' || $sport=='vb' || $sport=="ubo")
         echo "<input type=hidden name=\"gamenum[$ix]\" value=\"$matchnum\">";
      echo "<td><select name=\"mo[$ix]\"";
      //if($sport=='vb' && $level!=1) echo " disabled";
      echo "><option value=''>MM</option>";
      for($i=0;$i<count($moch);$i++)
      {
         echo "<option";
         if($curday[1]==$moch[$i]) echo " selected";
         echo ">$moch[$i]</option>";
      }
      echo "</select>/<select name=\"day[$ix]\"";
      //if($sport=='vb' && $level!=1) echo " disabled";
      echo "><option value=''>DD</option>";
      for($i=0;$i<count($daych);$i++)
      {
         echo "<option";
         if($curday[2]==$daych[$i]) echo " selected";
         echo ">$daych[$i]</option>";
      }
      echo "</select>/<select name=\"year[$ix]\"";
      //if($sport=='vb' && $level!=1) echo " disabled";
      echo ">";
      for($i=0;$i<count($yearch);$i++)
      {
         echo "<option";
         if($curday[0]==$yearch[$i]) echo " selected";
         echo ">$yearch[$i]</option>";
      }
      echo "</select></td>";
      echo "<td><input type=text class=tiny size=3 maxlength=2 name=\"hour[$ix]\" value=\"$curtime[0]\">:";
      echo "<input type=text class=tiny size=3 maxlength=2 name=\"min[$ix]\" value=\"$curtime[1]\">";
      echo "<select name=\"ampm[$ix]\">";
      echo "<option";
      if($curtime[2]=="PM") echo " selected";
      echo ">PM</option><option";
      if($curtime[2]=="AM") echo " selected";
      echo ">AM</option></select>";
      echo "<select name=\"timezone[$ix]\">";
      echo "<option";
      if($curtime[3]=="$central") echo " selected";
      echo ">$central</option><option";
      if($curtime[3]=="$mtn") echo " selected";
      echo ">$mtn</option></select>";
      echo "</td>";
      if($sport=='sb')
      {
	 echo "<td><input type=text size=8 name=\"field[$ix]\" value=\"$row[sbfield]\"></td>";
      }
      echo "</tr>";
   }//end if edit
   else
   {
      echo "<td>$curday[1]/$curday[2]/$curday[0]</td>";
      echo "<td>$curtime[0]:$curtime[1] $curtime[2] $curtime[3]</td>";
      if($sport=='sb') echo "<td>$row[field]</td>";
      echo "</tr>";
   }
   $ix++;
}
$sql="SELECT * FROM $disttimes WHERE distid='$distid' AND gamenum='0'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $matchnum=$ix+1;
   echo "<input type=hidden name=\"disttimesid[$ix]\" value=\"$row[id]\">";
   echo "<tr align=center><td><b>$matchnum</b></td>";
   if($edit==1)
   {
      if($sport=='sb' || $sport=='vb' || $sport=="ubo")
         echo "<input type=hidden name=\"gamenum[$ix]\" value=\"$matchnum\">";
      echo "<td><select name=\"mo[$ix]\"><option value=''>MM</option>";
      for($i=0;$i<count($moch);$i++)
      {
         echo "<option>$moch[$i]</option>";
      }
      echo "</select>/<select name=\"day[$ix]\"><option value=''>DD</option>";
      for($i=0;$i<count($daych);$i++)
      {
         echo "<option>$daych[$i]</option>";
      }
      echo "</select>/<select name=\"year[$ix]\">";
      for($i=0;$i<count($yearch);$i++)
      {
         echo "<option>$yearch[$i]</option>";
      }
      echo "</select></td>";
      echo "<td><input type=text class=tiny size=3 maxlength=2 name=\"hour[$ix]\">:";
      echo "<input type=text class=tiny size=3 maxlength=2 name=\"min[$ix]\">";
      echo "<select name=\"ampm[$ix]\">";
      echo "<option>PM</option><option>AM</option></select>";
      echo "<select name=\"timezone[$ix]\">";
      echo "<option>$central</option><option>$mtn</option></select>";
      echo "</td></tr>";
   }
   else
   {
      echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
   }
   $ix++;
}
   echO "<input type=hidden name=timeslots value='1'>";
}//end if disttimes table exists
else
   echo "<input type=hidden name=timeslots value='0'>";
echo "<input type=hidden name=gamecount value=\"$gamecount\">";
echo "</table><br>";
if($edit==1) echo "<input type=submit name=submit value=\"Save\">";
echo "</td></tr>";
echo "<tr align=center><td colspan=2><br><a href=\"javascript:window.close()\" class=small>Close</a>";
echo "&nbsp;&nbsp;<a class=small href=\"hostcontract.php?session=$session&sport=$sport&distid=$distid\">View your Contract to Host</a></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
