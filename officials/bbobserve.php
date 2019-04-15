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
//echo "$dbname, $obsid, $offid, $gameid";

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
   $sql="UPDATE $dbname.bbobserve SET gameid='$newgameid' WHERE gameid='$gameid' AND obsid='$obsid'";
   $result=mysql_query($sql);
   $gameid=$newgameid;
}
if(ereg("-",$gameid))
{
   $gameid=substr($gameid,0,strlen($gameid)-1);
   $postseasongame=1;
}
$sql="SELECT * FROM bbsched WHERE id='$gameid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[girls]=='x')
{
   $disttimes="bbgdisttimes";   
   $districts="bbgdistricts";
   $contracts="bbgcontracts";
}
else
{
   $disttimes="bbbdisttimes";   
   $districts="bbbdistricts";
   $contracts="bbbcontracts";
}

if($save && $save!="Go")	//put eval in db and show user what he/she entered
{
   $home=addslashes($home);
   $visitor=addslashes($visitor);
   $courtcovlead=addslashes($courtcovlead);
   $courtcovtrail=addslashes($courtcovtrail);
   $courtcovcenter=addslashes($courtcovcenter);
   $presscov=addslashes($presscov);
   $fouls=addslashes($fouls);
   $violations=addslashes($violations);
   $judgment=addslashes($judgment);
   $communication=addslashes($communication);
   $recommendations=addslashes($recommendations);
   $comments=addslashes($comments);
   if($level=="other" || ($level!="frosh" && $level!="jv" && $level!="var" && $levelspec!=""))
   {
      $level=$levelspec;
   }
   $dateeval=time();

   $sql="SELECT * FROM $dbname.bbobserve WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND gameid='$gameid'";
   if($postseasongame==1) $sql.=" AND postseasongame='1'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO bbobserve (obsid,offid,offid2,offid3,gameid,home,visitor,level,poe1,poe2,poe3,poe4,poe5,mgmt1,mgmt2,appearance1,appearance2,appearance3,pregame1,pregame2,pregame3,pregame4,courtcovlead,courtcovtrail,courtcovcenter,presscov,fouls,violations,sigmech1,sigmech2,sigmech3,sigmech4,sigmech5,sigmech6,sigmech7,sigmech8,judgment,communication,recommendations,comments,postseason,postlevel,postgender,crew";
      if($postseasongame==1) $sql2.=",postseasongame";
      $sql2.=") VALUES ('$obsid','$offid','$offid2','$offid3','$gameid','$home','$visitor','$level','$poe1','$poe2','$poe3','$poe4','$poe5','$mgmt1','$mgmt2','$appearance1','$appearance2','$appearance3','$pregame1','$pregame2','$pregame3','$pregame4','$courtcovlead','$courtcovtrail','$courtcovcenter','$presscov','$fouls','$violations','$sigmech1','$sigmech2','$sigmech3','$sigmech4','$sigmech5','$sigmech6','$sigmech7','$sigmech8','$judgment','$communication','$recommendations','$comments','$postseason','$postlevel','$postgender','$crew'";
      if($postseasongame==1) $sql2.=",'1'"; 
      $sql2.=")";
   }
   else
   {
      $sql2="UPDATE bbobserve SET offid2='$offid2',offid3='$offid3',home='$home',visitor='$visitor',level='$level',poe1='$poe1',poe2='$poe2',poe3='$poe3',poe4='$poe4',poe5='$poe5',mgmt1='$mgmt1',mgmt2='$mgmt2',appearance1='$appearance1',appearance2='$appearance2',appearance3='$appearance3',pregame1='$pregame1',pregame2='$pregame2',pregame3='$pregame3',pregame4='$pregame4',courtcovlead='$courtcovlead',courtcovtrail='$courtcovtrail',courtcovcenter='$courtcovcenter',presscov='$presscov',fouls='$fouls',violations='$violations',sigmech1='$sigmech1',sigmech2='$sigmech2',sigmech3='$sigmech3',sigmech4='$sigmech4',sigmech5='$sigmech5',sigmech6='$sigmech6',sigmech7='$sigmech7',sigmech8='$sigmech8',judgment='$judgment',communication='$communication',recommendations='$recommendations',comments='$comments',postseason='$postseason',postlevel='$postlevel',postgender='$postgender',crew='$crew' WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND gameid='$gameid'";
      if($postseasongame==1) $sql2.=" AND postseasongame='1'";
   }
   $result2=mysql_query($sql2);
   if(mysql_error()) echo "<div class='error'>ERROR: $sql2<br>".mysql_error()."</div>";

   //if "Saved " do NOT put dateeval in, but if Submitted, do
   if($save=="Submit Evaluation")
   {
      $sql2="UPDATE bbobserve SET dateeval='$dateeval' WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND gameid='$gameid'";
      if($postseasongame==1) $sql2.=" AND postseasongame='1'";
      $result2=mysql_query($sql2);

      $From="nsaa@nsaahome.org";
      $FromName="NSAA";
      $Subject="An NSAA Official's Evaluation has been submitted for you";
      $Text="A Nebraska School Activities Association Basketball Official's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
      $Html="A Nebraska School Activities Association Basketball Official's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!"; 
      $Attm=array();
      if($offid!='3427')	//test official
      {
         $sql2="SELECT first,last,email FROM $dbname.officials WHERE id='$offid'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if($row2[email]!="")      //e-mail provided
         {
            $To=$row2[email]; $ToName="$row2[first] $row2[last]";
	    SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
         }
         $sql2="SELECT first,last,email FROM $dbname.officials WHERE id='$offid2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if($row2[email]!="")      //e-mail provided
         {    
            $To=$row2[email]; $ToName="$row2[first] $row2[last]";
            SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
         }
         $sql2="SELECT first,last,email FROM $dbname.officials WHERE id='$offid3'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if($row2[email]!="")      //e-mail provided
         {  
            $To=$row2[email]; $ToName="$row2[first] $row2[last]";
            SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
         }
      }
      else
         SendMail($From,$FromName,'run7soccer@aim.com','Ann Gaffigan',$Subject,$Text,$Html,$Attm);
   }
}

