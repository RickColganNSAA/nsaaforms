<?php
require 'functions.php';
require 'variables.php';

$sport='sp';
//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;
if($level!=1) { $sample=0; $edit=0; }
if($edit==1) $sample=1;

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$sportname=GetSportName($sport);

if($distid)	//GET HOST ID
{
   $sql="SELECT * FROM $districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[hostid];
   unset($distid);
}

if($edit==1 && $savechanges)
{
   $text1=ereg_replace("\r\n","<br>",$text1);
   $text1=addslashes($text1);
   $text2=ereg_replace("\r\n","<br>",$text2);
   $text2=addslashes($text2);
   $text3=ereg_replace("\r\n","<br>",$text3);
   $text3=addslashes($text3);
   $sql="UPDATE spcontracttext SET text1='$text1',text2='$text2',text3='$text3' WHERE district='2'";
   $result=mysql_query($sql);
}

//get contract text
$dist=2;
$sql="SELECT * FROM spcontracttext WHERE district='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3];

//Get District Information:
if($sample==1 && $level==1)
{
   $sql="SELECT * FROM spdistricts ORDER BY hostid DESC LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[hostid];
}
$sql="SELECT * FROM $districts WHERE hostid='$hostid' ORDER BY accept ASC";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0 && $level!=1)
{
   echo "You are not the host for this district.";
   exit();
}

if($submit)
{
   if($level!=1)
   {
      if($submit=="Submit")
      {
         if($accept=='y' && (trim($time)=='' || trim($director)=='' || trim($email)=='' || trim($site)==''))
	 {
	    $error=1; 
	echo $error." $accept $time $director $email $site";
 	 }
    	 else
	 {
	    $director=addslashes($director);
	    $email=addslashes($email);
	    $site=addslashes($site);
   	    $time=addslashes($time);
	    $temp=split("-",$datech);
            if($datech)
	    {
	       $dates=$datech;
	       $sql="UPDATE $districts SET dates='$dates' WHERE hostid='$hostid'";
	       $result=mysql_query($sql);
	       $dates=date("M j, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
	    }
	    $sql="UPDATE $districts SET accept='$accept', director='$director', email='$email', site='$site',time='$time' WHERE hostid='$hostid'";
	    $result=mysql_query($sql);
	echo $sql;
	 }
      }//end if Submit
   }//end if level!=1
   else
   {
      $sql="UPDATE $districts SET confirm='$confirm' WHERE hostid='$hostid'";
      $result=mysql_query($sql);
   }
}

//GET DISTRICT INFO
$sql="SELECT * FROM $districts WHERE hostid='$hostid'";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{ 
   $hostschool[$ix]=$row[hostschool]; $distid[$i]=$row[id];
   $type[$ix]=$row[type]; $class[$ix]=$row["class"]; $district[$ix]=$row[district];
   $gender[$ix]=$row[gender];
   $dates="";
   if(trim($row[dates])!="")
   {
      $days=split("/",$row[dates]);
      for($i=0;$i<count($days);$i++)
      {
         $cur=split("-",$days[$i]);
         $cur2=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
         $dates.=date("M j",$cur2).", ";
      }
      $dates.=date("Y",$cur2);
   }
   else $dates="[TBA]";
   $datess[$ix]=$dates;
   //$dates[$ix]=date("m-d-Y", strtotime($row[dates]));
   $schools[$ix]=$row[schools];
   if($schools[$ix]=="") $schools[$ix]="TBA";
   if($row[accept]!='') //already responded to by host
   {
      $director=$row[director]; $email=$row[email]; $site=$row[site];
   }
   $time=$row[time];
   $ix++;
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"hostcontract_sp.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=\"secret\" value=\"$secret\">";
echo "<input type=hidden name=\"hostid\" value=\"$hostid\">";
echo "<input type=hidden name=edit value=$edit>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_sp.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echo "<br><a class=small href=\"hostcontract_sp.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=left><td><font style=\"color:red\"><b>If you accept this contract, you MUST enter the Director's name and e-mail, as well as the site for the contests.";
   if($selectdate==1 || $editdate==1) echo "<br><br>You must also select a date.";
   echo "</b></font></td></tr>";
}

$sql="SELECT accept,confirm,post FROM $districts WHERE hostid='$hostid' AND post='y' ORDER BY accept,confirm";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 && $secret!=1 && $sample!=1 && $level!=1)	//NO CONTRACT POSTED FOR THIS DISTRICT!
{
   echo "<tr align=center><th align=center>No Contract Posted for this Host.</th></tr>";
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
echo "<br></td></tr></table></td></tr>";

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
echo "<tr align=left><td>";
if($edit==1)
   echo "<font style=\"font-size:10pt\"><b>YOU ARE EDITING THE WORDING OF THIS CONTRACT.<br><br></font></b>";
else if($sample==1)
   echo "<font style=\"font-size:10pt\"><b>THIS IS A SAMPLE CONTRACT.<br><br></font></b>";
if($submit && $level!=1 && !$accept)
{
   echo "<font style=\"color:red\"><b>You must check ACCEPT or DECLINE.</b></font><br>";
}
echo "<table>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td>";
if($edit==1)
   echo "<input type=text size=50 class=tiny name=\"text1\" value=\"$text1\"></td></tr>";
else
   echo "<b>$text1</b></td></tr>";
echo "<tr align=left><td><b>DATE:</b></td><td><b>".date("F j, Y")."</b>";
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
echo "</td></tr>";
echo "</table></td></tr>";

echo "<tr align=left><td>";
if($edit==1)
{
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=\"text2\" cols=70 rows=3>$text2</textarea></td></tr>";
}
else
   echo "<br>$text2</td></tr>";
echo "<tr align=left><td>";
if($edit==1)
   echo "<font style=\"color:red\">[The information below comes from the database.]</font>";
echo "<table>";
for($i=0;$i<count($type);$i++)
{
   echo "<tr align=left><td><br><b>$type[$i] $class[$i]-$district[$i] $sportname</b></td></tr>";
   echo "<tr align=left><td><b>Schools Tentatively Assigned:</b></td></tr>";
   echo "<tr align=left><td>$schools[$i]</td></tr>";
   if($accept=='y')
   {
      echo "<tr align=left><td><font style=\"color:red\"><b>";
      if($level==1) echo "$hostschool[$i] ";
      else echo "You ";
      echo "entered the following information for this district:</b></font>";
      echo "</td></tr>";
      echo "<tr align=center><td><table><tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
      echo "<tr align=left><td><b>Director's E-mail:</b></td><td>$email</td></tr>";
      echo "<tr align=left><td><b>Site:</b></td><td>$site</td></tr>";
      echo "<tr align=left><td><b>Date:</b></td><td>$datess[$i]</td></tr>";
      echo "<tr align=left><td><b>Starting Time:</b></td><td>$time</td></tr>";
      echo "</table></td></tr>";
   }
}
echo "</table></td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea cols=70 rows=5 name=\"text3\">$text3</textarea></td></tr>";
}
else
   echo "$text3</td></tr>";
