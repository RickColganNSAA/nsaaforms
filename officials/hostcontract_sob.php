<?php
/****CONTRACT TO HOST SOCCER DISTRICTS****/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;
if($edit==1 && $level==1) $sample=1;
else $edit=0;

$sport="sob";
if($sample==1) $distid=1;
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$sportname=GetSportName($sport);

//GET UNIQUE HOST DATES:
$sql2="SELECT DISTINCT tourndate FROM sotourndates WHERE hostdate='x' AND boys='x' ORDER BY tourndate,label";
$result2=mysql_query($sql2);
$sohostdates=array(); $i=0;
$soshowdates=array();
while($row2=mysql_fetch_array($result2))
{
   $sohostdates[$i]=$row2[tourndate];
   $date=explode("-",$row2[tourndate]);
   $soshowdates[$i]=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $i++;
} 

if($level!=1)	//Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id]; $level=$row[level]; $school=$row[school];
}

if($edit==1 && $savechanges)
{
   $text1=ereg_replace("\r\n","<br>",$text1);
   $text1=addslashes($text1);
   $text2=ereg_replace("\r\n","<br>",$text2);
   $text2=addslashes($text2);
   $text3=ereg_replace("\r\n","<br>",$text3);
   $text3=addslashes($text3);
   $sql="UPDATE sobcontracttext SET text1='$text1',text2='$text2',text3='$text3' WHERE district='2'";
   $result=mysql_query($sql);
}

//get contract text
$dist=2;
$sql="SELECT * FROM sobcontracttext WHERE district='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3];

if($submit)
{
   if($level!=1)
   {
      if($submit=="Submit")
      {
         if($accept=='y' && (trim($director)=='' || trim($email)=='' || trim($site)==''))
            $error=1;
         else
         {
            $director=addslashes($director);
            $email=addslashes($email);
            $site=addslashes($site);
            $datech="";
            for($i=0;$i<count($sohostdates);$i++)
            {
               if($day[$i])
                  $datech.=$day[$i]."/";
            }
            $datech=substr($datech,0,strlen($datech)-1);
            $sql="UPDATE $districts SET accept='$accept', director='$director', email='$email', site='$site',dates='$datech' WHERE id='$distid'";
            $result=mysql_query($sql);
            if(!$accept) $error=2;
         }
      }//end if Submit
   }//end if level!=1
   else
   {
      $sql="UPDATE $districts SET confirm='$confirm' WHERE id='$distid'";
      $result=mysql_query($sql);
   }
}

//Get District Information: 
$sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[hostid]!=$hostid && $level!=1 && $school!=$row[hostschool])
{
   echo "You are not the host for this district.";
   exit();
}
$hostschool=$row[hostschool]; $hostid=$row[hostid];
$type=$row[type]; $class=$row["class"]; $district=$row[district];
$schools=$row[schools];
if($schools=="") $schools="TBA";
if($row[accept]!='')	//already responded to by host
{
   $director=$row[director]; $email=$row[email]; $site=$row[site];
   $dates="";
   $days=split("/",$row[dates]);
   for($i=0;$i<count($sohostdates);$i++)
   {
      $cur=split("-",$days[$i]);
      $cur2=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
      if($days[$i]=="") $dates.="";
      else if($i<(count($days)-1)) $dates.=date("F j",$cur2).", ";
      else $dates.=date("F j, Y",$cur2);
      $thisyear=$cur[0];
   }
}

//Get Host School Information:
$sql="SELECT t1.name,t1.level,t2.* FROM $db_name.logins AS t1, $db_name.headers AS t2 WHERE (t1.level='2' OR t1.level='4' OR t1.level='5') AND t1.school=t2.school AND t1.id='$hostid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostlevel=$row[level];
if($hostlevel==2)
{
   $name=$row[name];
   $address=$row[address1];
   $city=$row[city_state]; $zip=$row[zip];
}
if($sample==1)
{
   $name="John Doe"; $address="123 4th Street";
   $city="Lincoln, NE"; $zip="68508";
   $hostschool="Test's School";
   $schools="Lincoln East, Lincoln High, Lincoln Southeast, Lincoln Pius X, Lincoln North Star, Lincoln Southwest";
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"hostcontract_sob.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_sob.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echo "<br><a class=small href=\"hostcontract_sob.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=center><td><font style=\"color:red\"><b>If you accept this contract, you MUST enter the Director's name and e-mail, as well as the site for the tournament.</b></font></td></tr>";
}
else if($error==2)
{
   echo "<tr align=center><td><font style=\"color:red\"><b>You must check either the Agree to Accept or Unable to Accept button at the bottom of this screen</b></font></td></tr>";
}

