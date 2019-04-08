<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

$edit=0;
if($level==1 && $gameid && $view!=1)
   $edit=1;
else if($gameid)
   $view=1;
if($view!=1 || !$gameid)
   $view=0;
if($level==1) $print=1;

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

if($view==1)
{
   //show submitted info from database, link to printer-friendly version
   $sql="SELECT * FROM finance_hurr WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo $init_html;
   if($print!=1)
   {
      echo $header;
      echo "<br><a class=small target=new href=\"hurrfinance.php?session=$session&gameid=$gameid&print=1\">Printer-Friendly Version</a>";
   }
   else
   {
      if($level==1)
         echo "<a class=small href=\"hurrfinance.php?session=$session&gameid=$gameid\">Edit this Form</a>&nbsp;&nbsp;&nbsp;<a class=small href=\"javascript:window.close()\">Close Window</a>";
      echo "<table width=100%><tr align=center><td>";
   }
   echo "<br><table width=600 cellspacing=3 cellpadding=3><tr align=center><td colspan=4>";
   echo "<img src='../officials/nsaacontract.png'><br>";
   echo "<b>HURRICANE RELIEF FUND BASKETBALL GAME FINANCIAL REPORT<br>";
   echo "Submitted ".date("F j, Y",$row[datesub])."<br></b></td></tr>";
   echo "<tr align=left><td colspan=4><u>";
   if($row[gender]=='m') echo "Boys";
   else if($row[gender]=='f') echo "Girls";
   else echo "Boys & Girls";
   echo "</u>&nbsp;Basketball Game Held at <u>$row[site]</u>.</td></tr>";
   echo "<tr align=left><td colspan=4>Date of Game: <u>".date("m/d/Y",$row[gamedate])."</u></td></tr>";
   echo "<tr align=left><td colspan=4>Host Team: <u>$row[hostschool]</u> VS <u>$row[oppschool]</u></td></tr>";
   echo "<tr align=left><td colspan=4>Attendance: <u>$row[attendance]</u></td></tr>";
   echo "<tr align=left><td colspan=2>1)&nbsp;&nbsp;Total Receipts (Gross Ticket Sales Plus Radio and TV Fees)</td>";
   echo "<td align=right>Receipts&nbsp;&nbsp;#1&nbsp;&nbsp;</td><td><u>$$row[totalrec]</u></td></tr>";
   echo "<tr align=left><td colspan=4>2)&nbsp;&nbsp;Officials</td></tr>";
   echo "<tr align=left><td>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "a)&nbsp;Fees:</td>";
   echo "<td colspan=3><u>$$row[offfees]</u></td></tr>";
   echo "<tr align=left><td>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "b)&nbsp;Mileage:</td>";
   echo "<td><u>$$row[offmileage]</u></td>";
   $offtotal=number_format($row[offfees]+$row[offmileage],2,'.','');
   echo "<td align=right>Officials&nbsp;&nbsp;#2&nbsp;&nbsp;</td><td><u>$$offtotal</u></td></tr>";
   echo "<tr align=left><td colspan=2>3)&nbsp;&nbsp;Balance (line #1 minus line #2)</td>";
   $balance=number_format($row[totalrec]-$offtotal,2,'.','');
   echo "<td align=right>Balance&nbsp;&nbsp;#3&nbsp;&nbsp;</td><td><u>$$balance</u></td></tr>";
   echo "<tr valign=top align=left><td>Amount Sent to US Bank:&nbsp;<u>$$row[bankamt]</u><br><br>";
   echo "Check Number: <u>$row[checknum]</u><br><br>";
   if($row[datesent]=='')
      $datesent="[No date available]";
   else
      $datesent=date("m/d/Y",$row[datesent]);
   echo "Date Sent to US Bank: <u>$datesent</u></td>";
   echo "<td colspan=3><u>US Bank Address:</u><br>Mick McKinley<br>US Bank<br>";
   echo "233 South 13th Street<br>Lincoln, NE 68508</td></tr>";
   echo "<tr align=left><td colspan=4>Name of Person who Submitted this Form: <u>$row[submitter]</u></td></tr>";
   echo "<tr align=left><td colspan=4>Telephone Number: <u>(".substr($row[subphone],0,3).")".substr($row[subphone],3,3)."-".substr($row[subphone],6,4)."</td></tr>";
   echo "</table><br>";
   if($print!=1)
      echo "<a class=small target=new href=\"hurrfinance.php?session=$session&gameid=$gameid&print=1\">Printer-Friendly Version</a>";
   echo $end_html;
   exit();
}

