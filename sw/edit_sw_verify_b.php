<?php
//edit_sw_verify_b.php: SW Verification Form: blank version

require '../functions.php';
require '../variables.php';
require '../officials/variables.php';	//for autotab function
require 'swfunctions.php';

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
$sql2="SELECT id FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$schid=$row2[id];
//if(mysql_num_rows($result2)==0) exit();
$sql2="SELECT sid FROM swschool WHERE (mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid' OR school='$school
2')";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$schid=$row2[sid];

//delete entry if user clicked 'X'
if($delete && $delete!='' && $delete!='0')
{
   $sql="DELETE FROM sw_verify_perf_b WHERE id='$delete'";
   //echo "$sql<br>";
   $result=mysql_query($sql);
}

if($save)	//user clicked Save button
{
   //save to database
   //first, meet info:
   if($month=='MM' || $day=='DD')
      $meetdate="";
   else
      $meetdate=mktime(0,0,0,$month,$day,$year);
   $referee=ereg_replace("\'","\'",$referee);
   $referee=ereg_replace("\"","\'",$referee);
   $referee=trim($referee);
   $submitter=ereg_replace("\'","\'",$submitter);
   $submitter=ereg_replace("\"","\'",$submitter);
   $submitter=trim($submitter);

   $error=0;
   if($meetdate=="" || $referee=="" || $meetid==0 || $submitter=="" || !ereg("@",$subemail))
   {
      $error=1;
   }

   $today=time();
   //treat "Save & Submit" like "send" checkbox
   if($save=="Save & Submit" && $send=='y' && $error!='1')
      $send='y';
   else $send='n';
   $sql="SELECT id FROM sw_verify_b WHERE id='$formid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//meet not in DB yet
   {
      $sql2="INSERT INTO sw_verify_b (submitted,school,meetid,meetdate,referee,datesub,submitter,email) VALUES ('$send','$school2','$meetid','$meetdate','$referee','$today','$submitter','$subemail')";
      $result2=mysql_query($sql2);

      //get formid out
      $sql2="SELECT id FROM sw_verify_b WHERE school='$school2' AND datesub='$today'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $formid=$row2[0];
   }
   else					//update meet in DB
   {
      $sql2="UPDATE sw_verify_b SET submitted='$send', meetid='$meetid', meetdate='$meetdate', referee='$referee', datesub='$today',submitter='$submitter',email='$subemail' WHERE id='$formid'";
      $result2=mysql_query($sql2);
   }

   //next, individual performance info:
   $ix=0;	//index for array of students that did not meet qual marks
   $noqual=array();
   for($i=0;$i<10;$i++)
   {
      if($studevent[$i]!="Choose Event" && $stud[$i]!="Choose Student")
      {
	 //first check that performance entered meets qualifying standards
	 if(!ereg("Relay",$studevent[$i]))
	 {
	    $sql="SELECT last,first,middle,semesters,gender FROM eligibility WHERE id='$stud[$i]'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $curname="$row[last], $row[first] $row[middle] (".GetYear($row[3]).")";
	 }
	 else
	 {
	    $curname="";
	    for($j=0;$j<count($relaystud[$i]);$j++)
	    {
	       if($relaystud[$i][$j]!="Choose School")
	       {
		  $sql="SELECT last,first,middle,semesters,gender FROM eligibility WHERE id='".$relaystud[$i][$j]."'";
		  $result=mysql_query($sql);
		  $row=mysql_fetch_array($result);
		  $curname.="$row[last], $row[first] $row[middle] (".GetYear($row[3])."), ";
	       }
	    }
	    $curname=substr($curname,0,strlen($curname)-2);
	 }
	 $curevent="Boys ".$studevent[$i];
         $sql="SELECT qualmark FROM sw_qualify WHERE eventfull='$curevent'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 if($studevent[$i]!="Diving")
	 {
	    $qualmark=split("[:.]",$row[0]);
	    if(strlen($qualmark[2])==1) $qualmark[2].="0";
	    if(trim($min1[$i])=="" && trim($min2[$i])=="") $min[$i]="0";
	    else $min[$i]=$min1[$i].$min2[$i];
	    if(trim($sec1[$i])=="" && trim($sec2[$i])=="") $sec[$i]="00";
	    else if(trim($sec1[$i])=="") $sec[$i]="0".$sec2[$i];
	    else if(trim($sec2[$i])=="") $sec[$i]=$sec1[$i]."0";
	    else $sec[$i]=$sec1[$i].$sec2[$i];
	    if(trim($tenth1[$i])=="" && trim($tenth2[$i])=="") $tenth[$i]="00";
	    else if(trim($tenth1[$i])=="") $tenth[$i]="0".$tenth2[$i];
	    else if(trim($tenth2[$i])=="") $tenth[$i]=$tenth1[$i]."0";
	    else $tenth[$i]=$tenth1[$i].$tenth2[$i];
	    $curmark="$min[$i]:$sec[$i].$tenth[$i]";
	    $qualmark2="$qualmark[0]:$qualmark[1].$qualmark[2]";
	    $qualify=1;	//assume: student qualified
	    if(DoesQualify($curevent,$curmark)=="no") $qualify=0;

	    if($qualify==0)	//if student did not qualify, alert user
	    {
	       $noqual[$ix]=$studsch[$i]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curname."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curevent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$min[$i].":".$sec[$i].".".$tenth[$i]."&nbsp;(Qualifying Mark: $row[0])";
	       $ix++;
	    }
	    else	//if student did qualify, put in DB
	    {
	       if(ereg("Relay",$studevent[$i]))
	       {
		  $stud[$i]="";
		  for($j=0;$j<count($relaystud[$i]);$j++)
		  {
		     $stud[$i].=$relaystud[$i][$j]."/";
		  }
		  $stud[$i]=substr($stud[$i],0,strlen($stud[$i])-1);
                  //$performance=$min[$i].":".$sec[$i].".".$tenth[$i];
		  $performance=60*$min[$i];
		  $performance+=$sec[$i];
		  $performance.=".".$tenth[$i];
		  $sql2="INSERT INTO sw_verify_perf_b (formid,school,event,studentid,performance) VALUES ('$formid','$school2','$studevent[$i]','$stud[$i]','$performance')";
		  $result2=mysql_query($sql2);
		  echo mysql_error();
	       }
	       else
	       {
		  //$performance=$min[$i].":".$sec[$i].".".$tenth[$i];
		  $performance=60*$min[$i];
		  $performance+=$sec[$i];
		  $performance.=".".$tenth[$i];
	          $sql2="INSERT INTO sw_verify_perf_b (formid,school,event,studentid,performance) VALUES ('$formid','$school2','$studevent[$i]','$stud[$i]','$performance')";
	          $result2=mysql_query($sql2);
	       }
	    }
	 }
	 else	//Diving
	 {
	    $qualmarkdiv=$row[0];
	    if(trim($diving[$i])=="") $diving[$i]="0";
	    $qualify=1;	//assume: student qualified
	    if($diving[$i]<$qualmarkdiv)	//if mark did not meet standard
	    {
	       //student did not qualify
	       $qualify=0;
	    }
	    if($qualify==0)	//alert user
	    {
	       $noqual[$ix]=$studsch[$i]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curname."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curevent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$diving[$i]."&nbsp;(Qualifying Mark: $qualmarkdiv)";
	       $ix++;
	    }
	    else	//if student did qualify, put in DB
	    {
	       if(ereg("Relay",$studevent[$i]))
	       {
		  $stud[$i]="";
		  for($j=0;$j<count($relaystud[$i]);$j++)
		  {
		     $stud[$i].=$relaystud[$i][$j]."/";
		  }
		  $stud[$i]=substr($stud[$i],0,strlen($stud[$i])-1);
	       }
	       $sql2="INSERT INTO sw_verify_perf_b (formid,school,event,studentid,performance) VALUES ('$formid','$school2','$studevent[$i]','$stud[$i]','$diving[$i]')";
	       $result2=mysql_query($sql2);
	    }
	 }
      }//end if data entered
   }
   unset($studsch);
   unset($stud);
   unset($studevent);
   unset($diving);
   unset($min1); unset($sec1); unset($tenth1);
   unset($min2); unset($sec2); unset($tenth2);

   if($send=='y' && $error!=1)	//submit form to NSAA
   {
      header("Location:view_sw_verify_b.php?session=$session&school_ch=$school_ch&formid=$formid&send=y");
      exit();
   }
   /*
   else
   {
      header("Location:edit_sw_verify_b.php?session=$session&school_ch=$school_ch&formid=$formid&error=$error");
      exit();
   }
   */
}

