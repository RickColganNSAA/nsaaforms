<?php
//officialsapp.php: manage official's applications

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"officialsapp");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//date_default_timezone_set("Canada/Saskatchewan");
//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($curappid)
{ 
   if(!$curtable || $curtable=="") $curtable="officialsapp";
    $sql="SELECT * FROM $curtable WHERE appid='$curappid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo $init_html;
   $html=preg_replace("/\<html\>\<body\>/","",$row[html]);
   $html=preg_replace("/\<\/body\>\<\/html\>/","",$html);
   $a=explode('<td>',$html); 
   $a[2] = str_replace($a[2],'*********',$a[2]); 
   $a[2] = $a[2].'</td></tr><tr align="left"><th align="left">Full Name:';
   $html=implode('<td>',$a); 
   print_r($html); exit; 
   echo $html;
   echo $end_html;
   exit();
}
else if($print)
{  
   echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'.$init_html;
   for($i=0;$i<count($appid);$i++)
   {     
	  if(!$table[$i] || $table[$i]=="") $table[$i]="officialsapp";
      $sql="SELECT * FROM ".$table[$i]." WHERE appid='$appid[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html=preg_replace("/\<html\>\<body\>/","",$row[html]);
      $html=preg_replace("/\<\/body\>\<\/html\>/","",$html);
      $html=preg_replace("/\?tr\>/","/tr>",$html);
      echo $html;
      echo "<div style=\"position:static;page-break-after:always;\">&nbsp;</div>";
   } 
   echo $end_html;
   exit();
}

