<?php
//edit_off.php: displays specifics of official's
//	record.  Changes can be made here as well.

require 'variables.php';
require 'functions.php';

$level=GetLevelJ($session);

//validate user
if(!ValidUser($session) || $level!=1)
{
   header("Location:jindex.php");
   exit();
}   

if(!$offid) $offid=$id;

//check if submit action was "delete"
if($submit=="Delete Judge")
{
   header("Location:delete_confirm2.php?header=$header&id=$offid&session=$session&sport1=$sport1&sport2=$sport2&bool=$bool&query=$query&last=$last");
}

//connect to database:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//get level of user
$level=GetLevelJ($session);
?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php

if($submit=="Save Changes")
{
   $convictionexplain=addslashes(preg_replace("/\r\n/","<br>",$convictionexplain));
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $first=ereg_replace("\"","\'",$first);
   $middle=ereg_replace("\'","\'",$middle);
   //$class=ereg_replace("\'","\'",$class);
   $address=ereg_replace("\'","\'",$address);
   $address=ereg_replace("\"","\'",$address);
   $city=ereg_replace("\'","\'",$city);
   $city=ereg_replace("\"","\'",$city);
   $payment=ereg_replace("\'","\'",$payment);
   //get current pending value
   $sql="SELECT pending FROM judges WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $pending=$row[0];
   if(trim($payment)!='') $pending='';
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;
   $regdate="$year-$month-$day";
   if($month=="00" || $day=="00") $regdate="";

   $sql="UPDATE judges SET last='$lastname',first='$first',middle='$middle',socsec='$socsec',address='$address',city='$city',state='$state',zip='$zip',homeph='$homeph',workph='$workph',cellph='$cellph',email='$email',yearsplay='$yearsplay',yearsspeech='$yearsspeech',conviction='$conviction',convictionexplain='$convictionexplain',payment='$payment',firstyrplay='$firstyrplay',firstyrspeech='$firstyrspeech',play='$play',speech='$speech',datereg='$regdate',spmeeting='$spmeeting',ppmeeting='$ppmeeting',sptest='$sptest',pptest='$pptest',pending='$pending',pptrainingyr='$pptrainingyr',sptrainingyr='$sptrainingyr' WHERE id='$offid'";
   $result=mysql_query($sql);

   //if no passcode but there is something in payment field, get new passcode
   if(trim($passcode)=="" && trim($payment)!="")
   {
      $lastname2=ereg_replace("\'","",$lastname);
      $lastname2=ereg_replace(" ","",$lastname2);
      $pass=substr($lastname2,0,6);
      $num=rand(1000,9999);
      $passcode=$pass.$num;
      $sql="SELECT * FROM logins_j WHERE passcode='$passcode'";
      $result=mysql_query($sql);
      while(mysql_num_rows($result)>0)
      {
	 $num++;
	 $passcode=$pass.$num;
	 $sql="SELECT * FROM logins_j WHERE passcode='$passcode'";
	 $result=mysql_query($sql);
      }
   }

   $sql2="SELECT * FROM logins_j WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $name="$first $lastname";
      $name=ereg_replace("\'","\'",$name);
      $sql="INSERT INTO logins_j (name,level,passcode,offid) VALUES ('$name','2','$passcode','$offid')";
   }
   else
   {
      $sql="UPDATE logins_j SET passcode='$passcode' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
}

echo $init_html;
$header2=GetHeaderJ($session);
if($header!="no") echo $header2;

