<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sport='bbb';
$sportname=GetSportName($sport);
$districts=$sport."districts";
$year=GetFallYear($sport);

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<font style=\"font-size:12pt;\"><b>$sportname District Final Information & Results:</b></font><br><br>";

echo "<table>";
$sql="SELECT DISTINCT class FROM $db_name2.bbbdistricts WHERE type='District Final' ORDER BY class";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $class=$row['class'];
   $sql2="SELECT t1.*,t2.day,t2.time FROM $db_name2.bbbdistricts AS t1, $db_name2.bbbdisttimes AS t2 WHERE t1.id=t2.distid AND t1.type='District Final' AND t1.class='$class' ORDER BY t1.district";
   $result2=mysql_query($sql2);
   $ix=0; $percol=mysql_num_rows($result2)/2; $curcol=0;
   echo "<tr align=left><td colspan=2><font style=\"font-size:9pt;\"><b><hr><u>$class DISTRICT FINALS:</b></u></font></td></tr>";
   echo "<tr valign=top align=center><td width=300><table width=100% cellspacing=0 cellpadding=0>";
   while($row2=mysql_fetch_array($result2))
   {
      if($curcol>=$percol) 
      {
 	 echo "</table></td><td width=300><table width=100% cellspacing=0 cellpadding=0>";
	 $curcol=0;
      }
      if($ix%2==0) echo "<tr align=center><td bgcolor=#E0E0E0>";
      else echo "<tr align=center><td>";
      echo "<table width=100%>";
      echo "<tr align=left><td><font style=\"font-size:9pt;\">";
      echo "<b>$class-$row2[district] District Final:</b><br>";
      $day=split("-",$row2[day]);
      $date=date("F j, Y",mktime(0,0,0,$day[1],$day[2],$day[0]));
      if($row2[day]=='0000-00-00') $date="";
      if($row2[time]==": PM CST") $row2[time]="";
      if($row2[time]=="") $row2[time]=="";
      else $row2[time]="@ ".$row2[time];
      echo "$date $row2[time]<br>";
      echo "<b>Host (Site):</b>&nbsp;$row2[hostschool] ($row2[site])<br>";
      echo "<b>Teams:</b>&nbsp;&nbsp;$row2[schools]<br>";
      $sql3="SELECT * FROM $db_name.bbbsched WHERE distid='$row2[id]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      if(mysql_num_rows($result3)==0 || $row3[sidscore]=="" || $row3[oppscore]=="")
      {
         $winner=""; $score="";
      }
      else if($row3[sidscore]>$row3[oppscore])
      {
         $winner=GetSchoolName($row3[sid],$sport,$year); $score="($row3[sidscore]-$row3[oppscore])";
      }
      else
      {
	 $winner=GetSchoolName($row3[oppid],$sport,$year); $score="($row3[oppscore]-$row3[sidscore])";
      }
      echo "<b>WINNER:</b>&nbsp;&nbsp;$winner $score";
      echo "</td></tr></table>";
      echo "</td></tr>";
      $ix++; $curcol++;
   }
   echo "</table></td></tr>";
}
echo "<tr align=center><td colspan=2><hr></td></tr></table>";
    
echo $end_html;
exit();
?>
