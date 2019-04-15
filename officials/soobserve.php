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
else if(GetLevel($session)==3)	//observer user, obsid given
{
   $curobsid=GetObsID($session);
}
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
   $sql="UPDATE $dbname.soobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}

if($submit && $submit!="Go")	//put eval in db and show user what he/she entered
{
   $home=addslashes($home);
   $visitor=addslashes($visitor);
   $position=addslashes($position);
   $dateeval=time();
   for($i=1;$i<=8;$i++)
   {
      $field="obs".$i;
      $$field=addslashes($$field);
   }
   $comments=addslashes($comments);
   $gdist=addslashes($gdist); $gstate=addslashes($gstate);
   $bdist=addslashes($bdist); $bstate=addslashes($bstate);

   $sql="SELECT * FROM $dbname.soobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO $dbname.soobserve (obsid,offid,gameid,home,visitor,homescore,visscore,position,score1,obs1,score2,obs2,score3,obs3,score4,obs4,score5,obs5,score6,obs6,score7,obs7,score8,obs8,gdist,gstate,bdist,bstate,comments) VALUES ('$obsid','$offid','$gameid','$home','$visitor','$homescore','$visscore','$position','$score1','$obs1','$score2','$obs2','$score3','$obs3','$score4','$obs4','$score5','$obs5','$score6','$obs6','$score7','$obs7','$score8','$obs8','$gdist','$gstate','$bdist','$bstate','$comments')";
   }
   else
   {
      $sql2="UPDATE $dbname.soobserve SET home='$home',visitor='$visitor',homescore='$homescore',visscore='$visscore',position='$position',score1='$score1',obs1='$obs1',score2='$score2',obs2='$obs2',score3='$score3',obs3='$obs3',score4='$score4',obs4='$obs4',score5='$score5',obs5='$obs5',score6='$score6',obs6='$obs6',score7='$score7',obs7='$obs7',score8='$score8',obs8='$obs8',gdist='$gdist',gstate='$gstate',bdist='$bdist',bstate='$bstate',comments='$comments' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
   }
   $result2=mysql_query($sql2);
   //echo "$sql2<br>".mysql_error();

   //if "Saved", do NOT put dateeval in, but if Submitted, put dateeval in:
   if($submit=="Submit Evaluation")
   {
      $sql2="UPDATE $dbname.soobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
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
	 $Text="A Nebraska School Activities Association Soccer Official's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
         $Html="A Nebraska School Activities Association Soccer Official's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!";   
	 $Attm=array();
	 SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
      }
   }
}

//get answers if already submitted, and show NON-EDITABLE evaluation
$sql="SELECT * FROM $dbname.soobserve WHERE obsid='$obsid' AND offid='$offid' AND gameid='$gameid'";
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
$homescore=$row[homescore]; $visscore=$row[visscore];
$position=trim($row[position]);
   $sql2="SELECT positions,otheroff FROM $dbname.sosched WHERE offid='$offid' AND id='$gameid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($position=="") //if position field empty, grab from so schedule
      $position=trim($row2[positions]);
   $otheroffs=$row2[otheroff];
for($i=1;$i<=8;$i++)
{
   $field="score".$i; $field2="obs".$i;
   $$field=$row[$field]; $$field2=$row[$field2];
}
$gdist=$row[gdist]; $gstate=$row[gstate]; 
$bdist=$row[bdist]; $bstate=$row[bstate];
$comments=$row[comments];
$dateeval=date("F d, Y",$row[dateeval]);
//get schools listed for this game
$sql="SELECT schools FROM $dbname.sosched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schools=$row[0];
if($export)
{ 
	header('Content-Type: text/odt; charset=utf-8');
	header('Content-Disposition: attachment; filename=SchoolList.odt');
	$output = fopen('php://output', 'w');

   echo "";
   echo "Nebraska School Activities Association Soccer Officials Observation Form: \r\n";
   echo "(Evaluated by $obsname";
   echo " ".$dateeval.")";
   echo "\r\n";
   echo "\r\n";
   echo "Name of Referee: ";
   $sql="SELECT first,last FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$row[first] $row[last]\r\n";
   echo "Position: ";
   echo "$position\r\n";
 
   echo "Other Officials: ";
   echo "$otheroffs\r\n";

   $sql="SELECT offdate FROM $dbname.sosched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "Date Observed: ";
   $temp=split("-",$row[0]);
   $offdate="$temp[1]/$temp[2]/$temp[0]";
   echo "$offdate\r\n";
   
   echo "Schools: ";
   echo "$schools\r\n";
   echo "Home Team: ";
   echo "$home  ";
   echo "Final Home Score: ";
   echo "$homescore\r\n";

 	echo "Visiting Team: ";
	echo "$visitor  ";
	echo "Final Visitor Score: ";
	echo "$visscore\r\n";
	echo "\r\n";
	echo "\r\n";
	echo "\r\n";

   $ques=array("Pregame/Postgame responsibilities","Appearance/Fitness","Positioning","Mechanics/Signals"," FOUL RECOGNITION","Application of Rules/Consistency of Calls","Communication with coaches/players/partners"," Game Management (Control of the Game)");
   $ans=array("Superior","Above Average","Average","Below Average","Needs Improvement");
   for($x=0;$x<count($ques);$x++)
   {
   $i=$x+1; $temp="score".$i; $temp2="obs".$i;
   echo "$i) $ques[$x] ---- ";
   for($j=0;$j<count($ans);$j++)
   {

	 if($$temp==$ans[$j]) 
	    echo $ans[$j];
	 else
	    echo " ";
   }
   $cols=$j+1;
      echo $$temp2;
   echo "\r\n";
   }
   exit;
}
echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "<br><br>";

