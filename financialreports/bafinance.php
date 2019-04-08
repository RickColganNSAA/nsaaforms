<?php
require '../../calculate/functions.php';
require '../functions.php';
require_once('../variables.php');
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}
if(!$distid || $distid=='')
{
   echo $init_html;
   echo "<table width=100%><tr align=center><th><br><br>ERROR: No District/Subdistrict Selected!";
   if($level==1)
      echo "<br><br><a href=\"baindex.php?session=$session\">Return to Financial Reports</a>";
   else
      echo "<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT city_state FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split(",",$row[0]);
$hostcity=trim($temp[0]);

//IF WE ARE PAST MAY 31, USE ARCHIVED OFFICIALS DB TO LOOK AT badistricts AND badisttimes
$database=$db_name2;
if(PastDue(date("Y")."-05-31",0))
{
   $year2=date("Y"); $year1=$year2-1;
   $database.=$year1.$year2;
}

//get dist info from districts table
if($disttimesid>0)
{
   $sql="SELECT t1.type,t1.class,t1.district,t2.* FROM $database.badistricts AS t1, $database.badisttimes AS t2 WHERE t1.id=t2.distid AND t2.id='$disttimesid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row['class']; $district=$row[district];
   $hostschool=$row[hostschool];
   if($hostschool=="") $hostschool="[Host not Available]";
   $hostid=$row[hostid];
   $dates=date("M j, Y",strtotime($row[day]));
   if($row[day]=="0000-00-00") $dates="[Date not Available]";
   $sql2="SELECT * FROM basched WHERE distid='$distid' AND gamenum='$row[gamenum]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $basch=array(GetMainSchoolName($row2[sid],$sport),GetMainSchoolName($row2[oppid],$sport));
   sort($basch);
   $type=$row[type]; $gamenum=$row[gamenum]; $site=$row[site];
}
else
{
   $sql="SELECT * FROM $database.badistricts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row['class']; $district=$row[district];
   $hostschool=$row[hostschool];
   if($hostschool=="") $hostschool="[Host not Available]";
   $hostid=$row[hostid];
   $dates="";
   $day=split("/",$row[dates]);
   for($i=0;$i<count($day);$i++)
   {
      $date=split("-",$day[$i]);
      $dates.=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0])).", ";
   }
   $dates.=$date[0];
   if($row[dates]=="") $dates="[Date not Available]";
   $basch=split(", ",$row[schools]);
   sort($basch);
   $type=$row[type];
}
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
if($type=="Subdistrict") $round=1;
else if($type=="District") $round=2;
else
{
   echo $init_html;
   echo "<table><tr align=center><th><br><br>ERROR: $type $class-$district is NOT a Baseball District.<br><br>";
   if($level==1)
      echo "<a href=\"baindex.php?session=$session\">Return to Financial Reports</a>";
   else
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}

$sql="SELECT school FROM finance_ba WHERE school='$school2' AND distid='$distid'";
if($disttimesid>0) $sql.=" AND disttimesid='$disttimesid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)   //already submitted
   $submitted=1;
else $submitted=0;

