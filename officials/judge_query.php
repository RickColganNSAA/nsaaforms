<?php
//judge_query.php: Advanced Search Tool for judges list

require 'variables.php';
require 'functions.php';

$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$sql2="SELECT * FROM pptest ORDER BY place";
$result2=mysql_query($sql2);
$total=mysql_num_rows($result2);
if($total>0) $ppneeded=.8*$total;
else $ppneeded=40;
$sql2="SELECT * FROM sptest ORDER BY place";
$result2=mysql_query($sql2);
$total=mysql_num_rows($result2);
if($total>0) $spneeded=.8*$total;
else $spneeded=40;

if($search=="Search")
{
   $city=ereg_replace("\'","\'",$city);
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $socsec=ereg_replace("-","",$socsec);

   if($sport1=='~' && $bool=='~' && $sport2=='~')
   {
      $sql="SELECT * FROM judges WHERE ";
   }
   elseif($bool=='~' && $sport2=='~')
   {
      $sql="SELECT * FROM judges WHERE $sport1='x' AND ";
   }
   elseif($bool=='NOT')
   {
      $sql="SELECT * FROM judges WHERE $sport1='x' AND $sport2!='x' AND ";
   }
   else
   {
      $sql="SELECT * FROM judges WHERE ($sport1='x' $bool $sport2='x') AND ";
   }

   if(trim($socsec)!="") $sql.="socsec LIKE '$socsec%' AND ";
   if(trim($city)!="") $sql.="city LIKE '$city%' AND ";
   if(trim($lastname)!="") $sql.="last LIKE '$lastname%' AND ";
   if(trim($first)!="") $sql.="first LIKE '$first%' AND ";
   if(trim($zip)!="") $sql.="zip LIKE '$zip%' AND ";
   if(trim($area)!="") $sql.="(homeph LIKE '$area%' OR cellph LIKE '$area%' OR workph LIKE '$area%') AND ";
   if(trim($payment)!="") $sql.="payment LIKE '$payment%' AND ";
   if($firstyr=='play') $sql.="firstyrplay = 'x' AND ";
   else if($firstyr=='speech') $sql.="firstyrspeech = 'x' AND ";
   else if($firstyr=='either') $sql.="(firstyrplay = 'x' OR firstyrspeech = 'x') AND ";
   if($qualified=='y') $sql.="qualified = 'x' AND ";
   else if($qualified=='n') $sql.="qualified = '' AND ";
   if($month!="mm" && $day!="dd" && $year!="yyyy")
   {
      $sql.="datereg";
      if($datereg=="On or After") $sql.=">=";
      else if($datereg=="On or Before") $sql.="<=";
      else $sql.="=";
      $sql.="'$year-$month-$day' AND ";
   }
   $today=date("Y-m-d");
   if($ppdatesent=='x') $sql.="ppdatesent = '$today' AND payment!='' AND ";
   else if($ppdatesent) $sql.="ppdatesent='$ppdatesent' AND payment!='' AND ";
   else if($spdatesent=='x') $sql.="spdatesent = '$today' AND payment!='' AND ";
   else if($spdatesent) $sql.="spdatesent='$spdatesent' AND payment!='' AND ";
   else if($datesent=='x') $sql.="(spdatesent = '$today' OR ppdatesent='$today') AND payment!='' AND ";
   else if($datesent) $sql.="(spdatesent='$datesent' OR ppdatesent='$datesent') AND payment!='' AND ";
   if($meeting=='y') $sql.="meeting = 'x' AND ";
   else if($meeting=='n') $sql.="meeting = '' AND ";
   if(ereg("AND",$sql))
   {
      $sql=substr($sql,0,strlen($sql)-5);
   }
   else
   {
      $sql=substr($sql,0,strlen($sql)-7);
   }
  
   if(strlen($socsec)==9)	//entire soc sec # entered
   {
      $findone=1;	//if only one result from this search, go straight to that official's edit_off page
   }
   else
   {
      $findone=0;
   }
   if($ppdatesent=='x')
   {
      $sql2="UPDATE judges SET ppdatesent='$today' WHERE play='x' AND ppdatesent='0000-00-00' AND payment!=''";
      $result2=mysql_query($sql2);
   }
   else if($spdatesent=='x')
   {
      $sql2="UPDATE judges SET spdatesent='$today' WHERE speech='x' AND spdatesent='0000-00-00' AND payment!=''";
      $result2=mysql_query($sql2);
   }
   else if($datesent=='x')	//BOTH
   {
      $sql2="UPDATE judges SET spdatesent='$today' WHERE speech='x' AND spdatesent='0000-00-00' AND payment!=''";
      $result2=mysql_query($sql2);
      $sql2="UPDATE judges SET ppdatesent='$today' WHERE play='x' AND ppdatesent='0000-00-00' AND payment!=''";
      $result2=mysql_query($sql2);
   }

   $all="";
   if($setquery=='pmeeting')	//find PLAY judges who have paid and passed test but haven't attended a rules mtg
   {
      $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.play='x' AND t1.payment!='' AND t2.correct>=$ppneeded AND t1.ppmeeting!='x'";
   }
   else if($setquery=='smeeting')    //find SPEECH judges who have paid and passed test but haven't attended a rules mtg
   {
      $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.speech='x' AND t1.payment!='' AND t2.correct>=$spneeded AND t1.spmeeting!='x'";
   }
   else if($setquery=='ptest')	//find PLAY judges who've paid and attended mtg but not passed test
   {
      $sql="SELECT t1.* FROM judges AS t1 LEFT JOIN pptest_results AS t2 ON t1.id=t2.offid WHERE t1.play='x' AND t1.payment!='' AND t1.ppmeeting='x' AND (t2.offid IS NULL OR t2.correct<20)";
   }
   else if($setquery=='stest')  //find SPEECH judges who've paid and attended mtg but not passed test
   {
      $sql="SELECT t1.* FROM judges AS t1 LEFT JOIN sptest_results AS t2 ON t1.id=t2.offid WHERE t1.speech='x' AND t1.payment!='' AND t1.spmeeting='x' AND (t2.offid IS NULL OR t2.correct<40)";
   }
   else if($setquery=='apply')	//judges who paid & attended mtg but applied for sp & pp but only took 1 test
   {
      //$sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.meeting='x' AND t1.speech='x' AND t1.play='x' AND (t2.speech='' OR t2.play='') AND t2.combo=''";
   }
   else if($setquery=='pall')	//find PLAY judges who have met all 3 criteria
   {
      $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND t1.play='x' AND t2.correct>=$ppneeded";
   }
    else if($setquery=='ppnotapply')	//find PLAY judges who have met all 3 criteria
   {
      $sql="SELECT t1.* FROM ( SELECT q1.* FROM judges AS q1,pptest_results AS q2 WHERE q1.id=q2.offid AND q1.payment!='' AND q1.ppmeeting='x' AND q1.speech='x' AND q2.correct>=40 ) AS t1 LEFT JOIN (SELECT q1.* FROM judges AS q1,pptest_results AS q2, ppapply as q3 WHERE q1.id=q2.offid AND q1.id=q3.offid and q1.payment!='' AND q1.ppmeeting='x' AND q1.speech='x' AND q2.correct>=40 ) AS t2 ON t2.id = t1.id WHERE t2.id IS NULL";
   }
   else if($setquery=='sall')   //find SPEECH judges who have met all 3 criteria
   {
      $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.spmeeting='x' AND t1.speech='x' AND t2.correct>=$spneeded";
   }
   else if($setquery=='spnotapply')   //find SPEECH judges who have met all 3 criteria
   {
       $sql="SELECT t1.* FROM ( SELECT q1.* FROM judges AS q1,sptest_results AS q2 WHERE q1.id=q2.offid AND q1.payment!='' AND q1.spmeeting='x' AND q1.speech='x' AND q2.correct>=40 ) AS t1 LEFT JOIN (SELECT q1.* FROM judges AS q1,sptest_results AS q2, spapply as q3 WHERE q1.id=q2.offid AND q1.id=q3.offid and q1.payment!='' AND q1.spmeeting='x' AND q1.speech='x' AND q2.correct>=40 ) AS t2 ON t2.id = t1.id WHERE t2.id IS NULL"; 
   }
   else if($setquery=='all')   //find judges who have met all 3 criteria for SPEECH AND PLAY
   {
      $all="all";
      $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.spmeeting='x' AND t1.speech='x' AND t2.correct>=$spneeded";
   }
   else if($setquery=='ppins')
   {
      $sql="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.ppmeeting='x' AND t2.correct>=$ppneeded AND t1.firstyrplay='x'";
   }
   else if($setquery=='spins')
   {
      $sql="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND t1.spmeeting='x' AND t2.correct>=$spneeded AND t1.firstyrspeech='x'";
   }

   if($quickquery=="registered")
      $sql="SELECT * FROM judges WHERE payment!=''";
   else if($quickquery=="all")
      $sql="SELECT * FROM judges";
   $sql=ereg_replace("[+]","PLUS",$sql);
   header("Location:judges.php?session=$session&query=$sql&quickquery=$quickquery&setquery=$setquery&sport1=$sport1&bool=$bool&sport2=$sport2&ppdatesent=$ppdatesent&spdatesent=$spdatesent&datesent=$datesent&findone=$findone&all=$all");
   exit();
}

