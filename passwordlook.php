<?php
//passwordlook.php: allows NSAA users only to look up passcodes
//	of ADs and coaches

//validate user
require 'functions.php';
require 'variables.php';
$level=GetLevel($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:/nsaaforms/index.php");
   exit();
}

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}

$school2=ereg_replace("\'","\'",$school);

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

echo $init_html;
$header=GetHeader($session);
echo $header;

if($submit=="Go")
{
   if(ereg("Public Schools",$school2))
   {
      $sql1="SELECT * FROM logins WHERE school='$school2' AND level=5";
      $result1=mysql_query($sql1);
   }
   else 
   {
   if($activity_ch[0]=="AD")
   {
      $sql1="SELECT * FROM logins WHERE school='$school2' AND level=2 AND sport IS NULL";
   }
   if($activity_ch[0]=="All Coaches" || $activity_ch[1]=="All Coaches")
   {
      $sql2="SELECT * FROM logins WHERE school='$school2' AND level=3 AND sport IS NOT NULL";
   }
   else if((count($activity_ch)>0 && $activity_ch[0]!="AD") || ($activity_ch[0]=="AD" && count($activity_ch)>1))
   {
      $sql2="SELECT * FROM logins WHERE school='$school2' AND level=3 AND (";
      for($i=0;$i<count($activity_ch);$i++)
      {
	 if($activity_ch[$i]!="AD")
	 {
	    $sql2.="sport='$activity_ch[$i]' OR ";
	 }
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
   }
   $result1=mysql_query($sql1);
   $result2=mysql_query($sql2);
   }
?>
<center><br><br>
<table width=250 cellspacing=2 cellpadding=3 border=1 bordercolor=#000000>
<caption><b>Passcode(s) for <?php echo $school; ?>:</b></caption>
<tr align=center>
<th colspan=2>User</th><th>Passcode</th>
</tr>
<?php
   while($row1=mysql_fetch_array($result1))
   {
      echo "<tr align=left><td>$row1[1]</td>";
      echo "<td>AD</td>";
      echo "<td>$row1[6]</td></tr>";
   }
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr align=left><td>$row2[1]</td>";
      echo "<td>$row2[4]</td>";
      echo "<td>$row2[6]</td></tr>";
   }
echo "</table>";
echo "<br><a href=\"passwordlook.php?session=$session\">Lookup More Passcodes</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\">Home</a>";
exit();
}
?>
<center><br><br>
<form method=post action="passwordlook.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<table cellspacing=2 cellpadding=2>
<caption align=left><i>Choose the school (and activity if needed) of the user whose passcode you wish to retrieve:</i></caption>
<tr align=center valign=top>
<td><b>School:</b><br><select name=school>
<option>Lincoln Public Schools
<option>Omaha Public Schools
<option>Millard Public Schools
<?php
//get all schools
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option>$row[0]";
}
?>
   </select>
   </td>
   <td>
   <b>User(s):</b><br><select name=activity_ch[] multiple size=3>
   <option>AD
   <option>All Coaches
<?php
//get all activities
for($i=0;$i<count($act_long);$i++)
{
   echo "<option>$act_long[$i]";
}
?>
   </select>
   <br>
   <font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections
   </td>
   </tr>
   <tr align=center>
   <td colspan=2>
   <input type=submit name=submit value="Go">
   <input type=submit name=submit value="Cancel">
</td>
</tr>
</form>
<tr align=left>
<td><a href="passcodereport.php?session=<?php echo $session; ?>">Generate File of Passcodes for All Schools</a></td>
</tr>
</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
