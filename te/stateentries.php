<?php
/*******************************************
stateentries.php
Export State Entries
Created 9/29/08
Author: Ann Gaffigan
********************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';
require 'tefunctions.php';

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
if(!$sport || $sport=='te_b')
{
   $sport='te_b';
   $sportname="Boys Tennis";
   $gender="M";
}
else
{
   $sportname="Girls Tennis";
   $gender="F";
}
$schooltable=$sport."school";
$meettable=$sport."meets";
$resultstable=$sport."meetresults";

if(!$divch && !$class)
{
echo $init_html;
echo $header;

$sql="SELECT DISTINCT division FROM ".$sport."state ORDER BY division";
$result=mysql_query($sql);
$divch=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   $divch[$ix]=$row[0]; $ix++;
}

echo "<br><br><table width=\"400px\" class=nine><caption><b>".$sportname." State Entries by Class & Division:</b><br><a class=small href=\"".$sport."main.php?session=$session\">Return to $sportname Main Menu</a><hr></caption>";
$classes=GetClasses($sport);
echo "<tr align=left valign=top>";
for($c=0;$c<count($classes);$c++)
{
   echo "<td><b>Class ".$classes[$c].":</b><br><br>";
   for($d=0;$d<count($divch);$d++)
   {
      if(ereg("singles",$divch[$d]))
      {
         $basedivision="singles";
         $temp=split("singles",$divch[$d]);
         $divshow="#".$temp[1]." Singles";
      }
      else if(ereg("doubles",$divch[$d]))
      {
         $basedivision="doubles";
         $temp=split("doubles",$divch[$d]);
         $divshow="#".$temp[1]." Doubles";
      }
      else
      {
         $basedivision="singles"; $divshow="Substitute";
      }
      echo "<a href=\"stateentries.php?sport=$sport&session=$session&class=$classes[$c]&divch=$divch[$d]\" target=\"_blank\">$divshow</a><br><br>";
   }//end for each division
   echo "</td>";
}
echo "</tr></table>";
echo $end_html;
exit();
}//end if no div or class selected

//IF WE GET HERE, THEN A CLASS/DIVISION WAS CLICKED ON

$html=$init_html;
if(ereg("singles",$divch)) 
{
   $basedivision="singles";
   $temp=split("singles",$divch);
   $divshow="#".$temp[1]." Singles"; 
}
else if(ereg("doubles",$divch))
{
   $basedivision="doubles";
   $temp=split("doubles",$divch);
   $divshow="#".$temp[1]." Doubles";
}
else 
{
   $basedivision="singles"; $divshow="Substitute";
}
if($divch!="substitute")
{
//if($class=="A" || $sport=='te_b') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
   $sql="SELECT t1.* FROM ".$sport."state AS t1,$schooltable AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.division='$divch'";
//else
 //  $sql="SELECT * FROM ".$sport."distresults WHERE division='$divch'";
//echo $sql;
$result=mysql_query($sql);
$entries=array(); $ix=0;
while($row=mysql_fetch_array($result))	//FOR EACH STUDENT ENTERED IN THIS DIVISION
{
   $record=GetRecord($sport,$divch,"Varsity",$row[player1],$row[player2]);
   $rec=split("-",$record);
   $games=$rec[0]+$rec[1];
   $lossperc=number_format(($rec[1]/$games)*100,3,'.','');
   if($lossperc<10) $lossperc="00".$lossperc;
   else if($lossperc<100) $lossperc="0".$lossperc;
   $entries[$ix]=$lossperc.";".$row[id];
   $ix++;
}
sort($entries);
$string="<table frames=all rules=all cellspacing=0 cellpadding=3 style=\"border:#000000 1px solid;\">";
$string.="<caption><b>CLASS $class $divshow</b></caption>";
for($i=0;$i<count($entries);$i++)
{
   $temp=split(";",$entries[$i]);
   $lossperc=$temp[0];
   //if($class=="A" || $sport=='te_b') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
      $sql="SELECT * FROM ".$sport."state WHERE id='$temp[1]'";
   //else
     // $sql="SELECT * FROM ".$sport."distresults WHERE id='$temp[1]'"; 
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   //GET ALL RESULTS FOR CURRENT PLAYER(s) IN THIS DIVISION CATEGORY
   if(ereg("singles",$row[division]))
   {
      $temp=split("singles",$row[division]);
      $divshow="#".$temp[1]." SINGLES";
   }
   else if(ereg("doubles",$row[division]))
   {
      $temp=split("doubles",$row[division]);
      $divshow="#".$temp[1]." DOUBLES";
   }
   else $divshow="Substitute";
   $num=$i+1;
   $string.="<tr align=left><td>$num.</td>";
   $sql2="SELECT first,last,semesters,school FROM eligibility WHERE id='$row[player1]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(ereg("\(",$row2[first]))
   {
      $first_nick=split("\(",$row2[first]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
   }
   else $first=$row2[first];
   $string.="<td>$first $row2[last] (".GetYear($row2[semesters]).")";
   if(ereg("doubles",$row[division]))
   {
      $sql2="SELECT first,last,semesters,school FROM eligibility WHERE id='$row[player2]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg("\(",$row2[first]))
      {
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $string.=", $first $row2[last] (".GetYear($row2[semesters]).")";
   }
   $string.="</td><td>".GetSchoolName(GetSID2($row2[school],$sport,date("Y")),$sport,date("Y"))."</td>";
   $string.="<td>".GetRecord($sport,$divch,"Varsity",$row[player1],$row[player2])."</td></tr>";
}
$html.="<div>".$string."</table></div><div style=\"page-break-after:always;\"><br><br></div>";

for($i=0;$i<count($entries);$i++)
{
   $temp=split(";",$entries[$i]);
   $lossperc=$temp[0];
   //if($class=="A" || $sport=='te_b') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
      $sql="SELECT * FROM ".$sport."state WHERE id='$temp[1]'";
   //else
     // $sql="SELECT * FROM ".$sport."distresults WHERE id='$temp[1]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   //GET ALL RESULTS FOR CURRENT PLAYER(s) IN THIS DIVISION CATEGORY
   if(ereg("singles",$row[division]))
   {
      $temp=split("singles",$row[division]);
      $divshow="#".$temp[1]." SINGLES";
   }
   else if(ereg("doubles",$row[division]))
   {
      $temp=split("doubles",$row[division]);
      $divshow="#".$temp[1]." DOUBLES";
   }
   else $divshow="Substitute";
   $string="<div style=\"width:800px\"><table width=\"100%\"><tr><td align=left><td><font style=\"font-size:10pt;\"><b>CLASS $class $divshow: ";
   $sql2="SELECT first,last,semesters,school FROM eligibility WHERE id='$row[player1]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(ereg("\(",$row2[first]))
   {       
      $first_nick=split("\(",$row2[first]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
   }
   else $first=$row2[first];
   $string.=GetSchoolName(GetSID2($row2[school],$sport,date("Y")),$sport,date("Y")).": ";
   $string.="$first $row2[last] (".GetYear($row2[semesters]).")";
   if(ereg("doubles",$row[division]))
   {
      $sql2="SELECT first,last,semesters,school FROM eligibility WHERE id='$row[player2]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg("\(",$row2[first]))
      {   
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $string.=", $first $row2[last] (".GetYear($row2[semesters]).")";
   }
   $string.="</b></font></td><td align=right><div class=normalwhite><font style=\"font-size:10pt\"><b>$divshow Record: ".GetRecord($sport,$divch,"Varsity",$row[player1],$row[player2])."</b></font></div></td></tr></table></div><br>";
   if(ereg("doubles",$row[division]))
      $string.=GetAllResults($sport,$class,$divch,$row[sid],$row[player1],$row[player2],'1');
   else
      $string.=GetAllResults($sport,$class,$divch,$row[sid],$row[player1],0,1);
   $html.=$string."<div style=\"page-break-after:always;\"><br><br></div>";
}//end for each student entered in this division
}//end if not substitute
else	//SUBSTITUTE
{
//if($class=="A" || $sport=='te_b') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
   $sql="SELECT t1.* FROM ".$sport."state AS t1,$schooltable AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.division='$divch'";
//else
   //$sql="SELECT * FROM ".$sport."distresults WHERE division='$divch'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)) 
{
   $string="<div style=\"width:800px\"><table width=\"100%\"><tr><td align=left><td><font style=\"font-size:10pt;\"><b>CLASS $class SUBSTITUTE: ";
   $sql2="SELECT first,last,semesters,school FROM eligibility WHERE id='$row[player1]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(ereg("\(",$row2[first]))
   {
      $first_nick=split("\(",$row2[first]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
   }
   else $first=$row2[first];
   $string.=GetSchoolName(GetSID2($row2[school],$sport,date("Y")),$sport,date("Y")).": ";
   $string.="$first $row2[last] (".GetYear($row2[semesters]).")";
   $string.="</b></font></td></tr></table></div><br>";
   $string.=GetAllResults($sport,$class,"singles1",$row[sid],$row[player1],0,1);
   $html.=$string."<div style=\"page-break-after:always;\"><br><br></div>";
}
}
echo $html.$end_html2;
/*
      $filename=ereg_replace("[^a-zA-Z]","",$sportname)."Class".$classes[$c].strtoupper($divch).".html";
      $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
      fwrite($open,$html);
      fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
*/
?>
