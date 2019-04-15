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
   echo "<table class='none' width='500'><caption><b>";
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
   echo "<table class='none' width='500'><caption><b>";
   if($sport=='di') echo "Schools Whose Girls Swimming Coaches Have Voted for State Diving Judges";
   else if($sport!='so')
     echo "Schools Whose Coaches Have Voted for $sportname Officials";
   if($sport!='so')
   {
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
   echo "</table><br>";
   }
   else
   {
   echo "<table class='none' width='500'><caption><b>";
   echo "Schools Whose Boys Coaches Have Voted for $sportname Officials";
   echo ":<hr></b></caption>";
   $sql="SELECT DISTINCT(school) FROM $table WHERE ad_coach='bcoach' ORDER BY school";
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
   echo "<table class='none' width='500'><caption><b>";
   echo "Schools Whose Girls Coaches Have Voted for $sportname Officials";
   echo ":<hr></b></caption>";
   $sql="SELECT DISTINCT(school) FROM $table WHERE ad_coach='gcoach' ORDER BY school";
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
   }
   echo $end_html;
   exit();
}

$table=$sport."_votes";
$offtable=$sport."off";

echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#a0a0a0 1px solid;\" class='nine'>";
echo "<caption><b>$sportname Officials' Ballot Reports:</b><br>";
echo "<div class=alert id='exportdiv'></div><br>";	//ONCE CSV FILE IS WRITTEN, LINK WILL BE PUT IN THIS DIV
echo "</caption>";

//Column Headers for CSV file and to show on screen
$csv="\"First\",\"Last\",\"City\",";
if($sport=='di')
   $csv.="\"Registered\",\"Boys Coaches Votes\",\"Girls Coaches Votes\",";
else if($sport=='so')
   $csv.="\"AD Votes\",\"Boys Coaches Votes\",\"Girls Coaches Votes\",";
else if($sport!='wr')
   $csv.="\"AD Votes\",\"Coaches Votes\",";
$csv.="\"Total Votes\",";
if($sport=='wr' || preg_match("/bb/",$sport))
   $csv.="\"Total Rank\",";
echo "<tr align=center><td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=abc\">Official</a></td><td><b>City</b></td>";
if($sport!='di' && $sport!='wr')
{
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=advote\">AD<br>Votes</a></td>";
   if($sport!='so')
      echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=coachvote\">Coach<br>Votes</a></td>";
   if($sport=='so')
   {
      echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=boysvote\">Boys Coach<br>Votes</a></td>";
      echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=girlsvote\">Girls Coach<br>Votes</a></td>";
   }
}
else if($sport=='di')
{
   echo "<td><b>Registered</b></td>";
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=boysvote\">Boys Coaches<br>Votes</a></td>";
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=girlsvote\">Girls Coaches<br>Votes</a></td>";
}
echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=vote\">Total<br>Votes</a></td>";
if($sport=='wr' || preg_match("/bb/",$sport))
{
   echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport&type=rank\">Total<br>Rank</a></td>";
}
$sql="SELECT DISTINCT district FROM $table ORDER BY district";
$result=mysql_query($sql);
$distct=mysql_num_rows($result);
$dists=array();
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<th class=smaller>Dist $row[0]</th>";
   $dists[$i]=$row[0];
   $csv.="\"District $row[0]\",";
   $i++;
}
$csv.="\r\n";
$max=10;
while($i<10)
{
   $dists[$i]=0;
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
   else $off[reg][$ix]="";

   //get votes, by AD, Coach, overall total and by nsaa district
   $total=0;
   for($i=0;$i<count($dists);$i++)
   {
      $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND district='$dists[$i]'";
      $result2=mysql_query($sql2);
      if($sport=='wr' || preg_match("/bb/",$sport))	//count up points per district
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

      $off[ad][$ix]=""; $off[coach][$ix]="";
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
      if($sport=='so')
      {
         $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND ad_coach='bcoach'";
         $result2=mysql_query($sql2);
         $off[boys][$ix]=mysql_num_rows($result2);
         $sql2="SELECT * FROM $table WHERE officialid='$row[id]' AND ad_coach='gcoach'";
         $result2=mysql_query($sql2);
         $off[girls][$ix]=mysql_num_rows($result2);
      }
      else
      {
         $off[boys][$ix]=""; $off[girls][$ix]="";
      }
   }
   if($sport=='wr' || preg_match("/bb/",$sport))
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
   else
   {
      $off[rank][$ix]=''; $off[adrank][$ix]=''; $off[coachrank][$ix]='';
   }
   $ix++;
}
$numoffs=$ix;