echo "<form method=post action=\"soobserve.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=offid value=$offid>";
echo "<input type=hidden name=gameid value=$gameid>";
echo "<input type=hidden name=obsid value=$obsid>";

if($submit=="Save & Keep Editing" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been saved.  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br>You must click \"Submit Evalution\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</b></font><br><br>";
else if($submit=="Submit Evaluation" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br><br>";

echo "<table><caption><img src=\"nsaacontract.png\"><br>";
echo "<b>Nebraska School Activities Association<br>Soccer Officials Observation Form:</b><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " ".$dateeval;
echo ")<hr>";
if(GetLevel($session)==1 && $gameid && $gameid!='new')
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=so&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $submit=="Go")
{
   echo "<tr align=center><td><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.sosched WHERE offid='$offid' ORDER BY offdate";
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
$ans=array("Superior","Above Average","Average","Below Average","Needs Improvement");

if($gameid && $gameid!="new" || $print==1)
{

echo "<tr align=center><td><br><table>";

//show main information about game:
if($print!=1)
{
   echo "<tr align=left><td width=100><b>Name of Referee:</b></td>";
   $sql="SELECT first,last FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<td>$row[first] $row[last]</td></tr>";
   echo "<tr align=left><td width=100><b>Position:</b></td>";
   if($submitted==1) echo "<td>$position</td></tr>";
   else
      echo "<td><input type=text class=tiny size=10 name=position value=\"$position\"></td></tr>"; 
   echo "<tr align=left><td width=100><b>Other Officials:</b></td>";
   echo "<td>$otheroffs</td></tr>";
   //get date of game
   $sql="SELECT offdate FROM $dbname.sosched WHERE id='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
         echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
         echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
         $sql="SELECT * FROM $dbname.sosched WHERE offid='$offid' ORDER BY offdate";
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
   echo "<tr align=left><td width=100><b>Date Observed:</b></td>";
   $temp=split("-",$row[0]);
   $offdate="$temp[1]/$temp[2]/$temp[0]";
   echo "<td>$offdate</td></tr>";
   }
}
else
{
   echo "<tr align=left><td width=100><b>Name of Officials:</b></td>";
   echo "<td><input type=text size=40 name=officials></td></tr>";
   echo "<tr align=left><td width=100><b>Position:</b></td>";
   echo "<td><input type=text size=30 name=position></td></tr>";
   echo "<tr align=left><td width=100><b>Date Evaluated:</b></td>";
   echo "<td><input type=text size=30 name=dateeval></td></tr>";
}
echo "<tr align=left><td><b>Schools:</b></td>";
echo "<td>$schools</td></tr>";
echo "<tr align=left><td colspan=2><table>";
echo "<tr align=left><td><b>Home Team:</b></td>";
if($submitted==1)
   echo "<td>$home</td>";
else
   echo "<td><input type=text class=tiny size=30 name=home value=\"$home\"></td>";
echo "<td><b>Final Home Score:</b></td>";
if($submitted==1)
   echo "<td>$homescore</td></tr>";
else 
   echo "<td><input type=text class=tiny size=2 name=homescore value=\"$homescore\"></td></tr>";
echo "<tr align=left><td><b>Visiting Team:</b></td>";
if($submitted==1)
   echo "<td>$visitor</td>";
else
   echo "<td><input type=text class=tiny size=30 name=visitor value=\"$visitor\"></td>";
echo "<td><b>Final Visitor Score:</b></td>";
if($submitted==1)
   echo "<td>$visscore</td></tr>";
else
   echo "<td><input type=text class=tiny size=2 name=visscore value=\"$visscore\"></td></tr>";
echo "</table></td></tr>";
echo "</table></td></tr>";

//evaluation questions:
echo "<tr align=center><td><br><table";
if($submitted==1)
   echo " border=1 bordercolor=#000000";
echo " cellspacing=1 cellpadding=2>";
echo "<tr align=left><td colspan=6><b><u>AREAS OF EVALUATION:</u> (<i>Please indicate a rating and enter additional comments as necessary for each area.</i>)</b></td></tr>";

$ques=array("Pregame/Postgame responsibilities","Appearance/Fitness","Positioning","Mechanics/Signals"," FOUL RECOGNITION","Application of Rules/Consistency of Calls","Communication with coaches/players/partners"," Game Management (Control of the Game)");

if($submitted==1)	//show column headers for answers
{
   echo "<tr align=center><td>&nbsp;</td>";
   for($x=0;$x<count($ans);$x++)
   {
      echo "<th class=small>$ans[$x]</th>";
   }
   echo "</tr>";
}
for($x=0;$x<count($ques);$x++)
{
   $i=$x+1; $temp="score".$i; $temp2="obs".$i;
   echo "<tr align=left><td><b>$i)&nbsp;&nbsp;$ques[$x]</b></td>";
   for($j=0;$j<count($ans);$j++)
   {
      if($submitted==1)	//show what they answered
      {
	 if($$temp==$ans[$j]) 
	    echo "<td align=center><b>X</b></td>";
	 else
	    echo "<td>&nbsp;</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"score".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
   $cols=$j+1;
   echo "<tr align=left><td>&nbsp;<td colspan=$j>";
   if($submitted==1)
      echo $$temp2;
   else
      echo "<textarea rows=5 cols=50 name=\"obs".$i."\">".$$temp2."</textarea>";
   echo "</td></tr>";
}

if(GetLevel($session)==1 || (GetLevel($session)==3 && $curobsid==$obsid))       //if not an official, show comments for NSAA
{
   //Post Season
   echo "<tr align=center><td colspan=6><br><table>";
   echo "<tr align=left><td><b>This person is capable of working in what capacity:</td></tr>";
   echo "<tr align=left><td><b>Girls District:</b>&nbsp;";
   if($submitted==1)
      echo $gdist;
   else
      echo "<input type=text class=tiny size=20 name=\"gdist\" value=\"$gdist\">";
   echo "</td></tr>";
   echo "<tr align=left><td><b>Girls State:</b>&nbsp;";
   if($submitted==1)
      echo $gstate;
   else
      echo "<input type=text class=tiny size=20 name=\"gstate\" value=\"$gstate\">";
   echo "</td></tr>";
   echo "<tr align=left><td><b>Boys District:</b>&nbsp;";
   if($submitted==1)
      echo $bdist;
   else
      echo "<input type=text class=tiny size=20 name=\"bdist\" value=\"$bdist\">";
   echo "</td></tr>";
   echo "<tr align=left><td><b>Boys State:</b>&nbsp;";
   if($submitted==1)
      echo $bstate;
   else
      echo "<input type=text class=tiny size=20 name=\"bstate\" value=\"$bstate\">";
   echo "</td></tr></table></td></tr>";

   //Additional Comments
   echo "<tr align=center><td colspan=6><br><table>";
   echo "<tr align=left><td><b>Comments for NSAA only:</b><br>";
   if($submitted==1)
      echo "$comments</td></tr>";
   else
      echo "<textarea rows=10 cols=70 name=comments>$comments</textarea></td></tr>";
   echo "</table></td></tr>";
}

if($submitted!=1 && $print!=1)
{
   echo "<tr align=center><td colspan=6><br><font style=\"color:blue\"><b>NOTE:</b> You may click \"Save & Keep Editing\" if you want to save your work and continue later.<br>Your evaluation will NOT be sent to the NSAA until you click \"Submit Evaluation\" below.</font><br>";
   echo "<input type=submit name=submit value=\"Save & Keep Editing\"></td></tr>";
   echo "<tr align=center><td colspan=6><br><font style=\"color:blue\"><b>NOTE:</b> Once you click \"Submit Evaluation\", your submission of this evaluation is final.<br>YOU MUST CLICK \"Submit Evaluation\" FOR THE EVALUATION TO BE SENT!!!<br>You will be able to view your submitted evaluations, but you will NOT be able to edit them.<br><input type=submit name=submit value=\"Submit Evaluation\"></font></td></tr>";
}

echo "</table>";

echo "</td></tr></table>";
if($print!=1)
   echo "<br><br><a class=small href=\"javascript:window.close();\">Close Window</a>";
}//end if gameid given

echo $end_html;

?>
