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
   $sql="UPDATE $dbname.sbobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}

if($submit && $submit!="Go")	//put eval in db and show user what he/she entered
{
   $home=addslashes($home);
   $visitor=addslashes($visitor);
   $generalcomments=addslashes($generalcomments);
   $nonroutcomments=addslashes($nonroutcomments);
   $homeplcomments=addslashes($homeplcomments);
   $basecomments=addslashes($basecomments);
   $addlcomments=addslashes($addlcomments);
   $comments=addslashes($comments);
   $dateeval=time();

   $sql="SELECT * FROM $dbname.sbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO sbobserve (obsid,offid,gameid,home,visitor,general1,general2,general3,general4,general5,general6,general7,generalcomments,nonrout1,nonrout2,nonrout3,nonroutcomments,homepl1,homepl2,homepl3,homepl4,homepl5,homepl6,homepl7,homepl8,homepl9,homeplcomments,base1,base2,base3,base4,base5,base6,base7,base8,base9,basecomments,addlcomments,comments,postseason,postleveldist,postlevelstate) VALUES ('$obsid','$offid','$gameid','$home','$visitor','$general1','$general2','$general3','$general4','$general5','$general6','$general7','$generalcomments','$nonrout1','$nonrout2','$nonrout3','$nonroutcomments','$homepl1','$homepl2','$homepl3','$homepl4','$homepl5','$homepl6','$homepl7','$homepl8','$homepl9','$homeplcomments','$base1','$base2','$base3','$base4','$base5','$base6','$base7','$base8','$base9','$basecomments','$addlcomments','$comments','$postseason','$postleveldist','$postlevelstate')";
   }
   else
   {
      $sql2="UPDATE sbobserve SET home='$home',visitor='$visitor',general1='$general1',general2='$general2',general3='$general3',general4='$general4',general5='$general5',general6='$general6',general7='$general7',generalcomments='$generalcomments',nonrout1='$nonrout1',nonrout2='$nonrout2',nonrout3='$nonrout3',nonroutcomments='$nonroutcomments',homepl1='$homepl1',homepl2='$homepl2',homepl3='$homepl3',homepl4='$homepl4',homepl5='$homepl5',homepl6='$homepl6',homepl7='$homepl7',homepl8='$homepl8',homepl9='$homepl9',homeplcomments='$homeplcomments',base1='$base1',base2='$base2',base3='$base3',base4='$base4',base5='$base5',base6='$base6',base7='$base7',base8='$base8',base9='$base9',basecomments='$basecomments',addlcomments='$addlcomments',comments='$comments',postseason='$postseason',postleveldist='$postleveldist',postlevelstate='$postlevelstate' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   }
   $result2=mysql_query($sql2);
   //echo "$sql2<br>".mysql_error();

   //if "Saved", don't put dateeval in; if "submitted", put it in
   if($submit=="Submit Evaluation")
   {
      $sql2="UPDATE sbobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
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
	 $Text="A Nebraska School Activities Association Softball Umpire's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
         $Html="A Nebraska School Activities Association Softball Umpire's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!";
  	 $Attm=array();
	 SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
      }
   }
}

//get answers if already submitted
$sql="SELECT * FROM $dbname.sbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
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
$home=$row[home]; $visitor=$row[visitor];
$general1=$row[general1]; $general2=$row[general2]; $general3=$row[general3];
$general4=$row[general4]; $general5=$row[general5]; $general6=$row[general6]; 
$general7=$row[general7]; $generalcomments=$row[generalcomments];
$nonrout1=$row[nonrout1]; $nonrout2=$row[nonrout2]; $nonrout3=$row[nonrout3];
$nonroutcomments=$row[nonroutcomments];
$homepl1=$row[homepl1]; $homepl2=$row[homepl2]; $homepl3=$row[homepl3]; $homepl4=$row[homepl4];
$homepl5=$row[homepl5]; $homepl6=$row[homepl6]; $homepl7=$row[homepl7]; $homepl8=$row[homepl8];
$homepl9=$row[homepl9]; $homeplcomments=$row[homeplcomments];
$base1=$row[base1]; $base2=$row[base2]; $base3=$row[base3]; $base4=$row[base4];
$base5=$row[base5]; $base6=$row[base6]; $base7=$row[base7]; $base8=$row[base8];
$base9=$row[base9]; $basecomments=$row[basecomments];
$addlcomments=$row[addlcomments];
$comments=$row[comments];
$postseason=$row[postseason];
$postleveldist=$row[postleveldist]; $postlevelstate=$row[postlevelstate];
$dateeval=date("F d, Y",$row[dateeval]);
//get schools listed on this schedule entry
$sql="SELECT schools FROM $dbname.sbsched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schools=$row[0];

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<center>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "<br>";