echo $init_html;
echo $header;
?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php

//get list of eligible boy swimmers for this school/co-op
$sql="SELECT * FROM swschool WHERE sid='$schid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql="SELECT t1.* FROM eligibility AS t1, headers AS t2 WHERE t1.school=t2.school AND (t2.id='$row[mainsch]'";
if($row[othersch1]>0)
   $sql.=" OR t2.id='$row[othersch1]'";
if($row[othersch2]>0)
   $sql.=" OR t2.id='$row[othersch2]'";
if($row[othersch3]>0)
   $sql.=" OR t2.id='$row[othersch3]'";
$sql.=") AND t1.gender='M' AND t1.sw='x' AND t1.eligible='y' ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
$studch=array(); $i=0;
while($row=mysql_fetch_array($result))
{
   $studch[id][$i]=$row[id];
   $studch[name][$i]="$row[last], $row[first] $row[middle] (".GetYear($row[semesters]).")-$row[school]";
   $i++;
}


//get meet info out of database if already entered
$sql="SELECT * FROM sw_verify_b WHERE id='$formid'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $meetid=$row[meetid];
   if(!$meetdate)
   {
      $meetdate=$row[meetdate];
      $month=date("m",$meetdate); $day=date("d",$meetdate); $year=date("Y",$meetdate);
   }
   if(!$referee)
      $referee=$row[referee];
   if(!$submitter)
      $submitter=$row[submitter];
   if(!$subemail)
      $subemail=$row[email];
   $submitted=$row[submitted];
}
echo "<br>";

