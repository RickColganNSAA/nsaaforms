<?php
/*********************************
cupadmin.php
NSAA can manage NSAA Cup points
Author: Ann Gaffigan
Created: 8/13/15
*********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
/* $db_name='nsaascores20172018';
$db=mysql_connect($db_host,$db_user,$db_pass); */
if($reset==1):	//RESET NSAA CUP DATA FOR NEW YEAR

$sql="DELETE FROM cupmusicsettings";
$result=mysql_query($sql);
$sql="UPDATE cupregptsettings SET lastupdate='0'";
$result=mysql_query($sql);
$sql="DELETE FROM cupplaces";
$result=mysql_query($sql);
$sql="DELETE FROM cuppoints";
$result=mysql_query($sql);
$sql="UPDATE cupschools SET cupclass='',boyspoints=0,girlspoints=0,allpoints=0,adjustpts=0,reason='',adjustptsboys=0,reasonboys='',adjustptsgirls=0,reasongirls=''";
$result=mysql_query($sql);
$sql="DELETE FROM cupschoolsactivities";
$result=mysql_query($sql);

$html="Someone just clicked the RESET button on the NSAA Cup program.";
$text=$html;
$attm=array();
SendMail("nsaa@nsaahome.org","NSAA","agaffigan@gazelleincorporated.com","Ann Gaffigan","The NSAA Cup has been Reset",$text,$html,$attm);

endif; 		//END TOTAL RESET

if($offset==0):	//MAKE SURE WE AREN'T MISSING ANY SCHOOLS FROM headers TABLE & that cupschools doesn't have EXTRA schools

$sql="SELECT DISTINCT t1.id FROM headers AS t1 LEFT JOIN cupschools AS t2 ON t1.id=t2.schoolid WHERE t2.id IS NULL";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   //THIS IS A SCHOOL THAT NEEDS TO BE ADDED
   $sql2="INSERT INTO cupschools (schoolid) VALUES ('$row[id]')";
   $result2=mysql_query($sql2);
}

endif; 

/****** VIEW CURRENT STANDINGS ******/

echo $init_html;
echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a style=\"background-color:yellow;border:#ff0000 2px solid;padding:4px;\" href=\"cupadmin.php?session=$session&reset=1\" onClick=\"return confirm('Are you sure you want to reset all NSAA Cup data and start over?');\">RESET NSAA CUP DATA</a></p>";

echo "<h1>NSAA Cup:</h1>";

if($reset==1)
   echo "<div class='alert'><p>The NSAA Cup data has been RESET.</p><p>You will want to \"Un-publish\" the public links as well by clicking <a href=\"cuppublic.php?session=$session\">Manage Public Links</a>.</p><p>When you are ready for the new year, click <a href=\"cupsettings.php?type=class&session=$session\">Manage NSAA Cup Class Settings</a> to set up the classes, and to populate those classes with schools based on enrollment.</p></div>";

//CHECK FOR SCHOOLS MISSING CLASSIFICATIONS:
 $sql="SELECT * FROM cupschools WHERE cupclass=''";
$result=mysql_query($sql);
$missct=mysql_num_rows($result);
if($missct>0 && !$reset)
{
   echo "<div class='error'><p>There ";
   if($missct==1) echo "is $missct school ";
   else echo "are $missct schools ";
   echo "missing a CLASS for NSAA Cup. Click <a href=\"cupsettings.php?type=class&session=$session\">Manage Cup Class Settings</a> to update all classes (simply clicking Save will re-assign all schools).</p></div>";
}

echo "<p style=\"padding:10px;margin:10px;\"><a style=\"background-color:yellow;\" href=\"cupregpoints.php?session=$session\">Participation Points</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style=\"background-color:yellow;\" href=\"cupplaces.php?session=$session\">Enter State Championship Results (Top 8)</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style=\"background-color:yellow;\" href=\"cupsettings.php?session=$session\">Manage NSAA Cup Settings</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style=\"background-color:yellow;\" href=\"cuppublic.php?session=$session\">Preview & Manage Public Links</a></p>";

echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#a0a0a0 1px solid;'>
	<tr align=center>";
