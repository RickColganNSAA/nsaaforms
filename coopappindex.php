<?php
/********************************************************
coopappindex.php
Coop Application Main Menu for Schools
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
if(!ValidUser($session) || $level>2)
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid || $level!=1)
{
   $schoolid=GetSchoolID($session);
   if($level==1) $schoolid=1616;        //Test's School
}
$school=GetSchool2($schoolid);
$school2=ereg_replace("\'","\'",$school);
//GET SCHOOL YEAR - Since Spring activities due by January 1, $year = date("Y") always, except for ON January 1
$year1=date("Y");
if(date("m")==1 && date("j")==1) $year1--;
$year2=$year1+1;
$year3=$year2+1;

if($unlock)
{
   $sql="UPDATE coopschoolapp SET datesub=0 WHERE id='$unlock'";
   $result=mysql_query($sql);
   header("Location:coopappindex.php?session=$session");
   exit();
}
if($delete)
{
   $sql="DELETE FROM coopschoolapp WHERE coopappid='$delete'";
   $result=mysql_query($sql);
   $sql="DELETE FROM coopapp WHERE id='$delete'";
   $result=mysql_query($sql);
}

echo $init_html;
echo $header;

echo "<br><table cellspacing=0 class='nine' cellpadding=5 frame=all rules=all style='border:#808080 1px solid;width:800px;'><caption><b>Cooperative Sponsorship Agreements</b><br>";
echo "<p style='text-align:left;padding:5px;margin:10px;'><a href=\"coopapp.php?session=$session\">+ Begin Working on a NEW Cooperative Sponsorship Agreement</a></p>";
echo "<p style='padding:5px;margin:5px 10px 0px 10px;text-align:left;'><a href=\"CoopAppInfo.pdf\" target=\"_blank\">Guidelines for Cooperative Sponsorships (PDF)</a></p><br>";
echo "</caption>";

//IN PROGRESS, YOU ARE THE HEAD SCHOOL OR YOU ARE A CO-OPING SCHOOL
$sql="SELECT * FROM coopapp WHERE (schoolid1='$schoolid' OR schoolid2='$schoolid' OR schoolid3='$schoolid' OR schoolid4='$schoolid') AND datesubtoschools>0 AND datesubtoNSAA=0 ORDER BY datesubtoschools";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#ff0000'><td style='color:#ffffff;' colspan=3>Cooperative Agreements that are IN PROGRESS and have NOT been submitted to the NSAA yet:</td></tr><tr align=center><td>CO-OPING SCHOOLS</td><td>ACTIVITIES</td><td>FORMS</td></tr>";
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
      echo "<td>";
      if($schoolid==$row[schoolid1])	//IS HEAD SCHOOL
      {
         echo "<p><a href=\"coopapp.php?session=$session&coopappid=$coopappid\">Edit the Main Agreement Form</a>&nbsp;&nbsp;&nbsp;";
         echo "<a href=\"coopappindex.php?session=$session&delete=$coopappid\" style=\"background-color:#ff0000;color:#ffffff;text-decoration:none;\" onClick=\"return confirm('Are you sure you want to delete this entire Agreement?');\">&nbsp;X&nbsp;</a></p>";
         echo "<p><b>Individual Schools' Resolution Forms:</b><ul class='small'>";
               $sql2="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid' AND datesub>0";
               $result2=mysql_query($sql2);
  	 echo "<li><a target=\"_blank\" href=\"coopschoolapp.php?session=$session&coopappid=$coopappid&schoolid=$row[schoolid1]";
         if(mysql_num_rows($result2)>0) echo "&print=1";
	 echo "\" class=small>Your School</a>";
               if(mysql_num_rows($result2)==0)  //HAS NOT SUBMITTED THIS YET
		  echo " <label class='red'>Incomplete</label>";
	       else
		  echo " <label class='green'>Completed</label>";
         echo "</li>";
         for($i=2;$i<=4;$i++)
         {
	    $var="schoolid".$i;
            if($row[$var]>0)
            {
	       echo "<li><a class='small' href=\"coopschoolapp.php?session=$session&coopappid=$coopappid&schoolid=".$row[$var]."\" target=\"_blank\">".GetSchool2($row[$var])."</a>";
	       $sql2="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='".$row[$var]."' AND datesub>0";
	       $result2=mysql_query($sql2);
	       if(mysql_num_rows($result2)==0)	//HAS NOT SUBMITTED THIS YET
		  echo " <label class='red'>Incomplete</label>";
	       else
	       {
		  $row2=mysql_fetch_array($result2);
		  echo " <label class='green'>Completed</label> [<a href=\"coopappindex.php?session=$session&unlock=$row2[id]\" class=small onClick=\"return confirm('Are you sure you want to unlock this form? The school will need to re-submit it.');\">Unlock</a>]";
	       }
	       echo "</li>";
	    }
         }
         echo "</ul></p>";
      }
      else	//IS NOT HEAD SCHOOL
      {
         echo "<p><a href=\"coopapp.php?session=$session&coopappid=$coopappid\" target=\"_blank\">View the Main Agreement Form</a><br>(filled out by ".GetSchool2($row[schoolid1]).")</p>";
               $sql2="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid' AND datesub>0";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0)  //HAS SUBMITTED THIS
	       {
		  $row2=mysql_fetch_array($result2);
         	  echo "<p><a href=\"coopschoolapp.php?session=$session&coopappid=$coopappid&schoolid=$schoolid\" target=\"_blank\">View your School's Submitted Form & Resolution</a><br>(Your school submitted this form to ".GetSchool2($row[schoolid1])." on ".date("F j, Y",$row2[datesub]).". To make corrections, please contact ".GetSchool2($row[schoolid1])." to unlock this form for you.)</p>";
	       }
	       else
		  echo "<p><a href=\"coopschoolapp.php?session=$session&coopappid=$coopappid&schoolid=$schoolid\" style=\"background-color:yellow;\">Complete your School's Form & Resolution</a><br>(The coop agreement CANNOT be submitted to the NSAA until all co-oping schools have completed their own form and resolution.)</p>";
      }
      echo "</td>";
      echo "</tr>";
   }
   echo "</ul></td></tr>";
}

//SUBMITTED TO NSAA
$sql="SELECT * FROM coopapp WHERE (schoolid1='$schoolid' OR schoolid2='$schoolid' OR schoolid3='$schoolid' OR schoolid4='$schoolid') AND datesubtoschools>0 AND datesubtoNSAA>0 AND execdate=0 ORDER BY datesubtoNSAA";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#ffff33'><td style='color:#000000;' colspan=3>Cooperative Agreements that have been SUBMITTED TO THE NSAA but have NOT yet been acted upon by the NSAA:</td></tr><tr align=center><td>CO-OPING SCHOOLS</td><td>ACTIVITIES</td><td>FORM</td></tr>";
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
      echo "<td><p><a href=\"coopapp.php?session=$session&coopappid=$coopappid\" target=\"_blank\">View Submitted Cooperative Agreement</a></p><p>You are awaiting action on this agreement by the NSAA Board of Directors.</p></td>";
      echo "</tr>";
   }
}

//ACTED UPON
$sql="SELECT * FROM coopapp WHERE (schoolid1='$schoolid' OR schoolid2='$schoolid' OR schoolid3='$schoolid' OR schoolid4='$schoolid') AND datesubtoschools>0 AND datesubtoNSAA>0 AND execdate>0 ORDER BY execdate";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left bgcolor='#00ff00'><td style='color:#000000;' colspan=3>Cooperative Agreements that have been ACTED UPON by the NSAA Board of Directors:</td></tr><tr align=center><td>CO-OPING SCHOOLS</td><td>ACTIVITIES</td><td>FORM</td></tr>";
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
      echo "<td><a href=\"coopapp.php?session=$session&coopappid=$coopappid\" target=\"_blank\">View Submitted Cooperative Agreement</a><br>This Agreement was ";
      if($row[approved]=='x') echo "<b>APPROVED</b>";
      else echo "<b>DENIED</b>";
      echo " on ".date("F j, Y",$row[execdate]).", by the NSAA Board of Directors.</td>";
      echo "</tr>";
   }
}

echo "</table>";

echo $end_html;

?>
