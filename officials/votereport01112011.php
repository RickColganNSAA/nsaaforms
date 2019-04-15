<?php
//show reports for officials voting

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$header=GetHeader($session,"vote");
$level=GetLevel($session);
if($level==4) $level=1;

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$school=$school_ch;
$school2=ereg_replace("\'","\'",$school);
$sportname=GetSportName($sport);

echo $init_html;
echo $header;

echo "<br>";
echo "<a href=\"vote.php?sport=$sport&session=$session\" class=small>Return to $sportname Officials' Ballot Admin</a><br><br>";

if($type=="schools")
{
   $table=$sport."_votes";
   if($sport!='wr')	//WR: NO AD VOTING
   {
   echo "<table width=500><caption><b>";
   if($sport=='di') echo "Schools Whose Boys Swimming Coaches Have Voted for ";
   else echo "Schools Whose AD's Have Voted for ";
   echo "$sportname Officials:<hr></b></caption>";
   $sql="SELECT DISTINCT school FROM $table WHERE ad_coach='ad' ORDER BY school";
   if($sport=='di')
      $sql="SELECT DISTINCT school FROM $table WHERE coach='boys' ORDER BY school";
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
   }	//end if NOT Wrestling
   echo "<table width=500><caption><b>";
   if($sport=='di') echo "Schools Whose Girls Swimming Coaches Have Voted for State Diving Judges";
   else echo "Schools Whose Coaches Have Voted for $sportname Officials";
   echo ":<hr></b></caption>";
   $sql="SELECT DISTINCT(school) FROM $table WHERE ad_coach='coach' ORDER BY school";
   if($sport=='di') 
      $sql="SELECT DISTINCT(school) FROM $table WHERE coach='girls' ORDER BY school";
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
$offtable=$sport."off";

echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#a0a0a0 1px solid;\">";
echo "<caption><b>$sportname Officials' Ballot Reports:</b><br>";
echo "<i>";
echo "</i></caption>";
echo "<tr align=center><td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=abc\">Official</a></td><td><b>City</b></td>";
if($sport!='di' && $sport!='wr')
{
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=advote\">AD<br>Votes</a></td>";
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=coachvote\">Coach<br>Votes</a></td>";
}
else if($sport=='di')
{
   echo "<td><b>Registered</b></td>";
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=boysvote\">Boys Coaches<br>Votes</a></td>";
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=girlsvote\">Girls Coaches<br>Votes</a></td>";
}
echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=vote\">Total<br>Votes</a></td>";
if($sport=='wr')
{
   //echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=adrank\">AD<br>Total</a></td>";
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=coachrank\">Total<br>Rank</a></td>";
   //echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=rank\">Overall<br>Total</a></td>";
}
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

//get all officials names and votes out of database
$sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city FROM officials AS t1, $table AS t2 WHERE t1.id=t2.officialid ";
$sql.="ORDER BY t1.last,t1.first";
if($sport=='di')
   $sql="SELECT id,first,last,city,register FROM di_judges ORDER BY last,first";
$result=mysql_query($sql);
$ix=0;
$off=array();
while($row=mysql_fetch_array($result))
{
   $off[id][$ix]=$row[id];
   $off[first][$ix]=$row[first];
   $off[last][$ix]=$row[last];
   $off[city][$ix]=$row[city];
   if($sport=='di') $off[reg][$ix]=$row[register];

   //get votes, by AD, Coach, overall total and by nsaa district
   $total=0;
   for($i=0;$i<count($dists);$i++)
   {
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND district='$dists[$i]'";
      $result2=mysql_query($sql2);
      if($sport=='wr')	//count up points per district
      {
	 $off[$i][$ix]=0;
	 while($row2=mysql_fetch_array($result2))
	 {
            $off[$i][$ix]+=$row2[rank];   
	 }
	 $total+=mysql_num_rows($result2);
      }
      else
      {
         $off[$i][$ix]=mysql_num_rows($result2);
         $total+=$off[$i][$ix];
      }
   }
   //get overall total votes
   $off[total][$ix]=$total;
   if($sport=='di')
   {
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND coach='boys'";
      $result2=mysql_query($sql2);
      $off[boys][$ix]=mysql_num_rows($result2);
   
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND coach='girls'";
      $result2=mysql_query($sql2);
      $off[girls][$ix]=mysql_num_rows($result2);
   }
   else
   {
      //get total ad votes
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND ad_coach='ad'";
      $result2=mysql_query($sql2);
      $off[ad][$ix]=mysql_num_rows($result2);
      //get total coach votes
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND ad_coach='coach'";
      $result2=mysql_query($sql2);
      $off[coach][$ix]=mysql_num_rows($result2);
   }
   if($sport=='wr')
   {
      //get overall avg rank
      $sql2="SELECT rank,ad_coach FROM $table WHERE officialid='$row[id]'";
      $result2=mysql_query($sql2);
      $off[rank][$ix]=0; $off[adrank][$ix]=0; $off[coachrank][$ix]=0;
      while($row2=mysql_fetch_array($result2))
      {
         $off[rank][$ix]+=$row2[0];   
	 if($row2[ad_coach]=='ad') $off[adrank][$ix]+=$row2[0];
	 else if($row2[ad_coach]=='coach') $off[coachrank][$ix]+=$row2[0];
      }
   }

   if($type=="abc" || !$type || $type=="")	//abc order is default order
   {
      echo "<tr align=center><td align=left>".$off[first][$ix]." ".$off[last][$ix]."</td><td align=left>".$off[city][$ix]."</td>";
      if($sport=='di') 
      {
	 echo "<td>".$off[reg][$ix]."</td>";
	 echo "<td>".$off[boys][$ix]."</td><td>".$off[girls][$ix]."</td>";
      }
      else if($sport!='wr')
         echo "<td>".$off[ad][$ix]."</td><td>".$off[coach][$ix]."</td>";
      echo "<td>".$off[total][$ix]."</td>";
      if($sport=='wr')
         echo "<td>".$off[rank][$ix]."</td>";
      for($i=0;$i<count($dists);$i++)
      {
         echo "<td>".$off[$i][$ix]."</td>";
      }
      echo "</tr>";
   }
   $ix++;
}
$numoffs=$ix;

//show in correct sorted order (if not abc)
$temp=array(); $ix=0; 
if($type=="vote")
{
   $sortfield="total";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[total][$i];
      $ix++;
   }
}
else if($type=="advote")
{
   $sortfield="ad";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[ad][$i];
      $ix++;
   }
}
else if($type=="coachvote")
{
   $sortfield="coach";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[coach][$i];
      $ix++;
   }
}
else if($type=="boysvote")
{
   $sortfield="boys";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[boys][$i];
      $ix++;
   }
}
else if($type=="girlsvote")
{
   $sortfield="girls";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[girls][$i];
      $ix++;
   }
} 
else if($type=="rank")
{
   $sortfield="rank";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[rank][$i];
      $ix++;
   }
}
else if($type=="adrank")
{
   $sortfield="adrank";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[adrank][$i];
      $ix++;
   }
}
else if($type=="coachrank")
{
   $sortfield="coachrank";
   for($i=0;$i<$numoffs;$i++)
   {
      $temp[$ix]=$off[coachrank][$i];
      $ix++;
   }
}
sort($temp);
$usedoffs=array();
$uix=0;
for($i=count($temp)-1;$i>=0;$i--)
{
   for($j=0;$j<count($off[id]);$j++)
   {
      if($off[$sortfield][$j]==$temp[$i])
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
	    echo "<tr align=center><td align=left>".$off[first][$j]." ".$off[last][$j]."</td><td align=left>".$off[city][$j]."</td>";
            if($sport=='di')
            {
	       echo "<td align=left>".$off[reg][$j]."</td>";
	       echo "<td>".$off[boys][$j]."</td><td>".$off[girls][$j]."</td>";
	    }
	    else if($sport!='wr')
               echo "<td>".$off[ad][$j]."</td><td>".$off[coach][$j]."</td>";
            echo "<td>".$off[total][$j]."</td>";
            //if($sport!='wr')
               //echo "<td>".$off[adrank][$j]."</td><td>".$off[coachrank][$j]."</td>";
	    if($sport=='wr') echo "<td>".$off[rank][$j]."</td>";
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
	 
echo "</table><br><br>";
echo "<a href=\"vote.php?sport=$sport&session=$session\" class=small>Return to $sportname Officials' Ballot Admin</a
>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\" class=small>Return Home</a>";

echo $end_html;
?>
