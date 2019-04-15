<?php
if($judges=='y' && $sport=='pp') $sport='pp';
else if($judges=='y') $sport='sp';

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   if($sport!='pp' && $sport!='sp')
      header("Location:index.php?error=1");
   else
      header("Location:jindex.php?error=1");
   exit();
}

$table=$sport."_zones";

if($submits=="Delete Checked")
{
   for($i=0;$i<count($delzone);$i++)
   {
      if($delete[$i]=='y')
      {
	 $delzone2[$i]=ereg_replace("\'","\'",$delzone[$i]);
	 $sql="DELETE FROM $table WHERE zone='$delzone2[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

if($submits=="Copy" && $copy!="~")
{
   $thistable=$sport."_zones";
   $thattable=$copy."_zones";
   $sql="DELETE FROM $thistable";
   $result=mysql_query($sql);
   //echo "$sql<br>";
   $sql="SELECT * FROM $thattable";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $newzone=addslashes($row[zone]);
      $newcities=addslashes($row[cities]);
      $sql2="INSERT INTO $thistable (zone,cities) VALUES ('$newzone','$newcities')";
      $result2=mysql_query($sql2);
      //echo "$sql2<br>";
   }
}

if($submits=="Create Zone" || $submits=="Save Changes")
{
   $citylist="";
   for($i=0;$i<count($cityname);$i++) 
   {
      if($cities[$i]=='x')
	 $citylist.=trim($cityname[$i]).", ";
   }
   $citylist=substr($citylist,0,strlen($citylist)-2);

   $citylist2=ereg_replace("\'","\'",$citylist);
   $newzone2=ereg_replace("\'","\'",$newzone);

   $sql="SELECT id FROM $table WHERE zone='$newzone'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0 && !$zone)
   {
      echo $init_html;
      if($sport!='pp' && $sport!='sp')
         echo GetHeader($session,"apptooff");
      else
	 echo GetHeaderJ($session,"apptojudge");
      echo "<br><br>";
      echo "<font style=\"color:red\"><b>The name \"$newzone\" is already in use for another zone.  Please <a class=small href=\"javascript:history.go(-1)\">Go Back</a> and choose a different name.</b></font>";
      echo $end_html;
      exit();
   }

   if($zone)
   {
      $zone2=ereg_replace("\'","\'",$zone);
      $sql="UPDATE $table SET zone='$newzone2',cities='$citylist2' WHERE zone='$zone2'";
      unset($zone);
   }
   else
      $sql="INSERT INTO $table (zone,cities) VALUES ('$newzone2','$citylist2')";
   $result=mysql_query($sql);
}

echo $init_html;
if($sport!='pp' && $sport!='sp')
   echo GetHeader($session,"apptooff");
else
   echo GetHeaderJ($session,"apptojudge");
echo "<form method=post action=\"apptooffzones.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=judges value=$judges>";
echo "<input type=hidden name=sport value=$sport>";
//check that current zone is actually a zone for the selected sport
$zone2=ereg_replace("\'","\'",$zone);
$sql="SELECT id FROM $table WHERE zone='$zone2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   unset($zone);
}
else if($zone)
{
   echo "<input type=hidden name=zone value=\"$zone\">";
}
$table2=$sport."off";
if($sport!='pp' && $sport!='sp')
{
   $sql="SELECT t1.city, count(t1.city) FROM officials";
   $sql.=" AS t1, $table2 AS t2 WHERE t1.id=t2.offid AND t1.city!='' GROUP BY t1.city ORDER BY t1.city";
}
else
{
   $sql="SELECT city,count(city) FROM judges WHERE city!='' GROUP BY city ORDER BY city";
}
$result=mysql_query($sql);
for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
      $sportname=$act_long[$i];
}
if($sport=='pp') $sportname="Play Production";
else if($sport=='sp') $sportname="Speech";
if($submits=="Create Zone")
{
   echo "<font style=\"color:red\"><b>Your new zone \"$newzone\" has been created!</b><br><br></font>";
}
else if($submits=="Save Changes")
{
   echo "<font style=\"color:red\"><b>Your changes to $newzone have been saved.</b><br><br></font>";
}
if($judges=='y')
   echo "<br><a class=small href=\"apptojudge.php?session=$session\">Applications to Judge Admin</a><br><br>";
else
   echo "<a href=\"apptooff.php?session=$session&sport=$sport\" class=small>Applications to Officiate Admin</a><br><br>";
echo "<table cellspacing=4 cellpadding=4><caption><b>Manage ";
if($sport=='pp')
   echo "Play Production";
