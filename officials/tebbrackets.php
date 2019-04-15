<?php
echo   "<div style=\"text-align:center\"><a href=\"/\"><img src=\"/wp-content/uploads/2014/08/nsaalogotransparent250.png\" style=\"height:80;margin:5px;border:0;\"></a></div>";
require 'functions.php';
require 'variables.php';
require '/data/public_html/calculate/functions.php'; //Wildcard Functions

$db1=$db_name;
$db2=$db_name2;
//TESTING
//$db1="nsaascores20122013";
//$db2="nsaaofficials20122013";

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db2, $db);

$sql="SELECT schools FROM tebdistricts WHERE type='1stRound' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$first_round = explode(",",$row[schools]); 

$sql1="SELECT schools FROM tebdistricts WHERE type='2ndRound' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result1=mysql_query($sql1);
$row1=mysql_fetch_assoc($result1);
$second_round = explode(",",$row1[schools]); 

$sql2="SELECT schools FROM tebdistricts WHERE type='Quaterfinal' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_assoc($result2);
$quater_final = explode(",",$row2[schools]); 

$sql3="SELECT schools FROM tebdistricts WHERE type='Semifinal' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result3=mysql_query($sql3);
$row3=mysql_fetch_assoc($result3);
$semifinal = explode(",",$row3[schools]); 

$sql4="SELECT schools FROM tebdistricts WHERE type='Final' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result4=mysql_query($sql4);
$row4=mysql_fetch_assoc($result4);
$final = explode(",",$row4[schools]); 

$sql5="SELECT schools FROM tebdistricts WHERE type='3rdPlace' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result5=mysql_query($sql5);
$row5=mysql_fetch_assoc($result5);
$third_place = explode(",",$row5[schools]); 

$sql6="SELECT schools FROM tebdistricts WHERE type='5thPlaceQualify' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result6=mysql_query($sql6);
$row6=mysql_fetch_assoc($result6);
$fifth_qualify = explode(",",$row6[schools]); 

$sql7="SELECT schools FROM tebdistricts WHERE type='5thPlace' AND class='$_GET[class]'  AND district='$_GET[district]'";
$result7=mysql_query($sql7);
$row7=mysql_fetch_assoc($result7);
$fifth_place = explode(",",$row7[schools]); 

$sport='teb';
$sportname=GetSportName($sport);
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$width="240";	//width of each "round" in bracket
$height="80";	//height of each "match"