echo $init_html;
$header=GetHeaderJ($session);
echo $header; 
?>

<br>
<form method="post" action="judge_query.php">
<table cellspacing=0 cellpadding=5 class="nine" style="width:800px;">
<caption>
<b>Judges Advanced Search:<br></b>
<font style=\"font-size:8pt\">
<i>Please indicate your search criteria below:<br>(You can put in just the first part of the criteria you are looking for,<br> such as "685" in the Zip field for all zip codes beginning with 685.)</i></font><br><br>
<input type=hidden name=session value=<?php echo $session; ?>>
</caption>
<?php
echo "<tr align=left><td colspan=2><b><u>Enter your search criteria below:</u></b></td></tr>";
echo "<tr align=left><th align=left>Activitie(s):</th><td align=left>";
echo "<select name=sport1><option value=\"~\">All</option>";
echo "<option value=\"play\">Play Production</option>";
echo "<option value=\"speech\">Speech</option></select>";
echo "&nbsp;<select name=bool><option>~</option>";
echo "<option>AND</option><option>OR</option><option>NOT</option></select>&nbsp;";
echo "<select name=sport2><option>~</option>";
echo "<option value=\"play\">Play Production</option><option value=\"speech\">Speech</option></select>";
echo "</td></tr>";
echo "<tr align=left><th align=left>Soc Sec #:</th>";
echo "<td align=left><input type=text name=socsec size=10></td></tr>";
echo "<tr align=left><th align=left>Last Name:</th>";
echo "<td align=left><input type=text name=lastname size=30></td></tr>";
echo "<tr align=left><th align=left>First Name:</th>";
echo "<td align=left><input type=text name=first size=30></td></tr>";
echo "<tr align=left><th align=left>City:</th>";
echo "<td align=left><input type=text name=city size=30></td></tr>";
echo "<tr align=left><th align=left>Zip:</th>";
echo "<td align=left><input type=text name=zip size=10></td></tr>";
echo "<tr align=left><th align=left>Area Code:</th>";
echo "<td align=left><input type=text name=area size=5></td></tr>";
echo "<tr align=left><th align=left>First Year:</th>";
echo "<td align=left><input type=radio name=firstyr value='play'>Play&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=firstyr value='speech'>Speech&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=firstyr value='either'>Play OR Speech</td></tr>";
//echo "<tr align=left><th align=left>LD Qualified:</th>";
//echo "<td align=left><input type=radio name=qualified value='y'>Yes&nbsp;&nbsp;&nbsp;";
//echo "<input type=radio name=qualified value='n'>No</td></tr>";
echo "<tr align=left><th align=left>Payment:</th>";
echo "<td align=left><input type=text name=payment size=20></td></tr>";
echo "<tr align=left><th align=left>Date Registered:</th>";
echo "<td align=left><select name=datereg>";
echo "<option>On or After</option><option>On or Before</option><option>On</option></select>";
echo "<select name=month><option>mm</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option>$m</option>";
}
echo "</select> / <select name=day><option>dd</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option>$d</option>";
}
echo "</select> / <select name=year><option>yyyy</option>";
$curyr=date("Y",time());
$lastyr=$curyr-1;
echo "<option>$lastyr</option><option>$curyr</option></select>";
echo "</td></tr>";

