<?php
//view_fb_stats.php: optional stats report form
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!$session || !ValidUser($session))
{
   if($public!=1)	//if not public version
   {
      header("Location:../index.php");
      exit();
   }
}

$level=GetLevel($session);

if($level==1 || $public==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="fb";
if(!IsHeadSchool($schoolid,$sport) && !GetCoopHeadSchool($schoolid,$sport) && $school!="Test's School") //NOT a $sport school at all
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><br><div class='alert' style='width:400px;'><b>$school</b> is not listed as a ".GetActivityName($sport)." school.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}
else if(!IsHeadSchool($schoolid,$sport) && $school!="Test's School")    //in a Co-op, not the head school
{
   echo $init_html;
   echo GetHeader($session);
   $mainsch=GetCoopHeadSchool($schoolid,$sport);
   echo "<br><br><br><div class='alert' style='width:400px'><b>$school</b> is in a co-op with <b>$mainsch</b> for ".GetActivityName($sport).".<br><br>Only the head school of the co-op can fill out this entry form.  <b>$mainsch</b> is listed as the head school for this co-op.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}

//get mascot
$sql="SELECT mascot,color_names FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[0]; $colors=$row[1];

//get coaches
$sql="SELECT name,asst_coaches FROM logins WHERE level=3 AND school='$school2' AND sport LIKE 'Football%'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];

//check if players have been entered yet
$entered=0;
$sql="SELECT t1.* FROM fb_stat_off AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0) $entered=1;

$sql="SELECT t1.* FROM fb_stat_qb AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0) $entered=1;

$sql="SELECT t1.* FROM fb_stat_kick AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t2.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0) $entered=1;

$sql="SELECT t1.* FROM fb_stat_def AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0) $entered=1;

$sql="SELECT t1.* FROM fb_team AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0) $entered=1;

if($entered==0)
{
   if($public==1)
   {
      echo $init_html;
      echo "<table><tr><th>";
      echo "<b>The school you have chosen ($school) has not entered any Football statistics as of this time.</b>";
      echo "<br><br><a href=\"/fb.php\">$stateassn Football Page</a>";
      echo "&nbsp;&nbsp;&nbsp;<a href=\"../fb_stats.php\">View More Stats</a>";
      echo "</th></tr></table></body></html>";
      exit();
   }
   else
   {
      header("Location:edit_fb_stats.php?session=$session&school_ch=$school_ch");
      exit();
   }
}

echo $init_html;
$string=$init_html;	//string and csv are to be written to files
$csv="";		//as attachments for e-mails to dist dir

if($print!=1 && $public!=1)	//omit header if printer-friendly version
{
   echo GetHeader($session);
}

if($print!=1 && $public!=1)	//non-printer-friendly version
{
?>
<a class=small href="view_fb_stats.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target="new">Printer-Friendly Version</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="edit_fb_stats.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Edit this Form</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="view_fb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Football Main Page</a>
<br><br>
<?php
}//end if print!=1
else if($public==1)	//public version
{
?>
<a class=small href="/">www.nsaahome.org</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="../fb_stats.php">View More Stats</a>
<br><br>
<?php
}//end if public

//$info will hold next string of data to be shown on screen and written to file
$info.="<table cellspacing=0 cellpadding=0><!--Table to Hold Sub-Tables-->";
$info.="<caption><b>$stateassn Football Statistics Report</b></caption>";
$info.="<tr align=center>";
$info.="<td>";
$info.="<table>";
$info.="<tr align=center>";
   //get date of last update
   $sql="SELECT t1.date FROM fb_stat_updates AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
$update=date("F d, Y",$row[0]);
if(mysql_num_rows($result)==0)
   $update=date("F d, Y",time());
$info.="<td>As of $update</td>";
$csv="Date:,$update\r\n";
$info.="</tr>";
$info.="</table>";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><hr></td>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<td>";
$info.="<table cellspacing=5><!--School Name and Class-->";
$info.="<tr align=left>";
$info.="<th>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'fb');
$sql="SELECT * FROM fbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$info.="<td>".GetSchoolName($sid,'fb')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'fb')." $mascot\r\n";
$info.="<tr align=left><th>School Colors:</th><td>$colors</td></tr>";
$info.="<tr align=left><th>NSAA-Certified Coach:</th><td>$coach</td></tr>";
$info.="<tr align=left><th>Assistant Coaches:</th><td>$asst</td></tr>";
$csv.="School Colors:,\"$colors\"\r\n";
$csv.="Head Coach:,$coach\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$info.="<tr align=left><th>Class:</th><td>";
   //get class for this school if already given
   $sql="SELECT t1.class FROM fb_classes AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
$info.="$row[0]</td></tr>";
$csv.="Class:,$row[0]\r\n";
$info.="</table>";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td>";
$info.="<table width=100% cellspacing=2 cellpadding=3 border=1 bordercolor=#000000>";
$info.="<caption align=left><b><font size=2>Offensive Statistics:</font></caption>";
$info.="<!--Offensive Stats Table-->";
$info.="<tr align=center>";
$info.="<th class=smaller rowspan=2>Starter</th>";
$info.="<th class=smaller rowspan=2>Player<br>(Last, First M)</th>";
$info.="<th class=smaller rowspan=2>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller rowspan=2>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller rowspan=2>Total<br>TDs</th>";
$info.="<th class=smaller rowspan=2>Total Pts<br>Scored</th>";
$info.="<th class=smaller colspan=3>Rushing</th>";
$info.="<th class=smaller colspan=3>Receiving</th>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<th class=smaller>Carries</th>";
$info.="<th class=smaller>Yards</th>";
$info.="<th class=smaller>TDs</th>";
$info.="<th class=smaller>Catches</th>";
$info.="<th class=smaller>Yards</th>";
$info.="<th class=smaller>TDs</th>";
$info.="</tr>";
$csv.="\r\nOffensive Statistics:\r\n";
$csv.="Starter,Player,Lt Jersey #,Dk Jersey #,Total TDs,Total Pts Scored,Rush Carries, Rush Yds, Rush TDs, Rec Catches, Rec Yds, Rec Tds\r\n";
   //Get offensive stats already submitted for this school:
   $sql="SELECT t1.*,t2.last, t2.first, t2.middle FROM fb_stat_off AS t1, eligibility AS t2 WHERE t2.id=t1.student_id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt, t1.jersey_dk";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td>";
      if($row[2]=='y') 
      {
	 $info.="X";
	 $csv.="X,";
      }
      else 
      {
	 $info.="&nbsp;";
	 $csv.=",";
      }
      $info.="</td>";
      $info.="<td align=left>$row[14], $row[15] $row[16]</td>";
      $info.="<td>$row[3]</td>";
      $info.="<td>$row[4]</td>";
      $info.="<td>$row[5]</td>";
      $info.="<td>$row[6]</td>";
      $info.="<td>$row[7]</td>";
      $info.="<td>$row[8]</td>";
      $info.="<td>$row[9]</td>";
      $info.="<td>$row[10]</td>";
      $info.="<td>$row[11]</td>";
      $info.="<td>$row[12]</td>";
      $info.="</tr>";
      $csv.="$row[14] $row[15] $row[16],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12]\r\n";
   }
