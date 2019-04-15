<?php
$sport='bbg';

require 'functions.php';
require 'variables.php';
require '../../calculate/functions.php';
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

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";

//get array of games this official is hosting in this district
if($disttimesid>0)
   $sql="SELECT t1.id,t2.type FROM $disttimes AS t1,$districts AS t2,$contracts AS t3 WHERE t1.distid=t2.id AND t3.disttimesid=t1.id AND t3.offid='$offid' AND t1.id='$disttimesid' ORDER BY t3.accept,t3.confirm,t1.day,t1.time";
else
   $sql="SELECT t1.id,t2.type FROM $disttimes AS t1, $districts AS t2, $contracts AS t3 WHERE t1.distid=t2.id AND t3.disttimesid=t1.id AND t3.offid='$offid' AND t2.id='$distid' ORDER BY t3.accept,t3.confirm,t1.day,t1.time";
$result=mysql_query($sql);
echo mysql_error();
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
   $sql="UPDATE ".$sport."contracttext SET text1='$text1',text2='$text2',text3='$text3',text4='$text4' WHERE district='1'";
   $result=mysql_query($sql);
}

//get contract text
if($type=="State") $district=0;
else $district=1;
$sql="SELECT * FROM ".$sport."contracttext WHERE district='$district'";
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
echo "<form method=post action=\"".$sport."contract.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=givenoffid value=$offid>";
echo "<input type=hidden name=distid value=$distid>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<table cellspacing=3 cellpadding=3 width=75%>";
echo "<tr align=center><td>";
echo "<img src=\"nsaacontract.png\"><br>";
if($edit==1)
   echo "<a class=small href=\"".$sport."contract.php?session=$session&sample=1\">Preview this Contract</a>";
else if($sample==1 && $level==1)
   echo "<a class=small href=\"".$sport."contract.php?session=$session&edit=1\">Edit this Contract</a>";
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
         echo "<tr align=center><td><table width=80%><tr align=left><td>Thank you for accepting this contract."; 
         if($type!='State')
	    echo "Once the NSAA confirms this contract, the District Director will contact you with specific information.  Please do not announce your selection as an official!";
	 echo "<br><br></td></tr></table></td></tr>";
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

