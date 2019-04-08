<?php
//filtersw.php: go through nsaaswimming_tm database and get out results that should go in sw_verify_perf_

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
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

echo $init_html;
echo $header;
$insert=0;
$update=0;

//connect to $db_name_tm
mysql_close();
$db=mysql_connect("$db_host","root","nsaahome");
mysql_select_db("$db_name",$db);
//erase existing TM entries in sw_verify_perf_g and sw_verify_perf_b
$sql="DELETE FROM sw_verify_perf_g WHERE formid='0'";
$result=mysql_query($sql);
$sql="DELETE FROM sw_verify_perf_b WHERE formid='0'";
$result=mysql_query($sql);

mysql_select_db("nsaaswimming_tm",$db);

//first, go through RESULT table:
$sql="SELECT MEET,ATHLETE,TEAM,SCORE,DISTANCE,COURSE,MTEVENT,STROKE,I_R,RESULT FROM RESULT";
$result=mysql_query($sql);
$nix=0;
while($row=mysql_fetch_array($result))
{
   $resultid=$row[9];
   $athlete=$row[1];
   //first check that entry meets qualifying standards
      //get event name in correct format
   $mtevent=$row[6];
   mysql_select_db("nsaaswimming_tm",$db);
   $sql2="SELECT Sex,stroke FROM MTEVENT WHERE MtEvent='$mtevent'";
   $result2=mysql_query($sql2);
   echo mysql_error();
   $row2=mysql_fetch_array($result2);
   $sex=$row2[0]; $stroke=$row2[1];
   mysql_select_db("$db_name",$db);
   $event=GetSWEvent($row[4],$stroke,$row[8],$sex);
      //get mark
   if(ereg("Diving",$event))	//diving score
      $mark=$row[3]*(-1);
   else 
      $mark=$row[3];
   $mark=number_format($mark/100,2,'.','');
   $event=trim($event);
   $mark=trim($mark);
   $qualtype=DoesQualify($event,$mark);
   if($mark==0) $qualtype="no";
   if($qualtype!="no")
   {
      //get studentid and school
      mysql_select_db("nsaaswimming_tm",$db);
      if(!ereg("Relay",$event))
      {
         $sql2="SELECT Last,First,Pref FROM Athlete WHERE Athlete='$row[1]' AND Team1='$row[2]'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 $name="$row2[1] $row2[0]";
	 $first=ereg_replace("\'","\'",$row2[1]);
	 $last=ereg_replace("\'","\'",$row2[0]);
	 $nick=ereg_replace("\'","\'",$row2[2]);
	 $leadoffsplit="";
      }
      else
      {
	 //get leadoff leg split
	 $sql2="SELECT Split FROM SPLITS WHERE SplitID='$resultid' AND SplitIndex='2'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 if(mysql_num_rows($result2)==0 || !ereg("200 Free Relay",$event))
	 {
	    $leadoffsplit="";
	 }
	 else
	 {
	    $leadoffsplit=number_format($row2[0]/100,2,'.','');
	 }

	 //get athletes on relay team
	 $sql2="SELECT * FROM RELAY WHERE RELAY='$row[1]'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 $athletes[0]=$row2[7];
	 $athletes[1]=$row2[8];
	 $athletes[2]=$row2[9];
	 $athletes[3]=$row2[10];
	 $name="";
	 for($k=0;$k<count($athletes);$k++)
	 {
	    $sql2="SELECT Last,First,Pref FROM Athlete WHERE Athlete='$athletes[$k]'";
	    $result2=mysql_query($sql2);
	    echo mysql_error();
	    $row2=mysql_fetch_array($result2);
	    $name.="$row2[1] $row2[0], ";
	    $firsts[$k]=ereg_replace("\'","\'",$row2[1]);
	    $lasts[$k]=ereg_replace("\'","\'",$row2[0]);
	    $nicks[$k]=ereg_replace("\'","\'",$row2[2]);
	    if($k==0)
	    {
	       $first=ereg_replace("\'","\'",$row2[1]);
	       $last=ereg_replace("\'","\'",$row2[0]);
	       $nick=ereg_replace("\'","\'",$row2[2]);
	    }
	 }
	 $name=substr($name,0,strlen($name)-2);
      }
      $sql2="SELECT TCode FROM TEAM WHERE Team='$row[2]'";
      $result2=mysql_query($sql2);
      echo mysql_error();
      $row2=mysql_fetch_array($result2);
      $hytekabbr=$row2[0];
      mysql_select_db("$db_name",$db);
      $sql2="SELECT school,coops FROM sw_schools WHERE hytekabbr LIKE '$hytekabbr%'";
      $result2=mysql_query($sql2);
      echo mysql_error();
      $row2=mysql_fetch_array($result2);
      $cursch2=ereg_replace("\'","\'",$row2[0]);
      if($row2[1]=="")
      {
	 $tempsch=ereg_replace("\'","\'",$row2[0]);
         $sql3="SELECT id,school FROM eligibility WHERE school='$tempsch' AND last='$last' AND (first LIKE '$first%' OR first LIKE '$nick%' OR first LIKE '($first%)' OR first LIKE '($nick%)')";      
      }
      else
      {
	 $coops=ereg_replace("\'","\'",$row2[1]);
	 $coops=split("/",$coops);
	 $sql3="SELECT id,school FROM eligibility WHERE last='$last' AND (first LIKE '$first%' OR first LIKE '$nick%' OR first LIKE '($first%)' OR first LIKE '($nick%)') AND (";
	 for($k=0;$k<count($coops);$k++)
	 {
	    $sql3.="school='$coops[$k]' OR ";
	 }
	 $sql3=substr($sql3,0,strlen($sql3)-4);
	 $sql3.=")";
      }
      $result3=mysql_query($sql3);
      echo mysql_error();
      if(mysql_num_rows($result3)==0)
      {
	 if(trim("$first$last")!="")
	 {
	    $notfound[$nix]="$first $last ($hytekabbr)";
	    $nix++;
	 }
      }
      else
      {
	 $row3=mysql_fetch_array($result3);
	 $studentid=$row3[0];
	 $relaysch=$row3[1];
	 $relaysch2=ereg_replace("\'","\'",$relaysch);
	 //if Relay, get all students' ids
	 if(ereg("Relay",$event))
	 {
	    $studentid="";
	    for($k=0;$k<count($firsts);$k++)
	    {
	       $sql3="SELECT id FROM eligibility WHERE (first LIKE '$firsts[$k]' OR first LIKE '$nicks[$k]' OR first LIKE '($firsts[$k]%)' OR first LIKE '($nicks[$k]%)') AND last='$lasts[$k]' AND school='$relaysch2'";
	       $result3=mysql_query($sql3);
	       echo mysql_error();
	       $row3=mysql_fetch_array($result3);
	       $studentid.=$row3[0]."/";
	       if($k==0) $ath1=$row3[0];
	    }
	    $studentid=substr($studentid,0,strlen($studentid)-1);
	 }
	 $formid=0;
	 //get meet
	 mysql_select_db("nsaaswimming_tm",$db);
	 $sql2="SELECT MName FROM MEET WHERE Meet='$row[0]'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 $meet=$row2[0];
	 //put into sw_verify_perf_ table
	 if($sex=="F") 
	 {
	    $event=ereg_replace("Girls ","",$event);
	    $table="sw_verify_perf_g";
	 }
	 else 
	 {
	    $event=ereg_replace("Boys ","",$event);
	    $table="sw_verify_perf_b";
	 }
	 //check that no other Team Mgr entry has been put in that is faster for this student & event
	 mysql_select_db("$db_name",$db);
	 if(!ereg("Relay",$event))
	 {
	    $sql2="SELECT id,performance FROM $table WHERE formid='0' AND school='$cursch2' AND event='$event' AND studentid='$studentid'";
	 }
	 else
	 {
	    $meet2=ereg_replace("\'","\'",$meet);
	    $sql2="SELECT id,performance FROM $table WHERE formid='0' AND school='$cursch2' AND event='$event' AND meet='$meet2'";
	 }
	 $result2=mysql_query($sql2);
	 echo mysql_error();
         if(mysql_num_rows($result2)==0)	//no entry for this student/event yet
	 {
	    //...and put ALL relays in
	    $sql3="INSERT INTO $table (formid,school,event,studentid,performance,meet,leadoff) VALUES ('$formid','$cursch2','$event','$studentid','$mark','$meet','$leadoffsplit')";
	    $result3=mysql_query($sql3);
	    if(ereg("200 Free Relay",$event)) echo "$sql3<br>";
	    echo mysql_error();
	    $insert++;
	 }
	 else
	 {
	    $row2=mysql_fetch_array($result2);
	    if($row2[1]>=$mark)	//current entry is slower than new one
	    {
	       $sql3="UPDATE $table SET meet='$meet',studentid='$studentid',performance='$mark',leadoff='$leadoffsplit' WHERE id='$row2[0]'";
	       $result3=mysql_query($sql3);
	       if(ereg("200 Free Relay",$event)) echo "$sql3<br>";
	       echo mysql_error();
	       $update++;
	    }
	 }
      }
   }
   /*
   if(ereg("200 Free Relay",$event))	//check first leg for 50 Free qualifier
   {
      mysql_select_db("nsaaswimming_tm",$db);
      //$sql2="SELECT SPLITS.Split,relay.ath(1) FROM SPLITS,RELAY WHERE SPLITS.SplitID='$resultid' AND SPLITS.SplitIndex='2'"; 
      $sql2="SELECT * FROM RELAY WHERE RELAY='$athlete'";
      $result2=mysql_query($sql2);
      echo mysql_error();
      $row2=mysql_fetch_array($result2);
      $ath1=$row2[7]; 
      $sql2="SELECT Split FROM SPLITS WHERE SplitID='$resultid' AND SplitIndex='2'";
      $result2=mysql_query($sql2);
      echo mysql_error();
      $row2=mysql_fetch_array($result2);
      $split=number_format($row2[0]/100,2,'.','');
      if($sex=="F") $leadleg="Girls 50 Freestyle";
      else $leadleg="Boys 50 Freestyle";
      mysql_select_db("$db_name",$db);
      $qualtype=DoesQualify($leadleg,trim($split));
      if($split=="0.00" || $ath1=="") $qualtype="no";
      mysql_select_db("nsaaswimming_tm",$db);
      if($qualtype!="no")
      {
         //get studentid and school
	 $sql2="SELECT Last,First,Team1,Pref FROM Athlete WHERE Athlete='$ath1'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 $last=$row2[0]; $first=$row2[1]; $nick=$row2[3];
	 $sql2="SELECT TCode FROM TEAM WHERE Team='$row2[2]'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 $hytekabbr=$row2[0];
	 mysql_select_db("$db_name",$db);
	 $sql2="SELECT school,coops FROM sw_schools WHERE hytekabbr LIKE '$hytekabbr%'";
	 $result2=mysql_query($sql2);
	 echo mysql_error();
	 $row2=mysql_fetch_array($result2);
	 $cursch2=ereg_replace("\'","\'",$row2[0]);
         if($row2[1]=="")   
	 {   
	    $tempsch=ereg_replace("\'","\'",$row2[0]);
	    $first=ereg_replace("\'","\'",$first);
	    $last=ereg_replace("\'","\'",$last);
	    $sql3="SELECT id,school FROM eligibility WHERE school='$tempsch' AND last='$last' AND (first LIKE '$first%' OR first LIKE '$nick%' OR first LIKE '($first%)' OR first LIKE '($nick%)')";
	 }
	 else
	 {
	    $coops=ereg_replace("\'","\'",$row2[1]);
	    $coops=split("/",$coops);
	    $first=ereg_replace("\'","\'",$first);
	    $last=ereg_replace("\'","\'",$last);
	    $sql3="SELECT id,school FROM eligibility WHERE last='$last' AND (first LIKE '$first%' OR first LIKE '$nick%' OR first LIKE '($first%)' OR first LIKE '($nick%)') AND (";
	    for($k=0;$k<count($coops);$k++)        
	    {
	       $sql3.="school='$coops[$k]' OR ";
	    }
	    $sql3=substr($sql3,0,strlen($sql3)-4);
	    $sql3.=")";
	 }
	 $result3=mysql_query($sql3);
	 echo mysql_error();
         if(mysql_num_rows($result3)==0)
	 {    
	    if(trim("$first$last")!="")
	    {
	       $notfound[$nix]="$first $last ($hytekabbr)";
	       $nix++;
	    }
	 }
	 else   
	 {
	    $row3=mysql_fetch_array($result3);
	    $studentid=$row3[0];
	    $formid=0;    
	    //get meet
	    mysql_select_db("nsaaswimming_tm",$db);
	    $sql2="SELECT MName FROM MEET WHERE Meet='$row[0]'";
	    $result2=mysql_query($sql2);
	    echo mysql_error();
	    $row2=mysql_fetch_array($result2);
	    $meet=$row2[0];
	    //put into sw_verify_perf_ table
	    if($sex=="F")
	    {
	       $table="sw_verify_perf_g";
	    }
	    else
	    {
	       $table="sw_verify_perf_b";
	    }
            //check that no other Team Mgr entry has been put in that is faster for this student & event
	    mysql_select_db("$db_name",$db);
	    $sql2="SELECT id,performance FROM $table WHERE formid='0' AND school='$cursch2' AND event='50 Freestyle' AND studentid='$studentid'";
	    $result2=mysql_query($sql2);
	    echo mysql_error();
	    if(mysql_num_rows($result2)==0)        //no entry for this student/event yet
	    {
	       $sql3="INSERT INTO $table (formid,school,event,studentid,performance,meet) VALUES ('$formid','$cursch2','50 Freestyle','$studentid','$split','$meet')";
	       $result3=mysql_query($sql3);
	       echo mysql_error();
	       $insert++;
	    }
	    else
	    {
	       $row2=mysql_fetch_array($result2);
	       if($row2[1]>$mark)  //current entry is slower than new one
	       {
	          $sql3="UPDATE $table SET meet='$meet' AND performance='$mark' WHERE id='$row2[0]'";
		  $result3=mysql_query($sql3);
		  echo mysql_error();
		  $update++;
	       }
	    }
	 }
      }
      mysql_select_db("$db_name",$db);
   }
   */
   mysql_select_db("$db_name",$db);
}

$ix=0;
$notfound2=array();
for($i=0;$i<count($notfound);$i++)
{
   $dup=0;
   for($j=0;$j<$i;$j++)
   {
      if($notfound[$j]==$notfound[$i])
	 $dup=1;
   }
   if($dup==0)
   {
      $notfound2[$ix]=$notfound[$i];
      $ix++;
   }
}
if(count($notfound2)>0)
{
   echo "<table><tr align=center><th>The following students were not found in the NSAA Eligibility Database:<hr></th></tr>";
   echo "<tr align=left><td align=left>";
}
for($i=0;$i<count($notfound2);$i++)
{
   echo $notfound2[$i]."<br>";
}
if(count($notfound2)>0)
{
   echo "</td></tr></table>";
}

//header("Location:sw/qualifiers_b.php?session=$session");
echo "<br><br>Database update complete!!";
echo "<br><br><a href=\"sw/qualifiers_b.php?session=$session\">Click Here to Generate the new Season Best files</a>";
exit();

?>
