<?php
//edit_so_g.php: Girls Soccer Entry form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=addslashes($school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="sog";
if(!IsHeadSchool($schoolid,$sport) && !GetCoopHeadSchool($schoolid,$sport) && $school!="Test's School") //NOT a $sport school at all
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><br><div class='alert' style='width:400px;'><b>$school</b> is not listed as a ".GetActivityName($sport)." school.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}
else if(!IsHeadSchool($schoolid,$sport) && $school!="Test's School")    //in a Co-op, not the head school
{
   echo $init_html;
   echo GetHeader($session);
   $mainsch=GetCoopHeadSchool($schoolid,$sport);
   echo "<br><br><br><div class='alert' style='width:400px'><b>$school</b> is in a co-op with <b>$mainsch</b> for ".GetActivityName($sport).".<br><br>Only the head school of the co-op can fill out this entry form.  <b>$mainsch</b> is listed as the head school for this co-op.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}

//get name of coach from logins table
$sql="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Soccer'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];
$asst_coaches=$row[1];

//get mascot and colors from headers table
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$colors=$row[5];
$mascot=$row[6];

//get due date from db
$sql="SELECT duedate FROM form_duedates WHERE form='so_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//Check if State Form should show up now (Dist Form is 10 days past due date)
if(PastDue($duedate,8))
{
   $form_type="STATE";
   $state=1;
   $table="so_gstate";
}
else	//district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="so_g";
}

$sql="SELECT record FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$record=explode("-",$row[0]);
$win=$record[0]; $loss=$record[1];

//If form has already been submitted, get info from db:
$sql="SELECT t1.* FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
//If first time editing state form, COPY DISTRICT TABLE TO STATE TABLE
if($state==1 && mysql_num_rows($result)==0)	
{
   //$sql2="INSERT INTO $db_name.so_gstate SELECT * FROM $db_name.so_g WHERE (school='$school2' OR co_op='$school2')";
   $sql2="SELECT * FROM $db_name.so_g WHERE (school='$school2' OR co_op='$school2')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $curcoop=addslashes($row2[co_op]);
      $curschool=addslashes($row2[school]);
      $sql3="INSERT INTO $db_name.so_gstate (nickname,student_id,checked,class_dist,record,jersey_lt,jersey_dk,position,goals,assists,gk_games,gk_goals_allowed,gk_saves,co_op,school) VALUES ('".addslashes($row2[nickname])."','$row2[student_id]','$row2[checked]','$row2[class_dist]','$row2[record]','$row2[jersey_lt]','$row2[jersey_dk]','$row2[position]','$row2[goals]','$row2[assists]','$row2[gk_games]','$row2[gk_goals_allowed]','$row2[gk_saves]','$curcoop','$curschool')";
      $result3=mysql_query($sql3);
   }
   $sql="SELECT t1.* FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.co_op='$school2' OR t1.school='$school2')"; //re-read state table
   $result=mysql_query($sql);
}
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[2];
   $cur_id[$ix]=$row[1];
   $cur_jersey_lt[$ix]=$row[5];
   $cur_jersey_dk[$ix]=$row[6];
   $cur_position[$ix]=$row[7];
   $cur_position[$ix]=split("[/]",$cur_position[$ix]);
   $cur_goals[$ix]=$row[8];
   $cur_assists[$ix]=$row[9];
   $cur_games[$ix]=$row[10];
   $cur_allowed[$ix]=$row[11];
   $cur_saves[$ix]=$row[12];
   $cur_nick[$ix]=$row[nickname];
   $ix++;
}
?>

<html>
<head>
   <title>NSAA Home</title>
   <link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<body>

<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase()!='TD' && element!=null)
   {
      element=document.all?element.parentElement:element.parentNode;
   }
   if(element)
   {
      element.bgColor="FFFF33";
   }
}
</script>

