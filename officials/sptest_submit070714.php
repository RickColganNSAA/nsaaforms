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
if(GetLevelJ($session)==1)
{
   if($givenoffid) $offid=$givenoffid;
   else
   {
      echo $init_html."<table width=100% class=nine><tr align=center><td><br><br>";
      echo "ERROR: No official specified.";
      echo $end_html;
      exit();
   }
}
else
   $offid=GetJudgeID($session);

$sport="sp";
$sportname=GetSportName($sport);

if($submit=="Go" && $categid!="Jump To...")
{
   header("Location:".$sport."test.php?session=$session&categid=$categid");
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
   $totalques=mysql_num_rows($result2);
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

   //also update _hist table with this year's score
	 //get current school year in yyyy-yyyy format
	 $curyr=date("Y",$datetaken);
	 $curmo=date("m",$datetaken);
	 if($curmo<6)
	    $curyr--;
	 $curyr1=$curyr+1;
	 $regyr=$curyr."-".$curyr1;
   $sql="SELECT * FROM ".$sport."off_hist WHERE offid='$offid' AND regyr='$regyr'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)       //INSERT
   {
      $sql2="INSERT INTO ".$sport."off_hist (offid,regyr,obtest) VALUES ('$offid','$regyr','$score')";
   }
   else         //UPDATE
   {
      $sql2="UPDATE ".$sport."off_hist SET obtest='$score' WHERE offid='$offid' AND regyr='$regyr'";
   }
   $result2=mysql_query($sql2);

   if(($score/$totalques*100)<80 && $offid!='598')
   {
      $percent=number_format(($score/$totalques)*100,2,'.','');
      $name=GetJudgeName($offid);
      $sql="SELECT email FROM judges WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $email=$row[0];
      $date=date("M d, Y",$datetaken);
      $text="A $sportname Judge has failed his/her online test:\r\n\r\n";
      $text.="Name: $name\r\nDate Taken: $date\r\nScore: $score out of $totalques ($percent %)\r\nE-mail: $email\r\n\r\nThank You!"; 
      $html="A $sportname Judge has failed his/her online test:<br><br>";
      $html.="Name: $name<br>Date Taken: $date<br>Score: $score out of $totalques ($percent %)<br>";
      $html.="E-mail: <a href='mailto:$email'>$email</a><br><br>Thank You!";
      $attm=array();
      SendMail("nsaa@nsaahome.org","NSAA","ccallaway@nsaahome.org","Cindy Callaway","Failed Speech/Play Test",$text,$html,$attm);
   }

   //get due date of test and then add 3 days to it for date they can see results:
   $sql="SELECT DATE_ADD(duedate,INTERVAL 3 DAY) FROM test_duedates WHERE test='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $viewdate=substr($row[0],5,2)."/".substr($row[0],8,2)."/".substr($row[0],0,4);
   if(GetLevelJ($session)!=1)
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
if(GetLevelJ($session)==1)
   echo "<br>for <font style=\"color:red\"><b>".GetOffName($offid)."</b></font>";
echo "<hr></caption>";
$sql="SELECT * FROM ".$sport."test_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$totalanswered=0;
$answered=array(); $possible=array();
$sql2="SELECT * FROM ".$sport."test_categ";
$result2=mysql_query($sql2);
$i=1;
while($row2=mysql_fetch_array($result2))
{
   $answered[$i]=0; $possible=array();
   $i++;
}
$sql2="SELECT * FROM ".$sport."test ORDER BY place";
$result2=mysql_query($sql2);
$ix=0; $curcategid=0;
while($row2=mysql_fetch_array($result2))
{
   if($row2[category]!=$curcategid)
   {
      $curcategid=$row2[category];  $ix++;
   }
   $index="ques".$row2[place];
   if($row[$index]!='')
   {
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}
echo "<tr align=left><td align=left>You have answered <b>$totalanswered</b> out of <b>50</b> questions.<br><br>";
echo "<b>You may now <input type=submit name=submit value=\"Submit Test Answers\"><br></b>";
echo "<font style=\"color:blue\"><b>(You have NOT completed the test until you have clicked the \"Submit Test Answers\" button.)</b></font><br><br><b>OR</b><br><br>";
echo "<b>Go back and work on your test:&nbsp;</b>";
echo "<select class=small name=categid><option>Jump To...";
$sql="SELECT id,category,place FROM ".$sport."test_categ ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\">$row[category] (".$answered[$row[place]]." of ".$possible[$row[place]]." answered)";
}
echo "</select><input type=submit name=submit value=\"Go\">";
echo "<br>(Use the dropdown menu to see which questions you have not completed and/or to go back and work on any section.)";
echo "</td></tr></table></form>";
echo "<a class=small href=\"jwelcome.php?session=$session\">Return Home</a>";
echo $end_html;

?>
