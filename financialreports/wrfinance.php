<?php
require '../functions.php';
require_once('../variables.php');
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}
if(!$distid || $distid=='')
{
   echo $init_html;
   echo "<table width=100%><tr align=center><th><br><br>ERROR: No District/Subdistrict Selected!<br><br>";
   if($level==1)
      echo "<a href=\"wrindex.php?session=$session\">Return to Financial Reports</a>";
   else
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}

$level=GetLevel($session);
//get school user chose (Level 1) or belongs to (Level 2, 3)
if($level!=1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
   if(!$school_ch || $school_ch=='')
   {
      $sql="SELECT school FROM logins AS t1, $db_name2.wrdistricts AS t2 WHERE t1.id=t2.hostid AND t2.id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $school=$row[school]; $school_ch=$row[school];
   }
}
$school2=addslashes($school);

$sql="SELECT id,city_state FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split(",",$row[0]);
$hostcity=trim($temp[0]);

//get dist info from districts table
$sql="SELECT * FROM $db_name2.wrdistricts WHERE id='$distid'";
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
$type=$row[type];
$wrsch=split(", ",$row[schools]);
sort($wrsch);
if($type=="Subdistrict") $round=1;
else if($type=="District") $round=2;
else
{
   echo $init_html;
   echo "<table><tr align=center><th><br><br>ERROR: $type $class-$district is NOT a Wrestling District.<br><br>";
   if($level==1)
      echo "<a href=\"wrindex.php?session=$session\">Return to Financial Reports</a>";
   else
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}

$sql="SELECT * FROM finance_wr WHERE distid='$distid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)   //already submitted
   $submitted=1;
else $submitted=0;

