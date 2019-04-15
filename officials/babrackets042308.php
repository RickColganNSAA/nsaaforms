<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sport='ba';
$sportname=GetSportName($sport);
$districts=$sport."districts";
$seeds=$sport."seeds";
$disttimes=$sport."disttimes";
$schedtbl=$sport."sched";
$width="215";	//width of each "round" in bracket
$height="100";	//height of each "match"

echo $init_html;
echo "<table width=100%><tr align=center><td>";

//get number of teams
$sql="SELECT * FROM $seeds WHERE distid='$distid'";
$result=mysql_query($sql);
$teamct=mysql_num_rows($result);
if($teamct==0)
{
   $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND day!='0000-00-00'";
   $result=mysql_query($sql);
   $teamct=mysql_num_rows($result);
   $teamct++;
}
$year=GetFallYear($sport);

//get host of district
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostid=$row[hostid];
$sql2="SELECT school FROM $db_name.logins WHERE id='$hostid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$homeid=GetSID2($row2[school],$sport);
if(!($row[post]=='y' && $row[accept]=='y' && $row[confirm]=='y')) $row[hostschool]="??";
echo "<font style=\"font-size:12pt;\"><b>$sportname $row[type] $row[class]-$row[district] at $row[hostschool]</b></font><br><br><br>";
$seeded=$row[seeded]; $bracketed=$row[bracketed];

if($row[showtimes]!='y' && ($row[seeded]!='y' || $row[bracketed]!='y'))	//not available yet
{
   echo "<b>Information not available at this time.</b>";
   echo $end_html;
   exit();
}

//get match & seed info for each game in district:
for($i=1;$i<=$teamct;$i++)
{
   $sql="SELECT day,time FROM $db_name2.$disttimes WHERE distid='$distid' AND gamenum='$i'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $day=split("-",$row[day]);
   $field="match".$i;
   $$field="<font style=\"font-size:9pt;\">Game $i<br>$day[1]/$day[2]/$day[0]<br>$row[time]</font>";

   $sql="SELECT sid,ptavg FROM $db_name2.$seeds WHERE distid='$distid' AND seed='$i'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $field="seed".$i;
   if($seeded!='y' || $bracketed!='y')
      $$field="";
   else
      $$field="<font style=\"font-size:9pt;\">#$i ".GetSchoolName($row[sid],$sport,$year)." $row[ptavg] (".GetWinLoss($row[sid],$sport,$year).")</font>";
   $field="sid".$i;
   if($seeded!='y' || $bracketed!='y')
      $$field='';
   else
      $$field=$row[sid];
}

if($teamct==7)
{
   //MATCH 1:
   $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!='' && $row[sidscore]!='') //get Match 4 info
   {
      //MATCH 4:
      if($row[sidscore]>$row[oppscore])
      {
    	 $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
      }
      else
      {
   	 $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
      }
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,$sport,$year)." ($score1)</font>";
      $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='4'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[oppscore]!='' && $row[sidscore]!='')	//get Match 6 info
      {
	 if($row[sidscore]>$row[oppscore])
	 {
	    $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
	 }
	 else
	 {
	    $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
	 }
	 $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,$sport,$year)." ($score4)</font>"; 
      }
      else $winner4="";
   }
   else $winner1="";
   //MATCH 2:
   $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!='' && $row[sidscore]!='') //get Match 5 info
   {
      //MATCH 5:
      if($row[sidscore]>$row[oppscore])
      {
	 $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
      }
      else
      {
	 $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
      }
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,$sport,$year)." ($score2)</font>";
      $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='5'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[oppscore]!='' && $row[sidscore]!='')	//get Match 6 info
      {
	 if($row[sidscore]>$row[oppscore])
	 {
	    $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
	 }
	 else
	 {
	    $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
	 }
	 $winner5="<font style=\"font-size:9pt;\">".GetSchoolName($winner5sid,$sport,$year)." ($score5)</font>";
      }
      else $winner5="";
   }
   else $winner2="";
   //MATCH 3:
   $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='3'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[oppscore]!='' && $row[sidscore]!='') //get Match 5 info
   {
      //MATCH 5:
      if($row[sidscore]>$row[oppscore])
      {
	 $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
      }
      else
      {
	 $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
      }
      $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,$sport,$year)." ($score3)</font>";
      //already got Match 6 info with Match 2 
   }
   else $winner3="";

   //check if Final game is in wildcard program yet:
   if($winner4!='' && $winner5!='')
   {
      $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='6'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[sidscore]!="" && $row[oppscore]!="")      //get winner of final game
      {
         if($row[sidscore]>$row[oppscore])
         {   
            $winner6sid=$row[sid]; $score6="$row[sidscore]-$row[oppscore]";
         }
         else
         {
            $winner6sid=$row[oppid]; $score6="$row[oppscore]-$row[sidscore]";
         }
         $winner6=GetSchoolName($winner6sid,$sport,$year)." ($score6)";
      }
      else $winner6="WINNER";
   }
   else $winner6="WINNER";
   echo "<table cellspacing=1 cellpadding=0>";
   echo "<tr align=center valign=center>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=0>";
   //Round 1:
   echo "<tr align=center valign=bottom><td width=$width height=60>$seed4</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr
