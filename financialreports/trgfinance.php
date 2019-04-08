<?php
require '../functions.php';
require_once('../variables.php');

if(!$database || $database=='') $database=$db_name;
$database2=ereg_replace("scores","officials",$database);

$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$level=GetLevel($session);
if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}
$header=GetHeader($session);
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

if(!$distid || $distid=='')
{
   echo $init_html;
   echo "<table width=100%><tr align=center><th><br><br>ERROR: No District/Subdistrict Selected!";
   if($level==1)
      echo "<br><br><a href=\"trindex.php?session=$session\">Return to Track & Field Financial Reports</a>";
   else
      echo "<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}

$sql="SELECT city_state FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split(",",$row[0]);
$hostcity=trim($temp[0]);

//get dist info from districts table
$sql="SELECT * FROM $database2.trgdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $district=$row[district];
$hostschool=$row[hostschool];
if($hostschool=="") $hostschool="[Host not Available]";
$hostid=$row[hostid];
if($level!=1 && $school!="Test's School")
{
   $sql2="SELECT school FROM logins WHERE id='$hostid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[school]!=$school)
   {
      echo $init_html;
      echo "<table><tr align=center><th><br><br>ERROR: You are NOT the host of $row[type] $class-$district.<br><br>";
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
      echo $end_html;
      exit();
   }
}
$dates="";
$day=split("/",$row[dates]);
for($i=0;$i<count($day);$i++)
{
   $date=split("-",$day[$i]);
   $dates.=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0])).", ";
}
$dates.=$date[0];
if($row[dates]=="") $dates="[Date not Available]";
$trsch=split(", ",$row[schools]);
sort($trsch);
$type=$row[type];
if($type=="Subdistrict") $round=1;
else if($type=="District") $round=2;
else
{
   echo $init_html;
   echo "<table><tr align=center><th><br><br>ERROR: $type $class-$district is NOT a Track & Field District.<br><br>";
   if($level==1)
      echo "<a href=\"trindex.php?session=$session\">Return to Track & Field Financial Reports</a>";
   else
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}

$sql="SELECT school FROM finance_tr WHERE distid='$distid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)   //already submitted
   $submitted=1;
else $submitted=0;

