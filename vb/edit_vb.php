<?php
//edit_vb.php: Volleyball Entry form
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
$school2=ereg_replace("\'","\'",$school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="vb";
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

//get class/dist choices:
$sql="SELECT choices FROM classes_districts WHERE sport='vb'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_array=split(",",$row[0]);

//get name of coach from logins table
$sql="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='Volleyball'";
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
$sql="SELECT duedate FROM form_duedates WHERE form='vb'";
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
   $table="vb_state";
}
else    //district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="vb";
}
/* NO LONGER LOCK THE FORM, JUST SWITCH FROM DISTRICT TO STATE
else if(PastDue($duedate,0) && $level!=1)
{
//CHECK IF IT IS >2, <8.5 DAYS PAST THE DUE DATE FOR THIS FORM (lock if so)
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo $header;
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM vb WHERE school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_vb.php?session=$session&school_ch=$school_ch\">";
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
else	//district form
{
   $state=0;
   $form_type="DISTRICT";
   $table="vb";
}
*/

if($count>14)
{
?>
<script language="javascript">
alert("You tried to check too many players!  You may only check 14 players.  Please make sure you have the correct 14 players (or less) checked and submit your form again");
</script>
<?php
}

//get class/dist for this team
$sql="SELECT class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//record
$sql2="SELECT team_record FROM $table WHERE school='$school2'";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $temp=split("-",$row2[0]);
   $win=$temp[0]; $loss=$temp[1];
}

//If form has already been submitted, get info from db:
$sql="SELECT * FROM $table WHERE (school='$school2' OR co_op='$school2')";
$result=mysql_query($sql);
//If first time editing state form, COPY DISTRICT TABLE TO STATE TABLE
if($state==1 && mysql_num_rows($result)==0)	
{
   $sql2="INSERT INTO $db_name.vb_state SELECT * FROM $db_name.vb WHERE (school='$school2' OR co_op='$school2')";
   $result2=mysql_query($sql2);
echo mysql_error();
   $sql="SELECT * FROM $table WHERE (school='$school2' OR co_op='$school2')"; //re-read state table
   $result=mysql_query($sql);
}
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[7];
   $cur_id[$ix]=$row[1];
   $cur_jersey_lt[$ix]=$row[3];
   $cur_nickname[$ix]=$row[nickname];
   $cur_libero[$ix]=$row[4];
   $height=$row[5];
   $height=split("-",$height);
   $cur_height_ft[$ix]=$height[0];
   $cur_height_in[$ix]=$height[1];
   $cur_good[$ix]=$row[8];
   $cur_att[$ix]=$row[9];
   $cur_ace[$ix]=$row[10];
   $cur_blocks[$ix]=$row[11];
   $cur_kills[$ix]=$row[12];
   $cur_assists[$ix]=$row[13];
   $cur_position[$ix]=$row[14];
   $cur_position[$ix]=split("[/]",$cur_position[$ix]);
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
echo $header;

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Volleyball\">Return to Home-->Volleyball Entry Forms</a><br>";
?>

<form method=post action="submit_vb.php" enctype="multipart/form-data">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=state value=<?php echo $state; ?>>
<table style="width:800px;">
<tr align=center>
<th>VOLLEYBALL <?php echo $form_type; ?> ENTRY</th>
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
<tr align=left>
<td>
   <table cellspacing=0 cellpadding=2>
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'vb');
$sql="SELECT * FROM vbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0)
{
   echo "<div class='error'>ERROR: School not found in list of ".GetActivityName($sport)." teams.</div><br>";
}
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
if(mysql_num_rows($result) && citgf_file_exists("../downloads/".$row[filename]))
{
   $filename=$row[filename];
}
else
{
   $filename="";
}
   echo GetSchoolName($sid,'vb',date("Y"))." $mascot";
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
   <tr align=left><th>Class:</th>
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
   <th>Team Record:</th><td><input type=text name=wins value="<?php echo $win; ?>" size=2>wins <input type=text name=losses value="<?php echo $loss; ?>" size=2>losses</td>
   </tr>
   <tr align=left><th>Team Photo:</th><td>
   <?php if($filename!=''): ?>
   <p><a href="/nsaaforms/downloads/<?php echo $filename; ?>" target="_blank">Preview Team Photo</a></p>
   <?php endif; ?>
   <!--<iframe style="width:430px;height:175px;" src="simpleupload.php?session=<?php echo $session; ?>&sid=<?php echo $sid; ?>" frameborder='0'></iframe><p><i>Once your file has finished uploading, click "Save and Keep Editing" at the bottom of this page.</i></p>-->
   <input type="file" name="imageUpload" id="imageUpload"></p>
   </td></tr>
