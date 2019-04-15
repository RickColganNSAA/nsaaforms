<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$dbname || $dbname=="") $dbname="$db_name2";

if(!$obsid) $obsid=GetObsID($session);
if(!ereg("20052006",$dbname))
   $obsname=GetObsName($obsid);
else
{
   $sql="SELECT name FROM $dbname.logins WHERE id='$obsid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $obsname=$row[0];
}
if($obsid=="1") $obsname="NSAA";

if(!$gameid) $gameid=$game;
if($newgameid)
{
   $sql="UPDATE $dbname.fbobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}

if($submit && $submit!="Go")	//put eval in db and show user what he/she entered
{
   $home=addslashes($home);
   $visitor=addslashes($visitor);
   $site=addslashes($site);
   $weather=addslashes($weather);
   $field=addslashes($field);
   $quality=addslashes($quality);
   $score=addslashes($score);
   $unusual=addslashes($unusual);
   for($i=1;$i<=5;$i++)
   {
      $temp="running".$i;
      $temp2="passing".$i;
      $$temp=addslashes($$temp);
      $$temp2=addslashes($$temp2);
   }
   $kickoffs=addslashes($kickoffs);
   $punts=addslashes($punts);
   $fgoals=addslashes($fgoals);
   $judgment=addslashes($judgment);
   $recommendations=addslashes($recommendations);
   $comments=addslashes($comments);
   $dateeval=time();

   $sql="SELECT * FROM $dbname.fbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO fbobserve (obsid,offid,gameid,home,visitor,site,level,weather,field,quality,score,unusual,overall1,overall2,overall3,pregame1,pregame2,pregame3,pregame4,pregame5,pregame6,rules1,rules2,rules3,rules4,rules5,running1,running2,running3,running4,running5,passing1,passing2,passing3,passing4,passing5,kickoffs,punts,fgoals,signals1,signals2,judgment,recommendations,comments,postseason,postlevel) VALUES ('$obsid','$offid','$gameid','$home','$visitor','$site','$level','$weather','$field','$quality','$score','$unusual','$overall1','$overall2','$overall3','$pregame1','$pregame2','$pregame3','$pregame4','$pregame5','$pregame6','$rules1','$rules2','$rules3','$rules4','$rules5','$running1','$running2','$running3','$running4','$running5','$passing1','$passing2','$passing3','$passing4','$passing5','$kickoffs','$punts','$fgoals','$signals1','$signals2','$judgment','$recommendations','$comments','$postseason','$postlevel')";
   }
   else
   {
      $sql2="UPDATE fbobserve SET home='$home',visitor='$visitor',site='$site',level='$level',weather='$weather',field='$field',quality='$quality',score='$score',unusual='$unusual',overall1='$overall1',overall2='$overall2',overall3='$overall3',pregame1='$pregame1',pregame2='$pregame2',pregame3='$pregame3',pregame4='$pregame4',pregame5='$pregame5',pregame6='$pregame6',rules1='$rules1',rules2='$rules2',rules3='$rules3',rules4='$rules4',rules5='$rules5',running1='$running1',running2='$running2',running3='$running3',running4='$running4',running5='$running5',passing1='$passing1',passing2='$passing2',passing3='$passing3',passing4='$passing4',passing5='$passing5',kickoffs='$kickoffs',punts='$punts',fgoals='$fgoals',signals1='$signals1',signals2='$signals2',judgment='$judgment',recommendations='$recommendations',comments='$comments',postseason='$postseason',postlevel='$postlevel' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   }
   $result2=mysql_query($sql2);
   //echo "$sql2<br>".mysql_error();

   //if Saved, don't put dateeval in; if Submitted, do AND e-mail official that they have a new one
   if($submit=="Submit Evaluation")
   {
      $sql2="UPDATE fbobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
      $result2=mysql_query($sql2);

      $sql2="SELECT first,last,email FROM $dbname.officials WHERE id='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row2[email]!="")	//e-mail provided
      {
	 $From="nsaa@nsaahome.org";
	 $FromName="NSAA";
	 $To=$row2[email];
	 $ToName="$row2[first] $row2[last]";
	 $Subject="An NSAA Official's Evaluation has been submitted for you";
	 $Text="A Nebraska School Activities Association Football Official's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
	 $Html="A Nebraska School Activities Association Football Official's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!";
	 $Attm=array();
	 SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
      }
   }
}

//get answers if already submitted and only allow user to view, NOT edit
$sql="SELECT * FROM $dbname.fbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
//echo $sql;
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$id=$row[id];
if(mysql_num_rows($result)>0 && $row[dateeval]!="") 
{
   $submitted=1; $saved=0;
}
else if(mysql_num_rows($result)>0)
{
   $saved=1; $submitted=0;
}
else 
{
   $submitted=0; $saved=0;
}
$home=$row[home]; $visitor=$row[visitor];
$level=$row[level];
if($level!="" && $level!="frosh" && $level!="jv" && $level!="var")	//level=other
{
   $level='other';
   $levelspec=$row[level];
}
$weather=$row[weather]; $field=$row[field]; $quality=$row[quality]; $score=$row[score];
$unusual=$row[unusual]; $overall1=$row[overall1]; $overall2=$row[overall2]; $overall3=$row[overall3];
$pregame1=$row[pregame1]; $pregame2=$row[pregame2]; $pregame3=$row[pregame3];
$pregame4=$row[pregame4]; $pregame5=$row[pregame5]; $pregame6=$row[pregame6];
$rules1=$row[rules1]; $rules2=$row[rules2]; $rules3=$row[rules3]; $rules4=$row[rules4];
$rules5=$row[rules5]; 
$running1=$row[running1]; $running2=$row[running2]; $running3=$row[running3];
$running4=$row[running4]; $running5=$row[running5];
$passing1=$row[passing1]; $passing2=$row[passing2]; $passing3=$row[passing3];
$passing4=$row[passing4]; $passing5=$row[passing5];
$kickoffs=$row[kickoffs];
$punts=$row[punts];
$fgoals=$row[fgoals];
$signals1=$row[signals1];
$signals2=$row[signals2];
$judgment=$row[judgment];
$recommendations=$row[recommendations];
$postseason=$row[postseason];
$postlevel=$row[postlevel];
$site=$row[site];
$dateeval=date("F d, Y",$row[dateeval]);
$comments=$row[comments];
//get schools listed on this schedule entry
$sql="SELECT schools FROM $dbname.fbsched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schools=$row[0];

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "<br>";
if($submitted==1 && $submit=="Submit Evaluation")
{
   echo "<br><br><font style=\"color:blue\"><b>Thank you for submitting your evaluation!  Your evaluation is shown below.</b></font><br><br>";
}

echo "<form method=post action=\"fbobserve.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=offid value=$offid>";
echo "<input type=hidden name=gameid value=$gameid>";
echo "<input type=hidden name=obsid value=$obsid>";

if($submit=="Save & Keep Editing" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been saved.  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br>You must click \"Submit Evaluation\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</b></font><br><br>";
else if($submit=="Submit Evaluation" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br><br>";

echo "<br><table><caption><b>NSAA Football Officials Evaluation Form:</b><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " $dateeval";
echo ")<hr>";
if(GetLevel($session)==1 && $gameid && $gameid!='new')
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=fb&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $submit=="Go")
{
   echo "<tr align=center><td><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.fbsched WHERE offid='$offid' ORDER BY offdate";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($gameid==$row[id]) echo " selected";
      $date=split("-",$row[offdate]);
      $offdate="$date[1]/$date[2]";
      if($row[gametime]!="TBA")
      {
         $time=split("-",$row[gametime]);
         $gametime="$time[0]:$time[1]$time[2]";
      }
      else
      {
	 $gametime="Time: TBA";
      }
      echo ">$offdate $gametime @$row[location] ($row[schools])</option>";
   }
   echo "</select>&nbsp;<input type=submit name=submit value=\"Go\"></td></tr>";
}

//array of answer options
$ans=array("Satisfactory","Unsatisfactory");

if($gameid && $gameid!="new" || $print==1)
{

echo "<tr align=center><td><table>";
if($print!=1)
{
   //show main information about game:
   echo "<tr align=left><td><b>Name of Chief Crew Member:</b></td>";
   $sql="SELECT first,last,city FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td>$row[first] $row[last]</td></tr>";
   echo "<tr align=left><td><b>Official's Hometown:</b></td>";
   echo "<td>$row[city]</td></tr>";
   //show crew members and designations
   $sql="SELECT referee,umpire,linesman,linejudge,backjudge FROM $dbname.fbapply WHERE offid='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $referee=GetOffName($row[referee]);
   $umpire=GetOffName($row[umpire]);
   $linesman=GetOffName($row[linesman]);
   $linejudge=GetOffName($row[linejudge]);
   $backjudge=GetOffName($row[backjudge]);
   echo "<tr valign=top align=left><td><b>Crew Members:</b></td>";
   echo "<td>Referee:&nbsp;$referee<br>Umpire:&nbsp;$umpire<br>Linesman:&nbsp;$linesman<br>";
   echo "Line Judge:&nbsp;$linejudge<br>Back Judge:&nbsp;$backjudge</td></tr>";
   //get date of game
   $sql="SELECT offdate,location FROM $dbname.fbsched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
         echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
         echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
         $sql="SELECT * FROM $dbname.fbsched WHERE offid='$offid' ORDER BY offdate";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            $temp=split("-",$row[offdate]);
            echo "<option value='$row[id]'";
            if($gameid==$row[id]) echo " selected";
            echo ">$temp[1]/$temp[2]/$temp[0]: $row[schools]</option>";
         }
         echo "</select></td></tr>";
      }
   else
   {
   echo "<tr align=left><td><b>Date of Observation:</b></td>";
   $temp=split("-",$row[0]);
   $offdate="$temp[1]/$temp[2]/$temp[0]";
   echo "<td>$offdate</td></tr>";
   }
}
else
{
   echo "<tr align=left><td><b>Chief Crew Member:</b></td>";
   echo "<td><input type=text name=chief size=30></td></tr>";
   echo "<tr align=left><td><b>Date of Observation:</b></td>";
   echo "<td><input type=text name=dateeval size=30></td></tr>";
}
echo "<tr align=left><td><b>Site of Game:</b></td>";
if($submitted==1)
   echo "<td>$site</td></tr>";
