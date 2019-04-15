<?php
//rulesmeetingpayments.php: officials & coaches transactions for online rules meetings

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"rulesmeetingadmin");
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

if($curinvoiceid && $database)
{
   $sql="SELECT * FROM $database.rulesmeetingtransactions WHERE invoiceid='$curinvoiceid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $html=preg_replace("/\<html\>\<body\>/","",$row[html]);
   $html=preg_replace("/\<\/body\>\<\/html\>/","",$html);
   echo $html;
   exit();
}
else if($print)
{
//TESTING
//$database.="20132014";
   echo $init_html."<style type=\"text/css\">
        @media print {
           div {
              position: static;
           }
        }
        </style>";
   for($i=0;$i<count($appid);$i++)
   {
      $sql="SELECT * FROM $database.rulesmeetingtransactions WHERE invoiceid='$appid[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html=preg_replace("/\<html\>\<body\>/","",$row[html]);
      $html=preg_replace("/\<\/body\>\<\/html\>/","",$html);
      echo $html;
      echo "<div style=\"page-break-after:always;\">";
      echo "</div>";
   }
   echo $end_html;
   exit();
}

//TESTING
//$database.="20132014";

if($hiddensave)
{
   for($i=0;$i<count($appid);$i++)
   {
      $note[$i]=ereg_replace("\'","\'",$note[$i]);
      $note[$i]=ereg_replace("\"","\'",$note[$i]);
      if($noseeall=='x') $nosee[$i]="y";
      if($checkall=='x') $check[$i]="y";
      $sql="UPDATE $database.rulesmeetingtransactions SET checked='$check[$i]', notes='$note[$i]', nosee='$nosee[$i]' WHERE invoiceid='$appid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;
echo "<br><form method=post action=\"rulesmeetingpayments.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=\"viewall\" value=\"$viewall\">";
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
if(!$database) $database=$db_name2;
echo "<caption><b>Submitted <select name=\"database\" id=\"database\" onchange=\"submit();\">";
echo "<option value=\"$db_name2\"";
if(preg_match("/officials/",$database)) echo " selected";
echo ">Officials & Judges</option><option value=\"$db_name\"";
if(preg_match("/scores/",$database)) echo " selected";
echo ">Coaches</option></select> ";
echo "<select onchange=\"submit();\" name=\"sport\"><option value=''>All Sports</option>";
$sql2="SHOW TABLES LIKE '%rulesmeetings'";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $temp=split("rulesmeetings",$row2[0]);
   echo "<option value=\"$temp[0]\"";
   if($sport==$temp[0]) echo " selected";
   echo ">".GetSportName($temp[0])."</option>";
}
echo "</select> ";
echo "Online Rules Meeting Payments on <select name=\"day\" onChange=\"submit();\">";
$sql="SELECT DISTINCT FROM_UNIXTIME(datepaid,'%Y-%m-%d') as day FROM $database.rulesmeetingtransactions WHERE datepaid>0 ORDER BY day DESC";
$result=mysql_query($sql);
if(!$day || $day=="") $day=date("Y-m-d");
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[day]);
   $showday=date("D, M j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<option value=\"$row[day]\"";
   if($day==$row[day]) echo " selected";
   echo ">$showday</option>";
}
echo "</select> <input type=\"button\" onClick=\"form.target='_self';document.getElementById('print').value='0';submit();\" name=\"show\" value=\"Go\"></b><br><br>";
//VIEW ALL?
if($viewall=='x')
   echo "<a class=small href=\"rulesmeetingpayments.php?day=$day&sport=$sport&database=$database&session=$session\">View Only Transactions that HAVEN'T been Deleted from View</a><br><br>";
else
   echo "<a class=small href=\"rulesmeetingpayments.php?day=$day&sport=$sport&database=$database&session=$session&viewall=x\">View ALL Transactions, including those that have been deleted from view</a><br><br>";
echo "</caption>";
$colheaders="<tr align=left><th class=smaller>Delete<br>from View</th><th class=smaller>Sport</th><th class=smaller>Transaction Receipt</th><th class=smaller>Name</th>";
if($database==$db_name) 
{
   $colheaders.="<th class=smaller>School</th><th class=smaller>Official/Judge?</th>";
   $bottomcolspan=5;
}
else 
{
   $colheaders.="<th class=smaller>Head Coach?</th>";
   $bottomcolspan=4;
}
$colheaders.="<th class=smaller>Check if<br>Viewed</th><th class=smaller>Notes</th></tr>";
$datestart = new DateTime("$day 00:00:01");
$datestart= $datestart->getTimestamp();
$dateend = new DateTime("$day 23:59:59");
$dateend= $dateend->getTimestamp();
$sql="SELECT * FROM $database.rulesmeetingtransactions WHERE ";
if($sport && $sport!='')
   $sql.="invoiceid LIKE '%-$sport' AND ";
