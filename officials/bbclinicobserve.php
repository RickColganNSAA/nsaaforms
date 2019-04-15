<?php
//STOP 6/18: STILL NEED TO TEST AND ALSO BE SURE OUTPUT IN REPORTS IS OK WHEN NO offid GIVEN
/*********************************
bbclinicobserve.php
Observation form for BB Officials at Clinic
Created 6/18/13
Author: Ann Gaffigan
**********************************/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$dbname || $dbname=="") $dbname=$db_name2;
//echo "$dbname, $obsid, $offid, $gameid";
$years=preg_replace("/[^0-9]/","",$dbname);
if($years!='')
{
   $years=substr($years,0,4)."-".substr($years,4,4);
}
else	//CURRENT SCHOOL YEAR
{
   $year1=date("Y");
   if(date("m")<6) $year1--;
   $year2=$year1+1;
   $years="$year1-$year2";
}

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

if($save && $save!="Go")	//put eval in db and show user what he/she entered
{
   $location=addslashes($location);
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
   $clinicdate="$yr-$mo-$day";
   $dateeval=time();
   $official=addslashes($official);
   $official2=addslashes($official2);
   $official3=addslashes($official3);
   if(!$offid) $offid=3427;

   if($id>0)
      $sql="SELECT * FROM $dbname.bbclinicobserve WHERE id='$id' AND obsid='$obsid'";
   else if($offid>0)
      $sql="SELECT * FROM $dbname.bbclinicobserve WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' OR offid3='$offid')";
   else
      $sql="";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO bbclinicobserve (obsid,offid,offid2,offid3,official,official2,official3,location,clinicdate,poe1,poe2,poe3,poe4,poe5,mgmt1,mgmt2,appearance1,appearance2,appearance3,pregame1,pregame2,pregame3,pregame4,courtcovlead,courtcovtrail,courtcovcenter,presscov,fouls,violations,sigmech1,sigmech2,sigmech3,sigmech4,sigmech5,sigmech6,sigmech7,sigmech8,judgment,communication,recommendations,comments,postseason,postlevel,postgender,crew";
      $sql2.=") VALUES ('$obsid','$offid','$offid2','$offid3','$official','$official2','$official3','$location','$clinicdate','$poe1','$poe2','$poe3','$poe4','$poe5','$mgmt1','$mgmt2','$appearance1','$appearance2','$appearance3','$pregame1','$pregame2','$pregame3','$pregame4','$courtcovlead','$courtcovtrail','$courtcovcenter','$presscov','$fouls','$violations','$sigmech1','$sigmech2','$sigmech3','$sigmech4','$sigmech5','$sigmech6','$sigmech7','$sigmech8','$judgment','$communication','$recommendations','$comments','$postseason','$postlevel','$postgender','$crew'";
      $sql2.=")";
   }
   else
   {
      $row=mysql_fetch_array($result);
      $id=$row[id];
      $sql2="UPDATE bbclinicobserve SET offid='$offid',offid2='$offid2',offid3='$offid3',official='$official',official2='$official2',official3='$official3',location='$location',clinicdate='$clinicdate',poe1='$poe1',poe2='$poe2',poe3='$poe3',poe4='$poe4',poe5='$poe5',mgmt1='$mgmt1',mgmt2='$mgmt2',appearance1='$appearance1',appearance2='$appearance2',appearance3='$appearance3',pregame1='$pregame1',pregame2='$pregame2',pregame3='$pregame3',pregame4='$pregame4',courtcovlead='$courtcovlead',courtcovtrail='$courtcovtrail',courtcovcenter='$courtcovcenter',presscov='$presscov',fouls='$fouls',violations='$violations',sigmech1='$sigmech1',sigmech2='$sigmech2',sigmech3='$sigmech3',sigmech4='$sigmech4',sigmech5='$sigmech5',sigmech6='$sigmech6',sigmech7='$sigmech7',sigmech8='$sigmech8',judgment='$judgment',communication='$communication',recommendations='$recommendations',comments='$comments',postseason='$postseason',postlevel='$postlevel',postgender='$postgender',crew='$crew' WHERE id='$id'";
   }
   $result2=mysql_query($sql2);
   if(!$id) $id=mysql_insert_id();
   if(mysql_error()) echo "<div class='error'>ERROR: $sql2<br>".mysql_error()."</div>";

   //if "Saved " do NOT put dateeval in, but if Submitted, do
   if($save=="Submit Evaluation")
   {
      $sql2="UPDATE bbclinicobserve SET dateeval='$dateeval' WHERE id='$id'";
      $result2=mysql_query($sql2);

      $From="nsaa@nsaahome.org";
      $FromName="NSAA";
      $Subject="An NSAA Official's Evaluation has been submitted for you";
      $Text="A Nebraska School Activities Association Basketball Official's Evaluation has been filled out in your name.  Please login at https://secure.nsaahome.org/nsaaforms/officials/ to view your evaluation.\r\n\r\nThank You!";
      $Html="A Nebraska School Activities Association Basketball Official's Evaluation has been filled out in your name.  Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/\">https://secure.nsaahome.org/nsaaforms/officials/</a> to view your evaluation.<br><br>Thank You!"; 
      $Attm=array();
      if($offid!='3427' && $obsid!='22')	//test official/observer
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
   }
}

