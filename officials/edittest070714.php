<?php
//edittest.php: allow NSAA user to look at online test questions and edit the question and/or answer

if($sport=='pp' || $sport=='sp')
   header("Location:jedittest.php?session=$session&sport=$sport");

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//get full sportname
for($i=0;$i<count($activity);$i++)
{
   if($sport==$activity[$i])
      $sportname=$act_long[$i];
}
if($sport=='sp')
   $sportname="Speech & Play";

//get tables for this sport's test
$test=$sport."test";
$categ=$sport."test_categ";
$results=$sport."test_results";

//get array of categories and name of selected category
$sql="SELECT DISTINCT id,category,place FROM $categ ORDER BY place";
$result=mysql_query($sql);
$categories=array(); $catnames=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   if(!$curcat) 
      $curcat=$row[id]; 
   $categories[$ix]=$row[id];
   $catnames[$ix]=$row[category];
   if($curcat==$row[id])
   {
      $curcat_long=$row[category];
      $curcatplace=$row[place];
   }
   $ix++;
}
echo $init_html;
if($sport=='sp')
   echo GetHeaderJ($session,"sptestreport");
else
   echo GetHeader($session,"testreport");
echo "<br>";
echo "<a href=\"testreport.php?session=$session&sport=$sport\" class=small>Return to $sportname Online Test Admin</a><br><br>";
echo "<form method=post action=\"updatetest.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=curcat value=$curcat>";

echo "<table cellspacing=0 cellpadding=3><caption><b>Edit $sportname Test Questions/Answers:<hr></b></caption>";

$ques=array(); $ans=array();

echo "<tr align=left><th colspan=4 align=left>$curcat_long:</th></tr>";
echo "<tr align=left><td>&nbsp;</td><td><b>Question:</b></td>";
echo "<td><b>Answer:</b></td><td><b>Reference:</b></td></tr>";
//get test questions for this category
$sql2="SELECT question,place,answer,reference,id FROM $test WHERE category='$curcat' ORDER BY place";
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   $place=$row2[place];
   if($ix==0) $start=$place;
   echo "<tr valign=top align=left";
   if($ix%2==0) echo " bgcolor='#e0e0e0'";
   echo "><th align=left>$place.</th>";
   echo "<td><textarea class=small name=\"ques[$place]\" rows=3 cols=50>$row2[question]</textarea></td>";
   echo "<td width='350px;'>";
   //GET MULTIPLE CHOICES
   $sql3="SELECT * FROM ".$test."_mchoices WHERE questionid='$row2[id]' ORDER BY orderby";
   $result3=mysql_query($sql3);
   while($row3=mysql_fetch_array($result3))
   {
      echo "<input type=radio name=\"ans[$place]\" value=\"$row3[choicevalue]\"";
      if($row2[answer]==$row3[choicevalue]) echo " checked";
      echo ">$row3[choicelabel]<br>";
   }
   echo "<input type=radio name=\"ans[$place]\" value=\"acceptall\"";
   if($row2[answer]=='acceptall') echo " checked";
   echo ">ACCEPT ANY ANSWER</td>";
   echo "<td><textarea class=small rows=2 cols=40 name=\"ref[$place]\">$row2[reference]</textarea></td>";
   echo "</tr>";
   $ix++;
}
$end=$place;
echo "<input type=hidden name=start value=$start>";
echo "<input type=hidden name=end value=$end>";
echo "<tr align=center><td colspan=3>";
if($curcatplace<count($categories))
   echo "<input type=submit name=save value=\"Save & Go to Next Section\">&nbsp;";
//echo count($categories)." $curcatplace";
echo "<select onchange='submit();' name=jumptocat><option value=$curcat>Save & Jump To...</option>";
$testtable=$sport."test";
for($i=0;$i<count($categories);$i++)
{
   $sql="SELECT place FROM $testtable WHERE category='$categories[$i]' ORDER BY place";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $start=$row[0];
   while($row=mysql_fetch_array($result))
   {
      $end=$row[0];
   }
   echo "<option value=\"$categories[$i]\"";
   echo ">#$start";
   if(mysql_num_rows($result)>1) echo "-$end";
   echo ": $catnames[$i]</option>";
}
echo "<option value=\"Admin\">[$sportname Test Admin]</option>";
echo "<option value=\"Home\">[Home]</option>";
echo "</td></tr>";
echo "</table></form>";
echo "<a class=small href=\"testreport.php?session=$session&sport=$sport\">$sportname Online Test Admin</a>&nbsp;&nbsp;&nbsp;";
if($sport=='sp')
   echo "<a class=small href=\"jwelcome.php?session=$session\">Home</a>";
else
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;

?>