<?php
if($level==1)
{
      $sql_id="SELECT * FROM headers WHERE school='$school2'";
      $result_id=mysql_query($sql_id);
      $row_id=mysql_fetch_array($result_id);
      
	  $sql_coop="SELECT * FROM vbschool WHERE mainsch='$row_id[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
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
<tr align=left>
<td><p>Check the box next to the name of each student who will be participating in the district competition.  Then complete the rest of that player's information.  Fields with a * are required for the district entry.  <b>All fields are required for the state entry.</b>
</p></td>
</tr>
<tr align=left>
<td><p>No more than 14 may be suited up in any one tournament game.  See NSAA Volleyball Manual for rules about official tournament rosters.</p></td>
</tr>
<tr align=center>
<td>
   <table cellspacing=0 cellpadding=3 frame=all rules=all class='nine' style="border:#808080 1px solid;">
   <caption align=right>
   <table>
   <tr align=left><td>
   <font style="color:red"><b>NOTE: Only list your jersey numbers in the "Light Jersey No." column.  IF a player has two different jersey numbers OR your Libero is another number, then enter that number in the "Dark OR Libero Jersey No." column.</b></font>
   </td></tr>
   <tr align=right><td>
   <font size=2>Need help with positions?  <input type=button onClick="window.open('../positionshelp.php?session=<?php echo $session; ?>','positionshelp','height=300,width=500,location=no,menubar=no,resizable=no,scrollbars=yes,toolbar=no,titlebar=no,status=no,top=150,left=150');" value="Click Here">
   </td></tr>
   </table>
   </caption>
   <tr align=center>
   <th class="smaller" colspan=2>*Name (Grade)</th>
	<th class="smaller">Nickname</th>
   <th class=smaller>Position</th>
   <th class=smaller>*Light<br>Jersey<br>No.</th>
   <th class=smaller>Dark OR<br>Libero<br>Jersey No.</th>
   <th class=smaller>*Height</th>
   <th class=smaller>Digs</th>
   <th class=smaller>Serve<br>Receptions</th>
   <th class=smaller>Ace<br>Serves</th>
   <th class=smaller>Solo<br>Blocks</th>
   <th class=smaller>Kills</th>
   <th class=smaller>Assists</th>
   </tr>
<?php

   //GET VB PLAYERS FROM THIS TEAM
   $studs=explode("<result>",GetPlayers($sport,$school));
   $ix=0;
   for($s=0;$s<count($studs);$s++)
   {
      $stud=explode("<detail>",$studs[$s]);
      if($ix%10==0 && $ix>0)
      {
	 echo "<tr align=center><th class=smaller colspan=2>*Name (Grade)</th><th class=smaller>Nickname</th>";
	 echo "<th class=smaller>Position</th>";
	 echo "<th class=smaller>*Light<br>Jersey<br>No.</th>";
	 echo "<th class=smaller>Dark OR<br>Libero<br>Jersey No.</th>";
	 echo "<th class=smaller>*Height</th>";
	 echo "<th class=smaller>Digs</th>";
	 echo "<th class=smaller>Serve<br>Receptions</th>";
	 echo "<th class=smaller>Ace<br>Serves</th>";
	 echo "<th class=smaller>Solo<br>Blocks</th>";
	 echo "<th class=smaller>Kills</th>";
	 echo "<th class=smaller>Assists</th>";
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
      echo "<tr align=center><td>";
      echo "<input onClick=\"Color(this)\" type=checkbox name=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($stud[3]!="y") echo " bgcolor=red";
      echo ">$stud[1]</td>";
      echo "<td><input type=text name=\"nickname[$ix]\" value=\"".$cur_nickname[$index]."\" size=8></td>";
      echo "<td><select onChange=\"Color(this)\" name=\"position_array[$ix][]\" multiple size=2>";
      $posn_ix=0;
      for($i=0;$i<count($vb_positions);$i++)
      {
	 echo "<option";
	 if($submitted==1)
	 {
	    if($vb_positions[$i]==$cur_position[$index][$posn_ix])
	    {
	       echo " selected";
	       $posn_ix++;
	    }
	 }
	 echo ">$vb_positions[$i]";
      }
      echo "</select>";
      echo "</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=jersey_lt[$ix]";
      if($submitted==1) echo " value=$cur_jersey_lt[$index]";
      echo ">";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=libero[$ix]";
      if($submitted==1) echo " value=$cur_libero[$index]";
      echo ">";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=height_ft[$ix]";
      if($submitted==1) echo " value=$cur_height_ft[$index]";
      echo ">ft ";
      echo "<input onChange=\"Color(this)\" type=text size=2 class=tiny name=height_in[$ix]";
      if($submitted==1) echo " value=$cur_height_in[$index]";
      echo ">in</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=good_serves[$ix]";
      if($submitted==1) echo " value=$cur_good[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=att_serves[$ix]";
      if($submitted==1) echo " value=$cur_att[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=ace_serves[$ix]";
      if($submitted==1) echo " value=$cur_ace[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=blocks[$ix]";
      if($submitted==1) echo " value=$cur_blocks[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=kills[$ix]";
      if($submitted==1) echo " value=$cur_kills[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 class=tiny name=assists[$ix]";
      if($submitted==1) echo " value=$cur_assists[$index]";
      echo "></td>";
      echo "<input type=hidden name=id[$ix] value=$stud[0]><input type=hidden name=\"studsch[$ix]\" value=\"$stud[2]\"></tr>";
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
<tr align=left>
<th>
<input type=checkbox name="send" value=y>
Check this box if you want to submit the above information as your final state entry.  You have not officially submitted your entry until you have checked this box and clicked one of the two "Save" buttons below!
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
