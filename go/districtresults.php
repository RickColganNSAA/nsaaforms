<?php
/***************************************
districtresults.php
District Director enters District Results
Top 10 Individuals (+ ties)
Top 3 Teams
...go to State
Created 9/13/12
Author: Ann Gaffigan
****************************************/
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

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
if(!$sport) $sport='go_g';
$sport2=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$districts=$sport."districts";
$indytable=$sport2."distresults_indy";
$teamtable=$sport2."distresults_team";
$schtable=$sport."school";

if($distid && $save)	//COMMIT RESULTS TO DATABASE
{
   $errors="";

   if(!$indyct)
      $errors.="You must enter the number of INDIVIDUALS who competed in this tournament.<br>";
   if(!$teamsct)
      $errors.="You must enter the number of TEAMS who competed in this tournament.<br>";

   $sql="UPDATE $db_name2.$districts SET indyct='$indyct',teamct='$teamsct' WHERE id='$distid'";
   $result=mysql_query($sql);

   $sql="DELETE FROM $indytable WHERE distid='$distid'";
   $result=mysql_query($sql);

   $indyct=0;
   for($i=0;$i<count($place);$i++)
   {
      if($indy[$i]>0 && $points[$i]>0 && $place[$i]>0)
      {
	 //MAKE SURE THIS STUDENTID ISN'T ALREADY ENTERED
	 $sql="SELECT * FROM $indytable WHERE distid='$distid' AND studentid='$indy[$i]'";
	 $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
	 {
            $sql="INSERT INTO $indytable (distid,place,tie,sid,studentid,points) VALUES ('$distid','$place[$i]','$tie[$i]','$sch[$i]','$indy[$i]','$points[$i]')";
	    $result=mysql_query($sql);
	    $indyct++;
	 }
      } 
   }

   if($indyct<10)
      $errors.="You must enter the Top 10 Individuals, including their Place and Score. You've only entered $indyct.<br>";

   $sql="DELETE FROM $teamtable WHERE distid='$distid'";
   $result=mysql_query($sql);

   $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sids=explode(",",$row[sids]);
   $teamct=count($sids);

   for($i=0;$i<count($teamplace);$i++)
   {
      if($team[$i]>0 && ($teampoints[$i]>0 || $teamnoscore[$i]=='x'))
      {
         //MAKE SURE THIS SID ISN'T ALREADY ENTERED
         $sql="SELECT * FROM $teamtable WHERE distid='$distid' AND sid='$team[$i]'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
            $sql="INSERT INTO $teamtable (distid,place,sid,points,noscore) VALUES ('$distid','$teamplace[$i]','$team[$i]','$teampoints[$i]','$teamnoscore[$i]')";
            $result=mysql_query($sql);
	    $insertid=mysql_insert_id();

	    if($teamplace[$i]<=3)	//INDIVIDUAL SCORES FOR THIS TEAM AS WELL
	    {
	       for($j=0;$j<5;$j++)
	       {
		  $index=$j+1; $studvar="studentid".$index; $pointsvar="points".$index;
		  if($teamindydq[$i][$j]=='x') $teamindypoints[$i][$j]=999;
	          else if($teamindywd[$i][$j]=='x') $teamindypoints[$i][$j]=9999;
		  $sql="UPDATE $teamtable SET $studvar='".$teamindy[$i][$j]."',$pointsvar='".$teamindypoints[$i][$j]."' WHERE id='$insertid'";
	    	  $result=mysql_query($sql); 
		  if($teamindypoints[$i][$j]==0 || $teamindypoints[$i][$j]=="")
	    	     $errors.="You need to either enter a SCORE for each student on a Top-3 Team OR check DQ or WD next to their name.<br>";
	       }
	    }
	 }
      }
   }
   $sql="SELECT * FROM $teamtable WHERE distid='$distid'";
   $result=mysql_query($sql);
   $curteamct=mysql_num_rows($result);

   if($curteamct<$teamct)
      $errors.="You must enter Team Scores for all $teamct teams OR check \"No Team Score\" if a school did not compete. You've only entered a score or checked the box for $curteamct.<br>";

   if($level==1 && $errors=="")
   {
      //UPDATE TIME STAMP ONLY IF THIS IS FIRST ENTRY OF RESULTS
      $sql="SELECT * FROM $db_name2.$districts WHERE id='$distid' AND resultssubmitted=0";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $sql="UPDATE $db_name2.$districts SET resultssubmitted='".time()."' WHERE id='$distid'";
         $result=mysql_query($sql);
      }
      header("Location:districtresults.php?session=$session&sport=$sport&distid=$distid&saved=1");
      exit();
   }
   else if($errors=='')	//UPDATE TIMESTAMP
   {
      $sql="UPDATE $db_name2.$districts SET resultssubmitted='".time()."' WHERE id='$distid'";
      $result=mysql_query($sql);
      header("Location:districtresults.php?session=$session&sport=$sport&distid=$distid");
      exit();
   }
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Team2.js"></script>
</head>
<body onload="Team2.initialize('<?php echo $session; ?>','<?php echo $sport; ?>','sch','indy','20');">
<div id='loading' style='display:none;'></div>
<script language="javascript">
function ReOrder()
{
   var i=0;
   var prevplace=0;
   var curplace=1;
   while(i<20)
   {
      var placefield="place"+ i;
      var tiefield="tie" + i;
      if(curplace>=10 && Utilities.getElement(tiefield).checked)
      {
	 Utilities.getElement(placefield).value=prevplace;
      }
      else
      {
         Utilities.getElement(placefield).value=curplace;
         prevplace=curplace;
      } 
      curplace=curplace+1;
      if(curplace>10) curplace=10;
      i++;
   }
}
</script>
<?php
echo GetHeader($session)."<br>";

