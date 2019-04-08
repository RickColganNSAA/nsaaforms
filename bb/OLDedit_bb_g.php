<?php
//edit_bb_g.php: Basketball entry form

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
$school2=ereg_replace("\'","\'",$school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="bbg";
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
$sql="SELECT choices FROM classes_districts WHERE sport='bb_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_array=split(",",$row[0]);

//get name of coach from logins table
$sql="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Basketball'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];
$asst=$row[1];

//get mascot and colors from headers table
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$colors=$row[5];
$mascot=$row[6];

//get due date from db
$sql="SELECT duedate FROM form_duedates WHERE form='bb_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//Check if State Form should show up now (Dist Form is 10 days past due date)
if(PastDue($duedate,10))
{
   $form_type="STATE";
   $state=1;
   $table="bb_gstate";
}
else if(PastDue($duedate,0) && $level!=1)
{
//CHECK IF IT IS >2 DAYS PAST THE DUE DATE FOR THIS FORM
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo $header;
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM bb_g WHERE school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_bb_g.php?session=$session&school_ch=$school_ch\">";
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
   $table="bb_g";
}

//get team record and off/dev avg and class from bb table
$sql="SELECT record,off_avg,def_avg,class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
if($state==1 && mysql_num_rows($result)==0)
{
   $sql=ereg_replace("bb_gstate","bb_g",$sql);
   $result=mysql_query($sql);
}
$row=mysql_fetch_array($result);
$record=$row[0];
$record=split("-",$record);
$win=$record[0];
$loss=$record[1];
$off_avg=$row[1];
$def_avg=$row[2];
$class_dist=$row[3];


//If form has already been submitted, get info from db:
$sql="SELECT * FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
//If first time editing state form, COPY DISTRICT TABLE TO STATE TABLE
if($state==1 && mysql_num_rows($result)==0)
{
   //$sql2="INSERT INTO $db_name.bb_gstate SELECT * FROM $db_name.bb_g WHERE (school='$school2' OR co_op='$school2')";
   //$result2=mysql_query($sql2);
   $sql="SELECT * FROM bb_g WHERE school='$school2'"; //re-read state table
   $result=mysql_query($sql);
}

$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[17];
   $cur_id[$ix]=$row[1];
   $cur_height[$ix]=$row[3];
   $cur_height[$ix]=split("-",$cur_height[$ix]);
   $cur_height_ft[$ix]=$cur_height[$ix][0];
   $cur_height_in[$ix]=$cur_height[$ix][1];
   $cur_jersey_lt[$ix]=$row[4];
   $cur_jersey_dk[$ix]=$row[5];
   $cur_total_pts[$ix]=$row[6];
   $cur_pt_avg[$ix]=$row[7];
   $cur_total_rb[$ix]=$row[11];
   $cur_reb_avg[$ix]=$row[12];
   $cur_position[$ix]=$row[position];
   $cur_position[$ix]=split("[/]",$cur_position[$ix]);
   $cur_total_assists[$ix]=$row[total_assists];
   $cur_total_steals[$ix]=$row[total_steals];
   $cur_total_blocks[$ix]=$row[total_blocks];
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
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Basketball\">Return to Home-->Basketball Entry Forms</a><br>";
?>

<form method=post action="submit_bb_g.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=state value=<?php echo $state; ?>>
<table width=100%>
<tr align=center>
<th>GIRLS BASKETBALL <?php echo $form_type; ?> ENTRY</th>
</tr>
<?php
if($state!=1)
{
?>
<tr align=center>
<td><b>Due <?php echo $duedate2; ?></b><br><br></td>
</tr>
<?php
}//end if district
?>
<tr align=left>
<td>
   <table cellspacing=0 cellpadding=2>
   <tr align=left>
   <th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'bbg');
$sql="SELECT * FROM bbgschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
echo GetSchoolName($sid,'bbg',date("Y"))." $mascot";
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
   <th>Assistant Coach(es):</th><td><input type=text size=30 name=asst_coaches value="<?php echo $asst; ?>"></td>
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
   <td><input type=text name=wins size=2 value=<?php echo $win; ?>>&nbsp;wins
      <input type=text name=losses size=2 value=<?php echo $loss; ?>>&nbsp;losses
   </td>
   </tr>
   <tr align=left>
   <th>Team Offensive Avg:</th>
   <td><input type=text name=off_avg size=3 value=<?php echo $off_avg; ?>></td>
   </tr>
   <tr align=left>
   <th>Team Defensive Avg:</th>
   <td><input type=text name=def_avg size=3 value=<?php echo $def_avg; ?>></td>
   </tr>
   </table>
