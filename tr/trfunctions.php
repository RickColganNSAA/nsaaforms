<?php
//IMPORT POST/GET VARIABLES
if($_REQUEST)
{
   foreach($_REQUEST as $key => $value)
   {
        $$key=$value;
   }
}
if($_FILES)
{
   foreach($_FILES as $key => $value)
   {
      $$key = $_FILES[$key]['tmp_name'];
   }
}
require_once('../variables.php');
require_once('../functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

function AssignParticipantNumbers($class,$gender,$silent=TRUE)
{
   $db1="nsaastatetrack"; $db2="nsaascores";
   require_once('../functions.php');

   //START AT THE FOLLOWING NUMBERS:
   //CLASS A GIRLS: 1000
   if($class=="A" && $gender=="G") $start=1000;
   //CLASS A BOYS: 2000
   if($class=="A" && $gender=="B") $start=2000;
   //CLASS B GIRLS: 3000
   if($class=="B" && $gender=="G") $start=3000;
   //CLASS B BOYS: 4000
   if($class=="B" && $gender=="B") $start=4000;
   //CLASS C GIRLS: 5000
   if($class=="C" && $gender=="G") $start=5000;
   //CLASS C BOYS: 6000
   if($class=="C" && $gender=="B") $start=6000;
   //CLASS D GIRLS: 7000
   if($class=="D" && $gender=="G") $start=7000;
   //CLASS D BOYS: 8000
   if($class=="D" && $gender=="B") $start=8000;

   //FIRST MAKE SURE ANYONE CURREENTLY IN trstateparticipants TABLE IS IN FACT A QUALIFIER
   $sql="SELECT t1.studentid FROM $db1.trstateparticipants LEFT JOIN $db1.trstatequalifiers AS t2 ON t1.studentid=t2.studentid WHERE t2.id IS NULL";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="DELETE FROM $db1.trstateparticipants WHERE studentid='$row[studentid]'";
      $result2=mysql_query($sql2);
   }

   //NOW ORDER BY SCHOOL, ALPHA BY LAST NAME, ALPHA BY FIRST NAME
   $sql="SELECT DISTINCT t1.studentid,t2.first,t2.last,t2.semesters,t1.sid FROM $db1.trstatequalifiers AS t1,$db2.eligibility AS t2,$db1.trevents AS t3,$db1.trschool AS t4 WHERE t1.studentid=t2.id AND t1.eventid=t3.id AND t1.sid=t4.sid AND t1.class='$class' AND t3.gender LIKE '$gender%' ORDER BY t4.school,t2.last,t2.first";
   $result=mysql_query($sql);
   if(!$silent) echo "<p>Starting at #$start...</p>";
   $added=0; $total=0;
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT * FROM $db1.trstateparticipants WHERE studentid='$row[studentid]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)	//NEEDS A NUMBER
      {
	 //MAKE SURE $start isn't already in use
	 $sql3="SELECT * FROM $db1.trstateparticipants WHERE partnumber='$start'";
	 $result3=mysql_query($sql3);
	 while($row3=mysql_fetch_array($result3))
	 {
	    $start++;
	 }
	 $sql3="INSERT INTO $db1.trstateparticipants (studentid,partnumber,first,last,grade,sid) VALUES ('$row[studentid]','$start','".addslashes($row[first])."','".addslashes($row[last])."','".GetYear($row[semesters])."','$row[sid]')";
	 $result3=mysql_query($sql3);
	 $added++;
      }
      $total++;
   }

   if(!$silent) 
   {
      echo "<p>Assigned $added participant numbers. There are a total of $total Class $class";
      if($gender=="G") echo " Girls";
      else echo " Boys";  
      echo " participants in the state meet.</p>";
   }
   return;
}