//PLAY JUDGES WHO HAVE NOT YET BEEN SENT A MAILING:
echo "<tr align=left><td colspan=2><br><h3><u>OR Export Judges for a MAILING:</u></h3></td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=\"ppdatesent\" value='x'>&nbsp;";
//GET number of judges that have not been sent a mailing yet
$sql="SELECT id FROM judges WHERE play='x' AND ppdatesent='0000-00-00' AND payment!=''";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo "<b>Export <label class=\"highlight\"><u>PLAY PRODUCTION judges</u></label> who have not been sent a mailing yet.</b>&nbsp;&nbsp;";
echo "(There are <b>$ct</b> of these judges in the database.)";
echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;(This will record these judges as \"sent\" in the database.)<br>";
$sql="SELECT DISTINCT ppdatesent FROM judges WHERE ppdatesent!='0000-00-00' AND payment!='' ORDER BY ppdatesent DESC LIMIT 3";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<ul style='list-style-type:none;'><b>Previously downloaded exports of Play Production judges:</b>";
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[0]);
	$sql2="SELECT ppdatesent FROM judges WHERE ppdatesent='$row[0]'";
        $result2=mysql_query($sql2);
      echo "<li><input type=radio name='ppdatesent' value='$row[0]'> Play Production Judges Downloaded $date[1]/$date[2]/$date[0] (".mysql_num_rows($result2)." judges)</li>";
   }
   echo "</ul>";
}
echo "</td></tr>";

