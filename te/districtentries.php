<?php
/*******************************************
districtentries.php
Export District Entries
Created 6/29/09
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
if(!ValidUser($session))
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
$districts=ereg_replace("_","",$sport)."districts";

//Get District this User's School Hosts:
$hostsch=GetSchool($session);
$hostsch2=addslashes($hostsch);
$sql="SELECT id FROM logins WHERE school='$hostsch2' AND level='$level'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostid=$row[0];
$sql="SELECT * FROM $db_name2.$districts WHERE hostid='$hostid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "You are not the host of a $sportname District.";
   exit();
}
$row=mysql_fetch_array($result);
$distid=$row[id]; $class=$row['class']; $district=$row[district];

if(!$divch || $divch=="") $divch="singles1";

$html=$init_html;

//Settings according to Division:
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
   //THIS WORKS JUST LIKE STATE ENTRIES EXPORT 
   $sql="SELECT t1.* FROM ".$sport." AS t1,$schooltable AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.division='$divch'";
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
   $string.="<caption><b>District $class-$district $divshow</b></caption>";
   for($i=0;$i<count($entries);$i++)
   {
      $temp=split(";",$entries[$i]);
      $lossperc=$temp[0];
      $sql="SELECT * FROM ".$sport." WHERE id='$temp[1]'";
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
      $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player1]'";
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
         $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player2]'";
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
      $string.="</td><td>".GetSchoolName($row[sid],$sport,date("Y"))."</td>";
      $string.="<td>".GetRecord($sport,$divch,"Varsity",$row[player1],$row[player2])."</td></tr>";
   }
   
   $html.="<div>".$string."</table></div><div style=\"page-break-after:always;\"><br><br></div>";

   for($i=0;$i<count($entries);$i++)
   {
      $temp=split(";",$entries[$i]);
      $lossperc=$temp[0];
      $sql="SELECT * FROM ".$sport." WHERE id='$temp[1]'";
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
      $string="<div style=\"width:800px\"><table width=\"100%\"><tr><td align=left><td><font style=\"font-size:10pt;\"><b>CLASS $class $divshow: ".GetSchoolName($row[sid],$sport,date("Y")).": ";
      $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg("\(",$row2[first]))
      {       
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $string.="$first $row2[last] (".GetYear($row2[semesters]).")";
      if(ereg("doubles",$row[division]))
      {
         $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player2]'";
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
   $sql="SELECT t1.* FROM ".$sport." AS t1,$schooltable AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.division='$divch'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result)) 
   {
      $string="<div style=\"width:800px\"><table width=\"100%\"><tr><td align=left><td><font style=\"font-size:10pt;\"><b>CLASS $class SUBSTITUTE: ".GetSchoolName($row[sid],$sport,date("Y")).": ";
      $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player1]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg("\(",$row2[first]))
      {
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $string.="$first $row2[last] (".GetYear($row2[semesters]).")";
      $string.="</b></font></td></tr></table></div><br>";
      $string.=GetAllResults($sport,$class,"singles1",$row[sid],$row[player1],0,1);
      $html.=$string."<div style=\"page-break-after:always;\"><br><br></div>";
   }
}
echo $html.$end_html2;
?>
