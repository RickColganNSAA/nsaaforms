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

$offid=GetJudgeID($session);
$level=GetLevelJ($session);
if($level==1)
   $offid=$givenoffid;

$sport="sp";
$sportname="Speech";

if($submit=="Go" && $categid!="Jump To...")
{
   header("Location:sptest.php?givenoffid=$givenoffid&session=$session&forcecategid=$categid&test=$test");
   exit();
}
else if($submit=="Submit Test Answers")
{
   $datetaken=time();
   $sql="UPDATE sptest_results SET $test='$datetaken',datetaken='$datetaken' WHERE offid='$offid'";
   $result=mysql_query($sql);

   //GRADE TEST:
      //get answers:
   $ans=array();
   $ix=1;
   $sql="SELECT answer FROM sptest ORDER BY place";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ans[$ix]=$row[answer];
      $ix++;
   }
   $sql="SELECT * FROM sptest_results WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) $noanswers=1;	//no questions answered (don't even grade it)
   else
   {
      $noanswers=0;
      $missed="";
      $spscore=0; $ppscore=0;
      for($i=1;$i<=50;$i++)
      {
         $ques="ques".$i;
         if($row[$ques]==$ans[$i] || ($ans[$i]=='acceptall' && $row[$ques]!=''))        //correct answer
            $spscore++;
         else   //wrong answer
         {
            if($row[speech]!='' || $row[combo]!='')
               $missed.=$i.", ";
         }
      }
      for($i=51;$i<=60;$i++)
      {
         $ques="ques".$i;
         if($row[$ques]==$ans[$i] || ($ans[$i]=='acceptall' && $row[$ques]!=''))
            $ppscore++;
         else
         {
            if($row[play]!='' || $row[combo]!='')
               $missed.=$i.", ";
         }
      }
      //update this user's score
      $missed=substr($missed,0,strlen($missed)-2);
      $sql2="UPDATE sptest_results SET spscore='$spscore',ppscore='$ppscore',missed='$missed' WHERE offid='$offid'";
      $result2=mysql_query($sql2);
      /*
      $sql2="SELECT * FROM sptest ";
      $sql2.="ORDER BY place";
      $result2=mysql_query($sql2);
      $missed_sp=""; $missed_pp="";
      $score=0; $spscore=0; $ppscore=0;
      while($row2=mysql_fetch_array($result2))
      {
         $index="ques".$row2[place];
	 if($row2[place]<=50 && ($row[speech]!='' || $row[combo]!=''))	//sp test question
	 {
            if($row2[answer]=='b' || $row2[answer]==$row[$index]) //answer was correct
	       $spscore++;
            else 	//speech test was taken & answer was wrong
	       $missed_sp.="$row2[place], ";
	 }
	 else if($row2[place]>50 && ($row[play]!='' || $row[combo]!=''))	//pp test question
	 {
            if($row2[answer]=='b' || $row2[answer]==$row[$index])	//answer was correct
	       $ppscore++;
            else 	//play test was taken & answer was wrong
	       $missed_pp.="$row2[place], ";
	 } 
      }
      $missed=$missed_sp.$missed_pp;
      $missed=substr($missed,0,strlen($missed)-1);
      $sql="UPDATE sptest_results SET $test='$datetaken', spscore='$spscore', ppscore='$ppscore', missed='$missed' WHERE offid='$offid'";
      $result=mysql_query($sql);
      */

      $attm=array();
      //SendMail("nsaa@nsaahome.org","NSAA","run7soccer@aim.com","Ann Gaffigan","Speech/Play Test Completed","$offid, TEST: $test, SP: $spscore, PP: $ppscore","$offid, TEST: $test, SP: $spscore, PP: $ppscore",$attm);

      $total=$spscore+$ppscore;
      if(($test=="speech" && $spscore<40) || ($test=="play" && $ppscore<8) || ($test=="combo" && $total<48))
      {
         $name=GetJudgeName($offid);
         $sql="SELECT email FROM judges WHERE id='$offid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $email=$row[0];
         $date=date("M d, Y",$datetaken);
         if($test=="speech")
         {
	    $ppscore="N/A"; $total="N/A";
         }
         else if($test=="play")
         {
	    $spscore="N/A"; $total="N/A";
         }
         else
         {
	    $spscore="N/A"; $ppscore="N/A";
         }
         $text="A Judge has failed his/her online test:\r\n\r\n";
         $text.="Name: $name\r\nDate Taken: $date\r\nSpeech Test Score: $spscore\r\nPlay Test Score: $ppscore\r\nCombo Test Score: $total\r\nE-mail: $email\r\n\r\nThank You!"; 
         $html="A Judge has failed his/her online test:<br><br>";
         $html.="Name: $name<br>Date Taken: $date<br>Speech Test Score: $spscore<br>Play Test Score: $ppscore<br>Combo Test Score: $total<br>";
         $html.="E-mail: <a href='mailto:$email'>$email</a><br><br>Thank You!";
         $attm=array();
         SendMail("nsaa@nsaahome.org","NSAA","callaway@nsaahome.org","Cindy Callaway","Failed Speech/Play Test",$text,$html,$attm);
         //SendMail("nsaa@nsaahome.org","NSAA","run7soccer@aim.com","Ann Gaffigan","Failed Speech/Play Test",$text,$html,$attm);
      }

      echo $init_html;

      if($level!=1)
      {
         echo GetHeaderJ($session);

         //get due date of test and then add 3 days to it for date they can see results:
         $sql="SELECT DATE_ADD(duedate,INTERVAL 3 DAY) FROM test_duedates WHERE test='sp'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $viewdate=substr($row[0],5,2)."/".substr($row[0],8,2)."/".substr($row[0],0,4);

         echo "<br><br>";
         echo "<b>Your ";
         if($test=="speech") echo "Speech";
         else if($test=="play") echo "Play Production";
         else echo "Speech & Play Production";
         echo " Rules Examination has been submitted.</b><br><br>";
         echo "Please do NOT mail your test in.  You will be able to view the results of your test online on approximately $viewdate.  Thank you!";
         echo "<br><br><a href=\"jwelcome.php?session=$session\">Home</a><br>$end_html";
      }//end if level is not 1
      else	//NSAA user
      {
?>
<script language="javascript">
window.close();
</script>
<?php
      }
      exit();
   }//end if at least one test question answered
}

