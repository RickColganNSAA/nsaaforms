<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//get table names
$questions=$sport."test2";
$categories=$sport."test2_categ";
$results=$sport."test2_results";
$answers=$sport."test2_answers";

//get full sport name
for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
      $sportname=$act_long[$i];
}

if($submit=="Remove Checked from VIEW")
{
   for($i=0;$i<count($offids);$i++)
   {
      if($remove[$i]=='x' || $removeall=='x')
      {
         $sql="UPDATE $results SET showscore='n' WHERE offid='$offids[$i]'";
         $result=mysql_query($sql);
         echo mysql_error();
      }
   }
}
if($submit=="Delete Checked from DATABASE")
{
   for($i=0;$i<count($offids);$i++)
   {
      if($delete[$i]=='x')
      {
	 $sql="DELETE FROM $results WHERE offid='$offids[$i]'";
	 $result=mysql_query($sql);
	 //echo "$sql<br>";
      }
   }
}
if($submit=="Rescore $sportname Tests")
{
   //rescore each user's answers 
   $sql="SELECT * FROM $results WHERE datetaken!=''";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $offid=$row[offid];
      $sql2="SELECT * FROM $answers WHERE offid='$offid'";
      $result2=mysql_query($sql2);
      $missed=""; $score=0; $total=mysql_num_rows($result2);
      while($row2=mysql_fetch_array($result2))
      {
          $sql3="SELECT * FROM $questions WHERE id='".$row2[questionid]."'";
          $result3=mysql_query($sql3);
          $row3=mysql_fetch_array($result3);
          if($row2[answer]==$row3[answer] || $row3[answer]=='acceptall')
                  $score++;
          else
                  $missed.="$row2[place], ";
      }
      $missed=substr($missed,0,strlen($missed)-2);
      $sql2="UPDATE $results SET correct='$score', missed='$missed' WHERE offid='$offid'";
      $result2=mysql_query($sql2);

      //update hist table too
      $curyr=date("Y",$row[datetaken]);
      $curmo=date("m",$row[datetaken]);
      if($curmo<6)
	 $curyr--;
      $curyr1=$curyr+1;
      $regyr="$curyr-$curyr1";
      $hist=$sport."off_hist";
      $percent=$score; //number_format($score/$total,0,'.','');
      $sql2="UPDATE $hist SET suptest='$percent' WHERE offid='$offid' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
      UpdateRank($offid,$sport);
   }
}

echo $init_html;
echo GetHeader($session,"test2report");
echo "<br>";
echo "<form method=post action=\"test2report.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Sport</option>";
$sql="SHOW TABLES LIKE '%test2_results'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("test2_results",$row[0]);
   echo "<option value=\"$temp[0]\"";
   if($sport==$temp[0]) echo " selected";
   echo ">".GetSportName($temp[0])."</option>";
}
echo "</select><input type=submit name=go value=\"Go\">";
if($sport && $sport!='')
{
echo "<br><br><a href=\"edittest2.php?session=$session&sport=$sport\">Edit Part 2 $sportname Test Questions/Answers</a><br><br>";
echo "<input type=hidden name=showscore value=$showscore>";
echo "<input type=hidden name=sort value=\"$sort\">";
echo "<input type=submit name=submit value=\"Rescore $sportname Tests\"><br><br>";
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>$sportname PART 2 Online Tests Report:</b><br><font style=\"font-size:8pt\">(Click column header to sort by that field)</font><br>";
if($showscore!='all')
   echo "<a class=small href=\"test2report.php?sport=$sport&session=$session&showscore=all\">View ALL Part 2 $sportname Tests</a>";
else
   echo "<a class=small href=\"test2report.php?sport=$sport&session=$session\">View Part 2 $sportname Tests that have NOT been removed from view ONLY</a>";
echo "<br></caption>";

//get all online test-submitters and show name, score, ones missed:
$sql="SELECT t1.last,t1.first,t1.middle,t2.offid,t2.datetaken,t2.correct,t2.missed FROM officials AS t1, $results AS t2 WHERE t1.id=t2.offid AND t2.datetaken!='' ";
if($showscore!='all')
   $sql.="AND t2.showscore!='n' ";
$sql.="ORDER BY ";
if($sort=="date")
   $sql.="t2.datetaken";
else if($sort=="score")
   $sql.="t2.correct";
else
   $sql.="t1.last,t1.first";
$result=mysql_query($sql);
echo "<tr align=center>";
echo "<td><b>Remove from View</b><br>Check ALL<br><input type=checkbox name=removeall value='x'></td>";
echo "<th class=smaller><a class=small href=\"test2report.php?session=$session&sport=$sport&sort=name\">Name</a></th>";
echo "<th class=smaller><a class=small href=\"test2report.php?session=$session&sport=$sport&sort=date\">Date Taken</a></th>";
if($sport=="di") 
{
   $sql2="SELECT * FROM ditest";
   $result2=mysql_query($sql2);
   $total=mysql_num_rows($result2);
}
else $total=100;
echo "<th class=smaller><a class=small href=\"test2report.php?session=$session&sport=$sport&sort=score\">Score</a><br>(out of $total)</th>";
echo "<th class=smaller>#'s Missed</th>";
echo "<th class=smaller>Delete</th></tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr valign=top align=left>";
   echo "<input type=hidden name=\"offids[$ix]\" value=\"$row[3]\">";
   echo "<td align=center><input type=checkbox name=\"remove[$ix]\" value='x'></td>";
   echo "<td><a class=small target=new href=\"edit_off.php?session=$session&id=$row[3]&header=no\">$row[first] $row[middle] $row[last]</a></td>"; 
   $date=date("m/d/y",$row[datetaken]);
   echo "<td>$date</td>";
   echo "<td align=center>";
   if($sport=='di') $correct=$row[correct]*5;
   else $correct=$row[correct];
   if($correct<65)
      echo "<font style=\"color:red\"><b>";
   echo $row[correct];
   if($correct<65)
      echo "</b></font>";
   echo "</td>";
   echo "<td width=200>$row[missed]</td>";
   echo "<td align=center><input type=checkbox name=\"delete[$ix]\" value='x'></td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";
echo "<br><input type=submit name=submit value=\"Remove Checked from VIEW\">";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Delete Checked from DATABASE\">";
echo "</form>";
}//end if sport given
else
{
   echo "<br><br>[Please select a sport above and click \"Go\".]";
}
echo $end_html;
?>
