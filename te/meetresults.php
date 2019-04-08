<?php
require '../variables.php';
require '../functions.php';
require '../../calculate/functions.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!$sport) $sport="te_b";
if($sport=='teb') $sport="te_b";
else if($sport=='teg') $sport="te_g";

//$sql="USE nsaascores20122013";
//$result=mysql_query($sql);

echo $init_html;
echo "<table width=100%><tr align=center><td>";

if(!$meetid)
{
   if(!$week)	//FIND THIS WEEK OR DEFAULT TO WEEK 1
   {
      $sql2="SELECT startdate,YEAR(startdate) FROM ".$sport."meets WHERE WEEK(startdate)=WEEK(CURDATE()) LIMIT 1";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql2="SELECT WEEK(startdate),YEAR(startdate) FROM ".$sport."meets ORDER BY startdate LIMIT 1";
         $result2=mysql_query($sql2);
      }
      $row2=mysql_fetch_array($result2);
      $week=$row2[0]; $year=$row2[1];
   }
   else
   {
      $sql2="SELECT WEEK(startdate),YEAR(startdate) FROM ".$sport."meets ORDER BY startdate LIMIT 1";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $year=$row2[1];
   }

   $sql="SELECT DISTINCT WEEK(startdate) FROM ".$sport."meets WHERE startdate!='0000-00-00' ORDER BY startdate";
   $result=mysql_query($sql);
   echo "<br><h2>$year ".GetActivityName($sport)." Meet Results:</h2>";
   if(mysql_num_rows($result)==0)
      echo "<p><i>No results have been entered yet for this season.</i></p><p>";
   else
      echo "<p>Click on a set of dates to see results:</p><p>";
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT startdate FROM ".$sport."meets WHERE WEEK(startdate)='$row[0]' ORDER BY startdate LIMIT 1";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $start=explode("-",$row2[0]);
      $start=date("M j",mktime(0,0,0,$start[1],$start[2],$start[0]));
      $sql2="SELECT enddate FROM ".$sport."meets WHERE WEEK(startdate)='$row[0]' ORDER BY enddate DESC LIMIT 1";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $end=explode("-",$row2[0]);
      $end=date("M j",mktime(0,0,0,$end[1],$end[2],$end[0]));
      if($week==$row[0])
         echo "<label style=\"color:#a0a0a0;\"><b><u>$start-$end</b></u></label>&nbsp;&nbsp;&nbsp;";
      else
         echo "<a class=small href=\"meetresults.php?sport=$sport&week=$row[0]\">$start-$end</a>&nbsp;&nbsp;&nbsp;";
   }
   echo "</p>";

   echo "<table style=\"width:600px;\"><tr align=left><td>";

   //SHOW MEETS IN THIS WEEK
   $sql="SELECT * FROM ".$sport."meets WHERE WEEK(startdate)='$week' ORDER BY startdate,enddate,meetname";
   $result=mysql_query($sql);
   echo "<ul>";
   while($row=mysql_fetch_array($result))
   {
      $start=explode("-",$row[startdate]); $end=explode("-",$row[enddate]);
      $startsec=mktime(0,0,0,$start[1],$start[2],$start[0]);
      $endsec=mktime(0,0,0,$end[1],$end[2],$end[0]);
      $date=date("M j",$startsec);
      if(date("j",$startsec)==date("j",$endsec))      //SAME DAY
      {}
      else if(date("M",$startsec)==date("F",$endsec)) //SAME MONTH
         $date.="-".date("j",$endsec);
      else    //DIFF DAY AND MONTH
         $date.="-".date("M j",$endsec);    
      echo "<li>$date: <a class=\"small\" href=\"meetresults.php?sport=$sport&meetid=$row[id]&week=$week\">$row[meetname]</a> (at $row[meetsite])</li>";
   }
   echo "</ul>";

   echo "</td></tr></table>";

   echo "<br><br><a href=\"javascript:window.close();\">Close Window</a>";
   echo $end_html;
   exit();
}//END IF NO MEET ID GIVEN

//RESULTS
$sql="SELECT * FROM ".$sport."meets WHERE id='$meetid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$results=GetTennisMeetResults($sport,$meetid,0);
echo "<br><a href=\"meetresults.php?sport=$sport&week=$week\">&larr; Return to ".GetActivityName($sport)." Results</a><br><br>";
echo "<table frame=all rules=all style='border:#808080 1px solid;' cellspacing=0 cellpadding=5><caption><b>Results: $row[meetname]</b><br>";
$start=explode("-",$row[startdate]); $end=explode("-",$row[enddate]);
$startsec=mktime(0,0,0,$start[1],$start[2],$start[0]);
$endsec=mktime(0,0,0,$end[1],$end[2],$end[0]);
$date=date("F j",$startsec);
if(date("j",$startsec)==date("j",$endsec))	//SAME DAY
   $date.=", ".date("Y",$startsec);
else if(date("F",$startsec)==date("F",$endsec))	//SAME MONTH
   $date.="-".date("j, Y",$endsec);
else	//DIFF DAY AND MONTH
   $date.="-".date("F j, Y",$endsec);
echo "$date<br>at $row[meetsite]<br><br>";
echo "</caption>";
echo "<tr align=center><td><b>Division</b></td><td><b>Winning Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Defeated Player(s)</b></td><td><b>Varsity/JV</b></td><td><b><b>Score</b></td></tr>";
$results=split("<result>",$results);
$resultct=0;
for($i=0;$i<count($results);$i++)
{
   $details=split("<detail>",$results[$i]);
   if(ereg("Doubles",$details[1])) $color="#fafda2";
   else $color="#a0a0fa";
   echo "<tr align=left><td bgcolor=\"$color\">$details[1]</td><td>$details[2]</td><td>$details[3]</td><td>$details[4]</td><td>$details[5]</td><td>$details[7]</td></tr>";
   $resultct++;
}
echo "</table>";
if($resultct==0)
   echo "<br><br>[No results have been entered for this meet yet.]";
echo $end_html;
?>
