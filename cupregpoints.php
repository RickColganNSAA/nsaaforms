<?php
/*********************************
cupregpoints.php
NSAA can calculate Participation points
by season from the Registration data
Author: Ann Gaffigan
Created: 9/29/15
*********************************/

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
   header("Location:../index.php");
   exit();
}
/* $db_name='nsaascores20172018';
$db=mysql_connect($db_host,$db_user,$db_pass); */
$regpts=GetCupPointAmount(0);

if($pull==1)
{
   $sql2="SELECT schoolid FROM cupschools";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))        
   {
      $sql="SELECT activity FROM cupactivities";
      if($season && $season!='') $sql.=" WHERE season='$season'";
      else if($sport && $sport!='') $sql.=" WHERE activity='$sport'";
      $result=mysql_query($sql);
      if(mysql_error()) echo mysql_error()."<br>";
      while($row=mysql_fetch_array($result))
      {
	 //Make sure to pull from the right 'schoolregistration' table
         if(IsRegistered2011($row2[schoolid],$row[activity],'',FALSE,GetCupRegistrationTable())) $participating='x';
         else $participating="";
         $sql3="SELECT * FROM cupschoolsactivities WHERE schoolid='$row2[schoolid]' AND activity='$row[activity]'";
         $result3=mysql_query($sql3);
         echo mysql_error();
         if(mysql_num_rows($result3)==0)
            $sql3="INSERT INTO cupschoolsactivities (schoolid,activity,participating) VALUES ('".$row2['schoolid']."','$row[activity]','$participating')";
         else
         {
            $row3=mysql_fetch_array($result3);
            $sql3="UPDATE cupschoolsactivities SET participating='$participating' WHERE id='$row3[id]'";
         }
         $result3=mysql_query($sql3);
         echo mysql_error();
      
         //UPDATE REGISTRATION POINTS FOR THIS SCHOOL/SPORT
	 if($participating=='x')
	 {
            $sql3="SELECT * FROM cuppoints WHERE schoolid='$row2[schoolid]' AND activity='$row[activity]' AND class='reg'";
            $result3=mysql_query($sql3);
            echo mysql_error();
            if(mysql_num_rows($result3)==0)
               $sql3="INSERT INTO cuppoints (class,points,schoolid,activity) VALUES ('reg','$regpts','$row2[schoolid]','$row[activity]')";
            else
            {
               $row3=mysql_fetch_array($result3);
               $sql3="UPDATE cuppoints SET points='$regpts' WHERE id='$row3[id]'";
            }
            $result3=mysql_query($sql3);
         }
      } //END FOR EACH activity
      //UPDATE OVERALL POINTS FOR THIS SCHOOL
      UpdateCupPointTotals($row2[schoolid]);
   } //END FOR EACH SCHOOL

   if($season && $season!='')
   {
      $sql="SELECT * FROM cupregptsettings WHERE season='$season'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
         $sql="INSERT INTO cupregptsettings (lastupdate,season) VALUES ('".time()."','$season')";
      else
         $sql="UPDATE cupregptsettings SET lastupdate='".time()."' WHERE season='$season'";
      $result=mysql_query($sql);
   }
   else if($sport && $sport!='')
   {
      $sql="SELECT * FROM cupregptsettings WHERE activity='$sport'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
         $sql="INSERT INTO cupregptsettings (lastupdate,activity) VALUES ('".time()."','$sport')";
      else
         $sql="UPDATE cupregptsettings SET lastupdate='".time()."' WHERE activity='$sport'";
      $result=mysql_query($sql);
   }

   if($refer=="cupplaces")
      header("Location: cupplaces.php?session=$session&sport=$sport&class=$class&pulled=1");
}

echo $init_html;
echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a href=\"cupadmin.php?session=$session\">&larr; Return to NSAA Cup Main Menu</a></p>";

echo "<h1>NSAA Cup: Participation Points</h1>";

if($pull==1)
{
   if($season && $season!='')
      echo "<div class='alert'>The participation points for that season have been generated.</div>";
   else if($sport && $sport!='')
      echo "<div class='alert'>The participation points for that activity have been generated.</div>";
}

echo "<div style=\"text-align:left;width:600px;\">
	<p>Use the links below to pre-populate participation points for the NSAA Cup based on the registration data in the eligibility database, one season OR one activity at a time.</p><p>Registration points are currently set at <b><u>$regpts points</b></u> per school per activitiy. You can manage point settings <a href=\"cupsettings.php?session=$session&type=points\">here</a>.</p>";
$sql="SELECT * FROM cupactivities WHERE activity!='wrd' ORDER BY orderby";
$result=mysql_query($sql);
$curseason="";
while($row=mysql_fetch_array($result))
{
   if($curseason!=$row[season])
   {
      if($curseason!='') echo "</ul>";
      $sql2="SELECT * FROM cupregptsettings WHERE season='$row[season]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<h2>$row2[seasonname]:</h2>";
      echo "<ul>";
      $curseason=$row[season];
   }
   echo "<li><b>".GetActivityName($row[activity]).":</b>";
   $sql2="SELECT * FROM cupregptsettings WHERE activity='$row[activity]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(!$row2[lastupdate])
      echo "<p><a href=\"cupregpoints.php?session=$session&pull=1&sport=$row[activity]\">Pull participation points for ".GetActivityName($row[activity])."&rarr;</a> (please be patient)</p>";
   else
      echo "<p>Last pull took place on ".date("m/d/y",$row2[lastupdate])." at ".date("g:ia",$row2[lastupdate]).". <a class=\"small\" href=\"cupregpoints.php?session=$session&pull=1&sport=$row[activity]\">Pull participation points again*</a> <i>(please be patient)</i></p>";
   echo "</li>";
}
echo "</ul>";
echo "<p>* <i>Please note that this will override any participation checkmarks you have edited for schools in the NSAA Cup program for any of the activities for this activity.</i></p>";
echo "</div>";

?>
