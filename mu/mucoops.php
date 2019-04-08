<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

$schools=array();
$ix=0;
$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[school]; $ix++;
}

echo $init_html;
echo $header;

if($addcoop==1)
{
   echo "<br>";
   echo "<a class=small href=\"mucoops.php?session=$session\">Back to Music Co-ops</a><br><br>";
   echo "<form method=post action=\"mucoops.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<br><table><caption><b>Add a Music Co-op:</b></caption>";
   echo "<tr align=left><td>Head School:</td>";
   echo "<td><select name=\"mainsch\"><option value=''>Select School</option>";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>Other School #1:</td>";
   echo "<td><select name=\"othersch1\"><option value=''>Select School</option>";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>Other School #2:</td>";
   echo "<td><select name=\"othersch2\"><option value=''>Select School</option>";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>Vocal:</td>";
   echo "<td><input type=checkbox name=\"vocal\" value='x'></td></tr>";
   echo "<tr align=left><td>Instrumental:</td>";
   echo "<td><input type=checkbox name=\"instrumental\" value='x'></td></tr>";
   echo "<tr align=center><td colspan=2><br><input type=submit name=\"addnew\" value=\"Add Co-Op\"></td></tr>";
   echo "</table></form>";
   echo $end_html;
   exit();
}
else if($addnew && $mainsch!='' && $othersch1!='')
{
   $mainsch=addslashes($mainsch); $othersch1=addslashes($othersch1); $othersch2=addslashes($othersch2);
   $sql="INSERT INTO mucoops (mainsch,othersch1,othersch2,vocal,instrumental) VALUES ('$mainsch','$othersch1','$othersch2','$vocal','$instrumental')";
   $result=mysql_query($sql); 
}
else if($editid && !$editcoop)
{
   echo "<br>";
   echo "<a class=small href=\"mucoops.php?session=$session\">Back to Music Co-ops</a><br><br>";
   echo "<form method=post action=\"mucoops.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=editid value=\"$editid\">";
   $sql="SELECT * FROM mucoops WHERE id='$editid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<br><table><caption><b>Edit Music Co-op:</b></caption>";
   echo "<tr align=left><td>Head School:</td>";
   echo "<td><select name=\"mainsch\"><option value=''>Select School</option>";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option";
      if($row[mainsch]==$schools[$i]) echo " selected";
      echo ">$schools[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>Other School #1:</td>";
   echo "<td><select name=\"othersch1\"><option value=''>Select School</option>";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option";
      if($row[othersch1]==$schools[$i]) echo " selected";
      echo ">$schools[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>Other School #2:</td>";
   echo "<td><select name=\"othersch2\"><option value=''>Select School</option>";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option";
      if($row[othersch2]==$schools[$i]) echo " selected";
      echo ">$schools[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>Vocal:</td>";
   echo "<td><input type=checkbox name=\"vocal\" value='x'";
   if($row[vocal]=='x') echo " checked";
   echo "></td></tr>";
   echo "<tr align=left><td>Instrumental:</td>";
   echo "<td><input type=checkbox name=\"instrumental\" value='x'";
   if($row[instrumental]=='x') echo " checked";
   echo "></td></tr>";
   echo "<tr align=center><td colspan=2><br><input type=submit name=\"editcoop\" value=\"Save Changes\"></td></tr>";
   echo "</table></form>";
   echo $end_html;
   exit();
}
else if($editcoop && $editid && $mainsch!='' && $othersch1!='')
{
   $mainsch=addslashes($mainsch); $othersch1=addslashes($othersch1); $othersch2=addslashes($othersch2);
   $sql="UPDATE mucoops SET mainsch='$mainsch',othersch1='$othersch1',othersch2='$othersch2',vocal='$vocal',instrumental='$instrumental' WHERE id='$editid'";
   $result=mysql_query($sql);
}
else if($deleteid)
{
   $sql="DELETE FROM mucoops WHERE id='$deleteid'";
   $result=mysql_query($sql);
}
else if($save)
{
   for($i=0;$i<count($id);$i++)
   {
      $coordinator[$i]=addslashes($coordinator[$i]);
      $school[$i]=addslashes($school[$i]);
      
      $sql="UPDATE mubigdistricts SET coordinator='$coordinator[$i]',email='$email[$i]',school='$school[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
      //echo "$sql<br>".mysql_error()."<br>";
   }
}

echo "<br>";
echo "<a class=small href=\"muadmin.php?session=$session\">Main Music Menu</a><br><br>";
echo "<form method=post action=\"mucoops.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table frame=box rules=cols cellspacing=0 cellpadding=5";
echo "><caption><b>Music Co-ops:</b></caption>";
if($deleteid)
   echo "<tr align=left><td colspan=6><div class=alert>The music co-op has been deleted.</div></td></tr>";
$sql="SELECT * FROM mucoops ORDER BY mainsch";
$result=mysql_query($sql); $ix=0;
if(mysql_num_rows($result)>0)
{
if($addnew)
   echo "<tr align=left><td colspan=6><div class=alert>The new music co-op has been added below.</div></td></tr>";
echo "<tr align=left><td colspan=6><a class=small href=\"mucoops.php?session=$session&addcoop=1\">Add a Music Co-op</a><br><br></td></tr>";
echo "<tr align=left bgcolor=#F0F0F0><td><b>HEAD SCHOOL</b></td><td><b>OTHER SCHOOL #1</b></td><td><b>OTHER SCHOOL #2</b></td>";
echo "<td><b>Vocal</b></td><td><b>Instrumental</b></td><td><b>MANAGE</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"id[$ix]\" value=\"$row[id]\">";
   echo "<tr align=left><td>$row[mainsch]</td>";
   echo "<td>$row[othersch1]</td><td>$row[othersch2]</td>";
   echo "<td align=center>".strtoupper($row[vocal])."</td><td align=center>".strtoupper($row[instrumental])."</td>";
   echo "<td><a class=small href=\"mucoops.php?session=$session&editid=$row[id]\">Edit</a>&nbsp;|&nbsp;";
   echo "<a class=small href=\"mucoops.php?session=$session&deleteid=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this co-op?  This will NOT delete any of the schools involved; only their connection.');\">Delete</a></td>";
   echo "</tr>";
   $ix++;
}
}//end if co-ops exist
else
{
   echo "<tr align=left><td>There are currently no Music co-ops in the database<br><br>";
   echo "<a class=small href=\"mucoops.php?session=$session&addcoop=1\">Add a Music Co-op</a></td></tr>";
}
echo "</table></form>";
echo $end_html;
?>
