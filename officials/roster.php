<?php
//roster.php: printable version of roster of officials for selected sport
//	(only officials with R, C, or A in class field in __off table)
require 'functions.php';
require 'variables.php';

if(!$sport || $sport=='')
{
   echo $init_html;
   echo "<table width=100%><tr align=center><td><br><br><br>";
   echo "No ACTIVITY selected.<br><br>";
   echo "<a class=small href=\"javascript:window.close();\">Close Window</a>";
   echo $end_html;
   exit();
}

if($sport=='sp' || $sport=='pp')
{
   header("Location:jroster.php?session=$session&list=$sport&archive=$archive&ad=$ad");
   exit();
}

if($ad==1)	//check ad login
{
   $sql="SELECT * FROM $db_name.sessions WHERE session_id='$session'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      header("Locaiton:../index.php?error=1");
      exit();
   }
}

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session) && $ad!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
$level=GetLevel($session);

if($submit=="Save" && $level==1)
{
   if(preg_match("/$db_name2/",$archive))	//showing archived roster
      $sql="UPDATE $db_name2.rosters SET showold='$active' WHERE sport='$sport'";
   else
      $sql="UPDATE $db_name2.rosters SET active='$active' WHERE sport='$sport'";
   $result=mysql_query($sql);
}

echo "<a class=small href=\"javascript:window.close()\">Close this Window</a><br><br>";

$sportname=GetSportName($sport);
$today=date("m/d/Y");

if(preg_match("/officials/",$archive))	//showing archived roster
   $database=$archive;
else
   $database="$db_name2";
$offtable=$sport."off";
$sql="SELECT DISTINCT t1.*,t2.class";
if($sport=='tr') $sql.=",t3.position";
$sql.=" FROM $database.officials AS t1, $database.$offtable AS t2";
if($sport=='fb' && $crew==1)	//FOOTBALL, GROUPED BY CREW
   $sql.=", $database.fbapply AS t3 WHERE t1.id=t2.offid AND t2.offid=t3.chief AND ";
else if($sport=='tr')
{
   if($database==$archive)
   {
      $getyear=split("officials",$archive);
      $year1=substr($getyear[1],0,4);
      $year2=substr($getyear[1],4,4);
      $regyr="$year1-$year2";
   }
   else
   {
      $regyr2=date("Y"); $regyr1=$regyr2-1;
      $regyr="$regyr1-$regyr2";
   }
   $sql.=", ".$database.".".$offtable."_hist AS t3 WHERE t1.id=t2.offid AND t2.offid=t3.offid AND t3.regyr='$regyr' AND ";
}
else
   $sql.=" WHERE t1.id=t2.offid AND ";
$sql.="t2.mailing>=100 AND t1.$sport='x' AND t1.inactive!='x' AND t2.class!='' ";
if($sort=="class")
   $sql.="ORDER BY t2.class $sortdir,t1.last,t1.first";
else if($sort=="t3.position")
   $sql.="ORDER BY t3.position $sortdir,t1.last,t1.first";
else if($sort && $sort!="" && $sort!="name")
   $sql.="ORDER BY t1.$sort $sortdir,t1.last,t1.first";
else
   $sql.="ORDER BY t1.last $sortdir, t1.first";
