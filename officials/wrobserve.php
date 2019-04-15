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
   $sql="UPDATE $dbname.wrobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}

if($submit && $submit!="Go")	//put eval in db and show user what he/she entered
{
   $site=addslashes($site); $event=addslashes($event);
   $comments0=addslashes($comments0);
   $comments1=addslashes($comments1);
   $comments2=addslashes($comments2);
   $comments3=addslashes($comments3);
   $comments4=addslashes($comments4);
   $overallcomments=addslashes($overallcomments);
   $nsaacomments=addslashes($nsaacomments);
   $strong1=""; $weak1="";
   for($i=0;$i<$count1;$i++)
   {
      if($str1[$i]=='x') $strong1.="x/";
      else $strong1.="/";
      if($wk1[$i]=='x') $weak1.="x/";
      else $weak1.="/";
   }
   $strong1=substr($strong1,0,strlen($strong1)-1);
   $weak1=substr($weak1,0,strlen($weak1)-1); 
   $strong2=""; $weak2="";
   for($i=0;$i<$count2;$i++)
   {
      if($str2[$i]=='x') $strong2.="x/";
      else $strong2.="/";
      if($wk2[$i]=='x') $weak2.="x/";
      else $weak2.="/";
   }
   $strong2=substr($strong2,0,strlen($strong2)-1);
   $weak2=substr($weak2,0,strlen($weak2)-1);
   $strong3=""; $weak3="";
   for($i=0;$i<$count3;$i++)
   {
      if($str3[$i]=='x') $strong3.="x/";
      else $strong3.="/";
      if($wk3[$i]=='x') $weak3.="x/";
      else $weak3.="/";
   }
   $strong3=substr($strong3,0,strlen($strong3)-1);
   $weak3=substr($weak3,0,strlen($weak3)-1);
   $strong4=""; $weak4="";
   for($i=0;$i<$count4;$i++)
   {
      if($str4[$i]=='x') $strong4.="x/";
      else $strong4.="/";
      if($wk4[$i]=='x') $weak4.="x/";
      else $weak4.="/";
   }
   $strong4=substr($strong4,0,strlen($strong4)-1);
   $weak4=substr($weak4,0,strlen($weak4)-1);
   $overall="";
   for($i=0;$i<$count5;$i++)
   {
      if($over[$i]=='x') $overall.="x/";
      else $overall.="/";
   }
   $overall=substr($overall,0,strlen($overall)-1);
   $dateeval=time();

   $sql="SELECT * FROM $dbname.wrobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO wrobserve (obsid,offid,gameid,site,event,type,communicate,comments0,rating1,strong1,weak1,comments1,rating2,strong2,weak2,comments2,rating3,strong3,weak3,comments3,rating4,strong4,weak4,comments4,overall,overallcomments,district,state,observe,nsaacomments) VALUES ('$obsid','$offid','$gameid','$site','$event','$type','$communicate','$comments0','$rating1','$strong1','$weak1','$comments1','$rating2','$strong2','$weak2','$comments2','$rating3','$strong3','$weak3','$comments3','$rating4','$strong4','$weak4','$comments4','$overall','$overallcomments','$district','$state','$observe','$nsaacomments')";
   }
   else
   {
      $sql2="UPDATE wrobserve SET site='$site',event='$event',type='$type',communicate='$communicate',comments0='$comments0',rating1='$rating1',strong1='$strong1',weak1='$weak1',comments1='$comments1',rating2='$rating2',strong2='$strong2',weak2='$weak2',comments2='$comments2',rating3='$rating3',strong3='$strong3',weak3='$weak3',comments3='$comments3',rating4='$rating4',strong4='$strong4',weak4='$weak4',comments4='$comments4',overall='$overall',overallcomments='$overallcomments',district='$district',state='$state',observe='$observe',nsaacomments='$nsaacomments' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   }
   $result2=mysql_query($sql2);
   //echo "$sql2<br>".mysql_error();

   //if "Saved", don't put dateeval in; if "submitted", put it in
   if($submit=="Submit Evaluation")
   {
      $sql2="UPDATE wrobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
      $result2=mysql_query($sql2);

      $sql2="SELECT first,last,email FROM $dbname.officials WHERE id='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row2[email]!="")      //e-mail provided
      {
         $From="nsaa@nsaahome.org";
 	 $FromName="NSAA";
	 $To=$row2[email];
	 $ToName="$row2[first] $row2[last]";
	 $Subject="An NSAA Official's Evaluation has been submitted for you";
	 $Text="A Nebraska School Activities Association Wrestling Official's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
         $Html="A Nebraska School Activities Association Wrestling Official's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!";
  	 $Attm=array();
	 SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
         //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$Attm);
      }
   }
}

