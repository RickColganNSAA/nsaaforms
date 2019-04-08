<?php
//edit_sw_verify_b.php: SW Verification Form: blank version

require '../functions.php';
require '../variables.php';
require '../officials/variables.php';	//for autotab function

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

if($save)	//user clicked Save button
{
   //save to database
   //first, meet info:
   if($month=='MM' || $day=='DD')
      $meetdate="";
   else
      $meetdate=mktime(0,0,0,$month,$day,$year);
   $site=ereg_replace("\'","\'",$site);
   $site=ereg_replace("\"","\'",$site);
   $site=trim($site);
   $referee=ereg_replace("\'","\'",$referee);
   $referee=ereg_replace("\"","\'",$referee);
   $referee=trim($referee);
   $submitter=ereg_replace("\'","\'",$submitter);
   $submitter=ereg_replace("\"","\'",$submitter);
   $submitter=trim($submitter);

   if(!$meetname || $meetname=="")
   {
   $meet=$schsel1." VS ";
   if(trim($schtext2)!="")
      $meet.="$schtext2 VS ";
   else
      $meet.="$schsel2 VS ";
   if(trim($schtext3)!="")
      $meet.="$schtext3 VS ";
   else
      $meet.="$schsel3 VS ";
   if(trim($schtext4)!="")
   {
      $meet.=$schtext4;
   }
   else
   {
      $meet.=$schsel4;
   }
   $meet=ereg_replace(" VS Choose School","",$meet);
   $meet=ereg_replace("Choose School","",$meet);
   $meet=ereg_replace("\'","\'",$meet);
   $meet=ereg_replace("\"","\'",$meet);
   $meet=trim($meet);
   }
   else $meet=$meetname;

   $error=0;
   if($meetdate=="" || $site=="" || $referee=="" || $meet=="" || $submitter=="" || !ereg("@",$subemail))
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
      $sql2="INSERT INTO sw_verify_b (submitted,school,meet,meetdate,site,referee,datesub,submitter,email) VALUES ('$send','$school2','$meet','$meetdate','$site','$referee','$today','$submitter','$subemail')";
      $result2=mysql_query($sql2);

      //get formid out
      $sql2="SELECT id FROM sw_verify_b WHERE school='$school2' AND datesub='$today'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $formid=$row2[0];
   }
   else					//update meet in DB
   {
      $sql2="UPDATE sw_verify_b SET submitted='$send', meet='$meet', meetdate='$meetdate', site='$site', referee='$referee', datesub='$today',submitter='$submitter',email='$subemail' WHERE id='$formid'";
      $result2=mysql_query($sql2);
   }

   //next, individual performance info:
      //remove old data
      $sql="DELETE FROM sw_verify_perf_b WHERE formid='$formid'";
      $result=mysql_query($sql);
   $ix=0;	//index for array of students that did not meet qual marks
   $noqual=array();
   for($i=0;$i<count($studsch);$i++)
   {
      if($studsch[$i]!="Choose School" && $studevent[$i]!="Choose Event" && $stud[$i]!="Choose Student")
      {
	 //first check that performance entered meets qualifying standards
	 if(!ereg("Relay",$studevent[$i]))
	 {
	    $sql="SELECT last,first,middle,semesters,gender FROM eligibility WHERE id='$stud[$i]'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $curname="$row[1] $row[0] (".GetYear($row[3]).")";
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
		  $curname.="$row[1] $row[0] (".GetYear($row[3])."), ";
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
	       $temp=ereg_replace("\'","\'",$studsch[$i]);
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
		  $sql2="INSERT INTO sw_verify_perf_b (formid,school,event,studentid,performance) VALUES ('$formid','$temp','$studevent[$i]','$stud[$i]','$performance')";
		  $result2=mysql_query($sql2);
		  echo mysql_error();
	       }
	       else
	       {
		  //$performance=$min[$i].":".$sec[$i].".".$tenth[$i];
		  $performance=60*$min[$i];
		  $performance+=$sec[$i];
		  $performance.=".".$tenth[$i];
	          $sql2="INSERT INTO sw_verify_perf_b (formid,school,event,studentid,performance) VALUES ('$formid','$temp','$studevent[$i]','$stud[$i]','$performance')";
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
	       $temp=ereg_replace("\'","\'",$studsch[$i]);
	       $sql2="INSERT INTO sw_verify_perf_b (formid,school,event,studentid,performance) VALUES ('$formid','$temp','$studevent[$i]','$stud[$i]','$diving[$i]')";
	       $result2=mysql_query($sql2);
	    }
	 }
      }
   }

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

