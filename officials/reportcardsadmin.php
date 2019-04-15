<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session,"reportcardsadmin");

if($offid>0)
{
   $reporttbl="reportcard_".$sport;
   echo "<br><a class=small href=\"reportcardsadmin.php?session=$session&open=1&sport=$sport\">Game Report Cards Advanced Search</a><br><br>";
   $sql="SELECT * FROM $db_name.$reporttbl WHERE (offid1='$offid' OR offid2='$offid' OR offid3='$offid' OR offid4='$offid' OR offid5='$offid' OR offid6='$offid') AND datesub!=''";
   $result=mysql_query($sql);
   $rating=array();
   for($i=1;$i<=5;$i++)
   {
      $rating[$i][0]=0; //satisfactory
      $rating[$i][1]=0; //unsatisfactory
      $rating[$i][2]=0; //N/A
   }
   while($row=mysql_fetch_array($result))
   {
      for($i=1;$i<=5;$i++)
      {
         $var="radio".$i;
         switch($row[$var])
         {
            case "s":
               $rating[$i][0]++;
               break;
            case "u":
               $rating[$i][1]++;
               break;
            default:
               $rating[$i][2]++;
	 }
      } 
   }
   //Make table to display ratings:
   echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
   $offname=GetOffName($offid);
   echo "<caption><b>Official's ".GetSportName($sport)." Report Card Summary: $offname</b><br></caption>";
   echo "<tr align=center><td><b>Criteria:</b></td><td><b>Comments</b></td><td><b>Satisfactory</b></td><td><b>%</b></td>";
   echo "<td><b>Unsatisfactory</b></td><td><b>%</b></td><td><b>Non-Applicable</b></td><td><b>%</b></td>";
   echo "</tr>";
   $criteria=array("Professionalism","Game Control","Consistency","Mechanics","Communication with Players/Coaches");
   $totals=array();
   for($i=0;$i<count($criteria);$i++)
   {
      echo "<tr align=center><td align=left><b>$criteria[$i]</b></td>";
      $num=$i+1;
      $var="comments".$num;
      $curcrit=addslashes($criteria[$i]);
      echo "<td><a class=small href=\"#\" onclick=\"window.open('reportcardcomments.php?session=$session&sport=$sport&offid=$offid&field=$var&criteria=$curcrit','view_comments','height=500,width=600,location=no,scrollbars=yes');\">View Comments</a></td>";
      $curtotal=$rating[$num][0]+$rating[$num][1]+$rating[$num][2];
      echo "<td>".$rating[$num][0]."</td>";
      echo "<td>".number_format(($rating[$num][0]/$curtotal)*100,2,'.','')."</td>";
      $totals[0]+=$rating[$num][0];
      echo "<td>".$rating[$num][1]."</td>";
      echo "<td>".number_format(($rating[$num][1]/$curtotal)*100,2,'.','')."</td>";
      $totals[1]+=$rating[$num][1];
      echo "<td>".$rating[$num][2]."</td>";
      echo "<td>".number_format(($rating[$num][2]/$curtotal)*100,2,'.','')."</td>";
      $totals[2]+=$rating[$num][2];
      echo "</tr>";
   }
   echo "<tr align=center><td align=left><b>TOTALS:</b></td>";
   echo "<td><a class=small href=\"#\" onclick=\"window.open('reportcardcomments.php?session=$session&sport=$sport&offid=$offid&field=feedback&criteria=Positive or Negative Feedback','view_comments','height=500,width=600,location=no,scrollbars=yes');\">+/- Feedback</a></td>";
   $curtotal=$totals[0]+$totals[1]+$totals[2];
   echo "<td>".$totals[0]."</td>";
   echo "<td>".number_format(($totals[0]/$curtotal)*100,2,'.','')."</td>";
   echo "<td>".$totals[1]."</td>";
   echo "<td>".number_format(($totals[1]/$curtotal)*100,2,'.','')."</td>";
   echo "<td>".$totals[2]."</td>";
   echo "<td>".number_format(($totals[2]/$curtotal)*100,2,'.','')."</td>";
   echo "</tr>";
   echo "</table><br><br><a class=small href=\"reportcardsadmin.php?session=$session&sport=$sport&open=1\">Game Report Cards Advanced Search</a>";
   exit();
}
if($sid>0 && $search2=="Search")	//show report on selected school
{
   echo "<br><a class=small href=\"reportcardsadmin.php?session=$session&open=2&sport=$sport\">Game Report Cards Advanced Search</a><br><br>";
   $reporttbl="reportcard_".$sport;
   $sql="SELECT * FROM $reporttbl WHERE (oppid1='$sid' OR oppid2='$sid') AND datesub!=''";
   $result=mysql_query($sql);
   $rating=array();
   for($i=1;$i<=6;$i++)
   {
      $rating[$i][0]=0;	//satisfactory
      $rating[$i][1]=0;	//unsatisfactory
      $rating[$i][2]=0;	//N/A
   }
   while($row=mysql_fetch_array($result))
   {
      for($i=1;$i<=6;$i++)
      {
         if($sid==$row[oppid1])
         {
	    $var="homeradio".$i;
            switch($row[$var])
	    {
	       case "s":
	          $rating[$i][0]++;
		  break;
	       case "u":
	          $rating[$i][1]++;
	    	  break;
	       default:   
                  $rating[$i][2]++;
            }
         }
         else if($i<=4) //away team
	 {
	    $var="awayradio".$i;
	    $i2=$i+2;
	    switch($row[$var])
	    {
	       case "s":
	          $rating[$i2][0]++;
		  break;
	       case "u":
		  $rating[$i2][1]++;
		  break;
	       default:
		  $rating[$i2][2]++;
	    }
	 } 
	 else	//away team: set home criteria as "no answer"
	 {
	    $i2=$i-4;	
	    $rating[$i2][2]++;
         }
      }
   }
   //Make table to display ratings:
   echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
   $schname=GetSchoolName($sid,$sport,GetFallYear($sport));
   echo "<caption><b>School ".GetSportName($sport)." Report Card Summary: $schname</b><br></caption>";
   echo "<tr align=center><td><b>Criteria:</b></td><td><b>Comments</b></td>";
   echo "<td><b>Satisfactory</b></td><td><b>%</b></td>";
   echo "<td><b>Unsatisfactory</b></td><td><b>%</b></td><td><b>Non-Applicable</b></td><td><b>%</b></td>";
   echo "</tr>";
   $criteria=array("Game Management","Facilities","Overall Sportsmanship","Players' Conduct","Coaches' Conduct","Spectators' Conduct");
   $totals=array();
   for($i=0;$i<count($criteria);$i++)
   {
      echo "<tr align=center><td align=left><b>$criteria[$i]</b></td>";
      $num=$i+1;
      $curcrit=addslashes($criteria[$i]);
      $curnum=$num;
      echo "<td><a class=small href=\"#\" onclick=\"window.open('reportcardcomments.php?session=$session&sport=$sport&sid=$sid&criteria=$curcrit&num=$curnum','view_comments','height=500,width=600,location=no,scrollbars=yes');\">View Comments</a></td>";
      $curtotal=$rating[$num][0]+$rating[$num][1]+$rating[$num][2];
      echo "<td>".$rating[$num][0]."</td>";
      echo "<td>".number_format(($rating[$num][0]/$curtotal)*100,2,'.','')."</td>";
      $totals[0]+=$rating[$num][0];
      echo "<td>".$rating[$num][1]."</td>";
      echo "<td>".number_format(($rating[$num][1]/$curtotal)*100,2,'.','')."</td>";
      $totals[1]+=$rating[$num][1];
      echo "<td>".$rating[$num][2]."</td>";
      echo "<td>".number_format(($rating[$num][2]/$curtotal)*100,2,'.','')."</td>";
      $totals[2]+=$rating[$num][2];
      echo "</tr>";
   }
   echo "<tr align=center><td align=left><b>TOTALS:</b></td>";
   echo "<td><a class=small href=\"#\" onclick=\"window.open('reportcardcomments.php?session=$session&sport=$sport&sid=$sid&criteria=Positive or Negative Feedback&field=feedback','view_comments','height=500,width=600,location=no,scrollbars=yes');\">+/- Feedback</a></td>";
   $curtotal=$totals[0]+$totals[1]+$totals[2];
   echo "<td>".$totals[0]."</td>";
   echo "<td>".number_format(($totals[0]/$curtotal)*100,2,'.','')."</td>";
   echo "<td>".$totals[1]."</td>";
   echo "<td>".number_format(($totals[1]/$curtotal)*100,2,'.','')."</td>";
   echo "<td>".$totals[2]."</td>";
   echo "<td>".number_format(($totals[2]/$curtotal)*100,2,'.','')."</td>";
   echo "</tr>";
   echo "</table><br><br>";
   echo "<a class=small href=\"reportcardsadmin.php?session=$session&open=2&sport=$sport\">Game Report Cards Advanced Search</a>";
   echo $end_html;
   exit();
}
   
