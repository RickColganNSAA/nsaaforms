<?php
require 'functions.php';
require 'variables.php';
$sport='pp';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;
if($level!=1) { $sample=0; $edit=0; }
if($edit==1)
   $sample=1;

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
   $sql="UPDATE ppcontracttext SET text1='$text1',text2='$text2',text3='$text3' WHERE district='2'";
   $result=mysql_query($sql);
}

//get contract text
$dist=2;
$sql="SELECT * FROM ppcontracttext WHERE district='$dist'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; 

//Get District Information: 
$sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[hostschool]!=$school && $row[hostid]!=$hostid && $level!=1)
{
   echo "You are not the host for this district.";
   exit();
}
$hostschool=$row[hostschool];
$type=$row[type]; $class=$row["class"]; $district=$row[district];
$gender=$row[gender]; 
$curdates=$row[dates];
$dates="";
if(trim($row[dates])!="" && ($class=="C1" || $class=="C2" || $row[accept]=='y'))
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
$schools=$row[schools];
if($schools=="") $schools="TBA";
if($sample==1 && $level==1)
{
   $hostschool="Test College";
   $type="District"; $class="B"; $district="1";
   $distid='2';
   $schools="Lincoln North Star, Lincoln High, Lincoln Southeast, Lincoln Pius X, Lincoln Southwest";
   $sql="SELECT * FROM $districts WHERE type='$type' ORDER BY dates DESC";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $curdates=$row[dates];
   $dates="";
   if(trim($row[dates])=="")
      $curdates=date("Y")."-12-01/".date("Y")."-12-02";
   $days=split("/",$curdates);
   for($i=0;$i<count($days);$i++)
   {
      $cur=split("-",$days[$i]);
      $cur2=mktime(0,0,0,$cur[1],$cur[2],$cur[0]);
      $dates.=date("M j",$cur2).", ";
   }
   $dates.=date("Y",$cur2);
} 
if($sample!=1 && $row[accept]!='')	//already responded to by host
{
   $director=$row[director]; $email=$row[email]; $site=$row[site]; $time=$row[time];
}