if($type!='State')
{
   echo "<tr align=left><td>".date("F j, Y");
   if($edit==1)
      echo " <font style=\"color:red\">[Today's Date]</font>";
   echo "</td></tr>";
   if($sample==1) 
   {
      $offid="3427"; 
   }
   $sql="SELECT * FROM officials WHERE id='$offid'";
   $result=mysql_query($sql); 
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td>".GetOffName($offid);
   if($edit==1)
      echo " <font style=\"color:red\">[Official's Name & Address]</font>";
   echo "<br>$row[address]<br>$row[city], $row[state] $row[zip]";    
   echo "</td></tr>";
   
   if($disttimesid>0)	//CLASS A BASKETBALL
      $sql="SELECT t1.*,t2.class,t2.district,t2.gender,t2.type FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.id='$disttimesid'";
   else
      $sql="SELECT class,district,director,gender,dates,hostschool,site,schools,type FROM $districts WHERE id='$distid'";
   $result=mysql_query($sql);
   //echo $sql;
   $row=mysql_fetch_array($result);
   if($edit==1)
      echo "<tr align=left><td><textarea name=\"text1\" rows=2 cols=90>$text1</textarea></td></tr>";
   else
      echo "<tr align=left><td>$text1</td></tr>";
   echo "<tr align=left><td>";
   if($sample==1)
      echo "Girls District A-1";
   else
      echo "$row[gender] $row[type] $row[class]-$row[district]";
   if($edit==1)
      echo " <font style=\"color:red\">[District this contract is for]</font>";
   echo "<br><br>Dates:";
   if($edit==1) 
      echo " <font style=\"color:red\">[Timeslots this official has been assigned to in this district]</font>";
   echo "<table>";
   $sql2="SELECT * FROM $disttimes WHERE (";
   for($i=0;$i<count($gameid);$i++)
   {
      $sql2.="id='$gameid[$i]' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") ORDER BY gender,day,gamenum";
   $result2=mysql_query($sql2);
   $curgender="";
   while($row2=mysql_fetch_array($result2))
   {
      $date=split("-",$row2[day]); 
      $day=date("F d",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<tr align=left><td>$day at $row2[time]</td>";
      if($disttimesid>0)   //CLASS A BASKETBALL
      {
	 $sql3="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='$row2[gamenum]'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 echo "<td>&nbsp;&nbsp;&nbsp;".GetSchoolName($row3[sid],$sport)." vs ".GetSchoolName($row3[oppid],$sport)."</td>";
      }
      else echo "<td>&nbsp;</td>"; 
      if($row2[site]!='') echo "<td>&nbsp;&nbsp;at $row2[site]</td>";
      else if($row2[hostschool]!='') echo "<td>&nbsp;&nbsp;at $row2[hostschool]</td>";
      else echo "<td>&nbsp;</td>";
      if($row2[director]!='') echo "<td>(Director: $row2[director])</td>";
      else echo "<td>&nbsp;</td>";
      echo "</td></tr>";
   }
   if($sample=='1')
   {
      $string="<tr align=left><td>Feb 22:</td><td>5:00 PM CST (You are the Crew Chief)/7:30 PM CST</td></tr><tr align=left><td>Feb 23:</td><td>11:00 AM CST</td></tr>";
      echo $string;
   }
   echo "</table></td></tr>";
   if($sample==1)
   {
      echo "<tr align=left><td>Site: Bob Devaney Sports Center";
      if($edit==1)
	 echo " <font style=\"color:red\">[You may edit this district's information in the Host Contracts section]</font>";
      echo "</td></tr>";
   }
   else if($row['class']!='A')	//NON CLASS A BASKETBALL
   {
      if(trim($row[site])!='')
         echo "<tr align=left><td>Site: $row[site]</td></tr>";
      else
         echo "<tr align=left><td>Site: $row[hostschool]</td></tr>";
      if($sample==1)
         echO "<tr align=left><td>District Director: John Smith</td></tr>";
      else
         echo "<tr align=left><td>District Director: $row[director]</td></tr>";
      if($sample==1)
         echO "<tr align=left><td>Teams Assigned: Lincoln High, Lincoln East, Lincoln Southwest, Lincoln North Star, Lincoln Pius X</td></tr>";
      else
         echo "<tr align=left><td>Teams Assigned: $row[schools]";
      echo "</td></tr>";
   }

   //get partners
   echo "<tr align=left><td>Partner(s):";
   if($edit==1)
      echO " <font style=\"color:red\">[You may edit each official's assignments in the Officials Contracts section.]</font>";
   echo "<br><table></td></tr>";
   for($i=0;$i<count($gameid);$i++)
   {
      $sql="SELECT day,time FROM $disttimes WHERE id='$gameid[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $date=split("-",$row[day]);
      $curday=date("M d",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<tr align=left><td>$curday, $row[time]:</td>";

      $sql="SELECT offid FROM $contracts WHERE offid!='$offid' AND disttimesid='$gameid[$i]'";
      $result=mysql_query($sql);
      $partners="";
      while($row=mysql_fetch_array($result))
      {
         $partners.=GetOffName($row[offid]).", ";
      }
      $partners=substr($partners,0,strlen($partners)-2);
      echo "<td>$partners</td></tr>";
   }
   if($sample==1) 
      echo "<tr align=left><td>Feb 22, 5:00PM:</td><td>John Doe, Larry Jones</td></tr><tr align=left><td>Feb 22, 7:30PM:</td><td>John Doe, Larry Jones</td></tr><tr align=left><td>Feb 23, 11:00AM:</td><td>Jim Simpson, Larry Jones</td></tr>";
   echo "</table></td></tr>";

   if($edit==1)
   {
      echo "<tr align=left><td><font style=\"color:red;font-size:9pt;\"><b>PLEASE NOTE:</b><br>Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>.  Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.  Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br></font>";
      $text2=ereg_replace("\r\n","",$text2);
      $text2=ereg_replace("<br>","\r\n",$text2);
      echO "<tr align=left><td><textarea name=\"text2\" rows=10 cols=90>$text2</textarea></td></tr>";
      $text3=ereg_replace("\r\n","",$text3);
      $text3=ereg_replace("<br>","\r\n",$text3);
      echO "<tr align=left><td><textarea name=\"text3\" rows=5 cols=90>$text3</textarea></td></tr>";
   }
   else
   {
      echo "<tr align=left><td>$text2</td></tr>";
      echo "<tr align=left><td>$text3</td></tr>";
   }

}//end if not State

echo "<tr align=left><td><i>This contract shall be null and void if an official has been convicted of any crime involving moral turpitude or has committed any act, which subjects the NSAA or its member schools to public embarrassment or ridicule.</i></td></tr>";

if($accept!='y' && $accept!='n' && ($level!=1 || $sample==1))
{
   echo "<tr align=left><td><br><br><input type=radio name=accept value='y'>&nbsp;&nbsp;&nbsp;";
   if($edit==1)
      echo "<input type=text size=90 name=\"text4\" value=\"$text4\">";
   else
      echo $text4;
   echo "</td></tr>";
   echo "<tr align=left><td><br><br><input type=radio name=accept value='n'>&nbsp;&nbsp;&nbsp;I am unable to accept this contract.</td></tr>";
   if($edit==1)
      echO "<tr align=center><td><br><br><input type=submit name=savechanges value=\"Save Changes\"></td></tr>";
   else if($sample!=1)
      echo "<tr align=center><td><br><br><input type=submit name=submit value=\"Submit\"></td></tr>";
}
if($level==1 && $confirm!='y' && $confirm!='n' && $accept=='y')
{
   echo "<tr align=left><td><br><br><input type=radio name=confirm value='y'>&nbsp;&nbsp;&nbsp;The NSAA confirms the above agreement for the $thisyear $type Girls Basketball Tournament";
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