echo $init_html;
   echo "<table cellspacing=1 cellpadding=1><tr><td ><b style=\"margin-left:80px\" >1st Round</b></td><td><b style=\"margin-left:200px\">2nd Round</b></td><td><b style=\"margin-left:170px\">Quaterfinal</b></td><td><b style=\"margin-left:150px\">Semifinal</b></td><td><b style=\"margin-left:150px\">Final</b></td></tr></table>";
   echo "<table cellspacing=1 cellpadding=1>";
   echo "<tr align=center valign=center>";
   echo "<td>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[0]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 1</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[31]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[15]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 16</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[16]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[8]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 9</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[23]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[7]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 8</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[24]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[4]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 5</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[27]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[11]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 12</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[20]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[12]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 13</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[19]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[3]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 4</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[28]</td></tr>";
   echo "</table>"; 

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[2]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[29]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[13]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 14</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[18]</td></tr>";
   echo "</table>"; 

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[10]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 11</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[21]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[5]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 6</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[26]</td></tr>";
   echo "</table>";   

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[6]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 7</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[25]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[9]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 10</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[22]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[14]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 15</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[17]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=20>$first_round[30]</td></tr>";
   echo "<tr align=center><td width=$width height=50 class=border bgcolor=#E0E0E0><b>1st Round Game 2</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=10>$first_round[1]</td></tr>";
   echo "</table>";   
   echo "</td><td>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[0]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 1</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[15]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[8]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 8</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[7]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[4]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 5</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[11]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[12]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 4</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[3]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[2]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[13]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[10]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 6</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[5]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[6]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 7</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[9]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=40>$second_round[14]</td></tr>";
   echo "<tr align=center><td width=$width height=80 class=border bgcolor=#E0E0E0><b>2nd Round Game 2</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=45>$second_round[1]</td></tr>";
   echo "</table>";

   echo "</td><td>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=50>$quater_final[0]</td></tr>";
   echo "<tr align=center><td width=200 height=200 class=border bgcolor=#E0E0E0><b>Quaterfinal 1</b></td></tr>";
   echo "<tr align=center><td width=200 height=80>$quater_final[7]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=50>$quater_final[4]</td></tr>";
   echo "<tr align=center><td width=200 height=200 class=border bgcolor=#E0E0E0><b>Quaterfinal 4</b></td></tr>";
   echo "<tr align=center><td width=200 height=80>$quater_final[3]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=50>$quater_final[2]</td></tr>";
   echo "<tr align=center><td width=200 height=200 class=border bgcolor=#E0E0E0><b>Quaterfinal 3</b></td></tr>";
   echo "<tr align=center><td width=200 height=80>$quater_final[5]</td></tr>";
   echo "</table>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=50>$quater_final[6]</td></tr>";
   echo "<tr align=center><td width=200 height=200 class=border bgcolor=#E0E0E0><b>Quaterfinal 2</b></td></tr>";
   echo "<tr align=center><td width=200 height=80>$quater_final[1]</td></tr>";
   echo "</table>";   

   echo "</td><td>";

   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=80>$semifinal[0]</td></tr>";
   echo "<tr align=center><td width=200 height=400 class=border bgcolor=#E0E0E0><b>Semifinal 1</b></td></tr>";
   echo "<tr align=center><td width=200 height=160>$semifinal[3]</td></tr>";
   echo "</table>";
   
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=80>$semifinal[2]</td></tr>";
   echo "<tr align=center><td width=200 height=400 class=border bgcolor=#E0E0E0><b>Semifinal 2</b></td></tr>";
   echo "<tr align=center><td width=200 height=160>$semifinal[1]</td></tr>";
   echo "</table>";

   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=80>$final[0]</td></tr>";
   echo "<tr align=center><td width=200 height=700 class=border bgcolor=#E0E0E0><b>Final</b></td></tr>";
   echo "<tr align=center><td width=200 height=160>$final[1]</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";
   echo "<br><br><br>";

   
   echo "<table cellspacing=1 cellpadding=1><tr><td ><b style=\"margin-left:80px\" >3rd Place</b></td></tr></table>";
   echo "<table cellspacing=1 cellpadding=1>";
   echo "<tr align=center valign=center>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$third_place[0]</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>3rd Place</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=25>$third_place[1]</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";
   echo "<br><br><br>";
   
   echo "<table cellspacing=1 cellpadding=1><tr><td ><b style=\"margin-left:80px\" >5th Place Qualify</b></td><td><b style=\"margin-left:200px\">5th Place Final</b></td></tr></table>"; 
   echo "<table cellspacing=1 cellpadding=1>";
   echo "<tr align=center valign=center>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$fifth_qualify[0]</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>5th Place Qualify</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=25>$fifth_qualify[3]</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$fifth_qualify[1]</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>5th Place Qualify</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=60>$fifth_qualify[2]</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=$height>$fifth_place[0]</td></tr>";
   echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>5th Place Final</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=$height>$fifth_place[1]</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";

//get number of teams
$sql="SELECT * FROM vbseeds WHERE distid='$distid'";
$result=mysql_query($sql);
$teamct=mysql_num_rows($result);
if($teamct==0)
{
   $sql="SELECT * FROM $db2.$disttimes WHERE distid='$distid' AND day!='0000-00-00'";
   $result=mysql_query($sql);
   $teamct=mysql_num_rows($result);
   $teamct++;
}
$year=GetFallYear('teb');