$info.="</table>";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
$info.="<caption align=left><font size=2><b>Passing Statistics:</b></font></caption>";
$info.="<tr align=center>";
$info.="<th class=smaller>Starter</th>";
$info.="<th class=smaller>Player<br>(Last, First M)</th>";
$info.="<th class=smaller>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Comp</th>";
$info.="<th class=smaller>Attempts</th>";
$info.="<th class=smaller>Yards</th>";
$info.="<th class=smaller>TDs</th>";
$info.="<th class=smaller>Interceptions</th>";
$info.="</tr>";
$csv.="\r\nPassing Statistics:\r\n";
$csv.="Starter,Player,Lt Jersey #,Dk Jersey #,Completions,Attempts,Yards,TDs\r\n";
   //Get passing/qb already submitted for this school:
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_qb AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt, t1.jersey_dk";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td>";
      if($row[2]=='y') 
      {
	 $info.="X";
	 $csv.="X,";
      }
      else 
      {
	 $info.="&nbsp;";
	 $csv.=",";
      }
      $info.="</td>";
      $info.="<td align=left>$row[11], $row[12] $row[13]</td>";
      $info.="<td>$row[3]</td>";
      $info.="<td>$row[4]</td>";
      $info.="<td>$row[5]</td>";
      $info.="<td>$row[6]</td>";
      $info.="<td>$row[7]</td>";
      $info.="<td>$row[8]</td>";
      $info.="<td>$row[9]</td>";
      $info.="</tr>";
      $csv.="$row[11] $row[12] $row[13],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9]\r\n";
   }
