<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/officials/index.php?error=1");
   exit();
}
$offid=GetOffID($session);
echo $init_html;
if($saveview || $saveclose)
{
   $sql="SELECT * FROM opendates WHERE offid='$offid' AND contestdate='$date'";
   $result=mysql_query($sql);
   for($i=0;$i<count($sport);$i++)
   {
      $comments2=addslashes($comments[$i]);
      $field1=$sport[$i]; $field2=$sport[$i]."comments";
      if(mysql_num_rows($result)>0)
         $sql2="UPDATE opendates SET $field1='$check[$i]',$field2='$comments2' WHERE offid='$offid' AND contestdate='$date'";
      else
         $sql2="INSERT INTO opendates (offid,contestdate,$field1,$field2) VALUES ('$offid','$date','$check[$i]','$comments2')";
      $result2=mysql_query($sql2);
      $result=mysql_query($sql);	//(reload query)
   }
   //check to see if at least one sport is checked as available; if not, delete this record
   $sql="SELECT * FROM opendates WHERE offid='$offid' AND contestdate='$date'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0)
   {
      $id=$row[id];
      $avail=0;
      for($i=0;$i<count($activity);$i++)
      {
         if($row[$activity[$i]]=='x') $avail=1;
      }
      if($avail==0)
      { 
	 $sql2="DELETE FROM opendates WHERE offid='$offid' AND contestdate='$date'";
	 $result2=mysql_query($sql2);
      }
   }
   if($saveclose)
   {
      $temp=split("-",$date);
?>
<script language="javascript">
window.opener.location.replace("opendates.php?session=<?php echo $session; ?>&month=<?php echo "$temp[1]-$temp[0]"; ?>");
window.close();
</script>
<?php
      exit();
   }
}
if($date && $date!='')
{
   //get sports official is registered for
   //get sports this official is registered for
   $spreg_abb=array();
   $spreg_long=array();
   $ix=0;
   for($i=0;$i<count($activity);$i++)
   {
      $table=$activity[$i]."off";
      $sql="SELECT * FROM $table WHERE offid='$offid' AND payment!=''";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)     //Official has paid for this sport
      {
         $spreg_abb[$ix]=$activity[$i];
         $spreg_long[$ix]=$act_long[$i];
         $ix++;
      }
   }
   $temp=split("-",$date); $mo=$temp[1];
   echo "<table width=100% border=1 bordercolor=#000000 cellspacing=1 cellpadding=5 height=475><tr valign=top align=center><td><br>";
   echo "<form method=post action=\"opendates.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=date value=\"$date\">";
   echo "<table class=nine width=100%><caption><b><u>Open Date: $temp[1]/$temp[2]/$temp[0]</u></b></caption>";
   if($saveview)
      echo "<tr align=center><td><font style=\"color:red\">Your changes have been saved.</font></td></tr>";
   echo "<tr align=left><td><b><br>I am available on this date for the following sport(s):</b><br><i>(Please check the box next to each sport for which you are available to officiate on $temp[1]/$temp[2]/$temp[0].  Then enter any comments you may have, such as \"Only available in the afternoon\".)</td></tr>";
   $ix=0;
   $sql="SELECT * FROM opendates WHERE offid='$offid' AND contestdate='$date'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   for($i=0;$i<count($spreg_abb);$i++)
   {
      $season=GetSeason($spreg_abb[$i]); 
      if(($mo>=6 && $mo<=11 && $season=="Fall") || (($mo>=11 || $mo<=3) && $season=="Winter") || ($mo>=3 && $mo<=6 && $season=="Spring"))
      {
         echo "<input type=hidden name=\"sport[$ix]\" value=\"$spreg_abb[$i]\">";
         echo "<tr align=left><td><input type=checkbox name=\"check[$ix]\" value=\"x\"";
	 if($row[$spreg_abb[$i]]=='x') echo " checked";
         echo ">&nbsp;$spreg_long[$i]";
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         $commentsfield=$spreg_abb[$i]."comments";
         echo "<textarea cols=50 rows=3 name=\"comments[$ix]\">$row[$commentsfield]</textarea>";
         echo "</td></tr>";
	 $ix++;
      }
   }
   if($ix==0)
      echo "<tr align=center><td><br><br><font style=\"color:red\">You are not currently registered for any sports with contests played in this season.</font></td></tr>";
   echo "<tr align=center><td><input type=submit name=saveview value=\"Save & View\">&nbsp;";
   echo "<input type=submit name=saveclose value=\"Save & Close\"></td></tr>";
   echo "</table>";
   echo "</form>";
   echo $end_html;
   exit();
}
echo GetHeader($session);
echo "<br><form method=post action=\"opendates.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<div style=\"position:relative;z-index:1;width:750;height:1000;\">";
echo "<div style=\"position:absolute;top:10px;left:10px;\"><font style=\"font-size:9pt\"><b>Please Select a Month:&nbsp;</b></font>";
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
echo "</select></div>";
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
$top=35; $left=10; $inc=100;
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
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:$inc;text-align:right;border: black 1px solid;width:99;height:99;font-size:10pt;\" onMouseOver=\"this.style.background='#E0E0E0';\" onMouseOut=\"this.style.background='#FFFFFF';\" onClick=\"window.open('opendates.php?session=$session&date=$link_date','$link_date','width=500,height=500,scrollbars=yes');\">";
   $top2=$top+15;
   echo "<table width=100%><tr align=right><td class=nine><b>$day</b>&nbsp;<br><br></td></tr>";
   $sql="SELECT * FROM opendates WHERE offid='$offid' AND contestdate='$link_date'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $string="";
   if(mysql_num_rows($result)>0)
   { 
      $string.="<tr align=left><td>Available for:<br>";
      for($i=0;$i<count($activity);$i++)
      {
	 if($row[$activity[$i]]=='x')
	    $string.=strtoupper($activity[$i]).", ";
      }
      $string=substr($string,0,strlen($string)-2)."</td></tr>";
   }
   else
      $string="<tr align=center><td><font style=\"color:#A0A0A0\">Not Available</font></td></tr>";
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
echo $end_html;
?>