//get host of district
$sql="SELECT * FROM $db2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql2="SELECT school FROM $db1.logins WHERE id='$row[hostid]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$homeid=GetSID2($row2[school],'vb');
if(!($row[post]=='y' && $row[accept]=='y' && $row[confirm]=='y')) $row[hostschool]="??";
else if($row[site]!='') $row[hostschool]=$row[site];
//echo "<font style=\"font-size:12pt;\"><b>$sportname $row[type] $row[class]-$row[district] at $row[hostschool]</b></font><br><br><br>";
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
   $sql="SELECT day,time,notes,showgamenum FROM $db2.vbdisttimes WHERE distid='$distid' AND gamenum='$i'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $day=split("-",$row[day]);
   $field="match".$i;
   if($row['showgamenum']=='') $row['showgamenum']="Match $i";
   $$field="<font style=\"font-size:9pt;\">$row[showgamenum]<br>$day[1]/$day[2]/$day[0]<br>$row[time]";
   if($row[notes]!='') $$field.="<br>$row[notes]";
   $$field.="</font>";

   $sql="SELECT sid,ptavg FROM $db2.vbseeds WHERE distid='$distid' AND seed='$i'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $field="seed".$i;
   if($seeded!='y' || $bracketed!='y')
      $$field='';
   else
      $$field="<font style=\"font-size:9pt;\">#$i ".GetSchoolName($row[sid],'vb',$year)." $row[ptavg] (".GetWinLoss($row[sid],'vb',$year).")</font>";
   $field="sid".$i;
   if($seeded!='y' || $bracketed!='y')
      $$field='';
   else
      $$field=$row[sid];
}

/*if($teamct==6)
{
   //MATCH 1:
   $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'vb',$year)." ($score1)</font>";

      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='3'"; 
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
	 $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'vb',$year)." ($score3)</font>";
      }
      else $winner3="";
   }
   else
   {
      $winner1="";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='2'";
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
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'vb',$year)." ($score2)</font>";

      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='4'";
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
         $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,'vb',$year)." ($score4)</font>";
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
      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='5'";
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
	 $winner5=GetSchoolName($winner5sid,'vb',$year)." ($score5)";
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
else if($teamct==5)
{
   $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'vb',$year)." ($score1)</font>";

      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='3'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      if($row[oppscore]!="" && $row[sidscore]!="")      //get Match 4 info
      {
         if($row[sidscore]>$row[oppscore]) 
	 {
	    $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
	 }
         else 
	 {
	    $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
	 }
         $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'vb',$year)." ($score3)</font>";
      }
      else $winner3="";
   }
   else
   {
      $winner1="";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='2'";
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
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'vb',$year)." ($score2)</font>";
   }
   else
   {
      $winner2="";
   }

   //check if Final game is in wildcard program yet:
   if($winner3!='' && $winner2!='')
   {
      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='4'";
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
         $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,'vb',$year)." ($score4)</font>";
      }
      else $winner4="WINNER";
   }
   else $winner4="WINNER";

   echo "<table cellspacing=1 cellpadding=0>";
   echo "<tr align=center valign=center>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$seed4</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=75>$seed5</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=135>&nbsp;</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=10>$seed1</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=75>$winner1</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$seed3</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=60>$seed2</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$winner3</td></tr>";
   echo "<tr align=center><td width=$width height=210 class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=110>$winner2</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=150>&nbsp;</td></tr>";
   echo "<tr align=center><td width=200 height=30 class=border bgcolor=#E0E0E0><b>$winner4</b></td></tr>";
   echo "<tr align=center><td width=200 height=200>&nbsp;</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";
}//end if teamct=5
else if($teamct==4)
{
   //MATCH 1:
   $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'vb',$year)." ($score1)</font>";

      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='3'";
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
         $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'vb',$year)." ($score3)</font>";
      }
      else $winner3="WINNER";
   }
   else
   {
      $winner1=""; $winner3="WINNER";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='2'";
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
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'vb',$year)." ($score2)</font>";

      $sql="SELECT * FROM $db1.vbsched WHERE distid='$distid' AND gamenum='3'";
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
         $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'vb',$year)." ($score3)</font>";
      }
      else $winner3="WINNER";
   }
   else
   {
      $winner2=""; $winner3="WINNER";
   }

   echo "<table cellspacing=1 cellpadding=1>";
   echo "<tr align=center valign=center>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$seed1</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=25>$seed4</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$seed3</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=60>$seed2</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=$height>$winner1</td></tr>";
   echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=$height>$winner2</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center><td width=200 height=170>&nbsp;</td></tr>";
   echo "<tr align=center><td width=200 height=30 class=border bgcolor=#E0E0E0><b>$winner3</b></td></tr>";
   echo "<tr align=center><td width=200 height=170>&nbsp;</td></tr>";
   echo "</table>";
   echo "</td>";
   echo "</tr>";
   echo "</table>";
}*///end if teamct=4

echo $end_html;

?>
