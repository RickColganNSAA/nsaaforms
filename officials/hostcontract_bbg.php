<?php
require 'functions.php';
require 'variables.php';
require '../../calculate/functions.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;
if($edit==1 && $level==1) $sample=1;
else $edit=0;

$sport="bbg";
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$sportname=GetSportName($sport);
if($sample==1) $distid='118';

if($level!=1)	//Check that school is the school hosting this district
{
   $sql="SELECT t1.* FROM $db_name.logins AS t1, $db_name.sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id]; $level=$row[level]; $school=$row[school];
}
//Get District Information: 
if($disttimesid>0)	//CLASS A BASKETBALL
   $sql="SELECT t1.*,t2.type,t2.gender,t2.class,t2.district,t2.showoffs FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.id='$disttimesid'";
else
   $sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[hostid]!=$hostid && $level!=1 && $row[hostschool]!=$school)
{
   echo "You are not the host for this district.";
   exit();
}
$hostschool=$row[hostschool];
$temp=split(",",$row[schools]);
if($disttimesid>0) $teammcount=2;
else $teamcount=count($temp); 
$gamecount=$teamcount-1;
$type=$row[type]; $class=$row["class"]; $district=$row[district];
$gender=$row[gender]; 
$dates="";
//GET DATES, SCHOOLS, SAMPLE INFO
if($disttimesid>0)
{
   $date=explode("-",$row[day]);
   $dates=date("M j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." at $row[time]";
   $sql2="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='$row[gamenum]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $schools=GetSchoolName($row2[sid],$sport)." vs. ".GetSchoolName($row2[oppid],$sport);
   if($sample==1)
   {
      $hostschool="Test's School"; $schools="Test's School vs Adams Central";
   }
}
else 
{
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
   $schools=$row[schools];
   if($schools=="") $schools="TBA";
   if($sample==1)  
   {   
      $hostschool="Test's School";
      $schools="Lincoln East, Lincoln High, Lincoln North Star, Lincoln Southeast, Lincoln Southwest";
   }
}
if($row[accept]!='')	//already responded to by host
{
   $director=$row[director]; $email=$row[email]; $site=$row[site];
}

if($edit==1 && $savechanges)
{
   $text1=ereg_replace("\r\n","<br>",$text1);
   $text1=addslashes($text1);
   $text2=ereg_replace("\r\n","<br>",$text2);
   $text2=addslashes($text2);
   $text3=ereg_replace("\r\n","<br>",$text3);
   $text3=addslashes($text3);
   $sql="UPDATE ".$sport."contracttext SET text1='$text1',text2='$text2',text3='$text3' WHERE district='2'";
   $result=mysql_query($sql);
}

//get contract text
$dist=2;
$sql="SELECT * FROM ".$sport."contracttext WHERE district='$dist'";
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
         if($accept=='y' && (trim($director)=='' || trim($email)=='' || trim($site)=='' || ($selectdate==1 && !$datech) || ($editdate==1 && ($mo=='' || $day==''))))
	    $error=1; 
    	 else
	 {
	    $director=addslashes($director);
	    $email=addslashes($email);
	    $site=addslashes($site);
            if($editdate==1)
            {
	       $dates=$yr."-".$mo."-".$day;
               $sql="UPDATE $districts SET dates='$dates' WHERE id='$distid'";
	       $result=mysql_query($sql);
               $dates=date("M j, Y",mktime(0,0,0,$mo,$day,$yr));
	    } 
            else if($selectdate==1)
            {
	       $temp=split("-",$datech);
	       $dates=$datech;
	       $sql="UPDATE $districts SET dates='$dates' WHERE id='$distid'";
	       $result=mysql_query($sql);
	       $dates=date("M j, Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
   	    }
      	    if($disttimesid>0)
	       $sql="UPDATE $disttimes";
	    else
	       $sql="UPDATE $districts";
   	    $sql.=" SET accept='$accept', director='$director', email='$email', site='$site' WHERE ";
	    if($disttimesid>0)
	       $sql.="id='$disttimesid'";
	    else
	       $sql.="id='$distid'";
	    $result=mysql_query($sql);
	 }
      }//end if Submit
   }//end if level!=1
   else
   {
      if($disttimesid>0) $sql="UPDATE $disttimes SET confirm='$confirm' WHERE id='$disttimesid'";
      else $sql="UPDATE $districts SET confirm='$confirm' WHERE id='$distid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td><br>";
echo "<form method=post action=\"hostcontract_".$sport.".php\">
	<input type=hidden name=session value=$session>
	<input type=hidden name=edit value=$edit>
	<input type=hidden name=sport value=$sport>
	<input type=hidden name=distid value=\"$distid\">
	<input type=hidden name=\"disttimesid\" value=\"$disttimesid\">";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_".$sport.".php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echO "<br><a class=small href=\"hostcontract_".$sport.".php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=left><td><font style=\"color:red\"><b>If you accept this contract, you MUST enter the Director's name and e-mail, as well as the site for the tournament.";
   if($selectdate==1 || $editdate==1) echo "<br><br>You must also select a date.";
   echo "</b></font></td></tr>";
}

