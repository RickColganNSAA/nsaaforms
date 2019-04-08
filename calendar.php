<?php
/**********************************
calendar.php
Created: 6/23/08
EDIT Calendar Page
Author: Ann Gaffigan
***********************************/

require 'functions.php';
require '../functions.php';
require 'variables.php';

$header=GetHeader($session);
$header=ereg_replace("<link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"></head>","<link href=\"/css/nsaastyle.css\" rel=stylesheet type=\"text/css\"></head>",$init_html).$header;
$level=GetLevel($session);

$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $header;
echo "<form method=post action=\"calendar.php\">";
echo "<br><input type=hidden name=session value=\"$session\">";
echo "<table width=500>";
echo "<caption><b>NSAA Month-to-Month Calendar of Events</b><br><i>Click on a day to view/edit the full list of events on that day.</i></caption></table>";
echo "<div style='position:relative;width:800px;text-align:center;'>";

if($showdate && $showdate!='')
{
   $date=split(";",$showdate);
   $month=$date[0]; $year=$date[1];
}
if(!$month || $month=='') $month=date("n");
if(!$year || $year=='') $year=date("Y");
$date=getdate(mktime(0,0,0,$month,1,$year));   
$month_num=$date["mon"];   
$month_name=$date["month"];   
$year=$date["year"];   
$date_today=getdate(mktime(0,0,0,$month_num,1,$year));   
$first_week_day=$date_today["wday"];   
$cont = true;   
$today = 27;   
while(($today<=32) && ($cont))   
{      
   $date_today = getdate(mktime(0,0,0,$month_num,$today,$year));      
   if ($date_today["mon"] != $month_num)      
   {         
      $lastday = $today - 1;         
      $cont = false;      
   }      
   $today++;   
}   
if($month==1)   
{      
   $prevmonth=12; $prevyear=$year-1; $nextmonth=$month+1; $nextyear=$year;   
}   
else if($month==12)   
{      
   $prevmonth=$month-1; $prevyear=$year; $nextmonth=1; $nextyear=$year+1;   
}   
else   
{      
   $prevmonth=$month-1; $prevyear=$year; $nextmonth=$month+1; $nextyear=$year;   
}   
echo "<div style=\"position:absolute;top:15px;left:250px;\">";
echo "<a href=\"calendar.php?session=$session&month=$prevmonth&year=$prevyear\"><< ".date("M Y",mktime(0,0,0,$prevmonth,1,$prevyear))."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
/*****************************************************
DROP DOWN LIST OF OTHER MONTHS, CURRENT MONTH SELECTED
******************************************************/
//DON'T GO MORE THAN 1 YR AGO TO START, END ON JUNE 1 AFTER THE LAST THING ENTERED ON THE CALENDAR
$oneyrago=mktime(0,0,0,date("m"),date("j"),date("Y")-1);
$startmonth=date("m",$oneyrago);
$startyear=date("Y",$oneyrago);
$sql="SELECT * FROM calendar ORDER BY enddate DESC LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$lastdate=split("-",$row[enddate]);
if($lastdate[1]>7) $endyear=$lastdate[0]+1;
else $endyear=$lastdate[0];
$endmonth=6;
$showyear=$startyear;
$dates=array(); $i=0;
for($y=$startyear;$y<=$endyear;$y++)
{
   if($y==$startyear) $mo1=$startmonth;
   else $mo1=1;
   if($y==$endyear) $mo2=$endmonth;
   else $mo2=12;
   for($m=$mo1;$m<=$mo2;$m++)
   {
      $dates[$i]="$m;$y"; $i++;
   }
}

