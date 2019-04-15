<?php
$sport='wr';

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
if($edit==1 && $level!=1) $edit=0;
if($edit==1) $sample=1;

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;
if($level!=1) 
{
   $edit=0; $sample=0;
}
if($edit==1) $sample=1;
if($sample==1) $offid="3427";

$disttimes=$sport."districts";
$contracts=$sport."contracts";

if($sample==1) $distid=10;
$sql="SELECT * FROM $disttimes WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type=$row[type];
$dates="";
$date=split("/",$row[dates]);
for($i=0;$i<count($date);$i++)
{
   $temp=split("-",$date[$i]);
   $dates.=date("M j",mktime(0,0,0,$temp[1],$temp[2],$temp[0])).", ";
}
$dates.=$temp[0];

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
   $text6=ereg_replace("\r\n","<br>",$text6);
   $text6=addslashes($text6);
   $text7=ereg_replace("\r\n","<br>",$text7);
   $text7=addslashes($text7);
   $text8=ereg_replace("\r\n","<br>",$text8);
   $text8=addslashes($text8);
   $text9=ereg_replace("\r\n","<br>",$text9);
   $text9=addslashes($text9);
   $sql="UPDATE wrcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4',text5='$text5',text6='$text6',text7='$text7',text8='$text8',text9='$text9' WHERE district='1'";
   $result=mysql_query($sql);
}

//get contract text
$district=1;
$sql="SELECT * FROM wrcontracttext WHERE district='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; 
$text2=$row[text2];
$text3=$row[text3]; 
$text4=$row[text4];
$text5=$row[text5];
$text6=$row[text6];
$text7=$row[text7];
$text8=$row[text8];
$text9=$row[text9];

