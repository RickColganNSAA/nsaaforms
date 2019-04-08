<?php
//fbstatsreport.php: for NSAA user to view list of schools and when they last 
//   updated their FB Stats form, in order of most recent first

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
echo "<tr align=center><th>School</th><th>Last Update</th></tr>";

//get schools and last update from database; display most recent first
$sql="SELECT t1.*,t2.school FROM fb_stat_updates AS t1, headers AS t2,fbschool AS t3 WHERE t1.school_id=t2.id AND t3.mainsch=t2.id ORDER BY t1.date DESC";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    //code by robin
    $year = date("Y", $row[2]);
    if ($year != "2018")
        continue;
    //end of code
   $date=date("M d, Y g:i A",$row[2]);
   echo "<tr align=left><td>";
   echo "<a href=\"fb/view_fb_stats.php?session=$session&school_ch=$row[3]\">$row[3]</a></td><td>$date";
   echo "</td></tr>";
}
?>
</table>

</td>
</tr>
</table>
</body>
</html>

