<?php
//sw_state_edit_b.php: Edit SW State Entry Form (Boys)

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
//get swimming school--"school" field in sw_schools table
$sql="SELECT school,coops,hytekabbr,stateform_b,id FROM sw_schools WHERE school='$school2' OR coops LIKE '$school2/%' OR coops LIKE '%/$school2/%' OR coops LIKE '%/$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sw_sch=$row[0];
$sw_sch2=ereg_replace("\'","\'",$sw_sch);
$hytekabbr=$row[2];
$schoolid=$row[4];
if($row[1]=="")
   $coops=0;
else
   $coops=1;
if($row[3]!="")
{
   //state form has already been submitted
   header("Location:sw_state_view_b.php?session=$session&school_ch=$school_ch");
   exit();
}

if($submit=="Save")
{
   //update database table sw_state_b
   $sql="DELETE FROM sw_state_b WHERE schoolid='$schoolid'";
   $result=mysql_query($sql);

   for($i=0;$i<count($sw_events);$i++)
   {
      if(!ereg("Relay",$sw_events[$i]))
      {
         for($j=0;$j<4;$j++)
         {
	    if($swstud[$sw_events[$i]][$j]!="Choose Student")
	    {
	       $sql="INSERT INTO sw_state_b (schoolid,hytekabbr,event,entry) VALUES ('$schoolid','$hytekabbr','$sw_events[$i]','".$swstud[$sw_events[$i]][$j]."')";
	       $result=mysql_query($sql);
	    }
	 }
      }
      else	//Relay
      {
         $relaylist="";
	 for($j=0;$j<8;$j++)
	 {
	    $relaylist.=$relaystud[$sw_events[$i]][$j]."/";
	 }
	 $relaylist=substr($relaylist,0,strlen($relaylist)-1);
	 $sql="INSERT INTO sw_state_b (schoolid,hytekabbr,event,entry,relaystuds) VALUES ('$schoolid','$hytekabbr','$sw_events[$i]','".$relaytime[$sw_events[$i]]."','$relaylist')";
	 $result=mysql_query($sql);
      }
   }
   echo mysql_error();

   //if final checkbox checked, sent to sw_state_view_b.php
   if($final=='y')
   {
      header("Location:sw_state_view_b.php?session=$session&school_ch=$school_ch&final=y");
      exit();
   }
}

echo $init_html;
echo $header;

