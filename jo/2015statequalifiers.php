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

if($approve)
{
   $sql="UPDATE jocategories SET webapproved2='$webapproved2' WHERE id='$catid'";
   $result=mysql_query($sql);
}

if($addqualifiers)
{
   //GET $orderby
   $sql="SELECT * FROM joqualifiers WHERE catid='$catid' ORDER BY orderby DESC LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $orderby=$row[orderby]+1;
   for($i=0;$i<count($addentryid);$i++)
   {
      if($addcheck[$i]=='x')
      {
         $sql="INSERT INTO joqualifiers (catid,orderby,entryid) VALUES ('$catid','$orderby','$addentryid[$i]')";
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
   $sql="SELECT * FROM joqualifiers WHERE catid='$catid' ORDER BY orderby ASC";
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
       //junkdrawer.restoreListOrder('lines')
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
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[class]\"";
      if($class==$row['class']) echo " selected";
      echo ">CLASS $row[class]</option>";
   }
   echo "</select></p>";
//SELECT EVENT
   echo "<p><b>Select an EVENT:</b> ";
   echo "<select name=\"catid\" onChange=\"submit();\"><option value=''>Select Event</option>";
   $sql="SELECT t1.id,t1.category,t1.showplace,t2.judgeid,t2.datesub FROM jocategories AS t1,joassignments AS t2 WHERE t1.id=t2.catid ORDER BY t2.datesub DESC,t1.category";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($catid==$row[id]) { echo " selected"; $curcatname=$row[category]; $curshowplace=$row[showplace]; }
      echo ">$row[category]";
      if($row[datesub]>0)
         echo " (Submitted by ".GetJOJudgeName(0,GetJOJudgeForCategory($row[id]))." on ".date("m/d/y",$row[datesub]).")";
      echo "</option>";
   }
   echo "</select></p>";

if(!$catid || !$class)	//STILL SHOW LINK TO PREVIEW QUALIFIERS
{
      echo "<div class='alert' style=\"width:400px;text-align:center;\">";
      echo "<p><a href=\"statequalifierlist.php?session=$session\" target=\"_blank\">Preview State Qualifiers (to Create Document for Website)</a></p>";
      echo "</div>";
}
else	//CLASS & EVENT SELECTED -- PROCEED
{
   //FIRST MAKE SURE THIS CLASS/EVENT HAS ENTRIES IN THE joqualifiers TABLE - IF NOT, ENTER THEM
   $sql="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2,joschool AS t3 WHERE t1.entryid=t2.id AND t2.sid=t3.sid AND t3.class='$class' AND t1.catid='$catid' ";
   if($curshowplace==1) $sql.="ORDER BY t1.orderby";
   else $sql.="ORDER BY t3.school";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
	//ADD JUDGE'S RANKED TOP 12 TO THE joqualifiers TABLE (DEFAULT QUALIFIERS LIST)
	$sql2="INSERT INTO joqualifiers (catid,entryid,orderby) SELECT catid,id,classrank FROM joentries AS t1, joschool AS t2 WHERE t1.sid=t2.sid AND t1.catid='$catid' AND t1.classrank>=1 AND t1.classrank<=12 AND t2.class='$class' ORDER BY classrank";
   	$result2=mysql_query($sql2);
	//RERUN THE QUERY
        $sql="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2,joschool AS t3 WHERE t1.entryid=t2.id AND t2.sid=t3.sid AND t3.class='$class' AND t1.catid='$catid' ";
        if($curshowplace==1) $sql.="ORDER BY t1.orderby";
        else $sql.="ORDER BY t3.school";
   	$result=mysql_query($sql);
   	if(mysql_num_rows($result)==0)
   	{
      	   echo "<p><i>No entries have been ranked yet for Class $class $curcatname.</i></p>";
      	   echo $end_html;
           exit();
        }
    }	//NOW WE HAVE THE QUALIFIERS IN $result

      echo "<div class='alert' style=\"width:400px;text-align:center;\">";
      echo "<p><a href=\"statequalifierlist.php?session=$session\" target=\"_blank\">Preview State Qualifiers (to Create Document for Website)</a></p>";
      echo "</div>";

      echo "<table style=\"width:750px;\" cellspacing=0 cellpadding=5><tr align=left valign=top><td>";

 	//ARRANGE STATE QUALIFIERS:
      echo "<h2>Class $class $curcatname State Qualifiers:</h2>";
      if($curshowplace) echo "<ol>";
      else echo "<ul>";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         echo "<li id=\"$row[qualid]\">";
         echo GetStudentInfo($row[studentid],FALSE);
   	 if($row[studentid2]>0) echo ", et al";
   	 echo ", ".GetSchoolName($row[sid],'jo');
         echo "</li><br />";
         $ix++;
      }
      if($curshowplace) echo "</ol>";
      else echo "</ul>";

       echo "</td></tr></table>";
}//END IF CATID
echo "</form>";
echo $end_html;
?>