if($update || $hiddenupdate || $submitted==1) //submit report AND/OR show submitted report
{
   if($update || $hiddenupdate) //if report was submitted, add info to database & show printer-friendly version
   {
      $sql="SELECT * FROM finance_wr WHERE distid='$distid'";
      $result=mysql_query($sql);
      $datesub=time();
      if(mysql_num_rows($result)==0)	//INSERT, THEN CAN UPDATE
      {
         $sql2="INSERT INTO finance_wr (datesub,school,distid) VALUES ('$datesub','$school2','$distid')";
         $result2=mysql_query($sql2);
      }
      $sql2="UPDATE finance_wr SET nocasts='$nocasts',localmedia_bcasts='$localmedia_bcasts',localmedia_bfee='$localmedia_bfee', othermedia_wcasts='$othermedia_wcasts', othermedia_wfee='$othermedia_wfee', othermedia_tcasts='$othermedia_tcasts', othermedia_tfee='$othermedia_tfee', round='$round',attendance='$attendance',grossreceipts='$grossreceipts',offfees='$offfees',offmiles='$offmiles',offmilespaid='$offmilespaid',offexpenses='$offexpenses',offtotal='$offtotal',insurance='$insurance',subbalance='$subbalance',hostallow='$hostallow',nsaaallow='$nsaaallow',balance='$balance',vismileagepaid='$vismileage',prorate='$prorate',bonus='$bonus',hostbonus='$hostbonus',visbonus='$visbonus',hosttotal='$hostsum',vistotal='$vissum',gate='$gate',b_cast='$b_cast' WHERE distid='$distid'";
      $result2=mysql_query($sql2);
      //visiting schools:
      $sql2="DELETE FROM finance_wr_exp WHERE distid='$distid'";
      $result2=mysql_query($sql2);
      for($i=0;$i<count($wrschool);$i++)
      {
         $var1="row_".$i."_1";
         $var2="row_".$i."_2";
         $var3="row_".$i."_3";
         $var4="row_".$i."_4";
         $var5="row_".$i."_5";
         $var6="row_".$i."_6";
         $var7="row_".$i."_7";
         $var8="row_".$i."_8";
  	 $cursch2=addslashes($wrschool[$i]);
	 $sql="INSERT INTO finance_wr_exp (distid,school,wrestlers,rate,trips,miles,mileagedue,mileagepaid,bonus,totalpaid) VALUES ('$distid','$cursch2','".$$var1."','".$$var2."','".$$var3."','".$$var4."','".$$var5."','".$$var6."','".$$var7."','".$$var8."')"; 
	 $result=mysql_query($sql);
      }
   }//end if update
   //get submitted info to display:
   $sql="SELECT * FROM finance_wr WHERE distid='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($print!=1)
   {
      if($update || $hiddenupdate)
      {
         echo "<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"></head><body onload=\"window.open('wrfinance.php?session=$session&distid=$distid&print=1');\">";
      }
      else echo $init_html;
      echo GetHeader($session);
      echo "<br>";
      if($level==1)
         echo "<a href=\"wrindex.php?session=$session\" class=small>Return to Financial Reports</a>&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"wrfinance.php?session=$session&distid=$distid&print=1&school_ch=$school_ch\" class=small target=new>Printer-Friendly Version</a><br><br>";
      echo "This financial report was completed on ".date("F j, Y",$row[datesub]).".  You may no longer make changes to this form.  Please contact the NSAA if you must make a change.  Thank you!<br><br>";
   }
   else
      echo $init_html."<table width=100%><tr align=center><td>";
   echo "<table width=700 class=nine><caption><b>NSAA Wrestling Financial Report</b><hr></caption>";
   $today=date("M d, Y",time());
   echo "<tr align=left><td colspan=3><b>School: $space </b>$row[school]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Report Date: $space </b>".date("F j, Y",$row[datesub])."</td></tr>";
   $sql2="SELECT * FROM $db_name2.wrdistricts WHERE id='$distid'";
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
   echo "<tr align=left><td colspan=3><b>Attendance: $space </b>$row[attendance]</td></tr>";

   //#1
   echo "<tr align=right><td colspan=2><b>1. Gate Receipts  $space $</b></td><td width=50 align=right>".number_format($row[gate],'2','.','')."</td></tr>";
   echo "<tr align=right><td colspan=2><b>2. Broadcast Receipts  $space $</b></td><td width=50 align=right>".number_format($row[b_cast],'2','.','')."</td></tr>";
   echo "<tr align=right><td colspan=2><b>3. Total Receipts  $space $</b></td><td width=50 align=right>".number_format($row[grossreceipts],'2','.','')."</td></tr>";
   //#2
   echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
   echo "<tr align=left valign=center><td>a.&nbsp;Fees&nbsp;<span style=\"color:red;font-size:90%;font-style: italic;\">($190 Class A 1 day,$275 Class B/C/D 2 days)</span></td>";
   echo "<td>$".number_format($row[offfees],'2','.','')."</td></tr>";
   echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Total Mileage One Way for All Officials:&nbsp;</td>";
   echo "<td>$row[offmiles] miles</td><td>&nbsp;x $".$offmileagerate."</td></tr></table></td>";
   echo "<td>$".number_format($row[offmilespaid],2,'.','')."</td></tr>";
   echo "<tr align=left><td>c.&nbsp;Expenses ($60 per day if lodging required, maximum one day)</td>";
   echo "<td>$".number_format($row[offexpenses],2,'.','')."</td></tr></table></td></tr>";
   echo "<tr><td colspan=2 align=right><b>4. Officials' Total&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[offtotal],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
   echo "<td align=right><b>5. Insurance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[insurance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Sub-Balance</b> (Total Receipts #1, minus lines #2 & #3)</td>";
   echo "<td align=right><b>6. Sub-Balance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[subbalance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>NSAA</b> (25% of #4, Sub-Balance)</td>";
   echo "<td align=right><b>7. NSAA&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[nsaaallow],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Host School</b> (35% of #4, Sub-Balance)</td>";
   echO "<td align=right><b>8. Host School&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[hostallow],2,'.','')."</td></tr>";
   echO "<tr><td align=left><b>Balance</b> (Sub-Balance #4, minus lines #5 & #6)</td>";
   echo "<td align=right><b>9. Balance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[balance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Mileage Paid to Visiting Schools</b> (Total of Column B below)</td>";
   echo "<td align=right><b>10. Mileage&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[vismileagepaid],2,'.','')."</td></tr>";
   echo "<tr align=center><td colspan=3>";
   echo "<table cellspacing=0 cellpadding=4 border=1 bordercolor=#000000>";
   echo "<tr align=left><td colspan=9 align=left><b>Mileage Paid to Competing Schools:</b></td></tr>";
   echo "<tr align=center><th class=smaller rowspan=2><b>School</b></td><th class=small rowspan=2># of<br>Wrestlers<br>Plus One<br>Coach</th><th class=smaller colspan=4>Actual Team Mileage by Schedule<br><font style=\"font-size:7pt\">(See Wrestling Manual Page 18)</font><br><b>(A)</b></th><th class=smaller rowspan=2><b>Mileage Paid<br>100% or<br>Prorated<br>(B)</b></th><th class=smaller rowspan=2><b>Bonus<br>(C)</b></th><th class=smaller rowspan=2><b>Total Amount<br>Paid to Teams<br>(D)</b></th></tr>";
   echo "<tr align=center><th class=small>Rate</th><th class=small># Trips</th><th class=small>Miles<br>1 Way</th><th class=small>Mileage Due</th></tr>";
   $sql2="SELECT * FROM finance_wr_exp WHERE distid='$distid' ORDER BY id";
   $result2=mysql_query($sql2);
   $wrestlers=0; $trips=0; $miles=0; $mileagedue=0; $mileagepaid=0; $bonus=0; $totalpaid=0;
   while($row2=mysql_fetch_array($result2))
   {
     echo "<tr align=center><td align=left>$row2[school]</td>";
     echo "<td>$row2[wrestlers]</td>";
	$wrestlers+=$row2[wrestlers];
     echo "<td>".number_format($row2[rate],'2','.','')."</td>";
     echo "<td>$row2[trips]</td>";
	$trips+=$row2[trips];
     echo "<td>$row2[miles]</td>";
	$miles+=$row2[miles];
     echo "<td>".number_format($row2[mileagedue],'2','.','')."</td>";
	$mileagedue+=$row2[mileagedue];
     echo "<td>".number_format($row2[mileagepaid],'2','.','')."</td>";
	$mileagepaid+=$row2[mileagepaid];
     echo "<td>".number_format($row2[bonus],'2','.','')."</td>";
	$bonus+=$row2[bonus];
     echo "<td>".number_format($row2[totalpaid],'2','.','')."</td>";
	$totalpaid+=$row2[totalpaid];
     echo "</tr>";
   } 
   echo "<tr align=center><td align=right><b>Totals</b></td>";
   echo "<td>$wrestlers</td>";
   echo "<td>&nbsp;</td>";
   echo "<td>$trips</td>";
   echo "<td>$miles</td>";
   $mileagedue=number_format($mileagedue,'2','.','');
   echo "<td>$mileagedue</td>";
   $mileagepaid=number_format($mileagepaid,'2','.','');
   echo "<td>$mileagepaid</td>";
   $bonus=number_format($bonus,'2','.','');
   echo "<td>$bonus</td>";
   $totalpaid=number_format($totalpaid,'2','.','');
   echo "<td>$totalpaid</td>";
   echo "</tr>";
   echo "</table></td></tr>";
   echo "<tr align=left valign=top><td><b>Balance for Bonus</b> (#5 minus #6) To be distributed as specified below.<br>";
   echo "<table><tr align=left><td>Host School, 25% of #7</td><td>$".number_format($row[hostbonus],2,'.','')."</td></tr>";
   echo "<tr align=left><td>Schools, 75% of #7</td><td>$".number_format($row[visbonus],2,'.','')."</td></tr>";
   echo "<tr align=left><td colspan=2>(To be divided equally among <b>visiting</b> schools.)</td></tr>";
   echo "</table>";
   echo "</td><td align=right><b>Bonus&nbsp;&nbsp;#7 $space $</b></td><td align=right>".number_format($row[bonus],2,'.','')."</td></tr>";
	//SUMMARY
   echo "<tr><td colspan=2>&nbsp;";
   echo "</td><td align=center><b>SUMMARY</b></td></tr>";
   echo "<tr valign=center><td rowspan=6 align=right>&nbsp;";
   echo "<table width=450 border=1 bordercolor=\"red\" cellspacing=0 cellpadding=5><tr align=left><td class=nine>";
   $nsaacheck=number_format($row[insurance]+$row[nsaaallow],'2','.','');
   echo "<font style=\"color:red;\"><b>Write a check to NSAA for <font style=\"font-size:11pt\"><u>$".$nsaacheck."</u></font> and send a copy of this form with the check to the NSAA.<br>";
   echo "<br>Write a check to each school for the amount shown in Column D and send a copy of this form with the check to each school.</font></b></td></tr></table>";
   echo "</td>";
   echo "<td align=right>Officials $space $</td><td align=right width=50>".number_format($row[offtotal],2,'.','')."</td></tr>";
   echo "<tr><td align=right>Host $space $</td><td align=right>".number_format($row[hosttotal],2,'.','')."</td></tr>";
   echo "<tr><td align=right>Schools' Total $space $</td><td align=right>".number_format($row[vistotal],2,'.','')."</td></tr>";
   echo "<tr><td align=right>NSAA $space $</td><td align=right>".number_format($row[nsaaallow],2,'.','')."</td></tr>";
   echo "<tr><td align=right>Insurance $space $</td><td align=right>".number_format($row[insurance],2,'.','')."</td></tr>";
   echo "<tr><td align=right>Total $space $</td><td align=right>".number_format($row[grossreceipts],2,'.','')."</td></tr>";

   //LIVE VIDEO BROADCASTS
   echo "<tr valign=center><td rowspan=8 align=right>&nbsp;";
/*    echo "<table width=450 border=1 bordercolor=\"red\" cellspacing=0 cellpadding=5><tr align=left><td class=nine>";
   echo "<p style=\"color:red;\"><b><u>LIVE VIDEO BROADCAST FEES:</b></u></p>
        <p style=\"color:red\"><b>Local Media & Unaffiliated Broadcasts: </b>";
   if($row[localmedia_bfee]>0)
   {
      echo "&nbsp;<font style=\"font-size:11pt;\">$".$row[localmedia_bfee]."</font></p><p style=\"color:red\"><i>Local Media & Unaffiliated Student Broadcast Fees should be invoiced by the host school and receipts payable to the host school.</i></p>";
   }
   else echo "&nbsp;<font style=\"font-size:11pt;\">N/A</font></p>";
   echo "<p style=\"color:red\"><b>Competitor/Other Media & Affiliated Student Broadcasts: </b>";
   $wtfee=$row[othermedia_wfee]+$row[othermedia_tfee];
   if($wtfee>0)
   {
      echo "&nbsp;<font style=\"font-size:11pt;\">$".$wtfee."</font></p><p style=\"color:red\"><i>Comptitor/Other Media & Affiliated Student Broadcast Fees will be invoiced by the NSAA and receipts payable to the NSAA.</i></p>";
   }
   else echo "&nbsp;<font style=\"font-size:11pt;\">N/A</font></p>";
   echo "</td></tr></table>"; */
   echo "</td>";
   echo "<td align=right colspan=2><br /><b>LIVE VIDEO BROADCASTS</b></td></tr>";
   //echo "<tr><td align=left colspan=2>$space <b>Local Media/Unaffiliated</b></td></tr>";
   echo "<tr><td align=left colspan=2>$space <b>Student Groups</b></td></tr>";
   echo "<tr><td align=right>$row[localmedia_bcasts] x $100 = $</td><td align=right>".number_format($row[localmedia_bfee],2,'.','')."</td></tr>";
   //echo "<td align=left colspan=2>$space <b>Other Media/Affiliated</b></td></tr>";
   echo "<td align=left colspan=2>$space <b>Local Media Groups</b></td></tr>";
   echo "<td align=left colspan=2>$space Broadcasts:</td></tr>";
   echo "<tr><td align=right>$row[othermedia_wcasts] x $150 = $</td><td align=right>".number_format($row[othermedia_wfee],2,'.','')."</td></tr>";
   echo "<td align=left colspan=2>$space Telecasts (TV):</td></tr>";
   echo "<tr><td align=right>$row[othermedia_tcasts] x $250 = $</td><td align=right>".number_format($row[othermedia_tfee],2,'.','')."</td></tr>";
   echo "</td></tr>";

   echo "<tr><td colspan=3 align=right><br><br>";
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=5 class=nine>";
   echo "<caption>NSAA USE ONLY</caption>";
   echo "<tr align=right><td><br>Date <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Check No. <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Total <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "WR 602-20 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "INS.642-30 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "LIVE VIDEO.692-50 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "</td></tr></table></td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Finance.js"></script>
<script language="javascript">
function CalculateBcasts()
{
   var bcasts=Utilities.getElement('localmedia_bcasts').value;
   //var bfee=bcasts * 150;
   var bfee=bcasts * 100;
   Utilities.getElement('localmedia_bfee').value=bfee;

   var wcasts=Utilities.getElement('othermedia_wcasts').value;
   var wfee=wcasts * 150;
   Utilities.getElement('othermedia_wfee').value=wfee;

   var tcasts=Utilities.getElement('othermedia_tcasts').value;
   //var tfee=tcasts * 1500;
   var tfee=tcasts * 250;
   Utilities.getElement('othermedia_tfee').value=tfee;

   if(bcasts>0 || wcasts>0 || tcasts>0)
      Utilities.getElement('nocasts').checked=false;
}
function ErrorCheckBcasts()
{
   var bcasts=Utilities.getElement('localmedia_bcasts').value;
   var wcasts=Utilities.getElement('othermedia_wcasts').value;
   var tcasts=Utilities.getElement('othermedia_tcasts').value;

   if((Utilities.getElement('livebcastsYES').checked && bcasts==0 && wcasts==0 && tcasts==0) || (!Utilities.getElement('livebcastsYES').checked && !Utilities.getElement('livebcastsNO').checked))
      return false;
   else return true;
}
</script>
</head>
<?php
echo GetHeader($session);

?>
<body onload="Finance.initialize('<?php echo $session; ?>','wr');">
<?php
echo "<br><a href=\"index.php?session=$session\" class=small>Financial Reports Home</a><br><br>";
echo "<form method=post action=\"wrfinance.php\" name=wrform>";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=round value=$round>";
//echo "EXTRA: <div name=extra id=extra></div>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<table style=\"max-width:900px;\" cellspacing=2 cellpadding=2 class=nine><caption><b>NSAA DISTRICT WRESTLING TOURNAMENT FINANCIAL REPORT</b><hr>";
echO "</caption>";
echo "<tr align=left><td colspan=3><b>District $class-$district at $hostschool.<br>";
echO "Date(s):&nbsp;&nbsp;$dates</b></td></tr>";
echo "<tr align=left><td colspan=3><i><b><u>INSTRUCTIONS:</u></b><br><table cellpadding=3><tr align=left bgcolor=yellow><td><i>Please complete all fields highlighted in yellow.  The calculations will be made as you enter the numbers.<br>Please fill out the form completely, from top to bottom.</i></td></tr></table>When you are finished completing this form, click \"Submit Report\".  You will then be taken to a printer-friendly version of the form.  Please PRINT a copy of the form and send it, along with a check for the amount due the NSAA, to the NSAA office immediately.  Print a second copy for your files.  After receiving approval of the report from the NSAA, send a copy of this form to each participating school with the amount due them.</i></td></tr>";
echo "<tr><td></td><td align=right><b>1. Gate Receipts&nbsp;&nbsp;</b><br>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=1 name=\"gate\" id=\"gate\" value=\"".number_format($gate,2,'.','')."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td></tr>";

echo "<tr><td></td><td align=right><b>2. Broadcast Receipts&nbsp;&nbsp;</b><br>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=2 name=\"b_cast\" id=\"b_cast\" value=\"".number_format($b_cast,2,'.','')."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td></tr>";

echo "<tr align=left valign=center><td><table><tr align=left bgcolor=yellow><td><b>Attendance:</b>&nbsp;<input tabindex=\"3\" type=text class=tiny size=6 name=\"attendance\" value=\"$attendance\"></td></tr></table></td>";
echo "<td align=right><b>3. Total Receipts&nbsp;&nbsp;</b><br>";
echo "<font style=\"font-size:8pt;\">(Gross Ticket Sales Plus Radio and TV Fees)</font></td>"; 
echo "<td width=100  align=center>$<input tabindex=\"400\" type=text class=tiny size=7 name=\"grossreceipts\" id=\"grossreceipts\" value=\"".number_format($grossreceipts,2,'.','')."\" onblur=\"Finance.Calculate(this.id,this.value);\" readOnly=TRUE></td></tr>";
echo "<tr align=left><td colspan=3>Expenses are to be paid in full in order listed, using funds available.</td></tr>";
echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
echo "<tr align=left valign=center><td>a.&nbsp;Fees&nbsp;<span style=\"color:red;font-size:90%;font-style: italic;\">($190 Class A 1 day,$275 Class B/C/D 2 days)</span></td>";
echo "<td bgcolor=yellow>$<input tabindex=\"5\" type=text class=tiny size=6 name=\"offfees\" id=\"offfees\" value=\"".number_format($offfees,2,'.','')."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td></tr>";
echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Total Mileage One Way for All Officials:&nbsp;</td><td bgcolor=yellow><input tabindex=\"6\" type=text class=tiny size=3 name=\"offmiles\" id=\"offmiles\" value=\"$offmiles\" onblur=\"Finance.Calculate(this.id,this.value);\"> miles</td><td>&nbsp;x $1.00</td></tr></table></td>";
echo "<td>$<input type=text class=tiny size=6 name=\"offmilespaid\" id=\"offmilespaid\" value=\"".number_format($offmilespaid,2,'.','')."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td></tr>";
echo "<tr align=left><td>c.&nbsp;Expenses ($60 per day if lodging required, maximum one day)</td>";
echo "<td bgcolor=yellow>$<input tabindex=\"7\" type=text class=tiny size=6 name=\"offexpenses\" id=\"offexpenses\" value=\"".number_format($offexpenses,2,'.','')."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td></tr></table></td></tr>";
echo "<tr><td colspan=2 align=right><b>4. Officials' Total&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"offtotal\" id=\"offtotal\" value=\"".number_format($offtotal,2,'.','')."\" readOnly=TRUE></td></tr>";
echO "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
echo "<td align=right><b>5. Insurance&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"insurance\" id=\"insurance\" value=\"".number_format($insurance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Sub-Balance</b> (Total Receipts #1, minus lines #2 & #3)</td>";
echo "<td align=right><b>6. Sub-Balance&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"subbalance\" id=\"subbalance\" value=\"".number_format($subbalance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>NSAA</b> (25% of #4, Sub-Balance)</td>";
echo "<td align=right><b>7. NSAA&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"nsaaallow\" id=\"nsaaallow\" value=\"".number_format($nsaaallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Host School</b> (35% of #4, Sub-Balance)</td>";
echO "<td align=right><b>8. Host School&nbsp;&nbsp;</b></td>";
echo "<td align=center>$<input type=text class=tiny size=7 name=\"hostallow\" id=\"hostallow\" value=\"".number_format($hostallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echO "<tr><td align=left><b>Balance</b> (Sub-Balance #4, minus lines #5 & #6)</td>";
echo "<td align=right><b>9. Balance&nbsp;&nbsp;</b></td>";
echo "<td align=center>$<input type=text class=tiny size=7 name=\"balance\" id=\"balance\" value=\"".number_format($balance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Mileage Paid to Visiting Schools</b> (Total of Column B below)</td>";
echo "<td align=right><b>10. Mileage&nbsp;&nbsp;</b></td>";
echo "<td align=center>$<input type=text class=tiny size=7 name=\"vismileage\" id=\"vismileage\" value=\"".number_format($vismileage,2,'.','')."\"></td></tr>";
echo "<tr align=center><td colspan=3>";
echo "<table cellspacing=0 cellpadding=4 border=1 bordercolor=#000000>";
echo "<tr align=left><th class=smaller colspan=9 align=left>Mileage Paid to Competing Schools:<br>";
/*
echo "<form target=\"_blank\" name=\"frmGetMileage\" method=\"post\">";
echo "<table cellpadding=3 cellspacing=0><tr align=left valign=center bgcolor=\"#E0E0E0\">";
echo "<td>Calculate Mileage:<br>(<a class=tiny target=\"_blank\" href=\"http://randmcnally.com\">randmcnally.com</a>)</td>";
echo "<td>Starting City (do NOT include State)<br>";
echo "<input type=text name=\"txtStartCity\" maxlength=\"100\" size=25></td>";
echo "<td>Destination City (do NOT include State)<br>";
if($school=="Test's School") $hostcity="Lincoln";
echo "<input type=text name=\"txtDestCity\" value=\"$hostcity\" maxlength=100 size=25></td>";
echo "<td><input type=button onclick=\"window.open('http://www.randmcnally.com/rmc/directions/dirGetMileage.jsp?txtStartCity='+ txtStartCity.value +'&txtStartState=NE&txtDestCity='+ txtDestCity.value +'&txtDestState=NE','RandMcNally','width=800,height=600');\" value=\"Get Mileage\"></td></tr></table>";
echo "<input type=hidden name=\"txtStartState\" value=\"NE\">";
echo "<input type=hidden name=\"txtDestState\" value=\"NE\"></form>";
*/
if($school=="Test's School") $hostcity="Lincoln";
echo "<a target=\"_blank\" href=\"http://www.randmcnally.com/rmc/directions/dirGetMileage.jsp?txtStartState=NE&txtDestCity=$hostcity&txtDestState=NE\">Calculate Mileage (RandMcNally.com)</a>";
echo "</th></tr>";
echo "<tr align=center><th class=smaller rowspan=2><b>School</b></th><th class=small rowspan=2># of<br>Wrestlers<br>Plus One<br>Coach</th><th class=smaller colspan=4>Actual Team Mileage by Schedule<br><font style=\"font-size:7pt\">(See Wrestling Manual Page 18)</font><br><b>(A)</b></th><th class=smaller rowspan=2><b>Mileage Paid<br>100% or<br>Prorated<br>(B)</b></th><th class=smaller rowspan=2><b>Bonus<br>(C)</b></th><th class=smaller rowspan=2><b>Total Amount<br>Paid to Teams<br>(D)</b></th></tr>";
echo "<tr align=center><th class=small>Rate</th><th class=small># Trips</th><th class=small>Miles<br>1 Way</th><th class=small>Mileage Due</th></tr>";
switch($class)
{
   case "A":
      $max=8;
      break;
   case "B":
      $max=12;
      break;
   case "C":
      $max=16;
      break;
   default:
      $max=20;
}
//get WR schools
/*
$sql="SELECT * FROM wrschool WHERE outofstate!='1' ORDER BY school";
$result=mysql_query($sql);
$ix=0; $wrsch=array();
while($row=mysql_fetch_array($result))
{
   $wrsch[$ix]=$row[school];
   $ix++;
} 
*/
$tab=5;
for($i=0;$i<$max;$i++)
{
   $tab++;
   echo "<tr align=center><td bgcolor=yellow><select tabindex=\"$tab\" name=\"wrschool[$i]\"><option value=''>~</option>";
   for($j=0;$j<count($wrsch);$j++)
   {
      if($wrsch[$j]!=$school)
      {
         echo "<option";
         if($wrschool[$i]==$wrsch[$j]) echo " selected";
         echo ">$wrsch[$j]</option>";
      }
   }
   echo "</select></td>";
   $var="row_".$i."_1";
   if(!$$var) $$var=0;
   $tab++;
   echo "<td bgcolor=yellow><input tabindex=\"$tab\" type=text class=tiny size=3 name=\"$var\" id=\"$var\" value=\"".$$var."\" onblur=\"if(this.value>15) { this.value='0';alert('You cannot enter more than 15 in the field for number of wrestlers plus one coach!'); } Finance.Calculate(this.id,this.value);\"></td>";
   $var="row_".$i."_2";
   if(!$$var) $$var="0.00";
   echo "<td><input type=text class=tiny size=4 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_3";
   if(!$$var) $$var="";
   $tab++;
   echo "<td bgcolor=yellow><input tabindex=\"$tab\" type=text class=tiny size=2 name=\"$var\" id=\"$var\" value=\"".$$var."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td>";
   $var="row_".$i."_4";
   if(!$$var) $$var="";
   $tab++;
   echo "<td bgcolor=yellow><input type=text tabindex=\"$tab\" class=tiny size=3 name=\"$var\" id=\"$var\" value=\"".$$var."\" onblur=\"Finance.Calculate(this.id,this.value);\"></td>";
   $var="row_".$i."_5";
   if(!$$var) $$var="0.00";
   echo "<td>$<input type=text class=tiny size=6 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_6";
   if(!$$var) $$var="0.00";
   echo "<td>$<input type=text class=tiny size=6 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_7";
   if(!$$var) $$var="0.00";
   echo "<td>$<input type=text class=tiny size=6 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_8";
   if(!$$var) $$var="0.00";
   echo "<td>$<input type=text class=tiny size=6 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   echo "</tr>";
}
echo "<tr align=center><td align=right><b>Totals</b></td>";
if(!$col_1_total) $col_1_total=0;
echo "<td><input type=text class=tiny size=3 name=\"col_1_total\" id=\"col_1_total\" value=\"$col_1_total\" readOnly=TRUE></td>";
echo "<td>&nbsp;</td>";
if(!$col_3_total) $col_3_total="";
echo "<td><input type=text class=tiny size=2 name=\"col_3_total\" id=\"col_3_total\" value=\"$col_3_total\" readOnly=TRUE></td>";
if(!$col_4_total) $col_4_total="";
echo "<td><input type=text class=tiny size=4 name=\"col_4_total\" id=\"col_4_total\" value=\"$col_4_total\" readOnly=TRUE></td>";
if(!$col_5_total) $col_5_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_5_total\" id=\"col_5_total\" value=\"$col_5_total\" readOnly=TRUE></td>";
if(!$col_6_total) $col_6_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_6_total\" id=\"col_6_total\" value=\"$col_6_total\" readOnly=TRUE></td>";
if(!$col_7_total) $col_7_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_7_total\" id=\"col_7_total\" value=\"$col_7_total\" readOnly=TRUE></td>";
if(!$col_8_total) $col_8_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_8_total\" id=\"col_8_total\" value=\"$col_8_total\" readOnly=TRUE></td>";
echo "</tr>";
echo "</table></td></tr>";
echo "<tr align=left valign=top><td><b>Balance for Bonus</b> (#5 minus #6) To be distributed as specified below.<br>";
if(!$hostbonus) $hostbonus="0.00";
echo "<table><tr align=left><td>Host School, 25% of #7</td><td>$<input type=text class=tiny size=6 name=\"hostbonus\" id=\"hostbonus\" value=\"$hostbonus\"></td></tr>";
if(!$visbonus) $visbonus="0.00";
echo "<tr align=left><td>Schools, 75% of #7</td><td>$<input type=text class=tiny size=6 name=\"visbonus\" id=\"visbonus\" value=\"$visbonus\"></td></tr>";
echo "<tr align=left><td colspan=2>(To be divided equally among <b>visiting</b> schools.)</td></tr>";
echO "</table>";
if(!$bonus) $bonus="0.00";
echo "</td><td align=right><b>Bonus&nbsp;&nbsp;#7</b></td><td align=center>$<input type=text class=tiny size=7 name=\"bonus\" id=\"bonus\" value=\"$bonus\"></td></tr>";
echo "<tr><td colspan=2>&nbsp;";
echo "</td><td align=center><b>SUMMARY</b></td></tr>";
echo "<tr valign=center><td rowspan=6 align=right>&nbsp;";
echo "</td>";
echo "<input type=hidden name=\"nsaacheck\" id=\"nsaacheck\">";
if(!$officialsum) $officialsum="0.00";
echo "<td align=right>Officials</td><td align=center>$<input type=text class=tiny size=7 name=\"officialsum\" id=\"officialsum\" value=\"$officialsum\"></td></tr>";
if(!$hostsum) $hostsum="0.00";
echo "<tr><td align=right>Host</td><td align=center>$<input type=text class=tiny size=7 name=\"hostsum\" id=\"hostsum\" value=\"$hostsum\"></td></tr>";
if(!$vissum) $vissum="0.00";
echO "<tr><td align=right>Schools' Total</td><td align=center>$<input type=text class=tiny size=7 name=\"vissum\" id=\"vissum\" value=\"$vissum\"></td></tr>";
if(!$nsaasum) $nsaasum="0.00";
echo "<tr><td align=right>NSAA</td><td align=center>$<input type=text class=tiny size=7 name=\"nsaasum\" id=\"nsaasum\" value=\"$nsaasum\"></td></tr>";
if(!$insurancesum) $insurancesum="0.00";
echo "<tr><td align=right>Insurance</td><td align=center>$<input type=text class=tiny size=7 name=\"insurancesum\" id=\"insurancesum\" value=\"$insurancesum\"></td></tr>";
if(!$totalsum) $totalsum="0.00";
echO "<tr><td align=right>Total</td><td align=center>$<input type=text class=tiny size=7 name=\"totalsum\" id=\"totalsum\" value=\"$totalsum\"></td></tr>";
//if(!$totalsum2) $totalsum2="0.00";
//echO "<tr><td>&nbsp;</td><td align=right>Total</td><td align=center>$<input type=text class=tiny size=7 name=\"totalsum2\" id=\"totalsum2\" value=\"$totalsum2\"></td></tr>";

//LIVE VIDEO BROADCAST FORM:
echo "<tr><td align=left colspan=3>
        <h4>LIVE Video Broadcasts:</h4>
        <p><input type=radio name=\"livebcasts\" id=\"livebcastsYES\" value=\"Yes\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display=''; }\"> <b>YES</b>, LIVE Video Broadcasts were performed at my event.</p>
        <p><input type=radio name=\"livebcasts\" id=\"livebcastsNO\" value=\"No\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display='none'; }\"> <b>NO</b>, LIVE Video Broadcasts were not performed at my event.</p>
        <div id=\"bcastsdiv\" style=\"display:none;\">
        <p>Please complete the following section regarding <b><u>LIVE Video Broadcasts</b></u> that were performed at this event. ALL Broadcast request forms should be copied and submitted to the NSAA office via fax (402) 489-0934 or email <a href=\"mailto:jstauss@nsaahome.org\">jstauss@nsaahome.org</a>.</p>";
        //LOCAL MEDIA
echo "<br />
        <table cellspacing=0 cellpadding=5>
        <tr align=left><td colspan=2><p><b>STUDENT BROADCAST GROUPS:</b></p></td></tr>
        <tr bgcolor='yellow'><td align=right>Number of DAYS Broadcasted:</td><td align=left><input type=text size=2 name=\"localmedia_bcasts\" value=\"$localmedia_bcasts\" id=\"localmedia_bcasts\" onBlur=\"CalculateBcasts();\"></td></tr>
        <tr><td align=right>Total Broadcast Fees*:</td><td align=left>$<input type=text size=5 name=\"localmedia_bfee\" value=\"$localmedia_bfee\" id=\"localmedia_bfee\" onBlur=\"CalculateBcasts();\"> (at $100 per broadcast)</td></tr>
        <!--<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>-->";
        //OTHER MEDIA
        echo "<tr><td align=left colspan=2><p><b>LOCAL MEDIA BROADCAST GROUPS:</b></p></td></tr>";
        echo "<tr bgcolor='yellow'><td align=right>Number of DAYS Broadcasted:</td><td align=left><input type=text size=2 name=\"othermedia_wcasts\" value=\"$othermedia_wcasts\" id=\"othermedia_wcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
        echo "<tr><td align=right>Total Broadcast Fees**:</td><td align=left>$<input type=text size=5 name=\"othermedia_wfee\" value=\"$othermedia_wfee\" id=\"othermedia_wfee\" onBlur=\"CalculateBcasts();\"> (at $150 per broadcast)</td></tr>";
        echo "<tr bgcolor='yellow'><td align=right>Number of DAYS Telecasted (TV):</td><td align=left><input type=text size=2 name=\"othermedia_tcasts\" value=\"$othermedia_tcasts\" id=\"othermedia_tcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
        echo "<tr><td align=right>Total Telecast Fees**:</td><td align=left>$<input type=text size=6 name=\"othermedia_tfee\" value=\"$othermedia_tfee\" id=\"othermedia_tfee\" onBlur=\"CalculateBcasts();\"> (at $250 per telecast)</td></tr>";
        //echo "<tr><td align=left colspan=2><p><i>** The fees above will be invoiced by the NSAA office and receipts payable to the NSAA.</i></p></td></tr>";
        echo "<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>";
echo "</table>
        </div>
        </td></tr>";

//FINAL INSTRUCTIONS
echo "<tr align=center><td colspan=3><br />";
echo "<table width=500><tr align=left><td><font style=\"color:blue\">Please double-check that the information above is COMPLETE and ACCURATE before clicking \"Submit Report\".  You will NOT be able to make changes once your report is submitted.<br><br>When you click \"Submit Report\", you will be taken to the printer-friendly version of your completed form.  Please print out the form and send copies of it with your checks to the NSAA and the visiting schools.</font></td></tr></table>";

echo "<input type=hidden name=\"hiddenupdate\" id=\"hiddenupdate\"><input type=\"button\" id=\"update\" name=\"update\" class=\"fancybutton\" value=\"Submit Report\" onClick=\"if(ErrorCheckBcasts()) { Utilities.getElement('hiddenupdate').value='1'; submit(); } else { alert ('You must either check the box saying NO LIVE Video Broadcasts were performed at your event OR enter the number of broadcasts performed.'); }\"></td></tr>";

//echo "<input type=submit name=update value=\"Submit Report\" onclick=\"return confirm('Are you sure you want to submit this report?  You will not be able to make changes to your report after submitting it.');\"></td></tr>";
echo "</table>";
echo "</form>";
?>
<div id="loading" style="display:none"></div>
<?php
echo $end_html;
?>