if($disttimesid>0)
   $sql="SELECT accept,confirm,post FROM $disttimes WHERE id='$disttimesid' AND post='y'";
else
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
echo "<tr align=left><td><table>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td><b>$sportname Tournament Sites</b></td></tr>";
echo "<tr align=left><td><b>DATE:</b></td><td><b>".date("F j, Y")."</b>";
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
   echO "<input type=text class=tiny size=90 name=text1 value=\"$text1\">";
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=left><td><table>";
echo "<tr align=left><td width=170><b>Tournament:</b></td><td>$type $class-$district $sportname</td></tr>";
echo "<input type=hidden name=\"editdate\" value=\"0\">";
if($disttimesid>0)
{
   echo "<tr align=left><td><b>Date/Time:</b></td><td>$dates</td></tr>
	<tr align=lft><td><b>Schools:</b></td><td>$schools</td></tr>";
}
else
{
   echo "<tr align=left><td><b>Date(s):</b></td><td>$dates";
   if($edit==1)
      echo "&nbsp;&nbsp;<font style=\"color:red\">[Dates may be edited in the Host Contracts section where you assign the hosts.]</font>";
   echo "</td></tr>";
   echo "<tr align=left valign=top><td><b>Schools Tentatively Assigned:</b></td><td>$schools</td></tr>";
}
if($edit==1)
   echo "<tr align=left><td>&nbsp;</td><td><font style=\"color:red\">[Schools are indicated in the Host Contracts section where you assign the hosts.]</font></td></tr>";
if($accept=='y')
{
   echo "<tr align=left><td colspan=2><font style=\"color:red\"><b>";
   if($level==1) echo "$hostschool ";
   else echo "You ";
   echo "entered the following information for this district:</b></font>";
   if($confirm=='y' && $level!=1 && !$disttimesid)
      echo "<br>(To edit this information, <a class=small href=\"hostslots.php?session=$session&ad=1&sport=$sport&distid=$distid\">Click Here</a>)";
   echo "</td></tr>";
   echo "<tr align=left><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Director's E-mail:</b></td><td>$email</td></tr>";
   echo "<tr align=left><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Site:</b></td><td>$site</td></tr>";
   
   if(!$disttimesid) //NON CLASS A BASKETBALL
   {
      $sql="SELECT * FROM $disttimes WHERE distid='$distid' ORDER BY day, gamenum";
      $result=mysql_query($sql);
      $ix=1;
      echo "<tr align=left valign=top><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Time Slots:</b>";
      if($confirm!='y' && $level!=1) 
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<i>You will enter this information once the NSAA has confirmed your contract.</i>)";
      else if($confirm!='y' && $level==1)
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<i>$hostschool will enter this information once the NSAA has confirmed this contract.</i>)";
      echo "</td><td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=2><tr align=center><td><b>Game</b></td><td><b>Date</b></td><td><b>Time</b></td></tr>";
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
   } //END IF NOT $disttimesid (CLASS A BASKETBALL)
}
echo "</table></td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=text2 rows=5 cols=90>$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr>";

echo "<tr align=left><td>For more detailed information, please click on the <a class=small href=\"#\" onclick=\"window.open('".$sport."hostterms.php?session=$session&distid=$distid','".$sport."_terms','location=yes,scrollbars=yes,width=600');\">Terms & Conditions</a>.</td></tr>";

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))	//Host has not accepted yet
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echO "<input type=text class=tiny size=90 name=text3 value=\"$text3\">";
   else 
      echo $text3;
   echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<i>If you accept, you must complete ALL 3 fields below:</i><br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<input type=text class=tiny size=30 name=director value=\"$director\"> will serve as district director.<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "The director's e-mail address is: <input type=text class=tiny size=30 name=email value=\"$email\">.<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "The site will be <input type=text class=tiny size=30 name=site value=\"$site\">";
   echo "</td></tr>";
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
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district $sportname Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit ";
   if($sample==1) echo "disabled ";
   echo "value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the host's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><input type=submit name=submit ";
   if($sample==1) echo "disabled ";
   echo "value=\"Submit\"></td></tr>";
}
else
   echo "<tr align=center><td><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
echo "</table></form>";

echo $end_html;
?>
