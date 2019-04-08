<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//fb_stats.php: allow public to view fb stats of all schools

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

require 'variables.php';

echo "$init_html";
echo "<table width=100%><tr align=center><th><br><br>";
echo "<form method=post action=\"fb/view_fb_stats.php\">";
echo "<input type=hidden name=public value=1>";
echo "Select the school whose stats you wish to view and click \"Go\":<br>";
echo "<br><select name=school_ch>";
echo "<option>Choose School";

$sql="SELECT t1.school as team,t2.school FROM fbschool AS t1,headers AS t2 WHERE t1.mainsch=t2.id ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[school]\">$row[team]</option>";
}
echo "</select>";
echo "<input type=submit name=submit value=Go>";
echo "</form>";
echo "</th></tr></table></body></html>";
?>