$result=mysql_query($sql);
echo mysql_error();
//echo mysql_num_rows($result);
//echo $sql;
$results=array(); $ix=0;
$csv="\"First Name\",\"Last Name\",";
if($sport=='tr') $csv.="\"Starter\",";
$csv.="\"Address\",\"City\",\"State\",\"Zip\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"E-mail\"\r\n";
$crewmems=array("Referee","Umpire","Linesman","Linejudge","Backjudge");
while($row=mysql_fetch_array($result))
{
   $i=0;
   $results[$ix][$i]="$row[first] $row[last]";
   if($sport=='fb' && $crew==1) 
   {
      $results[$ix][$i].=" (Crew Chief";
      $sql2="SELECT * FROM $database.fbapply WHERE chief='$row[id]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      for($c=0;$c<count($crewmems);$c++)
      {
	 $field=strtolower($crewmems[$c]);
         if($row[id]==$row2[$field]) $results[$ix][$i].=", $crewmems[$c]";
      }
      $results[$ix][$i].=")";
   }
   $i++;
   if($sport=='tr')
   {
      if($row[position]=="starter") $results[$ix][$i]="X";
      else $results[$ix][$i]=" ";
      $i++;
   }
   else if($sport=='fb' && $crew!=1)
   {
      $sql2="SELECT * FROM $database.fbapply WHERE (chief='$row[id]' OR ";
      for($c=0;$c<count($crewmems);$c++)
      {
         $field=strtolower($crewmems[$c]);
         $sql2.="$field='$row[id]' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4).")";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)==0) $results[$ix][$i]="SUB";
      else $results[$ix][$i]="&nbsp;";
      $i++;
   }
   if($level!=2)
   {
      $results[$ix][$i]=$row['class']; $i++;
   }
   $results[$ix][$i]=$row[address]; $i++;
   $results[$ix][$i]=$row[city]; $i++;
   $results[$ix][$i]=$row[state]; $i++;
   $results[$ix][$i]=$row[zip]; $i++;
   $csv.="\"$row[first]\",\"$row[last]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",";
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
   $results[$ix][$i]="<a class=small href=\"mailto:$row[email]\">$row[email]</a>"; $i++;
   $csv.="\"$row[email]\"\r\n";
   $ix++;
   if($sport=='fb' && $crew==1)	//GET OTHER CREW MEMBERS
   {
      for($c=0;$c<count($crewmems);$c++)
      {
         $field=strtolower($crewmems[$c]);
         $curoffid=$row2[$field];
	 if($row[id]!=$curoffid && $curoffid>0)
	 {
         $sql3="SELECT DISTINCT t1.*,t2.class FROM $database.officials AS t1, $database.$offtable AS t2 WHERE t1.id=t2.offid AND t1.id='$curoffid'";
   	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
         $i=0;
         $results[$ix][$i]="$row3[first] $row3[last] (".$crewmems[$c].")"; 
         $i++;
         if($level!=2)
         {
            $results[$ix][$i]=$row3['class']; $i++;
         }
         $results[$ix][$i]=$row3[address]; $i++;
         $results[$ix][$i]=$row3[city]; $i++;
         $results[$ix][$i]=$row3[state]; $i++;
         $results[$ix][$i]=$row3[zip]; $i++;
         $csv.="\"$row3[first]\",\"$row3[last]\",\"$row3[address]\",\"$row3[city]\",\"$row3[state]\",\"$row3[zip]\",";
         if($row3[homeph]=="")
            $homeph="";
         else
            $homeph="(".substr($row3[homeph],0,3).")".substr($row3[homeph],3,3)."-".substr($row3[homeph],6,4);
         $csv.="\"$homeph\",";
         $results[$ix][$i]=$homeph; $i++;
         if($row3[workph]=="")
            $workph="";
         else
            $workph="(".substr($row3[workph],0,3).")".substr($row3[workph],3,3)."-".substr($row3[workph],6,4);
         $csv.="\"$workph\",";
         $results[$ix][$i]=$workph; $i++;
         if($row3[cellph]=="")
         $cellph="";
         else
            $cellph="(".substr($row3[cellph],0,3).")".substr($row3[cellph],3,3)."-".substr($row3[cellph],6,4);
         $csv.="\"$cellph\",";
         $results[$ix][$i]=$cellph; $i++;
         $results[$ix][$i]="<a class=small href=\"mailto:$row3[email]\">$row3[email]</a>"; $i++;
         $csv.="\"$row[email]\"\r\n";
         $ix++;
	 }//end if not crew chief
      }	//end for each crew member
   }	//end if group by crew
}
//write to export file:
$filename=$sport."officialsroster".date("m").date("d").date("Y").".csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
if(!fwrite($open,$csv)) echo "Could not write to $filename.";
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
//display in HTML format:
echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style='border:#a0a0a0 1px solid;'>";
echo "<caption><b>Nebraska School Activities Association Official $sportname Roster:</b><br>";
if(!ereg("$db_name2",$archive)) 
   echo "(as of $today)<br>";
