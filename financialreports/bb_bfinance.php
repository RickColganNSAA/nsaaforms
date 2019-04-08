<?php
require '../functions.php';
require_once('../variables.php');
require '../../calculate/functions.php';

$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

$sport1="bb_b";
$sport2="bbb";

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
      echo "<a href=\"".$sport1."index.php?session=$session\">Return to Financial Reports</a>";
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
      if(!$disttimesid)
      {
         $sql="SELECT school FROM logins AS t1, $db_name2.".$sport2."districts AS t2 WHERE t1.id=t2.hostid AND t2.id='$distid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $school=$row[school]; $school_ch=$row[school];
      } 
      else //CHECK FOR CLASS A BASKETBALL (disttimes RECORD)
      {
         $sql="SELECT school FROM logins AS t1, $db_name2.".$sport2."disttimes AS t2 WHERE t1.id=t2.hostid AND t2.id='$disttimesid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $school=$row[school]; $school_ch=$row[school];
      }
   }
}
$school2=addslashes($school);

$sql="SELECT id,city_state FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split(",",$row[0]);
$hostcity=trim($temp[0]);

//get dist info from districts table
if($disttimesid>0)	//CLASS A BASKETBALL
   $sql="SELECT t1.*,t2.class,t2.district,t2.type FROM $db_name2.".$sport2."disttimes AS t1,$db_name2.".$sport2."districts AS t2 WHERE t1.distid=t2.id AND t1.id='$disttimesid'";
else
   $sql="SELECT * FROM $db_name2.".$sport2."districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $district=$row[district];
$hostschool=trim($row[hostschool]);
if($hostschool=="") $hostschool="[Host not Available]";
else if(trim($row[site])!='' && trim($row[site])!=$hostschool)
   $hostschool.=" (at $row[site])";
