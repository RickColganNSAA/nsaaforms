<?php
//edit_obs.php: displays specifics of observer's
//	record.  Changes can be made here as well.

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}   

if(!$obsid) $obsid=$id;

//connect to database:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//get level of user
$level=GetLevel($session);
?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php

if($delete)
{
   if($delete>0)
   {
      $sql="DELETE FROM observers WHERE id='$delete'";
      $result=mysql_query($sql);
      $sql="DELETE FROM logins WHERE level='3' AND obsid='$delete'";
      $result=mysql_query($sql); 
   }
   header("Location:observers.php?session=$session&sport=$sport&query=$query&last=$last");
   exit();
}

if($submit=="Save Changes")
{
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $first=ereg_replace("\"","\'",$first);
   $address=ereg_replace("\'","\'",$address);
   $address=ereg_replace("\"","\'",$address);
   $city=ereg_replace("\'","\'",$city);
   $city=ereg_replace("\"","\'",$city);
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;
   $fax=$faxarea.$faxpre.$faxpost;

   $sql="UPDATE observers SET last='$lastname',first='$first',address='$address',city='$city',state='$state',zip='$zip',homeph='$homeph',workph='$workph',cellph='$cellph',fax='$fax',email='$email'";
   for($i=0;$i<count($activity);$i++)
   {
      $sql.=",$activity[$i]='$actch[$i]'";
   }
   $sql.=" WHERE id='$obsid'";
   $result=mysql_query($sql);

   //if no passcode but there is something in payment field, get new passcode
   if(trim($passcode)=="")
   {
      $lastname2=ereg_replace("\'","",$lastname);
      $lastname2=ereg_replace(" ","",$lastname2);
      $pass=substr($lastname2,0,6);
      $num=rand(1000,9999);
      $passcode=$pass.$num;
      $sql="SELECT * FROM logins WHERE passcode='$passcode'";
      $result=mysql_query($sql);
      while(mysql_num_rows($result)>0)
      {
	 $num++;
	 $passcode=$pass.$num;
	 $sql="SELECT * FROM logins WHERE passcode='$passcode'";
	 $result=mysql_query($sql);
      }
   }

   $sql2="SELECT * FROM logins WHERE obsid='$obsid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $name="$first $lastname";
      $name=ereg_replace("\'","\'",$name);
      $sql="INSERT INTO logins (name,level,passcode,obsid) VALUES ('$name','3','$passcode','$obsid')";
   }
   else
   {
      $sql="UPDATE logins SET passcode='$passcode' WHERE obsid='$obsid'";
   }
   $result=mysql_query($sql);
}

echo $init_html;
$header2=GetHeader($session);
if($header!="no") echo $header2;

