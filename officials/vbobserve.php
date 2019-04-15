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
   $sql="UPDATE $dbname.vbobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}

if($submit && $submit!="Go")	//put eval in db and show user what he/she entered
{
   $home=addslashes($home);
   $visitor=addslashes($visitor);
   $site=addslashes($site);
   $dateeval=time();
   $comments=addslashes($comments);
   $other=addslashes($other);

   $sql="SELECT * FROM $dbname.vbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO $dbname.vbobserve (obsid,offid,gameid,home,position,site,visitor,prof1,prof2,prof3,prof4,prof5,match1,match2,match3,match4,match5,judge1,judge2,judge3,judge4,judge5,judge6,mech1,mech2,mech3,mech4,other,comments,postseason,postlevel) VALUES ('$obsid','$offid','$gameid','$home','$position','$site','$visitor','$prof1','$prof2','$prof3','$prof4','$prof5','$match1','$match2','$match3','$match4','$match5','$judge1','$judge2','$judge3','$judge4','$judge5','$judge6','$mech1','$mech2','$mech3','$mech4','$other','$comments','$postseason','$postlevel')";
   }
   else
   {
      $sql2="UPDATE $dbname.vbobserve SET home='$home',position='$position',site='$site',visitor='$visitor',prof1='$prof1',prof2='$prof2',prof3='$prof3',prof4='$prof4',prof5='$prof5',match1='$match1',match2='$match2',match3='$match3',match4='$match4',match5='$match5',judge1='$judge1',judge2='$judge2',judge3='$judge3',judge4='$judge4',judge5='$judge5',mech1='$mech1',mech2='$mech2',mech3='$mech3',mech4='$mech4',other='$other',comments='$comments',postseason='$postseason',postlevel='$postlevel' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   }
   $result2=mysql_query($sql2);
   //echo "$sql2<br>".mysql_error();

   //if "Saved", do NOT put dateeval in, but if Submitted, put dateeval in:
   if($submit=="Submit Evaluation")
   {
      $sql2="UPDATE $dbname.vbobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
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
	 $Text="A Nebraska School Activities Association Volleyball Official's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
         $Html="A Nebraska School Activities Association Volleyball Official's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!";   
	 $Attm=array();
	 SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
      }
   }
}

//get answers if already submitted, and show NON-EDITABLE evaluation
$sql="SELECT * FROM $dbname.vbobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
//echo $sql;
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$id=$row[id];
if(mysql_num_rows($result)>0 && $row[dateeval]!="")	
{
   $saved=0; $submitted=1;
}
else if(mysql_num_rows($result)>0) 
{
   $saved=1;
   $submitted=0;
}
else
{
   $saved=0; $submitted=0;
}
$home=$row[home]; $visitor=$row[visitor];
$site=$row[site]; $position=$row[position];
$prof1=$row[prof1]; $prof2=$row[prof2]; $prof3=$row[prof3]; $prof4=$row[prof4]; $prof5=$row[prof5];
$match1=$row[match1]; $match2=$row[match2]; $match3=$row[match3]; $match4=$row[match4]; $match5=$row[match5];
$judge1=$row[judge1]; $judge2=$row[judge2]; $judge3=$row[judge3];
$judge4=$row[judge4]; $judge5=$row[judge5]; $judge6=$row[judge6];
$mech1=$row[mech1]; $mech2=$row[mech2]; $mech3=$row[mech3]; $mech4=$row[mech4];
$comments=$row[comments];
$other=$row[other];
$postseason=$row[postseason]; $postlevel=$row[postlevel];
$dateeval=date("F d, Y",$row[dateeval]);
//get schools listed for this game
$sql="SELECT schools FROM $dbname.vbsched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schools=$row[0];

echo $init_html;
echo "<table style=\"width:100%;\"><tr align=center><td>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "<br><br>";

echo "<form method=post action=\"vbobserve.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=offid value=$offid>";
echo "<input type=hidden name=gameid value=$gameid>";
echo "<input type=hidden name=obsid value=$obsid>";