echo "<tr align=left><td>For more detailed information, please click on the <a class=small href=\"#\" onclick=\"window.open('".$sport."hostterms.php?session=$session&distid=$distid[0]','".$sport."_terms','location=yes,scrollbars=yes,width=600');\">Terms & Conditions</a>.</td></tr>";
if($edit==1)
   echo "<tr align=left><td><font style=\"color:red\">[Please send changes to the Terms & Conditions to the programmer.]</font></td></tr>";

if(($accept!='y' && $accept!='n' && $level!=1) || ($sample==1 && $level==1))	//Host has not accepted yet (OR sample/edit)
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;<b>We accept the above agreement to host the $type[0] $class[0]-$district[0]";
   for($ix=1;$ix<count($type);$ix++)
   {
      echo ",  $class[$ix]-$district[$ix]";
   }
   echo " $sportname Contest";
   if(count($type)>1) echo "s";
   echo "</b>";
   if($edit==1)
      echo "<tr align=left><td><font style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[The correct district will show based on which district the user is hosting.]</font></td></tr>";
   echo ".</td><tr align=center><td><table><tr align=left><td colspan=2>";
   echo "<i>If you accept the contract";
   if(count($type)>1) echo "s";
   echo ", you must complete ALL of the fields below:</i><br>";
   echo "<input type=text class=tiny size=30 name=director onchange=\"this.form['accept'][0].checked=1;\" value=\"$director\"> will serve as <b>district director</b>.<br>";
   echo "The director's <b>e-mail address</b> is: <input type=text class=tiny size=30 name=email onchange=\"this.form['accept'][0].checked=1;\" value=\"$email\">.<br>";
   echo "The <b>site</b> will be <input type=text class=tiny size=30 name=site onchange=\"this.form['accept'][0].checked=1;\" value=\"$site\">.";
   echo "</td></tr>";
   if($sample==1 || $edit==1)
   {
      echo "<tr valign=top align=left><td width='125'><b>Please Select Date:</b></td><td><font style=\"color:red;\">The host will choose from the list of dates you enter for this district when you assign them as host. If you only enter 1 date, they will not be able to choose, but will just be shown this date.</font></td></tr>";
   }
   else if(count($days)>1)
   {
   echo "<tr valign=top align=left><td width=125><b>Please Select Date:</b>";
   echo "</td>";
   echo "<input type=hidden name=\"selectdate\" value=\"1\">";
   echo "<td>";
   for($i=0;$i<count($days);$i++)
   {
      echo "<input type=radio name=\"datech\" value=\"$days[$i]\" onclick=\"this.form['accept'][0].checked=1;\">&nbsp;";
      $temp=split("-",$days[$i]);
      echo date("F j, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
      echo "<br>";
   }
   echo "</td></tr>";
   }
   else
   {
      if(count($days)>0)
      {
         $temp=split("-",$days[0]);
         echo "<tr align=left><td><b>Date:</b></td><td>".date("F j, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]))."</td></tr>"; 
      }
      else
         echo "<tr align=left><td><b>Date:</b></td><td><label class='red'>NOT YET ENTERED BY NSAA OFFICE</label></td></tr>";
   }
   echo "<tr align=left valign=top><td width=125><b>Starting Time:</b><br>(Example: 9:30am)</td>";
   echo "<td><input type=text name=\"time\" value=\"$time\" size=10 class=tiny onchange=\"this.form['accept'][0].checked=1;\"></td></tr>";
   echo "<tr align=left><td>&nbsp;</td><td>All district speech contest must start no later than 12:00 noon.</td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=left><td><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;<b>We are unable to accept this contract.</b></td></tr>";
   if($edit==1)
      echo "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><input type=submit name=submit";
      if($sample==1) echo " disabled";
      echo " value=\"Submit\"></td></tr>";
   }
}
else if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Contest";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the host's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else
   echo "<tr align=center><td><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
echo "</table></form>";

echo $end_html;
?>