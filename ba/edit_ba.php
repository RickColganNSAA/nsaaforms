<?php
//edit_ba.php: Baseball entry form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';
//check if user needs to be re-directed:
if($submit=="Home")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}

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
$school2=ereg_replace("\'","\'",$school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="ba";
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

//get class/dist choices
$sql="SELECT DISTINCT class FROM $db_name2.badistricts WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
$ix=0; $class_array=array();
while($row=mysql_fetch_array($result))
{
   $class_array[$ix]=$row[0]; $ix++;
}

//get name of coach from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Baseball'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];

//get mascot and colors from headers table
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$colors=$row[5];
$mascot=$row[6];

//get due date from db
$sql="SELECT duedate FROM form_duedates WHERE form='ba'";
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
   $table="ba_state";
}
else    //district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="ba";
}

//get team record & class from baseball table
$sql="SELECT team_record,class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$record=$row[0];
$record=split("-",$record);
$win=$record[0];
$loss=$record[1];
$class_dist=$row[1];

$sid=GetSID2($school,'ba');

//GET TEAM PHOTO
$sql="SELECT filename FROM baschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)>0 && citgf_file_exists("../downloads/".$row[filename]))
{
   $filename=$row[filename];
}
else
{
   $filename="";
}

//If form has already been submitted, get info from db:
$sql="SELECT * FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
//If first time editing state form, COPY DISTRICT TABLE TO STATE TABLE
if($state==1 && mysql_num_rows($result)==0)
{
   //$sql2="INSERT INTO $db_name.ba_state SELECT * FROM $db_name.ba WHERE (school='$school2' OR co_op='$school2')";
   $sql2="SELECT * FROM $db_name.ba WHERE (school='$school2' OR co_op='$school2')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $curschool=addslashes($row2[school]); $curcoop=addslashes($row2[co_op]);
      $sql3="INSERT INTO $db_name.ba_state (student_id,nickname,school,checked,team_record,jersey_lt,jersey_dk,position,average,at_bats,hits,runs_scored,runs_batted,home_runs,pitching_record,pitching_era,class_dist,co_op) VALUES ('$row2[student_id]','".addslashes($row2[nickname])."','$curschool','$row2[checked]','$row2[team_record]','$row2[jersey_lt]','$row2[jersey_dk]','$row2[position]','$row2[average]','$row2[at_bats]','$row2[hits]','$row2[runs_scored]','$row2[runs_batted]','$row2[home_runs]','$row2[pitching_record]','$row2[pitching_era]','$row2[class_dist]','$curcoop')";
      $result3=mysql_query($sql3);
   }
   $sql="SELECT * FROM $table WHERE school='$school2'"; //re-read state table
   $result=mysql_query($sql);
}

$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[3];
   $cur_id[$ix]=$row[1];
   $cur_jersey_lt[$ix]=$row[5];
   $cur_jersey_dk[$ix]=$row[6];
   $cur_position[$ix]=$row[7];
   $cur_position[$ix]=split("[/]",$cur_position[$ix]);
   $cur_average[$ix]=$row[8];
   $cur_at_bats[$ix]=$row[9];
   $cur_hits[$ix]=$row[10];
   $cur_runs_scored[$ix]=$row[11];
   $cur_runs_batted[$ix]=$row[12];
   $cur_home_runs[$ix]=$row[13];
   $cur_pitching_record[$ix]=$row[14];
   $cur_pitching_era[$ix]=$row[15];
   $cur_nickname[$ix]=$row[nickname];
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
function CalculateAverage(ix)
{
   var atbats=parseFloat(document.getElementById('atbats'+ ix).value);
   var hits=document.getElementById('hits'+ ix).value;
   var average=(hits/atbats).toFixed(3);
   if(average=="NaN") average="";
   document.getElementById('average'+ ix).value=average;
}
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
echo $header;

if($level==1)
{
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Baseball\">Return to Home-->Baseball Entry Forms</a><br>";
}
?>

