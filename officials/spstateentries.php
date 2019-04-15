<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$exportuniqquals)	//if exportuniqquals, only creating and exporting the Schools & Unique Qualifiers file (qualifiers.html)
{
   echo $init_html;
   echo GetHeaderJ($session,"statespeech");
}

$dboffs="$db_name2";
$dbscores="$db_name";

if(!$exportuniqquals)
{
   echo "<br><a href=\"statespeech.php?session=$session\" class=\"small\">Return to State Speech Main Menu</a>";
   echO "<br><br><table width=400><caption><b>State Speech Entries/Room Assignments</b><hr><br></caption>";
   echO "<tr align=center><td><ul>";

for($c=0;$c<count($classes);$c++)
{
   $class=$classes[$c];
   $no=0;
   if($class=="A") $no=1;
   $txt="";
for($i=0;$i<count($spevents3);$i++)
{
   $event=$spevents2[$i];
   $txt.="Class $class $spevents3[$i]\r\n\r\n";
   //$sql="SELECT t1.round,t1.rounddate,TIME_FORMAT(t1.time,'%l:%i %p'),t2.section,t2.room,t2.id FROM spstaterounds AS t1, spstaterooms AS t2 WHERE t1.id=t2.roundid AND t1.class='$class' AND t1.event='$event' ORDER BY t1.round,t2.section";
   $sql="SELECT id,round,TIME_FORMAT(time,'%l:%i %p') AS time FROM spstaterounds WHERE (round='1' OR round='2') AND class='$class' AND event='$event' ORDER BY round";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $row[time]=ereg_replace("AM","a.m.",$row[time]);
      $row[time]=ereg_replace("PM","p.m.",$row[time]);
      $txt.="Round $row[round] $row[time]\r\n\r\n";
      $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]' ORDER BY section";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      { 
		$dono=1;
		if($no==1 && $row2[section]==4){$dono=0;  }
			if($dono==1){
			
						 $txt.="Section $row2[section]\r\nRoom: $row2[room]\r\n";
						 $sql3="SELECT offid FROM spstateassign WHERE roomid='$row2[id]'";
						 $result3=mysql_query($sql3);
						 $row3=mysql_fetch_array($result3);
							 if(GetJudgeName($row3[offid])!="NSAA") $judgename=GetJudgeName($row3[offid]);
							 else $judgename="";
						 $txt.="Judge: $judgename\r\n"; 
						 $sql3="SELECT studentids FROM spshuffle WHERE roomid='$row2[id]'";
							 $result3=mysql_query($sql3);
							 $row3=mysql_fetch_array($result3);
							 $studs=split("/",$row3[0]);
							 for($j=0;$j<count($studs);$j++)
						 {
							if(ereg(",",$studs[$j]))	//drama or duet
							{
							   $dstuds=split(",",$studs[$j]);
							   $studstr="";
							   for($k=0;$k<count($dstuds);$k++)
							   {
							  $sql4="SELECT first,last,semesters FROM $dbscores.eligibility WHERE id='$dstuds[$k]'";
							  $result4=mysql_query($sql4);
							  $row4=mysql_fetch_array($result4);
									  if(ereg("[(]",$row4[first]))
									  {
										 $temp=split("[(]",$row4[first]);
										 $nickname=$temp[1];
										 $nickname=ereg_replace("[)]","",$nickname);
										 $studstr.="$nickname $row4[last] ";
									  }
									  else
										 $studstr.="$row4[first] $row4[last] ";
							  $studstr.="(".GetYear($row4[semesters])."), ";
							   }
							   $studstr=substr($studstr,0,strlen($studstr)-2);
							}
							else
							{
							   $sql4="SELECT first,last,semesters FROM $dbscores.eligibility WHERE id='$studs[$j]'";
							   $result4=mysql_query($sql4);
							   $row4=mysql_fetch_array($result4);
								   if(ereg("[(]",$row4[first]))
								   {
									  $temp=split("[(]",$row4[first]);
									  $nickname=$temp[1];
									  $nickname=ereg_replace("[)]","",$nickname);
									  $studstr="  $nickname $row4[last]";
								   }
								   else
									  $studstr="  $row4[first] $row4[last]";
							   $studstr.=" (".GetYear($row4[semesters]).")";
							}
							$txt.=$studstr."\r\n";
					 }
					 $txt.="\r\n";
					 
			}		 
		}
   }
}	// END FOR EACH EVENT
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstateentries".$class.".txt"),"w");
fwrite($open,$txt);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstateentries".$class.".txt");
echo "<li><a target=\"_blank\" href=\"reports.php?session=$session&filename=spstateentries$class.txt\">Class $class Entries/Room Assignments</a><br><br></li>";
}//end for each CLASS
} //END IF !$exportuniqquals

