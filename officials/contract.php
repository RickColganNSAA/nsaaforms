<?php
if($type=="State")
{
   header("Location:statecontract.php?session=$session&offid=$offid&sport=$sport");
   exit();
}

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level==4) $level=1;

$disttimes=$sport."disttimes";
$districts=$sport."districts";
$contracts=$sport."contracts";

if($submit)
{
   $disttimesid=split("/",$disttimesids);
   if($level!=1)
   {
      for($i=0;$i<count($disttimesid);$i++)
      {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND disttimesid='$disttimesid[$i]'";
         $result=mysql_query($sql);
      }
   }
   else
   {
      for($i=0;$i<count($disttimesid);$i++)
      {
	 $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND disttimesid='$disttimesid[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo "<center><br>";
//echo "<a class=small href=\"javascript:window.close()\">Close</a>";
echo "<form method=post action=\"contract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=class value=$class>";
echo "<input type=hidden name=dist value=$dist>";
echo "<input type=hidden name=type value=\"$type\">";
echo "<input type=hidden name=offid value=$offid>";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
echo "</td></tr>";
$sql="SELECT t2.accept,t2.confirm,t2.post FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t2.offid='$offid' AND t2.times LIKE '%x%' AND t1.class='$class' AND t1.dist='$dist' AND t1.type='$type' ORDER BY t1.day";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
if($row[accept]=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo GetOffName($offid)." has ";
   echo "<b>accepted</b> the following contract.<br>";
   if($row[confirm]=='y')
   {
      echo "The NSAA has <b>confirmed</b> the following contract.";
   }
   else if($row[confirm]=='n')
   {
      echo "The NSAA has <b>rejected</b> the following contract.";
   }
   else if($level!=1)
   {
      echo "Please check back later to see if the NSAA has <b>confirmed</b> your contract.";
   }
   else
   {
      echo "The NSAA has not yet confirmed this contract.";
   }
}
else if($row[accept]=='n' && !$submit)
{
   if($level!=1)
      echo "<tr align=left><td>You have <b>declined</b> the following contract.<br>";
   else 
      echo "<tr align=left><td>This officials has <b>declined</b> the following contract.<br>";
   if($confirm=='y')
      echo "The NSAA has <b>acknowledged</b> this contract.<br>";
   else if($confirm=='')
      echo "The NSAA has <b>not yet acknowledged</b> this contract.<br>";
}
echo "<br><br></td></tr></table></td></tr>";

if($submit)
{
   if($level!=1)
   {
      if($accept=='y')
      {
         echo "<tr align=center><td><table width=80%><tr align=left><td>You have <b>accepted</b> the following contract.  You may print this screen out for your records.  Please check back later to see if the NSAA has <b>confirmed</b> your contract.<br><br></td></tr></table></td></tr>";
      }
      else if($accept=='n')
      {
         echo "<tr align=center><td>You have <b>declined</b> the following contract.<br><br></td></tr>";
      }
   }
   else
   {
      if($confirm=='y' && $accept=='y')
      {
	 echo "<tr align=center><td>You have <b>confirmed</b> the following contract.<br><br></td></tr>";
      }
      else if($confirm=='y' && $accept=='n')
      {
	 echo "<tr align=center><td>You have <b>acknowledged</b> the following contract.<br><br></td></tr>";
      }
   }
}
echo "<tr align=left><td>".date("F j, Y")."</td></tr>";
echo "<tr align=left><td>".GetOffName($offid)."</td></tr>";
echo "<tr align=left><td>You have been selected for the following ".date("Y");
if($sport=='vb' && $type!="State")
{
   echo " Subdistrict/District Volleyball Tournament ";
}
echo "hosted by the Nebraska School Activities Association:</td></tr>";
if($type!="State")
{
   echo "<tr align=left><td>$class-$dist</td></tr>";
   //get dates and times for this off
   $sql="SELECT t1.times,t2.times,t1.day,t1.id FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t2.offid='$offid' AND t2.times LIKE '%x%' AND t1.class='$class' AND t1.dist='$dist' AND t1.type='$type' ORDER BY t1.day";
   $result=mysql_query($sql);
   $dates="";
   $disttimesids="";
   $partner=array(); $p=0;
   while($row=mysql_fetch_array($result))
   {
      $disttimesids.=$row[id]."/";
      $d++;
      $date=split("-",$row[day]);
      if($type!="District Final")
      {
         $dates.=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
         $partner_date=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      }
      else
      {
	 $sql2="SELECT dates,time FROM $districts WHERE class='$class' AND district='$dist' AND type='District Final'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $dates.=$row2[0].": $row2[1]";
      }
      if($type!="District Final") $dates.=": ";
      if($type!="District Final")
      {
         $showtimes=split("/",$row[0]);
         $timech=split("/",$row[1]);
         $timestr="";
         for($i=0;$i<count($showtimes);$i++)
         {
	    if($timech[$i]=='x')
	    {
	       $timestr.=$showtimes[$i].", ";
	    }  
         }
         $timestr=substr($timestr,0,strlen($timestr)-2);
         $dates.=$timestr."; ";
      }
      //get partner
      $sql2="SELECT offid,times FROM $contracts WHERE disttimesid='$row[id]' AND times LIKE '%x%' AND offid!='$offid'";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 if($type!='District Final')
	 {
	    $partner_timech=split("/",$row2[times]);
	    for($i=0;$i<count($timech);$i++)
	    {
	       if($timech[$i]=='x' && $partner_timech[$i]=='x')
	       {
		  $partner[date][$p]=$partner_date.", ".$showtimes[$i];
		  $partner[name][$p]=GetOffName($row2[offid]);
		  $p++;
	       }
	    }
	 }
	 else
	 {
	    $partner[date][$p]=$partner_date;
	    $partner[name][$p]=GetOffName($row2[offid]);
	    $p++;
	 }
      }
   }
   $disttimesids=substr($disttimesids,0,strlen($disttimesids)-1);
   if($type!="District Final")
      $dates=substr($dates,0,strlen($dates)-2);
   echo "<tr align=left><td>$dates</td></tr>";
   //get District Info
   $sql="SELECT * FROM $districts WHERE class='$class' AND district='$dist' AND type='$type'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>District Director: $row[prefix] $row[first] $row[last]</td></tr>";
   echo "<tr align=left><td>Site: $row[site]</td></tr>";
   echo "<tr align=left><td>Teams Assigned: $row[schools]</td></tr>";
}
echo "<tr align=left><td>Partner(s):<br><table>";
for($i=0;$i<count($partner[name]);$i++)
{
   if($type!="District Final")
   {
      echo "<tr align=left><td>".$partner[date][$i].":</td>";
      echo "<td>".$partner[name][$i]."</td></tr>";
   }
   else
      echo "<tr align=left><td colspan=2>".$partner[name][$i]."</td></tr>";
}
echo "</table></td></tr>";
echo "<tr align=left><td><br>The fee is $40.00 per match.  Mileage will be paid for one automobile at a rate of 85 cents per mile one way each trip.</td></tr>";
echo "<tr align=left><td><i>If the official assigned to officiate the tournament with you is unable to officiate this district for any reason, the NSAA reserves the right to void this contract.</i></td></tr>";
echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";
if($accept!='y' && $accept!='n' && $level!=1)
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;I, as an independent contractor, accept the above agreement for the ".date("Y")." ";
   if($sport=='vb' && $type!="State")
   {
      echo "Subdistrict/District Volleyball Tournament";
   }
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   echo "<input type=hidden name=\"disttimesids\" value=\"$disttimesids\">";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the ".date("Y")." ";
   if($sport=='vb' && $type!="State")
   {
      echo "Subdistrict/District Volleyball Tournament";
   }
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<input type=hidden name=\"disttimesids\" value=\"$disttimesids\">";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the official's decline of the above agreement.</td></tr>";
   echo "<input type=hidden name=\"disttimesids\" value=\"$disttimesids\">";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
echo "</table></form>";
echo "<a class=small href=\"javascript:window.close()\">Close</a>";

echo $end_html;
?>
