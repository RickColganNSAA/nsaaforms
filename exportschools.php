<?php
require '../calculate/functions.php';
require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

$txt="\"TEAM NAME\"\t\"CLASS\"\t\"HEAD SCHOOL\"\t\"REGISTRATION PAID\"\t\"CO-OPING SCHOOL 1\"\t\"REGISTRATION PAID\"\t\"CO-OPING SCHOOL 2\"\t\"REGISTRATION PAID\"\t\"CO-OPING SCHOOL 3\"\t\"REGISTRATION PAID\"";
if(!$sport) $sport='vb';
if($sport=='wr')
   $txt.="\"HYDRATION STRIPS\"\t";
$txt.="\r\n";
$sql="SELECT * FROM ".GetSchoolsTable($sport)." WHERE (school!='' OR mainsch>0)";
if($coopsonly==1) $sql.=" AND (othersch1>0 OR othersch2>0 OR othersch3>0)";
$sql.=" ORDER BY school";
$result=mysql_query($sql);
$html="<table><tr><td><b>TEAM NAME</b></td>";
if($sport=='sw') $html.="<td>HYTEK ABBREVIATION</td>";
$html.="<td>CLASS</td><td>HEAD SCHOOL</td><td>REGISTRATION PAID</td><td>CO-OPING SCHOOL 1</td><td>REGISTRATION PAID</td><td>CO-OPING SCHOOL 2</td><td>REGISTRATION PAID</td><td>CO-OPING SCHOOL 3</td><td>REGISTRATION PAID</td>";
if($sport=='wr') $html.="<td>HYDRATION STRIPS</td>";
$html.="</tr>";
while($row=mysql_fetch_array($result))
{
   if($coopsonly!=1 || GetSchool2($row[othersch1]) || GetSchool2($row[othersch2]) || GetSchool2($row[othersch3]))
   {
   $txt.="\"$row[school]\"\t\"$row[class]\"\t\"".GetSchool2($row[mainsch])."\"\t";
   $html.="<tr><td>$row[school]</td><td>$row[class]</td>";
   if($sport=='sw') $html.="<td>$row[hytekabbr]</td>";
   $html.="<td>".GetSchool2($row[mainsch])."</td>";
   if(IsRegistered2011($row[mainsch],$sport)) 
   {
      $txt.="\"PAID\"\t";
      $html.="<td>PAID ".GetRegistrationDatePaid($row[mainsch],$sport,1)."</td>";
   }
   else 
   {
      $txt.="\"\"\t";
      $html.="<td bgcolor='#ff0000'>NOT PAID</td>";
   }
   if($row[othersch1]>0 && GetSchool2($row[othersch1]))
   {
      $txt.="\"".GetSchool2($row[othersch1])."\"\t";
      $html.="<td>".GetSchool2($row[othersch1])."</td>";
      if(IsRegistered2011($row[othersch1],$sport)) 
      {
         $txt.="\"PAID\"\t";
         $html.="<td>PAID ".GetRegistrationDatePaid($row[othersch1],$sport,1)."</td>";
      }
      else 
      {
         $txt.="\"\"\t";
         $html.="<td bgcolor='#ff0000'>NOT PAID</td>";
      }
   }
   else 
   {
      $txt.="\"\"\t\"\"\t"; $html.="<td></td><td></td>";
   }
   if($row[othersch2]>0 && GetSchool2($row[othersch2]))
   {
      $txt.="\"".GetSchool2($row[othersch2])."\"\t";
      $html.="<td>".GetSchool2($row[othersch2])."</td>";
      if(IsRegistered2011($row[othersch2],$sport)) 
      {
         $txt.="\"PAID\"\t";
         $html.="<td>PAID ".GetRegistrationDatePaid($row[othersch2],$sport,1)."</td>";
      }
      else
      {
         $txt.="\"\"\t";
         $html.="<td bgcolor='#ff0000'>NOT PAID</td>";
      }
   }
   else 
   {
      $txt.="\"\"\t\"\"\t"; $html.="<td></td><td></td>";
   }
   if($row[othersch3]>0 && GetSchool2($row[othersch3]))
   {
      $txt.="\"".GetSchool2($row[othersch3])."\"\t";
      $html.="<td>".GetSchool2($row[othersch3])."</td>";
      if(IsRegistered2011($row[othersch3],$sport)) 
      {
         $txt.="\"PAID\"\t";
         $html.="<td>PAID ".GetRegistrationDatePaid($row[othersch3],$sport,1)."</td>";
      }
      else
      {
         $txt.="\"\"\t";
         $html.="<td bgcolor='#ff0000'>NOT PAID</td>";
      }
   }
   else 
   {
      $txt.="\"\"\t\"\"\t";
      $html.="<td></td><td></td>";
   }
   if($sport=='wr')	//CHECK schoolregistration TABLE FOR wrfee2='x' (Hydration Strips)
   {
      $sql2="SELECT * FROM schoolregistration WHERE schoolid='$row[mainsch]' AND wrfee2='x'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0) { $txt.="\"X\"\t"; $html.="<td>X</td>"; }
      else { $txt.="\"\"\t"; $html.="<td></td>"; }
   }
   $txt.="\r\n";
   $html.="</tr>";
   }
}
$html.="</table>";

$filename=strtoupper($sport)."SchoolsExport".date("_m_d_Y").".xls";
if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w"))
{
   echo "Could not open $filename"; exit();
}
if(!fwrite($open,$html))
{
   echo "Could not write to $filename"; exit();
}
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
//echo "Redirecting to $session, $filename"; 
header("Location:exports.php?session=".$session."&filename=".$filename);
exit();
?>
