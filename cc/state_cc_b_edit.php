<?php
//state_cc_b_edit.php: district hosts submit their individual and team state qualifiers

require '../variables.php';
require '../functions.php';

$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get school of user
if($school_ch && GetLevel($session)==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

//check that user is a district host
if(!$dist_select)
{
$sql="SELECT * FROM $db_name2.ccbdistricts WHERE hostschool='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$dist=$row[0];
}
else
{
   $sql="SELECT * FROM $db_name2.ccbdistricts WHERE id='$dist_select'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $dist=$row[0];
}
if((!$dist_select && mysql_num_rows($result)==0) || !ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

if($store || $hiddensave)
{
   //submit information to database
   //Individuals:
   $sql="DELETE FROM cc_b_state_indy WHERE district_id='$dist'";
   $result=mysql_query($sql);
   for($i=0;$i<15;$i++)
   {
      $schvar="sch".$i; $indyvar="indy".$i;
      $minvar="min".$i; $secvar="sec".$i; $tenthvar="tenth".$i;
      $time=$$minvar.":".$$secvar.".".$$tenthvar;
      if($$schvar && $$indyvar)
      {
	 $place=$i+1; 
         $sql="INSERT INTO cc_b_state_indy (district_id, sid, student_id, place,finishtime) VALUES ('$dist','".$$schvar."','".$$indyvar."','$place','".$time."')";
	 $result=mysql_query($sql);
      }
   }
   //Qualifying Teams:
   $sql="DELETE FROM cc_b_state_team WHERE district_id='$dist'";
   $result=mysql_query($sql);
   for($i=1;$i<=3;$i++)
   {
      $students=""; $times="";
      for($j=0;$j<count($student[$i]);$j++)
      {
	 if($check[$i][$j]=='y')
	 {
	    $temp=$student[$i][$j];
	    $students.="$temp,";
            $curmin=$min[$i][$j]; $cursec=$sec[$i][$j]; $curtenth=$tenth[$i][$j];
   	    $curtime=$curmin.":".$cursec.".".$curtenth;
	    if($curtime!=":.") $times.=$curtime.",";
	 }
      }
      $students=substr($students,0,strlen($students)-1);
      $times=substr($times,0,strlen($times)-1);
      $sql="INSERT INTO cc_b_state_team (district_id, sid, student_ids, place, score,finishtimes) VALUES ('$dist','$team[$i]','$students','$i','$score[$i]','$times')";
      $result=mysql_query($sql);
   }
   for($i=4;$i<=15;$i++)
   { 
      $sql="INSERT INTO cc_b_state_team (district_id, sid, place, score) VALUES ('$dist','$team[$i]','$i','$score[$i]')";
      $result=mysql_query($sql);
   }
   if($store=="Save & View Form" || $final=='y')
   {
      header("Location:state_cc_b_view.php?session=$session&school_ch=$school_ch&final=$final&dist_select=$dist");
      exit();
   }
}
echo $init_html_ajax;
?>  
<script type="text/javascript" src="/javascript/Team2.js"></script>
<script language="javascript">
function Color(element)
{
   element = document.all ? Utilities.getElement(element).parentElement : Utilities.getElement(element).parentNode;
   if(element)
   {
      element.bgColor=yellow;
   }
}
</script>
</head>
<body onload="Team2.initialize('<?php echo $session; ?>','ccb','sch','indy','15');">
<?php
echo GetHeader($session);

//get district info
$sql="SELECT * FROM $db_name2.ccbdistricts WHERE id='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoollist=$row[schools];
echo "<br><table width=95%><!--Table of Tables-->";
echo "<caption><b>BOYS CROSS-COUNTRY DISTRICT RESULTS</b><br>";
echo "<a class=small href=\"state_cc_g_edit.php?session=$session\">Girls Cross-Country District Results</a></caption>";
echo "<tr align=left><td colspan=3>";
echo "Please report only qualifiers for the State Cross-Country Meet.  Choose the school and then choose from the list of students for that school.  <b>Class A</b> teams may enter <b>7</b> team members, but <b>Classes B, C, and D</b> may enter only <b>6</b>.  <i><b>Please report the top 15 individuals whether or not they qualified on a team</b>.</i></td></tr>";
echo "<tr align=center>";
echo "<td colspan=3><table>";
echo "<tr align=left><th>Class/District:</th><td>$row[class]-$row[district]</td>";
echo "<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location:</th><td>$row[site]</td>";
echo "<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:</th><td>";
$curclass=$row['class'];
$temp=split("-",$row[dates]);
echo "$temp[1]/$temp[2]/$temp[0]</td></tr>";
echo "</table><hr></td></tr>";

echo "<tr align=center><td colspan=3>";
//echo "<div class=\"searchresult\" name=\"debug\" id=\"debug\">DEBUG</div>";
echo "<form method=post action=\"state_cc_b_edit.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=dist_select value=$dist>";
echo "<table style=\"border:#333333 1px solid;\" frame=all rules=all cellspacing=0 cellpadding=4>";
echo "<caption><b>Individuals:</b></caption>";
echo "<a name=indy href=\"#indy\"></a>";
echo "<tr align=center><th>Place</th><th>School</th><th>Name (Grade)</th><th>Finish Time</th></tr>";

$schnames=split(",",$schoollist);
for($i=0;$i<count($schnames);$i++)
{
   $cur=addslashes(trim($schnames[$i]));
   $sql="SELECT * FROM ccbschool WHERE school='$cur' ORDER BY school";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0)
   {
      $cc_sch[$i]=$row[school]; $cc_sid[$i]=$row[sid];
   }
   else echo "ERROR: $cur not found<br>";
}

//get info already submitted
$sql="SELECT * FROM cc_b_state_indy WHERE district_id='$dist' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $schvar="sch".$ix;
   $indyvar="indy".$ix;
   $minvar="min".$ix; $secvar="sec".$ix; $tenthvar="tenth".$ix;
   if(!$$schvar)
      $$schvar=$row[sid];
   if(!$$indyvar)
      $$indyvar=$row[student_id];
   if(!$$minvar && !$$secvar) 
   {
      $time=split("[:.]",$row[finishtime]);
      $$minvar=$time[0]; $$secvar=$time[1]; $$tenthvar=$time[2];
   }
   $ix++;
}

