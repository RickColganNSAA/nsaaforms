<?php
echo   "<div style=\"text-align:center\"><a href=\"/\"><img src=\"/wp-content/uploads/2014/08/nsaalogotransparent250.png\" style=\"height:80;margin:5px;border:0;\"></a></div>";
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$database=$db_name2; //."20122013";

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($database, $db);

if(!$sport || $sport=='') $sport='vb';
if(!$type || $type=="") $type="District Final";
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$year=GetFallYear($sport);

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<font style=\"font-size:16px;\"><b>".GetSportName($sport)." $type Information & Results:</b></font><br><br>";

echo "<table>";
$sql="SELECT DISTINCT class FROM $database.$districts WHERE type='$type' ORDER BY class";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $class=$row['class'];
   $sql2="SELECT t1.*,t2.day,t2.time FROM $database.$districts AS t1, $database.$disttimes AS t2 WHERE t1.id=t2.distid AND t1.type='$type' AND t1.class='$class' ORDER BY t1.district";
   $result2=mysql_query($sql2);
   $ix=0; $percol=mysql_num_rows($result2)/2; $curcol=0;
   echo "<tr align=left><td colspan=2><font style=\"font-size:12px;\"><b><hr><u>$class ".strtoupper($type)."S:</b></u></font></td></tr>";
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
      echo "<tr align=left><td><font style=\"font-size:12px;\">";
      echo "<b>$class-$row2[district] $type:</b><br>";
      $day=split("-",$row2[day]);
      if(trim($row2[time])==": PM CT") $row2[time]="";
      else $row2[time]="@ ".$row2[time];
      if($day[1]=="00" || $day[2]=="00") $date="TBA";
      else $date=date("F j, Y",mktime(0,0,0,$day[1],$day[2],$day[0]));
      echo "$date $row2[time]<br>";
      echo "<b>Host (Site):</b>&nbsp;$row2[hostschool]";
      if($row2[site]!='') echo " ($row2[site])";
      echo "<br>";
      echo "<b>Teams:</b>&nbsp;&nbsp;$row2[schools]<br>";
      $sql3="SELECT * FROM $db_name.".$sport."sched WHERE distid='$row2[id]'";
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
