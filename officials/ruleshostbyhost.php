<?php
require 'functions.php';
require_once('variables.php');
$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/officials/index.php?error=1");
   exit();
}
if($sport=='' || !$sport)
{
   echo $init_html;
   echo GetHeader($session,"contractadmin");
   echo "<br><br>ERROR: No sport selected.";
   echo $end_html;
   exit();
}
$sportname=GetSportName($sport);
$table=$sport."ruleshosts";
$yearch=date("Y");
$yearch1=$yearch+1;

if($reset)
{
   $sql="UPDATE $table SET origsiteid='0' WHERE id='$siteid'";
   $result=mysql_query($sql);
}

if($delete && $siteid)
{
   $sql="DELETE FROM $table WHERE id='$siteid'";
   $result=mysql_query($sql);
   header("Location:ruleshostreport.php?session=$session&sport=$sport&delete=$siteid");
   exit();
}

if($save && $siteid)
{
   $hostname2=addslashes($hostname);
   $mtgtime2=addslashes($mtgtime);
   $location2=addslashes($location);
   $contactname2=addslashes($contactname);
   $contacttitle2=addslashes($contacttitle);
   $equipment2=addslashes($equipment);
   $sql="UPDATE $table SET type='$type', hostname='$hostname2', ";
   if($origsitehostid && $origsitehostid!='')
      $sql.="origsiteid='$origsitehostid', ";
   $sql.="mtgdate='$year-$month-$day', mtgtime='$mtgtime2', location='$location2', contactname='$contactname2', contacttitle='$contacttitle2', contactphone='$contactphone',equipment='$equipment2', attendance='$attendance', remindersent='$remindersent', showsched='$showsched' WHERE id='$siteid'";
   $result=mysql_query($sql);

   if($oldhostname!=$hostname)
   {
      //reset contract
      $sql="UPDATE $table SET post='',accept='',confirm='',remindersent='',showsched='' WHERE id='$siteid'";
      $result=mysql_query($sql);
   }
}

echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','assignform');DBLookup.initialize('<?php echo $session; ?>','<?php echo $sport; ?>');">
<?php
echo GetHeader($session,"contractadmin");
if(!$sport || $sport=='')
{
   echo "<br><br>No sport selected.";
   echo $end_html;
   exit();
}

