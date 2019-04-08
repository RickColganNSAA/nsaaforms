<?php
/*********************************
addmeet_te_g.php
Add Meet for Girls Tennis Results
Created 7/18/08
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
$sport='te_g'; $sportname="Girls Tennis";
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
$school2=ereg_replace("\'","\'",$school);

if($hiddensave || $save)
{
   $meetname=addslashes($meetname);
   $startdate="$year-$month-$day";
   if($month2=='' || $day2=='') $enddate=$startdate;
   else $enddate="$year-$month2-$day2";
   $meetsite=addslashes($meetsite);
   if($meetsite=="other") $meetsite=addslashes($othersite);
   $sql="INSERT INTO te_gmeets (meetname,startdate,enddate,meetsite,school) VALUES ('$meetname','$startdate','$enddate','$meetsite','$school2')";
   $result=mysql_query($sql);
   $meetid=mysql_insert_id();
   header("Location:meetresults_te_g.php?school_ch=$school_ch&session=$session&meetid=$meetid");
   exit();
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/TEMeetResults.js"></script>
</head>
<script language='javascript'>
function ErrorCheck()
{
   var errors='';
   //check meet name, date, site
   if(Utilities.getElement('meetname').value=='')
      errors+="<tr align=left><td><font style=\"color:red\"><b>Meet Name:</b></font>&nbsp;Please enter the name of the tennis meet.</td></tr>";
   if(Utilities.getElement('month').selectedIndex==0 || Utilities.getElement('day').selectedIndex==0 || Utilities.getElement('year').selectedIndex==0 || Utilities.getElement('month2').selectedIndex==0 || Utilities.getElement('day2').selectedIndex==0 || Utilities.getElement('year2').selectedIndex==0)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Meet Date:</b></font>&nbsp;Please enter the <b>STARTING and ENDING date(s)</b> of the tennis meet. (If it is a one-day meet, enter the same date for the starting and ending date.)</td></tr>";
   if(Utilities.getElement('meetsite').selectedIndex==0 || (Utilities.getElement('meetsite').selectedIndex==1 && Utilities.getElement('othersite').value==''))
      errors+="<tr align=left><td><font style=\"color:red\"><b>Meet Host:</b></font>&nbsp;Please enter school hosting the tennis meet.</td></tr>";
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
<body onload="TEMeetResults.initialize('showresults','<?php echo $sport; ?>','0','<?php echo $sid; ?>','<?php echo $session; ?>','0');">
<?php
echo $header;

echo "<br><form name=\"meetform\" method=post action=\"addmeet_te_g.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"school_ch\" value=\"$school_ch\">";
echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
echo "<table class=nine><caption><b>Add a Girls Tennis Meet:</b><br><a class=small href=\"main_".$sport.".php?school_ch=$school_ch&session=$session\">".$sportname." Main Menu</a><br><div class=help style=\"width:500px\"><b>INSTRUCTIONS:</b> Enter the DATE(S) for the meet you wish to enter results for.  As you enter the date(s), any meets ALREADY IN THE SYSTEM within those dates will be shown below the dates you entered.  If the meet you are looking for is shown, click on that meet to enter results for your players.  If the meet is NOT found, continue by entering the Meet Name and Meet Site and clicking \"Continue\" at the bottom of this screen.</div><hr></caption>";
echo "<tr align=left><td><b>Meet Date(s):</b></td><td><select name=\"month\" id=\"month\"><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if($m==date("m")) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day\" id=\"day\"><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   echo ">$d</option>";
}
echo "</select>/&nbsp;".date("Y");
echo "<input type=hidden name=\"year\" id=\"year\" value=\"".date("Y")."\"> - ";
echo "<select name=\"month2\" id=\"month2\"><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";   if($m==date("m")) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day2\" id=\"day2\"><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   echo ">$d</option>";
}
echo "</select>/&nbsp;".date("Y");
echo "<input type=hidden name=\"year2\" id=\"year2\" value=\"".date("Y")."\"></td></tr>";
echo "<tr><td colspan=2><div id=\"showresults\" style=\"text-align:center;\"></div></td></tr>";
echo "<tr><td colspan=2><div id=\"errordiv\" class=\"searchresults\" style=\"left:30%;width:400px;display:none;\"></div></td></tr>";
echo "<tr align=left><td colspan=2><div id=\"newmeetdiv\" style=\"display:none;\">";
echo "<table cellspacing=2 cellpadding=2>";
echo "<tr align=left><td><b>Meet Name:</b></td><td><input type=text size=45 name=\"meetname\" id=\"meetname\"></td></tr>";
echo "<tr align=left><td><b>Meet Host:</b></td><td><table cellspacing=0 cellpadding=0><tr align=left><td>";
echo "<select id=\"meetsite\" name=\"meetsite\" onchange=\"if(this.options.selectedIndex==1) { Utilities.getElement('other').style.visibility='visible'; } else { Utilities.getElement('other').style.visibility='hidden'; }\"><option value=''>Select Host School</option><option value='other'>OTHER</option>";
$sql="SELECT * FROM ".$sport."school ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[school]\">$row[school]</option>";
}
echo "</select></td><td><div id=\"other\" style=\"visibility:hidden;\"><input type=text size=30 name=\"othersite\" id=\"othersite\"></div></td></tr></table></td></tr>";
echo "<tr align=center><td colspan=2><input type=button name=\"save\" value=\"Continue\" onclick=\"ErrorCheck();\"></td></tr>";
echo "</table></div></td></tr>";
echo "</table>";
echo "</form>";
echo "<div id=\"loading\" style=\"display:none\"></div>";
echo $end_html;
?>