else
{
   $getyear=split("officials",$archive);
   $year1=substr($getyear[1],0,4);
   $year2=substr($getyear[1],4,4);
   echo "(NOTE: This is the final roster from the $year1-$year2 school year.)<br>";
}
if($level==1)	//NSAA user: allow to "ACTIVATE" roster (if not already)
{
   echo "<table><tr align=left><td>";
   $sql2="SELECT active,showold FROM $db_name2.rosters WHERE sport='$sport'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<form method=post action=\"roster.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=archive value=\"$archive\">";
   echo "<input type=hidden name=sport value=\"$sport\">";
   echo "<input type=checkbox value='x' name=active";
   if(ereg("officials",$archive) && $row2[showold]=='x') echo " checked";
   else if(!ereg("officials",$archive) && $row2[active]=='x') echo " checked";
   echo "> <font style=\"color:blue\"><b><i>Check here to post this roster to all AD's, $sportname officials and $sportname observers.</font></b></i>";
   echo "<input type=submit name=submit value=\"Save\"></form></td></tr></table>";
}
echo mysql_num_rows($result)." Results&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a class=small href=\"reports.php?ad=$ad&session=$session&filename=$filename\">Click HERE to EXPORT this Roster of $sportname Officials</a></b><br>";
echo "<br>";
if($sport=='fb' && !$crew)
{
   echo "<p><a href=\"roster.php?session=$session&sport=$sport&archive=$archive&crew=1\">View Football Officials Roster grouped by CREW</a></p>";
}
else if($sport=='fb')
{
   echo "<p><a href=\"roster.php?session=$session&archive=$archive&sport=$sport\">View ALL Registered Football Officials (not grouped by crew)</a></p>";
}
echo "</caption>";
echo "<tr align=center><td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=name";
if($sortdir=="asc" || ($sort=="name" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">Name</a></td>";
if($sport=='tr')
{
   echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=t3.position";
   if($sortdir=="asc" || ($sort=="position" && $sortdir!="desc")) echo "&sortdir=desc";
   echo "\">Starter</a></td>";
}
else if($sport=='fb' && $crew!=1)
{
   echo "<td><b>SUB<sup>1</sup></b></td>";
}
if($level!='2')	//if not an official, show classification
{
   echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=class";
   if($sortdir=="asc" || ($sort=="class" && $sortdir!="desc")) echo "&sortdir=desc";
   echo "\">Class</a></td>";
}
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=address";
if($sortdir=="asc" || ($sort=="address" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">Address</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=city";
if($sortdir=="asc" || ($sort=="city" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">City</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=state";
if($sortdir=="asc" || ($sort=="state" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">State</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=zip";
if($sortdir=="asc" || ($sort=="zip" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">Zip</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=homeph";
if($sortdir=="asc" || ($sort=="homeph" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">Home Phone</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=workph";
if($sortdir=="asc" || ($sort=="workph" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">Work Phone</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=cellph";
if($sortdir=="asc" || ($sort=="cellph" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\">Cell Phone</a></td>";
echo "<td><a class=small href=\"roster.php?archive=$archive&ad=$ad&session=$session&sport=$sport&sort=email";
if($sortdir=="asc" || ($sort=="email" && $sortdir!="desc")) echo "&sortdir=desc";
echo "\" width=\"300px\">E-mail</a></td>";
echo "</tr>";
for($i=0;$i<$ix;$i++)
{
   echo "<tr align=left>";
   for($j=0;$j<count($results[$i]);$j++)
   {
      echo "<td>";
      if($crew==1 && $sport=='fb' && $j==0 && preg_match("/Crew Chief/",$results[$i][$j]))
         echo "<b>".$results[$i][$j]."</b></td>";
      else echo $results[$i][$j]."</td>";
   }
   echo "</tr>";
}
echo "</table>";
if($sport=='fb' && $crew!=1)
   echo "<p><sup>1</sup> Officials marked as SUB are not currently listed on a specific Football Crew.</p>";
echo "<br><a class=small href=\"javascript:window.close()\">Close this Window</a>";
echo $end_html;
?>