else	//by default, show location listed on official's schedule for this game
   echo "<td><input type=text class=tiny size=30 name=site value=\"$row[location]\"></td></tr>";
echo "<tr align=left><td><b>Schools:</b></td>";
echo "<td>$schools</td></tr>";
echo "<tr align=left><td><b>Home Team:</b></td>";
if($submitted==1)
   echo "<td>$home</td></tr>";
else
   echo "<td><input type=text class=tiny size=30 name=home value=\"$home\"></td></tr>";
echo "<tr align=left><td><b>Visiting Team:</b></td>";
if($submitted==1)
   echo "<td>$visitor</td></tr>";
else
   echo "<td><input type=text class=tiny size=30 name=visitor value=\"$visitor\"></td></tr>";
echo "<tr align=left><td><b>Level:</b></td>";
if($submitted==1)
{
   echo "<td>".strtoupper($level);
   if($level=='other')
      echo ": $levelspec";
   echo "</td>";
}
else
{
   echo "<td><input type=radio name=level value='frosh'";
   if($level=='frosh') echo " checked";
   echo ">Frosh&nbsp;";
   echo "<input type=radio name=level value='jv'";
   if($level=='jv') echo " checked";
   echo ">JV&nbsp;";
   echo "<input type=radio name=level value='var'";
   if($level=='var') echo " checked";
   echo ">Varsity&nbsp;";
   echo "<input type=radio name=level value='other'";
   if($level=='other') echo " checked";
   echo ">Other (specify)&nbsp;";
   echo "<input type=text name=levelspec size=20 class=tiny";
   if($level=='other') echo " value=\"$levelspec\"";
   echo "></td>";
}
echo "</tr>";
echo "</table></td></tr>";

