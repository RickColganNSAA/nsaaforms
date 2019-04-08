<?php
$space="&nbsp;&nbsp;&nbsp;";

require '../functions.php';
require '../variables.php';

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

echo $init_html;
echo $header;
echo "<form method=post action=\"fbfinance.php\" name=fbform>";
echo "<center><br><br><font size=2><b>Football Financial Report:</b></font>";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table cellspacing=0 cellpadding=0><caption><hr></caption>";

//#1
echo "<tr align=left><th class=smaller colspan=2>";
echo "Class $class, ";
echo "<input type=hidden name=class value=$class>";
switch($round)
{
   case 1:
      echo "First Round";
      break;
   case 2:
      echo "Second Round";
      break;
   case 3:
      echo "Quarterfinals";
      break;
   case 4:
      echo "Semifinals";
      break;
}
echo "$space AT $space<input type=text name=location value=\"$location\" size=30>";
echo "</th></tr>";
?>
<script language="JavaScript">
function round(number,X)
{
   return number.toFixed(X);
}
</script>

<?php
 
echo "<tr align=left><th class=smaller>Attendance:&nbsp;&nbsp;<input type=text name=attendance size=6 value=\"$attendance\">$space$space";
echo "Visiting Team:&nbsp;&nbsp;<input type=text name=visitor value=\"$visitor\" size=30></th>";
echo "<th class=smaller align=right>$spaceGross Receipts$space#1&nbsp;";
echo "<input type=text name=grossreceipts value=\"".number_format($grossreceipts,2,'.','')."\" size=10 onchange='fbform.insdeduct.value=round(this.value*.08,2); fbform.balance.value=round(this.value-fbform.insdeduct.value-fbform.offtotal.value,2); fbform.hostallow.value=round(this.value/4,2); fbform.nsaaallow.value=round(this.value/4,2); if(fbform.visitormilespaid.value<this.value/10) { fbform.visitorpaid.value=round(this.value/10,2); } else { fbform.visitorpaid.value=round(fbform.visitormilespaid.value,2); } fbform.tenperc.value=round(this.value/10,2); fbform.distribution.value=round(fbform.hostallow.value+fbform.nsaaallow.value+fbform.visitorpaid.value,2); if(fbform.distribution.value>fbform.balance.value) { fbform.prorate.value=fbform.balance.value/fbform.distribution.value; fbform.hostallowpro.value=fbform.prorate.value*fbform.hostallow.value; fbform.nsaaallowpro.value=fbform.prorate.value*fbform.nsaaallow.value; fbform.visitorpaidpro.value=fbform.prorate.value*fbform.visitorpaid.value; } else { fbform.hostallowpro.value=fbform.hostallow.value; fbform.nsaaallowpro.value=nsaaallow.value; visitorpaidpro.value=visitorpaid.value; } fbform.distribution.pro=round(fbform.hostallowpro.value+fbform.nsaaallowpro.value+fbform.visitorpaidpro.value,2); fbform.bonus.value=round(fbform.balance.value-fbform.distributionpro.value,2); if(fbform.bonus.value<0) { fbform.bonus.value=0.00; } fbform.hostbonus.value=round(fbform.bonus.value*0.4,2); fbform.visitorbonus.value=round(fbform.bonus.value*0.4,2); fbform.nsaabonus.value=round(fbform.bonus.value-fbform.hostbonus.value-fbform.visitorbonus.value,2); fbform.hosttotal.value=round(fbform.hostallowpro.value+fbform.hostbonus.value,2); fbform.visitortotal.value=round(fbform.visitorpaidpro.value+fbform.visitorbonus.value,2); fbform.nsaatotal.value=round(fbform.insdeduct.value+fbform.nsaaallowpro.value+fbform.nsaabonus.value,2);'></th></tr>";

//#2
echo "<tr align=left valign=bottom><th><br>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=2>Officials:</th></tr>";
echo "<tr align=left><td>Fees: ($55.00 per official)</td>";
echo "<td align=right>= 2a. <input type=text name=offfees value=\"".number_format($offfees,2,'.','')."\" size=8 onchange='fbform.offtotal.value=round(this.value+fbform.offmilespaid.value,2);'></td></tr>";
echo "<tr align=left><td>Mileage: (one way, one car)$space<input type=text size=3 name=offmiles value=\"$offmiles\"> x $0.76$space</td>";
echo "<td align=right>= 2b. <input type=text name=offmilespaid value=\"".number_format($offmilespaid,2,'.','')."\" size=8></td></tr>";
echo "<input type=hidden name=mileageshouldbe value=\"".$offmiles*0.76."\">";
$mileageshouldbe=$offmiles*0.76;
echo "<tr align=right><td colspan=2>($offmiles x $0.76 = $".number_format($mileageshouldbe,2,'.','').")</td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space Total Officials' Payment$space #2&nbsp;";
$offtotal=$offfees+$offmilespaid;
echo "<input type=text readOnly=true size=10 name=offtotal value=\"".number_format($offtotal,2,'.','')."\"></th></tr>";

