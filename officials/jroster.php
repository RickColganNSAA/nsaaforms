<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name2, $db);

if($ad==1)      //check ad login
{
   $sql="SELECT * FROM $db_name.sessions WHERE session_id='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      header("Location:../index.php?error=1");
      exit();
   }
}

$level=GetLevelJ($session);

if(!ValidUser($session) && $ad!=1)
{
   header("Location:index.php?error=1");
   exit();
}

if(ereg("officials",$archive))
   $database=$archive;
else
   $database="$db_name2";
if($submit=="Save" && $level==1)
{   
   if(ereg("$db_name2",$archive))   //showing archived roster
      $sql="UPDATE $db_name2.rosters SET showold='$active' WHERE sport='$list'";
   else
      $sql="UPDATE $db_name2.rosters SET active='$active' WHERE sport='$list'";
   $result=mysql_query($sql);
}

if($database!=$db_name2)	//if BEFORE 2013-14, use old fields:
{
   $years=preg_replace("/[^0-9]/","",$database);
   $yr1=substr($years,0,4);
}
else $yr1=date("Y");

if($yr1<=2012)
{
   if($list=="all")
   {
      $sql="SELECT t1.* FROM $database.judges AS t1,$database.sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND ((t2.spscore>=40 AND t2.speech!='' AND t2.play='') OR (t2.ppscore>=8 AND t2.play!='' AND t2.speech='') OR (t2.ppscore + t2.spscore>=48 AND ((t2.speech!='' AND t2.play!='') OR t2.combo!='')))";
   }
   else if($list=="sp")
   {
      $sql="SELECT t1.* FROM $database.judges AS t1,$database.sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND ((t2.spscore>=40 AND t2.speech!='' AND t2.play='') OR (t2.ppscore + t2.spscore>=48 AND ((t2.speech!='' AND t2.play!='') OR t2.combo!='')))";
   }
   else if($list=='pp')
   {
      $sql="SELECT t1.* FROM $database.judges AS t1,$database.sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND ((t2.ppscore>=8 AND t2.play!='' AND t2.speech='') OR (t2.ppscore + t2.spscore>=48 AND ((t2.speech!='' AND t2.play!='') OR t2.combo!='')))";
   }
}
else if($list=="sp")
{
   $sql2="SELECT * FROM ".$list."test ORDER BY place";
   $result2=mysql_query($sql2);
   $total=mysql_num_rows($result2);
   if($total>0) $needed=.8*$total;
   else $needed=40;
   $sql="SELECT t1.* FROM $database.judges AS t1,$database.sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.spmeeting='x' AND t2.correct>='$needed'";
}
else if($list=="pp")
{
   $sql2="SELECT * FROM ".$list."test ORDER BY place";
   $result2=mysql_query($sql2);
   $total=mysql_num_rows($result2);
   if($total>0) $needed=.8*$total;
   else $needed=20;
   $sql="SELECT t1.* FROM $database.judges AS t1,$database.pptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND t2.correct>='$needed'";
}
if($sort=='address')
   $sql.=" ORDER BY t1.address";
else if($sort=='city')
   $sql.=" ORDER BY t1.city,t1.state";
else if($sort=='state')
   $sql.=" ORDER BY t1.state";
else if($sort=='zip')
   $sql.=" ORDER BY t1.zip";
else if($sort=='homeph')
   $sql.=" ORDER BY t1.homeph";
else if($sort=='cellph')
   $sql.=" ORDER BY t1.cellph";
else if($sort=='workph')
   $sql.=" ORDER BY t1.workph";
else if($sort=='email')
   $sql.=" ORDER BY t1.email";
else
   $sql.=" ORDER BY t1.last,t1.first,t1.middle";