//get answers if already submitted
if($id>0)
   $sql="SELECT * FROM $dbname.bbclinicobserve WHERE id='$id' AND obsid='$obsid'";
else if($offid>0)
   $sql="SELECT * FROM $dbname.bbclinicobserve WHERE obsid='$obsid' AND (offid='$offid' OR offid2='$offid' OR offid3='$offid')";
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
$location=$row[location]; $clinicdate=$row[clinicdate];
$cdate=explode("-",$clinicdate);
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
   if(($row[offid2]>0 || $row[official2]!='') && ($row[offid3]>0 || $row[official3]!='')) $crew='3';
   else if($row[offid2]>0 || $row[official2]!='') $crew='2';
   else $crew="";
}
$dateeval=date("F d, Y",$row[dateeval]);

echo $init_html;
echo "<table width=100%><tr align=center><td><p>";
echo "<a class=small href=\"javascript:window.close();\">Close Window</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"javascript:window.print();\">Print</a>";
echo "</p>";

echo "<form method=post action=\"bbclinicobserve.php\">";
echo "<input type=hidden name=session value='$session'>";
echo "<input type=hidden name=offid value='$offid'>";
echo "<input type=hidden name=obsid value='$obsid'>";
echo "<input type=hidden name='id' value='$id'>";

if($save=="Save & Keep Editing" && $print!=1)
   echo "<br><div class='normalwhite' style='width:500px'><font style=\"color:blue\"><b>Your evaluation has been saved.</b>  You may return and continue working on this evaluation at a later time.  There will be a link to this evaluation on your screen when you login.<br><br>You must click \"Submit Evaluation\" at the bottom of this screen in order for your evaluation to be sent to the NSAA.  When you do so, you will no longer be able to edit your evaluation.  You will only be able to view what you have submitted.</font></div><br><br>";
else if($save=="Submit Evaluation" && $print!=1)
   echo "<p style=\"color:blue\"><b>Your evaluation has been submitted to the NSAA.  Thank You!</b></p><br>";
echo "<table><caption><b>NSAA Basketball CLINIC Official Evaluation Form:</b><br>";
echo "(Evaluated by $obsname";
if($submitted==1)
   echo " $dateeval";
echo ")<hr>";
if(GetLevel($session)==1)
   echo "<a href=\"deleteobserve.php?session=$session&dbname=$dbname&sport=bbclinic&id=$id\" onClick=\"return confirm('Are you sure you want to delete this observation?  This action cannot be undone.');\">Delete this Observation</a>";
echo "</caption>";

//array of answer options
$ans=array("Satisfactory","Needs Improvement");

echo "<tr align=center><td><table class='nine'>";