>";
   echo "<tr align=center valign=top><td width=$width height=25>$seed5</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$seed3</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr
>";
   echo "<tr align=center valign=top><td width=$width height=25>$seed6</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$seed7</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=25>$seed2</td></tr>"; 
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   //Round 2:
   echo "<tr align=center valign=bottom><td width=$width height=10>$seed1</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match4</b></td></tr
>";
   echo "<tr align=center valign=top><td width=$width height=75>$winner1</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=75>$winner2</td></tr>";
   echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>$match5</b></td></tr
>";
   echo "<tr align=center valign=top><td width=$width height=75>$winner3</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   //Round 3
   echo "<tr align=center valign=bottom><td width=$width height=60>$winner4</td></tr>";
   echo "<tr align=center><td width=$width height=285 class=border bgcolor=#E0E0E0><b>$match6</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=140>$winner5</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=170>&nbsp;</td></tr>";
   echo "<tr align=center><td width=200 height=30 class=border bgcolor=#E0E0E0><b>$winner6</b></td></tr>";
   echo "<tr align=center><td width=200 height=250>&nbsp;</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";
}//end if teamct==7
else if($teamct==6)
{
   //MATCH 1:
   $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,$sport,$year)." ($score1)</font>";

      $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='3'"; 
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
	 $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,$sport,$year)." ($score3)</font>";
      }
      else $winner3="";
   }
   else $winner1="";

   //MATCH 2:
   $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='2'";
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
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,$sport,$year)." ($score2)</font>";

      $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='4'";
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
         $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,$sport,$year)." ($score4)</font>";
      }
      else $winner4="";
   }
   else
   {
      $winner2="";
   }

   //check if Final game is in wildcard program yet:
   if($winner3!='' && $winner4!='')
   {
      $sql="SELECT * FROM $db_name.$schedtbl WHERE distid='$distid' AND gamenum='5'";
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
      else $winner5="WINNER";
   }
   else $winner5="WINNER";

   echo "<table cellspacing=1 cellpadding=0>";
   echo "<tr align=center valign=center>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=0>";
   //Round 1:
   echo "<tr align=center valign=bottom><td width=$width height=60>$seed4</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=25>$seed5</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$seed3</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=60>$seed6</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   //Round 2:
   echo "<tr align=center valign=bottom><td width=$width height=10>$seed1</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=75>$winner1</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=75>$winner2</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
   echo "<tr align=center><td width=$width height=10>$seed2</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$winner3</td></tr>";
   echo "<tr align=center><td width=$width height=250 class=border bgcolor=#E0E0E0><b>$match5</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=60>$winner4</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=170>&nbsp;</td></tr>";
   echo "<tr align=center><td width=200 height=30 class=border bgcolor=#E0E0E0><b>$winner5</b></td></tr>";
   echo "<tr align=center><td width=200 height=170>&nbsp;</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";
}//end if teamct=6
echo $end_html;

?>
