<?php
/*********************************
editmeet_te_b.php
Edit Meet for Boys Tennis Results
Created 7/22/08
Author: Ann Gaffigan
*********************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sport='te_b';
$sportname="Boys Tennis";
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
   $sid=GetSID($session,$sport);
}
else if($school_ch)
{
   $sid=$school_ch;
   $school=GetMainSchoolName($sid,$sport);
}
else
{
   echo "ERROR: No School Selected";
   exit();
}
if(!$meetid || $meetid=='')
{
   echo "ERROR: No Meet Selected";
}
$school2=ereg_replace("\'","\'",$school);

if($hiddensave || $save)
{
   $meetname=addslashes($meetname);
   $startdate="$year-$month-$day";
   if($month2=='' || $day2=='') $enddate=$startdate;
   else $enddate="$year-$month2-$day2";
   $meetsite=addslashes($meetsite);
   if($meetsite=="other") $meetsite=addslashes($othersite);
   $sql="UPDATE ".$sport."meets SET meetname='$meetname',startdate='$startdate',enddate='$enddate',meetsite='$meetsite' WHERE id='$meetid'";
   $result=mysql_query($sql);
   header("Location:meetresults_te_b.php?school_ch=$school_ch&session=$session&meetid=$meetid");
   exit();
}

echo $init_html_ajax."</head><body>";
?>
<script language='javascript'>
function ErrorCheck()
{
   var errors='';
   //check meet name, date, host 
   if(Utilities.getElement('meetname').value=='')
      errors+="<tr align=left><td><font style=\"color:red\"><b>Meet Name:</b></font>&nbsp;Please enter the name of the tennis meet.</td></tr>";
   if(Utilities.getElement('month').selectedIndex==0 || Utilities.getElement('day').selectedIndex==0 || Utilities.getElement('year').selectedIndex==0)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Meet Date:</b></font>&nbsp;Please enter the date(s) of the tennis meet.</td></tr>";
   if(Utilities.getElement('meetsite').selectedIndex==0 && Utilities.getElement('othersite').value=='')
      errors+="<tr align=left><td><font style=\"color:red\"><b>Meet Host:</b></font>&nbsp;Please enter the school hosting the tennis meet.</td></tr>";
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your form:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      Utilities.getElement('hiddensave').value="Save";
      document.forms.meetform.submit();
   }
}
</script>
<?php
echo $header;

echo "<br><form name=\"meetform\" method=post action=\"editmeet_".$sport.".php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"school_ch\" value=\"$school_ch\">";
echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
echo "<input type=hidden name=\"meetid\" value=\"$meetid\">";
$sql="SELECT * FROM ".$sport."meets WHERE id='$meetid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<table class=nine><caption><b>You are editing the details for \"$row[meetname]\":</b><br><i>Please edit the name, date(s) and host for this meet.</i><br><a class=small href=\"main_".$sport.".php?school_ch=$school_ch&session=$session\">".$sportname." Main Menu</a><hr></caption>";
echo "<tr align=left><td><b>Meet Name:</b></td><td><input type=text size=45 value=\"$row[meetname]\" name=\"meetname\" id=\"meetname\"></td></tr>";
echo "<tr align=left><td><b>Meet Date(s):</b></td><td><select name=\"month\" id=\"month\"><option value=''>MM</option>";
$start=split("-",$row[startdate]);
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if($m==$start[1]) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day\" id=\"day\"><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if($d==$start[2]) echo " selected";
   echo ">$d</option>";
}
echo "</select>/&nbsp;".date("Y");
echo "<input type=hidden name=\"year\" id=\"year\" value=\"".$start[0]."\"> - ";
$end=split("-",$row[enddate]);
echo "<select name=\"month2\" id=\"month2\"><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{   
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";  
   if($m==$end[1]) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day2\" id=\"day2\"><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if($d==$end[2]) echo " selected";
   echo ">$d</option>";
}
echo "</select>/&nbsp;".date("Y");
echo "<input type=hidden name=\"year2\" id=\"year2\" value=\"".$end[0]."\"></td></tr>";
echo "<tr><td colspan=2><div id=\"errordiv\" class=\"searchresults\" style=\"left:30%;width:400px;display:none;\"></div></td></tr>";
echo "<tr align=left><td><b>Meet Host:</b></td><td><table cellspacing=0 cellpadding=0><tr align=left><td>";
echo "<select id=\"meetsite\" name=\"meetsite\" onchange=\"if(this.options.selectedIndex==0) { Utilities.getElement('other').style.visibility='visible'; othersite.value=''; } else { Utilities.getElement('other').style.visibility='hidden'; }\"><option value='other'>OTHER</option>";
$sql2="SELECT * FROM ".$sport."school ORDER BY school";
$result2=mysql_query($sql2);
$found==0;
while($row2=mysql_fetch_array($result2))
{
   echo "<option value=\"$row2[school]\"";
   if($row2[school]==$row[meetsite])
   {
      echo " selected";
      $found=1;
   }
   echo ">$row2[school]</option>";
}
if($found==1) $vis="hidden";
else $vis="visible";
echo "</select></td><td><div id=\"other\" style=\"visibility:$vis;\"><input type=text size=30 name=\"othersite\" id=\"othersite\" value=\"$row[meetsite]\"></div></td></tr></table></td></tr>";
echo "<tr align=center><td colspan=2><i>When you click \"Continue\", you will be taken the page where you enter results for this meet.</i><br><input type=button name=\"save\" value=\"Continue\" onclick=\"ErrorCheck();\"></td></tr>";
echo "</table>";
echo "</form>";

echo $end_html;
?>
