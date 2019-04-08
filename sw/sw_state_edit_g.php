<?php
//sw_state_edit_g.php: Edit SW State Entry Form (Girls)

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

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

//get swimming school--"school" field in swschool table
$sql="SELECT school,hytekabbr,stateform_g,sid FROM swschool WHERE school='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   $sql="SELECT id FROM headers WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) exit();
   else
   {
      $row=mysql_fetch_array($result);
      $schid=$row[id];
      $sql="SELECT school,hytekabbr,stateform_g,sid FROM swschool WHERE mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid'";
      $result=mysql_query($sql);
   }
}
$row=mysql_fetch_array($result);
$sw_sch=$row[school];
$sw_sch2=addslashes($sw_sch);
$sw_sch3=addslashes(GetMainSchoolName($row[sid],'sw'));
$hytekabbr=$row[hytekabbr];
$schoolid=$row[sid];
if($row[othersch1]==0 && $row[othersch2]==0 && $row[othersch3]==0)
   $coops=0;
else
   $coops=1;
if($row[stateform_g]!="" && $level!=1)
{
   //state form has already been submitted
   header("Location:sw_state_view_g.php?session=$session&school_ch=$school_ch");
   exit();
}

$duedate=GetDueDate("sw_state");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//check if due date is past
if(PastDue($duedate,-0.5) && $level!=1)
{
   echo $init_html;
   echo $header;
   echo "<br><br>This form was due on $duedate2 at noon.<br><br>";
   echo "<a class=small href=\"../welcome.php?session=$session\">Home</a>";
   exit();
}  

if($submit=="Save")
{
   //update database table sw_state_g
   $sql="DELETE FROM sw_state_g WHERE schoolid='$schoolid'";
   $result=mysql_query($sql);
 
   $students=array();	//array of student id's--swimmers can be in max of 4 events (at most 2 indy events)
   $six=0;	//index for students array
   $studentries=array();
   $studids=array();
   for($i=0;$i<count($sw_events);$i++)
   {
      if(!ereg("Relay",$sw_events[$i]))
      {
         for($j=0;$j<4;$j++)
         {
	    if($swstud[$sw_events[$i]][$j]!="Choose Student")
	    {
	       $stud_time=split("/",$swstud[$sw_events[$i]][$j]);
	       $sql="INSERT INTO sw_state_g (schoolid,hytekabbr,event,entry,studs) VALUES ('$schoolid','$hytekabbr','$sw_events[$i]','$stud_time[1]','$stud_time[0]')";
	       $result=mysql_query($sql);
	       /*
	       $studids[$stud_time[0]]=$stud_time[0];
	       $studentries[indy][$stud_time[0]]++;
	       $studentries[total][$stud_time[0]]++;
	       */
	    }
	 }
      }
      else	//Relay
      {
         $relaylist="";
	 for($j=0;$j<8;$j++)
	 {
	    $relaylist.=$relaystud[$sw_events[$i]][$j]."/";
	    $temp2=$relaystud[$sw_events[$i]][$j];
	    /*
	    if($temp2!="Choose Student")
	    {
	       $studids[$temp2]=$temp2;
	       $studentries[total][$temp2]++;
	    }
	    */
	 }
	 $relaylist=substr($relaylist,0,strlen($relaylist)-1);
	 $sql="INSERT INTO sw_state_g (schoolid,hytekabbr,event,entry,studs) VALUES ('$schoolid','$hytekabbr','$sw_events[$i]','".$relaytime[$sw_events[$i]]."','$relaylist')";
	 $result=mysql_query($sql);
      }
   }
   /*
   $dups=array(); $dix=0;
   foreach($studids as $index => $stud)
   {
      $students[$six]=$stud;
      $six++;
   }
   for($i=0;$i<count($students);$i++)
   {
      $error=0;
      if($studentries[total][$students[$i]]>4 || $studentries[indy][$students[$i]]>2)
	 $error=1;
      if($error==1)
      {
	 $dups[$dix]=$students[$i];
	 $dix++;
      }
   }
   */

   //if final checkbox checked, sent to sw_state_view_g.php
   if($final=='y') // && count($dups)==0)
   {
      header("Location:sw_state_view_g.php?session=$session&school_ch=$school_ch&final=y");
      exit();
   }
}

