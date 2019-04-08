<?php

require '../functions.php';
require '../variables.php';
require 'swfunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo $header;

if($level==1 && $delete==1)
{
   echo "<br><br>";
   echo "Are you sure you want to delete ";
   $sql="SELECT meet FROM sw_verify_b WHERE id='$formid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<b>$row[0]</b> ?";
   echo "<br><br><a href=\"view_sw_b.php?session=$session&school_ch=$school_ch&formid=$formid&delconfirm=1\" class=small>Yes</a>&nbsp;&nbsp;";
   echo "<a href=\"view_sw_b.php?session=$session&school_ch=$school_ch\" class=small>No</a>";
   echo $end_html;
   exit();
}
if($level==1 && $delconfirm==1)
{
   $sql="DELETE FROM sw_verify_b WHERE id='$formid'";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_verify_perf_b WHERE formid='$formid'";
   $result=mysql_query($sql);
   echo "<br><b>Form #$formid has been deleted.<br>";
}

if($submit && $level==1)
{
   for($i=0;$i<count($formid);$i++)
   {
      $sql="UPDATE sw_verify_b SET approved='$approved[$i]' WHERE id='$formid[$i]'";
      $result=mysql_query($sql);
   }
}

echo "<br>";
if($level==1)
{
   echo "<form method=post action=\"view_sw_b.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=school_ch value=\"$school_ch\">";
}
if($level!=3)
{
   if($level==1) $sportname="Swimming";
   else $sportname="Boys Swimming";
   echo "<a href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=$sportmame\" class=small>Return to Home-->Swimming</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_sw_g.php?session=$session&school_ch=$school_ch\" class=small>$school Girls Verification Forms</a><br><br>";
}
echo "<table><caption><b>$school Boys Swimming Verification Forms:</b><hr></caption>";
echo "<tr align=left><td align=left>";
$duedate=GetDueDate("sw");
$date=explode("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if(!PastDue($duedate,0))
{
   echo "<a href=\"edit_sw_verify_b.php?session=$session&school_ch=$school_ch\" class=small>Click Here to Start a New Verification Form</a><br><br>";
}
else
{
   echo "<p><i>Verification forms were due $duedate2.</i></p>";
}
echo "<b>The following Boys Verification Forms have been saved but NOT SUBMITTED to the NSAA:<br></b><ul>";
$sql="SELECT id,datesub,meetid FROM sw_verify_b WHERE school='$school2' AND submitted!='y' ORDER BY datesub";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<li>";
   $meetname=GetMeetName($row[meetid]);
   echo "<a href=\"edit_sw_verify_b.php?session=$session&formid=$row[id]&school_ch=$school_ch\" class=small>$meetname</a>&nbsp; (saved on ".date("M d, Y",$row[datesub]).")";
   if($level==1)
      echo "&nbsp;<a class=small href=\"view_sw_b.php?session=$session&school_ch=$school_ch&formid=$row[id]&delete=1\" onclick=\"return confirm('Are you sure you want to delete this form? You will not be able to undo this action.');\">Delete</a>";
   echo "<br>";
}
if(mysql_num_rows($result)==0) echo "(NONE)";
echo "</ul>";
echo "<b>The following Boys Verification Forms have been SUBMITTED to the NSAA:<br></b><ul>";
$sql="SELECT id,datesub,approved,meetid FROM sw_verify_b WHERE school='$school2' AND submitted='y' ORDER BY datesub"; 
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{   
   if($level==1)
   {
      echo "<input type=checkbox name=\"approved[$ix]\" value='y'";
      if($row["approved"]=='y') echo " checked";
      echo ">&nbsp;";
      echo "<input type=hidden name=\"formid[$ix]\" value=\"$row[id]\">";
      $ix++;
   }
   else
      echo "<li>";
   $meetname=GetMeetName($row[meetid]);
   echo "<a href=\"view_sw_verify_b.php?session=$session&formid=$row[id]&school_ch=$school_ch\" class=small>$meetname</a>&nbsp; (saved on ".date("M d, Y",$row[datesub]).")";
   if($level==1)
   {
      echo "&nbsp;<a class=small href=\"view_sw_b.php?session=$session&school_ch=$school_ch&formid=$row[id]&delete=1\">Delete</a>";
   }
   echo "<br>";
}
if(mysql_num_rows($result)==0) echo "(NONE)";
echo "</ul>";
echo "</td></tr>";
echo "</table>";
if($level==1)
{
   echo "<br><input type=submit name=submit value=\"Approve Checked Forms\">";
   echo "</form>";
}

echo $end_html;
?>
