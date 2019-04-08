<?php
/**********************************
calendar.php
Created: 7/9/08
EDIT Calendar DAY
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

if($save)
{
   for($i=0;$i<count($eventid);$i++)
   {
      $startdate=$year1[$i]."-".$month1[$i]."-".$day1[$i];
      $enddate=$year2[$i]."-".$month2[$i]."-".$day2[$i];
      $eventfull[$i]=addslashes($eventfull[$i]);
      $event="";
      for($j=0;$j<4;$j++)
      {
         if(trim($line[$i][$j])!="")
	    $event.=$line[$i][$j]."<br>";
      }
      if($event!='') $event=substr($event,0,strlen($event)-4);
      $event=addslashes($event);
      if($eventid[$i]>0)
      {
         $sql="UPDATE calendar SET startdate='$startdate',enddate='$enddate',event='$event',eventfull='".$eventfull[$i]."',color='".$color[$i]."' WHERE id='".$eventid[$i]."'";
	 $result=mysql_query($sql);
         //echo "$sql<br>";
      }
      else if(trim($eventfull[$i])!='')
      {
         $sql="INSERT INTO calendar (startdate,enddate,event,eventfull,color) VALUES ('$startdate','$enddate','$event','".$eventfull[$i]."','".$color[$i]."')";
         $result=mysql_query($sql);
         //echo "$sql<br>";
      }
   }
   header("Location:editdate.php?session=$session&date=$date");
   exit();
}

echo $header;
echo "<form method=post action=\"editdate.php\">";
echo "<br><input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=date value=\"$date\">";
echo "<table>";
$curdate=split("-",$date);
$curmo=number_format($curdate[1],0,'.','');
echo "<caption><b>NSAA Events for <u>".date("l, F j, Y",mktime(0,0,0,$curdate[1],$curdate[2],$curdate[0]))."</u></b><br><a href=\"calendar.php?session=$session&month=$curmo&year=$curdate[0]\">Return to Month-to-Month Calendar</a></caption>";
echo "<tr align=center><td>";
echo "<table cellspacing=0 cellpadding=4 width=100%>";
echo "<tr align=center><th>Date Range</th><th>Full Event Description</th><th>Abbreviated Event Description</th><th>Preview Abbreviated</th></tr>";
   $date=split("-",$date);
   if(strlen($date[1])<2) $date[1]="0".$date[1];
   if(strlen($date[2])<2) $date[2]="0".$date[2];
   $date=$date[0]."-".$date[1]."-".$date[2];
	//EDITABLE:
   $sql="SELECT * FROM calendar WHERE startdate<='$date' AND enddate>='$date' ORDER BY startdate";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<input type=hidden name=\"eventid[$ix]\" value=\"$row[id]\">";
      if($ix%2==0) $color="#E0E0E0";
      else $color="#ffffff";
      echo "<tr align=left valign=top height=\"135px\" bgcolor=\"$color\">";
      $start=split("-",$row[startdate]);
      echo "<td align=right>Start: <select name=\"month1[$ix]\"><option value=\"\">MM</option>";
      for($i=1;$i<=12;$i++)
      {
	 if($i<10) $m="0".$i;
	 else $m=$i;
	 echo "<option";
	 if($start[1]==$m) echo " selected"; 
	 echo ">$m</option>";
      }
      echo "</select>/<select name=\"day1[$ix]\"><option value=\"\">DD</option>";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;  
         else $d=$i;
         echo "<option";
         if($start[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year1[$ix]\"><option value=\"\">YYYY</option>";
      $year0=$start[0]-1; $year2=$start[0]+1;
      for($i=$year0;$i<=$year2;$i++)
      {
         echo "<option";
	 if($start[0]==$i) echo " selected";
	 echo ">$i</option>";
      }
      echo "</select><br>End: ";
      $end=split("-",$row[enddate]);
      echo "<select name=\"month2[$ix]\"><option value=\"\">MM</option>";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($end[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day2[$ix]\"><option value=\"\">DD</option>";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($end[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year2[$ix]\"><option value=\"\">YYYY</option>";
      $year0=$end[0]-1; $year2=$end[0]+1;
      for($i=$year0;$i<=$year2;$i++)
      {
         echo "<option";
         if($end[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td>";
      echo "<td><textarea rows=3 cols=30 name=\"eventfull[$ix]\">$row[eventfull]</textarea></td>";
      echo "<td>";
      $event=split("<br>",$row[event]);
      for($i=0;$i<count($event);$i++)
      {
	 $line=$i+1;
         echo "Line $line: <input type=text name=\"line[$ix][$i]\" id=\"line".$ix.$i."\" size=25 value=\"".$event[$i]."\" onkeyup=\"document.getElementById('testevent".$ix."').innerHTML=''; if(document.getElementById('line".$ix."0').value) document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."0').value +'</font><br>'; if(document.getElementById('line".$ix."1').value) document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."1').value +'<br></font>'; if(document.getElementById('line".$ix."2').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."2').value +'<br></font>'; if(document.getElementById('line".$ix."3').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."3').value +'<br></font>';\"><br>";
      }
      while($i<4)
      {
	 $line=$i+1;
         echo "Line $line: <input type=text name=\"line[$ix][$i]\" id=\"line".$ix.$i."\" size=25 onkeyup=\"document.getElementById('testevent".$ix."').innerHTML=''; if(document.getElementById('line".$ix."0').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."0').value +'</font><br>'; if(document.getElementById('line".$ix."1').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."1').value +'<br></font>'; if(document.getElementById('line".$ix."2').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."2').value +'<br></font>'; if(document.getElementById('line".$ix."3').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."3').value +'<br></font>';\"><br>";
	 $i++;
      }
      echo "</td>";
      if($row[color]=='') $row[color]='#f8d938';      
      //echo "<td width=\"130px\"><div class=\"saturday\" id=\"testday".$ix."\" name=\"testday".$ix."\"><div id=\"testevent".$ix."\" name=\"testevent".$ix."\" class=caleventlong style=\"text-align:left;width:125px;background-color:$row[color];\"><font size=1>$row[event]</font></div></div></td>";
      $colors=array("#f8d938","#00cc33","#dd3333","#B0C4DE","#00ffff","#ff00ff","#ff8c00");
      echo "<td><table cellspacing=0 cellpadding=0>";
      for($i=0;$i<count($colors);$i++)
      {
	 echo "<tr><td>";
         echo "<table cellspacing=0 cellpadding=1 class=eight><tr><td><input type=radio value=\"".$colors[$i]."\" name=\"color[$ix]\" onclick=\"document.getElementById('testevent".$ix."').style.backgroundColor='".$colors[$i]."';\"";
         if($row[color]==$colors[$i])
            echo " checked";
         echo "></td><td><div style=\"border:#000000 1px solid;width:30px;height:15px;background-color:".$colors[$i]."\">&nbsp;</div></td>";
	 if($i==1) echo "<td>(Used for STATE)</td>";
	 else if($i==0) echo "<td>(DEFAULT)</td>";
	 else if($i==3) echo "<td>(Used for Holidays)</td>";
	 else if($i==2) echo "<td>(Used for DISTRICTS)</td>";
	 else echo "<td>&nbsp;</td>";
	 echo "</tr></table>";
	 echo "</td></tr>";
      }
      echo "</table></td>";
      echo "</tr>"; 
      echo "<tr bgcolor=$color><td align=right><b>PREVIEW:</b></td><td colspan=3>";
      if($row[startdate]==$row[enddate]) 
      {
	 $width=125; $align=left;
      }
      else
      {
         $startsec=mktime(0,0,0,$start[1],$start[2],$start[0]); $endsec=mktime(0,0,0,$end[1],$end[2],$end[0]);
         $diffsec=$endsec-$startsec;
         $diffdays=$diffsec/(60*60*24);
	 $width=(($diffdays-1)*125)+190;
         //if($diffdays>1) $width-=0;
         $align=center;
      }
      echo "<div id=\"testevent".$ix."\" name=\"testevent".$ix."\" style=\"padding:2px;border:#808080 1px solid;text-align:$align;width:".$width."px;background-color:$row[color];\"><font size=1>$row[event]</font></div></td>";
      echo "</td></tr>";
      
      $ix++;
   }
   $curix=$ix;
   //get things in database but not in calendar table
   $cal=GetStaticEvents($date,$date,1);
   $string=""; 
   for($i=0;$i<count($cal[startdate]);$i++)
   {
      $startdate=$cal[startdate][$i]; $enddate=$cal[enddate][$i];
      $start=split("-",$startdate); $end=split("-",$enddate);
      if($ix%2==0) $color="#e0e0e0";
      else $color="#ffffff";
      $string.="<tr align=left bgcolor=\"$color\"><td>";
      if($startdate==$enddate)
         $string.="$start[1]/$start[2]/$start[0]";
      else
    	 $string.="$start[1]/$start[2]/$start[0]-$end[1]/$end[2]/$end[0]";
      $string.="</td><td>".$cal[event][$i]."</td><td colspan=3><div class=alert>This event is not editable; it is pulled directly from the database.</div></td></tr>";
      $ix++;
   }
   if($i==0) $string="";
   echo $string;
   //NEW EVENT
      $ix=$curix;
      echo "<input type=hidden name=\"eventid[$ix]\" value=\"0\">";
      if($ix%2==0) $color="#E0E0E0";
      else $color="#ffffff";
      echo "<tr align=left bgcolor=\"$color\"><td colspan=5><b><br>ADD NEW EVENT:</b></td></tr>";
      echo "<tr align=left valign=top height=\"135px\" bgcolor=\"$color\">";
      $start=split("-",$date);
      echo "<td align=right>Start: <select name=\"month1[$ix]\"><option value=\"\">MM</option>";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($start[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day1[$ix]\"><option value=\"\">DD</option>";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($start[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year1[$ix]\"><option value=\"\">YYYY</option>";
      $year0=$start[0]-1; $year2=$start[0]+1;
      for($i=$year0;$i<=$year2;$i++)
      {
         echo "<option";
         if($start[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select><br>End: ";
      $end=split("-",$date);
      echo "<select name=\"month2[$ix]\"><option value=\"\">MM</option>";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($end[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=\"day2[$ix]\"><option value=\"\">DD</option>";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echo "<option";
         if($end[2]==$d) echo " selected"; 
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year2[$ix]\"><option value=\"\">YYYY</option>";
      $year0=$end[0]-1; $year2=$end[0]+1;
      for($i=$year0;$i<=$year2;$i++)
      {
         echo "<option";
         if($end[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td>";
      echo "<td><textarea rows=3 cols=30 name=\"eventfull[$ix]\"></textarea></td>";
      echo "<td>";
      $i=0;
      while($i<4)
      {
         $line=$i+1;
         echo "Line $line: <input type=text name=\"line[$ix][$i]\" id=\"line".$ix.$i."\" size=25 value='' onkeyup=\"document.getElementById('testevent".$ix."').innerHTML=''; if(document.getElementById('line".$ix."0').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."0').value +'</font><br>'; if(document.getElementById('line".$ix."1').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."1').value +'<br></font>'; if(document.getElementById('line".$ix."2').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."2').value +'<br></font>'; if(document.getElementById('line".$ix."3').value!='') document.getElementById('testevent".$ix."').innerHTML+='<font size=1>'+ document.getElementById('line".$ix."3').value +'<br></font>';\"><br>";
         $i++;
      }
      echo "</td>";
	$rowcolor=$color;
      $color='#f8d938';
      //echo "<td width=\"130px\"><div class=\"saturday\" id=\"testday".$ix."\" name=\"testday".$ix."\"><div id=\"testevent".$ix."\" name=\"testevent".$ix."\" class=caleventlong style=\"text-align:left;width:125px;background-color:$color;\"><font size=1></font></div></div></td>";
      $colors=array("#f8d938","#00cc33","#dd3333","#B0C4DE","#00ffff","#ff00ff","#ff8c00");
      echo "<td><table cellspacing=0 cellpadding=0>";
      for($i=0;$i<count($colors);$i++)
      {
         echo "<tr><td>";
         echo "<table cellspacing=0 cellpadding=1 class=eight><tr><td><input type=radio value=\"".$colors[$i]."\" name=\"color[$ix]\" onclick=\"document.getElementById('testevent".$ix."').style.backgroundColor='".$colors[$i]."';\"";
         if($row[color]==$colors[$i] || ($row[color]=='' && $colors[$i]==$color))
            echo " checked";
         echo "></td><td><div style=\"border:#000000 1px solid;width:30px;height:15px;background-color:".$colors[$i]."\">&nbsp;</div></td>";
         if($i==1) echo "<td>(Used for STATE)</td>";
         else if($i==0) echo "<td>(DEFAULT)</td>";
         else if($i==3) echo "<td>(Used for Holidays)</td>";
         else if($i==2) echo "<td>(Used for DISTRICTS)</td>";
         else echo "<td>&nbsp;</td>";
         echo "</tr></table>";
         echo "</td></tr>";
      }
      echo "</table></td>";
      echo "</tr>";
      echo "<tr valign=top bgcolor=$rowcolor><td align=right><b>PREVIEW:</b></td><td colspan=3>";
      $width=125; $align=left;
      echo "<div id=\"testevent".$ix."\" name=\"testevent".$ix."\" style=\"padding:2px;border:#808080 1px solid;text-align:$align;width:".$width."px;background-color:$color;\"><font size=1></font></div> (The width of this preview will not change until after clicking \"Save Events\".  It may be longer if the date range for this event is more than one day.)</td>";
      echo "</td></tr>";
      $ix++;

   echo "</table>";

echo "</td></tr>";
echo "<tr align=center><td><input type=submit name=\"save\" value=\"Save Events\"></td></tr>";

echo "</table></form>";

echo $end_html;
?>