<form method=post action="submit_ba.php" enctype="multipart/form-data">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=state value=<?php echo $state; ?>>
<br>
<table>
<caption><b>
BASEBALL ROSTER AND STATISTICS FORM
</b>
</caption>
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
   <table cellspacing=0 cellpadding=2 style="width:600px">
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sql="SELECT * FROM baschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
echo GetSchoolName($sid,'ba',date("Y"))." $mascot";
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
   <th>Assistant Coach(es):</th>
   <td><input type=text name=asst value="<?php echo $asst; ?>" size=40></td>
   </tr>
   <tr align=left>
   <th>Class:</th>
   <td><select name=class_dist>
	  <option>Choose
   <?php
   for($i=0;$i<count($class_array);$i++)
   {
      echo "<option";
      if($class_dist==$class_array[$i]) echo " selected";
      echo ">$class_array[$i]";
   }
   ?>
       </select>
   </td>
   </tr>
   <tr align=left>
   <th>Team Record:</th>
   <td><input type=text name=wins size=3 value=<?php echo $win; ?>>&nbsp;wins
      <input type=text name=losses size=3 value=<?php echo $loss; ?>>&nbsp;losses
   </td></tr>
   <tr align=left>
   <th>Team Photo:</th>
   <td>
   <?php if($filename!=''): ?>
   <p><a href="/nsaaforms/downloads/<?php echo $filename; ?>" target="_blank">Preview Team Photo</a></p>
   <?php endif; ?>
   <!--<iframe style="width:430px;height:175px;" src="simpleupload.php?session=<?php echo $session; ?>&sid=<?php echo $sid; ?>" frameborder='0'></iframe><p><i>Once your file has finished uploading, click "Save and Keep Editing" at the bottom of this page.</i></p></td>
   </tr>-->
   <input type="file" name="imageUpload" id="imageUpload"></p>
   </td></tr>