echo "<select name=\"showdate\" onchange=\"submit();\">";
for($i=0;$i<count($dates);$i++)
{
   $cur=split(";",$dates[$i]);
   $showmonth=$cur[0]; $showyear=$cur[1];
   echo "<option value=\"$dates[$i]\"";
   if($month==$showmonth && $year==$showyear) echo " selected";
   echo ">".date("F Y",mktime(0,0,0,$showmonth,1,$showyear))."</option>";
}
echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<a href=\"calendar.php?session=$session&month=$nextmonth&year=$nextyear\">".date("M Y",mktime(0,0,0,$nextmonth,1,$nextyear))."></a></div>";
$top=50;
$left=1;
$daysofweek=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday/Sunday");
for($i=0;$i<count($daysofweek);$i++)
{
   echo "<div style=\"top:".$top."px;left:".$left."px;position:absolute;color:#808080\">$daysofweek[$i]</div>";
   $left+=125;
}
$day=1; $week=1;
$wday=$first_week_day-1;
$firstweek = true;
$top+=20;
$left=1;
$dayid=1;
while($day <= $lastday)
{
   if($firstweek)
   {
       $daysinprevmonth=GetDaysInMonth($prevmonth,$prevyear);
       for ($i=2; $i<=$first_week_day; $i++) 
       { 
       	  if($i!=6) $class="monthrufri";
	  else $class="saturday";
	  $curday=$daysinprevmonth-($first_week_day-$i);
	  if($i==2)
	  {
	     if($prevmonth<10) $month2="0".$prevmonth;
	     else $month2=$prevmonth;
	     if($curday<10) $day2="0".$curday;
    	     else $day2=$curday;
	     $mondate="$prevyear-$month2-$day2";
	  }
          echo "<div class=\"$class\" onMouseOver=\"this.style.backgroundColor='#B0C4DE';\" onMouseOut=\"this.style.backgroundColor='#e0e0e0';\" onclick=\"fullday".$dayid.".style.visibility='visible';image".$dayid.".style.visibility='visible';\" style=\"top:".$top."px;left:".$left."px;background-color:#E0E0E0;\"><table width=100% cellspacing=0 cellpadding=2><tr align=right><td><font style=\"color:#808080\">$curday</font></td></tr></table></div>";
          $curdate="$prevyear-$prevmonth-$curday";
          $curtime=mktime(0,0,0,$prevmonth,$curday,$prevyear);
          $fulldate=date("l, F j, Y",$curtime);
          $leftfull=$left+100; $leftgif=$leftfull-39; $topgif=$top;
          echo "<div id=\"image".$dayid."\" style=\"position:absolute;z-index:5;top:".$topgif."px;left:".$leftgif."px;visibility:hidden;\"><img src=\"/caldayext.gif\"></div><div class=\"fullday\" id=\"fullday".$dayid."\" style=\"visibility:hidden;top:".$top."px;left:".$leftfull."px;\"><table width=100% cellspacing=0 cellpadding=2 class=eight><tr bgcolor=#A0A0A0><td align=left><b>$fulldate</b>&nbsp;&nbsp;<a href=\"editdate.php?session=$session&date=$curdate\">Edit This Day</a></td><td align=right><img style=\"cursor:hand;cursor:pointer;\" onclick=\"fullday".$dayid.".style.visibility='hidden';image".$dayid.".style.visibility='hidden';\" src=\"/close.gif\"></td></tr><tr align=left><td colspan=2>".PopulateDay($curdate)."</td></tr></table></div>";
          $dayid++;
	  $left+=125;
       }
       if($first_week_day==0)
       {
          for($i=1;$i<=5;$i++)
	  {
	     $curday=$daysinprevmonth-(6-$i);
             if($i==1)          
	     {             
	        if($prevmonth<10) $month2="0".$prevmonth;             
		else $month2=$prevmonth;             
		if($curday<10) $day2="0".$curday;             
		else $day2=$curday;             
		$mondate="$prevyear-$month2-$day2";          
	     }
	     echo "<div class=\"monthrufri\" onMouseOver=\"this.style.backgroundColor='#B0C4DE';\" onMouseOut=\"this.style.backgroundColor='#e0e0e0';\" onclick=\"fullday".$dayid.".style.visibility='visible';image".$dayid.".style.visibility='visible';\" style=\"top:".$top."px;left:".$left."px;background-color:#E0E0E0;\"><table width=100% cellspacing=0 cellpadding=2><tr align=right><td><font style=\"color:#808080\">$curday</font></td></tr></table></div>";
             $curdate="$prevyear-$prevmonth-$curday";          
             $curtime=mktime(0,0,0,$prevmonth,$curday,$prevyear);          
             $fulldate=date("l, F j, Y",$curtime);          
             $leftfull=$left+100; $leftgif=$leftfull-39; $topgif=$top;
             echo "<div id=\"image".$dayid."\" style=\"position:absolute;z-index:5;top:".$topgif."px;left:".$leftgif."px;visibility:hidden;\"><img src=\"/caldayext.gif\"></div><div class=\"fullday\" id=\"fullday".$dayid."\" style=\"visibility:hidden;top:".$top."px;left:".$leftfull."px;\"><table class=eight width=100% cellspacing=0 cellpadding=2><tr bgcolor=#A0A0A0><td align=left><b>$fulldate</b>&nbsp;&nbsp;<a href=\"editdate.php?session=$session&date=$curdate\">Edit This Day</a></td><td align=right><img style=\"cursor:hand;cursor:pointer;\" onclick=\"fullday".$dayid.".style.visibility='hidden';image".$dayid.".style.visibility='hidden';\" src=\"/close.gif\"></td></tr><tr align=left><td colspan=2>".PopulateDay($curdate)."</td></tr></table></div>";          
	     $dayid++;
             $left+=125;
	  }
	  $curday=$daysinprevmonth;
          echo "<div class=\"saturday\" onMouseOver=\"this.style.backgroundColor='#B0C4DE';\" onMouseOut=\"this.style.backgroundColor='#ffffff';\" onclick=\"fullday".$dayid.".style.visibility='visible';image".$dayid.".style.visibility='visible';\" style=\"top:".$top."px;left:".$left."px;\"><table width=100% cellspacing=0 cellpadding=2><tr align=right><td><font style=\"color:#808080\">$curday/1</font></td></tr></table></div>";
          $curdate="$prevyear-$prevmonth-$curday";          
	  $curtime=mktime(0,0,0,$prevmonth,$curday,$prevyear);          
   	  $fulldate=date("l, F j, Y",$curtime);          
	  $leftfull=$left+100; $leftgif=$leftfull-39; $topgif=$top;
	  echo "<div id=\"image".$dayid."\" style=\"position:absolute;z-index:5;top:".$topgif."px;left:".$leftgif."px;visibility:hidden;\"><img src=\"/caldayext.gif\"></div><div class=\"fullday\" id=\"fullday".$dayid."\" style=\"visibility:hidden;top:".$top."px;left:".$leftfull."px;\"><table class=eight width=100% cellspacing=0 cellpadding=2><tr bgcolor=#A0A0A0><td align=left><b>$fulldate</b>&nbsp;&nbsp;<a href=\"editdate.php?session=$session&date=$curdate\">Edit This Day</a></td><td align=right><img style=\"cursor:hand;cursor:pointer;\" onclick=\"fullday".$dayid.".style.visibility='hidden';image".$dayid.".style.visibility='hidden';\" src=\"/close.gif\"></td></tr><tr align=left><td colspan=2>".PopulateDay($curdate)."</td></tr></table></div>";          
	  $dayid++;
	  $left+=125;
	  $week++; $left=1; $top+=225;
	  $wday=0; $day++;
       }
       $firstweek = false;
   }

   if($wday==0) 
   {
      if($month<10) $month2="0".$month;
      else $month2=$month;
      if($day<10) $day2="0".$day;
      else $day2=$day;
      $mondate="$year-$month2-$day2";
   }
   if($wday!=5) 
   {
      if($month<10) $month2="0".$month;
      else $month2=$month;
      if($day<10) $day2="0".$day;
      else $day2=$day;
      $class="monthrufri"; $showday=$day;
      $curdate="$year-$month2-$day2";
      $curtime=mktime(0,0,0,$month2,$day2,$year);
      $fulldate=date("l, F j, Y",$curtime);
   }
   else 
   {
      if($month<10) $month2="0".$month;
      else $month2=$month;
      if($day<10) $day2="0".$day;
      else $day2=$day;
      $satdate="$year-$month2-$day2";
      $class="saturday"; $day2=$day+1; 
      if($day2>$lastday) $day2=1;
      $showday="$day/$day2"; 
      $curdate="$year-$month2-$day";
      $curtime=mktime(0,0,0,$month2,$day,$year);
      $fulldate=date("l, F j, Y",$curtime);
      $day++;
   }
   
   echo "<div class=\"$class\" onMouseOver=\"this.style.backgroundColor='#B0C4DE';\" onMouseOut=\"this.style.backgroundColor='#ffffff';\" onclick=\"fullday".$dayid.".style.visibility='visible';image".$dayid.".style.visibility='visible';\" style=\"cursor:hand;cursor:pointer;top:".$top."px;left:".$left."px;\"><table width=100% cellspacing=0 cellpadding=2><tr align=right><td><font style=\"color:#808080\">$showday</font></td></tr></table></div>";
   $leftfull=$left+100; $leftgif=$leftfull-39; $topgif=$top;
   echo "<div id=\"image".$dayid."\" style=\"position:absolute;z-index:5;top:".$topgif."px;left:".$leftgif."px;visibility:hidden;\"><img src=\"/caldayext.gif\"></div><div class=\"fullday\" id=\"fullday".$dayid."\" style=\"padding:2px;visibility:hidden;top:".$top."px;left:".$leftfull."px;\"><table class=eight width=100% cellspacing=0 cellpadding=2><tr bgcolor=#A0A0A0><td align=left><b>$fulldate</b>&nbsp;&nbsp;<a href=\"editdate.php?session=$session&date=$curdate\">Edit This Day</a></td><td align=right><img style=\"cursor:hand;cursor:pointer;\" onclick=\"fullday".$dayid.".style.visibility='hidden';image".$dayid.".style.visibility='hidden';\" src=\"/close.gif\"></td></tr><tr align=left><td colspan=2>".PopulateDay($curdate)."</td></tr></table></div>";
   $left+=125;

    if($wday==5)
    {
       $top2=$top+17;
       echo PopulateWeek($top2,$mondate,$satdate);
       $week++; $left=1; $top+=225;
    }

    $wday++;
    $wday = $wday % 6;
    $day++; $dayid++;
}
if($wday<6 && $wday>=1)
{
    $daysinnextmonth=GetDaysInMonth($nextmonth,$nextyear);
    for ($i=$wday; $i<=5; $i++)
    {
       if($i!=5) $class="monthrufri";
       else $class="saturday";
       $curday=$i-$wday+1;
       echo "<div class=\"$class\" onMouseOver=\"this.style.backgroundColor='#B0C4DE';\" onMouseOut=\"this.style.backgroundColor='#e0e0e0';\" onclick=\"fullday".$dayid.".style.visibility='visible';image".$dayid.".style.visibility='visible';\" style=\"top:".$top."px;left:".$left."px;background-color:#E0E0E0;\"><table width=100% cellspacing=0 cellpadding=2><tr align=right><td><font style=\"color:#808080\">$curday</font></td></tr></table></div>";
       $leftfull=$left+100; $leftgif=$leftfull-39; $topgif=$top;
       $curdate="$nextyear-$nextmonth-$curday";
       $curtime=mktime(0,0,0,$nextmonth,$curday,$nextyear);
       $fulldate=date("l, F j, Y",$curtime);
       echo "<div id=\"image".$dayid."\" style=\"position:absolute;z-index:5;top:".$topgif."px;left:".$leftgif."px;visibility:hidden;\"><img src=\"/caldayext.gif\"></div><div class=\"fullday\" id=\"fullday".$dayid."\" style=\"visibility:hidden;top:".$top."px;left:".$leftfull."px;\"><table class=eight width=100% cellspacing=0 cellpadding=2><tr bgcolor=#A0A0A0><td align=left><b>$fulldate</b>&nbsp;&nbsp;<a href=\"editdate.php?session=$session&date=$curdate\">Edit This Day</a></td><td align=right><img style=\"cursor:hand;cursor:pointer;\" onclick=\"fullday".$dayid.".style.visibility='hidden';image".$dayid.".style.visibility='hidden';\" src=\"/close.gif\"></td></tr><tr align=left><td colspan=2>".PopulateDay($curdate)."</td></tr></table></div>";
       $left+=125;
       if($i==5)
       {
          $top2=$top+17; $satdate="$nextyear-$nextmonth-$curday";
          echo PopulateWeek($top2,$mondate,$satdate);
       }
       $dayid++;
    }
    $top+=135;
}
else
   $top+=10;
echo "<div style=\"position:absolute;top:".$top."px;left:250px;\"><a href=\"calendar.php?session=$session&month=$prevmonth&year=$prevyear\"><< ".date("M Y",mktime(0,0,0,$prevmonth,1,$prevyear))."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style=\"color:#19204f;font-size:11pt;\"><b>".date("F Y",mktime(0,0,0,$month,1,$year))."</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"calendar.php?session=$session&month=$nextmonth&year=$nextyear\">".date("M Y",mktime(0,0,0,$nextmonth,1,$nextyear))."></a></div>";
echo "</form>";
echo "</div>";

echo $end_html;
?>