echo "<a href=\"view_sw_b.php?session=$session&school_ch=$school_ch\" class=small>Return to Swimming Home</a><br><br>";

//show students user entered that did not meet qual standards
if(count($noqual)>0)
{
   echo "<table><tr align=left><th class=smaller align=left><font style=\"color:red\">THE FOLLOWING STUDENTS' PERFORMANCES DO NOT MEET THE QUALIFYING STANDARDS FOR THE STATE MEET:</font></th></tr>";
   echo "<tr align=left><td align=left>";
   echo "<table><tr align=left><th class=smaller align=left>School</th><th class=smaller align=left>Name/Grade</th><th class=smaller align=left>Event</th><th align=left class=smaller>Performance</th></tr>";
   for($i=0;$i<count($noqual);$i++)
   {
      $temp=split("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$noqual[$i]);
      echo "<tr align=left><td align=left>$temp[0]&nbsp;&nbsp;&nbsp;</td>";
      echo "<td align=left>$temp[1]&nbsp;&nbsp;&nbsp;</td>";
      echo "<td align=left>$temp[2]</td>";
      echo "<td align=left>$temp[3]</td></tr>";
   }
   echo "</table></td></tr>";
   echo "<tr align=left><th class=smaller align=left><font style=\"color:red\">These students have not been saved in the database yet, but you may enter their entry again below if the<br>performance you entered was incorrect.  Make sure to hit \"Save\" after you make your changes!!</table>";
}

echo "<form name=swform method=post action=\"edit_sw_verify_b.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=formid value=$formid>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=delete value=''>";
echo "<table><caption><b>BOYS VERIFICATION FORM<br>of State Swimming and Diving Qualifying Performances</b><hr></caption>";
if($submitted!='y')	//form has not been submitted to NSAA yet
{
   echo "<tr align=left><td colspan=3 align=left><font style=\"color:red\"><b>This form has NOT been submitted to the NSAA yet.  To do so, you must check the box at the bottom of this page before clicking the \"Save & Submit\" button.<br>Also, the Meet Score Sheet MUST be FAXED to the NSAA office at (402)-489-0934.  The Verification Form wil not be complete or accepted until the Meet Score Sheet is received.</font></b></td></tr>";
}
if($error==1)	//user did not enter all fields at top of form
{
   echo "<tr align=left><td colspan=3 align=left><font style=\"color:red\"><br><b>You must complete all of the fields at the top of this form in order to send it to the NSAA.  Please complete ALL of these fields and then submit this form to the NSAA.</b></font></td></tr>";
}
echo "<tr align=left><td align=left colspan=3>NOTE: You may use the <b>TAB</b> key to move between fields.</td></tr>";
echo "<tr align=left valign=top><th class=smaller align=left>Date of Meet:</th>";
echo "<td><select class=small name=month><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($month==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select class=small name=day><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($day==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select class=small name=year>";
$curryr=date("Y"); $lastyr=$curryr-1; $nextyr=$curryr+1;
for($i=$lastyr;$i<=$nextyr;$i++)
{
   echo "<option";
   if($year==$i) echo " selected";
   else if(!$year && $i==$curryr) echo " selected";
   echo ">$i</option>";
}
echo "</select><input type=submit name=go value=\"Go\"><br>";
echo "(You must select a date and then click \"Go\" to select your meet.)</td>";
if($month=='' || $day=='' || $year=='')
   echo "<td></td></tr>";
else if($month!='' && $day!='' && $year!='')
{
//show qualifying times:
echo "<td align=left rowspan=5><table border=1 bordercolor=#000000 frame=box rules=none cellspacing=0 cellpadd
ing=3>";
echo "<caption><font style=\"font-size:8pt\"><b>Qualifying Standards:</b></font></caption>";
$sql="SELECT eventfull,qualmark,automark FROM sw_qualify WHERE eventfull LIKE 'Boys%' ORDER BY id";
$result=mysql_query($sql);
echo "<tr align=center><td><b>Event</b></td><td><b>Automatic</b></td><td><b>Secondary</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   $event=substr($row[0],5,strlen($row[0]));
   echo "<tr align=left><td align=left>$event</td>";
   echo "<td align=left>$row[2]</td>";
   echo "<td align=left>$row[1]</td></tr>";
}
echo "</table></td></tr>";
$meetdate="$year-$month-$day";
$sql="SELECT * FROM swsched WHERE meetdate='$meetdate' AND (sid='$schid' OR oppid='$schid') ORDER BY meetname";
$result=mysql_query($sql);
echo "<tr align=left valign=top><td><b>Meet:</b>";
echo "<td><select name=meetid><option value='0'>Please Select Meet</option>";
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($meetid==$row[id]) echo " selected";
   $meetname=GetMeetName($row[id]);
   echo ">$meetname</option>";
}
echo "</select><br>";
echo "The meets scheduled for $month/$day/$year are listed in the dropdown menu above.<br>Please select your meet from this list.";
echo "</td></tr>";
echo "<tr align=left><th class=smaller align=left>Meet Referee:</th>";
echo "<td align=left><input type=text name=referee value=\"$referee\" size=40></td></tr>";
echo "<tr align=left><th class=smaller align=left>Person Submitting (Name):</th>";
echo "<td align=left><input type=text name=submitter value=\"$submitter\" size=30></td>";
echo "<tr align=left valign=top><th class=smaller align=left>Person Submitting (E-mail):</th>";
echo "<td align=left><input type=text name=subemail value=\"$subemail\" size=30></td></tr>";


