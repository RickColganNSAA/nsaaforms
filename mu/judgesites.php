<?php
/******************************************
judgesites.php
created 09/15/10
manage list of sites judge has worked
(iframe within judgesadmin.php)
*******************************************/
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   echo "ERROR: You do not seem to be logged in to the system.";
   exit();
}

if($delete)
{
   $sql="DELETE FROM mujudgesites WHERE mujudgeid='$mujudgeid' AND id='$delete'";
   $result=mysql_query($sql);
}

echo $init_html;
echo "<table width='100%'><tr align=center><td>";

   $sql2="SELECT * FROM mujudgesites WHERE mujudgeid='$mujudgeid' ORDER BY year DESC,site";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<b>Sites entered for this judge:</b><br>(Click the \"X\" to delete in order to remove the site record.)<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;100%\">";
      while($row2=mysql_fetch_array($result2))
      {
         echo "<tr align=center><td>$row2[year]</td><td>$row2[site]</td><td><a class=small href=\"judgesites.php?session=$session&mujudgeid=$mujudgeid&delete=$row2[id]\" onClick=\"return confirm('Are you sure you want to delete this site record?');\">X</a></td></tr>";
      }
      echo "</table>";
   }
   else
   {
      echo "[There are currently no site records entered for this judge.]";
   }

echo $end_html;
?>
