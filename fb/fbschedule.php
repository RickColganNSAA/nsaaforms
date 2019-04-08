<?php
//fbschedule.php:
//	Football schedule entry used by NSAA office.

require '../functions.php';
require '../variables.php';

$db = mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}

//$curryear = selected season year
if(!$curryear) $curryear = date("Y");
$curryear1 = $curryear+1;
$curryear2 = $curryear+2;

//get school info
$school=GetSchool($session);
$school2=addslashes($school);
$sql="SELECT sid FROM fbschool WHERE (school LIKE '$school2/%' OR school LIKE '%/$school2/%' OR school LIKE '%/$school2' OR school='$school2')";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sid=$row[0];

$sql="SELECT * FROM fbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row[0];
$school=$row[1];
$district=$row[2];
if($class=="A" || $class=="B") $maxgames=9;
else $maxgames=8;

echo $init_html;
echo GetHeader($session);

//if information submitted, save it to db:
if($submit=="Save Changes")
{
   for($i=0;$i<=$maxgames;$i++)
   {
      if($scoreid[$curryear][$i]!='' && $scoreid[$curryear][$i]!='0')
         $thisyr=1;	//date entered for $curryear for this week
      else $thisyr=0;
      if($scoreid[$curryear1][$i]!='' && $scoreid[$curryear1][$i]!='0')
         $nextyr=1;	//date entered for $curryear1 for this week
      else $nextyr=0;

      //THIS YEAR
      if($thisyr==1)
      {
         if($year[$curryear][$i]=="0000" || $year[$curryear][$i]=="") $thisyear=$curryear;
         else $thisyear=$year[$curryear][$i];
         if($month[$curryear][$i]=="") $thismonth="00";
         else $thismonth=$month[$curryear][$i];
         if($day[$curryear][$i]=="") $thisday="00";
         else $thisday=$day[$curryear][$i];
         $rec1="$thisyear-$thismonth-$thisday";
         if(!ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$rec1) || $thismonth>12 || $thisday>31)
            $rec1="$curryear-00-00";
         if($rec1=="--") $rec1="$curryear-00-00";
         if($rec1=="$curryear--") $rec1="$curryear-00-00";

         $sql="UPDATE fbsched SET received='$rec1' WHERE scoreid='".$scoreid[$curryear][$i]."'";
         $result=mysql_query($sql);
         $newscoreid1=$scoreid[$curryear][$i];

         //add week and received date to fbweeks table
         $sql="SELECT * FROM fbweeks WHERE scoreid='$newscoreid1'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
            $sql2="INSERT INTO fbweeks (received,week,scoreid) VALUES ('$rec1','".$seasonweek[$curryear][$i]."
','$newscoreid1')";
            $result2=mysql_query($sql2);
         }
         else //if only scoreid is in db, update it with rec date if possible
         {
            $sql2="UPDATE fbweeks SET received='$rec1',week='".$seasonweek[$curryear][$i]."' WHERE scoreid='$n
ewscoreid1'";
            $result2=mysql_query($sql2);
         }
      }
      //NEXT YEAR
      if($nextyr==1)
      {
         if($year[$curryear1][$i]=="0000" || $year[$curryear1][$i]=="") $thisyear=$curryear1;
         else $thisyear=$year[$curryear1][$i];
         if($month[$curryear1][$i]=="") $thismonth="00";
         else $thismonth=$month[$curryear1][$i];
         if($day[$curryear1][$i]=="") $thisday="00";
         else $thisday=$day[$curryear1][$i];
         $rec2="$thisyear-$thismonth-$thisday";
         if(!ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$rec2) || $thismonth>12 || $thisday>31)
            $rec2="$curryear1-00-00";
         if($rec2=="--") $rec2="$curryear1-00-00";
         if($rec2=="$curryear1--") $rec2="$curryear1-00-00";

         $sql="UPDATE fbsched SET received='$rec2' WHERE scoreid='".$scoreid[$curryear1][$i]."'";
         $result=mysql_query($sql);
         $newscoreid2=$scoreid[$curryear][$i];

         $sql="SELECT * FROM fbweeks WHERE scored='$newscoreid2'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
            $sql2="INSERT INTO fbweeks (received,week,scoreid) VALUES ('$rec2','".$seasonweek[$curryear1][$i].
"','$newscoreid2')";
            $result2=mysql_query($sql2);
         }
         else
         {
            $sql2="UPDATE fbweeks SET received='$rec2',week='".$seasonweek[$curryear1][$i]."' WHERE scoreid='$
newscoreid1'";
            $result2=mysql_query($sql2);
         }
      }
   }
}