//SCHOOL
if(!$sort || $sort=="") $sort="t1.allpoints DESC";
$sort2="t2.school ASC"; $curimg="";
if($sort=="t2.school ASC")
{
   $curimg="arrowdown.png";
   $sort2="t2.school DESC";
}
else if($sort=="t2.school DESC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">School";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//Enrollment
$sort2="t2.enrollment ASC"; $curimg="";
if($sort=="t2.enrollment ASC")
{
   $curimg="arrowdown.png";
   $sort2="t2.enrollment DESC";
}
else if($sort=="t2.enrollment DESC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Enrollment";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Girls Enrollment";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Boys Enrollment";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//CUP CLASS
$sort2="t1.cupclass ASC"; $curimg="";
if($sort=="t1.cupclass ASC")
{
   $curimg="arrowdown.png";
   $sort2="t1.cupclass DESC";
}
else if($sort=="t1.cupclass DESC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Cup Class";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//GIRLS ONLY
$sort2="t1.gender='girls' DESC"; $curimg="";
if($sort=="t1.gender='girls' DESC")
{
   $curimg="arrowdown.png";
   $sort2="t1.gender='girls' ASC";
}
else if($sort=="t1.gender='girls' ASC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Girls ONLY";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//BOYS ONLY
$sort2="t1.gender='boys' DESC"; $curimg="";
if($sort=="t1.gender='boys' DESC")
{
   $curimg="arrowdown.png";
   $sort2="t1.gender='boys' ASC";
}
else if($sort=="t1.gender='boys' ASC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Boys ONLY";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//ALL
$sort2="t1.gender='' DESC"; $curimg="";
if($sort=="t1.gender='' DESC")
{
   $curimg="arrowdown.png";
   $sort2="t1.gender='' ASC";
}  
else if($sort=="t1.gender='' ASC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">DUAL Gender";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//GIRLS DIVISION
$sort2="t1.girlspoints ASC"; $curimg="";
if($sort=="t1.girlspoints ASC")
{
   $curimg="arrowdown.png";
   $sort2="t1.girlspoints DESC";
}
else if($sort=="t1.girlspoints DESC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Girls Division";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//BOYS DIVISION
$sort2="t1.boyspoints ASC"; $curimg="";
if($sort=="t1.boyspoints ASC")
{
   $curimg="arrowdown.png";
   $sort2="t1.boyspoints DESC";
}
else if($sort=="t1.boyspoints DESC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Boys Division";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
//OVERALL DIVISION
$sort2="t1.allpoints ASC"; $curimg="";
if($sort=="t1.allpoints ASC")
{
   $curimg="arrowdown.png";
   $sort2="t1.allpoints DESC";
}
else if($sort=="t1.allpoints DESC")
   $curimg="arrowup.png";
echo "<td><a href=\"cupadmin.php?session=$session&class=$class&sort=$sort2\">Overall Division";
if($curimg!='')
   echo "<img src=\"$curimg\" style=\"border:0;height:10px;\">";
echo "</a></td>";
echo "</tr>";

//QUERY:
$sql="SELECT t1.*, t2.school, t2.enrollment,t2.boysenrollment,t2.girlsenrollment FROM cupschools AS t1, headers AS t2 WHERE t1.schoolid=t2.id AND t2.enrollment>0";
if($schoolid>0) $sql.=" AND t1.schoolid='$schoolid'";
else if($class!='') $sql.=" AND t1.cupclass='$class'";
$sql.=" ORDER BY $sort";
$result=mysql_query($sql);
//echo mysql_error().$sql;
$total=mysql_num_rows($result);
if(!$offset || $offset=="") $offset=0;
$limit=50;
$sql.=" LIMIT $offset, $limit";
$result=mysql_query($sql);

//NAVIGATION:
$nav="";
if($offset>0)
{
   $prevoff=$offset-$limit;
   $nav.="<div style=\"float:left;\"><a href=\"cupadmin.php?class=$class&sort=$sort&session=$session&offset=0\">|< Start</a>&nbsp;&nbsp;&nbsp;<a href=\"cupadmin.php?sort=$sort&session=$session&class=$class&offset=$prevoffset\"><< Prev</a></div>";
}
else
{
   $nav.="<div style=\"float:left;color:#a0a0a0;\"><b>|< Start&nbsp;&nbsp;&nbsp;<< Prev</b></div>";
}
$nextoff=$offset+$limit;
if($nextoff<$total)
{
   $diff=$total%$limit;
   $finaloff=$total-$diff;
   $nav.="<div style=\"float:right;\"><a href=\"cupadmin.php?class=$class&sort=$sort&session=$session&offset=$nextoff\">Next >></a>&nbsp;&nbsp;&nbsp;</a><a href=\"cupadmin.php?sort=$sort&session=$session&class=$class&offset=$finaloff\">End >|</a></div>";
}
else
{
   $nav.="<div style=\"float:right;color:#a0a0a0;\"><b>Next >>&nbsp;&nbsp;&nbsp;
End >|</b></div>";
}
$start=$offset+1; $end=$offset+$limit;
if($end>$total) $end=$total;
$nav.="Showing $start to $end of $total schools<div style=\"clear:both;\"></div>";
if($total==1) $nav="";
echo "<caption>$nav";
	//SELECT A SCHOOL
echo "<form method='post' action='cupadmin.php'>
	<input type='hidden' name='session' value='$session'>
	<p><select name='schoolid' onChange='submit();'><option value='0'>Jump to School:</option>";
$sql2="SELECT t1.school,t2.schoolid FROM headers AS t1, cupschools AS t2 WHERE t1.id=t2.schoolid ORDER BY t1.school";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   echo "<option value=\"$row2[schoolid]\"";
   if($row2[schoolid]==$schoolid) echo " selected";
   echo ">$row2[school]</option>";
}
echo "</select>";
if($schoolid>0) echo "&nbsp;&nbsp;<a href=\"cupadmin.php?session=$session&sort=$ort\" class=\"small\">view all</a>";
echo "</p></form>";
echo "</caption>";
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align='center'";
   if($i%2==0) echo " bgcolor='#f0f0f0'";
   echo "><td align='left'><a href=\"cupschool.php?session=$session&schoolid=$row[schoolid]\" class=\"small\">$row[school]</a></td><td>$row[enrollment]</td>";
   $cupclass=$row['cupclass'];
   $girls_enroll=$row['girlsenrollment'];
   $boys_enroll=$row['boysenrollment'];
   echo "<td>$girls_enroll</td>";
   echo "<td>$boys_enroll</td>";
    echo "<td>$cupclass</td>";

    if($row[gender]=="girls") echo "<td>X</td><td>&nbsp;</td><td>&nbsp;</td>";
   else if($row[gender]=="boys") echo "<td>&nbsp;</td><td>X</td><td>&nbsp;</td>";
   else echo "<td>&nbsp;</td><td>&nbsp;</td><td>X</td>";
   echo "<td>$row[girlspoints]</td><td>$row[boyspoints]</td><td>$row[allpoints]</td>";
   echo "</tr>";
   $i++;
}

echo "</table><div style=\"width:600px;margin:10px;\">$nav</div>";

?>
