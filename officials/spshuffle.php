<?php

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db("$db_name2", $db);

$level=GetLevelJ($session);
if(!(ValidUser($session)) || $level!=1)
{
   header("Location:jindex.php?error=1");
   exit();
}

$dbscores=$db_name;
$dboffs=$db_name2;

echo $init_html_ajax;
echo "<script type=\"text/javascript\" src=\"/javascript/StateSpeech.js\"></script>";
echo "</head>";
?>
<body onload="StateSpeech.initialize('');">
<?php
echo GetHeaderJ($session,"statespeech");

if($swap1 || $swap2)
{
   if($swap1) $round=1;
   else $round=2;
   //get room ID for each student/group of students to be swapped
   $eventname=GetSpeechEvent($event);
   $sql="SELECT id FROM $dboffs.spstaterounds WHERE class='$class' AND event='$eventname' AND round='$round'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $roundid=$row[id];
   $first=""; $next="";
   for($i=0;$i<$count;$i++)
   {
      $varname3="check".$i; $varname1="student".$i."id";
      if($$varname3=='x' && $first=="") $first=$$varname1;
      else if($$varname3=='x') $next=$$varname1;
   }
   if($first=="" || $next=="")
   {
      $swapmsg="<font style=\"color:red;font-size:8pt\"><b>You have clicked a \"Swap\" button but have not checked 2 students.  Please check 2 students before you click \"Swap\".</b></font>";
   }
   else
   {
   $sql="SELECT t1.* FROM $dboffs.spshuffle AS t1, $dboffs.spstaterooms AS t2 WHERE t1.roomid=t2.id AND t2.roundid='$roundid' AND (t1.studentids LIKE '$first/%' OR t1.studentids LIKE '%/$first/%' OR t1.studentids LIKE '%/$first')";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $curid1=$row[id];
   $sql2="SELECT t1.* FROM $dboffs.spshuffle AS t1, $dboffs.spstaterooms AS t2 WHERE t1.roomid=t2.id AND t2.roundid='$roundid' AND (t1.studentids LIKE '$next/%' OR t1.studentids LIKE '%/$next/%' OR t1.studentids LIKE '%/$next')";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $curid2=$row2[id];
   if($curid1!=$curid2)
   {
   $studs=split("/",$row[studentids]);
   $studentids="";
   for($i=0;$i<count($studs);$i++)
   {
      if($studs[$i]==$first) $studs[$i]=$next;
      if($studs[$i]!='')
         $studentids.=$studs[$i]."/";
   }
   $studentids=substr($studentids,0,strlen($studentids)-1); 
   $sql="UPDATE spshuffle SET studentids='$studentids' WHERE id='$curid1'";
   $result=mysql_query($sql);

   $studs=split("/",$row2[studentids]);
   $studentids="";
   for($i=0;$i<count($studs);$i++)
   {
      if($studs[$i]==$next) $studs[$i]=$first;
      if($studs[$i]!='')
         $studentids.=$studs[$i]."/";
   }
   $studentids=substr($studentids,0,strlen($studentids)-1);
   $sql="UPDATE spshuffle SET studentids='$studentids' WHERE id='$curid2'";
   $result=mysql_query($sql);
   }
   else
   {
       $studs=split("/",$row[studentids]);
       $studentids=""; 
       for($i=0;$i<count($studs);$i++)
       {
          if($studs[$i]==$first) $studs[$i]=$next;
	  else if($studs[$i]==$next) $studs[$i]=$first;
          if($studs[$i]!='')
             $studentids.=$studs[$i]."/";
      }
      $studentids=substr($studentids,0,strlen($studentids)-1);
      $sql="UPDATE spshuffle SET studentids='$studentids' WHERE id='$curid1'";
      $result=mysql_query($sql);
   }

   if(!ereg("Duet",$eventname) && !ereg("Drama",$eventname))
   {
      $sql="SELECT first,last,school FROM $dbscores.eligibility WHERE (id='$first' OR id='$next')";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $swapmsg="<font style=\"color:blue;font-size:8pt;\"><b>$row[first] $row[last] ($row[school]) and ";
      $row=mysql_fetch_array($result);
      $swapmsg.="$row[first] $row[last] ($row[school]) have been swapped within Round $round.</b></font>";
   }
   else
   {
      $temp=split(",",$first);
      $sql="SELECT * FROM $dbscores.eligibility WHERE (";
      for($i=0;$i<count($temp);$i++)
      {
         $sql.="id='$temp[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=")";
      $result=mysql_query($sql);
      $firststr="";
      while($row=mysql_fetch_array($result))
      {
         if($firststr=="") $firststr.="$row[school] (";
         $firststr.="$row[first] $row[last], ";
      }
      $firststr=substr($firststr,0,strlen($firststr)-2);
      $firststr.=")";
      $temp=split(",",$next);
      $sql="SELECT * FROM $dbscores.eligibility WHERE (";
      for($i=0;$i<count($temp);$i++)
      {
         $sql.="id='$temp[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=")";
      $result=mysql_query($sql);
      $nextstr="";
      while($row=mysql_fetch_array($result))
      {
         if($nextstr=="") $nextstr.="$row[school] (";
         $nextstr.="$row[first] $row[last], "; 
      }
      $nextstr=substr($nextstr,0,strlen($nextstr)-2);       
      $nextstr.=")";
      $swapmsg="<font style=\"color:blue;font-size:8pt;\"><b>$firststr and $nextstr have been swapped within Round $round.</b></font>";
   }
   }//end if no swap error
}
else if($reset)
{
   $eventname=GetSpeechEvent($event);
   $sql="SELECT t1.id FROM $dboffs.spstaterooms AS t1, $dboffs.spstaterounds AS t2 WHERE t1.roundid=t2.id AND t2.class='$class' AND t2.event='$eventname' AND (t2.round='1' OR t2.round='2')";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $roomid=$row[id];
      $sql2="DELETE FROM $dboffs.spshuffle WHERE roomid='$roomid'";
      $result2=mysql_query($sql2);
   }
}
else if($save || $hiddensave)	//save Student & Judge assignments
{
   $eventname=GetSpeechEvent($event);
   /***** JUDGES: Reset current judge assignments for this class & event, insert what's entered on screen *****/
   $sql="SELECT t1.id FROM $dboffs.spstateassign AS t1, $dboffs.spstaterooms AS t2, $dboffs.spstaterounds AS t3 WHERE t1.roomid=t2.id AND t2.roundid=t3.id AND t3.class='$class' AND t3.event='$eventname'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="DELETE FROM $dboffs.spstateassign WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
   }
   for($i=0;$i<$total;$i++)
   {
      $var1="offid".$i; 
      if($$var1!='0')
      {
         $sql2="SELECT * FROM $dboffs.spstateassign WHERE roomid='$roomid[$i]' AND offid='".$$var1."'";
    	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)==0)
	 {
            $sql="INSERT INTO $dboffs.spstateassign (roomid,offid) VALUES ('$roomid[$i]','".$$var1."')";
            $result=mysql_query($sql);
            //echo "$sql<br>".mysql_error()."<br>";
	 }
         else if(mysql_num_rows($result2)>1)	//TOO MANY, CLEAN UP
	 {
	    $row2=mysql_fetch_array($result2);
	    $sql="DELETE FROM $dboffs.spstateassign WHERE roomid='$roomid[$i]' AND offid='".$$var1."' AND id!='$row2[id]'";
	    $result=mysql_query($sql);
	 }
      }
   }
   /***** STUDENTS *****/
   switch($class)       //Class A: Top 4 places qualify for State; Top 3 for other classes
   {
      case "A":
         $persection=5;
         break;
      default;
         $persection=6;
   }
   $start=0; $end=$persection;	
   $studix=0;
   for($i=0;$i<($total-1);$i++)	// IGNORE FINAL ROOM - ONLY JUDGES ASSIGNED
   {
      //if($roomid[$i]==224 || $roomid[$i]==251) $end++;
	  if($roomid[$i]>36 && $roomid[$i]<55) $end++;
      $curroomid=$roomid[$i];	//SAVE STUDENTS FOR THIS ROOM
      $studstr="";
      for($j=$start;$j<$end;$j++)
      {
         $var1="student".$j."id";
	 $studstr.=$$var1."/";
	//echo "$j of $end - $var1: ".$$var1.", ";
      }
      if($studstr!='') $studstr=substr($studstr,0,strlen($studstr)-1);
      $sql="UPDATE $dboffs.spshuffle SET studentids='$studstr' WHERE roomid='$curroomid'";
    //echo "<br>$sql<br><br>";
      $result=mysql_query($sql);
      $start=$end; $end+=$persection;
   }
}
else if($shuffle)
{
   switch($class)	//Class A: Top 4 places qualify for State; Top 3 for other classes
   {
      case "A":
         $max=3;
         break;
      default;
         $max=3;
   }
   
   $eventname=GetSpeechEvent($event);

   /**********************************************
   GET QUALIFIERS FROM EACH DISTRICT IN THIS CLASS
   **********************************************/
   $order="district";
   $sql="SELECT * FROM ".$dboffs.".spdistricts WHERE class='$class' ORDER BY $order";
   $result=mysql_query($sql);
   $qualifiers=array(); $ix=0;	//NAMES OF QUALIFIERS FROM EACH DISTRICT IN PLACE ORDER
   $qualsch=array();		//SCHOOLS "" "" "" ""
   $qualids=array();		//IDS "" "" "" ""
   while($row=mysql_fetch_array($result))
   {
      $distid=$row[id];
      if($event=="dram") $table="sp_state_drama";	//DRAMA QUALIFIERS
      else if($event=="duet") $table="sp_state_duet";	//DUET QUALIFIERS
      else $table="sp_state_qual";			//OTHER QUALIFIERS
      $field=$event."_stud";
      $field2=$event."_sch";
      if($event=="dram" || $event=="duet")		//MULTIPLE QUALIFIERS PER PLACE
      {
         $sql2="SELECT t1.$field,t2.school FROM $dbscores.$table AS t1, $dbscores.spschool AS t2 WHERE t1.$field2=t2.sid AND t1.dist_id='$distid' ORDER BY t1.place";
         $result2=mysql_query($sql2);
         while($row2=mysql_fetch_array($result2))
         {
            $studs=split(",",$row2[$field]);	//STUDENT ID's ARE COMMA-DELIMITED
            $qualifiers[$ix]=""; $qualids[$ix]="";
	    for($k=0;$k<count($studs);$k++)	//FOR EACH STUDENT, GET NAME
	    {
	       $studs[$k]=trim($studs[$k]);
	       if($studs[$k]!='')
	       {
	          $sql3="SELECT * FROM $dbscores.eligibility WHERE id='$studs[$k]'";
	          $result3=mysql_query($sql3);
	          $row3=mysql_fetch_array($result3);
	          $qualifiers[$ix].="$row3[first] $row3[last], ";
	          $qualids[$ix].="$row3[id],";
	       }
	    }
	    $qualifiers[$ix]=substr($qualifiers[$ix],0,strlen($qualifiers[$ix])-2);
	    $qualifiers[$ix].=" ($row2[school])";	//NOW FOR THIS DISTRICT WE HAVE LIST OF NAMES WITH SCHOOL AT END, LIST OF ID's and JUST SCHOOL
	    $qualids[$ix]=substr($qualids[$ix],0,strlen($qualids[$ix])-1);	//LIST OF ID's
	    $qualsch[$ix]=$row2[school];					//JUST SCHOOL
	    $ix++;								//go to next qualifier(s)
	 }
         while($ix%$max>0)	//ACCOUNT FOR MISSING QUALIFIERS FROM TOP $max PLACES
         {
               $qualifiers[$ix]='NO ENTRY ???'; $qualids[$ix]=0; $ix++;
         }
      }
      else	//NON-DRAMA/DUET: SINGLE QUALIFIER PER PLACE
      {
         $sql2="SELECT $field FROM $dbscores.$table WHERE dist_id='$distid'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $studs=split(",",$row2[$field]);		//STUDENT ID's FOR TOP PLACES ARE COMMA-DELIMITED
//echo $sql2."<br>".$row2[$field];
	 for($k=0;$k<count($studs);$k++)
	 {
            $sql3="SELECT * FROM $dbscores.eligibility WHERE id='$studs[$k]'";	//GET NAMES OF ALL OF THESE STUDENTS
            $result3=mysql_query($sql3);
            $row3=mysql_fetch_array($result3);
//echo "$sql3<br>".mysql_error()." ".$row3[first]."<br>";
            $qualifiers[$ix]="$row3[first] $row3[last] ($row3[school])";	//NAME AND SCHOOL
	    $qualids[$ix]=$row3[id];						//ID
            $qualsch[$ix]=$row3[school];					//JUST SCHOOL
            $ix++;								//go to next qualifier
         }
         while($ix%$max>0)	//ACCOUNT FOR MISSING QUALIFIERS FROM TOP $max PLACES
         {
            $qualifiers[$ix]='NO ENTRY ???'; $qualids[$ix]=0; $ix++;
         }
      }
   }//end for each district

   /********************************************************************************
   Now put qualifiers into rounds and sections (a section is indicated by the roomid)
   *********************************************************************************/

   $sql="SELECT t2.id,t1.round,t2.section FROM $dboffs.spstaterounds AS t1, $dboffs.spstaterooms AS t2 WHERE t1.id=t2.roundid AND t1.class='$class' AND t1.event='$eventname' AND (t1.round='1' OR t1.round='2') ORDER BY t1.round,t2.section";
   $result=mysql_query($sql);	//GET ROOMID FOR EACH SECTION FOR THIS CLASS & EVENT, ORDERED BY ROUND # AND SECTION #
   if($class=='A')
   {
      while($row=mysql_fetch_array($result))
      {
	 $roomid=$row[id];
         if($row[round]=='1' && $row[section]=='1')	    	//ROUND 1 SECTION 1
         {
	    //District, Place: 1,1 2,4 3,2 4,3 (So first person in this section is District 1 1st Place Finisher, etc.)
	    //District 1, 1st place: 1st set of 4 qualifiers, 1st qualifier: ((1-1)*4)+(1-1)
	    $studentids="$qualids[0]/";
	    //District 2, 4th place: 2nd set of 4, 4th qualifier: ((2-1)*4)+(4-1)
	    $studentids.="$qualids[7]/";
	    //District 3, 2nd place: 3rd set, 2nd qualifier:	((3-1)*4)+(2-1)
	    $studentids.="$qualids[9]/";
	    //District 4, 3rd qualifier:	((4-1)*4)+(3-1)
	    $studentids.="$qualids[14]/";
	    $studentids.="$qualids[4]";
	 }
	 else if($row[round]=='2' && $row[section]=='1')	//ROUND 2 SECTION 1
	 {
	    //4,3  1,2  3,4  2,1
	    $studentids="$qualids[14]/$qualids[1]/$qualids[11]/$qualids[4]/$qualids[2]";
	 }
	 else if($row[round]=='1' && $row[section]=='2')	//ROUND 1 SECTION 2
	 {
	    //4,4  3,1  2,3  1,2
	    $studentids="$qualids[15]/$qualids[8]/$qualids[6]/$qualids[1]/$qualids[3]";
	 }
	 else if($row[round]=='2' && $row[section]=='2')	  //ROUND 2 SECTION 2
	 {
	    //3,3  2,2  1,1  4,4
	    $studentids="$qualids[10]/$qualids[5]/$qualids[0]/$qualids[15]/$qualids[13]";
	 }
	 else if($row[round]=='1' && $row[section]=='3')	//ROUND 1 SECTION 3
	 {
	    //3,4  4,1  1,3  2,2
	    $studentids="$qualids[11]/$qualids[12]/$qualids[2]/$qualids[5]/$qualids[13]/$qualids[10]";
	 }
         else if($row[round]=='2' && $row[section]=='3')	//ROUND 2 SECTION 3
	 {
	    //2,3  3,2  4,1  1,4
	    $studentids="$qualids[6]/$qualids[9]/$qualids[12]/$qualids[3]/$qualids[7]/$qualids[8]";
	 }
         /*else if($row[round]=='1' && $row[section]=='4')        //ROUND 1 SECTION 4
         {
            //2,1  1,4  4,2  3,3
            $studentids="$qualids[4]/$qualids[3]/$qualids[13]/$qualids[10]";
         }
         else if($row[round]=='2' && $row[section]=='4')        //ROUND 2 SECTION 4
         {
            //1,3  4,2  2,4  3,1
            $studentids="$qualids[2]/$qualids[13]/$qualids[7]/$qualids[8]";
         }*/
	 else
	 {
	    echo "ERROR: Unknown Round/Section Combo: Round $row[round], Section $row[section]<br>";
	 }
	 $sql2="SELECT * FROM $dboffs.spshuffle WHERE roomid='$roomid'";
	 $result2=mysql_query($sql2);
	 if(mysql_num_rows($result2)>0)	//UPDATE
	    $sql3="UPDATE $dboffs.spshuffle SET studentids='$studentids' WHERE roomid='$roomid'";
	 else
	    $sql3="INSERT INTO $dboffs.spshuffle (roomid,studentids) VALUES ('$roomid','$studentids')";
	 $result3=mysql_query($sql3);
      }
   }//end if CLASS A
   else 	//CLASSES BESIDES A
   {
      while($row=mysql_fetch_array($result))
      {
         $roomid=$row[id];
         if($row[round]=='1' && $row[section]=='1')	//ROUND 1 SECTION 1
         {
	    //2,3  3,2  1,1  5,2  4,1  6,3
            $studentids="$qualids[5]/$qualids[7]/$qualids[0]/$qualids[13]/$qualids[9]/$qualids[17]";
	 }
	 else if($row[round]=='2' && $row[section]=='1')	//ROUND 2 SECTION 1
	 {
	    //5,3  1,1  4,1  6,2  2,2  3,3
            $studentids="$qualids[14]/$qualids[0]/$qualids[9]/$qualids[16]/$qualids[4]/$qualids[8]";
         }
	 else if($row[round]=='1' && $row[section]=='2')	//ROUND 1 SECTION 2
	 {
	    //6,1  4,3  2,2  3,1  5,3  1,2
            $studentids="$qualids[15]/$qualids[11]/$qualids[4]/$qualids[6]/$qualids[14]/$qualids[1]";
	 }
	 else if($row[round]=='2' && $row[section]=='2')	//ROUND 2 SECTION 2
	 {
	    //1,2  6,3  5,1  2,3  3,1  4,2
            $studentids="$qualids[1]/$qualids[17]/$qualids[12]/$qualids[5]/$qualids[6]/$qualids[10]";
	 }
	 else if($row[round]=='1' && $row[section]=='3')	//ROUND 1 SECTION 3
	 {
	    //4,2  5,1  3,3  1,3  6,2  2,1
            $studentids="$qualids[10]/$qualids[12]/$qualids[8]/$qualids[2]/$qualids[16]/$qualids[3]";
       	 }
	 else if($row[round]=='2' && $row[section]=='3')	//ROUND 2 SECTION 3
	 {
	    //3,2  2,1  6,1  4,3  1,3  5,2
            $studentids="$qualids[7]/$qualids[3]/$qualids[15]/$qualids[11]/$qualids[2]/$qualids[13]";
	 }
         else
	    echo "ERROR: Unknown Round/Section Combo: Round $row[round], Section $row[section]<br>";
         $sql2="SELECT * FROM $dboffs.spshuffle WHERE roomid='$roomid'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0)      //UPDATE
            $sql3="UPDATE $dboffs.spshuffle SET studentids='$studentids' WHERE roomid='$roomid'";
         else
            $sql3="INSERT INTO $dboffs.spshuffle (roomid,studentids) VALUES ('$roomid','$studentids')";
         $result3=mysql_query($sql3);
      }
   }//end if class besides A
}//end if shuffle

