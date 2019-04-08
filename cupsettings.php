<?php
/*********************************
cupsettings.php
NSAA can enter settings for how to
assign Cup classes and points for
registration and top 8 places
Author: Ann Gaffigan
Created: 8/24/15
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
/****** SAVE SETTINGS ******/
if($saveclass) 		//SAVE CLASS SETTINGS
{
   for($i=0;$i<count($id);$i++)
   {
      $sql="UPDATE cupclasssettings SET class='$class[$i]',minenroll='$min[$i]', maxenroll='$max[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
      if(mysql_error()) { echo mysql_error()." with query $sql"; exit(); }
   }
   CupAssignClasses();
}
else if($savepoints) 	//SAVE POINTS SETTINGS
{
   for($i=0;$i<count($id);$i++)
   {
      $sql="UPDATE cuppointssettings SET points='$points[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
      if(mysql_error()) { echo mysql_error()." with query $sql"; exit(); }
   }
   //UPDATE POINTS:
   $sql="SELECT DISTINCT activity,class FROM cuppoints";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      CupAssignPoints($row['activity'],$row['class'],FALSE);
      CupAssignPoints($row['activity'],'reg',FALSE);
   }
}

/****** SELECT SETTINGS TYPE AND ENTER SETTINGS ******/

echo $init_html;

echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a href=\"cupadmin.php?session=$session\">&larr; Return to NSAA Cup Main Menu</a></p>";

echo "<h1>NSAA Cup: Manage Settings</h1>";

if($saveclass || $savepoints)
{
   if($message!='')
      echo "<div class=\"alert\">$message</div>";
   else
      echo "<div class=\"alert\">Your changes have been saved.</div>";
}

echo "<form method=\"post\" action=\"cupsettings.php\">
	<input type=hidden name=\"session\" value=\"$session\">
	<select name=\"type\" onChange=\"submit();\"><option value=\"\">Select Settings Option</option><option value=\"class\"";
if($type=="class") echo " selected";
echo ">Cup Classes</option><option value=\"points\"";
if($type=="points") echo " selected";
echo ">Points for Registration & Top 8</option></select>";

if($type=="class") 	//CLASS SETTINGS
{
   $sql="SELECT * FROM cupclasssettings WHERE class!='' ORDER BY minenroll DESC, maxenroll DESC";
   $result=mysql_query($sql);
   echo "<br><br><table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\"><tr align=center><th>CLASS</th><th>MINIMUM<br>ENROLLMENT</th><th>MAXIMUM<br>ENROLLMENT</th></tr>";
   $i=0; $error=""; $curmin=0; 
   while($row=mysql_fetch_array($result))
   {
      if($row[maxenroll]>=$curmin && $curmin>0) 
         $error.="The maximum enrollment for <u><b>CLASS $row[class]</u></b> equals or exceeds that of the class above it.<br>";
      $curmin=$row[minenroll];
      echo "<tr align=center><td><input size='3' type=text name=\"class[$i]\" value=\"$row[class]\"></td>
	<td><input type=text name=\"min[$i]\" size=\"5\" value=\"$row[minenroll]\"></td>
        <td><input type=text name=\"max[$i]\" size=\"5\" value=\"$row[maxenroll]\">
	<input type=\"hidden\" name=\"id[$i]\" value=\"$row[id]\"></td></tr>";
      $i++;
   }
   echo "</table>";
   if($error!='') echo "<div class=\"error\">ERROR:<br><br>$error</div>";
   if($curct>0)
      echo "<div class=\"alert\">NOTE: Clicking Save below will re-assign classes (if changes have been made) based on the information entered above. </div><br>";
   echo "<input type=\"submit\" class=\"fancybutton\" name=\"saveclass\" value=\"Save\">";
} 	//END IF $sport and $class CHOSEN
else if($type=="points")
{
   $sql="SELECT * FROM cuppointssettings ORDER BY place ASC";
   $result=mysql_query($sql);
   echo "<br><br><table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\"><tr align=center><th>PLACE<br>(or REGISTRATION)</th><th>POINTS</th></tr>";
   $i=0; $error=""; $curmin=0;
   while($row=mysql_fetch_array($result))
   {
      if($row[place]==0) $place="Registration<br><i>(per activity)</i>";
      else $place=date("jS",mktime(0,0,0,1,$row[place],date("Y")));
      echo "<tr align=center><td>$place</td>
        <td><input type=text name=\"points[$i]\" size=\"5\" value=\"$row[points]\"></td>
        <input type=\"hidden\" name=\"id[$i]\" value=\"$row[id]\"></td></tr>";
      $i++;
   }
   echo "</table>";
   if($error!='') echo "<div class=\"error\">ERROR:<br><br>$error</div>";
   if($curct>0)
      echo "<div class=\"alert\">NOTE: Clicking Save below will re-assign points (if changes have been made) based on the information entered above. </div><br>";
   echo "<input type=\"submit\" class=\"fancybutton\" name=\"savepoints\" value=\"Save\">";
}
else echo "<p><i>Please select Class or Points settings.</i></p>";
echo "</form>";

?>
