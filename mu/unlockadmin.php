<?php
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($save1 || $save2)
{
   for($i=0;$i<count($id);$i++)
   {
      if($id[$i]==0 && $unlocked[$i]=='x')
      {
	 $sql="SELECT * FROM headers WHERE id='$headerid[$i]'";
    	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $sch=addslashes($row[school]);
	 $sql="INSERT INTO muschools (school,unlocked) VALUES ('$sch','x')";
	 $result=mysql_query($sql);
      }
      else if($id[$i]>0)
      {
	 $sql="UPDATE muschools SET unlocked='$unlocked[$i]' WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
   }
} 

echo $init_html;
echo $header;

echo "<br>";
echo "<a class=small href=\"muadmin.php?session=$session\">Return to Music Entry Form Admin</a>";
if($save1 || $save2)
   echo "<br><div class=alert style='width:400px;text-align:center;'><i>Your changes have been saved.</i></div>";
echo "<form method=post action=\"unlockadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><table cellspacing=2 cellpadding=8><caption><b>Unlock/Re-Lock Music Entry Forms after the Due Date:</b></caption><tr align=center valign=top><td width='50%'>";
echo "<font style=\"font-size:9pt;\">Music Forms that have NOT been submitted:</font><br>";
$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#333333 1px solid;\">";
echo "<tr align=center><td><b>School</b></td><td><b>Unlocked</b></td></tr>";
$i=0;
while($row=mysql_fetch_array($result))
{
   $sch=addslashes($row[school]);
   $sql2="SELECT * FROM muschools WHERE school='$sch' AND submitted>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="SELECT * FROM muschools WHERE school='$sch'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)==0) { $curunlocked=''; $curid=0; }
      else { $curunlocked=$row2[unlocked]; $curid=$row2[id]; }
      if($curid>0) echo "<input type=hidden name=\"id[$i]\" value=\"$row2[id]\">";
      else echo "<input type=hidden name=\"id[$i]\" value=\"0\"><input type=hidden name=\"headersid[$i]\" value=\"$row[id]\">";
      echo "<tr align=left><td>$row[school]</td><td align=center><input type=checkbox name=\"unlocked[$i]\" value='x'";
      if($curunlocked=='x') echo " checked";
      echo "></td></tr>";
      $i++;
   }
}
echo "</table><br><input type=submit name=\"save1\" value=\"Save\"></td><td width='50%'>";
echo "<font style=\"font-size:9pt;\">Music Forms that HAVE been submitted:</font><br>";
$sql="SELECT * FROM muschools WHERE submitted>0 ORDER BY school";
$result=mysql_query($sql);
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#333333 1px solid;\">";
echo "<tr align=center><td><b>School</b></td><td><b>Unlocked</b></td></tr>";
while($row=mysql_fetch_array($result))
{
      echo "<input type=hidden name=\"id[$i]\" value=\"$row[id]\">";
      echo "<tr align=left><td>$row[school]</td><td align=center><input type=checkbox name=\"unlocked[$i]\" value='x'";
      if($row[unlocked]=='x') echo " checked";
      echo "></td></tr>";
      $i++;
}
echo "</table><br><input type=submit name=\"save1\" value=\"Save\">";
echo "</td></tr></table></form>";

echo $end_html;
?>
