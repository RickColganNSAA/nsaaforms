<?php
/*********************
assessorsdata.php
Show ALL initiated WR Assessors'
 registrations
in case they need to look up info
for an incomplete registration.
This screen offers ability to "push"
a registration through if the NSAA
has a transaction on record but the
registration was not marked as complete.
Created 1/11/16 by Ann Gaffigan
Adapted from registrationdata.php
**********************/
require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"officialsapp");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

echo $init_html.$header;

echo "<br><a href=\"assessorsapp.php?session=$session\" class=small>&larr; Return to COMPLETED Wrestling Assessors Registrations</a><br><br>";
echo "<form method=post action=\"assessorsdata.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;' class='nine'>";
echo "<caption><h2>All INITIATED Wrestling Assessors Registration Forms:</h2>";
//SELECT DAY
echo "<h3>Show Registrations for: <select name=\"day\"><option value='0'>ALL DAYS</option>";
$sql="SELECT DISTINCT FROM_UNIXTIME(appid,'%Y-%m-%d') as day FROM $db_name.wrassessorsapp ORDER BY day DESC";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[day]);
   $showday=date("D, M j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<option value=\"$row[day]\"";
   if($day==$row[day]) echo " selected";
   echo ">$showday</option>";
}
echo "</select> <input type=\"submit\" name=\"show\" value=\"Go\"></h3>";
if(mysql_error()) echo $sql."<br>".mysql_error()."<br>";
echo "</caption>";
$colheaders="<tr align='center'><th>Invoice # & Registration Form</th><th>User ID</th><th>Name</th><th>Transaction Approved</th></tr>";

$sql="SELECT t1.*,t2.first,t2.last FROM $db_name.wrassessorsapp AS t1, $db_name.wrassessors AS t2 WHERE t1.assessorid=t2.userid ";
if($day && $day!='') $sql.="AND FROM_UNIXTIME(t1.appid,'%Y-%m-%d')='$day' ";
$sql.=" ORDER BY t1.appid DESC";
$result=mysql_query($sql);
//echo $sql;
if(mysql_error()) echo mysql_error()."<br>$sql";
$ix=0;
while($row=mysql_fetch_array($result))
{
   $date=date("m/d/Y H:i T",$row[appid]);
   $id=$row[appid];
   if($ix%15==0) echo $colheaders;

   //LINK TO REGISTRATION FORM
   echo "<tr align='left'><td><a href=\"assessorsapp.php?session=$session&curappid=$id\" target=new>#$id: $date</a></td>";
   //USER ID
   echo "<td>$row[assessorid]</td>";
   //NAME
   echo "<td>$row[first] $row[last]</td>";
   //APPROVED?
   if($row[approved]=="yes") echo "<td>YES";
   else echo "<td bgcolor='#ff0000'><span style='color:#ffffff;'>NO</span>";
   if($row[approved]!='yes') //GIVE LINK TO "PUSH THROUGH" TRANSACTION
   {
      //Sometimes when a transaction is approved, the connection times out before
      //the customer returns from the Authorize.net gateway. Let NSAA push them through
      echo "<br><a href=\"../wrassessor/approval.php?session=$session&secret=46D5431FF61CD7EC47478FB32A52A&ssl_invoice_number=$row[appid]\" target=\"_blank\">Push Transaction Through*</a>";
   }
   echo "</td></tr>";
   $ix++;
}
if(mysql_num_rows($result)>0)
   echo "</table></form>";
else
   echo "<tr align=center><td>[No registrations found.]</td></tr></table></form>";
echo "<p>* If you see a link to \"Push Transaction Through,\" only click it if an assessor has paid but is not showing as having paid in the table above.</p>";

echo $end_html;
?>
