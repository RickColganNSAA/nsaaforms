<?php
$space="&nbsp;&nbsp;&nbsp;";

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
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

 if(!$scoreid || $scoreid=='')
{
   $sid=GetSID2($school,'fb');
   if($round) $roundnum=$round;
   if(($class=='A' || $class=='B' || $class=='C1' || $class=='C2') && $roundnum==3) $roundnum=2;
   if(($class=='A' || $class=='B' || $class=='C1' || $class=='C2') && $roundnum==4) $roundnum=3;
   $sql2="SELECT * FROM fbsched WHERE homeid='$sid' AND class='$class' AND round='$roundnum'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $scoreid=$row2[scoreid];
     if(mysql_num_rows($result2)!=1)
   {
      echo $init_html;
      echo "<table width=100%><tr align=center><th><br><br>ERROR: No Game Selected!";
      if($level==1)
         echo "<br><br><a href=\"fbindex.php?session=$session\">Return to Financial Reports</a>";
      else
	 echo "<br><br><a href=\"../welcome.php?session=$session\">Home</a>";
      echo $end_html;
      exit();
   }  
} 

//get dist info from districts table
$sql="SELECT * FROM fbsched WHERE scoreid='$scoreid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class'];  $roundnum=$row[round];
//if($class=='A' || $class=='B')
if($class=='A' || $class=='B' || $class=='C1' || $class=='C2')
   $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
else
   $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
for($i=0;$i<count($rounds);$i++)
{
   if(($i+1)==$roundnum) $round=$rounds[$i];
}
    if($roundnum==0)
{
   echo $init_html;
   echo "<table><tr align=center><th><br><br>ERROR: The game you've selected is NOT a valid Football game to report on.<br><br>";
   if($level==1)
      echo "<a href=\"fbindex.php?session=$session\">Return to Financial Reports</a>";
   else
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}    

$sql="SELECT school FROM finance_fb WHERE school='$school2' AND classdist='$class' AND round='$roundnum'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)	//already submitted
   $submitted=1;
else $submitted=0;

if($calculate)	//clean up values
{  //echo '<pre>'; print_r($_POST); exit;
   $grossreceipts=CleanCurrency($grossreceipts);
   //$gate=CleanCurrency($gate);
   //$b_cast=CleanCurrency($b_cast);
   $offfees=CleanCurrency($offfees);
   $offmilespaid=CleanCurrency($offmilespaid);
   if($partyct=="")
      $error=1;
}

