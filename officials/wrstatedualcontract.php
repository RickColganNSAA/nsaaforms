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
if($statesample==1 || $sample==1) "$offid=3427";

$disttimes=$sport."districts";
$contracts=$sport."contracts";

//FIRST MAKE SURE WE HAVE ALL THE FIELDS WE NEED FOR LODGING ON STATE AND STATE DUAL
$sql2="SELECT * FROM wrtourndates WHERE lodgingdate='x' ORDER BY tourndate";
$result2=mysql_query($sql2);
$i=0;
while($row2=mysql_fetch_array($result2))
{
   //CHECK THAT THERE IS A FIELD FOR THIS IN $contacts
   $num=$i+1; $field="date".$num;
   $sql3="SHOW COLUMNS FROM wrcontracts WHERE Field='$field'";
   $result3=mysql_query($sql3);
   if(mysql_num_rows($result3)==0)      //ADD FIELD
   {
      $sql3="ALTER TABLE wrcontracts ADD `$field` VARCHAR(5) NOT NULL";
      $result3=mysql_query($sql3);
   }
   $i++;
}

$sql2="SELECT * FROM wrtourndates WHERE lodgingdate='x' AND label = 'State Dual' ORDER BY tourndate";
$result2=mysql_query($sql2);
$wrlodging=array(); $wrlodging_sm=array(); $i=0;
while($row2=mysql_fetch_array($result2))
{
   $date=explode("-",$row2[tourndate]);
   $wrlodging[$i]=date("l, F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $wrlodging_sm[$i]=$date[1]."/".$date[2];
   $i++;
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
   $text6=ereg_replace("\r\n","<br>",$text6);
   $text6=addslashes($text6);
   $text7=ereg_replace("\r\n","<br>",$text7);
   $text7=addslashes($text7);
   $text8=ereg_replace("\r\n","<br>",$text8);
   $text8=addslashes($text8);
   $sql="UPDATE wrcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4',text5='$text5',text6='$text6',text7='$text7',text8='$text8' WHERE district='3'";
   $result=mysql_query($sql);
   $dates="";
   for($i=1;$i<=3;$i++)
   {
      $m="m".$i; $d="d".$i; $y="y".$i;
      $date=$$y."-".$$m."-".$$d;
      $dates.=$date."/";
   }
   $dates=substr($dates,0,strlen($dates)-1);
   $sql="UPDATE wrdistricts SET dates='$dates' WHERE type='State Dual'";
   $result=mysql_query($sql);
}

if($sample==1) $distid=20;
$sql="SELECT * FROM $disttimes WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type="State Dual";
$dates="";
$date=split("/",$row[dates]);
for($i=0;$i<count($date);$i++)
{
   if(!ereg("00-00",$date[$i]) && $date[$i]!='--' && $date[$i]!='')
   {
   $temp=split("-",$date[$i]);
   $dates.=date("M j",mktime(0,0,0,$temp[1],$temp[2],$temp[0])).", ";
   $num=$i+1;
   $mvar="m".$num; $$mvar=$temp[1];
   $dvar="d".$num; $$dvar=$temp[2];
   $yvar="y".$num; $$yvar=$temp[0];
    }
}
$dates.=$temp[0];
if($row[dates]=='' && $sample==1) $dates="Feb 23, 2013";

//get contract text
$sql="SELECT * FROM wrcontracttext WHERE district='3'";
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

if($submit || ($sample==1 && $lodging==1))
{
   if($level!=1 || ($sample==1 && $lodging==1))
   {
      if($submit=="Submit" || ($sample==1 && $lodging==1))
      {
         if($accept!='n' && !$child && !($sample==1 && $lodging==1))	//$child is required on State contract
	 {
	    $error=1; 
	 }
    	 else
	 {
	    if(!($sample==1 && $lodging==1))
	    {
               $sql="UPDATE $contracts SET accept='$accept',child='$child'";
	       $sql.=" WHERE offid='$offid' AND distid='$distid'";
               $result=mysql_query($sql);
	    }
            if($accept=='y' || ($sample==1 && $lodging==1))	//show lodging form
            {
               echo $init_html;
	       echo "<table width=100%><tr align=center><td>";
	       echo "<br>";
	       echo "<img src=\"nsaacontract.png\">";
	       if($sample==1 && $lodging==1)
		  echo "<br><a class=small href=\"wrstatedualcontract.php?session=$session&sample=1\">Return to Main Contract</a><br><br>";
	       echo "<form method=post action=\"wrstatedualcontract.php\">";
	       echo "<input type=hidden name=session value=$session>";
	       echo "<input type=hidden name=givenoffid value=$offid>";
	       echo "<input type=hidden name=distid value=$distid>";
	       echo "<input type=hidden name=edit value=\"$edit\">";
	       echo "<table cellspacing=2 cellpadding=2 width=500>";
	       echo "<caption align=center><b>NSAA $thisyear State Dual Wrestling Tournament Lodging Confirmation Form:</b></caption>";
               echo "<tr align=left><td><div class='normalwhite' style='width:500px;'><b>The NSAA will provide lodging for state Championships officials who qualify for and use lodging.  No lodging will be paid to an official living in the championship host city.  Rooms will be held in the official's name and any charges  beyond the single room rate are the responsibility of the official.  The NSAA will provide lodging for those officials who are selected to work state championship contests that are scheduled to begin at or after 6:00 PM and the official resides more than 100 miles from the championship site, if lodging is used. </b></div></td></tr>";
   	       echo "<tr align=left><td><b><br>Dates Needing Lodging (please check the appropriate date(s)</b></td></tr>";
	       for($i=0;$i<count($wrlodging);$i++)
	       {
	          $ix=$i+1;
	          $var="date".$ix;
	          echo "<tr align=left><td><input type=checkbox name=\"$var\" value='x'>&nbsp;";
	          echo "$wrlodging[$i]</td></tr>";
	       }
	       echo "<tr align=left><td><input type=text size=8 class=tiny name=arrive>&nbsp;";
	       echo "Approximate Arrival Time</td></tr>";
	       echo "<tr align=left><td><b>Any Special Requests: </b>";
	       echo "(smoking/non-smoking, bringing other family members, etc.)<br>";
	       echo "<textarea name=special cols=50 rows=5></textarea></td></tr>";
	       echo "<tr align=left><td>Contact the NSAA 402-489-0386 with any reservation changes or questions.  The Hotel is not authorized to make changes to existing NSAA reservations.  The NSAA will notify assigned officials where they will be staying in Kearney.</td></tr>";
	       echo "<tr align=left><td><u>Lodging:</u>&nbsp;For State Competition when lodging is required and used by an official, the NSAA will reserve and pay for those rooms.  Any charges beyond the single room rate are the responsibility of the official.  No lodging will be paid to an official living in the host city.</td></tr>";
	       echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Confirm\"";
	       if($sample==1 && $lodging==1) echo " disabled";
	       echo "></td></tr>";
	       echo "</table></form>";
	       echo $end_html;
	       exit();
   	    }//end if accept=='y'
	 }//end if $accept && $child given
      }//end if Submit
      else if($submit=="Confirm")	//lodging form submitted
      {
         $arrive=addslashes($arrive);
	 $special=addslashes($special);
	 $sql="UPDATE $contracts SET ";
	 for($i=1;$i<=count($wrlodging);$i++)
         {
	    $field="date".$i;
	    $sql.="$field='".$$field."', ";
	 }
	 $sql.=" arrive='$arrive', special='$special' WHERE distid='$distid' AND offid='$offid'";
	 $result=mysql_query($sql);
      }
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
echo "<form method=post action=\"wrstatedualcontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=edit value=$edit>";
echo "<table cellspacing=3 cellpadding=3 width=650>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($sample==1 && $edit!=1)
   echo "<br><a class=small href=\"wrstatedualcontract.php?session=$session&edit=1\">Edit this Contract</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"wrstatedualcontract.php?session=$session&sample=1&lodging=1\" class=small>Preview Lodging Portion</a>";
else if($edit==1)
   echo "<br><a class=small href=\"wrstatedualcontract.php?session=$session&sample=1\">Preview this Contract</a>";
echo "</td></tr>";

if($error==1)
{
   echo "<tr align=center><td><font style=\"color:red\"><b>If you accept this contract, you MUST check Yes or No to the question below pertaining to officials with a son/daughter who might qualify for the State Dual Wrestling Tournament.</b></font></td></tr>";
}

$sql="SELECT accept,confirm,post FROM $contracts WHERE offid='$offid' AND distid='$distid'";
if($level!=1) $sql.=" AND post='y'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 && $sample!=1)	//NO CONTRACT FOR THIS OFFICIAL AND THIS GAME!
{
   echo "<tr align=center><th align=center>No Contract for given official & contest.</th></tr>";
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

$sql="SELECT * FROM $disttimes WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($sample==1) $offid="3427";

echo "<tr align=left><td>Dear ".GetOffName($offid).":";
if($edit==1)
   echo "&nbsp;&nbsp;<font color=red>[Official's Name]</font>";
echo "<br><br></td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   echo "<input type=text size=80 class=tiny name=\"text1\" value=\"$text1\">";
   echo "<br><font color=red>State Dual Tournament Date:<br></font>";
   for($i=1;$i<=1;$i++)
   {
      $month="m".$i; $day="d".$i; $year="y".$i;
      echo "<select name=\"$month\"><option value=''>mm</option>";
      for($j=1;$j<=12;$j++)
      {
	 if($j<10) $m="0".$j;
	 else $m=$j;
	 echo "<option";
	 if($$month==$m) echo " selected";
	 echo ">$m</option>";	
      }
      echo "</select>/<select name=\"$day\"><option value=''>dd</option>";
      for($j=1;$j<=31;$j++)
      {
         if($j<10) $m="0".$j;
         else $m=$j;
         echo "<option";
         if($$day==$m) echo " selected";
         echo ">$m</option>";   
      }  
      echo "</select>/<select name=\"$year\"><option value=''>yyyy</option>";
      $yearlow=date("Y")-1; $yearhi=$yearlow+2;
      for($j=$yearlow;$j<=$yearhi;$j++)
      {
         echo "<option";
         if($$year==$j) echo " selected";
         echo ">$j</option>";
      }
      echo "</select><br>";
   }
}
else
   echo $text1." ".$dates.".";
echo "</td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   echo "<font style=\"color:red;font-size:9pt;\"><b>PLEASE NOTE:</b><br>Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>.  Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.  Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br></font>";
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea rows=25 cols=90 name=\"text2\">$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr><tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea rows=3 cols=90 name=\"text3\">$text3</textarea>"; 
}
else
   echo $text3;
echo "</td></tr>";
//if official accepted, retrieve submitted info
$sql="SELECT * FROM $contracts WHERE offid='$offid' AND distid='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$child=$row[child]; 
for($i=1;$i<=count($wrlodging);$i++)
{
   $var="date".$i;
   $$var=$row[$var];
}
$arrive=$row[arrive]; $special=$row[special];


if($accept!='n')
{
   echo "<tr align=left><td>";
   if($accept!='y')
      echo "<input type=radio name=child value='y'>&nbsp;";
   else if($accept=='y' && $child=='y')
      echo "<u><b>&nbsp;X&nbsp;</b></u>";
   else if($accept=='y')
      echo "<u>&nbsp;&nbsp;&nbsp;</u>";
   echo "&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=80 name=\"text7\" value=\"$text7\">";
   else
      echo $text7;
   echo "<br>";
   if($accept!='y')
      echo "<input type=radio name=child value='n'>&nbsp;";
   else if($accept=='y' && $child=='n')
      echo "<u><b>&nbsp;X&nbsp;</b></u>";
   else if($accept=='y')
      echo "<u>&nbsp;&nbsp;&nbsp;</u>";
   echo "&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=80 name=\"text8\" value=\"$text8\">";
   else
      echo $text8;
   echo "</td></tr>";
}

echo "<tr align=left><td>";
if($edit==1)
{
   $text4=ereg_replace("<br>","\r\n",$text4);
   echo "<textarea rows=2 cols=90 name=\"text4\">$text4</textarea>";
}
else
   echo $text4;
echo "</td></tr><tr align=left><td>";
if($edit==1)
{
   $text5=ereg_replace("<br>","\r\n",$text5);
   echo "<textarea rows=2 cols=90 name=\"text5\">$text5</textarea>";
}
else
   echo $text5;

if($accept=='y')
{
   //Show other contract info (lodging confirmation form)
   echo "<tr align=left><td><br>";
   echo "<b>Dates Needing Lodging:</b><br>";
   for($i=0;$i<count($wrlodging);$i++)
   {
      $ix=$i+1; $var="date".$ix;
      if($$var=='x') echo "$wrlodging[$i]<br>";
   }
   echo "<b>Approximate Arrival Time:</b> $arrive<br><br>";
   echo "<b>Special Requests:&nbsp;</b>";
   if(trim($special)!='')
      echo $special;
   else echo "None";
   echo "</td></tr>";
}
echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</td></tr>";
if($accept!='y' && $accept!='n' && ($level!=1 || $sample==1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
   {
      $text6=ereg_replace("<br>","\r\n",$text6);
      echo "<textarea rows=3 cols=80 name=\"text6\">$text6</textarea>";
   }
   else
      echo $text6;
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