$result=mysql_query($sql);
//echo $sql;
$results=array(); $ix=0;
$csv="\"First Name\",\"Last Name\",\"# Years Registered\",\"Address\",\"City\",\"State\",\"Zip\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"E-mail\"\r\n";
while($row=mysql_fetch_array($result))
{
   $i=0;
   $results[$ix][$i]="$row[first] $row[last]"; 
   $field=$list."trainingyr";
   if($row[$field]>0) $results[$ix][$i].=" *";
   $i++;
   if($list=='sp')
   {
      $results[$ix][$i]=$row[yearsspeech]; $i++;
   }
   else
   {
      $results[$ix][$i]=$row[yearsplay]; $i++;
   }
   $results[$ix][$i]=$row[address]; $i++;
   $results[$ix][$i]=$row[city]; $i++;
   $results[$ix][$i]=$row[state]; $i++;
   $results[$ix][$i]=$row[zip]; $i++;
   $csv.="\"$row[first]\",\"$row[last]\",\"$row[years]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",";
   if($row[homeph]=="")
      $homeph="";
   else
      $homeph="(".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
   $csv.="\"$homeph\",";
   $results[$ix][$i]=$homeph; $i++;
   if($row[workph]=="")
      $workph="";
   else
      $workph="(".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4);
   $csv.="\"$workph\",";
   $results[$ix][$i]=$workph; $i++;
   if($row[cellph]=="")
      $cellph="";
   else
      $cellph="(".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
   $csv.="\"$cellph\",";
   $results[$ix][$i]=$cellph; $i++;
   $results[$ix][$i]=$row[email]; $i++;
   $csv.="\"$row[email]\"\r\n";
   $ix++;
}
//write to export file:
$filename=$list."judgesroster".date("m").date("d").date("Y").".csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
if(!fwrite($open,$csv)) echo "Could not write to $filename.";
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
$today=date("m/d/Y");
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#333333 1px solid;\"><caption><b>Nebraska School Activities Association Official Roster:";
if($list=='all') echo " Speech & Play Production ";
else if($list=='sp') echo " Speech ";
else if($list=='pp') echo " Play Production ";
echo "Judges</b><br>";
if(!ereg("officials",$archive)) echo "(as of $today)<br>";
else
{
   $getyear=split("officials",$archive);
   $year1=substr($getyear[1],0,4);
   $year2=substr($getyear[1],4,4);
   echo "(NOTE: This is the final roster from the $year1-$year2 school year.)<br>";
}
echo mysql_error();
echo mysql_num_rows($result)." Results&nbsp;&nbsp;|&nbsp;&nbsp;";
if($ad==1)
   $filepath="../exports";
else
   $filepath="reports";
echo "<a class=small href=\"$filepath.php?session=$session&filename=$filename\">Click HERE to EXPORT this Roster of $sportname Officials</a></b><br>";
if($level==1)   //NSAA user: allow to "ACTIVATE" roster (if not already)
{   
   echo "<table><tr align=left><td>";
   $sql2="SELECT active,showold FROM $db_name2.rosters WHERE sport='$list'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<form method=post action=\"jroster.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=archive value=\"$archive\">";
   echo "<input type=hidden name=list value=\"$list\">";
   echo "<input type=checkbox value='x' name=active";
   if(ereg("officials",$archive) && $row2[showold]=='x') echo " checked";
   else if(!ereg("officials",$archive) && $row2[active]=='x') echo " checked";
   echo "> <font style=\"color:blue\"><b><i>Check here to post this roster to all AD's and $sportname judges.</font></b></i>";
   echo "<input type=submit name=submit value=\"Save\"></form></td></tr></table>";
}
echo "<p style=\"text-align:left;\">* Indicates the judge has attended a judge's training workshop.</p></caption>";

echo "<tr align=center>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list\">Name</a></td>";
//for 2008, show years registered with NSAA:
$start="2008-05-01";
if(PastDue($start,0))
   echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=years\">Years<br>Registered</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=address\">Address</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=city\">City</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=state\">ST</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=zip\">Zip</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=homeph\">Home Phone</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=workph\">Work Phone</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=cellph\">Cell Phone</a></td>";
echo "<td><a class=small href=\"jroster.php?session=$session&list=$list&sort=email\">E-mail</a></td>";
echo "</tr>";

for($i=0;$i<mysql_num_rows($result);$i++)
{
   echo "<tr align=left>";
   for($j=0;$j<count($results[$i]);$j++)
   {
      echo "<td>".$results[$i][$j]."</td>";
   }
   echo "</tr>";
}
echo "</table>";
echo $end_html;
?>
