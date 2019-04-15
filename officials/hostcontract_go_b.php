<?php
/****CONTRACT TO HOST BOYS GOLF DISTRICTS****/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

$sport="go_b";
$districts=$sport."districts";
if($edit==1 && $level==1) $sample=1;
else $edit=0;
if($sample==1) 
{
   $sql="SELECT * FROM $districts WHERE accept=''";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $distid=$row[id];
}
$sportname=GetSportName($sport);

if($level!=1)	//Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id]; $level=$row[level]; $school=$row[school];
}
//Get District Information: 
$sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[hostid]!=$hostid && $level!=1 && $row[hostschool]!=$school)
{
   echo "You are not the host for this district.";
   exit();
}
$hostschool=$row[hostschool];
$hostschool2=addslashes($hostschool);
$type=$row[type]; $class=$row["class"]; $district=$row[district]; $showdistrict=$row[showdistrict];
$dates="";
$days=split("/",$row[dates]); 
for($i=0;$i<count($days);$i++)
{
   $cur=split("-",$days[$i]);
   $cur2=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
   if($days[$i]=="") $dates.="";
   else $dates.=date("F j, Y",$cur2).", ";
   $thisyear=$cur[0];
}
$dates=substr($dates,0,strlen($dates)-2);
$schools=$row[schools];
if($schools=="") $schools="TBA";
if($row[accept]!='')	//already responded to by host
{
   $director=$row[director]; $email=$row[email]; $site=$row[site];
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
   $sql="UPDATE go_bcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4' WHERE district='2'";
   $result=mysql_query($sql);

   $dates="$year1-$month1-$day1/$year2-$month2-$day2";
   $sql="UPDATE go_bdistricts SET dates='$dates' WHERE type='District' AND accept=''";
   $result=mysql_query($sql);
}
   
//get contract text
$sql="SELECT * FROM go_bcontracttext WHERE district='2'";
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
         if($accept=='y' && (trim($director)=='' || trim($email)=='' || trim($site)=='' || $month=='00' || $day=='00' || $year=='0000'))
	    $error=1; 
    	 else
	 {
	    $director=addslashes($director);
	    $email=addslashes($email);
	    $site=addslashes($site);
            $dates="$year-$month-$day";
	    $sql="UPDATE $districts SET dates='$dates',accept='$accept', director='$director', email='$email', site='$site' WHERE id='$distid'";
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
echo "<form method=post action=\"hostcontract_go_b.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_go_b.php?session=$session&sample=1\">Preview this Contract</a>";
else if($level==1)
   echo "<br><a class=small href=\"hostcontract_go_b.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=center><td><div class='error'><b>ERROR:</b><br><br>If you accept this contract, you MUST select the DATE of the tournament, enter the name of the COURSE and enter the DIRECTOR'S NAME and EMAIL. Please do so below and submit this agreement again.</div></td></tr>";
}

$sql="SELECT accept,confirm,post FROM $districts WHERE id='$distid' AND post='y'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 && !($sample==1 && $level==1))	//NO CONTRACT POSTED FOR THIS DISTRICT!
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
$sql="SELECT course FROM $db_name.hostapp_go_b WHERE school='$hostschool2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$course=$row[0];
if($sample==1) $course="Pebble Beach Golf Course";
echo "<tr align=left><td><table>";
echo "<tr align=left><td><b>FROM:</b></td><td>";
if($edit==1)
   echO "<input type=text class=tiny size=50 name=text1 value=\"$text1\">";
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td><b>NSAA District $sportname Tournament</b></td></tr>";
echo "<tr align=left><td><b>DATE:</b></td><td>".date("F j, Y");
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
echo "</td></tr>";

if($edit==1) 
{
   echo "<tr align=left><td colspan=2><br><font style=\"color:blue;font-size:9pt;\"><b>PLEASE NOTE:</b><br>";
   echo "Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>. <br>";
   echo "Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.<br>";
   echo "Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br>";
   echo "Words between &lt;font style='color:red'&gt; and &lt;/font&gt; will be <font style=\"color:red\">RED<
/font>.";
   echo "</td></tr>";
}
echo "<tr align=left><td colspan=2><br>";
if($edit==1)
   echo "<input type=text class=tiny size=90 name=text2 value=\"$text2\">";
else
   echo $text2;
echo " <b><u>$course</b></u>.";
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[The course will be the one entered by this school on their Application to Host.]</font><br><br>";