//get answers if already submitted
$sql="SELECT * FROM $dbname.wrobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
//echo $sql;
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$id=$row[id];
if(mysql_num_rows($result)>0 && $row[dateeval]!='') 
{
   $saved=0; $submitted=1;
}
else if(mysql_num_rows($result)>0)
{
   $saved=1; $submitted=0;
}
else 
{
   $submitted=0; $saved=0;
}
$site=$row[site];
$event=$row[event]; $type=$row[type]; $communicate=$row[communicate];
$comments0=$row[comments0];
$rating1=$row[rating1]; $strong1=$row[strong1]; $weak1=$row[weak1]; $comments1=$row[comments1];
$rating2=$row[rating2]; $strong2=$row[strong2]; $weak2=$row[weak2]; $comments2=$row[comments2];
$rating3=$row[rating3]; $strong3=$row[strong3]; $weak3=$row[weak3]; $comments3=$row[comments3];
$rating4=$row[rating4]; $strong4=$row[strong4]; $weak4=$row[weak4]; $comments4=$row[comments4];
$overall=$row[overall]; $overallcomments=$row[overallcomments];
$district=$row[district]; $state=$row[state]; $observe=$row[observe];
$nsaacomments=$row[nsaacomments];
$dateeval=date("F d, Y",$row[dateeval]);
//get schools listed on this schedule entry
$sql="SELECT schools FROM $dbname.wrsched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schools=$row[0];

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<center>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "<br><br>";

