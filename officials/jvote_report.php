<?php
//show reports for state sb officials voting

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$header=GetHeaderJ($session);
$level=GetLevelJ($session);

//verify user
if(!ValidUser($session))
{
   header("Location:jindex.php");
   exit();
}

$school=$school_ch;
$school2=ereg_replace("\'","\'",$school);
if(!$sport) $sport='pp';
if($sport=='sp')
   $sportname="Speech";
else
   $sportname="Play Production";
$sql2="SELECT * FROM ".$sport."test ORDER BY place";
$result2=mysql_query($sql2);
$total=mysql_num_rows($result2);
if($total>0) $needed=.8*$total;
else $needed=40;

echo $init_html;
echo $header;

echo "<br>";
echo "<a href=\"jvote.php?sport=$sport&session=$session\" class=small>Return to $sportname Judges' Ballot Admin</a><br><br>";

if($type=="schools")
{
   echo "<table width=500><caption><b>Schools Whose AD's Have Voted for ";
   $table=$sport."_votes";
   echo "$sportname Judges:<hr></b></caption>";
   $sql="SELECT DISTINCT(school) FROM $table WHERE ad_coach='ad' ORDER BY school";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($ix%3==0) echo "<tr align=left>";
      echo "<td>$row[0]</td>";
      if(($ix+1)%3==0) echo "</tr>";
      $ix++;
   }
   echo "</table><br>";
   echo "<table width=500><caption><b>Schools Whose Coaches Have Voted for $sportname Judges:<hr></b></caption>";
   $sql="SELECT DISTINCT(school) FROM $table WHERE ad_coach='coach' ORDER BY school";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($ix%3==0) echo "<tr align=left>";
      echo "<td>$row[0]</td>";
      if(($ix+1)%3==0) echo "</tr>";
      $ix++;
   }
   echo "</table>";
   echo $end_html;
   exit();
}

$table=$sport."_votes";

echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>$sportname Judges' Ballot Reports:</b><br>";
echo "<i>";
if($ad_coach=="ad") echo "AD Votes Only, ";
else if($ad_coach=="coach") echo "Coaches Votes Only, ";
else echo "All Votes, ";
if($type=="vote") echo "by Total Votes";
else echo "Alphabetical Order";
echo "</i></caption>";
echo "<tr align=center><th class=smaller>Total Votes</th>";
echo "<th class=smaller>Name</th><th class=smaller>City</th>";
$sql="SELECT DISTINCT district FROM $table ORDER BY district";
$result=mysql_query($sql);
$dists=array();
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<th class=smaller>Dist $row[0]</th>";
   $dists[$i]=$row[0];
   $i++;
}
echo "</tr>";
//get all judges names and votes out of database
//$sql="SELECT t1.id,t1.first,t1.last,t1.city FROM officials AS t1, $offtable AS t2 WHERE t1.id=t2.offid AND t2.mailing>=100 ORDER BY t1.last,t1.first";
if($sport=='pp')
   $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2,ppapply AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND (t3.state1='x' OR t3.state2='x') AND t1.payment!='' AND t1.ppmeeting='x' AND t1.play='x' AND t2.correct>=$needed ORDER BY t1.last,t1.first,t1.middle";
else
   $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2,spapply AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND (t3.state1='x' OR t3.state2='x') AND t1.payment!='' AND t1.spmeeting='x' AND t1.speech='x' AND t2.correct>=$needed ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
$ix=0;
$off=array();
while($row=mysql_fetch_array($result))
{
   $off[id][$ix]=$row[id];
   $off[first][$ix]=$row[first];
   $off[last][$ix]=$row[last];
   $off[city][$ix]=$row[city];

   //get votes, total and by nsaa district
   $total=0;
   for($i=0;$i<count($dists);$i++)
   {
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND district='$dists[$i]'";
      if($ad_coach!="both")
	 $sql2.=" AND ad_coach='$ad_coach'";
      $result2=mysql_query($sql2);
      $off[$i][$ix]=mysql_num_rows($result2);
      $total+=$off[$i][$ix];
   }
   $off[total][$ix]=$total;
   if($type=="abc")
   {
      echo "<tr align=left><td>".$off[total][$ix]."</td><td>".$off[first][$ix]." ".$off[last][$ix]."</td><td>".$off[city][$ix]."</td>";
      for($i=0;$i<count($dists);$i++)
      {
         echo "<td>".$off[$i][$ix]."</td>";
      }
      echo "</tr>";
   }
   $ix++;
}
if($type=="vote")
{
   $temp=array();
   $ix=0;
   for($i=0;$i<count($off[total]);$i++)
   {
      $temp[$ix]=$off[total][$i];
      $ix++;
   }
   sort($temp);
   $usedoffs=array();
   $uix=0;
   for($i=count($temp)-1;$i>=0;$i--)
   {
      for($j=0;$j<count($off[id]);$j++)
      {
	 if($off[total][$j]==$temp[$i])
	 {
	    //check if official has already been shown
	    $used=0;
	    for($k=0;$k<count($usedoffs);$k++)
	    {
	       if($off[id][$j]==$usedoffs[$k])
		  $used=1;
	    }
	    if($used==0)
	    {
	       echo "<tr align=left><td>".$off[total][$j]."</td><td>".$off[first][$j]." ".$off[last][$j]."</td><td>".$off[city][$j]."</td>";
	       for($l=0;$l<count($dists);$l++)
	       {
		  echo "<td>".$off[$l][$j]."</td>";
	       }
	       echo "</tr>";
	       $usedoffs[$uix]=$off[id][$j];
	       $uix++;
	    }
	 }
      }
   }
}
	 
echo "</table><br><br>";
echo "<a href=\"jvote.php?sport=$sport&session=$session\" class=small>Return to $sportname Judges' Ballot Admin</a
>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"jwelcome.php?session=$session\" class=small>Return Home</a>";

echo $end_html;
?>
