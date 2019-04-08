<?php
//edit_sb.php: Softball entry form

//check if user needs to be re-directed:
if($submit=="Home")
{
   header("Location:/nsaaforms/welcome.php?session=$session");
   exit();
}
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
$schoolid=$row[id]; $sport="sb";
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
$sql="SELECT choices FROM classes_districts WHERE sport='sb'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_array=split(",",$row[0]);

//get class/dist of this team
$sql="SELECT class_dist FROM sb WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//get name of coach from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Softball'";
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
$sql="SELECT duedate FROM form_duedates WHERE form='sb'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//Check if State Form should show up now (Dist Form is 8 days past due date)
if(PastDue($duedate,8))
{
   $form_type="STATE";
   $state=1;
   $table="sb_state";
}
else    //district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="sb";
}
/*
else if(PastDue($duedate,0) && $level!=1 && $school!="Test's School")
{
//CHECK IF IT IS PAST THE DUE DATE FOR THIS FORM
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo $header;
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM sb WHERE school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_sb.php?session=$session&school_ch=$school_ch\">";
      echo "View your Submitted Form</a>";
   }
   else
   {
      echo "<font size=2>";
      echo "No information was submitted for your district entry.<br>";
      echo "If this was a mistake, please contact the NSAA immediately!";
      echo "<br><br>";
      echo "<a href=\"../welcome.php?session=$session\">Return Home</a></font>";
   }
   exit();
}
else    //district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="sb";
}
*/
//get team record from sb table
$sql="SELECT team_record FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$record=$row[0];
$record=split("-",$record);
$win=$record[0];
$loss=$record[1];


//If form has already been submitted, get info from db:
$sql="SELECT * FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
//If first time editing state form, COPY DISTRICT TABLE TO STATE TABLE
if($state==1 && mysql_num_rows($result)==0)
{
   $sql2="INSERT INTO $db_name.sb_state SELECT * FROM $db_name.sb WHERE (school='$school2' OR co_op='$school2')";
   $result2=mysql_query($sql2);
   $sql="SELECT * FROM $table WHERE school='$school2'"; //re-read state table
   $result=mysql_query($sql);
}

$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[3];
   $cur_id[$ix]=$row[1];
   $cur_jersey_lt[$ix]=$row[5];
   $cur_nickname[$ix]=$row[nickname];
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
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Softball\">Return to Home-->Softball Entry Forms</a><br>";
?>

<form method=post action="submit_sb.php" name='form1'  enctype="multipart/form-data">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=state value=<?php echo $state; ?>>
<table>
<tr align=center>
<th>SOFTBALL <?php echo $form_type; ?> ENTRY</th>
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
   <table cellspacing=0 cellpadding=2>
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'sb');
$sql="SELECT * FROM sbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$filename=$row[filename];
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
echo GetSchoolName($sid,'sb')." $mascot";
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
   <th>Assistant Coaches:</th><td><input type=text name="asst" size=50 value="<?php echo $asst; ?>"></td>
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
   </td>
   </tr>
   <tr align=left><th>Team Photo:</th><td>
   <?php if($filename!=''): ?>
   <p><a href="/nsaaforms/downloads/<?php echo $filename; ?>" target="_blank">Preview Team Photo</a></p>
   <?php endif; ?>
   <!--<iframe style="width:430px;height:175px;" src="simpleupload.php?session=<?php echo $session; ?>&sid=<?php echo $sid; ?>" frameborder='0'></iframe><p><i>Once
 your file has finished uploading, click "Save and Keep Editing" at the bottom of this page.</i></p></td></tr>-->
  <input type="file" name="imageUpload" id="imageUpload"></p>
   </td></tr>
