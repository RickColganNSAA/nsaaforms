<?php

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sportname=GetActivityName($sport);

//if($class=="A" || $sport=="te_b")	//ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
   $sql0="SELECT DISTINCT t1.sid FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER BY t2.school";
//else
  // $sql0="SELECT DISTINCT t1.sid FROM ".$sport."school AS t1,headers AS t2,eligibility AS t3,".$sport."distresults AS t4 WHERE t1.mainsch=t2.id AND t2.school=t3.school AND t3.id=t4.player1 ORDER BY t1.school";
$result0=mysql_query($sql0);
$divch=array("singles1","singles2","doubles1","doubles2");
$divch2=array("#1 Singles","#2 Singles","#1 Doubles","#2 Doubles");
echo $init_html;
echo "<table cellspacing=2 cellpadding=2>";
echo "<caption><b>NSAA $sportname Class $class Rosters</b></caption>";
$schct=mysql_num_rows($result0);
echo "<tr valign=top align=left><td>";
while($row0=mysql_fetch_array($result0))
{
   echo "<b>".GetSchoolName($row0[sid],$sport,date("Y"))."</b><br>";
   $sql2="SELECT t1.* FROM logins AS t1,headers AS t2,".$sport."school AS t3 WHERE t1.school=t2.school AND t2.id=t3.mainsch AND t1.sport='$sportname' AND t3.sid='$row0[sid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "Coach: $row2[name]<br>";
   for($i=0;$i<count($divch);$i++)
   {
      //if($class=="A" || $sport=='te_b')	//ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
         $sql2="SELECT * FROM ".$sport."state WHERE division='$divch[$i]' AND sid='$row0[sid]'";
      //else
	// $sql2="SELECT t1.player1,t1.player2 FROM ".$sport."distresults AS t1,eligibility AS t2,headers AS t3,".$sport."school AS t4 WHERE t1.player1=t2.id AND t2.school=t3.school AND t3.id=t4.mainsch AND t1.division='$divch[$i]' AND t4.sid='$row0[sid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $name=GetStudentInfo($row2[player1]);
      if(ereg("doubles",$divch[$i]))
      {
         $name.="/".GetStudentInfo($row2[player2]);
      }
      if(trim(ereg_replace("[^a-zA-Z]","",$name))=="") $name="No Entry";
      echo "$divch2[$i]:&nbsp;$name<br>";
   }
   echo "<br><br>";
   $curcol++;
}
echo "</td></tr></table>";
echo $end_html;
?>
