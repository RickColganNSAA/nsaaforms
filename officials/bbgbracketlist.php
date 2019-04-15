<?php
/*************************
List Postseason Games
in Text Format
Author Ann Gaffigan
Date 2/9/12
*************************/

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sport='bbg';
$sportname=GetSportName($sport);
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$year=GetFallYear($sport);
$year2=$year+1;
if(!$class) $class="A";

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<h2>$year2 Class $class $sportname District & Subdistrict Games:</h2>";
echo "<div style='width:600px;text-align:left;'>";

//FOR EACH DISTRICT IN THIS CLASS
$sql0="SELECT * FROM $db_name2.$districts WHERE class='$class' AND type!='State' ORDER BY type,district";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $distid=$row0[id];
   //get host of district
   if($row0[type]!="District Final")
   {
      $sql2="SELECT school FROM $db_name.logins WHERE id='$row0[hostid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $homeid=GetSID2($row2[school],$sport);
      if(!($row0[post]=='y' && $row0[accept]=='y' && $row0[confirm]=='y')) $row0[hostschool]="TBA";
      else if($row0[site]!='') $row0[hostschool]=$row0[site];
   }
   echo "<p><b>$row0[type] $row0[class]-$row0[district] at $row0[hostschool]</b>";
   $seeded=$row0[seeded]; $bracketed=$row0[bracketed];
   if($row0[showtimes]!='y' && ($row0[seeded]!='y' || $row0[bracketed]!='y'))	//not available yet
   {
      echo "<br><i>Information not available at this time.</i></p>";
   }
   else if($row0[type]=="District Final")
   {
      $sql2="SELECT t1.*,t2.day,t2.time FROM $db_name2.$districts AS t1, $db_name2.$disttimes AS t2 WHERE t1.id=t2.distid AND t1.id='$row0[id]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $day=split("-",$row2[day]);
      if($row2[time]==": PM CST") $row2[time]="";
      if($row2[time]=="") $row2[time]=="";
      else $row2[time]="@ ".$row2[time];
      echo "<p>$day[1]/$day[2] $row2[time], $row2[schools]";
      echo "</p>";
   }
   else
   {
      echo "&nbsp;&nbsp;&nbsp;<a class=small href=\"".$sport."brackets.php?distid=$distid\" target=\"_blank\">$row0[type] $row0[class]-$row0[district] Bracket</a></p>";
      //get match & seed info for each game in district:
      //get number of teams
      $sql="SELECT * FROM ".$sport."seeds WHERE distid='$distid'";
      $result=mysql_query($sql);
      $teamct=mysql_num_rows($result);
      if($teamct==0)
      {
         $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND day!='0000-00-00'";
         $result=mysql_query($sql);
         $teamct=mysql_num_rows($result);
         $teamct++;
      }
      for($i=1;$i<=$teamct;$i++)
      {
         $sql="SELECT day,time FROM $db_name2.".$sport."disttimes WHERE distid='$distid' AND gamenum='$i'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $day=split("-",$row[day]);
         $field="match".$i;
         $$field="<b>Game $i:</b> $day[1]/$day[2] at $row[time]";
         if($row[notes]!='') $$field.=" ($row[notes])";

         $sql="SELECT sid,ptavg FROM $db_name2.".$sport."seeds WHERE distid='$distid' AND seed='$i'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $field="seed".$i;
         if($seeded!='y' || $bracketed!='y')
            $$field='';
         else
            $$field="#$i ".GetSchoolName($row[sid],$sport,$year)." $row[ptavg] (".GetWinLoss($row[sid],$sport,$year).")";
         $field="sid".$i;
         if($seeded!='y' || $bracketed!='y')
            $$field='';
         else
            $$field=$row[sid];
      }

      //SHOW INFO FOR EACH GAME
if($teamct==6)
{
   //MATCH 1:
   $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!="" && $row[sidscore]!="")	//get Match 3 info
   {
      //MATCH 3:
      if($row[sidscore]>$row[oppscore]) 
      {
	 $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
      }
      else 
      {
	 $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
      }
      $winner1=GetSchoolName($winner1sid,$sport,$year)." ($score1)";

      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='3'"; 
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      if($row[oppscore]!="" && $row[sidscore]!="")	//get Match 5 info
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
	 }
	 else 
	 {
  	    $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
	 }
	 $winner3=GetSchoolName($winner3sid,$sport,$year)." ($score3)";
      }
      else $winner3="Winner of Game 3";
   }
   else
   {
      $winner1="Winner of Game 1";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!="" && $row[sidscore]!="") //get Match 4 info
   {
      //MATCH 4:
      if($row[sidscore]>$row[oppscore]) 
      {
	 $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
      }
      else 
      {
	 $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
      }
      $winner2=GetSchoolName($winner2sid,$sport,$year)." ($score2)";

      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='4'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      if($row[oppscore]!="" && $row[sidscore]!="")      //get Match 5 info
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
	 }
         else 
	 {
	    $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
	 }
         $winner4=GetSchoolName($winner4sid,$sport,$year)." ($score4)";
      }
      else $winner4="Winner of Game 4";
   }
   else
   {
      $winner2="Winner of Game 2";
   }

   //check if Final game is in wildcard program yet:
   if($winner3!='' && $winner4!='')
   {
      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='5'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[sidscore]!="" && $row[oppscore]!="")	//get winner of final game
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
	 }
	 else 
	 {
	    $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
	 }
	 $winner5=GetSchoolName($winner5sid,$sport,$year)." ($score5)";
      }
      else $winner5="Winner of Game 5";
   }
   else $winner5="Winner of Game 5";

   //Round 1:
   if($match1!='')
   {
      echo "<p>$match1";
      if($seed4!='' && $seed5!='') echo ", $seed4 vs $seed5</p>";
      else echo "</p>";
   }
   if($match2!='')
   {
      echo "<p>$match2";
      if($seed3!='' && $seed6!='') echo ", $seed3 vs $seed6</p>";
      else echo "</p>";
   }
   //Round 2:
   if($match3!='')
   {
      echo "<p>$match3";
      if($seed1!='' && $winner1!='') echo ", $seed1 vs $winner1</p>";
      else echo "</p>";
   }
   if($match4!='')
   {
      echo "<p>$match4";
      if($winner2!='' && $seed2!='') echo ", $winner2 vs $seed2</p>";
      else echo "</p>";
   }
   if($match5!='')
   {
      echo "<p>$match5";
      if($winner3!='' && $winner4!='') echo ", $winner3 vs $winner4</p>";
      else echo "</p>";
   }
   //CHAMP
   if($winner5!='' && !ereg("Winner",$winner5)) echo "<p><b>CHAMPION:</b> $winner5</p>";
}//end if teamct=6
else if($teamct==5)
{
   $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!="" && $row[sidscore]!="") //get Match 2 info
   {
      //MATCH 2:
      if($row[sidscore]>$row[oppscore]) 
      {
	 $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
      }
      else 
      {
	 $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
      }
      $winner1=GetSchoolName($winner1sid,$sport,$year)." ($score1)";

      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      if($row[oppscore]!="" && $row[sidscore]!="")      //get Match 4 info
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
	 }
         else 
	 {
	    $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
	 }
         $winner2=GetSchoolName($winner2sid,$sport,$year)." ($score2)";
      }
      else $winner2="Winner of Game 2";
   }
   else
   {
      $winner1="Winner of Game 1"; $winner2="Winner of Game 2";
   }

   //MATCH 3:
   $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='3'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!="" && $row[sidscore]!="") //get Match 4 info
   {
      //MATCH 4:
      if($row[sidscore]>$row[oppscore]) 
      {
	 $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
      }
      else 
      {
	 $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
      }
      $winner3=GetSchoolName($winner3sid,$sport,$year)." ($score3)";
   }
   else
   {
      $winner3="Winner of Game 3";
   }

   //check if Final game is in wildcard program yet:
   if($winner3!='' && $winner2!='')
   {
      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='4'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[sidscore]!="" && $row[oppscore]!="")      //get winner of final game
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
	 }
         else 
	 {
	    $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
	 }
         $winner4=GetSchoolName($winner4sid,$sport,$year)." ($score4)";
      }
      else $winner4="Winner of Game 4";
   }
   else $winner4="Winner of Game 4";

   if($match1!='')
   {
      echo "<p>$match1";
      if($seed4!='' && $seed5!='') echo ", $seed4 vs $seed5</p>";
      else echo "</p>";
   }
   if($match2!='')
   {
      echo "<p>$match2";
      if($seed1!='' && $winner1!='') echo ", $seed1 vs $winner1</p>";
      else echo "</p>";
   }
   if($match3!='')
   {
      echo "<p>$match3";
      if($seed3!='' && $seed2!='') echo ", $seed3 vs $seed2</p>";
      else echo "</p>";
   }
   if($match4!='')
   {
      echo "<p>$match4";
      if($winner2!='' && $winner3!='') echo ", $winner2 vs $winner3</p>";
      else echo "</p>";
   }
   if($winner4!='' && !ereg("Winner",$winner4)) echo "<p><b>CHAMPION:</b> $winner4</p>";
}//end if teamct=5
else if($teamct==4)
{
   //MATCH 1:
   $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!="" && $row[sidscore]!="") //get Match 3 info
   {
      //MATCH 3:
      if($row[sidscore]>$row[oppscore]) 
      {
	 $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
      }
      else 
      {
	 $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
      }
      $winner1=GetSchoolName($winner1sid,$sport,$year)." ($score1)";

      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='3'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)     
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
	 }
         else 
	 {
	    $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
	 }
         $winner3=GetSchoolName($winner3sid,$sport,$year)." ($score3)";
      }
      else $winner3="Winner of Game 3";
   }
   else
   {
      $winner1="Winner of Game 1"; $winner3="Winner of Game 3";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!="" && $row[sidscore]!="") //get Match 3 info
   {
      //MATCH 3:
      if($row[sidscore]>$row[oppscore]) 
      {
	 $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
      }
      else 
      {
	 $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
      }
      $winner2=GetSchoolName($winner2sid,$sport,$year)." ($score2)";

      $sql="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='3'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
	 }
         else 
	 {	
	    $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
	 }
         $winner3=GetSchoolName($winner3sid,$sport,$year)." ($score3)";
      }
      else $winner3="Winner of Game 3";
   }
   else
   {
      $winner1="Winner of Game 1"; $winner3="Winner of Game 3";
   }

   if($match1!='')
   {
      echo "<p>$match1";
      if($seed1!='' && $seed4!='') echo ", $seed1 vs $seed4</p>";
      else echo "</p>";
   }
   if($match2!='')
   {
      echo "<p>$match2";
      if($seed3!='' && $seed2!='') echo ", $seed3 vs $seed2</p>";
      else echo "</p>";
   }
   if($match3!='')
   {
      echo "<p>$match3";
      if($winner1!='' && $winner2!='') echo ", $winner1 vs $winner2</p>";
      else echo "</p>";
   }
   if($winner3!='' && !ereg("Winner",$winner3)) echo "<p><b>CHAMPION:</b> $winner3</p>";
}//end if teamct=4
   }//END IF SEEDED AND BRACKETED (ready for public viewing)
}//END FOR EACH DISTRICT
echo "</div>";
echo $end_html;

?>
