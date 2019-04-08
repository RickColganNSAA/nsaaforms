<?php
//edit_sp.php: edit speech district form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($school_ch && GetLevel($session))
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=addslashes($school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="sp";
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

//get mascot, colors
$sql="SELECT mascot, color_names FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[0]; $colors=$row[1];

//get coach
$sql="SELECT name,asst_coaches FROM logins WHERE level='3' AND sport='Speech' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];

$duedate=GetDueDate("sp");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
//CHECK IF THIS SCHOOL'S DISTRICT IS OVER:
	$sql="SELECT * FROM $db_name2.spdistricts WHERE (type='District' OR type='Subdistrict' OR type='District Final') AND (schools LIKE '%$school2%')";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$distdate=$row[dates];
//if(PastDue($duedate,0.5) && (($row[dates]!='0000-00-00' && mysql_num_rows($result)>0 && PastDue($distdate,0)) || mysql_num_rows($result)==0) && GetLevel($session)!=1)
if(PastDue($duedate,0.5) && GetLevel($session)!=1)
{
   $late_page=GetLatePage($duedate2);
   echo $init_html;
   echo GetHeader($session);
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT t1.* FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_sp.php?session=$session&school_ch=$school_ch\">";
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

echo $init_html;
echo GetHeader($session);
if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Speech\">Return to Home-->Speech Entry Forms</a><br>";

//get already-submitted info for this school:
$sql="SELECT t1.* FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_id[$ix]=$row[student_id];
   $cur_drama1[$ix]=$row[drama1];
   $cur_drama2[$ix]=$row[drama2];
   $cur_poetry[$ix]=$row[poetry];
   $cur_pers_speak[$ix]=$row[pers_speak];
   $cur_inform[$ix]=$row[inform];
   $cur_extemp[$ix]=$row[extemp];
   $cur_ent_speak[$ix]=$row[ent_speak];
   $cur_duet_acting1[$ix]=$row[duet_acting1];
   $cur_duet_acting2[$ix]=$row[duet_acting2];
   $cur_prose_humor[$ix]=$row[prose_humor];
   $cur_prose_serious[$ix]=$row[prose_serious];
   $cur_checked[$ix]=$row[checked];
   $ix++;
}
$date=explode("-",GetDueDate('sp'));
?>
<br>
<font size=2><b>
DISTRICT SPEECH CONTEST ENTRY FORM<br>
</font></b>
<div class=alert style='width:600px;font-size:9pt;'><b>PLEASE NOTE: </b> You do <b><u>NOT</b></u> need to e-mail or otherwise send this form to your district director! The director will be able to access this form through his or her NSAA Login. The information you've entered below must be <b><u>COMPLETED BY MIDNIGHT ON <?php echo $date[1]."/".$date[2]."/".$date[0]?></b></u>. After that date, your director will consider your form <b>COMPLETE</b>.</div><br>
<br>
   <form method=post name=spform action="submit_sp.php">
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<table width=90%><!--Table of Tables-->
<?php
//get classes options for speech
$sql="SELECT choices FROM classes_districts WHERE sport='sp'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$classes=split(",",$row[0]);

//get info already submitted
$sql="SELECT id, last, first, middle, semesters, eligible FROM eligibility WHERE school='$school2' AND sp='x' ORDER BY last";
$result=mysql_query($sql);
$ssst=array();
while($row=mysql_fetch_array($result))
{
	
	$ssst[]=	$row['id'];
}
$in=implode(',',$ssst);
 $sql="SELECT * FROM sp  WHERE school='$school2'  and student_id in (".$in.")";

$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row[2];
$contest_site=$row[3];
$emergname=$row[emergname];
$emergph=$row[emergph];
?>
<tr align=left>
<td>
   <table><!--School and Contest Info-->
   <tr align=left><th>School/Mascot:</th><td>
   <?php
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'sp');
$sql="SELECT * FROM spschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
echo GetSchoolName($sid,'sp')." $mascot";
   ?>
   </td>
   </tr>
   <tr align=left>
   <th>Colors:</th>
   <td><?php echo $colors; ?></td>
   </tr>
   <tr align=left>
   <th>Class & District:</th>
   <td><select name=class>
       <option>Choose
   <?php
   for($i=0;$i<count($classes);$i++)
   {
      echo "<option";
      if($class==$classes[$i]) echo " selected";
      echo ">$classes[$i]";
   }
   ?>
       </select>
   </td>
   </tr>
   <tr align=left>
   <th>Contest Site:</th>
   <td><input type=text name=contest_site size=50 value="<?php echo $contest_site; ?>">
   </td>
   </tr>
   <tr align=left>
   <th>NSAA-Certified Coach:</th>
   <td><?php echo $coach; ?></td>
   </tr>
   <tr align=left>
   <th>Assistant Coaches:</th>
   <td><input type=text size=50 name="asst" value="<?php echo $asst; ?>"></td>
   </tr>
   <tr align=left>
   <th colspan=2>Emergency Contact Person:&nbsp;
   <input type=text name=emergname size=30 value="<?php echo $emergname; ?>">
   &nbsp;&nbsp;Phone:&nbsp;
   (<input type=text name=area size=3 maxlength=3 value="<?php echo substr($emergph,0,3); ?>">)
   <input type=text name=pre size=3 maxlength=3 value="<?php echo substr($emergph,3,3); ?>">-
   <input type=text name=post size=4 maxlength=4 value="<?php echo substr($emergph,6,4); ?>">
   </th></tr>
   </table>