if($submit)
{
   $site2=addslashes($site);
   $submitter=addslashes($submitter);
   $hostschool=addslashes($hostschool);
   $oppschool=addslashes($oppschool);
   $totalrec=number_format($totalrec,2,'.','');
   $offfees=number_format($offfees,2,'.','');
   $offmileage=number_format($offmileage,2,'.','');
   $bankamt=number_format($bankamt,2,'.','');
   $phone=$area.$pre.$post;
   if($gamemo!='MM' && $gameday!='DD')
   {
      $gamedate_err=0;
      $gamedate=mktime(0,0,0,$gamemo,$gameday,$gameyr);
   }
   else $gamedate_err=1;
   if($sentmo!='MM' && $sentday!='DD')
      $datesent=mktime(0,0,0,$sentmo,$sentday,$sentyr);
   else
      $datesent="";

   //error-checking
   $error=0;
   if($gamedate_err==1 || !$gender || $site=='' || $hostschool=='Choose School' || $oppschool=='Choose School' || $totalrec=='' || $offfees=='' || $offmileage=='' || $submitter=='' || strlen($phone)!=10)
   {
      $error=1; 
   }
   else
   {
      if($edit==1)
      {
	 $sql="UPDATE finance_hurr SET gender='$gender', site='$site2', gamedate='$gamedate', hostschool='$hostschool', oppschool='$oppschool', attendance='$attendance', totalrec='$totalrec', offfees='$offfees', offmileage='$offmileage', bankamt='$bankamt', checknum='$checknum', datesent='$datesent', submitter='$submitter', subphone='$phone' WHERE id='$gameid'";
      }
      else
      {
	 $today=time();
	 $sql="INSERT INTO finance_hurr (gender,site,gamedate,hostschool,oppschool,attendance,totalrec,offfees,offmileage,bankamt,checknum,datesent,submitter,subphone,datesub) VALUES ('$gender','$site2','$gamedate','$hostschool','$oppschool','$attendance','$totalrec','$offfees','$offmileage','$bankamt','$checknum','$datesent','$submitter','$phone','$today')";
      }
      $result=mysql_query($sql);

      if(!$gameid)
      {
	 $sql="SELECT id FROM finance_hurr WHERE hostschool='$hostschool' ORDER BY id DESC LIMIT 1";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $gameid=$row[0];
      }

      header("Location:hurrfinance.php?session=$session&gameid=$gameid&error=$error&view=1");
      exit();
   }
}

if(!$calculate && $edit==1)
{
    //get information from database already saved for this game
    $sql="SELECT * FROM finance_hurr WHERE id='$gameid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $gender=$row[gender];
    $site=$row[site];
    $gamemo=date("m",$row[gamedate]); $gameday=date("d",$row[gamedate]); $gameyr=date("Y",$row[gamedate]);
    $hostschool=$row[hostschool];
    $oppschool=$row[oppschool];
    $attendance=$row[attendance];
    $totalrec=$row[totalrec];
    $offfees=$row[offfees];
    $offmileage=$row[offmileage];
    $bankamt=$row[bankamt];
    $checknum=$row[checknum];
    if($row[datesent]!='')
    {
       $sentmo=date("m",$row[datesent]); $sentday=date("d",$row[datesent]); $sentyr=date("Y",$row[datesent]);
    }
    else
    {
       $sentmo=""; $sentday=""; $sentyr="";
    }
    $submitter=$row[submitter];
    $phone=$row[subphone];
    $area=substr($phone,0,3); $pre=substr($phone,3,3); $post=substr($phone,6,4);
}
if(!$hostschool || $hostschool=='')
   $hostschool=$school;

echo $init_html;
if($level!=1) echo $header;
else echo "<table width=100%><tr align=center><td>";

echo "<form method=post action=\"hurrfinance.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=gameid value=$gameid>";
echo "<table>";
echo "<tr align=center><td colspan=3><img src='../officials/nsaacontract.png'></td></tr>";
echo "<tr align=center><td colspan=3><b>HURRICANE RELIEF FUND BASKETBALL GAME FINANCIAL REPORT<br>";
echo "(<i>Fields marked with a * are required</i>)</b></td></tr>";

