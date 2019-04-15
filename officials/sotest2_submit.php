<?php
require 'variables.php';
require 'functions.php';

//connect to db
$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=GetLevel($session);
$offid=GetOffID($session);
$sport='so';
$sportname=GetSportName($sport);
$testtable=$sport."test2";
$categtable=$sport."test2_categ";
$resultstable=$sport."test2_results";
$answerstable=$sport."test2_answers";
$offtable=$sport."off_hist";

if($hiddensave && $categid!="Jump To...")
{
   header("Location:".$testtable."_frame.php?session=$session&categid=$categid");
   exit();
}
else if($submitanswers=="Submit Test Answers")
{
   header("Location:".$testtable."_submit.php?session=$session&autosubmit=1");
   exit();
}
else if($autosubmit==1)
{
   $datetaken=time();
   $sql0="SELECT * FROM $resultstable WHERE offid='$offid'";
   $result0=mysql_query($sql0);
   if(mysql_num_rows($result0)==0)
      $sql="INSERT INTO $resultstable (offid,datetaken) VALUES ('$offid','$datetaken')";
   else
   {
      $row0=mysql_fetch_array($result0);
      if($row0[datetaken]!='' && $level!=1)
      {
         //get due date of test and then add 1 day to it for date they can see results:
         $sql="SELECT DATE_ADD(duedate,INTERVAL 1 DAY) FROM test2_duedates WHERE test='$sport'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $viewdate=substr($row[0],5,2)."/".substr($row[0],8,2)."/".substr($row[0],0,4);

         echo $init_html;
         echo GetHeader($session);
         echo "<br><br><div class=alert style=\"width:500px;\">";
         echo "<b>Your $sportname Supervised Test has been submitted.</b><br><br>";
         echo "You will be able to view the results of your test online on approximately $viewdate. Log in to your Official's Login on that date and under \"Online Tests,\" you will be able to view and print your results.<br><br>Thank you!</div>";
         echo "<br><br><a href=\"welcome.php?session=$session\">Home</a><br>$end_html";
         exit();
      }
      $sql="UPDATE $resultstable SET datetaken='$datetaken' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);

   //GRADE TEST:
   $sql="SELECT * FROM $answerstable WHERE offid='$offid'";
   $result=mysql_query($sql);
   $missed=""; $score=0; 
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT * FROM $testtable WHERE id='".$row[questionid]."'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row[answer]==$row2[answer] || $row2[answer]=='acceptall')
         $score++;
      else
         $missed.="$row[place], ";
   }
   $missed=substr($missed,0,strlen($missed)-2);
   $sql="UPDATE $resultstable SET correct='$score', missed='$missed' WHERE offid='$offid'";
   $result=mysql_query($sql);

   //UPDATE off_hist TABLE WITH PERCENTAGE
   $sql="SELECT * FROM test2_duedates WHERE test='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $totalques=$row[totalques];
   $percent=number_format((($score/$totalques)*100),2,'.','');
         $curyr=date("Y",$datetaken);
         $curmo=date("m",$datetaken);
         if($curmo<6)
            $curyr--;
         $curyr1=$curyr+1;
         $regyr=$curyr."-".$curyr1;
   $sql="SELECT * FROM $offtable WHERE offid='$offid' AND regyr='$regyr'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)       //INSERT
   {
      $sql2="INSERT INTO ".$sport."off_hist (offid,regyr,suptest) VALUES ('$offid','$regyr','$score')";
   }
   else         //UPDATE
   {
      $sql2="UPDATE ".$sport."off_hist SET suptest='$score' WHERE offid='$offid' AND regyr='$regyr'";
   }
   $result2=mysql_query($sql2);

   //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
   UpdateRank($offid,$sport);

   //get due date of test and then add 1 day to it for date they can see results:
   $sql="SELECT DATE_ADD(duedate,INTERVAL 1 DAY) FROM test2_duedates WHERE test='$sport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $viewdate=substr($row[0],5,2)."/".substr($row[0],8,2)."/".substr($row[0],0,4);
?>
<script language="javascript">
window.top.location.replace("sotest2_submit.php?session=<?php echo $session; ?>&autosubmit=1");
</script>
<?php

   exit();
}

echo $init_html;
echo "<table class=nine width=95%><tr align=center><td><br>";
echo "<form method=post action=\"".$testtable."_submit.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=\"hiddensave\" value=\"0\">";
echo "<table><caption><b>$sportname Supervised Test</b>";
echo "<hr></caption>";

$sql="SELECT * FROM $answerstable WHERE offid='$offid' ORDER BY category,place";
$result=mysql_query($sql);
$totalanswered=0; $ix=0; $curcategid=0;
$answered=array(); $possible=array(); $questotal=mysql_num_rows($result);
while($row=mysql_fetch_array($result))
{  
   if($row[category]!=$curcategid)
   {  
      $curcategid=$row[category];  $ix++; $answered[$ix]=0;
   }  
   if($row[answer]!='')
   {  
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}  

echo "<tr align=left><td align=left>You have answered <b>$totalanswered</b> out of <b>$questotal</b> questions.<br><br>";

   echo "<b>You may now <input type=submit name=submitanswers value=\"Submit Test Answers\"><br></b>";
   echo "<font style=\"color:blue\"><b>(You have NOT completed the test until you have clicked the \"Submit Test Answers\" button.)</b></font><br><br><b>OR</b><br><br>";

echo "<b>Go back and work on your test:&nbsp;</b>";
echo "<select class=small name=categid onchange=\"hiddensave.value='1';submit();\"><option>Jump To...";
$sql="SELECT DISTINCT t1.* FROM $categtable AS t1,$answerstable AS t2 WHERE t1.id=t2.category AND t2.offid='$offid' ORDER BY t1.place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\">$row[category] (".$answered[$row[place]]." of ".$possible[$row[place]]." answered)";
}
echo "</select>";
echo "<br>(Use the dropdown menu to see which questions you have not completed and/or to go back and work on any section.)";
echo "</td></tr></table></form>";
echo "</td></tr></table>";
echo $end_html;
?>
