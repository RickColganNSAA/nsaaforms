<?php
/*********************
registrationdata.php
Show ALL initiated registrations
in case they need to look up info
for an incomplete registration.
This screen offers ability to "push"
a registration through if the NSAA
has a transaction on record but the
registration was not marked as complete.
Created 1/11/16 by Ann Gaffigan
Adapted from rulesmeetingdata.php
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

echo "<br><a href=\"officialsapp.php?session=$session\" class=small>&larr; Return to COMPLETED Officials & Judges Registrations</a><br><br>";
echo "<form method=post action=\"registrationdata.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;' class='nine'>";
echo "<caption><h2>All INITIATED Officials & Judges Online Registration Forms:</h2>";
//SELECT DAY
if((!$day || $day=="") && (!$invoiceid || $invoiceid=='')) $day=date("Y-m-d");
else if($invoiceid && $invoiceid!='') $day="";
echo "<h3>Show Registrations for: <select name=\"day\">";
$sql="SELECT DISTINCT FROM_UNIXTIME(appid,'%Y-%m-%d') as day FROM officialsapp
        UNION DISTINCT
SELECT DISTINCT FROM_UNIXTIME(appid,'%Y-%m-%d') as day FROM judgesapp ORDER BY day DESC";
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
echo "<h3>OR: Search by Invoice #: <input type=text name=\"invoiceid\" size=25 value=\"$invoiceid\"> <input type=\"submit\" name=\"show2\" value=\"Go\"></h3>";
if(mysql_error()) echo $sql."<br>".mysql_error()."<br>";
echo "</caption>";
$colheaders="<tr align='center'><th>Invoice # & Registration Form</th><th>Official or Judge</th><th>Name</th><th>Transaction Approved</th></tr>";

$sql="SELECT 'officialsapp' AS tablename1,t1.appid,t1.approved,t1.checked,t1.notes,t1.nosee,t2.offid,t2.first,t2.last,t2.state FROM officialsapp AS t1, pendingoffs AS t2 WHERE t1.appid=t2.appid AND t1.appid!=''";
if($day && $day!='') $sql.=" AND FROM_UNIXTIME(t1.appid,'%Y-%m-%d')='$day'";
else if($invoiceid && $invoiceid!='') $sql.=" AND t1.appid='$invoiceid'";
$sql.=" UNION ALL";
$sql.=" SELECT 'judgesapp' AS tablename2,t3.appid,t3.approved,t3.checked,t3.notes,t3.nosee,t4.offid,t4.first,t4.last,t4.state FROM judgesapp AS t3, pendingjudges AS t4 WHERE t3.appid=t4.appid AND t3.appid!=''";
if($day && $day!='') $sql.=" AND FROM_UNIXTIME(t3.appid,'%Y-%m-%d')='$day'";
else if($invoiceid && $invoiceid!='') $sql.=" AND t3.appid='$invoiceid'";
$sql.=" ORDER BY appid DESC";
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
   echo "<tr align='left'><td><a href=\"officialsapp.php?session=$session&curtable=$row[0]&curappid=$id\" target=new>#$id: $date</a></td>";
   //OFFICIAL OR JUDGE?
   if($row[0]=="judgesapp")     //JUDGE
      echo "<td>JUDGE</td>";
   else
      echo "<td>OFFICIAL</td>";
   //NAME
   echo "<td>$row[first] $row[last] (ID# $row[offid])</td>";
   //APPROVED?
   if($row[approved]=="yes") echo "<td>YES";
   else echo "<td bgcolor='#ff0000'><span style='color:#ffffff;'>NO</span>";
   if($row[approved]!='yes') //GIVE LINK TO "PUSH THROUGH" TRANSACTION
   {
      //Sometimes when a transaction is approved, the connection times out before
      //the customer returns from the Authorize.net gateway. Let NSAA push them through
      echo "<br>";
      if($row[0]=="judgesapp")	//JUDGES
      {
         echo "<a href=\"japproval.php?session=$session&secret=46D5431FF61CD7EC47478FB32A52A&ssl_invoice_number=$row[appid]\" target=\"_blank\">Push Transaction Through*</a>";
      }
      else	//OFFICIALS
      {
	 if($row[state]=="NE") //IN-STATE
            echo "<a href=\"approval.php?session=$session&secret=46D5431FF61CD7EC47478FB32A52A&ssl_invoice_number=$row[appid]\" target=\"_blank\">Push Transaction Through*</a>";
         else	//AFFILIATE
            echo "<a href=\"affapproval.php?session=$session&secret=46D5431FF61CD7EC47478FB32A52A&ssl_invoice_number=$row[appid]\" target=\"_blank\">Push Transaction Through*</a><br>(Affiliate Official)";
      }
   }
   echo "</td></tr>";
   $ix++;
}
if(mysql_num_rows($result)>0)
   echo "</table></form>";
else
   echo "<tr align=center><td>[No registrations found.]</td></tr></table></form>";
echo "<p>* If you see a link to \"Push Transaction Through,\" only click it if an official/judge has paid but is not showing as having paid in the table above.</p>";

echo $end_html;
?>