echo $init_html;
echo $header;
/*
if($submit=="Save" && count($dups)>0)
{
   echo "<table width=400><tr align=left><th align=left><font style=\"color:red\">The following students were either entered in more than 4 events total or more than 2 individual events:</font></th></tr>";
   for($i=0;$i<count($dups);$i++)
   {
      $sql="SELECT first,last FROM eligibility WHERE id='$dups[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=left><td align=left><font style=\"color:red\">$row[0] $row[1]</font></td></tr>";
   }
   if($final=='y')
      echo "<tr align=left><th align=left><font style=\"color:red\">Please adjust your entries accordingly and resubmit this form.</font></th></tr>";
   echo "</table>";
}
*/

//for each individual event, show places for 4 entrants
$ix=0;
echo "<br>";
echo "<form method=post action=\"sw_state_edit_g.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
if($level==1)
   echo "<a href=\"../swstate.php?session=$session\" class=small>Return to State Swimming</a><br><br>";
echo "<table>";
echo "<caption><b>Girls State Swimming Entry Form:<br>";
echo "<font style=\"font-size:8pt;\">(Due $duedate2)</font><hr></b></caption>";
for($i=0;$i<count($sw_events);$i++)
{
   if(!ereg("Relay",$sw_events[$i]))
   {
      if($ix%3==0) echo "<tr align=center valign=top>";
      echo "<td><table><tr align=left><td><b>$sw_events[$i]:";
      $sql="SELECT automark,qualmark FROM sw_qualify WHERE eventfull='Girls $sw_events[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(!ereg("Diving",$sw_events[$i])) echo "<br>Auto: $row[0]/Secondary: $row[1]</b></td></tr>";
      else 
      {
	 echo "<br>Auto:300</b><br>";
	 echo "<font style=\"font-size:8pt\">(You must fax diver's scoresheet with<br>BOTH coach and divers signatures)</font></td></tr>";
      }
      //get students from this school with entry in sw_verify_perf_g for this event
      $sql="SELECT t1.* FROM sw_verify_perf_g AS t1, sw_verify_g AS t2 WHERE ((t1.formid=t2.id AND t2.approved='y') OR t1.formid='0') AND (t1.school='$sw_sch2' OR t1.school='$sw_sch3') AND t1.event='$sw_events[$i]' ORDER BY t1.performance";
      if(ereg("Diving",$sw_events[$i])) $sql.=" DESC";
      $result=mysql_query($sql);
      $studs=array();
      $six=0;
      while($row=mysql_fetch_array($result))
      {
	 $studs[id][$six]=$row[0];
         $studs[studid][$six]=$row[4];
	 //if($row[1]==0 || $sw_events[$i]=="Diving")
	    $studs[mark][$six]=$row[5];
	 //else
	    //$studs[mark][$six]=ConvertToSec($row[5]);
	 $six++;
      }
      $usednames=array();
      $uix=0;
      if(!ereg("Diving",$sw_events[$i]))
      {
	 $cur_studs=array();
	 $cix=0;
	    for($k=0;$k<$six;$k++)
	    {
		  $used=0;
		  for($l=0;$l<count($usednames);$l++)
		  {
		     if($usednames[$l]==$studs[studid][$k])
			$used=1;
		  }
		  if($used==0)
		  {
		     $cur_studs[mark][$cix]=$studs[mark][$k];
		     $cur_studs[id][$cix]=$studs[studid][$k];
		     $cix++;
		     $usednames[$uix]=$studs[studid][$k];
		     $uix++;
		  }
	          $studs[mark][$k]="";
	    }
      }
      else	//Diving
      {
	 $cur_studs=array();
	 $cix=0;
	    for($k=0;$k<$six;$k++)
	    {
		  $used=0;
		  for($l=0;$l<count($usednames);$l++)
		  {
		     if($usednames[$l]==$studs[studid][$k])
			$used=1;
		  }
		  if($used==0)
		  {
		     $cur_studs[mark][$cix]=$studs[mark][$k];
		     $cur_studs[id][$cix]=$studs[studid][$k];
		     $cix++;
		     $usednames[$uix]=$studs[studid][$k];
		     $uix++;
		  }
		  $studs[mark][$k]="";
	    }
      }
      $studshown=array(); $posfilled=array();
      //studshown: tells if student has already been show on current event's list from database
      //posfilled: tells if this row in event list has already been filled with selected entry from db
      for($j=0;$j<4;$j++)
      { 
         echo "<tr align=left><td align=left>";
         echo "<select class=small name=\"swstud[$sw_events[$i]][$j]\"><option>Choose Student";
         for($k=0;$k<count($cur_studs[id]);$k++)
         {
	    $cur_studs2[mark][$k]=ConvertFromSec($cur_studs[mark][$k]);
            $sql2="SELECT t2.formid,t1.first,t1.last,t1.semesters,t2.performance FROM eligibility AS t1, sw_verify_perf_g AS t2 WHERE t1.id=t2.studentid AND t2.studentid='".$cur_studs[id][$k]."' AND (t2.performance='".$cur_studs[mark][$k]."' OR t2.performance LIKE '%".$cur_studs2[mark][$k]."')";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
	    $qualify=DoesQualify("Girls ".$sw_events[$i],$row2[4]);
	    if($qualify!="no")
	    {
               echo "<option value=\"".$cur_studs[id][$k]."/".$cur_studs[mark][$k]."\"";
               //see if this entry is already in database
	       $sql3="SELECT id FROM sw_state_g WHERE schoolid='$schoolid' AND entry='".$cur_studs[mark][$k]."' AND studs='".$cur_studs[id][$k]."'";
	       $result3=mysql_query($sql3);
	       if(mysql_num_rows($result3)>0 && $studshown[$i][$k]!='1' && $posfilled[$j]!='1') 
	       {
		  echo " selected";
		  $studshown[$i][$k]=1;
		  $posfilled[$j]=1;
	       }
	       echo ">";
               echo "$row2[1] $row2[2] (".GetYear($row2[3]).") - ";
               if(!ereg("Diving",$sw_events[$i])) echo ConvertFromSec($row2[4]);
	       else echo $row2[4];
	       $qualify=DoesQualify("Girls ".$sw_events[$i],$row2[4]);
	       if($qualify=="Automatic") echo " AUTO";
	       else if($qualify=="Secondary") echo " SEC";
	    }
         }
         echo "</select></td></tr>";
      }
      echo "</table></td>";
      if(($ix+1)%3==0) echo "</tr>";
      $ix++;
   }
}
//now show relays
//get list of eligible girl swimmers for this school/co-op   
$sql="SELECT * FROM swschool WHERE sid='$schoolid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql="SELECT t1.* FROM eligibility AS t1, headers AS t2 WHERE t1.school=t2.school AND (t2.id='$row[mainsch]'";
if($row[othersch1]>0)
   $sql.=" OR t2.id='$row[othersch1]'";
if($row[othersch2]>0) 
   $sql.=" OR t2.id='$row[othersch2]'";
if($row[othersch3]>0)
   $sql.=" OR t2.id='$row[othersch3]'";
$sql.=") AND t1.gender='F' AND t1.sw='x' AND t1.eligible='y' ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
$result=mysql_query($sql);
$ix=0;
$relaystuds=array();
while($row=mysql_fetch_array($result))
{
   $relaystuds[id][$ix]=$row[id];
   $relaystuds[name][$ix]="$row[first] $row[last] (".GetYear($row[semesters]).")";
   $ix++;
}
$ix=0;
for($i=0;$i<count($sw_events);$i++)
{
   if(ereg("Relay",$sw_events[$i]))
   {
      if($ix%3==0) echo "<tr align=center valign=top>";
      echo "<td><table><tr align=left><b>$sw_events[$i]:";
      $sql="SELECT automark,qualmark FROM sw_qualify WHERE eventfull='Girls $sw_events[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<br>Auto: $row[0]/Secondary: $row[1]</b></td></tr>";
      //first allow user to choose from any of the auto-qualifying relay times for this school
      //$sql="SELECT t1.formid,t1.performance,t1.id FROM sw_verify_perf_g AS t1,sw_verify_g AS t2 WHERE ((t2.id=t1.formid AND t2.approved='y') OR t1.formid='0') AND (t1.school='$sw_sch2' OR t1.school='$sw_sch3') AND t1.event='$sw_events[$i]' LIMIT 10";
      $sql="SELECT formid,performance,id FROM sw_verify_perf_g WHERE (school='$sw_sch2' OR school='$sw_sch3') AND event='$sw_events[$i]' ORDER BY performance";
      $result=mysql_query($sql);
      $relaytimes=array();
      $rix=0;
      while($row=mysql_fetch_array($result))
      {
	 $sql2="SELECT approved FROM sw_verify_g WHERE id='$row[0]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[0]=='y' || $row[0]=='0')
	 {
	 //if($row[0]==0)	//in seconds format already
	    $relaytimes[mark][$rix]=$row[1];
	 //else	//convert to seconds format from mm:ss.hh format
	    //$relaytimes[mark][$rix]=ConvertToSec($row[1]);
	 //$relaytimes[id][$rix]=$row[2];
	    $rix++;
	 }
      }
      echo "<tr align=left><td><select class=small name=\"relaytime[$sw_events[$i]]\"><option>Choose Time";
      $usedtimes=array(); $uix=0;
      $auto=0;	//set to 1 when auto mark has been shown (so don't show secondary)
	 for($k=0;$k<$rix;$k++)
	 {
	       $qualify=DoesQualify("Girls ".$sw_events[$i],$relaytimes[mark][$k]);
	       if($qualify=="Automatic" || ($qualify=="Secondary" && $auto==0))
	       {
	          echo "<option value=\"".$relaytimes[mark][$k]."\"";
		  //check if this entry is entered in database
		  $temp2=ConvertFromSec($relaytimes[mark][$k]);
		  $sql2="SELECT id FROM sw_state_g WHERE schoolid='$schoolid' AND (entry='".$relaytimes[mark][$k]."' OR entry='$temp2')";
		  $result2=mysql_query($sql2);
		  if(mysql_num_rows($result2)>0)
		     echo " selected";
		  echo ">".ConvertFromSec($relaytimes[mark][$k])." - ";
                  if($qualify=="Automatic") echo "AUTO";
		  else echo "SEC";
		  $auto=1;
	       }
	 }
      echo "</select></td></tr>";
      //get swimmers on this relay from database, if any
      $sql2="SELECT studs FROM sw_state_g WHERE schoolid='$schoolid' AND event='$sw_events[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $dbstuds=split("/",$row2[0]);
      for($j=0;$j<8;$j++)
      {
	 echo "<tr align=left><td align=left>";
	 $num=$j+1;
	 if($j==4) echo "Alternates:<br>";
	 echo "$num&nbsp;<select class=small class=small name=\"relaystud[$sw_events[$i]][$j]\"><option>Choose Student";
	 for($k=0;$k<count($relaystuds[id]);$k++)
	 {
	    echo "<option value=\"".$relaystuds[id][$k]."\"";
	    if($dbstuds[$j]==$relaystuds[id][$k]) echo " selected";
	    echo ">";
	    echo $relaystuds[name][$k];
	 }
	 echo "</select></td></tr>";
      }
      echo "</table></td>";
      if(($ix+1)%3==0) echo "</tr>";
      $ix++;
   }
}
echo "</table>";

//show checkbox for final submission
echo "<table width=90%><tr align=left><th align=left><input type=checkbox name=final value=y>";
echo "<font style=\"color:red\"><b>Check this box when you have completed the above information and wish to make this your final submission of state qualifiers.  Then click \"Save\" below.<br></th></tr></table>";

echo "<br><input type=submit name=submit value=\"Save\">";
echo "</form>";

echo $end_html;
?>