//get judge's info from db
$sql="SELECT * FROM judges WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo "<form method=post action=\"edit_judge.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=query value=\"$query\">";
echo "<input type=hidden name=last value=$last>";
echo "<input type=hidden name=header value=\"$header\">";
if($header=="no")
{
   echo "<a href='#' onclick=\"window.close()\" class=small>Close this Window</a><br><br>";
}
else
{
   echo "<br>";
   echo "<a href=\"judges.php?session=$session&sport1=$sport1&bool=$bool&sport2=$sport2&last=$last&query=$query\" class=small>Return to Judges List</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"judge_query.php?session=$session&sport1=$sport1&bool=$bool&sport2=$sport2&last=$last&query=$query\" class=small>Return to Advanced Search</a><br><br>";
}
echo "<table class='nine' cellspacing=0 cellpadding=5>";
echo "<tr align=center>";
echo "<th colspan=2>Judge #$offid:<br>";
echo "</th></tr>";
echo "<tr><td colspan=2><hr></td></tr>";
//get judge's passcode
$sql2="SELECT passcode FROM logins_j WHERE offid='$offid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$passcode=$row2[0];
echo "<tr align=left><td>Passcode:</td>";
echo "<td><input type=text name=passcode value=\"$passcode\" size=15></td></tr>";
echo "<tr align=left valign=top><td>Name:<br>(last, first, M)</td>";
echo "<td><input type=text name=lastname value=\"$row[last]\" size=15>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=first value=\"$row[first]\" size=10>&nbsp;";
echo "<input type=text name=middle value=\"$row[middle]\" size=2>";
echo "</td></tr>";
echo "<tr align=left><td>Soc Sec #:</td>";
//echo "<td align=left><input type=text name=socsec onfocus='select();' value=\"$row[socsec]\" size=10 maxlength=9 onKeyUp='return autoTab(this,9,event);'></td></tr>";
//echo "<td align=left><input type=password name=socsec value=\"$row[socsec]\" size=10 maxlength=9 > <span  onclick='showssn()' style=\"cursor: pointer; color: blue;\">&nbsp;&nbsp;&nbsp;Show SSn:</span> <span id='showssn'></span></td></tr>";
echo "<td align=left><input type=password name=socsec  value=\"$row[socsec]\" size=10 maxlength=9 onKeyUp='return autoTab(this,9,event);'>&nbsp<span id=\"ssn1\">$row[socsec]</span>&nbsp<input type=\"button\" name=\"Show\" value=\"Show\" \ id=\"ssn_button\"/>&nbsp<input type=\"button\" name=\"Hide\" value=\"Hide\" \ id=\"ssn_button_hide\"/></td></tr>";
echo "<tr align=left><td>Address:</td>";
echo "<td align=left><input type=text name=address value=\"$row[address]\" size=30></td></tr>";
echo "<tr align=left><td>City, State Zip:</td>";
echo "<td align=left><input type=text name=city value=\"$row[city]\" size=20>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=state value=\"$row[state]\" size=2>&nbsp;&nbsp;";
echo "<input type=text name=zip value=\"$row[zip]\" size=10></td></tr>";
echo "<tr align=left><td>Home Phone:</td>";
$homearea=substr($row[homeph],0,3);
$homepre=substr($row[homeph],3,3);
$homepost=substr($row[homeph],6,4);
echo "<td align=left>(<input onfocus='select();' type=text maxlength=3 size=4 name=homearea value='$homearea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=homepre value='$homepre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 onfocus='select();' name=homepost value='$homepost' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><td>Work Phone:</td>";
$workarea=substr($row[workph],0,3);
$workpre=substr($row[workph],3,3);
$workpost=substr($row[workph],6,4);
echo "<td align=left>(<input type=text onfocus='select();' maxlength=3 size=4 name=workarea value='$workarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=workpre value='$workpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=workpost onfocus='select();' value='$workpost' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><td>Cell Phone:</td>";
$cellarea=substr($row[cellph],0,3);
$cellpre=substr($row[cellph],3,3);
$cellpost=substr($row[cellph],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=cellarea onfocus='select();' value='$cellarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=cellpre value='$cellpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=cellpost value='$cellpost' onfocus='select();' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><td>E-mail:</td>";
echo "<td align=left><input type=text size=30 name=email value=\"$row[email]\"></td></tr>";
echo "<tr align=left><td>Registered in:</td>";
echo "<td>";
echo "<input type=checkbox name=play value='x'";
if($row[play]=='x') echo " checked";
echo ">Play&nbsp;&nbsp;";
echo "<input type=checkbox name=speech value='x'";
if($row[speech]=='x') echo " checked";
echo ">Speech</td>";
echo "</tr>";
echo "<tr align=left><td>New Judge:</td>";
echo "<td><input type=checkbox name=\"firstyrplay\" value='x'";
if($row[firstyrplay]=='x') echo " checked";
echo ">Play&nbsp;&nbsp;";
echo "<input type=checkbox name=\"firstyrspeech\" value='x'";
if($row[firstyrspeech]=='x') echo " checked";
echo ">Speech";
echo "</td></tr>";
echo "<tr align=left><td>Years Registered:</td>";
echo "<td align=left>Play: <input type=text size=3 name=\"yearsplay\" value=\"$row[yearsplay]\">&nbsp;&nbsp;";
echo "Speech: <input type=text size=3 name=\"yearsspeech\" value=\"$row[yearsspeech]\"></td></tr>";
echo "<tr align=left><td>Years Attended Judge's Training:</td>";
echo "<td align=left>Play: <input type=text size=3 name=\"pptrainingyr\" value=\"$row[pptrainingyr]\">&nbsp;&nbsp;";
echo "Speech: <input type=text size=3 name=\"sptrainingyr\" value=\"$row[sptrainingyr]\"></td></tr>";
echo "<tr align=left valign=top><td>Convicted of Misdemeanor or Felony:</td>";
echo "<td>";
echo "<input type=radio name='conviction' value='yes' onClick=\"document.getElementById('convictiondiv').style.display='';\"";
if($row[conviction]=="yes") echo " checked";
echo "> Yes&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name='conviction' value='no' onClick=\"document.getElementById('convictiondiv').style.display='none';\"";
if($row[conviction]=="no") echo " checked";
echo "> No&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name='conviction' value='' onClick=\"document.getElementById('convictiondiv').style.display='none';\"";
if($row[conviction]!='yes' && $row[conviction]!='no') echo " checked";
echo "> Unknown<br>";
echo "<div id='convictiondiv'";
if($row[conviction]!='yes')
   echo " style=\"display:none;\"";
echo ">";
   echo "<p><i.If you checked \"Yes\" above, please explain:</i></p>
        <textarea name=\"convictionexplain\" style=\"height:200px;width:600px;\">".preg_replace("/<br>/","\r\n",$row[convictionexplain])."</textarea>";
echo "</div>";
echo "</td></tr>";
echo "<tr align=left><td>Payment:</td>";
echo "<td align=left><input type=text name=payment size=20 value=\"$row[payment]\">";
if($row[pending]=='x') echo "<font style=\"color:red\">(Credit Card Pending)</font>";
echo "</td></tr>";
echo "<tr align=left><td>Registration Date:</td>";
$datereg=split("-",$row[datereg]);
$mo=$datereg[1];
$d=$datereg[2];
$yr=$datereg[0];
echo "<td><select name=month class=small><option value=\"00\">~</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($mo==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select> / ";
echo "<select name=day class=small value=\"00\"><option>~</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($d==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select> / ";
if($yr=="0000") $yr=date("Y",time());
echo "<input type=text class=tiny size=4 name=year value=\"$yr\"></td></tr>";
echo "<tr align=left><td>Meeting:</td>";
echo "<td><input type=checkbox name=\"ppmeeting\" value='x'";
if($row[ppmeeting]=='x') echo " checked";
echo ">Play  <input type=checkbox name=\"spmeeting\" value=\"x\"";
if($row[spmeeting]=='x') echo " checked";
echo ">Speech</td></tr>";
echo "<tr align=left valign=top><td>Test Scores:</td><td>";
//get test scores from sptest_results table
            $sql3="SELECT * FROM pptest";
            $result3=mysql_query($sql3);
            $totalques=mysql_num_rows($result3);
$sql2="SELECT * FROM pptest_results WHERE offid='$row[id]' AND datetaken>0";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$ppscore=number_format(($row2[correct]/$totalques)*100,0,'.','')."%";
if(mysql_num_rows($result2)==0) $ppscore="";
            $sql3="SELECT * FROM sptest";
            $result3=mysql_query($sql3);
            $totalques=mysql_num_rows($result3);
$sql2="SELECT * FROM sptest_results WHERE offid='$row[id]' AND datetaken>0";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$spscore=number_format(($row2[correct]/$totalques)*100,0,'.','')."%";
if(mysql_num_rows($result2)==0) $spscore="";
if($ppscore=="")
{
   echo "<p>Play Production: NOT TAKEN <a class=small target=\"_blank\" href=\"pptest.php?session=$session&givenoffid=$offid\">Enter test for this judge</a></p>";
}
else
{
   echo "<p>Play Production: <b><a href=\"jviewtest.php?session=$session&givenoffid=$offid&sport=pp\" target=\"_blank\">$ppscore</a></b></u></p>";
}
if($spscore=="")
{
   echo "<p>Speech: NOT TAKEN <a class=small target=\"_blank\" href=\"sptest.php?session=$session&givenoffid=$offid\">Enter test for this judge</a></p>";
}
else
{
   echo "<p>Speech: <b><a href=\"jviewtest.php?session=$session&givenoffid=$offid&sport=sp\" target=\"_blank\">$spscore</a></p>";
}
echo "</td></tr>";
echo "<tr align=left valign=top><td><b>Application to Judge:</b><br>(Link will be <font style=\"color:blue\">blue</font> if judge<br>has saved an app)</td>";
echo "<td>";
//check if applied
$sql2="SELECT * FROM spapply WHERE offid='$offid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)==0)
   echo "Speech&nbsp;&nbsp;";
else
   echo "<a class=small target=small href=\"speechapp.php?session=$session&givenoffid=$offid&header=no\">Speech</a>&nbsp;&nbsp;";
$sql2="SELECT * FROM ppapply WHERE offid='$offid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if(mysql_num_rows($result2)==0)
   echo "Play&nbsp;&nbsp;";
else
   echo "<a class=small target=small href=\"playapp.php?session=$session&givenoffid=$offid&header=no\">Play</a>";
echo "</td></tr>";

echo "<tr align=left><td>Date Sent:</td>";
$date=split("-",$row[datesent]);
if($date[0]=="0000")
   echo "<td>Not Yet Sent</td></tr>";
else
   echo "<td>$date[1]/$date[2]/$date[0]</td></tr>";
echo "<input type=hidden name=id value=\"$offid\">";
echo "<tr align=center><td colspan=2><br><input type=submit name=submit tabindex='1' value=\"Save Changes\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Delete Judge\"></td></tr>";
echo "</table></form>";
if($header=="no")
{
   echo "<a class=small href='#' onclick=\"window.close()\">Close this Window</a>";
}
else
{
   echo "<a href=\"jwelcome.php?session=$session\" class=small>Return Home</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"judges.php?session=$session&sport1=$sport1&bool=$bool&sport2=$sport2&query=$query&last=$last\" class=small>Return to Judges List</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"judge_query.php?session=$session&sport1=$sport1&bool=$bool&sport2=$sport2&query=$query&last=$last\" class=small>Return to Advanced Search</a>";
}

echo $end_html;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
$("#ssn1").hide();
$("#ssn_button_hide").hide();
$( "#ssn_button" ).click(function() {
  $("#ssn1").show();
  $("#ssn_button_hide").show();
  $("#ssn_button").hide();
});  
$( "#ssn_button_hide" ).click(function() {
  $("#ssn1").hide();
  $("#ssn_button_hide").hide();
  $("#ssn_button").show();
});  
</script>
<script type="text/javascript">
function showssn(){
	
	var ssn = document.getElementsByName("socsec")[0].value;

	document.getElementById("showssn").innerHTML = ssn;
	
}

</script>