//show 15 spots for individual finishers
for($i=0;$i<15;$i++)
{
   $schools=""; $co_op=0;
   $place=$i+1;
   echo "<tr align=center><th>$place</th>";
   $var="sch".$i;
   echo "<td><select class=small name=\"$var\" id=\"$var\" onMouseDown=\"Team2.currentPlace=$i;\">";
   echo "<option value='0'>Choose School</option>";
   for($j=0;$j<count($cc_sid);$j++)
   {
      echo "<option value=\"$cc_sid[$j]\"";
      if($$var==$cc_sid[$j]) echo " selected";
      echo ">$cc_sch[$j]</option>";
   }
   echo "</select></td>";
   $var2="indy".$i;
   echo "<td align=left><select class=small name=\"$var2\" id=\"$var2\">";
   echo "<option value='0'>Choose Student</option>";
   //get students on district roster for selected school
   if($$var)
   {
      $sql="SELECT * FROM ccbschool WHERE sid='".$$var."'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sql2="SELECT t1.* FROM eligibility AS t1, headers AS t2 WHERE t1.school=t2.school AND (t2.id='$row[mainsch]' ";
      if($row[othersch1]!=0) $sql2.="OR t2.id='$row[othersch1]' ";
      if($row[othersch2]!=0) $sql2.="OR t2.id='$row[othersch2]' ";
      if($row[othersch3]!=0) $sql2.="OR t2.id='$row[othersch3]' ";
      $sql2=substr($sql2,0,strlen($sql2)-1).") AND t1.cc='x' AND t1.gender='M' ORDER BY t1.school,t1.last,t1.first,t1.middle";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($$var2==$row2[id]) echo " selected";
         $year=GetYear($row2[semesters]);
         echo ">$row2[last], $row2[first] $row2[middle] ($year)</option>";
      }
   }
   echo "</select></td>";
   $minvar="min".$i; $secvar="sec".$i; $tenthvar="tenth".$i;
   echo "<td><input type=text size=3 maxlength=2 name=\"min".$i."\" id=\"min".$i."\" value=\"".$$minvar."\">&nbsp;:&nbsp;";
   echo "<input type=text size=3 maxlength=2 name=\"sec".$i."\" id=\"sec".$i."\" value=\"".$$secvar."\">&nbsp;.&nbsp;";
   echo "<input type=text size=3 maxlength=2 name=\"tenth".$i."\" id=\"tenth".$i."\" value=\"".$$tenthvar."\"></td>";
   echo "</tr>";
}
echo "</table></td></tr>";