//evaluation questions:
echo "<tr align=center><td><table width=100%>";

echo "<tr align=left><td colspan=3><b><br>Weather Conditions:</b><br>";
if($submitted==1)
   echo "$weather</td></tr>";
else
   echo "<textarea rows=5 cols=70 name=weather>$weather</textarea></td></tr>";

echo "<tr align=left><td colspan=3><b><br>Field Conditions:</b><br>";
if($submitted==1)
   echo "$field</td></tr>";
else
   echo "<textarea rows=5 cols=70 name=field>$field</textarea></td></tr>";

echo "<tr align=left><td colspan=3><b><br>Quality of Game:</b><br>";
if($submitted==1)
   echo "$quality</td></tr>";
else
   echo "<textarea rows=5 cols=70 name=quality>$quality</textarea></td></tr>";

echo "<tr align=left><td colspan=3><b><br>Score:</b>&nbsp;";
if($submitted==1)
   echo "$score</td></tr>";
else
   echo "<input type=text size=50 name=score value=\"$score\"></td></tr>";

echo "<tr align=left><td colspan=3><b><br>Unusual Circumstances:</b><br>";
if($submitted==1)
   echo "$unusual</td></tr>";
else
   echo "<textarea rows=5 cols=70 name=unusual>$unusual</textarea></td></tr>";