if($day && $day!='')
   //$sql.="FROM_UNIXTIME(datepaid,'%Y-%m-%d')='$day' AND ";
   $sql.="(datepaid >='$datestart' AND  datepaid <='$dateend') AND ";
$sql.="approved='yes' AND ";
if($viewall!='x') $sql.="nosee!='y' AND ";
$sql.="datepaid>0 ORDER BY datepaid DESC";
$result=mysql_query($sql);
$ix=0;
//echo $sql;
while($row=mysql_fetch_array($result))
{
   $date=date("m/d/Y H:i T",$row[datepaid]);
   $id=$row[invoiceid]; $temp=split("-",$id); $cursp=strtoupper($temp[1]);
   if($ix%15==0) echo $colheaders;
   echo "<tr align=left><td align=center><input type=checkbox name=\"nosee[$ix]\"";
   if($row[nosee]=='y') echo " checked";
   echo " value='y'></td><td>$cursp</td><td>";
   if($row[html]=="") echo "<font style=\"color:red\"><b>!</b></font>&nbsp;";
   echo "<a href=\"rulesmeetingpayments.php?database=$database&session=$session&curinvoiceid=$id\" target=\"_blank\">#$id: $date</a>";
   if($database==$db_name2)
   {
      if($cursp=='SP' || $cursp=='PP' || $cursp=='SPPP') $table="judges";
      else $table="officials";
      $sql2="SELECT first,last FROM $table WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $name="$row2[first] $row2[last]";
      $sql2="SELECT * FROM $database.".strtolower($cursp)."rulesmeetings WHERE offid='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $school1=$row2[school1]; $school2=$row2[school2];
   }
   else
   {
      $sql2="SELECT name,school FROM $db_name.logins WHERE id='$row[coachid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $name=$row2[name]; $school=$row2[school];
      $sql2="SELECT * FROM $database.".strtolower($cursp)."rulesmeetings WHERE coachid='$row[coachid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offid=$row2[offid];
   }
   echo "</td><td>$name</td>";
   if($database==$db_name) 
   {
      echo "<td>$school</td>";
      if(!$offid) echo "<td>No</td>";
      else echo "<td>Yes (Official ID # $offid)</td>";
   }
   else
   {
      echo "<td>";
      if($cursp=='SPPP')
      {
         if($school1!='') echo "Speech Director<br>";
         if($school2!='') echo "Play Director";
      }
      else if($cursp=='BB' || $cursp=='SO' || $cursp=='SW' || $cursp=='TR')
      {
         if($school1!='') echo "Boys ".GetSportName(strtolower($cursp))."<br>";
         if($school2!='') echo "Girls ".GetSportName(strtolower($cursp));
      }
      echo "&nbsp;</td>";
   }
   echo "<td align=center><input type=checkbox name=\"check[$ix]\"";
   if($row[checked]=='y') echo " checked";
   echo " value='y'></td><td><input type=text size=40 name=\"note[$ix]\" value=\"$row[notes]\"></td></tr>";
   echo "<input type=hidden name=\"appid[$ix]\" value=\"$id\">";
   $ix++;
}
if(mysql_num_rows($result)>0)
{
   echo "<tr align=center><td><b>Check ALL</b><br /><input type=checkbox name=\"noseeall\" value=\"x\"></td><td colspan=\"$bottomcolspan\">&nbsp;</td><td><b>Check ALL</b><br /><input type=checkbox name=\"checkall\" value=\"x\"></td><td>&nbsp;</td></tr>";
   echo "</table><br><input type=hidden name=\"hiddensave\" id=\"hiddensave\" value=\"0\"><input type=button onClick=\"form.target='_self';document.getElementById('print').value='0';document.getElementById('hiddensave').value=1;submit();\" name=\"save\" value=\"Save\">&nbsp;&nbsp;<input type=button onClick=\"form.target='_blank';document.getElementById('print').value='1';submit();\" name=\"printbutton\" value=\"Print ALL\"><input type=hidden name='print' id='print'></form>";
}
else
   echo "<tr align=center><td>[No transactions have been completed at this time.]</td></tr></table></form>";

echo $end_html;
?>
