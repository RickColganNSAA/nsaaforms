<?php
/**************************************
spstatefinalsexport.php
Export for Finals Round (State Speech)
Created 3/12/08
Author: Ann Gaffigan
***************************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

$dbscores="$db_name";
$dboffs="$db_name2";

echo $init_html;
echo "<table width=100%><tr align=center><td><table frame=vsides rules=cols><tr align=left valign=top>";

$sql0="SELECT DISTINCT class FROM spdistricts WHERE class!='' ORDER BY class";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $class=$row0['class'];
   echo "<td><b>CLASS $class:</b><br><br>";
   for($e=0;$e<count($spevents2);$e++)
   {
      $event=$spevents2[$e];
      $csv="";
      $sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t3.id AS roomid,t3.room,t3.section,t4.class,t4.event,t4.round,t4.rounddate,TIME_FORMAT(t4.time,'%l:%i %p') AS time FROM $dboffs.judges AS t1, $dboffs.spstateassign AS t2, $dboffs.spstaterooms AS t3, $dboffs.spstaterounds AS t4 WHERE t1.id=t2.offid AND t2.roomid=t3.id AND t3.roundid=t4.id AND t4.class='$class' AND t4.round='3' AND t4.event='$event' ORDER BY t4.event,t1.last,t1.first";
      $result=mysql_query($sql);
      $csv="\"Class\",\"Event\",\"Judges\",\"Room\"\r\n";
      while($row=mysql_fetch_array($result))
      {
         $offid=$row[id]; 
         $roomid=$row[roomid]; 
         $room=$row[room]; $section=$row[section]; $round=$row[round]; $rounddate=$row[rounddate]; $time=$row[time];
         $eventstr="$time $class-$event Finals";
         $sql2="SELECT * FROM $dboffs.spshuffle WHERE roomid='$roomid'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $studs=split("/",$row2[studentids]);
         if(mysql_num_rows($result2)==0)
         {
            $studs=array();
         }
         for($i=0;$i<count($studs);$i++)
         {
            if(ereg(",",$studs[$i]))
            {
	       $dstuds=split(",",$studs[$i]);
	       $studlist="";
               for($j=0;$j<count($dstuds);$j++)
	       {
	          $sql3="SELECT school,first,last,semesters FROM $dbscores.eligibility WHERE id='$dstuds[$j]'";
                  $result3=mysql_query($sql3);
                  $row3=mysql_fetch_array($result3);
                  $cursch2=addslashes($row3[school]);
                  $sql4="SELECT t1.sid FROM $dbscores.spschool AS t1, $dbscores.headers AS t2 WHERE t2.school='$cursch2' AND (t1.mainsch=t2.id OR t1.othersch1=t2.id OR t1.othersch2=t2.id OR t1.othersch3=t2.id)";
                  $result4=mysql_query($sql4);
	          $row4=mysql_fetch_array($result4);
	          if($studlist=="") $studlist.="$row4[sid] ";
	          if(ereg("[(]",$row3[first]))
	          {
	             $temp=split("[(]",$row3[first]);
	             $nickname=$temp[1];
	             $nickname=ereg_replace("[)]","",$nickname);
	             $studlist.="$nickname $row3[last], ";
	          }
	          else 
	             $studlist.="$row3[first] $row3[last], ";
	       }
               $studlist=substr($studlist,0,strlen($studlist)-2);
            }
            else if($studs[$i]!='')
            { 
               $sql3="SELECT school,first,last,semesters FROM $dbscores.eligibility WHERE id='$studs[$i]'";
               $result3=mysql_query($sql3);
               $row3=mysql_fetch_array($result3);
               $cursch2=addslashes($row3[school]);
               $sql4="SELECT t1.sid FROM $dbscores.spschool AS t1, $dbscores.headers AS t2 WHERE t2.school='$cursch2' AND (t1.mainsch=t2.id OR t1.othersch1=t2.id OR t1.othersch2=t2.id OR t1.othersch3=t2.id)";
               $result4=mysql_query($sql4);
               $row4=mysql_fetch_array($result4);
               if(ereg("[(]",$row3[first]))
               {
                  $temp=split("[(]",$row3[first]);
                  $nickname=$temp[1];
                  $nickname=ereg_replace("[)]","",$nickname);
                  $studlist.="$row4[sid] $nickname $row3[last]";
               }
               else
	          $studlist="$row4[sid] $row3[first] $row3[last]";
            }
	    for($ix=0;$ix<7;$ix++)
               $csv.="\"$class\",\"$eventstr\",\"$row[first] $row[last]\",\"$room\"\r\n";
         }
         if(count($studs)==0)	//contestants not known yet
         {
	    for($ix=0;$ix<7;$ix++)
               $csv.="\"$class\",\"$eventstr\",\"$row[first] $row[last]\",\"$room\"\r\n";
         }
      }
      $open=fopen(citgf_fopen("/home/nsaahome/reports/spstatefinalsexport".$class.$spevents[$e].".csv"),"w");
      fwrite($open,$csv);
      fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatefinalsexport".$class.$spevents[$e].".csv");
      echo "$spevents3[$e]:<br><a class=small href=\"reports.php?session=$session&filename=spstatefinalsexport".$class.$spevents[$e].".csv\">spstatefinalsexport".$class.$spevents[$e].".csv</a><br><br>";
   }//end for each event
   echo "</td>";
}//end for each class
echo "</tr></table>";
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