//THE FORM
echo "<form method=post action=\"districtresults.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sport\" value=\"$sport\">";
echo "<input type=hidden name=\"distid\" value=\"$distid\">";
echo "<input type=hidden name=\"prevplace\" id=\"prevplace\">";

	//THE DISTRICT
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sids=explode(",",$row[sids]); $hostschool=$row[hostschool]; $site=$row[site];
$date=explode("-",$row[dates]);
$indyct=$row[indyct]; $teamsct=$row[teamct];
$showdate=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
$email=$row[email]; $director=$row[director];
$teamct=count($sids);
if($row[resultssubmitted]>0)
{
   $datesub=$row[resultssubmitted];
}
if($level==1)
   echo "<br><a href=\"distresultsmain.php?session=$session&sport=$sport\">Return to $sportname District Results Main Menu</a><br>";
echo "<br><table cellspacing=0 cellpadding=5><caption><b>".strtoupper("District $row[class]-$row[district] $sportname Results:")."</b><br>";
echo "<p>Hosted by <b>$hostschool</b> at <b>$site</b><br>on <b>$showdate</b><br>Director: <b>$director</b> (<a class=small href=\"mailto:$email\">$email</a>)</p>";
if($errors!='')
   echo "<div class='error' style='width:400px;'>The following errors need to be corrected before these results can be submitted:<br><br>$errors</div><br>";
else if($level==1 && $saved==1)
   echo "<div class='alert' style='width:400px;'>The District Results have been saved below. <a href=\"distresultsmain.php?session=$session&sport=$sport\">Return to Main Menu</a></div><br>";
else if($datesub>0)
   echo "<div class='alert' style='width:600px;margin-top:10px;'><b>These District Results were submitted to the NSAA on ".date("F j, Y",$datesub).".</b> Please contact the NSAA office to make further changes or corrections to these results.</div><br>";