if($print!=1) 
{
   echo "<form method=post action=\"sbobserve.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=offid value=$offid>";
   echo "<input type=hidden name=gameid value=$gameid>";
   echo "<input type=hidden name=obsid value=$obsid>";
   if($submit=="Save & Keep Editing")
      echo "<font style=\"color:blue\"><b>Your evaluation has been saved.  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br>You must click \"Submit Evaluation\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</b></font><br><br>";
   else if($submit=="Submit Evaluation")
      echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br><br>";
}
echo "<table width=95%><caption><b>NSAA Softball Umpire Observation Report:</b><br>";
echo "<font style=\"font-size:9pt\"><i>The purpose of this observation report is to give constructive feedback, which is designed to improve the technique and overall performance of the official.</i></font><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " $dateeval";
echo ")<hr>";
if(GetLevel($session)==1 && $gameid && $gameid!='new')
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=sb&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $submit=="Go")
{
   echo "<tr align=center><td><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.sbsched WHERE offid='$offid' ORDER BY offdate";
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
//$ans=array("Unacceptable","Fair","Average","Good","Very Good","Outstanding");
$ans=array("Needs Improvement","Good","Very Good","Outstanding");

if($gameid && $gameid!="new" || $print==1)
{
echo "<tr align=center><td><table>";
//show main information about game:
echo "<tr align=left><td><b>Name of Umpire:</b></td>";
if($print!=1)
{
   $sql="SELECT first,last FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td>$row[first] $row[last]</td></tr>";
   //get date of game
   $sql="SELECT offdate,schools FROM $dbname.sbsched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
         echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
         echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
         $sql="SELECT * FROM $dbname.sbsched WHERE offid='$offid' ORDER BY offdate";
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
   echo "<tr align=left><td><b>Observation Date:</b></td>";
   $temp=split("-",$row[0]);
   $offdate="$temp[1]/$temp[2]/$temp[0]";
   echo "<td>$offdate</td></tr>";
   echo "<tr align=left><td><b>Schools:</b></td>";
   echo "<td>$row[schools]</td></tr>";
   }
}
else
{
   echo "<td><input type=text name=umpire size=30></td></tr>";
   echo "<tr align=left><td><b>Date Evaluated:</b></td>";
   echo "<td><input type=text name=dateeval size=30></td></tr>";
}
echo "<tr align=left><td><b>Home Team:</b></td>";
if($submitted==1)
   echo "<td>$home</td>";
else
   echo "<td><input type=text class=tiny size=30 name=home value=\"$home\"></td></tr>";
echo "<tr align=left><td><b>Visiting Team:</b></td>";
if($submitted==1)
   echo "<td>$visitor</td>";
else
   echo "<td><input type=text class=tiny size=30 name=visitor value=\"$visitor\"></td></tr>";
echo "</table></td></tr>";

//evaluation questions:
echo "<tr align=center><td><table";
if($submitted==1)
   echo " border=1 bordercolor=#000000";
echo " cellspacing=1 cellpadding=2>";
//General
$genques=array("Pregame Duties","Appearance","Professional Demeanor","Projection of Confidence","Focus on the Game","Intensity/Alertness","Hustle");
$gennote=array("Arrive 30 min before game.  Check field and team equipment.","Do they look like umpires?  Are shirts and slacks clean and pressed?  Shoes polished?","How do they carry themselves on the field?  Do they take the proper position between innings?","Appears sure and decisive when making calls.  Do they have control of the game?","Do they talk to coaches/players too much?  Anticipate plays and upcoming situations.","Sharp and consistent from first pitch to final out.","Ready and willing to move to get into the proper position.");
echo "<tr align=left><th colspan=5 align=left class=smaller>GENERAL:</th></tr>";
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
for($x=0;$x<count($genques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250><b>$i.&nbsp;$genques[$x]</b><br>($gennote[$x])</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="general".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"general".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

echo "<tr align=left><td colspan=5><b>Comments:</b><br>";
if($submitted==1)
   echo "$generalcomments</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=generalcomments>$generalcomments</textarea></td></tr>";

//Non-Routine
$nrques=array("Knowledge/Application of Rules","Overall Ability to Handle Situations","Performance Under Pressure");
$nrnote=array("Applies and enforces National Federation Rules correctly.","Explains, listens, stays calm and professional.  Willingness to confer with partner to get the call right.","Makes the tough calls and gets them correct.");
echo "<tr align=left><td colspan=5><b><br>NON-ROUTINE SITUATIONS:</td></tr>";
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
for($x=0;$x<count($nrques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250><b>$i.&nbsp;$nrques[$x]</b><br>($nrnote[$x])</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="nonrout".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"nonrout".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

echo "<tr align=left><td colspan=5><b>Comments:</b><br>";
if($submitted==1)
   echo "$nonroutcomments</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=nonroutcomments>$nonroutcomments</textarea></td></tr>";

//Home Plate Umpire
$hpques=array("Plate Mechanics","Judgement of Strike Zone","Consistency of Strike Zone","Use of Voice/Signals","Timing of Calls","Mobility","Reaction to Developing Plays","Communications with Partner(s)","Crew Mechanics/Field Coverage");
$hpnote=array("Takes proper position in the slot.  Places head in same position and is still on every pitch.","Calls the high and low strikes and ball over the plate.  Handles breaking pitch well.","Misses few pitches.  Stays the same inning-to-inning.  Handles breaking pitch well.","Makes calls clear with voice and uses proper signals and techniques.","Takes time to see the pitch completely before making call.","Shows lateral quickness and good foot speed to see plays and get out of the way.","Anticipates where to go on the field and takes correct route to get into position.","Uses hand or verbal signals, makes eye contact with partner(s) to cover possible situations.  Asks for help on check swing.","Knows where to go on the field, takes the correct position to make calls.");
echo "<tr align=left><td colspan=5><b><br>HOME PLATE UMPIRE:</b></td></tr>";
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
for($x=0;$x<count($hpques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250><b>$i.&nbsp;$hpques[$x]</b><br>($hpnote[$x])</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="homepl".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"homepl".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}
echo "<tr align=left><td colspan=5><b>Comments:</b><br>";
if($submitted==1)
   echo "$homeplcomments</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=homeplcomments>$homeplcomments</textarea></td></tr>";

//Base Umpire
$bques=array("Judgement of Plays","Stable Position to Make Calls","Mechanics (Angle/Proximity)","Use of Voice/Signals","Timing of Calls","Mobility","Reaction to Developing Plays","Communication with Partner(s)","Crew Mechanics/Field Coverage");
$bnote=array("Makes the call with authority.","Stops, steadies body and head to see the play.","Takes the proper angle and gets within the proper distance to see the play and make the call.","Makes calls clear with voice and uses proper signals and techniques.","Sees the whole play before making the call.  Looks for possession before making the call.","Shows quickness and good foot speed to get in position to see plays.","Anticipates where to go on the field and takes correct route to get into position.","Uses hand or verbal signals, makes eye contact with partner(s) to cover possible situations.","Knows where to go on the field and takes correct position to make calls.");
echo "<tr align=left><td colspan=5><b><br>BASE UMPIRE:</b></td></tr>";
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
for($x=0;$x<count($bques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=250><b>$i.&nbsp;$bques[$x]</b><br>($bnote[$x])</td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="base".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"base".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}
echo "<tr align=left><td colspan=5><b>Comments:</b><br>";
if($submitted==1)
   echo "$basecomments</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=basecomments>$basecomments</textarea></td></tr>";

//additional comments
echo "<tr align=left><td colspan=5><b><br>ADDITIONAL COMMENTS:</b><br>";
if($submitted==1)
   echo "$addlcomments</td></tr>";
else
   echo "<textarea rows=5 cols=90 name=addlcomments>$addlcomments</textarea></td></tr>";

//Comments for NSAA Only
if(GetLevel($session)!=2)       //if not an official, show comments for NSAA
{
   echo "<tr align=left><td colspan=5><b>Comments for NSAA only:</b><br>";
   if($submitted==1)   
      echo "$comments</td></tr>";
   else
      echo "<textarea rows=5 cols=70 name=comments>$comments</textarea></td></tr>";
    
   //Post Season
   echo "<tr align=left><td><b>Recommendations for Post Season Assignments:</td>";
   if($submitted==1)
   {
      echo "<td colspan=4>".strtoupper($postseason)."</td></tr>";
   }
   else
   {
      echo "<td colspan=4><input type=radio name=postseason value='yes'";
      if($postseason=='yes') echo " checked";
      echo ">Yes&nbsp;&nbsp;";
      echo "<input type=radio name=postseason value='no'";
      if($postseason=='no') echo " checked";
      echo ">No</td></tr>";
   }
   echo "<tr align=left valign=top><td><b>If yes, at what level?</td>";
   echo "<td colspan=4>";
   echo "<table>";
   if($submitted==1)
   {
      echo "<tr align=left><td><b>District Tournament:</b></td><td>$postleveldist</td></tr>";
      echo "<tr align=left><td><b>State Tournament:</b></td><td>$postlevelstate</td></tr>";
   }
   else
   {
      $classes=array("A","B","C","Any Class");
      echo "<tr align=left><td><b>District Tournament:</b></td>";
      echo "<td>";
      for($i=0;$i<count($classes);$i++)
      {
 	 echo "<input type=radio name=postleveldist value=\"$classes[$i]\"";
	 if($postleveldist==$classes[$i]) echo " checked";
	 echo ">$classes[$i]&nbsp;";
      }
      echo "</td></tr>";
      echo "<tr align=left><td><b>State Tournament:</b></td>";
      echo "<td>";
      for($i=0;$i<count($classes);$i++)
      {
	 echo "<input type=radio name=postlevelstate value=\"$classes[$i]\"";
	 if($postlevelstate==$classes[$i]) echo " checked";
	 echo ">$classes[$i]&nbsp;";
      }
      echo "</td></tr>";
   }
   echo "</table></td></tr>";
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

?>
