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

//SELECT EVENT
   echo "<p><b>Select an Event:</b> ";
   echo "<select name=\"catid\" onChange=\"submit();\"><option value=''>Select Event</option>";
   $sql="SELECT t1.id,t1.category,t2.judgeid,t2.datesub FROM jocategories AS t1,joassignments AS t2 WHERE t1.id=t2.catid ORDER BY t2.datesub DESC,t1.category";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($catid==$row[id]) echo " selected";
      echo ">$row[category]";
      if($row[datesub]>0)
         echo " (Submitted by ".GetJOJudgeName(0,GetJOJudgeForCategory($row[id]))." on ".date("m/d/y",$row[datesub]).")";
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
   if($reset==1)
   {
      $sql="DELETE FROM joqualifiers WHERE catid='$catid'";
      $result=mysql_query($sql);
   }

   //FIRST MAKE SURE THIS EVENT HAS ENTRIES IN THE joqualifiers TABLE - IF NOT, ENTER THEM
   $sql="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2 WHERE t1.entryid=t2.id AND t1.catid='$catid' ORDER BY t1.orderby";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
	//ADD JUDGE'S RANKED TOP 12 TO THE joqualifiers TABLE (DEFAULT QUALIFIERS LIST)
	$sql2="INSERT INTO joqualifiers (catid,entryid,orderby) SELECT catid,id,overallrank FROM joentries WHERE catid='$catid' AND overallrank>=1 AND overallrank<=12 ORDER BY overallrank";
   	$result2=mysql_query($sql2);
        $sql="SELECT t1.id AS qualid,t1.orderby,t2.* FROM joqualifiers AS t1,joentries AS t2 WHERE t1.entryid=t2.id AND t1.catid='$catid' AND t2.studentid>0 ORDER BY t1.orderby";
   	$result=mysql_query($sql);
   	if(mysql_num_rows($result)==0)
   	{
      	   echo "<p><i>No entries have been ranked yet.</i></p>";
      	   echo $end_html;
           exit();
        }
    }	//NOW WE HAVE THE QUALIFIERS IN $result

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
      echo "<h2>State Qualifiers:&nbsp;&nbsp;&nbsp;[<a href=\"statequalifiers.php?session=$session&catid=$catid&reset=1\">Reset to Order Submitted by Judge</a>]</h2>";
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
         if($row[classrank]==1) echo " (Champ)";
	 else if($row[overallrank]>12) echo " (Alternate, Judge's Rank: $row[overallrank])";
         else echo " (Judge's Rank: $row[overallrank])";
         //Link to Remove from Qualifiers
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  	 echo "<a class='small' href=\"statequalifiers.php?session=$session&catid=$catid&deletequal=$row[qualid]\" onClick=\"return confirm('Are you sure you want to remove this entry from the State Qualifiers list? (Note that this will NOT change the ranking the judge gave the entry. You can add this entry back to the list of qualifiers later if you wish.)');\">Delete from Qualifiers List</a>";
         echo "</li>";
         $ix++;
      }
      echo "</ul>";
      if(!$submitted || $level==1)
         echo "<input type=hidden name='hiddensaveentries' id='hiddensaveentries'><input type=submit class='fancybutton2' name=saveentries value=\"Save Order\" onclick=\"document.getElementById('entryorder').value=junkdrawer.inspectListOrder('lines');\">";
      echo "</td><td>";

	//ADD QUALIFIERS TO THE LIST:
      $ix=0;
      //Missing Class Champs:
      echo "<br><table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"width:100%;border:#808080 1px solid;\">";
      echo "<caption><b>Class Champions Missing from Qualifiers List:</b>";
      $sql="SELECT t1.* FROM joentries AS t1 LEFT JOIN joqualifiers AS t2 ON t1.id=t2.entryid WHERE t1.classrank=1 AND t2.id IS NULL AND t1.catid='$catid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
	 echo "<p style=\"text-align:left;\"><i>Check the box next to an entry and click \"Add to List of Qualifiers\" to add it to the list on the left.</p></caption>";
	 echo "<tr bgcolor='#f0f0f0' align=center><td>Add</td><td>Class Champion</td></tr>";
         while($row=mysql_fetch_array($result))
         {
	    echo "<tr><td align=center><input type=checkbox name=\"addcheck[$ix]\" value='x'><input type=hidden name=\"addentryid[$ix]\" value=\"$row[id]\"></td>";
	    echo "<td>".GetStudentInfo($row[studentid],FALSE);
	    if($row[studentid2]>0) echo ", et al";
	    echo ", ".GetSchoolName($row[sid],'jo')." - Class ".GetClass($row[sid],'jo')." Champ</td></tr>";
	    $ix++;
	 }
      }
      else
         echo "<p><i>No class champions are missing from the list of qualifiers.</i></p></caption>";
      echo "</table><br>";

      //Alternates:
      echo "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"width:100%;border:#808080 1px solid;\">";
      echo "<caption><b>Alternates:</b>";
      $sql="SELECT t1.* FROM joentries AS t1 LEFT JOIN joqualifiers AS t2 ON t1.id=t2.entryid WHERE t1.overallrank>12 AND t1.overallrank<=15 AND t2.id IS NULL AND t1.catid='$catid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         echo "<p style=\"text-align:left;\"><i>Check the box next to an entry and click \"Add to List of Qualifiers\" to add it to the list on the left.</p></caption>";
         echo "<tr bgcolor='#f0f0f0' align=center><td>Add</td><td>Alternate</td></tr>";
         while($row=mysql_fetch_array($result))
         {
            echo "<tr><td align=center><input type=checkbox name=\"addcheck[$ix]\" value='x'><input type=hidden name=\"addentryid[$ix]\" value=\"$row[id]\"></td>";
            echo "<td>".GetStudentInfo($row[studentid],FALSE);
            if($row[studentid2]>0) echo ", et al";
            echo ", ".GetSchoolName($row[sid],'jo')." - Class ".GetClass($row[sid],'jo')."</td></tr>";
	    $ix++;
         }
      }
      else
         echo "<p><i>No alternates are missing from the list of qualifiers.</i></p></caption>";
      echo "</table><br>";

      //Originally Ranked in Top 12 by Judge
      echo "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"width:100%;border:#808080 1px solid;\">";
      echo "<caption><b>Entries Originally Ranked in Top 12 by Judge:</b>";
      $sql="SELECT t1.* FROM joentries AS t1 LEFT JOIN joqualifiers AS t2 ON t1.id=t2.entryid WHERE t1.overallrank>=1 AND t1.overallrank<=12 AND t2.id IS NULL AND t1.catid='$catid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         echo "<p style=\"text-align:left;\"><i>Check the box next to an entry and click \"Add to List of Qualifiers\" to add it to the list on the left.</p></caption>";
         echo "<tr bgcolor='#f0f0f0' align=center><td>Add</td><td>Top-12 Ranked Entry</td></tr>";
         while($row=mysql_fetch_array($result))
         {
            echo "<tr><td align=center><input type=checkbox name=\"addcheck[$ix]\" value='x'><input type=hidden name=\"addentryid[$ix]\" value=\"$row[id]\"></td>";
            echo "<td>".GetStudentInfo($row[studentid],FALSE);
            if($row[studentid2]>0) echo ", et al";
            echo ", ".GetSchoolName($row[sid],'jo')." - Class ".GetClass($row[sid],'jo')."</td></tr>";
	    $ix++;
         }
      }
      else
         echo "<p><i>No entries originally ranked in the Top 12 are missing from the list of qualifiers.</i></p></caption>";
      echo "</table><br>";

      echo "<input type=submit class='fancybutton' name='addqualifiers' value='<< Add to List of Qualifiers'>";
      
       echo "</td></tr></table>";
}//END IF CATID
echo "</form>";
echo $end_html;
?>
