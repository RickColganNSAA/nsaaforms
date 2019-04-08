<?php
/***************************
MAX PREPS HITS THIS SCRIPT
WITH A VALID KEY TO ACCESS 
SCHEDULES/SCORES FOR A 
PARTICULAR SPORT
Created 7/23/13
Author Ann Gaffigan, Gazelle INC
****************************/
require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';
//system("rm error_log");

$sportsend=strtolower($_REQUEST['sport']);
$apikey=$_REQUEST['apikey'];
$gendersend=strtolower($_REQUEST['gender']);
$sport=$sportsend;
$sql="SHOW TABLES LIKE '".$sport."sched'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//ADD GENDER
{
   if($gendersend=="girls") $sport.="g";
   else $sport.="b";
   $sql="SHOW TABLES LIKE '".$sport."sched'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//CANNOT FIND THIS SCHEDULE
   {
      echo "<xml>
	<error>ERROR: Schedule not found for $sportsend ($gendersend)</error>
	</xml>";
      exit();
   }
}
if($apikey!=$mpkey)
{
   echo "<xml>
	<error>ERROR: Invalid API KEY.</error>
	</xml>";
   exit();
}
   
$schedtbl=$sport."sched";
$schooltbl=$sport."school";
$year=date("Y");
if(date("m")<6) $year--;
$year2=$year+1;

$xml="<xml>
<DateTime>".time()."</DateTime>
<Gender>$gendersend</Gender>
<Sport>$sportsend</Sport>
<Games>\n";
$sql="SELECT * FROM $schedtbl WHERE received!='0000-00-00' AND received>='$year-08-01' AND received<'$year2-08-01' ORDER BY received";
$result=mysql_query($sql);
$ct=0;
while($row=mysql_fetch_array($result))
{
   $xml.="<Game>
	<Date>$row[received]</Date>\r\n\t
	<LastUpdate>$row[lastupdateformp]</LastUpdate>\r\n\t";
   if($row[tid]>0) 
      $xml.="<Type>Tournament</Type>\r\n\t<TournamentName>".GetTournamentName($row[tid],$sport)."</TournamentName>\r\n\t";
   else 
      $xml.="<Type>Game</Type>\r\n\t<TournamentName></TournamentName>\r\n\t";
   if($row[sidvargame]==0) { $sidname=""; $sidmpid=0; }
   else { $sidname=GetSchoolName($row[sid],$sport); $sidmpid=GetMaxPrepsID($row[sid],$sport); }
   if($row[oppvargame]==0) { $oppidname="0"; $oppidmpid=0; }
   else { $oppidname=GetSchoolName($row[oppid],$sport); $oppidmpid=GetMaxPrepsID($row[oppid],$sport); }
   $xml.="<ScoreID>$row[scoreid]</ScoreID>
\t<SchoolID1>$sidmpid</SchoolID1>
\t<School1>".$sidname."</School1>
\t<Varsity1>$row[sidvargame]</Varsity1>
\t<Score1>$row[sidscore]</Score1>\r\n";
   if($sport=='vb')	//SET SCORES
   {
      $xml.="\t<Set1Score1>$row[sidscore1]</Set1Score1>
\t<Set2Score1>$row[sidscore2]</Set2Score1>
\t<Set3Score1>$row[sidscore3]</Set3Score1>
\t<Set4Score1>$row[sidscore4]</Set4Score1>
\t<Set5Score1>$row[sidscore5]</Set5Score1>\r\n";
   }
   $xml.="\t<SchoolID2>$oppidmpid</SchoolID2>
\t<School2>".$oppidname."</School2>
\t<Varsity2>$row[oppvargame]</Varsity2>
\t<Score2>$row[oppscore]</Score2>\r\n";
   if($sport=='vb')     //SET SCORES
   {
      $xml.="\t<Set1Score2>$row[oppscore1]</Set1Score2>
\t<Set2Score2>$row[oppscore2]</Set2Score2>
\t<Set3Score2>$row[oppscore3]</Set3Score2>
\t<Set4Score2>$row[oppscore4]</Set4Score2>
\t<Set5Score2>$row[oppscore5]</Set5Score2>\r\n";
   }
   $xml.="\t<ScoreDateTime>$row[datescored]</ScoreDateTime>
\t<HomeSchoolID>".GetMaxPrepsID($row[homeid],$sport)."</HomeSchoolID>
\t<Postponed>$row[postponed]</Postponed>
\t<OriginalDate>$row[origdate]</OriginalDate>
\t<Cancelled>$row[cancelled]</Cancelled>
\t<Extra>$row[extra]</Extra>
\t<Highlights>$row[highlights]</Highlights>
</Game>\r\n";	
   //$ct++;
   //echo "$ct) ".time()."<br>"; flush();
}
$xml.="</Games>
</xml>";

$xml=preg_replace("/& /","&amp; ",$xml);

$filename=$gendersend.$sportsend."forMP.xml";
$open=fopen(citgf_fopen("attachments/$filename"),"w");
fwrite($open,$xml);
fclose($open); 
 citgf_makepublic("attachments/$filename");
header("Location:attachments/$filename");
?> 
