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
if(GetLevel($session)==1)
{
   $offid=$givenoffid;
   if(!$givenoffid)
   {
      echO $init_html;
      echo "<br><br>ERROR: no official specified.";
      echo $end_html;
      exit();
   }
}     
else
   $offid=GetOffID($session);

echo $init_html;
echo "<table><tr><td><center><br>";

echo "<form name=\"test_form\" method=post action=\"fbtest_update.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=categid value=\"$categid\">";
echo "<input type=hidden name=forcecategid>";
echo "<input type=hidden name=home>";
echo "<table width=90%>";
echo "<caption><b>Football Rules Examination - Part I</b><br>";
if(GetLevel($session)==1)
{
   echo "for <font style=\"color:red\"><b>".GetOffName($offid)."</b></font><br>";
}
$date=split("-",GetTestDueDate("fb"));
$duedate=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
echo "Due $duedate</caption>";
echo "<tr align=left><td align=left colspan=2><i><b>Instructions:</b></i></td></tr>";
echo "<tr align=left><td colspan=2><b>NOTE:</b> In the exam situations, A--refers to the offensive team and B--refers to their opponents the defensive team. K--refers to the kicking team and R--refers to the receiving team. A1, B1, K1 and R1 are players of these teams. If team possession changes during the down, each team retains its identity. In kicking situations, it is not during a try and no fair-catch signal has been given unless specified. Unless stated, acts occur while: the ball is inbounds; a forward pass is legal; any out-of-bounds is between the goal lines. Line means scrimmage line. Reference to a foul is to a player foul which is not unsportsmanlike. There is no foul or change of possession, unless it is mentioned, and penalties are considered accepted for enforcement.</td></tr>";
echo "<tr align=center><td align=center colspan=2><hr><table>";
$sql="SELECT category FROM fbtest_categ WHERE id='$categid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$category=$row[0];
echo "<tr align=left><th align=left colspan=2>$category:</th></tr>";
//get answers already entered by this official
$sql="SELECT * FROM fbtest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=1;$i<=100;$i++)
{
   $index="ques".$i;
   $answer[$i]=$row[$index];
}
$sql="SELECT question,place FROM fbtest WHERE category='$categid' ORDER BY place";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   $place=$row[1];
   echo "<tr align=left><td width=650 align=left>$place.&nbsp;&nbsp;&nbsp;";
   echo $row[0];
   echo "</td><td align=left>";
   echo "<input type=hidden name=\"place[$ix]\" value=\"$place\">";
   echo "<input type=radio name=\"answer[$ix]\" value='t'";
   if($answer[$place]=='t') echo " checked";
   echo "><b>T</b>&nbsp;&nbsp;";
   echo "<input type=radio name=\"answer[$ix]\" value='f'";
   if($answer[$place]=='f') echo " checked";
   echo "><b>F</b></td></tr>";
   $ix++;
}
echo "</table>";

echo $end_html;

?>