echo "<p style='text-align:left;'>Please enter the <b>Top 10 Individuals, plus ties <u>for 10th place</u></b>, below. List the playoff 10th place medal winner first. Select the School and then select the Student. Then enter the Score.</p><p style='text-align:left;'>Also enter all the competing <b>Team Scores</b> by selecting the School and entering the Score for each. If a school didn't field a team or post at least 4 individual scores, check the box in the \"No Score\" column for that team at the end of the list. For the <b>Top 3 teams</b>, you will also need to select the <b>names and enter the scores</b> for all the players on those teams.</p><p style='text-align:left;'>Don't forget to click <b>\"Submit Results\"</b> below to submit these District Results to the NSAA.</p><br>";
//NUMBER OF INDIVIDUALS AND TEAMS:
if($print==1 || ($level!=1 && $datesub>0))
{
   echo "<p style='text-align:left;'>Number of Individuals Competing: <b>$indyct</b></p>";
   echo "<p style='text-align:left;'>Number of Schools Competing: <b>$teamsct</b></p>";
}
else
{
   echo "<p style='text-align:left;'><b>Please enter the number of Individuals and Schools that competed in this tournament:</b></p>";
   echo "<p style='text-align:left;'>Individuals: <input type=text size=3 name=\"indyct\" id=\"indyct\" value=\"$indyct\"></p>";
   echo "<p style='text-align:left;'>Schools: <input type=text size=3 name=\"teamsct\" id=\"teamsct\" value=\"$teamsct\"></p>";
}
echo "</caption>";
echo "<tr valign='top' align=center>";

//INDIVIDUAL RESULTS
echo "<td><table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>Top 10 Individuals (Plus Ties)</b><p style='text-align:left;'>If there are <b><u>TIES for 10th Place</b></u>, place a checkbox in the Tie column for the players that tied with the playoff winner. The first player listed for 10th place should be the player who won the 10th-place Medal. The rest are players who did not win the playoff but will still qualify for State.</caption>";
echo "<tr align=center><td><b>Place</b></td><td><b>Tie</b></td><td><b>School (Coach)</b></td><td><b>Student</b></td><td><b>Score</b></td></tr>";
	//GET SCHOOLS FOR THIS SPORT
$sql2="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$sids=explode(",",$row2[sids]);
$sql2="SELECT * FROM $schtable WHERE (";
for($i=0;$i<count($sids);$i++)
{
   $sql2.="sid='$sids[$i]' OR ";
}
$sql2=substr($sql2,0,strlen($sql2)-4).") ORDER BY school";
$schs[sid]=array(); $schs[name]=array(); $s=0;
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $schs[sid][$s]=$row2[sid]; $schs[name][$s]=$row2[school];
   $schs[coach][$s]=GetCoaches(0,$sport,$row2[sid]);
   $s++;
}
	//INDY RESULTS ALREADY ENTERED:
