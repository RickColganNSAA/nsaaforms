<?php
/*******************************************
judgemain.php
Main Landing Page for Logged in JO Judge
Created 11/15/12
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
if(!ValidJOJudge($session))
{
   header("Location:index.php?error=1");
   exit();
}
$judgeid=GetJOJudgeID($session);
if($judgeid) $level=2;
$catids=explode(",",GetJOJudgeCategory($judgeid));
if(!$catid && count($catids)==1) $catid=$catids[0];

//GET CLASSES
$sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
$result=mysql_query($sql);
$classes=array(); $c=0;
while($row=mysql_fetch_array($result))
{
   $classes[$c]=$row['class']; $c++;
}

            $sql3="SELECT * FROM joassignments WHERE judgeid='$judgeid' AND catid='$catid'";
            $result3=mysql_query($sql3);
            $row3=mysql_fetch_array($result3);
            if($row3[datesub]==0) $submitted=0;
	    else $submitted=1;

if($hiddensaveentries || $saveentries)
{
   //SAVE ORDER
   $order=split("[|]",$entryorder);
   $rank=1;
   if($class!='') $field="classrank";
   else $field="overallrank";
   for($i=0;$i<count($order);$i++)
   {
      $id=trim($order[$i]);
      $sql="UPDATE joentries SET $field='$rank' WHERE id='$id'";
      $result=mysql_query($sql);
      $rank++;
   }
}

if($submitrankings && $satisfied=='x')
{
   $sql="UPDATE joassignments SET datesub='".time()."' WHERE judgeid='$judgeid' AND catid='$catid'";
   $result=mysql_query($sql);
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
if(!$submitted || $level==1)
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

echo "<br><h3><i>Welcome, ".GetJOJudgeName($session)."!</i></h3>";
echo "<form method=post action=\"judgemain.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

$duedate=date("Y")."-04-13";
$duedate2="April 13, ".date("Y");

if(count($catids)>1)	//SELECT FROM CATEGORIES
{
   $cats="";
   for($i=0;$i<count($catids);$i++)
   {
      $cats.="<u>".GetJOCategory($catids[$i])."</u> and ";
   }
   $cats=substr($cats,0,strlen($cats)-5);
   echo "<div class='alert' style='width:600px;text-align:center;'><p>You have been assigned to judge <b>".$cats.".</b> Judging must be completed no later than <b><u>$duedate2</b></u>.</p>";
   echo "<p><b>Select a Category to Judge:</b> <select name=\"catid\" onChange=\"submit();\"><option value='0'>Select Category</option>";
   for($i=0;$i<count($catids);$i++)
   {
      echo "<option value=\"$catids[$i]\"";
      if($catid==$catids[$i]) echo " selected";
      echo ">".GetJOCategory($catids[$i])."</option>";
   }
   echo "</select></p></div><br>";
   if($catid)
      echo "<h3>You are judging <u>".GetJOCategory($catid)."</u>.</h3>";
}
else if(count($catids)==1)
{
   $catid=$catids[0];
   echo "<h3>You have been assigned to judge <u>".GetJOCategory($catid)."</u>. Judging must be completed no later than <u>$duedate2</u>.</h3>";
}
else //NO ASSIGNMENTS
{
   echo "<p>You have not been assigned to any event categories by the NSAA yet.</p>";
   echo $end_html;
   exit();
}
if($catid)
{
   //CATEGORY DESCRIPTION
   $sql="SELECT * FROM jocategories WHERE id='$catid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<div class='help' style='text-align:left;width:700px;'>";
   echo "<h3 style='text-align:center;'>$row[header]</h3><p><b>Criteria for judging:</b> $row[description]</p></div>";

   echo "<h4>Choose the CLASS you wish to judge:</b>&nbsp;<select name=\"class\" onChange=\"submit();\"><option value=''>Select a CLASS</option>";
   $sql="SELECT DISTINCT class FROM joschool WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[0]\"";
      if($class==$row[0]) echo " selected";
      echo ">Class $row[0]</option>";
   }
   echo "</select></h4>";
   //echo "<p style='color:green;'><b>(NOTE: It may be easier to judge the individual classes before ranking all of the entries for the Overall category.)</b></p>";

   if($class!='')	//SPECIFIC CLASS
   {
      //GET SUBMISSIONS FOR THIS CATEGORY - ALLOW JUDGE TO MOVE THEM AROUND, PUT TOP 5 IN ORDER
      $sql="SELECT t1.* FROM joentries AS t1,eligibility AS t2,joschool AS t3 WHERE t1.sid=t3.sid AND t1.studentid=t2.id AND t1.catid='$catid' AND t3.class='$class'";
      	//CHECK IF ANY HAVE BEEN RANKED YET
        $sql2=$sql." AND classrank>0 ORDER BY t1.classrank";
        $result2=mysql_query($sql2);
        if(mysql_num_rows($result2)>0) $ranked=1;
	else $ranked=0;
      $sql.=" ORDER BY t1.classrank,t2.school,t2.last,t2.first";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo "<p><i>No entries have been submitted yet.</i></p>";
         echo $end_html;
         exit();
      }
      //INSTRUCTIONS
      echo "<div style=\"width:700px;text-align:left;\"><p><b>INSTRUCTIONS:</b></p>";
      if($ranked==0)
      {
         echo "<ul><li>Below are the entry submissions for <b>CLASS $class ".GetJOCategory($catid)."</b>, shown in <b>alphabetical order by school and then student name</b>.</li>";
         echo "<li>Please arrange the submissions so that the <b>TOP 5 ENTRIES</b> are positioned at the top, with <b>1st Place</b> in the upper left corner, <b>2nd Place</b> immediately to the right, followed by <b>3rd, 4th and 5th Place</b>.</li>";
      }
      else
      {
         echo "<ul><li>Below are the entry submissions for <b>CLASS $class ".GetJOCategory($catid)."</b>, with the <b>Top 12</b> showing first, as you have ranked them.</li>";
         echo "<li>If you need to change your rankings, you can re-arrange the submissions to show your desired <b>Top 12 submissions</b> in the top row.</li>";
      }
      echo "<li>NOTE: Only the <b>Top 3</b> will be scored, but please rank the <b>Top 12</b> in case alternates are needed.</li>";
      echo "<li>You can move a submission by clicking and dragging it to its desired location.</li><li>Click \"Save Order\" to save your rankings.</li></ul>";
      echo "<p style='text-align:center;'>Arrange your Top 12 entries as shown below:<br>";
        echo "<table style='width:100%;'><tr align=center><td><table cellspacing=0 cellpadding=8 frame=all rules=all style=\"border:#808080 1px solid;\">
                <tr align=center><td>1</td><td>2</td><td>3</td><td>4</td></tr>
                <tr align=center><td>5</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        </table></td></tr></table></p>";
      echo "</div>";
      if($ranked==1)
      {
         $errors="";
         // Is anyone ranked ahead of another entry in their class when they were ranked behind them on the class page?
         $curlowrank=17;
         while($row2=mysql_fetch_array($result2))
         {
            if($row2[overallrank]<$curlowrank)       //IF THIS ENTRY'S CLASS RANK IS LESS THAN THE LOWEST RANKED IN THEIR CLASS ABOVE THEM, WE HAVE A PROBLEM (unless $curlowrank is 17)
            {
               if($curlowrank<17 && $row2[classrank]<=5)
	       {
                  $errors.=$row2[id].",";
	       }
            }
            if($row2[overallrank]<16) $curlowrank=$row2[overallrank];
            else $curlowrank=16;
         }
         if($errors!='')
         {
            $errors=substr($errors,0,strlen($errors)-1);
            $err=explode(",",$errors);
            echo "<div class='error' style='width:600px;font-size:12px;'><p><b>If you rank Entry A AHEAD of Entry B in the Class rankings, you cannot rank Entry A BEHIND Entry B in the Overall rankings. Or vice versa.</b></p><p>You have done so in this class (Class $class).</p>";
            echo "<p>Please adjust either the Overall rankings or the Class rankings to fix this issue.</p></div>";
         }
      }
      echo "<input type=hidden name=\"entryorder\" id=\"entryorder\"><ul id=\"boxes\" style=\"width:750px;\">";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         if($row[classrank]==1) $rank="1st";
         else if($row[classrank]==2) $rank="2nd";
         else if($row[classrank]==3) $rank="3rd";
	 else if($row[classrank]==4 || $row[classrank]==5) $rank=$row[classrank]."th";
         if($row[overallrank]>0 && $row[overallrank]<=15) $orank=date("jS",mktime(0,0,0,1,$row[overallrank],date("Y"))); //GETS "nth" version of number
	 else $orank="";
         echo "<li id=\"$row[id]\"";
         if($row[classrank]<=5 && $row[classrank]>0) 
         {
	    echo " style=\"border:#000000 1px solid; background-color:#bee0fb;\"><label class='highlight'><b>$rank</b>";
	    if($orank!='') echo " ($orank Overall)";
     	    echo "</label>";
 	 }
    	 else 
  	 {
	    echo ">";
            if($orank!='') echo "<b>($orank Overall)</b>";
	 }
         echo "<input type=hidden name=\"entryids[$ix]\" value=\"$row[id]\">";
         echo "<h3 style='margin:8px 3px 7px 3px;'>ENTRY #".$row[id]."</h3>";
	/*
	GetSchoolName($row[sid],'jo')."</h3><p>".GetStudentInfo($row[studentid],FALSE);
            $j=2;
            while($j<=6)
            {
               $studvar="studentid".$j;
               if($row[$studvar]>0) echo ", ".GetStudentInfo($row[$studvar],FALSE);
               $j++;
            }
            echo "</p>";
         echo "<p><a class='small' href=\"../downloads/$row[filename]\" target=\"_blank\">$row[label]</a></p>";
         if($row[filename2]!='')
               echo "<p><a class='small' href=\"../downloads/$row[filename2]\" target=\"_blank\">$row[label2]</a></p>";
	*/
	if($row[filename2]!='')
           echo "<p><a href=\"../downloads/$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE #1</a></p><p><a href=\"../downloads/$row[filename2]\" target=\"_blank\">CLICK TOP OPEN FILE #2</a></p>";
	else
	   echo "<p><a href=\"../downloads/$row[filename]\" target=\"_blank\">CLICK TO OPEN FILE</a></p>";
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
	 if($ix==5 && $ranked==1) echo "<div style='clear:both;'></div>";
      }
      echo "</ul><div style='clear:both;'></div>";
      if(!$submitted || $level==1)
	 echo "<input type=hidden name='hiddensaveentries' id='hiddensaveentries'><input type=submit class='fancybutton2' name=saveentries value=\"Save Order\" onclick=\"document.getElementById('entryorder').value=junkdrawer.inspectListOrder('boxes');\">";
   }// END IF CLASS
   else //OVERALL RANKINGS	 - deactivated 2/19/15
   {
	/*
      //GET SUBMISSIONS FOR THIS CATEGORY - ALLOW JUDGE TO MOVE THEM AROUND, PUT TOP 15 IN ORDER
      $sql="SELECT t1.*,t3.class FROM joentries AS t1,eligibility AS t2,joschool AS t3 WHERE t1.sid=t3.sid AND t1.studentid=t2.id AND t1.catid='$catid'";
	//CHECK IF ANYONE HAS BEEN RANKED (OVERALL) YET
	$sql2=$sql." AND t1.overallrank>0 ORDER BY t1.overallrank";
 	$result2=mysql_query($sql2);
	if(mysql_num_rows($result2)>0) $ranked=1;
	else $ranked=0;
      $sql.=" ORDER BY t1.overallrank,t1.classrank,t3.class,t2.school,t2.last,t2.first";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo "<p><i>No entries have been submitted yet.</i></p>";
         echo $end_html;
         exit();
      }
      //INSTRUCTIONS
      //CHECK FOR ERRORS
      if($ranked==1)	
      {
	 $errors="";
	 // Is anyone ranked ahead of another entry in their class when they were ranked behind them on the class page?
	 for($c=0;$c<count($classes);$c++)
	 {
            $lowrank[$classes[$c]]=5; 
	 }
	 while($row2=mysql_fetch_array($result2))
         {
	    $class=$row2['class'];
	    if($row2[classrank]<$lowrank[$class])	//IF THIS ENTRY'S CLASS RANK IS LESS THAN THE LOWEST RANKED IN THEIR CLASS ABOVE THEM, WE HAVE A PROBLEM (unless $lowrank[$class] is 5)
	    {
	       if($lowrank[$class]<5 && $row2[overallrank]<=15)
	       {
	          $errors.=$row2[id].","; 
	       }
	       //else echo $lowrank[$class]." $class,$row2[classrank]<br>";
	    }
            if($row2[classrank]<4) $lowrank[$class]=$row2[classrank];
	    else $lowrank[$class]=4;
	 }
	 if($errors!='')
	 {
	    $errors=substr($errors,0,strlen($errors)-1);
	    $err=explode(",",$errors);
	    echo "<div class='error' style='width:600px;font-size:12px;'><p><b>If you rank Entry A AHEAD of Entry B in the Class rankings, you cannot rank Entry A BEHIND Entry B in the Overall rankings. Or vice versa.</b></p><p>You have done so in the following classes:</p><ul>";
	    $errors="";
	    for($i=0;$i<count($err);$i++)
	    {
	       $sql2="SELECT * FROM joentries WHERE id='$err[$i]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
               $class=GetClass($row2[sid],'jo');
	       if(!preg_match("/Class $class/",$errors))
	 	  $errors.="<li style='color:#ffffff;'>Class $class</li>";
	    }
	    echo $errors."</ul>";
	    echo "<p>Please adjust either the Overall rankings or the Class rankings to fix this issue.</p></div>";
	 }
	 else	//THEY CAN SUBMIT
	 {
	    echo "<div class='highlight' style='width:600px;text-align:left;'>";
	    if($submitted==0)
	    {
	       echo "<p>Your rankings have no errors and can be submitted at this time. Once you are satisfied with your Top 12 rankings in each class, you may check the box below to submit them to the NSAA.</p>";
	       echo "<p";
	       if($submitrankings && $satisfied!='x') echo " style=\"background-color:yellow;\"";
	       echo "><input type=checkbox name='satisfied' value='x'> <b><i>I am satisfied with my rankings and wish to submit them to the NSAA.</i></b></p>";
               if($submitrankings && $satisfied!='x') echo "<p><i>Please check the box above to verify you are satisfied with your rankings.</i></p>";
	       echo "<input type=submit name=\"submitrankings\" value=\"Submit Rankings to the NSAA\" class=\"fancybutton2\">";
	    }
	    else
	    {
	       echo "<p><b>Your rankings were submitted to the NSAA on <u>".date("F j, Y",$row3[datesub])."</u>. </b>You can no longer edit your rankings. If there is an issue with your submitted rankings, please contact the NSAA.</p>";
	    }
	    echo "</div>";
	 }
      }
      if($ranked!=1 || $errors!='')
      {
      echo "<div style=\"text-align:left;width:700px;\"><p><b>INSTRUCTIONS:</b></p>";
      if($ranked==0)
      {
         echo "<ul><li>Below are the <b>".GetJOCategory($catid)." entry submissions for ALL CLASSES</b>, showing in <b>alphabetical order by Class, then School and then Student Name</b>.</li>";
         echo "<li>Please arrange the submissions so that the <b>OVERALL TOP 15 ENTRIES</b> are positioned at the top, with <b>1st Place</b> in the upper left corner, <b>2nd Place</b> immediately to the right, followed by <b>3rd Place</b>, and so on.</li>";
      }
      else
      {
         echo "<ul><li>Below are the <b>".GetJOCategory($catid)." entry submissions for ALL CLASSES</b>, with the <b>Top 15</b> showing first, as you have ranked them.</li>";
         echo "<li>If you need to change your rankings, you can re-arrange the submissions to show your desired <b>Top 15 submissions</b> in order from left to right, top to bottom.</li>";
      }
      echo "<li>You can move a submission by clicking and dragging it to its desired location.</li><li>Click \"Save Order\" to save your rankings.</li></ul>";
      echo "<p style='text-align:center;'>Arrange your Top 15 entries as shown below:<br>";
        echo "<table style='width:100%;'><tr align=center><td><table cellspacing=0 cellpadding=8 frame=all rules=all style=\"border:#808080 1px solid;\">
                <tr align=center><td>1</td><td>2</td><td>3</td><td>4</td></tr>
                <tr align=center><td>5</td><td>6</td><td>7</td><td>8</td></tr>
                <tr align=center><td>9</td><td>10</td><td>11</td><td>12</td></tr>
                <tr align=center><td>13</td><td>14</td><td>15</td><td>&nbsp;</td></tr>
        </table></td></tr></table></p>";
      echo "</div>";
      }
      echo "<input type=hidden name=\"entryorder\" id=\"entryorder\"><ul id=\"boxes\" style=\"width:750px;\">";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
	 $class=GetClass($row[sid],'jo');
         if($row[classrank]==1) $classrank="1st in Class $class";
         else if($row[classrank]==2) $classrank="2nd in Class $class";
         else if($row[classrank]==3) $classrank="3rd in Class $class";
         else if($row[classrank]==4 || $row[classrank]==5) $classrank=$row[classrank]."th in Class $class";
         if($row[overallrank]>0 && $row[overallrank]<=15) $rank=date("jS",mktime(0,0,0,1,$row[overallrank],date("Y")));	//GETS "nth" version of number
         echo "<li id=\"$row[id]\"";
	 if($row[overallrank]<=15 && $row[overallrank]>0)
 	 {
            echo " style=\"background-color:#83f189;\"><label class='highlight'><b>$rank</b></label>";
	    if($row[classrank]<=5 && $row[classrank]>0)
	       echo "<br><label class='highlight'><b>($classrank)</b></label>";
 	 }
         else if($row[classrank]<=5 && $row[classrank]>0) 
	    echo " style=\"background-color:#bee0fb;\"><b>$classrank</b>";
         else echo ">";
         echo "<input type=hidden name=\"entryids[$ix]\" value=\"$row[id]\">";
         echo "<h3 style='margin:3px;'>".GetSchoolName($row[sid],'jo')." ($class)</h3><p>".GetStudentInfo($row[studentid])."</p>";
         echo "<p><a class='small' href=\"../downloads/$row[filename]\" target=\"_blank\">$row[label]</a></p>";
         echo "</li>";
         $ix++;
      }
      echo "</ul><div style='clear:both;'></div>";
      if(!$submitted || $level==1)
         echo "<input type=hidden name='hiddensaveentries' id='hiddensaveentries'><input type=submit class='fancybutton2' name=saveentries value=\"Save Order\" onclick=\"document.getElementById('entryorder').value=junkdrawer.inspectListOrder('boxes');\">";
	*/
      echo "<p><b><i>Please select a Class above.</i></b></p>";
   }	//END IF NO CLASS SELECTED
}//END IF CATID
echo "</form>";
echo $end_html;
?>
