<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session);
echo "<center><br><a href=\"officialsapp.php?session=$session\" class=small>Return to Online Officials' Apps</a><br>";
echo "<form method=post action=\"pendingoffs.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption><b>Officials with \"Pending\" Credit Card Applications:</b></caption>";
echo "<tr align=center><td><b>App Date/Time</b></td><td><b>Official Name (Soc Sec #)</b></td></tr>";
$sql="SELECT * FROM officials WHERE pending='x' ORDER BY appid DESC";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left>";
   $appdate=date("m/d/Y",$row[appid]);
   $apptime=date("h:i T",$row[appid]);
   $appdate.=" at ".$apptime;
   echo "<td>$appdate</td><td><a class=small href=\"#\" onClick=\"window.open('edit_off.php?session=$session&header=no&offid=$row[id]','editoff','width=600,height=500,resizable=yes,scrollbars=yes')\">$row[first] $row[last] ($row[socsec])</a></td></tr>";
}
echo "</table>";
echo "</form>";
echo "<a href=\"officialsapp.php?session=$session\" class=small>Return to Online Officials Apps</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\" class=small>Home</a>";
echo $end_html;

?>
