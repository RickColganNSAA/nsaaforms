<?php
/******************************************
judgesadmin.php
created 01/11/07
manage list of judges (DB table: mujudges)
*******************************************/
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   //header("Location:../index.php");
   echo $session;
   exit();
}

if($delete && $judgeid)
{
   $sql="SELECT first,last FROM mujudges WHERE id='$judgeid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $name="$row[first] $row[last]";
   $sql="DELETE FROM mujudges WHERE id='$judgeid'";
   $result=mysql_query($sql);
   $message="<font style=\"color:blue\"><b>The record for $name has been deleted.</b></font>";
   $judgeid=0;
} 

if($save || $addnew || $addsite)
{
   $last=addslashes($last); $first=addslashes($first);
   $address1=addslashes($address1); $address2=addslashes($address2);
   $cityst=addslashes($cityst);

   if($save || $addsite)	//current judge edited and saved
   {
      $update="$year-$month-$day";
      $sql="UPDATE mujudges SET last='$last',first='$first',notreg='$notreg',address1='$address1',address2='$address2',cityst='$cityst',zip='$zip',email='$email',homeph='$homeph',workph='$workph',cellph='$cellph',vocal='$vocal',piano='$piano',orchestra='$orchestra',instrumental='$instrumental',brass='$brass',woodwind='$woodwind',percussion='$percussion',teacher='$teacher',yearsteach='$yearsteach',yearsjudge='$yearsjudge',lastupdate='$update' WHERE id='$judgeid'";
      $result=mysql_query($sql);
      $message="<font style=\"color:blue\"><b>The changes to $first $last's record have been saved.</b></font>";
   }
   else if($addnew)	//new judge added
   {
      $update="$year-$month-$day";
      $sql="INSERT INTO mujudges (lastupdate,last,first,notreg,address1,address2,cityst,zip,email,homeph,workph,cellph,vocal,piano,orchestra,instrumental,brass,woodwind,percussion,teacher,yearsteach,yearsjudge) VALUES ('$update','$last','$first','$notreg','$address1','$address2','$cityst','$zip','$email','$homeph','$workph','$cellph','$vocal','$piano','$orchestra','$instrumental','$brass','$woodwind','$percussion','$teacher','$yearsteach','$yearsjudge')";
      $result=mysql_query($sql);
      $message="<font style=\"color:blue\"><b>$first $last has been added to the list of NSAA Music Judges.</v></font>";
      $judgeid=0;
   }

   if($addsite && $sitesite!='' && $sitesite!="Site")	//ADD SITE RECORD TO mujudgesites
   {
      $sql="INSERT INTO mujudgesites (mujudgeid,year,site) VALUES ('$judgeid','$siteyear','".addslashes($sitesite)."')";
      $result=mysql_query($sql);
      header("Location:judgesadmin.php?judgeid=$judgeid&session=$session#sites");
   }
}

echo $init_html;
echo $header;

echo "<br><a class=small href=\"muadmin.php?session=$session\">Music Admin Home</a>&nbsp;&nbsp;&nbsp;<a name=\"top\" target=\"_blank\" href=\"mujudges.php?session=$session\" class=small>Preview NSAA Music Judges List</a>&nbsp;&nbsp;&nbsp;";
echO "<a class=small href=\"mujudges.php?session=$session&export=1\" target=\"_blank\">Export NSAA Music Judges List</a><br>";
echo "<br><form method=post action=\"judgesadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width='700px' class=nine cellspacing=1 cellpadding=5>";
echo "<caption><b>2008-2009 NSAA Music Judges:</b><br>";
echo "<select name=\"judgeid\" onchange=\"submit();\"><option value=\"0\">Please Select a Judge</option>";
$sql="SELECT id,last,first FROM mujudges ORDER BY last,first";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($judgeid==$row[id]) echo " selected";
   echo ">$row[last], $row[first]</option>";
}
echo "</select> OR <a href=\"judgesadmin.php?session=$session&add=1\">Add a New Judge</a>";
if($message)
   echo "<br><br>$message";