//for each individual event, show places for 4 entrants
$ix=0;
echo "<center><br>";
echo "<form method=post action=\"sw_state_edit_b.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table>";
echo "<caption><b>Boys State Swimming Entry Form:<hr></b></caption>";
for($i=0;$i<count($sw_events);$i++)
{
   if(!ereg("Relay",$sw_events[$i]))
   {
      if($ix%3==0) echo "<tr align=center valign=top>";
      echo "<td><table><caption align=left><b>$sw_events[$i]:";
      $sql="SELECT automark,qualmark FROM sw_qualify WHERE eventfull='Boys $sw_events[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(!ereg("Diving",$sw_events[$i])) echo "<br>Auto: $row[0]/Secondary: $row[1]</b></caption>";
      else 
      {
	 echo "<br>Auto:300</b><br>";
	 echo "<font style=\"font-size:8pt\">(You must fax diver's scoresheet with<br>BOTH coach and divers signatures)</font></caption>";
      }
      //get students from this school with entry in sw_verify_perf_b for this event
      $sql="SELECT t1.* FROM sw_verify_perf_b AS t1, sw_verify_b AS t2 WHERE ((t1.formid=t2.id AND t2.approved='y') OR t1.formid='0') AND t1.school='$sw_sch2' AND t1.event='$sw_events[$i]' ORDER BY t1.studentid";
      $result=mysql_query($sql);
      $studs=array();
      $six=0;
      while($row=mysql_fetch_array($result))
      {
	 $studs[id][$six]=$row[0];
         $studs[studid][$six]=$row[4];
	 if($row[1]==0 || $sw_events[$i]=="Diving")
	    $studs[mark][$six]=$row[5];
	 else
	    $studs[mark][$six]=ConvertToSec($row[5]);
	 $six++;
      }
      //put in alphabetical order, and only put best time for each student
      $temp=array(); $tix=0;
      for($j=0;$j<$six;$j++)
      {
	 $temp[$tix]=$studs[mark][$j];
	 $tix++;
      }
      sort($temp);
      $usednames=array();
      $uix=0;
      if(!ereg("Diving",$sw_events[$i]))
      {
	 $cur_studs=array();
	 $cix=0;
	 for($j=0;$j<count($temp);$j++)
	 {
	    for($k=0;$k<$six;$k++)
	    {
	       if($temp[$j]==$studs[mark][$k])
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
	          $k=$six;
	       }
	    }
	 }
      }
      else	//Diving
      {
	 $cur_studs=array();
	 $cix=0;
	 for($j=count($temp)-1;$j>=0;$j--)
	 {
	    for($k=0;$k<$six;$k++)
	    {
	       if($temp[$j]==$studs[mark][$k])
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
		  $k=$six;
	       }
	    }
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
            $sql2="SELECT t2.formid,t1.first,t1.last,t1.semesters,t2.performance FROM eligibility AS t1, sw_verify_perf_b AS t2 WHERE t1.id=t2.studentid AND t2.studentid='".$cur_studs[id][$k]."' AND (t2.performance='".$cur_studs[mark][$k]."' OR t2.performance LIKE '%".$cur_studs2[mark][$k]."')";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
	    $qualify=DoesQualify("Boys ".$sw_events[$i],$row2[4]);
	    if($qualify!="no")
	    {
               echo "<option value=\"".$cur_studs[mark][$k]."\"";
               //see if this entry is already in database
	       $sql3="SELECT id FROM sw_state_b WHERE schoolid='$schoolid' AND entry='".$cur_studs[mark][$k]."'";
	       $result3=mysql_query($sql3);
	       if(mysql_num_rows($result3)>0 && $studshown[$i][$k]!='1' && $posfilled[$j]!='1') 
	       {
		  echo " selected";
		  $studshown[$i][$k]=1;
		  $posfilled[$j]=1;
	       }
	       echo ">";
               echo "$row2[1] $row2[2] (".GetYear($row2[3]).") - ";
               if($row2[0]==0 && $sw_events[$i]!="Diving") echo ConvertFromSec($row2[4]);
               else echo $row2[4];
	       $qualify=DoesQualify("Boys ".$sw_events[$i],$row2[4]);
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
//get array of all eligibile swimmers for this school
if($coops==1)	//there are co-ops associated with this school
{
   $sql="SELECT coops FROM sw_schools WHERE id='$schoolid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $coopsch=split("/",$row[0]);
   $sql="SELECT id,first,last,semesters,school FROM eligibility WHERE sw='x' AND gender='M' AND (";
   for($i=0;$i<count($coopsch);$i++)
   {
      $coopsch2[$i]=ereg_replace("\'","\'",$coopsch[$i]);
      $sql.="school='$coopsch2[$i]' OR ";
   }
   $sql=substr($sql,0,strlen($sql)-4);
   $sql.=") ORDER BY last";
}
else
{
   $sql="SELECT id,first,last,semesters,school FROM eligibility WHERE sw='x' AND gender='M' AND school='$school2'";
}
$result=mysql_query($sql);
$ix=0;
$relaystuds=array();
while($row=mysql_fetch_array($result))
{
   $relaystuds[id][$ix]=$row[0];
   $relaystuds[name][$ix]="$row[1] $row[2] (".GetYear($row[3]).")";
   $ix++;
}
$ix=0;
for($i=0;$i<count($sw_events);$i++)
{
   if(ereg("Relay",$sw_events[$i]))
   {
      if($ix%3==0) echo "<tr align=center valign=top>";
      echo "<td><table><caption align=left><b>$sw_events[$i]:";
      $sql="SELECT automark,qualmark FROM sw_qualify WHERE eventfull='Boys $sw_events[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<br>Auto: $row[0]/Secondary: $row[1]</b></caption>";
      //first allow user to choose from any of the auto-qualifying relay times for this school
      $sql="SELECT t1.formid,t1.performance,t1.id FROM sw_verify_perf_b AS t1,sw_verify_b AS t2 WHERE ((t2.id=t1.formid AND t2.approved='y') OR t1.formid='0') AND school='$sw_sch2' AND event='$sw_events[$i]'";
      $result=mysql_query($sql);
      $relaytimes=array();
      $rix=0;
      while($row=mysql_fetch_array($result))
      {
	 if($row[0]==0)	//in seconds format already
	    $relaytimes[mark][$rix]=$row[1];
	 else	//convert to seconds format from mm:ss.hh format
	    $relaytimes[mark][$rix]=ConvertToSec($row[1]);
	 //$relaytimes[id][$rix]=$row[2];
	 $rix++;
      }
      $temp=array(); $tix=0;
      for($j=0;$j<$rix;$j++)
      {
	 $temp[$tix]=$relaytimes[mark][$j];
	 $tix++;
      }
      sort($temp);
      echo "<tr align=left><td><select class=small name=\"relaytime[$sw_events[$i]]\"><option>Choose Time";
      $usedtimes=array(); $uix=0;
      $auto=0;	//set to 1 when auto mark has been shown (so don't show secondary)
      for($j=0;$j<count($temp);$j++)
      {
	 for($k=0;$k<$rix;$k++)
	 {
	    if($temp[$j]==$relaytimes[mark][$k])
	    {
	       $qualify=DoesQualify("Boys ".$sw_events[$i],$relaytimes[mark][$k]);
	       if($qualify=="Automatic" || ($qualify=="Secondary" && $auto==0))
	       {
	          echo "<option value=\"".$relaytimes[mark][$k]."\"";
		  //check if this entry is entered in database
		  $temp2=ConvertFromSec($relaytimes[mark][$k]);
		  $sql2="SELECT id FROM sw_state_b WHERE schoolid='$schoolid' AND (entry='".$relaytimes[mark][$k]."' OR entry='$temp2')";
		  $result2=mysql_query($sql2);
		  if(mysql_num_rows($result2)>0)
		     echo " selected";
		  echo ">".ConvertFromSec($relaytimes[mark][$k])." - ";
                  if($qualify=="Automatic") echo "AUTO";
		  else echo "SEC";
		  $auto=1;
		  $k=$rix;
	       }
	    }
	 }
      }
      echo "</select></td></tr>";
      //get swimmers on this relay from database, if any
      $sql2="SELECT relaystuds FROM sw_state_b WHERE schoolid='$schoolid' AND event='$sw_events[$i]'";
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