echo "<br><form method=post action=\"reportcardsadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=open value=\"$open\">";
echo "<table width=400><caption><b>Game Report Cards Advanced Search:</b></caption>";
echo "<tr align=left><th align=left>Sport:&nbsp;<select name=sport><option value=''>Select Sport</option>";
$reportcardsp=array("bbg","bbb");
for($i=0;$i<count($reportcardsp);$i++)
{
   echO "<option value=\"$reportcardsp[$i]\"";
   if($reportcardsp[$i]==$sport) echo " selected";
   echo ">".GetSportName($reportcardsp[$i])."</option>";
}
echo "</select><input type=submit name=go value=\"Go\"></th></tr>";
if($sport && $sport!='')
{
$schtbl=$sport."school";
$schedtbl=$sport."sched";
$fallyear=GetFallYear($sport);
$reporttbl="reportcard_".$sport;
echo "<tr align=left><th align=left>Search For:</th></tr>";

//Search by Official
if($open==1) $open1=0;
else $open1=1;
echo "<tr align=left><td><a href=\"reportcardsadmin.php?sport=$sport&session=$session&open=$open1\">";
if($open==1)
   echo "&nabla;&nbsp;";
else echo "&Delta;&nbsp;";
echo "Report Summaries on Officials</a></td></tr>";
if($open==1 || $search1)
{
   $reporttbl="reportcard_".$sport;
   echo "<tr align=center><td>";
   echo "<table><tr align=left valign=top><td><b>Last:</b><br>(Begins with)</td>";
   echo "<td><input type=text class=tiny size=20 name=\"last\" value=\"$last\"></td></tr>";
   echo "<tr align=left valign=top><td><b>First:</b><br>(Begins with)</td>";
   echo "<td><input type=text class=tiny size=20 name=\"first\" value=\"$first\"></td></tr>";
   echo "<tr align=left><td><b>OR Select:</b></td>";
   echo "<td><select name=offid><option value='0'>~</option>";
   $sql="SELECT DISTINCT t1.* FROM $db_name2.officials AS t1, $db_name.$reporttbl AS t2 WHERE (t1.id=t2.offid1 OR t1.id=t2.offid2 OR t1.id=t2.offid3 OR t1.id=t2.offid4 OR t1.id=t2.offid5 OR t1.id=t2.offid6) ORDER BY t1.last,t1.first";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      //if(IsReportCardOff($row[id]))
      //{
         echo "<option value='$row[id]'";
         if($offid==$row[id]) echo " selected";
	 echo ">$row[first] $row[last]</option>";
      //}
   }
   echo "</select></td></tr>";
   echo "<tr align=right><td colspan=2><input type=submit name=search1 value=\"Search\">";
   echo "</table>";
   if((trim($last)!="" || trim($first)!="" || ($offid && $offid!='0')) && $search1)
   {
      $last2=addslashes($last); $first2=addslashes($first);
      $sql="SELECT DISTINCT t1.id,t1.first,t1.last FROM $db_name2.officials AS t1, $db_name.$reporttbl AS t2 WHERE ";
      if($offid && $offid!='0') $sql.="t1.id='$offid' AND ";
      else
      {
         if(trim($last)!='') $sql.="t1.last LIKE '$last2%' AND ";
         if(trim($first)!='') $sql.="t1.first LIKE '$first2%' AND ";
      }
      $sql.="(t2.offid1=t1.id OR t2.offid2=t1.id OR t2.offid3=t1.id OR t2.offid4=t1.id OR t2.offid5=t1.id OR t2.offid6=t1.id) AND t2.datesub!='' ORDER BY t1.last, t1.first";
      $result=mysql_query($sql);
      $ix=0;
      echo "<br><table cellspacing=1 cellpadding=3><tr align=left valign=top><td>";
      $total=mysql_num_rows($result);
      $percol=$total/4; $curcol=0; 
      while($row=mysql_fetch_array($result))
      {
         echo "<a class=small href=\"reportcardsadmin.php?session=$session&sport=$sport&offid=$row[id]\">$row[last], $row[first]</a><br><br>";
         $ix++; $curcol++;
         if($curcol>=$percol)
         {
	    $curcol=0;
	    echo "</td><td>";
	 }
      }
      echo "</td></tr></table>";
   }
   echo "</td></tr>";
}