<?php
if($level==1)
{    
      $sql_id="SELECT * FROM headers WHERE school='$school2'";
      $result_id=mysql_query($sql_id);
      $row_id=mysql_fetch_array($result_id);
      
	  $sql_coop="SELECT * FROM sbschool WHERE mainsch='$row_id[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
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
   </table>
</td>
</tr>
<?php
$form_type=strtolower($form_type);
?>
<tr align=center>
<td><div class="normalwhite" style="width:600px;text-align:left;"><p>Check the box next to the name of each student who will be participating in the <?php echo $form_type; ?> competition.  Required fields for the district tournament are marked with a *.  <b>All fields are required for the state entry form.</b>  When your team qualifies for the state tournament, you must update this form with complete statistics on each player by 10 a.m. the morning after your team has qualified.  Players may be substituted on this team roster prior to district and between district and state at a school's discretion, provided no more than 20 players are in uniform.</p></div></td>
</tr>
<tr align=center>
<td>
   <table frame=all rules=all cellspacing=0 cellpadding=4 style="border:#808080 1px solid;">
   <caption align=right><font size=2>Need help with positions?  <input type=button onClick="window.open('../positionshelp.php?session=<?php echo $session; ?>','positionshelp','height=300, width=500, location=no, menubar=no, resizable=no, scrollbars=yes,toolbar=no,titlebar=no,status=no,top=150,left=150');" value="Click here"><br> 
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
   <tr align=center>
   <th colspan=2 class=smaller>*Name (Grade)</th><th class=smaller>Nickname</th>
   <th class=smaller>*Light<br>Jersey<br>No.</th>
   <th class=smaller>Dark<br>Jersey<br>No.</th>
   <th class=smaller>*Position</th>
   <th class=smaller>Batting<br>Average</th>
   <th class=smaller>At<br>Bats</th><th class=smaller>Hits</th>
   <th class=smaller>Runs<br>Scored</th>
   <th class=smaller>Runs<br>Batted<br>In</th>
   <th class=smaller>Home<br>Runs</th>
   <th class=smaller>Pitching<br>Record</th>
   <th class=smaller>Pitching<br>ERA</th>
   </tr>
<?php

   //GET SB PLAYERS FROM THIS TEAM
   $studs=explode("<result>",GetPlayers($sport,$school));
   $ix=0;
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);	//ID, name, school, eligible
      if($ix%10==0 && $ix>0)
      {
	 echo "<tr align=center>";
	 echo "<th class=smaller colspan=2>*Name (Grade)</th><th class=smaller>Nickname";
	 echo "<th class=smaller>*Light<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>Dark<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>*Position</th>";
	/*
	 echo "<th class=smaller>Batting<br>Average</th>";
	 echo "<th class=smaller>At<br>Bats</th><th class=smaller>Hits</th>";
	 echo "<th class=smaller>Runs<br>Scored</th>";
	 echo "<th class=smaller>Runs<br>Batted<br>In</th>";
	 echo "<th class=smaller>Home<br>Runs</th>";
	 echo "<th class=smaller>Pitching<br>Record</th>";
	 echo "<th class=smaller>Pitching<br>ERA</th>";
	*/
	 echo "</tr>";
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
      echo "<input type=checkbox name=check[$ix] id=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($stud[3]!="y") echo " bgcolor=red";
      echo ">$stud[1]</td><td><input type=text name=\"nickname[$ix]\" onChange=\"Color(this);\" value=\"$cur_nickname[$ix]\"></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 id=\"jersey_lt[$ix]\" name=jersey_lt[$ix]";
      if($submitted==1) echo " value=$cur_jersey_lt[$index]";
      echo " ></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=jersey_dk[$ix]";
      if($submitted==1) echo " value=$cur_jersey_dk[$index]";
      echo "></td>";
      echo "<td><select onChange=\"Color(this)\" name=\"position[$ix][]\" multiple size=2>";
      $posn_ix=0;
      for($i=0;$i<count($sb_positions);$i++)
      {
	 echo "<option";
	 if($submitted==1) 
	 {
	    if($sb_positions[$i]==$cur_position[$index][$posn_ix])
	    {
	       echo " selected";
	       $posn_ix++;
	    }
	 }
	 echo ">$sb_positions[$i]";
      }
      echo "</select></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=4 name=average[$ix]";
      if($submitted==1) echo " value=$cur_average[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=at_bats[$ix]";
      if($submitted==1) echo " value=$cur_at_bats[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=hits[$ix]";
      if($submitted==1) echo " value=$cur_hits[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=runs_scored[$ix]";
      if($submitted==1) echo " value=$cur_runs_scored[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=runs_batted[$ix]";
      if($submitted==1) echo " value=$cur_runs_batted[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=home_runs[$ix]";
      if($submitted==1) echo " value=$cur_home_runs[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=pitching_record[$ix]";
      if($submitted==1) echo " value=$cur_pitching_record[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=3 name=pitching_era[$ix]";
      if($submitted==1) echo " value=$cur_pitching_era[$index]";
      echo "></td>";
      echo "<input type=hidden name=\"id[$ix]\" value=\"$stud[0]\"><input type=hidden name=\"studsch[$ix]\" value=\"$stud[2]\"></tr>";
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
<?php
   $ix=0;
   for($s=0;$s<count($studs);$s++)
   {
?>
<script>
/* $( "#check<?php echo$ix; ?>" ).click(function() {
  if(this.getField("check<?php echo$ix; ?>").value=="y")
{
this.getField("jersey_lt<?php echo$ix; ?>").required=true;
}
else
{
this.getField("jersey_lt<?php echo$ix; ?>").required=false;
}
}); */
</script>
<?php $ix++; } ?>