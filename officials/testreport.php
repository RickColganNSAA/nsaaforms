<?php
require 'functions.php';
require 'variables.php';

if($sport=='pp' || $sport=='sp')
   header("Location:jtestreport.php?session=$session&sport=$sport");

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if ($_GET[sport]=='sos') $sport='sos';
//get table names
$questions=$sport."test";
$categories=$sport."test_categ";
$answers=$sport."test_results";

//Registration (School) Year
$regyr=GetSchoolYear();	//Gets current school year

//get full sport name
for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
      $sportname=$act_long[$i];
}
if ($_GET[sport]=='sos') $sportname='Soccer';
if ($sport=='sos') $sportname='Soccer';
if($submit=="Remove Checked from VIEW")
{
   for($i=0;$i<count($offids);$i++)
   {
      if($remove[$i]=='x' || $removeall=='x')
      {
         $sql="UPDATE $answers SET showscore='n' WHERE offid='$offids[$i]'";
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
	 $sql="DELETE FROM $answers WHERE offid='$offids[$i]'";
	 $result=mysql_query($sql);
	 //echo "$sql<br>";
      }
	  if($deletee[$i]=='x')
      {
	   $sql="DELETE FROM sostest_results WHERE offid='$offids[$i]'";
	   $result=mysql_query($sql);
      }
   }
}
if ($_GET[sport]=='sos') $sport='sos';
if ($sport=='sos') $sport='sos';
if($submit=="Rescore $sportname Tests")
{
   //rescore each user's answers with answers in __test table
   //get array of answers
   $ans=array();
   $ix=1;
   $sql="SELECT answer FROM $questions ORDER BY place";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ans[$ix]=$row[answer];
      $ix++;
   }
   //now go through each submitted test and rescore
   $sql="SELECT * FROM $answers WHERE datetaken!=''";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $missed="";
      $correct=0;
      for($i=1;$i<=count($ans);$i++)
      {
	 $ques="ques".$i;
	 if($row[$ques]==$ans[$i] || ($ans[$i]=='acceptall' && $row[$ques]!=''))	//correct answer
	 {
	    $correct++;
	 }
	 else	//wrong answer
	 {
	    $missed.=$i.", ";
	 }
      }
      //update this user's score
      $missed=substr($missed,0,strlen($missed)-2);
      $sql2="UPDATE $answers SET correct='$correct',missed='$missed' WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
      //update hist table too
      if ($sport!='sos' )$hist=$sport."off_hist"; else $hist='sooff_hist';
      if ($sport=='sos')$sql2="UPDATE sooff_hist SET sobtest='$correct' WHERE offid='$row[offid]' AND regyr='$regyr'";
      else $sql2="UPDATE $hist SET obtest='$correct' WHERE offid='$row[offid]' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      //echo "$sql2<br>";
      //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
      if ($sport!='sos' )UpdateRank($row[offid],$sport);
   }
   /*if($sport=='so')
   { //echo '<pre>'; print_r($ans); exit;
   $ans=array();
   $ix=1;
   $sql="SELECT answer FROM sostest ORDER BY place";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ans[$ix]=$row[answer];
      $ix++;
   }
   //now go through each submitted test and rescore
   $sql="SELECT * FROM sostest_results WHERE datetaken!=''";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $missed="";
      $correct=0;
      for($i=1;$i<=count($ans);$i++)
      {
	 $ques="ques".$i;
	 if($row[$ques]==$ans[$i] || ($ans[$i]=='acceptall' && $row[$ques]!=''))	//correct answer
	 {
	    $correct++;
	 }
	 else	//wrong answer
	 {
	    $missed.=$i.", ";
	 }
      }
      //update this user's score
      $missed=substr($missed,0,strlen($missed)-2);
      $sql2="UPDATE sostest_results SET correct='$correct',missed='$missed' WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
      //update hist table too
      $hist=$sport."off_hist";
      $sql2="UPDATE $hist SET sobtest='$correct' WHERE offid='$row[offid]' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      //echo "$sql2<br>";
      //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
      UpdateRank($row[offid],$sport);
   }
   }*/

}
   // if($_GET[sport]=='sos')
   // header("Location:testreport.php?session=$session&sport=$_GET[sport]");
