<?php
/****CONTRACT TO HOST CROSS-COUNTRY DISTRICTS****/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

$sport="ccb";
$districts=$sport."districts";
$sportname=GetSportName($sport);
if($edit==1 && $level==1) $sample=1;
else $edit=0;
if($sample==1) $distid='8';

if($level!=1)	//Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id]; $level=$row[level]; $school=$row[school];
}

//Get District Information: 
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[hostid]!=$hostid && $level!=1 && $row[hostschool]!=$school)
{
   echo "You are not the host for this district.";
   exit();
}
$hostschool=$row[hostschool]; $hostid=$row[hostid];
$type=$row[type]; $class=$row["class"]; $district=$row[district];
$dates=$row[dates];
$curday=split("-",$dates);
$thisyear=$curday[0];
$schools=$row[schools];
if($schools=="") $schools="TBA";
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
   if($row[address2]!='') $address.="<br>$row[address2]";
   $citystate=$row[city_state]; $zip=$row[zip];
}
if($sample==1)
{
   $hostlevel=1;
   $hostschool="Test's School";
   $schools="Alliance, Gering, Lexington, McCook, Ogallala, Scottsbluff, Sidney";
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
   $sql="UPDATE cccontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4' WHERE district=
'2'";
   $result=mysql_query($sql);

   $dates="$year-$month-$day";
   if($dates!="0000-00-00")
   {
      $sql="UPDATE ccbdistricts SET dates='$dates' WHERE type='District'";
      $result=mysql_query($sql);
   }
}

//get contract text
$dist=2;
$sql="SELECT * FROM cccontracttext WHERE district='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; $text4=$row[text4];

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
	 }
      }//end if Submit
   }//end if level!=1
   else
   {
      $sql="UPDATE $districts SET confirm='$confirm' WHERE id='$distid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"hostcontract_ccb.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_ccb.php?session=$session&sample=1\">Preview this Contract</a>";else
   echo "<br><a class=small href=\"hostcontract_ccb.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=center><td><font style=\"color:red\"><b>If you accept this contract, you MUST enter the Director's name and e-mail, as well as the site for the tournament.</b></font></td></tr>";
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
   echo "<td>$name<br>$hostschool<br>$address<br>$citystate $zip</td></tr>";
else
   echo "<td>$hostschool</td></tr>";
if($edit==1)
   echo "<tr align=left><td>&nbsp;</td><td><font style=\"color:red\">[If applicable, AD's name & school address will appear above as well]</font></td></tr>";
echo "<tr align=left><td><b>FROM:</b></td><td>";
if($edit==1)
   echo "<input type=text class=tiny size=50 name=text1 value=\"$text1\">";
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td><b>$thisyear NSAA $sportname District Meet Sites</b></td></tr>";
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
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=text2 rows=2 cols=90>$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td colspan=2><b>District $class-$district</b></td></tr>";
echo "<tr align=left><td colspan=2>Schools assigned to this district:</td></tr>";
if($edit==1)
{
   echo "<tr align=left><td colspan=2><font style=\"color:red\">[Schools for each district are indicated in the Host Contracts<br>section, where the host assignments are made.]</font></td></tr>";
}
$sch=split(",",$schools);
for($i=0;$i<count($sch);$i++)
{
   if($i%2==0) echo "<tr align=left>";
   echo "<td>".trim($sch[$i])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
   if(($ix+1)%2==0) echo "</tr>";
}
echo "</table></td></tr>";

echo "<tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea name=text3 rows=8 cols=90>$text3</textarea>";
}
else
   echo $text3;
echo "</td></tr>";

echo "<tr align=center><td><br><b>AGREEMENT:</b></td></tr>";
if($accept=='y')	//School has entered this info 
{
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td><b>Location of Meet:</b></td><td>$site</td></tr>";
   echo "<tr align=left><td><b>Date of Meet:</b></td><td>$dates</td></tr>";
   echo "<tr align=left><td><b>Meet Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td><b>Meet Director's E-mail:</b></td><td>$email</td></tr>";
   echo "</table></td></tr>";
}

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))	//Host has not accepted yet
{
   echo "<tr align=left><td><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=80 name=text4 value=\"$text4\">";
   else
      echo $text4;
   echo "<br><br>";
   echo "<i>If you accept, you must complete ALL 3 fields below:</i></td></tr>";
   echo "<tr align=center><td>";
   echo "<table><tr align=left><td><b>Location of Meet:</b></td>";
   echo "<td><input type=text class=tiny size=40 name=site></td></tr>";
   echo "<tr align=left valign=top><td><b>Date of Meet:</b></td>";
   $date=split("-",$dates);
   if($date[0]!='' && $date[0]!='0000') $year0=$date[0];
   else $year0=date("Y");
   $year1=$year0+1;
   if($edit==1)
   {
      echo "<td width=300><select name=month><option value='00'>MM</option>";
      for($i=1;$i<=12;$i++)
      {
	 if($i<10) $m="0".$i;
	 else $m=$i;
	 echo "<option";
         if($date[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=day><option value='00'>DD</option>";
      for($i=1;$i<=31;$i++)
      {
	 if($i<10) $d="0".$i;
	 else $d=$i;
	 echO "<option";
	 if($date[2]==$d) echo " selected";
	 echo ">$d</option>";
      }
      echo "</select>/<select name=year><option value='0000'>YYYY</option>";
      for($i=$year0;$i<=$year1;$i++)
      {
	 echo "<option";
	 if($date[0]==$i) echo " selected";
	 echo ">$i</option>";
      }
      echo "</select><br>";
      echo "<font style=\"color:red\">[Only the NSAA can edit this date.  Editing this date will change the date on all Cross-Country host contracts.  In addition, the year shown above will be the year in the \"SUBJECT:\" line at the top of this contract.]</font></td></tr>";
   } 
   else
   {
      echo "<td>".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."</td></tr>";
   }
   echo "<tr align=left><td><b>Meet Director:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=director></td></tr>";
   echo "<tr align=left><td><b>Meet Director's E-mail:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=email></td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=left><td><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;<b>We are unable to accept this contract.</b></td></tr>";
   if($edit==1)
      echo "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><input type=submit name=submit ";
      if($sample==1) echo "disabled ";
      echo "value=\"Submit\"></td></tr>";
   }
}
else if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Meet";
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
   if($sample==1) echo " disabled";
   echo "value=\"Submit\"></td></tr>";
}
else if($level==1 && $edit==1)
{
   echo "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
}
else
   echo "<tr align=center><td><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
echo "</table></form>";

echo $end_html;
?>