$sql="SELECT accept,confirm,post FROM $districts WHERE id='$distid' AND post='y'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 && $sample!=1)	//NO CONTRACT POSTED FOR THIS DISTRICT!
{
   echo "<tr align=center><th align=center>No Contract Posted for this District.</th></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
if($accept=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo "$hostschool has ";
   echo "<b>accepted</b> the following contract.<br>";
   if($confirm=='y')
   {
      echo "The NSAA has <b>confirmed</b> the following contract.";
      if($level!=1)
      {
         echo "<br>Please <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a> to enter the dates and times for this tournament's contests.";
      }
   }
   else if($row[confirm]=='n')
      echo "The NSAA has <b>rejected</b> the following contract.";
   else if($level!=1)
      echo "Please check back later to see if the NSAA has <b>confirmed</b> your contract.";
   else
      echo "The NSAA has not yet confirmed this contract.";
}
else if($accept=='n' && !$submit)
{
   if($level!=1)
      echo "<tr align=left><td>You have <b>declined</b> the following contract.<br>";
   else 
      echo "<tr align=left><td>$hostschool has <b>declined</b> the following contract.<br>";
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
      if($accept=='y' || $submit=="Confirm")
      {
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.";
         echo "<br><br></td></tr></table></td></tr>";
      }
      else if($accept=='n')
      {
         echo "<tr align=center><td>This confirms that you are not accepting this contract.<br><br></td></tr>";
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

//SHOW CONTRACT:
echo "<tr align=left><td><table>";
echo "<tr align=left valign=top><td><b>TO:</b></td>";
if($hostlevel==2)
   echo "<td>$name<br>$hostschool<br>$address<br>$city $zip</td></tr>";
else
   echo "<td>$hostschool</td></tr>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td><b>$thisyear NSAA $sportname District Tournament</b></td></tr>";
echo "<tr align=left><td><b>DATE:</b></td><td>".date("F j, Y");
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
echo "</td></tr>";
echo "</table></td></tr>";

if($edit==1)
{
   echo "<tr align=left><td><font style=\"color:blue;font-size:9pt;\"><b>PLEASE NOTE:</b><br>";
   echo "Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>. <br>";
   echo "Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.<br>";
   echo "Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br>";
   echo "Words between &lt;font style='color:red'&gt; and &lt;/font&gt; will be <font style=\"color:red\">RED</font>.";
   echo "</td></tr>";
}

echo "<tr align=left><td>";
if($edit==1)
{
   $text1=ereg_replace("<br>","\r\n",$text1);
   echo "<textarea name=text1 rows=2 cols=90>$text1</textarea>";
}
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td colspan=2><b>$type $class-$district</b><br><br>Schools assigned to this district:</td></tr>";
$sch=split(",",$schools);
for($i=0;$i<count($sch);$i++)
{
   if($i%2==0) echo "<tr align=left>";
   echo "<td>".trim($sch[$i])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
   if(($ix+1)%2==0) echo "</tr>";
}
echo "</table></td></tr>";
echo "<tr align=left><td>Before agreeing to accept this contract, please review the information about hosting this district by clicking on the <a class=small target=\"_blank\" href=\"sohostterms.php?session=$session\">Terms & Conditions</a>.</td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=text2 rows=8 cols=90>$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr>";
echo "<tr align=center><td><br><b>AGREEMENT:</b></td></tr>";
if($accept=='y')	//School has entered this info 
{
   if($confirm=='y' && $level!=1)
      echo "<tr align=center><td>(To edit this information, <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a>)</td></tr>";
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td><b>Tournament Site:</b></td><td>$site</td></tr>";
   echo "<tr align=left><td><b>Date(s) of Tournament:</b></td><td>$dates</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td><td>$email</td></tr>";
   $sql="SELECT * FROM $disttimes WHERE distid='$distid' ORDER BY day, time";
   $result=mysql_query($sql);
   $ix=1;
   echo "<tr align=left valign=top><td><b>Time Slots:</b>";
   if($confirm!='y' && $level!=1)
      echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<i>You will enter this information once the NSAA has confirmed your contract.</i>)";
   else if($confirm!='y' && $level==1)
      echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<i>$hostschool will enter this information once the NSAA has confirmed this contract.</i>)";
   echo "</td>";
   echo "<td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=2><tr align=center><td><b>Match</b></td><td><b>Date</b></td><td><b>Time</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center><td>$ix</td>";
      $day=split("-",$row[day]); $time=split("[: ]",$row[time]);
      echo "<td>$day[1]/$day[2]/$day[0]</td>";
      echo "<td align=left>$time[0]:$time[1] $time[2] $time[3]</td></tr>";
      $ix++;
   }
   while($ix<=$gamecount)
   {
     echo "<tr align=center><td>$ix</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
     $ix++;
   }
   echo "</table></td></tr>";
   echo "</table></td></tr>";
}

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))	//Host has not accepted yet
{
   echo "<tr align=left><td><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;<b>$hostschool&nbsp;";
   if($edit==1)
   {
      $text3=ereg_replace("<br>","\r\n",$text3);
      echo "&nbsp;<font style=\"color:red\">[Name of host]</font><textarea name=text3 rows=3 cols=90>$text3</textarea>";
   }
   else
      echo $text3;
   echo "</b><br><br>";
   echo "<i>If you accept, you must complete ALL fields below:</i></td></tr>";
   echo "<tr align=center><td>";
   echo "<table><tr align=left><td><b>Tournament Site:</b></td>";
   echo "<td><input type=text class=tiny size=40 name=site value=\"$site\"></td></tr>";
   echo "<tr align=left valign=top><td><b>Date(s) of Tournament:</b></td><td>";
   for($i=0;$i<count($sohostdates);$i++)
   {
      echo "<input type=checkbox name=\"day[$i]\" value=\"$sohostdates[$i]\"> $soshowdates[$i]<br>";
   }
   echo "</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=director value=\"$director\"></td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=email value=\"$email\"></td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=left><td><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;<b>We are unable to accept this contract.</b></td></tr>";
   if($edit==1)
      echO "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><input type=submit name=submit ";
      if($sample==1) echo "disabled ";
      echo "value=\"Submit\"></td></tr>";
   }
}
else if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit ";
   if($sample==1) echo "disabled ";
   echo "value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the host's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit ";
   if($sample==1) echo "disabled ";
   echo "value=\"Submit\"></td></tr>";
}
else
   echo "<tr align=center><td><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
echo "</table></form>";

echo $end_html;
?>