//show main information about game:
if($print!=1)
{
   echo "<tr align=left><td><b>Crew of Officials:</b></td>";
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
   $sql="SELECT * FROM $dbname.bbclinicobserve WHERE obsid='$obsid'";
   if($id>0) $sql.=" AND id='$id'";
   else if($offid>0) $sql.=" AND (offid='$offid' OR offid2='$offid' or offid3='$offid')";
   else $sql="";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
//echo $sql;
   $offid1=$row[offid]; $offid2=$row[offid2]; $offid3=$row[offid3];
   $official=$row[official]; $official2=$row[official2]; $official3=$row[official3];

   if($submitted==1)
   {
      echo "<tr align=left valign=top><td><b>Officials:</b></td><td><ol>";
      if($offid1>0 && ($offid!=3427 || trim($official)=='')) echo "<li>".GetOffName($offid1)."</li>";
      else echo "<li>".$official."</li>";
      if($offid2>0) echo "<li>".GetOffName($offid2)."</li>"; 
      else if(trim($official2)!='') echo "<li>$official2</li>";
      if($offid3>0) echo "<li>".GetOffName($offid3)."</li>";
      else if(trim($official3)!='') echo "<li>$official3</li>";
      echo "</ol>";
      echo "</td></tr>";
      echo "<tr align=left><td><b>Clinic Date:</b></td><td>$cdate[1]/$cdate[2]/$cdate[0]</td></tr>";
   }
   else 	//NOT SUBMITTED
   {
   $sql="SELECT first,last FROM $dbname.officials WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left valign=top><td>&nbsp;</td><td><p>Officials' Names:</p><ol>";
   $sql="SELECT DISTINCT t1.id,t1.first,t1.last FROM $dbname.officials AS t1, $dbname.bboff AS t3 WHERE t1.id=t3.offid ORDER BY t1.last,t1.first"; 
   $result=mysql_query($sql); 
   $offs=array(); $ix=0;
   echo "<li><select name=\"offid\"><option value='0'>Select Official</option>";
   while($row=mysql_fetch_array($result))
   {
      $offs[id][$ix]=$row[id];
      $offs[name][$ix]="$row[last], $row[first]";
      echo "<option value=\"".$offs[id][$ix]."\"";
      if($offid1==$offs[id][$ix]) echo " selected";
      echo ">".$offs[name][$ix]."</option>";
      $ix++;
   }
   echo "</select> <i>Can't find who you're looking for? Type their name here: <input type=text name=\"official\" value=\"$official\"></li>";
   echo "<li><select name=\"offid2\"><option value='0'>Select Official</option>";
   for($i=0;$i<count($offs[id]);$i++)
   {
      echo "<option value=\"".$offs[id][$i]."\"";
      if($offid2==$offs[id][$i]) echo " selected";
      echo ">".$offs[name][$i]."</option>";
   }
   echo "</select> <i>Can't find who you're looking for? Type their name here: <input type=text name=\"official2\" value=\"$official2\"></li>";
   if($crew=='3')
   {
      echo "<li><select name=\"offid3\"><option value='0'>Select Official</option>";
      for($i=0;$i<count($offs[id]);$i++)
      {
         echo "<option value=\"".$offs[id][$i]."\"";
         if($offid3==$offs[id][$i]) echo " selected";
         echo ">".$offs[name][$i]."</option>";
      }
      echo "</select> <i>Can't find who you're looking for? Type their name here: <input type=text name=\"official3\" value=\"$official3\"></li>";
   }
   echo "</ol>";
   if($note) echo "<p><i>$note</i></p>";
   echo "</td></tr>";
   echo "<tr align=left><td><b>Clinic Date:</b></td><td><select name=\"mo\"><option value=\"00\">MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option value=\"$m\"";
      if($cdate[1]==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"day\"><option value=\"00\">DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option value=\"$d\"";
      if($cdate[2]==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"yr\">";
   $year1=date("Y")-1; $year2=date("Y")+1;
   for($i=$year1;$i<=$year2;$i++)
   {
      echo "<option value=\"$i\"";
      if($cdate[0]==$i || (!$cdate[0] && $i==date("Y"))) echo " selected";
      echo ">$i</option>";
   }
   echo "</select></td></tr>";
   }	//END IF NOT SUBMITTED
}	//END IF NOT PRINT
else
{
   echo "<tr align=left><td><b>Name of Official:</b></td>";
   echo "<td><input type=text name=official size=30></td></tr>";
   echo "<tr align=left><td><b>Clinic Date:</b></td>";
   echo "<td><input type=text name=dateeval size=30></td></tr>";
}
echo "<tr align=left><td><b>Clinic Location:</b></td>";
if($submitted==1)
   echo "<td>$location</td></tr>";
else
   echo "<td><input type=text class=tiny size=30 name=location value=\"$location\"></td></tr>";
echo "</table></td></tr>";

//evaluation questions:
echo "<tr align=center><td><table style=\"width:700px;\" cellspacing=0 cellpadding=3 class='nine'>";
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
   echo "<td colspan=2><p>";
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
      echo "</p><p><input type=radio name=postgender value=\"Boys Only\"";
      if($postgender=="Boys Only") echo " checked";
      echo ">Boys ONLY&nbsp;&nbsp;<input type=radio name=postgender value=\"Girls Only\"";
      if($postgender=="Girls Only") echo " checked";
      echo ">Girls ONLY&nbsp;&nbsp;<input type=radio name=postgender value=\"Boys & Girls\"";
      if($postgender=="Boys & Girls") echo " checked";
      echo ">BOTH";
   }
   echo "</p></td></tr>";
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

echo $end_html;

?>
