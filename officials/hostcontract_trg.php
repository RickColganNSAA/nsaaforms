<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;
if($edit==1 && $level==1) $sample=1;
else $edit=0;
if($sample==1) $distid='1';

$sport="trg";
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
//Get District Information: 
$sql="SELECT * FROM $districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[hostid]!=$hostid && $level!=1 && $school!=$row[hostschool])
{
   echo "You are not the host for this district.";
   exit();
}
$hostschool=$row[hostschool];
$temp=split(",",$row[school]);
$teamcount=count($temp); 
$gamecount=$teamcount-1;
$type=$row[type]; $class=$row["class"]; $district=$row[district];
$gender=$row[gender]; 
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
$schools=$row[schools];
if($schools=="") $schools="TBA";
if($sample==1)
{
   $hostschool="Test's School";
   $schools="Lincoln High, Lincoln Southeast, Lincoln Pius X, Lincoln North Star, Lincoln East, Lincoln North Star";
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
   $text4=ereg_replace("\r\n","<br>",$text4);
   $text4=addslashes($text4);
   $text5=ereg_replace("\r\n","<br>",$text5);
   $text5=addslashes($text5);
   $sql="UPDATE trcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4',text5='$text5' WHERE district='2'";
   $result=mysql_query($sql);
}

//get contract text
$dist=2;
$sql="SELECT * FROM trcontracttext WHERE district='$dist'";
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
echo "<form method=post action=\"hostcontract_trg.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=$edit>";
echo "<input type=hidden name=sport value=$sport>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=500>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"hostcontract_trg.php?session=$session&sample=1\">Preview this Contract</a>";
else if($level==1)
   echo "<br><a class=small href=\"hostcontract_trg.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=left><td><font style=\"color:red\"><b>If you accept this contract, you MUST enter the Director's name and e-mail, as well as the site for the tournament.";
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
if($accept=='y' && !$submit && !$sample)
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
else if($accept=='n' && !$submit && !$sample)
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
else if($sample && $edit)
   echo "<tr align=left><td><div class=error>You are now EDITING the text that will show on ALL $sportname contracts. You are NOT editing a specific contract for a specific district.</div>";
else if($sample)
   echo "<tr align=left><td><div class=error>You are previewing a SAMPLE $sportname contract.</div>";
else echo "<tr><td>";
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
echo "<tr align=left><td><b>TO:</b></td><td><b>$hostschool</b></td></tr>";
echo "<tr align=left><td><b>FROM:</b></td><td>";
if($edit==1)
   echo "<input type=text class=tiny size=50 name=text1 value=\"$text1\">";
else
   echo $text1;
echo "</td></tr>";
echo "<tr align=left><td><b>SUBJECT:</b></td><td>";
if($edit==1)
   echo "<input type=text class=tiny size=50 name=text2 value=\"$text2\">";
else
   echo $text2;
echo "</td></tr>";
echo "<tr align=left><td><b>DATE:</b></td><td><b>".date("F j, Y")."</b>";
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
   echo "Words between &lt;font style='color:red'&gt; and &lt;/font&gt; will be <font style=\"color:red\">RED<
/font>.";
   echo "</td></tr>";
}

echo "<tr align=left><td><br>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea rows=2 cols=90 name=text3>$text3</textarea>";
}
else
   echo $text3;
echo "</td></tr>";

echo "<tr align=left><td><b>District $class-$district</b><br><b>Date:</b>&nbsp;$dates";
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\"><br>[This date can be added/edited for each district in the Host Contract section where the host assignments are made.]</font>";
echo "<br><b>Schools assigned:</b>";
if($edit==1)
   echo "&nbsp;&nbsp;<br><font style=\"color:red\">[These schools can be edited for each district in the Host Contract section where the host assignments are made. Below are SAMPLE SCHOOLS.]</font>";
echo "</td></tr>";
$schools=split(",",$schools);
echo "<tr align=left><td>";
for($i=0;$i<count($schools);$i++)
{
   echo $schools[$i]."<br>";
}
echo "</td></tr>";
if($accept=='y')
{
   echo "<tr align=center><td><table>";
   echo "<tr align=left><td colspan=2><font style=\"color:red\"><b>";
   if($level==1) echo "$hostschool ";
   else echo "You ";
   echo "entered the following information for this district:</b></font>";
   echo "</td></tr>";
   echo "<tr align=left><td width=150>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Director:</b></td><td>$director</td></tr>";
   echo "<tr align=left><td width=150>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Director's E-mail:</b></td><td>$email</td></tr>";
   echo "<tr align=left><td width=150>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Site:</b></td><td>$site</td></tr>";
   echo "</table></td></tr>";
}

echo "<tr align=left><td>";
if($edit==1)
{
   $text4=ereg_replace("<br>","\r\n",$text4);
   echo "<textarea name=text4 rows=8 cols=90>$text4</textarea>";
}
else
   echo $text4;
echo "</td></tr>";

echo "<tr align=left><td>Before agreeing to accept this contract, please review the information about hosting this district by clicking on <a class=small href=\"#\" onclick=\"window.open('trhostterms.php?session=$session&distid=$distid','".$sport."_terms','location=yes,scrollbars=yes,width=600');\">Terms & Conditions</a>.</td></tr>";

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))	//Host has not accepted yet
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=90 name=text5 value=\"$text5\">";
   else
      echo $text5;
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
      echO "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><input type=submit name=submit";
      if($sample==1) echo " disabled";
      echO " value=\"Submit\"></td></tr>";
   }
}
else if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $type $class-$district Boys & Girls Track & Field Meet.";
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
{
   if($edit==1 && $level==1)
      echO "<tr align=center><td><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   echo "<tr align=center><td><a class=small href=\"javascript:window.close()\">Close</a></td></tr>";
}
echo "</table></form>";

echo $end_html;
?>
