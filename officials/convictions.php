<?php
/***********************************
convictions.php
Report of Officials who've marked
that they have a misdemeanor/felony
conviction on their registration
Created 11/6/12
Author Ann Gaffigan
************************************/

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

echo $init_html;
echo GetHeader($session);
echo "<a name=\"top\">&nbsp;</a>";
echo "<table class='nine' frame=all rules=all style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=5><caption><b>Officials with Misdemeanor or Felony Convictions:</b>";
$sql="SELECT * FROM officials WHERE conviction='yes'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//IF NONE AT ALL
{
   echo "<p>(0 Officials)</p></caption></table>";
   echo "<br><br><a href=\"manageoff.php?session=$session\">Return to Manage Officials</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
if($lastname && $lastname!='')
   $sql.=" AND last LIKE '$lastname%'";
if(!$sort || $sort=="") $sort="last,first";
$sql.=" ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)==1)
   echo "<p>(".mysql_num_rows($result)." Official)</p>";
else
   echo "<p>(".mysql_num_rows($result)." Officials)</p>";
if(mysql_num_rows($result)>0 || ($lastname && $lastname!=''))
{
   echo "<form method=post action='convictions.php'>";
   echo "<input type=hidden name='session' value='$session'>";
   echo "<p><b>Narrow by Last Name:</b> Starts with: <input type=text value=\"$lastname\" name='lastname' size=10> <input type=submit name='go' value='Go'></p></form>";
   echo "</caption>";

echo "<tr align=center><td><b>Official's Name</b><br>(Click to View/Edit Profile)</td><td><b>Contact Info</b></td><td><b>Explanation of Conviction(s)</b></td></tr>";
$ix=1;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left valign=top><td><a href=\"edit_off.php?session=$session&offid=$row[id]\" target=\"_blank\">$row[first] $row[last]</a><br>$row[city], $row[state]</td>";
   echo "<td>";
               $hphone=FormatPhone($row[homeph]);
               if($hphone!='') echo "(H) $hphone<br>";
               $wphone=FormatPhone($row[workph]);
               if($wphone!='') echo "(W) $wphone<br>";
               $cphone=FormatPhone($row[cellph]);
               if($cphone!='') echo "(C) $cphone<br>";
   echo "<p><a href=\"mailto:$row[email]\" class='small'>$row[email]</a></p></td>";
   echo "<td width='500px'><div id='convictiondiv".$ix."'>";
   if(strlen($row[convictionexplain])>200)
   {
      echo "<p>".substr($row[convictionexplain],0,200)."...<input type=button onClick=\"document.getElementById('convictiondiv".$ix."').style.display='none';document.getElementById('convictiondiv".$ix."_2').style.display='';\" value=\"More\"></p>";
      echo "</div>";
      echo "<div id='convictiondiv".$ix."_2' style='display:none;'>";   
      echo "<p>$row[convictionexplain]</p><input type=button onClick=\"document.getElementById('convictiondiv".$ix."_2').style.display='none';document.getElementById('convictiondiv".$ix."').style.display='';\" value=\"Less\">";
      echo "</div>";
   }
   else
      echo "<p>$row[convictionexplain]</p>";
   echo "</td>";
   echo "</tr>";
   $ix++;
}
}	//END IF >0 OFFICIALS
else echo "</caption>";
echo "</table><br><br>";
echo "<a href=\"#top\">Return to Top</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"manageoff.php?session=$session\">Return to Manage Officials</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;

?>