echo $init_html;
echo "<table class=nine width=100%><tr align=center><td><br>";
echo "<form method=post action=\"sptest_submit.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=test value=\"$test\">";
echo "<table><caption><b>";
if($test=="speech") echo "Speech";
else if($test=="play") echo "Play Production";
else echo "Speech & Play Production";
echo " Rules Examination - Part I</b><br>";
if($level==1)
   echo "<br>for <font style=\"color:red\"><b>".GetJudgeName($offid)."</b></font>";
echo "<hr></caption>";
$sql="SELECT * FROM sptest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$answered=0;
//also count for each category: how many answered
$counts=array();
for($i=1;$i<=2;$i++)
{
   $counts[$i]=0;
}
$row=mysql_fetch_array($result);
for($i=1;$i<=60;$i++)
{
   $index="ques".$i;
   if($row[$index]!='')
   {
      $answered++;
      if($i<=50) $counts[1]++;
      else $counts[2]++;
   }
}
if($test=="speech")
{
   $answered=$counts[1];
   $max=50;
}
else if($test=="play")
{
   $answered=$counts[2];
   $max=10;
}
else
{
   $max=60;
}
if($noanswers==1)
{
   echo "<tr align=left><td><font style=\"color:red\"><b>You must answer at least 1 question in order to officially submit your test.</b></td></tr>";
}
echo "<tr align=left><td align=left>You have answered <b>$answered</b> out of <b>$max</b> questions.<br><br>";
echo "<b>You may now <input type=submit name=submit value=\"Submit Test Answers\"><br></b>";
echo "<font style=\"color:blue\"><b>(You have NOT completed the test until you have clicked the \"Submit Test Answers\" button.)</b></font><br><br><b>OR</b><br><br>";
echo "<b>Go back and work on your test:&nbsp;</b>";
if($test!="speech" && $test!="play")
{
echo "<select class=small name=categid><option>Jump To...";
$sql="SELECT id,category,place FROM sptest_categ ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[category]=="Speech")
      $categtotal=50;
   else
      $categtotal=10;
   echo "<option value=\"$row[id]\">$row[category] (".$counts[$row[place]]." of $categtotal answered)";
}
echo "</select><input type=submit name=submit value=\"Go\">";
echo "<br>(Use the dropdown menu to see which questions you have not completed and/or to go back and work on any section.)";
}
else
{
   echo "<a class=small href=\"sptest.php?givenoffid=$givenoffid&session=$session&test=$test\">Click Here</a>";
}
echo "</td></tr></table></form>";
echo "<a class=small href=\"jwelcome.php?session=$session\">Return Home</a>";
echo $end_html;

?>
