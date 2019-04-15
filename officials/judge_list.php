<?php
require 'variables.php';
require 'functions.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//validate user
if(!ValidUser($session))
{
   header("Location:jindex.php?page=judges&session=$session");
   exit();
}

?>

<html>
<head>
<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase() != 'TD' && element != null)
      element = document.all ? element.parentElement : element.parentNode;
   if(element)
   {
      element.bgColor="FFFF33";
   }
}
</script>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<body>
<table cellspacing="0" cellpadding="3" frame="all" rules="all" style="width:100%;border:#000000 1px solid;">
<form method="post" name="judge_form" action="update_judge.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=lastname value="<?php echo $lastname; ?>">

<?php
//get query: should be sent from judges.php but if not, use generic SELECT ALL query:
if(!$query) $query="SELECT * FROM judges";
//sql will be actual query used and sent to mysql:
$sql=ereg_replace("PLUS","+",$query);

if($lastname)	//if user typed value in Quick Search box in judge_footer, override other query
{
   $lastname2=addslashes($lastname);
   $sql="SELECT * FROM judges WHERE last LIKE '$lastname2%'";
   $query=$sql;	//keep query for output of results count later
   $lastch='y';
}
else
   $lastch='n';

$result=mysql_query($sql);

//***DISPLAY JUDGES***//
$tot_ct=mysql_num_rows($result);	//total count from whole query

if($tot_ct==1 && !ereg("datesent",$sql) && $findone=='1')
{
   //go straight to that judges edit_judge page
   $row=mysql_fetch_array($result);
?>
<script language="javascript">
top.location.replace('edit_judge.php?session=<?php echo $session; ?>&sport1=<?php echo $sport1; ?>&sport2=<?php echo $sport2; ?>&bool=<?php echo $bool; ?>&last=<?php echo $last; ?>&id=<?php echo $row[id]; ?>');
</script>
<?php
   exit();
}
if($all!='all') echo "<tr align=left><td colspan=27>Your search returned <b>$tot_ct</b> results,";

if(!ereg("AS t1",$sql))	//if not one of set queries
{
   if(!$last && $tot_ct>=100) $last='a';
   else if(!$last) $last="All";
   if($last!="All" && ereg("WHERE",$sql) && $lastch=='n') $sql.=" AND last LIKE '$last%'";
   else if($last!="All" && $lastch=='n') $sql.=" WHERE last LIKE '$last%'";
   $sql.=" ORDER BY last,first";
}
else	//one of the 3 "set" queries, see variable "setquery" in judge_query.php
{
   if(!$last && $tot_ct>100) $last='a';
   else if(!$last) $last="All";
   if($last!="All" AND $lastch=='n') $sql.=" AND t1.last LIKE '$last%'";
   $sql.=" ORDER BY t1.last,t1.first";
}
$result=mysql_query($sql);
// (SO: $query is the original query sent to this script (and sent on to other scripts) AND...
// $sql is the query for this letter's page (so all the  'A' results)
$ct=mysql_num_rows($result);
if($all!='all') echo " <b>$ct</b> of which are showing:";	//count for this letter's page
//echo "<br>$query<br>".mysql_error();

//show links to letters of alphabet for navigation:
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$alphabet=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
for($i=0;$i<count($alphabet);$i++)
{
   $upper=strtoupper($alphabet[$i]);
   if($last==$alphabet[$i])
   {
      echo "<b><font size=2>$upper&nbsp;</font></b>";
   }
   else
   {
      echo "<a href=\"judge_list.php?all=$all&quickquery=$quickquery&setquery=$setquery&last=$alphabet[$i]&sport1=$sport1&sport2=$sport2&bool=$bool&query=$query&session=$session\">$upper</a>&nbsp;";
   }
}
if(!$last || $last=="All")   echo "<b><font size=2>All</font></b>";
else
   echo "<a href=\"judge_list.php?all=$all&quickquery=$quickquery&setquery=$setquery&last=All&sport1=$sport1&bool=$bool&sport2=$sport2&query=$query&session=$session\">All</a>";
