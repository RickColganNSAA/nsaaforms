<?php
/***************************************************
addtest2question.php
Add a question to the pool for Part 2 online test
Created 9/6/11 by Ann Gaffigan
****************************************************/
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

//get tables for this sport's test
$test=$sport."test2";
$categ=$sport."test2_categ";
$mchoices=$sport."test2_mchoices";
$sportname=GetSportName($sport);
$letters=array("a","b","c","d","e","f","g");

if($save && trim($question)!='')
{
   $question=addslashes($question); $reference=addslashes($reference);
   if(!$quesid)	//NEW QUESTION
   {
      $sql="SELECT * FROM $test ORDER BY place DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0) $place=1;
      else $place=$row[place]+1;
      $sql="INSERT INTO $test (question,answer,reference,place) VALUES ('$question','$correct','$reference','$place')";
      $result=mysql_query($sql);
      $quesid=mysql_insert_id();
   }
   else	//EXISTING QUESTION
   {
      $sql="UPDATE $test SET question='$question',answer='$correct',reference='$reference' WHERE id='$quesid'";
      $result=mysql_query($sql);
      $sql="DELETE FROM $mchoices WHERE questionid='$quesid'";
      $result=mysql_query($sql);
   }
   //MULTIPLE CHOICES
   for($i=0;$i<count($answer);$i++) 
   {
      if($answer[$i]!='')
      {
            $answer[$i]=addslashes($answer[$i]);
	    $sql="INSERT INTO $mchoices (questionid,choicevalue,choicelabel,orderby) VALUES ('$quesid','$letters[$i]','$answer[$i]','$i')";
            $result=mysql_query($sql);
      }
   }
   if($save=="Save Question")
      header("Location:addtest2question.php?session=$session&quesid=$quesid&sport=$sport&edited=1");
   else
      header("Location:addtest2question.php?session=$session&quesid=$quesid&sport=$sport&added=1");
}

echo $init_html;
echo GetHeader($session,"test2report");
echo "<br>";
echo "<a href=\"edittest2.php?session=$session&sport=$sport\" class=small>Return to Edit $sportname Questions</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"test2report.php?session=$session&sport=$sport\" class=small>Return to $sportname Online Test Admin</a><br><br>";
echo "<form method=post action=\"addtest2question.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=quesid value=\"$quesid\">";
echo "<table class=nine cellspacing=0 cellpadding=3><caption><b>";
if($quesid)
{
   $sql="SELECT * FROM $test WHERE id='$quesid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "Edit $sportname Part 2 Question #$row[place]:";
}
else echo "Add a New PART 2 $sportname Test Question:";
echo "</b><br>";
if($quesid)
{
   $sql2="SELECT * FROM $test WHERE id<'$quesid' ORDER BY id DESC LIMIT 1";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2); 
   $previd=$row2[id];
   $sql2="SELECT * FROM $test WHERE id>'$quesid' ORDER BY id ASC LIMIT 1";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $nextid=$row2[id];
   if($previd)
      echo "<div style=\"float:left;\"><a href=\"addtest2question.php?sport=$sport&session=$session&quesid=$previd\"><< Previous Question</a></div>";
   if($nextid)
      echo "<div style=\"float:right;\"><a href=\"addtest2question.php?sport=$sport&session=$session&quesid=$nextid\">Next Question >></a></div>";
   echo "<div style='clear:both;'></div>";
}
if($added==1)
   echo "<div class=alert>Your question has been added!</div>";
else if($edited==1)
   echo "<div class=alert>Your changes have been saved!</div>";
echo "<br></caption>";
echo "<tr valign=top align=left><td><b>The QUESTION:</b></td><td><textarea name=\"question\" rows=5 cols=50 id=\"question\">$row[question]</textarea></td></tr>";
echo "<tr valign=top align=left><td><b>Possible ANSWERS:</b></td><td><i>Delete a multiple choice option by leaving the text field BLANK.</i><br>";
if($quesid)
{
   $sql2="SELECT * FROM $mchoices WHERE questionid='$quesid' ORDER BY orderby";
   $result2=mysql_query($sql2);
   $ix=0;
   while($row2=mysql_fetch_array($result2))
   {
      echo "$letters[$ix]) <input type=text name=\"answer[$ix]\" id=\"answer".$ix."\" value=\"$row2[choicelabel]\" size=20> <input type=radio name=\"correct\" id=\"correct\" value=\"$letters[$ix]\"";
      if($row[answer]==$row2[choicevalue]) echo " checked";
      echo "> Correct Answer<br>";
      $ix++;
   }
}
else
{
   echo "$letters[0]) <input type=text name=\"answer[0]\" id=\"answer0\" value=\"True\" size=20> ";
	echo "<input type=radio name=\"correct\" id=\"correct\" value=\"$letters[0]\"> Correct Answer<br>";
   echo "$letters[1]) <input type=text name=\"answer[1]\" id=\"answer1\" value=\"False\" size=20> ";
        echo "<input type=radio name=\"correct\" id=\"correct\" value=\"$letters[1]\"> Correct Answer";
   $ix=2;
}
for($i=$ix;$i<count($letters);$i++)
{
   echo "<div style=\"padding:5px;border:#808080 1px dotted;margin:5px;color:#0000ff;cursor:hand;cursor:pointer;font-weight:bold;font-size:12px;\" onClick=\"document.getElementById('mchoice".$i."').style.display='';this.style.display='none';\">+ Add Another</div>";
   echo "<div style=\"display:none;\" id=\"mchoice".$i."\">";
   echo "$letters[$i]) <input type=text name=\"answer[$i]\" id=\"answer".$i."\" size=20> ";
   echo "<input type=radio name=\"correct\" id=\"correct\" value=\"$letters[$i]\"> Correct Answer</div>";
}
echo "</td></tr>";
echo "<tr align=left><td><b>RULE REFERENCE:</b></td><td><input type=text name=\"reference\" value=\"$row[reference]\" id=\"reference\"></td></tr>";
if($quesid) $buttontext="Save Question";
else $buttontext="Add New Question";
echo "<tr align=center><td colspan=2><input type=submit name=\"save\" value=\"$buttontext\" class=\"fancybutton\"></td></tr>";
echo "</table>";
echo "</form>";

echo $end_html;
?>
