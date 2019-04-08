<?php

require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

echo $init_html;
echo "<table width=100%><tr align=center><td><br>";
echo "<form method=post action=\"test.php\">";
echo "<table width=250><caption><b>Class A State Speech Qualifiers Shuffle</b></caption><tr align=left><td>";
echo "<select onchange=\"submit();\" name=\"event\"><option value=''>Select an Event</option>";
echo "<option value=\"hum_stud\"";
if($event=="hum_stud") echo " selected";
echo ">Humorous Prose</option>";
echo "<option value=\"ser_stud\"";
if($event=="ser_stud") echo " selected";
echo ">Serious Prose</option>";
echo "<option value=\"ext_stud\"";
if($event=="ext_stud") echo " selected";
echo ">Extemporaneous Speaking</option>";
echo "<option value=\"poet_stud\"";
if($event=="poet_stud") echo " selected";
echo ">Poetry</option>";
echo "<option value=\"pers_stud\"";
if($event=="pers_stud") echo " selected";
echo ">Persuasive Speaking</option>";
echo "<option value=\"ent_stud\"";
if($event=="ent_stud") echo " selected";
echo ">Entertainment</option>";
echo "<option value=\"inf_stud\"";
if($event=="inf_stud") echo " selected";
echo ">Informative</option></select></caption></table>";
if($event!='')
{
$sql="SELECT * FROM $db_name2.spdistricts WHERE class='A' ORDER BY district";
$result=mysql_query($sql);
$qualifiers=array(); $ix=0;
$qualsch=array();
$quallist="";
while($row=mysql_fetch_array($result))
{
   $distid=$row[id];
   $sql2="SELECT * FROM sp_state_qual WHERE dist_id='$distid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $studs=split(",",$row2[$event]);
   $quallist.=$row2[$event].",";
   $sql3="SELECT * FROM eligibility WHERE (";
   for($i=0;$i<count($studs);$i++)
   {
      $sql3.="id='$studs[$i]' OR ";
   }
   $sql3=substr($sql3,0,strlen($sql3)-4);
   $sql3.=") ORDER BY school";
   $result3=mysql_query($sql3);
   while($row3=mysql_fetch_array($result3))
   {
      $qualifiers[$ix]="$row3[first] $row3[last] ($row3[school])";
      $qualsch[$ix]=$row3[school];
      $ix++;
   }
   while($ix%4>0)
   {
      $qualifiers[$ix]='NO ENTRY ???'; $ix++;
   }
   $cur2nd=$ix-3; $cur3rd=$ix-2;
   if($qualsch[$cur2nd]==$qualsch[$cur3rd])	//need to move these students to bottom of list of 4
   {
      //swap 2nd student and 4th student
      $cur4th=$ix-1;
      $temp=$qualifiers[$cur2nd];
      $qualifiers[$cur2nd]=$qualifiers[$cur4th];
      $qualifiers[$cur4th]=$temp;
   } 
}
$quallist=substr($quallist,0,strlen($quallist)-1);
$studs=split(",",$quallist);
echo "<table><tr align=left><td><b>Qualifiers:</b></td></tr>";
$district=0;
for($i=0;$i<count($studs);$i++)
{
   $place=($i%4)+1;
   if($i%4==0) 
   {
      $district++;
      echO "<tr align=left><td><b>District $district:</b></td>";
   }
   $sql="SELECT first, last, school FROM eligibility WHERE id='$studs[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>$place)&nbsp;&nbsp;$row[first] $row[last] ($row[school])</td></tr>";
}
echo "</table><br>";
/*
echo "<table><tr align=left><td><br>";
for($i=0;$i<count($qualifiers);$i++)
{
   echo "$qualifiers[$i]<br>";
}
echo "</td></tr></table>";
*/
echo "<table cellspacing=1 cellpadding=3 border=1 bordercolor=#000000>";
echo "<caption><b>Round Assignments:</b></caption>";
echo "<tr align=left valign=top><td><b>ROUND 1, SECTION 1:<br><br>";
echo "$qualifiers[0]<br>$qualifiers[4]<br>$qualifiers[6]<br>$qualifiers[9]<br>$qualifiers[11]<br>$qualifiers[15]";
echo "</td><td><b>ROUND 2, SECTION 1:<br><br>";
echo "$qualifiers[14]<br>$qualifiers[11]<br>$qualifiers[5]<br>$qualifiers[8]<br>$qualifiers[6]";
echo "</td></tr><tr align=left valign=top><td><b>ROUND 1, SECTION 2:<br><br>";
echo "$qualifiers[5]<br>$qualifiers[2]<br>$qualifiers[1]<br>$qualifiers[10]<br>$qualifiers[13]";
echo "</td><td><b>ROUND 2, SECTION 2:<br><br>";
echo "$qualifiers[15]<br>$qualifiers[12]<br>$qualifiers[10]<br>$qualifiers[4]<br>$qualifiers[3]<br>$qualifiers[1]";
echo "</td></tr><tr align=left valign=top><td><b>ROUND 1, SECTION 3:<br><br>";
echo "$qualifiers[3]<br>$qualifiers[7]<br>$qualifiers[8]<br>$qualifiers[12]<br>$qualifiers[14]";
echo "</td><td><b>ROUND 2, SECTION 3:<br><br>";
echo "$qualifiers[13]<br>$qualifiers[9]<br>$qualifiers[2]<br>$qualifiers[0]<br>$qualifiers[7]";
echo "</td></tr>";
echo "</table>";
}
echo $end_html;
?>