//get list of swimming schools
$sql="SELECT school, hytekabbr,coops FROM sw_schools ORDER BY school";
$result=mysql_query($sql);
$i=0;
$sch=array();
while($row=mysql_fetch_array($result))
{
   $sch[name][$i]=$row[0];
   $sch[abbr][$i]=$row[1];
   $sch[coops][$i]=$row[2];
   $i++;
}

//get meet info out of database if already entered
$sql="SELECT * FROM sw_verify_b WHERE id='$formid'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $meet=$row[3]; $meetname=$row[3];
   /*
   $meet=split(" VS ",$row[3]);
   if(!$schsel1 || $schsel1=="Choose School") $schsel1=$meet[0];
   if((!$schsel2 || $schsel2=="Choose School") && trim($schtext2)=="") 
   {
      $schsel2=$meet[1];
   }
   if((!$schsel3 || $schsel3=="Choose School") && trim($schtext3)=="")
   {
      $schsel3=$meet[2];
   }
   if((!$schsel4 || $schsel4=="Choose School") && trim($schtext4)=="")
   {
      $schsel4=$meet[3];
   }
   */
   if(!$meetdate)
   {
      $meetdate=$row[4];
      $month=date("m",$meetdate); $day=date("d",$meetdate); $year=date("Y",$meetdate);
   }
   if(!$site)
      $site=$row[5];
   if(!$referee)
      $referee=$row[6];
   if(!$submitter)
      $submitter=$row[8];
   if(!$subemail)
      $subemail=$row[9];
   $submitted=$row[1];
}
echo "<center><br>";

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
echo "<tr align=left><th class=smaller align=left>Person Submitting (Name):</th>";
echo "<td align=left><input type=text name=submitter value=\"$submitter\" size=30></td>";
//show qualifying times:
echo "<td align=left rowspan=6><table border=1 bordercolor=#000000 frame=box rules=none cellspacing=0 cellpadding=3>";
echo "<caption><font style=\"font-size:8pt\"><b>Qualifying Standards:</b></font></caption>";
$sql="SELECT eventfull,qualmark FROM sw_qualify WHERE eventfull LIKE 'Boys%' ORDER BY id";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $event=substr($row[0],5,strlen($row[0]));
   echo "<tr align=left><td align=left>$event</td>";
   echo "<td align=left>$row[1]</td></tr>";
}
echo "</table></td></tr>";
echo "<tr align=left valign=top><th class=smaller align=left>Person Submitting (E-mail):</th>";
echo "<td align=left><input type=text name=subemail value=\"$subemail\" size=30></td></tr>";
echo "<tr align=left valign=top><th class=smaller align=left>Meet:</th>";
echo "<td align=left>";
echo "<b>Name of Meet: <input type=text class=tiny name=\"meetname\" value=\"$meetname\" size=40><br>";
echo "OR Enter the Schools Competing in This Meet:<br></b>";
echo "<select class=small name=schsel1><option>Choose School";
for($i=0;$i<count($sch[name]);$i++)
{
   echo "<option";
   if($schsel1==$sch[name][$i]) 
      echo " selected";
   echo ">".$sch[name][$i];
}
echo "</select><br><b>VS.</b><br>";
echo "<select class=small name=schsel2><option>Choose School";
$foundsch=0;
for($i=0;$i<count($sch[name]);$i++)
{
   echo "<option";
   if($schsel2==$sch[name][$i]) 
   {
      echo " selected";
      $foundsch=1;
   }
   echo ">".$sch[name][$i];
}
echo "</select><br>";
if($foundsch==0 && $schsel2!="Choose School")	
{
   //school entered in db was from out of state field
   $schtext2=$schsel2;
}
echo "<input type=text name=\"schtext2\" size=30 value=\"$schtext2\"> (enter out-of-state school here)<br>";
echo "<b>VS.</b><br>";
echo "<select class=small name=schsel3><option>Choose School";
$foundsch=0;
for($i=0;$i<count($sch[name]);$i++)
{
   echo "<option";
   if($schsel3==$sch[name][$i]) 
   {
      echo " selected";
      $foundsch=1;
   }
   echo ">".$sch[name][$i];
}
echo "</select><br>";
if($foundsch==0 && $schsel3!="Choose School")
{
   $schtext3=$schsel3;
}
echo "<input type=text name=\"schtext3\" size=30 value=\"$schtext3\"> (enter out-of-state school here)<br>";
echo "<b>VS.</b><br>";
echo "<select class=small name=schsel4><option>Choose School";
$foundsch=0;
for($i=0;$i<count($sch[name]);$i++)
{
   echo "<option";
   if($schsel4==$sch[name][$i]) 
   {
      echo " selected";
      $foundsch=1;
   }
   echo ">".$sch[name][$i];
}
echo "</select><br>";
if($foundsch==0 && $schsel4!="Choose School")
{
   $schtext4=$schsel4;
}
echo "<input type=text name=\"schtext4\" size=30 value=\"$schtext4\"> (enter out-of-state school here)";
echo "</td></tr>";
echo "<tr align=left><th class=smaller align=left>Date of Meet:</th>";
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
$curryr=date("Y"); $lastyr=$curryr-1;
for($i=$lastyr;$i<=$curryr;$i++)
{
   echo "<option";
   if($year==$i) echo " selected";
   else if(!$year && $i==$curryr) echo " selected";
   echo ">$i</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><th class=smaller align=left>Site:</th>";
echo "<td align=left><input type=text name=site value=\"$site\" size=40></td></tr>";
echo "<tr align=left><th class=smaller align=left>Meet Referee:</th>";
echo "<td align=left><input type=text name=referee value=\"$referee\" size=40></td></tr>";


//individual performances table:
$studcoops=array();
//get info already in database
if($formid)
{
   $sql="SELECT * FROM sw_verify_perf_b WHERE formid='$formid'";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if(!$studsch[$ix] || $save) $studsch[$ix]=$row[2];
      if(!$studevent[$ix] || $save) $studevent[$ix]=$row[3];
      if(!$stud[$ix] || $save) $stud[$ix]=$row[4];
      $perf=$row[5];
      if(ereg("Diving",$studevent[$ix]))
      {
	 if(!$diving[$ix] || $save) $diving[$ix]=$perf;
	 $min1[$ix]=""; $min2[$ix]="";
	 $sec1[$ix]=""; $sec2[$ix]="";
	 $tenth1[$ix]=""; $tenth2[$ix]="";
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
	 if((!$min1[$ix] && !$min2[$ix]) || $save) 
	 {
	    if(strlen($perf2[0])==1)
	    {
	       $min1[$ix]="";
	       $min2[$ix]=$perf2[0];
	    }
	    else
	    {
	       $min1[$ix]=substr($perf2[0],0,1);
	       $min2[$ix]=substr($perf2[0],1,1);
	    }
	 }
	 if((!$sec1[$ix] && !$sec2[$ix]) || $save)
	 {
	    $sec1[$ix]=substr($perf2[1],0,1);
	    $sec2[$ix]=substr($perf2[1],1,1);
	 }
	 if((!$tenth1[$ix] && !$tenth2[$ix]) || $save)
	 {
	    $tenth1[$ix]=substr($perf2[2],0,1);
	    $tenth2[$ix]=substr($perf2[2],1,1);
	 }
	 $diving[$ix]="";
      }
      //reset rows that have duplicate info
      for($i=0;$i<count($stud);$i++)
      {
	 if($ix!=$i && $stud[$i]==$stud[$ix] && $studevent[$i]==$studevent[$ix])
	 {
	    $stud[$i]="Choose Student";
	    $studevent[$i]="Choose Event";
	    $studsch[$i]="Choose School";
	    $studcoops[$i]="";
	    $min1[$i]=""; $min2[$i]="";
	    $sec1[$i]=""; $sec2[$i]="";
	    $tenth1[$i]=""; $tenth2[$i]="";
	    $diving[$i]="";
	 }
      }
      $ix++;
   }
}