//individual performances table:
$studcoops=array();
//get info already in database
if($formid)
{
   $sql="SELECT * FROM sw_verify_perf_b WHERE formid='$formid'";
   $result=mysql_query($sql);
   $ix=0;
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=center><td colspan=3>";
      echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2>";
      echo "<tr align=center><td><b>Delete</b></td>";
      echo "<td><b>Event</b></td><td><b>Name/Grade</b></td><td><b>Performance</b></td>";
      echo "</tr>";
   }
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left><td align=center><a href=\"edit_sw_verify_b.php?session=$session&formid=$formid&delete=$row[id]&school_ch=$school_ch\">X</a></td>";
      echo "<td>$row[3]</td>";
      if(!ereg("Relay",$row[3]))
         echo "<td>".GetStudentInfo($row[4])."</td>";
      else 
      {
	 $relaymembers=split("/",$row[4]);
	 echo "<td>";
	 $members="";
         for($i=0;$i<count($relaymembers);$i++)
	 {
	    $members.=GetStudentInfo($relaymembers[$i]).", ";
         }
	 $members=substr($members,0,strlen($members)-2);
	 echo "$members</td>";
      }
      $perf=$row[5];
      if(ereg("Diving",$row[3]))
      {
	 echo "<td>$perf</td>";
      }
      else 
      {
	 if(ereg(":",$perf))
	    $perf2=split("[:.]",$perf);
	 else
	 {
	    $perf=ConvertFromSec($perf);
	    $perf2=split("[:.]",$perf);
	 }
	 if(strlen($perf2[0])==1)
	 {
	    echo "<td>$perf2[0]:";
	 }
	 else
	 {
	    echo "<td>".substr($perf2[0],0,1);
	    echo substr($perf2[0],1,1).":";
	 }
	 echo substr($perf2[1],0,1);
	 echo substr($perf2[1],1,1).".";
	 echo substr($perf2[2],0,1);
	 echo substr($perf2[2],1,1);
	 echo "</td>";
      }
   }
   if(mysql_num_rows($result)>0)
   {
       echo "</table></td></tr>";
   }
}