if($print!=1) 
{
   echo "<form method=post action=\"wrobserve.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=offid value=$offid>";
   echo "<input type=hidden name=gameid value=$gameid>";
   echo "<input type=hidden name=obsid value=$obsid>";
   if($submit=="Save & Keep Editing")
      echo "<font style=\"color:blue\"><b>Your evaluation has been saved.  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br>You must click \"Submit Evaluation\" at the bottom of this screen in order for your evaluation to be sent to the NSAA and the official.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</b></font><br><br>";
   else if($submit=="Submit Evaluation")
      echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br><br>";
}
echo "<table width=95%><caption><b>NSAA Wrestling Officials Observation Report:</b><br>";
echo "<font style=\"font-size:9pt\"><i>The purpose of this observation report is to give constructive feedback, which is designed to improve the technique and overall performance of the official.</i></font><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " $dateeval";
echo ")<hr>";
if(GetLevel($session)==1 && $gameid && $gameid!='new')
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=wr&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $submit=="Go")
{
   echo "<tr align=center><td><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.wrsched WHERE offid='$offid' ORDER BY offdate";
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
$ans=array("(1) Superior","(2) Above Average","(3) Average","(4) Below Average","(5) Poor");

if($gameid && $gameid!="new" || $print==1)
{
echo "<tr align=center><td><table>";
//show main information about game:
echo "<tr align=left><td><b>Name of Official:</b></td>";
if($print!=1)
{
   echo "<td>".GetOffName($offid)."</td></tr>";
   //get date of game
   $sql="SELECT offdate FROM $dbname.wrsched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
         echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
         echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
         $sql="SELECT * FROM $dbname.wrsched WHERE offid='$offid' ORDER BY offdate";
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
   echo "<tr align=left><td><b>Date Observed:</b></td>";
   $temp=split("-",$row[0]);
   $offdate="$temp[1]/$temp[2]/$temp[0]";
   echo "<td>$offdate</td></tr>";
   }
}
else
{
   echo "<td><input type=text name=official size=30></td></tr>";
   echo "<tr align=left><td><b>Date Evaluated:</b></td>";
   echo "<td><input type=text name=dateeval size=30></td></tr>";
}
echo "<tr align=left><td><b>Site of Contest:</b></td>";
if($submitted==1)
   echo "<td>$site</td></tr>";
else
   echo "<td><input type=text class=tiny size=40 name=site value=\"$site\"></td></tr>";
echo "<tr align=left><td><b>Name and Type of Event:</b></td>";
if($submitted==1)
   echo "<td>$event</td></tr>";
else
   echo "<td><input type=text class=tiny size=50 name=event value=\"$event\"></td></tr>";
echo "<tr align=left><td><b>Tournament or Dual format:</b></td>";
if($submitted==1)
   echo "<td>$type</td></tr>";
else
{
   echo "<td><input type=radio name=type value=\"Tournament\"";
   if($type=="Tournament") echo " checked";
   echo ">Tournament&nbsp;&nbsp;<input type=radio name=type value=\"Dual\"";
   if($type=="Dual") echo " checked";
   echo ">Dual</td></tr>";
}
echo "<tr align=left><td><b>Did you speak with the official about your observation?</b></td>";
if($submitted==1)
   echo "<td>$communicate</td></tr>";
else
{
   echo "<td><input type=radio name=communicate value=\"Yes\"";
   if($communicate=="Yes") echo " checked";
   echo ">Yes&nbsp;&nbsp;<input type=radio name=communicate value=\"No\"";
   if($type=="No") echo " checked";
   echo ">No</td></tr>";
}
echo "<tr align=left valign=top><td><b>Comments on observation & reaction from the official:</b></td>";
if($submitted==1)
   echo "<td>$comments0</td></tr>";
else
   echo "<td><textarea name=\"comments0\" rows=3 cols=40>$comments0</textarea></td></tr>";
echo "</table></td></tr>";



//evaluation questions:
echo "<tr align=center><td><table width=600";
echo " cellspacing=1 cellpadding=2>";

//Composure and Appearance:
echo "<tr align=left><th align=left width=50%><u>Composure and Appearance:</u></th>";
/*echo "<th align=right width=50%>Rating: ";
if($submitted==1)
{
   echo "<u>$rating1</u>&nbsp;&nbsp;</th></tr>";
}
else if($print==1)
{
   echo "<input type=text name=rating1 value=\"$rating1\"></th></tr>";
}
else
{
   echo "<select name=\"rating1\"><option value=''>Please Select</option>";
   for($i=0;$i<count($ans);$i++)
   {
      echo "<option";
      if($rating1==$ans[$i]) echo " selected";
      echo ">$ans[$i]</option>";
   }
   echo "</select></th></tr>";
}*/
$strengths1=array("Appearance - First impression","Proper uniform and equipment","Use of mechanics and positions","Respect from coaches and wrestlers","Tempo of the match and consistency","Location on the mat","Reaction to stressful situations","Composure on the mat");
$weaks1=array("Appearance and/or Proper Uniform","Authoritative manner in making calls","Ability to relax and let the match come to the official","Know the rules and anticipate difficult situations","Always be positive and under control","Perceived interest in the mat","Ability to remain neutral with fans and coaches");
echo "<tr align=left valign=top><td><b>Strengths:</b><br><br>";
echo "<input type=hidden name=count1 value=\"".count($strengths1)."\">";
$curstrong=split("/",$strong1);
for($i=0;$i<count($strengths1);$i++)
{
   echo "<input type=checkbox name=\"str1[$i]\" value='x'";
   if($curstrong[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $strengths1[$i]<br>"; 
}
echo "</td><td><b>Areas for Improvement & Growth:</b><br><br>";
$curweak=split("/",$weak1);
for($i=0;$i<count($weaks1);$i++)
{
   echo "<input type=checkbox name=\"wk1[$i]\" value='x'";
   if($curweak[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echO "> $weaks1[$i]<br>";
}
echo "</td></tr>";
echo "<tr align=left><td colspan=2><br><b>Comments:</b><br>";
if($submitted==1)
   echo "<i>$comments1</i></td></tr>";
else
   echo "<textarea rows=4 cols=70 name=\"comments1\">$comments1</textarea></td></tr>";

//Command of the Match and Mat Area:
echo "<tr align=left><th align=left width=50%><br><u>Command of the Match and Mat Area:</u></th>";
/*echo "<th align=right width=50%><br>Rating: ";
if($submitted==1)
{
   echo "<u>$rating2</u></th></tr>";
}
else if($print==1)
{
   echo "<input type=text name=rating2 value=\"$rating2\"></th></tr>";
}
else
{
   echo "<select name=\"rating2\"><option value=''>Please Select</option>";
   for($i=0;$i<count($ans);$i++)
   {
      echo "<option";
      if($rating2==$ans[$i]) echo " selected";
      echo ">$ans[$i]</option>";
   }
   echo "</select></th></tr>";
}*/
$strengths2=array("Aware of the entire mat and mat area","Control of coaches during match","Position in referees/referee's position and sequence mechanics","Flip sequence and match restart mechanics","Mechanics of \"Where to Look\" during the match","Official's location on the mat during wrestling","Proper and respectful fall sequence","Always aware of the time remaining and score of the match");
$weaks2=array("Ability to keep coaches in the chair and the coaches' area clear of spectators","Protecting wrestlers in out-of-bounds areas","Flip sequence: is it short/concise and easy-to-read","Officials position to make the correct call","Keeping wrestlers in vision at all times (esp. during dead clock)","Proper display of all signals","Avoid use of any signals not in the rulebook");
echo "<tr align=left valign=top><td><b>Strengths:</b><br><br>";
echo "<input type=hidden name=count2 value=\"".count($strengths2)."\">";
$curstrong=split("/",$strong2);
for($i=0;$i<count($strengths2);$i++)
{
   echo "<input type=checkbox name=\"str2[$i]\" value='x'"; 
   if($curstrong[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $strengths2[$i]<br>";
}
echo "</td><td><b>Areas for Improvement & Growth:</b><br><br>";
$curweak=split("/",$weak2);
for($i=0;$i<count($weaks2);$i++)
{
   echo "<input type=checkbox name=\"wk2[$i]\" value='x'";
   if($curweak[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $weaks2[$i]<br>";
}
echo "</td></tr>";
echo "<tr align=left><td colspan=2><br><b>Comments:</b><br>";
if($submitted==1)
   echo "<i>$comments2</i></td></tr>";
else
   echo "<textarea rows=4 cols=70 name=\"comments2\">$comments2</textarea></td></tr>";

//Communication:
echo "<tr align=left><th align=left width=50%><br><u>Communication:</u></th>";
/*echo "<th align=right width=50%><br>Rating: ";
if($submitted==1)
{
   echo "<u>$rating3</u></th></tr>";
}
else if($print==1)
{
   echo "<input type=text name=rating3 value=\"$rating3\"></th></tr>";
}
else
{
   echo "<select name=\"rating3\"><option value=''>Please Select</option>";
   for($i=0;$i<count($ans);$i++)
   {
      echo "<option";
      if($rating3==$ans[$i]) echo " selected";
      echo ">$ans[$i]</option>";
   }
   echo "</select></th></tr>";
}*/
$strengths3=array("Use of proper mechanics and signals while making signals clear and easy-to-read","Mechanics during and after near-fall and pinning situations","Communication with wrestlers, coaches and scorers table","Proper sequence in giving warnings, signals and awarding points","Procedures during blood, injury and recovery time","Optional start procedures","Making all calls clear, concise and respectful");
$weaks3=array("Verbally annouce points, warnings and points awarded","Present verbal and visual signals together and clearly","Use only the signals in the rulebook");
echo "<tr align=left valign=top><td><b>Strengths:</b><br><br>";
echo "<input type=hidden name=count3 value=\"".count($strengths3)."\">";
$curstrong=split("/",$strong3);
for($i=0;$i<count($strengths3);$i++)
{
   echo "<input type=checkbox name=\"str3[$i]\" value='x'";
   if($curstrong[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $strengths3[$i]<br>";
}
echo "</td><td><b>Areas for Improvement & Growth:</b><br><br>";
$curweak=split("/",$weak3);
for($i=0;$i<count($weaks3);$i++)
{
   echo "<input type=checkbox name=\"wk3[$i]\" value='x'";
   if($curweak[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $weaks3[$i]<br>";
}
echo "</td></tr>";
echo "<tr align=left><td colspan=2><br><b>Comments:</b><br>";
if($submitted==1)
   echo "<i>$comments3</i></td></tr>";
else
   echo "<textarea rows=4 cols=70 name=\"comments3\">$comments3</textarea></td></tr>";

//Judgment and Rules Applications:
echo "<tr align=left><th align=left width=50%><br><u>Judgment and Rules Applications:</u></th>";
/*echo "<th align=right width=50%><br>Rating: ";
if($submitted==1) 
{
   echo "<u>$rating4</u></th></tr>";
}
else if($print==1)
{
   echo "<input type=text name=rating4 value=\"$rating4\"></th></tr>";
}
else
{
   echo "<select name=\"rating4\"><option value=''>Please Select</option>";
   for($i=0;$i<count($ans);$i++)
   {
      echo "<option";
      if($rating4==$ans[$i]) echo " selected";
      echo ">$ans[$i]</option>";
   }
   echo "</select></th></tr>";
}*/
$strengths4=array("Knowledge of the wrestling rules intent, application and interpretation","Consistency of rules application and judgment","Reaction to fast-paced and difficult situations","Correct positioning for making the correct calls","Overtime procedures");
$weaks4=array("Calling \"Stalling\" when it occurs","Use of the stalemate call to promote aggressive wrestling","Observe other officials and discuss difficult situations","Careful study of the rulebook and casebook","Use of all opportunities to discuss rules and situations with other officials");
echo "<tr align=left valign=top><td><b>Strengths:</b><br><br>";
echo "<input type=hidden name=count4 value=\"".count($strengths4)."\">";
$curstrong=split("/",$strong4);
for($i=0;$i<count($strengths4);$i++)
{
   echo "<input type=checkbox name=\"str4[$i]\" value='x'";
   if($curstrong[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $strengths4[$i]<br>";
}
echo "</td><td><b>Areas for Improvement & Growth:</b><br><br>";
$curweak=split("/",$weak4);
for($i=0;$i<count($weaks4);$i++)
{
   echo "<input type=checkbox name=\"wk4[$i]\" value='x'";
   if($curweak[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $weaks4[$i]<br>";
}
echo "</td></tr>";
echo "<tr align=left><td colspan=2><br><b>Comments:</b><br>";
if($submitted==1)
   echo "<i>$comments4</i></td></tr>";
else
   echo "<textarea rows=4 cols=70 name=\"comments4\">$comments4</textarea></td></tr>";

echo "<tr align=left><th align=left colspan=2><br><u>OVERALL (The official exhibits Superior qualities in the following areas):</u></th></tr>";
echo "<tr align=left><td colspan=2>";
$areas=array("Uses preventative officiating","Maintains control of the match","Shows an interest in every match","Is concerned with the safety of the wrestlers on and off the mat","Use of proper communication techniques on and off the mat","Remains professional at all times before, during and after the contest","Works on being an official that is \"not noticeable\" - Wrestlers are the focus of the match, not the official.");
echo "<input type=hidden name=\"count5\" value=\"".count($areas)."\">";
$curoverall=split("/",$overall);
for($i=0;$i<count($areas);$i++)
{
   echo "<input type=checkbox name=\"over[$i]\" value='x'";
   if($curoverall[$i]=='x') echo " checked";
   if($submitted==1) echo " disabled";
   echo "> $areas[$i]<br>";
}
echo "</td></tr>";

echo "<tr align=left><td colspan=2><br><b>Notes or Comments Taken During Observation of Official:<br></b>";
if($submitted==1)
   echo "<i>$overallcomments</i>";
else
   echo "<textarea rows=5 cols=70 name=\"overallcomments\">$overallcomments</textarea></td></tr>";

if(GetLevel($session)!=2)	//non-officials
{
echo "<tr align=left><th align=left colspan=2><br><u>Comments for NSAA Office Only:</u></th></tr>";
echo "<tr align=left><td colspan=2>Do you recommend this official for a district contract?&nbsp;&nbsp;";
echo "<input type=radio name=district value='Yes'";
if($district=="Yes") echo " checked";
if($submitted==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;<input type=radio name=district value='No'";
if($district=="No") echo " checked";
if($submitted==1) echo " disabled";
echo ">No</td></tr>";
echo "<tr align=left><td colspan=2>Do you recommend this official for a state contract?&nbsp;&nbsp;";
echo "<input type=radio name=state value='Yes'";
if($state=="Yes") echo " checked";
if($submitted==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;<input type=radio name=state value='No'";
if($state=="No") echo " checked";
if($submitted==1) echo " disabled";
echo ">No</td></tr>";
echo "<tr align=left><td colspan=2>Does this official need to be observed again this year?&nbsp;&nbsp;";
echo "<input type=radio name=observe value='Yes'";
if($observe=="Yes") echo " checked";
if($submitted==1) echo " disabled";
echo ">Yes&nbsp;&nbsp;<input type=radio name=observe value='No'";
if($observe=="No") echo " checked";
if($submitted==1) echo " disabled";
echo ">No</td></tr>";

echo "<tr align=left><td colspan=2>Other Comments (only for NSAA to see):<br>";
if($submitted==1)
   echo "<i>$nsaacomments</i></td></tr>";
else
   echo "<textarea rows=4 cols=70 name=\"nsaacomments\">$nsaacomments</textarea></td></tr>";
}
echo "<tr align=left><td colspan=2><br><b>RATING SCALE: ";
for($i=0;$i<count($ans);$i++)
{
   echo $ans[$i]."&nbsp;&nbsp;";
}
echo "</b><br><br></td></tr>";
echo "<th align=left width=50%>Rating: ";
if($submitted==1)
{
   echo "<u>$rating1</u>&nbsp;&nbsp;</th></tr>";
}
else if($print==1)
{
   echo "<input type=text name=rating1 value=\"$rating1\"></th></tr>";
}
else
{
   echo "<select name=\"rating1\"><option value=''>Please Select</option>";
   for($i=0;$i<count($ans);$i++)
   {
      echo "<option";
      if($rating1==$ans[$i]) echo " selected";
      echo ">$ans[$i]</option>";
   }
   echo "</select></th></tr>";
}
if($submitted!=1 && $print!=1)
{
   echo "<tr align=center><td colspan=5><br>";
   echo "<font style=\"color:blue\"><b>NOTE:</b> You may click \"Save & Keep Editing\" if you want to save your work and continue later.  Your evaluation will NOT be sent to the NSAA until you click \"Submit Evaluation\" below.</font><br>";
   echo "<input type=submit name=submit value=\"Save & Keep Editing\"></td></tr>";
   echo "<tr align=center><td colspan=5><br>";
   echo "<font style=\"color:blue\"><b>NOTE: </b>Once you click \"Submit Evaluation\", your submission of this evaluation is final.  YOU MUST CLICK \"Submit Evaluation\" FOR THE EVALUATION TO BE SENT!!!<br>You will be able to view your submitted evaluations, but you will NOT be able to edit them.</font><br>";
   echo "<input type=submit name=submit value=\"Submit Evaluation\"></td></tr>";
}

echo "</table>";

echo "</td></tr></table>";
if($print!=1)
   echo "<a class=small href=\"javascript:window.close();\">Close Window</a>";
}//end if gameid given

echo $end_html;
exit();
?>
