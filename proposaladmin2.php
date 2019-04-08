<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=5)
{
   header("Location:index.php");
   exit();
}

$schgroup=GetSchool($session);

echo $init_html;
echo $header;


//LEGISLATIVE:
$sql="SELECT * FROM proposaladmin WHERE type='Legislative'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[duedate];
if(!PastDue($duedate,0))	//Allow user to submit proposals until due date
{
   echo "<form method=post action=\"proposal.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<br><table><tr align=left><th align=left>Submit a LEGISLATIVE Proposal for a School:</th>";
   echo "<td><select name=school_ch><option value=''>Choose School</option>";
   $sql="SELECT school FROM largeschools WHERE schgroup='$schgroup' ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option>$row[0]</option>";
   }
   echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\"></td></tr>";
   echo "</table>";
   echo "</form>";
}
else	//Show when proposals were due
{
   $date=split("-",$duedate);
   $duedate2="$date[1]/$date[2]/$date[0]";
   echo "<table><tr align=center><td><i>Legislative Proposals were due on <b>$duedate2</b>.</i></td></tr></table>";
}

//CLASS CAUCUS
if(CanAccessCaucusProposal($schgroup))
{
$sql="SELECT * FROM proposaladmin WHERE type='Class Caucus'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[duedate];
if(!PastDue($duedate,0))        //Allow user to submit proposals until due date
{
   echo "<form method=post action=\"proposal.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name='type' value='caucus'>";
   echo "<br><table><tr align=left><th align=left>Submit a CLASS CAUCUS Proposal for a School:</th>";
   echo "<td><select name=school_ch><option value=''>Choose School</option>";
   $sql="SELECT school FROM largeschools WHERE schgroup='$schgroup' ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option>$row[0]</option>";
   }
   echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\"></td></tr>";
   echo "</table>";
   echo "</form>";
}
else    //Show when proposals were due
{
   $date=split("-",$duedate);
   $duedate2="$date[1]/$date[2]/$date[0]";
   echo "<table><tr align=center><td><i>Class Caucus Proposals were due on <b>$duedate2</b>.</i></td></tr></table>";
}
}

echo "<br><table cellspacing=1 cellpadding=2 width=500><caption><b>Submitted Proposals for Change in NSAA Regulations:</b></caption>";
$sql="SELECT t1.* FROM proposals AS t1,largeschools AS t2 WHERE t1.school=t2.school AND t2.schgroup='$schgroup' ORDER BY t1.type,t2.school";
$result=mysql_query($sql);
$cursch=""; $ix=0; $x=0;
$ct=mysql_num_rows($result);
if($ct==0)
   echo "<tr align=center><td colspan=2>[No proposals have been submitted by any $schgroup yet.]</td></tr>";
while($row=mysql_fetch_array($result))
{
   if($cursch!=$row[school])	//new group of school's proposals
   {
      $ix++;
      $cursch=$row[school];
      echo "<tr align=left>";
      echo "<td colspan=2>";
      echo "<b>$cursch:</b></td></tr>";
   }
   $filename=$row[filename];
   $date=date("M d, Y",$row[datesub]);
   echo "<input type=hidden name=\"id[$x]\" value=\"$row[id]\">";
   echo "<tr align=center>";
   echo "<td align=left>";
   if($row[type]=='caucus') echo "Class Caucus Proposal: ";
   else echo "Legislative Proposal: ";
   echo "$filename (<a class=small target=new href=\"attachments/$filename\">View</a>)&nbsp;";
   if($row[locked]!='x')
      echo "(<a class=small target=new href=\"proposal.php?session=$session&givenid=$row[id]&school_ch=$row[school]\">Edit</a>)";
   echo "</td>";
   echo "<td align=left width=80>$date</td>";
   echo "</tr>";
   $x++;
}
echo "</td></tr>";
echo "</table>";
echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
echo $end_html;
?>