if($_POST && count($appid)>0)
{
   for($i=0;$i<count($appid);$i++)
   {
      $note[$i]=ereg_replace("\'","\'",$note[$i]);
      $note[$i]=ereg_replace("\"","\'",$note[$i]);
      if(!$table[$i] || $table[$i]=="") $table[$i]="officialsapp";
      if($checkallnosee=='x') $nosee[$i]='y';
      if($checkallcheck=='x') $check[$i]='y';
      $sql="UPDATE ".$table[$i]." SET checked='$check[$i]', notes='$note[$i]', nosee='$nosee[$i]' WHERE appid='$appid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<br><a href=\"assessorsapp.php?session=$session\" class=small>Go to WR ASSESSORS' Registration</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"wrvideopayments.php?session=$session\" class=small>Go to WR VIDEO PAYMENTS</a><br><br>";
echo "<a class=small target=\"_blank\" href=\"application.php\">Preview Officials Registration Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"japplication.php\" target=\"_blank\" class=\"small\">Preview Judges Registration Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a class=small href=\"duedates.php?session=$session&table=reg_duedates\">Edit Registration Due Dates</a>";
echo "<br><br><br><form method=post action=\"officialsapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=\"viewall\" value=\"$viewall\">";
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='border:#808080 1px solid;' class='nine'>";
echo "<caption><h2>Submitted Officials & Judges Online Registration Forms:</h2>";
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
echo "</select> <input type=\"button\" onClick=\"form.target='_self';document.getElementById('print').value='0';submit();\" name=\"show\" value=\"Go\"></h3>";
echo "<h3>OR: Search by Invoice #: <input type=text name=\"invoiceid\" size=25 value=\"$invoiceid\"> <input type=\"button\" onClick=\"form.target='_self';document.getElementById('print').value='0';submit();\" name=\"show2\" value=\"Go\"></h3>";
if(mysql_error()) echo $sql."<br>".mysql_error()."<br>";
//VIEW ALL?
if($viewall=='x')
   echo "<a href=\"officialsapp.php?session=$session&day=$day\">View COMPLETED Registrations that HAVEN'T been Deleted from View</a>";
else
   echo "<a href=\"officialsapp.php?session=$session&viewall=x&day=$day\">View All COMPLETED Registrations</a>";
echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"registrationdata.php?session=$session&day=$day\">View INITIATED Registrations, Completed or NOT &rarr;</a><br><br>";
echo "</caption>";
date_default_timezone_set("America/Chicago");
date_default_timezone_get();

$datestart = new DateTime("$day 00:00:01");
$datestart= $datestart->getTimestamp();
$dateend = new DateTime("$day 23:59:59");
$dateend= $dateend->getTimestamp();

$colheaders="<tr align=left><td><b>Delete<br>from View</b></td><td><b>Registration Form</b></td><td><b>Official<br>or Judge</b></td><td><b>Name</b></td><td><b>Check if<br>Viewed</b></td><td><b>Notes</b></td></tr>";
$sql="SELECT 'officialsapp' AS tablename1,appid,checked,notes,nosee FROM officialsapp WHERE approved='yes' AND appid!=''";
if($viewall!='x') $sql.=" AND nosee!='y'";
if($day && $day!='') $sql.=" AND (appid >='$datestart' and  appid <='$dateend')";
else if($invoiceid && $invoiceid!='') $sql.=" AND appid='$invoiceid'";
$sql.=" UNION ALL";
$sql.=" SELECT 'judgesapp' AS tablename2,appid,checked,notes,nosee FROM judgesapp WHERE approved='yes' AND appid!=''";
if($viewall!='x') $sql.=" AND nosee!='y'";
//if($day && $day!='') $sql.=" AND FROM_UNIXTIME(appid,'%Y-%m-%d')='$day'";
if($day && $day!='')  $sql.=" AND (appid >='$datestart' and  appid <='$dateend') ";
else if($invoiceid && $invoiceid!='') $sql.=" AND appid='$invoiceid'";
$sql.=" ORDER BY appid DESC";
$result=mysql_query($sql);
if(mysql_error()) echo mysql_error()."<br>$sql";
$ix=0;
while($row=mysql_fetch_array($result))
{
   $date=date("m/d/Y H:i T",$row[appid]);
   $id=$row[appid];
   if($ix%15==0) echo $colheaders;
   echo "<tr align=left><td align=center><input type=checkbox name=\"nosee[$ix]\"";
   if($row[nosee]=='y') echo " checked";
   echo " value='y'></td><td>";
   echo "<a href=\"officialsapp.php?session=$session&curtable=$row[0]&curappid=$id\" target=new>#$id: $date</a></td>";
   if($row[0]=="officialsapp")
   {
      echo "<td>OFFICIAL</td>";
      $sql2="SELECT first,last FROM officials WHERE appid='$id'";
   }
   else
   {
      echo "<td>JUDGE</td>";
      $sql2="SELECT first,last FROM judges WHERE appid='$id'";
   }
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td>$row2[first] $row2[last]";
   echo "</td><td align=center><input type=checkbox name=\"check[$ix]\"";
   if($row[checked]=='y') echo " checked";
   echo " value='y'></td><td><input type=text size=40 name=\"note[$ix]\" value=\"$row[notes]\"></td></tr>";
   echo "<input type=hidden name=\"appid[$ix]\" value=\"$id\"><input type=hidden name=\"table[$ix]\" value=\"$row[0]\">";
   $ix++;
}
if(mysql_num_rows($result)>0)	//CHECK ALL OPTIONS
   echo "<tr align=center><td>Check ALL<br /><input type=checkbox name=\"checkallnosee\" value=\"x\"></td><td colspan=3>&nbsp;</td><td>Check ALL<br /><input type=checkbox name=\"checkallcheck\" value=\"x\"><td>&nbsp;</td></tr>";
echo "</table><br><input type=button onClick=\"form.target='_self';document.getElementById('print').value='0';submit();\" name=\"save\" value=\"Save\">&nbsp;&nbsp;<input type=button onClick=\"form.target='_blank';document.getElementById('print').value='1';submit();\" name=\"printbutton\" value=\"Print ALL\"><input type=hidden name='print' id='print'></form>";

echo $end_html;
?>