</td>
</tr>
<?php
$form_type=strtolower($form_type);
?>
<tr align=center>
<td><p>Check the box next to the name of each student who will be participating in the <?php echo $form_type; ?> competition.  Required fields for the district tournament are marked with a *.  <b>All fields are required for the state entry form.</b>  When your team qualifies for the state tournament, you must update this form with complete statistics on each player by 10 a.m. the morning after your team has qualified. No more than 14 players may be designated as participants in any one district tournament game.  See NSAA Basketball Manual for rules about official tournament rosters.</p></td>
</tr>
<tr align=center><td><div class=alert style="width:500px";><i>For <b>Positions</b>, hold down CTRL (or Apple/Option on a Mac) to make multiple selections.</i></div></td></tr>
<tr align=center>
<td>
<?php
$sql="SELECT * FROM $table WHERE (school='$school2' OR co_op='$school2') AND checked='y'";
$result=mysql_query($sql);
$entryct=mysql_num_rows($result);
if($entryct>14) //too many entries
{
   echo "<font style=\"color:red; font-size:9pt\"><b>You have entered too many students!! </b>You may only enter <b>14</b> on this form.  You have entered $entryct. Please correct this error.</font>";
}
?>
   <table border=1 cellspacing=2 cellpadding=3 bordercolor=#000000>
<?php
$colheaders="
   <tr align=center>
   <th colspan=2 class=smaller>*Name</th>
   <th class=smaller>*Grade</th>
   <th class=smaller>*Light<br>Jersey<br>No.</th>
   <th class=smaller>*Dark<br>Jersey<br>No.</th>
   <th class=smaller>*Position</th>
   <th class=smaller>*Height</th>
   <th class=smaller>Total<br>Points</th>
   <th class=smaller>Point<br>Average</th>
   <th class=smaller>Total<br>Rebounds</th>
   <th class=smaller>Rebound<br>Average</th>
   <th class=smaller>Total<br>Assists</th>
   <th class=smaller>Total<br>Steals</th>
   <th class=smaller>Total<br>Blocks</th>
   </tr>