<?php
echo $init_html;
echo $header;
if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Soccer\">Return to Home-->Soccer Entry Forms</a><br>";
?>
<center>
<form method=post action="submit_so_g.php" enctype="multipart/form-data">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=state value=<?php echo $state; ?>>
<table style="width:100%;">
<tr align=center>
<th>GIRLS SOCCER <?php echo $form_type; ?> ENTRY</th>
</tr>
<?php
if($state!=1)
{
   echo "<tr align=center><td>";
   if(PastDue($duedate,0))
      echo "<div class='error' style='width:400px;text-align:left;'><p><b>Due $duedate2.</b></p><p>Please let your District Director know of any changes you make to this form, since the due date for this information has passed.</div>";
   else
      echo "<b>Due $duedate2</b>";
   echo "<br><br></td></tr>";
}//end if district
?>
<tr align=center>
<td>
   <table cellspacing=0 cellpadding=2 style="width:600px;">
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'sog');
$sql="SELECT * FROM sogschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$filename=$row[filename];
$class=$row['class'];
echo GetSchoolName($sid,'sog')." $mascot";
   ?>
   </td>
   </tr>
   <tr align=left>
   <th>Colors:</th><td><?php echo $colors; ?></td>
   </tr>
   <tr align=left>
   <th>NSAA-Certified Coach:</th><td><?php echo $coach; ?></td>
   </tr>
   <tr align=left>
   <th>Assistant Coaches:</th>
   <td><input type=text name=asst_coaches size=50 value="<?php echo $asst_coaches; ?>"></td>
   </tr>
   <tr align=left>
   <th>Class:</th>
   <td><?php echo $class; ?></td>
   </tr>
   <tr align=left>
   <th>Team Record:</th><td><input type=text name=wins value="<?php echo $win; ?>" size=2>&nbsp;wins <input type=text name=losses value="<?php echo $loss; ?>" size=2>&nbsp;losses</td>
   </tr>
   <tr align=left><th>Team Photo:</th><td>
   <?php if($filename!=''): ?>
   <p><a href="/nsaaforms/downloads/<?php echo $filename; ?>" target="_blank">Preview Team Photo</a></p>
   <?php endif; ?>
   <!--<iframe style="width:430px;height:175px;" src="simpleupload.php?sport=<?php echo $sport; ?>&session=<?php echo $session; ?>&sid=<?php echo $sid; ?>" frameborder='0'></iframe><p><i>Once
 your file has finished uploading, click "Save and Keep Editing" at the bottom of this page.</i></p></td></tr>-->
   <input type="file" name="imageUpload" id="imageUpload"></p>
   </td></tr>