//get answers if already submitted
$sql="SELECT * FROM $dbname.bbobserve WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND gameid='$gameid'";
if($postseasongame==1) $sql.=" AND postseasongame='1'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$id=$row[id];
if(mysql_num_rows($result)>0 && $row[dateeval]!='') 
{
   $submitted=1; $saved=0;
}
else if(mysql_num_rows($result)>0)
{
   $submitted=0; $saved=1;
}
else 
{
   $submitted=0; $saved=0;
}
$home=$row[home]; $visitor=$row[visitor];
$level=$row[level];
$appearance1=$row[appearance1]; $appearance2=$row[appearance2]; $appearance3=$row[appearance3];
$poe1=$row[poe1]; $poe2=$row[poe2]; $poe3=$row[poe3]; $poe4=$row[poe4]; $poe5=$row[poe5];
$mgmt1=$row[mgmt1]; $mgmt2=$row[mgmt2];
$pregame1=$row[pregame1]; $pregame2=$row[pregame2]; $pregame3=$row[pregame3]; $pregame4=$row[pregame4];
$courtcovlead=$row[courtcovlead]; $courtcovtrail=$row[courtcovtrail];
$courtcovcenter=$row[courtcovcenter];
$presscov=$row[presscov]; $fouls=$row[fouls]; $violations=$row[violations];
$sigmech1=$row[sigmech1]; $sigmech2=$row[sigmech2]; $sigmech3=$row[sigmech3]; $sigmech4=$row[sigmech4];
$sigmech5=$row[sigmech5]; $sigmech6=$row[sigmech6]; $sigmech7=$row[sigmech7]; $sigmech8=$row[sigmech8];
$judgment=$row[judgment]; $recommendations=$row[recommendations];
$communication=$row[communication];
$comments=$row[comments];
$postseason=$row[postseason]; $postlevel=$row[postlevel];
$postgender=$row[postgender];
if(!$crew || $crew=="") $crew=$row[crew];
if(!$crew || $crew=="")
{
   if($row[offid2]>0 && $row[offid3]>0) $crew='3';
   else if($row[offid2]>0) $crew='2';
   else $crew="";
}
$dateeval=date("F d, Y",$row[dateeval]);

echo $init_html;
echo "<table width=100%><tr align=center><td>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "<br>";

echo "<form method=post action=\"bbobserve.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=offid value=$offid>";
echo "<input type=hidden name=gameid value=$gameid>";
echo "<input type=hidden name=obsid value=$obsid>";
echo "<input type=hidden name=postseasongame value=\"$postseasongame\">";

