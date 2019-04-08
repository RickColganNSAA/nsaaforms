<?php
require 'trfunctions.php';
require_once('../../calculate/functions.php');
require_once('../functions.php');

echo preg_replace("/\<title\>NSAA Home/","<title>Nebraska High School Track & Field State Records",$init_html);
?>
<h1>Nebraska High School Track & Field State Records</h1>
<div style="text-align:left;width:800px;">
<p>The Nebraska School Activities Association maintains two sets of state records-(1) State Records (St) for any performance during the entire track season, including the state meet, and (2) State Meet Record (SM) for performances during the state track meet only. Each school is responsible for submitting applications for state records achieved prior to the state meet. Basic requirements for state record consideration:</p>
<ol>
<li>Track event must be conducted at metric distances.</li>
<li>Performances will be considered only from meets involving four or more schools being run according to National Federation/NSAA rules.</li>
<li>Performances must be certified on an NSAA record application form.</li>
</ol>
<p>List also includes previous year's gold medal winner.</p><br />
<label style="padding:5px;font-size:100%;background-color:#ffff00;">All-Class State Records are highlighted in yellow.</label>
<label style="padding:5px;font-size:100%;background-color:#00ff00;">All-Class State Meet Records are highlighted in green.</label>
<?php
$sql="USE nsaastatetrack";
$result=mysql_query($sql);
$sql="SELECT * FROM trevents ORDER BY gender DESC,field,id";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<br /><h3>$row[gender] $row[eventfull]</h3>";
   echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<tr align=center bgcolor=\"#f0f0f0\"><td rowspan=2>CLASS</td><td colspan=3 bgcolor=\"#ffff00\">STATE RECORD</td><td colspan=3 bgcolor=\"#00ff00\">STATE MEET RECORD</td></tr>";
   echo "<tr bgcolor=\"#f0f0f0\" align=center><td>MARK</td><td>";
   if($row[relay]!='x') echo "NAME & ";
   echo "SCHOOL</td><td>YEAR</td><td>MARK</td><td>NAME & SCHOOL</td><td>YEAR</td></tr>";
   $sql2="SELECT * FROM trstaterecords WHERE eventid='$row[id]' ORDER BY CLASS";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      //FormatPerformance($class="",$eventid,$perf1,$perf2,$auto=FALSE)
      echo "<tr align=left><td align=center>$row2[class]</td>";
      $record=FormatPerformance($row2['class'],$row[id],$row2[all1],$row2[all2],$row2[allhandauto]);
      echo "<td";
      if($row2[allgold]=='x') echo " bgcolor='#ffff00'";
      echo ">$record</td><td";
      if($row2[allgold]=='x') echo " bgcolor='#ffff00'";
      echo ">";
      if($row[relay]!='x') echo "$row2[allname], ";
      echo "$row2[allschool]</td><td";
      if($row2[allgold]=='x') echo " bgcolor='#ffff00'";
      echo ">$row2[allyear]</td>";
      $record=FormatPerformance($row2['class'],$row[id],$row2[meet1],$row2[meet2],$row2[meethandauto]);
      echo "<td";
      if($row2[meetgold]=='x') echo " bgcolor='#00ff00'";
      echo ">$record</td><td";
      if($row2[meetgold]=='x') echo " bgcolor='#00ff00'";
      echo ">";
      if($row[relay]!='x') echo "$row2[meetname], ";
      echo "$row2[meetschool]</td><td";
      if($row2[meetgold]=='x') echo " bgcolor='#00ff00'";
      echo ">$row2[meetyear]</td>";
      echo "</tr>";
   }
   echo "</table>";
}
?>
</div>
<?php
echo $end_html;
?>
