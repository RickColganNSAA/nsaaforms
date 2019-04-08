<?php
/*******************************************
stateentries.php
NSAA User can Browse School Submissions
for JO Contest
Created 1/6/13
Author: Ann Gaffigan
 *******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
    header("Location:index.php?error=1");
    exit();
}

echo $init_html;
echo $header;

echo "<br><p><a href=\"stateadmin.php?session=$session\">Return to JO Contest Main Menu</a></p>";

echo "<h2>NSAA Journalism Contest ENTRY SUBMISSIONS</h2>";
echo "<form method=post action=\"stateentries.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
//CAN FILTER BY CLASS, SCHOOL AND/OR CATEGORY
echo "<div class='normalwhite' style='text-align:left;width:400px;'>";
echo "<p><b>FILTER BY:</b></p>";
echo "<p><b>Class:</b> <select name=\"class\" onChange=\"submit();\"><option value=''>All Classes</option>";
$sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
$classes=array();
while($row=mysql_fetch_array($result))
{
    array_push($classes,$row['class']);
    echo "<option value=\"$row[class]\"";
    if($class==$row['class']) echo " selected";
    echo ">Class $row[class]</option>";
}
if (!in_array("C",$classes)){
    echo "<option value=\"C\">Class C</option>";
}
echo "</select></p>";
echo "<p><b>School:</b> <select name=\"sid\"><option value='0'>All Schools</option>";
$sql="SELECT * FROM joschool";
if($class && $class!='') $sql.=" WHERE class='$class'";
$sql.=" ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    echo "<option value=\"$row[sid]\"";
    if($sid==$row[sid]) echo " selected";
    echo ">$row[school]</option>";
}
echo "</select></p>";
echo "<p><b>Category:</b> <select name=\"catid\"><option value='0'>All Categories</option>";
$sql="SELECT * FROM jocategories ORDER BY category";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    echo "<option value=\"$row[id]\"";
    if($catid==$row[id]) echo " selected";
    echo ">$row[category]</option>";
}
echo "</select></p>";
echo "<p><input type=submit name='filter' value='Apply Filter'></p>";
echo "</div>";

//GET QUERY
if($catid)	//SORT BY CATEGORY (ORDER BY RANK)
{
    $sql="SELECT t1.* FROM joentries AS t1, joschool AS t2 WHERE t1.sid=t2.sid AND t1.studentid>0 AND t1.filename!='' AND ";
    if($class && $class!='') $sort="t1.classrank,t2.school";
    else $sort="t1.overallrank,t1.classrank,t2.school";
}
else
{
    $sql="SELECT DISTINCT t1.sid FROM joentries AS t1,joschool AS t2 WHERE t1.sid=t2.sid AND t1.studentid>0 AND t1.filename!='' AND ";
}
if($class && $class!='')
    $sql.="t2.class='$class' AND ";
if($sid)
    $sql.="t1.sid='$sid' AND ";
if($catid)
    $sql.="t1.catid='$catid' AND ";
if(!$sort) $sort="t2.school";
$sql=substr($sql,0,strlen($sql)-4)."ORDER BY $sort";
//echo $sql;
$result=mysql_query($sql);
$total=mysql_num_rows($result);
if(!$offset) $offset=0;
$limit=25;
$start=$offset+1;
$end=$offset+$limit;
if($end>$total) $end=$total;
$prevoffset=$offset-$limit;
$nextoffset=$offset+$limit;
$sql.=" LIMIT $offset,25";
$result=mysql_query($sql);

echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"min-width:600px !important;border:#808080 1px solid;\">";
echo "<caption><br>";
//PREVIOUS
echo "<div style=\"float:left;width:100px;\">&nbsp;";
if($prevoffset>=0)
{
    echo "<a href=\"stateentries.php?session=$session&class=$class&sid=$sid&catid=$catid&offset=$prevoffset\"><img src=\"../arrowleft.png\" style=\"border:0;height:25px;\"></a>";
}
echo "</div>";
echo "<div style=\"float:right;width:100px;\">&nbsp;";
if($nextoffset<$total)
{
    echo "<a href=\"stateentries.php?session=$session&class=$class&sid=$sid&catid=$catid&offset=$nextoffset\"><img src=\"../arrowright.png\" style=\"border:0;height:25px;\"></a>";
}
echo "</div>";
if($total==0) $start=0;
echo "Showing $start - $end of $total Entries<br><br>";
echo "<div style='clear:both;'></div>";

if($total==0 && $sid>0)
{
    echo "<p>No entries found for ".GetSchoolName($sid,'jo').". <a href=\"stateentry.php?session=$session&sid=$sid\">View/Edit ".GetSchoolName($sid,'jo')."'s Entries</a></p>";
}
echo "</caption>";
if(mysql_num_rows($result)>0)
{
    echo "<tr align=center><td><b>School</b><br>(Click to view full submission)</td><td><b>Class</b></td>";
    if($catid)	//View each entry for this category, not just number
    {
        echo "<td><b>Entry</b></td><td><b>Overall<br>Rank</b></td><td><b>Class<br>Rank</b></td>";
    }
    else	//SHOW # of ENTRIES FOR EACH CATEGORY
    {
        $sql2="SELECT * FROM jocategories ORDER BY category";
        $result2=mysql_query($sql2);
        $catids=array(); $cats=array(); $i=0;
        while($row2=mysql_fetch_array($result2))
        {
            echo "<td>$row2[colheader]</td>";
            $catids[$i]=$row2[id];
            $cats[$i]=$row2[category];
            $i++;
        }
    }
    echo "</tr>";
    while($row=mysql_fetch_array($result))
    {
        echo "<tr align=center><td align=left><a href=\"stateentry.php?session=$session&sid=$row[sid]\">".GetSchoolName($row[sid],'jo')."</a></td><td>".GetClass($row[sid],'jo',"",'joschool')."</td>";
        if($catid)	//SHOW SUBMISSION WITH LINK TO DOWNLOAD FILE
        {
            $errors=GetJOEntryErrors($row[sid],$catid);
            if($errors!='') echo "<td align=left bgcolor='#ff0000'>";
            else echo "<td align=left>";
            if(citgf_file_exists($_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/".$row[filename]))
            {
                echo "<a class=small href=\"/nsaaforms/downloads/$row[filename]\" target=\"_blank\">$row[label]</a><br>";
                if(citgf_file_exists($_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/".$row[filename2]) && $row[filename2]!='')
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"/nsaaforms/downloads/$row[filename2]\" target=\"_blank\">$row[label2]</a><br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".GetStudentInfo($row[studentid],FALSE);
                $j=2;
                while($j<=6)
                {
                    $studvar="studentid".$j;
                    if($row[$studvar]>0) echo ", ".GetStudentInfo($row[$studvar],FALSE);
                    $j++;
                }
            }
            echo "&nbsp;</td>";
            if($row[overallrank]<=15 && $row[overallrank]>0) echo "<td>$row[overallrank]</td>";
            else echo "<td>-</td>";
            if($row[classrank]<=5 && $row[classrank]>0) echo "<td>$row[classrank]</td>";
            else echo "<td>-</td>";
        } //END IF $catid
        else	//SHOW COUNT FOR EACH CATEGORY
        {
            for($i=0;$i<count($catids);$i++)
            {
                $sql2="SELECT id FROM joentries WHERE sid='$row[sid]' AND catid='".$catids[$i]."' AND filename!='' AND studentid>0";
                $result2=mysql_query($sql2);
                echo "<td";
                $errors=GetJOEntryErrors($row[sid],$catids[$i]);
                if($errors!='') echo " bgcolor='#ff0000'";
                echo ">";
                $ct=mysql_num_rows($result2);
                if($ct==3) echo "<b>$ct</b>";
                else echo $ct;
                echo "</td>";
            }
        }
        echo "</tr>";
    }
}//END IF FILTER RESULTED IN >0 RESULTS
else	//NO RESULTS
    echo "<tr align=center><td><p><i>Your search returned no results.</i></p></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
