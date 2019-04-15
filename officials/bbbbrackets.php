<?php
echo   "<div style=\"text-align:center\"><a href=\"/\"><img src=\"/wp-content/uploads/2014/08/nsaalogotransparent250.png\" style=\"height:80;margin:5px;border:0;\"></a></div>";
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sport='bbb';
$sportname=GetSportName($sport);
$districts=$sport."districts";
$width="215";	//width of each "round" in bracket
$height="100";	//height of each "match"

echo $init_html;
echo "<table width=100%><tr align=center><td>";

//get number of teams
$sql="SELECT * FROM bbbseeds WHERE distid='$distid'";
$result=mysql_query($sql);
$teamct=mysql_num_rows($result);
if($teamct==0)
{
   $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND day!='0000-00-00'";
   $result=mysql_query($sql);
   $teamct=mysql_num_rows($result);
   $teamct++;
}
$year=GetFallYear('bbb');

//get host of district
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql2="SELECT school FROM $db_name.logins WHERE id='$row[hostid]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$homeid=GetSID2($row2[school],'bbb');
if(!($row[post]=='y' && $row[accept]=='y' && $row[confirm]=='y')) $row[hostschool]="??";
else if($row[site]!='') $row[hostschool]=$row[site];
echo "<font style=\"font-size:12pt;\"><b>$sportname $row[type] $row[class]-$row[district]";
if($row['class']!="A") //CLASS A BASKETBALL
   echo " at $row[hostschool]";
echo "</b></font><br><br><br>";
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
   $sql="SELECT * FROM $db_name2.bbbdisttimes WHERE distid='$distid' AND gamenum='$i'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $day=split("-",$row[day]);
   $field="match".$i;
   $$field="<font style=\"font-size:12px;\">";
   if($row[showgamenum]!='')
      $$field.=$row[showgamenum];
   else
      $$field.="Game $i";
   $$field.="<br>$day[1]/$day[2]/$day[0]<br>$row[time]";
   if($class=="A") //CLASS A BASKETBALL
   {
      if(trim($row[site])!='') $site=$row[site];
      else $site=$row[hostschool];
      $$field.="<br>at $site";
   }
   if($row[notes]!='') $$field.="<br>$row[notes]";
   $$field.="</font>";

   $sql="SELECT sid,ptavg FROM $db_name2.bbbseeds WHERE distid='$distid' AND seed='$i'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $field="seed".$i;
   if($seeded!='y' || $bracketed!='y')
      $$field='';
   else
      $$field="<font style=\"font-size:9pt;\">#$i ".GetSchoolName($row[sid],'bbb',$year)." $row[ptavg] (".GetWinLoss($row[sid],'bbb',$year).")</font>";
   $field="sid".$i;
   if($seeded!='y' || $bracketed!='y')
      $$field='';
   else
      $$field=$row[sid];
}

if($teamct==6)
{
   //MATCH 1:
   $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'bbb',$year)." ($score1)</font>";

      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='3'"; 
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
	 $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'bbb',$year)." ($score3)</font>";
      }
      else $winner3="";
   }
   else
   {
      $winner1="";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='2'";
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
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'bbb',$year)." ($score2)</font>";

      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='4'";
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
         $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,'bbb',$year)." ($score4)</font>";
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
      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='5'";
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
	 $winner5=GetSchoolName($winner5sid,'bbb',$year)." ($score5)";
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
   $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'bbb',$year)." ($score1)</font>";

      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='2'";
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
         $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'bbb',$year)." ($score2)</font>";
      }
      else $winner2="";
   }
   else
   {
      $winner1="";
   }

   //MATCH 3:
   $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='3'";
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
      $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'bbb',$year)." ($score3)</font>";
   }
   else
   {
      $winner3="";
   }

   //check if Final game is in wildcard program yet:
   if($winner3!='' && $winner2!='')
   {
      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='4'";
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
         $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,'bbb',$year)." ($score4)</font>";
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
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=75>$winner1</td></tr>";
   echo "<tr align=center valign=bottom><td width=$width height=25>$seed3</td></tr>";
   echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=60>$seed2</td></tr>";
   echo "</table>";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=0>";
   echo "<tr align=center valign=bottom><td width=$width height=60>$winner2</td></tr>";
   echo "<tr align=center><td width=$width height=210 class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
   echo "<tr align=center valign=top><td width=$width height=110>$winner3</td></tr>";
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
   $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='1'";
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
      $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'bbb',$year)." ($score1)</font>";

      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='3'";
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
         $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'bbb',$year)." ($score3)</font>";
      }
      else $winner3="WINNER";
   }
   else
   {
      $winner1=""; $winner3="WINNER";
   }

   //MATCH 2:
   $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='2'";
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
      $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'bbb',$year)." ($score2)</font>";

      $sql="SELECT * FROM $db_name.bbbsched WHERE distid='$distid' AND gamenum='3'";
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
         $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'bbb',$year)." ($score3)</font>";
      }
      else $winner3="WINNER";
   }
   else
   {
      $winner1=""; $winner3="WINNER";
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
}//end if teamct=4
echo $end_html;

?>
