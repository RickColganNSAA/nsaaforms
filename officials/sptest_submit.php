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
$sportname=GetSportName($sport);
$sql="SELECT * FROM ".$sport."test";
$result=mysql_query($sql);
$totalques=mysql_num_rows($result);

if($submit=="Go" && $placestart!="Jump To...")
{
   header("Location:".$sport."test.php?session=$session&placestart=$placestart");
   exit();
}
else if($submit=="Submit Test Answers")
{
   $datetaken=time();
   $sql="UPDATE ".$sport."test_results SET datetaken='$datetaken' WHERE offid='$offid'";
   $result=mysql_query($sql);

   //GRADE TEST:
   $sql="SELECT * FROM ".$sport."test_results WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);

   $sql2="SELECT * FROM ".$sport."test ORDER BY place";
   $result2=mysql_query($sql2);
   $missed="";
   $score=0;
   while($row2=mysql_fetch_array($result2))
   {
      $index="ques".$row2[place];
      if($row2[answer]==$row[$index] || $row2[answer]=='acceptall')
	 $score++;
      else
	 $missed.="$row2[place], ";
   }
   $missed=substr($missed,0,strlen($missed)-1);
   $sql="UPDATE ".$sport."test_results SET correct='$score',  missed='$missed' WHERE offid='$offid'";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result2);
   $percent=number_format(($score/$total)*100,2,'.','');

   if($percent<80 && $offid!='598')
   {
      $name=GetJudgeName($offid);
      $sql="SELECT email FROM judges WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $email=$row[0];
      $date=date("M d, Y",$datetaken);
      $text="A $sportname Judge has failed his/her online test:\r\n\r\n";
      $text.="Name: $name\r\nDate Taken: $date\r\nScore: $score out of $total ($percent %)\r\nE-mail: $email\r\n\r\nThank You!"; 
      $html="A $sportname Judge has failed his/her online test:<br><br>";
      $html.="Name: $name<br>Date Taken: $date<br>Score: $score out of $total ($percent %)<br>";
      $html.="E-mail: <a href='mailto:$email'>$email</a><br><br>Thank You!";
      $attm=array();
      SendMail("nsaa@nsaahome.org","NSAA","ccallaway@nsaahome.org","Cindy Callaway","Failed $sportname Test",$text,$html,$attm);
   }
   
   if($percent>79 )
   {
      $name=GetJudgeName($offid);
	  $sql_sp="SELECT t1.first,t1.last,t1.email, t1.appid,t1.spmeeting, t2.correct FROM judges AS t1, sptest_results AS t2, spapply As t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t1.appid!=0 AND t1.id=$offid";
	  $result_sp=mysql_query($sql_sp);
	  $row_sp=mysql_fetch_array($result_sp);
	  if ($row_sp['spmeeting']=='x' && $row_sp['spmeeting']>79 )
	  $sp_requirements=1; else $sp_requirements=0;
      $To=$row_sp['email'];
	  $ToName=$row_sp['first'].' '.$row_sp['last'];
      $date=date("M d, Y",$datetaken);
	  $title = 'NSAA Registered Speech Judge';
      $email_text="Hi $ToName\r\n\r\n";
      $email_text.="Congratulations, you have completed the requirements to be an NSAA Registered Speech Judge\r\n\r\nThank You!"; 
      $attm=array();
      if ($sp_requirements==1)
	  SendMail($from,$fromname,$To,$ToName,$title,$email_text,$email_html,$attm);
   }

   //get due date of test and then add 3 days to it for date they can see results:
   $sql="SELECT DATE_ADD(duedate,INTERVAL 3 DAY) FROM test_duedates WHERE test='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $viewdate=substr($row[0],5,2)."/".substr($row[0],8,2)."/".substr($row[0],0,4);
   if(GetLevel($session)!=1)
   {
      echo $init_html;
      echo GetHeaderJ($session);
      echo "<br><br>";
      echo "<b>Your $sportname Rules Examination has been submitted.</b><br><br>";
      echo "Please do NOT mail your test in.  You will be able to view the results of your test online on approximately $viewdate.  Thank you!";
      echo "<br><br><a href=\"jwelcome.php?session=$session\">Home</a><br>$end_html";
   }
   else
   {
      echo $init_html;
      echo "<table width=100% class=nine><tr align=center><td><br><br>";
      echo "<b>".GetJudgeName($offid)."'s $sportname Rules Examination has been submitted.</b><br><br>";
      echo "You can check the score in the $sportname Test Admin.  Make sure to reload the admin screen if you have it open already.<br><br>";
      echo "<a href=\"javascript:window.close();\">Close this Window</a>";
      echo $end_html;
   }
   exit();
}

echo $init_html;
echo "<table width=100% class=nine><tr align=center><td><br>";
echo "<form method=post action=\"".$sport."test_submit.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<table><caption><b>$sportname Rules Examination - Part I</b><br>";
if(GetLevel($session)==1)
   echo "<br>for <font style=\"color:red\"><b>".GetJudgeName($offid)."</b></font>";
echo "<hr></caption>";
//get number answered for each section and total answered
$sql="SELECT * FROM ".$sport."test_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$totalanswered=0;
$answered=array(); $possible=array();
for($i=1;$i<=10;$i++)
{
   $answered[$i]=0;
}
$sql2="SELECT * FROM ".$sport."test ORDER BY place";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   if(($row2[place]%10)==1)     //1, 11, 21, etc
   {
      $ix++;
   }
   $index="ques".$row2[place];
   if($row[$index]!='')
   {
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}
echo "<tr align=left><td align=left>You have answered <b>$totalanswered</b> out of <b>$totalques</b> questions.<br><br>";
echo "<b>You may now <input type=submit name=submit value=\"Submit Test Answers\"><br></b>";
echo "<font style=\"color:blue\"><b>(You have NOT completed the test until you have clicked the \"Submit Test Answers\" button.)</b></font><br><br><b>OR</b><br><br>";
echo "<b>Go back and work on your test:&nbsp;</b>";
echo "<select class=small name=\"placestart\" onchange=\"hiddensave.value='1';submit();\"><option>Jump To...";
for($i=1;$i<=5;$i++)
{
   $start=($i*10)-9;
   $end=$start+9;
   echo "<option value=\"$start\">Questions $start to $end (".$answered[$i]." of ".$possible[$i]." answered)</option>";
}
echo "</select><input type=submit name=submit value=\"Go\">";
echo "<br>(Use the dropdown menu to see which questions you have not completed and/or to go back and work on any section.)";
echo "</td></tr></table></form>";
echo "<a class=small href=\"jwelcome.php?session=$session\">Return Home</a>";
echo $end_html;

?>