<?php
if($level==1)
{
      $sql_id="SELECT * FROM headers WHERE school='$school2'";
      $result_id=mysql_query($sql_id);
      $row_id=mysql_fetch_array($result_id);
      
	  $sql_coop="SELECT * FROM sogschool WHERE mainsch='$row_id[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
      $result_coop=mysql_query($sql_coop);
      $row_coop=mysql_fetch_array($result_coop);
	  if (!empty($row_coop[mainsch])) $coop_info[]=$row_coop[mainsch];
	  if (!empty($row_coop[othersch1])) $coop_info[]=$row_coop[othersch1];
	  if (!empty($row_coop[othersch2])) $coop_info[]=$row_coop[othersch2];
	  if (!empty($row_coop[othersch3])) $coop_info[]=$row_coop[othersch3];
	  //echo '<pre>'; print_r($coop_info); 
	  //$enroll=0;
	  foreach ($coop_info as $info)
	  {
	  $sql_school="SELECT * FROM headers WHERE id='$info'";
      $result_school=mysql_query($sql_school);
      $row_school=mysql_fetch_array($result_school);
	  
	  $sql="SELECT name FROM logins WHERE school='$row_school[school]' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $super[] = $row[name];
	  
	  $sql="SELECT id,name FROM logins WHERE school='$row_school[school]' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $prin[] = $row[name];
	  
	  $sql="SELECT id,name FROM logins WHERE school='$row_school[school]' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $ad[]= $row[name];
	  
	  $sql="SELECT * FROM headers WHERE school='$row_school[school]' ";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $enroll=$enroll+$row[enrollment];
	  
	  }
	  
	  $super=implode(", ",$super);
	  $prin=implode(", ",$prin);
	  $ad=implode(", ",$ad);
	  //HISTORICAL INFO:
      $ix=0;
        //Superintendent
      $sql="SELECT id,name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  if (!empty($super))
      echo "<tr align=\"left\"><td><b>Superintendent:</b></td><td><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$super\" size=30></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>Superintendent:</b></td><td><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$row[name]\" size=30></td></tr>";
        //Principal
      $ix++;
      $sql="SELECT id,name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if (!empty($prin))
      echo "<tr align=\"left\"><td><b>Principal:</b></td><td><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$prin\" size=30></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>Principal:</b></td><td><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$row[name]\" size=30></td></tr>";
        //AD
      $ix++;
      $sql="SELECT id,name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if (!empty($ad))
      echo "<tr align=\"left\"><td><b>Athletic Director:</b></td><td><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$ad\" size=30></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>Athletic Director:</b></td><td><input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$row[name]\" size=30></td></tr>";
        //Enrollment
      $sql="SELECT * FROM headers WHERE id='$schoolid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $enrollment=$row[enrollment];
	  if (!empty($enroll))
      echo "<tr align=\"left\"><td><b>NSAA Enrollment:</b></td><td><input type=text name=\"enrollment\" value=\"$enroll\" size=5></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>NSAA Enrollment:</b></td><td><input type=text name=\"enrollment\" value=\"$enrollment\" size=5></td></tr>";
      $sql="SELECT * FROM ".GetSchoolsTable($sport)." WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Trips to State: 4
      echo "<tr align=\"left\"><td><b>Trips to State:</b></td><td><input type=text name=\"tripstostate\" size=10 value=\"$row[tripstostate]\"></td></tr>";
        //Most Recent: 2012
      echo "<tr align=\"left\"><td><b>Most Recent:</b></td><td><input type=text name=\"mostrecent\" size=10 value=\"$row[mostrecent]\"></td></tr>";
        //Championships: None
      echo "<tr align=\"left\"><td><b>Championships:</b></td><td><input type=text name=\"championships\" size=20 value=\"$row[championships]\"></td></tr>";
        //Runner-up: B/2008, B/2010
      echo "<tr align=\"left\"><td><b>Runner-up:</b></td><td><input type=text name=\"runnerup\" size=20 value=\"$row[runnerup]\"></tr>";
}
?>
<tr align=left>
<td colspan=2><p>Check the box next to the name of each student who will be participating in the <?php echo $form_type;
?> competition.  Required fields for the district tournament are marked with a *.  <b>All fields are required
for the state entry form.</b>  When your team qualifies for the state tournament, you must update this form wi
th complete statistics on each player by 10 a.m. the morning after your team has qualified.  Players may be su
bstituted on this team roster prior to district and between district and state at a school's discretion, provided no more than 22 players are in uniform.</p>
<?php if($counterror): ?>
<div class="error">
ERROR: You have checked too many players on your form. You may check a maximum of 24 students.
</div>
<?php endif; ?>
</td>
</tr>
</table></td></tr>
<tr align=center>
<td>
   <table cellspacing=0 cellpadding=2 frame=all rules=all style="border:#808080 1px solid;">
   <caption align=right><font size=2>Need help with positions?  <input type=button onClick="window.open('../positionshelp.php?session=<?php echo $session; ?>','positionshelp','height=300,width=500,location=no,menubar=no,resizable=no,scrollbars=yes,toolbar=no,titlebar=no,status=no,top=150,left=150');" value="Click Here">
   </caption>
   <tr align=center>
   <th rowspan=2 class="smaller" colspan=2>*Name</th>
   <th rowspan=2 class="smaller">Nickname</th>
   <th rowspan=2 class=smaller>*Light<br>Jersey<br>No.</th>
   <th rowspan=2 class=smaller>*Dark<br>Jersey<br>No.</th>
   <th rowspan=2 class=smaller>*Position</th>
   <th rowspan=2 class=smaller>Goals</th>
   <th rowspan=2 class=smaller>Assists</th>
   <th colspan=3 class=smaller>Goalkeeper Stats</th>
   </tr>
   <tr align=center>
   <th class=smaller>Games</th>
   <th class=smaller>Goals<br>Allowed</th>
   <th class=smaller>Saves</th>
   </tr>
