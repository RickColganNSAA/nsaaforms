<?php
/*********************************
host_teb.php
District Host Main Menu for Boys Tennis
Created 6/28/09
Author: Ann Gaffigan
*********************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sport='te_b';
$sportname="Boys Tennis";
$districts="tebdistricts";

//get district this school is hosting
$sql="SELECT t1.id FROM logins AS t1,sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostid=$row[id];
$sql="SELECT * FROM $db_name2.$districts WHERE hostid='$hostid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: You are not a District Tennis Host";
   exit();
}
$class=$row['class']; $district=$row[district];
$distid=$row[id]; $sids=$row[sids];

echo $init_html;
echo $header;

echo "<br><table class=nine width=\"550px\" cellspacing=2 cellpadding=3><caption><b>$sportname District Host Main Menu:</b><br><i>You are the host of District $class-$district.</i>";
echo "<hr></caption>";

if(!PastDue(GetDueDate($sport),0) && !$secret)
{
   $date=split("-",GetDueDate($sport));
   echo "<tr align=center><td><br><br>The $sportname District Entry Forms are due on $date[1]/$date[2]/$date[0].<br><br>Please check back after this date to see the entries for your district.</td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}

//ENTRIES
echo "<tr align=left><td><b>District Entries:</b><ul>";
$sql="SELECT * FROM te_bschool WHERE (";
$sids=split(",",$sids);
for($i=0;$i<count($sids);$i++)
{
   $sids[$i]=trim($sids[$i]);
   $sql.="sid='$sids[$i]' OR ";
}
$sql=substr($sql,0,strlen($sql)-4).")";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<li><a href=\"view_".$sport.".php?sid=$row[sid]&session=$session\">".GetSchoolName($row[sid],$sport,GetFallYear($sport))."</a></li><br>";
}
echo "</ul></td></tr>";

//DISTRICT RESULTS LINK
echo "<tr align=left><td><a href=\"te_bdistresults.php?session=$session\">Enter Results for your District</a></td></tr>";

echo "</table>";

echo $end_html;
?>