if($save=="Save & Keep Editing" && $print!=1)
   echo "<br><div class='normalwhite' style='width:500px'><font style=\"color:blue\"><b>Your evaluation has been saved.</b>  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br><br>You must click \"Submit Evaluation\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</font></div><br><br>";
else if($save=="Submit Evaluation" && $print!=1)
   echo "<font style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></font><br><br>";
echo "<table><caption><b>NSAA Basketball Official Evaluation Form:</b><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " $dateeval";
echo ")<hr>";
if(GetLevel($session)==1 && $gameid && $gameid!='new')
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=bb&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//if gameid='new', allow (NSAA) user to choose game and fill out eval
if($gameid=='new' || $save=="Go")
{
   echo "<tr align=center><td><select name=gameid><option value='new'>Choose Game</option>";
   $sql="SELECT * FROM $dbname.bbsched WHERE offid='$offid' ORDER BY offdate";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($gameid==$row[id] && $postseasongame!=1) echo " selected";
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
   //get post season games
   $sql="SELECT t2.id,t2.day,t2.time,t2.gender,t3.type,t3.class,t3.district FROM $dbname.bbcontracts AS t1,bbdisttimes AS t2,bbdistricts AS t3 WHERE t1.offid='$offid' AND t1.disttimesid=t2.id AND t2.distid=t3.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ORDER BY t3.class,t3.district,t3.type DESC";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]-\"";
      if($gameid==$row[id] && $postseasongame==1) echo " selected";
      echo ">$row[type]";
      if($row[type]!='State') echo " $row[class]-$row[district]";
      if($row[gender]!='') echo "  ($row[gender])";
      $date=split("-",$row[day]); $offdate="$date[1]/$date[2]";
      if($row[type]!='State') echo " on $offdate";
      if($row[type]!='State' && $row[time]!='') echo " @ $row[time]";
      echo "</option>";
   }
   echo "</select>&nbsp;<input type=submit name=save value=\"Go\"></td></tr>";
}

//array of answer options
$ans=array("Satisfactory","Needs Improvement");

