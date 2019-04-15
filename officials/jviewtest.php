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

if(!$givenoffid || GetLevelJ($session)!=1) $offid=GetJudgeID($session);
else $offid=$givenoffid;

//check that test is at least 3 days past due
$sql="SELECT duedate FROM test_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[0]);
$duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
$now=time();
$days4=4*24*60*60;
if($sport=='ba' || $sport=='so' || $sport=='tr')
   $days4=7*60*60;	//7am the next day
$table=$sport."test_results";
$sql="SELECT * FROM $table WHERE datetaken!='' AND offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$datetaken=$row[datetaken];
$ct=mysql_num_rows($result);
if(GetLevelJ($session)!=1 && ($now<=($duedate+$days4) || $ct==0))	//if not late enough or off did not take this test
{
   //echo $sql;
   header("Location:jwelcome.php?session=$session&ct=$ct&duedate=$duedate");
   exit();
}

$sportname=GetSportName($sport);

echo $init_html;
//echo GetHeaderJ($session);
echo "<table cellspacing=0 cellpadding=0 style=\"width:100%;\"><tr align=center><td>";
echo "<br>";
$test=$sport."test";
$categ=$sport."test_categ";
$results=$sport."test_results";
   $sql2="SELECT * FROM $test";
   $result2=mysql_query($sql2);
   $totalques=mysql_num_rows($result2);
echo "<table width='750px' cellspacing=0 cellpadding=5 class=nine>";
echo "<caption><b>$sportname <u>Open Book</u> Test Results:</b><br>";
echo "You submitted this test on ".date("F d, Y",$datetaken); 
echo "</caption>";
$sql3="SELECT * FROM $results WHERE offid='$offid'";
$result3=mysql_query($sql3);
$row3=mysql_fetch_array($result3);
echo "<tr align=left><td><font style='font-size:12pt;'><b>";
echo "YOUR SCORE: ".number_format(($row3[correct]/$totalques)*100,0,'.','')."%<br></b></font>";
if($row3[correct]<$totalques)
{
   echo "<br><b>YOU MISSED ";
   if($row3[correct]==($totalques-1)) echo "THIS QUESTION: ";
   else echo "THESE QUESTIONS: ";
   if(substr($row3[missed],strlen($row3[missed])-1,1)==",")
      $row3[missed]=substr($row3[missed],0,strlen($row3[missed])-1);
   echo "</b>$row3[missed]";
}
echo "<br><br><b>CORRECT</b> answers are marked with a <img src=\"/images/greencheck.png\" border=0>.<br>";
echo "<b>INCORRECT</b> answers are marked with a <img src=\"/images/redx.png\" border=0>.<br>";
echo "<br>Additionally, questions you answered incorrectly are highlighted in <b>YELLOW</b>.";
echo "</td></tr>";

   $sql2="SELECT * FROM $test ORDER BY place";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $place=$row2[place];
      $index="ques".$place;
      $sql5="SELECT * FROM ".$test."_mchoices WHERE questionid='$row2[id]' ORDER BY orderby";
      $result5=mysql_query($sql5);
      $rightanswer=0;
      while($row5=mysql_fetch_array($result5))
      {
         if($row3[$index]==$row5[choicevalue] && $row2[answer]==$row5[choicevalue])
            $rightanswer=1;
      }
      if($rightanswer==0 || $level==1 || PastDue($duedate,0))
      {
      echo "<tr align=left valign=top";
      if(trim($row2[answer]!='acceptall') && trim($row2[answer])!=trim($row3[$index]))	//incorrect answer
	 echo " bgcolor=\"yellow\"><td>*&nbsp;";
      else
	 echo "><td>&nbsp;&nbsp;";
      echo "<b>$row2[place])</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row2[question]<br><ul style='list-style-type:none;'>";
      //GET DETAILS FROM MCHOICES TABLE
      $sql5="SELECT * FROM ".$test."_mchoices WHERE questionid='$row2[id]' ORDER BY orderby";
      $result5=mysql_query($sql5);
      while($row5=mysql_fetch_array($result5))
      {
         echo "<li style='margin-top:5px;font-size:9pt;'>".strtoupper($row5[choicevalue]).".&nbsp;&nbsp;&nbsp;$row5[choicelabel]";
         if($row2[answer]==$row5[choicevalue]) echo "&nbsp;<img style='margin:0px;' src=\"/images/greencheck.png\" border=0>&nbsp;";
         else if($row3[$index]==$row5[choicevalue]) echo "&nbsp;<img style='margin:0px;' src=\"/images/redx.png\" border=0>&nbsp;";
         if($row3[$index]==$row5[choicevalue]) echo "&nbsp;<b>YOUR ANSWER</b>";
	 echo "</li>";
      }
      if($row3[$index]=="") echo "<li><b>You did NOT answer this question.</b></li>";
      if($row2[answer]=="acceptall") echo "<li><b>ANY of the above answers were considered acceptable.</b></li>";
      echo "</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Reference: </b>$row2[reference]</td></tr>";
      }
   }


echo "</table>";
echo $end_html;
?>
