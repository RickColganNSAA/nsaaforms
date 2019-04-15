<?php
$sport='vb';

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
if($edit==1 && $level==1) $sample=1;
else $edit=0;
if($sample==1 && $level!=1) $sample=0;

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;
if($sample==1) $offid="3427";

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";

$sql="SELECT * FROM vbtourndates WHERE lodgingdate='x' ORDER BY tourndate";
$result=mysql_query($sql);
$vblodging=array(); $s=0;
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[tourndate]);
   $vblodging[$s]=date("l, M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $vblodging2[$s]="$date[1]/$date[2]";
   $i2=$i+1; $field="lodging".$i2;
   $sql2="SHOW FULL COLUMNS FROM vbcontracts WHERE Field='$field'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="ALTER TABLE vbcontracts ADD `$field` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
   }
   $s++;
}

//Get disttimesid for this contract
$sql="SELECT t1.id,t1.time FROM $disttimes AS t1,$contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$distid' AND t2.offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$type="state";
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
   $sql="UPDATE vbcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4',text5='$text5' WHERE district='0'";
   $result=mysql_query($sql);
   if(mysql_error()) echo $sql."<br>".mysql_error();
}

//get contract text
$sql="SELECT * FROM vbcontracttext WHERE district='0'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; $text4=$row[text4];
$text5=$row[text5]; $text6=$row[text6];

