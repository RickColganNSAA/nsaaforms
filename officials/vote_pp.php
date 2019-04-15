<?php

if(!$_REQUEST['nsaa'])  //AD or coach voting: check their login
{
   require '../functions.php';
   require '../variables.php';

   //connect to db
   $db=mysql_connect("$db_host",$db_user,$db_pass);
   mysql_select_db($db_name, $db);
   if(!ValidUser($session))
   {
      header("Location:../index.php?error=1");
      exit();
   }
   $header=GetHeader($session);
   $level=GetLevel($session);
}
else
{
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
   $header=GetHeaderJ($session);
   $level=GetLevelJ($session);
}
if(!$school_ch && $level!=100 && $sample!=1)
   $school_ch=GetSchool($session);
if($level==1 && $sample==1)
   $school_ch="Test's School";
$school2=addslashes($school_ch);

$sql="SELECT nsaadist FROM $db_name.headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$dist=$row[0];

$sql2="SELECT * FROM $db_name2.pptest ORDER BY place";
$result2=mysql_query($sql2);
$total=mysql_num_rows($result2);
if($total>0) $needed=.8*$total;
else $needed=40;

if($submit)
{
   $offlist=""; $count=0;
   for($i=0;$i<count($offid);$i++)
   {
      if($check[$i]=='x')
      {
         $sql="SELECT * FROM $db_name2.pp_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
	 $result=mysql_query($sql);
 	 if(mysql_num_rows($result)==0)
	 {
	    $sql2="INSERT INTO $db_name2.pp_votes (school,ad_coach,district,officialid) VALUES ('$school2','$ad_coach','$dist','$offid[$i]')";
	 }
	 else
	 {
	    $sql2="UPDATE $db_name2.pp_votes SET district='$dist' WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
	 }
	 $result2=mysql_query($sql2);

         $sql="SELECT first,last,city FROM $db_name2.judges WHERE id='$offid[$i]'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $offlist.="$row[first] $row[last] ($row[city])<br>";
         $count++;
      }
      else	//DELETE FROM DATABASE
      {
	 $sql="DELETE FROM $db_name2.pp_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$offid[$i]'";
         $result=mysql_query($sql);
      }
   }
   
      echo $init_html;
      echo $header;
      echo "<br><br><table><tr align=left><td><b>You have voted for the following Play Production Judges:</td></tr>";
      echo "<tr align=left><td>$offlist</td></tr>";
      echo "<tr align=left><td><b>Thank you for voting!<br><br></b>";
      if($level==1)
         echo "<a href=\"jvote.php?sport=pp&session=$session\">Return to Play Production Ballots Admin</a>";
      else echo "<a href=\"../welcome.php?session=$session\">Home</a>";
      echo "</td></tr></table>";
      echo $end_html;
      exit();
  
}

echo $init_html;
if($sample!=1) echo $header;

if($level==2)	//AD
   $ad_coach='ad';
else if($level==3)	//coach
   $ad_coach='coach';
//else ad_coach should be given (chosen on vote.php page)

//see if this person has voted already
$sql="SELECT * FROM $db_name2.pp_votes WHERE school='$school2' AND ad_coach='$ad_coach'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && $level!=1)
{
   echo "<br><br>You have already submitted your ballot for Play Production Judges.<br><br>";
   echo "Thank You!<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
//ELSE
echo "<br><form method=post action=\"vote_pp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=nsaa value=\"$nsaa\">";
echo "<input type=hidden name=ad_coach value=\"$ad_coach\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table><caption><b>";
if($sample==1) echo "Preview of ";
echo "Play Production Judges Ballot:</b>";
echo "<br><i>You may vote for as many judges as you wish.</i><br><br>";
echo "</caption>";
//get judges to be on ballot: attended rules meeting, paid, and passed PP test
$sql="SELECT t1.last,t1.first,t1.city,t1.id FROM $db_name2.judges AS t1,$db_name2.pptest_results AS t2,$db_name2.ppapply AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t1.payment!='' AND t1.ppmeeting='x' AND t2.correct>=$needed AND (t3.state1='x' OR t3.state2='x' OR t3.state3='x') ORDER BY t1.last,t1.first";
//echo $sql;
echo mysql_error();
$result=mysql_query($sql);
echo "<tr align=left valign=top><td>";
$total=mysql_num_rows($result);
$percol=$total/4;
$curcol=0;
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($curcol>=$percol) 
   {
      echo "</td><td>";
      $curcol=0;
   }
   echo "<input type=checkbox name=\"check[$ix]\" value='x'";
   $sql2="SELECT * FROM $db_name2.pp_votes WHERE school='$school2' AND ad_coach='$ad_coach' AND officialid='$row[id]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0) echo " checked";
   echo ">$row[first] $row[last] (".trim($row[city]).")<br><br>";
   echo "<input type=hidden name=\"offid[$ix]\" value=\"$row[id]\">";
   $curcol++; $ix++;
}
echo "</td></tr>";
echo "<tr align=center><td colspan=4><input type=submit name=submit value=\"Submit Ballot\"";
if($sample==1) echo " disabled";
echo "></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;

?>