echo "<form method=post action=\"fbschedule.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sid value=$sid>";
echo "<input type=hidden name=curryear value=$curryear>";
echo "<br>";
echo "<table cellspacing=4 cellpadding=4><tr align=center>";
for($y=$curryear;$y<=$curryear1;$y++)
{
   $next=$y+1;
   //get games already entered for this season, put in $entered array
   $sql="SELECT * FROM fbsched WHERE (sid='$sid' OR oppid='$sid') AND (received='$y-00-00' OR (received>='$y-00-00' AND received<'$next-00-00')) ORDER BY received";
   $result=mysql_query($sql);
   $entered=array();
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($row[sid]==$sid) 
      {
         $entered[$ix][opp]=$row[1];
         $entered[$ix][oppid]=$row[4];
         $entered[$ix][homegame]=$row[5];
      }
      else	//school is the opponent
      {
         $entered[$ix][oppid]=$row[0];
         $sql2="SELECT school FROM fbschool WHERE sid='$row[0]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $entered[$ix][opp]=$row2[0];
         if($row[5]==1) $entered[$ix][homegame]=0;
         else if($row[5]==2) $entered[$ix][homegame]=2;
         else $entered[$ix][homegame]=1;
      }
      $entered[$ix][received]=$row[2];
         $date=split("-",$row[2]);
         $entered[$ix][year]=$date[0];
         $entered[$ix][month]=$date[1];
         $entered[$ix][day]=$date[2];
      $entered[$ix][scoreid]=$row[3];
      $sql2="SELECT week FROM fbweeks WHERE scoreid='$row[3]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)>0)	//entry found in fbweeks
         $entered[$ix][week]=$row2[0];
      else	//no entry found
         $entered[$ix][week]=$ix+1;
      $curweek=$entered[$ix][week];
      $ix++;
   }

   //check if it is 9am on Feb 3
   $showdate=mktime(9,0,0,2,3,2006);
   $curryear=date("Y"); $curryear1=$curryear+1;
   $sql2="SELECT duedate FROM misc_duedates WHERE sport='fbsched'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $end=split("-",$row2[0]);
   $enddate=mktime(0,0,0,$end[1],$end[2],$end[0]);
   $now=time();
   if($now>=$showdate && $now<=$enddate)
      $edit=1;
   else
      $edit=0;

   echo "<td>";
   echo "<table cellspacing=1 cellpadding=2 border=1 bordercolor=#000000>";
   echo "<caption><b>$school $y Football Schedule:</b></caption>";
   echo "<tr align=center>";
   //echo "<th>Standardized<br>Calendar Week</th>";
   echo "<td><b>Week of<br>Season</b></td>";
   echo "<td><b>Date</b></td>";
   echo "<td><b>H/A</b></td>";
   echo "<td><b>Opponent</b></td></tr>";
   if($class=="A" || $class=="B") $maxgames=9;
   else $maxgames=8;
   for($i=0;$i<=$maxgames;$i++)
   {
      $copy=0;
      if($i!=0)
      {
         $calweek=$i+8; $seasweek=$i;
      }
      else
      {
         $calweek="Early"; $seasweek=0;
      }
      //check if this week is full already
      $full=-1;
      for($j=0;$j<count($entered);$j++)
      {
         if($entered[$j][week]==$seasweek) 
         {
	    $full=$j;
         }
      }
      if($full!=-1)	//there is a game entered for this week
      {
         $curscoreid=$entered[$full][scoreid];
         echo "<input type=hidden name=\"scoreid[$y][$i]\" value=$curscoreid>";
      }
      echo "<tr align=center>";
      //echo "<th>$calweek</th>";
      $sql2="SELECT week FROM fbweeks WHERE scoreid='$curscoreid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<th>$seasweek</th>";
      echo "<input type=hidden name=\"seasonweek[$y][$i]\" value=$seasweek>";
      if($full!=-1 && $edit==1)
      {
         echo "<td><input type=text name=\"month[$y][$i]\" maxlength=2 size=2";
         $mo=$entered[$full][month];
         if($mo!="00") echo " value=\"$mo\"";
         echo ">/";
         echo "<input type=text name=\"day[$y][$i]\" maxlength=2 size=2";
         $d=$entered[$full][day];
         if($d!="00") echo " value=$d";
         echo ">/";
         echo "<input readOnly=true type=text name=\"year[$y][$i]\" maxlength=4 size=4";
         echo " value=$y";
         echo "></td>";
      }//end if game entered for this week
      else if($full!=-1 && $edit==0)
      {
	 $mo=$entered[$full][month];
	 $d=$entered[$full][day];
         echo "<td>$mo/$d/$y</td>";
      }
      else echo "<td>&nbsp;</td>";
      echo "<td>";
      if($full!=-1 && $entered[$full][homegame]=="1") echo "H";
      else if($full!=-1 && $entered[$full][homegame]=="0") echo "A";
      else echo "&nbsp;";
      echo "</td>";
      echo "<td align=left>";
      if($entered[$full][oppid])
      {
         $sql2="SELECT school FROM fbschool WHERE sid='".$entered[$full][oppid]."'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         echo $row2[0];
      }
      else echo "&nbsp;";
      echo "</td></tr>";
   }
   echo "</table>";
   echo "</td>";
}//end of for each year
echo "</tr></table>";
echo "<br><input type=submit name=submit value=\"Save Changes\">";
echo "</form>";

echo $end_html;
?>