echo "<tr align=center><td colspan=3>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=1>";
echo "<caption align=left>The following students met or exceeded the state qualifying standards in the following events:<br>";
echo "<font style=\"color:red;font-size:9pt\">NOTE: If you have a <b>leadoff split time</b> that meets the automatic or secondary time, please <b>enter it as an individual event.</b></font></caption>";
for($i=0;$i<5;$i++)
{
   if($i==0) 
   {
      $topofscreen=$i;
      echo "<tr align=center><th rowspan=2 class=smaller>";
      echo "<a name=\"$topofscreen\" href=\"#$topofscreen\"></a>";
      echo "School</th><th rowspan=2 class=smaller>Event</th><th rowspan=2 class=smaller>Name/Grade</th><th class=smaller colspan=2>Performance</th></tr>";
      echo "<tr align=center><th class=smaller>";
      echo "Swimming</th><th class=smaller>Diving</th></tr>";
   }
   echo "<tr align=center valign=top>";
   echo "<td><select class=small name=\"studsch[$i]\" onchange=\"this.form.action+='#$topofscreen';submit();\"><option>Choose School";
   if(!$studsch[$i] || $studsch[$i]=="Choose School")
   {
      //if no school selected, use one from row above
      $studsch[$i]=$studsch[$i-1];
   }
   for($j=0;$j<count($sch[name]);$j++)
   {
      echo "<option";
      if($sch[name][$j]==$studsch[$i]) 
      {
	 echo " selected";
	 $studcoops[$i]=$sch[coops][$j];
      }
      echo ">".$sch[name][$j];
   }
   echo "</select></td>";
   echo "<td><select class=small onchange=\"this.form.action+='#$topofscreen';submit();\" name=\"studevent[$i]\"><option>Choose Event";
   for($j=0;$j<count($sw_events);$j++)
   {
      echo "<option";
      if($sw_events[$j]==$studevent[$i]) echo " selected";
      echo ">$sw_events[$j]";
   }
   echo "</select></td>";
   //get students from selected school
   unset($curstuds);
   unset($curnames);
   if($studsch[$i]!="Choose School")
   {
      if(ereg("/",$studcoops[$i]))
      {
	 $studcoops[$i]=ereg_replace("\'","\'",$studcoops[$i]);
         $studschs=split("/",$studcoops[$i]);
         $sql="SELECT id,last, first, middle, semesters FROM eligibility WHERE (";
	 for($j=0;$j<count($studschs);$j++)
	 {
	    $sql.="school='$studschs[$j]' OR ";
	 }
	 $sql=substr($sql,0,strlen($sql)-4);
	 $sql.=") AND sw='x' AND gender='M' ORDER BY last";
      }
      else
      {
         $studsch2[$i]=ereg_replace("\'","\'",$studsch[$i]);
         $sql="SELECT id,last,first,middle,semesters FROM eligibility WHERE school='$studsch2[$i]' AND sw='x' AND gender='M' ORDER BY last";
      }
      $result=mysql_query($sql);
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
	 $curstuds[$ix]=$row[0];
	 $curnames[$ix]="$row[1], $row[2] $row[3] (".GetYear($row[4]).")";
         $sql2="SELECT t2.performance FROM sw_verify_b AS t1, sw_verify_perf_b AS t2 WHERE ((t1.id=t2.formid AND t1.submitted='y' AND t1.approved='y') OR t2.formid='0') AND t2.event='$studevent[$i]' AND t2.studentid='$row[0]' ORDER BY t2.performance LIMIT 1";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if(ereg(":",$row2[0]) || $studevent[$i]=='Diving') $curfasttimes[$ix]=$row2[0];
	 else $curfasttimes[$ix]=ConvertFromSec($row2[0]);
         if(mysql_num_rows($result2)==0) $curfasttimes[$ix]='0';
	 $ix++;
      }
   }
      if(ereg("Relay",$studevent[$i]))
      {
	 echo "<td align=left>";
	 $relaystudtemp=split("/",$stud[$i]);
	 for($j=0;$j<4;$j++)
	 {
	    echo "<select class=small name=\"relaystud[$i][$j]\"><option>Choose Student";
	    for($k=0;$k<count($curstuds);$k++)
	    {
	       echo "<option value=\"$curstuds[$k]\"";
	       if($relaystud[$i][$j]==$curstuds[$k]) echo " selected";
	       else if($relaystudtemp[$j]==$curstuds[$k]) echo " selected";
	       echo ">$curnames[$k]";
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
	 for($j=0;$j<count($curstuds);$j++)
	 {
	    echo "<option value=\"$curstuds[$j]\"";
	    if($curstuds[$j]==$stud[$i]) echo " selected";
	    echo ">$curnames[$j]";
	    if($curfasttimes[$j]!='0' && $curfasttimes[$j]!="0:00.00") echo " ($curfasttimes[$j])";
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
echo "<tr align=left><th colspan=3 align=left><input type=checkbox name=send value='y'>&nbsp;<i>I certify that the performances listed were established at the above designated meet.</i><br>";
echo "<font style=\"color:blue\"><b>DO NOT CHECK BOX UNTIL ALL RESULTS HAVE BEEN ENTERED.</b></font></th></tr>";
echo "<tr align=left><td colspan=3 align=left>NOTE: You may click \"Save & Keep Editing\" without checking this box, and the information you have entered for this meet will be saved for you to continue working on.";
echo "<br><font style=\"color:red\">However, <b>this verification form is not sent to the NSAA until you check the above box BEFORE clicking \"Save & Submit\"</b> AND you complete ALL fields at the top of this form.<br>";
echo "Also, <b>the Meet Score Sheet MUST be FAXED to the NSAA office</b> at (402)489-0934.  The Verification Form will not be complete or accepted until the Meet Score Sheet is received.</font></td></tr>";
echo "<tr align=center><td colspan=3><input type=submit name=save value=\"Save & Keep Editing\">";
echo "&nbsp;&nbsp;<input type=submit name=save value=\"Save & Submit\"></td></tr>";
echo "</table>";
echo "</form>";

echo $end_html;
?>