<?php

   //get girls soccer participants from eligibility table in db
   $ix=0;
   $studs=explode("<result>",GetPlayers('sog',$school));
   $ix=0;
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      if($ix%10==0 && $ix>0)
      {
	 echo "<tr align=center>";
	 echo "<th rowspan=2 class=smaller colspan=2>*Name</th><th rowspan=2 class=\"smaller\">Nickname</th>";
	 echo "<th rowspan=2 class=smaller>*Light<br>Jersey<br>No.</th>";
	 echo "<th rowspan=2 class=smaller>*Dark<br>Jersey<br>No.</th>";
	 echo "<th rowspan=2 class=smaller>*Position</th>";
	 echo "<th rowspan=2 class=smaller>Goals</th>";
	 echo "<th rowspan=2 class=smaller>Assists</th>";
	 echo "<th colspan=3 class=smaller>Goalkeeper Stats</th></tr>";
	 echo "<tr align=center>";
	 echo "<th class=smaller>Games</th>";
	 echo "<th class=smaller>Goals<br>Allowed</th>";
	 echo "<th class=smaller>Saves</th></tr>";
      }
      //check if this student is already submitted:
      $submitted=0;
      for($i=0;$i<count($cur_id);$i++)
      {
	 if($cur_id[$i]==$stud[0]) 
	 {
	    $submitted=1;
	    $index=$i;
	 }
      } 
      echo "<tr align=center><td>";
      echo "<input onClick=\"Color(this)\" type=checkbox name=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($stud[3]!="y") echo " bgcolor=red";
      echo " align=left>$stud[1]</td><td><input type=text size=20 name=\"nickname[$ix]\" value=\"".$cur_nick[$index]."\"></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=jersey_lt[$ix]";
      if($submitted==1) echo " value=$cur_jersey_lt[$index]";
      echo ">";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=jersey_dk[$ix]";
      if($submitted==1) echo " value=$cur_jersey_dk[$index]";
      echo ">";
      echo "<td><select onChange=\"Color(this)\" name=\"position_array[$ix][]\" multiple size=2>";
      $posn_ix=0;
      for($i=0;$i<count($so_positions);$i++)
      {
	 echo "<option";
	 if($submitted==1)
	 {
	    if($so_positions[$i]==$cur_position[$index][$posn_ix])
	    {
	       echo " selected";
	       $posn_ix++;
	    }
	 }
	 echo ">$so_positions[$i]";
      }
      echo "</select>";
      echo "</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=goals[$ix]";
      if($submitted==1) echo " value=$cur_goals[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=assists[$ix]";
      if($submitted==1) echo " value=$cur_assists[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=games[$ix]";
      if($submitted==1) echo " value=$cur_games[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=allowed[$ix]";
      if($submitted==1) echo " value=$cur_allowed[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=1 name=saves[$ix]";
      if($submitted==1) echo " value=$cur_saves[$index]";
      echo "></td>";
      echo "<input type=hidden name=id[$ix] value=$stud[0]><input type=hidden name=\"studsch[$ix]\" value=\"$stud[2]\"></td></tr>";
      $ix++;
   }
   echo "</table>";

?>

</td>
</tr>
<tr align=left>
<td><br>
<?php $form_type=strtolower($form_type); ?>
<font style="color:red">Students listed in red are currently <b>ineligible</b>.  Please make sure they will be eligible for the <?php echo $form_type; ?> tournament before submitting them on this form.</font></p></td>
</tr>
<?php
if($state==1)	//have checkbox for final submission
{
?>
<tr align=center>
<td>
<div class=alert style="width:500px;font-size:10pt;">
<table class=nine><tr align=left><td><b>
<input type=checkbox name="send" value="y">
This form is complete and ready for the NSAA to use in the State Tournament Program.  This tells us that all your information is complete and we can begin printing it.<br></b><br><br>
(Check this box if you want to submit the above information as your final state entry.  You have not officially submitted your entry until you have checked this box and clicked one of the two "Save" buttons below!)
</td></tr></table>
</div>
</td>
</tr>
<?php
} //end if state
?>
<tr align=left>
<td><br><font size=3><i><?php echo $certify; ?></i></font><br><br></td>
</tr>
<tr align=center>
<td><input type=submit name=submit value="Save and Keep Editing">
    <input type=submit name=submit value="Save and View Form">
    <input type=submit name=submit value="Cancel">
</td>
</tr>
</table>
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