//SPEECH JUDGES WHO HAVE NOT YET BEEN SENT A MAILING:
echo "<tr align=left><td colspan=2><input type=radio name=spdatesent value='x'>&nbsp;";
//GET number of judges that have not been sent a mailing yet
$sql="SELECT id FROM judges WHERE speech='x' AND spdatesent='0000-00-00' AND payment!=''";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo "<b>Export <label class=\"highlight\"><u>SPEECH judges</u></label> who have not been sent a mailing yet.</b>&nbsp;&nbsp;";
echo "(There are <b>$ct</b> of these judges in the database.)";
echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;(This will record these judges as \"sent\" in the database.)<br>";
$sql="SELECT DISTINCT spdatesent FROM judges WHERE spdatesent!='0000-00-00' AND payment!='' ORDER BY spdatesent DESC LIMIT 3";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<ul style='list-style-type:none;'><b>Previously downloaded exports of Speech judges:</b>";
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[0]);
        $sql2="SELECT spdatesent FROM judges WHERE spdatesent='$row[0]'";
        $result2=mysql_query($sql2);
      echo "<li><input type=radio name='spdatesent' value='$row[0]'> Speech Judges Downloaded $date[1]/$date[2]/$date[0] (".mysql_num_rows($result2)." judges)</li>";
   }
   echo "</ul>";
}
echo "</td></tr>";