//get observer's info from db
$sql="SELECT * FROM observers WHERE id='$obsid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo "<form method=post action=\"edit_obs.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=query value=\"$query\">";
echo "<input type=hidden name=last value=$last>";
echo "<input type=hidden name=header value=$header>";
if($header!="no")
{
   echo "<a href=\"observers.php?session=$session&sport=$sport&last=$last&query=$query\" class=small>Return to Observers List</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"obs_query.php?session=$session&sport=$sport&last=$last&query=$query\" class=small>Return to Advanced Search</a><br><br>";
}
else
{
   echo "<a href=\"#\" onClick=\"window.close()\">Close this Window</a><br><br>";
}
echo "<table>";
echo "<tr align=center>";
echo "<th colspan=2>Observer #$obsid:<br>";
echo "<a class=small href=\"edit_obs.php?session=$session&id=$obsid$sport=$sport&query=$query&last=$last&delete=$obsid\" onclick=\"return confirm('Are you sure you want to delete Observer #$obsid?');\">[Delete this Observer]</a><hr>";
echo "</th></tr>";
//get observer's passcode
$sql2="SELECT passcode FROM logins WHERE obsid='$obsid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$passcode=$row2[0];
echo "<tr align=left><th class=smaller align=left>Passcode:</th>";
echo "<td><input type=text name=passcode value=\"$passcode\" size=15></td></tr>";
echo "<tr align=left><th class=smaller align=left>Name: (last, first, M)</th>";
echo "<td><input type=text name=lastname value=\"$row[last]\" size=15>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=first value=\"$row[first]\" size=10>&nbsp;";
echo "<input type=text name=middle value=\"$row[middle]\" size=2>";
echo "</td></tr>";
echo "<tr align=left><th class=smaller align=left>Address:</th>";
echo "<td align=left><input type=text name=address value=\"$row[address]\" size=30></td></tr>";
echo "<tr align=left><th class=smaller align=left>City, State Zip:</th>";
echo "<td align=left><input type=text name=city value=\"$row[city]\" size=20>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=state value=\"$row[state]\" size=2>&nbsp;&nbsp;";
echo "<input type=text name=zip value=\"$row[zip]\" size=10></td></tr>";
echo "<tr align=left><th class=smaller align=left>Home Phone:</th>";
$homearea=substr($row[homeph],0,3);
$homepre=substr($row[homeph],3,3);
$homepost=substr($row[homeph],6,4);
echo "<td align=left>(<input onfocus='select();' type=text maxlength=3 size=4 name=homearea value='$homearea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=homepre value='$homepre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 onfocus='select();' name=homepost value='$homepost' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Work Phone:</th>";
$workarea=substr($row[workph],0,3);
$workpre=substr($row[workph],3,3);
$workpost=substr($row[workph],6,4);
echo "<td align=left>(<input type=text onfocus='select();' maxlength=3 size=4 name=workarea value='$workarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=workpre value='$workpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=workpost onfocus='select();' value='$workpost' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Cell Phone:</th>";
$cellarea=substr($row[cellph],0,3);
$cellpre=substr($row[cellph],3,3);
$cellpost=substr($row[cellph],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=cellarea onfocus='select();' value='$cellarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=cellpre value='$cellpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=cellpost value='$cellpost' onfocus='select();' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Fax:</th>";
$faxarea=substr($row[fax],0,3);
$faxpre=substr($row[fax],3,3);
$faxpost=substr($row[fax],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=faxarea onfocus='select();' value='$faxarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=faxpre value='$faxpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=faxpost value='$faxpost' onfocus='select();' onKeyUp='return autoTab(this,4,event);'></td></tr>";

echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
echo "<td align=left><input type=text size=30 name=email value=\"$row[email]\"></td></tr>";
echo "<tr align=left><td colspan=2 align=left><b>Sports:</b>&nbsp;";
echo "<i>Check the box next to the sport(s) this observer evaluates.</i></td></tr>";
echo "<tr align=center><td colspan=2 align=center><table>";
for($i=0;$i<count($activity);$i++)
{
   if($i%2==0)
      echo "<tr align=left>";
   echo "<td align=left>&nbsp;&nbsp;&nbsp;";
   echo "<input type=checkbox name=\"actch[$i]\" value='x'";
   if($row[$activity[$i]]=='x') echo " checked";
   $width=500; $height=500;
   $query2=ereg_replace("[\]","",$query);
   $query2=ereg_replace("\'","\'",$query2);
   echo ">&nbsp;$act_long[$i]";
   echo "</td>";
   if(($i+1)%2==0)
      echo "</tr>";
}
echo "</table></td></tr>";
echo "<input type=hidden name=id value=\"$obsid\">";
echo "<tr align=center><td colspan=2><br><input type=submit name=submit tabindex='1' value=\"Save Changes\">";
echo "</table></form>";
if($header!="no")
{
   echo "<a href=\"welcome.php?session=$session\" class=small>Return Home</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"observers.php?session=$session&sport=$sport&query=$query&last=$last\" class=small>Return to Observers List</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"obs_query.php?session=$session&sport=$sport&query=$query&last=$last\" class=small>Return to Advanced Search</a>";
}
else
{
   echo "<a href=\"#\" onClick=\"window.close()\">Close this Window</a>";
}

echo $end_html;
?>
