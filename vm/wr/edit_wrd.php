<?php
/****************************
edit_wrd.php 
Dual Wrestling Roster Form
Created 1/14/16 in order to 
auto-populate State Program pages
by Ann Gaffigan
*****************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
$level=GetLevel($session);
if(!$school_ch || $level!=1)
   $school=GetSchool($session);
else
   $school=$school_ch;
$school2=ereg_replace("\'","\'",$school);
$schoolid=GetSchoolID2($school);

//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; 
$sport="wr";
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


//Get Coaches, Mascot & Colors
$coach=GetCoaches($schoolid,'wr');
$asst=GetAsstCoaches($schoolid,'wr');
$mascot=GetMascot($schoolid,'wr');
$colors=GetColors($schoolid,'wr');
$sid=GetSID2($school,'wr');
$class=GetClass($sid,'wr');
$sql="SELECT * FROM wrschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result) && citgf_file_exists("../downloads/".$row[filename]))
{
   $filename=$row[filename];
}
else
{
   $filename="";
}

//Get Wrestling Form Due Date
$duedate=GetDueDate('wrd');
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//CHECK IF IT IS >2 DAYS PAST THE DUE DATE FOR THIS FORM
//Changed 1/29/07 to NO GRACE PERIOD
if(PastDue($duedate,0) && $level!=1 && $school!="Test's School")
{
   $late_page=GetLatePage($duedate2);
   echo $init_html.GetHeader($session);
   echo $late_page;
   echo "<br><br>";
   //check if the form had been edited yet:
   $sql="SELECT * FROM wrd WHERE (school='$school2' OR co_op='$school2') AND checked='y'";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct>0)
   {
      echo "<a href=\"view_wrd.php?session=$session&school_ch=$school_ch\">";
      echo "View your Submitted Form</a>";
   }
   else
   {
      echo "<font size=2>";
      echo "No information was submitted for your team.<br>";
      echo "If this was a mistake, please contact the NSAA immediately!";
      echo "<br><br>";
      echo "<a href=\"../welcome.php?session=$session\">Return Home</a></font>";
   }
   exit();
}

//If form has already been submitted, get info from db:
$sql="SELECT * FROM wrd WHERE (school='$school2' OR co_op='$school2')";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $cur_checked[$ix]=$row[checked];
   $cur_id[$ix]=$row[student_id];
   $cur_weight[$ix]=$row[weight];
   $cur_record[$ix]=$row[record];
   $ix++;
   $submittedtoNSAA=$row['submitted'];
}

echo $init_html;
echo GetHeader($session)."<br>";
?>
<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase() != 'TD' && element != null)
   {
      element=document.all ? element.parentElement : element.parentNode;
   }
   if(element)
   {
      element.bgColor="FFFF33";
   }
}
</script>
<?php
if($level==1)
   echo "<p style=\"text-align:left\"><a href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Wrestling\">Return to Home &rarr; Wrestling Entry Forms</a></p>";
?>
<form method=post action="submit_wrd.php" name="form1">
<input type=hidden name="session" value="<?php echo $session; ?>">
<input type=hidden name="school_ch" value="<?php echo $school_ch; ?>">
<h1>NSAA Dual Wrestling Roster Form</h1>
<p><i>Due <b><?php echo $duedate2; ?></b></i></p>
<table width='700px' cellspacing=0 cellpadding=5 style="border:#808080 1px solid;" frame="all" rules="all">
<caption>
<div style="text-align:left;">
<p><b>School/Mascot:</b> <?php echo GetSchoolName($sid,'wr')." $mascot"; ?></p>
<p><b>Class:</b> <?php echo $class; ?></p>
<p><b>Colors:</b> <?php echo $colors; ?></p>
<p><b>NSAA-Certified Coach:</b> <?php echo $coach; ?></p>
<p><b>Assistant Coaches:</b> <input type=text name=asst size=50 value="<?php echo $asst; ?>"></p>
<br>
<table>
<?php
if($level==1)
{
            $sql_id="SELECT * FROM headers WHERE school='$school2'";
      $result_id=mysql_query($sql_id);
      $row_id=mysql_fetch_array($result_id);
      
	  $sql_coop="SELECT * FROM wrschool WHERE mainsch='$row_id[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
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
      $conf=$row[conference];
	  if (!empty($enroll))
      echo "<tr align=\"left\"><td><b>NSAA Enrollment:</b></td><td><input type=text name=\"enrollment\" value=\"$enroll\" size=5></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>NSAA Enrollment:</b></td><td><input type=text name=\"enrollment\" value=\"$enrollment\" size=5></td></tr>";
      echo "<tr align=\"left\"><td><b>Conference:</b></td><td><input type=text name=\"conference\" value=\"$conf\" size=30></td></tr>";
      $sql="SELECT * FROM ".GetSchoolsTable($sport)." WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Qualifications to State: 4
      echo "<tr align=\"left\"><td><b>Qualifications to State:</b></td><td><input type=text name=\"tripstostate\" size=10 value=\"$row[tripstostate]\"></td></tr>";
        //Most Recent: 2012
      echo "<tr align=\"left\"><td><b>Most Recent:</b></td><td><input type=text name=\"mostrecent\" size=10 value=\"$row[mostrecent]\"></td></tr>";
        //Championships: None
      echo "<tr align=\"left\"><td><b>Championships:</b></td><td><input type=text name=\"championships\" size=20 value=\"$row[championships]\"></td></tr>";
        //Runner-up: B/2008, B/2010
      echo "<tr align=\"left\"><td><b>Runner-up:</b></td><td><input type=text name=\"runnerup\" size=20 value=\"$row[runnerup]\"></tr>";
}
?>
</table>
<ul>
<li>Check the box next to the name of each student who will be participating in the state tournament.</li>
<li>Then select the weight class for that student and enter the student's win-loss record.</li>
</ul>
</div>
</caption>
<tr align=center>
<th colspan=2>Name (Grade)</th><th>Weight Class</th><th>Record</th></tr>
<?php
//get all wrestling participants, boys and girls
$studs=explode("<result>",GetPlayers('wr',$school));
$ix=0;
for($s=0;$s<count($studs);$s++)
{
   $stud=explode("<detail>",$studs[$s]);	//id, name/grade, ?, eligible
   if($ix%10==0 && $ix>0)
   echo "<tr align=center><th colspan=2>Name (Grade)</th><th>Weight Class</th><th>Record</th></tr>";
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
   echo "<input type=checkbox name=\"check[$ix]\" value=y";
   if($submitted==1 && $cur_checked[$index]=="y") echo " checked";
   echo "></td>";
   echo "<td";
   if($stud[3]!="y") echo " bgcolor=red";
   echo " align=left>$stud[1]</td>";
   echo "<td><select name=\"weight[$ix]\">";
   echo "<option>~";
   for($i=0;$i<count($weights);$i++)
   {
      echo "<option";
      if($submitted==1 && $cur_weight[$index]==$weights[$i])
         echo " selected";
      echo ">$weights[$i]";
   }
   echo "</select>&nbsp;lbs</td>";
   echo "<td><input type=text name=\"win[$ix]\" size=2";
   if($submitted==1)
   {
      $rec=split("-",$cur_record[$index]);
      echo " value=\"$rec[0]\"";
   }
   echo "><b>&nbsp;W</b>&nbsp;&nbsp;";
   echo "<input type=text name=\"loss[$ix]\" size=2";
   if($submitted==1) echo " value=\"$rec[1]\"";
   echo "><b>&nbsp;L</b>";
   echo "<input type=hidden name=\"id[$ix]\" value=\"$stud[0]\"></td></tr>";
   $ix++;
}
echo "</table>";
?>
<p style="color:red">Students listed in red are currently <b>ineligible</b>.  Please make sure they will be eligible for the tournament before submitting them on this form.</p>
<?php
/****** DUAL MEETS FROM THEIR SCHEDULE *******/
$sql="SELECT * FROM wrdsched WHERE (sid='$sid') ORDER BY received";
$result=mysql_query($sql);
//echo $sql;
echo "<h3>Dual Wrestling Schedule for $school:</h3>";
echo "<p>For each dual, enter your opponent, your score and your opponent's score. The W/L column will be automatically populated after you enter the scores.</p>";
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\"><tr align=center><th>Opponent</th><th>Date</th><th>W/L</th><th>Your Score</th><th>Opponent's Score</th><th>Delete</th></tr>";
$i=0;
while($row=mysql_fetch_array($result))
{
   if($sid==$row[sid])
   {
      $oppname=GetSchoolName($row[oppid],'wr');
      $oppscore=$row[oppscore];
      $sidscore=$row[sidscore];
      $oppsid=$row[oppid];
   }
   else
   {
      $oppname=GetSchoolName($row[sid],'wr');
      $oppscore=$row[sidscore];
      $sidscore=$row[oppscore];
      $oppsid=$row[sid];
   }
   if($sidscore>$oppscore) $winloss="W";
   else if($oppscore>$sidscore) $winloss="L";
   else $winloss="&nbsp;";
   $date=explode("-",$row[received]);
   echo "<tr align=center><td align=left><input type=\"hidden\" name=\"scoreid[$i]\" value=\"$row[scoreid]\"><input type=\"hidden\" name=\"oppid[$i]\" value=\"$oppsid\">$oppname</td>";
   echo "<td><select name=\"mo[$i]\"><option value=\"00\">MM</option>";
   for($j=1;$j<=12;$j++)
   {
      if($j<10) $m="0".$j;
      else $m=$j; 
      echo "<option value=\"$m\"";
      if($date[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"day[$i]\"><option value=\"00\">DD</option>";
   for($j=1;$j<=31;$j++)
   {
      if($j<10) $d="0".$j;
      else $d=$j;
      echo "<option value=\"$d\"";
      if($date[2]==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"yr[$i]\">";
   if(date("m")<6)
      $year1=date("Y")-1; 
   else
      $year1=date("Y");
   $year2=$year1+1;
   for($j=$year1;$j<=$year2;$j++)
   {
      echo "<option value=\"$j\"";
      if($date[0]==$j) echo " selected";
      echo ">$j</option>";
   }
   echo "</select></td>";
   echo "<td>$winloss</td><td><input type=text name=\"sidscore[$i]\" value=\"$sidscore\" size=4></td><td><input type=text name=\"oppscore[$i]\" value=\"$oppscore\" size=4></td><td><input type=\"checkbox\" name=\"delete[$i]\" value=\"x\"></td></tr>";
   $i++;
}	//END DUALS ALREADY IN THE DATABASE FOR THIS TEAM
//GET LIST OF OPPONENTS
$opps=array(); $opps[sid]=array(); $opps[name]=array();
$sql="SELECT sid,school FROM wrschool ORDER BY school";
$result=mysql_query($sql);
$j=0; 
while($row=mysql_fetch_array($result))
{
   $opps[sid][$j]=$row[sid];
   $opps[name][$j]=$row[school];
   $j++;
}
$max=$i+5;
while($i<$max)
{
   echo "<tr align=center><td align=left><input type=\"hidden\" name=\"scoreid[$i]\" value=\"0\"><select name=\"oppid[$i]\"><option value=\"0\">Select Opponent</option>";
   for($j=0;$j<count($opps[sid]);$j++)
   {
      echo "<option value=\"".$opps[sid][$j]."\">".$opps[name][$j]."</option>";
   }
   echo "</select></td>";
   echo "<td><select name=\"mo[$i]\"><option value=\"00\">MM</option>";
   for($j=1;$j<=12;$j++)
   {
      if($j<10) $m="0".$j;
      else $m=$j; 
      echo "<option value=\"$m\">$m</option>";
   }
   echo "</select>/<select name=\"day[$i]\"><option value=\"00\">DD</option>";
   for($j=1;$j<=31;$j++)
   {
      if($j<10) $d="0".$j;
      else $d=$j;
      echo "<option value=\"$d\">$d</option>";
   }
   echo "</select>/<select name=\"yr[$i]\">";
   if(date("m")<6)
      $year1=date("Y")-1; 
   else
      $year1=date("Y");
   $year2=$year1+1;
   for($j=$year1;$j<=$year2;$j++)
   {
      echo "<option value=\"$j\"";
      if($j==date("Y")) echo " selected";
      echo ">$j</option>";
   }
   echo "</select></td>";
   echo "<td>&nbsp;</td><td><input type=text name=\"sidscore[$i]\" size=4></td><td><input type=text name=\"oppscore[$i]\" size=4></td><td>n/a</td></tr>";
   $i++;
}
echo "</table>";
echo "<p>If you need to add MORE duals to your schedule, please click \"Save and Keep Editing,\" and you will be shown 5 more spaces to do so.</p>";
?>
<br />
<div class='alert'>
<?php
if($submittedtoNSAA>0)  //ALREADY SUBMITTED
{
   echo "<p><i>You already checked the box below and submitted your <u><b>FINAL ROSTER</u></b> for the State Tournament on ".date("F j, Y",$submittedtoNSAA)." at ".date("g:ia T",$submittedtoNSAA).".</i></p>
	<p><input type=\"checkbox\" name=\"send\" value=\"y\"> Check this box AGAIN if you've made <b>UPDATES to the information</b> since you last submitted it to the NSAA - the NSAA will be notified that your information has changed.</p>";
}
else	//NOT YET SUBMITTED
{
   echo '<p><input type="checkbox" name="send" value="y">
	Check this box if the information about is your <b><u>final roster for the State Tournament</b></u>.  You have not officially submitted your roster to the NSAA until you have checked this box and clicked one of the two "Save" buttons below!</p>';
}
?>
</div>
<h3 style="font-weight:normal;"><i><?php echo $certify; ?></i></h3>
<input type="submit" name="save" value="Save and Keep Editing" class="fancybutton">
    <input type="submit" name="save" value="Save and View Form" class="fancybutton">
    <input type="submit" name="save" value="Cancel" class="fancybutton">
</form>
<?
echo $end_html;
?>