//Search by School
if($open==2) $open2=0;
else $open2=2;
echo "<tr align=left><td><a href=\"reportcardsadmin.php?session=$session&open=$open2&sport=$sport\">";
if($open==2) echo "&nabla;&nbsp;";
else echo "&Delta;&nbsp;";
echo "Report Summaries on Schools</a></td></tr>";
if($open==2)
{
   echo "<tr align=center><td>";
   echo "<table><tr align=left><td><select name=\"sid\"><option value='0'>Select School</option>";
   $sql="SELECT * FROM $db_name.$schtbl ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $cursid=$row[sid];
      $sql2="SELECT * FROM $db_name2.$reporttbl WHERE (oppid1='$cursid' OR oppid2='$cursid')";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
      echo "<option value=\"$row[sid]\"";
      if($sid==$row[sid]) echo " selected"; 
      echo ">$row[school]</option>";
      }
   }
   echo "</select></td></tr>";
   echo "<tr align=right><td><input type=submit name=search2 value=\"Search\"></td></tr>";
   echo "</table></td></tr>";
}

//Search by Game (scoreid)
if($open==3) $open3=0;
else $open3=3;
echo "<tr align=left><td><a href=\"reportcardsadmin.php?session=$session&open=$open3&sport=$sport\">";
if($open==3) echo "&nabla;&nbsp;";
else echo "&Delta;&nbsp;";
echo "Individual Reports by Game</a></td></tr>";
if($open==3)
{
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td><b>Date of Game:</b></td>";
   echo "<td><select name=month><option value=''>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($month==$m) echo " selected";
      echO ">$m</option>";
   }
   echo "</select>/<select name=day><option value=''>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($day==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=year><option value=''>YYYY</option>";
   $year0=GetFallYear($sport);
   $year1=$year0+1;
   for($i=$year0;$i<=$year1;$i++)
   {
      echo "<option";
      if($year==$i) echo " selected";
      echO ">$i</option>";
   }
   echo "</select><input type=submit name=go3 value=\"Go\"></td></tr>";
   if(($search3 || $go3) && $month!='' && $day!='' && $year!='')
   {
      echo "<tr align=left><td><b>Select One Opponent:</b></td>";
      echo "<td><select name=\"oppid\"><option value='0'>(Can be either team playing)</option>";
      $sql="SELECT DISTINCT t1.sid,t1.school FROM $db_name.$schtbl AS t1, $db_name.$schedtbl AS t2 WHERE (t1.sid=t2.sid OR t1.sid=t2.oppid) AND t2.received='$year-$month-$day' AND t2.oppid!='0' ORDER BY t1.school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[sid]\"";
         if($oppid==$row[sid]) echo " selected";
  	 echo ">$row[school]</option>";
      }
      echo "</select><br></td></tr>";
      echO "<tr align=right><td colspan=2><input type=submit name=search3 value=\"Search\"></td></tr>";
      if($search3)
      {
	 //find games on this date with this opponent:
	 $sql="SELECT * FROM $db_name.$schedtbl WHERE received='$year-$month-$day' AND (sid='$oppid' OR oppid='$oppid') ORDER BY scoreid";
	 $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
	 {
	    if($scoreid!='x' && mysql_num_rows($result)==1) $scoreid=$row[scoreid];
	    echo "<tr align=left><td colspan=2>";
	    if($scoreid==$row[scoreid]) $curscoreid='x';
	    else $curscoreid=$row[scoreid];
	    echo "<a class=small href=\"reportcardsadmin.php?sport=$sport&session=$session&open=$open&month=$month&day=$day&year=$year&search3=Search&go3=Go&oppid=$oppid&scoreid=$curscoreid\">";
	    if($scoreid==$row[scoreid]) echo "&nabla;&nbsp;";
	    else echo "&Delta;&nbsp;";
	    echo GetSchoolName($row[sid],$sport,$fallyear)." VS ".GetSchoolName($row[oppid],$sport,$fallyear);
   	    if($row[homeid]>0) echo " @ ".GetSchoolName($row[homeid],$sport,$fallyear);
	    else if($row[tid]>0) echo " @ ".GetTournamentName($row[tid],$sport,$fallyear);
	    echo "</a></td></tr>";
	    if($scoreid==$row[scoreid])	//show links to report cards for this game
	    {
	       echO "<tr align=center><td colspan=2><table>";
	       //First look for school submissions
	       $sql2="SELECT * FROM $db_name.$reporttbl WHERE scoreid='$scoreid' AND datesub!='' ORDER BY datesub";
	       $result2=mysql_query($sql2);
	       while($row2=mysql_fetch_array($result2))
	       {
		  echo "<tr align=left><td>";
	  	  echo "<a class=small target=\"_blank\" href=\"../reportcard.php?session=$session&sport=$sport&givenscoreid=$scoreid&school_ch=$row2[school]\">School's Submission: $row2[school]</a>";
	  	  echo "</td></tr>";
	       }
               if(mysql_num_rows($result2)==0)
	          echo "<tr align=left><td>[No School-Submissions]</td></tr>";
	       //Now officials' submissions
	       $sql2="SELECT * FROM $db_name2.$reporttbl WHERE scoreid='$scoreid' AND datesub!='' ORDER BY datesub";
	       $result2=mysql_query($sql2);
	       while($row2=mysql_fetch_array($result2))
	       {
		  echo "<tr align=left><td>";
	          echO "<a class=small target=\"_blank\" href=\"reportcard.php?session=$session&sport=$sport&givenscoreid=$scoreid&givenoffid=$row2[offid]\">Official's Submission: ".GetOffName($row2[offid])."</a>";
	      	  echo "</td></tr>";
	       }
               if(mysql_num_rows($result2)==0)
                  echo "<tr align=left><td>[No Official-Submissions]</td></tr>";
	    }
         }
      } 
   }
   echo "</table></td></tr>";
}
}//end if sport chosen
echo "</table>";
echo "</form>";

echo $end_html;
?>
