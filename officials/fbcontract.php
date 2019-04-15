<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
$thisyear=GetSchoolYear(date("Y"),date("m"));
$thisyr=GetFallYear('fb');

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$sport='fb';

$level=GetLevel($session);
if($level==4) $level=1;
if($level==1 && $edit==1) $sample=1;
if($level!=1) $edit=0;

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;

$disttimes=$sport."brackets";
$contracts=$sport."contracts";
$sql="SELECT * FROM $disttimes WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[round]=="Finals")
{
   header("Location:fbstatecontract.php?session=$session&gameid=$gameid&offid=$offid");
   exit();
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
   $sql="UPDATE fbcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4' WHERE finals='0'";
   $result=mysql_query($sql);
}

//get contract text from DB
$sql="SELECT * FROM fbcontracttext WHERE finals='0'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1];
$text2=$row[text2];
$text3=$row[text3];
$text4=$row[text4];

if($submit)
{
   if($level!=1)
   {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND gameid='$gameid'";
         $result=mysql_query($sql);
   }
   else
   {
	 $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND gameid='$gameid'";
	 $result=mysql_query($sql);
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td><br>";
//echo "<a class=small href=\"javascript:window.close()\">Close</a>";
echo "<form method=post action=\"fbcontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=gameid value=$gameid>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\">";
if($edit==1)
   echo "<br><a class=small href=\"fbcontract.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1)
   echo "<br><a class=small href=\"fbcontract.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";
$sql="SELECT t2.accept,t2.confirm,t2.post FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.gameid AND t2.offid='$offid' AND t2.gameid='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
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
      if($accept=='y')
      {
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  Once the NSAA confirms this contract, the District Director will contact you with specific information.  Please do not announce your selection as an official!<br><br></td></tr></table></td></tr>";
      }
      else if($accept=='n')
      {
         echo "<tr align=center><td>This confirms that you are not accepting this contract..<br><br></td></tr>";
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
if($sample==1) { $offid='3427'; $gameid=1; }
else $offname=GetOffName($offid);
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>".GetOffName($offid);
   if($edit==1)
      echo "&nbsp;&nbsp;<font color=red>[Official's Name & Address]</font>";
   echo "<br>$row[address]<br>$row[city], $row[state] $row[zip]";    
   echo "</td></tr>";
echo "<tr align=left><td>";
if($edit==1)
   echo "<input type=text class=tiny size=90 name=\"text1\" value=\"$text1\">";
else
   echo $text1;
echo "</td></tr>";
$sql="SELECT * FROM $disttimes WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $round=$row[round];
if($class=='A' || $class=='B' || $class=="C1" || $class=="C2"||$class=="D6")
   $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
else
   $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
for($i=0;$i<count($rounds);$i++)
{
   if($rounds[$i]==$round) $roundnum=$i+1;
}
$sql2="SELECT * FROM $db_name.fbsched WHERE class='$class' AND round='$roundnum' AND gamenum='$row[gamenum]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left><td>";
echo "Class $class, $round";
if($edit==1)
   echo "<font color=red>&nbsp;&nbsp;[Game Information can be edited under the Host Contracts section.]</font>";
echo "<br>";
if($sample==1) $row2[received]=date("Y")."-11-15";
$date=split("-",$row2[received]);
echo "Date: ".date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."<br>";
if($sample==1) $row2[gametime]="7:00PM";
echo "Time: $row2[gametime]<br>";
echo "Host School: ";
$hostschool=GetSchoolName($row2[homeid],'fb',$thisyr);
if($sample==1) $hostschool="Test's School";
echo $hostschool;
$hostschool2=addslashes($hostschool);
echo "<br>";
if($sample==1)
{
   $school1="Test's School"; $school2="Omaha North";
}
else
{
   $school1=GetSchoolName($row2[sid],'fb',$thisyr);
   $school2=GetSchoolName($row2[oppid],'fb',$thisyr);
}
echo "Teams Competing:  $school1 VS $school2<br>";
echo "</td></tr>";

echo "<tr align=left><td>Crew Members:";
if($edit==1)
   echo "&nbsp;&nbsp;<font color=red>[The Crew Chief lists his crew members on his application.]</font>";
echo "<br>";
$sql="SELECT * FROM fbapply WHERE chief='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($sample==1)
{
   echo "Crew Chief: John Doe<br>Referee: Richard Smith<br>Umpire: Frank Johnson<br>Linesman: Jason Gall<br>Linejudge: Drew Anderson<br>Backjudge: Anthony Brown";
}
else
{
   echo "Crew Chief: ".GetOffName($row[chief])."<br>";
   echo "Referee: ".GetOffName($row[referee])."<br>";
   echo "Umpire: ".GetOffName($row[umpire])."<br>";
   echo "Linesman: ".GetOffName($row[linesman])."<br>";
   echo "Linejudge: ".GetOffName($row[linejudge])."<br>";
   echo "Backjudge: ".GetOffName($row[backjudge])."<br>";
}
echo "</td></tr>";

echo "<tr align=left><td>";
if($edit==1)
{
   echo "<tr align=left><td><font style=\"color:red;font-size:9pt;\"><b>PLEASE NOTE:</b><br>Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>.  Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.  Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br></font>";
   $text2=ereg_replace("\r\n","",$text2);
   $text2=ereg_replace("<br>","\r\n",$text2);
   echo "<textarea name=\"text2\" rows=3 cols=90>$text2</textarea>";
}
else
   echo $text2;
echo "</td></tr>";
echo "<tr align=left><td>";
if($edit==1)
{
   $text3=ereg_replace("\r\n","",$text3);
   $text3=ereg_replace("<br>","\r\n",$text3);
   echo "<textarea name=\"text3\" rows=6 cols=90>$text3</textarea>";
}
else
   echo $text3;
echo "</td></tr>";

echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && ($sample==1 || $level!=1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text class=tiny name=\"text4\" size=80 value=\"$text4\">";
   else
      echo $text4;
   echo "</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   if($edit==1)
      echo "<tr align=center><td><br><br><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else
   {
      echo "<tr align=center><td><br><br><input type=submit name=submit";
      if($sample==1) echo " disabled";
      echo " value=\"Submit\"></td></tr>";
   }
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear Football Playoffs";
   echo ".</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='n'>&nbsp;&nbsp;&nbsp;The NSAA rejects this contract.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
else if($accept=='n' && $confirm!='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA acknowledges the official's decline of the above agreement.</td></tr>";
   echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
echo "</table></form>";
echo "<a class=small href=\"javascript:window.close()\">Close</a>";

echo $end_html;
?>
