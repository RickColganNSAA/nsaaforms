<?php

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
   $sql="DELETE FROM logins WHERE level='6' AND id='$delete'";
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
         $sql="INSERT INTO logins (school,name,email,address1,address2,city_state,zip,passcode,level) VALUES ('$school[$i]','$name[$i]','$email[$i]','$address1[$i]','$address2[$i]','$city_state[$i]','$zip[$i]','$passcode[$i]','6')";
      }
      else		//UPDATE
      {
	 $sql="UPDATE logins SET school='$school[$i]', name='$name[$i]', email='$email[$i]', address1='$address1[$i]', address2='$address2[$i]', city_state='$city_state[$i]', zip='$zip[$i]', passcode='$passcode[$i]' WHERE id='$id[$i]' AND level='6'";
      }
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;
echo "<br>";
echo "<form method=post action=\"esus.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>Manage ESU User Information</b><br>";
echo "<font style=\"font-size:9pt;\">(for rules meeting hosts)</font></caption>";
//ADD NEW ESU USER:
echo "<tr align=left><td colspan=5><b>ADD NEW ESU USER:</b></td></tr>";
echo "<tr valign=top align=center><td><b>ESU</b></td><td><b>Name</b></td>";
echo "<td><b>E-mail</b><br>(Separate Multiple E-mails with Comma)</td><td><b>Address</b></td>";
echo "<td><b>City, State</b></td><td><b>Zip</b></td><td><b>Passcode</b></td><td>&nbsp;</td></tr>";
echo "<tr align=left valign=top><a name=\"$row[id]\">";
$ix1=0;
echo "<input type=hidden name=\"id[$ix1]\" value='0'>";
echo "<td><input type=text size=10 class=tiny name=\"school[$ix1]\"></td>";
echo "<td><input type=text size=20 class=tiny name=\"name[$ix1]\"></td>";
echo "<td><input type=text size=30 class=tiny name=\"email[$ix1]\"></td>";
echo "<td><input type=text size=20 class=tiny name=\"address1[$ix1]\"><br>";
echo "<input type=text size=20 class=tiny name=\"address2[$ix1]\"></td>";
echo "<td><input type=text size=15 class=tiny name=\"city_state[$ix1]\"></td>";
echo "<td><input type=text size=10 class=tiny name=\"zip[$ix1]\"></td>";
echo "<td><input type=text size=10 class=tiny name=\"passcode[$ix1]\"></td></tr>";
echo "<tr align=left><td colspan=6><input type=submit name=addnew value=\"Add New ESU\"><br><br></td>";
echo "</a></tr>";
$ix1++;

//VIEW/EDIT CURRENT COLLEGE USERS:
echo "<tr align=left><td colspan=6><b>CURRENT ESU USERS:</b><br><div style='text-align:left;margin:15px;' id='exportdiv'>Generating Export....</div></td></tr>";
echo "<tr valign=top align=center><td><b>ESU</b></td><td><b>Name</b></td><td><b>E-mail</b><br>(Separate Multiple E-mails with Comma)</td><td><b>Address</b></td>";
echo "<td><b>City, State</b></td><td><b>Zip</b></td><td><b>Passcode</b></td><td><b>Delete</b></td></tr>";
$csv="\"ESU\",\"Name\",\"Email\",\"Address1\",\"Address2\",\"City\",\"State\",\"Zip\"\r\n";
$sql="SELECT * FROM logins WHERE level='6' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left valign=top><a name=\"$row[id]\">";
   echo "<input type=hidden name=\"id[$ix1]\" value=\"$row[id]\">";
   echo "<td><input type=text size=10 class=tiny value=\"$row[school]\" name=\"school[$ix1]\"></td>";
   echo "<td><input type=text size=20 class=tiny value=\"$row[name]\" name=\"name[$ix1]\"></td>";
   echo "<td><input type=text size=30 class=tiny value=\"$row[email]\" name=\"email[$ix1]\"></td>";
   echo "<td><input type=text size=20 class=tiny value=\"$row[address1]\" name=\"address1[$ix1]\"><br>";
   echo "<input type=text size=20 class=tiny value=\"$row[address2]\" name=\"address2[$ix1]\"></td>";
   echo "<td><input type=text size=15 class=tiny value=\"$row[city_state]\" name=\"city_state[$ix1]\"></td>";
   echo "<td><input type=text size=10 class=tiny value=\"$row[zip]\" name=\"zip[$ix1]\"></td>";
   echo "<td><input type=text size=10 class=tiny value=\"$row[passcode]\" name=\"passcode[$ix1]\"></td>";
   echo "<td align=center><a class=small href=\"esus.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete $row[school]??');\">X</a></td>";
   echo "</a></tr>";
   $csv.="\"$row[school]\",\"$row[name]\",\"$row[email]\",\"$row[address1]\",\"$row[address2]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\"\r\n";
   $ix1++;
}
echo "<tr align=left><td colspan=6><input type=submit name=save value=\"Save ESU's\"></td></tr>";

$open=fopen(citgf_fopen("/home/nsaahome/reports/esusexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/esusexport.csv");
?>
<script language="javascript">
document.getElementById('exportdiv').innerHTML="<a href=\"exports.php?session=<?php echo $session; ?>&filename=esusexport.csv\">Download Export</a>";
</script>
<?php

echo "</table>";
echo "</form>";

echo $end_html;
?>