if($type=="vote")
{
   array_multisort($off[total],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[rank],$off[adrank],$off[coachrank],$off[reg],$off[boys],$off[girls],$off[ad],$off[coach]);
}
else if($type=='advote')
{
   array_multisort($off[ad],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[rank],$off[adrank],$off[coachrank],$off[reg],$off[boys],$off[girls],$off[total],$off[coach]);
}
else if($type=='coachvote')
{
   array_multisort($off[coach],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[rank],$off[adrank],$off[coachrank],$off[reg],$off[boys],$off[girls],$off[ad],$off[total]);
}
else if($type=="boysvote")
{
   array_multisort($off[boys],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[rank],$off[adrank],$off[coachrank],$off[reg],$off[total],$off[girls],$off[ad],$off[coach]);
}
else if($type=="girlsvote")
{
   array_multisort($off[girls],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[rank],$off[adrank],$off[coachrank],$off[reg],$off[boys],$off[total],$off[ad],$off[coach]);
}
else if($type=="rank")
{
   array_multisort($off[rank],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[total],$off[adrank],$off[coachrank],$off[reg],$off[boys],$off[girls],$off[ad],$off[coach]);
}
else if($type=="adrank")
{
   array_multisort($off[adrank],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[total],$off[rank],$off[coachrank],$off[reg],$off[boys],$off[girls],$off[ad],$off[coach]);
}
else if($type=="coachrank")
{
   array_multisort($off[coachrank],SORT_DESC,SORT_NUMERIC,$off[last],SORT_ASC,$off[first],SORT_ASC,$off[city],$off[0],$off[1],$off[2],$off[3],$off[4],$off[5],$off[6],$off[7],$off[8],$off[9],$off[total],$off[adrank],$off[rank],$off[reg],$off[boys],$off[girls],$off[ad],$off[coach]);
}

for($ix=0;$ix<$numoffs;$ix++)
{
      echo "<tr align=center><td align=left>".$off[first][$ix]." ".$off[last][$ix]."</td><td align=left>".$off[city][$ix]."</td>";
	$csv.="\"".$off[first][$ix]."\",\"".$off[last][$ix]."\",\"".$off[city][$ix]."\",";
      if($sport=='di') 
      {
	 echo "<td>".$off[reg][$ix]."</td>";
	 echo "<td>".$off[boys][$ix]."</td><td>".$off[girls][$ix]."</td>";
	 $csv.="\"".$off[reg][$ix]."\",\"".$off[boys][$ix]."\",\"".$off[girls][$ix]."\",";
      }
      else if($sport!='wr')
      {
         echo "<td>".$off[ad][$ix]."</td>";
	 if($sport!='so') echo "<td>".$off[coach][$ix]."</td>";
	 $csv.="\"".$off[ad][$ix]."\",";
	 if($sport!='so') $csv.="\"".$off[coach][$ix]."\",";
         if($sport=='so')
	 {
            echo "<td>".$off[boys][$ix]."</td><td>".$off[girls][$ix]."</td>";
	    $csv.="\"".$off[boys][$ix]."\",\"".$off[girls][$ix]."\",";
  	 }
      }
      echo "<td>".$off[total][$ix]."</td>";
      $csv.="\"".$off[total][$ix]."\",";
      if($sport=='wr' || preg_match("/bb/",$sport))
      {
         echo "<td>".$off[rank][$ix]."</td>";
	 $csv.="\"".$off[rank][$ix]."\",";
      }
      for($i=0;$i<$distct;$i++)
      {
         echo "<td>".$off[$i][$ix]."</td>";
	 $csv.="\"".$off[$i][$ix]."\",";
      }
      $csv.="\r\n";
      echo "</tr>";
}
echo "</table><br><br>";
//WRITE CSV FILE
$filename=strtoupper($sport)."BallotReport.csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
?>
<script language="javascript">
document.getElementById('exportdiv').style.display='';
document.getElementById('exportdiv').innerHTML="<a href='reports.php?session=<?php echo $session; ?>&filename=<?php echo $filename; ?>'>Export this Report</a> (.CSV file for Excel)";
</script>
<?php
echo "<a href=\"vote.php?sport=$sport&session=$session\" class=small>Return to $sportname Officials' Ballot Admin</a
>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\" class=small>Return Home</a>";

echo $end_html;
?>