echo "<tr align=center><td colspan=3>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=1 width=750>";
echo "<caption align=left>The following students met or exceeded the state qualifying standards in the following events:<br>";
echo "<font style=\"color:red;font-size:9pt\">NOTE: If you have a <b>leadoff split time</b> that meets the automatic or secondary time, please <b>enter it as an individual event.<br><br>";
echo "**ONCE YOU ENTER 10 QUALIFIERS, click \"Save & Keep Editing\" to see 10 MORE LINES on the screen to put entries in.</b></font></caption>";
for($i=0;$i<10;$i++)
{
   if($i==0) 
   {
      $topofscreen=$i;
      echo "<tr align=center><th rowspan=2 class=smaller>";
      echo "<a name=\"$topofscreen\" href=\"#$topofscreen\"></a>";
      echo "Event</th><th rowspan=2 class=smaller>Name/Grade</th><th class=smaller colspan=2>Performance</th></tr>";
      echo "<tr align=center><th class=smaller>";
      echo "Swimming</th><th class=smaller>Diving</th></tr>";
   }
   echo "<tr align=center valign=top>";
   echo "<td><select class=small onchange=\"this.form.action+='#$topofscreen';submit();\" name=\"studevent[$i]\"><option>Choose Event";
   for($j=0;$j<count($sw_events);$j++)
   {
      echo "<option";
      if($sw_events[$j]==$studevent[$i]) echo " selected";
      echo ">$sw_events[$j]";
   }
   echo "</select></td>";
   for($k=0;$k<count($studch[id]);$k++)
   { 
      $sql2="SELECT t2.performance FROM sw_verify_b AS t1, sw_verify_perf_b AS t2 WHERE ((t1.id=t2.formid AND t1.submitted='y' AND t1.approved='y') OR t2.formid='0') AND t2.event='$studevent[$i]' AND t2.studentid='".$studch[id][$k]."' ORDER BY t2.performance LIMIT 1";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg(":",$row2[0]) || $studevent[$i]=='Diving') $curfasttimes[$j]=$row2[0];
      else $curfasttimes[$k]=ConvertFromSec($row2[0]);
      if(mysql_num_rows($result2)==0) $curfasttimes[$k]='0';
   }
   if(ereg("Relay",$studevent[$i]))
   {
      echo "<td align=left>";
      $relaystudtemp=split("/",$stud[$i]);
      for($j=0;$j<4;$j++)
      {
         echo "<select class=small name=\"relaystud[$i][$j]\"><option>Choose Student";
         for($k=0;$k<count($studch[id]);$k++)
         {
            echo "<option value=\"".$studch[id][$k]."\"";
	    if($relaystud[$i][$j]==$studch[id][$k]) echo " selected";
	    else if($relaystudtemp[$j]==$studch[id][$k]) echo " selected";
	    echo ">".$studch[name][$k]."</option>";
	 }
	 echo "</select>";
	 echo "<br>";
      }
      echo "</td>";
   }
   else
   {
      echo "<td>";
      echo "<select class=small name=\"stud[$i]\"><option>Choose Student";
      for($j=0;$j<count($studch[id]);$j++)
      {
         echo "<option value=\"".$studch[id][$j]."\"";
         if($studch[id][$j]==$stud[$i]) echo " selected";
         echo ">".$studch[name][$j];
         if($curfasttimes[$j]!='0' && $curfasttimes[$j]!="0:00.00") echo " ($curfasttimes[$j])";
         echo "</option>";
      }
      echo "</select></td>";
   }
   echo "<td><input type=text size=1 onKeyUp='return autoTab(this,1,event);' maxlength=1 name=\"min1[$i]\" value=\"$min1[$i]\"><input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"min2[$i]\" value=\"$min2[$i]\"><b>:</b>";
   echo "<input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"sec1[$i]\" value=\"$sec1[$i]\"><input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"sec2[$i]\" value=\"$sec2[$i]\"><b>.</b>";
   echo "<input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"tenth1[$i]\" value=\"$tenth1[$i]\"><input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"tenth2[$i]\" value=\"$tenth2[$i]\">";
   echo "</td>";
   echo "<td><input type=text size=4 name=\"diving[$i]\" value=\"$diving[$i]\"></td></tr>";
}
echo "</table></td></tr>";
echo "<tr align=center><th colspan=3><font style=\"color:red;font-size:9pt;\"><b>**ONCE YOU ENTER 10 QUALIFIERS, click \"Save & Keep Editing\" to see 10 MORE LINES on the screen to put entries in.</b></font></th></tr>";
echo "<tr align=left><th colspan=3 align=left><input type=checkbox name=send value='y'>&nbsp;<i>I certify that the performances listed were established at the above designated meet.</i><br>";
echo "<font style=\"color:blue\"><b>DO NOT CHECK BOX UNTIL ALL RESULTS HAVE BEEN ENTERED.</b></font></th></tr>";
echo "<tr align=left><td colspan=3 align=left>NOTE: You may click \"Save & Keep Editing\" without checking this box, and the information you have entered for this meet will be saved for you to continue working on.";
echo "<br><font style=\"color:red\">However, <b>this verification form is not sent to the NSAA until you check the above box BEFORE clicking \"Save & Submit\"</b> AND you complete ALL fields at the top of this form.<br>";
echo "Also, <b>the Meet Score Sheet MUST be FAXED to the NSAA office</b> at (402)489-0934.  The Verification Form will not be complete or accepted until the Meet Score Sheet is received.</font></td></tr>";
echo "<tr align=center><td colspan=3><input type=submit name=save value=\"Save & Keep Editing\">";
echo "&nbsp;&nbsp;<input type=submit name=save value=\"Save & Submit\"></td></tr>";
echo "</table>";
}//end if date selected
echo "</form>";

echo $end_html;
?>