else if($sport=='sp')
   echo "Speech";
else
{
   echo "<select name=sport onchange='submit();'>";
   for($i=0;$i<count($activity);$i++)
   {
      echo "<option value='$activity[$i]'";
      if($sport==$activity[$i]) echo " selected";
      echo ">$act_long[$i]";
   }
   echo "</select>";
}
echo "&nbsp;Zones:</b><hr></caption>";
echo "<tr align=center><td><b>Copy zones from this sport: ";
echo "<select name=copy><option>~</option>";
if($sport!='sp')
   echo "<option value='sp'>Speech</option>";
if($sport!='pp')
   echo "<option value='pp'>Play Production</option>";
for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]!=$sport)
      echo "<option value='$activity[$i]'>$act_long[$i]";
}
echo "</select><input type=submit name=submits value=\"Copy\"><br></b>";
echo "(This will remove all of your current zones and replace them with ones from the sport you select)</td></tr>";
echo "<tr align=center valign=top><td><b>";
if($zone)
{
   echo "You are now editing the \"<i>$zone</i>\" zone:";
   echo "<br><a href=\"apptooffzones.php?session=$session&judges=$judges&sport=$sport\" class=small>Create a New Zone</a><br>";
}
else
{
   echo "Check the cities you want to include in your NEW ZONE:<br>";
}
echo "<br></b><table>";
$ix=0;
if($zone)
{
   $zone2=ereg_replace("\'","\'",$zone);
   $sql2="SELECT cities FROM $table WHERE zone='$zone2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $oldcities=split(",",$row2[0]);
}
while($row=mysql_fetch_array($result))
{
   //check to see if this city has already been used
   $city2=addslashes(trim($row[0]));
   $sql2="SELECT id FROM $table WHERE (cities LIKE '$city2,%' OR cities LIKE '%, $city2' OR cities LIKE '%, $city2,%')";
   if($zone)
   {
      $zone=ereg_replace("\'","\'",$zone);
      $sql2.=" AND zone!='$zone2'";
   }
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)	//not in any zone yet
   {
   if($ix%3==0) 
      echo "<tr align=left>";
   echo "<td>";
   echo "<input type=hidden name=\"cityname[$ix]\" value=\"".trim($row[0])."\">";
   echo "<input type=checkbox name=\"cities[$ix]\" value=\"x\"";
   if($zone)
   {
      for($i=0;$i<count($oldcities);$i++)
      {
	 if(trim($row[0])==trim($oldcities[$i]))
	 {
	    echo " checked"; $i=count($oldcities);
	 }
      }
   }
   echo ">$row[0] ($row[1])";
   echo "</td>";
   if(($ix+1)%3==0)
      echo "</tr>";
   $ix++;
   }
}
echo "</table><br><input type=text name=newzone size=30 ";
if($zone) 
   echo "value=\"$zone\"";
else
   echo "value=\"Name of New Zone\"";
echo "onclick=\"this.value=''\"><br>";
if($zone)
   echo "<b><input type=submit name=submits value=\"Save Changes\"></td>";
else
   echo "<br><input type=submit name=submits value=\"Create Zone\"></td>";
echo "<td width=40%><table><tr align=center><td><a class=small href=\"#\" onClick=\"window.open('mnebraska.gif','NEMap','width=500,height=300,menubar=no,resizable=yes,scrollbars=yes');\"><img src=\"mnebraska.gif\" width=100><br>(Click to enlarge)</a></td></tr></table>";
echo "<br><table><caption align=left><b>Current Zones:</b><br><font size=1>(Click to edit)</font></caption>";
$sql="SELECT zone,cities FROM $table ORDER BY zone";
$result=mysql_query($sql);
$delete=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<input type=hidden name=\"delzone[$ix]\" value=\"$row[0]\">";
   echo "<tr align=left><td><b><input type=checkbox name=\"delete[$ix]\" value='y'>&nbsp;<a href=\"apptooffzones.php?session=$session&judges=$judges&sport=$sport&zone=$row[0]\" class=small>\"$row[0]\"</a>:</b><br>$row[1]</td></tr>";
   $ix++;
}
if(mysql_num_rows($result)>0)
   echo "<tr align=center><td><input type=submit name=submits value=\"Delete Checked\"></td></tr>";
else
   echo "<tr align=center><td><br>[none]</td></tr>";
echo "</table>";
echo "</td></tr>";

echo "</tr></table>";
echo "</form>";
echo $end_html;
?>