if($submit || $submit2)
{
   if($level!=1 && (($submit && (($lodging && $accept) || $accept=='n')) || $submit2))
   {
      if($submit)
      {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND disttimesid='$disttimesid'";
         $result=mysql_query($sql);
         if($accept=='y' && $lodging=='y')	//show LODGING FORM
	 {
	    echo $init_html;
            echo "<table width=100%><tr align=center><td><br>";
	    echo "<form method=post action=\"vbstatecontract.php\">";
	    echo "<input type=hidden name=session value=$session>";
  	    echo "<input type=hidden name=givenoffid value=$offid>";
	    echo "<input type=hidden name=distid value=$distid>";
	    echo "<table cellspacing=3 cellpadding=3>";
	    echo "<tr align=center><td>";
	    echo "<img src=\"nsaacontract.png\">";
	    echo "</td></tr>";
            echo "<tr align=left><td><div class='normalwhite' style='width:500px;'><b>$text5</b></div></td></tr>";
	    echo "<tr align=left><td><br><br><b>Please check the dates you need lodging:</b><br>";
	    for($i=0;$i<count($vblodging);$i++)
	    {
	       $num=$i+1; $var="lodging".$num;
	       echo "<input type=checkbox name=\"$var\" value='x'> $vblodging[$i]<br>";
	       if($i==0)	//ask for arrival time:
	       {
	          echo "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Approximate Time of Arrival:&nbsp;</b>";
                  echo "<input type=text class=tiny size=10 name=\"arrive\"><br>";
	       }
	    }
	    echo "</td></tr>";
	    echo "<tr align=center><td><input type=submit name=submit2 value=\"Submit\"></td></tr>";
	    echo "</table></form>";
	    echo $end_html;
	    exit();
         }
      }
      elseif($submit2)
      {
	 //store lodging form info
	 $arrive=addslashes($arrive);	
	 $sql="UPDATE $contracts SET ";
         for($i=1;$i<=count($vblodging);$i++)
	 {
	    $var="lodging".$i;
	    $sql.="$var='".$$var."', ";
         }
	 $sql.="arrive='$arrive' WHERE offid='$offid' AND disttimesid='$disttimesid'";
	 $result=mysql_query($sql);
         //echo "$sql<br>".mysql_error();
	 header("Location:vbstatecontract.php?session=$session&distid=$distid&accepted=1");
	 exit();
      }
   }
   else if($level!=1 && (!$accept || ($accept=='y' && !$lodging)))	//did not answer both questions
   {
      $error=1;
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
echo "<form method=post action=\"vbstatecontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echO "<br><a class=small href=\"vbstatecontract.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echO "<br><a class=small href=\"vbstatecontract.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";
$sql="SELECT t2.accept,t2.confirm,t2.post FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t2.offid='$offid' AND t2.disttimesid='$disttimesid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
if($error==1)
{
   echo "<tr align=left><td><font style=\"color:red\"><b>You must either accept or decline this contract.  If you are accepting this contract, you must check whether or not you need lodging.</b></font></td></tr>";
}
if($row[accept]=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo GetOffName($offid)." has ";
   echo "<b>accepted</b> the following contract.";
   if($accepted==1) echo "  More information will be e-mailed to you soon.";
   echo "<br>";
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
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  More information will be e-mailed to you soon."; 
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
echo "<tr align=left><td><b>".date("F j, Y");
if($edit==1)
   echo "&nbsp;&nbsp;<font style=\"color:red\">[Today's Date]</font>";
echo "</b></td></tr>";
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>".GetOffName($offid);
   if($edit==1)
      echo "&nbsp;&nbsp;<font style=\"color:red\">[Official's Name & Address]</font>";
   echo "<br>$row[address]<br>$row[city], $row[state] $row[zip]";    
   echo "</td></tr>";

if($edit==1)
{
   echo "<tr align=left><td><font style=\"color:blue;font-size:9pt;\"><b>PLEASE NOTE:</b><br>";
   echo "Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>. <br>";
   echo "Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.<br>";
   echo "Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br>";
   echo "Words between &lt;font style='color:red'&gt; and &lt;/font&gt; will be <font style=\"color:red\">RED</font>.";
   echo "</td></tr>";
}

//Text (Body of Contract)
echo "<tr align=left><td>";
if($edit==1)
{
   $text1=ereg_replace("<br>","\r\n",$text1);
   echo "<textarea rows=2 cols=90 name=text1>$text1</textarea>";
}
else
   echo $text1;
echo "</td></tr>";

$sql="SELECT * FROM $districts WHERE type='State'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

echo "<tr align=left><td>";
if($edit==1)
{
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea rows=15 cols=90 name=text2>$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr>";

//LODGING QUESTION
if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))
{
   echo "<tr align=left><td>";
   echo "<input type=radio name=\"lodging\" value='y'>&nbsp;<b>YES, I need lodging</b> (You will be able to indicate which nights and other details on the next screen)</td></tr>";
   if($sample==1)
   {
      echo "<tr align=center><td><font style=\"color:blue\"><b>PREVIEW OF LODGING FORM (shows on next screen if official ACCEPTS):<br></font></b><table cellspacing=3 cellpadding=3 style=\"background-color:#E0E0E0\">";
      echo "<tr align=left><td><div class='normalwhite' style='width:500px;'>";
      if($edit==1)
         echo "<textarea name=\"text5\" rows=5 cols=70>$text5</textarea>";
      else
         echo "$text5";
      echo "</div></td></tr>";
      echo "<tr align=left><td><br><b>Please check the dates you need lodging:</b><br>";
      for($i=0;$i<count($vblodging);$i++)
      {
         $num=$i+1; $var="lodging".$num;
         echo "<input type=checkbox name=\"$var\" value='x'> $vblodging[$i]<br>";
         if($i==0)        //ask for arrival time:
         {
            echo "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Approximate Time of Arrival:&nbsp;</b>";
            echo "<input type=text class=tiny size=10 name=\"arrive\"><br>";
         }
      }
      echo "</td></tr>";
      echo "<tr align=center><td><input type=submit name=submit2 disabled value=\"Submit\"></td></tr>";
      echo "</table></td></tr>";
   }
   echo "<tr align=left><td><input type=radio name=lodging value='n'>&nbsp;<b>No, I do not need lodging</b></td></tr>";
}
else if($accept=='y')
{
   $sql2="SELECT * FROM $contracts WHERE offid='$offid' AND disttimesid='$disttimesid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><td><b>Lodging Requirements:</b></td></tr>";
   if($row2[lodging1]=='' && $row2[lodging2]=='' && $row2[lodging3]=='' && $row2[lodging4]=='' && $row2[arrive]=='')
   {
      echo "<tr align=left><td>[None]</td></tr>";
   }
   else
   {
      echo "<tr align=left><td>";
      for($i=0;$i<count($vblodging);$i++)
      {
         $num=$i+1; $field="lodging".$num; 
         if($row2[$field]=='x') echo "<u>&nbsp;<b>X</b>&nbsp;</u>&nbsp;";
         else echo "<u>&nbsp;&nbsp;&nbsp;&nbsp;</u>&nbsp;";
         echo $vblodging[$i]."<br>";
	 if($i==0)
	    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Approximate Arrival Time:</b> $row2[arrive]<br>";
      }
      echo "</td></tr>";
   }
}

//Legal Wording
echo "<tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea name=\"text3\" rows=3 cols=90>$text3</textarea>";
}
else
   echo "<p>".$text3."</p>";
echo "</td></tr>";

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny size=90 name=text4 value=\"$text4\">";
   else
      echo $text4;
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
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear State Volleyball Tournament";
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
