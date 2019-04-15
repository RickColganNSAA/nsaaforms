<?php
/****CONTRACT TO HOST WRESTLING DISTRICTS****/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;
if($edit==1 && $level==1) $sample=1;
else $edit=0;
if($sample==1) $distid=3;

$sport="wr";
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$sportname=GetSportName($sport);

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
   $text4=ereg_replace("\r\n","<br>",$text4);
   $text4=addslashes($text4);
   $text5=ereg_replace("\r\n","<br>",$text5);
   $text5=addslashes($text5);
   $sql="UPDATE wrcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4',text5='$text5' 
WHERE district='2'";
   $result=mysql_query($sql);
}
   
//get contract text
$dist=2;
$sql="SELECT * FROM wrcontracttext WHERE district='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; $text4=$row[text4]; $text5=$row[text5];

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
            $sql="UPDATE $districts SET accept='$accept', director='$director', email='$email', site='$site' WHERE id='$distid'";
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
$days=split("/",$row[dates]);
$dates="";
for($i=0;$i<count($days);$i++)
{
   $cur=split("-",$days[$i]);
   $cur2=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
   if($days[$i]=="") $dates.="";
   else if($i<(count($days)-1)) $dates.=date("F j",$cur2).", ";
   else $dates.=date("F j, Y",$cur2);
   $thisyear=$cur[0];
}
if($sample==1)
{
   $hostschool="Test's School";
   $schools="Lincoln High, Lincoln East, Lincoln Southeast, Lincoln Southwest, Lincoln Pius X, Lincoln North Star";
}
if($row[accept]!='')	//already responded to by host
{
   $director=$row[director]; $email=$row[email]; $site=$row[site];
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

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"hostcontract_wr.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_wr.php?session=$session&sample=1\">Preview this Contract</a>";
else
   echo "<br><a class=small href=\"hostcontract_wr.php?session=$session&edit=1\">Edit this Contract</a>";
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
      /*
      if($level!=1)
      {
         echo "<br>Please <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a> to enter the dates and times for this tournament's contests.";
      }
      */
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
   echo "<font style=\"color:red\">&nbsp;&nbsp;[Today's Date]</font>";
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
   echo "<textarea name=text1 rows=2 cols=80>$text1</textarea>";
}
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td><b>Tournament:</b></td><td>$class-$district</td></tr>";

echo "<tr align=left><td><b>Date(s):</b></td><td>$dates";
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[These dates can be added/edited for each district<br>in the Host Contract section where the host assignments are made.]</font>";
echo "</td></tr>";
echo "<tr align=left><td colspan=2><b>Schools assigned to this district:</b></td></tr>";
if($edit==1)
   echo "<tr align=left><td colspan=2><font style=\"color:red\">[These schools can be edited for each district in the Host Contract section where the host assignments are made.]</font></td></tr>";
$sch=split(",",$schools);
for($i=0;$i<count($sch);$i++)
{
   if($i%2==0) echo "<tr align=left>";
   echo "<td>".trim($sch[$i])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
   if(($ix+1)%2==0) echo "</tr>";
}
echo "</table></td></tr>";
echO "<tr align=left><td>";
if($edit==1)
{
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=text2 rows=8 cols=80>$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr>";

echo "<tr align=left><td>Before agreeing to accept this contract, please review the information about hosting this district by clicking on the <a class=small href=\"#\" onclick=\"window.open('wrhostterms.php?session=$session&distid=$distid','WR_Terms','location=yes,scrollbars=yes,width=600,height=600');\">Terms & Conditions</a>.</td></tr>";
if($edit==1)
   echo "<tr align=left><td><font style=\"color:red\">[Send updates to Terms & Conditions to programmer.]</fomt></td></tr>";
 
if($accept=='y')	//School has entered this info 
{
   echo "<tr align=center><td><b>You have entered the following information for this tournament:<br></b><table>";
   echo "<tr align=left><td><b>Tournament Site:</b></td><td>$site</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td><td>$email</td></tr>";
   echo "</table></td></tr>";
}

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))	//Host has not accepted yet
{
   echo "<tr align=center><td><br><b>AGREEMENT:</b></td></tr>";
   echo "<tr align=left><td><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;<b>$hostschool&nbsp;";
   if($edit==1)
   {
      $text3=ereg_replace("<br>","\r\n",$text3);
      echo "<font style=\"color:red\">&nbsp;&nbsp;[Host School]</font><textarea name=text3 rows=2 cols=80>$text3</textarea>";
   }
   else
      echo $text3;
   echo "</b><br><br>";
   echo "<i>If you accept, you must complete ALL fields below:</i></td></tr>";
   echo "<tr align=center><td>";
   echo "<table><tr align=left><td><b>Tournament Site:</b></td>";
   echo "<td><input type=text class=tiny size=40 name=site value=\"$site\"></td></tr>";
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
   echo "<tr align=center><td><br><br><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
echo "</table></form>";

echo $end_html;
?>