$sql2="SELECT t1.* FROM $indytable AS t1, eligibility AS t2 WHERE t1.studentid=t2.id AND t1.distid='$distid' ORDER BY t1.place,t1.tie";
$result2=mysql_query($sql2);
$ix=0; $place=0;  $seen10th=0;
while($row2=mysql_fetch_array($result2))
{
   $cursid=$row2[sid];
   if($print==1 || ($level!=1 && $datesub>0))
   {
      echo "<tr align=center><td>$row2[place]";
      echo "</td>";
      if($row2[tie]=='x') echo "<td>T</td>";
      else echo "<td>&nbsp;</td>";
      echo "<td align=left>".GetSchoolName($cursid,$sport)." (Coach: ".GetCoaches(0,$sport,$cursid).")</td><td align=left>".GetStudentInfo($row2[studentid])."</td><td>$row2[points]</td></tr>";
   }
   else	//EDITING
   { 
      echo "<tr align=center><td><input type=text size=3 maxlength=2 name=\"place[$ix]\" id=\"place".$ix."\" value=\"$row2[place]\">";
      if($row2[place]==10 && $seen10th==0)
      {
         echo "<label style='font-size:15px;'><b>*</b></label></td><td><b>Medal<br>Winner</b></td>"; 
	 $seen10th=1;
      }
      else if($row2[place]>=10 && $seen10th==1)
      {
         echo "</td><td><input type=checkbox name=\"tie[$ix]\" id=\"tie".$ix."\" value='x'";
         if($row2[tie]=='x') echo " checked";
         echo "></td>";
      }
      else
      {
         echo "</td><td>&nbsp;</td>";
      }
      echo "<td><select name=\"sch[$ix]\" id=\"sch".$ix."\" onMouseDown=\"Team2.currentPlace=$ix;\"><option value=''>Select School</option>";
      for($i=0;$i<count($schs[sid]);$i++)
      {
         echo "<option value=\"".$schs[sid][$i]."\"";
         if($cursid==$schs[sid][$i]) echo " selected";
         echo ">".$schs[name][$i]." - Coach: ".$schs[coach][$i]."</option>";
      }
      echo "</select></td><td align=left><select name=\"indy[$ix]\" id=\"indy".$ix."\"><option value='0'>Select Student</option>";
      if($sport=="go_b") //ALLOW BOY OR GIRL TO BE CHOSEN
         $string=GetPlayers($sport,GetMainSchoolName($cursid,$sport),GetFallYear($sport),FALSE,TRUE);
      else
         $string=GetPlayers($sport,GetMainSchoolName($cursid,$sport),$fallyear);
      if(!ereg("Please",$string))
      {
         $results=split("<result>",$string);
         for($i=0;$i<count($results);$i++)
         {
            $details=split("<detail>",$results[$i]);
            echo "<option value=\"$details[0]\"";
            if($row2[studentid]==$details[0]) echo " selected";
            echo ">$details[1]</option>";
         }
      }
      echo "</select></td><td><input type=text size=3 maxlength=4 name=\"points[$ix]\" id=\"points".$ix."\" value=\"$row2[points]\"></td></tr>";
   }
   $ix++; $place=$row2[place]; 
}
$place=$ix+1; 
if($place>10) $place=10;
if(!$print && !($datesub>0 && $level!=1))
{
while($ix<20)
{
   echo "<tr align=center><td><input type=text size=3 maxlength=2 name=\"place[$ix]\" id=\"place".$ix."\" value=\"$place\">";
   if($place==10 && $seen10th==0)
   {
      echo "<label style='font-size:15px;'><b>*</b></label></td><td><b>Medal<br>Winner</b></td>"; 
      $seen10th=1;
   }
   else if($place>=10 && $seen10th==1)
   {
      echo "</td><td><input type=checkbox name=\"tie[$ix]\" id=\"tie".$ix."\" value=\"x\"></td>";
   }
   else
   {
      echo "</td><td>&nbsp;</td>";
   }
   echo "<td><select name=\"sch[$ix]\" id=\"sch".$ix."\" onMouseDown=\"Team2.currentPlace=$ix;\"><option value=''>Select School</option>";
   for($i=0;$i<count($schs[sid]);$i++)
   {
      echo "<option value=\"".$schs[sid][$i]."\">".$schs[name][$i]." - Coach: ".$schs[coach][$i]."</option>";
   }
   echo "</select></td><td><select name=\"indy[$ix]\" id=\"indy".$ix."\"><option value='0'>Select Student</option>";
   echo "</select></td><td><input type=text size=3 maxlength=4 name=\"points[$ix]\" id=\"points".$ix."\" value=\"$row2[points]\"></td></tr>";
   $ix++; $prevplace=$place; 
   if($place<10) $place++; 
}
echo "</table><p style='text-align:left;'><b>* Won scorecard playoff for 10th Place Medal</b></p></td><td>";
}	//END IF EDITABLE
else
   echo "</table></td><td>";