echo $init_html;
echo GetHeader($session,"testreport");
if ($_GET[sport]=='sos')$sport='so';
if ($sport=='sos')$sport='so';
echo "<br>";
echo "<form method=post action=\"testreport.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport><option value=''>Choose Sport</option>";
 $sql="SHOW TABLES LIKE '%test_results'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("test_results",$row[0]);
   if($temp[0]!='sp' && $temp[0]!='pp')
   {
      echo "<option value=\"$temp[0]\"";
      if($sport==$temp[0]) echo " selected";
      echo ">".GetSportName($temp[0])."</option>";
   }
}
echo "</select><input type=submit name=go value=\"Go\">";
if ($_GET[sport]=='sos')$sport='sos';
if ($sport=='sos')$sport='sos';
if ($_GET[sport]=='sos')echo "<input type=hidden name=sport value=\"sos\">";
if ($sport=='sos')echo "<input type=hidden name=sport value=\"sos\">";
if($sport && $sport!='')
{
echo "<br><br><a href=\"edittest.php?session=$session&sport=$sport\">Edit $sportname Test Questions/Answers</a><br><br>";
if($sport=='so')echo "<a href=\"edittest.php?session=$session&sport=sos\">Edit $sportname Test Questions/Answers(Spanish)</a><br><br>";
echo "<input type=hidden name=showscore value=$showscore>";
echo "<input type=hidden name=sort value=\"$sort\">";
echo "<input type=submit name=submit value=\"Rescore $sportname Tests\"><br><br>";
if($sport=='so')echo "<a class=small href=\"testreport.php?sport=$sport&session=$session&sport=sos\">View Spanish Test Report</a>";
echo "<table frame='all' rules='all' style='border:#808080 1px solid;' cellspacing=0 cellpadding=5><caption><b>$sportname Online Tests Report:</b><br><font style=\"font-size:8pt\">(Click column header to sort by that field)</font><br>";
if($showscore!='all')
   echo "<a class=small href=\"testreport.php?sport=$sport&session=$session&showscore=all\">View ALL $sportname Tests</a>";
else
   echo "<a class=small href=\"testreport.php?sport=$sport&session=$session\">View $sportname Tests that have NOT been removed from view ONLY</a>";
echo "<br></caption>";

//get all online test-submitters and show name, score, ones missed:
if($sport=='so')
{
$sql="SELECT t1.last,t1.first,t1.middle,t2.offid,t2.datetaken,t2.correct,t2.missed FROM officials AS t1, $answers AS t2 WHERE t1.id=t2.offid AND t2.datetaken!='' ";
//$sql="SELECT t1.last,t1.first,t1.middle,t2.offid,t2.datetaken,t2.correct,t2.missed, t3.datetaken sdatetaken,t3.correct scorrect,t3.missed smissed FROM officials AS t1, $answers AS t2, sostest_results AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND  t2.datetaken!='' ";

if($showscore!='all')
   $sql.="AND t2.showscore!='n' ";
$sql.="ORDER BY ";
if($sort=="date")
   $sql.="t2.datetaken";
else if($sort=="score")
   $sql.="t2.correct";
else
   $sql.="t1.last,t1.first";
}
else
{
$sql="SELECT t1.last,t1.first,t1.middle,t2.offid,t2.datetaken,t2.correct,t2.missed FROM officials AS t1, $answers AS t2 WHERE t1.id=t2.offid AND t2.datetaken!='' ";
if($showscore!='all')
   $sql.="AND t2.showscore!='n' ";
$sql.="ORDER BY ";
if($sort=="date")
   $sql.="t2.datetaken";
else if($sort=="score")
   $sql.="t2.correct";
else
   $sql.="t1.last,t1.first";
 }
 //echo $sql;
$result=mysql_query($sql);
echo "<tr align=center>";
echo "<td><b>Remove from View</b><br>Check ALL<br><input type=checkbox name=removeall value='x'></td>";
echo "<th class=smaller><a class=small href=\"testreport.php?session=$session&sport=$sport&sort=name\">Name</a></th>";
echo "<th class=smaller><a class=small href=\"testreport.php?session=$session&sport=$sport&sort=date\">Date Taken</a></th>";
if($sport=="di") 
{
   $sql2="SELECT * FROM ditest";
   $result2=mysql_query($sql2);
   $total=mysql_num_rows($result2);
}
else $total=100;
echo "<th class=smaller><a class=small href=\"testreport.php?session=$session&sport=$sport&sort=score\">Score</a><br>(out of $total)</th>";
echo "<th class=smaller>#'s Missed</th>";
echo "<th class=smaller>Attempts</th>";
echo "<th class=smaller>Edit Test</th>";
if($sport=='so')
echo "<th class=smaller>Delete</th>";
else
echo "<th class=smaller>Delete</th></tr>";
/*if($sport=='so')
{
echo "<th class=smaller><a class=small href=\"testreport.php?session=$session&sport=$sport&sort=date\">Date Taken <br>(Spanish)</a></th>";
echo "<th class=smaller><a class=small href=\"testreport.php?session=$session&sport=$sport&sort=score\">Score</a><br>(out of $total)<br>(Spanish)</th>";
echo "<th class=smaller>#'s Missed (Spanish Test)</th>";
echo "<th class=smaller>Attempts <br>(Spanish)</th>";
echo "<th class=smaller>Edit Test<br>(Spanish)</th>";
echo "<th class=smaller>Delete</th></tr>";
}*/
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr valign=top align=left>";
   echo "<input type=hidden name=\"offids[$ix]\" value=\"$row[3]\">";
   echo "<td align=center><input type=checkbox name=\"remove[$ix]\" value='x'></td>";
   echo "<td><a class=small target=new href=\"edit_off.php?session=$session&id=$row[3]&header=no\">$row[first] $row[middle] $row[last]</a></td>"; 
   $date=date("M d, Y",$row[datetaken]);
   echo "<td>$date</td>";
   echo "<td align=center>";
   if($sport=='di') $correct=$row[correct]*5;
   else $correct=$row[correct];
   if($correct<80)
      echo "<font style=\"color:red\"><b>";
   echo $row[correct];
   if($correct<80)
      echo "</b></font>";
   echo "</td>";
   echo "<td width=200>$row[missed]</td>";
	//# of Attempts
   if($sport=='sos')$sql2="SELECT sobtestattempts FROM ".$sport."off_hist WHERE regyr='$regyr' AND offid='$row[offid]'";
   else $sql2="SELECT obtestattempts FROM ".$sport."off_hist WHERE regyr='$regyr' AND offid='$row[offid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td>$row2[0]</td>";
   echo "<td><a class=small target=\"_blank\" href=\"".$sport."test.php?givenoffid=$row[3]&session=$session\">Edit Test</a></td>";
   echo "<td align=center><input type=checkbox name=\"delete[$ix]\" value='x'></td>";
   /*if($sport=='so')
   {
   if(date("Y",$row[sdatetaken])=='1969') $date=''; else $date=date("M d, Y",$row[sdatetaken]);
   echo "<td>$date</td>";
   echo "<td align=center>";

   if($row[scorrect]<80)
      echo "<font style=\"color:red\"><b>";
   echo $row[scorrect];
   if($scorrect<80)
      echo "</b></font>";
   echo "</td>";
   echo "<td width=200>$row[smissed]</td>";
	//# of Attempts
   $sql2="SELECT sobtestattempts FROM ".$sport."off_hist WHERE regyr='$regyr' AND offid='$row[offid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td>$row2[0]</td>";
   echo "<td><a class=small target=\"_blank\" href=\"sostest.php?givenoffid=$row[3]&session=$session\">Edit Test </a></td>";
   echo "<td align=center><input type=checkbox name=\"deletee[$ix]\" value='x'></td>";
   }*/
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
