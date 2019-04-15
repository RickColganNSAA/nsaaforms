<?php

require 'functions.php';
require 'variables.php';

   $seednums[sid]=array(0,1,9,5,13,3,11,7,15,1,9,5,13,3,11,7,15);
   $seednums[oppid]=array(0,16,8,12,4,14,6,10,2,16,8,12,4,14,6,10,2);

//get array of schools to choose from ($db_name DB)
mysql_close();
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);
$schools=array(); $i=0;
$sql="SELECT school,sid FROM fbschool WHERE outofstate!='1' AND class='$class' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $schools[$i]=$row[0]; $sids[$i]=$row[1];
   $i++;
}
mysql_close();

//connect to $db_name2 db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$header=GetHeader($session,"contractadmin");

//connect to $db_name db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name, $db);

if($save)	//user clicked Save button
{
   //save bracket info in database
   for($i=0;$i<count($gameids);$i++)
   {
      $time[$i]=addslashes($time[$i]); $site[$i]=addslashes($site[$i]);
      if($school1[$i]!='' && $hostschool[$i]=='1')
	 $hostschool[$i]=$school1[$i];
      else if($school2[$i]!='' && $hostschool[$i]=='2')
	 $hostschool[$i]=$school2[$i];
      else $hostschool[$i]='';
      if($gameids[$i]!='0')
         $sql="UPDATE fbsched SET received='$year[$i]-$month[$i]-$day[$i]',gametime='$time[$i]',gamesite='$site[$i]',sid='$school1[$i]',oppid='$school2[$i]',homeid='$hostschool[$i]',gamenum='$gamenum[$i]' WHERE scoreid='$gameids[$i]'";
      else
         $sql="INSERT INTO fbsched (class,round,received,gametime,gamesite,sid,oppid,homeid,gamenum) VALUES ('$class','$round','$year[$i]-$month[$i]-$day[$i]','$time[$i]','$site[$i]','$school1[$i]','$school2[$i]','$hostschool[$i]','$gamenum[$i]')";
      if(!($gameids[$i]=='0' && ($month[$i]=='00' || $day[$i]=='00')))
      {
         $result=mysql_query($sql);
	 //echo "Game $gamenum[$i] - $sql<br>";
      }
   }
}

echo $init_html;
echo $header;
echo "<br>";
echo "<a class=small href=\"assignfb.php?session=$session\">Return to Football Playoffs Officials Assignments</a>";
if($class && $class!='')
{
   echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"fbbracket.php?class=$class&session=$session\" target=\"_blank\">Preview Class $class Playoff Bracket</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"fbbracket.php?class=$class&officials=1\" target=\"_blank\">Class $class Bracket WITH CREW CHIEFS</a>";
}
echo "<br><br>";
echo "<form method=post action=\"fbbrackets.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<a href=\"fbshowtoad.php?session=$session\" target=\"_blank\">Preview Host School's Report</a><br><br>";
echo "<table cellspacing=0 cellpadding=5 class='nine'><caption><b>Football Playoff Brackets:</b><br>";
echo "Choose Class:&nbsp;";
$classes=array("A","B","C1","C2","D1","D2","D6");
echo "<select name=class onchange=\"submit();\"><option>~</option>";
for($i=0;$i<count($classes);$i++)
{
   echo "<option";
   if($class==$classes[$i]) echo " selected";
   echo ">$classes[$i]</option>";
}
echo "</select>&nbsp;";
if($class=="A" || $class=="B" || $class=="C1" || $class=="C2" || $class=="D6")
{
   $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
}
else
{
   $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
}
echo "Choose a Round:&nbsp;<select name=round onchange=\"submit();\"><option>~</option>";
for($i=0;$i<count($rounds);$i++)
{
   $roundnum=$i+1;
   echo "<option value='$roundnum'";
   if($round==$roundnum) echo " selected";
   echo ">$rounds[$i]</option>";
}
echo "</select><br><br>";
echo "</caption>";