if($hiddenupdate || $update || $submitted==1)	//submit report as complete AND/OR show submitted report
{  
   if($hiddenupdate || $update)//if report was submitted, add info to database
   {
   if($partyct=="")
      $error=1;
   $location=ereg_replace("\'","\'",$location);
   $location=ereg_replace("\"","\'",$location);
   $visitor=ereg_replace("\'","\'",$visitor);
   $visitor=ereg_replace("\"","\'",$visitor);

   $sql="SELECT school FROM finance_fb WHERE school='$school2' AND classdist='$class' AND round='$round'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      $sql2="INSERT INTO finance_fb (nocasts,localmedia_bcasts,localmedia_bfee,othermedia_wcasts,othermedia_wfee,othermedia_tcasts,othermedia_tfee,datesub,school,classdist,round,location,attendance,visitor,grossreceipts,offfees,offmiles,offmilespaid,visitormiles,partyct,insdeduct,balance,hostallow,nsaaallow,visitorpaid,distribution,prorate,hostallowpro,nsaaallowpro,visitorpaidpro,distributionpro,bonus,hostbonus,visitorbonus,nsaabonus,gate,b_cast) VALUES ('$nocasts','$localmedia_bcasts','$localmedia_bfee','$othermedia_wcasts','$othermedia_wfee','$othermedia_tcasts','$othermedia_tfee','".time()."','$school2','$class','$roundnum','$location','$attendance','$visitor','$grossreceipts','$offfees','$offmiles','$offmilespaid','$visitormiles','$partyct','$insdeduct','$balance','$hostallow','$nsaaallow','$visitorpaid','$distribution','$prorate','$hostallowpro','$nsaaallowpro','$visitorpaidpro','$distributionpro','$bonus','$hostbonus','$visitorbonus','$nsaabonus','$gate','$b_cast')";
   }
   else					//UPDATE
   {
      $sql2="UPDATE finance_fb SET nocasts='$nocasts',localmedia_bcasts='$localmedia_bcasts',localmedia_bfee='$localmedia_bfee', othermedia_wcasts='$othermedia_wcasts', othermedia_wfee='$othermedia_wfee', othermedia_tcasts='$othermedia_tcasts', othermedia_tfee='$othermedia_tfee', datesub='".time()."',location='$location',attendance='$attendance',visitor='$visitor',grossreceipts='$grossreceipts',offfees='$offfees',offmiles='$offmiles',offmilespaid='$offmilespaid',visitormiles='$visitormiles',partyct='$partyct',insdeduct='$insdeduct',balance='$balance',hostallow='$hostallow',nsaaallow='$nsaaallow',visitorpaid='$visitorpaid',distribution='$distribution',prorate='$prorate',hostallowpro='$hostallowpro',nsaaallowpro='$nsaaallowpro',visitorpaidpro='$visitorpaidpro',distributionpro='$distributionpro',bonus='$bonus',hostbonus='$hostbonus',visitorbonus='$visitorbonus',nsaabonus='$nsaabonus',gate='$gate',b_cast='$b_cast' WHERE school='$school2' AND classdist='$class' AND round='$roundnum'";
   }
   $result2=mysql_query($sql2);
   }//end if update

   //get submitted info to display
   $sql="SELECT * FROM finance_fb WHERE school='$school2' AND classdist='$class' AND round='$roundnum'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo mysql_error();
   
   $location=$row[4]; $attendance=$row[5];
   $visitor=$row[6]; $grossreceipts=$row[7];
   $offfees=$row[8]; $offmiles=$row[9];
   $offmilespaid=$row[10]; $visitormiles=$row[11];
   $partyct=$row[partyct];
   $gate=$row[37];
   $b_cast=$row[38];
   //get "per mile" allotment
   if($partyct>=13 && $partyct<=18)
      $permile="2.55";
   if($partyct>=19 && $partyct<=24)
      $permile="3.40";
   else if($partyct>=25 && $partyct<=30)
      $permile="4.25";
   else if($partyct>=31)
      $permile="5.10";
   $offtotal=number_format($offfees+$offmilespaid,2,'.','');

   $insdeduct=number_format($grossreceipts*.10,2,'.','');

   $balance=number_format($grossreceipts-$offtotal-$insdeduct,2,'.','');
   if($balance<0) $balance="0.00";

   $hostallow=number_format($grossreceipts*0.25,2,'.','');
   $nsaaallow=number_format($grossreceipts*0.25,2,'.','');
   if(($visitormiles-50)*$permile<$grossreceipts*.1)
      $visitorpaid=number_format($grossreceipts*.1,2,'.','');
   else
      $visitorpaid=number_format(($visitormiles-50)*$permile,2,'.','');
   $distribution=number_format($hostallow+$nsaaallow+$visitorpaid,2,'.','');

   $prorate=$balance/$distribution;
   if($prorate>1) $prorate=1;
   echo "<input type=hidden name=prorate value=$prorate>";
   $prorateperc=number_format($prorate*100,4,'.','');
   $hostallowpro=number_format($hostallow*$prorate,2,'.','');
   $visitorpaidpro=number_format($visitorpaid*$prorate,2,'.','');
   $nsaaallowpro=number_format($balance-$hostallowpro-$visitorpaidpro,2,'.','');
   $tempnum=number_format($nsaaallow*$prorate,2,'.','');
   if($nsaaallowpro>$tempnum) 
   {
      $diff=number_format($nsaaallowpro-$tempnum,2,'.','');
      $nsaaallowpro=number_format($nsaaallow*$prorate,2,'.','');
      if($diff<0.05) $visitorpaidpro=number_format($visitorpaidpro+$diff,2,'.','');
   }
   $distributionpro=number_format($hostallowpro+$nsaaallowpro+$visitorpaidpro,2,'.','');

   $bonus=number_format($balance-$distributionpro,2,'.','');
   if($bonus<0) $bonus="0.00";
   $hostbonus=number_format($bonus*.4,2,'.','');
   $visitorbonus=number_format($bonus*.4,2,'.','');
   $nsaabonus=number_format($bonus-$hostbonus-$visitorbonus,2,'.','');

   $hosttotal=number_format($hostallowpro+$hostbonus,2,'.','');
   $visitortotal=number_format($visitorpaidpro+$visitorbonus,2,'.','');
   $nsaapartial=number_format($nsaaallowpro+$nsaabonus,2,'.','');
   $nsaatotal=number_format($nsaaallowpro+$nsaabonus+$insdeduct,2,'.','');
   $total=number_format($offtotal+$hosttotal+$visitortotal+$nsaatotal,2,'.','');

   if($hiddenupdate || $update)
   {
      echo "<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"></head><body onload=\"window.open('fbfinance.php?session=$session&class=$class&round=$roundnum&school_ch=$school_ch&print=1');\">";
   }
   else echo $init_html;

   if($print!=1) echo $header;
   echo "<table width=100%><tr align=center><td>";
   if($print!=1)
   {
      echo "<br><br>";
      if($level==1)
         echo "<a href=\"fbindex.php?session=$session\" class=small>Return to Financial Reports</a>$space";
      echo "<a href=\"fbfinance.php?session=$session&scoreid=$scoreid&school_ch=$school&print=1\" target=\"_blank\" class=small>Printer-Friendly Version</a><br><br>";
      echo "Your financial report has been submitted.  Please <a class=small target=new href=\"fbfinance.php?session=$session&scoreid=$scoreid&school_ch=$school_ch&print=1\">print out this page</a> for your records.  Thank you!<br><br>";
   }
   echo "<p>Please select <b>File ---> Print</b> to print this page. Send a copy of this printed form with your checks to the Visitng School and the NSAA.</p><table><caption><b>NSAA Football Financial Report</b><hr></caption>";
   $today=date("M d, Y",time());
   echo "<tr align=left><td colspan=3><b>School: $space </b>$school</td></tr>";
   echo "<tr align=left><td colspan=3><b>Date: $space </b>$today</td></tr>";
   echo "<tr align=left><td colspan=3><b>Class: $space </b>$class</td></tr>";
   echo "<tr align=left><td colspan=3><b>Playoff: $space </b>$round</td></tr>";
   echo "<tr align=left><td colspan=3><b>At: $space </b>$location</td></tr>";
   echo "<tr align=left><td colspan=3><b>Attendance: $space </b>$attendance</td></tr>";
   echo "<tr align=left><td colspan=3><b>Name of Visiting Team: $space </b>$visitor</td></tr>";
   echo "<tr align=left><td colspan=3><b>Number on Opponent's Roster Plus 1 Coach: $space </b>$partyct&nbsp;&nbsp;(See chart below for maximum #, which INCLUDES the coach)<br>";
   echo "<table cellspacing=0 cellpadding=2 border=1 bordercolor=#000000>";
   echo "<tr align=center><td><b>Class</b></td><td><b>Max #</b></td></tr>";
   echo "<tr align=left><td>A/B</td><td>42</td></tr>";
   echo "<tr align=left><td>C1/C2</td><td>38</td></tr>";
   echo "<tr align=left><td>D1</td><td>28</td></tr>";
   echo "<tr align=left><td>D2</td><td>24</td></tr></table>";
   echo "</td></tr>";

   //#1
   echo "<tr align=right><th colspan=2 class=smaller>1. GATE Receipts $space  $space $</th><th class=smaller align=right>$gate</th></tr>";
   echo "<tr align=right><th colspan=2 class=smaller>2. Broadcast Receipts $space  $space $</th><th class=smaller align=right>$b_cast</th></tr>";
   echo "<tr align=right><th colspan=2 class=smaller>3. Gross Receipts $space  $space $</th><th class=smaller align=right>$grossreceipts</th></tr>";

   //#2
   echo "<tr align=left valign=bottom><th>";
   echo "<table><tr align=left><th class=smaller colspan=2>Officials</th></tr>";
   echo "<tr align=left><td>Fees: ($70 per official)</td><td align=right>= 2a. $$offfees</td></tr>";
   echo "<tr align=left><td>Mileage: (one way, one car) $space $offmiles x $".$offmileagerate."</td><td align=right>= 2b. $$offmilespaid</td></tr>";
   echo "</table></th>";
   echo "<th align=right class=smaller>4. Total Officials' Payment $space  $space $</th><th class=smaller align=right>$offtotal</th></tr>";

   //#3
   echo "<tr align=left><th class=smaller>Insurance Deduction (line #1 x 0.10)</th>";
   echo "<th class=smaller align=right>5. Insurance $space  $space $</th><th class=smaller align=right>$insdeduct</th></tr>";

   //#4
   echo "<tr align=left><th class=smaller>Balance Before Distribution (line #1 minus lines #2 & #3)</th>";
   echo "<th class=smaller align=right>6. Balance $space  $space $</th><th class=smaller align=right>$balance</th></tr>";

   //#5
   echo "<tr align=left valign=bottom><th>";
   echo "<table><tr align=left><th class=smaller colspan=2>Distribution</th></tr>";
   echo "<tr align=left><td>Host School Allowance (line #1 x 0.25)</td><td align=right>5a. $$hostallow</td></tr>";
   echo "<tr align=left><td>NSAA School Allowance (line #1 x 0.25)</td><td align=right>5b. $$nsaaallow</td></tr>";
   echo "<tr align=left><td>Visitor's Mileage (($visitormiles - 50 mi) x $$permile OR 10% of line #1)</td>";
   echo "<td align=right>5c. $$visitorpaid</td></tr>";
   echo "</table></th>";
   echo "<th class=smaller align=right>7. Total Distribution $space  $space $</th><th class=smaller align=right>$distribution</th></tr>";

   //#6
   echo "<tr align=left valign=bottom><th>";
   echo "<table><tr align=left><th class=smaller colspan=2>Distribution Prorated (if necessary)</th></tr>";
   echo "<tr align=left><td>Host School Allowance $space 5a. x ";
   if($prorate<1) echo "$prorateperc%";
   echo "</td>";
   echo "<td align=right>";
   if($prorate<1) echo "= 6a. $$hostallowpro";
   echo "</td></tr>";
   echo "<tr align=left><td>NSAA Allowance $space 5b. x ";
   if($prorate<1) echo "$prorateperc%";
   echo "</td>";
   echo "<td align=right>";
   if($prorate<1) echo "= 6b. $$nsaaallowpro";
   echo "</td></tr>";
   echo "<tr align=left><td>Visitor's Mileage $space 5c. x ";
   if($prorate<1) echo "$prorateperc%";
   echo "</td>";
   echo "<td align=right>";
   if($prorate<1) echo "=6c. $$visitorpaidpro";
   echo "</td></tr>";
   echo "</table></th>";
   echo "<th class=smaller align=right>8. Total Prorated Distribution $space  $space $</th><th class=smaller align=right>";
   if($prorate<1) echo "$distributionpro";
   else echo "0.00";
   echo "</th></tr>";

   //#7
   echo "<tr align=left valign=top><th>";
   echo "<table><tr align=left><th class=smaller colspan=2>Bonus Balance</th></tr>";
   echo "<tr align=left><td>Host School Share (line #7 x 0.40)</td><td align=right>= 7a. $$hostbonus</td></tr>";
   echo "<tr align=left><td>Visiting School Share (line #7 x 0.40)</td><td align=right>= 7b. $$visitorbonus</td></tr>";
   echo "<tr align=left><td>NSAA Share (line #7 x 0.20)</td><td align=right>= 7c. $$nsaabonus</td></tr>";
   echo "</table></th>";
   echo "<th align=right class=smaller>9. Bonus Balance $space  $space $</th><th class=smaller align=right>$bonus</th></tr>";

   //SUMMARY
   echo "<tr><td colspan=2>&nbsp;";
   echo "</td><td align=center><b>SUMMARY</b></td></tr>";
   echo "<tr valign=center><td rowspan=6 align=right>&nbsp;";
   echo "<table width=450 border=1 bordercolor=\"red\" cellspacing=0 cellpadding=5><tr align=left><td class=nine>";
   echo "<font style=\"color:red;\"><b>Write a check to Visiting School for <font size=2><u>$$visitortotal</u></font> and send a copy of this form with the check to the school.<br>";
   echo "<br>Write a check to NSAA for <font size=2><u>$$nsaatotal</u></font> and send a copy of this form with the check to the NSAA.</font></b></td></tr></table>";
   echo "</td>";
   echo "<td align=right>Officials $space $</td><td align=right width=50>".number_format($offtotal,2,'.','')."</td></tr>";
   echo "<tr><td align=right>Hosting School $space $</td><td align=right>".number_format($hosttotal,2,'.','')."</td></tr>";
   echo "<tr><td align=right>Visiting School $space $</td><td align=right>".number_format($visitortotal,2,'.','')."</td></tr>";
   echo "<tr><td align=right>NSAA $space $</td><td align=right>".number_format($nsaapartial,2,'.','')."</td></tr>";
   echo "<tr><td align=right>Insurance $space $</td><td align=right>".number_format($insdeduct,2,'.','')."</td></tr>";
   echo "<tr><td align=right>Total Gate $space $</td><td align=right>".number_format($total,2,'.','')."</td></tr>";

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
      echo "&nbsp;<font style=\"font-size:11pt;\">$".$wtfee."</font></p><p style=\"color:red\"><i>Competitor/Other Media & Affiliated Student Broadcast Fees will be invoiced by the NSAA and receipts payable to the NSAA.</i></p>";
   }
   else echo "&nbsp;<font style=\"font-size:11pt;\">N/A</font></p>";
   echo "</td></tr></table>"; */
   echo "</td>";
   echo "<td align=right colspan=2><br /><b>LIVE VIDEO BROADCASTS</b></td></tr>";
   echo "<tr><td align=left colspan=2>$space <b>Student Groups</b></td></tr>";
   echo "<tr><td align=right>$row[localmedia_bcasts] x $100 = $</td><td align=right>".number_format($row[localmedia_bfee],2,'.','')."</td></tr>";
   echo "<td align=left colspan=2>$space <b>Local Media Groups</b></td></tr>";
   echo "<td align=left colspan=2>$space Webcasts:</td></tr>";
   echo "<tr><td align=right>$row[othermedia_wcasts] x $250 = $</td><td align=right>".number_format($row[othermedia_wfee],2,'.','')."</td></tr>";
   echo "<td align=left colspan=2>$space Telecasts (TV):</td></tr>";
   echo "<tr><td align=right>$row[othermedia_tcasts] x $250 = $</td><td align=right>".number_format($row[othermedia_tfee],2,'.','')."</td></tr>";
   echo "</td></tr>";

   echo "<tr><td colspan=3 align=right><br><br>";
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=5 class=nine>";
   echo "<caption>NSAA USE ONLY</caption>";
   echo "<tr align=right><td><br>Date <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Check No. <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Total <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "FB 602-6 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "INS.642-30 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Live Video.692-50 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "</td></tr></table></td></tr>";

   echo "</table>";

   if($print!=1)
   {
      echo "<br>";
      if($level==1)
         echo "<a href=\"fbindex.php?session=$session\" class=small>Return to Financial Reports</a>$space";
      echo "<a href=\"../welcome.php?session=$session\" class=small>Return to Home</a>$space";
      echo "<a href=\"fbfinance.php?session=$session&scoreid=$scoreid&school_ch=$school&print=1\" target=\"_blank\" class=small>Printer-Friendly Version</a><br><br>";
   }
   echo $end_html;
   exit();
}
$thisyr=GetFallYear('fb');
$sql="SELECT * FROM fbsched WHERE scoreid='$scoreid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sid=GetSID($session,'fb');
if($row[sid]==$sid)
   $oppname=GetSchoolName($row[oppid],'fb',$thisyr);