if($submit)
{
   if($level!=1)
   {
      if($submit=="Submit")
      {
            $sql="UPDATE $contracts SET accept='$accept'";
	    $sql.=" WHERE offid='$offid' AND distid='$distid'";
            $result=mysql_query($sql);
      }//end if Submit
   }//end if level!=1
   else
   {
      $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND distid='$distid'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"wrcontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"wrcontract.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echO "<br><a class=small href=\"wrcontract.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";

$sql="SELECT accept,confirm,post FROM $contracts WHERE offid='$offid' AND distid='$distid' AND post='y'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 && $sample!=1)	//NO CONTRACT FOR THIS OFFICIAL AND THIS GAME!
{
   echo "<tr align=center><th align=center>No Contract.</th></tr>";
   echo "</table>";
   echo $end_html;
   exit();
}
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
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
      if($accept=='y' || $submit=="Confirm")
      {
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.";
         echo " Once the NSAA confirms this contract, the District Director will contact you with specific information.  Please do not announce your selection as an official!";
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

   echo "<tr align=left><td>".date("F j, Y");
   if($edit==1) 
      echo "<font color=red>&nbsp;&nbsp;[Today's Date]</font>";
   echo "</td></tr>";
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result); 
   echo "<tr align=left><td>".GetOffName($offid);
   if($edit==1)
      echo "<font color=red>&nbsp;&nbsp;[Official's Name & Address]</font>";
   echo "<br>$row[address]<br>$row[city], $row[state] $row[zip]";
   echo "</td></tr>";
   echo "<tr align=left><td>";
   if($edit==1)
      echo "<input type=text class=tiny size=90 name=\"text1\" value=\"$text1\">";
   else
      echo $text1;
   echo "</td></tr>";

   $sql="SELECT * FROM $disttimes WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>";
   echo "<b>District $row[class]-$row[district]</b>";
   if($edit==1)
      echo "<font color=red>&nbsp;&nbsp;[District this contract is for]</font>";
   echo "</td></tr>";
   $dates="";
   $date=split("/",$row[dates]);
   for($i=0;$i<count($date);$i++)
   {
      $temp=split("-",$date[$i]);
      $dates.=date("F j",mktime(0,0,0,$temp[1],$temp[2],$temp[0])).", ";
   }
   $dates.=$temp[0];
   if($sample==1) $dates="February 9, February 10";
   echo "<tr align=left><td><b>Date(s):</b> $dates";
   if($edit==1)
      echo "<font color=red>&nbsp;&nbsp;[You may edit this district's information in the Host Contracts section.]</font>";
   echo "</td></tr><tr align=left><td>";
   if($sample==1) $row[site]="Beatrice High School";
   echo "<b>Site:</b> $row[site]</td></tr>";
   if($sample==1) 
   {
      $row[director]="Randy Coleman"; $row[hostschool]="Beatrice";
   }
   echo "<tr align=left><td><b>District Director:</b>$row[director] ($row[hostschool])</td></tr>";
   if($sample==1)
      $row[schools]="Auburn, Beatrice, Crete, Fairbury, Falls City, Gretna, Nebraska City, Norris, Platteview, Plattsmouth, Syracuse, Waverly";
   echo "<tr align=left><td><b>Teams Assigned:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $row[schools]<br>";
   echo "</td></tr>";

   //get partners
   $sql="SELECT offid FROM $contracts WHERE offid!='$offid' AND distid='$distid'";
   $result=mysql_query($sql);
   $partners="";
   while($row=mysql_fetch_array($result))
   {
      $partners.=GetOffName($row[offid]).", ";
   }
   $partners=substr($partners,0,strlen($partners)-2);
   if($sample==1)
      $partners="Larry Jones, John Smith, Tom Johnson";
   echo "<tr align=left><td><b>Assigned Officials:</b>";
   if($edit==1) 
      echo "<font color=red>&nbsp;&nbsp;[You may edit each official's assignments in the Officials Contracts section.]</font>";
   echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$partners</td></tr>";
   if($edit==1)
   {
      echo "<tr align=left><td><font style=\"color:red;font-size:9pt;\"><b>PLEASE NOTE:</b><br>Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>.  Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.  Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br></font></td></tr>";
      $text5=ereg_replace("<br>","\r\n",$text5);
      echo "<tr align=left><td><textarea rows=2 cols=90 name=\"text5\">$text5</textarea></td></tr>";
   }
   else
      echo "<tr align=left><td>$text5</td></tr>";
   echo "<tr align=left><td><table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3><tr align=center><td><b>Class</b></td><td><b>Fee</b></td></tr>";
   if($edit==1)
   {
      echo "<tr align=center><td>A</td><td><input type=text class=tiny size=8 name=\"text6\" value=\"$text6\"></td></tr>";
      echo "<tr align=center><td>B</td><td><input type=text class=tiny size=8 name=\"text7\" value=\"$text7\"></td></tr>";
      echo "<tr align=center><td>C</td><td><input type=text class=tiny size=8 name=\"text8\" value=\"$text8\"></td></tr>";
      echo "<tr align=center><td>D</td><td><input type=text class=tiny size=8 name=\"text9\" value=\"$text9\"></td></tr>";
   }
   else
   {
      echo "<tr align=center><td>A</td><td>$text6</td></tr>";
      echo "<tr align=center><td>B</td><td>$text7</td></tr>";
      echo "<tr align=center><td>C</td><td>$text8</td></tr>";
      echo "<tr align=center><td>D</td><td>$text9</td></tr>";
   }
   echo "</table></td></tr>";
   echo "<tr align=left><td>";
   if($edit==1)
   {
      $text2=ereg_replace("<br>","\r\n",$text2);
      echo "<textarea rows=20 cols=90 name=\"text2\">$text2</textarea>";
   }
   else
      echo $text2;
   echo "</td></tr>";
   echo "<tr align=left><td>";
   if($edit==1)
   {
      $text3=ereg_replace("<br>","\r\n",$text3);
      echo "<textarea rows=2 cols=90 name=\"text3\">$text3</textarea>";
   }
   else
      echo $text3;
   echo "</td></tr>";

echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=90 name=\"text4\" value=\"$text4\">";
   else
      echo $text4;
   echo "</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   if($edit==1)
      echo "<tr align=center><td><br><br><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"";
      if($sample==1) echo " disabled";
      echo "></td></tr>";
   }
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear $type Wrestling Tournament";
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