//TEAM RESULTS
echo "<table cellspacing=0 cellpadding=5 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\"><caption><b>Team Scores</b></caption>";
echo "<tr align=center><td><b>Place</b></td><td><b>School</b></td><td><b>Score</b></td><td><b>No Score</b></td></tr>";
	//Team Results Already Entered
$sql2="SELECT t1.* FROM $teamtable AS t1, $schtable AS t2 WHERE t1.sid=t2.sid AND t1.distid='$distid' ORDER BY t1.place,t2.school";
$result2=mysql_query($sql2);
$ix=0; $place=0;
while($row2=mysql_fetch_array($result2))
{
   if($print==1 || ($level!=1 && $datesub>0))
   {
      if($row2[noscore]=='x') $row2[points]="NTS";
      echo "<tr align=center><td>$row2[place]</td><td align=left>".GetSchoolName($row2[sid],$sport)."</td><td>$row2[points]</td><td>".strtoupper($row2[noscore])." </td></tr>";
      if($row2[place]<=3)
      {
         for($j=0;$j<5;$j++)
         {
            $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
	    echo "<tr><td colspan=2 align=right>".GetStudentInfo($row2[$studvar])."</td><td align=center>".$row2[$pointvar]."</td><td>&nbsp;</td></tr>";
         }
      }
   }
   else
   {
      echo "<tr align=center><td>$row2[place]<input type=hidden name=\"teamplace[$ix]\" id=\"teamplace".$ix."\" value=\"$row2[place]\"></td>";
      echo "<td><select name=\"team[$ix]\" id=\"team".$ix."\" onMouseDown=\"Team2.currentPlace=$ix;\"><option value=''>Select School</option>";
      for($i=0;$i<count($schs[sid]);$i++)
      {
         echo "<option value=\"".$schs[sid][$i]."\"";
         if($row2[sid]==$schs[sid][$i]) echo " selected";
         echo ">".$schs[name][$i]."</option>";
      }
      if($row2[noscore]=='x') $row2[points]="NTS";
      echo "</select></td><td><input type=text size=3 maxlength=4 name=\"teampoints[$ix]\" id=\"teampoints".$ix."\" value=\"$row2[points]\"></td>";
      if($row2[place]>3)
      {
         echo "<td><input type=checkbox name=\"teamnoscore[$ix]\" id=\"teamnoscore".$ix."\" value='x'";
         if($row2[noscore]=='x') echo " checked";
         echo "> No Team Score</td>";
      }
      else
      {
	 echo "<td>&nbsp;</td>";
      }
      echo "</tr>";
      if($row2[place]<=3)	//SHOW 5 SPOTS FOR INDIVIDUAL TEAM MEMBERS
      {
	 for($j=0;$j<5;$j++)
	 {
	    $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
	    echo "<tr><td colspan=2 align=right><select name=\"teamindy[$ix][$j]\" id=\"teamindy".$ix.$j."\"><option value='0'>Select Student</option>";
      	    if($sport=="go_b") //ALLOW BOY OR GIRL TO BE CHOSEN
         	$string=GetPlayers($sport,GetMainSchoolName($row2[sid],$sport),GetFallYear($sport),FALSE,TRUE);
      	    else
         	$string=GetPlayers($sport,GetMainSchoolName($row2[sid],$sport),$fallyear);
      	    if(!ereg("Please",$string))
      	    {
         	$results=split("<result>",$string);
         	for($i=0;$i<count($results);$i++)
         	{
            	   $details=split("<detail>",$results[$i]);
            	   echo "<option value=\"$details[0]\"";
            	   if($row2[$studvar]==$details[0]) echo " selected";
            	   echo ">$details[1]</option>";
         	}
            }
	    if($row2[$pointvar]==999 || $row2[$pointvar]==9999)
	       $curpoints="";
	    else $curpoints=$row2[$pointvar];
	    echo "</select><td align=center><input type=text size=3 maxlength=3 name=\"teamindypoints[$ix][$j]\" id=\"teamindypoints".$ix.$j."\" value=\"".$curpoints."\"></td>";
	    echo "<td><input type=checkbox name=\"teamindydq[$ix][$j]\" id=\"teamindydq".$ix.$j."\" value=\"x\"";
	    if($row2[$pointvar]==999) echo " checked";
	    echo ">DQ&nbsp;&nbsp;<input type=checkbox name=\"teamindywd[$ix][$j]\" id=\"teamindywd".$ix.$j."\" value='x'";
	    if($row2[$pointvar]==9999) echo " checked";
	    echo ">WD</td></tr>";
         }
      }	//END IF PLACES 1st - 3rd
   }
   $ix++; $place=$row2[place];
}
$place++;
if(!$print && !($datesub>0 && $level!=1))
{
while($ix<$teamct)
{
   echo "<tr align=center><td>$place<input type=hidden name=\"teamplace[$ix]\" id=\"teamplace".$ix."\" value=\"$place\"></td>";
   echo "<td><select name=\"team[$ix]\" id=\"team".$ix."\" onMouseDown=\"Team2.currentPlace=$ix;\"><option value=''>Select School</option>";
   for($i=0;$i<count($schs[sid]);$i++)
   {
      echo "<option value=\"".$schs[sid][$i]."\">".$schs[name][$i]."</option>";
   }
   echo "</select></td><td><input type=text size=3 maxlength=4 name=\"teampoints[$ix]\" id=\"teampoints".$ix."\"></td>";
   if($place>3)
      echo "<td><input type=checkbox name=\"teamnoscore[$ix]\" id=\"teamnoscore".$ix."\" value='x'> No Team Score</td>";
   else
      echo "<td>&nbsp;</td>";
   echo "</tr>";
   if($place<=3)     //SHOW 5 SPOTS FOR INDIVIDUAL TEAM MEMBERS
   {
      for($j=0;$j<5;$j++)
      {
         $index=$j+1; $studvar="studentid".$index; $pointvar="points".$index;
         if($sport=="go_b") //ALLOW BOY OR GIRL TO BE CHOSEN
             $string=GetPlayers($sport,GetMainSchoolName($row2[sid],$sport),GetFallYear($sport),FALSE,TRUE);
         else
             $string=GetPlayers($sport,GetMainSchoolName($row2[sid],$sport),GetFallYear($sport));	//STOP
         echo "<tr><td colspan=2 align=right><select name=\"teamindy[$ix][$j]\" id=\"teamindy".$ix.$j."\"><option value='0'>Select Student</option>";
         if(!ereg("Please",$string))
         {
             $results=split("<result>",$string);
             for($i=0;$i<count($results);$i++)
             {
                $details=split("<detail>",$results[$i]);
                echo "<option value=\"$details[0]\">$details[1]</option>";
             }
         }
         echo "</select><td align=center><input type=text size=3 maxlength=3 name=\"teamindypoints[$ix][$j]\" id=\"teamindypoints".$ix.$j."\"></td><td><input type=checkbox name=\"teamindydq[$ix][$j]\" id=\"teamindydq".$ix.$j."\" value='x'>DQ&nbsp;&nbsp;<input type=checkbox name=\"teamindywd[$ix][$j]\" id=\"teamindywd".$ix.$j."\" value='x'>WD</td></tr>";
      }
   } //END IF PLACES 1st - 3rd
   $ix++; $place++;
}
}//END IF EDITING
echo "</table>";
echo "</td></tr>";

echo "</table>";
if(!$print && !($datesub>0 && $level!=1))
   echo "<input type=submit class='fancybutton2' name='save' value=\"Submit Results\">";
else
   echo "<br><br><a href=\"welcome.php?session=$session\">Return Home</a>";

echo "</form>";

echo $end_html;
?>
