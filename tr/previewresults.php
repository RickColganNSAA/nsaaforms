<?php
//previewresults.php: NSAA Track & Field - Admin can preview results that will go to website
//Created 11/17/09
//Author: Ann Gaffigan

require '../functions.php';
require '../variables.php';
require 'trfunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

$db1="nsaascores";
$db2="nsaaofficials";

//DISTRICT INFORMATION AT THE TOP:
$sql="SELECT * FROM $db2.trbdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: District ID# $distid NOT FOUND.";
   exit();
}
$class=$row['class'];
$district=$row[district];
        //GET GIRLS DISTID
        $sql2="SELECT * FROM $db_name2.trgdistricts WHERE class='$row[class]' AND district='$row[district]'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        $gdistid=$row2[id];
$info=$init_html."<br><table width='100%'><caption><b>".date("Y")." NSAA Track & Field District $row[class]-$row[district] Results:</b><br>";
$day=split("-",$row[dates]);
$info.=date("l, F j, Y",mktime(0,0,0,$day[1],$day[2],$day[0]))."<br>";
$info.="Host: $row[hostschool]<br>Site: $row[site]<br><br>";
$info.="<b><u>Please contact the NSAA office with ANY changes/corrections. E-mail NSAA (nsaa@nsaahome.org) or call (402) 489-0386 immediately.</u></b><br><br>";
$info.="<i>This page was last updated ".date("m/d/Y")." at ".date("g:ia T").".</i>";
$info.="</caption>";
$info.="<tr align=center><td>";

$info.="<table class=nine cellspacing=3 cellpadding=3>";

//RESULTS: TEAM SCORES FIRST
$info.="<tr align=left><td><b>GIRLS TEAM SCORES:</b><br>";
$sql="SELECT teamscores,teams,indys FROM $db1.tr_state_dist_g WHERE dist='$gdistid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$teamscores=$row[0];
$teams=$row[1]; $indys=$row[2];
$teamdst=split("<br>",$teamscores);
$teamscores="";
for($i=0;$i<count($teamdst);$i++)
{
   $place=$i+1;
   $teamdst[$i]=split(",",$teamdst[$i]);
   $teamname=GetSchoolName($teamdst[$i][0],'trg');
   if($teamname!='')
      $teamscores.=$place.".&nbsp;".$teamname.", ".trim($teamdst[$i][1])."<br>";
}
$info.=$teamscores;
$info.="</td></tr>";
$info.="<tr align=left><td><b>BOYS TEAM SCORES:</b><br>";
$sql="SELECT teamscores,teams,indys FROM $db1.tr_state_dist_b WHERE dist='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$teamscores=$row[0];
$teams=$row[1]; $indys=$row[2];
$teamdst=split("<br>",$teamscores);
$teamscores="";
for($i=0;$i<count($teamdst);$i++)
{
   $place=$i+1;
   $teamdst[$i]=split(",",$teamdst[$i]);
   $teamname=GetSchoolName($teamdst[$i][0],'trb');
   if($teamname=="Omaha Roncalli Catholic/Duchesne Academy") $teamname="Omaha Roncalli Catholic";
   if($teamname!='')      
      $teamscores.=$place.".&nbsp;".$teamname.", ".trim($teamdst[$i][1])."<br>";
}
$info.=$teamscores;
$info.="</td></tr>";

//INDIVIDUAL RESULTS, GIRLS FIRST, THEN BOYS
$info.="<tr align=left><td>";
	//GIRLS
for($i=0;$i<count($trevents_g);$i++)
{
   //if($trevents_g[$i]!='extraqual' && $trevents_g[$i]!='teamscores')
   if($trevents_g[$i]!='teamscores')
   {
      $info.=GetResults($gdistid,'g',$trevents_g[$i]);
      $info.="<br>";
   }
}
for($i=0;$i<count($trevents);$i++)
{
   //if($trevents[$i]!='extraqual' && $trevents[$i]!='teamscores')
	//BOYS
   if($trevents[$i]!='teamscores')
   {
      $info.=GetResults($distid,'b',$trevents[$i]);
      $info.="<br>";
   } 
}
$info.="</td></tr>";

$info.="</table>";

$info.=$end_html;

$filename=$class.$district."RESULTS.html";
$open=fopen(citgf_fopen("previews/".$filename),"w");
fwrite($open,$info);
fclose($open); 
 citgf_makepublic("previews/".$filename);

//echo ereg_replace($init_html,$init_html."<a href=\"publish.php?filename=$filename&session=$session\">Publish these Results to the NSAA Website</a><br><br>",$info);
echo ereg_replace("<br><table width='100%'><caption><b>","<br><center><a href=\"publish.php?filename=$filename&session=$session\">Publish these Results to the NSAA Website</a></center><br><table width='100%'><caption><b>",$info);
?>
