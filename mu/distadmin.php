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

echo $init_html;
echo $header;

if($save)
{
   for($i=0;$i<count($id);$i++)
   {
      $coordinator[$i]=addslashes($coordinator[$i]);
      $school[$i]=addslashes($school[$i]);
      
      $sql="UPDATE mubigdistricts SET schoolid1='$schoolid1[$i]',schoolid2='$schoolid2[$i]',loginid1='$loginid1[$i]',loginid2='$loginid2[$i]',certificates='$certificates[$i]',coordinator='$coordinator[$i]',email='$email[$i]',school='$school[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
      //echo "$sql<br>".mysql_error()."<br>";
   }
}

echo "<br>";
echo "<a class=small href=\"muadmin.php?session=$session\">Main Music Menu</a><br><br>";
echo "<form method=post action=\"distadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=650";
echo "><caption><b>Music District Coordinators:</b></caption>";

$sql="SELECT * FROM mubigdistricts ORDER BY distnum";
$result=mysql_query($sql); $ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"id[$ix]\" value=\"$row[id]\">";
   echo "<tr align=left valign=top><th align=left colspan=2><b>District $row[distnum]:</b></td></tr>";
   echo "<tr align=left><td><b>Coordinator's Name:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"coordinator[$ix]\" value=\"$row[coordinator]\"></td></tr>";
   echo "<tr align=left><td><b>Coordinator's School:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"school[$ix]\" value=\"$row[school]\"></td></tr>";
   echo "<tr align=left><td><b>Coordinator's E-mail:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"email[$ix]\" value=\"$row[email]\"></td></tr>";
   echo "<tr align=left><td colspan=2><input type=checkbox name=\"certificates[$ix]\" value=\"x\"";
   if($row[certificates]=='x') echo " checked";
   echo "> Grant access to PDF Music Certificate Generation Form for ALL DISTRICT $row[distnum] SITES</td></tr>";
   echo "<tr align=left><td colspan=2><b>Who can access this entry form, the certificate generation and the financial report through their NSAA login?</b></td></tr>";
   echo "<tr align=left valign=top><td><b>Select School(s):</b></td><td><select name=\"schoolid1[$ix]\"><option value='0'>Select School</option>";
      $sql2="SELECT * FROM headers ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row2[id]==$row[schoolid1]) echo " selected";
         echo ">$row2[school]</option>";
      }
   echo "</select><br><select name=\"schoolid2[$ix]\"><option value='0'>Select School</option>";
      $sql2="SELECT * FROM headers ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row2[id]==$row[schoolid2]) echo " selected";
         echo ">$row2[school]</option>";
      }
   echo "</select></td></tr>";
   echo "<tr align=left><td colspan=2><b>NOTE:</b> Selecting a <b><u>SCHOOL</b></u> will allow the <b><u>AD</b></u> as well as the <b><u>Vocal, Instrumental and/or Orchestra Directors</b></u> to access the District Information. To only grant <b>SPECIFIC PEOPLE</b> access to a district's information, use the dropdown boxes below.</td></tr>";
   echo "<tr valign=top align=left><td><b>Select Person(People):</b></td><td><select name=\"loginid1[$ix]\"><option value='0'>Select Specific Person</option>";
      $sql2="SELECT * FROM logins WHERE level=2 OR (level=3 AND name!='' AND (sport LIKE '%Music%' OR sport='Orchestra')) OR level=4 ORDER BY school,name";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row2[id]==$row[loginid1]) echo " selected";
	 if($row2[sport]!='') $name="$row2[name] ($row2[sport])";
         else if($row2[name]!='') { $name=$row2[name]; if($row2[level]==2) $name.=" (AD)"; }
         else if($row2[sport]!='') $name=$row2[sport];
         echo ">$row2[school]: $name</option>";
      }
   echo "</select><br><select name=\"loginid2[$ix]\"><option value='0'>Select Specific Person</option>";
      $sql2="SELECT * FROM logins WHERE level=2 OR (level=3 AND name!='' AND (sport LIKE '%Music%' OR sport='Orchestra')) OR level=4 ORDER BY school,name";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row2[id]==$row[loginid2]) echo " selected";
         if($row2[sport]!='') $name="$row2[name] ($row2[sport])";
         else if($row2[name]!='') { $name=$row2[name]; if($row2[level]==2) $name.=" (AD)"; }
         else if($row2[sport]!='') $name=$row2[sport];
         echo ">$row2[school]: $name</option>";
      }
   echo "</select></td></tr>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   $ix++;
}
echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save District Info\"></td></tr>";
echo "</table></form>";
echo $end_html;
?>