if($submit=="Save & Keep Editing" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been saved.  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br>You must click \"Submit Evalution\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</b></font><br><br>";
else if($submit=="Submit Evaluation" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br><br>";

echo "<table cellspacing=0 cellpadding=5";
if($print!=1) echo " class=nine";
echo "><caption><b>NSAA Volleyball Officials Evaluation Form:</b><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " ".$dateeval;
echo ")<hr>";
if(GetLevel($session)==1 && $gameid && $gameid!='new')
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=vb&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $submit=="Go")
{
   echo "<tr align=center><td><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.vbsched WHERE offid='$offid' ORDER BY offdate";
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
//$ans=array("Superior","Above Average","Average","Below Average","Needs Improvement");
//9/18/13: New answer set:
$ans=array("Uses Preferred Technique","Technique is Acceptable but Modification is Necessary to Attain Top Level","Needs Improvement (Needs More Training)");

if($gameid && $gameid!="new" || $print==1)
{

echo "<tr align=center><td><table>";

//show main information about game:
if($print!=1)
{
   echo "<tr align=left><td><b>Name of Official:</b></td>";
   $sql="SELECT first,last FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td>$row[first] $row[last]</td></tr>";
   echo "<tr align=left><td><b>Position:</b></td>";
   echo "<td><input type=radio name='position' value='R1'";
   if($position=="R1") echo " selected";
   echo "> R1&nbsp;&nbsp;"; 
   echo "<input type=radio name='position' value='R2'";
   if($position=="R2") echo " selected";
   echo "> R2</td></tr>";   
   //get date of game
   $sql="SELECT offdate FROM $dbname.vbsched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
         echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
         echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
         $sql="SELECT * FROM $dbname.vbsched WHERE offid='$offid' ORDER BY offdate";
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
   echo "<tr align=left><td><b>Name of Official:</b></td>";
   echo "<td><input type=text size=40 name=officials></td></tr>";
   echo "<tr align=left><td><b>Position:</b></td>";
   echo "<td><input type=radio name='position' value='R1'> R1&nbsp;&nbsp;";
   echo "<input type=radio name='position' value='R2'> R2</td></tr>"; 
   echo "<tr align=left><td><b>Date Evaluated:</b></td>";
   echo "<td><input type=text size=30 name=dateeval></td></tr>";
}
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
echo "<tr align=left><td><b>Site:</b></td>";
if($submitted==1)
   echo "<td>$site</td></tr>";
else
   echo "<td><input type=text class=tiny size=30 name=site value=\"$site\"></td></tr>";
echo "</table></td></tr>";

//evaluation questions:
echo "<tr align=center><td><table";
if($submitted==1)
   echo " style=\"border:#808080 1px solid;\" frame=all rules=all";
echo " cellspacing=0 cellpadding=2>";
//Professionalism
$profques=array("Appearance: wears proper uniform, neat, well-groomed","Communication with coaches and other game personnel: handles all interactions professionally and efficiently, monitors and anticipates requests from the bench, maintains an approachable demeanor and positive attitude","Projects confidence in oneself and in the performance of the entire officiating team","Displays a professional demeanor throughout the match");
echo "<tr align=left><td colspan=2 align=left><h2>A) PROFESSIONALISM:</h2></td></tr>";
/*
if($submitted==1)	//show column headers for answers
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
*/
for($x=0;$x<count($profques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top";
   if($i%2==0) echo " bgcolor='#f0f0f0'";
   echo "><td width='400px'><p>$i.&nbsp;$profques[$x]</p></td><td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="prof".$i;
	/*
      if($submitted==1)	//show what they answered
      {
	 if($$temp==$ans[$j]) 
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
	*/
         echo "<p><input type=radio name=\"prof".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
	 if($submitted==1) echo " disabled";
         echo ">$ans[$j]&nbsp;</p>";
      //}
   }
   echo "</td></tr>";
}
//Match Control
$matchques=array("Safety assured: inspects playing site and equipment, assures player safety","Assesses unsportsmanlike penalties when warranted","Is knowledgeable about rules and uses them appropriately","Does not allow decisions to be affected by comments of spectators, players, or coaches","Allows other members of officiating team to perform appropriate duties (line judges, etc.)");
echo "<tr align=left><td colspan=2><br><h2>B) MATCH CONTROL:</h2></td></tr>";
/*
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
*/
for($x=0;$x<count($matchques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top";
   if($i%2==0) echo " bgcolor='#f0f0f0'";
   echo "><td width='400px'><p>$i.&nbsp;$matchques[$x]</p></td><td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="match".$i;
	/*
      if($submitted==1)
      {
	 if($$temp==$ans[$j]) 
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
	*/
         echo "<p><input type=radio name=\"match".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         if($submitted==1) echo " disabled";
         echo ">$ans[$j]&nbsp;</p>";
      //}
   }
   echo "</td></tr>";
}
//Judgment
$judgeques=array("Maintains same standards of ball-handling consistency from one game to the next within the match (R1 Only)","Maintains consistency appropriate to skill levels of teams (R1 Only)","Recognizes and calls back row player violations","Recognizes and calls illegal alignment (R2 Only)","Calls net violations (R2 Only)","Calls definite center line violations (R2 Only)");
echo "<tr align=left><td colspan=2><br><h2>C) JUDGMENT:</h2></td></tr>";
/*
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$j]</th>";
   }
   echo "</tr>";
}
*/
for($x=0;$x<count($judgeques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top";
   if($i%2==0) echo " bgcolor='#f0f0f0'";
   echo "><td width='400px'><p>$i.&nbsp;$judgeques[$x]</p></td><td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="judge".$i;
	/*
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
	*/
         echo "<p><input type=radio name=\"judge".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         if($submitted==1) echo " disabled";
         echo ">$ans[$j]&nbsp;</p>";
      //}
   }
   echo "</td></tr>";
}
//Mechanics
$mechques=array("Whistle is clear, sharp, and authoritative","Uses correct hand signals and techniques","Substitutions: uses correct signal, efficient, does not disrupt flow of game (R2 Only)","Makes decisions quickly and accurately, using input from other members of the officiating team (R1 Only)");
echo "<tr align=left><td colspan=2><br><h2>D) MECHANICS:</h2></td></tr>";
/*
if($submitted==1)
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
*/
for($x=0;$x<count($mechques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top";
   if($i%2==0) echo " bgcolor='#f0f0f0'";
   echo "><td width='400px'><p>$i.&nbsp;$mechques[$x]</p></td><td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="mech".$i;
	/*
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
	*/
         echo "<p><input type=radio name=\"mech".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         if($submitted==1) echo " disabled";
         echo ">$ans[$j]&nbsp;</p>";
      //}
   }
   echo "</td></tr>";
}
//COMMENTS
echo "<tr align=left><td colspan=2><br><b>Comments:</b><br>";
if($submitted==1)
   echo "$other</td></tr>";
else
   echo "<textarea style=\"width:700px;height:200px;\" name=other>$other</textarea></td></tr>";

if(GetLevel($session)!=2)       //if not an official, show comments for NSAA
{
   echo "<tr align=left><td colspan=7><b>Comments for NSAA only:</b><br>";
   if($submitted==1)
      echo "$comments</td></tr>";
   else
      echo "<textarea rows=5 cols=70 name=comments>$comments</textarea></td></tr>";

   //Post Season
   echo "<tr align=left><td><b>Recommendations for Post Season Assignments:</td>";
   if($submitted==1)
   {
      echo "<td colspan=6>".strtoupper($postseason)."</td></tr>";
   }
   else
   {
      echo "<td colspan=6><input type=radio name=postseason value='yes'";
      if($postseason=='yes') echo " checked";
      echo ">Yes&nbsp;";
      echo "<input type=radio name=postseason value='no'";
      if($postseason=='no') echo " checked";
         echo ">No</td></tr>";
   }
   echo "<tr align=left><td><b>If yes, at what level?</td>";
   echo "<td colspan=6>";
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
   echo "<tr align=center><td colspan=2><br><font style=\"color:blue\"><b>NOTE:</b> You may click \"Save & Keep Editing\" if you want to save your work and continue later.<br> Your evaluation will NOT be sent to the NSAA until you click \"Submit Evaluation\" below.</font><br>";
   echo "<input type=submit name=submit value=\"Save & Keep Editing\"></td></tr>";
   echo "<tr align=center><td colspan=2><br><font style=\"color:blue\"><b>NOTE:</b> Once you click \"Submit Evaluation\", your submission of this evaluation is final.<br>YOU MUST CLICK \"Submit Evaluation\" FOR THE EVALUATION TO BE SENT!!!<br>You will be able to view your submitted evaluations, but you will NOT be able to edit them.<br><input type=submit name=submit value=\"Submit Evaluation\"></font></td></tr>";
}

echo "</table>";

echo "</td></tr></table>";
if($print!=1)
   echo "<br><br><a class=small href=\"javascript:window.close();\">Close Window</a>";
}//end if gameid given

echo $end_html;

?>