";
echo $colheaders;

   //get basketball participants from eligibility table in db
   $sql="SELECT id, first, last, middle, semesters, eligible FROM eligibility WHERE school='$school2' and bb='x' AND gender='F' ORDER BY last";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($ix%10==0 && $ix>0)
      {
	 echo $colheaders;
      }
      //check if this student is already submitted:
      $submitted=0;
      for($i=0;$i<count($cur_id);$i++)
      {
	 if($cur_id[$i]==$row[0]) 
	 {
	    $submitted=1;
	    $index=$i;
	 }
      } 
      echo "<tr align=center><td onClick=\"Color(this)\">";
      echo "<input type=checkbox name=check[$ix] value=y";
      if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
      echo "></td>";
      echo "<td";
      if($row[5]!="y") echo " bgcolor=red";
      echo ">$row[2], $row[1] $row[3]</td>";
      $year=GetYear($row[4]);
      echo "<td>$year</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=jersey_lt[$ix]";
      if($submitted==1) echo " value=$cur_jersey_lt[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=jersey_dk[$ix]";
      if($submitted==1) echo " value=$cur_jersey_dk[$index]";
      echo "></td>";
      echo "<td><select onChange=\"Color(this)\" name=\"position_array[$ix][]\" multiple size='2'>";
      $posn_ix=0;
      for($i=0;$i<count($bb_positions);$i++)
      {
         echo "<option";
         if($submitted==1)
         {
            if($bb_positions[$i]==$cur_position[$index][$posn_ix])
            {
               echo " selected";
               $posn_ix++;
            }
         }
         echo ">$bb_positions[$i]";
      }
      echo "</select>";
      echo "</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=height_ft[$ix]";
      if($submitted==1) echo " value=$cur_height_ft[$index]";
      echo ">&nbsp;ft&nbsp;";
      echo "<input onChange=\"Color(this)\" type=text size=2 name=height_in[$ix]";
      if($submitted==1) echo " value=$cur_height_in[$index]";
      echo ">&nbsp;in</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=total_pts[$ix]";
      if($submitted==1) echo " value=$cur_total_pts[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=pt_avg[$ix]";
      if($submitted==1) echo " value=$cur_pt_avg[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=total_rb[$ix]";
      if($submitted==1) echo " value=$cur_total_rb[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=reb_avg[$ix]";
      if($submitted==1) echo " value=$cur_reb_avg[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=total_assists[$ix]";
      if($submitted==1) echo " value=\"".$cur_total_assists[$index]."\"";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=total_steals[$ix]";
      if($submitted==1) echo " value=$cur_total_steals[$index]";
      echo "></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=total_blocks[$ix]";
      if($submitted==1) echo " value=$cur_total_blocks[$index]";
      echo "></td>";
      echo "<input type=hidden name=id[$ix] value=$row[0]></td></tr>";
      $ix++;
   }
   echo "</table>";

   //Show Co-op Students added (if any) and button to Add More
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters, t2.eligible FROM $table AS t1, eligibility AS t2 WHERE t1.co_op='$school2' AND t1.student_id=t2.id";
   $result=mysql_query($sql);
   echo "<br><input type=button name=button onClick=\"window.open('coop_bb_g.php?session=$session&school=$school2&class_dist=$class_dist','coop','menubar=no, location=no, resizable=no, scrollbars=yes, width=650, height=450')\" value=\"Add Co-Op Students\"><br><font style=\"color:red\">If you have added co-op students and don't see them below, you may need to refresh this screen.  Or click \"Save & Keep Editing\".";
   if(mysql_num_rows($result)>0)	//show existing co-op students
   {
      echo "<br><a name=coop></a>";
      echo "<table border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
      echo "<caption><b>Co-Op Students:</b>";
      echo "<div class=alert style=\"width:500px;\"><i>For <b>Positions</b>, hold down CTRL (or Apple/Option on a Mac) to make multiple selections.</i></div>";
      echo "</caption>";
      echo "
   <tr align=center>
   <th colspan=3 class=smaller>*Name</th>
   <th class=smaller>*Grade</th>
   <th class=smaller>*Light<br>Jersey<br>No.</th>
   <th class=smaller>*Dark<br>Jersey<br>No.</th>
   <th class=smaller>*Position</th>
   <th class=smaller>*Height</th>
   <th class=smaller>Total<br>Points</th>
   <th class=smaller>Point<br>Average</th>
   <th class=smaller>Total<br>Rebounds</th>
   <th class=smaller>Rebound<br>Average</th>
   <th class=smaller>Total<br>Assists</th>
   <th class=smaller>Total<br>Steals</th>
   <th class=smaller>Total<br>Blocks</th>
   </tr>
      ";
   }
   $coop=0;
   while($row=mysql_fetch_array($result))
   {
      //get year in school
      $year=GetYear($row[semesters]);
      //get height in correct format
      $height=split("-",$row[3]);
      $height_ft=$height[0]; $height_in=$height[1];
      echo "<tr align=left>";
      echo "<td><input onClick=\"Color(this)\" type=checkbox";
      echo " name=\"coop_check[$coop]\" value=y";
      if($row[17]=='y') echo " checked";
      echo "></td>";
      echo "<td>$row[school]</td>";
      echo "<td";
      if($row[eligible]!='y') echo " bgcolor=#FF0000";
      echo ">$row[last], $row[first] $row[middle]</td>";
      echo "<td>$year</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_jersey_lt[$coop]\" value=$row[4]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_jersey_dk[$coop]\" value=$row[5]></td>";
      echo "<td><select onChange=\"Color(this)\" name=\"coop_position_array[$coop][]\" multiple size='2'>";
      $coop_pos=split("[/]",$row[position]);
      for($i=0;$i<count($bb_positions);$i++)
      {
         echo "<option";
	 for($j=0;$j<count($coop_pos);$j++)
	 {
	    if($bb_positions[$i]==$coop_pos[$j]) echo " selected";
	 }
         echo ">$bb_positions[$i]";
      }
      echo "</select>";
      echo "</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_height_ft[$coop]\" value=$height_ft>&nbsp;ft&nbsp;";
      echo "<input onChange=\"Color(this)\" type=text size=2 name=\"coop_height_in[$coop]\" value=$height_in>&nbsp;in</td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_total_pts[$coop]\" value=$row[6]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_pt_avg[$coop]\" value=$row[7]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_total_rb[$coop]\" value=$row[11]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_reb_avg[$coop]\" value=$row[12]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_total_assists[$coop]\" value=$row[total_assists]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_total_steals[$coop]\" value=$row[total_steals]></td>";
      echo "<td><input onChange=\"Color(this)\" type=text size=2 name=\"coop_total_blocks[$coop]\" value=$row[total_blocks]></td>";
      echo "<input type=hidden name=\"coop_student[$coop]\" value=$row[1]>";
      echo "</tr>";
      $coop++;
   }
   if(mysql_num_rows($result)>0) echo "</table>";
?>

</td>
</tr>
<tr align=left>
<td><br>
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
<td><b>PLEASE ONLY CLICK THESE BUTTONS ONE TIME!!!</b><br>
    <input type=submit name=submit value="Save and Keep Editing">
    <input type=submit name=submit value="Save and View Form">
    <input type=submit name=submit value="Cancel"><br>
    <b>PLEASE ONLY CLICK THESE BUTTONS ONE TIME!!!</b>
</td>
</tr>
</table>
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