//GENERATE REPORT OF SCHOOLS WITH # OF UNIQUE QUALIFIERS FOR EACH
$sql="SELECT * FROM $dbscores.spschool ORDER BY class,school";
$result=mysql_query($sql);
$html=$init_html."<table width=100%><tr align=center><td><table>";
$class='';
while($row=mysql_fetch_array($result))
{
   $school=$row[school]; $school2=addslashes($school); $code=$row[code]; $sid=$row[sid]; $qualifiers="";
   if($class!=$row['class'])
   {
      $class=$row['class'];
      $html.="<tr align=left><td colspan=2><hr>Class $class<hr></td></tr>";
   }
   $sql2="SELECT * FROM $dbscores.sp_state_drama WHERE dram_sch='$sid'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $qualifiers.=$row2[dram_stud];
      if(substr($qualifiers,strlen($qualifiers)-1,1)!=",")
	 $qualifiers.=",";
   }
   $qualifiers=ereg_replace(",,",",",$qualifiers); 
   $sql2="SELECT * FROM $dbscores.sp_state_duet WHERE duet_sch='$sid'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $qualifiers.=$row2[duet_stud];
      if(substr($qualifiers,strlen($qualifiers)-1,1)!=",")
         $qualifiers.=",";
   }
   $qualifiers=ereg_replace(",,",",",$qualifiers);
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (hum_sch LIKE '$sid,%' OR hum_sch LIKE '%,$sid,%' OR hum_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[hum_sch]);
      $stud=split(",",$row2[hum_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
	    $qualifiers.=$stud[$i].",";
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ser_sch LIKE '$sid,%' OR ser_sch LIKE '%,$sid,%' OR ser_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[ser_sch]);
      $stud=split(",",$row2[ser_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   } 
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ext_sch LIKE '$sid,%' OR ext_sch LIKE '%,$sid,%' OR ext_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[ext_sch]);
      $stud=split(",",$row2[ext_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (poet_sch LIKE '$sid,%' OR poet_sch LIKE '%,$sid,%' OR poet_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[poet_sch]);
      $stud=split(",",$row2[poet_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (pers_sch LIKE '$sid,%' OR pers_sch LIKE '%,$sid,%' OR pers_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[pers_sch]);
      $stud=split(",",$row2[pers_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ent_sch LIKE '$sid,%' OR ent_sch LIKE '%,$sid,%' OR ent_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[ent_sch]);
      $stud=split(",",$row2[ent_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (inf_sch LIKE '$sid,%' OR inf_sch LIKE '%,$sid,%' OR inf_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[inf_sch]);
      $stud=split(",",$row2[inf_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $uniquequals=Unique($qualifiers);
   $string=$uniquequals;
   if(trim($uniquequals)=="," || trim($uniquequals)=='') 
      $count=0;
   else
   {
      $uniquequals=split(",",$uniquequals);
      $count=0; $studlist="";
      for($c=0;$c<count($uniquequals);$c++)
      {
	 if(trim($uniquequals[$c])!='') 
         {
            $count++;
	 }
      }
   }
   if($count>0)
      $html.="<tr align=left><td>$school</td><td>$count</td></tr>"; 
}//end for each school
$html.="</table>".$end_html;
$open=fopen(citgf_fopen("/home/nsaahome/reports/qualifiers.html"),"w");
fwrite($open,$html);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/qualifiers.html");
if($exportuniqquals)
   header("Location:reports.php?session=$session&filename=qualifiers.html");
else
   echo "<li><a href=\"reports.php?session=$session&filename=qualifiers.html\" target=\"_blank\">Number of Qualifiers by School (All Classes)</a><br><br></li>";
echo "</ul></td></tr></table>";
echo $end_html;

function GetYear($semester)
{
  //return year in school, given the semester
  if(!$semester) return "";
  if($semester==1 || $semester==2)
    return 9;
  else if($semester==3 || $semester==4)
    return 10;
  else if($semester==5 || $semester==6)
    return 11;
  else if($semester==7 || $semester==8)
    return 12;
  else if($semester<1)
    return "<9";
  else if($semester>8)
    return ">12";
  else return "";
}
?>