echo "<tr align=left><td colspan=3><b><br>AREAS TO BE EVALUATED:</b></td></tr>";

//Overall Appearance
$overallq=array("Uniforms","Physical Appearance","Professionalism");
echo "<tr align=left><td colspan=3><b><br>Overall Appearance of Crew:</b></td></tr>";
for($x=0;$x<count($overallq);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250>$i.&nbsp;$overallq[$x]:</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="overall".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"overall".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Pre-game Duties
$pregameq=array("Arrival on field (30 minutes minimum)","Inspection of field","Line crew/equipment","Clock operator","Meet coaches","Coin toss");
echo "<tr align=left><td colspan=3><b><br>Pre-Game Duties:</b></td></tr>";
for($x=0;$x<count($pregameq);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250>$i.&nbsp;$pregameq[$x]:</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="pregame".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"pregame".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Rules
$rulesq=array("Legal equipment (towels, jerseys, eye shields, gloves) checked","5-man free kick assignments","One-minute after score","Team boxes/sideline management","Only 3 coaches in restricted area between plays");
echo "<tr align=left><td colspan=3><b><br>Rules Enforcement:</b></td></tr>";
for($x=0;$x<count($rulesq);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250>$i.&nbsp;$rulesq[$x]:</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="rules".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"rules".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//COVERAGE OF PLAYS
echo "<tr align=left><td colspan=3><b><br>Coverage of Plays:</b></td></tr>";
//Running Plays
$runningq=array("Referee","Umpire","Line Judge","Linesman","Back Judge");
echo "<tr align=left><td colspan=3><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Running Plays:</b></td></tr>";
for($x=0;$x<count($runningq);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$i.&nbsp;$runningq[$x]:</td>";
   echo "<td colspan=2>";
   $temp="running".$i;
   if($submitted==1)
      echo $$temp;
   else
      echo "<input type=text name=$temp value=\"".$$temp."\" size=60>";
   echo "</td>";
   echo "</tr>";
}

//Passing Plays
$passingq=array("Referee","Umpire","Line Judge","Linesman","Back Judge");
echo "<tr align=left><td colspan=3><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Passing Plays:</b></td></tr>";
for($x=0;$x<count($passingq);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$i.&nbsp;$passingq[$x]:</td>";
   echo "<td colspan=2>";
   $temp="passing".$i;
   if($submitted==1)
      echo $$temp;
   else
      echo "<input type=text name=$temp value=\"".$$temp."\" size=60>";
   echo "</td>";
   echo "</tr>";
}

//Kick-Offs
echo "<tr align=left><td colspan=3><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kick-Offs:</b><br>";
if($submitted==1)
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$kickoffs</td></tr>";
else
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea rows=5 cols=70 name=kickoffs>$kickoffs</textarea></td></tr>";

//Punts
echo "<tr align=left><td colspan=3><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Punts:</b><br>";
if($submitted==1)
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$punts</td></tr>";
else
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea rows=5 cols=70 name=punts>$punts</textarea></td></tr>";

//Field Goals/PATs
echo "<tr align=left><td colspan=3><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Field Goals/PAT's:</b><br>";
if($submitted==1)
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fgoals</td></tr>";
else
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea rows=5 cols=70 name=fgoals>$fgoals</textarea></td></tr>";

//Signals
$signalq=array("Stopping and starting the clock","Penalty enforcement");
echo "<tr align=left><td colspan=3><b><br>Signals:</b></td></tr>";
for($x=0;$x<count($signalq);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250>$i.&nbsp;$signalq[$x]:</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="signal".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"signal".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Judgment
echo "<tr align=left><td colspan=3><b><br>Game Management/Communication:</b><br>";
if($submitted==1)
   echo "$judgment</td></tr>";
else
   echo "<textarea rows=5 cols=70 name=judgment>$judgment</textarea></td></tr>";

//Recommendations
echo "<tr align=left><td colspan=3><b><br>Recommendations for Improvement:</b><br>";
if($submitted==1)
   echo "$recommendations</td></tr>";
else
   echo "<textarea rows=5 cols=70 name=recommendations>$recommendations</textarea></td></tr>";

//Comments for NSAA Only
if(GetLevel($session)!=2)	//if not an official, show comments for NSAA
{
   echo "<tr align=left><td colspan=3><b>Comments for NSAA only:</b><br>";
   if($submitted==1)
      echo "$comments</td></tr>";
   else
      echo "<textarea rows=5 cols=70 name=comments>$comments</textarea></td></tr>";

   //Post Season
   echo "<tr align=left><td><b>Recommendations for Post Season Assignments:</td>";
   if($submitted==1)
   {
      echo "<td>".strtoupper($postseason)."</td></tr>";
   }
   else
   {
      echo "<td><input type=radio name=postseason value='yes'";
      if($postseason=='yes') echo " checked";
      echo ">Yes&nbsp;</td>";
      echo "<td><input type=radio name=postseason value='no'";
      if($postseason=='no') echo " checked";
      echo ">No</td></tr>";
   }
   echo "<tr align=left><td><b>If yes, at what level?</td>";
   echo "<td colspan=2>";
   if($submitted==1)
      echo $postlevel;
   else
   {
      $classes=array("A","B","C-1","C-2","D-1","D-2","All Classes");
      for($i=0;$i<count($classes);$i++)
      {
         echo "<input type=radio name=postlevel value=\"$classes[$i]\"";
         if($postlevel==$classes[$i]) echo " checked";
         echo ">$classes[$i]&nbsp;";
      }
   }
   echo "</td></tr>";
}

if($submitted!=1 && $print!=1)
{
   echo "<tr align=center><td colspan=7><br><font style=\"color:blue\"><b>NOTE:</b> You may click \"Save & Keep Editing\" if you want to save your work and continue later.  Your evaluation will NOT be sent to the NSAA until you click \"Submit Evaluation\" below.</font><br>";
   echo "<input type=submit name=submit value=\"Save & Keep Editing\"></td></tr>";
   echo "<tr align=center><td colspan=7><br><font style=\"color:blue\"><b>NOTE:</b> Once you click \"Submit Evaluation\", your submission of this evaluation is final.  YOU MUST CLICK \"Submit Evaluation\" FOR THE EVALUATION TO BE SENT!!!<br>You will be able to view your submitted evaluations,but you will NOT be able to edit them.<br><input type=submit name=submit value=\"Submit Evaluation\"></td></tr>";
}

echo "</table>";

echo "</td></tr></table><br><br>";
if($print!=1) echo "<a class=small href=\"javascript:window.close();\">Close Window</a>";
}//end if gameid given

echo $end_html;

?>