if($class && $class!='~' && $round && $round!='~')
{
   if($class=="A" || $class=="B" || $class=="C1" || $class=="C2" || $class=="D6") { $teamct=16; $rounds=4; }
   else { $teamct=32; $rounds=5; }
   
   echo "<tr align=left><td><b>Game #</b></td><td><b>Date</b></td>";
   echo "<td><b>Time</b></td><td><b>Site</b></td><td><b>School 1</b></td><td><b>School 2</b></td></tr>";
   $gamect=pow(2,($rounds-$round));
   $ix=0;
   for($g=1;$g<=$gamect;$g++)	//FOR EACH GAME IN THIS ROUND:
   {
      $sql="SELECT * FROM fbsched WHERE class='$class' AND round='$round' AND gamenum='$g'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0) $row[scoreid]=0;
      echo "<tr align=left";
      if($ix%2==0) echo " bgcolor='#f0f0f0'";
      echo ">";
      echo "<input type=hidden name=\"gameids[$ix]\" value=\"$row[scoreid]\">";
      echo "<input type=hidden name=\"gamenum[$ix]\" value=\"$g\">";
      echo "<td align=center><b>$g</b></td>";
      $date=split("-",$row[received]);
      $curmo=$date[1]; $curday=$date[2]; $curyear=$date[0];
      echo "<td><select name=\"month[$ix]\"><option value='00'>MM</option>";
      for($i=1;$i<=12;$i++)
      {
           if($i<9) $value="0".$i;
	    else $value=$i;
	    echo "<option value=\"$value\"";
	    if($curmo==$value) echo " selected";
	    echo ">$i</option>";
      }
      echo "</select>/<select name=\"day[$ix]\"><option value='00'>DD</option>";
      for($i=1;$i<=31;$i++)
      {
	    if($i<9) $value="0".$i;
	    else $value=$i;
	    echo "<option value=\"$value\"";
	    if($curday==$value) echo " selected";
	    echo ">$i</option>";
      }
      echo "</select>/<select name=\"year[$ix]\">";
      $thisyr=date("Y");
      $thisyr1=$thisyr+1;
      for($i=$thisyr;$i<=$thisyr1;$i++)
      {
	    echo "<option";
	    if($curyear==$i) echo " selected";
	    echo ">$i</option>";
      }
      echo "</select>";
      echo "</td>";
      echo "<td><input type=text class=tiny size=10 name=\"time[$ix]\" value=\"$row[gametime]\"></td>";
      echo "<td><input type=text size=20 name=\"site[$ix]\" value=\"$row[gamesite]\"></td>";
      echo "<td>";
      if($round==1) echo "<b>#".$seednums[sid][$g]."</b>&nbsp;";
      echo "<select name=\"school1[$ix]\"><option value=''>Choose School</option>";
      for($i=0;$i<count($schools);$i++)
      {
            echo "<option value=\"$sids[$i]\"";
	    if($row[sid]==$sids[$i]) echo " selected";
	    echo ">$schools[$i]</option>";
      }
      echo "</select><br>";
      echo "<input type=radio name=\"hostschool[$ix]\" value='1'";
      if($row[homeid]==$row[sid]) echo " checked";
      echo ">Host School";
      echo "</td>";
      echo "<td>";
      if($round==1) echo "<b>#".$seednums[oppid][$g]."</b>&nbsp;";
      echo "<select name=\"school2[$ix]\"><option value=''>Choose School</option>";
      for($i=0;$i<count($schools);$i++)
      {
	    echo "<option value=\"$sids[$i]\"";
	    if($row[oppid]==$sids[$i]) echo " selected";
	    echo ">$schools[$i]</option>";
      }
      echo "</select><br>";
      echo "<input type=radio name=\"hostschool[$ix]\" value='2'";
      if($row[homeid]==$row[oppid]) echo " checked";
      echo ">Host School";
      echo "</td>";
      echo "</tr>";
      $ix++;
   }	//END FOR EACH GAME IN THIS ROUND
   echo "<tr align=center><td colspan=6>";
   echo "<input type=submit class='fancybutton2' name=save value=\"Save\"></td></tr>";
}

echo "</table><br>";
echo "<a href=\"assignfb.php?session=$session\">Return to Football Playoffs Officials Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"fbbracket.php?class=$class&session=$session\" target=\"_blank\">Preview Class $class Playoff Bracket</a><br>";
echo "</form>";
echo $end_html;
?>