$hostid=$row[hostid]; $hostid2=$row[hostid2];
if($level!=1 && $school!="Test's School")	//BACKWARDS CHECK THAT THIS SCHOOL IS INDEED THE HOST
{
   $sql2="SELECT id,school FROM logins WHERE (id='$hostid' OR id='$hostid2')";
   $result2=mysql_query($sql2);
   $nothost=1;
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[school]==$school) 
      {
         $nothost=0; $curhostid=$row[id];
      }
   }
   if($nothost)
   {
      echo $init_html;
      echo "<table><tr align=center><th><br><br>ERROR: You are NOT the host of $row[type] $class-$district.<br><br>";
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
      echo $end_html;
      exit();
   }
}
if($disttimesid>0)	//CLASS A BASKETBALL
{
   $date=split("-",$row[day]);   
   $dates=date("M j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $sql2="SELECT * FROM ".$sport."sched WHERE distid='$row[distid]' AND gamenum='$row[gamenum]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $bbsch=array(GetMainSchoolName($row2[sid],$sport),GetMainSchoolName($row2[oppid],$sport));
}
else
{
   $dates="";
   $day=split("/",$row[dates]);
   for($i=0;$i<count($day);$i++)
   {
      $date=split("-",$day[$i]);
      $dates.=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0])).", ";
   }
   $dates.=$date[0];
   if($row[dates]=="") $dates="[Date not Available]";
   $bbsch=split(", ",$row[schools]);
   if($row[type]=="District Final")
      $bbsch=split(" VS ",$row[schools]);
}
sort($bbsch);
$type=$row[type]; $class=$row['class']; $district=$row[district];
$site=$row[site];
if($type=="Subdistrict") $round=1;
else if($type=="District") $round=2;
else if($type=="District Final") $round=2;
   else
{
   echo $init_html;
   echo "<table><tr align=center><th><br><br>ERROR: $type $class-$district is NOT a Boys Basketball District.<br><br>";
   if($level==1)
      echo "<a href=\"".$sport2."index.php?session=$session\">Return to Financial Reports</a>";
   else
      echo "<a href=\"../welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}  

$sql="SELECT * FROM finance_".$sport1." WHERE distid='$distid' ";
if($disttimesid>0) //CLASS A BASKETBALL
   $sql.="AND disttimesid='$disttimesid' ";
$sql.="AND school='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)   //already submitted
{
   $submitted=1;
   $row=mysql_fetch_array($result);
   $reportid=$row[id];
}
else $submitted=0;

if($hiddenupdate || $submitted==1) //submit report AND/OR show submitted report
{
   if($hiddenupdate)//if report was submitted, add info to database & show printer-friendly version
   {
      $sql="SELECT * FROM finance_".$sport1." WHERE distid='$distid' ";
      if($disttimesid>0) 	//CLASS A BASKETBALL
         $sql.="AND disttimesid='$disttimesid' ";
      $sql.="AND school='$school2'";
      $result=mysql_query($sql);
      $datesub=time();
      $winner2=addslashes($winner); $runnerup2=addslashes($runnerup);
      if($hostpart=='x') $hostbonus=$parthostbonus;
      else $hostbonus=$nonparthostbonus;
      if(mysql_num_rows($result)==0)	//INSERT
      {
         $sql2="INSERT INTO finance_".$sport1." (nocasts,localmedia_bcasts,localmedia_bfee,othermedia_wcasts,othermedia_wfee,othermedia_tcasts,othermedia_tfee,datesub,school,distid,disttimesid,round,winner,runnerup,attendance,grossreceipts,offfees,offmiles,offmilespaid,offtotal,insurance,hostgiven,hostallow,nsaaallow,balance,vismileagepaid,prorate,bonus,nsaabonus,hostpart,hostgames,hostbonus,visbonus,hosttotal,vistotal,nsaatotal,gate,b_cast) VALUES ('$nocasts','$localmedia_bcasts','$localmedia_bfee','$othermedia_wcasts','$othermedia_wfee','$othermedia_tcasts','$othermedia_tfee','$datesub','$school2','$distid','$disttimesid','$round','$winner2','$runnerup2','$attendance','$grossreceipts','$offfees','$offmiles','$offmilespaid','$offtotal','$insurance','$hostgiven','$hostallow','$nsaaallow','$balance','$vismileage','$prorate','$bonus','$nsaabonus','$hostpart','$hostgames','$hostbonus','$visbonus','$hostsum','$vissum','$nsaasum','$gate','$b_cast')";
	 $result2=mysql_query($sql2);
	 $reportid=mysql_insert_id();
      }
      else				//UPDATE
      {
         $row=mysql_fetch_array($result);
         $reportid=$row[id];
         $sql2="UPDATE finance_".$sport1." SET nocasts='$nocasts',localmedia_bcasts='$localmedia_bcasts',localmedia_bfee='$localmedia_bfee', othermedia_wcasts='$othermedia_wcasts', othermedia_wfee='$othermedia_wfee', othermedia_tcasts='$othermedia_tcasts', othermedia_tfee='$othermedia_tfee', datesub='$datesub',round='$round',winner='$winner2',runnerup='$runnerup2',attendance='$attendance',grossreceipts='$grossreceipts',offfees='$offfees',offmiles='$offmiles',offmilespaid='$offmilespaid',offtotal='$offtotal',insurance='$insurance',hostgiven='$hostgiven',hostallow='$hostallow',nsaaallow='$nsaaallow',balance='$balance',vismileagepaid='$vismileage',prorate='$prorate',bonus='$bonus',nsaabonus='$nsaabonus',hostpart='$hostpart',hostgames='$hostgames',hostbonus='$hostbonus',visbonus='$visbonus',hosttotal='$hostsum',vistotal='$vissum',nsaatotal='$nsaasum',gate='$gate',b_cast='$b_cast' WHERE id='$reportid'";
         $result2=mysql_query($sql2);
      }
      if(mysql_error())
      {
         echo mysql_error()."<br>$sql2";
         exit();
      }
      //visiting schools:
      $sql2="DELETE FROM finance_".$sport1."_exp WHERE reportid='$reportid'";
      $result2=mysql_query($sql2);
      for($i=0;$i<count($bbschool);$i++)
      {
         $var1="row_".$i."_1";
         $var2="row_".$i."_2";
         $var3="row_".$i."_3";
         $var4="row_".$i."_4";
         $var5="row_".$i."_5";
         $var6="row_".$i."_6";
         $var7="row_".$i."_7";
         $var8="row_".$i."_8";
  	 $cursch2=addslashes($bbschool[$i]);
	 $sql="INSERT INTO finance_".$sport1."_exp (reportid,disttimesid,distid,school,miles1way,trips,miles,mileagedue,mileagepaid,matches,bonus,totalpaid) VALUES ('$reportid','$disttimesid','$distid','$cursch2','".$$var1."','".$$var2."','".$$var3."','".$$var4."','".$$var5."','".$$var6."','".$$var7."','".$$var8."')"; 
	 $result=mysql_query($sql);
      }
   }//end if update
   //get submitted info to display:
   $sql="SELECT * FROM finance_".$sport1." WHERE distid='$distid' ";
   if($disttimesid>0) //CLASS A BASKETBALL
      $sql.="AND disttimesid='$disttimesid' ";
   $sql.="AND school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($print!=1)
   {
      if($hiddenupdate)
      {
         echo "<html><head><title>NSAA Home</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\"></head><body onload=\"window.open('".$sport1."finance.php?session=$session&disttimesid=$disttimesid&distid=$distid&print=1');\">";
      }
      else echo $init_html;
      echo GetHeader($session);
      echo "<br>";
      if($level==1)
         echo "<a href=\"".$sport1."index.php?session=$session\" class=small>Return to Financial Reports</a>&nbsp;&nbsp;&nbsp;";
      else if($school=="Test's School")
         echo "<a href=\"index.php?session=$session\" class=small>Return to Financial Reports</a>&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"".$sport1."finance.php?session=$session&disttimesid=$disttimesid&distid=$distid&print=1&school_ch=$school_ch\" class=small target=new>Printer-Friendly Version</a><br><br>";
      echo "This financial report was completed on ".date("F j, Y",$row[datesub]).".  You may no longer make changes to this form.  Please contact the NSAA if you must make a change.  Thank you!<br><br>";
   }
   else
      echo $init_html."<table width=100%><tr align=center><td>";
   echo "<table width=700 class=nine><caption><b>NSAA Boys Basketball Financial Report</b><hr></caption>";
   $today=date("M d, Y",time());
   echo "<tr align=left><td colspan=3><b>School: $space </b>$row[school]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Report Date: $space </b>".date("F j, Y",$row[datesub])."</td></tr>";
   echo "<tr align=left><td colspan=3><b>$type $class-$district</td></tr>";
   echo "<tr align=left><td colspan=3><b>At: $space </b>$site</td></tr>";
   echo "<tr align=left><td colspan=3><b>Dates: $space </b>$dates</td></tr>";
   echo "<tr align=left><td colspan=3><b>Winner: $space </b> $row[winner]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Runner-Up: $space </b> $row[runnerup]</td></tr>";
   echo "<tr align=left><td colspan=3><b>Attendance: $space </b>$row[attendance]</td></tr>";

   //#1
   echo "<tr align=right><td colspan=2><b>1. Gate Receipts  $space $</b></td><td width=50 align=right>".number_format($row[gate],'2','.','')."</td></tr>";
   echo "<tr align=right><td colspan=2><b>2. Broadcast Receipts  $space $</b></td><td width=50 align=right>".number_format($row[b_cast],'2','.','')."</td></tr>";
   echo "<tr align=right><td colspan=2><b>3. Total Receipts  $space $</b></td><td width=50 align=right>".number_format($row[grossreceipts],'2','.','')."</td></tr>";
   //#2
   echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
   echo "<tr align=left valign=center><td>a.&nbsp;Fees ($60.00 each per game):&nbsp;</td>";
   echo "<td>$".number_format($row[offfees],'2','.','')."</td></tr>";
   echo "<tr align=left><td><table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Total Mileage (one way, one car per set of officials):&nbsp;</td>";
   echo "<td>$row[offmiles] miles</td><td>&nbsp;x $".$offmileagerate."</td></tr></table></td>";
   echo "<td>$".number_format($row[offmilespaid],2,'.','')."</td></tr>";
   echo "</table></td></tr>";
   echo "<tr><td colspan=2 align=right><b>4. Officials' Total&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[offtotal],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
   echo "<td align=right><b>5. Insurance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[insurance],2,'.','')."</td></tr>";
   if(!$disttimesid)	//HOST ALLOWANCE FOR NON CLASS A BASKETBALL ONLY
   {
      if($type=="District Final")
         echo "<tr><td align=left><b>Host School Allowance</b> ($50.00)</td>";
      else
         echo "<tr><td align=left><b>Host School Allowance</b> (3 teams $75.00, 4 teams $110.00, 5 teams $145.00, 6 teams $180.00)</td>";
      echo "<td align=right><b>6.Host School Allowance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[hostgiven],2,'.','')."</td></tr>";
   }
   echo "<tr><td align=left><b>Host School</b> (25% of #1, Gross Receipts)</td>";
   echO "<td align=right><b>6. Host School&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[hostallow],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>NSAA</b> (25% of #1, Gross Receipts)</td>";
   echo "<td align=right><b>7. NSAA&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[nsaaallow],2,'.','')."</td></tr>";
   echO "<tr><td align=left><b>Balance</b> (Gross Receipts #3, minus lines #4 through #8)</td>";
   echo "<td align=right><b>8. Balance&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[balance],2,'.','')."</td></tr>";
   echo "<tr><td align=left><b>Mileage Paid to Visiting Schools</b> (Total of Column C below)</td>";
   echo "<td align=right><b>9.Mileage&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[vismileagepaid],2,'.','')."</td></tr>";
   echo "<tr align=center><td colspan=3>";
   //Visiting Schools:
   echo "<table cellspacing=0 cellpadding=4 border=1 bordercolor=#000000>";
   echo "<tr align=left><td colspan=9 align=left><b>Mileage Paid to Competing Schools:</b></td></tr>";
   echo "<tr align=center><th class=smaller rowspan=2><b>School</b></th>";
   echo "<th class=smaller colspan=3>(A)</th><th class=smaller>(B)</th><th class=smaller>(C)</th>";
   echo "<th class=smaller>(D)</th><th class=smaller>(E)</th><th class=smaller>(F)</th></tr>";
   echo "<tr align=center><th class=small>Miles<br>1 Way</th><th class=small>No.<br>Trips</th><th class=small>Total<br>Miles</th>";
   echo "<th class=small>Mileage Due<br>($1.70 x Total Mi)</th><th class=small>Mileage Paid<br>(100% or Prorated)</th>";
   echo "<th class=small>Games<br>Played</th><th class=small>Bonus</th><th class=small>Total Amt<br>Paid to Teams</th></tr>";
   $sql2="SELECT * FROM finance_".$sport1."_exp WHERE distid='$distid' AND reportid='$reportid' ORDER BY id";
   $result2=mysql_query($sql2);
   $miles1way=0; $trips=0; $miles=0; $mileagedue=0; $mileagepaid=0; $matches=0; $bonus=0; $totalpaid=0;
   while($row2=mysql_fetch_array($result2))
   {
     echo "<tr align=center><td align=left>$row2[school]</td>";
     echo "<td>$row2[miles1way]</td>";
	$miles1way+=$row2[miles1way];
     echo "<td>$row2[trips]</td>";
	$trips+=$row2[trips];
     echo "<td>$row2[miles]</td>";
	$miles+=$row2[miles];
     echo "<td>".number_format($row2[mileagedue],'2','.','')."</td>";
	$mileagedue+=$row2[mileagedue];
     echo "<td>".number_format($row2[mileagepaid],'2','.','')."</td>";
	$mileagepaid+=$row2[mileagepaid];
     echo "<td>$row2[matches]</td>";
        $matches+=$row2[matches];
     echo "<td>".number_format($row2[bonus],'2','.','')."</td>";
	$bonus+=$row2[bonus];
     echo "<td>".number_format($row2[totalpaid],'2','.','')."</td>";
	$totalpaid+=$row2[totalpaid];
     echo "</tr>";
   } 
   echo "<tr align=center><td align=right><b>Totals</b></td>";
   echo "<td>$miles1way</td>";
   echo "<td>$trips</td>";
   echo "<td>$miles</td>";
   $mileagedue=number_format($mileagedue,'2','.','');
   echo "<td>$mileagedue</td>";
   $mileagepaid=number_format($mileagepaid,'2','.','');
   echo "<td>$mileagepaid</td>";
   echo "<td>$matches</td>";
   $bonus=number_format($bonus,'2','.','');
   echo "<td>$bonus</td>";
   $totalpaid=number_format($totalpaid,'2','.','');
   echo "<td>$totalpaid</td>";
   echo "</tr>";
   echo "</table></td></tr>";
   echo "<tr align=left valign=top><td><b>Balance for Bonus</b> (#9 minus #10) To be distributed as specified below.<br>";
   if($row[hostpart]=='x')
   {
      echo "<table><tr align=left><td><u>Participating</u> Host School ($row[hostgames] Games Played):</td>";
      echo "<td>$".number_format($row[hostbonus],2,'.','')."</td></tr>";
      echo "<tr align=left><td>Schools, 90% of #9 (inclues participating host school's bonus):</td>";
      echo "<td>$".number_format($row[visbonus],2,'.','')."</td></tr>";
      if($row[visbonus]>0)
      {
	 $matches+=$row[hostgames];
         echo "<tr align=left><td colspan=2>(Each school will receive $".number_format($row[visbonus]/$matches,2,'.','')." per game.)</td></tr>";
      }
      echo "<tr align=left><td>NSAA, 10% of #9:</td>";
      echo "<td>$".number_format($row[nsaabonus],2,'.','')."</td></tr></table>";
   }
   else 
   {
      echo "<table><tr align=left><td>Host School, 15% of #9</td><td>$".number_format($row[hostbonus],2,'.','')."</td></tr>";
      echo "<tr align=left><td>Schools, 75% of #9</td><td>$".number_format($row[visbonus],2,'.','')."</td></tr>";
      if($row[visbonus]>0)
         echo "<tr align=left><td colspan=2>(Each school will receive $".number_format($row[visbonus]/$matches,2,'.','')." per game.)</td></tr>";
      echo "<tr align=left><td>NSAA, 10% of #9</td><td>$".number_format($row[nsaabonus],2,'.','')."</td></tr>";
      echo "</table>";
   }
   //echo "</td><td align=right><b>Bonus&nbsp;&nbsp;#9 $space $</b></td><td align=right>".number_format($row[bonus],2,'.','')."</td></tr>";
   echo "</td><td align=right><b>10. Bonus&nbsp;&nbsp; $space $</b></td><td align=right>".number_format($row[bonus],2,'.','')."</td></tr>";

   //SUMMARY
   echo "<tr><td colspan=2>&nbsp;";
   echo "</td><td align=center><b>SUMMARY</b></td></tr>";
   echo "<tr valign=center><td rowspan=6 align=right>&nbsp;";
   echo "<table width=450 border=1 bordercolor=\"red\" cellspacing=0 cellpadding=5><tr align=left><td class=nine>";
   $nsaacheck=number_format($row[insurance]+$row[nsaaallow]+$row[nsaabonus],'2','.','');
   echo "<font style=\"color:red;\"><b>Write a check to NSAA for <font style=\"font-size:11pt\"><u>$".$nsaacheck."</u></font> and send a copy of this form with the check to the NSAA.<br>";
   echo "<br>Write a check to each school for the amount shown in Column F and send a copy of this form with the check to each school.</font></b></td></tr></table>";
   echo "</td>";
   echo "<td align=right>Officials $space $</td><td align=right width=50>".number_format($row[offtotal],2,'.','')."</td></tr>";
   echo "<tr><td align=right>Host $space $</td><td align=right>".number_format($row[hosttotal],2,'.','')."</td></tr>";
   echo "<tr><td align=right>Schools' Total $space $</td><td align=right>".number_format($row[vistotal],2,'.','')."</td></tr>";
   echo "<tr><td align=right>NSAA $space $</td><td align=right>".number_format($row[nsaatotal],2,'.','')."</td></tr>";
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
      echo "&nbsp;<font style=\"font-size:11pt;\">$".$wtfee."</font></p><p style=\"color:red\"><i>Competitor/Other Media & Affiliated Student Broadcast Fees will be invoiced by the NSAA and receipts payable to the NSAA.</i></p>";
   }
   else echo "&nbsp;<font style=\"font-size:11pt;\">N/A</font></p>";
   echo "</td></tr></table>"; */
   echo "</td>";
   echo "<td align=right colspan=2><br /><b>LIVE VIDEO BROADCASTS</b></td></tr>";
   //echo "<tr><td align=left colspan=2>$space <b>Local Media/Unaffiliated</b></td></tr>";
   echo "<tr><td align=left colspan=2>$space <b>Student Groups</b></td></tr>";
   echo "<tr><td align=right>$row[localmedia_bcasts] x $100 = $</td><td align=right>".number_format($row[localmedia_bfee],2,'.','')."</td></tr>";
   echo "<td align=left colspan=2>$space <b>Local Media Groups</b></td></tr>";
   echo "<td align=left colspan=2>$space Webcasts:</td></tr>";
   echo "<tr><td align=right>$row[othermedia_wcasts] x $150 = $</td><td align=right>".number_format($row[othermedia_wfee],2,'.','')."</td></tr>";
   echo "<td align=left colspan=2>$space Telecasts (TV):</td></tr>";
   echo "<tr><td align=right>$row[othermedia_tcasts] x $250 = $</td><td align=right>".number_format($row[othermedia_tfee],2,'.','')."</td></tr>";
   echo "</td></tr>";

   //NSAA USE
   echo "<tr><td colspan=3 align=right><br><br>";
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=5 class=nine>";
   echo "<caption>NSAA USE ONLY</caption>";
   echo "<tr align=right><td><br>Date <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Check No. <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "Total <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "BB 602-2 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "INS 642-30 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "LIVE VIDEO 692-30 <u>$space $space $space $space $space $space $space $space $space $space</u><br><br>";
   echo "</td></tr></table></td></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Finance3.js"></script>
<script language="javascript">
function CalculateBcasts()
{
   var bcasts=Utilities.getElement('localmedia_bcasts').value;
   var bfee=bcasts * 100;
   Utilities.getElement('localmedia_bfee').value=bfee;

   var wcasts=Utilities.getElement('othermedia_wcasts').value;
   var wfee=wcasts * 150;
   Utilities.getElement('othermedia_wfee').value=wfee;

   var tcasts=Utilities.getElement('othermedia_tcasts').value;
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
function ErrorCheck()
{
	var max=Utilities.getElement('totalschs').value;
	for(var i=0;i<max;i++)
	{
	   var varname="bbschool" + i;
	   if(Utilities.getElement(varname).selectedIndex > 0)
	   {
	        var varname1="row_" + i + "_1";
		var miles1way = Utilities.getElement(varname1).value;
                var varname2="row_" + i + "_2";
                var trips = Utilities.getElement(varname2).value;
                var varname6="row_" + i + "_6";
                var games = Utilities.getElement(varname6).value;
	        if(miles1way==0 || trips==0 || games==0) return false;
	   }
	}
	return true;
}
</script>
</head>
<?php
echo GetHeader($session);

?>
<body onload="Finance3.initialize('<?php echo $session; ?>','<?php echo $sport1; ?>');">
<?php
echo "<p><a href=\"index.php?session=$session\" class=small>&larr; Financial Reports Home</a></p><br>";
echo "<form method=post action=\"".$sport1."finance.php\" name=\"".$sport1."form\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=round value=$round>";
//echo "EXTRA: <div name=extra id=extra></div>";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=distid value=\"$distid\">";
echo "<input type=hidden name=\"disttimesid\" value=\"$disttimesid\">";
echo "<table style=\"max-width:960px;\" cellspacing=0 cellpadding=6 class=nine><caption><h2>NSAA ".strtoupper($type)." BOYS BASKETBALL TOURNAMENT FINANCIAL REPORT</h2>";
echo "<div class=\"normalwhite\" style=\"width:700px;\"><p><b><u>INSTRUCTIONS:</u></b></p><p><label style=\"background-color:yellow;\">Please complete all fields highlighted in yellow.</label> The calculations will be made as you enter the numbers. Please fill out the form completely, from top to bottom.</p>
<p style=\"color:red;\"><b><label style=\"background-color:yellow;\">*NEW*</label> Please make sure to complete the <u>Video Broadcast Section</u> located at the bottom of this form. ALL \"LIVE Video Broadcast Request Forms\" should be forwarded to the NSAA office via fax (402) 489-0934 or email: <a href=\"mailto:mhuber@nsaahome.org\">mhuber@nsaahome.org</a>. Additional information on the NSAA's LIVE Video Broadcast Policies is located in the NSAA Media Manual on the website.</b></p>
<p>When you are finished completing this form, click \"Submit Report\".  You will then be taken to a printer-friendly version of the form.  Please PRINT a copy of the form and send it, along with a check for the amount due the NSAA, to the NSAA office immediately.  Print a second copy for your files.  After receiving approval of the report from the NSAA, send a copy of this form to each participating school with the amount due them.</p>
</div><br><hr><br>";
echO "</caption>";
echo "<tr align=left><td colspan=3><h3>$type $class-$district at $hostschool.</h3>";
echO "<h4>Date(s):&nbsp;&nbsp;$dates</h4></td></tr>";
echo "<tr><td></td><td align=right><b>1. Gate Receipts&nbsp;&nbsp;</b><br>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=1 name=\"gate\" id=\"gate\" value=\"".number_format($gate,2,'.','')."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td></tr>";

echo "<tr><td></td><td align=right><b>2. Broadcast Receipts&nbsp;&nbsp;</b><br>";
echo "<td width=100 bgcolor=yellow align=center>$<input type=text class=tiny size=7 tabindex=2 name=\"b_cast\" id=\"b_cast\" value=\"".number_format($b_cast,2,'.','')."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td></tr>";

echo "<tr align=left><td colspan=3><table>";

echo "<tr align=left bgcolor=yellow><td><b>Winner:</b> <select tabindex=3 name=\"winner\"><option value=''>~</option>";
for($i=0;$i<count($bbsch);$i++)
{
   echo "<option";
   if($winner==$bbsch[$i]) echo " selected";
   echo ">$bbsch[$i]</option>";
}
echo "</select></td></tr>";

echo "<tr align=left bgcolor=yellow><td><b>Runner-Up:</b> <select name=\"runnerup\" tabindex=4><option value=''>~</option>";
for($i=0;$i<count($bbsch);$i++)
{
   echo "<option";
   if($runnerup==$bbsch[$i]) echo " selected";
   echo ">$bbsch[$i]</option>";
}
echo "</select></td></tr>";
echo "</table></td></tr>";
echo "<tr align=left valign=center><td><table><tr align=left bgcolor=yellow><td><b>Attendance:</b>&nbsp;<input tabindex=\"5\" type=text class=tiny size=6 name=\"attendance\" value=\"$attendance\"></td></tr></table></td>";
// Total Gate
echo "<td align=right><b>3. Total Receipts&nbsp;&nbsp;</b><br>";
echo "<font style=\"font-size:8pt;\">(Gross Ticket Sales)</font></td>";
echo "<td width=100  align=center>$<input  type=text class=tiny size=7 name=\"grossreceipts\" id=\"grossreceipts\" value=\"".number_format($grossreceipts,2,'.','')."\" onblur=\"Finance3.Calculate(this.id,this.value);\" readonly=TRUE></td></tr>";
// Officials
echo "<tr align=left><td colspan=3>Expenses are to be paid in full in order listed, using funds available.</td></tr>";
echo "<tr align=left><td colspan=3><b>Officials:</b><br><table>";
echo "<tr align=left valign=center><td>a.&nbsp;Fees ($60.00 each per game)&nbsp;</td>";
echo "<td bgcolor=yellow>$<input tabindex=\"7\" type=text class=tiny size=6 name=\"offfees\" id=\"offfees\" value=\"".number_format($offfees,2,'.','')."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td></tr>";
echo "<tr align=left><td>";
echo "<table cellspacing=0 cellpadding=0><tr align=left><td>b.&nbsp;Total Mileage (one way, one car per set of officials):&nbsp;</td><td bgcolor=yellow><input tabindex=\"8\" type=text class=tiny size=3 name=\"offmiles\" id=\"offmiles\" value=\"$offmiles\" onblur=\"Finance3.Calculate(this.id,this.value);\"> miles</td><td>&nbsp;x $1.00</td></tr></table></td>";
echo "<td>$<input type=text class=tiny size=6 name=\"offmilespaid\" id=\"offmilespaid\" value=\"".number_format($offmilespaid,2,'.','')."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td></tr>";
echo "</table></td></tr>";
echo "<tr><td colspan=2 align=right><b>4. Officials' Total&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"offtotal\" id=\"offtotal\" value=\"".number_format($offtotal,2,'.','')."\" readOnly=TRUE></td></tr>";
// Insurance
echo "<tr><td align=left><b>Insurance Deduction</b> (10% of Total Receipts #1, to be sent to NSAA)</td>";
echo "<td align=right><b>5. Insurance&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"insurance\" id=\"insurance\" value=\"".number_format($insurance,2,'.','')."\" readOnly=TRUE></td></tr>";
// Host Given
if(!$hostgiven || $hostgiven=='')
{
   switch(count($bbsch))
   {
      case 3:
         $hostgiven="75.00";
	 break;
      case 4:
	 $hostgiven="110.00";
	 break;
      case 5:
	 $hostgiven="145.00";
	 break;
      case 6:
	 $hostgiven="180.00";
	 break;
      default:
	 $hostgiven="0.00";
   }
}
if($type=="District Final")
{
   echo "<tr><td align=left><b>Host School Allowance</b><br>($50.00)</td>";
   echo "<td align=right><b>6. Host School Allowance&nbsp;&nbsp;</b></td>";
   $tab=9;
   echo "<td align=center bgcolor=>$<input type=text  class=tiny size=7 name=\"hostgiven\" id=\"hostgiven\" value=\"".number_format(50,2,'.','')."\" readOnly=true></td></tr>";
   $num=5;
}
else if(!$disttimesid)    //HOST ALLOWANCE FOR NON CLASS A BASKETBALL ONLY
{
   echo "<tr><td align=left><b>Host School Allowance</b><br>(3 teams $75.00, 4 teams $110.00, 5 teams $145.00, 6 teams $180.00)</td>";
   echo "<td align=right><b>6. Host School Allowance&nbsp;&nbsp;</b></td>";
   $tab=9;
   echo "<td align=center bgcolor=>$<input type=text  class=tiny size=7 name=\"hostgiven\" id=\"hostgiven\" value=\"".number_format($hostgiven,2,'.','')."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td></tr>";
   $num=5;
}
else 
{
   echo "<input type=hidden name=\"hostgiven\" id=\"hostgiven\" value=\"0\">";
   $num=4;
}
echo "<tr><td align=left><b>Host School</b> (25% of #1, Gross Receipts)</td>";
//echo "<td align=right><b>Host School&nbsp;&nbsp;#$num</b></td><td align=center>$<input type=text class=tiny size=7 name=\"hostallow\" id=\"hostallow\" value=\"".number_format($hostallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<td align=right><b>7. Host School&nbsp;&nbsp; </b></td><td align=center>$<input type=text class=tiny size=7 name=\"hostallow\" id=\"hostallow\" value=\"".number_format($hostallow,2,'.','')."\" readOnly=TRUE></td></tr>";
$num++;
echo "<tr><td align=left><b>NSAA</b> (25% of #1, Gross Receipts)</td>";
//echO "<td align=right><b>NSAA&nbsp;&nbsp;#$num</b></td>";
echO "<td align=right><b>8. NSAA&nbsp;&nbsp;</b></td>";
$num++;
echo "<td align=center>$<input type=text class=tiny size=7 name=\"nsaaallow\" id=\"nsaaallow\" value=\"".number_format($nsaaallow,2,'.','')."\" readOnly=TRUE></td></tr>";
echO "<tr><td align=left><b>Balance</b> (Gross Receipts #1, minus lines #2 through #6)</td>";
echo "<td align=right><b>9. Balance&nbsp;&nbsp; </b></td>";
$num++;
echo "<td align=center>$<input type=text class=tiny size=7 name=\"balance\" id=\"balance\" value=\"".number_format($balance,2,'.','')."\" readOnly=TRUE></td></tr>";
echo "<tr><td width=700 align=left valign=top><b>Mileage Paid to Visiting Schools</b> (Total of Column C below)<br>";
echo "Mileage will be paid to schools, including schools within city limits of tournament site, ONLY if <u>school</u> designated vehicle(s) is/are used to transport the team. If receipts are not sufficient to cover team expenses in column B above, the team expenses shall be paid on a pro-rated basis, using the balance of funds available.";
echo "</td>";
//echo "<td align=right><b>Mileage&nbsp;&nbsp;#$num</b></td>";
echo "<td align=right><b>10. Mileage&nbsp;&nbsp;</b></td>";
$num++;
echo "<td align=center>$<input type=text class=tiny size=7 name=\"vismileage\" id=\"vismileage\" value=\"".number_format($vismileage,2,'.','')."\"></td></tr>";
echo "<tr align=center><td colspan=3>";
echo "<table cellspacing=0 cellpadding=4 style=\"border:#000000 1px solid;\" frame=all rules=all>";
echo "<tr align=left><th class=smaller colspan=9 align=left>Mileage Paid to Competing Schools:<br>";
if($school=="Test's School") $hostcity="Lincoln";
echo "<a target=\"_blank\" href=\"http://www.randmcnally.com/rmc/directions/dirGetMileage.jsp?txtStartState=NE&txtDestCity=$hostcity&txtDestState=NE\">Calculate Mileage (RandMcNally.com)</a>";
echo "</th></tr>";
echo "<tr align=center><th class=smaller rowspan=2><b>School</b></th>";
echo "<th class=smaller colspan=3>(A)</th><th class=smaller>(B)</th><th class=smaller>(C)</th>";
echo "<th class=smaller>(D)</th><th class=smaller>(E)</th><th class=smaller>(F)</th></tr>";
echo "<tr align=center><th class=small>Miles<br>1 Way</th><th class=small>No.<br>Trips</th><th class=small>Total<br>Miles</th>";
echo "<th class=small>Mileage Due<br>($1.70 x Total Mi)</th><th class=small>Mileage Paid<br>(100% or Prorated)</th>";
echo "<th class=small>Games<br>Played</th><th class=small>Bonus</th><th class=small>Total Amt<br>Paid to Teams</th></tr>";
if(($round=="1" || $class=="A" || $class=="B") && !$disttimesid)
   $max=6;
else if($disttimesid>0)
   $max=1;
else
   $max=2;
$tab++;
echo "<input type=hidden name=\"totalschs\" id=\"totalschs\" value=\"$max\">";
//$tab=100;
for($i=0;$i<$max;$i++)
{
   $tab++;
   echo "<tr align=center><td bgcolor=yellow><select id=\"bbschool".$i."\" tabindex=\"$tab\" name=\"bbschool[$i]\"><option value=''>~</option>";
   for($j=0;$j<count($bbsch);$j++)
   {
      //if($bbsch[$j]!=$school)
      //{
         echo "<option value=\"".$bbsch[$j]."\"";
         if($bbschool[$i]==$bbsch[$j]) echo " selected";
         echo ">$bbsch[$j]</option>";
      //}
   }
   echo "</select></td>";
   $var="row_".$i."_1";
   if(!$$var) $$var=0;
   $tab++;
   echo "<td bgcolor=yellow><input tabindex=\"$tab\" type=text class=tiny size=3 name=\"$var\" id=\"$var\" value=\"".$$var."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td>";
   $var="row_".$i."_2";
   if(!$$var) $$var=0;
   $tab++;
   echo "<td bgcolor=yellow><input tabindex=\"$tab\" type=text class=tiny size=2 name=\"$var\" id=\"$var\" value=\"".$$var."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td>";
   $var="row_".$i."_3";
   if(!$$var) $$var="0";
   echo "<td><input type=text class=tiny size=3 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_4";
   if(!$$var) $$var="0.00";
   echo "<td>$<input type=text class=tiny size=6 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_5";
   if(!$$var) $$var="0.00";
   echo "<td>$<input type=text class=tiny size=6 name=\"$var\" id=\"$var\" value=\"".$$var."\" readOnly=TRUE></td>";
   $var="row_".$i."_6";
   if(!$$var) $$var=0;
   $tab++;
   echo "<td bgcolor=yellow><input tabindex=\"$tab\" type=text class=tiny size=2 name=\"$var\" id=\"$var\" value=\"".$$var."\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td>";
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
if(!$col_2_total) $col_2_total=0;
echo "<td><input type=text class=tiny size=2 name=\"col_2_total\" id=\"col_2_total\" value=\"$col_2_total\" readOnly=TRUE></td>";
if(!$col_3_total) $col_3_total=0;
echo "<td><input type=text class=tiny size=3 name=\"col_3_total\" id=\"col_3_total\" value=\"$col_3_total\" readOnly=TRUE></td>";
if(!$col_4_total) $col_4_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_4_total\" id=\"col_4_total\" value=\"$col_4_total\" readOnly=TRUE></td>";
if(!$col_5_total) $col_5_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_5_total\" id=\"col_5_total\" value=\"$col_5_total\" readOnly=TRUE></td>";
if(!$col_6_total) $col_6_total=0;
echo "<td><input type=text class=tiny size=2 name=\"col_6_total\" id=\"col_6_total\" value=\"$col_6_total\" readOnly=TRUE></td>";
if(!$col_7_total) $col_7_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_7_total\" id=\"col_7_total\" value=\"$col_7_total\" readOnly=TRUE></td>";
if(!$col_8_total) $col_8_total="0.00";
echo "<td>$<input type=text class=tiny size=6 name=\"col_8_total\" id=\"col_8_total\" value=\"$col_8_total\" readOnly=TRUE></td>";
echo "</tr>";
echo "</table></td></tr>";
echo "<tr align=left valign=top><td width=700><b>Balance for Bonus</b> (#7 minus #8) To be distributed as specified below.<br>";
echo "If the host school is a participaing team, the host school shall receive a per match share of the bonus equal to that received by the other participating schools, and the bonus shall be divided as follows: NSAA 10%, participating schools 90%. If the host is not a participating school, the bonus shall be divided as follows: Host 15%, NSAA 10%, participating schools 75%.</td>";
if(!$bonus) $bonus="0.00";
//echo "<td align=right><b>Bonus&nbsp;&nbsp;#$num</b></td><td align=center>$<input type=text class=tiny size=7 name=\"bonus\" id=\"bonus\" value=\"$bonus\"></td></tr>";
echo "<td align=right><b>11. Bonus&nbsp;&nbsp;</b></td><td align=center>$<input type=text class=tiny size=7 name=\"bonus\" id=\"bonus\" value=\"$bonus\"></td></tr>";
$num++;
echo "<tr align=left><td>";
echo "<table><tr align=left><td bgcolor=yellow colspan=2><input type=checkbox name=\"hostpart\" id=\"hostpart\" value='x' onClick=\"Finance3.Calculate(this.id,this.value);\"";
if($hostpart=='x') echo " checked";
echo "> <b>We (the host school) are a participating team.<br></b>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If YES, please enter your number of games played:&nbsp;";
echo "<input type=text name=\"hostgames\" id=\"hostgames\" size=2 class=tiny value=\"$hostgames\" onblur=\"Finance3.Calculate(this.id,this.value);\"></td></tr>";
if(!$nonparthostbonus) $nonparthostbonus="0.00";
echo "<tr align=left><td>Non-Participating Host School</td><td>$<input type=text class=tiny size=6 name=\"nonparthostbonus\" id=\"nonparthostbonus\" value=\"$nonparthostbonus\" readOnly=TRUE></td></tr>";
if(!$parthostbonus) $parthostbonus="0.00";
echo "<tr align=left><td>Participating Host School</td><td>$<input type=text class=tiny size=6 name=\"parthostbonus\" id=\"parthostbonus\" value=\"$parthostbonus\" readOnly=TRUE></td></tr>";
if(!$visbonus) $visbonus="0.00";
echo "<tr align=left><td>Schools</td><td>$<input type=text class=tiny size=6 name=\"visbonus\" id=\"visbonus\" value=\"$visbonus\" readOnly=TRUE></td></tr>";
if(!$nsaabonus) $nsaabonus="0.00";
echo "<tr align=left><td>NSAA</td><td>$<input type=text class=tiny size=6 name=\"nsaabonus\" id=\"nsaabonus\" value=\"$nsaabonus\" readOnly=TRUE></td></tr>";
echO "</table></td><td colspan=2>&nbsp;</td></tr>";
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

//LIVE VIDEO BROADCAST FORM:
echo "<tr><td align=left colspan=3>
	<h4>LIVE Video Broadcasts:</h4>
	<p><input type=radio name=\"livebcasts\" id=\"livebcastsYES\" value=\"Yes\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display=''; }\"> <b>YES</b>, LIVE Video Broadcasts were performed at my event.</p>
        <p><input type=radio name=\"livebcasts\" id=\"livebcastsNO\" value=\"No\" onClick=\"if(this.checked) { Utilities.getElement('bcastsdiv').style.display='none'; }\"> <b>NO</b>, LIVE Video Broadcasts were not performed at my event.</p>
	<div id=\"bcastsdiv\" style=\"display:none;\">
	<p>Please complete the following section regarding <b><u>LIVE Video Broadcasts</b></u> that were performed at this event. ALL Broadcast request forms should be copied and submitted to the NSAA office via fax (402) 489-0934 or email <a href=\"mailto:mhuber@nsaahome.org\">mhuber@nsaahome.org</a>.</p>";
	//LOCAL MEDIA
echo "<br />
	<table cellspacing=0 cellpadding=5>
	<tr align=left><td colspan=2><p><b>STUDENT BROADCAST GROUPS:</b></p></td></tr>
	<tr bgcolor='yellow'><td align=right>Number of GAMES Broadcasted:</td><td align=left><input type=text size=2 tabindex=35 name=\"localmedia_bcasts\" value=\"$localmedia_bcasts\" id=\"localmedia_bcasts\" onBlur=\"CalculateBcasts();\"></td></tr>
	<tr><td align=right>Total Broadcast Fees*:</td><td align=left>$<input type=text size=5 name=\"localmedia_bfee\" value=\"$localmedia_bfee\" id=\"localmedia_bfee\" onBlur=\"CalculateBcasts();\"> (at $100 per broadcast)</td></tr>
	<!--<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>-->";
	//OTHER MEDIA
	echo "<tr><td align=left colspan=2><p><b>LOCAL MEDIA BROADCAST GROUPS:</b></p></td></tr>";
	echo "<tr bgcolor='yellow'><td align=right>Number of GAMES Webcasted:</td><td align=left><input type=text size=2 tabindex=36 name=\"othermedia_wcasts\" value=\"$othermedia_wcasts\" id=\"othermedia_wcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
	echo "<tr><td align=right>Total Webcast Fees**:</td><td align=left>$<input type=text size=5 name=\"othermedia_wfee\" value=\"$othermedia_wfee\" id=\"othermedia_wfee\" onBlur=\"CalculateBcasts();\"> (at $150 per webcast)</td></tr>";
	echo "<tr bgcolor='yellow'><td align=right>Number of GAMES Telecasted (TV):</td><td align=left><input type=text size=2 tabindex=37 name=\"othermedia_tcasts\" value=\"$othermedia_tcasts\" id=\"othermedia_tcasts\" onBlur=\"CalculateBcasts();\"></td></tr>";
	echo "<tr><td align=right>Total Telecast Fees**:</td><td align=left>$<input type=text size=6 name=\"othermedia_tfee\" value=\"$othermedia_tfee\" id=\"othermedia_tfee\" onBlur=\"CalculateBcasts();\"> (at $250 per telecast)</td></tr>";
	//echo "<tr><td align=left colspan=2><p><i>** The fees above will be invoiced by the NSAA office and receipts payable to the NSAA.</i></p></td></tr>";
	echo "<tr><td align=left colspan=2><p><i>* The fees above should be invoiced by the host school and receipts payable to the host school.</i></p></td></tr>";
echo "</table>
	</div>
	</td></tr>";

//FINAL INSTRUCTIONS
echo "<tr align=center><td colspan=3>";
echo "<p style=\"text-align:left;padding:10px 150px;color:blue\"><b>Please double-check that the information above is COMPLETE and ACCURATE before clicking \"Submit Report\".  You will NOT be able to make changes once your report is submitted.<br><br>When you click \"Submit Report\", you will be taken to the printer-friendly version of your completed form.  Please print out the form and send copies of it with your checks to the NSAA and the visiting schools.</b></p>";
echo "<input type=hidden name=\"hiddenupdate\" id=\"hiddenupdate\"><input type=\"button\" id=\"update\" name=\"update\" class=\"fancybutton\" value=\"Submit Report\" onClick=\"if(ErrorCheck()) { if(ErrorCheckBcasts()) { Utilities.getElement('hiddenupdate').value='1'; submit(); } else { alert ('You must either check the box saying NO LIVE Video Broadcasts were performed at your event OR enter the number of broadcasts performed.'); } } else { alert('You are missing some mileage, number of trips, and/or games played for one or more competing schools. Please correct and click this button again.'); }\"></td></tr>";
echo "</table>";
echo "</form>";
?>
<div id="loading" style="display:none"></div>
<?php
echo $end_html;
?>
