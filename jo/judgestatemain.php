<?php
/*******************************************
judgestatemain.php
Created 4/15/18
Author: cricalitgroup
*******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header=GetJOStateHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidJOStateJudge($session))
{
   header("Location:joindex.php?error=1");
   exit();
}
$judgeid=GetJOStateJudgeID($session);
if($judgeid) $level=2;
$assignids=explode(",",GetJOStateJudgeAssignment($judgeid));

if(!$assignid && count($assignids)==1) $assignid=$assignids[0];
$catids=array(); $classes=array();
for($i=0;$i<count($assignids);$i++)
{
   $sql="SELECT catid,class FROM jostateassignments WHERE id='$assignids[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $catids[$i]=$row[0]; $classes[$i]=$row[1];
   if($assignid==$assignids[$i])
   {
      $catid=$catids[$i]; $class=$classes[$i];
   }
} 

if($hiddensaveentries || $saveentries)
{  //echo '<pre>';print_r($_POST);exit;
   //SAVE ORDER
   $order=split("[|]",$entryorder);
   $rank=1;
   if($class!='') $field="classrank";
   else $field="overallrank";
   for($i=0;$i<count($order);$i++)
   {
      $id=trim($order[$i]);
      $sql="UPDATE jostateentries SET $field='$rank' WHERE id='$id'";
      $result=mysql_query($sql);
      $rank++;
   }
}

if($submitrankings && $satisfied=='x')
{
   $sql="UPDATE jostateassignments SET datesub='".time()."' WHERE judgeid='$judgeid' AND id='$assignid'";
   $result=mysql_query($sql);
}
if($assignid)   //CHECK IF SUBMITTED
{
   $sql3="SELECT * FROM jostateassignments WHERE judgeid='$judgeid' AND id='$assignid'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   $submitted=$row3[datesub];
}

echo $init_html_ajax;
echo "
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/core.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/events.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/css.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/coordinates.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/drag.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/dragsort.js\"></script>
            <script type=\"text/javascript\" src=\"tool-man/source/org/tool-man/cookies.js\"></script>";
?>
<script type="text/javascript" src="/javascript/Journalism.js"></script>
</head>
<body onLoad="Journalism.initialize();">
<div id="loading" style="display:none;"></div>
<?php
echo $header;
if(!$assignid || ($assignid && !$submitted) || $level==1)
{
?>
<script type="text/javascript" language="JavaScript">
var dragsort = ToolMan.dragsort()
var junkdrawer = ToolMan.junkdrawer()
window.onload = function() {
       //junkdrawer.restoreListOrder('boxes')
       dragsort.makeListSortable(document.getElementById('boxes'),saveOrder)
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
}

echo "<br><h3><i>Welcome, ".GetJOStateJudgeName($session)."!</i></h3>";
echo "<form method=post action=\"judgestatemain.php#rankingstop\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

$duedate=GetJOStateSetting('judgeduedate');
$duedate2=date("F j, Y",strtotime($duedate));
$duedate3=strtotime($duedate);

if(count($assignids)>1)	//SELECT FROM CATEGORIES
{
   $assigns="";
   for($i=0;$i<count($assignids);$i++)
   {
      $assigns.="<u>".GetJOStateCategory($catids[$i])." (Class $classes[$i])</u> and ";
   }
   $assigns=substr($assigns,0,strlen($assigns)-5);
   echo "<div class='alert' style='width:600px;text-align:center;'><p>You have been assigned to judge <b>".$assigns.".</b> Judging must be completed no later than <b><u>$duedate2</b></u>.</p>";
   echo "<p><b>Select a Category and Class to Judge:</b> <select name=\"assignid\" onChange=\"submit();\"><option value='0'>Select Category & Class</option>";
   for($i=0;$i<count($assignids);$i++)
   {
      echo "<option value=\"$assignids[$i]\"";
      if($assignid==$assignids[$i]) echo " selected";
      echo ">".GetJOStateCategory($catids[$i])." - Class $classes[$i]</option>";
   }
   echo "</select></p></div><br>";
   if($assignid)
      echo "<h3>You are judging <u>".GetJOStateCategory($catid)." (Class $class)</u>.</h3>";
}
else if(count($assignids)==1)
{
   //echo "<h3>You have been assigned to judge <u>".GetJOStateCategory($catid)." - Class $class</u>. Judging must be completed no later than <u>$duedate2</u>.</h3>";
   echo "<h3>You have been assigned to judge <u> Class $class</u>. Judging must be completed no later than <u>$duedate2</u>.</h3>";
}
else //NO ASSIGNMENTS
{
   echo "<p>You have not been assigned to any event categories by the NSAA yet.</p>";
   echo $end_html;
   exit();
}
if($assignid)
{
   //CATEGORY DESCRIPTION
   $sql="SELECT * FROM jostatecategories WHERE id='$catid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<div class='help' style='text-align:left;width:700px;'>";
   echo "<h3 style='text-align:center;'>$row[header]</h3><p><b>Criteria for judging:</b> $row[description]</p></div>";

	//RANKED YET?
   $sql2="SELECT t1.* FROM jostateentries AS t1,eligibility AS t2,joschool AS t3 WHERE t1.sid=t3.sid AND t1.studentid=t2.id AND t1.catid='$catid' AND t1.class='$class' AND classrank>0";
   /* if ($class=='A')$catid=1; else $catid=2;
   $sql2="SELECT t1.* FROM jostateentries AS t1,eligibility AS t2,joschool AS t3 WHERE t1.sid=t3.sid AND t1.studentid=t2.id AND t1.catid='$catid'  AND classrank>0";
    */$result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0) $ranked=1;
   else $ranked=0;
	//ANY ENTRIES YET?
   $sql="SELECT t1.* FROM jostateentries AS t1,eligibility AS t2,joschool AS t3 WHERE t1.sid=t3.sid AND t1.studentid=t2.id AND t1.catid='$catid' AND t1.class='$class' ORDER BY classrank ASC";
   /* if ($class=='A')$catid=1; else $catid=2;
   $sql="SELECT t1.* FROM jostateentries AS t1,eligibility AS t2,joschool AS t3 WHERE t1.sid=t3.sid AND t1.studentid=t2.id AND t1.catid='$catid'  ORDER BY classrank ASC";
    */$result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "<p><i>No entries have been submitted for this class yet.</i></p>";
      echo $end_html;
      exit();
   }

   //IF WE GET HERE, THERE ARE ENTRIES TO RANK
      //INSTRUCTIONS
   echo "<div style=\"width:700px;text-align:left;\"><p><b>INSTRUCTIONS:</b></p>";
   if($ranked==0)
   {
      echo "<ul><li>Below are the entry submissions for <b>CLASS $class ".GetJOStateCategory($catid)."</b>, shown in <b>alphabetical order by school and then student name</b>.</li>";
      echo "<ul><li>Below are the entry submissions for <b>CLASS $class </b>, shown in <b>alphabetical order by school and then student name</b>.</li>";
      echo "<li>Please arrange the submissions so that the <b>TOP <span style=\"background-color:yellow;font-size:125%;\"><u>6</u></span> ENTRIES</b> are positioned at the top, with <b>1st Place</b> in the upper left corner, <b>2nd Place</b> immediately to the right, followed by <b>3rd and 4th place</b>. Then move <b>5th Place</b> to the first position in the second row, <b>6th place</b> to the right of 5th, and so on until you have ranked the top 6 (or less if there are not 6 entries in your event). <!--Please note that your 13th, 14th and 15th-ranked entries will be used in the event that ALTERNATES are needed ONLY.--></li>";
   }
   else
   {
      echo "<ul><li>Below are the entry submissions for <b>CLASS $class ".GetJOStateCategory($catid)."</b>, with the <b>Top 6</b> showing first, as you have ranked them.</li>";
      //echo "<li>If you need to change your rankings, you can re-arrange the submissions to show your desired <b>Top 15 submissions</b> in the top row.</li>";
   }
   echo "<li>You can move a submission by clicking and dragging it to its desired location.</li><li>Click \"Save Order\" to save your rankings.</li>
	<!--<li>Where you have <u><b>more than one entry from a single person</u></b>, please rank only the best entry submitted from that student. <b>A student cannot place more than once in the same event.</b></li>-->
	<li><b>Once you are satisfied with your rankings,</b> you will need to check the box indicating you are ready to submit final rankings to the NSAA.</li></ul>";
      echo "<p style='text-align:center;'>Arrange your Top 6 entries as shown below, with the rest of the entries following in an order that does not matter:<br>";
      echo "<table style='width:100%;'><tr align=center><td><table cellspacing=0 cellpadding=8 frame=all rules=all style=\"border:#808080 1px solid;\">
             <tr align=center><td>1</td><td>2</td><td>3</td><td>4</td></tr>
             <tr align=center><td>5</td><td>6</td><td>7</td><td>8</td></tr>
             <tr align=center><td>9</td><td>10</td><td>11</td><td>12</td></tr>
             <tr align=center><td>13</td><td>14</td><td>15</td><td>-</td></tr>
             <tr align=center><td>-</td><td>-</td><td>-</td><td>-</td></tr>
        </table><a name='rankingstop'>&nbsp;</a>...&nbsp;</td></tr></table></p>";
	//IF $ranked, CAN SUBMIT TO NSAA:
      if($ranked==1)    //THEY CAN SUBMIT WHEN THEY ARE READY
      {     
	        if($duedate3>time()){
            echo "<div class='highlight' style='padding:16px;text-align:left;'>";
            
			if($submitted==0)
            {
               echo "<p>Once you are satisfied with your Top 6 rankings in this event, you may check the box below to submit them to the NSAA.</p><h3>";
               echo "<input type=checkbox name='satisfied' value='x'> <i>I am satisfied with my rankings and wish to submit them to the NSAA.</i></h3>";
               if($submitrankings && $satisfied!='x') echo "<p style=\"color:red;font-weight:bold;\"><i>Please check the box above to verify you are satisfied with your rankings.</i></p>";
               echo "<input type=submit name=\"submitrankings\" value=\"Submit Rankings to the NSAA\" class=\"fancybutton2\">";
            }
            else
            {
               echo "<p><b>Your rankings were submitted to the NSAA on <u>".date("F j, Y",$submitted)."</u>. </b>You can no longer edit your rankings. If there is an issue with your submitted rankings, please contact the NSAA.</p>";
            }
			
            echo "</div>";
			}
      }
	//END SUBMIT TO NSAA
      echo "</div>";
      echo "<input type=hidden name=\"entryorder\" id=\"entryorder\"><ul id=\"boxes\" style=\"width:750px;\">";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         if($row[classrank]==1) $rank="1st";
         else if($row[classrank]==2) $rank="2nd";
         else if($row[classrank]==3) $rank="3rd";
	 else if($row[classrank]>0 && $row[classrank]<=15) $rank=$row[classrank]."th";
         echo "<li id=\"$row[id]\"";
         if($row[classrank]<=15 && $row[classrank]>0) 
	    echo " style=\"border:#000000 1px solid; background-color:#bee0fb;\"><label class='highlight'><b>$rank</b></label>";
    	 else 
	    echo ">";
         echo "<input type=hidden name=\"entryids[$ix]\" value=\"$row[id]\">";
         echo "<h3 style='margin:8px 3px 7px 3px;'>ENTRY #".$row[id]."</h3><p>".GetStudentInfo($row[studentid],FALSE)."</p>";
	if($row[filename2]!=''){
           if ((preg_match('/http/', $row[filename])) && (preg_match('/http/', $row[filename2])))
		   echo "<p><a href=\"$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE #1</a></p><p><a href=\"$row[filename2]\" target=\"_blank\">CLICK TOP OPEN FILE #2</a></p>";
		   else if ((preg_match('/http/', $row[filename])) && !(preg_match('/http/', $row[filename2])))
		   echo "<p><a href=\"$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE #1</a></p><p><a href=\"../downloads/$row[filename2]\" target=\"_blank\">CLICK TOP OPEN FILE #2</a></p>";
		   else if (!(preg_match('/http/', $row[filename])) && (preg_match('/http/', $row[filename2])))
		   echo "<p><a href=\"../downloads/$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE #1</a></p><p><a href=\"$row[filename2]\" target=\"_blank\">CLICK TOP OPEN FILE #2</a></p>";
		   else if (!(preg_match('/http/', $row[filename])) && !(preg_match('/http/', $row[filename2])))
		   echo "<p><a href=\"../downloads/$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE #1</a></p><p><a href=\"../downloads/$row[filename2]\" target=\"_blank\">CLICK TOP OPEN FILE #2</a></p>";
		   else
		   echo "<p><a href=\"../downloads/$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE #1</a></p><p><a href=\"../downloads/$row[filename2]\" target=\"_blank\">CLICK TOP OPEN FILE #2</a></p>";
		   }
	else{
	   if ((preg_match('/http/', $row[filename])))
	   echo "<p><a href=\"$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE</a></p>";
	   else
	   echo "<p><a href=\"../downloads/$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE</a></p>";
	   }
	   //JUDGE COMMENTS LINK:
         if(trim($row[judgecomments])!='') 
         {
	    $snippet=substr($row[judgecomments],0,25)."..."; $addedit="Edit";
	    $row[judgecomments]=preg_replace("/\<br\>/","\r\n",$row[judgecomments]);
     	 }
         else 
	 { 
	    $snippet=""; $addedit="Add";
  	 }
	 echo "<div id=\"thecomment".$ix."\">$snippet</div><input type=button id=\"addcomment".$ix."\" name=\"addcomment[$ix]\" value=\"$addedit Comments\" onClick=\"Utilities.getElement('commentbox".$ix."').style.display='';\">";
         echo "</li>";
	 echo "<div style=\"position:relative;\"><div id=\"commentbox".$ix."\" style=\"display:none;position:absolute;z-index:100;\" class=\"alert\"><input type=hidden name=\"entryid[$ix]\" id=\"entryid".$ix."\" value=\"$row[id]\"><p><b>Enter feedback or other comments regarding this entry:</b></p><textarea name=\"comments[$ix]\" id=\"comments".$ix."\" style=\"height:75px;width:350px;\">$row[judgecomments]</textarea><br /><input type=button name=\"savecomments".$ix."\" id=\"savecomments".$ix."\" value=\"Save\" onClick=\"Journalism.saveJudgeComment('".$ix."');\"></div></div>";
         $ix++;
	 if($ix==15 && $ranked==1) echo "<div style='clear:both;'></div>";
      }
      echo "</ul><div style='clear:both;'></div>";
      if(!$submitted || $level==1){
	  if ($duedate3 > time())
	  echo "<input type=hidden name='hiddensaveentries' id='hiddensaveentries'><input type=submit class='fancybutton2' name=saveentries value=\"Save Order\" onclick=\"document.getElementById('entryorder').value=junkdrawer.inspectListOrder('boxes');\">";
     	 else
	 echo "<b style=\"font-coler:red !important\">Submission date is over<b>";
     }
	 }//END IF CATID
echo "</form>";
echo $end_html;
?>
