<?php
/********************************************************
coopappadmin.php
Coop Application Main Menu for NSAA
Created 8/28/12
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

//GET SCHOOL YEAR - Since Spring activities due by January 1, $year = date("Y") always, except for ON January 1
$year1=date("Y");
if(date("m")==1 && date("j")==1) $year1--;
$year2=$year1+1;
$year3=$year2+1;

if($delete)
{
   $sql="DELETE FROM coopapp WHERE id='$delete'";
   $result=mysql_query($sql);
   $sql="DELETE FROM coopschoolapp WHERE coopappid='$delete'";
   $result=mysql_query($sql);
   header("Location:coopappadmin.php?session=$session");
   exit();
}
if($unlock)
{
   $sql="UPDATE coopapp SET datesubtoNSAA=0 WHERE id='$unlock'";
   $result=mysql_query($sql);
   header("Location:coopappadmin.php?session=$session");
   exit();
}

echo $init_html;
echo $header;

echo "<br><table cellspacing=0 class='nine' cellpadding=5 frame=all rules=all style='border:#808080 1px solid;width:800px;'><caption><b>Cooperative Sponsorship Agreements</b><br><br></caption>";

//SUBMITTED TO NSAA, NOT ACTED UPON YET
$sql="SELECT * FROM coopapp WHERE datesubtoschools>0 AND datesubtoNSAA>0 AND execdate=0 ORDER BY datesubtoNSAA";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#ff0000'><td style='color:#ffffff;' colspan=4>Cooperative Agreements that have been SUBMITTED TO THE NSAA but have NOT yet been acted upon by the Board of Directors:</td></tr><tr align=center><td>CO-OPING SCHOOLS</td><td>ACTIVITIES</td><td>FORM</td><td>DELETE</td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $coopappid=$row[id];
      echo "<tr align=left><td width='250px'><b>".GetSchool2($row[schoolid1])."</b> (Head School)<br>".GetSchool2($row[schoolid2]);
      if($row[schoolid3]>0) echo "<br>".GetSchool2($row[schoolid3]);
      if($row[schoolid4]>0) echo "<br>".GetSchool2($row[schoolid4]);
      echo "</td><td width='250px'>";
      $actstring=""; $allacts=1;
      for($i=0;$i<count($coopsports);$i++)
      {
         if($row[$coopsports[$i]]=='x')
            $actstring.=GetActivityName($coopsports[$i]).", ";
         else
            $allacts=0;
      }
      $actstring=substr($actstring,0,strlen($actstring)-2);
      if($allacts==1) $actstring="All Activities";
      echo $actstring."</td>";
      echo "<td><p><a href=\"coopapp.php?session=$session&coopappid=$coopappid\" target=\"_blank\">View Submitted Cooperative Agreement</a></p><p>This agreement was submitted by ".GetSchool2($row[schoolid1])." on ".date("F j, Y",$row[datesubtoNSAA]).". The schools are awaiting action on this agreement by the NSAA Board of Directors.</p> [<a href=\"coopappadmin.php?session=$session&unlock=$row[id]\" class=small onClick=\"return confirm('Are you sure you want to unlock this form? The head school will need to re-submit it.');\">Unlock</a>]</td>";
      echo "<td align=center><a href=\"coopappadmin.php?session=$session&delete=$coopappid\" onClick=\"return confirm('Are you sure you want to delete this form? This action cannot be undone.');\">X</a></td>";
      echo "</tr>";
   }
}

//ACTED UPON
$sql="SELECT * FROM coopapp WHERE datesubtoschools>0 AND datesubtoNSAA>0 AND execdate>0 ORDER BY execdate desc";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#00ff00'><td style='color:#000000;' colspan=4>Cooperative Agreements that have been ACTED UPON by the NSAA Board of Directors:</td></tr><tr align=center><td>CO-OPING SCHOOLS</td><td>ACTIVITIES</td><td>FORM</td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $coopappid=$row[id];
      echo "<tr align=left><td width='250px'><b>".GetSchool2($row[schoolid1])."</b> (Head School)<br>".GetSchool2($row[schoolid2]);
      if($row[schoolid3]>0) echo "<br>".GetSchool2($row[schoolid3]);
      if($row[schoolid4]>0) echo "<br>".GetSchool2($row[schoolid4]);
      echo "</td><td width='250px'>";
      $actstring=""; $allacts=1;
      for($i=0;$i<count($coopsports);$i++)
      {
         if($row[$coopsports[$i]]=='x')
            $actstring.=GetActivityName($coopsports[$i]).", ";
         else
            $allacts=0;
      }
      $actstring=substr($actstring,0,strlen($actstring)-2);
      if($allacts==1) $actstring="All Activities";
      echo $actstring."</td>";
      echo "<td><a href=\"coopapp.php?session=$session&coopappid=$coopappid\" target=\"_blank\">View Submitted Cooperative Agreement</a><br>This agreement was ";
      if($row[approved]=='x') echo "<b>APPROVED</b>";
      else echo "<b>DENIED</b>";
      echo " on ".date("F j, Y",$row[execdate]).", by the NSAA Board of Directors.</td>";
      echo "</tr>";
   }
}

echo "</table>";

echo $end_html;

?>
