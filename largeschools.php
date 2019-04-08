<?php
/****************************
largeschools.php
Manage Level 5 Users
Created 7/25/13
by Ann Gaffigan
******************************/

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

if($delete>0)
{
   $sql="DELETE FROM logins WHERE level='5' AND id='$delete'";
   $result=mysql_query($sql);
}
if($save || $addnew || $delete)
{
   for($i=0;$i<count($id);$i++)
   {
      $school[$i]=addslashes($school[$i]);
      $name[$i]=addslashes($name[$i]);
      $address1[$i]=addslashes($address1[$i]);
      $address2[$i]=addslashes($address2[$i]);
      $city_state[$i]=addslashes($city_state[$i]);
      $zip[$i]=addslashes($zip[$i]);
      $passcode[$i]=addslashes($passcode[$i]);
      if($id[$i]==0 && trim($school[$i])!="")	//INSERT
      {
         $sql="INSERT INTO logins (school,name,email,address1,address2,city_state,zip,passcode,level) VALUES ('$school[$i]','$name[$i]','$email[$i]','$address1[$i]','$address2[$i]','$city_state[$i]','$zip[$i]','$passcode[$i]','5')";
      }
      else		//UPDATE
      {
	 $sql="UPDATE logins SET school='$school[$i]', name='$name[$i]',email='$email[$i]', address1='$address1[$i]', address2='$address2[$i]', city_state='$city_state[$i]', zip='$zip[$i]', passcode='$passcode[$i]' WHERE id='$id[$i]'";
      }
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;
echo "<br>";
echo "<form method=post action=\"largeschools.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=2><caption><b>Manage Large School Groups User Information</b></caption>";
//ADD NEW USER:
echo "<tr align=left><td colspan=5><b>ADD NEW LARGE SCHOOL GROUP USER:</b></td></tr>";
echo "<tr valign=top align=center><td><b>Large School Group</b></td><td><b>Name</b></td><td><b>E-mail</b></td><td><b>Address</b></td>";
echo "<td><b>City, State<br>Zip</b></td><td><b>Passcode</b></td><td>&nbsp;</td></tr>";
echo "<tr align=left valign=top><a name=\"$row[id]\">";
$ix1=0;
echo "<input type=hidden name=\"id[$ix1]\" value='0'>";
echo "<td><input type=text size=30 name=\"school[$ix1]\"></td>";
$sql="SELECT DISTINCT school FROM logins WHERE level='5' ORDER BY school";
$result=mysql_query($sql);
$groups=array(); $g=0;
while($row=mysql_fetch_array($result))
{
   $groups[$g]=$row[school]; $g++;
}
echo "<td><input type=text size=20 class=tiny name=\"name[$ix1]\"></td>";
echo "<td><input type=text size=25 class=tiny name=\"email[$ix1]\"></td>";
echo "<td><input type=text size=20 class=tiny name=\"address1[$ix1]\"><br>";
echo "<input type=text size=20 class=tiny name=\"address2[$ix1]\"></td>";
echo "<td><input type=text size=17 class=tiny name=\"city_state[$ix1]\"><br>";
echo "<input type=text size=10 class=tiny name=\"zip[$ix1]\"></td>";
echo "<td><input type=text size=12 class=tiny name=\"passcode[$ix1]\"></td></tr>";
echo "<tr align=left><td colspan=6><input type=submit name=addnew value=\"Add New User\"><br><br></td>";
echo "</a></tr>";
$ix1++;

//VIEW/EDIT CURRENT USERS:
echo "<tr align=left><td colspan=6><b>CURRENT LARGE SCHOOL GROUP USERS:</b></td></tr>";
echo "<tr valign=top align=center><td><b>Large School Group</b></td><td><b>Name</b></td><td><b>E-mail</b></td><td><b>Address</b></td>";
echo "<td><b>City, State<br>Zip</b></td><td><b>Passcode</b></td><td><b>Delete</b></td></tr>";
$sql="SELECT * FROM logins WHERE level='5' ORDER BY school, name";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($ix1%2!=0) $bgcolor="#e0e0e0";
   else $bgcolor="#ffffff";
   echo "<tr align=left bgcolor=\"$bgcolor\" valign=top><td><a name=\"$row[id]\">";
   echo "<input type=hidden name=\"id[$ix1]\" value=\"$row[id]\">";
   echo "<select name=\"school[$ix1]\"><option value=\"\">Select Large School Group</option>";
   for($g=0;$g<count($groups);$g++)
   {
      echo "<option value=\"$groups[$g]\"";
      if($row[school]==$groups[$g]) echo " selected";
      echo ">$groups[$g]</option>";
   }
   echo "</select></td>";
   echo "<td><input type=text size=20 class=tiny value=\"$row[name]\" name=\"name[$ix1]\"></td>";
   echo "<td><input type=text size=25 class=tiny value=\"$row[email]\" name=\"email[$ix1]\"></td>";
   echo "<td><input type=text size=20 class=tiny value=\"$row[address1]\" name=\"address1[$ix1]\"><br>";
   echo "<input type=text size=20 class=tiny value=\"$row[address2]\" name=\"address2[$ix1]\"></td>";
   echo "<td><input type=text size=17 class=tiny value=\"$row[city_state]\" name=\"city_state[$ix1]\"><br>";
   echo "<input type=text size=10 class=tiny value=\"$row[zip]\" name=\"zip[$ix1]\"></td>";
   echo "<td><input type=text size=12 class=tiny value=\"$row[passcode]\" name=\"passcode[$ix1]\"></td>";
   echo "<td align=center><a class=small href=\"largeschools.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete $row[school]??');\">X</a></td>";
   echo "</a></tr>";
   $ix1++;
}
echo "<tr align=left><td colspan=6><input type=submit name=save value=\"Save Large School Group Users\"></td></tr>";

echo "</table>";
echo "</form>";

echo $end_html;
?>