if($update || $submitted==1) //submit report AND/OR show submitted report
{
   $error="";
   if($update)//if report was submitted, add info to database & show printer-friendly version
   {
      //check that all required fields are completed
      if(($girlswinner=='' || $girlsrunnerup=='' || $boyswinner=='' || $boysrunnerup=='') && $school!="Test's School")
         $error.="You must select the winner and runner-up teams for boys and girls.<br>";
      if($grossreceipts=="0.00")
	 $error.="You must enter your Total Receipts (Gross Ticket Sales Plus Radio and TV Fees) in Box #1.<br>";
      if($error=="")
      {
      $sql="SELECT * FROM finance_tr WHERE distid='$distid'";
      $result=mysql_query($sql);
      $datesub=time();
      $girlswinner2=addslashes($girlswinner); $girlsrunnerup2=addslashes($girlsrunnerup);
      $boyswinner2=addslashes($boyswinner); $boysrunnerup2=addslashes($boysrunnerup);
      if(mysql_num_rows($result)==0)	//INSERT
         $sql2="INSERT INTO finance_tr (datesub,school,distid,round,girlswinner,girlsrunnerup,boyswinner,boysrunnerup,attendance,grossreceipts,offstarterfee,offstartermiles,offstartermilespaid,offfees,offmiles,offmilespaid,offtotal,insurance,balance,hostallow,nsaaallow) VALUES ('$datesub','$school2','$distid','$round','$girlswinner2','$girlsrunnerup2','$boyswinner2','$boysrunnerup2','$attendance','$grossreceipts','$offstarterfee','$offstartermiles','$offstartermilespaid','$offfees','$offmiles','$offmilespaid','$offtotal','$insurance','$balance','$hostallow','$nsaaallow')";
      else				//UPDATE
         $sql2="UPDATE finance_tr SET datesub='$datesub',round='$round',girlswinner='$girlswinner2',girlsrunnerup='$girlsrunnerup2',boyswinner='$boyswinner2',boysrunnerup='$boysrunnerup2',attendance='$attendance',grossreceipts='$grossreceipts',offstarterfee='$offstarterfee',offstartermiles='$offstartermiles',offstartermilespaid='$offstartermilespaid',offfees='$offfees',offmiles='$offmiles',offmilespaid='$offmilespaid',offtotal='$offtotal',insurance='$insurance',balance='$balance',hostallow='$hostallow',nsaaallow='$nsaaallow' WHERE distid='$distid'";
      $result2=mysql_query($sql2);
      }
   }//end if update

   if($error=='')
   {
   //get submitted info to display:
   $sql="SELECT * FROM finance_tr WHERE distid='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($print!=1)
   {
      if($update)
      {
         echo "<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"></head><body onload=\"window.open('trfinance.php?database=$database&session=$session&distid=$distid&print=1');\">";
      }
      else echo $init_html;
      echo $header;
      echo "<br>";
      if($level==1)
         echo "<a href=\"trindex.php?session=$session\" class=small>Return to Track & Field Financial Reports</a>&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"trfinance.php?session=$session&distid=$distid&print=1&school_ch=$school_ch&database=$database\" class=small target=new>Printer-Friendly Version</a><br><br>";
      echo "This financial report was completed on ".date("F j, Y",$row[datesub]).".  You may no longer make changes to this form.  Please contact the NSAA if you must make a change.  Thank you!<br><br>";
   }
   else
      echo $init_html."<table width=\"7in\"><tr align=center><td>";
   echo "<table width=700 class=nine><caption><b>NSAA Track & Field District Financial Report</b><hr></caption>";
   $today=date("M d, Y",time());
   echo "<tr align=left><td colspan=3><b>School: $space </b>$row[school]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Report Date: $space </b>".date("F j, Y",$row[datesub])."</td></tr>";
   $sql2="SELECT * FROM $database2.trgdistricts WHERE id='$distid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $dates="";
   $day=split("/",$row2[dates]);
   for($i=0;$i<count($day);$i++)
   {
      $cur=split("-",$day[$i]);
      $dates.=date("M j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
   }
   $dates.=$cur[0];
   echo "<tr align=left><td colspan=3><b>$row2[type] $row2[class]-$row2[district]</td></tr>";
   echo "<tr align=left><td colspan=3><b>At: $space </b>$row2[site]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Dates: $space </b>$dates</td></tr>";
   echo "<tr align=left><td colspan=3><b>Girls Winner:</b> $space $row[girlswinner] $space <b>Girls Runner-up:</b> $space $row[girlsrunnerup]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Boys Winner:</b> $space $row[boyswinner] $space <b>Boys Runner-up:</b> $space $row[boysrunnerup]</td></tr>";
   echo "<tr align=left><td><b>Attendance: $space </b>$row[attendance]</td><td colspan=2>&nbsp;</td></tr>";

   //#1
   echo "<tr align=right><td colspan=2><b>Total Receipts #1 $space $</b></td><td width=50 align=right>".number_format($row[grossreceipts],'2','.','')."</td></tr>";
   //#2
   echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
   echo "<tr align=left valign=center><td>a.&nbsp;Starter Fee&nbsp;</td>";
   echo "<td>$".number_format($row[offstarterfee],'2','.','')."</td></tr>";
   echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Starter Mileage (One Way):&nbsp;</td>";
   echo "<td>$row[offstartermiles] miles</td><td>&nbsp;x $".$offmileagerate."</td></tr></table></td>";
   echo "<td>$".number_format($row[offstartermilespaid],2,'.','')."</td></tr>";
   echo "<tr align=left valign=center><td>c.&nbsp;Referee Fee:&nbsp;</td>";
   echo "<td>$".number_format($row[offfees],'2','.','')."</td></tr>";
   echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>d.&nbsp;Referee Mileage (One Way):&nbsp;</td>";
   echo "<td>$row[offmiles] miles</td><td>&nbsp;x $1.00</td></tr></table></td>";
   echo "<td>$".number_format($row[offmilespaid],2,'.','')."</td></tr>";
   echo "</table></td></tr>";
   echo "<tr><td colspan=2 align=right><b>Officials' Total&nbsp;&nbsp;#2 $space $</b></td><td align=right>".number_format($row[offtotal],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
   echo "<td align=right><b>Insurance&nbsp;&nbsp;#3 $space $</b></td><td align=right>".number_format($row[insurance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Balance</b> (Total Receipts #1, minus lines #2 & #3)</td>";
   echo "<td align=right><b>Balance&nbsp;&nbsp;#4 $space $</b></td><td align=right>".number_format($row[balance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>NSAA</b> (25% of #4, Balance)</td>";
   echo "<td align=right><b>NSAA&nbsp;&nbsp;#5 $space $</b></td><td align=right>".number_format($row[nsaaallow],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Host School</b> (75% of #4, Balance)</td>";
   echO "<td align=right><b>Host School&nbsp;&nbsp;#6 $space $</b></td><td align=right>".number_format($row[hostallow],2,'.','')."</td></tr>";
   echo "<tr align=center><td><table width=450 border=1 bordercolor=\"red\" cellspacing=0 cellpadding=5><tr align=left><td class=nine>";
   $nsaacheck=number_format($row[insurance]+$row[nsaaallow],'2','.','');
   echo "<font style=\"color:red;\"><b>Write a check to NSAA for <font style=\"font-size:11pt\"><u>$".$nsaacheck."</u></font> and send a copy of this form with the check to the NSAA.<br></td></tr></table>";
   echo "</td><td colspan=2>&nbsp;</td></tr>";
   echo "<tr><td align=right colspan=3><br><br>";
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=5 class=nine width=220>";
   echo "<caption>NSAA USE ONLY</caption>";
   echo "<tr align=left><td><br>Date<u> ".$space."$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Check No. <u>$space $space $space $space $space $space $space $space</u><br><br>";
   echo "Track<br>602-18 <u>$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Insurance<br>642-30 <u>$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "</td></tr></table></td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
   }//end if no errors
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Finance2.js"></script>
</head>
<?php
echo $header;

?>
<body onload="Finance2.initialize('<?php echo $session; ?>','tr');">
<?php
echo "<br>";
if($level==1)
   echo "<a href=\"trindex.php?session=$session\" class=small>Financial Reports Home</a><br><br>";
echo "<form method=post action=\"trfinance.php\" name=trform>";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=database value=\"$database\">";
echo "<input type=hidden name=round value=$round>";
//echo "EXTRA: <div name=extra id=extra></div>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<table width=90% cellspacing=2 cellpadding=2 class=nine><caption><b>NSAA TRACK & FIELD DISTRICT FINANCIAL REPORT</b><hr>";
if($error!='')
{
   echo "<table class=nine><tr align=left><td><font style=\"color:red\">";
   echo "<b>You have the following errors in your form:<br><br></b>$error";
   echo "</td></tr></table>";
}
echO "</caption>";
//INSTRUCTIONS:
echo "<tr align=left><td colspan=3><i><b><u>INSTRUCTIONS:</u></b><br><table cellpadding=3><tr align=left bgcolor=yellow><td><i>Please complete all fields highlighted in yellow.  The calculations will be made as you enter the numbers.<br>Please fill out the form completely, from top to bottom.</i></td></tr></table>When you are finished completing this form, click \"Submit Report\".  You will then be taken to a printer-friendly version of the form.  Please PRINT a copy of the form and send it, along with a check for the amount due the NSAA, to the NSAA office immediately.  Print a second copy for your files.  After receiving approval of the report from the NSAA, send a copy of this form to each participating school with the amount due them.</i></td></tr>";
//DISTRICT, HOST, and DATE(S)
echo "<tr align=left><td colspan=3><b>District $class-$district at $hostschool.<br>";
echO "Date(s):&nbsp;&nbsp;$dates</b></td></tr>";
//WINNER/RUNNERUP/ATTENDANCE:
echo "<tr align=left valign=center><td colspan=3><table>";
echo "<tr align=left bgcolor=yellow><td><b>Girls Winner:</b>&nbsp;";
echo "<select name=\"girlswinner\" tabindex=1><option value=''>~</option>";
for($i=0;$i<count($trsch);$i++)
{
   echo "<option";
   if($girlswinner==$trsch[$i]) echo " selected";
   echo ">$trsch[$i]</option>";
}
echo "</select></td>";
echo "<td><b>Girls Runner-up:</b>&nbsp;";
echo "<select name=\"girlsrunnerup\" tabindex=2><option value=''>~</option>";
for($i=0;$i<count($trsch);$i++)
{
   echo "<option";
   if($girlsrunnerup==$trsch[$i]) echo " selected";
   echo ">$trsch[$i]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left bgcolor=yellow><td><b>Boys Winner:</b>&nbsp;";
echo "<select name=\"boyswinner\" tabindex=3><option value=''>~</option>";
for($i=0;$i<count($trsch);$i++)
{
   echo "<option";
   if($boyswinner==$trsch[$i]) echo " selected";
   echo ">$trsch[$i]</option>";
}
echo "</select></td>";
echo "<td><b>Boys Runner-up:</b>&nbsp;";
echo "<select name=\"boysrunnerup\" tabindex=4><option value=''>~</option>";
for($i=0;$i<count($trsch);$i++)
{
   echo "<option";
   if($boysrunnerup==$trsch[$i]) echo " selected";
   echo ">$trsch[$i]</option>";
}
echo "</select></td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td><table><tr align=left><td bgcolor=yellow><b>Attendance:</b>&nbsp;<input type=text class=tiny size=6 tabindex=5 name=\"attendance\" value=\"$attendance\"></td><td>&nbsp;</td></tr>";
echo "</table></td>";
//TOTAL RECEIPTS:
echo "<td align=right><b>Total Receipts&nbsp;&nbsp;#1</b><br>";
echo "<font style=\"font-size:8pt;\">(Gross Ticket Sales Plus Radio and TV Fees)</font></td>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=6 name=\"grossreceipts\" id=\"grossreceipts\" value=\"".number_format($grossreceipts,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr>";
echo "<tr align=left><td colspan=3>Expenses are to be paid in full in order listed, using funds available.</td></tr>";
//OFFICIALS:
echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
echo "<tr align=left valign=center><td>a.&nbsp;Starter Fee&nbsp;<br>$space<font style=\"font-size:8pt\">(Maximum-$200 per starter)</font></td>";
echo "<td bgcolor=yellow>$<input type=text tabindex=7 class=tiny size=6 name=\"offstarterfee\" id=\"offstarterfee\" value=\"".number_format($offstarterfee,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr>";
if(!$offstartermiles) $offstartermiles="0";
echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Starter Mileage (One Way):&nbsp;</td><td bgcolor=yellow><input type=text tabindex=8 class=tiny size=3 name=\"offstartermiles\" id=\"offstartermiles\" value=\"$offstartermiles\" onblur=\"Finance2.Calculate(this.id,this.value);\"> miles</td><td>&nbsp;x $1.00</td></tr></table></td>";
echo "<td>$<input type=text class=tiny size=6 readOnly=true name=\"offstartermilespaid\" id=\"offstartermilespaid\" value=\"".number_format($offstartermilespaid,2,'.','')."\"></td></tr>";
echo "<tr align=left valign=center><td>c.&nbsp;Referee Fee&nbsp;<br>$space";
echo "<font style=\"font-size:8pt\">($100 Maximum if not NSAA-registered; $200 Maximum if NSAA-registered.)</font></td>";
echo "<td bgcolor=yellow>$<input tabindex=9 type=text class=tiny size=6 name=\"offfees\" id=\"offfees\" value=\"".number_format($offfees,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr>";
if(!$offmiles) $offmiles="0";
echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>d.&nbsp;Referee Mileage (One Way):&nbsp;</td><td bgcolor=yellow><input tabindex=10 type=text class=tiny size=3 name=\"offmiles\" id=\"offmiles\" value=\"$offmiles\" onblur=\"Finance2.Calculate(this.id,this.value);\"> miles</td><td>&nbsp;x $1.00</td></tr></table></td>";
echo "<td>$<input type=text class=tiny size=6 readOnly=true name=\"offmilespaid\" id=\"offmilespaid\" value=\"".number_format($offmilespaid,2,'.','')."\"></td></tr>";
echo "</table></td></tr>";
echo "<tr><td colspan=2 align=right><b>Officials' Total&nbsp;&nbsp;#2</b></td><td align=center>$<input type=text class=tiny size=7 name=\"offtotal\" id=\"offtotal\" value=\"".number_format($offtotal,2,'.','')."\" readOnly=TRUE></td></tr>";
echO "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
echo "<td align=right><b>Insurance&nbsp;&nbsp;#3</b></td><td align=center>$<input type=text class=tiny size=7 name=\"insurance\" id=\"insurance\" value=\"".number_format($insurance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Balance</b> (Total Receipts #1, minus lines #2 & #3)</td>";
echo "<td align=right><b>Balance&nbsp;&nbsp;#4</b></td><td align=center>$<input type=text class=tiny size=7 name=\"balance\" id=\"balance\" value=\"".number_format($balance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>NSAA</b> (25% of #4, Balance)</td>";
echo "<td align=right><b>NSAA&nbsp;&nbsp;#5</b></td><td align=center>$<input type=text class=tiny size=7 name=\"nsaaallow\" id=\"nsaaallow\" value=\"".number_format($nsaaallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Host School</b> (75% of #4, Balance)</td>";
echO "<td align=right><b>Host School&nbsp;&nbsp;#6</b></td>";
echo "<td align=center>$<input type=text class=tiny size=7 name=\"hostallow\" id=\"hostallow\" value=\"".number_format($hostallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr align=center><td colspan=3>";
echo "<table width=500><tr align=left><td><font style=\"color:blue\">Please double-check that the information above is COMPLETE and ACCURATE before clicking \"Submit Report\".  You will NOT be able to make changes once your report is submitted.<br><br>When you click \"Submit Report\", you will be taken to the printer-friendly version of your completed form.  Please print out the form and send copies of it with your checks to the NSAA and the visiting schools.</font></td></tr></table>";
echo "<input type=submit name=update value=\"Submit Report\" onclick=\"return confirm('Are you sure you want to submit this report?  You will not be able to make changes to your report after submitting it.');\"></td></tr>";
echo "</table>";
echo "</form>";
?>
<div id="loading" style="display:none"></div>
<?php
echo $end_html;
?>