<?php 
if($level==1)
{
      $sql_id="SELECT * FROM headers WHERE school='$school2'";
      $result_id=mysql_query($sql_id);
      $row_id=mysql_fetch_array($result_id);
      
	  $sql_coop="SELECT * FROM baschool WHERE mainsch='$row_id[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
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
      $sql="SELECT * FROM baschool WHERE sid='$sid'";
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
<td colspan=2><p>Check the box next to the name of each student who will be participating in the <?php echo $form_type; ?> competition.  Required fields for the district tournament are marked with a *.  <b>All fields are required for the state entry form.</b>  When your team qualifies for the state tournament, you must update this form with complete statistics on each player by 10 a.m. the morning after your team has qualified.  Players may be substituted on this team roster prior to district and between district and state at a school's discretion, provided no more than 22 players are in uniform.</p></td>
</tr>
   </table>
</td>
</tr>
<?php
$form_type=strtolower($form_type);
?>
<tr align=center>
<td>
   <table cellspacing=0 cellpadding=3 frame=all rules=all style="border:#808080 1px solid;">
	
   <caption align=right><!--<font size=2>Need help with positions?  <input type=button onClick="window.open('../positionshelp.php?session=<?php echo $session; ?>','positionshelp','height=300, width=500, location=no, menubar=no, resizable=no, scrollbars=yes,toolbar=no,titlebar=no,status=no,top=150,left=150');" value="Click here"> -->
   <?php if($_GET[light] || $_GET[dark])
   {
	if(!empty($_GET[light]) )
	{
		$lj= explode(",",$_GET[light]);
		foreach ($lj as $lightj)
		{
		  $sql="SELECT * FROM eligibility WHERE id='$lightj'";
		  $result=mysql_query($sql);
		  $row=mysql_fetch_array($result);
		  echo"<b style=\"color: red;background-color: yellow;\">Please insert Light Jersey no for $row[first] $row[last]<b><br>";	
		}
	}
	if(!empty($_GET[dark]) )
	{
		$dj= explode(",",$_GET[dark]);
		foreach ($dj as $darkj)
		{
		  $sql="SELECT * FROM eligibility WHERE id='$darkj'";
		  $result=mysql_query($sql);
		  $row=mysql_fetch_array($result);
		  echo"<b style=\"color: red; background-color: yellow;\">Please insert Dark Jersey no for $row[first] $row[last]<b><br>";	
		}
	}
   }
   ?>
   </caption>
	
<?php
$colheaders="
   <tr bgcolor='#f0f0f0' align=center>
   <th colspan=2 class=smaller rowspan=2>*Name, Grade</th>
	<th rowspan=2 class=smaller>Nickname<br>(Overwrites first<br>name on roster)</th>
	<th colspan=2 class=smaller>Jersey No.</th>
   <th class=smaller colspan=".count($ba_positions).">*Position</th>
   <th class=smaller rowspan=2>At<br>Bats</th>
   <th class=smaller rowspan=2>Hits</th>
   <th class=smaller rowspan=2>Batting<br>Average</th>
   <th class=smaller rowspan=2>Runs<br>Scored</th>
   <th class=smaller rowspan=2>Runs<br>Batted<br>In</th>
   <th class=smaller rowspan=2>Home<br>Runs</th>
   <th class=smaller>Pitching Record</th>
   <th class=smaller rowspan=2>Pitching<br>ERA</th>
   </tr>
   <tr bgcolor='#f0f0f0' align=center>
   	<th class=smaller>*Light</th>
   	<th class=smaller>Dark</th>";
for($i=0;$i<count($ba_positions);$i++)
{
   $colheaders.="<th class=smaller>$ba_positions[$i]</th>";
}
	$colheaders.="<th class=smaller>(Wins-Losses-Saves)</th></tr>";

echo $colheaders;

   //get baseball participants from eligibility table in db
   $sql="SELECT id, first, last, middle, semesters, eligible FROM eligibility WHERE school='$school2' and ba='x' ORDER BY last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   //GET BASEBALL PLAYERS FROM THIS TEAM
   	//studs[0]=id, 1=name, 2=school, 3=eligible
   $studs=explode("<result>",GetPlayers('ba',$school));
   $ix=0;
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      if($ix%10==0 && $ix>0)
      {
	 echo $colheaders;
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
      echo "<tr align=center><td onClick=\"Color(this)\">";
      echo "<input type=checkbox name=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td align=left";
      if($stud[3]!="y") echo " bgcolor=red";
      echo ">$stud[1]</td>";
      echo "<td><input type=text name=\"nickname[$ix]\" value=\"".$cur_nickname[$index]."\" size=8></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=jersey_lt[$ix]";
      if($submitted==1) echo " value=$cur_jersey_lt[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=jersey_dk[$ix]";
      if($submitted==1) echo " value=$cur_jersey_dk[$index]";
      echo "></td>";
	/*
      echo "<td><select onChange=\"Color(this)\" name=\"position[$ix][]\" multiple size=2>";
      $posn_ix=0;
      for($i=0;$i<count($ba_positions);$i++)
      {
	 echo "<option";
	 if($submitted==1) 
	 {
	    if($ba_positions[$i]==$cur_position[$index][$posn_ix])
	    {
	       echo " selected";
	       $posn_ix++;
	    }
	 }
	 echo ">$ba_positions[$i]";
      }
      echo "</select></td>";
	*/
      $posn_ix=0;
      for($i=0;$i<count($ba_positions);$i++)
      {
         echo "<td><input type=checkbox name=\"position[$ix][$i]\" value=\"x\"";
         if($ba_positions[$i]==$cur_position[$index][$posn_ix]) 
	 {
	    echo " checked"; $posn_ix++;
	 }
         echo "></td>";
      }
	/*
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=average[$ix]";
      if($submitted==1) echo " value=$cur_average[$index]";
      echo "></td>";
	*/
      echo "<td><input onChange=\"Color(this); CalculateAverage($ix);\" type=text size=2 name=\"at_bats[$ix]\" id=\"atbats".$ix."\"";
      if($submitted==1) echo " value=$cur_at_bats[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this); CalculateAverage($ix);\" type=text size=2 name=\"hits[$ix]\" id=\"hits".$ix."\"";
      if($submitted==1) echo " value=$cur_hits[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=4 name=\"average[$ix]\" id=\"average".$ix."\"";
      if($submitted==1) echo " value=$cur_average[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=runs_scored[$ix]";
      if($submitted==1) echo " value=$cur_runs_scored[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=runs_batted[$ix]";
      if($submitted==1) echo " value=$cur_runs_batted[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=home_runs[$ix]";
      if($submitted==1) echo " value=$cur_home_runs[$index]";
      echo "></td>";
      $pitchrecord=split("-",$cur_pitching_record[$index]);
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=pitching_record_wins[$ix]";
      if($submitted==1) echo " value=$pitchrecord[0]";
      echo "><b>-</b>";
      echo "<input onChange=\"Color(this)\" type=text size=2 name=pitching_record_losses[$ix]";
      if($submitted==1) echo " value=$pitchrecord[1]";
      echo "><b>-</b>";
      echo "<input onChange=\"Color(this)\" type=text size=2 name=pitching_record_saves[$ix]";
      if($submitted==1) echo " value=$pitchrecord[2]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=pitching_era[$ix]";
      if($submitted==1) echo " value=$cur_pitching_era[$index]";
      echo "><input type=hidden name=\"id[$ix]\" value=\"$stud[0]\"><input type=hidden name=\"studsch[$ix]\" value=\"$stud[2]\"></td></tr>";
      $ix++;
   }
   echo "</table>";

?>

</td>
</tr>
<tr align=left>
<td><br><font size=2><i>(Substitutions may be made the day of the district meet and, if team qualifies for state, for the start of the state meet.)</i><br>
<font style="color:red">Students listed in red are currently <b>ineligible</b>.  Please make sure they will be eligible for the <?php echo $form_type; ?> tournament before submitting them on this form.</font></p></font></td>
</tr>
<?php
if($state==1)   //have checkbox for final submission
{
?>
<tr align=left>
<th>
<input type=checkbox name="send" value=y>
Check this box if you want to submit the above information as your final state entry.
You have not officially submitted your entry until you have checked this
box and clicked one of the two "Save" buttons below!
</th>
</tr>
<?php
} //end if state
?>
<tr align=left>
<td><br><font size=3><i><?php echo $certify; ?></i></font><br><br></td>
</tr>
<tr align=center>
<td><input type=submit name="save" value="Save and Keep Editing">
    <input type=submit name="save" value="Save and View Form">
    <input type=submit name="save" value="Cancel">
</td>
</tr>
</table>
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