echo "<tr align=left><td colspan=3><b>*</b>";
echo "<input type=radio name=gender value='m'";
if($gender=='m') echo " checked";
echo ">Boys&nbsp;<input type=radio name=gender value='f'";
if($gender=='f') echo " checked";
echo ">Girls&nbsp;<input type=radio name=gender value='all'";
if($gender=='all') echo " checked";
echo ">Boys & Girls&nbsp;";
echo "Basketball Game Held At <input type=text name=site value=\"$site\" class=tiny size=40><br>";
echo "(<b>*</b> Check One)</td></tr>";
echo "<tr align=left><td colspan=3><b>*</b> Date&nbsp;";
echo "<select name=gamemo class=small><option>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $mo="0".$i;
   else $mo=$i;
   echo "<option";
   if($gamemo==$mo) echo " selected";
   echo ">$mo</option>";
}
echo "</select>/<select name=gameday class=small><option>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $day="0".$i;
   else $day=$i;
   echo "<option";
   if($gameday==$day) echo " selected";
   echo ">$day</option>";
}
echo "</select>/<select name=gameyr class=small><option>YYYY</option>";
$curryr=date("Y");
$lastyr=$curryr-1;
if(!$gameyr) $gameyr=$curryr;
for($i=$lastyr;$i<=$curryr;$i++)
{
   echo "<option";
   if($gameyr==$i) echo " selected";
   echo ">$i</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td colspan=3><b>*</b> Host Team:";
echo "<select class=small name=hostschool><option>Choose School</option>";
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($hostschool==$row[0]) echo " selected";
   echo ">$row[0]</option>";
   $schools[$ix]=$row[0];
   $ix++;
}
echo "</select>&nbsp;VS.&nbsp;<b>*</b>";
echo "<select class=small name=oppschool><option>Choose School</option>";
for($i=0;$i<count($schools);$i++)
{
   echo "<option";
   if($schools[$i]==$oppschool) echo " selected";
   echo ">$schools[$i]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left>";
echo "<td colspan=3>Attendance: <input type=text size=5 name=attendance value=\"$attendance\"></td></tr>";

echo "<tr align=left><td>1)&nbsp;&nbsp;&nbsp;";
echo "<b>* Total Receipts</b> (Gross Tocket Sales Plus Radio and TV Fees)</td>";
echo "<td align=right colspan=2><b>*</b> Receipts&nbsp;&nbsp;#1&nbsp;";
echo "$<input type=text class=tiny size=8 name=totalrec value=\"$totalrec\"></td></tr>";
echo "<tr align=left><td colspan=3>2)&nbsp;&nbsp;&nbsp;";
echo "<b>Officials</b></td></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "a) <b>*</b> Fees:</td>";
echo "<td colspan=2>$<input type=text class=tiny size=8 name=offfees value=\"$offfees\"></td></tr>";
echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "b) <b>*</b> Mileage:</td>";
echo "<td>$<input type=text class=tiny size=8 name=offmileage value=\"$offmileage\"></td>";
echo "<td align=right><input type=submit name=calculate value=\"Calculate\"></td></tr>";
if($calculate || $edit==1)
{
   echo "<tr align=right><td colspan=2>&nbsp;</td>";
   echo "<td align=right>Officials&nbsp;&nbsp;#2&nbsp;";
   $offtotal=number_format($offfees+$offmileage,2,'.','');
   echo "$<input type=text class=tiny size=8 name=offtotal readOnly=true value=\"$offtotal\">";
   echo "</td></tr>";

   echo "<tr align=left><td>3)&nbsp;&nbsp;&nbsp;";
   echo "<b>Balance</b> (line #1 minus line #2)</td>";
   echo "<td align=right colspan=2>Balance&nbsp;&nbsp;#3&nbsp;";
   $balance=number_format($totalrec-$offtotal,2,'.','');
   echo "$<input type=text class=tiny size=8 name=balance readOnly=true value=\"$balance\">";
   echo "</td></tr>";

   echo "<tr valign=top align=left><td><b>Amount Sent to US Bank:</b>&nbsp;&nbsp;";
   echo "$<input type=text class=tiny size=8 name=bankamt value=\"$bankamt\"></td>";
   echo "<td colspan=2><u>US Bank Address:</u><br>";
   echo "Mick McKinley<br>US Bank<br>233 South 13th Street<br>Lincoln, NE 68508</td></tr>";

   echo "<tr align=left><td colspan=3>Check Number:&nbsp;";
   echo "<input type=text class=tiny size=10 name=checknum value=\"$checknum\"></td></tr>";
   echo "<tr align=left><td colspan=3>Date Sent to US Bank:&nbsp;";
   echo "<select name=sentmo class=small><option>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $mo="0".$i;
      else $mo=$i;
      echo "<option";
      if($sentmo==$mo) echo " selected";
      echo ">$mo</option>";
   }
   echo "</select>/<select name=sentday class=small><option>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $day="0".$i;
      else $day=$i;
      echo "<option";
      if($sentday==$day) echo " selected";
      echo ">$day</option>";
   }  
   echo "</select>/<select name=sentyr class=small><option>YYYY</option>";
   $curryr=date("Y");
   $lastyr=$curryr-1;
   if(!$sentyr) $sentyr=$curryr;
   for($i=$lastyr;$i<=$curryr;$i++)
   {
      echo "<option";
      if($sentyr==$i) echo " selected";
      echo ">$i</option>";
   }
   echo "</select></td></tr>";

   echo "<tr align=left><td colspan=3>";
   echo "<b>*</b> Name of Person Submitting this Form:&nbsp;";
   echo "<input type=text class=tiny size=30 name=submitter value=\"$submitter\"></td></tr>";
   echo "<tr align=left><td colspan=3>";
   echo "<b>*</b> Telephone Number:&nbsp;";
   echo "(<input type=text class=tiny size=3 name=area value=\"$area\">)";
   echo "<input type=text class=tiny size=3 name=pre value=\"$pre\">-";
   echo "<input type=text class=tiny size=4 name=post value=\"$post\"></td></tr>";

   echo "<tr align=center><td colspan=3>";
   echo "<i><br>Remember to hit <b>\"Submit\"</b> and click on <b>\"Printer-Friendly Version\"</b>.<br>";
   echo "Print one copy for your files and one copy to send to US Bank</i></td></tr>";
   echo "<tr align=center><td colspan=3><input type=submit name=submit value=\"Submit\"></td></tr>";
}

echo "</table></form>";

echo $end_html;
?>