//#3
echo "<tr align=left><th class=smaller><br>Insurance Deduction:&nbsp;(line #1 x 0.08)</th>";
$insdeduct=$grossreceipts*0.08;
$insdeduct=$insdeduct;
echo "<th align=right class=smaller>$space Insurance$space #3&nbsp;";
echo "<input type=text readOnly=true size=10 name=insdeduct value=\"".number_format($insdeduct,2,'.','')."\"></th></tr>";

//#4
echo "<tr align=left><th class=smaller><br>Balance Before Distribution (line #1 minus lines #2 & #3)</th>";
$balance=$grossreceipts-$offtotal-$insdeduct;
if($balance<0) $balance=0;
echo "<th align=right class=smaller>$space Balance$space #4&nbsp;";
echo "<input type=text name=balance readOnly=true size=10 value=\"".number_format($balance,2,'.','')."\"></th></tr>";

//#5
echo "<tr align=left valign=bottom><th>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=2><br>Distribution:</th></tr>";
echo "<tr align=left><td>Host School Allowance (line #1 x 0.25)</td>";
$hostallow=$grossreceipts*0.25;
echo "<td align=right>= 5a. <input type=text readOnly=true name=hostallow value=\"".number_format($hostallow,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left><td>NSAA Allowance (line #1 x 0.25)</td>";
$nsaaallow=$grossreceipts*0.25;
echo "<td align=right>= 5b. <input type=text readOnly=true name=nsaaallow value=\"".number_format($nsaaallow,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left><td>Visitors<table cellspacing=0 cellpadding=0>";
echo "<tr align=left><td>1. Mileage (miles one way): (<input type=text name=visitormiles value=\"$visitormiles\" size=3 onchange='fbform.visitormilespaid.value=round((this.value-50)*4.2,2); if(fbform.visitormilespaid.value<fbform.grossreceipts.value*0.1) { fbform.visitorpaid.value=round(fbform.grossreceipts.value*0.1,2); } else { fbform.visitorpaid.value=round(fbform.visitormilespaid.value,2); }'>";
echo " - 50 miles) x $4.20 = ";
$visitormilespaid=($visitormiles-50)*4.20;
if($visitormilespaid<0) $visitormilespaid=0;
$visitormilespaid=$visitormilespaid;
echo "<input type=text readOnly=true name=visitormilespaid value=\"".number_format($visitormilespaid,2,'.','')."\" size=8></td></tr>";
$tenperc=$grossreceipts*0.10;
$tenperc=$tenperc;
echo "<tr align=left><td>2.  10% of Gross Receipts: <input type=text readOnly=true name=tenperc value=\"".number_format($tenperc,2,'.','')."\" size=8>";
echo "</td></tr></table></td></tr>";
if($tenperc>$visitormilespaid) $visitorpaid=$tenperc;
else $visitorpaid=$visitormilespaid;
if($visitorpaid<=0) $visitorpaid=0;
$visitorpaid=$visitorpaid;
echo "<tr align=left><td>Total Visitor's Mileage OR 10% of Gross Receipts (line #1):</td>";
echo "<td align=right>= 5c. <input type=text readOnly=true name=visitorpaid value=\"".number_format($visitorpaid,2,'.','')."\" size=8></td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space Total Distribution$space #5&nbsp;";
$distribution=$hostallow+$nsaaallow+$visitorpaid;
echo "<input type=text readOnly=true name=distribution value=\"".number_format($distribution,2,'.','')."\" size=10></th></tr>";

//#6
echo "<tr align=left valign=bottom><th>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=3><br>Distribution Prorated (if necessary)</th></tr>";
if($balance<$distribution)	//need to prorate
{ 
   $prorate=$balance/$distribution;
   $prorateperc=$prorate*100;
   $hostallowpro=$hostallow*$prorate;
   $nsaaallowpro=$nsaaallow*$prorate;
   $visitorpaidpro=$visitorpaid*prorate;
}
else	//no prorating
{
   $prorate=0;
   $prorateperc="100.00";
   $hostallowpro=$hostallow;
   $nsaaallowpro=$nsaaallow;
   $visitorpaidpro=$visitorpaid;
}
echo "<tr align=left valign=bottom><td>Host School Allowance</td>";
echo "<td>$space 5a. x $prorateperc%$space</td>";
echo "<td align=right>= 6a. <input type=text readOnly=true name=hostallowpro value=\"".number_format($hostallowpro,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>NSAA Allowance</td>";
echo "<td>$space 5b. x $prorateperc%$space</td>";
echo "<td align=right>= 6b. <input type=text readOnly=true name=nsaaallowpro value=\"".number_format($nsaaallowpro,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>Visitor's Mileage</td>";
echo "<td>$space 5c. x $prorateperc%$space</td>";
echo "<td align=right>= 6c. <input type=text readOnly=true name=visitorpaidpro value=\"".number_format($visitorpaidpro,2,'.','')."\" size=8></td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space Total Prorated Distribution$space #6&nbsp;";
$distributionpro=$hostallowpro+$nsaaallowpro+$visitorpaidpro;
$distributionpro=$distributionpro;
echo "<input type=text readOnly=true name=distributionpro value=\"".number_format($distributionpro,2,'.','')."\" size=10></th></tr>";

//#7
echo "<tr align=left valign=top><th>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><th class=smaller colspan=2><br>Bonus Balance</th></tr>";
$bonus=$balance-$distribution;
if($bonus<0) $bonus=0;
if($bonus>0)
{
   $hostbonus=$bonus*0.40;
   $visitorbonus=$bonus*0.40;
   $nsaabonus=$bonus-$hostbonus-$visitorbonus;
}
else
{
   $hostbonus="0.00";
   $visitorbonus="0.00";
   $nsaabonus="0.00";
}
echo "<tr align=left valign=bottom><td>Host School Share (line #7 x 0.40)</td>";
echo "<td align=right>$space = 7a. <input type=text readOnly=true name=hostbonus value=\"".number_format($hostbonus,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>Visiting School Share (line #7 x 0.40)</td>";
echo "<td align=right>$space = 7b. <input type=text readOnly=true name=visitorbonus value=\"".number_format($visitorbonus,2,'.','')."\" size=8></td></tr>";
echo "<tr align=left valign=bottom><td>NSAA Share (line #7 x 0.20)</td>";
echo "<td align=right>$space = 7c. <input type=text readOnly=true name=nsaabonus value=\"".number_format($nsaabonus,2,'.','')."\" size=8></td></tr>";
echo "</table></th>";
echo "<th align=right class=smaller>$space Bonus Balance$space #7&nbsp;";
echo "<input type=text readOnly=true name=bonus value=\"".number_format($bonus,2,'.','')."\" size=10></th></tr>";

//Summary
echo "<tr align=center><td colspan=2><hr></td></tr>";
$hosttotal=$hostallowpro+$hostbonus;
$visitortotal=$visitorpaidpro+$visitorbonus;
$nsaatotal=$nsaaallowpro+$nsaabonus+$insdeduct;
echo "<tr align=right><th>&nbsp;</th>";
echo "<th align=right><table><caption align=left><b>SUMMARY:</b></caption>";
echo "<tr align=left><th class=smaller>Officials:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=officialstotal value=\"".number_format($offtotal,2,'.','')."\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>Hosting School:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=hosttotal value=\"".number_format($hosttotal,2,'.','')."\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>Visiting School:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=visitortotal value=\"".number_format($visitortotal,2,'.','')."\" size=10></th></tr>";
echo "<tr align=left><th class=smaller>NSAA:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=nsaatotal value=\"".number_format($nsaatotal,2,'.','')."\" size=10></th></tr>";
$total=$hosttotal+$visitortotal+$nsaatotal;
echo "<tr align=left><th class=smaller>TOTAL GATE:</th>";
echo "<th align=right>$ <input type=text readOnly=true name=total value=\"".number_format($total,2,'.','')."\" size=10></th></tr>";
echo "</table></th></tr>";

echo "<tr ailgn=center><th colspan=2><input type=submit name=update value=\"Submit Report\"></th></tr>";

echo "</table></form>";
echo $end_html;
?>
