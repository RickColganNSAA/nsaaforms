<?php
/***********************************
viewtest2.php
Official can view their Part 2 test
after the due date
Created on 9/14/11
by Ann Gaffigan
************************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$offid=GetOffID($session);

//check that test is at least 1 day past due
$sql="SELECT * FROM test2_duedates WHERE test='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$totalques=$row[totalques];
$date=split("-",$row[duedate]);
$duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
$now=time();
$days2=2*24*60*60;	//(midnight day after due date)
$table=$sport."test2_results";
$sql="SELECT * FROM $table WHERE datetaken!='' AND offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$datetaken=$row[datetaken];
$ct=mysql_num_rows($result);
if($now<=($duedate+$days2) || $ct==0)	//if not late enough or off did not take this test
{
   header("Location:welcome.php?session=$session&ct=$ct&duedate=$duedate");
   exit();
}
$sportname=GetSportName($sport);

echo $init_html;
echo GetHeader($session);
echo "<br>";
$test=$sport."test2";
$categ=$sport."test2_categ";
$results=$sport."test2_results";
$answers=$sport."test2_answers";
$mchoices=$sport."test2_mchoices";
$sql="SELECT * FROM $categ ORDER BY place";
$result=mysql_query($sql);
echo "<table width='750px' cellspacing=5 cellpadding=5 class=nine>";
echo "<caption><b>$sportname <u>Part 2</u> Test Results:</b><br>";
echo "You completed this test on ".date("F j, Y",$datetaken); 
echo "</caption>";
$sql3="SELECT * FROM $results WHERE offid='$offid'";
$result3=mysql_query($sql3);
$row3=mysql_fetch_array($result3);
echo "<tr align=left><td><b>";
$sql4="SELECT id FROM $test";
$result4=mysql_query($sql4);
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
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT t1.place,t1.answer AS answer,t2.id,t2.question,t2.reference,t2.answer AS correctanswer FROM $answers AS t1, $test AS t2 WHERE t1.questionid=t2.id AND t1.category='$row[id]' AND t1.offid='$offid' ORDER BY t1.place";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $place=$row2[place];
      $index="ques".$place;

         if(trim($row2[correctanswer])!='acceptall' && trim($row2[answer])!=trim($row2[correctanswer]))  //incorrect answer
            echo "<tr align=left valign=top bgcolor=\"yellow\"><td><br>&nbsp;&nbsp;";
         else 
            echo "<tr align=left valign=top><td><br>&nbsp;&nbsp;";

         echo "<b>$row2[place])</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row2[question]<br><ul style='list-style-type:none;'>";
         //GET DETAILS FROM MCHOICES TABLE
         $sql5="SELECT * FROM $mchoices WHERE questionid='$row2[id]' ORDER BY orderby";
         $result5=mysql_query($sql5);
         while($row5=mysql_fetch_array($result5))
         {
            echo "<li style='margin-top:5px;font-size:9pt;'>".strtoupper($row5[choicevalue]).".&nbsp;&nbsp;&nbsp;$row5[choicelabel]";
            if($row2[correctanswer]==$row5[choicevalue]) echo "&nbsp;<img style='margin:0px;' src=\"/images/greencheck.png\" border=0>&nbsp;";
            else if($row2[answer]==$row5[choicevalue]) echo "&nbsp;<img style='margin:0px;' src=\"/images/redx.png\" border=0>&nbsp;";
            if($row2[answer]==$row5[choicevalue]) echo "&nbsp;<b>YOUR ANSWER</b>";
            echo "</li>";
         }
         if($row2[answer]=="") echo "<li><b>You did NOT answer this question.</b></li>";
         if($row2[answer]=="acceptall") echo "<li><b>ANY of the above answers were considered acceptable.</b></li>";
         echo "</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Reference: </b>$row2[reference]</td></tr>";
   }
}

echo "</table>";
echo "<br><br><a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