if($hiddenupdate || $update || $submitted==1) //submit report AND/OR show submitted report
{
   $error="";
   if($hiddenupdate || $update)//if report was submitted, add info to database & show printer-friendly version
   {
      //check that all required fields are completed
      if(($winner=='' || $runnerup=='') && $school!="Test's School" && $session!="1424366492")
         $error.="You must select the winner and runner-up teams.<br>";
      if($grossreceipts=="0.00")
         $error.="You must enter your Total Receipts (Gross Ticket Sales Plus Radio and TV Fees) in Box #1.<br>";
      if($error=='')
      {
      $sql="SELECT * FROM finance_ba WHERE school='$school2' AND distid='$distid'";
      if($disttimesid>0) $sql.=" AND disttimesid='$disttimesid'";
      $result=mysql_query($sql);
      $datesub=time();
      $winner2=addslashes($winner); $runnerup2=addslashes($runnerup);
      if(mysql_num_rows($result)==0)    //INSERT, THEN CAN UPDATE
      {
         $sql2="INSERT INTO finance_ba (datesub,school,distid,disttimesid) VALUES ('$datesub','$school2','$distid','$disttimesid')";
         $result2=mysql_query($sql2);
      }
      $sql2="UPDATE finance_ba SET nocasts='$nocasts',localmedia_bcasts='$localmedia_bcasts',localmedia_bfee='$localmedia_bfee', othermedia_wcasts='$othermedia_wcasts', othermedia_wfee='$othermedia_wfee', othermedia_tcasts='$othermedia_tcasts', othermedia_tfee='$othermedia_tfee', datesub='$datesub',round='$round',winner='$winner2',runnerup='$runnerup2',attendance='$attendance',grossreceipts='$grossreceipts',offfees='$offfees',offmiles='$offmiles',offmilespaid='$offmilespaid',offtotal='$offtotal',insurance='$insurance',balance='$balance',hostallow='$hostallow',nsaaallow='$nsaaallow',gate='$gate',b_cast='$b_cast' WHERE school='$school2' AND distid='$distid' AND disttimesid='$disttimesid'";
      $result2=mysql_query($sql2);
      }
   }//end if update

   if($error=='')
   {
   //get submitted info to display:
   $sql="SELECT * FROM finance_ba WHERE school='$school2' AND distid='$distid'";
   if($disttimesid>0) $sql.=" AND disttimesid='$disttimesid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($print!=1)
   {
      if($update || $hiddenupdate)
      {
         echo "<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"></head><body onload=\"window.open('bafinance.php?session=$session&disttimesid=$disttimesid&distid=$distid&print=1');\">";
      }
      else echo $init_html;
      echo GetHeader($session);
      echo "<br>";
      if($level==1)
         echo "<a href=\"baindex.php?session=$session\" class=small>Return to Financial Reports</a>&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"bafinance.php?session=$session&disttimesid=$disttimesid&distid=$distid&print=1&school_ch=$school_ch\" class=small target=new>Printer-Friendly Version</a><br><br>";
      echo "This financial report was completed on ".date("F j, Y",$row[datesub]).".  You may no longer make changes to this form.  Please contact the NSAA if you must make a change.  Thank you!<br><br>";
   }
   else
      echo $init_html."<table width=100%><tr align=center><td>";
   echo "<table width=700 class=nine><caption><b>NSAA Baseball Financial Report</b><hr></caption>";
   $today=date("M d, Y",time());
   echo "<tr align=left><td colspan=3><b>School: $space </b>$row[school]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Report Date: $space </b>".date("F j, Y",$row[datesub])."</td></tr>";
   echo "<tr align=left><td colspan=3><b>$type $class-$district</td></tr>";
   echo "<tr align=left><td colspan=3><b>At: $space </b>$site</td></tr>";
   echo "<tr align=left><td colspan=3><b>Date: $space </b>$dates (Game $gamenum)</td></tr>";
   echo "<tr align=left><td colspan=3><b>Winner:</b> $space $row[winner] $space <b>Runner-up:</b> $space $row[runnerup] $space <b>Attendance: $space </b>$row[attendance]</td></tr>";

   //#1
   echo "<tr align=right><td colspan=2><b>1. Gate Receipts  $space $</b></td><td width=50 align=right>".number_format($row[gate],'2','.','')."</td></tr>";
   echo "<tr align=right><td colspan=2><b>2. Broadcast Receipts  $space $</b></td><td width=50 align=right>".number_format($row[b_cast],'2','.','')."</td></tr>";
   echo "<tr align=right><td colspan=2><b>3. Total Receipts  $space $</b></td><td width=50 align=right>".number_format($row[grossreceipts],'2','.','')."</td></tr>";
   //#2
   echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
   echo "<tr align=left valign=center><td>a.&nbsp;Fees&nbsp;<span style=\"color:red;font-size:90%;font-style: italic;\">($55 per game)</span></td>";
   echo "<td>$".number_format($row[offfees],'2','.','')."</td></tr>";
   echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Total Mileage One Way for All Officials:&nbsp;</td>";
   echo "<td>$row[offmiles] miles</td><td>&nbsp;x $".$offmileagerate."</td></tr></table></td>";
   echo "<td>$".number_format($row[offmilespaid],2,'.','')."</td></tr></table></td></tr>";
   echo "<tr><td colspan=2 align=right><b>4. Officials' Total&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[offtotal],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
   echo "<td align=right><b>5. Insurance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[insurance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Balance</b> (Total Receipts #1, minus lines #2 & #3)</td>";
   echo "<td align=right><b>6. Balance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[balance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>NSAA</b> (25% of #4, Balance)</td>";
   echo "<td align=right><b>7. NSAA&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[nsaaallow],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Host School</b> (75% of #4, Balance)</td>";
   echO "<td align=right><b>8. Host School&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[hostallow],2,'.','')."</td></tr>";

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
   echo "<td align=left colspan=2>$space <b>Local Media Groups</b></td></tr>";
   echo "<td align=left colspan=2>$space Broadcasts:</td></tr>";
   echo "<tr><td align=right>$row[othermedia_wcasts] x $150 = $</td><td align=right>".number_format($row[othermedia_wfee],2,'.','')."</td></tr>";
   echo "<td align=left colspan=2>$space Telecasts (TV):</td></tr>";
   echo "<tr><td align=right>$row[othermedia_tcasts] x $250 = $</td><td align=right>".number_format($row[othermedia_tfee],2,'.','')."</td></tr>";
   echo "</td></tr>";

   echo "<tr align=center><td><table width=450 border=1 bordercolor=\"red\" cellspacing=0 cellpadding=5><tr align=left><td class=nine>";
   $nsaacheck=number_format($row[insurance]+$row[nsaaallow],'2','.','');
   echo "<font style=\"color:red;\"><b>Write a check to NSAA for <font style=\"font-size:11pt\"><u>$".$nsaacheck."</u></font> and send a copy of this form with the check to the NSAA.<br></td></tr></table>";
   echo "</td>";
   echo "<td align=right colspan=2><br><br>";
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=5 class=nine width=220>";
   echo "<caption>NSAA USE ONLY</caption>";
   echo "<tr align=left><td><br>Date<u> ".$space."$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Check No. <u>$space $space $space $space $space $space $space $space</u><br><br>";
   echo "Baseball<br>602-1 <u>$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Insurance<br>642-30 <u>$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Live Video<br>692-50 <u>$space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "</td></tr></table></td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
   }
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Finance2.js"></script>
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
<body onload="Finance2.initialize('<?php echo $session; ?>','ba');">
<?php
echo "<br>";
if($level==1)
   echo "<a href=\"baindex.php?session=$session\" class=small>Financial Reports Home</a><br><br>";
echo "<form method=post action=\"bafinance.php\" name=baform>";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=round value=$round>";
//echo "EXTRA: <div name=extra id=extra></div>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<input type=hidden name=disttimesid value=\"$disttimesid\">";
echo "<table width=90% cellspacing=2 cellpadding=2 class=nine><caption><b>NSAA BASEBALL DISTRICT FINANCIAL REPORT</b><hr>";
if($error!='')
{
   echo "<table class=nine><tr align=left><td><font style=\"color:red\">";
   echo "<b>You have the following errors in your form:<br><br></b>$error";
   echo "</td></tr></table>";
}
echO "</caption>";
echo "<tr align=left><td colspan=3><b>District $class-$district at $hostschool.<br>";
echO "Date:&nbsp;&nbsp;$dates</b> (Game $gamenum)</td></tr>";
echo "<tr align=left><td colspan=3><i><b><u>INSTRUCTIONS:</u></b><br><table cellpadding=3><tr align=left bgcolor=yellow><td><i>Please complete all fields highlighted in yellow.  The calculations will be made as you enter the numbers.<br>Please fill out the form completely, from top to bottom.</i></td></tr></table>When you are finished completing this form, click \"Submit Report\".  You will then be taken to a printer-friendly version of the form.  Please PRINT a copy of the form and send it, along with a check for the amount due the NSAA, to the NSAA office immediately.  Print a second copy for your files.  After receiving approval of the report from the NSAA, send a copy of this form to each participating school with the amount due them.</i></td></tr>";
echo "<tr><td></td><td align=right><b>1. Gate Receipts&nbsp;&nbsp;</b><br>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=1 name=\"gate\" id=\"gate\" value=\"".number_format($gate,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr>";

echo "<tr><td></td><td align=right><b>2. Broadcast Receipts&nbsp;&nbsp;</b><br>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=2 name=\"b_cast\" id=\"b_cast\" value=\"".number_format($b_cast,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr>";


echo "<tr align=left valign=center><td><table><tr align=left bgcolor=yellow><td><b>Winner:</b>&nbsp;";
echo "<select name=\"winner\" tabindex=3 ><option value=''>~</option>";
for($i=0;$i<count($basch);$i++) 
{
   echo "<option";
   if($winner==$basch[$i]) echo " selected";
   echo ">$basch[$i]</option>";
}
echo "</select></td>";
echo "<td><b>Runner-up:</b>&nbsp;";
echo "<select name=\"runnerup\" tabindex=4><option value=''>~</option>";
for($i=0;$i<count($basch);$i++) 
{
   echo "<option";
   if($runnerup==$basch[$i]) echo " selected";
   echo ">$basch[$i]</option>";
}
echo "</select></td>";
echo "<td><b>Attendance:</b>&nbsp;<input type=text class=tiny size=6 name=\"attendance\" value=\"$attendance\" tabindex=5></td></tr></table></td>";
echo "<td align=right><b>3. Total Receipts&nbsp;&nbsp;</b><br>";
echo "<font style=\"font-size:8pt;\">(Gross Ticket Sales Plus Radio and TV Fees)</font></td>";
echo "<td width=100  align=center>$<input type=text class=tiny size=7 tabindex=600 name=\"grossreceipts\" id=\"grossreceipts\" value=\"".number_format($grossreceipts,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\" readOnly=TRUE></td></tr>";
echo "<tr align=left><td colspan=3>Expenses are to be paid in full in order listed, using funds available.</td></tr>";
echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
echo "<tr align=left valign=center><td>a.&nbsp;Fees&nbsp;<span style=\"color:red;font-size:90%;font-style: italic;\">($55 per game)</span></td>";
echo "<td bgcolor=yellow>$<input type=text class=tiny size=6 tabindex=8 name=\"offfees\" id=\"offfees\" value=\"".number_format($offfees,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr>";
echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Total Mileage One Way for All Officials:&nbsp;</td><td bgcolor=yellow><input type=text class=tiny size=3 tabindex=8 name=\"offmiles\" id=\"offmiles\" value=\"$offmiles\" onblur=\"Finance2.Calculate(this.id,this.value);\"> miles</td><td>&nbsp;x $1.00</td></tr></table></td>";
echo "<td>$<input type=text class=tiny size=6  name=\"offmilespaid\" id=\"offmilespaid\" value=\"".number_format($offmilespaid,2,'.','')."\" onblur=\"Finance2.Calculate(this.id,this.value);\"></td></tr></table></td></tr>";
echo "<tr><td colspan=2 align=right><b>4. Officials' Total&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"offtotal\" id=\"offtotal\" value=\"".number_format($offtotal,2,'.','')."\" readOnly=TRUE></td></tr>";
echO "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
echo "<td align=right><b>5. Insurance&nbsp;&nbsp;</b></td><td align=center>$<input type=text tabindex=500 class=tiny size=7  name=\"insurance\" id=\"insurance\" value=\"".number_format($insurance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Balance</b> (Total Receipts #1, minus lines #2 & #3)</td>";
echo "<td align=right><b>6. Balance&nbsp;&nbsp;</b></td><td align=center>$<input type=text ctabindex=5000 lass=tiny size=7  name=\"balance\" id=\"balance\" value=\"".number_format($balance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>NSAA</b> (25% of #4, Balance)</td>";
echo "<td align=right><b>7. NSAA&nbsp;&nbsp;</b></td><td align=center>$<input type=text tabindex=5000 class=tiny size=7 name=\"nsaaallow\" id=\"nsaaallow\" value=\"".number_format($nsaaallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td align=left><b>Host School</b> (75% of #4, Balance)</td>";
echO "<td align=right><b>8. Host School&nbsp;&nbsp;</b></td>";
echo "<td align=center>$<input type=text class=tiny size=7 name=\"hostallow\" id=\"hostallow\" value=\"".number_format($hostallow,2,'.','')."\" readOnly=TRUE></td></tr>";

//LIVE VIDEO BROADCAST FORM:
echo "<tr><td align=left colspan=3>
        <br /><h4>LIVE Video Broadcasts:</h4>
        <p><input type=radio name=\"livebcasts\" tabindex=9 id=\"livebcastsYES\" value=\"Yes\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display=''; }\"> <b>YES</b>, LIVE Video Broadcasts were performed at my event.</p>
        <p><input type=radio name=\"livebcasts\" id=\"livebcastsNO\" value=\"No\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display='none'; }\"> <b>NO</b>, LIVE Video Broadcasts were not performed at my event.</p>
        <div id=\"bcastsdiv\" style=\"display:none;\">
        <p>Please complete the following section regarding <b><u>LIVE Video Broadcasts</b></u> that were performed at this event. ALL Broadcast request forms should be copied and submitted to the NSAA office via fax (402) 489-0934 or email <a href=\"mailto:jstauss@nsaahome.org\">jstauss@nsaahome.org</a>.</p>";
        //LOCAL MEDIA
echo "<br />
        <table cellspacing=0 cellpadding=5>
        <tr align=left><td colspan=2><p><b> STUDENT BROADCAST GROUPS:</b></p></td></tr>
        <tr bgcolor='yellow'><td align=right>Number of GAMES Broadcasted:</td><td align=left><input type=text tabindex=10  size=2 name=\"localmedia_bcasts\" value=\"$localmedia_bcasts\" id=\"localmedia_bcasts\" onBlur=\"CalculateBcasts();\"></td></tr>
        <tr><td align=right>Total Broadcast Fees*:</td><td align=left>$<input type=text tabindex=11  size=5 name=\"localmedia_bfee\" value=\"$localmedia_bfee\" id=\"localmedia_bfee\" onBlur=\"CalculateBcasts();\"> (at $100 per broadcast)</td></tr>
        <!--<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>-->";
        //OTHER MEDIA
        //echo "<tr><td align=left colspan=2><p><b>COMPETITOR/OTHER MEDIA & AFFILIATED STUDENT BROADCAST GROUPS:</b></p></td></tr>";
        echo "<tr><td align=left colspan=2><p><b>LOCAL MEDIA BROADCAST GROUPS:</b></p></td></tr>";
        echo "<tr bgcolor='yellow'><td align=right>Number of GAMES Broadcasted:</td><td align=left><input type=text tabindex=12 size=2 name=\"othermedia_wcasts\" value=\"$othermedia_wcasts\" id=\"othermedia_wcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
        echo "<tr><td align=right>Total Broadcast Fees**:</td><td align=left>$<input type=text size=5 tabindex=13 name=\"othermedia_wfee\" value=\"$othermedia_wfee\" id=\"othermedia_wfee\" onBlur=\"CalculateBcasts();\"> (at $150 per broadcast)</td></tr>";
        echo "<tr bgcolor='yellow'><td align=right>Number of GAMES Telecasted (TV):</td><td align=left><input type=text tabindex=14 size=2 name=\"othermedia_tcasts\" value=\"$othermedia_tcasts\" id=\"othermedia_tcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
        echo "<tr><td align=right>Total Telecast Fees**:</td><td align=left>$<input type=text size=6 name=\"othermedia_tfee\" value=\"$othermedia_tfee\" id=\"othermedia_tfee\" onBlur=\"CalculateBcasts();\"> (at $250 per telecast)</td></tr>";
        //echo "<tr><td align=left colspan=2><p><i>** The fees above will be invoiced by the NSAA office and receipts payable to the NSAA.</i></p></td></tr>";
        echo "<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>";
echo "</table>
        </div>
        </td></tr>";

//FINAL INSTRUCTIONS
echo "<tr align=center><td colspan=3>";
echo "<table width=500><tr align=left><td><font style=\"color:blue\">Please double-check that the information above is COMPLETE and ACCURATE before clicking \"Submit Report\".  You will NOT be able to make changes once your report is submitted.<br><br>When you click \"Submit Report\", you will be taken to the printer-friendly version of your completed form.  Please print out the form and send copies of it with your checks to the NSAA and the visiting schools.</font></td></tr></table>";

echo "<input type=hidden name=\"hiddenupdate\" id=\"hiddenupdate\"><input type=\"button\" id=\"update\" tabindex=15 name=\"update\" class=\"fancybutton\" value=\"Submit Report\" onClick=\"if(ErrorCheckBcasts()) { Utilities.getElement('hiddenupdate').value='1'; submit(); } else { alert ('You must either check the box saying NO LIVE Video Broadcasts were performed at your event OR enter the number of broadcasts performed.'); }\"></td></tr>";

echo "</table>";
echo "</form>";
?>
<div id="loading" style="display:none"></div>
<?php
echo $end_html;
?>