echo "<br><form name=\"assignform\" method=post action=\"spshuffle.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>State Speech Student & Judge Assignments:</b><br>";
echo "Select a Class: <select name=\"class\" onchange=\"submit();\"><option value=''>~</option>";
for($i=0;$i<count($classes);$i++)
{
   echo "<option";
   if($class==$classes[$i]) echo " selected";
   echo ">$classes[$i]</option>";
}
echo "</select>&nbsp;Select an Event:<select name=\"event\" onchange=\"submit();\"><option value=''>~</option>";
for($i=0;$i<count($spevents2);$i++)
{
   echo "<option value=\"$spevents[$i]\"";
   if($event==$spevents[$i]) echo " selected";
   echo ">$spevents2[$i]</option>";
}
echo "</select>";
echo "</caption>";
if($class && $class!='')
{
   echo "<tr align=center><td>";
   echo "<a class=small href=\"sprooms.php?session=$session&class=$class\">Edit Class $class Round Dates & Times and Room Numbers</a></td></tr>";
}
if($class && $class!='' && $event && $event!='')
{
   $ix=0; $jx=0;
   echo "<tr align=center valign=top>";
   $eventname=GetSpeechEvent($event);
   echo "<th align=center><br><b>Class $class $eventname:</b><br>";
   if($swapmsg!='') echo "<table width=500><tr align=left><td>".$swapmsg."</td></tr></table><br>";
   echo "<div style='width:700px;' class='alert'><u>NOTE:</u> If a student was replaced on the DISTRICT RESULTS form*, you can replace the original student with the new student below by deleting the original student's name and typing the new student's name, which will result in a \"lookup\" that will return matching students. Select the new student from the lookup list and click \"Save ALL Assignments\" at the bottom of this screen.<br><br>* A student MUST be listed on the DISTRICT RESULTS/STATE QUALIFIERS FORM in order to add them to this page.</div>";
   echo "<table cellspacing=3 cellpadding=3><tr align=center valign=top>";
   $sql="SELECT * FROM $dboffs.spstaterounds WHERE class='$class' AND event='$eventname' AND (round='1' OR round='2') ORDER BY round";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $roundid=$row[id];
      echo "<td><u><b>ROUND $row[round]:</b></u><br>";
      $sql2="SELECT * FROM $dboffs.spstaterooms WHERE roundid='$roundid' ORDER BY section LIMIT 3";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))	//FOR EACH ROOM:
      {
         $roomid=$row2[id];
         echo "<br><b>Room $row2[room]: ($roomid)<br></b>";
	 $sql3="SELECT * FROM $dboffs.spstateassign WHERE roomid='$roomid'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 echo "<b>Judge:</b>&nbsp;";
	 echo "<input type=hidden name=\"roomid[$jx]\" value=\"$roomid\">";
         if(mysql_num_rows($result3)==0)
	 {
	    echo "<input type=hidden name=\"offid".$jx."\" value=\"0\">";
	    echo "<input type=text class=tiny size=20 name=\"offname$jx\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick3.php?session=$session&jx=$jx&roomid=$roomid','judgespick','resizable=yes,scrollbars=yes,location=no');\"><br>";
         }
         else
         {
	    echo "<input type=hidden name=\"offid".$jx."\" value=\"$row3[offid]\">";
            echo "<input type=text class=tiny size=20 name=\"offname$jx\" value=\"".GetJudgeName($row3[offid])."\" onClick=\"window.open('judgespick3.php?session=$session&jx=$jx&roomid=$roomid','judgespick','resizable=yes,scrollbars=yes,location=no');\"><br>";
         }
 	 $jx++;
          $sql3="SELECT * FROM $dboffs.spshuffle WHERE roomid='$roomid'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $studs=split("/",$row3[studentids]);
	 if(ereg("Drama",$eventname) || ereg("Duet",$eventname)) { $size=40; $list="listwide"; }
	 else { $size=35; $list="list"; }
         for($j=0;$j<count($studs);$j++)
         {
	    if(!ereg(",",$studs[$j]))
	    {
	       $sql4="SELECT id,first,last,school FROM $dbscores.eligibility WHERE id='$studs[$j]'";
	       $result4=mysql_query($sql4);
	       $row4=mysql_fetch_array($result4);
	       $name="$row4[first] $row4[last] ($row4[school])";
	    }
	    else
	    {
	       $name=""; $curstud=split(",",$studs[$j]);
	       $sql4="SELECT id,first,last,school FROM $dbscores.eligibility WHERE (";
	       for($i=0;$i<count($curstud);$i++)
	       {
		  $sql4.="id='$curstud[$i]' OR ";
	       }
	       $sql4=substr($sql4,0,strlen($sql4)-4);
	       $sql4.=")";
	       $result4=mysql_query($sql4);
	       while($row4=mysql_fetch_array($result4))
	       {
	          if($name=="") $name.="$row4[school]: ";
		  $name.="$row4[first] $row4[last], ";
	       }
	       $name=substr($name,0,strlen($name)-2);
	    }
	    $varname1="student".$ix."id";
	    $varname2="student".$ix;
	    $varname3="check".$ix;
            if(mysql_num_rows($result3)==0)
            {
	       echo "<br>[No students assigned.  Please click \"Shuffle Students\" below to populate these rooms.]<br>";
	       $swapbutton=0;
            }
	    else
	    {
	       echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$studs[$j]\">";
	       echo "<input type=checkbox name=\"$varname3\" value='x'>&nbsp;";
	       $distplace=GetDistrictSpeechPlace($event,$studs[$j]);
	       $curdist=GetSpeechDistrict($event,$studs[$j]);
               echo "<input class=tiny type=text size=$size name=\"$varname2\" id=\"$varname2\" value=\"$name\" onkeyup=\"StateSpeech.lookupStudents('$varname2',this.value,'$roundid');\">";
	       if($distplace!='0')
	       {
	          if($distplace=='1') echo "&nbsp;1st";
	          else if($distplace=='2') echo "&nbsp;2nd";
	          else if($distplace=='3') echo "&nbsp;3rd";
	          else echo "&nbsp;".$distplace."th";
	       }
	       else echo "&nbsp;&nbsp;&nbsp;?????";
	       if($curdist!='0')
	          echo ", $curdist";
 	       echo "<br><div style=\"display:none\" class=\"$list\" size=$size id=\"".$varname2."List\" name=\"".$varname2."List\"></div>";
	       $ix++;
	       $swapbutton=1;
	    }
         }
      }//end each room
      echo "</td>";
   }//end each round
   //FINALS:
   $sql="SELECT t1.id,t1.room FROM $dboffs.spstaterooms AS t1, $dboffs.spstaterounds AS t2 WHERE t1.roundid=t2.id AND t2.round='3' AND t2.class='$class' AND t2.event='$eventname'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $roomid=$row[id]; $room=$row[room];

   $sql="SELECT t2.id,t2.room,t3.offid FROM $dboffs.spstaterounds AS t1, $dboffs.spstaterooms AS t2, $dboffs.spstateassign AS t3 WHERE t1.id=t2.roundid AND t1.round='3' AND t2.id=t3.roomid AND t1.class='$class' AND t1.event='$eventname'";
   $result=mysql_query($sql);
   echo "<td align=center><u><b>FINALS JUDGES:</b></u><br>";
   echo "<br><b>Room $room:<br></b>";
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<input type=hidden name=\"roomid[$jx]\" value=\"$roomid\">";
      echo "<input type=hidden name=\"offid".$jx."\" value=\"$row[offid]\">";
      echo "<input type=text class=tiny size=20 name=\"offname$jx\" value=\"".GetJudgeName($row[offid])."\" onClick=\"window.open('judgespick3.php?session=$session&jx=$jx&roomid=$roomid','judgespick','resizable=yes,scrollbars=yes,location=no');\"><br>";
      $i++; $jx++;
   }
   while($i<3)
   {
      echo "<input type=hidden name=\"roomid[$jx]\" value=\"$roomid\">";
      echo "<input type=hidden name=\"offid".$jx."\" value=\"0\">";
      echo "<input type=text class=tiny size=20 name=\"offname$jx\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick3.php?session=$session&jx=$jx&roomid=$roomid','judgespick','resizable=yes,scrollbars=yes,location=no');\"><br>";
      $i++; $jx++;
   }
   echo "</td>";
   echo "</tr>";
   if($swapbutton==1)
   {
      echo "<tr align=left><td>";
      echo "<input type=submit name=swap1 value=\"Swap\"></td>";
      echo "<td><input type=submit name=swap2 value=\"Swap\"></td></tr>";
      echo "<tr align=center><td colspan=3>";
      echo "<input type=submit name=save value=\"Save ALL Assignments\">&nbsp;&nbsp;";
      echO "<input type=submit name=reset onclick=\"return confirm('Are you sure you want to reset all the assignments for this class and event?');\" value=\"Reset STUDENT Assignments\"></td></tr>";
   }
   else
      echo "<tr align=center><td colspan=3><input type=submit name=\"save\" value=\"Save Judges\">&nbsp;&nbsp;<input type=submit name=\"shuffle\" value=\"Shuffle Students\"></td></tr>";
   echo "<input type=hidden name=hiddensave>";
   echo "</table></th></tr>";
   echo "<input type=hidden name=\"total\" value=\"$jx\">";
}
else	//no class/event given
{
   echo "<tr align=center><td><br><br><table width=500 class=nine><tr align=left><td><i>Please select a class and event to view/edit the students & judges assigned to each round and section for this event.</i></td></tr></table></td></tr>";
}
echo "<input type=hidden name=\"count\" value=\"$ix\">";
echo "</table>";
?>
<div id="debug"></div>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
