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
if($edit==1 && $level!=1) $edit=0;
if($edit==1) $sample=1;

if(!$givenoffid) $offid=GetOffID($session);
else $offid=$givenoffid;
if($sample==1) $offid="3427";
$offname=GetOffName($offid);

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";

//get array of games this official is hosting in this district
$sql="SELECT t1.id,t2.type FROM $disttimes AS t1, $districts AS t2, $contracts AS t3 WHERE t1.distid=t2.id AND t3.disttimesid=t1.id AND t3.offid='$offid' AND t2.id='$distid' ORDER BY t1.day,t1.time";
$result=mysql_query($sql);
$gameid=array(); $g=0;
while($row=mysql_fetch_array($result))
{
   $gameid[$g]=$row[id]; $g++;
   $type=$row[type];
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
   $sql="UPDATE sogcontracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4' WHERE district='1'";
   $result=mysql_query($sql);
}

//get contract text
if($type=="State") $district=0;
else $district=1;
$sql="SELECT * FROM sogcontracttext WHERE district='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$text1=$row[text1]; $text2=$row[text2];
$text3=$row[text3]; $text4=$row[text4];

if($submit)
{
   if($level!=1)
   {
      for($i=0;$i<count($gameid);$i++)
      {
         $sql="UPDATE $contracts SET accept='$accept' WHERE offid='$offid' AND disttimesid='$gameid[$i]'";
         $result=mysql_query($sql);
      }
   }
   else
   {
      for($i=0;$i<count($gameid);$i++)
      {
	 $sql="UPDATE $contracts SET confirm='$confirm' WHERE offid='$offid' AND disttimesid='$gameid[$i]'";
	 $result=mysql_query($sql);
         //echo "$sql<br>";
      }
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<br>";
//echo "<a class=small href=\"javascript:window.close()\">Close</a>";
echo "<form method=post action=\"sogcontract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\"><br><br>";
if($edit==1)
   echo "<a class=small href=\"sogcontract.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1 && $level==1)
   echo "<a class=small href=\"sogcontract.php?session=$session&edit=1\">Edit this Contract</a>";
echo "</td></tr>";
$sql="SELECT t2.accept,t2.confirm,t2.post FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t2.offid='$offid' AND t2.disttimesid='$gameid[0]'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$confirm=$row[confirm]; $accept=$row[accept];
echo "<tr align=center><td><table width=80%>";
if($row[accept]=='y' && !$submit)
{
   echo "<tr align=left><td>";
   if($level!=1) echo "You have ";
   else echo "$offname has ";
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
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract.  "; 
         if($type!='State')
	    echo "Once the NSAA confirms this contract, the District Director will contact you with specific information.  Please do not announce your selection as an official!";
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
   if($edit==1) echo " <font style=\"color:red\">[Today's Date]</font>";
   echo "</td></tr>";
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>".GetOffName($offid);
   if($edit==1) echo " <font style=\"color:red\">[Official's Name & Address]</font>";
   echo "<br>$row[address]<br>$row[city], $row[state] $row[zip]";    
   echo "</td></tr>";
   $sql="SELECT * FROM $districts WHERE id='$distid'";
   $result=mysql_query($sql);
   //echo $sql;
   $row=mysql_fetch_array($result);

   if(trim($row[director])=="")
   {
      $hostid=$row[hostid];
      $sql2="SELECT name FROM $db_name.logins WHERE id='$hostid' AND level='2'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $director=$row2[0];
   }
   else
      $director="$row[director]";
   $director.=" ($row[hostschool])";
   if($sample==1) $director="John Doe (Test's School)";
   if($edit==1)
      echo "<tr align=left><td><textarea rows=2 cols=90 name=\"text1\">$text1</textarea></td></tr>";
   else
      echo "<tr align=left><td>$text1</td></tr>";
   echo "<tr align=left><td>";
   if($sample==1) { $row[type]="District"; $row['class']="A"; $row[district]="1"; }
   echo "<b>$row[type] $row[class]-$row[district]</b>";
   if($edit==1)
      echo " <font style=\"color:red\">[District this contract is for]</font>";
   echo "</td></tr>";
   echo "<tr align=left><td><b>Dates:</b>";
   if($edit==1)
      echo " <font style=\"color:red\">[Timeslots this official has been assigned to in this district]</font>";
   echo "<table>";
   $sql2="SELECT DISTINCT day FROM $disttimes WHERE (";
   for($i=0;$i<count($gameid);$i++)
   {
      $sql2.="id='$gameid[$i]' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") ORDER BY day";
   $result2=mysql_query($sql2);
   //echo $sql2;
   while($row2=mysql_fetch_array($result2))
   {
      $sql3="SELECT id,time FROM $disttimes WHERE day='$row2[day]' AND (";
      for($i=0;$i<count($gameid);$i++)
      {
         $sql3.="id='$gameid[$i]' OR ";
      }
      $sql3=substr($sql3,0,strlen($sql3)-4);
      $sql3.=") ORDER BY time";
      $result3=mysql_query($sql3);
      $curday=''; $string="";
      while($row3=mysql_fetch_array($result3))
      {
         if($curday!=$row2[day]) 
         {
            if($curday!='') 
            {
	       $string=substr($string,0,strlen($string)-1);
	       $string.="</td></tr>";
            } 
 	    $curday=$row2[day];
            $date=split("-",$curday); $day=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
            $string.="<tr align=left><td>$day:</td><td>";
         }
         $string.="$row3[time]";
	 $sql4="SELECT position FROM $contracts WHERE offid='$offid' AND disttimesid='$row3[id]'";
	 $result4=mysql_query($sql4);
 	 $row4=mysql_fetch_array($result4);
         $string.=" ($row4[position])";
	 $string.="/";
      }
      $string=substr($string,0,strlen($string)-1);
      $string.="</td></tr>";
      echo $string;
   }
   if($sample=='1')
   {
      $string="<tr align=left><td>May 5:</td><td>5:00 PM CST (You are the Crew Chief)/7:30 PM CST</td></tr><tr align=left><td>May 6:</td><td>11:00 AM CST</td></tr>";
      echo $string;
   }
   echo "</table></td></tr>";
   if($sample==1) $row[site]="Haymarket Park";
   echo "<tr align=left><td><b>Site:</b> $row[site]";
   if($edit==1)
      echo " <font style=\"color:red\">[You may edit this district's information in the Host Contracts section.]</font>";
   echo "</td></tr>";
   echo "<tr align=left><td><b>District Director:</b> $director</td></tr>";
   if($sample==1) $row[schools]="Adams Central, Omaha North, Lincoln Pius X, etc.";
   echo "<tr align=left><td><b>Teams Assigned:</b> $row[schools]";
   echo "</td></tr>";

   //get partners
   echo "<tr align=left><td><b>Crew:</b>";
   if($edit==1)
      echo " <font style=\"color:red\">[You may edit each official's assignments in the Officials Contracts section.]</font>";
   echo "<br><table></td></tr>";
   for($i=0;$i<count($gameid);$i++)
   {
      $sql="SELECT day,time FROM $disttimes WHERE id='$gameid[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $date=split("-",$row[day]);
      $curday=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<tr align=left><td>$curday, $row[time]:</td>";

      $sql="SELECT offid,position FROM $contracts WHERE disttimesid='$gameid[$i]' ORDER BY position";
      $result=mysql_query($sql);
//echo $sql;
      $partners="";
      while($row=mysql_fetch_array($result))
      {
         $partners.=GetOffName($row[offid]);
	 $partners.=" ($row[position])";	
	 $partners.=", ";
      }
      $partners=substr($partners,0,strlen($partners)-2);
      if($sample==1) $partners="Ken Smith (Crew Chief), Larry Johnson, Eric Jones";
      echo "<td>$partners</td></tr>";
   }
   if($sample=='1')
   {
      $partners1="Ken Smith, Larry Johnson, Eric Jones";
      $partners="Ken Smith (Crew Chief), Larry Johnson, Eric Jones";
      echo "<tr align=left><td>May 5, 5:00 PM CST:</td><td>$partners1</td></tr>";
      echo "<tr align=left><td>May 5, 7:30 PM CST:</td><td>$partners</td></tr>";
      echo "<tr align=left><td>May 6, 11:00 AM CST:</td><td>$partners</td></tr>";
   }
   echo "</table></td></tr>";

   if($edit==1)
   {
      echo "<tr align=left><td><font style=\"color:red;font-size:9pt;\"><b>PLEASE NOTE:</b><br>Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>.  Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.  Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br></font>";
      $text2=ereg_replace("<br>","\r\n",$text2);
      echo "<textarea name=\"text2\" rows=15 cols=90>$text2</textarea></td></tr>";
   }
   else
      echo "<tr align=left><td>$text2</td></tr>";
   if($edit==1)
   {
      echo "<tr align=left><td><textarea name=\"text3\" rows=3 cols=90>$text3</textarea></td></tr>";
   }
   else
      echo "<tr align=left><td><i>$text3</i></td></tr>";

echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && ($level!=1 || $sample==1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
   {
      echo "<input type=text name=\"text4\" value=\"$text4\" size=90>";
   }
   else
      echo $text4; //"I, as an independent contractor, accept the above agreement for the $thisyear $type Girls Soccer Tournament.";
   echo "</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   if($sample!=1 && $edit!=1)
      echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
   else if($edit==1)
      echo "<tr align=center><td><br><br><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear $type Girls Soccer Tournament";
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
