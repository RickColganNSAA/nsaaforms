<?php
/*******************************************
statequalifiers.php
Allow NSAA to manage list of state qualifiers
Created 1/22/13
Author: Ann Gaffigan
 *******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetJOHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
    header("Location:index.php?error=1");
    exit();
}

if($reset==1 && $catid && $class!='')
{
    $sql="DELETE FROM joqualifiers WHERE catid='$catid' AND class='$class'";
    $result=mysql_query($sql);
    header("Location:statequalifiers.php?session=$session&catid=$catid&class=$class");
}

if($approve)
{
    $sql="UPDATE jocategories SET webapproved2='$webapproved2' WHERE id='$catid'";
    $result=mysql_query($sql);
}

if($addqualifiers)
{
    //GET $orderby
    $sql="SELECT * FROM joqualifiers WHERE catid='$catid' AND class='$class' ORDER BY orderby DESC LIMIT 1";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $orderby=$row[orderby]+1;
    for($i=0;$i<count($addentryid);$i++)
    {
        if($addcheck[$i]=='x')
        {
            $sql="INSERT INTO joqualifiers (class,catid,orderby,entryid) VALUES ('$class','$catid','$orderby','$addentryid[$i]')";
            $result=mysql_query($sql);
            $orderby++;
        }
    }
}

if($deletequal)
{
    $sql="DELETE FROM joqualifiers WHERE id='$deletequal'";
    $result=mysql_query($sql);
    //Re-Order
    $orderby=1;
    $sql="SELECT * FROM joqualifiers WHERE catid='$catid' AND class='$class' ORDER BY orderby ASC";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result))
    {
        $sql2="UPDATE joqualifiers SET orderby='$orderby' WHERE id='$row[id]'";
        $result2=mysql_query($sql2);
        $orderby++;
    }
}

if($hiddensaveentries || $saveentries)
{
    //SAVE ORDER
    $order=split("[|]",$entryorder);
    $rank=1;
    for($i=0;$i<count($order);$i++)
    {
        $id=trim($order[$i]);
        $sql="UPDATE joqualifiers SET orderby='$rank' WHERE id='$id'";
        $result=mysql_query($sql);
        $rank++;
    }
}

$html=preg_split("/<\/head>/",$init_html);
echo $html[0];
echo "
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/core.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/events.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/css.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/coordinates.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/drag.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/dragsort.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/cookies.js\"></script>";
echo "</head>".$html[1];
echo $header;
?>
<script type="text/javascript" language="JavaScript">
    var dragsort = ToolMan.dragsort()
    var junkdrawer = ToolMan.junkdrawer()
    window.onload = function() {
        dragsort.makeListSortable(document.getElementById('lines'),saveOrder)
    }
    function verticalOnly(item) {
        item.toolManDragGroup.verticalOnly()
    }
    function speak(id, what) {
        var element = document.getElementById(id);
        element.innerHTML = 'Clicked ' + what;
    }
    function saveOrder(item) {
        var group = item.toolManDragGroup
        var list = group.element.parentNode
        var id = list.getAttribute('id')
        if (id == null) return
        group.register('dragend', function() {
            ToolMan.cookies().set('list-' + id,junkdrawer.serializeList(list),365)
        })
    }
</script>
<?php

echo "<br><a href=\"stateadmin.php?session=$session\">Return to JO Contest Main Menu</a>";
echo "<h2>Manage State Journalism Contest Qualifiers</h2>";
echo "<form method=post action=\"statequalifiers.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

//SELECT CLASS
echo "<p><b>Select a CLASS:</b> <select name=\"class\" onChange=\"submit();\"><option value=''>Select Class</option>";
$sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
$classes=array();
while($row=mysql_fetch_array($result))
{
    array_push($classes,$row['class']);
    echo "<option value=\"$row[class]\"";
    if($class==$row['class']) echo " selected";
    echo ">CLASS $row[class]</option>";
}
if (!in_array("C",$classes)){
    echo "<option value=\"C\">CLASS C</option>";
}
echo "</select></p>";
//SELECT EVENT
echo "<p><b>Select an EVENT:</b> ";
echo "<select name=\"catid\" onChange=\"submit();\"><option value=''>Select Event</option>";
$sql="SELECT t1.id,t1.category,t1.showplace,t2.judgeid,t2.datesub FROM jocategories AS t1,joassignments AS t2 WHERE t1.id=t2.catid AND t2.class='$class' ORDER BY t2.datesub DESC,t1.category";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    echo "<option value=\"$row[id]\"";
    if($catid==$row[id]) echo " selected";
    echo ">$row[category]";
    if($row[datesub]>0)
        echo " (Submitted by ".GetJOJudgeName(0,GetJOJudgeForCategory($row[id],$class))." on ".date("m/d/y",$row[datesub]).")";
    echo "</option>";
}
echo "</select></p>";

if(!$catid)	//STILL SHOW LINK TO PREVIEW QUALIFIERS
{
    echo "<div class='alert' style=\"width:400px;text-align:center;\">";
    echo "<p><a href=\"statequalifierlist.php?session=$session\" target=\"_blank\">Preview State Qualifiers (to Create Document for Website)</a></p>";
    echo "</div>";
}
else	//EVENT SELECTED -- PROCEED
{
    //FIRST MAKE SURE THIS CLASS/EVENT HAS ENTRIES IN THE joqualifiers TABLE - IF NOT, ENTER THEM
    $sql="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2,joschool AS t3 WHERE t1.entryid=t2.id AND t2.sid=t3.sid AND t1.class='$class' AND t1.catid='$catid' ORDER BY t1.orderby";
    $result=mysql_query($sql);
    if(mysql_num_rows($result)==0)
    {
        //ADD JUDGE'S RANKED TOP 12 TO THE joqualifiers TABLE (DEFAULT QUALIFIERS LIST)
        $sql2="INSERT INTO joqualifiers (class,catid,entryid,orderby) SELECT t2.class,t1.catid,t1.id,t1.classrank FROM joentries AS t1, joschool AS t2 WHERE t1.sid=t2.sid AND t1.catid='$catid' AND t1.classrank>=1 AND t1.classrank<=12 AND t2.class='$class' ORDER BY classrank";
        $result2=mysql_query($sql2);
        //RERUN THE QUERY
        $sql="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2,joschool AS t3 WHERE t1.entryid=t2.id AND t2.sid=t3.sid AND t1.class='$class' AND t1.catid='$catid' ";
        if($curshowplace==1) $sql.="ORDER BY t1.orderby";
        else $sql.="ORDER BY t3.school";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)==0)
        {
            echo "<p><i>No entries have been ranked yet for Class $class $curcatname.</i></p>";
            echo $end_html;
            exit();
        }
    }   //NOW WE HAVE THE QUALIFIERS IN $result

    //INSTRUCTIONS
    echo "<div style=\"text-align:left;width:700px;\">";
    echo "<p>If any Class Champions were not ranked in the Top 12, they are shown to the right. The alternates are also shown on the right. Check the box next to a class champion or alternate and click \"Add\" to add them to the list on the left.</p>";
    echo "<p>Click the Delete link to delete someone from the Top 12 and therefore from the list of qualifiers for this event.</p>";
    echo "<p>Once you add or remove entries from the list of Qualifiers, you can always re-arrange their order again and click \"Save Order.\"</p>";
    echo "</div>";

    echo "<div class='alert' style=\"width:400px;text-align:center;\">";
    echo "<p><a href=\"statequalifierlist.php?session=$session\" target=\"_blank\">Preview State Qualifiers (to Create Document for Website)</a></p>";
    echo "</div>";

    echo "<table style=\"width:750px;\" cellspacing=0 cellpadding=5><tr align=left valign=top><td>";

    //ARRANGE STATE QUALIFIERS:
    echo "<h2>State Qualifiers:&nbsp;&nbsp;&nbsp;[<a href=\"statequalifiers.php?session=$session&catid=$catid&class=$class&reset=1\">Reset to Order Submitted by Judge</a>]</h2>";
    echo "<input type=hidden name=\"entryorder\" id=\"entryorder\"><ul id=\"lines\">";
    $ix=0;
    while($row=mysql_fetch_array($result))
    {
        $class=GetClass($row[sid],'jo');
        echo "<li id=\"$row[qualid]\">";
        echo "<label class='highlight'><b>$row[orderby].</b></label>&nbsp;";
        echo "<input type=hidden name=\"entryids[$ix]\" value=\"$row[qualid]\">";
        echo GetStudentInfo($row[studentid],FALSE);
        if($row[studentid2]>0) echo ", et al";
        echo ", ".GetSchoolName($row[sid],'jo')." - $class";
        if($row[overallrank]>12) echo " (Alternate, Judge's Rank: $row[classrank])";
        else echo " (Judge's Rank: $row[classrank])";
        //Link to Remove from Qualifiers
        echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<a class='small' href=\"statequalifiers.php?session=$session&class=$class&catid=$catid&deletequal=$row[qualid]\" onClick=\"return confirm('Are you sure you want to remove this entry from the Class $class State Qualifiers list? (Note that this will NOT change the ranking the judge gave the entry. You can add this entry back to the list of qualifiers later if you wish.)');\">Delete from Qualifiers List</a>";
        echo "</li>";
        $ix++;
    }
    echo "</ul>";
    if(!$submitted || $level==1)
        echo "<input type=hidden name='hiddensaveentries' id='hiddensaveentries'><input type=submit class='fancybutton2' name=saveentries value=\"Save Order\" onclick=\"document.getElementById('entryorder').value=junkdrawer.inspectListOrder('lines');\">";
    echo "</td><td>";

    //ADD QUALIFIERS TO THE LIST:
    $ix=0;
    //Alternates:
    echo "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"width:100%;border:#808080 1px solid;\">";
    echo "<caption><b>Alternates:</b>";
    $sql="SELECT t1.* FROM joentries AS t1 LEFT JOIN joqualifiers AS t2 ON t1.id=t2.entryid WHERE t1.classrank>12 AND t1.classrank<=15 AND t2.id IS NULL AND t1.catid='$catid'";
    $result=mysql_query($sql);
    $curix=0;
    if(mysql_num_rows($result)>0)
    {
        while($row=mysql_fetch_array($result))
        {
            if(GetClass($row[sid],'jo')==$class)
            {
                if($curix==0)
                {
                    echo "<p style=\"text-align:left;\"><i>Check the box next to an entry and click \"Add to List of Qualifiers\" to add it to the list on the left.</p></caption>";
                    echo "<tr bgcolor='#f0f0f0' align=center><td>Add</td><td>Alternate</td></tr>";
                }
                echo "<tr><td align=center><input type=checkbox name=\"addcheck[$ix]\" value='x'><input type=hidden name=\"addentryid[$ix]\" value=\"$row[id]\"></td>";
                echo "<td>".GetStudentInfo($row[studentid],FALSE);
                if($row[studentid2]>0) echo ", et al";
                echo ", ".GetSchoolName($row[sid],'jo')." - Class ".GetClass($row[sid],'jo')."</td></tr>";
                $ix++; $curix++;
            }
        }
    }
    if($curix==0)
        echo "<p><i>No alternates are missing from the list of qualifiers.</i></p></caption>";
    echo "</table><br>";

    //Originally Ranked in Top 12 by Judge
    echo "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"width:100%;border:#808080 1px solid;\">";
    echo "<caption><b>Entries Originally Ranked in Top 12 by Judge:</b>";
    $sql="SELECT t1.* FROM joentries AS t1 LEFT JOIN joqualifiers AS t2 ON t1.id=t2.entryid WHERE t1.classrank>=1 AND t1.classrank<=12 AND t2.id IS NULL AND t1.catid='$catid'";
    $result=mysql_query($sql);
    $curix=0;
    if(mysql_num_rows($result)>0)
    {
        while($row=mysql_fetch_array($result))
        {
            if(GetClass($row[sid],'jo')==$class)
            {
                if($curix==0)
                {
                    echo "<p style=\"text-align:left;\"><i>Check the box next to an entry and click \"Add to List of Qualifiers\" to add it to the list on the left.</p></caption>";
                    echo "<tr bgcolor='#f0f0f0' align=center><td>Add</td><td>Top-12 Ranked Entry</td></tr>";
                }
                echo "<tr><td align=center><input type=checkbox name=\"addcheck[$ix]\" value='x'><input type=hidden name=\"addentryid[$ix]\" value=\"$row[id]\"></td>";
                echo "<td>".GetStudentInfo($row[studentid],FALSE);
                if($row[studentid2]>0) echo ", et al";
                echo ", ".GetSchoolName($row[sid],'jo')." - Class ".GetClass($row[sid],'jo')."</td></tr>";
                $ix++; $curix++;
            }
        }
    }
    if($curix==0)
        echo "<p><i>No entries originally ranked in the Top 12 are missing from the list of qualifiers.</i></p></caption>";
    echo "</table><br>";

    echo "<input type=submit class='fancybutton' name='addqualifiers' value='<< Add to List of Qualifiers'>";

    echo "</td></tr></table>";
}//END IF CATID
echo "</form>";
echo $end_html;
?>
