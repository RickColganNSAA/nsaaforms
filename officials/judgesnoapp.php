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

if($sport=='pp')
{
   $table="ppapply";
   $sportname="Play Production";
   $other='sp';
   $othername="Speech";
}
else
{
   $table="spapply";
   $sportname="Speech";
   $other='pp';
   $othername="Play";
}

$sql2="SELECT * FROM ".$sport."test ORDER BY place";
$result2=mysql_query($sql2);
$total=mysql_num_rows($result2);
if($total>0) $needed=.8*$total;
else $needed=40;

echo $init_html;
echo GetHeaderJ($session);
echo "<br>";
echo "<a class=small href=\"apptojudge.php?session=$session\">Applications to Judge Admin</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"judgesnoapp.php?session=$session&sport=$other\">View $othername Judges who Haven't Submitted an App</a><br><br>";
echo "<table cellspacing=3 cellpassing=3><caption><b>Registered Judges who have NOT submitted an Application to Judge $sportname:</b><hr></caption>";
$sql="SELECT t1.* FROM judges AS t1 LEFT JOIN $table AS t2 ON t1.id=t2.offid WHERE t2.offid IS NULL ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$ix=0; $emaillist="";
while($row=mysql_fetch_array($result))
{
   //check if registered
   if($sport=='sp')
     $sql2="SELECT t1.email,t2.correct FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t2.offid='$row[id]' AND t2.correct>=$needed AND t1.spmeeting='x' AND t1.payment!=''";
   else
      $sql2="SELECT t1.email,t2.correct FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t2.offid='$row[id]' AND t2.correct>=$needed AND t1.ppmeeting='x' AND t1.payment!=''";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(mysql_num_rows($result2)>0)	//if IS registered
   {
   if($ix%2==0)
      echo "<tr align=left>";
   echo "<td><a class=small target=new href=\"";
   if($sport=='sp') echo "speechapp.php";
   else echo "playapp.php";
   echo "?session=$session&givenoffid=$row[id]\">$row[first] $row[middle] $row[last]</a>";
   echo "</td>";
   echo "<td>SP: $row2[spscore]</td><td>PP: $row2[ppscore]</td><td>$row2[email]</td>";
   $emaillist.=$row2[email].", ";
   if(($ix+1)%2==0)
      echo "</tr>";
   $ix++;
   }
}
echo "</table>";
echo "<br><br>";
if($sport=='sp')
   $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.spmeeting='x' AND t2.correct>=$needed";
else
   $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND t2.correct>=$needed";
$result=mysql_query($sql);
$reg=mysql_num_rows($result);
echo "$ix Results out of $reg total <b><u>Registered</b></u> $sportname Judges (Paid, Meeting, Test)<br><br>";
echo "<textarea rows=20 cols=80 name=emails>$emaillist</textarea><br><br>";
echo "<a class=small href=\"apptojudge.php?session=$session\">Applications to Judge Admin</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"judgesnoapp.php?session=$session&sport=$other\">View $othername Judges who Haven't Submitted an App</a>";
echo $end_html;

?>