if($submit)
{
   if($level!=1)
   {
      if($submit=="Submit")
      {
         if($accept=='y' && (trim($director)=='' || trim($email)=='' || trim($site)=='' || trim($time)=='' || !$datesch))
	    $error=1; 
    	 else
	 {
	    $director=addslashes($director);
	    $email=addslashes($email);
	    $site=addslashes($site);
	    $time=addslashes($time);
	    
	       //Choose from 2 or 3 dates; 
               $sql="UPDATE $districts SET dates='$datesch' WHERE id='$distid'";
	       $result=mysql_query($sql);
	       $temp=split("-",$datesch);
	       $dates=date("F j, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
	  
	    $sql="UPDATE $districts SET accept='$accept', director='$director', email='$email', site='$site', time='$time' WHERE id='$distid'";
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
echo "<form method=post action=\"hostcontract_pp.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=edit value=$edit>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_pp.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echo "<br><a class=small href=\"hostcontract_pp.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=left><td><font style=\"color:red\"><b>If you accept this contract, you MUST enter the Director's name and e-mail, as well as the site & time for the contest.";
   if($selectdate==1 || $editdate==1) echo "<br><br>You must also select a date.";
   echo "</b></font></td></tr>";
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
         echo "<br>Please <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a> to enter the dates and times for this contests.";
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
echO "<table>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td>";
if($edit==1)
{
   echo "<input type=text size=40 class=tiny name=\"text1\" value=\"$text1\">";
}
else
   echo "<b>$text1</b>";
echo "</td></tr>";
echo "<tr align=left><td><b>DATE:</b></td><td><b>".date("F j, Y")."</b>";
if($edit==1) echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
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
   echo "<textarea name=\"text2\" cols=70 rows=3>$text2</textarea>";
}
else
   echo "<br>$text2";
echo "</td></tr>";
echo "<tr align=left><td><table>";
if($edit==1)
   echo "<tr align=left><td colspan=2><font style=\"color:red\">[The following information comes from the database.]</font></td></tr>";
echo "<tr align=left><td colspan=2><b>$type $class-$district $sportname</b></td></tr>";
echo "<tr align=left valign=top><td colspan=2><b>Schools Tentatively Assigned:</b><br><table width=75%><tr align=left><td>$schools</td></tr></table></td></tr>";
if($accept=='y' && $sample!=1)
{
   echo "<tr align=left><td colspan=2><font style=\"color:red\"><b>";
   if($level==1) echo "$hostschool ";
   else echo "You ";
   echo "entered the following information for this district:</b></font>";
   if($confirm=='y' && $level!=1)
      echo "<br>(To edit this information, <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a>)";
   echo "</td></tr>";
   echo "<tr align=left><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Director's E-mail:</b></td><td>$email</td></tr>";
   echo "<tr align=left><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Site:</b></td><td>$site</td></tr>";
   echo "<input type=hidden name=\"editdate\" value=\"0\">";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Date:</b></td><td>$dates</td></tr>";
   echO "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Starting Time:</b></td><td>$time</td></tr>";
}
echo "</table></td></tr>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<tr align=left><td><textarea name=\"text3\" cols=70 rows=5>$text3</textarea></td></tr>";
}
else
{
   echo "<tr align=left><td>$text3</td></tr>";
}

echo "<tr align=left><td>For more detailed information, please click on the <a class=small href=\"#\" onclick=\"window.open('".$sport."hostterms.php?session=$session&distid=$distid','".$sport."_terms','location=yes,scrollbars=yes,width=600');\">Terms & Conditions</a>.</td></tr>";
if($edit==1)
   echo "<tr align=left><td><font style=\"color:red\">[Please e-mail the programmer with any changs to Terms & Conditions.]</font></td></tr>";

if(($accept!='y' && $accept!='n' && $level!=1) || ($sample==1 && $level==1))	//Host has not accepted yet (OR sample shows)
{
   echo "<tr align=left><td><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;<b>We accept the above agreement to host the $type $class-$district $sportname Tournament</b>.</td></tr>";
   if($edit==1)
      echo "<tr align=left><td><font style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[The correct district will show based on which district the user is hosting.]</font></td></tr>";
   echo "<tr align=center><td><table><tr align=left><td colspan=2><i>If you accept, you must complete ALL of the fields below:</i></td></tr>";
   echo "<tr align=left><td colspan=2><input type=text class=tiny size=30 name=director value=\"$director\" onchange=\"this.form['accept'][0].checked=1;\"> will serve as <b>district director</b>.<br>";
   echo "The director's <b>e-mail address</b> is: <input type=text class=tiny size=30 name=email onchange=\"this.form['accept'][0].checked=1;\" value=\"$email\">.<br>";
   echo "The <b>site</b> will be <input type=text class=tiny size=30 name=site onchange=\"this.form['accept'][0].checked=1;\" value=\"$site\">.";
   echo "</td></tr>";
   echo "<tr align=left><td width=200><b>Please Select Date:</b>";
   echo "</td>";
   echo "<input type=hidden name=\"editdate\" value=\"1\">";
   echo "<td>";

      $days=split("/",$curdates);
      for($i=0;$i<count($days);$i++)
      {
         $day=split("-",$days[$i]);
         echo "<input type=radio name=datesch value=\"$days[$i]\"";
         echo "> $day[1]/$day[2]/$day[0]<br>";
      }
   echo "</td></tr>";
   echO "<tr valign=top align=left><td width=200><b>Please enter the Starting Time:</b><br>(Example: 9:30am)</
td>";
   echo "<td><input type=text size=10 class=tiny name=\"time\" onchange=\"this.form['accept'][0].checked=1;\"></td></tr></table></td></tr>"; 
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
else if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y') //ACCEPTED NOT CONFIRMED
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Tournament";
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
