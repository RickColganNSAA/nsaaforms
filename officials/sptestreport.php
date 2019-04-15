<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

//get table names
$questions="sptest";
$categories="sptest_categ";
$answers="sptest_results";
$sport="sp";
$sportname="Speech & Play Production";

if($submit=="DELETE/RESET Checked")
{
   for($i=0;$i<count($offids);$i++)
   {
      if($remove[$i]=='x')
      {
	 $sql="DELETE FROM $answers WHERE offid='$offids[$i]'";
	 $result=mysql_query($sql);
      }
   }
}
else if($submit=="Rescore $sportname Tests")
{
   //rescore each user's answers with answers in __test table
   //get array of answers
   $ans=array();
   $ix=1;
   $sql="SELECT answer,place FROM $questions ORDER BY category,place";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ans[$ix]=trim($row[answer]);
      $ix++;
   }
   //now go through each submitted test and rescore
   $sql="SELECT * FROM $answers WHERE datetaken!=''";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $missed="";
      $spscore=0; $ppscore=0;
      for($i=1;$i<=50;$i++)
      {
	 $ques="ques".$i;
	 if($row[$ques]==$ans[$i] || ($ans[$i]=='b' && $row[$ques]!=''))	//correct answer
	    $spscore++;
	 else	//wrong answer
	 {
	    if($row[speech]!='' || $row[combo]!='')
	       $missed.=$i.", ";
	 }
      }
      for($i=51;$i<=60;$i++)
      {
	 $ques="ques".$i;
	 if($row[$ques]==$ans[$i] || ($ans[$i]=='b' && $row[$ques]!=''))
	    $ppscore++;
	 else
	 {
	    if($row[play]!='' || $row[combo]!='')
	    {
	       $missed.=$i.", "; //echo "$missed ($row[$ques] $ans[$i])<br>";
   	    }
	 }
      }
      //update this user's score
      $missed=substr($missed,0,strlen($missed)-2);
      $sql2="UPDATE $answers SET spscore='$spscore',ppscore='$ppscore',missed='$missed' WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
   }
}

echo $init_html;
echo GetHeaderJ($session,"sptestreport");
echo "<br>";
echo "<a class=small href=\"edittest.php?session=$session&sport=$sport\">Edit $sportname Test Questions/Answers</a><br><br>";
echo "<form method=post action=\"sptestreport.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=sort value=\"$sort\">";
echo "<input type=submit name=submit value=\"Rescore $sportname Tests\"><br><br>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2><caption><b>$sportname Online Tests Report:</b><br><font style=\"font-size:8pt\">(Click column header to sort by that field)</font><br>";
echo "<br></caption>";

//get all online test-submitters and show name, score, ones missed:
$sql="SELECT t1.last,t1.first,t2.offid,t2.datetaken,t2.spscore,t2.ppscore,t2.missed,t2.speech,t2.play,t2.combo FROM judges AS t1, $answers AS t2 WHERE t1.id=t2.offid AND t2.datetaken!='' ";
$sql.="ORDER BY ";
if($sort=="date")
   $sql.="t2.datetaken";
else if($sort=="sp")
   $sql.="t2.spscore";
else if($sort=="pp")
   $sql.="t2.ppscore";
else if($sort=="score")
   $sql.="t2.spscore,t2.ppscore";
else
   $sql.="t1.last,t1.first";
$result=mysql_query($sql);
echo "<tr align=center>";
echo "<td><b>Delete/Reset</td>";
echo "<th class=smaller><a class=small href=\"sptestreport.php?session=$session&sport=$sport&sort=name\">Name</a></th>";
echo "<th class=smaller><a class=small href=\"sptestreport.php?session=$session&sport=$sport&sort=date\">Date Taken</a></th>";
echo "<th class=smaller><a class=small href=\"sptestreport.php?session=$session&sport=$sport&sort=sp\">Speech<br>Score</th>";
echo "<th class=smaller><a class=small href=\"sptestreport.php?session=$session&sport=$sport&sort=pp\">Play<br>Score</th>";
echo "<th class=smaller><a class=small href=\"sptestreport.php?session=$session&sport=$sport&sort=score\">Total<br>Score</a></th>";
echo "<th class=smaller>#'s Missed</th>";
echo "</tr>";
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr valign=top align=left>";
   echo "<input type=hidden name=\"offids[$ix]\" value=\"$row[2]\">";
   echo "<td align=center><input type=checkbox name=\"remove[$ix]\" value='x'></td>";
   echo "<td><a class=small target=new href=\"edit_judge.php?session=$session&id=$row[2]&header=no\">$row[first] $row[last]</a></td>"; 
   $date=date("M d, Y",$row[datetaken]);
   echo "<td>$date</td>";
   echo "<td align=center>";
   if($row[spscore]<40 && $row[speech]!="")
      echo "<font style=\"color:red\"><b>";
   if($row[speech]!="" || $row[combo]!='')
   {
      $spscore=number_format(($row[spscore]/50)*100,0,'.','');
      //$spscore=$row[spscore];
      echo $spscore."%";
   }
   else 
      echo "N/A";
   if($row[spscore]<40 && $row[speech]!="")
      echo "</b></font>";
   echo "</td>";
   echo "<td align=center>";
   if($row[ppscore]<8 && $row[play]!="")
      echo "<font style=\"color:red\"><b>";
   if($row[play]!="" || $row[combo]!='')
   {
      $ppscore=number_format(($row[ppscore]/10)*100,0,'.','');
      //$ppscore=$row[ppscore];
      echo $ppscore."%";
   }
   else
      echo "N/A";
   if($row[ppscore]<8 && $row[play]!="")
      echo "</b></font>";
   echo "</td>";
   $total=$row[spscore]+$row[ppscore];
   echo "<td align=center>";
   if($total<48 && ($row[combo]!="" || ($row[speech]!="" && $row[play]!="")))
      echo "<font style=\"color:red\"><b>";
   if($row[combo]!="" || ($row[speech]!="" && $row[play]!=""))
   {
      $totalperc=number_format(($total/60)*100,0,'.','');
      echo $totalperc.'%';
   }
   else
   {
      if($row[play]!='')	//play score is total score
      {
	 $ppperc=number_format(($row[ppscore]/10)*100,0,'.','');
	 echo $ppperc."%";
      }
      else if($row[speech]!='')	//speech score is total score
      {
	 $spperc=number_format(($row[spscore]/50)*100,0,'.','');
	 echo $spperc."%";
      }
      else echo "N/A";
   }
   if($total<48)
      echo "</b></font>";
   echo "</td>";
   echo "<td width=200>$row[missed]</td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";
echo "<br><input type=submit name=submit value=\"DELETE/RESET Checked\">";
echo "</form>";
echo $end_html;
?>