//Team results:
echo "<tr align=left><td colspan=3><hr>";
echo "<font size=2><b>Teams:</b></font><br>";
echo "(1)&nbsp;Please choose the top three teams from your district from the appropriate drop-down boxes.";
echo "<br>(2)&nbsp;Then check the box next to the name of each student from those teams that will be competing at the state meet.<br>NOTE: <b>Class A</b> teams may have <b>7</b> participants, while <b>Class B, C, and D</b> teams may only have <b>6</b> participants.<br><br></td></tr>";
echo "<tr align=left valign=top>";

//get submitted teams from db
$sql="SELECT * FROM cc_b_state_team WHERE district_id='$dist' AND student_ids IS NOT NULL ORDER BY place";
$result=mysql_query($sql);
$ix=1;
while($row=mysql_fetch_array($result))
{
   if(!$team[$ix] || $team[$ix]=="0")
   {
      $team[$ix]=$row[2];
   $checks[$ix]=split(",",$row[3]);
   $times[$ix]=split(",",$row[finishtimes]);
   }
   $ix++;
}
echo "<input type=hidden name=\"hiddensave\" value=\"0\">";
for($x=1;$x<=3;$x++)
{
echo "<td>";
if($x==1) $place="1st";
else if($x==2) $place="2nd";
else $place="3rd";
echo "<a name=\"team$x\" href=\"#team$x\"></a>";
echo "<font size=2><b>$place-Place Team:</b></font><br>";
echo "<select class=small name=\"team[$x]\" onchange=\"this.form.action+='#team$x';hiddensave.value='1';submit();\">";
echo "<option value='0'>Choose School</option>";
for($i=0;$i<count($cc_sch);$i++)
{
   echo "<option value=\"$cc_sid[$i]\"";
   if($team[$x]==$cc_sid[$i]) echo " selected";
   echo ">$cc_sch[$i]";
}
echo "</select><br><br>"; 
echo "<table frames=all rules=all style=\"border:#333333 1px solid;\" cellspacing=0 cellpadding=4>";
echo "<tr align=center><th colspan=2>Team Members</th><th>Grade</th><th>Finish Time</th></tr>";
//get runners from this school
$sql2="SELECT * FROM ccbschool WHERE sid='$team[$x]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$sql="SELECT t2.id,t2.last,t2.first,t2.middle,t2.semesters FROM eligibility AS t2, headers AS t3 WHERE t2.school=t3.school AND t2.cc='x' AND t2.gender='M' AND (t3.id='$row2[mainsch]'";
if($row2[othersch1]) $sql.=" OR t3.id='$row2[othersch1]'";
if($row2[othersch2]) $sql.=" OR t3.id='$row2[othersch2]'";
if($row2[othersch3]) $sql.=" OR t3.id='$row2[othersch3]'";
$sql.=") ORDER BY t2.school,t2.last,t2.first";
$result=mysql_query($sql);
$ix=0; $teamcount=0;
while($row=mysql_fetch_array($result))
{
   $submitted=0;
   for($j=0;$j<count($checks[$x]);$j++)
   {
      if($checks[$x][$j]==$row[0])	//student was checked in db
      {
	 $submitted=1;
	 $curtime=$times[$x][$j];
	 $j=count($checks[$x]);
      }
   }
   if($submitted==0)    //Check if this student was entered in the top 15 individuals.
   {
      $sql2="SELECT * FROM cc_b_state_indy WHERE student_id='$row[id]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0) 
      {
	 $row2=mysql_fetch_array($result2);
	 $submitted=1; $curtime=$row2[finishtime];
      }
   }
   echo "<tr align=center>";
   echo "<td><input type=checkbox name=\"check[$x][$ix]\" value='y' onclick=\"Utilities.getElement('min".$x.$ix."').style.backgroundColor='yellow';\"";
   if($submitted==1 || $check[$x][$ix]=='y') 
   {
      echo " checked"; $teamcount++;
   }
   echo "></td>";
   $year=GetYear($row[4]);
   echo "<input type=hidden name=\"student[$x][$ix]\" value=$row[0]>";
   echo "<td align=left>$row[1], $row[2] $row[3]</td><td>$year</td>";
   if($submitted==1)
      echo "<td bgcolor=yellow>";
   else echo "<td>";
   $time=split("[:.]",$curtime);
   if(!$min[$x][$ix]) $min[$x][$ix]=$time[0];
   if(!$sec[$x][$ix]) $sec[$x][$ix]=$time[1];
   if(!$tenth[$x][$ix]) $tenth[$x][$ix]=$time[2];
   if($min[$x][$ix]!='' && $sec[$x][$ix]!='') // && $time[2]!='')
      $timecount++;
   unset($curtime);
   echo "<input type=text size=3 maxlength=2 name=\"min[$x][$ix]\" id=\"min".$x.$ix."\" value=\"".$min[$x][$ix]."\">:";
   echo "<input type=text size=3 maxlength=2 name=\"sec[$x][$ix]\" value=\"".$sec[$x][$ix]."\">.";
   echo "<input type=text size=3 maxlength=2 id=\"tenth".$x.$ix."\" name=\"tenth[$x][$ix]\" value=\"".$tenth[$x][$ix]."\"></td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";
if(($curclass=="A" && $teamcount>7) || ($curclass!="A" && $teamcount>6))
{
   echo "<br><div class=error style=\"width:300px;\">ERROR: You have checked too many students for this team.";
   if($curclass=="A") echo "<br>You may check a maximum of 7 students.</div>";
   else echo "<br>You may check a maximum of 6 students.</div>";
}
if($teamcount>$timecount)
{
   echo "<br><div class=error style=\"width:300px;\">ERROR: You have not entered finishing times for all of the students on this team.</div>";
}
echo "</td>";
}//end for loop
echo "</tr>";
//Team Score Table
echo "<tr align=center><td colspan=3><hr>";
echo "<table frame=all rules=all style=\"border:#333333 1px solid;\" cellspacing=0 cellpadding=4>";
echo "<caption><b>TEAM SCORE:</b></caption>";
echo "<tr align=center><th>Place</th><th>School</th><th>Points</th></tr>";

//get submitted scores from db
$sql="SELECT * FROM cc_b_state_team WHERE district_id='$dist' ORDER BY place";
$result=mysql_query($sql);
$ix=1;
while($row=mysql_fetch_array($result))
{
   $points[$ix]=$row[5];
   if($ix>3 && (!$team[$ix] || $team[$ix]=="0")) 
      $team[$ix]=$row[sid];
   $ix++;
}
for($i=1;$i<=3;$i++)
{
   echo "<tr align=center>";
   echo "<th>$i</th>";
   $sql2="SELECT school FROM ccbschool WHERE sid='$team[$i]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td align=left>$row2[school]</td>";
   echo "<td><input type=text name=\"score[$i]\" size=4 value=\"$points[$i]\"></td>";
   echo "</tr>";
}
for($i=4;$i<=15;$i++)
{
   echo "<tr align=center>";
   echo "<th>$i</th>";
   echo "<td><select class=small name=\"team[$i]\">";
   echo "<option value='0'>Choose School</option>";
   for($j=0;$j<count($cc_sch);$j++)
   {
      echo "<option value=\"$cc_sid[$j]\"";
      if($team[$i]==$cc_sid[$j]) echo " selected";
      echo ">$cc_sch[$j]";
   }
   echo "</select></td>";
   echo "<td><input type=text name=\"score[$i]\" size=4 value=\"$points[$i]\"></td>";
   echo "</tr>";
}
echo "</table></td></tr>";
echo "<tr align=center><th colspan=3><br><table width=80%><tr align=left><th>";
echo "<input type=checkbox name=final value=y>";
echo "Check this box when you have completed the above information and wish to make this your final submission of state qualifiers.  Then click one of the \"Save\" buttons below.<br></th></tr></table></th></tr>";
echo "<tr align=center><td colspan=3>";
echo "<input type=submit name=store value=\"Save & Keep Editing\">&nbsp;";
echo "<input type=submit name=store value=\"Save & View Form\">";
echo "</td></tr>";
echo "</form>";
echo "</table><!--End Table of Tables-->";
?>
<div id="loading" style=\"display:none;\"></div>
<?php
echo $end_html;
?>
