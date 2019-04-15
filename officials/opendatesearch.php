<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

echo $init_html;
if($sport && $sport!='' && $date && $date!='')
{
   $temp=split("-",$date); $mo=$temp[1];
   echo "<table width=100%><tr valign=top align=center><td>";
   echo "<a class=small href=\"javascript:window.close();\">Close Window</a><br><br>";
   echo "<table class=nine width=100%><caption><b><u>".GetSportName($sport)." Open Date: $temp[1]/$temp[2]/$temp[0]</u></b></caption>";
   $comments=$sport."comments";
   $sql="SELECT t1.offid,t1.$comments,t2.* FROM opendates AS t1, officials AS t2 WHERE t1.$sport='x' AND t1.contestdate='$date' AND t1.offid=t2.id ORDER BY t2.last,t2.first";
   $result=mysql_query($sql);
   echo "<tr align=left><td><i>".mysql_num_rows($result)." Official";
   if(mysql_num_rows($result)>1) echo "s";
   echo " Available:</i></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left><td><br><b>$row[first] $row[last]</b> ($row[city])<br>";
      if($row[email]!='') echo "<a href=\"mailto:$row[email]\">$row[email]</a><br>";
      if($row[homeph]!='') echo "(H) (".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>"; 
      if($row[workph]!='') echo "(W) (".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
      if($row[cellph]!='') echo "(C) (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4)."<br>";
      if($row[$comments]!='')
         echo "Comments: <i>$row[$comments]</i>";
      echo "</td></tr>";
   }
   echo "</table>";
   echo "</form><br>";
   echo "<a class=small href=\"javascript:window.close();\">Close Window</a>";
   echo $end_html;
   exit();
}
echo "<table width=100%><tr align=center><td>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a><br><br>";
echo "<br><form method=post action=\"opendatesearch.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<div style=\"position:relative;z-index:1;width:750;height:1000;\">";
echo "<div style=\"position:absolute;top:10px;left:10px;text-align:left;\">";
echo "<font style=\"font-size:9pt\"><b>Please Select a Sport:&nbsp;</b></font>";
echo "<select name=sport onchange=\"submit();\"><option value=''>~</option>";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value=\"$activity[$i]\"";
   if($sport==$activity[$i]) echo " selected";
   echo ">".GetSportName($activity[$i])."</option>";
}
echo "</select><br><br>";
if(!$sport || $sport=="")
{
   echo "<br><table width=500 class=nine><tr align=left><td><i>Please select a sport above in order to view open dates for officials for the selected sport.</i></td><tr></table></form>";
   echo $end_html;
   exit();
}
else
{
echo "<font style=\"font-size:9pt\"><b>Please Select a Month:&nbsp;</b></font>";
if(!$month)
   $month=date("m")." ".date("Y"); 
if(ereg("-",$month))
   $month=ereg_replace("-"," ",$month);
$temp=split(" ",$month);
if(substr($temp[0],0,1)=="0") 
{
   $temp[0]=substr($temp[0],1,1);
   $month=substr($month,1,strlen($month)-1);
}
$monthch=$temp[0]; $yearch=$temp[1];
if($monthch<7) $fallyr=$yearch-1;
else $fallyr=$yearch;
$springyr=$fallyr+1;
echo "<select name=month onchange=\"submit();\">";
for($i=7;$i<=18;$i++)
{
   if($i>12)
   {
      $yr=$springyr; $mo=$i-12;
   }
   else
   {
      $yr=$fallyr; $mo=$i;
   }
   echo "<option value=\"$mo $yr\"";
   if("$mo $yr"==$month) echo " selected";
   echo ">".date("F",mktime(0,0,0,$i,1,$yr))." $yr";
}
echo "</select><br><font style=\"font-size:9pt\"><i>Click on a date with officials available to view the officials' contact info as well as any comments they mave have posted.</i></font></div>";
$date = getdate(mktime(0,0,0,$monthch,1,$yearch));
$month_num = $date["mon"];
$month_name = $date["month"];
$year = $date["year"];
$date_today = getdate(mktime(0,0,0,$month_num,1,$yearch));
$first_week_day = $date_today["wday"];
$cont = true;
$today = 27;
while (($today <= 32) && ($cont))
{
   $date_today = getdate(mktime(0,0,0,$month_num,$today,$yearch));
   if($date_today["mon"] != $month_num)
   {
      $lastday = $today - 1;
      $cont = false;
   }
   $today++;
}
$top=90; $left=10; $inc=100;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Sunday</b></div>";
$left+=$inc;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Monday</b></div>";
$left+=$inc;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Tuesday</b></div>";
$left+=$inc;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Wednesday</b></div>";
$left+=$inc;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Thursday</b></div>";
$left+=$inc;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Friday</b></div>";
$left+=$inc;
echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:100;text-align:center;\"><b>Saturday</b></div>";
$day = 1;
$wday = $first_week_day;
$firstweek = true;
$top+=20; $left=10;
while ( $day <= $lastday)
{
   if ($firstweek)
   {
      for ($i=1; $i<=$first_week_day; $i++)
      {
         echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;border: black 1px solid;width:99;height:99;\">&nbsp;</div>";
         $left+=$inc;
      }
      $firstweek = false;
   }
   if($wday==0) { $top+=$inc; $left=10; }
   if ( intval($month_num) < 10) { $new_month_num = "0$month_num"; }
   elseif (intval($month_num) >= 10) { $new_month_num = $month_num; }
   if ( intval($day) < 10) { $new_day = "0$day"; }
   elseif (intval($day) >= 10) { $new_day = $day; }
   $link_date = "$yearch-$new_month_num-$new_day";
   $sql="SELECT DISTINCT offid FROM opendates WHERE $sport='x' AND contestdate='$link_date'";
   $result=mysql_query($sql);
   $count=mysql_num_rows($result);
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:$inc;text-align:right;border: black 1px solid;width:99;height:99;font-size:10pt;\"";
   if($count>0)
      echo " onMouseOver=\"this.style.background='#E0E0E0';\" onMouseOut=\"this.style.background='#FFFFFF';\" onClick=\"window.open('opendatesearch.php?sport=$sport&date=$link_date','$link_date','width=500,height=500,scrollbars=yes');\"";
   echo ">";
   $top2=$top+15;
   echo "<table width=100%><tr align=right><td class=nine><b>$day</b>&nbsp;<br><br></td></tr>";
   if($count==1)
      $string="<tr align=center><td>$count Official Available</td></tr>";
   else if($count>0)
      $string="<tr align=center><td>$count Officials Available</td></tr>";
   else
      $string="<tr align=center><td><font style=\"color:#A0A0A0\">No Officials Available</font></td></tr>";
   echo "$string</table></div>";
   $left+=$inc;
   $wday++;
   $wday = $wday % 7;
   $day++;
}  
//now finish out blank days in last week of month:
while($wday<7)
{
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;border:black 1px solid;width:99;height:99;\">&nbsp;</div>";
   $left+=$inc;
   $wday++;
}
echo "</div></td>";
echo "</td></table></form>";
echo "<br><a class=small href=\"javascript:window.close();\">Close Window</a><br><br>";
}//end if sport chosen
echo $end_html;
?>