function GetEventName($eventid)
{
   $db1="nsaastatetrack";
   $sql="SELECT * FROM $db1.trevents WHERE id='$eventid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   return $row[gender]." ".$row[eventfull];
}
function GetEventLong($event,$gender)
{
   require '../variables.php';
   if($gender=='b')
   {
      for($i=0;$i<count($trevents);$i++)
      {
	 if($event==$trevents[$i]) return $treventslong[$i];
      }
   }
   else
   {
      for($i=0;$i<count($trevents_g);$i++)
      {
         if($event==$trevents_g[$i]) return $treventslong_g[$i];
      }
   }
   return "EVENT??";
}
function GetNextFastest($class,$eventid,$fastestct,$startingplace,$relay=0)
{
   //$eventid = Event ID (trevents.id)
   //$fastestct = # of next fastest times, for example: Next 6 fastest times
   //$startingplace = Place the athlete had to have gotten at most (so if Top 3 auto-qualified and we need next fastest times, startingplace = 4)
   //$relay = 1 if this is a relay event, else 0 (default)
   //RETURN ARRAY OF trstatequalifiers.id's
   $db1="nsaastatetrack";
   require_once("../../calculate/functions.php");

   //FIRST - GET TOP $fastestct TIMES FOR NON-AUTO-QUALIFIERS FOR THIS EVENT
   $sql2="SELECT ";
   if($relay==1) $sql2.="DISTINCT distperf1,distperf2,sid ";
   else $sql2.="distperf1,distperf2 ";
   $sql2.="FROM $db1.trstatequalifiers WHERE class='$class' AND eventid='$eventid' AND distplace>=$startingplace ORDER BY distperf1,distperf2 LIMIT $fastestct";
   $result2=mysql_query($sql2);
   $place=1;
   while($row2=mysql_fetch_array($result2))
   {
      $distperf1[$place]=$row2[distperf1]; $distperf2[$place]=$row2[distperf2];
      $place++;
   }
   //NOW $distperf1[$i] and $distperf2[$i] CONTAIN THE [$i]TH FASTEST PERSON'S TIME
   //NOW WE LOOK AT THE $fastestctTH TIME - $distperf1[$fastestct] and $distperf2[$fastestct] and see HOW MANY PEOPLE TIED FOR THIS TIME
   //IF ($fastestct+1) OR MORE PEOPLE TIED FOR IT, NO ONE WITH THIS TIME GOES
   //FOR $X = $fastestct DOWN TO 2, if $X PEOPLE TIED FOR IT, [($fastestct+1)-$X]TH TIME MUST EQUAL THIS TIME, OR ELSE NO ONE WITH THIS TIME GOES
   //EXAMPLE: $fastestct = 6:
   	//IF 7 OR MORE PEOPLE TIED FOR IT, NO ONE WITH THIS TIME GOES
   	//IF 6 PEOPLE TIED FOR IT, $distperf1[1] and $distperf2[1] MUST EQUAL THIS TIME, OR ELSE NO ONE WITH THIS TIME GOES
   	//IF 5 PEOPLE TIED FOR IT, $distperf1[2] and $distperf2[2] MUST EQUAL THIS TIME, OR ELSE NO ONE WITH THIS TIME GOES
   	//IF 4 PEOPLE TIED FOR IT, $distperf1[3] and $distperf2[3] MUST EQUAL THIS TIME, OR ELSE NO ONE WITH THIS TIME GOES
   	//IF 3 PEOPLE TIED FOR IT, $distperf1[4] and $distperf2[4] MUST EQUAL THIS TIME, OR ELSE NO ONE WITH THIS TIME GOES
   	//IF 2 PEOPLE TIED FOR IT, $distperf1[5] and $distperf2[5] MUST EQUAL THIS TIME, OR ELSE NO ONE WITH THIS TIME GOES
   //SO...HOW MANY ATHLETES/TEAMS RAN THE [$fastestct]TH TIME???
   $sql2="SELECT ";
   if($relay==1) $sql2.="DISTINCT sid";
   else $sql2.="*";
   $sql2.=" FROM $db1.trstatequalifiers WHERE class='$class' AND eventid='$eventid' AND distplace>=$startingplace AND distperf1='".$distperf1[$fastestct]."' AND distperf2 LIKE '".$distperf2[$fastestct]."'";
   $result2=mysql_query($sql2);
   $ties=mysql_num_rows($result2);        //$ties = # OF ATHLETES THAT RAN THIS TIME
   $all=0;	//BY DEFAULT, WE ARE ASSUMING NOT ALL ATHLETES IN TOP $fastestct TIMES FROM $startingplace ON GET TO GO (We're assuming there are ties somewhere)
   for($i=$fastestct;$i>=2;$i--)	//FROM $fastestct DOWN TO 2 
   {
      if($ties==$i)
      {
	 $curix=($fastestct+1)-$i;
         if($distperf1[$curix]==$distperf1[$fastestct] && $distperf2[$curix]==$distperf2[$fastestct])	//ALL $fastestct GET TO GO!
            $all=1;
      }
   }
   if($ties==1)	//NO TIES
      $all=1;
   //NOW GET THE QUALIFIERS
   //if($all==1) ---> BIZ AS USUAL - ATHLETES/TEAMS IN TOP $fastestct (AFTER <$startingplace PLACERS ARE OUT) GET TO GO
   //else --> ONLY ATHLETES/TEAMS FASTER THAN $distperf1[$fastestct]:$distperf2[$fastestct] THAT PLACED HIGHER THAN OR EQUAL TO $startingplace CAN GO
   $sql2="SELECT ";
   if($relay==1) $sql2.="DISTINCT sid ";
   else $sql2.="id ";
   $sql2.="FROM $db1.trstatequalifiers WHERE class='$class' AND eventid='$eventid' AND distplace>=$startingplace ORDER BY distperf1,distperf2 LIMIT $fastestct";
   $result2=mysql_query($sql2);
   $curqualifiers="";
   while($row2=mysql_fetch_array($result2))
   {
      if($all==1 || !($row2[distperf1]==$distperf1[$fastestct] && $row2[distperf2]==$distperf2[$fastestct]))
      {
         $curqualifiers.=$row2[0].",";
      }
   }
   return substr($curqualifiers,0,strlen($curqualifiers)-1);
}
function GetEventField($eventdistcode = NULL,$gender = NULL,$returnfield = 'id')
{
   if(strtoupper($gender)=="B" || strtoupper($gender)=="M") $gender="Boys";
   else if(strtoupper($gender)=="G" || strtoupper($gender)=="F") $gender="Girls";
   $sql="SELECT $returnfield FROM nsaastatetrack.trevents WHERE ";
   if($eventdistcode) $sql.="eventdistcode='$eventdistcode' AND ";
   if($gender) $sql.="gender='$gender' AND ";
   if(!$eventdistcode && !$gender) return FALSE;
   $sql=substr($sql,0,strlen($sql)-5);
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) return FALSE;
   else return $row[0];
}
function IsRelay($eventid)
{
   $sql="SELECT * FROM nsaastatetrack.trevents WHERE id='$eventid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[relay]=='x') return TRUE;
   else return FALSE;
}
function IsTrack($eventid)
{
   $sql="SELECT * FROM nsaastatetrack.trevents WHERE id='$eventid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[track]=='x') return TRUE;
   else return FALSE;
}
function ValidPerformance($eventid,$perf1,$perf2)
{
   //CHECK PERFORMANCE AGAINST BOUNDARIES OF THE EVENT TO SEE IF IT MAKES SENSE FOR THIS EVENT
   //EXAMPLE: For the 800m, 2:10 is an acceptable performance, 0:36 and 8:02 are not.
   $sql="SELECT * FROM nsaastatetrack.trevents WHERE id='$eventid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) return FALSE;
   //CHECK FOR THINKS LIKE SECONDS OVER 59 AND inches OVER 11
   if($perf2>=60 && IsTrack($eventid)) return FALSE;
   else if($perf2>=12 && !IsTrack($eventid)) return FALSE;
   if($perf1>$row[maxperf1] || ($perf1==$row[maxperf1] && $perf2>$row[maxperf2]))
         return FALSE;
   else if($perf1<$row[minperf1] || ($perf1==$row[minperf1] && $perf2<$row[minperf2]))
         return FALSE;
   else
         return TRUE;
}
function GetResults($distid,$gender,$event,$export = 0,$extraqual=FALSE)
{
   //This function "gets" the district track and field results for a certain district and event
   //The differentiator in what happens in this function is mainly the $export parameter
   //$export = 0: Just outputting the results as HTML
   //$export = 1: Returning CSV for Excel/comma-delimited export
   //$export = 2: Used by transferdistresults.php to sent results to nsaastatetrack DB
   require '../variables.php';
   require_once('../../calculate/functions.php');

   $db1="nsaascores";
   $db2="nsaaofficials";
   $year=date("Y")-1;
   $statedb="nsaastatetrack";
   $qualtable="trstatequalifiers";

   $sql="SELECT * FROM $db2.tr".$gender."districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row['class']; $district=$row[district];

   //$csv="\"$event\"\r\n";
   $eventcode=$class.$district.strtoupper($gender).strtoupper($event);

   if($event=="extraqual" || $extraqual)
   {
      $sql="SELECT * FROM $db1.tr_state_extra_".$gender." WHERE district='$distid' ";
      if($extraqual) $sql.="AND eventnum='$event'";
      else $sql.="ORDER BY eventnum";
      $result=mysql_query($sql);
      $info="";
      if(!$extraqual) 
      {
         $info="<table class=nine cellspacing=2 cellpadding=2><caption><b>Extra Field Event Qualifiers:</b></caption>";
         $info.="<tr align=center><th class=smaller>Event</th><th class=smaller>Name</th><th class=smaller>School</th><th class=smaller>Grade</th><th class=smaller>Place</th><th class=smaller>Performance<br>(ft in)</th></tr>";
      }
      while($row=mysql_fetch_array($result))
      {
	 $eventcode=$class.$district.strtoupper($gender).strtoupper($row[eventnum]);
	 $eventid=GetEventField($row[eventnum],$gender);
         $curevent=GetEventLong($row[eventnum],$gender);
         $sql2="SELECT first,last,semesters,school FROM $db1.eligibility WHERE id='$row[student_id]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
   	 if(ereg("[(]",$row2[first]))      //nickname
   	 {
      	    $first_nick=explode("(",$row2[first]);
      	    $first_nick[1]=trim($first_nick[1]);
      	    $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      	    $row2[first]=$first;
   	 }
         $first=trim($row2[first]);
         $last=trim($row2[last]);
 	 $row[place]=ereg_replace("[^0-9]","",$row[place]);
	 $info.="<tr align='left'>";
         if(!$extraqual)
            $info.="<td>$curevent</td><td>$first $last</td><td>".GetSchoolName($row[school],'tr'.$gender)."</td><td>".GetYear($row2[semesters])."</td><td>$row[place]</td><td>$row[perf1] ft $row[perf2] in</td></tr>";
	 else
            $info.="<td><b>$row[place]</b></td><td>$first $last</td><td>".GetYear($row2[semesters])."</td><td>".GetSchoolName($row[school],'tr'.$gender)."</td><td>$row[perf1] ft $row[perf2] in</td></tr>";
	 $csv.="\"$eventcode\",\"".GetSchoolName($row[school],'tr'.$gender)."\",\"$first\",\"$last\",\"".GetYear($row2[semesters])."\",\"$row[place]\",\"$row[perf1]\",\"$row[perf2]\"\r\n";
	 if($export==2 && $row[student_id]>0)	//THIS MEANS WE ARE PUTTING DIST RESULTS INTO STATE QUALIFIERS TABLE
	 {
	    $sql2="INSERT INTO $statedb.$qualtable (extraqual,class,district,eventid,sid,studentid,distplace,distperf1,distperf2) VALUES ('x','$class','$district','$eventid','".GetSID2($row2[school],'tr'.$gender,$year)."','$row[student_id]','$row[place]','$row[perf1]','$row[perf2]')";
	    $result2=mysql_query($sql2);
		if(mysql_error()) echo "$sql2<br>".mysql_error()."<br>";
	    //echo "$sql2<br>".mysql_error()."<br>";
	 }
      }
      if($extraqual)
      {
         if($info!='')
	    $info="<tr align=left><td colspan=5>Extra Automatic Qualifiers:</td></tr>".$info;
      }
      else $info.="</table>";
      if($export==2) return TRUE;
      else if($export) return $csv;
      else return $info;
   }//end if Extra Field Event Qualifiers

   if($event=="teamscores")
   {
      $sql="SELECT teamscores,teams,indys FROM $db1.tr_state_dist_".$gender." WHERE dist='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $teamscores=$row[0];
      $teams=$row[1]; $indys=$row[2];
      $teamdst=split("<br>",$teamscores);
      $teamscores="";
      for($i=0;$i<count($teamdst);$i++)
      {
         $teamdst[$i]=split(",",$teamdst[$i]);
         $teamname=GetSchoolName($teamdst[$i][0],'tr'.$gender);
         if($teamname!='')
            $teamscores.=$teamname.", ".trim($teamdst[$i][1])."<br>";
      }
      $info="<table class=nine cellspacing=2 cellpadding=3>
      		<caption><b>Team Scores:</b></caption>
		<tr align=left><td>
		$teamscores
		</td></tr>
		<tr align=left>
		<td><b>Number of teams participating:</b>&nbsp;&nbsp;$teams</td></tr>
		<tr align=left>
		<td><b>Total number of individuals entered:</b>&nbsp;&nbsp;$indys</th></tr>
		</table>";
      if($export==2) return TRUE;
      else if($export) return $csv;
      else return $info;
   }

   $max_track=$limit[$class][track];
   $max_field=$limit[$class][field];
   $max_relay_sh=$limit[$class][relay_sh];
   $max_relay_lg=$limit[$class][relay_lg];
   if($event=="pv" || $event=="hj" || $event=="lj" || $event=="sp" || $event=="d" || $event=="tj")
      $max=$limit[$class][field];
   else if($event=="100h" || $event=="800" || $event=="1600m" || $event=="3200m" || $event=="110" || $event=="300" || $event=="100" || $event=="200" || $event=="400m")
      $max=$limit[$class][track];
   else if($event=="3200r")
      $max=$limit[$class][relay_lg];
   else
      $max=$limit[$class][relay_sh];

   $eventlong=GetEventLong($event,$gender);
   $eventid=GetEventField($event,$gender);
   if($gender=='b') $eventlong="Boys ".$eventlong;
   else $eventlong="Girls ".$eventlong;

   $info="<table cellspacing=2 cellpadding=2 class=nine><tr align=left><td colspan=4><b>$eventlong:</b></td></tr>"; 

   if($event!="400r" && $event!="1600r" && $event!="3200r")	//NON-RELAYS
   {
      $sql="SELECT * FROM $db1.tr_state_qual_".$gender." WHERE district='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)>0)
      {
         $eventsch="sch_".$event;
         $eventstud="stud_".$event;
         $eventperf="perf_".$event;
         $eventplace="place_".$event;
         $eventtie="tie_".$event;
         $sch[$event]=split(",",$row[$eventsch]);
         $stud[$event]=split(",",$row[$eventstud]);
         $perf[$event]=split(",",$row[$eventperf]);
         $place[$event]=split(",",$row[$eventplace]);
         $tie[$event]=split(",",$row[$eventtie]);
      }
   }
   else //RELAYS
   {
      $sql="SELECT sch,perf FROM $db1.tr_state_relays_".$gender." WHERE district='$distid' AND relay='$event' ORDER BY place";
      $result=mysql_query($sql);
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         $sch[$event][$ix]=$row[0];
         $perf[$event][$ix]=$row[1];
         $ix++;
      }
   }
   if(!ereg("Meter",$eventlong) && !ereg("Extra",$eventlong) && !ereg("Team",$eventlong))
   {
      $sql="SELECT * FROM $db1.tr_state_place_".$gender." WHERE district='$distid'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $curplace=split("/",$row[$event]);
      }
   }
   for($i=0;$i<$max;$i++) //for each place, one row:
   {
      $schools=""; $co_op=0;
      //PLACE
      if($event=="pv" || $event=="hj")
      {
         $thisplace=$place[$event][$i];
      }
      else if($event=="lj" || $event=="tj" || $event=="sp" || $event=="d")
      {
         if(trim($curplace[$i])!="") $thisplace=$curplace[$i];
         else $thisplace=$i+1;
      }
      else
         $thisplace=$i+1;
      $thisplace=ereg_replace("[^0-9]","",$thisplace);
      $info.="<tr valign=top align=center><th>$thisplace</th>";
      $cur_perf=trim($perf[$event][$i]);
      if(substr($cur_perf,strlen($cur_perf)-1,1)=="/") $cur_perf=substr($cur_perf,0,strlen($cur_perf)-1);
      if(ereg("/",$cur_perf))
      {
	  $temp=explode("/",$cur_perf);
	  $cur_perf=$temp[0];
      }
      $cur_perf=trim($cur_perf);
      $perfparts=preg_split("/([^0-9])/",$cur_perf);
      $part2="";
      if($event=="lj" || $event=="tj" || $event=="sp" || $event=="d" || $event=="pv" || $event=="hj" || ereg(":",$cur_perf) || ereg("-",$cur_perf)) { $part1=$perfparts[0]; $start=1; }
      else { $part1=0; $start=0; }
      for($p=$start;$p<count($perfparts);$p++)
      {
         $part2.=$perfparts[$p].".";
      }
      $part2=substr($part2,0,strlen($part2)-1);
      $csv.="\"$eventcode\",\"".GetSchoolName($sch[$event][$i],'tr'.$gender)."\",";
      //STUDENT
      $sch2[$event][$i]=ereg_replace("\'","\'",$sch[$event][$i]);
      $temp=$sch2[$event][$i];
      $info.="<td align=left>";
      if($event=="400r" || $event=="1600r" || $event=="3200r")  //relays
      {
	 //IN 2011-12, Class C had a tie in 400r, so only 15 teams went
         $sql="SELECT * FROM $db1.tr_state_relays_".$gender." WHERE district='$distid' AND relay='$event' ORDER BY place";
         $result=mysql_query($sql);
         $ix=0;
         while($row=mysql_fetch_array($result))
         {
            $relay_stud[$event][$ix]=split(",",$row[stud]);
            $ix++;
         }
         $num=4;
         $stud_list=""; $sem_list="";
         for($k=0;$k<$num;$k++)
         {
            $id=$relay_stud[$event][$i][$k];
            $sql2="SELECT last, first, semesters,school FROM $db1.eligibility WHERE id='$id'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            if(ereg("[(]",$row2[first]))      //nickname
            {
               $first_nick=explode("(",$row2[first]);
               $first_nick[1]=trim($first_nick[1]);            
	       $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
               $row2[first]=$first;
            }         
	    $first=trim($row2[first]);
            $last=trim($row2[last]);
            $stud_list.=$first." ".$last."&nbsp;<br>";
            $sem_list.=GetYear($row2[2])."&nbsp;<br>";
	    $csv.="\"$first\",\"$last\",\"".GetYear($row2[semesters])."\",";
            if($export==2 && $id>0) //THIS MEANS WE ARE PUTTING DIST RESULTS INTO STATE QUALIFIERS TABLE
            {
               $sql2="INSERT INTO $statedb.$qualtable (class,district,eventid,sid,studentid,distplace,distperf1,distperf2) VALUES ('$class','$district','$eventid','".GetSID2($row2[school],'tr'.$gender,$year)."','$id','$thisplace','$part1','$part2')";
               $result2=mysql_query($sql2);
            }
         }
         $info.=$stud_list."</td><td>".$sem_list;
      }//end if relay
      else
      {
         $id=$stud[$event][$i];
         $sql2="SELECT first,last,semesters,school FROM $db1.eligibility WHERE id='$id'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(ereg("[(]",$row2[first]))      //nickname
         {
            $first_nick=explode("(",$row2[first]);
            $first_nick[1]=trim($first_nick[1]);            
	    $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            $row2[first]=$first;
         }         
	 $first=trim($row2[first]);
         $last=trim($row2[last]);
         $info.=$first." ".$last."&nbsp;";
         $info.="</td><td>".GetYear($row2[2])."&nbsp;";
	 $csv.="\"$first\",\"$last\",\"".GetYear($row2[semesters])."\",";
         if($export==2 && $id>0) //THIS MEANS WE ARE PUTTING DIST RESULTS INTO STATE QUALIFIERS TABLE            
    	 {
            $sql2="INSERT INTO $statedb.$qualtable (class,district,eventid,sid,studentid,distplace,distperf1,distperf2) VALUES ('$class','$district','$eventid','".GetSID2($row2[school],'tr'.$gender,$year)."','$id','$thisplace','$part1','$part2')";
            $result2=mysql_query($sql2);
         }
      }
      $info.="</td>";
      $info.="<td valign=top align=left>";
      $info.=GetSchoolName($sch[$event][$i],'tr'.$gender)."&nbsp;</td>";
      $info.="<td align='left'>$cur_perf&nbsp;</td></tr>";
      $csv.="\"$thisplace\",\"$part1\",\"$part2\"\r\n";
   }
   if($event=="pv" || $event=="hj")	//TIES ARE POSSIBLE, WHICH MEANS EXTRA QUALIFIERS
   {
      $i2=$i-1;
      while($i<count($perf[$event]) && $tie[$event][$i2]=="yes")
      {
         $schools=""; $co_op=0;
         $thisplace=$place[$event][$i];
         $thisplace=ereg_replace("[^0-9]","",$thisplace);
         $info.="<tr valign=top align=center><th>$thisplace</th>";
         $sch2[$event][$i]=ereg_replace("\'","\'",$sch[$event][$i]);
         $temp=$sch2[$event][$i];
         $info.="<td align=left>";
         $id=$stud[$event][$i];
         $sql2="SELECT first,last,semesters,school FROM $db1.eligibility WHERE id='$id'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(ereg("[(]",$row2[first]))      //nickname
         {
            $first_nick=explode("(",$row2[first]);
            $first_nick[1]=trim($first_nick[1]);
            $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            $row2[first]=$first;
         }
         $first=trim($row2[first]);
         $last=trim($row2[last]);
         $info.=$first." ".$last."&nbsp;";
         $info.="</td><td>".GetYear($row2[2])."&nbsp;";
         $cur_perf=$perf[$event][$i];
         if(substr($cur_perf,strlen($cur_perf)-1,1)=="/") $cur_perf=substr($cur_perf,0,strlen($cur_perf)-1);
         $info.="</td>";
         $info.="<td valign=top align=left>";
         $info.=GetSchoolName($sch[$event][$i],'tr'.$gender)."&nbsp;</td>";
         $info.="<td>$cur_perf&nbsp;</td></tr>";
         $perfparts=preg_split("/([^0-9])/",$cur_perf);
         $part2="";
         for($p=1;$p<count($perfparts);$p++)
         {
            $part2.=$perfparts[$p].".";
         }
         $part2=substr($part2,0,strlen($part2)-1);
         $csv.="\"EXTRA $eventcode\",\"".GetSchoolName($sch[$event][$i],'tr'.$gender)."\",\"$first\",\"$last\",\"".GetYear($row2[semesters])."\",\"$thisplace\",\"$perfparts[0]\",\"$part2\"\r\n";
         if($export==2 && $id>0) //THIS MEANS WE ARE PUTTING DIST RESULTS INTO STATE QUALIFIERS TABLE            
         {
            $sql2="INSERT INTO $statedb.$qualtable (class,district,eventid,sid,studentid,distplace,distperf1,distperf2) VALUES ('$class','$district','$eventid','".GetSID2($row2[school],'tr'.$gender,$year)."','$id','$thisplace','$perfparts[0]','$part2')";
            $result2=mysql_query($sql2);
         }
         $i++; $i2=$i-1;
      }
   }
   if(!IsTrack(GetEventField($event,$gender)) && $export==0)
   {
      //GET ANY ADDITIONAL FIELD EVENT QUALIFIERS FOR THIS EVENT
      //This is unecessary for $export>0 
      $info.=GetResults($distid,$gender,$event,0,TRUE);
   }
   $info.="</table>";
   if($export) return $csv;
   else return $info;
}
function FormatPerformance($class="",$eventid,$perf1,$perf2,$auto=FALSE)
{
   $sql="SELECT * FROM nsaastatetrack.trevents WHERE id='$eventid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(trim($perf1)=="") $perf1=0;
   if(trim($perf2)=="") $perf2=0;
   if($perf1==0 && $perf2==0) return "";
   if($row[field]=='x')
   {
      if(trim($perf2)=="") $perf2=0;
      $perf2=number_format($perf2,2,'.','');
      if($perf2<10) $perf2="0".$perf2;
      return "$perf1-$perf2";
   }
   else
   {
      if(trim($perf2)=="") $perf2=0;
      if($auto=="hand") //$auto value overrides other
         $perf2=number_format($perf2,1,'.','');
      else if($auto=="auto")
         $perf2=number_format($perf2,2,'.','');
      else if($class=="A" || $class=="B" || $class=="C" || $class=="D") $perf2=number_format($perf2,2,'.','');       //AUTO TIMING (as of 2018, ALL CLASSES)
      else if($class=="") $perf2=number_format($perf2,3,'.','');        //STATE MEET: 3 decimal places
      else $perf2=number_format($perf2,1,'.','');       //HAND TIMING
      if($perf2<10) $perf2="0".$perf2;
      if($perf1==0) return $perf2;
      else return "$perf1:$perf2";
   }
}
function IsBoys($eventid)
{
   $sql="SELECT * FROM nsaastatetrack.trevents WHERE id='$eventid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[gender]=='Boys') return TRUE;
   else return FALSE;
}
?>
