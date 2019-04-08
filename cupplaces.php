<?php
/*********************************
cupplaces.php
NSAA can enter Top 8 in each class
to pre-populate the points for NSAA Cup
Author: Ann Gaffigan
Created: 8/17/15
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
/****** SAVE OR RESET TOP 8 ******/
if($save)
{
   if($class=="NOCLASS") $class="";
   for($i=0;$i<count($id);$i++)
   {
      if($sid[$i]==0 && $id[$i]>0)
         $sql="DELETE FROM cupplaces WHERE id='$id[$i]'";
      else if($sid[$i]==0) $sql="";
      else if($id[$i]==0)	//INSERT
         $sql="INSERT INTO cupplaces (class,place, sid, activity) VALUES ('$class','$place[$i]','$sid[$i]','$sport')";
      else
	 $sql="UPDATE cupplaces SET place='$place[$i]', sid='$sid[$i]' WHERE id='$id[$i]' AND activity='$sport'";
      if($sql!='')
         $result=mysql_query($sql);
   }
   //NOW ASSIGN POINTS TO cuppoints TABLE
   $message=CupAssignPoints($sport,$class,TRUE);
}
else if($reset)
{
   for($i=0;$i<count($id);$i++)
   {
      $sql="DELETE FROM cupplaces WHERE id='$id[$i]'";
      $result=mysql_query($sql);
   }
   //NOW ASSIGN POINTS TO cuppoints TABLE
   if($class=="NOCLASS") $class="";
   $message=CupAssignPoints($sport,$class,TRUE);
}
else $message="";

/****** ENTER TOP 8 FOR A CERTAIN ACTIVITY AND CLASS ******/

echo $init_html;

echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a href=\"cupadmin.php?session=$session\">&larr; Return to NSAA Cup Main Menu</a></p>";
if($sport && $sport!='')
   echo "<p style=\"text-align:left;\"><a href=\"cupplaces.php?session=$session\">&larr; Return to NSAA Cup State Championships Main Menu</a></p>";

echo "<h1>NSAA Cup: State Championship Results</h1>";

if($save || $reset)
{
   if($message!='')
      echo "<div class=\"alert\">$message</div>";
   else
      echo "<div class=\"alert\">Your changes have been saved.</div>";
}

echo "<form method=\"post\" action=\"cupplaces.php\">
	<input type=hidden name=\"session\" value=\"$session\">
	<select name=\"sport\" onChange=\"submit();\"><option value=\"\">Select an Activity</option>";
