<?php
//nsaa_verify_g.php: Girls SW Verification Form for NSAA to enter last-minute additions

require '../functions.php';
require '../variables.php';
require '../officials/variables.php';	//for autotab function

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!=1)
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

if($delete && $delete!='0')
{
   $sql="DELETE FROM sw_verify_perf_g WHERE id='$delete' AND formid='0'";
   $result=mysql_query($sql);
   header("Location:nsaa_verify_g.php?session=$session");
   exit();
}

if($save)	//user clicked Save button
{
   //save to database
   if($studsch_g!="Choose School" && $studevent_g!="Choose Event" && $stud_g!="Choose Student" && $meet!='' && $month!='MM' && $day!='DD')
   {
      //first check that performance entered meets qualifying standards
      if(!ereg("Relay",$studevent_g))
      {
	    $sql="SELECT last,first,middle,semesters,gender FROM eligibility WHERE id='$stud_g'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $curname="$row[1] $row[0] (".GetYear($row[3]).")";
      }
      else
      {
	    $curname="";
	    for($j=0;$j<count($relaystud_g);$j++)
	    {
	       if($relaystud_g[$j]!="Choose School")
	       {
		  $sql="SELECT last,first,middle,semesters,gender FROM eligibility WHERE id='".$relaystud_g[$j]."'";
		  $result=mysql_query($sql);
		  $row=mysql_fetch_array($result);
		  $curname.="$row[1] $row[0] (".GetYear($row[3])."), ";
	       }
	    }
	    $curname=substr($curname,0,strlen($curname)-2);
      }
      $curevent="Girls ".$studevent_g;
      $sql="SELECT qualmark FROM sw_qualify WHERE eventfull='$curevent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($studevent[$i]!="Diving")
      {
         $qualmark=split("[:.]",$row[0]);
         if(strlen($qualmark[2])==1) $qualmark[2].="0";
         if(trim($min1_g)=="" && trim($min2_g)=="") $min_g="0";
	 else $min_g=$min1_g.$min2_g;
	 if(trim($sec1_g)=="" && trim($sec2_g)=="") $sec_g="00";
	 else if(trim($sec1_g)=="") $sec_g="0".$sec2_g;
	 else if(trim($sec2_g)=="") $sec_g=$sec1_g."0";
	 else $sec_g=$sec1_g.$sec2_g;
	 if(trim($tenth1_g)=="" && trim($tenth2_g)=="") $tenth_g="00";
	 else if(trim($tenth1_g)=="") $tenth_g="0".$tenth2_g;
	 else if(trim($tenth2_g)=="") $tenth_g=$tenth1_g."0";
	 else $tenth_g=$tenth1_g.$tenth2_g;
	 $curmark="$min_g:$sec_g.$tenth_g";
	 $qualmark2="$qualmark[0]:$qualmark[1].$qualmark[2]";
	 $qualify=1;	//assume: student qualified
	 if(DoesQualify($curevent,$curmark)=="no") $qualify=0;

         if($qualify==0)	//if student did not qualify, alert user
         {
            $noqual="The Qualifying Mark for $curevent is $row[0].";
            $ix++;
         }
         else	//if student did qualify, put in DB
         {
	    $performance=60*$min_g;
	    $performance+=$sec_g;
	    $performance.=".".$tenth_g;
	    $meet=addslashes($meet);
	    $meetdate=mktime(0,0,0,$month,$day,$year);
            $temp=ereg_replace("\'","\'",$studsch_g);
            if(ereg("Relay",$studevent_g))
            {
   	       $stud_g="";
	       for($j=0;$j<count($relaystud_g);$j++)
	       {
	          $stud_g.=$relaystud_g[$j]."/";
	       }
	       $stud_g=substr($stud_g,0,strlen($stud_g)-1);
	       $sql2="INSERT INTO sw_verify_perf_g (formid,school,event,studentid,performance,meet,meetdate) VALUES ('0','$temp','$studevent_g','$stud_g','$performance','$meet','$meetdate')";
	       $result2=mysql_query($sql2);
	       echo mysql_error();
	    }
	    else
	    {
	       $sql2="INSERT INTO sw_verify_perf_g (formid,school,event,studentid,performance,meet,meetdate) VALUES ('0','$temp','$studevent_g','$stud_g','$performance','$meet','$meetdate')";
	       $result2=mysql_query($sql2);
	    }
	 }
      }
      else	//Diving
      {
         $qualmarkdiv=$row[0];
         if(trim($diving_g)=="") $diving_g="0";
	 $qualify=1;	//assume: student qualified
	 if($diving_g<$qualmarkdiv)	//if mark did not meet standard
	 {
	    //student did not qualify
	    $qualify=0;
	 }
	 if($qualify==0)	//alert user
	 {
	    $noqual=$studsch_g."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curname."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curevent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$diving_g."&nbsp;(Qualifying Mark: $qualmarkdiv)";
	    $ix++;
	 }
	 else	//if student did qualify, put in DB
	 {
	    $temp=ereg_replace("\'","\'",$studsch_g);
	    $meet=addslashes($meet);
	    $meetdate=mktime(0,0,0,$month,$day,$year);
	    $sql2="INSERT INTO sw_verify_perf_g (formid,school,event,studentid,performance,meet,meetdate) VALUES ('0','$temp','$studevent_g','$stud_g','$diving_g','$meet','$meetdate')";
	    $result2=mysql_query($sql2);
	 }
      }
   }
   if(!$noqual || $noqual=='')
   {
      header("Location:nsaa_verify_g.php?session=$session&studsch_g=$studsch_g");
      exit();
   }
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

echo "<center><br>";

echo "<form name=swform method=post action=\"nsaa_verify_g.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<a href=\"nsaa_verify_b.php?session=$session\" class=small>NSAA Boys Verification Form</a><br><br>";
echo "<table><caption><b>NSAA VERIFICATION FORM<br>of GIRLS State Swimming and Diving Qualifying Performances</b><hr></caption>";

//show errors, if any:
if($noqual!="")
{
   echo "<tr align=left><td align=left><font style=\"color:red\"><b>$noqual</b></font></td></tr>";
}

//individual performances table:
$studcoops=array();

echo "<tr align=left><td align=left>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=0>";
echo "<tr align=center>";
echo "<th class=smaller rowspan=2>Meet &<br>Meet Date</th>";
echo "<th rowspan=2 class=smaller>";
echo "School</th><th rowspan=2 class=smaller>Event</th><th rowspan=2 class=smaller>Name/Grade (Fastest Time)</th><th class=smaller colspan=2>Performance</th></tr>";
echo "<tr align=center><th class=smaller>";
echo "Swimming</th><th class=smaller>Diving</th></tr>";
echo "<tr align=center valign=top>";
echo "<td><input type=text class=tiny name=\"meet\" value=\"$meet\" size=20>";
echo "<br><select name=\"month\"><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $mo="0".$i;
   else $mo=$i;
   echo "<option";
   if($month==$mo) echo " selected";
   echo ">$mo</option>";
}
echo "</select>/<select name=\"day\"><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($d==$day) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"year\">";
$curryr=date("Y"); $lastyr=$curryr-1;
for($i=$lastyr;$i<=$curryr;$i++)
{
   $y=substr($i,2,2);
   echo "<option value=\"$i\"";
   if($i==$year) echo " selected";
   else if(!$year && $i==$curryr) echo " selected";
   echo ">$y</option>";
}
echo "</select></td>";
echo "<td><select class=small name=\"studsch_g\" onchange='submit()'><option>Choose School";
for($j=0;$j<count($sch[name]);$j++)
{
   echo "<option";
   if($sch[name][$j]==$studsch_g) 
   {
      echo " selected";
      $studcoops_g=$sch[coops][$j];
   }
   echo ">".$sch[name][$j];
}
echo "</select></td>";
echo "<td><select class=small onchange='submit()' name=\"studevent_g\"><option>Choose Event";
for($j=0;$j<count($sw_events);$j++)
{
   echo "<option";
   if($sw_events[$j]==$studevent_g) echo " selected";
   echo ">$sw_events[$j]";
}
echo "</select></td>";
//get students from selected school
if($studsch_g!="Choose School")
{
   if(ereg("/",$studcoops_g))
   {
      $studcoops_g=ereg_replace("\'","\'",$studcoops_g);
      $studschs=split("/",$studcoops_g);
      $sql="SELECT id,last, first, middle, semesters FROM eligibility WHERE (";
      for($j=0;$j<count($studschs);$j++)
      {
         $sql.="school='$studschs[$j]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND sw='x' AND gender='F' ORDER BY last";
   }
   else
   {
      $studsch2_g=ereg_replace("\'","\'",$studsch_g);
      $sql="SELECT id,last,first,middle,semesters FROM eligibility WHERE school='$studsch2_g' AND sw='x' AND gender='F' ORDER BY last";
   }
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $curstuds[$ix]=$row[0];
      $curnames[$ix]="$row[1], $row[2] $row[3] (".GetYear($row[4]).")";
      $sql2="SELECT t2.performance FROM sw_verify_g AS t1, sw_verify_perf_g AS t2 WHERE ((t1.id=t2.formid AND t1.submitted='y' AND t1.approved='y') OR t2.formid='0' OR t2.formid='46') AND t2.event='$studevent_g' AND t2.studentid='$row[0]' ORDER BY t2.performance ";
      if($studevent_g=='Diving') $sql2.="DESC ";
      $sql2.="LIMIT 1";

      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg(":",$row2[0]) || $studevent_g=='Diving')
  	 $curfasttimes[$ix]=$row2[0];
      else
	 $curfasttimes[$ix]=ConvertFromSec($row2[0]);
      if(mysql_num_rows($result2)==0) $curfasttimes[$ix]='0';
      $ix++;
   }
}
if(ereg("Relay",$studevent_g))
{
   echo "<td align=left>";
   $relaystudtemp=split("/",$stud_g);
   for($j=0;$j<4;$j++)
   {
      echo "<select class=small name=\"relaystud_g[$j]\"><option>Choose Student";
      for($k=0;$k<count($curstuds);$k++)
      {
         echo "<option value=\"$curstuds[$k]\"";
         if($relaystud_g[$j]==$curstuds[$k]) echo " selected";
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
   echo "<select class=small name=\"stud_g\"><option>Choose Student";
   for($j=0;$j<count($curstuds);$j++)
   {
      echo "<option value=\"$curstuds[$j]\"";
      if($curstuds[$j]==$stud_g) echo " selected";
      echo ">$curnames[$j]";
      if($curfasttimes[$j]!="0:00.00" && $curfasttimes[$j]!='0') 
         echo " ($curfasttimes[$j])";
   }
   echo "</select></td>";
}
echo "<td><input type=text size=1 onKeyUp='return autoTab(this,1,event);' maxlength=1 name=\"min1_g\" value=\"$min1_g\"><input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"min2_g\" value=\"$min2_g\"><b>:</b>";
echo "<input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"sec1_g\" value=\"$sec1_g\"><input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"sec2_g\" value=\"$sec2_g\"><b>.</b>";
echo "<input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"tenth1_g\" value=\"$tenth1_g\"><input type=text onKeyUp='return autoTab(this,1,event);' maxlength=1 size=1 name=\"tenth2_g\" value=\"$tenth2_g\">";
echo "</td>";
echo "<td><input type=text size=4 name=\"diving_g\" value=\"$diving_g\"></td></tr>";
echo "</table></td></tr>";
echo "<tr align=center><td><input type=submit name=save value=\"Save\"></td></tr>";
echo "</table>";
echo "</form>";
//get info already in database
$sql="SELECT * FROM sw_verify_perf_g WHERE formid='0'";
$result=mysql_query($sql);
echo "<tr align=left><td align=left><table cellspacing=3 cellpadding=3>";
if(mysql_num_rows($result)>0)
   echo "<tr align=left><th align=left colspan=5 class=smaller>NSAA Additions So Far:</th></tr><tr align=left>
<th align=center class=smaller>&nbsp;</th><th align=left class=smaller>Meet</th><th align=left class=smaller>M
eet Date</th><th align=left class=smaller>School</th><th class=smaller align=left>Event</th><th class=smaller 
align=left>Swimmer(s)</th><th align=left class=smaller>Performance</th></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left valign=top>";
   echo "<td align=center><a class=small href=\"nsaa_verify_g.php?session=$session&delete=$row[0]\">Delete</a>
</td>";
   echo "<td align=left>$row[meet]</td><td align=left>".date("m/d/Y",$row[meetdate])."</td>";
   echo "<td align=left>$row[2]</td><td align=left>$row[3]</td>";
   if(ereg("Relay",$row[3]))
   {
      $relayids=split("/",$row[4]);
      echo "<td align=left>";
      for($i=0;$i<count($relayids);$i++)
      {
         $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$relayids[$i]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         echo "$row2[0] $row2[1] (".GetYear($row2[2]).")<br>";
      }
      echo "</td>";   
   }
   else
   {
      $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[4]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<td align=left>$row2[0] $row2[1] (".GetYear($row2[2]).")</td>";
   }
   echo "<td align=left>";
   if($row[3]=='Diving') echo $row[5];
   else echo ConvertFromSec($row[5]);
   echo "</td></tr>";
}
echo "</table></td></tr>";

echo $end_html;
?>
