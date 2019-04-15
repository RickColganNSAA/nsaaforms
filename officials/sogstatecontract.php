<?php
$sport='sog';

require 'functions.php';
require 'variables.php';
$thisyear=GetSchoolYear(date("Y"),date("m"));

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level==4) $level=1;

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;
if($edit==1 && $level==1) $sample=1;
else $edit=0;
if($level!=1) $sample=0;
if($sample==1) $offid=3427;

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";

$sql="SELECT * FROM $districts WHERE type='State'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$gender="Girls";
$dates="";
$date=split("/",$row[dates]);
for($i=0;$i<count($date);$i++)
{
   $temp=split("-",$date[$i]);
   $dates.=date("F j",mktime(0,0,0,$temp[1],$temp[2],$temp[0])).", ";
}
$dates.=$temp[0];

//Get disttimesid and type (stand-by or regular) for this contract
$sql="SELECT t1.id,t1.time FROM $disttimes AS t1,$contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$distid' AND t2.offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[time]=='standby') $type="standby";
else $type="state";
$disttimesid=$row[id];

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
   $sql="UPDATE sogcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4',text5='$text5' WHERE district='0'";
   $result=mysql_query($sql);
   //echo $sql."<br>";
   echo mysql_error();
}

//get contract text
$sql="SELECT * FROM sogcontracttext WHERE district='0'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; $text4=$row[text4];
$text5=$row[text5]; $text6=$row[text6];

if($submit)
{
   if($level!=1)
   {
      if(!$accept) $error=1;
      else
      {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND disttimesid='$disttimesid'";
         $result=mysql_query($sql);
      }
   }
   else if($level==1)
   {
      $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND disttimesid='$disttimesid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"sogstatecontract.php\">";
echo "<input type=hidden name=session value=$session>";
echO "<input type=hidden name=edit value=\"$edit\">";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"sogstatecontract.php?session=$session&sample=1\">Preview this Contract</a>";
else
   echo "<br><a class=small href=\"sogstatecontract.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";
$sql="SELECT t2.accept,t2.confirm,t2.post FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t2.offid='$offid' AND t2.disttimesid='$disttimesid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
if($error==1)
{
   echo "<tr align=center><td><font style=\"color:red\"><b>You must either accept or decline this contract.</b></font></td></tr>";
}
if($row[accept]=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo GetOffName($offid)." has ";
   echo "<b>accepted</b> the following contract.<br>";
   if($row[confirm]=='y')
   {
      echo "The NSAA has <b>confirmed</b> the following contract.";
   }
   else if($row[confirm]=='n')
   {
      echo "The NSAA has <b>rejected</b> the following contract.";
   }
   else if($level!=1)
   {
      echo "Please check back later to see if the NSAA has <b>confirmed</b> your contract.";
   }
   else
   {
      echo "The NSAA has not yet confirmed this contract.";
   }
}
else if($row[accept]=='n' && !$submit)
{
   if($level!=1)
      echo "<tr align=left><td>You have <b>declined</b> the following contract.<br>";
   else 
      echo "<tr align=left><td>This officials has <b>declined</b> the following contract.<br>";
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
      if($accept=='y')
      {
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  More information will be provided soon."; 
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

//Date and Official's Name
echo "<tr align=left><td><b>".date("F j, Y")."</b>";
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
echO "</td></tr>";
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>".GetOffName($offid);
   if($edit==1)
      echO "&nbsp;&nbsp;<font style=\"color:red\">[Official's Name & Address]</font>";
   echo "<br>$row[address]<br>$row[city], $row[state] $row[zip]";    
   echo "</td></tr>";

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

//Text (Body of Contract)
echo "<tr align=left><td>";
if($edit==1)
{
   echo "<font style=\"color:red\">For Stand-By Officials:<br></font>";
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=text2 rows=2 cols=90>$text2</textarea><br>";
   echo "<font style=\"color:red\">For Regular Officials:<br></font>";
   $text1=ereg_replace("<br>","\r\n",$text1);
   echo "<textarea name=text1 rows=2 cols=90>$text1</textarea>";
}
else
{
   if($type=='standby') echo $text2;
   else echo $text1;
}
echo "</td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea name=text3 rows=15 cols=90>$text3</textarea>";
}
else
   echo $text3;
echo "</td></tr>";

echo "<tr align=left><td>";
if($edit==1)
{
   $text4=ereg_replace("<br>","\r\n",$text4);
   echo "<textarea name=text4 rows=4 cols=90>$text4</textarea>";
}
else
   echO $text4;
echo "</td></tr>";

echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text name=text5 class=tiny size=90 value=\"$text5\">";
   else
      echo $text5;
   echo "</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   if($edit==1)
      echo "<tr align=center><td><br><br><input type=submit name=savechanges value=\"Save Changes\"></td></tr>"; 
   else
   {
      echo "<tr align=center><td><br><br><input type=submit name=submit ";
      if($sample==1) echo "disabled ";
      echo "value=\"Submit\"></td></tr>";
   }
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear State Soccer Tournament";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y' && $level==1)
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the official's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
echo "</table></form>";
echo "<a class=small href=\"javascript:window.close()\">Close</a>";

echo $end_html;
?>
