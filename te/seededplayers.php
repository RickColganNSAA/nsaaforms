<?php

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'tefunctions.php';

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
if(!$sport) $sport="te_b";
$sportname=GetActivityName($sport);
$html=$init_html."<table cellspacing=0 cellpadding=3 class=eight>";
$html.="<caption><b>NSAA $sportname Class $class Seeded Players</b><br><br></caption>";
$divch=array("singles1","doubles1","singles2","doubles2");
$divch2=array("#1 Singles","#1 Doubles","#2 Singles","#2 Doubles");
$html.="<tr align=left><td>";
for($d=0;$d<count($divch);$d++)
{
   $html.="<b>".strtoupper($divch2[$d])."</b><br>";
for($i=0;$i<12;$i++)
{
   $curseed=$i+1;
   $sql="SELECT * FROM ".$sport."seeds WHERE seed='$curseed' AND class='$class' AND division='$divch[$d]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[hideseed]!='x')
   {
   $html.=$curseed.".&nbsp;";
   $sql2="SELECT * FROM eligibility WHERE id='$row[player1]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(ereg("\(",$row2[first]))
   {
      $first_nick=split("\(",$row2[first]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
   }
   else $first=$row2[first];
   $name="$first $row2[last]";
   if(ereg("doubles",$divch[$d]))
   {
      $sql2="SELECT * FROM eligibility WHERE id='$row[player2]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg("\(",$row2[first]))
      {
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $name.="/$first $row2[last]";
   }
   $sid=GetSID2($row2[school],$sport);
   $record="(".GetRecord($sport,$row[division],'Varsity',$row[player1],$row[player2]).")";
   if($record=="(0-0)") $record="";
   $html.="$name, ".GetSchoolName($sid,$sport,date("Y"))." $record<br>";
   }
}
   $html.="<br>";
}
$html.="</td></tr>";
$html.="</table>";
$filename=ereg_replace("_","",$sport)."Class".$class."Seeded.html";
$open=fopen(citgf_fopen("previews/".$filename),"w");
fwrite($open,$html.$end_html);
fclose($open); 
 citgf_makepublic("previews/".$filename);
echo ereg_replace("<br></caption>","<a href=\"publish.php?session=$session&filename=$filename\">Publish to NSAA Website</a><br><br></caption>",$html).$end_html;

?>