</td>
</tr>
<tr align=left>
<td>
   <table style=\"width:100%;border:#333333 1px solid;\" cellspacing=0 cellpadding=5 frame=all rules=all>
   <caption align=left><b>*&nbsp;<font style="font-size:9pt;">PLEASE NOTE:&nbsp;&nbsp;Make sure you CHECK THE BOX TO THE LEFT OF THE STUDENTS YOU WANT ENTERED ON THE FORM.  The form will NOT list the students on the submitted entry unless the box is checked next to their name.  Then proceed with checking the event(s) you want to enter that student in.</font></b></caption>
   <!--Participants Table-->
<?php
//column headers
$colheaders="
   <tr align=center>
   <th class=smaller rowspan=2 colspan=2>Entrant's Name</th>
   <th class=smaller rowspan=2>Grade</th>
   <th class=smaller rowspan=2>Drama<br>GROUP 1<br>(max: 5)</th>
   <th class=smaller rowspan=2>Drama<br>GROUP 2<br>(max: 5)</th>
   <th class=smaller rowspan=2>Poetry</th>
   <th class=smaller rowspan=2>Persuasive<br>Speaking</th>
   <th class=smaller rowspan=2>Informative<br>Public<br>Speaking</th>
   <th class=smaller rowspan=2>Extemporaneous<br>Speaking</th>
   <th class=smaller rowspan=2>Entertainment<br>Speaking</th>
   <th class=smaller rowspan=2>Duet Acting<br>GROUP 1<br>(2 students)</th>
   <th class=smaller rowspan=2>Duet Acting<br>GROUP 2<br>(2 students)</th>
   <th class=smaller colspan=2>Oral Interpretation of Prose</th>
   </tr>
   <tr align=center>
   <th class=smaller>Humorous</th>
   <th class=smaller>Serious</th>
   </tr>";