$info.="</table>";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
$info.="<caption align=left><font size=2><b>Punting Statistics:</b></font></caption>";
$info.="<tr align=center>";
$info.="<th class=smaller>Starter</th>";
$info.="<th class=smaller>Player<br>(Last, First M)</th>";
$info.="<th class=smaller>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Attempts</th>";
$info.="<th class=smaller>Yards</th>";
$info.="<th class=smaller>Average</th>";
$info.="<th class=smaller>Longest</th>";
$info.="</tr>";
$csv.="\r\nPunting Statistics:\r\n";
$csv.="Starter,Player,Lt Jersey #,Dk Jersey #,Attempts,Yards,Average,Longest\r\n";
   //Get kicker stats already submitted for this school:
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_kick AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt, t1.jersey_dk";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td>";
      if($row[2]=='y') 
      {
	 $info.="X";
	 $csv.="X,";
      }
      else 
      {
	 $info.="&nbsp;";
	 $csv.=",";
      }
      $info.="</td>";
      $info.="<td align=left>$row[10], $row[11] $row[12]</td>";
      $info.="<td>$row[3]</td>";
      $info.="<td>$row[4]</td>";
      $info.="<td>$row[5]</td>";
      $info.="<td>$row[6]</td>";
      $info.="<td>$row[7]</td>";
      $info.="<td>$row[8]</td>";
      $info.="</tr>";
      $csv.="$row[11] $row[10],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8]\r\n";
   }
$info.="</table>";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
$info.="<caption align=left><font size=2><b>Place-Kicking Statistics:</b></font>";
$info.="</caption>";
$info.="<!--Place-Kicking Table-->";
$info.="<tr align=center>";
$info.="<th class=smaller rowspan=2>Starter</th>";
$info.="<th class=smaller rowspan=2>Player<br>(Last, First M)</th>";
$info.="<th class=smaller rowspan=2>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller rowspan=2>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller colspan=2>Point After TD</th>";
$info.="<th class=smaller colspan=2>Field Goals</th>";
$info.="<th class=smaller rowspan=2>Longest</th>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<th class=smaller>Att</th><th class=smaller>Good</th>";
$info.="<th class=smaller>Att</th><th class=smaller>Good</th>";
$info.="</tr>";
$csv.="\r\nPlace-Kicking Statistics:\r\n";
$csv.="Starter,Player,Lt Jersey #,Dk Jersey #,PAT Att,PAT Good,FG ATT,FG Good,Longest\r\n";
   //get placekicker stats for this school
$sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_pk AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY t1.jersey_lt, t1.jersey_dk";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $info.="<tr align=center>";
   $info.="<td>";
   if($row[2]=='y')
   {
      $info.="X";
      $csv.="X,";
   }
   else
   {
      $info.="&nbsp;";
      $csv.=",";
   }
   $info.="</td>";
   $info.="<td align=left>$row[11], $row[12] $row[13]</td>";
   $info.="<td>$row[3]</td>";
   $info.="<td>$row[4]</td>";
   $info.="<td>$row[5]</td>";
   $info.="<td>$row[6]</td>";
   $info.="<td>$row[7]</td>";
   $info.="<td>$row[8]</td>";
   $info.="<td>$row[9]</td>";
   $info.="</tr>";
   $csv.="$row[12] $row[11],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9]\r\n";
}
$info.="</table></td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table width=100% border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
$info.="<caption align=left><font size=2><b>Defensive Statistics:</b></font>";
$info.="</caption>";
$info.="<!--Defensive Table-->";
$info.="<tr align=center>";
$info.="<th class=smaller rowspan=2>Starter</th>";
$info.="<th class=smaller rowspan=2>Player<br>(Last, First M)</th>";
$info.="<th class=smaller rowspan=2>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller rowspan=2>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller colspan=3>Tackles</th>";
$info.="<th class=smaller rowspan=2>QB<br>Sacks</th>";
$info.="<th class=smaller rowspan=2>Passes<br>Intercepted</th>";
$info.="<th class=smaller rowspan=2>Blocked<br>Kicks</th>";
$info.="<th class=smaller rowspan=2>Fumble<br>Recoveries</th>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<th class=smaller>Solo</th>";
$info.="<th class=smaller>Assisted</th>";
$info.="<th class=smaller>Total</th>";
$info.="</tr>";
$csv.="\r\nDefensive Statistics:\r\n";
$csv.="Starter,Player,Lt Jersey #,Dk Jersey #,Solo Tackles,Asst Tackles,Total Tackles,QB Sacks,Interceptions,Blocked Kicks,Fumble Recoverage\r\n";
   //Get defensive stats already submitted for this school:
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle FROM fb_stat_def AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' or t1.co_op='$school2') ORDER BY t1.jersey_lt, t1.jersey_dk";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td>";
      if($row[2]=='y') 
      {
	 $info.="X";
	 $csv.="X,";
      }
      else 
      {
	 $info.="&nbsp;";
	 $csv.=",";
      }
      $info.="</td>";
      $info.="<td align=left>$row[13], $row[14] $row[15]</td>";
      $info.="<td>$row[3]</td>";
      $info.="<td>$row[4]</td>";
      $info.="<td>$row[5]</td>";
      $info.="<td>$row[6]</td>";
      $info.="<td>$row[7]</td>";
      $info.="<td>$row[8]</td>";
      $info.="<td>$row[9]</td>";
      $info.="<td>$row[10]</td>";
      $info.="<td>$row[11]</td>";
      $info.="</tr>";
      $csv.="$row[13] $row[14] $row[15],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11]\r\n";
   }