//SPEECH AND/OR PLAY JUDGES WHO HAVE NOT YET BEEN SENT A MAILING:
echo "<tr align=left><td colspan=2><input type=radio name=\"datesent\" value='x'>&nbsp;";
//GET number of judges that have not been sent a mailing yet
$sql="SELECT id FROM judges WHERE ((speech='x' AND spdatesent='0000-00-00') OR (play='x' AND ppdatesent='0000-00-00')) AND payment!=''";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo "<b>Export <label class=\"highlight\"><u>ANY SPEECH AND/OR PLAY judges</u></label> who have not been sent a mailing yet.</b>&nbsp;&nbsp;";
echo "(There are <b>$ct</b> of these judges in the database.)";
echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;(This will record these judges as \"sent\" in the database.)<br>";
echo "</td></tr>";

//echo "<td align=left><input type=radio name=meeting value='y'>Yes&nbsp;&nbsp;&nbsp;";
//echo "<input type=radio name=meeting value='n'>No</td></tr>";

//READY-MADE QUERIES:
	//PLAY
echo "<tr align=left><td colspan=2><br><h3><u>OR Choose one of the queries below:</u></h3></td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='pmeeting'>&nbsp;";
echo "<label class='highlight'>PLAY Judges</label> who have PAID and PASSED THEIR TEST(S), but have NOT ATTENDED A RULES MEETING</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='ptest'>&nbsp;";
echo "<label class='highlight'>PLAY Judges</label> who have PAID and ATTENDED A RULES MEETING, but have NOT PASSED/TAKEN THEIR TEST(S)</td></tr>";
/* NO LONGER NEEDED AS OF June 2013
echo "<tr align=left><td><input type=radio name=setquery value='apply'>&nbsp;";
echo "Judges who have PAID and ATTENDED A RULES MEETING, but REGISTERED AS A SPEECH & PLAY JUDGE BUT ONLY TOOK ONE OF THE TESTS.</td></tr>";
*/
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='pall'>&nbsp;";
echo "ALL <label class='highlight'>PLAY Judges</label> who have PAID, ATTENDED A RULES MEETING, and PASSED THEIR TEST(S)</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='ppnotapply'>&nbsp;";
echo "<label class='highlight'>PLAY Judges</label> who have PAID, PASSED THEIR TEST(S), VIEWED THE RULES MEETING, but have NOT COMPLETED THE APP TO JUDGE.</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='ppins'>&nbsp;";
echo "<label class='highlight'>PLAY Judges</label> who need a PIN (those who have paid, attended a rules meeting, passed their test(s) and are FIRST-YEAR judges)</td></tr>";
	//SPEECH
echo "<tr align=left><td colspan=2><br><input type=radio name=setquery value='smeeting'>&nbsp;";
echo "<label class='highlight'>SPEECH Judges</label> who have PAID and PASSED THEIR TEST(S), but have NOT ATTENDED A RULES MEETING</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='stest'>&nbsp;";
echo "<label class='highlight'>SPEECH Judges</label> who have PAID and ATTENDED A RULES MEETING, but have NOT PASSED/TAKEN THEIR TEST(S)</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='sall'>&nbsp;";
echo "ALL <label class='highlight'>SPEECH Judges</label> who have PAID, ATTENDED A RULES MEETING, and PASSED THEIR TEST(S)</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='spnotapply'>&nbsp;";
echo "<label class='highlight'>SPEECH Judges</label> who have PAID, PASSED THEIR TEST(S), VIEWED THE RULES MEETING, but have NOT COMPLETED THE APP TO JUDGE.</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=setquery value='spins'>&nbsp;";
echo "<label class='highlight'>SPEECH Judges</label> who need a PIN (those who have paid, attended a rules meeting, passed their test(s) and are FIRST-YEAR judges)</td></tr>";
	//WHO HAVE MET ALL 3 CRITERIA IN BOTH PLAY AND SPEECH
echo "<tr align=left><td colspan=2><br><input type=radio name=setquery value='all'>&nbsp;";
echo "<label class='highlight'>ALL Judges</label> who have PAID, ATTENDED A RULES MEETING, and PASSED THEIR TEST(S) in <label class='highlight'>PLAY AND SPEECH</label>.</td></tr>";

        //QUICK EXPORTS
/*
echo "<tr align=left><td colspan=2><p><b><u>QUICK EXPORTS:</b></u></p><input type=radio name=quickquery value='registered'>&nbsp;";
echo "All Judges who have REGISTERED (PAID) this year (Name, Email, Address)</td></tr>";
echo "<tr align=left><td colspan=2><input type=radio name=quickquery value='all'>&nbsp;";
echo "ALL JUDGES in the Database (Name, Email, Address)</td></tr>";
*/
?>
<tr align=center>
<td colspan=2><br>
<input type=submit name=search value="Search">
<input type=submit name=cancel value="Cancel">
</td>
</tr>
</table>
</form>
</center>

</td>
</tr>
</table>
</body>
</html>