echo $colheaders;
$sql="SELECT id, last, first, middle, semesters, eligible FROM eligibility WHERE school='$school2' AND sp='x' ORDER BY last";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   //check if student has already been entered into db
   $submitted=0;
   for($i=0;$i<count($cur_id);$i++)
   {
      if($cur_id[$i]==$row[0]) 
      {
	 $submitted=1;
	 $index=$i;
      }
   }
   if($ix%10==0 && $ix!=0) echo $colheaders;
   echo "<tr align=center>";
   echo "<input type=hidden name=\"student[$ix]\" value=$row[0]>";
   echo "<td><input type=checkbox id=\"check".$ix."\" name=\"check[$ix]\" value=y";
   if($submitted==1 && $cur_checked[$index]=='y')
   {
      echo " checked";
   }
   echo "></td>";
   echo "<td align=left";
   if($row[5]!='y') echo " bgcolor=red";
   echo ">$row[1], $row[2] $row[3]</td>";
   $year=GetYear($row[4]);
   echo "<td>$year</td>";
   echo "<td><input type=checkbox name=\"drama1[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_drama1[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"drama2[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_drama2[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"poetry[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_poetry[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"pers_speak[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_pers_speak[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"inform[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_inform[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"extemp[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_extemp[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"ent_speak[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_ent_speak[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"duet_acting1[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_duet_acting1[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"duet_acting2[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; }\" value=y";
   if($submitted==1 && $cur_duet_acting2[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"prose_humor[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; window.open('prose.php?session=$session&school=$school2&name=prose_humor&id=$row[0]','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')}\" value=y";
   if($submitted==1 && $cur_prose_humor[$index]=='y') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"prose_serious[$ix]\" onClick=\"if(this.checked) { document.getElementById('check".$ix."').checked=true; window.open('prose.php?session=$session&school=$school2&name=prose_serious&id=$row[0]','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=400')}\" value=y";
   if($submitted==1 && $cur_prose_serious[$index]=='y') echo " checked";
   echo "></td>";
   echo "</tr>";
   $ix++;
}
?>
   </table>
</td>
</tr>
<tr align=center><td>
<?php
echo "<input type=button name=button onClick=\"window.open('coop_sp.php?session=$session&school=$school2','coop','menubar=no,location=no,resizable=no,scrollbars=yes,width=650,height=450')\" value=\"Add Co-Op Students\">";
?>
<!--Show Co-Op Students-->
<?php
$sql="SELECT * FROM sp WHERE co_op='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
?>
   <table style=\"width:100%;border:#333333 1px solid;\" cellspacing=0 cellpadding=5 frame=all rules=all>
   <tr align=center>
   <th class=smaller colspan=2 rowspan=2>School</th>
   <th class=smaller rowspan=2>Name</th>
   <th class=smaller rowspan=2>Grade</th>
   <th class=smaller rowspan=2>Drama<br>GROUP 1<br>(max: 5)</th>
   <th class=smaller rowspan=2>Drama<br>GROUP 2<br>(max: 5)</th>
   <th class=smaller rowspan=2>Poetry</th>
   <th class=smaller rowspan=2>Persuasive<br>Speaking</th>
   <th class=smaller rowspan=2>Informative<br>Public<br>Speaking</th>
   <th class=smaller rowspan=2>Extemporaneous<br>Speaking</th>
   <th class=smaller rowspan=2>Entertainment<br>Speaking</th>
   <th class=smaller rowspan=2>Duet Acting<br>GROUP 1<br>(2 students)</th>
   <th class=smaller rowspan=2>Duet Acting<br>GROUP 2<br>(2 students)</th>
   <th class=smaller colspan=2>Oral Interpretation of Prose</th>
   </tr>
   <tr align=center>
   <th class=smaller>Humorous</th>
   <th class=smaller>Serious</th>
   </tr>
<?php
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      //get info on co_op student
      $sql2="SELECT id, school, last, first, middle, semesters,eligible FROM eligibility WHERE id='$row[1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<tr align=center>";
      echo "<td><input type=checkbox id=\"coop_check".$i."\" name=\"coop_check[$i]\" value=y";
      if($row[checked]=='y') echo " checked";
      echo "></td><td>$row2[1]</td>";
      echo "<td";
      if($row2[6]!='y') echo " bgcolor=red";
      echo " align=left>$row2[2], $row2[3] $row2[4]</td>";
      echo "<input type=hidden name=\"coop_student[$i]\" value=$row2[0]>";
      $year=GetYear($row2[5]);
      echo "<td>$year</td>";
      echo "<td><input type=checkbox name=\"coop_drama1[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[drama1]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_drama2[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[drama2]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_poet[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[poetry]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_pers[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[pers_speak]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_inf[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[inform]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_ext[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[extemp]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_ent[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[ent_speak9]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_duet1[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[duet_acting1]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_duet2[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[duet_acting2]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_hum[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[prose_humor]=='y') echo " checked";
      echo "></td>";
      echo "<td><input type=checkbox name=\"coop_ser[$i]\" onClick=\"if(this.checked) { document.getElementById('coop_check".$i."').checked=true; }\" value=y";
      if($row[prose_serious]=='y') echo " checked";
      echo "></td>";
      echo "</tr>";
      $i++;
    }
    echo "</table>";
}
?>
</td></tr>
<tr align=left>
<td>
<font style="color:red">
*Students listed in red are currently <b>ineligible</b>.
Please make sure they will be eligible for the district contest
before submitting them on this form.
</font>
</td>
</tr>
<tr align=left>
<td>
<font size=2><i><?php echo $certify; ?></i></font><br><br>
</td>
</tr>
<tr align=center>
<td>
<input type=submit name=save value="Save & Keep Editing">
<input type=submit name=save value="Save & View Form">
<input type=submit name=save value="Cancel">
</td>
</tr>
</table><!--End Table of Tables-->
   </form>

</td><!--End Main-->
</tr>
</table>
</body>
</html>