$info.="</table>";
$info.="</td></tr>";

//display team stats
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
$info.="<caption align=left><b>Team Statistics:</b></caption>";
$info.="<tr align=center><th></th>";
$info.="<th class=smaller>Points<br>Scored</th>";
$info.="<th class=smaller>Rushing<br>Yards</th>";
$info.="<th class=smaller>Passing<br>Yards</th>";
$info.="<th class=smaller>Total<br>Offense</th></tr>";
$info.="<tr align=center><th>Your Team Totals:</th>";
$csv.="\r\nTeam Statistics:\r\n";
$csv.=",Pts Scored,Rushing Yds, Passing Yds, Total Offense\r\n";
   //get team stats from fb_team
   $sql="SELECT t1.* FROM fb_team AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
$info.="<td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td></tr>";
$info.="<tr align=center><th>Opponents' Totals:</th>";
$info.="<td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td></tr>";
$csv.="Your Team Totals:,$row[2],$row[3],$row[4],$row[5]\r\n";
$csv.="Opponents' Totals:,$row[6],$row[7],$row[8],$row[9]";
$info.="</table>";
$info.="</td></tr>";

//show records reported
$sql="SELECT t1.* FROM fb_records AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2' ORDER BY t1.date";
$result=mysql_query($sql);
$info.="<tr align=center>";
$info.="<td><br><table border=1 bordercolor=#000000 cellspacing=2 cellpadding=3>";
$info.="<caption align=left><b>Playoff Records Broken:</b></caption>";
$info.="<tr align=center><th class=smaller>Date</th>";
$info.="<th class=smaller>Opponent</th><th class=smaller>Record</th></tr>";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM headers WHERE id='$row[2]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $opp=$row2[0];
   $date=date("M d, Y",$row[3]);
   $info.="<tr align=left><td>$date</td><td>$opp</td><td>$row[4]</td></tr>";
}


$info.="</table><!--End Table of Sub-Tables-->";
echo $info;
$string.=$info;
if($print!=1 && $public!=1)	//non-printer-friendly version
{
?>
<br>
<a class=small href="view_fb_stats.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target="new">Printer-Friendly Version</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="edit_fb_stats.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Edit this Form</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="view_fb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">Football Main Page</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="../welcome.php?session=<?php echo $session; ?>">Home</a>
<?php
}//end if print!=1
else if($public==1)	//public version
{
?>
<br><br>
<a class=small href="/">www.nsaahome.org</a>
&nbsp;&nbsp;&nbsp;
<a class=small href="../fb_stats.php">View More Stats</a>
<?php
}//end if public
else if($print==1 && $public!=1)	//printer-friendly version
{
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Football";
   $activ_lower=strtolower($activ);

   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");
?>
</form>
<table>
<tr align=center><th><br><br>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=fb value="1"><!--send as stats form-->
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<table>
<tr align=left><th>
Your e-mail address:</th>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><th>
Recipient(s)' address(es):</th>
<td>
<textarea name=email cols=50 rows=5 class=email><?php echo $recipients; ?></textarea>
<?php
echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
?>
</td>
</tr>
<tr align=center><td colspan=2>
<input type=submit name=submit value="Send">
</td></tr>
</table>
<font style="font-size:8pt">
<?php echo $email_note; ?>
</font>
</form>
</th></tr>
<?php
}  //end if print=1
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
