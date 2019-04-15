<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=GetLevel($session);
if($level!=1)
   $obsid=GetObsID($session);
else $obsid=0;

if($level!=1 && $obsid>0)
{
   //MAKE SURE THIS OBSERVATION BELONGS TO THIS OFFICIAL
   $sql="SELECT * FROM $dbname.".$sport."observe WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[obsid]!=$obsid) $error="ERROR: This is not an observation you filled out.";
   else $error="";
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<br><br>";

if($level!=1 && $obsid>0 && $error!='')
{
   echo "<div class='error' style='width:400px;'>$error</div>";
   echo $end_html;
   exit();
}

$sql="DELETE FROM $dbname.".$sport."observe WHERE id='$id'";
$result=mysql_query($sql);
if(mysql_error())
   echo "<div class=error>ERROR: ".mysql_error()."</div>";
else
   echo "<div class=alert style=\"width:350px;text-align:center;\">The observation has been deleted.</div>";

echo "<br><br><br>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "</td></tr></table>";
echo $end_html;

?>