$sql="SELECT * FROM cupactivities";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[activity]\"";
   if($sport==$row[activity]) echo " selected";
   echo ">".GetActivityName($row[activity])."</option>";
}
echo "</select>";
if($sport && $sport!='')	//GET CLASSES FOR THIS SPORT/ACTIVITY
{
   if($sport=='mu') $classfield="classch";
   else $classfield="class";
   $sql="SELECT DISTINCT $classfield FROM ".GetSchoolTable($sport)." WHERE $classfield!='' ORDER BY $classfield";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $class="NOCLASS"; echo "&nbsp;&nbsp;(No Classes)";
   }
   else
   {
      echo "&nbsp;&nbsp;<select name=\"class\" onChange=\"submit();\"><option value=\"\">Select a Class</option>";
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"".$row[$classfield]."\"";
         if($class==$row[$classfield]) echo " selected";
         echo ">Class ".$row[$classfield]."</option>"; 
      }
      echo "</select>";
   }
}
if($sport && $sport!='' && $class && $class!='')
{
   if($sport=='mu') $classfield="classch";
   else $classfield="class";

   //CHECK TO SEE IF PARTICIPATION POINTS HAVE BEEN PULLED FOR THIS SPORT YET:
   $sql2="SELECT * FROM cupregptsettings WHERE activity='$sport'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(!$row2[lastupdate])
      echo "<div class='error'><p>The Participation Points have not yet been pulled in for ".GetActivityName($sport).".</p><p><a style=\"color:#ffffff;\" href=\"cupregpoints.php?refer=cupplaces&class=$class&session=$session&sport=$sport&pull=1\">Pull participation points for ".GetActivityName($sport)." &rarr;</a>  <i>(be patient)</i></p></div>";
   else
      echo "<div class='alert'><img src=\"../images/greencheck.png\"> Participation points were pulled for ".GetActivityName($sport)." on ".date("F j, Y",$row2[lastupdate])."</div>";

   //GET TEAMS IN THIS SPORT/CLASS
   $sql="SELECT * FROM ".GetSchoolTable($sport)." WHERE outofstate!=1";
   if($class!="NOCLASS") $sql.=" AND $classfield='$class'";
   if($sport=='mu') $sql="SELECT * FROM muschools WHERE $classfield='$class'";
   //code by robin
   if($sport=='ubo') $sql="SELECT * FROM ".GetSchoolTable($sport);
   $sql.=" ORDER BY school";
   $result=mysql_query($sql);
   $teams=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $teams[name][$i]=$row['school']; $teams[sid][$i]=$row['sid']; $i++;
   }

   //SHOW TABLE OF TOP 8
   $sql="SELECT t1.*,t2.school,t2.mainsch,t2.othersch1,t2.othersch2,t2.othersch3 FROM cupplaces AS t1, ".GetSchoolTable($sport)." AS t2 WHERE t1.sid=t2.sid AND t1.activity='$sport'";
   if($class!='NOCLASS') $sql.=" AND t1.$classfield='$class'";
   $sql.=" ORDER BY t1.place";
   $result=mysql_query($sql);
   echo "<br><br><table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\">
	<tr align=center><th>PLACE</th><th colspan=2>TEAM</th></tr>";
   $i=0; 
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center><td><input size='3' type=text name=\"place[$i]\" value=\"$row[place]\"></td>
	<td><select name=\"sid[$i]\"><option value=\"0\">Select Team</option>";
        for($j=0;$j<count($teams[name]);$j++)
   	{
	   echo "<option value=\"".$teams[sid][$j]."\"";
	   if($row[sid]==$teams[sid][$j]) echo " selected";
	   echo ">".$teams[name][$j]."</option>";
        }
	echo "</select><input type=\"hidden\" name=\"id[$i]\" value=\"$row[id]\"></td>";
      //SHOW POINTS ENTERED FOR THIS TEAM ALREADY:
       $sql2="SELECT * FROM cuppoints WHERE (schoolid='$row[mainsch]' OR schoolid='$row[othersch1]' OR schoolid='$row[othersch2]' OR schoolid='$row[othersch3]') AND activity='$sport'";
      //code by robin
      if ($sport!='ubo'){
          if($class!="NOCLASS") $sql2.=" AND class='$class'";
          else $sql2.=" AND class!='reg'";
      }else{
         $sql2.="  AND class!='reg'";
      }
      $result2=mysql_query($sql2);
      echo "<td align='left'>";
      while($row2=mysql_fetch_array($result2))
      {
	 echo "<a href=\"cupschool.php?session=$session&schoolid=$row2[schoolid]\" class=\"small\">".GetSchool2($row2[schoolid])."</a> - $row2[points] points";
         if($row2['ignorepts']=='x') echo " (ignored)";
         echo "<br>";
      }
      echo "</td></tr>";
      $i++;
   }
   $curct=$i;
   if($i<8) $max=8;
   else $max=$i+2;
   while($i<$max)	//ENTER BLANK SPACES
   {
      $place=$i+1;
      echo "<tr align=center><td><input size='3' type=text name=\"place[$i]\" value=\"$place\"></td>
        <td colspan=2><select name=\"sid[$i]\"><option value=\"0\">Select Team</option>";
        for($j=0;$j<count($teams[name]);$j++)
        {
           echo "<option value=\"".$teams[sid][$j]."\"";
           echo ">".$teams[name][$j]."</option>";
        }
        echo "</select><input type=hidden name=\"id[$i]\" value=\"0\"></td></tr>";
      $i++;
   }
   echo "</table>";
   if($curct>0)
   {
      echo "<div class=\"alert\">NOTE: Clicking Save below will re-assign points for this activity and class based on the information entered above. Any point values previously overwritten by the NSAA for this activity in this class will be replaced.</div><br>";
      echo "<input type=\"submit\" class=\"fancybutton\" name=\"reset\" value=\"RESET\" onClick=\"return confirm('Are you sure you want to RESET the State Championships results for this activity and class? This will remove all points assigned for this activity and class thus far.');\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   }
   echo "<input type=\"submit\" class=\"fancybutton\" name=\"save\" value=\"SAVE\">";
} 	//END IF $sport and $class CHOSEN
else 
{
   echo "<p><i>Please select an ACTIVITY and a CLASS above or click on one below.</i></p>";
   echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;'>";
   $sql="SELECT * FROM cupactivities ORDER BY orderby";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($row[activity]=='mu') $classfield="classch";
      else $classfield="class";
      echo "<tr align=left><th>".GetActivityName($row[activity])."</th><td>";
      if($row[activity]=="mu")
      {
	 $sql2="SELECT lastupdate FROM cupmusicsettings";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
            $sql3="SELECT SUM(points) FROM cuppoints WHERE activity='mu' AND class!='reg'";
            $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	 if($row2[0]>0)
	 {
	    echo "<i>Music points were last pulled on ".date("m/d/y",$row2[0])." at ".date("g:ia",$row2[0])."</i>.<br>";
	    echo "$row3[0] total points have been assigned. <a href=\"cupmusic.php?session=$session\">View points by school</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"cupmusic.php?session=$session&pull=1\">Re-calculate music points for all schools</a>.";
	 }
         else
      	    echo "<i>Music points have not yet been pulled.</i> <a href=\"cupmusic.php?session=$session&pull=1\">Calculate music points for all schools &rarr;</a> (please be patient)"; 
      }	//END IF MUSIC
      else
      {
      $sql2="SELECT DISTINCT $classfield FROM ".GetSchoolTable($row[activity])." WHERE $classfield!='' ORDER BY $classfield";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $sql3="SELECT id FROM cupplaces WHERE activity='$row[activity]' AND class='$row2[class]'";
	 $result3=mysql_query($sql3);
	 $ct=mysql_num_rows($result3);
	 echo "<span";
         if($ct==0) echo " style=\"background-color:#00ff00;\"";
	 else if($ct<8) echo " style=\"background-color:yellow;\"";
	 echo "><a href=\"cupplaces.php?session=$session&sport=$row[activity]&class=$row2[class]\">$row2[class]</a> ($ct place";
	 if($ct!=1) echo "s";
	 echo " entered)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      if(mysql_num_rows($result2)==0)	//NO CLASS
      {
         $sql3="SELECT id FROM cupplaces WHERE activity='$row[activity]' AND class=''";
         $result3=mysql_query($sql3);
         $ct=mysql_num_rows($result3);
         echo "<span";
         if($ct==0) echo " style=\"background-color:#00ff00;\"";
         else if($ct<8) echo " style=\"background-color:yellow;\"";
         echo "><a href=\"cupplaces.php?session=$session&sport=$row[activity]&class=NOCLASS\">NO CLASSES</a> ($ct place";
         if($ct!=1) echo "s";
         echo " entered)</span>";
      }
      } //END IF NOT MUSIC
      echo "</td></tr>";
   }
   echo "</table>";
} //END IF NO $sport SELECTED
echo "</form>";

?>