echo "<form method=post action=\"ruleshostbyhost.php\" name=\"assignform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<br><a class=small href=\"rulescontracts.php?session=$session&sport=$sport\">$sportname Rules Meeting Host MAIN MENU</a>";
if($siteid)
{
   echo "&nbsp;&nbsp;<a class=small href=\"ruleshostbyhost.php?session=$session&sport=$sport\">$sportname Rules Meeting Host SEARCH</a>";
}
echo "&nbsp;&nbsp;<a class=small href=\"ruleshostreport.php?session=$session&sport=$sport\">$sportname Rules Meeting Host REPORT</a>";
echo "<br><br>";
echo "<font style=\"font-size:9pt\"><b><u>Rules Meeting Hosts ($sportname):</b></u><br><br>";
if($siteid && $siteid!='')
{   
   $sql="SELECT * FROM $table WHERE id='$siteid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<input type=hidden name=siteid value=\"$siteid\">";
   $type=$row[type];
   $date=split("-",$row[mtgdate]); $month=$date[1]; $day=$date[2]; $year=$date[0];
   $mtgtime=$row[mtgtime];
   $hostname=$row[hostname];
   echo "<table>";
   if($posted=='yes')
   {
      if($email!='')
         echo "<caption><font style=\"color:red\">The contract has been posted to the host and an e-mail has been sent to <b>$email</b> informing them of their selection to host.</font></caption>";
      else
      {
         echo "<caption><font style=\"color:red\">The contract has been posted to the host but there was no e-mail on file for ";
         $hostname2=addslashes($hostname);
	 $sql2="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND (level='2' OR level='4' OR level='6')";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[level]==2)
	    echo "$hostname's AD or Activities Director";
	 else 
	    echo "$hostname's contact person";
         echo ", so they have not been notified via e-mail.</font></caption>";
      }
   }
   if($added==1)
   {
      echo "<tr align=left><td colspan=2><font style=\"color:red\"><b>The following Rules Meeting Host assignment has been added:</font></td></tr>";
   }
   echo "<tr valign=top align=left><th align=left>Type:</td>";
   echo "<td><input type=radio name=\"type\" value=\"Originating\"";
   if($type=="Originating") echo " checked";
   echo "> Originating<br>";
   if($type=="Originating")
   {
      $sql2="SELECT * FROM $table WHERE origsiteid='$siteid' ORDER BY mtgdate,hostname";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receiving Sites:<br>";
      while($row2=mysql_fetch_array($result2))
      {
	 $date=split("-",$row2[mtgdate]);
	 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=small href=\"ruleshostbyhost.php?sport=$sport&session=$session&siteid=$row2[id]\">$date[1]/$date[2]/$date[0]: $row2[hostname]</a><br>";
      }
   }
   echo "<input type=radio name=\"type\" value=\"Receiving\"";
   if($type=="Receiving") echo " checked";
   echO "> Receiving<br>";
   if($type=="Receiving") 
   {
      $sql2="SELECT * FROM $table WHERE id='$row[origsiteid]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
	 $row2=mysql_fetch_array($result2);
	 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Originating Site:&nbsp;&nbsp;";
         $date=split("-",$row2[mtgdate]);
	 echo "<a class=small href=\"ruleshostbyhost.php?sport=$sport&session=$session&siteid=$row2[id]\">$date[1]/$date[2]/$date[0]: $row2[hostname]</a>&nbsp;&nbsp;<a class=small href=\"ruleshostbyhost.php?sport=$sport&session=$session&siteid=$siteid&reset=$row2[id]\"><font style=\"color:red\">Reset</font></a><br>";
      }
      else
      {
         echo "Originating Site:<br>";
	 echo "<input type=hidden id=origsitehostid name=origsitehostid value=''>";
         echo "<input type=text class=tiny size=35 name=\"origsitehost\" id=\"origsitehost\" value=\"$origsitehost\" onkeyup=\"UserLookup.lookup('origsitehost',this.value,'$sport','ruleshosts');\"><div class=\"list\" id=\"origsitehostList\"></div>";
      }
   }
   echo "<input type=radio name=\"type\" value=\"Regular\"";
   if($type=="Regular") echo " checked";
   echo "> Regular</td></tr>";
   echo "<tr align=left><th align=left>Date & Time:</th>";
   echo "<td><select name=\"month\"><option value=''>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($month==$m) echo " selected";
      echO ">$m</option>";
   }
   echo "</select> / <select name=\"day\"><option value=''>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($day==$d) echo " selected";
      echO ">$d</option>";
   }
   echo "</select> / <select name=\"year\"><option value=''>YYYY</option>";
   if(!$year || $year=='' || $year=="0000") $year=date("Y");
   $year1=$year+1;
   for($i=$year;$i<=$year1;$i++)
   {
      echo "<option";
      if($year==$i) echo " selected";
      echo ">$i</option>";
   }
   echo "</select> at <input type=text class=tiny size=15 value=\"$mtgtime\" name=\"mtgtime\"></td></tr>";
   echo "<tr align=left valign=top><th align=left>Host:</th>";
   echo "<input type=hidden name=oldhostname value=\"$hostname\">";
   echO "<td><input type=text class=tiny size=35 name=\"hostname\" id=\"hostname\" value=\"$hostname\" onkeyup=\"UserLookup.lookup('hostname',this.value,'','ruleshosts');\"><div class=\"list\" id=\"hostnameList\"></div></td></tr>";
   echo "<tr align=left><th align=left>Attendance:</th>";
   echo "<td><input type=text class=tiny size=5 name=\"attendance\" value=\"$row[attendance]\"></td></tr>";
   echo "<tr align=left><td align=left colspan=2><font style=\"font-size:9pt\">";
   echo "<input type=checkbox name=\"remindersent\" value='x'";
   if($row[remindersent]=='x') echo " checked";
   echo ">&nbsp;Check if reminder letter sent</font></td></tr>";
   echo "<tr align=left><td align=left colspan=2><font style=\"font-size:9pt\">";
   echo "<input type=checkbox name=\"showsched\" value='x'";
   if($row[showsched]=='x') echo " checked";
   echo ">&nbsp;Check here to show this site on the <a target=\"_blank\" href=\"rulesschedule.php?session=$session\">Rules Meeting Schedule</a> (posted to the NSAA Officials & Judges)</td></tr>";
   echo "<tr align=center><td colspan=2><table class=nine border=1 bordercolor=#000000 cellspacing=0 cellpadding=4><tr align=left><td>";
   if($row[post]=='')
   {
      echo "You have NOT posted this contract to the host yet.<br>Don't forget to SAVE this information before posting the contract.<br>";
      echo "<a href=\"postrules.php?session=$session&siteid=$siteid&sport=$sport\">Post Contract</a>&nbsp;&nbsp;";
      echo "<a href=\"rulescontract.php?session=$session&siteid=$siteid&sport=$sport\" target=\"_blank\">Preview Contract</a>";
   }
   else if($row[accept]=='')
   {
      echo "You have POSTED this contract to the host.<br>";
      echo "The host has NOT responded yet.<br>";
      echo "<a target=\"_blank\" href=\"rulescontract.php?session=$session&siteid=$siteid&sport=$sport\">View Contract</a>";
   }
   else
   {
      echo "You have POSTED this contract to the host.<br>";
      if($row[accept]=='y')
      {
         echo "The host has ACCEPTED this contract.<br>";
         if($row[confirm]=='')
            echo "You have NOT responded to this contract yet.<br>";
	 else if($row[confirm]=='y')
	    echo "You have CONFIRMED this contract.<br>";
	 else
	    echo "You have REJECTED this contract.<br>";
      }
      else
      {
         echo "The host has DECLINED this contract.<br>";
         if($row[confirm]=='')
            echo "You have NOT responded to this contract yet.<br>";
         else 
            echo "You have ACKNOWLEDGED this contract.<br>";
      }
      echo "<a target=\"_blank\" href=\"rulescontract.php?session=$session&siteid=$siteid&sport=$sport\">View Contract</a>";
      if($row[accept]=='y' && $row[confirm]=='y')
         echo "<br><a target=\"_blank\" href=\"rulesreminder.php?session=$session&siteid=$siteid&sport=$sport\">View/Print Reminder Letter</a>";
   }
   echo "</td></tr></table></td></tr>";
   if($row[accept]=='y') $be="was";
   else $be="will be";
   echo "<tr align=left><td colspan=2><font style=\"font-size:9pt\"><i>The following information $be entered by the host on their contract.<br>You (the NSAA) may overwrite this information and click \"Save\" at the bottom of this screen.</i></font></td></tr>";
   echo "<tr align=left><th align=left>Location:</th><td><input type=text class=tiny size=30 value=\"$row[location]\" name=\"location\"></td></tr>";
   echO "<tr align=left><th align=left>Contact Person:</th><td><input type=text class=tiny size=25 value=\"$row[contactname]\" name=\"contactname\"></td></tr>";
   echo "<tr align=left><th align=left>Contact Title:</th><td><input type=text class=tiny size=25 value=\"$row[contacttitle]\" name=\"contacttitle\"></td></tr>";
   echo "<tr align=left><th align=left>Contact Phone:</th><td><input type=text class=tiny size=20 value=\"$row[contactphone]\" name=\"contactphone\"></td></tr>";
   if($row[type]=="Originating")
   {
      echo "<tr align=left><th align=left>Equipment Needed:</th><td><input type=text class=tiny size=30 value=\"$row[equipment]\" name=\"equipment\"></td></tr>";
   }
   echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save\">&nbsp;&nbsp;&nbsp;";
   echo "<input type=submit name=delete value=\"Delete\" onclick=\"return confirm('Are you sure you want to delete this Rules Meeting?');\"></td></tr>";
   echo "</table>";
}//end if site id given
else	//Allow user to search by DATE or HOST
{
   echo "<table width=600>";
   echo "<tr valign=top><!--DATE--><td width=300 align=right>";

   echo "<div style=\"position:relative;z-index:1;width:300;\">";
   echo "<div style=\"position:absolute;top:10px;left:10px;\"><font style=\"font-size:9pt\"><b>Search by Date:&nbsp;</b></font>";
   if(!$month)
   {
      $month=date("n"); $year=date("Y");
      if($month<7 && $year=$yearch) $month=7;
   }
   if($month<=12) $year=$yearch;
   else $year=$yearch1;
   echo "<select name=month onchange=\"submit();\">";
   for($i=7;$i<=18;$i++)
   {
      if($i>12)
      {
         $yr=$yearch1; $mo=$i-12;
      }
      else
      {
         $yr=$yearch; $mo=$i;
      }
      echo "<option value=\"$mo\"";
      if($mo==$month) echo " selected";
      echo ">".date("F",mktime(0,0,0,$i,1,$yr))." $yr";
   }
   echo "</select></div>";
   $date = getdate(mktime(0,0,0,$month,1,$year));
   $month_num = $date["mon"];
   $month_name = $date["month"];
   $year = $date["year"];
   $date_today = getdate(mktime(0,0,0,$month_num,1,$year));
   $first_week_day = $date_today["wday"];
   $cont = true;
   $today = 27;
   while (($today <= 32) && ($cont))
   {
      $date_today = getdate(mktime(0,0,0,$month_num,$today,$year));
      if($date_today["mon"] != $month_num)
      {
         $lastday = $today - 1;
         $cont = false;
      }
      $today++;
   }
   $top=35; $left=10;
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>Su</b></div>";
   $left+=25; 
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>M</b></div>";
   $left+=25; 
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>Tu</b></div>";
   $left+=25; 
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>W</b></div>";
   $left+=25; 
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>Th</b></div>";
   $left+=25; 
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>F</b></div>";
   $left+=25; 
   echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\"><b>Sa</b></div>";
   $day = 1;
   $wday = $first_week_day;
   $firstweek = true; 
   $top+=25; $left=10;
   while ( $day <= $lastday)
   {
      if ($firstweek)
      {
	 for ($i=1; $i<=$first_week_day; $i++)
         {
	    echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;\">&nbsp;</div>";
	    $left+=25;
	 }
         $firstweek = false;
      }
      if($wday==0) { $top+=25; $left=10; }
      if ( intval($month_num) < 10) { $new_month_num = "0$month_num"; }
      elseif (intval($month_num) >= 10) { $new_month_num = $month_num; }
      if ( intval($day) < 10) { $new_day = "0$day"; }
      elseif (intval($day) >= 10) { $new_day = $day; }
      $link_date = "$year-$new_month_num-$new_day";
      echo "<div style=\"z-index:1;position:absolute;top:".$top."px;left:".$left."px;width:25;text-align:center;";
      if(IsDateOfRulesMtg($link_date,$sport)) echo "background-color:yellow";
      echo "\">";
      $sql="SELECT id,hostname,type FROM $db_name2.$table WHERE mtgdate=`$link_date`";
      $top2=$top+15;
      echo "<a href=\"#\" onclick=\"DBLookup.toggle('$sql','$link_date','$top2','$left','ruleshostbyhost');\">$day</a></div><br><div class=\"calendarday\" style=\"display:none\" id=\"$link_date\"></div>";
      $left+=25;
      $wday++;
      $wday = $wday % 7;
      $day++;
   }
   echo "</div></td>";

   echo "<!--HOST--><td align=left>";
   echo "<font style=\"font-size:9pt\"><b><br>Search by Host:</b></font><br>";
   echo "<input type=text class=tiny size=35 name=\"hostname\" id=\"hostname\" value=\"$hostname\" onkeyup=\"UserLookup.lookup('hostname',this.value,'$sport','ruleshosts');\"><div class=\"list\" id=\"hostnameList\"></div>";
   if($hostname && $hostname!='')
   {
      $hostname2=addslashes($hostname);
      $sql="SELECT id, hostname,type,mtgdate FROM $db_name2.$table WHERE hostname='$hostname2' ORDER BY mtgdate, type";
      $result=mysql_query($sql);
      echo "<br><table><tr align=left><td>";
      while($row=mysql_fetch_array($result))
      {
         echo "<a class=small href=\"ruleshostbyhost.php?session=$session&sport=$sport&siteid=$row[id]\">";
	 $date=split("-",$row[mtgdate]);
	 echo "$date[1]/$date[2]/$date[0]:&nbsp;&nbsp;$row[type] Site<br><br>";
      }
      echo "</td></tr></table>";
   } 
   echo "</td></tr></table>";
}
echo "</form>";
?>
<div id="debug"></div>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