echo "</caption>";
if($judgeid && $add!=1)
{
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   $sql="SELECT * FROM mujudges WHERE id='$judgeid' ORDER BY last,first";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td><b>Last, First:</b></td>";
   echo "<td><input type=text class=tiny name=\"last\" value=\"$row[last]\" size=15>, <input type=text class=tiny name=\"first\" value=\"$row[first]\" size=12></td></tr>";
   echo "<tr align=left><td></td><td><input type=checkbox name=\"notreg\" value=\"x\"";
   if($row[notreg]=='x') echo " checked";
   echo "> <b>NOT REGISTERED</b></td></tr>";
   echo "<tr valign=top align=left><td><b>Address:</b></td>";
   echo "<td><input type=text class=tiny name=\"address1\" value=\"$row[address1]\" size=25><br>";
   echo "<input type=text class=tiny name=\"address2\" value=\"$row[address2]\" size=25></td></tr>";
   echo "<tr align=left><td><b>City, ST Zip:</b></td>";
   echo "<td><input type=text class=tiny name=\"cityst\" value=\"$row[cityst]\" size=15>&nbsp;&nbsp;";
   echo "<input type=text class=tiny name=\"zip\" value=\"$row[zip]\" size=5></td></tr>";
   echo "<tr align=left><td><b>E-mail:</b></td>";
   echo "<td><input type=text class=tiny name=\"email\" value=\"$row[email]\" size=40></td></tr>";
   echo "<tr valign=top align=left><td><b>Phone:</b></td>";
   echo "<td width=125>(H)<input type=text class=tiny name=\"homeph\" value=\"$row[homeph]\" size=13><br>";
   echo "(B)<input type=text class=tiny name=\"workph\" value=\"$row[workph]\" size=13><br>";
   echo "(C)<input type=text class=tiny name=\"cellph\" value=\"$row[cellph]\" size=13</td></tr>";
   echo "<tr valign=top align=left><td><b>Judging Preferences:</td>";
   echo "<td><table><tr align=left valign=top><td><input type=checkbox name=\"vocal\" value='x'";
   if($row[vocal]=='x') echo " checked";
   echo ">&nbsp;Vocal<br><input type=checkbox name=\"piano\" value='x'";
   if($row[piano]=='x') echo " checked";
   echo ">&nbsp;Piano<br><input type=checkbox name=\"orchestra\" value='x'";
   if($row[orchestra]=='x') echo " checked";
   echo ">&nbsp;Orchestra<br><input type=checkbox name=\"instrumental\" value='x'";
   if($row[instrumental]=='x') echo " checked";
   echo ">&nbsp;Instrumental</td><td><input type=checkbox name=\"brass\" value='x'";
   if($row[brass]=='x') echo " checked";
   echo ">&nbsp;Brass<br><input type=checkbox name=\"woodwind\" value='x'";
   if($row[woodwind]=='x') echo " checked";
   echo ">&nbsp;Woodwind<br><input type=checkbox name=\"percussion\" value='x'";
   if($row[percussion]=='x') echo " checked";
   echo ">&nbsp;Percussion</td></tr></table></td></tr>";
   echo "<tr align=left><td><b>Currently Teaching:</b></td>";
   echo "<td><input type=text class=tiny size=3 name=\"teacher\" value=\"$row[teacher]\"></td></tr>";
   echo "<tr align=left><td><b>Years Teaching:</b></td>";
   echo "<td><input type=text class=tiny size=2 name=\"yearsteach\" value=\"$row[yearsteach]\"></td></tr>";
   echo "<tr align=left><td><b>Years Judging:</b></td>";
   echo "<td><input type=text class=tiny size=2 name=\"yearsjudge\" value=\"$row[yearsjudge]\"></td></tr>";
   //SITES JUDGED & YEAR (mujudgesites table)
   echo "<tr align=left valign=top><td><b>NSAA Sites Judged & Year:</b></td><td>";
   echo "<iframe src=\"judgesites.php?session=$session&mujudgeid=$row[id]\" width='325px' height='150px'></iframe><a name='sites'><br></a>";
   echo "Add a Site: <select name=\"siteyear\">";
   $year0=date("Y")-5; $year=date("Y");
   for($y=$year0;$y<=$year;$y++)
   {
      echo "<option value=\"$y\"";
      if($year==$y) echo " selected";
      echo ">$y</option>";
   }
   echo "</select> <input type=text name=\"sitesite\" size=30 value=\"Site\" onFocus=\"if(this.value=='Site') { this.value=''; }\">&nbsp;";
   echo "<input type=submit name=\"addsite\" value=\"Add Site\"></td></tr>";
   echo "<tr align=left><td><b>NSAA-Registered Judge - Last Updated:</b></td>";
   echo "<td>";
   $date=split("-",$row[lastupdate]);
   echo "<select name=month><option value='00'>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option"; 
      if($date[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select> / <select name=day><option value='00'>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($date[2]==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select> / <select name=year><option value='0000'>YYYY</option>";
   $year1=date("Y")-1;
   $year2=$year1+1;
   echo "<option";
   if($date[0]==$year1) echo " selected";
   echo ">$year1</option><option";
   if($date[0]==$year2) echo " selected";
   echo ">$year2</option></select></td></tr>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   echo "<tr><td align=left><input type=submit name=\"delete\" value=\"Delete Judge\" onclick=\"return confirm('Are you sure you want to delete the record for $row[first] $row[last]?');\"></td><td align=right><input type=submit name=\"save\" value=\"Save\"></td></tr>";
}
else if($add==1)
{
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   echo "<tr align=center><td colspan=2><font style=\"color:blue\"><b>Please enter the information for the new judge:</b></td></tr>";
   echo "<tr align=left><td><b>Last, First:</b></td>";
   echo "<td><input type=text class=tiny name=\"last\" size=15>, <input type=text class=tiny name=\"first\" size=12></td></tr>";
   echo "<tr align=left><td></td><td><input type=checkbox name=\"notreg\" value=\"x\"><b>NOT REGISTERED</b></td></tr>";
   echo "<tr valign=top align=left><td><b>Address:</b></td>";
   echo "<td><input type=text class=tiny name=\"address1\" size=15><br>";
   echo "<input type=text class=tiny name=\"address2\" size=15></td></tr>";
   echo "<tr align=left><td><b>City, ST Zip:</b></td>";
   echo "<td><input type=text class=tiny name=\"cityst\" size=20>&nbsp;&nbsp;";
   echo "<input type=text class=tiny name=\"zip\" size=6></td></tr>";
   echo "<tr align=left><td><b>E-mail:</b></td>";
   echo "<td><input type=text class=tiny name=\"email\" size=25></td></tr>";
   echo "<tr valign=top align=left><td><b>Phone:</b></td>";
   echo "<td width=125>(H)<input type=text class=tiny name=\"homeph\" size=13><br>";
   echo "(B)<input type=text class=tiny name=\"workph\" size=13><br>";
   echo "(C)<input type=text class=tiny name=\"cellph\" size=13</td></tr>";
   echo "<tr valign=top align=left><td><b>Judging Preferences:</td>";
   echo "<td><table><tr align=left valign=top><td><input type=checkbox name=\"vocal\" value='x'";
   echo ">&nbsp;Vocal<br><input type=checkbox name=\"piano\" value='x'";
   echo ">&nbsp;Piano<br><input type=checkbox name=\"orchestra\" value='x'";
   echo ">&nbsp;Orchestra<br><input type=checkbox name=\"instrumental\" value='x'";
   echo ">&nbsp;Instrumental</td><td><input type=checkbox name=\"brass\" value='x'";
   echo ">&nbsp;Brass<br><input type=checkbox name=\"woodwind\" value='x'";
   echo ">&nbsp;Woodwind<br><input type=checkbox name=\"percussion\" value='x'";
   echo ">&nbsp;Percussion</td></tr></table></td></tr>";
   echo "<tr align=left><td><b>Currently Teaching:</b></td>";
   echo "<td><input type=text class=tiny size=3 name=\"teacher\"></td></tr>";
   echo "<tr align=left><td><b>Years Teaching:</b></td>";
   echo "<td><input type=text class=tiny size=2 name=\"yearsteach\"></td></tr>";
   echo "<tr align=left><td><b>Years Judging:</b></td>";
   echo "<td><input type=text class=tiny size=2 name=\"yearsjudge\"></td></tr>";
   echo "<tr align=left><td><b>NSAA-Registered Judge - Last Updated:</b></td>";
   echo "<td>";
   echo "<select name=month><option value='00'>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      echo ">$m</option>";
   }
   echo "</select> / <select name=day><option value='00'>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      echo ">$d</option>";
   }
   echo "</select> / <select name=year><option value='0000'>YYYY</option>";
   $year1=date("Y")-1;
   $year2=$year1+1;
   echo "<option";
   echo ">$year1</option><option";
   echo ">$year2</option></select></td></tr>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   echo "<tr align=right><td colspan=2><input type=submit name=\"addnew\" value=\"Add Judge\"></td></tr>";
}
echo "</table></form>";

echo $end_html;
?>