if($gameid && $gameid!="new" || $print==1)
{

echo "<tr align=center><td><table>";

//show main information about game:
if($print!=1)
{
   echo "<tr align=left valign=top><td><b>Crew of Officials:</b></td>";
   echo "<td><font style=\"font-size:9pt\">I observed a: ";
   if($submitted==1)
   {
      if($crew==2) echo "2-Person Crew";
      else if($crew==3) echo "3-Person Crew";
      else echo "1-Person Crew (OR: I am evaluating only one official that I observed)";
      echo "</td></tr>";
   }
   else
   {
   echo "<input type=radio onclick=\"submit();\" name=crew value='2'";
   if($crew=='2' || $crew=="" || $crew=='0') echo " checked";
   echo ">2-Person Crew&nbsp;&nbsp;";
   echo "<input type=radio onclick=\"submit();\" name=crew value='3'";
   if($crew=='3') echo " checked";
   echo ">3-Person Crew</font></td></tr>";
   }

   //get officials entered, if any
   $sql="SELECT offid,offid2,offid3 FROM $dbname.bbobserve WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' or offid3='$offid') AND gameid='$gameid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $offid1=$row[offid]; $offid2=$row[offid2]; $offid3=$row[offid3];

   if($submitted==1)
   {
      echo "<tr align=left valign=top><td>&nbsp;</td><td><b><u>Officials:</u></b><br>";
      echo "1) ".GetOffName($offid1)."<br>";
      if($offid2>0) echo "2) ".GetOffName($offid2)."<br>"; 
      if($offid3>0) echo "3) ".GetOffName($offid3)."<br>";
      echo "</td></tr>";
   }
   else 
   {
   $sql="SELECT first,last FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left valign=top><td>&nbsp;</td><td><font style=\"font-size:9pt\"><u>Officials' Names:</u><br>1) $row[first] $row[last]<br>";
   if($postseasongame==1)
   {
      $sql="SELECT DISTINCT t1.id,t1.first,t1.last FROM $dbname.officials AS t1, $dbname.bboff AS t2, $dbname.$contracts AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t2.payment!='' AND t1.id!='$offid' ORDER BY t1.last,t1.first";
	$note="If you cannot find an official in this list, he or she is not listed as officiating the NSAA Basketball Tournament.";
   }
   else
   {
      $sql="SELECT offdate FROM bbsched WHERE id='$gameid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sql="SELECT DISTINCT t1.id,t1.first,t1.last FROM $dbname.officials AS t1, $dbname.bbsched AS t3 WHERE t1.id=t3.offid AND t3.offdate='$row[0]' AND t1.id!='$offid' ORDER BY t1.last,t1.first"; 
	$note="If you cannot find an official in this list, he or she has not listed a game on this date on his or her schedule.";
   }
   $result=mysql_query($sql); 
   $offs=array(); $ix=0;
   echo "2) <select name=\"offid2\"><option value='0'>Select Official</option>";
   while($row=mysql_fetch_array($result))
   {
      $offs[id][$ix]=$row[id];
      $offs[name][$ix]="$row[last], $row[first]";
      echo "<option value=\"".$offs[id][$ix]."\"";
      if($offid2==$offs[id][$ix]) echo " selected";
      echo ">".$offs[name][$ix]."</option>";
      $ix++;
   }
   echo "</select><br>";
   if($crew=='3')
   {
      echo "3) <select name=\"offid3\"><option value='0'>Select Official</option>";
      for($i=0;$i<count($offs[id]);$i++)
      {
         echo "<option value=\"".$offs[id][$i]."\"";
         if($offid3==$offs[id][$i]) echo " selected";
         echo ">".$offs[name][$i]."</option>";
      }
      echo "</select>";
   }
   if($note) echo "<br><i>$note</i>";
   echo "</font></td></tr>";
   }
   //get date of game
   if($postseasongame==1)
   {
     echo "<tr align=left><td><b>Date Evaluated:</b></td>";
      $sql="SELECT day,time,gender FROM $dbname.$disttimes WHERE id='$gameid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $temp=split("-",$row[0]); $gender="Boys";
      echo "<td>$temp[1]/$temp[2]/$temp[0] ";
      if($row[time]!="" && $row[time]!='standby') echo "@ $row[time]";
      echo "</td></tr>";
      $sql="SELECT t1.* FROM $dbname.$districts AS t1, $dbname.$disttimes AS t2 WHERE t2.distid=t1.id AND t2.id='$gameid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<td><b>(Sub)District/State:</b></td>";
      echo "<td>$row[type]";
      if($row[type]!='State') echo " $row[class]-$row[district]";
      if($gender!='') echo " ($gender)";
      echo "</td></tr>";
   }
   else
   {   
      $sql="SELECT offdate,schools FROM $dbname.bbsched WHERE id='$gameid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=left><td colspan=2><div style=\"width:650px;\" class=error><b>GAME #$gameid NOT FOUND.</b><br><br>";
	 echo "The official may have changed their schedule in such a way that Game #$gameid is no longer in the system.  Please select the game you are reporting on from the list below.  If you do not see the game, please contact the official and ask him or her to enter it into their schedule.</div></td></tr>";
	 echo "<tr align=left><td><b>Select Game:</b></td><td><select name=\"newgameid\" onchange=\"submit();\">";
	 $sql="SELECT * FROM $dbname.bbsched WHERE offid='$offid' ORDER BY offdate";
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
         echo "<tr align=left><td><b>Date Evaluated:</b></td>";
         $temp=split("-",$row[0]);
         $offdate="$temp[1]/$temp[2]/$temp[0]";
         echo "<td>$offdate</td></tr>";
         echo "<tr align=left><td><b>Schools:</b></td>";
         echo "<td>$row[schools]</td>";
      }
   }
}
else
{
   echo "<tr align=left><td><b>Name of Official:</b></td>";
   echo "<td><input type=text name=official size=30></td></tr>";
   echo "<tr align=left><td><b>Date Evaluated:</b></td>";
   echo "<td><input type=text name=dateeval size=30></td></tr>";
}
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
if($postseasongame!=1)
{
echo "<tr align=left><td><b>Level:</b></td>";
if($submitted==1)
{
   echo "<td>";
   if($level!="" && $level!="frosh" && $level!="jv" && $level!="var")	//other
      echo "OTHER: $level";
   else echo strtoupper($level);
   echo "</td></tr>";
}
else
{
   echo "<td><input type=radio name=level value='frosh'";
   if($level=="frosh") echo " checked";
   echo ">Frosh&nbsp;";
   echo "<input type=radio name=level value='jv'";
   if($level=="jv") echo " checked";
   echo ">JV&nbsp;";
   echo "<input type=radio name=level value='var'";
   if($level=="var") echo " checked";
   echo ">Varsity&nbsp;";
   echo "<input type=radio name=other value='other'";
   if($level!="" && $level!="frosh" && $level!="jv" && $level!="var") 
   {
      echo " checked";
      $levelspec=$level;
   }
   echo ">Other (specify) <input type=text name=levelspec class=tiny size=10 value=\"$levelspec\"></td></tr>";
}
}//end if not post season game
echo "</table></td></tr>";