echo "</td></tr>";
while($row=mysql_fetch_array($result))
{
   $proceed=1;
   if($all=="all")	//GET judges who've met ALL 3 CRITERIA in SPEECH AND PLAY
   {
	//WE HAVE ALREADY GOTTEN THE ONES WHO'VE MET ALL 3 in SPEECH, NOW CHECK PLAY FOR THIS JUDGE
        $sql2="SELECT * FROM pptest ORDER BY place";
        $result2=mysql_query($sql2);
        $total=mysql_num_rows($result2);
        if($total>0) $needed=.8*$total;
        else $needed=40;
        $sql2="SELECT t1.* FROM judges AS t1,pptest_results AS t2 WHERE t1.id=t2.offid AND t1.ppmeeting='x' AND t1.play='x' AND t2.correct>=$needed AND t1.id='$row[id]'";
	$result2=mysql_query($sql2);
	if(mysql_num_rows($result2)==0)
	   $proceed=0;
   }
   //SHOW JUDGE (ID HIDDEN)
   if($proceed==1)
   {
   if($ix%15==0)
   {
?>
<tr height=27 align=center>
<th class=small>Name<br>(last, first MI)</th>
<th class=small>Soc Sec #</th>
<th class=small>Passcode</th>
<th class=small>E-mail</th>
<th class=small>Play</th>
<th class=small>PP<br>Test</th>
<th class=small>PP<br>Meeting</th>
<th class=small>PP Mailing</th>
<th class=small>Speech</th>
<th class=small>SP<br>Test</th>
<th class=small>SP<br>Meeting</th>
<th class=small>SP Mailing</th>
<th class=small>New<br>Play<br>Judge</th>
<th class=small>New<br>Speech<br>Judge</th>
<th class=small>Payment (Date Entered)</th>
</tr>
<?php
   }
   echo "<tr title=\"$row[2], $row[3] $row[4]\" align=center";
   if($ix%2==0)
   {
      $color="#F0F0F0";
      echo " bgcolor=#F0F0F0";
   }
   else $color="#FFFFFF";
   echo "><td align=left><input type=hidden name=\"offid[$ix]\" value=\"$row[0]\">";
   echo "<a class=small style=\"color:black\" target=\"_top\" title=\"$row[1]\" href=\"edit_judge.php?session=$session&id=$row[0]&sport1=$sport1&bool=$bool&sport2=$sport2&query=$query&last=$last\">";
   echo "$row[2], $row[3] $row[4]</a>";
   echo "</td>";
   //echo "<td>$row[1]</td>";
   /*-------Soc Sec # column replaceed by *********---------*/
   
   if( $row[1]!= '' ) {
	   $Soc_Sec = '*********';
   } else{
	   $Soc_Sec = '';
   }
   
   echo "<td>$Soc_Sec</td>";
   
   //get passcode
   $sql2="SELECT passcode FROM logins_j WHERE offid='$row[0]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $pass=$row2[0];
   echo "<td><input type=text size=10 onchange=\"Color(this)\" class=tiny name=\"passcode[$ix]\" value='$pass'></td>";
   echo "<td><a href=\"mailto:$row[email]\" class=\"small\">$row[email]</a></td>";
   //get this judge's test score info
   $sqlTEST="SELECT * FROM pptest_results WHERE offid='$row[0]'";
   $resultTEST=mysql_query($sqlTEST);
   if(mysql_num_rows($resultTEST)==0)
      $ppscore="&nbsp;";
   else
   {
      $rowTEST=mysql_fetch_array($resultTEST);
            $sql2="SELECT * FROM pptest";
            $result2=mysql_query($sql2);
            $total=mysql_num_rows($result2);
      $ppscore=number_format(($rowTEST[correct]/$total)*100,0,'.','')."%";
   }
   $sqlTEST="SELECT * FROM sptest_results WHERE offid='$row[0]'";
   $resultTEST=mysql_query($sqlTEST);
   if(mysql_num_rows($resultTEST)==0)
      $spscore="&nbsp;";
   else
   {
      $rowTEST=mysql_fetch_array($resultTEST);
            $sql2="SELECT * FROM sptest";
            $result2=mysql_query($sql2);
            $total=mysql_num_rows($result2);
      $spscore=number_format(($rowTEST[correct]/$total)*100,0,'.','')."%";
   }
   echo "<td><input type=checkbox onclick=\"Color(this)\" name=\"play[$ix]\" value='x'";
   if($row[play]=='x') echo " checked";
   echo "></td>";
   echo "<td>$ppscore</td>";
   echo "<td align=center><input onclick=\"Color(this)\" type=checkbox name=\"ppmeeting[$ix]\" value='x'";
   if($row[ppmeeting]=='x') echo " checked";
   echo "></td>";
   $ppdatesent=split("-",$row[ppdatesent]);
   if($ppdatesent[0]=="0000")
      echo "<td>Not Sent Yet</td>";
   else
      echo "<td>$ppdatesent[1]/$ppdatesent[2]/$ppdatesent[0]</td>";
   echo "<td><input type=checkbox onclick=\"Color(this)\" name=\"speech[$ix]\" value='x'";
   if($row[speech]=='x') echo " checked";
   echo "></td>";
   echo "<td>$spscore</td>";
   echo "<td align=center><input onclick=\"Color(this)\" type=checkbox name=\"spmeeting[$ix]\" value='x'";
   if($row[spmeeting]=='x') echo " checked";
   echo "></td>";
   $spdatesent=split("-",$row[spdatesent]);
   if($spdatesent[0]=="0000")
      echo "<td>Not Sent Yet</td>";
   else
      echo "<td>$spdatesent[1]/$spdatesent[2]/$spdatesent[0]</td>";
   echo "<td align=center><input onclick=\"Color(this)\" type=checkbox name=\"firstyrplay[$ix]\" value='x'";
   if($row[firstyrplay]=='x') echo " checked";
   echo "></td>";
   echo "<td align=center><input onclick=\"Color(this)\" type=checkbox name=\"firstyrspeech[$ix]\" value='x'";
   if($row[firstyrspeech]=='x') echo " checked";
   echo "></td>";
   if($row[datereg]=="" || $row[datereg]=="0000-00-00") $datereg="";
   else 
   {
      $date=split("-",$row[datereg]);
      $datereg="(".date("m/d",mktime(0,0,0,$date[1],$date[2],$date[0])).")";
   }
   echo "<td>$row[payment] $datereg</td>";
   echo "</tr>";
   $ix++;
   }	//END IF PROCEED
}
?>
</table>
<br>
<input type=hidden name=count value=<?php echo $ix; ?>>
<input type=hidden name=last value="<?php echo $last; ?>">
<input type=hidden name=sport1 value=<?php echo $sport1; ?>>
<input type=hidden name=bool value=<?php echo $bool; ?>>
<input type=hidden name=sport2 value=<?php echo $sport2;?>>
<input type=hidden name=query value="<?php echo $query; ?>">
</form>
</body>
</html>
