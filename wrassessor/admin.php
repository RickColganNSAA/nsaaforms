<?php
require "wrfunctions.php";
require "../variables.php";

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidAdmin($session))
{
   header("Location:../index.php?error=1");
   exit();
}

echo $init_html;
echo GetAdminHeader($session);

echo "<br><table cellspacing=0 cellpadding=3 style=\"border:#808080 1px solid;\" frame=all rules=all><caption><b>NSAA Wrestling Assessors Admin:</b>";
if($deleted)
{
   echo "<div class=alert style=\"width:400px;text-align:center;\">The account for Assessor #$deleted has been deleted.</div>";
}
/**************FILTER******************/
echo "<div class=normalwhite style=\"width:650px;\">";
echo "<form method=post action=\"admin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellpadding=3 cellspacing=3>";
echo "<tr align=left><td><b>FILTER ASSESSORS:</b></td></tr>";
echo "<tr align=left><td><b>by REGISTRATION: </b><input type=radio name=\"registered\" value=\"yes\"";
if($registered=="yes") echo " checked";
echo ">Show only REGISTERED assessors&nbsp;&nbsp;<input type=radio name=\"registered\" value=\"no\"";
if($registered=="no") echo " checked";
echo ">Show only NON-REGISTERED assessors&nbsp;&nbsp;<input type=radio name=\"registered\" value=\"both\"";
if($registered=="both" || !$registered) echo " checked";
echo ">Show BOTH</td></tr>";
echo "<tr align=center><td><input type=submit name=\"filter\" value=\"Filter\"></td></tr>";
echo "</table>";
echo "</form>";
echo "</div>";
/**************END FILTER**************/
echo "<div class='alert' id='export' style='width:350px;text-align:center;margin-top:10px;'></div><br>";
echo "<div style='width:650px;text-align:right;'><a href=\"adduser.php?session=$session\">+ Add a NEW Assessor</a></div>";
echo "</caption>";
echo "<tr align=center>";
if(!$sort) $sort="t2.appid DESC";
echo "<input type=hidden name=\"sort\" value=\"$sort\">";
if($sort=="last DESC, first DESC")
{
   $curimg="arrowup.png"; $cursort="last ASC, first ASC";
}
else if($sort=="last ASC, first ASC")
{
   $curimg="arrowdown.png"; $cursort="last DESC, first DESC";
}
else
{
   $curimg=""; $cursort="last DESC, first DESC";
}
echo "<td><a class=small href=\"admin.php?registered=$registered&filter=$filter&session=$session&sort=$cursort\">Assessor<br>(click to edit/view details)</a>";
if(ereg("last ",$sort))
   echo "&nbsp;<a href=\"admin.php?registered=$registered&filter=$filter&session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=15></a>";
echo "</td>";
if($sort=="t1.datecreated DESC")
{
   $curimg="arrowup.png"; $cursort="t1.datecreated ASC";
}
else if($sort=="t1.datecreated ASC")
{
   $curimg="arrowdown.png"; $cursort="t1.datecreated DESC";
}
else
{
   $curimg=""; $cursort="t1.datecreated DESC";
}
echo "<td><a class=small href=\"admin.php?registered=$registered&filter=$filter&session=$session&sort=$cursort\">Date/Time CREATED ACCOUNT</a>";
if(ereg("datecreated ",$sort))
   echo "&nbsp;<a href=\"admin.php?registered=$registered&filter=$filter&session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=15></a>";
echo "</td>";
if($sort=="t2.appid DESC")
{
   $curimg="arrowup.png"; $cursort="t2.appid ASC";
}
else if($sort=="t2.appid ASC")
{
   $curimg="arrowdown.png"; $cursort="t2.appid DESC";
}
else
{
   $curimg=""; $cursort="t2.appid DESC";
}
echo "<td><a class=small href=\"admin.php?registered=$registered&filter=$filter&session=$session&sort=$cursort\">Date/Time Submitted REGISTRATION</a>";
if(ereg("appid ",$sort))
   echo "&nbsp;<a href=\"admin.php?registered=$registered&filter=$filter&session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=15></a>";
echo "</td>";
echo "<td><b>E-mail</b></td></tr>";

$csv="\"Last Name\",\"First Name\",\"E-mail\"\r\n";
$sql="SELECT * FROM wrassessors";
$sql.=" ORDER BY $sort";
$sql="SELECT t1.*,t2.appid AS curappid FROM wrassessors AS t1 LEFT JOIN wrassessorsapp AS t2 ON t1.userid=t2.assessorid WHERE (t2.approved='yes' OR t2.id IS NULL) ORDER BY $sort";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT t1.*,t2.appid FROM wrassessors AS t1,wrassessorsapp AS t2 WHERE t1.userid=t2.assessorid AND t2.approved='yes' AND t2.assessorid='$row[userid]' ORDER BY t2.appid DESC LIMIT 1";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(!$filter || ($filter && $registered=="yes" && IsPaid($row[userid])) || ($filter && $registered=="no" && !IsPaid($row[userid])) || ($filter && $registered=="both"))
   {
      echo "<tr align=left>";
      echo "<td><a href=\"manageuser.php?session=$session&userid=$row[userid]\" class=small>$row[last], $row[first]</a></td>";
      if($row[datecreated]>0)
         echo "<td>".date("m/d/y",$row[datecreated])." at ".date("g:ia T",$row[datecreated])."</td>";
      else echo "<td>ACCOUNT ALREADY EXISTED</td>";
      if(mysql_num_rows($result2)>0)
         echo "<td>".date("m/d/y",$row2[appid])." at ".date("g:ia T",$row2[appid])."</td>";
      else
         echo "<td>NOT REGISTERED</td>";
      echo "<td><a class=small href=\"mailto:$row[email]\">$row[email]</a></td>";
      echo "</tr>";
      $csv.="\"$row[last]\",\"$row[first]\",\"$row[email]\"\r\n";
   }
}
echo "</table>";
//WRITE TO FILE
$open=fopen(citgf_fopen("/home/nsaahome/reports/wrassessors_".date("m-d-y").".csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/wrassessors_".date("m-d-y").".csv"); 
?>
<script language='javascript'>
document.getElementById('export').innerHTML="<a class=small href=\"../exports.php?session=<?php echo $session; ?>&filename=wrassessors_<?php echo date("m-d-y"); ?>.csv\">Export Wrestling Assessors Shown Below</a>";
</script>
<?php
echo "</td></tr></table>";
echo $end_html;
?>
