<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;
if($level!=1) { $sample=0; $edit=0; }
if($edit==1) $sample=1;
if($sample==1) $distid='7';

$sport="sb";
$districts=$sport."districts";
$sportname=GetSportName($sport);

if($level!=1)	//Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id]; $level=$row[level]; $school=$row[hostschool];
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
   $sql="UPDATE sbcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4' WHERE district='2'";
   $result=mysql_query($sql);

   //dates
   if($overwritedates=="x")
   {
      $dates="";
      for($i=0;$i<2;$i++)
      {
         $dates.="$year[$i]-$month[$i]-$day[$i]/";
      }
      $dates=substr($dates,0,strlen($dates)-1);
      $sql="UPDATE $districts SET dates='$dates' WHERE type!='State'";
      $result=mysql_query($sql); 
   }
}

//get contract text
$dist=2;
$sql="SELECT * FROM sbcontracttext WHERE district='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; $text4=$row[text4];

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
$dates="";
$days=split("/",$row[dates]); 
for($i=0;$i<count($days);$i++)
{
   $cur=split("-",$days[$i]);
   $cur2=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
   if($days[$i]=="") $dates.="";
   else $dates.=date("F j, Y",$cur2)." and ";
   $thisyear=$cur[0];
}
$dates=substr($dates,0,strlen($dates)-5);
$schools=$row[schools];
if($schools=="") $schools="TBA";
if($row[accept]!='')	//already responded to by host
{
   $director=$row[director]; $email=$row[email]; $site=$row[site];
   $fieldct=$row[fieldct]; $lightedfieldct=$row[lightedfieldct];
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
   $name="John Doe"; $address="123 45th Street"; $city="Lincoln, NE"; $zip="68503";
   $hostschool="Test's School";
}

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
	    $sql="UPDATE $districts SET accept='$accept', director='$director', email='$email', site='$site',fieldct='$fieldct',lightedfieldct='$lightedfieldct' WHERE id='$distid'";
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
echo "<form method=post action=\"hostcontract_sb.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_sb.php?session=$session&sample=1\">Preview this Contract</a><br><br><b>YOU ARE EDITING THIS CONTRACT</b>";
else if($sample==1)
   echo "<br><a class=small href=\"hostcontract_sb.php?session=$session&edit=1\">Edit this Contract</a><BR><BR><b>THIS IS A SAMPLE CONTRACT</b>";
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
if($accept=='y' && !$submit && $sample!=1)
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
else if($accept=='n' && !$submit && $sample!=1)
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
echo "<tr align=left valign=top><td><b>TO:</b>";
if($sample==1) echo "<br><font style=\"color:red\">[Host Info from Schools Database]</font>";
echo "</td>";
if($hostlevel==2)
   echo "<td>$name<br>$hostschool<br>$address<br>$city $zip</td></tr>";
else
   echo "<td>$hostschool</td></tr>";
echo "<tr align=left><td><b>FROM:</b></td><td>";
if($edit==1)
   echo "<input type=text class=tiny size=50 name=\"text1\" value=\"$text1\">";
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td>";
if($edit==1)
   echo "<input type=text class=tiny size=50 name=\"text2\" value=\"$text2\">";
else 
   echo "<b>$text2</b>";