//evaluation questions:
echo "<tr align=center><td><table width=700>";
//Appearance
$appques=array("Uniforms","Physical Appearance","Professionalism");
echo "<tr align=left><th colspan=3 align=left class=smaller>OVERALL APPEARANCE OF OFFICIALS:</th></tr>";
for($x=0;$x<count($appques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=350><b>$i.&nbsp;$appques[$x]</b></td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="appearance".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td width=175><input type=radio name=\"appearance".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}
//Pre-Game
$preques=array("Arrival on Court-(15 minutes minimum)","Meet Coaches/Captains","Check book in advance of 10 minutes","Maintain observational positioning");
echo "<tr align=left><td colspan=3><b><br>PRE-GAME DUTIES:</td></tr>";
for($x=0;$x<count($preques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=350><b>$i.&nbsp;$preques[$x]</b></td>";
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
         echo "<td width=175><input type=radio name=\"pregame".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Court Coverage as Lead Official
echo "<tr align=left><td colspan=3><b>Court Coverage as Lead Official:</b><br>";
if($submitted==1)
   echo "$courtcovlead</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=courtcovlead>$courtcovlead</textarea></td></tr>";

//Court Coverage as Trail Official
echo "<tr align=left><td colspan=3><b>Court Coverage as Trail Official:</b><br>";
if($submitted==1)
   echo "$courtcovtrail</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=courtcovtrail>$courtcovtrail</textarea></td></tr>";

//Court Coverage as Center Official
echo "<tr align=left><td colspan=3><b>Court Coverage as Center Official:</b><br>";
if($submitted==1)
   echo "$courtcovcenter</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=courtcovcenter>$courtcovcenter</textarea></td></tr>";

//Press Coverage:
echo "<tr align=left><td colspan=3><b>Press Coverage:</b><br>";
if($submitted==1)
   echo "$presscov</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=presscov>$presscov</textarea></td></tr>";

//Fouls:
echo "<tr align=left><td colspan=3><b>Fouls:</b><br>";
if($submitted==1)
   echo "$fouls</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=fouls>$fouls</textarea></td></tr>";

//Violations:
echo "<tr align=left><td colspan=3><b>Violations:</b><br>";
if($submitted==1)
   echo "$violations</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=violations>$violations</textarea></td></tr>";

//Signals and Mechanics
$smques=array("Preliminary signal given","Fist up high on foul call","Use of only approved signals","Stop when reporting to table","Visible counts","Position during timeouts and quarters","Free throw mechanics","Switch on fouls");
echo "<tr align=left><td colspan=3><b><br>SIGNALS AND MECHANICS:</b></td></tr>";
for($x=0;$x<count($smques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td><b>$i.&nbsp;$smques[$x]</b></td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="sigmech".$i;
      if($submitted==1)
      {
	 if($$temp==$ans[$j])
	    echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td><input type=radio name=\"sigmech".$i."\" value=\"$ans[$j]\"";
         if($$temp===$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Points of Emphasis
$poeques=array("Rules Enforcement","Sporting Behavior","Perimeter Play","Closely Guarded Situations","Principle of Verticality");
echo "<tr align=left><th colspan=3 align=left class=smaller><br>POINTS OF EMPHASIS:</th></tr>";
for($x=0;$x<count($poeques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=350><b>$i.&nbsp;$poeques[$x]</b></td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="poe".$i;
      if($submitted==1)
      {
         if($$temp==$ans[$j])
            echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td width=175><input type=radio name=\"poe".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Management
$mgmtques=array("Possession Arrow","End of Game");
echo "<tr align=left><th colspan=3 align=left class=smaller><br>MANAGEMENT:</th></tr>";
for($x=0;$x<count($mgmtques);$x++)
{
   $i=$x+1;
   echo "<tr align=left valign=top><td width=350><b>$i.&nbsp;$mgmtques[$x]</b></td>";
   for($j=0;$j<count($ans);$j++)
   {
      $temp="mgmt".$i;
      if($submitted==1)
      {
         if($$temp==$ans[$j])
            echo "<td colspan=2>$ans[$j]</td>";
      }
      else
      {
         echo "<td width=175><input type=radio name=\"mgmt".$i."\" value=\"$ans[$j]\"";
         if($$temp==$ans[$j]) echo " checked";
         echo ">$ans[$j]&nbsp;</td>";
      }
   }
   echo "</tr>";
}

//Game Management & Communication
echo "<tr align=left><td colspan=3><br><b>Game Management/Communication:</b><br>";
if($submitted==1)
{
   $communication=ereg_replace("\r\n","<br>",$communication);
   echo "$communication</td></tr>";
}
else
   echo "<textarea rows=15 cols=90 name=communication>$communication</textarea></td></tr>";

//Recommendations for Improvement
echo "<tr align=left><td colspan=3><br><b>Recommendations for Improvement:</b><br>";
if($submitted==1)
   echo "$recommendations</td></tr>";
else
   echo "<textarea rows=3 cols=70 name=recommendations>$recommendations</textarea></td></tr>";

if(GetLevel($session)!=2)       //if not an official, show comments for NSAA
{
   echo "<tr align=left><td colspan=3><br><b>Comments for NSAA only:</b><br>";
   if($submitted==1)
      echo "$comments</td></tr>";
   else
      echo "<textarea rows=5 cols=70 name=comments>$comments</textarea></td></tr>";

   //Post Season
   if($postseasongame!=1)
   {
   echo "<tr align=left><td><b>Recommendations for Post Season Assignments:</td>";
   if($submitted==1)
   {
      echo "<td>".strtoupper($postseason)."</td></tr>";
   }
   else
   {
      echo "<td colspan=2><input type=radio name=postseason value='yes'";
      if($postseason=='yes') echo " checked";
         echo ">Yes&nbsp;&nbsp;";
      echo "<input type=radio name=postseason value='no'";
      if($postseason=='no') echo " checked";
         echo ">No</td></tr>";
   }
   echo "<tr align=left valign=top><td><b>If yes, at what level?</td>";
   echo "<td colspan=2>";
   if($submitted==1)
      echo $postlevel." (".$postgender.")";
   else
   {
      $classch=array("A","B","C1","C2","D1","D2","All Classes");
      for($i=0;$i<count($classch);$i++)
      {
         echo "<input type=radio name=postlevel value=\"$classch[$i]\"";
 	 if($postlevel==$classch[$i]) echo " checked";
	    echo ">$classch[$i]&nbsp;";
      }
      echo "<br><input type=radio name=postgender value=\"Boys Only\"";
      if($postgender=="Boys Only") echo " checked";
      echo ">Boys ONLY&nbsp;&nbsp;<input type=radio name=postgender value=\"Girls Only\"";
      if($postgender=="Girls Only") echo " checked";
      echo ">Girls ONLY&nbsp;&nbsp;<input type=radio name=postgender value=\"Boys & Girls\"";
      if($postgender=="Boys & Girls") echo " checked";
      echo ">BOTH";
   }
   echo "</td></tr>";
   }//end if not postseason game
}

if($submitted!=1 && $print!=1)
{
   echo "<tr align=center><td colspan=3><br>";
   echo "<font style=\"color:blue\"><b>NOTE:</b> You may click \"Save & Keep Editing\" if you want to save your work and continue later.<br>Your evaluation will NOT be sent to the NSAA until you click \"Submit Evaluation\" below.</font><br>";
   echo "<input type=submit name=save value=\"Save & Keep Editing\"></td></tr>";
   echo "<tr align=center><td colspan=3><br>";
   echo "<font style=\"color:blue\"><b>NOTE: </b>Once you click \"Submit Evaluation\", your submission of this
evaluation is final.<br>YOU MUST CLICK \"Submit Evaluation\" FOR THE EVALUATION TO BE SENT!!!<br>You will be able to view your submitted evaluations, but you will NOT be able to edit them.";
   echo "<br><input type=submit name=save value=\"Submit Evaluation\"></td></tr>";
}

echo "</table>";

echo "</td></tr></table>";
if($print!=1) echo "<a class=small href=\"javascript:window.close();\">Close Window</a>";
}//end if gameid given

echo $end_html;

?>