if($showdistrict!='x' && $class=="A")
   echo "You have been selected to host <b>a Class $class District.<br></b><br>";
else
   echo "You have been selected to host <b>District $class-$district.<br></b><br>";
echo "<b>The schools assigned to this district are:</b><br><table><tr align=left><td>$schools</td></tr></table></td></tr>";
echo "</table></td></tr>";

echo "<tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea name=text3 rows=10 cols=90>$text3</textarea>";
}
else
   echo $text3;
echo "</td></tr>";
echo "<tr align=center><td><br><b>AGREEMENT:</b></td></tr>";
if($edit!=1 && $accept=='y')	//School has entered this info 
{
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td><b>Name of Course:</b></td><td>$site</td></tr>";
   echo "<tr align=left><td><b>Date of Tournament:</b></td><td>$dates</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td><td>$email</td></tr>";
   echo "</table></td></tr>";
}
if(($accept!='y' || $edit==1) && $accept!='n' && (($sample==1 && $level==1) || $level!=1))	//Host has not accepted yet
{
   echo "<tr align=left><td><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=90 name=text4 value=\"$text4\">";
   else
      echo $text4;
   echo "<br><br>";
   echo "<i>If you accept, you must complete ALL 4 fields below:</i></td></tr>";
   echo "<tr align=center><td>";
   echo "<table>";
   echo "<tr align=left valign=top><td><b>Date of Tournament:</b></td>";
      $sql="SELECT * FROM $districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $day=split("/",$row[dates]);
      $day1=split("-",$day[0]); $day2=split("-",$day[1]);
   if($edit==1)
   {
      $sql="SELECT * FROM $districts WHERE accept=''";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $day=split("/",$row[dates]);
      $day1=split("-",$day[0]); $day2=split("-",$day[1]);
      //NSAA needs to indicate the 2 dates from which the hosts can choose
      echo "<td><font style=\"color:red\">[Please enter the 2 dates from which the hosts must choose from to schedule the tournament:]</font><br>";
      if($day1[0]!='' && $day1[0]!='0000') $year0=$day1[0];
      else $year0=date("Y");
      $year1=$year0+1;
      echo "<select name=month1><option value='00'>MM</option>";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($day1[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=day1><option value='00'>DD</option>";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echO "<option";
         if($day1[2]==$d) echO " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=year1><option value='0000'>YYYY</option>";
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($day1[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select><br>";
      echo "<select name=month2><option value='00'>MM</option>";
      for($i=1;$i<=12;$i++)
      {
         if($i<10) $m="0".$i;
         else $m=$i;
         echo "<option";
         if($day2[1]==$m) echo " selected";
         echo ">$m</option>";
      }
      echo "</select>/<select name=day2><option value='00'>DD</option>";
      for($i=1;$i<=31;$i++)
      {
         if($i<10) $d="0".$i;
         else $d=$i;
         echO "<option";
         if($day2[2]==$d) echO " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=year2><option value='0000'>YYYY</option>";
      for($i=$year0;$i<=$year1;$i++)
      {
         echo "<option";
         if($day2[0]==$i) echo " selected";
         echo ">$i</option>";
      }
      echo "</select></td></tr>";
   }
   else
   {
      echo "<td>";
      echo "<select name=month><option value='00'>MM</option>";
         echo "<option selected>".$day1[1]."</option>";
	 if($day2[1] && $day2[1]!=$day1[1]) echo "<option>".$day2[1]."</option>";
      echo "</select>/<select name=day><option value='00'>DD</option>";
         echo "<option selected>".$day1[2]."</option>";
         if($day2[2]) echo "<option>".$day2[2]."</option>";
      echo "</select>/<select name=year><option value='0000'>YYYY</option>";
         echo "<option selected>".$day1[0]."</option>";
         if($day2[2] && $day2[0]!=$day1[0]) echo "<option>".$day2[0]."</option>";
      echo "</select></td></tr>";
   }
   echo "<tr align=left><td><b>Name of Course:</b></td>";
   echo "<td><input type=text class=tiny size=40 name=site value=\"$course\"></td></tr>";
   echo "<tr align=left><td><b>Director:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=director></td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=email></td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=left><td><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;<b>We are unable to accept this contract.</b></td></tr>";
   if($edit==1)
      echo "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><input type=submit name=submit ";
      if($sample==1) echo "disabled ";
      echO "value=\"Submit\"></td></tr>";
   }
}
else if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit ";
   if($sample==1) echO "disabled ";
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