else
   $oppname=GetSchoolName($row[sid],'fb',$thisyr);
$hostname=GetSchoolName($row[homeid],'fb',$thisyr);
if($row[gamesite]!='') $hostname=$row[gamesite];
$hostid=$row[homeid];
if($level!=1 && $sid!=$row[homeid])
{
   echo $init_html;
   echo "<table><tr align=center><th><br><br>ERROR: You are NOT the host of Class $row[class], Round $row[round] (".GetSchoolName($row[sid],'fb',$thisyr)." vs ".GetSchoolName($row[oppid],'fb',$thisyr).").<br><br>";
   echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
echo $init_html_ajax;
?>
<script language="javascript">
function CalculateGross()
{
   var gate=Utilities.getElement('gate').value;
   var b_cast=Utilities.getElement('b_cast').value;
   var bfee=parseFloat(gate) + parseFloat(b_cast);
   Utilities.getElement('grossreceipts').value=parseFloat(bfee);

}
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
<?php
echo $header;
if($level==1)
   echo "<br><a href=\"fbindex.php?session=$session\" class=small>Financial Reports Home</a><br><br>";
else
   echo "<br>";
echo "<form method=post action=\"fbfinance.php\" name=\"fbform\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=scoreid value=\"$scoreid\">";
echo "<table cellspacing=0 cellpadding=0 style=\"max-width:800px;\"><caption>";
echo "<h2>FOOTBALL FINANCIAL REPORT:</h2>";echo "<div class=\"normalwhite\" style=\"width:700px;\"><p><b><u>INSTRUCTIONS:</u></b></p><p>Please fill out the form completely, from top to bottom.</p>
<p style=\"color:red;\"><b><label style=\"background-color:yellow;\">*NEW*</label> Please make sure to complete the <u>Video Broadcast Section</u> located at the bottom of this form. ALL \"LIVE Video Broadcast Request Forms\" should be forwarded to the NSAA office via fax (402) 489-0934 or email: <a href=\"mailto:mhuber@nsaahome.org\">mhuber@nsaahome.org</a>. Additional information on the NSAA's LIVE Video Broadcast Policies is located in the NSAA Media Manual on the website.</b></p>
<p>When you are finished completing this form, click \"Submit Report\".  You will then be taken to a printer-friendly version of the form.  Please PRINT a copy of the form and send it, along with a check for the amount due the NSAA, to the NSAA office immediately.  Print a second copy for your files.  After receiving approval of the reportfrom the NSAA, send a copy of this form to each participating school with the amount due them.</p>
</div><br><hr><br>";
echo "</caption>";
//#1
echo "<tr align=left><th class=smaller colspan=2>";
echo "CLASS $class ".strtoupper($round)." ";
echo "$space AT $space<input type=text name=location value=\"$hostname\" size=50>";
echo "</th></tr>";
?>
<script language="JavaScript">
function round(number,X)
{
   return number.toFixed(X);
}
</script>

<?php
 
echo "<tr align=left><th class=smaller colspan=2>Attendance:&nbsp;&nbsp;<input type=text name=attendance size=6 value=\"$attendance\">$space$space";
echo " Name of Visiting Team:&nbsp;&nbsp;<input type=text name=visitor value=\"$oppname\" size=50></th></tr>";
echo "<tr align=left><th class=smaller colspan=2>";
echo "<br>Please enter the following values and then click \"Calculate\" to complete the rest of the form.<br>";
echo "You will need to click \"Calculate\" again any time you change any of the following values!<br><br>";
echo "Also, please note that you must <font style=\"color:red\">click \"Submit Report\"</font> at the bottom of this screen when you are<br>satisfied with the calculations in order for the form to be sent to the NSAA.<br><br>";
echo "<font style=\"color:red\">NOTE: To move between fields, use the TAB key.<br><br>";
echo "***Please only enter numbers. You do not need to include dollar signs ($) or commas (,)***</font></th></tr>";

echo "<tr align=left><th class=smaller colspan=2><br>Gate Receipts&nbsp;";
echo "<input type=text name=gate value=\"".number_format($gate,2,'.','')."\" size=10 id=\"gate\" onBlur=\"CalculateGross();\"><br>";
echo "<tr align=left><th class=smaller colspan=2><br>Broadcast Receipts&nbsp;";
echo "<input type=text name=b_cast value=\"".number_format($b_cast,2,'.','')."\" size=10 id=\"b_cast\" onBlur=\"CalculateGross();\"><br>";
echo "<tr align=left><th class=smaller colspan=2><br>Gross Receipts&nbsp;";
echo "<input type=text name=grossreceipts value=\"".number_format($grossreceipts,2,'.','')."\" id=\"grossreceipts\" size=10><br>";
echo "<br><table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=2>Officials:</th></tr>";
echo "<tr align=left><td>Fees: ($70.00 per official)</td>";
echo "<td align=right>= 2a. <input type=text name=offfees value=\"".number_format($offfees,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left><td>Mileage: (one way, one car)$space<input type=text size=3 name=offmiles value=\"$offmiles\"> x $1.00$space</td>";
echo "<td align=right>= 2b. <input type=text name=offmilespaid value=\"".number_format($offmilespaid,2,'.','')."\" size=8></td></tr>";
echo "<input type=hidden name=mileageshouldbe value=\"".$offmiles*1.00."\">";
$mileageshouldbe=$offmiles*1.00;
echo "<tr align=right><td colspan=2>($offmiles x $1.00 = $".number_format($mileageshouldbe,2,'.','').")</td></tr>";
echo "</table><br>";
echo "<table cellspacing=0 cellpadding=3><tr align=left><td>Visitor Mileage (miles one way):</td>";
echo "<td><input type=text size=3 name=visitormiles value=\"$visitormiles\"></td><td bgcolor=#E0E0E0>";
/*
echo "<form target=\"_blank\" name=\"frmGetMileage\" method=\"post\">";
echo "&nbsp;&nbsp;Calculate Mileage:<br>&nbsp;&nbsp;(<a class=tiny target=\"_blank\" href=\"http://randmcnally.com\">randmcnally.com</a>)</td>";
echo "<td bgcolor=#E0E0E0>Starting City (do NOT include State)<br>";
echo "<input type=text name=\"txtStartCity\" maxlength=\"100\" size=25></td>";
echo "<td bgcolor=#E0E0E0>Destination City (do NOT include State)<br>";
echo "<input type=text name=\"txtDestCity\" value=\"$hostcity\" maxlength=100 size=25></td>";
echo "<td bgcolor=#E0E0E0><input type=button onclick=\"window.open('http://www.randmcnally.com/rmc/directions/dirGetMileage.jsp?txtStartCity='+ txtStartCity.value +'&txtStartState=NE&txtDestCity='+ txtDestCity.value +'&txtDestState=NE','RandMcNally','width=800,height=600');\" value=\"Get Mileage\">";
echo "<input type=hidden name=\"txtStartState\" value=\"NE\">";
echo "<input type=hidden name=\"txtDestState\" value=\"NE\"></form>
*/
echo "<a target=\"_blank\" href=\"http://www.randmcnally.com/rmc/directions/dirGetMileage.jsp?txtStartState=NE&txtDestCity=$hostcity&txtDestState=NE\">Calculate Mileage (RandMcNally.com)</a>";
echo "</td></tr></table>";
echo "<br>";
echo "Number on Opponent's Roster Plus 1 Coach:&nbsp;";
echo "<input type=text size=2 name=partyct value=\"$partyct\">&nbsp;&nbsp;";
echo "(See Chart Below for Maximum #, which INCLUDES the coach)<br>";
echo "<table cellspacing=0 cellpadding=2 border=1 bordercolor=#000000>";
echo "<tr align=left><td><b>Class</b><td><b>Max #</b></td></tr>";
echo "<tr align=left><td>A/B</td><td>42</td></tr>";
echo "<tr align=left><td>C1/C2</td><td>38</td></tr>";
echo "<tr align=left><td>D1</td><td>28</td></tr>";
echo "<tr align=left><td>D2</td><td>24</td></tr></table>";
   //get "per mile" allotment
   if($partyct>=13 && $partyct<=18)
      $permile="2.43";
   if($partyct>=19 && $partyct<=24)
      $permile="3.40";
   else if($partyct>=25 && $partyct<=30)
      $permile="4.25";
   else if($partyct>=31)
      $permile="5.10";
if($error==1)
   echo "<br><font style=\"color:red\"><b>You MUST enter the number of individuals in your official traveling party.</b></font>";
echo "</th></tr>";
echo "<tr align=right><th colspan=2><input type=submit name=calculate value=\"Calculate\"></th></tr>";
echo "<tr align=center><th colspan=2><hr></th></tr>";

//#1
/* echo "<tr><th colspan=2 align=right class=smaller>$space 1. Gate Receipts$space &nbsp;";
echo "<input type=text  size=10 name=gate value=\"".number_format($gate,2,'.','')."\"></th></tr>";
echo "<tr><th colspan=2 align=right class=smaller>$space 2. Broadcast Receipts$space &nbsp;";
echo "<input type=text  size=10 name=b_cast value=\"".number_format($b_cast,2,'.','')."\"></th></tr>";
 */
echo "<tr><th colspan=2 align=right class=smaller>$space 1. Gross Receipts$space &nbsp;";
echo "<input type=text readOnly=true size=10 name=grossreceipts2 value=\"".number_format($grossreceipts,2,'.','')."\"></th></tr>";

//#2
echo "<tr><th colspan=2 align=right class=smaller>$space 2. Total Officials' Payment$space &nbsp;";
$offtotal=$offfees+$offmilespaid;
$offtotal=number_format($offtotal,2,'.','');
echo "<input type=text readOnly=true size=10 name=offtotal value=\"$offtotal\"></th></tr>";

//#3
echo "<tr align=left><th class=smaller><br>Insurance Deduction:&nbsp;(line #1 x 0.10)</th>";
$insdeduct=$grossreceipts*0.10;
$insdeduct=number_format($insdeduct,2,'.','');
echo "<th align=right class=smaller>$space 3. Insurance$space &nbsp;";
echo "<input type=text readOnly=true size=10 name=insdeduct value=\"$insdeduct\"></th></tr>";

//#4
echo "<tr align=left><th class=smaller><br>Balance Before Distribution (line #1 minus lines #2 & #3)</th>";
$balance=$grossreceipts-$offtotal-$insdeduct;
if($balance<0) $balance=0;
$balance=number_format($balance,2,'.','');
echo "<th align=right class=smaller>$space 4. Balance$space &nbsp;";
echo "<input type=text name=balance readOnly=true size=10 value=\"$balance\"></th></tr>";

//#5
echo "<tr align=left valign=bottom><th>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=2><br>Distribution:</th></tr>";
echo "<tr align=left><td>Host School Allowance (line #1 x 0.25)</td>";
$hostallow=$grossreceipts*0.25;
$hostallow=number_format($hostallow,2,'.','');
echo "<td align=right>= 5a. <input type=text readOnly=true name=hostallow value=\"$hostallow\" size=8></td></tr>";
echo "<tr align=left><td>NSAA Allowance (line #1 x 0.25)</td>";
$nsaaallow=$grossreceipts*0.25;
$nsaaallow=number_format($nsaaallow,2,'.','');
echo "<td align=right>= 5b. <input type=text readOnly=true name=nsaaallow value=\"$nsaaallow\" size=8></td></tr>";
echo "<tr align=left><td>Visitors<table cellspacing=0 cellpadding=0>";
echo "<tr align=left><td>1. Mileage (miles one way): ($visitormiles";
echo " - 50 miles) x $$permile= ";
$visitormilespaid=($visitormiles-50)*$permile;
if($visitormilespaid<0) $visitormilespaid=0;
$visitormilespaid=$visitormilespaid;
$visitormilespaid=number_format($visitormilespaid,2,'.','');
echo "<input type=text readOnly=true name=visitormilespaid value=\"$visitormilespaid\" size=8></td></tr>";
$tenperc=$grossreceipts*0.10;
$tenperc=number_format($tenperc,2,'.','');
echo "<tr align=left><td>2.  10% of Gross Receipts: <input type=text readOnly=true name=tenperc value=\"$tenperc\" size=8>";
echo "</td></tr></table></td></tr>";
if($tenperc>$visitormilespaid) $visitorpaid=$tenperc;
else $visitorpaid=$visitormilespaid;
if($visitorpaid<=0) $visitorpaid=0;
$visitorpaid=number_format($visitorpaid,2,'.','');
echo "<tr align=left><td>Total Visitor's Mileage OR 10% of Gross Receipts (line #1):</td>";
echo "<td align=right>= 5c. <input type=text readOnly=true name=visitorpaid value=\"$visitorpaid\" size=8></td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space 5. Total Distribution$space &nbsp;";
$distribution=$hostallow+$nsaaallow+$visitorpaid;
$distribution=number_format($distribution,2,'.','');
echo "<input type=text readOnly=true name=distribution value=\"$distribution\" size=10></th></tr>";

//#6
echo "<tr align=left valign=bottom><th>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=3><br>Distribution Prorated (if necessary)</th></tr>";
if($balance<$distribution)	//need to prorate
{ 
   $prorate=$balance/$distribution;
   $prorateperc=number_format($prorate*100,4,'.','');
   $hostallowpro=number_format($hostallow*$prorate,2,'.','');
   $visitorpaidpro=number_format($visitorpaid*$prorate,2,'.','');
   $nsaaallowpro=number_format($balance-$hostallowpro-$visitorpaidpro,2,'.','');
   if($nsaaallowpro>$nsaaallow*$prorate) $nsaaallowpro=number_format($nsaaallow*$prorate,2,'.','');
}
else  //no prorating
{
   $prorate=0;
   $prorateperc="100.00";
   $hostallowpro=$hostallow;
   $nsaaallowpro=$nsaaallow;
   $visitorpaidpro=$visitorpaid;
}
echo "<input type=hidden name=prorateperc value=\"$prorateperc\">";
echo "<tr align=left valign=bottom><td>Host School Allowance</td>";
echo "<td>$space 5a. x ";
if($prorate>0) echo "$prorateperc%";
echo "$space</td>";
echo "<td align=right>= 6a. <input type=text readOnly=true name=hostallowpro value=\"";
if($prorate>0) echo "$hostallowpro";
else echo "0.00";
echo "\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>NSAA Allowance</td>";
echo "<td>$space 5b. x ";
if($prorate>0) echo "$prorateperc%";
echo "$space</td>";
echo "<td align=right>= 6b. <input type=text readOnly=true name=nsaaallowpro value=\"";
if($prorate>0) echo "$nsaaallowpro";
else echo "0.00";
echo "\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>Visitor's Mileage</td>";
echo "<td>$space 5c. x ";
if($prorate>0) echo "$prorateperc%";
echo "$space</td>";
echo "<td align=right>= 6c. <input type=text readOnly=true name=visitorpaidpro value=\"";
if($prorate>0) echo "$visitorpaidpro";
else echo "0.00";
echo "\" size=8></td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space 6. Total Prorated Distribution$space &nbsp;";
$distributionpro=$hostallowpro+$nsaaallowpro+$visitorpaidpro;
$distributionpro=number_format($distributionpro,2,'.','');
echo "<input type=text readOnly=true name=distributionpro value=\"";
if($prorate>0) echo "$distributionpro";
else echo "0.00";
echo "\" size=10></th></tr>";

//#7
echo "<tr align=left valign=top><th>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=2><br>Bonus Balance</th></tr>";
$bonus=$balance-$distribution;
if($bonus<0) $bonus=0;
$bonus=number_format($bonus,2,'.','');
$gate=number_format($gate,2,'.','');
if($bonus>0)
{
   $hostbonus=number_format($bonus*0.40,2,'.','');
   $visitorbonus=number_format($bonus*0.40,2,'.','');
   $nsaabonus=number_format($bonus-$hostbonus-$visitorbonus,2,'.','');
}
else
{
   $hostbonus="0.00";
   $visitorbonus="0.00";
   $nsaabonus="0.00";
}
echo "<tr align=left valign=bottom><td>Host School Share (line #7 x 0.40)</td>";
echo "<td align=right>$space = 7a. <input type=text readOnly=true name=hostbonus value=\"$hostbonus\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>Visiting School Share (line #7 x 0.40)</td>";
echo "<td align=right>$space = 7b. <input type=text readOnly=true name=visitorbonus value=\"$visitorbonus\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>NSAA Share (line #7 x 0.20)</td>";
echo "<td align=right>$space = 7c. <input type=text readOnly=true name=nsaabonus value=\"$nsaabonus\" size=8></td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space 7. Bonus Balance$space &nbsp;";
echo "<input type=text readOnly=true name=bonus value=\"$bonus\" size=10></th></tr>";

//Summary
echo "<tr align=center><td colspan=2><hr></td></tr>";
$hosttotal=$hostallowpro+$hostbonus;
$hosttotal=number_format($hosttotal,2,'.','');
$visitortotal=$visitorpaidpro+$visitorbonus;
$visitortotal=number_format($visitortotal,2,'.','');
$nsaapartial=number_format($nsaaallowpro+$nsaabonus,2,'.','');
$nsaatotal=$nsaaallowpro+$nsaabonus+$insdeduct;
$nsaatotal=number_format($nsaatotal,2,'.','');
echo "<tr align=right><th class=smaller align=left><font style=\"color:red\">Write a check to Visiting School for <font size=2><u>$$visitortotal</u></font> and send a copy of this form with the check to the school.<br><br>Write a check to NSAA for <font size=2><u>$$nsaatotal</u></font> and send a copy of this form with the check to the NSAA.<br><br>";
echo "<font style=\"font-size:10pt\">***DON'T FORGET TO CLICK \"Submit Report\" BELOW IN ORDER FOR THIS FORM TO BE SENT TO THE NSAA!!!</font></font></th>";
echo "<th align=right><table><caption align=left><b>SUMMARY:</b></caption>";
echo "<tr align=left><th class=smaller>Officials:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=officialstotal value=\"$offtotal\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>Hosting School:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=hosttotal value=\"$hosttotal\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>Visiting School:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=visitortotal value=\"$visitortotal\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>NSAA:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=nsaapartial value=\"$nsaapartial\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>Insurance:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=insurance value=\"$insdeduct\" size=10></th></tr>";
echo "<input type=hidden name=nsaatotal value=\"$nsaatotal\">";
$total=$hosttotal+$visitortotal+$nsaatotal+$offtotal;
$total=number_format($total,2,'.','');
echo "<tr align=left><th class=smaller>TOTAL GATE:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=total value=\"$total\" size=10></th></tr>";
echo "</table></th></tr>";

//LIVE VIDEO BROADCAST FORM:
echo "<tr><td align=left colspan=2>
        <h4>LIVE Video Broadcasts:</h4>
        <p><input type=radio name=\"livebcasts\" id=\"livebcastsYES\" value=\"Yes\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display=''; }\"> <b>YES</b>, LIVE Video Broadcasts were performed at my event.</p>
        <p><input type=radio name=\"livebcasts\" id=\"livebcastsNO\" value=\"No\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display='none'; }\"> <b>NO</b>, LIVE Video Broadcasts were not performed at my event.</p>
        <div id=\"bcastsdiv\" style=\"display:none;\">
        <p>Please complete the following section regarding <b><u>LIVE Video Broadcasts</b></u> that were performed at this event. ALL Broadcast request forms should be copied and submitted to the NSAA office via fax (402) 489-0934 or email <a href=\"mailto:jstauss@nsaahome.org\">jstauss@nsaahome.org</a>.</p>";
	echo "<p style=\"background-color:yellow;\">Fields in yellow are REQUIRED.</p>";
        //LOCAL MEDIA
echo "<br />
        <table cellspacing=0 cellpadding=5>
        <tr align=left><td colspan=2><p><b>STUDENT BROADCAST GROUPS:</b></p></td></tr>
        <tr bgcolor='yellow'><td align=right>Number of GAMES Broadcasted:</td><td align=left><input type=text size=2 name=\"localmedia_bcasts\" value=\"$localmedia_bcasts\" id=\"localmedia_bcasts\" onBlur=\"CalculateBcasts();\"></td></tr>
        <tr><td align=right>Total Broadcast Fees*:</td><td align=left>$<input type=text size=5 name=\"localmedia_bfee\" value=\"$localmedia_bfee\" id=\"localmedia_bfee\" onBlur=\"CalculateBcasts();\"> (at $100 per broadcast)</td></tr>
        <!--<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>-->";
        //OTHER MEDIA
        echo "<tr><td align=left colspan=2><p><b>LOCAL MEDIA BROADCAST GROUPS:</b></p></td></tr>";
        echo "<tr bgcolor='yellow'><td align=right>Number of GAMES Webcasted:</td><td align=left><input type=text size=2 name=\"othermedia_wcasts\" value=\"$othermedia_wcasts\" id=\"othermedia_wcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
        echo "<tr><td align=right>Total Webcast Fees**:</td><td align=left>$<input type=text size=5 name=\"othermedia_wfee\" value=\"$othermedia_wfee\" id=\"othermedia_wfee\" onBlur=\"CalculateBcasts();\"> (at $150 per webcast)</td></tr>";
        echo "<tr bgcolor='yellow'><td align=right>Number of GAMES Telecasted (TV):</td><td align=left><input type=text size=2 name=\"othermedia_tcasts\" value=\"$othermedia_tcasts\" id=\"othermedia_tcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
        echo "<tr><td align=right>Total Telecast Fees**:</td><td align=left>$<input type=text size=6 name=\"othermedia_tfee\" value=\"$othermedia_tfee\" id=\"othermedia_tfee\" onBlur=\"CalculateBcasts();\"> (at $250 per telecast)</td></tr>";
        //echo "<tr><td align=left colspan=2><p><i>** The fees above will be invoiced by the NSAA office and receipts payable to the NSAA.</i></p></td></tr>";
        echo "<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>";
echo "</table>
        </div>
        </td></tr>";


echo "<tr align=center><td colspan=2><p style=\"text-align:left;padding:10px 150px;color:blue\"><b>Please double-check that the information above is COMPLETE and ACCURATE before clicking \"Submit Report\".  You will NOT be able to make changes once your report is submitted.<br><br>When you click \"Submit Report\", you will be taken to the printer-friendly version of your completed form.  Please print out the form and send copies of it with your checks to the NSAA and the visiting schools.</b></p>";
echo "<input type=hidden name=\"hiddenupdate\" id=\"hiddenupdate\"><input type=\"button\" id=\"update\" name=\"update\" class=\"fancybutton\" value=\"Submit Report\" onClick=\"if(ErrorCheckBcasts()) { Utilities.getElement('hiddenupdate').value='1'; submit(); } else { alert ('You must either check the box saying NO LIVE Video Broadcasts were performed at your event OR enter the number of broadcasts performed.'); }\"></td></tr>";

echo "</table></form>";
echo $end_html;
?>
