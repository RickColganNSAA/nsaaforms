<?php
//fbrecordsreport.php: for NSAA user to view list of schools and when they last 
//   updated their FB Stats form--Broken Records fields

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo GetHeader($session);

echo "<center><br><br><table border=1 cellspacing=0 cellpadding=3 bordercolor=#000000 frame=vsides>";
echo "<tr align=center><th>School</th><th>Last Records Update</th></tr>";

//get schools and last update from database; display most recent first
$sql="SELECT DISTINCT(t1.edited),t2.school FROM fb_records AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t1.record IS NOT NULL AND t1.record!='' ORDER BY t1.edited DESC";
$result=mysql_query($sql);
$thisyr=date("Y");
$thismo=date("m");
if($thismo<6)
   $lastyr=$thisyr-2;
else
   $lastyr=$thisyr-1;
while($row=mysql_fetch_array($result))
{
   if(date("Y",$row[0])>$lastyr)
   {
      $date=date("M d, Y g:i A",$row[0]);
      echo "<tr align=left><td>";
      echo "<a href=\"fb/view_fb_stats.php?session=$session&school_ch=$row[1]\">$row[1]</a></td><td>$date";
      echo "</td></tr>";
   }
}
?>
</table>

</td>
</tr>
</table>
</body>
</html>