echo "</td></tr>";
//<b>$thisyear NSAA $sportname District Tournament</b>
echo "<tr align=left><td><b>DATE:</b></td><td>".date("F j, Y");
if($sample==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
echo "</td></tr>";
echo "</table></td></tr>";

echo "<tr align=left><td>The Nebraska School Activities Association needs your consideration to serve as a host for the $thisyear Class $class-$district $sportname District Tournament.";
if($edit==1) 
   echo "<br><font style=\"color:red\">[The district the host has been assigned to will show in the above statement.]</font>";
echo "</td></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><td colspan=2>Schools assigned to this district:";
if($sample==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[These schools comes from the database record for this district.]</font>";
echo "</td></tr>";
$sch=split(",",$schools);
for($i=0;$i<count($sch);$i++)
{
   if($i%2==0) echo "<tr align=left>";
   echo "<td>".trim($sch[$i])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
   if(($ix+1)%2==0) echo "</tr>";
}
echo "</table></td></tr>";

echo "<tr align=left><td>The dates of <b>$dates</b> have been set aside for $sportname Districts.</td></tr>";
if($edit==1)
{
   echo "<tr align=left><td><font style=\"color:red\">To change the dates above (as well as in the wording below under \"AGREEMENT\"), check the box below, select the dates you want and click \"Save Changes\" at the bottom of the screen.  The same dates will show on ALL of the Softball District Host Contracts.<br><br>";
   echo "PLEASE NOTE: Checking the box below will indicate you want to change ALL of the district's dates to the dates you select below.  The assigned hosts will select one or both of the dates below as the date(s) on which they will host their district.  If you have already assigned hosts to some of the districts, checking the box below will overwrite any date selections they may have made.  If you do not wish to overwrite all of the districts, you can change these dates one district at a time in the host contracts assignment section.</font><br>";
   echo "<input type=checkbox name=\"overwritedates\" value='x'> YES, Change ALL district contracts so that the host can select from the dates listed below.  By default, each contract will have both of the dates below selected as the days on which the tournament will be held.<br>";
   $year=date("Y"); $year0=$year-1; $year1=$year+1;
   for($i=0;$i<2;$i++)
   {
      $cur=split("-",$days[$i]);
      echo "<select name=\"month[$i]\"><option value='00'>MM</option>";
      for($j=1;$j<=12;$j++)
      {
	 if($j<10) $m="0".$j;
	 else $m=$j;
	 echo "<option";
	 if($cur[1]==$m) echo " selected";
	 echo ">$m</option>";
      }
      echo "</select>/<select name=\"day[$i]\"><option value='00'>DD</option>";
      for($j=1;$j<=31;$j++)
      {
         if($j<10) $d="0".$j;
         else $d=$j;
         echo "<option";    
         if($cur[2]==$d) echo " selected";
         echo ">$d</option>";
      }
      echo "</select>/<select name=\"year[$i]\"><option value='0000'>YYYY</option>";
      for($j=$year0;$j<=$year1;$j++)
      {
         echo "<option";    
         if($cur[0]==$j) echo " selected";
         echo ">$j</option>";
      }
      echo "</select><br>";
   }
   echo "</td></tr>";
}
echo "<tr align=left><td>";
if($edit==1)
   echo "<input type=text class=tiny size=70 name=\"text3\" value=\"$text3\">";
else 
   echo $text3;
echo "</td></tr>";
//Schedule the championship game(s) on Friday.
echo "<tr align=left><td>";
if($edit==1)
{
   $text4=ereg_replace("<br>","\r\n",$text4);
   echo "<textarea name=\"text4\" cols=70 rows=6>$text4</textarea>";
}
else
   echo $text4;
echo "</td></tr>";
/*
echo "<tr align=left><td>If you can accept this invitation to host this NSAA District $sportname Tournament, click on the Agree to Host button.</td></tr>";
echo "<tr align=left><td>If you must decline this offer, click on the Unable to Accept button, to indicate to us that we will need to select another site.</td></tr>";
*/
echo "<tr align=center><td><br><b>AGREEMENT:</b></td></tr>";
if($accept=='y' && $sample!=1)	//School has entered this info 
{
   if($confirm=='y' && $level!=1)
      echo "<tr align=center><td>(To edit this information, <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a>)</td></tr>";
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td><b>Tournament Site:</b></td><td>$site</td></tr>";
   echo "<tr align=left><td><b>Date(s) of Tournament:</b></td><td>$dates</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td><td>$email</td></tr>";
   echo "<tr align=left><td><b>Number of Fields:</b></td><td>$fieldct</td></tr>";
   echo "<tr align=left><td><b>Number of Lighted Fields:</b></td><td>$lightedfieldct</td></tr>";
   echo "</table></td></tr>";
}

if(($accept!='y' && $accept!='n' && $level!=1) || ($sample==1 && $level==1))	//Host has not accepted yet OR sample
{
   echo "<tr align=left><td><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;<b>$hostschool agrees to host the Class $class-$district $sportname District Tournament on $dates.  Tournaments may be held on one or two days.  Please complete as soon as possible:</b><br><br>";
   echo "<i>If you accept, you must complete ALL fields below:</i></td></tr>";
   echo "<tr align=center><td>";
   echo "<table><tr align=left><td><b>Tournament Site:</b></td>";
   echo "<td><input type=text class=tiny size=40 name=site></td></tr>";
   echo "<td><b>Date(s) of Tournament:</b></td>";
   echo "<td>$dates</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=director></td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=email></td></tr>";
   echo "<tr align=left><td><b>Number of Fields:</b></td>";
   echo "<td><input type=text class=tiny size=3 name=fieldct></td></tr>";
   echo "<tr align=left><td><b>Number of Lighted Fields:</b></td>";
   echo "<td><input type=text class=tiny size=3 name=lightedfieldct></td></tr>";
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
   echo "<tr align=left><td><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the host's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else
   echo "<tr align=center><td><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
echo "</table></form>";

echo $end_html;
?>
