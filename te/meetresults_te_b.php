<?php
/************************************
meetresults_te_b.php
Enter Meet Results for Boys TE Meet 
Created 7/18/08
Author: Ann Gaffigan
************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

if(!$curresultid) $curresultid=0;

$header=GetHeader($session);
$level=GetLevel($session);
//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
$sport='te_b';
$sportname="Boys Tennis";
$gender='M';
$meettable=$sport."meets";
$resultstable=$sport."meetresults";

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
   $sid=GetSID($session,$sport);
}
else if($school_ch)
{
   $sid=$school_ch;
   $school=GetMainSchoolName($sid,$sport);
}
else
{
   echo "ERROR: No School Selected";
   exit();
}
$school2=ereg_replace("\'","\'",$school);

if($delete)
{
   $sql="DELETE FROM $resultstable WHERE id='$delete'";
   $result=mysql_query($sql);
}
if($hiddensave)
{
   $oppid=$oppid0;
   $player1=$newplayer1; $player2=$newplayer2; $player3=$oppplayer00; $player4=$oppplayer01;
   $oosschool=addslashes($oosschool); $oosplayer1=addslashes($oosplayer1); $oosplayer2=addslashes($oosplayer2);
   if($oosplayer1=="[Player's Name]") $oosplayer1="";      
   if($oosplayer2=="[Player's Name]") $oosplayer2="";      
   if($newwinloss=="win") $winnerid=$sid;      
   else $winnerid=$oppid;      
   $score=$newscore;      
   if($curresultid && $curresultid!='')      
   {         
      $sql="UPDATE $resultstable SET varsityjv1='$varsityjv1',varsityjv2='$varsityjv2',division='$newdivision',oppid1='$sid',player1='$player1',player2='$player2',oppid2='$oppid',player3='$player3',player4='$player4',winnerid='$winnerid',score='$score',oosschool='$oosschool',oosplayer1='$oosplayer1',oosplayer2='$oosplayer2' WHERE id='$curresultid'";      
      $added=2;
   }      
   else      //INSERT NEW      
   {         
      $sql="INSERT INTO $resultstable (meetid,varsityjv1,varsityjv2,division,oppid1,player1,player2,oppid2,player3,player4,winnerid,score,oosschool,oosplayer1,oosplayer2) VALUES ('$meetid','$varsityjv1','$varsityjv2','$newdivision','$sid','$player1','$player2','$oppid','$player3','$player4','$winnerid','$score','$oosschool','$oosplayer1','$oosplayer2')";         
      $curresultid=0;
      $added=1;
   }
   $result=mysql_query($sql);
   //RESET FIELDS         
   //Utilities.getElement('newdivision').selectedIndex=0;         
   //Utilities.getElement('oppid0').selectedIndex=0;         
   //Utilities.getElement('oppplayer00').options.length=0;         
   //Utilities.getElement('oppplayer01').options.length=0;         
   //Utilities.getElement('newscore').value='';      
   header("Location:meetresults_te_b.php?session=$session&school_ch=$school_ch&added=$added&meetid=$meetid");
   exit();
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Team2.js"></script>
<script type="text/javascript" src="/javascript/TEMeetResults.js"></script>
<script language="javascript">
function ErrorCheckNew()
{
   var errors='';
   //check division, player(s), opponent team & player(s), W/L, Score:
   if(Utilities.getElement('newdivision').selectedIndex=='0')
      errors+="<tr align=left><td><font style=\"color:red\"><b>Division:</b></font>&nbsp;Please select the division for this match (#1 singles, #2 doubles, etc.).</td></tr>";
   if((!Utilities.getElement('varsity1').checked && !Utilities.getElement('jv1').checked) || (!Utilities.getElement('varsity2').checked && !Utilities.getElement('jv2').checked))
      errors+="<tr align=left><td><font style=\"color:red\"><b>Varsity/JV:</b></font>&nbsp;Please select \"Varsity\" or \"JV\" for EACH opponent for this match.</td></tr>";
   var divindex=Utilities.getElement('newdivision').selectedIndex;
   var division=Utilities.getElement('newdivision').options[divindex].value;
   if(division.match('singles'))
   {
      if(Utilities.getElement('newplayer1').selectedIndex=='0')
	 errors+="<tr align=left><td><font style=\"color:red\"><b>Your Player:</b></font>&nbsp;Please select your player.</td></tr>";
      if(Utilities.getElement('oppplayer00').selectedIndex=='0' && Utilities.getElement('oosplayer1').value=='')
	 errors+="<tr align=left><td><font style=\"color:red\"><b>Opponent:</b></font>&nbsp;Please select the opponent.</td></tr>";
   }
   else	if(division.match('doubles')) //doubles
   {
      if(Utilities.getElement('newplayer1').selectedIndex=='0' || Utilities.getElement('newplayer2').selectedIndex=='0')
	 errors+="<tr align=left><td><font style=\"color:red\"><b>Your Players:</b></font>&nbsp;Please select BOTH of your players.</td></tr.";
      if((Utilities.getElement('oppid0').selectedIndex!='1' && (Utilities.getElement('oppplayer00').selectedIndex=='0' || Utilities.getElement('oppplayer01').selectedIndex=='0')) || (Utilities.getElement('oppid0').selectedIndex=='1' && (Utilities.getElement('oosplayer1').value=='' || Utilities.getElement('oosplayer2').value=='')))
         errors+="<tr align=left><td><font style=\"color:red\"><b>Opponents:</b></font>&nbsp;Please select BOTH of the opponents.</td></tr>";
   }
   if(!Utilities.getElement('newwin').checked && !Utilities.getElement('newloss').checked)
      errors+="<tr align=left><td><font style=\"color:red\"><b>Win/Loss:</b></font>&nbsp;Please indicate whether this match was a win (W) or loss (L) for your team.</td></tr>";
   if(Utilities.getElement('newscore').value=='')
      errors+="<tr align=left><td><font style=\"color:red\"><b>Score:</b></font>&nbsp;Please enter the score for this match.</td></tr>";
   if(errors!="")
   {
      Utilities.getElement('errordiv').style.display="";
      Utilities.getElement('errordiv').innerHTML="<table width=100% bgcolor=#F0F0F0><tr align=center><td><div class=error>Please correct the following errors in your match:</div></td></tr>"+ errors +"<tr align=center><td><img src='/okbutton.png' onclick=\"Utilities.getElement('errordiv').style.display='none';\"></td></tr></table>";
   }
   else
   {
      Utilities.getElement('hiddensave').value="Save";
      document.forms.resultsform.submit();
   }
}
</script>
</head>
<?php
$school_ch2=ereg_replace("\'","\'",$school_ch);
?>
<body onload="Team2.initialize('<?php echo $session; ?>','te_b','oppid','oppplayer','1');TEMeetResults.initialize('showresults','<?php echo $sport; ?>','<?php echo $meetid; ?>','<?php echo $sid; ?>','<?php echo $session; ?>','<?php echo $school_ch2; ?>','<?php echo $curresultid; ?>');">
<?php
echo $header;

echo "<br><form method=post name=resultsform action=\"meetresults_te_b.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"school_ch\" value=\"$school_ch\">";
echo "<input type=hidden name=\"meetid\" id=\"meetid\" value=\"$meetid\">";
if(!$curresultid || $curresultid=='') $bgcolor="#ffffff";
else $bgcolor="#FAFAD2";
echo "<table cellspacing=0 cellpadding=1 rules=cols frame=box style=\"border:#808080 1px solid;background-color:$bgcolor;\">";
$sql="SELECT * FROM ".$sport."meets WHERE id='$meetid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<caption><b>You are entering results for \"$row[meetname]\"</b> (";
$start=split("-",$row[startdate]);
$end=split("-",$row[enddate]);
if($row[startdate]==$row[enddate])
   echo "$start[1]/$start[2] at&nbsp;";
else 
   echo "$start[1]/$start[2] - $end[1]/$end[2] at&nbsp;";
echo "$row[meetsite])<br>";
echo "<a href=\"editmeet_".$sport.".php?school_ch=$school_ch&session=$session&meetid=$row[id]\" class=small>Edit this Meet's Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
echo "<a class=small href=\"main_".$sport.".php?school_ch=$school_ch&session=$session\">".$sportname." Main Menu</a><br>";
echo "<div class=\"help\" style=\"text-align:left;width:700px;\"><b>INSTRUCTIONS:</b> Please add the results for each match in which one of YOUR PLAYERS participated, one match at a time.  When you click \"Add Match\", the table of results below will refresh to show the new addition.  To edit the results for any match, click on the match in the table of results, make the necessary changes, and click \"Save Match\".<br><br><b>PLEASE NOTE:</b> If another team has already entered matches against your players, you do NOT need to enter those specific matches again.</div><br>";
echo "</caption>";
$color="#E0E0E0";
if(!$curresultid || $curresultid=='')
{
   echo "<input type=hidden name=curresultid value=''>";
   echo "<tr align=left><td colspan=7><b>Add Match Result:</b></td></tr>";
}
else
{
   echo "<input type=hidden name=\"curresultid\" id=\"curresultid\" value=\"$curresultid\">";
   echo "<tr align=left><td colspan=7><b>Edit Match Result:</b>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"meetresults_",$sport.".php?school_ch=$school_ch&session=$session&meetid=$meetid&delete=$curresultid\">Delete This Match</a></td></tr>";
   $sql0="SELECT * FROM ".$sport."meetresults WHERE id='$curresultid'";
   $result0=mysql_query($sql0);
   $row0=mysql_fetch_array($result0);
}
echo "<tr><td colspan=7><div id=\"errordiv\" class=\"searchresults\" style=\"left:30%;width:400px;display:none;\"></div></td></tr>";
echo "<tr align=center bgcolor=$color><td><b>Division</b></td><td><b>Your Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Opponent Team, Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Win/Loss</b></td><td><b>Score</b></td></tr>";
echo "<tr align=center valign=top><td><select name=\"newdivision\" id=\"newdivision\" onchange=\"var ix=this.selectedIndex; if(this.options[ix].value.match('doubles')) { player2div.style.visibility='visible'; oppplayer2div.style.visibility='visible'; oosdiv3.style.visibility='visible'; } else { player2div.style.visibility='hidden'; oppplayer2div.style.visibility='hidden'; }\"><option value=''>~</option>";
for($i=1;$i<=12;$i++)
{
   $value="singles".$i;
   $text="#$i Singles";
   echo "<option value=\"$value\"";
   if($row0[division]==$value) echo " selected";
   echo ">$text</option>";
}
for($i=1;$i<=12;$i++)
{
   $value="doubles".$i;
   $text="#$i Doubles";
   echo "<option value=\"$value\"";
   if($row0[division]==$value) echo " selected";
   echo ">$text</option>";
}
echo "</select></td>";
echo "<td><select name=\"newplayer1\" id=\"newplayer1\"><option value=\"0\">~</option>";
$sql2="SELECT * FROM ".$sport."school WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
      $sql="SELECT DISTINCT t2.id,t2.last,t2.first,t2.middle,t2.semesters,t2.school FROM eligibility AS t2, headers AS t3 WHERE t2.school=t3.school AND t2.te='x' AND t2.gender='$gender' ";
      $sql.="AND (t3.id='$row2[mainsch]'";
      if($row2[othersch1]) $sql.=" OR t3.id='$row2[othersch1]'";
      if($row2[othersch2]) $sql.=" OR t3.id='$row2[othersch2]'";
      if($row2[othersch3]) $sql.=" OR t3.id='$row2[othersch3]'";
      $sql.=") ORDER BY t2.school,t2.last,t2.first";
$sqlshow=$sql;
$result=mysql_query($sql);
$students[id]=array(); $students[name]=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if(($sid==$row0[oppid1] && $row0[player1]==$row[id]) || ($sid==$row0[oppid2] && $row0[player3]==$row[id]))
      echo " selected";
   echo ">$row[last], $row[first] (".GetYear($row[semesters]).")</option>";
   $students[id][$ix]=$row[id]; $students[name][$ix]="$row[last], $row[first] (".GetYear($row[semesters]).")";
   $ix++;
}
echo "</select><br>";
if($curresultid && $curresultid!='' && ereg("doubles",$row0[division]))
{
   $vis="visible"; $disp="block";
}
else 
{
   $vis="hidden"; $disp="none";
}
echo "<div id=\"player2div\" style=\"visibility:$vis;\"><select name=\"newplayer2\" id=\"newplayer2\"><option value='0'>~</option>";
for($i=0;$i<count($students[id]);$i++)
{
   echo "<option value=\"".$students[id][$i]."\"";
   if(($sid==$row0[oppid1] && $row0[player2]==$students[id][$i]) || ($sid==$row0[oppid2] && $row0[player4]==$students[id][$i]))
       echo " selected";
   echo ">".$students[name][$i]."</option>";
}
echo "</select></div></td>";
echo "<td><input type=radio name=\"varsityjv1\" id=\"varsity1\" value=\"Varsity\"";
if($row0[varsityjv1]=='Varsity') echo " checked";
echo ">Varsity&nbsp;&nbsp;<input type=radio name=\"varsityjv1\" id=\"jv1\" value=\"JV\"";
if($row0[varsityjv1]=='JV') echo " checked";
echo ">JV&nbsp;</td>";
echo "<td><table cellspacing=0 cellpadding=1><tr align=left valign=top><td><select name=\"oppid0\" id=\"oppid0\" onMouseDown=\"Team2.currentPlace=0;Team2.currentDuplicateStuds=2;\" onMouseUp=\"if(this.selectedIndex==1) { oosdiv.style.visibility='visible'; oosdiv.style.display='block'; oosdiv2.style.visibility='visible'; oosdiv2.style.display='block'; nonoosdiv.style.visibility='hidden'; nonoosdiv.style.display='none'; } else { oosdiv.style.visibility='hidden'; oosdiv.style.display='none'; oosdiv2.style.visibility='hidden'; oosdiv2.style.display='none'; nonoosdiv.style.visibility='visible'; nonoosdiv.style.display='block'; }\"><option value='0'>Opponent's Team</option><option value='1000000000'";
if($row0[oppid2]==1000000000) echo " selected";
echo ">OUT-OF-STATE TEAM</option>";
$sql="SELECT * FROM ".$sport."school ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[sid]\"";
   if(($sid==$row0[oppid1] && $row0[oppid2]==$row[sid]) || ($sid==$row0[oppid2] && $row0[oppid1]==$row[sid])) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select><br>";
if($row0[oppid2]==1000000000) 
{
   $vis2="visible"; $vis3="hidden";
   $disp2="block"; $disp3="none";
}
else 
{
   $vis2="hidden"; $vis3="visible";
   $disp2="none"; $disp3="block";
}
if($row0[oosschool]=="")
   $row0[oosschool]="[Out-of-State School]";
echo "<div id=\"oosdiv\" style=\"visibility:$vis2;display:$disp2;\"><input type=text name=\"oosschool\" id=\"oosschool\" size=25 onFocus=\"if(this.value=='[Out-of-State School]') { this.value=''; }\" value=\"$row0[oosschool]\">";
echo "</td><td><div id=\"nonoosdiv\" style=\"visibility:$vis3;display:$disp3;\"><select name=\"oppplayer00\" id=\"oppplayer00\"><option value='0'>Opponent</option>";
if($curresultid && $curresultid!='')
{
   $sql="SELECT t1.id,t1.first,t1.last,t1.semesters FROM eligibility AS t1, headers AS t2, ".$sport."school AS t3 WHERE t1.school=t2.school AND (t2.id=t3.mainsch OR t2.id=t3.othersch1 OR t2.id=t3.othersch2 OR t2.id=t3.othersch3) AND ";
   if($sid==$row0[oppid1]) $sql.="t3.sid='$row0[oppid2]'";
   else $sql.="t3.sid='$row0[oppid1]'";
   $sql.=" AND t1.gender='$gender' AND te='x' ORDER BY t1.last,t1.first";
   $result=mysql_query($sql);
   $students2[id]=array(); $students2[name]=array(); $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if(($sid==$row0[oppid1] && $row0[player3]==$row[id]) || ($sid==$row0[oppid2] && $row0[player1]==$row[id])) 
         echo " selected";
      echo ">$row[last], $row[first] (".GetYear($row[semesters]).")</option>";
      $students2[id][$ix]=$row[id]; $students2[name][$ix]="$row[last], $row[first] (".GetYear($row[semesters]).")";
      $ix++;
   }
}
echo "</select><br><div id=\"oppplayernote\" style=\"display:none\"></div><br>";
echo "<div id=\"oppplayer2div\" style=\"visibility:$vis;\"><select name=\"oppplayer01\" id=\"oppplayer01\"><option value='0'>Opponent</option>";
if($curresultid && $curresultid!='')
{
   for($i=0;$i<count($students2[id]);$i++)
   {
      echo "<option value=\"".$students2[id][$i]."\"";
      if(($sid==$row0[oppid1] && $row0[player4]==$students2[id][$i]) || ($sid==$row0[oppid2] && $row0[player2]==$students2[id][$i]))
         echo " selected";
      echo ">".$students2[name][$i]."</option>";
   }
}
echo "</select></div></div>";
if($row0[oosplayer1]=="")
   $row0[oosplayer1]="[Player's Name]";
echo "<div id=\"oosdiv2\" style=\"visibility:$vis2;display:$disp2;\"><input type=text name=\"oosplayer1\" id=\"oosplayer1\" size=20 value=\"$row0[oosplayer1]\" onFocus=\"if(this.value=='[Player\'s Name]') { this.value=''; }\">";
if($row0[oosplayer2]=="")
   $row0[oosplayer2]="[Player's Name]";
echo "<div id=\"oosdiv3\" style=\"visibility:$vis;\"><br><input type=text name=\"oosplayer2\" id=\"oosplayer2\" size=20 value=\"$row0[oosplayer2]\" onFocus=\"if(this.value=='[Player\'s Name]') { this.value=''; }\"></div>";
echo "</div>";
echo "</td>";
echo "</tr></table></td>";
echo "<td><input type=radio name=\"varsityjv2\" id=\"varsity2\" value=\"Varsity\"";
if($row0[varsityjv2]=='Varsity') echo " checked";
echo ">Varsity&nbsp;&nbsp;<input type=radio name=\"varsityjv2\" id=\"jv2\" value=\"JV\"";
if($row0[varsityjv2]=='JV') echo " checked";
echo ">JV&nbsp;</td>";
echo "<td><input type=radio name=\"newwinloss\" id=\"newwin\" value=\"win\"";
if($row0[winnerid]==$sid) echo " checked";
echo ">W&nbsp;";
echo "<input type=radio name=\"newwinloss\" id=\"newloss\" value=\"loss\"";
if($row0[winnerid]!=$sid && $row0[winnerid]>0) echo " checked";
echo ">L</td>";
echo "<td><input type=text class=tiny size=12 name=\"newscore\" id=\"newscore\"";
if($row0[score]) echo " value=\"$row0[score]\"";
echo "></td>";
echo "</tr>";
echo "<tr align=right><td colspan=7><table cellspacing=0 cellpadding=2><tr align=right valign=top><td>";
if($added==1)
{
   echo "<div id=\"querystatus\" class=alert style=\"width:200px;\">Your match has been added below!</div>";
?>
<script language="javascript">
setTimeout('Utilities.getElement(\'querystatus\').style.visibility=\'hidden\';',3000);
</script>
<?php
}
else if($added==2)
{
   echo "<div id=\"querystatus\" class=alert style=\"width:200px;\">Your match has been updated below!</div>";
?>   
<script language="javascript">
setTimeout('Utilities.getElement(\'querystatus\').style.visibility=\'hidden\';',3000);
</script>
<?php
}
echo "</td>";
if(!$curresultid || $curresultid=='')
{
   echo "<td><input type=button name=\"addmatch\" value=\"Add Match\" onclick=\"ErrorCheckNew();\"";
   //if(PastDue("2009-10-12",0.4)) echo " disabled";
   echo "></td></tr></table></td></tr>";
}
else
{
   echo "<td><input type=button name=\"editmatch\" value=\"Save Match\" onclick=\"ErrorCheckNew();\"";
   //if(PastDue("2008-10-12",0.4)) echo " disabled";
   echo "><br>";
   echo "<br><a class=small href=\"meetresults_".$sport.".php?school_ch=$school_ch&session=$session&meetid=$meetid\">Add New Match</a>";
   echo "</td></tr></table></td></tr>";
}
echo "<input type=hidden name=\"queryfinished\" id=\"queryfinished\" value=\"0\" onchange=\"if(this.value==1) { TEMeetResults.getResults(); this.value=0; }\">";
echo "</table>";
echo "<br>";
echo "<div id=\"showresults\">";

//RESULTS
      $results=GetTennisMeetResults("te_b",$meetid,$sid);
      $string="<table frame=box rules=cols style='border:#808080 1px solid;' cellspacing=0 cellpadding=3><caption><b>Your Players' Match Results</b><br><div class='alert' style='font-size:9pt;'><b>PLEASE NOTE: </b><br><br>Matches highlighted in <font style='color:#00cc33;'><b>green</b></font> were entered OR last edited by your opponent.<br><br>***The <b>WIN/LOSS</b> column will flip to show the correct record for your player, but the <b>SCORE</b> remains the way it was originally entered.***</div><br><div class=help style='width:500px'>To <b>EDIT</b> a match, click on the match and it will show in the box above this table.  At that time you can either edit the match and click \"Save Match\" or click \"Delete this Match\" to delete the match entirely.</div></caption><tr align=center><td><b>Division</b></td><td><b>Your Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Opposing Team, Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Win/Loss</b></td><td><b>Score</b></td></tr>";
      //$string="<table frame=box rules=cols style='border:#808080 1px solid;' cellspacing=0 cellpadding=3><caption><b>Your Players' Match Results</b><br>(Matches highlighted in <font style='color:#00cc33;'><b>green</b></font> were entered OR last edited by your opponent.)<br><div class=help style='width:500px'>To <b>EDIT</b> a match, click on the match and it will show in the box above this table.  At that time you can either edit the match and click \"Save Match\" or click \"Delete this Match\" to delete the match entirely.</div></caption><tr align=center><td><b>Division</b></td><td><b>Your Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Opposing Team, Player(s)</b></td><td><b>Varsity/JV</b></td><td><b>Win/Loss</b></td><td><b>Score</b></td></tr>";
$results=split("<result>",$results);
for($i=0;$i<count($results);$i++)
{
   if(($i%2)==0) $color="#E0E0E0";
   else $color="#FFFFFF";
   $details=split("<detail>",$results[$i]);
   if($details[0]==$curresultid) $color="#FAFAD2";
   if($details[8]!=$sid)      //this school did NOT submit this score: shade score cell
      $color2="#00cc33";
   else $color2=$color;
   $string.="<input type=hidden name=\"resultid[$i]\" value=\"$details[0]\"><tr align=left style=\"background-color:$color2;cursor:hand;cursor:pointer;\" onMouseOver=\"this.style.backgroundColor='#FAFAD2';\" onMouseOut=\"this.style.backgroundColor='$color2';\" onClick=\"window.location.replace('meetresults_".$sport.".php?school_ch=$sid&session=$session&meetid=$meetid&curresultid=$details[0]');\"><td>$details[1]</td><td>$details[2]</td><td>$details[3]</td><td>$details[4]</td><td>$details[5]</td><td>$details[6]</td><td>$details[7]</td></tr>";
}
$string.="</table>";
if(!ereg("background-",$string))
   $string="[No results have been entered for this meet yet.  Please enter each match above.]";
echo $string;

echo "</div>";
echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
echo "</form>";
echo "<div id=\"loading\" style=\"display:none;\"></div>";
echo $end_html;
?>
